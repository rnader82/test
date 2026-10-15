@php
    $isStaff = auth()->user()->is_admin || auth()->user()->is_employee;
    $canViewInternal = auth()->user()->is_admin || auth()->user()->hasPermission('view-internal-status');
    $canMakeInternal = auth()->user()->is_admin && auth()->user()->hasPermission('manage-messages');
    $entries = collect();
    foreach (($isStaff ? $messages : $messages->where('visible_to_client', true)) as $message) $entries->push(['type' => 'message', 'item' => $message]);
    foreach ($states as $stateLog) $entries->push(['type' => 'state', 'item' => $stateLog]);
    if ($isStaff) foreach (($notes ?? collect()) as $note) $entries->push(['type' => 'note', 'item' => $note]);
    $entries = $entries->sortBy(fn ($entry) => $entry['item']->created_at);
@endphp
<div class="message-board issue-message-list">
    @forelse($entries as $entry)
        @if($entry['type'] === 'message')
            @php
                $message = $entry['item'];
                $isSupport = $message->user?->is_admin || $message->user?->is_employee || ($isStaff && $message->user_id === auth()->id());
                $messageClass = !$message->visible_to_client ? 'internal' : ($isSupport ? 'support' : 'client');
            @endphp
            <article class="message chat-message {{ $messageClass }} {{ $message->user_id === auth()->id() ? 'my-message' : 'other-message' }}">
                <div class="chat-bubble-wrap"><div class="chat-bubble">
                    <strong class="chat-message-subject">@if(!$message->visible_to_client)<i class="bi bi-lock-fill" aria-hidden="true"></i> @endif{{ $message->subject }}</strong>
                    <div class="chat-message-text">{{ $message->message }}</div>
                    <div class="chat-message-meta">
                        @if($message->visible_to_client && $canMakeInternal)
                            <form method="POST" action="{{ route('messages.make_internal', $message->id) }}" class="chat-message-visibility">@csrf<button type="submit" class="issue-visibility-button" onclick="return confirm(@js(__('locale.make_internal_confirmation')))"><i class="bi bi-eye" aria-hidden="true"></i> {{ __('locale.visible_to_client') }}</button></form>
                        @else
                            <span class="chat-message-visibility"><i class="bi {{ $message->visible_to_client ? 'bi-eye' : 'bi-lock-fill' }}" aria-hidden="true"></i> {{ $message->visible_to_client ? __('locale.visible_to_client') : __('locale.internal_message') }}</span>
                        @endif
                        <span class="message-sender">{{ $message->user?->name ?? __('locale.system') }}</span><time class="timestamp">{{ format_datetime($message->created_at) }}</time>
                    </div>
                </div></div>
            </article>
        @elseif($entry['type'] === 'note')
            @php $note = $entry['item']; @endphp
            <article class="message chat-message internal"><div class="chat-bubble"><strong class="chat-message-subject"><i class="bi bi-lock-fill" aria-hidden="true"></i> {{ __('locale.internal_message') }}</strong><div class="chat-message-text">{{ $note->note }}</div><div class="chat-message-meta"><span>{{ $note->user?->name ?? __('locale.system') }}</span><time>{{ format_datetime($note->created_at) }}</time></div></div></article>
        @else
            @php
                $stateLog = $entry['item'];
                $status = $canViewInternal ? $stateLog->state?->internalStatus : $stateLog->state?->externalStatus;
            @endphp
            <article class="status-entry issue-state-event"><i class="bi bi-arrow-repeat" aria-hidden="true"></i><strong>{{ $status?->translated_name ?? __('locale.unknown_status') }}</strong><span>{{ $stateLog->user?->name ?? __('locale.system') }}</span><time>{{ format_datetime($stateLog->created_at) }}</time></article>
        @endif
    @empty
        <p class="issue-empty">{{ __('locale.no_messages') }}</p>
    @endforelse
</div>
