@extends('layouts.app')

@section('title', __('locale.visite_prev.edit_template'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('vp-checklist-templates.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.visite_prev.edit_template') }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('vp-checklist-templates.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-1 fs-12">{{ __('locale.cancel') ?? 'Annuler' }}</a>
        <button type="submit" form="checklistTemplateEditForm" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-check-lg me-1"></i>{{ __('locale.visite_prev.update') ?? 'Mettre à jour' }}
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
                            <i class="bi bi-pencil-square me-2 text-primary"></i>{{ __('locale.visite_prev.edit_template') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('vp-checklist-templates.update', $template->id) }}" method="POST" id="checklistTemplateEditForm">
                            @csrf
                            @method('PUT')
                            @include('visite_prev.checklist_templates._form', ['template' => $template])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
