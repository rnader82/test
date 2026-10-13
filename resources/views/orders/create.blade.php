@extends('layouts.app')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.orders.titles.create') }}</h1>
    </div>
@endsection

@section('content')
	<div class="content-wrapper">
		<div class="container-fluid px-3 py-2 flex-grow-1">
			<form action="{{ route('orders.store') }}" method="POST" id="order-form" class="mt-1">
				@csrf

				@include('orders._form')

				<div class="d-flex justify-content-end gap-2 mt-3 mb-4">
					<a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-4 fw-semibold">{{ __('locale.orders.buttons.cancel') }}</a>
					<button type="submit" form="order-form" class="btn btn-sm btn-primary rounded-pill px-4 fw-bold shadow-sm" style="background-color: #0288d1; border-color: #0288d1;">
						<i class="bi bi-check-lg me-1"></i> {{ __('locale.orders.buttons.save') }}
					</button>
				</div>
			</form>
		</div>
	</div>
@endsection

