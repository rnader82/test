@extends('layouts.app')

@section('title', __('locale.lbl_edit_technical_intervention_type'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('tech_intervention_types.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.lbl_edit_technical_intervention_type') }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('tech_intervention_types.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-1 fs-12">{{ __('locale.cancel') ?? 'Annuler' }}</a>
        <button type="submit" form="techInterventionTypeEditForm" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-check-lg me-1"></i>{{ __('locale.btn_edit_type') ?? 'Mettre à jour' }}
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
                            <i class="bi bi-pencil-square me-2 text-primary"></i>{{ __('locale.lbl_edit_technical_intervention_type') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('tech_intervention_types.update', $type) }}" method="POST" id="techInterventionTypeEditForm">
                            @csrf
                            @method('PUT')
                            @include('tech_intervention_types.form', ['techInterventionType' => $type])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection