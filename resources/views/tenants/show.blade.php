@extends('layouts.app')
@section('portal-module', 'tenants')
@section('portal-page', 'show')
@section('portal-kind', 'record')
@section('portal-family', 'admin')
@section('portal-icon', 'gear')


@section('title', $tenant->app_name ?: $tenant->domain)

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('tenants.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ $tenant->app_name }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <form action="{{ route('tenants.clear-cache', $tenant->id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-info px-3 py-1 fs-12">
                <i class="bi bi-arrow-clockwise me-1"></i>{{ __('locale.Clear Cache') }}
            </button>
        </form>
        <a href="{{ route('tenants.edit', $tenant->id) }}" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-pencil-square me-1"></i>{{ __('locale.Edit Tenant') ?? 'Modifier' }}
        </a>
        <form action="{{ route('tenants.destroy', $tenant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('locale.delete_confirmation') }}')">
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
                <div class="module-record-card card card-premium shadow-sm border-0 mb-4">
                    <div class="card-header bg-light border-bottom py-3 d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
                            <i class="bi bi-stack me-2 text-primary"></i>{{ __('locale.lbl_tenant_details') ?? 'Détails du tenant' }}
                        </h5>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace">
                            {{ $tenant->domain }}
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-4 text-muted">{{ __('locale.App Name') }}</dt>
                            <dd class="col-sm-8 fw-semibold text-dark">{{ $tenant->app_name }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.Domain') }}</dt>
                            <dd class="col-sm-8 font-monospace text-primary">{{ $tenant->domain }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.Logo') }}</dt>
                            <dd class="col-sm-8">
                                @if($tenant->logo_path)
                                    <div class="p-2 bg-light rounded border d-inline-block">
                                        <img src="{{ asset('storage/' . $tenant->logo_path) }}" alt="Logo" style="height: 40px; max-width: 150px; object-fit: contain;">
                                    </div>
                                @else
                                    <span class="text-muted fst-italic">{{ __('locale.lbl_none') }}</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.Contact Email') }}</dt>
                            <dd class="col-sm-8">
                                @if($tenant->settings['contact_email'] ?? null)
                                    <a href="mailto:{{ $tenant->settings['contact_email'] }}" class="text-decoration-none">
                                        {{ $tenant->settings['contact_email'] }}
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.Branding Settings') ?? 'Couleurs' }}</dt>
                            <dd class="col-sm-8">
                                <div class="d-flex flex-wrap gap-2">
                                    @if($tenant->settings['primary_color'] ?? null)
                                        <span class="badge px-3 py-1 text-white" style="background-color: {{ $tenant->settings['primary_color'] }};">
                                            Primaire: {{ $tenant->settings['primary_color'] }}
                                        </span>
                                    @endif
                                    @if($tenant->settings['primary_hover_color'] ?? null)
                                        <span class="badge px-3 py-1 text-white" style="background-color: {{ $tenant->settings['primary_hover_color'] }};">
                                            Survol: {{ $tenant->settings['primary_hover_color'] }}
                                        </span>
                                    @endif
                                    @if($tenant->settings['secondary_color'] ?? null)
                                        <span class="badge px-3 py-1 text-dark" style="background-color: {{ $tenant->settings['secondary_color'] }};">
                                            Secondaire: {{ $tenant->settings['secondary_color'] }}
                                        </span>
                                    @endif
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
