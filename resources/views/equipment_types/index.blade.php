@extends('layouts.app')

@section('title', __('locale.equipment_types'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.equipment_types') }}</h1>
        <span class="header-count">({{ method_exists($equipment_types, 'total') ? $equipment_types->total() : count($equipment_types) }})</span>
    </div>
@endsection

@section('navbar-search')
    <form method="GET" action="{{ route('equipment_types.index') }}" class="w-100">
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
    <a href="{{ route('equipment_types.create') }}" class="btn btn-primary" title="{{ __('locale.add_equipment_type') ?? 'Ajouter un type' }}">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.add_equipment_type') ?? 'Nouveau type' }}
    </a>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card card-premium shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 100px;">{{ __('locale.id') }}</th>
                                <th>{{ __('locale.equipment_type') }}</th>
                                <th class="text-end pe-4" style="width: 120px;">{{ __('locale.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($equipment_types as $type)
                                <tr style="cursor: pointer;" onclick="window.location='{{ route('equipment_types.show', $type->id) }}'">
                                    <td class="ps-4">
                                        <span class="badge bg-light text-secondary border font-monospace">#{{ $type->id }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('equipment_types.show', $type->id) }}" class="fw-semibold text-dark text-decoration-none">
                                            {{ $type->name }}
                                        </a>
                                    </td>
                                    <td class="text-end pe-4" onclick="event.stopPropagation();">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" id="typeActionMenu{{ $type->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border" aria-labelledby="typeActionMenu{{ $type->id }}">
                                                <li>
                                                    <a href="{{ route('equipment_types.show', $type->id) }}" class="dropdown-item">
                                                        <i class="bi bi-eye me-2 text-info"></i>{{ __('locale.btn_show') ?? 'Voir' }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('equipment_types.edit', $type->id) }}" class="dropdown-item">
                                                        <i class="bi bi-pencil-square me-2 text-primary"></i>{{ __('locale.edit') }}
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('equipment_types.destroy', $type->id) }}" method="POST">
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
                                    <td colspan="3" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2 text-muted"></i>
                                        {{ __('locale.no_data_available') ?? 'Aucun type d\'équipement trouvé' }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if(method_exists($equipment_types, 'hasPages') && $equipment_types->hasPages())
                <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
                    <span class="text-muted small">
                        {{ __('locale.results_sur', ['first' => $equipment_types->firstItem() ?? 0, 'last' => $equipment_types->lastItem() ?? 0, 'total' => $equipment_types->total()]) }}
                    </span>
                    <div>
                        {{ $equipment_types->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
