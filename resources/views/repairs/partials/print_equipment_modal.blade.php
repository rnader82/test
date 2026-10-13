<div class="modal fade no-print" id="printEquipmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="printEquipmentModalTitle">{{ __('locale.lbl_add_equipment') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('locale.cancel') }}"></button>
            </div>
            <div class="modal-body">
                <form id="printEquipmentForm">
                    <input type="hidden" id="modal-row-id">
                    <div class="row g-3">
                        <div class="col-12" id="modal-search-container">
                            <label class="form-label">{{ __('locale.search_equipment') }}</label>
                            <div class="position-relative" data-autocomplete-container>
                                <input
                                    type="text"
                                    id="modal-equipment-search"
                                    class="form-control"
                                    placeholder="{{ __('locale.search_equipment') }}"
                                    autocomplete="off"
                                    data-autocomplete-input
                                    data-autocomplete-url="{{ route('equipments.search') }}"
                                    data-autocomplete-label-key="name"
                                    data-autocomplete-value-key="id"
                                    data-autocomplete-secondary-key="reference"
                                    data-autocomplete-min-length="2"
                                    data-autocomplete-max-results="10"
                                >
                                <div
                                    class="list-group position-absolute w-100 shadow-sm bg-white"
                                    data-autocomplete-results
                                    style="max-height: 240px; overflow-y: auto; z-index: 1055;"
                                    hidden
                                ></div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('locale.lbl_type') }}</label>
                            <input type="text" id="modal-type" class="form-control" list="equipment-types">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('locale.serial_number') }}</label>
                            <input type="text" id="modal-serial_number" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('locale.internal_number') }}</label>
                            <input type="text" id="modal-reference" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('locale.lbl_name') }}</label>
                            <input type="text" id="modal-name" class="form-control">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold mt-2">{{ __('locale.lbl_checklist') }}</label>
                            <div class="row row-cols-2 row-cols-md-3 g-2">
                                @foreach ($equipmentChecklistFields as $fieldKey => $label)
                                    <div class="col">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="modal-{{ $fieldKey }}">
                                            <label class="form-check-label" for="modal-{{ $fieldKey }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">{{ __('locale.other') }}</label>
                            <input type="text" id="modal-other" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('locale.cancel') }}</button>
                <button type="button" class="btn btn-primary" id="save-modal-equipment">{{ __('locale.save') }}</button>
            </div>
        </div>
    </div>
</div>
