@extends('layouts.app')

@section('title', __('Create contract template'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('contract_templates.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('Create contract template') }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('contract_templates.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-1 fs-12">{{ __('locale.cancel') ?? 'Annuler' }}</a>
        <button type="submit" form="contractTemplateForm" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-check-lg me-1"></i>{{ __('locale.save') ?? 'Enregistrer' }}
        </button>
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <form action="{{ route('contract_templates.store') }}" method="POST" id="contractTemplateForm">
            @csrf
            @include('contract_templates._form', ['contractTemplate' => null])
        </form>
    </div>
</div>
@endsection
