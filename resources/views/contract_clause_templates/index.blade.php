@extends('layouts.app')
@section('portal-module', 'contract_clause_templates')
@section('portal-page', 'index')
@section('portal-kind', 'list')
@section('portal-family', 'quotes')
@section('portal-icon', 'file-lines')


@section('title', __('locale.menu_contract_clause_templates') ?? 'Contract clause templates')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.menu_contract_clause_templates') ?? 'Clauses types de contrat' }}</h1>
        <span class="header-count">({{ method_exists($clauseTemplates, 'total') ? $clauseTemplates->total() : count($clauseTemplates) }})</span>
    </div>
@endsection

@section('navbar-search')
    <form method="GET" action="{{ route('contract_clause_templates.index') }}" class="w-100">
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
    <a href="{{ route('contract_clause_templates.create') }}" class="btn btn-primary" title="{{ __('locale.add_clause') ?? 'Ajouter une clause' }}">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.add_clause') ?? 'Nouvelle clause' }}
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
                                <th class="ps-4">{{ __('locale.title') ?? 'Titre' }}</th>
                                <th>{{ __('locale.active') ?? 'Actif' }}</th>
                                <th class="text-end pe-4" style="width: 120px;">{{ __('locale.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($clauseTemplates as $template)
                                <tr style="cursor: pointer;" onclick="window.location='{{ route('contract_clause_templates.show', $template) }}'">
                                    <td class="ps-4">
                                        <a href="{{ route('contract_clause_templates.show', $template) }}" class="fw-semibold text-dark text-decoration-none">
                                            {{ $template->title }}
                                        </a>
                                    </td>
                                    <td>
                                        @if ($template->is_active)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">{{ __('locale.yes') ?? 'Oui' }}</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">{{ __('locale.no') ?? 'Non' }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4" onclick="event.stopPropagation();">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" id="clauseActionMenu{{ $template->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border" aria-labelledby="clauseActionMenu{{ $template->id }}">
                                                <li>
                                                    <a href="{{ route('contract_clause_templates.show', $template) }}" class="dropdown-item">
                                                        <i class="bi bi-eye me-2 text-info"></i>{{ __('locale.btn_show') ?? 'Voir' }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('contract_clause_templates.edit', $template) }}" class="dropdown-item">
                                                        <i class="bi bi-pencil-square me-2 text-primary"></i>{{ __('locale.edit') }}
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('contract_clause_templates.destroy', $template) }}" method="POST">
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
                                    <td colspan="3" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2 text-muted"></i>
                                        {{ __('locale.lbl_no_records_found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if(method_exists($clauseTemplates, 'hasPages') && $clauseTemplates->hasPages())
                <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
                    <span class="text-muted small">
                        {{ __('locale.results_sur', ['first' => $clauseTemplates->firstItem() ?? 0, 'last' => $clauseTemplates->lastItem() ?? 0, 'total' => $clauseTemplates->total()]) }}
                    </span>
                    <div>
                        {{ $clauseTemplates->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
