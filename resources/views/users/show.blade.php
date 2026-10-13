@extends('layouts.app')

@section('title', $user->name)

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0 fs-5 fw-bold text-dark">{{ $user->name }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="mailto:{{ $user->email }}" class="btn btn-sm btn-outline-secondary px-3 py-1 fs-12">
            <i class="bi bi-envelope me-1"></i>{{ __('locale.lbl_email') ?? 'E-mail' }}
        </a>
        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-pencil-square me-1"></i>{{ __('locale.edit') ?? 'Modifier' }}
        </a>
        @if ($user->id !== auth()->id())
            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('locale.dashboard_new.confirm_delete_user') }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger px-3 py-1 fs-12">
                    <i class="bi bi-trash me-1"></i>{{ __('locale.delete') }}
                </button>
            </form>
        @endif
    </div>
@endsection

@section('content')
@php
    $directPermissionIds = $user->permissions->pluck('id')->toArray();
    $rolePermissionsMap = [];
    foreach ($user->roles as $role) {
        foreach ($role->permissions as $rp) {
            if (!isset($rolePermissionsMap[$rp->id])) {
                $rolePermissionsMap[$rp->id] = [];
            }
            $rolePermissionsMap[$rp->id][] = $role->name;
        }
    }
    $allGrantedPermissionIds = array_unique(array_merge($directPermissionIds, array_keys($rolePermissionsMap)));
    $totalGrantedCount = count($allGrantedPermissionIds);
    $directCount = count($directPermissionIds);
    $viaRolesCount = count(array_keys($rolePermissionsMap));
    $isAdminUser = $user->is_admin || $user->isNovalyoAdmin();
    $allowedPrefixes = $user->getAllowedCompanyPrefixes();
    $portalFields = \App\Models\UserAssignment::getPermissionFields();
    $userColor = $user->color ?: '#008cd2';
    $userInitials = strtoupper(substr($user->first_name ?: $user->name ?: 'U', 0, 1) . substr($user->last_name ?: '', 0, 1)) ?: strtoupper(substr($user->name ?: 'U', 0, 2));
@endphp

<div class="content-wrapper p-3 p-md-4">
    <div class="container-xxl flex-grow-1">

        <!-- User Profile Hero Card -->
        <div class="card card-premium shadow-sm border-0 mb-4 overflow-hidden position-relative">
            <!-- Colored accent strip -->
            <div class="position-absolute top-0 start-0 w-100" style="height: 5px; background: linear-gradient(90deg, {{ $userColor }}, #4f46e5);"></div>

            <div class="card-body p-4 pt-4">
                <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-3 pb-3 mb-3 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="position-relative">
                            <div class="d-flex align-items-center justify-content-center text-white fw-bold rounded-circle shadow-sm flex-shrink-0 user-avatar-circle"
                                 style="width: 64px; height: 64px; font-size: 1.4rem; background-color: {{ $userColor }}; border: 3px solid rgba(255,255,255,0.9); box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                                {{ $userInitials }}
                            </div>
                            <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle" style="width: 14px; height: 14px;" title="Actif"></span>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <h3 class="mb-0 fw-bold text-dark">{{ $user->name }}</h3>
                                <div class="d-flex align-items-center gap-1">
                                    <span class="d-inline-block rounded-circle border border-white shadow-sm" style="width: 14px; height: 14px; background-color: {{ $userColor }};" title="Couleur utilisateur: {{ $userColor }}"></span>
                                    <span class="badge bg-light text-secondary border small font-monospace">{{ $userColor }}</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3 mt-1 flex-wrap text-muted small">
                                <span>
                                    <i class="bi bi-envelope text-primary me-1"></i>
                                    <a href="mailto:{{ $user->email }}" class="text-decoration-none text-muted fw-medium">{{ $user->email }}</a>
                                </span>
                                @if($user->created_at)
                                    <span>
                                        <i class="bi bi-calendar3 me-1"></i>Inscrit le {{ $user->created_at->format('d/m/Y') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Role & Admin Badges -->
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        @if($user->is_admin || $user->isNovalyoAdmin())
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 fs-12 rounded-pill">
                                <i class="bi bi-shield-lock-fill me-1"></i>{{ __('locale.admin') }}
                            </span>
                        @endif
                        @if($user->isNovalyoAdmin())
                            <span class="badge bg-indigo-subtle text-indigo border border-indigo-subtle px-3 py-2 fs-12 rounded-pill" style="background-color: #ede9fe; color: #6366f1; border-color: #ddd6fe !important;">
                                <i class="bi bi-award-fill me-1"></i>Admin Novalyo
                            </span>
                        @endif
                        @if($user->is_employee)
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 fs-12 rounded-pill">
                                <i class="bi bi-briefcase-fill me-1"></i>{{ __('locale.employee') }}
                            </span>
                        @endif
                        @if($user->is_client_admin)
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 fs-12 rounded-pill">
                                <i class="bi bi-person-workspace me-1"></i>{{ __('locale.lbl_client_admin') ?? 'Admin Client' }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Responsive Quick-Details Grid -->
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="p-3 bg-light rounded-3 h-100 border border-light-subtle transition-hover">
                            <span class="text-muted small d-block mb-1 text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">{{ __('locale.nom') }}</span>
                            <span class="fw-bold text-dark fs-6">{{ $user->last_name ?: '—' }}</span>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 bg-light rounded-3 h-100 border border-light-subtle transition-hover">
                            <span class="text-muted small d-block mb-1 text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">{{ __('locale.prenom') }}</span>
                            <span class="fw-bold text-dark fs-6">{{ $user->first_name ?: '—' }}</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="p-3 bg-light rounded-3 h-100 border border-light-subtle transition-hover">
                            <span class="text-muted small d-block mb-1 text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">{{ __('locale.lbl_email') }}</span>
                            <span class="fw-semibold text-dark text-truncate d-block" title="{{ $user->email }}">{{ $user->email }}</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="p-3 bg-light rounded-3 h-100 border border-light-subtle transition-hover">
                            <span class="text-muted small d-block mb-1 text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">{{ __('locale.lbl_company_access') }}</span>
                            @if($user->isNovalyoAdmin())
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Toutes (Novalyo)</span>
                            @elseif(!empty($allowedPrefixes))
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($allowedPrefixes as $prefix)
                                        <span class="badge bg-white text-dark border rounded-pill">{{ $prefix }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted fst-italic small">Aucune restriction</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabbed Content Sections -->
        <div class="card card-premium shadow-sm border-0 mb-4 overflow-hidden">
            <div class="card-header bg-white border-bottom p-0">
                <ul class="nav nav-tabs border-0 px-3 pt-2 gap-1" id="userTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link custom-user-tab active" id="permissions-tab" data-bs-toggle="tab" data-bs-target="#permissions" type="button" role="tab">
                            <i class="bi bi-shield-check me-2"></i>{{ __('locale.menu_permissions') }}
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill ms-2">{{ $totalGrantedCount }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link custom-user-tab" id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles" type="button" role="tab">
                            <i class="bi bi-person-badge me-2"></i>{{ __('locale.lbl_roles') }}
                            <span class="badge bg-secondary-subtle text-secondary border rounded-pill ms-2">{{ $user->roles->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link custom-user-tab" id="assignments-tab" data-bs-toggle="tab" data-bs-target="#assignments" type="button" role="tab">
                            <i class="bi bi-buildings me-2"></i>{{ __('locale.lbl_user_assignments') ?? 'Affectations Clients' }}
                            <span class="badge bg-secondary-subtle text-secondary border rounded-pill ms-2">{{ $user->assignments->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link custom-user-tab" id="company-tab" data-bs-toggle="tab" data-bs-target="#company" type="button" role="tab">
                            <i class="bi bi-building-check me-2"></i>{{ __('locale.lbl_company_access') }}
                        </button>
                    </li>
                    @if ($user->clientDocumentTypes->count() > 0 || $user->hasDocumentAccess())
                    <li class="nav-item" role="presentation">
                        <button class="nav-link custom-user-tab" id="document-types-tab" data-bs-toggle="tab" data-bs-target="#document-types" type="button" role="tab">
                            <i class="bi bi-file-earmark-lock me-2"></i>{{ __('locale.document_types_assignment') ?? 'Types de documents' }}
                            <span class="badge bg-secondary-subtle text-secondary border rounded-pill ms-2">{{ $user->clientDocumentTypes->count() }}</span>
                        </button>
                    </li>
                    @endif
                </ul>
            </div>

            <div class="card-body p-4 bg-white" id="userTabsContent">
                <div class="tab-content">

                    <!-- Permissions Tab -->
                    <div class="tab-pane fade show active" id="permissions" role="tabpanel">

                        <!-- Summary & KPI Bar -->
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-4">
                                <div class="p-3 rounded-3 border bg-gradient-kpi d-flex align-items-center justify-content-between h-100 shadow-2xs">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle p-2 bg-primary-subtle text-primary d-flex align-items-center justify-content-center shadow-xs" style="width: 48px; height: 48px;">
                                            <i class="bi bi-shield-check fs-4"></i>
                                        </div>
                                        <div>
                                            <h3 class="mb-0 fw-bold text-dark">{{ $totalGrantedCount }}</h3>
                                            <small class="text-muted fw-medium">Total permissions accordées</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-primary text-white rounded-pill px-2 py-1 small">Total</span>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="p-3 rounded-3 border bg-gradient-kpi d-flex align-items-center justify-content-between h-100 shadow-2xs">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle p-2 bg-info-subtle text-info d-flex align-items-center justify-content-center shadow-xs" style="width: 48px; height: 48px;">
                                            <i class="bi bi-person-check fs-4"></i>
                                        </div>
                                        <div>
                                            <h3 class="mb-0 fw-bold text-dark">{{ $directCount }}</h3>
                                            <small class="text-muted fw-medium">Permissions directes</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-info text-white rounded-pill px-2 py-1 small">Directes</span>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="p-3 rounded-3 border bg-gradient-kpi d-flex align-items-center justify-content-between h-100 shadow-2xs">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle p-2 bg-success-subtle text-success d-flex align-items-center justify-content-center shadow-xs" style="width: 48px; height: 48px;">
                                            <i class="bi bi-people-fill fs-4"></i>
                                        </div>
                                        <div>
                                            <h3 class="mb-0 fw-bold text-dark">{{ $viaRolesCount }}</h3>
                                            <small class="text-muted fw-medium">Permissions via rôles</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-success text-white rounded-pill px-2 py-1 small">Rôles</span>
                                </div>
                            </div>
                        </div>

                        @if($isAdminUser)
                            <div class="alert alert-info border border-info-subtle d-flex align-items-center mb-4 p-3 rounded-3 shadow-2xs" role="alert">
                                <div class="p-2 rounded-circle bg-info-subtle text-info me-3 flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-shield-lock-fill fs-5"></i>
                                </div>
                                <div>
                                    <strong class="d-block text-dark mb-1">{{ __('locale.lbl_is_admin') ?? 'Administrateur' }} :</strong>
                                    <span class="text-secondary small">Cet utilisateur dispose des droits administrateur globaux. Même si certaines permissions ne sont pas explicitement cochées, l'accès à toutes les fonctionnalités système lui est accordé.</span>
                                </div>
                            </div>
                        @endif

                        <!-- Interactive Controls Toolbar -->
                        <div class="card bg-light border border-light-subtle mb-4 rounded-3 shadow-2xs">
                            <div class="card-body p-3">
                                <div class="row g-2 align-items-center">
                                    <div class="col-lg-4 col-md-5">
                                        <div class="input-group input-group-sm shadow-xs">
                                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                                            <input type="text" id="permissionSearch" class="form-control border-start-0 ps-0" placeholder="Rechercher une permission...">
                                            <button class="btn btn-outline-secondary border-start-0 bg-white text-muted d-none" type="button" id="clearSearchBtn">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-md-7">
                                        <div class="btn-group btn-group-sm w-100 shadow-xs" role="group" id="permissionFilterGroup">
                                            <button type="button" class="btn btn-outline-primary active" data-filter="granted">Toutes accordées</button>
                                            <button type="button" class="btn btn-outline-primary" data-filter="direct">Directes</button>
                                            <button type="button" class="btn btn-outline-primary" data-filter="roles">Via rôles</button>
                                            <button type="button" class="btn btn-outline-primary" data-filter="all">Toutes</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-12 text-lg-end text-start mt-2 mt-lg-0">
                                        <div class="btn-group btn-group-sm shadow-xs">
                                            <button type="button" class="btn btn-outline-secondary" id="btnExpandAll" title="Déplier tous les modules">
                                                <i class="bi bi-arrows-expand me-1"></i>Tout déplier
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary" id="btnCollapseAll" title="Replier tous les modules">
                                                <i class="bi bi-arrows-collapse me-1"></i>Tout replier
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Clean Empty-State Message -->
                        <div id="noPermissionsMatch" class="alert alert-light text-center border py-5 d-none rounded-3">
                            <div class="rounded-circle bg-light d-inline-flex p-3 mb-3 text-muted">
                                <i class="bi bi-search fs-2"></i>
                            </div>
                            <div class="fw-bold text-dark fs-6 mb-1">Aucune permission ne correspond à votre recherche ou filtre.</div>
                            <small class="text-muted">Essayez d'ajuster les filtres ou de modifier votre terme de recherche.</small>
                        </div>

                        <!-- Hierarchical Module Accordions -->
                        <div class="accordion custom-accordion" id="permissionsAccordion">
                            @foreach ($permissions->whereNull('parent_id') as $grandparentPermission)
                                @php
                                    $moduleChildPermissions = $permissions->where('parent_id', $grandparentPermission->id);
                                    $parentsWithChildren = $moduleChildPermissions->filter(function($p) use ($permissions) {
                                        return $permissions->where('parent_id', $p->id)->count() > 0;
                                    });
                                    $parentsWithoutChildren = $moduleChildPermissions->filter(function($p) use ($permissions) {
                                        return $permissions->where('parent_id', $p->id)->count() == 0;
                                    });

                                    // Collect all permission IDs under this module
                                    $moduleAllPermIds = [$grandparentPermission->id];
                                    foreach ($parentsWithoutChildren as $pwc) {
                                        $moduleAllPermIds[] = $pwc->id;
                                    }
                                    foreach ($parentsWithChildren as $parent) {
                                        $moduleAllPermIds[] = $parent->id;
                                        $children = $permissions->where('parent_id', $parent->id);
                                        foreach ($children as $c) {
                                            $moduleAllPermIds[] = $c->id;
                                        }
                                    }
                                    $moduleAllPermIds = array_unique($moduleAllPermIds);
                                    $moduleGrantedCount = count(array_intersect($moduleAllPermIds, $allGrantedPermissionIds));
                                    $moduleTotalCount = count($moduleAllPermIds);

                                    // Grandparent permission status
                                    $gpDirect = in_array($grandparentPermission->id, $directPermissionIds);
                                    $gpRoleNames = $rolePermissionsMap[$grandparentPermission->id] ?? [];
                                    $gpViaRole = !empty($gpRoleNames);
                                    $gpGranted = $gpDirect || $gpViaRole;
                                @endphp

                                <div class="accordion-item shadow-2xs mb-3 border rounded-3 overflow-hidden module-accordion-item"
                                     data-module-id="{{ $grandparentPermission->id }}"
                                     data-module-granted="{{ $moduleGrantedCount }}">
                                    <h2 class="accordion-header" id="heading{{ $grandparentPermission->id }}">
                                        <button class="accordion-button collapsed py-3 px-3 bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $grandparentPermission->id }}" aria-expanded="false" aria-controls="collapse{{ $grandparentPermission->id }}">
                                            <div class="d-flex align-items-center justify-content-between w-100 me-3 flex-wrap gap-2">
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="rounded p-2 bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                        <i class="bi bi-folder2-open fs-6"></i>
                                                    </span>
                                                    <span class="fw-bold text-dark fs-6">{{ $grandparentPermission->translated_name }}</span>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if($moduleGrantedCount > 0)
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill module-granted-badge">
                                                            <i class="bi bi-check-circle me-1"></i>{{ $moduleGrantedCount }} accordée{{ $moduleGrantedCount > 1 ? 's' : '' }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-light text-muted border rounded-pill module-granted-badge">0 accordée</span>
                                                    @endif
                                                    <span class="badge bg-light text-secondary border rounded-pill small">{{ $moduleTotalCount }} total</span>
                                                </div>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $grandparentPermission->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $grandparentPermission->id }}">
                                        <div class="accordion-body bg-light-subtle border-top p-3">

                                            <!-- Grandparent permission item -->
                                            <div class="module-direct-items mb-3">
                                                <div class="permission-item d-flex align-items-center justify-content-between p-2 px-3 rounded-3 border mb-2 shadow-2xs transition-hover {{ $gpGranted ? 'bg-white border-success-subtle' : 'bg-light text-muted border-light' }}"
                                                     data-name="{{ strtolower($grandparentPermission->translated_name . ' ' . $grandparentPermission->name . ' ' . $grandparentPermission->slug) }}"
                                                     data-granted="{{ $gpGranted ? '1' : '0' }}"
                                                     data-direct="{{ $gpDirect ? '1' : '0' }}"
                                                     data-via-role="{{ $gpViaRole ? '1' : '0' }}">
                                                    <div class="d-flex align-items-center gap-2 overflow-hidden">
                                                        @if($gpGranted)
                                                            <i class="bi bi-check-circle-fill text-success fs-5 flex-shrink-0"></i>
                                                        @else
                                                            <i class="bi bi-dash-circle text-muted fs-5 flex-shrink-0"></i>
                                                        @endif
                                                        <div class="text-truncate">
                                                            <span class="fw-bold {{ $gpGranted ? 'text-dark' : 'text-muted' }}">{{ $grandparentPermission->translated_name }}</span>
                                                            <small class="text-muted fst-italic ms-1 font-monospace">({{ $grandparentPermission->slug }})</small>
                                                            <span class="badge bg-secondary-subtle text-secondary border rounded-pill ms-1 small">Module principal</span>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center flex-wrap gap-1 flex-shrink-0 ms-2">
                                                        @if($gpDirect)
                                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">Directe</span>
                                                        @endif
                                                        @foreach($gpRoleNames as $roleName)
                                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Rôle: {{ $roleName }}</span>
                                                        @endforeach
                                                        @if(!$gpGranted)
                                                            <span class="badge bg-light text-muted border rounded-pill">Non accordée</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Parents with children -->
                                            @if($parentsWithChildren->count() > 0)
                                                <div class="row g-3 mb-3">
                                                    @foreach ($parentsWithChildren as $parentPermission)
                                                        @php
                                                            $pDirect = in_array($parentPermission->id, $directPermissionIds);
                                                            $pRoleNames = $rolePermissionsMap[$parentPermission->id] ?? [];
                                                            $pViaRole = !empty($pRoleNames);
                                                            $pGranted = $pDirect || $pViaRole;
                                                            $children = $permissions->where('parent_id', $parentPermission->id);
                                                        @endphp
                                                        <div class="col-md-6 parent-perm-card">
                                                            <div class="card h-100 shadow-2xs border rounded-3 overflow-hidden">
                                                                <div class="card-header bg-white p-2 px-3 border-bottom">
                                                                    <div class="permission-item d-flex align-items-center justify-content-between"
                                                                         data-name="{{ strtolower($parentPermission->translated_name . ' ' . $parentPermission->name . ' ' . $parentPermission->slug) }}"
                                                                         data-granted="{{ $pGranted ? '1' : '0' }}"
                                                                         data-direct="{{ $pDirect ? '1' : '0' }}"
                                                                         data-via-role="{{ $pViaRole ? '1' : '0' }}">
                                                                        <div class="d-flex align-items-center gap-2 overflow-hidden">
                                                                            @if($pGranted)
                                                                                <i class="bi bi-check-circle-fill text-success fs-5 flex-shrink-0"></i>
                                                                            @else
                                                                                <i class="bi bi-dash-circle text-muted fs-5 flex-shrink-0"></i>
                                                                            @endif
                                                                            <span class="fw-bold {{ $pGranted ? 'text-dark' : 'text-muted' }} text-truncate">{{ $parentPermission->translated_name }}</span>
                                                                        </div>
                                                                        <div class="d-flex align-items-center flex-wrap gap-1 flex-shrink-0 ms-2">
                                                                            @if($pDirect)
                                                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">Directe</span>
                                                                            @endif
                                                                            @foreach($pRoleNames as $roleName)
                                                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Rôle: {{ $roleName }}</span>
                                                                            @endforeach
                                                                            @if(!$pGranted)
                                                                                <span class="badge bg-light text-muted border rounded-pill">Non accordée</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body p-2 bg-light-subtle">
                                                                    @foreach ($children as $childPermission)
                                                                        @php
                                                                            $cDirect = in_array($childPermission->id, $directPermissionIds);
                                                                            $cRoleNames = $rolePermissionsMap[$childPermission->id] ?? [];
                                                                            $cViaRole = !empty($cRoleNames);
                                                                            $cGranted = $cDirect || $cViaRole;
                                                                        @endphp
                                                                        <div class="permission-item d-flex align-items-center justify-content-between p-2 px-3 rounded-2 border mb-1 transition-hover {{ $cGranted ? 'bg-white border-success-subtle' : 'bg-light text-muted border-light' }}"
                                                                             data-name="{{ strtolower($childPermission->translated_name . ' ' . $childPermission->name . ' ' . $childPermission->slug) }}"
                                                                             data-granted="{{ $cGranted ? '1' : '0' }}"
                                                                             data-direct="{{ $cDirect ? '1' : '0' }}"
                                                                             data-via-role="{{ $cViaRole ? '1' : '0' }}">
                                                                            <div class="d-flex align-items-center gap-2 overflow-hidden">
                                                                                @if($cGranted)
                                                                                    <i class="bi bi-check-circle-fill text-success fs-6 flex-shrink-0"></i>
                                                                                @else
                                                                                    <i class="bi bi-dash-circle text-muted fs-6 flex-shrink-0"></i>
                                                                                @endif
                                                                                <span class="small fw-semibold {{ $cGranted ? 'text-dark' : 'text-muted' }} text-truncate">{{ $childPermission->translated_name }}</span>
                                                                            </div>
                                                                            <div class="d-flex align-items-center flex-wrap gap-1 flex-shrink-0 ms-2">
                                                                                @if($cDirect)
                                                                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-12 rounded-pill">Directe</span>
                                                                                @endif
                                                                                @foreach($cRoleNames as $roleName)
                                                                                    <span class="badge bg-success-subtle text-success border border-success-subtle fs-12 rounded-pill">Rôle: {{ $roleName }}</span>
                                                                                @endforeach
                                                                                @if(!$cGranted)
                                                                                    <span class="badge bg-light text-muted border fs-12 rounded-pill">Non accordée</span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <!-- Parents without children -->
                                            @if($parentsWithoutChildren->count() > 0)
                                                <div class="parent-perm-card mb-2">
                                                    @if($parentsWithChildren->count() > 0)
                                                        <h6 class="text-muted small fw-bold mb-2 pt-2 border-top">
                                                            <i class="bi bi-list-check me-1"></i>{{ __('locale.lbl_other_permissions') ?? 'Autres permissions du module' }}
                                                        </h6>
                                                    @endif
                                                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-2">
                                                        @foreach ($parentsWithoutChildren as $parentPermission)
                                                            @php
                                                                $pwDirect = in_array($parentPermission->id, $directPermissionIds);
                                                                $pwRoleNames = $rolePermissionsMap[$parentPermission->id] ?? [];
                                                                $pwViaRole = !empty($pwRoleNames);
                                                                $pwGranted = $pwDirect || $pwViaRole;
                                                            @endphp
                                                            <div class="col">
                                                                <div class="permission-item d-flex align-items-center justify-content-between p-2 px-3 rounded-2 border h-100 transition-hover {{ $pwGranted ? 'bg-white border-success-subtle' : 'bg-light text-muted border-light' }}"
                                                                     data-name="{{ strtolower($parentPermission->translated_name . ' ' . $parentPermission->name . ' ' . $parentPermission->slug) }}"
                                                                     data-granted="{{ $pwGranted ? '1' : '0' }}"
                                                                     data-direct="{{ $pwDirect ? '1' : '0' }}"
                                                                     data-via-role="{{ $pwViaRole ? '1' : '0' }}">
                                                                    <div class="d-flex align-items-center gap-2 overflow-hidden">
                                                                        @if($pwGranted)
                                                                            <i class="bi bi-check-circle-fill text-success fs-6 flex-shrink-0"></i>
                                                                        @else
                                                                            <i class="bi bi-dash-circle text-muted fs-6 flex-shrink-0"></i>
                                                                        @endif
                                                                        <span class="small fw-semibold {{ $pwGranted ? 'text-dark' : 'text-muted' }} text-truncate">{{ $parentPermission->translated_name }}</span>
                                                                    </div>
                                                                    <div class="d-flex align-items-center flex-wrap gap-1 flex-shrink-0 ms-2">
                                                                        @if($pwDirect)
                                                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-12 rounded-pill">Directe</span>
                                                                        @endif
                                                                        @foreach($pwRoleNames as $roleName)
                                                                            <span class="badge bg-success-subtle text-success border border-success-subtle fs-12 rounded-pill">Rôle: {{ $roleName }}</span>
                                                                        @endforeach
                                                                        @if(!$pwGranted)
                                                                            <span class="badge bg-light text-muted border fs-12 rounded-pill">Non accordée</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>

                    <!-- Roles Tab -->
                    <div class="tab-pane fade" id="roles" role="tabpanel">
                        <div class="row g-3">
                            @forelse($user->roles as $role)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 border rounded-3 shadow-2xs overflow-hidden transition-hover">
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center py-3 border-bottom">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="rounded p-2 bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                    <i class="bi bi-shield-check"></i>
                                                </span>
                                                <h6 class="mb-0 fw-bold text-dark">{{ $role->name }}</h6>
                                            </div>
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">
                                                {{ $role->permissions->count() }} permission(s)
                                            </span>
                                        </div>
                                        <div class="card-body p-3">
                                            <p class="text-muted small mb-0">{{ $role->description ?: 'Aucune description disponible pour ce rôle.' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5 text-muted">
                                    <div class="rounded-circle bg-light d-inline-flex p-3 mb-3">
                                        <i class="bi bi-person-x fs-2 text-muted"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">{{ __('locale.lbl_no_roles') ?? 'Aucun rôle assigné' }}</h6>
                                    <p class="small text-muted mb-3">Cet utilisateur ne possède pas de rôle spécifique.</p>
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary px-3 rounded-pill">
                                        <i class="bi bi-plus-lg me-1"></i>Assigner des rôles
                                    </a>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Client Assignments Tab -->
                    <div class="tab-pane fade" id="assignments" role="tabpanel">
                        @forelse($user->assignments as $assignment)
                            <div class="card mb-3 border rounded-3 shadow-2xs overflow-hidden">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom flex-wrap gap-2">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="rounded p-2 bg-secondary-subtle text-secondary d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                            <i class="bi bi-building fs-5"></i>
                                        </span>
                                        <div>
                                            <strong class="text-dark fs-6">{{ $assignment->client->raison_sociale ?? 'Client #' . $assignment->client_id }}</strong>
                                            @if($assignment->client && $assignment->client->type)
                                                <span class="badge bg-secondary-subtle text-secondary border rounded-pill ms-2">{{ $assignment->client->type }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3" type="button" data-bs-toggle="collapse" data-bs-target="#client-perm-{{ $assignment->id }}" aria-expanded="true">
                                        <i class="bi bi-shield-lock me-1"></i>Permissions Portail
                                    </button>
                                </div>
                                <div id="client-perm-{{ $assignment->id }}" class="collapse show">
                                    <div class="card-body p-3 bg-light-subtle">
                                        <div class="row g-2">
                                            @foreach($portalFields as $field)
                                                <div class="col-sm-6 col-md-4 col-lg-3">
                                                    <div class="d-flex align-items-center gap-2 p-2 px-3 rounded-2 border transition-hover {{ $assignment->$field ? 'bg-white border-success-subtle text-dark' : 'bg-light text-muted border-light' }}">
                                                        @if($assignment->$field)
                                                            <i class="bi bi-check-circle-fill text-success"></i>
                                                            <span class="small fw-semibold text-dark">{{ __('locale.lbl_' . $field) }}</span>
                                                        @else
                                                            <i class="bi bi-x-circle text-muted"></i>
                                                            <span class="small text-muted">{{ __('locale.lbl_' . $field) }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">
                                <div class="rounded-circle bg-light d-inline-flex p-3 mb-3">
                                    <i class="bi bi-buildings fs-2 text-muted"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Aucune affectation client</h6>
                                <p class="small text-muted mb-0">Cet utilisateur n'est actuellement affecté à aucun client spécifique.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Company Access Tab -->
                    <div class="tab-pane fade" id="company" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card border rounded-3 shadow-2xs h-100 overflow-hidden">
                                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                                        <span class="rounded p-2 bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="bi bi-buildings"></i>
                                        </span>
                                        <h6 class="mb-0 fw-bold text-dark">Sociétés Novalyo</h6>
                                    </div>
                                    <div class="card-body p-3">
                                        @if($user->isNovalyoAdmin())
                                            <div class="alert alert-success d-flex align-items-center mb-0 p-3 rounded-3 border border-success-subtle">
                                                <i class="bi bi-check-circle-fill me-2 fs-5 text-success"></i>
                                                <div><strong>Toutes les sociétés:</strong> Accès administrateur global Novalyo.</div>
                                            </div>
                                        @else
                                            <ul class="list-group list-group-flush rounded-2 overflow-hidden border">
                                                <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-light text-dark border font-monospace">NO</span>
                                                        <span class="fw-semibold">Nova France</span>
                                                    </div>
                                                    @if(in_array('NO', $allowedPrefixes))
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Autorisé</span>
                                                    @else
                                                        <span class="badge bg-secondary-subtle text-secondary border rounded-pill">Non autorisé</span>
                                                    @endif
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-light text-dark border font-monospace">NS</span>
                                                        <span class="fw-semibold">Nova Solutions</span>
                                                    </div>
                                                    @if(in_array('NS', $allowedPrefixes))
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Autorisé</span>
                                                    @else
                                                        <span class="badge bg-secondary-subtle text-secondary border rounded-pill">Non autorisé</span>
                                                    @endif
                                                </li>
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border rounded-3 shadow-2xs h-100 overflow-hidden">
                                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                                        <span class="rounded p-2 bg-info-subtle text-info d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="bi bi-tags"></i>
                                        </span>
                                        <h6 class="mb-0 fw-bold text-dark">Préfixes spécifiques</h6>
                                    </div>
                                    <div class="card-body p-3">
                                        @php
                                            $customPrefixes = array_diff($allowedPrefixes, ['NO', 'NS']);
                                        @endphp
                                        @if(!empty($customPrefixes))
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($customPrefixes as $prefix)
                                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-6 px-3 py-2 rounded-pill font-monospace">{{ $prefix }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center py-4 text-muted">
                                                <i class="bi bi-slash-circle fs-3 d-block mb-2 text-muted"></i>
                                                <span class="small">Aucun préfixe spécifique supplémentaire défini.</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Document Types Tab -->
                    @if ($user->clientDocumentTypes->count() > 0 || $user->hasDocumentAccess())
                    <div class="tab-pane fade" id="document-types" role="tabpanel">
                        <div class="row g-3">
                            @forelse($user->clientDocumentTypes as $docType)
                                <div class="col-md-4">
                                    <div class="card border rounded-3 shadow-2xs p-3 d-flex flex-row align-items-center gap-3 transition-hover h-100">
                                        <div class="rounded-circle p-2 bg-primary-subtle text-primary flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                            <i class="bi bi-file-earmark-lock fs-5"></i>
                                        </div>
                                        <div class="overflow-hidden">
                                            <h6 class="mb-0 fw-bold text-dark text-truncate">{{ $docType->name }}</h6>
                                            @if($docType->code)
                                                <small class="badge bg-light text-secondary border font-monospace mt-1">{{ $docType->code }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5 text-muted">
                                    <div class="rounded-circle bg-light d-inline-flex p-3 mb-3">
                                        <i class="bi bi-file-earmark-x fs-2 text-muted"></i>
                                    </div>
                                    @if($user->hasDocumentAccess())
                                        <div class="alert alert-info d-inline-block text-start mb-0 p-3 rounded-3 border border-info-subtle">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Aucun type de document spécifique assigné : l'utilisateur a accès à tous les types de documents actifs grâce à ses permissions documentaires.
                                        </div>
                                    @else
                                        <p class="small text-muted mb-0">Aucun type de document assigné.</p>
                                    @endif
                                </div>
                            @endforelse
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>

<style>
/* Micro styles for user show page */
.transition-hover {
    transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
}
.transition-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}
.shadow-2xs {
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
}
.shadow-xs {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}
.bg-gradient-kpi {
    background: linear-gradient(180deg, #ffffff 0%, #fbfcfd 100%);
}
.custom-user-tab {
    border: none !important;
    border-bottom: 2px solid transparent !important;
    color: #64748b !important;
    font-weight: 600;
    font-size: 13.5px;
    padding: 0.75rem 1rem !important;
    border-radius: 0 !important;
    transition: all 0.2s ease;
}
.custom-user-tab:hover {
    color: var(--bs-primary, #008cd2) !important;
    background-color: transparent !important;
}
.custom-user-tab.active {
    color: var(--bs-primary, #008cd2) !important;
    border-bottom: 2px solid var(--bs-primary, #008cd2) !important;
    background-color: transparent !important;
}
.user-avatar-circle {
    transition: transform 0.2s ease;
}
.user-avatar-circle:hover {
    transform: scale(1.05);
}
.custom-accordion .accordion-button:not(.collapsed) {
    background-color: #f8fafc !important;
    color: var(--bs-primary, #008cd2);
    box-shadow: none;
}
.custom-accordion .accordion-button:focus {
    box-shadow: none;
    border-color: rgba(0, 140, 210, 0.2);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('permissionSearch');
    const clearSearchBtn = document.getElementById('clearSearchBtn');
    const filterButtons = document.querySelectorAll('#permissionFilterGroup button');
    const btnExpandAll = document.getElementById('btnExpandAll');
    const btnCollapseAll = document.getElementById('btnCollapseAll');
    const emptyState = document.getElementById('noPermissionsMatch');
    let currentFilter = 'granted'; // 'granted', 'direct', 'roles', 'all'

    function filterPermissions() {
        const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
        let totalVisible = 0;

        if (clearSearchBtn) {
            if (query.length > 0) {
                clearSearchBtn.classList.remove('d-none');
            } else {
                clearSearchBtn.classList.add('d-none');
            }
        }

        const moduleItems = document.querySelectorAll('.module-accordion-item');

        moduleItems.forEach(function (moduleEl) {
            let moduleVisibleCount = 0;

            // Process parent cards with children or leaf items
            const parentCards = moduleEl.querySelectorAll('.parent-perm-card');
            parentCards.forEach(function (cardEl) {
                let cardVisibleCount = 0;
                const items = cardEl.querySelectorAll('.permission-item');
                items.forEach(function (item) {
                    const isGranted = item.getAttribute('data-granted') === '1';
                    const isDirect = item.getAttribute('data-direct') === '1';
                    const isViaRole = item.getAttribute('data-via-role') === '1';
                    const name = item.getAttribute('data-name') || '';

                    let filterPass = false;
                    if (currentFilter === 'all') {
                        filterPass = true;
                    } else if (currentFilter === 'granted') {
                        filterPass = isGranted;
                    } else if (currentFilter === 'direct') {
                        filterPass = isDirect;
                    } else if (currentFilter === 'roles') {
                        filterPass = isViaRole;
                    }

                    const queryPass = !query || name.includes(query);

                    if (filterPass && queryPass) {
                        item.classList.remove('d-none');
                        cardVisibleCount++;
                        totalVisible++;
                    } else {
                        item.classList.add('d-none');
                    }
                });

                if (cardVisibleCount > 0) {
                    cardEl.classList.remove('d-none');
                    moduleVisibleCount += cardVisibleCount;
                } else {
                    cardEl.classList.add('d-none');
                }
            });

            // Process module direct items (grandparent item)
            const directItems = moduleEl.querySelectorAll('.module-direct-items > .permission-item');
            directItems.forEach(function (item) {
                const isGranted = item.getAttribute('data-granted') === '1';
                const isDirect = item.getAttribute('data-direct') === '1';
                const isViaRole = item.getAttribute('data-via-role') === '1';
                const name = item.getAttribute('data-name') || '';

                let filterPass = false;
                if (currentFilter === 'all') {
                    filterPass = true;
                } else if (currentFilter === 'granted') {
                    filterPass = isGranted;
                } else if (currentFilter === 'direct') {
                    filterPass = isDirect;
                } else if (currentFilter === 'roles') {
                    filterPass = isViaRole;
                }

                const queryPass = !query || name.includes(query);

                if (filterPass && queryPass) {
                    item.classList.remove('d-none');
                    moduleVisibleCount++;
                    totalVisible++;
                } else {
                    item.classList.add('d-none');
                }
            });

            const collapseEl = moduleEl.querySelector('.accordion-collapse');
            if (moduleVisibleCount > 0) {
                moduleEl.classList.remove('d-none');
                // Auto-expand if active search term
                if (query && collapseEl && typeof bootstrap !== 'undefined') {
                    const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapseEl, { toggle: false });
                    bsCollapse.show();
                }
            } else {
                moduleEl.classList.add('d-none');
            }
        });

        if (emptyState) {
            if (totalVisible === 0) {
                emptyState.classList.remove('d-none');
            } else {
                emptyState.classList.add('d-none');
            }
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterPermissions);
    }

    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function () {
            searchInput.value = '';
            filterPermissions();
            searchInput.focus();
        });
    }

    filterButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            filterButtons.forEach(function (b) { b.classList.remove('active'); });
            this.classList.add('active');
            currentFilter = this.getAttribute('data-filter');
            filterPermissions();
        });
    });

    if (btnExpandAll) {
        btnExpandAll.addEventListener('click', function () {
            document.querySelectorAll('#permissionsAccordion .accordion-collapse').forEach(function (collapseEl) {
                if (typeof bootstrap !== 'undefined') {
                    const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapseEl, { toggle: false });
                    bsCollapse.show();
                }
            });
        });
    }

    if (btnCollapseAll) {
        btnCollapseAll.addEventListener('click', function () {
            document.querySelectorAll('#permissionsAccordion .accordion-collapse').forEach(function (collapseEl) {
                if (typeof bootstrap !== 'undefined') {
                    const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapseEl, { toggle: false });
                    bsCollapse.hide();
                }
            });
        });
    }

    // Run initial filter (default: 'granted')
    filterPermissions();
});
</script>
@endsection
