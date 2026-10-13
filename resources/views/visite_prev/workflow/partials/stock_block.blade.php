<div class="card mb-3 p-3">
    @if($stock && $stock->id)
        <input type="hidden" name="stocks[{{ $index }}][id]" value="{{ $stock->id }}">
    @endif

    <div class="row mb-2">
        <div class="col-md-3">
            <label>{{ __('visite_prev.category') }}</label>
            <select name="stocks[{{ $index }}][category]" class="form-select">
                @foreach(['telecoms', 'patient_call', 'room_equipment', 'medal', 'fugue'] as $cat)
                    <option value="{{ $cat }}"
                        {{ old("stocks.$index.category", $stock->category ?? '') === $cat ? 'selected' : '' }}>
                        {{ __('visite_prev.' . $cat) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label>{{ __('visite_prev.type') }}</label>
            <input type="text" name="stocks[{{ $index }}][type]" class="form-control"
                   value="{{ old("stocks.$index.type", $stock->type ?? '') }}">
        </div>

        <div class="col-md-2">
            <label>{{ __('visite_prev.make') }}</label>
            <input type="text" name="stocks[{{ $index }}][make]" class="form-control"
                   value="{{ old("stocks.$index.make", $stock->make ?? '') }}">
        </div>

        <div class="col-md-2">
            <label>{{ __('visite_prev.model') }}</label>
            <input type="text" name="stocks[{{ $index }}][model]" class="form-control"
                   value="{{ old("stocks.$index.model", $stock->model ?? '') }}">
        </div>

        <div class="col-md-2">
            <label>{{ __('visite_prev.qty') }}</label>
            <input type="number" name="stocks[{{ $index }}][qty]" class="form-control"
                   value="{{ old("stocks.$index.qty", $stock->qty ?? '') }}">
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-12">
            <label>{{ __('visite_prev.references') }}</label>
            <input type="text" name="stocks[{{ $index }}][references]" class="form-control"
                   value="{{ old("stocks.$index.references", $stock->references ?? '') }}">
        </div>
    </div>
</div>
