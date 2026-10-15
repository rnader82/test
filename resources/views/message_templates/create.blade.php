@extends('layouts.app')
@section('portal-module', 'message_templates')
@section('portal-page', 'create')
@section('portal-kind', 'form')
@section('portal-family', 'admin')
@section('portal-icon', 'gear')


@section('title', __('locale.add_message_template'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('message_templates.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.add_message_template') }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('message_templates.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-1 fs-12">{{ __('locale.cancel') ?? 'Annuler' }}</a>
        <button type="submit" form="messageTemplateCreateForm" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-check-lg me-1"></i>{{ __('locale.save') ?? 'Enregistrer' }}
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
                            <i class="bi bi-chat-left-text me-2 text-primary"></i>{{ __('locale.add_message_template') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('message_templates.store') }}" method="POST" id="messageTemplateCreateForm">
                            @csrf
                            @include('message_templates._form', ['template' => null])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection