<div class="row g-3">
    <div class="col-md-8">
        <label for="name" class="form-label fw-semibold">{{ __('locale.name') }} <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
            value="{{ old('name', $techInterventionSeverity->name ?? '') }}" placeholder="Ex: Bloquante, Majeure, Mineure..." required autofocus>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="color" class="form-label fw-semibold">{{ __('locale.lbl_color') }}</label>
        <div class="d-flex align-items-center gap-2">
            <input type="color" name="color" id="color" class="form-control form-control-color"
                value="{{ old('color', $techInterventionSeverity->color ?? '#3b82f6') }}" style="width: 50px;">
            <span class="text-muted small">{{ __('locale.display_color') }}</span>
        </div>
        @error('color')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="description" class="form-label fw-semibold">{{ __('locale.description') }}</label>
        <textarea class="form-control @error('description') is-invalid @enderror" id="description"
            name="description" rows="2" placeholder="Description facultative...">{{ old('description', $techInterventionSeverity->description ?? '') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    @foreach(config('locales.available') as $locale)
        @php $trans = $techInterventionSeverity->translations[$locale] ?? []; @endphp
        <div class="col-12"><hr class="my-2 text-muted"></div>
        <div class="col-md-6">
            <label for="translations_{{ $locale }}_name" class="form-label fw-semibold">{{ __('locale.name') }} ({{ strtoupper($locale) }})</label>
            <input type="text" class="form-control" id="translations_{{ $locale }}_name"
                name="translations[{{ $locale }}][name]" value="{{ old('translations.' . $locale . '.name', $trans['name'] ?? '') }}">
        </div>
        <div class="col-md-6">
            <label for="translations_{{ $locale }}_description" class="form-label fw-semibold">{{ __('locale.description') }} ({{ strtoupper($locale) }})</label>
            <textarea class="form-control" id="translations_{{ $locale }}_description" rows="2"
                name="translations[{{ $locale }}][description]">{{ old('translations.' . $locale . '.description', $trans['description'] ?? '') }}</textarea>
        </div>
    @endforeach
</div>