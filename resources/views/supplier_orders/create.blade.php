@extends('layouts.app')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('supplier-orders.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">Nouvelle Commande Fournisseur</h1>
    </div>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto animate__animated animate__fadeIn">
            <div class="card card-premium">
                <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between border-0">
                    <h5 class="mb-0 text-white"><i class="bi bi-cart-plus me-2 text-white"></i>Informations de la Commande</h5>
                </div>
                <div class="card-body pt-4">
                    <form action="{{ route('supplier-orders.store') }}" method="POST">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="supplier_id" class="form-label fw-semibold">Fournisseur</label>
                                <select name="supplier_id" id="supplier_id" class="form-select select2 @error('supplier_id') is-invalid @enderror" data-placeholder="Sélectionner un fournisseur...">
                                    <option value=""></option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }} ({{ $supplier->cegid_id ?? 'Pas de CEGID ID' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="numero" class="form-label fw-semibold">Numéro de Commande</label>
                                <input type="text" name="numero" id="numero" class="form-control @error('numero') is-invalid @enderror" value="{{ old('numero') }}" placeholder="Ex: CF-2026-0001" required>
                                @error('numero')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="ref_interne" class="form-label fw-semibold">Référence Interne</label>
                                <input type="text" name="ref_interne" id="ref_interne" class="form-control @error('ref_interne') is-invalid @enderror" value="{{ old('ref_interne') }}">
                                @error('ref_interne')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="devise" class="form-label fw-semibold">Devise</label>
                                <input type="text" name="devise" id="devise" class="form-control @error('devise') is-invalid @enderror" value="{{ old('devise', 'EUR') }}" placeholder="EUR, USD, etc.">
                                @error('devise')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="date_piece" class="form-label fw-semibold">Date de la Pièce</label>
                                <input type="date" name="date_piece" id="date_piece" class="form-control @error('date_piece') is-invalid @enderror" value="{{ old('date_piece', date('Y-m-d')) }}">
                                @error('date_piece')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="date_livraison" class="form-label fw-semibold">Date de Livraison</label>
                                <input type="date" name="date_livraison" id="date_livraison" class="form-control @error('date_livraison') is-invalid @enderror" value="{{ old('date_livraison') }}">
                                @error('date_livraison')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="form-check form-switch mt-3">
                                    <input type="hidden" name="modifiable" value="0">
                                    <input class="form-check-input" type="checkbox" name="modifiable" id="modifiable" value="1" {{ old('modifiable', '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="modifiable">Commande Modifiable</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label fw-semibold">Notes / Observations</label>
                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Notes optionnelles...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('supplier-orders.index') }}" class="btn btn-outline-secondary px-4">Annuler</a>
                            <button type="submit" class="btn btn-primary px-5 fw-bold">Créer et continuer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%',
            allowClear: true
        });
    });
</script>
@endsection
