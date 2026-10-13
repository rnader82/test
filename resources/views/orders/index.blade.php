@extends('layouts.app')

@section('title', __('locale.orders.title'))

@section('navbar-icon')
    <i class="fa-solid fa-cart-shopping title-icon me-2"></i>
@endsection

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.orders.title') }}</h1>
    </div>
@endsection

@section('navbar-subtitle', 'Gérez et suivez l\'ensemble de vos commandes et devis')

@section('navbar-search')
    <form method="GET" action="{{ route('orders.index') }}" class="w-100" id="searchForm">
        <label class="search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input id="searchInput" type="search" name="search" value="{{ request('search') }}" placeholder="{{ __('locale.orders.index.search_placeholder') }}" />
        </label>
        @foreach(request()->except(['search', 'page']) as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
    </form>
@endsection

@section('page-actions')
    @php
        $hasActiveFilters = request()->filled('statut')
            || request()->filled('reference_prefix')
            || request()->filled('item_type');
    @endphp

    <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#advancedFilters" aria-expanded="{{ $hasActiveFilters ? 'true' : 'false' }}">
        <i class="fa-solid fa-filter"></i> {{ __('locale.advanced_filters') }}
        @if($hasActiveFilters)
            <span class="badge bg-primary rounded-circle p-1 ms-1" style="width: 7px; height: 7px; display: inline-block;"></span>
        @endif
    </button>

    @if (Auth::user()->isNovalyoAdmin() && request('password') === env('ORDERS_ADMIN_PASSWORD', 'NM9hRXf7BvVwTH77Zx9d4ku74ELy4PaGia'))
        <div class="dropdown">
            <button class="btn dropdown-toggle" type="button" id="adminActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('locale.title_sync_all_orders') }}">
                <i class="fa-solid fa-bolt"></i> Actions &amp; Odoo
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="adminActionsDropdown">
                <li>
                    <form action="{{ route('orders.sync-all-odoo') }}" method="POST" class="d-inline"
                          onsubmit="return confirm('{{ __('locale.confirm_sync_unsynced_orders') }}')">
                        @csrf
                        @if(request('password'))
                            <input type="hidden" name="password" value="{{ request('password') }}">
                        @endif
                        <button type="submit" class="dropdown-item">
                            <i class="fa-solid fa-cloud-arrow-up me-2 text-secondary"></i>
                            {{ __('locale.btn_sync_unsynced_orders') }}
                        </button>
                    </form>
                </li>
                <li>
                    <form action="{{ route('orders.sync-all-odoo') }}?force=1" method="POST" class="d-inline"
                          onsubmit="return confirm('{{ __('locale.confirm_force_resync_orders') }}')">
                        @csrf
                        @if(request('password'))
                            <input type="hidden" name="password" value="{{ request('password') }}">
                        @endif
                        <button type="submit" class="dropdown-item">
                            <i class="fa-solid fa-rotate me-2 text-warning"></i>
                            {{ __('locale.btn_force_resync_all_orders') }}
                        </button>
                    </form>
                </li>
                <li>
                    <form action="{{ route('orders.sync-all-odoo') }}?sync_delivered=1" method="POST" class="d-inline"
                          onsubmit="return confirm('{{ __('locale.confirm_sync_delivered_quantities') }}')">
                        @csrf
                        @if(request('password'))
                            <input type="hidden" name="password" value="{{ request('password') }}">
                        @endif
                        <button type="submit" class="dropdown-item">
                            <i class="fa-solid fa-truck me-2 text-success"></i>
                            {{ __('locale.btn_sync_delivered_quantities') }}
                        </button>
                    </form>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('orders.pull-statuses-odoo') }}" method="POST" class="d-inline"
                          onsubmit="return confirm('{{ __('locale.confirm_pull_order_statuses') }}')">
                        @csrf
                        @if(request('password'))
                            <input type="hidden" name="password" value="{{ request('password') }}">
                        @endif
                        <button type="submit" class="dropdown-item">
                            <i class="fa-solid fa-cloud-arrow-down me-2 text-primary"></i>
                            {{ __('locale.btn_pull_order_statuses') }}
                        </button>
                    </form>
                </li>
                <li>
                    <form action="{{ route('orders.sync-all-supplier-orders') }}" method="POST" class="d-inline"
                          onsubmit="return confirm('{{ __('locale.confirm_sync_supplier_orders') }}')">
                        @csrf
                        @if(request('password'))
                            <input type="hidden" name="password" value="{{ request('password') }}">
                        @endif
                        <button type="submit" class="dropdown-item">
                            <i class="fa-solid fa-boxes-stacked me-2 text-info"></i>
                            {{ __('locale.btn_sync_supplier_orders') }}
                        </button>
                    </form>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('orders.confirm-all-odoo') }}" method="POST" class="d-inline"
                          onsubmit="return confirm('{{ __('locale.confirm_move_all_sale') }}')">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fa-solid fa-circle-check me-2 text-primary"></i>
                            {{ __('locale.btn_confirm_all_sale') }}
                        </button>
                    </form>
                </li>
                <li>
                    <form action="{{ route('orders.deliver-all-bulk') }}" method="POST" class="d-inline"
                           onsubmit="return confirm('{{ __('locale.confirm_deliver_all_bulk') }}')">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fa-solid fa-truck-ramp-box me-2 text-success"></i>
                            {{ __('locale.btn_deliver_all_bulk') }}
                        </button>
                    </form>
                </li>
                <li>
                    <form action="{{ route('orders.revert-draft-all') }}" method="POST" class="d-inline"
                           onsubmit="return confirm('{{ __('locale.confirm_revert_draft_all') }}')">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fa-solid fa-rotate-left me-2 text-warning"></i>
                            {{ __('locale.btn_revert_draft_all') }}
                        </button>
                    </form>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <button type="button" class="dropdown-item text-success" data-bs-toggle="collapse" data-bs-target="#importSection">
                        <i class="fa-solid fa-file-arrow-up me-2"></i> {{ __('locale.import.btn_import') }} (CSV / XLSX)
                    </button>
                </li>
                @if (Auth::user()->is_admin || Auth::user()->hasPermission('import-orders-ftp') || Auth::user()->hasPermission('order-access'))
                <li>
                    <form action="{{ route('orders.import-ftp') }}" method="POST" class="d-inline"
                          onsubmit="return confirm('{{ __('locale.import.confirm_import_commandes_ftp') }}')">
                        @csrf
                        <button type="submit" class="dropdown-item text-info">
                            <i class="fa-solid fa-download me-2"></i> {{ __('locale.import.btn_import_commandes_ftp') }}
                        </button>
                    </form>
                </li>
                @endif
            </ul>
        </div>
    @endif

    <div class="create-menu" id="createMenu">
        @if (Auth::user()->is_admin || Auth::user()->hasPermission('create-orders') || Auth::user()->hasPermission('order-access'))
            <a class="btn primary" href="{{ route('orders.create') }}">
                <i class="fa-solid fa-plus"></i> {{ __('locale.create') ?? 'Créer' }}
            </a>
        @endif
    </div>
@endsection

@section('content')
<div class="panel">
    <!-- Toolbar with Modern Tabs and Pager -->
    <div class="toolbar">
        <div class="tabs">
            @php
                $currentPrefix = request('reference_prefix');
                $currentItemType = request('item_type');
                $currentStatut = request('statut');
                $isAll = empty($currentPrefix) && empty($currentItemType) && empty($currentStatut);
                $isNS = $currentPrefix === 'NS';
                $isNO = $currentPrefix === 'NO';
                $isServices = $currentItemType === 'services_only';
                $isGoods = $currentItemType === 'goods_only';
                $isMixte = $currentItemType === 'goods_and_services';
            @endphp
            <a href="{{ route('orders.index') }}" class="tab {{ $isAll ? 'active' : '' }}">
                Toutes les commandes <span class="count">{{ $orders->total() }}</span>
            </a>
            <a href="{{ route('orders.index', ['reference_prefix' => 'NS']) }}" class="tab {{ $isNS ? 'active' : '' }}">
                <i class="fa-solid fa-cart-shopping me-1"></i> Commandes (NS)
            </a>
            <a href="{{ route('orders.index', ['reference_prefix' => 'NO']) }}" class="tab {{ $isNO ? 'active' : '' }}">
                <i class="fa-solid fa-file-lines me-1"></i> Devis (NO)
            </a>
            <a href="{{ route('orders.index', ['item_type' => 'services_only']) }}" class="tab {{ $isServices ? 'active' : '' }}">
                <i class="fa-solid fa-wrench me-1"></i> Services
            </a>
            <a href="{{ route('orders.index', ['item_type' => 'goods_only']) }}" class="tab {{ $isGoods ? 'active' : '' }}">
                <i class="fa-solid fa-box me-1"></i> Matériels
            </a>
            <a href="{{ route('orders.index', ['item_type' => 'goods_and_services']) }}" class="tab {{ $isMixte ? 'active' : '' }}">
                <i class="fa-solid fa-layer-group me-1"></i> Mixte
            </a>
        </div>

        <div class="toolbar-right">
            <div class="pager">
                <span id="pagerLabel" class="pager-label">
                    {{ $orders->firstItem() ?? 0 }}-{{ $orders->lastItem() ?? 0 }} / {{ $orders->total() }}
                </span>
                @if ($orders->onFirstPage())
                    <span class="pager-btn" style="opacity: 0.45; cursor: not-allowed;"><i class="fa-solid fa-chevron-left"></i></span>
                @else
                    <a href="{{ $orders->previousPageUrl() }}" class="pager-btn" aria-label="Page précédente"><i class="fa-solid fa-chevron-left"></i></a>
                @endif

                @if ($orders->hasMorePages())
                    <a href="{{ $orders->nextPageUrl() }}" class="pager-btn" aria-label="Page suivante"><i class="fa-solid fa-chevron-right"></i></a>
                @else
                    <span class="pager-btn" style="opacity: 0.45; cursor: not-allowed;"><i class="fa-solid fa-chevron-right"></i></span>
                @endif
            </div>
        </div>
    </div>

    <!-- Advanced Filters Form Collapse -->
    <div class="collapse {{ $hasActiveFilters ? 'show' : '' }}" id="advancedFilters" style="border-bottom: 1px solid var(--border); background: #fafbfc;">
        <div class="p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-primary d-flex align-items-center gap-2">
                    <i class="fa-solid fa-filter"></i>{{ __('locale.advanced_filters') }}
                </h6>
                @if($hasActiveFilters)
                    <a href="{{ route('orders.index', request('search') ? ['search' => request('search')] : []) }}" class="btn btn-sm btn-outline-danger">
                        <i class="fa-solid fa-rotate-left me-1"></i>{{ __('locale.orders.index.reset') }}
                    </a>
                @endif
            </div>
            <form method="GET" action="{{ route('orders.index') }}">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif
                @if(request('direction'))
                    <input type="hidden" name="direction" value="{{ request('direction') }}">
                @endif
                @if(request('password'))
                    <input type="hidden" name="password" value="{{ request('password') }}">
                @endif
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="statut" class="form-label">{{ __('locale.orders.index.status_label') }}</label>
                        <input type="text" name="statut" id="statut" value="{{ request('statut') }}" class="form-control" placeholder="{{ __('locale.orders.index.status_label') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="reference_prefix" class="form-label">{{ __('locale.orders.index.reference_prefix_label') }}</label>
                        <select name="reference_prefix" id="reference_prefix" class="form-select">
                            <option value="">{{ __('locale.orders.index.reference_prefix_all') }}</option>
                            <option value="NS" @selected(request('reference_prefix') === 'NS')>NS (Commandes)</option>
                            <option value="NO" @selected(request('reference_prefix') === 'NO')>NO (Devis)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="item_type" class="form-label">{{ __('locale.orders.index.item_type_label') }}</label>
                        <select name="item_type" id="item_type" class="form-select">
                            <option value="">{{ __('locale.orders.index.item_type_all') }}</option>
                            <option value="services_only" @selected(request('item_type') === 'services_only')>{{ __('locale.orders.index.item_type_services_only') }}</option>
                            <option value="goods_only" @selected(request('item_type') === 'goods_only')>{{ __('locale.orders.index.item_type_goods_only') }}</option>
                            <option value="goods_and_services" @selected(request('item_type') === 'goods_and_services')>{{ __('locale.orders.index.item_type_goods_and_services') }}</option>
                        </select>
                    </div>
                    <div class="col-12 d-flex justify-content-end gap-2 mt-2">
                        @if($hasActiveFilters)
                            <a href="{{ route('orders.index', request('search') ? ['search' => request('search')] : []) }}" class="btn btn-outline-secondary">
                                {{ __('locale.orders.index.reset') }}
                            </a>
                        @endif
                        <button type="submit" class="btn primary">
                            <i class="fa-solid fa-filter me-1"></i>{{ __('locale.btn_apply_filters') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Import Section (Collapsible) -->
    @if(auth()->user()->isNovalyoAdmin())
        <div class="collapse" id="importSection" style="border-bottom: 1px solid var(--border); background: #fafbfc;">
            <div class="p-4">
                <form id="orderImportForm" class="import-form" method="POST" action="{{ route('orders.import') }}" enctype="multipart/form-data">
                    @csrf
                    <h6 class="fw-bold mb-3 text-success d-flex align-items-center gap-2">
                        <i class="fa-solid fa-cloud-arrow-up"></i>{{ __('locale.import.orders_import') }}
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="company_id" class="form-label">{{ __('locale.import.select_company') }}</label>
                            <select name="company_id" id="company_id" class="form-select select2" required data-placeholder="{{ __('locale.import.select_company') }}">
                                <option value=""></option>
                                @foreach(\App\Models\Company::all() as $comp)
                                    <option value="{{ $comp->id }}" @selected(session('active_company_prefix') === $comp->code)>{{ $comp->name }} ({{ $comp->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="file" class="form-label">{{ __('locale.menu_order') }} (CSV / XLSX)</label>
                            <input type="file" name="file" id="file" class="form-control" accept=".csv,.xlsx,.xls">
                        </div>
                        <div class="col-md-4">
                            <label for="details_file" class="form-label">{{ __('locale.import.orders_details_import') }} (CSV / XLSX)</label>
                            <input type="file" name="details_file" id="details_file" class="form-control" accept=".csv,.xlsx,.xls">
                        </div>
                        <div class="col-12 d-flex justify-content-end gap-2 mt-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fa-solid fa-upload me-1"></i>{{ __('locale.import.btn_import') }}
                            </button>
                        </div>
                    </div>
                </form>
                <div id="orderImportMessage" class="alert d-none mt-3" role="alert" aria-live="polite" aria-atomic="true"></div>
            </div>
        </div>
    @endif

    @if ($message = Session::get('success'))
        <div class="alert alert-success m-3 mb-0" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ $message }}
        </div>
    @endif

    <!-- Table -->
    @if (count($orders) > 0)
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th class="check-col"><input id="checkAll" type="checkbox" aria-label="Tout sélectionner"></th>
                        <th style="min-width: 70px;">
                            <a href="{{ route('orders.index', array_merge(request()->query(), ['sort' => 'id', 'direction' => request('sort') === 'id' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="sortable {{ request('sort') === 'id' ? 'sorted' : '' }}">
                                {{ __('locale.orders.table.id') }}
                                <span class="sort-icons">
                                    <i class="fa-solid fa-caret-up {{ request('sort') === 'id' && request('direction') === 'asc' ? 'text-primary' : '' }}"></i>
                                    <i class="fa-solid fa-caret-down {{ request('sort') === 'id' && request('direction') === 'desc' ? 'text-primary' : '' }}"></i>
                                </span>
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('orders.index', array_merge(request()->query(), ['sort' => 'nature_piece', 'direction' => request('sort') === 'nature_piece' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="sortable {{ request('sort') === 'nature_piece' ? 'sorted' : '' }}">
                                {{ __('locale.orders.table.nature') }}
                                <span class="sort-icons">
                                    <i class="fa-solid fa-caret-up {{ request('sort') === 'nature_piece' && request('direction') === 'asc' ? 'text-primary' : '' }}"></i>
                                    <i class="fa-solid fa-caret-down {{ request('sort') === 'nature_piece' && request('direction') === 'desc' ? 'text-primary' : '' }}"></i>
                                </span>
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('orders.index', array_merge(request()->query(), ['sort' => 'date_piece', 'direction' => request('sort', 'date_piece') === 'date_piece' && request('direction', 'desc') === 'asc' ? 'desc' : 'asc'])) }}" class="sortable {{ request('sort', 'date_piece') === 'date_piece' ? 'sorted' : '' }}">
                                {{ __('locale.orders.table.date') }}
                                <span class="sort-icons">
                                    <i class="fa-solid fa-caret-up {{ request('sort', 'date_piece') === 'date_piece' && request('direction', 'desc') === 'asc' ? 'text-primary' : '' }}"></i>
                                    <i class="fa-solid fa-caret-down {{ request('sort', 'date_piece') === 'date_piece' && request('direction', 'desc') === 'desc' ? 'text-primary' : '' }}"></i>
                                </span>
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('orders.index', array_merge(request()->query(), ['sort' => 'ref_interne', 'direction' => request('sort') === 'ref_interne' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="sortable {{ request('sort') === 'ref_interne' ? 'sorted' : '' }}">
                                {{ __('locale.orders.table.reference') }}
                                <span class="sort-icons">
                                    <i class="fa-solid fa-caret-up {{ request('sort') === 'ref_interne' && request('direction') === 'asc' ? 'text-primary' : '' }}"></i>
                                    <i class="fa-solid fa-caret-down {{ request('sort') === 'ref_interne' && request('direction') === 'desc' ? 'text-primary' : '' }}"></i>
                                </span>
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('orders.index', array_merge(request()->query(), ['sort' => 'odoo_id', 'direction' => request('sort') === 'odoo_id' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="sortable {{ request('sort') === 'odoo_id' ? 'sorted' : '' }}">
                                {{ __('locale.lbl_odoo_id') }}
                                <span class="sort-icons">
                                    <i class="fa-solid fa-caret-up {{ request('sort') === 'odoo_id' && request('direction') === 'asc' ? 'text-primary' : '' }}"></i>
                                    <i class="fa-solid fa-caret-down {{ request('sort') === 'odoo_id' && request('direction') === 'desc' ? 'text-primary' : '' }}"></i>
                                </span>
                            </a>
                        </th>
                        <th>{{ __('locale.orders.table.client') }}</th>
                        <th>
                            <a href="{{ route('orders.index', array_merge(request()->query(), ['sort' => 'statut', 'direction' => request('sort') === 'statut' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="sortable {{ request('sort') === 'statut' ? 'sorted' : '' }}">
                                {{ __('locale.orders.table.status') }}
                                <span class="sort-icons">
                                    <i class="fa-solid fa-caret-up {{ request('sort') === 'statut' && request('direction') === 'asc' ? 'text-primary' : '' }}"></i>
                                    <i class="fa-solid fa-caret-down {{ request('sort') === 'statut' && request('direction') === 'desc' ? 'text-primary' : '' }}"></i>
                                </span>
                            </a>
                        </th>
                        <th style="text-align: right;">
                            <a href="{{ route('orders.index', array_merge(request()->query(), ['sort' => 'total_ht', 'direction' => request('sort') === 'total_ht' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="sortable {{ request('sort') === 'total_ht' ? 'sorted' : '' }}">
                                {{ __('locale.orders.table.total_ht') }}
                                <span class="sort-icons">
                                    <i class="fa-solid fa-caret-up {{ request('sort') === 'total_ht' && request('direction') === 'asc' ? 'text-primary' : '' }}"></i>
                                    <i class="fa-solid fa-caret-down {{ request('sort') === 'total_ht' && request('direction') === 'desc' ? 'text-primary' : '' }}"></i>
                                </span>
                            </a>
                        </th>
                        <th style="text-align: right;">
                            <a href="{{ route('orders.index', array_merge(request()->query(), ['sort' => 'total_ttc', 'direction' => request('sort') === 'total_ttc' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="sortable {{ request('sort') === 'total_ttc' ? 'sorted' : '' }}">
                                {{ __('locale.orders.table.total_ttc') }}
                                <span class="sort-icons">
                                    <i class="fa-solid fa-caret-up {{ request('sort') === 'total_ttc' && request('direction') === 'asc' ? 'text-primary' : '' }}"></i>
                                    <i class="fa-solid fa-caret-down {{ request('sort') === 'total_ttc' && request('direction') === 'desc' ? 'text-primary' : '' }}"></i>
                                </span>
                            </a>
                        </th>
                        <th style="text-align: center;">
                            <a href="{{ route('orders.index', array_merge(request()->query(), ['sort' => 'lines_count', 'direction' => request('sort') === 'lines_count' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="sortable {{ request('sort') === 'lines_count' ? 'sorted' : '' }}">
                                {{ __('locale.orders.table.lines') }}
                                <span class="sort-icons">
                                    <i class="fa-solid fa-caret-up {{ request('sort') === 'lines_count' && request('direction') === 'asc' ? 'text-primary' : '' }}"></i>
                                    <i class="fa-solid fa-caret-down {{ request('sort') === 'lines_count' && request('direction') === 'desc' ? 'text-primary' : '' }}"></i>
                                </span>
                            </a>
                        </th>
                        <th style="width: 50px; text-align: center;"></th>
                    </tr>
                </thead>
                <tbody id="orderRows">
                    @foreach ($orders as $item)
                        @php
                            $url = route('orders.show', $item);
                            $client = $item->client;
                            $concurrent = $client?->concurrent;
                            $defaultPayment = $client?->default_payment;

                            // Contract status
                            $hasActiveContract = false;
                            if ($client && $client->contracts && $client->contracts->isNotEmpty()) {
                                $hasActiveContract = $client->contracts->contains(function ($c) {
                                    return $c->calculateStatus() === 'avec_contrat';
                                });
                            } elseif ($client && $client->contract_status) {
                                $hasActiveContract = ($client->contract_status->value ?? $client->contract_status) === 'avec_contrat';
                            }

                            // Status style
                            $st = strtolower($item->statut ?? '');
                            $sClass = 's-blue';
                            $sIcon = 'fa-circle-info';
                            if (str_contains($st, 'factur') || str_contains($st, 'livr') || str_contains($st, 'clos') || str_contains($st, 'term')) {
                                $sClass = 's-green';
                                $sIcon = 'fa-check';
                            } elseif (str_contains($st, 'annul') || str_contains($st, 'rejet') || str_contains($st, 'bloqu')) {
                                $sClass = 's-red';
                                $sIcon = 'fa-xmark';
                            } elseif (str_contains($st, 'cours') || str_contains($st, 'attente') || str_contains($st, 'valid')) {
                                $sClass = 's-orange';
                                $sIcon = 'fa-clock';
                            } elseif (str_contains($st, 'devis') || str_contains($st, 'brouillon')) {
                                $sClass = 's-purple';
                                $sIcon = 'fa-file-lines';
                            }
                        @endphp
                        <tr class="clickable" style="cursor: pointer;" onclick="window.location='{{ $url }}'">
                            <td class="check-col" onclick="event.stopPropagation();">
                                <input type="checkbox" aria-label="Sélectionner {{ $item->id }}">
                            </td>
                            <td class="ticket-col">
                                <span class="ticket-id">{{ $item->id }}</span>
                            </td>
                            <td>
                                <span class="badge" style="background: #f1f5f9; color: #475569; font-weight: 600;">
                                    {{ $item->nature_piece ?? '—' }}
                                </span>
                            </td>
                            <td>
                                <div class="datetime">
                                    <strong>{{ $item->date_piece ? format_date($item->date_piece, 'short') : '—' }}</strong>
                                </div>
                            </td>
                            <td>
                                <span class="badge" style="background: #f8fafc; color: #334155; border: 1px solid #e2e8f0; font-family: monospace; font-size: 11.5px; font-weight: 600;">
                                    {{ $item->ref_interne ?? '—' }}
                                </span>
                            </td>
                            <td>
                                @if ($item->odoo_id)
                                    <a href="{{ rtrim(config('services.odoo.url'), '/') }}/web#id={{ $item->odoo_id }}&model=sale.order&view_type=form"
                                       target="_blank"
                                       class="badge"
                                       style="background: #edf7ff; color: #087ce7; border: 1px solid #9cc7ff; font-family: monospace; font-weight: 600; text-decoration: none;"
                                       onclick="event.stopPropagation();">
                                        <i class="fa-solid fa-arrow-up-right-from-square me-1" style="font-size: 9px;"></i>{{ $item->odoo_id }}
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="client-col">
                                <div class="client">
                                    @if ($client)
                                        <div class="d-flex align-items-center gap-1 mb-1">
                                            @if ($hasActiveContract)
                                                <span class="badge contract on"><i class="fa-solid fa-shield-halved"></i>Sous contrat</span>
                                            @else
                                                <span class="badge contract off"><i class="fa-solid fa-shield-halved"></i>Hors contrat</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('clients.show', $client->id) }}" onclick="event.stopPropagation();" class="text-dark fw-semibold">
                                            {{ $client->raison_sociale }}
                                        </a>
                                        @if (!empty($client->ville))
                                            <span class="city">{{ $client->ville }}</span>
                                        @endif
                                        @if ($defaultPayment || $concurrent)
                                            <i class="fa-solid fa-triangle-exclamation text-danger ms-1" data-bs-toggle="tooltip" title="{{ $defaultPayment ? __('locale.default_payment') : __('locale.lbl_concurrent') }}" onclick="event.stopPropagation();"></i>
                                        @endif
                                    @else
                                        <span class="text-muted">{{ __('locale.lbl_no_client') }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if ($item->statut)
                                    <span class="status {{ $sClass }}">
                                        <i class="fa-solid {{ $sIcon }}"></i>{{ $item->statut }}
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                                @if ($item->odoo_id)
                                    <div class="mt-1">
                                        @if ($item->state === 'sale')
                                            <span class="badge badge-solid-success" style="font-size: 10px;" title="Confirmed in Odoo">
                                                <i class="fa-solid fa-check me-1"></i>{{ __('locale.lbl_odoo_sale') }}
                                            </span>
                                        @else
                                            <span class="badge badge-solid-warning" style="font-size: 10px;" title="Synced as Draft in Odoo">
                                                <i class="fa-solid fa-file me-1"></i>{{ __('locale.lbl_odoo_draft') }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td style="text-align: right;" class="fw-medium text-dark">
                                {{ $item->total_ht ? format_number($item->total_ht, 2) : '—' }}
                            </td>
                            <td style="text-align: right;" class="fw-bold text-primary">
                                {{ $item->total_ttc ? format_number($item->total_ttc, 2) : '—' }}
                            </td>
                            <td style="text-align: center;">
                                <span class="badge" style="background: #f1f5f9; color: #475569; font-weight: 600;">
                                    {{ $item->lines_count }}
                                </span>
                            </td>
                            <td style="text-align: center;" onclick="event.stopPropagation();">
                                <div class="dropdown">
                                    <button class="btn btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 32px; height: 32px; padding: 0; border: none; background: transparent;">
                                        <i class="fa-solid fa-ellipsis-vertical text-muted"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                        <li>
                                            <a href="{{ route('orders.show', $item) }}" class="dropdown-item">
                                                <i class="fa-solid fa-eye me-2 text-muted"></i>{{ __('locale.view') }}
                                            </a>
                                        </li>
                                        @if (Auth::user()->is_admin || Auth::user()->hasPermission('edit-orders') || Auth::user()->hasPermission('order-access'))
                                            <li>
                                                <a href="{{ route('orders.edit', $item) }}" class="dropdown-item">
                                                    <i class="fa-solid fa-pen-to-square me-2 text-warning"></i>{{ __('locale.edit') ?? 'Modifier' }}
                                                </a>
                                            </li>
                                        @endif
                                        @if (Auth::user()->is_admin || Auth::user()->hasPermission('sync-orders-odoo') || Auth::user()->hasPermission('order-access'))
                                            <li>
                                                <form action="{{ route('orders.sync-odoo', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fa-solid fa-arrows-rotate me-2 text-muted"></i>{{ __('locale.btn_sync_odoo_draft') }}
                                                    </button>
                                                </form>
                                            </li>
                                            @if ($item->state !== 'sale')
                                                <li>
                                                    <form action="{{ route('orders.confirm-odoo', $item->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="fa-solid fa-circle-check me-2 text-success"></i>{{ __('locale.btn_confirm_sale') }}
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                        @endif
                                        @if (Auth::user()->is_admin || Auth::user()->hasPermission('delete-orders') || Auth::user()->hasPermission('order-access'))
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('orders.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('locale.confirm_delete') ?? 'Êtes-vous sûr de vouloir supprimer cette commande ?' }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="fa-solid fa-trash me-2"></i>{{ __('locale.delete') ?? 'Supprimer' }}
                                                    </button>
                                                </form>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="footer-note d-flex justify-content-between align-items-center">
            <span>{{ $orders->perPage() }} commandes par page</span>
            <div>{{ $orders->withQueryString()->links() }}</div>
        </div>
    @else
        <div class="p-5 text-center text-muted">
            <i class="fa-solid fa-cart-shopping fs-1 mb-3 text-muted" style="opacity: 0.5;"></i>
            <p class="mb-0">{{ __('locale.orders.index.no_results') }}</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkAll = document.getElementById('checkAll');
        if (checkAll) {
            checkAll.addEventListener('change', function(e) {
                document.querySelectorAll('#orderRows input[type="checkbox"]').forEach(cb => {
                    cb.checked = e.target.checked;
                });
            });
        }

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        const importForm = document.getElementById('orderImportForm');
        const messageContainer = document.getElementById('orderImportMessage');

        if (importForm) {
            const submitButton = importForm.querySelector('button[type="submit"]');

            const resetMessage = () => {
                if (!messageContainer) return;
                messageContainer.classList.add('d-none');
                messageContainer.classList.remove('alert', 'alert-success', 'alert-danger');
                messageContainer.textContent = '';
            };

            const showMessage = (type, text) => {
                if (!messageContainer) return;
                messageContainer.innerHTML = text;
                messageContainer.classList.remove('d-none');
                messageContainer.classList.add('alert');
                messageContainer.classList.remove('alert-success', 'alert-danger');
                if (type === 'error') {
                    messageContainer.classList.add('alert-danger');
                } else {
                    messageContainer.classList.add('alert-success');
                }
            };

            importForm.addEventListener('submit', async function(event) {
                event.preventDefault();
                resetMessage();

                if (submitButton) {
                    submitButton.disabled = true;
                }

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
                        try {
                            payload = JSON.parse(rawText);
                        } catch (parseError) {
                            payload = rawText;
                        }
                    }

                    if (!response.ok) {
                        if (payload !== null) {
                            throw payload;
                        }
                        throw {
                            message: "{{ __('locale.import_error') }}"
                        };
                    }

                    const messages = [];
                    if (payload && typeof payload === 'object') {
                        if (payload.summary) {
                            const summary = payload.summary;
                            const createdOrders = Number(summary.orders?.created ?? 0);
                            const updatedOrders = Number(summary.orders?.updated ?? 0);
                            const skippedOrders = Number(summary.orders?.skipped ?? 0);
                            const createdLines = Number(summary.lines?.created ?? 0);
                            const skippedLines = Number(summary.lines?.skipped ?? 0);

                            const importCompletedLabel = "{{ __('locale.import_completed') }}";
                            messages.push(`<strong>${importCompletedLabel}</strong><br>`);
                            messages.push(`Commandes: Créées: ${createdOrders}, Mises à jour: ${updatedOrders}, Ignorées: ${skippedOrders}.<br>`);
                            messages.push(`Lignes de détails: Créées: ${createdLines}, Ignorées: ${skippedLines}.`);

                            if (Array.isArray(summary.errors) && summary.errors.length > 0) {
                                const errorsText = summary.errors.map(err => {
                                    return `[Fichier: ${err.file}, Ligne: ${err.row}] ${err.message}`;
                                }).join('; ');
                                messages.push(`<br><span class="text-danger">Erreurs: ${errorsText}</span>`);

                                if (summary.error_file_url) {
                                    messages.push(`<br><a href="${summary.error_file_url}" class="btn btn-sm btn-outline-danger fw-bold mt-2 d-inline-block" target="_blank"><i class="fa-solid fa-file-arrow-down me-1"></i>Télécharger le rapport d'erreurs (CSV)</a>`);
                                }
                            }
                        } else if (payload.message) {
                            messages.push(payload.message);
                        }
                    } else if (typeof payload === 'string' && payload !== '') {
                        messages.push(payload);
                    }

                    if (messages.length === 0) {
                        messages.push("{{ __('locale.import_completed') }}");
                    }

                    showMessage('success', messages.join(' '));

                    setTimeout(() => {
                        window.location.reload();
                    }, 2500);

                } catch (error) {
                    let errorMessage = "{{ __('locale.import_error') }}";
                    if (error) {
                        if (typeof error === 'string') {
                            errorMessage = error;
                        } else if (error.message) {
                            errorMessage = error.message;
                        } else if (error.errors) {
                            const flattened = Object.values(error.errors).flat();
                            if (flattened.length > 0) {
                                errorMessage = flattened.join(' ');
                            }
                        }
                    }
                    showMessage('error', errorMessage);
                } finally {
                    if (submitButton) {
                        submitButton.disabled = false;
                    }
                }
            });
        }
    });

    $(document).ready(function() {
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2').select2({
                allowClear: true,
                width: '100%'
            });
        }
    });
</script>
@endpush
