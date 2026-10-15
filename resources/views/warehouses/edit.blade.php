@extends('layouts.app')
@section('portal-module', 'warehouses')
@section('portal-page', 'edit')
@section('portal-kind', 'form')
@section('portal-family', 'catalog')
@section('portal-icon', 'tags')


@section('title', __('locale.edit_warehouse') ?? 'Modifier l\'entrepôt')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('warehouses.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.edit_warehouse') ?? 'Modifier l\'entrepôt' }}: {{ $warehouse->name }}</h1>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="module-form-card card card-premium">
            <div class="card-body">
                <form action="{{ route('warehouses.update', $warehouse->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="name" class="form-label fw-bold">{{ __('locale.name') ?? 'Nom' }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $warehouse->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="code" class="form-label fw-bold">{{ __('locale.code') ?? 'Code' }}</label>
                            <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $warehouse->code) }}">
                            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label fw-bold">{{ __('locale.address') ?? 'Adresse' }}</label>
                        <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address', $warehouse->address) }}</textarea>
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                             <label for="odoo_id" class="form-label fw-bold">Odoo ID</label>
                             <input type="number" name="odoo_id" id="odoo_id" class="form-control" value="{{ old('odoo_id', $warehouse->odoo_id) }}">
                             <div class="form-text text-muted">ID de l'emplacement ou de l'entrepôt correspondant dans Odoo.</div>
                        </div>
                    </div>

                    <div class="mb-4 d-flex align-items-center gap-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $warehouse->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_active">{{ __('locale.active') ?? 'Actif' }}</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('warehouses.index') }}" class="btn btn-outline-secondary">{{ __('locale.cancel') ?? 'Annuler' }}</a>
                        <button type="submit" class="btn btn-primary px-4">{{ __('locale.update') ?? 'Mettre à jour' }}</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="mt-4">
            <div class="module-form-card card card-premium">
                <div class="card-header border-bottom">
                    <h5 class="card-title mb-0">{{ __('locale.stock_in_this_warehouse') ?? 'Articles stockés dans cet entrepôt' }}</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">{{ __('locale.reference') }}</th>
                                    <th>{{ __('locale.name') }}</th>
                                    <th class="text-end pe-4">{{ __('locale.quantity') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($warehouse->equipments()->limit(10)->get() as $equipment)
                                    <tr>
                                        <td class="ps-4"><a href="{{ route('equipments.show', $equipment->id) }}">{{ $equipment->reference }}</a></td>
                                        <td>{{ $equipment->name }}</td>
                                        <td class="text-end pe-4"><span class="fw-bold">{{ $equipment->pivot->quantity }}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-3 text-muted">Aucun article dans cet entrepôt.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($warehouse->equipments()->count() > 10)
                        <div class="p-3 text-center border-top">
                            <small class="text-muted">Affichage des 10 premiers articles seulement.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
