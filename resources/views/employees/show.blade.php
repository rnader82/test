@extends('layouts.app')
@section('portal-module', 'employees')
@section('portal-page', 'show')
@section('portal-kind', 'record')
@section('portal-family', 'technical')
@section('portal-icon', 'screwdriver-wrench')


@section('title', $employee->full_name . ' - Fiche Collaborateur')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('employees.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="h4 mb-0 fw-bold">{{ $employee->full_name }}</h1>
        @if($employee->is_active)
            <span class="badge bg-success">Actif</span>
        @else
            <span class="badge bg-secondary">Inactif</span>
        @endif
    </div>
@endsection

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i> Modifier le profil
        </a>
    </div>
@endsection

@section('content')
<div class="container-fluid px-0">
    <div class="row g-4">
        
        <!-- Left Column: Employee Profile Details -->
        <div class="col-12 col-lg-4">
            <div class="module-record-card card card-premium shadow-sm border-0 mb-4">
                <div class="card-body p-4 text-center border-bottom">
                    <div class="avatar bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold fs-2 mb-3 shadow-sm" style="width: 72px; height: 72px;">
                        {{ mb_substr($employee->first_name, 0, 1) }}
                    </div>
                    <h5 class="fw-bold mb-1">{{ $employee->full_name }}</h5>
                    @if($employee->job_title)
                        <div class="text-muted mb-2">{{ $employee->job_title }}</div>
                    @endif
                    <div class="d-flex justify-content-center gap-2">
                        <span class="badge bg-light text-dark border">
                            <i class="bi bi-building me-1"></i> {{ $employee->company?->name ?? 'Toutes sociétés' }}
                        </span>
                        @if($employee->establishment)
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-geo-alt me-1"></i> {{ $employee->establishment }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="card-body p-4">
                    <h6 class="fw-bold text-uppercase small text-muted mb-3">Coordonnées & Informations</h6>
                    
                    <div class="mb-3">
                        <span class="text-muted small d-block">Adresse email</span>
                        @if($employee->email)
                            <a href="mailto:{{ $employee->email }}" class="fw-semibold text-decoration-none">
                                <i class="bi bi-envelope me-1"></i> {{ $employee->email }}
                            </a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <span class="text-muted small d-block">Téléphone</span>
                        @if($employee->phone)
                            <a href="tel:{{ $employee->phone }}" class="fw-semibold text-decoration-none">
                                <i class="bi bi-telephone me-1"></i> {{ $employee->phone }}
                            </a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <span class="text-muted small d-block">Compte utilisateur système</span>
                        @if($employee->user)
                            <span class="badge bg-info-subtle text-info p-2 border border-info-subtle">
                                <i class="bi bi-person-check me-1"></i> {{ $employee->user->name }} ({{ $employee->user->email }})
                            </span>
                        @else
                            <span class="text-muted small fst-italic">Salarié sans compte applicatif</span>
                        @endif
                    </div>

                    <div>
                        <span class="text-muted small d-block">Enregistré le</span>
                        <span class="small text-muted">{{ $employee->created_at ? $employee->created_at->format('d/m/Y') : '—' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Assigned Equipments (Active & History) -->
        <div class="col-12 col-lg-8">
            
            <!-- Active Assigned Equipments Card -->
            <div class="module-record-card card card-premium shadow-sm border-0 mb-4">
                <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-laptop me-2 text-primary"></i> Matériel actuellement en possession
                    </h5>
                    <span class="badge bg-success rounded-pill">{{ $employee->activeAssignments->count() }} actif(s)</span>
                </div>
                <div class="card-body p-4">
                    @if($employee->activeAssignments->isNotEmpty())
                        <div class="row g-3">
                            @foreach($employee->activeAssignments as $assignment)
                                @php $eq = $assignment->employeeEquipment; @endphp
                                @if($eq)
                                    <div class="col-12 col-md-6">
                                        <div class="module-record-card card h-100 border shadow-none p-3 hover-shadow-sm transition">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div class="d-flex align-items-center gap-2">
                                                    @if($eq->type === 'telephone')
                                                        <i class="bi bi-phone fs-4 text-primary"></i>
                                                    @elseif($eq->type === 'ordinateur')
                                                        <i class="bi bi-laptop fs-4 text-info"></i>
                                                    @elseif($eq->type === 'voiture')
                                                        <i class="fa-solid fa-car fs-4 text-success"></i>
                                                    @else
                                                        <i class="bi bi-box fs-4 text-secondary"></i>
                                                    @endif
                                                    <div>
                                                        <a href="{{ route('employee_equipments.show', $eq->id) }}" class="fw-bold text-dark text-decoration-none d-block">
                                                            {{ $eq->brand }} {{ $eq->model }}
                                                        </a>
                                                        <small class="text-muted">{{ $eq->type_label }}</small>
                                                    </div>
                                                </div>
                                                {!! $eq->status_badge !!}
                                            </div>

                                            <div class="small text-muted mb-2">
                                                @if($eq->serial_number)
                                                    <div><strong>N° série :</strong> <code>{{ $eq->serial_number }}</code></div>
                                                @endif
                                                @if($eq->imei)
                                                    <div><strong>IMEI :</strong> <code>{{ $eq->imei }}</code></div>
                                                @endif
                                                @if($eq->registration_plate)
                                                    <div><strong>Immatriculation :</strong> <span class="badge bg-dark text-white">{{ $eq->registration_plate }}</span></div>
                                                @endif
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center pt-2 border-top mt-auto small">
                                                <span class="text-muted">
                                                    <i class="bi bi-calendar-check me-1"></i> Remis le {{ $assignment->handover_date->format('d/m/Y') }}
                                                </span>
                                                <a href="{{ route('employee_equipments.show', $eq->id) }}" class="btn btn-sm btn-outline-primary py-0 px-2">
                                                    Voir la fiche <i class="bi bi-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-box-seam fs-1 d-block mb-2 text-secondary"></i>
                            Aucun matériel actuellement attribué à ce collaborateur.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Complete Assignment History Timeline -->
            <div class="module-record-card card card-premium shadow-sm border-0">
                <div class="card-header bg-transparent border-bottom py-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-clock-history me-2 text-primary"></i> Historique complet des affectations
                    </h5>
                </div>
                <div class="card-body p-4">
                    @forelse($employee->assignments as $assignment)
                        @php $eq = $assignment->employeeEquipment; @endphp
                        <div class="d-flex align-items-start gap-3 pb-3 mb-3 border-bottom {{ $loop->last ? 'border-bottom-0 pb-0 mb-0' : '' }}">
                            <div class="avatar rounded-circle d-flex align-items-center justify-content-center {{ $assignment->is_active ? 'bg-success text-white' : 'bg-secondary text-white' }}" style="width: 36px; height: 36px; min-width: 36px;">
                                @if($eq?->type === 'telephone')
                                    <i class="bi bi-phone"></i>
                                @elseif($eq?->type === 'ordinateur')
                                    <i class="bi bi-laptop"></i>
                                @elseif($eq?->type === 'voiture')
                                    <i class="fa-solid fa-car"></i>
                                @else
                                    <i class="bi bi-box"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    @if($eq)
                                        <a href="{{ route('employee_equipments.show', $eq->id) }}" class="fw-bold text-dark text-decoration-none">
                                            {{ $eq->brand }} {{ $eq->model }} <span class="badge bg-light text-muted border ms-1">{{ $eq->type_label }}</span>
                                        </a>
                                    @else
                                        <span class="text-muted fw-bold">Équipement archivé</span>
                                    @endif

                                    @if($assignment->is_active)
                                        <span class="badge bg-success-subtle text-success">Actuel</span>
                                    @else
                                        <span class="badge bg-light text-muted border">Restitué</span>
                                    @endif
                                </div>

                                <div class="text-muted small mt-1">
                                    <i class="bi bi-calendar-check me-1"></i> Remis le : <strong>{{ $assignment->handover_date->format('d/m/Y') }}</strong>
                                    @if($assignment->return_date)
                                        &nbsp;•&nbsp; <i class="bi bi-calendar-x me-1"></i> Rendu le : <strong>{{ $assignment->return_date->format('d/m/Y') }}</strong>
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
                            Aucun historique d'affectation enregistré pour ce collaborateur.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
