<!-- Recherche Officielle Gouvernement Français SIRET / SIREN pour Clients -->
<div class="card mb-3 border-secondary border-opacity-25 bg-light-subtle shadow-none">
    <div class="card-body p-3">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-secondary text-white p-2 rounded-circle">
                    <i class="bi bi-bank2 fs-6"></i>
                </span>
                <div>
                    <h6 class="mb-0 fw-bold text-secondary">Recherche Entreprise (API Entreprise v4 / entreprise.api.gouv.fr)</h6>
                    <small class="text-muted">Interroge l'API officielle <code>entreprise.api.gouv.fr/v4/insee/sirene/etablissements/</code> pour préremplir automatiquement la fiche client.</small>
                </div>
            </div>
            <span class="badge bg-white text-secondary border border-secondary-subtle shadow-xs">
                <i class="bi bi-patch-check-fill text-success me-1"></i>API Entreprise v4 (INSEE Sirene)
            </span>
        </div>

        <div class="input-group">
            <span class="input-group-text bg-white border-end-0 text-muted">
                <i class="bi bi-upc-scan"></i>
            </span>
            <input type="text" id="gov_client_siret_input" class="form-control border-start-0" 
                   placeholder="Saisissez un numéro SIRET (14 chiffres) ou SIREN (9 chiffres) ou nom d'entreprise..."
                   value="{{ old('code_siret', isset($client) ? $client->code_siret : '') }}">
            <button type="button" class="btn btn-secondary fw-semibold d-flex align-items-center gap-2 px-3" id="btn-search-client-siret-gov">
                <span class="spinner-border spinner-border-sm d-none" id="gov-client-siret-spinner" role="status" aria-hidden="true"></span>
                <i class="bi bi-search" id="gov-client-siret-icon"></i>
                <span id="gov-client-siret-btn-label">Rechercher</span>
            </button>
        </div>
        <div class="form-text text-muted mt-1">
            <i class="bi bi-info-circle me-1"></i>Recherche par numéro SIRET ou SIREN. Préremplit : Raison Sociale, SIRET, Code NIF (TVA), Adresse, Code Postal, Ville, Département, Pays et statut d'activité.
        </div>

        <!-- Zone de notification / alertes -->
        <div id="gov-client-siret-alert" class="mt-2 d-none"></div>

        <!-- Zone des établissements multiples -->
        <div id="gov-client-establishments-container" class="mt-3 d-none">
            <div class="p-3 bg-white rounded border">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="fw-bold text-dark small">
                        <i class="bi bi-buildings me-1 text-secondary"></i>Établissements disponibles pour cette entreprise :
                    </span>
                    <button type="button" class="btn-close btn-sm" id="btn-close-client-establishments" aria-label="Close"></button>
                </div>
                <div class="list-group list-group-flush small" id="gov-client-establishments-list" style="max-height: 220px; overflow-y: auto;"></div>
            </div>
        </div>
    </div>
</div>

<style>
.is-autofilled {
    background-color: #e8f5e9 !important;
    border-color: #81c784 !important;
    box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.25) !important;
    transition: background-color 0.8s ease, border-color 0.8s ease;
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    const searchClientUrl = "{{ route('clients.search-siret') }}";

    function setSearchingState(isSearching) {
        if (isSearching) {
            $('#btn-search-client-siret-gov').prop('disabled', true);
            $('#gov-client-siret-spinner').removeClass('d-none');
            $('#gov-client-siret-icon').addClass('d-none');
            $('#gov-client-siret-btn-label').text('Recherche...');
        } else {
            $('#btn-search-client-siret-gov').prop('disabled', false);
            $('#gov-client-siret-spinner').addClass('d-none');
            $('#gov-client-siret-icon').removeClass('d-none');
            $('#gov-client-siret-btn-label').text('Rechercher');
        }
    }

    function showAlert(type, message, details = '') {
        const icon = type === 'success' ? 'bi-check-circle-fill text-success' : (type === 'warning' ? 'bi-exclamation-triangle-fill text-warning' : 'bi-x-circle-fill text-danger');
        let html = `
            <div class="alert alert-${type} alert-dismissible fade show d-flex align-items-start gap-2 mb-0 py-2 px-3 small" role="alert">
                <i class="bi ${icon} fs-5 mt-n1 flex-shrink-0"></i>
                <div class="flex-grow-1">
                    <div>${message}</div>
                    ${details ? `<div class="mt-1 text-muted small">${details}</div>` : ''}
                </div>
                <button type="button" class="btn-close btn-sm p-2" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        `;
        $('#gov-client-siret-alert').html(html).removeClass('d-none');
    }

    function highlightInput(selector) {
        const $el = $(selector);
        if ($el.length) {
            $el.addClass('is-autofilled');
            setTimeout(() => {
                $el.removeClass('is-autofilled');
            }, 2500);
        }
    }

    function applyClientCompanyData(company) {
        if (!company) return;

        // Raison Sociale
        if (company.name) {
            $('input[name="raison_sociale"]').val(company.name);
            highlightInput('input[name="raison_sociale"]');
        }

        // SIRET
        const siretVal = company.siret || company.siren_siret;
        if (siretVal) {
            $('input[name="code_siret"]').val(siretVal);
            $('#gov_client_siret_input').val(siretVal);
            highlightInput('input[name="code_siret"]');
        }

        // Code NIF / Numéro TVA
        if (company.code_nif) {
            $('input[name="code_nif"]').val(company.code_nif);
            highlightInput('input[name="code_nif"]');
        }

        // Sigle / Enseigne / Libellé abrégé
        if (company.sigle) {
            if (!$('input[name="enseigne"]').val()) {
                $('input[name="enseigne"]').val(company.sigle);
                highlightInput('input[name="enseigne"]');
            }
            if (!$('input[name="libelle_abrege"]').val()) {
                $('input[name="libelle_abrege"]').val(company.sigle);
                highlightInput('input[name="libelle_abrege"]');
            }
        }

        // Adresse 1
        if (company.address) {
            $('input[name="adresse_1"]').val(company.address);
            highlightInput('input[name="adresse_1"]');
        }

        // Adresse 2 / Complément
        if (company.adresse_2) {
            $('input[name="adresse_2"]').val(company.adresse_2);
            highlightInput('input[name="adresse_2"]');
        }

        // Code Postal & Département
        if (company.code_postal) {
            const cp = String(company.code_postal).trim();
            $('input[name="code_postal"]').val(cp);
            highlightInput('input[name="code_postal"]');

            if ($('input[name="department"]').length) {
                const dept = cp.startsWith('97') ? cp.substring(0, 3) : cp.substring(0, 2);
                $('input[name="department"]').val(dept);
                highlightInput('input[name="department"]');
            }
        }

        // Ville
        if (company.ville) {
            $('input[name="ville"]').val(company.ville);
            highlightInput('input[name="ville"]');
        }

        // Pays
        if ($('input[name="pays"]').length) {
            $('input[name="pays"]').val(company.country || 'France');
            highlightInput('input[name="pays"]');
        }

        // Statut fermé / actif
        if ($('#closed').length && typeof company.closed !== 'undefined') {
            $('#closed').prop('checked', Boolean(company.closed));
            highlightInput('#closed');
        }

        // Régime TVA par défaut France si vide
        if ($('select[name="regime_tva_id"]').length && !$('select[name="regime_tva_id"]').val()) {
            $('select[name="regime_tva_id"] option').each(function() {
                const text = $(this).text();
                if (text.includes('France') || text.includes('FRA')) {
                    $('select[name="regime_tva_id"]').val($(this).val()).trigger('change');
                    highlightInput('select[name="regime_tva_id"]');
                    return false;
                }
            });
        }
    }

    function renderClientEstablishments(establishments) {
        if (!establishments || establishments.length <= 1) {
            $('#gov-client-establishments-container').addClass('d-none');
            return;
        }

        let listHtml = '';
        establishments.forEach(function(etab, index) {
            const badge = etab.is_siege 
                ? '<span class="badge bg-secondary text-white me-1">Siège social</span>' 
                : '<span class="badge bg-light text-dark border me-1">Établissement</span>';
            const closedBadge = etab.closed ? '<span class="badge bg-danger ms-1">Fermé</span>' : '';
            const fullAddress = [etab.address, etab.code_postal, etab.ville].filter(Boolean).join(', ');

            listHtml += `
                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-2 etab-item"
                     data-index="${index}">
                    <div>
                        <div class="d-flex align-items-center gap-1">
                            ${badge}
                            <span class="fw-semibold text-dark">${etab.siret || ''}</span>
                            ${closedBadge}
                        </div>
                        <div class="text-muted small mt-1">
                            <i class="bi bi-geo-alt me-1"></i>${fullAddress || 'Adresse non renseignée'}
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-secondary btn-sm btn-select-client-etab py-1 px-2" data-index="${index}">
                        Choisir cet établissement
                    </button>
                </div>
            `;
        });

        $('#gov-client-establishments-list').html(listHtml);
        $('#gov-client-establishments-container').removeClass('d-none');

        window._govClientEstablishments = establishments;
    }

    $(document).on('click', '.btn-select-client-etab', function() {
        const index = $(this).data('index');
        const etab = (window._govClientEstablishments || [])[index];
        if (etab) {
            if (etab.siret) {
                $('input[name="code_siret"]').val(etab.siret);
                $('#gov_client_siret_input').val(etab.siret);
                highlightInput('input[name="code_siret"]');
            }
            if (etab.address) {
                $('input[name="adresse_1"]').val(etab.address);
                highlightInput('input[name="adresse_1"]');
            }
            if (etab.adresse_2) {
                $('input[name="adresse_2"]').val(etab.adresse_2);
                highlightInput('input[name="adresse_2"]');
            }
            if (etab.code_postal) {
                const cp = String(etab.code_postal).trim();
                $('input[name="code_postal"]').val(cp);
                highlightInput('input[name="code_postal"]');

                if ($('input[name="department"]').length) {
                    const dept = cp.startsWith('97') ? cp.substring(0, 3) : cp.substring(0, 2);
                    $('input[name="department"]').val(dept);
                    highlightInput('input[name="department"]');
                }
            }
            if (etab.ville) {
                $('input[name="ville"]').val(etab.ville);
                highlightInput('input[name="ville"]');
            }
            if (typeof etab.closed !== 'undefined') {
                $('#closed').prop('checked', Boolean(etab.closed));
                highlightInput('#closed');
            }

            showAlert('success', `Établissement sélectionné : ${etab.siret} (${etab.ville || ''})`);
        }
    });

    $('#btn-close-client-establishments').on('click', function() {
        $('#gov-client-establishments-container').addClass('d-none');
    });

    function executeClientSearch(query) {
        query = (query || '').trim();
        if (!query) {
            showAlert('warning', 'Veuillez saisir un numéro SIRET (14 chiffres) ou SIREN (9 chiffres).');
            return;
        }

        setSearchingState(true);
        $('#gov-client-siret-alert').addClass('d-none');
        $('#gov-client-establishments-container').addClass('d-none');

        $.ajax({
            url: searchClientUrl,
            type: 'GET',
            data: { siret: query },
            dataType: 'json',
            success: function(response) {
                setSearchingState(false);
                if (response.success && response.company) {
                    applyClientCompanyData(response.company);

                    const statusBadge = response.company.closed 
                        ? '<span class="badge bg-danger ms-1">Fermé</span>' 
                        : '<span class="badge bg-success ms-1">Actif</span>';
                    const siegeBadge = response.company.is_siege 
                        ? '<span class="badge bg-secondary ms-1">Siège</span>' 
                        : '';

                    const details = `
                        SIRET : <strong>${response.company.siret || response.company.siren}</strong> |
                        TVA : <strong>${response.company.code_nif || 'Non renseigné'}</strong>
                        ${statusBadge} ${siegeBadge}
                    `;

                    showAlert('success', `Entreprise trouvée : <strong>${response.company.name}</strong>. Les champs du formulaire client ont été préremplis.`, details);

                    if (response.establishments && response.establishments.length > 1) {
                        renderClientEstablishments(response.establishments);
                    }
                } else {
                    showAlert('warning', response.message || 'Aucune information trouvée.');
                }
            },
            error: function(xhr) {
                setSearchingState(false);
                let msg = 'Une erreur est survenue lors de la communication avec les services du gouvernement.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                } else if (xhr.status === 503) {
                    msg = 'Le service public officiel est temporairement indisponible ou en maintenance.';
                }
                showAlert('danger', msg);
            }
        });
    }

    // Bouton de recherche dans la boîte dédiée
    $('#btn-search-client-siret-gov').on('click', function() {
        executeClientSearch($('#gov_client_siret_input').val());
    });

    // Entrée clavier sur l'input dédié
    $('#gov_client_siret_input').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            executeClientSearch($(this).val());
        }
    });

    // Synchronisation depuis le champ SIRET standard
    $(document).on('click', '.btn-trigger-client-siret-search', function() {
        const val = $('input[name="code_siret"]').val();
        if (val) {
            $('#gov_client_siret_input').val(val);
        }
        executeClientSearch(val || $('#gov_client_siret_input').val());
    });
});
</script>
@endpush
