@extends('layouts.app')

@section('title', $contractClauseTemplate->title)

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('contract_clause_templates.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ $contractClauseTemplate->title }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('contract_clause_templates.edit', $contractClauseTemplate) }}" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-pencil-square me-1"></i>{{ __('locale.edit') ?? 'Modifier' }}
        </a>
        <form action="{{ route('contract_clause_templates.destroy', $contractClauseTemplate) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('locale.delete_confirmation') }}')">
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
                <div class="card card-premium shadow-sm border-0 mb-4">
                    <div class="card-header bg-light border-bottom py-3 d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
                            <i class="bi bi-card-checklist me-2 text-primary"></i>{{ __('locale.lbl_clause_details') ?? 'Détails de la clause' }}
                        </h5>
                        @if($contractClauseTemplate->is_active)
                            <span class="badge bg-success-subtle text-success border border-success-subtle">{{ __('locale.active') ?? 'Actif' }}</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">{{ __('locale.inactive') ?? 'Inactif' }}</span>
                        @endif
                    </div>
                    <div class="card-body p-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-3 text-muted">{{ __('locale.title') ?? 'Titre' }}</dt>
                            <dd class="col-sm-9 fw-semibold text-dark">{{ $contractClauseTemplate->title }}</dd>

                            <dt class="col-sm-3 text-muted">{{ __('locale.content') ?? 'Contenu' }}</dt>
                            <dd class="col-sm-9 text-dark">
                                <div class="p-3 bg-light rounded border">
                                    {!! nl2br(e($contractClauseTemplate->body)) !!}
                                </div>
                            </dd>

                            <dt class="col-sm-3 text-muted mt-3">{{ __('locale.status') }}</dt>
                            <dd class="col-sm-9 mt-3">
                                @if($contractClauseTemplate->is_active)
                                    <span class="text-success fw-medium"><i class="bi bi-check-circle me-1"></i>Actif</span>
                                @else
                                    <span class="text-secondary fw-medium"><i class="bi bi-dash-circle me-1"></i>Inactif</span>
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
