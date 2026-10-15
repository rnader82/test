@extends('layouts.app')
@section('portal-module', 'pricelists')
@section('portal-page', 'edit')
@section('portal-kind', 'form')
@section('portal-family', 'catalog')
@section('portal-icon', 'tags')


@section('title', __('locale.edit_pricelist_title'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('pricelists.show', $pricelist->id) }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.edit_pricelist_title') }}</h1>
    </div>
@endsection

@section('content')
<style>
    .btn-odoo-primary {
        background-color: #714B67;
        color: white;
        border: 1px solid #714B67;
        font-weight: 500;
        border-radius: 4px;
        transition: all 0.2s;
    }
    .btn-odoo-primary:hover {
        background-color: #5c3c54;
        border-color: #5c3c54;
        color: white;
    }
    .btn-odoo-secondary {
        background-color: white;
        color: #495057;
        border: 1px solid #ced4da;
        font-weight: 500;
        border-radius: 4px;
        transition: all 0.2s;
    }
    .btn-odoo-secondary:hover {
        background-color: #f8f9fa;
        color: #212529;
    }
    .odoo-sheet {
        background: #ffffff;
        border: 1px solid #dee2e6;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        padding: 2.5rem;
        margin-top: 1rem;
        margin-bottom: 2rem;
        border-radius: 4px;
        max-width: 900px;
    }
    .odoo-form-label {
        font-weight: 600;
        color: #495057;
        font-size: 0.9rem;
    }
    .odoo-form-control {
        border-radius: 4px;
        border: 1px solid #ced4da;
        padding: 0.375rem 0.75rem;
    }
    .odoo-form-control:focus {
        border-color: #714B67;
        box-shadow: 0 0 0 0.25rem rgba(113, 75, 103, 0.25);
    }
    .drop-zone-active {
        border: 2px dashed #714B67 !important;
        background-color: rgba(113, 75, 103, 0.05) !important;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .btn-outline-odoo {
        color: #495057 !important;
        border: 1px solid var(--md-sys-color-outline-variant) !important;
        background-color: #ffffff !important;
        font-weight: 500;
        transition: all 0.2s;
    }
    .btn-outline-odoo:hover {
        background-color: var(--md-sys-color-secondary) !important;
        color: var(--md-sys-color-primary) !important;
        border-color: transparent !important;
        opacity: 0.9;
    }
    .btn-check:checked + .btn-outline-odoo {
        background-color: var(--md-sys-color-secondary) !important;
        color: var(--md-sys-color-primary) !important;
        border-color: transparent !important;
    }
    .text-odoo-color {
        color: #714B67 !important;
    }
</style>

<div class="container-fluid px-4 mt-3">
    <form action="{{ route('pricelists.update', $pricelist->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Control Header Banner -->
        <div class="module-form-card card card-premium p-4 mb-4">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <h2 class="mb-0 fw-bold text-dark fs-4">
                            {{ __('locale.edit_pricelist_title') }}
                        </h2>
                    </div>
                </div>
                <div class="col-md-5 text-md-end mt-3 mt-md-0">
                    <div class="d-flex align-items-center gap-2 justify-content-md-end">
                        <button type="submit" class="btn btn-odoo-primary px-4">{{ __('locale.btn_save') }}</button>
                        <a href="{{ route('pricelists.show', $pricelist->id) }}" class="btn btn-odoo-secondary px-4">{{ __('locale.btn_ignore') }}</a>
                    </div>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger max-width-900">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-4">
            <!-- Left Panel: Available Selection Mode -->
            <div class="col-xl-4 col-lg-5">
                <div class="module-form-card card card-premium" style="max-height: 750px; display: flex; flex-direction: column; padding: 20px !important;">
                    <div class="card-header bg-transparent border-0 pt-0 px-0 pb-3 d-flex align-items-center justify-content-between mb-2">
                        <!-- Mode selector -->
                        <div class="btn-group w-100" role="group" aria-label="Selection Mode">
                            <input type="radio" class="btn-check" name="left-panel-mode" id="mode-products" value="products" checked>
                            <label class="btn btn-outline-odoo btn-sm py-2 px-3 fw-bold" for="mode-products">
                                <i class="bi bi-box-seam me-1"></i> Produits
                            </label>
                            
                            <input type="radio" class="btn-check" name="left-panel-mode" id="mode-categories" value="categories">
                            <label class="btn btn-outline-odoo btn-sm py-2 px-3 fw-bold" for="mode-categories">
                                <i class="bi bi-tags me-1"></i> Catégories
                            </label>
                            
                            <input type="radio" class="btn-check" name="left-panel-mode" id="mode-global" value="global">
                            <label class="btn btn-outline-odoo btn-sm py-2 px-3 fw-bold" for="mode-global">
                                <i class="bi bi-globe me-1"></i> Global
                            </label>
                        </div>
                    </div>
                    
                    <div class="card-body p-0 d-flex flex-column overflow-hidden">
                        <!-- Header/Title -->
                        <div class="mb-3 d-flex align-items-center justify-content-between">
                            <span class="fw-bold text-dark small" id="left-panel-title">
                                Produits disponibles
                                <span class="badge bg-secondary-subtle text-secondary ms-1 rounded-circle" id="products-count">0</span>
                            </span>
                        </div>

                        <!-- Search Bar -->
                        <div class="mb-3" id="search-bar-container">
                            <div class="input-group search-bar-mockup w-100" style="max-width: none;">
                                <i class="bi bi-search"></i>
                                <input type="text" id="search-left-panel" class="form-control" placeholder="Rechercher un article">
                            </div>
                        </div>

                        <!-- Dynamic Cascading Category selectors (only for products mode) -->
                        <div class="row g-2 mb-3" id="cascading-selectors-container">
                            <div class="col-4">
                                <select id="select-category" class="form-select form-select-sm px-2">
                                    <option value="">Catégorie</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <select id="select-family" class="form-select form-select-sm px-2" disabled>
                                    <option value="">Famille</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <select id="select-subfamily" class="form-select form-select-sm px-2" disabled>
                                    <option value="">Sous-famille</option>
                                </select>
                            </div>
                        </div>

                        <!-- Available items list scroll area -->
                        <div id="left-panel-list" class="flex-grow-1 overflow-auto list-group list-group-flush border-top border-light pt-2" style="max-height: 400px; min-height: 300px;">
                            <div class="text-center text-muted py-4 small">
                                <i class="bi bi-search me-1"></i> Tapez pour rechercher ou sélectionnez une catégorie.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Tabbed Form and Rules -->
            <div class="col-xl-8 col-lg-7">
                <div class="module-form-card card card-premium p-4" id="right-panel-card" style="min-height: 600px;">
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs nav-tabs-premium mb-4" id="pricelistFormTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active px-4 py-3 fw-bold" id="info-tab" data-bs-toggle="tab" data-bs-target="#tab-info" type="button" role="tab">
                                <i class="bi bi-info-circle me-1"></i> Informations
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-4 py-3 fw-bold" id="produits-tab" data-bs-toggle="tab" data-bs-target="#tab-produits" type="button" role="tab">
                                <i class="bi bi-list-stars me-1"></i> Produits
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-4 py-3 fw-bold" id="config-tab" data-bs-toggle="tab" data-bs-target="#tab-config" type="button" role="tab">
                                <i class="bi bi-gear me-1"></i> Configuration
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-4 py-3 fw-bold" id="visualisation-tab" data-bs-toggle="tab" data-bs-target="#tab-visualisation" type="button" role="tab">
                                <i class="bi bi-eye me-1"></i> Visualisation
                            </button>
                        </li>
                    </ul>

                    <!-- Tabs Content -->
                    <div class="tab-content bg-transparent p-0 border-0" id="pricelistFormTabsContent">
                        
                        <!-- Tab 1: Informations (General fields) -->
                        <div class="tab-pane fade show active" id="tab-info" role="tabpanel">
                            <div class="row g-4">
                                <div class="col-12">
                                    <label for="name" class="odoo-form-label">{{ __('locale.lbl_pricelist_name') }}</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $pricelist->name) }}" required class="form-control odoo-form-control form-control-lg fs-3 fw-bold">
                                </div>

                                <div class="col-md-6 mt-4">
                                    <label for="currency" class="odoo-form-label">{{ __('locale.lbl_currency') }}</label>
                                    <select name="currency" id="currency" required class="form-select odoo-form-control">
                                        <option value="EUR" {{ old('currency', $pricelist->currency) === 'EUR' ? 'selected' : '' }}>EUR</option>
                                        <option value="USD" {{ old('currency', $pricelist->currency) === 'USD' ? 'selected' : '' }}>USD</option>
                                        <option value="GBP" {{ old('currency', $pricelist->currency) === 'GBP' ? 'selected' : '' }}>GBP</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mt-4">
                                    <label for="company_id" class="odoo-form-label">{{ __('locale.menu_company') }}</label>
                                    <select name="company_id" id="company_id" class="form-select odoo-form-control">
                                        <option value="">{{ __('locale.lbl_all_companies') }}</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}" {{ old('company_id', $pricelist->company_id) == $company->id ? 'selected' : '' }}>
                                                {{ $company->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mt-4">
                                    <label for="client_id" class="odoo-form-label">{{ __('locale.lbl_assigned_client') }}</label>
                                    <select name="client_id" id="client_id" class="form-select odoo-form-control">
                                        <option value="">{{ __('locale.lbl_no_client_generic') }}</option>
                                        @foreach($clients as $c)
                                            <option value="{{ $c->id }}" {{ old('client_id', $pricelist->client_id) == $c->id ? 'selected' : '' }}>
                                                {{ $c->raison_sociale }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 mt-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="active" id="active" value="1" {{ $pricelist->active ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold text-muted" for="active">{{ __('locale.active') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab 2: Produits (Rules List) -->
                        <div class="tab-pane fade" id="tab-produits" role="tabpanel">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h4 class="mb-0 text-dark fw-bold">{{ __('locale.lbl_rules_count') ?? 'Règles de prix' }}</h4>
                            </div>
                            
                            <ul class="nav nav-tabs tab-odoo mb-4" id="pricelistTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="sales-tab" data-bs-toggle="tab" data-bs-target="#sales-rules" type="button" role="tab" aria-controls="sales-rules" aria-selected="true">
                                        {{ __('locale.tab_sales_price') }}
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="recurring-tab" data-bs-toggle="tab" data-bs-target="#recurring-rules" type="button" role="tab" aria-controls="recurring-rules" aria-selected="false">
                                        {{ __('locale.tab_recurring_price') }}
                                    </button>
                                </li>
                            </ul>

                            <input type="hidden" name="rules_json" id="rules_json" value="[]">

                            <div class="tab-content" id="pricelistTabsContent">
                                <!-- Sales Rules Tab -->
                                <div class="tab-pane fade show active" id="sales-rules" role="tabpanel" aria-labelledby="sales-tab">
                                    <div class="table-responsive">
                                        <table class="table table-hover odoo-table-gray align-middle">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('locale.lbl_apply_on') }}</th>
                                                    <th>{{ __('locale.lbl_price') }}</th>
                                                    <th>{{ __('locale.lbl_min_qty') }}</th>
                                                    <th>{{ __('locale.lbl_start_date') }}</th>
                                                    <th>{{ __('locale.lbl_end_date') }}</th>
                                                    <th style="width: 80px;"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="sales-rules-body">
                                                <!-- Dynamic content -->
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-3">
                                        <a href="#" class="add-line-link" onclick="openAddRuleModal(event, '')">
                                            <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_add_line') }}
                                        </a>
                                    </div>
                                </div>

                                <!-- Recurring Rules Tab -->
                                <div class="tab-pane fade" id="recurring-rules" role="tabpanel" aria-labelledby="recurring-tab">
                                    <div class="table-responsive">
                                        <table class="table table-hover odoo-table-gray align-middle">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('locale.lbl_apply_on') }}</th>
                                                    <th>{{ __('locale.lbl_recurring_plan') }}</th>
                                                    <th>{{ __('locale.lbl_price') }}</th>
                                                    <th>{{ __('locale.lbl_min_qty') }}</th>
                                                    <th>{{ __('locale.lbl_start_date') }}</th>
                                                    <th>{{ __('locale.lbl_end_date') }}</th>
                                                    <th style="width: 80px;"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="recurring-rules-body">
                                                <!-- Dynamic content -->
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-3">
                                        <a href="#" class="add-line-link" onclick="openAddRuleModal(event, 'Mensuel')">
                                            <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_add_line') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab 3: Configuration -->
                        <div class="tab-pane fade" id="tab-config" role="tabpanel">
                            <div class="py-4 text-center">
                                <i class="bi bi-gear text-muted fs-1 mb-3 d-block"></i>
                                <h5 class="fw-bold text-dark mb-1">Configuration de la liste de prix</h5>
                                <p class="text-muted small">Aucune configuration financière supplémentaire n'est requise pour cette liste de prix.</p>
                            </div>
                        </div>

                        <!-- Tab 4: Visualisation -->
                        <div class="tab-pane fade" id="tab-visualisation" role="tabpanel">
                            <div class="py-4 text-center">
                                <i class="bi bi-eye text-muted fs-1 mb-3 d-block"></i>
                                <h5 class="fw-bold text-dark mb-1">Aperçu Visuel</h5>
                                <p class="text-muted small">Chargeant l'aperçu de la liste de prix...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@include('pricelists.partials.rule_modal')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let rules = {!! old('rules_json', json_encode($pricelist->items)) !!};
        let editingRuleIndex = -1;

        function getEquipmentName(id) {
            const select = document.getElementById('equipment_id');
            const option = select.querySelector(`option[value="${id}"]`);
            return option ? option.textContent.trim() : '{{ __("locale.lbl_unknown_product") }}';
        }

        function getCategoryName(id) {
            const select = document.getElementById('category_id');
            const option = select.querySelector(`option[value="${id}"]`);
            return option ? option.textContent.trim() : '{{ __("locale.lbl_unknown_category") }}';
        }

        function formatPrice(rule) {
            if (rule.compute_price === 'fixed') {
                return (parseFloat(rule.fixed_price) || 0).toFixed(2).replace('.', ',') + ' €';
            } else if (rule.compute_price === 'percentage') {
                const pct = (parseFloat(rule.percent_price) || 0).toFixed(2).replace('.', ',');
                return '{{ __("locale.lbl_percent_discount", ["percent" => ":pct"]) }}'.replace(':pct', pct);
            } else if (rule.compute_price === 'formula') {
                let baseName = '{{ __("locale.lbl_public_sales_price") }}';
                if (rule.base === 'standard_price') {
                    baseName = '{{ __("locale.lbl_purchase_cost") }}';
                } else if (rule.base === 'pricelist') {
                    const select = document.getElementById('base_pricelist_id');
                    const opt = select.querySelector(`option[value="${rule.base_pricelist_id}"]`);
                    baseName = opt ? opt.textContent.trim() : '{{ __("locale.lbl_other_pricelist") }}';
                }
                
                let display = '';
                const discount = parseFloat(rule.price_discount) || 0;
                if (discount !== 0) {
                    const pct = discount.toFixed(2).replace('.', ',');
                    if (rule.base === 'standard_price') {
                        display = '{{ __("locale.lbl_markup_on", ["percent" => ":pct", "base" => ":base"]) }}'
                            .replace(':pct', pct).replace(':base', baseName);
                    } else {
                        display = '{{ __("locale.lbl_percent_discount_on", ["percent" => ":pct", "base" => ":base"]) }}'
                            .replace(':pct', pct).replace(':base', baseName);
                    }
                } else {
                    display = '{{ __("locale.lbl_based_on", ["base" => ":base"]) }}'.replace(':base', baseName);
                }
                
                const surcharge = parseFloat(rule.price_surcharge) || 0;
                if (surcharge !== 0) {
                    display += ' + ' + surcharge.toFixed(2).replace('.', ',') + ' €';
                }
                return display;
            }
            return '';
        }

        function formatDate(dateStr) {
            if (!dateStr) return '—';
            const date = new Date(dateStr);
            if (isNaN(date.getTime())) return '—';
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }

        window.renderRules = function() {
            const salesBody = document.getElementById('sales-rules-body');
            const recurringBody = document.getElementById('recurring-rules-body');
            
            salesBody.innerHTML = '';
            recurringBody.innerHTML = '';
            
            let salesCount = 0;
            let recurringCount = 0;
            
            rules.forEach((rule, index) => {
                let applyText = '{{ __("locale.lbl_all_products") }}';
                if (rule.applied_on === '1_product' || rule.applied_on === '0_product_variant') {
                    if (rule.equipment_name) {
                        applyText = rule.equipment_name;
                    } else if (rule.equipment) {
                        applyText = rule.equipment.name + ' [' + rule.equipment.reference + ']';
                    } else {
                        applyText = getEquipmentName(rule.equipment_id);
                    }
                } else if (rule.applied_on === '2_product_category') {
                    applyText = '{{ __("locale.lbl_category_prefix") }}' + getCategoryName(rule.category_id);
                }
                
                const minQty = (parseFloat(rule.min_quantity) || 0).toFixed(2).replace('.', ',');
                const startDate = formatDate(rule.date_start);
                const endDate = formatDate(rule.date_end);
                
                const row = document.createElement('tr');
                
                if (!rule.plan_name) {
                    // Sales price rule
                    row.innerHTML = `
                        <td>${applyText}</td>
                        <td class="fw-semibold">${formatPrice(rule)}</td>
                        <td>${minQty}</td>
                        <td>${startDate}</td>
                        <td>${endDate}</td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-link text-muted p-0 me-2" onclick="editRule(${index})"><i class="bi bi-pencil fs-5"></i></button>
                            <button type="button" class="btn btn-sm btn-link text-muted p-0" onclick="deleteRule(${index})"><i class="bi bi-trash fs-5"></i></button>
                        </td>
                    `;
                    salesBody.appendChild(row);
                    salesCount++;
                } else {
                    // Recurring price rule
                    row.innerHTML = `
                        <td>${applyText}</td>
                        <td class="fw-semibold text-primary">${rule.plan_name}</td>
                        <td class="fw-semibold">${formatPrice(rule)}</td>
                        <td>${minQty}</td>
                        <td>${startDate}</td>
                        <td>${endDate}</td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-link text-muted p-0 me-2" onclick="editRule(${index})"><i class="bi bi-pencil fs-5"></i></button>
                            <button type="button" class="btn btn-sm btn-link text-muted p-0" onclick="deleteRule(${index})"><i class="bi bi-trash fs-5"></i></button>
                        </td>
                    `;
                    recurringBody.appendChild(row);
                    recurringCount++;
                }
            });
            
            if (salesCount === 0) {
                salesBody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-muted">{{ __("locale.no_rules_defined") }}</td></tr>`;
            }
            if (recurringCount === 0) {
                recurringBody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-muted">{{ __("locale.no_recurring_rules_defined") }}</td></tr>`;
            }
            
            document.getElementById('rules_json').value = JSON.stringify(rules);
            updateVisualisationTab();
        };

        window.deleteRule = function(index) {
            if (confirm('{{ __("locale.confirm_delete_rule") }}')) {
                rules.splice(index, 1);
                renderRules();
            }
        };

        window.editRule = function(index) {
            editingRuleIndex = index;
            const rule = rules[index];
            
            document.querySelector(`input[name="applied_on"][value="${rule.applied_on}"]`).checked = true;
            document.querySelector(`input[name="applied_on"][value="${rule.applied_on}"]`).dispatchEvent(new Event('change'));
            
            if (rule.applied_on === '1_product' || rule.applied_on === '0_product_variant') {
                const select = document.getElementById('equipment_id');
                let option = select.querySelector(`option[value="${rule.equipment_id}"]`);
                if (!option && rule.equipment_id) {
                    option = document.createElement('option');
                    option.value = rule.equipment_id;
                    option.textContent = rule.equipment_name || (rule.equipment ? (rule.equipment.name + ' [' + rule.equipment.reference + ']') : 'Produit selectionné');
                    select.appendChild(option);
                }
                select.value = rule.equipment_id || '';
            } else if (rule.applied_on === '2_product_category') {
                document.getElementById('category_id').value = rule.category_id || '';
            }
            
            document.getElementById('min_quantity').value = rule.min_quantity || '0.00';
            document.getElementById('date_start').value = rule.date_start ? rule.date_start.substring(0, 10) : '';
            document.getElementById('date_end').value = rule.date_end ? rule.date_end.substring(0, 10) : '';
            
            document.querySelector(`input[name="compute_price"][value="${rule.compute_price}"]`).checked = true;
            document.querySelector(`input[name="compute_price"][value="${rule.compute_price}"]`).dispatchEvent(new Event('change'));
            
            if (rule.compute_price === 'fixed') {
                document.getElementById('fixed_price').value = rule.fixed_price || '0.00';
            } else if (rule.compute_price === 'percentage') {
                document.getElementById('percent_price').value = rule.percent_price || '0.00';
            } else if (rule.compute_price === 'formula') {
                document.getElementById('base').value = rule.base || 'list_price';
                document.getElementById('base').dispatchEvent(new Event('change'));
                document.getElementById('base_pricelist_id').value = rule.base_pricelist_id || '';
                document.getElementById('price_discount').value = rule.price_discount || '0.00';
                document.getElementById('price_surcharge').value = rule.price_surcharge || '0.00';
                document.getElementById('price_round').value = rule.price_round || '0.00';
                document.getElementById('price_min_margin').value = rule.price_min_margin || '0.00';
                document.getElementById('price_max_margin').value = rule.price_max_margin || '0.00';
            }
            
            document.getElementById('modal_plan_name').value = rule.plan_name || '';
            
            if (rule.plan_name) {
                document.getElementById('ruleModalLabel').textContent = "{{ __('locale.modal_create_recurring_rules') }}";
            } else {
                document.getElementById('ruleModalLabel').textContent = "{{ __('locale.modal_create_rules') }}";
            }
            
            const modal = new bootstrap.Modal(document.getElementById('ruleModal'));
            modal.show();
        };

        window.openAddRuleModal = function(event, planName) {
            if (event) event.preventDefault();
            editingRuleIndex = -1;
            
            document.getElementById('ruleForm').reset();
            
            document.querySelector('input[name="applied_on"][value="3_global"]').checked = true;
            document.querySelector('input[name="applied_on"][value="3_global"]').dispatchEvent(new Event('change'));
            document.querySelector('input[name="compute_price"][value="fixed"]').checked = true;
            document.querySelector('input[name="compute_price"][value="fixed"]').dispatchEvent(new Event('change'));
            document.getElementById('base').dispatchEvent(new Event('change'));
            
            document.getElementById('modal_plan_name').value = planName;
            
            if (planName) {
                document.getElementById('ruleModalLabel').textContent = "{{ __('locale.modal_create_recurring_rules') }}";
            } else {
                document.getElementById('ruleModalLabel').textContent = "{{ __('locale.modal_create_rules') }}";
            }
            
            const modal = new bootstrap.Modal(document.getElementById('ruleModal'));
            modal.show();
        };

        window.openAddRuleModalWithProduct = function(product) {
            editingRuleIndex = -1;
            document.getElementById('ruleForm').reset();
            
            const appliedProductRadio = document.querySelector('input[name="applied_on"][value="1_product"]');
            if (appliedProductRadio) {
                appliedProductRadio.checked = true;
                appliedProductRadio.dispatchEvent(new Event('change'));
            }
            
            const select = document.getElementById('equipment_id');
            if (select) {
                let option = select.querySelector(`option[value="${product.id}"]`);
                if (!option) {
                    option = document.createElement('option');
                    option.value = product.id;
                    option.textContent = `${product.name} [${product.reference}]`;
                    select.appendChild(option);
                }
                select.value = product.id;
            }

            document.querySelector('input[name="compute_price"][value="fixed"]').checked = true;
            document.querySelector('input[name="compute_price"][value="fixed"]').dispatchEvent(new Event('change'));
            document.getElementById('base').dispatchEvent(new Event('change'));
            
            document.getElementById('modal_plan_name').value = '';
            document.getElementById('ruleModalLabel').textContent = "{{ __('locale.modal_create_rules') }}";
            
            const modal = new bootstrap.Modal(document.getElementById('ruleModal'));
            modal.show();
        };

        const ruleForm = document.getElementById('ruleForm');
        ruleForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const applied_on = document.querySelector('input[name="applied_on"]:checked').value;
            const min_quantity = document.getElementById('min_quantity').value;
            const date_start = document.getElementById('date_start').value;
            const date_end = document.getElementById('date_end').value;
            const compute_price = document.querySelector('input[name="compute_price"]:checked').value;
            const plan_name = document.getElementById('modal_plan_name').value;
            
            let rule = {
                applied_on,
                min_quantity: parseFloat(min_quantity) || 0,
                date_start: date_start || null,
                date_end: date_end || null,
                compute_price,
                plan_name: plan_name || null
            };
            
            if (editingRuleIndex !== -1 && rules[editingRuleIndex].id) {
                rule.id = rules[editingRuleIndex].id;
            }
            
            if (applied_on === '1_product' || applied_on === '0_product_variant') {
                const select = document.getElementById('equipment_id');
                rule.equipment_id = select.value;
                const selectedOpt = select.options[select.selectedIndex];
                rule.equipment_name = selectedOpt ? selectedOpt.textContent.trim() : '';
            } else if (applied_on === '2_product_category') {
                const select = document.getElementById('category_id');
                rule.category_id = select.value;
                const selectedOpt = select.options[select.selectedIndex];
                rule.category_name = selectedOpt ? selectedOpt.textContent.trim() : '';
            }
            
            if (compute_price === 'fixed') {
                rule.fixed_price = parseFloat(document.getElementById('fixed_price').value) || 0;
            } else if (compute_price === 'percentage') {
                rule.percent_price = parseFloat(document.getElementById('percent_price').value) || 0;
            } else if (compute_price === 'formula') {
                rule.base = document.getElementById('base').value;
                if (rule.base === 'pricelist') {
                    rule.base_pricelist_id = document.getElementById('base_pricelist_id').value;
                }
                rule.price_discount = parseFloat(document.getElementById('price_discount').value) || 0;
                rule.price_surcharge = parseFloat(document.getElementById('price_surcharge').value) || 0;
                rule.price_round = parseFloat(document.getElementById('price_round').value) || 0;
                rule.price_min_margin = parseFloat(document.getElementById('price_min_margin').value) || 0;
                rule.price_max_margin = parseFloat(document.getElementById('price_max_margin').value) || 0;
            }
            
            if (editingRuleIndex === -1) {
                rules.push(rule);
            } else {
                rules[editingRuleIndex] = rule;
            }
            
            const modalEl = document.getElementById('ruleModal');
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) {
                modalInstance.hide();
            }
            
            renderRules();
        });

        // --- Available Products AJAX Loader & Cascading Selectors ---
        let categories = [];

        function loadCategories() {
            fetch('{{ route("orders.categories") }}')
                .then(resp => resp.json())
                .then(data => {
                    categories = data;
                    buildCategoryDropdown();
                    const currentMode = document.querySelector('input[name="left-panel-mode"]:checked')?.value;
                    if (currentMode === 'categories') {
                        renderCategoriesList();
                    }
                });
        }

        function buildCategoryDropdown() {
            const select = document.getElementById('select-category');
            if (!select) return;
            select.innerHTML = '<option value="">Catégorie</option>';
            
            const topLevel = categories.filter(c => !c.parent_id);
            topLevel.forEach(c => {
                const opt = document.createElement('option');
                opt.value = c.id;
                opt.textContent = c.name_raw;
                select.appendChild(opt);
            });
        }

        const selectCategory = document.getElementById('select-category');
        if (selectCategory) {
            selectCategory.addEventListener('change', (e) => {
                const catId = e.target.value;
                const selectFamily = document.getElementById('select-family');
                const selectSubfamily = document.getElementById('select-subfamily');

                selectFamily.innerHTML = '<option value="">Famille</option>';
                selectFamily.disabled = true;
                selectSubfamily.innerHTML = '<option value="">Sous-famille</option>';
                selectSubfamily.disabled = true;

                if (catId) {
                    const selectedCat = categories.find(c => c.id == catId);
                    if (selectedCat) {
                        const families = categories.filter(c => c.parent_id == selectedCat.id);
                        if (families.length > 0) {
                            selectFamily.disabled = false;
                            families.forEach(c => {
                                const opt = document.createElement('option');
                                opt.value = c.id;
                                opt.textContent = c.name_raw;
                                selectFamily.appendChild(opt);
                            });
                        }
                    }
                }
                loadEquipments();
            });
        }

        const selectFamily = document.getElementById('select-family');
        if (selectFamily) {
            selectFamily.addEventListener('change', (e) => {
                const famId = e.target.value;
                const selectSubfamily = document.getElementById('select-subfamily');

                selectSubfamily.innerHTML = '<option value="">Sous-famille</option>';
                selectSubfamily.disabled = true;

                if (famId) {
                    const selectedFam = categories.find(c => c.id == famId);
                    if (selectedFam) {
                        const subfamilies = categories.filter(c => c.parent_id == selectedFam.id);
                        if (subfamilies.length > 0) {
                            selectSubfamily.disabled = false;
                            subfamilies.forEach(c => {
                                const opt = document.createElement('option');
                                opt.value = c.id;
                                opt.textContent = c.name_raw;
                                selectSubfamily.appendChild(opt);
                            });
                        }
                    }
                }
                loadEquipments();
            });
        }

        const selectSubfamily = document.getElementById('select-subfamily');
        if (selectSubfamily) {
            selectSubfamily.addEventListener('change', () => {
                loadEquipments();
            });
        }

        let searchTimeout = null;
        const searchInput = document.getElementById('search-left-panel');
        if (searchInput) {
            searchInput.addEventListener('input', () => {
                const currentMode = document.querySelector('input[name="left-panel-mode"]:checked').value;
                clearTimeout(searchTimeout);
                
                if (currentMode === 'products') {
                    searchTimeout = setTimeout(loadEquipments, 300);
                } else if (currentMode === 'categories') {
                    searchTimeout = setTimeout(renderCategoriesList, 100);
                }
            });
        }

        // Mode toggling logic
        const leftPanelModes = document.querySelectorAll('input[name="left-panel-mode"]');
        leftPanelModes.forEach(modeRadio => {
            modeRadio.addEventListener('change', (e) => {
                const mode = e.target.value;
                const searchBarContainer = document.getElementById('search-bar-container');
                const cascadingSelectorsContainer = document.getElementById('cascading-selectors-container');
                const searchInput = document.getElementById('search-left-panel');
                
                // Reset search query when changing modes
                if (searchInput) searchInput.value = '';

                if (mode === 'products') {
                    document.getElementById('left-panel-title').innerHTML = `
                        Produits disponibles
                        <span class="badge bg-secondary-subtle text-secondary ms-1 rounded-circle" id="products-count">0</span>
                    `;
                    if (searchBarContainer) searchBarContainer.style.display = 'block';
                    if (cascadingSelectorsContainer) cascadingSelectorsContainer.style.setProperty('display', 'flex', 'important');
                    if (searchInput) searchInput.placeholder = 'Rechercher un article';
                    
                    loadEquipments();
                } else if (mode === 'categories') {
                    document.getElementById('left-panel-title').innerHTML = `
                        Catégories disponibles
                        <span class="badge bg-secondary-subtle text-secondary ms-1 rounded-circle" id="categories-count">0</span>
                    `;
                    if (searchBarContainer) searchBarContainer.style.display = 'block';
                    if (cascadingSelectorsContainer) cascadingSelectorsContainer.style.setProperty('display', 'none', 'important');
                    if (searchInput) searchInput.placeholder = 'Rechercher une catégorie';
                    
                    renderCategoriesList();
                } else if (mode === 'global') {
                    document.getElementById('left-panel-title').innerHTML = `Application globale`;
                    if (searchBarContainer) searchBarContainer.style.display = 'none';
                    if (cascadingSelectorsContainer) cascadingSelectorsContainer.style.setProperty('display', 'none', 'important');
                    
                    renderGlobalList();
                }
            });
        });

        function loadEquipments() {
            const q = document.getElementById('search-left-panel').value;
            const clientVal = document.getElementById('client_id').value;
            
            let categoryId = document.getElementById('select-subfamily').value;
            if (!categoryId) {
                categoryId = document.getElementById('select-family').value;
            }
            if (!categoryId) {
                categoryId = document.getElementById('select-category').value;
            }

            const url = new URL('{{ route("orders.equipments") }}', window.location.origin);
            if (q) url.searchParams.append('q', q);
            if (clientVal) url.searchParams.append('client_id', clientVal);
            if (categoryId) url.searchParams.append('category_id', categoryId);

            const container = document.getElementById('left-panel-list');
            if (!container) return;
            container.innerHTML = '<div class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary" role="status"></div> Loading...</div>';

            fetch(url.toString())
                .then(resp => resp.json())
                .then(equipments => {
                    const currentMode = document.querySelector('input[name="left-panel-mode"]:checked').value;
                    if (currentMode !== 'products') return;

                    container.innerHTML = '';
                    const countBadge = document.getElementById('products-count');
                    if (countBadge) countBadge.textContent = equipments.length;

                    if (equipments.length === 0) {
                        container.innerHTML = '<div class="text-center py-4 text-muted small">Aucun article trouvé.</div>';
                        return;
                    }

                    equipments.forEach(eq => {
                        const card = document.createElement('div');
                        card.className = 'list-group-item list-group-item-action border-light py-3 px-2 cursor-pointer';
                        card.setAttribute('draggable', 'true');
                        
                        card.innerHTML = `
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div class="flex-grow-1">
                                    <span class="fw-bold text-dark d-block text-uppercase">${eq.reference}</span>
                                    <small class="text-muted text-break d-block mt-1">${eq.name}</small>
                                </div>
                                <div class="text-end">
                                    <span class="fw-bold text-primary text-nowrap">${eq.price.toLocaleString('fr-FR', { minimumFractionDigits: 2 })} €</span>
                                </div>
                            </div>
                        `;

                        card.addEventListener('dragstart', (e) => {
                            e.dataTransfer.setData('application/json', JSON.stringify({
                                ...eq,
                                drag_type: 'product'
                            }));
                            e.dataTransfer.effectAllowed = 'copy';
                        });

                        card.addEventListener('click', () => {
                            openAddRuleModalWithProduct(eq);
                        });
                        container.appendChild(card);
                    });
                });
        }

        function renderCategoriesList() {
            const container = document.getElementById('left-panel-list');
            if (!container) return;

            const q = document.getElementById('search-left-panel').value.toLowerCase();
            const filteredCategories = categories.filter(cat => cat.name.toLowerCase().includes(q));

            container.innerHTML = '';
            const countBadge = document.getElementById('categories-count');
            if (countBadge) countBadge.textContent = filteredCategories.length;

            if (filteredCategories.length === 0) {
                container.innerHTML = '<div class="text-center py-4 text-muted small">Aucune catégorie trouvée.</div>';
                return;
            }

            filteredCategories.forEach(cat => {
                const card = document.createElement('div');
                card.className = 'list-group-item list-group-item-action border-light py-3 px-2 cursor-pointer';
                card.setAttribute('draggable', 'true');
                
                card.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div class="flex-grow-1">
                            <span class="fw-bold text-dark d-block"><i class="bi bi-tag-fill text-muted me-2"></i>${cat.name}</span>
                        </div>
                    </div>
                `;

                card.addEventListener('dragstart', (e) => {
                    e.dataTransfer.setData('application/json', JSON.stringify({
                        id: cat.id,
                        name: cat.name,
                        drag_type: 'category'
                    }));
                    e.dataTransfer.effectAllowed = 'copy';
                });

                card.addEventListener('click', () => {
                    openAddRuleModalWithCategory(cat);
                });
                container.appendChild(card);
            });
        }

        function renderGlobalList() {
            const container = document.getElementById('left-panel-list');
            if (!container) return;

            container.innerHTML = '';

            const card = document.createElement('div');
            card.className = 'list-group-item list-group-item-action border-light py-4 px-3 cursor-pointer text-center';
            card.setAttribute('draggable', 'true');
            
            card.innerHTML = `
                <div class="py-3">
                    <i class="bi bi-globe fs-1 text-odoo-color mb-2 d-block"></i>
                    <span class="fw-bold text-dark d-block fs-5">Tous les articles (Global)</span>
                    <small class="text-muted d-block mt-2">Cliquez ou glissez-déposez pour appliquer une règle sur l'ensemble du catalogue.</small>
                </div>
            `;

            card.addEventListener('dragstart', (e) => {
                e.dataTransfer.setData('application/json', JSON.stringify({
                    drag_type: 'global'
                }));
                e.dataTransfer.effectAllowed = 'copy';
            });

            card.addEventListener('click', () => {
                openAddRuleModalWithGlobal();
            });
            container.appendChild(card);
        }

        window.openAddRuleModalWithCategory = function(cat) {
            editingRuleIndex = -1;
            document.getElementById('ruleForm').reset();
            
            const appliedCategoryRadio = document.querySelector('input[name="applied_on"][value="2_product_category"]');
            if (appliedCategoryRadio) {
                appliedCategoryRadio.checked = true;
                appliedCategoryRadio.dispatchEvent(new Event('change'));
            }
            
            const select = document.getElementById('category_id');
            if (select) {
                select.value = cat.id;
                $(select).trigger('change');
            }

            document.querySelector('input[name="compute_price"][value="fixed"]').checked = true;
            document.querySelector('input[name="compute_price"][value="fixed"]').dispatchEvent(new Event('change'));
            document.getElementById('base').dispatchEvent(new Event('change'));
            
            document.getElementById('modal_plan_name').value = '';
            document.getElementById('ruleModalLabel').textContent = "{{ __('locale.modal_create_rules') }}";
            
            const modal = new bootstrap.Modal(document.getElementById('ruleModal'));
            modal.show();
        };

        window.openAddRuleModalWithGlobal = function() {
            editingRuleIndex = -1;
            document.getElementById('ruleForm').reset();
            
            const appliedGlobalRadio = document.querySelector('input[name="applied_on"][value="3_global"]');
            if (appliedGlobalRadio) {
                appliedGlobalRadio.checked = true;
                appliedGlobalRadio.dispatchEvent(new Event('change'));
            }

            document.querySelector('input[name="compute_price"][value="fixed"]').checked = true;
            document.querySelector('input[name="compute_price"][value="fixed"]').dispatchEvent(new Event('change'));
            document.getElementById('base').dispatchEvent(new Event('change'));
            
            document.getElementById('modal_plan_name').value = '';
            document.getElementById('ruleModalLabel').textContent = "{{ __('locale.modal_create_rules') }}";
            
            const modal = new bootstrap.Modal(document.getElementById('ruleModal'));
            modal.show();
        };

        // --- Visualisation Live Preview ---
        function updateVisualisationTab() {
            const visTab = document.getElementById('tab-visualisation');
            if (!visTab) return;

            const nameVal = document.getElementById('name').value || 'Sans nom';
            const currencyVal = document.getElementById('currency').value;
            const companySelect = document.getElementById('company_id');
            const companyText = companySelect.options[companySelect.selectedIndex]?.text || '—';
            const clientSelect = document.getElementById('client_id');
            const clientText = clientSelect.options[clientSelect.selectedIndex]?.text || 'Tous les clients';

            let rulesListHtml = '';
            if (rules.length === 0) {
                rulesListHtml = '<p class="text-muted text-center py-4">Aucune règle définie pour le moment.</p>';
            } else {
                rulesListHtml = `
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Cible</th>
                                    <th>Type</th>
                                    <th>Prix / Calcul</th>
                                    <th>Qté Min</th>
                                    <th>Date Début</th>
                                    <th>Date Fin</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${rules.map(rule => {
                                    let applyText = '{{ __("locale.lbl_all_products") }}';
                                    if (rule.applied_on === '1_product' || rule.applied_on === '0_product_variant') {
                                        if (rule.equipment_name) {
                                            applyText = rule.equipment_name;
                                        } else if (rule.equipment) {
                                            applyText = rule.equipment.name + ' [' + rule.equipment.reference + ']';
                                        } else {
                                            applyText = getEquipmentName(rule.equipment_id);
                                        }
                                    } else if (rule.applied_on === '2_product_category') {
                                        if (rule.category_name) {
                                            applyText = '{{ __("locale.lbl_category_prefix") }}' + rule.category_name;
                                        } else if (rule.category) {
                                            applyText = '{{ __("locale.lbl_category_prefix") }}' + rule.category.name;
                                        } else {
                                            applyText = '{{ __("locale.lbl_category_prefix") }}' + getCategoryName(rule.category_id);
                                        }
                                    }
                                    const typeText = rule.plan_name ? `<span class="badge bg-primary-subtle text-primary">${rule.plan_name}</span>` : '<span class="badge bg-secondary-subtle text-secondary">Vente Directe</span>';
                                    return `
                                        <tr>
                                            <td class="fw-semibold">${applyText}</td>
                                            <td>${typeText}</td>
                                            <td class="fw-semibold text-success">${formatPrice(rule)}</td>
                                            <td>${(parseFloat(rule.min_quantity) || 0).toFixed(2).replace('.', ',')}</td>
                                            <td>${formatDate(rule.date_start)}</td>
                                            <td>${formatDate(rule.date_end)}</td>
                                        </tr>
                                    `;
                                }).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            }

            visTab.innerHTML = `
                <div class="p-3">
                    <div class="border-bottom pb-3 mb-4">
                        <h4 class="fw-bold text-dark mb-1">${nameVal}</h4>
                        <div class="d-flex gap-4 mt-2 text-muted small flex-wrap">
                            <div><i class="bi bi-currency-euro me-1"></i> Devise: <strong>${currencyVal}</strong></div>
                            <div><i class="bi bi-building me-1"></i> Société: <strong>${companyText}</strong></div>
                            <div><i class="bi bi-person me-1"></i> Client: <strong>${clientText}</strong></div>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-3 text-dark">Règles de prix (${rules.length})</h5>
                    ${rulesListHtml}
                </div>
            `;
        }

        // Live update listeners for visualisation tab
        document.getElementById('name').addEventListener('input', updateVisualisationTab);
        document.getElementById('currency').addEventListener('change', updateVisualisationTab);
        document.getElementById('company_id').addEventListener('change', updateVisualisationTab);

        $('#client_id').select2({
            theme: 'bootstrap-5',
            placeholder: 'Sélectionnez un client...',
            allowClear: true,
            width: '100%'
        }).on('change', function() {
            const currentMode = document.querySelector('input[name="left-panel-mode"]:checked').value;
            if (currentMode === 'products') {
                loadEquipments();
            }
            updateVisualisationTab();
        });

        // --- Drag and Drop Setup ---
        const rightCard = document.getElementById('right-panel-card');
        if (rightCard) {
            rightCard.addEventListener('dragenter', (e) => {
                const produitsTab = document.getElementById('produits-tab');
                if (produitsTab && !produitsTab.classList.contains('active')) {
                    produitsTab.click();
                }
            });
        }

        const dropZones = [
            { id: 'sales-rules', planName: '' },
            { id: 'recurring-rules', planName: 'Mensuel' }
        ];

        dropZones.forEach(zone => {
            const el = document.getElementById(zone.id);
            if (!el) return;

            el.addEventListener('dragover', (e) => {
                e.preventDefault();
                el.classList.add('drop-zone-active');
            });

            el.addEventListener('dragleave', () => {
                el.classList.remove('drop-zone-active');
            });

            el.addEventListener('drop', (e) => {
                e.preventDefault();
                el.classList.remove('drop-zone-active');

                try {
                    const data = JSON.parse(e.dataTransfer.getData('application/json'));
                    if (data) {
                        let newRule = {
                            min_quantity: 0,
                            compute_price: 'fixed',
                            fixed_price: 0,
                            plan_name: zone.planName || null,
                            date_start: null,
                            date_end: null
                        };

                        if (data.drag_type === 'category') {
                            newRule.applied_on = '2_product_category';
                            newRule.category_id = data.id;
                            newRule.category_name = data.name;
                        } else if (data.drag_type === 'global') {
                            newRule.applied_on = '3_global';
                        } else {
                            // Product or default
                            newRule.applied_on = '1_product';
                            newRule.equipment_id = data.id;
                            newRule.equipment_name = data.name + ' [' + data.reference + ']';
                            newRule.fixed_price = data.price || 0;
                        }

                        rules.push(newRule);
                        renderRules();
                    }
                } catch (err) {
                    console.error("Drop error:", err);
                }
            });
        });

        // Initialize AJAX loaders and render
        loadCategories();
        loadEquipments();
        renderRules();
    });
</script>
@endsection
