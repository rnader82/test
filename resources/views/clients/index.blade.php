@extends('layouts.app') 

@section('navbar-search')
    <form method="GET" action="{{ route('clients.index') }}" class="w-100">
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
		<h1>{{ __('locale.lbl_client') }}</h1>
		{{-- <form id="refreshClientForm2" method="POST" action="/api/clients/import"> 
			@csrf 
			<input type="hidden" value="client" name="file_type" />
			<button type="submit" class="btn btn-outline-primary btn-sm border-0 bg-transparent" title="{{ __('locale.btn_refresh_clients') }}">
				<i class="bi bi-arrow-clockwise fs-4"></i>
			</button>
		</form> --}}
        <span class="header-count">({{ $clients->total() }})</span>
	</div>
@endsection

@section('page-actions')
    <a href="{{ route('clients.create') }}" class="btn btn-primary" title="{{ __('locale.lbl_create_client') }}">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.lbl_create_client') ?? 'Nouveau client' }}
    </a>
@endsection

@section('content')
	<div class="content-wrapper">
		<div class="container-xxl flex-grow-1">
			<div class="table-toolbar">
				<div class="toolbar-left">
                    <span class="text-muted small">
                        {{ __('locale.results_range', ['first' => $clients->firstItem() ?? 0, 'last' => $clients->lastItem() ?? 0, 'total' => $clients->total()]) }}
                    </span>
                </div>
                <div class="toolbar-right d-flex align-items-center gap-2">
                    @if (Auth::user()->isNovalyoAdmin())
                        <a href="{{ route('clients.export', request()->query()) }}" class="btn btn-outline-secondary btn-sm border-0" title="{{ __('locale.export_clients') }}">
                            <i class="bi bi-download me-1"></i> {{ __('locale.btn_export') }}
                        </a>
                    @endif
                    @if (Auth::user()->is_admin)
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm border-0 dropdown-toggle" type="button"
                                    id="syncAllOdooDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                                    title="{{ __('locale.title_sync_all_odoo') }}">
                                <i class="bi bi-arrow-repeat me-1"></i> {{ __('locale.btn_sync_all_odoo') }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="syncAllOdooDropdown">
                                <li>
                                    <form action="{{ route('clients.sync-all-odoo') }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('{{ __('locale.confirm_sync_unsynced') }}')">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-cloud-upload me-2 text-secondary"></i>
                                            {{ __('locale.btn_sync_unsynced_clients') }}
                                        </button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('clients.sync-all-odoo') }}?force=1" method="POST" class="d-inline"
                                          onsubmit="return confirm('{{ __('locale.confirm_force_resync') }}')">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-arrow-clockwise me-2 text-warning"></i>
                                            {{ __('locale.btn_force_resync_all_clients') }}
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endif
                    @php
                        $hasActiveFilters = request()->filled('group_id')
                            || request()->filled('region')
                            || request()->filled('pays')
                            || request()->filled('langue')
                            || (request()->has('closed') && request('closed') !== '0')
                            || request()->filled('type');
                    @endphp
                    <a href="#advancedFilters" data-bs-toggle="collapse" role="button" aria-expanded="{{ $hasActiveFilters ? 'true' : 'false' }}" aria-controls="advancedFilters" class="btn btn-outline-secondary btn-sm border-0 position-relative"> 
                        <i class="bi bi-filter me-1"></i> {{ __('locale.advanced_filters') }} 
                        @if($hasActiveFilters)
                            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-primary border border-light rounded-circle">
                                <span class="visually-hidden">Filtres actifs</span>
                            </span>
                        @endif
                    </a>
                </div>
			</div>
			<div id="responseMessage" class="alert d-none mt-3" role="alert"></div>
			@if ($message = Session::get('success'))
				<div class="alert alert-success mt-3" role="alert"> {{ $message }} </div>
			@endif
			@if ($message = Session::get('error'))
				<div class="alert alert-danger mt-3" role="alert"> {{ $message }} </div>
			@endif
            
            <div class="collapse {{ $hasActiveFilters ? 'show' : '' }}" id="advancedFilters">
                <div class="mb-4">
						<form action="{{ route('clients.index') }}" method="GET" class="row g-3">
							@if(request('search'))
								<input type="hidden" name="search" value="{{ request('search') }}">
							@endif
							@if(request('sort'))
								<input type="hidden" name="sort" value="{{ request('sort') }}">
							@endif
							@if(request('direction'))
								<input type="hidden" name="direction" value="{{ request('direction') }}">
							@endif

							<!-- Group Filter -->
							<div class="col-md-6">
								<label for="group_id" class="form-label">{{ __('locale.lbl_group') }}</label>
								<select name="group_id" id="group_id" class="form-select select2">
									<option value="">{{ __('locale.lbl_all_groups') }}</option>
									@foreach ($groups as $group)
										<option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}> {{ $group->raison_sociale }} </option>
									@endforeach
								</select>
							</div>
							<!-- Region Filter -->
							<div class="col-md-6">
								<label for="region" class="form-label">{{ __('locale.lbl_region') }}</label>
								<select name="region" id="region" class="form-select select2">
									<option value="">{{ __('locale.lbl_all_regions') }}</option>
									@foreach ($regions as $region)
										@if (!empty($region))
											<option value="{{ $region }}" {{ request('region') == $region ? 'selected' : '' }}> {{ $region }} </option>
										@endif
									@endforeach
								</select>
							</div>
							<!-- Pays Filter -->
							<div class="col-md-6">
								<label for="pays" class="form-label">{{ __('locale.lbl_country') }}</label>
								<select name="pays" id="pays" class="form-select select2">
									<option value="">{{ __('locale.lbl_all_countries') }}</option>
									@foreach ($countries as $country)
										@if (!empty($country))
											<option value="{{ $country }}" {{ request('pays') == $country ? 'selected' : '' }}> {{ $country }} </option>
										@endif
									@endforeach
								</select>
							</div>
							<!-- Langue Filter -->
							<div class="col-md-6">
								<label for="langue" class="form-label">{{ __('locale.lbl_langue') }}</label>
								<select name="langue" id="langue" class="form-select select2">
									<option value="">{{ __('locale.lbl_all_langues') }}</option>
									@foreach ($languages as $language)
										<option value="{{ $language }}" {{ request('langue') == $language ? 'selected' : '' }}> {{ $language }} </option>
									@endforeach
								</select>
							</div>
							<!-- Closed Filter -->
							<div class="col-md-6">
								<label for="closed" class="form-label">{{ __('locale.lbl_closed') }}</label>
								<select name="closed" id="closed" class="form-select select2">
									<option value="all" {{ request('closed') === 'all' ? 'selected' : '' }}>{{ __('locale.lbl_all_closed') }}</option>
									<option value="0" {{ !request()->has('closed') || request('closed') === '0' ? 'selected' : '' }}>{{ __('locale.lbl_open') }}</option>
									<option value="1" {{ request('closed') === '1' ? 'selected' : '' }}>{{ __('locale.lbl_closed') }}</option>
								</select>
							</div>
							<!-- Type Filter -->
							<div class="col-md-6">
								<label for="type" class="form-label">{{ __('locale.type') }}</label>
								<select name="type" id="type" class="form-select select2">
									<option value="">{{ __('locale.lbl_all_types') }}</option>
									@foreach ($clientTypes as $type)
										<option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}> {{ $type }} </option>
									@endforeach
								</select>
							</div>
							<!-- Actions -->
							<div class="col-12 d-flex justify-content-end gap-2 mt-3">
								<a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">{{ __('locale.btn_clear') }}</a>
								<button type="submit" class="btn btn-primary">{{ __('locale.btn_apply_filters') }}</button>
							</div>
						</form>
                </div>
            </div>
			@php
				$currentSort = request('sort', 'id');
				$currentDirection = request('direction', 'asc');
				$sortOptions = [
				    'id' => __('locale.lbl_id'),
				    'raison_sociale' => __('locale.lbl_raison_sociale'),
				    'group' => __('locale.lbl_group'),
				    'contract_status' => __('locale.lbl_contract_status'),
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
						<a href="{{ route('clients.index', array_merge(request()->query(), ['sort' => $column, 'direction' => $direction])) }}"
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
				.client-card {
					position: relative;
					transition: transform 0.2s ease;
					cursor: pointer;
				}
				.client-card:hover {
					transform: translateY(-4px);
					z-index: 1000 !important;
					position: relative !important;
				}

				.col:hover {
					z-index: 1000;
					position: relative;
				}
			</style>

			<div class="mt-2">
				<div>
					<div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
						@forelse ($clients as $client)
							@php
								$url = route('clients.show', $client->id);
								$contractStatusValue = $client->contract_status->value ?? null;
								$contractStatusClass = match ($contractStatusValue) {
								    'avec_contrat' => 'badge-matte-success',
								    'sans_contrat' => 'badge-matte-secondary',
								    'contrat_expire' => 'badge-matte-warning',
								    'contrat_resilie' => 'badge-matte-danger',
								    default => 'badge-matte-secondary',
								};
								$clientType = $client->type->value ?? $client->type;
								$clientTypeClass = match ($clientType) {
								    'Groupe' => 'badge-matte-primary',
								    'HQ' => 'badge-matte-dark',
								    'Site' => 'badge-matte-info',
								    default => 'badge-matte-secondary',
								};
								$addressParts = array_filter([
								    trim(implode(' ', array_filter([$client->adresse_1, $client->adresse_2, $client->adresse_3]))),
								    trim(implode(' ', array_filter([$client->code_postal, $client->ville]))),
								    $client->pays,
								]);
								$address = implode(', ', $addressParts);
								$warningReasons = [];
								if ($client->default_payment) {
								    $warningReasons[] = __('locale.default_payment');
								}
								if ($client->concurrent) {
								    $warningReasons[] = __('locale.lbl_concurrent');
								}
								$canEditClient = Auth::user()->is_admin || Auth::user()->hasPermission('edit-clients');
								$canDeleteClient = Auth::user()->is_admin || Auth::user()->hasPermission('delete-clients');
							@endphp
							<div class="col">
								<div class="card client-card h-100" onclick='window.location="{{ $url }}"' style="overflow: visible !important;">
									@if ($client->closed)
										<div class="ribbon-wrapper">
											<div class="ribbon">{{ __('locale.lbl_closed') }}</div>
										</div>
									@endif
									<div class="card-body d-flex flex-column" style="overflow: visible !important;">
										<div class="d-flex justify-content-between align-items-start mb-3">
											<div>
												<h5 class="card-title mb-1">
													@if (!empty($warningReasons))
														<span class="text-danger d-inline-flex align-items-center justify-content-end"
															data-bs-toggle="tooltip"
															data-bs-placement="top"
															title="{{ implode(' • ', $warningReasons) }}">
															<i class="bi bi-exclamation-triangle-fill"></i>
															<span class="visually-hidden">{{ implode(', ', $warningReasons) }}</span>
														</span>
													@endif
													{{ $client->raison_sociale }}
												</h5>
												<div class="text-muted small">
													{{ $client->parent->raison_sociale ?? '' }}
												</div>
											</div>
											<div class="text-end">
												@if ($contractStatusValue)
													<span class="badge {{ $contractStatusClass }}">{{ __('locale.contract_status.' . $contractStatusValue) }}</span>
												@endif
												<span class="badge {{ $clientTypeClass }} ms-1">{{ $clientType }}</span>

											</div>
										</div>

										<div class="mb-3">
											<div class="text-muted small fw-semibold">{{ __('locale.address') }}</div>
											<div>{{ $address ?: __('locale.not_available') }}</div>
										</div>

										<div class="mt-auto pt-3 d-flex align-items-center justify-content-between border-top">
											<div class="d-flex flex-wrap gap-3 small text-muted">
												<div class="d-flex align-items-center">
													<i class="bi bi-ticket-perforated me-1 text-primary"></i>
													<span class="fw-semibold text-dark">{{ $client->tickets_count }}</span>
													<span class="ms-1">{{ __('locale.menu_tickets') }}</span>
												</div>
												<div class="d-flex align-items-center">
													<i class="bi bi-tools me-1 text-primary"></i>
													<span class="fw-semibold text-dark">{{ $client->interventions_count }}</span>
													<span class="ms-1">{{ __('locale.menu_interventions') }}</span>
												</div>
											</div>
											@if ($canEditClient || $canDeleteClient)
												<div class="dropdown" onclick="event.stopPropagation();">
													<button class="btn btn-sm btn-outline-secondary" type="button" id="clientActionMenu{{ $client->id }}" data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="viewport">
														<i class="bi bi-three-dots-vertical"></i>
													</button>
													<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="clientActionMenu{{ $client->id }}">
														@if ($canEditClient)
															<li>
																<a href="{{ route('clients.edit', $client->id) }}" class="dropdown-item">
																	<i class="bi bi-pencil-square me-2"></i>{{ __('locale.edit') }}
																</a>
															</li>
														@endif
														@if ($canDeleteClient)
															<li>
																<form action="{{ route('clients.destroy', $client->id) }}" method="POST">
																	@csrf
																	@method('DELETE')
																	<button type="submit" class="dropdown-item text-danger" onclick="return confirm('{{ __('locale.delete_confirmation') }}')">
																		<i class="bi bi-trash me-2"></i>{{ __('locale.delete') }}
																	</button>
																</form>
															</li>
														@endif
													</ul>
												</div>
											@endif
										</div>
									</div>
								</div>
							</div>
						@empty
							<div class="col">
								<p class="text-center mb-0">{{ __('locale.lbl_no_records_found') }}</p>
							</div>
						@endforelse
					</div>
				</div>
				<div class="d-flex justify-content-center mt-4">
					{{ $clients->withQueryString()->links() }}
				</div>
			</div>
		</div>
	</div>

	<script>
		$(document).ready(function() {
			$('.select2').select2({
				width: '100%',
			});

			const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
			tooltipTriggerList.map(function(tooltipTriggerEl) {
				return new bootstrap.Tooltip(tooltipTriggerEl);
			});

			$('#refreshClientForm2').on('submit', function(event) {
				event.preventDefault();

				const form = $(this);
				const url = form.attr('action');
				const formData = form.serialize();
				const responseMessage = $('#responseMessage');

				$.ajax({
					url: url,
					method: 'POST',
					data: formData,
					success: function(response) {
						responseMessage
							.removeClass('d-none alert-danger')
							.addClass('alert-success')
							.text(response.message)
							.show();
					},
					error: function(xhr) {
						const errorMessage = xhr.responseJSON?.message || '{{ __('locale.error_occurred') }}';
						responseMessage
							.removeClass('d-none alert-success')
							.addClass('alert-danger')
							.text(errorMessage)
							.show();
					}
				});
			});
		});
	</script>
	@endsection

