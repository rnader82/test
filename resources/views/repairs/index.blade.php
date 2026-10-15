@extends('layouts.app')
@section('portal-module', 'repairs')
@section('portal-page', 'index')
@section('portal-kind', 'list')
@section('portal-family', 'technical')
@section('portal-icon', 'screwdriver-wrench')


@section('title', __('locale.menu_repairs'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.menu_repairs') }}</h1>
        <span class="header-count">({{ $repairs->total() }})</span>
    </div>
@endsection

@section('navbar-search')
    <form method="GET" action="{{ route('repairs.index') }}" class="w-100">
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
    <a href="{{ route('repairs.create') }}" class="btn btn-primary" title="{{ __('locale.btn_create_repair') ?? 'Nouveau' }}">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_create_repair') ?? 'Nouvelle réparation' }}
    </a>
@endsection

@section('content')
<style>
	.status-rectangle {
		display: inline-block;
		width: 20px;
		height: 7px;
		border-radius: 3px;
		margin-right: 6px;
		vertical-align: middle;
	}

	.status-box {
		display: inline-block;
		width: 12px;
		height: 12px;
		border-radius: 3px;
		margin-right: 6px;
		vertical-align: middle;
	}

	.status-item {
		display: inline-flex;
		align-items: center;
	}

	.client-link {
		text-decoration: none;
		color: inherit;
	}

	.client-link:hover {
		color: var(--bs-primary);
		text-decoration: underline;
	}
</style>
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">

        <div class="table-toolbar portal-original-toolbar">
            <div class="toolbar-left">
                @foreach ($statuses as $status)
                    <span class="status-item small" style="font-size: 0.75rem; margin-right: 10px;">
                        <span class="status-box" style="background-color: {{ $status->color }}; width: 8px; height: 8px;"></span>
                        <span class="text-muted">{{ $status->translated_name ?? $status->name }}</span>
                    </span>
                @endforeach
            </div>
            <div class="toolbar-right">
                <a href="#advancedFilters" data-bs-toggle="collapse" role="button" aria-expanded="{{ ($filtersActive ?? false) ? 'true' : 'false' }}" aria-controls="advancedFilters" class="btn btn-outline-secondary btn-sm border-0">
                    <i class="bi bi-filter me-1"></i> {{ __('locale.advanced_filters') }}
                </a>
            </div>
        </div>


    <div class="collapse {{ ($filtersActive ?? false) ? 'show' : '' }}" id="advancedFilters">
        <form method="GET" action="{{ route('repairs.index') }}" class="mb-4">
            <input type="hidden" name="search" value="{{ request('search') }}">
            <input type="hidden" name="sort" value="{{ request('sort') }}">
            <input type="hidden" name="direction" value="{{ request('direction') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="status_id" class="form-label small fw-semibold">{{ __('locale.lbl_status') }}</label>
                    <select name="status_id" id="status_id" class="form-select form-select-sm">
                        <option value="">{{ __('locale.all_statuses') }}</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}" {{ (string) ($selectedStatusId ?? '') === (string) $status->id ? 'selected' : '' }}>
                                {{ $status->translated_name ?? $status->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="warranty_type" class="form-label small fw-semibold">{{ __('locale.lbl_warranty') }}</label>
                    <select name="warranty_type" id="warranty_type" class="form-select form-select-sm">
                        <option value="">Tous les types</option>
                        <option value="under_warranty" {{ request('warranty_type') === 'under_warranty' ? 'selected' : '' }}>{{ __('locale.sav_under_warranty') }}</option>
                        <option value="out_of_warranty" {{ request('warranty_type') === 'out_of_warranty' ? 'selected' : '' }}>{{ __('locale.sav_out_of_warranty') }}</option>
                        <option value="amen_contract" {{ request('warranty_type') === 'amen_contract' ? 'selected' : '' }}>{{ __('locale.sav_amen_contract') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="repair_status" class="form-label small fw-semibold">{{ __('locale.lbl_repair_status') }}</label>
                    <select name="repair_status" id="repair_status" class="form-select form-select-sm">
                        <option value="">Tous les résultats</option>
                        <option value="pending" {{ request('repair_status') === 'pending' ? 'selected' : '' }}>{{ __('locale.sav_pending') }}</option>
                        <option value="repaired" {{ request('repair_status') === 'repaired' ? 'selected' : '' }}>{{ __('locale.sav_repaired') }}</option>
                        <option value="unrepairable" {{ request('repair_status') === 'unrepairable' ? 'selected' : '' }}>{{ __('locale.sav_unrepairable') }}</option>
                        <option value="replaced_new" {{ request('repair_status') === 'replaced_new' ? 'selected' : '' }}>{{ __('locale.sav_replaced_new') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="supplier_id" class="form-label small fw-semibold">{{ __('locale.lbl_supplier') }}</label>
                    <select name="supplier_id" id="supplier_id" class="form-select form-select-sm select2">
                        <option value="">Tous les fournisseurs</option>
                        @foreach($suppliers ?? [] as $supplier)
                            <option value="{{ $supplier->id }}" {{ (string) request('supplier_id') === (string) $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="client_search" class="form-label small fw-semibold">{{ __('locale.lbl_client') }}</label>
                    <div class="position-relative" data-autocomplete-container>
                        <input
                            type="text"
                            name="client_search"
                            id="client_search"
                            class="form-control form-control-sm"
                            placeholder="{{ __('locale.search_client_placeholder') }}"
                            value="{{ $clientSearchValue ?? '' }}"
                            autocomplete="off"
                            data-autocomplete-input
                            data-autocomplete-url="{{ route('clients.search') }}"
                            data-autocomplete-hidden-input="#client_id"
                            data-autocomplete-label-key="raison_sociale"
                            data-autocomplete-value-key="id"
                            data-autocomplete-max-results="10"
                        >
                        <input type="hidden" name="client_id" id="client_id" value="{{ request('client_id') }}">
                        <div
                            class="list-group position-absolute w-100 shadow-sm bg-white"
                            data-autocomplete-results
                            style="max-height: 240px; overflow-y: auto; z-index: 1050;"
                            hidden
                        ></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="issue_search" class="form-label small fw-semibold">{{ __('locale.lbl_issue') }}</label>
                    <div class="position-relative" data-autocomplete-container>
                        <input
                            type="text"
                            name="issue_search"
                            id="issue_search"
                            class="form-control form-control-sm"
                            placeholder="{{ __('locale.search_issue_placeholder') }}"
                            value="{{ $issueSearchValue ?? '' }}"
                            autocomplete="off"
                            data-autocomplete-input
                            data-autocomplete-url="{{ route('issues.search') }}"
                            data-autocomplete-hidden-input="#issue_id"
                            data-autocomplete-label-key="subject"
                            data-autocomplete-value-key="id"
                            data-autocomplete-show-id="true"
                            data-autocomplete-max-results="10"
                        >
                        <input type="hidden" name="issue_id" id="issue_id" value="{{ request('issue_id') }}">
                        <div
                            class="list-group position-absolute w-100 shadow-sm bg-white"
                            data-autocomplete-results
                            style="max-height: 240px; overflow-y: auto; z-index: 1050;"
                            hidden
                        ></div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12 d-flex justify-content-end gap-2">
                    <a href="{{ route('repairs.index') }}" class="btn btn-outline-secondary btn-sm">{{ __('locale.btn_reset') }}</a>
                    <button type="submit" class="btn btn-primary btn-sm">{{ __('locale.btn_apply_filters') }}</button>
                </div>
            </div>
        </form>
    </div>

    @if ($repairs->count())
        <div class="mb-2" style="float: right;">
            <div class="d-flex align-items-center">
                <span class="me-2 text-muted small">{{ __('locale.results_range', ['first' => $repairs->firstItem(), 'last' => $repairs->lastItem(), 'total' => $repairs->total()]) }}</span>
            </div>
        </div>


        <table class="module-table table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 30px;"></th>
                    <th style="width: 90px;">
                        <a href="{{ route('repairs.index', array_merge(request()->query(), ['sort' => 'id', 'direction' => request('sort') === 'id' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                            Dossier #
                            @if(request('sort') === 'id')
                                <i class="bi bi-caret-{{ request('direction') === 'asc' ? 'up' : 'down' }}-fill"></i>
                            @endif
                        </a>
                    </th>
                    <th style="width: 180px;">
                        <a href="{{ route('repairs.index', array_merge(request()->query(), ['sort' => 'issue', 'direction' => request('sort') === 'issue' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                            Ticket
                            @if(request('sort') === 'issue')
                                <i class="bi bi-caret-{{ request('direction') === 'asc' ? 'up' : 'down' }}-fill"></i>
                            @endif
                        </a>
                    </th>
                    <th style="width: 220px;">
                        <a href="{{ route('repairs.index', array_merge(request()->query(), ['sort' => 'client', 'direction' => request('sort') === 'client' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                            {{ __('locale.lbl_client') }}
                            @if(request('sort') === 'client')
                                <i class="bi bi-caret-{{ request('direction') === 'asc' ? 'up' : 'down' }}-fill"></i>
                            @endif
                        </a>
                    </th>
                    <th>Matériels / Équipements SAV & Étapes</th>
                    <th style="width: 140px;">
                        <a href="{{ route('repairs.index', array_merge(request()->query(), ['sort' => 'status', 'direction' => request('sort') === 'status' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                            {{ __('locale.lbl_status') }}
                            @if(request('sort') === 'status')
                                <i class="bi bi-caret-{{ request('direction') === 'asc' ? 'up' : 'down' }}-fill"></i>
                            @endif
                        </a>
                    </th>
                    <th class="text-center" style="width: 80px;">{{ __('locale.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($repairs as $repair)
                    @php
                        $status = $repair->status;
                        $statusColor = $status?->color ?? '#6c757d';
                        $statusTextColor = $status?->text_color ?? '#ffffff';
                        $statusLabel = $status?->translated_name ?? $status?->name ?? __('locale.lbl_no_status');
                        $client = $repair->effective_client;
                        $clientCity = $client?->ville;
                        $defaultPayment = $client->default_payment ?? false;
                        $concurrent = $client->concurrent ?? false;
                        $clientWarningParts = array_filter([
                            $concurrent ? __('locale.lbl_concurrent') : null,
                            $defaultPayment ? __('locale.default_payment') : null,
                        ]);
                        $clientWarningTitle = implode('<br>', $clientWarningParts);
                        $contractStatusValue = $client?->contract_status?->value;
                        $contractStatusText = $contractStatusValue ? __('locale.contract_status.' . $contractStatusValue) : '';
                        $contractStatusClass = match ($contractStatusValue) {
                            'avec_contrat' => 'badge-solid-success',
                            'sans_contrat' => 'badge-solid-secondary',
                            'contrat_expire' => 'badge-solid-warning',
                            'contrat_resilie' => 'badge-solid-danger',
                            default => 'badge-solid-secondary',
                        };
                    @endphp
                    <tr class="clickable-row" data-href="{{ route('repairs.show', $repair->id) }}">
                        <td class="text-center">
                            <span class="status-rectangle" style="background-color: {{ $statusColor }};"></span>
                        </td>
                        <td>
                            <div class="fw-bold font-monospace">#{{ $repair->id }}</div>
                            <div class="text-muted small" style="font-size: 0.75rem;">{{ $repair->created_at->format('d/m/Y') }}</div>
                        </td>
                        <td>
                            @if($repair->issue)
                                <div class="fw-semibold text-truncate" style="max-width: 170px;" title="{{ $repair->issue->subject }}">{{ $repair->issue->subject }}</div>
                                <div class="text-muted small">
                                    <a href="{{ route('issues.show', $repair->issue_id) }}" onclick="event.stopPropagation();">Ticket #{{ $repair->issue_id }}</a>
                                </div>
                            @elseif($repair->external_ticket_number)
                                <span class="badge bg-light text-dark border"><i class="bi bi-tag me-1"></i>{{ $repair->external_ticket_number }}</span>
                                <div class="text-muted small" style="font-size: 0.75rem;">Ticket externe</div>
                            @else
                                <span class="text-muted small">Sans ticket</span>
                            @endif
                        </td>
                        <td>
                            @if($client)
                                @if($contractStatusText)
                                    <span class="badge {{ $contractStatusClass }} me-1" onclick="event.stopPropagation();">{{ $contractStatusText }}</span>
                                @endif
                                <a href="{{ route('clients.show', $client->id) }}" onclick="event.stopPropagation();" class="fw-semibold">
                                    {{ $client->raison_sociale }}@if(!empty($clientCity)) - {{ $clientCity }}@endif
                                </a>
                                @if($defaultPayment || $concurrent)
                                    <i class="bi bi-exclamation-triangle-fill text-danger ms-1" data-bs-toggle="tooltip" data-bs-html="true" title="{!! $clientWarningTitle !!}" onclick="event.stopPropagation();"></i>
                                @endif
                            @else
                                <span class="text-muted">{{ __('locale.lbl_no_client') }}</span>
                            @endif
                        </td>
                        <td>
                            @forelse($repair->equipmentRepairs as $eqRep)
                                <div class="d-flex align-items-center flex-wrap gap-1 mb-1 pb-1 {{ !$loop->last ? 'border-bottom' : '' }}">
                                    <span class="fw-semibold small text-dark">{{ $eqRep->equipment?->name ?? 'Équipement' }}</span>
                                    @if($eqRep->serial_number)
                                        <span class="badge bg-light text-dark font-monospace border" style="font-size: 0.75rem;">S/N: {{ $eqRep->serial_number }}</span>
                                    @endif

                                    <!-- Warranty badge -->
                                    @if($eqRep->warranty_type === 'under_warranty')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.7rem;">Garantie</span>
                                    @elseif($eqRep->warranty_type === 'out_of_warranty')
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle" style="font-size: 0.7rem;">
                                            Devis @if($eqRep->quote_amount){{ format_number($eqRep->quote_amount, 2) }}€@endif
                                            @if($eqRep->quote_signed === true)<i class="bi bi-check2 ms-1 text-success"></i>@endif
                                        </span>
                                    @elseif($eqRep->warranty_type === 'amen_contract')
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.7rem;">Amen @if($eqRep->loan_serial_number)(Prêt: {{ $eqRep->loan_serial_number }})@endif</span>
                                    @endif

                                    <!-- Supplier & Repair status -->
                                    @if($eqRep->supplier_id || $eqRep->recipient_name)
                                        <span class="badge bg-secondary-subtle text-secondary border" style="font-size: 0.7rem;">
                                            <i class="bi bi-truck me-1"></i>{{ $eqRep->supplier?->name ?? $eqRep->recipient_name }}
                                        </span>
                                    @endif

                                    @if($eqRep->repair_status && $eqRep->repair_status !== 'pending')
                                        <span class="badge bg-info-subtle text-info-emphasis border" style="font-size: 0.7rem;">{{ $eqRep->repair_status_label }}</span>
                                    @endif

                                    @if($eqRep->customer_delivery_date)
                                        <span class="badge bg-success text-white" style="font-size: 0.7rem;"><i class="bi bi-check-lg me-1"></i>Livré client</span>
                                    @endif
                                </div>
                            @empty
                                <span class="text-muted small">Aucun équipement</span>
                            @endforelse
                        </td>
                        <td>
                            <span class="badge" style="background-color: {{ hexToRgba($statusColor, 0.15) }}; color: {{ getDynamicStatusColor($statusColor) }}; font-weight: 600;">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="actions-cell text-center">
                            <div class="dropdown" onclick="event.stopPropagation();">
                                <button class="btn btn-sm btn-outline-secondary" type="button" id="actionMenu{{ $repair->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="actionMenu{{ $repair->id }}">
                                    <li>
                                        <a href="{{ route('repairs.show', $repair->id) }}" class="dropdown-item">
                                            <i class="bi bi-eye me-2 text-primary"></i>{{ __('locale.view') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('repairs.edit', $repair->id) }}" class="dropdown-item">
                                            <i class="bi bi-pencil-square me-2 text-warning"></i>{{ __('locale.edit') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('repairs.print-preview', $repair->id) }}" class="dropdown-item" target="_blank">
                                            <i class="bi bi-printer me-2 text-secondary"></i>Formulaire d'impression
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('repairs.destroy', $repair->id) }}" method="POST" onsubmit="event.stopPropagation(); return confirm('{{ __('locale.delete_confirmation') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-trash me-2"></i>{{ __('locale.delete') }}
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $repairs->withQueryString()->links() }}
    @else
        <div class="alert alert-info">{{ __('locale.no_repairs_found') }}</div>
    @endif

        </div>
    </div>
</div>

@endsection

@section('scripts')
@include('repairs.autocomplete_js')
<script>
    function initSelect2(parentEl = '#advancedFilters') {
        const $parent = parentEl instanceof HTMLElement ? $(parentEl) : $(parentEl);

        $parent.find('.select2').each(function () {
            const $element = $(this);

            if ($element.data('select2')) {
                $element.select2('destroy');
            }

            $element.select2({
                dropdownParent: $element.closest('.modal').length ? $element.closest('.modal') : $parent,
                width: '100%'
            });
        });
    }

    let repairsPageInitialized = false;

    const initializeRepairsPage = () => {
        if (repairsPageInitialized) {
            return;
        }

        repairsPageInitialized = true;

        initSelect2('#advancedFilters');

        const runAutocompleteInitialization = () => {
            if (typeof window.initializeRepairAutocomplete === 'function') {
                window.initializeRepairAutocomplete();
            } else {
                document.dispatchEvent(new Event('repairs:refresh-autocomplete'));
            }
        };

        runAutocompleteInitialization();

        function handleWindowLoad() {
            runAutocompleteInitialization();
            window.removeEventListener('load', handleWindowLoad);
        }

        if (document.readyState !== 'complete') {
            window.addEventListener('load', handleWindowLoad);
        }

        function handleAutocompleteReady() {
            runAutocompleteInitialization();
            document.removeEventListener('repairs:autocomplete-ready', handleAutocompleteReady);
        }

        if (typeof window.initializeRepairAutocomplete !== 'function') {
            document.addEventListener('repairs:autocomplete-ready', handleAutocompleteReady);
        }

        const advancedFilters = document.getElementById('advancedFilters');
        if (advancedFilters) {
            advancedFilters.addEventListener('shown.bs.collapse', function () {
                initSelect2(advancedFilters);
                runAutocompleteInitialization();
            });
        }

        document.querySelectorAll('.clickable-row').forEach(row => {
            if (row.dataset.repairRowInitialized === 'true') {
                return;
            }

            row.addEventListener('click', function (event) {
                if (!event.target.closest('.actions-cell')) {
                    window.location = this.dataset.href;
                }
            });

            row.dataset.repairRowInitialized = 'true';
        });
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeRepairsPage);
    } else {
        initializeRepairsPage();
    }
</script>
@endsection