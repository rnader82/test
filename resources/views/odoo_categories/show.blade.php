@extends('layouts.app')
@section('portal-module', 'odoo_categories')
@section('portal-page', 'show')
@section('portal-kind', 'record')
@section('portal-family', 'catalog')
@section('portal-icon', 'tags')


@section('title', $odooCategory->name . ' - ' . (__('locale.odoo_category_details') ?? 'Détails Catégorie Odoo'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('odoo_categories.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ $odooCategory->name }}</h1>
        <span class="badge bg-light text-dark border">Odoo ID: {{ $odooCategory->odoo_id }}</span>
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('odoo_categories.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>{{ __('locale.back_to_list') ?? 'Retour à la liste' }}
                </a>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('odoo_categories.edit', $odooCategory->id) }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-pencil-square me-1"></i>{{ __('locale.edit') ?? 'Modifier' }}
                </a>
                <form action="{{ route('odoo_categories.destroy', $odooCategory->id) }}" method="POST" onsubmit="return confirm('{{ __('locale.confirm_delete_odoo_category') ?? 'Êtes-vous sûr de vouloir supprimer cette catégorie Odoo ?' }}');" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-trash me-1"></i>{{ __('locale.delete') ?? 'Supprimer' }}
                    </button>
                </form>
            </div>
        </div>

        <div class="row g-4">
            {{-- Category Overview --}}
            <div class="col-md-6">
                <div class="module-record-card card card-premium h-100 p-4">
                    <h5 class="card-title text-primary mb-3">
                        <i class="bi bi-info-circle me-2"></i>{{ __('locale.general_information') }}
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless align-middle mb-0">
                            <tbody>
                                <tr>
                                    <th class="text-muted w-40">{{ __('locale.odoo_id') }} :</th>
                                    <td><span class="badge bg-light text-dark border fw-bold">{{ $odooCategory->odoo_id }}</span></td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('locale.name') }} :</th>
                                    <td class="fw-semibold">{{ $odooCategory->name }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('locale.parent_category') }} :</th>
                                    <td>
                                        @if($odooCategory->parent)
                                            <a href="{{ route('odoo_categories.show', $odooCategory->parent->id) }}" class="badge bg-secondary text-decoration-none">
                                                <i class="bi bi-folder2 me-1"></i>{{ $odooCategory->parent->name }}
                                            </a>
                                        @elseif($odooCategory->parent_odoo_id)
                                            <span class="badge bg-secondary">ID #{{ $odooCategory->parent_odoo_id }}</span>
                                        @else
                                            <span class="text-muted">{{ __('locale.no_parent_root') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('locale.parent_path') }} :</th>
                                    <td>
                                        @if($odooCategory->parent_path)
                                            <code>{{ $odooCategory->parent_path }}</code>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('locale.sort_order') }} :</th>
                                    <td><span class="badge bg-light text-dark border fw-bold">{{ $odooCategory->sort_order }}</span></td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('locale.customer_taxes') }} :</th>
                                    <td>
                                        @if($customerTaxes && $customerTaxes->count() > 0)
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($customerTaxes as $tax)
                                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                                        {{ $tax->name }} ({{ $tax->company_name ?? 'Global' }})
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">{{ __('locale.no_customer_taxes_configured') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('locale.supplier_taxes') }} :</th>
                                    <td>
                                        @if($supplierTaxes && $supplierTaxes->count() > 0)
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($supplierTaxes as $tax)
                                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">
                                                        {{ $tax->name }} ({{ $tax->company_name ?? 'Global' }})
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">{{ __('locale.no_supplier_taxes_configured') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('locale.lbl_created_at') }} :</th>
                                    <td>{{ $odooCategory->created_at ? $odooCategory->created_at->format('d/m/Y H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('locale.lbl_updated_at') }} :</th>
                                    <td>{{ $odooCategory->updated_at ? $odooCategory->updated_at->format('d/m/Y H:i') : '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Subcategories & Accounting Families --}}
            <div class="col-md-6">
                <div class="module-record-card card card-premium h-100 p-4">
                    <h5 class="card-title text-primary mb-3">
                        <i class="bi bi-diagram-3 me-2"></i>{{ __('locale.subcategories') }} ({{ $odooCategory->children->count() }})
                    </h5>
                    @if($odooCategory->children->count() > 0)
                        <div class="list-group list-group-flush mb-4">
                            @foreach($odooCategory->children as $child)
                                <a href="{{ route('odoo_categories.show', $child->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="bi bi-folder-fill text-warning me-2"></i>{{ $child->name }}
                                    </div>
                                    <span class="badge bg-light text-dark border">ID: {{ $child->odoo_id }}</span>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-4">{{ __('locale.no_subcategories_attached') }}</p>
                    @endif

                    <h5 class="card-title text-primary mb-3">
                        <i class="bi bi-calculator me-2"></i>{{ __('locale.accounting_families') }} ({{ $odooCategory->accountingFamilies->count() }})
                    </h5>
                    @if($odooCategory->accountingFamilies->count() > 0)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($odooCategory->accountingFamilies as $family)
                                <a href="{{ route('accounting_families.edit', $family->id) }}" class="badge bg-warning text-dark text-decoration-none">
                                    {{ $family->family_name }} ({{ $family->code_cegid }})
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">{{ __('locale.no_accounting_families_attached') }}</p>
                    @endif
                </div>
            </div>

            {{-- Linked Equipments --}}
            <div class="col-12">
                <div class="module-record-card card card-premium p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title text-primary mb-0">
                            <i class="bi bi-box-seam me-2"></i>{{ __('locale.linked_equipments') }} ({{ $equipments->total() }})
                        </h5>
                        @if($equipments->total() > 0)
                            <a href="{{ route('equipments.index', ['odoo_category_id' => $odooCategory->id]) }}" class="btn btn-outline-primary btn-sm">
                                {{ __('locale.view_all_articles') }}
                            </a>
                        @endif
                    </div>

                    @if($equipments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>{{ __('locale.reference') }}</th>
                                        <th>{{ __('locale.designation') }}</th>
                                        <th>{{ __('locale.brand') }}</th>
                                        <th>{{ __('locale.type') }}</th>
                                        <th>{{ __('locale.sale_price_currency') }}</th>
                                        <th class="text-end">{{ __('locale.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($equipments as $equipment)
                                        <tr>
                                            <td class="fw-bold">{{ $equipment->reference }}</td>
                                            <td>{{ $equipment->designation ?? $equipment->name }}</td>
                                            <td>{{ $equipment->brand->name ?? '-' }}</td>
                                            <td>{{ $equipment->type->name ?? '-' }}</td>
                                            <td>{{ number_format($equipment->selling_price, 2, ',', ' ') }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('equipments.show', $equipment->id) }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $equipments->links('pagination::bootstrap-5') }}
                        </div>
                    @else
                        <p class="text-muted mb-0">{{ __('locale.no_equipments_in_odoo_category') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
