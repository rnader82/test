@extends('layouts.app')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.inventory_report') }}</h1>
    </div>
@endsection

@section('content')
<div class="container-xxl mt-4">

        <!-- Date Picker Card -->
        <div class="card mb-4 card-premium shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                <h5 class="mb-0"><i class="bi bi-calendar-check me-2 text-primary"></i>{{ __('locale.inventory_date') }}</h5>
            </div>
            <div class="card-body pt-4">
                <form method="GET" action="{{ route('reports.inventory') }}" id="filterForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label for="inventory_date" class="form-label fw-semibold">{{ __('locale.inventory_date') }}</label>
                            <input type="date" name="inventory_date" id="inventory_date" class="form-control" value="{{ $inventoryDate }}" required>
                        </div>
                        <div class="col-md-5">
                            <label for="search" class="form-label fw-semibold">{{ __('locale.search') }}</label>
                            <div class="input-group">
                                <input type="text" name="search" id="search" class="form-control" placeholder="{{ __('locale.search_placeholder') }}" value="{{ $search }}">
                                @if($search)
                                    <a href="{{ route('reports.inventory', ['inventory_date' => $inventoryDate]) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-lg"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search me-2"></i>{{ __('locale.btn_apply_filters') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if($inventoryDate)
            <!-- Results Table Card -->
            <div class="card card-premium shadow-sm animate__animated animate__fadeIn">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('locale.results') }} ({{ (is_object($equipments) || is_array($equipments)) ? count($equipments) : 0 }})</h5>
                    <div class="badge bg-label-info">
                        {{ __('locale.lbl_period') }}: {{ now()->format('d/m/Y') }} → {{ \Carbon\Carbon::parse($inventoryDate)->format('d/m/Y') }}
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-compact" id="inventoryTable">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('locale.lbl_reference') }}</th>
                                <th>{{ __('locale.lbl_name') }}</th>
                                <th>{{ __('locale.lbl_brand') }}</th>
                                <th>{{ __('locale.lbl_type') }}</th>
                                <th class="text-center">{{ __('locale.current_stock') }}</th>
                                <th class="text-center">{{ __('locale.reserved_stock') }}</th>
                                <th class="text-center">{{ __('locale.incoming_stock') }}</th>
                                <th class="text-center">{{ __('locale.available_stock') }}</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($equipments as $equipment)
                                <tr>
                                    <td><span class="fw-bold">{{ $equipment->reference }}</span></td>
                                    <td>{{ $equipment->name }}</td>
                                    <td>{{ $equipment->brand?->name ?? '-' }}</td>
                                    <td>
                                        @if($equipment->equipmentType)
                                            <span class="badge bg-label-secondary">{{ $equipment->equipmentType->name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-label-primary px-3 fs-6">{{ $equipment->current_stock }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($equipment->reserved_qty > 0)
                                            <span class="badge bg-label-warning px-3 fs-6">- {{ $equipment->reserved_qty }}</span>
                                        @else
                                            <span class="text-muted">0</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($equipment->incoming_qty > 0)
                                            <span class="badge bg-label-info px-3 fs-6">+ {{ $equipment->incoming_qty }}</span>
                                        @else
                                            <span class="text-muted">0</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $badgeClass = $equipment->available_qty > 0 ? 'bg-label-success' : ($equipment->available_qty < 0 ? 'bg-label-danger' : 'bg-label-secondary');
                                        @endphp
                                        <span class="badge {{ $badgeClass }} px-3 fs-6 fw-bold">{{ $equipment->available_qty }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-center text-muted">
                                            <i class="bi bi-box fs-1 mb-3"></i>
                                            <p>{{ __('locale.no_equipment_found') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="card card-premium shadow-sm py-5 text-center">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="avatar avatar-xl bg-label-primary rounded mx-auto mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-box-seam fs-1 d-flex align-items-center justify-content-center h-100"></i>
                        </div>
                    </div>
                    <h4 class="mb-2">{{ __('locale.select_inventory_date') }}</h4>
                    <p class="text-muted">{{ __('locale.inventory_report_hint') }}</p>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .card-premium {
        border: none;
        border-radius: 0.75rem;
        background: #fff;
    }
    .table-compact {
        font-size: 0.875rem;
    }
    .table-compact th {
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05rem;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    .table-compact td {
        padding-top: 0.875rem;
        padding-bottom: 0.875rem;
    }
    .ls-1 { letter-spacing: 1px; }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize tooltips if any
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection
