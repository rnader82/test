@extends('layouts.app')

@section('title', __('locale.add_accounting_family') ?? 'Ajouter une famille comptable')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('accounting_families.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.add_accounting_family') ?? 'Ajouter une famille comptable' }}</h1>
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="card card-premium p-4">
            <form action="{{ route('accounting_families.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="code_cegid" class="form-label fw-semibold">{{ __('locale.code_cegid') }}</label>
                        <input type="text" class="form-control @error('code_cegid') is-invalid @enderror" id="code_cegid" name="code_cegid" value="{{ old('code_cegid') }}" required>
                        @error('code_cegid')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="family_name" class="form-label fw-semibold">{{ __('locale.family_name') }}</label>
                        <input type="text" class="form-control @error('family_name') is-invalid @enderror" id="family_name" name="family_name" value="{{ old('family_name') }}" required>
                        @error('family_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="odoo_category_id" class="form-label fw-semibold">{{ __('locale.odoo_category') }}</label>
                        <select name="odoo_category_id" id="odoo_category_id" class="form-select select2">
                            <option value="">Sélectionner la catégorie Odoo</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('odoo_category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('odoo_category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="fiscal_position" class="form-label fw-semibold">{{ __('locale.fiscal_position_name_text') }}</label>
                        <input type="text" class="form-control @error('fiscal_position') is-invalid @enderror" id="fiscal_position" name="fiscal_position" value="{{ old('fiscal_position') }}" required>
                        @error('fiscal_position')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="fiscal_position_id" class="form-label fw-semibold">{{ __('locale.fiscal_position_table_link') }}</label>
                        <select name="fiscal_position_id" id="fiscal_position_id" class="form-select select2">
                            <option value="">Sélectionner la position fiscale</option>
                            @foreach($fiscalPositions as $fp)
                                <option value="{{ $fp->id }}" {{ old('fiscal_position_id') == $fp->id ? 'selected' : '' }}>
                                    {{ $fp->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('fiscal_position_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                     <div class="col-md-6">
                        <label for="account_number" class="form-label fw-semibold">{{ __('locale.account_number') }}</label>
                        <input type="text" class="form-control @error('account_number') is-invalid @enderror" id="account_number" name="account_number" value="{{ old('account_number') }}">
                        @error('account_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="tax_name" class="form-label fw-semibold">{{ __('locale.tax_name') }}</label>
                        <input type="text" class="form-control @error('tax_name') is-invalid @enderror" id="tax_name" name="tax_name" value="{{ old('tax_name') }}">
                        @error('tax_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="tva_account" class="form-label fw-semibold">Compte TVA Vente</label>
                        <input type="text" class="form-control @error('tva_account') is-invalid @enderror" id="tva_account" name="tva_account" value="{{ old('tva_account') }}">
                        @error('tva_account')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="taxes_id" class="form-label fw-semibold">Taxes de Vente Odoo</label>
                        <select name="taxes_id[]" id="taxes_id" class="form-select select2" multiple>
                            @foreach($customerTaxes as $tax)
                                <option value="{{ $tax->odoo_id }}" {{ in_array($tax->odoo_id, (array)old('taxes_id', [])) ? 'selected' : '' }}>
                                    {{ $tax->name }} ({{ $tax->company_name ?? 'Global' }})
                                </option>
                            @endforeach
                        </select>
                        @error('taxes_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="purchase_account_number" class="form-label fw-semibold">{{ __('locale.purchase_account_number') }}</label>
                        <input type="text" class="form-control @error('purchase_account_number') is-invalid @enderror" id="purchase_account_number" name="purchase_account_number" value="{{ old('purchase_account_number') }}">
                        @error('purchase_account_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="supplier_tax_name" class="form-label fw-semibold">{{ __('locale.supplier_tax_name') }}</label>
                        <input type="text" class="form-control @error('supplier_tax_name') is-invalid @enderror" id="supplier_tax_name" name="supplier_tax_name" value="{{ old('supplier_tax_name') }}">
                        @error('supplier_tax_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="purchase_tva_account" class="form-label fw-semibold">Compte TVA Achat</label>
                        <input type="text" class="form-control @error('purchase_tva_account') is-invalid @enderror" id="purchase_tva_account" name="purchase_tva_account" value="{{ old('purchase_tva_account') }}">
                        @error('purchase_tva_account')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="supplier_taxes_id" class="form-label fw-semibold">Taxes d'Achat Odoo</label>
                        <select name="supplier_taxes_id[]" id="supplier_taxes_id" class="form-select select2" multiple>
                            @foreach($supplierTaxes as $tax)
                                <option value="{{ $tax->odoo_id }}" {{ in_array($tax->odoo_id, (array)old('supplier_taxes_id', [])) ? 'selected' : '' }}>
                                    {{ $tax->name }} ({{ $tax->company_name ?? 'Global' }})
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_taxes_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="account_stock_variation_id" class="form-label fw-semibold">Compte Variation de Stock</label>
                        <input type="text" class="form-control @error('account_stock_variation_id') is-invalid @enderror" id="account_stock_variation_id" name="account_stock_variation_id" value="{{ old('account_stock_variation_id') }}">
                        @error('account_stock_variation_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="property_stock_valuation_account_id" class="form-label fw-semibold">Compte Valorisation de Stock</label>
                        <input type="text" class="form-control @error('property_stock_valuation_account_id') is-invalid @enderror" id="property_stock_valuation_account_id" name="property_stock_valuation_account_id" value="{{ old('property_stock_valuation_account_id') }}">
                        @error('property_stock_valuation_account_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="type" class="form-label fw-semibold">HA ou VT (Flux principal)</label>
                        <select name="type" id="type" class="form-select">
                            <option value="">Sélectionner</option>
                            <option value="HA" {{ old('type') == 'HA' ? 'selected' : '' }}>HA (Achat)</option>
                            <option value="VT" {{ old('type') == 'VT' ? 'selected' : '' }}>VT (Vente)</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <!-- Spacer to balance row -->
                    </div>

                    <div class="col-12">
                        <label for="legal_mention" class="form-label fw-semibold">Mention légale</label>
                        <textarea class="form-control @error('legal_mention') is-invalid @enderror" id="legal_mention" name="legal_mention" rows="2">{{ old('legal_mention') }}</textarea>
                        @error('legal_mention')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="comment" class="form-label fw-semibold">Commentaire</label>
                        <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" rows="2">{{ old('comment') }}</textarea>
                        @error('comment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">{{ __('locale.save') ?? 'Enregistrer' }}</button>
                    <a href="{{ route('accounting_families.index') }}" class="btn btn-secondary">{{ __('locale.cancel') ?? 'Annuler' }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
