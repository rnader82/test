@extends('layouts.app')
@section('portal-module', 'process_stages')
@section('portal-page', 'show')
@section('portal-kind', 'record')
@section('portal-family', 'admin')
@section('portal-icon', 'gear')


@section('title', $processStage->translated_name)

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('process_stages.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ $processStage->translated_name }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('process_stages.edit', $processStage) }}" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-pencil-square me-1"></i>{{ __('locale.edit') ?? 'Modifier' }}
        </a>
        <form action="{{ route('process_stages.destroy', $processStage) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('locale.delete_confirmation') }}')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger px-3 py-1 fs-12">
                <i class="bi bi-trash me-1"></i>{{ __('locale.delete') }}
            </button>
        </form>
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="module-record-card card card-premium shadow-sm border-0">
                    <div class="card-header bg-light border-bottom py-3">
                        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
                            <i class="bi bi-info-circle me-2 text-primary"></i>{{ __('locale.lbl_process_stage_details') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-4 text-muted">{{ __('locale.name') }}</dt>
                            <dd class="col-sm-8 fw-semibold text-dark">{{ $processStage->translated_name }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.model_slug') }}</dt>
                            <dd class="col-sm-8"><span class="badge bg-light text-secondary border font-monospace">{{ $processStage->model_slug }}</span></dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.description') }}</dt>
                            <dd class="col-sm-8">{{ $processStage->translated_description ?: '—' }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.lbl_created_at') }}</dt>
                            <dd class="col-sm-8 text-muted small">{{ format_datetime($processStage->created_at) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection