<form action="{{ $action }}" method="POST" class="needs-validation" novalidate>
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    <div class="mb-3">
        <label for="name" class="form-label">{{ __('locale.name') }}</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $state->name) }}" required>
    </div>
    

    <div class="mb-3">
        <label for="internal_status_id" class="form-label">{{ __('locale.lbl_internal_status') }}</label>
        <select name="internal_status_id" id="internal_status_id" class="form-select select2" required>
            <option value="">{{ __('locale.lbl_select_status') }}</option>
            @foreach($statuses as $status)
                <option value="{{ $status->id }}" {{ old('internal_status_id', $state->internal_status_id) == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="external_status_id" class="form-label">{{ __('locale.lbl_external_status') }}</label>
        <select name="external_status_id" id="external_status_id" class="form-select select2" required>
            <option value="">{{ __('locale.lbl_select_status') }}</option>
            @foreach($statuses as $status)
                <option value="{{ $status->id }}" {{ old('external_status_id', $state->external_status_id) == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
            @endforeach
        </select>
    </div>
    @foreach(config('locales.available') as $locale)
        @php $trans = $state->translations[$locale] ?? []; @endphp
        <div class="mb-3">
            <label for="translations_{{ $locale }}_name" class="form-label">{{ __('locale.name') }} ({{ strtoupper($locale) }})</label>
            <input type="text" class="form-control" id="translations_{{ $locale }}_name"
                name="translations[{{ $locale }}][name]" value="{{ old('translations.' . $locale . '.name', $trans['name'] ?? '') }}">
        </div>
    @endforeach

    <button type="submit" class="btn btn-primary">{{ __('locale.save') }}</button>
</form>