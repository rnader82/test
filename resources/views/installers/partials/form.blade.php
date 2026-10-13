<div class="row g-3">
    <div class="col-md-12">
        <label for="name" class="form-label fw-semibold">{{ __('locale.lbl_company_name') }} <span class="text-danger">*</span></label>
        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $installer->name ?? '') }}" required autofocus>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="address1" class="form-label fw-semibold">{{ __('locale.lbl_address_1') }} <span class="text-danger">*</span></label>
        <input type="text" name="address1" id="address1" class="form-control @error('address1') is-invalid @enderror" value="{{ old('address1', $installer->address1 ?? '') }}" required>
        @error('address1')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="address2" class="form-label fw-semibold">{{ __('locale.lbl_address_2') }} <span class="text-muted small">({{ __('locale.lbl_optional') }})</span></label>
        <input type="text" name="address2" id="address2" class="form-control @error('address2') is-invalid @enderror" value="{{ old('address2', $installer->address2 ?? '') }}">
        @error('address2')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="postal_code" class="form-label fw-semibold">{{ __('locale.lbl_postal_code') }} <span class="text-danger">*</span></label>
        <input type="text" name="postal_code" id="postal_code" class="form-control @error('postal_code') is-invalid @enderror" value="{{ old('postal_code', $installer->postal_code ?? '') }}" required>
        @error('postal_code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="city" class="form-label fw-semibold">{{ __('locale.lbl_city') }} <span class="text-danger">*</span></label>
        <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city', $installer->city ?? '') }}" required>
        @error('city')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="state" class="form-label fw-semibold">{{ __('locale.lbl_state') }} <span class="text-muted small">({{ __('locale.lbl_optional') }})</span></label>
        <input type="text" name="state" id="state" class="form-control @error('state') is-invalid @enderror" value="{{ old('state', $installer->state ?? '') }}">
        @error('state')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="country" class="form-label fw-semibold">{{ __('locale.lbl_country') }} <span class="text-danger">*</span></label>
        <input type="text" name="country" id="country" class="form-control @error('country') is-invalid @enderror" value="{{ old('country', $installer->country ?? '') }}" required>
        @error('country')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="email" class="form-label fw-semibold">{{ __('locale.lbl_email') }} <span class="text-danger">*</span></label>
        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $installer->email ?? '') }}" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="phone" class="form-label fw-semibold">{{ __('locale.lbl_phone') }} <span class="text-danger">*</span></label>
        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $installer->phone ?? '') }}" required>
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="fax" class="form-label fw-semibold">{{ __('locale.lbl_fax') }} <span class="text-muted small">({{ __('locale.lbl_optional') }})</span></label>
        <input type="text" name="fax" id="fax" class="form-control @error('fax') is-invalid @enderror" value="{{ old('fax', $installer->fax ?? '') }}">
        @error('fax')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
