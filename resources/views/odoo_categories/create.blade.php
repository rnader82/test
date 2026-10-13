@extends('layouts.app')

@section('title', __('locale.add_odoo_category') ?? 'Ajouter une catégorie Odoo')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('odoo_categories.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.add_odoo_category') ?? 'Ajouter une catégorie Odoo' }}</h1>
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="card card-premium p-4">
            <form action="{{ route('odoo_categories.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="odoo_id" class="form-label fw-semibold">{{ __('locale.odoo_id') }} <span class="text-danger">*</span></label>
                        <input type="number" min="1" class="form-control @error('odoo_id') is-invalid @enderror" id="odoo_id" name="odoo_id" value="{{ old('odoo_id') }}" required placeholder="{{ __('locale.eg_hint', ['example' => '15']) ?? 'Ex: 15' }}">
                        @error('odoo_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">{{ __('locale.odoo_category_id_hint') }}</div>
                    </div>

                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold">{{ __('locale.name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="Ex: {{ __('locale.eg_servers_network') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="parent_odoo_id" class="form-label fw-semibold">{{ __('locale.parent_category') }}</label>
                        <select class="form-select @error('parent_odoo_id') is-invalid @enderror" id="parent_odoo_id" name="parent_odoo_id">
                            <option value="">{{ __('locale.none_root') }}</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->odoo_id }}" {{ old('parent_odoo_id') == $parent->odoo_id ? 'selected' : '' }}>
                                    {{ $parent->name }} (ID: {{ $parent->odoo_id }})
                                </option>
                            @endforeach
                        </select>
                        @error('parent_odoo_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="parent_path" class="form-label fw-semibold">{{ __('locale.parent_path_optional') }}</label>
                        <input type="text" class="form-control @error('parent_path') is-invalid @enderror" id="parent_path" name="parent_path" value="{{ old('parent_path') }}" placeholder="Ex: 1/5/12/">
                        @error('parent_path')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">{{ __('locale.parent_path_hint') }}</div>
                    </div>

                    <div class="col-md-6">
                        <label for="sort_order" class="form-label fw-semibold">{{ __('locale.sort_order') ?? 'Ordre d\'affichage' }}</label>
                        <input type="number" min="0" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order') }}" placeholder="Ex: 1, 2, 3...">
                        @error('sort_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">{{ __('locale.sort_order_default_hint') ?? 'Si laissé vide, l\'identifiant sera utilisé par défaut. Si un numéro existant est saisi, les suivants seront décalés.' }}</div>
                    </div>

                    <div class="col-md-6">
                        <label for="taxes_id" class="form-label fw-semibold">{{ __('locale.customer_taxes') }}</label>
                        <select name="taxes_id[]" id="taxes_id" class="form-select select2 @error('taxes_id') is-invalid @enderror" multiple data-placeholder="{{ __('locale.select_taxes') ?? 'Sélectionner des taxes...' }}">
                            @foreach($customerTaxes as $tax)
                                <option value="{{ $tax->odoo_id }}" {{ in_array($tax->odoo_id, (array)old('taxes_id', [])) ? 'selected' : '' }}>
                                    {{ $tax->name }} ({{ $tax->company_name ?? 'Global' }})
                                </option>
                            @endforeach
                        </select>
                        @error('taxes_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">{{ __('locale.category_customer_taxes_hint') }}</div>
                    </div>

                    <div class="col-md-6">
                        <label for="supplier_taxes_id" class="form-label fw-semibold">{{ __('locale.supplier_taxes') }}</label>
                        <select name="supplier_taxes_id[]" id="supplier_taxes_id" class="form-select select2 @error('supplier_taxes_id') is-invalid @enderror" multiple data-placeholder="{{ __('locale.select_taxes') ?? 'Sélectionner des taxes...' }}">
                            @foreach($supplierTaxes as $tax)
                                <option value="{{ $tax->odoo_id }}" {{ in_array($tax->odoo_id, (array)old('supplier_taxes_id', [])) ? 'selected' : '' }}>
                                    {{ $tax->name }} ({{ $tax->company_name ?? 'Global' }})
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_taxes_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">{{ __('locale.category_supplier_taxes_hint') }}</div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>{{ __('locale.save') ?? 'Enregistrer' }}
                    </button>
                    <a href="{{ route('odoo_categories.index') }}" class="btn btn-secondary">
                        {{ __('locale.cancel') ?? 'Annuler' }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2').select2({
        width: '100%',
        placeholder: function() {
            return $(this).data('placeholder') || '';
        }
    });
});
</script>
@endpush
