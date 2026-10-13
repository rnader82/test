<div class="message-board d-flex flex-column">
	@php
		$entries = collect();
		foreach ($messages as $m) {
		    $entries->push(['type' => 'message', 'item' => $m]);
		}
		foreach ($states as $s) {
		    $entries->push(['type' => 'state', 'item' => $s]);
		}
		$entries = $entries->sortBy(fn($e) => $e['item']->created_at);
		$canViewInternal = auth()->user()->is_admin || auth()->user()->hasPermission('view-internal-status');
		$canMakeInternal = auth()->user()->is_admin && auth()->user()->hasPermission('manage-messages');
	@endphp
	@forelse($entries as $entry)
		@if ($entry['type'] === 'message')
			@php $message = $entry['item']; @endphp <div class="message {{ $message->user_id === auth()->id() ? 'my-message' : 'other-message' }}">
				@if ($message->user_id !== auth()->id())
					<div class="message-sender">{{ $message->user->name }}</div>
				@endif
				<p class="mb-0">
				<h5>{{ $message->subject }}</h5><br />{{ $message->message }}</p>
				<div class="timestamp"> {{ format_datetime($message->created_at) }} @if ($message->visible_to_client)
						@if ($canMakeInternal)
							<form method="POST"
								action="{{ route('messages.make_internal', $message->id) }}" class="d-inline"> @csrf <button type="submit" class="badge bg-primary border-0"
									title="{{ __('locale.make_internal_confirmation') }}" onclick="return confirm('{{ __('locale.make_internal_confirmation') }}');"> {{ __('locale.visible_to_client') }} </button>
							</form>
						@else
							<span class="badge bg-primary ms-2">{{ __('locale.visible_to_client') }}</span>
						@endif
					@else
						<span class="badge bg-secondary ms-2">{{ __('locale.internal_message') }}</span>
					@endif
				</div>
			</div>
		@else
			@php
				$stateLog = $entry['item'];
				$status = $canViewInternal ? optional($stateLog->state)->internalStatus : optional($stateLog->state)->externalStatus;
			@endphp <div
				class="status-entry" @if ($stateLog->note) data-bs-toggle="tooltip" title="{{ $stateLog->note }}" @endif>
				<div class="status-header">
					<span class="status-sender">{{ $stateLog->user->name ?? __('locale.system') }}</span>
					<span class="badge" style="background-color: {{ hexToRgba($status->color ?? '#ccc', 0.3) }}; color: {{ $status->color ?? '#ccc' }};"> {{ $status?->translated_name ?? __('locale.unknown_status') }}
					</span>
				</div>
				<div class="timestamp">{{ format_datetime($stateLog->created_at) }}</div>
			</div>
		@endif @empty <p>{{ __('locale.no_messages') }}</p>
	@endforelse
</div>
