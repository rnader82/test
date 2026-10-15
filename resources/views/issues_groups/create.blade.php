@extends('layouts.app')
@section('portal-module', 'issues_groups')
@section('portal-page', 'create')
@section('portal-kind', 'form')
@section('portal-family', 'admin')
@section('portal-icon', 'gear')


@section('title', __('locale.lbl_create_issue_group'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('issues_groups.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.lbl_create_issue_group') }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('issues_groups.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-1 fs-12">{{ __('locale.btn_cancel') ?? 'Annuler' }}</a>
        <button type="submit" form="issueGroupCreateForm" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-check-lg me-1"></i>{{ __('locale.btn_create_group') ?? 'Enregistrer' }}
        </button>
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
                            <i class="bi bi-collection me-2 text-primary"></i>{{ __('locale.lbl_create_issue_group') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('issues_groups.store') }}" method="POST" id="issueGroupCreateForm">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">{{ __('locale.lbl_group_name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name') }}" placeholder="Ex: Matériel, Logiciel, Réseau..." required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label fw-semibold">{{ __('locale.description') }}</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" rows="3" placeholder="Description facultative...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
