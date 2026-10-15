@extends('layouts.app')
@section('portal-module', 'visite_prev')
@section('portal-page', 'index')
@section('portal-kind', 'list')
@section('portal-family', 'technical')
@section('portal-icon', 'screwdriver-wrench')


@section('title', __('locale.visite_prev.checklist_templates') ?? 'Modèles de checklist VP')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.visite_prev.checklist_templates') ?? 'Modèles de checklist VP' }}</h1>
        <span class="header-count">({{ method_exists($templates, 'total') ? $templates->total() : count($templates) }})</span>
    </div>
@endsection

@section('page-actions')
    <a href="{{ route('vp-checklist-templates.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.visite_prev.add_new_template') ?? 'Nouveau modèle' }}
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
                                <th class="ps-4" style="width: 100px;">{{ __('locale.visite_prev.order_number') }}</th>
                                <th>{{ __('locale.visite_prev.action') }}</th>
                                <th style="width: 160px;">{{ __('locale.visite_prev.created_at') }}</th>
                                <th class="text-end pe-4" style="width: 120px;">{{ __('locale.visite_prev.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($templates as $template)
                                <tr style="cursor: pointer;" onclick="window.location='{{ route('vp-checklist-templates.show', $template->id) }}'">
                                    <td class="ps-4">
                                        <span class="badge bg-light text-secondary border font-monospace">#{{ $template->order_number }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('vp-checklist-templates.show', $template->id) }}" class="fw-semibold text-dark text-decoration-none">
                                            {{ $template->action }}
                                        </a>
                                    </td>
                                    <td class="text-muted small">
                                        {{ format_date($template->created_at, 'short') }}
                                    </td>
                                    <td class="text-end pe-4" onclick="event.stopPropagation();">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" id="templateActionMenu{{ $template->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border" aria-labelledby="templateActionMenu{{ $template->id }}">
                                                <li>
                                                    <a href="{{ route('vp-checklist-templates.show', $template->id) }}" class="dropdown-item">
                                                        <i class="bi bi-eye me-2 text-info"></i>{{ __('locale.btn_show') ?? 'Voir' }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('vp-checklist-templates.edit', $template->id) }}" class="dropdown-item">
                                                        <i class="bi bi-pencil-square me-2 text-primary"></i>{{ __('locale.edit') }}
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('vp-checklist-templates.destroy', $template->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('{{ __('locale.visite_prev.confirm_delete') }}')">
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
            @if(method_exists($templates, 'hasPages') && $templates->hasPages())
                <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
                    <span class="text-muted small">
                        {{ __('locale.results_sur', ['first' => $templates->firstItem() ?? 0, 'last' => $templates->lastItem() ?? 0, 'total' => $templates->total()]) }}
                    </span>
                    <div>
                        {{ $templates->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
