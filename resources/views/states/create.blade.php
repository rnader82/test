@extends('layouts.app')
@section('portal-module', 'states')
@section('portal-page', 'create')
@section('portal-kind', 'form')
@section('portal-family', 'admin')
@section('portal-icon', 'gear')


@section('title', __('locale.btn_create') . ' ' . __('locale.menu_states'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('states.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.btn_create') }} {{ __('locale.menu_states') }}</h1>
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="module-form-card card card-premium shadow-sm border-0">
                    <div class="card-header bg-light border-bottom py-3">
                        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
                            <i class="bi bi-activity me-2 text-primary"></i>{{ __('locale.btn_create') }} {{ __('locale.menu_states') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @include('states.form', ['state' => new \App\Models\State, 'statuses' => $statuses, 'action' => route('states.store'), 'method' => 'POST'])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection