@extends('layouts.app')
@section('portal-module', 'countries')
@section('portal-page', 'create')
@section('portal-kind', 'form')
@section('portal-family', 'admin')
@section('portal-icon', 'gear')


@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('countries.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">Nouveau Pays</h1>
    </div>
@endsection

@section('page-actions')
    <button type="submit" form="country-form" class="btn btn-primary">
        <i class="bi bi-check-lg me-1"></i> Enregistrer
    </button>
@endsection

@section('content')
<div class="container-xxl flex-grow-1">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="module-form-card card shadow-sm border-0">
                <div class="card-header bg-primary text-white border-0">
                    <h5 class="mb-0 text-white"><i class="bi bi-globe me-2"></i>Informations du Pays</h5>
                </div>
                <div class="card-body pt-4">
                    <form action="{{ route('countries.store') }}" method="POST" id="country-form">
                        @csrf

                        <div class="mb-3">
                            <label for="name_fr" class="form-label fw-semibold">Nom en français <span class="text-danger">*</span></label>
                            <input type="text" name="name_fr" id="name_fr"
                                   class="form-control @error('name_fr') is-invalid @enderror"
                                   value="{{ old('name_fr') }}" required>
                            @error('name_fr')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name_en" class="form-label fw-semibold">Nom en anglais <span class="text-danger">*</span></label>
                            <input type="text" name="name_en" id="name_en"
                                   class="form-control @error('name_en') is-invalid @enderror"
                                   value="{{ old('name_en') }}" required>
                            @error('name_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Utilisé pour la correspondance avec Odoo (<code>res.country</code>).</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="iso_code" class="form-label fw-semibold">Code ISO (2 lettres)</label>
                                <input type="text" name="iso_code" id="iso_code"
                                       class="form-control font-monospace text-uppercase @error('iso_code') is-invalid @enderror"
                                       value="{{ old('iso_code') }}" maxlength="2" placeholder="FR">
                                @error('iso_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="odoo_id" class="form-label fw-semibold">Odoo ID</label>
                                <input type="number" name="odoo_id" id="odoo_id"
                                       class="form-control font-monospace @error('odoo_id') is-invalid @enderror"
                                       value="{{ old('odoo_id') }}" min="1">
                                @error('odoo_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Remplir automatiquement via Odoo Settings.</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-2">
                            <a href="{{ route('countries.index') }}" class="btn btn-outline-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary px-5 fw-bold">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
