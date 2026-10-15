@extends('layouts.app')
@section('portal-module', 'equipment_categories')
@section('portal-page', 'index')
@section('portal-kind', 'list')
@section('portal-family', 'catalog')
@section('portal-icon', 'tags')


@section('title', 'Segmentation produits')

@section('navbar-icon')
    <i class="bi bi-diagram-3-fill title-icon me-2 text-primary"></i>
@endsection

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1 class="mb-0">Segmentation produits</h1>
    </div>
@endsection

@section('navbar-subtitle', 'Gestion et organisation des catégories, familles et sous-familles de produits')

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-2 px-3 py-1-5 shadow-sm fw-semibold" id="btn-save-changes">
            <i class="bi bi-floppy"></i>
            <span>Sauvegarder</span>
            <span class="badge bg-white text-primary rounded-pill d-none" id="save-badge-count">0</span>
        </button>

        <button type="button" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2 px-3 py-1-5 shadow-sm fw-semibold" id="btn-restore-changes" title="Réinitialiser les changements non sauvegardés">
            <i class="bi bi-arrow-counterclockwise"></i>
            <span>Restaurer</span>
        </button>
    </div>
@endsection

@section('content')
<div class="content-wrapper p-3 p-md-4">
    <div class="container-fluid p-0">

        <!-- Toast Alerts Container -->
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999;">
            <div id="segmentationToast" class="toast align-items-center text-white bg-dark border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center gap-2" id="toastMessage">
                        <i class="bi bi-check-circle-fill text-success"></i> Action effectuée.
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>

        <!-- Row 1: 3 Columns (Catégories, Familles, Sous familles) -->
        <div class="row g-3 mb-3">
            <!-- Col 1: Catégories -->
            <div class="col-12 col-lg-4">
                <div class="card h-100 shadow-sm border rounded-3 bg-white tree-panel" id="panel-categories" data-level="1">
                    <div class="card-header bg-white border-bottom py-2-5 px-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn-circle-add btn-circle-blue" id="btn-add-category" title="Ajouter une catégorie">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                            <span class="fw-bold text-dark fs-6">Catégories</span>
                        </div>
                        <span class="badge badge-subtle-blue" id="badge-categories-count"># 0 disponibles</span>
                    </div>
                    <div class="card-body p-0 tree-list-scrollable" id="list-categories">
                        <div class="text-center py-4 text-muted small">
                            <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
                            <div>Chargement...</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Col 2: Familles -->
            <div class="col-12 col-lg-4">
                <div class="card h-100 shadow-sm border rounded-3 bg-white tree-panel" id="panel-families" data-level="2">
                    <div class="card-header bg-white border-bottom py-2-5 px-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn-circle-add btn-circle-grey" id="btn-add-family" title="Ajouter une famille" disabled>
                                <i class="bi bi-plus-lg"></i>
                            </button>
                            <span class="fw-bold text-dark fs-6">Familles</span>
                        </div>
                        <span class="badge badge-subtle-blue d-none" id="badge-families-count"># 0 disponibles</span>
                    </div>
                    <div class="card-body p-0 tree-list-scrollable" id="list-families">
                        <div class="text-center py-4 text-muted small tree-placeholder">
                            Sélectionnez une catégorie pour voir ses familles
                        </div>
                    </div>
                </div>
            </div>

            <!-- Col 3: Sous familles -->
            <div class="col-12 col-lg-4">
                <div class="card h-100 shadow-sm border rounded-3 bg-white tree-panel" id="panel-subfamilies" data-level="3">
                    <div class="card-header bg-white border-bottom py-2-5 px-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn-circle-add btn-circle-grey" id="btn-add-subfamily" title="Ajouter une sous-famille" disabled>
                                <i class="bi bi-plus-lg"></i>
                            </button>
                            <span class="fw-bold text-dark fs-6">Sous familles</span>
                        </div>
                        <span class="badge badge-subtle-blue d-none" id="badge-subfamilies-count"># 0 disponibles</span>
                    </div>
                    <div class="card-body p-0 tree-list-scrollable" id="list-subfamilies">
                        <div class="text-center py-4 text-muted small tree-placeholder">
                            Sélectionnez une famille pour voir ses sous-familles
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: 2 Columns (Produits trouvés & Produits disponibles) -->
        <div class="row g-3">
            <!-- Left: Produits trouvés -->
            <div class="col-12 col-lg-6">
                <div class="card h-100 shadow-sm border rounded-3 bg-white products-panel" id="panel-found-products">
                    <div class="card-header bg-white border-bottom py-2-5 px-3 d-flex align-items-center justify-content-between">
                        <span class="fw-bold text-dark fs-6">Produits trouvés</span>
                        <span class="badge badge-subtle-blue" id="badge-found-segmentation">
                            # Aucune segmentation définie
                        </span>
                    </div>
                    <div class="card-body p-0 table-scrollable drop-target-zone" id="zone-found-products">
                        <table class="module-table table table-hover align-middle mb-0 segmentation-table">
                            <thead>
                                <tr>
                                    <th style="width: 25%;">Produit</th>
                                    <th style="width: 45%;">Description</th>
                                    <th style="width: 15%;">Famille</th>
                                    <th style="width: 15%;" class="text-end pe-3">Prix</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-found-products">
                                <!-- Empty state by default -->
                                <tr>
                                    <td colspan="4" class="p-0">
                                        <div class="empty-state-wrapper py-5 text-center">
                                            <div class="empty-state-icon mb-3">
                                                <i class="bi bi-cart-x"></i>
                                            </div>
                                            <h6 class="fw-bold text-dark mb-1">Aucun produit trouvé.</h6>
                                            <p class="text-muted small mb-0">Ajustez les filtres pour plus de résultats.</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right: Produits disponibles -->
            <div class="col-12 col-lg-6">
                <div class="card h-100 shadow-sm border rounded-3 bg-white products-panel" id="panel-available-products">
                    <div class="card-header bg-white border-bottom py-2-5 px-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold text-dark fs-6">Produits disponibles</span>
                            <span class="badge badge-subtle-green" id="badge-available-count">
                                <i class="bi bi-check-circle-fill me-1"></i>0 disponibles
                            </span>
                        </div>
                        <div class="search-input-wrapper position-relative">
                            <i class="bi bi-search search-icon"></i>
                            <input type="text" class="form-control form-control-sm search-field ps-4" id="input-search-available" placeholder="Rechercher un produit">
                        </div>
                    </div>
                    <div class="card-body p-0 table-scrollable drop-target-zone" id="zone-available-products">
                        <table class="module-table table table-hover align-middle mb-0 segmentation-table">
                            <thead>
                                <tr>
                                    <th style="width: 25%;">Produit</th>
                                    <th style="width: 45%;">Description</th>
                                    <th style="width: 15%;">Famille</th>
                                    <th style="width: 15%;" class="text-end pe-3">Prix</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-available-products">
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted small">
                                        <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
                                        <div>Chargement des produits...</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal: Ajouter / Modifier un élément (Catégorie, Famille, Sous-famille) -->
<div class="modal fade" id="nodeCrudModal" tabindex="-1" aria-labelledby="nodeCrudModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header bg-light py-2-5 px-3 border-bottom">
                <h6 class="modal-title fw-bold text-dark" id="nodeCrudModalLabel">Ajouter un élément</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-node-crud">
                <div class="modal-body p-3">
                    <input type="hidden" id="modal-node-id" value="">
                    <input type="hidden" id="modal-node-parent-id" value="">
                    <input type="hidden" id="modal-node-level" value="1">
                    
                    <div class="mb-3" id="modal-parent-info-wrapper" style="display: none;">
                        <label class="form-label text-muted small mb-1">Parent sélectionné</label>
                        <div class="form-control form-control-sm bg-light text-muted" id="modal-parent-name">—</div>
                    </div>

                    <div class="mb-3">
                        <label for="modal-node-name" class="form-label fw-semibold text-dark small mb-1">
                            Nom <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="modal-node-name" placeholder="Ex: Téléphonie" required autofocus>
                    </div>

                    <div class="mb-0">
                        <label for="modal-node-order" class="form-label text-muted small mb-1">Ordre de tri (optionnel)</label>
                        <input type="number" class="form-control form-control-sm" id="modal-node-order" placeholder="Ex: 10">
                    </div>
                </div>
                <div class="modal-footer bg-light py-2 px-3 border-top d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-sm btn-primary px-3" id="btn-save-node">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Segmentation Page Styles - Pixel accurate to mockup */
.py-1-5 {
    padding-top: 0.38rem;
    padding-bottom: 0.38rem;
}
.py-2-5 {
    padding-top: 0.65rem;
    padding-bottom: 0.65rem;
}
.tree-panel {
    border-color: #e2e8f0 !important;
    min-height: 250px;
}
.tree-list-scrollable {
    height: 210px;
    overflow-y: auto;
}
.tree-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #94a3b8;
    font-style: italic;
    padding: 1rem;
}
.btn-circle-add {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    transition: all 0.2s ease;
    cursor: pointer;
}
.btn-circle-blue {
    background-color: #1e70bf;
    color: #ffffff;
}
.btn-circle-blue:hover {
    background-color: #155a9c;
}
.btn-circle-grey {
    background-color: #e2e8f0;
    color: #94a3b8;
    cursor: not-allowed;
}
.btn-circle-grey.active {
    background-color: #1e70bf;
    color: #ffffff;
    cursor: pointer;
}
.badge-subtle-blue {
    background-color: #f0f7ff;
    color: #1e70bf;
    font-weight: 600;
    font-size: 0.78rem;
    padding: 0.35rem 0.65rem;
    border-radius: 6px;
    border: 1px solid #d0e4fc;
}
.badge-subtle-green {
    background-color: #edfbf2;
    color: #16a34a;
    font-weight: 600;
    font-size: 0.78rem;
    padding: 0.35rem 0.65rem;
    border-radius: 6px;
    border: 1px solid #c7f3d6;
}

/* Tree Item */
.tree-item {
    padding: 0.65rem 1rem;
    border-bottom: 1px solid #f1f5f9;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: background 0.15s ease, color 0.15s ease;
    color: #334155;
    font-size: 0.88rem;
}
.tree-item:last-child {
    border-bottom: none;
}
.tree-item:hover {
    background-color: #f8fafc;
}
.tree-item.active {
    background-color: #eef6ff;
    color: #1e70bf;
    font-weight: 600;
}
.tree-item-actions {
    display: none;
    align-items: center;
    gap: 3px;
}
.tree-item:hover .tree-item-actions {
    display: flex;
}
.tree-action-btn {
    background: transparent;
    border: none;
    padding: 2px 4px;
    color: #94a3b8;
    border-radius: 4px;
    transition: all 0.15s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.tree-action-btn:hover {
    color: #1e293b;
    background-color: #f1f5f9;
}
.tree-action-btn.btn-order {
    color: #64748b;
    font-size: 0.85rem;
}
.tree-action-btn.btn-order:hover {
    color: #1e70bf;
    background-color: #e0f2fe;
}
.tree-action-btn.btn-del:hover {
    color: #ef4444;
    background-color: #fee2e2;
}
.tree-drag-handle {
    cursor: grab;
    color: #cbd5e1;
    font-size: 0.95rem;
    transition: color 0.15s ease;
}
.tree-drag-handle:hover {
    color: #64748b;
}
.tree-drag-handle:active {
    cursor: grabbing;
}
.order-badge {
    font-size: 0.68rem !important;
    font-weight: 600;
    min-width: 22px;
    text-align: center;
    border-radius: 4px;
    color: #64748b;
    background-color: #f8fafc;
}
.tree-item.dragging-node {
    opacity: 0.35;
    background-color: #e2e8f0 !important;
}
.tree-item.tree-item-reorder-over-top {
    border-top: 2px solid #1e70bf !important;
}
.tree-item.tree-item-reorder-over-bottom {
    border-bottom: 2px solid #1e70bf !important;
}

/* Products Panels */
.products-panel {
    border-color: #e2e8f0 !important;
}
.table-scrollable {
    height: 420px;
    overflow-y: auto;
}
.segmentation-table {
    font-size: 0.84rem;
}
.segmentation-table thead th {
    background-color: #f8fafc;
    color: #64748b;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: none;
    padding: 0.65rem 0.85rem;
    border-bottom: 1px solid #e2e8f0;
    position: sticky;
    top: 0;
    z-index: 2;
}
.segmentation-table tbody tr {
    transition: background-color 0.15s ease;
    cursor: grab;
}
.segmentation-table tbody tr:active {
    cursor: grabbing;
}
.segmentation-table tbody tr.staged-moved {
    background-color: #f0fdf4 !important;
}
.segmentation-table tbody tr.staged-unassigned {
    background-color: #fffbeb !important;
}
.segmentation-table tbody td {
    padding: 0.65rem 0.85rem;
    border-bottom: 1px solid #f1f5f9;
    color: #334155;
}
.segmentation-table tbody tr:hover td {
    background-color: #f8fafc;
}
.product-ref {
    font-weight: 700;
    color: #0f172a;
}
.product-desc {
    color: #475569;
    max-width: 250px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.product-fam {
    color: #64748b;
    font-size: 0.78rem;
}
.product-price {
    font-weight: 500;
    color: #0f172a;
}

/* Empty State */
.empty-state-wrapper {
    color: #64748b;
}
.empty-state-icon {
    font-size: 2.2rem;
    color: #93c5fd;
    line-height: 1;
}

/* Search input */
.search-input-wrapper {
    width: 220px;
}
.search-input-wrapper .search-icon {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 13px;
    pointer-events: none;
}
.search-input-wrapper .search-field {
    border-radius: 6px;
    font-size: 0.82rem;
    border-color: #cbd5e1;
}

/* HTML5 Drag and Drop feedback */
.drop-target-active {
    outline: 2px dashed #1e70bf !important;
    background-color: #f0f7ff !important;
}
.dragging-row {
    opacity: 0.45;
    background-color: #e2e8f0 !important;
}
.quick-action-btn {
    opacity: 0;
    transition: opacity 0.15s ease;
}
.segmentation-table tbody tr:hover .quick-action-btn {
    opacity: 1;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    
    // Application State
    let hierarchyTree = [];
    let selectedCategory = null;   // Level 1
    let selectedFamily = null;     // Level 2
    let selectedSubFamily = null;  // Level 3
    
    let foundProducts = [];        // Products assigned in DB
    let availableProducts = [];
    let treeData = [];  
    // Staged changes: Map of equipment_id => { equipment, targetCategoryId, previousCategoryId, type: 'assign'|'unassign' }
    const stagedChanges = new Map();
    
    let searchDebounceTimer = null;

    // Toast helper
    const toastEl = document.getElementById('segmentationToast');
    const toastMessage = document.getElementById('toastMessage');
    const bsToast = toastEl ? new bootstrap.Toast(toastEl, { delay: 3000 }) : null;

    function showToast(message, isSuccess = true) {
        if (!toastMessage || !bsToast) return;
        toastMessage.innerHTML = isSuccess 
            ? `<i class="bi bi-check-circle-fill text-success"></i> ${message}`
            : `<i class="bi bi-exclamation-triangle-fill text-danger"></i> ${message}`;
        bsToast.show();
    }

    // Active target node (subfamily > family > category)
    function getActiveTargetNode() {
        return selectedSubFamily || selectedFamily || selectedCategory || null;
    }

    // Update Save button dirty state badge
    function updateSaveBadge() {
        const saveBadge = document.getElementById('save-badge-count');
        const count = stagedChanges.size;
        if (saveBadge) {
            if (count > 0) {
                saveBadge.textContent = count;
                saveBadge.classList.remove('d-none');
            } else {
                saveBadge.classList.add('d-none');
            }
        }
    }

    // Initial Load
    loadHierarchy();
    loadProducts();

    // ----------------------------------------------------------------
    // 1. Hierarchy Management (Categories, Families, Subfamilies)
    // ----------------------------------------------------------------

    async function loadHierarchy() {
        try {
            const res = await fetch('{{ route("equipment_categories.hierarchy_data") }}', {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();
            if (data.success) {
                hierarchyTree = data.tree || [];
                renderCategoriesList();
                
                // Update Badge Catégories count
                const catBadge = document.getElementById('badge-categories-count');
                if (catBadge) {
                    catBadge.textContent = `# ${hierarchyTree.length} disponibles`;
                }

                // If currently selected items exist, refresh them
                if (selectedCategory) {
                    selectedCategory = hierarchyTree.find(c => c.id === selectedCategory.id) || null;
                    renderFamiliesList();
                }
                if (selectedFamily && selectedCategory) {
                    selectedFamily = selectedCategory.children?.find(f => f.id === selectedFamily.id) || null;
                    renderSubFamiliesList();
                }
                if (selectedSubFamily && selectedFamily) {
                    selectedSubFamily = selectedFamily.children?.find(sf => sf.id === selectedSubFamily.id) || null;
                }
            }
        } catch (err) {
            console.error('Error loading hierarchy:', err);
        }
    }

    let draggedNodeData = null;

    function renderCategoriesList() {
        const container = document.getElementById('list-categories');
        if (!container) return;

        if (hierarchyTree.length === 0) {
            container.innerHTML = `<div class="tree-placeholder">Aucune catégorie définie</div>`;
            return;
        }

        let html = '';
        hierarchyTree.forEach((cat, index) => {
            const isSelected = selectedCategory && selectedCategory.id === cat.id;
            html += `
                <div class="tree-item ${isSelected ? 'active' : ''}" data-id="${cat.id}" data-level="1" draggable="true">
                    <div class="d-flex align-items-center flex-grow-1 overflow-hidden me-2">
                        <span class="tree-drag-handle me-2" title="Glisser pour réorganiser">
                            <i class="bi bi-grip-vertical"></i>
                        </span>
                        <span class="badge border px-1-5 py-0-5 me-2 order-badge" title="Ordre d'affichage">#${index + 1}</span>
                        <span class="tree-item-title text-truncate">${escapeHtml(cat.name)}</span>
                    </div>
                    <div class="tree-item-actions">
                        <button type="button" class="tree-action-btn btn-order ${index === 0 ? 'disabled opacity-25' : ''}" title="Monter" onclick="event.stopPropagation(); window.moveNodeOrder(${cat.id}, 1, -1)">
                            <i class="bi bi-chevron-up"></i>
                        </button>
                        <button type="button" class="tree-action-btn btn-order ${index === hierarchyTree.length - 1 ? 'disabled opacity-25' : ''}" title="Descendre" onclick="event.stopPropagation(); window.moveNodeOrder(${cat.id}, 1, 1)">
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <button type="button" class="tree-action-btn btn-edit ms-1" title="Modifier" onclick="event.stopPropagation(); window.openEditNode(${cat.id}, 1)">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button type="button" class="tree-action-btn btn-del" title="Supprimer" onclick="event.stopPropagation(); window.deleteNode(${cat.id}, 1)">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `;
        });
        container.innerHTML = html;

        setupTreeItemEvents(container, 1);
    }

    function selectCategory(id) {
        selectedCategory = hierarchyTree.find(c => c.id === id) || null;
        selectedFamily = null;
        selectedSubFamily = null;

        renderCategoriesList();
        renderFamiliesList();
        renderSubFamiliesList();

        // Enable add family button
        const btnAddFamily = document.getElementById('btn-add-family');
        if (btnAddFamily) {
            btnAddFamily.disabled = !selectedCategory;
            btnAddFamily.className = selectedCategory ? 'btn-circle-add btn-circle-blue' : 'btn-circle-add btn-circle-grey';
        }

        updateSegmentationBadge();
        loadProducts();
    }

    function renderFamiliesList() {
        const container = document.getElementById('list-families');
        const badge = document.getElementById('badge-families-count');
        if (!container) return;

        if (!selectedCategory) {
            container.innerHTML = `<div class="tree-placeholder">Sélectionnez une catégorie pour voir ses familles</div>`;
            if (badge) badge.classList.add('d-none');
            return;
        }

        const families = selectedCategory.children || [];
        if (badge) {
            badge.textContent = `# ${families.length} disponibles`;
            badge.classList.remove('d-none');
        }

        if (families.length === 0) {
            container.innerHTML = `<div class="tree-placeholder">Aucune famille dans cette catégorie</div>`;
            return;
        }

        let html = '';
        families.forEach((fam, index) => {
            const isSelected = selectedFamily && selectedFamily.id === fam.id;
            html += `
                <div class="tree-item ${isSelected ? 'active' : ''}" data-id="${fam.id}" data-level="2" draggable="true">
                    <div class="d-flex align-items-center flex-grow-1 overflow-hidden me-2">
                        <span class="tree-drag-handle me-2" title="Glisser pour réorganiser">
                            <i class="bi bi-grip-vertical"></i>
                        </span>
                        <span class="badge border px-1-5 py-0-5 me-2 order-badge" title="Ordre d'affichage">#${index + 1}</span>
                        <span class="tree-item-title text-truncate">${escapeHtml(fam.name)}</span>
                    </div>
                    <div class="tree-item-actions">
                        <button type="button" class="tree-action-btn btn-order ${index === 0 ? 'disabled opacity-25' : ''}" title="Monter" onclick="event.stopPropagation(); window.moveNodeOrder(${fam.id}, 2, -1)">
                            <i class="bi bi-chevron-up"></i>
                        </button>
                        <button type="button" class="tree-action-btn btn-order ${index === families.length - 1 ? 'disabled opacity-25' : ''}" title="Descendre" onclick="event.stopPropagation(); window.moveNodeOrder(${fam.id}, 2, 1)">
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <button type="button" class="tree-action-btn btn-edit ms-1" title="Modifier" onclick="event.stopPropagation(); window.openEditNode(${fam.id}, 2)">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button type="button" class="tree-action-btn btn-del" title="Supprimer" onclick="event.stopPropagation(); window.deleteNode(${fam.id}, 2)">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `;
        });
        container.innerHTML = html;

        setupTreeItemEvents(container, 2);
    }

    function selectFamily(id) {
        if (!selectedCategory) return;
        selectedFamily = (selectedCategory.children || []).find(f => f.id === id) || null;
        selectedSubFamily = null;

        renderFamiliesList();
        renderSubFamiliesList();

        // Enable add subfamily button
        const btnAddSubFamily = document.getElementById('btn-add-subfamily');
        if (btnAddSubFamily) {
            btnAddSubFamily.disabled = !selectedFamily;
            btnAddSubFamily.className = selectedFamily ? 'btn-circle-add btn-circle-blue' : 'btn-circle-add btn-circle-grey';
        }

        updateSegmentationBadge();
        loadProducts();
    }

    function renderSubFamiliesList() {
        const container = document.getElementById('list-subfamilies');
        const badge = document.getElementById('badge-subfamilies-count');
        if (!container) return;

        if (!selectedFamily) {
            container.innerHTML = `<div class="tree-placeholder">Sélectionnez une famille pour voir ses sous-familles</div>`;
            if (badge) badge.classList.add('d-none');
            return;
        }

        const subfamilies = selectedFamily.children || [];
        if (badge) {
            badge.textContent = `# ${subfamilies.length} disponibles`;
            badge.classList.remove('d-none');
        }

        if (subfamilies.length === 0) {
            container.innerHTML = `<div class="tree-placeholder">Aucune sous-famille dans cette famille</div>`;
            return;
        }

        let html = '';
        subfamilies.forEach((sf, index) => {
            const isSelected = selectedSubFamily && selectedSubFamily.id === sf.id;
            html += `
                <div class="tree-item ${isSelected ? 'active' : ''}" data-id="${sf.id}" data-level="3" draggable="true">
                    <div class="d-flex align-items-center flex-grow-1 overflow-hidden me-2">
                        <span class="tree-drag-handle me-2" title="Glisser pour réorganiser">
                            <i class="bi bi-grip-vertical"></i>
                        </span>
                        <span class="badge border px-1-5 py-0-5 me-2 order-badge" title="Ordre d'affichage">#${index + 1}</span>
                        <span class="tree-item-title text-truncate">${escapeHtml(sf.name)}</span>
                    </div>
                    <div class="tree-item-actions">
                        <button type="button" class="tree-action-btn btn-order ${index === 0 ? 'disabled opacity-25' : ''}" title="Monter" onclick="event.stopPropagation(); window.moveNodeOrder(${sf.id}, 3, -1)">
                            <i class="bi bi-chevron-up"></i>
                        </button>
                        <button type="button" class="tree-action-btn btn-order ${index === subfamilies.length - 1 ? 'disabled opacity-25' : ''}" title="Descendre" onclick="event.stopPropagation(); window.moveNodeOrder(${sf.id}, 3, 1)">
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <button type="button" class="tree-action-btn btn-edit ms-1" title="Modifier" onclick="event.stopPropagation(); window.openEditNode(${sf.id}, 3)">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button type="button" class="tree-action-btn btn-del" title="Supprimer" onclick="event.stopPropagation(); window.deleteNode(${sf.id}, 3)">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `;
        });
        container.innerHTML = html;

        setupTreeItemEvents(container, 3);
    }

    function setupTreeItemEvents(container, level) {
        container.querySelectorAll('.tree-item').forEach(el => {
            const id = parseInt(el.getAttribute('data-id'));

            // Click selection
            el.addEventListener('click', () => {
                if (level === 1) selectCategory(id);
                else if (level === 2) selectFamily(id);
                else if (level === 3) selectSubFamily(id);
            });

            // Product Drop target
            setupNodeDropTarget(el, id);

            // Node Reorder Drag & Drop
            el.addEventListener('dragstart', (e) => {
                // If dragging a product row, don't initiate node drag
                if (draggedEquipmentId) return;

                draggedNodeData = { id: id, level: level };
                el.classList.add('dragging-node');
                e.dataTransfer.setData('text/plain', JSON.stringify(draggedNodeData));
                e.dataTransfer.effectAllowed = 'move';
            });

            el.addEventListener('dragend', () => {
                draggedNodeData = null;
                el.classList.remove('dragging-node');
                document.querySelectorAll('.tree-item-reorder-over-top, .tree-item-reorder-over-bottom').forEach(node => {
                    node.classList.remove('tree-item-reorder-over-top', 'tree-item-reorder-over-bottom');
                });
            });

            el.addEventListener('dragover', (e) => {
                if (draggedNodeData && draggedNodeData.level === level && draggedNodeData.id !== id) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.dataTransfer.dropEffect = 'move';

                    const rect = el.getBoundingClientRect();
                    const midY = rect.top + rect.height / 2;
                    if (e.clientY < midY) {
                        el.classList.add('tree-item-reorder-over-top');
                        el.classList.remove('tree-item-reorder-over-bottom');
                    } else {
                        el.classList.add('tree-item-reorder-over-bottom');
                        el.classList.remove('tree-item-reorder-over-top');
                    }
                }
            });

            el.addEventListener('dragleave', () => {
                el.classList.remove('tree-item-reorder-over-top', 'tree-item-reorder-over-bottom');
            });

            el.addEventListener('drop', (e) => {
                if (draggedNodeData && draggedNodeData.level === level && draggedNodeData.id !== id) {
                    e.preventDefault();
                    e.stopPropagation();

                    const isTop = el.classList.contains('tree-item-reorder-over-top');
                    el.classList.remove('tree-item-reorder-over-top', 'tree-item-reorder-over-bottom');

                    reorderNodesArray(draggedNodeData.id, id, level, isTop);
                }
            });
        });
    }

    window.moveNodeOrder = async function (nodeId, level, direction) {
        let items = [];
        if (level === 1) {
            items = hierarchyTree;
        } else if (level === 2 && selectedCategory) {
            items = selectedCategory.children || [];
        } else if (level === 3 && selectedFamily) {
            items = selectedFamily.children || [];
        }

        const currentIndex = items.findIndex(item => item.id === nodeId);
        if (currentIndex === -1) return;

        const targetIndex = currentIndex + direction;
        if (targetIndex < 0 || targetIndex >= items.length) return;

        // Swap items in local array
        const temp = items[currentIndex];
        items[currentIndex] = items[targetIndex];
        items[targetIndex] = temp;

        // Update sort_order numbers
        const payload = items.map((item, idx) => {
            item.sort_order = idx + 1;
            return {
                id: item.id,
                sort_order: idx + 1
            };
        });

        // Re-render immediately
        if (level === 1) {
            renderCategoriesList();
        } else if (level === 2) {
            renderFamiliesList();
        } else if (level === 3) {
            renderSubFamiliesList();
        }

        // Persist to backend
        try {
            const res = await fetch('{{ route("equipment_categories.reorder_nodes") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ items: payload })
            });
            const data = await res.json();
            if (data.success) {
                showToast('Ordre mis à jour avec succès.', 'success');
            } else {
                showToast(data.message || 'Erreur lors de la mise à jour de l\'ordre.', 'danger');
            }
        } catch (err) {
            console.error('Error reordering nodes:', err);
            showToast('Erreur de communication.', 'danger');
        }
    };

    async function reorderNodesArray(sourceId, targetId, level, placeBefore) {
        let items = [];
        if (level === 1) items = hierarchyTree;
        else if (level === 2 && selectedCategory) items = selectedCategory.children || [];
        else if (level === 3 && selectedFamily) items = selectedFamily.children || [];

        const sourceIndex = items.findIndex(item => item.id === sourceId);
        const targetIndex = items.findIndex(item => item.id === targetId);

        if (sourceIndex === -1 || targetIndex === -1) return;

        const [movedItem] = items.splice(sourceIndex, 1);
        let insertIndex = items.findIndex(item => item.id === targetId);
        if (!placeBefore) {
            insertIndex += 1;
        }
        items.splice(insertIndex, 0, movedItem);

        const payload = items.map((item, idx) => {
            item.sort_order = idx + 1;
            return { id: item.id, sort_order: idx + 1 };
        });

        if (level === 1) renderCategoriesList();
        else if (level === 2) renderFamiliesList();
        else if (level === 3) renderSubFamiliesList();

        try {
            const res = await fetch('{{ route("equipment_categories.reorder_nodes") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ items: payload })
            });
            const data = await res.json();
            if (data.success) {
                showToast('Ordre mis à jour avec succès.', 'success');
            } else {
                showToast(data.message || 'Erreur lors de la mise à jour de l\'ordre.', 'danger');
            }
        } catch (err) {
            console.error('Error reordering nodes:', err);
            showToast('Erreur de communication.', 'danger');
        }
    }

    function selectSubFamily(id) {
        if (!selectedFamily) return;
        selectedSubFamily = (selectedFamily.children || []).find(sf => sf.id === id) || null;

        renderSubFamiliesList();
        updateSegmentationBadge();
        loadProducts();
    }

    function updateSegmentationBadge() {
        const badge = document.getElementById('badge-found-segmentation');
        if (!badge) return;

        const target = getActiveTargetNode();
        if (!target) {
            badge.textContent = '# Aucune segmentation définie';
        } else {
            let path = [];
            if (selectedCategory) path.push(selectedCategory.name);
            if (selectedFamily) path.push(selectedFamily.name);
            if (selectedSubFamily) path.push(selectedSubFamily.name);
            badge.textContent = `# ${path.join(' > ')}`;
        }
    }

    // ----------------------------------------------------------------
    // 2. Node CRUD Modals (Create / Edit / Delete)
    // ----------------------------------------------------------------

    const nodeModalEl = document.getElementById('nodeCrudModal');
    const nodeModal = nodeModalEl ? new bootstrap.Modal(nodeModalEl) : null;
    const formNode = document.getElementById('form-node-crud');

    // Add Buttons triggers
    document.getElementById('btn-add-category')?.addEventListener('click', () => openCreateNode(1));
    document.getElementById('btn-add-family')?.addEventListener('click', () => {
        if (selectedCategory) openCreateNode(2);
    });
    document.getElementById('btn-add-subfamily')?.addEventListener('click', () => {
        if (selectedFamily) openCreateNode(3);
    });

    function openCreateNode(level) {
        document.getElementById('modal-node-id').value = '';
        document.getElementById('modal-node-level').value = level;
        document.getElementById('modal-node-name').value = '';
        document.getElementById('modal-node-order').value = '';

        const parentInfo = document.getElementById('modal-parent-info-wrapper');
        const parentName = document.getElementById('modal-parent-name');
        const label = document.getElementById('nodeCrudModalLabel');

        if (level === 1) {
            label.textContent = 'Ajouter une catégorie';
            document.getElementById('modal-node-parent-id').value = '';
            parentInfo.style.display = 'none';
        } else if (level === 2) {
            label.textContent = 'Ajouter une famille';
            document.getElementById('modal-node-parent-id').value = selectedCategory?.id || '';
            parentName.textContent = selectedCategory?.name || '';
            parentInfo.style.display = 'block';
        } else if (level === 3) {
            label.textContent = 'Ajouter une sous-famille';
            document.getElementById('modal-node-parent-id').value = selectedFamily?.id || '';
            parentName.textContent = selectedFamily?.name || '';
            parentInfo.style.display = 'block';
        }

        nodeModal?.show();
    }

    window.openEditNode = function (id, level) {
        let node = null;
        if (level === 1) {
            node = hierarchyTree.find(c => c.id === id);
        } else if (level === 2) {
            node = selectedCategory?.children?.find(f => f.id === id);
        } else if (level === 3) {
            node = selectedFamily?.children?.find(sf => sf.id === id);
        }
        if (!node) return;

        document.getElementById('modal-node-id').value = node.id;
        document.getElementById('modal-node-level').value = level;
        document.getElementById('modal-node-parent-id').value = node.parent_id || '';
        document.getElementById('modal-node-name').value = node.name;
        document.getElementById('modal-node-order').value = node.sort_order || '';

        const label = document.getElementById('nodeCrudModalLabel');
        const parentInfo = document.getElementById('modal-parent-info-wrapper');
        label.textContent = level === 1 ? 'Modifier la catégorie' : (level === 2 ? 'Modifier la famille' : 'Modifier la sous-famille');
        parentInfo.style.display = 'none';

        nodeModal?.show();
    };

    formNode?.addEventListener('submit', async function (e) {
        e.preventDefault();
        const id = document.getElementById('modal-node-id').value;
        const parentId = document.getElementById('modal-node-parent-id').value || null;
        const name = document.getElementById('modal-node-name').value.trim();
        const sortOrder = document.getElementById('modal-node-order').value || null;

        if (!name) return;

        try {
            const url = id 
                ? `/equipment_categories/${id}/crud-node`
                : `{{ route('equipment_categories.store_node') }}`;
            const method = id ? 'PUT' : 'POST';

            const res = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    name: name,
                    parent_id: parentId,
                    sort_order: sortOrder
                })
            });

            const data = await res.json();
            if (data.success) {
                nodeModal?.hide();
                showToast(data.message || 'Enregistré avec succès.');
                await loadHierarchy();
            } else {
                alert(data.message || 'Une erreur est survenue.');
            }
        } catch (err) {
            console.error('Error saving node:', err);
            alert('Erreur lors de la sauvegarde.');
        }
    });

    window.deleteNode = async function (id, level) {
        const itemType = level === 1 ? 'cette catégorie' : (level === 2 ? 'cette famille' : 'cette sous-famille');
        if (!confirm(`Êtes-vous sûr de vouloir supprimer ${itemType} ? Tous ses éléments enfants seront également supprimés et les produits seront détachés.`)) {
            return;
        }

        try {
            const res = await fetch(`/equipment_categories/${id}/crud-node`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            const data = await res.json();
            if (data.success) {
                showToast(data.message || 'Suppression effectuée.');
                if (selectedCategory && selectedCategory.id === id) selectedCategory = null;
                if (selectedFamily && selectedFamily.id === id) selectedFamily = null;
                if (selectedSubFamily && selectedSubFamily.id === id) selectedSubFamily = null;
                await loadHierarchy();
                await loadProducts();
            } else {
                alert(data.message || 'Erreur lors de la suppression.');
            }
        } catch (err) {
            console.error('Error deleting node:', err);
        }
    };

    // ----------------------------------------------------------------
    // 3. Products Loading & Rendering
    // ----------------------------------------------------------------

    document.getElementById('input-search-available')?.addEventListener('input', function () {
        clearTimeout(searchDebounceTimer);
        searchDebounceTimer = setTimeout(() => {
            loadProducts(true); // Search only available
        }, 250);
    });

    async function loadProducts(searchOnly = false) {
        const target = getActiveTargetNode();
        const search = document.getElementById('input-search-available')?.value.trim() || '';

        const params = new URLSearchParams({
            category_id: target ? target.id : '',
            search: search
        });

        try {
            const res = await fetch(`{{ route('equipment_categories.products_data') }}?${params.toString()}`, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();
            if (data.success) {
                if (!searchOnly) {
                    foundProducts = data.found_products || [];
                }
                availableProducts = data.available_products || [];

                // Re-apply staged changes in memory
                applyStagedChangesToLocalState();

                renderFoundProductsTable();
                renderAvailableProductsTable(data.total_available);
            }
        } catch (err) {
            console.error('Error loading products:', err);
        }
    }

    function applyStagedChangesToLocalState() {
        const target = getActiveTargetNode();
        const targetId = target ? target.id : null;

        stagedChanges.forEach((change, eqId) => {
            if (change.targetCategoryId === targetId && targetId !== null) {
                // Moved into active category
                if (!foundProducts.some(p => p.id === eqId)) {
                    foundProducts.unshift(change.equipment);
                }
            } else if (change.previousCategoryId === targetId && change.targetCategoryId !== targetId) {
                // Moved out of active category
                foundProducts = foundProducts.filter(p => p.id !== eqId);
            }
        });
    }

    function renderFoundProductsTable() {
        const tbody = document.getElementById('tbody-found-products');
        if (!tbody) return;

        const target = getActiveTargetNode();

        if (!target || foundProducts.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="p-0">
                        <div class="empty-state-wrapper py-5 text-center">
                            <div class="empty-state-icon mb-3">
                                <i class="bi bi-cart-x"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Aucun produit trouvé.</h6>
                            <p class="text-muted small mb-0">Ajustez les filtres pour plus de résultats.</p>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';
        foundProducts.forEach(prod => {
            const isStaged = stagedChanges.has(prod.id);
            html += `
                <tr draggable="true" data-equipment-id="${prod.id}" class="${isStaged ? 'staged-moved' : ''}">
                    <td class="product-ref">
                        <div class="d-flex align-items-center gap-2">
                            <span>${escapeHtml(prod.reference)}</span>
                            <button type="button" class="btn btn-xs btn-outline-danger p-0 px-1 quick-action-btn" title="Désassigner" onclick="window.unassignProductQuick(${prod.id})">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </td>
                    <td class="product-desc" title="${escapeHtml(prod.description)}">
                        ${escapeHtml(prod.description)}
                    </td>
                    <td class="product-fam">
                        ${escapeHtml(prod.family || '—')}
                    </td>
                    <td class="product-price text-end pe-3">
                        ${escapeHtml(prod.price_formatted)}
                    </td>
                </tr>
            `;
        });
        tbody.innerHTML = html;

        setupDraggableRows(tbody, 'found');
    }

    function renderAvailableProductsTable(totalAvailable = 0) {
        const tbody = document.getElementById('tbody-available-products');
        const badge = document.getElementById('badge-available-count');
        if (!tbody) return;

        if (badge) {
            badge.innerHTML = `<i class="bi bi-check-circle-fill me-1"></i>${totalAvailable} disponibles`;
        }

        if (availableProducts.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted small">
                        Aucun produit disponible trouvé
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';
        availableProducts.forEach(prod => {
            const isStaged = stagedChanges.has(prod.id);
            html += `
                <tr draggable="true" data-equipment-id="${prod.id}" class="${isStaged ? 'staged-moved' : ''}">
                    <td class="product-ref">
                        <div class="d-flex align-items-center gap-2">
                            <span>${escapeHtml(prod.reference)}</span>
                            <button type="button" class="btn btn-xs btn-outline-primary p-0 px-1 quick-action-btn" title="Assigner à la sélection active" onclick="window.assignProductQuick(${prod.id})">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </td>
                    <td class="product-desc" title="${escapeHtml(prod.description)}">
                        ${escapeHtml(prod.description)}
                    </td>
                    <td class="product-fam">
                        ${escapeHtml(prod.family || '—')}
                    </td>
                    <td class="product-price text-end pe-3">
                        ${escapeHtml(prod.price_formatted)}
                    </td>
                </tr>
            `;
        });
        tbody.innerHTML = html;

        setupDraggableRows(tbody, 'available');
    }

    // ----------------------------------------------------------------
    // 4. HTML5 Drag and Drop & Interactive Movements
    // ----------------------------------------------------------------

    let draggedEquipmentId = null;
    let draggedSource = null;

    function setupDraggableRows(container, source) {
        container.querySelectorAll('tr[draggable="true"]').forEach(row => {
            row.addEventListener('dragstart', function (e) {
                draggedEquipmentId = parseInt(this.getAttribute('data-equipment-id'));
                draggedSource = source;
                this.classList.add('dragging-row');
                e.dataTransfer.setData('text/plain', draggedEquipmentId);
                e.dataTransfer.effectAllowed = 'move';
            });

            row.addEventListener('dragend', function () {
                this.classList.remove('dragging-row');
                document.querySelectorAll('.drop-target-active').forEach(el => el.classList.remove('drop-target-active'));
            });
        });
    }

    // Drop Target: "Produits trouvés" Zone
    const zoneFound = document.getElementById('zone-found-products');
    if (zoneFound) {
        zoneFound.addEventListener('dragover', function (e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            this.classList.add('drop-target-active');
        });

        zoneFound.addEventListener('dragleave', function () {
            this.classList.remove('drop-target-active');
        });

        zoneFound.addEventListener('drop', function (e) {
            e.preventDefault();
            this.classList.remove('drop-target-active');
            const eqId = parseInt(e.dataTransfer.getData('text/plain') || draggedEquipmentId);
            if (!eqId) return;

            const target = getActiveTargetNode();
            if (!target) {
                alert('Veuillez d\'abord sélectionner une catégorie, famille ou sous-famille.');
                return;
            }

            stageAssignEquipment(eqId, target.id);
        });
    }

    // Drop Target: "Produits disponibles" Zone (Unassign)
    const zoneAvailable = document.getElementById('zone-available-products');
    if (zoneAvailable) {
        zoneAvailable.addEventListener('dragover', function (e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            this.classList.add('drop-target-active');
        });

        zoneAvailable.addEventListener('dragleave', function () {
            this.classList.remove('drop-target-active');
        });

        zoneAvailable.addEventListener('drop', function (e) {
            e.preventDefault();
            this.classList.remove('drop-target-active');
            const eqId = parseInt(e.dataTransfer.getData('text/plain') || draggedEquipmentId);
            if (!eqId) return;

            stageUnassignEquipment(eqId);
        });
    }

    // Setup node element in tree as drop target
    function setupNodeDropTarget(el, categoryId) {
        el.addEventListener('dragover', function (e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            this.classList.add('drop-target-active');
        });

        el.addEventListener('dragleave', function () {
            this.classList.remove('drop-target-active');
        });

        el.addEventListener('drop', function (e) {
            e.preventDefault();
            this.classList.remove('drop-target-active');
            const eqId = parseInt(e.dataTransfer.getData('text/plain') || draggedEquipmentId);
            if (!eqId) return;

            stageAssignEquipment(eqId, categoryId);
        });
    }

    // Quick Action Functions
    window.assignProductQuick = function (eqId) {
        const target = getActiveTargetNode();
        if (!target) {
            alert('Veuillez d\'abord sélectionner une catégorie, famille ou sous-famille pour y assigner le produit.');
            return;
        }
        stageAssignEquipment(eqId, target.id);
    };

    window.unassignProductQuick = function (eqId) {
        stageUnassignEquipment(eqId);
    };

    function stageAssignEquipment(eqId, targetCategoryId) {
        let eq = availableProducts.find(p => p.id === eqId) || foundProducts.find(p => p.id === eqId);
        if (!eq) return;

        const previousCategoryId = eq.category_id;

        // Clone eq and update family label
        const targetCategory = findCategoryInTree(targetCategoryId);
        const updatedEq = {
            ...eq,
            category_id: targetCategoryId,
            family: targetCategory ? targetCategory.name : eq.family
        };

        stagedChanges.set(eqId, {
            equipment: updatedEq,
            targetCategoryId: targetCategoryId,
            previousCategoryId: previousCategoryId,
            type: 'assign'
        });

        updateSaveBadge();
        applyStagedChangesToLocalState();
        renderFoundProductsTable();
        renderAvailableProductsTable(availableProducts.length);
        showToast(`Produit ${escapeHtml(eq.reference)} déplacé (en attente de sauvegarde).`);
    }

    function stageUnassignEquipment(eqId) {
        let eq = foundProducts.find(p => p.id === eqId) || availableProducts.find(p => p.id === eqId);
        if (!eq) return;

        const previousCategoryId = eq.category_id;

        const updatedEq = {
            ...eq,
            category_id: null,
            family: ''
        };

        stagedChanges.set(eqId, {
            equipment: updatedEq,
            targetCategoryId: null,
            previousCategoryId: previousCategoryId,
            type: 'unassign'
        });

        updateSaveBadge();
        foundProducts = foundProducts.filter(p => p.id !== eqId);
        renderFoundProductsTable();
        renderAvailableProductsTable(availableProducts.length);
        showToast(`Produit ${escapeHtml(eq.reference)} retiré (en attente de sauvegarde).`);
    }

    function findCategoryInTree(id) {
        let found = null;
        function search(nodes) {
            for (let node of nodes) {
                if (node.id === id) {
                    found = node;
                    return;
                }
                if (node.children && node.children.length > 0) {
                    search(node.children);
                }
            }
        }
        search(hierarchyTree);
        return found;
    }

    // ----------------------------------------------------------------
    // 5. Sauvegarder & Restaurer Actions
    // ----------------------------------------------------------------

    document.getElementById('btn-save-changes')?.addEventListener('click', async function () {
        if (stagedChanges.size === 0) {
            showToast('Aucun changement à sauvegarder.', true);
            return;
        }

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status"></span> Sauvegarde...`;

        const assignments = [];
        stagedChanges.forEach((change, eqId) => {
            assignments.push({
                equipment_id: eqId,
                category_id: change.targetCategoryId
            });
        });

        try {
            const res = await fetch('{{ route("equipment_categories.save_assignments") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ assignments: assignments })
            });

            const data = await res.json();
            if (data.success) {
                stagedChanges.clear();
                updateSaveBadge();
                showToast(data.message || 'Modifications sauvegardées avec succès !', true);
                await loadHierarchy();
                await loadProducts();
            } else {
                alert(data.message || 'Erreur lors de la sauvegarde.');
            }
        } catch (err) {
            console.error('Error saving assignments:', err);
            alert('Erreur réseau lors de la sauvegarde.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = `<i class="bi bi-floppy"></i> <span>Sauvegarder</span> <span class="badge bg-white text-primary rounded-pill d-none" id="save-badge-count">0</span>`;
            updateSaveBadge();
        }
    });

    document.getElementById('btn-restore-changes')?.addEventListener('click', async function () {
        if (stagedChanges.size > 0) {
            if (!confirm('Voulez-vous annuler toutes les modifications non enregistrées et restaurer les données d\'origine ?')) {
                return;
            }
        }
        stagedChanges.clear();
        updateSaveBadge();
        showToast('Données restaurées.', true);
        await loadHierarchy();
        await loadProducts();
    });

    // Helper: Escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.toString().replace(/[&<>"']/g, m => map[m]);
    }
});
</script>
@endsection
