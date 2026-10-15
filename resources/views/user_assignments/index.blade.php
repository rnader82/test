@extends('layouts.app')
@section('portal-module', 'user_assignments')
@section('portal-page', 'index')
@section('portal-kind', 'list')
@section('portal-family', 'technical')
@section('portal-icon', 'screwdriver-wrench')


@section('title', __('locale.lbl_user_assignments'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.lbl_user_assignments') }}</h1>
        <span class="header-count">({{ method_exists($users, 'total') ? $users->total() : count($users) }})</span>
    </div>
@endsection

@section('page-actions')
    <a href="{{ route('user_assignments.create') }}" class="btn btn-primary" title="{{ __('locale.btn_create_user_assignment') }}">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_create_user_assignment') }}
    </a>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
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
                                <th class="ps-4" style="width: 280px;">{{ __('locale.lbl_user') }}</th>
                                <th>{{ __('locale.lbl_clients') }}</th>
                                <th class="text-end pe-4" style="width: 120px;">{{ __('locale.lbl_actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                @php
                                    $userAssignments = $user->userAssignments;
                                    $clientIds = $userAssignments->pluck('client_id')->unique();
                                    $clientNames = \App\Models\Client::whereIn('id', $clientIds)->pluck('raison_sociale')->toArray();
                                @endphp
                                <tr style="cursor: pointer;" onclick="window.location='{{ route('user_assignments.show', $user->id) }}'">
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="d-flex align-items-center justify-content-center bg-primary-subtle text-primary fw-bold rounded-circle" 
                                                 style="width: 38px; height: 38px; font-size: 0.85rem; text-transform: uppercase;">
                                                {{ substr($user->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <a href="{{ route('user_assignments.show', $user->id) }}" class="fw-semibold text-dark text-decoration-none">
                                                    {{ $user->name }}
                                                </a>
                                                <small class="text-muted d-block" style="font-size: 0.8rem;">{{ $user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1 align-items-center">
                                            @if(empty($clientNames))
                                                <span class="text-muted fst-italic small">
                                                    {{ __('locale.lbl_no_clients_assigned') ?? 'Aucun client assigné' }}
                                                </span>
                                            @else
                                                @foreach(array_slice($clientNames, 0, 4) as $clientName)
                                                    <span class="badge bg-light text-dark border">
                                                        <i class="bi bi-building me-1 text-secondary"></i>{{ $clientName }}
                                                    </span>
                                                @endforeach
                                                @if(count($clientNames) > 4)
                                                    <span class="badge bg-secondary-subtle text-secondary" title="{{ implode(', ', array_slice($clientNames, 4)) }}">
                                                        +{{ count($clientNames) - 4 }} {{ __('locale.lbl_more') ?? 'autres' }}
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-end pe-4" onclick="event.stopPropagation();">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" id="uaActionMenu{{ $user->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border" aria-labelledby="uaActionMenu{{ $user->id }}">
                                                <li>
                                                    <a href="{{ route('user_assignments.show', $user->id) }}" class="dropdown-item">
                                                        <i class="bi bi-eye me-2 text-info"></i>{{ __('locale.btn_show') }}
                                                    </a>
                                                </li>
                                                @if($userAssignments->first())
                                                    <li>
                                                        <a href="{{ route('user_assignments.edit', $userAssignments->first()->id) }}" class="dropdown-item">
                                                            <i class="bi bi-pencil-square me-2 text-primary"></i>{{ __('locale.btn_edit') }}
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">
                                        <i class="bi bi-people fs-2 d-block mb-2 text-muted"></i>
                                        {{ __('locale.lbl_no_records_found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if(method_exists($users, 'hasPages') && $users->hasPages())
                <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
                    <span class="text-muted small">
                        {{ __('locale.results_sur', ['first' => $users->firstItem() ?? 0, 'last' => $users->lastItem() ?? 0, 'total' => $users->total()]) }}
                    </span>
                    <div>
                        {{ $users->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
