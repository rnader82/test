@extends('layouts.app')

@section('title', __('locale.issue_criticities_list_title'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.issue_criticities_list_title') }}</h1>
        <span class="header-count">({{ method_exists($criticities, 'total') ? $criticities->total() : count($criticities) }})</span>
    </div>
@endsection

@section('page-actions')
    <a href="{{ route('issue_criticities.create') }}" class="btn btn-primary" title="{{ __('locale.btn_create_issue_criticity') ?? 'Ajouter' }}">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_create_issue_criticity') ?? 'Nouvelle criticité' }}
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
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 250px;">{{ __('locale.name') }}</th>
                                <th>{{ __('locale.description') }}</th>
                                <th style="width: 140px;">{{ __('locale.lbl_color') }}</th>
                                <th class="text-end pe-4" style="width: 120px;">{{ __('locale.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($criticities as $criticity)
                                @php
                                    $color = $criticity->color ?: '#3b82f6';
                                    $textColor = isColorLightOrDark($color) === 'light' ? '#0f172a' : '#ffffff';
                                @endphp
                                <tr style="cursor: pointer;" onclick="window.location='{{ route('issue_criticities.show', $criticity) }}'">
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge px-3 py-2 fw-semibold" style="background-color: {{ $color }}; color: {{ $textColor }}; border-radius: 6px;">
                                                {{ $criticity->translated_name }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-muted small">
                                        {{ $criticity->translated_description ? Str::limit($criticity->translated_description, 80) : '—' }}
                                    </td>
                                    <td>
                                        @if ($criticity->color)
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="d-inline-block rounded-circle border" style="width: 16px; height: 16px; background-color: {{ $criticity->color }};"></span>
                                                <span class="font-monospace text-muted small">{{ $criticity->color }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted small">{{ __('locale.lbl_not_set') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4" onclick="event.stopPropagation();">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" id="critActionMenu{{ $criticity->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border" aria-labelledby="critActionMenu{{ $criticity->id }}">
                                                <li>
                                                    <a href="{{ route('issue_criticities.show', $criticity) }}" class="dropdown-item">
                                                        <i class="bi bi-eye me-2 text-info"></i>{{ __('locale.btn_show') ?? 'Voir' }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('issue_criticities.edit', $criticity) }}" class="dropdown-item">
                                                        <i class="bi bi-pencil-square me-2 text-primary"></i>{{ __('locale.edit') }}
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('issue_criticities.destroy', $criticity) }}" method="POST">
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
                                        {{ __('locale.no_issue_criticities_found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if(method_exists($criticities, 'hasPages') && $criticities->hasPages())
                <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
                    <span class="text-muted small">
                        {{ __('locale.results_sur', ['first' => $criticities->firstItem() ?? 0, 'last' => $criticities->lastItem() ?? 0, 'total' => $criticities->total()]) }}
                    </span>
                    <div>
                        {{ $criticities->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection