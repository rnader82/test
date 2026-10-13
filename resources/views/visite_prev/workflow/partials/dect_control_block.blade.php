<div class="card mb-3 p-3">
    @if($control && $control->id)
        <input type="hidden" name="controls[{{ $index }}][id]" value="{{ $control->id }}">
    @endif

    <div class="row mb-2">
        <div class="col-md-4">
            <label for="numero-{{ $index }}">{{ __('visite_prev.numero') }}</label>
            <input type="number"
                   name="controls[{{ $index }}][numero]"
                   id="numero-{{ $index }}"
                   class="form-control"
                   value="{{ old("controls.$index.numero", $control->numero ?? '') }}">
        </div>

        <div class="col-md-4">
            <label for="name_of_dect-{{ $index }}">{{ __('visite_prev.name_of_dect') }}</label>
            <input type="text"
                   name="controls[{{ $index }}][name_of_dect]"
                   id="name_of_dect-{{ $index }}"
                   class="form-control"
                   value="{{ old("controls.$index.name_of_dect", $control->name_of_dect ?? '') }}">
        </div>

        <div class="col-md-4">
            <label for="reference-{{ $index }}">{{ __('visite_prev.reference') }}</label>
            <input type="text"
                   name="controls[{{ $index }}][reference]"
                   id="reference-{{ $index }}"
                   class="form-control"
                   value="{{ old("controls.$index.reference", $control->reference ?? '') }}">
        </div>
    </div>

    <div class="row mb-2">
        @foreach(['cover_hs', 'screen_hs', 'battery_hs', 'mic_hs', 'speaker_hs'] as $check)
            <div class="col-md-2">
                <div class="form-check">
                    <input class="form-check-input"
                           type="checkbox"
                           id="{{ $check }}-{{ $index }}"
                           name="controls[{{ $index }}][{{ $check }}]"
                           {{ old("controls.$index.$check", $control->{$check} ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="{{ $check }}-{{ $index }}">
                        {{ __('visite_prev.' . $check) }}
                    </label>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row mb-2">
        <div class="col-md-4">
            <div class="form-check">
                <input class="form-check-input"
                       type="checkbox"
                       id="verified-{{ $index }}"
                       name="controls[{{ $index }}][verified]"
                       {{ old("controls.$index.verified", $control->verified ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="verified-{{ $index }}">
                    {{ __('visite_prev.verified') }}
                </label>
            </div>
        </div>
        <div class="col-md-8">
            <label for="reason-{{ $index }}">{{ __('visite_prev.reason') }}</label>
            <input type="text"
                   name="controls[{{ $index }}][reason]"
                   id="reason-{{ $index }}"
                   class="form-control"
                   value="{{ old("controls.$index.reason", $control->reason ?? '') }}">
        </div>
    </div>

    <div class="mb-2">
        <label for="other-{{ $index }}">{{ __('visite_prev.other') }}</label>
        <input type="text"
               name="controls[{{ $index }}][other]"
               id="other-{{ $index }}"
               class="form-control"
               value="{{ old("controls.$index.other", $control->other ?? '') }}">
    </div>

    <div class="mb-2">
        <label for="todo-{{ $index }}">{{ __('visite_prev.todo') }}</label>
        <input type="text"
               name="controls[{{ $index }}][todo]"
               id="todo-{{ $index }}"
               class="form-control"
               value="{{ old("controls.$index.todo", $control->todo ?? '') }}">
    </div>
</div>
