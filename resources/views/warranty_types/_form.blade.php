@php
    $isEdit = isset($warrantyType);
@endphp

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-6">
                <label for="name" class="form-label fw-semibold small">{{ __('locale.name') }} <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $warrantyType->name ?? '') }}" required placeholder="Ex: Sous garantie constructeur">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label for="code" class="form-label fw-semibold small">{{ __('locale.technical_code') }} @if($isEdit)<span class="text-danger">*</span>@endif</label>
                <input type="text" name="code" id="code" class="form-control font-monospace @error('code') is-invalid @enderror" value="{{ old('code', $warrantyType->code ?? '') }}" placeholder="under_warranty / out_of_warranty / amen_contract" @if(!$isEdit) placeholder="Généré automatiquement si vide" @endif>
                <div class="form-text small">Identifiant technique pour les formulaires (ex: under_warranty, out_of_warranty, amen_contract).</div>
                @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label for="color" class="form-label fw-semibold small">{{ __('locale.badge_color') }}</label>
                <div class="input-group">
                    <input type="color" name="color" id="color" class="form-control form-control-color border @error('color') is-invalid @enderror" style="width: 48px; height: 38px; padding: 4px;" value="{{ old('color', $warrantyType->color ?? '#3b82f6') }}">
                    <input type="text" class="form-control font-monospace" value="{{ old('color', $warrantyType->color ?? '#3b82f6') }}" oninput="document.getElementById('color').value = this.value">
                </div>
                @error('color')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-9">
                <label for="description" class="form-label fw-semibold small">{{ __('locale.description') }}</label>
                <textarea name="description" id="description" rows="2" class="form-control @error('description') is-invalid @enderror" placeholder="Précisions et conditions d'application...">{{ old('description', $warrantyType->description ?? '') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <label for="order" class="form-label fw-semibold small">{{ __('locale.display_order') }}</label>
                <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', $warrantyType->order ?? 0) }}">
                @error('order')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 mt-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $warrantyType->is_active ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold small" for="is_active">{{ __('locale.active_available_in_sav') }}</label>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer bg-light d-flex justify-content-end gap-2 py-3">
        <a href="{{ route('warranty_types.index') }}" class="btn btn-outline-secondary">{{ __('locale.cancel') }}</a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg me-1"></i> {{ $isEdit ? __('locale.save') : __('locale.save') }}
        </button>
    </div>
</div>
