<div class="card mb-3 p-3">
    @if($elevator && $elevator->id)
        <input type="hidden" name="elevators[{{ $index }}][id]" value="{{ $elevator->id }}">
    @endif

    <div class="row mb-2">
        <div class="col-md-4">
            <label>{{ __('locale.visite_prev.elevator') }}</label>
            <input type="text"
                   name="elevators[{{ $index }}][elevator]"
                   class="form-control"
                   value="{{ old("elevators.$index.elevator", $elevator->elevator ?? '') }}">
        </div>
        <div class="col-md-4">
            <label>{{ __('locale.visite_prev.linenumber') }}</label>
            <input type="text"
                   name="elevators[{{ $index }}][linenumber]"
                   class="form-control"
                   value="{{ old("elevators.$index.linenumber", $elevator->linenumber ?? '') }}">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <div class="form-check">
                <input class="form-check-input"
                       type="checkbox"
                       name="elevators[{{ $index }}][passes_by_pabx]"
                       {{ old("elevators.$index.passes_by_pabx", $elevator->passes_by_pabx ?? false) ? 'checked' : '' }}>
                <label class="form-check-label">{{ __('locale.visite_prev.passes_by_pabx') }}</label>
            </div>
        </div>
    </div>
</div>
