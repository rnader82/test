@extends('layouts.app')
@section('portal-module', 'commercials')
@section('portal-page', 'edit')
@section('portal-kind', 'form')
@section('portal-family', 'crm')
@section('portal-icon', 'arrow-trend-up')


@section('title', __('locale.lbl_edit_commercial'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('commercials.show', $commercial) }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.lbl_edit_commercial') }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('commercials.show', $commercial) }}" class="btn btn-sm btn-outline-secondary px-3 py-1 fs-12">{{ __('locale.cancel') ?? 'Annuler' }}</a>
        <button type="submit" form="commercialEditForm" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-check-lg me-1"></i>{{ __('locale.btn_update') ?? 'Mettre à jour' }}
        </button>
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="module-form-card card card-premium shadow-sm border-0">
                    <div class="card-header bg-light border-bottom py-3">
                        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
                            <i class="bi bi-person-badge me-2 text-primary"></i>{{ $commercial->prenom }} {{ $commercial->libelle }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('commercials.update', $commercial) }}" id="commercialEditForm">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="external_id" class="form-label fw-semibold">{{ __('locale.external_id') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('external_id') is-invalid @enderror" id="external_id" name="external_id"
                                        value="{{ old('external_id', $commercial->external_id) }}" required autofocus>
                                    @error('external_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="libelle" class="form-label fw-semibold">{{ __('locale.libelle') }}</label>
                                    <input type="text" class="form-control" id="libelle" name="libelle"
                                        value="{{ old('libelle', $commercial->libelle) }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="prenom" class="form-label fw-semibold">{{ __('locale.prenom') }}</label>
                                    <input type="text" class="form-control" id="prenom" name="prenom"
                                        value="{{ old('prenom', $commercial->prenom) }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="email" class="form-label fw-semibold">{{ __('locale.lbl_email') }}</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email', $commercial->email) }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="etablissement" class="form-label fw-semibold">{{ __('locale.etablissement') }}</label>
                                    <input type="text" class="form-control" id="etablissement" name="etablissement"
                                        value="{{ old('etablissement', $commercial->etablissement) }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="type_commercial" class="form-label fw-semibold">{{ __('locale.type_commercial') }}</label>
                                    <input type="text" class="form-control" id="type_commercial" name="type_commercial"
                                        value="{{ old('type_commercial', $commercial->type_commercial) }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="salarie" class="form-label fw-semibold">{{ __('locale.salarie') }}</label>
                                    <input type="text" class="form-control" id="salarie" name="salarie"
                                        value="{{ old('salarie', $commercial->salarie) }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="commission" class="form-label fw-semibold">{{ __('locale.commission') }}</label>
                                    <input type="text" class="form-control" id="commission" name="commission"
                                        value="{{ old('commission', $commercial->commission) }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="util_associe" class="form-label fw-semibold">{{ __('locale.util_associe') }}</label>
                                    <input type="text" class="form-control" id="util_associe" name="util_associe"
                                        value="{{ old('util_associe', $commercial->util_associe) }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="zone_com" class="form-label fw-semibold">{{ __('locale.zone_com') }}</label>
                                    <input type="text" class="form-control" id="zone_com" name="zone_com"
                                        value="{{ old('zone_com', $commercial->zone_com) }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="type_com" class="form-label fw-semibold">{{ __('locale.type_com') }}</label>
                                    <input type="text" class="form-control" id="type_com" name="type_com"
                                        value="{{ old('type_com', $commercial->type_com) }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="nature_piece_g" class="form-label fw-semibold">{{ __('locale.nature_piece_g') }}</label>
                                    <input type="text" class="form-control" id="nature_piece_g" name="nature_piece_g"
                                        value="{{ old('nature_piece_g', $commercial->nature_piece_g) }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="prefixe_tiers" class="form-label fw-semibold">{{ __('locale.prefixe_tiers') }}</label>
                                    <input type="text" class="form-control" id="prefixe_tiers" name="prefixe_tiers"
                                        value="{{ old('prefixe_tiers', $commercial->prefixe_tiers) }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="hr_rang" class="form-label fw-semibold">{{ __('locale.hr_rang') }}</label>
                                    <input type="text" class="form-control" id="hr_rang" name="hr_rang"
                                        value="{{ old('hr_rang', $commercial->hr_rang) }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="date_suppression" class="form-label fw-semibold">{{ __('locale.date_suppression') }}</label>
                                    <input type="date" class="form-control" id="date_suppression" name="date_suppression"
                                        value="{{ old('date_suppression', $commercial->date_suppression ? \Illuminate\Support\Carbon::parse($commercial->date_suppression)->format('Y-m-d') : '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="comment_caiss" class="form-label fw-semibold">{{ __('locale.comment_caiss') }}</label>
                                    <textarea class="form-control" id="comment_caiss" name="comment_caiss" rows="3">{{ old('comment_caiss', $commercial->comment_caiss) }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="bloc_note" class="form-label fw-semibold">{{ __('locale.bloc_note') }}</label>
                                    <textarea class="form-control" id="bloc_note" name="bloc_note" rows="3">{{ old('bloc_note', $commercial->bloc_note) }}</textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
