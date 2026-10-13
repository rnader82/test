<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>
			{{ __('locale.title_tickets', [
			    'ticket_id' => $issue->id,
			    'group_name' => $issue->group->raison_sociale,
			    'site_name' => $issue->site->raison_sociale ?? __('locale.lbl_no_site'),
			    'subject' => $issue->subject,
			]) }}
		</title>
		<style>
			body {
				font-family: DejaVu Sans, sans-serif;
				font-size: 12px;
				color: #333;
				line-height: 1.5;
			}

			h2 {
				background-color: #f2f2f2;
				padding: 10px;
				font-size: 18px;
				border-left: 5px solid #007bff;
			}

			.section {
				margin-bottom: 30px;
			}

			.section-title {
				font-size: 15px;
				font-weight: bold;
				border-bottom: 1px solid #ccc;
				margin-bottom: 10px;
				color: #007bff;
			}

			.field {
				margin-bottom: 6px;
			}

			.field-label {
				font-weight: bold;
				display: inline-block;
				width: 180px;
			}

			.badge {
				display: inline-block;
				padding: 2px 6px;
				font-size: 10px;
				background-color: #6c757d;
				color: white;
				border-radius: 3px;
			}

			.badge-client {
				background-color: #007bff;
			}

			.badge-internal {
				background-color: #6c757d;
			}

			hr {
				border-top: 1px dashed #bbb;
				margin: 10px 0;
			}

			.timeline {
				border-left: 2px solid #007bff;
				padding-left: 15px;
			}

			.timeline-item {
				margin-bottom: 15px;
			}
		</style>
	</head>

	<body>
		<!-- Title -->
		<h2>
			{{ __('locale.title_tickets', [
			    'ticket_id' => $issue->id,
			    'group_name' => $issue->group->raison_sociale,
			    'site_name' => $issue->site->raison_sociale ?? __('locale.lbl_no_site'),
			    'subject' => $issue->subject,
			]) }}
		</h2>
		@php
			$typeSlug = \App\Support\IssueLookup::typeSlug($issue->type) ?? \Illuminate\Support\Str::slug((string) $issue->type, '_');
			$criticityInternalSlug = \App\Support\IssueLookup::criticitySlug($issue->criticity_internal) ?? \Illuminate\Support\Str::slug((string) $issue->criticity_internal, '_');
			$criticityClientSlug = \App\Support\IssueLookup::criticitySlug($issue->criticity_client) ?? \Illuminate\Support\Str::slug((string) $issue->criticity_client, '_');
		@endphp
		<!-- Section: Summary -->
		<div class="section">
			<div class="section-title">{{ __('locale.summary') }}</div>
			<div class="field"><span class="field-label">{{ __('locale.lbl_status') }}:</span> {{ $issue->status?->translated_name ?? '-' }}</div>
			<div class="field"><span class="field-label">{{ __('locale.lbl_origine') }}:</span> {{ $issue->origine->translated_name ?? __('locale.lbl_no_site') }}</div>
			<!-- Type with Color -->
			<div class="field" style="background-color: {{ $colorVars['issues_' . $typeSlug] ?? '#f2f2f2' }}; padding: 6px; border-radius: 4px;">
				<span class="field-label">{{ __('locale.lbl_type') }}:</span> {{ $issue->translated_type }}
			</div>
			<div class="field"><span class="field-label">{{ __('locale.lbl_category') }}:</span> {{ $issue->category->translated_name ?? '-' }}</div>
			<!-- Criticity Internal with Color -->
			<div class="field" style="background-color: {{ $colorVars['issues_' . $criticityInternalSlug] ?? '#f2f2f2' }}; padding: 6px; border-radius: 4px;">
				<span class="field-label">{{ __('locale.lbl_criticity_internal') }}:</span> {{ ucfirst($issue->translated_criticity_internal) }}
			</div>
			<!-- Criticity Client with Color -->
			<div class="field" style="background-color: {{ $colorVars['issues_' . $criticityClientSlug] ?? '#f2f2f2' }}; padding: 6px; border-radius: 4px;">
				<span class="field-label">{{ __('locale.lbl_criticity_client') }}:</span> {{ ucfirst($issue->translated_criticity_client) }}
			</div>
			<div class="field"><span class="field-label">{{ __('locale.lbl_ticket_groups') }}:</span> {{ $issue->issues_groups->pluck('name')->implode(', ') ?: __('locale.lbl_no_site') }}</div>
			<div class="field"><span class="field-label">{{ __('locale.lbl_user_affected') }}:</span> {{ $issue->users->pluck('name')->implode(', ') ?: __('locale.lbl_no_users_assigned') }}</div>
		</div>
		<!-- Section: Description -->
		<div class="section">
			<div class="section-title">{{ __('locale.description') }}</div> {!! $issue->description !!}
		</div>
		<!-- Section: Internal Notes -->
		<div class="section">
			<div class="section-title">{{ __('locale.lbl_notes_internal') }}</div>
			@if ($issue->internal_notes)
				<div class="field">{!! $issue->internal_notes !!}</div>
				<hr>
				@endif @forelse($issue->internalNotes as $note)
					<div class="field">
						<strong>{{ $note->user->name ?? __('locale.system') }}</strong> ({{ format_datetime($note->created_at) }})<br> {!! nl2br(e($note->note)) !!}
					</div>
					<hr> @empty @if (!$issue->internal_notes)
						<p>{{ __('locale.no_notes') }}</p>
					@endif
				@endforelse
		</div>
		<!-- Section: Documents -->
		<div class="section">
			<div class="section-title">{{ __('locale.lbl_attach_document') }}</div> {{ $issue->documents ?? __('locale.lbl_no_documents_attached') }}
		</div>
		<!-- Section: Messages -->
		<div class="section">
			<div class="section-title">{{ __('locale.lbl_message_board') }}</div>
			@forelse($issue->messages as $message)
				<div class="field">
					<strong>{{ $message->user->name ?? __('locale.system') }}</strong> ({{ format_datetime($message->created_at) }})<br>
					<em>{{ $message->subject }}</em><br> {{ $message->message }}<br>
					@if ($message->visible_to_client)
						<span class="badge badge-client">{{ __('locale.visible_to_client') }}</span>
					@else
						<span
							class="badge badge-internal">{{ __('locale.internal_message') }}</span>
					@endif
				</div>
			<hr> @empty <p>{{ __('locale.no_messages') }}</p>
			@endforelse
		</div>
		<!-- Section: Technical Interventions -->
		<div class="section">
			<div class="section-title">{{ __('locale.menu_interventions') }}</div>
			@forelse($issue->techIntervs as $interv)
				<div class="field">
					<strong>#{{ $interv->id }} - {{ $interv->subject }}</strong><br> {{ __('locale.lbl_site') }}: {{ $interv->site->raison_sociale ?? '-' }} | {{ __('locale.lbl_severity') }}:
					{{ $interv->severity->translated_name ?? '-' }} | {{ __('locale.lbl_priority') }}: {{ ucfirst($interv->priority) }}<br> {{ __('locale.lbl_intervention_date') }}:
					{{ format_datetime($interv->intervention_date, 'short', 'short') }} - {{ __('locale.lbl_end_date') }}: {{ $interv->end_date ? format_datetime($interv->end_date, 'short', 'short') : '-' }}<br>
					{{ __('locale.lbl_users') }}: {{ $interv->assignedUsers->pluck('name')->implode(', ') ?: __('locale.lbl_no_users_assigned') }}<br>
					<div><strong>{{ __('locale.description') }}</strong><br>{!! nl2br(e($interv->description)) !!}</div>
					<div><strong>{{ __('locale.lbl_notes_internal') }}</strong><br>{!! nl2br(e($interv->notes)) !!}</div>
				</div>
			<hr> @empty <p>{{ __('locale.lbl_no_records_found') }}</p>
			@endforelse
		</div>
		<!-- Section: Repairs -->
		<div class="section">
			<div class="section-title">{{ __('locale.menu_repairs') }}</div>
			@forelse($issue->repairs as $repair)
				<div class="field">
					<strong>{{ __('locale.repair') }} #{{ $repair->id }}</strong> | <strong>{{ __('locale.lbl_status') }}:</strong>
					{{ $repair->status?->translated_name ?? ($repair->status?->name ?? __('locale.lbl_no_status')) }}<br>
					<strong>{{ __('locale.lbl_notes_internal') }}</strong><br> {!! nl2br(e($repair->notes)) !!}
				</div>
			<hr> @empty <p>{{ __('locale.lbl_no_records_found') }}</p>
			@endforelse
		</div>
		<!-- Section: Status History -->
		<div class="section">
			<div class="section-title">{{ __('locale.lbl_status_history') }}</div>
			@php
				$canViewInternal = auth()->user()->is_admin || auth()->user()->hasPermission('view-internal-status');
			@endphp
			@forelse($issue->stateLogs as $log)
				@php
					$status = $canViewInternal ? optional($log->state)->internalStatus : optional($log->state)->externalStatus;
				@endphp
				<div class="timeline-item">
					<strong>{{ $status?->translated_name ?? __('locale.unknown_status') }}</strong> – {{ format_datetime($log->created_at) }}<br> {{ $log->user->name ?? __('locale.system') }}<br>
					@if ($log->note)
						<em>{{ $log->note }}</em>
					@endif
			</div> @empty <p>{{ __('locale.lbl_no_status_changes') }}</p>
			@endforelse
		</div>
		<!-- Section: Additional Info
				<div class="section">
								<div class="section-title">{{ __('locale.lbl_additional_info') }}</div>
								<p>{{ __('locale.lbl_additional_info_content') }}</p>
				</div> -->
	</body>

</html>
