@extends('layouts.app')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.suppliers') ?? 'Fournisseurs' }}</h1>
        <span class="header-count">({{ $suppliers->total() ?? 0 }})</span>
    </div>
@endsection

@section('navbar-search')
    <form method="GET" action="{{ route('suppliers.index') }}" class="w-100">
        <div class="search-bar-mockup">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('locale.search_placeholder') }}">
            @foreach(request()->except(['search', 'page']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </div>
    </form>
@endsection

@section('page-actions')
    <a href="{{ route('suppliers.create') }}" class="btn btn-primary" title="{{ __('locale.btn_add_supplier') ?? 'Ajouter un fournisseur' }}">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_add_supplier') ?? 'Nouveau fournisseur' }}
    </a>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger border-0 shadow-sm mb-4">{{ session('error') }}</div>
        @endif

        <div class="table-toolbar">
            <div class="toolbar-left">
                <span class="text-muted small">
                    Résultats {{ $suppliers->firstItem() ?? 0 }}-{{ $suppliers->lastItem() ?? 0 }} sur {{ $suppliers->total() ?? 0 }}
                </span>
            </div>
            <div class="toolbar-right d-flex align-items-center gap-3">
                @if (Auth::user()->isNovalyoAdmin())
                    <a href="{{ route('suppliers.export', request()->query()) }}" class="btn btn-outline-secondary btn-sm border-0">
                        <i class="bi bi-download me-1"></i> Exporter
                    </a>
                @endif
                @if (Auth::user()->isNovalyoAdmin())
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm border-0 dropdown-toggle" type="button"
                                id="syncAllOdooDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-arrow-repeat me-1"></i> Sync all to Odoo
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="syncAllOdooDropdown">
                            <li>
                                <form action="{{ route('suppliers.sync-all-odoo') }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Voulez-vous synchroniser les fournisseurs non synchronisés ?')">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-cloud-upload me-2 text-secondary"></i>
                                        Sync unsynced suppliers
                                    </button>
                                </form>
                            </li>
                            <li>
                                <form action="{{ route('suppliers.sync-all-odoo') }}?force=1" method="POST" class="d-inline"
                                      onsubmit="return confirm('Voulez-vous forcer la resynchronisation de tous les fournisseurs ?')">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-arrow-clockwise me-2 text-warning"></i>
                                        Force re-sync all suppliers
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endif
                @if (Auth::user()->isNovalyoAdmin())
                <a href="#importSection" data-bs-toggle="collapse" role="button"
                   class="btn btn-link text-decoration-none text-success fw-bold p-0 me-3"
                   aria-expanded="false" aria-controls="importSection">
                    <i class="bi bi-cloud-arrow-up me-1"></i> Importer
                </a>
                @endif
                <a href="#advancedFilters" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="advancedFilters" class="btn btn-outline-secondary btn-sm border-0">
                    <i class="bi bi-filter me-1"></i> {{ __('locale.advanced_filters') ?? 'Filtres avancés' }}
                </a>
            </div>
        </div>

        @php
            $hasActiveFilters = request()->filled('type') || request()->filled('closed') || request()->filled('ville') || request()->filled('nature') || request()->filled('condition_reglement_id') || request()->filled('regime_tva_id');
        @endphp

        <div class="collapse {{ $hasActiveFilters ? 'show' : '' }} mb-4" id="advancedFilters">
            <div class="card card-body border-0 shadow-sm mt-3">
                <form action="{{ route('suppliers.index') }}" method="GET" class="row g-3">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    <!-- Type Filter -->
                    <div class="col-md-4 col-sm-6">
                        <label for="filter_type" class="form-label fw-bold small text-uppercase text-muted">{{ __('locale.type') ?? 'Type' }}</label>
                        <select name="type" id="filter_type" class="form-select select2" data-placeholder="Tous les types">
                            <option value="">Tous les types</option>
                            <option value="fabricant" @selected(request('type') === 'fabricant')>{{ __('locale.fabricant') ?? 'Fabricant' }}</option>
                            <option value="redistributeur" @selected(request('type') === 'redistributeur')>{{ __('locale.redistributeur') ?? 'Redistributeur' }}</option>
                        </select>
                    </div>

                    <!-- Status Filter (Closed) -->
                    <div class="col-md-4 col-sm-6">
                        <label for="filter_closed" class="form-label fw-bold small text-uppercase text-muted">Statut</label>
                        <select name="closed" id="filter_closed" class="form-select select2">
                            <option value="">Tous les statuts</option>
                            <option value="0" @selected(request('closed') === '0')>Ouvert</option>
                            <option value="1" @selected(request('closed') === '1')>Fermé</option>
                        </select>
                    </div>

                    <!-- Ville Filter -->
                    <div class="col-md-4 col-sm-6">
                        <label for="filter_ville" class="form-label fw-bold small text-uppercase text-muted">Ville</label>
                        <select name="ville" id="filter_ville" class="form-select select2" data-placeholder="Toutes les villes">
                            <option value="">Toutes les villes</option>
                            @foreach($villes as $ville)
                                <option value="{{ $ville }}" @selected(request('ville') === $ville)>{{ $ville }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nature Filter -->
                    <div class="col-md-4 col-sm-6">
                        <label for="filter_nature" class="form-label fw-bold small text-uppercase text-muted">Nature</label>
                        <select name="nature" id="filter_nature" class="form-select select2" data-placeholder="Toutes les natures">
                            <option value="">Toutes les natures</option>
                            @foreach($natures as $nature)
                                <option value="{{ $nature }}" @selected(request('nature') === $nature)>{{ $nature }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Condition de règlement Filter -->
                    <div class="col-md-4 col-sm-6">
                        <label for="filter_condition_reglement" class="form-label fw-bold small text-uppercase text-muted">Cond. règlement</label>
                        <select name="condition_reglement_id" id="filter_condition_reglement" class="form-select select2" data-placeholder="Toutes les conditions">
                            <option value="">Toutes les conditions</option>
                            @foreach($conditionsReglement as $cr)
                                <option value="{{ $cr->id }}" @selected(request('condition_reglement_id') == $cr->id)>{{ $cr->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Régime TVA Filter -->
                    <div class="col-md-4 col-sm-6">
                        <label for="filter_regime_tva" class="form-label fw-bold small text-uppercase text-muted">Régime TVA</label>
                        <select name="regime_tva_id" id="filter_regime_tva" class="form-select select2" data-placeholder="Tous les régimes">
                            <option value="">Tous les régimes</option>
                            @foreach($regimesTva as $rt)
                                <option value="{{ $rt->id }}" @selected(request('regime_tva_id') == $rt->id)>{{ $rt->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Réinitialiser</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-filter me-1"></i>{{ __('locale.btn_apply_filters') ?? 'Filtrer' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if (Auth::user()->isNovalyoAdmin())
        {{-- Import section --}}
        <div class="collapse" id="importSection">
            <form id="supplierImportForm" class="import-form mt-3" method="POST"
                  action="{{ route('suppliers.import') }}" enctype="multipart/form-data">
                @csrf
                <h5 class="fw-bold mb-3 text-success">
                    <i class="bi bi-cloud-arrow-up-fill me-2"></i>Import fournisseurs (CSV CEGID)
                </h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="supplier_company_id" class="form-label fw-bold small text-uppercase text-muted">
                            Société
                        </label>
                        <select name="company_id" id="supplier_company_id" class="form-select select2" required
                                data-placeholder="Sélectionner une société">
                            <option value=""></option>
                            @foreach(\App\Models\Company::all() as $comp)
                                <option value="{{ $comp->id }}"
                                    @selected(session('active_company_prefix') === $comp->code)>
                                    {{ $comp->name }} ({{ $comp->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label for="supplier_file" class="form-label fw-bold small text-uppercase text-muted">
                            Fichier CSV fournisseurs
                        </label>
                        <input type="file" name="file" id="supplier_file" class="form-control" accept=".csv,.txt,.xlsx,.xls">
                        <div class="form-text text-muted">
                            Formats acceptés : CSV, XLSX &nbsp;·&nbsp; Colonnes attendues : <code>Nature | Code | Raison sociale | Code postal | Ville | SIREN / SIRET | Code client chez le fournisseur | Adresse 1 | Adresse 2 | Adresse 3 | Adresse messagerie | Téléphone n°1 | Téléphone n°2 | Conditions de règlement | Code NIF | Code NAF | Régime TVA</code>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold w-100">
                            <i class="bi bi-upload me-1"></i>Importer
                        </button>
                    </div>
                </div>
            </form>
            <div id="supplierImportMessage" class="alert d-none mt-3" role="alert" aria-live="polite" aria-atomic="true"></div>
        </div>
        @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <style>
        .supplier-card {
            position: relative;
            transition: transform 0.2s ease;
            cursor: pointer;
        }
        .supplier-card:hover {
            transform: translateY(-4px);
            z-index: 1000 !important;
            position: relative !important;
        }
    </style>

    <div class="mt-2">
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
            @forelse ($suppliers as $supplier)
                @php
                    $url = route('suppliers.show', $supplier);
                    $typeClass = $supplier->type === 'fabricant' ? 'badge-matte-primary' : 'badge-matte-info';
                    $addressParts = array_filter([
                        $supplier->address,
                        $supplier->adresse_2,
                        $supplier->adresse_3,
                        trim(implode(' ', array_filter([$supplier->code_postal, $supplier->ville]))),
                    ]);
                    $address = implode(', ', $addressParts);
                @endphp
                <div class="col">
                    <div class="card supplier-card h-100" onclick='window.location="{{ $url }}"' style="overflow: visible !important;">
                        @if ($supplier->closed)
                            <div class="ribbon-wrapper">
                                <div class="ribbon">{{ __('locale.lbl_closed') }}</div>
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column" style="overflow: visible !important;">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title mb-1 text-truncate" style="max-width: 220px;">
                                        {{ $supplier->name }}
                                    </h5>
                                    @if($supplier->cegid_id)
                                        <div class="text-muted small">
                                            CEGID: {{ $supplier->cegid_id }}
                                        </div>
                                    @endif
                                    @if($supplier->odoo_id)
                                        <div class="text-muted small">
                                            @if(config('services.odoo.url'))
                                                <a href="{{ rtrim(config('services.odoo.url'), '/') }}/web#id={{ $supplier->odoo_id }}&model=res.partner&view_type=form"
                                                   target="_blank"
                                                   rel="noopener"
                                                   class="text-decoration-none text-muted"
                                                   onclick="event.stopPropagation();"
                                                   title="Ouvrir dans Odoo">
                                                    Odoo: {{ $supplier->odoo_id }} <i class="bi bi-box-arrow-up-right ms-1" style="font-size: 0.75rem;"></i>
                                                </a>
                                            @else
                                                <span>Odoo: {{ $supplier->odoo_id }}</span>
                                            @endif
                                        </div>
                                    @endif
                                    <div class="mt-1">
                                        @if($supplier->company)
                                            <span class="badge bg-label-secondary" style="font-size: 0.72rem;">{{ $supplier->company->name }} ({{ $supplier->company->code }})</span>
                                        @else
                                            <span class="badge bg-label-info" style="font-size: 0.72rem;"><i class="bi bi-globe me-1"></i>Toutes les sociétés</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="badge {{ $typeClass }}">{{ $supplier->type === 'fabricant' ? (__('locale.fabricant') ?? 'Fabricant') : (__('locale.redistributeur') ?? 'Redistributeur') }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="text-muted small fw-semibold">{{ __('locale.address') ?? 'Adresse' }}</div>
                                <div class="text-truncate">{{ $address ?: (__('locale.not_available') ?? 'Non disponible') }}</div>
                            </div>

                            <div class="mb-3">
                                <div class="text-muted small fw-semibold">Contact</div>
                                <div class="small text-muted">
                                    @if($supplier->contact_email)
                                        <div class="text-truncate"><i class="bi bi-envelope me-1 text-primary"></i>{{ $supplier->contact_email }}</div>
                                    @endif
                                    @if($supplier->contact_phone)
                                        <div><i class="bi bi-telephone me-1 text-primary"></i>{{ $supplier->contact_phone }}</div>
                                    @endif
                                    @if(!$supplier->contact_email && !$supplier->contact_phone)
                                        <span>-</span>
                                    @endif
                                </div>
                            </div>

                            <div class="d-flex flex-wrap gap-3 small text-muted">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-people me-1 text-primary"></i>
                                    <span class="fw-semibold text-dark">{{ $supplier->contacts_count }}</span>
                                    <span class="ms-1">{{ __('locale.contacts') ?? 'Contacts' }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-text me-1 text-primary"></i>
                                    <span class="fw-semibold text-dark">{{ $supplier->supplier_orders_count }}</span>
                                    <span class="ms-1">Commandes</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-tools me-1 text-primary"></i>
                                    <span class="fw-semibold text-dark">{{ $supplier->equipments_count }}</span>
                                    <span class="ms-1">Équipements</span>
                                </div>
                            </div>

                            <div class="mt-auto pt-3 d-flex justify-content-end border-top">
                                <div class="dropdown" onclick="event.stopPropagation();">
                                    <button class="btn btn-sm btn-outline-secondary" type="button" id="supplierActionMenu{{ $supplier->id }}" data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="viewport">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="supplierActionMenu{{ $supplier->id }}">
                                        <li>
                                            <a href="{{ route('suppliers.show', $supplier->id) }}" class="dropdown-item">
                                                <i class="bi bi-eye me-2"></i>{{ __('locale.btn_show') ?? 'Voir' }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('suppliers.edit', $supplier->id) }}" class="dropdown-item">
                                                <i class="bi bi-pencil-square me-2"></i>{{ __('locale.edit') ?? 'Modifier' }}
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('{{ __('locale.confirm_delete') ?? 'Êtes-vous sûr de vouloir supprimer ce fournisseur ?' }}')">
                                                    <i class="bi bi-trash me-2"></i>{{ __('locale.btn_delete') ?? 'Supprimer' }}
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center mb-0 text-muted py-5">{{ __('locale.lbl_no_records_found') }}</p>
                </div>
            @endforelse
        </div>
    </div>
    <div class="mt-3">
        {{ $suppliers->withQueryString()->links() }}
    </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const importForm = document.getElementById('supplierImportForm');
        const messageContainer = document.getElementById('supplierImportMessage');

        if (!importForm) return;

        const submitButton = importForm.querySelector('button[type="submit"]');

        const resetMessage = () => {
            if (!messageContainer) return;
            messageContainer.classList.add('d-none');
            messageContainer.classList.remove('alert-success', 'alert-danger', 'alert-warning');
            messageContainer.innerHTML = '';
        };

        const showMessage = (type, html) => {
            if (!messageContainer) return;
            messageContainer.innerHTML = html;
            messageContainer.classList.remove('d-none');
            messageContainer.classList.remove('alert-success', 'alert-danger', 'alert-warning');
            messageContainer.classList.add(
                type === 'error' ? 'alert-danger' : (type === 'warning' ? 'alert-warning' : 'alert-success')
            );
        };

        importForm.addEventListener('submit', async function (event) {
            event.preventDefault();
            resetMessage();

            if (submitButton) submitButton.disabled = true;

            try {
                const formData = new FormData(importForm);
                const response = await fetch(importForm.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                let rawText = await response.text();
                rawText = typeof rawText === 'string' ? rawText.trim() : '';
                let payload = null;

                if (rawText !== '') {
                    try { payload = JSON.parse(rawText); } catch { payload = rawText; }
                }

                if (!response.ok) {
                    if (payload !== null) throw payload;
                    throw { message: "Une erreur est survenue lors de l'import." };
                }

                const messages = [];
                if (payload && typeof payload === 'object' && payload.summary) {
                    const s = payload.summary;
                    messages.push(`<strong>Import terminé</strong><br>`);
                    messages.push(`Créés : ${s.created ?? 0} &nbsp;|&nbsp; Mis à jour : ${s.updated ?? 0} &nbsp;|&nbsp; Ignorés : ${s.skipped ?? 0}.`);

                    if (Array.isArray(s.errors) && s.errors.length > 0) {
                        const errList = s.errors.slice(0, 20).map(err =>
                            `[Ligne ${err.row ?? '?'}] ${err.message}`
                        ).join('<br>');
                        messages.push(`<br><span class="text-danger"><strong>Erreurs (${s.errors.length}) :</strong><br>${errList}</span>`);
                    }
                } else if (payload && payload.message) {
                    messages.push(payload.message);
                } else {
                    messages.push('Import terminé.');
                }

                const hasErrors = payload?.summary?.errors?.length > 0;
                showMessage(hasErrors ? 'warning' : 'success', messages.join(' '));

                if (!hasErrors) {
                    setTimeout(() => window.location.reload(), 2500);
                }

            } catch (error) {
                let msg = "Une erreur est survenue lors de l'import.";
                if (typeof error === 'string') {
                    msg = error;
                } else if (error?.errors) {
                    msg = Object.values(error.errors).flat().join(' ');
                } else if (error?.message) {
                    msg = error.message;
                }
                showMessage('error', msg);
            } finally {
                if (submitButton) submitButton.disabled = false;
            }
        });

        // Init select2 if available
        if (typeof $ !== 'undefined' && typeof $.fn !== 'undefined' && typeof $.fn.select2 !== 'undefined') {
            $('#supplier_company_id').select2({ allowClear: true, width: '100%' });
            $('#filter_type').select2({ allowClear: true, placeholder: 'Tous les types', width: '100%' });
            $('#filter_closed').select2({ allowClear: true, placeholder: 'Tous les statuts', width: '100%' });
            $('#filter_ville').select2({ allowClear: true, placeholder: 'Toutes les villes', width: '100%' });
            $('#filter_nature').select2({ allowClear: true, placeholder: 'Toutes les natures', width: '100%' });
            $('#filter_condition_reglement').select2({ allowClear: true, placeholder: 'Toutes les conditions', width: '100%' });
            $('#filter_regime_tva').select2({ allowClear: true, placeholder: 'Tous les régimes', width: '100%' });
        }
    });
</script>
@endsection
