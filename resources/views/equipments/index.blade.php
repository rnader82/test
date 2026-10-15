@extends('layouts.app')
@section('portal-module', 'equipments')
@section('portal-page', 'index')
@section('portal-kind', 'list')
@section('portal-family', 'catalog')
@section('portal-icon', 'tags')


@section('navbar-search')
    <form method="GET" action="{{ route('equipments.index') }}" class="w-100">
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
        <h1>{{ __('locale.menu_equipments') }}</h1>
        <span class="header-count">({{ (is_object($equipments) && method_exists($equipments, 'total')) ? $equipments->total() : 'Error' }})</span>
    </div>
@endsection


@section('page-actions')
    <a href="{{ route('equipments.create') }}" class="btn btn-primary" title="{{ __('locale.add_equipment') }}">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.add_equipment') ?? 'Nouvel équipement' }}
    </a>
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
    .table-toolbar {
        flex-shrink: 0;
        margin-bottom: 0.5rem !important;
        padding: 0.4rem 1rem !important;
    }
    .card-premium.eq-card-table {
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        margin-bottom: 0 !important;
    }
    .card-premium.eq-card-table .card-body {
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .card-premium.eq-card-table .table-responsive {
        flex: 1 1 auto;
        overflow-y: auto;
        overflow-x: hidden;
    }
    .card-premium.eq-card-table table thead th {
        position: sticky;
        top: 0;
        z-index: 5;
        background: #f8fafc !important;
    }
    .card-footer {
        flex-shrink: 0;
        padding-top: 0.4rem !important;
        padding-bottom: 0.4rem !important;
    }
    .copy-reference-btn {
        cursor: pointer;
        transition: all 0.15s ease-in-out;
    }
    .copy-reference-btn:hover {
        filter: brightness(0.92);
        transform: scale(1.02);
    }
</style>
@endpush

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        
        <div class="table-toolbar mb-2">
            <div class="toolbar-left">
                <span class="text-muted small">
                    @if(is_object($equipments) && method_exists($equipments, 'firstItem'))
                        {{ __('locale.results_sur', ['first' => $equipments->firstItem() ?? 0, 'last' => $equipments->lastItem() ?? 0, 'total' => $equipments->total()]) }}
                    @else
                        {{ __('locale.equipment_index.results_error') }}
                    @endif
                </span>
            </div>
            <div class="toolbar-right d-flex gap-2">
                @if (Auth::user()->isNovalyoAdmin())
                    <div class="dropdown d-inline">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                            id="syncAllOdooDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                            title="{{ __('locale.title_sync_all_equipments') }}">
                            <i class="bi bi-arrow-repeat me-1"></i> {{ __('locale.btn_sync_all_odoo') }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border" aria-labelledby="syncAllOdooDropdown">
                            <li><h6 class="dropdown-header">{{ __('locale.equipment_index.sync_unsynced_header') }}</h6></li>
                            <li>
                                <form action="{{ route('equipments.sync-all-odoo') }}" method="POST" class="d-inline"
                                    onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').innerHTML='<i class=\'bi bi-arrow-repeat spin me-1\'></i> {{ __('locale.equipment_index.btn_syncing') }}';">
                                    @csrf
                                    <input type="hidden" name="sync_inventory" value="1">
                                    <button type="submit" class="dropdown-item" onclick="return confirm('{{ __('locale.confirm_sync_unsynced_equipments') }}')">
                                        <i class="bi bi-arrow-repeat me-2 text-primary"></i>{{ __('locale.equipment_index.btn_sync_with_inv') }}
                                    </button>
                                </form>
                            </li>
                            <li>
                                <form action="{{ route('equipments.sync-all-odoo') }}" method="POST" class="d-inline"
                                    onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').innerHTML='<i class=\'bi bi-arrow-repeat spin me-1\'></i> {{ __('locale.equipment_index.btn_syncing') }}';">
                                    @csrf
                                    <input type="hidden" name="sync_inventory" value="0">
                                    <button type="submit" class="dropdown-item" onclick="return confirm('{{ __('locale.confirm_sync_unsynced_equipments') }}')">
                                        <i class="bi bi-arrow-repeat me-2 text-secondary"></i>{{ __('locale.equipment_index.btn_sync_no_inv') }}
                                    </button>
                                </form>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><h6 class="dropdown-header">{{ __('locale.equipment_index.force_resync_header') }}</h6></li>
                            <li>
                                <form action="{{ route('equipments.sync-all-odoo') }}?force=1" method="POST" class="d-inline"
                                    onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').innerHTML='<i class=\'bi bi-arrow-repeat spin me-1\'></i> {{ __('locale.equipment_index.btn_syncing') }}';">
                                    @csrf
                                    <input type="hidden" name="sync_inventory" value="1">
                                    <button type="submit" class="dropdown-item" onclick="return confirm('{{ __('locale.confirm_force_resync_equipments') }}')">
                                        <i class="bi bi-arrow-repeat me-2 text-warning"></i>{{ __('locale.btn_force_resync_all_equipments') }} + inventory
                                    </button>
                                </form>
                            </li>
                            <li>
                                <form action="{{ route('equipments.sync-all-odoo') }}?force=1" method="POST" class="d-inline"
                                    onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').innerHTML='<i class=\'bi bi-arrow-repeat spin me-1\'></i> {{ __('locale.equipment_index.btn_syncing') }}';">
                                    @csrf
                                    <input type="hidden" name="sync_inventory" value="0">
                                    <button type="submit" class="dropdown-item" onclick="return confirm('{{ __('locale.confirm_force_resync_equipments') }}')">
                                        <i class="bi bi-arrow-repeat me-2 text-muted"></i>{{ __('locale.btn_force_resync_all_equipments') }} (no inventory)
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endif
                @php
                    $selectedProductType = request('product_type');
                    if (!$selectedProductType) {
                        if (request('is_combo') === '1' || request('is_combo') === 1 || request('is_combo') === 'true') {
                            $selectedProductType = 'combo';
                        } elseif (request('is_kit') === '1' || request('is_kit') === 1 || request('is_kit') === 'true') {
                            $selectedProductType = 'kit';
                        } elseif ((request('is_combo') === '0' || request('is_combo') === 0 || request('is_combo') === 'false') && (request('is_kit') === '0' || request('is_kit') === 0 || request('is_kit') === 'false')) {
                            $selectedProductType = 'standard';
                        }
                    }

                    $hasActiveFilters = request()->filled('brand_id')
                        || request()->filled('equipment_type_id')
                        || request()->filled('equipment_category_id')
                        || request()->filled('company_id')
                        || request()->filled('accounting_family_id')
                        || request()->filled('odoo_category_id')
                        || request()->filled('supplier_id')
                        || request()->filled('status')
                        || request()->filled('product_type')
                        || request()->filled('is_combo')
                        || request()->filled('is_kit')
                        || (request()->has('closed') && request('closed') !== '0');
                @endphp
                <a href="#advancedFilters" data-bs-toggle="collapse" role="button" aria-expanded="{{ $hasActiveFilters ? 'true' : 'false' }}" aria-controls="advancedFilters" class="btn btn-outline-secondary btn-sm position-relative"> 
                    <i class="bi bi-filter me-1"></i> {{ __('locale.advanced_filters') }} 
                    @if($hasActiveFilters)
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-primary border border-light rounded-circle">
                            <span class="visually-hidden">{{ __('locale.active_filters') }}</span>
                        </span>
                    @endif
                </a>
                @if (Auth::user()->isNovalyoAdmin())
                <a href="#importSection" class="btn btn-outline-primary btn-sm" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="importSection">
                    <i class="bi bi-cloud-arrow-up me-1"></i> {{ __('locale.import.btn_import') }}
                </a>
                @endif
            </div>
        </div>

        <div class="collapse {{ $hasActiveFilters ? 'show' : '' }}" id="advancedFilters">
            <div class="card card-premium mb-4 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-funnel-fill me-2"></i>{{ __('locale.advanced_filters') }}</h5>
                    @if($hasActiveFilters)
                        <a href="{{ route('equipments.index', request('search') ? ['search' => request('search')] : []) }}" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-x-circle me-1"></i>{{ __('locale.lbl_reset_filters') }}
                        </a>
                    @endif
                </div>
                <form method="GET" action="{{ route('equipments.index') }}">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    @if(request('per_page'))
                        <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                    @endif
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small text-muted text-uppercase">{{ __('locale.lbl_filter_by_brand') }}</label>
                            <select name="brand_id" class="form-select select2" data-placeholder="{{ __('locale.lbl_filter_by_brand') }}">
                                <option value=""></option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small text-muted text-uppercase">{{ __('locale.lbl_filter_by_type') }}</label>
                            <select name="equipment_type_id" class="form-select select2" data-placeholder="{{ __('locale.lbl_filter_by_type') }}">
                                <option value=""></option>
                                @foreach($equipmentTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('equipment_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small text-muted text-uppercase">{{ __('locale.lbl_filter_by_category') }}</label>
                            <select name="equipment_category_id" class="form-select select2" data-placeholder="{{ __('locale.lbl_filter_by_category') }}">
                                <option value=""></option>
                                @foreach($equipmentCategories as $category)
                                    <option value="{{ $category->id }}" {{ request('equipment_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small text-muted text-uppercase">{{ __('locale.lbl_filter_by_company') }}</label>
                            <select name="company_id" class="form-select select2" data-placeholder="{{ __('locale.lbl_filter_by_company') }}">
                                <option value=""></option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small text-muted text-uppercase">{{ __('locale.lbl_filter_by_accounting_family') }}</label>
                            <select name="accounting_family_id" class="form-select select2" data-placeholder="{{ __('locale.lbl_filter_by_accounting_family') }}">
                                <option value=""></option>
                                @foreach($accountingFamilies as $family)
                                    <option value="{{ $family->id }}" {{ request('accounting_family_id') == $family->id ? 'selected' : '' }}>
                                        {{ $family->code_cegid ? '[' . $family->code_cegid . '] ' : '' }}{{ $family->family_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small text-muted text-uppercase">{{ __('locale.lbl_filter_by_odoo_category') }}</label>
                            <select name="odoo_category_id" class="form-select select2" data-placeholder="{{ __('locale.lbl_filter_by_odoo_category') }}">
                                <option value=""></option>
                                @foreach($odooCategories as $odooCat)
                                    <option value="{{ $odooCat->id }}" {{ request('odoo_category_id') == $odooCat->id ? 'selected' : '' }}>{{ $odooCat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small text-muted text-uppercase">{{ __('locale.lbl_filter_by_supplier') }}</label>
                            <select name="supplier_id" class="form-select select2" data-placeholder="{{ __('locale.lbl_filter_by_supplier') }}">
                                <option value=""></option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small text-muted text-uppercase">{{ __('locale.lbl_filter_by_status') }}</label>
                            <select name="status" class="form-select select2" data-placeholder="{{ __('locale.lbl_filter_by_status') }}">
                                <option value=""></option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small text-muted text-uppercase">{{ __('locale.equipment_show.lbl_product_type') ?? 'Type de produit' }}</label>
                            <select name="product_type" class="form-select select2" data-placeholder="{{ __('locale.combo.filter_combo') ?? 'Tous (Standards, Combos, Kits)' }}">
                                <option value="">{{ __('locale.combo.filter_combo') ?? 'Tous (Standards, Combos, Kits)' }}</option>
                                <option value="combo" {{ $selectedProductType === 'combo' ? 'selected' : '' }}>{{ __('locale.combo.filter_combo_only') ?? 'Combos uniquement' }}</option>
                                <option value="kit" {{ $selectedProductType === 'kit' ? 'selected' : '' }}>{{ __('locale.kit.filter_kit_only') ?? 'Kits uniquement' }}</option>
                                <option value="standard" {{ $selectedProductType === 'standard' ? 'selected' : '' }}>{{ __('locale.combo.filter_standard_only') ?? 'Standards uniquement' }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small text-muted text-uppercase">{{ __('locale.lbl_closed') }}</label>
                            <select name="closed" class="form-select select2">
                                <option value="all" {{ request('closed') === 'all' ? 'selected' : '' }}>{{ __('locale.lbl_all_closed') }}</option>
                                <option value="0" {{ !request()->has('closed') || request('closed') === '0' ? 'selected' : '' }}>{{ __('locale.lbl_open') }}</option>
                                <option value="1" {{ request('closed') === '1' ? 'selected' : '' }}>{{ __('locale.lbl_closed') }}</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if (Auth::user()->isNovalyoAdmin())
        <div class="collapse" id="importSection">
            <div class="card card-premium mb-4 p-4">
                <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-cloud-arrow-up-fill me-2"></i>{{ __('locale.import.btn_import') }}</h5>
                <div class="row align-items-center g-4">
                    @if(auth()->user()->is_admin || auth()->user()->hasPermission('import-equipment-lead-time'))
                        <div class="col-md-6 border-end">
                            <h5 class="fw-bold mb-2">{{ __('locale.import.csv_import') }}</h5>
                            <p class="text-muted small mb-3">{{ __('locale.import.lead_times_help') }}</p>
                            <form id="equipmentLeadTimesImportForm" class="import-form" method="POST" action="{{ route('equipments.lead_times_import') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="input-group input-group-sm mb-2 mx-auto" style="max-width: 300px;">
                                    <input type="file" name="file" class="form-control" accept=".csv,.xlsx" required>
                                    <button type="submit" class="btn btn-secondary">
                                        <i class="bi bi-upload"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                    @if(auth()->user()->is_admin || auth()->user()->hasPermission('edit-equipment'))
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-2">{{ __('locale.import.buying_price_import') }}</h5>
                            <p class="text-muted small mb-3">{{ __('locale.import.buying_price_help') }}</p>
                            <form id="equipmentBuyingPriceImportForm" class="import-form" method="POST" action="{{ route('equipments.buying_price_import') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="input-group input-group-sm mb-2 mx-auto" style="max-width: 300px;">
                                    <input type="file" name="file" class="form-control" accept=".csv,.xlsx" required>
                                    <button type="submit" class="btn btn-primary" title="{{ __('locale.import.btn_import') }}">
                                        <i class="bi bi-upload"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <div id="equipmentImportMessage" class="mt-3 d-none" role="alert" aria-live="polite" aria-atomic="true"></div>

        @if (session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger mt-3">{{ session('error') }}</div>
        @endif

        @php
            $canSeeSalePrice = auth()->user()?->canSeeEquipmentSalePrices() ?? false;
        @endphp
        <div class="card card-premium eq-card-table">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="module-table table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">{{ __('locale.lbl_reference') }}</th>
                                <th>{{ __('locale.lbl_designation') }}</th>
                                @if($canSeeSalePrice)
                                    <th>{{ __('locale.lbl_price_per_unit') }}</th>
                                @endif
                                <th class="text-end pe-4" style="width: 100px;">{{ __('locale.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($equipments as $equipment)
                                <tr style="cursor: pointer;" onclick="window.location='{{ route('equipments.show', $equipment) }}'">
                                    <td class="ps-4" onclick="event.stopPropagation();" style="cursor: default;">
                                        <span class="badge bg-primary-subtle text-primary border-0 px-3 py-2 fw-bold copy-reference-btn"
                                              role="button"
                                              onclick="copyEquipmentReference(event, this, '{{ addslashes($equipment->reference) }}')"
                                              title="{{ __('locale.click_to_copy') }}">
                                            <span class="reference-label">{{ $equipment->reference }}</span>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-bold fs-6 text-dark d-flex align-items-center gap-1 flex-wrap">
                                            <span>{{ $equipment->name }}</span>
                                            @if($equipment->is_combo)
                                                <span class="badge bg-primary text-white border-0 px-2 py-0.5 fs-12 fw-bold" style="font-size: 0.75rem;"><i class="bi bi-layers me-0.5"></i>{{ __('locale.combo.badge_combo') ?? 'Combo' }}</span>
                                            @endif
                                            @if($equipment->is_kit)
                                                <span class="badge bg-success text-white border-0 px-2 py-0.5 fs-12 fw-bold" style="font-size: 0.75rem;"><i class="bi bi-box-seam me-0.5"></i>{{ __('locale.kit.badge_kit') ?? 'Kit' }}</span>
                                                @if($equipment->is_kit_price_manual)
                                                    <span class="badge bg-warning text-dark border-0 px-2 py-0.5 fs-12 fw-bold" style="font-size: 0.72rem;" title="{{ __('locale.kit.manual_price_tooltip') ?? 'Le prix de ce kit a été défini manuellement' }}">
                                                        <i class="bi bi-pencil me-0.5"></i>{{ __('locale.kit.badge_price_manual') ?? 'Prix manuel' }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-0.5 fs-12 fw-bold" style="font-size: 0.72rem;" title="{{ __('locale.kit.auto_price_tooltip') ?? 'Le prix de ce kit est synchronisé automatiquement avec ses composants' }}">
                                                        <i class="bi bi-magic me-0.5"></i>{{ __('locale.kit.badge_price_auto') ?? 'Prix auto' }}
                                                    </span>
                                                @endif
                                            @endif
                                            @if($equipment->closed)
                                                <span class="badge bg-danger text-danger bg-opacity-10 border-0 px-2 py-0.5 fs-12 fw-bold" style="font-size: 0.75rem;"><i class="bi bi-lock-fill me-0.5"></i>{{ __('locale.lbl_closed') ?? 'Fermé' }}</span>
                                            @endif
                                        </div>
                                        <div class="small text-muted">{{ $equipment->brand->name ?? __('locale.no_brand') }}</div>
                                    </td>
                                    @if($canSeeSalePrice)
                                    <td>
                                        <span class="fw-medium text-dark">{{ format_number($equipment->price_per_unit, 2) }} {{ __('locale.currency') }}</span>
                                    </td>
                                    @endif
                                    <td class="text-end pe-4" onclick="event.stopPropagation();">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" id="equipmentActionMenu{{ $equipment->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border" aria-labelledby="equipmentActionMenu{{ $equipment->id }}">
                                                <li>
                                                    <a href="{{ route('equipments.edit', $equipment) }}" class="dropdown-item">
                                                        <i class="bi bi-pencil-square me-2 text-warning"></i>{{ __('locale.edit') }}
                                                    </a>
                                                </li>
                                                @if (Auth::user()->is_admin)
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><h6 class="dropdown-header">{{ __('locale.equipment_index.sync_odoo_header') }}</h6></li>
                                                    <li>
                                                        <form action="{{ route('equipments.sync-odoo', $equipment->id) }}" method="POST"
                                                              onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').innerHTML='<i class=\'bi bi-arrow-repeat spin me-1\'></i> Syncing…';">
                                                            @csrf
                                                            <input type="hidden" name="sync_inventory" value="1">
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="bi bi-arrow-repeat me-2 text-primary"></i>{{ __('locale.equipment_index.btn_sync_with_inv') }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('equipments.sync-odoo', $equipment->id) }}" method="POST"
                                                              onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').innerHTML='<i class=\'bi bi-arrow-repeat spin me-1\'></i> Syncing…';">
                                                            @csrf
                                                            <input type="hidden" name="sync_inventory" value="0">
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="bi bi-arrow-repeat me-2 text-secondary"></i>{{ __('locale.equipment_index.btn_sync_no_inv') }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('equipments.destroy', $equipment) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('{{ __('locale.confirm_delete') }}')">
                                                            <i class="bi bi-trash me-2"></i>{{ __('locale.delete') }}
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $canSeeSalePrice ? 4 : 3 }}" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                        {{ __('locale.lbl_no_records_found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if(is_object($equipments) && method_exists($equipments, 'links') && $equipments->hasPages())
                    <div class="card-footer d-flex justify-content-end border-top bg-transparent py-3">
                        {{ $equipments->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const messageContainer = document.getElementById('equipmentImportMessage');

			const resetMessage = () => {
				if (!messageContainer) {
					return;
				}

				messageContainer.classList.add('d-none');
				messageContainer.classList.remove('alert', 'alert-success', 'alert-danger');
				messageContainer.textContent = '';
			};

			const summarizeImport = (summary) => {
				if (!summary || typeof summary !== 'object') {
					return '';
				}

				const created = Number(summary.created ?? 0);
				const updated = Number(summary.updated ?? 0);
				const skipped = Number(summary.skipped ?? 0);
				const processed = Number(summary.processed ?? (created + updated + skipped));

				const createdLabel = "{{ __('locale.import_created', ['count' => '']) }}".trim();
				const updatedLabel = "{{ __('locale.import_updated', ['count' => '']) }}".trim();
				const skippedLabel = "{{ __('locale.import_skipped', ['count' => '']) }}".trim();
				const processedLabel = "{{ __('locale.import_processed', ['count' => '']) }}".trim();

				return `${createdLabel}${created}, ${updatedLabel}${updated}, ${skippedLabel}${skipped}, ${processedLabel}${processed}`;
			};

			const flattenErrors = (errors) => {
				if (!errors) {
					return [];
				}

				if (Array.isArray(errors)) {
					return errors;
				}

				if (typeof errors === 'object') {
					return Object.values(errors).reduce((accumulator, value) => {
						if (Array.isArray(value)) {
							return accumulator.concat(value);
						}

						if (value) {
							return accumulator.concat([value]);
						}

						return accumulator;
					}, []);
				}

				return [errors];
			};

			const showMessage = (type, text) => {
				if (!messageContainer) {
					return;
				}

				messageContainer.textContent = text;
				messageContainer.classList.remove('d-none');
				messageContainer.classList.add('alert');
				messageContainer.classList.remove('alert-success', 'alert-danger');

				if (type === 'error') {
					messageContainer.classList.add('alert-danger');
				} else {
					messageContainer.classList.add('alert-success');
				}
			};

			const forms = document.querySelectorAll('.import-form');
			forms.forEach(function(form) {
				form.addEventListener('submit', async function(event) {
					event.preventDefault();
					resetMessage();

					const submitButton = form.querySelector('button[type="submit"]');
					if (submitButton) {
						submitButton.disabled = true;
					}

					try {
						const formData = new FormData(form);
						const response = await fetch(form.action, {
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

					if (Array.isArray(payload)) {
						payload.forEach((item) => {
							const summaryText = summarizeImport(item);
							const fileLabel = item.file ? `${item.file}: ` : '';
							const statusCode = Number(item.status ?? 500);
							const succeeded = statusCode >= 200 && statusCode < 300;
							const importCompletedLabel = "{{ __('locale.import_completed') }}";
							const importFailedLabel = "{{ __('locale.import_failed') }}";
							let message = `${fileLabel}${succeeded ? importCompletedLabel : importFailedLabel}`;

							if (summaryText) {
								message += ` ${summaryText}`;
							}

							if (Array.isArray(item.errors) && item.errors.length > 0) {
								const errors = item.errors.map((error) => (typeof error === 'string' ? error : error.message)).filter(Boolean);
								if (errors.length > 0) {
									message += ` Errors: ${errors.join('; ')}`;
								}
							}

							messages.push(message);
						});
					} else if (payload && typeof payload === 'object') {
						if (payload.summary) {
							const summaryText = summarizeImport(payload.summary);
							const importCompletedLabel = "{{ __('locale.import_completed') }}";
							messages.push(summaryText ? `${importCompletedLabel} ${summaryText}` : importCompletedLabel);
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
				} catch (error) {
					let errorMessage = "{{ __('locale.import_error') }}";

					if (error) {
						if (typeof error === 'string') {
							errorMessage = error;
						} else if (error.message) {
							errorMessage = error.message;
						} else if (error.errors) {
							const flattened = flattenErrors(error.errors)
								.map((err) => (typeof err === 'string' ? err : err.message))
								.filter(Boolean);

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
			});
		});

        $(document).ready(function() {
            function initSelect2() {
                $('#advancedFilters .select2').select2({
                    allowClear: true,
                    width: '100%'
                });
            }

            initSelect2();

            $('#advancedFilters').on('shown.bs.collapse', function() {
                initSelect2();
            });
            
            // Automatically submit form on select change
            $('#advancedFilters .select2').on('change', function() {
                $(this).closest('form').submit();
            });

            $('.select2-no-submit').select2({
                allowClear: true,
                width: '100%'
            });
		});

        // 1-click copy for equipment reference
        function copyEquipmentReference(event, element, text) {
            if (event) {
                event.stopPropagation();
                event.preventDefault();
            }
            if (!text) return;

            var copyPromise;
            if (navigator.clipboard && window.isSecureContext) {
                copyPromise = navigator.clipboard.writeText(text);
            } else {
                copyPromise = new Promise(function(resolve, reject) {
                    var textarea = document.createElement('textarea');
                    textarea.value = text;
                    textarea.style.position = 'fixed';
                    textarea.style.top = '0';
                    textarea.style.left = '0';
                    textarea.style.opacity = '0';
                    document.body.appendChild(textarea);
                    textarea.focus();
                    textarea.select();
                    try {
                        var successful = document.execCommand('copy');
                        document.body.removeChild(textarea);
                        successful ? resolve() : reject(new Error('execCommand copy failed'));
                    } catch (err) {
                        document.body.removeChild(textarea);
                        reject(err);
                    }
                });
            }

            copyPromise.then(function() {
                var $btn = $(element);
                var $label = $btn.find('.reference-label');
                var originalText = $label.text() || text;

                $btn.removeClass('bg-primary-subtle text-primary')
                    .addClass('bg-success-subtle text-success');
                $label.html('<i class="bi bi-check2 me-1"></i>' + originalText);

                setTimeout(function() {
                    $btn.removeClass('bg-success-subtle text-success')
                        .addClass('bg-primary-subtle text-primary');
                    $label.text(originalText);
                }, 1500);
            }).catch(function(err) {
                console.error('Failed to copy reference:', err);
            });
        }
	</script>
@endsection
