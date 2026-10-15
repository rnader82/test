@extends('layouts.app')
@section('portal-module', 'states')
@section('portal-page', 'show')
@section('portal-kind', 'record')
@section('portal-family', 'admin')
@section('portal-icon', 'gear')


@section('title', $state->translated_name)

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('states.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ $state->translated_name }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <form action="{{ route('states.destroy', $state) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('locale.confirm_delete') }}')">
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
                            <i class="bi bi-activity me-2 text-primary"></i>{{ __('locale.lbl_state_details') ?? 'Détails de l\'état' }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-4 text-muted">{{ __('locale.name') }}</dt>
                            <dd class="col-sm-8 fw-semibold text-dark">{{ $state->translated_name }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.internal_status') }}</dt>
                            <dd class="col-sm-8">
                                @if($state->internalStatus)
                                    <span class="badge px-3 py-1 fw-semibold" style="background-color: {{ $state->internalStatus->color ?? '#64748b' }}; color: {{ $state->internalStatus->text_color ?? '#ffffff' }};">
                                        {{ $state->internalStatus->translated_name }}
                                    </span>
                                @else
                                    <span class="text-muted small">&mdash;</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.external_status') }}</dt>
                            <dd class="col-sm-8">
                                @if($state->externalStatus)
                                    <span class="badge px-3 py-1 fw-semibold" style="background-color: {{ $state->externalStatus->color ?? '#64748b' }}; color: {{ $state->externalStatus->text_color ?? '#ffffff' }};">
                                        {{ $state->externalStatus->translated_name }}
                                    </span>
                                @else
                                    <span class="text-muted small">&mdash;</span>
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
