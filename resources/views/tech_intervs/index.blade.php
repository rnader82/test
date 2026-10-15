@extends('layouts.app')
@section('portal-module', 'tech_intervs')
@section('portal-page', 'index')
@section('portal-kind', 'list')
@section('portal-family', 'technical')
@section('portal-icon', 'screwdriver-wrench')


@section('title', __('locale.lbl_technical_intervention'))

@section('navbar-icon')
    <i class="fa-solid fa-screwdriver-wrench title-icon me-2"></i>
@endsection

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ $pageTitle ?? __('locale.lbl_technical_intervention') }}</h1>
    </div>
@endsection

@section('navbar-subtitle', 'Gérez et suivez l\'ensemble de vos interventions techniques')

@section('navbar-search')
    <form method="GET" action="{{ route('tech_intervs.index') }}" class="w-100" id="searchForm">
        <label class="search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input id="searchInput" type="search" name="search" value="{{ request('search') }}" placeholder="{{ __('locale.search_placeholder') }}" />
        </label>
        @foreach(request()->except(['search', 'page']) as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
    </form>
@endsection

@section('page-actions')
    @php
        $hasActiveFilters = request()->filled('group_id')
            || request()->filled('client_id')
            || request()->filled('assigned_user')
            || request()->filled('status_id')
            || request()->filled('state_id')
            || request()->filled('type_id')
            || request()->filled('tech_intervention_type_id')
            || request()->filled('severity_id')
            || request()->filled('tech_intervention_severity_id')
            || request()->filled('metier_id')
            || request()->filled('tech_intervention_metier_id')
            || request()->filled('priority')
            || request()->filled('criticity_internal')
            || request()->filled('criticity_client')
            || request()->filled('contract_status')
            || request()->filled('start_date')
            || request()->filled('end_date')
            || request()->filled('new_messages');
    @endphp

    <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#advancedFilters" aria-expanded="{{ $hasActiveFilters ? 'true' : 'false' }}">
        <i class="fa-solid fa-filter"></i> {{ __('locale.advanced_filters') }}
        @if($hasActiveFilters)
            <span class="badge bg-primary rounded-circle p-1 ms-1" style="width: 7px; height: 7px; display: inline-block;"></span>
        @endif
    </button>

    <div class="create-menu" id="createMenu">
        @if (Auth::user()->is_admin || Auth::user()->hasPermission('create-intervention'))
            <button class="btn primary create-main create-tech-interv-btn" id="createTechIntervBtn" type="button">
                <i class="fa-solid fa-plus"></i> {{ __('locale.create') ?? 'Créer' }}
            </button>
            <button class="btn primary create-toggle dropdown-toggle dropdown-toggle-split" id="createMenuToggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Ouvrir le menu de création">
                <i class="fa-solid fa-chevron-down"></i>
            </button>

            <div class="create-dropdown dropdown-menu dropdown-menu-end shadow border-0" id="createDropdown" role="menu">
                <button class="create-option create-tech-interv-btn" type="button" role="menuitem">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                    <span>
                        <strong>{{ __('locale.lbl_create_technical_intervention') ?? 'Créer une intervention' }}</strong>
                        <small>Planifier ou enregistrer une intervention technique.</small>
                    </span>
                </button>

                @if (Auth::user()->hasPermission('open-ticket') || Auth::user()->is_admin == 1)
                <a class="create-option" href="{{ route('issues.create') }}" role="menuitem">
                    <i class="fa-solid fa-ticket"></i>
                    <span>
                        <strong>{{ __('locale.btn_create_issue') ?? 'Créer un ticket' }}</strong>
                        <small>Créer une nouvelle demande de support.</small>
                    </span>
                </a>
                @endif
            </div>
        @endif
    </div>
@endsection

@section('content')
<div class="panel">
    <!-- Toolbar with Modern Tabs and Pager -->
    <div class="toolbar">
        <div class="tabs">
            @php
                $isMine = (string)request('assigned_user') === (string)Auth::id();
                $isPriority = request('contract_status') === 'avec_contrat' && (request('priority') === 'Urgent' || request('criticity_internal') === 'Urgent');
                $isAll = !$isMine && !$isPriority && !request()->filled('status_id') && !request()->filled('new_messages');
            @endphp
            <a href="{{ route('tech_intervs.index') }}" class="tab {{ $isAll ? 'active' : '' }}">
                Toutes les interventions <span class="count">{{ $interventions->total() }}</span>
            </a>
            <a href="{{ route('tech_intervs.index', ['assigned_user' => Auth::id()]) }}" class="tab {{ $isMine ? 'active' : '' }}">
                Mes interventions
            </a>
            <a href="{{ route('tech_intervs.index', ['contract_status' => 'avec_contrat', 'priority' => 'Urgent']) }}" class="tab priority {{ $isPriority ? 'active' : '' }}">
                Prioritaire (Urgent &amp; sous contrat)
            </a>
            @if(isset($statuses) && $statuses->isNotEmpty())
                @foreach($statuses->take(3) as $st)
                    @php
                        $isStatusTab = (string)request('status_id') === (string)$st->id;
                    @endphp
                    <a href="{{ route('tech_intervs.index', ['status_id' => $st->id]) }}" class="tab {{ $isStatusTab ? 'active' : '' }}">
                        <span style="background-color: {{ $st->color ?? '#6c757d' }}; width: 8px; height: 8px; border-radius: 50%; display: inline-block;"></span>
                        {{ $st->translated_name }}
                    </a>
                @endforeach
            @endif
        </div>

        <div class="toolbar-right">
            <div class="pager">
                <span id="pagerLabel" class="pager-label">
                    {{ $interventions->firstItem() ?? 0 }}-{{ $interventions->lastItem() ?? 0 }} / {{ $interventions->total() }}
                </span>
                @if ($interventions->onFirstPage())
                    <span class="pager-btn" style="opacity: 0.45; cursor: not-allowed;"><i class="fa-solid fa-chevron-left"></i></span>
                @else
                    <a href="{{ $interventions->previousPageUrl() }}" class="pager-btn" aria-label="Page précédente"><i class="fa-solid fa-chevron-left"></i></a>
                @endif

                @if ($interventions->hasMorePages())
                    <a href="{{ $interventions->nextPageUrl() }}" class="pager-btn" aria-label="Page suivante"><i class="fa-solid fa-chevron-right"></i></a>
                @else
                    <span class="pager-btn" style="opacity: 0.45; cursor: not-allowed;"><i class="fa-solid fa-chevron-right"></i></span>
                @endif
            </div>
        </div>
    </div>

    <!-- Advanced Filters Form Collapse -->
    <div class="collapse {{ $hasActiveFilters ? 'show' : '' }}" id="advancedFilters" style="border-bottom: 1px solid var(--border); background: #fafbfc;">
        <div class="p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-primary d-flex align-items-center gap-2">
                    <i class="fa-solid fa-filter"></i>{{ __('locale.advanced_filters') }}
                </h6>
                @if($hasActiveFilters)
                    <a href="{{ route('tech_intervs.index', request('search') ? ['search' => request('search')] : []) }}" class="btn btn-sm btn-outline-danger">
                        <i class="fa-solid fa-rotate-left me-1"></i>{{ __('locale.lbl_reset_filters') }}
                    </a>
                @endif
            </div>
            <form method="GET" action="{{ route('tech_intervs.index') }}">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif
                @if(request('direction'))
                    <input type="hidden" name="direction" value="{{ request('direction') }}">
                @endif
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="filter_group_id" class="form-label">{{ __('locale.lbl_group') }}</label>
                        <select name="group_id" id="filter_group_id" class="form-select select2" data-placeholder="{{ __('locale.lbl_all_groups') }}">
                            <option value="">{{ __('locale.lbl_all_groups') }}</option>
                            @foreach ($groups as $group)
                                <option value="{{ $group->id }}" {{ (string)request('group_id') === (string)$group->id ? 'selected' : '' }}> {{ $group->raison_sociale }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filter_client_id" class="form-label">{{ __('locale.lbl_client') }}</label>
                        <select name="client_id" id="filter_client_id" class="form-select select2" data-placeholder="{{ __('locale.lbl_all_clients') }}">
                            <option value="">{{ __('locale.lbl_all_clients') }}</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}" {{ (string)request('client_id') === (string)$client->id ? 'selected' : '' }}> {{ $client->raison_sociale }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filter_assigned_user" class="form-label">{{ __('locale.lbl_assigned_user') }}</label>
                        <select name="assigned_user" id="filter_assigned_user" class="form-select select2" data-placeholder="{{ __('locale.menu_users') }}">
                            <option value="">{{ __('locale.menu_users') }}</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ (string)request('assigned_user') === (string)$user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filter_status_id" class="form-label">{{ __('locale.lbl_status') }}</label>
                        <select name="status_id" id="filter_status_id" class="form-select select2" data-placeholder="{{ __('locale.all_statuses') }}">
                            <option value="">{{ __('locale.all_statuses') }}</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status->id }}" {{ (string)request('status_id') === (string)$status->id ? 'selected' : '' }}>
                                    {{ $status->translated_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filter_state_id" class="form-label">{{ __('locale.lbl_state') }}</label>
                        <select name="state_id" id="filter_state_id" class="form-select select2" data-placeholder="{{ __('locale.all_states') }}">
                            <option value="">{{ __('locale.all_states') }}</option>
                            @foreach ($states as $state)
                                <option value="{{ $state->id }}" {{ (string)request('state_id') === (string)$state->id ? 'selected' : '' }}>
                                    {{ $state->translated_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filter_type_id" class="form-label">{{ __('locale.lbl_type') }}</label>
                        <select name="type_id" id="filter_type_id" class="form-select select2" data-placeholder="{{ __('locale.lbl_all_types') }}">
                            <option value="">{{ __('locale.lbl_all_types') }}</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}" {{ (string)(request('type_id') ?? request('tech_intervention_type_id')) === (string)$type->id ? 'selected' : '' }}>
                                    {{ $type->translated_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filter_metier_id" class="form-label">{{ __('locale.lbl_metier') }}</label>
                        <select name="metier_id" id="filter_metier_id" class="form-select select2" data-placeholder="{{ __('locale.lbl_all_metiers') ?? 'Tous les métiers' }}">
                            <option value="">{{ __('locale.lbl_all_metiers') ?? 'Tous les métiers' }}</option>
                            @foreach ($metiers as $metier)
                                <option value="{{ $metier->id }}" {{ (string)(request('metier_id') ?? request('tech_intervention_metier_id')) === (string)$metier->id ? 'selected' : '' }}>
                                    {{ $metier->translated_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filter_priority" class="form-label">{{ __('locale.lbl_priority') }}</label>
                        <select name="priority" id="filter_priority" class="form-select select2" data-placeholder="{{ __('locale.lbl_priority') }}">
                            <option value="">{{ __('locale.lbl_priority') }}</option>
                            <option value="Normal" {{ request('priority') === 'Normal' ? 'selected' : '' }}>{{ __('locale.criticity_normal') }}</option>
                            <option value="Important" {{ request('priority') === 'Important' ? 'selected' : '' }}>{{ __('locale.criticity_important') }}</option>
                            <option value="Urgent" {{ request('priority') === 'Urgent' ? 'selected' : '' }}>{{ __('locale.criticity_urgent') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filter_severity_id" class="form-label">{{ __('locale.lbl_severity') }}</label>
                        <select name="severity_id" id="filter_severity_id" class="form-select select2" data-placeholder="{{ __('locale.lbl_severity') }}">
                            <option value="">{{ __('locale.lbl_severity') }}</option>
                            @foreach ($severities as $severity)
                                <option value="{{ $severity->id }}" {{ (string)(request('severity_id') ?? request('tech_intervention_severity_id')) === (string)$severity->id ? 'selected' : '' }}>
                                    {{ $severity->translated_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filter_contract_status" class="form-label">{{ __('locale.lbl_contract_status') }}</label>
                        <select name="contract_status" id="filter_contract_status" class="form-select select2" data-placeholder="{{ __('locale.lbl_contract_status') }}">
                            <option value="">{{ __('locale.lbl_contract_status') }}</option>
                            <option value="avec_contrat" {{ request('contract_status') === 'avec_contrat' ? 'selected' : '' }}>{{ __('locale.contract_status.avec_contrat') }}</option>
                            <option value="sans_contrat" {{ request('contract_status') === 'sans_contrat' ? 'selected' : '' }}>{{ __('locale.contract_status.sans_contrat') }}</option>
                            <option value="contrat_expire" {{ request('contract_status') === 'contrat_expire' ? 'selected' : '' }}>{{ __('locale.contract_status.contrat_expire') }}</option>
                            <option value="contrat_resilie" {{ request('contract_status') === 'contrat_resilie' ? 'selected' : '' }}>{{ __('locale.contract_status.contrat_resilie') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filter_start_date" class="form-label">{{ __('locale.start_date') ?? 'Date début' }}</label>
                        <input type="date" name="start_date" id="filter_start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="filter_end_date" class="form-label">{{ __('locale.end_date') ?? 'Date fin' }}</label>
                        <input type="date" name="end_date" id="filter_end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                </div>
                <div class="mt-3 text-end">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> {{ __('locale.btn_apply_filters') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Section -->
    <div class="table-responsive">
        <table class="module-table table-custom">
            <thead>
                <tr>
                    <th style="width: 36px;" class="text-center">
                        <input type="checkbox" id="checkAll" class="form-check-input" aria-label="Tout sélectionner">
                    </th>
                    <th style="width: 36px;" class="text-center">
                        <span class="badge badge-matte-secondary" title="{{ __('locale.lbl_contract_status') }}"><i class="bi bi-shield-check"></i></span>
                    </th>
                    <th style="width: 120px;">{{ __('locale.lbl_status') }}</th>
                    <th style="width: 120px;">{{ __('locale.lbl_state') }}</th>
                    <th style="width: 80px;">
                        <a href="{{ route('tech_intervs.index', array_merge(request()->query(), ['sort' => 'id', 'direction' => request('sort') === 'id' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                            {{ __('locale.lbl_id') }}
                            @if (request('sort') === 'id')
                                <i class="fa-solid fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                            @else
                                <i class="fa-solid fa-sort text-muted opacity-50"></i>
                            @endif
                        </a>
                    </th>
                    <th style="width: 220px;">
                        <a href="{{ route('tech_intervs.index', array_merge(request()->query(), ['sort' => 'client', 'direction' => request('sort') === 'client' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                            {{ __('locale.lbl_client') }}
                            @if (request('sort') === 'client')
                                <i class="fa-solid fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                            @else
                                <i class="fa-solid fa-sort text-muted opacity-50"></i>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('tech_intervs.index', array_merge(request()->query(), ['sort' => 'subject', 'direction' => request('sort') === 'subject' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                            {{ __('locale.lbl_subject') }}
                            @if (request('sort') === 'subject')
                                <i class="fa-solid fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                            @else
                                <i class="fa-solid fa-sort text-muted opacity-50"></i>
                            @endif
                        </a>
                    </th>
                    <th style="width: 160px;">{{ __('locale.lbl_assigned_user') }}</th>
                    <th style="width: 140px;">
                        <a href="{{ route('tech_intervs.index', array_merge(request()->query(), ['sort' => 'created_at', 'direction' => request('sort') === 'created_at' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark d-inline-flex align-items-center gap-1">
                            {{ __('locale.lbl_created_at') }}
                            @if (request('sort') === 'created_at')
                                <i class="fa-solid fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                            @else
                                <i class="fa-solid fa-sort text-muted opacity-50"></i>
                            @endif
                        </a>
                    </th>
                    <th style="width: 100px;" class="text-end pe-4">{{ __('locale.actions') }}</th>
                </tr>
            </thead>
            <tbody id="techIntervRows">
                @forelse ($interventions as $intervention)
                    @php
                        $defaultPayment = $intervention->client->default_payment ?? false;
                        $concurrent = $intervention->client?->concurrent ?? false;
                        $clientTips = [];
                        if ($concurrent) { $clientTips[] = __('locale.lbl_concurrent'); }
                        if ($defaultPayment) { $clientTips[] = __('locale.default_payment'); }
                        $clientTooltip = implode('<br>', $clientTips);

                        $statusObj = $intervention->status;
                        $stateObj = $intervention->state;
                    @endphp
                    <tr class="row-clickable" data-href="{{ route('tech_intervs.show', $intervention->id) }}">
                        <td class="text-center" onclick="event.stopPropagation();">
                            <input type="checkbox" class="form-check-input" value="{{ $intervention->id }}" aria-label="Sélectionner">
                        </td>
                        <td class="text-center" onclick="event.stopPropagation();">
                            @if ($intervention->client && $intervention->client->contracts->isNotEmpty())
                                <div class="d-flex gap-1 justify-content-center">
                                    @foreach ($intervention->client->contracts->unique('contract_type_id') as $contract)
                                        @php
                                            $cStatusValue = $contract->calculateStatus();
                                            $cStatusClass = match ($cStatusValue) {
                                                'avec_contrat' => 'badge-matte-success',
                                                'sans_contrat' => 'badge-matte-secondary',
                                                'contrat_expire' => 'badge-matte-warning',
                                                'contrat_resilie' => 'badge-matte-danger',
                                                default => 'badge-matte-secondary',
                                            };
                                            $cTypeName = $contract->type?->translated_name ?? __('locale.unknown_type');
                                            $cStatusText = __('locale.contract_status.' . $cStatusValue);
                                        @endphp
                                        <span class="badge {{ $cStatusClass }} p-0" 
                                            style="width: 10px; height: 10px; display: inline-block; cursor: help; border-radius: 50%;"
                                            data-bs-toggle="tooltip" data-bs-html="true" 
                                            title="{{ $cTypeName }} ({{ $cStatusText }})">
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="badge badge-matte-secondary p-0" style="width: 10px; height: 10px; display: inline-block; border-radius: 50%; opacity: 0.4;"></span>
                            @endif
                        </td>
                        <td>
                            @if($statusObj)
                                <span class="badge" style="background-color: {{ hexToRgba($statusObj->color ?? '#6c757d', 0.15) }}; color: {{ $statusObj->color ?? '#6c757d' }}; font-weight: 600;">
                                    <span style="background-color: {{ $statusObj->color ?? '#6c757d' }}; width: 6px; height: 6px; border-radius: 50%; display: inline-block; margin-right: 4px;"></span>
                                    {{ $statusObj->translated_name }}
                                </span>
                            @else
                                <span class="badge bg-light text-dark border">—</span>
                            @endif
                        </td>
                        <td>
                            @if($stateObj)
                                <span class="badge" style="background-color: {{ hexToRgba($stateObj->color ?? '#6c757d', 0.15) }}; color: {{ $stateObj->color ?? '#6c757d' }}; font-weight: 600;">
                                    <span style="background-color: {{ $stateObj->color ?? '#6c757d' }}; width: 6px; height: 6px; border-radius: 50%; display: inline-block; margin-right: 4px;"></span>
                                    {{ $stateObj->translated_name }}
                                </span>
                            @else
                                <span class="badge bg-light text-dark border">—</span>
                            @endif
                        </td>
                        <td>
                            <strong class="font-monospace text-primary">#{{ $intervention->id }}</strong>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark text-truncate" style="max-width: 210px;" title="{{ $intervention->client->raison_sociale ?? '' }}">
                                {{ $intervention->client->raison_sociale ?? '—' }}
                                @if($clientTooltip)
                                    <i class="fa-solid fa-triangle-exclamation text-danger ms-1" data-bs-toggle="tooltip" data-bs-html="true" title="{!! $clientTooltip !!}"></i>
                                @endif
                            </div>
                            @if($intervention->client && $intervention->client->ville)
                                <small class="text-muted"><i class="fa-solid fa-location-dot me-1 small"></i>{{ $intervention->client->ville }}</small>
                            @endif
                        </td>
                        <td>
                            <div class="text-dark fw-medium text-truncate" style="max-width: 380px;" title="{{ $intervention->subject }}">
                                {{ $intervention->subject }}
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                @if($intervention->type)
                                    <span class="badge bg-light text-secondary border small">{{ $intervention->type->translated_name }}</span>
                                @endif
                                @if($intervention->priority && $intervention->priority !== 'Normal')
                                    <span class="badge {{ $intervention->priority === 'Urgent' ? 'bg-danger' : 'bg-warning text-dark' }} small">{{ $intervention->priority }}</span>
                                @endif
                                @if($intervention->issues && $intervention->issues->isNotEmpty())
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle small">
                                        <i class="fa-solid fa-ticket me-1"></i>{{ $intervention->issues->count() }} ticket(s)
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($intervention->assignedUsers && $intervention->assignedUsers->isNotEmpty())
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($intervention->assignedUsers->take(2) as $usr)
                                        <span class="badge bg-light text-dark border small" title="{{ $usr->name }}">{{ $usr->name }}</span>
                                    @endforeach
                                    @if($intervention->assignedUsers->count() > 2)
                                        <span class="badge bg-secondary small">+{{ $intervention->assignedUsers->count() - 2 }}</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="small text-muted" title="{{ $intervention->created_at }}">
                                {{ format_datetime($intervention->created_at, 'short', 'none') }}
                            </span>
                        </td>
                        <td class="text-end pe-3" onclick="event.stopPropagation();">
                            <div class="d-flex justify-content-end gap-1">
                                <button type="button" class="btn btn-sm btn-icon btn-outline-secondary edit-tech-interv-btn" data-id="{{ $intervention->id }}" title="{{ __('locale.btn_edit') }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <a href="{{ route('tech_intervs.pdf', $intervention->id) }}" target="_blank" class="btn btn-sm btn-icon btn-outline-secondary" title="{{ __('locale.pdf_export') ?? 'Exporter PDF' }}">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </a>
                                <a href="{{ route('tech_intervs.show', $intervention->id) }}" class="btn btn-sm btn-icon btn-outline-primary" title="{{ __('locale.show') }}">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-5">
                            <div class="text-muted">
                                <i class="fa-solid fa-screwdriver-wrench fs-2 mb-2 d-block opacity-25"></i>
                                {{ __('locale.no_records_found') ?? 'Aucune intervention technique trouvée' }}
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Bottom Pagination -->
    <div class="p-3 border-top d-flex justify-content-between align-items-center">
        <div class="small text-muted">
            Affichage de {{ $interventions->firstItem() ?? 0 }} à {{ $interventions->lastItem() ?? 0 }} sur {{ $interventions->total() }} résultats
        </div>
        <div>
            {{ $interventions->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal for Create / Edit Technical Intervention -->
<div class="modal fade" id="techIntervModal" tabindex="-1" aria-labelledby="techIntervModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0 rounded-3" id="tech-interv-modal-content">
            <!-- Loaded dynamically via AJAX -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function initSelect2(parentEl) {
        const root = parentEl ? (parentEl instanceof HTMLElement ? $(parentEl) : $(parentEl)) : $(document);
        root.find('.select2').each(function() {
            const $this = $(this);
            if ($this.data('select2')) {
                $this.select2('destroy');
            }
            $this.select2({
                theme: 'bootstrap-5',
                dropdownParent: $this.closest('.modal-body').length ? $this.closest('.modal-body') : $(document.body),
                width: '100%'
            });
        });
    }

    function initClientContactLoader(parentEl) {
        const clientInput = parentEl.querySelector('#client_id');
        const contactSelect = parentEl.querySelector('#client_contact_id');
        if (!clientInput || !contactSelect) return;

        const loadContacts = (clientId, selectedContactId = null) => {
            if (!clientId) {
                contactSelect.innerHTML = '<option value="">{{ __("locale.select_contact") }}</option>';
                return;
            }
            fetch(`/clients/${clientId}/contacts`)
                .then(res => res.json())
                .then(contacts => {
                    contactSelect.innerHTML = '<option value="">{{ __("locale.select_contact") }}</option>';
                    contacts.forEach(c => {
                        const opt = document.createElement('option');
                        opt.value = c.id;
                        opt.textContent = `${c.prenom || ''} ${c.nom || ''}`.trim();
                        if (selectedContactId && String(selectedContactId) === String(c.id)) {
                            opt.selected = true;
                        }
                        contactSelect.appendChild(opt);
                    });
                    if ($(contactSelect).data('select2')) {
                        $(contactSelect).trigger('change');
                    }
                })
                .catch(() => {});
        };

        // Event listener for client change
        parentEl.addEventListener('client-selected', function(e) {
            loadContacts(e.detail.clientId);
        });
    }

    function initInterventionClientContactLoader(parentEl) {
        const intervClientInput = parentEl.querySelector('#intervention_client_id');
        const intervContactSelect = parentEl.querySelector('#intervention_client_contact_id');
        if (!intervClientInput || !intervContactSelect) return;

        const loadIntervContacts = (clientId, selectedContactId = null) => {
            if (!clientId) {
                intervContactSelect.innerHTML = '<option value="">{{ __("locale.select_contact") }}</option>';
                return;
            }
            fetch(`/clients/${clientId}/contacts`)
                .then(res => res.json())
                .then(contacts => {
                    intervContactSelect.innerHTML = '<option value="">{{ __("locale.select_contact") }}</option>';
                    contacts.forEach(c => {
                        const opt = document.createElement('option');
                        opt.value = c.id;
                        opt.textContent = `${c.prenom || ''} ${c.nom || ''}`.trim();
                        if (selectedContactId && String(selectedContactId) === String(c.id)) {
                            opt.selected = true;
                        }
                        intervContactSelect.appendChild(opt);
                    });
                    if ($(intervContactSelect).data('select2')) {
                        $(intervContactSelect).trigger('change');
                    }
                })
                .catch(() => {});
        };

        parentEl.addEventListener('intervention-client-selected', function(e) {
            loadIntervContacts(e.detail.clientId);
        });
    }

    function initTechIntervAutocomplete(parentEl) {
        const translations = {
            concurrent: @json(__('locale.lbl_concurrent')),
            default_payment: @json(__('locale.default_payment')),
            contract_status: {
                avec_contrat: @json(__('locale.contract_status.avec_contrat')),
                sans_contrat: @json(__('locale.contract_status.sans_contrat')),
                contrat_expire: @json(__('locale.contract_status.contrat_expire')),
                contrat_resilie: @json(__('locale.contract_status.contrat_resilie'))
            }
        };

        const contractStatusClasses = {
            avec_contrat: 'badge-matte-success',
            sans_contrat: 'badge-matte-secondary',
            contrat_expire: 'badge-matte-warning',
            contrat_resilie: 'badge-matte-danger'
        };

        // 1. Client Search
        const clientSearch = parentEl.querySelector('#client_search');
        const clientHidden = parentEl.querySelector('#client_id');
        const clientSuggestions = parentEl.querySelector('#client_suggestions');
        const clientAddress = parentEl.querySelector('#client_address');
        const clientWarning = parentEl.querySelector('#client_warning');

        if (clientSearch && clientSuggestions && clientHidden) {
            clientSearch.addEventListener('input', function() {
                const query = this.value.trim();
                if (query.length < 2) {
                    clientSuggestions.style.display = 'none';
                    clientSuggestions.innerHTML = '';
                    return;
                }

                fetch(`{{ route('clients.search') }}?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        clientSuggestions.innerHTML = '';
                        if (data.length === 0) {
                            clientSuggestions.style.display = 'none';
                            return;
                        }
                        data.forEach(client => {
                            const li = document.createElement('li');
                            li.classList.add('list-group-item', 'list-group-item-action', 'p-2');
                            li.style.cursor = 'pointer';
                            li.dataset.id = client.id;
                            li.dataset.address = [client.adresse_1, client.adresse_2, client.code_postal, client.ville].filter(Boolean).join(' ');
                            li.dataset.concurrent = client.concurrent ? '1' : '';
                            li.dataset.default_payment = client.default_payment ? '1' : '';

                            const parts = [];
                            let nameHtml = `<strong>${client.raison_sociale}</strong>`;
                            if (client.contract_status) {
                                const badgeClass = contractStatusClasses[client.contract_status] || 'badge-matte-secondary';
                                const badgeLabel = translations.contract_status[client.contract_status] || client.contract_status;
                                nameHtml += ` <span class="badge ${badgeClass}">${badgeLabel}</span>`;
                            }
                            if (client.concurrent || client.default_payment) {
                                const tips = [];
                                if (client.default_payment) tips.push(translations.default_payment);
                                if (client.concurrent) tips.push(translations.concurrent);
                                nameHtml += ` <i class="fa-solid fa-triangle-exclamation text-danger ms-2" data-bs-toggle="tooltip" data-bs-html="true" title="${tips.join('<br>')}"></i>`;
                            }
                            parts.push(nameHtml);
                            if (client.ville) parts.push(`<small class="text-muted d-block">${client.ville}</small>`);
                            li.innerHTML = parts.join('');
                            clientSuggestions.appendChild(li);
                        });
                        clientSuggestions.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
                        clientSuggestions.style.display = 'block';
                    });
            });

            clientSuggestions.addEventListener('click', function(e) {
                const li = e.target.closest('li');
                if (!li) return;
                clientHidden.value = li.dataset.id;
                clientSearch.value = li.querySelector('strong') ? li.querySelector('strong').textContent.trim() : li.textContent.trim();
                if (clientAddress) clientAddress.textContent = li.dataset.address;

                const tips = [];
                if (li.dataset.concurrent) tips.push(translations.concurrent);
                if (li.dataset.default_payment) tips.push(translations.default_payment);
                if (clientWarning) {
                    if (tips.length) {
                        clientWarning.classList.remove('d-none');
                        clientWarning.setAttribute('title', tips.join('\n'));
                        const existing = bootstrap.Tooltip.getInstance(clientWarning);
                        if (existing) existing.dispose();
                        new bootstrap.Tooltip(clientWarning);
                    } else {
                        clientWarning.classList.add('d-none');
                        clientWarning.removeAttribute('title');
                    }
                }

                clientSuggestions.style.display = 'none';
                clientSuggestions.innerHTML = '';

                parentEl.dispatchEvent(new CustomEvent('client-selected', { detail: { clientId: li.dataset.id } }));
            });
        }

        // 2. Intervention Client Search
        const intervClientSearch = parentEl.querySelector('#intervention_client_search');
        const intervClientHidden = parentEl.querySelector('#intervention_client_id');
        const intervClientSuggestions = parentEl.querySelector('#intervention_client_suggestions');
        const intervClientAddress = parentEl.querySelector('#intervention_client_address');
        const intervClientWarning = parentEl.querySelector('#intervention_client_warning');

        if (intervClientSearch && intervClientSuggestions && intervClientHidden) {
            intervClientSearch.addEventListener('input', function() {
                const query = this.value.trim();
                if (query.length < 2) {
                    intervClientSuggestions.style.display = 'none';
                    intervClientSuggestions.innerHTML = '';
                    return;
                }

                fetch(`{{ route('clients.search') }}?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        intervClientSuggestions.innerHTML = '';
                        if (data.length === 0) {
                            intervClientSuggestions.style.display = 'none';
                            return;
                        }
                        data.forEach(client => {
                            const li = document.createElement('li');
                            li.classList.add('list-group-item', 'list-group-item-action', 'p-2');
                            li.style.cursor = 'pointer';
                            li.dataset.id = client.id;
                            li.dataset.address = [client.adresse_1, client.adresse_2, client.code_postal, client.ville].filter(Boolean).join(' ');
                            li.dataset.concurrent = client.concurrent ? '1' : '';
                            li.dataset.default_payment = client.default_payment ? '1' : '';

                            const parts = [];
                            let nameHtml = `<strong>${client.raison_sociale}</strong>`;
                            if (client.contract_status) {
                                const badgeClass = contractStatusClasses[client.contract_status] || 'badge-matte-secondary';
                                const badgeLabel = translations.contract_status[client.contract_status] || client.contract_status;
                                nameHtml += ` <span class="badge ${badgeClass}">${badgeLabel}</span>`;
                            }
                            parts.push(nameHtml);
                            if (client.ville) parts.push(`<small class="text-muted d-block">${client.ville}</small>`);
                            li.innerHTML = parts.join('');
                            intervClientSuggestions.appendChild(li);
                        });
                        intervClientSuggestions.style.display = 'block';
                    });
            });

            intervClientSuggestions.addEventListener('click', function(e) {
                const li = e.target.closest('li');
                if (!li) return;
                intervClientHidden.value = li.dataset.id;
                intervClientSearch.value = li.querySelector('strong') ? li.querySelector('strong').textContent.trim() : li.textContent.trim();
                if (intervClientAddress) intervClientAddress.textContent = li.dataset.address;

                intervClientSuggestions.style.display = 'none';
                intervClientSuggestions.innerHTML = '';

                parentEl.dispatchEvent(new CustomEvent('intervention-client-selected', { detail: { clientId: li.dataset.id } }));
            });
        }

        // Close on outside click
        document.addEventListener('click', function(e) {
            if (clientSearch && clientSuggestions && !clientSearch.contains(e.target) && !clientSuggestions.contains(e.target)) {
                clientSuggestions.style.display = 'none';
                clientSuggestions.innerHTML = '';
            }
            if (intervClientSearch && intervClientSuggestions && !intervClientSearch.contains(e.target) && !intervClientSuggestions.contains(e.target)) {
                intervClientSuggestions.style.display = 'none';
                intervClientSuggestions.innerHTML = '';
            }
        });
    }

    function initQuill(parentEl) {
        const quillEl = parentEl.querySelector('#tech-quill-description');
        if (!quillEl) return;
        const quill = new Quill(quillEl, {
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    ['clean']
                ],
            },
            theme: 'snow'
        });

        const form = parentEl.querySelector('#tech_interv_form') || parentEl.querySelector('form');
        form?.addEventListener('submit', function() {
            const hidden = parentEl.querySelector('#tech_interv_description');
            if (hidden) hidden.value = quill.root.innerHTML;
        });
    }

    window.initTechIntervForm = function(parentEl) {
        initSelect2(parentEl);
        initTechIntervAutocomplete(parentEl);
        initClientContactLoader(parentEl);
        initInterventionClientContactLoader(parentEl);
        initQuill(parentEl);
        parentEl.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
    };

    document.addEventListener('DOMContentLoaded', function() {
        initSelect2('#advancedFilters');

        const advancedFilters = document.getElementById('advancedFilters');
        if (advancedFilters) {
            advancedFilters.addEventListener('shown.bs.collapse', function () {
                initSelect2(advancedFilters);
            });
        }

        // Row clickable navigation
        document.querySelectorAll('.row-clickable').forEach(row => {
            row.addEventListener('click', function() {
                const url = this.getAttribute('data-href');
                if (url) window.location.href = url;
            });
        });

        // Modal for create and edit
        const modalEl = document.getElementById('techIntervModal');
        let techModal = null;
        if (modalEl) {
            techModal = new bootstrap.Modal(modalEl);

            document.querySelectorAll('.create-tech-interv-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    fetch("{{ route('tech_intervs.create-modal') }}")
                        .then(resp => resp.text())
                        .then(html => {
                            document.getElementById('tech-interv-modal-content').innerHTML = html;
                            techModal.show();
                            window.initTechIntervForm(modalEl);
                        });
                });
            });

            document.querySelectorAll('.edit-tech-interv-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const id = this.getAttribute('data-id');
                    fetch(`/tech_intervs/${id}/edit/modal`)
                        .then(resp => resp.text())
                        .then(html => {
                            document.getElementById('tech-interv-modal-content').innerHTML = html;
                            techModal.show();
                            window.initTechIntervForm(modalEl);
                        });
                });
            });
        }

        const checkAll = document.getElementById('checkAll');
        if (checkAll) {
            checkAll.addEventListener('change', function(e) {
                document.querySelectorAll('#techIntervRows input[type="checkbox"]').forEach(cb => {
                    cb.checked = e.target.checked;
                });
            });
        }

        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
    });
</script>
@endpush
