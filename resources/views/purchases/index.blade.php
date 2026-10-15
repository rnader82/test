@extends('layouts.app')
@section('portal-module', 'purchases')
@section('portal-page', 'index')
@section('portal-kind', 'list')
@section('portal-family', 'purchasing')
@section('portal-icon', 'cart-flatbed')


@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.purchases') ?? 'Achats' }}</h1>
        <span class="header-count">({{ $purchases->total() ?? 0 }})</span>
    </div>
@endsection

@section('page-actions')
    <a href="{{ route('purchases.create') }}" class="btn btn-primary" title="{{ __('locale.btn_new_purchase') ?? 'Nouvel Achat' }}">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_new_purchase') ?? 'Nouvel Achat' }}
    </a>
@endsection

@section('content')

@php
    $modulePageRecords = method_exists($purchases, 'items') ? collect($purchases->items()) : collect($purchases);
@endphp
<section class="supply-summary portal-module-summary" aria-label="{{ __('locale.portal.modules.page_scope') }}">
    <article class="module-card"><small>{{ __('locale.portal.modules.records_total') }}</small><strong>{{ method_exists($purchases, 'total') ? $purchases->total() : $modulePageRecords->count() }}</strong></article>
    <article class="module-card"><small>{{ __('locale.portal.modules.records_page') }}</small><strong>{{ $modulePageRecords->count() }}</strong></article>
    <article class="module-card"><small>{{ __('locale.portal.modules.pending_page') }}</small><strong>{{ $modulePageRecords->filter(fn ($record) => in_array($record->status, ['pending'], true))->count() }}</strong></article>
    <article class="module-card"><small>{{ __('locale.portal.modules.completed_page') }}</small><strong>{{ $modulePageRecords->filter(fn ($record) => in_array($record->status, ['received'], true))->count() }}</strong></article>
</section>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="table-toolbar portal-original-toolbar">
            <div class="toolbar-left">
                <span class="text-muted small">
                    Résultats {{ $purchases->firstItem() ?? 0 }}-{{ $purchases->lastItem() ?? 0 }} sur {{ $purchases->total() ?? 0 }}
                </span>
            </div>
            <div class="toolbar-right">
            </div>
        </div>


    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <table class="module-table table table-bordered table-hover">
            <thead>
                <tr>
                        <th>{{ __('locale.lbl_reference') ?? 'Référence' }}</th>
                        <th>{{ __('locale.lbl_supplier') ?? 'Fournisseur' }}</th>
                        <th>{{ __('locale.lbl_date') ?? 'Date' }}</th>
                        <th>{{ __('locale.expected_delivery') ?? 'Livraison Prévue' }}</th>
                        <th>{{ __('locale.lbl_status') ?? 'Statut' }}</th>
                        <th class="text-end">{{ __('locale.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($purchases as $purchase)
                        <tr>
                            <td>
                                <a href="{{ route('purchases.show', $purchase) }}" class="fw-bold text-dark text-decoration-none">
                                    {{ $purchase->reference }}
                                </a>
                            </td>
                            <td>{{ $purchase->supplier->name ?? '-' }}</td>
                            <td>{{ $purchase->created_at->format('d/m/Y') }}</td>
                            <td>{{ $purchase->expected_delivery_date ? $purchase->expected_delivery_date->format('d/m/Y') : '-' }}</td>
                            <td>
                                @if($purchase->status === 'pending')
                                    <span class="badge bg-label-warning">{{ __('locale.pending') ?? 'En attente' }}</span>
                                @elseif($purchase->status === 'received')
                                    <span class="badge bg-label-success">{{ __('locale.received') ?? 'Reçu' }}</span>
                                @else
                                    <span class="badge bg-label-danger">{{ __('locale.cancelled') ?? 'Annulé' }}</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="{{ route('purchases.show', $purchase) }}">
                                            <i class="bi bi-eye me-1"></i> {{ __('locale.btn_show') }}
                                        </a>
                                        <a class="dropdown-item" href="{{ route('purchases.edit', $purchase) }}">
                                            <i class="bi bi-pencil-square me-1"></i> {{ __('locale.edit') }}
                                        </a>
                                        <form action="{{ route('purchases.destroy', $purchase) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('{{ __('locale.confirm_delete') ?? 'Supprimer cet achat?' }}')">
                                                <i class="bi bi-trash me-1"></i> {{ __('locale.btn_delete') ?? 'Supprimer' }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-cart fs-1 mb-2"></i>
                                    <p>{{ __('locale.lbl_no_records_found') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
        </tbody>
    </table>
    
    <div class="mt-3">
        {{ $purchases->withQueryString()->links() }}
    </div>
    </div>
</div>

@endsection
