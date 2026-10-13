<?php
/**
 * Shared category form fields partial.
 *
 * @var \App\Models\Category $category Category instance providing default values.
 */
?>
<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label fw-semibold">{{ __('locale.name') }} <span class="text-danger">*</span></label>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $category->name ?? '') }}" required autofocus>
    </div>

    @php
        $selectedType = old('type', $category->type ?? 'Informations');
        $selectedEquipmentType = old('equipment_type_id', $category->equipment_type_id ?? '');
    @endphp
    <div class="col-md-6">
        <label for="type" class="form-label fw-semibold">{{ __('locale.lbl_type') }} <span class="text-danger">*</span></label>
        <select name="type" id="type" class="form-select" required>
            <option value="Informations" {{ $selectedType === 'Informations' ? 'selected' : '' }}>{{ __('locale.informations') }}</option>
            <option value="Panne" {{ $selectedType === 'Panne' ? 'selected' : '' }}>{{ __('locale.panne') }}</option>
            <option value="Modification" {{ $selectedType === 'Modification' ? 'selected' : '' }}>{{ __('locale.modification') }}</option>
            <option value="Demande de Devis" {{ $selectedType === 'Demande de Devis' ? 'selected' : '' }}>{{ __('locale.demande_de_devis') }}</option>
        </select>
    </div>

    <div class="col-md-6">
        <label for="equipment_type_id" class="form-label fw-semibold">{{ __('locale.lbl_equipment_type') }}</label>
        <select name="equipment_type_id" id="equipment_type_id" class="form-select">
            <option value="">{{ __('locale.select_equipment_type') }}</option>
            @foreach ($equipmentTypes as $type)
                <option value="{{ $type->id }}" {{ (string) $selectedEquipmentType === (string) $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-12">
        <label for="description" class="form-label fw-semibold">{{ __('locale.description') }}</label>
        <textarea name="description" id="description" class="form-control" rows="2">{{ old('description', $category->description ?? '') }}</textarea>
    </div>

    @foreach (config('locales.available') as $locale)
        <div class="col-12"><hr class="my-2 text-muted"></div>
        <div class="col-md-6">
            <label for="translations_{{ $locale }}_name" class="form-label fw-semibold">
                {{ __('locale.name') }} ({{ strtoupper($locale) }})
            </label>
            <input type="text" class="form-control" id="translations_{{ $locale }}_name" name="translations[{{ $locale }}][name]"
                value="{{ old('translations.' . $locale . '.name', data_get($category->translations, $locale . '.name')) }}">
        </div>
        <div class="col-md-6">
            <label for="translations_{{ $locale }}_description" class="form-label fw-semibold">
                {{ __('locale.description') }} ({{ strtoupper($locale) }})
            </label>
            <textarea class="form-control" id="translations_{{ $locale }}_description" rows="2"
             name="translations[{{ $locale }}][description]">{{ old('translations.' . $locale . '.description', data_get($category->translations, $locale . '.description')) }}</textarea>
        </div>
    @endforeach
</div>
