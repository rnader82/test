@extends('layouts.app')
@section('portal-module', 'tech_intervs')
@section('portal-page', 'create')
@section('portal-kind', 'form')
@section('portal-family', 'technical')
@section('portal-icon', 'screwdriver-wrench')


@php
    $pageTitle = 'Nouvelle intervention';
    $truncatedTitle = \Illuminate\Support\Str::limit($pageTitle, 60);
@endphp

@section('title', $truncatedTitle)

@section('navbar-title')
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('tech_intervs.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 bg-white text-dark border p-2 shadow-sm" title="Retour">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <h1 class="mb-0 fw-bold" style="color: #0f172a; font-size: 1.35rem;">
            {{ $truncatedTitle }}
        </h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2 me-2">
        <a href="{{ route('tech_intervs.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 bg-white text-dark border px-4 py-2 fw-semibold shadow-sm">
            Annuler
        </a>
        <button type="button" onclick="document.getElementById('tech_interv_form').requestSubmit ? document.getElementById('tech_interv_form').requestSubmit() : document.getElementById('tech_interv_form').submit()" class="btn btn-primary btn-sm px-4 py-2 rounded-3 fw-semibold shadow-sm" style="background-color: #4338ca; border-color: #4338ca;">
            Enregistrer
        </button>
    </div>
@endsection

@section('content')
<div class="container-fluid px-4 py-3">
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm border-0 rounded-4 mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @include('tech_intervs.form', [
        'action' => route('tech_intervs.store'),
        'method' => 'POST',
        'technicalIntervention' => $technicalIntervention,
        'users' => $users,
        'priorities' => $priorities,
        'severities' => $severities,
        'types' => $types,
        'subTypes' => $subTypes ?? collect(),
        'metiers' => $metiers,
        'statuses' => $statuses,
        'states' => $states,
        'warehouses' => $warehouses,
        'issues' => $issues,
        'arr_preset' => $arr_preset ?? [],
    ])
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.initTechIntervForm === 'function') {
            window.initTechIntervForm(document);
        }
    });
</script>
@endpush
