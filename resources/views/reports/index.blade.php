@extends('layouts.app')
@section('portal-module', 'reports')
@section('portal-page', 'index')
@section('portal-kind', 'performance')
@section('portal-family', 'performance')
@section('portal-icon', 'chart-line')


@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.menu_reports') }}</h1>
    </div>
@endsection

@section('content')
<div class="container-xxl mt-4">

        <div class="row g-4">
            @foreach($reports as $report)
                @if(Auth::user()->hasPermission($report->permission_slug))
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ $report->route_name ? route($report->route_name) : '#' }}" class="text-decoration-none">
                            <div class="performance-card card h-100 card-premium hover-elevate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar avatar-md me-3">
                                            <span class="avatar-initial rounded bg-label-primary">
                                                <i class="{{ $report->icon }} fs-4"></i>
                                            </span>
                                        </div>
                                        <h5 class="card-title mb-0">{{ $report->translated_title }}</h5>
                                    </div>
                                    <p class="card-text text-muted">
                                        {{ $report->translated_description }}
                                    </p>
                                    <div class="mt-3 d-flex justify-content-between align-items-center">
                                        <span class="badge bg-label-primary">{{ __('locale.view_report') }}</span>
                                        <i class="bi bi-chevron-right text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
            @endforeach

            @if($reports->isEmpty())
                <div class="col-12">
                    <div class="alert alert-info" role="alert">
                        {{ __('locale.no_reports_available') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

<style>
    .hover-elevate {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .hover-elevate:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    .card-premium {
        border: none;
        border-radius: 0.75rem;
        background: #fff;
    }
</style>
@endsection
