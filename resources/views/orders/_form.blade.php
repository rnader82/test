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
	                    'type_article' => $line->type_article,
	                    'qte' => $line->qte,
	                    'qty_delivered' => $line->qty_delivered,
	                    'pu_ttc' => $line->pu_ttc,
	                    'pu_ht' => $line->pu_ht,
	                    'pu_ht_net' => $line->pu_ht_net,
	                    'remise_ligne' => $line->remise_ligne,
	                    'remise_2' => $line->remise_2 ?? 0,
	                    'chapter_id' => $line->chapter_id,
	                    'chapter_title' => $line->chapter_title,
	                    'is_option' => $line->is_option ?? false,
	                    'famille_taxe1_id' => $line->famille_taxe1_id,
	                    'montant_ht' => $line->montant_ht,
	                    'total_taxe1' => $line->total_taxe1,
	                    'montant_ttc' => $line->montant_ttc,
	                ],
	            )
	            ->toArray()
	        : [],
	);

	$portData = old(
	    'ports',
	    $ports instanceof \Illuminate\Support\Collection
	        ? $ports
	            ->map(
	                fn($port) => [
	                    'code_port' => $port->code_port,
	                    'libelle' => $port->libelle,
	                    'montant_mini' => $port->montant_mini,
	                    'facturable' => $port->facturable,
	                    'type_port' => $port->type_port,
	                ],
	            )
	            ->toArray()
	        : [],
	);

    $predefinedReserves = $reserves ?? \App\Models\DevisReserve::active()->ordered()->get();
@endphp

<!-- Scoped CSS for Clean Order Design (Matches Screenshot 100%) -->
<style>
.clean-order-header {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 1rem 1.25rem;
}
.quote-status-badge {
    background-color: #fef3e7 !important;
    color: #d97706 !important;
    border: 1px solid #fed7aa !important;
    border-radius: 4px !important;
    padding: 3px 8px !important;
    font-size: 12px !important;
    font-weight: 600 !important;
}
.quote-pill-btn {
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 20px !important;
    padding: 3px 12px !important;
    font-size: 12px !important;
    color: #475569 !important;
    font-weight: 500 !important;
    box-shadow: none !important;
    transition: all 0.15s;
}
.quote-pill-btn:hover {
    background: #f8fafc !important;
    border-color: #cbd5e1 !important;
}
.nav-tabs-stepper {
    border-bottom: 1px solid #e2e8f0 !important;
    background: transparent !important;
    gap: 1.5rem;
}
.stepper-tab-btn {
    border: none !important;
    border-bottom: 2px solid transparent !important;
    background: transparent !important;
    color: #64748b !important;
    font-size: 13.5px !important;
    padding: 8px 12px 10px 12px !important;
    transition: all 0.2s;
    border-radius: 0 !important;
}
.stepper-tab-btn:hover {
    color: #0288d1 !important;
}
.stepper-tab-btn.active {
    color: #0288d1 !important;
    border-bottom: 2px solid #0288d1 !important;
    background: transparent !important;
    font-weight: 600 !important;
}
.clean-panel {
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 6px !important;
}
.btn-clean-action {
    width: 28px;
    height: 28px;
    border-radius: 4px;
    background: #ffffff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    font-size: 14px;
    transition: all 0.15s ease-in-out;
    cursor: pointer;
}
.btn-clean-action.action-purple {
    border: 1px solid #ce93d8 !important;
    color: #9c27b0 !important;
}
.btn-clean-action.action-purple:hover {
    background-color: #f3e5f5;
}
.btn-clean-action.action-green {
    border: 1px solid #a5d6a7 !important;
    color: #2e7d32 !important;
}
.btn-clean-action.action-green:hover {
    background-color: #e8f5e9;
}
.btn-clean-action.action-teal {
    border: 1px solid #80cbc4 !important;
    color: #00897b !important;
}
.btn-clean-action.action-teal:hover {
    background-color: #e0f2f1;
}
.btn-clean-action.action-blue {
    border: 1px solid #90caf9 !important;
    color: #1976d2 !important;
}
.btn-clean-action.action-blue:hover {
    background-color: #e3f2fd;
}
.btn-clean-action.action-orange {
    border: 1px solid #ffcc80 !important;
    color: #ef6c00 !important;
}
.btn-clean-action.action-orange:hover {
    background-color: #fff3e0;
}
.clean-quote-table {
    border-collapse: separate !important;
    border-spacing: 0 !important;
}
.clean-quote-table thead th {
    background-color: #f8fafc !important;
    color: #5a738e !important;
    font-size: 12px !important;
    font-weight: 500 !important;
    border-bottom: 1px solid #e2e8f0 !important;
    border-top: none !important;
    border-left: none !important;
    border-right: none !important;
    padding: 8px 6px !important;
}
.clean-quote-table tbody tr {
    border-bottom: 1px solid #f1f5f9 !important;
    background-color: #ffffff;
}
.clean-quote-table tbody tr:hover {
    background-color: #fbfcfe;
}
.clean-quote-table tbody td {
    border: none !important;
    border-bottom: 1px solid #f1f5f9 !important;
    padding: 6px 6px !important;
    font-size: 12.5px;
}
.clean-quote-table tr.chapter-row {
    background-color: #f5e7d5 !important;
    border-bottom: 1px solid #ebd8c1 !important;
}
.clean-quote-table tr.chapter-row td {
    background-color: #f5e7d5 !important;
    border-bottom: 1px solid #ebd8c1 !important;
    padding: 7px 6px !important;
}
.clean-underline-input {
    border: none !important;
    border-bottom: 1px solid #cbd5e1 !important;
    background: transparent !important;
    outline: none !important;
    padding: 1px 0 !important;
    border-radius: 0 !important;
    transition: border-color 0.2s;
    box-shadow: none !important;
}
.clean-underline-input:focus {
    border-bottom-color: #0288d1 !important;
}
.clean-underline-input::-webkit-outer-spin-button,
.clean-underline-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
.clean-underline-input[type=number] {
    -moz-appearance: textfield;
}
.clean-custom-scroll::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}
.clean-custom-scroll::-webkit-scrollbar-track {
    background: #f8fafc;
}
.clean-custom-scroll::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}
.clean-custom-scroll::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
.line-delete-btn {
    opacity: 0.25;
    transition: opacity 0.15s;
}
tr:hover .line-delete-btn {
    opacity: 1;
}
</style>

<!-- Header Banner Panel (Clean design matching screenshot) -->
<div class="clean-order-header mb-3">
    <div class="row align-items-center">
        <!-- Top Left Header Info -->
        <div class="col-md-7">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="fw-bold text-dark fs-5" id="quote-header-title">
                    @if(isset($order->id) && $order->id)
                        Devis {{ $order->ref_interne ?: ($order->numero ?: ('B' . $order->id . '-1')) }}
                    @else
                        Devis {{ $order->ref_interne ?: 'B19254-1' }}
                    @endif
                </span>
                <span class="text-muted">|</span>
                <span class="badge quote-status-badge d-inline-flex align-items-center gap-1">
                    <i class="bi bi-pencil-fill" style="font-size: 0.75rem;"></i>
                    <span id="header-status">{{ $order->statut ?? 'Brouillon' }}</span>
                </span>
            </div>
            <div class="mt-1">
                <div class="fw-bold text-dark text-uppercase" style="font-size: 13.5px;" id="header-client-name">
                    {{ optional($order->client)->raison_sociale ?? 'GROUPE NOVALYO' }}
                </div>
                <div class="text-muted" style="font-size: 12px;" id="header-client-address">
                    @if($order->client)
                        {{ trim($order->client->code_postal . ' ' . $order->client->ville) }}
                    @else
                        38150 SALAISE SUR SANNE
                    @endif
                </div>
            </div>
            <!-- Action Badges Row -->
            <div class="mt-2 d-flex align-items-center gap-2 flex-wrap">
                <button type="button" class="btn btn-sm quote-pill-btn d-inline-flex align-items-center gap-2" id="btn-toggle-ports">
                    <i class="bi bi-truck text-primary" style="font-size: 14px;"></i>
                    <span>Port</span>
                    <span class="badge bg-primary text-white rounded-circle" id="header-ports-count" style="font-size: 10px; width: 17px; height: 17px; display: inline-flex; align-items: center; justify-content: center;">{{ count($portData) }}</span>
                </button>
                <button type="button" class="btn btn-sm quote-pill-btn d-inline-flex align-items-center gap-2" id="btn-toggle-note">
                    <i class="bi bi-file-earmark-text text-secondary" style="font-size: 14px;"></i>
                    <span>Note</span>
                </button>
                <div class="d-flex align-items-center gap-2 ms-2 text-muted" style="font-size: 12px;">
                    <span class="text-secondary fw-medium">Buffer</span>
                    <i class="bi bi-files cursor-pointer" title="Copier"></i>
                    <i class="bi bi-list-task cursor-pointer" title="Voir liste"></i>
                    <span class="badge bg-primary text-white rounded-1" id="buffer-badge" style="font-size: 10px; padding: 2px 5px;">0</span>
                    <i class="bi bi-x-lg text-muted cursor-pointer" style="font-size: 0.75rem;"></i>
                </div>
            </div>
        </div>

        <!-- Top Right Totals & Remise (Exact design from screenshot) -->
        <div class="col-md-5 d-flex justify-content-md-end align-items-center gap-4 mt-3 mt-md-0">
            <div class="text-md-end">
                <span class="fw-bold text-uppercase d-block mb-1" style="font-size: 12px; color: #0288d1; letter-spacing: 0.5px;">Total HT</span>
                <div class="fw-bold" style="font-size: 26px; color: #0288d1; line-height: 1;" id="header-total-ht">
                    {{ number_format((float) ($order->total_ht ?? 0), 2, ',', ' ') }} €
                </div>
            </div>
            <div class="d-flex flex-column align-items-end" style="width: 110px;">
                <span class="text-muted small mb-1" style="font-size: 11px;">Remise</span>
                <div class="d-flex flex-column gap-1 w-100">
                    <div class="input-group input-group-sm">
                        <input type="number" step="0.01" name="remise_pied" id="remise_pied" value="{{ old('remise_pied', $order->remise_pied ?? 0) }}" class="form-control form-control-sm text-end p-1 border" style="font-size: 12px; border-color: #cbd5e1 !important;">
                        <span class="input-group-text bg-transparent border-0 px-1 text-dark" style="font-size: 12px;">€</span>
                    </div>
                    <div class="input-group input-group-sm">
                        <input type="number" step="0.0001" id="remise_pied_percent" value="0.0000" class="form-control form-control-sm text-end p-1 border" style="font-size: 12px; border-color: #cbd5e1 !important;">
                        <span class="input-group-text bg-transparent border-0 px-1 text-dark" style="font-size: 12px;">%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sticky Notes Collapse Area -->
<div class="collapse mb-3" id="note-collapse">
    <div class="clean-panel p-3">
        <label for="internal_note" class="form-label small fw-bold text-uppercase text-muted">Notes Internes du Devis</label>
        <textarea name="internal_note" id="internal_note" class="form-control" rows="3" placeholder="Écrivez vos notes internes ici...">{{ old('internal_note', $order->internal_note) }}</textarea>
    </div>
</div>

<!-- Tabs Navigation (4 Steps Stepper) -->
<ul class="nav nav-tabs nav-tabs-stepper mb-3" id="orderFormTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active px-4 py-2 fw-semibold stepper-tab-btn" id="info-tab" data-bs-toggle="tab" data-bs-target="#tab-info" type="button" role="tab">
            <i class="bi bi-info-circle me-1"></i> Informations
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link px-4 py-2 fw-semibold stepper-tab-btn" id="produits-tab" data-bs-toggle="tab" data-bs-target="#tab-produits" type="button" role="tab">
            <i class="bi bi-list-ul me-1"></i> Produits
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link px-4 py-2 fw-semibold stepper-tab-btn" id="config-tab" data-bs-toggle="tab" data-bs-target="#tab-config" type="button" role="tab">
            <i class="bi bi-sliders me-1"></i> Configuration
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link px-4 py-2 fw-semibold stepper-tab-btn" id="visualisation-tab" data-bs-toggle="tab" data-bs-target="#tab-visualisation" type="button" role="tab">
            <i class="bi bi-printer me-1"></i> Visualisation
        </button>
    </li>
</ul>

<!-- Tabs Content -->
<div class="tab-content bg-transparent p-0 border-0" id="orderFormTabsContent">
    
    <!-- STEP 1: Informations (Client & Contact - Matches Screenshot 1) -->
    <div class="tab-pane fade show active" id="tab-info" role="tabpanel">
        <div class="card card-premium p-4 shadow-sm border-0 mb-4">
            <div class="row g-4">
                <!-- Left Column: Client -->
                <div class="col-lg-6 border-end pe-lg-4">
                    <h5 class="fw-bold text-dark mb-3">Client</h5>
                    
                    <!-- Row 1: Client Select + Quick Create Button -->
                    <div class="mb-3">
                        <label class="form-label small text-muted mb-1" for="client_id_select">Client</label>
                        <div class="input-group">
                            <select id="client_id_select" class="form-select">
                                <option value="">Sélectionner un client</option>
                                @foreach($clients ?? [] as $cl)
                                    <option value="{{ $cl->id }}" 
                                        @selected(old('client_id', $order->client_id) == $cl->id)
                                        data-name="{{ $cl->raison_sociale }}"
                                        data-code="{{ $cl->cegid_id }}"
                                        data-siret="{{ $cl->code_siret }}"
                                        data-address="{{ trim($cl->adresse_1 . ' ' . $cl->adresse_2) }}"
                                        data-zip="{{ $cl->code_postal }}"
                                        data-city="{{ $cl->ville }}">
                                        {{ $cl->raison_sociale }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-primary rounded-circle ms-2 d-flex align-items-center justify-content-center" id="btn-quick-client" style="width: 38px; height: 38px;" title="Créer un nouveau client">
                                <i class="bi bi-plus-lg fs-5"></i>
                            </button>
                        </div>
                        <input type="hidden" name="client_id" id="client_id" value="{{ old('client_id', $order->client_id) }}">
                    </div>

                    <!-- Row 2: Code & SIRET -->
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1" for="client_code_input">Code</label>
                            <input type="text" id="client_code_input" class="form-control form-control-sm bg-light" value="{{ optional($order->client)->cegid_id }}" placeholder="Code">
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1" for="client_siret_input">SIRET</label>
                            <input type="text" id="client_siret_input" class="form-control form-control-sm bg-light" value="{{ optional($order->client)->code_siret }}" placeholder="SIRET">
                        </div>
                    </div>

                    <!-- Row 3: Adresse -->
                    <div class="mb-3">
                        <label class="form-label small text-muted mb-1" for="client_address_input">Adresse</label>
                        <input type="text" id="client_address_input" class="form-control form-control-sm bg-light" value="{{ trim(optional($order->client)->adresse_1 . ' ' . optional($order->client)->adresse_2) }}" placeholder="Adresse">
                    </div>

                    <!-- Row 4: Code postal & Ville -->
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1" for="client_zip_input">Code postal</label>
                            <input type="text" id="client_zip_input" class="form-control form-control-sm bg-light" value="{{ optional($order->client)->code_postal }}" placeholder="Code postal">
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1" for="client_city_input">Ville</label>
                            <input type="text" id="client_city_input" class="form-control form-control-sm bg-light" value="{{ optional($order->client)->ville }}" placeholder="Ville">
                        </div>
                    </div>
                </div>

                <!-- Right Column: Contact -->
                <div class="col-lg-6 ps-lg-4">
                    <h5 class="fw-bold text-dark mb-3">Contact</h5>

                    <!-- Row 1: Contact Select -->
                    <div class="mb-3">
                        <label class="form-label small text-muted mb-1" for="contact_id">Contact</label>
                        <select name="contact_id" id="contact_id" class="form-select">
                            <option value="">Contact</option>
                            @if ($order->contact)
                                <option value="{{ $order->contact_id }}" selected
                                    data-prenom="{{ $order->contact->prenom }}"
                                    data-nom="{{ $order->contact->nom }}"
                                    data-email="{{ $order->contact->email }}"
                                    data-phone="{{ $order->contact->phone1 ?: $order->contact->mobile }}">
                                    {{ $order->contact->prenom }} {{ $order->contact->nom }}
                                </option>
                            @endif
                        </select>
                    </div>

                    <!-- Row 2: Prénom & Nom -->
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1" for="contact_prenom_input">Prénom</label>
                            <input type="text" id="contact_prenom_input" class="form-control form-control-sm bg-light" value="{{ optional($order->contact)->prenom }}" placeholder="Prénom">
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1" for="contact_nom_input">Nom</label>
                            <input type="text" id="contact_nom_input" class="form-control form-control-sm bg-light" value="{{ optional($order->contact)->nom }}" placeholder="Nom">
                        </div>
                    </div>

                    <!-- Row 3: Email & Téléphone -->
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1" for="contact_email_input">Email</label>
                            <input type="email" id="contact_email_input" class="form-control form-control-sm bg-light" value="{{ optional($order->contact)->email }}" placeholder="Email">
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1" for="contact_phone_input">Téléphone</label>
                            <input type="text" id="contact_phone_input" class="form-control form-control-sm bg-light" value="{{ optional($order->contact)->phone1 ?: optional($order->contact)->mobile }}" placeholder="Téléphone">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden Address Inputs to preserve sync functionality -->
            <input type="hidden" name="addresses[facturation][libelle]" id="addr_fact_libelle" value="{{ optional($addresses['facturation'])->libelle }}">
            <input type="hidden" name="addresses[facturation][adresse1]" id="addr_fact_adresse1" value="{{ optional($addresses['facturation'])->adresse1 }}">
            <input type="hidden" name="addresses[facturation][code_postal]" id="addr_fact_code_postal" value="{{ optional($addresses['facturation'])->code_postal }}">
            <input type="hidden" name="addresses[facturation][ville]" id="addr_fact_ville" value="{{ optional($addresses['facturation'])->ville }}">
            <input type="hidden" name="addresses[livraison][libelle]" id="addr_livr_libelle" value="{{ optional($addresses['livraison'])->libelle }}">
            <input type="hidden" name="addresses[livraison][adresse1]" id="addr_livr_adresse1" value="{{ optional($addresses['livraison'])->adresse1 }}">
            <input type="hidden" name="addresses[livraison][code_postal]" id="addr_livr_code_postal" value="{{ optional($addresses['livraison'])->code_postal }}">
            <input type="hidden" name="addresses[livraison][ville]" id="addr_livr_ville" value="{{ optional($addresses['livraison'])->ville }}">

            <!-- Bottom Step 1 Action -->
            <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm btn-nav-step" data-target-step="#produits-tab">
                    Etape suivante <i class="bi bi-arrow-right ms-1"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- STEP 2: Produits (Product search panel and lines table - Matches Screenshot 100%) -->
    <div class="tab-pane fade" id="tab-produits" role="tabpanel">
        <div class="row g-3">
            
            <!-- Left Panel: Available Products Selection & 5 Action Toolbar -->
            <div class="col-xl-4 col-lg-5">
                <div class="clean-panel p-3" style="min-height: 600px; display: flex; flex-direction: column;">
                    <!-- Top Toolbar: Toggles & 5 Action Buttons -->
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <!-- Toggles layout (Grid / List) -->
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-sm btn-primary p-1 px-2 rounded-1 me-1 shadow-none" style="background-color: #0288d1; border-color: #0288d1;" title="Vue grille"><i class="bi bi-grid-fill"></i></button>
                            <button type="button" class="btn btn-sm btn-light border-0 p-1 px-2 rounded-1 text-muted" title="Vue liste"><i class="bi bi-list"></i></button>
                        </div>

                        <!-- 5 Action Buttons (Toolbar matching screenshot icons & colors) -->
                        <div class="d-flex align-items-center gap-1">
                            <!-- Button 1: Purple Icon (≡+) -> Ajouter une Réserve -->
                            <div class="dropdown">
                                <button type="button" class="btn-clean-action action-purple" id="dropdownReservesBtn" data-bs-toggle="dropdown" aria-expanded="false" title="Ajouter une réserve">
                                    <i class="bi bi-text-indent-left"></i>
                                </button>
                                <ul class="dropdown-menu shadow-lg p-2" id="dropdownReservesMenu" aria-labelledby="dropdownReservesBtn" style="min-width: 360px; max-height: 400px; overflow-y: auto; font-size: 0.85rem; z-index: 9999;">
                                    <li>
                                        <a class="dropdown-item py-2 fw-bold text-primary rounded bg-light mb-1 d-flex align-items-center gap-2" href="#" id="btn-open-reserve-modal-quick">
                                            <i class="bi bi-plus-circle-fill"></i> Nouvelle Réserve
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-1 text-secondary rounded mb-1 d-flex align-items-center gap-2" href="#" id="btn-add-reserve-libre">
                                            <span class="badge bg-secondary rounded-pill px-2">Libre</span> Réserve Libre
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <div id="dropdown-reserves-items-list">
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
                            <button type="button" class="btn-clean-action action-green" title="Choix de modèle de devis" id="btn-open-template-modal">
                                <i class="bi bi-plus-square"></i>
                            </button>

                            <!-- Button 3: Teal circle icon -> Ajouter Chapitre Option -->
                            <button type="button" class="btn-clean-action action-teal" title="Ajouter un chapitre option" id="btn-add-option-chapter">
                                <i class="bi bi-check2-circle"></i>
                            </button>

                            <!-- Button 4: Blue icon (💬) -> Ajouter un Commentaire -->
                            <button type="button" class="btn-clean-action action-blue" title="Ajouter un commentaire" id="btn-add-comment-line">
                                <i class="bi bi-chat-left-text"></i>
                            </button>

                            <!-- Button 5: Orange icon (💳+) -> Ajouter un Chapitre standard -->
                            <button type="button" class="btn-clean-action action-orange" title="Ajouter un chapitre" id="btn-add-chapter">
                                <i class="bi bi-credit-card-2-front"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Title row & Select all checkbox -->
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center gap-1">
                            <span class="fw-bold text-dark" style="font-size: 13px;">Produits disponibles</span>
                            <span class="text-primary fw-bold ms-1" style="font-size: 12px;">$</span>
                            <span class="badge bg-secondary-subtle text-secondary ms-1 rounded-pill" style="font-size: 10px;" id="products-count">0</span>
                        </div>
                        <div class="form-check m-0">
                            <input class="form-check-input" type="checkbox" id="chk-select-all" style="cursor: pointer;">
                        </div>
                    </div>

                    <!-- Search Bar -->
                    <div class="mb-2">
                        <div class="position-relative">
                            <i class="bi bi-search position-absolute top-50 translate-middle-y text-muted" style="left: 10px; font-size: 0.85rem;"></i>
                            <input type="text" id="search-equipments" class="form-control form-control-sm ps-4 border" style="border-color: #d1d5db !important; border-radius: 4px; font-size: 12.5px;" placeholder="Rechercher un article">
                        </div>
                    </div>

                    <!-- 3 Cascading Category Dropdowns in a single row -->
                    <div class="row g-1 mb-2">
                        <div class="col-4">
                            <select id="select-category" class="form-select form-select-sm px-2 text-secondary border" style="border-color: #d1d5db !important; font-size: 11.5px; border-radius: 4px;">
                                <option value="">Catégorie</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <select id="select-family" class="form-select form-select-sm px-2 text-secondary border" style="border-color: #d1d5db !important; font-size: 11.5px; border-radius: 4px;" disabled>
                                <option value="">Famille</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <select id="select-subfamily" class="form-select form-select-sm px-2 text-secondary border" style="border-color: #d1d5db !important; font-size: 11.5px; border-radius: 4px;" disabled>
                                <option value="">Sous-famille</option>
                            </select>
                        </div>
                    </div>

                    <!-- Table Header for Catalog List -->
                    <div class="d-flex justify-content-between align-items-center py-1 px-2 border-bottom text-muted" style="font-size: 11.5px; font-weight: 500; background: #fafbfc;">
                        <span>Produit</span>
                        <span>Prix</span>
                    </div>

                    <!-- Available products list scroll area -->
                    <div id="equipments-list" class="flex-grow-1 overflow-auto clean-custom-scroll" style="max-height: 520px;">
                        <div class="text-center text-muted py-4 small">
                            <i class="bi bi-search me-1"></i> Chargement des articles...
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Order Lines Table (Matches Screenshot 100%) -->
            <div class="col-xl-8 col-lg-7">
                <div class="clean-panel" style="min-height: 600px; display: flex; flex-direction: column;">
                    <!-- Hidden auxiliary buttons for JS compatibility -->
                    <div class="d-none">
                        <button type="button" id="btn-add-section-line"></button>
                        <button type="button" id="add-line-btn"></button>
                    </div>

                    <div class="table-responsive flex-grow-1 overflow-auto clean-custom-scroll">
                        <table class="table clean-quote-table align-middle mb-0" id="lines-table" style="min-width: 780px;">
                            <thead>
                                <tr>
                                    <th style="width: 35px;" class="text-center text-muted"><i class="bi bi-arrow-down-up" style="font-size: 0.75rem;"></i></th>
                                    <th style="width: 35px;" class="text-center"><input class="form-check-input" type="checkbox" id="chk-table-all" checked></th>
                                    <th>Article</th>
                                    <th class="text-center" style="width: 55px;">Qté</th>
                                    <th class="text-end" style="width: 80px;">PxPub</th>
                                    <th class="text-end" style="width: 60px;">R1</th>
                                    <th class="text-end" style="width: 65px;">R2</th>
                                    <th class="text-end" style="width: 80px;">PuHT</th>
                                    <th class="text-end" style="width: 85px;">PxTot</th>
                                    <th style="width: 35px;" class="text-center"><input class="form-check-input" type="checkbox"></th>
                                </tr>
                            </thead>
                            <tbody id="lines-container">
                                @foreach ($lineData as $index => $line)
                                    @include('orders.partials.line', ['index' => $index, 'line' => $line])
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <template id="line-template">
                        @include('orders.partials.line', ['index' => '__INDEX__', 'line' => []])
                    </template>

                    <!-- Step 2 Navigation Footer -->
                    <div class="p-3 border-top d-flex justify-content-between align-items-center bg-white" style="border-radius: 0 0 6px 6px;">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold btn-nav-step" data-target-step="#info-tab">
                            <i class="bi bi-arrow-left me-1"></i> Étape précédente
                        </button>
                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-4 fw-bold shadow-sm btn-nav-step" data-target-step="#config-tab" style="background-color: #0288d1; border-color: #0288d1;">
                            Étape suivante <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- STEP 3: Configuration (Agence, Options, Conditions, Réserves & CGV - Matches Screenshot 3) -->
    <div class="tab-pane fade" id="tab-config" role="tabpanel">
        <div class="row g-4">
            <!-- Left Column: Agence, Options & Conditions -->
            <div class="col-xl-6">
                <!-- Card 1: Agence & Options -->
                <div class="card card-premium p-4 shadow-sm border-0 mb-4">
                    <h5 class="fw-bold text-dark mb-3">Agence & Options</h5>
                    
                    <!-- Agence Select -->
                    <div class="mb-4">
                        <label for="company_id" class="form-label small text-muted mb-1">Agence</label>
                        <select name="company_id" id="company_id" class="form-select">
                            <option value="">Sélectionner une agence</option>
                            @foreach($companies ?? [] as $comp)
                                <option value="{{ $comp->id }}" @selected(old('company_id', $order->company_id) == $comp->id)>
                                    {{ $comp->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Checkboxes -->
                    <div class="d-flex flex-column gap-3">
                        <div class="form-check d-flex align-items-center gap-2">
                            <input class="form-check-input" type="checkbox" name="has_cover_page" id="has_cover_page" value="1" @checked(old('has_cover_page', $order->has_cover_page))>
                            <label class="form-check-label fw-semibold text-dark d-flex align-items-center gap-2" for="has_cover_page">
                                Avec page de garde
                                <i class="bi bi-pencil-square text-muted cursor-pointer" title="Modifier la page de garde"></i>
                            </label>
                        </div>
                        <div class="form-check d-flex align-items-center gap-2">
                            <input class="form-check-input" type="checkbox" name="has_tech_validation" id="has_tech_validation" value="1" @checked(old('has_tech_validation', $order->has_tech_validation))>
                            <label class="form-check-label fw-semibold text-dark" for="has_tech_validation">
                                Avec validation Technique
                            </label>
                        </div>
                        <div class="form-check d-flex align-items-center gap-2">
                            <input class="form-check-input" type="checkbox" name="has_commercial_validation" id="has_commercial_validation" value="1" @checked(old('has_commercial_validation', $order->has_commercial_validation))>
                            <label class="form-check-label fw-semibold text-dark" for="has_commercial_validation">
                                Avec validation Commerciale
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Conditions -->
                <div class="card card-premium p-4 shadow-sm border-0 mb-4">
                    <h5 class="fw-bold text-dark mb-3">Conditions</h5>

                    <!-- Mode de paiement -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="modereglement_id" class="form-label small text-muted mb-0">Mode de paiement</label>
                            <i class="bi bi-pencil-fill text-muted" style="font-size: 0.8rem;"></i>
                        </div>
                        <select name="modereglement_id" id="modereglement_id" class="form-select">
                            <option value="">- Mode de paiement standard -</option>
                            @foreach($conditionsReglement ?? [] as $cond)
                                <option value="{{ $cond->id }}" @selected(old('modereglement_id', $order->modereglement_id) == $cond->id)>
                                    {{ $cond->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Valide jusqu'au -->
                    <div>
                        <label for="validity_date" class="form-label small text-muted mb-1">Valide jusqu'au</label>
                        <div class="input-group">
                            <input type="date" name="validity_date" id="validity_date" class="form-control" value="{{ old('validity_date', optional($order->validity_date)->format('Y-m-d') ?: now()->addDays(30)->format('Y-m-d')) }}">
                            <span class="input-group-text bg-white"><i class="bi bi-calendar-date text-muted"></i></span>
                        </div>
                    </div>
                </div>

                <!-- Hidden Financial Config fields preserved -->
                <input type="hidden" name="depot_id" value="{{ old('depot_id', $order->depot_id) }}">
                <input type="hidden" name="tiers_id" value="{{ old('tiers_id', $order->tiers_id) }}">
                <input type="hidden" name="tiers_facture_id" id="tiers_facture_id" value="{{ old('tiers_facture_id', $order->tiers_facture_id) }}">
                <input type="hidden" name="tiers_livre_id" value="{{ old('tiers_livre_id', $order->tiers_livre_id) }}">
                <input type="hidden" name="regime_taxe_id" value="{{ old('regime_taxe_id', $order->regime_taxe_id) }}">
                <input type="hidden" name="nature_piece" id="nature_piece" value="{{ old('nature_piece', $order->nature_piece ?? 'Commande') }}">
                <input type="hidden" name="ref_interne" id="ref_interne" value="{{ old('ref_interne', $order->ref_interne) }}">
                <input type="hidden" name="date_piece" id="date_piece" value="{{ old('date_piece', optional($order->date_piece)->format('Y-m-d') ?: now()->format('Y-m-d')) }}">
                <input type="hidden" name="date_livraison" id="date_livraison" value="{{ old('date_livraison', optional($order->date_livraison)->format('Y-m-d')) }}">
                <input type="hidden" name="statut" id="statut" value="{{ old('statut', $order->statut ?? 'Brouillon') }}">
                <input type="hidden" name="pdf_template" id="pdf_template_input" value="{{ old('pdf_template', $order->pdf_template ?? 'Devis-Novalyo') }}">
            </div>

            <!-- Right Column: Sub-tabs Réserves & CGV -->
            <div class="col-xl-6">
                <div class="card card-premium p-4 shadow-sm border-0 h-100 d-flex flex-column">
                    <!-- Sub-tabs nav -->
                    <ul class="nav nav-pills mb-3 border-bottom pb-2" id="pills-tab-config-right" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-bold btn-sm rounded-pill px-3" id="pills-reserves-tab" data-bs-toggle="pill" data-bs-target="#pills-reserves" type="button" role="tab">
                                <i class="bi bi-list-columns-reverse me-1"></i> Réserves
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold btn-sm rounded-pill px-3" id="pills-cgv-tab" data-bs-toggle="pill" data-bs-target="#pills-cgv" type="button" role="tab">
                                <i class="bi bi-tools me-1"></i> CGV
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content flex-grow-1" id="pills-tabContent-config-right">
                        <!-- Réserves Sub-tab -->
                        <div class="tab-pane fade show active" id="pills-reserves" role="tabpanel">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold" id="btn-add-reserve-step3">
                                    <i class="bi bi-plus-lg me-1"></i> Réserves
                                </button>
                                <input type="text" id="filter-reserve-input" class="form-control form-control-sm" placeholder="Titre de la réserve...">
                            </div>

                            <!-- List of active reserves -->
                            <div id="reserves-list-container" class="border rounded p-3 bg-light bg-opacity-50 overflow-auto" style="min-height: 220px; max-height: 380px;">
                                <div id="reserves-empty-state" class="text-center py-5 text-muted">
                                    <i class="bi bi-card-checklist fs-1 text-secondary opacity-50 d-block mb-2"></i>
                                    <span class="fw-semibold">Aucune réserve trouvée.</span><br>
                                    <small>Cliquez sur le + pour en ajouter une.</small>
                                </div>
                                <div id="reserves-items-box" class="d-flex flex-column gap-2"></div>
                            </div>
                            <textarea name="reserves" id="reserves_hidden_input" class="d-none">{{ old('reserves', $order->reserves) }}</textarea>
                        </div>

                        <!-- CGV Sub-tab -->
                        <div class="tab-pane fade" id="pills-cgv" role="tabpanel">
                            <div class="p-3 border rounded bg-light bg-opacity-25" style="max-height: 380px; overflow-y: auto; font-size: 0.85rem;">
                                <h6 class="fw-bold text-dark">Conditions Générales de Vente — Novalyo</h6>
                                <p class="text-muted small">Les présentes conditions régissent les ventes et prestations conclues par Novalyo auprès de ses clients professionnels. Toute commande passée emporte adhésion pleine et entière aux présentes CGV.</p>
                                <hr class="my-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="cgv_accepted_check" checked>
                                    <label class="form-check-label small fw-semibold text-dark" for="cgv_accepted_check">
                                        Inclure le document standard des CGV en annexe du devis
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ports collapse/editor -->
                    <div class="mt-4 pt-3 border-top" id="port-section-card">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold text-dark mb-0"><i class="bi bi-truck text-success me-1"></i> Frais de Port</h6>
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" id="add-port">
                                <i class="bi bi-plus-lg me-1"></i> Ajouter port
                            </button>
                        </div>
                        <div id="ports-container" class="d-flex flex-column gap-2">
                            @foreach ($portData as $index => $port)
                                @include('orders.partials.port', ['index' => $index, 'port' => $port])
                            @endforeach
                        </div>
                        <template id="port-template">
                            @include('orders.partials.port', ['index' => '__INDEX__', 'port' => []])
                        </template>
                    </div>

                    <!-- Step 3 Navigation Footer -->
                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold btn-nav-step" data-target-step="#produits-tab">
                            <i class="bi bi-arrow-left me-1"></i> Étape précédente
                        </button>
                        <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm btn-nav-step" data-target-step="#visualisation-tab">
                            Étape suivante <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- STEP 4: Visualisation (PDF Layout - Matches Screenshot 4) -->
    <div class="tab-pane fade" id="tab-visualisation" role="tabpanel">
        <div class="card card-premium shadow-sm border-0 mb-4">
            
            <!-- Top Toolbar -->
            <div class="card-header bg-transparent border-bottom p-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <h5 class="mb-0 fw-bold text-dark">Visualisation PDF</h5>
                    <select id="pdf-template-select" class="form-select form-select-sm" style="width: 200px;">
                        <option value="Devis-Novalyo" selected>Devis-Novalyo</option>
                        <option value="Devis-Standard">Devis-Standard</option>
                    </select>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold" id="btn-generate-pdf-preview">
                        Générer <i class="bi bi-arrow-repeat ms-1"></i>
                    </button>
                    <a href="{{ isset($order->id) && $order->id ? route('orders.pdf', $order) : '#' }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-bold bg-white" id="btn-download-pdf-real" target="_blank">
                        Télécharger PDF <i class="bi bi-download ms-1"></i>
                    </a>
                    <button type="submit" form="order-form" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-check-lg me-1"></i> {{ isset($order->id) && $order->id ? __('locale.orders.buttons.update') : __('locale.orders.buttons.save') }}
                    </button>
                </div>
            </div>

            <!-- PDF Novalyo Visual Mockup Render -->
            <div class="card-body p-4 bg-dark bg-opacity-75 d-flex justify-content-center">
                <div class="bg-white p-4 shadow-lg rounded" style="width: 100%; max-width: 900px; min-height: 800px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;" id="pdf-mockup-sheet">
                    
                    <!-- Novalyo Cyan Header Banner -->
                    <div style="background-color: #008ba3; color: #ffffff; padding: 20px; border-radius: 4px; margin-bottom: 25px;">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h2 class="fw-bold mb-0 text-white fs-3 d-flex align-items-center gap-2">
                                    <i class="bi bi-plus-circle-fill"></i> novalyo
                                </h2>
                                <span class="text-uppercase small" style="letter-spacing: 1.5px; color: #b2ebf2; font-size: 0.75rem;">Intelligences Connectées Santé</span>
                            </div>
                            <div class="text-end small" style="color: #e0f7fa;">
                                commande@novalyo.fr<br>
                                www.novalyo.fr
                            </div>
                        </div>

                        <div class="row g-2 align-items-stretch">
                            <!-- Client Box -->
                            <div class="col-md-5 small">
                                <span style="font-size: 0.7rem; text-transform: uppercase; color: #b2ebf2;" class="d-block mb-1">Devis pour :</span>
                                <strong class="fs-6 d-block text-white" id="vis-client-name">GROUPE NOVALYO</strong>
                                <span id="vis-contact-name" class="d-block">M Anthony ABOU NADER</span>
                                <span id="vis-client-address-line" class="d-block text-white-50">38150 SALAISE SUR SANNE</span>
                            </div>
                            <!-- Commercial Box -->
                            <div class="col-md-3 small">
                                <span style="font-size: 0.7rem; text-transform: uppercase; color: #b2ebf2;" class="d-block mb-1">Commercial :</span>
                                <div>Contact commercial : <strong class="text-white">Mohamed KHELOUFI</strong></div>
                                <div>Rédacteur de l'offre : <strong class="text-white">Ralph NADER</strong></div>
                            </div>
                            <!-- Quote Teal Card -->
                            <div class="col-md-4">
                                <div class="p-2 rounded text-center" style="background-color: #005b6e; color: #ffffff;">
                                    <div class="fw-bold small text-uppercase mb-1" style="font-size: 0.75rem;">Simulation Non Contractuelle</div>
                                    <div class="fw-bold fs-6 mb-1" id="vis-quote-ref">N° B19254-1</div>
                                    <div class="small border-top pt-1 text-white-50" style="font-size: 0.7rem;">
                                        No Client: <strong class="text-white" id="vis-client-no">C025060000</strong> &nbsp;|&nbsp; 
                                        Le: <strong class="text-white" id="vis-quote-date">10/09/2026</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="table-responsive mb-4">
                        <table class="table table-sm align-middle mb-0" id="vis-table">
                            <thead style="background-color: #90a4ae; color: #ffffff;">
                                <tr>
                                    <th style="width: 22%;" class="py-2 px-3">Référence</th>
                                    <th style="width: 44%;" class="py-2 px-3">Désignation</th>
                                    <th style="width: 10%;" class="text-center py-2 px-3">Quantité</th>
                                    <th style="width: 12%;" class="text-end py-2 px-3">Prix unitaire €</th>
                                    <th style="width: 12%;" class="text-end py-2 px-3">Prix total €</th>
                                </tr>
                            </thead>
                            <tbody id="vis-table-body">
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted small">Aucun article dans ce devis.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Totals Block -->
                    <div class="d-flex justify-content-end mb-4">
                        <div style="width: 320px;">
                            <div class="d-flex justify-content-between py-1 border-bottom small">
                                <span class="text-muted">Total HT brut :</span>
                                <strong id="vis-total-brut">0,00 €</strong>
                            </div>
                            <div class="d-flex justify-content-between py-1 border-bottom small text-danger" id="vis-remise-row" style="display: none;">
                                <span>Remise globale :</span>
                                <strong id="vis-remise-val">- 0,00 €</strong>
                            </div>
                            <div class="d-flex justify-content-between py-1 border-bottom small">
                                <span class="fw-bold text-dark">Total Net HT :</span>
                                <strong class="fw-bold text-dark" id="vis-total-net-ht">0,00 €</strong>
                            </div>
                            <div class="d-flex justify-content-between py-1 border-bottom small">
                                <span class="text-muted">T.V.A. (20,00%) :</span>
                                <strong id="vis-tva">0,00 €</strong>
                            </div>
                            <div class="d-flex justify-content-between py-2 fw-bold fs-5 text-primary border-top border-2 border-primary">
                                <span>TOTAL TTC :</span>
                                <span id="vis-total-ttc">0,00 €</span>
                            </div>
                        </div>
                    </div>

                    <!-- Reserves preview block in PDF mockup -->
                    <div id="vis-reserves-box" class="p-3 bg-light rounded border small mb-3" style="display: none;">
                        <h6 class="fw-bold text-dark mb-2 small text-uppercase">Conditions spécifiques & Réserves</h6>
                        <ul id="vis-reserves-ul" class="mb-0 ps-3 text-muted"></ul>
                    </div>

                </div>
            </div>

            <!-- Step 4 Navigation Footer -->
            <div class="card-footer bg-transparent border-top p-3 d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold btn-nav-step" data-target-step="#config-tab">
                    <i class="bi bi-arrow-left me-1"></i> Étape précédente
                </button>
            </div>
        </div>
    </div>

</div>

<!-- Hidden native form inputs -->
<input type="hidden" name="total_ht" id="total_ht_hidden" value="{{ old('total_ht', $order->total_ht ?? 0) }}">
<input type="hidden" name="total_ttc" id="total_ttc_hidden" value="{{ old('total_ttc', $order->total_ttc ?? 0) }}">

<!-- MODAL: Quick Create Client (Opened by blue + button in Step 1) -->
<div class="modal fade" id="modalQuickClient" tabindex="-1" aria-labelledby="modalQuickClientLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-dark" id="modalQuickClientLabel">
                    <i class="bi bi-person-plus text-primary me-2"></i> Créer un nouveau client
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="form-quick-client">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Raison Sociale <span class="text-danger">*</span></label>
                            <input type="text" id="qc_raison_sociale" class="form-control" placeholder="ex: CLINIQUE SAINT ROCH">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Code Client (Cegid)</label>
                            <input type="text" id="qc_cegid_id" class="form-control" placeholder="ex: C025060000">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">SIRET</label>
                            <input type="text" id="qc_code_siret" class="form-control" placeholder="SIRET">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Adresse 1</label>
                            <input type="text" id="qc_adresse_1" class="form-control" placeholder="Numéro et rue">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Adresse 2</label>
                            <input type="text" id="qc_adresse_2" class="form-control" placeholder="Complément d'adresse">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Code Postal</label>
                            <input type="text" id="qc_code_postal" class="form-control" placeholder="Code postal">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">Ville</label>
                            <input type="text" id="qc_ville" class="form-control" placeholder="Ville">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email</label>
                            <input type="email" id="qc_email" class="form-control" placeholder="contact@etablissement.fr">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Téléphone</label>
                            <input type="text" id="qc_tel" class="form-control" placeholder="04 ...">
                        </div>
                        <hr class="my-3">
                        <h6 class="fw-bold text-dark mb-1">Contact associé (optionnel)</h6>
                        <div class="col-md-6">
                            <label class="form-label small">Prénom Contact</label>
                            <input type="text" id="qc_contact_prenom" class="form-control form-control-sm" placeholder="Prénom">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Nom Contact</label>
                            <input type="text" id="qc_contact_nom" class="form-control form-control-sm" placeholder="Nom">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" id="btn-submit-quick-client">
                    <i class="bi bi-check-lg me-1"></i> Créer et sélectionner
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: Ajout d'une nouvelle réserve (Matches screenshot) -->
<div class="modal fade" id="modalOrderReserveQuickAdd" tabindex="-1" aria-labelledby="modalOrderReserveQuickAddTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 8px;">
            <div id="quickReserveForm">
                <!-- Header -->
                <div class="modal-header border-bottom px-4 pt-4 pb-3 align-items-start">
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="modalOrderReserveQuickAddTitle" style="color: #1e3a5f !important; font-size: 1.25rem;">
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
                        <label for="quick_reserve_title" class="form-label text-secondary small fw-semibold mb-2">Réserve</label>
                        <input type="text" class="form-control rounded-2 border py-2" id="quick_reserve_title" name="title" placeholder="" autofocus>
                    </div>
                    <div>
                        <textarea class="form-control rounded-2 border p-3" id="quick_reserve_content" name="content" rows="6" placeholder=""></textarea>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer border-0 px-4 pb-4 pt-0 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary px-4 rounded-2" data-bs-dismiss="modal">
                        Annuler
                    </button>
                    <button type="button" class="btn btn-primary px-4 rounded-2 d-inline-flex align-items-center gap-2" style="background-color: #3b82f6; border-color: #3b82f6;" id="btn-submit-quick-reserve">
                        <span>Enregistrer</span>
                        <i class="bi bi-floppy"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: Choix de Modèle de Devis (Opened by green + button in Step 2 Toolbar - Matches Screenshot 3) -->
<div class="modal fade" id="modalTemplatePicker" tabindex="-1" aria-labelledby="modalTemplatePickerLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom px-4 pt-4 pb-3">
                <h5 class="modal-title fw-bold text-dark" id="modalTemplatePickerLabel">
                    Choix de modèle de devis
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Options (Radio buttons) -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark small mb-2">Options</label>
                    <div class="d-flex align-items-center gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tmpl_mode" id="tmpl_mode_replace" value="replace">
                            <label class="form-check-label small" for="tmpl_mode_replace">Remplacer toutes les lignes du devis</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tmpl_mode" id="tmpl_mode_append" value="append" checked>
                            <label class="form-check-label small fw-semibold" for="tmpl_mode_append">Ajouter les lignes du modèle au devis</label>
                        </div>
                    </div>
                </div>

                <!-- Recherche / Filtres -->
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark small mb-2">Recherche</label>
                    <div class="row g-2 align-items-center">
                        <div class="col-auto">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="tmpl_filter_all" checked>
                                <label class="form-check-label small" for="tmpl_filter_all">Tous</label>
                            </div>
                        </div>
                        <div class="col">
                            <select id="tmpl_filter_group" class="form-select form-select-sm">
                                <option value="">Groupe</option>
                            </select>
                        </div>
                        <div class="col">
                            <select id="tmpl_filter_client" class="form-select form-select-sm">
                                <option value="">Client</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Table of templates -->
                <div class="table-responsive border rounded" style="max-height: 380px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0" id="templates-list-table">
                        <thead class="table-light small text-muted">
                            <tr>
                                <th class="py-2 px-3">Description du modèle</th>
                                <th class="py-2 px-3 text-end" style="width: 100px;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="templates-table-body">
                            <!-- Populated dynamically via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Interactive JavaScript Engine -->
<script>
document.addEventListener('DOMContentLoaded', () => {

    // --- Step Navigation Buttons ---
    document.querySelectorAll('.btn-nav-step').forEach(btn => {
        btn.addEventListener('click', () => {
            const targetSelector = btn.getAttribute('data-target-step');
            const targetTab = document.querySelector(targetSelector);
            if (targetTab) {
                const bootstrapTab = new bootstrap.Tab(targetTab);
                bootstrapTab.show();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    });

    // --- Port and Note toggles ---
    const btnTogglePorts = document.getElementById('btn-toggle-ports');
    if (btnTogglePorts) {
        btnTogglePorts.addEventListener('click', () => {
            const configTab = document.getElementById('config-tab');
            new bootstrap.Tab(configTab).show();
            setTimeout(() => {
                const portCard = document.getElementById('port-section-card');
                if (portCard) portCard.scrollIntoView({ behavior: 'smooth' });
            }, 200);
        });
    }

    const btnToggleNote = document.getElementById('btn-toggle-note');
    const noteCollapse = new bootstrap.Collapse(document.getElementById('note-collapse'), { toggle: false });
    if (btnToggleNote) {
        btnToggleNote.addEventListener('click', () => noteCollapse.toggle());
    }

    // --- Step 1: Client Selection & Field Auto-Population ---
    const clientSelect = document.getElementById('client_id_select');
    const clientHidden = document.getElementById('client_id');
    const codeInput = document.getElementById('client_code_input');
    const siretInput = document.getElementById('client_siret_input');
    const addressInput = document.getElementById('client_address_input');
    const zipInput = document.getElementById('client_zip_input');
    const cityInput = document.getElementById('client_city_input');

    const contactSelect = document.getElementById('contact_id');
    const contactPrenomInput = document.getElementById('contact_prenom_input');
    const contactNomInput = document.getElementById('contact_nom_input');
    const contactEmailInput = document.getElementById('contact_email_input');
    const contactPhoneInput = document.getElementById('contact_phone_input');

    function onClientSelected(clientId, optionEl = null) {
        clientHidden.value = clientId || '';
        if (!clientId) {
            codeInput.value = '';
            siretInput.value = '';
            addressInput.value = '';
            zipInput.value = '';
            cityInput.value = '';
            contactSelect.innerHTML = '<option value="">Contact</option>';
            document.getElementById('header-client-name').textContent = 'Sélectionnez un client';
            document.getElementById('header-client-address').textContent = '';
            return;
        }

        const selectedOption = optionEl || clientSelect.querySelector(`option[value="${clientId}"]`);
        if (selectedOption) {
            codeInput.value = selectedOption.getAttribute('data-code') || '';
            siretInput.value = selectedOption.getAttribute('data-siret') || '';
            addressInput.value = selectedOption.getAttribute('data-address') || '';
            zipInput.value = selectedOption.getAttribute('data-zip') || '';
            cityInput.value = selectedOption.getAttribute('data-city') || '';

            const name = selectedOption.getAttribute('data-name') || selectedOption.textContent.trim();
            document.getElementById('header-client-name').textContent = name;
            document.getElementById('header-client-address').textContent = (zipInput.value + ' ' + cityInput.value).trim();
            
            // Sync hidden address inputs
            document.getElementById('addr_fact_libelle').value = name;
            document.getElementById('addr_fact_adresse1').value = addressInput.value;
            document.getElementById('addr_fact_code_postal').value = zipInput.value;
            document.getElementById('addr_fact_ville').value = cityInput.value;
            document.getElementById('addr_livr_libelle').value = name;
            document.getElementById('addr_livr_adresse1').value = addressInput.value;
            document.getElementById('addr_livr_code_postal').value = zipInput.value;
            document.getElementById('addr_livr_ville').value = cityInput.value;
        }

        // Fetch contacts for this client
        fetch(`/clients/${clientId}/contacts`)
            .then(res => res.json())
            .then(contacts => {
                contactSelect.innerHTML = '<option value="">Contact</option>';
                contacts.forEach((c, idx) => {
                    const opt = document.createElement('option');
                    opt.value = c.id;
                    opt.textContent = [c.prenom, c.nom].filter(Boolean).join(' ');
                    opt.setAttribute('data-prenom', c.prenom || '');
                    opt.setAttribute('data-nom', c.nom || '');
                    opt.setAttribute('data-email', c.email || '');
                    opt.setAttribute('data-phone', c.phone1 || c.mobile || '');
                    if (idx === 0 && !contactSelect.value) {
                        opt.selected = true;
                    }
                    contactSelect.appendChild(opt);
                });
                onContactSelected();
            });

        loadEquipments();
    }

    if (clientSelect) {
        clientSelect.addEventListener('change', () => {
            onClientSelected(clientSelect.value);
        });
    }

    function onContactSelected() {
        const selected = contactSelect.options[contactSelect.selectedIndex];
        if (selected && selected.value) {
            contactPrenomInput.value = selected.getAttribute('data-prenom') || '';
            contactNomInput.value = selected.getAttribute('data-nom') || '';
            contactEmailInput.value = selected.getAttribute('data-email') || '';
            contactPhoneInput.value = selected.getAttribute('data-phone') || '';
        } else {
            contactPrenomInput.value = '';
            contactNomInput.value = '';
            contactEmailInput.value = '';
            contactPhoneInput.value = '';
        }
    }

    if (contactSelect) {
        contactSelect.addEventListener('change', onContactSelected);
    }

    // --- Quick Create Client Modal ---
    const btnQuickClient = document.getElementById('btn-quick-client');
    const modalQuickClient = new bootstrap.Modal(document.getElementById('modalQuickClient'));
    const btnSubmitQuickClient = document.getElementById('btn-submit-quick-client');

    if (btnQuickClient) {
        btnQuickClient.addEventListener('click', () => modalQuickClient.show());
    }

    if (btnSubmitQuickClient) {
        btnSubmitQuickClient.addEventListener('click', () => {
            const raisonSociale = document.getElementById('qc_raison_sociale').value;
            if (!raisonSociale) {
                alert('La raison sociale est requise.');
                return;
            }

            const payload = {
                _token: '{{ csrf_token() }}',
                raison_sociale: raisonSociale,
                cegid_id: document.getElementById('qc_cegid_id').value,
                code_siret: document.getElementById('qc_code_siret').value,
                adresse_1: document.getElementById('qc_adresse_1').value,
                adresse_2: document.getElementById('qc_adresse_2').value,
                code_postal: document.getElementById('qc_code_postal').value,
                ville: document.getElementById('qc_ville').value,
                email: document.getElementById('qc_email').value,
                tel_1: document.getElementById('qc_tel').value,
                contact_prenom: document.getElementById('qc_contact_prenom').value,
                contact_nom: document.getElementById('qc_contact_nom').value,
            };

            fetch('{{ route("orders.quick-client") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.client) {
                    const cl = data.client;
                    const opt = document.createElement('option');
                    opt.value = cl.id;
                    opt.textContent = cl.raison_sociale;
                    opt.setAttribute('data-name', cl.raison_sociale);
                    opt.setAttribute('data-code', cl.cegid_id || '');
                    opt.setAttribute('data-siret', cl.code_siret || '');
                    opt.setAttribute('data-address', (cl.adresse_1 || '') + ' ' + (cl.adresse_2 || ''));
                    opt.setAttribute('data-zip', cl.code_postal || '');
                    opt.setAttribute('data-city', cl.ville || '');
                    opt.selected = true;
                    clientSelect.appendChild(opt);

                    onClientSelected(cl.id, opt);
                    modalQuickClient.hide();
                    document.getElementById('form-quick-client').reset();
                } else {
                    alert('Erreur lors de la création du client.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Erreur réseau.');
            });
        });
    }

    // --- Recalculation Engine (Compound discount R1/R2, chapter sub-totals, global Total HT) ---
    function recalculate() {
        let totalLinesHt = 0;
        let currentChapterSubtotal = 0;
        let activeChapterRow = null;

        const rows = document.querySelectorAll('#lines-container tr[data-item]');

        rows.forEach((row, i) => {
            const posInput = row.querySelector('.line-position');
            if (posInput) posInput.value = i + 1;

            const type = row.getAttribute('data-type') || 'Article';

            if (type === 'Chapitre' || type === 'Option') {
                // If previous chapter existed, output its subtotal
                if (activeChapterRow) {
                    const subtotalEl = activeChapterRow.querySelector('.chapter-subtotal-display');
                    if (subtotalEl) subtotalEl.textContent = currentChapterSubtotal.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';
                }
                activeChapterRow = row;
                currentChapterSubtotal = 0;
                return;
            }

            if (type === 'Article') {
                const qteInput = row.querySelector('.line-qte');
                const puHtInput = row.querySelector('.line-pu-ht');
                const remise1Input = row.querySelector('.line-remise-ligne');
                const remise2Input = row.querySelector('.line-remise-2');
                const puHtNetInput = row.querySelector('.line-pu-ht-net');
                const montantHtInput = row.querySelector('.line-montant-ht');
                const isOptionInput = row.querySelector('.line-is-option');

                const qte = parseFloat(qteInput.value) || 0;
                const puHt = parseFloat(puHtInput.value) || 0;
                const r1 = parseFloat(remise1Input.value) || 0;
                const r2 = parseFloat(remise2Input.value) || 0;
                const isOption = isOptionInput && isOptionInput.value == '1';

                // Net Unit Price: puHt * (1 - r1/100) * (1 - r2/100)
                const puHtNet = puHt * (1 - r1 / 100) * (1 - r2 / 100);
                const montantHt = qte * puHtNet;

                if (puHtNetInput) puHtNetInput.value = puHtNet.toFixed(2);
                if (montantHtInput) montantHtInput.value = montantHt.toFixed(2);

                const pxPubDisplay = row.querySelector('.line-pxpub-display');
                if (pxPubDisplay) pxPubDisplay.textContent = puHt.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';

                const puHtNetDisplay = row.querySelector('.line-pu-ht-net-display');
                if (puHtNetDisplay) puHtNetDisplay.textContent = puHtNet.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';

                const montantHtDisplay = row.querySelector('.line-montant-ht-display');
                if (montantHtDisplay) montantHtDisplay.textContent = montantHt.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';

                currentChapterSubtotal += montantHt;

                if (!isOption) {
                    totalLinesHt += montantHt;
                }
            }
        });

        // Set last active chapter subtotal
        if (activeChapterRow) {
            const subtotalEl = activeChapterRow.querySelector('.chapter-subtotal-display');
            if (subtotalEl) subtotalEl.textContent = currentChapterSubtotal.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';
        }

        // Footer discount calculation
        const remisePiedInput = document.getElementById('remise_pied');
        const remisePiedPercentInput = document.getElementById('remise_pied_percent');
        const discountVal = parseFloat(remisePiedInput ? remisePiedInput.value : 0) || 0;
        const totalHt = Math.max(0, totalLinesHt - discountVal);
        const totalTtc = totalHt * 1.20;

        // Update displays & hidden inputs
        const headerTotalHt = document.getElementById('header-total-ht');
        if (headerTotalHt) headerTotalHt.textContent = totalHt.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';

        const hiddenTotalHt = document.getElementById('total_ht_hidden');
        if (hiddenTotalHt) hiddenTotalHt.value = totalHt.toFixed(2);
        const hiddenTotalTtc = document.getElementById('total_ttc_hidden');
        if (hiddenTotalTtc) hiddenTotalTtc.value = totalTtc.toFixed(2);

        if (totalLinesHt > 0 && remisePiedPercentInput && document.activeElement !== remisePiedPercentInput) {
            remisePiedPercentInput.value = ((discountVal / totalLinesHt) * 100).toFixed(4);
        }
    }

    // Chapter Collapse / Expand Toggle
    document.addEventListener('click', (e) => {
        const toggleIcon = e.target.closest('.chapter-toggle-icon');
        if (!toggleIcon) return;
        const chapterRow = toggleIcon.closest('tr[data-item]');
        if (!chapterRow) return;

        const isCollapsed = toggleIcon.classList.contains('bi-chevron-down');
        if (isCollapsed) {
            toggleIcon.classList.remove('bi-chevron-down');
            toggleIcon.classList.add('bi-chevron-up');
        } else {
            toggleIcon.classList.remove('bi-chevron-up');
            toggleIcon.classList.add('bi-chevron-down');
        }

        let next = chapterRow.nextElementSibling;
        while (next && !next.matches('tr[data-type="Chapitre"], tr[data-type="Option"]')) {
            if (isCollapsed) {
                next.style.display = '';
            } else {
                next.style.display = 'none';
            }
            next = next.nextElementSibling;
        }
    });

    // Line input listeners
    const linesContainer = document.getElementById('lines-container');
    linesContainer.addEventListener('input', (e) => {
        if (e.target.classList.contains('line-qte') ||
            e.target.classList.contains('line-pu-ht') ||
            e.target.classList.contains('line-remise-ligne') ||
            e.target.classList.contains('line-remise-2')) {
            recalculate();
        }
    });

    const remisePiedInput = document.getElementById('remise_pied');
    const remisePiedPercentInput = document.getElementById('remise_pied_percent');
    remisePiedInput.addEventListener('input', recalculate);
    remisePiedPercentInput.addEventListener('input', () => {
        let sumLines = 0;
        document.querySelectorAll('#lines-container tr[data-item][data-type="Article"]').forEach(r => {
            const isOpt = r.querySelector('.line-is-option')?.value == '1';
            if (!isOpt) sumLines += parseFloat(r.querySelector('.line-montant-ht').value) || 0;
        });
        const pct = parseFloat(remisePiedPercentInput.value) || 0;
        remisePiedInput.value = (sumLines * (pct / 100)).toFixed(2);
        recalculate();
    });

    // Remove row listener
    document.body.addEventListener('click', (e) => {
        if (e.target.matches('[data-remove-item]') || e.target.closest('[data-remove-item]')) {
            e.preventDefault();
            const row = e.target.closest('[data-item]');
            if (row) {
                row.remove();
                recalculate();
                if (typeof loadEquipments === 'function') {
                    loadEquipments();
                }
            }
        }
    });

    // --- TOOLBAR BUTTON 1: Add Reserve Dropdown (Mauve Icon) & Modal ---
    const modalQuickReserveEl = document.getElementById('modalOrderReserveQuickAdd');
    const modalQuickReserve = modalQuickReserveEl ? new bootstrap.Modal(modalQuickReserveEl) : null;
    const quickReserveForm = document.getElementById('quickReserveForm');

    function openQuickReserveModal() {
        if (modalQuickReserve) {
            document.getElementById('quick_reserve_title').value = '';
            document.getElementById('quick_reserve_content').value = '';
            modalQuickReserve.show();
        }
    }

    const btnOpenReserveQuick = document.getElementById('btn-open-reserve-modal-quick');
    if (btnOpenReserveQuick) {
        btnOpenReserveQuick.addEventListener('click', (e) => {
            e.preventDefault();
            openQuickReserveModal();
        });
    }

    const btnSubmitQuickReserve = document.getElementById('btn-submit-quick-reserve');
    function submitQuickReserve() {
        const title = document.getElementById('quick_reserve_title').value.trim();
        const content = document.getElementById('quick_reserve_content').value.trim();
        if (!title) return;

        if (btnSubmitQuickReserve) btnSubmitQuickReserve.disabled = true;

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
            if (btnSubmitQuickReserve) btnSubmitQuickReserve.disabled = false;
            if (data.success && data.reserve) {
                const resText = (data.reserve.content && data.reserve.content.length > 0)
                    ? `${data.reserve.title} - ${data.reserve.content}`
                    : data.reserve.title;
                addReserveToQuote(resText);

                // Add to dropdown list
                const listContainer = document.getElementById('dropdown-reserves-items-list');
                if (listContainer) {
                    const emptyMsg = listContainer.querySelector('.empty-reserves-msg');
                    if (emptyMsg) emptyMsg.remove();

                    const li = document.createElement('li');
                    li.innerHTML = `<a class="dropdown-item py-2 text-wrap text-secondary rounded item-reserve-predef" href="#" data-text="${escapeHtml(resText)}">${escapeHtml(resText)}</a>`;
                    li.querySelector('a').addEventListener('click', (ev) => {
                        ev.preventDefault();
                        addReserveToQuote(resText);
                    });
                    listContainer.prepend(li);
                }

                if (modalQuickReserve) modalQuickReserve.hide();
            } else {
                alert(data.message || 'Erreur lors de l\'enregistrement de la réserve.');
            }
        })
        .catch(err => {
            if (btnSubmitQuickReserve) btnSubmitQuickReserve.disabled = false;
            alert('Erreur de communication avec le serveur.');
        });
    }

    if (btnSubmitQuickReserve) {
        btnSubmitQuickReserve.addEventListener('click', submitQuickReserve);
    }
    const quickReserveTitleInput = document.getElementById('quick_reserve_title');
    if (quickReserveTitleInput) {
        quickReserveTitleInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                submitQuickReserve();
            }
        });
    }

    function addReserveToQuote(reserveText) {
        if (!reserveText) return;
        const hiddenReserves = document.getElementById('reserves_hidden_input');
        const current = hiddenReserves.value ? hiddenReserves.value.split('\n').filter(Boolean) : [];
        if (!current.includes(reserveText)) {
            current.push(reserveText);
            hiddenReserves.value = current.join('\n');
            renderReservesList();
            // Notification toast
            const toast = document.createElement('div');
            toast.className = 'position-fixed bottom-0 end-0 p-3';
            toast.style.zIndex = 11000;
            toast.innerHTML = `<div class="toast show align-items-center text-white bg-success border-0" role="alert"><div class="d-flex"><div class="toast-body"><i class="bi bi-check-circle me-2"></i> Réserve ajoutée : ${escapeHtml(reserveText.substring(0, 45))}...</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div></div>`;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3500);
        }
    }

    document.querySelectorAll('.item-reserve-predef').forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            addReserveToQuote(item.getAttribute('data-text'));
        });
    });

    document.getElementById('btn-add-reserve-libre').addEventListener('click', (e) => {
        e.preventDefault();
        const text = prompt('Saisir le texte de la réserve libre :');
        if (text) addReserveToQuote(text.trim());
    });

    // Step 3 reserves render
    function renderReservesList() {
        const hiddenReserves = document.getElementById('reserves_hidden_input');
        const box = document.getElementById('reserves-items-box');
        const emptyState = document.getElementById('reserves-empty-state');
        const lines = hiddenReserves.value ? hiddenReserves.value.split('\n').filter(Boolean) : [];

        box.innerHTML = '';
        if (lines.length === 0) {
            emptyState.style.display = 'block';
            return;
        }
        emptyState.style.display = 'none';

        lines.forEach((res, i) => {
            const card = document.createElement('div');
            card.className = 'p-2 bg-white rounded border d-flex justify-content-between align-items-center shadow-sm';
            card.innerHTML = `
                <span class="small text-dark"><i class="bi bi-shield-check text-success me-2"></i> ${escapeHtml(res)}</span>
                <button type="button" class="btn btn-sm btn-link text-danger p-0" title="Supprimer"><i class="bi bi-trash"></i></button>
            `;
            card.querySelector('button').addEventListener('click', () => {
                lines.splice(i, 1);
                hiddenReserves.value = lines.join('\n');
                renderReservesList();
            });
            box.appendChild(card);
        });
    }

    const btnAddReserveStep3 = document.getElementById('btn-add-reserve-step3');
    if (btnAddReserveStep3) {
        btnAddReserveStep3.addEventListener('click', () => {
            const filterInput = document.getElementById('filter-reserve-input');
            const val = filterInput ? filterInput.value.trim() : '';
            if (val) {
                addReserveToQuote(val);
                filterInput.value = '';
            } else {
                openQuickReserveModal();
            }
        });
    }

    renderReservesList();

    // --- TOOLBAR BUTTON 2: Quote Templates Modal (Green Icon) ---
    const modalTemplatePicker = new bootstrap.Modal(document.getElementById('modalTemplatePicker'));
    const btnOpenTemplateModal = document.getElementById('btn-open-template-modal');
    const templatesTableBody = document.getElementById('templates-table-body');

    function loadTemplates() {
        fetch('{{ route("orders.templates") }}')
            .then(res => res.json())
            .then(templates => {
                templatesTableBody.innerHTML = '';
                if (templates.length === 0) {
                    templatesTableBody.innerHTML = '<tr><td colspan="2" class="text-center py-3 text-muted">Aucun modèle disponible.</td></tr>';
                    return;
                }
                templates.forEach(t => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="py-2 px-3">
                            <strong class="text-dark">${t.name}</strong>
                            ${t.description ? `<br><small class="text-muted">${t.description}</small>` : ''}
                        </td>
                        <td class="py-2 px-3 text-end">
                            <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold" data-id="${t.id}">
                                Choisir
                            </button>
                        </td>
                    `;
                    tr.querySelector('button').addEventListener('click', () => {
                        applyTemplate(t.id);
                    });
                    templatesTableBody.appendChild(tr);
                });
            });
    }

    if (btnOpenTemplateModal) {
        btnOpenTemplateModal.addEventListener('click', () => {
            loadTemplates();
            modalTemplatePicker.show();
        });
    }

    function applyTemplate(templateId) {
        fetch(`/orders/templates/${templateId}`)
            .then(res => res.json())
            .then(tmpl => {
                const mode = document.querySelector('input[name="tmpl_mode"]:checked').value;
                if (mode === 'replace') {
                    linesContainer.innerHTML = '';
                }

                if (tmpl.lines && tmpl.lines.length > 0) {
                    tmpl.lines.forEach(line => {
                        insertTemplateLine(line);
                    });
                }
                modalTemplatePicker.hide();
                recalculate();
            });
    }

    function insertTemplateLine(line) {
        const index = linesContainer.querySelectorAll('tr[data-item]').length;
        const type = line.type_ligne || 'Article';

        if (type === 'Chapitre') {
            insertChapterRow(line.libelle || line.chapter_title || 'Téléphonie');
        } else if (type === 'Section') {
            insertSectionRow(line.libelle || 'Section');
        } else if (type === 'Option') {
            insertOptionChapterRow(line.libelle || 'Option');
        } else if (type === 'Commentaire') {
            insertCommentRow(line.libelle || '');
        } else {
            // Article line
            const templateHtml = document.getElementById('line-template').innerHTML.replace(/__INDEX__/g, index);
            linesContainer.insertAdjacentHTML('beforeend', templateHtml);
            const row = linesContainer.querySelector('tr[data-item]:last-child');
            row.querySelector('.line-code-article').value = line.code_article || '';
            row.querySelector('.line-libelle').value = line.libelle || '';
            row.querySelector('.line-equipment-id').value = line.equipment_id || '';
            row.querySelector('.line-qte').value = line.qte || 1;
            row.querySelector('.line-pu-ht').value = parseFloat(line.pu_ht || 0).toFixed(2);
            row.querySelector('.line-remise-ligne').value = parseFloat(line.remise_ligne || 0).toFixed(2);
            row.querySelector('.line-remise-2').value = parseFloat(line.remise_2 || 0).toFixed(2);
        }
    }

    // --- TOOLBAR BUTTON 3: Add Option Chapter (Green Circle Icon) ---
    function insertOptionChapterRow(title = 'Titre du chapitre...') {
        const index = linesContainer.querySelectorAll('tr[data-item]').length;
        const tr = document.createElement('tr');
        tr.setAttribute('data-item', '');
        tr.setAttribute('data-type', 'Option');
        tr.className = 'align-middle option-chapter-row';
        tr.style.cssText = 'background-color: #f8fafc; border-left: 4px solid #0d6efd;';
        tr.innerHTML = `
            <input type="hidden" name="lines[${index}][position]" class="line-position" value="${index + 1}">
            <input type="hidden" name="lines[${index}][type_ligne]" class="line-type-ligne" value="Option">
            <input type="hidden" name="lines[${index}][is_option]" class="line-is-option" value="1">
            <input type="hidden" name="lines[${index}][code_article]" class="line-code-article" value="">
            <input type="hidden" name="lines[${index}][equipment_id]" class="line-equipment-id" value="">
            <input type="hidden" name="lines[${index}][qte]" class="line-qte" value="0">
            <input type="hidden" name="lines[${index}][pu_ht]" class="line-pu-ht" value="0">
            <input type="hidden" name="lines[${index}][pu_ht_net]" class="line-pu-ht-net" value="0">
            <input type="hidden" name="lines[${index}][remise_ligne]" class="line-remise-ligne" value="0">
            <input type="hidden" name="lines[${index}][remise_2]" class="line-remise-2" value="0">
            <input type="hidden" name="lines[${index}][montant_ht]" class="line-montant-ht" value="0">

            <td class="text-center" style="width: 40px; cursor: move;"><i class="bi bi-chevron-down text-muted small"></i></td>
            <td class="text-center" style="width: 40px;"><input class="form-check-input" type="checkbox"></td>
            <td colspan="4" class="py-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-2 py-1 fw-bold small d-inline-flex align-items-center gap-1">
                        <i class="bi bi-gear-wide-connected"></i> Option
                    </span>
                    <i class="bi bi-exclamation-triangle text-danger"></i>
                    <input type="text" name="lines[${index}][libelle]" class="form-control form-control-sm fw-semibold border-0 bg-transparent text-secondary p-0 line-libelle" value="${title}" placeholder="Titre du chapitre...">
                </div>
            </td>
            <td colspan="2" class="text-end fw-bold text-secondary py-2">
                <span class="small text-muted me-2">Sous-total :</span>
                <span class="chapter-subtotal-display">0,00 €</span>
            </td>
            <td class="text-center" style="width: 50px;">
                <button type="button" class="btn btn-sm btn-link text-danger p-0 border-0" data-remove-item><i class="bi bi-x-lg fs-5"></i></button>
            </td>
        `;
        linesContainer.appendChild(tr);
        tr.scrollIntoView({ behavior: 'smooth' });
        recalculate();
    }

    document.getElementById('btn-add-option-chapter').addEventListener('click', () => insertOptionChapterRow());

    // --- TOOLBAR BUTTON 4: Add Comment Line (Blue Icon) ---
    function insertCommentRow(text = 'Commentaire...') {
        const index = linesContainer.querySelectorAll('tr[data-item]').length;
        const tr = document.createElement('tr');
        tr.setAttribute('data-item', '');
        tr.setAttribute('data-type', 'Commentaire');
        tr.className = 'align-middle comment-row';
        tr.style.backgroundColor = '#fdfbf7';
        tr.innerHTML = `
            <input type="hidden" name="lines[${index}][position]" class="line-position" value="${index + 1}">
            <input type="hidden" name="lines[${index}][type_ligne]" class="line-type-ligne" value="Commentaire">
            <input type="hidden" name="lines[${index}][code_article]" class="line-code-article" value="COMMENTAIRE">
            <input type="hidden" name="lines[${index}][equipment_id]" class="line-equipment-id" value="">
            <input type="hidden" name="lines[${index}][qte]" class="line-qte" value="0">
            <input type="hidden" name="lines[${index}][pu_ht]" class="line-pu-ht" value="0">
            <input type="hidden" name="lines[${index}][pu_ht_net]" class="line-pu-ht-net" value="0">
            <input type="hidden" name="lines[${index}][remise_ligne]" class="line-remise-ligne" value="0">
            <input type="hidden" name="lines[${index}][remise_2]" class="line-remise-2" value="0">
            <input type="hidden" name="lines[${index}][montant_ht]" class="line-montant-ht" value="0">

            <td class="text-center" style="width: 40px; cursor: move;"><i class="bi bi-arrow-down-up text-muted small"></i></td>
            <td class="text-center" style="width: 40px;"><i class="bi bi-chat-quote text-primary"></i></td>
            <td colspan="6" class="py-2">
                <input type="text" name="lines[${index}][libelle]" class="form-control form-control-sm fst-italic border-0 bg-transparent text-secondary p-0 line-libelle" value="${text}" placeholder="Tapez votre commentaire...">
            </td>
            <td class="text-center" style="width: 50px;">
                <button type="button" class="btn btn-sm btn-link text-danger p-0 border-0" data-remove-item><i class="bi bi-x-lg fs-5"></i></button>
            </td>
        `;
        linesContainer.appendChild(tr);
        tr.scrollIntoView({ behavior: 'smooth' });
    }

    document.getElementById('btn-add-comment-line').addEventListener('click', () => insertCommentRow());

    // --- TOOLBAR BUTTON 5: Add Standard Chapter (Orange Icon) ---
    function insertChapterRow(title = 'Téléphonie') {
        const index = linesContainer.querySelectorAll('tr[data-item]').length;
        const chapId = 'chap_' + Date.now();
        const tr = document.createElement('tr');
        tr.setAttribute('data-item', '');
        tr.setAttribute('data-type', 'Chapitre');
        tr.className = 'align-middle chapter-row';
        tr.style.cssText = 'background-color: #fcecd7; border-top: 2px solid #f8c291; border-bottom: 2px solid #f8c291;';
        tr.innerHTML = `
            <input type="hidden" name="lines[${index}][position]" class="line-position" value="${index + 1}">
            <input type="hidden" name="lines[${index}][type_ligne]" class="line-type-ligne" value="Chapitre">
            <input type="hidden" name="lines[${index}][chapter_id]" class="line-chapter-id" value="${chapId}">
            <input type="hidden" name="lines[${index}][chapter_title]" class="line-chapter-title" value="${title}">
            <input type="hidden" name="lines[${index}][code_article]" class="line-code-article" value="">
            <input type="hidden" name="lines[${index}][equipment_id]" class="line-equipment-id" value="">
            <input type="hidden" name="lines[${index}][qte]" class="line-qte" value="0">
            <input type="hidden" name="lines[${index}][pu_ht]" class="line-pu-ht" value="0">
            <input type="hidden" name="lines[${index}][pu_ht_net]" class="line-pu-ht-net" value="0">
            <input type="hidden" name="lines[${index}][remise_ligne]" class="line-remise-ligne" value="0">
            <input type="hidden" name="lines[${index}][remise_2]" class="line-remise-2" value="0">
            <input type="hidden" name="lines[${index}][montant_ht]" class="line-montant-ht" value="0">

            <td class="text-center" style="width: 40px; cursor: move;"><i class="bi bi-chevron-down text-dark small"></i></td>
            <td class="text-center" style="width: 40px;"><input class="form-check-input" type="checkbox" checked></td>
            <td colspan="4" class="py-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-folder2-open text-warning fs-5"></i>
                    <input type="text" name="lines[${index}][libelle]" class="form-control form-control-sm fw-bold border-0 bg-transparent text-dark p-0 line-libelle fs-6" value="${title}" placeholder="Titre du chapitre...">
                </div>
            </td>
            <td colspan="2" class="text-end fw-bold text-dark py-2">
                <span class="small text-muted me-2 font-normal">Sous-total :</span>
                <span class="chapter-subtotal-display fs-6 fw-bold">0,00 €</span>
            </td>
            <td class="text-center" style="width: 50px;">
                <button type="button" class="btn btn-sm btn-link text-danger p-0 border-0" data-remove-item><i class="bi bi-x-lg fs-5"></i></button>
            </td>
        `;
        linesContainer.appendChild(tr);
        tr.scrollIntoView({ behavior: 'smooth' });
        recalculate();
    }

    document.getElementById('btn-add-chapter').addEventListener('click', () => insertChapterRow());

    // Sub-section button
    function insertSectionRow(title = 'Section') {
        const index = linesContainer.querySelectorAll('tr[data-item]').length;
        const tr = document.createElement('tr');
        tr.setAttribute('data-item', '');
        tr.setAttribute('data-type', 'Section');
        tr.className = 'align-middle section-row';
        tr.style.backgroundColor = '#fafafa';
        tr.innerHTML = `
            <input type="hidden" name="lines[${index}][position]" class="line-position" value="${index + 1}">
            <input type="hidden" name="lines[${index}][type_ligne]" class="line-type-ligne" value="Section">
            <input type="hidden" name="lines[${index}][code_article]" class="line-code-article" value="">
            <input type="hidden" name="lines[${index}][equipment_id]" class="line-equipment-id" value="">
            <input type="hidden" name="lines[${index}][qte]" class="line-qte" value="0">
            <input type="hidden" name="lines[${index}][pu_ht]" class="line-pu-ht" value="0">
            <input type="hidden" name="lines[${index}][pu_ht_net]" class="line-pu-ht-net" value="0">
            <input type="hidden" name="lines[${index}][remise_ligne]" class="line-remise-ligne" value="0">
            <input type="hidden" name="lines[${index}][remise_2]" class="line-remise-2" value="0">
            <input type="hidden" name="lines[${index}][montant_ht]" class="line-montant-ht" value="0">

            <td class="text-center" style="width: 40px; cursor: move;"><i class="bi bi-arrow-down-up text-muted small"></i></td>
            <td class="text-center" style="width: 40px;"><input class="form-check-input" type="checkbox" checked></td>
            <td colspan="6" class="py-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-light text-primary border rounded p-1"><i class="bi bi-card-heading"></i></span>
                    <input type="text" name="lines[${index}][libelle]" class="form-control form-control-sm fw-semibold border-0 bg-transparent text-primary p-0 line-libelle" value="${title}" placeholder="Nom de la section...">
                </div>
            </td>
            <td class="text-center" style="width: 50px;">
                <button type="button" class="btn btn-sm btn-link text-danger p-0 border-0" data-remove-item><i class="bi bi-x-lg fs-5"></i></button>
            </td>
        `;
        linesContainer.appendChild(tr);
        tr.scrollIntoView({ behavior: 'smooth' });
    }

    document.getElementById('btn-add-section-line').addEventListener('click', () => insertSectionRow());

    // Add empty article line
    document.getElementById('add-line-btn').addEventListener('click', () => {
        const index = linesContainer.querySelectorAll('tr[data-item]').length;
        const templateHtml = document.getElementById('line-template').innerHTML.replace(/__INDEX__/g, index);
        linesContainer.insertAdjacentHTML('beforeend', templateHtml);
        const row = linesContainer.querySelector('tr[data-item]:last-child');
        if (row) row.scrollIntoView({ behavior: 'smooth' });
        recalculate();
    });

    // --- Available Products AJAX Loader & Cascading Selects ---
    let categories = [];
    function loadCategories() {
        fetch('{{ route("orders.categories") }}')
            .then(res => res.json())
            .then(data => {
                categories = data;
                buildCategoryDropdown();
            });
    }

    function buildCategoryDropdown() {
        const select = document.getElementById('select-category');
        select.innerHTML = '<option value="">Catégorie</option>';
        const topLevel = categories.filter(c => !c.parent_id);
        topLevel.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.id;
            opt.textContent = c.name_raw;
            select.appendChild(opt);
        });
    }

    document.getElementById('select-category').addEventListener('change', (e) => {
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

    document.getElementById('select-family').addEventListener('change', (e) => {
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

    document.getElementById('select-subfamily').addEventListener('change', loadEquipments);

    let searchTimeout = null;
    document.getElementById('search-equipments').addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(loadEquipments, 300);
    });

    function loadEquipments() {
        const q = document.getElementById('search-equipments').value;
        const clientId = document.getElementById('client_id').value;
        let categoryId = document.getElementById('select-subfamily').value || document.getElementById('select-family').value || document.getElementById('select-category').value;

        const url = new URL('{{ route("orders.equipments") }}', window.location.origin);
        if (q) url.searchParams.append('q', q);
        if (clientId) url.searchParams.append('client_id', clientId);
        if (categoryId) url.searchParams.append('category_id', categoryId);

        const container = document.getElementById('equipments-list');
        container.innerHTML = '<div class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary"></div></div>';

        fetch(url.toString())
            .then(res => res.json())
            .then(equipments => {
                container.innerHTML = '';

                // Get existing articles in the quote table to filter them out
                const existingIds = new Set();
                const existingRefs = new Set();
                document.querySelectorAll('#lines-container tr[data-item]').forEach(row => {
                    const eqId = row.querySelector('.line-equipment-id')?.value;
                    const code = row.querySelector('.line-code-article')?.value;
                    if (eqId) existingIds.add(String(eqId));
                    if (code) existingRefs.add(code.trim().toUpperCase());
                });

                const availableEquipments = equipments.filter(eq => {
                    return !existingIds.has(String(eq.id)) && !existingRefs.has(String(eq.reference || '').trim().toUpperCase());
                });

                document.getElementById('products-count').textContent = availableEquipments.length;

                if (availableEquipments.length === 0) {
                    container.innerHTML = '<div class="text-center py-4 text-muted small">Aucun article disponible.</div>';
                    return;
                }

                availableEquipments.forEach(eq => {
                    const card = document.createElement('div');
                    card.className = 'py-2 px-2 border-bottom cursor-pointer clean-catalog-item';
                    card.style.transition = 'opacity 0.2s ease, transform 0.2s ease, background-color 0.15s';
                    card.setAttribute('data-eq-id', eq.id);
                    card.setAttribute('data-reference', eq.reference);

                    let substitutesBadge = '';
                    if (eq.substitutes_count > 0) {
                        substitutesBadge = `<span class="badge rounded-pill mt-1 show-substitutes" data-id="${eq.id}" style="background-color: #e0f2fe; color: #0284c7; font-size: 11px; font-weight: 500; cursor: pointer; padding: 2px 8px;"><i class="bi bi-chevron-down me-1" style="font-size: 9px;"></i> ${eq.substitutes_count} Article${eq.substitutes_count > 1 ? 's' : ''} lié${eq.substitutes_count > 1 ? 's' : ''}</span>`;
                    }

                    card.innerHTML = `
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <span class="fw-bold text-dark text-uppercase" style="font-size: 12.5px;">${eq.reference}</span>
                                    ${substitutesBadge}
                                </div>
                                <small class="text-muted text-break d-block mt-1" style="font-size: 11.5px; line-height: 1.25;">${eq.name}</small>
                                <div class="substitutes-list d-none border-top pt-2 mt-2" id="subs-${eq.id}"></div>
                            </div>
                            <div class="text-end text-nowrap">
                                <span class="fw-bold text-dark" style="font-size: 12.5px;">${eq.price.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} €</span>
                            </div>
                        </div>
                    `;

                    card.addEventListener('click', (e) => {
                        if (e.target.closest('.show-substitutes')) {
                            e.stopPropagation();
                            const subEl = document.getElementById(`subs-${eq.id}`);
                            subEl.classList.toggle('d-none');
                            if (subEl.innerHTML === '' && eq.substitutes) {
                                eq.substitutes.forEach(sub => {
                                    if (existingIds.has(String(sub.id)) || existingRefs.has(String(sub.reference || '').trim().toUpperCase())) {
                                        return;
                                    }
                                    const subRow = document.createElement('div');
                                    subRow.className = 'd-flex justify-content-between align-items-center py-1 border-bottom border-light';
                                    subRow.innerHTML = `
                                        <div class="small">
                                            <span class="fw-bold text-dark" style="font-size: 12px;">${sub.reference}</span>
                                            <small class="text-muted d-block" style="font-size: 11px;">${sub.name}</small>
                                        </div>
                                        <button type="button" class="btn btn-xs btn-primary py-0 px-2 rounded font-semibold text-nowrap" style="font-size: 11px;">${sub.price.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} €</button>
                                    `;
                                    subRow.querySelector('button').addEventListener('click', (subEv) => {
                                        subEv.stopPropagation();
                                        addProductToQuote(sub, subRow);
                                    });
                                    subEl.appendChild(subRow);
                                });
                            }
                            return;
                        }
                        addProductToQuote(eq, card);
                    });

                    container.appendChild(card);
                });
            });
    }

    function addProductToQuote(product, sourceElement = null) {
        const index = linesContainer.querySelectorAll('tr[data-item]').length;
        const templateHtml = document.getElementById('line-template').innerHTML.replace(/__INDEX__/g, index);
        linesContainer.insertAdjacentHTML('beforeend', templateHtml);
        const row = linesContainer.querySelector('tr[data-item]:last-child');

        row.querySelector('.line-code-article').value = product.reference;
        row.querySelector('.line-libelle').value = product.name;
        row.querySelector('.line-equipment-id').value = product.id;
        row.querySelector('.line-pu-ht').value = product.price.toFixed(2);
        row.querySelector('.line-pu-ht-net').value = product.price.toFixed(2);
        row.querySelector('.line-montant-ht').value = product.price.toFixed(2);
        row.querySelector('.line-qte').value = 1;
        row.querySelector('.line-remise-ligne').value = '0.00';
        row.querySelector('.line-remise-2').value = '0.00';

        const formattedPrice = product.price.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';
        const pxPubDisplay = row.querySelector('.line-pxpub-display');
        if (pxPubDisplay) pxPubDisplay.textContent = formattedPrice;
        const puHtNetDisplay = row.querySelector('.line-pu-ht-net-display');
        if (puHtNetDisplay) puHtNetDisplay.textContent = formattedPrice;
        const montantHtDisplay = row.querySelector('.line-montant-ht-display');
        if (montantHtDisplay) montantHtDisplay.textContent = formattedPrice;

        recalculate();
        row.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

        // Make the article disappear from the left list
        if (sourceElement) {
            sourceElement.style.transition = 'opacity 0.2s ease, transform 0.2s ease, height 0.2s ease';
            sourceElement.style.opacity = '0';
            sourceElement.style.transform = 'translateX(20px)';
            setTimeout(() => {
                sourceElement.remove();
                const countBadge = document.getElementById('products-count');
                if (countBadge) {
                    const currentCount = parseInt(countBadge.textContent) || 0;
                    countBadge.textContent = Math.max(0, currentCount - 1);
                }
            }, 200);
        } else {
            const foundCard = document.querySelector(`.clean-catalog-item[data-eq-id="${product.id}"]`) ||
                              Array.from(document.querySelectorAll('.clean-catalog-item')).find(el => el.getAttribute('data-reference') === product.reference);
            if (foundCard) {
                foundCard.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
                foundCard.style.opacity = '0';
                setTimeout(() => {
                    foundCard.remove();
                    const countBadge = document.getElementById('products-count');
                    if (countBadge) {
                        const currentCount = parseInt(countBadge.textContent) || 0;
                        countBadge.textContent = Math.max(0, currentCount - 1);
                    }
                }, 200);
            }
        }
    }

    // --- STEP 4: Live PDF Mockup Renderer ---
    function updatePdfPreview() {
        // Client info
        const clientName = clientSelect.options[clientSelect.selectedIndex]?.getAttribute('data-name') || document.getElementById('header-client-name').textContent;
        document.getElementById('vis-client-name').textContent = clientName;
        document.getElementById('vis-contact-name').textContent = (contactPrenomInput.value + ' ' + contactNomInput.value).trim() || 'M Anthony ABOU NADER';
        document.getElementById('vis-client-address-line').textContent = (zipInput.value + ' ' + cityInput.value).trim() || '38150 SALAISE SUR SANNE';
        document.getElementById('vis-client-no').textContent = codeInput.value || 'C025060000';

        const quoteRef = document.getElementById('ref_interne')?.value || ('B' + (document.getElementById('client_id')?.value || '19254') + '-1');
        document.getElementById('vis-quote-ref').textContent = 'N° ' + quoteRef;

        const datePiece = document.getElementById('date_piece')?.value;
        document.getElementById('vis-quote-date').textContent = datePiece ? new Date(datePiece).toLocaleDateString('fr-FR') : new Date().toLocaleDateString('fr-FR');

        // Table lines
        const tbody = document.getElementById('vis-table-body');
        tbody.innerHTML = '';
        const rows = document.querySelectorAll('#lines-container tr[data-item]');

        if (rows.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-muted small">Aucun article dans ce devis.</td></tr>';
            return;
        }

        let totalBrut = 0;
        let totalNet = 0;

        rows.forEach(r => {
            const type = r.getAttribute('data-type') || 'Article';
            const tr = document.createElement('tr');

            if (type === 'Chapitre') {
                const title = r.querySelector('.line-libelle')?.value || 'Chapitre';
                const subtotal = r.querySelector('.chapter-subtotal-display')?.textContent || '0,00 €';
                tr.style.cssText = 'background-color: #ffe0b2; color: #d84315; font-weight: bold; font-size: 0.85rem;';
                tr.innerHTML = `<td colspan="4" class="py-2 px-3">📁 ${title}</td><td class="text-end py-2 px-3">${subtotal}</td>`;
            } else if (type === 'Section') {
                const title = r.querySelector('.line-libelle')?.value || 'Section';
                tr.style.cssText = 'background-color: #f5f5f5; color: #455a64; font-weight: bold; font-size: 0.8rem;';
                tr.innerHTML = `<td colspan="5" class="py-1 px-3">🗐 ${title}</td>`;
            } else if (type === 'Option') {
                const title = r.querySelector('.line-libelle')?.value || 'Option';
                const subtotal = r.querySelector('.chapter-subtotal-display')?.textContent || '0,00 €';
                tr.style.cssText = 'background-color: #f1f8e9; color: #33691e; font-weight: bold; font-size: 0.8rem;';
                tr.innerHTML = `<td colspan="4" class="py-1 px-3">⚙️ [Option] ${title}</td><td class="text-end py-1 px-3">${subtotal}</td>`;
            } else if (type === 'Commentaire') {
                const comment = r.querySelector('.line-libelle')?.value || '';
                tr.style.cssText = 'background-color: #f9fbe7; font-style: italic; color: #555555;';
                tr.innerHTML = `<td colspan="5" class="py-1 px-3">💬 ${comment}</td>`;
            } else {
                const code = r.querySelector('.line-code-article')?.value || '';
                const desc = r.querySelector('.line-libelle')?.value || '';
                const qte = parseFloat(r.querySelector('.line-qte')?.value) || 0;
                const puNet = parseFloat(r.querySelector('.line-pu-ht-net')?.value) || 0;
                const montant = parseFloat(r.querySelector('.line-montant-ht')?.value) || 0;
                const isOption = r.querySelector('.line-is-option')?.value == '1';

                tr.innerHTML = `
                    <td class="py-2 px-3 fw-bold">${code} ${isOption ? '<span class="badge bg-success small">Option</span>' : ''}</td>
                    <td class="py-2 px-3 text-muted">${desc}</td>
                    <td class="py-2 px-3 text-center">${qte}</td>
                    <td class="py-2 px-3 text-end">${puNet.toLocaleString('fr-FR', { minimumFractionDigits: 2 })} €</td>
                    <td class="py-2 px-3 text-end fw-bold">${montant.toLocaleString('fr-FR', { minimumFractionDigits: 2 })} €</td>
                `;

                if (!isOption) {
                    totalNet += montant;
                }
            }
            tbody.appendChild(tr);
        });

        const discountVal = parseFloat(remisePiedInput.value) || 0;
        totalBrut = totalNet + discountVal;
        const totalFinalNet = Math.max(0, totalNet - discountVal);
        const tva = totalFinalNet * 0.20;
        const totalTtc = totalFinalNet * 1.20;

        document.getElementById('vis-total-brut').textContent = totalBrut.toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' €';
        document.getElementById('vis-total-net-ht').textContent = totalFinalNet.toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' €';
        document.getElementById('vis-tva').textContent = tva.toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' €';
        document.getElementById('vis-total-ttc').textContent = totalTtc.toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' €';

        const remiseRow = document.getElementById('vis-remise-row');
        if (discountVal > 0) {
            remiseRow.style.display = 'flex';
            document.getElementById('vis-remise-val').textContent = '- ' + discountVal.toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' €';
        } else {
            remiseRow.style.display = 'none';
        }

        // Reserves
        const hiddenReserves = document.getElementById('reserves_hidden_input');
        const reservesBox = document.getElementById('vis-reserves-box');
        const reservesUl = document.getElementById('vis-reserves-ul');
        const resLines = hiddenReserves.value ? hiddenReserves.value.split('\n').filter(Boolean) : [];

        if (resLines.length > 0) {
            reservesBox.style.display = 'block';
            reservesUl.innerHTML = '';
            resLines.forEach(r => {
                const li = document.createElement('li');
                li.textContent = r;
                reservesUl.appendChild(li);
            });
        } else {
            reservesBox.style.display = 'none';
        }
    }

    document.getElementById('visualisation-tab').addEventListener('click', updatePdfPreview);
    document.getElementById('btn-generate-pdf-preview').addEventListener('click', updatePdfPreview);

    // Initial setup
    loadCategories();
    loadEquipments();
    recalculate();
});
</script>
