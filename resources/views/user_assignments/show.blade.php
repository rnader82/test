@extends('layouts.app')

@section('title', $user->name)

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('user_assignments.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ $user->name }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        @if ($userAssignments->first())
            <a href="{{ route('user_assignments.edit', $userAssignments->first()->id) }}" class="btn btn-sm btn-primary px-3 py-1 fs-12">
                <i class="bi bi-pencil-square me-1"></i> {{ __('locale.btn_edit') }}
            </a>
        @endif
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card card-premium shadow-sm border-0">
                    <div class="card-header bg-light border-bottom py-3 d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center bg-primary-subtle text-primary fw-bold rounded-circle" 
                             style="width: 44px; height: 44px; font-size: 1rem; text-transform: uppercase;">
                            {{ substr($user->name, 0, 2) }}
                        </div>
                        <div>
                            <h5 class="card-title mb-0 fs-6 fw-bold text-dark">{{ $user->name }}</h5>
                            <small class="text-muted">{{ $user->email }}</small>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-dark mb-3">
                            <i class="bi bi-building me-2 text-primary"></i>{{ __('locale.lbl_clients') }} ({{ count($clients) }})
                        </h6>
                        <div class="d-flex flex-wrap gap-2">
                            @forelse($clients as $client)
                                <span class="badge bg-light text-dark border p-2 d-flex align-items-center gap-1 fs-13">
                                    <i class="bi bi-building text-secondary"></i> {{ $client->raison_sociale }}
                                </span>
                            @empty
                                <p class="text-muted fst-italic mb-0">{{ __('locale.msg_no_user_assignments') }}</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
