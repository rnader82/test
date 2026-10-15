@extends('layouts.app')
@section('portal-module', 'suppliers')
@section('portal-page', 'edit')
@section('portal-kind', 'form')
@section('portal-family', 'purchasing')
@section('portal-icon', 'cart-flatbed')


@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.btn_edit_supplier') ?? 'Modifier Fournisseur' }}</h1>
    </div>
@endsection

@section('page-actions')
    <button type="submit" form="supplier-form" class="btn btn-primary">
        <i class="bi bi-check-lg me-1"></i> {{ __('locale.btn_save') ?? 'Enregistrer' }}
    </button>
@endsection

@section('content')
<div class="container-xxl flex-grow-1">

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="module-form-card card shadow-sm border-0">
                <div class="card-header bg-warning text-dark d-flex align-items-center justify-content-between border-0">
                    <h5 class="mb-0 text-dark fw-bold"><i class="bi bi-pencil-square me-2 text-dark"></i>{{ __('locale.lbl_information') ?? 'Modifier les informations' }}</h5>
                </div>
                <div class="card-body pt-4">
                    <form action="{{ route('suppliers.update', $supplier) }}" method="POST" id="supplier-form">
                        @csrf
                        @method('PUT')

                        @include('suppliers._siret_lookup')

                        <div class="row mb-3">
                            <div class="col">
                                <div class="form-check form-switch">
                                    <input type="checkbox" name="closed" id="closed" class="form-check-input" value="1" {{ old('closed', $supplier->closed) ? 'checked' : '' }}>
                                    <label for="closed" class="form-check-label fw-semibold">{{ __('locale.lbl_closed') ?? 'Fermé' }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-semibold">{{ __('locale.lbl_name') ?? 'Raison Sociale' }}</label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $supplier->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="company_id" class="form-label fw-semibold">{{ __('locale.company') ?? 'Société' }}</label>
                                <select name="company_id" id="company_id" class="form-select @error('company_id') is-invalid @enderror">
                                    <option value="" {{ empty(old('company_id', $supplier->company_id)) ? 'selected' : '' }}>-- Toutes les sociétés --</option>
                                    @foreach ($companies as $comp)
                                        <option value="{{ $comp->id }}" {{ old('company_id', $supplier->company_id) == $comp->id ? 'selected' : '' }}>
                                            {{ $comp->name }} ({{ $comp->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('company_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label fw-semibold">{{ __('locale.lbl_type') ?? 'Type' }}</label>
                                <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="fabricant" {{ old('type', $supplier->type) === 'fabricant' ? 'selected' : '' }}>{{ __('locale.fabricant') ?? 'Fabricant' }}</option>
                                    <option value="redistributeur" {{ old('type', $supplier->type) === 'redistributeur' ? 'selected' : '' }}>{{ __('locale.redistributeur') ?? 'Redistributeur' }}</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nature" class="form-label fw-semibold">Nature</label>
                                <input type="text" name="nature" id="nature" class="form-control @error('nature') is-invalid @enderror" value="{{ old('nature', $supplier->nature) }}">
                                @error('nature')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cegid_id" class="form-label fw-semibold">Code CEGID</label>
                                <input type="text" name="cegid_id" id="cegid_id" class="form-control @error('cegid_id') is-invalid @enderror" value="{{ old('cegid_id', $supplier->cegid_id) }}">
                                @error('cegid_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="odoo_id" class="form-label fw-semibold">{{ __('locale.lbl_odoo_id') ?? 'ID Odoo' }}</label>
                                <input type="number" name="odoo_id" id="odoo_id" class="form-control @error('odoo_id') is-invalid @enderror" value="{{ old('odoo_id', $supplier->odoo_id) }}">
                                @error('odoo_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="contact_email" class="form-label fw-semibold">{{ __('locale.lbl_email') ?? 'Email de contact' }}</label>
                                <input type="email" name="contact_email" id="contact_email" class="form-control @error('contact_email') is-invalid @enderror" value="{{ old('contact_email', $supplier->contact_email) }}">
                                @error('contact_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="contact_phone" class="form-label fw-semibold">{{ __('locale.lbl_phone') ?? 'Téléphone de contact' }}</label>
                                <input type="text" name="contact_phone" id="contact_phone" class="form-control @error('contact_phone') is-invalid @enderror" value="{{ old('contact_phone', $supplier->contact_phone) }}">
                                @error('contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="phone_2" class="form-label fw-semibold">Téléphone n°2</label>
                                <input type="text" name="phone_2" id="phone_2" class="form-control @error('phone_2') is-invalid @enderror" value="{{ old('phone_2', $supplier->phone_2) }}">
                                @error('phone_2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="address" class="form-label fw-semibold">{{ __('locale.address') ?? 'Adresse 1' }}</label>
                                <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $supplier->address) }}">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="adresse_2" class="form-label fw-semibold">Adresse 2</label>
                                <input type="text" name="adresse_2" id="adresse_2" class="form-control @error('adresse_2') is-invalid @enderror" value="{{ old('adresse_2', $supplier->adresse_2) }}">
                                @error('adresse_2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="adresse_3" class="form-label fw-semibold">Adresse 3</label>
                                <input type="text" name="adresse_3" id="adresse_3" class="form-control @error('adresse_3') is-invalid @enderror" value="{{ old('adresse_3', $supplier->adresse_3) }}">
                                @error('adresse_3')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="code_postal" class="form-label fw-semibold">Code postal</label>
                                <input type="text" name="code_postal" id="code_postal" class="form-control @error('code_postal') is-invalid @enderror" value="{{ old('code_postal', $supplier->code_postal) }}">
                                @error('code_postal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="ville" class="form-label fw-semibold">Ville</label>
                                <input type="text" name="ville" id="ville" class="form-control @error('ville') is-invalid @enderror" value="{{ old('ville', $supplier->ville) }}">
                                @error('ville')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="country" class="form-label fw-semibold">Pays</label>
                                <select name="country" id="country" class="form-select select2 @error('country') is-invalid @enderror">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach ($countries as $c)
                                        <option value="{{ $c }}" {{ old('country', $supplier->country) === $c ? 'selected' : '' }}>{{ $c }}</option>
                                    @endforeach
                                </select>
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="langue" class="form-label fw-semibold">{{ __('locale.lbl_langue') ?? 'Langue' }}</label>
                                <select name="langue" id="langue" class="form-select select2 @error('langue') is-invalid @enderror">
                                    @php
                                        $suppLang = old('langue', $supplier->langue ?? 'Français');
                                        $allLangs = isset($languages) ? $languages : \App\Models\Language::where('is_active', true)->orderBy('name')->get();
                                    @endphp
                                    @foreach($allLangs as $lOpt)
                                        <option value="{{ $lOpt->name }}" @selected($suppLang === $lOpt->name)>{{ $lOpt->name }}</option>
                                    @endforeach
                                    @if(!empty($suppLang) && !$allLangs->contains('name', $suppLang))
                                        <option value="{{ $suppLang }}" selected>{{ $suppLang }}</option>
                                    @endif
                                </select>
                                @error('langue')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="siren_siret" class="form-label fw-semibold d-flex align-items-center justify-content-between">
                                    <span>SIREN / SIRET</span>
                                    <small class="text-muted fw-normal">
                                        <i class="bi bi-patch-check text-primary me-1"></i>api.gouv.fr
                                    </small>
                                </label>
                                <div class="input-group">
                                    <input type="text" name="siren_siret" id="siren_siret" class="form-control @error('siren_siret') is-invalid @enderror" value="{{ old('siren_siret', $supplier->siren_siret) }}" placeholder="9 ou 14 chiffres">
                                    <button class="btn btn-outline-primary btn-trigger-siret-search" type="button" title="Rechercher auprès des registres officiels de l'État">
                                        <i class="bi bi-search me-1"></i>Rechercher
                                    </button>
                                </div>
                                @error('siren_siret')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="client_code_supplier" class="form-label fw-semibold">Code client chez le fournisseur</label>
                                <input type="text" name="client_code_supplier" id="client_code_supplier" class="form-control @error('client_code_supplier') is-invalid @enderror" value="{{ old('client_code_supplier', $supplier->client_code_supplier) }}">
                                @error('client_code_supplier')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="condition_reglement_id" class="form-label fw-semibold">Conditions de règlement</label>
                                <select name="condition_reglement_id" id="condition_reglement_id" class="form-select @error('condition_reglement_id') is-invalid @enderror">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach ($conditionsReglement as $cond)
                                        <option value="{{ $cond->id }}" {{ old('condition_reglement_id', $supplier->condition_reglement_id) == $cond->id ? 'selected' : '' }}>{{ $cond->name }}</option>
                                    @endforeach
                                </select>
                                @error('condition_reglement_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="code_nif" class="form-label fw-semibold">Code NIF</label>
                                <input type="text" name="code_nif" id="code_nif" class="form-control @error('code_nif') is-invalid @enderror" value="{{ old('code_nif', $supplier->code_nif) }}">
                                @error('code_nif')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="code_naf" class="form-label fw-semibold">Code NAF</label>
                                <input type="text" name="code_naf" id="code_naf" class="form-control @error('code_naf') is-invalid @enderror" value="{{ old('code_naf', $supplier->code_naf) }}">
                                @error('code_naf')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="regime_tva_id" class="form-label fw-semibold">Régime TVA</label>
                                <select name="regime_tva_id" id="regime_tva_id" class="form-select @error('regime_tva_id') is-invalid @enderror">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach ($regimesTva as $regime)
                                        <option value="{{ $regime->id }}" {{ old('regime_tva_id', $supplier->regime_tva_id) == $regime->id ? 'selected' : '' }}>{{ $regime->name }} ({{ $regime->code }})</option>
                                    @endforeach
                                </select>
                                @error('regime_tva_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="bank_name" class="form-label fw-semibold">{{ __('locale.lbl_bank_name') }}</label>
                                <input type="text" name="bank_name" id="bank_name" class="form-control @error('bank_name') is-invalid @enderror" value="{{ old('bank_name', $supplier->bank_name) }}">
                                @error('bank_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="iban" class="form-label fw-semibold">{{ __('locale.lbl_iban') }}</label>
                                <input type="text" name="iban" id="iban" class="form-control @error('iban') is-invalid @enderror" value="{{ old('iban', $supplier->iban) }}">
                                @error('iban')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="bic" class="form-label fw-semibold">{{ __('locale.lbl_bic') }}</label>
                                <input type="text" name="bic" id="bic" class="form-control @error('bic') is-invalid @enderror" value="{{ old('bic', $supplier->bic) }}">
                                @error('bic')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label fw-semibold">{{ __('locale.lbl_notes') ?? 'Notes' }}</label>
                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $supplier->notes) }}</textarea>
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

@section('scripts')
<script>
    $(document).ready(function() {
        $('#country').select2({
            placeholder: '-- Sélectionner --',
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endsection
