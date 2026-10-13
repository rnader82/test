<script>
(function () {
    const initEquipmentForm = () => {
        const dataElement = document.getElementById('equipment-form-data');

        if (!dataElement) {
            return;
        }

        let parsedFormData = {};
        if (dataElement.tagName === 'SCRIPT') {
            try {
                parsedFormData = JSON.parse(dataElement.textContent || '{}');
            } catch (e) {
                parsedFormData = {};
            }
        }

        const parseJsonDataset = (key, fallback) => {
            if (parsedFormData && parsedFormData[key] !== undefined) {
                return parsedFormData[key];
            }
            try {
                const value = dataElement.dataset[key];
                if (!value) {
                    return fallback;
                }
                const parsed = JSON.parse(value);
                return Array.isArray(parsed) || typeof parsed === 'object' ? parsed : fallback;
            } catch (error) {
                return fallback;
            }
        };

        const toStringValue = (value) => (value === null || value === undefined ? '' : String(value));

        const equipmentTypes = parseJsonDataset('types', []);
        const brands = parseJsonDataset('brands', []);
        const suppliers = parseJsonDataset('suppliers', []);
        const warrantyTypes = parseJsonDataset('warrantyTypes', []);
        const repairDiagnoses = parseJsonDataset('repairDiagnoses', []);
        const equipments = parseJsonDataset('equipments', []);
        const selectedEntries = parseJsonDataset('selected', []);
        const labels = parseJsonDataset('labels', {});

        const equipmentMap = new Map();
        equipments.forEach((equipment) => {
            const id = toStringValue(equipment.id);
            if (id) {
                equipmentMap.set(id, equipment);
            }
        });

        const container = document.getElementById('equipment-items');
        const addButton = document.getElementById('addEquipmentButton');

        if (!container) {
            return;
        }

        const ensureSearchModal = () => {
            const modals = Array.from(document.querySelectorAll('#repairEquipmentSearchModal'));
            if (!modals.length) {
                return null;
            }

            const activeModal = modals.pop();
            modals.forEach((duplicate) => duplicate.remove());

            if (!document.body.contains(activeModal)) {
                document.body.appendChild(activeModal);
            }

            return activeModal;
        };

        const modalElement = ensureSearchModal();
        const modalConstructor = window.bootstrap && window.bootstrap.Modal;
        const modalInstance = modalElement && modalConstructor
            ? modalConstructor.getOrCreateInstance(modalElement)
            : null;
        const searchInput = modalElement ? modalElement.querySelector('[data-equipment-search-input]') : null;
        const hiddenInput = modalElement ? modalElement.querySelector('[data-equipment-search-hidden]') : null;
        const resultsList = modalElement ? modalElement.querySelector('[data-autocomplete-results]') : null;
        const canUseSearchModal = Boolean(modalInstance && searchInput);

        let activeCard = null;

        const resetModal = () => {
            if (searchInput) {
                searchInput.value = '';
                searchInput.dataset.autocompleteSelectedValue = '';
                searchInput.dataset.autocompleteSelectedLabel = '';
            }

            if (hiddenInput) {
                hiddenInput.value = '';
            }

            if (resultsList) {
                resultsList.innerHTML = '';
                resultsList.hidden = true;
                resultsList.classList.remove('show');
            }
        };

        const openSearchModalForCard = (card) => {
            if (!canUseSearchModal || !modalInstance) {
                const fallbackSelect = card.querySelector('.equipment-model');
                if (fallbackSelect) {
                    fallbackSelect.classList.remove('d-none');
                    fallbackSelect.focus();
                }
                return;
            }

            activeCard = card;
            resetModal();
            modalInstance.show();

            window.setTimeout(() => {
                if (searchInput) {
                    searchInput.focus();
                    searchInput.select();
                }
            }, 120);
        };

        if (modalElement) {
            modalElement.addEventListener('hidden.bs.modal', () => {
                activeCard = null;
                resetModal();
            });
        }

        const resolveEquipmentDetails = (item) => {
            if (!item) {
                return null;
            }

            const itemId = toStringValue(item.id ?? item.value);
            if (!itemId) {
                return null;
            }

            const localData = equipmentMap.get(itemId) || {};
            const merged = Object.assign({}, localData, item, { id: itemId });
            equipmentMap.set(itemId, merged);

            return merged;
        };

        const filterBrands = (typeId) => {
            if (!typeId) {
                return brands;
            }

            const allowedBrandIds = new Set(
                equipments
                    .filter((equipment) => toStringValue(equipment.equipment_type_id) === toStringValue(typeId))
                    .map((equipment) => toStringValue(equipment.brand_id))
            );

            return brands.filter((brand) => allowedBrandIds.has(toStringValue(brand.id)));
        };

        const filterModels = (typeId, brandId) => {
            return equipments.filter((equipment) => {
                const matchesType = !typeId || toStringValue(equipment.equipment_type_id) === toStringValue(typeId);
                const matchesBrand = !brandId || toStringValue(equipment.brand_id) === toStringValue(brandId);
                return matchesType && matchesBrand;
            });
        };

        const populateSelect = (select, options, placeholder, selectedValue) => {
            if (!select) return;
            const currentValue = selectedValue !== null && selectedValue !== undefined ? toStringValue(selectedValue) : toStringValue(select.value);
            select.innerHTML = '';

            const placeholderOption = document.createElement('option');
            placeholderOption.value = '';
            placeholderOption.textContent = placeholder || '';
            select.appendChild(placeholderOption);

            options.forEach((optionData) => {
                const option = document.createElement('option');
                option.value = optionData.id ?? '';
                option.textContent = optionData.name ?? '';
                if (optionData.code) {
                    option.dataset.code = optionData.code;
                }
                if (toStringValue(option.value) === currentValue || (optionData.code && optionData.code === currentValue)) {
                    option.selected = true;
                }
                select.appendChild(option);
            });
        };

        const populateModelSelect = (select, typeId, brandId, selectedValue) => {
            if (!select) return;
            const models = filterModels(typeId, brandId);
            const currentValue = selectedValue ?? select.value;

            select.innerHTML = '';
            const placeholderOption = document.createElement('option');
            placeholderOption.value = '';
            placeholderOption.textContent = labels.selectModel || 'Sélectionner un modèle';
            select.appendChild(placeholderOption);

            models.forEach((model) => {
                const option = document.createElement('option');
                option.value = model.id ?? '';
                const reference = model.reference ? `${model.reference} - ` : '';
                option.textContent = `${reference}${model.name ?? ''}`;
                option.dataset.price = model.price_per_unit ?? '';
                option.dataset.reference = model.reference ?? '';
                option.dataset.brandId = toStringValue(model.brand_id);
                option.dataset.equipmentTypeId = toStringValue(model.equipment_type_id);
                option.dataset.name = model.name ?? '';
                if (toStringValue(option.value) === toStringValue(currentValue)) {
                    option.selected = true;
                }
                select.appendChild(option);
            });
        };

        const updateModelDisplay = (card) => {
            const modelSelect = card.querySelector('.equipment-model');
            const displayInput = card.querySelector('[data-equipment-model-display]');
            const clearButton = card.querySelector('[data-equipment-clear-selection]');

            if (!modelSelect || !displayInput) return;

            const selectedOption = modelSelect.options[modelSelect.selectedIndex] || null;

            if (selectedOption && selectedOption.value) {
                const label = (selectedOption.textContent || '').trim();
                displayInput.value = label;
                displayInput.title = label;
                displayInput.dataset.selectedEquipmentId = selectedOption.value;
                if (clearButton) clearButton.classList.remove('d-none');
            } else {
                displayInput.value = '';
                displayInput.title = '';
                displayInput.dataset.selectedEquipmentId = '';
                if (labels.selectModel) displayInput.placeholder = labels.selectModel;
                if (clearButton) clearButton.classList.add('d-none');
            }
        };

        /**
         * Applies the selected model/equipment and automatically auto-fills
         * its Brand and Equipment Type into the card.
         */
        const applyEquipmentSelection = (card, equipment) => {
            if (!card || !equipment) {
                return;
            }

            const typeSelect = card.querySelector('.equipment-type');
            const brandSelect = card.querySelector('.equipment-brand');
            const modelSelect = card.querySelector('.equipment-model');

            const typeId = toStringValue(equipment.equipment_type_id);
            const brandId = toStringValue(equipment.brand_id);
            const modelId = toStringValue(equipment.id);

            // 1. Auto-fill Equipment Type
            if (typeSelect && typeId) {
                typeSelect.value = typeId;
            }

            // 2. Re-populate and auto-fill Brand
            if (brandSelect) {
                populateSelect(
                    brandSelect,
                    filterBrands(typeId),
                    labels.selectBrand || 'Toutes les marques',
                    brandId
                );
                if (brandId) {
                    brandSelect.value = brandId;
                }
            }

            // 3. Re-populate and select Model
            if (modelSelect) {
                populateModelSelect(modelSelect, typeId, brandId, modelId);
                modelSelect.value = modelId;
            }

            updateModelDisplay(card);
        };

        const updateFieldNames = () => {
            const cards = container.querySelectorAll('.equipment-card');
            cards.forEach((card, index) => {
                const title = card.querySelector('.equipment-card-title');
                if (title && labels.deviceTitle) {
                    title.textContent = labels.deviceTitle.replace(':number', index + 1);
                } else if (title) {
                    title.textContent = `Matériel #${index + 1}`;
                }

                card.querySelectorAll('[data-field]').forEach((field) => {
                    const fieldName = field.getAttribute('data-field');
                    field.setAttribute('name', `equipment_data[${index}][${fieldName}]`);
                });
            });
        };

        const handleWarrantyToggle = (card) => {
            const warrantySelect = card.querySelector('[data-field="warranty_type_id"]') || card.querySelector('[data-field="warranty_type"]');
            const warrantyUnderBlock = card.querySelector('.sav-block-warranty-under');
            const warrantyOutBlock = card.querySelector('.sav-block-warranty-out');
            const warrantyAmenBlock = card.querySelector('.sav-block-warranty-amen');

            if (!warrantySelect) return;
            const selectedOption = warrantySelect.options[warrantySelect.selectedIndex] || null;
            const code = selectedOption ? (selectedOption.dataset.code || selectedOption.value) : warrantySelect.value;

            if (warrantyUnderBlock) warrantyUnderBlock.classList.toggle('d-none', code !== 'under_warranty');
            if (warrantyOutBlock) warrantyOutBlock.classList.toggle('d-none', code !== 'out_of_warranty');
            if (warrantyAmenBlock) warrantyAmenBlock.classList.toggle('d-none', code !== 'amen_contract');
        };

        const initSupplierSelect2 = (supplierSelect, card) => {
            if (!supplierSelect) return;
            if (window.jQuery && typeof window.jQuery.fn.select2 !== 'undefined') {
                const $select = $(supplierSelect);
                if ($select.data('select2')) {
                    $select.select2('destroy');
                }
                const $container = $select.closest('.position-relative');
                $select.select2({
                    theme: 'bootstrap-5',
                    placeholder: labels.selectSupplier || '-- Choisir fournisseur --',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $container.length ? $container : $select.parent()
                });
            }
        };

        const formatDateToDMY = (val) => {
            if (!val) return '';
            val = String(val).trim();
            if (/^\d{1,2}\/\d{1,2}\/\d{4}$/.test(val)) return val;
            if (/^\d{4}-\d{2}-\d{2}/.test(val)) {
                const parts = val.split('T')[0].split('-');
                return `${parts[2]}/${parts[1]}/${parts[0]}`;
            }
            return val;
        };

        const createEquipmentCard = (initialData = {}) => {
            const card = document.createElement('div');
            card.className = 'card mb-4 border shadow-sm equipment-card';

            const d = new Date();
            const todayDMY = `${String(d.getDate()).padStart(2, '0')}/${String(d.getMonth() + 1).padStart(2, '0')}/${d.getFullYear()}`;
            const arrivalVal = formatDateToDMY(initialData.arrival_date) || todayDMY;

            card.innerHTML = `
                <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                    <span class="fw-bold text-dark equipment-card-title"><i class="bi bi-cpu me-1 text-primary"></i> Matériel</span>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-equipment-btn">
                        <i class="bi bi-trash me-1"></i> ${labels.remove || 'Supprimer'}
                    </button>
                </div>
                <div class="card-body">
                    <!-- SECTION 1 : RECEPTION & IDENTIFICATION -->
                    <div class="p-3 mb-3 bg-light rounded-3 border">
                        <h6 class="fw-bold text-secondary mb-3 small text-uppercase"><i class="bi bi-box-seam me-1 text-primary"></i> 1. Réception & Matériel</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">${labels.type || 'Type'}</label>
                                <select class="form-select form-select-sm equipment-type" data-field="equipment_type_id"></select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">${labels.brand || 'Marque'}</label>
                                <select class="form-select form-select-sm equipment-brand" data-field="brand_id"></select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">${labels.model || 'Modèle'} <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" readonly placeholder="${labels.selectModel || 'Choisir modèle...'}" data-equipment-model-display style="cursor: pointer;">
                                    <button class="btn btn-outline-secondary" type="button" data-equipment-open-search title="Rechercher un modèle">
                                        <i class="bi bi-search"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary d-none" type="button" data-equipment-clear-selection title="Effacer">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                                <select class="form-select form-select-sm equipment-model d-none" data-field="equipment_id" required></select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">${labels.serial || 'N° de série'}</label>
                                <input type="text" class="form-control form-control-sm font-monospace" placeholder="SN / IPEI..." data-field="serial_number" value="${toStringValue(initialData.serial_number)}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">${labels.arrivalDate || "Date d'arrivée"}</label>
                                <input type="text" class="form-control form-control-sm flatpickr-date" placeholder="JJ/MM/AAAA" data-field="arrival_date" value="${arrivalVal}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">${labels.description || 'Description / Symptôme de la panne'}</label>
                                <input type="text" class="form-control form-control-sm" placeholder="Panne constatée..." data-field="description" value="${toStringValue(initialData.description)}">
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2 : PRISE EN CHARGE & GARANTIE -->
                    <div class="p-3 mb-3 bg-light rounded-3 border">
                        <h6 class="fw-bold text-secondary mb-3 small text-uppercase"><i class="bi bi-shield-check me-1 text-primary"></i> 2. Garantie & Prise en charge</h6>
                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">${labels.warranty || 'Type de prise en charge'}</label>
                                <select class="form-select form-select-sm equipment-warranty-select" data-field="warranty_type_id"></select>
                            </div>
                        </div>

                        <!-- SOUS-BLOC : SOUS GARANTIE -->
                        <div class="sav-block-warranty-under mt-2 p-2 bg-white rounded border d-none">
                            <span class="badge bg-success mb-2 small"><i class="bi bi-check-circle me-1"></i> ${labels.underWarranty || 'Prise en charge sous garantie'}</span>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-label small">${labels.recipient || 'Envoi à qui (Destinataire)'}</label>
                                    <input type="text" class="form-control form-control-sm" placeholder="Constructeur / Réparateur" data-field="recipient_name" value="${toStringValue(initialData.recipient_name)}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small">${labels.tracking || 'N° de colis / Suivi'}</label>
                                    <input type="text" class="form-control form-control-sm font-monospace" placeholder="Tracking colis..." data-field="tracking_number" value="${toStringValue(initialData.tracking_number)}">
                                </div>
                            </div>
                        </div>

                        <!-- SOUS-BLOC : HORS GARANTIE (DEVIS) -->
                        <div class="sav-block-warranty-out mt-2 p-2 bg-white rounded border d-none">
                            <span class="badge bg-warning text-dark mb-2 small"><i class="bi bi-file-earmark-text me-1"></i> ${labels.outOfWarranty || 'Gestion du Devis Réparation'}</span>
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <label class="form-label small">${labels.quoteNumber || 'N° Devis'}</label>
                                    <input type="text" class="form-control form-control-sm font-monospace" placeholder="DEV-..." data-field="quote_number" value="${toStringValue(initialData.quote_number)}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small">${labels.quoteAmount || 'Montant (€ HT)'}</label>
                                    <input type="number" step="0.01" class="form-control form-control-sm" placeholder="0.00" data-field="quote_amount" value="${toStringValue(initialData.quote_amount)}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small">${labels.quoteSentDate || 'Date envoi devis client'}</label>
                                    <input type="text" class="form-control form-control-sm flatpickr-date" placeholder="JJ/MM/AAAA" data-field="quote_sent_date" value="${formatDateToDMY(initialData.quote_sent_date)}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small">${labels.quoteSigned || 'Retour devis signé'}</label>
                                    <select class="form-select form-select-sm" data-field="quote_signed">
                                        <option value="">-- En attente --</option>
                                        <option value="1" ${initialData.quote_signed === '1' || initialData.quote_signed === true ? 'selected' : ''}>Oui (Signé)</option>
                                        <option value="0" ${initialData.quote_signed === '0' || initialData.quote_signed === false ? 'selected' : ''}>Non (Refusé)</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small">${labels.quoteSignedDate || 'Date signature devis'}</label>
                                    <input type="text" class="form-control form-control-sm flatpickr-date" placeholder="JJ/MM/AAAA" data-field="quote_signed_date" value="${formatDateToDMY(initialData.quote_signed_date)}">
                                </div>
                            </div>
                        </div>

                        <!-- SOUS-BLOC : CONTRAT AMEN -->
                        <div class="sav-block-warranty-amen mt-2 p-2 bg-white rounded border d-none">
                            <span class="badge bg-primary mb-2 small"><i class="bi bi-phone me-1"></i> ${labels.amenContract || 'Échange Standard / Prêt Contrat Amen'}</span>
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <label class="form-label small">${labels.amenReplacedSerial || 'N° DECT remplacé'}</label>
                                    <input type="text" class="form-control form-control-sm font-monospace" placeholder="S/N remplacé..." data-field="amen_replaced_serial" value="${toStringValue(initialData.amenReplacedSerial || initialData.amen_replaced_serial)}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small">${labels.amenSentDate || 'Date envoi remplacement'}</label>
                                    <input type="text" class="form-control form-control-sm flatpickr-date" placeholder="JJ/MM/AAAA" data-field="amen_sent_date" value="${formatDateToDMY(initialData.amenSentDate || initialData.amen_sent_date)}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small">${labels.loanSerial || 'Matériel de prêt (N° série)'}</label>
                                    <input type="text" class="form-control form-control-sm font-monospace" placeholder="S/N Prêt..." data-field="loan_serial_number" value="${toStringValue(initialData.loanSerial || initialData.loan_serial_number)}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small">${labels.loanDate || 'Date envoi prêt'}</label>
                                    <input type="text" class="form-control form-control-sm flatpickr-date" placeholder="JJ/MM/AAAA" data-field="loan_sent_date" value="${formatDateToDMY(initialData.loanDate || initialData.loan_sent_date)}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 3 : EXPEDITION & RETOUR FOURNISSEUR -->
                    <div class="p-3 mb-3 bg-light rounded-3 border">
                        <h6 class="fw-bold text-secondary mb-3 small text-uppercase"><i class="bi bi-truck me-1 text-primary"></i> 3. Expédition & Retour Fournisseur</h6>
                        <div class="row g-3">
                            <div class="col-md-3 position-relative">
                                <label class="form-label small fw-semibold">${labels.supplier || 'Fournisseur / Réparateur'}</label>
                                <select class="form-select form-select-sm select2-supplier-field" data-field="supplier_id"></select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-semibold">${labels.supplierDispatchDate || 'Date envoi fournisseur'}</label>
                                <input type="text" class="form-control form-control-sm flatpickr-date" placeholder="JJ/MM/AAAA" data-field="supplier_dispatch_date" value="${formatDateToDMY(initialData.supplier_dispatch_date)}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-semibold">${labels.supplierReturnDate || 'Date retour fournisseur'}</label>
                                <input type="text" class="form-control form-control-sm flatpickr-date" placeholder="JJ/MM/AAAA" data-field="supplier_return_date" value="${formatDateToDMY(initialData.supplier_return_date)}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-semibold">${labels.repairStatus || 'Diagnostic / Résultat réparateur'}</label>
                                <select class="form-select form-select-sm" data-field="repair_diagnosis_id"></select>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 4 : EXPEDITION CLIENT & ODOO -->
                    <div class="p-3 mb-3 bg-light rounded-3 border">
                        <h6 class="fw-bold text-secondary mb-3 small text-uppercase"><i class="bi bi-send-check me-1 text-primary"></i> 4. Expédition chez le Client</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">${labels.customerDeliveryDate || 'Date expédition chez le client'}</label>
                                <input type="text" class="form-control form-control-sm flatpickr-date" placeholder="JJ/MM/AAAA" data-field="customer_delivery_date" value="${formatDateToDMY(initialData.customer_delivery_date)}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">${labels.odooDeliveryRef || 'Réf / Bon de livraison Odoo'}</label>
                                <input type="text" class="form-control form-control-sm font-monospace" placeholder="Ex: WH/OUT/00124" data-field="odoo_delivery_ref" value="${toStringValue(initialData.odoo_delivery_ref)}">
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 5 : COMMENTAIRES -->
                    <div>
                        <label class="form-label small fw-semibold"><i class="bi bi-chat-left-text me-1"></i> ${labels.comments || 'Commentaires libres'}</label>
                        <textarea class="form-control form-control-sm" rows="2" placeholder="Remarques et détails SAV..." data-field="comments">${toStringValue(initialData.comments)}</textarea>
                    </div>
                </div>
            `;

            // Setup dropdowns
            const typeSelect = card.querySelector('.equipment-type');
            const brandSelect = card.querySelector('.equipment-brand');
            const modelSelect = card.querySelector('.equipment-model');
            const displayInput = card.querySelector('[data-equipment-model-display]');
            const supplierSelect = card.querySelector('.select2-supplier-field');
            const warrantySelect = card.querySelector('.equipment-warranty-select');
            const diagnosisSelect = card.querySelector('[data-field="repair_diagnosis_id"]');
            const removeBtn = card.querySelector('.remove-equipment-btn');
            const openSearchBtn = card.querySelector('[data-equipment-open-search]');
            const clearBtn = card.querySelector('[data-equipment-clear-selection]');

            // Initial population
            let initTypeId = initialData.equipment_type_id;
            let initBrandId = initialData.brand_id;
            const initModelId = initialData.equipment_id;

            if (initModelId && equipmentMap.has(toStringValue(initModelId))) {
                const eq = equipmentMap.get(toStringValue(initModelId));
                if (!initTypeId) initTypeId = eq.equipment_type_id;
                if (!initBrandId) initBrandId = eq.brand_id;
            }

            populateSelect(typeSelect, equipmentTypes, labels.selectType || 'Tous les types', initTypeId);
            populateSelect(brandSelect, filterBrands(initTypeId), labels.selectBrand || 'Toutes les marques', initBrandId);
            populateModelSelect(modelSelect, initTypeId, initBrandId, initModelId);
            
            // Populate warranty types
            const initialWarrantyVal = initialData.warranty_type_id || initialData.warranty_type || '';
            populateSelect(warrantySelect, warrantyTypes, '-- Sélectionner prise en charge --', initialWarrantyVal);

            // Populate repair diagnoses
            const initialDiagnosisVal = initialData.repair_diagnosis_id || initialData.repair_status || '';
            populateSelect(diagnosisSelect, repairDiagnoses, '-- Non spécifié --', initialDiagnosisVal);

            // Populate suppliers
            populateSelect(supplierSelect, suppliers, labels.selectSupplier || '-- Choisir fournisseur --', initialData.supplier_id);

            updateModelDisplay(card);
            handleWarrantyToggle(card);

            warrantySelect.addEventListener('change', () => handleWarrantyToggle(card));

            // Filtering by Type
            typeSelect.addEventListener('change', (e) => {
                const typeId = e.target.value;
                populateSelect(brandSelect, filterBrands(typeId), labels.selectBrand || 'Toutes les marques', brandSelect.value);
                populateModelSelect(modelSelect, typeId, brandSelect.value);
                updateModelDisplay(card);
            });

            // Filtering by Brand
            brandSelect.addEventListener('change', (e) => {
                const brandId = e.target.value;
                populateModelSelect(modelSelect, typeSelect.value, brandId);
                updateModelDisplay(card);
            });

            // Selecting a Model -> Automatically auto-fills Brand and Equipment Type!
            modelSelect.addEventListener('change', (e) => {
                const selected = e.target.value;
                if (selected && equipmentMap.has(toStringValue(selected))) {
                    const eq = equipmentMap.get(toStringValue(selected));
                    applyEquipmentSelection(card, eq);
                } else {
                    updateModelDisplay(card);
                }
            });

            // Open search modal when clicking search button or input
            if (openSearchBtn) {
                openSearchBtn.addEventListener('click', () => openSearchModalForCard(card));
            }
            if (displayInput) {
                displayInput.addEventListener('click', () => openSearchModalForCard(card));
            }

            if (clearBtn) {
                clearBtn.addEventListener('click', () => {
                    modelSelect.value = '';
                    updateModelDisplay(card);
                });
            }

            removeBtn.addEventListener('click', () => {
                if (window.jQuery && typeof window.jQuery.fn.select2 !== 'undefined') {
                    $(supplierSelect).select2('destroy');
                }
                card.remove();
                updateFieldNames();
            });

            container.appendChild(card);
            updateFieldNames();
            initSupplierSelect2(supplierSelect, card);

            if (typeof flatpickr !== 'undefined') {
                card.querySelectorAll('.flatpickr-date').forEach(el => {
                    flatpickr(el, {
                        dateFormat: 'd/m/Y',
                        allowInput: true,
                        monthSelectorType: 'static'
                    });
                });
            }
        };

        // Handle selection from autocomplete modal
        if (canUseSearchModal && searchInput) {
            const handleAutocompleteSelection = (event) => {
                const detail = event.detail || {};
                if (detail.input && detail.input !== searchInput) {
                    return;
                }

                const equipment = resolveEquipmentDetails(detail.item);
                if (activeCard && equipment) {
                    applyEquipmentSelection(activeCard, equipment);
                }

                if (modalInstance) {
                    modalInstance.hide();
                }
            };

            searchInput.addEventListener('repairs:autocomplete-selected', handleAutocompleteSelection);
        }

        if (addButton) {
            addButton.addEventListener('click', () => createEquipmentCard());
        }

        if (Array.isArray(selectedEntries) && selectedEntries.length > 0) {
            selectedEntries.forEach((entry) => createEquipmentCard(entry));
        } else {
            createEquipmentCard();
        }
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initEquipmentForm);
    } else {
        initEquipmentForm();
    }
})();
</script>