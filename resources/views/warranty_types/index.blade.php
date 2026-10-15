@extends('layouts.app')
@section('portal-module', 'warranty_types')
@section('portal-page', 'index')
@section('portal-kind', 'list')
@section('portal-family', 'admin')
@section('portal-icon', 'gear')


@section('title', 'Types de Garantie / Prise en charge SAV')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('repairs.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">
            Types de Garantie & Prise en charge SAV
        </h1>
    </div>
@endsection

@section('navbar-search')
    <form method="GET" action="{{ route('warranty_types.index') }}" class="w-100">
        <div class="search-bar-mockup">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('locale.search') }}">
            @foreach(request()->except(['search', 'page']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </div>
    </form>
@endsection

@section('page-actions')
    <a href="{{ route('warranty_types.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_create_warranty_type') }}
    </a>
@endsection

@section('content')
<div class="container-xxl py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <form action="{{ route('warranty_types.index') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Rechercher..." value="{{ request('search') }}" style="width: 250px;">
            <button type="submit" class="btn btn-outline-secondary btn-sm"><i class="bi bi-search"></i></button>
            @if(request('search'))
                <a href="{{ route('warranty_types.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-lg"></i></a>
            @endif
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="module-table table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">{{ __('locale.order') }}</th>
                        <th>{{ __('locale.name') }}</th>
                        <th style="width: 200px;">{{ __('locale.technical_code') }}</th>
                        <th>{{ __('locale.description') }}</th>
                        <th style="width: 100px;">{{ __('locale.status') }}</th>
                        <th class="text-end" style="width: 120px;">{{ __('locale.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($warrantyTypes as $type)
                        <tr style="cursor: pointer;" onclick="window.location='{{ route('warranty_types.show', $type) }}'">
                            <td class="text-muted small">{{ $type->order }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ route('warranty_types.show', $type) }}" class="text-decoration-none">
                                        <span class="badge" style="background-color: {{ $type->color ?? '#3b82f6' }}; color: #ffffff;">
                                            {{ $type->name }}
                                        </span>
                                    </a>
                                </div>
                            </td>
                            <td>
                                <span class="font-monospace small text-muted">{{ $type->code }}</span>
                            </td>
                            <td class="small text-muted">{{ $type->description ?: '—' }}</td>
                            <td>
                                @if($type->is_active)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Actif</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border">Inactif</span>
                                @endif
                            </td>
                            <td class="text-end" onclick="event.stopPropagation();">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('warranty_types.show', $type) }}" class="btn btn-sm btn-outline-info" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('warranty_types.edit', $type) }}" class="btn btn-sm btn-outline-warning" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('warranty_types.destroy', $type) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce type de garantie ?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Aucun type de garantie configuré.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($warrantyTypes->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $warrantyTypes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
