<form action="{{ $action }}" method="POST" class="needs-validation" novalidate>
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    <div class="mb-3">
        <label for="name" class="form-label">{{ __('locale.name') }}</label>
        <input 
            type="text" 
            class="form-control @error('name') is-invalid @enderror"
            name="name" 
            id="name" 
            value="{{ old('name', $status->name) }}" 
            required
        >
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="color" class="form-label">{{ __('locale.color') }}</label>
        <input 
            type="color" 
            class="form-control-color @error('color') is-invalid @enderror"
            name="color" 
            id="color" 
            value="{{ old('color', $status->color ?? '#000000') }}"
            title="{{ __('locale.choose_color') }}"
        >
        @error('color')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="text_color" class="form-label">{{ __('locale.text_color') }}</label>
        <input
            type="color"
            class="form-control-color @error('text_color') is-invalid @enderror"
            name="text_color"
            id="text_color"
            value="{{ old('text_color', $status->text_color ?? '#000000') }}"
            title="{{ __('locale.choose_color') }}"
        >
        @error('text_color')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    <div class="mb-3">
        <label for="module" class="form-label">{{ __('locale.module') }}</label>
        <select 
            class="form-select @error('module') is-invalid @enderror" 
            name="module" 
            id="module"
        >
            @php
                $modules = ['issue', 'task', 'ticket', 'repair', 'vp_visit'];
            @endphp
            @foreach($modules as $mod)
                <option value="{{ $mod }}" {{ (old('module', $status->module) === $mod) ? 'selected' : '' }}>
                    {{ ucfirst($mod) }}
                </option>
            @endforeach
        </select>
        @error('module')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">{{ __('locale.status_type') ?? 'Status Type' }}</label>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="status_flag" id="flag_none" value="none" {{ (!$status->is_pending && !$status->is_closed) ? 'checked' : '' }}>
            <label class="form-check-label" for="flag_none">
                {{ __('locale.normal') ?? 'Normal' }}
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="status_flag" id="flag_pending" value="is_pending" {{ $status->is_pending ? 'checked' : '' }}>
            <label class="form-check-label" for="flag_pending">
                {{ __('locale.is_pending') ?? 'Is Pending' }}
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="status_flag" id="flag_closed" value="is_closed" {{ $status->is_closed ? 'checked' : '' }}>
            <label class="form-check-label" for="flag_closed">
                {{ __('locale.is_closed') ?? 'Is Closed' }}
            </label>
        </div>
    </div>

    @foreach(config('locales.available') as $locale)
        @php $trans = $status->translations[$locale] ?? []; @endphp
        <div class="mb-3">
            <label for="translations_{{ $locale }}_name" class="form-label">{{ __('locale.name') }} ({{ strtoupper($locale) }})</label>
            <input type="text" class="form-control" id="translations_{{ $locale }}_name"
                name="translations[{{ $locale }}][name]" value="{{ old('translations.' . $locale . '.name', $trans['name'] ?? '') }}">
        </div>
    @endforeach


    <button type="submit" class="btn btn-primary">
        {{ __('locale.save') }}
    </button>
</form>
