@extends('layouts.app')
@section('portal-module', 'equipments')
@section('portal-page', 'edit')
@section('portal-kind', 'form')
@section('portal-family', 'catalog')
@section('portal-icon', 'tags')


@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('equipments.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 px-1 me-1 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <i class="bi bi-star text-warning fs-5"></i>
        <input type="text" class="form-control odoo-title-input" id="name" name="name" form="equipmentForm" value="{{ old('name', $equipment->name) }}" required placeholder="{{ __('locale.equipment_form.lbl_product') }}" style="width:700px; max-width: 100%; height: 38px; font-size: 1.05rem; font-weight: 600;">
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('equipments.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-1 fs-12">{{ __('locale.cancel') }}</a>
        <button type="submit" form="equipmentForm" class="btn btn-sm btn-secondary px-3 py-1 fs-12">
            <i class="bi bi-check-lg me-1"></i>{{ __('locale.lbl_save') }}
        </button>
    </div>
@endsection

@push('styles')
<style>
    .content-wrapper {
        min-height: calc(100vh - 88px);
        display: flex;
        flex-direction: column;
        overflow-y: auto;
    }
    .container-xxl {
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
        padding-top: 0.15rem;
        padding-bottom: 0.15rem;
        min-height: 0;
    }
    form.eq-form-layout {
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
        min-height: 0;
        width: 100%;
    }
    .odoo-sheet {
        background: #ffffff;
        color: #1e293b;
        border: 1px solid #dbe2ea;
        border-radius: 10px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        padding: 0.6rem 1rem;
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
        min-height: 580px;
    }
    .badge-associated {
        background-color: #e0f2fe !important;
        color: #0284c7 !important;
        border: 1px solid #bae6fd;
        font-size: 0.82rem;
        font-weight: 600;
        padding: 0.35rem 0.85rem;
        border-radius: 20px;
        box-shadow: 0 1px 2px rgba(2, 132, 199, 0.08);
    }
    .badge-available {
        background-color: #dcfce7 !important;
        color: #15803d !important;
        border: 1px solid #bbf7d0;
        font-size: 0.82rem;
        font-weight: 600;
        padding: 0.35rem 0.85rem;
        border-radius: 20px;
        box-shadow: 0 1px 2px rgba(21, 128, 61, 0.08);
    }
    .dual-list-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #ffffff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        height: 520px;
        min-height: 380px;
        max-height: 65vh;
    }
    .dual-list-card-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 0.6rem 0.85rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
    }
    .dual-list-scroll-area {
        flex: 1 1 auto;
        height: 0;
        min-height: 0;
        max-height: none;
        overflow-y: auto;
        padding: 0.6rem;
        background: #fafbfc;
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }
    .dual-list-scroll-area::-webkit-scrollbar {
        width: 6px;
    }
    .dual-list-scroll-area::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    .dual-list-scroll-area::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    .combo-item-card {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.35rem 0.65rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 7px;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
        user-select: none;
    }
    .combo-item-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    }
    .draggable-item {
        cursor: grab;
    }
    .draggable-item:active {
        cursor: grabbing;
    }
    .card-drag-handle {
        cursor: grab;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        padding: 0.05rem;
        transition: color 0.15s ease;
    }
    .card-drag-handle:hover {
        color: #0284c7;
    }
    .card-drag-handle:active {
        cursor: grabbing;
    }
    .card-item-main {
        flex: 1 1 auto;
        min-width: 0;
    }
    .card-item-title {
        font-weight: 600;
        font-size: 0.825rem;
        color: #1e293b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .card-item-subtitle {
        display: none;
    }
    .item-ref-badge {
        background: #f1f5f9;
        color: #0f172a;
        font-weight: 700;
        font-size: 0.72rem;
        padding: 0.1rem 0.45rem;
        border-radius: 5px;
        border: 1px solid #e2e8f0;
        display: inline-block;
        flex-shrink: 0;
        max-width: 110px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .card-item-qty {
        flex: 0 0 auto;
        width: 82px;
    }
    .card-item-price {
        flex: 0 0 auto;
        width: 105px;
    }
    .card-item-price-tag {
        font-weight: 700;
        font-size: 0.825rem;
        color: #0f172a;
        text-align: right;
        flex: 0 0 auto;
        white-space: nowrap;
    }
    .card-item-action {
        flex: 0 0 auto;
    }
    .btn-action-icon {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
        border: 1px solid transparent;
        cursor: pointer;
    }
    .btn-action-icon.btn-action-icon-sm {
        width: 20px;
        height: 20px;
        font-size: 0.75rem;
        flex-shrink: 0;
        padding: 0;
    }
    .btn-action-icon.btn-action-icon-sm i {
        font-size: 0.85rem;
        line-height: 1;
    }
    .btn-action-icon.btn-add {
        background: #eff6ff;
        color: #2563eb;
        border-color: #dbeafe;
    }
    .btn-action-icon.btn-add:hover {
        background: #2563eb;
        color: #ffffff;
        transform: scale(1.1);
        box-shadow: 0 3px 6px rgba(37, 99, 235, 0.25);
    }
    .btn-action-icon.btn-remove {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fee2e2;
    }
    .btn-action-icon.btn-remove:hover {
        background: #dc2626;
        color: #ffffff;
        transform: scale(1.1);
        box-shadow: 0 3px 6px rgba(220, 38, 38, 0.25);
    }
    .combo-item-card.is-dragging {
        opacity: 0.45 !important;
        border: 2px dashed #0284c7 !important;
        background-color: #f0f9ff !important;
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.15) !important;
    }
    .combo-item-card.is-selected {
        background-color: #eff6ff !important;
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 1px #3b82f6 !important;
    }
    .combo-item-card.duplicate-flash {
        animation: flashHighlight 0.6s ease;
    }
    @keyframes flashHighlight {
        0% { background-color: #fef08a; border-color: #eab308; }
        50% { background-color: #fef9c3; border-color: #facc15; }
        100% { background-color: #ffffff; border-color: #e2e8f0; }
    }
    .combo-drop-indicator {
        height: 4px;
        background: #0284c7;
        border-radius: 4px;
        margin: 4px 0;
        position: relative;
        box-shadow: 0 0 10px rgba(2, 132, 199, 0.7);
        pointer-events: none;
        transition: all 0.05s ease;
    }
    .combo-drop-indicator::before, .combo-drop-indicator::after {
        content: '';
        position: absolute;
        width: 8px;
        height: 8px;
        background: #0284c7;
        border-radius: 50%;
        top: -2px;
    }
    .combo-drop-indicator::before { left: -2px; }
    .combo-drop-indicator::after { right: -2px; }
    .item-select-chk {
        cursor: pointer;
        width: 1.15rem;
        height: 1.15rem;
        border-radius: 4px;
        border: 1.5px solid #94a3b8;
        flex-shrink: 0;
    }
    .item-select-chk:checked {
        background-color: #0284c7;
        border-color: #0284c7;
    }
    .empty-drop-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 10px;
        padding: 2.5rem 1.5rem;
        text-align: center;
        color: #94a3b8;
        background: #ffffff;
        transition: all 0.2s ease;
    }
    .filter-select {
        font-size: 0.825rem;
        border-color: #cbd5e1;
        border-radius: 8px;
        color: #475569;
        background-color: #ffffff;
    }
    .filter-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
    }
    .search-input-group {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        overflow: hidden;
        background: #ffffff;
        transition: all 0.2s ease;
    }
    .search-input-group:focus-within {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
    }
    .search-input-group .form-control {
        border: none;
        box-shadow: none;
        font-size: 0.875rem;
        padding: 0.55rem 0.75rem;
    }
    .search-input-group .input-group-text {
        border: none;
        background: transparent;
        color: #94a3b8;
        padding-left: 0.85rem;
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
    .odoo-title-input {
        font-size: 1.05rem;
        font-weight: 600;
        color: #0f172a !important;
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        padding: 0.3rem 0.75rem;
        transition: all 0.15s ease;
    }
    .odoo-title-input:focus {
        border-color: #91378e;
        box-shadow: 0 0 0 3px rgba(91, 77, 246, 0.12);
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
        cursor: pointer;
    }
    .odoo-img-box:hover {
        border-color: var(--bs-secondary, #6c757d);
        color: var(--bs-secondary, #6c757d);
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
    .odoo-field-control {
        width: 62%;
    }
    .odoo-field-control .form-control,
    .odoo-field-control .form-select {
        font-size: 0.8rem;
        padding: 0.2rem 0.4rem;
        height: auto;
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
    .eq-bottom-bar {
        flex-shrink: 0;
        padding-top: 0.4rem;
        margin-top: 0.25rem;
        border-top: 1px solid #e2e8f0;
    }
    .stock-toggle-group {
        display: inline-flex;
        gap: 0.5rem;
        align-items: center;
    }
    .stock-toggle-btn {
        border-radius: 6px !important;
        transition: all 0.15s ease-in-out;
        border: 1.5px solid #cbd5e1 !important;
        color: #64748b !important;
        background-color: #f8fafc !important;
        min-width: 48px;
        text-align: center;
        padding: 0.15rem 0.75rem !important;
        font-weight: 700 !important;
        font-size: 0.85rem !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        cursor: pointer;
    }
    .stock-toggle-btn:hover {
        border-color: #94a3b8 !important;
        color: #334155 !important;
        background-color: #f1f5f9 !important;
    }
    .btn-check:checked + .stock-toggle-yes {
        background-color: #10b981 !important;
        border-color: #059669 !important;
        color: #ffffff !important;
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.3) !important;
    }
    .btn-check:checked + .stock-toggle-no {
        background-color: #ef4444 !important;
        border-color: #dc2626 !important;
        color: #ffffff !important;
        box-shadow: 0 2px 6px rgba(239, 68, 68, 0.3) !important;
    }
</style>
@endpush

@section('content')
@php
    $canSeeCost = Auth::user()?->canSeeEquipmentCostPrices() ?? false;
    $canChangePrice = Auth::user()?->canChangeEquipmentPrice() ?? false;
@endphp
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 article-detail-shell">
        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm py-2 px-3 mb-2 small">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('equipments.update', $equipment) }}" method="POST" class="eq-form-layout" id="equipmentForm">
            @csrf
            @method('PUT')

            <!-- Odoo Sheet Container -->
            <div class="odoo-sheet article-detail-card">
                <!-- Top Bar inside sheet: Checkboxes & Image -->
                <div class="d-flex align-items-center justify-content-between mb-2 flex-shrink-0">
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <div class="form-check form-check-inline mb-0 small">
                            <input class="form-check-input" type="checkbox" id="sale_ok" name="sale_ok" value="1" {{ old('sale_ok', $equipment->sale_ok ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="sale_ok">{{ __('locale.equipment_form.lbl_sale_ok') }} ?</label>
                        </div>
                        <div class="form-check form-check-inline mb-0 small">
                            <input class="form-check-input" type="checkbox" id="recurring_invoice" name="recurring_invoice" value="1" {{ old('recurring_invoice', $equipment->recurring_invoice ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="recurring_invoice">{{ __('locale.equipment_form.lbl_recurring_invoice') }} ?</label>
                        </div>
                        <div class="form-check form-check-inline mb-0 small">
                            <input class="form-check-input" type="checkbox" id="purchase_ok" name="purchase_ok" value="1" {{ old('purchase_ok', $equipment->purchase_ok ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="purchase_ok">{{ __('locale.equipment_form.lbl_purchase_ok') }} ?</label>
                        </div>
                        <div class="form-check form-check-inline mb-0 small">
                            <input class="form-check-input" type="checkbox" id="is_combo" name="is_combo" value="1" {{ old('is_combo', $equipment->is_combo) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_combo">{{ __('locale.combo.is_combo') ?? 'Produit Combo' }}</label>
                        </div>
                        <div class="form-check form-check-inline mb-0 small">
                            <input class="form-check-input" type="checkbox" id="is_kit" name="is_kit" value="1" {{ old('is_kit', $equipment->is_kit) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_kit">{{ __('locale.kit.is_kit') ?? 'Kit / Nomenclature' }}</label>
                        </div>
                        <div class="form-check form-switch mb-0 small ms-2">
                            <input class="form-check-input" type="checkbox" id="closed" name="closed" value="1" {{ old('closed', $equipment->closed) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold text-danger" for="closed">{{ __('locale.equipment_form.lbl_closed_archived') }}</label>
                        </div>
                        @if($equipment->odoo_id)
                            @php
                                $odooProductUrl = config('services.odoo.url')
                                    ? rtrim(config('services.odoo.url'), '/') . '/web#id=' . $equipment->odoo_id . '&model=product.product&view_type=form'
                                    : null;
                            @endphp
                            <div class="d-flex align-items-center gap-1 small ms-1">
                                @if($odooProductUrl)
                                    <a href="{{ $odooProductUrl }}" target="_blank" rel="noopener" class="badge bg-secondary-subtle text-secondary border border-secondary-subtle font-monospace text-decoration-none" title="{{ __('locale.open_in_odoo') ?? 'Ouvrir dans Odoo' }}">
                                        <i class="bi bi-hash me-1"></i>{{ __('locale.equipment_form.lbl_odoo_id') }}: {{ $equipment->odoo_id }} <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                    </a>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle font-monospace"><i class="bi bi-hash me-1"></i>{{ __('locale.equipment_form.lbl_odoo_id') }}: {{ $equipment->odoo_id }}</span>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div>
                        <div class="odoo-img-box" style="width: 36px; height: 36px;" title="{{ __('locale.equipment_form.lbl_edit_image') }}">
                            <i class="bi bi-camera fs-5"></i>
                        </div>
                    </div>
                </div>

                <!-- Odoo Nav Tabs -->
                <ul class="nav nav-tabs odoo-nav-tabs article-tabs" id="equipmentTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general-tab-pane" type="button" role="tab">{{ __('locale.equipment_show.lbl_tab_general') }}</button>
                    </li>
                    <li class="nav-item {{ old('sale_ok', $equipment->sale_ok ?? true) ? '' : 'd-none' }}" role="presentation" id="sales-tab-nav" style="{{ old('sale_ok', $equipment->sale_ok ?? true) ? '' : 'display: none !important;' }}">
                        <button class="nav-link" id="sales-tab" data-bs-toggle="tab" data-bs-target="#sales-tab-pane" type="button" role="tab">{{ __('locale.equipment_show.lbl_tab_sales') }}</button>
                    </li>
                    <li class="nav-item {{ old('recurring_invoice', $equipment->recurring_invoice ?? false) ? '' : 'd-none' }}" role="presentation" id="subscription-tab-nav" style="{{ old('recurring_invoice', $equipment->recurring_invoice ?? false) ? '' : 'display: none !important;' }}">
                        <button class="nav-link" id="subscription-tab" data-bs-toggle="tab" data-bs-target="#subscription-tab-pane" type="button" role="tab">
                            <i class="bi bi-arrow-repeat me-1"></i>{{ __('locale.equipment_form.lbl_tab_subscription') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pricing-tab" data-bs-toggle="tab" data-bs-target="#pricing-tab-pane" type="button" role="tab">{{ __('locale.equipment_show.lbl_tab_pricing') }}</button>
                    </li>
                    <li class="nav-item {{ old('purchase_ok', $equipment->purchase_ok ?? true) ? '' : 'd-none' }}" role="presentation" id="purchase-tab-nav" style="{{ old('purchase_ok', $equipment->purchase_ok ?? true) ? '' : 'display: none !important;' }}">
                        <button class="nav-link" id="purchase-tab" data-bs-toggle="tab" data-bs-target="#purchase-tab-pane" type="button" role="tab">{{ __('locale.equipment_show.lbl_tab_purchase') }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory-tab-pane" type="button" role="tab">{{ __('locale.equipment_show.lbl_tab_inventory') }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="accounting-tab" data-bs-toggle="tab" data-bs-target="#accounting-tab-pane" type="button" role="tab">{{ __('locale.equipment_show.lbl_tab_accounting') }}</button>
                    </li>
                    <li class="nav-item {{ old('is_combo', $equipment->is_combo) ? '' : 'd-none' }}" role="presentation" id="combo-tab-nav" style="{{ old('is_combo', $equipment->is_combo) ? '' : 'display: none !important;' }}">
                        <button class="nav-link" id="combos-tab" data-bs-toggle="tab" data-bs-target="#combos-tab-pane" type="button" role="tab">
                            <i class="bi bi-layers me-1"></i>{{ __('locale.combo.tab_combos') ?? 'Combos' }} (<span id="tabEquipmentComboCount">{{ $equipment->combos->count() ?: $equipment->comboItems->count() }}</span>)
                        </button>
                    </li>
                    <li class="nav-item {{ old('is_kit', $equipment->is_kit) ? '' : 'd-none' }}" role="presentation" id="kit-tab-nav" style="{{ old('is_kit', $equipment->is_kit) ? '' : 'display: none !important;' }}">
                        <button class="nav-link" id="kit-tab" data-bs-toggle="tab" data-bs-target="#kit-tab-pane" type="button" role="tab">
                            <i class="bi bi-box-seam me-1"></i>{{ __('locale.kit.tab_kit') ?? 'Composants du Kit' }} (<span id="tabEquipmentKitCount">{{ $equipment->kitItems ? $equipment->kitItems->count() : 0 }}</span>)
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content article-detail-body" id="equipmentTabContent">
                    <!-- Tab 1: Informations Générales -->
                    <div class="tab-pane fade show active" id="general-tab-pane" role="tabpanel" tabindex="0">
                        <div class="row g-3">
                            <!-- Left Column -->
                            <div class="col-md-6 pe-md-4">
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_reference') }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" class="form-control form-control-sm" id="reference" name="reference" value="{{ old('reference', $equipment->reference) }}">
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_product_type') }}</div>
                                    <div class="odoo-field-control">
                                        <select class="form-select form-select-sm select2" id="equipment_type_id" name="equipment_type_id" required>
                                            <option value="">{{ __('locale.equipment_form.lbl_select_placeholder') }}</option>
                                            @foreach ($equipmentTypes as $type)
                                                <option value="{{ $type->id }}" {{ old('equipment_type_id', $equipment->equipment_type_id) == $type->id ? 'selected' : '' }}>
                                                    {{ $type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_invoice_policy') }}</div>
                                    <div class="odoo-field-control">
                                        <select class="form-select form-select-sm select2" id="invoice_policy" name="invoice_policy">
                                            <option value="order" {{ old('invoice_policy', $equipment->invoice_policy ?? 'order') == 'order' ? 'selected' : '' }}>{{ __('locale.equipment_form.lbl_invoice_policy_order') }}</option>
                                            <option value="delivery" {{ old('invoice_policy', $equipment->invoice_policy) == 'delivery' ? 'selected' : '' }}>{{ __('locale.equipment_form.lbl_invoice_policy_delivery') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_track_inventory') }}</div>
                                    <div class="odoo-field-control">
                                        @php
                                            $stockMgmtVal = old('stock_management', $equipment->stock_management ?? 'X');
                                            $isStockMgmtTrue = !in_array(strtoupper((string)$stockMgmtVal), ['-', '0', 'FALSE', 'N', 'NO', 'OFF']);
                                        @endphp
                                        <div class="stock-toggle-group" role="group" aria-label="{{ __('locale.equipment_form.lbl_track_inventory_aria') }}">
                                            <input type="radio" class="btn-check" name="stock_management" id="stock_mgmt_x" value="X" {{ $isStockMgmtTrue ? 'checked' : '' }}>
                                            <label class="btn stock-toggle-btn stock-toggle-yes" for="stock_mgmt_x">X</label>

                                            <input type="radio" class="btn-check" name="stock_management" id="stock_mgmt_dash" value="-" {{ !$isStockMgmtTrue ? 'checked' : '' }}>
                                            <label class="btn stock-toggle-btn stock-toggle-no" for="stock_mgmt_dash">-</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_available_qty') }}</div>
                                    <div class="odoo-field-control">
                                        <div class="input-group input-group-sm">
                                            <input type="number" class="form-control form-control-sm text-secondary fw-bold" id="current_stock" name="current_stock" value="{{ old('current_stock', $equipment->current_stock ?? 0) }}">
                                            <span class="input-group-text py-0">{{ $equipment->unit_of_measure ?? __('locale.equipment_form.lbl_unit_default') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_equipment_category') }}</div>
                                    <div class="odoo-field-control">
                                        <div class="row g-1">
                                            <div class="col-4">
                                                <select id="select-equipment-category" class="form-select form-select-sm select2">
                                                    <option value="">{{ __('locale.equipment_form.lbl_category_placeholder') }}</option>
                                                </select>
                                            </div>
                                            <div class="col-4">
                                                <select id="select-equipment-family" class="form-select form-select-sm select2" disabled>
                                                    <option value="">{{ __('locale.equipment_form.lbl_family_placeholder') }}</option>
                                                </select>
                                            </div>
                                            <div class="col-4">
                                                <select id="select-equipment-subfamily" class="form-select form-select-sm select2" disabled>
                                                    <option value="">{{ __('locale.equipment_form.lbl_subfamily_placeholder') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <input type="hidden" name="equipment_category_id" id="equipment_category_id" value="{{ old('equipment_category_id', $equipment->equipment_category_id) }}">
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_brand') }} <span class="text-danger">*</span></div>
                                    <div class="odoo-field-control">
                                        <select class="form-select form-select-sm select2" id="brand_id" name="brand_id" required>
                                            <option value="">{{ __('locale.equipment_form.lbl_brand_placeholder') }}</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}" {{ old('brand_id', $equipment->brand_id) == $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6 ps-md-4">
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label d-flex align-items-center justify-content-between">
                                        <span>{{ __('locale.equipment_form.lbl_sale_price') }}</span>
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle combo-forced-price-badge" style="display: none; font-size: 0.7rem;" title="{{ __('locale.combo.manually_modified') }}"><i class="bi bi-pencil-square me-1"></i>{{ __('locale.combo.forced_price_badge') }}</span>
                                    </div>
                                    <div class="odoo-field-control">
                                        <div class="input-group input-group-sm">
                                            <input type="number" step="0.01" class="form-control form-control-sm fw-bold {{ !$canChangePrice ? 'bg-light text-muted' : '' }}" id="price_per_unit" name="price_per_unit" value="{{ old('price_per_unit', $equipment->price_per_unit) }}" required {{ !$canChangePrice ? 'readonly' : '' }} title="{{ !$canChangePrice ? (__('locale.kit.locked_price_tooltip') ?? 'Vous n\'avez pas la permission de modifier ce prix') : '' }}">
                                            <span class="input-group-text py-0">{{ __('locale.currency') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_customer_taxes') }}</div>
                                    <div class="odoo-field-control">
                                        <select name="taxes_id[]" id="taxes_id" class="form-select form-select-sm select2" multiple>
                                            @foreach($customerTaxes as $tax)
                                                <option value="{{ $tax->odoo_id }}" {{ in_array($tax->odoo_id, (array)old('taxes_id', $equipment->taxes_id ?? [])) ? 'selected' : '' }}>
                                                    {{ $tax->name }} ({{ $tax->company_name ?? 'Global' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if($canSeeCost)
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label d-flex align-items-center justify-content-between">
                                        <span>{{ __('locale.equipment_form.lbl_cost') }}</span>
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle combo-forced-cost-badge" style="display: none; font-size: 0.7rem;" title="{{ __('locale.combo.manually_modified') }}"><i class="bi bi-pencil-square me-1"></i>{{ __('locale.combo.forced_price_badge') }}</span>
                                    </div>
                                    <div class="odoo-field-control">
                                        <div class="input-group input-group-sm">
                                            <input type="number" step="0.01" class="form-control form-control-sm" id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $equipment->purchase_price) }}">
                                            <span class="input-group-text py-0">{{ __('locale.currency') }}</span>
                                            <button class="btn btn-outline-secondary btn-double-price fw-semibold" type="button" title="{{ __('locale.equipment_form.btn_double_price') ?? 'Multiplier par 2 pour définir le prix de vente' }}" {{ !$canChangePrice ? 'disabled' : '' }}>x2</button>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <input type="hidden" id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $equipment->purchase_price) }}">
                                @endif
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_supplier_taxes') }}</div>
                                    <div class="odoo-field-control">
                                        <select name="supplier_taxes_id[]" id="supplier_taxes_id" class="form-select form-select-sm select2" multiple>
                                            @foreach($supplierTaxes as $tax)
                                                <option value="{{ $tax->odoo_id }}" {{ in_array($tax->odoo_id, (array)old('supplier_taxes_id', $equipment->supplier_taxes_id ?? [])) ? 'selected' : '' }}>
                                                    {{ $tax->name }} ({{ $tax->company_name ?? 'Global' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_odoo_category') }}</div>
                                    <div class="odoo-field-control">
                                        <select class="form-select form-select-sm select2" id="odoo_category_id" name="odoo_category_id">
                                            <option value="">{{ __('locale.equipment_form.lbl_odoo_category_placeholder') }}</option>
                                            @foreach ($odooCategories as $category)
                                                <option value="{{ $category->id }}" {{ old('odoo_category_id', $equipment->odoo_category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_odoo_id') }}</div>
                                    <div class="odoo-field-control">
                                        <div class="input-group input-group-sm">
                                            <input type="number" class="form-control form-control-sm font-monospace fw-semibold" id="odoo_id" name="odoo_id" value="{{ old('odoo_id', $equipment->odoo_id) }}" placeholder="ex. 1234">
                                            @if($equipment->odoo_id && config('services.odoo.url'))
                                                @php
                                                    $odooProductUrl = rtrim(config('services.odoo.url'), '/') . '/web#id=' . $equipment->odoo_id . '&model=product.product&view_type=form';
                                                @endphp
                                                <a href="{{ $odooProductUrl }}" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm py-0 d-flex align-items-center" title="{{ __('locale.open_in_odoo') ?? 'Ouvrir dans Odoo' }}">
                                                    <i class="bi bi-box-arrow-up-right"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_barcode') }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" class="form-control form-control-sm" id="barcode" name="barcode" value="{{ old('barcode', $equipment->barcode) }}">
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_company') }}</div>
                                    <div class="odoo-field-control">
                                        <select class="form-select form-select-sm select2" id="company_id" name="company_id">
                                            <option value="">{{ __('locale.equipment_form.lbl_company_placeholder') }}</option>
                                            @foreach ($companies as $company)
                                                <option value="{{ $company->id }}" {{ old('company_id', $equipment->company_id) == $company->id ? 'selected' : '' }}>
                                                    {{ $company->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 2: Ventes -->
                    <div class="tab-pane fade" id="sales-tab-pane" role="tabpanel" tabindex="0">
                        <div class="row g-3">
                            <div class="col-md-6 pe-md-4">
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_line_discount') }}</div>
                                    <div class="odoo-field-control">
                                        <input class="form-check-input" type="checkbox" id="is_line_discountable" name="is_line_discountable" value="1" {{ old('is_line_discountable', $equipment->is_line_discountable) ? 'checked' : '' }}>
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_footer_discount') }}</div>
                                    <div class="odoo-field-control">
                                        <input class="form-check-input" type="checkbox" id="is_footer_discountable" name="is_footer_discountable" value="1" {{ old('is_footer_discountable', $equipment->is_footer_discountable) ? 'checked' : '' }}>
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_discountable') }}</div>
                                    <div class="odoo-field-control">
                                        <input class="form-check-input" type="checkbox" id="is_discountable" name="is_discountable" value="1" {{ old('is_discountable', $equipment->is_discountable) ? 'checked' : '' }}>
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_modifiable') }}</div>
                                    <div class="odoo-field-control">
                                        <input class="form-check-input" type="checkbox" id="modifiable" name="modifiable" value="1" {{ old('modifiable', $equipment->modifiable) ? 'checked' : '' }}>
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_discount_rate') }}</div>
                                    <div class="odoo-field-control">
                                        <input type="number" step="0.01" class="form-control form-control-sm" id="discount" name="discount" value="{{ old('discount', $equipment->discount) }}">
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_customer_lead') }}</div>
                                    <div class="odoo-field-control">
                                        <div class="input-group input-group-sm">
                                            <input type="number" class="form-control form-control-sm" id="customer_lead" name="customer_lead" value="{{ old('customer_lead', $equipment->customer_lead) }}">
                                            <span class="input-group-text py-0">{{ __('locale.equipment_form.lbl_days') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 ps-md-4">
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_suggested_substitutes') }}</div>
                                    <div class="odoo-field-control">
                                        <select class="form-control form-control-sm select2-ajax" id="substitute_ids" name="substitute_ids[]" multiple>
                                            @foreach($equipment->substitutes as $substitute)
                                                <option value="{{ $substitute->id }}" selected>
                                                    {{ $substitute->name }} {{ $substitute->reference ? '(' . $substitute->reference . ')' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="odoo-field-row align-items-start">
                                    <div class="odoo-field-label pt-1">{{ __('locale.equipment_form.lbl_sale_description') }}</div>
                                    <div class="odoo-field-control">
                                        <textarea class="form-control form-control-sm" id="description_sale" name="description_sale" rows="3" placeholder="{{ __('locale.equipment_form.lbl_sale_description_placeholder') }}">{{ old('description_sale', $equipment->description_sale) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab: Tarifs récurrents -->
                    <div class="tab-pane fade" id="subscription-tab-pane" role="tabpanel" tabindex="0">
                        <div class="table-responsive mb-2">
                            <table class="table table-sm table-hover align-middle mb-0" id="recurringRulesTable" style="font-size: 0.82rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th style="min-width: 200px;">{{ __('locale.equipment_form.lbl_recurring_plan_col') }}</th>
                                        <th style="min-width: 180px;">{{ __('locale.equipment_form.lbl_recurring_pricelist_col') }}</th>
                                        <th style="min-width: 130px;">{{ __('locale.equipment_form.lbl_recurring_price_col') }}</th>
                                        <th style="min-width: 120px;">{{ __('locale.equipment_form.lbl_recurring_date_start_col') }}</th>
                                        <th style="min-width: 120px;">{{ __('locale.equipment_form.lbl_recurring_date_end_col') }}</th>
                                        <th style="min-width: 140px;">{{ __('locale.equipment_form.lbl_recurring_company_col') }}</th>
                                        <th style="width: 40px; text-align: center;"></th>
                                    </tr>
                                </thead>
                                <tbody id="recurringRulesBody">
                                    @php
                                        $existingRules = old('recurring_rules', $equipment->recurringRules ?? collect());
                                    @endphp
                                    @foreach($existingRules as $idx => $rule)
                                        @php
                                            $ruleId = is_array($rule) ? ($rule['id'] ?? null) : $rule->id;
                                            $planId = is_array($rule) ? ($rule['recurring_plan_id'] ?? null) : ($rule->recurring_plan_id ?? null);
                                            $plId = is_array($rule) ? ($rule['pricelist_id'] ?? null) : ($rule->pricelist_id ?? null);
                                            $fixedPrice = is_array($rule) ? ($rule['fixed_price'] ?? '') : ($rule->fixed_price ?? '');
                                            $dateStart = is_array($rule) ? ($rule['date_start'] ?? '') : ($rule->date_start ? \Carbon\Carbon::parse($rule->date_start)->format('Y-m-d') : '');
                                            $dateEnd = is_array($rule) ? ($rule['date_end'] ?? '') : ($rule->date_end ? \Carbon\Carbon::parse($rule->date_end)->format('Y-m-d') : '');
                                            $compVal = is_array($rule) ? ($rule['company_id'] ?? null) : ($rule->company_id ?? null);
                                        @endphp
                                        <tr class="recurring-rule-row" data-index="{{ $idx }}">
                                            <td>
                                                @if($ruleId)
                                                    <input type="hidden" name="recurring_rules[{{ $idx }}][id]" value="{{ $ruleId }}">
                                                @endif
                                                <select name="recurring_rules[{{ $idx }}][recurring_plan_id]" class="form-select form-select-sm" required>
                                                    <option value="">{{ __('locale.equipment_form.lbl_select_plan') }}</option>
                                                    @foreach($recurringPlans as $rp)
                                                        <option value="{{ $rp->id }}" {{ (string)$planId === (string)$rp->id ? 'selected' : '' }}>
                                                            {{ $rp->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="recurring_rules[{{ $idx }}][pricelist_id]" class="form-select form-select-sm">
                                                    <option value="">{{ __('locale.equipment_form.lbl_all_pricelists') }}</option>
                                                    @foreach($pricelists as $pl)
                                                        <option value="{{ $pl->id }}" {{ (string)$plId === (string)$pl->id ? 'selected' : '' }}>
                                                            {{ $pl->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="number" step="0.01" min="0" name="recurring_rules[{{ $idx }}][fixed_price]" class="form-control form-control-sm text-end" value="{{ $fixedPrice }}" placeholder="0.00">
                                                    <span class="input-group-text py-0 px-1 small">{{ __('locale.currency') }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="date" name="recurring_rules[{{ $idx }}][date_start]" class="form-control form-control-sm" value="{{ $dateStart }}">
                                            </td>
                                            <td>
                                                <input type="date" name="recurring_rules[{{ $idx }}][date_end]" class="form-control form-control-sm" value="{{ $dateEnd }}">
                                            </td>
                                            <td>
                                                <select name="recurring_rules[{{ $idx }}][company_id]" class="form-select form-select-sm">
                                                    <option value="">{{ __('locale.equipment_form.lbl_all_companies') }}</option>
                                                    @foreach($companies as $c)
                                                        <option value="{{ $c->id }}" {{ (string)$compVal === (string)$c->id ? 'selected' : '' }}>
                                                            {{ $c->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-link text-danger p-0 delete-recurring-rule-btn" title="Supprimer la ligne">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Add line button matching Odoo ("Ajouter une ligne") -->
                        <div class="mt-2 mb-4">
                            <a href="javascript:void(0)" class="text-primary text-decoration-none fw-semibold small d-inline-flex align-items-center" id="addRecurringRuleBtn">
                                <i class="bi bi-plus-lg me-1"></i>{{ __('locale.equipment_form.lbl_add_recurring_line') }}
                            </a>
                        </div>

                        <!-- OPTIONS Section (matches Odoo) -->
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="text-uppercase fw-bold text-secondary mb-3 small" style="letter-spacing: 0.5px;">
                                OPTIONS
                            </h6>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="allow_one_time_sale" name="allow_one_time_sale" value="1" {{ old('allow_one_time_sale', $equipment->allow_one_time_sale ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="allow_one_time_sale">
                                    {{ __('locale.equipment_form.lbl_allow_one_time_sale_q') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 3: Prix -->
                    <div class="tab-pane fade" id="pricing-tab-pane" role="tabpanel" tabindex="0">
                        <div class="row g-3">
                            <div class="col-md-6 pe-md-4">
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label d-flex align-items-center justify-content-between">
                                        <span>{{ __('locale.equipment_form.lbl_sale_price_excl_tax') }}</span>
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle combo-forced-price-badge" style="display: none; font-size: 0.7rem;" title="{{ __('locale.combo.manually_modified') }}"><i class="bi bi-pencil-square me-1"></i>{{ __('locale.combo.forced_price_badge') }}</span>
                                    </div>
                                    <div class="odoo-field-control">
                                        <div class="input-group input-group-sm">
                                            <input type="number" step="0.01" class="form-control form-control-sm fw-bold {{ !$canChangePrice ? 'bg-light text-muted' : '' }}" id="price_per_unit_tab" value="{{ old('price_per_unit', $equipment->price_per_unit) }}" {{ !$canChangePrice ? 'readonly' : '' }}>
                                            <span class="input-group-text py-0">{{ __('locale.currency') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_recommended_sale_price') }}</div>
                                    <div class="odoo-field-control">
                                        <div class="input-group input-group-sm">
                                            <input type="number" step="0.01" class="form-control form-control-sm" id="price_excl_tax" name="price_excl_tax" value="{{ old('price_excl_tax', $equipment->price_excl_tax) }}">
                                            <span class="input-group-text py-0">{{ __('locale.currency') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_estimated_incl_tax') }}</div>
                                    <div class="odoo-field-control">
                                        <div class="input-group input-group-sm">
                                            <input type="number" step="0.01" class="form-control form-control-sm" id="price_incl_tax" name="price_incl_tax" value="{{ old('price_incl_tax', $equipment->price_incl_tax) }}">
                                            <span class="input-group-text py-0">{{ __('locale.currency') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 ps-md-4">
                                @if($canSeeCost)
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label d-flex align-items-center justify-content-between">
                                        <span>{{ __('locale.equipment_form.lbl_purchase_cost') }}</span>
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle combo-forced-cost-badge" style="display: none; font-size: 0.7rem;" title="{{ __('locale.combo.manually_modified') }}"><i class="bi bi-pencil-square me-1"></i>{{ __('locale.combo.forced_price_badge') }}</span>
                                    </div>
                                    <div class="odoo-field-control">
                                        <div class="input-group input-group-sm">
                                            <input type="number" step="0.01" class="form-control form-control-sm" id="purchase_price_tab" value="{{ old('purchase_price', $equipment->purchase_price) }}">
                                            <span class="input-group-text py-0">{{ __('locale.currency') }}</span>
                                            <button class="btn btn-outline-secondary btn-double-price fw-semibold" type="button" title="{{ __('locale.equipment_form.btn_double_price') ?? 'Multiplier par 2 pour définir le prix de vente' }}" {{ !$canChangePrice ? 'disabled' : '' }}>x2</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_odp_purchase_price') }}</div>
                                    <div class="odoo-field-control">
                                        <div class="input-group input-group-sm">
                                            <input type="number" step="0.01" class="form-control form-control-sm" id="odp_purchase_price" name="odp_purchase_price" value="{{ old('odp_purchase_price', $equipment->odp_purchase_price ?? $equipment->purchase_price) }}">
                                            <span class="input-group-text py-0">{{ __('locale.currency') }}</span>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <input type="hidden" id="purchase_price_tab" value="{{ old('purchase_price', $equipment->purchase_price) }}">
                                @endif
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_ga_pmap') }}</div>
                                    <div class="odoo-field-control">
                                        <div class="input-group input-group-sm">
                                            <input type="number" step="0.01" class="form-control form-control-sm" id="prix_moyen_achat_pondere" name="prix_moyen_achat_pondere" value="{{ old('prix_moyen_achat_pondere', $equipment->prix_moyen_achat_pondere ?? $equipment->ga_pmap) }}">
                                            <span class="input-group-text py-0">{{ __('locale.currency') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_ga_pmrp') }}</div>
                                    <div class="odoo-field-control">
                                        <div class="input-group input-group-sm">
                                            <input type="number" step="0.01" class="form-control form-control-sm" id="prix_moyen_revient_pondere" name="prix_moyen_revient_pondere" value="{{ old('prix_moyen_revient_pondere', $equipment->prix_moyen_revient_pondere ?? $equipment->ga_pmrp) }}">
                                            <span class="input-group-text py-0">{{ __('locale.currency') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 4: Achats -->
                    <div class="tab-pane fade" id="purchase-tab-pane" role="tabpanel" tabindex="0">
                        @php
                            $supplierRefs = old('supplier_references');
                            if ($supplierRefs === null) {
                                $supplierRefs = [];
                                foreach($equipment->suppliers as $sup) {
                                    $supplierRefs[] = [
                                        'supplier_id' => $sup->id,
                                        'reference' => $sup->pivot->supplier_reference,
                                        'product_name' => $sup->pivot->product_name,
                                        'date_start' => $sup->pivot->date_start ? \Carbon\Carbon::parse($sup->pivot->date_start)->format('Y-m-d') : '',
                                        'date_end' => $sup->pivot->date_end ? \Carbon\Carbon::parse($sup->pivot->date_end)->format('Y-m-d') : '',
                                        'company_id' => $sup->pivot->company_id,
                                        'min_qty' => $sup->pivot->min_qty,
                                        'product_uom' => $sup->pivot->product_uom,
                                        'price' => $sup->pivot->price,
                                        'discount' => $sup->pivot->discount,
                                        'currency' => $sup->pivot->currency,
                                        'delay' => $sup->pivot->delay,
                                    ];
                                }
                            }
                        @endphp
                        <div class="table-responsive mb-2">
                            <table class="table table-sm table-hover align-middle mb-0 text-nowrap" style="font-size: 0.82rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th style="min-width: 220px;">{{ __('locale.equipment_form.lbl_supplier_col') }}</th>
                                        <th style="min-width: 150px;">{{ __('locale.equipment_form.lbl_supplier_product_name_col') }}</th>
                                        <th style="min-width: 150px;">{{ __('locale.equipment_form.lbl_supplier_product_code_col') }}</th>
                                        <th style="min-width: 110px;">{{ __('locale.equipment_form.lbl_date_start_col') }}</th>
                                        <th style="min-width: 110px;">{{ __('locale.equipment_form.lbl_date_end_col') }}</th>
                                        <th style="min-width: 110px;">{{ __('locale.equipment_form.lbl_company_col') }}</th>
                                        <th style="min-width: 80px;">{{ __('locale.equipment_form.lbl_qty_col') }}</th>
                                        <th style="min-width: 90px;">{{ __('locale.equipment_form.lbl_unit_col') }}</th>
                                        <th style="min-width: 90px;">{{ __('locale.equipment_form.lbl_unit_price_col') }}</th>
                                        <th style="min-width: 80px;">{{ __('locale.equipment_form.lbl_discount_col') }}</th>
                                        <th style="min-width: 80px;">{{ __('locale.equipment_form.lbl_currency_col') }}</th>
                                        <th style="min-width: 70px;">{{ __('locale.equipment_form.lbl_delay_col') }}</th>
                                        <th style="width: 35px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="reseller-references-container">
                                    @foreach($supplierRefs as $index => $ref)
                                        <tr class="reseller-ref-row">
                                            <td>
                                                <select name="supplier_references[{{ $index }}][supplier_id]" class="form-select form-select-sm select2-supplier-ref p-1" style="font-size:0.8rem;">
                                                    <option value="">{{ __('locale.equipment_form.lbl_select_placeholder') }}</option>
                                                    @foreach($suppliers as $supplier)
                                                        <option value="{{ $supplier->id }}" {{ ($ref['supplier_id'] ?? '') == $supplier->id ? 'selected' : '' }}>
                                                            {{ $supplier->name }}{{ $supplier->company ? ' (' . $supplier->company->name . ')' : '' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="text" name="supplier_references[{{ $index }}][product_name]" class="form-control form-control-sm p-1" style="font-size:0.8rem;" value="{{ $ref['product_name'] ?? '' }}"></td>
                                            <td><input type="text" name="supplier_references[{{ $index }}][reference]" class="form-control form-control-sm p-1" style="font-size:0.8rem;" value="{{ $ref['reference'] ?? '' }}"></td>
                                             <td><input type="date" name="supplier_references[{{ $index }}][date_start]" class="form-control form-control-sm p-1" style="font-size:0.8rem;" value="{{ $ref['date_start'] ?? '' }}"></td>
                                            <td><input type="date" name="supplier_references[{{ $index }}][date_end]" class="form-control form-control-sm p-1" style="font-size:0.8rem;" value="{{ $ref['date_end'] ?? '' }}"></td>
                                            <td>
                                                <select name="supplier_references[{{ $index }}][company_id]" class="form-select form-select-sm p-1" style="font-size:0.8rem;">
                                                    <option value="">{{ __('locale.equipment_form.lbl_visible_all_option') }}</option>
                                                    @foreach($companies as $company)
                                                        <option value="{{ $company->id }}" {{ ($ref['company_id'] ?? $equipment->company_id ?? '') == $company->id ? 'selected' : '' }}>
                                                            {{ $company->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="number" step="0.01" name="supplier_references[{{ $index }}][min_qty]" class="form-control form-control-sm p-1" style="font-size:0.8rem;" value="{{ $ref['min_qty'] ?? '1.00' }}"></td>
                                            <td><input type="text" name="supplier_references[{{ $index }}][product_uom]" class="form-control form-control-sm p-1" style="font-size:0.8rem;" value="{{ $ref['product_uom'] ?? __('locale.equipment_form.lbl_unit_default') }}"></td>
                                            <td><input type="number" step="0.01" name="supplier_references[{{ $index }}][price]" class="form-control form-control-sm p-1" style="font-size:0.8rem;" value="{{ $ref['price'] ?? '0.00' }}"></td>
                                            <td><input type="number" step="0.01" name="supplier_references[{{ $index }}][discount]" class="form-control form-control-sm p-1" style="font-size:0.8rem;" value="{{ $ref['discount'] ?? '0.00' }}"></td>
                                            <td><input type="text" name="supplier_references[{{ $index }}][currency]" class="form-control form-control-sm p-1" style="font-size:0.8rem;" value="{{ $ref['currency'] ?? 'EUR' }}"></td>
                                            <td><input type="number" name="supplier_references[{{ $index }}][delay]" class="form-control form-control-sm p-1" style="font-size:0.8rem;" value="{{ $ref['delay'] ?? '1' }}"></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-link text-danger remove-reseller-ref p-0">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mb-4">
                            <a href="javascript:void(0)" class="text-success text-decoration-none fw-semibold small" id="add-reseller-ref">
                                {{ __('locale.equipment_form.lbl_add_line') }}
                            </a>
                        </div>

                        <hr class="my-4" style="opacity: 0.15;">

                        <div class="row g-4">
                            <!-- Column 1: FACTURES FOURNISSEURS -->
                            <div class="col-md-6 pe-md-4">
                                <h6 class="fw-bold text-uppercase border-bottom pb-2 text-secondary mb-3 small" style="letter-spacing: 0.5px;">{{ __('locale.equipment_form.lbl_supplier_invoices') }}</h6>
                                
                                <div class="d-flex align-items-start mb-3">
                                    <div class="me-3 text-nowrap small fw-semibold pt-1" style="min-width: 140px;">
                                        {{ __('locale.equipment_form.lbl_control_policy') }} <i class="bi bi-question-circle text-muted ms-1 small" data-bs-toggle="tooltip" title="{{ __('locale.equipment_form.lbl_control_policy') }}"></i>
                                    </div>
                                    <div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="purchase_control_policy" id="control_policy_order" value="order" {{ old('purchase_control_policy', $equipment->purchase_control_policy ?? 'receive') == 'order' ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="control_policy_order">
                                                {{ __('locale.equipment_form.lbl_control_on_order') }}
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="purchase_control_policy" id="control_policy_receive" value="receive" {{ old('purchase_control_policy', $equipment->purchase_control_policy ?? 'receive') == 'receive' ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="control_policy_receive">
                                                {{ __('locale.equipment_form.lbl_control_on_received') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Column 2: DESCRIPTION DES ACHATS & AVERTISSEMENT -->
                            <div class="col-md-6 ps-md-4">
                                <h6 class="fw-bold text-uppercase border-bottom pb-2 text-secondary mb-3 small" style="letter-spacing: 0.5px;">{{ __('locale.equipment_form.lbl_purchase_description') }}</h6>
                                <div class="mb-4">
                                    <textarea class="form-control form-control-sm" id="description_purchase" name="description_purchase" rows="2" placeholder="{{ __('locale.equipment_form.lbl_purchase_description_placeholder') }}">{{ old('description_purchase', $equipment->description_purchase) }}</textarea>
                                    <div class="form-text text-muted small mt-1">{{ __('locale.equipment_form.lbl_purchase_description_note') }}</div>
                                </div>

                                <h6 class="fw-bold text-uppercase border-bottom pb-2 text-secondary mb-3 small" style="letter-spacing: 0.5px;">{{ __('locale.equipment_form.lbl_purchase_warning') }}</h6>
                                <div>
                                    <input type="text" class="form-control form-control-sm" id="purchase_warning" name="purchase_warning" value="{{ old('purchase_warning', $equipment->purchase_warning) }}" placeholder="{{ __('locale.equipment_form.lbl_purchase_warning_placeholder') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 5: Inventaire -->
                    <div class="tab-pane fade" id="inventory-tab-pane" role="tabpanel" tabindex="0">
                        <div class="row g-3">
                            <div class="col-md-6 pe-md-4">
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_lot_tracking') }}</div>
                                    <div class="odoo-field-control">
                                        <select class="form-select form-select-sm select2" id="lot_management" name="lot_management">
                                            <option value="none" {{ old('lot_management', $equipment->lot_management) == 'none' ? 'selected' : '' }}>{{ __('locale.equipment_form.lbl_lot_none') }}</option>
                                            <option value="lot" {{ old('lot_management', $equipment->lot_management) == 'lot' ? 'selected' : '' }}>{{ __('locale.equipment_form.lbl_lot_by_lot') }}</option>
                                            <option value="serial" {{ old('lot_management', $equipment->lot_management) == 'serial' ? 'selected' : '' }}>{{ __('locale.equipment_form.lbl_lot_by_serial') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_minimum_qty') }}</div>
                                    <div class="odoo-field-control">
                                        <input type="number" class="form-control form-control-sm" id="minimum_quantity" name="minimum_quantity" value="{{ old('minimum_quantity', $equipment->minimum_quantity) }}">
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_packaging') }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" class="form-control form-control-sm" id="packaging" name="packaging" value="{{ old('packaging', $equipment->packaging) }}">
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_pcb') }}</div>
                                    <div class="odoo-field-control">
                                        <input type="number" class="form-control form-control-sm" id="pcb" name="pcb" value="{{ old('pcb', $equipment->pcb) }}">
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_unit_of_measure') }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" class="form-control form-control-sm" id="unit_of_measure" name="unit_of_measure" value="{{ old('unit_of_measure', $equipment->unit_of_measure) }}" placeholder="{{ __('locale.equipment_form.lbl_unit_default') }}">
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_purchase_unit_of_measure') }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" class="form-control form-control-sm" id="purchase_unit_of_measure" name="purchase_unit_of_measure" value="{{ old('purchase_unit_of_measure', $equipment->purchase_unit_of_measure) }}" placeholder="{{ __('locale.equipment_form.lbl_unit_default') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 ps-md-4">
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_weight_kg') }}</div>
                                    <div class="odoo-field-control">
                                        <input type="number" step="0.001" class="form-control form-control-sm" id="weight" name="weight" value="{{ old('weight', $equipment->weight) }}">
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_volume_m3') }}</div>
                                    <div class="odoo-field-control">
                                        <input type="number" step="0.001" class="form-control form-control-sm" id="volume" name="volume" value="{{ old('volume', $equipment->volume) }}">
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_customs_code') }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" class="form-control form-control-sm" id="customs_code" name="customs_code" value="{{ old('customs_code', $equipment->customs_code) }}">
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_country_of_origin') }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" class="form-control form-control-sm" id="country_of_origin" name="country_of_origin" value="{{ old('country_of_origin', $equipment->country_of_origin) }}">
                                    </div>
                                </div>
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_warranty') }}</div>
                                    <div class="odoo-field-control">
                                        <input type="text" class="form-control form-control-sm" id="warranty" name="warranty" value="{{ old('warranty', $equipment->warranty) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 6: Comptabilité -->
                    <div class="tab-pane fade" id="accounting-tab-pane" role="tabpanel" tabindex="0">
                        <div class="row g-3">
                            <div class="col-md-6 pe-md-4">
                                <div class="odoo-field-row">
                                    <div class="odoo-field-label">{{ __('locale.equipment_form.lbl_accounting_family') }}</div>
                                    <div class="odoo-field-control">
                                        <select name="accounting_family_id" id="accounting_family_id" class="form-select form-select-sm select2">
                                            <option value="">{{ __('locale.equipment_form.lbl_select_placeholder') }}</option>
                                            @foreach($accountingFamilies as $family)
                                                <option value="{{ $family->id }}" {{ old('accounting_family_id', $equipment->accounting_family_id) == $family->id ? 'selected' : '' }}>
                                                    {{ $family->family_name }} ({{ $family->code_cegid }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 7: Combos / Produits associés -->
                    <div class="tab-pane fade" id="combos-tab-pane" role="tabpanel" tabindex="0">
                        <div class="module-form-card card border shadow-sm mb-3">
                            <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-layers text-primary fs-5"></i>
                                    <h6 class="fw-bold mb-0 text-dark">
                                        {{ __('locale.combo.associated_combos_odoo') }}
                                    </h6>
                                </div>
                                <a href="{{ route('combos.create') }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-plus-lg me-1"></i>{{ __('locale.combo.new_combo') }}
                                </a>
                            </div>
                            <div class="card-body p-3">
                                <p class="text-muted small mb-2">{{ __('locale.combo.associated_combos_desc') }}</p>
                                <div class="w-100">
                                    @php
                                        $attachedComboIds = old('combo_ids', $equipment->combos->pluck('id')->toArray());
                                    @endphp
                                    <select class="form-select form-select-sm select2" id="combo_ids" name="combo_ids[]" multiple="multiple" data-placeholder="{{ __('locale.combo.select_combos') }}" style="width: 100%;">
                                        @if(isset($combos))
                                            @foreach($combos as $combo)
                                                <option value="{{ $combo->id }}" {{ in_array($combo->id, $attachedComboIds) ? 'selected' : '' }}>
                                                    {{ $combo->name }} ({{ $combo->comboItems->count() }} options{{ $combo->odoo_id ? ' · Odoo #' . $combo->odoo_id : '' }})
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    <i class="bi bi-info-circle me-1"></i>{{ __('locale.combo.associated_combos_notice') }}
                                </small>
                            </div>
                        </div>

                        <div class="row g-3 py-1 align-items-stretch">
                            <!-- Left Column: Produits associés -->
                            <div class="col-lg-6 d-flex flex-column">
                                <div class="dual-list-card w-100 flex-grow-1">
                                    <div class="dual-list-card-header">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-link-45deg text-primary fs-5"></i>
                                            <h6 class="fw-bold mb-0 text-dark">
                                                {{ __('locale.combo.associated_products') ?? 'Produits associés' }}
                                            </h6>
                                        </div>
                                        <span class="badge rounded-pill badge-associated d-inline-flex align-items-center">
                                            <span id="equipmentComboAssociatedCount">{{ $equipment->comboItems->count() }}</span>&nbsp;{{ __('locale.combo.associated_count') }}
                                        </span>
                                    </div>

                                    <div class="dual-list-scroll-area border-0 rounded-0" id="comboItemsScrollArea" style="display: flex; flex-direction: column;">
                                        <div id="comboItemsContainer" class="d-flex flex-column gap-1" style="flex: 1 1 auto; min-height: 100%;">
                                            @foreach ($equipment->comboItems as $idx => $cItem)
                                                @php
                                                    $child = $cItem->childEquipment;
                                                    $ref = $child ? ($child->reference ?? 'N/A') : 'N/A';
                                                    $name = $child ? $child->name : __('locale.combo.item_not_found');
                                                    $unitPrice = $cItem->extra_price > 0 ? $cItem->extra_price : ($child ? ($child->price_per_unit ?? $child->price_excl_tax ?? 0) : 0);
                                                    $childCostPrice = $child ? ($child->purchase_price ?? 0) : 0;
                                                    $qty = $cItem->quantity ?? 1;
                                                @endphp
                                                <div class="combo-item-card draggable-item combo-item-row"
                                                    draggable="true"
                                                    data-id="{{ $cItem->child_equipment_id }}"
                                                    data-item-id="{{ $cItem->id }}"
                                                    data-ref="{{ $ref }}"
                                                    data-name="{{ $name }}"
                                                    data-price="{{ $cItem->extra_price }}"
                                                    data-unit-price="{{ $unitPrice }}"
                                                    data-cost-price="{{ number_format($childCostPrice, 2, '.', '') }}"
                                                    data-quantity="{{ $qty }}">
                                                    <div class="card-drag-handle" title="{{ __('locale.combo.drag_to_reorder') }}">
                                                        <i class="bi bi-grip-vertical"></i>
                                                        <input type="hidden" class="input-combo-item-id" name="combo_items[{{ $idx }}][id]" value="{{ $cItem->id }}">
                                                        <input type="hidden" class="input-combo-equipment-id" name="combo_items[{{ $idx }}][child_equipment_id]" value="{{ $cItem->child_equipment_id }}">
                                                        <input type="hidden" class="input-combo-sequence" name="combo_items[{{ $idx }}][sequence]" value="{{ $cItem->sequence ?? $idx }}">
                                                    </div>
                                                    <div class="card-item-main d-flex align-items-center gap-2">
                                                        <span class="item-ref-badge" title="{{ $ref }}">{{ $ref }}</span>
                                                        <span class="card-item-title" title="{{ $name }}">{{ $name }}</span>
                                                    </div>
                                                    <div class="card-item-qty">
                                                        <div class="input-group input-group-sm">
                                                            <span class="input-group-text bg-light text-muted px-1" style="font-size: 0.72rem;">{{ __('locale.qty') }}</span>
                                                            <input type="number" step="1" min="1" class="form-control form-control-sm text-center fw-bold input-combo-quantity" name="combo_items[{{ $idx }}][quantity]" value="{{ old('combo_items.'.$idx.'.quantity', $qty) }}" data-prev-qty="{{ old('combo_items.'.$idx.'.quantity', $qty) }}">
                                                        </div>
                                                    </div>
                                                    <div class="card-item-price">
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" min="0" class="form-control form-control-sm text-end fw-bold input-combo-extra-price" name="combo_items[{{ $idx }}][extra_price]" value="{{ old('combo_items.'.$idx.'.extra_price', $cItem->extra_price) }}">
                                                            <span class="input-group-text bg-light text-muted px-1">€</span>
                                                        </div>
                                                    </div>
                                                    <div class="card-item-action">
                                                        <button type="button" class="btn-action-icon btn-remove btn-remove-combo-item" title="{{ __('locale.delete') ?? 'Supprimer' }}">
                                                            <i class="bi bi-x fs-5"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div id="noComboItemsAlert" class="empty-drop-zone m-2" style="{{ $equipment->comboItems->count() > 0 ? 'display: none;' : '' }}">
                                            <i class="bi bi-arrow-left-right fs-2 d-block mb-2 text-primary"></i>
                                            <div class="fw-semibold text-dark mb-1">{{ __('locale.combo.drag_drop_items_here') }}</div>
                                            <div class="small text-muted">{{ __('locale.combo.or_click_plus_in_available') }} <span class="badge bg-primary-subtle text-primary border"><i class="bi bi-plus"></i></span> {{ __('locale.combo.in_available_products_list') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column: Produits disponibles -->
                            <div class="col-lg-6 d-flex flex-column">
                                <div class="dual-list-card w-100 flex-grow-1">
                                    <div class="dual-list-card-header">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-grid-3x3-gap text-success fs-5"></i>
                                            <h6 class="fw-bold mb-0 text-dark">
                                                {{ __('locale.combo.available_products') ?? 'Produits disponibles' }}
                                            </h6>
                                        </div>
                                        <span class="badge rounded-pill badge-available d-inline-flex align-items-center">
                                            <span id="equipmentComboAvailableCount">{{ count($equipments) }}</span>&nbsp;{{ __('locale.combo.available_count') }}
                                        </span>
                                    </div>

                                    <div class="p-2 bg-light border-bottom flex-shrink-0">
                                        <!-- Search bar + Advanced filter toggle -->
                                        <div class="d-flex align-items-center gap-1">
                                            <div class="input-group search-input-group flex-grow-1">
                                                <span class="input-group-text py-1 px-2"><i class="bi bi-search"></i></span>
                                                <input type="text" class="form-control form-control-sm py-1" id="eqComboSearchInput" placeholder="{{ __('locale.combo.search_placeholder') }}">
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1 px-2 py-1 flex-shrink-0" id="btnToggleAdvancedFilters" title="{{ __('locale.advanced_filters') }}" style="height: 34px; font-size: 0.8rem;">
                                                <i class="bi bi-funnel"></i>
                                                <span class="d-none d-sm-inline">{{ __('locale.filters') }}</span>
                                                <span id="activeFilterBadge" class="badge bg-primary text-white rounded-pill px-1 d-none" style="font-size: 0.65rem;">0</span>
                                            </button>
                                        </div>

                                        <!-- Collapsible Advanced Filters -->
                                        <div id="eqComboAdvancedFilters" class="mt-2 pt-2 border-top border-slate-200" style="display: none;">
                                            <div class="row g-1">
                                                <div class="col-4">
                                                    <select class="form-select form-select-sm filter-select py-1" id="eqComboFilterCat" style="font-size: 0.75rem;">
                                                        <option value="">{{ __('locale.category') ?? 'Catégorie' }}</option>
                                                        @foreach ($equipmentCategories as $cat)
                                                            <option value="{{ $cat->id }}">{{ $cat->name_raw ?? $cat->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-4">
                                                    <select class="form-select form-select-sm filter-select py-1" id="eqComboFilterBrand" style="font-size: 0.75rem;">
                                                        <option value="">{{ __('locale.brand') ?? 'Famille' }}</option>
                                                        @foreach ($brands as $brand)
                                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-4">
                                                    <select class="form-select form-select-sm filter-select py-1" id="eqComboFilterType" style="font-size: 0.75rem;">
                                                        <option value="">{{ __('locale.equipment_type') ?? 'Sous-famille' }}</option>
                                                        @foreach ($equipmentTypes as $type)
                                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Available items scroll area -->
                                    <div class="dual-list-scroll-area border-0 rounded-0">
                                        <div id="eqComboAvailableContainer" class="d-flex flex-column gap-1" style="min-height: 150px;">
                                            @foreach ($equipments as $eq)
                                                @php
                                                    $price = $eq->price_per_unit ?? $eq->price_excl_tax ?? 0;
                                                    $costPrice = $eq->purchase_price ?? 0;
                                                @endphp
                                                <div class="combo-item-card draggable-item eq-combo-available-row"
                                                    draggable="true"
                                                    data-id="{{ $eq->id }}"
                                                    data-ref="{{ $eq->reference ?? 'N/A' }}"
                                                    data-name="{{ $eq->name }}"
                                                    data-price="{{ number_format($price, 2, '.', '') }}"
                                                    data-price-formatted="{{ number_format($price, 2, ',', ' ') }} €"
                                                    data-cost-price="{{ number_format($costPrice, 2, '.', '') }}"
                                                    data-cost-price-formatted="{{ number_format($costPrice, 2, ',', ' ') }} €"
                                                    data-category="{{ $eq->equipment_category_id ?? '' }}"
                                                    data-brand="{{ $eq->brand_id ?? '' }}"
                                                    data-type="{{ $eq->equipment_type_id ?? '' }}">
                                                    <div class="card-item-action">
                                                        <button type="button" class="btn-action-icon btn-action-icon-sm btn-add btn-add-eq-combo" title="{{ __('locale.add') ?? 'Ajouter' }}">
                                                            <i class="bi bi-plus"></i>
                                                        </button>
                                                    </div>
                                                    <div class="card-drag-handle" title="{{ __('locale.combo.drag_to_left') }}">
                                                        <i class="bi bi-grip-vertical"></i>
                                                    </div>
                                                    <div class="card-item-main d-flex align-items-center gap-2">
                                                        <span class="item-ref-badge" title="{{ $eq->reference ?? 'N/A' }}">{{ $eq->reference ?? 'N/A' }}</span>
                                                        <span class="card-item-title" title="{{ $eq->name }}">{{ $eq->name }}</span>
                                                    </div>
                                                    <div class="card-item-price-tag">
                                                        {{ number_format($price, 2, ',', ' ') }} €
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div id="noEqComboAvailableResults" class="text-center py-4 text-muted" style="display: none;">
                                            <i class="bi bi-search fs-3 d-block mb-1 text-secondary"></i>
                                            {{ __('locale.combo.no_matching_available') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 8: Composants du Kit -->
                    <div class="tab-pane fade" id="kit-tab-pane" role="tabpanel" tabindex="0">
                        <div class="module-form-card card border shadow-sm mb-3">
                            <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <i class="bi bi-box-seam text-primary fs-5"></i>
                                    <h6 class="fw-bold mb-0 text-dark">
                                        {{ __('locale.kit.tab_kit') ?? 'Composants du Kit' }} <span class="badge bg-success-subtle text-success border border-success-subtle ms-1 font-monospace" style="font-size: 0.75rem;">Odoo Phantom BoM</span>
                                    </h6>
                                    <span id="kitPriceStatusBadge" class="badge {{ old('is_kit_price_manual', $equipment->is_kit_price_manual ?? false) ? 'bg-warning text-dark' : 'bg-info-subtle text-info border border-info-subtle' }} px-2 py-1" style="font-size: 0.75rem;" title="{{ old('is_kit_price_manual', $equipment->is_kit_price_manual ?? false) ? (__('locale.kit.manual_price_tooltip') ?? 'Le prix de ce kit a été défini manuellement') : (__('locale.kit.auto_price_tooltip') ?? 'Le prix de ce kit est synchronisé automatiquement avec ses composants') }}">
                                        <i class="bi {{ old('is_kit_price_manual', $equipment->is_kit_price_manual ?? false) ? 'bi-pencil' : 'bi-magic' }} me-1" id="kitPriceStatusBadgeIcon"></i>
                                        <span id="kitPriceStatusBadgeText">{{ old('is_kit_price_manual', $equipment->is_kit_price_manual ?? false) ? (__('locale.kit.badge_price_manual') ?? 'Prix manuel') : (__('locale.kit.badge_price_auto') ?? 'Prix auto') }}</span>
                                    </span>
                                    <input type="hidden" name="is_kit_price_manual" id="is_kit_price_manual" value="{{ old('is_kit_price_manual', ($equipment->is_kit_price_manual ?? false) ? '1' : '0') }}">
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    @if($canSeeCost)
                                        <div class="small text-muted">
                                            {{ __('locale.kit.total_kit_cost') }}: <strong id="kitTotalCostDisplay" class="text-dark">0,00 €</strong>
                                        </div>
                                    @endif
                                    <div class="small text-muted">
                                        {{ __('locale.kit.suggested_price') }}: <strong id="kitTotalSaleDisplay" class="text-dark">0,00 €</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <p class="text-muted small mb-0">{{ __('locale.kit.kit_explanation') ?? 'Un kit (nomenclature fantôme dans Odoo) regroupe plusieurs articles vendus sous une seule référence. Lors de la livraison, Odoo déstocke automatiquement les composants individuels.' }}</p>
                                <!-- Price Change Notification Banner -->
                                <div id="kitPriceChangeAlert" class="alert alert-warning border-warning-subtle align-items-center justify-content-between flex-wrap gap-2 mt-3 mb-0 py-2 px-3 rounded-3 shadow-sm d-none">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-exclamation-circle-fill text-warning fs-5 flex-shrink-0"></i>
                                        <div>
                                            <span class="fw-bold text-dark" id="kitAlertTitle">{{ __('locale.kit.price_changed_notice') ?? 'Le prix des composants a changé' }} :</span>
                                            <span class="text-secondary small ms-1" id="kitAlertContent">
                                                <span id="kitAlertOldLabel">{{ __('locale.kit.current_price') ?? 'Prix actuel' }}</span> : <strong id="kitAlertOldPrice" class="text-dark">0,00 €</strong>
                                                <i class="bi bi-arrow-right mx-1 text-muted"></i>
                                                <span id="kitAlertNewLabel">{{ __('locale.kit.new_price') ?? 'Nouveau prix' }}</span> : <strong id="kitAlertNewPrice" class="text-primary">0,00 €</strong>
                                                <span id="kitAlertCostInfo" class="ms-2 text-muted" style="display: none;"></span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($canChangePrice)
                                            <button type="button" class="btn btn-sm btn-primary py-1 px-3 fw-semibold shadow-sm" id="btnAlertApplyNewPrice">
                                                <i class="bi bi-check2 me-1"></i>{{ __('locale.kit.btn_apply_new_price') ?? 'Appliquer le nouveau prix' }}
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2" id="btnAlertKeepOldPrice">
                                                <i class="bi bi-x me-1"></i>{{ __('locale.kit.btn_keep_old_price') ?? "Conserver l'ancien prix" }}
                                            </button>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle py-1 px-2">
                                                <i class="bi bi-lock me-1"></i>{{ __('locale.kit.no_permission_to_change_price') ?? "Vous n'avez pas la permission de modifier le prix de vente." }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(($detectedSoldQty ?? 0) > 0 || ($equipment->combo_sales_deducted_qty ?? 0) > 0)
                            <div class="alert alert-warning border-warning-subtle d-flex align-items-start gap-3 mb-3 shadow-sm rounded-3" role="alert">
                                <i class="bi bi-exclamation-triangle-fill text-warning fs-4 mt-1 flex-shrink-0"></i>
                                <div class="flex-grow-1">
                                    <div class="fw-bold text-dark mb-1">
                                        {{ __('locale.kit.past_sales_notice_title') ?? 'Conversion Combo vers Kit : Déduction automatique du stock dans Odoo' }}
                                    </div>
                                    <p class="small text-muted mb-2">
                                        {{ __('locale.kit.past_sales_conversion_desc', ['count' => (float) ($detectedSoldQty ?? 0)]) ?? "Ce produit était un Combo pour lequel " . ($detectedSoldQty ?? 0) . " unité(s) vendue(s) ont été détectées. Les matières premières de vos composants seront directement déduites de vos stocks réels dans Odoo lors de l'enregistrement." }}
                                    </p>
                                    <div class="d-flex align-items-center gap-2 flex-wrap bg-white p-2 rounded border border-warning-subtle">
                                        <label for="deduct_sold_qty" class="small fw-semibold text-dark mb-0">
                                            {{ __('locale.kit.lbl_sold_units_to_deduct') ?? 'Unités vendues à déduire dans Odoo :' }}
                                        </label>
                                        <input type="number" step="any" min="0" name="deduct_sold_qty" id="deduct_sold_qty" 
                                            value="{{ old('deduct_sold_qty', max(0, (float)($detectedSoldQty ?? 0) - (float)($equipment->combo_sales_deducted_qty ?? 0))) }}" 
                                            class="form-control form-control-sm text-end fw-bold" style="width: 110px;">
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle small">
                                            {{ __('locale.kit.already_deducted') ?? 'Déjà déduit' }} : {{ (float)($equipment->combo_sales_deducted_qty ?? 0) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row g-3 py-1 align-items-stretch">
                            <!-- Left Column: Composants associés -->
                            <div class="col-lg-6 d-flex flex-column">
                                <div class="dual-list-card w-100 flex-grow-1">
                                    <div class="dual-list-card-header">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-link-45deg text-primary fs-5"></i>
                                            <h6 class="fw-bold mb-0 text-dark">
                                                {{ __('locale.kit.components') ?? 'Composants associés' }}
                                            </h6>
                                        </div>
                                        <span class="badge rounded-pill badge-associated d-inline-flex align-items-center">
                                            <span id="kitAssociatedCount">{{ $equipment->kitItems ? $equipment->kitItems->count() : 0 }}</span>&nbsp;{{ __('locale.combo.associated_count') ?? 'associés' }}
                                        </span>
                                    </div>

                                    <div class="dual-list-scroll-area border-0 rounded-0" id="kitItemsScrollArea" style="display: flex; flex-direction: column;">
                                        <div id="kitItemsContainer" class="d-flex flex-column gap-1" style="flex: 1 1 auto; min-height: 100%;">
                                            @if($equipment->kitItems && $equipment->kitItems->isNotEmpty())
                                                @foreach($equipment->kitItems as $idx => $kItem)
                                                    @php
                                                        $child = $kItem->childEquipment;
                                                        $cost = $child ? (float)($child->purchase_price ?? $kItem->cost_price ?? 0) : (float)($kItem->cost_price ?? 0);
                                                        $price = $child ? (float)($child->price_per_unit ?? $child->price_excl_tax ?? 0) : 0;
                                                        $qty = (float)($kItem->quantity ?: 1);
                                                        $ref = $child ? ($child->reference ?? 'N/A') : 'N/A';
                                                        $name = $child ? $child->name : __('locale.kit.component_not_found');
                                                        $rowPrice = $canSeeCost ? ($cost * $qty) : ($price * $qty);
                                                        $rowPriceTitle = $canSeeCost ? (__('locale.kit.total_cost') ?? 'Coût total') : (__('locale.total_price') ?? 'Prix total');
                                                    @endphp
                                                    <div class="combo-item-card draggable-item kit-item-row"
                                                        draggable="true"
                                                        data-id="{{ $kItem->child_equipment_id }}"
                                                        data-item-id="{{ $kItem->id }}"
                                                        data-ref="{{ $ref }}"
                                                        data-name="{{ $name }}"
                                                        data-cost="{{ $cost }}"
                                                        data-price="{{ $price }}"
                                                        data-quantity="{{ fmod($qty, 1) === 0.0 ? (int)$qty : $qty }}">
                                                        <div class="card-drag-handle" title="{{ __('locale.combo.drag_to_reorder') }}">
                                                            <i class="bi bi-grip-vertical"></i>
                                                            <input type="hidden" name="kit_items[{{ $idx }}][id]" value="{{ $kItem->id }}">
                                                            <input type="hidden" name="kit_items[{{ $idx }}][child_equipment_id]" value="{{ $kItem->child_equipment_id }}">
                                                            <input type="hidden" class="kit-item-seq" name="kit_items[{{ $idx }}][sequence]" value="{{ $idx }}">
                                                            <input type="hidden" name="kit_items[{{ $idx }}][cost_price]" value="{{ number_format($cost, 2, '.', '') }}">
                                                        </div>
                                                        <div class="card-item-main d-flex align-items-center gap-2">
                                                            <span class="item-ref-badge" title="{{ $ref }}">{{ $ref }}</span>
                                                            <span class="card-item-title" title="{{ $name }}">{{ $name }}</span>
                                                        </div>
                                                        <div class="card-item-qty">
                                                            <div class="input-group input-group-sm">
                                                                <span class="input-group-text bg-light text-muted px-1" style="font-size: 0.72rem;">{{ __('locale.qty') }}</span>
                                                                <input type="number" step="any" min="0.01" class="form-control form-control-sm text-center fw-bold kit-item-qty" name="kit_items[{{ $idx }}][quantity]" value="{{ fmod($qty, 1) === 0.0 ? (int)$qty : $qty }}" style="font-size: 0.8rem;">
                                                            </div>
                                                        </div>
                                                        <div class="card-item-price-tag me-2 kit-item-total-price" title="{{ $rowPriceTitle }}">
                                                            {{ number_format($rowPrice, 2, ',', ' ') }} €
                                                        </div>
                                                        <div class="card-item-action">
                                                            <button type="button" class="btn-action-icon btn-remove btn-remove-kit-item" title="{{ __('locale.remove') ?? 'Supprimer' }}">
                                                                <i class="bi bi-x fs-5"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div id="noKitItemsAlert" class="empty-drop-zone m-2" style="{{ ($equipment->kitItems && $equipment->kitItems->isNotEmpty()) ? 'display: none;' : '' }}">
                                            <i class="bi bi-arrow-left-right fs-2 d-block mb-2 text-primary"></i>
                                            <div class="fw-semibold text-dark mb-1">{{ __('locale.kit.drag_drop_components_here') ?? 'Glissez-déposez des articles ici' }}</div>
                                            <div class="small text-muted">{{ __('locale.combo.or_click_plus_in_available') }} <span class="badge bg-primary-subtle text-primary border"><i class="bi bi-plus"></i></span> {{ __('locale.combo.in_available_products_list') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column: Composants disponibles -->
                            <div class="col-lg-6 d-flex flex-column">
                                <div class="dual-list-card w-100 flex-grow-1">
                                    <div class="dual-list-card-header">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-grid-3x3-gap text-success fs-5"></i>
                                            <h6 class="fw-bold mb-0 text-dark">
                                                {{ __('locale.kit.available_components') ?? 'Composants disponibles' }}
                                            </h6>
                                        </div>
                                        <span class="badge rounded-pill badge-available d-inline-flex align-items-center">
                                            <span id="kitAvailableCount">{{ count($equipments) }}</span>&nbsp;{{ __('locale.combo.available_count') ?? 'disponibles' }}
                                        </span>
                                    </div>

                                    <div class="p-2 bg-light border-bottom flex-shrink-0">
                                        <!-- Search bar + Advanced filter toggle -->
                                        <div class="d-flex align-items-center gap-1">
                                            <div class="input-group search-input-group flex-grow-1">
                                                <span class="input-group-text py-1 px-2"><i class="bi bi-search"></i></span>
                                                <input type="text" class="form-control form-control-sm py-1" id="kitSearchInput" placeholder="{{ __('locale.combo.search_placeholder') }}">
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1 px-2 py-1 flex-shrink-0" id="btnToggleKitAdvancedFilters" title="{{ __('locale.advanced_filters') }}" style="height: 34px; font-size: 0.8rem;">
                                                <i class="bi bi-funnel"></i>
                                                <span class="d-none d-sm-inline">{{ __('locale.filters') }}</span>
                                                <span id="kitActiveFilterBadge" class="badge bg-primary text-white rounded-pill px-1 d-none" style="font-size: 0.65rem;">0</span>
                                            </button>
                                        </div>

                                        <!-- Collapsible Advanced Filters -->
                                        <div id="kitAdvancedFilters" class="mt-2 pt-2 border-top border-slate-200" style="display: none;">
                                            <div class="row g-1">
                                                <div class="col-4">
                                                    <select class="form-select form-select-sm filter-select py-1" id="kitFilterCat" style="font-size: 0.75rem;">
                                                        <option value="">{{ __('locale.category') ?? 'Catégorie' }}</option>
                                                        @foreach ($equipmentCategories as $cat)
                                                            <option value="{{ $cat->id }}">{{ $cat->name_raw ?? $cat->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-4">
                                                    <select class="form-select form-select-sm filter-select py-1" id="kitFilterBrand" style="font-size: 0.75rem;">
                                                        <option value="">{{ __('locale.brand') ?? 'Famille' }}</option>
                                                        @foreach ($brands as $brand)
                                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-4">
                                                    <select class="form-select form-select-sm filter-select py-1" id="kitFilterType" style="font-size: 0.75rem;">
                                                        <option value="">{{ __('locale.equipment_type') ?? 'Sous-famille' }}</option>
                                                        @foreach ($equipmentTypes as $type)
                                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Available items scroll area -->
                                    <div class="dual-list-scroll-area border-0 rounded-0">
                                        <div id="kitAvailableContainer" class="d-flex flex-column gap-1" style="min-height: 150px;">
                                            @foreach ($equipments as $eq)
                                                @php
                                                    $price = $eq->price_per_unit ?? $eq->price_excl_tax ?? 0;
                                                    $costPrice = $canSeeCost ? ($eq->purchase_price ?? 0) : 0;
                                                    $cardDisplayPrice = $canSeeCost ? $costPrice : $price;
                                                    $cardDisplayTitle = $canSeeCost ? (__('locale.kit.unit_cost') ?? 'Coût unitaire') : (__('locale.price_per_unit') ?? 'Prix public');
                                                @endphp
                                                <div class="combo-item-card draggable-item eq-kit-available-row"
                                                    draggable="true"
                                                    data-id="{{ $eq->id }}"
                                                    data-ref="{{ $eq->reference ?? 'N/A' }}"
                                                    data-name="{{ $eq->name }}"
                                                    data-price="{{ number_format($price, 2, '.', '') }}"
                                                    data-price-formatted="{{ number_format($price, 2, ',', ' ') }} €"
                                                    data-cost-price="{{ number_format($costPrice, 2, '.', '') }}"
                                                    data-cost-price-formatted="{{ number_format($costPrice, 2, ',', ' ') }} €"
                                                    data-category="{{ $eq->equipment_category_id ?? '' }}"
                                                    data-brand="{{ $eq->brand_id ?? '' }}"
                                                    data-type="{{ $eq->equipment_type_id ?? '' }}">
                                                    <div class="card-item-action">
                                                        <button type="button" class="btn-action-icon btn-action-icon-sm btn-add btn-add-kit-item" title="{{ __('locale.add') ?? 'Ajouter' }}">
                                                            <i class="bi bi-plus"></i>
                                                        </button>
                                                    </div>
                                                    <div class="card-drag-handle" title="{{ __('locale.combo.drag_to_left') }}">
                                                        <i class="bi bi-grip-vertical"></i>
                                                    </div>
                                                    <div class="card-item-main d-flex align-items-center gap-2">
                                                        <span class="item-ref-badge" title="{{ $eq->reference ?? 'N/A' }}">{{ $eq->reference ?? 'N/A' }}</span>
                                                        <span class="card-item-title" title="{{ $eq->name }}">{{ $eq->name }}</span>
                                                    </div>
                                                    <div class="card-item-price-tag" title="{{ $cardDisplayTitle }}">
                                                        {{ number_format($cardDisplayPrice, 2, ',', ' ') }} €
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div id="noKitAvailableResults" class="text-center py-4 text-muted" style="display: none;">
                                            <i class="bi bi-search fs-3 d-block mb-1 text-secondary"></i>
                                            {{ __('locale.combo.no_matching_available') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="eq-bottom-bar" id="eqBottomBar">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="notes-title my-0 border-0 p-0">{{ __('locale.equipment_form.lbl_internal_notes') }}</div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('equipments.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-1 fs-12">{{ __('locale.cancel') }}</a>
                            <button type="submit" class="btn btn-sm btn-secondary px-4 py-1 fs-12">
                                <i class="bi bi-check-lg me-1"></i>{{ __('locale.lbl_save') }}
                            </button>
                        </div>
                    </div>
                    <textarea class="form-control form-control-sm mt-1" id="description" name="description" rows="1" placeholder="{{ __('locale.equipment_form.lbl_internal_notes_placeholder') }}">{{ old('description', $equipment->description) }}</textarea>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Kit Price Confirmation Modal -->
<div class="modal fade" id="kitPriceConfirmModal" tabindex="-1" aria-labelledby="kitPriceConfirmModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 580px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-light py-3 px-4 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px; background-color: rgba(255, 171, 0, 0.15);">
                        <i class="bi bi-currency-exchange fs-5" style="color: #ffab00;"></i>
                    </div>
                    <h6 class="modal-title fw-bold mb-0 text-dark fs-5" id="kitPriceConfirmModalLabel">
                        {{ __('locale.kit.price_update_modal_title') ?? 'Mise à jour du prix du kit' }}
                    </h6>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-secondary mb-3 fs-6" style="line-height: 1.5;">
                    {{ __('locale.kit.price_update_modal_desc') ?? 'Le prix de vente actuel de l\'équipement a été défini manuellement et diffère de la somme des prix des composants.' }}
                </p>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <div class="p-3 rounded-3 text-center h-100 d-flex flex-column justify-content-center" style="background-color: #f3f4f6; border: 1px solid #e5e7eb;">
                            <span class="text-muted small fw-semibold text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                {{ __('locale.kit.current_price') ?? 'Prix actuel' }}
                            </span>
                            <span class="fs-4 fw-bolder text-dark" id="kitModalOldPrice">0,00 €</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-3 text-center h-100 d-flex flex-column justify-content-center shadow-sm" style="background-color: var(--primary-brand-color, #666cff) !important; border: 1px solid rgba(0, 0, 0, 0.05);">
                            <span class="small fw-semibold text-uppercase mb-1" style="color: rgba(255, 255, 255, 0.85) !important; font-size: 0.75rem; letter-spacing: 0.5px;">
                                {{ __('locale.kit.new_components_sum') ?? 'Somme des composants' }}
                            </span>
                            <span class="fs-4 fw-bolder" id="kitModalNewPrice" style="color: #ffffff !important;">0,00 €</span>
                        </div>
                    </div>
                </div>
                <div class="p-3 rounded-3 bg-light border border-light-subtle d-flex align-items-center gap-2 mb-0" style="background-color: #f8f9fa !important;">
                    <i class="bi bi-info-circle text-primary fs-5 flex-shrink-0"></i>
                    <div class="small text-muted" style="line-height: 1.4;">
                        {{ __('locale.kit.price_choice_prompt') ?? 'Le prix de ce kit peut être calculé automatiquement à partir de ses composants ou défini manuellement. Que souhaitez-vous faire ?' }}
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light py-3 px-4 border-top d-flex flex-wrap align-items-center justify-content-between gap-2">
                <button type="button" class="btn btn-outline-secondary order-last order-sm-first" data-bs-dismiss="modal">
                    {{ __('locale.cancel') ?? 'Annuler' }}
                </button>
                <div class="d-flex flex-wrap gap-2 justify-content-end">
                    <button type="button" class="btn btn-outline-dark" id="btnKeepOldKitPrice">
                        <i class="bi bi-check2 me-1"></i><span id="btnKeepOldKitPriceText" data-label="{{ __('locale.kit.btn_keep_old_price') ?? 'Conserver le prix actuel' }}">{{ __('locale.kit.btn_keep_old_price') ?? 'Conserver le prix actuel' }}</span>
                    </button>
                    <button type="button" class="btn btn-primary shadow-xs" id="btnUseNewKitPrice">
                        <i class="bi bi-arrow-repeat me-1"></i><span id="btnUseNewKitPriceText" data-label="{{ __('locale.kit.btn_use_new_price') ?? 'Appliquer la nouvelle somme' }}">{{ __('locale.kit.btn_use_new_price') ?? 'Appliquer la nouvelle somme' }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2').select2({
        width: '100%'
    });

    // Odoo Category and Accounting Family taxes mapping
    const odooCategoriesTaxMap = {
        @foreach($odooCategories as $c)
            "{{ $c->id }}": {
                taxes_id: @json($c->taxes_id ?? []),
                supplier_taxes_id: @json($c->supplier_taxes_id ?? [])
            },
        @endforeach
    };

    const accountingFamiliesMap = {
        @foreach($accountingFamilies as $fam)
            "{{ $fam->id }}": {
                odoo_category_id: "{{ $fam->odoo_category_id }}",
                taxes_id: @json($fam->taxes_id ?? []),
                supplier_taxes_id: @json($fam->supplier_taxes_id ?? [])
            },
        @endforeach
    };

    $('#odoo_category_id').on('change', function() {
        const selectedCatId = $(this).val();
        const selectedCatText = $(this).find('option:selected').text().trim().toLowerCase();

        if (selectedCatText === 'marchandises') {
            const $marOption = $('#equipment_type_id option').filter(function() {
                return $(this).text().trim().toUpperCase() === 'MAR';
            });
            if ($marOption.length && $('#equipment_type_id').val() !== $marOption.val()) {
                $('#equipment_type_id').val($marOption.val()).trigger('change');
            }

            const $marchandisesFam = $('#accounting_family_id option').filter(function() {
                return $(this).text().trim().toLowerCase().startsWith('marchandises');
            });
            if ($marchandisesFam.length && $('#accounting_family_id').val() !== $marchandisesFam.val()) {
                $('#accounting_family_id').val($marchandisesFam.val()).trigger('change');
            }
        }

        if (selectedCatId && odooCategoriesTaxMap[selectedCatId]) {
            const catTaxes = odooCategoriesTaxMap[selectedCatId];
            if (catTaxes.taxes_id && catTaxes.taxes_id.length > 0) {
                $('#taxes_id').val(catTaxes.taxes_id.map(String)).trigger('change');
            }
            if (catTaxes.supplier_taxes_id && catTaxes.supplier_taxes_id.length > 0) {
                $('#supplier_taxes_id').val(catTaxes.supplier_taxes_id.map(String)).trigger('change');
            }
        }
    });

    $('#accounting_family_id').on('change', function() {
        const selectedFamId = $(this).val();
        if (selectedFamId && accountingFamiliesMap[selectedFamId]) {
            const famData = accountingFamiliesMap[selectedFamId];
            if (famData.odoo_category_id && $('#odoo_category_id').val() !== String(famData.odoo_category_id)) {
                $('#odoo_category_id').val(famData.odoo_category_id).trigger('change');
            }
            if (famData.taxes_id && famData.taxes_id.length > 0) {
                $('#taxes_id').val(famData.taxes_id.map(String)).trigger('change');
            }
            if (famData.supplier_taxes_id && famData.supplier_taxes_id.length > 0) {
                $('#supplier_taxes_id').val(famData.supplier_taxes_id.map(String)).trigger('change');
            }
        }
    });

    // Combo Price & Cost Calculation State
    let isPublicPriceForced = false;
    let isCostPriceForced = false;
    let forcedPublicPrice = null;
    let forcedCostPrice = null;
    let isProgrammaticPriceUpdate = false;

    function updateComboPriceBadges() {
        const publicBadges = document.querySelectorAll('.combo-forced-price-badge');
        const costBadges = document.querySelectorAll('.combo-forced-cost-badge');

        publicBadges.forEach(el => {
            el.style.display = isPublicPriceForced ? 'inline-flex' : 'none';
            if (forcedPublicPrice !== null) {
                el.title = `{{ __('locale.combo.manually_modified') }}: ${Number(forcedPublicPrice).toFixed(2)} €`;
            }
        });

        costBadges.forEach(el => {
            el.style.display = isCostPriceForced ? 'inline-flex' : 'none';
            if (forcedCostPrice !== null) {
                el.title = `{{ __('locale.combo.manually_modified') }}: ${Number(forcedCostPrice).toFixed(2)} €`;
            }
        });
    }

    // Keep price fields synced bidirectionally between tabs and track manual changes
    $('#price_per_unit, #price_per_unit_tab').on('input change', function() {
        const val = $(this).val();
        if (this.id === 'price_per_unit') {
            $('#price_per_unit_tab').val(val);
        } else {
            $('#price_per_unit').val(val);
        }
        $('#price_excl_tax').val(val);

        if (isProgrammaticPriceUpdate) return;

        const isCombo = document.getElementById('is_combo')?.checked;
        if (isCombo && typeof calculateComboTotals === 'function') {
            const totals = calculateComboTotals();
            const numVal = parseFloat(val) || 0;
            if (totals.count > 0 && Math.abs(numVal - totals.publicPrice) > 0.001) {
                isPublicPriceForced = true;
                forcedPublicPrice = numVal;
            } else if (totals.count > 0 && Math.abs(numVal - totals.publicPrice) <= 0.001) {
                isPublicPriceForced = false;
                forcedPublicPrice = null;
            }
            updateComboPriceBadges();
        }
    });

    let isOdpPriceManuallyChanged = {{ (old('odp_purchase_price') !== null && old('odp_purchase_price') != old('purchase_price', $equipment->purchase_price)) || ($equipment->odp_purchase_price !== null && (float)$equipment->odp_purchase_price !== (float)$equipment->purchase_price) ? 'true' : 'false' }};

    $('#odp_purchase_price').on('input change', function() {
        isOdpPriceManuallyChanged = true;
    });

    $('#purchase_price, #purchase_price_tab').on('input change', function() {
        const val = $(this).val();
        if (this.id === 'purchase_price') {
            $('#purchase_price_tab').val(val);
        } else {
            $('#purchase_price').val(val);
        }

        if (!isOdpPriceManuallyChanged) {
            $('#odp_purchase_price').val(val);
        }

        if (isProgrammaticPriceUpdate) return;

        const isCombo = document.getElementById('is_combo')?.checked;
        if (isCombo && typeof calculateComboTotals === 'function') {
            const totals = calculateComboTotals();
            const numVal = parseFloat(val) || 0;
            if (totals.count > 0 && Math.abs(numVal - totals.costPrice) > 0.001) {
                isCostPriceForced = true;
                forcedCostPrice = numVal;
            } else if (totals.count > 0 && Math.abs(numVal - totals.costPrice) <= 0.001) {
                isCostPriceForced = false;
                forcedCostPrice = null;
            }
            updateComboPriceBadges();
        }
    });

    // Double purchase price and place into sale price (price per unit)
    $(document).on('click', '.btn-double-price', function(e) {
        e.preventDefault();
        if ($(this).is(':disabled') || $(this).prop('disabled')) return;

        const currentInput = $(this).closest('.input-group').find('input');
        const val = currentInput.val();
        if (val !== undefined && val !== '') {
            $('#purchase_price, #purchase_price_tab').val(val).trigger('input').trigger('change');
        }
        const cost = parseFloat($('#purchase_price').val()) || 0;
        const doubled = (cost * 2).toFixed(2);

        if (typeof priceManuallyEdited !== 'undefined') {
            priceManuallyEdited = true;
        }
        if (typeof priceChoiceExplicitlyMade !== 'undefined') {
            priceChoiceExplicitlyMade = null;
        }
        if (typeof setKitPriceManualState === 'function') {
            setKitPriceManualState(true);
        }

        $('#price_excl_tax').val(doubled);
        $('#price_per_unit, #price_per_unit_tab').val(doubled).trigger('input').trigger('change');
    });

    $('.select2-ajax').select2({
        ajax: {
            url: "{{ route('equipments.search') }}",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term,
                    page: params.page
                };
            },
            processResults: function(data, params) {
                params.page = params.page || 1;
                return {
                    results: data.map(function(item) {
                        return {
                            id: item.id,
                            text: item.name + (item.reference ? ' (' + item.reference + ')' : '')
                        };
                    }),
                    pagination: {
                        more: (params.page * 10) < data.total
                    }
                };
            },
            cache: true
        },
        placeholder: "{{ __('locale.lbl_select_substitutes') }}",
        minimumInputLength: 2,
        language: "{{ app()->getLocale() }}"
    });

    $('.select2-supplier-ref').select2({
        width: '100%',
        placeholder: "{{ __('locale.equipment_form.lbl_select_placeholder') }}",
        allowClear: true
    });

    let resellerIndex = {{ count($supplierRefs) }};
    $('#add-reseller-ref').click(function() {
        const html = `
            <tr class="reseller-ref-row">
                <td>
                    <select name="supplier_references[${resellerIndex}][supplier_id]" class="form-select form-select-sm select2-supplier-ref p-1" style="font-size:0.8rem;" required>
                        <option value="">{{ __('locale.equipment_form.lbl_select_placeholder') }}</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}{{ $supplier->company ? ' (' . $supplier->company->name . ')' : '' }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="text" name="supplier_references[${resellerIndex}][product_name]" class="form-control form-control-sm p-1" style="font-size:0.8rem;"></td>
                <td><input type="text" name="supplier_references[${resellerIndex}][reference]" class="form-control form-control-sm p-1" style="font-size:0.8rem;" placeholder="{{ __('locale.equipment_form.lbl_reference') }}" required></td>
                <td><input type="date" name="supplier_references[${resellerIndex}][date_start]" class="form-control form-control-sm p-1" style="font-size:0.8rem;"></td>
                <td><input type="date" name="supplier_references[${resellerIndex}][date_end]" class="form-control form-control-sm p-1" style="font-size:0.8rem;"></td>
                <td>
                    <select name="supplier_references[${resellerIndex}][company_id]" class="form-select form-select-sm p-1" style="font-size:0.8rem;">
                        <option value="">{{ __('locale.equipment_form.lbl_visible_all_option') }}</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" step="0.01" name="supplier_references[${resellerIndex}][min_qty]" class="form-control form-control-sm p-1" style="font-size:0.8rem;" value="1.00"></td>
                <td><input type="text" name="supplier_references[${resellerIndex}][product_uom]" class="form-control form-control-sm p-1" style="font-size:0.8rem;" value="{{ __('locale.equipment_form.lbl_unit_default') }}"></td>
                <td><input type="number" step="0.01" name="supplier_references[${resellerIndex}][price]" class="form-control form-control-sm p-1" style="font-size:0.8rem;" value="0.00"></td>
                <td><input type="number" step="0.01" name="supplier_references[${resellerIndex}][discount]" class="form-control form-control-sm p-1" style="font-size:0.8rem;" value="0.00"></td>
                <td><input type="text" name="supplier_references[${resellerIndex}][currency]" class="form-control form-control-sm p-1" style="font-size:0.8rem;" value="EUR"></td>
                <td><input type="number" name="supplier_references[${resellerIndex}][delay]" class="form-control form-control-sm p-1" style="font-size:0.8rem;" value="1"></td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-link text-danger remove-reseller-ref p-0">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        const $newRow = $(html);
        $newRow.find(`input[name="supplier_references[${resellerIndex}][product_name]"]`).val($('#name').val() || '');
        $newRow.find(`input[name="supplier_references[${resellerIndex}][reference]"]`).val($('#reference').val() || '');
        $('#reseller-references-container').append($newRow);
        $newRow.find('.select2-supplier-ref').select2({
            width: '100%',
            placeholder: "{{ __('locale.equipment_form.lbl_select_placeholder') }}",
            allowClear: true
        });
        resellerIndex++;
    });

    $(document).on('click', '.remove-reseller-ref', function() {
        $(this).closest('.reseller-ref-row').remove();
    });

    // --- Equipment Category Cascading Logic ---
    const equipmentCategories = @json($equipmentCategories);
    const selectEqCat = document.getElementById('select-equipment-category');
    const selectEqFam = document.getElementById('select-equipment-family');
    const selectEqSub = document.getElementById('select-equipment-subfamily');
    const eqHiddenInput = document.getElementById('equipment_category_id');

    function getSelectedEqHierarchy(id) {
        if (!id) return { catId: null, famId: null, subId: null };
        const c3 = equipmentCategories.find(c => c.id == id);
        if (!c3) return { catId: null, famId: null, subId: null };
        
        if (!c3.parent_id) {
            return { catId: c3.id, famId: null, subId: null };
        }
        
        const c2 = equipmentCategories.find(c => c.id == c3.parent_id);
        if (!c2) {
            return { catId: c3.id, famId: null, subId: null };
        }
        
        if (!c2.parent_id) {
            return { catId: c2.id, famId: c3.id, subId: null };
        }
        
        const c1 = equipmentCategories.find(c => c.id == c2.parent_id);
        if (!c1) {
            return { catId: c2.id, famId: c3.id, subId: null };
        }
        
        return { catId: c1.id, famId: c2.id, subId: c3.id };
    }

    function buildEqOptions(selectElement, list, selectedId, placeholder) {
        selectElement.innerHTML = `<option value="">${placeholder}</option>`;
        list.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.id;
            opt.textContent = c.name_raw;
            if (c.id == selectedId) {
                opt.selected = true;
            }
            selectElement.appendChild(opt);
        });
        const disabledState = list.length === 0;
        $(selectElement).prop('disabled', disabledState).trigger('change');
    }

    function updateEqCascades() {
        const catId = selectEqCat ? selectEqCat.value : null;
        const famId = selectEqFam ? selectEqFam.value : null;
        const subId = selectEqSub ? selectEqSub.value : null;

        let finalId = '';
        if (subId) {
            finalId = subId;
        } else if (famId) {
            finalId = famId;
        } else if (catId) {
            finalId = catId;
        }
        if (eqHiddenInput) eqHiddenInput.value = finalId;
    }

    // Initialize
    if (eqHiddenInput) {
        const eqInitialId = eqHiddenInput.value;
        const eqHierarchy = getSelectedEqHierarchy(eqInitialId);

        const eqTopLevelCats = equipmentCategories.filter(c => !c.parent_id);
        if (selectEqCat) buildEqOptions(selectEqCat, eqTopLevelCats, eqHierarchy.catId, "{{ __('locale.equipment_form.lbl_category_placeholder') }}");

        if (eqHierarchy.catId && selectEqFam) {
            const families = equipmentCategories.filter(c => c.parent_id == eqHierarchy.catId);
            buildEqOptions(selectEqFam, families, eqHierarchy.famId, "{{ __('locale.equipment_form.lbl_family_placeholder') }}");
        } else if (selectEqFam) {
            buildEqOptions(selectEqFam, [], null, "{{ __('locale.equipment_form.lbl_family_placeholder') }}");
        }

        if (eqHierarchy.famId && selectEqSub) {
            const subfamilies = equipmentCategories.filter(c => c.parent_id == eqHierarchy.famId);
            buildEqOptions(selectEqSub, subfamilies, eqHierarchy.subId, "{{ __('locale.equipment_form.lbl_subfamily_placeholder') }}");
        } else if (selectEqSub) {
            buildEqOptions(selectEqSub, [], null, "{{ __('locale.equipment_form.lbl_subfamily_placeholder') }}");
        }

        updateEqCascades();
    }

    // Event Listeners
    $(selectEqCat).on('change', function () {
        const catId = this.value;
        if (selectEqFam) {
            selectEqFam.innerHTML = '<option value="">{{ __('locale.equipment_form.lbl_family_placeholder') }}</option>';
            selectEqFam.disabled = true;
        }
        if (selectEqSub) {
            selectEqSub.innerHTML = '<option value="">{{ __('locale.equipment_form.lbl_subfamily_placeholder') }}</option>';
            selectEqSub.disabled = true;
        }

        if (catId && selectEqFam) {
            const families = equipmentCategories.filter(c => c.parent_id == catId);
            buildEqOptions(selectEqFam, families, null, "{{ __('locale.equipment_form.lbl_family_placeholder') }}");
        }
        updateEqCascades();
        if ($(selectEqFam).hasClass('select2-hidden-accessible') || $(selectEqFam).data('select2')) {
            $(selectEqFam).trigger('change.select2');
        }
        if ($(selectEqSub).hasClass('select2-hidden-accessible') || $(selectEqSub).data('select2')) {
            $(selectEqSub).trigger('change.select2');
        }
    });

    $(selectEqFam).on('change', function () {
        const famId = this.value;
        if (selectEqSub) {
            selectEqSub.innerHTML = '<option value="">{{ __('locale.equipment_form.lbl_subfamily_placeholder') }}</option>';
            selectEqSub.disabled = true;
        }

        if (famId && selectEqSub) {
            const subfamilies = equipmentCategories.filter(c => c.parent_id == famId);
            buildEqOptions(selectEqSub, subfamilies, null, "{{ __('locale.equipment_form.lbl_subfamily_placeholder') }}");
        }
        updateEqCascades();
        if ($(selectEqSub).hasClass('select2-hidden-accessible') || $(selectEqSub).data('select2')) {
            $(selectEqSub).trigger('change.select2');
        }
    });

    $(selectEqSub).on('change', function () {
        updateEqCascades();
    });

    // Toggle Tabs based on top checkboxes
    const saleOkSwitch = document.getElementById('sale_ok');
    const salesTabNav = document.getElementById('sales-tab-nav');
    const purchaseOkSwitch = document.getElementById('purchase_ok');
    const purchaseTabNav = document.getElementById('purchase-tab-nav');
    const recurringInvoiceSwitch = document.getElementById('recurring_invoice');
    const subscriptionTabNav = document.getElementById('subscription-tab-nav');
    const isComboSwitch = document.getElementById('is_combo');
    const comboTabNav = document.getElementById('combo-tab-nav');
    const isKitSwitch = document.getElementById('is_kit');
    const kitTabNav = document.getElementById('kit-tab-nav');

    function setTabNavVisible(tabNav, isVisible) {
        if (!tabNav) return;
        if (isVisible) {
            tabNav.classList.remove('d-none');
            tabNav.style.removeProperty('display');
        } else {
            tabNav.classList.add('d-none');
            tabNav.style.setProperty('display', 'none', 'important');
        }
    }

    function switchBackToGeneralIfActive(tabBtnId) {
        const tabBtn = document.getElementById(tabBtnId);
        if (tabBtn && tabBtn.classList.contains('active')) {
            const generalTab = new bootstrap.Tab(document.getElementById('general-tab'));
            generalTab.show();
        }
    }

    if (saleOkSwitch && salesTabNav) {
        saleOkSwitch.addEventListener('change', function () {
            setTabNavVisible(salesTabNav, this.checked);
            if (!this.checked) {
                switchBackToGeneralIfActive('sales-tab');
            }
        });
        setTabNavVisible(salesTabNav, saleOkSwitch.checked);
    }

    if (purchaseOkSwitch && purchaseTabNav) {
        purchaseOkSwitch.addEventListener('change', function () {
            setTabNavVisible(purchaseTabNav, this.checked);
            if (!this.checked) {
                switchBackToGeneralIfActive('purchase-tab');
            }
        });
        setTabNavVisible(purchaseTabNav, purchaseOkSwitch.checked);
    }

    if (recurringInvoiceSwitch && subscriptionTabNav) {
        recurringInvoiceSwitch.addEventListener('change', function () {
            setTabNavVisible(subscriptionTabNav, this.checked);
            if (this.checked) {
                const tabTrigger = new bootstrap.Tab(document.getElementById('subscription-tab'));
                tabTrigger.show();
            } else {
                switchBackToGeneralIfActive('subscription-tab');
            }
        });
        setTabNavVisible(subscriptionTabNav, recurringInvoiceSwitch.checked);
    }

    if (isComboSwitch && comboTabNav) {
        isComboSwitch.addEventListener('change', function () {
            setTabNavVisible(comboTabNav, this.checked);
            if (this.checked) {
                if (isKitSwitch) isKitSwitch.checked = false;
                setTabNavVisible(kitTabNav, false);
                const tabTrigger = new bootstrap.Tab(document.getElementById('combos-tab'));
                tabTrigger.show();
            } else {
                switchBackToGeneralIfActive('combos-tab');
            }
        });
        setTabNavVisible(comboTabNav, isComboSwitch.checked);
    }

    if (isKitSwitch && kitTabNav) {
        isKitSwitch.addEventListener('change', function () {
            setTabNavVisible(kitTabNav, this.checked);
            if (this.checked) {
                if (isComboSwitch) isComboSwitch.checked = false;
                setTabNavVisible(comboTabNav, false);
                const tabTrigger = new bootstrap.Tab(document.getElementById('kit-tab'));
                tabTrigger.show();
            } else {
                switchBackToGeneralIfActive('kit-tab');
            }
        });
        setTabNavVisible(kitTabNav, isKitSwitch.checked);
    }

    // Direct Combo Items management with Native Multi-Drag & Drop
    const comboItemsContainer = document.getElementById('comboItemsContainer');
    const eqComboAvailableContainer = document.getElementById('eqComboAvailableContainer');
    const noComboItemsAlert = document.getElementById('noComboItemsAlert');
    const eqComboAssociatedCount = document.getElementById('equipmentComboAssociatedCount');
    const eqComboAvailableCount = document.getElementById('equipmentComboAvailableCount');
    const eqComboSearchInput = document.getElementById('eqComboSearchInput');
    const eqComboFilterCat = document.getElementById('eqComboFilterCat');
    const eqComboFilterBrand = document.getElementById('eqComboFilterBrand');
    const eqComboFilterType = document.getElementById('eqComboFilterType');
    const noEqComboAvailableResults = document.getElementById('noEqComboAvailableResults');
    const equipmentForm = document.querySelector('form.eq-form-layout');
    const selectAllEqComboAvailable = document.getElementById('selectAllEqComboAvailable');
    const eqComboSelectedCountBadge = document.getElementById('eqComboSelectedCountBadge');
    const eqComboSelectedCount = document.getElementById('eqComboSelectedCount');
    const btnAddSelectedEqCombo = document.getElementById('btnAddSelectedEqCombo');

    // Drop indicator line element
    const eqDropIndicator = document.createElement('div');
    eqDropIndicator.className = 'combo-drop-indicator';

    // Drag state
    let eqCurrentDragState = null;

    const tabEquipmentComboCount = document.getElementById('tabEquipmentComboCount');

    // Fast counter and UI update
    function updateEqComboAssociatedCount() {
        if (!comboItemsContainer) return;
        const count = comboItemsContainer.querySelectorAll('.combo-item-row').length;
        if (eqComboAssociatedCount) eqComboAssociatedCount.textContent = count;
        if (tabEquipmentComboCount) tabEquipmentComboCount.textContent = count;
        if (noComboItemsAlert) noComboItemsAlert.style.display = count === 0 ? 'block' : 'none';
    }

    // Update bulk selection toolbar for equipment combo
    function updateEqComboSelectionToolbar() {
        if (!eqComboAvailableContainer) return;
        const totalVisible = eqComboAvailableContainer.querySelectorAll('.eq-combo-available-row').length;
        const checkedBoxes = eqComboAvailableContainer.querySelectorAll('.item-select-chk:checked');
        const count = checkedBoxes.length;

        if (eqComboSelectedCount) eqComboSelectedCount.textContent = count;

        if (count > 0) {
            eqComboSelectedCountBadge?.classList.remove('d-none');
            btnAddSelectedEqCombo?.classList.remove('d-none');
        } else {
            eqComboSelectedCountBadge?.classList.add('d-none');
            btnAddSelectedEqCombo?.classList.add('d-none');
        }

        if (selectAllEqComboAvailable) {
            selectAllEqComboAvailable.checked = totalVisible > 0 && count === totalVisible;
            selectAllEqComboAvailable.indeterminate = count > 0 && count < totalVisible;
        }

        // Highlight selected cards
        eqComboAvailableContainer.querySelectorAll('.eq-combo-available-row').forEach(card => {
            const chk = card.querySelector('.item-select-chk');
            if (chk && chk.checked) {
                card.classList.add('is-selected');
            } else {
                card.classList.remove('is-selected');
            }
        });
    }

    // Toggle select all available
    selectAllEqComboAvailable?.addEventListener('change', function () {
        if (!eqComboAvailableContainer) return;
        const isChecked = this.checked;
        eqComboAvailableContainer.querySelectorAll('.item-select-chk').forEach(chk => {
            chk.checked = isChecked;
        });
        updateEqComboSelectionToolbar();
    });

    // Form submission reindexer
    function reindexEqComboInputs() {
        if (!comboItemsContainer) return;
        const rows = comboItemsContainer.querySelectorAll('.combo-item-row');
        rows.forEach((row, index) => {
            const idInput = row.querySelector('.input-combo-item-id');
            const eqInput = row.querySelector('.input-combo-equipment-id');
            const qtyInput = row.querySelector('.input-combo-quantity');
            const priceInput = row.querySelector('.input-combo-extra-price');
            const seqInput = row.querySelector('.input-combo-sequence');

            if (idInput) idInput.name = `combo_items[${index}][id]`;
            if (eqInput) eqInput.name = `combo_items[${index}][child_equipment_id]`;
            if (qtyInput) qtyInput.name = `combo_items[${index}][quantity]`;
            if (priceInput) priceInput.name = `combo_items[${index}][extra_price]`;
            if (seqInput) {
                seqInput.name = `combo_items[${index}][sequence]`;
                seqInput.value = index;
            }
        });
    }

    equipmentForm?.addEventListener('submit', reindexEqComboInputs);

    function calculateComboTotals() {
        if (!comboItemsContainer) return { publicPrice: 0, costPrice: 0, count: 0 };
        const rows = comboItemsContainer.querySelectorAll('.combo-item-row');
        let totalPublic = 0;
        let totalCost = 0;

        rows.forEach(row => {
            const qtyInput = row.querySelector('.input-combo-quantity');
            const qty = parseInt(qtyInput ? qtyInput.value : row.dataset.quantity || 1, 10) || 1;
            
            const extraPriceInput = row.querySelector('.input-combo-extra-price');
            const unitPrice = extraPriceInput ? (parseFloat(extraPriceInput.value) || 0) : (parseFloat(row.dataset.unitPrice || row.dataset.price) || 0);
            const unitCost = parseFloat(row.dataset.costPrice) || 0;

            totalPublic += unitPrice * qty;
            totalCost += unitCost * qty;
        });

        return {
            publicPrice: Math.round(totalPublic * 100) / 100,
            costPrice: Math.round(totalCost * 100) / 100,
            count: rows.length
        };
    }

    function showForcedPriceOverrideToast(previousPrice, newPrice, previousCost, newCost, isAddAction = false) {
        let toastContainer = document.getElementById('comboToastContainer');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'comboToastContainer';
            toastContainer.className = 'position-fixed bottom-0 end-0 p-3';
            toastContainer.style.zIndex = '1090';
            document.body.appendChild(toastContainer);
        }

        const priceWarningTemplate = isAddAction
            ? `{{ __('locale.combo.forced_price_item_added_warning', ['forced' => '__FORCED__', 'new' => '__NEW__']) }}`
            : `{{ __('locale.combo.forced_price_warning', ['forced' => '__FORCED__', 'new' => '__NEW__']) }}`;

        let message = priceWarningTemplate
            .replace('__FORCED__', Number(previousPrice || 0).toFixed(2))
            .replace('__NEW__', Number(newPrice || 0).toFixed(2));

        if (previousCost !== null && Math.abs(previousCost - newCost) > 0.001) {
            const costWarningTemplate = isAddAction
                ? ` {{ __('locale.combo.forced_cost_item_added_warning', ['new' => '__NEWCOST__']) }}`
                : ` {{ __('locale.combo.forced_cost_warning', ['new' => '__NEWCOST__']) }}`;
            const costMsg = costWarningTemplate
                .replace('__NEWCOST__', Number(newCost || 0).toFixed(2));
            message += '<br>' + costMsg;
        }

        const toastEl = document.createElement('div');
        toastEl.className = 'toast align-items-center text-bg-warning border-0 show shadow-lg mb-2';
        toastEl.role = 'alert';
        toastEl.ariaLive = 'assertive';
        toastEl.ariaAtomic = 'true';
        toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body py-2 px-3 fw-medium">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-6"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        toastContainer.appendChild(toastEl);

        const bsToast = new bootstrap.Toast(toastEl, { delay: 7000 });
        bsToast.show();
        toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
    }

    function syncComboPricesToInputs(triggerAlertIfOverriding = false, isAddAction = false, explicitForcedPublic = null, explicitForcedCost = null) {
        const isCombo = document.getElementById('is_combo')?.checked;
        if (!isCombo) return;

        const totals = calculateComboTotals();
        if (totals.count === 0) return;

        const priceInput = document.getElementById('price_per_unit');
        const priceTabInput = document.getElementById('price_per_unit_tab');
        const purchaseInput = document.getElementById('purchase_price');
        const purchaseTabInput = document.getElementById('purchase_price_tab');

        const currentPublic = explicitForcedPublic !== null ? explicitForcedPublic : (parseFloat(priceInput ? priceInput.value : 0) || 0);
        const currentCost = explicitForcedCost !== null ? explicitForcedCost : (parseFloat(purchaseInput ? purchaseInput.value : 0) || 0);

        const hadForcedPublic = (explicitForcedPublic !== null || isPublicPriceForced) && Math.abs(currentPublic - totals.publicPrice) > 0.001;
        const hadForcedCost = (explicitForcedCost !== null || isCostPriceForced) && Math.abs(currentCost - totals.costPrice) > 0.001;

        if (triggerAlertIfOverriding && (hadForcedPublic || hadForcedCost)) {
            showForcedPriceOverrideToast(
                hadForcedPublic ? currentPublic : totals.publicPrice,
                totals.publicPrice,
                hadForcedCost ? currentCost : totals.costPrice,
                totals.costPrice,
                isAddAction
            );
        }

        isPublicPriceForced = false;
        isCostPriceForced = false;
        forcedPublicPrice = null;
        forcedCostPrice = null;
        updateComboPriceBadges();

        isProgrammaticPriceUpdate = true;
        const newPubStr = totals.publicPrice.toFixed(2);
        const newCostStr = totals.costPrice.toFixed(2);

        if (priceInput) {
            priceInput.value = newPubStr;
            priceInput.dispatchEvent(new Event('input', { bubbles: true }));
            priceInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
        if (priceTabInput) {
            priceTabInput.value = newPubStr;
            priceTabInput.dispatchEvent(new Event('input', { bubbles: true }));
            priceTabInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
        if (purchaseInput) {
            purchaseInput.value = newCostStr;
            purchaseInput.dispatchEvent(new Event('input', { bubbles: true }));
            purchaseInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
        if (purchaseTabInput) {
            purchaseTabInput.value = newCostStr;
            purchaseTabInput.dispatchEvent(new Event('input', { bubbles: true }));
            purchaseTabInput.dispatchEvent(new Event('change', { bubbles: true }));
        }

        isProgrammaticPriceUpdate = false;
    }

    function createEqComboAssociatedRow(data) {
        const div = document.createElement('div');
        div.className = 'combo-item-card draggable-item combo-item-row';
        div.draggable = true;
        div.dataset.id = data.id;
        if (data.itemId) div.dataset.itemId = data.itemId;
        div.dataset.ref = data.ref || 'N/A';
        div.dataset.name = data.name || '';
        div.dataset.price = data.price || '0.00';
        div.dataset.unitPrice = data.unitPrice || data.price || '0.00';
        div.dataset.costPrice = data.costPrice || '0.00';
        const qty = parseInt(data.quantity, 10) > 0 ? parseInt(data.quantity, 10) : 1;
        div.dataset.quantity = qty;

        div.innerHTML = `
            <div class="card-drag-handle" title="{{ __('locale.combo.drag_to_reorder') }}">
                <i class="bi bi-grip-vertical"></i>
                ${data.itemId ? `<input type="hidden" class="input-combo-item-id" value="${data.itemId}">` : ''}
                <input type="hidden" class="input-combo-equipment-id" value="${data.id}">
                <input type="hidden" class="input-combo-sequence" value="0">
            </div>
            <div class="card-item-main d-flex align-items-center gap-2">
                <span class="item-ref-badge" title="${escapeHtml(data.ref || 'N/A')}">${escapeHtml(data.ref || 'N/A')}</span>
                <span class="card-item-title" title="${escapeHtml(data.name)}">${escapeHtml(data.name)}</span>
            </div>
            <div class="card-item-qty">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted px-1" style="font-size: 0.72rem;">{{ __('locale.qty') }}</span>
                    <input type="number" step="1" min="1" class="form-control form-control-sm text-center fw-bold input-combo-quantity" value="${qty}" data-prev-qty="${qty}">
                </div>
            </div>
            <div class="card-item-price">
                <div class="input-group input-group-sm">
                    <input type="number" step="0.01" min="0" class="form-control form-control-sm text-end fw-bold input-combo-extra-price" value="${data.price || '0.00'}">
                    <span class="input-group-text bg-light text-muted px-1">€</span>
                </div>
            </div>
            <div class="card-item-action">
                <button type="button" class="btn-action-icon btn-remove btn-remove-combo-item" title="{{ __('locale.delete') ?? 'Supprimer' }}">
                    <i class="bi bi-x fs-5"></i>
                </button>
            </div>
        `;
        return div;
    }

    function updateEqComboAvailableCount() {
        if (!eqComboAvailableContainer) return;
        const count = eqComboAvailableContainer.querySelectorAll('.eq-combo-available-row').length;
        if (eqComboAvailableCount) {
            eqComboAvailableCount.textContent = count;
        }
        if (noEqComboAvailableResults) {
            noEqComboAvailableResults.style.display = count === 0 ? 'block' : 'none';
        }
    }

    function addEqToCombo(itemData, insertBeforeNode = null) {
        if (!comboItemsContainer) return false;
        const existing = comboItemsContainer.querySelector(`.combo-item-row[data-id="${itemData.id}"]`);
        if (existing) {
            existing.classList.remove('duplicate-flash');
            void existing.offsetWidth; // Restart animation
            existing.classList.add('duplicate-flash');
            existing.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            return false;
        }

        const preTotals = calculateComboTotals();
        const priceInput = document.getElementById('price_per_unit');
        const purchaseInput = document.getElementById('purchase_price');
        const currentPublic = parseFloat(priceInput ? priceInput.value : 0) || 0;
        const currentCost = parseFloat(purchaseInput ? purchaseInput.value : 0) || 0;
        const isCurrentlyForcedPub = isPublicPriceForced || (preTotals.count > 0 && Math.abs(currentPublic - preTotals.publicPrice) > 0.001);
        const isCurrentlyForcedCost = isCostPriceForced || (preTotals.count > 0 && Math.abs(currentCost - preTotals.costPrice) > 0.001);
        const forcedPubVal = isCurrentlyForcedPub ? currentPublic : null;
        const forcedCostVal = isCurrentlyForcedCost ? currentCost : null;

        const qty = parseInt(itemData.quantity, 10) > 0 ? parseInt(itemData.quantity, 10) : 1;
        itemData.quantity = qty;
        const newRow = createEqComboAssociatedRow(itemData);
        if (insertBeforeNode && insertBeforeNode.parentNode === comboItemsContainer) {
            comboItemsContainer.insertBefore(newRow, insertBeforeNode);
        } else {
            comboItemsContainer.appendChild(newRow);
        }

        // Remove from available list if present
        const availableRow = eqComboAvailableContainer?.querySelector(`.eq-combo-available-row[data-id="${itemData.id}"]`);
        if (availableRow) {
            availableRow.remove();
        }

        updateEqComboAssociatedCount();
        updateEqComboAvailableCount();

        // Automatically sync combo prices and notify that forced price will be changed
        syncComboPricesToInputs(true, true, forcedPubVal, forcedCostVal);

        return true;
    }

    const comboItemsScrollArea = document.getElementById('comboItemsScrollArea');

    // --- NATIVE DRAG & DROP ENGINE ---
    function cleanupEqDrag() {
        document.querySelectorAll('.is-dragging').forEach(el => el.classList.remove('is-dragging'));
        if (eqDropIndicator.parentNode) {
            eqDropIndicator.parentNode.removeChild(eqDropIndicator);
        }
        eqCurrentDragState = null;
    }

    document.addEventListener('dragstart', function (e) {
        if (e.target.closest('input, button, .btn, .input-group, select, textarea, a')) {
            return;
        }

        const availableCard = e.target.closest('.eq-combo-available-row');
        if (availableCard && eqComboAvailableContainer?.contains(availableCard)) {
            eqCurrentDragState = {
                source: 'available',
                items: [{
                    id: availableCard.dataset.id,
                    ref: availableCard.dataset.ref,
                    name: availableCard.dataset.name,
                    price: availableCard.dataset.price,
                    costPrice: availableCard.dataset.costPrice || '0.00',
                    element: availableCard
                }]
            };

            availableCard.classList.add('is-dragging');
            e.dataTransfer.effectAllowed = 'copyMove';
            e.dataTransfer.setData('text/plain', 'combo-item');
            return;
        }

        const associatedCard = e.target.closest('.combo-item-row');
        if (associatedCard && comboItemsContainer?.contains(associatedCard)) {
            eqCurrentDragState = {
                source: 'associated',
                items: [{
                    id: associatedCard.dataset.id,
                    element: associatedCard
                }]
            };

            associatedCard.classList.add('is-dragging');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', 'combo-item');
        }
    });

    // Dragover handler for combo items container and scroll area
    function onEqComboDragOver(e) {
        if (!eqCurrentDragState) return;
        e.preventDefault();
        e.dataTransfer.dropEffect = eqCurrentDragState.source === 'associated' ? 'move' : 'copy';

        const rows = Array.from(comboItemsContainer.querySelectorAll('.combo-item-row:not(.is-dragging)'));
        let targetBeforeRow = null;

        for (const row of rows) {
            const rect = row.getBoundingClientRect();
            const midpoint = rect.top + rect.height / 2;
            if (e.clientY < midpoint) {
                targetBeforeRow = row;
                break;
            }
        }

        if (targetBeforeRow) {
            if (eqDropIndicator.nextSibling !== targetBeforeRow) {
                comboItemsContainer.insertBefore(eqDropIndicator, targetBeforeRow);
            }
        } else {
            if (eqDropIndicator.parentNode !== comboItemsContainer || eqDropIndicator.nextSibling !== null) {
                comboItemsContainer.appendChild(eqDropIndicator);
            }
        }
    }

    // Drop handler for combo items container and scroll area
    function onEqComboDrop(e) {
        if (!eqCurrentDragState) return;
        e.preventDefault();
        e.stopPropagation();

        const insertBeforeNode = (eqDropIndicator.parentNode === comboItemsContainer) ? eqDropIndicator.nextSibling : null;
        if (eqDropIndicator.parentNode) {
            eqDropIndicator.parentNode.removeChild(eqDropIndicator);
        }

        if (eqCurrentDragState.source === 'available') {
            eqCurrentDragState.items.forEach(item => {
                addEqToCombo(item, insertBeforeNode);
            });
        } else if (eqCurrentDragState.source === 'associated') {
            const draggedRow = eqCurrentDragState.items[0].element;
            if (draggedRow && insertBeforeNode !== draggedRow) {
                if (insertBeforeNode) {
                    comboItemsContainer.insertBefore(draggedRow, insertBeforeNode);
                } else {
                    comboItemsContainer.appendChild(draggedRow);
                }
                updateEqComboAssociatedCount();
            }
        }

        cleanupEqDrag();
    }

    comboItemsContainer?.addEventListener('dragover', onEqComboDragOver);
    comboItemsScrollArea?.addEventListener('dragover', onEqComboDragOver);
    comboItemsContainer?.addEventListener('drop', onEqComboDrop);
    comboItemsScrollArea?.addEventListener('drop', onEqComboDrop);

    document.addEventListener('dragend', function () {
        cleanupEqDrag();
    });

    btnAddSelectedEqCombo?.addEventListener('click', function () {
        if (!eqComboAvailableContainer) return;
        const checkedCards = eqComboAvailableContainer.querySelectorAll('.eq-combo-available-row .item-select-chk:checked');
        checkedCards.forEach(chk => {
            const card = chk.closest('.eq-combo-available-row');
            if (card) {
                addEqToCombo({
                    id: card.dataset.id,
                    ref: card.dataset.ref,
                    name: card.dataset.name,
                    price: card.dataset.price,
                    costPrice: card.dataset.costPrice || '0.00'
                });
            }
        });
        updateEqComboSelectionToolbar();
    });

    eqComboAvailableContainer?.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-add-eq-combo');
        if (btn) {
            const card = btn.closest('.eq-combo-available-row');
            if (card) {
                addEqToCombo({
                    id: card.dataset.id,
                    ref: card.dataset.ref,
                    name: card.dataset.name,
                    price: card.dataset.price,
                    costPrice: card.dataset.costPrice || '0.00'
                });
            }
        }
    });

    comboItemsContainer?.addEventListener('input', function (e) {
        const qtyInput = e.target.closest('.input-combo-quantity');
        if (qtyInput) {
            const row = qtyInput.closest('.combo-item-row');
            if (!row) return;

            let newQty = parseInt(qtyInput.value, 10);
            if (isNaN(newQty) || newQty < 1) {
                newQty = 1;
            }
            qtyInput.dataset.prevQty = newQty;
            row.dataset.quantity = newQty;

            syncComboPricesToInputs(true);
            return;
        }

        const priceInput = e.target.closest('.input-combo-extra-price');
        if (priceInput) {
            const row = priceInput.closest('.combo-item-row');
            if (!row) return;

            const newPrice = parseFloat(priceInput.value) || 0;
            row.dataset.price = newPrice.toFixed(2);
            row.dataset.unitPrice = newPrice.toFixed(2);

            syncComboPricesToInputs(true);
            return;
        }
    });

    comboItemsContainer?.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-remove-combo-item');
        if (btn) {
            const row = btn.closest('.combo-item-row');
            if (row) {
                row.remove();
                updateEqComboAssociatedCount();
                syncComboPricesToInputs(true);
                fetchEqComboAvailable();
            }
        }
    });

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function renderEqComboAvailableCard(eq) {
        const div = document.createElement('div');
        div.className = 'combo-item-card draggable-item eq-combo-available-row';
        div.draggable = true;
        div.dataset.id = eq.id;
        div.dataset.ref = eq.reference || 'N/A';
        div.dataset.name = eq.name || '';
        div.dataset.price = eq.price || '0.00';
        div.dataset.priceFormatted = eq.price_formatted || (eq.price + ' €');
        div.dataset.costPrice = eq.cost_price || '0.00';
        div.dataset.costPriceFormatted = eq.cost_price_formatted || (eq.cost_price + ' €');
        div.dataset.category = eq.category_id || '';
        div.dataset.brand = eq.brand_id || '';
        div.dataset.type = eq.equipment_type_id || '';

        div.innerHTML = `
            <div class="card-item-action">
                <button type="button" class="btn-action-icon btn-action-icon-sm btn-add btn-add-eq-combo" title="{{ __('locale.add') ?? 'Ajouter' }}">
                    <i class="bi bi-plus"></i>
                </button>
            </div>
            <div class="card-drag-handle" title="{{ __('locale.combo.drag_to_left') }}">
                <i class="bi bi-grip-vertical"></i>
            </div>
            <div class="card-item-main d-flex align-items-center gap-2">
                <span class="item-ref-badge" title="${escapeHtml(eq.reference)}">${escapeHtml(eq.reference)}</span>
                <span class="card-item-title" title="${escapeHtml(eq.name)}">${escapeHtml(eq.name)}</span>
            </div>
            <div class="card-item-price-tag">
                ${escapeHtml(eq.price_formatted)}
            </div>
        `;
        return div;
    }

    // Advanced filters toggle & badge
    const btnToggleAdvancedFilters = document.getElementById('btnToggleAdvancedFilters');
    const eqComboAdvancedFilters = document.getElementById('eqComboAdvancedFilters');
    const activeFilterBadge = document.getElementById('activeFilterBadge');

    function updateActiveFilterBadge() {
        let count = 0;
        if (eqComboFilterCat?.value) count++;
        if (eqComboFilterBrand?.value) count++;
        if (eqComboFilterType?.value) count++;

        if (activeFilterBadge) {
            if (count > 0) {
                activeFilterBadge.textContent = count;
                activeFilterBadge.classList.remove('d-none');
                btnToggleAdvancedFilters?.classList.add('btn-primary');
                btnToggleAdvancedFilters?.classList.remove('btn-outline-secondary');
            } else {
                activeFilterBadge.classList.add('d-none');
                btnToggleAdvancedFilters?.classList.remove('btn-primary');
                btnToggleAdvancedFilters?.classList.add('btn-outline-secondary');
            }
        }
    }

    btnToggleAdvancedFilters?.addEventListener('click', function () {
        if (!eqComboAdvancedFilters) return;
        const isHidden = eqComboAdvancedFilters.style.display === 'none';
        eqComboAdvancedFilters.style.display = isHidden ? 'block' : 'none';
    });

    let eqSearchDebounceTimer = null;
    let eqSearchAbortController = null;

    function fetchEqComboAvailable() {
        if (eqSearchDebounceTimer) clearTimeout(eqSearchDebounceTimer);

        eqSearchDebounceTimer = setTimeout(() => {
            if (eqSearchAbortController) {
                eqSearchAbortController.abort();
            }
            eqSearchAbortController = new AbortController();

            const query = (eqComboSearchInput?.value || '').trim();
            const selectedCat = eqComboFilterCat?.value || '';
            const selectedBrand = eqComboFilterBrand?.value || '';
            const selectedType = eqComboFilterType?.value || '';

            const associatedIds = Array.from(comboItemsContainer?.querySelectorAll('.combo-item-row') || [])
                .map(r => r.dataset.id)
                .filter(Boolean);

            const params = new URLSearchParams();
            params.append('exclude_id', '{{ $equipment->id }}');
            if (associatedIds.length > 0) {
                params.append('exclude_ids', associatedIds.join(','));
            }
            if (query) params.append('search', query);
            if (selectedCat) params.append('category_id', selectedCat);
            if (selectedBrand) params.append('brand_id', selectedBrand);
            if (selectedType) params.append('equipment_type_id', selectedType);

            fetch(`{{ route('combos.available-equipments') }}?${params.toString()}`, {
                signal: eqSearchAbortController.signal,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (!eqComboAvailableContainer) return;
                eqComboAvailableContainer.innerHTML = '';

                if (data.items && data.items.length > 0) {
                    const fragment = document.createDocumentFragment();
                    data.items.forEach(eq => {
                        fragment.appendChild(renderEqComboAvailableCard(eq));
                    });
                    eqComboAvailableContainer.appendChild(fragment);
                    if (noEqComboAvailableResults) noEqComboAvailableResults.style.display = 'none';
                } else {
                    if (noEqComboAvailableResults) noEqComboAvailableResults.style.display = 'block';
                }

                if (eqComboAvailableCount) {
                    eqComboAvailableCount.textContent = data.count || 0;
                }
            })
            .catch(err => {
                if (err.name !== 'AbortError') {
                    console.error('Error fetching available equipments:', err);
                }
            });
        }, 200);
    }

    eqComboSearchInput?.addEventListener('input', fetchEqComboAvailable);
    eqComboFilterCat?.addEventListener('change', () => {
        updateActiveFilterBadge();
        fetchEqComboAvailable();
    });
    eqComboFilterBrand?.addEventListener('change', () => {
        updateActiveFilterBadge();
        fetchEqComboAvailable();
    });
    eqComboFilterType?.addEventListener('change', () => {
        updateActiveFilterBadge();
        fetchEqComboAvailable();
    });

    function updateBottomBarVisibility() {
        const activeTab = document.querySelector('#equipmentTab .nav-link.active');
        const bottomBar = document.getElementById('eqBottomBar');
        if (bottomBar) {
            if (activeTab && activeTab.id === 'combos-tab') {
                bottomBar.style.display = 'none';
            } else {
                bottomBar.style.display = '';
            }
        }
    }

    document.querySelectorAll('#equipmentTab button[data-bs-toggle="tab"]').forEach(tabBtn => {
        tabBtn.addEventListener('shown.bs.tab', function () {
            updateBottomBarVisibility();
        });
    });

    document.getElementById('combos-tab')?.addEventListener('shown.bs.tab', function () {
        updateEqComboAssociatedCount();
        $('#combo_ids').select2({
            width: '100%',
            placeholder: "{{ __('locale.combo.select_combos') }}"
        });
    });

    // Recurring rules table dynamic add/remove
    let nextRecurringIndex = {{ (isset($existingRules) ? count($existingRules) : 0) + 100 }};
    const addRecurringRuleBtn = document.getElementById('addRecurringRuleBtn');
    const recurringRulesBody = document.getElementById('recurringRulesBody');

    if (addRecurringRuleBtn && recurringRulesBody) {
        addRecurringRuleBtn.addEventListener('click', function () {
            const idx = nextRecurringIndex++;
            const tr = document.createElement('tr');
            tr.className = 'recurring-rule-row';
            tr.dataset.index = idx;

            let planOptions = `<option value="">{{ __('locale.equipment_form.lbl_select_plan') }}</option>`;
            @foreach($recurringPlans as $rp)
                planOptions += `<option value="{{ $rp->id }}">{{ addslashes($rp->name) }}</option>`;
            @endforeach

            let plOptions = `<option value="">{{ __('locale.equipment_form.lbl_all_pricelists') }}</option>`;
            @foreach($pricelists as $pl)
                plOptions += `<option value="{{ $pl->id }}">{{ addslashes($pl->name) }}</option>`;
            @endforeach

            let compOptions = `<option value="">{{ __('locale.equipment_form.lbl_all_companies') }}</option>`;
            @foreach($companies as $c)
                compOptions += `<option value="{{ $c->id }}">{{ addslashes($c->name) }}</option>`;
            @endforeach

            tr.innerHTML = `
                <td>
                    <select name="recurring_rules[${idx}][recurring_plan_id]" class="form-select form-select-sm" required>
                        ${planOptions}
                    </select>
                </td>
                <td>
                    <select name="recurring_rules[${idx}][pricelist_id]" class="form-select form-select-sm">
                        ${plOptions}
                    </select>
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <input type="number" step="0.01" min="0" name="recurring_rules[${idx}][fixed_price]" class="form-control form-control-sm text-end" placeholder="0.00">
                        <span class="input-group-text py-0 px-1 small">{{ __('locale.currency') }}</span>
                    </div>
                </td>
                <td>
                    <input type="date" name="recurring_rules[${idx}][date_start]" class="form-control form-control-sm">
                </td>
                <td>
                    <input type="date" name="recurring_rules[${idx}][date_end]" class="form-control form-control-sm">
                </td>
                <td>
                    <select name="recurring_rules[${idx}][company_id]" class="form-select form-select-sm">
                        ${compOptions}
                    </select>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-link text-danger p-0 delete-recurring-rule-btn" title="Supprimer la ligne">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;

            recurringRulesBody.appendChild(tr);
        });

        recurringRulesBody.addEventListener('click', function (e) {
            const delBtn = e.target.closest('.delete-recurring-rule-btn');
            if (delBtn) {
                const tr = delBtn.closest('tr');
                if (tr) {
                    tr.remove();
                }
            }
        });
    }

    // Initial check on load for forced price / cost badges
    if (isComboSwitch && isComboSwitch.checked) {
        const totals = calculateComboTotals();
        if (totals.count > 0) {
            const currentPublic = parseFloat($('#price_per_unit').val()) || 0;
            const currentCost = parseFloat($('#purchase_price').val()) || 0;
            if (Math.abs(currentPublic - totals.publicPrice) > 0.001) {
                isPublicPriceForced = true;
                forcedPublicPrice = currentPublic;
            }
            if (Math.abs(currentCost - totals.costPrice) > 0.001) {
                isCostPriceForced = true;
                forcedCostPrice = currentCost;
            }
            updateComboPriceBadges();
        }
    }

    // ==========================================
    // KIT COMPONENTS MANAGEMENT LOGIC (MATCHING COMBO DESIGN)
    // ==========================================
    const canSeeCost = {{ $canSeeCost ? 'true' : 'false' }};
    const canChangePrice = {{ $canChangePrice ? 'true' : 'false' }};

    const kitItemsContainer = document.getElementById('kitItemsContainer');
    const kitItemsScrollArea = document.getElementById('kitItemsScrollArea');
    const kitAvailableContainer = document.getElementById('kitAvailableContainer');
    const noKitItemsAlert = document.getElementById('noKitItemsAlert');
    const kitAssociatedCount = document.getElementById('kitAssociatedCount');
    const kitAvailableCount = document.getElementById('kitAvailableCount');
    const tabEquipmentKitCount = document.getElementById('tabEquipmentKitCount');
    const kitTotalCostDisplay = document.getElementById('kitTotalCostDisplay');
    const kitTotalSaleDisplay = document.getElementById('kitTotalSaleDisplay');
    const kitPriceChangeAlert = document.getElementById('kitPriceChangeAlert');
    const btnAlertApplyNewPrice = document.getElementById('btnAlertApplyNewPrice');
    const btnAlertKeepOldPrice = document.getElementById('btnAlertKeepOldPrice');
    let priceChoiceExplicitlyMade = null;
    let priceManuallyEdited = false;
    let kitInitialized = false;
    const initialDbIsManual = {{ ($equipment->is_kit_price_manual ?? false) ? 'true' : 'false' }};
    let savedPriceWasDifferent = initialDbIsManual;

    function showKitPriceAlert() {
        if (!kitPriceChangeAlert) return;
        kitPriceChangeAlert.classList.remove('d-none');
        kitPriceChangeAlert.classList.add('d-flex');
    }

    function hideKitPriceAlert() {
        if (!kitPriceChangeAlert) return;
        kitPriceChangeAlert.classList.remove('d-flex');
        kitPriceChangeAlert.classList.add('d-none');
    }

    const kitSearchInput = document.getElementById('kitSearchInput');
    const kitFilterCat = document.getElementById('kitFilterCat');
    const kitFilterBrand = document.getElementById('kitFilterBrand');
    const kitFilterType = document.getElementById('kitFilterType');
    const btnToggleKitAdvancedFilters = document.getElementById('btnToggleKitAdvancedFilters');
    const kitAdvancedFilters = document.getElementById('kitAdvancedFilters');
    const kitActiveFilterBadge = document.getElementById('kitActiveFilterBadge');
    const noKitAvailableResults = document.getElementById('noKitAvailableResults');

    const kitDropIndicator = document.createElement('div');
    kitDropIndicator.className = 'combo-drop-indicator';

    let kitCurrentDragState = null;

    function updateKitActiveFilterBadge() {
        let count = 0;
        if (kitFilterCat?.value) count++;
        if (kitFilterBrand?.value) count++;
        if (kitFilterType?.value) count++;

        if (kitActiveFilterBadge) {
            if (count > 0) {
                kitActiveFilterBadge.textContent = count;
                kitActiveFilterBadge.classList.remove('d-none');
                btnToggleKitAdvancedFilters?.classList.add('btn-primary');
                btnToggleKitAdvancedFilters?.classList.remove('btn-outline-secondary');
            } else {
                kitActiveFilterBadge.classList.add('d-none');
                btnToggleKitAdvancedFilters?.classList.remove('btn-primary');
                btnToggleKitAdvancedFilters?.classList.add('btn-outline-secondary');
            }
        }
    }

    btnToggleKitAdvancedFilters?.addEventListener('click', function () {
        if (!kitAdvancedFilters) return;
        const isHidden = kitAdvancedFilters.style.display === 'none';
        kitAdvancedFilters.style.display = isHidden ? 'block' : 'none';
    });

    function updateKitTotals() {
        if (!kitItemsContainer) return;
        const rows = kitItemsContainer.querySelectorAll('.kit-item-row');
        let totalCost = 0;
        let totalSale = 0;

        rows.forEach((row, idx) => {
            const seqInput = row.querySelector('.kit-item-seq');
            if (seqInput) seqInput.value = idx;

            const cost = parseFloat(row.dataset.cost) || 0;
            const price = parseFloat(row.dataset.price) || 0;
            const qtyInput = row.querySelector('.kit-item-qty');
            const qty = parseFloat(qtyInput ? qtyInput.value : row.dataset.quantity || 1) || 1;

            totalCost += (cost * qty);
            totalSale += (price * qty);

            const rowPriceTag = row.querySelector('.kit-item-total-price') || row.querySelector('.kit-item-total-cost');
            if (rowPriceTag) {
                const rowPrice = canSeeCost ? (cost * qty) : (price * qty);
                rowPriceTag.textContent = rowPrice.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';
            }
        });

        const count = rows.length;
        if (kitAssociatedCount) kitAssociatedCount.textContent = count;
        if (tabEquipmentKitCount) tabEquipmentKitCount.textContent = count;
        if (noKitItemsAlert) noKitItemsAlert.style.display = count === 0 ? 'block' : 'none';
        if (kitTotalCostDisplay && canSeeCost) kitTotalCostDisplay.textContent = totalCost.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';
        if (kitTotalSaleDisplay) kitTotalSaleDisplay.textContent = totalSale.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';

        const totals = { totalCost, totalSale, count };

        if (kitInitialized && isKitSwitch?.checked && !savedPriceWasDifferent && !priceManuallyEdited && count > 0 && canChangePrice && initialSavedPrice === 0) {
            applyKitTotalsToInputs(totalSale, totalCost);
            setKitPriceManualState(false);
        }

        checkPriceDifference(totals);

        return totals;
    }

    function checkPriceDifference(totals) {
        if (!kitPriceChangeAlert) return;
        if (!totals || totals.count === 0) {
            hideKitPriceAlert();
            return;
        }

        const currentSale = parseFloat($('#price_per_unit').val()) || 0;
        const currentCost = parseFloat($('#purchase_price').val()) || 0;
        const newSale = Math.round(totals.totalSale * 100) / 100;
        const newCost = Math.round(totals.totalCost * 100) / 100;

        const saleDiffers = Math.abs(currentSale - newSale) > 0.009;
        const costDiffers = Math.abs(currentCost - newCost) > 0.009;

        if (priceChoiceExplicitlyMade === 'kept' || priceChoiceExplicitlyMade === 'applied') {
            hideKitPriceAlert();
            return;
        }

        // On initial page load, if manual price is set, keep manual price without nagging
        if (!kitInitialized && initialDbIsManual) {
            hideKitPriceAlert();
            return;
        }

        const priceDiffers = canSeeCost ? (saleDiffers || costDiffers) : saleDiffers;

        if (!priceDiffers) {
            hideKitPriceAlert();
            return;
        }

        const formattedCurrentSale = currentSale.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';
        const formattedNewSale = newSale.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';
        const formattedCurrentCost = currentCost.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';
        const formattedNewCost = newCost.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';

        const titleEl = document.getElementById('kitAlertTitle');
        const oldLabelEl = document.getElementById('kitAlertOldLabel');
        const newLabelEl = document.getElementById('kitAlertNewLabel');
        const oldPriceEl = document.getElementById('kitAlertOldPrice');
        const newPriceEl = document.getElementById('kitAlertNewPrice');
        const costInfoEl = document.getElementById('kitAlertCostInfo');

        if (saleDiffers) {
            if (titleEl) titleEl.textContent = "{{ __('locale.kit.price_changed_notice') ?? 'Le prix des composants a changé' }} :";
            if (oldLabelEl) oldLabelEl.textContent = "{{ __('locale.kit.current_price') ?? 'Prix actuel' }}";
            if (newLabelEl) newLabelEl.textContent = "{{ __('locale.kit.new_price') ?? 'Nouveau prix' }}";
            if (oldPriceEl) oldPriceEl.textContent = formattedCurrentSale;
            if (newPriceEl) newPriceEl.textContent = formattedNewSale;

            if (canSeeCost && costDiffers && costInfoEl) {
                costInfoEl.textContent = `({{ __('locale.kit.unit_cost') ?? 'Coût' }} : ${formattedCurrentCost} → ${formattedNewCost})`;
                costInfoEl.style.display = 'inline';
            } else if (costInfoEl) {
                costInfoEl.style.display = 'none';
            }
        } else if (canSeeCost && costDiffers) {
            if (titleEl) titleEl.textContent = "{{ __('locale.kit.cost_changed_notice') ?? 'Le coût des composants a changé' }} :";
            if (oldLabelEl) oldLabelEl.textContent = "{{ __('locale.kit.current_cost') ?? 'Coût actuel' }}";
            if (newLabelEl) newLabelEl.textContent = "{{ __('locale.kit.new_cost') ?? 'Nouveau coût' }}";
            if (oldPriceEl) oldPriceEl.textContent = formattedCurrentCost;
            if (newPriceEl) newPriceEl.textContent = formattedNewCost;
            if (costInfoEl) costInfoEl.style.display = 'none';
        }

        showKitPriceAlert();
    }

    function setKitPriceManualState(isManual) {
        const isKitPriceManualInput = document.getElementById('is_kit_price_manual');
        const kitPriceStatusBadge = document.getElementById('kitPriceStatusBadge');
        const kitPriceStatusBadgeIcon = document.getElementById('kitPriceStatusBadgeIcon');
        const kitPriceStatusBadgeText = document.getElementById('kitPriceStatusBadgeText');

        if (isKitPriceManualInput) {
            isKitPriceManualInput.value = isManual ? '1' : '0';
        }
        if (kitPriceStatusBadge && kitPriceStatusBadgeText) {
            if (isManual) {
                kitPriceStatusBadge.className = 'badge bg-warning text-dark px-2 py-1';
                kitPriceStatusBadge.title = "{{ __('locale.kit.manual_price_tooltip') ?? 'Le prix de ce kit a été défini manuellement' }}";
                if (kitPriceStatusBadgeIcon) kitPriceStatusBadgeIcon.className = 'bi bi-pencil me-1';
                kitPriceStatusBadgeText.textContent = "{{ __('locale.kit.badge_price_manual') ?? 'Prix manuel' }}";
            } else {
                kitPriceStatusBadge.className = 'badge bg-info-subtle text-info border border-info-subtle px-2 py-1';
                kitPriceStatusBadge.title = "{{ __('locale.kit.auto_price_tooltip') ?? 'Le prix de ce kit est synchronisé automatiquement avec ses composants' }}";
                if (kitPriceStatusBadgeIcon) kitPriceStatusBadgeIcon.className = 'bi bi-magic me-1';
                kitPriceStatusBadgeText.textContent = "{{ __('locale.kit.badge_price_auto') ?? 'Prix auto' }}";
            }
        }
    }

    function applyKitTotalsToInputs(totalSale, totalCost) {
        const pInput = document.getElementById('price_per_unit');
        const pTabInput = document.getElementById('price_per_unit_tab');
        const cInput = document.getElementById('purchase_price');
        const cTabInput = document.getElementById('purchase_price_tab');

        const valSale = typeof totalSale === 'number' ? totalSale.toFixed(2) : totalSale;
        const valCost = typeof totalCost === 'number' ? totalCost.toFixed(2) : totalCost;

        if (pInput) pInput.value = valSale;
        if (pTabInput) pTabInput.value = valSale;
        if (cInput) cInput.value = valCost;
        if (cTabInput) cTabInput.value = valCost;
        if (!isOdpPriceManuallyChanged) {
            const odpInput = document.getElementById('odp_purchase_price');
            if (odpInput) odpInput.value = valCost;
        }
    }

    function renderKitItemRow(data) {
        const id = data.id;
        const itemId = data.itemId || '';
        const ref = data.ref || 'N/A';
        const name = data.name || 'Article';
        const cost = parseFloat(data.cost || data.costPrice || 0) || 0;
        const price = parseFloat(data.price || 0) || 0;
        const qty = parseFloat(data.quantity) > 0 ? parseFloat(data.quantity) : 1;
        const index = kitItemsContainer ? kitItemsContainer.querySelectorAll('.kit-item-row').length : 0;

        const rowPrice = canSeeCost ? (cost * qty) : (price * qty);
        const rowPriceTitle = canSeeCost ? "{{ __('locale.kit.total_cost') ?? 'Coût total' }}" : "{{ __('locale.total_price') ?? 'Prix total' }}";

        const div = document.createElement('div');
        div.className = 'combo-item-card draggable-item kit-item-row';
        div.draggable = true;
        div.dataset.id = id;
        if (itemId) div.dataset.itemId = itemId;
        div.dataset.ref = ref;
        div.dataset.name = name;
        div.dataset.cost = cost;
        div.dataset.price = price;
        div.dataset.quantity = qty;

        div.innerHTML = `
            <div class="card-drag-handle" title="{{ __('locale.combo.drag_to_reorder') }}">
                <i class="bi bi-grip-vertical"></i>
                ${itemId ? `<input type="hidden" name="kit_items[${index}][id]" value="${itemId}">` : ''}
                <input type="hidden" name="kit_items[${index}][child_equipment_id]" value="${id}">
                <input type="hidden" class="kit-item-seq" name="kit_items[${index}][sequence]" value="${index}">
                <input type="hidden" name="kit_items[${index}][cost_price]" value="${cost.toFixed(2)}">
            </div>
            <div class="card-item-main d-flex align-items-center gap-2">
                <span class="item-ref-badge" title="${escapeHtml(ref)}">${escapeHtml(ref)}</span>
                <span class="card-item-title" title="${escapeHtml(name)}">${escapeHtml(name)}</span>
            </div>
            <div class="card-item-qty">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted px-1" style="font-size: 0.72rem;">{{ __('locale.qty') }}</span>
                    <input type="number" step="any" min="0.01" class="form-control form-control-sm text-center fw-bold kit-item-qty" name="kit_items[${index}][quantity]" value="${qty}" style="font-size: 0.8rem;">
                </div>
            </div>
            <div class="card-item-price-tag me-2 kit-item-total-price" title="${rowPriceTitle}">
                ${rowPrice.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} €
            </div>
            <div class="card-item-action">
                <button type="button" class="btn-action-icon btn-remove btn-remove-kit-item" title="{{ __('locale.remove') ?? 'Supprimer' }}">
                    <i class="bi bi-x fs-5"></i>
                </button>
            </div>
        `;
        return div;
    }

    function renderKitAvailableCard(eq) {
        const div = document.createElement('div');
        div.className = 'combo-item-card draggable-item eq-kit-available-row';
        div.draggable = true;
        div.dataset.id = eq.id;
        div.dataset.ref = eq.reference || 'N/A';
        div.dataset.name = eq.name;
        div.dataset.price = eq.price;
        div.dataset.priceFormatted = eq.price_formatted;
        div.dataset.cost = canSeeCost ? eq.cost_price : '0.00';
        div.dataset.costFormatted = canSeeCost ? eq.cost_price_formatted : '0,00 €';
        div.dataset.category = eq.category_id || '';
        div.dataset.brand = eq.brand_id || '';
        div.dataset.type = eq.equipment_type_id || '';

        const cardDisplayPrice = canSeeCost
            ? (eq.cost_price_formatted || (parseFloat(eq.cost_price || 0).toFixed(2) + ' €'))
            : (eq.price_formatted || (parseFloat(eq.price || 0).toFixed(2) + ' €'));
        const cardDisplayTitle = canSeeCost ? "{{ __('locale.kit.unit_cost') ?? 'Coût unitaire' }}" : "{{ __('locale.price_per_unit') ?? 'Prix public' }}";

        div.innerHTML = `
            <div class="card-item-action">
                <button type="button" class="btn-action-icon btn-action-icon-sm btn-add btn-add-kit-item" title="{{ __('locale.add') ?? 'Ajouter' }}">
                    <i class="bi bi-plus"></i>
                </button>
            </div>
            <div class="card-drag-handle" title="{{ __('locale.combo.drag_to_left') }}">
                <i class="bi bi-grip-vertical"></i>
            </div>
            <div class="card-item-main d-flex align-items-center gap-2">
                <span class="item-ref-badge" title="${escapeHtml(eq.reference || 'N/A')}">${escapeHtml(eq.reference || 'N/A')}</span>
                <span class="card-item-title" title="${escapeHtml(eq.name)}">${escapeHtml(eq.name)}</span>
            </div>
            <div class="card-item-price-tag" title="${cardDisplayTitle}">
                ${escapeHtml(cardDisplayPrice)}
            </div>
        `;
        return div;
    }

    function addEqToKit(itemData, insertBeforeNode = null) {
        if (!kitItemsContainer) return false;
        priceChoiceExplicitlyMade = null;

        const existingRow = kitItemsContainer.querySelector(`.kit-item-row[data-id="${itemData.id}"]`);
        if (existingRow) {
            const qtyInput = existingRow.querySelector('.kit-item-qty');
            const currentQty = parseFloat(qtyInput ? qtyInput.value : existingRow.dataset.quantity || 1) || 1;
            const newQty = currentQty + 1;
            if (qtyInput) qtyInput.value = newQty;
            existingRow.dataset.quantity = newQty;

            existingRow.classList.remove('duplicate-flash');
            void existingRow.offsetWidth;
            existingRow.classList.add('duplicate-flash');

            updateKitTotals();
            return true;
        }

        const newRow = renderKitItemRow(itemData);
        if (insertBeforeNode && insertBeforeNode.parentNode === kitItemsContainer) {
            kitItemsContainer.insertBefore(newRow, insertBeforeNode);
        } else {
            kitItemsContainer.appendChild(newRow);
        }

        updateKitTotals();
        return true;
    }

    // --- DRAG & DROP FOR KIT ---
    function cleanupKitDrag() {
        document.querySelectorAll('.kit-tab-pane .is-dragging, #kit-tab-pane .is-dragging').forEach(el => el.classList.remove('is-dragging'));
        if (kitDropIndicator.parentNode) {
            kitDropIndicator.parentNode.removeChild(kitDropIndicator);
        }
        kitCurrentDragState = null;
    }

    document.addEventListener('dragstart', function (e) {
        if (e.target.closest('input, button, .btn, .input-group, select, textarea, a')) {
            return;
        }

        const availableCard = e.target.closest('.eq-kit-available-row');
        if (availableCard && kitAvailableContainer?.contains(availableCard)) {
            kitCurrentDragState = {
                source: 'available',
                items: [{
                    id: availableCard.dataset.id,
                    ref: availableCard.dataset.ref,
                    name: availableCard.dataset.name,
                    price: availableCard.dataset.price,
                    cost: availableCard.dataset.cost || availableCard.dataset.costPrice || '0.00',
                    element: availableCard
                }]
            };

            availableCard.classList.add('is-dragging');
            e.dataTransfer.effectAllowed = 'copyMove';
            e.dataTransfer.setData('text/plain', 'kit-item');
            return;
        }

        const associatedCard = e.target.closest('.kit-item-row');
        if (associatedCard && kitItemsContainer?.contains(associatedCard)) {
            kitCurrentDragState = {
                source: 'associated',
                items: [{
                    id: associatedCard.dataset.id,
                    element: associatedCard
                }]
            };

            associatedCard.classList.add('is-dragging');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', 'kit-item');
        }
    });

    function onKitDragOver(e) {
        if (!kitCurrentDragState) return;
        e.preventDefault();
        e.dataTransfer.dropEffect = kitCurrentDragState.source === 'associated' ? 'move' : 'copy';

        const rows = Array.from(kitItemsContainer.querySelectorAll('.kit-item-row:not(.is-dragging)'));
        let targetBeforeRow = null;

        for (const row of rows) {
            const rect = row.getBoundingClientRect();
            const midpoint = rect.top + rect.height / 2;
            if (e.clientY < midpoint) {
                targetBeforeRow = row;
                break;
            }
        }

        if (targetBeforeRow) {
            if (kitDropIndicator.nextSibling !== targetBeforeRow) {
                kitItemsContainer.insertBefore(kitDropIndicator, targetBeforeRow);
            }
        } else {
            if (kitDropIndicator.parentNode !== kitItemsContainer || kitDropIndicator.nextSibling !== null) {
                kitItemsContainer.appendChild(kitDropIndicator);
            }
        }
    }

    function onKitDrop(e) {
        if (!kitCurrentDragState) return;
        e.preventDefault();
        e.stopPropagation();

        const insertBeforeNode = (kitDropIndicator.parentNode === kitItemsContainer) ? kitDropIndicator.nextSibling : null;
        if (kitDropIndicator.parentNode) {
            kitDropIndicator.parentNode.removeChild(kitDropIndicator);
        }

        if (kitCurrentDragState.source === 'available') {
            kitCurrentDragState.items.forEach(item => {
                addEqToKit(item, insertBeforeNode);
            });
        } else if (kitCurrentDragState.source === 'associated') {
            const draggedRow = kitCurrentDragState.items[0].element;
            if (draggedRow && insertBeforeNode !== draggedRow) {
                if (insertBeforeNode) {
                    kitItemsContainer.insertBefore(draggedRow, insertBeforeNode);
                } else {
                    kitItemsContainer.appendChild(draggedRow);
                }
                updateKitTotals();
            }
        }

        cleanupKitDrag();
    }

    kitItemsContainer?.addEventListener('dragover', onKitDragOver);
    kitItemsScrollArea?.addEventListener('dragover', onKitDragOver);
    kitItemsContainer?.addEventListener('drop', onKitDrop);
    kitItemsScrollArea?.addEventListener('drop', onKitDrop);

    document.addEventListener('dragend', function () {
        cleanupKitDrag();
    });

    // Event delegation for adding / removing kit items
    kitAvailableContainer?.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-add-kit-item');
        if (btn) {
            const card = btn.closest('.eq-kit-available-row');
            if (card) {
                addEqToKit({
                    id: card.dataset.id,
                    ref: card.dataset.ref,
                    name: card.dataset.name,
                    price: card.dataset.price,
                    cost: card.dataset.cost || card.dataset.costPrice || '0.00'
                });
            }
        }
    });

    kitItemsContainer?.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-remove-kit-item');
        if (btn) {
            const row = btn.closest('.kit-item-row');
            if (row) {
                priceChoiceExplicitlyMade = null;
                row.remove();
                updateKitTotals();
            }
        }
    });

    kitItemsContainer?.addEventListener('input', function (e) {
        const qtyInput = e.target.closest('.kit-item-qty');
        if (qtyInput) {
            const row = qtyInput.closest('.kit-item-row');
            if (!row) return;

            let newQty = parseFloat(qtyInput.value);
            if (isNaN(newQty) || newQty < 0.01) {
                newQty = 1;
            }
            priceChoiceExplicitlyMade = null;
            row.dataset.quantity = newQty;
            updateKitTotals();
        }
    });

    // AJAX debounced search for kit available components
    let kitSearchDebounceTimer = null;
    let kitSearchAbortController = null;

    function fetchEqKitAvailable() {
        if (kitSearchDebounceTimer) clearTimeout(kitSearchDebounceTimer);

        kitSearchDebounceTimer = setTimeout(() => {
            if (kitSearchAbortController) {
                kitSearchAbortController.abort();
            }
            kitSearchAbortController = new AbortController();

            const query = (kitSearchInput?.value || '').trim();
            const selectedCat = kitFilterCat?.value || '';
            const selectedBrand = kitFilterBrand?.value || '';
            const selectedType = kitFilterType?.value || '';

            const params = new URLSearchParams();
            params.append('exclude_id', '{{ $equipment->id }}');
            if (query) params.append('search', query);
            if (selectedCat) params.append('category_id', selectedCat);
            if (selectedBrand) params.append('brand_id', selectedBrand);
            if (selectedType) params.append('equipment_type_id', selectedType);

            fetch(`{{ route('equipments.available-kit-components') }}?${params.toString()}`, {
                signal: kitSearchAbortController.signal,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (!kitAvailableContainer) return;
                kitAvailableContainer.innerHTML = '';

                if (data.items && data.items.length > 0) {
                    const fragment = document.createDocumentFragment();
                    data.items.forEach(eq => {
                        fragment.appendChild(renderKitAvailableCard(eq));
                    });
                    kitAvailableContainer.appendChild(fragment);
                    if (noKitAvailableResults) noKitAvailableResults.style.display = 'none';
                } else {
                    if (noKitAvailableResults) noKitAvailableResults.style.display = 'block';
                }

                if (kitAvailableCount) {
                    kitAvailableCount.textContent = data.count || (data.items ? data.items.length : 0);
                }
            })
            .catch(err => {
                if (err.name !== 'AbortError') {
                    console.error('Erreur chargement composants kit:', err);
                }
            });
        }, 250);
    }

    kitSearchInput?.addEventListener('input', fetchEqKitAvailable);
    kitFilterCat?.addEventListener('change', function () {
        updateKitActiveFilterBadge();
        fetchEqKitAvailable();
    });
    kitFilterBrand?.addEventListener('change', function () {
        updateKitActiveFilterBadge();
        fetchEqKitAvailable();
    });
    kitFilterType?.addEventListener('change', function () {
        updateKitActiveFilterBadge();
        fetchEqKitAvailable();
    });

    // Action listeners for kit price change alert banner
    btnAlertApplyNewPrice?.addEventListener('click', function () {
        if (!canChangePrice) return;
        const totals = updateKitTotals();
        if (!totals || totals.count === 0) return;

        savedPriceWasDifferent = false;
        priceManuallyEdited = false;
        priceChoiceExplicitlyMade = 'applied';
        setKitPriceManualState(false);

        applyKitTotalsToInputs(totals.totalSale, totals.totalCost);
        hideKitPriceAlert();

        const toast = document.createElement('div');
        toast.className = 'badge bg-success text-white py-1 px-2 position-fixed shadow-sm';
        toast.style.bottom = '20px';
        toast.style.right = '20px';
        toast.style.zIndex = '9999';
        toast.innerHTML = '<i class="bi bi-check-lg me-1"></i>{{ __("locale.kit.price_applied_success") ?? "Nouveau prix appliqué !" }}';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2500);
    });

    btnAlertKeepOldPrice?.addEventListener('click', function () {
        priceChoiceExplicitlyMade = 'kept';
        savedPriceWasDifferent = true;
        setKitPriceManualState(true);
        hideKitPriceAlert();
    });

    // Capture initial state before kit changes
    const initialSavedPrice = parseFloat(document.getElementById('price_per_unit')?.value) || 0;
    const initialTotals = updateKitTotals();
    const initialComponentsSum = initialTotals ? Math.round(initialTotals.totalSale * 100) / 100 : 0;

    savedPriceWasDifferent = initialDbIsManual || (Math.abs(initialSavedPrice - initialComponentsSum) > 0.009 && initialComponentsSum > 0);
    kitInitialized = true;
    let kitPriceModalConfirmed = false;

    setKitPriceManualState(savedPriceWasDifferent);

    // Detect manual user typing in price fields
    $('#price_per_unit, #price_per_unit_tab').on('input change', function (e) {
        if (e.originalEvent) {
            priceManuallyEdited = true;
            priceChoiceExplicitlyMade = null;
            setKitPriceManualState(true);
        }
    });

    // On save (form submit) interceptor
    $('#equipmentForm').on('submit', function (e) {
        if (kitPriceModalConfirmed) {
            return true;
        }

        const isKit = isKitSwitch ? isKitSwitch.checked : false;
        if (!isKit) {
            return true;
        }

        if (!canChangePrice) {
            return true;
        }

        const totals = updateKitTotals();
        if (!totals || totals.count === 0) {
            return true;
        }

        const currentPrice = parseFloat($('#price_per_unit').val()) || 0;
        const newComponentSum = Math.round(totals.totalSale * 100) / 100;
        const priceDiffers = Math.abs(currentPrice - newComponentSum) > 0.009;
        const wasManuallyChanged = savedPriceWasDifferent || priceManuallyEdited;

        // Prompt user if price was changed manually and differs from component sum and no explicit keep choice made
        if (wasManuallyChanged && priceDiffers && priceChoiceExplicitlyMade !== 'kept') {
            e.preventDefault();
            e.stopPropagation();

            openKitPriceConfirmModal(currentPrice, newComponentSum, totals);
            return false;
        }

        // Otherwise adjust price automatically
        if (!wasManuallyChanged && priceChoiceExplicitlyMade !== 'kept') {
            applyKitTotalsToInputs(totals.totalSale, totals.totalCost);
            setKitPriceManualState(false);
        }
        return true;
    });

    function openKitPriceConfirmModal(oldPrice, newPrice, totals) {
        const modalEl = document.getElementById('kitPriceConfirmModal');
        if (!modalEl || typeof bootstrap === 'undefined') {
            kitPriceModalConfirmed = true;
            $('#equipmentForm')[0].submit();
            return;
        }

        const formattedOld = oldPrice.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';
        const formattedNew = newPrice.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';

        $('#kitModalOldPrice').text(formattedOld);
        $('#kitModalNewPrice').text(formattedNew);

        const btnOldLabel = $('#btnKeepOldKitPriceText').data('label') || "{{ __('locale.kit.btn_keep_old_price') ?? 'Conserver le prix actuel' }}";
        const btnNewLabel = $('#btnUseNewKitPriceText').data('label') || "{{ __('locale.kit.btn_use_new_price') ?? 'Appliquer la nouvelle somme' }}";

        $('#btnKeepOldKitPriceText').text(`${btnOldLabel} (${formattedOld})`);
        $('#btnUseNewKitPriceText').text(`${btnNewLabel} (${formattedNew})`);

        const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);

        $('#btnKeepOldKitPrice').off('click').on('click', function () {
            setKitPriceManualState(true);
            kitPriceModalConfirmed = true;
            bsModal.hide();
            $('#equipmentForm')[0].submit();
        });

        $('#btnUseNewKitPrice').off('click').on('click', function () {
            applyKitTotalsToInputs(newPrice, totals.totalCost);
            setKitPriceManualState(false);
            kitPriceModalConfirmed = true;
            bsModal.hide();
            $('#equipmentForm')[0].submit();
        });

        bsModal.show();
    }

    updateEqComboAssociatedCount();
    updateBottomBarVisibility();
});
</script>
@endpush
@endsection
