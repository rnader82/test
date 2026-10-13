@extends('layouts.app')

@section('title', $company->name)

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ $company->name }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-pencil-square me-1"></i>{{ __('locale.lbl_edit') ?? 'Modifier' }}
        </a>
        <form action="{{ route('companies.destroy', $company->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('locale.delete_confirmation') }}')">
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
                            <i class="bi bi-building me-2 text-primary"></i>{{ __('locale.lbl_company_details') ?? 'Détails de l\'entité' }}
                        </h5>
                        <span class="badge bg-primary px-3 py-2 fw-bold font-monospace">
                            {{ $company->code }}
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-4 text-muted">{{ __('locale.id') }}</dt>
                            <dd class="col-sm-8 fw-semibold text-dark">#{{ $company->id }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.lbl_company_code') }}</dt>
                            <dd class="col-sm-8 font-monospace fw-bold text-primary">{{ $company->code }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.lbl_company_name') }}</dt>
                            <dd class="col-sm-8 fw-semibold text-dark">{{ $company->name }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.created_at') ?? 'Créé le' }}</dt>
                            <dd class="col-sm-8 text-muted">{{ $company->created_at ? $company->created_at->format('d/m/Y H:i') : '—' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
