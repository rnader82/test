@extends('layouts.app')

@section('title', __('locale.combo.combos') ?? 'Choix de Combos')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <i class="bi bi-layers text-primary fs-4"></i>
        <h1 class="mb-0 fs-4">{{ __('locale.combo.combos') ?? 'Choix de Combos' }}</h1>
        <span class="header-count">({{ method_exists($combos, 'total') ? $combos->total() : count($combos) }})</span>
    </div>
@endsection

@section('navbar-search')
    <form method="GET" action="{{ route('combos.index') }}" class="w-100">
        <div class="search-bar-mockup">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('locale.search') ?? 'Rechercher un combo...' }}">
            @foreach(request()->except(['search', 'page']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </div>
    </form>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        @if(count($companies) > 1)
        <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-building me-1"></i>
                {{ request('company_id') ? ($companies->firstWhere('id', request('company_id'))->name ?? 'Société') : 'Toutes les sociétés' }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <li><a class="dropdown-item {{ !request('company_id') ? 'active' : '' }}" href="{{ route('combos.index', request()->except(['company_id', 'page'])) }}">Toutes les sociétés</a></li>
                @foreach($companies as $comp)
                    <li><a class="dropdown-item {{ request('company_id') == $comp->id ? 'active' : '' }}" href="{{ route('combos.index', array_merge(request()->except('page'), ['company_id' => $comp->id])) }}">{{ $comp->name }}</a></li>
                @endforeach
            </ul>
        </div>
        @endif
        @if (Auth::user()->is_admin || Auth::user()->isNovalyoAdmin() || Auth::user()->hasPermission('create-combos') || Auth::user()->hasPermission('combos-access'))
        <a href="{{ route('combos.create') }}" class="btn btn-primary" title="{{ __('locale.combo.add_combo') ?? 'Créer un choix de combo' }}">
            <i class="bi bi-plus-lg me-1"></i> {{ __('locale.combo.add_combo') ?? 'Nouveau combo' }}
        </a>
        @endif
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
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
                                <th class="ps-4" style="width: 100px;">{{ __('locale.id') ?? 'ID' }}</th>
                                <th>{{ __('locale.name') ?? 'Nom' }}</th>
                                <th>{{ __('locale.company') ?? 'Société' }}</th>
                                <th class="text-center">{{ __('locale.combo.items_count') ?? 'Articles / Options' }}</th>
                                <th class="text-end">{{ __('locale.combo.base_price') ?? 'Prix de base' }}</th>
                                <th class="text-center">Produits associés</th>
                                <th class="text-center" style="width: 120px;">Odoo</th>
                                <th class="text-end pe-4" style="width: 130px;">{{ __('locale.actions') ?? 'Actions' }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($combos as $combo)
                                <tr style="cursor: pointer;" onclick="window.location='{{ route('combos.show', $combo->id) }}'">
                                    <td class="ps-4">
                                        <span class="badge bg-light text-secondary border font-monospace">#{{ $combo->id }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('combos.show', $combo->id) }}" class="fw-semibold text-dark text-decoration-none">
                                            {{ $combo->name }}
                                        </a>
                                        @if($combo->sequence)
                                            <small class="text-muted d-block font-monospace">Ordre: {{ $combo->sequence }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($combo->company)
                                            <span class="badge bg-info-subtle text-info border border-info-subtle">
                                                <i class="bi bi-building me-1"></i>{{ $combo->company->name }}
                                            </span>
                                        @else
                                            <span class="badge bg-light text-secondary border">
                                                <i class="bi bi-globe me-1"></i>Toutes sociétés
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                            <i class="bi bi-box me-1"></i>{{ $combo->combo_items_count ?? $combo->comboItems->count() }}
                                        </span>
                                    </td>
                                    <td class="text-end fw-semibold text-dark">
                                        {{ number_format($combo->base_price, 2, ',', ' ') }} €
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">
                                            <i class="bi bi-link-45deg me-1"></i>{{ $combo->equipments_count ?? $combo->equipments->count() }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($combo->odoo_id)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle" title="ID Odoo: {{ $combo->odoo_id }}">
                                                <i class="bi bi-check2-circle me-1"></i>#{{ $combo->odoo_id }}
                                            </span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle" title="Non synchronisé">
                                                <i class="bi bi-clock me-1"></i>Non sync
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4" onclick="event.stopPropagation();">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" id="comboActionMenu{{ $combo->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border" aria-labelledby="comboActionMenu{{ $combo->id }}">
                                                <li>
                                                    <a href="{{ route('combos.show', $combo->id) }}" class="dropdown-item">
                                                        <i class="bi bi-eye me-2 text-info"></i>{{ __('locale.btn_show') ?? 'Voir' }}
                                                    </a>
                                                </li>
                                                @if (Auth::user()->is_admin || Auth::user()->isNovalyoAdmin() || Auth::user()->hasPermission('edit-combos') || Auth::user()->hasPermission('combos-access'))
                                                <li>
                                                    <a href="{{ route('combos.edit', $combo->id) }}" class="dropdown-item">
                                                        <i class="bi bi-pencil-square me-2 text-primary"></i>{{ __('locale.edit') ?? 'Modifier' }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('combos.sync-odoo', $combo->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="bi bi-arrow-repeat me-2 text-success"></i>{{ __('locale.combo.sync_to_odoo') ?? 'Sync Odoo' }}
                                                        </button>
                                                    </form>
                                                </li>
                                                @endif
                                                @if (Auth::user()->is_admin || Auth::user()->isNovalyoAdmin() || Auth::user()->hasPermission('delete-combos') || Auth::user()->hasPermission('combos-access'))
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('combos.destroy', $combo->id) }}" method="POST" onsubmit="return confirm('{{ __('locale.confirm_delete') ?? 'Êtes-vous sûr de vouloir supprimer ce combo ?' }}');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="bi bi-trash3 me-2"></i>{{ __('locale.delete') ?? 'Supprimer' }}
                                                        </button>
                                                    </form>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="bi bi-layers fs-1 d-block mb-2 text-secondary"></i>
                                        <p class="mb-2 fw-semibold">Aucun choix de combo trouvé</p>
                                        @if (Auth::user()->is_admin || Auth::user()->isNovalyoAdmin() || Auth::user()->hasPermission('create-combos') || Auth::user()->hasPermission('combos-access'))
                                        <a href="{{ route('combos.create') }}" class="btn btn-sm btn-primary mt-1">
                                            <i class="bi bi-plus-lg me-1"></i> Créer le premier combo
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($combos, 'links'))
                <div class="p-3 border-top d-flex justify-content-end">
                    {{ $combos->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
