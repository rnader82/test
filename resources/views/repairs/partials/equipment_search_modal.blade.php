<div class="modal fade" id="repairEquipmentSearchModal" tabindex="-1" aria-hidden="true" data-equipment-search-modal>
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content shadow-lg p-3 mb-5 bg-body rounded border border-primary w-75 ms-auto me-auto h-75">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('locale.search_equipment') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('locale.cancel') }}"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="repair_equipment_search_input" class="form-label">{{ __('locale.search_equipment') }}</label>
                    <div class="position-relative" data-autocomplete-container data-equipment-search-container>
                        <input
                            type="text"
                            id="repair_equipment_search_input"
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
                            data-autocomplete-empty-text="{{ __('locale.no_equipment_found') }}"
                            data-equipment-search-input
                        >
                        <input type="hidden" data-equipment-search-hidden>
                        <div
                            class="list-group position-absolute w-100 shadow-sm bg-white"
                            data-autocomplete-results
                            style="max-height: 240px; overflow-y: auto; z-index: 1055;"
                            hidden
                        ></div>
                    </div>
                    <div class="form-text text-muted">{{ __('locale.select_equipment') }}</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('locale.cancel') }}</button>
            </div>
        </div>
    </div>
</div>