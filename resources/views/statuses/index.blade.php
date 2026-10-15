@extends('layouts.app')
@section('portal-module', 'statuses')
@section('portal-page', 'index')
@section('portal-kind', 'list')
@section('portal-family', 'admin')
@section('portal-icon', 'gear')


@section('title', __('locale.statuses'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.statuses') }}</h1>
        <span class="header-count">({{ method_exists($statuses, 'total') ? $statuses->total() : count($statuses) }})</span>
    </div>
@endsection

@section('navbar-search')
    <form method="GET" action="{{ route('statuses.index') }}" class="w-100">
        <div class="search-bar-mockup">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('locale.search') }}">
            @foreach(request()->except(['search', 'page']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </div>
    </form>
@endsection

@section('page-actions')
    <button type="button" class="btn btn-primary create-status-btn" title="{{ __('locale.btn_create_status') }}">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_create_status') ?? 'Ajouter un statut' }}
    </button>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card card-premium shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="module-table table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">{{ __('locale.name') }}</th>
                                <th>{{ __('locale.module') }}</th>
                                <th>{{ __('locale.status_type') ?? 'Type' }}</th>
                                <th class="text-end pe-4" style="width: 120px;">{{ __('locale.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($statuses as $status)
                                <tr style="cursor: pointer;" onclick="window.location='{{ route('statuses.show', $status) }}'">
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge px-3 py-2 fw-semibold" style="background-color: {{ $status->color ?? '#64748b' }}; color: {{ $status->text_color ?? '#ffffff' }}; border-radius: 6px;">
                                                {{ $status->translated_name }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border text-uppercase font-monospace" style="font-size: 0.75rem;">
                                            {{ $status->module }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($status->is_pending)
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle">{{ __('locale.is_pending') ?? 'En attente' }}</span>
                                        @elseif($status->is_closed)
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">{{ __('locale.is_closed') ?? 'Fermé' }}</span>
                                        @else
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">{{ __('locale.normal') ?? 'Normal' }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4" onclick="event.stopPropagation();">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" id="statusActionMenu{{ $status->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border" aria-labelledby="statusActionMenu{{ $status->id }}">
                                                <li>
                                                    <a href="{{ route('statuses.show', $status) }}" class="dropdown-item">
                                                        <i class="bi bi-eye me-2 text-info"></i>{{ __('locale.btn_show') ?? 'Voir' }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <button type="button" class="dropdown-item edit-status-btn" data-id="{{ $status->id }}">
                                                        <i class="bi bi-pencil-square me-2 text-primary"></i>{{ __('locale.edit') }}
                                                    </button>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('statuses.destroy', $status) }}" method="POST">
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
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2 text-muted"></i>
                                        {{ __('locale.lbl_no_records_found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if(method_exists($statuses, 'hasPages') && $statuses->hasPages())
                <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
                    <span class="text-muted small">
                        {{ __('locale.results_sur', ['first' => $statuses->firstItem() ?? 0, 'last' => $statuses->lastItem() ?? 0, 'total' => $statuses->total()]) }}
                    </span>
                    <div>
                        {{ $statuses->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" id="status-modal-content"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const statusModalEl = document.getElementById('statusModal');
    const statusModal = new bootstrap.Modal(statusModalEl);

    const createBtn = document.querySelector('.create-status-btn');
    if (createBtn) {
        createBtn.addEventListener('click', function () {
            fetch("{{ route('statuses.create-modal') }}")
                .then(resp => resp.text())
                .then(html => {
                    document.getElementById('status-modal-content').innerHTML = html;
                    statusModal.show();
                });
        });
    }

    document.querySelectorAll('.edit-status-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            fetch(`/statuses/${id}/edit/modal`)
                .then(resp => resp.text())
                .then(html => {
                    document.getElementById('status-modal-content').innerHTML = html;
                    statusModal.show();
                });
        });
    });
});
</script>
@endsection