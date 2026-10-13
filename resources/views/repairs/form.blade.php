@php
    $equipmentTypesCollection = (isset($equipmentTypes) && count($equipmentTypes)) ? collect($equipmentTypes) : \App\Models\EquipmentType::orderBy('name')->get(['id', 'name']);
    $brandsCollection = (isset($brands) && count($brands)) ? collect($brands) : \App\Models\Brand::orderBy('name')->get(['id', 'name']);
    $equipmentsCollection = (isset($equipments) && count($equipments)) ? collect($equipments) : \App\Models\Equipment::orderBy('name')->get(['id', 'name', 'reference', 'equipment_type_id', 'brand_id', 'price_per_unit']);
    $suppliersCollection = (isset($suppliers) && count($suppliers)) ? collect($suppliers) : \App\Models\Supplier::orderBy('name')->get(['id', 'name']);
    $statusesCollection = (isset($statuses) && count($statuses)) ? collect($statuses) : \App\Models\Status::where('module', 'repair')->orderBy('name')->get();
    $warrantyTypesCollection = (isset($warrantyTypes) && count($warrantyTypes)) ? collect($warrantyTypes) : \App\Models\WarrantyType::where('is_active', true)->orderBy('order')->orderBy('name')->get(['id', 'name', 'code', 'color']);
    $repairDiagnosesCollection = (isset($repairDiagnoses) && count($repairDiagnoses)) ? collect($repairDiagnoses) : \App\Models\RepairDiagnosis::where('is_active', true)->orderBy('order')->orderBy('name')->get(['id', 'name', 'code', 'color']);
    $issuesCollection = isset($issues) ? collect($issues) : collect();

    $selectedIssueId = old('issue_id', $repair->issue_id ?? null);
    $selectedIssue = $selectedIssueId ? $issuesCollection->firstWhere('id', (int) $selectedIssueId) : null;
    $selectedIssueSubject = $selectedIssue ? ($selectedIssue->subject ?? __('locale.lbl_no_title')) : null;
    $selectedIssueLabel = $selectedIssue
        ? sprintf('#%s%s%s', $selectedIssue->id, $selectedIssueSubject ? ' - ' . $selectedIssueSubject : '', $selectedIssue->client ? ' (' . $selectedIssue->client->raison_sociale . ')' : '')
        : '';

    $issueSearchValue = old('issue_search', $selectedIssueLabel);

    $selectedClientId = old('client_id', $repair->client_id ?? ($selectedIssue?->client_id ?? null));
    $selectedClient = $selectedClientId ? \App\Models\Client::find($selectedClientId) : null;
    $clientSearchValue = old('client_search', $selectedClient ? $selectedClient->raison_sociale : '');

    $hasInternalTicket = old('ticket_mode', $selectedIssueId ? 'internal' : ($repair->id && !$repair->issue_id ? 'external' : 'internal')) === 'internal';

    $preparedEquipmentTypes = $equipmentTypesCollection->map(fn ($type) => [
        'id' => $type->id,
        'name' => $type->name,
    ])->values();

    $preparedBrands = $brandsCollection->map(fn ($brand) => [
        'id' => $brand->id,
        'name' => $brand->name,
    ])->values();

    $preparedSuppliers = $suppliersCollection->map(fn ($supplier) => [
        'id' => $supplier->id,
        'name' => $supplier->name,
    ])->values();

    $preparedWarrantyTypes = $warrantyTypesCollection->map(fn ($wt) => [
        'id' => $wt->id,
        'name' => $wt->name,
        'code' => $wt->code,
        'color' => $wt->color,
    ])->values();

    $preparedRepairDiagnoses = $repairDiagnosesCollection->map(fn ($rd) => [
        'id' => $rd->id,
        'name' => $rd->name,
        'code' => $rd->code,
        'color' => $rd->color,
    ])->values();

    $preparedEquipments = $equipmentsCollection->map(fn ($equipment) => [
        'id' => $equipment->id,
        'name' => $equipment->name,
        'reference' => $equipment->reference,
        'equipment_type_id' => $equipment->equipment_type_id,
        'brand_id' => $equipment->brand_id,
        'price_per_unit' => $equipment->price_per_unit,
    ])->values();

    $selectedEquipments = collect(old('equipment_data', []))->map(function ($item) {
        return [
            'equipment_type_id' => $item['equipment_type_id'] ?? null,
            'brand_id' => $item['brand_id'] ?? null,
            'equipment_id' => $item['equipment_id'] ?? null,
            'client_equipment_id' => $item['client_equipment_id'] ?? null,
            'serial_number' => $item['serial_number'] ?? null,
            'arrival_date' => $item['arrival_date'] ?? null,
            'warranty_type_id' => $item['warranty_type_id'] ?? null,
            'warranty_type' => $item['warranty_type'] ?? null,
            'supplier_dispatch_date' => $item['supplier_dispatch_date'] ?? null,
            'supplier_id' => $item['supplier_id'] ?? null,
            'recipient_name' => $item['recipient_name'] ?? null,
            'tracking_number' => $item['tracking_number'] ?? null,
            'quote_number' => $item['quote_number'] ?? null,
            'quote_amount' => $item['quote_amount'] ?? null,
            'quote_sent_date' => $item['quote_sent_date'] ?? null,
            'quote_signed' => isset($item['quote_signed']) ? (string)$item['quote_signed'] : null,
            'quote_signed_date' => $item['quote_signed_date'] ?? null,
            'supplier_return_date' => $item['supplier_return_date'] ?? null,
            'repair_diagnosis_id' => $item['repair_diagnosis_id'] ?? ($item['repair_status_id'] ?? null),
            'repair_status' => $item['repair_status'] ?? null,
            'customer_delivery_date' => $item['customer_delivery_date'] ?? null,
            'odoo_delivery_ref' => $item['odoo_delivery_ref'] ?? null,
            'amen_replaced_serial' => $item['amen_replaced_serial'] ?? null,
            'amen_sent_date' => $item['amen_sent_date'] ?? null,
            'loan_serial_number' => $item['loan_serial_number'] ?? null,
            'loan_sent_date' => $item['loan_sent_date'] ?? null,
            'comments' => $item['comments'] ?? null,
            'description' => $item['description'] ?? null,
            'price' => $item['price'] ?? null,
        ];
    })->values();

    if ($selectedEquipments->isEmpty() && isset($repair)) {
        $selectedEquipments = collect($repair->equipmentRepairs ?? [])->map(function ($equipmentRepair) {
            $equipment = $equipmentRepair->equipment;

            return [
                'equipment_type_id' => $equipment->equipment_type_id ?? null,
                'brand_id' => $equipment->brand_id ?? null,
                'equipment_id' => $equipment->id ?? null,
                'client_equipment_id' => $equipmentRepair->client_equipment_id,
                'serial_number' => $equipmentRepair->serial_number,
                'arrival_date' => $equipmentRepair->arrival_date ? $equipmentRepair->arrival_date->format('d/m/Y') : null,
                'warranty_type_id' => $equipmentRepair->warranty_type_id,
                'warranty_type' => $equipmentRepair->warranty_type,
                'supplier_dispatch_date' => $equipmentRepair->supplier_dispatch_date ? $equipmentRepair->supplier_dispatch_date->format('d/m/Y') : null,
                'supplier_id' => $equipmentRepair->supplier_id,
                'recipient_name' => $equipmentRepair->recipient_name,
                'tracking_number' => $equipmentRepair->tracking_number,
                'quote_number' => $equipmentRepair->quote_number,
                'quote_amount' => $equipmentRepair->quote_amount,
                'quote_sent_date' => $equipmentRepair->quote_sent_date ? $equipmentRepair->quote_sent_date->format('d/m/Y') : null,
                'quote_signed' => $equipmentRepair->quote_signed !== null ? ($equipmentRepair->quote_signed ? '1' : '0') : null,
                'quote_signed_date' => $equipmentRepair->quote_signed_date ? $equipmentRepair->quote_signed_date->format('d/m/Y') : null,
                'supplier_return_date' => $equipmentRepair->supplier_return_date ? $equipmentRepair->supplier_return_date->format('d/m/Y') : null,
                'repair_diagnosis_id' => $equipmentRepair->repair_diagnosis_id,
                'repair_status' => $equipmentRepair->repair_status,
                'customer_delivery_date' => $equipmentRepair->customer_delivery_date ? $equipmentRepair->customer_delivery_date->format('d/m/Y') : null,
                'odoo_delivery_ref' => $equipmentRepair->odoo_delivery_ref,
                'amen_replaced_serial' => $equipmentRepair->amen_replaced_serial,
                'amen_sent_date' => $equipmentRepair->amen_sent_date ? $equipmentRepair->amen_sent_date->format('d/m/Y') : null,
                'loan_serial_number' => $equipmentRepair->loan_serial_number,
                'loan_sent_date' => $equipmentRepair->loan_sent_date ? $equipmentRepair->loan_sent_date->format('d/m/Y') : null,
                'comments' => $equipmentRepair->comments,
                'description' => $equipmentRepair->description,
                'price' => $equipmentRepair->price,
            ];
        })->values();
    }

    $equipmentLabels = [
        'deviceTitle' => __('locale.equipment_device_title'),
        'type' => __('locale.lbl_equipment_type'),
        'brand' => __('locale.lbl_brand'),
        'model' => __('locale.lbl_model'),
        'serial' => __('locale.serial_number'),
        'description' => __('locale.description'),
        'remove' => __('locale.delete'),
        'selectType' => __('locale.select_equipment_type'),
        'selectBrand' => __('locale.select_brand'),
        'selectModel' => __('locale.select_equipment'),
        'selectSupplier' => __('locale.select_supplier'),
        'arrivalDate' => __('locale.lbl_arrival_date'),
        'warranty' => __('locale.lbl_warranty'),
        'underWarranty' => __('locale.sav_under_warranty'),
        'outOfWarranty' => __('locale.sav_out_of_warranty'),
        'amenContract' => __('locale.sav_amen_contract'),
        'supplier' => __('locale.lbl_supplier'),
        'recipient' => __('locale.lbl_recipient_name'),
        'tracking' => __('locale.lbl_tracking_number'),
        'quoteNumber' => __('locale.lbl_quote_number'),
        'quoteAmount' => __('locale.lbl_quote_amount'),
        'quoteSentDate' => __('locale.lbl_quote_sent_date'),
        'quoteSigned' => __('locale.lbl_quote_signed'),
        'quoteSignedDate' => __('locale.lbl_quote_signed_date'),
        'supplierDispatchDate' => __('locale.lbl_supplier_dispatch_date'),
        'supplierReturnDate' => __('locale.lbl_supplier_return_date'),
        'repairStatus' => __('locale.lbl_repair_status'),
        'repaired' => __('locale.sav_repaired'),
        'unrepairable' => __('locale.sav_unrepairable'),
        'replacedNew' => __('locale.sav_replaced_new'),
        'pending' => __('locale.sav_pending'),
        'customerDeliveryDate' => __('locale.lbl_customer_delivery_date'),
        'odooDeliveryRef' => __('locale.lbl_odoo_delivery_ref'),
        'amenReplacedSerial' => __('locale.lbl_amen_replaced_serial'),
        'amenSentDate' => __('locale.lbl_amen_sent_date'),
        'loanSerial' => __('locale.lbl_loan_serial_number'),
        'loanDate' => __('locale.lbl_loan_sent_date'),
        'comments' => __('locale.comments'),
        'parkEquipment' => __('locale.lbl_client_park_equipment'),
        'selectParkEquipment' => __('locale.lbl_select_park_equipment'),
    ];

    $selectedStatusIdValue = old('status_id', $repair->status_id ?? $statusesCollection->first()?->id);
@endphp

<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-folder2-open me-2"></i>Informations du Dossier SAV</h6>

        <!-- Ticket Mode Selection -->
        <div class="mb-3">
            <label class="form-label fw-semibold small">Mode de rattachement</label>
            <div class="d-flex gap-4">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="ticket_mode" id="mode_internal" value="internal" {{ $hasInternalTicket ? 'checked' : '' }}>
                    <label class="form-check-label" for="mode_internal">
                        <i class="bi bi-ticket-detailed me-1"></i> Avec Ticket interne Novalyo 360
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="ticket_mode" id="mode_external" value="external" {{ !$hasInternalTicket ? 'checked' : '' }}>
                    <label class="form-check-label" for="mode_external">
                        <i class="bi bi-building me-1"></i> Sans ticket interne (Client direct & Ticket externe)
                    </label>
                </div>
            </div>
        </div>

        <!-- Block Internal Ticket -->
        <div id="block_internal_ticket" class="{{ $hasInternalTicket ? '' : 'd-none' }}">
            <div class="mb-3">
                <label for="repair_issue_search" class="form-label small fw-semibold">{{ __('locale.lbl_issue') }} <span class="text-danger">*</span></label>
                <div class="d-flex flex-column flex-md-row gap-2 align-items-stretch">
                    <div class="position-relative flex-grow-1" data-autocomplete-container>
                        <input
                            type="text"
                            name="issue_search"
                            id="repair_issue_search"
                            class="form-control"
                            placeholder="{{ __('locale.lbl_select_issue') }}"
                            value="{{ $issueSearchValue }}"
                            autocomplete="off"
                            data-autocomplete-input
                            data-autocomplete-url="{{ route('issues.search') }}"
                            data-autocomplete-hidden-input="#repair_issue_id"
                            data-autocomplete-label-key="subject"
                            data-autocomplete-value-key="id"
                            data-autocomplete-show-id="true"
                            data-autocomplete-max-results="10"
                        >
                        <input type="hidden" name="issue_id" id="repair_issue_id" value="{{ $selectedIssueId }}">
                        <div
                            class="list-group position-absolute w-100 shadow-sm bg-white"
                            data-autocomplete-results
                            style="max-height: 240px; overflow-y: auto; z-index: 1050;"
                            hidden
                        ></div>
                    </div>
                    <button type="button" class="btn btn-outline-primary flex-shrink-0" data-quick-issue-open>
                        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_create_issue') }}
                    </button>
                </div>
                @error('issue_id')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Block External / Direct Client -->
        <div id="block_external_ticket" class="{{ !$hasInternalTicket ? '' : 'd-none' }}">
            <div class="row g-3 mb-3">
                <div class="col-md-7">
                    <label for="repair_client_search" class="form-label small fw-semibold">{{ __('locale.lbl_client') }} <span class="text-danger">*</span></label>
                    <div class="position-relative" data-autocomplete-container>
                        <input
                            type="text"
                            name="client_search"
                            id="repair_client_search"
                            class="form-control"
                            placeholder="{{ __('locale.search_client_placeholder') }}"
                            value="{{ $clientSearchValue }}"
                            autocomplete="off"
                            data-autocomplete-input
                            data-autocomplete-url="{{ route('clients.search') }}"
                            data-autocomplete-hidden-input="#repair_client_id"
                            data-autocomplete-label-key="raison_sociale"
                            data-autocomplete-value-key="id"
                            data-autocomplete-max-results="10"
                        >
                        <input type="hidden" name="client_id" id="repair_client_id" value="{{ $selectedClientId }}">
                        <div
                            class="list-group position-absolute w-100 shadow-sm bg-white"
                            data-autocomplete-results
                            style="max-height: 240px; overflow-y: auto; z-index: 1050;"
                            hidden
                        ></div>
                    </div>
                    @error('client_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-5">
                    <label for="external_ticket_number" class="form-label small fw-semibold">{{ __('locale.lbl_external_ticket') }}</label>
                    <input
                        type="text"
                        name="external_ticket_number"
                        id="external_ticket_number"
                        class="form-control"
                        placeholder="{{ __('locale.lbl_external_ticket_placeholder') }}"
                        value="{{ old('external_ticket_number', $repair->external_ticket_number ?? '') }}"
                    >
                    @error('external_ticket_number')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label for="status_id" class="form-label small fw-semibold">{{ __('locale.lbl_status') }} <span class="text-danger">*</span></label>
                <select name="status_id" id="status_id" class="form-select select2" {{ $statusesCollection->isEmpty() ? 'disabled' : '' }}>
                    @foreach($statusesCollection as $status)
                        <option value="{{ $status->id }}" {{ (string) $selectedStatusIdValue === (string) $status->id ? 'selected' : '' }}>
                            {{ $status->translated_name ?? $status->name }}
                        </option>
                    @endforeach
                </select>
                @error('status_id')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-8">
                <label for="repair_notes" class="form-label small fw-semibold">Notes générales du dossier SAV</label>
                <input type="text" name="notes" id="repair_notes" class="form-control" placeholder="Observations globales..." value="{{ old('notes', $repair->notes ?? '') }}">
            </div>
        </div>
    </div>
</div>

<div class="d-flex align-items-center justify-content-between mb-3">
    <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-tools text-primary me-2"></i>{{ __('locale.menu_equipments') }} / Suivi SAV par matériel</h5>
    <span class="badge bg-light text-muted border">Multi-numéros de série gérés</span>
</div>

<script type="application/json" id="equipment-form-data">
{!! json_encode([
    'types' => $preparedEquipmentTypes,
    'brands' => $preparedBrands,
    'suppliers' => $preparedSuppliers,
    'warrantyTypes' => $preparedWarrantyTypes,
    'repairDiagnoses' => $preparedRepairDiagnoses,
    'equipments' => $preparedEquipments,
    'selected' => $selectedEquipments,
    'labels' => $equipmentLabels,
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) !!}
</script>

<div class="text-danger small mb-3" id="equipment-error-messages">
    @if ($errors->has('equipment_data'))
        <div>{{ $errors->first('equipment_data') }}</div>
    @endif
    @foreach (['equipment_id', 'serial_number', 'arrival_date', 'warranty_type', 'supplier_id', 'quote_number', 'repair_status'] as $field)
        @if ($errors->has('equipment_data.*.' . $field))
            <div>{{ $errors->first('equipment_data.*.' . $field) }}</div>
        @endif
    @endforeach
</div>

<div id="equipment-items"></div>

<div class="mt-3">
    <button type="button" class="btn btn-outline-primary" id="addEquipmentButton">
        <i class="bi bi-plus-circle me-1"></i> {{ __('locale.add_equipment') }} (Nouveau matériel SAV)
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const radioInternal = document.getElementById('mode_internal');
    const radioExternal = document.getElementById('mode_external');
    const blockInternal = document.getElementById('block_internal_ticket');
    const blockExternal = document.getElementById('block_external_ticket');
    const issueInput = document.getElementById('repair_issue_id');
    const clientInput = document.getElementById('repair_client_id');

    const toggleTicketMode = () => {
        if (radioInternal.checked) {
            blockInternal.classList.remove('d-none');
            blockExternal.classList.add('d-none');
        } else {
            blockInternal.classList.add('d-none');
            blockExternal.classList.remove('d-none');
            if (issueInput) {
                issueInput.value = '';
                const searchField = document.getElementById('repair_issue_search');
                if (searchField) searchField.value = '';
            }
        }
    };

    if (radioInternal && radioExternal) {
        radioInternal.addEventListener('change', toggleTicketMode);
        radioExternal.addEventListener('change', toggleTicketMode);
    }
});
</script>