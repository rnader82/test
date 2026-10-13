@extends('layouts.app')

@section('navbar-title')
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('equipments.inventory.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="mb-0">{{ __('locale.inventory_history') }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small text-uppercase">
                    <li class="breadcrumb-item"><a href="{{ route('equipments.inventory.index') }}">{{ __('locale.lbl_inventory') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('locale.history') }}</li>
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
        <div class="card card-premium eq-card-table mb-2">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-premium mb-0">
                        <thead class="small text-uppercase text-muted">
                            <tr>
                                <th class="ps-4 py-3">{{ __('locale.lbl_date_time') }}</th>
                                <th class="py-3">{{ __('locale.lbl_equipment') }}</th>
                                <th class="py-3">{{ __('locale.lbl_user') }}</th>
                                <th class="py-3 text-center">{{ __('locale.lbl_previous_qty') }}</th>
                                <th class="py-3 text-center text-primary">{{ __('locale.lbl_recorded_qty') }}</th>
                                <th class="py-3 text-center">{{ __('locale.lbl_diff') }}</th>
                                <th class="pe-4 py-3">{{ __('locale.notes') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="fw-bold text-dark">{{ $log->created_at->format('d/m/Y') }}</div>
                                        <div class="small text-muted">{{ $log->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="py-3">
                                        <div class="fw-bold text-primary">{{ $log->equipment->name ?? '-' }}</div>
                                        <div class="small text-muted">{{ $log->equipment->reference ?? '-' }}</div>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-person small"></i>
                                            </div>
                                            <span class="small fw-medium">{{ $log->user->name ?? __('locale.lbl_system') }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center text-muted fw-bold">{{ $log->previous_quantity }}</td>
                                    <td class="py-3 text-center fw-bold text-primary fs-5">{{ $log->recorded_quantity }}</td>
                                    <td class="py-3 text-center">
                                        @php $diff = $log->recorded_quantity - $log->previous_quantity; @endphp
                                        @if($diff > 0)
                                            <span class="badge bg-success bg-opacity-10 text-success border-0 px-3 py-2 rounded-pill fw-bold">
                                                <i class="bi bi-arrow-up-short"></i> +{{ $diff }}
                                            </span>
                                        @elseif($diff < 0)
                                            <span class="badge bg-danger bg-opacity-10 text-danger border-0 px-3 py-2 rounded-pill fw-bold">
                                                <i class="bi bi-arrow-down-short"></i> {{ $diff }}
                                            </span>
                                        @else
                                            <span class="text-muted small">0</span>
                                        @endif
                                    </td>
                                    <td class="pe-4 py-3 text-muted small italic">
                                        {{ $log->notes ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="opacity-25 mb-3">
                                            <i class="bi bi-journal-x fs-1"></i>
                                        </div>
                                        <p class="text-muted">{{ __('locale.no_inventory_history') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-center eq-pagination-bar mt-1">
            {{ $logs->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
