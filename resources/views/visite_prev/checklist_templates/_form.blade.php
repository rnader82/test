<div class="row g-3">
    <div class="col-md-4">
        <label for="order_number" class="form-label fw-semibold">{{ __('locale.visite_prev.order_number') }} <span class="text-danger">*</span></label>
        <input type="number" name="order_number" id="order_number" class="form-control @error('order_number') is-invalid @enderror"
               value="{{ old('order_number', optional($template)->order_number ?? 1) }}" required autofocus>
        @error('order_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-8">
        <label for="action" class="form-label fw-semibold">{{ __('locale.visite_prev.action') }} <span class="text-danger">*</span></label>
        <input type="text" name="action" id="action" class="form-control @error('action') is-invalid @enderror"
               value="{{ old('action', optional($template)->action) }}" placeholder="Ex: Vérification des branchements..." required>
        @error('action')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
