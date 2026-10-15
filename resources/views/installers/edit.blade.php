@extends('layouts.app')
@section('portal-module', 'installers')
@section('portal-page', 'edit')
@section('portal-kind', 'form')
@section('portal-family', 'technical')
@section('portal-icon', 'screwdriver-wrench')


@section('title', __('locale.lbl_edit_installer'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('installers.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.lbl_edit_installer') }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('installers.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-1 fs-12">{{ __('locale.cancel') ?? 'Annuler' }}</a>
        <button type="submit" form="installerEditForm" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-check-lg me-1"></i>{{ __('locale.btn_edit_installer') ?? 'Mettre à jour' }}
        </button>
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="module-form-card card card-premium shadow-sm border-0">
                    <div class="card-header bg-light border-bottom py-3">
                        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
                            <i class="bi bi-pencil-square me-2 text-primary"></i>{{ __('locale.lbl_edit_installer') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('installers.update', $installer) }}" method="POST" id="installerEditForm">
                            @csrf
                            @method('PUT')
                            @include('installers.partials.form', ['installer' => $installer])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
