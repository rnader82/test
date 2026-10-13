@extends('layouts.app')

@section('title', $odooFiscalPosition->name)

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('odoo_fiscal_positions.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ $odooFiscalPosition->name }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('odoo_fiscal_positions.edit', $odooFiscalPosition) }}" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-pencil-square me-1"></i>{{ __('locale.edit') ?? 'Modifier' }}
        </a>
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
                            <i class="bi bi-cash-coin me-2 text-primary"></i>{{ __('locale.lbl_fiscal_position_details') ?? 'Détails de la position fiscale' }}
                        </h5>
                        <span class="badge bg-light text-dark border font-monospace fw-bold">
                            Odoo ID #{{ $odooFiscalPosition->odoo_id }}
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-4 text-muted">{{ __('locale.name') }}</dt>
                            <dd class="col-sm-8 fw-semibold text-dark">{{ $odooFiscalPosition->name }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.odoo_id') }}</dt>
                            <dd class="col-sm-8 font-monospace">{{ $odooFiscalPosition->odoo_id }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.company_name') ?? 'Société' }}</dt>
                            <dd class="col-sm-8 text-dark">{{ $odooFiscalPosition->company_name ?: '—' }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.odoo_company_id') ?? 'Odoo Company ID' }}</dt>
                            <dd class="col-sm-8 font-monospace">{{ $odooFiscalPosition->odoo_company_id ?: '—' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
