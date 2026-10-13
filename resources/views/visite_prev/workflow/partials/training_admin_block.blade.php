<div class="card mb-3 p-3" id="attendee-{{ $index }}">
    <div class="row mb-2">
        <div class="col-md-4">
            <label>{{ __('locale.visite_prev.first_name') }}</label>
            <input type="text" name="attendees[{{ $index }}][first_name]" class="form-control"
                   value="{{ old("attendees.$index.first_name", $attendee->first_name ?? '') }}">
        </div>
        <div class="col-md-4">
            <label>{{ __('locale.visite_prev.last_name') }}</label>
            <input type="text" name="attendees[{{ $index }}][last_name]" class="form-control"
                   value="{{ old("attendees.$index.last_name", $attendee->last_name ?? '') }}">
        </div>
        <div class="col-md-3">
            <label>{{ __('locale.visite_prev.position') }}</label>
            <input type="text" name="attendees[{{ $index }}][position]" class="form-control"
                   value="{{ old("attendees.$index.position", $attendee->position ?? '') }}">
        </div>
        <div class="col-md-1 d-flex align-items-end justify-content-end">
            <button type="button" class="btn btn-sm btn-danger" onclick="removeAttendee({{ $index }})">&times;</button>
        </div>
    </div>

    <div class="mb-2">
        <label>{{ __('locale.visite_prev.signature') }}</label>

        @php
            $oldSignature = old("attendees.$index.signature");
            $existingSignature = $attendee->signature ?? null;
            $signatureBase64 = null;

            if ($oldSignature) {
                $signatureBase64 = $oldSignature;
            } elseif ($existingSignature && file_exists(public_path($existingSignature))) {
                $mime = mime_content_type(public_path($existingSignature));
                $content = base64_encode(file_get_contents(public_path($existingSignature)));
                $signatureBase64 = "data:$mime;base64,$content";
            }
        @endphp

        @if ($signatureBase64)
            <div>
                <img src="{{ $signatureBase64 }}" alt="Signature" class="img-fluid" style="max-height: 250px;">
                <input type="hidden" name="attendees[{{ $index }}][signature]" value="{{ $signatureBase64 }}">
            </div>
        @else
            <div id="signature-box-{{ $index }}" class="signature-box"></div>
            <input type="hidden" name="attendees[{{ $index }}][signature]" id="signature-{{ $index }}">
        @endif
    </div>
</div>
