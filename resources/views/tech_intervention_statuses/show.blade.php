@extends('layouts.app')

@section('title', $techInterventionStatus->translated_name)

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('tech_intervention_statuses.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ $techInterventionStatus->translated_name }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('tech_intervention_statuses.edit', $techInterventionStatus) }}" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-pencil-square me-1"></i>{{ __('locale.edit') ?? 'Modifier' }}
        </a>
        <form action="{{ route('tech_intervention_statuses.destroy', $techInterventionStatus) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('locale.delete_confirmation') }}')">
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
            <div class="col-lg-7">
                <div class="card card-premium shadow-sm border-0 mb-4">
                    <div class="card-header bg-light border-bottom py-3 d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
                            <i class="bi bi-check-circle me-2 text-primary"></i>{{ __('locale.lbl_tech_status_details') ?? 'Détails du statut d\'intervention' }}
                        </h5>
                        @if($techInterventionStatus->color)
                            <span class="badge px-3 py-1 text-white" style="background-color: {{ $techInterventionStatus->color }};">
                                {{ $techInterventionStatus->color }}
                            </span>
                        @endif
                    </div>
                    <div class="card-body p-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-4 text-muted">{{ __('locale.name') }}</dt>
                            <dd class="col-sm-8 fw-semibold text-dark">{{ $techInterventionStatus->translated_name }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.description') }}</dt>
                            <dd class="col-sm-8 text-dark">{{ $techInterventionStatus->translated_description ?: '—' }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.color') }}</dt>
                            <dd class="col-sm-8">
                                @if($techInterventionStatus->color)
                                    <span class="badge px-3 py-1 text-white" style="background-color: {{ $techInterventionStatus->color }};">
                                        {{ $techInterventionStatus->color }}
                                    </span>
                                @else
                                    <span class="text-muted small">&mdash;</span>
                                @endif
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
