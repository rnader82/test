@extends('layouts.app')
@section('portal-module', 'pricelists')
@section('portal-page', 'index')
@section('portal-kind', 'list')
@section('portal-family', 'catalog')
@section('portal-icon', 'tags')


@section('title', __('locale.pricelists_list_title') ?? 'Listes de prix')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1 class="mb-0">{{ __('locale.pricelists_list_title') ?? 'Listes de prix' }}</h1>
    </div>
@endsection

@section('navbar-search')
    <form method="GET" action="{{ route('pricelists.index') }}" class="w-100">
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
    <a href="{{ route('pricelists.create') }}" class="btn btn-primary" title="{{ __('locale.pricelists.create') }}">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.pricelists.create') }}
    </a>
@endsection

@section('content')
<style>
    .btn-odoo-primary {
        background-color: #714B67;
        color: white;
        border: 1px solid #714B67;
        font-weight: 500;
        border-radius: 4px;
        padding: 0.375rem 0.75rem;
        transition: all 0.2s;
    }
    .btn-odoo-primary:hover {
        background-color: #5c3c54;
        border-color: #5c3c54;
        color: white;
    }
    .btn-odoo-secondary {
        background-color: white;
        color: #495057;
        border: 1px solid #ced4da;
        font-weight: 500;
        border-radius: 4px;
        padding: 0.375rem 0.75rem;
        transition: all 0.2s;
    }
    .btn-odoo-secondary:hover {
        background-color: #f8f9fa;
        color: #212529;
    }
    .odoo-search-bar {
        border-radius: 4px;
        border: 1px solid #ced4da;
    }
    .odoo-table th {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: 600;
    }
    .odoo-card {
        border: 1px solid #dee2e6;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        border-radius: 4px;
        background: white;
    }
    .badge-active {
        background-color: #d1e7dd;
        color: #0f5132;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
    }
    .badge-inactive {
        background-color: #f8d7da;
        color: #842029;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
    }
</style>

<div class="container mt-3">
    <!-- Top Control Panel -->
    <div class="odoo-card p-3 mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('pricelists.create') }}" class="btn btn-odoo-primary">
                <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_nouveau') }}
            </a>
            <button class="btn btn-odoo-secondary" onclick="window.location.reload();">
                <i class="bi bi-arrow-clockwise"></i> {{ __('locale.btn_actualiser') }}
            </button>
        </div>

        <!-- Search input -->
        <form action="{{ route('pricelists.index') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('locale.search_pricelist_placeholder') }}" class="form-control odoo-search-bar" style="width: 250px;">
            <button type="submit" class="btn btn-odoo-secondary">{{ __('locale.lbl_search') }}</button>
            @if(request('search'))
                <a href="{{ route('pricelists.index') }}" class="btn btn-outline-danger btn-sm d-flex align-items-center">{{ __('locale.lbl_clear') }}</a>
            @endif
        </form>
    </div>

    <!-- Pricelist Table -->
    <div class="odoo-card p-4">
        @if ($pricelists->count())
            <div class="table-responsive">
                <table class="module-table table table-hover odoo-table align-middle">
                    <thead>
                        <tr>
                            <th>{{ __('locale.lbl_name') }}</th>
                            <th>{{ __('locale.lbl_currency') }}</th>
                            <th>{{ __('locale.menu_company') }}</th>
                            <th>{{ __('locale.lbl_rules_count') }}</th>
                            <th>{{ __('locale.lbl_status') }}</th>
                            <th class="text-end">{{ __('locale.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pricelists as $pl)
                            <tr>
                                <td class="fw-semibold">
                                    <a href="{{ route('pricelists.show', $pl->id) }}" style="color: #714B67; text-decoration: none;">
                                        {{ $pl->name }}
                                    </a>
                                </td>
                                <td>{{ $pl->currency }}</td>
                                <td>{{ $pl->company?->name ?? __('locale.lbl_all_companies') }}</td>
                                <td>
                                    <span class="badge bg-secondary rounded-pill">
                                        {{ $pl->items_count }}
                                    </span>
                                </td>
                                <td>
                                    @if ($pl->active)
                                        <span class="badge-active">{{ __('locale.active') }}</span>
                                    @else
                                        <span class="badge-inactive">{{ __('locale.inactive') }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-odoo-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('pricelists.show', $pl->id) }}" class="dropdown-item">
                                                    <i class="bi bi-eye me-2"></i> {{ __('locale.btn_consult') }}
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('pricelists.edit', $pl->id) }}" class="dropdown-item">
                                                    <i class="bi bi-pencil-square me-2"></i> {{ __('locale.btn_edit') }}
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('pricelists.destroy', $pl->id) }}" method="POST" onsubmit="return confirm('{{ __('locale.confirm_delete_pricelist') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bi bi-trash me-2"></i> {{ __('locale.btn_delete') }}
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $pricelists->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-tags fs-1 text-muted"></i>
                <h5 class="mt-3">{{ __('locale.no_pricelists_found') }}</h5>
                <p class="text-muted">{{ __('locale.create_new_pricelist_hint') }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
