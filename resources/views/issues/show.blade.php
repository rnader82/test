@extends('layouts.app') @section('title', 'Show Issue')

@php
    $site_name = !empty($issue->site) ? $issue->site->raison_sociale : __('locale.lbl_no_site');
@endphp

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('issues.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">
            {{ __('locale.title_tickets', [
                'ticket_id' => $issue->id,
                'group_name' => $issue->group->raison_sociale == 'All' ? null : $issue->group->raison_sociale . ',',
                'site_name' => $site_name,
                'subject' => $issue->subject,
            ]) }}
        </h1>
    </div>
@endsection

@section('page-actions')
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#statusModal">
        <i class="bi bi-tag me-1"></i> {{ __('locale.lbl_status') }}
    </button>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#stateModal">
        <i class="bi bi-flag me-1"></i> {{ __('locale.lbl_state') }}
    </button>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#usersModal">
        <i class="bi bi-people me-1"></i> {{ __('locale.lbl_user_affected') }}
    </button>
@endsection

@section('content') 
<style>
	label {
		font-weight: 800;
		margin-bottom: 0.25rem;
	}

	.card.mb-3 {
		margin-bottom: 0.75rem !important;
	}

	.card-body {
		padding: 0.75rem 1rem;
		font-size: 0.925rem;
	}

	.card-body p.form-control-plaintext,
	.card-body select.form-select,
	.card-body textarea.form-control {
		margin-bottom: 0.5rem;
	}

	.nav-tabs {
		margin-top: 1.25rem !important;
	}

	.tab-content {
		margin-top: 0.75rem !important;
	}

	.message-board {
		background-color: #f8f9fa;
		border-radius: 10px;
		padding: 20px;
		max-width: 100%;
		margin: 0 auto;
	}

	.message {
		padding: 10px 15px;
		border-radius: 15px;
		margin-bottom: 15px;
		max-width: 70%;
		position: relative;
	}

	.message.my-message {
		background-color: #dcf8c6;
		align-self: flex-end;
		margin-left: auto;
	}

	.message.other-message {
		background-color: #ffffff;
		align-self: flex-start;
		margin-right: auto;
	}

	.status-entry {
		background-color: #e9ecef;
		padding: 10px 15px;
		border-radius: 15px;
		margin-bottom: 15px;
		max-width: 70%;
		align-self: flex-end;
		margin-left: auto;
		text-align: right;
	}

	.status-entry .status-sender {
		font-weight: bold;
		font-size: 0.9em;
		margin-bottom: 0;
		margin-right: 4px;
	}

	.status-entry .status-header {
		display: flex;
		justify-content: flex-end;
		align-items: center;
		gap: 0.25rem;
		margin-bottom: 2px;
	}

	.status-entry .timestamp {
		font-size: 0.85em;
		color: #888;
		margin-top: 5px;
	}

	.message .timestamp {
		font-size: 0.85em;
		color: #888;
		margin-top: 5px;
	}

	.message .message-sender {
		font-weight: bold;
		font-size: 0.9em;
		margin-bottom: 5px;
	}

	.timeline {
		display: flex;
		flex-wrap: wrap;
		gap: 1rem;
	}

	.timeline-item {
		flex: 1 1 150px;
		text-align: center;
		padding: 0.5rem;
		background-color: #f8f9fa;
		border-radius: 0.25rem;
		box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
	}

	.user-update-container {
		display: flex;
		align-items: flex-start;
		gap: 0.5rem;
	}

	.user-update-container .select2-container {
		flex: 1;
	}

	.user-update-container button {
		white-space: nowrap;
	}

	.fab-menu {
		display: flex;
		flex-direction: column;
		gap: 10px;
		margin-bottom: 15px;
		opacity: 0;
		visibility: hidden;
		transform: translateY(20px);
		transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
		align-items: center;
	}

	.fab-menu.show {
		opacity: 1;
		visibility: visible;
		transform: translateY(0);
	}

	.fab-menu-item {
		width: 48px;
		height: 48px;
		border-radius: 50%;
		border: none;
		background: #ffffff;
		color: #91378e;
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
		display: flex;
		align-items: center;
		justify-content: center;
		transition: all 0.2s ease;
		text-decoration: none;
		position: relative;
	}

	.fab-menu-item:hover {
		background: #7ec1d1;
		color: #ffffff;
		transform: scale(1.1);
		box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
	}

	.fab-menu-item i {
		font-size: 20px;
	}

	.fab-menu-item::after {
		content: attr(data-label);
		position: absolute;
		right: 60px;
		background: rgba(0, 0, 0, 0.75);
		color: #fff;
		padding: 4px 10px;
		border-radius: 6px;
		font-size: 12px;
		white-space: nowrap;
		opacity: 0;
		visibility: hidden;
		transition: all 0.2s ease;
		pointer-events: none;
	}

	.fab-menu-item:hover::after {
		opacity: 1;
		visibility: visible;
		right: 65px;
	}

	#fab-toggle {
		transition: transform 0.3s ease;
	}

	#fab-toggle.active {
		transform: rotate(135deg);
		background-color: #ff3e1d !important;
	}

	.div-floating-button {
		z-index: 2000 !important;
	}

	.fab-menu {
		z-index: 2001 !important;
	}
</style>
<div class="container" id="page-top">
@php
		$typeSlug = \App\Support\IssueLookup::typeSlug($issue->type) ?? \Illuminate\Support\Str::slug((string) $issue->type, '_');
		$criticity_internal = \App\Support\IssueLookup::criticitySlug($issue->criticity_internal) ?? \Illuminate\Support\Str::slug((string) $issue->criticity_internal, '_');
		$criticity_client = \App\Support\IssueLookup::criticitySlug($issue->criticity_client) ?? \Illuminate\Support\Str::slug((string) $issue->criticity_client, '_');
		$login = auth()->user()->previous_login_at;
		if ($login) {
		    $newMessagesCount = $issue->messages()->where('created_at', '>', $login)->count();
		    $newMessagesCount += $issue->stateLogs()->where('created_at', '>', $login)->count();
		    $newIntervsCount = $issue->techIntervs()->where('tech_intervs.created_at', '>', $login)->count();
		    $newRepairsCount = $issue->repairs()->where('created_at', '>', $login)->count();
		    $newNotesCount = $issue->internalNotes()->where('created_at', '>', $login)->count();
		} else {
		    $newMessagesCount = $newIntervsCount = $newRepairsCount = $newNotesCount = 0;
		}
	@endphp
	<a href="{{ route('issues.export_pdf', $issue->id) }}" class="btn btn-sm btn-danger me-1" target="_blank">
		<i class="bi bi-file-earmark-pdf"></i>
	</a>
	@if ($issue->client?->concurrent)
		<span class="badge bg-danger">{{ __('locale.lbl_concurrent') }}</span>
	@endif
	@if ($issue->client?->default_payment)
		<span class="badge bg-danger">{{ __('locale.default_payment') }}</span>
	@endif
	@php
		$client = $issue->client;
		$contractStatusValue = $client?->contract_status;
		$contractStatusClass = match ($contractStatusValue) {
		    'avec_contrat' => 'badge-matte-success',
		    'sans_contrat' => 'badge-matte-secondary',
		    'contrat_expire' => 'badge-matte-warning',
		    'contrat_resilie' => 'badge-matte-danger',
		    default => 'badge-matte-secondary',
		};
		$contractsTooltip = '';
		if ($client && $client->contracts && $client->contracts->isNotEmpty()) {
			$contractsTooltip = $client->contracts->map(function ($c) {
				$stText = __('locale.contract_status.' . $c->calculateStatus());
				$typeName = $c->type?->translated_name ?? $c->type?->name ?? '';
				$name = $c->contract_name ?: ($typeName ?: '#' . $c->id);
				return e($name . ($typeName && $name !== $typeName ? " ({$typeName})" : '') . ": {$stText}");
			})->implode('<br>');
		}
	@endphp
	@if ($contractStatusValue)
		<span class="badge {{ $contractStatusClass }}" @if($contractsTooltip) data-bs-toggle="tooltip" data-bs-html="true" title="{!! $contractsTooltip !!}" @endif>
			<i class="fa-solid fa-shield-halved me-1"></i>{{ __('locale.contract_status.' . $contractStatusValue) }}
		</span>
	@endif
	<br /><br />
	<div class="row" id="page-mid">
		<!-- Left Column -->
		<div class="col-md-6">
			<!-- Status Display -->
			<div class="card mb-3">
				<div class="card-body">
					<label for="status">{{ __('locale.lbl_status') }}</label>
					@php
						$canViewInternal = auth()->user()->is_admin || auth()->user()->hasPermission('view-internal-status');
						$statusObj = $canViewInternal ? (optional($issue->state)->internalStatus ?: $issue->latestStatus) : (optional($issue->state)->externalStatus ?: $issue->latestStatus);
						$stateNote = optional($issue->latestStateLog())->note;
					@endphp
					<span class="badge"
						style="background-color: {{ hexToRgba(optional($statusObj)->color ?? '#ccc', 0.3) }}; color: {{ optional($statusObj)->color ?? '#ccc' }};">
						{{ optional($statusObj)->translated_name ?? (optional($statusObj)->name ?? __('locale.not_available')) }} </span>
					@if ($stateNote)
						<div class="mt-1 text-secondary">
							<i class="bi bi-chat-left-text me-1"></i>
							<span data-bs-toggle="tooltip" title="{{ $stateNote }}"> {{ \Illuminate\Support\Str::limit($stateNote, 80) }} </span>
						</div>
					@endif
					<ul class="list-group list-group-flush">
						<li class="list-group-item">
							<strong>{{ __('locale.lbl_origine') }}:</strong> {{ $issue->origine->translated_name ?? __('locale.lbl_no_site') }}
						</li>
						<li class="list-group-item">
							<strong>{{ __('locale.lbl_type') }}:</strong> {{ $issue->translated_type }}
						</li>
						<li class="list-group-item">
							<strong>{{ __('locale.lbl_category') }}:</strong> {{ $issue->category->translated_name ?? __('locale.lbl_no_site') }}
						</li>
						<li class="list-group-item">
							<strong>{{ __('locale.lbl_state') }}:</strong> {{ $issue->state?->translated_name ?? '-' }}
						</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- Right Column -->
		<div class="col-md-6">
			<!-- Ticket Attributes -->
			<div class="card mb-3 height-first">
				<div class="card-body p-0">
					<ul class="list-group list-group-flush">
						<li class="list-group-item" style="">
							<strong>{{ __('locale.lbl_criticity_internal') }}:</strong> {{ $issue->translated_criticity_internal }}
						</li>
						<li class="list-group-item" style="">
							<strong>{{ __('locale.lbl_criticity_client') }}:</strong> {{ $issue->translated_criticity_client }}
						</li>
					</ul>
				</div>
			</div>
			<!-- Groups Display -->
			<div class="card mb-3">
				<div class="card-body p-0">
					<ul class="list-group list-group-flush">
						<li class="list-group-item">
							<strong>{{ __('locale.lbl_ticket_groups') }}:</strong> {{ $issue->issues_groups->pluck('name')->implode(', ') ?: __('locale.lbl_no_site') }}
						</li>
						<li class="list-group-item">
							<strong>{{ __('locale.lbl_user_affected') }}:</strong> {{ $issue->users->pluck('name')->implode(', ') ?: __('locale.lbl_no_users_assigned') }}
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="text-end mb-3">
	</div>
	<div class="row">
		<!-- Description Field with Formatting -->
		<div class="card mb-3">
			<div class="card-body">
				<label for="description">{{ __('locale.description') }}</label>
				<p class="form-control-plaintext">{!! $issue->description !!}</p>
			</div>
		</div>
		<!-- Documents Upload -->
		<div class="card mb-3">
			<div class="card-body">
				<label for="documents">{{ __('locale.lbl_attach_document') }}</label>
				<p class="form-control-plaintext">{{ $issue->documents }}</p>
			</div>
		</div>
	</div>
	<div class="card mb-4">
		<div class="card-header fw-bold"> {{ __('locale.lbl_state_history') }} </div>
		<div class="card-body">
			@if ($issue->stateLogs->isEmpty())
				<p class="text-muted">{{ __('locale.lbl_no_status_changes') }}</p>
			@else
				<div class="timeline">
					@foreach ($issue->stateLogs()->oldest()->get() as $log)
						@php $logStatus = $canViewInternal ? optional($log->state)->internalStatus : optional($log->state)->externalStatus; @endphp <div
							class="timeline-item" style="border-left: 5px solid {{ $logStatus->color ?? '#ccc' }}; padding-left: 0.5rem;"
							@if ($log->note) data-bs-toggle="tooltip" title="{{ $log->note }}" @endif>
							<div class="fw-bold">{{ ucfirst($logStatus->name ?? '') }}</div>
							<div class="text-muted">{{ $log->user->name ?? __('locale.system') }}</div>
							<div class="text-muted small">{{ format_datetime($log->created_at) }}</div>
						</div>
					@endforeach
				</div>
			@endif
		</div>
	</div>
	<!-- Tab Navigation -->
	<ul class="nav nav-tabs mt-4" id="issueTabs" role="tablist">
		<li class="nav-item" role="presentation">
			<button class="nav-link active" id="messages-tab" data-bs-toggle="tab" data-bs-target="#messages" type="button" role="tab" aria-controls="messages" aria-selected="true">
				{{ __('locale.lbl_message_board') }}
				@if ($newMessagesCount > 0)
					<span class="badge bg-danger ms-1">{{ $newMessagesCount }}</span>
				@else
					<span class="badge bg-secondary ms-1">{{ $newMessagesCount }}</span>
				@endif
			</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link" id="tech-intervs-tab" data-bs-toggle="tab" data-bs-target="#tech-intervs" type="button" role="tab" aria-controls="tech-intervs" aria-selected="false">
				{{ __('locale.menu_interventions') }}
				@if ($newIntervsCount > 0)
					<span class="badge bg-danger ms-1">{{ $newIntervsCount }}</span>
				@else
					<span class="badge bg-secondary ms-1">{{ $newIntervsCount }}</span>
				@endif
			</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link" id="repairs-tab" data-bs-toggle="tab" data-bs-target="#repairs" type="button" role="tab" aria-controls="repairs" aria-selected="false">
				{{ __('locale.menu_repairs') }}
				@if ($newRepairsCount > 0)
					<span class="badge bg-danger ms-1">{{ $newRepairsCount }}</span>
				@else
					<span class="badge bg-secondary ms-1">{{ $newRepairsCount }}</span>
				@endif
			</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link" id="internal-notes-tab" data-bs-toggle="tab" data-bs-target="#internal-notes" type="button" role="tab" aria-controls="internal-notes" aria-selected="false">
				{{ __('locale.lbl_notes_internal') }}
				@if ($newNotesCount > 0)
					<span class="badge bg-danger ms-1">{{ $newNotesCount }}</span>
				@else
					<span class="badge bg-secondary ms-1">{{ $newNotesCount }}</span>
				@endif
			</button>
		</li>
		<!-- Add more tabs here as needed -->
	</ul>
	<!-- Tab Content -->
	<div class="tab-content mt-3" id="issueTabContent">
		<!-- Messages Tab -->
		<div class="tab-pane fade show active" id="messages" role="tabpanel" aria-labelledby="messages-tab">
			@php
				$currentUser = auth()->user();
				$canSeeAll = $currentUser->is_admin || $currentUser->is_employee;
				$messagesCollection = $canSeeAll ? $issue->messages : $issue->messages->where('visible_to_client', true);
				$messagesCollection = $messagesCollection->sortBy('created_at');
				$stateCollection = $issue->stateLogs->sortBy('created_at');
			@endphp
			@include('issues.partials.messages_list', ['messages' => $messagesCollection, 'states' => $stateCollection])
			<h3
				class="mt-4">
				{{ __('locale.lbl_add_new_message') }}</h3>
			<form id="message_form" action="{{ route('messages_store', $issue->id) }}" method="POST"> @csrf <input type="hidden" name="visible_to_client" id="visible_to_client" value="1">
				<div class="mb-3">
					<label for="template_select" class="form-label">{{ __('locale.select_template') }}</label>
					<select id="template_select" class="form-select select2">
						<option value="">{{ __('locale.select_template') }}</option>
						@foreach ($templates as $t)
							<option value="{{ $t->id }}" data-subject="{{ $t->subject }}"
								data-message="{{ $t->message }}">{{ $t->subject }}</option>
						@endforeach
					</select>
				</div>
				<div class="mb-3">
					<label for="subject" class="form-label">{{ __('locale.lbl_subject') }}</label>
					<input type="text" name="subject" id="subject" class="form-control" value="{{ $issue->subject }}" required>
				</div>
				<div class="mb-3">
					<label for="message" class="form-label">{{ __('locale.lbl_message') }}</label>
					<textarea name="message" id="message" class="form-control" required></textarea>
				</div>
				<button type="button" class="btn btn-primary float-end" onclick="submitMessage(1)">{{ __('locale.btn_post_message') }}</button>
				@if ($canSeeAll)
					<button type="button"
						class="btn btn-secondary float-end me-2" onclick="submitMessage(0)">{{ __('locale.internal_message') }}</button>
				@endif
			</form>
		</div>
		<!-- Tech Intervs Tab -->
		<div class="tab-pane fade" id="tech-intervs" role="tabpanel" aria-labelledby="tech-intervs-tab">
			<a href="{{ route('tech_intervs.create', ['issue_id' => $issue->id]) }}" class="btn btn-sm btn-outline-primary mb-3">
				<i class="bi bi-plus-lg"></i>
			</a>
			@if ($issue->techIntervs->isEmpty())
				<p>{{ __('locale.lbl_no_records_found') }}</p>
			@else
				<div class="accordion" id="techIntervsAccordion">
					@foreach ($issue->techIntervs as $interv)
						<div class="accordion-item mb-3">
							<h2 class="accordion-header" id="heading{{ $loop->index }}">
								<button class="accordion-button {{ $loop->first ? '' : 'collapsed' }} bg-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $loop->index }}"
									aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse{{ $loop->index }}"> {{ __('locale.lbl_technical_intervention') }} #{{ $interv->id }} -
									{{ $interv->subject }} </button>
							</h2>
							<div id="collapse{{ $loop->index }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="heading{{ $loop->index }}"
								data-bs-parent="#techIntervsAccordion">
								<div class="accordion-body mt-3 position-relative">
									<!-- Edit Button -->
									<a href="{{ route('tech_intervs.edit', $interv->id) }}" class="btn btn-sm btn-outline-warning position-absolute top-0 end-0 me-2">
										<i class="bi bi-pencil-square"></i>
									</a>
									<div class="row mt-4">
										<!-- Left Column -->
										<div class="col-md-6">
											<p><strong>{{ __('locale.lbl_site') }}:</strong> {{ $interv->site->raison_sociale ?? __('locale.lbl_no_site') }}</p>
											<p><strong>{{ __('locale.lbl_severity') }}:</strong> {{ $interv->severity->translated_name ?? '-' }}</p>
											<p><strong>{{ __('locale.lbl_type') }}:</strong> {{ $interv->type->translated_name ?? '-' }}</p>
											<p><strong>{{ __('locale.lbl_status') }}:</strong>
												<span class="badge" style="background-color: {{ hexToRgba($interv->status->color ?? '#ccc', 0.3) }}; color: {{ $interv->status->color ?? '#ccc' }};">
													{{ __('locale.tech_interv_status.' . str_replace(['-', ' '], '_', strtolower($interv->status->name))) }} </span>
											</p>
											<p><strong>{{ __('locale.lbl_state') }}:</strong>
												<span class="badge bg-secondary"> {{ $interv->state?->translated_name ?? '-' }} </span>
											</p>
											<p><strong>{{ __('locale.lbl_priority') }}:</strong>
												<span class="badge bg-warning text-dark">{{ __('locale.criticity_' . strtolower($interv->priority)) }}</span>
											</p>
											<p><strong>{{ __('locale.lbl_criticity_internal') }}:</strong>
												<span class="badge bg-danger">{{ __('locale.criticity_' . strtolower($interv->criticity_internal)) }}</span>
											</p>
											<p><strong>{{ __('locale.lbl_criticity_client') }}:</strong>
												<span class="badge bg-danger">{{ __('locale.criticity_' . strtolower($interv->criticity_external)) }}</span>
											</p>
										</div>
										<!-- Right Column -->
										<div class="col-md-6">
											<p><strong>{{ __('locale.lbl_intervention_date') }}:</strong> {{ $interv->intervention_date }}</p>
											<p><strong>{{ __('locale.lbl_end_date') }}:</strong> {{ $interv->end_date ?? '-' }}</p>
											<p><strong>{{ __('locale.lbl_planned') }}:</strong>
												<span class="badge {{ $interv->is_planned ? 'bg-primary' : 'bg-dark' }}"> {!! $interv->is_planned
												    ? '<i class="bi bi-check-lg"></i>'
												    : '<i
																																																																																																																																																																																																																																																																																																																																																                                                class="bi bi-x-lg"></i>' !!} </span>
											</p>
											<p><strong>{{ __('locale.lbl_visible_to_client') }}:</strong>
												<span class="badge {{ $interv->is_visible_to_client ? 'bg-primary' : 'bg-dark' }}"> {!! $interv->is_visible_to_client
												    ? '<i class="bi bi-check-lg"></i>'
												    : '<i
																																																																																																																																																																																																																																																																																																																																																                                                class="bi bi-x-lg"></i>' !!} </span>
											</p>
											<p><strong>{{ __('locale.lbl_users') }}:</strong>
												@if ($interv->assignedUsers->isNotEmpty())
													{{ implode(', ', $interv->assignedUsers->pluck('name')->toArray()) }}
												@else
													{{ __('locale.lbl_no_users_assigned') }}
												@endif
											</p>
										</div>
									</div>
									<hr>
									<!-- Subject & Description -->
									<p><strong>{{ __('locale.lbl_subject') }}:</strong> {{ $interv->subject }}</p>
									<p><strong>{{ __('locale.description') }}:</strong><br />{!! nl2br(e($interv->description)) !!}</p>
									<p><strong>{{ __('locale.lbl_notes_internal') }}:</strong><br />{!! nl2br(e($interv->notes)) !!}</p>
								</div>
							</div>
						</div>
					@endforeach
				</div>
			@endif
		</div>
		<!-- Repairs Tab -->
		<div class="tab-pane fade" id="repairs" role="tabpanel" aria-labelledby="repairs-tab">
			@if ($issue->repairs->isEmpty())
				<p>{{ __('locale.lbl_no_records_found') }}</p>
			@else
				<div
					class="accordion" id="repairsAccordion">
					@foreach ($issue->repairs as $repair)
						<div class="accordion-item mb-3">
							<h2 class="accordion-header" id="repairHeading{{ $loop->index }}">
								<button class="accordion-button {{ $loop->first ? '' : 'collapsed' }} bg-primary" type="button" data-bs-toggle="collapse" data-bs-target="#repairCollapse{{ $loop->index }}"
									aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="repairCollapse{{ $loop->index }}"> {{ __('locale.repair') }} #{{ $repair->id }} </button>
							</h2>
							<div id="repairCollapse{{ $loop->index }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="repairHeading{{ $loop->index }}"
								data-bs-parent="#repairsAccordion">
								<div class="accordion-body mt-3 position-relative">
									<!-- Edit Button -->
									<a href="{{ route('repairs.edit', $repair->id) }}" class="btn btn-sm btn-outline-warning position-absolute top-0 end-0 me-2 ">
										<i class="bi bi-pencil-square"></i>
									</a>
									<div class="row mt-4">
										<div class="col-md-12">
											@php
												$status = $repair->status;
												$statusColor = $status?->color ?? '#6c757d';
												$statusTextColor = $status?->text_color ?? '#ffffff';
												$statusLabel = $status?->translated_name ?? ($status?->name ?? __('locale.lbl_no_status'));
											@endphp
											<p><strong>{{ __('locale.lbl_status') }}:</strong>
												<span class="badge" style="background-color: {{ hexToRgba($statusColor, 0.3) }}; color: {{ $statusColor }};"> {{ $statusLabel }} </span>
											</p>
										</div>
									</div>
									<hr>
									<p><strong>{{ __('locale.lbl_notes_internal') }}:</strong><br>{!! nl2br(e($repair->notes)) !!}</p>
								</div>
							</div>
						</div>
					@endforeach
				</div>
			@endif
		</div>
		<!-- Internal Notes Tab -->
		<div class="tab-pane fade" id="internal-notes" role="tabpanel" aria-labelledby="internal-notes-tab">
			@if ($issue->internal_notes)
				<div class="message-board d-flex flex-column mb-3">
					<div class="message other-message">
						<p class="mb-0">{!! $issue->internal_notes !!}</p>
						<div class="timestamp">{{ format_datetime($issue->created_at) }}</div>
					</div>
				</div>
			@endif
			@php
				$notesCollection = $issue->internalNotes->sortBy('created_at');
			@endphp
			@include('issues.partials.internal_notes_list', ['notes' => $notesCollection]) <h3 class="mt-4">
				{{ __('locale.lbl_add_new_note') }}</h3>
			<form action="{{ route('issue_notes.store', $issue->id) }}" method="POST"> @csrf <div class="mb-3">
					<textarea name="note" class="form-control" rows="3" required></textarea>
				</div>
				<button type="submit" class="btn btn-primary">{{ __('locale.btn_save') }}</button>
			</form>
		</div>
	</div>
	<!-- Status Change Modal -->
	<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<form class="modal-content" action="{{ route('issues.updateStatus', $issue->id) }}" method="POST"> @csrf @method('PUT') <div class="modal-header">
					<h5 class="modal-title" id="statusModalLabel">{{ __('locale.lbl_status') }}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="mb-3">
						<label for="status_id" class="form-label">{{ __('locale.lbl_status') }}</label>
						<select name="status_id" id="status_id" class="form-select select-status">
							@foreach ($statuses as $status)
								<option value="{{ $status->id }}" data-color="{{ $status->color }}"
									data-text-color="{{ $status->text_color }}" {{ $issue->status_id == $status->id ? 'selected' : '' }}> {{ $status->translated_name }} </option>
							@endforeach
						</select>
					</div>
					<div class="mb-3">
						<label for="note" class="form-label">{{ __('locale.lbl_note_optional') }}</label>
						<textarea name="note" id="note" rows="2" class="form-control" placeholder="{{ __('locale.lbl_note_optional') }}"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">{{ __('locale.update') }}</button>
				</div>
			</form>
		</div>
	</div>
	<!-- State Change Modal -->
	<div class="modal fade" id="stateModal" tabindex="-1" aria-labelledby="stateModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<form class="modal-content" action="{{ route('issues.updateState', $issue->id) }}" method="POST"> @csrf @method('PUT') <div class="modal-header">
					<h5 class="modal-title" id="stateModalLabel">{{ __('locale.lbl_state') }}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="mb-3">
						<label for="state_id" class="form-label">{{ __('locale.lbl_state') }}</label>
						<select name="state_id" id="state_id" class="form-select select-state">
							@foreach ($states as $state)
								<option value="{{ $state->id }}" {{ $issue->state_id == $state->id ? 'selected' : '' }}> {{ $state->translated_name }} </option>
							@endforeach
						</select>
					</div>
					<div class="mb-3">
						<label for="state_note" class="form-label">{{ __('locale.lbl_note_optional') }}</label>
						<textarea name="note" id="state_note" rows="2" class="form-control" placeholder="{{ __('locale.lbl_note_optional') }}"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">{{ __('locale.update') }}</button>
				</div>
			</form>
		</div>
	</div>
	<!-- Assigned Users Modal -->
	<div class="modal fade" id="usersModal" tabindex="-1" aria-labelledby="usersModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<form class="modal-content" action="{{ route('issues.updateUsers', $issue->id) }}" method="POST"> @csrf @method('PUT') <div class="modal-header">
					<h5 class="modal-title" id="usersModalLabel">{{ __('locale.lbl_user_affected') }}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="mb-3">
						<label for="usersSelect" class="form-label">{{ __('locale.lbl_user_affected') }}</label>
						<select name="users[]" id="usersSelect" class="form-select select2" multiple>
							@foreach ($users as $user)
								<option value="{{ $user->id }}" {{ $issue->users->contains($user->id) ? 'selected' : '' }}>{{ $user->name }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">{{ __('locale.update') }}</button>
				</div>
			</form>
		</div>
	</div>
	<div id="page-bottom" class="mb-4"></div>
</div>

<div class="div-floating-button">
    <div class="fab-menu" id="fab-menu">
        <button type="button" class="fab-menu-item" data-bs-toggle="modal" data-bs-target="#statusModal" data-label="{{ __('locale.lbl_status') }}">
            <i class="bi bi-tag"></i>
        </button>
        <button type="button" class="fab-menu-item" data-bs-toggle="modal" data-bs-target="#stateModal" data-label="{{ __('locale.lbl_state') }}">
            <i class="bi bi-flag"></i>
        </button>
        <button type="button" class="fab-menu-item" data-bs-toggle="modal" data-bs-target="#usersModal" data-label="{{ __('locale.lbl_user_affected') }}">
            <i class="bi bi-people"></i>
        </button>
        <a href="{{ route('tech_intervs.create', ['issue_id' => $issue->id]) }}" class="fab-menu-item" data-label="{{ __('locale.menu_interventions') }}">
            <i class="bi bi-tools"></i>
        </a>
        <a href="{{ route('issues.export_pdf', $issue->id) }}" class="fab-menu-item" target="_blank" data-label="{{ __('locale.lbl_print') }}">
            <i class="bi bi-file-earmark-pdf"></i>
        </a>
    </div>
    <button type="button" class="btn btn-primary" id="fab-toggle" title="{{ __('locale.actions') }}">
        <i class="bi bi-plus-lg"></i>
    </button>
</div>
@endsection @push('scripts')
<script>
	function submitMessage(val) {
		document.getElementById('visible_to_client').value = val;
		document.getElementById('message_form').submit();
	}

	$(document).ready(function() {
		function formatStatusOption(state) {
			if (!state.id) return state.text;
			console.log(state.id);
			const $element = $(state.element);
			const color = $element.data('color') || '#ccc';
			const textColor = $element.data('text-color') || '#000';
			const statusId = $element.data('id') || 'N/A'; // fallback in case missing

			return $(
				'<span style="display:flex; align-items:center;">' +
				'<span style="background-color:' + color + '; width:14px; height:14px; border-radius:3px; display:inline-block; margin-right:8px;"></span>' +
				'<strong style="color:' + textColor + ';">' + state.text + '</strong>' +
				'</span>'
			);
		}


		$('.select-status').select2({
			templateResult: formatStatusOption,
			templateSelection: formatStatusOption,
			width: '60%',
			dropdownParent: $('#statusModal') // ✅ This line fixes the input focus issue
		});
		$('.select-state').select2({
			width: '60%',
			dropdownParent: $('#stateModal')
		});
		$('#usersSelect').select2({
			dropdownParent: $('#usersModal'),
			width: '100%' // ✅ Make it span full width
		});

		function handleTemplate(selectId, subjectId, messageId) {
			const issueSubject = @json($issue->subject);
			$("#" + selectId).on("change", function() {
				const opt = $(this).find("option:selected");
				const sub = opt.data("subject");
				const msg = opt.data("message");
				if (sub) {
					$("#" + subjectId).val(sub);
					$("#" + messageId).val(msg);
				} else {
					$("#" + subjectId).val(issueSubject);
					$("#" + messageId).val('');
				}
			});
		}
		handleTemplate("template_select", "subject", "message");
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
		tooltipTriggerList.map(function(tooltipTriggerEl) {
			return new bootstrap.Tooltip(tooltipTriggerEl);
		});

		document.getElementById('fab-toggle')?.addEventListener('click', function() {
			this.classList.toggle('active');
			document.getElementById('fab-menu').classList.toggle('show');
		});
	});

	$('.select2').select2();
</script> @endpush
