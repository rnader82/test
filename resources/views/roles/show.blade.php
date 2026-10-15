@extends('layouts.app')
@section('portal-module', 'roles')
@section('portal-page', 'show')
@section('portal-kind', 'record')
@section('portal-family', 'admin')
@section('portal-icon', 'gear')


@section('title', $role->translated_name)

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ $role->translated_name }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-pencil-square me-1"></i>{{ __('locale.edit') ?? 'Modifier' }}
        </a>
        <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('locale.delete_confirmation') }}')">
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
            <div class="col-lg-8">
                <div class="module-record-card card card-premium shadow-sm border-0 mb-4">
                    <div class="card-header bg-light border-bottom py-3 d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
                            <i class="bi bi-shield-lock me-2 text-primary"></i>{{ __('locale.lbl_role_details') ?? 'Détails du rôle' }}
                        </h5>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace">
                            {{ $role->slug }}
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-3 text-muted">{{ __('locale.name') }}</dt>
                            <dd class="col-sm-9 fw-semibold text-dark">{{ $role->translated_name }}</dd>

                            <dt class="col-sm-3 text-muted">{{ __('locale.description') }}</dt>
                            <dd class="col-sm-9 text-dark">{{ $role->translated_description ?: '—' }}</dd>

                            <dt class="col-sm-3 text-muted">{{ __('locale.lbl_users') }} ({{ $role->users->count() }})</dt>
                            <dd class="col-sm-9">
                                <div class="d-flex flex-wrap gap-1">
                                    @forelse($role->users as $user)
                                        <span class="badge bg-light text-dark border">
                                            <i class="bi bi-person me-1"></i>{{ $user->name }}
                                        </span>
                                    @empty
                                        <span class="text-muted fst-italic">{{ __('locale.lbl_no_users') ?? 'Aucun utilisateur assigné' }}</span>
                                    @endforelse
                                </div>
                            </dd>

                            <dt class="col-sm-3 text-muted">{{ __('locale.menu_permissions') }} ({{ $role->permissions->count() }})</dt>
                            <dd class="col-sm-9">
                                <div class="d-flex flex-wrap gap-1">
                                    @forelse($role->permissions as $perm)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                            {{ $perm->translated_name ?? $perm->name }}
                                        </span>
                                    @empty
                                        <span class="text-muted fst-italic">{{ __('locale.lbl_no_permissions') ?? 'Aucune permission' }}</span>
                                    @endforelse
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
