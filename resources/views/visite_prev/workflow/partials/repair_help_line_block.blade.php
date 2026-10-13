<div class="card mb-3 p-3">
    @if($line && $line->id)
        <input type="hidden" name="help_lines[{{ $index }}][id]" value="{{ $line->id }}">
    @endif

    <div class="row mb-2">
        <div class="col-md-4">
            <label>{{ __('locale.visite_prev.name') }}</label>
            <input type="text"
                   name="help_lines[{{ $index }}][name]"
                   class="form-control"
                   value="{{ old("help_lines.$index.name", $line->name ?? '') }}">
        </div>
        <div class="col-md-4">
            <label>{{ __('locale.visite_prev.linenumber') }}</label>
            <input type="text"
                   name="help_lines[{{ $index }}][linenumber]"
                   class="form-control"
                   value="{{ old("help_lines.$index.linenumber", $line->linenumber ?? '') }}">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <div class="form-check">
                <input class="form-check-input"
                       type="checkbox"
                       name="help_lines[{{ $index }}][passes_by_pabx]"
                       {{ old("help_lines.$index.passes_by_pabx", $line->passes_by_pabx ?? false) ? 'checked' : '' }}>
                <label class="form-check-label">{{ __('locale.visite_prev.passes_by_pabx') }}</label>
            </div>
        </div>
    </div>
</div>
