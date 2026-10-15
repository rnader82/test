@extends('layouts.app')
@section('portal-module', 'clients')
@section('portal-page', 'edit')
@section('portal-kind', 'form')
@section('portal-family', 'relations')
@section('portal-icon', 'users')


@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('clients.show', $client->id) }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <h1 class="mb-0 fw-bold text-secondary fs-4">{{ __('locale.lbl_edit_client_title') ?? 'Modifier le Client' }}</h1>
    </div>
@endsection

@push('styles')
<style>
    .content-wrapper {
        min-height: calc(100vh - 75px);
        display: flex;
        flex-direction: column;
    }
    .container-xxl {
        height: 100%;
        display: flex;
        flex-direction: column;
        padding-top: 0.25rem;
        padding-bottom: 0.25rem;
    }
    form.eq-form-layout {
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
    }
    .odoo-sheet {
        background: #ffffff;
        color: #1e293b;
        border: 1px solid #dbe2ea;
        border-radius: 10px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        padding: 0.75rem 1.25rem;
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
    }
    .tab-content {
        flex: 1 1 auto;
        padding-right: 0.25rem;
        padding-bottom: 0.5rem;
    }
    .odoo-top-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b !important;
        letter-spacing: 0.5px;
    }
    .odoo-title-input {
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a !important;
        background-color: #ffffff !important;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 0.25rem 0.6rem;
    }
    .odoo-title-input:focus {
        border-color: var(--bs-secondary, #6c757d);
        box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.15);
    }
    .odoo-nav-tabs {
        border-bottom: 2px solid #e2e8f0;
        gap: 0.25rem;
        margin-bottom: 0.75rem;
        flex-shrink: 0;
        overflow-x: auto;
        overflow-y: hidden;
        scrollbar-width: none !important;
        -ms-overflow-style: none !important;
    }
    .odoo-nav-tabs::-webkit-scrollbar {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
    }
    .odoo-nav-tabs .nav-link {
        border: none !important;
        color: #475569 !important;
        font-weight: 600;
        font-size: 0.8rem;
        padding: 0.35rem 0.85rem;
        border-radius: 0;
        transition: all 0.15s ease;
        outline: none !important;
        box-shadow: none !important;
        background-color: transparent !important;
    }
    .odoo-nav-tabs .nav-link:hover,
    .odoo-nav-tabs .nav-link:focus {
        color: var(--bs-secondary, #6c757d) !important;
        background-color: rgba(108, 117, 125, 0.08) !important;
    }
    .odoo-nav-tabs .nav-link.active,
    .odoo-nav-tabs .nav-link.active:focus,
    .odoo-nav-tabs .nav-link.active:hover,
    .odoo-nav-tabs .nav-link.active:active,
    .odoo-nav-tabs .nav-link.active:focus-visible {
        color: var(--bs-secondary, #6c757d) !important;
        background-color: transparent !important;
        border-bottom: 3px solid var(--bs-secondary, #6c757d) !important;
        font-weight: 700 !important;
        outline: none !important;
        box-shadow: none !important;
    }
    .odoo-field-row {
        display: flex;
        align-items: center;
        margin-bottom: 0.4rem;
        font-size: 0.8rem;
    }
    .odoo-field-label {
        width: 38%;
        font-weight: 600;
        color: #475569 !important;
        padding-right: 0.5rem;
    }
    .odoo-field-control {
        width: 62%;
    }
    .odoo-field-control .form-control,
    .odoo-field-control .form-select {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
        height: auto;
    }
    .notes-title {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #64748b;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 0.2rem;
        margin-top: 0.5rem;
        margin-bottom: 0.4rem;
    }
    .eq-bottom-bar {
        flex-shrink: 0;
        padding-top: 0.5rem;
        margin-top: 0.5rem;
        border-top: 1px solid #e2e8f0;
    }
</style>
@endpush

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm py-2 px-3 mb-2 small">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('clients.update', $client) }}" method="POST" id="client-form" class="eq-form-layout">
            @csrf
            @method('PUT')

            <!-- Odoo Sheet Container -->
            <div class="odoo-sheet">
                <!-- Top Title Input -->
                <div class="row align-items-start mb-2">
                    <div class="col">
                        <div class="odoo-top-label mb-1">{{ __('locale.lbl_raison_sociale') }} <span class="text-danger">*</span></div>
                        <input type="text" name="raison_sociale" class="form-control odoo-title-input w-100 mb-1" placeholder="{{ __('locale.placeholder_raison_sociale') }}" value="{{ old('raison_sociale', $client->raison_sociale) }}" required autofocus>
                        @if($client->company || $client->cegid_id || $client->odoo_id)
                            <div class="d-flex flex-wrap align-items-center gap-2 mt-2 mb-1">
                                @if($client->company)
                                    <span class="badge bg-secondary font-monospace"><i class="bi bi-building me-1"></i>{{ $client->company->name }} ({{ $client->company->code }})</span>
                                @endif
                                @if($client->cegid_id)
                                    <span class="badge bg-light text-dark border font-monospace">CEGID: {{ $client->cegid_id }}</span>
                                @endif
                                @if($client->odoo_id)
                                    @php
                                        $odooClientUrl = config('services.odoo.url')
                                            ? rtrim(config('services.odoo.url'), '/') . '/web#id=' . $client->odoo_id . '&model=res.partner&view_type=form'
                                            : null;
                                    @endphp
                                    @if($odooClientUrl)
                                        <a href="{{ $odooClientUrl }}" target="_blank" rel="noopener" class="badge bg-secondary-subtle text-secondary border border-secondary-subtle font-monospace text-decoration-none" title="Ouvrir dans Odoo">
                                            <i class="bi bi-hash me-1"></i>Odoo ID: {{ $client->odoo_id }} <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                        </a>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle font-monospace"><i class="bi bi-hash me-1"></i>Odoo ID: {{ $client->odoo_id }}</span>
                                    @endif
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                @include('clients._siret_lookup')

                <!-- Odoo Nav Tabs -->
                <ul class="nav nav-tabs odoo-nav-tabs" id="clientTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general-tab-pane" type="button" role="tab">
                            <i class="bi bi-info-circle me-1"></i>{{ __('locale.equipment_show.lbl_tab_general') ?? 'Général' }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="accounting-tab" data-bs-toggle="tab" data-bs-target="#accounting-tab-pane" type="button" role="tab">
                            <i class="bi bi-cash-stack me-1"></i>{{ __('locale.equipment_show.lbl_tab_accounting') ?? 'Comptabilité & Facturation' }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="portal-tab" data-bs-toggle="tab" data-bs-target="#portal-tab-pane" type="button" role="tab">
                            <i class="bi bi-shield-lock me-1"></i>{{ __('locale.portal_permissions') ?? 'Portail & Options' }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="notes-tab" data-bs-toggle="tab" data-bs-target="#notes-tab-pane" type="button" role="tab">
                            <i class="bi bi-journal-text me-1"></i>{{ __('locale.notes_and_comments') ?? 'Notes & Commentaires' }}
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="clientTabContent">
                    
                    <!-- TAB 1: GENERAL -->
                    <div class="tab-pane fade show active" id="general-tab-pane" role="tabpanel" tabindex="0">
                        <div class="row g-3">
                            <!-- Left Column: Coordonnées & Identification -->
                            <div class="col-md-6 border-end pe-md-3">
                                <div class="notes-title"><i class="bi bi-info-circle me-1"></i>{{ __('locale.lbl_information') ?? 'Identification' }}</div>
                                
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_libelle_abrege') ?? 'Libellé abrégé' }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" name="libelle_abrege" class="form-control" value="{{ old('libelle_abrege', $client->libelle_abrege) }}">
                                    </div>
                                </div>

                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_enseigne') ?? 'Enseigne' }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" name="enseigne" class="form-control" value="{{ old('enseigne', $client->enseigne) }}">
                                    </div>
                                </div>

                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_type') ?? 'Type' }}</div>
                                    <div class="odoo-field-control">
                                        <select name="type" class="form-select select2">
                                            @foreach (App\Enums\ClientType::cases() as $type)
                                                <option value="{{ $type->value }}" {{ old('type', $client->type?->value ?? $client->type) == $type->value ? 'selected' : '' }}>{{ $type->value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_group') ?? 'Groupe parent' }}</div>
                                    <div class="odoo-field-control">
                                        <select name="group_id" class="form-select select2">
                                            <option value="">{{ __('locale.lbl_select_group') ?? '-- Aucun (Client indépendant) --' }}</option>
                                            @foreach ($groups as $group)
                                                <option value="{{ $group->id }}" {{ old('group_id', $client->parent_id) == $group->id ? 'selected' : '' }}>{{ $group->raison_sociale }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_commercial_novalyo') ?? 'Commercial' }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" name="commercial_novalyo" class="form-control" value="{{ old('commercial_novalyo', $client->commercial_novalyo) }}">
                                    </div>
                                </div>

                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_secteur_activite') ?? 'Secteur d\'activité' }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" name="secteur_activite" class="form-control" value="{{ old('secteur_activite', $client->secteur_activite) }}">
                                    </div>
                                </div>

                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_langue') ?? 'Langue' }}</div>
                                    <div class="odoo-field-control">
                                        <select name="langue" class="form-select">
                                            @php
                                                $clientLang = old('langue', $client->langue ?? 'Français');
                                                $allLangs = isset($languages) ? $languages : \App\Models\Language::where('is_active', true)->orderBy('name')->get();
                                            @endphp
                                            @foreach($allLangs as $lOpt)
                                                <option value="{{ $lOpt->name }}" @selected($clientLang === $lOpt->name)>{{ $lOpt->name }}</option>
                                            @endforeach
                                            @if(!empty($clientLang) && !$allLangs->contains('name', $clientLang))
                                                <option value="{{ $clientLang }}" selected>{{ $clientLang }}</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_donneurs_ordre') ?? 'Donneurs d\'ordre' }}</div>
                                    <div class="odoo-field-control">
                                        <select name="donneurs_ordre[]" class="form-select select2" multiple="multiple" data-placeholder="{{ __('locale.lbl_select_donneurs_ordre') ?? 'Choisir un ou plusieurs' }}">
                                            @foreach ($allClients as $c)
                                                <option value="{{ $c->id }}" {{ (is_array(old('donneurs_ordre')) ? in_array($c->id, old('donneurs_ordre')) : $client->donneursOrdre->contains($c->id)) ? 'selected' : '' }}>
                                                    {{ $c->raison_sociale }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_societe_composante') ?? 'Société composante' }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" name="societe_composante" class="form-control" value="{{ old('societe_composante', $client->societe_composante) }}">
                                    </div>
                                </div>

                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_partenaires') ?? 'Partenaires' }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" name="partenaires" class="form-control" value="{{ old('partenaires', $client->partenaires) }}">
                                    </div>
                                </div>

                                <div class="notes-title mt-3"><i class="bi bi-telephone me-1"></i>{{ __('locale.menu_contacts') ?? 'Contacts & Téléphones' }}</div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_telephone_1') ?? 'Tél. 1' }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" name="tel_1" class="form-control" value="{{ old('tel_1', $client->tel_1) }}">
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_telephone_2') ?? 'Tél. 2' }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" name="tel_2" class="form-control" value="{{ old('tel_2', $client->tel_2) }}">
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_telephone_3') ?? 'Tél. 3' }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" name="tel_3" class="form-control" value="{{ old('tel_3', $client->tel_3) }}">
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_email') ?? 'Email' }}</div>
                                    <div class="odoo-field-control">
                                        <input type="email" name="email" class="form-control" value="{{ old('email', $client->email) }}">
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_client_web') ?? 'Site Web' }}</div>
                                    <div class="odoo-field-control">
                                        <input type="url" name="site_web" class="form-control" value="{{ old('site_web', $client->site_web ?: $client->client_web) }}" placeholder="https://...">
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column: Adresse Postale -->
                            <div class="col-md-6 ps-md-3">
                                <div class="notes-title"><i class="bi bi-geo-alt me-1"></i>{{ __('locale.address') ?? 'Adresse Postale' }}</div>
                                
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_adresse_1') ?? 'Adresse 1' }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" name="adresse_1" class="form-control" value="{{ old('adresse_1', $client->adresse_1) }}">
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_adresse_2') ?? 'Adresse 2' }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" name="adresse_2" class="form-control" value="{{ old('adresse_2', $client->adresse_2) }}">
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_adresse_3') ?? 'Adresse 3' }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" name="adresse_3" class="form-control" value="{{ old('adresse_3', $client->adresse_3) }}">
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_code_postal') ?? 'Code postal' }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" name="code_postal" class="form-control" value="{{ old('code_postal', $client->code_postal) }}">
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_ville') ?? 'Ville' }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" name="ville" class="form-control" value="{{ old('ville', $client->ville) }}">
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_department') ?? 'Département' }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" name="department" class="form-control" value="{{ old('department', $client->department) }}">
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_region') ?? 'Région' }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" name="region" class="form-control" value="{{ old('region', $client->region) }}">
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_country') ?? 'Pays' }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" name="pays" class="form-control" value="{{ old('pays', $client->pays) }}">
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_division_territoriale') ?? 'Division territoriale' }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" name="division_territoriale" class="form-control" value="{{ old('division_territoriale', $client->division_territoriale) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: ACCOUNTING & INVOICING -->
                    <div class="tab-pane fade" id="accounting-tab-pane" role="tabpanel" tabindex="0">
                        <div class="row g-3">
                            <div class="col-md-6 border-end pe-md-3">
                                <div class="notes-title"><i class="bi bi-cash-stack me-1"></i>{{ __('locale.equipment_show.lbl_tab_accounting') ?? 'Comptabilité & Facturation' }}</div>
                                
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_company') ?? 'Société' }}</div>
                                    <div class="odoo-field-control">
                                        <select name="company_id" id="company_id" class="form-select select2">
                                            <option value="">-- {{ __('locale.select_company') ?? 'Sélectionner une société' }} --</option>
                                            @foreach ($companies as $comp)
                                                <option value="{{ $comp->id }}" data-odoo-company-id="{{ $comp->odoo_id }}" {{ old('company_id', $client->company_id) == $comp->id ? 'selected' : '' }}>{{ $comp->name }} ({{ $comp->code }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_cegid_id') ?? 'ID CEGID' }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" name="cegid_id" class="form-control" value="{{ old('cegid_id', $client->cegid_id) }}">
                                    </div>
                                </div>

                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_cegid_id_2') ?? 'ID CEGID 2 (Tiers)' }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" name="cegid_id2" class="form-control" value="{{ old('cegid_id2', $client->cegid_id2) }}">
                                    </div>
                                </div>

                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_compte_general_collectif') ?? 'Compte général collectif' }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" name="compte_general_collectif" class="form-control" value="{{ old('compte_general_collectif', $client->compte_general_collectif) }}">
                                    </div>
                                </div>

                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_odoo_id') ?? 'ID Odoo' }}</div>
                                    <div class="odoo-field-control">
                                        <div class="input-group">
                                            <input type="number" name="odoo_id" id="odoo_id" class="form-control font-monospace @error('odoo_id') is-invalid @enderror" value="{{ old('odoo_id', $client->odoo_id) }}" placeholder="Odoo ID">
                                            @if($client->odoo_id && config('services.odoo.url'))
                                                <a href="{{ rtrim(config('services.odoo.url'), '/') . '/web#id=' . $client->odoo_id . '&model=res.partner&view_type=form' }}" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm d-flex align-items-center" title="Ouvrir dans Odoo">
                                                    <i class="bi bi-box-arrow-up-right"></i>
                                                </a>
                                            @endif
                                            @error('odoo_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_contract_status') ?? 'Statut de contrat' }} <span class="text-danger">*</span></div>
                                    <div class="odoo-field-control">
                                        <select name="contract_status" class="form-select" required>
                                            @php $currentStatus = old('contract_status', $client->contract_status->value ?? ($client->contract_status ?? 'sans_contrat')); @endphp
                                            @foreach (App\Enums\ContractStatus::values() as $statusVal)
                                                <option value="{{ $statusVal }}" {{ $currentStatus == $statusVal ? 'selected' : '' }}>
                                                    {{ __('locale.contract_status.' . $statusVal) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_condition_reglement') }}</div>
                                    <div class="odoo-field-control">
                                        <select name="condition_reglement_id" class="form-select select2">
                                            <option value="">-- {{ __('locale.not_specified') }} --</option>
                                            @foreach ($conditionsReglement as $cr)
                                                <option value="{{ $cr->id }}" {{ old('condition_reglement_id', $client->condition_reglement_id) == $cr->id ? 'selected' : '' }}>{{ $cr->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_regime_tva') }}</div>
                                    <div class="odoo-field-control">
                                        <select name="regime_tva_id" class="form-select select2">
                                            <option value="">-- {{ __('locale.not_specified') }} --</option>
                                            @foreach ($regimesTva as $rt)
                                                <option value="{{ $rt->id }}" {{ old('regime_tva_id', $client->regime_tva_id) == $rt->id ? 'selected' : '' }}>{{ $rt->code }} - {{ $rt->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_odoo_fiscal_positions') }}</div>
                                    <div class="odoo-field-control">
                                        <select name="fiscal_position_id" id="fiscal_position_id" class="form-select select2">
                                            <option value="">-- {{ __('locale.not_specified') }} --</option>
                                            @foreach ($fiscalPositions as $fp)
                                                <option value="{{ $fp->id }}" data-odoo-company-id="{{ $fp->odoo_company_id }}" {{ old('fiscal_position_id', $client->fiscal_position_id) == $fp->id ? 'selected' : '' }}>{{ $fp->name }} @if($fp->company_name)({{ $fp->company_name }})@endif</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_code_siret') ?? 'SIRET' }}</div>
                                    <div class="odoo-field-control">
                                        <div class="input-group">
                                            <input type="text" name="code_siret" class="form-control" value="{{ old('code_siret', $client->code_siret) }}">
                                            <button type="button" class="btn btn-outline-secondary btn-trigger-client-siret-search" title="Rechercher sur les services officiels">
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_code_nif') ?? 'NIF / TVA Intra' }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" name="code_nif" class="form-control" value="{{ old('code_nif', $client->code_nif) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 ps-md-3">
                                <div class="notes-title"><i class="bi bi-bank me-1"></i>{{ __('locale.banking_details') ?? 'Coordonnées Bancaires' }}</div>
                                
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_bank_name') ?? 'Banque' }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $client->bank_name) }}">
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_iban') ?? 'IBAN' }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" name="iban" class="form-control" value="{{ old('iban', $client->iban) }}">
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_bic') ?? 'BIC' }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" name="bic" class="form-control" value="{{ old('bic', $client->bic) }}">
                                    </div>
                                </div>

                                <div class="notes-title mt-3"><i class="bi bi-calendar-event me-1"></i>{{ __('locale.dates') ?? 'Dates' }}</div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_date_ouverture') ?? 'Date ouverture' }}</div>
                                    <div class="odoo-field-control">
                                        <input type="date" name="date_ouverture" class="form-control" value="{{ old('date_ouverture', $client->date_ouverture) }}">
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_date_fermeture') ?? 'Date fermeture' }}</div>
                                    <div class="odoo-field-control">
                                        <input type="date" name="date_fermeture" class="form-control" value="{{ old('date_fermeture', $client->date_fermeture) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 3: PORTAL & FLAGS -->
                    <div class="tab-pane fade" id="portal-tab-pane" role="tabpanel" tabindex="0">
                        <div class="notes-title"><i class="bi bi-toggles me-1"></i>{{ __('locale.general_options') ?? 'Options Générales' }}</div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-4">
                                <div class="form-check form-switch p-2 border rounded bg-light">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" name="closed" id="closed" value="1" {{ old('closed', $client->closed) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold small" for="closed">{{ __('locale.lbl_closed') ?? 'Fermé' }}</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch p-2 border rounded bg-light">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" name="concurrent" id="concurrent" value="1" {{ old('concurrent', $client->concurrent) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold small" for="concurrent">{{ __('locale.lbl_concurrent') ?? 'Concurrent' }}</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch p-2 border rounded bg-light">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" name="default_payment" id="default_payment" value="1" {{ old('default_payment', $client->default_payment) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold small" for="default_payment">{{ __('locale.default_payment') ?? 'Défaut de paiement' }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="notes-title"><i class="bi bi-shield-lock me-1"></i>{{ __('locale.portal_permissions') ?? 'Permissions Portail' }}</div>
                        <div class="row g-2 mt-1">
                            @php
                                $portalFields = [
                                    'can_contact_client_directly' => __('locale.portal_perm_can_contact_client_directly'),
                                    'can_view_tickets' => __('locale.portal_perm_can_view_tickets'),
                                    'can_view_other_tickets' => __('locale.portal_perm_can_view_other_tickets'),
                                    'can_manage_users' => __('locale.portal_perm_can_manage_users'),
                                    'can_view_contracts' => __('locale.portal_perm_can_view_contracts'),
                                    'can_view_interventions' => __('locale.portal_perm_can_view_interventions'),
                                    'can_view_repairs' => __('locale.portal_perm_can_view_repairs'),
                                    'can_view_vp_visits' => __('locale.portal_perm_can_view_vp_visits'),
                                    'can_view_equipments' => __('locale.portal_perm_can_view_equipments'),
                                    'can_view_projects' => __('locale.portal_perm_can_view_projects'),
                                    'can_view_orders' => __('locale.portal_perm_can_view_orders'),
                                    'can_view_invoices' => __('locale.portal_perm_can_view_invoices'),
                                    'can_view_knowledgebase' => __('locale.portal_perm_can_view_knowledgebase'),
                                    'can_view_statistics' => __('locale.portal_perm_can_view_statistics'),
                                ];
                            @endphp
                            @foreach($portalFields as $pField => $pLabel)
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-check form-switch p-2 border rounded bg-light">
                                        <input class="form-check-input ms-0 me-2" type="checkbox" name="{{ $pField }}" id="{{ $pField }}" value="1" {{ old($pField, $client->$pField) ? 'checked' : '' }}>
                                        <label class="form-check-label small" for="{{ $pField }}">{{ $pLabel }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- TAB 4: NOTES -->
                    <div class="tab-pane fade" id="notes-tab-pane" role="tabpanel" tabindex="0">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="notes-title"><i class="bi bi-sticky me-1"></i>{{ __('locale.lbl_bloc_note') ?? 'Bloc-notes' }}</div>
                                <textarea name="bloc_note" class="form-control" rows="8" placeholder="{{ __('locale.internal_notes_placeholder') ?? 'Notes internes...' }}">{{ old('bloc_note', $client->bloc_note) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <div class="notes-title"><i class="bi bi-chat-left-text me-1"></i>{{ __('locale.lbl_commentaire') ?? 'Commentaires' }}</div>
                                <textarea name="commentaire" class="form-control" rows="8" placeholder="{{ __('locale.general_comments_placeholder') ?? 'Commentaires généraux...' }}">{{ old('commentaire', $client->commentaire) }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Bottom Action Bar -->
                <div class="eq-bottom-bar d-flex justify-content-between align-items-center">
                    <a href="{{ route('clients.show', $client->id) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-lg me-1"></i> {{ __('locale.btn_cancel') ?? 'Annuler' }}
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-check-lg me-1"></i> {{ __('locale.btn_update') ?? 'Mettre à jour' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').each(function() {
            $(this).select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: $(this).data('placeholder') || '{{ __('locale.select_placeholder') ?? 'Sélectionner' }}',
                allowClear: true
            });
        });

        // Filter fiscal positions based on selected company
        const companySelect = $('#company_id');
        const fpSelect = $('#fiscal_position_id');

        if (companySelect.length && fpSelect.length) {
            const originalOptions = fpSelect.find('option').clone();

            function filterFiscalPositions() {
                const selectedOption = companySelect.find('option:selected');
                const odooCompanyId = selectedOption.data('odoo-company-id');
                const currentVal = fpSelect.val();

                fpSelect.empty();

                originalOptions.each(function() {
                    const opt = $(this);
                    const fpCompanyId = opt.data('odoo-company-id');

                    if (!opt.val() || !odooCompanyId || String(fpCompanyId) === String(odooCompanyId)) {
                        fpSelect.append(opt.clone());
                    }
                });

                if (fpSelect.find(`option[value="${currentVal}"]`).length) {
                    fpSelect.val(currentVal);
                } else {
                    fpSelect.val('');
                }

                fpSelect.trigger('change');
            }

            companySelect.on('change', filterFiscalPositions);
        }
    });
</script>
@endpush
