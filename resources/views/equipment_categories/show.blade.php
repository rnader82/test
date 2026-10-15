@extends('layouts.app')
@section('portal-module', 'equipment_categories')
@section('portal-page', 'show')
@section('portal-kind', 'record')
@section('portal-family', 'catalog')
@section('portal-icon', 'tags')


@section('title', $equipmentCategory->name)

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('equipment_categories.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ $equipmentCategory->name }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('equipment_categories.edit', $equipmentCategory) }}" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-pencil-square me-1"></i>{{ __('locale.edit') ?? 'Modifier' }}
        </a>
        <form action="{{ route('equipment_categories.destroy', $equipmentCategory) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('locale.delete_confirmation') }}')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger px-3 py-1 fs-12">
                <i class="bi bi-trash me-1"></i>{{ __('locale.delete') }}
            </button>
        </form>
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="module-record-card card card-premium shadow-sm border-0 mb-4">
                    <div class="card-header bg-light border-bottom py-3 d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
                            <i class="bi bi-grid me-2 text-primary"></i>{{ __('locale.lbl_equipment_category_details') ?? 'Détails de la catégorie' }}
                        </h5>
                        @if($equipmentCategory->sort_order !== null)
                            <span class="badge bg-light text-secondary border font-monospace">
                                Ordre: {{ $equipmentCategory->sort_order }}
                            </span>
                        @endif
                    </div>
                    <div class="card-body p-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-4 text-muted">{{ __('locale.id') }}</dt>
                            <dd class="col-sm-8 fw-semibold text-dark">#{{ $equipmentCategory->id }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.name') }}</dt>
                            <dd class="col-sm-8 fw-semibold text-dark">{{ $equipmentCategory->name }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.description') }}</dt>
                            <dd class="col-sm-8 text-dark">{{ $equipmentCategory->description ?: '—' }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.order') ?? 'Ordre d\'affichage' }}</dt>
                            <dd class="col-sm-8">{{ $equipmentCategory->sort_order ?? '—' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
