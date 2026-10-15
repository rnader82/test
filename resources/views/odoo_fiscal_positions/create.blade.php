@extends('layouts.app')
@section('portal-module', 'odoo_fiscal_positions')
@section('portal-page', 'create')
@section('portal-kind', 'form')
@section('portal-family', 'catalog')
@section('portal-icon', 'tags')


@section('title', __('locale.add_fiscal_position') ?? 'Ajouter une position fiscale')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('odoo_fiscal_positions.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.add_fiscal_position') ?? 'Ajouter une position fiscale' }}</h1>
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="module-form-card card card-premium p-4">
            <form action="{{ route('odoo_fiscal_positions.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="odoo_id" class="form-label fw-semibold">{{ __('locale.odoo_id') }}</label>
                        <input type="number" class="form-control @error('odoo_id') is-invalid @enderror" id="odoo_id" name="odoo_id" value="{{ old('odoo_id') }}" required>
                        @error('odoo_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold">{{ __('locale.name') }}</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="odoo_company_id" class="form-label fw-semibold">{{ __('locale.odoo_company_id') }}</label>
                        <input type="number" class="form-control @error('odoo_company_id') is-invalid @enderror" id="odoo_company_id" name="odoo_company_id" value="{{ old('odoo_company_id') }}">
                        @error('odoo_company_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="company_name" class="form-label fw-semibold">{{ __('locale.company_name') }}</label>
                        <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name" name="company_name" value="{{ old('company_name') }}">
                        @error('company_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">{{ __('locale.save') ?? 'Enregistrer' }}</button>
                    <a href="{{ route('odoo_fiscal_positions.index') }}" class="btn btn-secondary">{{ __('locale.cancel') ?? 'Annuler' }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
