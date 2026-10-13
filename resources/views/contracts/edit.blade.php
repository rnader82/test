@extends('layouts.app')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('contracts.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.contracts.edit') }}</h1>
    </div>
@endsection

@section('content')
	<div class="container mt-4">
		<form action="{{ route('contracts.update', $contract) }}" method="POST" enctype="multipart/form-data">
			@csrf
			@method('PUT')
			@include('contracts._form', ['contract' => $contract])
		</form>
	</div>
@endsection

