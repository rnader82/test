@extends('layouts.app')
@section('portal-module', 'warranty_types')
@section('portal-page', 'edit')
@section('portal-kind', 'form')
@section('portal-family', 'admin')
@section('portal-icon', 'gear')


@section('title', 'Modifier type de garantie SAV')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('warranty_types.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">
            Modifier type de garantie : {{ $warrantyType->name }}
        </h1>
    </div>
@endsection

@section('content')
<div class="container-xxl py-3">
    <form action="{{ route('warranty_types.update', $warrantyType) }}" method="POST">
        @csrf
        @method('PUT')
        @include('warranty_types._form')
    </form>
</div>
@endsection
