@extends('layouts.app')
@section('portal-module', 'suppliers')
@section('portal-page', 'show')
@section('portal-kind', 'record')
@section('portal-family', 'purchasing')
@section('portal-icon', 'cart-flatbed')


@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">
            {{ $supplier->name }}
        </h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex gap-2">
        @if (Auth::user()->is_admin || Auth::user()->hasPermission('settings-access'))
            <form action="{{ route('suppliers.sync-odoo', $supplier) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-repeat me-1"></i> Sync to Odoo
                </button>
            </form>
        @endif
        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-warning">
            <i class="bi bi-pencil-square me-1"></i> {{ __('locale.edit') ?? 'Modifier' }}
        </a>
    </div>
@endsection

@section('content')
<div class="container-xxl flex-grow-1">
    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-4">{{ session('error') }}</div>
    @endif

    <div class="row">
        <!-- Supplier Information -->
        <div class="col-md-4">
            <div class="module-record-card card shadow-sm border-0 mb-4" style="position: relative; overflow: visible !important;">
                @if ($supplier->closed)
                    <div class="ribbon-wrapper">
                        <div class="ribbon">{{ __('locale.lbl_closed') }}</div>
                    </div>
                @endif
                <div class="card-header bg-primary text-white border-0">
                    <h5 class="mb-0 text-white"><i class="bi bi-info-circle me-2 text-white"></i>{{ __('locale.lbl_information') ?? 'Détails du Fournisseur' }}</h5>
                </div>
                <div class="card-body pt-4">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <span class="fw-bold d-block text-muted small text-uppercase">{{ __('locale.lbl_name') ?? 'Raison Sociale' }}</span>
                            <span class="fs-5 fw-bold">{{ $supplier->name }}</span>
                        </li>
                        <li class="mb-3">
                            <span class="fw-bold d-block text-muted small text-uppercase">{{ __('locale.company') ?? 'Société' }}</span>
                            <span class="badge {{ $supplier->company ? 'bg-label-secondary' : 'bg-label-info' }}">
                                @if($supplier->company)
                                    {{ $supplier->company->name }} ({{ $supplier->company->code }})
                                @else
                                    <i class="bi bi-globe me-1"></i>Toutes les sociétés
                                @endif
                            </span>
                        </li>
                        <li class="mb-3">
                            <span class="fw-bold d-block text-muted small text-uppercase">Code CEGID</span>
                            <span class="fw-medium">{{ $supplier->cegid_id ?? '-' }}</span>
                        </li>
                        <li class="mb-3">
                            <span class="fw-bold d-block text-muted small text-uppercase">{{ __('locale.lbl_odoo_id') }}</span>
                            @if($supplier->odoo_id)
                                @if(config('services.odoo.url'))
                                    <a href="{{ rtrim(config('services.odoo.url'), '/') }}/web#id={{ $supplier->odoo_id }}&model=res.partner&view_type=form"
                                       target="_blank"
                                       rel="noopener"
                                       class="text-decoration-none fw-medium"
                                       title="Ouvrir dans Odoo">
                                        {{ $supplier->odoo_id }} <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                    </a>
                                @else
                                    <span class="fw-medium">{{ $supplier->odoo_id }}</span>
                                @endif
                            @else
                                <span class="fw-medium">-</span>
                            @endif
                        </li>
                        <li class="mb-3">
                            <span class="fw-bold d-block text-muted small text-uppercase">{{ __('locale.lbl_type') ?? 'Type' }}</span>
                            @if($supplier->type === 'fabricant')
                                <span class="badge bg-label-primary">{{ __('locale.fabricant') ?? 'Fabricant' }}</span>
                            @else
                                <span class="badge bg-label-info">{{ __('locale.redistributeur') ?? 'Redistributeur' }}</span>
                            @endif
                        </li>
                        <li class="mb-3">
                            <span class="fw-bold d-block text-muted small text-uppercase">Nature</span>
                            <span class="fw-medium">{{ $supplier->nature ?? '-' }}</span>
                        </li>
                        <li class="mb-3">
                            <span class="fw-bold d-block text-muted small text-uppercase">{{ __('locale.lbl_email') ?? 'Email' }}</span>
                            <span>{{ $supplier->contact_email ?? '-' }}</span>
                        </li>
                        <li class="mb-3">
                            <span class="fw-bold d-block text-muted small text-uppercase">{{ __('locale.lbl_phone') ?? 'Téléphone' }}</span>
                            <span>{{ $supplier->contact_phone ?? '-' }}</span>
                        </li>
                        <li class="mb-3">
                            <span class="fw-bold d-block text-muted small text-uppercase">Téléphone n°2</span>
                            <span>{{ $supplier->phone_2 ?? '-' }}</span>
                        </li>
                        <li class="mb-3">
                            <span class="fw-bold d-block text-muted small text-uppercase">{{ __('locale.address') ?? 'Adresse' }}</span>
                            <span>
                                @if($supplier->address)
                                    <div>{{ $supplier->address }}</div>
                                @endif
                                @if($supplier->adresse_2)
                                    <div>{{ $supplier->adresse_2 }}</div>
                                @endif
                                @if($supplier->adresse_3)
                                    <div>{{ $supplier->adresse_3 }}</div>
                                @endif
                                @if($supplier->code_postal || $supplier->ville)
                                    <div>{{ $supplier->code_postal }} {{ $supplier->ville }}</div>
                                @endif
                                @if($supplier->country)
                                    <div>{{ $supplier->country }}</div>
                                @endif
                                @if(!$supplier->address && !$supplier->adresse_2 && !$supplier->adresse_3 && !$supplier->code_postal && !$supplier->ville && !$supplier->country)
                                    -
                                @endif
                            </span>
                        </li>
                        <li class="mb-3">
                            <span class="fw-bold d-block text-muted small text-uppercase">{{ __('locale.lbl_langue') ?? 'Langue' }}</span>
                            <span class="badge bg-label-success">{{ $supplier->langue ?: 'Français' }}</span>
                        </li>
                        <li class="mb-3">
                            <span class="fw-bold d-block text-muted small text-uppercase">Code client chez le fournisseur</span>
                            <span>{{ $supplier->client_code_supplier ?? '-' }}</span>
                        </li>
                        <li class="mb-3">
                            <span class="fw-bold d-block text-muted small text-uppercase">SIREN / SIRET</span>
                            <span>{{ $supplier->siren_siret ?? '-' }}</span>
                        </li>
                        <li class="mb-3">
                            <span class="fw-bold d-block text-muted small text-uppercase">Conditions de règlement</span>
                            <span>{{ $supplier->conditionReglement ? $supplier->conditionReglement->name : '-' }}</span>
                        </li>
                        <li class="mb-3">
                            <span class="fw-bold d-block text-muted small text-uppercase">Code NIF</span>
                            <span>{{ $supplier->code_nif ?? '-' }}</span>
                        </li>
                        <li class="mb-3">
                            <span class="fw-bold d-block text-muted small text-uppercase">Code NAF</span>
                            <span>{{ $supplier->code_naf ?? '-' }}</span>
                        </li>
                        <li class="mb-3">
                            <span class="fw-bold d-block text-muted small text-uppercase">Régime TVA</span>
                            <span>{{ $supplier->regimeTva ? $supplier->regimeTva->name . ' (' . $supplier->regimeTva->code . ')' : '-' }}</span>
                        </li>
                        <li class="mb-3">
                            <span class="fw-bold d-block text-muted small text-uppercase">{{ __('locale.lbl_bank_name') }}</span>
                            <span>{{ $supplier->bank_name ?? '-' }}</span>
                        </li>
                        <li class="mb-3">
                            <span class="fw-bold d-block text-muted small text-uppercase">{{ __('locale.lbl_iban') }}</span>
                            <span>{{ $supplier->iban ?? '-' }}</span>
                        </li>
                        <li class="mb-3">
                            <span class="fw-bold d-block text-muted small text-uppercase">{{ __('locale.lbl_bic') }}</span>
                            <span>{{ $supplier->bic ?? '-' }}</span>
                        </li>
                        <li class="mb-3">
                            <span class="fw-bold d-block text-muted small text-uppercase">{{ __('locale.lbl_notes') ?? 'Notes' }}</span>
                            <span>{!! nl2br(e($supplier->notes ?? '-')) !!}</span>
                        </li>
                        <li class="mb-3">
                            <span class="fw-bold d-block text-muted small text-uppercase">Créé le</span>
                            <span class="small text-muted">{{ $supplier->created_at ? $supplier->created_at->format('d/m/Y H:i') : '-' }}</span>
                        </li>
                        <li class="mb-3">
                            <span class="fw-bold d-block text-muted small text-uppercase">Mis à jour le</span>
                            <span class="small text-muted">{{ $supplier->updated_at ? $supplier->updated_at->format('d/m/Y H:i') : '-' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Tabs for Purchase Orders & Contacts -->
        <div class="col-md-8">
            <ul class="nav nav-tabs mb-3" id="supplierDetailsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active d-flex align-items-center justify-content-between w-100 gap-2" id="purchases-tab" data-bs-toggle="tab" data-bs-target="#purchases" type="button"
                        role="tab" aria-controls="purchases" aria-selected="true">
                        <span>{{ __('locale.purchase_history') ?? 'Historique des Achats' }}</span>
                        <span class="badge rounded-pill bg-secondary ms-auto">{{ $supplier->purchases->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link d-flex align-items-center justify-content-between w-100 gap-2" id="contacts-tab" data-bs-toggle="tab" data-bs-target="#contacts" type="button"
                        role="tab" aria-controls="contacts" aria-selected="false">
                        <span>{{ __('locale.contacts') ?? 'Contacts' }}</span>
                        <span class="badge rounded-pill bg-secondary ms-auto">{{ $supplier->contacts->count() }}</span>
                    </button>
                </li>
            </ul>
            
            <div class="tab-content" id="supplierDetailsTabsContent">
                <!-- Purchases Tab -->
                <div class="tab-pane fade show active" id="purchases" role="tabpanel" aria-labelledby="purchases-tab">
                    <div class="module-record-card card shadow-sm border-0">
                        <div class="card-header border-bottom d-flex justify-content-between align-items-center bg-white py-3">
                            <h5 class="mb-0">{{ __('locale.purchase_history') ?? 'Historique des Achats' }}</h5>
                            <a href="{{ route('purchases.create', ['supplier_id' => $supplier->id]) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_new_purchase') ?? 'Nouvel Achat' }}
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('locale.lbl_reference') ?? 'Référence' }}</th>
                                        <th>{{ __('locale.lbl_date') ?? 'Date' }}</th>
                                        <th>{{ __('locale.expected_delivery') ?? 'Livraison prévue' }}</th>
                                        <th>{{ __('locale.lbl_status') ?? 'Statut' }}</th>
                                        <th class="text-end">{{ __('locale.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($supplier->purchases as $purchase)
                                        <tr>
                                            <td><a href="{{ route('purchases.show', $purchase) }}" class="fw-bold">{{ $purchase->reference }}</a></td>
                                            <td>{{ $purchase->created_at->format('d/m/Y') }}</td>
                                            <td>{{ $purchase->expected_delivery_date ? $purchase->expected_delivery_date->format('d/m/Y') : '-' }}</td>
                                            <td>
                                                @if($purchase->status === 'pending')
                                                    <span class="badge bg-label-warning">{{ __('locale.pending') ?? 'En attente' }}</span>
                                                @elseif($purchase->status === 'received')
                                                    <span class="badge bg-label-success">{{ __('locale.received') ?? 'Reçu' }}</span>
                                                @else
                                                    <span class="badge bg-label-danger">{{ __('locale.cancelled') ?? 'Annulé' }}</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <p class="text-muted mb-0">{{ __('locale.lbl_no_records_found') }}</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Contacts Tab -->
                <div class="tab-pane fade" id="contacts" role="tabpanel" aria-labelledby="contacts-tab">
                    <div class="module-record-card card shadow-sm border-0">
                        <div class="card-header border-bottom d-flex justify-content-between align-items-center bg-white py-3">
                            <h5 class="mb-0">{{ __('locale.contacts') ?? 'Contacts' }}</h5>
                            <a href="{{ route('supplier-contacts.create', ['supplier_id' => $supplier->id]) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_add_contact') ?? 'Nouveau Contact' }}
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('locale.lbl_name') ?? 'Nom' }}</th>
                                        <th>{{ __('locale.lbl_email') ?? 'Email' }}</th>
                                        <th>{{ __('locale.lbl_phone') ?? 'Téléphone' }}</th>
                                        <th class="text-end">{{ __('locale.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($supplier->contacts as $contact)
                                        <tr>
                                            <td>
                                                <a href="{{ route('supplier-contacts.show', $contact) }}" class="fw-bold text-decoration-none">
                                                    {{ $contact->prenom }} {{ $contact->nom }}
                                                </a>
                                                @if($contact->langue)
                                                    <div class="mt-1">
                                                        <span class="badge bg-light text-success border px-1 py-0 small" style="font-size: 0.75rem;"><i class="bi bi-translate me-1"></i>{{ $contact->langue }}</span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $contact->email ?? '-' }}</td>
                                            <td>
                                                <div>{{ $contact->phone1 ?? '-' }}</div>
                                                @if($contact->phone2) <div class="small text-muted">{{ $contact->phone2 }}</div> @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="dropdown" onclick="event.stopPropagation();">
                                                    <button class="btn btn-sm btn-outline-secondary" type="button" id="supplierContactActionMenu{{ $contact->id }}" data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="viewport">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="supplierContactActionMenu{{ $contact->id }}">
                                                        <li>
                                                            <a href="{{ route('supplier-contacts.show', $contact) }}" class="dropdown-item">
                                                                <i class="bi bi-eye me-2"></i>{{ __('locale.btn_show') ?? 'Voir' }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('supplier-contacts.edit', $contact) }}" class="dropdown-item">
                                                                <i class="bi bi-pencil-square me-2"></i>{{ __('locale.edit') ?? 'Modifier' }}
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">
                                                {{ __('locale.lbl_no_records_found') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
