@extends('layouts.app')

@section('title', __('locale.lbl_create_origine'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('origines.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.lbl_create_origine') }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('origines.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-1 fs-12">{{ __('locale.cancel') ?? 'Annuler' }}</a>
        <button type="submit" form="origineCreateForm" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-check-lg me-1"></i>{{ __('locale.lbl_create_origine') ?? 'Enregistrer' }}
        </button>
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card card-premium shadow-sm border-0">
                    <div class="card-header bg-light border-bottom py-3">
                        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
                            <i class="bi bi-compass me-2 text-primary"></i>{{ __('locale.lbl_create_origine') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('origines.store') }}" method="POST" id="origineCreateForm">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">{{ __('locale.name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Ex: Téléphone, Email, Portail..." required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @foreach(config('locales.available') as $locale)
                                <div class="mb-3">
                                    <label for="translations_{{ $locale }}_name" class="form-label fw-semibold">{{ __('locale.name') }} ({{ strtoupper($locale) }})</label>
                                    <input type="text" name="translations[{{ $locale }}][name]" id="translations_{{ $locale }}_name" class="form-control" value="{{ old('translations.' . $locale . '.name') }}">
                                </div>
                            @endforeach
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
