@extends('layouts.app')

@section('title', __('locale.lbl_odoo_categories') ?? 'Catégories Odoo')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.lbl_odoo_categories') ?? 'Catégories Odoo' }}</h1>
        <span class="header-count">({{ $categories->total() }})</span>
    </div>
@endsection

@section('navbar-search')
    <form method="GET" action="{{ route('odoo_categories.index') }}" class="w-100">
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
    <a href="{{ route('odoo_categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.add_category') ?? 'Ajouter une catégorie' }}
    </a>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <form action="{{ route('odoo_categories.index') }}" method="GET" class="d-flex flex-grow-1 gap-2" style="max-width: 600px;">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="{{ __('locale.search') ?? 'Rechercher par nom, ID Odoo, chemin...' }}" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
                @if(count($parentOptions) > 0)
                <select name="parent_odoo_id" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="">{{ __('locale.all_parents') ?? 'Toutes les catégories parentes' }}</option>
                    <option value="root" {{ request('parent_odoo_id') === 'root' ? 'selected' : '' }}>{{ __('locale.root_categories_only') ?? 'Racines uniquement' }}</option>
                    @foreach($parentOptions as $parent)
                        <option value="{{ $parent->odoo_id }}" {{ request('parent_odoo_id') == $parent->odoo_id ? 'selected' : '' }}>
                            {{ $parent->name }} ({{ $parent->odoo_id }})
                        </option>
                    @endforeach
                </select>
                @endif
                @if(request('search') || request('parent_odoo_id'))
                    <a href="{{ route('odoo_categories.index') }}" class="btn btn-outline-secondary" title="{{ __('locale.reset_filters') ?? 'Réinitialiser' }}">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
            </form>
            <div class="d-flex gap-2">
                <form action="{{ route('odoo_categories.sync') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-repeat me-1"></i> {{ __('locale.sync_from_odoo') ?? 'Synchroniser depuis Odoo' }}
                    </button>
                </form>
                <a href="{{ route('odoo_categories.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> {{ __('locale.add_odoo_category') ?? 'Ajouter une catégorie' }}
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div id="reorder-feedback" class="alert alert-success alert-dismissible fade show d-none" role="alert">
            <i class="bi bi-check-circle me-2"></i><span class="feedback-text"></span>
            <button type="button" class="btn-close" onclick="this.parentElement.classList.add('d-none')"></button>
        </div>

        <div class="card card-premium">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 90px;" class="text-center">{{ __('locale.sort_order') ?? 'Ordre' }}</th>
                            <th style="width: 100px;">{{ __('locale.odoo_id') }}</th>
                            <th>{{ __('locale.name') }}</th>
                            <th>{{ __('locale.parent_category') }}</th>
                            <th>{{ __('locale.taxes_sale_purchase') }}</th>
                            <th class="text-center">{{ __('locale.subcategories') }}</th>
                            <th class="text-center">{{ __('locale.equipments') }}</th>
                            <th class="text-center">{{ __('locale.accounting_families_short') }}</th>
                            <th class="text-end" style="width: 100px;">{{ __('locale.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="odoo-categories-table" data-reorder-url="{{ route('odoo_categories.reorder') }}">
                        @forelse ($categories as $category)
                            <tr data-id="{{ $category->id }}" data-sort-order="{{ $category->sort_order }}" style="cursor: pointer;" onclick="window.location='{{ route('odoo_categories.show', $category->id) }}'">
                                <td class="text-center align-middle" onclick="event.stopPropagation();">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <i class="bi bi-grip-vertical text-muted drag-handle fs-5" role="button" style="cursor: grab;" title="{{ __('locale.odoo_category_drag_help') ?? 'Glisser pour réordonner' }}"></i>
                                        <span class="badge bg-light text-dark border fw-bold sort-order-badge">{{ $category->sort_order }}</span>
                                        <div class="d-inline-flex flex-column ms-1">
                                            <a href="javascript:void(0)" class="text-muted text-decoration-none" title="Monter" onclick="moveCategoryRow(this, 'up')">
                                                <i class="bi bi-chevron-up" style="font-size: 0.7rem; line-height: 1;"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="text-muted text-decoration-none" title="Descendre" onclick="moveCategoryRow(this, 'down')">
                                                <i class="bi bi-chevron-down" style="font-size: 0.7rem; line-height: 1;"></i>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border fw-bold">{{ $category->odoo_id }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('odoo_categories.show', $category->id) }}" class="text-primary fw-semibold text-decoration-none">
                                        {{ $category->name }}
                                    </a>
                                </td>
                                <td>
                                    @if($category->parent)
                                        <a href="{{ route('odoo_categories.show', $category->parent->id) }}" class="badge bg-secondary text-decoration-none" onclick="event.stopPropagation();">
                                            <i class="bi bi-folder2 me-1"></i>{{ $category->parent->name }}
                                        </a>
                                    @elseif($category->parent_odoo_id)
                                        <span class="badge bg-secondary">ID #{{ $category->parent_odoo_id }}</span>
                                    @else
                                        <span class="text-muted"><i class="bi bi-dash-lg"></i></span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        @if(!empty($category->taxes_id))
                                             <div class="small">
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25" title="{{ __('locale.customer_taxes') }}">
                                                    <i class="bi bi-tag me-1"></i>{{ __('locale.customer_taxes') }} ({{ count((array)$category->taxes_id) }})
                                                </span>
                                            </div>
                                        @endif
                                        @if(!empty($category->supplier_taxes_id))
                                            <div class="small">
                                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25" title="{{ __('locale.supplier_taxes') }}">
                                                    <i class="bi bi-cart me-1"></i>{{ __('locale.supplier_taxes') }} ({{ count((array)$category->supplier_taxes_id) }})
                                                </span>
                                            </div>
                                        @endif
                                        @if(empty($category->taxes_id) && empty($category->supplier_taxes_id))
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($category->children_count > 0)
                                        <a href="{{ route('odoo_categories.index', ['parent_odoo_id' => $category->odoo_id]) }}" class="badge bg-info text-dark text-decoration-none" onclick="event.stopPropagation();">
                                            {{ $category->children_count }}
                                        </a>
                                    @else
                                        <span class="text-muted">0</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($category->equipments_count > 0)
                                        <a href="{{ route('equipments.index', ['odoo_category_id' => $category->id]) }}" class="badge bg-primary text-decoration-none" onclick="event.stopPropagation();">
                                            {{ $category->equipments_count }}
                                        </a>
                                    @else
                                        <span class="text-muted">0</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($category->accounting_families_count > 0)
                                        <span class="badge bg-warning text-dark">{{ $category->accounting_families_count }}</span>
                                    @else
                                        <span class="text-muted">0</span>
                                    @endif
                                </td>
                                <td class="text-end" onclick="event.stopPropagation();">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" type="button" id="catActionMenu{{ $category->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="catActionMenu{{ $category->id }}">
                                            <li>
                                                <a href="{{ route('odoo_categories.show', $category->id) }}" class="dropdown-item">
                                                    <i class="bi bi-eye me-2 text-info"></i>{{ __('locale.view') ?? 'Consulter' }}
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('odoo_categories.edit', $category->id) }}" class="dropdown-item">
                                                    <i class="bi bi-pencil-square me-2 text-primary"></i>{{ __('locale.edit') ?? 'Modifier' }}
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('odoo_categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('{{ __('locale.confirm_delete_odoo_category') ?? 'Êtes-vous sûr de vouloir supprimer cette catégorie Odoo ?' }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
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
                                <td colspan="9" class="text-center py-4 text-muted">
                                    {{ __('locale.no_odoo_categories_found') ?? 'Aucune catégorie Odoo trouvée.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $categories->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"
    integrity="sha384-BSxuMLxX+FCbTdYec3TbXlnMGEEM2QXTFdtDaveen71o+jswm2J36+xFqp8k4VHM" crossorigin="anonymous"></script>
<script>
    function showFeedback(message, type) {
        const feedback = document.getElementById('reorder-feedback');
        if (!feedback) return;
        feedback.className = `alert alert-${type} alert-dismissible fade show mb-3`;
        const textSpan = feedback.querySelector('.feedback-text');
        if (textSpan) textSpan.textContent = message;
        feedback.classList.remove('d-none');
        setTimeout(() => feedback.classList.add('d-none'), 4000);
    }

    function saveCategoriesOrder() {
        const tableBody = document.getElementById('odoo-categories-table');
        if (!tableBody) return;
        const rows = Array.from(tableBody.querySelectorAll('tr[data-id]'));
        if (!rows.length) return;

        // Collect existing sort_orders sorted ascending
        const existingOrders = rows.map(r => parseInt(r.dataset.sortOrder, 10) || 0).sort((a, b) => a - b);

        const items = rows.map((r, index) => {
            const newOrder = existingOrders[index] !== undefined ? existingOrders[index] : (index + 1);
            r.dataset.sortOrder = newOrder;
            const badge = r.querySelector('.sort-order-badge');
            if (badge) badge.textContent = newOrder;
            return {
                id: parseInt(r.dataset.id, 10),
                sort_order: newOrder
            };
        });

        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = tokenMeta ? tokenMeta.getAttribute('content') : '';

        fetch(tableBody.dataset.reorderUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ items }),
        })
        .then(response => {
            if (!response.ok) throw new Error('Network error');
            return response.json();
        })
        .then(data => {
            showFeedback(data.message || '{{ __("locale.odoo_category_order_updated") }}', 'success');
        })
        .catch(err => {
            console.error('Error updating order:', err);
            showFeedback('{{ __("locale.odoo_category_order_error") }}', 'danger');
        });
    }

    function moveCategoryRow(btn, direction) {
        const row = btn.closest('tr');
        if (!row) return;
        if (direction === 'up') {
            const prev = row.previousElementSibling;
            if (prev && prev.hasAttribute('data-id')) {
                row.parentNode.insertBefore(row, prev);
                saveCategoriesOrder();
            }
        } else if (direction === 'down') {
            const next = row.nextElementSibling;
            if (next && next.hasAttribute('data-id')) {
                row.parentNode.insertBefore(next, row);
                saveCategoriesOrder();
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const tableBody = document.getElementById('odoo-categories-table');
        if (tableBody) {
            new Sortable(tableBody, {
                animation: 150,
                handle: '.drag-handle',
                onEnd: function () {
                    saveCategoriesOrder();
                }
            });
        }
    });
</script>
@endsection
