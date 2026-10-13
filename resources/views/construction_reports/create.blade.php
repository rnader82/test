@extends('layouts.app')

@section('title', 'Nouveau Compte Rendu')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('construction-reports.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">Nouveau Compte Rendu</h1>
    </div>
@endsection

@section('content')
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body py-4">
            <div class="wizard-steps d-flex justify-content-between position-relative">
                <div class="wizard-progress position-absolute top-50 start-0 translate-middle-y w-100 bg-light" style="height: 2px; z-index: 0;">
                    <div class="wizard-progress-bar bg-primary" style="height: 2px; width: 0%; transition: width 0.3s ease;"></div>
                </div>
                <div class="step-item active text-center position-relative" style="z-index: 1; width: 33.33%;">
                    <div class="step-circle mx-auto bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">1</div>
                    <div class="step-label small fw-bold">Informations</div>
                </div>
                <div class="step-item text-center position-relative" style="z-index: 1; width: 33.33%;">
                    <div class="step-circle mx-auto bg-white border text-muted rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">2</div>
                    <div class="step-label small fw-bold">Participants</div>
                </div>
                <div class="step-item text-center position-relative" style="z-index: 1; width: 33.33%;">
                    <div class="step-circle mx-auto bg-white border text-muted rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">3</div>
                    <div class="step-label small fw-bold">Observations</div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('construction-reports.store') }}" id="wizard-form" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="status" id="report-status" value="published">

        {{-- Step 1: General Info --}}
        <div class="wizard-step" id="step-1">
            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 text-primary">Informations Générales</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @if($type === 'prospect')
                                    <div class="col-md-6">
                                        <label class="form-label">Prospect <span class="text-danger">*</span></label>
                                        <select name="prospect_id" id="prospect_id" class="form-select select2-ajax" data-url="{{ route('prospects.search') }}" data-placeholder="Rechercher un prospect..." required>
                                            @if(request('prospect_id'))
                                                @php
                                                    $prospect = App\Models\Prospect::find(request('prospect_id'));
                                                @endphp
                                                @if($prospect)
                                                    <option value="{{ $prospect->id }}" selected>{{ $prospect->raison_sociale }}</option>
                                                @endif
                                            @endif
                                        </select>
                                    </div>
                                @else
                                    <div class="col-md-6">
                                        <label class="form-label">Client <span class="text-danger">*</span></label>
                                        <select name="client_id" id="client_id" class="form-select select2-ajax" data-url="{{ route('clients.search') }}" data-placeholder="Rechercher un client..." required>
                                        </select>
                                    </div>
                                @endif
                                <div class="col-md-6">
                                    <label class="form-label">Projet</label>
                                    <select name="project_id" id="project_id" class="form-select select2-ajax" data-url="{{ route('projects.search') }}" data-placeholder="Rechercher un projet..." required>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">N° Rapport</label>
                                    <input type="text" name="report_number" class="form-control" value="{{ old('report_number') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date</label>
                                    <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Lieu</label>
                                    <input type="text" name="location" class="form-control" value="{{ old('location') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 text-primary">Design & Media</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Photo du Projet</label>
                                <input type="file" name="project_picture" class="form-control" accept="image/*">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Coordonnées (En-tête)</label>
                                <textarea name="coordinates" class="form-control" rows="4">{{ old('coordinates') }}</textarea>
                                <small class="text-muted">Si vide, les coordonnées par défaut seront utilisées.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Step 2: Stakeholders --}}
        <div class="wizard-step d-none" id="step-2">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary">Participants</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary add-stakeholder">
                        <i class="bi bi-plus-lg"></i> Ajouter un participant
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0" id="table-stakeholders">
                            <thead class="bg-light text-muted small uppercase">
                                <tr>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Fonction</th>
                                    <th>Société</th>
                                    <th>Téléphone</th>
                                    <th>Email</th>
                                    <th>Statut</th>
                                    <th width="30" title="Convoqué">C</th>
                                    <th width="80" title="Nombre d'absences">Absence (N)</th>
                                    <th width="30" title="Recevoir le rapport par email">R</th>
                                    <th width="40"></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Step 3: Remarks --}}
        <div class="wizard-step d-none" id="step-3">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary">Observations</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary add-remark">
                        <i class="bi bi-plus-lg"></i> Ajouter une observation
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="table-remarks">
                            <thead class="bg-light text-muted small uppercase">
                                <tr>
                                    <th width="80">N°</th>
                                    <th width="200">Titre</th>
                                    <th>Description</th>
                                    <th width="150">Créé le</th>
                                    <th width="150">Date limite</th>
                                    <th width="150">Statut</th>
                                    <th width="40"></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="sticky-bottom bg-white p-3 border-top d-flex justify-content-between gap-2 shadow-lg rounded-top" style="z-index: 1000;">
            <div>
                <button type="button" class="btn btn-outline-secondary px-4 prev-step d-none">Précédent</button>
                <a href="{{ route('construction-reports.index') }}" class="btn btn-link text-muted cancel-wizard">Annuler</a>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-light border px-4 save-draft">Enregistrer en brouillon</button>
                <button type="button" class="btn btn-primary px-5 next-step">Suivant</button>
                <button type="submit" class="btn btn-success px-5 finish-step d-none">Terminer et Publier</button>
            </div>
        </div>
    </form>
</div>

{{-- Templates for JS --}}
<template id="tpl-stakeholder">
    <tr>
        <td><input type="text" name="stakeholders[{index}][last_name]" class="form-control form-control-sm"></td>
        <td><input type="text" name="stakeholders[{index}][first_name]" class="form-control form-control-sm"></td>
        <td><input type="text" name="stakeholders[{index}][position]" class="form-control form-control-sm"></td>
        <td><input type="text" name="stakeholders[{index}][company]" class="form-control form-control-sm"></td>
        <td><input type="text" name="stakeholders[{index}][phone]" class="form-control form-control-sm"></td>
        <td><input type="email" name="stakeholders[{index}][email]" class="form-control form-control-sm"></td>
        <td>
            <select name="stakeholders[{index}][status]" class="form-select form-select-sm">
                <option value="present">Présent</option>
                <option value="absent">Absent</option>
                <option value="excuse">Excusé</option>
                <option value="absent_non_excuse">Absent non excusé</option>
            </select>
        </td>
        <td class="text-center"><input type="checkbox" name="stakeholders[{index}][is_convoque]" class="form-check-input"></td>
        <td><input type="number" name="stakeholders[{index}][absence_count]" class="form-control form-control-sm" value="0"></td>
        <td class="text-center"><input type="checkbox" name="stakeholders[{index}][receives_report]" class="form-check-input"></td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger border-0 remove-row">
                <i class="bi bi-x-lg"></i>
            </button>
        </td>
    </tr>
</template>

<template id="tpl-remark">
    <tr class="align-top">
        <td>
            <input type="text" name="remarks[{index}][number]" class="form-control">
        </td>
        <td>
            <input type="text" name="remarks[{index}][title]" class="form-control" placeholder="Titre de la remarque">
        </td>
        <td>
            <div class="quill-editor bg-white" style="height: 120px;"></div>
            <input type="hidden" name="remarks[{index}][description]" class="quill-content">
            <input type="file" name="remarks[{index}][images][]" class="form-control form-control-sm mt-2" multiple accept="image/*">
        </td>
        <td><input type="date" name="remarks[{index}][created_date]" class="form-control" value="{{ date('Y-m-d') }}"></td>
        <td><input type="date" name="remarks[{index}][limit_date]" class="form-control"></td>
        <td><input type="text" name="remarks[{index}][status]" class="form-control"></td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger border-0 remove-row">
                <i class="bi bi-x-lg"></i>
            </button>
        </td>
    </tr>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let stakeholderIndex = 0;
    let remarkIndex = 0;

    // Wizard Logic
    let currentStep = 1;
    const totalSteps = 3;

    function updateWizard() {
        // Update Steps Visibility
        document.querySelectorAll('.wizard-step').forEach(step => step.classList.add('d-none'));
        document.getElementById(`step-${currentStep}`).classList.remove('d-none');

        // Update Progress Bar
        const progress = ((currentStep - 1) / (totalSteps - 1)) * 100;
        document.querySelector('.wizard-progress-bar').style.width = `${progress}%`;

        // Update Step Items
        document.querySelectorAll('.step-item').forEach((item, index) => {
            const stepNum = index + 1;
            const circle = item.querySelector('.step-circle');
            if (stepNum < currentStep) {
                circle.classList.remove('bg-white', 'border', 'text-muted', 'bg-primary');
                circle.classList.add('bg-success', 'text-white');
                circle.innerHTML = '<i class="bi bi-check-lg"></i>';
            } else if (stepNum === currentStep) {
                circle.classList.remove('bg-white', 'border', 'text-muted', 'bg-success');
                circle.classList.add('bg-primary', 'text-white');
                circle.innerHTML = stepNum;
            } else {
                circle.classList.remove('bg-primary', 'bg-success', 'text-white');
                circle.classList.add('bg-white', 'border', 'text-muted');
                circle.innerHTML = stepNum;
            }
        });

        // Update Buttons
        document.querySelector('.prev-step').classList.toggle('d-none', currentStep === 1);
        document.querySelector('.next-step').classList.toggle('d-none', currentStep === totalSteps);
        document.querySelector('.finish-step').classList.toggle('d-none', currentStep !== totalSteps);
        
        // Scroll to top of the form
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    document.querySelector('.next-step').addEventListener('click', () => {
        if (currentStep < totalSteps) {
            currentStep++;
            updateWizard();
        }
    });

    document.querySelector('.prev-step').addEventListener('click', () => {
        if (currentStep > 1) {
            currentStep--;
            updateWizard();
        }
    });

    document.querySelector('.save-draft').addEventListener('click', () => {
        document.getElementById('report-status').value = 'draft';
        document.querySelectorAll('[required]').forEach(el => el.removeAttribute('required'));
        document.getElementById('wizard-form').submit();
    });

    updateWizard();

    // Initialize Select2 AJAX
    function initSelect2Ajax(element) {
        const url = element.dataset.url;
        const placeholder = element.dataset.placeholder;

        $(element).select2({
            theme: 'bootstrap-5',
            placeholder: placeholder,
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term || ''
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.map(function (item) {
                            return {
                                id: item.id,
                                text: item.name || item.raison_sociale || 
                                      ((item.prenom || '') + (item.prenom && item.nom ? ' ' : '') + (item.nom || '')) || 
                                      item.email || (element.id === 'client_id' || element.id === 'prospect_id' ? '' : ('ID: ' + item.id))
                            };
                        })
                    };
                },
                cache: true
            }
        });
    }

    $('.select2-ajax').each(function() {
        initSelect2Ajax(this);
    });

    // Initialize Quill
    function initQuill(row) {
        const container = row.querySelector('.quill-editor');
        const input = row.querySelector('.quill-content');
        
        if (!container || !input) return;

        const quill = new Quill(container, {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['clean']
                ]
            }
        });

        quill.on('text-change', function() {
            input.value = quill.root.innerHTML === '<p><br></p>' ? '' : quill.root.innerHTML;
        });
    }

    // Add Stakeholder
    function addStakeholderRow() {
        const tpl = document.getElementById('tpl-stakeholder').innerHTML;
        const html = tpl.replace(/{index}/g, stakeholderIndex);
        document.querySelector('#table-stakeholders tbody').insertAdjacentHTML('beforeend', html);
        stakeholderIndex++;
    }

    document.querySelector('.add-stakeholder').addEventListener('click', addStakeholderRow);

    // Add Remark
    function addRemarkRow() {
        const tpl = document.getElementById('tpl-remark').innerHTML;
        const html = tpl.replace(/{index}/g, remarkIndex);
        const tbody = document.querySelector('#table-remarks tbody');
        tbody.insertAdjacentHTML('beforeend', html);
        
        const newRow = tbody.lastElementChild;
        initQuill(newRow);
        
        remarkIndex++;
    }

    document.querySelector('.add-remark').addEventListener('click', addRemarkRow);

    // Initial rows
    addStakeholderRow();
    addRemarkRow();

    // Remove row
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-row')) {
            e.target.closest('tr').remove();
        }
    });
});
</script>

<style>
    .form-control:focus, .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
    }
    .section-title {
        border-left: 4px solid #3b82f6;
        padding-left: 10px;
    }
    .ql-toolbar.ql-snow {
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        background: #f8fafc;
        padding: 4px;
    }
    .ql-container.ql-snow {
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
        font-family: inherit;
        font-size: 0.875rem;
    }
    .ql-editor {
        min-height: 80px;
        padding: 8px 12px;
    }
</style>
@endsection
