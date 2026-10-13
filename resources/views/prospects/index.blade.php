@extends('layouts.app') 

@section('navbar-search')
    <form method="GET" action="{{ route('prospects.index') }}" class="w-100">
        <div class="search-bar-mockup">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('locale.search_placeholder') }}">
            @foreach(request()->except(['search', 'page']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </div>
    </form>
@endsection

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
		<h1>{{ __('locale.menu_prospects') }}</h1>
        <span class="header-count">({{ $prospects->total() }})</span>
	</div>
@endsection

@section('page-actions')
    <a href="{{ route('prospects.create') }}" class="btn btn-primary" title="{{ __('locale.lbl_create_prospect') }}">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.lbl_create_prospect') ?? 'Nouveau prospect' }}
    </a>
@endsection

@section('content')
	<div class="content-wrapper">
		<div class="container-xxl flex-grow-1">
			<div class="table-toolbar">
				<div class="toolbar-left">
                    <span class="text-muted small">
                        {{ __('locale.results_range', ['first' => $prospects->firstItem() ?? 0, 'last' => $prospects->lastItem() ?? 0, 'total' => $prospects->total()]) }}
                    </span>
                </div>
			</div>
			@if ($message = Session::get('success'))
				<div class="alert alert-success mt-3" role="alert"> {{ $message }} </div>
			@endif
            
			@php
				$currentSort = request('sort', 'id');
				$currentDirection = request('direction', 'asc');
				$sortOptions = [
				    'id' => __('locale.lbl_id'),
				    'raison_sociale' => __('locale.lbl_raison_sociale'),
				];
			@endphp

			<div class="d-flex flex-wrap justify-content-end align-items-center gap-2 mb-3">
				<span class="text-muted small">{{ __('locale.lbl_sort_by') }}:</span>
				<div class="btn-group" role="group">
					@foreach ($sortOptions as $column => $label)
						@php
							$direction = $currentSort === $column && $currentDirection === 'asc' ? 'desc' : 'asc';
							$isActive = $currentSort === $column;
							$icon = $isActive ? ($currentDirection === 'asc' ? 'up' : 'down') : null;
						@endphp
						<a href="{{ route('prospects.index', array_merge(request()->query(), ['sort' => $column, 'direction' => $direction])) }}"
							class="btn btn-sm btn-outline-secondary {{ $isActive ? 'active' : '' }}">
							<span>{{ $label }}</span>
							@if ($icon)
								<i class="bi bi-caret-{{ $icon }}-fill ms-1"></i>
							@endif
						</a>
					@endforeach
				</div>
			</div>

			<style>
				.prospect-card {
					transition: transform 0.2s ease;
					cursor: pointer;
				}
				.prospect-card:hover {
					transform: translateY(-4px);
					z-index: 1000 !important;
					position: relative !important;
				}
			</style>

			<div class="mt-2">
				<div>
					<div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
						@forelse ($prospects as $prospect)
							@php
								$url = route('prospects.show', $prospect->id);
								$addressParts = array_filter([
								    trim(implode(' ', array_filter([$prospect->adresse_1, $prospect->adresse_2, $prospect->adresse_3]))),
								    trim(implode(' ', array_filter([$prospect->code_postal, $prospect->ville]))),
								    $prospect->pays,
								]);
								$address = implode(', ', $addressParts);
							@endphp
							<div class="col">
								<div class="card prospect-card h-100" onclick='window.location="{{ $url }}"' style="overflow: visible !important;">
									<div class="card-body d-flex flex-column" style="overflow: visible !important;">
										<div class="d-flex justify-content-between align-items-start mb-3">
											<div>
												<h5 class="card-title mb-1">
													{{ $prospect->raison_sociale }}
												</h5>
											</div>
											<div class="text-end">
												@if($prospect->type)
													<span class="badge badge-matte-info">{{ $prospect->type }}</span>
												@endif
											</div>
										</div>

										<div class="mb-3">
											<div class="text-muted small fw-semibold">{{ __('locale.address') }}</div>
											<div>{{ $address ?: __('locale.not_available') }}</div>
										</div>

										<div class="d-flex flex-wrap gap-3 small text-muted">
											<div class="d-flex align-items-center">
												<i class="bi bi-journal-text me-1 text-primary"></i>
												<span class="fw-semibold text-dark">{{ $prospect->reports_count }}</span>
												<span class="ms-1">Rapports</span>
											</div>
										</div>

										<div class="mt-auto pt-3 d-flex justify-content-end border-top">
											<div class="dropdown" onclick="event.stopPropagation();">
												<button class="btn btn-sm btn-outline-secondary" type="button" id="prospectActionMenu{{ $prospect->id }}" data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="viewport">
													<i class="bi bi-three-dots-vertical"></i>
												</button>
												<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="prospectActionMenu{{ $prospect->id }}">
													<li>
														<a href="{{ route('prospects.edit', $prospect->id) }}" class="dropdown-item">
															<i class="bi bi-pencil-square me-2"></i>{{ __('locale.edit') }}
														</a>
													</li>
													<li>
														<form action="{{ route('prospects.destroy', $prospect->id) }}" method="POST">
															@csrf
															@method('DELETE')
															<button type="submit" class="dropdown-item text-danger" onclick="return confirm('{{ __('locale.delete_confirmation') }}')">
																<i class="bi bi-trash me-2"></i>{{ __('locale.delete') }}
															</button>
														</form>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						@empty
							<div class="col-12">
								<p class="text-center mb-0">{{ __('locale.lbl_no_records_found') }}</p>
							</div>
						@endforelse
					</div>
				</div>
				<div class="d-flex justify-content-center mt-4">
					{{ $prospects->withQueryString()->links() }}
				</div>
			</div>
		</div>
	</div>

	@endsection
