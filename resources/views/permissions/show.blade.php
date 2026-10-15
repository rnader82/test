@extends('layouts.app')
@section('portal-module', 'permissions')
@section('portal-page', 'show')
@section('portal-kind', 'record')
@section('portal-family', 'admin')
@section('portal-icon', 'gear')


@section('title', $permission->translated_name)

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ $permission->translated_name }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-pencil-square me-1"></i>{{ __('locale.edit') ?? 'Modifier' }}
        </a>
        <form action="{{ route('permissions.destroy', $permission) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('locale.delete_confirmation') }}')">
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
                            <i class="bi bi-check2-circle me-2 text-primary"></i>{{ __('locale.lbl_permission_details') ?? 'Détails de la permission' }}
                        </h5>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace">
                            {{ $permission->slug }}
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-3 text-muted">{{ __('locale.name') }}</dt>
                            <dd class="col-sm-9 fw-semibold text-dark">{{ $permission->translated_name }}</dd>

                            <dt class="col-sm-3 text-muted">{{ __('locale.slug') ?? 'Code' }}</dt>
                            <dd class="col-sm-9 font-monospace text-dark">{{ $permission->slug }}</dd>

                            <dt class="col-sm-3 text-muted">{{ __('locale.lbl_parent_permission') ?? 'Permission Parente' }}</dt>
                            <dd class="col-sm-9">
                                @if($permission->parent)
                                    <a href="{{ route('permissions.show', $permission->parent) }}" class="badge bg-light text-dark border text-decoration-none">
                                        <i class="bi bi-arrow-return-right me-1 text-primary"></i>{{ $permission->parent->translated_name }}
                                    </a>
                                @else
                                    <span class="text-muted fst-italic">{{ __('locale.lbl_none') ?? 'Racine (Aucun parent)' }}</span>
                                @endif
                            </dd>

                            @if($permission->children && $permission->children->count() > 0)
                                <dt class="col-sm-3 text-muted">{{ __('locale.lbl_sub_permissions') ?? 'Sous-permissions' }} ({{ $permission->children->count() }})</dt>
                                <dd class="col-sm-9">
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($permission->children as $child)
                                            <a href="{{ route('permissions.show', $child) }}" class="badge bg-light text-dark border text-decoration-none">
                                                {{ $child->translated_name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </dd>
                            @endif

                            <dt class="col-sm-3 text-muted">{{ __('locale.lbl_roles') }} ({{ $permission->roles->count() }})</dt>
                            <dd class="col-sm-9">
                                <div class="d-flex flex-wrap gap-1">
                                    @forelse($permission->roles as $role)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                            {{ $role->translated_name ?? $role->name }}
                                        </span>
                                    @empty
                                        <span class="text-muted fst-italic">{{ __('locale.lbl_no_roles') ?? 'Aucun rôle' }}</span>
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
