@extends('layouts.app')
@section('portal-module', 'combos')
@section('portal-page', 'show')
@section('portal-kind', 'record')
@section('portal-family', 'catalog')
@section('portal-icon', 'tags')


@section('title', $combo->name . ' - ' . (__('locale.combo.title') ?? 'Combo'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('combos.index') }}" class="btn btn-sm btn-outline-secondary py-1 px-2">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="mb-0 fs-4">{{ $combo->name }}</h1>
        @if($combo->odoo_id)
            @if(config('services.odoo.url'))
                <a href="{{ rtrim(config('services.odoo.url'), '/') }}/web#id={{ $combo->odoo_id }}&model=product.combo&view_type=form" target="_blank" rel="noopener" class="badge bg-success-subtle text-success border border-success-subtle font-monospace text-decoration-none ms-1" title="Ouvrir dans Odoo">
                    <i class="bi bi-check2-circle me-1"></i>Odoo #{{ $combo->odoo_id }} <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                </a>
            @else
                <span class="badge bg-success-subtle text-success border border-success-subtle font-monospace ms-1">
                    <i class="bi bi-check2-circle me-1"></i>Odoo #{{ $combo->odoo_id }}
                </span>
            @endif
        @else
            <span class="badge bg-warning-subtle text-warning border border-warning-subtle ms-1">
                <i class="bi bi-clock me-1"></i>Non synchronisé avec Odoo
            </span>
        @endif
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        @if (Auth::user()->is_admin || Auth::user()->isNovalyoAdmin() || Auth::user()->hasPermission('edit-combos') || Auth::user()->hasPermission('combos-access'))
        <form action="{{ route('combos.sync-odoo', $combo->id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-success">
                <i class="bi bi-arrow-repeat me-1"></i> {{ __('locale.combo.sync_to_odoo') ?? 'Sync Odoo' }}
            </button>
        </form>
        <a href="{{ route('combos.edit', $combo->id) }}" class="btn btn-primary">
            <i class="bi bi-pencil-square me-1"></i> {{ __('locale.edit') ?? 'Modifier' }}
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

        <div class="row g-4">
            <!-- Left Column: Combo Details -->
            <div class="col-lg-4">
                <div class="module-record-card card card-premium shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bi bi-info-circle text-primary me-2"></i>Détails du Combo
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="text-muted fw-semibold">Nom du choix :</span>
                                <span class="fw-bold text-dark">{{ $combo->name }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="text-muted fw-semibold">Société :</span>
                                @if($combo->company)
                                    <span class="badge bg-info-subtle text-info border border-info-subtle">
                                        <i class="bi bi-building me-1"></i>{{ $combo->company->name }}
                                    </span>
                                @else
                                    <span class="badge bg-light text-secondary border">
                                        <i class="bi bi-globe me-1"></i>Toutes les sociétés
                                    </span>
                                @endif
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="text-muted fw-semibold">Ordre / Séquence :</span>
                                <span class="badge bg-light text-dark border font-monospace">{{ $combo->sequence ?? 0 }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="text-muted fw-semibold">Prix de base calculé :</span>
                                <span class="fw-bold fs-5 text-primary">{{ number_format($combo->base_price, 2, ',', ' ') }} €</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="text-muted fw-semibold">Nombre d'options :</span>
                                <span class="badge rounded-pill bg-primary px-3 py-1">{{ $combo->comboItems->count() }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="text-muted fw-semibold">ID Odoo :</span>
                                @if($combo->odoo_id)
                                    <span class="font-monospace text-dark fw-bold">#{{ $combo->odoo_id }}</span>
                                @else
                                    <span class="text-muted small">Non synchronisé</span>
                                @endif
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                <span class="text-muted fw-semibold">Créé le :</span>
                                <span class="text-muted small">{{ $combo->created_at ? $combo->created_at->format('d/m/Y H:i') : '—' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Right Column: Articles & Related Equipments -->
            <div class="col-lg-8">
                <!-- Card 1: Options incluses dans ce combo -->
                <div class="module-record-card card card-premium shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bi bi-list-check text-primary me-2"></i>{{ __('locale.combo.options') ?? 'Articles & Options' }}
                            <span class="badge rounded-pill bg-primary-subtle text-primary ms-1">{{ $combo->comboItems->count() }}</span>
                        </h5>
                        <a href="{{ route('combos.edit', $combo->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil me-1"></i>Modifier les options
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4" style="width: 50px;">#</th>
                                        <th>Article / Matériel</th>
                                        <th class="text-center" style="width: 90px;">{{ __('locale.quantity') ?? 'Quantité' }}</th>
                                        <th class="text-end" style="width: 140px;">Prix catalogue</th>
                                        <th class="text-end" style="width: 150px;">Supplément</th>
                                        <th class="text-end pe-4" style="width: 160px;">Prix total option</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($combo->comboItems as $index => $item)
                                        @php
                                            $eq = $item->equipment;
                                            $catPrice = $eq ? (float) ($eq->price_per_unit ?? $eq->price_excl_tax ?? 0) : 0;
                                            $extra = (float) ($item->extra_price ?? 0);
                                            $totalOption = $combo->base_price + $extra;
                                        @endphp
                                        <tr>
                                            <td class="ps-4 text-muted small font-monospace">{{ $index + 1 }}</td>
                                            <td>
                                                @if($eq)
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-light text-secondary border font-monospace">{{ $eq->reference ?? 'N/A' }}</span>
                                                        <a href="{{ route('equipments.show', $eq->id) }}" class="fw-semibold text-dark text-decoration-none">
                                                            {{ $eq->name }}
                                                        </a>
                                                    </div>
                                                    @if($eq->brand || $eq->equipmentCategory)
                                                        <small class="text-muted d-block mt-1">
                                                            {{ $eq->brand ? $eq->brand->name : '' }}
                                                            {{ ($eq->brand && $eq->equipmentCategory) ? ' • ' : '' }}
                                                            {{ $eq->equipmentCategory ? ($eq->equipmentCategory->name_raw ?? $eq->equipmentCategory->name) : '' }}
                                                        </small>
                                                    @endif
                                                @else
                                                    <span class="text-danger small">Équipement introuvable (#{{ $item->equipment_id }})</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary-subtle text-dark border">{{ (int) ($item->quantity ?? 1) }}</span>
                                            </td>
                                            <td class="text-end fw-semibold text-muted">
                                                {{ number_format($catPrice, 2, ',', ' ') }} €
                                            </td>
                                            <td class="text-end">
                                                @if($extra > 0)
                                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle fw-bold">
                                                        + {{ number_format($extra, 2, ',', ' ') }} €
                                                    </span>
                                                @else
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                        Inclus (0,00 €)
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-4 fw-bold text-primary">
                                                {{ number_format($totalOption, 2, ',', ' ') }} €
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                {{ __('locale.combo.no_items') ?? 'Aucun article dans ce combo pour le moment.' }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Produits / Équipements utilisant ce combo -->
                <div class="module-record-card card card-premium shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bi bi-box-seam text-secondary me-2"></i>Produits Combos utilisant ce choix
                            <span class="badge rounded-pill bg-secondary-subtle text-secondary ms-1">{{ $combo->equipments->count() }}</span>
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @if($combo->equipments->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Référence</th>
                                            <th>Nom de l'article combo</th>
                                            <th class="text-end">Prix unitaire</th>
                                            <th class="text-center">Statut</th>
                                            <th class="text-end pe-4">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($combo->equipments as $equipment)
                                            <tr>
                                                <td class="ps-4">
                                                    <span class="badge bg-light text-secondary border font-monospace">{{ $equipment->reference ?? 'N/A' }}</span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('equipments.show', $equipment->id) }}" class="fw-semibold text-dark text-decoration-none">
                                                        {{ $equipment->name }}
                                                    </a>
                                                </td>
                                                <td class="text-end fw-semibold">
                                                    {{ number_format((float) ($equipment->price_per_unit ?? $equipment->price_excl_tax ?? 0), 2, ',', ' ') }} €
                                                </td>
                                                <td class="text-center">
                                                    @if($equipment->closed)
                                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Fermé</span>
                                                    @else
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle">Actif</span>
                                                    @endif
                                                </td>
                                                <td class="text-end pe-4">
                                                    <a href="{{ route('equipments.show', $equipment->id) }}" class="btn btn-sm btn-outline-secondary" title="Voir l'équipement">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-info-circle fs-3 d-block mb-2 text-secondary"></i>
                                <p class="mb-0 small">Ce choix de combo n'est associé à aucun produit combo pour le moment.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
