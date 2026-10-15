@extends('layouts.app')
@section('portal-module', 'accounting_families')
@section('portal-page', 'show')
@section('portal-kind', 'record')
@section('portal-family', 'catalog')
@section('portal-icon', 'tags')


@section('title', $accountingFamily->family_name)

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('accounting_families.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ $accountingFamily->family_name }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('accounting_families.edit', $accountingFamily) }}" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-pencil-square me-1"></i>{{ __('locale.edit') ?? 'Modifier' }}
        </a>
        <form action="{{ route('accounting_families.destroy', $accountingFamily) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('locale.delete_confirmation') }}')">
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
                            <i class="bi bi-calculator me-2 text-primary"></i>{{ __('locale.lbl_accounting_family_details') ?? 'Détails de la famille comptable' }}
                        </h5>
                        <span class="badge bg-light text-dark border font-monospace fw-bold">
                            {{ $accountingFamily->code_cegid }}
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-4 text-muted">{{ __('locale.family_name') ?? 'Nom de famille' }}</dt>
                            <dd class="col-sm-8 fw-semibold text-dark">{{ $accountingFamily->family_name }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.code_cegid') ?? 'Code Cegid' }}</dt>
                            <dd class="col-sm-8 font-monospace">{{ $accountingFamily->code_cegid }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.fiscal_position') ?? 'Position fiscale' }}</dt>
                            <dd class="col-sm-8">
                                <span class="badge bg-secondary-subtle text-secondary border">{{ $accountingFamily->fiscal_position }}</span>
                            </dd>

                            @if($accountingFamily->odooCategory)
                                <dt class="col-sm-4 text-muted">{{ __('locale.odoo_category') ?? 'Catégorie Odoo' }}</dt>
                                <dd class="col-sm-8">
                                    <a href="{{ route('odoo_categories.show', $accountingFamily->odooCategory) }}" class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none">
                                        {{ $accountingFamily->odooCategory->name }}
                                    </a>
                                </dd>
                            @endif

                            <dt class="col-sm-4 text-muted">{{ __('locale.account_number') ?? 'Compte vente' }}</dt>
                            <dd class="col-sm-8 font-monospace">{{ $accountingFamily->account_number ?: '—' }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.tax_name') ?? 'Taxe vente' }}</dt>
                            <dd class="col-sm-8">{{ $accountingFamily->tax_name ?: '—' }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.purchase_account_number') ?? 'Compte achat' }}</dt>
                            <dd class="col-sm-8 font-monospace">{{ $accountingFamily->purchase_account_number ?: '—' }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.supplier_tax_name') ?? 'Taxe achat' }}</dt>
                            <dd class="col-sm-8">{{ $accountingFamily->supplier_tax_name ?: '—' }}</dd>

                            @if($accountingFamily->legal_mention)
                                <dt class="col-sm-4 text-muted">{{ __('locale.legal_mention') ?? 'Mention légale' }}</dt>
                                <dd class="col-sm-8 text-dark">{{ $accountingFamily->legal_mention }}</dd>
                            @endif

                            @if($accountingFamily->comment)
                                <dt class="col-sm-4 text-muted">{{ __('locale.comment') ?? 'Commentaire' }}</dt>
                                <dd class="col-sm-8 text-dark">{{ $accountingFamily->comment }}</dd>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
