@extends('layouts.app')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1 class="mb-0 fs-4 fw-bold">{{ __('locale.menu_client_document_types') }}</h1>
        <span class="header-count">({{ $types->total() }})</span>
    </div>
@endsection

@section('navbar-search')
    <form method="GET" action="{{ route('client-document-types.index') }}" class="w-100">
        <div class="search-bar-mockup">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('locale.search_placeholder') }}...">
            @foreach(request()->except(['search', 'page']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </div>
    </form>
@endsection

@section('page-actions')
    @if (Auth::user()->is_admin == 1 || Auth::user()->isNovalyoAdmin() || Auth::user()->hasPermission('create-client-document-types'))
        <a href="{{ route('client-document-types.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_add') }}
        </a>
    @endif
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm py-2 px-3 mb-3 small">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger border-0 shadow-sm py-2 px-3 mb-3 small">{{ session('error') }}</div>
        @endif

        {{-- Filter bar --}}
        <div class="d-flex align-items-center justify-content-between gap-2 mb-3 flex-wrap">
            <form method="GET" action="{{ route('client-document-types.index') }}" class="d-flex align-items-center gap-2">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                <select name="status" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                    <option value="">{{ __('locale.all_status') }}</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('locale.active') }}</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('locale.inactive') }}</option>
                </select>
            </form>
            <span class="text-muted small">
                {{ $types->firstItem() ?? 0 }}&ndash;{{ $types->lastItem() ?? 0 }} sur {{ $types->total() }}
            </span>
        </div>

        <div class="card shadow-sm border-0 rounded-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 25%;">{{ __('locale.lbl_name') }}</th>
                            <th style="width: 40%;">{{ __('locale.lbl_description') }}</th>
                            <th class="text-center" style="width: 15%;">{{ __('locale.lbl_documents_count') }}</th>
                            <th class="text-center" style="width: 10%;">{{ __('locale.lbl_status') }}</th>
                            <th class="text-end" style="width: 10%;">{{ __('locale.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($types as $type)
                            <tr style="cursor: pointer;" onclick="window.location='{{ route('client-document-types.show', $type) }}'">
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('client-document-types.show', $type) }}" class="text-decoration-none">
                                            <span class="badge bg-{{ $type->color ?? 'secondary' }} rounded-pill px-2 py-1">
                                                <i class="bi bi-tag-fill me-1 small"></i>{{ $type->name }}
                                            </span>
                                        </a>
                                    </div>
                                </td>
                                <td class="text-muted small">{{ $type->description ?: '—' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border">{{ $type->documents_count ?? 0 }}</span>
                                </td>
                                <td class="text-center">
                                    @if($type->is_active)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">{{ __('locale.active') }}</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">{{ __('locale.inactive') }}</span>
                                    @endif
                                </td>
                                <td class="text-end" onclick="event.stopPropagation();">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary border-0" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                            <li>
                                                <a href="{{ route('client-document-types.show', $type) }}" class="dropdown-item small">
                                                    <i class="bi bi-eye me-2 text-info"></i>{{ __('locale.btn_show') ?? 'Voir' }}
                                                </a>
                                            </li>
                                            @if (Auth::user()->is_admin == 1 || Auth::user()->isNovalyoAdmin() || Auth::user()->hasPermission('edit-client-document-types'))
                                                <li>
                                                    <a href="{{ route('client-document-types.edit', $type) }}" class="dropdown-item small">
                                                        <i class="bi bi-pencil-square me-2 text-warning"></i>{{ __('locale.btn_edit') }}
                                                    </a>
                                                </li>
                                            @endif
                                            @if (Auth::user()->is_admin == 1 || Auth::user()->isNovalyoAdmin() || Auth::user()->hasPermission('delete-client-document-types'))
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('client-document-types.destroy', $type) }}" method="POST"
                                                          onsubmit="return confirm('{{ __('locale.confirm_delete') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item small text-danger">
                                                            <i class="bi bi-trash me-2"></i>{{ __('locale.btn_delete') }}
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="bi bi-folder-x fs-2 d-block mb-1"></i>
                                    {{ __('locale.no_data_available') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($types->hasPages())
                <div class="card-footer bg-white border-0 py-2">
                    {{ $types->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
