@extends('layouts.app')

@section('title', $warrantyType->name)

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('warranty_types.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ $warrantyType->name }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('warranty_types.edit', $warrantyType) }}" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-pencil-square me-1"></i>{{ __('locale.edit') ?? 'Modifier' }}
        </a>
        <form action="{{ route('warranty_types.destroy', $warrantyType) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('locale.delete_confirmation') }}')">
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
                <div class="card card-premium shadow-sm border-0 mb-4">
                    <div class="card-header bg-light border-bottom py-3 d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
                            <i class="bi bi-shield-check me-2 text-primary"></i>{{ __('locale.lbl_warranty_type_details') ?? 'Détails du type de garantie' }}
                        </h5>
                        @if($issue_active = $warrantyType->is_active)
                            <span class="badge bg-success-subtle text-success border border-success-subtle">{{ __('locale.active') ?? 'Actif' }}</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">{{ __('locale.inactive') ?? 'Inactif' }}</span>
                        @endif
                    </div>
                    <div class="card-body p-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-4 text-muted">{{ __('locale.name') }}</dt>
                            <dd class="col-sm-8 fw-semibold text-dark">{{ $warrantyType->name }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.code') ?? 'Code' }}</dt>
                            <dd class="col-sm-8 font-monospace text-primary">{{ $warrantyType->code }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.color') }}</dt>
                            <dd class="col-sm-8">
                                @if($warrantyType->color)
                                    <span class="badge px-3 py-1 text-white" style="background-color: {{ $warrantyType->color }};">
                                        {{ $warrantyType->color }}
                                    </span>
                                @else
                                    <span class="text-muted small">&mdash;</span>
                                @endif
                            </dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.description') }}</dt>
                            <dd class="col-sm-8 text-dark">{{ $warrantyType->description ?: '—' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
