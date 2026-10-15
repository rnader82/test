@extends('layouts.app')
@section('portal-module', 'companies')
@section('portal-page', 'edit')
@section('portal-kind', 'form')
@section('portal-family', 'admin')
@section('portal-icon', 'gear')


@section('title', __('locale.edit_company'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 px-1">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.edit_company') }}</h1>
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="module-form-card card card-premium">
            <div class="card-body p-4">
                <form action="{{ route('companies.update', $company->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-building me-2"></i>{{ __('locale.lbl_main_info') }}</h5>
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="code">{{ __('locale.lbl_company_code') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-hash text-muted"></i></span>
                                <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $company->code) }}" placeholder="NO" required maxlength="10">
                            </div>
                            <div class="form-text text-muted small mt-1">{{ __('Example: NO, NS, etc.') }}</div>
                            @error('code') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="name">{{ __('locale.lbl_company_name') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-card-text text-muted"></i></span>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $company->name) }}" placeholder="Novalyo" required>
                            </div>
                            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end pt-3 border-top">
                        <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary px-4">{{ __('locale.cancel') }}</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i>{{ __('locale.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
