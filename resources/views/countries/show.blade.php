@extends('layouts.app')
@section('portal-module', 'countries')
@section('portal-page', 'show')
@section('portal-kind', 'record')
@section('portal-family', 'admin')
@section('portal-icon', 'gear')


@section('title', $country->name_fr)

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('countries.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ $country->name_fr }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('countries.edit', $country) }}" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-pencil-square me-1"></i>{{ __('locale.edit') ?? 'Modifier' }}
        </a>
        <form action="{{ route('countries.destroy', $country) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('locale.delete_confirmation') }}')">
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
                <div class="module-record-card card card-premium shadow-sm border-0 mb-4">
                    <div class="card-header bg-light border-bottom py-3 d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
                            <i class="bi bi-globe me-2 text-primary"></i>{{ __('locale.lbl_country_details') ?? 'Détails du pays' }}
                        </h5>
                        @if($country->iso_code)
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace">
                                {{ $country->iso_code }}
                            </span>
                        @endif
                    </div>
                    <div class="card-body p-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-4 text-muted">{{ __('locale.name_fr') }}</dt>
                            <dd class="col-sm-8 fw-semibold text-dark">{{ $country->name_fr }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.name_en') }}</dt>
                            <dd class="col-sm-8 text-dark">{{ $country->name_en }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.iso_code') }}</dt>
                            <dd class="col-sm-8">
                                @if($country->iso_code)
                                    <span class="badge bg-light text-secondary border font-monospace">{{ $country->iso_code }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.odoo_id') }}</dt>
                            <dd class="col-sm-8">
                                @if($country->odoo_id)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle font-monospace">{{ $country->odoo_id }}</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">{{ __('locale.not_synced') }}</span>
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
