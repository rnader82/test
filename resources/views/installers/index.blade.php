@extends('layouts.app')

@section('title', __('locale.lbl_installers_title'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.lbl_installers_title') }}</h1>
        <span class="header-count">({{ method_exists($installers, 'total') ? $installers->total() : count($installers) }})</span>
    </div>
@endsection

@section('navbar-search')
    <form method="GET" action="{{ route('installers.index') }}" class="w-100">
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
    <a href="{{ route('installers.create') }}" class="btn btn-primary" title="{{ __('locale.lbl_create_installer') ?? 'Ajouter' }}">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.lbl_create_installer') ?? 'Nouvel installateur' }}
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
                                <th class="ps-4">{{ __('locale.name') }}</th>
                                <th>{{ __('locale.lbl_email') }}</th>
                                <th>{{ __('locale.lbl_phone') }}</th>
                                <th>{{ __('locale.lbl_city') }}</th>
                                <th class="text-end pe-4" style="width: 120px;">{{ __('locale.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($installers as $installer)
                                <tr style="cursor: pointer;" onclick="window.location='{{ route('installers.show', $installer) }}'">
                                    <td class="ps-4">
                                        <a href="{{ route('installers.show', $installer) }}" class="fw-semibold text-dark text-decoration-none">
                                            {{ $installer->name }}
                                        </a>
                                    </td>
                                    <td>
                                        @if($installer->email)
                                            <a href="mailto:{{ $installer->email }}" class="text-muted small text-decoration-none" onclick="event.stopPropagation();">
                                                <i class="bi bi-envelope me-1"></i>{{ $installer->email }}
                                            </a>
                                        @else
                                            <span class="text-muted small">&mdash;</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($installer->phone)
                                            <span class="text-muted small"><i class="bi bi-telephone me-1"></i>{{ $installer->phone }}</span>
                                        @else
                                            <span class="text-muted small">&mdash;</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($installer->city)
                                            <span class="badge bg-light text-dark border">{{ $installer->city }}</span>
                                        @else
                                            <span class="text-muted small">&mdash;</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4" onclick="event.stopPropagation();">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" id="installerActionMenu{{ $installer->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border" aria-labelledby="installerActionMenu{{ $installer->id }}">
                                                <li>
                                                    <a href="{{ route('installers.show', $installer) }}" class="dropdown-item">
                                                        <i class="bi bi-eye me-2 text-info"></i>{{ __('locale.btn_show') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('installers.edit', $installer) }}" class="dropdown-item">
                                                        <i class="bi bi-pencil-square me-2 text-primary"></i>{{ __('locale.edit') }}
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('installers.destroy', $installer) }}" method="POST">
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
                                        <i class="bi bi-inbox fs-2 d-block mb-2 text-muted"></i>
                                        {{ __('locale.lbl_no_records_found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if(method_exists($installers, 'hasPages') && $installers->hasPages())
                <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
                    <span class="text-muted small">
                        {{ __('locale.results_sur', ['first' => $installers->firstItem() ?? 0, 'last' => $installers->lastItem() ?? 0, 'total' => $installers->total()]) }}
                    </span>
                    <div>
                        {{ $installers->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
