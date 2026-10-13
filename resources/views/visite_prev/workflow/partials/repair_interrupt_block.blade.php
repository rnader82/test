<div class="card mb-3 p-3">
    @if($repair && $repair->id)
        <input type="hidden" name="repairs[{{ $index }}][id]" value="{{ $repair->id }}">
    @endif

    <div class="row mb-2">
        <div class="col-md-4">
            <label>{{ __('locale.visite_prev.equipment') }}</label>
            <input type="text"
                   name="repairs[{{ $index }}][equipment]"
                   class="form-control"
                   value="{{ old("repairs.$index.equipment", $repair->equipment ?? '') }}">
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <div class="form-check">
                <input class="form-check-input"
                       type="checkbox"
                       name="repairs[{{ $index }}][disjoncteur]"
                       {{ old("repairs.$index.disjoncteur", $repair->disjoncteur ?? false) ? 'checked' : '' }}>
                <label class="form-check-label">{{ __('locale.visite_prev.disjoncteur') }}</label>
            </div>
        </div>

        <div class="col-md-3">
            <label>{{ __('locale.visite_prev.type') }}</label>
            <input type="text"
                   name="repairs[{{ $index }}][type]"
                   class="form-control"
                   value="{{ old("repairs.$index.type", $repair->type ?? '') }}">
        </div>

        <div class="col-md-3">
            <label>{{ __('locale.visite_prev.situation') }}</label>
            <input type="text"
                   name="repairs[{{ $index }}][situation]"
                   class="form-control"
                   value="{{ old("repairs.$index.situation", $repair->situation ?? '') }}">
        </div>
    </div>
</div>
