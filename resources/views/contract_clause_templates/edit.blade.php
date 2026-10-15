@extends('layouts.app')
@section('portal-module', 'contract_clause_templates')
@section('portal-page', 'edit')
@section('portal-kind', 'form')
@section('portal-family', 'quotes')
@section('portal-icon', 'file-lines')


@section('title', __('locale.edit_clause') ?? 'Modifier la clause type')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('contract_clause_templates.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.edit_clause') ?? 'Modifier la clause type' }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('contract_clause_templates.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-1 fs-12">{{ __('locale.cancel') ?? 'Annuler' }}</a>
        <button type="submit" form="contractClauseTemplateEditForm" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-check-lg me-1"></i>{{ __('locale.update') ?? 'Mettre à jour' }}
        </button>
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="module-form-card card card-premium shadow-sm border-0">
                    <div class="card-header bg-light border-bottom py-3">
                        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
                            <i class="bi bi-pencil-square me-2 text-primary"></i>{{ __('locale.edit_clause') ?? 'Modifier la clause type' }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('contract_clause_templates.update', $contractClauseTemplate) }}" method="POST" id="contractClauseTemplateEditForm">
                            @csrf
                            @method('PUT')
                            @include('contract_clause_templates._form', ['contractClauseTemplate' => $contractClauseTemplate])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
