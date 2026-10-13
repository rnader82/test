@extends('layouts.app')

@section('title', __('locale.status') . ': ' . $status->translated_name)

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('statuses.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ $status->translated_name }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('statuses.edit', $status) }}" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-pencil-square me-1"></i>{{ __('locale.edit') }}
        </a>
        <form action="{{ route('statuses.destroy', $status) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('locale.are_you_sure') }}');">
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
            <div class="col-lg-6">
                <div class="card card-premium shadow-sm border-0">
                    <div class="card-header bg-light border-bottom py-3">
                        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
                            <i class="bi bi-info-circle me-2 text-primary"></i>{{ __('locale.status') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-4 text-muted">{{ __('locale.name') }}</dt>
                            <dd class="col-sm-8 fw-semibold text-dark">{{ $status->translated_name }}</dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.color') }}</dt>
                            <dd class="col-sm-8">
                                <span class="badge px-3 py-1" style="background-color: {{ $status->color }}; color: {{ $status->text_color }};">
                                    {{ $status->color }}
                                </span>
                            </dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.module') }}</dt>
                            <dd class="col-sm-8"><span class="badge bg-light text-dark border">{{ ucfirst($status->module) }}</span></dd>

                            <dt class="col-sm-4 text-muted">{{ __('locale.status_type') ?? 'Type' }}</dt>
                            <dd class="col-sm-8">
                                @if($status->is_pending)
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">{{ __('locale.is_pending') ?? 'En attente' }}</span>
                                @elseif($status->is_closed)
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">{{ __('locale.is_closed') ?? 'Fermé' }}</span>
                                @else
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">{{ __('locale.normal') ?? 'Normal' }}</span>
                                @endif
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
