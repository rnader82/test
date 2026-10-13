@extends('layouts.app')

@php
    $equipments = is_iterable($equipments) ? $equipments : collect();
    $currentStatus = request('status', '');
    $selectedWarehouseId = request('warehouse_id');
@endphp

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.inventory_management') }}</h1>
        <span class="header-count">({{ is_object($equipments) && method_exists($equipments, 'total') ? $equipments->total() : count($equipments) }})</span>
    </div>
@endsection

@section('navbar-search')
    <form method="GET" action="{{ route('equipments.inventory.index') }}" class="w-100 d-flex gap-2">
        <div class="search-bar-mockup flex-grow-1">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('locale.search_equipment') }}">
        </div>
        <select name="warehouse_id" class="form-select border-0 shadow-sm rounded-3" style="width: auto; background-color: #f8f9fa;" onchange="this.form.submit()">
            <option value="">{{ __('locale.all_warehouses') }}</option>
            @foreach($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}" {{ $selectedWarehouseId == $warehouse->id ? 'selected' : '' }}>
                    {{ $warehouse->name }} ({{ $warehouse->code }})
                </option>
            @endforeach
        </select>
        @if($currentStatus)
            <input type="hidden" name="status" value="{{ $currentStatus }}">
        @endif
        @foreach(request()->except(['search', 'warehouse_id', 'status', 'page']) as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
    </form>
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
    .kpi-row {
        flex-shrink: 0;
        margin-bottom: 0.75rem;
    }
    .kpi-card {
        background: #fff;
        border-radius: 0.75rem;
        border: 1px solid rgba(0,0,0,0.06);
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }
    .kpi-icon {
        width: 42px;
        height: 42px;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    .table-toolbar {
        flex-shrink: 0;
        margin-bottom: 0.5rem !important;
    }
    .status-pill {
        border-radius: 20px;
        padding: 0.25rem 0.85rem;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        border: 1px solid transparent;
    }
    .status-pill.active {
        background: #0d6efd;
        color: #fff !important;
    }
    .status-pill:not(.active) {
        background: #f1f3f5;
        color: #495057;
    }
    .status-pill:hover:not(.active) {
        background: #e9ecef;
    }
    .card-premium.eq-card-table {
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        margin-bottom: 0.5rem !important;
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
    .eq-pagination-bar {
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-2 py-2 px-3 small d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger border-0 shadow-sm mb-2 py-2 px-3 small d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- KPI Summary Cards (Odoo Inventory Snapshot) -->
        <div class="row g-2 kpi-row">
            <div class="col-6 col-md-3">
                <div class="kpi-card">
                    <div class="kpi-icon bg-primary-subtle text-primary">
                        <i class="bi bi-boxes"></i>
                    </div>
                    <div>
                        <span class="text-muted d-block small text-uppercase fw-semibold">{{ __('locale.lbl_on_hand_qty') }}</span>
                        <span class="fs-5 fw-bold text-dark">{{ number_format($kpis['total_on_hand_units'], 0) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="kpi-card">
                    <div class="kpi-icon bg-warning-subtle text-warning">
                        <i class="bi bi-lock-fill"></i>
                    </div>
                    <div>
                        <span class="text-muted d-block small text-uppercase fw-semibold">{{ __('locale.lbl_reserved_qty') }}</span>
                        <span class="fs-5 fw-bold text-warning">{{ number_format($kpis['total_reserved_units'], 0) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="kpi-card">
                    <div class="kpi-icon bg-success-subtle text-success">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <span class="text-muted d-block small text-uppercase fw-semibold">{{ __('locale.lbl_available_qty') }}</span>
                        <span class="fs-5 fw-bold text-success">{{ number_format($kpis['total_available_units'], 0) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="kpi-card">
                    <div class="kpi-icon bg-info-subtle text-info">
                        <i class="bi bi-shop"></i>
                    </div>
                    <div>
                        <span class="text-muted d-block small text-uppercase fw-semibold">{{ __('locale.menu_warehouses') }}</span>
                        <span class="fs-5 fw-bold text-dark">{{ $kpis['total_warehouses_count'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toolbar & Filter Pills -->
        <div class="table-toolbar d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
            <div class="toolbar-left d-flex flex-wrap align-items-center gap-1">
                @php
                    $routeParams = request()->except(['status', 'page']);
                @endphp
                <a href="{{ route('equipments.inventory.index', $routeParams) }}" 
                   class="status-pill {{ empty($currentStatus) ? 'active' : '' }}">
                    {{ __('locale.all') }}
                </a>
                <a href="{{ route('equipments.inventory.index', array_merge($routeParams, ['status' => 'in_stock'])) }}" 
                   class="status-pill {{ $currentStatus === 'in_stock' ? 'active' : '' }}">
                    <i class="bi bi-check-circle me-1"></i> {{ __('locale.in_stock') }}
                </a>
                <a href="{{ route('equipments.inventory.index', array_merge($routeParams, ['status' => 'low_stock'])) }}" 
                   class="status-pill {{ $currentStatus === 'low_stock' ? 'active' : '' }}">
                    <i class="bi bi-exclamation-triangle me-1"></i> {{ __('locale.low_stock') }}
                </a>
                <a href="{{ route('equipments.inventory.index', array_merge($routeParams, ['status' => 'out_of_stock'])) }}" 
                   class="status-pill {{ $currentStatus === 'out_of_stock' ? 'active' : '' }}">
                    <i class="bi bi-x-circle me-1"></i> {{ __('locale.out_of_stock') }}
                </a>
                <a href="{{ route('equipments.inventory.index', array_merge($routeParams, ['status' => 'reserved'])) }}" 
                   class="status-pill {{ $currentStatus === 'reserved' ? 'active' : '' }}">
                    <i class="bi bi-lock me-1"></i> {{ __('locale.lbl_reserved') }}
                </a>
            </div>
            <div class="toolbar-right d-flex gap-2">
                <form action="{{ route('equipments.inventory.sync_odoo') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm px-3 shadow-sm text-white fw-semibold" 
                            title="{{ $lastInventorySync ? __('locale.last_sync') . ': ' . $lastInventorySync : __('locale.pull_live_stock_odoo') }}"
                            {{ !$isConfigured ? 'disabled' : '' }}>
                        <i class="bi bi-arrow-repeat me-1"></i> {{ __('locale.pull_inventory_from_odoo') }}
                    </button>
                </form>
                <a href="{{ route('equipments.inventory.history') }}" class="btn btn-outline-secondary btn-sm px-3 shadow-sm">
                    <i class="bi bi-clock-history me-1"></i> {{ __('locale.history') }}
                </a>
            </div>
        </div>

        <!-- Inventory Table (Odoo Quants & Warehouses) -->
        <div class="card card-premium eq-card-table mb-2">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-premium mb-0 align-middle">
                        <thead class="small text-uppercase text-muted">
                            <tr>
                                <th class="ps-4 py-3">{{ __('locale.lbl_reference') }}</th>
                                <th class="py-3">{{ __('locale.lbl_designation') }}</th>
                                <th class="py-3">{{ __('locale.warehouse') }}</th>
                                <th class="py-3 text-center">{{ __('locale.lbl_on_hand_qty') }}</th>
                                <th class="py-3 text-center">{{ __('locale.lbl_reserved_qty') }}</th>
                                <th class="py-3 text-center">{{ __('locale.lbl_available_qty') }}</th>
                                <th class="py-3 text-center">{{ __('locale.lbl_status') }}</th>
                                <th class="pe-4 py-3 text-end">{{ __('locale.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($equipments as $equipment)
                                @php
                                    $whCount = $equipment->warehouses->count();
                                    // Warehouse-specific vs Global
                                    if ($selectedWarehouseId) {
                                        $whPivot = $equipment->warehouses->firstWhere('id', $selectedWarehouseId)?->pivot;
                                        $onHand = (float) ($whPivot->quantity ?? 0.0);
                                        $reserved = (float) ($whPivot->reserved_quantity ?? 0.0);
                                    } else {
                                        $onHand = (float) ($equipment->current_stock ?? 0.0);
                                        $reserved = (float) $equipment->reserved_stock;
                                    }
                                    $available = max(0.0, $onHand - $reserved);
                                    $minQty = (float) ($equipment->minimum_quantity ?? 0.0);
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-1">
                                            <span class="badge bg-primary-subtle text-primary border-0 px-2 py-1 fw-bold font-monospace">
                                                {{ $equipment->reference ?: '-' }}
                                            </span>
                                            @if($equipment->odoo_id)
                                                @if(config('services.odoo.url'))
                                                    <a href="{{ rtrim(config('services.odoo.url'), '/') }}/web#id={{ $equipment->odoo_id }}&model=product.product&view_type=form" 
                                                       target="_blank" 
                                                       rel="noopener noreferrer" 
                                                       class="badge bg-light text-primary border font-monospace small text-decoration-none" 
                                                       title="{{ __('locale.open_in_odoo') ?? 'Ouvrir dans Odoo' }}"
                                                       onclick="event.stopPropagation();">
                                                        Odoo #{{ $equipment->odoo_id }} <i class="bi bi-box-arrow-up-right ms-1" style="font-size: 0.65rem;"></i>
                                                    </a>
                                                @else
                                                    <span class="badge bg-light text-muted border font-monospace small" title="Odoo ID: {{ $equipment->odoo_id }}">
                                                        Odoo #{{ $equipment->odoo_id }}
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark fs-6">{{ $equipment->name }}</div>
                                        @if($equipment->brand)
                                            <div class="small text-muted italic">{{ $equipment->brand->name }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($selectedWarehouseId)
                                            @php
                                                $selectedWh = $warehouses->firstWhere('id', $selectedWarehouseId);
                                            @endphp
                                            <span class="badge bg-light text-dark border">
                                                <i class="bi bi-building me-1 text-primary"></i> {{ $selectedWh->name ?? __('locale.warehouse') }}
                                            </span>
                                        @else
                                            @if($whCount > 0)
                                                <div class="dropdown d-inline-block">
                                                    <button class="btn btn-xs btn-outline-secondary dropdown-toggle rounded-pill" type="button" data-bs-toggle="dropdown">
                                                        <i class="bi bi-shop me-1 text-secondary"></i> {{ $whCount }} {{ $whCount > 1 ? __('locale.warehouses') : __('locale.warehouse') }}
                                                    </button>
                                                    <ul class="dropdown-menu shadow-sm small">
                                                        @foreach($equipment->warehouses as $wh)
                                                            <li class="dropdown-item d-flex justify-content-between align-items-center gap-3">
                                                                <span>{{ $wh->name }} ({{ $wh->code }})</span>
                                                                <span class="badge bg-success-subtle text-success fw-bold">{{ (float) $wh->pivot->quantity }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @else
                                                <span class="text-muted small italic">{{ __('locale.no_warehouse_assigned') }}</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary-subtle text-primary border-0 fs-6 px-3 py-1 rounded-pill fw-bold font-monospace">
                                            {{ $onHand }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $reserved > 0 ? 'bg-warning-subtle text-warning' : 'bg-light text-muted' }} border-0 fs-6 px-3 py-1 rounded-pill fw-bold font-monospace">
                                            {{ $reserved }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $available > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} border-0 fs-6 px-3 py-1 rounded-pill fw-bold font-monospace">
                                            {{ $available }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($onHand <= 0)
                                            <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-25 px-2 py-1">
                                                <i class="bi bi-x-circle me-1"></i> {{ __('locale.out_of_stock') }}
                                            </span>
                                        @elseif($onHand <= $minQty)
                                            <span class="badge bg-warning-subtle text-warning border border-warning border-opacity-25 px-2 py-1">
                                                <i class="bi bi-exclamation-triangle me-1"></i> {{ __('locale.low_stock') }}
                                            </span>
                                        @else
                                            <span class="badge bg-success-subtle text-success border border-success border-opacity-25 px-2 py-1">
                                                <i class="bi bi-check-circle me-1"></i> {{ __('locale.in_stock') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="d-inline-flex gap-1">
                                            <a href="{{ route('equipments.inventory.count', $equipment) }}" 
                                               class="btn btn-sm btn-primary shadow-sm rounded-pill px-3 py-1"
                                               title="{{ __('locale.btn_adjust_count') }}">
                                                <i class="bi bi-pencil-square me-1"></i> {{ __('locale.btn_adjust') }}
                                            </a>
                                            <a href="{{ route('equipments.show', $equipment) }}" 
                                               class="btn btn-sm btn-outline-secondary shadow-sm rounded-circle p-1" style="width: 28px; height: 28px;"
                                               title="{{ __('locale.view') }}">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="opacity-25 mb-3">
                                            <i class="bi bi-boxes fs-1"></i>
                                        </div>
                                        <p class="text-muted mb-0">{{ __('locale.no_equipments_found') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if(is_object($equipments) && method_exists($equipments, 'hasPages') && $equipments->hasPages())
                <div class="card-footer bg-white border-top py-2 px-4 eq-pagination-bar d-flex justify-content-between align-items-center">
                    <span class="text-muted small">
                        {{ __('locale.results_sur', ['first' => $equipments->firstItem() ?? 0, 'last' => $equipments->lastItem() ?? 0, 'total' => $equipments->total()]) }}
                    </span>
                    <div>
                        {{ $equipments->links() }}
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
