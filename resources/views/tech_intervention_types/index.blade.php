@extends('layouts.app')
@section('portal-module', 'tech_intervention_types')
@section('portal-page', 'index')
@section('portal-kind', 'list')
@section('portal-family', 'admin')
@section('portal-icon', 'gear')


@section('title', __('locale.lbl_technical_intervention_types'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.lbl_technical_intervention_types') }}</h1>
        <span class="header-count">({{ method_exists($types, 'total') ? $types->total() : count($types) }})</span>
    </div>
@endsection

@section('page-actions')
    <a href="{{ route('tech_intervention_types.create') }}" class="btn btn-primary" title="{{ __('locale.btn_create_tech_intervention_type') ?? 'Ajouter' }}">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_create_tech_intervention_type') ?? 'Nouveau type' }}
    </a>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card card-premium shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="module-table table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 250px;">{{ __('locale.name') }}</th>
                                <th>{{ __('locale.description') }}</th>
                                <th style="width: 140px;">{{ __('locale.lbl_color') }}</th>
                                <th class="text-end pe-4" style="width: 120px;">{{ __('locale.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($types as $type)
                                @php
                                    $color = (!empty($type->color) ? $type->color : "#3b82f6" );
                                    $textColor = isColorLightOrDark($color) === 'light' ? '#0f172a' : '#ffffff';
                                @endphp
                                <tr style="cursor: pointer;" onclick="window.location='{{ route('tech_intervention_types.show', $type) }}'">
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge px-3 py-2 fw-semibold" style="background-color: {{ $color }}; color: {{ $textColor }}; border-radius: 6px;">
                                                {{ $type->translated_name }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-muted small">
                                        {{ $type->description ? Str::limit($type->description, 80) : '—' }}
                                    </td>
                                    <td>
                                        @if ($type->color)
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="d-inline-block rounded-circle border" style="width: 16px; height: 16px; background-color: {{ $type->color }};"></span>
                                                <span class="font-monospace text-muted small">{{ $type->color }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted small">{{ __('locale.lbl_not_set') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4" onclick="event.stopPropagation();">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" id="typeIntervActionMenu{{ $type->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border" aria-labelledby="typeIntervActionMenu{{ $type->id }}">
                                                <li>
                                                    <a href="{{ route('tech_intervention_types.show', $type) }}" class="dropdown-item">
                                                        <i class="bi bi-eye me-2 text-info"></i>{{ __('locale.btn_show') ?? 'Voir' }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('tech_intervention_types.edit', $type) }}" class="dropdown-item">
                                                        <i class="bi bi-pencil-square me-2 text-primary"></i>{{ __('locale.edit') }}
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('tech_intervention_types.destroy', $type) }}" method="POST">
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
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2 text-muted"></i>
                                        {{ __('locale.lbl_no_records_found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if(method_exists($types, 'hasPages') && $types->hasPages())
                <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
                    <span class="text-muted small">
                        {{ __('locale.results_sur', ['first' => $types->firstItem() ?? 0, 'last' => $types->lastItem() ?? 0, 'total' => $types->total()]) }}
                    </span>
                    <div>
                        {{ $types->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection