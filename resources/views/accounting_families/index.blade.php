@extends('layouts.app')
@section('portal-module', 'accounting_families')
@section('portal-page', 'index')
@section('portal-kind', 'list')
@section('portal-family', 'catalog')
@section('portal-icon', 'tags')


@section('title', __('locale.lbl_accounting_family') ?? 'Familles Comptables')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.lbl_accounting_family') ?? 'Familles Comptables' }}</h1>
        <span class="header-count">({{ $accounting_families->total() }})</span>
    </div>
@endsection

@section('navbar-search')
    <form method="GET" action="{{ route('accounting_families.index') }}" class="w-100">
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
    <a href="{{ route('accounting_families.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.add_accounting_family') ?? 'Nouvelle famille' }}
    </a>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <form action="{{ route('accounting_families.index') }}" method="GET" class="d-flex w-50">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="{{ __('locale.search') ?? 'Rechercher...' }}" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
            <a href="{{ route('accounting_families.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> {{ __('locale.add_accounting_family') ?? 'Ajouter une famille comptable' }}
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card card-premium">
            <div class="table-responsive">
                <table class="module-table table table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>{{ __('locale.code_cegid') }}</th>
                            <th>{{ __('locale.family_name') }}</th>
                            <th>{{ __('locale.odoo_category') }}</th>
                            <th>{{ __('locale.fiscal_position') }}</th>
                            <th>{{ __('locale.account_number') }}</th>
                            <th>{{ __('locale.tax_name') }}</th>
                            <th>{{ __('locale.purchase_account_number') }}</th>
                            <th>{{ __('locale.supplier_tax_name') }}</th>
                            <th class="text-end">{{ __('locale.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($accounting_families as $family)
                            <tr style="cursor: pointer;" onclick="window.location='{{ route('accounting_families.show', $family->id) }}'">
                                <td class="fw-bold">{{ $family->code_cegid }}</td>
                                <td>
                                    <a href="{{ route('accounting_families.show', $family->id) }}" class="fw-semibold text-dark text-decoration-none">
                                        {{ $family->family_name }}
                                    </a>
                                </td>
                                <td>
                                     @if($family->odooCategory)
                                         <span class="badge bg-secondary">{{ $family->odooCategory->name }}</span>
                                     @else
                                         <span class="text-muted">-</span>
                                     @endif
                                </td>
                                <td>
                                     @if($family->fiscalPosition)
                                         <span class="badge bg-info text-dark">{{ $family->fiscalPosition->name }}</span>
                                     @else
                                         <span class="text-muted">{{ $family->fiscal_position }}</span>
                                     @endif
                                </td>
                                <td><code>{{ $family->account_number ?? '-' }}</code></td>
                                <td>
                                    @if($family->taxes_id && count((array)$family->taxes_id) > 0)
                                        @foreach(\App\Models\OdooTax::whereIn('odoo_id', (array)$family->taxes_id)->pluck('name') as $taxName)
                                            <span class="badge bg-primary me-1">{{ $taxName }}</span>
                                        @endforeach
                                    @else
                                        {{ $family->tax_name ?? '-' }}
                                    @endif
                                </td>
                                <td><code>{{ $family->purchase_account_number ?? '-' }}</code></td>
                                <td>
                                    @if($family->supplier_taxes_id && count((array)$family->supplier_taxes_id) > 0)
                                        @foreach(\App\Models\OdooTax::whereIn('odoo_id', (array)$family->supplier_taxes_id)->pluck('name') as $taxName)
                                            <span class="badge bg-success me-1">{{ $taxName }}</span>
                                        @endforeach
                                    @else
                                        {{ $family->supplier_tax_name ?? '-' }}
                                    @endif
                                </td>
                                <td class="text-end" onclick="event.stopPropagation();">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" type="button" id="familyActionMenu{{ $family->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="familyActionMenu{{ $family->id }}">
                                            <li>
                                                <a href="{{ route('accounting_families.show', $family->id) }}" class="dropdown-item">
                                                    <i class="bi bi-eye me-2 text-info"></i>{{ __('locale.btn_show') ?? 'Voir' }}
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('accounting_families.edit', $family->id) }}" class="dropdown-item">
                                                    <i class="bi bi-pencil-square me-2"></i>{{ __('locale.edit') }}
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('accounting_families.destroy', $family->id) }}" method="POST" class="d-inline">
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
                                <td colspan="9" class="text-center py-4 text-muted">
                                    Aucune famille comptable trouvée.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $accounting_families->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
