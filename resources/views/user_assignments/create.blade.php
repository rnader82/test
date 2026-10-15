@extends('layouts.app')
@section('portal-module', 'user_assignments')
@section('portal-page', 'create')
@section('portal-kind', 'form')
@section('portal-family', 'technical')
@section('portal-icon', 'screwdriver-wrench')


@section('title', __('locale.lbl_create_user_assignment'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('user_assignments.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.lbl_create_user_assignment') }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('user_assignments.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-1 fs-12">{{ __('locale.btn_cancel') ?? 'Annuler' }}</a>
        <button type="submit" form="userAssignmentCreateForm" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-check-lg me-1"></i>{{ __('locale.btn_create') ?? 'Enregistrer' }}
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
                            <i class="bi bi-person-check me-2 text-primary"></i>{{ __('locale.lbl_create_user_assignment') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('user_assignments.store') }}" method="POST" id="userAssignmentCreateForm">
                            @csrf

                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="user_id" class="form-label fw-semibold">{{ __('locale.lbl_user') }} <span class="text-danger">*</span></label>
                                    <select name="user_id" id="user_id" class="form-select" required autofocus>
                                        <option value="">{{ __('locale.lbl_select') ?? 'Sélectionner un utilisateur' }}</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label for="client_id" class="form-label fw-semibold">{{ __('locale.lbl_clients') }}</label>
                                    <select name="client_id[]" id="client_id" class="form-select select2" multiple>
                                        <optgroup label="Groupes">
                                            @foreach ($groups as $group)
                                                <option value="{{ $group->id }}">{{ $group->raison_sociale }}</option>
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="Sites">
                                            @foreach ($sites as $site)
                                                <option value="{{ $site->id }}">{{ $site->raison_sociale }}</option>
                                            @endforeach
                                        </optgroup>
                                    </select>
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

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "{{ __('locale.lbl_select') }}",
                allowClear: true
            });
        });
    </script>
@endsection
