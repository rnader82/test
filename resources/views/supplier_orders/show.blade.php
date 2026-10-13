@extends('layouts.app')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('supplier-orders.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">
            Commande : {{ $supplierOrder->numero }}
        </h1>
    </div>
@endsection

@section('page-actions')
    <a href="{{ route('supplier-orders.edit', $supplierOrder) }}" class="btn btn-warning text-dark fw-bold">
        <i class="bi bi-pencil-square me-1"></i> Modifier la pièce
    </a>
@endsection

@section('content')
<div class="container-xxl flex-grow-1">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Supplier Order Info -->
        <div class="col-md-4 mb-4">
            <div class="card card-premium shadow-sm border-0 h-100">
                <div class="card-header bg-primary text-white border-0 py-3">
                    <h5 class="mb-0 text-white"><i class="bi bi-info-circle me-2 text-white"></i>Détails de la Commande</h5>
                </div>
                <div class="card-body pt-4">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3 border-bottom pb-2">
                            <span class="fw-bold d-block text-muted small text-uppercase">Numéro de Commande</span>
                            <span class="fs-5 fw-bold text-dark">{{ $supplierOrder->numero }}</span>
                        </li>
                        <li class="mb-3 border-bottom pb-2">
                            <span class="fw-bold d-block text-muted small text-uppercase">{{ __('locale.lbl_odoo_id') }}</span>
                            @if ($supplierOrder->odoo_id)
                                @if (config('services.odoo.url'))
                                    <a href="{{ rtrim(config('services.odoo.url'), '/') }}/web#id={{ $supplierOrder->odoo_id }}&model=purchase.order&view_type=form"
                                       target="_blank"
                                       class="badge bg-info bg-opacity-10 text-info border-0 px-3 py-2 rounded-pill font-monospace fw-bold text-decoration-none">
                                        {{ $supplierOrder->odoo_id }} <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                    </a>
                                @else
                                    <span class="badge bg-info bg-opacity-10 text-info border-0 px-3 py-2 rounded-pill font-monospace fw-bold">
                                        {{ $supplierOrder->odoo_id }}
                                    </span>
                                @endif
                            @else
                                <span class="text-muted italic">{{ __('locale.lbl_not_synced') }}</span>
                            @endif
                        </li>
                        <li class="mb-3 border-bottom pb-2">
                            <span class="fw-bold d-block text-muted small text-uppercase">Commande Client Associée</span>
                            @if ($supplierOrder->order)
                                <a href="{{ route('orders.show', $supplierOrder->order) }}" class="fw-bold text-primary text-decoration-none">
                                    <i class="bi bi-file-earmark-text me-1"></i> Commande #{{ $supplierOrder->order->id }} ({{ $supplierOrder->order->ref_interne ?? $supplierOrder->order->numero }})
                                </a>
                                @if($supplierOrder->order->client)
                                    <span class="d-block small text-muted">{{ $supplierOrder->order->client->raison_sociale }}</span>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </li>
                        <li class="mb-3 border-bottom pb-2">
                            <span class="fw-bold d-block text-muted small text-uppercase">Référence Interne / Origine</span>
                            <span class="fs-6 text-dark">{{ $supplierOrder->ref_interne ?? ($supplierOrder->origin ?? '-') }}</span>
                        </li>
                        <li class="mb-3 border-bottom pb-2">
                            <span class="fw-bold d-block text-muted small text-uppercase">Fournisseur</span>
                            @if($supplierOrder->supplier)
                                <a href="{{ route('suppliers.show', $supplierOrder->supplier) }}" class="fw-bold fs-5 text-primary text-decoration-none">
                                    {{ $supplierOrder->supplier->name }}
                                </a>
                                @if($supplierOrder->supplier->cegid_id)
                                    <span class="badge bg-label-info ms-2">CEGID: {{ $supplierOrder->supplier->cegid_id }}</span>
                                @endif
                            @else
                                <span class="text-danger fw-bold">Non défini</span>
                            @endif
                        </li>
                        <li class="mb-3 border-bottom pb-2">
                            <span class="fw-bold d-block text-muted small text-uppercase">Date de Pièce</span>
                            <span class="fs-6 text-dark">{{ $supplierOrder->date_piece ? $supplierOrder->date_piece->format('d/m/Y') : '-' }}</span>
                        </li>
                        <li class="mb-3 border-bottom pb-2">
                            <span class="fw-bold d-block text-muted small text-uppercase">Date de Livraison Prévue</span>
                            <span class="fs-6 text-primary fw-bold">{{ $supplierOrder->date_livraison ? $supplierOrder->date_livraison->format('d/m/Y') : '-' }}</span>
                        </li>
                        <li class="mb-3 border-bottom pb-2">
                            <span class="fw-bold d-block text-muted small text-uppercase">État</span>
                            @php
                                $stateBadgeClass = match($supplierOrder->state) {
                                    'purchase' => 'bg-label-success',
                                    'done' => 'bg-label-info',
                                    'cancel' => 'bg-label-danger',
                                    'to approve' => 'bg-label-warning',
                                    'sent' => 'bg-label-info',
                                    'draft' => 'bg-label-secondary',
                                    default => $supplierOrder->modifiable ? 'bg-label-success' : 'bg-label-danger',
                                };
                                $stateLabel = match($supplierOrder->state) {
                                    'purchase' => 'Bon de commande',
                                    'done' => 'Terminé / Reçu',
                                    'cancel' => 'Annulé',
                                    'to approve' => 'À approuver',
                                    'sent' => 'Envoyé',
                                    'draft' => 'Demande de prix',
                                    default => $supplierOrder->state ?? ($supplierOrder->modifiable ? 'Modifiable' : 'Figée'),
                                };
                            @endphp
                            <span class="badge {{ $stateBadgeClass }} px-3 py-2 fs-6">{{ $stateLabel }}</span>
                        </li>
                        <li class="mb-3 border-bottom pb-2">
                            <span class="fw-bold d-block text-muted small text-uppercase">Devise</span>
                            <span class="badge bg-label-secondary px-3 py-2 fs-6">{{ $supplierOrder->devise ?? 'EUR' }}</span>
                        </li>
                        <li class="mb-3 border-bottom pb-2">
                            <span class="fw-bold d-block text-muted small text-uppercase">Total HT</span>
                            <span class="fs-5 fw-bold text-dark">{{ number_format($supplierOrder->total_ht, 2, ',', ' ') }} {{ $supplierOrder->devise ?? 'EUR' }}</span>
                        </li>
                        <li class="mb-3 border-bottom pb-2">
                            <span class="fw-bold d-block text-muted small text-uppercase">Total TTC (20% TVA)</span>
                            <span class="fs-4 fw-bold text-primary">{{ number_format($supplierOrder->total_ttc, 2, ',', ' ') }} {{ $supplierOrder->devise ?? 'EUR' }}</span>
                        </li>
                        <li>
                            <span class="fw-bold d-block text-muted small text-uppercase">Notes / Observations</span>
                            <span class="text-dark small">{!! nl2br(e($supplierOrder->notes ?? '-')) !!}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Supplier Order Lines -->
        <div class="col-md-8 mb-4">
            <div class="card card-premium shadow-sm border-0 h-100">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center py-3 bg-light">
                    <h5 class="mb-0 text-dark fw-bold"><i class="bi bi-list-stars me-2"></i>Lignes de la Commande</h5>
                    @if($supplierOrder->modifiable)
                        <button type="button" class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#addLineModal">
                            <i class="bi bi-plus-lg me-1"></i> Ajouter une ligne
                        </button>
                    @endif
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%">Num.</th>
                                <th>Équipement</th>
                                <th class="text-center">Quantité cmdée</th>
                                <th class="text-center">Quantité livrée</th>
                                <th class="text-end">PU. HT Net</th>
                                <th class="text-end">Total HT</th>
                                <th>Date Liv.</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($supplierOrder->lines as $line)
                                <tr>
                                    <td><span class="fw-bold text-muted">#{{ $line->position ?? '-' }}</span></td>
                                    <td>
                                        @if ($line->equipment)
                                            <a href="{{ route('equipments.show', $line->equipment) }}" class="fw-bold text-dark text-decoration-none">
                                                {{ $line->equipment->name }}
                                            </a>
                                            <div class="text-muted small">Réf: <code>{{ $line->equipment->reference }}</code></div>
                                        @else
                                            <span class="text-danger fw-semibold">Équipement inconnu</span>
                                        @endif
                                        @if($line->notes)
                                            <div class="text-warning small fst-italic mt-1"><i class="bi bi-chat-left-text me-1"></i>{{ $line->notes }}</div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary px-3 fs-6">{{ number_format($line->quantity, 0, ',', ' ') }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($line->quantity_delivered !== null)
                                            @php
                                                $pct = ($line->quantity > 0) ? round(($line->quantity_delivered / $line->quantity) * 100) : null;
                                                $badgeClass = ($pct !== null && $pct >= 100) ? 'bg-success' : (($pct !== null && $pct > 0) ? 'bg-warning text-dark' : 'bg-secondary');
                                            @endphp
                                            <span class="badge {{ $badgeClass }} px-3 fs-6">
                                                {{ number_format($line->quantity_delivered, 0, ',', ' ') }}
                                                @if($pct !== null)<small class="ms-1">({{ $pct }}%)</small>@endif
                                            </span>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-semibold text-dark">{{ number_format($line->unit_price_ht_net, 2, ',', ' ') }}</td>
                                    <td class="text-end fw-bold text-primary">{{ number_format($line->total_price_ht, 2, ',', ' ') }}</td>
                                    <td>
                                        <span class="text-secondary small">{{ $line->expected_delivery_date ? $line->expected_delivery_date->format('d/m/Y') : '-' }}</span>
                                    </td>
                                    <td class="text-end">
                                        @if($supplierOrder->modifiable)
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-secondary border-0" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editLineModal"
                                                    data-id="{{ $line->id }}"
                                                    data-name="{{ addslashes($line->equipment->name ?? 'Équipement') }}"
                                                    data-quantity="{{ $line->quantity }}"
                                                    data-qty-delivered="{{ $line->quantity_delivered }}"
                                                    data-price="{{ $line->unit_price_ht_net }}"
                                                    data-date="{{ $line->expected_delivery_date ? $line->expected_delivery_date->format('Y-m-d') : '' }}"
                                                    data-pmpa="{{ $line->pmpa }}"
                                                    data-last-price="{{ $line->last_purchase_price }}"
                                                    data-mvt="{{ $line->movement_type }}"
                                                    data-notes="{{ $line->notes }}">
                                                    <i class="bi bi-pencil text-warning"></i>
                                                </button>
                                                <form action="{{ route('supplier-orders.removeLine', [$supplierOrder->id, $line->id]) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Retirer cette ligne?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-muted small"><i class="bi bi-lock-fill"></i></span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-box fs-1 opacity-25 mb-3"></i>
                                            <p>Aucune ligne dans cette commande fournisseur</p>
                                        </div>
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

@if($supplierOrder->modifiable)
<!-- Modal Add Line -->
<div class="modal fade" id="addLineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('supplier-orders.addLine', $supplierOrder->id) }}" method="POST">
            @csrf
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title fw-bold text-white"><i class="bi bi-plus-circle me-2 text-white"></i>Ajouter un équipement</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Rechercher l'équipement</label>
                        <select name="equipment_id" id="select2-add-equipment" class="form-select" required></select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Quantité commandée</label>
                            <input type="number" name="quantity" id="add-line-quantity" class="form-control" value="1" min="0.001" step="any" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Quantité livrée <small class="text-muted">(optionnel, &le; Qté cmd.)</small></label>
                            <input type="number" name="quantity_delivered" id="add-line-qty-delivered" class="form-control" min="0" step="any" placeholder="≤ Qté commandée">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">PU. HT Net</label>
                            <input type="number" name="unit_price_ht_net" class="form-control" value="0.00" min="0" step="any" required>
                        </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Date de Livraison</label>
                            <input type="date" name="expected_delivery_date" class="form-control" value="{{ $supplierOrder->date_livraison ? $supplierOrder->date_livraison->format('Y-m-d') : '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Type Mouvement</label>
                            <input type="text" name="movement_type" class="form-control" value="Achat" placeholder="Achat, Vente...">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">PMPA</label>
                            <input type="number" name="pmpa" class="form-control" min="0" step="any" placeholder="Optionnel">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Dernier Prix Achat</label>
                            <input type="number" name="last_purchase_price" class="form-control" min="0" step="any" placeholder="Optionnel">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Notes</label>
                        <input type="text" name="notes" class="form-control" placeholder="Observations spécifiques pour cette ligne">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Ajouter</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Line -->
<div class="modal fade" id="editLineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editLineForm" method="POST">
            @csrf
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-warning text-dark border-0">
                    <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Modifier : <span id="edit-line-equip-name"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Quantité commandée</label>
                            <input type="number" name="quantity" id="edit-line-quantity" class="form-control" min="0.001" step="any" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Quantité livrée <small class="text-muted">(optionnel, &le; Qté cmd.)</small></label>
                            <input type="number" name="quantity_delivered" id="edit-line-qty-delivered" class="form-control" min="0" step="any" placeholder="≤ Qté commandée">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">PU. HT Net</label>
                            <input type="number" name="unit_price_ht_net" id="edit-line-price" class="form-control" min="0" step="any" required>
                        </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Date de Livraison</label>
                            <input type="date" name="expected_delivery_date" id="edit-line-date" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Type Mouvement</label>
                            <input type="text" name="movement_type" id="edit-line-mvt" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">PMPA</label>
                            <input type="number" name="pmpa" id="edit-line-pmpa" class="form-control" min="0" step="any">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Dernier Prix Achat</label>
                            <input type="number" name="last_purchase_price" id="edit-line-last-price" class="form-control" min="0" step="any">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Notes</label>
                        <input type="text" name="notes" id="edit-line-notes" class="form-control">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning px-4 fw-bold text-dark">Mettre à jour</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
@endsection

@section('scripts')
@if($supplierOrder->modifiable)
<script>
    $(document).ready(function() {
        // Init Select2 for add equipment lookup
        $('#select2-add-equipment').select2({
            theme: 'bootstrap-5',
            width: '100%',
            dropdownParent: $('#addLineModal'),
            placeholder: "Rechercher un équipement...",
            minimumInputLength: 2,
            ajax: {
                url: "{{ route('equipments.search') }}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return { q: params.term };
                },
                processResults: function(data) {
                    return {
                        results: data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.name + (item.reference ? ' (' + item.reference + ')' : '')
                            };
                        })
                    };
                },
                cache: true
            }
        });

        // Set action url and field values on edit modal open
        const editModal = document.getElementById('editLineModal');
        if (editModal) {
            editModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const quantity = button.getAttribute('data-quantity');
                const qtyDelivered = button.getAttribute('data-qty-delivered');
                const price = button.getAttribute('data-price');
                const date = button.getAttribute('data-date');
                const pmpa = button.getAttribute('data-pmpa');
                const lastPrice = button.getAttribute('data-last-price');
                const mvt = button.getAttribute('data-mvt');
                const notes = button.getAttribute('data-notes');

                document.getElementById('edit-line-equip-name').textContent = name;
                document.getElementById('edit-line-quantity').value = quantity;
                document.getElementById('edit-line-price').value = price;
                document.getElementById('edit-line-date').value = date;
                document.getElementById('edit-line-pmpa').value = pmpa;
                document.getElementById('edit-line-last-price').value = lastPrice;
                document.getElementById('edit-line-mvt').value = mvt;
                document.getElementById('edit-line-notes').value = notes;

                const qtyDeliveredInput = document.getElementById('edit-line-qty-delivered');
                if (qtyDeliveredInput) {
                    qtyDeliveredInput.value = qtyDelivered || '';
                    if (quantity && parseFloat(quantity) > 0) {
                        qtyDeliveredInput.max = quantity;
                    }
                }

                const form = document.getElementById('editLineForm');
                form.action = `{{ url('supplier-orders') }}/{{ $supplierOrder->id }}/lines/${id}`;
            });
        }

        // Dynamic max on add-line modal: update qty_delivered max when quantity changes
        const addQtyInput = document.getElementById('add-line-quantity');
        const addQtyDeliveredInput = document.getElementById('add-line-qty-delivered');
        if (addQtyInput && addQtyDeliveredInput) {
            addQtyInput.addEventListener('input', function() {
                const val = parseFloat(this.value);
                if (!isNaN(val) && val > 0) {
                    addQtyDeliveredInput.max = val;
                    const curDelivered = parseFloat(addQtyDeliveredInput.value);
                    if (!isNaN(curDelivered) && curDelivered > val) {
                        addQtyDeliveredInput.value = val;
                    }
                } else {
                    addQtyDeliveredInput.removeAttribute('max');
                }
            });
        }

        // Dynamic max on edit-line modal
        const editQtyInput = document.getElementById('edit-line-quantity');
        const editQtyDeliveredInput = document.getElementById('edit-line-qty-delivered');
        if (editQtyInput && editQtyDeliveredInput) {
            editQtyInput.addEventListener('input', function() {
                const val = parseFloat(this.value);
                if (!isNaN(val) && val > 0) {
                    editQtyDeliveredInput.max = val;
                    const curDelivered = parseFloat(editQtyDeliveredInput.value);
                    if (!isNaN(curDelivered) && curDelivered > val) {
                        editQtyDeliveredInput.value = val;
                    }
                } else {
                    editQtyDeliveredInput.removeAttribute('max');
                }
            });
        }
    });
</script>
@endif
@endsection
