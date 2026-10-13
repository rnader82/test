@extends('layouts.app')

@section('title', __('locale.add_warehouse') ?? 'Ajouter un entrepôt')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('warehouses.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.add_warehouse') ?? 'Ajouter un entrepôt' }}</h1>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card card-premium">
            <div class="card-body">
                <form action="{{ route('warehouses.store') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="name" class="form-label fw-bold">{{ __('locale.name') ?? 'Nom' }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="code" class="form-label fw-bold">{{ __('locale.code') ?? 'Code' }}</label>
                            <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}">
                            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label fw-bold">{{ __('locale.address') ?? 'Adresse' }}</label>
                        <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address') }}</textarea>
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                             <label for="odoo_id" class="form-label fw-bold">Odoo ID</label>
                             <input type="number" name="odoo_id" id="odoo_id" class="form-control @error('odoo_id') is-invalid @enderror" value="{{ old('odoo_id') }}">
                             <div class="form-text text-muted">ID de l'emplacement ou de l'entrepôt correspondant dans Odoo.</div>
                             @error('odoo_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-4 d-flex align-items-center gap-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_active">{{ __('locale.active') ?? 'Actif' }}</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('warehouses.index') }}" class="btn btn-outline-secondary">{{ __('locale.cancel') ?? 'Annuler' }}</a>
                        <button type="submit" class="btn btn-primary px-4">{{ __('locale.save') ?? 'Enregistrer' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
