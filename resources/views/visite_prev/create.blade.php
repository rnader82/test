@extends('layouts.app')
@section('portal-module', 'visite_prev')
@section('portal-page', 'create')
@section('portal-kind', 'form')
@section('portal-family', 'technical')
@section('portal-icon', 'screwdriver-wrench')


@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('visite-prev.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.visite_prev.create_vp_visit') }}</h1>
    </div>
@endsection

@section('content')
<div class="container mt-4">
    <form action="{{ route('visite-prev.store') }}" method="POST">
        @csrf
        @include('visite_prev.partials.form', ['vpVisit' => null, 'users' => $users])
        <button type="submit" class="btn btn-primary">{{ __('locale.visite_prev.save') }}</button>
    </form>
</div>
@endsection


@section('scripts')
    @parent
    @include('visite_prev.partials.scripts')
@endsection