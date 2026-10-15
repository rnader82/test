@extends('layouts.app')
@section('portal-module', 'employee_equipments')
@section('portal-page', 'show')
@section('portal-kind', 'record')
@section('portal-family', 'technical')
@section('portal-icon', 'screwdriver-wrench')


@section('title', ($employeeEquipment->brand . ' ' . $employeeEquipment->model) ?: 'Détails du matériel')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('employee_equipments.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="h4 mb-0 fw-bold">{{ $employeeEquipment->brand }} {{ $employeeEquipment->model }}</h1>
        {!! $employeeEquipment->status_badge !!}
    </div>
@endsection

@section('page-actions')
    <div class="d-flex gap-2">
        @if($employeeEquipment->currentAssignment)
            <button type="button" class="btn btn-warning text-dark" data-bs-toggle="modal" data-bs-target="#returnModal">
                <i class="bi bi-arrow-return-left me-1"></i> {{ __('locale.employee_equipments.btn_return') ?? 'Enregistrer la restitution' }}
            </button>
        @endif
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#assignModal">
            <i class="bi bi-person-plus me-1"></i> {{ __('locale.employee_equipments.btn_assign') ?? 'Attribuer' }}
        </button>
        <a href="{{ route('employee_equipments.edit', $employeeEquipment->id) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i> Modifier
        </a>
    </div>
@endsection

@push('styles')
<style>
    .timeline-item {
        position: relative;
        padding-left: 2.25rem;
        padding-bottom: 1.75rem;
    }
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: 0.7rem;
        top: 0.5rem;
        bottom: 0;
        width: 2px;
        background-color: #e2e8f0;
    }
    .timeline-item:last-child::before {
        display: none;
    }
    .timeline-dot {
        position: absolute;
        left: 0;
        top: 0.25rem;
        width: 1.5rem;
        height: 1.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    
    @if($employeeEquipment->type === 'voiture' && $employeeEquipment->technical_inspection_date)
        @php
            $isCtUrgent = $employeeEquipment->technical_inspection_date->isPast() || $employeeEquipment->technical_inspection_date->diffInDays(now()) <= 60;
        @endphp
        @if($isCtUrgent)
            <div class="alert alert-warning d-flex align-items-center mb-4 shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                <div>
                    <strong>Alerte Contrôle Technique :</strong> La date du prochain contrôle technique est prévue le 
                    <strong>{{ $employeeEquipment->technical_inspection_date->format('d/m/Y') }}</strong> 
                    ({{ $employeeEquipment->technical_inspection_date->isPast() ? 'Dépassé !' : 'dans ' . $employeeEquipment->technical_inspection_date->diffInDays(now()) . ' jours' }}).
                </div>
            </div>
        @endif
    @endif

    <div class="row g-4">
        
        <!-- Left Column: Asset Info & Current Assignment -->
        <div class="col-12 col-lg-7">
            
            <!-- Asset Details Card -->
            <div class="module-record-card card card-premium shadow-sm border-0 mb-4">
                <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">
                        @if($employeeEquipment->type === 'telephone')
                            <i class="bi bi-phone me-2 text-primary"></i> Fiche Téléphone
                        @elseif($employeeEquipment->type === 'ordinateur')
                            <i class="bi bi-laptop me-2 text-info"></i> Fiche Ordinateur
                        @elseif($employeeEquipment->type === 'voiture')
                            <i class="fa-solid fa-car me-2 text-success"></i> Fiche Véhicule de Société
                        @else
                            <i class="bi bi-box me-2 text-secondary"></i> Fiche Matériel
                        @endif
                    </h5>
                    <span class="badge bg-light text-dark border">#{{ $employeeEquipment->id }}</span>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-6 col-md-4">
                            <span class="text-muted small d-block">Catégorie</span>
                            <span class="fw-bold">{{ $employeeEquipment->type_label }}</span>
                        </div>
                        <div class="col-6 col-md-4">
                            <span class="text-muted small d-block">Société</span>
                            <span class="fw-bold {{ $employeeEquipment->effective_company ? 'text-dark' : 'text-muted' }}">
                                {{ $employeeEquipment->effective_company?->name ?? 'Toutes (Parc global)' }}
                            </span>
                        </div>
                        <div class="col-6 col-md-4">
                            <span class="text-muted small d-block">Établissement / Agence</span>
                            <span class="fw-bold {{ $employeeEquipment->effective_establishment ? 'text-dark' : 'text-muted' }}">
                                {{ $employeeEquipment->effective_establishment ?? 'Toutes' }}
                            </span>
                        </div>
                        <div class="col-6 col-md-4">
                            <span class="text-muted small d-block">Marque</span>
                            <span class="fw-bold">{{ $employeeEquipment->brand ?? '—' }}</span>
                        </div>
                        <div class="col-6 col-md-4">
                            <span class="text-muted small d-block">Modèle</span>
                            <span class="fw-bold">{{ $employeeEquipment->model ?? '—' }}</span>
                        </div>
                        <div class="col-6 col-md-4">
                            <span class="text-muted small d-block">N° de série</span>
                            <code class="fw-bold">{{ $employeeEquipment->serial_number ?? '—' }}</code>
                        </div>

                        @if($employeeEquipment->type === 'telephone')
                            <div class="col-12 col-md-6">
                                <span class="text-muted small d-block">Numéro IMEI</span>
                                <span class="badge bg-light text-primary font-monospace fs-6 px-3 py-2 border">{{ $employeeEquipment->imei ?? 'Non renseigné' }}</span>
                            </div>
                        @endif

                        @if($employeeEquipment->type === 'voiture')
                            <div class="col-6 col-md-4">
                                <span class="text-muted small d-block">Immatriculation</span>
                                <span class="badge bg-dark text-white font-monospace fs-6 px-3 py-1">{{ $employeeEquipment->registration_plate ?? '—' }}</span>
                            </div>
                            <div class="col-6 col-md-4">
                                <span class="text-muted small d-block">Type de véhicule</span>
                                <span class="fw-bold">{{ $employeeEquipment->vehicle_type ?? '—' }}</span>
                            </div>
                            <div class="col-6 col-md-4">
                                <span class="text-muted small d-block">Motorisation / Carburant</span>
                                <span class="fw-bold">{{ $employeeEquipment->fuel_type ?? '—' }}</span>
                            </div>
                            <div class="col-6 col-md-4">
                                <span class="text-muted small d-block">1ère mise en circulation</span>
                                <span class="fw-bold">{{ $employeeEquipment->first_registration_date ? $employeeEquipment->first_registration_date->format('d/m/Y') : '—' }}</span>
                            </div>
                            <div class="col-6 col-md-4">
                                <span class="text-muted small d-block">Contrôle technique (CT)</span>
                                <span class="fw-bold {{ ($employeeEquipment->technical_inspection_date && $employeeEquipment->technical_inspection_date->diffInDays(now()) <= 60) ? 'text-danger' : '' }}">
                                    {{ $employeeEquipment->technical_inspection_date ? $employeeEquipment->technical_inspection_date->format('d/m/Y') : '—' }}
                                </span>
                            </div>
                            <div class="col-6 col-md-4">
                                <span class="text-muted small d-block">Kilométrage</span>
                                <span class="fw-bold">{{ $employeeEquipment->mileage ? number_format($employeeEquipment->mileage, 0, ',', ' ') . ' km' : '—' }}</span>
                            </div>
                            <div class="col-12 col-md-6">
                                <span class="text-muted small d-block">Carte carburant / Télépéage</span>
                                <span class="fw-bold">{{ $employeeEquipment->fuel_card_badge ?? '—' }}</span>
                            </div>
                        @endif

                        @if($employeeEquipment->notes)
                            <div class="col-12 mt-3 pt-3 border-top">
                                <span class="text-muted small d-block mb-1">Commentaire général :</span>
                                <div class="p-3 bg-light rounded text-dark">{{ $employeeEquipment->notes }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Current Assignment Box -->
            <div class="module-record-card card card-premium shadow-sm border-0">
                <div class="card-header bg-transparent border-bottom py-3">
                    <h5 class="card-title mb-0 fw-bold"><i class="bi bi-person-check me-2 text-primary"></i> Affectation en cours</h5>
                </div>
                <div class="card-body p-4">
                    @if($employeeEquipment->currentAssignment)
                        @php 
                            $current = $employeeEquipment->currentAssignment; 
                            $emp = $current->employee;
                        @endphp
                        <div class="d-flex align-items-center justify-content-between p-3 bg-primary-subtle rounded-3 mb-3 border border-primary-subtle">
                            <div class="d-flex align-items-center gap-3">
                                @if($emp)
                                    <a href="{{ route('employees.show', $emp->id) }}" class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-5 text-decoration-none" style="width: 48px; height: 48px;">
                                        {{ mb_substr($current->full_name, 0, 1) }}
                                    </a>
                                    <div>
                                        <h6 class="mb-0 fw-bold fs-6">
                                            <a href="{{ route('employees.show', $emp->id) }}" class="text-dark text-decoration-none hover-primary">
                                                {{ $current->full_name }}
                                            </a>
                                        </h6>
                                        <span class="text-muted small">
                                            {{ $emp->company?->name . ($emp->establishment ? ' • ' . $emp->establishment : '') }}
                                        </span>
                                    </div>
                                @else
                                    <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 48px; height: 48px;">
                                        {{ mb_substr($current->full_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold fs-6 text-dark">{{ $current->full_name }}</h6>
                                        <span class="text-muted small">Saisie libre</span>
                                    </div>
                                @endif
                            </div>
                            <span class="badge bg-success px-3 py-2">En possession</span>
                        </div>

                        <div class="row g-3">
                            <div class="col-6">
                                <span class="text-muted small d-block">Date de remise au collaborateur</span>
                                <span class="fw-bold fs-6">{{ $current->handover_date->format('d/m/Y') }}</span>
                            </div>
                            <div class="col-6">
                                <span class="text-muted small d-block">Durée d'affectation</span>
                                <span class="fw-semibold text-secondary">{{ $current->handover_date->diffForHumans(null, true) }}</span>
                            </div>
                            @if($current->notes)
                                <div class="col-12">
                                    <span class="text-muted small d-block">Notes sur l'affectation</span>
                                    <p class="mb-0 text-muted fst-italic">{{ $current->notes }}</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-box-seam fs-1 text-muted d-block mb-2"></i>
                            <h6 class="text-muted fw-bold">Matériel actuellement disponible</h6>
                            <p class="text-muted small mb-3">Cet équipement n'est actuellement affecté à aucun collaborateur.</p>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignModal">
                                <i class="bi bi-person-plus me-1"></i> Attribuer à un collaborateur
                            </button>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Right Column: Full Assignment History Timeline -->
        <div class="col-12 col-lg-5">
            <div class="module-record-card card card-premium shadow-sm border-0">
                <div class="card-header bg-transparent border-bottom py-3">
                    <h5 class="card-title mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i> Historique des affectations</h5>
                </div>
                <div class="card-body p-4">
                    @forelse($employeeEquipment->assignments as $assignment)
                        @php $aEmp = $assignment->employee; @endphp
                        <div class="timeline-item">
                            <div class="timeline-dot {{ $assignment->is_active ? 'bg-success text-white' : 'bg-secondary text-white' }}">
                                <i class="bi {{ $assignment->is_active ? 'bi-check-lg' : 'bi-arrow-left-right' }}"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <div>
                                        @if($aEmp)
                                            <h6 class="mb-0 fw-bold">
                                                <a href="{{ route('employees.show', $aEmp->id) }}" class="text-dark text-decoration-none hover-primary">
                                                    {{ $assignment->full_name }}
                                                </a>
                                            </h6>
                                            @if($aEmp->company || $aEmp->establishment)
                                                <small class="text-muted">{{ $aEmp->company?->name }} {{ $aEmp->establishment ? '• ' . $aEmp->establishment : '' }}</small>
                                            @endif
                                        @else
                                            <h6 class="mb-0 fw-bold text-dark">{{ $assignment->full_name }}</h6>
                                        @endif
                                    </div>
                                    @if($assignment->is_active)
                                        <span class="badge bg-success-subtle text-success">Actuel</span>
                                    @else
                                        <span class="badge bg-light text-muted border">Restitué</span>
                                    @endif
                                </div>
                                <div class="text-muted small mb-1 mt-1">
                                    <i class="bi bi-calendar-check me-1"></i> Remis le : <strong>{{ $assignment->handover_date->format('d/m/Y') }}</strong>
                                    @if($assignment->return_date)
                                        <br><i class="bi bi-calendar-x me-1"></i> Rendu le : <strong>{{ $assignment->return_date->format('d/m/Y') }}</strong>
                                    @endif
                                </div>
                                @if($assignment->notes)
                                    <div class="p-2 bg-light rounded small text-secondary mt-2">
                                        {{ $assignment->notes }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-journal-x fs-2 d-block mb-2 text-secondary"></i>
                            Aucun historique d'affectation pour le moment.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal Attribuer -->
<div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('employee_equipments.assign', $employeeEquipment->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="assignModalLabel"><i class="bi bi-person-plus text-primary me-2"></i> {{ __('locale.employee_equipments.modal_assign_title') ?? 'Attribuer ce matériel' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Sélectionnez le collaborateur auquel remettre ce matériel (sa société et son établissement seront automatiquement rattachés) :</p>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="modal_employee_id">Salarié / Collaborateur</label>
                        <select name="employee_id" id="modal_employee_id" class="form-select">
                            <option value="">-- Choisir un collaborateur --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">
                                    {{ $emp->select_label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="text-center my-2 text-muted small">— ou saisie manuelle —</div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold" for="modal_last_name">Nom</label>
                            <input type="text" name="last_name" id="modal_last_name" class="form-control form-control-sm" placeholder="Nom">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold" for="modal_first_name">Prénom</label>
                            <input type="text" name="first_name" id="modal_first_name" class="form-control form-control-sm" placeholder="Prénom">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="modal_handover_date">Date de remise</label>
                        <input type="date" name="handover_date" id="modal_handover_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="modal_notes">Commentaire / Remise</label>
                        <textarea name="notes" id="modal_notes" rows="2" class="form-control" placeholder="Observations sur la remise..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Valider l'affectation</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Restituer -->
@if($employeeEquipment->currentAssignment)
<div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('employee_equipments.return', $employeeEquipment->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="returnModalLabel"><i class="bi bi-arrow-return-left text-warning me-2"></i> {{ __('locale.employee_equipments.modal_return_title') ?? 'Enregistrer la restitution' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Enregistrer la restitution du matériel par <strong>{{ $employeeEquipment->currentAssignment->full_name }}</strong> :</p>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="return_date">Date de restitution</label>
                        <input type="date" name="return_date" id="return_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="new_status">Nouveau statut du matériel</label>
                        <select name="new_status" id="new_status" class="form-select" required>
                            <option value="disponible" selected>Disponible (Prêt pour réattribution)</option>
                            <option value="restitue">Restitué (En stock)</option>
                            <option value="en_maintenance">En maintenance / Nettoyage</option>
                            <option value="hors_service">Hors service / Défectueux</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="return_notes">Observations de restitution</label>
                        <textarea name="notes" id="return_notes" rows="3" class="form-control" placeholder="État du matériel rendu, accessoires rendus, etc..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning text-dark"><i class="bi bi-check-lg me-1"></i> Confirmer la restitution</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

@endsection
