@extends('layouts.app')
@section('portal-module', 'contracts')
@section('portal-page', 'create')
@section('portal-kind', 'form')
@section('portal-family', 'relations')
@section('portal-icon', 'users')


@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('contracts.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.contracts.new') }}</h1>
    </div>
@endsection

@section('content')
	<div class="container">
		<form action="{{ route('contracts.store') }}" method="POST" enctype="multipart/form-data">
			@csrf
			@include('contracts._form', ['contract' => null])

		</form>
	</div>
@endsection
