@extends('layouts.app')
@section('portal-module', 'brands')
@section('portal-page', 'create')
@section('portal-kind', 'form')
@section('portal-family', 'catalog')
@section('portal-icon', 'tags')


@section('title', __('locale.add_brand'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('brands.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.add_brand') }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('brands.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-1 fs-12">{{ __('locale.cancel') }}</a>
        <button type="submit" form="brandCreateForm" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-check-lg me-1"></i>{{ __('locale.save') ?? 'Enregistrer' }}
        </button>
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="module-form-card card card-premium shadow-sm border-0">
                    <div class="card-header bg-light border-bottom py-3">
                        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
                            <i class="bi bi-award me-2 text-primary"></i>{{ __('locale.add_brand') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('brands.store') }}" method="POST" id="brandCreateForm">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">{{ __('locale.brand') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Ex: Apple, Dell, HP..." required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
