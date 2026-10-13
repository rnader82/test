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
    .transition-icon {
        transition: transform 0.3s ease;
    }
    .card-header[aria-expanded="true"] .transition-icon {
        transform: rotate(180deg);
    }
    .eq-snapshots-row {
        flex: 1 1 auto;
        overflow-y: auto;
        overflow-x: hidden;
    }
</style>
@endpush

@section('navbar-title')
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('equipments.inventory.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="mb-0">{{ __('locale.yearly_snapshots') }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small text-uppercase">
                    <li class="breadcrumb-item"><a href="{{ route('equipments.inventory.index') }}">{{ __('locale.lbl_inventory') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('locale.snapshots') }}</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('page-actions')
    @if(Auth::user()->isMotherCompany())
        <button type="button" class="btn btn-warning btn-sm shadow-sm fw-bold px-3" data-bs-toggle="modal" data-bs-target="#generateSnapshotModal">
            <i class="bi bi-lightning-fill me-1"></i> {{ __('locale.btn_generate_snapshot') }}
        </button>
    @endif
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-2 py-1 px-3 small">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row g-3 eq-snapshots-row">
        <div class="col-lg-8">
            @forelse ($snapshots as $year => $yearSnapshots)
                <div class="card card-premium mb-4 overflow-hidden border-0">
                    <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center" 
                         style="cursor: pointer;"
                         data-bs-toggle="collapse" 
                         data-bs-target="#year-{{ $year }}"
                         aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                            <i class="bi bi-calendar-check text-primary me-2"></i> {{ __('locale.stock_as_of_31_12') }}{{ $year }}
                        </h5>
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 border-0 fw-bold">
                                {{ $yearSnapshots->count() }} {{ __('locale.equipments') }}
                            </span>
                            <i class="bi bi-chevron-down text-muted transition-icon"></i>
                        </div>
                    </div>
                    <div id="year-{{ $year }}" class="collapse {{ $loop->first ? 'show' : '' }}">
                        <div class="card-body p-0 border-top">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="small text-uppercase text-muted" style="background: #fbfbfc;">
                                        <tr>
                                            <th class="ps-4 py-3">{{ __('locale.lbl_equipment') }}</th>
                                            <th class="py-3 text-end pe-4">{{ __('locale.lbl_quantity') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($yearSnapshots as $snapshot)
                                            <tr>
                                                <td class="ps-4 py-3">
                                                    <div class="fw-bold text-dark">{{ $snapshot->equipment->name ?? '-' }}</div>
                                                    <div class="small text-muted">{{ $snapshot->equipment->reference ?? '-' }}</div>
                                                </td>
                                                <td class="text-end pe-4 py-3">
                                                    <span class="badge bg-white text-primary border shadow-sm fs-6 px-3 py-1 fw-bold">{{ $snapshot->quantity }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card card-premium text-center py-5">
                    <div class="card-body">
                        <div class="opacity-25 mb-3">
                            <i class="bi bi-camera-video-off fs-1"></i>
                        </div>
                        <h5 class="text-muted">{{ __('locale.no_snapshots_yet') }}</h5>
                    </div>
                </div>
            @endforelse
        </div>

        @if(Auth::user()->isMotherCompany())
        <div class="col-lg-4">
            <div class="card card-premium shadow-lg sticky-top" style="top: 20px;">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <h5 class="mb-0 fw-bold d-flex align-items-center text-dark">
                        <i class="bi bi-pencil-square me-2 text-warning"></i> {{ __('locale.manual_regularization') }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small mb-4">{{ __('locale.regularization_desc') }}</p>
                    <form action="{{ route('equipments.inventory.save_manual_snapshot') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase text-muted">{{ __('locale.lbl_equipment') }}</label>
                            <select name="equipment_id" class="form-select select2" required>
                                <option value="">{{ __('locale.select_equipment_placeholder') }}</option>
                                @foreach(\App\Models\Equipment::orderBy('name')->get() as $eq)
                                    <option value="{{ $eq->id }}">{{ $eq->name }} ({{ $eq->reference }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <label class="form-label small fw-bold text-uppercase text-muted">{{ __('locale.lbl_year') }}</label>
                                <input type="number" name="year" class="form-control border-0 bg-light bg-opacity-50 p-3" value="{{ date('Y') }}" required min="2000" max="{{ date('Y') }}">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold text-uppercase text-muted">{{ __('locale.lbl_quantity') }}</label>
                                <input type="number" name="quantity" class="form-control border-0 bg-light bg-opacity-50 p-3" required min="0">
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning btn-lg fw-bold shadow-sm py-3">
                                {{ __('locale.btn_save_record') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
    </div>
</div>

<!-- Modal Redesign -->
<div class="modal fade" id="generateSnapshotModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header border-0 bg-warning p-4">
                <h5 class="modal-title fw-bold text-dark fs-4">
                    <i class="bi bi-lightning-charge-fill me-2"></i> {{ __('locale.generate_yearly_snapshot') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('equipments.inventory.generate_snapshot') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="d-flex align-items-start mb-4 p-3 rounded-3 bg-light">
                        <i class="bi bi-info-circle-fill text-info me-3 fs-3"></i>
                        <p class="mb-0 small text-muted">{{ __('locale.snapshot_prompt') }}</p>
                    </div>
                    
                    <div class="mb-2">
                        <label for="snapshot_year" class="form-label fw-bold small text-uppercase text-muted">{{ __('locale.lbl_target_year') }}</label>
                        <select name="year" id="snapshot_year" class="form-select form-select-lg border-0 bg-light bg-opacity-50 p-3 fw-bold" required>
                            @for($y = date('Y'); $y >= 2000; $y--)
                                <option value="{{ $y }}" @if($y == date('Y')) selected @endif>{{ $y }} {{ __('locale.yearly_target_desc') }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-link text-muted fw-bold text-decoration-none px-4" data-bs-dismiss="modal">{{ __('locale.btn_cancel') }}</button>
                    <button type="submit" class="btn btn-warning btn-lg px-5 fw-bold shadow-lg rounded-pill">{{ __('locale.btn_confirm_generate') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        if ($.fn.select2) {
            $('.select2').select2({
                width: '100%',
                placeholder: "{{ __('locale.select_equipment_placeholder') }}",
                allowClear: true
            });
        }
    });
</script>
@endpush
@endsection
