@php
	/** @var \App\Models\ContractClauseTemplate|null $contractClauseTemplate */
@endphp
<div class="row g-3">
    <div class="col-12">
        <label class="form-label fw-semibold" for="title">{{ __('locale.title') ?? 'Titre' }} <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $contractClauseTemplate->title ?? '') }}" placeholder="Ex: Clause de résiliation, Confidentialité..." required autofocus>
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label class="form-label fw-semibold" for="body">{{ __('locale.content') ?? 'Contenu de la clause' }} <span class="text-danger">*</span></label>
        <textarea class="form-control @error('body') is-invalid @enderror" id="body" name="body" rows="6" placeholder="Rédigez le texte de la clause..." required>{{ old('body', $contractClauseTemplate->body ?? '') }}</textarea>
        @error('body')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $contractClauseTemplate->is_active ?? true))>
            <label class="form-check-label fw-semibold" for="is_active">{{ __('locale.active') ?? 'Actif' }}</label>
        </div>
    </div>
</div>
