<div class="message-board d-flex flex-column">
	@forelse($notes as $note)
		<div class="message {{ $note->user_id === auth()->id() ? 'my-message' : 'other-message' }}">
			@if ($note->user_id !== auth()->id())
				<div class="message-sender">{{ $note->user->name }}</div>
			@endif
			<p class="mb-0">{!! nl2br(e($note->note)) !!}</p>
			<div class="timestamp">{{ format_datetime($note->created_at) }}</div>
		</div>
	@empty
		<p>{{ __('locale.no_notes') }}</p>
	@endforelse
</div>
