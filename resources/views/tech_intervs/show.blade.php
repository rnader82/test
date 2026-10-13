@extends('layouts.app')

@php
    $client = $technicalIntervention->client;
    $clientContact = $technicalIntervention->clientContact;
    $materials = $technicalIntervention->materials;
    $tools = $technicalIntervention->tools;
    $tasks = $technicalIntervention->tasks;
    $warehouses = $warehouses ?? \App\Models\Warehouse::all();
    $fullTitle = "Intervention #{$technicalIntervention->id}" . ($technicalIntervention->subject ? " - {$technicalIntervention->subject}" : '');
    $truncatedTitle = \Illuminate\Support\Str::limit($fullTitle, 60);
@endphp

@section('title', $truncatedTitle)

@section('navbar-title')
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('tech_intervs.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 bg-white text-dark border p-2 shadow-sm" title="Retour">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h1 class="mb-0 fw-bold" style="color: #0f172a; font-size: 1.35rem;" title="{{ $fullTitle }}">
                {{ $truncatedTitle }}
            </h1>
            <div class="d-flex align-items-center gap-2 mt-1">
                @if($technicalIntervention->status)
                    <span class="badge" style="background-color: {{ hexToRgba($technicalIntervention->status->color ?? '#6c757d', 0.15) }}; color: {{ $technicalIntervention->status->color ?? '#6c757d' }}; font-weight: 700; font-size: 0.75rem;">
                        <span style="background-color: {{ $technicalIntervention->status->color ?? '#6c757d' }}; width: 7px; height: 7px; border-radius: 50%; display: inline-block; margin-right: 4px;"></span>
                        {{ $technicalIntervention->status->translated_name }}
                    </span>
                @endif
                @if($technicalIntervention->state)
                    <span class="badge" style="background-color: {{ hexToRgba($technicalIntervention->state->color ?? '#6c757d', 0.15) }}; color: {{ $technicalIntervention->state->color ?? '#6c757d' }}; font-weight: 700; font-size: 0.75rem;">
                        <span style="background-color: {{ $technicalIntervention->state->color ?? '#6c757d' }}; width: 7px; height: 7px; border-radius: 50%; display: inline-block; margin-right: 4px;"></span>
                        {{ $technicalIntervention->state->translated_name }}
                    </span>
                @endif
                @if ($client?->concurrent)
                    <span class="badge bg-danger" style="font-size: 0.75rem;"><i class="bi bi-flag-fill me-1"></i>{{ __('locale.lbl_concurrent') }}</span>
                @endif
                @if ($client?->default_payment)
                    <span class="badge bg-danger" style="font-size: 0.75rem;"><i class="bi bi-exclamation-triangle-fill me-1"></i>{{ __('locale.default_payment') }}</span>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2 me-2">
        <a href="{{ route('tech_intervs.edit', $technicalIntervention->id) }}" class="btn btn-outline-secondary btn-sm rounded-3 bg-white text-dark border px-3 py-2 fw-semibold shadow-sm">
            <i class="bi bi-pencil-square me-1"></i> {{ __('locale.btn_edit') ?? 'Modifier' }}
        </a>
        <a href="{{ route('tech_intervs.pdf', $technicalIntervention->id) }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-3 bg-white text-dark border px-3 py-2 fw-semibold shadow-sm">
            <i class="bi bi-file-earmark-pdf me-1"></i> {{ __('locale.pdf_export') ?? 'Exporter En PDF' }}
        </a>
    </div>
@endsection

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- TOP SECTION: Grande Card englobant les 4 cartes -->
    <div class="outer-container-card mb-4">
        <div class="row g-3">
            <!-- 1. Client Card -->
            <div class="col-xl-3 col-lg-6 col-md-12">
                <div class="inner-sub-card">
                    <div class="card-title-custom">
                        <i class="bi bi-person" style="color: #4f46e5; font-size: 1.25rem;"></i>
                        <span>1. Client</span>
                    </div>

                    <div class="field-row">
                        <div class="field-label">Raison sociale</div>
                        <div class="field-value-box flex-grow-1 fw-bold">
                            @if($client)
                                <a href="{{ route('clients.show', $client->id) }}" class="text-decoration-none text-primary">{{ $client->raison_sociale }}</a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field-label">Contact</div>
                        <div class="field-value-box flex-grow-1">
                            {{ $clientContact ? ($clientContact->prenom . ' ' . $clientContact->nom) : '—' }}
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field-label">Email</div>
                        <div class="field-value-box flex-grow-1">
                            {{ $clientContact?->email ?? ($client?->email ?? '—') }}
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field-label">Téléphone</div>
                        <div class="field-value-box flex-grow-1">
                            {{ $clientContact?->telephone ?? ($clientContact?->mobile ?? ($client?->tel_1 ?? '—')) }}
                        </div>
                    </div>

                    <div class="field-row align-items-start">
                        <div class="field-label pt-1">Adresse</div>
                        <div class="field-value-box flex-grow-1" style="min-height: 48px;">
                            @if($client)
                                @php
                                    $cAddr = trim(($client->adresse_1 ?? '') . ' ' . ($client->adresse_2 ?? '') . "\n" . ($client->code_postal ?? '') . ' ' . ($client->ville ?? ''));
                                @endphp
                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ urlencode($cAddr) }}" target="_blank" rel="noopener" class="text-decoration-none text-dark">
                                    <i class="bi bi-geo-alt me-1 text-danger"></i>
                                    {!! nl2br(e($cAddr)) !!}
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Site d'installation Card -->
            <div class="col-xl-3 col-lg-6 col-md-12">
                <div class="inner-sub-card">
                    <div class="card-title-custom">
                        <i class="bi bi-geo-alt-fill" style="color: #16a34a; font-size: 1.25rem;"></i>
                        <span>2. Site d'installation</span>
                    </div>

                    <div class="d-flex align-items-center mb-2" style="margin-left: 105px;">
                        @if($technicalIntervention->is_site_address_different)
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle small" style="font-size: 0.7rem;">Adresse différente</span>
                        @else
                            <span class="badge bg-light text-muted border small" style="font-size: 0.7rem;">Même adresse que client</span>
                        @endif
                    </div>

                    <div class="field-row">
                        <div class="field-label">Nom du site</div>
                        <div class="field-value-box flex-grow-1 fw-semibold">
                            {{ $technicalIntervention->site_name ?: ($client?->raison_sociale ?? '—') }}
                        </div>
                    </div>

                    <div class="field-row align-items-start">
                        <div class="field-label pt-1">Adresse</div>
                        <div class="field-value-box flex-grow-1" style="min-height: 48px;">
                            @if($technicalIntervention->site_address)
                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ urlencode($technicalIntervention->site_address) }}" target="_blank" rel="noopener" class="text-decoration-none text-dark">
                                    <i class="bi bi-geo-alt me-1 text-danger"></i>
                                    {!! nl2br(e($technicalIntervention->site_address)) !!}
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field-label">Coordonnées GPS</div>
                        <div class="field-value-box flex-grow-1">
                            @if($technicalIntervention->gps_coordinates)
                                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($technicalIntervention->gps_coordinates) }}" target="_blank" rel="noopener" class="text-primary text-decoration-none fw-medium">
                                    <i class="bi bi-pin-map me-1 text-primary"></i> {{ $technicalIntervention->gps_coordinates }}
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field-label">Contact sur site</div>
                        <div class="field-value-box flex-grow-1">
                            {{ $technicalIntervention->site_contact_name ?: ($clientContact ? ($clientContact->prenom . ' ' . $clientContact->nom) : '—') }}
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field-label">Téléphone</div>
                        <div class="field-value-box flex-grow-1">
                            {{ $technicalIntervention->site_contact_phone ?: ($clientContact?->telephone ?? ($clientContact?->mobile ?? ($client?->tel_1 ?? '—'))) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Intervention Card -->
            <div class="col-xl-3 col-lg-6 col-md-12">
                <div class="inner-sub-card">
                    <div class="card-title-custom">
                        <i class="bi bi-wrench-adjustable" style="color: #4f46e5; font-size: 1.25rem;"></i>
                        <span>3. Intervention</span>
                    </div>

                    <div class="field-row">
                        <div class="field-label">Type d'intervention</div>
                        <div class="field-value-box flex-grow-1 fw-semibold">
                            {{ $technicalIntervention->type?->translated_name ?? '—' }}
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field-label">Sous-type</div>
                        <div class="field-value-box flex-grow-1">
                            {{ $technicalIntervention->subType?->translated_name ?? '—' }}
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field-label">Priorité</div>
                        <div class="field-value-box flex-grow-1">
                            <span class="badge {{ $technicalIntervention->priority === 'Urgent' ? 'bg-danger' : ($technicalIntervention->priority === 'Important' ? 'bg-warning text-dark' : 'bg-light text-dark border') }}">
                                {{ $technicalIntervention->translated_priority }}
                            </span>
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field-label">Date planifiée</div>
                        <div class="field-value-box flex-grow-1">
                            <i class="bi bi-calendar3 me-1 text-muted"></i>
                            {{ $technicalIntervention->intervention_date ? format_datetime($technicalIntervention->intervention_date, 'short', 'short') : '—' }}
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field-label">Durée estimée</div>
                        <div class="field-value-box flex-grow-1">
                            <i class="bi bi-clock me-1 text-muted"></i>
                            @if (!is_null($technicalIntervention->estimated_duration))
                                @php
                                    $d = intdiv($technicalIntervention->estimated_duration, 1440);
                                    $rem = $technicalIntervention->estimated_duration % 1440;
                                    $h = intdiv($rem, 60);
                                    $m = $rem % 60;
                                @endphp
                                {{ ($d > 0 ? "{$d}j " : '') . sprintf('%02d:%02d', $h, $m) }}
                            @else
                                —
                            @endif
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field-label">Technicien assigné</div>
                        <div class="field-value-box flex-grow-1">
                            @if ($technicalIntervention->assignedUsers->isNotEmpty())
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($technicalIntervention->assignedUsers as $usr)
                                        <span class="badge bg-light text-dark border"><i class="bi bi-person-fill me-1"></i>{{ $usr->name }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted">{{ __('locale.lbl_no_users_assigned') ?? 'Non assigné' }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Checklist des tâches Card -->
            <div class="col-xl-3 col-lg-6 col-md-12">
                <div class="inner-sub-card d-flex flex-column">
                    <div class="card-title-custom justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-list-check" style="color: #4f46e5; font-size: 1.25rem;"></i>
                            <span>4. Checklist des tâches</span>
                        </div>
                        <span class="badge bg-light text-dark border small" id="task_count_badge" style="font-weight: 500;">
                            {{ $tasks->where('is_completed', true)->count() }} / {{ $tasks->count() }}
                        </span>
                    </div>

                    <div id="show_checklist_container" class="flex-grow-1 overflow-auto pe-1 mb-3" style="max-height: 280px;">
                        @forelse($tasks as $t)
                            <div class="task-item d-flex align-items-center justify-content-between py-1 px-1 rounded-2" data-task-id="{{ $t->id }}">
                                <div class="d-flex align-items-center gap-2 mb-0 flex-grow-1">
                                    <input class="form-check-input custom-checkbox toggle-task" type="checkbox" data-id="{{ $t->id }}"
                                        id="show_task_{{ $t->id }}" {{ $t->is_completed ? 'checked' : '' }}>
                                    <label class="task-label-text mb-0 cursor-pointer {{ $t->is_completed ? 'text-decoration-line-through text-muted' : '' }}"
                                        for="show_task_{{ $t->id }}">
                                        {{ $t->task }}
                                    </label>
                                </div>
                                <button type="button" class="btn btn-link btn-sm text-danger opacity-50 hover-opacity-100 p-0 ms-2 delete-task" data-id="{{ $t->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4 small" id="empty_task_msg">
                                <i class="bi bi-check2-circle fs-3 d-block opacity-50 mb-1"></i>
                                Aucune tâche enregistrée
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-auto pt-2">
                        <div class="input-group input-group-sm">
                            <input type="text" id="new_task_input" class="form-control custom-input" placeholder="Ajouter une tâche...">
                            <button type="button" class="btn btn-primary px-3" id="btn_quick_add_task" style="background-color: #4f46e5; border-color: #4f46e5;">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BOTTOM SECTION: Grande Card englobant les 2 cartes (Matériels et Outils) -->
    <div class="outer-container-card mb-4">
        <div class="card-title-custom mb-3">
            <i class="bi bi-box-seam" style="color: #4f46e5; font-size: 1.25rem;"></i>
            <span>5. Matériel & outils nécessaires</span>
        </div>

        <div class="row g-3">
            <!-- Carte 1: Matériel nécessaire pour l'intervention -->
            <div class="col-lg-6 col-md-12">
                <div class="inner-sub-card d-flex flex-column h-100">
                    <div class="sub-table-title mb-3">Matériel nécessaire pour l'intervention</div>
                    
                    <div class="table-responsive flex-grow-1 mb-2">
                        <table class="table custom-table align-middle mb-0" id="show_materials_table">
                            <thead>
                                <tr>
                                    <th>Matériel</th>
                                    <th>Référence</th>
                                    <th class="text-center" style="width: 60px;">Qté</th>
                                    <th style="width: 200px;">Où se trouve le matériel ?</th>
                                    <th style="width: 25px;"></th>
                                </tr>
                            </thead>
                            <tbody id="show_materials_tbody">
                                @forelse($materials as $mat)
                                    @php
                                        $isAtCl = (bool)$mat->is_at_client;
                                        $whName = $mat->warehouse?->name ?? 'bureau Novalyo';
                                    @endphp
                                    <tr data-equip-id="{{ $mat->id }}">
                                        <td class="fw-medium text-dark">{{ $mat->display_name }}</td>
                                        <td class="text-muted">{{ $mat->display_reference }}</td>
                                        <td class="text-center">{{ $mat->quantity }}</td>
                                        <td>
                                            @if($isAtCl)
                                                <span class="badge-pill-loc badge-client">Chez le client</span>
                                            @else
                                                <span class="badge-pill-loc badge-novalyo text-truncate" style="max-width: 190px;" title="{{ $whName }}">
                                                    À prendre au bureau Novalyo
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-link btn-sm text-danger opacity-50 hover-opacity-100 p-0 delete-show-equip" data-id="{{ $mat->id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="empty_materials_row">
                                        <td colspan="5" class="text-center text-muted py-3 small">
                                            Aucun matériel renseigné
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-auto pt-2">
                        <a href="javascript:void(0)" class="action-link-blue" id="btn_show_add_mat_modal">
                            Ajouter du matériel
                        </a>
                    </div>
                </div>
            </div>

            <!-- Carte 2: Outils d'installation / programmation -->
            <div class="col-lg-6 col-md-12">
                <div class="inner-sub-card d-flex flex-column h-100">
                    <div class="sub-table-title mb-3">Outils d'installation / programmation</div>

                    <div class="table-responsive flex-grow-1 mb-2">
                        <table class="table custom-table align-middle mb-0" id="show_tools_table">
                            <thead>
                                <tr>
                                    <th>Outil / Logiciel</th>
                                    <th>Référence</th>
                                    <th class="text-center" style="width: 60px;">Qté</th>
                                    <th style="width: 25px;"></th>
                                </tr>
                            </thead>
                            <tbody id="show_tools_tbody">
                                @forelse($tools as $tool)
                                    <tr data-equip-id="{{ $tool->id }}">
                                        <td class="fw-medium text-dark">{{ $tool->display_name }}</td>
                                        <td class="text-muted">{{ $tool->display_reference }}</td>
                                        <td class="text-center">{{ $tool->quantity }}</td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-link btn-sm text-danger opacity-50 hover-opacity-100 p-0 delete-show-equip" data-id="{{ $tool->id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="empty_tools_row">
                                        <td colspan="4" class="text-center text-muted py-3 small">
                                            Aucun outil renseigné
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-auto pt-2">
                        <a href="javascript:void(0)" class="action-link-blue" id="btn_show_add_tool_modal">
                            Ajouter un outil / logiciel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Description & Notes Section if available -->
    @if($technicalIntervention->description)
        <div class="outer-container-card mb-4">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-file-text me-2 text-primary"></i>{{ __('locale.description') ?? 'Description' }}</h6>
            <div class="text-secondary">{!! $technicalIntervention->description !!}</div>
        </div>
    @endif
</div>

<!-- Modal: Ajouter du matériel (Show view) -->
<div class="modal fade" id="showAddMaterialModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">Ajouter du matériel</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <div class="mb-3 position-relative">
                    <label class="form-label small text-muted fw-semibold">Rechercher un équipement / matériel</label>
                    <input type="text" id="show_mat_search" class="form-control custom-input" placeholder="Nom ou référence..." autocomplete="off">
                    <input type="hidden" id="show_mat_equipment_id">
                    <ul id="show_mat_suggestions" class="list-group position-absolute w-100 bg-white shadow-lg border rounded-3"
                        style="top:100%; left:0; z-index: 1070; display:none; max-height: 180px; overflow-y: auto;"></ul>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-8">
                        <label class="form-label small text-muted fw-semibold">Nom personnalisé / Matériel</label>
                        <input type="text" id="show_mat_custom_name" class="form-control custom-input" placeholder="Nom du matériel">
                    </div>
                    <div class="col-4">
                        <label class="form-label small text-muted fw-semibold">Référence</label>
                        <input type="text" id="show_mat_custom_ref" class="form-control custom-input" placeholder="RÉF">
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-4">
                        <label class="form-label small text-muted fw-semibold">Quantité</label>
                        <input type="number" id="show_mat_qty" class="form-control custom-input" value="1" min="1">
                    </div>
                    <div class="col-8">
                        <label class="form-label small text-muted fw-semibold">Où se trouve le matériel ?</label>
                        <select id="show_mat_location" class="form-select custom-input">
                            <option value="client">Chez le client</option>
                            @foreach($warehouses as $wh)
                                <option value="wh_{{ $wh->id }}">À prendre: {{ $wh->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-sm btn-light rounded-pill px-3" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" id="btn_confirm_show_add_mat" style="background-color: #4f46e5; border-color: #4f46e5;">Ajouter</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Ajouter un outil / logiciel (Show view) -->
<div class="modal fade" id="showAddToolModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">Ajouter un outil ou logiciel</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <div class="row g-2 mb-3">
                    <div class="col-8">
                        <label class="form-label small text-muted fw-semibold">Outil / Logiciel</label>
                        <input type="text" id="show_tool_name" class="form-control custom-input" placeholder="ex: Tournevis set, Logiciel...">
                    </div>
                    <div class="col-4">
                        <label class="form-label small text-muted fw-semibold">Référence</label>
                        <input type="text" id="show_tool_ref" class="form-control custom-input" placeholder="ex: OUT-TS-01">
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-4">
                        <label class="form-label small text-muted fw-semibold">Quantité</label>
                        <input type="number" id="show_tool_qty" class="form-control custom-input" value="1" min="1">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-sm btn-light rounded-pill px-3" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" id="btn_confirm_show_add_tool" style="background-color: #4f46e5; border-color: #4f46e5;">Ajouter</button>
            </div>
        </div>
    </div>
</div>

<style>
.outer-container-card {
    background: #ffffff;
    border: 1px solid #e8ecf2;
    border-radius: 14px;
    padding: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
}

.inner-sub-card {
    background: #ffffff;
    border: 1px solid #e8ecf2;
    border-radius: 12px;
    padding: 18px;
    height: 100%;
}

.card-title-custom {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 18px;
}

.field-row {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.field-label {
    width: 105px;
    min-width: 105px;
    font-size: 13px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 0;
}

.field-value-box {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 13px;
    color: #1e293b;
    padding: 6px 12px;
    background-color: #ffffff;
}

.sub-table-title {
    font-size: 13.5px;
    font-weight: 700;
    color: #0f172a;
}

.custom-table {
    font-size: 13px;
    border-collapse: separate;
    border-spacing: 0;
}

.custom-table thead th {
    font-weight: 600;
    color: #0f172a;
    border-bottom: 1px solid #f1f5f9;
    padding: 8px 10px;
    background: transparent;
}

.custom-table tbody td {
    padding: 8px 10px;
    vertical-align: middle;
    border-bottom: 1px solid #f8fafc;
}

.badge-pill-loc {
    border: none;
    font-size: 12px;
    font-weight: 600;
    padding: 4px 14px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
}

.badge-client {
    background-color: #e6f9f0 !important;
    color: #059669 !important;
}

.badge-novalyo {
    background-color: #fff4ea !important;
    color: #ea580c !important;
}

.action-link-blue {
    color: #4f46e5;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    cursor: pointer;
}

.action-link-blue:hover {
    color: #4338ca;
    text-decoration: underline;
}

.custom-checkbox {
    width: 16px;
    height: 16px;
    border-radius: 4px;
    border: 1px solid #cbd5e1;
    cursor: pointer;
    accent-color: #4f46e5;
}

.task-label-text {
    font-size: 13px;
    font-weight: 500;
    color: #1e293b;
}
</style>

@include('tech_intervs.tasks_js', ['techIntervId' => $technicalIntervention->id])

<script>
document.addEventListener('DOMContentLoaded', function() {
    const quickAddBtn = document.getElementById('btn_quick_add_task');
    const quickAddInput = document.getElementById('new_task_input');
    const checklistContainer = document.getElementById('show_checklist_container');
    const emptyMsg = document.getElementById('empty_task_msg');

    if (quickAddBtn && quickAddInput) {
        quickAddBtn.addEventListener('click', function() {
            const taskText = quickAddInput.value.trim();
            if (!taskText) return;

            fetch(`/tech_intervs/{{ $technicalIntervention->id }}/tasks`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ task: taskText })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (emptyMsg) emptyMsg.remove();
                    const div = document.createElement('div');
                    div.className = 'task-item d-flex align-items-center justify-content-between py-1 px-1 rounded-2';
                    div.dataset.taskId = data.task_id;
                    div.innerHTML = `
                        <div class="d-flex align-items-center gap-2 mb-0 flex-grow-1">
                            <input class="form-check-input custom-checkbox toggle-task" type="checkbox" data-id="${data.task_id}" id="show_task_${data.task_id}">
                            <label class="task-label-text mb-0 cursor-pointer" for="show_task_${data.task_id}">
                                ${data.task}
                            </label>
                        </div>
                        <button type="button" class="btn btn-link btn-sm text-danger opacity-50 hover-opacity-100 p-0 ms-2 delete-task" data-id="${data.task_id}">
                            <i class="bi bi-trash"></i>
                        </button>
                    `;
                    checklistContainer.appendChild(div);
                    quickAddInput.value = '';
                }
            });
        });

        quickAddInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                quickAddBtn.click();
            }
        });
    }

    // Material Modal in Show View
    const btnShowAddMat = document.getElementById('btn_show_add_mat_modal');
    const showMatModalEl = document.getElementById('showAddMaterialModal');
    const btnConfirmShowAddMat = document.getElementById('btn_confirm_show_add_mat');
    const showMatSearch = document.getElementById('show_mat_search');
    const showMatSuggestions = document.getElementById('show_mat_suggestions');
    const showMatCustomName = document.getElementById('show_mat_custom_name');
    const showMatCustomRef = document.getElementById('show_mat_custom_ref');
    const showMatQty = document.getElementById('show_mat_qty');
    const showMatLoc = document.getElementById('show_mat_location');
    const showMatEquipmentId = document.getElementById('show_mat_equipment_id');
    const showMaterialsTbody = document.getElementById('show_materials_tbody');

    let showMatModal = null;
    if (showMatModalEl && typeof bootstrap !== 'undefined') {
        showMatModal = new bootstrap.Modal(showMatModalEl);
    }

    if (btnShowAddMat && showMatModal) {
        btnShowAddMat.addEventListener('click', function() {
            if (showMatSearch) showMatSearch.value = '';
            if (showMatCustomName) showMatCustomName.value = '';
            if (showMatCustomRef) showMatCustomRef.value = '';
            if (showMatQty) showMatQty.value = '1';
            if (showMatEquipmentId) showMatEquipmentId.value = '';
            if (showMatSuggestions) showMatSuggestions.style.display = 'none';
            showMatModal.show();
        });
    }

    if (showMatSearch) {
        let matDebounce;
        showMatSearch.addEventListener('input', function() {
            clearTimeout(matDebounce);
            const q = this.value.trim();
            if (q.length < 2) {
                if (showMatSuggestions) showMatSuggestions.style.display = 'none';
                return;
            }
            matDebounce = setTimeout(() => {
                fetch(`/equipments/search?q=${encodeURIComponent(q)}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(r => r.json())
                .then(data => {
                    if (!showMatSuggestions) return;
                    showMatSuggestions.innerHTML = '';
                    if (!data || data.length === 0) {
                        showMatSuggestions.style.display = 'none';
                        return;
                    }
                    data.forEach(eq => {
                        const li = document.createElement('li');
                        li.className = 'list-group-item list-group-item-action cursor-pointer p-2 small';
                        li.innerHTML = `<strong>${eq.name}</strong> <span class="text-muted ms-1">(${eq.reference || ''})</span>`;
                        li.addEventListener('click', function() {
                            if (showMatSearch) showMatSearch.value = eq.name;
                            if (showMatCustomName) showMatCustomName.value = eq.name;
                            if (showMatCustomRef) showMatCustomRef.value = eq.reference || '';
                            if (showMatEquipmentId) showMatEquipmentId.value = eq.id;
                            showMatSuggestions.style.display = 'none';
                        });
                        showMatSuggestions.appendChild(li);
                    });
                    showMatSuggestions.style.display = 'block';
                });
            }, 250);
        });
    }

    if (btnConfirmShowAddMat) {
        btnConfirmShowAddMat.addEventListener('click', function() {
            const name = (showMatCustomName ? showMatCustomName.value.trim() : '') || (showMatSearch ? showMatSearch.value.trim() : '');
            const ref = showMatCustomRef ? showMatCustomRef.value.trim() : '';
            const qty = showMatQty ? (showMatQty.value || 1) : 1;
            const eqId = showMatEquipmentId ? showMatEquipmentId.value : null;
            const locVal = showMatLoc ? showMatLoc.value : 'client';
            const isAtClient = locVal === 'client';
            const warehouseId = isAtClient ? null : locVal.replace('wh_', '');

            if (!name) {
                alert('Veuillez renseigner le nom du matériel');
                return;
            }

            fetch(`/tech_intervs/{{ $technicalIntervention->id }}/equipments`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    equipment_id: eqId,
                    custom_name: name,
                    custom_reference: ref,
                    quantity: qty,
                    item_type: 'material',
                    is_at_client: isAtClient,
                    warehouse_id: warehouseId
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const emptyRow = document.getElementById('empty_materials_row');
                    if (emptyRow) emptyRow.remove();

                    const badgeClass = isAtClient ? 'badge-client' : 'badge-novalyo';
                    const badgeText = isAtClient ? 'Chez le client' : 'À prendre au bureau Novalyo';
                    const tr = document.createElement('tr');
                    tr.dataset.equipId = data.equipment ? data.equipment.id : '';
                    tr.innerHTML = `
                        <td class="fw-medium text-dark">${name}</td>
                        <td class="text-muted">${ref}</td>
                        <td class="text-center">${qty}</td>
                        <td>
                            <span class="badge-pill-loc ${badgeClass}">${badgeText}</span>
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-link btn-sm text-danger opacity-50 hover-opacity-100 p-0 delete-show-equip" data-id="${data.equipment ? data.equipment.id : ''}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    `;
                    if (showMaterialsTbody) showMaterialsTbody.appendChild(tr);
                    if (showMatModal) showMatModal.hide();
                }
            });
        });
    }

    // Tool Modal in Show View
    const btnShowAddTool = document.getElementById('btn_show_add_tool_modal');
    const showToolModalEl = document.getElementById('showAddToolModal');
    const btnConfirmShowAddTool = document.getElementById('btn_confirm_show_add_tool');
    const showToolName = document.getElementById('show_tool_name');
    const showToolRef = document.getElementById('show_tool_ref');
    const showToolQty = document.getElementById('show_tool_qty');
    const showToolsTbody = document.getElementById('show_tools_tbody');

    let showToolModal = null;
    if (showToolModalEl && typeof bootstrap !== 'undefined') {
        showToolModal = new bootstrap.Modal(showToolModalEl);
    }

    if (btnShowAddTool && showToolModal) {
        btnShowAddTool.addEventListener('click', function() {
            if (showToolName) showToolName.value = '';
            if (showToolRef) showToolRef.value = '';
            if (showToolQty) showToolQty.value = '1';
            showToolModal.show();
        });
    }

    if (btnConfirmShowAddTool) {
        btnConfirmShowAddTool.addEventListener('click', function() {
            const name = showToolName ? showToolName.value.trim() : '';
            const ref = showToolRef ? showToolRef.value.trim() : '';
            const qty = showToolQty ? (showToolQty.value || 1) : 1;

            if (!name) {
                alert('Veuillez renseigner le nom de l\'outil ou logiciel');
                return;
            }

            fetch(`/tech_intervs/{{ $technicalIntervention->id }}/equipments`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    custom_name: name,
                    custom_reference: ref,
                    quantity: qty,
                    item_type: 'tool'
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const emptyRow = document.getElementById('empty_tools_row');
                    if (emptyRow) emptyRow.remove();

                    const tr = document.createElement('tr');
                    tr.dataset.equipId = data.equipment ? data.equipment.id : '';
                    tr.innerHTML = `
                        <td class="fw-medium text-dark">${name}</td>
                        <td class="text-muted">${ref}</td>
                        <td class="text-center">${qty}</td>
                        <td class="text-end">
                            <button type="button" class="btn btn-link btn-sm text-danger opacity-50 hover-opacity-100 p-0 delete-show-equip" data-id="${data.equipment ? data.equipment.id : ''}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    `;
                    if (showToolsTbody) showToolsTbody.appendChild(tr);
                    if (showToolModal) showToolModal.hide();
                }
            });
        });
    }

    // Equipment delete handler in Show View
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.delete-show-equip');
        if (btn) {
            const equipId = btn.dataset.id;
            if (!equipId) return;
            if (!confirm('Voulez-vous vraiment retirer cet élément ?')) return;

            fetch(`/tech_intervs/{{ $technicalIntervention->id }}/equipments/${equipId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const row = btn.closest('tr');
                    if (row) row.remove();
                }
            });
        }
    });
});
</script>
@endsection
