@extends('layouts.app')
@section('portal-module', 'projects')
@section('portal-page', 'show')
@section('portal-kind', 'record')
@section('portal-family', 'adv')
@section('portal-icon', 'boxes-stacked')


@section('title', 'Détails du Projet - ' . $project->name)

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <div class="mb-0">
            <h1 class="mb-0">{{ $project->name }}</h1>
            <p class="text-muted mb-0 small">
                <i class="bi bi-person me-1"></i> {{ $project->client->raison_sociale ?? 'N/A' }}
                <span class="mx-2">|</span>
                <i class="bi bi-calendar-event me-1"></i> {{ $project->date_debut ? $project->date_debut->format('d/m/Y') : '?' }} - {{ $project->date_fin ? $project->date_fin->format('d/m/Y') : '?' }}
            </p>
        </div>
    </div>
@endsection

@section('page-actions')
    <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-warning">
        <i class="bi bi-pencil-square me-1"></i> {{ __('locale.edit') }}
    </a>
@endsection

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="module-record-card card mb-4">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="projectTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="missions-tab" data-bs-toggle="tab" data-bs-target="#missions" type="button" role="tab">Missions</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="equipments-tab" data-bs-toggle="tab" data-bs-target="#equipments" type="button" role="tab">Équipements</button>
                </li>
                @foreach ($documentCategories as $key => $label)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="{{ $key }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $key }}" type="button" role="tab">{{ $label }}</button>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="projectTabsContent">
                <!-- Missions / Tasks -->
                <div class="tab-pane fade show active" id="missions" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Liste des Tâches</h5>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                            <i class="bi bi-plus-lg me-1"></i> Ajouter une tâche
                        </button>
                    </div>

                    @if ($project->tasks->count())
                        <ul class="list-group">
                            @foreach ($project->tasks as $task)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="task-{{ $task->id }}" {{ $task->is_completed ? 'checked' : '' }} 
                                            onclick="window.location='{{ route('projects.tasks.toggle', $task->id) }}'">
                                        <label class="form-check-label {{ $task->is_completed ? 'text-decoration-line-through text-muted' : '' }}" for="task-{{ $task->id }}">
                                            <strong>{{ $task->title }}</strong>
                                            @if ($task->description)
                                                <br><small class="text-muted">{{ $task->description }}</small>
                                            @endif
                                        </label>
                                    </div>
                                    <div>
                                        <form action="{{ route('projects.tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm text-danger" onclick="return confirm('Supprimer cette tâche ?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">Aucune tâche pour le moment.</p>
                    @endif
                </div>

                <!-- Document Categories -->
                @foreach ($documentCategories as $key => $label)
                    <div class="tab-pane fade" id="{{ $key }}" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>{{ $label }}</h5>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal" onclick="setUploadType('{{ $key }}', '{{ addslashes($label) }}')">
                                <i class="bi bi-upload me-1"></i> Charger un document
                            </button>
                        </div>

                        @php $docs = $project->documents->where('type', $key); @endphp

                        @if ($docs->count())
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nom du fichier</th>
                                            <th>Taille</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($docs as $doc)
                                            <tr>
                                                <td><i class="bi bi-file-earmark me-2"></i>{{ $doc->document_name }}</td>
                                                <td>{{ $doc->size_human }}</td>
                                                <td>{{ $doc->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <a href="{{ route('projects.documents.download', $doc->id) }}" class="btn btn-sm btn-outline-secondary">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                    <form action="{{ route('projects.documents.destroy', $doc->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ce document ?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">Aucun document chargé dans cette catégorie.</p>
                        @endif
                    </div>
                @endforeach

                <!-- Equipments -->
                <div class="tab-pane fade" id="equipments" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Équipements Nécessaires</h5>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addEquipmentModal">
                            <i class="bi bi-plus-lg me-1"></i> Ajouter un équipement
                        </button>
                    </div>

                    @if ($project->equipments->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Équipement</th>
                                        <th>Référence</th>
                                        <th>Quantité</th>
                                        <th>Date Besoin</th>
                                        <th>Statut Stock</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($project->equipments as $equip)
                                        <tr>
                                            <td>
                                                <a href="{{ route('equipments.show', $equip->id) }}" class="fw-bold text-dark text-decoration-none">
                                                    {{ $equip->name }}
                                                </a>
                                            </td>
                                            <td><code class="small">{{ $equip->reference }}</code></td>
                                            <td><span class="badge bg-primary px-3 py-2 rounded-pill">{{ $equip->pivot->quantity }}</span></td>
                                            <td>
                                                @if($equip->pivot->needed_by_date)
                                                    <span class="text-dark">{{ \Carbon\Carbon::parse($equip->pivot->needed_by_date)->format('d/m/Y') }}</span>
                                                @else
                                                    <span class="text-muted italic small">Non définie</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $stock = $equip->current_stock ?? 0;
                                                @endphp
                                                @if($stock >= $equip->pivot->quantity)
                                                    <span class="badge bg-success-subtle text-success border-0"><i class="bi bi-check-circle me-1"></i> {{ $stock }} en stock</span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning border-0"><i class="bi bi-exclamation-triangle me-1"></i> {{ $stock }} en stock</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary border-0" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editEquipmentModal"
                                                        data-id="{{ $equip->id }}"
                                                        data-name="{{ addslashes($equip->name) }}"
                                                        data-quantity="{{ $equip->pivot->quantity }}"
                                                        data-date="{{ $equip->pivot->needed_by_date ? \Carbon\Carbon::parse($equip->pivot->needed_by_date)->format('Y-m-d') : '' }}">
                                                        <i class="bi bi-pencil small"></i>
                                                    </button>
                                                    <form action="{{ route('projects.removeEquipment', [$project->id, $equip->id]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Retirer cet équipement du projet ?')">
                                                            <i class="bi bi-trash small"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5 bg-light rounded-3">
                            <i class="bi bi-tools fs-1 text-muted opacity-25 mb-3"></i>
                            <p class="text-muted mb-0">Aucun équipement planifié pour ce projet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Equipment -->
<div class="modal fade" id="addEquipmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('projects.addEquipment', $project->id) }}" method="POST">
            @csrf
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Ajouter un équipement</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Rechercher l'équipement</label>
                        <select name="equipment_id" id="select2-add-equipment" class="form-select" required></select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Quantité</label>
                            <input type="number" name="quantity" class="form-control" value="1" min="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Date besoin</label>
                            <input type="date" name="needed_by_date" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Ajouter au projet</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Equipment -->
<div class="modal fade" id="editEquipmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editEquipmentForm" method="POST">
            @csrf
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-warning text-dark border-0">
                    <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Modifier : <span id="edit-equip-name"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Quantité</label>
                            <input type="number" name="quantity" id="edit-equip-quantity" class="form-control" min="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Date besoin</label>
                            <input type="date" name="needed_by_date" id="edit-equip-date" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning px-4 fw-bold">Mettre à jour</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Add Task -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('projects.tasks.store', $project->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter une tâche</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Titre</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description (optionnel)</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Upload Document -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('projects.documents.upload', $project->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" id="upload_type">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Charger un document : <span id="upload_label"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Fichier</label>
                        <input type="file" name="document" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Charger</button>
                </div>
            </div>
        </form>
    </div>
</div>

    <div id="page-bottom" class="mb-4"></div>

</div>

<script>
    function setUploadType(type, label) {
        document.getElementById('upload_type').value = type;
        document.getElementById('upload_label').innerText = label;
    }


    $(document).ready(function() {
        // Tab persistence
        const storageKey = 'activeTab-project-{{ $project->id }}';
        const activeTab = localStorage.getItem(storageKey);
        if (activeTab) {
            const tabEl = document.querySelector(`button[data-bs-target="${activeTab}"]`);
            if (tabEl) {
                const tab = new bootstrap.Tab(tabEl);
                tab.show();
            }
        }

        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            localStorage.setItem(storageKey, $(e.target).attr('data-bs-target'));
        });

        // Init Select2 for add modal
        $('#select2-add-equipment').select2({
            theme: 'bootstrap-5',
            width: '100%',
            dropdownParent: $('#addEquipmentModal'),
            placeholder: 'Rechercher un équipement...',
            minimumInputLength: 2,
            ajax: {
                url: "{{ route('equipments.search') }}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return { q: params.term };
                },
                processResults: function(data) {
                    return {
                        results: data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.name + (item.reference ? ' (' + item.reference + ')' : '')
                            };
                        })
                    };
                },
                cache: true
            }
        });

        // Handle edit modal population
        const editEquipmentModal = document.getElementById('editEquipmentModal');
        if (editEquipmentModal) {
            editEquipmentModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const quantity = button.getAttribute('data-quantity');
                const date = button.getAttribute('data-date');

                const form = editEquipmentModal.querySelector('#editEquipmentForm');
                form.action = `{{ url('projects') }}/{{ $project->id }}/equipments/${id}`;
                
                editEquipmentModal.querySelector('#edit-equip-name').textContent = name;
                editEquipmentModal.querySelector('#edit-equip-quantity').value = quantity;
                editEquipmentModal.querySelector('#edit-equip-date').value = date;
            });
        }
    });
</script>
@endsection
