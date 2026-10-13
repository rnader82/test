@extends('layouts.app')

@section('content')
<style>
    .form_title {
        background-color: var(--bs-purple) !important;
    }
    .address-color{
        color: var(--bs-purple) !important;
    }
    .repair-print .print-area {
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        padding: 2rem;
        max-width: 297mm;
        min-height: 210mm;
        box-shadow: 0 0 25px rgba(0, 0, 0, 0.05);
    }

    .repair-print .print-logo {
        max-width: 300px;
        height: auto;
    }

    .repair-print .info-value {
        display: block;
        padding: 0.5rem 0.75rem;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        background-color: #f8f9fa;
        min-height: 2.5rem;
        font-size: 0.95rem;
        white-space: pre-wrap;
    }

    .repair-print .print-header h1 {
        font-size: 1.5rem;
        letter-spacing: 0.05em;
    }

    .repair-print .print-table {
        width: 100%;
        border-collapse: collapse;
    }

    .repair-print .print-table td {
        padding: 0;
        border: none;
        vertical-align: top;
    }

    .repair-equipment-table input[type="checkbox"] {
        width: 1.1rem;
        height: 1.1rem;
        cursor: pointer;
    }

    .repair-equipment-table input[type="text"],
    .repair-print textarea,
    .repair-print input[type="date"] {
        background-color: #fff;
    }

    .repair-equipment-table input[type="checkbox"] {
        width: 1.1rem;
        height: 1.1rem;
        cursor: pointer;
    }

    .repair-equipment-table td,
    .repair-equipment-table th {
        vertical-align: middle;
        font-size: 0.7rem;
        padding: 0.35rem 0.5rem;
        text-transform: uppercase;
    }

    .repair-equipment-table td {
        vertical-align: middle;
        font-size: 0.75rem;
        padding: 0.35rem 0.5rem;
    }

    .repair-equipment-table .static-value {
        display: block;
        padding: 0.25rem 0.5rem;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        background-color: #f8f9fa;
        min-height: 2rem;
        font-size: 0.75rem;
        line-height: 1.2;
    }

    .repair-equipment-table input[type="text"] {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    @media print {
        body {
            background: #fff !important;
        }

        .layout-menu,
        .layout-navbar,
        .layout-page > .content-wrapper > .container-xxl,
        .layout-page > footer,
        .no-print {
            display: none !important;
        }

        .layout-page {
            padding-left: 0 !important;
        }

        .repair-print .print-area {
            display: table;
            width: 100%;
        }

        .table-responsive {
            overflow: visible !important;
        }

        header.print-header {
            display: table-header-group;
        }

        main.print-body {
            display: table-row-group;
        }

        header.print-header {
            padding: 1.5rem 2rem 1rem;
            background-color: #fff;
        }

        main.print-body {
            padding: 1.5rem 2rem 2rem;
        }

        @page {
            size: A4 landscape;
            margin: 20mm;
        }
    }
</style>

@php
    $printFormData = $printFormData ?? [];
@endphp

<div class="container py-4 repair-print">
    @if (session('status'))
        <div class="alert alert-success no-print">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger no-print">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2 no-print">
        <div class="d-flex gap-2">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>
        <div id="save-indicator" class="text-muted small d-none">
            <div class="spinner-border spinner-border-sm me-1" role="status"></div>
            {{ __('locale.saving') }}...
        </div>
    </div>

    <form id="repair-print-form" class="print-area mx-auto" method="POST" action="{{ route('repairs.print-form.store', $repair) }}">
        @csrf
        <table class="print-table">
            <thead>
                <tr>
                    <td>
                        <header class="print-header">
                            <div class="d-flex justify-content-between align-items-start mb-4 gap-3">
                                <div class="text-start">
                                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="print-logo mb-2">
                                    <div class="text-uppercase fw-semibold small">Adresse unique pour le renvoi des réparations</div>
                                    <div class="small text-uppercase address-color">NOVALYO - ZAC des castors - 38150 Salaise sur sanne</div>
                                </div>
                                <h4 class="text-center text-uppercase h5 mb-0 flex-grow-1 mt-12 text-white form_title">{{ __('locale.repair_form') }}</h4>
                                <div class="text-center mt-12" style="min-width: 200px;">
                                    <label class="form-label fw-semibold text-uppercase small">{{ __('locale.lbl_date') }}</label>
                                    <input
                                        type="date"
                                        class="form-control"
                                        name="form_date"
                                        value="{{ old('form_date', $printFormData['form_date'] ?? now()->format('Y-m-d')) }}"
                                    >
                                </div>
                            </div>

                            <div class="row g-3 align-items-start">
                                <div class="col-lg-4">
                                    <label class="form-label fw-semibold text-uppercase small">{{ __('locale.lbl_client') }}</label>
                                    <span class="info-value">{{ $client?->raison_sociale }}</span>
                                    @if (filled($clientInformation))
                                        <label class="form-label fw-semibold text-uppercase small mt-3">{{ __('locale.lbl_client_information') }}</label>
                                        <span class="info-value">{{ $clientInformation }}</span>
                                    @endif
                                    <label class="form-label fw-semibold text-uppercase small mt-3">{{ __('locale.lbl_client_contact') }}</label>
                                    <span class="info-value">{{ trim(collect([$contact?->civility, $contact?->prenom, $contact?->nom])->filter()->implode(' ')) }}</span>
                                    <label class="form-label fw-semibold text-uppercase small mt-3">{{ __('locale.lbl_contact_details') }}</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="contact_details"
                                        value="{{ old('contact_details', $printFormData['contact_details'] ?? $contactDetails) }}"
                                    >
                                </div>
                                <div class="col-lg-4">
                                    <label class="form-label fw-semibold text-uppercase small">{{ __('locale.lbl_client_address') }}</label>
                                    <textarea
                                        class="form-control"
                                        rows="9"
                                        name="client_address">{{ old('client_address', $printFormData['client_address'] ?? $clientAddress) }}</textarea>
                                </div>
                                <div class="col-lg-4">
                                    <label class="form-label fw-semibold text-uppercase small">{{ __('locale.lbl_delivery_address') }}</label>
                                    <textarea
                                        class="form-control"
                                        rows="9"
                                        name="delivery_address">{{ old('delivery_address', $printFormData['delivery_address'] ?? $clientAddress) }}</textarea>
                                </div>
                            </div>
                            <h4 class="text-center text-uppercase h5 flex-grow-1 text-white form_title mt-2">{{ __('locale.lbl_repair_do_not_send') }}</h4>
                        </header>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <main class="print-body">

            <div class="table-responsive">
                <table class="table table-bordered table-sm align-middle repair-equipment-table">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="text-center">{{ __('locale.lbl_type') }}</th>
                            <th scope="col" class="text-center">{{ __('locale.serial_number') }}</th>
                            <th scope="col" class="text-center">{{ __('locale.internal_number') }}</th>
                            <th scope="col" class="text-center">{{ __('locale.lbl_name') }}</th>
                            @foreach ($equipmentChecklistFields as $label)
                                <th scope="col" class="text-center" style="width: 40px; min-width: 40px;">{{ $label }}</th>
                            @endforeach
                            <th scope="col" class="text-center">{{ __('locale.other') }}</th>
                            <th scope="col" class="text-center no-print" style="width: 80px;"></th>
                        </tr>
                    </thead>
                    <tbody id="equipment-tbody">
                        @php
                            $existingEquipmentIds = $repair->equipmentRepairs->pluck('id')->toArray();
                            $allEquipmentData = data_get($printFormData, 'equipment', []);
                            $extraEquipmentKeys = collect($allEquipmentData)->keys()->filter(fn($key) => !in_array($key, $existingEquipmentIds))->toArray();
                        @endphp

                        @foreach ($repair->equipmentRepairs as $equipmentRepair)
                            @php
                                $id = $equipmentRepair->id;
                                $equipmentData = data_get($allEquipmentData, $id, []);
                                $type = old("equipment.{$id}.type", $equipmentData['type'] ?? $equipmentRepair->equipment?->equipmentType?->name);
                                $serial = old("equipment.{$id}.serial_number", $equipmentData['serial_number'] ?? $equipmentRepair->serial_number);
                                $ref = old("equipment.{$id}.reference", $equipmentData['reference'] ?? $equipmentRepair->equipment?->reference);
                                $name = old("equipment.{$id}.name", $equipmentData['name'] ?? $equipmentRepair->equipment?->name);
                                $other = old("equipment.{$id}.other", $equipmentData['other'] ?? '');
                            @endphp
                            <tr class="equipment-row" data-id="{{ $id }}">
                                <td>
                                    <span class="display-type">{{ $type }}</span>
                                    <input type="hidden" name="equipment[{{ $id }}][type]" value="{{ $type }}">
                                </td>
                                <td>
                                    <span class="display-serial_number">{{ $serial }}</span>
                                    <input type="hidden" name="equipment[{{ $id }}][serial_number]" value="{{ $serial }}">
                                </td>
                                <td>
                                    <span class="display-reference">{{ $ref }}</span>
                                    <input type="hidden" name="equipment[{{ $id }}][reference]" value="{{ $ref }}">
                                </td>
                                <td>
                                    <span class="display-name">{{ $name }}</span>
                                    <input type="hidden" name="equipment[{{ $id }}][name]" value="{{ $name }}">
                                </td>
                                @foreach ($equipmentChecklistFields as $fieldKey => $label)
                                    @php
                                        $isChecked = old("equipment.{$id}.{$fieldKey}", $equipmentData[$fieldKey] ?? false);
                                    @endphp
                                    <td class="text-center">
                                        <i class="bi {{ (bool)$isChecked ? 'bi-check-square-fill text-success' : 'bi-square text-muted' }}"></i>
                                        <input type="hidden" name="equipment[{{ $id }}][{{ $fieldKey }}]" value="{{ (bool)$isChecked ? '1' : '0' }}">
                                    </td>
                                @endforeach
                                <td>
                                    <span class="display-other">{{ $other }}</span>
                                    <input type="hidden" name="equipment[{{ $id }}][other]" value="{{ $other }}">
                                </td>
                                <td class="no-print text-center">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary edit-equipment-row">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-equipment-row">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        @foreach ($extraEquipmentKeys as $key)
                            @php
                                $equipmentData = data_get($allEquipmentData, $key, []);
                                $type = old("equipment.{$key}.type", $equipmentData['type'] ?? '');
                                $serial = old("equipment.{$key}.serial_number", $equipmentData['serial_number'] ?? '');
                                $ref = old("equipment.{$key}.reference", $equipmentData['reference'] ?? '');
                                $name = old("equipment.{$key}.name", $equipmentData['name'] ?? '');
                                $other = old("equipment.{$key}.other", $equipmentData['other'] ?? '');
                            @endphp
                            <tr class="equipment-row" data-id="{{ $key }}">
                                <td>
                                    <span class="display-type">{{ $type }}</span>
                                    <input type="hidden" name="equipment[{{ $key }}][type]" value="{{ $type }}">
                                </td>
                                <td>
                                    <span class="display-serial_number">{{ $serial }}</span>
                                    <input type="hidden" name="equipment[{{ $key }}][serial_number]" value="{{ $serial }}">
                                </td>
                                <td>
                                    <span class="display-reference">{{ $ref }}</span>
                                    <input type="hidden" name="equipment[{{ $key }}][reference]" value="{{ $ref }}">
                                </td>
                                <td>
                                    <span class="display-name">{{ $name }}</span>
                                    <input type="hidden" name="equipment[{{ $key }}][name]" value="{{ $name }}">
                                </td>
                                @foreach ($equipmentChecklistFields as $fieldKey => $label)
                                    @php
                                        $isChecked = old("equipment.{$key}.{$fieldKey}", $equipmentData[$fieldKey] ?? false);
                                    @endphp
                                    <td class="text-center">
                                        <i class="bi {{ (bool)$isChecked ? 'bi-check-square-fill text-success' : 'bi-square text-muted' }}"></i>
                                        <input type="hidden" name="equipment[{{ $key }}][{{ $fieldKey }}]" value="{{ (bool)$isChecked ? '1' : '0' }}">
                                    </td>
                                @endforeach
                                <td>
                                    <span class="display-other">{{ $other }}</span>
                                    <input type="hidden" name="equipment[{{ $key }}][other]" value="{{ $other }}">
                                </td>
                                <td class="no-print text-center">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary edit-equipment-row">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-equipment-row">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="text-end mt-2 no-print">
                    <button type="button" id="add-equipment-btn" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-plus"></i> {{ __('locale.lbl_add_equipment') }}
                    </button>
                </div>
                <datalist id="equipment-types">
                    @foreach($equipmentTypes as $type)
                        <option value="{{ $type->name }}">
                    @endforeach
                </datalist>
                            </div>
                        </main>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
    <div class="d-flex flex-wrap justify-content-end align-items-center mb-4 gap-3 no-print mt-3">
        <button type="submit" class="btn btn-primary px-4" form="repair-print-form">
            <i class="bi bi-save me-1"></i> {{ __('locale.save') }}
        </button>
        <a href="{{ route('repairs.print-preview', $repair) }}" target="_blank" class="btn btn-primary px-4">
            <i class="bi bi-printer me-1"></i> {{ __('locale.btn_print_preview') }}
        </a>
    </div>

    @include('repairs.partials.print_equipment_modal')
</div>
@section('scripts')
@include('repairs.autocomplete_js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tbody = document.getElementById('equipment-tbody');
        const addBtn = document.getElementById('add-equipment-btn');
        const modalElement = document.getElementById('printEquipmentModal');
        const modal = new bootstrap.Modal(modalElement);
        const modalForm = document.getElementById('printEquipmentForm');
        const saveModalBtn = document.getElementById('save-modal-equipment');
        const modalTitle = document.getElementById('printEquipmentModalTitle');
        const mainForm = document.getElementById('repair-print-form');
        const saveIndicator = document.getElementById('save-indicator');

        let currentEditingRow = null;
        const checklistFields = @json(array_keys($equipmentChecklistFields));

        async function saveForm() {
            saveIndicator?.classList.remove('d-none');
            const formData = new FormData(mainForm);
            
            try {
                const response = await fetch(mainForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });
                
                if (!response.ok) throw new Error('Save failed');
                
                // Show success briefly
                if (saveIndicator) {
                    saveIndicator.innerHTML = '<i class="bi bi-check2-all text-success me-1"></i> {{ __("locale.saved") }}';
                    setTimeout(() => {
                        saveIndicator.classList.add('d-none');
                        saveIndicator.innerHTML = '<div class="spinner-border spinner-border-sm me-1" role="status"></div> {{ __("locale.saving") }}...';
                    }, 2000);
                }
            } catch (error) {
                console.error('Auto-save error:', error);
                if (saveIndicator) {
                    saveIndicator.innerHTML = '<i class="bi bi-exclamation-triangle text-danger me-1"></i> Error';
                }
            }
        }

        const debouncedSave = (function() {
            let timeout;
            return function() {
                clearTimeout(timeout);
                timeout = setTimeout(saveForm, 1000);
            };
        })();

        // Auto-save for other fields
        mainForm.querySelectorAll('input, textarea').forEach(el => {
            if (el.closest('.repair-equipment-table') || el.closest('.modal')) return;
            el.addEventListener('input', debouncedSave);
            el.addEventListener('change', debouncedSave);
        });

        function openModal(row = null) {
            currentEditingRow = row;
            modalForm.reset();
            document.getElementById('modal-row-id').value = row ? row.dataset.id : '';
            modalTitle.textContent = row ? "{{ __('locale.lbl_edit_equipment') }}" : "{{ __('locale.lbl_add_equipment') }}";

            // Clear checkboxes manually since reset() might not clear some states depending on browser
            checklistFields.forEach(field => {
                const cb = document.getElementById(`modal-${field}`);
                if (cb) cb.checked = false;
            });

            if (row) {
                const id = row.dataset.id;
                document.getElementById('modal-type').value = row.querySelector(`[name="equipment[${id}][type]"]`).value;
                document.getElementById('modal-serial_number').value = row.querySelector(`[name="equipment[${id}][serial_number]"]`).value;
                document.getElementById('modal-reference').value = row.querySelector(`[name="equipment[${id}][reference]"]`).value;
                document.getElementById('modal-name').value = row.querySelector(`[name="equipment[${id}][name]"]`).value;
                document.getElementById('modal-other').value = row.querySelector(`[name="equipment[${id}][other]"]`).value;

                checklistFields.forEach(field => {
                    const input = row.querySelector(`[name="equipment[${id}][${field}]"]`);
                    const cb = document.getElementById(`modal-${field}`);
                    if (cb) cb.checked = input && input.value == "1";
                });
            }
            modal.show();
        }

        addBtn?.addEventListener('click', () => openModal());

        tbody?.addEventListener('click', function(e) {
            const editBtn = e.target.closest('.edit-equipment-row');
            const removeBtn = e.target.closest('.remove-equipment-row');

            if (editBtn) {
                openModal(editBtn.closest('tr'));
            } else if (removeBtn) {
                if (confirm('{{ __("locale.delete_confirmation") }}')) {
                    removeBtn.closest('tr').remove();
                    saveForm();
                }
            }
        });

        saveModalBtn?.addEventListener('click', function() {
            const id = document.getElementById('modal-row-id').value || 'new-' + Date.now();
            let row = currentEditingRow;

            if (!row) {
                row = document.createElement('tr');
                row.className = 'equipment-row';
                row.dataset.id = id;
                tbody.appendChild(row);
            }

            const data = {
                type: document.getElementById('modal-type').value,
                serial_number: document.getElementById('modal-serial_number').value,
                reference: document.getElementById('modal-reference').value,
                name: document.getElementById('modal-name').value,
                other: document.getElementById('modal-other').value
            };

            checklistFields.forEach(field => {
                const cb = document.getElementById(`modal-${field}`);
                data[field] = cb && cb.checked ? "1" : "0";
            });

            let html = `
                <td><span class="display-type">${data.type}</span><input type="hidden" name="equipment[${id}][type]" value="${data.type}"></td>
                <td><span class="display-serial_number">${data.serial_number}</span><input type="hidden" name="equipment[${id}][serial_number]" value="${data.serial_number}"></td>
                <td><span class="display-reference">${data.reference}</span><input type="hidden" name="equipment[${id}][reference]" value="${data.reference}"></td>
                <td><span class="display-name">${data.name}</span><input type="hidden" name="equipment[${id}][name]" value="${data.name}"></td>
            `;

            checklistFields.forEach(field => {
                const isChecked = data[field] == "1";
                const displayClass = isChecked ? 'bi-check-square-fill text-success' : 'bi-square text-muted';
                html += `
                    <td class="text-center">
                        <i class="bi ${displayClass}"></i>
                        <input type="hidden" name="equipment[${id}][${field}]" value="${data[field]}">
                    </td>
                `;
            });

            html += `
                <td><span class="display-other">${data.other}</span><input type="hidden" name="equipment[${id}][other]" value="${data.other}"></td>
                <td class="no-print text-center">
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-primary edit-equipment-row">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-equipment-row">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </td>
            `;

            row.innerHTML = html;
            modal.hide();
            saveForm();
        });

        // Autocomplete support in modal
        const searchInput = document.getElementById('modal-equipment-search');
        if (searchInput) {
            searchInput.addEventListener('repairs:autocomplete-selected', function(e) {
                const item = e.detail.item;
                if (item) {
                    document.getElementById('modal-name').value = item.name || '';
                    document.getElementById('modal-reference').value = item.reference || '';
                    // Type mapping if available
                    if (item.equipment_type) {
                        document.getElementById('modal-type').value = item.equipment_type.name || '';
                    } else if (item.equipment_type_name) {
                         document.getElementById('modal-type').value = item.equipment_type_name;
                    }
                }
            });
        }
    });
</script>
@endsection
@endsection

