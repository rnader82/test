@extends('layouts.app')

@section('title', __('locale.menu_states'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.menu_states') }}</h1>
        <span class="header-count">({{ method_exists($states, 'total') ? $states->total() : count($states) }})</span>
    </div>
@endsection

@section('navbar-search')
    <form method="GET" action="{{ route('states.index') }}" class="w-100">
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
    <button type="button" class="btn btn-primary create-state-btn" title="{{ __('locale.btn_create_state') ?? 'Ajouter' }}">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_create_state') ?? 'Nouvel état' }}
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
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">{{ __('locale.name') }}</th>
                                <th>{{ __('locale.lbl_internal_status') }}</th>
                                <th>{{ __('locale.lbl_external_status') }}</th>
                                <th class="text-end pe-4" style="width: 120px;">{{ __('locale.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($states as $state)
                                <tr style="cursor: pointer;" onclick="window.location='{{ route('states.show', $state) }}'">
                                    <td class="ps-4">
                                        <a href="{{ route('states.show', $state) }}" class="fw-semibold text-dark text-decoration-none">
                                            {{ $state->name }}
                                        </a>
                                    </td>
                                    <td>
                                        @if($state->internalStatus)
                                            <span class="badge px-3 py-1.5" style="background-color: {{ $state->internalStatus->color ?? '#64748b' }}; color: {{ $state->internalStatus->text_color ?? '#ffffff' }}; border-radius: 6px;">
                                                {{ $state->internalStatus->name }}
                                            </span>
                                        @else
                                            <span class="text-muted small">&mdash;</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($state->externalStatus)
                                            <span class="badge px-3 py-1.5" style="background-color: {{ $state->externalStatus->color ?? '#64748b' }}; color: {{ $state->externalStatus->text_color ?? '#ffffff' }}; border-radius: 6px;">
                                                {{ $state->externalStatus->name }}
                                            </span>
                                        @else
                                            <span class="text-muted small">&mdash;</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4" onclick="event.stopPropagation();">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" id="stateActionMenu{{ $state->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border" aria-labelledby="stateActionMenu{{ $state->id }}">
                                                <li>
                                                    <a href="{{ route('states.show', $state) }}" class="dropdown-item">
                                                        <i class="bi bi-eye me-2 text-info"></i>{{ __('locale.btn_show') ?? 'Voir' }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <button type="button" class="dropdown-item edit-state-btn" data-id="{{ $state->id }}">
                                                        <i class="bi bi-pencil-square me-2 text-primary"></i>{{ __('locale.edit') }}
                                                    </button>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('states.destroy', $state) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('{{ __('locale.confirm_delete') }}');">
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
            @if(method_exists($states, 'hasPages') && $states->hasPages())
                <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
                    <span class="text-muted small">
                        {{ __('locale.results_sur', ['first' => $states->firstItem() ?? 0, 'last' => $states->lastItem() ?? 0, 'total' => $states->total()]) }}
                    </span>
                    <div>
                        {{ $states->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Container -->
<div class="modal fade" id="stateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" id="state-modal-content"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const stateModalEl = document.getElementById('stateModal');
    const stateModal = new bootstrap.Modal(stateModalEl);

    const createBtn = document.querySelector('.create-state-btn');
    if (createBtn) {
        createBtn.addEventListener('click', function () {
            fetch("{{ route('states.create-modal') }}")
                .then(resp => resp.text())
                .then(html => {
                    document.getElementById('state-modal-content').innerHTML = html;
                    stateModal.show();
                });
        });
    }

    document.querySelectorAll('.edit-state-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            fetch(`/states/${id}/edit/modal`)
                .then(resp => resp.text())
                .then(html => {
                    document.getElementById('state-modal-content').innerHTML = html;
                    stateModal.show();
                });
        });
    });
});
</script>
@endsection