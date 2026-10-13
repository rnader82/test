<div class="card mb-3 p-3">
    @if($input && $input->id)
        <input type="hidden" name="inputs[{{ $index }}][id]" value="{{ $input->id }}">
    @endif

    <div class="row mb-2">
        <div class="col-md-4">
            <label>{{ __('locale.visite_prev.name') }}</label>
            <input type="text" name="inputs[{{ $index }}][name]" class="form-control"
                   value="{{ old("inputs.$index.name", $input->name ?? '') }}">
        </div>
        <div class="col-md-4">
            <label>{{ __('locale.visite_prev.make') }}</label>
            <input type="text" name="inputs[{{ $index }}][make]" class="form-control"
                   value="{{ old("inputs.$index.make", $input->make ?? '') }}">
        </div>
        <div class="col-md-4">
            <label>{{ __('locale.visite_prev.model') }}</label>
            <input type="text" name="inputs[{{ $index }}][model]" class="form-control"
                   value="{{ old("inputs.$index.model", $input->model ?? '') }}">
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-3">
            <label>{{ __('locale.visite_prev.batteries') }}</label>
            <input type="number" name="inputs[{{ $index }}][number_of_batteries]" class="form-control"
                   value="{{ old("inputs.$index.number_of_batteries", $input->number_of_batteries ?? '') }}">
        </div>
        <div class="col-md-3">
            <label>{{ __('locale.visite_prev.montage') }}</label>
            <input type="text" name="inputs[{{ $index }}][montage]" class="form-control"
                   value="{{ old("inputs.$index.montage", $input->montage ?? '') }}">
        </div>
        <div class="col-md-3">
            <label>{{ __('locale.visite_prev.installation_date') }}</label>
            <input type="date" name="inputs[{{ $index }}][installation_date]" class="form-control"
                   value="{{ old("inputs.$index.installation_date", isset($input) && $input->installation_date ? $input->installation_date->format('Y-m-d') : '') }}">
        </div>
        <div class="col-md-3">
            <label>{{ __('locale.visite_prev.location') }}</label>
            <input type="text" name="inputs[{{ $index }}][location]" class="form-control"
                   value="{{ old("inputs.$index.location", $input->location ?? '') }}">
        </div>
    </div>
    

    <div class="row mb-2">
        <div class="col-md-3">
            <label>{{ __('locale.visite_prev.charge_tension') }}</label>
            <input type="number" step="0.01" name="inputs[{{ $index }}][charge_tension]" class="form-control"
                   value="{{ old("inputs.$index.charge_tension", $input->charge_tension ?? '') }}">
        </div>
        <div class="col-md-3">
            <label>{{ __('locale.visite_prev.battery_tension') }}</label>
            <input type="number" step="0.01" name="inputs[{{ $index }}][battery_tension]" class="form-control"
                   value="{{ old("inputs.$index.battery_tension", $input->battery_tension ?? '') }}">
        </div>
        <div class="col-md-3">
            <label>{{ __('locale.visite_prev.tension_after_power_cut') }}</label>
            <input type="number" step="0.01" name="inputs[{{ $index }}][tension_after_power_cut]" class="form-control"
                   value="{{ old("inputs.$index.tension_after_power_cut", $input->tension_after_power_cut ?? '') }}">
        </div>
        <div class="col-md-3">
            <label>{{ __('locale.visite_prev.tension_after_30mins_power_cut') }}</label>
            <input type="number" step="0.01" name="inputs[{{ $index }}][tension_after_30mins_power_cut]" class="form-control"
                   value="{{ old("inputs.$index.tension_after_30mins_power_cut", $input->tension_after_30mins_power_cut ?? '') }}">
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-6 d-flex align-items-end">
            <div class="form-check">
                <input type="checkbox" class="form-check-input"
                       name="inputs[{{ $index }}][needs_change]"
                       {{ old("inputs.$index.needs_change", $input->needs_change ?? false) ? 'checked' : '' }}>
                <label class="form-check-label">{{ __('locale.visite_prev.needs_change') }}</label>
            </div>
        </div>
        <div class="col-md-6 d-flex align-items-end">
            <div class="form-check">
                <input type="checkbox" class="form-check-input"
                       name="inputs[{{ $index }}][battery_needs_change]"
                       {{ old("inputs.$index.battery_needs_change", $input->battery_needs_change ?? false) ? 'checked' : '' }}>
                <label class="form-check-label">{{ __('locale.visite_prev.battery_needs_change') }}</label>
            </div>
        </div>
    </div>
</div>
