@extends('layouts.app')
@section('portal-module', 'contracts')
@section('portal-page', 'show')
@section('portal-kind', 'record')
@section('portal-family', 'relations')
@section('portal-icon', 'users')


@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('contracts.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.contracts.details') }}</h1>
    </div>
@endsection

@section('page-actions')
    <a href="{{ route('contracts.edit', $contract) }}" class="btn btn-warning">
        <i class="bi bi-pencil-square me-1"></i> {{ __('locale.contracts.edit') }}
    </a>
@endsection

@section('content')
	<div class="container-xxl">

		<!-- Contract Main Summary Card -->
		<div class="module-record-card card mb-4 border-start border-4 border-primary">
			<div class="card-body">
				<div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
					<div>
						<div class="d-flex align-items-center gap-2 mb-2">
							<h4 class="card-title fw-bold mb-0">
								{{ $contract->contract_number ?: ($contract->contract_name ?: '#' . $contract->id) }}
							</h4>
							@if($contract->odoo_id)
								@php
									$odooBaseUrl = rtrim(config('services.odoo.url') ?? '', '/');
									$odooLink = $odooBaseUrl ? "{$odooBaseUrl}/web#id={$contract->odoo_id}&model=sale.order&view_type=form" : null;
								@endphp
								@if($odooLink)
									<a href="{{ $odooLink }}" target="_blank" class="badge bg-primary text-white text-decoration-none px-2 py-1" title="{{ __('locale.contracts.view_in_odoo') }}">
										<i class="bi bi-box-arrow-up-right me-1"></i> Odoo #{{ $contract->odoo_id }}
									</a>
								@else
									<span class="badge bg-primary text-white px-2 py-1">Odoo #{{ $contract->odoo_id }}</span>
								@endif
							@endif
						</div>
						<p class="text-muted mb-2">
							<i class="bi bi-building me-1"></i> <strong>{{ __('locale.contracts.client') }}:</strong>
							@if($contract->client)
								<a href="{{ route('clients.show', $contract->client) }}" class="fw-semibold text-primary text-decoration-none">
									{{ $contract->client->raison_sociale }}
								</a>
							@else
								<span class="text-muted">N/A</span>
							@endif
							@if($contract->company)
								<span class="ms-3 badge bg-light text-dark border">
									<i class="bi bi-geo-alt me-1"></i>{{ __('locale.contracts.company') }}: {{ $contract->company->name }}
								</span>
							@endif
						</p>
					</div>

					<div class="text-md-end">
						@php
							$statusValue = $contract->calculateStatus();
							$badgeClass = match ($statusValue) {
							    'avec_contrat' => 'badge-matte-success',
							    'contrat_expire' => 'badge-matte-danger',
							    'contrat_resilie' => 'badge-matte-warning',
							    default => 'badge-matte-secondary',
							};
						@endphp
						<div class="mb-1">
							<span class="badge {{ $badgeClass }} fs-6">
								{{ __('locale.contract_status.' . $statusValue) }}
							</span>
						</div>
						@if($contract->subscription_state)
							<span class="badge bg-light text-muted border small">
								{{ __('locale.contracts.odoo_state') }}: {{ $contract->subscription_state_label }}
							</span>
						@endif
					</div>
				</div>

				<hr class="my-3">

				<div class="row g-3">
					<div class="col-sm-6 col-md-3">
						<div class="p-2 border rounded bg-light">
							<small class="text-muted d-block text-uppercase fw-semibold">{{ __('locale.contracts.amount_untaxed') }}</small>
							<span class="fs-6 fw-bold text-dark font-monospace">
								{{ $contract->amount_untaxed !== null ? number_format($contract->amount_untaxed, 2, ',', ' ') . ' ' . ($contract->currency ?: '€') : '—' }}
							</span>
						</div>
					</div>
					<div class="col-sm-6 col-md-3">
						<div class="p-2 border rounded bg-light">
							<small class="text-muted d-block text-uppercase fw-semibold">{{ __('locale.contracts.amount_total') }}</small>
							<span class="fs-6 fw-bold text-dark font-monospace">
								{{ $contract->amount_total !== null ? number_format($contract->amount_total, 2, ',', ' ') . ' ' . ($contract->currency ?: '€') : '—' }}
							</span>
						</div>
					</div>
					<div class="col-sm-6 col-md-3">
						<div class="p-2 border rounded bg-light">
							<small class="text-muted d-block text-uppercase fw-semibold">{{ __('locale.contracts.recurring_plan') }}</small>
							<span class="fs-6 fw-semibold text-dark">
								{{ $contract->recurring_plan_name ?: ($contract->template->contract_name ?? '—') }}
							</span>
						</div>
					</div>
					<div class="col-sm-6 col-md-3">
						<div class="p-2 border rounded bg-light">
							<small class="text-muted d-block text-uppercase fw-semibold">{{ __('locale.contracts.next_invoice_date') }}</small>
							<span class="fs-6 fw-semibold text-dark font-monospace">
								{{ $contract->next_invoice_date ? $contract->next_invoice_date->format('d/m/Y') : '—' }}
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>

		<ul class="nav nav-tabs mb-3" id="contractDetailsTabs" role="tablist">
			<li class="nav-item" role="presentation">
				<button class="nav-link active" id="lines-tab" data-bs-toggle="tab" data-bs-target="#lines" type="button" role="tab">
					<i class="bi bi-box-seam me-1"></i> {{ __('locale.contracts.tab_lines') }} ({{ $contract->lines->count() }})
				</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="terms-tab" data-bs-toggle="tab" data-bs-target="#terms" type="button" role="tab">
					<i class="bi bi-file-earmark-text me-1"></i> {{ __('locale.contracts.tab_terms') }}
				</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="gti-tab" data-bs-toggle="tab" data-bs-target="#gti" type="button" role="tab">
					<i class="bi bi-speedometer me-1"></i> {{ __('locale.contracts.tab_gti') }}
				</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">
					<i class="bi bi-paperclip me-1"></i> {{ __('locale.contracts.tab_documents') }} ({{ $contract->documents->count() }})
				</button>
			</li>
		</ul>

		<div class="tab-content" id="contractTabsContent">
			<!-- 1. Odoo Subscription Lines / Articles Tab -->
			<div class="tab-pane fade show active" id="lines" role="tabpanel" aria-labelledby="lines-tab">
				<div class="module-record-card card shadow-sm">
					<div class="card-header bg-light d-flex justify-content-between align-items-center">
						<h6 class="mb-0 fw-bold"><i class="bi bi-list-check me-2 text-primary"></i>{{ __('locale.contracts.odoo_lines_title') }}</h6>
						<span class="badge bg-secondary">{{ $contract->lines->count() }}</span>
					</div>
					<div class="table-responsive">
						<table class="table table-hover align-middle mb-0">
							<thead class="table-light">
								<tr>
									<th>{{ __('locale.lbl_reference') }}</th>
									<th>{{ __('locale.contracts.item_service') }}</th>
									<th>{{ __('locale.contracts.associated_equipment') }}</th>
									<th class="text-center">{{ __('locale.lbl_quantity') }}</th>
									<th class="text-end">{{ __('locale.contracts.unit_price_ht') }}</th>
									<th class="text-center">{{ __('locale.contracts.discount') }}</th>
									<th class="text-end">{{ __('locale.contracts.subtotal_ht') }}</th>
								</tr>
							</thead>
							<tbody>
								@forelse ($contract->lines as $line)
									<tr>
										<td class="font-monospace fw-semibold">{{ $line->product_reference ?: '—' }}</td>
										<td>
											<div class="fw-semibold text-dark">{{ $line->product_name }}</div>
											@if($line->odoo_id)
												<small class="text-muted font-monospace">Odoo line #{{ $line->odoo_id }}</small>
											@endif
										</td>
										<td>
											@if($line->equipment)
												<a href="{{ route('equipments.show', $line->equipment) }}" class="badge bg-light text-primary border text-decoration-none" title="{{ __('locale.equipment_show.lbl_tab_general') ?? 'Voir l\'article' }}">
													<i class="bi bi-laptop me-1"></i>{{ $line->equipment->name }}
												</a>
											@else
												<span class="text-muted small">—</span>
											@endif
										</td>
										<td class="text-center font-monospace">{{ $line->quantity }}</td>
										<td class="text-end font-monospace">{{ number_format($line->unit_price, 2, ',', ' ') }} {{ $contract->currency ?: '€' }}</td>
										<td class="text-center font-monospace">{{ $line->discount > 0 ? $line->discount . '%' : '—' }}</td>
										<td class="text-end font-monospace fw-bold">{{ number_format($line->subtotal, 2, ',', ' ') }} {{ $contract->currency ?: '€' }}</td>
									</tr>
								@empty
									<tr>
										<td colspan="7" class="text-center py-4 text-muted">
											<i class="bi bi-inbox fs-3 d-block mb-1 text-muted opacity-50"></i>
											{{ __('locale.contracts.no_lines') }}
										</td>
									</tr>
								@endforelse
							</tbody>
							@if($contract->lines->isNotEmpty())
								<tfoot class="table-light font-monospace fw-bold">
									<tr>
										<td colspan="6" class="text-end">{{ __('locale.contracts.total_lines_ht') }}:</td>
										<td class="text-end">{{ number_format($contract->lines->sum('subtotal'), 2, ',', ' ') }} {{ $contract->currency ?: '€' }}</td>
									</tr>
								</tfoot>
							@endif
						</table>
					</div>
				</div>
			</div>

			<!-- 2. Terms Tab -->
			<div class="tab-pane fade" id="terms" role="tabpanel" aria-labelledby="terms-tab">
				<div class="module-record-card card shadow-sm mb-3">
					<div class="card-body">
						<div class="row mb-3">
							<div class="col-md-6"><strong>{{ __('locale.contracts.terms.contract_name') }}:</strong> {{ $contract->resolvedValue('contract_name') }}</div>
							<div class="col-md-6"><strong>{{ __('locale.contracts.terms.nb_vps_per_year') }}:</strong> {{ $contract->resolvedValue('nb_vps_per_year') }}</div>
						</div>
						<div class="row mb-3">
							<div class="col-md-6"><strong>{{ __('locale.contracts.terms.date_start') }}:</strong> {{ optional($contract->resolvedValue('date_debut_validite'))->format('d/m/Y') }}</div>
							<div class="col-md-6"><strong>{{ __('locale.contracts.terms.date_end') }}:</strong> {{ optional($contract->resolvedValue('date_fin_validite'))->format('d/m/Y') }}</div>
						</div>
						<div class="row mb-3">
							<div class="col-md-6"><strong>{{ __('locale.contracts.terms.renewal_duration_months') }}:</strong> {{ $contract->resolvedValue('duree_renouvellement_en_mois') }}</div>
							<div class="col-md-6"><strong>{{ __('locale.contracts.terms.recurrence_count') }}:</strong> {{ $contract->resolvedValue('nb_de_recurrences') }}</div>
						</div>
						<div class="row mb-3">
							<div class="col-md-6"><strong>{{ __('locale.contracts.terms.notice_months') }}:</strong> {{ $contract->resolvedValue('preavis_en_mois') }}</div>
							<div class="col-md-6"><strong>{{ __('locale.contracts.terms.tacite_reconduction') }}:</strong> {{ $contract->resolvedValue('tacite_reconduction') ? __('locale.yes') : __('locale.no') }}</div>
						</div>
						<div class="row mb-3">
							<div class="col-md-6"><strong>{{ __('locale.contracts.terms.astreinte') }}:</strong> {{ $contract->resolvedValue('astreinte') ? __('locale.yes') : __('locale.no') }}</div>
						</div>
						<div class="mb-3">
							<strong>{{ __('locale.contracts.terms.divers') }}:</strong>
							<div class="border rounded p-2 bg-light mt-1">{{ $contract->resolvedValue('divers') ?: '—' }}</div>
						</div>
					</div>
				</div>
			</div>

			<!-- 3. GTI/GTR Tab -->
			<div class="tab-pane fade" id="gti" role="tabpanel" aria-labelledby="gti-tab">
				<div class="module-record-card card shadow-sm mb-3">
					<div class="card-body">
						<div class="row mb-3">
							<div class="col-md-6"><strong>{{ __('locale.contracts.gti.panne_mineure_gti') }}:</strong> {{ $contract->resolvedValue('panne_mineure_gti') }}</div>
							<div class="col-md-6"><strong>{{ __('locale.contracts.gti.panne_mineure_gtr') }}:</strong> {{ $contract->resolvedValue('panne_mineure_gtr') }}</div>
						</div>
						<div class="row mb-3">
							<div class="col-md-6"><strong>{{ __('locale.contracts.gti.panne_grave_gti') }}:</strong> {{ $contract->resolvedValue('panne_grave_gti') }}</div>
							<div class="col-md-6"><strong>{{ __('locale.contracts.gti.panne_grave_gtr') }}:</strong> {{ $contract->resolvedValue('panne_grave_gtr') }}</div>
						</div>
						<div class="row mb-3">
							<div class="col-md-6"><strong>{{ __('locale.contracts.gti.panne_majeure_gti') }}:</strong> {{ $contract->resolvedValue('panne_majeure_gti') }}</div>
							<div class="col-md-6"><strong>{{ __('locale.contracts.gti.panne_majeure_gtr') }}:</strong> {{ $contract->resolvedValue('panne_majeure_gtr') }}</div>
						</div>
						<div class="row mb-3">
							<div class="col-md-6"><strong>{{ __('locale.contracts.gti.formation_gti') }}:</strong> {{ $contract->resolvedValue('formation_gti') }}</div>
							<div class="col-md-6"><strong>{{ __('locale.contracts.gti.formation_gtr') }}:</strong> {{ $contract->resolvedValue('formation_gtr') }}</div>
						</div>
						<div class="row mb-3">
							<div class="col-md-6"><strong>{{ __('locale.contracts.gti.sans_objet_gti') }}:</strong> {{ $contract->resolvedValue('sans_objet_gti') }}</div>
							<div class="col-md-6"><strong>{{ __('locale.contracts.gti.sans_objet_gtr') }}:</strong> {{ $contract->resolvedValue('sans_objet_gtr') }}</div>
						</div>
					</div>
				</div>
			</div>

			<!-- 4. Documents & Clauses Tab -->
			<div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
				<div class="module-record-card card shadow-sm mb-3">
					<div class="card-body">
						<h5>{{ __('locale.contracts.documents') }}</h5>
						@if ($contract->documents->isEmpty())
							<p class="text-muted">{{ __('locale.contracts.no_documents') }}</p>
						@else
							<ul class="list-group mb-3">
								@foreach ($contract->documents as $document)
									<li class="list-group-item d-flex justify-content-between align-items-center">
										<span><i class="bi bi-file-earmark-pdf me-2 text-danger"></i>{{ $document->document_name }}</span>
										@if($document->document_path)
											<a href="{{ Storage::url($document->document_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
												<i class="bi bi-download me-1"></i> Télécharger
											</a>
										@endif
									</li>
								@endforeach
							</ul>
						@endif

						<h5>{{ __('locale.contracts.preset_clauses') }}</h5>
						@if ($contract->presetClauses->isEmpty())
							<p class="text-muted">{{ __('locale.contracts.no_preset_clauses') }}</p>
						@else
							<ul class="list-group mb-3">
								@foreach ($contract->presetClauses->sortBy('id') as $clause)
									<li class="list-group-item">{{ $clause->template->title ?? __('locale.contracts.preset_clauses') }}</li>
								@endforeach
							</ul>
						@endif

						<h5>{{ __('locale.contracts.custom_clauses') }}</h5>
						@if ($contract->customClauses->isEmpty())
							<p class="text-muted">{{ __('locale.contracts.no_custom_clauses') }}</p>
						@else
							<ul class="list-group mb-3">
								@foreach ($contract->customClauses->sortBy('id') as $clause)
									<li class="list-group-item">
										<strong>{{ $clause->title ?? __('locale.contracts.custom_clauses') }}</strong>
										<div class="mt-1">{{ $clause->body }}</div>
									</li>
								@endforeach
							</ul>
						@endif

						<h5>{{ __('locale.contracts.equipment') }}</h5>
						@if ($contract->equipmentTypes->isEmpty())
							<p class="text-muted">{{ __('locale.contracts.no_equipment') }}</p>
						@else
							<ul class="list-group">
								@foreach ($contract->equipmentTypes as $equipment)
									<li class="list-group-item d-flex justify-content-between align-items-center">
										<span>{{ $equipment->equipmentType->name ?? __('locale.contracts.equipment') }}</span>
										<span class="text-muted">{{ $equipment->comment }}</span>
									</li>
								@endforeach
							</ul>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
