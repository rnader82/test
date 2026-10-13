@extends('layouts.app')

@section('title', __('locale.Tenants'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1 class="mb-0">{{ __('locale.Tenants') }}</h1>
        <span class="header-count">({{ method_exists($tenants, 'total') ? $tenants->total() : $tenants->count() }})</span>
    </div>
@endsection

@section('page-actions')
    <a href="{{ route('tenants.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.Create Tenant') }}
    </a>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card card-premium">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('locale.id') }}</th>
                                <th>{{ __('locale.Domain') }}</th>
                                <th>{{ __('locale.App Name') }}</th>
                                <th class="text-center">{{ __('locale.Logo') }}</th>
                                <th>{{ __('locale.Contact Email') }}</th>
                                <th class="text-center" style="width: 100px;">{{ __('locale.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tenants as $tenant)
                                <tr style="cursor: pointer;" onclick="window.location='{{ route('tenants.show', $tenant->id) }}'">
                                    <td><span class="text-muted fw-semibold">#{{ $tenant->id }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-globe me-2 text-primary"></i>
                                            <a href="{{ route('tenants.show', $tenant->id) }}" class="fw-bold text-dark text-decoration-none">
                                                {{ $tenant->domain }}
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-secondary">{{ $tenant->app_name }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($tenant->logo_path)
                                            <div class="d-inline-flex p-1 bg-light rounded border">
                                                <img src="{{ asset('storage/' . $tenant->logo_path) }}" alt="Logo" style="height: 28px; max-width: 100px; object-fit: contain;">
                                            </div>
                                        @else
                                            <span class="badge bg-secondary">{{ __('locale.lbl_none') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($tenant->settings['contact_email'] ?? null)
                                            <a href="mailto:{{ $tenant->settings['contact_email'] }}" class="text-muted text-decoration-none" onclick="event.stopPropagation();">
                                                <i class="bi bi-envelope me-1"></i>{{ $tenant->settings['contact_email'] }}
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center" onclick="event.stopPropagation();">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" id="tenantActionMenu{{ $tenant->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="tenantActionMenu{{ $tenant->id }}">
                                                <li>
                                                    <a href="{{ route('tenants.show', $tenant->id) }}" class="dropdown-item">
                                                        <i class="bi bi-eye me-2 text-info"></i>{{ __('locale.btn_show') ?? 'Voir' }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('tenants.edit', $tenant->id) }}" class="dropdown-item">
                                                        <i class="bi bi-pencil-square me-2 text-warning"></i>{{ __('locale.Edit Tenant') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('tenants.clear-cache', $tenant->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="bi bi-arrow-clockwise me-2 text-info"></i>{{ __('locale.Clear Cache') }}
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <form action="{{ route('tenants.destroy', $tenant->id) }}" method="POST">
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
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                        {{ __('locale.lbl_no_records_found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if(method_exists($tenants, 'links') && $tenants->hasPages())
                    <div class="card-footer d-flex justify-content-end border-top bg-transparent py-3">
                        {{ $tenants->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
