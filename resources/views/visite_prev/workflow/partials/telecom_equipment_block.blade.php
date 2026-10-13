<div class="card mb-3 p-3">
    @if(!is_null($equipment->id ?? null))
        <input type="hidden" name="equipments[{{ $index }}][id]" value="{{ $equipment->id }}">
    @endif

    <div class="row mb-2">
        <div class="col-md-4">
            <label>{{ __('locale.visite_prev.type') }}</label>
            <input type="text" name="equipments[{{ $index }}][type]" class="form-control"
                   value="{{ old("equipments.$index.type", $equipment->type ?? '') }}">
        </div>
        <div class="col-md-4">
            <label>{{ __('locale.visite_prev.make') }}</label>
            <input type="text" name="equipments[{{ $index }}][make]" class="form-control"
                   value="{{ old("equipments.$index.make", $equipment->make ?? '') }}">
        </div>
        <div class="col-md-4">
            <label>{{ __('locale.visite_prev.qty') }}</label>
            <input type="number" name="equipments[{{ $index }}][qty]" class="form-control"
                   value="{{ old("equipments.$index.qty", $equipment->qty ?? 1) }}">
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-4">
            <label>{{ __('locale.visite_prev.model') }}</label>
            <input type="text" name="equipments[{{ $index }}][model]" class="form-control"
                   value="{{ old("equipments.$index.model", $equipment->model ?? '') }}">
        </div>
        <div class="col-md-4">
            <label>{{ __('locale.visite_prev.place') }}</label>
            <input type="text" name="equipments[{{ $index }}][place]" class="form-control"
                   value="{{ old("equipments.$index.place", $equipment->place ?? '') }}">
        </div>
        <div class="col-md-4">
            <label>{{ __('locale.visite_prev.password') }}</label>
            <input type="text" name="equipments[{{ $index }}][password]" class="form-control"
                   value="{{ old("equipments.$index.password", $equipment->password ?? '') }}">
        </div>
    </div>

    <div class="mb-2">
        <label>{{ __('locale.visite_prev.images') }}</label>
        <input type="file" name="equipments[{{ $index }}][images][]" multiple class="form-control">

        @if (!old("equipments.$index") && is_array($equipment->images ?? null))
            <div class="mt-2">
                @foreach ($equipment->images as $img)
                    <img src="{{ str_starts_with($img, 'http') ? $img : asset('storage/' . $img) }}" width="70" class="me-2 mb-2" />
                @endforeach
            </div>
        @endif
    </div>
</div>

