<!-- Rule Modal -->
<div class="modal fade" id="ruleModal" tabindex="-1" aria-labelledby="ruleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 4px; border: 1px solid #dee2e6;">
            <form id="ruleForm" action="{{ route('pricelists.rules.store', $pricelist->id ?? 0) }}" method="POST">
                @csrf
                <input type="hidden" name="plan_name" id="modal_plan_name" value="">

                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" id="ruleModalLabel" style="color: #495057;">{{ __('locale.modal_create_rules') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <!-- Appliquer à -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-muted">{{ __('locale.modal_apply_to') }}</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="applied_on" id="applied_global" value="3_global" checked>
                                    <label class="form-check-label" for="applied_global">{{ __('locale.lbl_all_products') }}</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="applied_on" id="applied_category" value="2_product_category">
                                    <label class="form-check-label" for="applied_category">{{ __('locale.lbl_category') }}</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="applied_on" id="applied_product" value="1_product">
                                    <label class="form-check-label" for="applied_product">{{ __('locale.modal_product') }}</label>
                                </div>
                            </div>
                        </div>

                        <!-- Quantité min -->
                        <div class="col-md-6">
                            <label for="min_quantity" class="form-label fw-semibold text-muted">{{ __('locale.lbl_min_qty') }}</label>
                            <input type="number" step="0.01" name="min_quantity" id="min_quantity" value="0.00" class="form-control" style="border-radius: 4px;">
                        </div>

                        <!-- Product Selector -->
                        <div class="col-md-6" id="product_selector_group" style="display: none;">
                            <label for="equipment_id" class="form-label fw-semibold text-muted">{{ __('locale.modal_product') }}</label>
                            <select name="equipment_id" id="equipment_id" class="form-select" style="border-radius: 4px;">
                                <option value="">{{ __('locale.modal_choose_product') }}</option>
                                @foreach($equipments as $eq)
                                    <option value="{{ $eq->id }}">{{ $eq->name }} [{{ $eq->reference }}]</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Category Selector -->
                        <div class="col-12" id="category_selector_group" style="display: none;">
                            <label class="form-label fw-semibold text-muted d-block">{{ __('locale.modal_product_category') }}</label>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <select id="select-category-level1" class="form-select select2">
                                        <option value="">{{ __('locale.select_category') ?? 'Catégorie' }}</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select id="select-category-level2" class="form-select select2" disabled>
                                        <option value="">{{ __('locale.select_family') ?? 'Famille' }}</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select id="select-category-level3" class="form-select select2" disabled>
                                        <option value="">{{ __('locale.select_subfamily') ?? 'Sous-famille' }}</option>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="category_id" id="category_id" value="">
                        </div>

                        <!-- Validité Dates -->
                        <div class="col-md-6">
                            <label for="date_start" class="form-label fw-semibold text-muted">{{ __('locale.lbl_start_date') }}</label>
                            <input type="date" name="date_start" id="date_start" class="form-control" style="border-radius: 4px;">
                        </div>
                        <div class="col-md-6">
                            <label for="date_end" class="form-label fw-semibold text-muted">{{ __('locale.lbl_end_date') }}</label>
                            <input type="date" name="date_end" id="date_end" class="form-control" style="border-radius: 4px;">
                        </div>

                        <hr class="text-muted my-4">

                        <!-- Type de prix -->
                        <div class="col-md-12">
                            <label class="form-label fw-semibold text-muted">{{ __('locale.modal_price_type') }}</label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="compute_price" id="compute_fixed" value="fixed" checked>
                                    <label class="form-check-label" for="compute_fixed">{{ __('locale.modal_fixed_price') }}</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="compute_price" id="compute_percentage" value="percentage">
                                    <label class="form-check-label" for="compute_percentage">{{ __('locale.modal_discount_public_price') }}</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="compute_price" id="compute_formula" value="formula">
                                    <label class="form-check-label" for="compute_formula">{{ __('locale.modal_formula') }}</label>
                                </div>
                            </div>
                        </div>

                        <!-- Fixed Price Option -->
                        <div class="col-md-6" id="fixed_price_group">
                            <label for="fixed_price" class="form-label fw-semibold text-muted">{{ __('locale.modal_fixed_price') }} (EUR)</label>
                            <input type="number" step="0.01" name="fixed_price" id="fixed_price" value="0.00" class="form-control" style="border-radius: 4px;">
                        </div>

                        <!-- Percentage Option -->
                        <div class="col-md-6" id="percentage_price_group" style="display: none;">
                            <label for="percent_price" class="form-label fw-semibold text-muted">{{ __('locale.modal_discount_pct') }}</label>
                            <input type="number" step="0.01" name="percent_price" id="percent_price" value="0.00" class="form-control" style="border-radius: 4px;">
                        </div>

                        <!-- Formula Option fields -->
                        <div class="row g-3 mt-1" id="formula_price_group" style="display: none;">
                            <div class="col-md-6">
                                <label for="base" class="form-label fw-semibold text-muted">{{ __('locale.modal_base_price') }}</label>
                                <select name="base" id="base" class="form-select" style="border-radius: 4px;">
                                    <option value="list_price">{{ __('locale.modal_public_sales_price') }}</option>
                                    <option value="standard_price">{{ __('locale.modal_purchase_cost') }}</option>
                                    <option value="pricelist">{{ __('locale.modal_other_pricelist') }}</option>
                                </select>
                            </div>

                            <div class="col-md-6" id="base_pricelist_selector" style="display: none;">
                                <label for="base_pricelist_id" class="form-label fw-semibold text-muted">{{ __('locale.modal_other_pricelist') }}</label>
                                <select name="base_pricelist_id" id="base_pricelist_id" class="form-select" style="border-radius: 4px;">
                                    <option value="">{{ __('locale.modal_choose_pricelist') }}</option>
                                    @foreach($pricelists as $otherPl)
                                        <option value="{{ $otherPl->id }}">{{ $otherPl->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="price_discount" class="form-label fw-semibold text-muted">{{ __('locale.modal_discount_margin_pct') }}</label>
                                <input type="number" step="0.01" name="price_discount" id="price_discount" value="0.00" class="form-control" style="border-radius: 4px;">
                                <div class="form-text small text-muted">{{ __('locale.modal_discount_margin_hint') }}</div>
                            </div>

                            <div class="col-md-4">
                                <label for="price_surcharge" class="form-label fw-semibold text-muted">{{ __('locale.modal_extra_fees') }}</label>
                                <input type="number" step="0.01" name="price_surcharge" id="price_surcharge" value="0.00" class="form-control" style="border-radius: 4px;">
                            </div>

                            <div class="col-md-4">
                                <label for="price_round" class="form-label fw-semibold text-muted">{{ __('locale.modal_round_to') }}</label>
                                <input type="number" step="0.01" name="price_round" id="price_round" value="0.00" class="form-control" style="border-radius: 4px;">
                            </div>

                            <div class="col-md-6">
                                <label for="price_min_margin" class="form-label fw-semibold text-muted">{{ __('locale.modal_min_margin') }}</label>
                                <input type="number" step="0.01" name="price_min_margin" id="price_min_margin" value="0.00" class="form-control" style="border-radius: 4px;">
                            </div>

                            <div class="col-md-6">
                                <label for="price_max_margin" class="form-label fw-semibold text-muted">{{ __('locale.modal_max_margin') }}</label>
                                <input type="number" step="0.01" name="price_max_margin" id="price_max_margin" value="0.00" class="form-control" style="border-radius: 4px;">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-odoo-primary px-3">{{ __('locale.btn_save_close') }}</button>
                    <button type="button" class="btn btn-odoo-secondary px-3" data-bs-dismiss="modal">{{ __('locale.btn_ignore') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('ruleModal');
        if (!modal) return;

        // Toggle apply selectors based on applied_on selection
        const radiosApplied = document.querySelectorAll('input[name="applied_on"]');
        const prodGroup = document.getElementById('product_selector_group');
        const catGroup = document.getElementById('category_selector_group');

        function toggleAppliedOnFields() {
            const selected = document.querySelector('input[name="applied_on"]:checked').value;
            if (selected === '1_product') {
                prodGroup.style.display = 'block';
                catGroup.style.display = 'none';
            } else if (selected === '2_product_category') {
                prodGroup.style.display = 'none';
                catGroup.style.display = 'block';
            } else {
                prodGroup.style.display = 'none';
                catGroup.style.display = 'none';
            }
        }

        radiosApplied.forEach(radio => {
            radio.addEventListener('change', toggleAppliedOnFields);
        });

        // Toggle pricing parameters based on compute_price selection
        const radiosCompute = document.querySelectorAll('input[name="compute_price"]');
        const fixedGroup = document.getElementById('fixed_price_group');
        const pctGroup = document.getElementById('percentage_price_group');
        const formulaGroup = document.getElementById('formula_price_group');

        function togglePricingFields() {
            const selected = document.querySelector('input[name="compute_price"]:checked').value;
            if (selected === 'fixed') {
                fixedGroup.style.display = 'block';
                pctGroup.style.display = 'none';
                formulaGroup.style.display = 'none';
            } else if (selected === 'percentage') {
                fixedGroup.style.display = 'none';
                pctGroup.style.display = 'block';
                formulaGroup.style.display = 'none';
            } else if (selected === 'formula') {
                fixedGroup.style.display = 'none';
                pctGroup.style.display = 'none';
                formulaGroup.style.display = 'flex';
            }
        }

        radiosCompute.forEach(radio => {
            radio.addEventListener('change', togglePricingFields);
        });

        // Toggle other pricelist selection based on base select
        const baseSelect = document.getElementById('base');
        const basePlGroup = document.getElementById('base_pricelist_selector');

        baseSelect.addEventListener('change', function() {
            if (baseSelect.value === 'pricelist') {
                basePlGroup.style.display = 'block';
            } else {
                basePlGroup.style.display = 'none';
            }
        });

        // Initialize Select2 search for Categories and Equipments in modal
        const jQueryModal = $('#ruleModal');
        jQueryModal.on('shown.bs.modal', function () {
            $('#select-category-level1, #select-category-level2, #select-category-level3').select2({
                theme: 'bootstrap-5',
                dropdownParent: jQueryModal,
                allowClear: true,
                width: '100%'
            });
            $('#equipment_id').select2({
                theme: 'bootstrap-5',
                dropdownParent: jQueryModal,
                placeholder: 'Sélectionnez un produit...',
                allowClear: true,
                width: '100%',
                ajax: {
                    url: '{{ route("orders.equipments") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.name + ' [' + item.reference + ']'
                                };
                            })
                        };
                    },
                    cache: true
                }
            });
        });

        jQueryModal.on('hidden.bs.modal', function () {
            ['#select-category-level1', '#select-category-level2', '#select-category-level3'].forEach(sel => {
                if ($(sel).hasClass("select2-hidden-accessible")) {
                    $(sel).select2('destroy');
                }
            });
            if ($('#equipment_id').hasClass("select2-hidden-accessible")) {
                $('#equipment_id').select2('destroy');
            }
        });

        // --- Pricelist Category Cascading Logic ---
        const modalCategories = @json($categories);
        const selectCatLevel1 = document.getElementById('select-category-level1');
        const selectCatLevel2 = document.getElementById('select-category-level2');
        const selectCatLevel3 = document.getElementById('select-category-level3');
        const categoryHiddenInput = document.getElementById('category_id');

        function getSelectedCategoryHierarchy(id) {
            if (!id) return { catId: null, famId: null, subId: null };
            const c3 = modalCategories.find(c => c.id == id);
            if (!c3) return { catId: null, famId: null, subId: null };
            
            if (!c3.parent_id) {
                return { catId: c3.id, famId: null, subId: null };
            }
            
            const c2 = modalCategories.find(c => c.id == c3.parent_id);
            if (!c2) {
                return { catId: c3.id, famId: null, subId: null };
            }
            
            if (!c2.parent_id) {
                return { catId: c2.id, famId: c3.id, subId: null };
            }
            
            const c1 = modalCategories.find(c => c.id == c2.parent_id);
            if (!c1) {
                return { catId: c2.id, famId: c3.id, subId: null };
            }
            
            return { catId: c1.id, famId: c2.id, subId: c3.id };
        }

        function buildCategoryOptions(selectElement, list, selectedId, placeholder) {
            selectElement.innerHTML = `<option value="">${placeholder}</option>`;
            list.forEach(c => {
                const opt = document.createElement('option');
                opt.value = c.id;
                opt.textContent = c.name_raw;
                if (c.id == selectedId) {
                    opt.selected = true;
                }
                selectElement.appendChild(opt);
            });
            const disabledState = list.length === 0;
            $(selectElement).prop('disabled', disabledState).trigger('change');
        }

        function updateCategoryCascades() {
            const catId = selectCatLevel1.value;
            const famId = selectCatLevel2.value;
            const subId = selectCatLevel3.value;

            let finalId = '';
            if (subId) {
                finalId = subId;
            } else if (famId) {
                finalId = famId;
            } else if (catId) {
                finalId = catId;
            }
            categoryHiddenInput.value = finalId;
        }

        function reinitCategoryDropdowns(selectedId) {
            const hierarchy = getSelectedCategoryHierarchy(selectedId);
            const topLevelCats = modalCategories.filter(c => !c.parent_id);
            buildCategoryOptions(selectCatLevel1, topLevelCats, hierarchy.catId, "-- Catégorie --");

            if (hierarchy.catId) {
                const families = modalCategories.filter(c => c.parent_id == hierarchy.catId);
                buildCategoryOptions(selectCatLevel2, families, hierarchy.famId, "-- Famille --");
            } else {
                buildCategoryOptions(selectCatLevel2, [], null, "-- Famille --");
            }

            if (hierarchy.famId) {
                const subfamilies = modalCategories.filter(c => c.parent_id == hierarchy.famId);
                buildCategoryOptions(selectCatLevel3, subfamilies, hierarchy.subId, "-- Sous-famille --");
            } else {
                buildCategoryOptions(selectCatLevel3, [], null, "-- Sous-famille --");
            }

            if ($(selectCatLevel1).hasClass('select2-hidden-accessible') || $(selectCatLevel1).data('select2')) {
                $(selectCatLevel1).trigger('change.select2');
            }
            if ($(selectCatLevel2).hasClass('select2-hidden-accessible') || $(selectCatLevel2).data('select2')) {
                $(selectCatLevel2).trigger('change.select2');
            }
            if ($(selectCatLevel3).hasClass('select2-hidden-accessible') || $(selectCatLevel3).data('select2')) {
                $(selectCatLevel3).trigger('change.select2');
            }
        }

        // Event Listeners for changes in selectors
        $(selectCatLevel1).on('change', function () {
            const catId = this.value;
            selectCatLevel2.innerHTML = '<option value="">-- Famille --</option>';
            selectCatLevel2.disabled = true;
            selectCatLevel3.innerHTML = '<option value="">-- Sous-famille --</option>';
            selectCatLevel3.disabled = true;

            if (catId) {
                const families = modalCategories.filter(c => c.parent_id == catId);
                buildCategoryOptions(selectCatLevel2, families, null, "-- Famille --");
            }
            updateCategoryCascades();
            if ($(selectCatLevel2).hasClass('select2-hidden-accessible') || $(selectCatLevel2).data('select2')) {
                $(selectCatLevel2).trigger('change.select2');
            }
            if ($(selectCatLevel3).hasClass('select2-hidden-accessible') || $(selectCatLevel3).data('select2')) {
                $(selectCatLevel3).trigger('change.select2');
            }
        });

        $(selectCatLevel2).on('change', function () {
            const famId = this.value;
            selectCatLevel3.innerHTML = '<option value="">-- Sous-famille --</option>';
            selectCatLevel3.disabled = true;

            if (famId) {
                const subfamilies = modalCategories.filter(c => c.parent_id == famId);
                buildCategoryOptions(selectCatLevel3, subfamilies, null, "-- Sous-famille --");
            }
            updateCategoryCascades();
            if ($(selectCatLevel3).hasClass('select2-hidden-accessible') || $(selectCatLevel3).data('select2')) {
                $(selectCatLevel3).trigger('change.select2');
            }
        });

        $(selectCatLevel3).on('change', function () {
            updateCategoryCascades();
        });

        // Sync cascading selectors when hidden input changes programmatically (backward compatibility)
        $(categoryHiddenInput).on('change', function () {
            const currentSelectedVal = categoryHiddenInput.value;
            const catVal = selectCatLevel1.value;
            const famVal = selectCatLevel2.value;
            const subVal = selectCatLevel3.value;
            const dropdownVal = subVal || famVal || catVal;
            
            if (dropdownVal != currentSelectedVal) {
                reinitCategoryDropdowns(currentSelectedVal);
            }
        });

        // Initialize selectors on load
        reinitCategoryDropdowns(categoryHiddenInput.value);
    });
</script>
