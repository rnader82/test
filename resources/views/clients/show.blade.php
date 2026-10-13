@extends('layouts.app')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <h1 class="mb-0 fw-bold text-secondary fs-4" title="{{ $client->raison_sociale }}">{{ \Illuminate\Support\Str::limit($client->raison_sociale, 50) }}</h1>
        @php
            $clientType = $client->type->value ?? $client->type;
            $clientTypeClass = match ($clientType) {
                'Groupe' => 'badge-matte-primary',
                'HQ' => 'badge-matte-dark',
                'Site' => 'badge-matte-info',
                default => 'badge-matte-secondary',
            };
        @endphp
        <span class="badge {{ $clientTypeClass }}">{{ $clientType }}</span>
        @if ($client->company)
            <span class="badge bg-secondary" title="{{ __('locale.lbl_company') ?? 'Société' }}">{{ $client->company->name }} ({{ $client->company->code }})</span>
        @endif
    </div>
@endsection

@section('page-actions')
    @if (Auth::user()->is_admin == 1 || Auth::user()->isNovalyoAdmin())
        <div class="dropdown d-inline-block me-1">
            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                    id="syncOdooDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-arrow-repeat me-1"></i> {{ __('locale.btn_sync_odoo') }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border" aria-labelledby="syncOdooDropdown">
                <li>
                    <form action="{{ route('clients.sync-odoo', $client->id) }}" method="POST" class="d-inline"
                          onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').innerHTML='<i class=\'bi bi-arrow-repeat spin me-1\'></i> {{ __('locale.syncing') }}';">
                        @csrf
                        <button type="submit" class="dropdown-item small">
                            <i class="bi bi-cloud-upload me-2 text-secondary"></i> {{ __('locale.btn_sync_odoo') }}
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    @endif
    @if (Auth::user()->is_admin == 1 || Auth::user()->isNovalyoAdmin() || Auth::user()->hasPermission('edit-clients'))
        <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-sm btn-warning">
            <i class="bi bi-pencil-square me-1"></i> {{ __('locale.btn_edit') }}
        </a>
    @endif
@endsection

@push('styles')
<style>
    .content-wrapper {
        height: calc(100vh - 75px);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .container-xxl {
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        padding-top: 0.25rem;
        padding-bottom: 0.25rem;
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
        overflow: hidden;
    }
    .tab-content {
        flex: 1 1 auto;
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: 0.25rem;
    }
    .odoo-top-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b !important;
        letter-spacing: 0.5px;
    }
    .odoo-title-display {
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a !important;
    }
    .odoo-img-box {
        width: 54px;
        height: 54px;
        border: 2px dashed #cbd5e1;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        background: #f8fafc;
    }
    .odoo-nav-tabs {
        border-bottom: 2px solid #e2e8f0;
        gap: 0.25rem;
        margin-bottom: 0.5rem;
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
        padding: 0.25rem 0.75rem;
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
        margin-bottom: 0.35rem;
        font-size: 0.8rem;
    }
    .odoo-field-label {
        width: 38%;
        font-weight: 600;
        color: #475569 !important;
        padding-right: 0.5rem;
    }
    .odoo-field-value {
        width: 62%;
        color: #0f172a !important;
        font-weight: 500;
    }
    .notes-title {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #64748b;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 0.15rem;
        margin-top: 0.4rem;
        margin-bottom: 0.25rem;
    }
    .odoo-inline-table {
        table-layout: fixed;
        width: 100%;
        font-size: 0.78rem;
    }
    .odoo-inline-table th,
    .odoo-inline-table td {
        padding: 0.25rem 0.3rem !important;
        vertical-align: middle;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
@endpush

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm py-1 px-3 mb-2 small">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger border-0 shadow-sm py-1 px-3 mb-2 small">{{ session('error') }}</div>
        @endif

        <!-- Odoo Sheet Container -->
        <div class="odoo-sheet">
            <!-- Header / Title & Metadata Chips -->
            <div class="row align-items-start mb-2">
                <div class="col">
                    <div class="odoo-top-label mb-1">{{ __('locale.lbl_client') ?? 'Client' }}</div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-building text-primary fs-5"></i>
                        <span class="odoo-title-display" title="{{ $client->raison_sociale }}">{{ $client->raison_sociale }}</span>
                        @if($client->enseigne)
                            <span class="text-muted small">({{ $client->enseigne }})</span>
                        @endif
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-2">
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
                        @php
                            $contractStatusValue = $client->contract_status->value ?? null;
                            $contractStatusClass = match ($contractStatusValue) {
                                'avec_contrat' => 'badge-matte-success',
                                'sans_contrat' => 'badge-matte-secondary',
                                'contrat_expire' => 'badge-matte-warning',
                                'contrat_resilie' => 'badge-matte-danger',
                                default => 'badge-matte-secondary',
                            };
                        @endphp
                        @if ($contractStatusValue)
                            <span class="badge {{ $contractStatusClass }}">{{ __('locale.contract_status.' . $contractStatusValue) }}</span>
                        @endif
                        @if($client->concurrent)
                            <span class="badge bg-danger"><i class="bi bi-flag-fill me-1"></i>{{ __('locale.lbl_concurrent') ?? 'Concurrent' }}</span>
                        @endif
                        @if($client->default_payment)
                            <span class="badge bg-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i>{{ __('locale.default_payment') ?? 'Défaut de paiement' }}</span>
                        @endif
                        @if($client->closed)
                            <span class="badge bg-danger"><i class="bi bi-lock-fill me-1"></i>{{ __('locale.lbl_closed') ?? 'Fermé' }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-auto">
                    <div class="odoo-img-box">
                        <i class="bi bi-building fs-3"></i>
                    </div>
                </div>
            </div>

            @php
                $user = Auth::user();
                $isAdmin = $user->is_admin == 1 || $user->isNovalyoAdmin();
                $hasDocTypeAccess = $isAdmin || !empty($user->getAllowedClientDocumentTypeIds());
                $canViewDocs = ($isAdmin || $user->hasPermission('view-client-documents') || $user->hasPermission('clients-list-access')) && $hasDocTypeAccess;
                $canAddDocs = ($isAdmin || $user->hasPermission('add-client-documents')) && $hasDocTypeAccess;
                $canDeleteDocs = ($isAdmin || $user->hasPermission('delete-client-documents')) && $hasDocTypeAccess;
                $canViewIssues = $isAdmin || $user->hasPermission('menu-tickets');
                $canViewInterventions = $isAdmin || $user->hasPermission('menu-interventions');
                $canViewRepairs = $isAdmin || $user->hasPermission('menu-repair');
                $canViewContracts = $isAdmin || $user->hasPermission('view-contracts');
                $canViewUsers = $isAdmin || $user->hasPermission('view-users') || $user->hasPermission('admin-view-users') || $user->hasPermission('manage-users');
                $canViewEquipments = $isAdmin || $user->hasPermission('equipments-access') || $user->hasPermission('view-equipments');
                $canViewSites = $isAdmin || (($client->type->value === 'Groupe' || $client->children->isNotEmpty()) && $user->hasPermission('clients-list-access'));
                $canViewContacts = $isAdmin || $user->hasPermission('view-contacts');
                $canViewOrders = $isAdmin || $user->hasPermission('view-orders') || $user->hasPermission('order-access');
            @endphp

            <!-- Odoo Nav Tabs -->
            <ul class="nav nav-tabs odoo-nav-tabs" id="clientTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general-tab-pane" type="button" role="tab">{{ __('locale.equipment_show.lbl_tab_general') }}</button>
                </li>
                @if($canViewDocs)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-flex align-items-center gap-1" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents-tab-pane" type="button" role="tab">
                            <i class="bi bi-file-earmark-text"></i> {{ __('locale.documents') }}
                            <span class="badge bg-light text-dark border ms-1">{{ $client->documents->count() }}</span>
                        </button>
                    </li>
                @endif
                @if($canViewIssues)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-flex align-items-center gap-1" id="issues-tab" data-bs-toggle="tab" data-bs-target="#issues-tab-pane" type="button" role="tab">
                            <i class="bi bi-ticket-detailed"></i> {{ __('locale.menu_tickets') }}
                            <span class="badge bg-light text-dark border ms-1">{{ $client->issues->count() }}</span>
                        </button>
                    </li>
                @endif
                @if($canViewInterventions)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-flex align-items-center gap-1" id="interventions-tab" data-bs-toggle="tab" data-bs-target="#interventions-tab-pane" type="button" role="tab">
                            <i class="bi bi-tools"></i> {{ __('locale.menu_interventions') }}
                            <span class="badge bg-light text-dark border ms-1">{{ $client->techInterventions->count() }}</span>
                        </button>
                    </li>
                @endif
                @if($canViewRepairs)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-flex align-items-center gap-1" id="repairs-tab" data-bs-toggle="tab" data-bs-target="#repairs-tab-pane" type="button" role="tab">
                            <i class="bi bi-wrench"></i> {{ __('locale.menu_repair') }}
                            <span class="badge bg-light text-dark border ms-1">{{ $client->repairs->count() }}</span>
                        </button>
                    </li>
                @endif
                @if($canViewContracts)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-flex align-items-center gap-1" id="contracts-tab" data-bs-toggle="tab" data-bs-target="#contracts-tab-pane" type="button" role="tab">
                            <i class="bi bi-journal-text"></i> {{ __('locale.menu_contracts') }}
                            <span class="badge bg-light text-dark border ms-1">{{ $client->contracts->count() }}</span>
                        </button>
                    </li>
                @endif
                @if($canViewOrders)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-flex align-items-center gap-1" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders-tab-pane" type="button" role="tab">
                            <i class="bi bi-cart3"></i> {{ __('locale.orders.title') }}
                            <span class="badge bg-light text-dark border ms-1">{{ $client->orders->count() }}</span>
                        </button>
                    </li>
                @endif
                @if($canViewEquipments)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-flex align-items-center gap-1" id="equipments-tab" data-bs-toggle="tab" data-bs-target="#equipments-tab-pane" type="button" role="tab">
                            <i class="bi bi-laptop"></i> {{ __('locale.menu_equipments') }}
                            <span class="badge bg-light text-dark border ms-1">{{ $client->clientEquipments->count() }}</span>
                        </button>
                    </li>
                @endif
                @if($canViewContacts)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-flex align-items-center gap-1" id="contacts-tab" data-bs-toggle="tab" data-bs-target="#contacts-tab-pane" type="button" role="tab">
                            <i class="bi bi-people"></i> {{ __('locale.menu_contacts') }}
                            <span class="badge bg-light text-dark border ms-1">{{ $client->contacts->count() }}</span>
                        </button>
                    </li>
                @endif
                @if($canViewSites)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-flex align-items-center gap-1" id="sites-tab" data-bs-toggle="tab" data-bs-target="#sites-tab-pane" type="button" role="tab">
                            <i class="bi bi-diagram-3"></i> {{ __('locale.lbl_sites') }}
                            <span class="badge bg-light text-dark border ms-1">{{ $client->children->count() }}</span>
                        </button>
                    </li>
                @endif
                @if($canViewUsers)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-flex align-items-center gap-1" id="users-tab" data-bs-toggle="tab" data-bs-target="#users-tab-pane" type="button" role="tab">
                            <i class="bi bi-person-check"></i> {{ __('locale.menu_users') }}
                            <span class="badge bg-light text-dark border ms-1">{{ $client->assignedUsers->count() }}</span>
                        </button>
                    </li>
                @endif
                <li class="nav-item" role="presentation">
                    <button class="nav-link d-flex align-items-center gap-1" id="portal-tab" data-bs-toggle="tab" data-bs-target="#portal-tab-pane" type="button" role="tab">
                        <i class="bi bi-shield-lock"></i> {{ __('locale.portal_permissions') }}
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="clientTabContent">
                
                <!-- 1. GENERAL TAB -->
                <div class="tab-pane fade show active" id="general-tab-pane" role="tabpanel" tabindex="0">
                    <div class="row g-3">
                        <!-- Left Column: Coordonnées & Identification -->
                        <div class="col-md-6 border-end pe-md-3">
                            <div class="notes-title"><i class="bi bi-info-circle me-1"></i>{{ __('locale.lbl_information') ?? 'Identification & Coordonnées' }}</div>
                            
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.lbl_raison_sociale') ?? 'Raison Sociale' }}</div>
                                <div class="odoo-field-value fw-bold">{{ $client->raison_sociale }}</div>
                            </div>
                            @if($client->libelle_abrege)
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_libelle_abrege') ?? 'Libellé abrégé' }}</div>
                                    <div class="odoo-field-value">{{ $client->libelle_abrege }}</div>
                                </div>
                            @endif
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.lbl_enseigne') ?? 'Enseigne' }}</div>
                                <div class="odoo-field-value">{{ $client->enseigne ?: '—' }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.lbl_type') ?? 'Type' }}</div>
                                <div class="odoo-field-value"><span class="badge {{ $clientTypeClass }}">{{ $clientType }}</span></div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.lbl_group') ?? 'Groupe parent' }}</div>
                                <div class="odoo-field-value">
                                    @if($client->parent)
                                        <a href="{{ route('clients.show', $client->parent_id) }}" class="text-decoration-none fw-semibold">
                                            <i class="bi bi-box-arrow-up-right me-1 small"></i>{{ $client->parent->raison_sociale }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.lbl_commercial_novalyo') ?? 'Commercial' }}</div>
                                <div class="odoo-field-value">{{ $client->commercial_novalyo ?: '—' }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.lbl_secteur_activite') ?? 'Secteur d\'activité' }}</div>
                                <div class="odoo-field-value">{{ $client->secteur_activite ?: '—' }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.lbl_langue') ?? 'Langue' }}</div>
                                <div class="odoo-field-value">{{ $client->langue ?: '—' }}</div>
                            </div>
                            @if($client->societe_composante)
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_societe_composante') ?? 'Société composante' }}</div>
                                    <div class="odoo-field-value">{{ $client->societe_composante }}</div>
                                </div>
                            @endif
                            @if($client->partenaires)
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_partenaires') ?? 'Partenaires' }}</div>
                                    <div class="odoo-field-value">{{ $client->partenaires }}</div>
                                </div>
                            @endif

                            <div class="notes-title mt-3"><i class="bi bi-geo-alt me-1"></i>{{ __('locale.address') ?? 'Adresse Postale' }}</div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.lbl_adresse_1') ?? 'Adresse 1' }}</div>
                                <div class="odoo-field-value">{{ $client->adresse_1 ?: '—' }}</div>
                            </div>
                            @if($client->adresse_2)
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_adresse_2') ?? 'Adresse 2' }}</div>
                                    <div class="odoo-field-value">{{ $client->adresse_2 }}</div>
                                </div>
                            @endif
                            @if($client->adresse_3)
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_adresse_3') ?? 'Adresse 3' }}</div>
                                    <div class="odoo-field-value">{{ $client->adresse_3 }}</div>
                                </div>
                            @endif
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.lbl_code_postal') ?? 'Code postal' }} / {{ __('locale.lbl_ville') ?? 'Ville' }}</div>
                                <div class="odoo-field-value">{{ $client->code_postal }} {{ $client->ville }}</div>
                            </div>
                            @if($client->department)
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_department') ?? 'Département' }}</div>
                                    <div class="odoo-field-value">{{ $client->department }}</div>
                                </div>
                            @endif
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.lbl_region') ?? 'Région' }} / {{ __('locale.lbl_country') ?? 'Pays' }}</div>
                                <div class="odoo-field-value">{{ $client->region ?: '—' }} ({{ $client->pays ?: '—' }})</div>
                            </div>
                            @if($client->division_territoriale)
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_division_territoriale') ?? 'Division territoriale' }}</div>
                                    <div class="odoo-field-value">{{ $client->division_territoriale }}</div>
                                </div>
                            @endif

                            <div class="notes-title mt-3"><i class="bi bi-telephone me-1"></i>{{ __('locale.lbl_direct_contact') }}</div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.lbl_telephone_1') }}</div>
                                <div class="odoo-field-value">
                                    @if($client->tel_1)
                                        <a href="tel:{{ $client->tel_1 }}" class="text-decoration-none">{{ $client->tel_1 }}</a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </div>
                            </div>
                            @if($client->tel_2)
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_telephone_2') }}</div>
                                    <div class="odoo-field-value"><a href="tel:{{ $client->tel_2 }}" class="text-decoration-none">{{ $client->tel_2 }}</a></div>
                                </div>
                            @endif
                            @if($client->tel_3)
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_telephone_3') }}</div>
                                    <div class="odoo-field-value"><a href="tel:{{ $client->tel_3 }}" class="text-decoration-none">{{ $client->tel_3 }}</a></div>
                                </div>
                            @endif
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.lbl_email') }}</div>
                                <div class="odoo-field-value">
                                    @if($client->email)
                                        <a href="mailto:{{ $client->email }}" class="text-decoration-none">{{ $client->email }}</a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.lbl_client_web') }}</div>
                                <div class="odoo-field-value">
                                    @if($client->site_web || $client->client_web)
                                        <a href="{{ $client->site_web ?: $client->client_web }}" target="_blank" class="text-decoration-none">
                                            {{ $client->site_web ?: $client->client_web }} <i class="bi bi-box-arrow-up-right small ms-1"></i>
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Comptabilité, Fiscalité & Banque -->
                        <div class="col-md-6 ps-md-3">
                            <div class="notes-title"><i class="bi bi-cash-stack me-1"></i>{{ __('locale.equipment_show.lbl_tab_accounting') }}</div>

                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.lbl_company') }}</div>
                                <div class="odoo-field-value">
                                    @if($client->company)
                                        <span class="badge bg-secondary">{{ $client->company->name }} ({{ $client->company->code }})</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.lbl_cegid_id') }}</div>
                                <div class="odoo-field-value font-monospace">{{ $client->cegid_id ?: '—' }}</div>
                            </div>
                            @if($client->cegid_id2)
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_cegid_id_2') }}</div>
                                    <div class="odoo-field-value font-monospace">{{ $client->cegid_id2 }}</div>
                                </div>
                            @endif
                            @if($client->compte_general_collectif)
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_compte_general_collectif') ?? 'Compte général collectif' }}</div>
                                    <div class="odoo-field-value font-monospace">{{ $client->compte_general_collectif }}</div>
                                </div>
                            @endif
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.lbl_odoo_id') }}</div>
                                <div class="odoo-field-value font-monospace">
                                    @if($client->odoo_id)
                                        @php
                                            $odooClientUrl = config('services.odoo.url')
                                                ? rtrim(config('services.odoo.url'), '/') . '/web#id=' . $client->odoo_id . '&model=res.partner&view_type=form'
                                                : null;
                                        @endphp
                                        @if($odooClientUrl)
                                            <a href="{{ $odooClientUrl }}" target="_blank" rel="noopener" class="text-decoration-none" title="Ouvrir dans Odoo">
                                                {{ $client->odoo_id }} <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                            </a>
                                        @else
                                            {{ $client->odoo_id }}
                                        @endif
                                    @else
                                        —
                                    @endif
                                </div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.lbl_condition_reglement') }}</div>
                                <div class="odoo-field-value">{{ $client->conditionReglement->name ?? '—' }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.lbl_regime_tva') }}</div>
                                <div class="odoo-field-value">{{ $client->regimeTva ? $client->regimeTva->code . ' - ' . $client->regimeTva->name : '—' }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.lbl_odoo_fiscal_positions') }}</div>
                                <div class="odoo-field-value">{{ $client->fiscalPosition->name ?? '—' }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.lbl_code_siret') }}</div>
                                <div class="odoo-field-value font-monospace">{{ $client->code_siret ?: '—' }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.lbl_code_nif') }}</div>
                                <div class="odoo-field-value font-monospace">{{ $client->code_nif ?: '—' }}</div>
                            </div>

                            <div class="notes-title mt-3"><i class="bi bi-bank me-1"></i>{{ __('locale.banking_details') }}</div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.lbl_bank_name') }}</div>
                                <div class="odoo-field-value">{{ $client->bank_name ?: '—' }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.lbl_iban') }}</div>
                                <div class="odoo-field-value font-monospace">{{ $client->iban ?: '—' }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.lbl_bic') }}</div>
                                <div class="odoo-field-value font-monospace">{{ $client->bic ?: '—' }}</div>
                            </div>

                            <div class="notes-title mt-3"><i class="bi bi-calendar-event me-1"></i>{{ __('locale.lbl_history_dates') }}</div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.lbl_date_ouverture') }}</div>
                                <div class="odoo-field-value">{{ $client->date_ouverture ?: '—' }}</div>
                            </div>
                            @if($client->date_fermeture)
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.lbl_date_fermeture') }}</div>
                                    <div class="odoo-field-value text-danger">{{ $client->date_fermeture }}</div>
                                </div>
                            @endif
                        </div>

                        <!-- Full Width: Bloc-Notes & Commentaires -->
                        @if($client->bloc_note || $client->commentaire)
                            <div class="col-12 mt-2 pt-2 border-top">
                                <div class="row">
                                    @if($client->bloc_note)
                                        <div class="col-md-6">
                                            <div class="notes-title"><i class="bi bi-sticky me-1"></i>{{ __('locale.lbl_bloc_note') }}</div>
                                            <div class="p-2 bg-light rounded small text-muted">{{ $client->bloc_note }}</div>
                                        </div>
                                    @endif
                                    @if($client->commentaire)
                                        <div class="col-md-6">
                                            <div class="notes-title"><i class="bi bi-chat-left-text me-1"></i>{{ __('locale.lbl_commentaire') }}</div>
                                            <div class="p-2 bg-light rounded small text-muted">{{ $client->commentaire }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- 2. DOCUMENTS TAB (NEW!) -->
                @if($canViewDocs)
                    <div class="tab-pane fade" id="documents-tab-pane" role="tabpanel" tabindex="0">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                            <div>
                                <h6 class="fw-bold mb-0 text-secondary"><i class="bi bi-folder2-open me-2 text-primary"></i>{{ __('locale.client_documents') }}</h6>
                                <span class="text-muted small">{{ $client->documents->count() }} {{ __('locale.documents_attached') }}</span>
                            </div>
                            @if($canAddDocs)
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                                    <i class="bi bi-cloud-arrow-up me-1"></i> {{ __('locale.btn_upload_document') }}
                                </button>
                            @endif
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 odoo-inline-table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 25%;">{{ __('locale.lbl_document_title') }}</th>
                                        <th style="width: 18%;">{{ __('locale.lbl_type') }}</th>
                                        <th style="width: 12%;">{{ __('locale.lbl_size') }}</th>
                                        <th style="width: 15%;">{{ __('locale.lbl_expiry_date') }}</th>
                                        <th style="width: 18%;">{{ __('locale.uploaded_by') }}</th>
                                        <th class="text-end" style="width: 12%;">{{ __('locale.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($client->documents as $doc)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="bi {{ $doc->file_icon }} fs-5 flex-shrink-0"></i>
                                                    <div class="text-truncate">
                                                        <span class="fw-bold text-dark d-block text-truncate" title="{{ $doc->display_name }}">{{ $doc->display_name }}</span>
                                                        <span class="text-muted small font-monospace text-truncate d-block" title="{{ $doc->document_name }}">{{ $doc->document_name }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($doc->documentType)
                                                    <span class="badge bg-{{ $doc->documentType->color ?? 'secondary' }} rounded-pill px-2 py-1">
                                                        {{ $doc->documentType->name }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary-subtle text-secondary border">{{ __('locale.general') }}</span>
                                                @endif
                                            </td>
                                            <td class="font-monospace small">{{ $doc->size_human }}</td>
                                            <td>
                                                @if($doc->expires_at)
                                                    @if($doc->is_expired)
                                                        <span class="badge bg-danger text-white" title="{{ __('locale.expired_on', ['date' => $doc->expires_at->format('d/m/Y')]) }}">
                                                            <i class="bi bi-exclamation-octagon me-1"></i>{{ $doc->expires_at->format('d/m/Y') }} ({{ __('locale.expired') }})
                                                        </span>
                                                    @elseif($doc->is_expiring_soon)
                                                        <span class="badge bg-warning text-dark" title="{{ __('locale.expiring_soon_on', ['date' => $doc->expires_at->format('d/m/Y')]) }}">
                                                            <i class="bi bi-clock-history me-1"></i>{{ $doc->expires_at->format('d/m/Y') }} ({{ __('locale.expiring_soon') }})
                                                        </span>
                                                    @else
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                            <i class="bi bi-calendar-check me-1"></i>{{ $doc->expires_at->format('d/m/Y') }}
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="small">
                                                    <span class="fw-semibold text-dark d-block">{{ $doc->uploadedBy->name ?? __('locale.system') }}</span>
                                                    <span class="text-muted">{{ $doc->created_at->format('d/m/Y H:i') }}</span>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex align-items-center justify-content-end gap-1">
                                                    <a href="{{ route('clients.documents.download', [$client->id, $doc->id]) }}" class="btn btn-sm btn-outline-secondary py-0 px-2" title="{{ __('locale.btn_download') }}">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                    @if($canDeleteDocs)
                                                        <form action="{{ route('clients.documents.destroy', [$client->id, $doc->id]) }}" method="POST" class="d-inline"
                                                              onsubmit="return confirm('{{ __('locale.confirm_delete') }}')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" title="{{ __('locale.btn_delete') }}">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                <i class="bi bi-folder2-open fs-2 d-block mb-1 text-secondary"></i>
                                                {{ __('locale.no_documents_found') }}
                                                @if($canAddDocs)
                                                    <div class="mt-2">
                                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                                                            <i class="bi bi-cloud-arrow-up me-1"></i> {{ __('locale.btn_add_first_document') }}
                                                        </button>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- 3. TICKETS TAB -->
                @if($canViewIssues)
                    <div class="tab-pane fade" id="issues-tab-pane" role="tabpanel" tabindex="0">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold mb-0 text-secondary"><i class="bi bi-ticket-detailed me-2 text-primary"></i>{{ __('locale.incident_tickets') }} ({{ $client->issues->count() }})</h6>
                            <a href="{{ route('issues.create', ['client_id' => $client->id]) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_open_ticket') }}
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 odoo-inline-table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 15%;">{{ __('locale.lbl_ref_id') }}</th>
                                        <th style="width: 40%;">{{ __('locale.lbl_title') }}</th>
                                        <th style="width: 15%;">{{ __('locale.lbl_status') }}</th>
                                        <th style="width: 15%;">{{ __('locale.lbl_date') }}</th>
                                        <th class="text-end" style="width: 15%;">{{ __('locale.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($client->issues as $issue)
                                        <tr>
                                            <td class="font-monospace fw-bold">#{{ $issue->id }}</td>
                                            <td class="text-truncate">{{ $issue->title }}</td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $issue->status->name ?? 'N/A' }}</span>
                                            </td>
                                            <td class="small text-muted">{{ $issue->created_at->format('d/m/Y') }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('issues.show', $issue->id) }}" class="btn btn-sm btn-outline-secondary py-0 px-2">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center py-3 text-muted">{{ __('locale.no_tickets_associated') }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- 4. INTERVENTIONS TAB -->
                @if($canViewInterventions)
                    <div class="tab-pane fade" id="interventions-tab-pane" role="tabpanel" tabindex="0">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold mb-0 text-secondary"><i class="bi bi-tools me-2 text-primary"></i>{{ __('locale.technical_interventions') }} ({{ $client->techInterventions->count() }})</h6>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 odoo-inline-table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 15%;">{{ __('locale.lbl_ref_id') }}</th>
                                        <th style="width: 40%;">{{ __('locale.lbl_description') }}</th>
                                        <th style="width: 15%;">{{ __('locale.lbl_status') }}</th>
                                        <th style="width: 15%;">{{ __('locale.lbl_date') }}</th>
                                        <th class="text-end" style="width: 15%;">{{ __('locale.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($client->techInterventions as $ti)
                                        <tr>
                                            <td class="font-monospace fw-bold">#{{ $ti->id }}</td>
                                            <td class="text-truncate">{{ $ti->description ?? $ti->name ?? 'Intervention' }}</td>
                                            <td><span class="badge bg-secondary">{{ $ti->status->name ?? 'N/A' }}</span></td>
                                            <td class="small text-muted">{{ $ti->intervention_date ? \Carbon\Carbon::parse($ti->intervention_date)->format('d/m/Y') : '—' }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('tech_intervs.show', $ti->id) }}" class="btn btn-sm btn-outline-secondary py-0 px-2"><i class="bi bi-eye"></i></a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center py-3 text-muted">{{ __('locale.no_interventions_associated') }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- 5. REPAIRS TAB -->
                @if($canViewRepairs)
                    <div class="tab-pane fade" id="repairs-tab-pane" role="tabpanel" tabindex="0">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold mb-0 text-secondary"><i class="bi bi-wrench me-2 text-primary"></i>Dossiers SAV & Réparations ({{ $client->repairs->count() }})</h6>
                            <a href="{{ route('repairs.create', ['client_id' => $client->id]) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-plus-lg me-1"></i> Nouveau dossier SAV
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 odoo-inline-table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 100px;">Dossier #</th>
                                        <th style="width: 180px;">Ticket</th>
                                        <th>Matériels & Traçabilité</th>
                                        <th style="width: 140px;">Statut</th>
                                        <th style="width: 100px;">Date</th>
                                        <th class="text-end" style="width: 70px;">{{ __('locale.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($client->repairs as $rep)
                                        <tr>
                                            <td class="font-monospace fw-bold">#{{ $rep->id }}</td>
                                            <td>
                                                @if($rep->issue_id)
                                                    <a href="{{ route('issues.show', $rep->issue_id) }}" class="fw-semibold">
                                                        Ticket #{{ $rep->issue_id }}
                                                    </a>
                                                    @if($rep->issue?->subject)
                                                        <div class="text-muted small text-truncate" style="max-width: 160px;">{{ $rep->issue->subject }}</div>
                                                    @endif
                                                @elseif($rep->external_ticket_number)
                                                    <span class="badge bg-light text-dark border"><i class="bi bi-tag me-1"></i>{{ $rep->external_ticket_number }}</span>
                                                @else
                                                    <span class="text-muted small">Sans ticket</span>
                                                @endif
                                            </td>
                                            <td>
                                                @forelse($rep->equipmentRepairs as $er)
                                                    <div class="small mb-1">
                                                        <span class="fw-semibold">{{ $er->equipment?->name ?? 'Équipement' }}</span>
                                                        @if($er->serial_number)
                                                            <span class="font-monospace text-muted">(S/N: {{ $er->serial_number }})</span>
                                                        @endif
                                                        @if($er->warranty_type)
                                                            <span class="badge bg-light text-dark border ms-1" style="font-size: 0.7rem;">{{ $er->warranty_label }}</span>
                                                        @endif
                                                        @if($er->customer_delivery_date)
                                                            <span class="badge bg-success-subtle text-success ms-1" style="font-size: 0.7rem;">Livré</span>
                                                        @endif
                                                    </div>
                                                @empty
                                                    <span class="text-muted small">Aucun équipement</span>
                                                @endforelse
                                            </td>
                                            <td>
                                                <span class="badge" style="background-color: {{ hexToRgba($rep->status_color, 0.15) }}; color: {{ getDynamicStatusColor($rep->status_color) }}; font-weight: 600;">
                                                    {{ $rep->status_label }}
                                                </span>
                                            </td>
                                            <td class="small text-muted">{{ $rep->created_at->format('d/m/Y') }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('repairs.show', $rep->id) }}" class="btn btn-sm btn-outline-secondary py-0 px-2" title="Voir"><i class="bi bi-eye"></i></a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center py-3 text-muted">{{ __('locale.no_repairs_associated') }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- 6. CONTRACTS TAB -->
                @if($canViewContracts)
                    <div class="tab-pane fade" id="contracts-tab-pane" role="tabpanel" tabindex="0">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold mb-0 text-secondary"><i class="bi bi-journal-text me-2 text-primary"></i>{{ __('locale.menu_contracts') }} ({{ $client->contracts->count() }})</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#quickAddContractModal">
                                <i class="bi bi-plus-lg me-1"></i> {{ __('locale.lbl_create_contract') }}
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 odoo-inline-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('locale.lbl_reference') }}</th>
                                        <th>{{ __('locale.lbl_company') ?? 'Société' }}</th>
                                        <th>{{ __('locale.lbl_type') }}</th>
                                        <th>{{ __('locale.lbl_amount_ht') ?? 'Montant HT' }}</th>
                                        <th>{{ __('locale.lbl_status') }}</th>
                                        <th>{{ __('locale.lbl_start') }}</th>
                                        <th>{{ __('locale.lbl_end') }}</th>
                                        <th class="text-end">{{ __('locale.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($client->contracts as $contract)
                                        @php
                                            $cStatus = $contract->calculateStatus();
                                            $cBadgeClass = match ($cStatus) {
                                                'avec_contrat' => 'badge-matte-success',
                                                'contrat_expire' => 'badge-matte-danger',
                                                'contrat_resilie' => 'badge-matte-warning',
                                                default => 'badge-matte-secondary',
                                            };
                                        @endphp
                                        <tr>
                                            <td>
                                                <a href="{{ route('contracts.show', $contract->id) }}" class="font-monospace fw-bold text-decoration-none text-primary">
                                                    {{ $contract->contract_number ?: ($contract->contract_name ?: '#' . $contract->id) }}
                                                </a>
                                                @if($contract->odoo_id)
                                                    <span class="badge bg-light text-secondary border ms-1">Odoo #{{ $contract->odoo_id }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($contract->company)
                                                    <span class="badge bg-light text-dark border">{{ $contract->company->name }}</span>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div>{{ $contract->template->contractType->translated_name ?? $contract->contractType->translated_name ?? ($contract->contract_name ?? '—') }}</div>
                                                @if($contract->recurring_plan_name)
                                                    <small class="text-muted">{{ $contract->recurring_plan_name }}</small>
                                                @endif
                                            </td>
                                            <td class="font-monospace fw-semibold">
                                                {{ $contract->amount_untaxed !== null ? number_format($contract->amount_untaxed, 2, ',', ' ') . ' ' . ($contract->currency ?: '€') : '—' }}
                                            </td>
                                            <td>
                                                <span class="badge {{ $cBadgeClass }}">{{ __('locale.contract_status.' . $cStatus) }}</span>
                                                @if($contract->subscription_state)
                                                    <span class="badge bg-light text-muted border small d-block mt-1">{{ $contract->subscription_state_label }}</span>
                                                @endif
                                            </td>
                                            <td class="small font-monospace">{{ $contract->date_debut_validite ? \Carbon\Carbon::parse($contract->date_debut_validite)->format('d/m/Y') : '—' }}</td>
                                            <td class="small font-monospace">{{ $contract->date_fin_validite ? \Carbon\Carbon::parse($contract->date_fin_validite)->format('d/m/Y') : '—' }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('contracts.show', $contract->id) }}" class="btn btn-sm btn-outline-secondary py-0 px-2" title="{{ __('locale.view') }}"><i class="bi bi-eye"></i></a>
                                                <a href="{{ route('contracts.edit', $contract->id) }}" class="btn btn-sm btn-outline-primary py-0 px-2 ms-1" title="{{ __('locale.edit') }}"><i class="bi bi-pencil"></i></a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="8" class="text-center py-3 text-muted">{{ __('locale.no_contracts_associated') }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- ORDERS TAB -->
                @if($canViewOrders)
                    <div class="tab-pane fade" id="orders-tab-pane" role="tabpanel" tabindex="0">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold mb-0 text-secondary">
                                <i class="bi bi-cart3 me-2 text-primary"></i>{{ __('locale.orders.title') }} ({{ $client->orders->count() }})
                            </h6>
                            @if(Auth::user()->is_admin == 1 || Auth::user()->isNovalyoAdmin() || Auth::user()->hasPermission('create-orders') || Auth::user()->hasPermission('order-access'))
                                <a href="{{ route('orders.create', ['client_id' => $client->id]) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-plus-lg me-1"></i> {{ __('locale.orders.buttons.create') ?? 'Créer une commande' }}
                                </a>
                            @endif
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 odoo-inline-table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 14%;">{{ __('locale.orders.table.reference') ?? 'Référence' }}</th>
                                        <th style="width: 10%;">{{ __('locale.lbl_odoo_id') ?? 'Odoo ID' }}</th>
                                        <th style="width: 12%;">{{ __('locale.orders.table.nature') ?? 'Nature' }}</th>
                                        <th style="width: 11%;">{{ __('locale.orders.table.date') ?? 'Date' }}</th>
                                        <th style="width: 15%;">{{ __('locale.orders.table.status') ?? 'Statut' }}</th>
                                        <th class="text-end" style="width: 12%;">{{ __('locale.orders.table.total_ht') ?? 'Total HT' }}</th>
                                        <th class="text-end" style="width: 12%;">{{ __('locale.orders.table.total_ttc') ?? 'Total TTC' }}</th>
                                        <th class="text-center" style="width: 6%;">{{ __('locale.orders.table.lines') ?? 'Lignes' }}</th>
                                        <th class="text-end" style="width: 8%;">{{ __('locale.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($client->orders as $order)
                                        @php
                                            $st = strtolower($order->statut ?? '');
                                            $orderBadgeClass = 'badge-matte-secondary';
                                            if (str_contains($st, 'factur') || str_contains($st, 'livr') || str_contains($st, 'clos') || str_contains($st, 'term')) {
                                                $orderBadgeClass = 'badge-matte-success';
                                            } elseif (str_contains($st, 'annul') || str_contains($st, 'rejet') || str_contains($st, 'bloqu')) {
                                                $orderBadgeClass = 'badge-matte-danger';
                                            } elseif (str_contains($st, 'cours') || str_contains($st, 'attente') || str_contains($st, 'valid')) {
                                                $orderBadgeClass = 'badge-matte-warning';
                                            } elseif (str_contains($st, 'devis') || str_contains($st, 'brouillon')) {
                                                $orderBadgeClass = 'badge-matte-info';
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                <a href="{{ route('orders.show', $order->id) }}" class="font-monospace fw-bold text-decoration-none text-primary">
                                                    {{ $order->ref_interne ?: ($order->numero ?: '#' . $order->id) }}
                                                </a>
                                            </td>
                                            <td>
                                                @if ($order->odoo_id)
                                                    @php
                                                        $odooOrderUrl = config('services.odoo.url')
                                                            ? rtrim(config('services.odoo.url'), '/') . '/web#id=' . $order->odoo_id . '&model=sale.order&view_type=form'
                                                            : null;
                                                    @endphp
                                                    @if($odooOrderUrl)
                                                        <a href="{{ $odooOrderUrl }}" target="_blank" rel="noopener" class="badge bg-secondary-subtle text-secondary border border-secondary-subtle font-monospace text-decoration-none" title="Ouvrir dans Odoo">
                                                            <i class="bi bi-hash me-1"></i>{{ $order->odoo_id }} <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                                        </a>
                                                    @else
                                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle font-monospace"><i class="bi bi-hash me-1"></i>{{ $order->odoo_id }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border">{{ $order->nature_piece ?: '—' }}</span>
                                            </td>
                                            <td class="small font-monospace">
                                                {{ $order->date_piece ? \Carbon\Carbon::parse($order->date_piece)->format('d/m/Y') : '—' }}
                                            </td>
                                            <td>
                                                @if ($order->statut)
                                                    <span class="badge {{ $orderBadgeClass }}">{{ $order->statut }}</span>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                                @if ($order->odoo_id)
                                                    @if ($order->state === 'sale')
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle small ms-1" title="Confirmé dans Odoo">
                                                            <i class="bi bi-check me-1"></i>{{ __('locale.lbl_odoo_sale') ?? 'Vente' }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle small ms-1" title="Brouillon Odoo">
                                                            <i class="bi bi-file-earmark me-1"></i>{{ __('locale.lbl_odoo_draft') ?? 'Devis' }}
                                                        </span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-end font-monospace">
                                                {{ $order->total_ht !== null ? number_format($order->total_ht, 2, ',', ' ') . ' €' : '—' }}
                                            </td>
                                            <td class="text-end font-monospace fw-semibold">
                                                {{ $order->total_ttc !== null ? number_format($order->total_ttc, 2, ',', ' ') . ' €' : '—' }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-secondary border">{{ $order->lines_count ?? $order->lines->count() }}</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-secondary py-0 px-2" title="{{ __('locale.view') }}">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-outline-primary py-0 px-2 ms-1" title="{{ __('locale.edit') }}">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="9" class="text-center py-3 text-muted">{{ __('locale.orders.index.no_results') ?? 'Aucune commande trouvée.' }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- 7. EQUIPMENTS TAB -->
                @if($canViewEquipments)
                    <div class="tab-pane fade" id="equipments-tab-pane" role="tabpanel" tabindex="0">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold mb-0 text-secondary"><i class="bi bi-laptop me-2 text-primary"></i>{{ __('locale.client_equipment_fleet') }} ({{ $client->clientEquipments->count() }})</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addEquipmentModal">
                                <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_add_equipment') }}
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 odoo-inline-table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 25%;">{{ __('locale.lbl_equipment') }}</th>
                                        <th style="width: 20%;">{{ __('locale.serial_number') }}</th>
                                        <th style="width: 15%;">{{ __('locale.lbl_state') }}</th>
                                        <th style="width: 15%;">Suivi SAV</th>
                                        <th style="width: 15%;">{{ __('locale.lbl_location') }}</th>
                                        <th class="text-end" style="width: 10%;">{{ __('locale.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($client->clientEquipments as $ce)
                                        @php
                                            $lastRepair = $ce->equipmentRepairs->last();
                                        @endphp
                                        <tr>
                                            <td class="fw-semibold text-truncate">
                                                {{ $ce->equipment->name ?? '—' }}
                                                @if($ce->equipment?->reference)
                                                    <div class="text-muted small font-monospace">{{ $ce->equipment->reference }}</div>
                                                @endif
                                            </td>
                                            <td class="font-monospace small text-truncate fw-bold">
                                                {{ $ce->serial_number ?: ($ce->equipment->reference ?? '—') }}
                                            </td>
                                            <td><span class="badge bg-secondary">{{ $ce->state->name ?? 'Opérationnel' }}</span></td>
                                            <td>
                                                @if($lastRepair)
                                                    <a href="{{ route('repairs.show', $lastRepair->repair_id) }}" class="badge bg-warning-subtle text-warning-emphasis border text-decoration-none">
                                                        <i class="bi bi-wrench me-1"></i>SAV #{{ $lastRepair->repair_id }}
                                                    </a>
                                                @else
                                                    <span class="text-muted small">Aucun SAV</span>
                                                @endif
                                            </td>
                                            <td class="small text-truncate">{{ $ce->location ?: ($ce->comment ?: '—') }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('repairs.create', ['client_id' => $client->id, 'client_equipment_id' => $ce->id]) }}" class="btn btn-sm btn-outline-primary py-0 px-2 me-1" title="Ouvrir un dossier SAV">
                                                    <i class="bi bi-wrench"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 edit-equipment-btn" 
                                                        data-bs-toggle="modal" data-bs-target="#editEquipmentModal"
                                                        data-id="{{ $ce->id }}"
                                                        data-equipment-id="{{ $ce->equipment_id }}"
                                                        data-serial-number="{{ $ce->serial_number }}"
                                                        data-location="{{ $ce->location }}"
                                                        data-equipment-state-id="{{ $ce->equipment_state_id }}"
                                                        data-equipment-support-id="{{ $ce->equipment_support_id }}"
                                                        data-comment="{{ $ce->comment }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center py-3 text-muted">{{ __('locale.no_equipments_recorded') }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- 8. CONTACTS TAB -->
                @if($canViewContacts)
                    <div class="tab-pane fade" id="contacts-tab-pane" role="tabpanel" tabindex="0">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="fw-bold mb-0 text-secondary"><i class="bi bi-people me-2 text-primary"></i>{{ __('locale.menu_contacts') }} ({{ $client->contacts->count() }})</h6>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addContactModal">
                                    <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_add_contact') }}
                                </button>
                                <a href="{{ route('contacts.create', ['client_id' => $client->id]) }}" class="btn btn-sm btn-outline-secondary" title="Page complète">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 odoo-inline-table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 15%;">{{ __('locale.lbl_type') ?? 'Type' }}</th>
                                        <th style="width: 25%;">{{ __('locale.lbl_fullname') }}</th>
                                        <th style="width: 20%;">{{ __('locale.contact_address') }}</th>
                                        <th style="width: 20%;">{{ __('locale.lbl_email') }}</th>
                                        <th style="width: 12%;">{{ __('locale.lbl_telephone') }}</th>
                                        <th class="text-end" style="width: 8%;">{{ __('locale.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($client->contacts as $contact)
                                        @php
                                            $typeBadgeClass = match($contact->type) {
                                                'delivery' => 'bg-info bg-opacity-10 text-info border border-info border-opacity-25',
                                                'invoice' => 'bg-success bg-opacity-10 text-success border border-success border-opacity-25',
                                                'other' => 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25',
                                                default => 'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25',
                                            };
                                            $typeIcon = match($contact->type) {
                                                'delivery' => 'bi-truck',
                                                'invoice' => 'bi-file-earmark-text',
                                                'other' => 'bi-geo-alt',
                                                default => 'bi-person',
                                            };
                                            $fullName = trim(($contact->prenom ?? '') . ' ' . ($contact->nom ?? ''));
                                            if (empty($fullName)) {
                                                $fullName = $contact->nom ?: ($contact->prenom ?: '—');
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                <span class="badge {{ $typeBadgeClass }} fw-semibold px-2 py-1">
                                                    <i class="bi {{ $typeIcon }} me-1"></i>{{ $contact->type_label }}
                                                </span>
                                                @if($contact->primary)
                                                    <span class="badge bg-warning text-dark ms-1" title="{{ __('locale.primary') }}"><i class="bi bi-star-fill"></i></span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $contact->civility ? $contact->civility . ' ' : '' }}{{ $fullName }}</div>
                                                <div class="d-flex align-items-center gap-1 flex-wrap mt-1">
                                                    @if($contact->odoo_id)
                                                        @if(config('services.odoo.url'))
                                                            <a href="{{ rtrim(config('services.odoo.url'), '/') }}/web#id={{ $contact->odoo_id }}&model=res.partner&view_type=form" target="_blank" rel="noopener" class="badge bg-secondary-subtle text-secondary border px-1 py-0 small text-decoration-none font-monospace" style="font-size: 0.75rem;" title="Ouvrir dans Odoo">
                                                                <i class="bi bi-box-arrow-up-right me-1" style="font-size: 8px;"></i>Odoo #{{ $contact->odoo_id }}
                                                            </a>
                                                        @else
                                                            <span class="badge bg-light text-secondary border px-1 py-0 small font-monospace" style="font-size: 0.75rem;">Odoo #{{ $contact->odoo_id }}</span>
                                                        @endif
                                                    @endif
                                                    @if($contact->langue)
                                                        <span class="badge bg-light text-primary border px-1 py-0 small" style="font-size: 0.75rem;"><i class="bi bi-translate me-1"></i>{{ $contact->langue }}</span>
                                                    @endif
                                                    @if($contact->function_code)
                                                        <span class="text-muted small"><i class="bi bi-briefcase me-1"></i>{{ $contact->function_code }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="small text-muted">
                                                @if($contact->adresse_1 || $contact->ville)
                                                    <div>{{ $contact->adresse_1 }}</div>
                                                    <div>{{ trim(($contact->code_postal ?? '') . ' ' . ($contact->ville ?? '')) }}</div>
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td class="small">
                                                @if($contact->email)
                                                    <a href="mailto:{{ $contact->email }}" class="text-decoration-none">{{ $contact->email }}</a>
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td class="small">
                                                <div>{{ $contact->phone1 ?? $contact->mobile ?? '—' }}</div>
                                                @if($contact->phone1 && $contact->mobile)
                                                    <div class="text-muted small"><i class="bi bi-phone me-1"></i>{{ $contact->mobile }}</div>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('contacts.edit', $contact->id) }}" class="btn btn-outline-warning py-0 px-2" title="{{ __('locale.edit') }}"><i class="bi bi-pencil"></i></a>
                                                    <a href="{{ route('contacts.show', $contact->id) }}" class="btn btn-outline-secondary py-0 px-2" title="{{ __('locale.view') }}"><i class="bi bi-eye"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center py-4 text-muted">{{ __('locale.no_contacts_recorded') }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- 9. SITES / FILIALES TAB -->
                @if($canViewSites)
                    <div class="tab-pane fade" id="sites-tab-pane" role="tabpanel" tabindex="0">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold mb-0 text-secondary"><i class="bi bi-diagram-3 me-2 text-primary"></i>{{ __('locale.attached_sites') }} ({{ $client->children->count() }})</h6>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 odoo-inline-table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 35%;">{{ __('locale.lbl_raison_sociale') }}</th>
                                        <th style="width: 20%;">{{ __('locale.lbl_ville') }}</th>
                                        <th style="width: 20%;">{{ __('locale.lbl_code_postal') }}</th>
                                        <th style="width: 15%;">{{ __('locale.lbl_contract_status') }}</th>
                                        <th class="text-end" style="width: 10%;">{{ __('locale.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($client->children as $child)
                                        <tr>
                                            <td class="fw-semibold">{{ $child->raison_sociale }}</td>
                                            <td>{{ $child->ville ?: '—' }}</td>
                                            <td>{{ $child->code_postal ?: '—' }}</td>
                                            <td><span class="badge bg-secondary">{{ $child->contract_status->value ?? '—' }}</span></td>
                                            <td class="text-end">
                                                <a href="{{ route('clients.show', $child->id) }}" class="btn btn-sm btn-outline-secondary py-0 px-2"><i class="bi bi-eye"></i></a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center py-3 text-muted">{{ __('locale.no_attached_sites') }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- 10. USERS TAB -->
                @if($canViewUsers)
                    <div class="tab-pane fade" id="users-tab-pane" role="tabpanel" tabindex="0">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold mb-0 text-secondary"><i class="bi bi-person-check me-2 text-primary"></i>{{ __('locale.assigned_users') }} ({{ $client->assignedUsers->count() }})</h6>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 odoo-inline-table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 30%;">{{ __('locale.lbl_name') }}</th>
                                        <th style="width: 35%;">{{ __('locale.lbl_email') }}</th>
                                        <th style="width: 20%;">{{ __('locale.lbl_role') }}</th>
                                        <th class="text-end" style="width: 15%;">{{ __('locale.lbl_status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($client->assignedUsers as $u)
                                        <tr>
                                            <td class="fw-semibold">{{ $u->name }}</td>
                                            <td class="small">{{ $u->email }}</td>
                                            <td><span class="badge bg-light text-dark border">{{ $u->role->name ?? __('locale.user') }}</span></td>
                                            <td class="text-end"><span class="badge bg-success-subtle text-success">{{ __('locale.active') }}</span></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center py-3 text-muted">{{ __('locale.no_assigned_users') }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- 11. PORTAL PERMISSIONS TAB -->
                <div class="tab-pane fade" id="portal-tab-pane" role="tabpanel" tabindex="0">
                    <div class="notes-title"><i class="bi bi-shield-check me-1"></i>{{ __('locale.portal_permissions') }}</div>
                    <div class="row g-2 mt-2">
                        @php
                            $portalFlags = [
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
                        @foreach($portalFlags as $flagKey => $flagLabel)
                            <div class="col-md-4 col-sm-6">
                                <div class="d-flex align-items-center gap-2 p-2 border rounded bg-light">
                                    <i class="bi {{ $client->$flagKey ? 'bi-check-circle-fill text-success' : 'bi-x-circle text-muted' }} fs-5"></i>
                                    <span class="small {{ $client->$flagKey ? 'fw-bold text-dark' : 'text-muted' }}">{{ $flagLabel }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Upload Document Modal -->
@if($canAddDocs)
<div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-labelledby="uploadDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('clients.documents.upload', $client->id) }}" method="POST" enctype="multipart/form-data" class="modal-content shadow border-0 rounded-3">
            @csrf
            <div class="modal-header bg-light border-0 py-3">
                <h5 class="modal-title fs-6 fw-bold" id="uploadDocumentModalLabel">
                    <i class="bi bi-cloud-arrow-up me-2 text-primary"></i>{{ __('locale.btn_upload_document') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="mb-3">
                    <label for="doc_file" class="form-label fw-semibold small">{{ __('locale.lbl_select_file') }} <span class="text-danger">*</span></label>
                    <input type="file" name="document" id="doc_file" class="form-control" required>
                    <div class="form-text small">{{ __('locale.accepted_formats_help') }}</div>
                </div>

                <div class="mb-3">
                    <label for="doc_title" class="form-label fw-semibold small">{{ __('locale.lbl_document_title') }}</label>
                    <input type="text" name="title" id="doc_title" class="form-control" placeholder="{{ __('locale.placeholder_doc_title') }}">
                </div>

                <div class="mb-3">
                    <label for="doc_type_id" class="form-label fw-semibold small">{{ __('locale.lbl_document_type') }}</label>
                    <select name="client_document_type_id" id="doc_type_id" class="form-select">
                        <option value="">-- {{ __('locale.select_type') }} --</option>
                        @foreach ($documentTypes as $dt)
                            <option value="{{ $dt->id }}">{{ $dt->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-2">
                    <label for="doc_expires_at" class="form-label fw-semibold small">{{ __('locale.lbl_expiry_date') }}</label>
                    <input type="date" name="expires_at" id="doc_expires_at" class="form-control">
                </div>
            </div>
            <div class="modal-footer bg-light border-0 py-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">{{ __('locale.btn_cancel') }}</button>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-upload me-1"></i> {{ __('locale.btn_upload') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<!-- Quick Add Contract Modal -->
<div class="modal fade" id="quickAddContractModal" tabindex="-1" aria-labelledby="quickAddContractModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('contracts.store') }}" method="POST">
            @csrf
            <input type="hidden" name="client_id" value="{{ $client->id }}">
            <div class="modal-content shadow border-0 rounded-3">
                <div class="modal-header bg-light border-0 py-3">
                    <h5 class="modal-title fs-6 fw-bold" id="quickAddContractModalLabel">{{ __('locale.contracts.new') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-semibold">{{ __('locale.contracts.type') }}</label>
                            <select name="contract_type_id" class="form-select" required>
                                <option value="">{{ __('locale.contracts.select_type') }}</option>
                                @foreach ($contractTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->translated_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">{{ __('locale.contracts.terms.date_start') }}</label>
                            <input type="date" name="date_debut_validite" class="form-control" value="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">{{ __('locale.contracts.terms.date_end') }}</label>
                            <input type="date" name="date_fin_validite" class="form-control" value="{{ now()->addYear()->format('Y-m-d') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">{{ __('locale.btn_cancel') }}</button>
                    <button type="submit" class="btn btn-primary btn-sm">{{ __('locale.btn_create') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Add Equipment Modal -->
<div class="modal fade" id="addEquipmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('clients.equipments.store', $client->id) }}" method="POST" class="modal-content shadow border-0 rounded-3">
            @csrf
            <div class="modal-header bg-light border-0 py-3">
                <h5 class="modal-title fs-6 fw-bold">{{ __('locale.add_equipment_to_client') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="mb-3">
                    <label class="form-label small fw-semibold">{{ __('locale.lbl_equipment') }} <span class="text-danger">*</span></label>
                    <select name="equipment_id" class="form-select" required>
                        <option value="">{{ __('locale.select_equipment') }}</option>
                        @foreach($equipments as $eq)
                            <option value="{{ $eq->id }}">{{ $eq->name }} ({{ $eq->reference }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">{{ __('locale.serial_number') }}</label>
                        <input type="text" name="serial_number" class="form-control font-monospace" placeholder="S/N ou IPEI...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">{{ __('locale.lbl_location') }}</label>
                        <input type="text" name="location" class="form-control" placeholder="Chambre, Service...">
                    </div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">{{ __('locale.lbl_state') }}</label>
                        <select name="equipment_state_id" class="form-select">
                            <option value="">{{ __('locale.select') }}</option>
                            @foreach($equipmentStates as $st)
                                <option value="{{ $st->id }}">{{ $st->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">{{ __('locale.lbl_support') }}</label>
                        <select name="equipment_support_id" class="form-select">
                            <option value="">{{ __('locale.select') }}</option>
                            @foreach($equipmentSupports as $sp)
                                <option value="{{ $sp->id }}">{{ $sp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label small fw-semibold">{{ __('locale.lbl_comment_location') }}</label>
                    <textarea name="comment" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light border-0 py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">{{ __('locale.btn_cancel') }}</button>
                <button type="submit" class="btn btn-primary btn-sm">{{ __('locale.btn_add') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Equipment Modal -->
<div class="modal fade" id="editEquipmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editEquipmentForm" action="" method="POST" class="modal-content shadow border-0 rounded-3">
            @csrf
            @method('PUT')
            <div class="modal-header bg-light border-0 py-3">
                <h5 class="modal-title fs-6 fw-bold">{{ __('locale.edit_equipment') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <input type="hidden" name="equipment_id" id="edit_equipment_id">
                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">{{ __('locale.serial_number') }}</label>
                        <input type="text" name="serial_number" id="edit_serial_number" class="form-control font-monospace" placeholder="S/N ou IPEI...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">{{ __('locale.lbl_location') }}</label>
                        <input type="text" name="location" id="edit_location" class="form-control" placeholder="Chambre, Service...">
                    </div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">{{ __('locale.lbl_state') }}</label>
                        <select name="equipment_state_id" id="edit_equipment_state_id" class="form-select">
                            <option value="">{{ __('locale.select') }}</option>
                            @foreach($equipmentStates as $st)
                                <option value="{{ $st->id }}">{{ $st->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">{{ __('locale.lbl_support') }}</label>
                        <select name="equipment_support_id" id="edit_equipment_support_id" class="form-select">
                            <option value="">{{ __('locale.select') }}</option>
                            @foreach($equipmentSupports as $sp)
                                <option value="{{ $sp->id }}">{{ $sp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label small fw-semibold">{{ __('locale.lbl_comment_location') }}</label>
                    <textarea name="comment" id="edit_comment" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light border-0 py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">{{ __('locale.btn_cancel') }}</button>
                <button type="submit" class="btn btn-primary btn-sm">{{ __('locale.btn_update') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Contact Modal (Odoo Style) -->
<div class="modal fade" id="addContactModal" tabindex="-1" aria-labelledby="addContactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="{{ route('contacts.store') }}" method="POST" class="modal-content shadow border-0 rounded-3">
            @csrf
            <input type="hidden" name="client_id" value="{{ $client->id }}">
            <input type="hidden" name="redirect_to" value="client">

            <div class="modal-header bg-light border-0 py-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-person-plus text-primary fs-5"></i>
                    <h5 class="modal-title fs-6 fw-bold mb-0" id="addContactModalLabel">{{ __('locale.btn_add_contact') }}</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <!-- Odoo Type Radio Selector -->
                <div class="odoo-type-selector d-flex align-items-center flex-wrap gap-3 pb-3 mb-3 border-bottom">
                    <label class="form-check form-check-inline cursor-pointer mb-0">
                        <input class="form-check-input modal-odoo-type-radio" type="radio" name="type" id="modal_type_contact" value="contact" checked>
                        <span class="form-check-label fw-semibold ms-1">{{ __('locale.type_contact') }}</span>
                    </label>
                    <label class="form-check form-check-inline cursor-pointer mb-0">
                        <input class="form-check-input modal-odoo-type-radio" type="radio" name="type" id="modal_type_invoice" value="invoice">
                        <span class="form-check-label fw-semibold ms-1">{{ __('locale.type_invoice') }}</span>
                    </label>
                    <label class="form-check form-check-inline cursor-pointer mb-0">
                        <input class="form-check-input modal-odoo-type-radio" type="radio" name="type" id="modal_type_delivery" value="delivery">
                        <span class="form-check-label fw-semibold ms-1">{{ __('locale.type_delivery') }}</span>
                    </label>
                    <label class="form-check form-check-inline cursor-pointer mb-0">
                        <input class="form-check-input modal-odoo-type-radio" type="radio" name="type" id="modal_type_other" value="other">
                        <span class="form-check-label fw-semibold ms-1">{{ __('locale.type_other') }}</span>
                    </label>
                </div>

                <div class="row g-3">
                    <!-- Left Icon Container -->
                    <div class="col-sm-3 text-center text-sm-start">
                        <div class="odoo-icon-container p-3 rounded-3 bg-light d-flex align-items-center justify-content-center border shadow-xs" style="min-height: 90px;">
                            <div id="modal_icon_delivery" class="modal-odoo-type-icon d-none">
                                <svg width="60" height="60" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 22L32 6L60 22L32 38L4 22Z" fill="#E8EEF5"/>
                                    <path d="M4 22L32 38V58L4 42V22Z" fill="#C5D3E8"/>
                                    <path d="M60 22L32 38V58L60 42V22Z" fill="#9FB4D4"/>
                                    <path d="M38 32L54 23L58 26L42 35L38 32Z" fill="#5A96D8"/>
                                    <path d="M42 35L58 26V36L42 45V35Z" fill="#3D75B5"/>
                                    <circle cx="20" cy="47" r="5" fill="#333333"/>
                                    <circle cx="20" cy="47" r="2.5" fill="#B0B8C1"/>
                                    <circle cx="48" cy="47" r="5" fill="#333333"/>
                                    <circle cx="48" cy="47" r="2.5" fill="#B0B8C1"/>
                                </svg>
                            </div>
                            <div id="modal_icon_invoice" class="modal-odoo-type-icon d-none">
                                <svg width="56" height="56" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="12" y="8" width="40" height="48" rx="4" fill="#E8EEF5" stroke="#3D75B5" stroke-width="2"/>
                                    <line x1="20" y1="20" x2="44" y2="20" stroke="#3D75B5" stroke-width="3" stroke-linecap="round"/>
                                    <line x1="20" y1="28" x2="44" y2="28" stroke="#9FB4D4" stroke-width="2" stroke-linecap="round"/>
                                    <line x1="20" y1="36" x2="36" y2="36" stroke="#9FB4D4" stroke-width="2" stroke-linecap="round"/>
                                    <circle cx="42" cy="44" r="6" fill="#198754"/>
                                    <path d="M39.5 44L41.5 46L45 42" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <div id="modal_icon_contact" class="modal-odoo-type-icon">
                                <svg width="56" height="56" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="32" cy="22" r="12" fill="#5A96D8"/>
                                    <path d="M12 54C12 43 21 39 32 39C43 39 52 43 52 54" fill="#C5D3E8"/>
                                </svg>
                            </div>
                            <div id="modal_icon_other" class="modal-odoo-type-icon d-none">
                                <svg width="56" height="56" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M32 8C22 8 14 16 14 26C14 39 32 56 32 56C32 56 50 39 50 26C50 16 42 8 32 8Z" fill="#E8EEF5" stroke="#E67E22" stroke-width="2"/>
                                    <circle cx="32" cy="26" r="7" fill="#E67E22"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Right Fields Form -->
                    <div class="col-sm-9">
                        <!-- Names row -->
                        <div class="row g-2 mb-2">
                            <div class="col-md-3">
                                <label for="modal_civility" class="form-label small text-muted mb-1">{{ __('locale.civility') }}</label>
                                <select name="civility" id="modal_civility" class="form-select form-select-sm">
                                    <option value="">--</option>
                                    <option value="M.">M.</option>
                                    <option value="Mme">Mme</option>
                                    <option value="Mr">Mr</option>
                                    <option value="Dr">Dr</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="modal_prenom" class="form-label small text-muted mb-1">{{ __('locale.prenom') }}</label>
                                <input type="text" name="prenom" id="modal_prenom" class="form-control form-control-sm" maxlength="255" placeholder="Brandon">
                            </div>
                            <div class="col-md-5">
                                <label for="modal_nom" class="form-label small text-muted mb-1">{{ __('locale.nom') }} <span class="text-danger">*</span></label>
                                <input type="text" name="nom" id="modal_nom" class="form-control form-control-sm fw-semibold" maxlength="255" placeholder="Freeman" required>
                            </div>
                        </div>

                        <!-- Communication row -->
                        <div class="row g-2 mb-2">
                            <div class="col-md-4">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light text-primary"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" id="modal_email" class="form-control" placeholder="{{ __('locale.email') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light text-primary"><i class="bi bi-telephone"></i></span>
                                    <input type="text" name="phone1" id="modal_phone1" class="form-control" placeholder="{{ __('locale.lbl_phone') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light text-primary"><i class="bi bi-phone"></i></span>
                                    <input type="text" name="mobile" id="modal_mobile" class="form-control" placeholder="{{ __('locale.lbl_mobile') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Address Section -->
                        <div class="p-3 rounded-3 bg-light bg-opacity-50 border mb-2">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fw-bold text-secondary small text-uppercase" id="modal_address_section_title">
                                    {{ __('locale.address_contact') }}
                                </span>
                                <button type="button" class="btn btn-xs btn-link text-decoration-none p-0" id="modal_btn_copy_address">
                                    <i class="bi bi-arrow-down-left-square me-1"></i>{{ __('locale.copy_client_address') ?? 'Copier l\'adresse du client' }}
                                </button>
                            </div>

                            <div class="row g-2">
                                <div class="col-12">
                                    <input type="text" name="adresse_1" id="modal_adresse_1" class="form-control form-control-sm mb-1" placeholder="{{ __('locale.street_1') }} (ex. 80 avenue Archimède)">
                                    <input type="text" name="adresse_2" id="modal_adresse_2" class="form-control form-control-sm" placeholder="{{ __('locale.street_2') }}">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="code_postal" id="modal_code_postal" class="form-control form-control-sm" placeholder="{{ __('locale.code_postal') }}">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" name="ville" id="modal_ville" class="form-control form-control-sm" placeholder="{{ __('locale.ville') }}">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="state" id="modal_state" class="form-control form-control-sm" placeholder="{{ __('locale.state_province') }}">
                                </div>
                                <div class="col-12">
                                    <input type="text" name="pays" id="modal_pays" class="form-control form-control-sm" placeholder="{{ __('locale.country') }}" value="France">
                                </div>
                            </div>
                        </div>

                        <!-- GLN -->
                        <div class="row g-2 mb-2 align-items-center">
                            <div class="col-md-2">
                                <label for="modal_gln" class="form-label small text-muted mb-0 d-flex align-items-center gap-1">
                                    <span>{{ __('locale.gln') }}</span>
                                    <i class="bi bi-question-circle text-muted" data-bs-toggle="tooltip" title="{{ __('locale.gln_hint') }}"></i>
                                </label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" name="gln" id="modal_gln" class="form-control form-control-sm" placeholder="GLN">
                            </div>
                        </div>

                        <!-- Notes Internes -->
                        <div class="row g-2 mb-2 align-items-start">
                            <div class="col-md-2">
                                <label for="modal_notes" class="form-label small text-muted mb-0 d-flex align-items-center gap-1">
                                    <i class="bi bi-grip-vertical text-muted"></i>
                                    <span>{{ __('locale.notes') }}</span>
                                </label>
                            </div>
                            <div class="col-md-10">
                                <textarea name="notes" id="modal_notes" rows="2" class="form-control form-control-sm" placeholder="{{ __('locale.internal_notes') }}"></textarea>
                            </div>
                        </div>

                        <!-- Function, Language, Odoo ID & Primary switch -->
                        <div class="row g-2 pt-2 border-top align-items-center">
                            <div class="col-md-4">
                                <input type="text" name="function_code" id="modal_function_code" class="form-control form-control-sm" maxlength="100" placeholder="{{ __('locale.job_position') ?? 'Poste occupé' }}">
                            </div>
                            <div class="col-md-3">
                                <select name="langue" id="modal_langue" class="form-select form-select-sm">
                                    @php
                                        $allModalLangs = \App\Models\Language::where('is_active', true)->orderBy('name')->get();
                                        $defaultLangName = \App\Models\Language::getDefault()?->name ?? 'Français';
                                    @endphp
                                    @foreach($allModalLangs as $mOption)
                                        <option value="{{ $mOption->name }}" @selected($mOption->name === $defaultLangName)>{{ $mOption->name }}</option>
                                    @endforeach
                                    @if($allModalLangs->isEmpty())
                                        <option value="Français" selected>Français</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="odoo_id" id="modal_odoo_id" class="form-control form-control-sm font-monospace" placeholder="Odoo ID">
                            </div>
                            <div class="col-md-3">
                                <div class="form-check form-switch ms-2">
                                    <input class="form-check-input" type="checkbox" name="primary" id="modal_primary">
                                    <label class="form-check-label small fw-medium" for="modal_primary">{{ __('locale.primary') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light border-0 py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">{{ __('locale.btn_cancel') }}</button>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-check-lg me-1"></i> {{ __('locale.save') }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-select tab if URL parameter ?tab=... is present
    const urlParams = new URLSearchParams(window.location.search);
    const activeTabParam = urlParams.get('tab');
    if (activeTabParam) {
        const targetTab = document.getElementById(`${activeTabParam}-tab`);
        if (targetTab) {
            bootstrap.Tab.getOrCreateInstance(targetTab).show();
        }
    }

    // Modal Type Switcher
    const modalTitles = {
        contact: "{{ __('locale.address_contact') }}",
        invoice: "{{ __('locale.address_invoice') }}",
        delivery: "{{ __('locale.address_delivery') }}",
        other: "{{ __('locale.address_other') }}"
    };

    const updateModalTypeUI = (type) => {
        document.querySelectorAll('.modal-odoo-type-icon').forEach(el => el.classList.add('d-none'));
        const activeIcon = document.getElementById(`modal_icon_${type}`);
        if (activeIcon) activeIcon.classList.remove('d-none');

        const sectionTitle = document.getElementById('modal_address_section_title');
        if (sectionTitle) sectionTitle.textContent = modalTitles[type] || modalTitles.contact;
    };

    document.querySelectorAll('.modal-odoo-type-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            updateModalTypeUI(this.value);
        });
    });

    // Copy Client Address in Modal
    const modalBtnCopy = document.getElementById('modal_btn_copy_address');
    if (modalBtnCopy) {
        modalBtnCopy.addEventListener('click', function() {
            document.getElementById('modal_adresse_1').value = "{{ $client->adresse_1 }}";
            document.getElementById('modal_adresse_2').value = "{{ $client->adresse_2 }}";
            document.getElementById('modal_code_postal').value = "{{ $client->code_postal }}";
            document.getElementById('modal_ville').value = "{{ $client->ville }}";
            document.getElementById('modal_pays').value = "{{ $client->pays ?: 'France' }}";
        });
    }

    // Equipment Edit Modal
    const editBtns = document.querySelectorAll('.edit-equipment-btn');
    const editForm = document.getElementById('editEquipmentForm');
    
    editBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const equipmentId = this.getAttribute('data-equipment-id');
            const serialNumber = this.getAttribute('data-serial-number');
            const location = this.getAttribute('data-location');
            const stateId = this.getAttribute('data-equipment-state-id');
            const supportId = this.getAttribute('data-equipment-support-id');
            const comment = this.getAttribute('data-comment');

            editForm.action = `/clients/{{ $client->id }}/equipments/${id}`;
            document.getElementById('edit_equipment_id').value = equipmentId;
            const snInput = document.getElementById('edit_serial_number');
            if (snInput) snInput.value = serialNumber || '';
            const locInput = document.getElementById('edit_location');
            if (locInput) locInput.value = location || '';
            document.getElementById('edit_equipment_state_id').value = stateId;
            document.getElementById('edit_equipment_support_id').value = supportId;
            document.getElementById('edit_comment').value = comment || '';
        });
    });
});
</script>
@endpush
@endsection
