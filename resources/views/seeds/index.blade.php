@extends('layouts.app')
@section('portal-module', 'seeds')
@section('portal-page', 'index')
@section('portal-kind', 'list')
@section('portal-family', 'admin')
@section('portal-icon', 'gear')


@section('content')
	<div class="content-wrapper">
		<div class="container-xxl flex-grow-1 container-p-y">
			<div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
				<div>
					<h4 class="mb-1">{{ __('Seeding dashboard') }}</h4>
					<p class="text-muted mb-0">{{ __('Launch database seeders per module or per factory (excluding clients, contacts and devis).') }}</p>
				</div>
				<a href="{{ route('seeds.advanced') }}" class="btn btn-secondary">
					<i class="ti ti-settings me-1"></i> {{ __('Advanced Seeder') }}
				</a>
			</div>

			@if (session('seed_status'))
				@php($status = session('seed_status'))
				@php($batchSize = $status['per_chunk'] ?? 10)
				@php($chunkSize = $status['chunk_size'] ?? null)
				<div class="alert alert-success" role="alert">
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<h6 class="alert-heading mb-1">{{ __('Seeding started for :module', ['module' => $status['module']]) }}</h6>
							<p class="mb-0">{{ __('Records are inserted :count at a time to keep memory usage low.', ['count' => $batchSize]) }}</p>
							@if ($chunkSize)
								<p class="mb-0 small text-muted">{{ __('Chunked seeders run in slices of :count records to reduce memory pressure.', ['count' => $chunkSize]) }}</p>
							@endif
							@if (!empty($status['messages']))
								<ul class="mb-0 mt-2">
									@foreach ($status['messages'] as $message)
										<li class="small">{{ $message }}</li>
									@endforeach
								</ul>
							@endif
						</div>
						<span class="badge bg-label-primary">{{ __(':count per batch', ['count' => $batchSize]) }}</span>
					</div>
				</div>
			@endif

			<div class="row g-4">
				@foreach ($modules as $key => $module)
					
					<div class="col-md-6 col-lg-2">
						<div class="card h-100">
							<div class="card-body d-flex flex-column">
								<div class="d-flex justify-content-between align-items-start mb-3">
									<h6 class="card-title mb-0">{{ $module['label'] }}</h6>
									<span class="badge bg-label-info">{{ __(':count per batch', ['count' => $module['batch_size']]) }}</span>
								</div>
								<div class="mt-auto">
									<form method="POST" action="{{ route('seeds.run') }}">
										@csrf
										<input type="hidden" name="module" value="{{ $key }}">
										<button type="submit" class="btn btn-primary w-100">
											{{ __('Seed :module', ['module' => $module['label']]) }}
										</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</div>
@endsection
