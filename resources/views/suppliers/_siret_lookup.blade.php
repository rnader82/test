<!-- Recherche Officielle Gouvernement Français SIRET / SIREN -->
<div class="card mb-4 border-primary border-opacity-25 bg-primary bg-opacity-10 shadow-none">
    <div class="card-body p-3">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary text-white p-2 rounded-circle">
                    <i class="bi bi-bank2 fs-6"></i>
                </span>
                <div>
                    <h6 class="mb-0 fw-bold text-primary">Recherche Entreprise (API Entreprise v4 / entreprise.api.gouv.fr)</h6>
                    <small class="text-muted">Interroge l'API officielle <code>entreprise.api.gouv.fr/v4/insee/sirene/etablissements/</code> pour préremplir automatiquement les informations du fournisseur.</small>
                </div>
            </div>
            <span class="badge bg-white text-primary border border-primary-subtle shadow-xs">
                <i class="bi bi-patch-check-fill text-success me-1"></i>API Entreprise v4 (INSEE Sirene)
            </span>
        </div>

        <div class="input-group">
            <span class="input-group-text bg-white border-end-0 text-muted">
                <i class="bi bi-upc-scan"></i>
            </span>
            <input type="text" id="gov_siret_input" class="form-control border-start-0" 
                   placeholder="Saisissez un numéro SIRET (14 chiffres) ou SIREN (9 chiffres) ou nom d'entreprise..."
                   value="{{ old('siren_siret', isset($supplier) ? $supplier->siren_siret : '') }}">
            <button type="button" class="btn btn-primary fw-semibold d-flex align-items-center gap-2 px-3" id="btn-search-siret-gov">
                <span class="spinner-border spinner-border-sm d-none" id="gov-siret-spinner" role="status" aria-hidden="true"></span>
                <i class="bi bi-search" id="gov-siret-icon"></i>
                <span id="gov-siret-btn-label">Rechercher</span>
            </button>
        </div>
        <div class="form-text text-muted mt-1">
            <i class="bi bi-info-circle me-1"></i>Recherche par numéro SIRET ou SIREN. Préremplit : Raison Sociale, SIRET, Code NAF, Code NIF (TVA), Adresse, Code Postal, Ville, Pays et statut d'activité.
        </div>

        <!-- Zone de notification / alertes -->
        <div id="gov-siret-alert" class="mt-3 d-none"></div>

        <!-- Zone des établissements multiples -->
        <div id="gov-establishments-container" class="mt-3 d-none">
            <div class="p-3 bg-white rounded border">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="fw-bold text-dark small">
                        <i class="bi bi-buildings me-1 text-primary"></i>Établissements disponibles pour cette entreprise :
                    </span>
                    <button type="button" class="btn-close btn-sm" id="btn-close-establishments" aria-label="Close"></button>
                </div>
                <div class="list-group list-group-flush small" id="gov-establishments-list" style="max-height: 220px; overflow-y: auto;"></div>
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
    const searchUrl = "{{ route('suppliers.search-siret') }}";

    function setSearchingState(isSearching) {
        if (isSearching) {
            $('#btn-search-siret-gov').prop('disabled', true);
            $('#gov-siret-spinner').removeClass('d-none');
            $('#gov-siret-icon').addClass('d-none');
            $('#gov-siret-btn-label').text('Recherche...');
        } else {
            $('#btn-search-siret-gov').prop('disabled', false);
            $('#gov-siret-spinner').addClass('d-none');
            $('#gov-siret-icon').removeClass('d-none');
            $('#gov-siret-btn-label').text('Rechercher');
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
        $('#gov-siret-alert').html(html).removeClass('d-none');
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

    function applyCompanyData(company) {
        if (!company) return;

        // Raison Sociale
        if (company.name) {
            $('#name').val(company.name);
            highlightInput('#name');
        }

        // SIREN / SIRET
        if (company.siret || company.siren_siret) {
            const siretVal = company.siret || company.siren_siret;
            $('#siren_siret').val(siretVal);
            $('#gov_siret_input').val(siretVal);
            highlightInput('#siren_siret');
        }

        // Code NAF
        if (company.code_naf) {
            $('#code_naf').val(company.code_naf);
            highlightInput('#code_naf');
        }

        // Code NIF / Numéro TVA
        if (company.code_nif) {
            $('#code_nif').val(company.code_nif);
            highlightInput('#code_nif');
        }

        // Adresse 1
        if (company.address) {
            $('#address').val(company.address);
            highlightInput('#address');
        }

        // Adresse 2 / Complément
        if (company.adresse_2) {
            $('#adresse_2').val(company.adresse_2);
            highlightInput('#adresse_2');
        }

        // Code Postal
        if (company.code_postal) {
            $('#code_postal').val(company.code_postal);
            highlightInput('#code_postal');
        }

        // Ville
        if (company.ville) {
            $('#ville').val(company.ville);
            highlightInput('#ville');
        }

        // Pays: France
        if ($('#country').length) {
            const countryVal = company.country || 'France';
            $('#country').val(countryVal).trigger('change');
            highlightInput('#country');
        }

        // Statut fermé / actif
        if ($('#closed').length && typeof company.closed !== 'undefined') {
            $('#closed').prop('checked', Boolean(company.closed));
            highlightInput('#closed');
        }

        // Nature juridique
        if (company.nature && (!$('#nature').val() || $('#nature').val().trim() === '')) {
            $('#nature').val(company.nature);
            highlightInput('#nature');
        }

        // Régime TVA par défaut France si vide
        if ($('#regime_tva_id').length && !$('#regime_tva_id').val()) {
            $('#regime_tva_id option').each(function() {
                const text = $(this).text();
                if (text.includes('France') || text.includes('FRA')) {
                    $('#regime_tva_id').val($(this).val()).trigger('change');
                    highlightInput('#regime_tva_id');
                    return false;
                }
            });
        }
    }

    function renderEstablishments(establishments) {
        if (!establishments || establishments.length <= 1) {
            $('#gov-establishments-container').addClass('d-none');
            return;
        }

        let listHtml = '';
        establishments.forEach(function(etab, index) {
            const badge = etab.is_siege 
                ? '<span class="badge bg-primary text-white me-1">Siège social</span>' 
                : '<span class="badge bg-secondary text-white me-1">Établissement</span>';
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
                    <button type="button" class="btn btn-outline-primary btn-sm btn-select-etab py-1 px-2" data-index="${index}">
                        Choisir cet établissement
                    </button>
                </div>
            `;
        });

        $('#gov-establishments-list').html(listHtml);
        $('#gov-establishments-container').removeClass('d-none');

        // Store establishments in memory
        window._govEstablishments = establishments;
    }

    $(document).on('click', '.btn-select-etab', function() {
        const index = $(this).data('index');
        const etab = (window._govEstablishments || [])[index];
        if (etab) {
            if (etab.siret) {
                $('#siren_siret').val(etab.siret);
                $('#gov_siret_input').val(etab.siret);
                highlightInput('#siren_siret');
            }
            if (etab.address) {
                $('#address').val(etab.address);
                highlightInput('#address');
            }
            if (etab.adresse_2) {
                $('#adresse_2').val(etab.adresse_2);
                highlightInput('#adresse_2');
            }
            if (etab.code_postal) {
                $('#code_postal').val(etab.code_postal);
                highlightInput('#code_postal');
            }
            if (etab.ville) {
                $('#ville').val(etab.ville);
                highlightInput('#ville');
            }
            if (etab.code_naf) {
                $('#code_naf').val(etab.code_naf);
                highlightInput('#code_naf');
            }
            if (typeof etab.closed !== 'undefined') {
                $('#closed').prop('checked', Boolean(etab.closed));
                highlightInput('#closed');
            }

            showAlert('success', `Établissement sélectionné : ${etab.siret} (${etab.ville || ''})`);
        }
    });

    $('#btn-close-establishments').on('click', function() {
        $('#gov-establishments-container').addClass('d-none');
    });

    function executeSearch(query) {
        query = (query || '').trim();
        if (!query) {
            showAlert('warning', 'Veuillez saisir un numéro SIRET (14 chiffres) ou SIREN (9 chiffres).');
            return;
        }

        setSearchingState(true);
        $('#gov-siret-alert').addClass('d-none');
        $('#gov-establishments-container').addClass('d-none');

        $.ajax({
            url: searchUrl,
            type: 'GET',
            data: { siret: query },
            dataType: 'json',
            success: function(response) {
                setSearchingState(false);
                if (response.success && response.company) {
                    applyCompanyData(response.company);

                    const statusBadge = response.company.closed 
                        ? '<span class="badge bg-danger ms-1">Fermé</span>' 
                        : '<span class="badge bg-success ms-1">Actif</span>';
                    const siegeBadge = response.company.is_siege 
                        ? '<span class="badge bg-primary ms-1">Siège</span>' 
                        : '';

                    const details = `
                        SIRET : <strong>${response.company.siret || response.company.siren}</strong> |
                        NAF : <strong>${response.company.code_naf || 'Non renseigné'}</strong> |
                        TVA : <strong>${response.company.code_nif || 'Non renseigné'}</strong>
                        ${statusBadge} ${siegeBadge}
                    `;

                    showAlert('success', `Entreprise trouvée : <strong>${response.company.name}</strong>. Les champs du formulaire ont été préremplis.`, details);

                    if (response.establishments && response.establishments.length > 1) {
                        renderEstablishments(response.establishments);
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
                    msg = 'Le service public officiel (recherche-entreprises.api.gouv.fr) est temporairement indisponible ou en maintenance.';
                }
                showAlert('danger', msg);
            }
        });
    }

    // Bouton de recherche dans la boîte dédiée
    $('#btn-search-siret-gov').on('click', function() {
        executeSearch($('#gov_siret_input').val());
    });

    // Entrée clavier sur l'input dédié
    $('#gov_siret_input').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            executeSearch($(this).val());
        }
    });

    // Synchronisation depuis le champ SIREN/SIRET standard si l'utilisateur y clique
    $(document).on('click', '.btn-trigger-siret-search', function() {
        const val = $('#siren_siret').val();
        if (val) {
            $('#gov_siret_input').val(val);
        }
        executeSearch(val || $('#gov_siret_input').val());
    });
});
</script>
@endpush
