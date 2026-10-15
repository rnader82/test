@extends('layouts.app')
@section('portal-module', 'tenants')
@section('portal-page', 'create')
@section('portal-kind', 'form')
@section('portal-family', 'admin')
@section('portal-icon', 'gear')


@section('title', __('locale.Create Tenant'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('tenants.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 px-1">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.Create Tenant') }}</h1>
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="module-form-card card card-premium">
            <div class="card-body p-4">
                <form action="{{ route('tenants.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-gear-fill me-2"></i>{{ __('locale.lbl_main_info') }}</h5>
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="domain">{{ __('locale.Domain') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-globe text-muted"></i></span>
                                <input type="text" name="domain" id="domain" class="form-control @error('domain') is-invalid @enderror" value="{{ old('domain') }}" placeholder="client.novalyo360.com" required>
                            </div>
                            @error('domain') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="app_name">{{ __('locale.App Name') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-window text-muted"></i></span>
                                <input type="text" name="app_name" id="app_name" class="form-control @error('app_name') is-invalid @enderror" value="{{ old('app_name') }}" placeholder="Client Portal" required>
                            </div>
                            @error('app_name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold" for="logo">{{ __('locale.Logo') }}</label>
                            <input type="file" name="logo" id="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                            <div class="form-text text-muted small mt-1">{{ __('locale.upload_logo_format_hint') }}</div>
                            @error('logo') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <h5 class="fw-bold mb-3 text-primary pt-3 border-top"><i class="bi bi-palette-fill me-2"></i>{{ __('locale.Branding Settings') }}</h5>
                    <div class="row g-4 mb-4">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold" for="primary_color">{{ __('locale.Primary Color') }}</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" name="settings[primary_color]" id="primary_color" class="form-control form-control-color border @error('settings.primary_color') is-invalid @enderror" style="width: 48px; height: 38px; padding: 4px;" value="{{ old('settings.primary_color', '#3490dc') }}">
                                <input type="text" id="primary_color_text" class="form-control form-control-sm text-uppercase font-monospace" readonly value="#3490DC">
                            </div>
                            @error('settings.primary_color') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label fw-semibold" for="primary_hover_color">{{ __('locale.Primary Hover Color') }}</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" name="settings[primary_hover_color]" id="primary_hover_color" class="form-control form-control-color border @error('settings.primary_hover_color') is-invalid @enderror" style="width: 48px; height: 38px; padding: 4px;" value="{{ old('settings.primary_hover_color', '#2779bd') }}">
                                <input type="text" id="primary_hover_color_text" class="form-control form-control-sm text-uppercase font-monospace" readonly value="#2779BD">
                            </div>
                            @error('settings.primary_hover_color') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label fw-semibold" for="secondary_color">{{ __('locale.Secondary Color') }}</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" name="settings[secondary_color]" id="secondary_color" class="form-control form-control-color border @error('settings.secondary_color') is-invalid @enderror" style="width: 48px; height: 38px; padding: 4px;" value="{{ old('settings.secondary_color', '#ffed4a') }}">
                                <input type="text" id="secondary_color_text" class="form-control form-control-sm text-uppercase font-monospace" readonly value="#FFED4A">
                            </div>
                            @error('settings.secondary_color') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label fw-semibold" for="contact_email">{{ __('locale.Contact Email') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" name="settings[contact_email]" id="contact_email" class="form-control @error('settings.contact_email') is-invalid @enderror" value="{{ old('settings.contact_email') }}" placeholder="contact@domain.com">
                            </div>
                            @error('settings.contact_email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                        <a href="{{ route('tenants.index') }}" class="btn btn-outline-secondary px-4">{{ __('locale.cancel') }}</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i>{{ __('locale.Create Tenant') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const primaryColor = document.getElementById('primary_color');
        const primaryColorText = document.getElementById('primary_color_text');
        const primaryHoverColor = document.getElementById('primary_hover_color');
        const primaryHoverColorText = document.getElementById('primary_hover_color_text');
        const secondaryColor = document.getElementById('secondary_color');
        const secondaryColorText = document.getElementById('secondary_color_text');

        // Sync initial text values
        primaryColorText.value = primaryColor.value.toUpperCase();
        primaryHoverColorText.value = primaryHoverColor.value.toUpperCase();
        secondaryColorText.value = secondaryColor.value.toUpperCase();

        // Add listeners to sync on change
        primaryColor.addEventListener('input', function() {
            primaryColorText.value = this.value.toUpperCase();
        });
        primaryHoverColor.addEventListener('input', function() {
            primaryHoverColorText.value = this.value.toUpperCase();
        });
        secondaryColor.addEventListener('input', function() {
            secondaryColorText.value = this.value.toUpperCase();
        });
    });
</script>
@endpush
