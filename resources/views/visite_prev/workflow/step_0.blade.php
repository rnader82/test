@extends('layouts.app')

@section('content')
	<div class="container">
		<h3 class="mb-2">{{ __('visite_prev.step_0_title') }}</h3>
		<p class="text-muted">{{ __('visite_prev.point_bilan_intro', [
		    'start' => $summaryPeriod['start']->format('d/m/Y'),
		    'end' => $summaryPeriod['end']->format('d/m/Y'),
		]) }}
		</p>

		@if (session('success'))
			<div class="alert alert-success">{{ session('success') }}</div>
		@endif

		<div class="row g-3 mb-4">
			<div class="col-md-4 col-lg-2">
				<div class="card h-100">
					<div class="card-body">
						<p class="text-muted mb-1">{{ __('visite_prev.card_vp') }}</p>
						<h4 class="fw-bold mb-0">{{ number_format($overviewStats['vp']) }}</h4>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-lg-2">
				<div class="card h-100">
					<div class="card-body">
						<p class="text-muted mb-1">{{ __('visite_prev.card_intervention') }}</p>
						<h4 class="fw-bold mb-0">{{ number_format($overviewStats['interventions']) }}</h4>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-lg-2">
				<div class="card h-100">
					<div class="card-body">
						<p class="text-muted mb-1">{{ __('visite_prev.card_sav') }}</p>
						<h4 class="fw-bold mb-1">{{ number_format($overviewStats['sav']) }}</h4>
                        <div class="small text-muted">{{ __('visite_prev.repair_shipment_count', ['count' => number_format($overviewStats['sav'])]) }}</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-lg-2">
				<div class="card h-100">
					<div class="card-body">
						<p class="text-muted mb-1">{{ __('visite_prev.card_repairs_equipment') }}</p>
						<h4 class="fw-bold mb-1">{{ number_format($overviewStats['repairs_equipment']) }}</h4>
                        <div class="d-flex flex-column gap-1 mt-1">
                            <span class="badge bg-success p-1 small" style="font-size: 0.7rem;">{{ __('visite_prev.equipment_repaired_count', ['count' => number_format($overviewStats['repaired'])]) }}</span>
                            <span class="badge bg-danger p-1 small" style="font-size: 0.7rem;">{{ __('visite_prev.equipment_irreparable_count', ['count' => number_format($overviewStats['irreparable'])]) }}</span>
                        </div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-lg-2">
				<div class="card h-100">
					<div class="card-body">
						<p class="text-muted mb-1">{{ __('visite_prev.card_tickets') }}</p>
						<h4 class="fw-bold mb-0">{{ number_format($overviewStats['tickets']) }}</h4>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-lg-2">
				<div class="card h-100">
					<div class="card-body">
						<p class="text-muted mb-1">{{ __('visite_prev.card_quotes') }}</p>
						<h4 class="fw-bold mb-0">{{ number_format($overviewStats['quotes']) }}</h4>
					</div>
				</div>
			</div>
		</div>

		<form method="POST" action="{{ route('vp-visit-workflow.update', $vp_visit->id) }}?step={{ $step }}" enctype="multipart/form-data">
			@csrf
			@method('PUT')

			<ul class="nav nav-tabs" id="point-bilan-tabs" role="tablist">
				<li class="nav-item" role="presentation">
					<button
						class="nav-link active"
						id="vp-tab"
						data-bs-toggle="tab"
						data-bs-target="#vp"
						type="button"
						role="tab"
						aria-controls="vp"
						aria-selected="true">
						{{ __('visite_prev.card_vp') }}
					</button>
				</li>
				<li class="nav-item" role="presentation">
					<button
						class="nav-link"
						id="intervention-tab"
						data-bs-toggle="tab"
						data-bs-target="#intervention"
						type="button"
						role="tab"
						aria-controls="intervention"
						aria-selected="false">
						{{ __('visite_prev.card_intervention') }}
					</button>
				</li>
				<li class="nav-item" role="presentation">
					<button
						class="nav-link"
						id="sav-tab"
						data-bs-toggle="tab"
						data-bs-target="#sav"
						type="button"
						role="tab"
						aria-controls="sav"
						aria-selected="false">
						{{ __('visite_prev.card_sav') }}
					</button>
				</li>
				<li class="nav-item" role="presentation">
					<button
						class="nav-link"
						id="repairs-tab"
						data-bs-toggle="tab"
						data-bs-target="#repairs"
						type="button"
						role="tab"
						aria-controls="repairs"
						aria-selected="false">
						{{ __('visite_prev.card_repairs_equipment') }}
					</button>
				</li>
				<li class="nav-item" role="presentation">
					<button
						class="nav-link"
						id="tickets-tab"
						data-bs-toggle="tab"
						data-bs-target="#tickets"
						type="button"
						role="tab"
						aria-controls="tickets"
						aria-selected="false">
						{{ __('visite_prev.card_tickets') }}
					</button>
				</li>
				<li class="nav-item" role="presentation">
					<button
						class="nav-link"
						id="quotes-tab"
						data-bs-toggle="tab"
						data-bs-target="#quotes"
						type="button"
						role="tab"
						aria-controls="quotes"
						aria-selected="false">
						{{ __('visite_prev.card_quotes') }}
					</button>
				</li>
				<li class="nav-item" role="presentation">
					<button
						class="nav-link"
						id="plans-tab"
						data-bs-toggle="tab"
						data-bs-target="#plans"
						type="button"
						role="tab"
						aria-controls="plans"
						aria-selected="false">
						{{ __('visite_prev.tab_plans_title') }}
					</button>
				</li>
			</ul>

			<div class="tab-content pt-3" id="point-bilan-tab-content">
				<div class="tab-pane fade show active" id="vp" role="tabpanel" aria-labelledby="vp-tab">
					<h5 class="mb-3">{{ __('visite_prev.tab_vp_title') }}</h5>
					<p class="text-muted">{{ __('visite_prev.tab_vp_description') }}</p>
				</div>
				<div class="tab-pane fade" id="intervention" role="tabpanel" aria-labelledby="intervention-tab">
					<h5 class="mb-3">{{ __('visite_prev.tab_intervention_title') }}</h5>
					<p class="text-muted">{{ __('visite_prev.tab_intervention_description') }}</p>

					<div class="table-responsive">
						<table class="table table-sm align-middle">
							<thead>
								<tr>
									<th class="text-center">{{ __('visite_prev.intervention_validated') }}</th>
									<th>#</th>
									<th>{{ __('visite_prev.intervention_subject') }}</th>
									<th>{{ __('visite_prev.intervention_client') }}</th>
									<th>{{ __('visite_prev.intervention_date') }}</th>
									<th>{{ __('visite_prev.intervention_status') }}</th>
									<th>{{ __('visite_prev.intervention_validated_at') }}</th>
									<th>{{ __('visite_prev.intervention_comment') }}</th>
								</tr>
							</thead>
							<tbody>
								@forelse($interventions as $intervention)
									@php
										$interventionFeedbackRow = data_get($interventionFeedback, (string) $intervention->id, []);
										$interventionValidated = (bool) data_get($interventionFeedbackRow, 'validated');
										$interventionValidatedAt = data_get($interventionFeedbackRow, 'validated_at');
										$interventionValidatedAtDisplay = $interventionValidatedAt ? \Illuminate\Support\Carbon::parse($interventionValidatedAt)->format('d/m/Y H:i') : null;
										$interventionComment = data_get($interventionFeedbackRow, 'comment', '');
									@endphp
									<tr>
										<td class="text-center">
											<input type="hidden" name="interventions[{{ $intervention->id }}][validated]" value="0">
											<input
												type="hidden"
												name="interventions[{{ $intervention->id }}][validated_at]"
												id="intervention-{{ $intervention->id }}-validated-at"
												value="{{ $interventionValidatedAt }}">
											<input
												type="hidden"
												name="interventions[{{ $intervention->id }}][comment]"
												id="intervention-{{ $intervention->id }}-comment"
												value="{{ $interventionComment }}">
											<input
												type="checkbox"
												class="form-check-input intervention-validated-checkbox"
												name="interventions[{{ $intervention->id }}][validated]"
												value="1"
												data-intervention-id="{{ $intervention->id }}"
												{{ $interventionValidated ? 'checked' : '' }}>
										</td>
										<td>{{ $intervention->id }}</td>
										<td>{{ $intervention->subject ?? '—' }}</td>
										<td>{{ $intervention->client->raison_sociale ?? __('locale.not_available') }}</td>
										<td>{{ optional($intervention->created_at)->format('d/m/Y') ?? '—' }}</td>
										<td>
											<span class="badge bg-light text-dark">{{ $intervention->status->translated_name ?? ($intervention->status->name ?? __('locale.not_available')) }}</span>
										</td>
										<td>
											<span
												id="intervention-{{ $intervention->id }}-validated-at-display"
												class="{{ $interventionValidatedAt ? 'badge bg-light text-dark' : 'text-muted' }}">
												{{ $interventionValidatedAtDisplay ?? '—' }}
											</span>
										</td>
										<td>
											<div class="d-flex align-items-center gap-2">
												<span
													id="intervention-{{ $intervention->id }}-comment-preview"
													class="text-truncate"
													style="max-width: 240px;">
													{{ $interventionComment ?: __('visite_prev.intervention_comment_placeholder') }}
												</span>
												<button
													type="button"
													class="btn btn-sm btn-outline-secondary intervention-comment-button"
													data-bs-toggle="modal"
													data-bs-target="#interventionCommentModal"
													data-intervention-id="{{ $intervention->id }}"
													data-intervention-label="#{{ $intervention->id }} — {{ $intervention->subject ?? __('locale.not_available') }}">
													{{ __('visite_prev.intervention_comment') }}
												</button>
											</div>
										</td>
									</tr>
								@empty
									<tr>
										<td colspan="8" class="text-center text-muted">{{ __('visite_prev.no_interventions_found') }}</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>

					<div class="d-flex justify-content-end">
						{{ $interventions->withQueryString()->links() }}
					</div>
				</div>
				<div class="tab-pane fade" id="sav" role="tabpanel" aria-labelledby="sav-tab">
					<h5 class="mb-3">{{ __('visite_prev.tab_sav_title') }}</h5>
					<p class="text-muted">{{ __('visite_prev.tab_sav_description') }}</p>

					<div class="table-responsive">
						<table class="table table-sm align-middle">
							<thead>
								<tr>
									<th>#</th>
									<th>{{ __('visite_prev.sav_ticket') }}</th>
									<th>{{ __('visite_prev.sav_client') }}</th>
									<th>{{ __('visite_prev.sav_status') }}</th>
									<th>{{ __('visite_prev.lbl_shipment_date') }}</th>
                                    <th>{{ __('visite_prev.lbl_reception_date') }}</th>
									<th>{{ __('visite_prev.sav_created_at') }}</th>
								</tr>
							</thead>
							<tbody>
								@forelse($repairDetails as $repair)
									<tr>
										<td>{{ $repair->id }}</td>
										<td>{{ $repair->issue->subject ?? '—' }}</td>
										<td>{{ $repair->issue->client->raison_sociale ?? '—' }}</td>
										<td>
											<span class="badge" style="background-color: {{ $repair->status_color }}; color: {{ $repair->status_text_color }};">
												{{ $repair->status_label }}
											</span>
										</td>
										<td>{{ optional($repair->created_at)->format('d/m/Y') ?? '—' }}</td>
                                        <td>{{ optional($repair->repair_date)->format('d/m/Y') ?? '—' }}</td>
										<td>{{ optional($repair->created_at)->format('d/m/Y') ?? '—' }}</td>
									</tr>
								@empty
									<tr>
										<td colspan="5" class="text-center text-muted">{{ __('visite_prev.no_sav_found') }}</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>

					<div class="d-flex justify-content-end">
						{{ $repairDetails->withQueryString()->links() }}
					</div>
				</div>
				<div class="tab-pane fade" id="repairs" role="tabpanel" aria-labelledby="repairs-tab">
					<div class="d-flex justify-content-between align-items-center mb-2">
						<div>
							<h5 class="mb-0">{{ __('visite_prev.tab_repairs_title') }}</h5>
							<small class="text-muted">{{ __('visite_prev.tab_repairs_description') }}</small>
						</div>
						<div class="text-end">
							<div class="fw-semibold">{{ __('visite_prev.card_repairs_equipment') }}: {{ number_format($overviewStats['repairs_equipment']) }}</div>
							<div class="text-muted">{{ __('visite_prev.repair_shipments', ['count' => number_format($overviewStats['sav'])]) }}</div>
						</div>
					</div>

					<div class="table-responsive">
						<table class="table table-sm align-middle">
							<thead>
								<tr>
									<th>#</th>
									<th>{{ __('visite_prev.equipment_repair_ticket') }}</th>
									<th>{{ __('visite_prev.equipment_repair_equipment') }}</th>
									<th>{{ __('visite_prev.equipment_repair_serial') }}</th>
									<th class="text-end">{{ __('visite_prev.equipment_repair_price') }}</th>
									<th>{{ __('locale.lbl_status') }}</th>
									<th>{{ __('visite_prev.created_at') }}</th>
								</tr>
							</thead>
							<tbody>
								@forelse($equipmentRepairs as $equipmentRepair)
									<tr>
										<td>{{ $equipmentRepair->id }}</td>
										<td>{{ optional($equipmentRepair->repair->issue)->subject ?? '—' }}</td>
										<td>{{ $equipmentRepair->equipment->name ?? '—' }}</td>
										<td>{{ $equipmentRepair->serial_number ?? '—' }}</td>
										<td class="text-end">{{ $equipmentRepair->price ?? '—' }}</td>
										<td>
                                            @if($equipmentRepair->repair && $equipmentRepair->repair->status)
                                                <span class="badge" style="background-color: {{ $equipmentRepair->repair->status_color }}; color: {{ $equipmentRepair->repair->status_text_color }};">
                                                    {{ $equipmentRepair->repair->status_label }}
                                                </span>
                                            @else
                                                —
                                            @endif
                                        </td>
										<td>{{ optional($equipmentRepair->created_at)->format('d/m/Y') ?? '—' }}</td>
									</tr>
								@empty
									<tr>
										<td colspan="6" class="text-center text-muted">{{ __('visite_prev.no_equipment_repairs_found') }}</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>

					<div class="d-flex justify-content-end">
						{{ $equipmentRepairs->withQueryString()->links() }}
					</div>
				</div>
				<div class="tab-pane fade" id="tickets" role="tabpanel" aria-labelledby="tickets-tab">
					<div class="d-flex justify-content-between align-items-center mb-2">
						<div>
							<h5 class="mb-1">{{ __('visite_prev.tab_tickets_title') }}</h5>
							<p class="text-muted mb-2">{{ __('visite_prev.tab_tickets_description') }}</p>
							<p class="fw-semibold mb-0">{{ __('visite_prev.ticket_count', ['count' => number_format($overviewStats['tickets'])]) }}</p>
						</div>
						<div class="text-end">
							<span class="badge bg-light text-dark">{{ __('visite_prev.ticket_feedback_hint') }}</span>
						</div>
					</div>

					<div class="table-responsive">
						<table class="table table-sm align-middle">
							<thead>
								<tr>
									<th class="text-center">{{ __('visite_prev.ticket_reviewed') }}</th>
									<th>{{ __('visite_prev.repair_ticket') }}</th>
									<th>{{ __('visite_prev.repair_client') }}</th>
									<th>{{ __('visite_prev.created_at') }}</th>
									<th>{{ __('visite_prev.ticket_checked_at') }}</th>
									<th>{{ __('visite_prev.ticket_comment_optional') }}</th>
								</tr>
							</thead>
							<tbody>
								@forelse($tickets as $ticket)
									@php
										$feedback = data_get($ticketFeedback, (string) $ticket->id, []);
										$checked = old('tickets.' . $ticket->id . '.checked', data_get($feedback, 'checked'));
										$checkedAt = data_get($feedback, 'checked_at');
										$checkedAtDisplay = $checkedAt ? \Illuminate\Support\Carbon::parse($checkedAt)->format('d/m/Y H:i') : null;
										$comment = old('tickets.' . $ticket->id . '.comment', data_get($feedback, 'comment'));
									@endphp
									<tr>
										<td class="text-center">
											<input type="hidden" name="tickets[{{ $ticket->id }}][checked]" value="0">
											<input type="hidden" name="tickets[{{ $ticket->id }}][checked_at]" id="ticket-{{ $ticket->id }}-checked-at" value="{{ $checkedAt }}">
											<input
												type="checkbox"
												class="form-check-input ticket-validated-checkbox"
												name="tickets[{{ $ticket->id }}][checked]"
												value="1"
												data-ticket-id="{{ $ticket->id }}"
												{{ $checked ? 'checked' : '' }}>
										</td>
										<td>
											<div class="fw-semibold">#{{ $ticket->id }} — {{ $ticket->subject }}</div>
											<div class="text-muted small">{{ __('visite_prev.ticket_subject_hint') }}</div>
										</td>
										<td>{{ $ticket->client->raison_sociale ?? __('locale.not_available') }}</td>
										<td>{{ optional($ticket->created_at)->format('d/m/Y') ?? '—' }}</td>
										<td>
											<span
												id="ticket-{{ $ticket->id }}-checked-at-display"
												class="{{ $checkedAt ? 'badge bg-light text-dark' : 'text-muted' }}">
												{{ $checkedAtDisplay ?? '—' }}
											</span>
										</td>
										<td>
											<input type="hidden" name="tickets[{{ $ticket->id }}][comment]" id="ticket-{{ $ticket->id }}-comment" value="{{ $comment }}">
											<div class="d-flex align-items-center gap-2">
												<span id="ticket-{{ $ticket->id }}-comment-preview" class="text-truncate" style="max-width: 240px;">
													{{ $comment ?: __('visite_prev.ticket_comment_placeholder') }}
												</span>
												<button
													type="button"
													class="btn btn-sm btn-outline-secondary ticket-comment-button"
													data-bs-toggle="modal"
													data-bs-target="#ticketCommentModal"
													data-ticket-id="{{ $ticket->id }}"
													data-ticket-label="#{{ $ticket->id }} — {{ $ticket->subject }}">
													{{ __('visite_prev.ticket_comment_optional') }}
												</button>
											</div>
										</td>
									</tr>
								@empty
									<tr>
										<td colspan="6" class="text-center text-muted">{{ __('visite_prev.no_tickets_found') }}</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>
				</div>
				<div class="tab-pane fade" id="quotes" role="tabpanel" aria-labelledby="quotes-tab">
					<h5 class="mb-1">{{ __('visite_prev.tab_quotes_title') }}</h5>
					<p class="text-muted mb-3">{{ __('visite_prev.tab_quotes_description') }}</p>
					<div class="row g-3">
						<div class="col-md-4">
							<div class="card h-100">
								<div class="card-body">
									<p class="text-muted mb-1">{{ __('visite_prev.quotes_accepted') }}</p>
									<h5 class="fw-bold mb-0">{{ number_format($quoteBreakdown['accepted']) }}</h5>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="card h-100">
								<div class="card-body">
									<p class="text-muted mb-1">{{ __('visite_prev.quotes_refused') }}</p>
									<h5 class="fw-bold mb-0">{{ number_format($quoteBreakdown['refused']) }}</h5>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="card h-100">
								<div class="card-body">
									<p class="text-muted mb-1">{{ __('visite_prev.quotes_no_response') }}</p>
									<h5 class="fw-bold mb-0">{{ number_format($quoteBreakdown['no_response']) }}</h5>
								</div>
							</div>
						</div>
					</div>

					<div class="table-responsive mt-3">
						<table class="table table-sm align-middle">
							<thead>
								<tr>
									<th>#</th>
									<th>{{ __('visite_prev.quote_reference') }}</th>
									<th>{{ __('visite_prev.repair_client') }}</th>
									<th>{{ __('visite_prev.quote_status') }}</th>
									<th>{{ __('visite_prev.quote_amount') }}</th>
									<th>{{ __('visite_prev.quote_date') }}</th>
								</tr>
							</thead>
							<tbody>
								@forelse($quotes as $quote)
									<tr>
										<td>{{ $quote->id }}</td>
										<td>{{ $quote->ref_interne ?? '—' }}</td>
										<td>{{ $quote->client->raison_sociale ?? '—' }}</td>
										<td>{{ $quote->statut ?? '—' }}</td>
										<td>{{ $quote->total_ttc ?? '—' }}</td>
										<td>{{ optional($quote->date_piece)->format('d/m/Y') ?? '—' }}</td>
									</tr>
								@empty
									<tr>
										<td colspan="6" class="text-center text-muted">{{ __('visite_prev.no_quotes_found') }}</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>

					<div class="d-flex justify-content-end">
						{{ $quotes->withQueryString()->links() }}
					</div>
				</div>
				<div class="tab-pane fade" id="plans" role="tabpanel" aria-labelledby="plans-tab">
					<h5 class="mb-1">{{ __('visite_prev.tab_plans_title') }}</h5>
					<p class="text-muted mb-3">{{ __('visite_prev.tab_plans_description') }}</p>

					<div class="card mb-4">
						<div class="card-body">
							<label for="building_plans" class="form-label">{{ __('visite_prev.upload_plan') }} (PDF)</label>
							<input type="file" name="building_plans[]" id="building_plans" class="form-control" accept="application/pdf" multiple>
						</div>
					</div>

					<div class="table-responsive">
						<table class="table table-sm align-middle">
							<thead>
								<tr>
									<th>{{ __('locale.name') }}</th>
									<th>{{ __('visite_prev.plan_uploaded_at') }}</th>
									<th class="text-end">{{ __('locale.actions') }}</th>
								</tr>
							</thead>
							<tbody>
								@forelse($vp_visit->building_plans ?? [] as $plan)
									<tr>
										<td>
											<i class="bi bi-file-earmark-pdf text-danger me-2"></i>
											{{ $plan['name'] }}
										</td>
										<td>{{ \Illuminate\Support\Carbon::parse($plan['uploaded_at'])->format('d/m/Y H:i') }}</td>
										<td class="text-end">
											<div class="btn-group btn-group-sm">
												<a href="{{ Storage::disk('public')->url($plan['path']) }}" target="_blank" class="btn btn-outline-primary">
													<i class="bi bi-eye"></i>
												</a>
												<a href="{{ Storage::disk('public')->url($plan['path']) }}" download class="btn btn-outline-primary">
													<i class="bi bi-download"></i>
												</a>
												<button type="button" class="btn btn-outline-danger btn-delete-plan" data-path="{{ $plan['path'] }}">
													<i class="bi bi-trash"></i>
												</button>
											</div>
										</td>
									</tr>
								@empty
									<tr>
										<td colspan="3" class="text-center text-muted">{{ __('visite_prev.no_plans_uploaded') }}</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>

					<div id="plans-deletions-container"></div>
				</div>
			</div>

			<div class="d-flex justify-content-end mt-4">
				<button type="submit" class="btn btn-primary">
					{{ __('visite_prev.next') }}
				</button>
			</div>
		</form>

		<div class="modal fade" id="interventionCommentModal" tabindex="-1" aria-labelledby="interventionCommentModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="interventionCommentModalLabel">{{ __('visite_prev.intervention_comment') }}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<p class="text-muted" id="interventionCommentModalContext"></p>
						<div class="mb-3">
							<label for="interventionCommentModalTextarea" class="form-label">{{ __('visite_prev.intervention_comment') }}</label>
							<textarea class="form-control" id="interventionCommentModalTextarea" rows="4" placeholder="{{ __('visite_prev.intervention_comment_placeholder') }}"></textarea>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('locale.cancel') }}</button>
						<button type="button" class="btn btn-primary" id="interventionCommentModalSave">{{ __('locale.save') }}</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="ticketCommentModal" tabindex="-1" aria-labelledby="ticketCommentModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="ticketCommentModalLabel">{{ __('visite_prev.ticket_comment_optional') }}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<p class="text-muted" id="ticketCommentModalContext"></p>
						<div class="mb-3">
							<label for="ticketCommentModalTextarea" class="form-label">{{ __('visite_prev.ticket_comment_optional') }}</label>
							<textarea class="form-control" id="ticketCommentModalTextarea" rows="4" placeholder="{{ __('visite_prev.ticket_comment_placeholder') }}"></textarea>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('locale.cancel') }}</button>
						<button type="button" class="btn btn-primary" id="ticketCommentModalSave">{{ __('locale.save') }}</button>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
	<script>
		document.addEventListener('DOMContentLoaded', () => {
			const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
			const formatNumber = (value) => String(value).padStart(2, '0');

			const formatHiddenDateTime = (date) => {
				const year = date.getFullYear();
				const month = formatNumber(date.getMonth() + 1);
				const day = formatNumber(date.getDate());
				const hours = formatNumber(date.getHours());
				const minutes = formatNumber(date.getMinutes());
				const seconds = formatNumber(date.getSeconds());

				return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
			};

			const formatDisplayDateTime = (date) => {
				const day = formatNumber(date.getDate());
				const month = formatNumber(date.getMonth() + 1);
				const year = date.getFullYear();
				const hours = formatNumber(date.getHours());
				const minutes = formatNumber(date.getMinutes());

				return `${day}/${month}/${year} ${hours}:${minutes}`;
			};

			document.querySelectorAll('.ticket-validated-checkbox').forEach((checkbox) => {
				checkbox.addEventListener('change', (event) => {
					const ticketId = event.target.dataset.ticketId;
					const checkedAtInput = document.getElementById(`ticket-${ticketId}-checked-at`);
					const checkedAtDisplay = document.getElementById(`ticket-${ticketId}-checked-at-display`);

					if (!checkedAtInput || !checkedAtDisplay) {
						return;
					}

					if (event.target.checked) {
						const now = new Date();
						checkedAtInput.value = formatHiddenDateTime(now);
						checkedAtDisplay.textContent = formatDisplayDateTime(now);
						checkedAtDisplay.classList.remove('text-muted');
						checkedAtDisplay.classList.add('badge', 'bg-light', 'text-dark');
					} else {
						checkedAtInput.value = '';
						checkedAtDisplay.textContent = '—';
						checkedAtDisplay.classList.remove('badge', 'bg-light', 'text-dark');
						checkedAtDisplay.classList.add('text-muted');
					}
				});
			});

			const commentModalElement = document.getElementById('ticketCommentModal');
			const commentModal = new bootstrap.Modal(commentModalElement);
			const commentTextarea = document.getElementById('ticketCommentModalTextarea');
			const commentContext = document.getElementById('ticketCommentModalContext');
			const commentSaveButton = document.getElementById('ticketCommentModalSave');
			let currentTicketId = null;
			const placeholder = @json(__('visite_prev.ticket_comment_placeholder'));

			document.querySelectorAll('.ticket-comment-button').forEach((button) => {
				button.addEventListener('click', () => {
					currentTicketId = button.dataset.ticketId;
					const label = button.dataset.ticketLabel;
					const commentInput = document.getElementById(`ticket-${currentTicketId}-comment`);

					commentContext.textContent = label;
					commentTextarea.value = commentInput ? commentInput.value : '';
				});
			});

			commentSaveButton.addEventListener('click', () => {
				if (!currentTicketId) {
					return;
				}

				const commentInput = document.getElementById(`ticket-${currentTicketId}-comment`);
				const commentPreview = document.getElementById(`ticket-${currentTicketId}-comment-preview`);
				const value = commentTextarea.value.trim();

				if (commentInput) {
					commentInput.value = value;
				}

				if (commentPreview) {
					commentPreview.textContent = value || placeholder;
				}

				commentModal.hide();
			});

			const interventionCommentModalElement = document.getElementById('interventionCommentModal');
			const interventionCommentModal = new bootstrap.Modal(interventionCommentModalElement);
			const interventionCommentTextarea = document.getElementById('interventionCommentModalTextarea');
			const interventionCommentContext = document.getElementById('interventionCommentModalContext');
			const interventionCommentSave = document.getElementById('interventionCommentModalSave');
			const interventionPlaceholder = @json(__('visite_prev.intervention_comment_placeholder'));

			let currentInterventionId = null;

			document.querySelectorAll('.intervention-comment-button').forEach((button) => {
				button.addEventListener('click', () => {
					currentInterventionId = button.dataset.interventionId;

					interventionCommentContext.textContent = button.dataset.interventionLabel;
					const commentInput = document.getElementById(`intervention-${currentInterventionId}-comment`);
					interventionCommentTextarea.value = commentInput ? commentInput.value : '';
				});
			});

			interventionCommentSave.addEventListener('click', () => {
				if (!currentInterventionId) {
					return;
				}

				const comment = interventionCommentTextarea.value.trim();
				const preview = document.getElementById(`intervention-${currentInterventionId}-comment-preview`);
				const commentInput = document.getElementById(`intervention-${currentInterventionId}-comment`);

				if (commentInput) {
					commentInput.value = comment;
				}

				if (preview) {
					preview.textContent = comment || interventionPlaceholder;
				}

				interventionCommentModal.hide();
			});

			document.querySelectorAll('.intervention-validated-checkbox').forEach((checkbox) => {
				checkbox.addEventListener('change', (event) => {
					const interventionId = event.target.dataset.interventionId;
					const validatedAtInput = document.getElementById(`intervention-${interventionId}-validated-at`);
					const validatedAtDisplay = document.getElementById(`intervention-${interventionId}-validated-at-display`);

					if (!validatedAtInput || !validatedAtDisplay) {
						return;
					}

					if (event.target.checked) {
						const now = new Date();
						validatedAtInput.value = formatHiddenDateTime(now);
						validatedAtDisplay.textContent = formatDisplayDateTime(now);
						validatedAtDisplay.classList.remove('text-muted');
						validatedAtDisplay.classList.add('badge', 'bg-light', 'text-dark');
					} else {
						validatedAtInput.value = '';
						validatedAtDisplay.textContent = '—';
						validatedAtDisplay.classList.remove('badge', 'bg-light', 'text-dark');
						validatedAtDisplay.classList.add('text-muted');
					}
				});
			});

			document.querySelectorAll('.btn-delete-plan').forEach((button) => {
				button.addEventListener('click', (event) => {
					const path = event.currentTarget.dataset.path;
					const row = event.currentTarget.closest('tr');
					const container = document.getElementById('plans-deletions-container');

					const input = document.createElement('input');
					input.type = 'hidden';
					input.name = 'delete_plans[]';
					input.value = path;
					container.appendChild(input);

					row.classList.add('table-danger');
					event.currentTarget.disabled = true;
				});
			});
		});
	</script>
@endpush
