@extends('layouts.app')

@section('title', __('locale.btn_create_tech_intervention_status'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('tech_intervention_statuses.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.btn_create_tech_intervention_status') }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('tech_intervention_statuses.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-1 fs-12">{{ __('locale.cancel') ?? 'Annuler' }}</a>
        <button type="submit" form="techInterventionStatusCreateForm" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-check-lg me-1"></i>{{ __('locale.btn_create_status') ?? 'Enregistrer' }}
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
                            <i class="bi bi-info-circle me-2 text-primary"></i>{{ __('locale.btn_create_tech_intervention_status') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('tech_intervention_statuses.store') }}" method="POST" id="techInterventionStatusCreateForm">
                            @csrf
                            @include('tech_intervention_statuses.form', ['techInterventionStatus' => new \App\Models\TechInterventionStatus()])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection