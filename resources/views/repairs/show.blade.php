@extends('layouts.app')

@section('title', 'Dossier SAV #' . $repair->id)

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('repairs.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">
            Dossier SAV #{{ $repair->id }}
        </h1>
    </div>
@endsection

@section('page-actions')
    <a href="{{ route('repairs.print-preview', $repair) }}" target="_blank" class="btn btn-outline-secondary">
        <i class="bi bi-printer me-1"></i> Formulaire d'impression
    </a>
    @if(auth()->user() && (auth()->user()->is_admin || auth()->user()->hasPermission('edit-repair')))
        <a href="{{ route('repairs.edit', $repair) }}" class="btn btn-warning">
            <i class="bi bi-pencil-square me-1"></i> {{ __('locale.edit') }}
        </a>
    @endif
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('libs/flatpickr/flatpickr.css') }}">
@endpush

@section('content')
@php
    $client = $repair->effective_client;
    $status = $repair->status;
    $statusColor = $status?->color ?? '#6c757d';
    $statusLabel = $status?->translated_name ?? $status?->name ?? __('locale.lbl_no_status');
    $canEdit = auth()->user() && (auth()->user()->is_admin || auth()->user()->hasPermission('edit-repair') || auth()->user()->hasPermission('change-equipment-repair-state'));
    $warrantyTypesCollection = $warrantyTypes ?? \App\Models\WarrantyType::where('is_active', true)->orderBy('order')->orderBy('name')->get();
    $repairDiagnosesCollection = $repairDiagnoses ?? \App\Models\RepairDiagnosis::where('is_active', true)->orderBy('order')->orderBy('name')->get();
@endphp

<div class="container-xxl py-3">
    <!-- Header Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <span class="badge fs-6 px-3 py-2" style="background-color: {{ hexToRgba($statusColor, 0.15) }}; color: {{ getDynamicStatusColor($statusColor) }}; font-weight: 600;">
                            <span class="status-rectangle" style="background-color: {{ $statusColor }}; width: 10px; height: 10px; border-radius: 50%; display: inline-block; margin-right: 6px;"></span>
                            {{ $statusLabel }}
                        </span>
                        <span class="text-muted small">Créé le {{ $repair->created_at->format('d/m/Y à H:i') }}</span>
                    </div>

                    <h4 class="fw-bold mb-1">
                        @if($client)
                            <a href="{{ route('clients.show', $client->id) }}" class="text-decoration-none text-dark">
                                <i class="bi bi-building text-primary me-2"></i>{{ $client->raison_sociale }}
                            </a>
                        @else
                            <span class="text-muted">Client non spécifié</span>
                        @endif
                    </h4>

                    <div class="text-muted mt-2">
                        @if($repair->issue)
                            <span class="me-3"><i class="bi bi-ticket-perforated me-1 text-primary"></i>Ticket interne : <a href="{{ route('issues.show', $repair->issue_id) }}">#{{ $repair->issue_id }} - {{ $repair->issue->subject }}</a></span>
                        @endif
                        @if($repair->external_ticket_number)
                            <span class="me-3"><i class="bi bi-tag me-1 text-secondary"></i>Ticket externe : <strong>{{ $repair->external_ticket_number }}</strong></span>
                        @endif
                        @if(!$repair->issue && !$repair->external_ticket_number)
                            <span class="me-3"><i class="bi bi-dash-circle me-1"></i>Sans ticket</span>
                        @endif
                    </div>
                </div>

                <div class="col-md-5 text-md-end mt-3 mt-md-0">
                    <div class="p-3 bg-light rounded-3 d-inline-block text-start" style="min-width: 220px;">
                        <div class="text-muted small">Total équipements suivis</div>
                        <div class="fs-4 fw-bold text-primary">{{ $repair->equipmentRepairs->count() }} matériel(s)</div>
                        @if($repair->notes)
                            <div class="small text-secondary mt-1 text-truncate" style="max-width: 250px;" title="{{ $repair->notes }}">
                                <i class="bi bi-sticky me-1"></i>{{ $repair->notes }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Équipements & Traçabilité SAV -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="fw-bold mb-0"><i class="bi bi-tools text-primary me-2"></i>Matériels & Traçabilité du Suivi SAV</h5>
        @if($canEdit)
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">
                <i class="bi bi-pencil-square me-1"></i>Modification autorisée des suivis matériels
            </span>
        @endif
    </div>

    @forelse($repair->equipmentRepairs as $index => $eqRep)
        <div class="card shadow-sm border mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center py-3 flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary rounded-pill">#{{ $index + 1 }}</span>
                    <h6 class="mb-0 fw-bold text-dark fs-6">{{ $eqRep->equipment?->name ?? 'Équipement non répertorié' }}</h6>
                    @if($eqRep->equipment?->reference)
                        <span class="badge bg-light text-muted border font-monospace">{{ $eqRep->equipment->reference }}</span>
                    @endif
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    @if($eqRep->warrantyTypeRelation)
                        <span class="badge px-2 py-1" style="background-color: {{ $eqRep->warrantyTypeRelation->color ?? '#3b82f6' }}; color: #ffffff;">
                            <i class="bi bi-shield-check me-1"></i>{{ $eqRep->warrantyTypeRelation->name }}
                        </span>
                    @elseif($eqRep->warranty_type === 'under_warranty')
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"><i class="bi bi-shield-check me-1"></i>Sous garantie</span>
                    @elseif($eqRep->warranty_type === 'out_of_warranty')
                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1"><i class="bi bi-file-earmark-text me-1"></i>Hors garantie (Devis)</span>
                    @elseif($eqRep->warranty_type === 'amen_contract')
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1"><i class="bi bi-phone me-1"></i>Contrat Amen</span>
                    @endif

                    @if($eqRep->customer_delivery_date)
                        <span class="badge bg-success text-white px-2 py-1"><i class="bi bi-check2-all me-1"></i>Livré client</span>
                    @endif

                    @if($canEdit)
                        <button type="button" class="btn btn-sm btn-primary ms-1" data-bs-toggle="modal" data-bs-target="#editEquipmentModal_{{ $eqRep->id }}">
                            <i class="bi bi-pencil-square me-1"></i> Modifier le suivi
                        </button>
                    @endif
                </div>
            </div>

            <div class="card-body p-4">
                <div class="row g-4">
                    <!-- Étape 1 : Réception -->
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded-3 h-100 border">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="fw-bold small text-uppercase text-secondary mb-0"><i class="bi bi-box-arrow-in-down text-primary me-1"></i> 1. Réception</h6>
                                <span class="badge bg-secondary-subtle text-secondary border small" style="font-size: 0.7rem;"><i class="bi bi-lock-fill me-1"></i>Fixe</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted small d-block">Numéro de série :</span>
                                <span class="font-monospace fw-bold">{{ $eqRep->serial_number ?: '—' }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted small d-block">Date d'arrivée :</span>
                                <span>{{ $eqRep->arrival_date ? $eqRep->arrival_date->format('d/m/Y') : '—' }}</span>
                            </div>
                            <div>
                                <span class="text-muted small d-block">Description panne :</span>
                                <span class="small">{{ $eqRep->description ?: 'Aucune description' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Étape 2 : Prise en charge / Garantie -->
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded-3 h-100 border">
                            <h6 class="fw-bold small text-uppercase text-secondary mb-3"><i class="bi bi-shield-check text-primary me-1"></i> 2. Garantie / Devis</h6>
                            @if(($eqRep->warrantyTypeRelation && $eqRep->warrantyTypeRelation->code === 'under_warranty') || $eqRep->warranty_type === 'under_warranty')
                                <div class="mb-2">
                                    <span class="text-muted small d-block">Prise en charge :</span>
                                    <span class="badge bg-success text-white">{{ $eqRep->warrantyTypeRelation?->name ?? 'Sous garantie constructeur' }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="text-muted small d-block">Envoi réparateur :</span>
                                    <span>{{ $eqRep->recipient_name ?: '—' }}</span>
                                </div>
                                <div>
                                    <span class="text-muted small d-block">N° colis / Suivi :</span>
                                    <span class="font-monospace">{{ $eqRep->tracking_number ?: '—' }}</span>
                                </div>
                            @elseif(($eqRep->warrantyTypeRelation && $eqRep->warrantyTypeRelation->code === 'out_of_warranty') || $eqRep->warranty_type === 'out_of_warranty')
                                <div class="mb-2">
                                    <span class="text-muted small d-block">N° Devis :</span>
                                    <span class="font-monospace fw-bold">{{ $eqRep->quote_number ?: '—' }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="text-muted small d-block">Montant devis :</span>
                                    <span class="fw-bold text-primary">{{ $eqRep->quote_amount ? format_number($eqRep->quote_amount, 2) . ' € HT' : '—' }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="text-muted small d-block">Date envoi client :</span>
                                    <span>{{ $eqRep->quote_sent_date ? $eqRep->quote_sent_date->format('d/m/Y') : '—' }}</span>
                                </div>
                                <div>
                                    <span class="text-muted small d-block">Devis signé :</span>
                                    @if($eqRep->quote_signed === true)
                                        <span class="badge bg-success"><i class="bi bi-check-lg me-1"></i>Signé @if($eqRep->quote_signed_date)({{ $eqRep->quote_signed_date->format('d/m/Y') }})@endif</span>
                                    @elseif($eqRep->quote_signed === false)
                                        <span class="badge bg-danger">Refusé</span>
                                    @else
                                        <span class="badge bg-warning text-dark">En attente</span>
                                    @endif
                                </div>
                            @elseif(($eqRep->warrantyTypeRelation && $eqRep->warrantyTypeRelation->code === 'amen_contract') || $eqRep->warranty_type === 'amen_contract')
                                <div class="mb-2">
                                    <span class="text-muted small d-block">N° DECT remplacé :</span>
                                    <span class="font-monospace fw-bold">{{ $eqRep->amen_replaced_serial ?: '—' }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="text-muted small d-block">Date envoi remplacement :</span>
                                    <span>{{ $eqRep->amen_sent_date ? $eqRep->amen_sent_date->format('d/m/Y') : '—' }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="text-muted small d-block">Matériel de prêt S/N :</span>
                                    <span class="font-monospace">{{ $eqRep->loan_serial_number ?: '—' }}</span>
                                </div>
                                <div>
                                    <span class="text-muted small d-block">Date envoi prêt :</span>
                                    <span>{{ $eqRep->loan_sent_date ? $eqRep->loan_sent_date->format('d/m/Y') : '—' }}</span>
                                </div>
                            @else
                                <span class="text-muted small">{{ $eqRep->warrantyTypeRelation?->name ?: 'Non renseigné' }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Étape 3 : Expédition Fournisseur -->
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded-3 h-100 border">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="fw-bold small text-uppercase text-secondary mb-0"><i class="bi bi-truck text-primary me-1"></i> 3. Fournisseur</h6>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted small d-block">Fournisseur : <span class="badge bg-secondary-subtle text-secondary border small py-0 px-1"><i class="bi bi-lock-fill me-1"></i>Fixe</span></span>
                                <span class="fw-semibold">{{ $eqRep->supplier?->name ?: '—' }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted small d-block">Date d'envoi :</span>
                                <span>{{ $eqRep->supplier_dispatch_date ? $eqRep->supplier_dispatch_date->format('d/m/Y') : '—' }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted small d-block">Date retour fournisseur :</span>
                                <span>{{ $eqRep->supplier_return_date ? $eqRep->supplier_return_date->format('d/m/Y') : '—' }}</span>
                            </div>
                            <div>
                                <span class="text-muted small d-block">Diagnostic réparateur :</span>
                                @if($eqRep->repairDiagnosis)
                                    <span class="badge" style="background-color: {{ $eqRep->repairDiagnosis->color ?? '#64748b' }}; color: #ffffff;">
                                        {{ $eqRep->repairDiagnosis->name }}
                                    </span>
                                @elseif($eqRep->repair_status === 'repaired')
                                    <span class="badge bg-success">Réparable / Réparé</span>
                                @elseif($eqRep->repair_status === 'unrepairable')
                                    <span class="badge bg-danger">Irréparable</span>
                                @elseif($eqRep->repair_status === 'replaced_new')
                                    <span class="badge bg-info text-dark">Échange neuf</span>
                                @else
                                    <span class="badge bg-secondary">En attente / En cours</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Étape 4 : Expédition Client -->
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded-3 h-100 border">
                            <h6 class="fw-bold small text-uppercase text-secondary mb-3"><i class="bi bi-send-check text-primary me-1"></i> 4. Livraison Client</h6>
                            <div class="mb-2">
                                <span class="text-muted small d-block">Date expédition client :</span>
                                <span class="fw-bold {{ $eqRep->customer_delivery_date ? 'text-success' : 'text-muted' }}">
                                    {{ $eqRep->customer_delivery_date ? $eqRep->customer_delivery_date->format('d/m/Y') : 'Non expédié' }}
                                </span>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted small d-block">Réf livraison Odoo :</span>
                                <span class="font-monospace">{{ $eqRep->odoo_delivery_ref ?: '—' }}</span>
                            </div>
                            @if($eqRep->comments)
                                <div class="mt-3 pt-2 border-top">
                                    <span class="text-muted small d-block">Commentaires :</span>
                                    <span class="small fst-italic">{{ $eqRep->comments }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($canEdit)
            <!-- Modal Modification Suivi Matériel SAV -->
            <div class="modal fade" id="editEquipmentModal_{{ $eqRep->id }}" tabindex="-1" aria-labelledby="editEquipmentModalLabel_{{ $eqRep->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <form action="{{ route('repairs.equipment-repairs.update', [$repair->id, $eqRep->id]) }}" method="POST" class="modal-content border-0 shadow-lg equipment-repair-edit-form" data-eq-id="{{ $eqRep->id }}">
                        @csrf
                        @method('PUT')

                        <div class="modal-header bg-light">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary rounded-pill">#{{ $index + 1 }}</span>
                                <h5 class="modal-title fw-bold mb-0" id="editEquipmentModalLabel_{{ $eqRep->id }}">
                                    <i class="bi bi-tools text-primary me-2"></i>Modifier le suivi SAV : {{ $eqRep->equipment?->name ?? 'Matériel' }}
                                </h5>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body p-4">
                            <!-- 1. Bloc des Informations Fixes (Non modifiables) -->
                            <div class="p-3 mb-4 rounded-3 bg-light border border-secondary-subtle">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-secondary text-white px-2 py-1">
                                        <i class="bi bi-lock-fill me-1"></i>Champs fixes (non modifiables)
                                    </span>
                                    @if($eqRep->equipment?->reference)
                                        <span class="font-monospace small text-muted">Réf: {{ $eqRep->equipment->reference }}</span>
                                    @endif
                                </div>
                                <div class="row g-3 small">
                                    <div class="col-md-4">
                                        <label class="text-muted d-block fw-semibold mb-1">Type & Modèle équipement</label>
                                        <span class="fw-bold text-dark">{{ $eqRep->equipment?->name ?? '—' }}</span>
                                        @if($eqRep->equipment?->type)
                                            <div class="text-muted small">({{ $eqRep->equipment->type->name }})</div>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted d-block fw-semibold mb-1">Numéro de série (S/N)</label>
                                        <span class="font-monospace fw-bold text-dark">{{ $eqRep->serial_number ?: '—' }}</span>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted d-block fw-semibold mb-1">Date d'arrivée / réception</label>
                                        <span class="fw-bold text-dark">{{ $eqRep->arrival_date ? $eqRep->arrival_date->format('d/m/Y') : '—' }}</span>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted d-block fw-semibold mb-1">Fournisseur</label>
                                        <span class="fw-bold text-dark">{{ $eqRep->supplier?->name ?: '—' }}</span>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="text-muted d-block fw-semibold mb-1">Description panne initiale</label>
                                        <span class="text-secondary fst-italic">{{ $eqRep->description ?: 'Aucune description' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Section Prise en charge & Garantie -->
                            <div class="card mb-4 border shadow-none bg-white">
                                <div class="card-header bg-light py-2">
                                    <h6 class="fw-bold text-primary mb-0 small text-uppercase">
                                        <i class="bi bi-shield-check me-1"></i> Étape 2 : Prise en charge / Garantie
                                    </h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="mb-3">
                                        <label for="modal_warranty_type_id_{{ $eqRep->id }}" class="form-label small fw-semibold">Type de garantie / Prise en charge</label>
                                        <select name="warranty_type_id" id="modal_warranty_type_id_{{ $eqRep->id }}" class="form-select modal-warranty-select" data-eq-id="{{ $eqRep->id }}">
                                            <option value="">-- Sélectionner la prise en charge --</option>
                                            @foreach($warrantyTypesCollection as $wt)
                                                <option value="{{ $wt->id }}" data-code="{{ $wt->code }}" {{ ((string)$eqRep->warranty_type_id === (string)$wt->id || $eqRep->warranty_type === $wt->code) ? 'selected' : '' }}>
                                                    {{ $wt->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    @php
                                        $currentWarrantyCode = $eqRep->warrantyTypeRelation?->code ?? $eqRep->warranty_type;
                                    @endphp

                                    <!-- Under Warranty Sub-fields -->
                                    <div class="warranty-group warranty-group-under_warranty" id="warranty_group_under_warranty_{{ $eqRep->id }}" style="{{ $currentWarrantyCode === 'under_warranty' ? '' : 'display: none;' }}">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Envoi réparateur / Destinataire</label>
                                                <input type="text" name="recipient_name" class="form-control form-control-sm" placeholder="Ex: SAV Constructeur..." value="{{ old('recipient_name', $eqRep->recipient_name) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">N° colis / Suivi transporteur</label>
                                                <input type="text" name="tracking_number" class="form-control form-control-sm font-monospace" placeholder="Ex: 6A1234567890..." value="{{ old('tracking_number', $eqRep->tracking_number) }}">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Out of Warranty / Devis Sub-fields -->
                                    <div class="warranty-group warranty-group-out_of_warranty" id="warranty_group_out_of_warranty_{{ $eqRep->id }}" style="{{ $currentWarrantyCode === 'out_of_warranty' ? '' : 'display: none;' }}">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">N° Devis</label>
                                                <input type="text" name="quote_number" class="form-control form-control-sm font-monospace" placeholder="Ex: DEV-2026-001" value="{{ old('quote_number', $eqRep->quote_number) }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">Montant devis (€ HT)</label>
                                                <input type="number" step="0.01" min="0" name="quote_amount" class="form-control form-control-sm" placeholder="0.00" value="{{ old('quote_amount', $eqRep->quote_amount) }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">Date envoi devis client</label>
                                                <input type="text" name="quote_sent_date" class="form-control form-control-sm flatpickr-date" placeholder="JJ/MM/AAAA" value="{{ old('quote_sent_date', $eqRep->quote_sent_date ? $eqRep->quote_sent_date->format('d/m/Y') : '') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">État devis client</label>
                                                <select name="quote_signed" class="form-select form-select-sm">
                                                    <option value="" {{ $eqRep->quote_signed === null ? 'selected' : '' }}>En attente de réponse</option>
                                                    <option value="1" {{ $eqRep->quote_signed === true ? 'selected' : '' }}>Devis signé / Accepté</option>
                                                    <option value="0" {{ $eqRep->quote_signed === false ? 'selected' : '' }}>Devis refusé</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Date signature devis</label>
                                                <input type="text" name="quote_signed_date" class="form-control form-control-sm flatpickr-date" placeholder="JJ/MM/AAAA" value="{{ old('quote_signed_date', $eqRep->quote_signed_date ? $eqRep->quote_signed_date->format('d/m/Y') : '') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Amen Contract Sub-fields -->
                                    <div class="warranty-group warranty-group-amen_contract" id="warranty_group_amen_contract_{{ $eqRep->id }}" style="{{ $currentWarrantyCode === 'amen_contract' ? '' : 'display: none;' }}">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">N° DECT remplacé</label>
                                                <input type="text" name="amen_replaced_serial" class="form-control form-control-sm font-monospace" placeholder="S/N remplacé..." value="{{ old('amen_replaced_serial', $eqRep->amen_replaced_serial) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Date envoi remplacement</label>
                                                <input type="text" name="amen_sent_date" class="form-control form-control-sm flatpickr-date" placeholder="JJ/MM/AAAA" value="{{ old('amen_sent_date', $eqRep->amen_sent_date ? $eqRep->amen_sent_date->format('d/m/Y') : '') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Matériel de prêt S/N</label>
                                                <input type="text" name="loan_serial_number" class="form-control form-control-sm font-monospace" placeholder="S/N prêt..." value="{{ old('loan_serial_number', $eqRep->loan_serial_number) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Date envoi prêt</label>
                                                <input type="text" name="loan_sent_date" class="form-control form-control-sm flatpickr-date" placeholder="JJ/MM/AAAA" value="{{ old('loan_sent_date', $eqRep->loan_sent_date ? $eqRep->loan_sent_date->format('d/m/Y') : '') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. Section Expédition & Retour Fournisseur -->
                            <div class="card mb-4 border shadow-none bg-white">
                                <div class="card-header bg-light py-2">
                                    <h6 class="fw-bold text-primary mb-0 small text-uppercase">
                                        <i class="bi bi-truck me-1"></i> Étape 3 : Expédition Fournisseur
                                    </h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="modal_dispatch_date_{{ $eqRep->id }}" class="form-label small fw-semibold">
                                                Date d'envoi fournisseur
                                            </label>
                                            <input type="text" name="supplier_dispatch_date" id="modal_dispatch_date_{{ $eqRep->id }}" class="form-control form-control-sm modal-dispatch-date flatpickr-date" data-eq-id="{{ $eqRep->id }}" placeholder="JJ/MM/AAAA" value="{{ old('supplier_dispatch_date', $eqRep->supplier_dispatch_date ? $eqRep->supplier_dispatch_date->format('d/m/Y') : '') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="modal_return_date_{{ $eqRep->id }}" class="form-label small fw-semibold d-flex align-items-center justify-content-between">
                                                <span>Date retour fournisseur</span>
                                                <i class="bi bi-info-circle text-muted" data-bs-toggle="tooltip" title="Modifiable si la date d'envoi fournisseur est renseignée."></i>
                                            </label>
                                            @php
                                                $hasDispatchDate = !empty($eqRep->supplier_dispatch_date);
                                            @endphp
                                            <input type="text" name="supplier_return_date" id="modal_return_date_{{ $eqRep->id }}" class="form-control form-control-sm modal-return-date flatpickr-date" data-eq-id="{{ $eqRep->id }}" placeholder="JJ/MM/AAAA" value="{{ old('supplier_return_date', $eqRep->supplier_return_date ? $eqRep->supplier_return_date->format('d/m/Y') : '') }}" {{ $hasDispatchDate ? '' : 'disabled' }}>
                                            <div class="small text-muted return-date-hint mt-1" id="return_date_hint_{{ $eqRep->id }}" style="{{ $hasDispatchDate ? 'display: none;' : '' }}">
                                                <i class="bi bi-lock me-1"></i>Renseignez la date d'envoi pour activer ce champ.
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="modal_diagnosis_id_{{ $eqRep->id }}" class="form-label small fw-semibold">Diagnostic réparateur</label>
                                            <select name="repair_diagnosis_id" id="modal_diagnosis_id_{{ $eqRep->id }}" class="form-select form-select-sm">
                                                <option value="">-- Non spécifié --</option>
                                                @foreach($repairDiagnosesCollection as $rd)
                                                    <option value="{{ $rd->id }}" {{ ((string)$eqRep->repair_diagnosis_id === (string)$rd->id || $eqRep->repair_status === $rd->code) ? 'selected' : '' }}>
                                                        {{ $rd->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 4. Section Expédition Client & Clôture -->
                            <div class="card border shadow-none bg-white">
                                <div class="card-header bg-light py-2">
                                    <h6 class="fw-bold text-primary mb-0 small text-uppercase">
                                        <i class="bi bi-send-check me-1"></i> Étape 4 : Livraison Client & Commentaires
                                    </h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-semibold">Date expédition chez le client</label>
                                            <input type="text" name="customer_delivery_date" class="form-control form-control-sm flatpickr-date" placeholder="JJ/MM/AAAA" value="{{ old('customer_delivery_date', $eqRep->customer_delivery_date ? $eqRep->customer_delivery_date->format('d/m/Y') : '') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-semibold">Réf / Bon de livraison Odoo</label>
                                            <input type="text" name="odoo_delivery_ref" class="form-control form-control-sm font-monospace" placeholder="Ex: WH/OUT/00124" value="{{ old('odoo_delivery_ref', $eqRep->odoo_delivery_ref) }}">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="form-label small fw-semibold">Commentaires libres SAV</label>
                                        <textarea name="comments" rows="2" class="form-control form-control-sm" placeholder="Remarques et détails SAV...">{{ old('comments', $eqRep->comments) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-lg me-1"></i>{{ __('locale.cancel') }}
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @empty
        <div class="alert alert-info">Aucun équipement enregistré dans ce dossier SAV.</div>
    @endforelse
</div>
@endsection

@section('scripts')
<script src="{{ asset('libs/flatpickr/flatpickr.js') }}"></script>
@if($canEdit)
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialisation Flatpickr format dd/mm/YYYY
    if (typeof flatpickr !== 'undefined') {
        flatpickr('.flatpickr-date', {
            dateFormat: 'd/m/Y',
            allowInput: true,
            monthSelectorType: 'static'
        });
    }

    // 1. Gestion dynamique du type de garantie dans les modals
    document.querySelectorAll('.modal-warranty-select').forEach(function (selectEl) {
        function updateWarrantyGroups() {
            const eqId = selectEl.dataset.eqId;
            const selectedOpt = selectEl.options[selectEl.selectedIndex];
            const code = selectedOpt ? selectedOpt.getAttribute('data-code') : '';

            const underWarrantyGroup = document.getElementById('warranty_group_under_warranty_' + eqId);
            const outOfWarrantyGroup = document.getElementById('warranty_group_out_of_warranty_' + eqId);
            const amenContractGroup = document.getElementById('warranty_group_amen_contract_' + eqId);

            if (underWarrantyGroup) underWarrantyGroup.style.display = (code === 'under_warranty') ? 'block' : 'none';
            if (outOfWarrantyGroup) outOfWarrantyGroup.style.display = (code === 'out_of_warranty') ? 'block' : 'none';
            if (amenContractGroup) amenContractGroup.style.display = (code === 'amen_contract') ? 'block' : 'none';
        }

        selectEl.addEventListener('change', updateWarrantyGroups);
    });

    // 2. Gestion de la date d'envoi et de retour fournisseur :
    // "if the sent date is preset then the user can change the received from supplier date."
    document.querySelectorAll('.modal-dispatch-date').forEach(function (dispatchInput) {
        const eqId = dispatchInput.dataset.eqId;
        const returnInput = document.getElementById('modal_return_date_' + eqId);
        const hintEl = document.getElementById('return_date_hint_' + eqId);

        function checkDispatchDate() {
            const hasValue = dispatchInput.value && dispatchInput.value.trim() !== '';
            if (returnInput) {
                returnInput.disabled = !hasValue;
                if (!hasValue) {
                    returnInput.value = '';
                    if (returnInput._flatpickr) {
                        returnInput._flatpickr.clear();
                    }
                }
            }
            if (hintEl) {
                hintEl.style.display = hasValue ? 'none' : 'block';
            }
        }

        dispatchInput.addEventListener('input', checkDispatchDate);
        dispatchInput.addEventListener('change', checkDispatchDate);
    });
});
</script>
@endif
@endsection
