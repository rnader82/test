@extends('layouts.app')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.menu_deliveries') ?? 'Livraisons' }}</h1>
        <span class="header-count">({{ $deliveries->total() ?? 0 }})</span>
    </div>
@endsection

@section('navbar-search')
    <form method="GET" action="{{ route('deliveries.index') }}" class="w-100">
        <div class="search-bar-mockup">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('locale.deliveries_search_placeholder') ?? 'Rechercher un bon de livraison...' }}">
            @foreach(request()->except(['search', 'page']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </div>
    </form>
@endsection

@section('page-actions')
    @if (Auth::user()->is_admin || Auth::user()->isNovalyoAdmin() || Auth::user()->hasPermission('create-deliveries') || Auth::user()->hasPermission('deliveries-access'))
    <a href="{{ route('deliveries.create') }}" class="btn btn-primary" title="{{ __('locale.btn_new_delivery') }}">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_new_delivery') }}
    </a>
    @endif
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card card-premium mb-4">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('deliveries.index') }}" class="mb-0">
                    <input type="hidden" name="sort" value="{{ request('sort', 'id') }}">
                    <input type="hidden" name="direction" value="{{ request('direction', 'desc') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="search" class="form-label fw-bold small text-uppercase text-muted">Rechercher</label>
                            <div class="input-group">
                                <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control"
                                    placeholder="Numéro, origine, client...">
                                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="picking_type" class="form-label fw-bold small text-uppercase text-muted">Type d'opération</label>
                            <select name="picking_type" id="picking_type" class="form-select" onchange="this.form.submit()">
                                <option value="">Tous les types</option>
                                <option value="outgoing" @selected(request('picking_type') === 'outgoing')>📦 Client (Outgoing)</option>
                                <option value="internal" @selected(request('picking_type') === 'internal')>🔄 Interne (Internal)</option>
                                <option value="incoming" @selected(request('picking_type') === 'incoming')>📥 Fournisseur (Incoming)</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="state" class="form-label fw-bold small text-uppercase text-muted">Statut</label>
                            <select name="state" id="state" class="form-select" onchange="this.form.submit()">
                                <option value="">Tous les statuts</option>
                                <option value="draft" @selected(request('state') === 'draft')>Brouillon (Draft)</option>
                                <option value="waiting" @selected(request('state') === 'waiting')>En attente (Waiting)</option>
                                <option value="confirmed" @selected(request('state') === 'confirmed')>Confirmé (Confirmed)</option>
                                <option value="assigned" @selected(request('state') === 'assigned')>Prêt (Assigned)</option>
                                <option value="done" @selected(request('state') === 'done')>Fait (Done)</option>
                                <option value="cancel" @selected(request('state') === 'cancel')>Annulé (Cancel)</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="has_client" class="form-label fw-bold small text-uppercase text-muted">Client</label>
                            <select name="has_client" id="has_client" class="form-select" onchange="this.form.submit()">
                                <option value="">Tous</option>
                                <option value="yes" @selected(request('has_client') === 'yes')>Avec client</option>
                                <option value="no" @selected(request('has_client') === 'no')>Sans client</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label for="synced" class="form-label fw-bold small text-uppercase text-muted">Odoo</label>
                            <select name="synced" id="synced" class="form-select" onchange="this.form.submit()">
                                <option value="">Tous</option>
                                <option value="yes" @selected(request('synced') === 'yes')>Lié</option>
                                <option value="no" @selected(request('synced') === 'no')>Non lié</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end gap-2">
                            <a href="{{ route('deliveries.index') }}" class="btn btn-outline-secondary w-50" title="Réinitialiser les filtres">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                            @if (Auth::user()->is_admin || Auth::user()->isNovalyoAdmin() || Auth::user()->hasPermission('create-deliveries') || Auth::user()->hasPermission('deliveries-access'))
                            <a href="{{ route('deliveries.create') }}" class="btn btn-primary w-50 fw-bold" title="Nouveau BL">
                                <i class="bi bi-plus-lg me-1"></i>+
                            </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-premium">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>
                                <a href="{{ route('deliveries.index', array_merge(request()->query(), ['sort' => 'id', 'direction' => request('sort') === 'id' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark fw-bold">
                                    ID @if(request('sort') === 'id') <i class="bi bi-arrow-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i> @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('deliveries.index', array_merge(request()->query(), ['sort' => 'numero', 'direction' => request('sort') === 'numero' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark fw-bold">
                                    Numéro BL @if(request('sort') === 'numero') <i class="bi bi-arrow-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i> @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('deliveries.index', array_merge(request()->query(), ['sort' => 'picking_type', 'direction' => request('sort') === 'picking_type' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark fw-bold">
                                    Type @if(request('sort') === 'picking_type') <i class="bi bi-arrow-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i> @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('deliveries.index', array_merge(request()->query(), ['sort' => 'ref_interne', 'direction' => request('sort') === 'ref_interne' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark fw-bold">
                                    Réf. Interne (Origine) @if(request('sort') === 'ref_interne') <i class="bi bi-arrow-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i> @endif
                                </a>
                            </th>
                            <th>Client / Destinataire</th>
                            <th>Commande associée</th>
                            <th>
                                <a href="{{ route('deliveries.index', array_merge(request()->query(), ['sort' => 'odoo_id', 'direction' => request('sort') === 'odoo_id' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark fw-bold">
                                    Odoo ID @if(request('sort') === 'odoo_id') <i class="bi bi-arrow-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i> @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('deliveries.index', array_merge(request()->query(), ['sort' => 'date_livraison', 'direction' => request('sort') === 'date_livraison' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark fw-bold">
                                    Date Livraison @if(request('sort') === 'date_livraison') <i class="bi bi-arrow-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i> @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('deliveries.index', array_merge(request()->query(), ['sort' => 'state', 'direction' => request('sort') === 'state' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark fw-bold">
                                    Statut @if(request('sort') === 'state') <i class="bi bi-arrow-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i> @endif
                                </a>
                            </th>
                            <th>Lignes</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deliveries as $item)
                            <tr>
                                <td class="fw-bold">#{{ $item->id }}</td>
                                <td>
                                    <a href="{{ route('deliveries.show', $item) }}" class="fw-bold text-primary text-decoration-none">
                                        {{ $item->numero ?: 'BL-' . str_pad($item->id, 5, '0', STR_PAD_LEFT) }}
                                    </a>
                                </td>
                                <td>
                                    @if($item->picking_type === 'outgoing')
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle"><i class="bi bi-truck me-1"></i>Client</span>
                                    @elseif($item->picking_type === 'internal')
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle"><i class="bi bi-arrow-left-right me-1"></i>Interne</span>
                                    @elseif($item->picking_type === 'incoming')
                                        <span class="badge bg-info-subtle text-info border border-info-subtle"><i class="bi bi-box-arrow-in-down me-1"></i>Fournisseur</span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="font-monospace">{{ $item->ref_interne ?: ($item->origin ?: '—') }}</span>
                                </td>
                                <td>
                                    @if($item->client)
                                        <a href="{{ route('clients.show', $item->client) }}" class="text-dark text-decoration-none fw-semibold">
                                            {{ $item->client->raison_sociale }}
                                        </a>
                                    @elseif($item->picking_type === 'internal')
                                        <span class="text-muted small italic"><i class="bi bi-arrow-left-right me-1"></i>Transfert interne</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Sans client</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->order)
                                        <a href="{{ route('orders.show', $item->order) }}" class="badge bg-light text-dark border text-decoration-none" title="Voir la commande">
                                            <i class="bi bi-box me-1"></i>{{ $item->order->ref_interne ?: '#' . $item->order->id }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if (!empty($item->odoo_id))
                                        @if (config('services.odoo.url'))
                                            <a href="{{ rtrim(config('services.odoo.url'), '/') }}/web#id={{ $item->odoo_id }}&model=stock.picking&view_type=form"
                                                target="_blank"
                                                class="badge bg-success-subtle text-success-emphasis border border-success-subtle text-decoration-none"
                                                title="Ouvrir dans Odoo">
                                                <i class="bi bi-box-arrow-up-right me-1"></i>{{ $item->odoo_id }}
                                            </a>
                                        @else
                                            <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle">{{ $item->odoo_id }}</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle">Non lié</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $item->date_livraison ? $item->date_livraison->format('d/m/Y') : ($item->scheduled_date ? $item->scheduled_date->format('d/m/Y') : '—') }}
                                </td>
                                <td>
                                    @php
                                        $stateBadgeClass = match($item->state) {
                                            'done' => 'bg-success',
                                            'assigned' => 'bg-primary',
                                            'confirmed', 'waiting' => 'bg-warning text-dark',
                                            'cancel' => 'bg-danger',
                                            default => 'bg-secondary',
                                        };
                                        $stateLabel = match($item->state) {
                                            'done' => 'Livré (Done)',
                                            'assigned' => 'Prêt (Assigned)',
                                            'confirmed' => 'Confirmé',
                                            'waiting' => 'En attente',
                                            'cancel' => 'Annulé',
                                            'draft' => 'Brouillon',
                                            default => $item->state ?: '—',
                                        };
                                    @endphp
                                    <span class="badge {{ $stateBadgeClass }}">{{ $stateLabel }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $item->lines_count }}</span>
                                </td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-icon btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('deliveries.show', $item) }}">
                                                    <i class="bi bi-eye me-2 text-primary"></i>Voir les détails
                                                </a>
                                            </li>
                                            @if (Auth::user()->is_admin || Auth::user()->isNovalyoAdmin() || Auth::user()->hasPermission('edit-deliveries') || Auth::user()->hasPermission('deliveries-access'))
                                            <li>
                                                <a class="dropdown-item" href="{{ route('deliveries.edit', $item) }}">
                                                    <i class="bi bi-pencil me-2 text-warning"></i>Modifier
                                                </a>
                                            </li>
                                            @endif
                                            @if (Auth::user()->is_admin || Auth::user()->isNovalyoAdmin() || Auth::user()->hasPermission('delete-deliveries') || Auth::user()->hasPermission('deliveries-access'))
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('deliveries.destroy', $item) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer ce bon de livraison ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bi bi-trash me-2"></i>Supprimer
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
                                <td colspan="10" class="text-center py-5 text-muted">
                                    <i class="bi bi-truck fs-1 d-block mb-2 text-secondary"></i>
                                    Aucun bon de livraison trouvé.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($deliveries->hasPages())
                <div class="card-footer bg-transparent border-top p-3 d-flex justify-content-between align-items-center flex-wrap">
                    <span class="text-muted small">
                        Affichage de {{ $deliveries->firstItem() ?? 0 }} à {{ $deliveries->lastItem() ?? 0 }} sur {{ $deliveries->total() }} livraisons
                    </span>
                    {{ $deliveries->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
