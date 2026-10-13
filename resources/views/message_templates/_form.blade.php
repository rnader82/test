<div class="row g-3">
    <div class="col-12">
        <label for="subject" class="form-label fw-semibold">{{ __('locale.lbl_subject') }} <span class="text-danger">*</span></label>
        <input type="text" name="subject" id="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject', optional($template)->subject) }}" placeholder="Ex: Confirmation de rendez-vous, Facture disponible..." required autofocus>
        @error('subject')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="message" class="form-label fw-semibold">{{ __('locale.lbl_message') }} <span class="text-danger">*</span></label>
        <textarea name="message" id="message" class="form-control @error('message') is-invalid @enderror" rows="6" placeholder="Rédigez le texte du modèle..." required>{{ old('message', optional($template)->message) }}</textarea>
        @error('message')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>