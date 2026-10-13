@extends('layouts.app') 

@section('navbar-search')
    <form method="GET" action="{{ route('supplier-contacts.index') }}" class="w-100">
        <div class="search-bar-mockup">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('locale.search_placeholder') ?? 'Rechercher...' }}">
            @foreach(request()->except(['search', 'page']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </div>
    </form>
@endsection

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.supplier_contacts') ?? 'Contacts Fournisseurs' }}</h1>
        <span class="header-count">({{ $contacts->total() }})</span>
    </div>
@endsection

@section('page-actions')
    <a href="{{ route('supplier-contacts.create') }}" class="btn btn-primary" title="{{ __('locale.btn_create_supplier_contact') }}">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_create_supplier_contact') }}
    </a>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="table-toolbar">
            <div class="toolbar-left">
                <span class="text-muted small">
                    Résultats {{ $contacts->firstItem() ?? 0 }}-{{ $contacts->lastItem() ?? 0 }} sur {{ $contacts->total() ?? 0 }}
                </span>
            </div>
            <div class="toolbar-right d-flex gap-3">
                @if (Auth::user()->isNovalyoAdmin())
                <a href="#importSection" data-bs-toggle="collapse" role="button"
                   class="btn btn-link text-decoration-none text-success fw-bold p-0"
                   aria-expanded="false" aria-controls="importSection">
                    <i class="bi bi-cloud-arrow-up me-1"></i> Importer
                </a>
                @endif
                <a href="#advancedFilters" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="advancedFilters" class="btn btn-outline-secondary btn-sm border-0"> 
                    <i class="bi bi-filter me-1"></i> {{ __('locale.advanced_filters') ?? 'Filtres avancés' }} 
                </a>
            </div>
        </div>

        @if (Auth::user()->isNovalyoAdmin())
        {{-- Import section --}}
        <div class="collapse" id="importSection">
            <form id="contactImportForm" class="import-form mt-3" method="POST"
                  action="{{ route('supplier-contacts.import') }}" enctype="multipart/form-data">
                @csrf
                <h5 class="fw-bold mb-3 text-success">
                    <i class="bi bi-cloud-arrow-up-fill me-2"></i>Import contacts fournisseurs (CSV)
                </h5>
                <div class="row g-3">
                    <div class="col-md-5">
                        <label for="contact_file" class="form-label fw-bold small text-uppercase text-muted">
                            Fichier CSV contacts
                        </label>
                        <input type="file" name="file" id="contact_file" class="form-control" accept=".csv,.txt,.xlsx,.xls" required>
                        <div class="form-text text-muted">
                            Formats acceptés : CSV, XLSX &nbsp;·&nbsp; En-têtes attendus : <code>Nom | Prénom | Nat. | Société | Raison sociale | Adresse 1 | Adresse 2 | Adresse 3 | Code postal | Ville | Adresse messagerie | Téléphone N° 1 | Téléphone N° 2 | Téléphone n°3</code>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="import_company_id" class="form-label fw-bold small text-uppercase text-muted">
                            Société
                        </label>
                        <select name="company_id" id="import_company_id" class="form-select" required>
                            <option value="">-- Sélectionner une société --</option>
                            @foreach(\App\Models\Company::orderBy('name')->get() as $company)
                                <option value="{{ $company->id }}">{{ $company->name }} ({{ $company->code }})</option>
                            @endforeach
                        </select>
                        <div class="form-text text-muted">
                            Les contacts seront rattachés aux fournisseurs de cette société.
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold w-100">
                            <i class="bi bi-upload me-1"></i>Importer
                        </button>
                    </div>
                </div>
            </form>
            <div id="contactImportMessage" class="alert d-none mt-3" role="alert" aria-live="polite" aria-atomic="true"></div>
        </div>
        @endif

        @if ($message = Session::get('success'))
            <div class="alert alert-success mt-3 mx-3" role="alert"> {{ $message }} </div>
        @endif

        <div class="collapse" id="advancedFilters">
            <div class="p-4 bg-white border-bottom">
                <form method="GET" action="{{ route('supplier-contacts.index') }}" class="row g-3">
                    <div class="col-md-6">
                        <label for="supplier_id" class="form-label">{{ __('locale.lbl_supplier') ?? 'Fournisseur' }}</label>
                        <select name="supplier_id" id="supplier_id" class="form-select select2" data-placeholder="Filtrer par fournisseur">
                            <option value=""></option>
                            @foreach(\App\Models\Supplier::orderBy('name')->get() as $sup)
                                <option value="{{ $sup->id }}" @selected(request('supplier_id') == $sup->id)>
                                    {{ $sup->name }} @if($sup->cegid_id) ({{ $sup->cegid_id }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <input type="hidden" name="search" value="{{ request('search') }}">

                    <div class="col-md-12 text-end mt-3">
                        <a href="{{ route('supplier-contacts.index') }}" class="btn btn-outline-secondary me-2">{{ __('locale.btn_reset') ?? 'Réinitialiser' }}</a>
                        <button type="submit" class="btn btn-primary px-4">{{ __('locale.btn_apply_filters') ?? 'Filtrer' }}</button>
                    </div>
                </form>
            </div>
        </div>

        @php
            $currentSort = request('sort', 'id');
            $currentDirection = request('direction', 'asc');
            $sortOptions = [
                'id' => __('locale.lbl_id') ?? 'ID',
                'nom' => __('locale.name') ?? 'Nom',
                'supplier' => __('locale.lbl_supplier') ?? 'Fournisseur',
            ];
        @endphp

        <div class="d-flex flex-wrap justify-content-end align-items-center gap-2 p-3">
            <span class="text-muted small">{{ __('locale.lbl_sort_by') ?? 'Trier par' }}:</span>
            <div class="btn-group" role="group">
                @foreach ($sortOptions as $column => $label)
                    @php
                        $direction = $currentSort === $column && $currentDirection === 'asc' ? 'desc' : 'asc';
                        $isActive = $currentSort === $column;
                        $icon = $isActive ? ($currentDirection === 'asc' ? 'up' : 'down') : null;
                    @endphp
                    <a href="{{ route('supplier-contacts.index', array_merge(request()->query(), ['sort' => $column, 'direction' => $direction])) }}"
                        class="btn btn-sm btn-outline-secondary {{ $isActive ? 'active' : '' }}">
                        <span>{{ $label }}</span>
                        @if ($icon)
                            <i class="bi bi-caret-{{ $icon }}-fill ms-1"></i>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>

        <style>
            .contact-card {
                transition: transform 0.2s ease, box-shadow 0.2s ease;
                cursor: pointer;
            }
            .contact-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
                z-index: 10;
            }
            .contact-avatar {
                width: 48px;
                height: 48px;
                background-color: rgba(var(--md-sys-color-secondary-rgb), 0.1);
                color: var(--md-sys-color-secondary);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.25rem;
                font-weight: 600;
                border-radius: 12px;
            }
        </style>

        <div class="px-3 pb-4">
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                @forelse ($contacts as $contact)
                    @php
                        $url = route('supplier-contacts.show', $contact->id);
                        $supplier = $contact->supplier;
                        $initials = strtoupper(substr($contact->prenom ?? '', 0, 1) . substr($contact->nom ?? '', 0, 1));
                        if(empty($initials)) $initials = '?';
                    @endphp
                    <div class="col">
                        <div class="card contact-card h-100" onclick='window.location="{{ $url }}"' style="border-top: 3px solid #28a745;">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="contact-avatar">
                                        {{ $initials }}
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h5 class="card-title mb-0 text-truncate">{{ $contact->nom }} {{ $contact->prenom }}</h5>
                                        <div class="d-flex align-items-center gap-1 flex-wrap text-muted small text-truncate mt-1">
                                            @if($contact->langue)
                                                <span class="badge bg-light text-success border px-1 py-0 small" style="font-size: 0.75rem;"><i class="bi bi-translate me-1"></i>{{ $contact->langue }}</span>
                                            @endif
                                            <span><i class="bi bi-geo-alt me-1"></i>{{ $contact->ville ?: 'Ville non renseignée' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="text-muted small fw-semibold mb-1">{{ __('locale.lbl_supplier') ?? 'Fournisseur' }}</div>
                                    <div class="d-flex align-items-center text-success fw-medium">
                                        <i class="bi bi-truck me-2"></i>
                                        @if ($supplier)
                                            <span class="text-truncate">{{ $supplier->name }}</span>
                                        @else
                                            <span class="text-muted small italic">Aucun fournisseur</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="d-flex flex-column gap-2 small text-muted">
                                    @if($contact->email)
                                        <div class="d-flex align-items-center text-truncate">
                                            <i class="bi bi-envelope me-2 text-success"></i>
                                            <span class="text-truncate">{{ $contact->email }}</span>
                                        </div>
                                    @endif
                                    @if($contact->phone1)
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-telephone me-2 text-success"></i>
                                            {{ $contact->phone1 }}
                                        </div>
                                    @endif
                                    @if($contact->phone2)
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-phone me-2 text-success"></i>
                                            {{ $contact->phone2 }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="text-muted">
                            <i class="bi bi-person-lines-fill fs-1 mb-2"></i>
                            <p>Aucun contact fournisseur trouvé.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $contacts->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const importForm = document.getElementById('contactImportForm');
        const messageContainer = document.getElementById('contactImportMessage');

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
                if (error && typeof error === 'object') {
                    if (error.errors && typeof error.errors === 'object') {
                        const firstKey = Object.keys(error.errors)[0];
                        if (firstKey && Array.isArray(error.errors[firstKey]) && error.errors[firstKey][0]) {
                            msg = error.errors[firstKey][0];
                        }
                    } else if (error.message) {
                        msg = error.message;
                    }
                }
                showMessage('error', msg);
            } finally {
                if (submitButton) submitButton.disabled = false;
            }
        });
    });
</script>
@endsection
