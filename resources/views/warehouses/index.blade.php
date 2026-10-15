@extends('layouts.app')
@section('portal-module', 'warehouses')
@section('portal-page', 'index')
@section('portal-kind', 'list')
@section('portal-family', 'catalog')
@section('portal-icon', 'tags')


@section('title', __('locale.menu_warehouses')) 

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.menu_warehouses') }}</h1>
    </div>
@endsection

@section('navbar-search')
    <form method="GET" action="{{ route('warehouses.index') }}" class="w-100">
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
    <a href="{{ route('warehouses.create') }}" class="btn btn-primary" title="{{ __('locale.add_warehouse') ?? 'Ajouter un entrepôt' }}">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.add_warehouse') ?? 'Nouvel entrepôt' }}
    </a>
@endsection


@section('content')
<div class="card card-premium shadow-none">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-4 mt-2">
            <form action="{{ route('warehouses.index') }}" method="GET" class="d-flex">
                <div class="search-bar-mockup">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" placeholder="{{ __('locale.search') }}" value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn btn-primary ms-2">{{ __('locale.search') }}</button>
            </form>
        </div>
        
        <div class="table-responsive">
            <table class="module-table table table-premium">
                <thead>
                    <tr>
                        <th>{{ __('locale.code') ?? 'Code' }}</th>
                        <th>{{ __('locale.name') ?? 'Nom' }}</th>
                        <th>{{ __('locale.address') ?? 'Adresse' }}</th>
                        <th>{{ __('locale.status') ?? 'Statut' }}</th>
                        <th>{{ __('locale.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($warehouses as $warehouse)
                        <tr>
                            <td><span class="fw-bold">{{ $warehouse->code ?? 'N/A' }}</span></td>
                            <td>{{ $warehouse->name }}</td>
                            <td>{{ Str::limit($warehouse->address, 50) }}</td>
                            <td>
                                @if($warehouse->is_active)
                                    <span class="badge bg-success">{{ __('locale.active') ?? 'Actif' }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('locale.inactive') ?? 'Inactif' }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary" type="button" id="whActionMenu{{ $warehouse->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="whActionMenu{{ $warehouse->id }}">
                                        <li>
                                            <a href="{{ route('warehouses.edit', $warehouse->id) }}" class="dropdown-item">
                                                <i class="bi bi-pencil-square me-2"></i>{{ __('locale.edit') }}
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('warehouses.destroy', $warehouse->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('{{ __('locale.delete_confirmation') }}')">
                                                    <i class="bi bi-trash me-2"></i>{{ __('locale.delete') }}
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                {{ __('locale.no_warehouses_found') ?? 'Aucun entrepôt trouvé.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center mt-4"> 
            {{ $warehouses->withQueryString()->links('pagination::bootstrap-5') }} 
        </div>
    </div>
</div>
	@endsection

