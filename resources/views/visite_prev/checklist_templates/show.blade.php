@extends('layouts.app')

@section('title', __('visite_prev.template_details') ?? 'Détails du modèle de checklist')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('vp-checklist-templates.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">Action #{{ $template->order_number }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('vp-checklist-templates.edit', $template) }}" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-pencil-square me-1"></i>{{ __('locale.edit') ?? 'Modifier' }}
        </a>
        <form action="{{ route('vp-checklist-templates.destroy', $template) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('visite_prev.confirm_delete') }}')">
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
                            <i class="bi bi-card-checklist me-2 text-primary"></i>{{ __('visite_prev.template_details') ?? 'Détails de l\'action de checklist' }}
                        </h5>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace">
                            Ordre: {{ $template->order_number }}
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-4 text-muted">{{ __('visite_prev.order_number') ?? 'Numéro d\'ordre' }}</dt>
                            <dd class="col-sm-8 fw-semibold text-dark font-monospace">{{ $template->order_number }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('visite_prev.action') ?? 'Action / Tâche' }}</dt>
                            <dd class="col-sm-8 text-dark">{{ $template->action }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
