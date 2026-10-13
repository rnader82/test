@extends('layouts.app')

@section('navbar-title')
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('equipments.inventory.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="mb-0">{{ __('locale.inventory_count') }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small text-uppercase">
                    <li class="breadcrumb-item"><a href="{{ route('equipments.inventory.index') }}">{{ __('locale.lbl_inventory') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('locale.new_count') }}</li>
                </ol>
            </nav>
        </div>
    </div>
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
    .eq-count-row {
        flex: 1 1 auto;
        overflow-y: auto;
        overflow-x: hidden;
    }
</style>
@endpush

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="row g-3 eq-count-row">
            <!-- Left Card: Article & Current Multi-Warehouse Breakdown -->
            <div class="col-lg-5">
                <div class="card card-premium mb-0 h-100">
                    <div class="card-header bg-transparent border-0 pt-3 px-3 pb-0">
                        <h5 class="mb-0 fw-bold d-flex align-items-center text-dark">
                            <i class="bi bi-info-circle text-primary me-2"></i> {{ __('locale.lbl_equipment_details') }}
                        </h5>
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-3 text-center py-3 rounded-3 border-0 bg-primary-subtle bg-opacity-10">
                            <h5 class="fw-bold mb-1 text-primary" title="{{ $equipment->name }}">{{ \Illuminate\Support\Str::limit($equipment->name, 65) }}</h5>
                            <div class="d-flex justify-content-center flex-wrap gap-2 mt-2">
                                <span class="badge bg-white text-dark shadow-sm border px-2 py-1 fw-bold font-monospace">{{ $equipment->reference ?? '-' }}</span>
                                @if($equipment->brand)
                                    <span class="badge bg-white text-dark shadow-sm border px-2 py-1">{{ $equipment->brand->name }}</span>
                                @endif
                                @if($equipment->odoo_id)
                                    @if(config('services.odoo.url'))
                                        <a href="{{ rtrim(config('services.odoo.url'), '/') }}/web#id={{ $equipment->odoo_id }}&model=product.product&view_type=form" 
                                           target="_blank" 
                                           rel="noopener noreferrer" 
                                           class="badge bg-white text-primary shadow-sm border font-monospace text-decoration-none" 
                                           title="{{ __('locale.open_in_odoo') ?? 'Ouvrir dans Odoo' }}"
                                           onclick="event.stopPropagation();">
                                            Odoo #{{ $equipment->odoo_id }} <i class="bi bi-box-arrow-up-right ms-1" style="font-size: 0.65rem;"></i>
                                        </a>
                                    @else
                                        <span class="badge bg-white text-primary shadow-sm border font-monospace">Odoo #{{ $equipment->odoo_id }}</span>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="list-group list-group-flush small bg-transparent mb-3">
                            <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 py-2">
                                <span class="text-muted fw-bold">{{ __('locale.lbl_on_hand_qty') }}</span>
                                <span class="badge bg-primary-subtle text-primary border-0 fs-6 px-3 py-1 rounded-pill fw-bold font-monospace">{{ (float) ($equipment->current_stock ?? 0) }}</span>
                            </div>
                            <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 py-2">
                                <span class="text-muted fw-bold">{{ __('locale.lbl_reserved_qty') }}</span>
                                <span class="badge bg-warning-subtle text-warning border-0 fs-6 px-3 py-1 rounded-pill fw-bold font-monospace">{{ (float) $equipment->reserved_stock }}</span>
                            </div>
                            <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center px-0 py-2">
                                <span class="text-muted fw-bold">{{ __('locale.lbl_available_qty') }}</span>
                                <span class="badge bg-success-subtle text-success border-0 fs-6 px-3 py-1 rounded-pill fw-bold font-monospace">{{ (float) $equipment->available_stock }}</span>
                            </div>
                        </div>

                        <h6 class="fw-bold small text-uppercase text-muted mb-2"><i class="bi bi-shop me-1"></i>{{ __('locale.stock_by_warehouse') }}</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0 small align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('locale.warehouse') }}</th>
                                        <th class="text-center">{{ __('locale.lbl_on_hand_qty') }}</th>
                                        <th class="text-center">{{ __('locale.lbl_reserved_qty') }}</th>
                                        <th class="text-center">{{ __('locale.lbl_available_qty') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($equipment->warehouses as $wh)
                                        @php
                                            $whQty = (float) $wh->pivot->quantity;
                                            $whRes = (float) $wh->pivot->reserved_quantity;
                                            $whAvail = max(0.0, $whQty - $whRes);
                                        @endphp
                                        <tr>
                                            <td class="fw-semibold">{{ $wh->name }} ({{ $wh->code }})</td>
                                            <td class="text-center font-monospace fw-bold text-primary">{{ $whQty }}</td>
                                            <td class="text-center font-monospace text-warning">{{ $whRes }}</td>
                                            <td class="text-center font-monospace fw-bold text-success">{{ $whAvail }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-2">{{ __('locale.no_warehouse_assigned') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Right Card: Odoo Physical Inventory Adjustment Form -->
            <div class="col-lg-7">
                <div class="card card-premium h-100">
                    <div class="card-header bg-transparent border-0 pt-3 px-3 pb-0">
                        <h5 class="mb-0 fw-bold d-flex align-items-center text-dark">
                            <i class="bi bi-pencil-square text-warning me-2"></i> {{ __('locale.record_entry') }}
                        </h5>
                    </div>
                    <div class="card-body p-3">
                        <div class="alert alert-info border-0 shadow-sm small p-3 mb-3 rounded-3">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-cloud-check-fill text-primary me-2 fs-5 mt-0.5"></i>
                                <div>
                                    <strong class="d-block mb-1">{{ __('locale.odoo_inventory_sync_info_title') }}</strong>
                                    {{ __('locale.odoo_inventory_sync_info_desc') }}
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('equipments.inventory.store_count', $equipment) }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="warehouse_id" class="form-label fw-bold text-dark small text-uppercase tracking-wider mb-1">{{ __('locale.warehouse') }} <span class="text-danger">*</span></label>
                                <select name="warehouse_id" id="warehouse_id" class="form-select select2 @error('warehouse_id') is-invalid @enderror" required onchange="updateWarehouseCurrentStock()">
                                    <option value="">{{ __('locale.select_warehouse') }}</option>
                                    @foreach($warehouses as $warehouse)
                                        @php
                                            $whPivot = $equipment->warehouses->firstWhere('id', $warehouse->id)?->pivot;
                                            $whStock = (float) ($whPivot->quantity ?? 0.0);
                                        @endphp
                                        <option value="{{ $warehouse->id }}" 
                                                data-current-stock="{{ $whStock }}"
                                                {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                            {{ $warehouse->name }} ({{ $warehouse->code }}) - {{ __('locale.current_stock') }}: {{ $whStock }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('warehouse_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small text-uppercase tracking-wider mb-1">{{ __('locale.lbl_system_stock') }}</label>
                                    <input type="text" id="display_current_stock" class="form-control bg-light font-monospace fw-bold" value="—" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="recorded_quantity" class="form-label fw-bold text-dark small text-uppercase tracking-wider mb-1">{{ __('locale.lbl_physical_count') }} <span class="text-danger">*</span></label>
                                    <div class="input-group shadow-sm rounded-3 overflow-hidden">
                                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-box"></i></span>
                                        <input type="number" name="recorded_quantity" id="recorded_quantity" 
                                               class="form-control border-start-0 @error('recorded_quantity') is-invalid @enderror py-2 fw-bold font-monospace" 
                                               value="{{ old('recorded_quantity') }}" placeholder="0" required min="0" step="any" oninput="calculateDifference()">
                                        @error('recorded_quantity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Difference Preview Badge -->
                            <div class="p-3 bg-light rounded-3 mb-3 d-flex justify-content-between align-items-center">
                                <span class="small fw-semibold text-muted text-uppercase">{{ __('locale.lbl_difference') }}:</span>
                                <span id="diff_badge" class="badge bg-secondary fs-6 px-3 py-1 font-monospace">0</span>
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label fw-bold text-dark small text-uppercase tracking-wider mb-1">{{ __('locale.notes_optional') }}</label>
                                <textarea name="notes" id="notes" class="form-control bg-light bg-opacity-50 border-0 focus-ring shadow-sm p-2" 
                                          rows="2" placeholder="{{ __('locale.notes_placeholder') }}" maxlength="500"></textarea>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary shadow-sm py-2 rounded-3 fw-bold fs-6">
                                    <i class="bi bi-cloud-check-fill me-2"></i> {{ __('locale.btn_apply_inventory') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateWarehouseCurrentStock() {
    const select = document.getElementById('warehouse_id');
    const selectedOption = select.options[select.selectedIndex];
    const currentStock = selectedOption.getAttribute('data-current-stock') || '0';
    document.getElementById('display_current_stock').value = currentStock;
    calculateDifference();
}

function calculateDifference() {
    const currentStock = parseFloat(document.getElementById('display_current_stock').value) || 0;
    const recordedVal = document.getElementById('recorded_quantity').value;
    const badge = document.getElementById('diff_badge');

    if (recordedVal === '' || isNaN(recordedVal)) {
        badge.className = 'badge bg-secondary fs-6 px-3 py-1 font-monospace';
        badge.innerText = '—';
        return;
    }

    const recorded = parseFloat(recordedVal);
    const diff = recorded - currentStock;

    if (diff > 0) {
        badge.className = 'badge bg-success fs-6 px-3 py-1 font-monospace';
        badge.innerText = '+' + diff.toFixed(2).replace(/\.00$/, '');
    } else if (diff < 0) {
        badge.className = 'badge bg-danger fs-6 px-3 py-1 font-monospace';
        badge.innerText = diff.toFixed(2).replace(/\.00$/, '');
    } else {
        badge.className = 'badge bg-secondary fs-6 px-3 py-1 font-monospace';
        badge.innerText = "{{ __('locale.no_difference') }}";
    }
}

$(document).ready(function() {
    $('.select2').select2({
        width: '100%'
    });
    if (document.getElementById('warehouse_id').value) {
        updateWarehouseCurrentStock();
    }
});
</script>
@endpush
