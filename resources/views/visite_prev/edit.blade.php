@extends('layouts.app')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('visite-prev.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.visite_prev.edit_vp_visit') }} #{{ $vpVisite->id }}</h1>
    </div>
@endsection

@section('content')
<div class="container">

    <form action="{{ route('visite-prev.update', $vpVisite->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('visite_prev.partials.form', ['vpVisit' => $vpVisite, 'users' => $users])
        <button type="submit" class="btn btn-warning">{{ __('locale.visite_prev.update') }}</button>
    </form>
</div>
@endsection

@section('scripts')
    @parent
    @include('visite_prev.partials.scripts')
@endsection