@extends('layouts.app')
@section('portal-module', 'remises_ascom')
@section('portal-page', 'index')
@section('portal-kind', 'list')
@section('portal-family', 'catalog')
@section('portal-icon', 'tags')


@section('title', __('locale.remises_ascom') ?? 'Remises Ascom') 

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.remises_ascom') ?? 'Remises Ascom' }}</h1>
        <span class="header-count">({{ method_exists($remisesAscom, 'total') ? $remisesAscom->total() : count($remisesAscom) }})</span>
    </div>
@endsection

@section('navbar-search')
    <form method="GET" action="{{ route('remises_ascom.index') }}" class="w-100">
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
    <a href="{{ route('remises_ascom.create') }}" class="btn btn-primary" title="{{ __('locale.add_remise_ascom') ?? 'Ajouter une remise Ascom' }}">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.add_remise_ascom') ?? 'Nouvelle remise' }}
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
                    <table class="module-table table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 100px;">{{ __('locale.id') ?? 'ID' }}</th>
                                <th>{{ __('locale.code_remise') ?? 'Code remise' }}</th>
                                <th>{{ __('locale.pourcentage') ?? 'Pourcentage' }}</th>
                                <th class="text-end pe-4" style="width: 120px;">{{ __('locale.actions') ?? 'Actions' }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($remisesAscom as $remise)
                                <tr style="cursor: pointer;" onclick="window.location='{{ route('remises_ascom.show', $remise->id) }}'">
                                    <td class="ps-4">
                                        <span class="badge bg-light text-secondary border font-monospace">#{{ $remise->id }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('remises_ascom.show', $remise->id) }}" class="text-decoration-none">
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1.5 fw-bold font-monospace">
                                                {{ $remise->code_remise }}
                                            </span>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">{{ number_format($remise->pourcentage, 2, ',', ' ') }} %</span>
                                    </td>
                                    <td class="text-end pe-4" onclick="event.stopPropagation();">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" id="remiseActionMenu{{ $remise->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border" aria-labelledby="remiseActionMenu{{ $remise->id }}">
                                                <li>
                                                    <a href="{{ route('remises_ascom.show', $remise->id) }}" class="dropdown-item">
                                                        <i class="bi bi-eye me-2 text-info"></i>{{ __('locale.btn_show') ?? 'Voir' }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('remises_ascom.edit', $remise->id) }}" class="dropdown-item">
                                                        <i class="bi bi-pencil-square me-2 text-primary"></i>{{ __('locale.edit') ?? 'Modifier' }}
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('remises_ascom.destroy', $remise->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('{{ __('locale.delete_confirmation') ?? 'Êtes-vous sûr ?' }}')">
                                                            <i class="bi bi-trash me-2"></i>{{ __('locale.delete') ?? 'Supprimer' }}
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2 text-muted"></i>
                                        {{ __('locale.lbl_no_records_found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if(method_exists($remisesAscom, 'hasPages') && $remisesAscom->hasPages())
                <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
                    <span class="text-muted small">
                        {{ __('locale.results_sur', ['first' => $remisesAscom->firstItem() ?? 0, 'last' => $remisesAscom->lastItem() ?? 0, 'total' => $remisesAscom->total()]) }}
                    </span>
                    <div>
                        {{ $remisesAscom->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
