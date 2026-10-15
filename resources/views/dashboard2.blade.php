{{-- The old template dashboard contained demonstration figures and dead controls. --}}
@extends('layouts.app')
@section('portal-module', 'dashboard2')
@section('portal-page', 'index')
@section('portal-kind', 'performance')
@section('portal-family', 'dashboard')
@section('portal-icon', 'house')
@section('navbar-title')<h1>{{ __('locale.dashboard') }}</h1>@endsection
@section('content')
<section class="module-card">
    <h2>{{ __('locale.dashboard') }}</h2>
    <p>{{ __('locale.portal.modules.dashboard') }}</p>
    <a class="btn primary" href="{{ route('dashboard') }}">{{ __('locale.portal.modules.open_dashboard') }}</a>
</section>
@endsection
