@extends('layouts.app')

@section('title', __('locale.menu_tickets')) 

@section('navbar-icon')
    <i class="fa-solid fa-ticket title-icon me-2"></i>
@endsection

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ $pageTitle ?? __('locale.issues_list_title') }}</h1>
    </div>
@endsection

@section('navbar-subtitle', 'Gérez et suivez l\'ensemble de vos demandes')

@section('navbar-search')
    <form method="GET" action="{{ route('issues.index') }}" class="w-100" id="searchForm">
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
            || request()->filled('origine_id')
            || request()->filled('category_id')
            || request()->filled('issue_type_id')
            || request()->filled('type')
            || request()->filled('issue_criticity_internal_id')
            || request()->filled('criticity_internal')
            || request()->filled('issue_criticity_client_id')
            || request()->filled('criticity_client')
            || request()->filled('issue_severity_id')
            || request()->filled('severity')
            || request()->filled('department')
            || request()->filled('contract_status')
            || request()->filled('status_id')
            || request()->filled('state_id')
            || request()->filled('user_id')
            || request()->filled('division_zone_id')
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
        @if (Auth::user()->is_admin || Auth::user()->hasPermission('open-ticket'))
            <button class="btn primary create-main create-issue-btn" id="createTicketBtn" type="button">
                <i class="fa-solid fa-plus"></i> {{ __('locale.create') ?? 'Créer' }}
            </button>
            <button class="btn primary create-toggle dropdown-toggle dropdown-toggle-split" id="createMenuToggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Ouvrir le menu de création">
                <i class="fa-solid fa-chevron-down"></i>
            </button>

            <div class="create-dropdown dropdown-menu dropdown-menu-end shadow border-0" id="createDropdown" role="menu">
                <button class="create-option create-issue-btn" type="button" role="menuitem">
                    <i class="fa-solid fa-ticket"></i>
                    <span>
                        <strong>{{ __('locale.btn_create_issue') ?? 'Créer un ticket' }}</strong>
                        <small>Créer une nouvelle demande de support.</small>
                    </span>
                </button>

                @if (Auth::user()->hasPermission('menu-interventions') || Auth::user()->is_admin == 1)
                <a class="create-option" href="{{ route('tech_intervs.create') }}" role="menuitem">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                    <span>
                        <strong>Créer une intervention</strong>
                        <small>Planifier ou enregistrer une intervention technique.</small>
                    </span>
                </a>
                @endif

                @if (Auth::user()->hasPermission('technical-visite-prev-access') || Auth::user()->is_admin == 1)
                <a class="create-option" href="{{ route('visite-prev.create') }}" role="menuitem">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>
                        <strong>Créer une demande d'intervention</strong>
                        <small>Soumettre une demande qui devra être qualifiée puis planifiée.</small>
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
                $isMine = (string)request('user_id') === (string)Auth::id();
                $isPriority = request('contract_status') === 'avec_contrat' && (request('criticity_internal') === 'high' || request('severity') === 'high');
                $isAll = !$isMine && !$isPriority && !request()->filled('status_id') && !request()->filled('new_messages');
                $isNewMessages = request('new_messages') == 1;
            @endphp
            <a href="{{ route('issues.index') }}" class="tab {{ $isAll ? 'active' : '' }}">
                Tous les tickets <span class="count">{{ $issues->total() }}</span>
            </a>
            <a href="{{ route('issues.index', ['user_id' => Auth::id()]) }}" class="tab {{ $isMine ? 'active' : '' }}">
                Mes tickets
            </a>
            <a href="{{ route('issues.index', ['contract_status' => 'avec_contrat', 'criticity_internal' => 'high']) }}" class="tab priority {{ $isPriority ? 'active' : '' }}">
                Prioritaire (En panne &amp; sous contrat)
            </a>
            @if(isset($statuses) && $statuses->isNotEmpty())
                @foreach($statuses->take(3) as $st)
                    @php
                        $isStatusTab = (string)request('status_id') === (string)$st->id;
                    @endphp
                    <a href="{{ route('issues.index', ['status_id' => $st->id]) }}" class="tab {{ $isStatusTab ? 'active' : '' }}">
                        <span style="background-color: {{ $st->color }}; width: 8px; height: 8px; border-radius: 50%; display: inline-block;"></span>
                        {{ $st->translated_name }}
                    </a>
                @endforeach
            @endif
        </div>

        <div class="toolbar-right">
            <div class="pager">
                <span id="pagerLabel" class="pager-label">
                    {{ $issues->firstItem() ?? 0 }}-{{ $issues->lastItem() ?? 0 }} / {{ $issues->total() }}
                </span>
                @if ($issues->onFirstPage())
                    <span class="pager-btn" style="opacity: 0.45; cursor: not-allowed;"><i class="fa-solid fa-chevron-left"></i></span>
                @else
                    <a href="{{ $issues->previousPageUrl() }}" class="pager-btn" aria-label="Page précédente"><i class="fa-solid fa-chevron-left"></i></a>
                @endif

                @if ($issues->hasMorePages())
                    <a href="{{ $issues->nextPageUrl() }}" class="pager-btn" aria-label="Page suivante"><i class="fa-solid fa-chevron-right"></i></a>
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
                    <a href="{{ route('issues.index', request('search') ? ['search' => request('search')] : []) }}" class="btn btn-sm btn-outline-danger">
                        <i class="fa-solid fa-rotate-left me-1"></i>{{ __('locale.lbl_reset_filters') }}
                    </a>
                @endif
            </div>
            <form method="GET" action="{{ route('issues.index') }}">
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
                        <label for="group_id" class="form-label">{{ __('locale.lbl_group') }}</label>
                        <select name="group_id" id="group_id" class="form-select select2" data-placeholder="{{ __('locale.lbl_all_groups') }}">
                            <option value="">{{ __('locale.lbl_all_groups') }}</option>
                            @foreach ($groups as $group)
                                <option value="{{ $group->id }}" {{ (string)request('group_id') === (string)$group->id ? 'selected' : '' }}> {{ $group->raison_sociale }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="client_id" class="form-label">{{ __('locale.lbl_client') }}</label>
                        <select name="client_id" id="client_id_filter" class="form-select select2" data-placeholder="{{ __('locale.lbl_all_clients') }}">
                            <option value="">{{ __('locale.lbl_all_clients') }}</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}" {{ (string)request('client_id') === (string)$client->id ? 'selected' : '' }}> {{ $client->raison_sociale }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="origine_id" class="form-label">{{ __('locale.lbl_origine') }}</label>
                        <select name="origine_id" id="origine_id" class="form-select select2" data-placeholder="{{ __('locale.lbl_all_origines') }}">
                            <option value="">{{ __('locale.lbl_all_origines') }}</option>
                            @foreach ($origines as $origine)
                                <option value="{{ $origine->id }}" {{ (string)request('origine_id') === (string)$origine->id ? 'selected' : '' }}> {{ $origine->translated_name }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="category_id" class="form-label">{{ __('locale.category') }}</label>
                        <select name="category_id" id="category_id" class="form-select select2" data-placeholder="{{ __('locale.category') }}">
                            <option value="">{{ __('locale.category') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ (string)request('category_id') === (string)$category->id ? 'selected' : '' }}> {{ $category->translated_name ?? $category->name }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="issue_type_id" class="form-label">{{ __('locale.lbl_type') }}</label>
                        <select name="issue_type_id" id="issue_type_id" class="form-select select2" data-placeholder="{{ __('locale.lbl_all_types') }}">
                            <option value="">{{ __('locale.lbl_all_types') }}</option>
                            @foreach ($issueTypes as $value => $type)
                                <option value="{{ $value }}" {{ (string)($filters['type'] ?? null) === (string)$value ? 'selected' : '' }}> {{ $type['label'] ?? ($type['name'] ?? '') }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="issue_criticity_internal_id" class="form-label">{{ __('locale.lbl_criticity_internal') }}</label>
                        <select name="issue_criticity_internal_id" id="issue_criticity_internal_id" class="form-select select2" data-placeholder="{{ __('locale.lbl_all_criticity') }}">
                            <option value="">{{ __('locale.lbl_all_criticity') }}</option>
                            @foreach ($issueCriticities as $value => $criticity)
                                <option value="{{ $value }}" {{ (string)($filters['criticity_internal'] ?? null) === (string)$value ? 'selected' : '' }}> {{ $criticity['label'] ?? ($criticity['name'] ?? '') }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="issue_severity_id" class="form-label">{{ __('locale.lbl_severity') }}</label>
                        <select name="issue_severity_id" id="issue_severity_id" class="form-select select2" data-placeholder="{{ __('locale.lbl_severity') }}">
                            <option value="">{{ __('locale.lbl_severity') }}</option>
                            @foreach ($issueSeverities as $value => $severity)
                                <option value="{{ $value }}" {{ (string)($filters['severity'] ?? null) === (string)$value ? 'selected' : '' }}> {{ $severity['label'] ?? ($severity['name'] ?? '') }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="status_id" class="form-label">{{ __('locale.lbl_status') }}</label>
                        <select name="status_id" id="status_id" class="form-select select2" data-placeholder="{{ __('locale.all_statuses') }}">
                            <option value="">{{ __('locale.all_statuses') }}</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status->id }}" {{ (string)request('status_id') === (string)$status->id ? 'selected' : '' }}> {{ $status->translated_name }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="state_id" class="form-label">{{ __('locale.lbl_state') }}</label>
                        <select name="state_id" id="state_id" class="form-select select2" data-placeholder="{{ __('locale.all_states') }}">
                            <option value="">{{ __('locale.all_states') }}</option>
                            @foreach ($states as $state)
                                <option value="{{ $state->id }}" {{ (string)request('state_id') === (string)$state->id ? 'selected' : '' }}> {{ $state->translated_name }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="user_id" class="form-label">{{ __('locale.lbl_assigned_user') }}</label>
                        <select name="user_id" id="user_id" class="form-select select2" data-placeholder="{{ __('locale.menu_users') }}">
                            <option value="">{{ __('locale.menu_users') }}</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ (string)request('user_id') === (string)$user->id ? 'selected' : '' }}> {{ $user->name }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="contract_status" class="form-label">{{ __('locale.lbl_contract_status') }}</label>
                        <select name="contract_status" id="contract_status" class="form-select select2" data-placeholder="{{ __('locale.all_contract_statuses') }}">
                            <option value="">{{ __('locale.all_contract_statuses') }}</option>
                            @foreach (App\Enums\ContractStatus::cases() as $cStatus)
                                <option value="{{ $cStatus->value }}" {{ request('contract_status') == $cStatus->value ? 'selected' : '' }}> {{ __('locale.contract_status.' . $cStatus->value) }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">{{ __('locale.lbl_start_date') }}</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-12 d-flex justify-content-end gap-2 mt-2">
                        @if($hasActiveFilters)
                            <a href="{{ route('issues.index', request('search') ? ['search' => request('search')] : []) }}" class="btn btn-outline-secondary">
                                {{ __('locale.lbl_reset_filters') }}
                            </a>
                        @endif
                        <button type="submit" class="btn primary">
                            <i class="fa-solid fa-filter me-1"></i>{{ __('locale.btn_apply_filters') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    @if (count($issues) > 0)
        @php $typeIcons = $issueTypeDisplay; @endphp
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th class="check-col"><input id="checkAll" type="checkbox" aria-label="Tout sélectionner"></th>

                        <th class="ticket-col">
                            <a href="{{ route('issues.index', array_merge(request()->query(), ['sort' => 'id', 'direction' => request('sort') === 'id' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="sortable {{ request('sort') === 'id' ? 'sorted' : '' }}">
                                {{ __('locale.lbl_id') ?? 'N° ticket' }}
                                <span class="sort-icons">
                                    <i class="fa-solid fa-caret-up {{ request('sort') === 'id' && request('direction') === 'asc' ? 'text-primary' : '' }}"></i>
                                    <i class="fa-solid fa-caret-down {{ request('sort') === 'id' && request('direction') === 'desc' ? 'text-primary' : '' }}"></i>
                                </span>
                            </a>
                        </th>

                        <th class="title-col">
                            <a href="{{ route('issues.index', array_merge(request()->query(), ['sort' => 'subject', 'direction' => request('sort') === 'subject' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="sortable {{ request('sort') === 'subject' ? 'sorted' : '' }}">
                                {{ __('locale.lbl_subject') ?? 'Titre' }}
                                <span class="sort-icons">
                                    <i class="fa-solid fa-caret-up {{ request('sort') === 'subject' && request('direction') === 'asc' ? 'text-primary' : '' }}"></i>
                                    <i class="fa-solid fa-caret-down {{ request('sort') === 'subject' && request('direction') === 'desc' ? 'text-primary' : '' }}"></i>
                                </span>
                            </a>
                        </th>

                        <th class="client-col">
                            <a href="{{ route('issues.index', array_merge(request()->query(), ['sort' => 'client', 'direction' => request('sort') === 'client' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="sortable {{ request('sort') === 'client' ? 'sorted' : '' }}">
                                {{ __('locale.lbl_client') ?? 'Client' }}
                                <span class="sort-icons">
                                    <i class="fa-solid fa-caret-up {{ request('sort') === 'client' && request('direction') === 'asc' ? 'text-primary' : '' }}"></i>
                                    <i class="fa-solid fa-caret-down {{ request('sort') === 'client' && request('direction') === 'desc' ? 'text-primary' : '' }}"></i>
                                </span>
                            </a>
                        </th>

                        <th class="contract-col">{{ __('locale.lbl_contract_status') ?? 'Contrat' }}</th>

                        <th class="assignee-col">{{ __('locale.lbl_assigned_user') ?? 'Affecté à' }}</th>

                        <th class="priority-col">{{ __('locale.lbl_priority') ?? 'Priorité' }}</th>

                        <th class="created-col">
                            <a href="{{ route('issues.index', array_merge(request()->query(), ['sort' => 'created_at', 'direction' => request('sort') === 'created_at' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="sortable {{ request('sort') === 'created_at' ? 'sorted' : '' }}">
                                Date &amp; heure<br>de création
                                <span class="sort-icons">
                                    <i class="fa-solid fa-caret-up {{ request('sort') === 'created_at' && request('direction') === 'asc' ? 'text-primary' : '' }}"></i>
                                    <i class="fa-solid fa-caret-down {{ request('sort') === 'created_at' && request('direction') === 'desc' ? 'text-primary' : '' }}"></i>
                                </span>
                            </a>
                        </th>

                        <th class="remaining-col">Temps restant</th>

                        <th class="status-col">{{ __('locale.lbl_status') ?? 'Statut' }}</th>

                        <th style="width: 50px; text-align: center;"></th>
                    </tr>
                </thead>

                <tbody id="ticketRows">
                    @foreach ($issues as $issue)
                        @php
                            $url = route('issues.show', $issue->id);
                            $typeSlug = \App\Support\IssueLookup::typeSlug($issue->type) ?? \Illuminate\Support\Str::slug((string) $issue->type, '_');
                            $canViewInternal = auth()->user()->is_admin || auth()->user()->hasPermission('view-internal-status');
                            $statusEntry = $canViewInternal
                                ? (optional($issue->state)->internalStatus ?: ($issue->latestStatus ?: $issue->status))
                                : (optional($issue->state)->externalStatus ?: ($issue->latestStatus ?: $issue->status));
                            $defaultPayment = $issue->client->default_payment ?? false;
                            $concurrent = $issue->client?->concurrent ?? false;

                            // Contract status determination
                            $client = $issue->client;
                            $contractStatusValue = $client?->contract_status ?? 'sans_contrat';
                            $contractBadgeClass = match ($contractStatusValue) {
                                'avec_contrat' => 'contract on',
                                'contrat_expire' => 'badge-solid-warning',
                                'contrat_resilie' => 'badge-solid-danger',
                                default => 'contract off',
                            };
                            $contractStatusLabel = __('locale.contract_status.' . $contractStatusValue) ?: ($contractStatusValue === 'avec_contrat' ? 'Sous contrat' : 'Sans contrat');
                            
                            $contractsTooltip = '';
                            if ($client && $client->contracts && $client->contracts->isNotEmpty()) {
                                $contractsTooltip = $client->contracts->map(function ($c) {
                                    $stText = __('locale.contract_status.' . $c->calculateStatus());
                                    $typeName = $c->type?->translated_name ?? $c->type?->name ?? '';
                                    $name = $c->contract_name ?: ($typeName ?: '#' . $c->id);
                                    return e($name . ($typeName && $name !== $typeName ? " ({$typeName})" : '') . ": {$stText}");
                                })->implode('<br>');
                            }

                            // Priority calculation
                            $prioSlug = strtolower($issue->issueCriticityInternal?->name ?? ($issue->issueSeverity?->name ?? ''));
                            $prioClass = 'low';
                            $prioText = $issue->issueCriticityInternal?->translated_name ?? ($issue->issueSeverity?->translated_name ?? 'Normale');
                            if (str_contains($prioSlug, 'haut') || str_contains($prioSlug, 'high') || str_contains($prioSlug, 'crit') || str_contains($prioSlug, 'urg')) {
                                $prioClass = 'high';
                            } elseif (str_contains($prioSlug, 'moy') || str_contains($prioSlug, 'med')) {
                                $prioClass = 'medium';
                            }

                            // Status badge class & icon
                            $sName = strtolower($statusEntry?->name ?? '');
                            $sClass = 's-blue';
                            $sIcon = 'fa-circle-info';
                            if (str_contains($sName, 'devis') || str_contains($sName, 'bloqu') || str_contains($sName, 'urgent')) {
                                $sClass = 's-red';
                                $sIcon = 'fa-file-lines';
                            } elseif (str_contains($sName, 'planif') || str_contains($sName, 'site') || str_contains($sName, 'repar') || str_contains($sName, 'interv')) {
                                $sClass = 's-blue';
                                $sIcon = 'fa-screwdriver-wrench';
                            } elseif (str_contains($sName, 'clot') || str_contains($sName, 'resol') || str_contains($sName, 'term') || str_contains($sName, 'close')) {
                                $sClass = 's-green';
                                $sIcon = 'fa-lock';
                            } elseif (str_contains($sName, 'cours') || str_contains($sName, 'migrat')) {
                                $sClass = 's-orange';
                                $sIcon = 'fa-arrow-right';
                            } elseif (str_contains($sName, 'command') || str_contains($sName, 'mater')) {
                                $sClass = 's-purple';
                                $sIcon = 'fa-cube';
                            } elseif (str_contains($sName, 'attente') || str_contains($sName, 'fabric') || str_contains($sName, 'escalad')) {
                                $sClass = 's-brown';
                                $sIcon = 'fa-chart-column';
                            } elseif (str_contains($sName, 'reponse') || str_contains($sName, 'sans suite')) {
                                $sClass = 's-gray';
                                $sIcon = 'fa-comment';
                            }
                        @endphp
                        <tr class="clickable" style="cursor: pointer;" onclick='window.open("{{ $url }}", "_blank")'>
                            <td class="check-col" onclick="event.stopPropagation();">
                                <input type="checkbox" aria-label="Sélectionner {{ $issue->id }}">
                            </td>

                            <td class="ticket-col">
                                <span class="ticket-id">{{ $issue->id }}</span>
                            </td>

                            <td class="title-col">
                                <div class="ticket-title">{{ $issue->subject }}</div>
                            </td>

                            <td class="client-col">
                                <div class="client">
                                    @if ($issue->client)
                                        <a href="{{ route('clients.show', $issue->client->id) }}" onclick="event.stopPropagation();" class="text-dark fw-semibold">
                                            {{ $issue->client->raison_sociale }}
                                        </a>
                                        @if (!empty($issue->client->ville))
                                            <span class="city">{{ $issue->client->ville }}</span>
                                        @endif
                                        @if ($defaultPayment || $concurrent)
                                            <i class="fa-solid fa-triangle-exclamation text-danger ms-1" data-bs-toggle="tooltip" title="{{ $defaultPayment ? 'Défaut de paiement' : 'Concurrent' }}" onclick="event.stopPropagation();"></i>
                                        @endif
                                    @else
                                        <span class="text-muted">{{ __('locale.lbl_no_client') }}</span>
                                    @endif
                                </div>
                            </td>

                            <td class="contract-col">
                                <span class="badge {{ $contractBadgeClass }}" @if($contractsTooltip) data-bs-toggle="tooltip" data-bs-html="true" title="{!! $contractsTooltip !!}" @endif>
                                    <i class="fa-solid fa-shield-halved"></i>{{ $contractStatusLabel }}
                                </span>
                            </td>

                            <td class="assignee-col">
                                @if ($issue->users->isNotEmpty())
                                    @foreach ($issue->users as $u)
                                        @php
                                            $trigram = !empty($u->identifier) ? $u->identifier : ($u->name ? strtoupper(substr(str_replace(' ', '', $u->name), 0, 3)) : '—');
                                        @endphp
                                        <span class="assignee" title="{{ $u->name }}">{{ $trigram }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted" style="font-size: 11.5px;">—</span>
                                @endif
                            </td>

                            <td class="priority-col">
                                <span class="priority {{ $prioClass }}">
                                    <span class="priority-dot"></span>{{ $prioText }}
                                </span>
                            </td>

                            <td class="created-col">
                                <div class="datetime">
                                    <strong>{{ $issue->created_at ? $issue->created_at->format('d/m/Y') : '—' }}</strong>
                                    <span class="text-muted">{{ $issue->created_at ? $issue->created_at->format('H:i') : '' }}</span>
                                </div>
                            </td>

                            <td class="remaining-col">
                                @php
                                    $remMin = null;
                                    if ($issue->sla_deadline && now()->lessThan($issue->sla_deadline)) {
                                        $remMin = now()->diffInMinutes($issue->sla_deadline);
                                    }
                                @endphp
                                @if ($remMin !== null)
                                    @php
                                        $rCls = 'green';
                                        if ($remMin <= 20) $rCls = 'red';
                                        elseif ($remMin <= 60) $rCls = 'orange';
                                        elseif ($remMin <= 120) $rCls = 'yellow';
                                        $rh = floor($remMin / 60);
                                        $rm = $remMin % 60;
                                        $rTxt = $rh > 0 ? "{$rh} h " . sprintf('%02d', $rm) : "{$rm} min";
                                    @endphp
                                    <span class="remaining {{ $rCls }}"><i class="fa-regular fa-clock"></i>{{ $rTxt }}</span>
                                @else
                                    <span class="remaining none">—</span>
                                @endif
                            </td>

                            <td class="status-col">
                                <span class="status {{ $sClass }}">
                                    <i class="fa-solid {{ $sIcon }}"></i>{{ $statusEntry?->translated_name ?? ($statusEntry?->name ?? ($issue->status?->translated_name ?? ($issue->status?->name ?? __('locale.unknown_status')))) }}
                                </span>
                            </td>

                            <td style="text-align: center;" onclick="event.stopPropagation();">
                                @php
                                    $canEdit = Auth::user()->is_admin || Auth::user()->hasPermission('edit-ticket');
                                    $canDelete = Auth::user()->is_admin || Auth::user()->hasPermission('delete-ticket');
                                @endphp
                                @if ($canEdit || $canDelete)
                                    <div class="dropdown">
                                        <button class="btn btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 32px; height: 32px; padding: 0; border: none; background: transparent;">
                                            <i class="fa-solid fa-ellipsis-vertical text-muted"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                            @if ($canEdit)
                                                <li>
                                                    <button type="button" class="dropdown-item edit-issue-btn" data-id="{{ $issue->id }}">
                                                        <i class="fa-solid fa-pen-to-square me-2 text-muted"></i>{{ __('locale.edit') }}
                                                    </button>
                                                </li>
                                            @endif
                                            @if ($canDelete)
                                                <li>
                                                    <form method="POST" action="{{ route('issues.destroy', $issue->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('{{ __('locale.delete_confirmation') }}');">
                                                            <i class="fa-solid fa-trash me-2"></i>{{ __('locale.delete') }}
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="footer-note d-flex justify-content-between align-items-center">
            <span>{{ $issues->perPage() }} tickets par page</span>
            <div>{{ $issues->withQueryString()->links() }}</div>
        </div>
    @else
        <div class="p-5 text-center text-muted">
            <i class="fa-solid fa-ticket fs-1 mb-3 text-muted" style="opacity: 0.5;"></i>
            <p class="mb-0">{{ __('locale.lbl_no_records_found') }}</p>
        </div>
    @endif
</div>

<!-- Modal Creation / Edition -->
<div class="modal fade" id="issueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" id="issue-modal-content"></div>
    </div>
</div>

<div class="toast" id="toast" role="status" aria-live="polite"></div>
@endsection

@push('scripts')
<script>
    function initSelect2(parentEl = '#advancedFilters') {
        const $parent = parentEl instanceof HTMLElement ? $(parentEl) : $(parentEl);

        $parent.find('.select2').each(function () {
            const $element = $(this);

            if ($element.data('select2')) {
                $element.select2('destroy');
            }

            const options = {
                theme: 'bootstrap-5',
                width: '100%',
                allowClear: true,
                placeholder: $element.data('placeholder') || ''
            };

            const $modal = $element.closest('.modal');
            if ($modal.length) {
                options.dropdownParent = $modal;
            }

            $element.select2(options);
        });
    }

    function initIssueAutocomplete(parentEl) {
        const translations = {
            concurrent: "{{ __('locale.lbl_concurrent') }}",
            default_payment: "{{ __('locale.default_payment') }}",
            contract_status: {
                avec_contrat: "{{ __('locale.contract_status.avec_contrat') }}",
                sans_contrat: "{{ __('locale.contract_status.sans_contrat') }}",
                contrat_resilie: "{{ __('locale.contract_status.contrat_resilie') }}",
                contrat_expire: "{{ __('locale.contract_status.contrat_expire') }}",
            }
        };

        const contractStatusClasses = {
            avec_contrat: 'badge-solid-success',
            sans_contrat: 'badge-solid-secondary',
            contrat_resilie: 'badge-solid-danger',
            contrat_expire: 'badge-solid-warning',
        };

        function setupClientAutocomplete() {
            const searchInput = parentEl.querySelector('#client_search');
            const suggestions = parentEl.querySelector('#client_suggestions');
            const hiddenInput = parentEl.querySelector('#client_id');
            const addressEl = parentEl.querySelector('#client_address');
            const warningIcon = parentEl.querySelector('#client_warning');

            if (!searchInput) return;

            searchInput.addEventListener('input', function() {
                const query = this.value;
                hiddenInput.value = '';
                addressEl.textContent = '';
                warningIcon.classList.add('d-none');
                warningIcon.removeAttribute('title');
                if (!query) {
                    suggestions.style.display = 'none';
                    suggestions.innerHTML = '';
                    return;
                }

                fetch(`{{ route('clients.search') }}?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        suggestions.innerHTML = '';
                        if (data.length === 0) {
                            suggestions.style.display = 'none';
                            return;
                        }
                        data.forEach(client => {
                            const li = document.createElement('li');
                            li.classList.add('list-group-item');
                            li.dataset.id = client.id;
                            li.dataset.address = [client.adresse_1, client.adresse_2, client.code_postal, client.ville].filter(Boolean).join(' ');
                            li.dataset.concurrent = client.concurrent ? '1' : '';
                            li.dataset.default_payment = client.default_payment ? '1' : '';

                            const parts = [];
                            let nameHtml = client.raison_sociale;
                            if (client.contract_status) {
                                const badgeClass = contractStatusClasses[client.contract_status] || 'badge-solid-secondary';
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
                            if (client.ville) parts.push(`<small>${client.ville}</small>`);
                            li.innerHTML = parts.join('<br>');
                            suggestions.appendChild(li);
                        });
                        suggestions.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
                        suggestions.style.display = 'block';
                    });
            });

            suggestions.addEventListener('click', function(e) {
                const li = e.target.closest('li');
                if (!li) return;
                hiddenInput.value = li.dataset.id;
                searchInput.value = li.firstChild.textContent.trim();
                addressEl.textContent = li.dataset.address;

                const tips = [];
                if (li.dataset.concurrent) tips.push(translations.concurrent);
                if (li.dataset.default_payment) tips.push(translations.default_payment);
                if (tips.length) {
                    warningIcon.classList.remove('d-none');
                    warningIcon.setAttribute('title', tips.join('\n'));
                    const existing = bootstrap.Tooltip.getInstance(warningIcon);
                    if (existing) existing.dispose();
                    new bootstrap.Tooltip(warningIcon);
                } else {
                    warningIcon.classList.add('d-none');
                    warningIcon.removeAttribute('title');
                }

                suggestions.style.display = 'none';
                suggestions.innerHTML = '';
            });

            document.addEventListener('click', function(e) {
                if (
                    !searchInput.contains(e.target) &&
                    !suggestions.contains(e.target) &&
                    !e.target.closest('.select2-container')
                ) {
                    suggestions.style.display = 'none';
                    suggestions.innerHTML = '';
                }
            });
        }

        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

        setupClientAutocomplete();
        if (typeof window.initIssueCategoryDropdown === 'function') {
            window.initIssueCategoryDropdown(parentEl);
        }
    }

    function initQuill(parentEl) {
        const quillEl = parentEl.querySelector('#quill-description');
        if (!quillEl) return;
        const quill = new Quill(quillEl, {
            modules: {
                toolbar: [
                    ['bold', 'italic'],
                    [{ list: 'ordered' }, { list: 'bullet' }]
                ],
            },
            theme: 'snow'
        });

        parentEl.querySelector('#form_submition')?.addEventListener('submit', function() {
            const hidden = parentEl.querySelector('#description');
            if (hidden) hidden.value = quill.root.innerHTML;
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        initSelect2('#advancedFilters');

        const advancedFilters = document.getElementById('advancedFilters');
        if (advancedFilters) {
            advancedFilters.addEventListener('shown.bs.collapse', function () {
                initSelect2(advancedFilters);
            });
        }

        const issueModalEl = document.getElementById('issueModal');
        let issueModal = null;
        if (issueModalEl) {
            issueModal = new bootstrap.Modal(issueModalEl);

            document.querySelectorAll('.create-issue-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    fetch("{{ route('issues.create-modal') }}")
                        .then(resp => resp.text())
                        .then(html => {
                            document.getElementById('issue-modal-content').innerHTML = html;
                            issueModal.show();
                            initSelect2(issueModalEl);
                            initIssueAutocomplete(issueModalEl);
                            initQuill(issueModalEl);
                            if (typeof window.initIssueCategoryDropdown === 'function') {
                                window.initIssueCategoryDropdown(issueModalEl);
                            }
                        });
                });
            });

            document.querySelectorAll('.edit-issue-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    fetch(`/issues/${id}/edit/modal`)
                        .then(resp => resp.text())
                        .then(html => {
                            document.getElementById('issue-modal-content').innerHTML = html;
                            issueModal.show();
                            initSelect2(issueModalEl);
                            initIssueAutocomplete(issueModalEl);
                            initQuill(issueModalEl);
                            if (typeof window.initIssueCategoryDropdown === 'function') {
                                window.initIssueCategoryDropdown(issueModalEl);
                            }
                        });
                });
            });
        }

        const checkAll = document.getElementById('checkAll');
        if (checkAll) {
            checkAll.addEventListener('change', function(e) {
                document.querySelectorAll('#ticketRows input[type="checkbox"]').forEach(cb => {
                    cb.checked = e.target.checked;
                });
            });
        }

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
