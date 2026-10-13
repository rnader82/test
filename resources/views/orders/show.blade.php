@extends('layouts.app')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.orders.titles.show') }} #{{ $order->id }}</h1>
    </div>
@endsection

@section('page-actions')
    @if (Auth::user()->is_admin || Auth::user()->hasPermission('edit-orders'))
        <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-outline-primary me-1" id="btn-edit-order-{{ $order->id }}">
            <i class="bi bi-pencil-square me-1"></i> {{ __('locale.edit') ?? 'Modifier' }}
        </a>
    @endif

    @if (Auth::user()->is_admin || Auth::user()->hasPermission('sync-orders-odoo'))
        <form action="{{ route('orders.sync-odoo', $order->id) }}" method="POST" class="d-inline-block me-1"
              onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').innerHTML='<i class=\'bi bi-arrow-repeat spin me-1\'></i> Syncing…';">
            @csrf
            <button type="submit" class="btn btn-outline-secondary" id="btn-sync-odoo-{{ $order->id }}">
                <i class="bi bi-arrow-repeat me-1"></i> {{ __('locale.btn_sync_odoo_draft') }}
            </button>
        </form>

        <form action="{{ route('orders.sync-supplier-orders', $order->id) }}" method="POST" class="d-inline-block me-1"
              onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').innerHTML='<i class=\'bi bi-arrow-repeat spin me-1\'></i> Syncing…';">
            @csrf
            <button type="submit" class="btn btn-outline-info" id="btn-sync-supplier-orders-{{ $order->id }}" title="{{ __('locale.btn_sync_supplier_orders') }}">
                <i class="bi bi-box-seam me-1"></i> {{ __('locale.btn_sync_supplier_orders') }}
            </button>
        </form>

        @if ($order->state !== 'sale')
            <form action="{{ route('orders.confirm-odoo', $order->id) }}" method="POST" class="d-inline-block me-1"
                  onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').innerHTML='<i class=\'bi bi-arrow-repeat spin me-1\'></i> Confirming…';">
                @csrf
                <button type="submit" class="btn btn-primary" id="btn-confirm-odoo-{{ $order->id }}">
                    <i class="bi bi-check-circle me-1"></i> {{ __('locale.btn_confirm_sale') }}
                </button>
            </form>
        @else
            <form action="{{ route('orders.mark-all-delivered', $order->id) }}" method="POST" class="d-inline-block me-1"
                  onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').innerHTML='<i class=\'bi bi-arrow-repeat spin me-1\'></i> Syncing…';">
                @csrf
                <button type="submit" class="btn btn-success" id="btn-mark-all-delivered-{{ $order->id }}">
                    <i class="bi bi-truck me-1"></i> {{ __('locale.btn_mark_all_delivered') }}
                </button>
            </form>
            <form action="{{ route('orders.revert-draft', $order->id) }}" method="POST" class="d-inline-block me-1"
                  onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').innerHTML='<i class=\'bi bi-arrow-repeat spin me-1\'></i> Reverting…';">
                @csrf
                <button type="submit" class="btn btn-outline-warning" id="btn-revert-draft-{{ $order->id }}">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> {{ __('locale.btn_revert_draft') }}
                </button>
            </form>
        @endif

    @endif

    @if (Auth::user()->is_admin || Auth::user()->hasPermission('import-orders-ftp') || Auth::user()->hasPermission('order-access'))
        <form action="{{ route('orders.reimport-ftp', $order->id) }}" method="POST" class="d-inline-block me-1"
              onsubmit="if (!confirm('{{ __('locale.confirm_reimport_ftp') }}')) { return false; } this.querySelector('button').disabled=true; this.querySelector('button').innerHTML='<i class=\'bi bi-arrow-repeat spin me-1\'></i> Reimporting…';">
            @csrf
            <button type="submit" class="btn btn-outline-secondary" id="btn-reimport-ftp-{{ $order->id }}" title="{{ __('locale.btn_reimport_ftp') }}" {{ empty($order->ref_interne) ? 'disabled' : '' }}>
                <i class="bi bi-cloud-arrow-down me-1"></i> {{ __('locale.btn_reimport_ftp') }}
            </button>
        </form>
    @endif
@endsection


@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">

        @php
            $contactLabel = $order->contact
                ? trim(
                    collect([$order->contact->prenom, $order->contact->nom])
                        ->filter()
                        ->implode(' '),
                )
                : null;
            $clientLabel = optional($order->client)->raison_sociale;
        @endphp

        <div class="row g-4">
            <div class="col-12">
                <div class="card card-premium mb-4">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                        <h5 class="mb-0 fw-bold d-flex align-items-center">
                            <i class="bi bi-file-earmark-text text-primary me-2 fs-4"></i> {{ __('locale.orders.sections.summary') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-3">
                                <span class="text-muted d-block small fw-bold text-uppercase mb-1">{{ __('locale.orders.fields.nature') }}</span>
                                <span class="fw-semibold text-dark">{{ $order->nature_piece ?? '—' }}</span>
                            </div>
                            <div class="col-md-3">
                                <span class="text-muted d-block small fw-bold text-uppercase mb-1">{{ __('locale.orders.fields.document_date') }}</span>
                                <span class="fw-semibold text-dark">{{ $order->date_piece ? format_date($order->date_piece, 'short') : '—' }}</span>
                            </div>
                            <div class="col-md-3">
                                <span class="text-muted d-block small fw-bold text-uppercase mb-1">{{ __('locale.orders.fields.delivery_date') }}</span>
                                <span class="fw-semibold text-dark">{{ $order->date_livraison ? format_date($order->date_livraison, 'short') : '—' }}</span>
                            </div>
                            <div class="col-md-3">
                                <span class="text-muted d-block small fw-bold text-uppercase mb-1">{{ __('locale.orders.fields.internal_reference') }}</span>
                                <span class="badge bg-light text-dark border-0 px-3 py-2 rounded-pill font-monospace fw-bold">
                                    {{ $order->ref_interne ?? '—' }}
                                </span>
                            </div>
                            
                            <hr class="my-2 opacity-25">

                            <div class="col-md-3">
                                <span class="text-muted d-block small fw-bold text-uppercase mb-1">{{ __('locale.orders.fields.status') }}</span>
                                <span class="badge bg-secondary-subtle text-secondary border-0 px-3 py-2 rounded-pill fw-bold">
                                    {{ $order->statut ?? '—' }}
                                </span>
                            </div>
                            <div class="col-md-3">
                                <span class="text-muted d-block small fw-bold text-uppercase mb-1">{{ __('locale.lbl_odoo_id') }}</span>
                                <span class="fw-semibold text-dark">
                                    @if ($order->odoo_id)
                                        @if (config('services.odoo.url'))
                                            <a href="{{ rtrim(config('services.odoo.url'), '/') }}/web#id={{ $order->odoo_id }}&model=sale.order&view_type=form"
                                               target="_blank"
                                               class="badge bg-info bg-opacity-10 text-info border-0 px-3 py-2 rounded-pill font-monospace fw-bold text-decoration-none">
                                                {{ $order->odoo_id }} <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                            </a>
                                        @else
                                            <span class="badge bg-info bg-opacity-10 text-info border-0 px-3 py-2 rounded-pill font-monospace fw-bold">
                                                {{ $order->odoo_id }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-muted italic">{{ __('locale.lbl_not_synced') }}</span>
                                    @endif
                                </span>
                            </div>
                            <div class="col-md-3">
                                <span class="text-muted d-block small fw-bold text-uppercase mb-1">{{ __('locale.lbl_odoo_state') }}</span>
                                <span class="fw-semibold text-dark">
                                    @if ($order->state === 'sale')
                                        <span class="badge bg-success bg-opacity-10 text-success border-0 px-3 py-2 rounded-pill fw-bold">
                                            {{ __('locale.lbl_odoo_sale') }}
                                        </span>
                                    @elseif ($order->state === 'draft' || $order->odoo_id)
                                        <span class="badge bg-warning bg-opacity-10 text-warning border-0 px-3 py-2 rounded-pill fw-bold">
                                            {{ __('locale.lbl_odoo_draft') }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border-0 px-3 py-2 rounded-pill fw-bold">
                                            —
                                        </span>
                                    @endif
                                </span>
                            </div>
                            <div class="col-md-3">
                                <span class="text-muted d-block small fw-bold text-uppercase mb-1">{{ __('locale.orders.sections.contact') }}</span>
                                <span class="fw-semibold text-dark">
                                    @if ($contactLabel)
                                        <a href="{{ route('contacts.show', $order->contact) }}" class="text-decoration-none text-primary fw-bold">
                                            <i class="bi bi-person me-1"></i> {{ $contactLabel }} ({{ $order->contact->id }})
                                        </a>
                                    @elseif($order->contact_id)
                                        <a href="{{ route('contacts.show', $order->contact_id) }}" class="text-decoration-none text-primary fw-bold">
                                            {{ $order->contact_id }}
                                        </a>
                                    @else
                                        <span class="text-muted italic">—</span>
                                    @endif
                                </span>
                            </div>
                            <div class="col-md-3">
                                <span class="text-muted d-block small fw-bold text-uppercase mb-1">{{ __('locale.orders.sections.client') }}</span>
                                <span class="fw-semibold text-dark">
                                    @if ($clientLabel)
                                        <a href="{{ route('clients.show', $order->client) }}" class="text-decoration-none text-primary fw-bold">
                                            <i class="bi bi-building me-1"></i> {{ $clientLabel }} ({{ $order->client->id }})
                                        </a>
                                    @elseif($order->client_id)
                                        <a href="{{ route('clients.show', $order->client_id) }}" class="text-decoration-none text-primary fw-bold">
                                            {{ $order->client_id }}
                                        </a>
                                    @else
                                        <span class="text-muted italic">—</span>
                                    @endif
                                </span>
                            </div>
                            <div class="col-md-3">
                                <span class="text-muted d-block small fw-bold text-uppercase mb-1">{{ __('locale.orders.fields.depot_id') }}</span>
                                <span class="fw-semibold text-dark">{{ $order->depot_id ?? '—' }}</span>
                            </div>

                            <hr class="my-2 opacity-25">

                            <div class="col-md-3">
                                <span class="text-muted d-block small fw-bold text-uppercase mb-1">{{ __('locale.orders.fields.customer_id') }}</span>
                                <span class="fw-semibold text-dark">{{ $order->tiers_id ?? '—' }}</span>
                            </div>
                            <div class="col-md-3">
                                <span class="text-muted d-block small fw-bold text-uppercase mb-1">{{ __('locale.orders.fields.invoice_customer_id') }}</span>
                                <span class="fw-semibold text-dark">{{ $order->tiers_facture_id ?? '—' }}</span>
                            </div>
                            <div class="col-md-3">
                                <span class="text-muted d-block small fw-bold text-uppercase mb-1">{{ __('locale.orders.fields.delivery_customer_id') }}</span>
                                <span class="fw-semibold text-dark">{{ $order->tiers_livre_id ?? '—' }}</span>
                            </div>
                            <div class="col-md-3">
                                <span class="text-muted d-block small fw-bold text-uppercase mb-1">{{ __('locale.orders.fields.payment_mode_id') }}</span>
                                <span class="fw-semibold text-dark">{{ $order->modereglement_id ?? '—' }}</span>
                            </div>

                            <hr class="my-2 opacity-25">

                            <div class="col-md-3">
                                <span class="text-muted d-block small fw-bold text-uppercase mb-1">{{ __('locale.orders.fields.tax_regime_id') }}</span>
                                <span class="fw-semibold text-dark">{{ $order->regime_taxe_id ?? '—' }}</span>
                            </div>
                            <div class="col-md-3">
                                <span class="text-muted d-block small fw-bold text-uppercase mb-1">{{ __('locale.orders.fields.footer_discount') }}</span>
                                <span class="fw-semibold text-dark">{{ $order->remise_pied ? format_number($order->remise_pied, 2) : '—' }}</span>
                            </div>
                            <div class="col-md-3">
                                <span class="text-muted d-block small fw-bold text-uppercase mb-1">{{ __('locale.orders.fields.total_ht') }}</span>
                                <span class="fw-semibold text-dark">{{ $order->total_ht ? format_number($order->total_ht, 2) : '—' }}</span>
                            </div>
                            <div class="col-md-3">
                                <span class="text-muted d-block small fw-bold text-uppercase mb-1">{{ __('locale.orders.fields.total_ttc') }}</span>
                                <span class="fw-bold text-primary fs-5">{{ $order->total_ttc ? format_number($order->total_ttc, 2) : '—' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card card-premium mb-4">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                        <h5 class="mb-0 fw-bold d-flex align-items-center">
                            <i class="bi bi-geo-alt text-warning me-2 fs-4"></i> {{ __('locale.orders.sections.addresses') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            @foreach (['livraison' => __('locale.orders.sections.delivery_address'), 'facturation' => __('locale.orders.sections.billing_address')] as $type => $label)
                                @php
                                    $address = $order->addresses->firstWhere('type', $type);
                                @endphp
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3 bg-light bg-opacity-50 border border-light h-100">
                                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-tag me-1 text-secondary"></i> {{ $label }}</h6>
                                        @if ($address)
                                            <ul class="list-unstyled mb-0 small">
                                                <li class="mb-2"><span class="text-muted fw-bold d-inline-block" style="width: 100px;">{{ __('locale.orders.fields.label') }}:</span> {{ $address->libelle ?? '—' }}</li>
                                                <li class="mb-2"><span class="text-muted fw-bold d-inline-block" style="width: 100px;">{{ __('locale.orders.fields.first_name') }}:</span> {{ $address->prenom ?? '—' }}</li>
                                                <li class="mb-2"><span class="text-muted fw-bold d-inline-block" style="width: 100px;">{{ __('locale.orders.fields.address') }}:</span> {{ collect([$address->adresse1, $address->adresse2, $address->adresse3])->filter()->implode(', ') ?: '—' }}</li>
                                                <li class="mb-2"><span class="text-muted fw-bold d-inline-block" style="width: 100px;">{{ __('locale.orders.fields.postal_code') }}:</span> {{ $address->code_postal ?? '—' }}</li>
                                                <li class="mb-2"><span class="text-muted fw-bold d-inline-block" style="width: 100px;">{{ __('locale.orders.fields.city') }}:</span> {{ $address->ville ?? '—' }}</li>
                                                <li class="mb-2"><span class="text-muted fw-bold d-inline-block" style="width: 100px;">{{ __('locale.orders.fields.country') }}:</span> {{ $address->pays ?? '—' }}</li>
                                                <li class="mb-0"><span class="text-muted fw-bold d-inline-block" style="width: 100px;">{{ __('locale.orders.fields.phone') }}:</span> {{ $address->telephone ?? '—' }}</li>
                                            </ul>
                                        @else
                                            <p class="text-muted mb-0 small italic text-secondary">{{ __('locale.orders.messages.no_address') }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card card-premium mb-4">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                        <h5 class="mb-0 fw-bold d-flex align-items-center">
                            <i class="bi bi-list-stars text-info me-2 fs-4"></i> {{ __('locale.orders.sections.lines') }}
                        </h5>
                    </div>
                    <div class="card-body p-0 mt-3">
                        <div class="table-responsive">
                            <table class="table table-premium mb-0">
                                <thead class="small text-uppercase text-muted">
                                    <tr>
                                        <th class="ps-4">{{ __('locale.orders.fields.position') }}</th>
                                        <th>{{ __('locale.orders.fields.type') }}</th>
                                        <th>{{ __('locale.orders.fields.code') }}</th>
                                        <th>{{ __('locale.orders.fields.equipment') }}</th>
                                        <th>{{ __('locale.orders.fields.label') }}</th>
                                        <th class="text-end">{{ __('locale.orders.fields.quantity') }}</th>
                                        <th class="text-end">{{ __('locale.orders.fields.quantity_delivered') }}</th>
                                        <th class="text-end">{{ __('locale.orders.fields.unit_ht') }}</th>
                                        <th class="text-end">{{ __('locale.orders.fields.unit_ttc') }}</th>
                                        <th class="text-end">{{ __('locale.orders.fields.amount_ht') }}</th>
                                        <th class="pe-4 text-end">{{ __('locale.orders.fields.amount_ttc') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($order->lines as $line)
                                        <tr>
                                            <td class="ps-4 fw-bold text-dark">{{ $line->position }}</td>
                                            <td>{{ $line->type_ligne ?? '—' }}</td>
                                            <td>
                                                <span class="badge bg-light text-dark border-0 px-2 py-1 font-monospace">
                                                    {{ $line->code_article ?? '—' }}
                                                </span>
                                            </td>
                                            <td>{{ $line->equipment_id ?? '—' }}</td>
                                            <td>{{ $line->libelle ?? '—' }}</td>
                                            <td class="text-end fw-semibold">{{ $line->qte ? format_number($line->qte, 3) : '—' }}</td>
                                            <td class="text-end">
                                                @if($line->qty_delivered !== null)
                                                    @php
                                                        $pct = ($line->qte && $line->qte > 0) ? round(($line->qty_delivered / $line->qte) * 100) : null;
                                                        $badgeClass = ($pct !== null && $pct >= 100) ? 'bg-success' : (($pct !== null && $pct > 0) ? 'bg-warning text-dark' : 'bg-secondary');
                                                    @endphp
                                                    <span class="badge {{ $badgeClass }} px-2 py-1">
                                                        {{ format_number($line->qty_delivered, 3) }}
                                                        @if($pct !== null)<small class="ms-1">({{ $pct }}%)</small>@endif
                                                    </span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td class="text-end">{{ $line->pu_ht ? format_number($line->pu_ht, 2) : '—' }}</td>
                                            <td class="text-end">{{ $line->pu_ttc ? format_number($line->pu_ttc, 2) : '—' }}</td>
                                            <td class="text-end fw-medium">{{ $line->montant_ht ? format_number($line->montant_ht, 2) : '—' }}</td>
                                            <td class="pe-4 text-end fw-bold text-primary">{{ $line->montant_ttc ? format_number($line->montant_ttc, 2) : '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center py-4">
                                                <p class="text-muted mb-0 small">{{ __('locale.orders.messages.no_lines') }}</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card card-premium mb-4">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                        <h5 class="mb-0 fw-bold d-flex align-items-center">
                            <i class="bi bi-truck text-success me-2 fs-4"></i> {{ __('locale.orders.sections.ports') }}
                        </h5>
                    </div>
                    <div class="card-body p-0 mt-3">
                        <div class="table-responsive">
                            <table class="table table-premium mb-0">
                                <thead class="small text-uppercase text-muted">
                                    <tr>
                                        <th class="ps-4">{{ __('locale.orders.fields.code') }}</th>
                                        <th>{{ __('locale.orders.fields.label') }}</th>
                                        <th>{{ __('locale.orders.fields.type') }}</th>
                                        <th class="text-center">{{ __('locale.orders.fields.billable') }}</th>
                                        <th class="pe-4 text-end">{{ __('locale.orders.fields.minimum_amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($order->ports as $port)
                                        <tr>
                                            <td class="ps-4 font-monospace fw-bold">{{ $port->code_port ?? '—' }}</td>
                                            <td>{{ $port->libelle ?? '—' }}</td>
                                            <td>{{ $port->type_port ?? '—' }}</td>
                                            <td class="text-center">
                                                @if ($port->facturable)
                                                    <span class="badge bg-success bg-opacity-10 text-success border-0 px-3 py-2 rounded-pill fw-bold">
                                                        <i class="bi bi-check-circle-fill me-1"></i> {{ __('locale.lbl_yes') }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border-0 px-3 py-2 rounded-pill fw-bold">
                                                        <i class="bi bi-x-circle-fill me-1"></i> {{ __('locale.lbl_no') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="pe-4 text-end fw-bold text-primary">{{ $port->montant_mini ? format_number($port->montant_mini, 2) : '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <p class="text-muted mb-0 small">{{ __('locale.orders.messages.no_ports') }}</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card card-premium mb-4">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold d-flex align-items-center">
                            <i class="bi bi-box-seam text-primary me-2 fs-4"></i> {{ __('locale.orders.sections.supplier_orders') }}
                            <span class="badge bg-primary bg-opacity-10 text-primary ms-2 rounded-pill fs-6">{{ $order->supplierOrders->count() }}</span>
                        </h5>
                        @if (Auth::user()->is_admin)
                            <form action="{{ route('orders.sync-supplier-orders', $order->id) }}" method="POST" class="d-inline"
                                  onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').innerHTML='<i class=\'bi bi-arrow-repeat spin me-1\'></i> Syncing…';">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="bi bi-arrow-repeat me-1"></i> {{ __('locale.btn_sync_supplier_orders') }}
                                </button>
                            </form>
                        @endif
                    </div>
                    <div class="card-body p-0 mt-3">
                        <div class="table-responsive">
                            <table class="table table-premium mb-0">
                                <thead class="small text-uppercase text-muted">
                                    <tr>
                                        <th class="ps-4">{{ __('locale.lbl_reference') ?? 'Référence' }}</th>
                                        <th>{{ __('locale.lbl_odoo_id') }}</th>
                                        <th>{{ __('locale.lbl_supplier') ?? 'Fournisseur' }}</th>
                                        <th>Date Commande</th>
                                        <th>Date Livraison</th>
                                        <th>État</th>
                                        <th class="text-end">Total HT</th>
                                        <th class="text-end">Total TTC</th>
                                        <th class="text-center">Articles</th>
                                        <th class="pe-4 text-end">{{ __('locale.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($order->supplierOrders as $so)
                                        @php
                                            $stateBadgeClass = match($so->state) {
                                                'purchase' => 'bg-success bg-opacity-10 text-success',
                                                'done' => 'bg-info bg-opacity-10 text-info',
                                                'cancel' => 'bg-danger bg-opacity-10 text-danger',
                                                'to approve' => 'bg-warning bg-opacity-10 text-warning',
                                                default => 'bg-secondary bg-opacity-10 text-secondary',
                                            };
                                            $stateLabel = match($so->state) {
                                                'purchase' => 'Bon de commande',
                                                'done' => 'Terminé / Reçu',
                                                'cancel' => 'Annulé',
                                                'to approve' => 'À approuver',
                                                'sent' => 'Envoyé',
                                                'draft' => 'Demande de prix',
                                                default => $so->state ?? ($so->modifiable ? 'Modifiable' : 'Figée'),
                                            };
                                            $totalDelivered = $so->lines->sum('quantity_delivered');
                                            $totalOrdered = $so->lines->sum('quantity');
                                        @endphp
                                        <tr>
                                            <td class="ps-4">
                                                <a href="{{ route('supplier-orders.show', $so) }}" class="fw-bold text-primary text-decoration-none">
                                                    {{ $so->numero ?? 'PO #' . $so->id }}
                                                </a>
                                                @if($so->origin && $so->origin !== $so->numero)
                                                    <span class="d-block small text-muted font-monospace">{{ $so->origin }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($so->odoo_id)
                                                    @if (config('services.odoo.url'))
                                                        <a href="{{ rtrim(config('services.odoo.url'), '/') }}/web#id={{ $so->odoo_id }}&model=purchase.order&view_type=form"
                                                           target="_blank"
                                                           class="badge bg-info bg-opacity-10 text-info border-0 px-2 py-1 rounded-pill font-monospace fw-bold text-decoration-none">
                                                            {{ $so->odoo_id }} <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                                        </a>
                                                    @else
                                                        <span class="badge bg-info bg-opacity-10 text-info border-0 px-2 py-1 rounded-pill font-monospace fw-bold">
                                                            {{ $so->odoo_id }}
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($so->supplier)
                                                    <a href="{{ route('suppliers.show', $so->supplier) }}" class="fw-semibold text-dark text-decoration-none">
                                                        <i class="bi bi-truck me-1 text-muted"></i> {{ $so->supplier->name }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>{{ $so->date_piece ? format_date($so->date_piece, 'short') : '—' }}</td>
                                            <td>{{ $so->date_livraison ? format_date($so->date_livraison, 'short') : '—' }}</td>
                                            <td>
                                                <span class="badge {{ $stateBadgeClass }} border-0 px-2 py-1 rounded-pill fw-bold">
                                                    {{ $stateLabel }}
                                                </span>
                                            </td>
                                            <td class="text-end fw-medium">{{ $so->total_ht ? format_number($so->total_ht, 2) : '—' }}</td>
                                            <td class="text-end fw-bold text-primary">{{ $so->total_ttc ? format_number($so->total_ttc, 2) : '—' }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark border-0 px-2 py-1 rounded-pill" title="Reçu: {{ format_number($totalDelivered, 2) }} / Commandé: {{ format_number($totalOrdered, 2) }}">
                                                    {{ $so->lines->count() }} art. ({{ format_number($totalDelivered, 0) }}/{{ format_number($totalOrdered, 0) }})
                                                </span>
                                            </td>
                                            <td class="pe-4 text-end">
                                                <a href="{{ route('supplier-orders.show', $so) }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-4">
                                                <p class="text-muted mb-0 small">{{ __('locale.orders.messages.no_supplier_orders') }}</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
