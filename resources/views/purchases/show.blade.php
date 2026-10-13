@extends('layouts.app')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">
            {{ $purchase->reference }}
        </h1>
    </div>
@endsection

@section('page-actions')
    <a href="{{ route('purchases.edit', $purchase) }}" class="btn btn-warning">
        <i class="bi bi-pencil-square me-1"></i> {{ __('locale.edit') ?? 'Modifier' }}
    </a>
@endsection

@section('content')
<div class="container-xxl flex-grow-1">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Purchase Info -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white border-0">
                    <h5 class="mb-0 text-white"><i class="bi bi-info-circle me-2 text-white"></i>{{ __('locale.lbl_information') ?? 'Détails de l\'Achat' }}</h5>
                </div>
                <div class="card-body pt-4">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <span class="fw-bold d-block text-muted small text-uppercase">{{ __('locale.lbl_reference') ?? 'Référence' }}</span>
                            <span class="fs-5 fw-bold">{{ $purchase->reference }}</span>
                        </li>
                        <li class="mb-3">
                            <span class="fw-bold d-block text-muted small text-uppercase">{{ __('locale.lbl_supplier') ?? 'Fournisseur' }}</span>
                            @if($purchase->supplier)
                                <a href="{{ route('suppliers.show', $purchase->supplier) }}" class="fw-bold fs-5">{{ $purchase->supplier->name }}</a>
                                <span class="badge bg-label-primary ms-2">{{ $purchase->supplier->type }}</span>
                            @else
                                <span class="text-danger">Fournisseur inconnu</span>
                            @endif
                        </li>
                        <li class="mb-3">
                            <span class="fw-bold d-block text-muted small text-uppercase">{{ __('locale.lbl_status') ?? 'Statut' }}</span>
                            @if($purchase->status === 'pending')
                                <span class="badge bg-warning px-3 py-2 fs-6"><i class="bi bi-clock me-2"></i>{{ __('locale.pending') ?? 'En attente' }}</span>
                            @elseif($purchase->status === 'received')
                                <span class="badge bg-success px-3 py-2 fs-6"><i class="bi bi-check-circle me-2"></i>{{ __('locale.received') ?? 'Reçu' }}</span>
                            @else
                                <span class="badge bg-danger px-3 py-2 fs-6"><i class="bi bi-x-circle me-2"></i>{{ __('locale.cancelled') ?? 'Annulé' }}</span>
                            @endif
                        </li>
                        <li class="mb-3">
                            <span class="fw-bold d-block text-muted small text-uppercase">{{ __('locale.expected_delivery') ?? 'Livraison Prévue' }}</span>
                            <span class="fs-5 text-primary fw-bold">{{ $purchase->expected_delivery_date ? $purchase->expected_delivery_date->format('d/m/Y') : '-' }}</span>
                        </li>
                        <li>
                            <span class="fw-bold d-block text-muted small text-uppercase">{{ __('locale.lbl_notes') ?? 'Notes' }}</span>
                            <span>{!! nl2br(e($purchase->notes ?? '-')) !!}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Purchase Items -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0 animate__animated animate__fadeIn">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('locale.purchase_items') ?? 'Équipements Commandés' }}</h5>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addEquipmentModal">
                        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_add_equipment') ?? 'Ajouter un équipement' }}
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light text-uppercase small text-muted fw-bold">
                            <tr>
                                <th>{{ __('locale.lbl_equipment') ?? 'Équipement' }}</th>
                                <th>{{ __('locale.lbl_reference') ?? 'Référence' }}</th>
                                <th class="text-center">{{ __('locale.lbl_quantity') ?? 'Quantité' }}</th>
                                <th>{{ __('locale.expected_delivery') ?? 'Livraison' }}</th>
                                <th class="text-end">{{ __('locale.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($purchase->equipments as $equipment)
                                <tr>
                                    <td>
                                        <a href="{{ route('equipments.show', $equipment) }}" class="fw-bold text-dark text-decoration-none">
                                            {{ $equipment->name }}
                                        </a>
                                    </td>
                                    <td><code class="small">{{ $equipment->reference }}</code></td>
                                    <td class="text-center">
                                        <span class="badge bg-primary px-3 fs-6">{{ $equipment->pivot->quantity }}</span>
                                    </td>
                                    <td>
                                        @if($equipment->pivot->expected_delivery_date)
                                            <span class="text-primary">{{ \Carbon\Carbon::parse($equipment->pivot->expected_delivery_date)->format('d/m/Y') }}</span>
                                        @else
                                            <span class="text-muted small">IDEM ACHAT</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group shadow-none">
                                            <button type="button" class="btn btn-sm btn-outline-secondary border-0" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editEquipmentModal"
                                                data-id="{{ $equipment->id }}"
                                                data-name="{{ addslashes($equipment->name) }}"
                                                data-quantity="{{ $equipment->pivot->quantity }}"
                                                data-date="{{ $equipment->pivot->expected_delivery_date ? \Carbon\Carbon::parse($equipment->pivot->expected_delivery_date)->format('Y-m-d') : '' }}">
                                                <i class="bi bi-pencil text-warning"></i>
                                            </button>
                                            <form action="{{ route('purchases.removeEquipment', [$purchase->id, $equipment->id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Retirer cet équipement ?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-box fs-1 opacity-25 mb-3"></i>
                                            <p>{{ __('locale.lbl_no_records_found') }}</p>
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

<!-- Modal Add Equipment -->
<div class="modal fade" id="addEquipmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('purchases.addEquipment', $purchase->id) }}" method="POST">
            @csrf
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title fw-bold text-white"><i class="bi bi-plus-circle me-2 text-white"></i>{{ __('locale.btn_add_equipment') ?? 'Ajouter un équipement' }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('locale.search_equipment') ?? 'Rechercher l\'équipement' }}</label>
                        <select name="equipment_id" id="select2-add-equipment" class="form-select" required></select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('locale.lbl_quantity') ?? 'Quantité' }}</label>
                            <input type="number" name="quantity" class="form-control" value="1" min="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('locale.expected_delivery') ?? 'Livraison Prévue' }}</label>
                            <input type="date" name="expected_delivery_date" class="form-control" value="{{ $purchase->expected_delivery_date ? $purchase->expected_delivery_date->format('Y-m-d') : '' }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">{{ __('locale.btn_cancel') ?? 'Annuler' }}</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">{{ __('locale.btn_save') ?? 'Ajouter' }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Equipment -->
<div class="modal fade" id="editEquipmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editEquipmentForm" method="POST">
            @csrf
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-warning text-dark border-0">
                    <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Modifier : <span id="edit-equip-name"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('locale.lbl_quantity') ?? 'Quantité' }}</label>
                            <input type="number" name="quantity" id="edit-equip-quantity" class="form-control" min="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('locale.expected_delivery') ?? 'Livraison' }}</label>
                            <input type="date" name="expected_delivery_date" id="edit-equip-date" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">{{ __('locale.btn_cancel') ?? 'Annuler' }}</button>
                    <button type="submit" class="btn btn-warning px-4 fw-bold">{{ __('locale.btn_update') ?? 'Mettre à jour' }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Init Select2 for add modal
        $('#select2-add-equipment').select2({
            theme: 'bootstrap-5',
            width: '100%',
            dropdownParent: $('#addEquipmentModal'),
            placeholder: "{{ __('locale.search_equipment_placeholder') ?? 'Rechercher un équipement...' }}",
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

        // Helper to add weekdays to a date
        function addWeekdays(date, days) {
            let result = new Date(date);
            let addedDays = 0;
            while (addedDays < days) {
                result.setDate(result.getDate() + 1);
                let dayOfWeek = result.getDay();
                if (dayOfWeek !== 0 && dayOfWeek !== 6) { // Not Sunday or Saturday
                    addedDays++;
                }
            }
            return result;
        }

        // Logic to update delivery date based on lead time
        function updateDeliveryDate(modal, equipmentId, quantity) {
            if (!equipmentId || !quantity) return;
            
            const dateInput = modal.find('input[type="date"]');
            
            $.ajax({
                url: `/equipments/${equipmentId}/lead-time`,
                data: { quantity: quantity, supplier_id: '{{ $purchase->supplier_id }}' },
                success: function(response) {
                    const leadTimeDays = response.lead_time_days;
                    const calculatedDate = addWeekdays(new Date(), leadTimeDays);
                    
                    // Format to YYYY-MM-DD for date input
                    const yyyy = calculatedDate.getFullYear();
                    const mm = String(calculatedDate.getMonth() + 1).padStart(2, '0');
                    const dd = String(calculatedDate.getDate()).padStart(2, '0');
                    
                    dateInput.val(`${yyyy}-${mm}-${dd}`);
                }
            });
        }

        // Add modal listeners
        $('#select2-add-equipment').on('change', function() {
            const equipmentId = $(this).val();
            const quantity = $('#addEquipmentModal').find('input[name="quantity"]').val();
            updateDeliveryDate($('#addEquipmentModal'), equipmentId, quantity);
        });

        $('#addEquipmentModal').find('input[name="quantity"]').on('input', function() {
            const equipmentId = $('#select2-add-equipment').val();
            const quantity = $(this).val();
            updateDeliveryDate($('#addEquipmentModal'), equipmentId, quantity);
        });

        // Edit modal listeners
        $('#edit-equip-quantity').on('input', function() {
            const form = $(this).closest('form');
            const action = form.attr('action');
            // Extract equipment ID from action URL: /purchases/{purchase}/equipments/{equipment}
            const parts = action.split('/');
            const equipmentId = parts[parts.length - 1];
            const quantity = $(this).val();
            updateDeliveryDate($('#editEquipmentModal'), equipmentId, quantity);
        });

        // Handle edit modal population
        const editEquipmentModal = document.getElementById('editEquipmentModal');
        if (editEquipmentModal) {
            editEquipmentModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const quantity = button.getAttribute('data-quantity');
                const date = button.getAttribute('data-date');

                const form = editEquipmentModal.querySelector('#editEquipmentForm');
                form.action = `{{ url('purchases') }}/{{ $purchase->id }}/equipments/${id}`;
                
                editEquipmentModal.querySelector('#edit-equip-name').textContent = name;
                editEquipmentModal.querySelector('#edit-equip-quantity').value = quantity;
                editEquipmentModal.querySelector('#edit-equip-date').value = date;
            });
        }
    });
</script>
@endsection
