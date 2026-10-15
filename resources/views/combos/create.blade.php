@extends('layouts.app')
@section('portal-module', 'combos')
@section('portal-page', 'create')
@section('portal-kind', 'form')
@section('portal-family', 'catalog')
@section('portal-icon', 'tags')


@section('title', __('locale.combo.create_combo') ?? 'Créer un choix de combo')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('combos.index') }}" class="btn btn-sm btn-outline-secondary py-1 px-2">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="mb-0 fs-4">{{ __('locale.combo.create_combo') ?? 'Nouveau choix de combo' }}</h1>
    </div>
@endsection

@section('page-actions')
    <button type="submit" form="comboForm" class="btn btn-primary">
        <i class="bi bi-check2-circle me-1"></i> {{ __('locale.save') ?? 'Enregistrer' }}
    </button>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Veuillez corriger les erreurs suivantes :</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form id="comboForm" method="POST" action="{{ route('combos.store') }}">
            @csrf

            <div class="row g-4">
                <!-- Left: General information -->
                <div class="col-lg-4">
                    <div class="module-form-card card card-premium shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h5 class="card-title mb-0 fw-bold text-dark">
                                <i class="bi bi-info-circle text-primary me-2"></i>Informations générales
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">
                                    {{ __('locale.combo.name') ?? 'Nom du choix de combo' }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Ex: Choix Écran, Choix Clavier..." required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="company_id" class="form-label fw-semibold">{{ __('locale.company') ?? 'Société' }}</label>
                                <select class="form-select @error('company_id') is-invalid @enderror" id="company_id" name="company_id">
                                    <option value="">{{ __('locale.all_companies') ?? 'Toutes les sociétés (Global)' }}</option>
                                    @foreach($companies as $comp)
                                        <option value="{{ $comp->id }}" {{ old('company_id') == $comp->id ? 'selected' : '' }}>
                                            {{ $comp->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text small">Laisser vide pour rendre ce combo disponible dans tous les équipements multi-sociétés.</div>
                                @error('company_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="sequence" class="form-label fw-semibold">{{ __('locale.combo.sequence') ?? 'Ordre d\'affichage' }}</label>
                                <input type="number" class="form-control @error('sequence') is-invalid @enderror" id="sequence" name="sequence" value="{{ old('sequence', 0) }}" min="0">
                                @error('sequence')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-4">

                            <!-- Base price summary -->
                            <div class="p-3 bg-light rounded-3 border">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-muted small fw-semibold">Options configurées:</span>
                                    <span class="fw-bold" id="itemCountDisplay">0</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small fw-semibold">Prix de base calculé:</span>
                                    <span class="fw-bold fs-5 text-primary" id="basePriceDisplay">0,00 €</span>
                                </div>
                                <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">
                                    Le prix de base est le prix catalogue minimum parmi les options sélectionnées.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Options builder -->
                <div class="col-lg-8">
                    <div class="module-form-card card card-premium shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h5 class="card-title mb-0 fw-bold text-dark">
                                    <i class="bi bi-list-check text-primary me-2"></i>{{ __('locale.combo.options') ?? 'Articles & Options du Combo' }} <span class="text-danger">*</span>
                                </h5>
                                <small class="text-muted">Sélectionnez les articles qui feront partie de ce groupe de choix.</small>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Search & Add equipment section -->
                            <div class="p-3 bg-light rounded-3 border mb-3">
                                <label class="form-label fw-semibold mb-2">
                                    <i class="bi bi-search me-1"></i>Ajouter un article depuis le catalogue
                                </label>
                                <div class="row g-2">
                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <input type="text" id="equipmentSearchInput" class="form-control" placeholder="Rechercher par référence, nom d'article...">
                                            <button class="btn btn-outline-secondary" type="button" id="btnSearchEquipments">
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-outline-primary w-100" id="btnOpenCatalogModal" data-bs-toggle="modal" data-bs-target="#catalogModal">
                                            <i class="bi bi-grid-3x3-gap me-1"></i>Catalogue
                                        </button>
                                    </div>
                                </div>
                                <!-- Quick search autocomplete container -->
                                <div id="quickSearchResults" class="list-group shadow-sm mt-1 position-absolute" style="z-index: 1050; max-height: 280px; overflow-y: auto; display: none; width: calc(100% - 2rem);"></div>
                            </div>

                            <!-- Selected items table -->
                            <div class="table-responsive">
                                <table class="table table-hover align-middle border mb-0" id="comboItemsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;" class="text-center">#</th>
                                            <th>Article / Matériel</th>
                                            <th style="width: 140px;" class="text-end">Prix catalogue</th>
                                            <th style="width: 160px;" class="text-end">
                                                Supplément (€)
                                                <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip" title="Prix supplémentaire à ajouter au prix de base"></i>
                                            </th>
                                            <th style="width: 80px;" class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="comboItemsTbody">
                                        <!-- Dynamic items added here -->
                                    </tbody>
                                </table>
                            </div>

                            <div id="noItemsPlaceholder" class="text-center py-5 border border-dashed rounded-3 mt-3 bg-light">
                                <i class="bi bi-box-arrow-in-down fs-1 text-secondary d-block mb-2"></i>
                                <p class="fw-semibold text-dark mb-1">Aucun article sélectionné</p>
                                <p class="text-muted small mb-0">Recherchez un article ci-dessus ou parcourez le catalogue pour ajouter des options à ce combo.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Catalog Picker -->
<div class="modal fade" id="catalogModal" tabindex="-1" aria-labelledby="catalogModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="catalogModalLabel">
                    <i class="bi bi-grid-3x3-gap text-primary me-2"></i>Sélectionner des articles du catalogue
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" id="modalCatalogSearch" class="form-control" placeholder="Filtrer les articles...">
                </div>
                <div class="table-responsive" style="max-height: 400px;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Référence</th>
                                <th>Nom</th>
                                <th class="text-end">Prix</th>
                                <th class="text-center" style="width: 100px;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="modalCatalogTbody">
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                                    Chargement du catalogue...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tbody = document.getElementById('comboItemsTbody');
    const placeholder = document.getElementById('noItemsPlaceholder');
    const searchInput = document.getElementById('equipmentSearchInput');
    const quickResults = document.getElementById('quickSearchResults');
    const itemCountDisplay = document.getElementById('itemCountDisplay');
    const basePriceDisplay = document.getElementById('basePriceDisplay');

    let selectedItems = new Map();

    function updateSummary() {
        const count = selectedItems.size;
        itemCountDisplay.textContent = count;

        if (count === 0) {
            placeholder.style.display = 'block';
            basePriceDisplay.textContent = '0,00 €';
            return;
        }

        placeholder.style.display = 'none';

        let minPrice = null;
        selectedItems.forEach(item => {
            const p = parseFloat(item.price) || 0;
            if (minPrice === null || p < minPrice) {
                minPrice = p;
            }
        });

        basePriceDisplay.textContent = (minPrice !== null ? minPrice.toFixed(2).replace('.', ',') : '0,00') + ' €';
    }

    function renderRow(item) {
        const tr = document.createElement('tr');
        tr.dataset.id = item.id;
        tr.innerHTML = `
            <td class="text-center text-muted sequence-index">${tbody.children.length + 1}</td>
            <td>
                <span class="badge bg-light text-dark border font-monospace me-1">${escapeHtml(item.reference)}</span>
                <span class="fw-semibold text-dark">${escapeHtml(item.name)}</span>
                ${item.subtitle ? `<small class="text-muted d-block">${escapeHtml(item.subtitle)}</small>` : ''}
                <input type="hidden" name="items[${item.id}][equipment_id]" value="${item.id}">
                <input type="hidden" name="items[${item.id}][sequence]" class="input-sequence" value="${tbody.children.length}">
            </td>
            <td class="text-end fw-semibold text-muted item-catalog-price" data-price="${item.price}">
                ${parseFloat(item.price).toFixed(2).replace('.', ',')} €
            </td>
            <td class="text-end">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">+</span>
                    <input type="number" step="0.01" min="0" name="items[${item.id}][extra_price]" class="form-control text-end fw-bold input-extra-price" value="${item.extra_price || '0.00'}">
                    <span class="input-group-text">€</span>
                </div>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-item" title="Retirer">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;

        tr.querySelector('.btn-remove-item').addEventListener('click', function () {
            selectedItems.delete(item.id);
            tr.remove();
            reindex();
            updateSummary();
        });

        tbody.appendChild(tr);
        reindex();
        updateSummary();
    }

    function reindex() {
        Array.from(tbody.children).forEach((tr, idx) => {
            const seqEl = tr.querySelector('.sequence-index');
            const seqInput = tr.querySelector('.input-sequence');
            if (seqEl) seqEl.textContent = idx + 1;
            if (seqInput) seqInput.value = idx;
        });
    }

    function addItem(item) {
        if (selectedItems.has(item.id)) {
            return;
        }
        item.extra_price = item.extra_price || '0.00';
        selectedItems.set(item.id, item);
        renderRow(item);
    }

    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>'"]/g, tag => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;'
        }[tag] || tag));
    }

    // Quick search logic
    let debounceTimer;
    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        const q = searchInput.value.trim();
        if (q.length < 2) {
            quickResults.style.display = 'none';
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`{{ route('combos.available-equipments') }}?search=${encodeURIComponent(q)}`)
                .then(res => res.json())
                .then(data => {
                    quickResults.innerHTML = '';
                    if (!data.items || data.items.length === 0) {
                        quickResults.innerHTML = '<div class="list-group-item text-muted small">Aucun article trouvé</div>';
                    } else {
                        data.items.forEach(it => {
                            const isAdded = selectedItems.has(it.id);
                            const a = document.createElement('a');
                            a.href = 'javascript:void(0);';
                            a.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2' + (isAdded ? ' disabled bg-light' : '');
                            a.innerHTML = `
                                <div>
                                    <span class="badge bg-light text-dark border font-monospace me-1">${escapeHtml(it.reference)}</span>
                                    <strong>${escapeHtml(it.name)}</strong>
                                    ${it.subtitle ? `<small class="text-muted d-block">${escapeHtml(it.subtitle)}</small>` : ''}
                                </div>
                                <div class="text-end">
                                    <span class="fw-semibold me-2">${it.price_formatted}</span>
                                    ${isAdded ? '<span class="badge bg-secondary">Déjà ajouté</span>' : '<button class="btn btn-xs btn-primary btn-sm py-0 px-2"><i class="bi bi-plus"></i></button>'}
                                </div>
                            `;
                            if (!isAdded) {
                                a.addEventListener('click', function (e) {
                                    e.preventDefault();
                                    addItem(it);
                                    searchInput.value = '';
                                    quickResults.style.display = 'none';
                                });
                            }
                            quickResults.appendChild(a);
                        });
                    }
                    quickResults.style.display = 'block';
                });
        }, 250);
    });

    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !quickResults.contains(e.target)) {
            quickResults.style.display = 'none';
        }
    });

    // Catalog modal logic
    const modalTbody = document.getElementById('modalCatalogTbody');
    const modalSearch = document.getElementById('modalCatalogSearch');
    let modalItems = [];

    function loadModalCatalog(searchQuery = '') {
        modalTbody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-muted"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Chargement...</td></tr>';
        fetch(`{{ route('combos.available-equipments') }}?search=${encodeURIComponent(searchQuery)}`)
            .then(res => res.json())
            .then(data => {
                modalItems = data.items || [];
                renderModalCatalog();
            });
    }

    function renderModalCatalog() {
        modalTbody.innerHTML = '';
        if (modalItems.length === 0) {
            modalTbody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-muted">Aucun article trouvé</td></tr>';
            return;
        }

        modalItems.forEach(it => {
            const isAdded = selectedItems.has(it.id);
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td><span class="badge bg-light text-secondary border font-monospace">${escapeHtml(it.reference)}</span></td>
                <td>
                    <div class="fw-semibold text-dark">${escapeHtml(it.name)}</div>
                    ${it.subtitle ? `<small class="text-muted">${escapeHtml(it.subtitle)}</small>` : ''}
                </td>
                <td class="text-end fw-semibold">${it.price_formatted}</td>
                <td class="text-center">
                    ${isAdded 
                        ? '<span class="badge bg-secondary">Ajouté</span>' 
                        : `<button type="button" class="btn btn-sm btn-outline-primary btn-modal-add" data-id="${it.id}"><i class="bi bi-plus-lg"></i></button>`
                    }
                </td>
            `;
            const btn = tr.querySelector('.btn-modal-add');
            if (btn) {
                btn.addEventListener('click', function () {
                    addItem(it);
                    renderModalCatalog();
                });
            }
            modalTbody.appendChild(tr);
        });
    }

    document.getElementById('catalogModal').addEventListener('shown.bs.modal', function () {
        loadModalCatalog(modalSearch.value);
    });

    let modalDebounce;
    modalSearch.addEventListener('input', function () {
        clearTimeout(modalDebounce);
        modalDebounce = setTimeout(() => {
            loadModalCatalog(modalSearch.value);
        }, 300);
    });

    // Form submit validation
    document.getElementById('comboForm').addEventListener('submit', function (e) {
        if (selectedItems.size === 0) {
            e.preventDefault();
            alert('Veuillez ajouter au moins un article dans ce choix de combo.');
        }
    });
});
</script>
@endpush
@endsection
