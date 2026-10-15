@extends('layouts.app')
@section('portal-module', 'contacts')
@section('portal-page', 'index')
@section('portal-kind', 'list')
@section('portal-family', 'relations')
@section('portal-icon', 'users')


@section('navbar-search')
    <form method="GET" action="{{ route('contacts.index') }}" class="w-100">
        <div class="search-bar-mockup">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('locale.search_placeholder') }}">
            @foreach(request()->except(['search', 'page']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </div>
    </form>
@endsection

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.contacts') }}</h1>
        {{-- <form id="refreshSiteForm" method="POST" action="/import-csv-from-ftp" class="m-0"> 
            @csrf 
            <input type="hidden" value="contact" name="file_type" />
            <button type="submit" class="btn btn-outline-primary btn-sm border-0 bg-transparent" title="Refresh Contacts">
                <i class="bi bi-arrow-clockwise fs-4"></i>
            </button>
        </form> --}}
        <span class="header-count">({{ $contacts->total() }})</span>
    </div>
@endsection

@section('page-actions')
    <a href="{{ route('contacts.create') }}" class="btn btn-primary" title="{{ __('locale.btn_create_contact') }}">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_create_contact') ?? 'Nouveau contact' }}
    </a>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="table-toolbar portal-original-toolbar">
            <div class="toolbar-left">
                <span class="text-muted small">
                    {{ __('locale.results_range', ['first' => $contacts->firstItem() ?? 0, 'last' => $contacts->lastItem() ?? 0, 'total' => $contacts->total()]) }}
                </span>
            </div>
            <div class="toolbar-right">
                <a href="#advancedFilters" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="advancedFilters" class="btn btn-outline-secondary btn-sm border-0"> 
                    <i class="bi bi-filter me-1"></i> {{ __('locale.advanced_filters') }} 
                </a>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success mt-3 mx-3" role="alert"> {{ $message }} </div>
        @endif

        <div class="collapse" id="advancedFilters">
            <div class="p-4 bg-white border-bottom">
                <form method="GET" action="{{ route('contacts.index') }}" class="row g-3">
                    <div class="col-md-6 position-relative">
                        <label for="client_search" class="form-label">{{ __('locale.lbl_client') }}</label>
                        <div class="d-flex align-items-center">
                            <input type="text" id="client_search" class="form-control" autocomplete="off" value="{{ $selectedClient->raison_sociale ?? '' }}" placeholder="{{ __('locale.search_client_placeholder') }}">
                            <i id="client_warning"
                                class="bi bi-exclamation-triangle-fill text-danger ms-2 {{ $selectedClient && ($selectedClient->concurrent || $selectedClient->default_payment) ? '' : 'd-none' }}"
                                data-bs-toggle="tooltip" data-bs-html="true" @if ($selectedClient) title="{!! implode('<br>', array_filter([$selectedClient->concurrent ? __('locale.lbl_concurrent') : null, $selectedClient->default_payment ? __('locale.default_payment') : null])) !!}" @endif></i>
                        </div>
                        <div id="client_address" class="small text-muted mt-1">
                            @if ($selectedClient)
                                {{ trim($selectedClient->adresse_1 . ' ' . $selectedClient->adresse_2) }}@if ($selectedClient->code_postal || $selectedClient->ville)
                                    - {{ trim($selectedClient->code_postal . ' ' . $selectedClient->ville) }}
                                @endif
                            @endif
                        </div>
                        <input type="hidden" name="client_id" id="client_id" value="{{ request('client_id') }}">
                        <ul id="client_suggestions" class="list-group position-absolute w-100 bg-white shadow-sm autocomplete-suggestions" style="z-index: 1000; display:none;"></ul>
                    </div>
                    
                    <input type="hidden" name="search" value="{{ request('search') }}">

                    <div class="col-md-12 text-end mt-3">
                        <a href="{{ route('contacts.index') }}" class="btn btn-outline-secondary me-2">{{ __('locale.btn_reset') }}</a>
                        <button type="submit" class="btn btn-primary px-4">{{ __('locale.btn_apply_filters') }}</button>
                    </div>
                </form>
            </div>
        </div>

        @php
            $currentSort = request('sort', 'id');
            $currentDirection = request('direction', 'asc');
            $sortOptions = [
                'id' => __('locale.lbl_id'),
                'nom' => __('locale.name'),
                'client' => __('locale.lbl_client'),
                'odoo_id' => __('locale.lbl_odoo_id') ?? 'Odoo ID',
            ];
        @endphp

        <div class="d-flex flex-wrap justify-content-end align-items-center gap-2 p-3">
            <span class="text-muted small">{{ __('locale.lbl_sort_by') }}:</span>
            <div class="btn-group" role="group">
                @foreach ($sortOptions as $column => $label)
                    @php
                        $direction = $currentSort === $column && $currentDirection === 'asc' ? 'desc' : 'asc';
                        $isActive = $currentSort === $column;
                        $icon = $isActive ? ($currentDirection === 'asc' ? 'up' : 'down') : null;
                    @endphp
                    <a href="{{ route('contacts.index', array_merge(request()->query(), ['sort' => $column, 'direction' => $direction])) }}"
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
                background-color: rgba(105, 108, 255, 0.1);
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
                        $url = route('contacts.show', $contact->id);
                        $client = $contact->client;
                        $initials = strtoupper(substr($contact->prenom ?? '', 0, 1) . substr($contact->nom ?? '', 0, 1));
                        if(empty($initials)) $initials = '?';
                        
                        $borderColor = '';
                        if ($client) {
                            if ($client->type === \App\Enums\ClientType::Site) {
                                $borderColor = '#b54784';
                            } elseif ($client->type === \App\Enums\ClientType::Groupe) {
                                $borderColor = '#0082c6';
                            }
                        }
                    @endphp
                    <div class="col">
                        <div class="card contact-card h-100" onclick='window.location="{{ $url }}"' style="@if ($borderColor) border-top: 3px solid {{ $borderColor }}; @endif">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="contact-avatar">
                                        {{ $initials }}
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="d-flex align-items-center gap-1 mb-1">
                                            <h5 class="card-title mb-0 text-truncate">{{ $contact->civility }} {{ $contact->nom }} {{ $contact->prenom }}</h5>
                                        </div>
                                        <div class="d-flex align-items-center gap-1 flex-wrap">
                                            <span class="badge bg-light text-secondary border px-2 py-0 small">{{ $contact->type_label }}</span>
                                            @if($contact->odoo_id)
                                                @if(config('services.odoo.url'))
                                                    <a href="{{ rtrim(config('services.odoo.url'), '/') }}/web#id={{ $contact->odoo_id }}&model=res.partner&view_type=form" target="_blank" rel="noopener" class="badge bg-secondary-subtle text-secondary border px-2 py-0 small text-decoration-none font-monospace" onclick="event.stopPropagation();" title="Ouvrir dans Odoo">
                                                        <i class="bi bi-box-arrow-up-right me-1" style="font-size: 9px;"></i>Odoo #{{ $contact->odoo_id }}
                                                    </a>
                                                @else
                                                    <span class="badge bg-light text-secondary border px-2 py-0 small font-monospace">Odoo #{{ $contact->odoo_id }}</span>
                                                @endif
                                            @endif
                                            @if($contact->langue)
                                                <span class="badge bg-light text-primary border px-2 py-0 small"><i class="bi bi-translate me-1"></i>{{ $contact->langue }}</span>
                                            @endif
                                            @if($contact->function_code)
                                                <span class="text-muted small text-truncate">
                                                    <i class="bi bi-briefcase me-1"></i>{{ $contact->function_code }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="text-muted small fw-semibold mb-1">{{ __('locale.lbl_client') }}</div>
                                    <div class="d-flex align-items-center text-primary fw-medium">
                                        <i class="bi bi-building me-2"></i>
                                        @if ($client)
                                            <span class="text-truncate">{{ $client->raison_sociale }}</span>
                                            @if ($client->default_payment || $client->concurrent)
                                                <i class="bi bi-exclamation-triangle-fill text-danger ms-2" data-bs-toggle="tooltip" 
                                                   title="{!! implode(' • ', array_filter([$client->concurrent ? __('locale.lbl_concurrent') : null, $client->default_payment ? __('locale.default_payment') : null])) !!}"></i>
                                            @endif
                                        @else
                                            <span class="text-muted small italic">{{ __('locale.no_client') }}</span>
                                        @endif
                                    </div>
                                    @if($contact->adresse_1 || $contact->ville)
                                        <div class="text-muted small mt-1">
                                            <i class="bi bi-geo-alt me-1"></i>{{ $contact->adresse_1 }}{{ $contact->ville ? ', ' . $contact->ville : '' }}
                                        </div>
                                    @endif
                                </div>

                                <div class="d-flex flex-column gap-2 small text-muted">
                                    @if($contact->email)
                                        <div class="d-flex align-items-center text-truncate">
                                            <i class="bi bi-envelope me-2 text-primary"></i>
                                            <span class="text-truncate">{{ $contact->email }}</span>
                                        </div>
                                    @endif
                                    @if($contact->phone1)
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-telephone me-2 text-primary"></i>
                                            {{ $contact->phone1 }}
                                        </div>
                                    @endif
                                    @if($contact->phone2)
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-phone me-2 text-primary"></i>
                                            {{ $contact->phone2 }}
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-4 pt-3 d-flex justify-content-end border-top">
                                    <div class="dropdown" onclick="event.stopPropagation();">
                                        <button class="btn btn-sm btn-outline-secondary" type="button" id="contactActionMenu{{ $contact->id }}" data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="viewport">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="contactActionMenu{{ $contact->id }}">
                                            <li>
                                                <a href="{{ route('contacts.edit', $contact->id) }}" class="dropdown-item py-2">
                                                    <i class="bi bi-pencil-square me-2 text-warning"></i>{{ __('locale.edit') }}
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('contacts.destroy', $contact->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item py-2 text-danger" onclick="return confirm('{{ __('locale.delete_confirmation') }}')">
                                                        <i class="bi bi-trash me-2"></i>{{ __('locale.delete') }}
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
                        <div class="text-center py-5">
                            <i class="bi bi-person-x fs-1 text-muted opacity-50 mb-3"></i>
                            <p class="text-muted mb-0">{{ __('locale.no_contacts_found') }}</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $contacts->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Floating Action Button --}}
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        function initClientAutocomplete() {
            const translations = {
                concurrent: "{{ __('locale.lbl_concurrent') }}",
                default_payment: "{{ __('locale.default_payment') }}",
                contract_status: {
                    avec_contrat: "{{ __('locale.contract_status.avec_contrat') }}",
                    sans_contrat: "{{ __('locale.contract_status.sans_contrat') }}",
                    contrat_resilie: "{{ __('locale.contract_status.contrat_resilie') }}",
                    contrat_expire: "{{ __('locale.contract_status.contrat_expire') }}",
                }
            };

            const contractStatusClasses = {
                avec_contrat: 'badge-matte-success',
                sans_contrat: 'badge-matte-secondary',
                contrat_resilie: 'badge-matte-danger',
                contrat_expire: 'badge-matte-warning',
            };

            const setupClientAutocomplete = (prefix) => {
                const searchInput = document.getElementById(`${prefix}_search`);
                const suggestions = document.getElementById(`${prefix}_suggestions`);
                const hiddenInput = document.getElementById(`${prefix}_id`);
                const addressEl = document.getElementById(`${prefix}_address`);
                const warningIcon = document.getElementById(`${prefix}_warning`);

                if (!searchInput) return;

                searchInput.addEventListener('input', function() {
                    const query = this.value;
                    if (!query) {
                        suggestions.style.display = 'none';
                        suggestions.innerHTML = '';
                        hiddenInput.value = '';
                        if(addressEl) addressEl.textContent = '';
                        if(warningIcon) warningIcon.classList.add('d-none');
                        return;
                    }

                    fetch(`{{ route('clients.search') }}?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            suggestions.innerHTML = '';
                            if (data.length === 0) {
                                suggestions.style.display = 'none';
                                return;
                            }
                            data.forEach(client => {
                                const li = document.createElement('li');
                                li.classList.add('list-group-item', 'list-group-item-action', 'cursor-pointer', 'py-2');
                                li.dataset.id = client.id;
                                li.dataset.address = [client.adresse_1, client.code_postal, client.ville].filter(Boolean).join(' ');
                                li.dataset.concurrent = client.concurrent ? '1' : '';
                                li.dataset.default_payment = client.default_payment ? '1' : '';

                                let nameHtml = `<div class="fw-bold">${client.raison_sociale}</div>`;
                                let badgesHtml = '<div class="mt-1">';
                                if (client.contract_status) {
                                    const badgeClass = contractStatusClasses[client.contract_status] || 'badge-matte-secondary';
                                    const badgeLabel = translations.contract_status[client.contract_status] || client.contract_status;
                                    badgesHtml += `<span class="badge ${badgeClass} me-1">${badgeLabel}</span>`;
                                }
                                if (client.concurrent || client.default_payment) {
                                    badgesHtml += `<i class="bi bi-exclamation-triangle-fill text-danger"></i>`;
                                }
                                badgesHtml += '</div>';
                                
                                li.innerHTML = nameHtml + badgesHtml + `<small class="text-muted">${client.ville || ''}</small>`;
                                suggestions.appendChild(li);
                            });
                            suggestions.style.display = 'block';
                        });
                });

                suggestions.addEventListener('click', function(e) {
                    const li = e.target.closest('li');
                    if (!li) return;
                    
                    hiddenInput.value = li.dataset.id;
                    searchInput.value = li.querySelector('.fw-bold').textContent.trim();
                    if(addressEl) addressEl.textContent = li.dataset.address;

                    if(warningIcon) {
                        const isWarning = li.dataset.concurrent || li.dataset.default_payment;
                        if (isWarning) {
                            warningIcon.classList.remove('d-none');
                        } else {
                            warningIcon.classList.add('d-none');
                        }
                    }

                    suggestions.style.display = 'none';
                    suggestions.innerHTML = '';
                });

                document.addEventListener('click', function(e) {
                    if (!searchInput.contains(e.target) && !suggestions.contains(e.target)) {
                        suggestions.style.display = 'none';
                    }
                });
            };

            setupClientAutocomplete('client');
        }

        initClientAutocomplete();
    });
</script>
@endsection
