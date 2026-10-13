@extends('layouts.app')

@php
    $calculatedComboPublicPrice = 0;
    $calculatedComboCostPrice = 0;
    $isPublicPriceForced = false;
    $isCostPriceForced = false;

    if ($equipment->is_combo && $equipment->comboItems->isNotEmpty()) {
        $calculatedComboPublicPrice = (float) $equipment->comboItems->sum(function($item) {
            $unitPrice = (float) ($item->extra_price > 0 ? $item->extra_price : ($item->childEquipment->price_per_unit ?? $item->childEquipment->price_excl_tax ?? 0));
            return $unitPrice * (int) ($item->quantity ?? 1);
        });

        $calculatedComboCostPrice = (float) $equipment->comboItems->sum(function($item) {
            $unitCost = (float) ($item->childEquipment->purchase_price ?? 0);
            return $unitCost * (int) ($item->quantity ?? 1);
        });

        $isPublicPriceForced = abs((float)$equipment->price_per_unit - $calculatedComboPublicPrice) > 0.001;
        $isCostPriceForced = abs((float)($equipment->purchase_price ?? 0) - $calculatedComboCostPrice) > 0.001;
    }

    $calculatedKitPublicPrice = 0;
    $calculatedKitCostPrice = 0;
    if ($equipment->is_kit && $equipment->kitItems->isNotEmpty()) {
        $calculatedKitPublicPrice = (float) $equipment->kitItems->sum(function($item) {
            $child = $item->childEquipment;
            $unitPrice = (float) ($child ? ($child->price_per_unit ?? $child->price_excl_tax ?? 0) : 0);
            return $unitPrice * (float) ($item->quantity ?? 1);
        });

        $calculatedKitCostPrice = (float) $equipment->kitItems->sum(function($item) {
            $child = $item->childEquipment;
            $unitCost = (float) ($item->cost_price > 0 ? $item->cost_price : ($child->purchase_price ?? 0));
            return $unitCost * (float) ($item->quantity ?? 1);
        });
    }

    $isDoublePrice = (float)($equipment->purchase_price ?? 0) > 0 && abs((float)$equipment->price_per_unit - ((float)$equipment->purchase_price * 2)) < 0.005;

    $canSeeCostPrices = auth()->user()?->canSeeEquipmentCostPrices() ?? false;
    $canSeeSalePrices = auth()->user()?->canSeeEquipmentSalePrices() ?? false;
    $visiblePriceHistories = $equipment->priceHistories->filter(function ($history) use ($canSeeCostPrices, $canSeeSalePrices) {
        if (!$canSeeCostPrices && in_array($history->field_name, ['purchase_price', 'odp_purchase_price', 'ga_pmap', 'ga_pmrp'])) {
            return false;
        }
        if (!$canSeeSalePrices && in_array($history->field_name, ['price_per_unit', 'price_excl_tax', 'price_incl_tax'])) {
            return false;
        }
        return true;
    });
@endphp

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('equipments.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <h1 class="mb-0 fw-bold text-secondary fs-4" title="{{ $equipment->name }}">{{ \Illuminate\Support\Str::limit($equipment->name, 75) }}</h1>
    </div>
@endsection

@section('page-actions')
    @if (Auth::user()->is_admin)
        <div class="dropdown d-inline-block me-1">
            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                    id="syncOdooDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-arrow-repeat me-1"></i> {{ __('locale.equipment_show.btn_sync_odoo') }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border" aria-labelledby="syncOdooDropdown">
                <li>
                    <form action="{{ route('equipments.sync-odoo', $equipment->id) }}" method="POST" class="d-inline"
                          onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').innerHTML='<i class=\'bi bi-arrow-repeat spin me-1\'></i> {{ __('locale.equipment_show.btn_syncing') }}';">
                        @csrf
                        <input type="hidden" name="sync_inventory" value="1">
                        <button type="submit" class="dropdown-item small">
                            <i class="bi bi-arrow-repeat me-2 text-secondary"></i> {{ __('locale.equipment_show.btn_sync_with_inventory') }}
                        </button>
                    </form>
                </li>
                <li>
                    <form action="{{ route('equipments.sync-odoo', $equipment->id) }}" method="POST" class="d-inline"
                          onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').innerHTML='<i class=\'bi bi-arrow-repeat spin me-1\'></i> {{ __('locale.equipment_show.btn_syncing') }}';">
                        @csrf
                        <input type="hidden" name="sync_inventory" value="0">
                        <button type="submit" class="dropdown-item small">
                            <i class="bi bi-arrow-repeat me-2 text-secondary"></i> {{ __('locale.equipment_show.btn_sync_without_inventory') }}
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    @endif
    <a href="{{ route('equipments.edit', $equipment->id) }}" class="btn btn-sm btn-warning">
        <i class="bi bi-pencil-square me-1"></i> {{ __('locale.lbl_edit') }}
    </a>
@endsection

@push('styles')
<style>
    .content-wrapper {
        min-height: calc(100vh - 75px);
        display: flex;
        flex-direction: column;
        overflow-y: auto;
    }
    .container-xxl {
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
        padding-top: 0.25rem;
        padding-bottom: 0.25rem;
        min-height: 0;
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
        min-height: 580px;
    }
    #equipmentTabContent,
    .tab-content {
        flex: 1 1 auto;
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: 0.25rem;
        scrollbar-width: none !important;
        -ms-overflow-style: none !important;
    }
    #equipmentTabContent::-webkit-scrollbar,
    .tab-content::-webkit-scrollbar {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
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
    .odoo-nav-tabs .nav-item.d-none {
        display: none !important;
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
    /* Odoo Inline Table Textbox Style */
    .odoo-inline-input {
        border: none !important;
        border-bottom: 1.5px solid #714B67 !important;
        background-color: rgba(113, 75, 103, 0.03) !important;
        border-radius: 0 !important;
        font-size: 0.78rem !important;
        padding: 0.15rem 0.3rem !important;
        height: 26px !important;
        color: #1e293b !important;
        box-shadow: none !important;
        transition: all 0.15s ease-in-out;
    }
    .odoo-inline-input:hover {
        background-color: rgba(113, 75, 103, 0.08) !important;
        border-bottom-color: #5d3d55 !important;
    }
    .odoo-inline-input:focus {
        background-color: #ffffff !important;
        border-bottom: 2px solid #714B67 !important;
        outline: none !important;
        box-shadow: 0 1px 3px rgba(113, 75, 103, 0.2) !important;
    }
    /* Select2 Odoo Theme for inline row */
    .select2-container--odoo-inline .select2-selection--single {
        border: none !important;
        border-bottom: 1.5px solid #714B67 !important;
        background-color: rgba(113, 75, 103, 0.03) !important;
        border-radius: 0 !important;
        height: 26px !important;
        line-height: 24px !important;
    }
    .select2-container--odoo-inline .select2-selection--single:hover {
        background-color: rgba(113, 75, 103, 0.08) !important;
    }
    .select2-container--odoo-inline .select2-selection--single .select2-selection__rendered {
        line-height: 24px !important;
        font-size: 0.78rem !important;
        padding-left: 4px !important;
        color: #1e293b !important;
    }
    .select2-container--odoo-inline .select2-selection--single .select2-selection__arrow {
        height: 24px !important;
    }
    /* Compact Table Layout to Fit Single Viewport */
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
            <!-- Header / Title & Image -->
            <div class="row align-items-start mb-2">
                <div class="col">
                    <div class="odoo-top-label mb-1">{{ __('locale.equipment_show.lbl_product') }}</div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-star-fill text-warning fs-5"></i>
                        <span class="odoo-title-display" title="{{ $equipment->name }}">{{ \Illuminate\Support\Str::limit($equipment->name, 65) }}</span>
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <div class="d-flex align-items-center gap-1 small">
                            <i class="bi {{ $equipment->sale_ok ? 'bi-check-circle-fill text-success' : 'bi-circle text-muted' }}"></i>
                            <span class="fw-semibold">{{ __('locale.equipment_show.lbl_sale_ok') }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-1 small">
                            <i class="bi {{ $equipment->purchase_ok ? 'bi-check-circle-fill text-secondary' : 'bi-circle text-muted' }}"></i>
                            <span class="fw-semibold">{{ __('locale.equipment_show.lbl_purchase_ok') }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-1 small">
                            <i class="bi {{ $equipment->recurring_invoice ? 'bi-check-circle-fill text-info' : 'bi-circle text-muted' }}"></i>
                            <span class="fw-semibold">{{ __('locale.equipment_show.lbl_recurring_invoice') }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-1 small">
                            <i class="bi {{ $equipment->is_combo ? 'bi-check-circle-fill text-primary' : 'bi-circle text-muted' }}"></i>
                            <span class="fw-semibold">{{ __('locale.combo.is_combo') ?? 'Produit Combo' }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-1 small">
                            <i class="bi {{ $equipment->is_kit ? 'bi-check-circle-fill text-success' : 'bi-circle text-muted' }}"></i>
                            <span class="fw-semibold">{{ __('locale.kit.is_kit') ?? 'Kit de composants' }}</span>
                            @if($equipment->is_kit)
                                @if($equipment->is_kit_price_manual)
                                    <span class="badge bg-warning text-dark ms-1" style="font-size: 0.72rem;">{{ __('locale.kit.badge_price_manual') ?? 'Prix manuel' }}</span>
                                @else
                                    <span class="badge bg-info-subtle text-info border border-info-subtle ms-1" style="font-size: 0.72rem;">{{ __('locale.kit.badge_price_auto') ?? 'Prix auto' }}</span>
                                @endif
                            @endif
                        </div>
                        @if($equipment->odoo_id)
                            @php
                                $odooProductUrl = config('services.odoo.url')
                                    ? rtrim(config('services.odoo.url'), '/') . '/web#id=' . $equipment->odoo_id . '&model=product.product&view_type=form'
                                    : null;
                            @endphp
                            <div class="d-flex align-items-center gap-1 small">
                                @if($odooProductUrl)
                                    <a href="{{ $odooProductUrl }}" target="_blank" rel="noopener" class="badge bg-secondary-subtle text-secondary border border-secondary-subtle font-monospace text-decoration-none" title="{{ __('locale.open_in_odoo') }}">
                                        <i class="bi bi-hash me-1"></i>{{ __('locale.equipment_show.lbl_odoo_id') }}: {{ $equipment->odoo_id }} <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                    </a>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle font-monospace"><i class="bi bi-hash me-1"></i>{{ __('locale.equipment_show.lbl_odoo_id') }}: {{ $equipment->odoo_id }}</span>
                                @endif
                            </div>
                        @endif
                        @if($equipment->odoo_bom_id)
                            <div class="d-flex align-items-center gap-1 small">
                                <span class="badge bg-success-subtle text-success border border-success-subtle font-monospace" title="{{ __('locale.kit.odoo_bom_id') }}">
                                    <i class="bi bi-diagram-3 me-1"></i>{{ __('locale.kit.odoo_bom_id') }}: {{ $equipment->odoo_bom_id }}
                                </span>
                            </div>
                        @endif
                        @if($equipment->closed)
                            <div class="d-flex align-items-center gap-1 small ms-2 text-danger fw-bold">
                                <i class="bi bi-lock-fill"></i> {{ __('locale.equipment_show.lbl_closed_archived') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-auto">
                    <div class="odoo-img-box">
                        <i class="bi bi-camera fs-3"></i>
                    </div>
                </div>
            </div>

            <!-- Odoo Nav Tabs -->
            <ul class="nav nav-tabs odoo-nav-tabs" id="equipmentTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general-tab-pane" type="button" role="tab">{{ __('locale.equipment_show.lbl_tab_general') }}</button>
                </li>
                @if($equipment->sale_ok)
                <li class="nav-item" role="presentation" id="sales-tab-nav">
                    <button class="nav-link" id="sales-tab" data-bs-toggle="tab" data-bs-target="#sales-tab-pane" type="button" role="tab">{{ __('locale.equipment_show.lbl_tab_sales') }}</button>
                </li>
                @endif
                @if($equipment->recurring_invoice)
                <li class="nav-item" role="presentation" id="subscription-tab-nav">
                    <button class="nav-link" id="subscription-tab" data-bs-toggle="tab" data-bs-target="#subscription-tab-pane" type="button" role="tab">
                        <i class="bi bi-arrow-repeat me-1"></i>{{ __('locale.equipment_show.lbl_tab_subscription') }}
                        @if($recurringItems->isNotEmpty())
                            <span class="badge bg-info-subtle text-info border border-info-subtle ms-1">{{ $recurringItems->count() }}</span>
                        @endif
                    </button>
                </li>
                @endif
                @if($canSeeSalePrices || $canSeeCostPrices)
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pricing-tab" data-bs-toggle="tab" data-bs-target="#pricing-tab-pane" type="button" role="tab">{{ __('locale.equipment_show.lbl_tab_pricing') }}</button>
                </li>
                @endif
                @if($equipment->purchase_ok)
                <li class="nav-item" role="presentation" id="purchase-tab-nav">
                    <button class="nav-link" id="purchase-tab" data-bs-toggle="tab" data-bs-target="#purchase-tab-pane" type="button" role="tab">{{ __('locale.equipment_show.lbl_tab_purchase') }}</button>
                </li>
                @endif
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory-tab-pane" type="button" role="tab">{{ __('locale.equipment_show.lbl_tab_inventory') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="accounting-tab" data-bs-toggle="tab" data-bs-target="#accounting-tab-pane" type="button" role="tab">{{ __('locale.equipment_show.lbl_tab_accounting') }}</button>
                </li>
                @if($equipment->is_combo)
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="combos-tab" data-bs-toggle="tab" data-bs-target="#combos-tab-pane" type="button" role="tab">
                        <i class="bi bi-layers me-1"></i>{{ __('locale.combo.tab_combos') ?? 'Combos' }} (<span id="tabEquipmentComboCount">{{ $equipment->comboItems->count() }}</span>)
                    </button>
                </li>
                @endif
                @if($equipment->is_kit)
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="kit-tab" data-bs-toggle="tab" data-bs-target="#kit-tab-pane" type="button" role="tab">
                        <i class="bi bi-diagram-3 me-1 text-success"></i>{{ __('locale.kit.tab_kit') ?? 'Composants du Kit' }} (<span id="tabEquipmentKitCount">{{ $equipment->kitItems->count() }}</span>)
                    </button>
                </li>
                @endif
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="equipmentTabContent">
                <!-- Tab 1: Informations Générales -->
                <div class="tab-pane fade show active" id="general-tab-pane" role="tabpanel" tabindex="0">
                    <div class="row g-3">
                        <!-- Left Column -->
                        <div class="col-md-6 pe-md-4">
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_reference') }}</div>
                                <div class="odoo-field-value">{{ $equipment->reference ?? '-' }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_product_type') }}</div>
                                <div class="odoo-field-value">{{ $equipment->equipmentType->name ?? '-' }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_invoice_policy') }}</div>
                                <div class="odoo-field-value">{{ $equipment->invoice_policy == 'delivery' ? __('locale.equipment_show.lbl_invoice_policy_delivery') : __('locale.equipment_show.lbl_invoice_policy_order') }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_track_inventory') }}</div>
                                <div class="odoo-field-value">
                                    @if(!in_array(strtoupper((string)($equipment->stock_management ?? 'X')), ['-', '0', 'FALSE', 'N', 'NO']))
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">X</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">-</span>
                                    @endif
                                </div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_available_qty') }}</div>
                                <div class="odoo-field-value text-secondary fw-bold">{{ $equipment->current_stock }} {{ $equipment->unit_of_measure ?? __('locale.equipment_show.lbl_unit_col') }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_equipment_category') }}</div>
                                <div class="odoo-field-value">{{ $equipment->equipmentCategory->name ?? '-' }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_brand') }}</div>
                                <div class="odoo-field-value">{{ $equipment->brand->name ?? '-' }}</div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6 ps-md-4">
                            @if($canSeeSalePrices)
                            <div class="odoo-field-row">
                                <div class="odoo-field-label d-flex align-items-center justify-content-between">
                                    <span>{{ __('locale.equipment_show.lbl_sale_price') }}</span>
                                    @if($isPublicPriceForced)
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle" style="font-size: 0.7rem;" title="{{ __('locale.combo.calculated_price_sum') }}: {{ number_format($calculatedComboPublicPrice, 2, ',', ' ') }} €"><i class="bi bi-pencil-square me-1"></i>{{ __('locale.combo.forced_price_badge') }}</span>
                                    @endif
                                </div>
                                <div class="odoo-field-value fw-bold">
                                    {{ format_number($equipment->price_per_unit, 2) }} {{ __('locale.currency') }}
                                    @if($isDoublePrice)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle ms-1 align-middle" style="font-size: 0.72rem;" title="{{ __('locale.equipment_show.badge_double_price') ?? '2x Prix d\'achat' }}">x2</span>
                                    @endif
                                </div>
                            </div>
                            @endif
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_customer_taxes') }}</div>
                                <div class="odoo-field-value">{{ $equipment->customer_taxes->pluck('name')->implode(', ') ?: '-' }}</div>
                            </div>
                            @if($canSeeCostPrices)
                            <div class="odoo-field-row">
                                <div class="odoo-field-label d-flex align-items-center justify-content-between">
                                    <span>{{ __('locale.equipment_show.lbl_cost') }}</span>
                                    @if($isCostPriceForced)
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle" style="font-size: 0.7rem;" title="{{ __('locale.combo.calculated_price_sum') }}: {{ number_format($calculatedComboCostPrice, 2, ',', ' ') }} €"><i class="bi bi-pencil-square me-1"></i>{{ __('locale.combo.forced_price_badge') }}</span>
                                    @endif
                                </div>
                                <div class="odoo-field-value">{{ format_number($equipment->purchase_price ?? 0, 2) }} {{ __('locale.currency') }}</div>
                            </div>
                            @endif
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_supplier_taxes') }}</div>
                                <div class="odoo-field-value">{{ $equipment->supplier_taxes->pluck('name')->implode(', ') ?: '-' }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_odoo_category') }}</div>
                                <div class="odoo-field-value">{{ $equipment->odooCategory->name ?? '-' }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_odoo_id') }}</div>
                                <div class="odoo-field-value font-monospace fw-semibold">
                                    @if($equipment->odoo_id)
                                        @php
                                            $odooProductUrl = config('services.odoo.url')
                                                ? rtrim(config('services.odoo.url'), '/') . '/web#id=' . $equipment->odoo_id . '&model=product.product&view_type=form'
                                                : null;
                                        @endphp
                                        @if($odooProductUrl)
                                            <a href="{{ $odooProductUrl }}" target="_blank" rel="noopener" class="text-decoration-none" title="{{ __('locale.open_in_odoo') }}">
                                                {{ $equipment->odoo_id }} <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                            </a>
                                        @else
                                            {{ $equipment->odoo_id }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                            
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_barcode') }}</div>
                                <div class="odoo-field-value">{{ $equipment->barcode ?? '-' }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_company') }}</div>
                                <div class="odoo-field-value">{{ $equipment->company->name ?? __('locale.equipment_show.lbl_visible_all') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Ventes -->
                <div class="tab-pane fade" id="sales-tab-pane" role="tabpanel" tabindex="0">
                    <div class="row g-3">
                        <div class="col-md-6 pe-md-4">
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_line_discount') }}</div>
                                <div class="odoo-field-value">{{ $equipment->is_line_discountable ? __('locale.equipment_show.yes') : __('locale.equipment_show.no') }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_footer_discount') }}</div>
                                <div class="odoo-field-value">{{ $equipment->is_footer_discountable ? __('locale.equipment_show.yes') : __('locale.equipment_show.no') }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_discountable') }}</div>
                                <div class="odoo-field-value">{{ $equipment->is_discountable ? __('locale.equipment_show.yes') : __('locale.equipment_show.no') }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_modifiable') }}</div>
                                <div class="odoo-field-value">{{ $equipment->modifiable ? __('locale.equipment_show.yes') : __('locale.equipment_show.no') }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_discount_rate') }}</div>
                                <div class="odoo-field-value">{{ $equipment->discount !== null && $equipment->discount !== '' ? format_number($equipment->discount, 2) . '%' : '-' }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_customer_lead') }}</div>
                                <div class="odoo-field-value">{{ $equipment->customer_lead ? $equipment->customer_lead . ' ' . __('locale.equipment_show.lbl_days') : '-' }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_recurring_invoice') }}</div>
                                <div class="odoo-field-value">
                                    @if($equipment->recurring_invoice)
                                        <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1">{{ __('locale.equipment_show.yes') }}</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">{{ __('locale.equipment_show.no') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 ps-md-4">
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_suggested_substitutes') }}</div>
                                <div class="odoo-field-value">{{ $equipment->substitutes->pluck('name')->implode(', ') ?: '-' }}</div>
                            </div>
                            <div class="odoo-field-row align-items-start">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_sale_description') }}</div>
                                <div class="odoo-field-value text-muted italic">{{ $equipment->description_sale ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab: Tarifs récurrents -->
                <div class="tab-pane fade" id="subscription-tab-pane" role="tabpanel" tabindex="0">
                    <div class="table-responsive mb-4">
                        <table class="table table-sm table-hover align-middle mb-0 odoo-inline-table">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 22%;">{{ __('locale.equipment_show.lbl_recurring_plan_col') }}</th>
                                    <th style="width: 22%;">{{ __('locale.equipment_show.lbl_recurring_pricelist_col') }}</th>
                                    @if($canSeeSalePrices)
                                    <th style="width: 16%;">{{ __('locale.equipment_show.lbl_recurring_price_col') }}</th>
                                    @endif
                                    <th style="width: 14%;">{{ __('locale.equipment_show.lbl_recurring_date_start_col') }}</th>
                                    <th style="width: 14%;">{{ __('locale.equipment_show.lbl_recurring_date_end_col') }}</th>
                                    <th style="width: 12%;">{{ __('locale.equipment_show.lbl_recurring_company_col') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recurringItems as $item)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-semibold px-2 py-1">
                                                <i class="bi bi-arrow-repeat me-1"></i>{{ $item->recurringPlan?->name ?? $item->plan_name }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($item->pricelist)
                                                <a href="{{ route('pricelists.show', $item->pricelist_id) }}" class="text-decoration-none fw-semibold small text-primary">
                                                    {{ $item->pricelist->name }}
                                                </a>
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>
                                        @if($canSeeSalePrices)
                                        <td class="fw-bold text-dark">
                                            {{ format_number($item->fixed_price ?? 0, 2) }} {{ __('locale.currency') }}
                                        </td>
                                        @endif
                                        <td class="text-muted small">
                                            {{ $item->date_start ? \Carbon\Carbon::parse($item->date_start)->format('d/m/Y') : '—' }}
                                        </td>
                                        <td class="text-muted small">
                                            {{ $item->date_end ? \Carbon\Carbon::parse($item->date_end)->format('d/m/Y') : '—' }}
                                        </td>
                                        <td>
                                            @if($item->company)
                                                <span class="badge bg-secondary-subtle text-secondary small">
                                                    {{ $item->company->name }}
                                                </span>
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $canSeeSalePrices ? 6 : 5 }}" class="text-center py-4 text-muted">
                                            <i class="bi bi-arrow-repeat fs-3 d-block mb-2 text-secondary opacity-50"></i>
                                            {{ __('locale.equipment_show.lbl_no_recurring_items') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- OPTIONS Section (matches Odoo) -->
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="text-uppercase fw-bold text-secondary mb-3 small" style="letter-spacing: 0.5px;">
                            OPTIONS
                        </h6>
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-semibold text-dark small">{{ __('locale.equipment_show.lbl_allow_one_time_sale_q') }}</span>
                            @if($equipment->allow_one_time_sale)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                    <i class="bi bi-check-circle-fill me-1"></i>{{ __('locale.equipment_show.yes') }}
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">
                                    <i class="bi bi-circle me-1"></i>{{ __('locale.equipment_show.no') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Tab 3: Prix -->
                @if($canSeeSalePrices || $canSeeCostPrices)
                <div class="tab-pane fade" id="pricing-tab-pane" role="tabpanel" tabindex="0">
                    <div class="row g-3">
                        @if($canSeeSalePrices)
                        <div class="{{ $canSeeCostPrices ? 'col-md-6 pe-md-4' : 'col-md-12' }}">
                            <div class="odoo-field-row">
                                <div class="odoo-field-label d-flex align-items-center justify-content-between">
                                    <span>{{ __('locale.equipment_show.lbl_sale_price_excl_tax') }}</span>
                                    @if($isPublicPriceForced)
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle" style="font-size: 0.7rem;" title="{{ __('locale.combo.calculated_price_sum') }}: {{ number_format($calculatedComboPublicPrice, 2, ',', ' ') }} €"><i class="bi bi-pencil-square me-1"></i>{{ __('locale.combo.forced_price_badge') }}</span>
                                    @endif
                                </div>
                                <div class="odoo-field-value fw-bold text-secondary">
                                    {{ format_number($equipment->price_per_unit, 2) }} {{ __('locale.currency') }}
                                    @if($isDoublePrice)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle ms-1 align-middle" style="font-size: 0.72rem;" title="{{ __('locale.equipment_show.badge_double_price') ?? '2x Prix d\'achat' }}">x2</span>
                                    @endif
                                </div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_recommended_sale_price') }}</div>
                                <div class="odoo-field-value">{{ $equipment->price_excl_tax !== null && $equipment->price_excl_tax !== '' ? format_number($equipment->price_excl_tax, 2) . ' ' . __('locale.currency') : '-' }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_estimated_incl_tax') }}</div>
                                <div class="odoo-field-value">{{ $equipment->price_incl_tax !== null && $equipment->price_incl_tax !== '' ? format_number($equipment->price_incl_tax, 2) . ' ' . __('locale.currency') : '-' }}</div>
                            </div>
                        </div>
                        @endif
                        @if($canSeeCostPrices)
                        <div class="{{ $canSeeSalePrices ? 'col-md-6 ps-md-4' : 'col-md-12' }}">
                            <div class="odoo-field-row">
                                <div class="odoo-field-label d-flex align-items-center justify-content-between">
                                    <span>{{ __('locale.equipment_show.lbl_purchase_cost') }}</span>
                                    @if($isCostPriceForced)
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle" style="font-size: 0.7rem;" title="{{ __('locale.combo.calculated_price_sum') }}: {{ number_format($calculatedComboCostPrice, 2, ',', ' ') }} €"><i class="bi bi-pencil-square me-1"></i>{{ __('locale.combo.forced_price_badge') }}</span>
                                    @endif
                                </div>
                                <div class="odoo-field-value">{{ format_number($equipment->purchase_price ?? 0, 2) }} {{ __('locale.currency') }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_odp_purchase_price') }}</div>
                                <div class="odoo-field-value">{{ $equipment->odp_purchase_price !== null && $equipment->odp_purchase_price !== '' ? format_number($equipment->odp_purchase_price, 2) . ' ' . __('locale.currency') : '-' }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_ga_pmap') }}</div>
                                <div class="odoo-field-value">{{ $equipment->ga_pmap !== null ? format_number($equipment->ga_pmap, 2) . ' ' . __('locale.currency') : '-' }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_ga_pmrp') }}</div>
                                <div class="odoo-field-value">{{ $equipment->ga_pmrp !== null ? format_number($equipment->ga_pmrp, 2) . ' ' . __('locale.currency') : '-' }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Tab 4: Achats -->
                <div class="tab-pane fade" id="purchase-tab-pane" role="tabpanel" tabindex="0">
                    @can('add-equipment-supplier')
                        <form id="inline-add-supplier-form" action="{{ route('equipments.add_supplier', $equipment->id) }}" method="POST">
                            @csrf
                        </form>
                    @endcan

                    <div class="table-responsive mb-2">
                        <table class="table table-sm table-hover align-middle mb-0 odoo-inline-table">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 18%;">{{ __('locale.equipment_show.lbl_supplier_col') }}</th>
                                    <th style="width: 16%;">{{ __('locale.equipment_show.lbl_product_name_col') }}</th>
                                    <th style="width: 14%;">{{ __('locale.equipment_show.lbl_product_code_col') }}</th>
                                    <th style="width: 8%;">{{ __('locale.equipment_show.lbl_date_start_col') }}</th>
                                    <th style="width: 8%;">{{ __('locale.equipment_show.lbl_date_end_col') }}</th>
                                    <th style="width: 8%;">{{ __('locale.equipment_show.lbl_company_col') }}</th>
                                    <th style="width: 5%;">{{ __('locale.equipment_show.lbl_qty_col') }}</th>
                                    <th style="width: 6%;">{{ __('locale.equipment_show.lbl_unit_col') }}</th>
                                    @if($canSeeCostPrices)
                                    <th style="width: 8%;">{{ __('locale.equipment_show.lbl_unit_price_col') }}</th>
                                    @endif
                                    <th style="width: 4%;">{{ __('locale.equipment_show.lbl_discount_col') }}</th>
                                    <th style="width: 4%;">{{ __('locale.equipment_show.lbl_currency_col') }}</th>
                                    <th style="width: 4%;">{{ __('locale.equipment_show.lbl_delay_col') }}</th>
                                    <th style="width: 7%; text-align: center;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($equipment->suppliers as $sup)
                                    <tr>
                                        <td class="fw-semibold">{{ $sup->name }}</td>
                                        <td>{{ $sup->pivot->product_name ?? $equipment->name }}</td>
                                        <td>{{ $sup->pivot->supplier_reference ?? '-' }}</td>
                                        <td>{{ $sup->pivot->date_start ? \Carbon\Carbon::parse($sup->pivot->date_start)->format('d/m/Y') : '-' }}</td>
                                        <td>{{ $sup->pivot->date_end ? \Carbon\Carbon::parse($sup->pivot->date_end)->format('d/m/Y') : '-' }}</td>
                                        <td>{{ $companies->firstWhere('id', $sup->pivot->company_id)->name ?? $equipment->company->name ?? 'NOVALYO' }}</td>
                                        <td>{{ format_number($sup->pivot->min_qty ?? 1, 2) }}</td>
                                        <td>{{ $sup->pivot->product_uom ?? $equipment->purchase_unit_of_measure ?? __('locale.equipment_show.lbl_unit_col') }}</td>
                                        @if($canSeeCostPrices)
                                        <td>{{ format_number($sup->pivot->price ?? $equipment->purchase_price ?? 0, 2) }}</td>
                                        @endif
                                        <td>{{ format_number($sup->pivot->discount ?? 0, 2) }}</td>
                                        <td>{{ $sup->pivot->currency ?? 'EUR' }}</td>
                                        <td>{{ $sup->pivot->delay ?? 1 }}</td>
                                        <td class="text-center">
                                            @can('delete-equipment-supplier')
                                                <form action="{{ route('equipments.remove_supplier', [$equipment->id, $sup->id, $sup->pivot->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('locale.delete_confirmation') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0 border-0" title="{{ __('locale.delete') }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="empty-suppliers-row">
                                        <td colspan="{{ $canSeeCostPrices ? 13 : 12 }}" class="text-muted text-center py-3">{{ __('locale.equipment_show.lbl_no_supplier_rates') }}</td>
                                    </tr>
                                @endforelse

                                @can('add-equipment-supplier')
                                    <tr id="inline-add-supplier-row" style="display: none;" class="table-light border-top border-primary">
                                        <td>
                                            <select form="inline-add-supplier-form" name="supplier_id" id="inline_supplier_id" class="form-select form-select-sm odoo-inline-input" required>
                                                <option value="">{{ __('locale.equipment_show.lbl_select_placeholder') }}</option>
                                                @foreach($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}{{ $supplier->company ? ' (' . $supplier->company->name . ')' : '' }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input form="inline-add-supplier-form" type="text" name="product_name" class="form-control form-control-sm odoo-inline-input" value="{{ $equipment->name }}"></td>
                                        <td><input form="inline-add-supplier-form" type="text" name="supplier_reference" class="form-control form-control-sm odoo-inline-input" placeholder="{{ __('locale.equipment_show.lbl_product_code_placeholder') }}" required></td>
                                        <td><input form="inline-add-supplier-form" type="date" name="date_start" class="form-control form-control-sm odoo-inline-input"></td>
                                        <td><input form="inline-add-supplier-form" type="date" name="date_end" class="form-control form-control-sm odoo-inline-input"></td>
                                        <td>
                                            <select form="inline-add-supplier-form" name="company_id" class="form-select form-select-sm odoo-inline-input">
                                                <option value="">{{ __('locale.equipment_show.lbl_visible_all_option') }}</option>
                                                @foreach($companies as $company)
                                                    <option value="{{ $company->id }}" {{ ($equipment->company_id ?? '') == $company->id ? 'selected' : '' }}>
                                                        {{ $company->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input form="inline-add-supplier-form" type="number" step="0.01" name="min_qty" class="form-control form-control-sm odoo-inline-input" value="1.00"></td>
                                        <td><input form="inline-add-supplier-form" type="text" name="product_uom" class="form-control form-control-sm odoo-inline-input" value="{{ $equipment->purchase_unit_of_measure ?? __('locale.equipment_show.lbl_unit_col') }}"></td>
                                        @if($canSeeCostPrices)
                                        <td><input form="inline-add-supplier-form" type="number" step="0.01" name="price" class="form-control form-control-sm odoo-inline-input" value="{{ number_format($equipment->purchase_price ?? 0, 2, '.', '') }}"></td>
                                        @else
                                        <input form="inline-add-supplier-form" type="hidden" name="price" value="{{ number_format($equipment->purchase_price ?? 0, 2, '.', '') }}">
                                        @endif
                                        <td><input form="inline-add-supplier-form" type="number" step="0.01" name="discount" class="form-control form-control-sm odoo-inline-input" value="0.00"></td>
                                        <td><input form="inline-add-supplier-form" type="text" name="currency" class="form-control form-control-sm odoo-inline-input" value="EUR"></td>
                                        <td><input form="inline-add-supplier-form" type="number" name="delay" class="form-control form-control-sm odoo-inline-input" value="1"></td>
                                        <td class="text-center align-middle">
                                            <button form="inline-add-supplier-form" type="submit" class="btn btn-sm btn-success p-0 px-1 border-0" title="{{ __('locale.equipment_show.btn_save') }}">
                                                <i class="bi bi-check-lg fs-6"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-1 border-0" id="cancel-inline-add" title="{{ __('locale.equipment_show.btn_cancel') }}">
                                                <i class="bi bi-x-lg fs-6"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endcan
                            </tbody>
                        </table>
                    </div>

                    @can('add-equipment-supplier')
                        <div class="mb-4">
                            <a href="javascript:void(0)" class="text-success text-decoration-none fw-semibold small" id="btn-show-inline-add">
                                {{ __('locale.equipment_show.lbl_add_line') }}
                            </a>
                        </div>
                    @endcan

                    <hr class="my-4" style="opacity: 0.15;">

                    <div class="row g-4">
                        <!-- Column 1: FACTURES FOURNISSEURS -->
                        <div class="col-md-6 pe-md-4">
                            <h6 class="fw-bold text-uppercase border-bottom pb-2 text-secondary mb-3 small" style="letter-spacing: 0.5px;">{{ __('locale.equipment_show.lbl_supplier_invoices') }}</h6>
                            
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">
                                    {{ __('locale.equipment_show.lbl_control_policy') }} <i class="bi bi-question-circle text-muted ms-1 small" data-bs-toggle="tooltip" title="{{ __('locale.equipment_show.lbl_control_policy') }}"></i>
                                </div>
                                <div class="odoo-field-value">
                                    @if(($equipment->purchase_control_policy ?? 'receive') == 'order')
                                        {{ __('locale.equipment_show.lbl_control_on_order') }}
                                    @else
                                        {{ __('locale.equipment_show.lbl_control_on_received') }}
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Column 2: DESCRIPTION DES ACHATS & AVERTISSEMENT -->
                        <div class="col-md-6 ps-md-4">
                            <h6 class="fw-bold text-uppercase border-bottom pb-2 text-secondary mb-3 small" style="letter-spacing: 0.5px;">{{ __('locale.equipment_show.lbl_purchase_description') }}</h6>
                            <div class="mb-4">
                                <div class="odoo-field-value text-muted italic">{{ $equipment->description_purchase ?? __('locale.equipment_show.lbl_no_purchase_description') }}</div>
                                <div class="form-text text-muted small mt-1">{{ __('locale.equipment_show.lbl_purchase_description_note') }}</div>
                            </div>

                            <h6 class="fw-bold text-uppercase border-bottom pb-2 text-secondary mb-3 small" style="letter-spacing: 0.5px;">{{ __('locale.equipment_show.lbl_purchase_warning') }}</h6>
                            <div>
                                <div class="odoo-field-value text-muted italic">{{ $equipment->purchase_warning ?? __('locale.equipment_show.lbl_purchase_warning_example') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 5: Inventaire -->
                <div class="tab-pane fade" id="inventory-tab-pane" role="tabpanel" tabindex="0">
                    <!-- Warehouse Stock Levels Table -->
                    <div class="card border shadow-sm mb-4">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-boxes text-success fs-5"></i>
                                <span class="fw-bold text-dark fs-6">{{ __('locale.stock_by_warehouse') ?? 'Niveaux de stock par entrepôt' }}</span>
                            </div>
                            @can('inventory-access')
                                <a href="{{ route('equipments.inventory.count', $equipment) }}" class="btn btn-sm btn-primary shadow-sm rounded-pill px-3 py-1">
                                    <i class="bi bi-pencil-square me-1"></i> {{ __('locale.btn_count') ?? 'Ajuster l\'inventaire' }}
                                </a>
                            @endcan
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-muted small">
                                    <tr>
                                        <th class="ps-4">{{ __('locale.warehouse') ?? 'Entrepôt' }}</th>
                                        <th class="text-center">{{ __('locale.lbl_on_hand_qty') ?? 'En main' }}</th>
                                        <th class="text-center">{{ __('locale.lbl_reserved_qty') ?? 'Réservé' }}</th>
                                        <th class="text-center">{{ __('locale.lbl_available_qty') ?? 'Disponible' }}</th>
                                        <th class="text-center">{{ __('locale.lbl_last_count_date') ?? 'Dernier inventaire' }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($equipment->warehouses as $wh)
                                        @php
                                            $whQty = (float) $wh->pivot->quantity;
                                            $whRes = (float) $wh->pivot->reserved_quantity;
                                            $whAvail = max(0.0, $whQty - $whRes);
                                            $whDate = $wh->pivot->last_inventory_date ? \Carbon\Carbon::parse($wh->pivot->last_inventory_date)->format('d/m/Y H:i') : '—';
                                        @endphp
                                        <tr>
                                            <td class="ps-4 fw-semibold text-dark">
                                                <i class="bi bi-building me-1 text-primary"></i> {{ $wh->name }} ({{ $wh->code }})
                                            </td>
                                            <td class="text-center font-monospace fw-bold text-primary fs-6">{{ $whQty }}</td>
                                            <td class="text-center font-monospace text-warning fs-6">{{ $whRes }}</td>
                                            <td class="text-center font-monospace fw-bold text-success fs-6">{{ $whAvail }}</td>
                                            <td class="text-center text-muted small">{{ $whDate }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">
                                                {{ __('locale.no_warehouse_assigned') ?? 'Aucun stock enregistré pour cet article.' }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6 pe-md-4">
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_lot_tracking') }}</div>
                                <div class="odoo-field-value">{{ $equipment->lot_management ?? __('locale.equipment_show.lbl_no_tracking') }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_minimum_qty') }}</div>
                                <div class="odoo-field-value">{{ $equipment->minimum_quantity ?? 0 }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_packaging') }}</div>
                                <div class="odoo-field-value">{{ $equipment->packaging ?? '-' }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_pcb') }}</div>
                                <div class="odoo-field-value">{{ $equipment->pcb ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 ps-md-4">
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_weight_kg') }}</div>
                                <div class="odoo-field-value">{{ $equipment->weight ? $equipment->weight . ' kg' : '-' }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_volume_m3') }}</div>
                                <div class="odoo-field-value">{{ $equipment->volume ? $equipment->volume . ' m³' : '-' }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_customs_code') }}</div>
                                <div class="odoo-field-value">{{ $equipment->customs_code ?? '-' }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_country_of_origin') }}</div>
                                <div class="odoo-field-value">{{ $equipment->country_of_origin ?? '-' }}</div>
                            </div>
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_warranty') }}</div>
                                <div class="odoo-field-value">{{ $equipment->warranty ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 6: Comptabilité -->
                <div class="tab-pane fade" id="accounting-tab-pane" role="tabpanel" tabindex="0">
                    <div class="row g-3">
                        <div class="col-md-6 pe-md-4">
                            <div class="odoo-field-row">
                                <div class="odoo-field-label">{{ __('locale.equipment_show.lbl_accounting_family') }}</div>
                                <div class="odoo-field-value">{{ $equipment->accountingFamily?->family_name ?? '-' }} {{ $equipment->accountingFamily?->code_cegid ? '(' . $equipment->accountingFamily->code_cegid . ')' : '' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($equipment->is_combo)
                <!-- Tab 7: Combos -->
                <div class="tab-pane fade" id="combos-tab-pane" role="tabpanel" tabindex="0">
                    <div class="row g-3">
                        <div class="col-12">
                            @if($equipment->comboItems->isNotEmpty())
                                <div class="card border shadow-sm mb-4">
                                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-box-seam text-primary fs-5"></i>
                                            <span class="fw-bold text-dark fs-6">{{ __('locale.combo.direct_combo_items_title', ['count' => $equipment->comboItems->count()]) }}</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-3">
                                            @if($canSeeSalePrices)
                                                <span class="text-muted small">{{ __('locale.combo.total_sale_price') }}: <strong class="text-dark">{{ number_format($calculatedComboPublicPrice, 2, ',', ' ') }} €</strong></span>
                                            @endif
                                            @if($canSeeCostPrices)
                                                <span class="text-muted small">{{ __('locale.combo.total_cost') }}: <strong class="text-dark">{{ number_format($calculatedComboCostPrice, 2, ',', ' ') }} €</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr class="text-muted small">
                                                    <th class="ps-4" style="width: 50px;">#</th>
                                                    <th>{{ __('locale.lbl_equipment') }}</th>
                                                    <th>{{ __('locale.reference') ?? 'Référence' }}</th>
                                                    <th class="text-center" style="width: 90px;">{{ __('locale.quantity') ?? 'Quantité' }}</th>
                                                    @if($canSeeSalePrices)
                                                        <th class="text-end">{{ __('locale.combo.unit_sale_price') }}</th>
                                                    @endif
                                                    @if($canSeeCostPrices)
                                                        <th class="text-end">{{ __('locale.combo.unit_cost') }}</th>
                                                    @endif
                                                    @if($canSeeSalePrices)
                                                        <th class="text-end pe-4">{{ __('locale.combo.total_sale_price') }}</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($equipment->comboItems as $idx => $cItem)
                                                    @php
                                                        $child = $cItem->childEquipment;
                                                        $uPrice = (float) ($cItem->extra_price > 0 ? $cItem->extra_price : ($child->price_per_unit ?? $child->price_excl_tax ?? 0));
                                                        $uCost = (float) ($child->purchase_price ?? 0);
                                                        $q = (int) ($cItem->quantity ?? 1);
                                                    @endphp
                                                    <tr>
                                                        <td class="ps-4 text-muted small font-monospace">{{ $idx + 1 }}</td>
                                                        <td>
                                                            @if($child)
                                                                <a href="{{ route('equipments.show', $child->id) }}" class="fw-semibold text-dark text-decoration-none">
                                                                    {{ $child->name }}
                                                                </a>
                                                            @else
                                                                <span class="text-muted">{{ __('locale.combo.item_not_found') }}</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-light text-secondary border font-monospace">
                                                                {{ $child->reference ?? '-' }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge bg-secondary-subtle text-dark border">{{ $q }}</span>
                                                        </td>
                                                        @if($canSeeSalePrices)
                                                        <td class="text-end fw-semibold text-muted">
                                                            {{ number_format($uPrice, 2, ',', ' ') }} €
                                                        </td>
                                                        @endif
                                                        @if($canSeeCostPrices)
                                                        <td class="text-end text-muted">
                                                            {{ number_format($uCost, 2, ',', ' ') }} €
                                                        </td>
                                                        @endif
                                                        @if($canSeeSalePrices)
                                                        <td class="text-end pe-4 fw-bold text-dark">
                                                            {{ number_format($uPrice * $q, 2, ',', ' ') }} €
                                                        </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            @if($equipment->comboItems->isEmpty())
                                <div class="text-center py-5 text-muted border border-dashed rounded-3 bg-light">
                                    <i class="bi bi-layers fs-1 d-block mb-2 text-secondary"></i>
                                    <p class="fw-semibold text-dark mb-1">{{ __('locale.combo.no_combos_associated') }}</p>
                                    <p class="small text-muted mb-3">{{ __('locale.combo.no_combos_associated_desc') }}</p>
                                    <a href="{{ route('equipments.edit', $equipment->id) }}#combos-tab" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil me-1"></i>{{ __('locale.combo.associate_combos') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                @if($equipment->is_kit)
                <!-- Tab: Composants du Kit -->
                <div class="tab-pane fade" id="kit-tab-pane" role="tabpanel" tabindex="0">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="alert alert-info d-flex align-items-center mb-3">
                                <i class="bi bi-info-circle-fill fs-5 me-2 flex-shrink-0"></i>
                                <div>
                                    {{ __('locale.kit.show_explanation') ?? "Ce produit est un kit (Nomenclature Odoo 'Fantôme'). Il n'a pas de stock physique propre : son stock dépend des composants ci-dessous et lors de la livraison, ses composants sont prélevés individuellement." }}
                                </div>
                            </div>

                            @if($equipment->kitItems->isNotEmpty())
                                <div class="card border rounded-3 overflow-hidden shadow-none mb-3">
                                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-diagram-3 text-success fs-5"></i>
                                            <span class="fw-bold text-dark fs-6">{{ __('locale.kit.components_count', ['count' => $equipment->kitItems->count()]) ?? 'Composants du kit (' . $equipment->kitItems->count() . ')' }}</span>
                                            @if($equipment->is_kit_price_manual)
                                                <span class="badge bg-warning text-dark border-0 px-2 py-1 fs-12 fw-bold" title="{{ __('locale.kit.manual_price_tooltip') ?? 'Le prix de ce kit a été défini manuellement' }}">
                                                    <i class="bi bi-pencil me-1"></i>{{ __('locale.kit.badge_price_manual') ?? 'Prix manuel' }}
                                                </span>
                                            @else
                                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 fs-12 fw-bold" title="{{ __('locale.kit.auto_price_tooltip') ?? 'Le prix de ce kit est synchronisé automatiquement avec ses composants' }}">
                                                    <i class="bi bi-magic me-1"></i>{{ __('locale.kit.badge_price_auto') ?? 'Prix auto' }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="d-flex align-items-center gap-3">
                                            @if($canSeeSalePrices)
                                                <span class="text-muted small">{{ __('locale.kit.total_calculated_sale_price') ?? 'Total prix vente comp.' }}: <strong class="text-dark">{{ number_format($calculatedKitPublicPrice, 2, ',', ' ') }} €</strong></span>
                                            @endif
                                            @if($canSeeCostPrices)
                                                <span class="text-muted small">{{ __('locale.kit.total_calculated_cost') ?? 'Total coût comp.' }}: <strong class="text-dark">{{ number_format($calculatedKitCostPrice, 2, ',', ' ') }} €</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr class="text-muted small">
                                                    <th class="ps-4" style="width: 50px;">#</th>
                                                    <th>{{ __('locale.lbl_equipment') }}</th>
                                                    <th>{{ __('locale.reference') ?? 'Référence' }}</th>
                                                    <th class="text-center" style="width: 100px;">{{ __('locale.quantity') ?? 'Quantité' }}</th>
                                                    <th class="text-center" style="width: 120px;">{{ __('locale.stock') ?? 'Stock Actuel' }}</th>
                                                    @if($canSeeCostPrices)
                                                        <th class="text-end">{{ __('locale.kit.unit_cost') ?? 'Coût unitaire' }}</th>
                                                    @endif
                                                    @if($canSeeSalePrices)
                                                        <th class="text-end">{{ __('locale.kit.unit_sale_price') ?? 'Prix vente unitaire' }}</th>
                                                    @endif
                                                    @if($canSeeCostPrices || $canSeeSalePrices)
                                                        <th class="text-end pe-4">{{ $canSeeCostPrices ? (__('locale.kit.total_cost') ?? 'Coût total') : (__('locale.total_price') ?? 'Prix total') }}</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($equipment->kitItems as $idx => $kItem)
                                                    @php
                                                        $child = $kItem->childEquipment;
                                                        $uPrice = (float) ($child ? ($child->price_per_unit ?? $child->price_excl_tax ?? 0) : 0);
                                                        $uCost = (float) ($kItem->cost_price > 0 ? $kItem->cost_price : ($child->purchase_price ?? 0));
                                                        $q = (float) ($kItem->quantity ?? 1);
                                                    @endphp
                                                    <tr>
                                                        <td class="ps-4 text-muted small font-monospace">{{ $idx + 1 }}</td>
                                                        <td>
                                                            @if($child)
                                                                <a href="{{ route('equipments.show', $child->id) }}" class="fw-semibold text-dark text-decoration-none">
                                                                    {{ $child->name }}
                                                                </a>
                                                                @if($child->brand)
                                                                    <div class="small text-muted">{{ $child->brand->name }}</div>
                                                                @endif
                                                            @else
                                                                <span class="text-muted">{{ __('locale.combo.item_not_found') }}</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-light text-secondary border font-monospace">
                                                                {{ $child->reference ?? '-' }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">{{ $q }}</span>
                                                        </td>
                                                        <td class="text-center">
                                                            @if($child)
                                                                <span class="badge {{ ($child->current_stock ?? 0) > 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} border">
                                                                    {{ $child->current_stock ?? 0 }}
                                                                </span>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        @if($canSeeCostPrices)
                                                            <td class="text-end text-muted">
                                                                {{ number_format($uCost, 2, ',', ' ') }} €
                                                            </td>
                                                        @endif
                                                        @if($canSeeSalePrices)
                                                        <td class="text-end fw-semibold text-muted">
                                                            {{ number_format($uPrice, 2, ',', ' ') }} €
                                                        </td>
                                                        @endif
                                                        @if($canSeeCostPrices || $canSeeSalePrices)
                                                        <td class="text-end pe-4 fw-bold text-dark">
                                                            {{ number_format(($canSeeCostPrices ? $uCost : $uPrice) * $q, 2, ',', ' ') }} €
                                                        </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-5 text-muted border border-dashed rounded-3 bg-light">
                                    <i class="bi bi-diagram-3 fs-1 d-block mb-2 text-secondary"></i>
                                    <p class="fw-semibold text-dark mb-1">{{ __('locale.kit.no_components_associated') ?? 'Aucun composant associé à ce kit' }}</p>
                                    <p class="small text-muted mb-3">{{ __('locale.kit.no_components_associated_desc') ?? 'Modifiez cet équipement pour lui associer des composants.' }}</p>
                                    <a href="{{ route('equipments.edit', $equipment->id) }}#kit-tab" class="btn btn-sm btn-success">
                                        <i class="bi bi-pencil me-1"></i>{{ __('locale.kit.edit_kit_components') ?? 'Gérer les composants' }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Bottom Section: NOTES INTERNES -->
            <div class="notes-title">{{ __('locale.equipment_show.lbl_internal_notes') }}</div>
            <div class="p-2 rounded bg-light text-muted small italic mb-2">
                {{ $equipment->description ?? __('locale.equipment_show.lbl_internal_notes_default') }}
            </div>
        </div>

        <!-- Related Data Sections Card -->
        <div class="card card-premium border-0 shadow-sm overflow-hidden mb-4">
            <div class="card-header bg-white border-bottom p-0">
                <ul class="nav nav-tabs nav-tabs-bordered card-header-tabs m-0 px-3 pt-2" id="equipmentRelatedTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-semibold d-flex align-items-center py-2 px-3 small" id="reseller-tab" data-bs-toggle="tab" data-bs-target="#reseller-tab-pane" type="button" role="tab">
                            <i class="bi bi-shop text-warning me-2"></i> {{ __('locale.lbl_reseller_references') }}
                            @if($equipment->suppliers->isNotEmpty())
                                <span class="badge bg-warning bg-opacity-10 text-warning ms-2 rounded-pill">{{ $equipment->suppliers->count() }}</span>
                            @endif
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold d-flex align-items-center py-2 px-3 small" id="substitutes-tab" data-bs-toggle="tab" data-bs-target="#substitutes-tab-pane" type="button" role="tab">
                            <i class="bi bi-arrow-repeat text-success me-2"></i> {{ __('locale.lbl_substitutes') }}
                            @if($equipment->substitutes->isNotEmpty())
                                <span class="badge bg-success bg-opacity-10 text-success ms-2 rounded-pill">{{ $equipment->substitutes->count() }}</span>
                            @endif
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold d-flex align-items-center py-2 px-3 small" id="projects-tab" data-bs-toggle="tab" data-bs-target="#projects-tab-pane" type="button" role="tab">
                            <i class="bi bi-briefcase text-secondary me-2"></i> {{ __('locale.lbl_future_projects') }}
                            @if($equipment->projects->isNotEmpty())
                                <span class="badge bg-secondary bg-opacity-10 text-secondary ms-2 rounded-pill">{{ $equipment->projects->count() }}</span>
                            @endif
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        @php
                            $pendingPurchases = $equipment->purchases()->where('status', 'pending')->get();
                        @endphp
                        <button class="nav-link fw-semibold d-flex align-items-center py-2 px-3 small" id="purchases-tab" data-bs-toggle="tab" data-bs-target="#purchases-tab-pane" type="button" role="tab">
                            <i class="bi bi-cart-check text-info me-2"></i> {{ __('locale.lbl_planned_purchases') }}
                            @if($pendingPurchases->isNotEmpty())
                                <span class="badge bg-info bg-opacity-10 text-info ms-2 rounded-pill">{{ $pendingPurchases->count() }}</span>
                            @endif
                        </button>
                    </li>
                    @if($canSeeSalePrices || $canSeeCostPrices)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold d-flex align-items-center py-2 px-3 small" id="history-tab" data-bs-toggle="tab" data-bs-target="#history-tab-pane" type="button" role="tab">
                            <i class="bi bi-clock-history text-danger me-2"></i> {{ __('locale.lbl_price_history') ?? 'Historique prix' }}
                            @if($visiblePriceHistories->isNotEmpty())
                                <span class="badge bg-danger bg-opacity-10 text-danger ms-2 rounded-pill">{{ $visiblePriceHistories->count() }}</span>
                            @endif
                        </button>
                    </li>
                    @endif
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold d-flex align-items-center py-2 px-3 small" id="leadtimes-tab" data-bs-toggle="tab" data-bs-target="#leadtimes-tab-pane" type="button" role="tab">
                            <i class="bi bi-truck text-primary me-2"></i> {{ __('locale.lbl_delivery_lead_times') }}
                            @if($equipment->deliveryLeadTimes->isNotEmpty())
                                <span class="badge bg-primary bg-opacity-10 text-primary ms-2 rounded-pill">{{ $equipment->deliveryLeadTimes->count() }}</span>
                            @endif
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body p-3 tab-content" id="equipmentRelatedTabsContent">
                <!-- Reseller References Tab -->
                <div class="tab-pane fade show active" id="reseller-tab-pane" role="tabpanel" tabindex="0">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0 fw-bold small text-uppercase">{{ __('locale.lbl_reseller_references') }}</h6>
                        @can('add-equipment-supplier')
                        <button type="button" class="btn btn-xs btn-secondary rounded-pill" data-bs-toggle="modal" data-bs-target="#addResellerModal">
                            <i class="bi bi-plus-lg me-1"></i> {{ __('locale.lbl_add_reseller_reference') ?? 'Ajouter' }}
                        </button>
                        @endcan
                    </div>
                    @if($equipment->suppliers->isEmpty())
                        <p class="text-muted mb-0 small italic text-center py-2">{{ __('locale.no_reseller_references_found') ?? 'Aucune référence revendeur n\'est enregistrée.' }}</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle border-0 mb-0 small">
                                <thead class="text-uppercase text-muted">
                                    <tr>
                                        <th class="py-2 px-3">{{ __('locale.lbl_reseller_provider') }}</th>
                                        <th class="py-2 px-3">{{ __('locale.lbl_specific_reference') }}</th>
                                        <th class="py-2 px-3 text-end">{{ __('locale.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($equipment->suppliers as $supplier)
                                        <tr class="border-top">
                                            <td class="px-3 py-2">
                                                <a href="{{ route('suppliers.show', $supplier->id) }}" class="fw-bold text-dark text-decoration-none">
                                                    {{ $supplier->name }}
                                                </a>
                                            </td>
                                            <td class="px-3">
                                                <span class="badge bg-light text-dark border-0 px-2 py-1 rounded-pill font-monospace">
                                                    {{ $supplier->pivot->supplier_reference }}
                                                </span>
                                            </td>
                                            <td class="px-3 text-end">
                                                <a href="{{ route('suppliers.show', $supplier->id) }}" class="btn btn-xs btn-outline-secondary rounded-pill me-1" title="{{ __('locale.btn_show') }}">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @can('delete-equipment-supplier')
                                                <form action="{{ route('equipments.remove_supplier', [$equipment->id, $supplier->id, $supplier->pivot->id]) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-xs btn-outline-danger rounded-pill" onclick="return confirm('{{ __('locale.are_you_sure') }}')" title="{{ __('locale.btn_delete') }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Substitutes Tab -->
                <div class="tab-pane fade" id="substitutes-tab-pane" role="tabpanel" tabindex="0">
                    <h6 class="mb-2 fw-bold small text-uppercase">{{ __('locale.lbl_substitutes') }}</h6>
                    @if($equipment->substitutes->isEmpty())
                        <p class="text-muted mb-0 small italic text-center py-2">{{ __('locale.msg_no_substitutes') }}</p>
                    @else
                        <div class="list-group list-group-flush small">
                            @foreach($equipment->substitutes as $substitute)
                                <a href="{{ route('equipments.show', $substitute) }}" class="list-group-item list-group-item-action px-0 py-2 border-light">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-box text-success me-2"></i>
                                        <span class="fw-bold text-dark me-2">{{ $substitute->name }}</span>
                                        <span class="small text-muted">({{ $substitute->reference }})</span>
                                        <i class="bi bi-chevron-right ms-auto text-muted small"></i>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Future Projects Tab -->
                <div class="tab-pane fade" id="projects-tab-pane" role="tabpanel" tabindex="0">
                    <h6 class="mb-2 fw-bold small text-uppercase">{{ __('locale.lbl_future_projects') }}</h6>
                    @if($equipment->projects->isEmpty())
                        <p class="text-muted mb-0 small italic text-center py-2">{{ __('locale.no_future_projects_found') ?? 'Aucun projet futur n\'est planifié.' }}</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle border-0 mb-0 small">
                                <thead class="text-uppercase text-muted">
                                    <tr>
                                        <th class="py-2 px-3">{{ __('locale.lbl_project') }}</th>
                                        <th class="py-2 px-3">{{ __('locale.lbl_client') }}</th>
                                        <th class="py-2 px-3 text-center">{{ __('locale.lbl_quantity') }}</th>
                                        <th class="py-2 px-3 text-end">{{ __('locale.lbl_need_date') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($equipment->projects as $project)
                                        <tr class="border-top">
                                            <td class="px-3 py-2">
                                                <a href="{{ route('projects.show', $project->id) }}" class="fw-bold text-dark text-decoration-none">
                                                    {{ $project->name }}
                                                </a>
                                            </td>
                                            <td class="px-3 py-2">{{ $project->client->raison_sociale ?? '-' }}</td>
                                            <td class="px-3 py-2 text-center"><span class="badge bg-secondary px-2 py-1 rounded-pill">{{ $project->pivot->quantity }}</span></td>
                                            <td class="px-3 py-2 text-end fw-bold text-secondary">{{ $project->pivot->needed_by_date ? \Carbon\Carbon::parse($project->pivot->needed_by_date)->format('d/m/Y') : '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Planned Purchases Tab -->
                <div class="tab-pane fade" id="purchases-tab-pane" role="tabpanel" tabindex="0">
                    <h6 class="mb-2 fw-bold small text-uppercase">{{ __('locale.lbl_planned_purchases') }}</h6>
                    @if($pendingPurchases->isEmpty())
                        <p class="text-muted mb-0 small italic text-center py-2">{{ __('locale.no_planned_purchases_found') ?? 'Aucun achat prévu n\'est en cours.' }}</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle border-0 mb-0 small">
                                <thead class="text-uppercase text-muted">
                                    <tr>
                                        <th class="py-2 px-3">{{ __('locale.lbl_purchase_reference') ?? 'Référence Achat' }}</th>
                                        <th class="py-2 px-3">{{ __('locale.lbl_supplier') }}</th>
                                        <th class="py-2 px-3 text-center">{{ __('locale.lbl_quantity') }}</th>
                                        <th class="py-2 px-3 text-end">{{ __('locale.lbl_expected_delivery_date') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingPurchases as $purchase)
                                        <tr class="border-top">
                                            <td class="px-3 py-2"><a href="{{ route('purchases.show', $purchase->id) }}" class="fw-bold text-dark text-decoration-none">{{ $purchase->reference }}</a></td>
                                            <td class="px-3 py-2">{{ $purchase->supplier->name ?? '-' }}</td>
                                            <td class="px-3 py-2 text-center"><span class="badge bg-info px-2 py-1 rounded-pill">{{ $purchase->pivot->quantity }}</span></td>
                                            <td class="px-3 py-2 text-end fw-bold text-info">{{ $purchase->pivot->expected_delivery_date ? \Carbon\Carbon::parse($purchase->pivot->expected_delivery_date)->format('d/m/Y') : '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Price History Tab -->
                @if($canSeeSalePrices || $canSeeCostPrices)
                <div class="tab-pane fade" id="history-tab-pane" role="tabpanel" tabindex="0">
                    @php
                        // Standard field definitions and color theme
                        $fieldDefinitions = [
                            'price_per_unit' => [
                                'label' => __('locale.equipment_show.lbl_sale_price') ?? 'Prix de vente',
                                'color' => '#696cff',
                                'is_currency' => true,
                                'badge_class' => 'bg-primary-subtle text-primary border-primary-subtle',
                            ],
                            'purchase_price' => [
                                'label' => __('locale.equipment_show.lbl_purchase_cost') ?? 'Prix d\'achat',
                                'color' => '#ff3e1d',
                                'is_currency' => true,
                                'badge_class' => 'bg-danger-subtle text-danger border-danger-subtle',
                            ],
                            'price_excl_tax' => [
                                'label' => __('locale.equipment_show.lbl_sale_price_excl_tax') ?? 'Prix de vente HT',
                                'color' => '#71dd37',
                                'is_currency' => true,
                                'badge_class' => 'bg-success-subtle text-success border-success-subtle',
                            ],
                            'price_incl_tax' => [
                                'label' => __('locale.equipment_show.lbl_estimated_incl_tax') ?? 'Prix TTC estimé',
                                'color' => '#03c3ec',
                                'is_currency' => true,
                                'badge_class' => 'bg-info-subtle text-info border-info-subtle',
                            ],
                            'odp_purchase_price' => [
                                'label' => __('locale.equipment_show.lbl_odp_purchase_price') ?? 'Prix d\'achat ODP',
                                'color' => '#ffab00',
                                'is_currency' => true,
                                'badge_class' => 'bg-warning-subtle text-warning-emphasis border-warning-subtle',
                            ],
                            'discount' => [
                                'label' => __('locale.equipment_show.lbl_discount_rate') ?? 'Remise (%)',
                                'color' => '#fd7e14',
                                'is_currency' => false,
                                'badge_class' => 'bg-secondary-subtle text-secondary border-secondary-subtle',
                            ],
                            'ga_pmap' => [
                                'label' => __('locale.equipment_show.lbl_ga_pmap') ?? 'PMAP',
                                'color' => '#20c997',
                                'is_currency' => true,
                                'badge_class' => 'bg-success-subtle text-success border-success-subtle',
                            ],
                            'ga_pmrp' => [
                                'label' => __('locale.equipment_show.lbl_ga_pmrp') ?? 'PMRP',
                                'color' => '#e83e8c',
                                'is_currency' => true,
                                'badge_class' => 'bg-danger-subtle text-danger border-danger-subtle',
                            ],
                        ];

                        $fallbackColors = ['#6610f2', '#6f42c1', '#d63384', '#198754', '#0dcaf0', '#ffc107', '#20c997', '#0d6efd'];
                        $fallbackColorIndex = 0;

                        // Group histories by field_name and order chronologically (asc)
                        $groupedHistories = $visiblePriceHistories->groupBy('field_name');
                        $chartSeries = [];
                        $chartColors = [];

                        foreach ($groupedHistories as $fieldName => $histories) {
                            $sorted = $histories->sortBy('created_at')->values();
                            if ($sorted->isEmpty()) {
                                continue;
                            }

                            $fieldDef = $fieldDefinitions[$fieldName] ?? [
                                'label' => ucwords(str_replace('_', ' ', $fieldName)),
                                'color' => $fallbackColors[($fallbackColorIndex++) % count($fallbackColors)],
                                'is_currency' => true,
                                'badge_class' => 'bg-secondary-subtle text-secondary border-secondary-subtle',
                            ];

                            $firstHistory = $sorted->first();
                            $isPercentage = ($fieldName === 'discount');
                            $unit = $isPercentage ? '%' : __('locale.currency');

                            $dataPoints = [];
                            $lastTimestamp = null;

                            // Add initial baseline point if old_value exists
                            if ($firstHistory->old_value !== null && $firstHistory->created_at) {
                                $initialTime = null;
                                if ($equipment->created_at && $equipment->created_at->lt($firstHistory->created_at)) {
                                    $initialTime = $equipment->created_at;
                                } else {
                                    $initialTime = $firstHistory->created_at->copy()->subMinutes(10);
                                }

                                $ts = $initialTime->getTimestamp() * 1000;
                                $lastTimestamp = $ts;

                                $dataPoints[] = [
                                    'x' => $ts,
                                    'y' => (float) $firstHistory->old_value,
                                    'meta' => [
                                        'isInitial' => true,
                                        'date' => $initialTime->format('d/m/Y H:i'),
                                        'value' => number_format((float) $firstHistory->old_value, 2, ',', ' '),
                                        'fieldName' => $fieldDef['label'],
                                        'isPercentage' => $isPercentage,
                                        'userName' => 'Valeur initiale',
                                    ]
                                ];
                            }

                            // Plot every change point on this field's curve
                            foreach ($sorted as $history) {
                                if (!$history->created_at) {
                                    continue;
                                }

                                $ts = $history->created_at->getTimestamp() * 1000;
                                if ($lastTimestamp !== null && $ts <= $lastTimestamp) {
                                    $ts = $lastTimestamp + 1000;
                                }
                                $lastTimestamp = $ts;

                                $oldVal = $history->old_value !== null ? (float) $history->old_value : null;
                                $newVal = $history->new_value !== null ? (float) $history->new_value : 0;
                                $diff = $oldVal !== null ? ($newVal - $oldVal) : 0;

                                $dataPoints[] = [
                                    'x' => $ts,
                                    'y' => $newVal,
                                    'meta' => [
                                        'isInitial' => false,
                                        'date' => $history->created_at->format('d/m/Y H:i'),
                                        'oldValue' => $oldVal !== null ? number_format($oldVal, 2, ',', ' ') : '-',
                                        'newValue' => number_format($newVal, 2, ',', ' '),
                                        'diffRaw' => round($diff, 2),
                                        'diff' => number_format($diff, 2, ',', ' '),
                                        'fieldName' => $fieldDef['label'],
                                        'isPercentage' => $isPercentage,
                                        'userName' => $history->user->name ?? 'Système',
                                    ]
                                ];
                            }

                            if (!empty($dataPoints)) {
                                $chartSeries[] = [
                                    'name' => $fieldDef['label'],
                                    'data' => $dataPoints,
                                ];
                                $chartColors[] = $fieldDef['color'];
                            }
                        }
                    @endphp

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <h6 class="mb-0 fw-bold small text-uppercase">
                                <i class="bi bi-clock-history text-danger me-1"></i> {{ __('locale.lbl_price_history') ?? 'Historique des prix' }}
                            </h6>
                            @if($visiblePriceHistories->isNotEmpty())
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill font-monospace" style="font-size: 0.72rem;">
                                    {{ $visiblePriceHistories->count() }} {{ $visiblePriceHistories->count() > 1 ? 'modifications' : 'modification' }}
                                </span>
                            @endif
                        </div>
                        @if($visiblePriceHistories->isNotEmpty() && !empty($chartSeries))
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-muted small d-none d-sm-inline" style="font-size: 0.75rem;">Style :</span>
                                <div class="btn-group btn-group-sm" role="group" aria-label="Type de courbe">
                                    <button type="button" class="btn btn-outline-secondary btn-xs active price-curve-toggle" data-curve-type="straight" title="Ligne directe">
                                        <i class="bi bi-slash-lg"></i> Ligne
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-xs price-curve-toggle" data-curve-type="smooth" title="Courbe lissée">
                                        <i class="bi bi-bezier2"></i> Lisse
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-xs price-curve-toggle" data-curve-type="stepline" title="Paliers en escalier">
                                        <i class="bi bi-reception-3"></i> Paliers
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($visiblePriceHistories->isEmpty())
                        <p class="text-muted mb-0 small italic text-center py-4">{{ __('locale.no_price_history_found') ?? 'Aucun historique de prix.' }}</p>
                    @else
                        <!-- Chart Card -->
                        <div class="card border border-light-subtle shadow-none mb-3" style="background-color: #fbfcfe; border-radius: 8px;">
                            <div class="card-body p-3">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-graph-up-arrow text-primary"></i>
                                        <span class="small fw-bold text-dark">{{ __('locale.equipment_show.lbl_price_evolution_graph') ?? 'Évolution des prix par champ' }}</span>
                                    </div>
                                    <span class="text-muted small" style="font-size: 0.72rem;">
                                        <i class="bi bi-info-circle me-1"></i>Cliquez sur un libellé de la légende pour masquer/afficher une courbe
                                    </span>
                                </div>
                                <div id="equipmentPriceHistoryChart" style="min-height: 320px; width: 100%;"></div>
                            </div>
                        </div>

                        <!-- Price History Table -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle border-0 mb-0 small">
                                <thead class="text-uppercase text-muted">
                                    <tr>
                                        <th class="py-2 px-3">{{ __('locale.equipment_show.lbl_date_col') }}</th>
                                        <th class="py-2 px-3">{{ __('locale.equipment_show.lbl_field_col') }}</th>
                                        <th class="py-2 px-3">{{ __('locale.equipment_show.lbl_old_value_col') }}</th>
                                        <th class="py-2 px-3">{{ __('locale.equipment_show.lbl_new_value_col') }}</th>
                                        <th class="py-2 px-3 text-center">Écart</th>
                                        <th class="py-2 px-3">{{ __('locale.equipment_show.lbl_user_col') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($visiblePriceHistories as $history)
                                        @php
                                            $fieldDef = $fieldDefinitions[$history->field_name] ?? [
                                                'label' => ucwords(str_replace('_', ' ', $history->field_name)),
                                                'color' => '#6c757d',
                                                'is_currency' => true,
                                                'badge_class' => 'bg-secondary-subtle text-secondary border-secondary-subtle',
                                            ];
                                            $isPercentage = ($history->field_name === 'discount');
                                            $unit = $isPercentage ? '%' : __('locale.currency');
                                            $oldVal = $history->old_value !== null ? (float) $history->old_value : null;
                                            $newVal = $history->new_value !== null ? (float) $history->new_value : null;
                                            $diff = ($oldVal !== null && $newVal !== null) ? ($newVal - $oldVal) : null;
                                        @endphp
                                        <tr class="border-top">
                                            <td class="px-3 py-2 text-nowrap text-muted font-monospace" style="font-size: 0.76rem;">
                                                <i class="bi bi-calendar3 me-1 opacity-75"></i>
                                                {{ $history->created_at ? $history->created_at->format('d/m/Y H:i') : '-' }}
                                            </td>
                                            <td class="px-3 py-2">
                                                <span class="badge {{ $fieldDef['badge_class'] ?? 'bg-light text-dark' }} border px-2 py-1" style="font-size: 0.74rem;">
                                                    <span style="display:inline-block; width:7px; height:7px; border-radius:50%; background-color:{{ $fieldDef['color'] }}; margin-right:4px;"></span>
                                                    {{ $fieldDef['label'] }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-2 text-muted">
                                                {{ $oldVal !== null ? format_number($oldVal, 2) . ' ' . $unit : '-' }}
                                            </td>
                                            <td class="px-3 py-2 fw-bold text-dark">
                                                {{ $newVal !== null ? format_number($newVal, 2) . ' ' . $unit : '-' }}
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                @if($diff !== null)
                                                    @if($diff > 0)
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.72rem;">
                                                            <i class="bi bi-arrow-up-right me-1"></i>+{{ format_number($diff, 2) }} {{ $unit }}
                                                        </span>
                                                    @elseif($diff < 0)
                                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 0.72rem;">
                                                            <i class="bi bi-arrow-down-right me-1"></i>{{ format_number($diff, 2) }} {{ $unit }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle" style="font-size: 0.72rem;">
                                                            =
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-2 text-nowrap">
                                                <span class="small text-secondary">
                                                    <i class="bi bi-person me-1"></i>{{ $history->user->name ?? 'Système' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                @endif

                <!-- Delivery Lead Times Tab -->
                <div class="tab-pane fade" id="leadtimes-tab-pane" role="tabpanel" tabindex="0">
                    @php
                        $latestLeadTime = $equipment->deliveryLeadTimes->sortByDesc('updated_at')->first();
                        $latestLeadTimeFileDate = $latestLeadTime?->file_date;
                    @endphp
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0 fw-bold small text-uppercase">{{ __('locale.lbl_delivery_lead_times') }}</h6>
                        @if($latestLeadTimeFileDate)
                            <span class="badge bg-light text-secondary border px-2 py-1 small">
                                <i class="bi bi-calendar-event me-1 text-primary"></i> {{ __('locale.lbl_file_date') }}: <strong class="text-dark">{{ \Carbon\Carbon::parse($latestLeadTimeFileDate)->format('d/m/Y') }}</strong>
                            </span>
                        @endif
                    </div>
                    @if($equipment->deliveryLeadTimes->isEmpty())
                        <p class="text-muted mb-0 small italic text-center py-2">{{ __('locale.no_lead_times') }}</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle border-0 mb-0 small">
                                <thead class="text-uppercase text-muted">
                                    <tr>
                                        <th class="py-2 px-3">{{ __('locale.group') }}</th>
                                        <th class="py-2 px-3 text-center">{{ __('locale.min_qty') }}</th>
                                        <th class="py-2 px-3 text-center">{{ __('locale.max_qty') }}</th>
                                        <th class="py-2 px-3 text-center">{{ __('locale.lbl_file_date') }}</th>
                                        <th class="py-2 px-3 text-end">{{ __('locale.lead_time_days') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($equipment->deliveryLeadTimes as $leadTime)
                                        <tr class="border-top">
                                            <td class="px-3 py-2 fw-semibold">{{ $leadTime->lead_time_group ?? '-' }}</td>
                                            <td class="px-3 py-2 text-center">{{ format_number($leadTime->min_quantity, 0) }}</td>
                                            <td class="px-3 py-2 text-center">{{ $leadTime->max_quantity ? format_number($leadTime->max_quantity, 0) : '∞' }}</td>
                                            <td class="px-3 py-2 text-center text-muted">
                                                {{ $leadTime->file_date ? \Carbon\Carbon::parse($leadTime->file_date)->format('d/m/Y') : '-' }}
                                            </td>
                                            <td class="px-3 py-2 text-end fw-bold text-primary">{{ $leadTime->lead_time_days }} {{ __('locale.equipment_show.lbl_days') ?? 'jours' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#btn-show-inline-add').click(function() {
        $('#empty-suppliers-row').hide();
        $('#inline-add-supplier-row').show();
        $(this).parent().hide();

        if (!$('#inline_supplier_id').hasClass('select2-hidden-accessible')) {
            $('#inline_supplier_id').select2({
                width: '100%',
                placeholder: "{{ __('locale.equipment_show.lbl_select_placeholder') }}",
                allowClear: true,
                containerCssClass: 'select2-container--odoo-inline'
            }).on('select2:open', function() {
                setTimeout(function() {
                    const searchField = document.querySelector('.select2-container--open .select2-search__field');
                    if (searchField) {
                        searchField.focus();
                    }
                }, 10);
            });
        }

        setTimeout(function() {
            $('#inline_supplier_id').select2('open');
        }, 50);
    });

        $('#cancel-inline-add').click(function() {
        $('#inline-add-supplier-row').hide();
        $('#btn-show-inline-add').parent().show();
        if ($('#empty-suppliers-row').length && !$('#inline-add-supplier-row').siblings('tr:visible').not('#empty-suppliers-row').length) {
            $('#empty-suppliers-row').show();
        }
    });

    @if(isset($chartSeries) && !empty($chartSeries))
    // Price History Chart Initialization
    let priceHistoryChart = null;
    const priceChartData = @json($chartSeries);
    const priceChartColors = @json($chartColors);

    function initPriceHistoryChart() {
        const chartElement = document.querySelector('#equipmentPriceHistoryChart');
        if (!chartElement) return;

        if (priceHistoryChart) {
            setTimeout(function() {
                priceHistoryChart.resize();
            }, 50);
            return;
        }

        if (typeof ApexCharts === 'undefined') {
            console.warn('ApexCharts is not loaded');
            return;
        }

        const options = {
            series: priceChartData,
            colors: priceChartColors,
            chart: {
                type: 'line',
                height: 320,
                fontFamily: 'inherit',
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: false,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: true,
                        reset: true
                    },
                    export: {
                        csv: {
                            filename: 'historique_prix_{{ $equipment->id }}',
                            headerCategory: 'Date'
                        },
                        svg: {
                            filename: 'historique_prix_{{ $equipment->id }}'
                        },
                        png: {
                            filename: 'historique_prix_{{ $equipment->id }}'
                        }
                    }
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 400
                }
            },
            stroke: {
                curve: 'straight',
                width: 2.5
            },
            markers: {
                size: 5,
                strokeWidth: 2,
                strokeColors: '#ffffff',
                hover: {
                    size: 8,
                    sizeOffset: 3
                }
            },
            xaxis: {
                type: 'datetime',
                labels: {
                    datetimeUTC: false,
                    format: 'dd/MM/yy HH:mm',
                    style: {
                        fontSize: '11px',
                        colors: '#64748b'
                    }
                },
                tooltip: {
                    enabled: false
                }
            },
            yaxis: {
                labels: {
                    formatter: function(val) {
                        return (val !== null && val !== undefined) ? Number(val).toFixed(2) : '';
                    },
                    style: {
                        fontSize: '11px',
                        colors: '#64748b'
                    }
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'left',
                fontSize: '12px',
                fontWeight: 600,
                labels: {
                    colors: '#475569'
                },
                markers: {
                    radius: 12,
                    offsetX: -3
                },
                itemMargin: {
                    horizontal: 10,
                    vertical: 5
                }
            },
            grid: {
                borderColor: '#e2e8f0',
                strokeDashArray: 3,
                padding: {
                    top: 0,
                    right: 20,
                    bottom: 0,
                    left: 10
                }
            },
            tooltip: {
                shared: false,
                intersect: true,
                theme: 'light',
                custom: function({ series, seriesIndex, dataPointIndex, w }) {
                    const point = w.config.series[seriesIndex].data[dataPointIndex];
                    if (!point) return '';
                    const meta = point.meta || {};
                    const seriesName = w.config.series[seriesIndex].name;
                    const seriesColor = w.config.colors[seriesIndex];
                    const unit = meta.isPercentage ? '%' : '€';

                    let changeHtml = '';
                    if (meta.isInitial) {
                        changeHtml = `
                            <div class="d-flex justify-content-between gap-3 text-muted small mt-1">
                                <span>Valeur initiale :</span>
                                <span class="fw-bold text-dark">${point.y.toFixed(2)} ${unit}</span>
                            </div>`;
                    } else {
                        const diff = meta.diffRaw || 0;
                        let diffBadge = '';
                        if (diff > 0) {
                            diffBadge = `<span class="badge bg-success-subtle text-success ms-1">+${diff.toFixed(2)} ${unit}</span>`;
                        } else if (diff < 0) {
                            diffBadge = `<span class="badge bg-danger-subtle text-danger ms-1">${diff.toFixed(2)} ${unit}</span>`;
                        } else {
                            diffBadge = `<span class="badge bg-secondary-subtle text-secondary ms-1">=</span>`;
                        }

                        changeHtml = `
                            <div class="d-flex justify-content-between gap-3 text-muted small mt-1">
                                <span>Ancien prix :</span>
                                <span class="fw-medium">${meta.oldValue !== null ? meta.oldValue + ' ' + unit : '-'}</span>
                            </div>
                            <div class="d-flex justify-content-between gap-3 small mt-1">
                                <span class="text-muted">Nouveau prix :</span>
                                <span class="fw-bold text-dark">${meta.newValue + ' ' + unit} ${diffBadge}</span>
                            </div>`;
                    }

                    const userHtml = meta.userName ? `
                        <div class="d-flex justify-content-between gap-3 text-muted small mt-2 pt-1 border-top" style="font-size:0.72rem;">
                            <span>Par :</span>
                            <span class="fw-semibold text-secondary">${meta.userName}</span>
                        </div>` : '';

                    return `
                        <div class="p-2 shadow-sm rounded border bg-white" style="font-size: 0.78rem; min-width: 220px;">
                            <div class="d-flex align-items-center gap-2 border-bottom pb-1 mb-1">
                                <span style="display:inline-block; width:9px; height:9px; border-radius:50%; background-color:${seriesColor};"></span>
                                <strong class="text-dark">${seriesName}</strong>
                            </div>
                            <div class="text-muted small mb-1" style="font-size:0.72rem;">
                                <i class="bi bi-calendar3 me-1"></i>${meta.date}
                            </div>
                            ${changeHtml}
                            ${userHtml}
                        </div>
                    `;
                }
            }
        };

        priceHistoryChart = new ApexCharts(chartElement, options);
        priceHistoryChart.render();
    }

    // Curve type toggles
    $('.price-curve-toggle').on('click', function() {
        $('.price-curve-toggle').removeClass('active');
        $(this).addClass('active');
        const curve = $(this).data('curve-type');
        if (priceHistoryChart) {
            priceHistoryChart.updateOptions({
                stroke: {
                    curve: curve
                }
            });
        }
    });

    // Initialize or resize chart when price history tab is shown
    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
        if (e.target.id === 'history-tab' || $(e.target).data('bs-target') === '#history-tab-pane') {
            setTimeout(function() {
                initPriceHistoryChart();
            }, 80);
        }
    });

    // Initialize if tab is active on load
    if ($('#history-tab-pane').hasClass('active') || $('#history-tab').hasClass('active')) {
        setTimeout(function() {
            initPriceHistoryChart();
        }, 100);
    }
    @endif
});
</script>
@endpush
@endsection
