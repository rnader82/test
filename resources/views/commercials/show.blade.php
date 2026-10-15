@extends('layouts.app')
@section('portal-module', 'commercials')
@section('portal-page', 'show')
@section('portal-kind', 'record')
@section('portal-family', 'crm')
@section('portal-icon', 'arrow-trend-up')


@section('title', $commercial->prenom . ' ' . $commercial->libelle)

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('commercials.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ $commercial->prenom }} {{ $commercial->libelle }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('commercials.edit', $commercial) }}" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-pencil-square me-1"></i> {{ __('locale.edit') }}
        </a>
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="module-record-card card card-premium shadow-sm border-0">
                    <div class="card-header bg-light border-bottom py-3">
                        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
                            <i class="bi bi-person-badge me-2 text-primary"></i>{{ __('locale.lbl_commercial_details') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-3 text-muted">{{ __('locale.external_id') }}</dt>
                            <dd class="col-sm-3"><span class="badge bg-light text-secondary border font-monospace">{{ $commercial->external_id }}</span></dd>

                            <dt class="col-sm-3 text-muted">{{ __('locale.libelle') }}</dt>
                            <dd class="col-sm-3 fw-semibold text-dark">{{ $commercial->libelle }}</dd>

                            <dt class="col-sm-3 text-muted">{{ __('locale.prenom') }}</dt>
                            <dd class="col-sm-3 text-dark">{{ $commercial->prenom }}</dd>

                            <dt class="col-sm-3 text-muted">{{ __('locale.lbl_email') }}</dt>
                            <dd class="col-sm-3">
                                @if($commercial->email)
                                    <a href="mailto:{{ $commercial->email }}" class="text-decoration-none">{{ $commercial->email }}</a>
                                @else
                                    &mdash;
                                @endif
                            </dd>

                            <dt class="col-sm-3 text-muted">{{ __('locale.etablissement') }}</dt>
                            <dd class="col-sm-3">{{ $commercial->etablissement ?: '—' }}</dd>

                            <dt class="col-sm-3 text-muted">{{ __('locale.type_commercial') }}</dt>
                            <dd class="col-sm-3">{{ $commercial->type_commercial ?: '—' }}</dd>

                            <dt class="col-sm-3 text-muted">{{ __('locale.zone_com') }}</dt>
                            <dd class="col-sm-3">{{ $commercial->zone_com ?: '—' }}</dd>

                            <dt class="col-sm-3 text-muted">{{ __('locale.type_com') }}</dt>
                            <dd class="col-sm-3">{{ $commercial->type_com ?: '—' }}</dd>

                            <dt class="col-sm-3 text-muted">{{ __('locale.nature_piece_g') }}</dt>
                            <dd class="col-sm-3">{{ $commercial->nature_piece_g ?: '—' }}</dd>

                            <dt class="col-sm-3 text-muted">{{ __('locale.prefixe_tiers') }}</dt>
                            <dd class="col-sm-3">{{ $commercial->prefixe_tiers ?: '—' }}</dd>

                            <dt class="col-sm-3 text-muted">{{ __('locale.hr_rang') }}</dt>
                            <dd class="col-sm-3">{{ $commercial->hr_rang ?: '—' }}</dd>

                            <dt class="col-sm-3 text-muted">{{ __('locale.salarie') }}</dt>
                            <dd class="col-sm-3">{{ $commercial->salarie ?: '—' }}</dd>

                            @if($commercial->bloc_note)
                                <dt class="col-sm-3 text-muted">{{ __('locale.bloc_note') }}</dt>
                                <dd class="col-sm-9"><div class="bg-light rounded p-3 text-dark small">{{ $commercial->bloc_note }}</div></dd>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
