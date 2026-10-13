@extends('layouts.app')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.btn_edit_purchase') ?? 'Modifier Achat' }} : <span class="text-primary">{{ $purchase->reference }}</span></h1>
    </div>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 mt-4">

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning text-dark d-flex align-items-center justify-content-between border-0">
                    <h5 class="mb-0 text-dark fw-bold"><i class="bi bi-pencil-square me-2"></i>{{ __('locale.lbl_information') ?? 'Modifier les informations' }}</h5>
                </div>
                <div class="card-body pt-4">
                    <form action="{{ route('purchases.update', $purchase) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="supplier_id" class="form-label fw-semibold">{{ __('locale.lbl_supplier') ?? 'Fournisseur' }}</label>
                            <select name="supplier_id" id="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id', $purchase->supplier_id) == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="reference" class="form-label fw-semibold">{{ __('locale.lbl_reference') ?? 'Référence' }}</label>
                                <input type="text" name="reference" id="reference" class="form-control @error('reference') is-invalid @enderror" value="{{ old('reference', $purchase->reference) }}" required>
                                @error('reference')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label fw-semibold">{{ __('locale.lbl_status') ?? 'Statut' }}</label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="pending" {{ old('status', $purchase->status) === 'pending' ? 'selected' : '' }}>{{ __('locale.pending') ?? 'En attente' }}</option>
                                    <option value="received" {{ old('status', $purchase->status) === 'received' ? 'selected' : '' }}>{{ __('locale.received') ?? 'Reçu' }}</option>
                                    <option value="cancelled" {{ old('status', $purchase->status) === 'cancelled' ? 'selected' : '' }}>{{ __('locale.cancelled') ?? 'Annulé' }}</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="expected_delivery_date" class="form-label fw-semibold">{{ __('locale.expected_delivery') ?? 'Livraison Prévue' }}</label>
                            <input type="date" name="expected_delivery_date" id="expected_delivery_date" class="form-control @error('expected_delivery_date') is-invalid @enderror" value="{{ old('expected_delivery_date', $purchase->expected_delivery_date ? $purchase->expected_delivery_date->format('Y-m-d') : '') }}">
                            <div class="form-text text-primary">
                                <i class="bi bi-info-circle me-1"></i>Cette date est automatiquement synchronisée avec la livraison la plus proche de vos équipements.
                            </div>
                            @error('expected_delivery_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label fw-semibold">{{ __('locale.lbl_notes') ?? 'Notes' }}</label>
                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $purchase->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-warning px-5 fw-bold">
                                {{ __('locale.btn_update') ?? 'Mettre à jour' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
