@extends('layouts.app')
@section('portal-module', 'odoo_fiscal_positions')
@section('portal-page', 'index')
@section('portal-kind', 'list')
@section('portal-family', 'catalog')
@section('portal-icon', 'tags')


@section('title', __('locale.lbl_odoo_fiscal_positions') ?? 'Positions Fiscales Odoo')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.lbl_odoo_fiscal_positions') ?? 'Positions Fiscales Odoo' }}</h1>
        <span class="header-count">({{ $fiscal_positions->total() }})</span>
    </div>
@endsection

@section('navbar-search')
    <form method="GET" action="{{ route('odoo_fiscal_positions.index') }}" class="w-100">
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
    <a href="{{ route('odoo_fiscal_positions.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.add_fiscal_position') ?? 'Ajouter une position fiscale' }}
    </a>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <form action="{{ route('odoo_fiscal_positions.index') }}" method="GET" class="d-flex w-50">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="{{ __('locale.search') ?? 'Rechercher...' }}" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
            <div class="d-flex gap-2">
                <form action="{{ route('odoo_fiscal_positions.sync') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-repeat me-1"></i> {{ __('locale.sync_from_odoo') ?? 'Synchroniser depuis Odoo' }}
                    </button>
                </form>
                <a href="{{ route('odoo_fiscal_positions.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> {{ __('locale.add_fiscal_position') ?? 'Ajouter une position fiscale' }}
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card card-premium">
            <div class="table-responsive">
                <table class="module-table table table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>{{ __('locale.odoo_id') }}</th>
                            <th>{{ __('locale.name') }}</th>
                            <th>{{ __('locale.odoo_company_id') }}</th>
                            <th>{{ __('locale.company_name') }}</th>
                            <th class="text-end">{{ __('locale.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($fiscal_positions as $position)
                            <tr style="cursor: pointer;" onclick="window.location='{{ route('odoo_fiscal_positions.show', $position->id) }}'">
                                <td class="fw-bold">{{ $position->odoo_id }}</td>
                                <td>
                                    <a href="{{ route('odoo_fiscal_positions.show', $position->id) }}" class="fw-semibold text-dark text-decoration-none">
                                        {{ $position->name }}
                                    </a>
                                </td>
                                <td>{{ $position->odoo_company_id ?? '-' }}</td>
                                <td>{{ $position->company_name ?? '-' }}</td>
                                <td class="text-end" onclick="event.stopPropagation();">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" type="button" id="posActionMenu{{ $position->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="posActionMenu{{ $position->id }}">
                                            <li>
                                                <a href="{{ route('odoo_fiscal_positions.show', $position->id) }}" class="dropdown-item">
                                                    <i class="bi bi-eye me-2 text-info"></i>{{ __('locale.btn_show') ?? 'Voir' }}
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('odoo_fiscal_positions.edit', $position->id) }}" class="dropdown-item">
                                                    <i class="bi bi-pencil-square me-2"></i>{{ __('locale.edit') ?? 'Modifier' }}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    Aucune position fiscale trouvée.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $fiscal_positions->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
