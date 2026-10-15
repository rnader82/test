@extends('layouts.app')

@php
    $currentUser = auth()->user();
    $canSeeAll = $currentUser->is_admin || $currentUser->is_employee;
    $canManageTicket = $currentUser->is_admin || $currentUser->hasPermission('edit-ticket');
    $canViewInternal = $currentUser->is_admin || $currentUser->hasPermission('view-internal-status');
    $subject = html_entity_decode($issue->subject ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $attributes = $issue->getAttributes();
    $reference = $attributes['reference'] ?? '#' . $issue->id;
    $client = $issue->client;
    $site = $issue->site;
    $statusObj = $canViewInternal ? ($issue->state?->internalStatus ?: $issue->latestStatus) : ($issue->state?->externalStatus ?: $issue->latestStatus);
    $statusLabel = $statusObj?->translated_name ?? $statusObj?->name ?? __('locale.not_available');
    $statusColor = preg_match('/^#[0-9a-f]{6}$/i', $statusObj?->color ?? '') ? $statusObj->color : '#64748b';
    $stateNote = $issue->latestStateLog()?->note;
    $typeSlug = \App\Support\IssueLookup::typeSlug($issue->type) ?? \Illuminate\Support\Str::slug((string) $issue->type, '_');
    $typeClass = match ($typeSlug) { 'panne_grave' => 'outage-type critical', 'panne_majeure' => 'outage-type major', 'panne_mineure' => 'outage-type minor', default => 'type-badge information' };
    $priorityLabel = $canViewInternal ? $issue->translated_criticity_internal : $issue->translated_criticity_client;
    $prioritySlug = \Illuminate\Support\Str::lower(\Illuminate\Support\Str::ascii($priorityLabel ?? ''));
    $priorityClass = preg_match('/haut|high|crit|urg/', $prioritySlug) ? 'high' : (preg_match('/moyen|medium|important/', $prioritySlug) ? 'medium' : 'low');
    $contractStatus = $client?->contract_status;
    $contractStatus = $contractStatus instanceof \BackedEnum ? $contractStatus->value : $contractStatus;
    $contracts = collect($client?->contracts ?? []);
    $messagesCollection = ($canSeeAll ? $issue->messages : $issue->messages->where('visible_to_client', true))->sortBy('created_at');
    $stateCollection = $issue->stateLogs->sortBy('created_at');
    $notesCollection = $canSeeAll ? $issue->internalNotes->sortBy('created_at') : collect();
    $interventions = $canSeeAll ? $issue->techIntervs : $issue->techIntervs->where('is_visible_to_client', true);
    $repairs = $issue->repairs;
    // Optional relationships are read only when the application's controller has loaded them.
    $quotes = $issue->relationLoaded('quotes') ? collect($issue->getRelation('quotes')) : collect();
    $equipment = $issue->relationLoaded('equipment') ? $issue->getRelation('equipment') : null;
    $contact = $issue->relationLoaded('contact') ? $issue->getRelation('contact') : null;
    $slaAt = $callbackAt = null;
    try { if (!empty($attributes['sla_deadline'])) $slaAt = \Carbon\Carbon::parse($attributes['sla_deadline']); } catch (\Throwable $ignored) {}
    try { if ($callback = ($attributes['callback_at'] ?? $attributes['reminder_at'] ?? $attributes['next_callback_at'] ?? null)) $callbackAt = \Carbon\Carbon::parse($callback); } catch (\Throwable $ignored) {}
    $slaMinutes = $slaAt ? (int) floor(now()->diffInMinutes($slaAt, false)) : null;
    $slaLabel = $slaMinutes === null ? __('locale.portal.detail.no_sla') : ($slaMinutes < 0 ? __('locale.portal.detail.sla_overdue', ['minutes' => abs($slaMinutes)]) : __('locale.portal.detail.sla_remaining', ['minutes' => $slaMinutes]));
    $contactName = $contact?->name ?? $attributes['contact_name'] ?? $attributes['requester_name'] ?? null;
    $contactEmail = $contact?->email ?? $attributes['contact_email'] ?? $client?->email;
    $contactPhone = $contact?->phone ?? $attributes['contact_phone'] ?? $client?->tel_1;
    $closingState = collect($states)->first(function ($state) {
        $label = \Illuminate\Support\Str::lower(\Illuminate\Support\Str::ascii($state->name ?? $state->translated_name ?? ''));
        return (bool) preg_match('/clotur|(^|[_ -])(clos|closed|ferme)($|[_ -])/', $label);
    });
    $events = collect();
    foreach ($messagesCollection as $message) $events->push(['title' => $message->subject, 'text' => \Illuminate\Support\Str::limit(strip_tags($message->message), 130), 'date' => $message->created_at, 'kind' => 'message']);
    foreach ($stateCollection as $log) {
        $logStatus = $canViewInternal ? $log->state?->internalStatus : $log->state?->externalStatus;
        $events->push(['title' => $logStatus?->translated_name ?? $logStatus?->name ?? __('locale.lbl_state'), 'text' => ($log->user?->name ?? __('locale.system')) . ($canViewInternal && $log->note ? ' · ' . $log->note : ''), 'date' => $log->created_at, 'kind' => 'state']);
    }
    foreach ($notesCollection as $note) $events->push(['title' => __('locale.internal_message'), 'text' => \Illuminate\Support\Str::limit($note->note, 130), 'date' => $note->created_at, 'kind' => 'note']);
    foreach ($interventions as $intervention) $events->push(['title' => __('locale.lbl_technical_intervention') . ' #' . $intervention->id, 'text' => $intervention->subject, 'date' => $intervention->created_at, 'kind' => 'intervention']);
    foreach ($repairs as $repair) $events->push(['title' => __('locale.repair') . ' #' . $repair->id, 'text' => $repair->status?->translated_name ?? '', 'date' => $repair->created_at, 'kind' => 'repair']);
    $events = $events->sortByDesc('date');
    $progressLogs = $stateCollection->isEmpty() ? collect() : collect([$stateCollection->first(), $stateCollection->last()])->unique('id');
@endphp

@section('title'){{ __('locale.portal.tickets') }} {{ $reference }} · {{ $subject }}@endsection

@section('content')
<section class="ticket-detail-view issue-detail" id="ticketDetailView" aria-label="{{ __('locale.portal.detail.ticket_record') }}">
    <header class="detail-topbar">
        <a class="detail-back" href="{{ route('issues.index') }}" aria-label="{{ __('locale.portal.detail.back') }}"><i class="bi bi-arrow-left" aria-hidden="true"></i></a>
        <div class="detail-heading">
            <div class="detail-heading-top"><span>{{ $reference }}</span><span class="{{ $typeClass }}"><i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>{{ $issue->translated_type }}</span></div>
            <h1 title="{{ $subject }}">{{ $subject }}</h1>
        </div>
        <div class="detail-header-actions">
            <button class="btn" type="button" data-issue-reply><i class="bi bi-reply-fill" aria-hidden="true"></i>{{ __('locale.portal.detail.reply') }}</button>
            @if($canManageTicket)
                <a class="btn" href="{{ route('issues.edit', $issue->id) }}"><i class="bi bi-pencil-fill" aria-hidden="true"></i>{{ __('locale.btn_edit') }}</a>
                <button class="btn" type="button" data-bs-toggle="modal" data-bs-target="#usersModal"><i class="bi bi-person-plus-fill" aria-hidden="true"></i>{{ __('locale.portal.detail.reassign') }}</button>
                <button class="btn primary" type="button" @if($closingState) data-issue-close="{{ $closingState->id }}" @else data-bs-toggle="modal" data-bs-target="#stateModal" @endif><i class="bi bi-check-lg" aria-hidden="true"></i>{{ $closingState ? __('locale.portal.detail.close_ticket') : __('locale.lbl_state') }}</button>
            @endif
            <div class="dropdown">
                <button class="row-action-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="{{ __('locale.actions') }}"><i class="bi bi-three-dots-vertical" aria-hidden="true"></i></button>
                <ul class="dropdown-menu dropdown-menu-end">
                    @if($canManageTicket)
                        <li><button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#statusModal">{{ __('locale.lbl_status') }}</button></li>
                        <li><button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#stateModal">{{ __('locale.lbl_state') }}</button></li>
                    @endif
                    <li><a class="dropdown-item" href="{{ route('issues.export_pdf', $issue->id) }}" target="_blank" rel="noopener"><i class="bi bi-file-earmark-pdf" aria-hidden="true"></i> {{ __('locale.lbl_print') }}</a></li>
                </ul>
            </div>
        </div>
    </header>
    <div class="detail-body">
        @if($errors->any())<div class="alert alert-danger" role="alert">{{ __('locale.portal.validation_error') }}<ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        @if(session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
        <section class="detail-summary" aria-label="{{ __('locale.lbl_status') }}">
            <span class="status issue-status" style="--issue-status-color:{{ $statusColor }};--issue-status-bg:{{ hexToRgba($statusColor, 0.08) }}"><i class="bi bi-file-earmark-text-fill" aria-hidden="true"></i>{{ $statusLabel }}</span>
            <span class="priority {{ $priorityClass }}"><span class="priority-dot" aria-hidden="true"></span>{{ $priorityLabel ?? '—' }}</span>
            @if($contractStatus)<span class="badge contract {{ $contractStatus === 'avec_contrat' ? 'on' : 'off' }}"><i class="bi bi-shield-fill-check" aria-hidden="true"></i>{{ __('locale.contract_status.' . $contractStatus) }}</span>@endif
            @if($client?->concurrent)<span class="badge bg-danger">{{ __('locale.lbl_concurrent') }}</span>@endif
            @if($client?->default_payment)<span class="badge bg-danger">{{ __('locale.default_payment') }}</span>@endif
            <span class="detail-summary-separator" aria-hidden="true"></span>
            <span class="detail-summary-item"><i class="bi bi-person" aria-hidden="true"></i>{{ __('locale.portal.ticket_assignee') }} <strong class="assignee-list">@forelse($issue->users as $assignee)<span class="assignee" title="{{ $assignee->name }}">{{ $assignee->identifier ?: $assignee->name }}</span>@empty {{ __('locale.lbl_no_users_assigned') }} @endforelse</strong></span>
            @foreach($issue->issues_groups as $group)<span class="assignee team">{{ $group->name }}</span>@endforeach
            <span class="detail-summary-item"><i class="bi bi-clock" aria-hidden="true"></i>SLA <strong class="remaining {{ $slaMinutes !== null && $slaMinutes <= 60 ? 'red' : 'green' }}">{{ $slaLabel }}</strong></span>
            <span class="detail-summary-item"><i class="bi bi-telephone-fill" aria-hidden="true"></i>{{ __('locale.portal.callbacks') }} <strong>{{ $callbackAt ? format_datetime($callbackAt) : '—' }}</strong></span>
        </section>

        @if($canManageTicket)
        <form class="technical-case-banner" id="issue-workflow-form" action="{{ route('issues.updateState', $issue->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="technical-case-field"><label for="workflow-state">{{ __('locale.portal.detail.case_stage') }}</label><select id="workflow-state" name="state_id" required @disabled(collect($states)->isEmpty())>@foreach($states as $state)<option value="{{ $state->id }}" @selected((string) old('state_id', $issue->state_id) === (string) $state->id)>{{ $state->translated_name }}</option>@endforeach</select></div>
            <div class="technical-case-field"><label for="workflow-note">{{ __('locale.portal.detail.next_action') }}</label><input id="workflow-note" name="note" value="{{ old('note', $stateNote) }}" placeholder="{{ __('locale.lbl_note_optional') }}"></div>
            <div class="technical-case-field"><label id="workflow-owner-label">{{ __('locale.portal.detail.owner') }}</label><button class="issue-workflow-value" type="button" data-bs-toggle="modal" data-bs-target="#usersModal" aria-labelledby="workflow-owner-label"><span>{{ $issue->users->pluck('name')->implode(', ') ?: __('locale.lbl_no_users_assigned') }}</span><i class="bi bi-chevron-down" aria-hidden="true"></i></button></div>
            <div class="technical-case-field"><label for="workflow-due">{{ __('locale.portal.detail.deadline') }} · SLA</label><input id="workflow-due" type="text" value="{{ $slaAt ? format_datetime($slaAt) : '—' }}" readonly></div>
            <div class="technical-case-field"><label id="workflow-status-label">{{ __('locale.lbl_status') }}</label><button class="issue-workflow-value" type="button" data-bs-toggle="modal" data-bs-target="#statusModal" aria-labelledby="workflow-status-label"><span>{{ $statusLabel }}</span><i class="bi bi-chevron-down" aria-hidden="true"></i></button></div>
            <button class="btn primary technical-case-save" type="submit" @disabled(collect($states)->isEmpty())><i class="bi bi-floppy-fill" aria-hidden="true"></i>{{ __('locale.btn_save') }}</button>
        </form>
        @endif

        <div class="detail-layout">
            <div class="detail-main">
                <section class="ticket-progress" aria-label="{{ __('locale.lbl_state_history') }}">
                    <div class="ticket-progress-track">
                        <div class="ticket-progress-step"><span class="ticket-progress-label">{{ __('locale.portal.detail.client_request') }}</span><span class="ticket-progress-dot" aria-hidden="true"></span><strong>{{ $contactName ?? $client?->raison_sociale ?? __('locale.system') }}</strong><small>{{ __('locale.portal.detail.created') }}</small><time>{{ format_datetime($issue->created_at) }}</time></div>
                        @forelse($progressLogs as $log)
                            @php $logStatus = $canViewInternal ? $log->state?->internalStatus : $log->state?->externalStatus; @endphp
                            <div class="ticket-progress-step {{ $loop->last ? 'current' : '' }}"><span class="ticket-progress-label">{{ $logStatus?->translated_name ?? $logStatus?->name ?? __('locale.lbl_state') }}</span><span class="ticket-progress-dot" aria-hidden="true"></span><strong>{{ $log->user?->name ?? __('locale.system') }}</strong><small>{{ $loop->last ? __('locale.portal.detail.current_state') : __('locale.lbl_state_history') }}</small><time>{{ format_datetime($log->created_at) }}</time></div>
                        @empty
                            <div class="ticket-progress-step current"><span class="ticket-progress-label">{{ $statusLabel }}</span><span class="ticket-progress-dot" aria-hidden="true"></span><strong>{{ $issue->users->pluck('name')->implode(', ') ?: '—' }}</strong><small>{{ __('locale.portal.detail.current_state') }}</small><time>{{ format_datetime($issue->updated_at ?? $issue->created_at) }}</time></div>
                        @endforelse
                    </div>
                </section>
                <nav class="detail-tabs single-ticket-tabs" id="issueTabs" role="tablist" aria-label="{{ __('locale.portal.detail.sections') }}">
                    <button class="detail-tab active" id="messages-tab" type="button" data-bs-toggle="tab" data-bs-target="#messages" role="tab" aria-controls="messages" aria-selected="true"><i class="bi bi-chat-dots" aria-hidden="true"></i>{{ __('locale.portal.detail.activity') }}<span class="conversation-count">{{ $messagesCollection->count() + $notesCollection->count() + ($canSeeAll && $issue->internal_notes ? 1 : 0) }}</span></button>
                    <button class="detail-tab" id="diagnostic-tab" type="button" data-bs-toggle="tab" data-bs-target="#diagnostic" role="tab" aria-controls="diagnostic" aria-selected="false"><i class="bi bi-clipboard-pulse" aria-hidden="true"></i>{{ __('locale.portal.detail.diagnostic') }}</button>
                    <button class="detail-tab" id="tech-intervs-tab" type="button" data-bs-toggle="tab" data-bs-target="#tech-intervs" role="tab" aria-controls="tech-intervs" aria-selected="false"><i class="bi bi-person-gear" aria-hidden="true"></i>{{ __('locale.menu_interventions') }}<span class="conversation-count">{{ $interventions->count() }}</span></button>
                    <button class="detail-tab" id="repairs-tab" type="button" data-bs-toggle="tab" data-bs-target="#repairs" role="tab" aria-controls="repairs" aria-selected="false"><i class="bi bi-wrench" aria-hidden="true"></i>{{ __('locale.menu_repairs') }}<span class="conversation-count">{{ $repairs->count() }}</span></button>
                    <button class="detail-tab" id="quotes-tab" type="button" data-bs-toggle="tab" data-bs-target="#quotes" role="tab" aria-controls="quotes" aria-selected="false"><i class="bi bi-receipt" aria-hidden="true"></i>{{ __('locale.portal.detail.quotes_parts') }}<span class="conversation-count">{{ $quotes->count() }}</span></button>
                    <button class="detail-tab" id="history-tab" type="button" data-bs-toggle="tab" data-bs-target="#history" role="tab" aria-controls="history" aria-selected="false"><i class="bi bi-folder2-open" aria-hidden="true"></i>{{ __('locale.portal.detail.documents_history') }}</button>
                </nav>
                <div class="tab-content" id="issueTabContent">
                    <section class="tab-pane show active detail-panel conversation-panel" id="messages" role="tabpanel" aria-labelledby="messages-tab" tabindex="0" data-detail-panel="activity">
                        <div class="technical-activity-layout">
                            <div class="technical-chat-column">
                                <div class="chat-thread" id="ticketConversation">
                                    <article class="chat-message client"><div class="chat-bubble-wrap"><div class="chat-bubble"><strong class="chat-message-subject">{{ __('locale.lbl_subject') }} : {{ $subject }}</strong><div class="issue-rich-text">{!! $issue->description !!}</div><div class="chat-message-meta"><span>{{ $contactName ?? $client?->raison_sociale ?? __('locale.system') }}</span><time>{{ format_datetime($issue->created_at) }}</time></div></div></div></article>
                                    @include('issues.partials.messages_list', ['messages' => $messagesCollection, 'states' => $stateCollection, 'notes' => $notesCollection])
                                    @if($canSeeAll)
                                        @if($issue->internal_notes)<article class="chat-message internal"><div class="chat-bubble"><strong class="chat-message-subject"><i class="bi bi-lock-fill" aria-hidden="true"></i> {{ __('locale.internal_message') }}</strong><div class="issue-rich-text">{!! $issue->internal_notes !!}</div><div class="chat-message-meta"><time>{{ format_datetime($issue->created_at) }}</time></div></div></article>@endif
                                    @endif
                                </div>
                                <form class="reply-box" id="message_form" action="{{ route('messages_store', $issue->id) }}" method="POST">
                                    @csrf <input type="hidden" name="visible_to_client" id="visible_to_client" value="1">
                                    <div class="reply-modes"><button class="reply-mode active" type="button" data-issue-reply-mode="1" aria-pressed="true">{{ __('locale.portal.detail.client_reply') }}</button>@if($canSeeAll)<button class="reply-mode" type="button" data-issue-reply-mode="0" aria-pressed="false"><i class="bi bi-lock-fill" aria-hidden="true"></i>{{ __('locale.internal_message') }}</button>@endif</div>
                                    <div class="issue-compose-fields"><label for="template_select">{{ __('locale.select_template') }}</label><select id="template_select" class="form-select"><option value="">{{ __('locale.select_template') }}</option>@foreach($templates as $template)<option value="{{ $template->id }}" data-subject="{{ $template->subject }}" data-message="{{ $template->message }}">{{ $template->subject }}</option>@endforeach</select><label for="subject">{{ __('locale.lbl_subject') }}</label><input type="text" class="form-control" id="subject" name="subject" value="{{ old('subject', $subject) }}" required></div>
                                    <label for="message" class="visually-hidden">{{ __('locale.lbl_message') }}</label><textarea id="message" name="message" placeholder="{{ __('locale.portal.detail.reply_placeholder') }}" required>{{ old('message') }}</textarea>
                                    <div class="reply-footer"><button class="btn" type="button" data-issue-open-tab="history-tab"><i class="bi bi-paperclip" aria-hidden="true"></i>{{ __('locale.documents') }}</button><button class="btn primary" type="submit"><i class="bi bi-send-fill" aria-hidden="true"></i>{{ __('locale.btn_post_message') }}</button></div>
                                </form>
                            </div>
                            <aside class="technical-events" aria-label="{{ __('locale.portal.detail.timeline') }}"><h3>{{ __('locale.portal.detail.timeline') }}</h3><div class="technical-event-list">@forelse($events->take(10) as $event)<article class="technical-event"><strong>{{ $event['title'] }}</strong><span>{{ $event['text'] }}</span><time>{{ format_datetime($event['date']) }}</time></article>@empty<p class="issue-empty">{{ __('locale.lbl_no_status_changes') }}</p>@endforelse</div></aside>
                        </div>
                    </section>
                    <section class="tab-pane detail-panel" id="diagnostic" role="tabpanel" aria-labelledby="diagnostic-tab" tabindex="0">
                        <div class="technical-panel-head"><h2>{{ __('locale.portal.detail.diagnostic') }}</h2><div class="technical-quick-actions">@if($canManageTicket)<a class="btn" href="{{ route('tech_intervs.create', ['issue_id' => $issue->id]) }}"><i class="bi bi-plus-lg" aria-hidden="true"></i>{{ __('locale.lbl_technical_intervention') }}</a>@endif</div></div>
                        <div class="diagnostic-grid"><article class="diagnostic-card"><h3>{{ __('locale.description') }}</h3><div class="issue-rich-text">{!! $issue->description !!}</div><dl class="detail-info-list"><div class="detail-info"><dt>{{ __('locale.lbl_origine') }}</dt><dd>{{ $issue->origine?->translated_name ?? '—' }}</dd></div><div class="detail-info"><dt>{{ __('locale.lbl_category') }}</dt><dd>{{ $issue->category?->translated_name ?? '—' }}</dd></div><div class="detail-info"><dt>{{ __('locale.lbl_criticity_client') }}</dt><dd>{{ $issue->translated_criticity_client }}</dd></div>@if($canViewInternal)<div class="detail-info"><dt>{{ __('locale.lbl_criticity_internal') }}</dt><dd>{{ $issue->translated_criticity_internal }}</dd></div>@endif</dl></article>
                        @if($canSeeAll)<article class="diagnostic-card" id="internal-notes"><h3>{{ __('locale.lbl_notes_internal') }}</h3>@include('issues.partials.internal_notes_list', ['notes' => $notesCollection])<form action="{{ route('issue_notes.store', $issue->id) }}" method="POST">@csrf<label class="visually-hidden" for="diagnostic-note">{{ __('locale.lbl_add_new_note') }}</label><textarea class="form-control mb-3" name="note" id="diagnostic-note" rows="6" required></textarea><button class="btn primary" type="submit"><i class="bi bi-floppy-fill" aria-hidden="true"></i>{{ __('locale.btn_save') }}</button></form></article>@endif</div>
                    </section>
                    <section class="tab-pane detail-panel" id="tech-intervs" role="tabpanel" aria-labelledby="tech-intervs-tab" tabindex="0">
                        <div class="technical-panel-head"><h2>{{ __('locale.menu_interventions') }}</h2>@if($canManageTicket)<a class="btn primary" href="{{ route('tech_intervs.create', ['issue_id' => $issue->id]) }}"><i class="bi bi-plus-lg" aria-hidden="true"></i>{{ __('locale.portal.detail.create_intervention') }}</a>@endif</div>
                        <div class="technical-object-list">@forelse($interventions as $interv)<article class="technical-object-card"><div class="technical-object-icon"><i class="bi bi-tools" aria-hidden="true"></i></div><div><strong>#{{ $interv->id }} · {{ $interv->subject }}</strong><small>{{ $interv->site?->raison_sociale ?? '—' }} · {{ $interv->intervention_date ?? '—' }}</small><small>{{ $interv->assignedUsers->pluck('name')->implode(', ') ?: __('locale.lbl_no_users_assigned') }}</small><details class="issue-record-details"><summary>{{ __('locale.description') }}</summary><p>{{ $interv->description }}</p><dl class="detail-info-list"><div class="detail-info"><dt>{{ __('locale.lbl_state') }}</dt><dd>{{ $interv->state?->translated_name ?? '—' }}</dd></div><div class="detail-info"><dt>{{ __('locale.lbl_end_date') }}</dt><dd>{{ $interv->end_date ?? '—' }}</dd></div><div class="detail-info"><dt>{{ __('locale.lbl_severity') }}</dt><dd>{{ $interv->severity?->translated_name ?? '—' }}</dd></div><div class="detail-info"><dt>{{ __('locale.lbl_type') }}</dt><dd>{{ $interv->type?->translated_name ?? '—' }}</dd></div><div class="detail-info"><dt>{{ __('locale.lbl_planned') }}</dt><dd>{{ $interv->is_planned ? '✓' : '—' }}</dd></div><div class="detail-info"><dt>{{ __('locale.lbl_priority') }}</dt><dd>{{ $interv->priority ? __('locale.criticity_' . strtolower((string) $interv->priority)) : '—' }}</dd></div><div class="detail-info"><dt>{{ __('locale.lbl_criticity_client') }}</dt><dd>{{ $interv->criticity_external ? __('locale.criticity_' . strtolower((string) $interv->criticity_external)) : '—' }}</dd></div>@if($canViewInternal)<div class="detail-info"><dt>{{ __('locale.lbl_criticity_internal') }}</dt><dd>{{ $interv->criticity_internal ? __('locale.criticity_' . strtolower((string) $interv->criticity_internal)) : '—' }}</dd></div><div class="detail-info"><dt>{{ __('locale.lbl_visible_to_client') }}</dt><dd>{{ $interv->is_visible_to_client ? '✓' : '—' }}</dd></div>@endif</dl>@if($canSeeAll && $interv->notes)<p>{{ $interv->notes }}</p>@endif</details></div><div class="technical-object-actions"><span class="technical-status info">{{ $interv->status?->translated_name ?? $interv->status?->name ?? '—' }}</span>@if($canManageTicket)<a class="btn" href="{{ route('tech_intervs.edit', $interv->id) }}">{{ __('locale.btn_edit') }}</a>@endif</div></article>@empty<p class="issue-empty">{{ __('locale.lbl_no_records_found') }}</p>@endforelse</div>
                    </section>
                    <section class="tab-pane detail-panel" id="repairs" role="tabpanel" aria-labelledby="repairs-tab" tabindex="0">
                        <div class="technical-panel-head"><h2>{{ __('locale.menu_repairs') }}</h2></div><div class="technical-object-list">@forelse($repairs as $repair)<article class="technical-object-card"><div class="technical-object-icon"><i class="bi bi-wrench" aria-hidden="true"></i></div><div><strong>{{ __('locale.repair') }} #{{ $repair->id }}</strong>@if($canSeeAll)<small>{{ $repair->notes }}</small>@endif</div><div class="technical-object-actions"><span class="technical-status info">{{ $repair->status?->translated_name ?? $repair->status?->name ?? __('locale.lbl_no_status') }}</span>@if($canManageTicket)<a class="btn" href="{{ route('repairs.edit', $repair->id) }}">{{ __('locale.btn_edit') }}</a>@endif</div></article>@empty<p class="issue-empty">{{ __('locale.lbl_no_records_found') }}</p>@endforelse</div>
                    </section>
                    <section class="tab-pane detail-panel" id="quotes" role="tabpanel" aria-labelledby="quotes-tab" tabindex="0">
                        <div class="technical-panel-head"><h2>{{ __('locale.portal.detail.quotes_parts') }}</h2></div><div class="technical-object-list">@forelse($quotes as $quote)<article class="technical-object-card"><div class="technical-object-icon"><i class="bi bi-receipt" aria-hidden="true"></i></div><div><strong>{{ $quote->reference ?? '#' . $quote->id }}</strong><small>{{ $quote->subject ?? $quote->name ?? '' }}</small></div><div class="technical-object-actions"><span class="technical-status info">{{ data_get($quote, 'status.translated_name', '') }}</span></div></article>@empty<p class="issue-empty">{{ __('locale.portal.detail.no_linked_quotes') }}</p>@endforelse</div>
                    </section>
                    <section class="tab-pane detail-panel" id="history" role="tabpanel" aria-labelledby="history-tab" tabindex="0">
                        <div class="technical-panel-head"><h2>{{ __('locale.portal.detail.documents_history') }}</h2><a class="btn" href="{{ route('issues.export_pdf', $issue->id) }}" target="_blank" rel="noopener"><i class="bi bi-file-earmark-pdf" aria-hidden="true"></i>{{ __('locale.lbl_print') }}</a>@if($canManageTicket)<a class="btn" href="{{ route('issues.edit', $issue->id) }}"><i class="bi bi-paperclip" aria-hidden="true"></i>{{ __('locale.lbl_attach_document') }}</a>@endif</div>
                        <div class="diagnostic-grid"><article class="diagnostic-card"><h3>{{ __('locale.documents') }}</h3><p class="issue-document-name">{{ $issue->documents ?: __('locale.lbl_no_records_found') }}</p></article><article class="diagnostic-card"><h3>{{ __('locale.lbl_state_history') }}</h3><div class="technical-event-list">@forelse($stateCollection->reverse() as $log)@php $logStatus = $canViewInternal ? $log->state?->internalStatus : $log->state?->externalStatus; @endphp<article class="technical-event"><strong>{{ $logStatus?->translated_name ?? $logStatus?->name ?? '—' }}</strong><span>{{ $log->user?->name ?? __('locale.system') }}</span>@if($canViewInternal && $log->note)<span>{{ $log->note }}</span>@endif<time>{{ format_datetime($log->created_at) }}</time></article>@empty<p class="issue-empty">{{ __('locale.lbl_no_status_changes') }}</p>@endforelse</div></article></div>
                    </section>
                </div>
            </div>
            <aside class="detail-side" aria-label="{{ __('locale.portal.detail.ticket_context') }}">
                <section class="detail-side-card"><h2>{{ __('locale.portal.detail.client_requester') }}</h2><dl class="detail-info-list"><div class="detail-info"><dt>{{ __('locale.lbl_client') }}</dt><dd>{{ $client?->raison_sociale ?? '—' }}</dd></div><div class="detail-info"><dt>{{ __('locale.lbl_site') }}</dt><dd>{{ $site?->raison_sociale ?? $client?->ville ?? '—' }}</dd></div><div class="detail-info"><dt>{{ __('locale.portal.detail.contact') }}</dt><dd>{{ $contactName ?? '—' }}@if($contactPhone)<br><a href="tel:{{ preg_replace('/[^+0-9]/', '', $contactPhone) }}">{{ $contactPhone }}</a>@endif</dd></div><div class="detail-info"><dt>{{ __('locale.portal.email') }}</dt><dd>@if($contactEmail)<a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>@else — @endif</dd></div></dl></section>
                <section class="detail-side-card"><h2>{{ __('locale.portal.detail.contract_sla') }}</h2><div class="detail-sla {{ $slaMinutes === null ? 'inactive' : '' }}"><i class="bi bi-hourglass-split" aria-hidden="true"></i>{{ $slaLabel }}</div><dl class="detail-info-list"><div class="detail-info"><dt>{{ __('locale.portal.ticket_contract') }}</dt><dd>{{ $contractStatus ? __('locale.contract_status.' . $contractStatus) : '—' }}</dd></div>@foreach($contracts as $contract)<div class="detail-info"><dt>{{ __('locale.lbl_name') }}</dt><dd>{{ $contract->contract_name ?: ($contract->type?->translated_name ?? '#' . $contract->id) }}</dd></div>@endforeach</dl></section>
                <section class="detail-side-card"><h2>{{ __('locale.portal.detail.related_equipment') }}</h2><dl class="detail-info-list"><div class="detail-info"><dt>{{ __('locale.lbl_equipment') }}</dt><dd>{{ $equipment?->name ?? $attributes['equipment_name'] ?? '—' }}</dd></div><div class="detail-info"><dt>{{ __('locale.lbl_reference') }}</dt><dd>{{ $equipment?->reference ?? $attributes['equipment_reference'] ?? '—' }}</dd></div><div class="detail-info"><dt>{{ __('locale.lbl_category') }}</dt><dd>{{ $issue->category?->translated_name ?? '—' }}</dd></div></dl></section>
                <section class="detail-side-card"><h2>{{ __('locale.portal.detail.overview') }}</h2><dl class="detail-info-list"><div class="detail-info"><dt>{{ __('locale.menu_interventions') }}</dt><dd><button type="button" class="issue-count-link" data-issue-open-tab="tech-intervs-tab">{{ $interventions->count() }}</button></dd></div><div class="detail-info"><dt>{{ __('locale.menu_repairs') }}</dt><dd><button type="button" class="issue-count-link" data-issue-open-tab="repairs-tab">{{ $repairs->count() }}</button></dd></div><div class="detail-info"><dt>{{ __('locale.portal.detail.quotes_parts') }}</dt><dd><button type="button" class="issue-count-link" data-issue-open-tab="quotes-tab">{{ $quotes->count() }}</button></dd></div></dl></section>
            </aside>
        </div>
    </div>
</section>
@if($canManageTicket)
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
						<select name="status_id" id="status_id" class="form-select select-status" required>
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
						<select name="state_id" id="state_id" class="form-select select-state" required>
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
@endif
@endsection

@push('scripts')
<script>
(() => {
    const detail = document.getElementById('ticketDetailView');
    if (!detail) return;
    function openTab(id) {
        const tab = document.getElementById(id);
        if (tab) { bootstrap.Tab.getOrCreateInstance(tab).show(); tab.scrollIntoView({block:'nearest', inline:'nearest'}); }
    }
    detail.querySelectorAll('[data-issue-open-tab]').forEach(button => button.addEventListener('click', () => openTab(button.dataset.issueOpenTab)));
    detail.querySelector('[data-issue-reply]')?.addEventListener('click', () => { openTab('messages-tab'); document.getElementById('message').focus(); });
    detail.querySelector('[data-issue-close]')?.addEventListener('click', event => {
        const select = document.getElementById('state_id');
        select.value = event.currentTarget.dataset.issueClose;
        if (window.jQuery) jQuery(select).trigger('change');
        bootstrap.Modal.getOrCreateInstance(document.getElementById('stateModal')).show();
    });
    detail.querySelectorAll('[data-issue-reply-mode]').forEach(button => button.addEventListener('click', () => {
        detail.querySelectorAll('[data-issue-reply-mode]').forEach(mode => { mode.classList.toggle('active', mode === button); mode.setAttribute('aria-pressed', String(mode === button)); });
        document.getElementById('visible_to_client').value = button.dataset.issueReplyMode;
        document.getElementById('message').focus();
    }));
    document.getElementById('template_select').addEventListener('change', event => {
        const option = event.target.selectedOptions[0];
        document.getElementById('subject').value = option?.dataset.subject || @json($subject);
        document.getElementById('message').value = option?.dataset.message || '';
    });
    document.getElementById('message_form').addEventListener('keydown', event => {
        if ((event.ctrlKey || event.metaKey) && event.key === 'Enter') { event.preventDefault(); event.currentTarget.requestSubmit(); }
    });
    if (window.jQuery?.fn.select2) {
        [['#status_id','#statusModal'], ['#state_id','#stateModal'], ['#usersSelect','#usersModal']].forEach(([field,modal]) => {
            const select = jQuery(field);
            if (select.length) { if (select.hasClass('select2-hidden-accessible')) select.select2('destroy'); select.select2({width:'100%', dropdownParent:jQuery(modal)}); }
        });
    }
})();
</script>
@endpush
