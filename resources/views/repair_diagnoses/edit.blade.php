@extends('layouts.app')
@section('portal-module', 'repair_diagnoses')
@section('portal-page', 'edit')
@section('portal-kind', 'form')
@section('portal-family', 'admin')
@section('portal-icon', 'gear')


@section('title', 'Modifier diagnostic SAV')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('repair_diagnoses.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">
            Modifier diagnostic : {{ $repairDiagnosis->name }}
        </h1>
    </div>
@endsection

@section('content')
<div class="container-xxl py-3">
    <form action="{{ route('repair_diagnoses.update', $repairDiagnosis) }}" method="POST">
        @csrf
        @method('PUT')
        @include('repair_diagnoses._form')
    </form>
</div>
@endsection
