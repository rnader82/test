@php
    $lineData = old(
        'lines',
        $lines instanceof \Illuminate\Support\Collection
            ? $lines
                ->map(
                    fn($line) => [
                        'position' => $line->position,
                        'type_ligne' => $line->type_ligne ?? 'Article',
                        'code_article' => $line->code_article,
                        'equipment_id' => $line->equipment_id,
                        'libelle' => $line->libelle,
                        'qte' => $line->qte,
                        'pu_ht' => $line->pu_ht,
                        'remise_ligne' => $line->remise_ligne ?? 0,
                        'remise_2' => $line->remise_2 ?? 0,
                        'chapter_id' => $line->chapter_id,
                        'chapter_title' => $line->chapter_title,
                        'is_option' => $line->is_option ?? false,
                        'montant_ht' => $line->montant_ht ?? 0,
                    ],
                )
                ->toArray()
            : [],
    );

    $predefinedReserves = $reserves ?? \App\Models\DevisReserve::active()->ordered()->get();
    $isCreate = empty($template->id);
@endphp

<!-- Header Banner Panel (Exact replica of Modèle de devis screenshot) -->
<div class="card card-premium p-4 mb-4 shadow-sm border-0 bg-white">
    <div class="row align-items-center">
        <!-- Top Left Header Info -->
        <div class="col-md-5">
            <h2 class="mb-1 fw-bold text-dark fs-4" id="template-header-title">
                {{ $template->name ?: 'Modèle de devis' }}
            </h2>
            <div class="small text-muted mb-2">
                <span class="d-inline-block me-3">
                    <strong>Catégorie :</strong> 
                    <span id="header-cat-label" class="text-secondary">{{ optional($template->category)->name ?? '(non spécifié)' }}</span>
                </span>
                <span class="d-inline-block">
                    <strong>Famille :</strong> 
                    <span id="header-fam-label" class="text-secondary">{{ optional($template->family)->name ?? '(non spécifié)' }}</span>
                </span>
            </div>
            <!-- Action Badges Row -->
            <div class="mt-2 d-flex align-items-center gap-2 flex-wrap">
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-bold bg-white border-light text-secondary d-inline-flex align-items-center gap-2" id="btn-toggle-port" data-bs-toggle="collapse" data-bs-target="#port-collapse">
                    <i class="bi bi-truck text-primary fs-6"></i>
                    <span>Port</span>
                    <span class="badge bg-primary text-white rounded-circle" id="port-badge">{{ $template->port_ht > 0 ? '1' : '0' }}</span>
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-bold bg-white border-light text-secondary d-inline-flex align-items-center gap-2" id="btn-toggle-note" data-bs-toggle="collapse" data-bs-target="#note-collapse">
                    <i class="bi bi-file-earmark-text text-secondary fs-6"></i>
                    <span>Note</span>
                </button>
                <div class="d-flex align-items-center gap-2 ms-2 text-muted small">
                    <span>Buffer</span>
                    <i class="bi bi-files cursor-pointer" id="btn-copy-buffer" title="Copier la sélection"></i>
                    <i class="bi bi-list-task cursor-pointer" id="btn-view-buffer" title="Coller le buffer"></i>
                    <span class="badge bg-primary text-white rounded-circle" id="buffer-badge">0</span>
                    <i class="bi bi-x-lg text-danger cursor-pointer" id="btn-clear-buffer" style="font-size: 0.75rem;" title="Vider le buffer"></i>
                </div>
            </div>
        </div>

        <!-- Center: Tabs Navigation Pill matching screenshot -->
        <div class="col-md-3 text-center my-3 my-md-0">
            <ul class="nav nav-pills justify-content-center border-0" id="templateFormTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $isCreate ? 'active' : '' }} px-3 py-2 fw-bold" id="info-tab" data-bs-toggle="tab" data-bs-target="#tab-info" type="button" role="tab">
                        <i class="bi bi-info-circle me-1"></i> Informations
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ !$isCreate ? 'active' : '' }} px-3 py-2 fw-bold" id="produits-tab" data-bs-toggle="tab" data-bs-target="#tab-produits" type="button" role="tab">
                        <i class="bi bi-list-task me-1"></i> Produits
                    </button>
                </li>
            </ul>
        </div>

        <!-- Top Right Totals & Discount inputs -->
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <div class="d-flex flex-column align-items-md-end gap-1">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-0" style="font-size: 0.75rem;">Total HT</span>
                    <h3 class="text-primary fw-bold mb-0 fs-3" id="header-total-ht">
                        {{ number_format((float) ($template->total_ht ?? 0), 2, ',', ' ') }} €
                    </h3>
                </div>
                <!-- Remise stack -->
                <div class="text-md-end border-top pt-2 w-100" style="max-width: 220px;">
                    <span class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 0.7rem;">Remise</span>
                    <div class="d-flex flex-column gap-1">
                        <div class="input-group input-group-sm">
                            <input type="number" step="0.01" name="remise_pied" id="remise_pied" value="{{ old('remise_pied', $template->remise_pied ?? 0) }}" class="form-control text-end fw-semibold text-dark">
                            <span class="input-group-text bg-light text-muted">€</span>
                        </div>
                        <div class="input-group input-group-sm">
                            <input type="number" step="0.0001" name="remise_pied_percent" id="remise_pied_percent" value="{{ old('remise_pied_percent', $template->remise_pied_percent ?? 0) }}" class="form-control text-end fw-semibold text-dark">
                            <span class="input-group-text bg-light text-muted">%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Collapsible Port Area -->
<div class="collapse mb-4" id="port-collapse">
    <div class="card card-premium p-4 shadow-sm border-0">
        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-truck text-primary me-2"></i>Frais de Port du Modèle</h6>
        <div class="row g-3">
            <div class="col-md-4">
                <label for="port_ht" class="form-label small text-muted">Montant Port HT (€)</label>
                <div class="input-group">
                    <input type="number" step="0.01" name="port_ht" id="port_ht" class="form-control" value="{{ old('port_ht', $template->port_ht ?? 0) }}">
                    <span class="input-group-text">€</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Collapsible Internal Notes Area -->
<div class="collapse mb-4" id="note-collapse">
    <div class="card card-premium p-4 shadow-sm border-0">
        <label for="internal_note" class="form-label small fw-bold text-uppercase text-muted">Notes Internes du Modèle</label>
        <textarea name="internal_note" id="internal_note" class="form-control" rows="3" placeholder="Écrivez vos notes internes pour ce modèle...">{{ old('internal_note', $template->internal_note) }}</textarea>
    </div>
</div>

<!-- Tabs Content Area -->
<div class="tab-content bg-transparent p-0 border-0" id="templateTabsContent">
    
    <!-- TAB 1: Informations -->
    <div class="tab-pane fade {{ $isCreate ? 'show active' : '' }}" id="tab-info" role="tabpanel">
        <div class="card card-premium p-4 shadow-sm border-0 mb-4">
            <h5 class="fw-bold text-dark mb-4"><i class="bi bi-info-circle text-primary me-2"></i>Paramètres du Modèle de devis</h5>
            
            <div class="row g-4">
                <!-- Left Column -->
                <div class="col-lg-6 border-end pe-lg-4">
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold small text-dark">Nom du modèle <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" required value="{{ old('name', $template->name) }}" placeholder="Ex: 001 - Mitel 470, Solution Appel Malade...">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label small text-muted">Description / Notes publiques</label>
                        <textarea name="description" id="description" class="form-control" rows="3" placeholder="Description du pack ou modèle de devis...">{{ old('description', $template->description) }}</textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="equipment_category_id" class="form-label small text-muted">Catégorie de produit</label>
                            <select name="equipment_category_id" id="equipment_category_id" class="form-select">
                                <option value="">(Non spécifié)</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected(old('equipment_category_id', $template->equipment_category_id) == $cat->id)>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="equipment_family_id" class="form-label small text-muted">Famille de produit</label>
                            <select name="equipment_family_id" id="equipment_family_id" class="form-select">
                                <option value="">(Non spécifié)</option>
                                @foreach($families as $fam)
                                    <option value="{{ $fam->id }}" data-parent="{{ $fam->parent_id }}" @selected(old('equipment_family_id', $template->equipment_family_id) == $fam->id)>
                                        {{ $fam->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Assignments & Reserves -->
                <div class="col-lg-6 ps-lg-4">
                    <div class="mb-3">
                        <label for="client_id" class="form-label small text-muted">Client spécifique (Optionnel)</label>
                        <select name="client_id" id="client_id" class="form-select">
                            <option value="">Tous les clients (Modèle global)</option>
                            @foreach($clients as $cl)
                                <option value="{{ $cl->id }}" @selected(old('client_id', $template->client_id) == $cl->id)>
                                    {{ $cl->raison_sociale }} ({{ $cl->cegid_id }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="company_id" class="form-label small text-muted">Société / Agence</label>
                        <select name="company_id" id="company_id" class="form-select">
                            <option value="">Toutes les sociétés</option>
                            @foreach($companies as $comp)
                                <option value="{{ $comp->id }}" @selected(old('company_id', $template->company_id) == $comp->id)>
                                    {{ $comp->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="reserves" class="form-label small text-muted">Réserves associées par défaut</label>
                        <textarea name="reserves" id="reserves" class="form-control" rows="3" placeholder="Réserves textuelles pour ce modèle...">{{ old('reserves', $template->reserves) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" id="btn-go-to-produits">
                    Accéder aux produits <i class="bi bi-arrow-right ms-1"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- TAB 2: Produits (Exact replica of screenshot) -->
    <div class="tab-pane fade {{ !$isCreate ? 'show active' : '' }}" id="tab-produits" role="tabpanel">
        <div class="row g-4">
            
            <!-- Left Panel: Available Products Selection & 5 Action Toolbar -->
            <div class="col-xl-4">
                <div class="card card-premium shadow-sm border-0" style="max-height: 850px; display: flex; flex-direction: column;">
                    <div class="card-header bg-transparent border-0 pt-3 px-3 pb-2 d-flex align-items-center justify-content-between">
                        <!-- Toggles layout (Grid / List) -->
                        <div class="d-flex align-items-center gap-1">
                            <button type="button" class="btn btn-sm btn-primary py-1 px-2"><i class="bi bi-grid-fill"></i></button>
                            <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 border-0"><i class="bi bi-list"></i></button>
                        </div>

                        <!-- 5 Action Buttons (Toolbar matching user's requested icons) -->
                        <div class="d-flex align-items-center gap-1">
                            <!-- Button 1: Purple Icon (≡+) -> Ajouter une Réserve -->
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-outline-secondary p-1 border rounded shadow-none" style="color: #9c27b0; border-color: #ba68c8 !important;" id="dropdownReservesBtn" data-bs-toggle="dropdown" aria-expanded="false" title="Ajouter une réserve">
                                    <i class="bi bi-text-indent-left fs-5"></i>
                                </button>
                                <ul class="dropdown-menu shadow-lg p-2" id="dropdownTmplReservesMenu" aria-labelledby="dropdownReservesBtn" style="min-width: 360px; max-height: 400px; overflow-y: auto; font-size: 0.85rem; z-index: 9999;">
                                    <li>
                                        <a class="dropdown-item py-2 fw-bold text-primary rounded bg-light mb-1 d-flex align-items-center gap-2" href="#" id="btn-open-reserve-modal-quick-tmpl">
                                            <i class="bi bi-plus-circle-fill"></i> Nouvelle Réserve
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-1 text-secondary rounded mb-1 d-flex align-items-center gap-2" href="#" id="btn-add-reserve-libre">
                                            <span class="badge bg-secondary rounded-pill px-2">Libre</span> Réserve Libre
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <div id="dropdown-tmpl-reserves-list">
                                        @forelse($predefinedReserves as $resItem)
                                            @php
                                                $resText = is_object($resItem) ? ($resItem->formatted_text ?? $resItem->title) : $resItem;
                                            @endphp
                                            <li>
                                                <a class="dropdown-item py-2 text-wrap text-secondary rounded item-reserve-predef" href="#" data-text="{{ $resText }}">
                                                    {{ $resText }}
                                                </a>
                                            </li>
                                        @empty
                                            <li class="px-3 py-2 text-center text-muted small empty-reserves-msg">
                                                <em>Aucune réserve prédéfinie</em>
                                            </li>
                                        @endforelse
                                    </div>
                                </ul>
                            </div>

                            <!-- Button 2: Green Icon (🗐+) -> Choix de modèle(s) de devis -->
                            <button type="button" class="btn btn-sm btn-outline-success p-1 border rounded shadow-none" title="Importer un autre modèle" id="btn-open-template-modal">
                                <i class="bi bi-plus-square fs-5"></i>
                            </button>

                            <!-- Button 3: Green circle icon (🗹/🗷) -> Ajouter Chapitre Option -->
                            <button type="button" class="btn btn-sm btn-outline-secondary p-1 border rounded shadow-none" style="color: #00897b; border-color: #80cbc4 !important;" title="Ajouter un chapitre option" id="btn-add-option-chapter">
                                <i class="bi bi-check2-circle fs-5"></i>
                            </button>

                            <!-- Button 4: Blue icon (💬) -> Ajouter un Commentaire -->
                            <button type="button" class="btn btn-sm btn-outline-primary p-1 border rounded shadow-none" title="Ajouter un commentaire" id="btn-add-comment-line">
                                <i class="bi bi-chat-left-text fs-5"></i>
                            </button>

                            <!-- Button 5: Orange icon (💳+) -> Ajouter un Chapitre standard -->
                            <button type="button" class="btn btn-sm btn-outline-warning p-1 border rounded shadow-none" style="color: #f57c00; border-color: #ffb74d !important;" title="Ajouter un chapitre" id="btn-add-chapter">
                                <i class="bi bi-credit-card-2-front fs-5"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body p-3 d-flex flex-column overflow-hidden">
                        <!-- Filter headers -->
                        <div class="mb-2 d-flex align-items-center justify-content-between">
                            <span class="fw-bold text-dark small">
                                Produits disponibles
                                <span class="badge bg-secondary-subtle text-secondary ms-1 rounded-circle" id="products-count">0</span>
                            </span>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chk-select-all">
                            </div>
                        </div>

                        <!-- Search Bar -->
                        <div class="mb-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" id="search-equipments" class="form-control border-start-0" placeholder="Rechercher un article">
                            </div>
                        </div>

                        <!-- Cascading Category selectors -->
                        <div class="row g-1 mb-2">
                            <div class="col-4">
                                <select id="select-category" class="form-select form-select-sm px-1">
                                    <option value="">Catégorie</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <select id="select-family" class="form-select form-select-sm px-1" disabled>
                                    <option value="">Famille</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <select id="select-subfamily" class="form-select form-select-sm px-1" disabled>
                                    <option value="">Sous-famille</option>
                                </select>
                            </div>
                        </div>

                        <!-- Available products list scroll area -->
                        <div id="equipments-list" class="flex-grow-1 overflow-auto list-group list-group-flush border-top pt-2" style="max-height: 480px;">
                            <div class="text-center text-muted py-4 small">
                                <i class="bi bi-search me-1"></i> Chargement des articles...
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Template Lines Table -->
            <div class="col-xl-8">
                <div class="card card-premium shadow-sm border-0" style="display: flex; flex-direction: column;">
                    <div class="card-header bg-transparent border-0 pt-3 px-3 pb-2 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold d-flex align-items-center text-dark">
                            <i class="bi bi-list-task text-primary me-2 fs-5"></i> Lignes du Modèle de Devis
                        </h5>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold" id="btn-add-section-line">
                                <i class="bi bi-folder-plus me-1 text-primary"></i> Sous-section
                            </button>
                            <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold" id="add-line-btn">
                                <i class="bi bi-plus-lg me-1"></i> Ajouter article
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-0 overflow-auto">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle mb-0" id="lines-table" style="min-width: 820px;">
                                <thead class="table-light text-uppercase small text-muted">
                                    <tr>
                                        <th style="width: 40px;" class="text-center"><i class="bi bi-arrow-down-up"></i></th>
                                        <th style="width: 40px;" class="text-center"><input class="form-check-input" type="checkbox" id="chk-table-all" checked></th>
                                        <th>Article</th>
                                        <th class="text-center" style="width: 80px;">Qté</th>
                                        <th class="text-end" style="width: 110px;">PxPub</th>
                                        <th class="text-end" style="width: 85px;">R1</th>
                                        <th class="text-end" style="width: 85px;">R2</th>
                                        <th class="text-end" style="width: 110px;">PuHT</th>
                                        <th class="text-end" style="width: 120px;">PxTot</th>
                                        <th style="width: 50px;" class="text-center"><i class="bi bi-trash"></i></th>
                                    </tr>
                                </thead>
                                <tbody id="lines-body">
                                    <!-- Dynamic lines injected by JS from $lineData -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top p-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="text-muted small">
                            <span id="lines-count-summary">0 ligne(s)</span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <button type="button" class="btn btn-outline-primary rounded-pill px-4 fw-semibold" id="btn-back-to-info">
                                <i class="bi bi-arrow-left me-1"></i> Informations
                            </button>
                            <a href="{{ route('quote-templates.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-success rounded-pill px-5 fw-bold shadow-sm">
                                <i class="bi bi-check-lg me-1"></i> Enregistrer le Modèle
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: Ajout d'une nouvelle réserve (Matches screenshot) -->
<div class="modal fade" id="modalTmplReserveQuickAdd" tabindex="-1" aria-labelledby="modalTmplReserveQuickAddTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 8px;">
            <form id="quickTmplReserveForm">
                @csrf
                <!-- Header -->
                <div class="modal-header border-bottom px-4 pt-4 pb-3 align-items-start">
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="modalTmplReserveQuickAddTitle" style="color: #1e3a5f !important; font-size: 1.25rem;">
                            Réserve
                        </h5>
                        <small class="text-muted d-block mt-1" style="font-size: 0.9rem;">
                            Ajout d'une nouvelle réserve
                        </small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Body -->
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="quick_tmpl_reserve_title" class="form-label text-secondary small fw-semibold mb-2">Réserve</label>
                        <input type="text" class="form-control rounded-2 border py-2" id="quick_tmpl_reserve_title" name="title" required placeholder="" autofocus>
                    </div>
                    <div>
                        <textarea class="form-control rounded-2 border p-3" id="quick_tmpl_reserve_content" name="content" rows="6" placeholder=""></textarea>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer border-0 px-4 pb-4 pt-0 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary px-4 rounded-2" data-bs-dismiss="modal">
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-primary px-4 rounded-2 d-inline-flex align-items-center gap-2" style="background-color: #3b82f6; border-color: #3b82f6;" id="btn-submit-quick-tmpl-reserve">
                        <span>Enregistrer</span>
                        <i class="bi bi-floppy"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Importer un autre modèle de devis (Green icon) -->
<div class="modal fade" id="templateModal" tabindex="-1" aria-labelledby="templateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-light border-0 py-3">
                <h5 class="modal-title fw-bold text-dark" id="templateModalLabel">
                    <i class="bi bi-plus-square text-success me-2"></i>Choix de modèle de devis
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted small">Rechercher un modèle</label>
                    <input type="text" id="tpl-search-input" class="form-control form-control-sm" placeholder="Ex: Mitel, Ascom, Téléphonie...">
                </div>
                <div class="mb-3">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <span class="fw-bold small text-dark">Mode d'insertion :</span>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tpl_insert_mode" id="tpl_mode_replace" value="replace">
                            <label class="form-check-label small" for="tpl_mode_replace">Remplacer les lignes actuelles</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tpl_insert_mode" id="tpl_mode_append" value="append" checked>
                            <label class="form-check-label small" for="tpl_mode_append">Ajouter à la suite</label>
                        </div>
                    </div>
                </div>
                <div class="list-group list-group-flush border rounded-3 overflow-auto" id="templates-list-container" style="max-height: 350px;">
                    <div class="text-center py-4 text-muted small">
                        <i class="bi bi-arrow-repeat spin me-1"></i> Chargement des modèles...
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0 py-3">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom Styles matching quotation aesthetics */
.card-premium {
    border-radius: 12px;
    background: #ffffff;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
}
.nav-pills .nav-link {
    color: #495057;
    border-radius: 20px;
    margin: 0 4px;
}
.nav-pills .nav-link.active {
    background-color: #0288d1;
    color: #ffffff;
}
.chapter-row {
    background-color: #fbe9d7 !important;
    font-weight: 700;
    color: #b76413;
}
.section-row {
    background-color: #f4f9fd !important;
    font-weight: 600;
    color: #0288d1;
}
.option-row {
    background-color: #e8f5e9 !important;
}
.comment-row {
    background-color: #fbfbfb !important;
    font-style: italic;
}
.cursor-pointer {
    cursor: pointer;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let lines = @json($lineData);
    let allCategories = [];
    let bufferLines = [];

    const linesBody = document.getElementById('lines-body');
    const headerTotalHt = document.getElementById('header-total-ht');
    const remisePied = document.getElementById('remise_pied');
    const remisePiedPercent = document.getElementById('remise_pied_percent');
    const nameInput = document.getElementById('name');
    const headerTitle = document.getElementById('template-header-title');
    const catSelect = document.getElementById('equipment_category_id');
    const famSelect = document.getElementById('equipment_family_id');
    const headerCatLabel = document.getElementById('header-cat-label');
    const headerFamLabel = document.getElementById('header-fam-label');

    // Validation of Information Tab before accessing products
    function validateInfoTab() {
        const nameVal = nameInput ? nameInput.value.trim() : '';
        if (!nameVal) {
            if (nameInput) {
                nameInput.classList.add('is-invalid');
                let feedback = document.getElementById('name-validation-msg');
                if (!feedback) {
                    feedback = document.createElement('div');
                    feedback.id = 'name-validation-msg';
                    feedback.className = 'invalid-feedback d-block fw-semibold mt-1';
                    feedback.innerHTML = '<i class="bi bi-exclamation-circle me-1"></i> Veuillez renseigner le nom du modèle avant d\'accéder aux produits et de les ajouter.';
                    nameInput.parentNode.appendChild(feedback);
                }
                nameInput.focus();
            }
            return false;
        } else {
            if (nameInput) {
                nameInput.classList.remove('is-invalid');
                const feedback = document.getElementById('name-validation-msg');
                if (feedback) feedback.remove();
            }
            return true;
        }
    }

    const produitsTabBtn = document.getElementById('produits-tab');
    if (produitsTabBtn) {
        produitsTabBtn.addEventListener('show.bs.tab', function(e) {
            if (!validateInfoTab()) {
                e.preventDefault();
            }
        });
    }

    const btnGoProduits = document.getElementById('btn-go-to-produits');
    if (btnGoProduits && produitsTabBtn) {
        btnGoProduits.addEventListener('click', function(e) {
            e.preventDefault();
            if (validateInfoTab()) {
                const tabInstance = bootstrap.Tab.getOrCreateInstance(produitsTabBtn);
                tabInstance.show();
            }
        });
    }

    const btnBackToInfo = document.getElementById('btn-back-to-info');
    const infoTabBtn = document.getElementById('info-tab');
    if (btnBackToInfo && infoTabBtn) {
        btnBackToInfo.addEventListener('click', function(e) {
            e.preventDefault();
            const tabInstance = bootstrap.Tab.getOrCreateInstance(infoTabBtn);
            tabInstance.show();
        });
    }

    // Update Header dynamic texts
    if (nameInput) {
        nameInput.addEventListener('input', function() {
            headerTitle.textContent = this.value.trim() || 'Modèle de devis';
            if (this.value.trim()) {
                this.classList.remove('is-invalid');
                const feedback = document.getElementById('name-validation-msg');
                if (feedback) feedback.remove();
            }
        });
    }

    function updateHeaderCategoryLabels() {
        if (catSelect && headerCatLabel) {
            headerCatLabel.textContent = catSelect.options[catSelect.selectedIndex]?.text || '(non spécifié)';
        }
        if (famSelect && headerFamLabel) {
            headerFamLabel.textContent = famSelect.options[famSelect.selectedIndex]?.text || '(non spécifié)';
        }
    }

    if (catSelect) {
        catSelect.addEventListener('change', function() {
            const selectedCatId = this.value;
            if (famSelect) {
                Array.from(famSelect.options).forEach(opt => {
                    if (!opt.value) return;
                    if (!selectedCatId || opt.dataset.parent == selectedCatId) {
                        opt.style.display = '';
                    } else {
                        opt.style.display = 'none';
                        if (famSelect.value == opt.value) famSelect.value = '';
                    }
                });
            }
            updateHeaderCategoryLabels();
        });
    }

    if (famSelect) {
        famSelect.addEventListener('change', updateHeaderCategoryLabels);
    }

    // Format currency
    function formatMoney(amount) {
        return new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(amount || 0) + ' €';
    }

    // Render Table Lines
    function renderLines() {
        linesBody.innerHTML = '';
        let currentChapterTotal = 0;
        let runningTotal = 0;

        lines.forEach((line, index) => {
            line.position = index + 1;
            const tr = document.createElement('tr');
            tr.dataset.index = index;

            const isOption = line.is_option == true || line.is_option == '1' || line.is_option == 'true';
            const qte = parseFloat(line.qte) || 1;
            const puHt = parseFloat(line.pu_ht) || 0;
            const r1 = parseFloat(line.remise_ligne) || 0;
            const r2 = parseFloat(line.remise_2) || 0;
            const puNet = puHt * (1 - (r1 / 100)) * (1 - (r2 / 100));
            const pxTot = qte * puNet;

            if (line.type_ligne === 'Chapitre') {
                tr.className = 'chapter-row align-middle';
                tr.innerHTML = `
                    <td class="text-center text-muted"><i class="bi bi-grip-vertical"></i></td>
                    <td class="text-center"><input type="checkbox" class="form-check-input line-select-chk" data-index="${index}" checked></td>
                    <td colspan="6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-folder2-open text-warning fs-5"></i>
                            <input type="text" name="lines[${index}][libelle]" value="${escapeHtml(line.libelle || '')}" class="form-control form-control-sm fw-bold border-0 bg-transparent text-dark line-libelle-input" placeholder="Titre du Chapitre">
                        </div>
                    </td>
                    <td class="text-end fw-bold text-dark chapter-subtotal-cell" data-chapter="${escapeHtml(line.libelle || '')}">
                        Sous-total : <span class="subtotal-val">${formatMoney(0)}</span>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-link text-danger p-0 btn-remove-line" data-index="${index}"><i class="bi bi-x-circle fs-6"></i></button>
                    </td>
                    <input type="hidden" name="lines[${index}][position]" value="${index + 1}">
                    <input type="hidden" name="lines[${index}][type_ligne]" value="Chapitre">
                    <input type="hidden" name="lines[${index}][chapter_title]" value="${escapeHtml(line.libelle || '')}">
                    <input type="hidden" name="lines[${index}][qte]" value="0">
                    <input type="hidden" name="lines[${index}][pu_ht]" value="0">
                `;
            } else if (line.type_ligne === 'Section') {
                tr.className = 'section-row align-middle';
                tr.innerHTML = `
                    <td class="text-center text-muted"><i class="bi bi-grip-vertical"></i></td>
                    <td class="text-center"><input type="checkbox" class="form-check-input line-select-chk" data-index="${index}" checked></td>
                    <td colspan="7">
                        <div class="d-flex align-items-center gap-2 ps-3">
                            <i class="bi bi-window-sidebar text-primary"></i>
                            <input type="text" name="lines[${index}][libelle]" value="${escapeHtml(line.libelle || '')}" class="form-control form-control-sm fw-semibold border-0 bg-transparent text-primary line-libelle-input" placeholder="Sous-section / Titre">
                        </div>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-link text-danger p-0 btn-remove-line" data-index="${index}"><i class="bi bi-x-circle fs-6"></i></button>
                    </td>
                    <input type="hidden" name="lines[${index}][position]" value="${index + 1}">
                    <input type="hidden" name="lines[${index}][type_ligne]" value="Section">
                    <input type="hidden" name="lines[${index}][qte]" value="0">
                    <input type="hidden" name="lines[${index}][pu_ht]" value="0">
                `;
            } else if (line.type_ligne === 'Commentaire') {
                tr.className = 'comment-row align-middle';
                tr.innerHTML = `
                    <td class="text-center text-muted"><i class="bi bi-grip-vertical"></i></td>
                    <td class="text-center"><input type="checkbox" class="form-check-input line-select-chk" data-index="${index}" checked></td>
                    <td colspan="7">
                        <div class="d-flex align-items-center gap-2 ps-3">
                            <i class="bi bi-chat-left-text text-secondary"></i>
                            <input type="text" name="lines[${index}][libelle]" value="${escapeHtml(line.libelle || '')}" class="form-control form-control-sm border-0 bg-transparent text-secondary line-libelle-input" placeholder="Commentaire / Remarque">
                        </div>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-link text-danger p-0 btn-remove-line" data-index="${index}"><i class="bi bi-x-circle fs-6"></i></button>
                    </td>
                    <input type="hidden" name="lines[${index}][position]" value="${index + 1}">
                    <input type="hidden" name="lines[${index}][type_ligne]" value="Commentaire">
                    <input type="hidden" name="lines[${index}][qte]" value="0">
                    <input type="hidden" name="lines[${index}][pu_ht]" value="0">
                `;
            } else {
                // Standard Article Line
                if (isOption) {
                    tr.className = 'option-row align-middle';
                }
                if (!isOption) {
                    runningTotal += pxTot;
                }

                tr.innerHTML = `
                    <td class="text-center text-muted"><i class="bi bi-grip-vertical"></i></td>
                    <td class="text-center"><input type="checkbox" class="form-check-input line-select-chk" data-index="${index}" checked></td>
                    <td>
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center gap-2">
                                ${isOption ? '<span class="badge bg-warning text-dark px-1 py-0 me-1" style="font-size:0.7rem;">Option</span>' : ''}
                                <span class="fw-bold text-dark small">${escapeHtml(line.code_article || '')}</span>
                            </div>
                            <input type="text" name="lines[${index}][libelle]" value="${escapeHtml(line.libelle || '')}" class="form-control form-control-sm border-0 p-0 bg-transparent text-muted small line-libelle-input" placeholder="Désignation article">
                        </div>
                    </td>
                    <td class="text-center">
                        <input type="number" step="0.01" min="0" name="lines[${index}][qte]" value="${qte}" class="form-control form-control-sm text-center line-calc-input input-qte" data-index="${index}">
                    </td>
                    <td class="text-end">
                        <input type="number" step="0.01" min="0" name="lines[${index}][pu_ht]" value="${puHt.toFixed(2)}" class="form-control form-control-sm text-end line-calc-input input-puht" data-index="${index}">
                    </td>
                    <td class="text-end">
                        <input type="number" step="0.01" min="0" max="100" name="lines[${index}][remise_ligne]" value="${r1.toFixed(2)}" class="form-control form-control-sm text-end line-calc-input input-r1" data-index="${index}">
                    </td>
                    <td class="text-end">
                        <input type="number" step="0.01" min="0" max="100" name="lines[${index}][remise_2]" value="${r2.toFixed(2)}" class="form-control form-control-sm text-end line-calc-input input-r2" data-index="${index}">
                    </td>
                    <td class="text-end fw-semibold text-secondary small cell-pu-net">
                        ${formatMoney(puNet)}
                    </td>
                    <td class="text-end fw-bold text-dark cell-px-tot">
                        ${formatMoney(pxTot)}
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-link text-danger p-0 btn-remove-line" data-index="${index}"><i class="bi bi-x-circle fs-6"></i></button>
                    </td>
                    <input type="hidden" name="lines[${index}][position]" value="${index + 1}">
                    <input type="hidden" name="lines[${index}][type_ligne]" value="${line.type_ligne || 'Article'}">
                    <input type="hidden" name="lines[${index}][code_article]" value="${escapeHtml(line.code_article || '')}">
                    <input type="hidden" name="lines[${index}][equipment_id]" value="${line.equipment_id || ''}">
                    <input type="hidden" name="lines[${index}][chapter_id]" value="${escapeHtml(line.chapter_id || '')}">
                    <input type="hidden" name="lines[${index}][chapter_title]" value="${escapeHtml(line.chapter_title || '')}">
                    <input type="hidden" name="lines[${index}][is_option]" value="${isOption ? '1' : '0'}">
                `;
            }

            linesBody.appendChild(tr);
        });

        calculateOverallTotals();
        updateChapterSubtotals();
        document.getElementById('lines-count-summary').textContent = lines.length + ' ligne(s)';
    }

    // Calculate Chapter Subtotals
    function updateChapterSubtotals() {
        let currentSubtotal = 0;
        let lastChapterCell = null;

        lines.forEach((line, index) => {
            if (line.type_ligne === 'Chapitre') {
                if (lastChapterCell) {
                    lastChapterCell.querySelector('.subtotal-val').textContent = formatMoney(currentSubtotal);
                }
                const row = linesBody.children[index];
                if (row) lastChapterCell = row.querySelector('.chapter-subtotal-cell');
                currentSubtotal = 0;
            } else if (line.type_ligne === 'Article' && !line.is_option) {
                const qte = parseFloat(line.qte) || 1;
                const puHt = parseFloat(line.pu_ht) || 0;
                const r1 = parseFloat(line.remise_ligne) || 0;
                const r2 = parseFloat(line.remise_2) || 0;
                currentSubtotal += qte * (puHt * (1 - (r1 / 100)) * (1 - (r2 / 100)));
            }
        });
        if (lastChapterCell) {
            lastChapterCell.querySelector('.subtotal-val').textContent = formatMoney(currentSubtotal);
        }
    }

    // Calculate Overall Totals & Remise Pied
    function calculateOverallTotals() {
        let totalBrut = 0;
        lines.forEach(line => {
            const isOption = line.is_option == true || line.is_option == '1' || line.is_option == 'true';
            if (line.type_ligne === 'Article' && !isOption) {
                const qte = parseFloat(line.qte) || 1;
                const puHt = parseFloat(line.pu_ht) || 0;
                const r1 = parseFloat(line.remise_ligne) || 0;
                const r2 = parseFloat(line.remise_2) || 0;
                totalBrut += qte * (puHt * (1 - (r1 / 100)) * (1 - (r2 / 100)));
            }
        });

        let finalTotal = totalBrut;
        const remisePercentVal = parseFloat(remisePiedPercent?.value) || 0;
        const remiseEuroVal = parseFloat(remisePied?.value) || 0;

        if (remisePercentVal > 0) {
            finalTotal = totalBrut * (1 - (remisePercentVal / 100));
        } else if (remiseEuroVal > 0) {
            finalTotal = Math.max(0, totalBrut - remiseEuroVal);
        }

        if (headerTotalHt) {
            headerTotalHt.textContent = formatMoney(finalTotal);
        }
    }

    // Event Delegation on lines table
    linesBody.addEventListener('input', function(e) {
        const target = e.target;
        const index = target.dataset.index;
        if (index === undefined) return;

        if (target.classList.contains('input-qte')) {
            lines[index].qte = parseFloat(target.value) || 0;
        } else if (target.classList.contains('input-puht')) {
            lines[index].pu_ht = parseFloat(target.value) || 0;
        } else if (target.classList.contains('input-r1')) {
            lines[index].remise_ligne = parseFloat(target.value) || 0;
        } else if (target.classList.contains('input-r2')) {
            lines[index].remise_2 = parseFloat(target.value) || 0;
        } else if (target.classList.contains('line-libelle-input')) {
            lines[index].libelle = target.value;
        }

        // Live update cell values
        const row = linesBody.children[index];
        if (row && lines[index].type_ligne === 'Article') {
            const qte = parseFloat(lines[index].qte) || 1;
            const puHt = parseFloat(lines[index].pu_ht) || 0;
            const r1 = parseFloat(lines[index].remise_ligne) || 0;
            const r2 = parseFloat(lines[index].remise_2) || 0;
            const puNet = puHt * (1 - (r1 / 100)) * (1 - (r2 / 100));
            const pxTot = qte * puNet;

            const puNetCell = row.querySelector('.cell-pu-net');
            const pxTotCell = row.querySelector('.cell-px-tot');
            if (puNetCell) puNetCell.textContent = formatMoney(puNet);
            if (pxTotCell) pxTotCell.textContent = formatMoney(pxTot);
        }

        calculateOverallTotals();
        updateChapterSubtotals();
    });

    linesBody.addEventListener('click', function(e) {
        const removeBtn = e.target.closest('.btn-remove-line');
        if (removeBtn) {
            const index = parseInt(removeBtn.dataset.index);
            lines.splice(index, 1);
            renderLines();
        }
    });

    // Remise foot listeners
    if (remisePied) {
        remisePied.addEventListener('input', function() {
            if (parseFloat(this.value) > 0 && remisePiedPercent) {
                remisePiedPercent.value = '0.0000';
            }
            calculateOverallTotals();
        });
    }

    if (remisePiedPercent) {
        remisePiedPercent.addEventListener('input', function() {
            if (parseFloat(this.value) > 0 && remisePied) {
                remisePied.value = '0.00';
            }
            calculateOverallTotals();
        });
    }

    // 5 Toolbar Actions Handlers
    // 1. Purple Reserve Dropdown & Modal actions
    const modalQuickReserveTmplEl = document.getElementById('modalTmplReserveQuickAdd');
    const modalQuickReserveTmpl = modalQuickReserveTmplEl ? new bootstrap.Modal(modalQuickReserveTmplEl) : null;
    const quickTmplReserveForm = document.getElementById('quickTmplReserveForm');

    function addReserveLineToTemplate(resText) {
        if (!resText) return;
        lines.push({
            type_ligne: 'Commentaire',
            libelle: 'Réserve : ' + resText,
            code_article: null,
            qte: 0,
            pu_ht: 0,
        });
        renderLines();
    }

    document.getElementById('btn-open-reserve-modal-quick-tmpl')?.addEventListener('click', function(e) {
        e.preventDefault();
        if (modalQuickReserveTmpl) {
            document.getElementById('quick_tmpl_reserve_title').value = '';
            document.getElementById('quick_tmpl_reserve_content').value = '';
            modalQuickReserveTmpl.show();
        }
    });

    if (quickTmplReserveForm) {
        quickTmplReserveForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const title = document.getElementById('quick_tmpl_reserve_title').value.trim();
            const content = document.getElementById('quick_tmpl_reserve_content').value.trim();
            if (!title) return;

            const btnSubmit = document.getElementById('btn-submit-quick-tmpl-reserve');
            if (btnSubmit) btnSubmit.disabled = true;

            fetch('{{ route("quote-reserves.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    title: title,
                    content: content,
                    is_active: 1
                })
            })
            .then(res => res.json())
            .then(data => {
                if (btnSubmit) btnSubmit.disabled = false;
                if (data.success && data.reserve) {
                    const resText = (data.reserve.content && data.reserve.content.length > 0)
                        ? `${data.reserve.title} - ${data.reserve.content}`
                        : data.reserve.title;
                    addReserveLineToTemplate(resText);

                    // Add to dropdown list
                    const listContainer = document.getElementById('dropdown-tmpl-reserves-list');
                    if (listContainer) {
                        const emptyMsg = listContainer.querySelector('.empty-reserves-msg');
                        if (emptyMsg) emptyMsg.remove();

                        const li = document.createElement('li');
                        li.innerHTML = `<a class="dropdown-item py-2 text-wrap text-secondary rounded item-reserve-predef" href="#" data-text="${escapeHtml(resText)}">${escapeHtml(resText)}</a>`;
                        li.querySelector('a').addEventListener('click', (ev) => {
                            ev.preventDefault();
                            addReserveLineToTemplate(resText);
                        });
                        listContainer.prepend(li);
                    }

                    if (modalQuickReserveTmpl) modalQuickReserveTmpl.hide();
                } else {
                    alert(data.message || 'Erreur lors de l\'enregistrement de la réserve.');
                }
            })
            .catch(err => {
                if (btnSubmit) btnSubmit.disabled = false;
                alert('Erreur de communication avec le serveur.');
            });
        });
    }

    document.getElementById('btn-add-reserve-libre')?.addEventListener('click', function(e) {
        e.preventDefault();
        const text = prompt('Saisir le texte de la réserve libre :');
        if (text) {
            addReserveLineToTemplate(text.trim());
        }
    });

    document.querySelectorAll('.item-reserve-predef').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            addReserveLineToTemplate(this.dataset.text);
        });
    });

    // 2. Green Template Modal
    const templateModalEl = document.getElementById('templateModal');
    const templateModal = templateModalEl ? new bootstrap.Modal(templateModalEl) : null;
    document.getElementById('btn-open-template-modal')?.addEventListener('click', function() {
        loadTemplatesList();
        templateModal?.show();
    });

    // 3. Green Circle Option Chapter
    document.getElementById('btn-add-option-chapter')?.addEventListener('click', function() {
        lines.push({
            type_ligne: 'Chapitre',
            libelle: 'Option - ',
            code_article: null,
            qte: 0,
            pu_ht: 0,
            is_option: true,
        });
        renderLines();
    });

    // 4. Blue Comment
    document.getElementById('btn-add-comment-line')?.addEventListener('click', function() {
        lines.push({
            type_ligne: 'Commentaire',
            libelle: 'Commentaire : ',
            code_article: null,
            qte: 0,
            pu_ht: 0,
        });
        renderLines();
    });

    // 5. Orange Chapter
    document.getElementById('btn-add-chapter')?.addEventListener('click', function() {
        lines.push({
            type_ligne: 'Chapitre',
            libelle: 'Nouveau Chapitre',
            code_article: null,
            qte: 0,
            pu_ht: 0,
            is_option: false,
        });
        renderLines();
    });

    // Sous-section button
    document.getElementById('btn-add-section-line')?.addEventListener('click', function() {
        lines.push({
            type_ligne: 'Section',
            libelle: 'Nouvelle Sous-section',
            code_article: null,
            qte: 0,
            pu_ht: 0,
        });
        renderLines();
    });

    // Add empty article line
    document.getElementById('add-line-btn')?.addEventListener('click', function() {
        lines.push({
            type_ligne: 'Article',
            code_article: '',
            libelle: 'Nouvel article',
            equipment_id: null,
            qte: 1,
            pu_ht: 0,
            remise_ligne: 0,
            remise_2: 0,
            is_option: false,
        });
        renderLines();
    });

    // Buffer Actions
    document.getElementById('btn-copy-buffer')?.addEventListener('click', function() {
        const checkedIndices = Array.from(document.querySelectorAll('.line-select-chk:checked')).map(c => parseInt(c.dataset.index));
        if (checkedIndices.length === 0) {
            alert('Veuillez cocher au moins une ligne à copier.');
            return;
        }
        bufferLines = checkedIndices.map(i => JSON.parse(JSON.stringify(lines[i])));
        document.getElementById('buffer-badge').textContent = bufferLines.length;
    });

    document.getElementById('btn-view-buffer')?.addEventListener('click', function() {
        if (bufferLines.length === 0) {
            alert('Le buffer est vide.');
            return;
        }
        bufferLines.forEach(bl => lines.push(JSON.parse(JSON.stringify(bl))));
        renderLines();
    });

    document.getElementById('btn-clear-buffer')?.addEventListener('click', function() {
        bufferLines = [];
        document.getElementById('buffer-badge').textContent = '0';
    });

    // Product Catalogue search & cascading categories
    const searchInput = document.getElementById('search-equipments');
    const selectCat = document.getElementById('select-category');
    const selectFam = document.getElementById('select-family');
    const selectSubFam = document.getElementById('select-subfamily');
    const equipmentsList = document.getElementById('equipments-list');

    // Load categories
    fetch('/orders/categories')
        .then(res => res.json())
        .then(data => {
            allCategories = data;
            populateCategorySelect();
        })
        .catch(() => {});

    function populateCategorySelect() {
        if (!selectCat) return;
        selectCat.innerHTML = '<option value="">Catégorie</option>';
        const rootCats = allCategories.filter(c => !c.parent_id);
        rootCats.forEach(cat => {
            const opt = document.createElement('option');
            opt.value = cat.id;
            opt.textContent = cat.name_raw || cat.name;
            selectCat.appendChild(opt);
        });
    }

    if (selectCat) {
        selectCat.addEventListener('change', function() {
            const catId = this.value;
            selectFam.innerHTML = '<option value="">Famille</option>';
            selectSubFam.innerHTML = '<option value="">Sous-famille</option>';
            selectSubFam.disabled = true;

            if (catId) {
                selectFam.disabled = false;
                const fams = allCategories.filter(c => c.parent_id == catId);
                fams.forEach(fam => {
                    const opt = document.createElement('option');
                    opt.value = fam.id;
                    opt.textContent = fam.name_raw || fam.name;
                    selectFam.appendChild(opt);
                });
            } else {
                selectFam.disabled = true;
            }
            fetchEquipments();
        });
    }

    if (selectFam) {
        selectFam.addEventListener('change', function() {
            const famId = this.value;
            selectSubFam.innerHTML = '<option value="">Sous-famille</option>';
            if (famId) {
                selectSubFam.disabled = false;
                const subFams = allCategories.filter(c => c.parent_id == famId);
                subFams.forEach(sf => {
                    const opt = document.createElement('option');
                    opt.value = sf.id;
                    opt.textContent = sf.name_raw || sf.name;
                    selectSubFam.appendChild(opt);
                });
            } else {
                selectSubFam.disabled = true;
            }
            fetchEquipments();
        });
    }

    if (selectSubFam) {
        selectSubFam.addEventListener('change', fetchEquipments);
    }

    let searchTimeout = null;
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(fetchEquipments, 300);
        });
    }

    function fetchEquipments() {
        const query = searchInput ? searchInput.value : '';
        const categoryId = selectSubFam?.value || selectFam?.value || selectCat?.value || '';

        equipmentsList.innerHTML = '<div class="text-center text-muted py-4 small"><i class="bi bi-arrow-repeat spin me-1"></i> Recherche...</div>';

        const params = new URLSearchParams();
        if (query) params.append('query', query);
        if (categoryId) params.append('category_id', categoryId);

        fetch(`/orders/equipments?${params.toString()}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('products-count').textContent = data.length;
                renderEquipments(data);
            })
            .catch(() => {
                equipmentsList.innerHTML = '<div class="text-center text-danger py-4 small">Erreur de chargement des articles.</div>';
            });
    }

    function renderEquipments(items) {
        if (!equipmentsList) return;
        equipmentsList.innerHTML = '';
        if (items.length === 0) {
            equipmentsList.innerHTML = '<div class="text-center text-muted py-4 small">Aucun article trouvé.</div>';
            return;
        }

        items.forEach(eq => {
            const itemDiv = document.createElement('div');
            itemDiv.className = 'list-group-item list-group-item-action p-2 border-0 border-bottom d-flex align-items-start justify-content-between gap-2';
            
            const hasSubstitutes = eq.substitutes_count > 0;
            const subCollapseId = 'sub-eq-' + eq.id;

            itemDiv.innerHTML = `
                <div class="flex-grow-1 cursor-pointer" onclick="addEquipmentToQuote(${JSON.stringify(eq).replace(/"/g, '&quot;')})">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-bold text-dark small">${escapeHtml(eq.reference || eq.name)}</span>
                    </div>
                    <div class="text-muted small text-truncate" style="max-width: 220px;" title="${escapeHtml(eq.name)}">
                        ${escapeHtml(eq.name)}
                    </div>
                    ${hasSubstitutes ? `
                        <div class="mt-1">
                            <a href="#${subCollapseId}" class="badge bg-light text-primary text-decoration-none border" data-bs-toggle="collapse" onclick="event.stopPropagation();">
                                ⌄ ${eq.substitutes_count} Articles liés
                            </a>
                            <div class="collapse mt-1" id="${subCollapseId}">
                                <div class="bg-light p-2 rounded-2 small">
                                    ${eq.substitutes.map(sub => `
                                        <div class="d-flex align-items-center justify-content-between py-1 border-bottom border-light">
                                            <span>${escapeHtml(sub.name)}</span>
                                            <button type="button" class="btn btn-xs btn-outline-primary py-0 px-1" onclick="event.stopPropagation(); addEquipmentToQuote(${JSON.stringify(sub).replace(/"/g, '&quot;')});">
                                                +
                                            </button>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        </div>
                    ` : ''}
                </div>
                <div class="text-end">
                    <span class="fw-bold text-dark small d-block">${formatMoney(eq.price)}</span>
                    <button type="button" class="btn btn-sm btn-primary rounded-circle p-0 d-inline-flex align-items-center justify-content-center mt-1" style="width: 24px; height: 24px;" onclick="addEquipmentToQuote(${JSON.stringify(eq).replace(/"/g, '&quot;')})">
                        <i class="bi bi-plus"></i>
                    </button>
                </div>
            `;
            equipmentsList.appendChild(itemDiv);
        });
    }

    window.addEquipmentToQuote = function(eq) {
        lines.push({
            type_ligne: 'Article',
            code_article: eq.reference || eq.name,
            libelle: eq.name,
            equipment_id: eq.id,
            qte: 1,
            pu_ht: parseFloat(eq.price) || 0,
            remise_ligne: 0,
            remise_2: 0,
            is_option: false,
        });
        renderLines();
    };

    // Load templates in modal
    function loadTemplatesList() {
        const container = document.getElementById('templates-list-container');
        if (!container) return;
        fetch('/orders/templates')
            .then(res => res.json())
            .then(templates => {
                container.innerHTML = '';
                if (templates.length === 0) {
                    container.innerHTML = '<div class="text-center py-4 text-muted small">Aucun modèle disponible.</div>';
                    return;
                }
                templates.forEach(t => {
                    const item = document.createElement('a');
                    item.href = '#';
                    item.className = 'list-group-item list-group-item-action d-flex align-items-center justify-content-between py-3';
                    item.innerHTML = `
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">${escapeHtml(t.name)}</h6>
                            <small class="text-muted">${escapeHtml(t.description || '')}</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-light text-dark border">${t.lines_count || (t.lines ? t.lines.length : 0)} lignes</span>
                            <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3">Insérer</button>
                        </div>
                    `;
                    item.addEventListener('click', function(e) {
                        e.preventDefault();
                        insertTemplate(t.id);
                    });
                    container.appendChild(item);
                });
            })
            .catch(() => {
                container.innerHTML = '<div class="text-center py-4 text-danger small">Erreur de chargement des modèles.</div>';
            });
    }

    function insertTemplate(templateId) {
        fetch(`/orders/templates/${templateId}`)
            .then(res => res.json())
            .then(tplData => {
                const mode = document.querySelector('input[name="tpl_insert_mode"]:checked')?.value || 'append';
                if (mode === 'replace') {
                    lines = [];
                }
                if (tplData.lines && Array.isArray(tplData.lines)) {
                    tplData.lines.forEach(l => {
                        lines.push({
                            type_ligne: l.type_ligne || 'Article',
                            code_article: l.code_article || '',
                            libelle: l.libelle || '',
                            equipment_id: l.equipment_id || null,
                            qte: parseFloat(l.qte) || 1,
                            pu_ht: parseFloat(l.pu_ht) || 0,
                            remise_ligne: parseFloat(l.remise_ligne) || 0,
                            remise_2: parseFloat(l.remise_2) || 0,
                            chapter_id: l.chapter_id || null,
                            chapter_title: l.chapter_title || null,
                            is_option: l.is_option == true || l.is_option == '1',
                        });
                    });
                }
                renderLines();
                templateModal?.hide();
            })
            .catch(() => {
                alert('Erreur lors de l\'insertion du modèle.');
            });
    }

    function escapeHtml(text) {
        if (!text) return '';
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Initial trigger
    renderLines();
    fetchEquipments();
});
</script>
