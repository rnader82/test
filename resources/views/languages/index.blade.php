@extends('layouts.app')
@section('portal-module', 'languages')
@section('portal-page', 'index')
@section('portal-kind', 'list')
@section('portal-family', 'admin')
@section('portal-icon', 'gear')


@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.lbl_langue') ?? 'Langues' }}</h1>
        <span class="header-count">({{ $languages->total() }})</span>
    </div>
@endsection

@section('navbar-search')
    <form method="GET" action="{{ route('languages.index') }}" class="w-100">
        <div class="search-bar-mockup">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par nom, code ISO ou locale...">
            @foreach(request()->except(['search', 'page']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </div>
    </form>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <form method="POST" action="{{ route('languages.sync') }}" class="d-inline" onsubmit="return confirm('{{ __('locale.confirm_sync_languages_odoo') }}')">
            @csrf
            <button type="submit" class="btn btn-outline-primary">
                <i class="bi bi-cloud-arrow-down me-1"></i> {{ __('locale.btn_import_from_odoo') }}
            </button>
        </form>
        <a href="{{ route('languages.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_create_language') }}
        </a>
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger border-0 shadow-sm mb-4">{{ session('error') }}</div>
        @endif

        {{-- Filter bar --}}
        <form method="GET" action="{{ route('languages.index') }}" class="d-flex align-items-center gap-2 mb-3 flex-wrap">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            <select name="status" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                <option value="">Tous les statuts</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>✅ Actives</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>⛔ Inactives</option>
            </select>
            <select name="synced" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                <option value="">Toutes les langues</option>
                <option value="1" {{ request('synced') === '1' ? 'selected' : '' }}>✅ Avec Odoo ID</option>
                <option value="0" {{ request('synced') === '0' ? 'selected' : '' }}>⚠️ Sans Odoo ID</option>
            </select>
            <span class="text-muted small ms-auto">
                {{ $languages->firstItem() ?? 0 }}&ndash;{{ $languages->lastItem() ?? 0 }} sur {{ $languages->total() }}
            </span>
        </form>

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="module-table table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('locale.name') }}</th>
                            <th class="text-center">{{ __('locale.code_iso') }}</th>
                            <th class="text-center">Locale (Odoo / I18N)</th>
                            <th class="text-center">{{ __('locale.odoo_id') }}</th>
                            <th class="text-center">{{ __('locale.status') }}</th>
                            <th class="text-center">{{ __('locale.default_state') }}</th>
                            <th class="text-end">{{ __('locale.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($languages as $language)
                            <tr style="cursor: pointer;" onclick="window.location='{{ route('languages.show', $language) }}'">
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-xs bg-label-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 11px;">
                                            {{ strtoupper(substr($language->code ?? $language->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('languages.show', $language) }}" class="fw-semibold text-dark text-decoration-none">
                                                {{ $language->name }}
                                            </a>
                                            @if($language->is_default)
                                                <span class="badge bg-label-warning ms-1 small"><i class="bi bi-star-fill me-1"></i>Défaut</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($language->code)
                                        <span class="badge bg-label-secondary font-monospace">{{ $language->code }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($language->locale)
                                        <span class="badge bg-label-info font-monospace">{{ $language->locale }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($language->odoo_id)
                                        <span class="badge bg-label-success font-monospace">{{ $language->odoo_id }}</span>
                                    @else
                                        <span class="badge bg-label-secondary">Non lié</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($language->is_active)
                                        <span class="badge bg-label-success">Active</span>
                                    @else
                                        <span class="badge bg-label-danger">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($language->is_default)
                                        <span class="badge bg-label-warning"><i class="bi bi-check-circle-fill me-1"></i>Oui</span>
                                    @else
                                        <span class="text-muted">Non</span>
                                    @endif
                                </td>
                                <td class="text-end" onclick="event.stopPropagation();">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary border-0" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('languages.show', $language) }}" class="dropdown-item">
                                                    <i class="bi bi-eye me-2 text-info"></i>{{ __('locale.btn_show') ?? 'Voir' }}
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('languages.edit', $language) }}" class="dropdown-item">
                                                    <i class="bi bi-pencil-square me-2"></i>Modifier
                                                </a>
                                            </li>
                                            @if(!$language->is_default)
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('languages.destroy', $language) }}" method="POST"
                                                          onsubmit="return confirm('Supprimer cette langue ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="bi bi-trash me-2"></i>Supprimer
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
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-translate fs-2 d-block mb-2"></i>
                                    Aucune langue trouvée.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3 d-flex justify-content-center">
            {{ $languages->withQueryString()->links() }}
        </div>

    </div>
</div>
@endsection
