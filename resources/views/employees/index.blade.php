@extends('layouts.app')
@section('portal-module', 'employees')
@section('portal-page', 'index')
@section('portal-kind', 'list')
@section('portal-family', 'technical')
@section('portal-icon', 'screwdriver-wrench')


@section('title', 'Gestion des salariés')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1 class="h4 mb-0 fw-bold">Gestion des salariés</h1>
        <span class="badge bg-primary rounded-pill">{{ $employees->total() }}</span>
    </div>
@endsection

@section('navbar-search')
    <form method="GET" action="{{ route('employees.index') }}" class="w-100">
        <div class="search-bar-mockup">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un salarié par nom, prénom, email, établissement...">
            @foreach(request()->except(['search', 'page']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </div>
    </form>
@endsection

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('employee_equipments.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-laptop me-1"></i> Matériel collaborateurs
        </a>
        <a href="{{ route('employees.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus me-1"></i> Nouveau salarié
        </a>
    </div>
@endsection

@section('content')
<div class="container-fluid px-0">
    <div class="card card-premium shadow-sm border-0" style="margin-top: 20px;">
        <div class="card-body p-4">
            
            <!-- Table -->
            <div class="table-responsive">
                <table class="module-table table table-hover table-premium align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nom & Prénom</th>
                            <th>Société</th>
                            <th>Établissement / Site</th>
                            <th>Poste / Fonction</th>
                            <th>Coordonnées</th>
                            <th>Matériel actif en possession</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('employees.show', $employee->id) }}" class="avatar avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold text-decoration-none" style="width: 32px; height: 32px; font-size: 13px;">
                                            {{ mb_substr($employee->first_name, 0, 1) }}
                                        </a>
                                        <div>
                                            <a href="{{ route('employees.show', $employee->id) }}" class="fw-bold text-dark text-decoration-none d-block hover-primary">
                                                {{ $employee->full_name }}
                                            </a>
                                            @if($employee->user)
                                                <span class="badge bg-light text-muted border small"><i class="bi bi-person-check me-1"></i> Compte lié</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="fw-semibold text-dark">{{ $employee->company?->name ?? '—' }}</span>
                                </td>

                                <td>{{ $employee->establishment ?? '—' }}</td>

                                <td>{{ $employee->job_title ?? '—' }}</td>

                                <td>
                                    @if($employee->email)
                                        <div class="small text-muted"><i class="bi bi-envelope me-1"></i> {{ $employee->email }}</div>
                                    @endif
                                    @if($employee->phone)
                                        <div class="small text-muted"><i class="bi bi-telephone me-1"></i> {{ $employee->phone }}</div>
                                    @endif
                                    @if(!$employee->email && !$employee->phone)
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td>
                                    @if($employee->activeAssignments->isNotEmpty())
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($employee->activeAssignments as $assignment)
                                                @php $eq = $assignment->employeeEquipment; @endphp
                                                @if($eq)
                                                    <a href="{{ route('employee_equipments.show', $eq->id) }}" class="badge bg-primary-subtle text-primary text-decoration-none p-2 border border-primary-subtle" title="Voir la fiche matériel">
                                                        @if($eq->type === 'telephone')
                                                            <i class="bi bi-phone me-1"></i>
                                                        @elseif($eq->type === 'ordinateur')
                                                            <i class="bi bi-laptop me-1"></i>
                                                        @elseif($eq->type === 'voiture')
                                                            <i class="fa-solid fa-car me-1"></i>
                                                        @endif
                                                        {{ $eq->brand }} {{ $eq->model }}
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted small fst-italic">Aucun matériel assigné</span>
                                    @endif
                                </td>

                                <td>
                                    @if($employee->is_active)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-secondary">Inactif</span>
                                    @endif
                                </td>

                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('employees.show', $employee->id) }}">
                                                    <i class="bi bi-eye me-2 text-info"></i> Consulter
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('employees.edit', $employee->id) }}">
                                                    <i class="bi bi-pencil me-2 text-primary"></i> Modifier
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" onsubmit="return confirm('Supprimer ce salarié ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bi bi-trash me-2"></i> Supprimer
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-people fs-1 d-block mb-2 text-secondary"></i>
                                    Aucun salarié trouvé.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($employees->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $employees->links() }}
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
