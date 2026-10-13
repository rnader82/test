@extends('layouts.app')

@section('title', __('locale.lbl_edit_process_stage'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('process_stages.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.lbl_edit_process_stage') }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('process_stages.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-1 fs-12">{{ __('locale.cancel') ?? 'Annuler' }}</a>
        <button type="submit" form="processStageEditForm" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-check-lg me-1"></i>{{ __('locale.btn_update') ?? 'Mettre à jour' }}
        </button>
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card card-premium shadow-sm border-0">
                    <div class="card-header bg-light border-bottom py-3">
                        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
                            <i class="bi bi-pencil-square me-2 text-primary"></i>{{ __('locale.lbl_edit_process_stage') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('process_stages.update', $processStage->id) }}" method="POST" id="processStageEditForm">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold">{{ __('locale.name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name', $processStage->name) }}" required autofocus>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="model_slug" class="form-label fw-semibold">{{ __('locale.model_slug') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="model_slug" class="form-control @error('model_slug') is-invalid @enderror" id="model_slug" value="{{ old('model_slug', $processStage->model_slug) }}" required>
                                    @error('model_slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="description" class="form-label fw-semibold">{{ __('locale.description') }}</label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" rows="2">{{ old('description', $processStage->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                @foreach(config('locales.available') as $locale)
                                    @php $trans = $processStage->translations[$locale] ?? []; @endphp
                                    <div class="col-12"><hr class="my-2 text-muted"></div>
                                    <div class="col-md-6">
                                        <label for="translations_{{ $locale }}_name" class="form-label fw-semibold">{{ __('locale.name') }} ({{ strtoupper($locale) }})</label>
                                        <input type="text" class="form-control" id="translations_{{ $locale }}_name" name="translations[{{ $locale }}][name]" value="{{ old('translations.' . $locale . '.name', $trans['name'] ?? '') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="translations_{{ $locale }}_description" class="form-label fw-semibold">{{ __('locale.description') }} ({{ strtoupper($locale) }})</label>
                                        <textarea class="form-control" id="translations_{{ $locale }}_description" rows="2" name="translations[{{ $locale }}][description]">{{ old('translations.' . $locale . '.description', $trans['description'] ?? '') }}</textarea>
                                    </div>
                                @endforeach
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection