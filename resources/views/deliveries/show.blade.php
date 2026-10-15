@extends('layouts.app')
@section('portal-module', 'deliveries')
@section('portal-page', 'show')
@section('portal-kind', 'record')
@section('portal-family', 'adv')
@section('portal-icon', 'boxes-stacked')


@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('deliveries.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <h1>Bon de Livraison : {{ $delivery->numero ?: '#' . $delivery->id }}</h1>
    </div>
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

        <div class="row">
            <!-- Main Info Card -->
            <div class="col-lg-8 mb-4">
                <div class="module-record-card card card-premium mb-4">
                    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0"><i class="bi bi-truck me-2 text-primary"></i>Informations de Livraison</h5>
                        <div class="d-flex gap-2">
                            @if (Auth::user()->is_admin || Auth::user()->isNovalyoAdmin() || Auth::user()->hasPermission('edit-deliveries') || Auth::user()->hasPermission('deliveries-access'))
                            <a href="{{ route('deliveries.edit', $delivery) }}" class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil me-1"></i>Modifier
                            </a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <span class="text-muted small text-uppercase fw-semibold d-block">Numéro BL</span>
                                <span class="fw-bold fs-6">{{ $delivery->numero ?: '—' }}</span>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted small text-uppercase fw-semibold d-block">Réf. Interne / Origine</span>
                                <span class="font-monospace fw-bold">{{ $delivery->ref_interne ?: ($delivery->origin ?: '—') }}</span>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted small text-uppercase fw-semibold d-block">Type d'Opération</span>
                                @if($delivery->picking_type === 'outgoing')
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-6"><i class="bi bi-truck me-1"></i>Livraison Client (Outgoing)</span>
                                @elseif($delivery->picking_type === 'internal')
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle fs-6"><i class="bi bi-arrow-left-right me-1"></i>Transfert Interne (Internal)</span>
                                @elseif($delivery->picking_type === 'incoming')
                                    <span class="badge bg-info-subtle text-info border border-info-subtle fs-6"><i class="bi bi-box-arrow-in-down me-1"></i>Réception Fournisseur (Incoming)</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted small text-uppercase fw-semibold d-block">Date de Livraison</span>
                                <span>{{ $delivery->date_livraison ? $delivery->date_livraison->format('d/m/Y H:i') : ($delivery->scheduled_date ? $delivery->scheduled_date->format('d/m/Y H:i') : '—') }}</span>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted small text-uppercase fw-semibold d-block">Statut</span>
                                @php
                                    $stateBadgeClass = match($delivery->state) {
                                        'done' => 'bg-success',
                                        'assigned' => 'bg-primary',
                                        'confirmed', 'waiting' => 'bg-warning text-dark',
                                        'cancel' => 'bg-danger',
                                        default => 'bg-secondary',
                                    };
                                    $stateLabel = match($delivery->state) {
                                        'done' => 'Livré (Done)',
                                        'assigned' => 'Prêt (Assigned)',
                                        'confirmed' => 'Confirmé',
                                        'waiting' => 'En attente',
                                        'cancel' => 'Annulé',
                                        'draft' => 'Brouillon',
                                        default => $delivery->state ?: '—',
                                    };
                                @endphp
                                <span class="badge {{ $stateBadgeClass }}">{{ $stateLabel }}</span>
                            </div>
                            @if($delivery->note)
                                <div class="col-12">
                                    <span class="text-muted small text-uppercase fw-semibold d-block">Notes</span>
                                    <div class="p-3 bg-light rounded mt-1">{{ $delivery->note }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Delivery Lines Table -->
                <div class="module-record-card card card-premium">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="fw-bold mb-0"><i class="bi bi-list-check me-2 text-primary"></i>Articles / Lignes de livraison ({{ $delivery->lines->count() }})</h5>
                    </div>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Référence</th>
                                    <th>Désignation</th>
                                    <th class="text-end">Qté Demandée</th>
                                    <th class="text-end">Qté Livrée</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($delivery->lines as $idx => $line)
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td>
                                            @if($line->equipment)
                                                <a href="{{ route('equipments.show', $line->equipment) }}" class="fw-bold text-decoration-none">
                                                    {{ $line->product_reference ?: $line->equipment->reference }}
                                                </a>
                                            @else
                                                <span class="font-monospace">{{ $line->product_reference ?: '—' }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $line->product_name ?: ($line->equipment?->name ?? '—') }}</td>
                                        <td class="text-end fw-semibold">{{ (float)$line->qty_demande }}</td>
                                        <td class="text-end fw-bold text-success">{{ (float)$line->qty_done }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            Aucune ligne d'article dans ce bon de livraison.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info Cards -->
            <div class="col-lg-4">
                <!-- Odoo Connection Card -->
                <div class="module-record-card card card-premium mb-4 border-top border-4 border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0">Synchronisation Odoo</h6>
                            <i class="bi bi-cloud-check text-primary fs-4"></i>
                        </div>
                        <div class="mb-3">
                            <span class="text-muted small text-uppercase fw-semibold d-block">Odoo Picking ID</span>
                            @if($delivery->odoo_id)
                                <span class="font-monospace fw-bold fs-5 text-success">{{ $delivery->odoo_id }}</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle">Non synchronisé</span>
                            @endif
                        </div>
                        @if($delivery->odoo_id && config('services.odoo.url'))
                            <a href="{{ rtrim(config('services.odoo.url'), '/') }}/web#id={{ $delivery->odoo_id }}&model=stock.picking&view_type=form"
                                target="_blank"
                                class="btn btn-outline-primary btn-sm w-100 fw-semibold">
                                <i class="bi bi-box-arrow-up-right me-1"></i>Ouvrir dans Odoo
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Client Card -->
                <div class="module-record-card card card-premium mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0">Client / Destinataire</h6>
                            <i class="bi bi-person text-secondary fs-4"></i>
                        </div>
                        @if($delivery->client)
                            <div class="mb-2">
                                <span class="fw-bold d-block">{{ $delivery->client->raison_sociale }}</span>
                                <span class="text-muted small">{{ $delivery->client->cegid_id ? 'Réf: ' . $delivery->client->cegid_id : '' }}</span>
                            </div>
                            <a href="{{ route('clients.show', $delivery->client) }}" class="btn btn-sm btn-outline-secondary w-100">
                                <i class="bi bi-eye me-1"></i>Voir la fiche client
                            </a>
                        @else
                            <span class="text-muted">Aucun client lié</span>
                        @endif
                    </div>
                </div>

                <!-- Order Card -->
                <div class="module-record-card card card-premium mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0">Commande liée</h6>
                            <i class="bi bi-box text-secondary fs-4"></i>
                        </div>
                        @if($delivery->order)
                            <div class="mb-2">
                                <span class="fw-bold d-block">{{ $delivery->order->ref_interne ?: '#' . $delivery->order->id }}</span>
                                <span class="text-muted small">{{ $delivery->order->date_piece ? $delivery->order->date_piece->format('d/m/Y') : '' }}</span>
                            </div>
                            <a href="{{ route('orders.show', $delivery->order) }}" class="btn btn-sm btn-outline-secondary w-100">
                                <i class="bi bi-box-arrow-up-right me-1"></i>Voir la commande
                            </a>
                        @else
                            <span class="text-muted">Aucune commande liée</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
