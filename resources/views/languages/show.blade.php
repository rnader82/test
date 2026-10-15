@extends('layouts.app')
@section('portal-module', 'languages')
@section('portal-page', 'show')
@section('portal-kind', 'record')
@section('portal-family', 'admin')
@section('portal-icon', 'gear')


@section('title', $language->name)

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('languages.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ $language->name }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('languages.edit', $language) }}" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-pencil-square me-1"></i>{{ __('locale.edit') ?? 'Modifier' }}
        </a>
        @if(!$language->is_default)
            <form action="{{ route('languages.destroy', $language) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('locale.delete_confirmation') }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger px-3 py-1 fs-12">
                    <i class="bi bi-trash me-1"></i>{{ __('locale.delete') }}
                </button>
            </form>
        @endif
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="module-record-card card card-premium shadow-sm border-0 mb-4">
                    <div class="card-header bg-light border-bottom py-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar avatar-sm bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 14px;">
                                {{ strtoupper(substr($language->code ?? $language->name, 0, 2)) }}
                            </div>
                            <div>
                                <h5 class="card-title mb-0 fs-6 fw-bold text-dark">{{ $language->name }}</h5>
                                <small class="text-muted">{{ $language->locale ?: 'Pas de locale spécifique' }}</small>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            @if($language->is_default)
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle"><i class="bi bi-star-fill me-1"></i>{{ __('locale.default_state') }}</span>
                            @endif
                            @if($language->is_active)
                                <span class="badge bg-success-subtle text-success border border-success-subtle">{{ __('locale.active_state') }}</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">{{ __('locale.inactive_state') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-4 text-muted">{{ __('locale.name') }}</dt>
                            <dd class="col-sm-8 fw-semibold text-dark">{{ $language->name }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.iso_code') }}</dt>
                            <dd class="col-sm-8 font-monospace">{{ $language->code ?: '—' }}</dd>

                            <dt class="col-sm-4 text-muted">Locale (Odoo / I18N)</dt>
                            <dd class="col-sm-8 font-monospace">{{ $language->locale ?: '—' }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.odoo_id') }}</dt>
                            <dd class="col-sm-8">
                                @if($language->odoo_id)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle font-monospace">{{ $language->odoo_id }}</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">{{ __('locale.unlinked') }}</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.status') }}</dt>
                            <dd class="col-sm-8">
                                @if($language->is_active)
                                    <span class="text-success fw-medium"><i class="bi bi-check-circle me-1"></i>Active</span>
                                @else
                                    <span class="text-danger fw-medium"><i class="bi bi-x-circle me-1"></i>Inactive</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4 text-muted">Langue par défaut</dt>
                            <dd class="col-sm-8">{{ $language->is_default ? 'Oui' : 'Non' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
