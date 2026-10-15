@extends('layouts.app')
@section('portal-module', 'purchases')
@section('portal-page', 'create')
@section('portal-kind', 'form')
@section('portal-family', 'purchasing')
@section('portal-icon', 'cart-flatbed')


@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.btn_new_purchase') ?? 'Nouvel Achat' }}</h1>
    </div>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 mt-4">

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="module-form-card card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between border-0">
                    <h5 class="mb-0 text-white"><i class="bi bi-cart-plus me-2"></i>{{ __('locale.lbl_information') ?? 'Informations de l\'Achat' }}</h5>
                </div>
                <div class="card-body pt-4">
                    <form action="{{ route('purchases.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="supplier_id" class="form-label fw-semibold">{{ __('locale.lbl_supplier') ?? 'Fournisseur' }}</label>
                            <div class="input-group">
                                <select name="supplier_id" id="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                                    <option value="">{{ __('locale.select_supplier') ?? 'Sélectionner un fournisseur...' }}</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id', request('supplier_id')) == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }} ({{ $supplier->type }})</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#quickAddSupplierModal">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="reference" class="form-label fw-semibold">{{ __('locale.lbl_reference') ?? 'Référence' }}</label>
                                <input type="text" name="reference" id="reference" class="form-control @error('reference') is-invalid @enderror" value="{{ old('reference') }}" required>
                                @error('reference')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="expected_delivery_date" class="form-label fw-semibold">{{ __('locale.expected_delivery') ?? 'Livraison Prévue' }}</label>
                                <input type="date" name="expected_delivery_date" id="expected_delivery_date" class="form-control @error('expected_delivery_date') is-invalid @enderror" value="{{ old('expected_delivery_date') }}">
                                @error('expected_delivery_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label fw-semibold">{{ __('locale.lbl_notes') ?? 'Notes' }}</label>
                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary px-5 fw-bold">
                                {{ __('locale.btn_save') ?? 'Créer et continuer' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Quick Add Supplier -->
<div class="modal fade" id="quickAddSupplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="quickSupplierForm">
            @csrf
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title fw-bold text-white"><i class="bi bi-person-plus me-2"></i>{{ __('locale.btn_add_supplier') ?? 'Ajouter un fournisseur' }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('locale.lbl_name') ?? 'Raison Sociale' }}</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('locale.lbl_type') ?? 'Type' }}</label>
                        <select name="type" class="form-select" required>
                            <option value="fabricant">{{ __('locale.fabricant') ?? 'Fabricant' }}</option>
                            <option value="redistributeur">{{ __('locale.redistributeur') ?? 'Redistributeur' }}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">{{ __('locale.btn_cancel') ?? 'Annuler' }}</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">{{ __('locale.btn_save') ?? 'Enregistrer' }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#quickSupplierForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const submitBtn = form.find('button[type="submit"]');
            
            submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Enregistrement...');
            
            $.ajax({
                url: "{{ route('suppliers.store') }}",
                method: "POST",
                data: form.serialize(),
                success: function(response) {
                    // Add to dropdown and select it
                    const newOption = new Option(response.name + ' (' + response.type + ')', response.id, true, true);
                    $('#supplier_id').append(newOption).trigger('change');
                    
                    // Close modal and reset form
                    $('#quickAddSupplierModal').modal('hide');
                    form[0].reset();
                    submitBtn.prop('disabled', false).html("{{ __('locale.btn_save') ?? 'Enregistrer' }}");
                },
                error: function(xhr) {
                    alert('Erreur lors de la création du fournisseur');
                    submitBtn.prop('disabled', false).html("{{ __('locale.btn_save') ?? 'Enregistrer' }}");
                }
            });
        });
    });
</script>
@endsection
