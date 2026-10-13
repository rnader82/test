@extends('layouts.app')

@section('title', __('locale.employee_equipments.title') ?? 'Matériel Collaborateurs & Flotte')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1 class="h4 mb-0 fw-bold">{{ __('locale.employee_equipments.title') ?? 'Matériel Collaborateurs & Flotte' }}</h1>
        <span class="badge bg-primary rounded-pill">{{ $equipments->total() }}</span>
    </div>
@endsection

@section('navbar-search')
    <form method="GET" action="{{ route('employee_equipments.index') }}" class="w-100">
        <div class="search-bar-mockup">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par marque, modèle, n° série, immatriculation, collaborateur...">
            @foreach(request()->except(['search', 'page']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </div>
    </form>
@endsection

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('employees.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-people me-1"></i> Gérer les salariés
        </a>
        <a href="{{ route('employee_equipments.export', request()->all()) }}" class="btn btn-outline-secondary">
            <i class="bi bi-file-earmark-excel me-1"></i> {{ __('locale.employee_equipments.export_csv') ?? 'Exporter CSV / Excel' }}
        </a>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="newEquipmentDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-plus-lg me-1"></i> {{ __('locale.employee_equipments.btn_add') ?? 'Nouveau matériel' }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="newEquipmentDropdown">
                <li><a class="dropdown-item py-2" href="{{ route('employee_equipments.create', ['type' => 'telephone']) }}"><i class="bi bi-phone me-2 text-primary"></i> {{ __('locale.employee_equipments.type_phone') ?? 'Téléphone' }}</a></li>
                <li><a class="dropdown-item py-2" href="{{ route('employee_equipments.create', ['type' => 'ordinateur']) }}"><i class="bi bi-laptop me-2 text-info"></i> {{ __('locale.employee_equipments.type_laptop') ?? 'Ordinateur portable' }}</a></li>
                <li><a class="dropdown-item py-2" href="{{ route('employee_equipments.create', ['type' => 'voiture']) }}"><i class="fa-solid fa-car me-2 text-success"></i> {{ __('locale.employee_equipments.type_car') ?? 'Véhicule de société' }}</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item py-2" href="{{ route('employee_equipments.create', ['type' => 'autre']) }}"><i class="bi bi-box me-2 text-secondary"></i> {{ __('locale.employee_equipments.type_other') ?? 'Autre matériel' }}</a></li>
            </ul>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .ee-nav-tabs {
        border-bottom: 2px solid #e2e8f0;
        margin-bottom: 1.25rem;
    }
    .ee-nav-tabs .nav-link {
        border: none;
        color: #64748b;
        font-weight: 500;
        padding: 0.75rem 1.25rem;
        position: relative;
        transition: all 0.2s ease;
        background: transparent;
    }
    .ee-nav-tabs .nav-link:hover {
        color: #0f172a;
    }
    .ee-nav-tabs .nav-link.active {
        color: #2563eb;
        font-weight: 600;
        background: transparent;
    }
    .ee-nav-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #2563eb;
    }
    .badge-tab-count {
        font-size: 0.75rem;
        padding: 0.2em 0.55em;
        border-radius: 9999px;
        margin-left: 0.4rem;
        background-color: #f1f5f9;
        color: #475569;
    }
    .ee-nav-tabs .nav-link.active .badge-tab-count {
        background-color: #dbeafe;
        color: #1d4ed8;
    }
    .stat-card-mini {
        background: #ffffff;
        border-radius: 0.75rem;
        border: 1px solid #e2e8f0;
        padding: 0.85rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.04);
    }
    .stat-icon-wrapper {
        width: 42px;
        height: 42px;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    
    <!-- Mini KPI Stat Row -->
    <div class="row g-3 mb-3" style="margin-top: 20px;">
        <div class="col-6 col-md-3">
            <div class="stat-card-mini">
                <div class="stat-icon-wrapper bg-primary-subtle text-primary">
                    <i class="bi bi-collection"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Parc</div>
                    <div class="h5 mb-0 fw-bold">{{ $counts['tous'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card-mini">
                <div class="stat-icon-wrapper bg-success-subtle text-success">
                    <i class="bi bi-person-check"></i>
                </div>
                <div>
                    <div class="text-muted small">En service</div>
                    <div class="h5 mb-0 fw-bold">{{ $counts['en_service'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card-mini">
                <div class="stat-icon-wrapper bg-info-subtle text-info">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div>
                    <div class="text-muted small">Disponible</div>
                    <div class="h5 mb-0 fw-bold">{{ $counts['disponible'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card-mini">
                <div class="stat-icon-wrapper bg-warning-subtle text-warning">
                    <i class="bi bi-shield-exclamation"></i>
                </div>
                <div>
                    <div class="text-muted small">CT à prévoir (60j)</div>
                    <div class="h5 mb-0 fw-bold text-warning">{{ $counts['ct_urgent'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-premium shadow-sm border-0">
        <div class="card-body p-4">
            
            <!-- Category Tabs -->
            <ul class="nav ee-nav-tabs">
                <li class="nav-item">
                    <a class="nav-link {{ $tab === 'tous' ? 'active' : '' }}" href="{{ route('employee_equipments.index', array_merge(request()->except(['tab', 'page']), ['tab' => 'tous'])) }}">
                        <i class="bi bi-grid me-1"></i> {{ __('locale.employee_equipments.tab_all') ?? 'Tous' }}
                        <span class="badge-tab-count">{{ $counts['tous'] }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $tab === 'telephone' ? 'active' : '' }}" href="{{ route('employee_equipments.index', array_merge(request()->except(['tab', 'page']), ['tab' => 'telephone'])) }}">
                        <i class="bi bi-phone me-1"></i> {{ __('locale.employee_equipments.tab_phones') ?? 'Téléphones' }}
                        <span class="badge-tab-count">{{ $counts['telephone'] }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $tab === 'ordinateur' ? 'active' : '' }}" href="{{ route('employee_equipments.index', array_merge(request()->except(['tab', 'page']), ['tab' => 'ordinateur'])) }}">
                        <i class="bi bi-laptop me-1"></i> {{ __('locale.employee_equipments.tab_laptops') ?? 'Ordinateurs' }}
                        <span class="badge-tab-count">{{ $counts['ordinateur'] }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $tab === 'voiture' ? 'active' : '' }}" href="{{ route('employee_equipments.index', array_merge(request()->except(['tab', 'page']), ['tab' => 'voiture'])) }}">
                        <i class="fa-solid fa-car me-1"></i> {{ __('locale.employee_equipments.tab_cars') ?? 'Flotte automobile' }}
                        <span class="badge-tab-count">{{ $counts['voiture'] }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $tab === 'autre' ? 'active' : '' }}" href="{{ route('employee_equipments.index', array_merge(request()->except(['tab', 'page']), ['tab' => 'autre'])) }}">
                        <i class="bi bi-box me-1"></i> {{ __('locale.employee_equipments.tab_others') ?? 'Autres' }}
                        <span class="badge-tab-count">{{ $counts['autre'] }}</span>
                    </a>
                </li>
            </ul>

            <!-- Filter Toolbar -->
            <form action="{{ route('employee_equipments.index') }}" method="GET" class="row g-2 mb-4 align-items-center">
                <input type="hidden" name="tab" value="{{ $tab }}">
                
                <div class="col-12 col-md-4">
                    <div class="search-bar-mockup w-100">
                        <i class="bi bi-search"></i>
                        <input type="text" name="search" placeholder="Rechercher..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <select name="company_id" class="form-select" onchange="this.form.submit()">
                        <option value="">{{ __('locale.all_companies') ?? 'Toutes les sociétés' }}</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->name }} ({{ $company->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-6 col-md-3">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Tous les statuts</option>
                        <option value="en_service" {{ request('status') === 'en_service' ? 'selected' : '' }}>En service</option>
                        <option value="disponible" {{ request('status') === 'disponible' ? 'selected' : '' }}>Disponible</option>
                        <option value="restitue" {{ request('status') === 'restitue' ? 'selected' : '' }}>Restitué</option>
                        <option value="en_maintenance" {{ request('status') === 'en_maintenance' ? 'selected' : '' }}>En maintenance</option>
                        <option value="hors_service" {{ request('status') === 'hors_service' ? 'selected' : '' }}>Hors service</option>
                    </select>
                </div>

                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1"><i class="bi bi-filter"></i> Filtrer</button>
                    @if(request()->hasAny(['search', 'status', 'company_id']))
                        <a href="{{ route('employee_equipments.index', ['tab' => $tab]) }}" class="btn btn-outline-secondary" title="Réinitialiser">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>

            <!-- Table Responsive -->
            <div class="table-responsive">
                <table class="table table-hover table-premium align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            @if($tab === 'tous')
                                <th>Catégorie</th>
                            @endif
                            <th>Société</th>
                            <th>Établissement</th>
                            <th>Marque</th>
                            <th>Modèle</th>
                            <th>N° série</th>
                            
                            @if($tab === 'telephone' || $tab === 'tous')
                                <th>IMEI</th>
                            @endif

                            @if($tab === 'voiture' || $tab === 'tous')
                                <th>Immatriculation</th>
                                @if($tab === 'voiture')
                                    <th>Contrôle Technique</th>
                                @endif
                            @endif

                            <th>Collaborateur assigné</th>
                            <th>Date remise</th>
                            <th>Date rendu</th>
                            <th>Suivi</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($equipments as $equipment)
                            @php
                                $assignment = $equipment->currentAssignment ?? $equipment->latestAssignment;
                                $assigneeName = $equipment->current_holder_name;
                                $isReturned = $assignment && $assignment->return_date && !$equipment->currentAssignment;
                            @endphp
                            <tr>
                                @if($tab === 'tous')
                                    <td>
                                        @if($equipment->type === 'telephone')
                                            <span class="badge bg-primary-subtle text-primary"><i class="bi bi-phone me-1"></i> Téléphone</span>
                                        @elseif($equipment->type === 'ordinateur')
                                            <span class="badge bg-info-subtle text-info"><i class="bi bi-laptop me-1"></i> Ordinateur</span>
                                        @elseif($equipment->type === 'voiture')
                                            <span class="badge bg-success-subtle text-success"><i class="fa-solid fa-car me-1"></i> Véhicule</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary"><i class="bi bi-box me-1"></i> Autre</span>
                                        @endif
                                    </td>
                                @endif

                                <td>
                                    <span class="{{ $equipment->effective_company ? 'fw-semibold text-dark' : 'text-muted' }}">{{ $equipment->effective_company?->name ?? 'Toutes' }}</span>
                                </td>

                                <td>
                                    <span class="{{ $equipment->effective_establishment ? 'text-dark' : 'text-muted' }}">{{ $equipment->effective_establishment ?? 'Toutes' }}</span>
                                </td>
                                
                                <td>
                                    <a href="{{ route('employee_equipments.show', $equipment->id) }}" class="fw-bold text-dark text-decoration-none hover-primary">
                                        {{ $equipment->brand ?? '—' }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('employee_equipments.show', $equipment->id) }}" class="text-dark text-decoration-none hover-primary">
                                        {{ $equipment->model ?? '—' }}
                                    </a>
                                </td>
                                
                                <td>
                                    @if($equipment->serial_number)
                                        <code class="text-muted">{{ $equipment->serial_number }}</code>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                @if($tab === 'telephone' || $tab === 'tous')
                                    <td>
                                        @if($equipment->imei)
                                            <span class="badge bg-light text-dark font-monospace">{{ $equipment->imei }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                @endif

                                @if($tab === 'voiture' || $tab === 'tous')
                                    <td>
                                        @if($equipment->registration_plate)
                                            <span class="badge bg-dark text-light font-monospace px-2 py-1">{{ $equipment->registration_plate }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    @if($tab === 'voiture')
                                        <td>
                                            @if($equipment->technical_inspection_date)
                                                @php
                                                    $isCtUrgent = $equipment->technical_inspection_date->isPast() || $equipment->technical_inspection_date->diffInDays(now()) <= 60;
                                                @endphp
                                                <span class="{{ $isCtUrgent ? 'text-danger fw-bold' : 'text-muted' }}">
                                                    {{ $equipment->technical_inspection_date->format('d/m/Y') }}
                                                    @if($isCtUrgent)
                                                        <i class="bi bi-exclamation-triangle-fill text-warning ms-1" title="Contrôle technique imminent ou dépassé"></i>
                                                    @endif
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    @endif
                                @endif

                                <td>
                                    @if($assigneeName)
                                        <div class="d-flex align-items-center gap-2">
                                            @if($assignment && $assignment->employee)
                                                <a href="{{ route('employees.show', $assignment->employee->id) }}" class="avatar avatar-xs bg-primary text-white rounded-circle d-flex align-items-center justify-content-center text-decoration-none" style="width: 26px; height: 26px; font-size: 11px;">
                                                    {{ mb_substr($assigneeName, 0, 1) }}
                                                </a>
                                                <a href="{{ route('employees.show', $assignment->employee->id) }}" class="fw-semibold text-dark text-decoration-none hover-primary">
                                                    {{ $assigneeName }}
                                                </a>
                                            @else
                                                <div class="avatar avatar-xs bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; font-size: 11px;">
                                                    {{ mb_substr($assigneeName, 0, 1) }}
                                                </div>
                                                <span class="fw-semibold text-dark">{{ $assigneeName }}</span>
                                            @endif
                                        </div>
                                    @elseif($assignment)
                                        @if($assignment->employee)
                                            <a href="{{ route('employees.show', $assignment->employee->id) }}" class="text-muted fst-italic text-decoration-none">
                                                Anc. {{ $assignment->full_name }}
                                            </a>
                                        @else
                                            <span class="text-muted fst-italic">Anc. {{ $assignment->full_name }}</span>
                                        @endif
                                    @else
                                        <span class="badge bg-light text-secondary">Non attribué</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $assignment && $assignment->handover_date ? $assignment->handover_date->format('d/m/Y') : '—' }}
                                </td>

                                <td>
                                    {{ $assignment && $assignment->return_date ? $assignment->return_date->format('d/m/Y') : '—' }}
                                </td>

                                <td>
                                    {!! $equipment->status_badge !!}
                                </td>

                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('employee_equipments.show', $equipment->id) }}">
                                                    <i class="bi bi-eye me-2 text-primary"></i> Détails & Historique
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('employee_equipments.edit', $equipment->id) }}">
                                                    <i class="bi bi-pencil me-2 text-info"></i> Modifier
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('employee_equipments.destroy', $equipment->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet équipement ?');">
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
                                <td colspan="15" class="text-center py-5">
                                    <div class="text-muted mb-3">
                                        <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                        {{ __('locale.employee_equipments.no_equipments_found') ?? 'Aucun équipement trouvé.' }}
                                    </div>
                                    <a href="{{ route('employee_equipments.create', ['type' => $tab !== 'tous' ? $tab : 'telephone']) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-plus-lg me-1"></i> Ajouter du matériel
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($equipments->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $equipments->links() }}
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
