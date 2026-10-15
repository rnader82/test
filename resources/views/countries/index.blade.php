@extends('layouts.app')
@section('portal-module', 'countries')
@section('portal-page', 'index')
@section('portal-kind', 'list')
@section('portal-family', 'admin')
@section('portal-icon', 'gear')


@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>Pays</h1>
        <span class="header-count">({{ $countries->total() }})</span>
    </div>
@endsection

@section('navbar-search')
    <form method="GET" action="{{ route('countries.index') }}" class="w-100">
        <div class="search-bar-mockup">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par nom ou code ISO...">
            @foreach(request()->except(['search', 'page']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </div>
    </form>
@endsection

@section('page-actions')
    <a href="{{ route('countries.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_create_country') }}
    </a>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger border-0 shadow-sm mb-4">{{ session('error') }}</div>
        @endif

        {{-- Filter bar --}}
        <form method="GET" action="{{ route('countries.index') }}" class="d-flex align-items-center gap-2 mb-3 flex-wrap">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            <select name="synced" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                <option value="">Tous les pays</option>
                <option value="1" {{ request('synced') === '1' ? 'selected' : '' }}>✅ Avec Odoo ID</option>
                <option value="0" {{ request('synced') === '0' ? 'selected' : '' }}>⚠️ Sans Odoo ID</option>
            </select>
            <span class="text-muted small ms-auto">
                {{ $countries->firstItem() ?? 0 }}&ndash;{{ $countries->lastItem() ?? 0 }} sur {{ $countries->total() }}
            </span>
        </form>

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="module-table table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('locale.name_fr') }}</th>
                            <th>{{ __('locale.name_en') }}</th>
                            <th class="text-center">{{ __('locale.iso_code') }}</th>
                            <th class="text-center">{{ __('locale.odoo_id') }}</th>
                            <th class="text-end">{{ __('locale.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($countries as $country)
                            <tr style="cursor: pointer;" onclick="window.location='{{ route('countries.show', $country) }}'">
                                <td class="fw-semibold">
                                    <a href="{{ route('countries.show', $country) }}" class="fw-semibold text-dark text-decoration-none">
                                        {{ $country->name_fr }}
                                    </a>
                                </td>
                                <td class="text-muted">{{ $country->name_en }}</td>
                                <td class="text-center">
                                    @if($country->iso_code)
                                        <span class="badge bg-label-secondary font-monospace">{{ $country->iso_code }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($country->odoo_id)
                                        <span class="badge bg-label-success font-monospace">{{ $country->odoo_id }}</span>
                                    @else
                                        <span class="badge bg-label-warning">Non synchronisé</span>
                                    @endif
                                </td>
                                <td class="text-end" onclick="event.stopPropagation();">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary border-0" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('countries.show', $country) }}" class="dropdown-item">
                                                    <i class="bi bi-eye me-2 text-info"></i>{{ __('locale.btn_show') ?? 'Voir' }}
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('countries.edit', $country) }}" class="dropdown-item">
                                                    <i class="bi bi-pencil-square me-2"></i>Modifier
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('countries.destroy', $country) }}" method="POST"
                                                      onsubmit="return confirm('Supprimer ce pays ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bi bi-trash me-2"></i>Supprimer
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-globe fs-2 d-block mb-2"></i>
                                    Aucun pays trouvé.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3 d-flex justify-content-center">
            {{ $countries->withQueryString()->links() }}
        </div>

    </div>
</div>
@endsection
