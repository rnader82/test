@extends('layouts.app')
@section('portal-module', 'process_stages')
@section('portal-page', 'index')
@section('portal-kind', 'list')
@section('portal-family', 'admin')
@section('portal-icon', 'gear')


@section('title', __('locale.lbl_process_stages'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.lbl_process_stages') }}</h1>
        <span class="header-count">({{ method_exists($processStages, 'total') ? $processStages->total() : count($processStages) }})</span>
    </div>
@endsection

@section('page-actions')
    <a href="{{ route('process_stages.create') }}" class="btn btn-primary" title="{{ __('locale.btn_create_process_stage') ?? 'Ajouter' }}">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_create_process_stage') ?? 'Nouvelle étape' }}
    </a>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
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
                                <th>{{ __('locale.description') }}</th>
                                <th>{{ __('locale.model_slug') }}</th>
                                <th class="text-end pe-4" style="width: 120px;">{{ __('locale.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($processStages as $stage)
                                <tr style="cursor: pointer;" onclick="window.location='{{ route('process_stages.show', $stage) }}'">
                                    <td class="ps-4">
                                        <a href="{{ route('process_stages.show', $stage) }}" class="fw-semibold text-dark text-decoration-none">
                                            {{ $stage->translated_name }}
                                        </a>
                                    </td>
                                    <td class="text-muted small">
                                        {{ $stage->translated_description ? Str::limit($stage->translated_description, 80) : '—' }}
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-secondary border font-monospace">{{ $stage->model_slug }}</span>
                                    </td>
                                    <td class="text-end pe-4" onclick="event.stopPropagation();">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" id="stageActionMenu{{ $stage->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border" aria-labelledby="stageActionMenu{{ $stage->id }}">
                                                <li>
                                                    <a href="{{ route('process_stages.show', $stage) }}" class="dropdown-item">
                                                        <i class="bi bi-eye me-2 text-info"></i>{{ __('locale.btn_show') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('process_stages.edit', $stage) }}" class="dropdown-item">
                                                        <i class="bi bi-pencil-square me-2 text-primary"></i>{{ __('locale.edit') }}
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('process_stages.destroy', $stage) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('{{ __('locale.delete_confirmation') }}')">
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
            @if(method_exists($processStages, 'hasPages') && $processStages->hasPages())
                <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
                    <span class="text-muted small">
                        {{ __('locale.results_sur', ['first' => $processStages->firstItem() ?? 0, 'last' => $processStages->lastItem() ?? 0, 'total' => $processStages->total()]) }}
                    </span>
                    <div>
                        {{ $processStages->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection