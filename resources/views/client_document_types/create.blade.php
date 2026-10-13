@extends('layouts.app')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('client-document-types.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <h1 class="mb-0 fs-4 fw-bold">{{ __('locale.lbl_create_client_document_type') }}</h1>
    </div>
@endsection

@section('page-actions')
    <button type="submit" form="doc-type-form" class="btn btn-primary btn-sm">
        <i class="bi bi-check-lg me-1"></i> {{ __('locale.btn_save') }}
    </button>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm py-2 px-3 mb-3 small">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-light border-0 py-3">
                        <h6 class="mb-0 fw-bold text-secondary">
                            <i class="bi bi-file-earmark-plus me-2 text-primary"></i>{{ __('locale.lbl_type_details') }}
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('client-document-types.store') }}" method="POST" id="doc-type-form">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold small">{{ __('locale.lbl_name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="{{ __('locale.placeholder_doc_type_name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label fw-semibold small">{{ __('locale.lbl_description') }}</label>
                                <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror" placeholder="{{ __('locale.placeholder_doc_type_desc') }}">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="color" class="form-label fw-semibold small">{{ __('locale.lbl_badge_color') }}</label>
                                    <select name="color" id="color" class="form-select">
                                        <option value="secondary" {{ old('color') == 'secondary' ? 'selected' : '' }}>{{ __('locale.color_gray') }}</option>
                                        <option value="primary" {{ old('color') == 'primary' ? 'selected' : '' }}>{{ __('locale.color_blue') }}</option>
                                        <option value="success" {{ old('color') == 'success' ? 'selected' : '' }}>{{ __('locale.color_green') }}</option>
                                        <option value="warning" {{ old('color') == 'warning' ? 'selected' : '' }}>{{ __('locale.color_yellow') }}</option>
                                        <option value="danger" {{ old('color') == 'danger' ? 'selected' : '' }}>{{ __('locale.color_red') }}</option>
                                        <option value="info" {{ old('color') == 'info' ? 'selected' : '' }}>{{ __('locale.color_cyan') }}</option>
                                        <option value="dark" {{ old('color') == 'dark' ? 'selected' : '' }}>{{ __('locale.color_dark') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold small" for="is_active">{{ __('locale.active') }}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                                <a href="{{ route('client-document-types.index') }}" class="btn btn-outline-secondary btn-sm">
                                    {{ __('locale.btn_cancel') }}
                                </a>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-check-lg me-1"></i> {{ __('locale.btn_save') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
