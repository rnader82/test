@extends('layouts.app')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ $selectedClient ? route('clients.show', ['client' => $selectedClient->id, 'tab' => 'contacts']) : route('contacts.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.add_contact') }}</h1>
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('contacts.store') }}" method="POST">
            @csrf
            @if($selectedClient)
                <input type="hidden" name="redirect_to" value="client">
            @endif

            <div class="row g-4">
                <!-- Main Form Card -->
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow-sm mb-4 border-0">
                        <div class="card-body p-4">
                            <!-- Odoo Type Radio Selector -->
                            <div class="odoo-type-selector d-flex align-items-center flex-wrap gap-4 pb-3 mb-4 border-bottom">
                                <label class="form-check form-check-inline cursor-pointer mb-0">
                                    <input class="form-check-input odoo-type-radio" type="radio" name="type" id="type_contact" value="contact" {{ old('type', 'contact') === 'contact' ? 'checked' : '' }}>
                                    <span class="form-check-label fw-semibold ms-1">{{ __('locale.type_contact') }}</span>
                                </label>
                                <label class="form-check form-check-inline cursor-pointer mb-0">
                                    <input class="form-check-input odoo-type-radio" type="radio" name="type" id="type_invoice" value="invoice" {{ old('type') === 'invoice' ? 'checked' : '' }}>
                                    <span class="form-check-label fw-semibold ms-1">{{ __('locale.type_invoice') }}</span>
                                </label>
                                <label class="form-check form-check-inline cursor-pointer mb-0">
                                    <input class="form-check-input odoo-type-radio" type="radio" name="type" id="type_delivery" value="delivery" {{ old('type') === 'delivery' ? 'checked' : '' }}>
                                    <span class="form-check-label fw-semibold ms-1">{{ __('locale.type_delivery') }}</span>
                                </label>
                                <label class="form-check form-check-inline cursor-pointer mb-0">
                                    <input class="form-check-input odoo-type-radio" type="radio" name="type" id="type_other" value="other" {{ old('type') === 'other' ? 'checked' : '' }}>
                                    <span class="form-check-label fw-semibold ms-1">{{ __('locale.type_other') }}</span>
                                </label>
                            </div>

                            <!-- Odoo Main Layout Row: Icon Box on Left + Fields on Right -->
                            <div class="row g-4">
                                <div class="col-sm-3 col-md-2 text-center text-sm-start">
                                    <div class="odoo-icon-container p-3 rounded-3 bg-light d-flex align-items-center justify-content-center border shadow-xs" style="min-height: 100px;">
                                        <!-- Isometric Delivery Truck Icon -->
                                        <div id="icon_delivery" class="odoo-type-icon {{ old('type') === 'delivery' ? '' : 'd-none' }}">
                                            <svg width="72" height="72" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M4 22L32 6L60 22L32 38L4 22Z" fill="#E8EEF5"/>
                                                <path d="M4 22L32 38V58L4 42V22Z" fill="#C5D3E8"/>
                                                <path d="M60 22L32 38V58L60 42V22Z" fill="#9FB4D4"/>
                                                <path d="M38 32L54 23L58 26L42 35L38 32Z" fill="#5A96D8"/>
                                                <path d="M42 35L58 26V36L42 45V35Z" fill="#3D75B5"/>
                                                <circle cx="20" cy="47" r="5" fill="#333333"/>
                                                <circle cx="20" cy="47" r="2.5" fill="#B0B8C1"/>
                                                <circle cx="48" cy="47" r="5" fill="#333333"/>
                                                <circle cx="48" cy="47" r="2.5" fill="#B0B8C1"/>
                                            </svg>
                                        </div>
                                        <!-- Invoice Icon -->
                                        <div id="icon_invoice" class="odoo-type-icon {{ old('type') === 'invoice' ? '' : 'd-none' }}">
                                            <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="12" y="8" width="40" height="48" rx="4" fill="#E8EEF5" stroke="#3D75B5" stroke-width="2"/>
                                                <line x1="20" y1="20" x2="44" y2="20" stroke="#3D75B5" stroke-width="3" stroke-linecap="round"/>
                                                <line x1="20" y1="28" x2="44" y2="28" stroke="#9FB4D4" stroke-width="2" stroke-linecap="round"/>
                                                <line x1="20" y1="36" x2="36" y2="36" stroke="#9FB4D4" stroke-width="2" stroke-linecap="round"/>
                                                <circle cx="42" cy="44" r="6" fill="#198754"/>
                                                <path d="M39.5 44L41.5 46L45 42" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                                            </svg>
                                        </div>
                                        <!-- Contact Icon -->
                                        <div id="icon_contact" class="odoo-type-icon {{ old('type', 'contact') === 'contact' ? '' : 'd-none' }}">
                                            <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="32" cy="22" r="12" fill="#5A96D8"/>
                                                <path d="M12 54C12 43 21 39 32 39C43 39 52 43 52 54" fill="#C5D3E8"/>
                                            </svg>
                                        </div>
                                        <!-- Other Icon -->
                                        <div id="icon_other" class="odoo-type-icon {{ old('type') === 'other' ? '' : 'd-none' }}">
                                            <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M32 8C22 8 14 16 14 26C14 39 32 56 32 56C32 56 50 39 50 26C50 16 42 8 32 8Z" fill="#E8EEF5" stroke="#E67E22" stroke-width="2"/>
                                                <circle cx="32" cy="26" r="7" fill="#E67E22"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-9 col-md-10">
                                    <!-- Names row -->
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-3">
                                            <label for="civility" class="form-label small text-muted mb-1">{{ __('locale.civility') }}</label>
                                            <select name="civility" id="civility" class="form-select form-select-sm">
                                                <option value="" {{ old('civility') == '' ? 'selected' : '' }}>--</option>
                                                <option value="M." {{ old('civility') == 'M.' ? 'selected' : '' }}>M.</option>
                                                <option value="Mme" {{ old('civility') == 'Mme' ? 'selected' : '' }}>Mme</option>
                                                <option value="Mr" {{ old('civility') == 'Mr' ? 'selected' : '' }}>Mr</option>
                                                <option value="Dr" {{ old('civility') == 'Dr' ? 'selected' : '' }}>Dr</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="prenom" class="form-label small text-muted mb-1">{{ __('locale.prenom') }}</label>
                                            <input type="text" name="prenom" id="prenom" class="form-control" maxlength="255" placeholder="Brandon" value="{{ old('prenom') }}">
                                        </div>
                                        <div class="col-md-5">
                                            <label for="nom" class="form-label small text-muted mb-1">{{ __('locale.nom') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="nom" id="nom" class="form-control fw-semibold" maxlength="255" placeholder="Freeman" value="{{ old('nom') }}" required>
                                        </div>
                                    </div>

                                    <!-- Communication row: Email, Phone, Mobile -->
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-4">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text bg-light text-primary border-end-0"><i class="bi bi-envelope"></i></span>
                                                <input type="email" name="email" id="email" class="form-control border-start-0" placeholder="{{ __('locale.email') }}" value="{{ old('email') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text bg-light text-primary border-end-0"><i class="bi bi-telephone"></i></span>
                                                <input type="text" name="phone1" id="phone1" class="form-control border-start-0" placeholder="{{ __('locale.lbl_phone') }}" value="{{ old('phone1') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text bg-light text-primary border-end-0"><i class="bi bi-phone"></i></span>
                                                <input type="text" name="mobile" id="mobile" class="form-control border-start-0" placeholder="{{ __('locale.lbl_mobile') }}" value="{{ old('mobile') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Address Section (Odoo Style) -->
                                    <div class="odoo-address-box p-3 rounded-3 bg-light bg-opacity-50 border mb-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="fw-bold text-secondary small text-uppercase" id="address_section_title">
                                                {{ __('locale.address_contact') }}
                                            </span>
                                            @if($selectedClient)
                                                <button type="button" class="btn btn-xs btn-link text-decoration-none p-0" id="btn_copy_client_address">
                                                    <i class="bi bi-arrow-down-left-square me-1"></i>{{ __('locale.copy_client_address') ?? 'Copier l\'adresse du client' }}
                                                </button>
                                            @endif
                                        </div>

                                        <div class="row g-2">
                                            <div class="col-12">
                                                <input type="text" name="adresse_1" id="adresse_1" class="form-control form-control-sm mb-1" placeholder="{{ __('locale.street_1') }} (ex. 80 avenue Archimède)" value="{{ old('adresse_1') }}">
                                                <input type="text" name="adresse_2" id="adresse_2" class="form-control form-control-sm" placeholder="{{ __('locale.street_2') }}" value="{{ old('adresse_2') }}">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" name="code_postal" id="code_postal" class="form-control form-control-sm" placeholder="{{ __('locale.code_postal') }}" value="{{ old('code_postal') }}">
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" name="ville" id="ville" class="form-control form-control-sm" placeholder="{{ __('locale.ville') }}" value="{{ old('ville') }}">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" name="state" id="state" class="form-control form-control-sm" placeholder="{{ __('locale.state_province') }}" value="{{ old('state') }}">
                                            </div>
                                            <div class="col-12">
                                                <input type="text" name="pays" id="pays" class="form-control form-control-sm" placeholder="{{ __('locale.country') }}" value="{{ old('pays', 'France') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- GLN Field -->
                                    <div class="row g-2 mb-3 align-items-center">
                                        <div class="col-md-2">
                                            <label for="gln" class="form-label small text-muted mb-0 d-flex align-items-center gap-1">
                                                <span>{{ __('locale.gln') }}</span>
                                                <i class="bi bi-question-circle text-muted" data-bs-toggle="tooltip" title="{{ __('locale.gln_hint') }}"></i>
                                            </label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" name="gln" id="gln" class="form-control form-control-sm" placeholder="GLN (ex. 3012345678901)" value="{{ old('gln') }}">
                                        </div>
                                    </div>

                                    <!-- Notes Internes -->
                                    <div class="row g-2 mb-2 align-items-start">
                                        <div class="col-md-2">
                                            <label for="notes" class="form-label small text-muted mb-0 d-flex align-items-center gap-1">
                                                <i class="bi bi-grip-vertical text-muted"></i>
                                                <span>{{ __('locale.notes') }}</span>
                                            </label>
                                        </div>
                                        <div class="col-md-10">
                                            <textarea name="notes" id="notes" rows="2" class="form-control form-control-sm" placeholder="{{ __('locale.internal_notes') }}">{{ old('notes') }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Function, Service, Language & Odoo ID -->
                                    <div class="row g-2 pt-2 border-top">
                                        <div class="col-md-3">
                                            <label for="function_code" class="form-label small text-muted mb-1">{{ __('locale.function_code') }}</label>
                                            <input type="text" name="function_code" id="function_code" class="form-control form-control-sm" maxlength="100" placeholder="{{ __('locale.job_position') ?? 'Poste occupé' }}" value="{{ old('function_code') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="service_code" class="form-label small text-muted mb-1">{{ __('locale.service_code') }}</label>
                                            <input type="text" name="service_code" id="service_code" class="form-control form-control-sm" maxlength="255" placeholder="{{ __('locale.department') ?? 'Service / Département' }}" value="{{ old('service_code') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="langue" class="form-label small text-muted mb-1">{{ __('locale.lbl_langue') ?? 'Langue' }}</label>
                                            <select name="langue" id="langue" class="form-select form-select-sm">
                                                @php
                                                    $contactLang = old('langue', 'Français');
                                                    $allLangs = isset($languages) ? $languages : \App\Models\Language::where('is_active', true)->orderBy('name')->get();
                                                @endphp
                                                @foreach($allLangs as $langOption)
                                                    <option value="{{ $langOption->name }}" @selected($contactLang === $langOption->name)>{{ $langOption->name }}</option>
                                                @endforeach
                                                @if(!empty($contactLang) && !$allLangs->contains('name', $contactLang))
                                                    <option value="{{ $contactLang }}" selected>{{ $contactLang }}</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="odoo_id" class="form-label small text-muted mb-1">{{ __('locale.lbl_odoo_id') ?? 'Odoo ID' }}</label>
                                            <input type="number" name="odoo_id" id="odoo_id" class="form-control form-control-sm font-monospace @error('odoo_id') is-invalid @enderror" placeholder="Odoo ID" value="{{ old('odoo_id') }}">
                                            @error('odoo_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Settings Card -->
                <div class="col-xl-4 col-lg-5">
                    <!-- Client Assignment Card -->
                    <div class="card shadow-sm mb-4 border-0">
                        <div class="card-header py-3 bg-light border-0">
                            <h5 class="card-title mb-0 fs-6 fw-bold text-secondary">{{ __('locale.lbl_client') }}</h5>
                        </div>
                        <div class="card-body">
                            @if($selectedClient)
                                <div class="p-3 border rounded-3 bg-light mb-2">
                                    <div class="fw-bold text-primary fs-6">{{ $selectedClient->raison_sociale }}</div>
                                    <div class="small text-muted mt-1">
                                        {{ $selectedClient->adresse_1 }} {{ $selectedClient->adresse_2 }}<br>
                                        {{ $selectedClient->code_postal }} {{ $selectedClient->ville }}
                                    </div>
                                    <input type="hidden" name="client_id" id="client_id" value="{{ $selectedClient->id }}">
                                </div>
                            @else
                                <div class="position-relative">
                                    <label for="client_search" class="form-label small text-muted mb-1">{{ __('locale.search_client_placeholder') }}</label>
                                    <div class="d-flex align-items-center">
                                        <input type="text" id="client_search" class="form-control" autocomplete="off" value="{{ old('client_search') }}" placeholder="{{ __('locale.search_client_placeholder') }}">
                                        <i id="client_warning" class="bi bi-exclamation-triangle-fill text-danger ms-2 d-none" data-bs-toggle="tooltip" data-bs-html="true"></i>
                                    </div>
                                    <div id="client_address" class="small text-muted mt-1"></div>
                                    <input type="hidden" name="client_id" id="client_id" value="{{ old('client_id') }}">
                                    <ul id="client_suggestions" class="list-group position-absolute w-100 bg-white shadow-sm autocomplete-suggestions" style="z-index: 1000; display:none;"></ul>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Options Card -->
                    <div class="card shadow-sm mb-4 border-0">
                        <div class="card-header py-3 bg-light border-0">
                            <h5 class="card-title mb-0 fs-6 fw-bold text-secondary">{{ __('locale.system') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="form-check form-switch card-switch p-3 border rounded-3 @if(old('primary')) bg-light @endif">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <label class="form-check-label fw-medium d-block" for="primary">{{ __('locale.primary') }}</label>
                                            <small class="text-muted">{{ __('locale.contact_primary_hint') }}</small>
                                        </div>
                                        <input class="form-check-input" type="checkbox" name="primary" id="primary" @if(old('primary')) checked @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="form-check form-switch card-switch p-3 border rounded-3 @if(old('closed')) bg-light @endif">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <label class="form-check-label fw-medium d-block" for="closed">{{ __('locale.lbl_closed') }}</label>
                                            <small class="text-muted">{{ __('locale.contact_closed_hint') }}</small>
                                        </div>
                                        <input class="form-check-input" type="checkbox" name="closed" id="closed" @if(old('closed')) checked @endif>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="card shadow-sm border-0">
                        <div class="card-body d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-check-lg me-1"></i> {{ __('locale.save') }}
                            </button>
                            <a href="{{ $selectedClient ? route('clients.show', ['client' => $selectedClient->id, 'tab' => 'contacts']) : route('contacts.index') }}" class="btn btn-outline-secondary w-100">
                                {{ __('locale.btn_cancel') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Type Switcher Behavior
        const titles = {
            contact: "{{ __('locale.address_contact') }}",
            invoice: "{{ __('locale.address_invoice') }}",
            delivery: "{{ __('locale.address_delivery') }}",
            other: "{{ __('locale.address_other') }}"
        };

        const updateTypeUI = (type) => {
            document.querySelectorAll('.odoo-type-icon').forEach(el => el.classList.add('d-none'));
            const activeIcon = document.getElementById(`icon_${type}`);
            if (activeIcon) activeIcon.classList.remove('d-none');

            const sectionTitle = document.getElementById('address_section_title');
            if (sectionTitle) sectionTitle.textContent = titles[type] || titles.contact;
        };

        document.querySelectorAll('.odoo-type-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                updateTypeUI(this.value);
            });
        });

        // Initial UI state
        const checkedRadio = document.querySelector('.odoo-type-radio:checked');
        if (checkedRadio) {
            updateTypeUI(checkedRadio.value);
        }

        // Copy Client Address Button
        @if($selectedClient)
        const btnCopyClient = document.getElementById('btn_copy_client_address');
        if (btnCopyClient) {
            btnCopyClient.addEventListener('click', function() {
                document.getElementById('adresse_1').value = "{{ $selectedClient->adresse_1 }}";
                document.getElementById('adresse_2').value = "{{ $selectedClient->adresse_2 }}";
                document.getElementById('code_postal').value = "{{ $selectedClient->code_postal }}";
                document.getElementById('ville').value = "{{ $selectedClient->ville }}";
                document.getElementById('pays').value = "{{ $selectedClient->pays ?: 'France' }}";
            });
        }
        @endif

        // Switch background toggle
        document.querySelectorAll('.card-switch input').forEach(input => {
            input.addEventListener('change', function() {
                const parent = this.closest('.card-switch');
                if (this.checked) {
                    parent.classList.add('bg-light');
                } else {
                    parent.classList.remove('bg-light');
                }
            });
        });

        // Client Autocomplete if not pre-selected
        @if(!$selectedClient)
        function initClientAutocomplete() {
            const searchInput = document.getElementById('client_search');
            const suggestions = document.getElementById('client_suggestions');
            const hiddenInput = document.getElementById('client_id');
            const addressEl = document.getElementById('client_address');
            const warningIcon = document.getElementById('client_warning');

            if (!searchInput) return;

            searchInput.addEventListener('input', function() {
                const query = this.value;
                if (!query) {
                    suggestions.style.display = 'none';
                    suggestions.innerHTML = '';
                    hiddenInput.value = '';
                    if(addressEl) addressEl.textContent = '';
                    if(warningIcon) warningIcon.classList.add('d-none');
                    return;
                }

                fetch(`{{ route('clients.search') }}?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        suggestions.innerHTML = '';
                        if (data.length === 0) {
                            suggestions.style.display = 'none';
                            return;
                        }
                        data.forEach(client => {
                            const li = document.createElement('li');
                            li.classList.add('list-group-item', 'list-group-item-action', 'cursor-pointer', 'py-2');
                            li.dataset.id = client.id;
                            li.dataset.adresse1 = client.adresse_1 || '';
                            li.dataset.adresse2 = client.adresse_2 || '';
                            li.dataset.cp = client.code_postal || '';
                            li.dataset.ville = client.ville || '';
                            li.dataset.pays = client.pays || 'France';
                            li.dataset.concurrent = client.concurrent ? '1' : '';
                            li.dataset.default_payment = client.default_payment ? '1' : '';

                            let nameHtml = `<div class="fw-bold">${client.raison_sociale}</div>`;
                            li.innerHTML = nameHtml + `<small class="text-muted">${[client.adresse_1, client.code_postal, client.ville].filter(Boolean).join(' ')}</small>`;
                            suggestions.appendChild(li);
                        });
                        suggestions.style.display = 'block';
                    });
            });

            suggestions.addEventListener('click', function(e) {
                const li = e.target.closest('li');
                if (!li) return;
                
                hiddenInput.value = li.dataset.id;
                searchInput.value = li.querySelector('.fw-bold').textContent.trim();
                if(addressEl) addressEl.textContent = `${li.dataset.adresse1} ${li.dataset.cp} ${li.dataset.ville}`;

                // If address fields are empty, autofill with client address
                if (!document.getElementById('adresse_1').value) {
                    document.getElementById('adresse_1').value = li.dataset.adresse1;
                    document.getElementById('adresse_2').value = li.dataset.adresse2;
                    document.getElementById('code_postal').value = li.dataset.cp;
                    document.getElementById('ville').value = li.dataset.ville;
                    document.getElementById('pays').value = li.dataset.pays;
                }

                suggestions.style.display = 'none';
                suggestions.innerHTML = '';
            });

            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !suggestions.contains(e.target)) {
                    suggestions.style.display = 'none';
                }
            });
        }
        initClientAutocomplete();
        @endif
    });
</script>
@endsection
