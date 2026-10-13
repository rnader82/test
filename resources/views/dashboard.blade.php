@extends('layouts.app')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.dashboard') }}</h1>
    </div>
@endsection

@section('content')
	<div class="content-wrapper">
		<div class="container-xxl flex-grow-1 container-p-y">
			@php
				$filters = $filters ?? ['start_date' => null, 'end_date' => null];
				$period = $filters['period'] ?? null;
			@endphp

			<!-- Premium Filter Card -->
			<div class="card mb-4 border-0">
				<div class="card-body p-4">
					<h5 class="fw-bold mb-3 d-flex align-items-center gap-2" style="font-size: 1.1rem; color: var(--md-sys-color-on-surface);">
						<i class="bi bi-filter-left fs-4 text-primary"></i> {{ __('locale.select_action') ?? 'Filtrer le tableau' }}
					</h5>
					<form method="GET" action="{{ route('dashboard') }}" class="row g-3 align-items-end">
						<div class="col-md-6 col-lg-4" data-client-search data-client-search-endpoint="{{ route('dashboard.clients.search') }}">
							<label for="client_search" class="form-label">{{ __('locale.dashboard_filter_client_label') }}</label>
							<div class="position-relative">
								<input type="search" id="client_search" class="form-control"
									placeholder="{{ __('locale.dashboard_filter_client_placeholder') }}"
									value="{{ $selectedClient?->raison_sociale }}" autocomplete="off"
									data-client-search-input>
								<input type="hidden" name="client_id" id="client_id" value="{{ $selectedClientId }}"
									data-client-id-input>
								<div class="list-group position-absolute w-100 shadow bg-white" data-client-search-results hidden
									style="z-index: 10055; max-height: 260px; overflow-y: auto; border-radius: 8px;"></div>
							</div>
						</div>
						<div class="col-md-3 col-lg-2">
							<label for="period" class="form-label">{{ __('locale.dashboard_filter_period_label') }}</label>
							<select id="period" name="period" class="form-select">
								<option value="">{{ __('locale.dashboard_filter_period_placeholder') }}</option>
								<option value="current_year" @selected($period === 'current_year')>
									{{ __('locale.dashboard_period_current_year') }}
								</option>
								@if ($canUseContractYear)
									<option value="contract_year" @selected($period === 'contract_year')>
										{{ __('locale.dashboard_period_contract_year') }}
									</option>
								@endif
								<option value="yesterday" @selected($period === 'yesterday')>
									{{ __('locale.dashboard_period_yesterday') }}
								</option>
								<option value="last_7_days" @selected($period === 'last_7_days')>
									{{ __('locale.dashboard_period_last_7_days') }}
								</option>
								<option value="last_15_days" @selected($period === 'last_15_days')>
									{{ __('locale.dashboard_period_last_15_days') }}
								</option>
								<option value="last_month" @selected($period === 'last_month')>
									{{ __('locale.dashboard_period_last_month') }}
								</option>
								<option value="last_3_months" @selected($period === 'last_3_months')>
									{{ __('locale.dashboard_period_last_3_months') }}
								</option>
								<option value="last_6_months" @selected($period === 'last_6_months')>
									{{ __('locale.dashboard_period_last_6_months') }}
								</option>
								<option value="last_year" @selected($period === 'last_year')>
									{{ __('locale.dashboard_period_last_year') }}
								</option>
								<option value="custom" @selected($period === 'custom')>
									{{ __('locale.dashboard_period_custom') }}
								</option>
							</select>
						</div>
						<div class="col-md-3 col-lg-2">
							<label for="start_date" class="form-label">{{ __('locale.dashboard_filter_start_date_label') }}</label>
							<input type="date" id="start_date" name="start_date" class="form-control"
								value="{{ $filters['start_date'] }}">
						</div>
						<div class="col-md-3 col-lg-2">
							<label for="end_date" class="form-label">{{ __('locale.dashboard_filter_end_date_label') }}</label>
							<input type="date" id="end_date" name="end_date" class="form-control"
								value="{{ $filters['end_date'] }}">
						</div>
						<div class="col-auto">
							<button type="submit" class="btn btn-primary px-4">{{ __('locale.dashboard_filter_apply') }}</button>
						</div>
						@if ($selectedClientId || $filters['start_date'] || $filters['end_date'] || $period)
							<div class="col-auto">
								<a href="{{ route('dashboard') }}" class="btn btn-secondary px-4">{{ __('locale.dashboard_filter_reset') }}</a>
							</div>
						@endif
					</form>
				</div>
			</div>

			@if ($selectedClient || $filters['start_date'] || $filters['end_date'])
				<div class="alert alert-info border-0 shadow-sm d-flex align-items-center gap-3 p-3 mb-4" role="alert" style="border-radius: 12px; background-color: var(--md-sys-color-primary-container) !important; color: var(--md-sys-color-on-primary-container) !important;">
					<i class="bi bi-info-circle fs-4 text-primary"></i>
					<div>
						@if ($selectedClient)
							<div class="fw-bold fs-6">{{ __('locale.dashboard_filtered_client_title', ['client' => $selectedClient->raison_sociale]) }}</div>
							@if ($selectedClientAggregateCount)
								<div class="small opacity-90">{{ trans_choice('locale.dashboard_filtered_client_count', $selectedClientAggregateCount, ['count' => $selectedClientAggregateCount]) }}</div>
							@endif
						@endif
						@php
							$dateRangeMessages = collect();

							if ($filters['start_date'] && $filters['end_date']) {
							    $dateRangeMessages->push(__('locale.dashboard_filtered_date_range', ['start' => $filters['start_date'], 'end' => $filters['end_date']]));
							} elseif ($filters['start_date']) {
							    $dateRangeMessages->push(__('locale.dashboard_filtered_start_date', ['date' => $filters['start_date']]));
							} elseif ($filters['end_date']) {
							    $dateRangeMessages->push(__('locale.dashboard_filtered_end_date', ['date' => $filters['end_date']]));
							}
						@endphp
						@foreach ($dateRangeMessages as $message)
							<div class="small mb-0 opacity-90">{{ $message }}</div>
						@endforeach
					</div>
				</div>
			@endif
			@php
				$sections = [
				    [
				        'title' => __('locale.dashboard_tickets_by_status'),
				        'summary' => $ticketSummary,
				    ],
				    [
				        'title' => __('locale.dashboard_interventions_by_status'),
				        'summary' => $interventionSummary,
				    ],
				    [
				        'title' => __('locale.dashboard_repairs_by_status'),
				        'summary' => $repairSummary,
				    ],
				];
			@endphp

			<!-- Premium Top Metrics Grid -->
			<div class="row g-4 mb-4">
				@if ($canViewKpis)
					<div class="col-xxl-4 col-md-6">
						<div class="card h-100 border-0">
							<div class="card-body p-4">
								<div class="d-flex justify-content-between align-items-start">
									<div class="d-flex align-items-center gap-3">
										<div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 48px; height: 48px; background-color: rgba(79, 70, 229, 0.1); color: var(--md-sys-color-primary);">
											<i class="bi bi-wallet2 fs-4"></i>
										</div>
										<div>
											<h5 class="mb-0 fw-bold" style="font-size: 1.05rem; color: var(--md-sys-color-on-surface);">{{ __('locale.dashboard_kpi_revenue_title') }}</h5>
											<p class="text-muted small mb-0">{{ __('locale.dashboard_kpi_revenue_subtitle') }}</p>
										</div>
									</div>
								</div>
								<div class="mt-4">
									<span class="display-5 fw-bold text-slate-900 d-block mb-1">
										{{ format_number($revenueSummary['amount'], 2) }} <span class="fs-4 fw-medium text-muted">{{ __('locale.currency') }}</span>
									</span>
									<p class="text-muted small mb-0">
										{{ __('locale.dashboard_kpi_revenue_amount', [
										    'amount' => format_number($revenueSummary['amount'], 2),
										    'currency' => __('locale.currency'),
										]) }}
									</p>
								</div>
							</div>
						</div>
					</div>
				@endif

				<div class="col-xxl-4 col-md-6">
					<div class="card h-100 border-0">
						<div class="card-body p-4">
							<div class="d-flex justify-content-between align-items-start">
								<div class="d-flex align-items-center gap-3">
									<div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 48px; height: 48px; background-color: rgba(13, 148, 136, 0.1); color: var(--md-sys-color-secondary);">
										<i class="bi bi-file-earmark-text fs-4"></i>
									</div>
									<div>
										<h5 class="mb-0 fw-bold" style="font-size: 1.05rem; color: var(--md-sys-color-on-surface);">{{ __('locale.dashboard_devis_by_reference') }}</h5>
										<p class="text-muted small mb-0">
											{{ trans_choice('locale.dashboard_total_items', $devisSummary['total'], ['count' => $devisSummary['total']]) }}
										</p>
									</div>
								</div>
								<span class="fs-2 fw-bold text-slate-900">{{ $devisSummary['total'] }}</span>
							</div>

							<div class="mt-4">
								<span class="fs-3 fw-bold text-slate-800 d-block mb-3">
									{{ format_number($devisSummary['amount'], 2) }} <span class="fs-6 fw-medium text-muted">{{ __('locale.currency') }}</span>
								</span>
								@if ($devisSummary['total'] === 0)
									<!-- Premium Empty State -->
									<div class="text-center py-4 bg-light rounded-3" style="background-color: var(--md-sys-color-surface-variant) !important;">
										<div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle mb-2 shadow-sm" style="width: 40px; height: 40px; color: var(--md-sys-color-outline);">
											<i class="bi bi-folder2-open fs-5"></i>
										</div>
										<h6 class="text-muted small fw-semibold mb-0">{{ __('locale.dashboard_no_records') }}</h6>
									</div>
								@else
									@php
										$nsPercentage = $devisSummary['total'] > 0 ? round(($devisSummary['ns'] / $devisSummary['total']) * 100) : 0;
										$noPercentage = $devisSummary['total'] > 0 ? round(($devisSummary['no'] / $devisSummary['total']) * 100) : 0;
									@endphp
									<div class="row g-3 mt-1">
										<div class="col-sm-6">
											<h6 class="text-muted small fw-bold mb-1">{{ __('locale.dashboard_devis_ns_label') }}</h6>
											<span class="fs-4 fw-bold text-slate-800 d-block">{{ $devisSummary['ns'] }}</span>
											<div class="d-flex align-items-center gap-2">
												<span class="badge badge-matte-success" style="font-size: 0.7rem;">{{ $nsPercentage }}%</span>
												<small class="text-muted" style="font-size: 0.72rem;">{{ format_number($devisSummary['ns_amount'], 2) }} {{ __('locale.currency') }}</small>
											</div>
										</div>
										<div class="col-sm-6">
											<h6 class="text-muted small fw-bold mb-1">{{ __('locale.dashboard_devis_no_label') }}</h6>
											<span class="fs-4 fw-bold text-slate-800 d-block">{{ $devisSummary['no'] }}</span>
											<div class="d-flex align-items-center gap-2">
												<span class="badge badge-matte-warning" style="font-size: 0.7rem;">{{ $noPercentage }}%</span>
												<small class="text-muted" style="font-size: 0.72rem;">{{ format_number($devisSummary['no_amount'], 2) }} {{ __('locale.currency') }}</small>
											</div>
										</div>
									</div>
									<div class="progress mt-4" style="height: 6px; border-radius: 999px; background-color: var(--md-sys-color-outline-variant);">
										<div class="progress-bar" role="progressbar" style="width: {{ $nsPercentage }}%; background-color: var(--md-sys-color-secondary) !important;"
											aria-valuenow="{{ $nsPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
										<div class="progress-bar bg-warning" role="progressbar"
											style="width: {{ $noPercentage }}%;" aria-valuenow="{{ $noPercentage }}"
											aria-valuemin="0" aria-valuemax="100"></div>
									</div>
									<p class="text-muted small mb-0 mt-3">{{ __('locale.dashboard_devis_split_help') }}</p>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Premium Chart & Metrics Section -->
			<div class="row g-4">
				@foreach ($sections as $section)
					@php
						$icon = 'bi-folder2';
						$iconBg = 'rgba(79, 70, 229, 0.1)';
						$iconColor = 'var(--md-sys-color-primary)';
						if ($loop->index === 0) {
							$icon = 'bi-ticket-perforated';
							$iconBg = 'rgba(139, 92, 246, 0.1)';
							$iconColor = 'var(--md-sys-color-tertiary)';
						} elseif ($loop->index === 1) {
							$icon = 'bi-tools';
							$iconBg = 'rgba(79, 70, 229, 0.1)';
							$iconColor = 'var(--md-sys-color-primary)';
						} elseif ($loop->index === 2) {
							$icon = 'bi-wrench';
							$iconBg = 'rgba(245, 158, 11, 0.1)';
							$iconColor = '#d97706';
						}
					@endphp
					<div class="col-xxl-4 col-md-6">
						<div class="card h-100 border-0">
							<div class="card-body p-4">
								<div class="d-flex justify-content-between align-items-start mb-4">
									<div class="d-flex align-items-center gap-3">
										<div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 48px; height: 48px; background-color: {{ $iconBg }}; color: {{ $iconColor }};">
											<i class="bi {{ $icon }} fs-4"></i>
										</div>
										<div>
											<h5 class="mb-0 fw-bold" style="font-size: 1.05rem; color: var(--md-sys-color-on-surface);">{{ $section['title'] }}</h5>
											<p class="text-muted small mb-0">
												{{ trans_choice('locale.dashboard_total_items', $section['summary']['total'], ['count' => $section['summary']['total']]) }}
											</p>
										</div>
									</div>
									<span class="fs-2 fw-bold text-slate-900">{{ $section['summary']['total'] }}</span>
								</div>

								@if ($section['summary']['total'] === 0)
									<!-- Premium Empty State -->
									<div class="text-center py-5 px-3">
										<div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3 shadow-sm" style="width: 56px; height: 56px; color: var(--md-sys-color-outline); background-color: var(--md-sys-color-surface-variant) !important;">
											<i class="bi bi-folder2-open fs-3"></i>
										</div>
										<h6 class="fw-semibold text-muted mb-1">{{ __('locale.dashboard_no_records') }}</h6>
										<p class="text-muted small mb-0">Aucune donnée n'est disponible pour la période ou le client sélectionné.</p>
									</div>
								@else
									@php
										$chartId = 'dashboard-status-chart-' . $loop->index;
										$statuses = collect($section['summary']['statuses'])->sortByDesc(fn($status) => $status['percentage'] ?? 0)->values();

										$maxPercentage = $statuses->max('percentage') ?? 0;

										$enrichedStatuses = $statuses->map(function ($status) {
										    $status['is_active'] = ($status['count'] ?? 0) > 0;

										    return $status;
										});

										if (!$enrichedStatuses->contains(fn($status) => $status['is_active'])) {
										    $enrichedStatuses = $enrichedStatuses->map(function ($status, $index) {
										        $status['is_active'] = $index === 0;

										        return $status;
										    });
										}

										$chartData = [
										    'statuses' => $enrichedStatuses
										        ->map(
										            fn($status) => [
										                'id' => $status['id'],
										                'label' => $status['name'],
										                'value' => (float) $status['count'],
										                'color' => $status['color'],
										                'percentage' => $status['percentage'],
										                'textColor' => $status['text_color'],
										                'active' => $status['is_active'],
										            ],
										        )
										        ->values()
										        ->all(),
										];

										$displayStatuses = $enrichedStatuses->values()->all();
										$activeTotal = $enrichedStatuses->filter(fn($status) => $status['is_active'])->sum('count');
									@endphp

									<div class="mb-4 dashboard-chart-wrapper" data-chart-wrapper="{{ $chartId }}"
										data-active-text="{{ __('locale.dashboard_status_active') }}"
										data-inactive-text="{{ __('locale.dashboard_status_inactive') }}">
										<div id="{{ $chartId }}" class="mx-auto dashboard-chart"
											style="min-width: 300px; min-height: 300px; width: 100%; max-width: 360px; height: 320px;"
											data-dashboard-chart='@json($chartData)'></div>
									</div>
									<p class="text-muted fw-bold small mb-1" data-status-active-total
										data-chart-id="{{ $chartId }}"
										data-total-template="{{ __('locale.dashboard_active_total_template') }}">
										{{ __('locale.dashboard_active_total_template', ['count' => $activeTotal]) }}
									</p>
									<p class="text-muted small mb-4">{{ __('locale.dashboard_status_selection_help') }}</p>

									<ul class="list-unstyled mb-0">
										@foreach ($displayStatuses as $status)
											@php
												$isActive = $status['is_active'];
												$statusToggleId = $chartId . '-toggle-' . $status['id'];
											@endphp
											<li class="mb-3 dashboard-status-row {{ $isActive ? '' : 'opacity-50' }}"
												style="transition: var(--premium-transition-fast);"
												data-chart-status-row data-chart-id="{{ $chartId }}"
												data-status-id="{{ $status['id'] }}">
												<div
													class="d-flex align-items-center justify-content-between mb-2 gap-3 flex-wrap">
													<div class="d-flex align-items-center gap-3">
														<div class="form-check form-switch mb-0">
															<input class="form-check-input dashboard-status-toggle" type="checkbox"
																role="switch" id="{{ $statusToggleId }}"
																data-chart-id="{{ $chartId }}"
																data-status-id="{{ $status['id'] }}"
																aria-label="{{ __('locale.dashboard_toggle_status', ['status' => $status['name']]) }}"
																@checked($isActive)>
														</div>
														<span class="badge rounded-pill"
															style="background-color: {{ $status['color'] }}; color: {{ $status['text_color'] }};">
															{{ $status['name'] }}
														</span>
													</div>
													<div class="text-end">
														<span class="fw-bold d-block text-slate-800">{{ $status['count'] }}</span>
														<small class="text-muted" style="font-size: 0.75rem;">{{ $status['percentage'] }}%</small>
													</div>
												</div>
												<div class="progress" style="height: 6px; border-radius: 999px; background-color: var(--md-sys-color-outline-variant);">
													<div class="progress-bar" role="progressbar"
														style="width: {{ $status['percentage'] }}%; background-color: {{ $status['color'] }}; border-radius: 999px;"
														aria-valuenow="{{ $status['percentage'] }}" aria-valuemin="0"
														aria-valuemax="100"></div>
												</div>
												<div class="mt-1 d-flex justify-content-between">
													<small class="{{ $isActive ? 'text-success fw-semibold' : 'text-muted' }} d-flex align-items-center gap-1" style="font-size: 0.72rem;"
														data-status-state data-chart-id="{{ $chartId }}"
														data-status-id="{{ $status['id'] }}">
														<span class="rounded-circle d-inline-block" style="width: 6px; height: 6px; background-color: currentColor;"></span>
														{{ $isActive ? __('locale.dashboard_status_active') : __('locale.dashboard_status_inactive') }}
													</small>
												</div>
											</li>
										@endforeach
									</ul>

									@if ($section['summary']['without_status'] > 0)
										<div class="alert alert-warning border-0 mt-4 mb-0 d-flex align-items-center gap-2" role="alert" style="border-radius: 8px;">
											<i class="bi bi-exclamation-triangle-fill text-warning fs-5"></i>
											<span class="small">{{ trans_choice('locale.dashboard_without_status', $section['summary']['without_status'], ['count' => $section['summary']['without_status']]) }}</span>
										</div>
									@endif
								@endif
							</div>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</div>
@endsection

@push('scripts')
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			var clientSearchRoot = document.querySelector('[data-client-search]');

			if (clientSearchRoot) {
				var clientSearchInput = clientSearchRoot.querySelector('[data-client-search-input]');
				var clientIdInput = clientSearchRoot.querySelector('[data-client-id-input]');
				var resultsContainer = clientSearchRoot.querySelector('[data-client-search-results]');
				var searchEndpoint = clientSearchRoot.getAttribute('data-client-search-endpoint');
				var debounceTimer = null;
				var activeController = null;

				function hideClientResults() {
					if (!resultsContainer) {
						return;
					}

					resultsContainer.hidden = true;
					resultsContainer.style.display = 'none';
				}

				function clearClientResults() {
					if (!resultsContainer) {
						return;
					}

					while (resultsContainer.firstChild) {
						resultsContainer.removeChild(resultsContainer.firstChild);
					}
				}

				function renderClientResults(items) {
					if (!resultsContainer) {
						return;
					}

					clearClientResults();

					if (!Array.isArray(items) || items.length === 0) {
						hideClientResults();
						return;
					}

					var fragment = document.createDocumentFragment();

					items.forEach(function(item) {
						var button = document.createElement('button');
						button.type = 'button';
						button.className = 'list-group-item list-group-item-action';
						button.textContent = item.name || '';
						button.setAttribute('data-client-id', item.id);
						fragment.appendChild(button);
					});

					resultsContainer.appendChild(fragment);
					resultsContainer.hidden = false;
					resultsContainer.style.display = 'block';
				}

				function requestClientResults(query) {
					if (!searchEndpoint) {
						return;
					}

					if (activeController) {
						activeController.abort();
					}

					activeController = new AbortController();

					fetch(searchEndpoint + '?q=' + encodeURIComponent(query), {
							headers: {
								'Accept': 'application/json'
							},
							signal: activeController.signal
						})
						.then(function(response) {
							if (!response.ok) {
								throw new Error('Network response was not ok');
							}

							return response.json();
						})
						.then(function(payload) {
							var items = payload && Array.isArray(payload.data) ? payload.data : [];
							renderClientResults(items);
						})
						.catch(function(error) {
							if (error.name === 'AbortError') {
								return;
							}

							console.warn('Failed to fetch client search results', error);
							clearClientResults();
							hideClientResults();
						})
						.finally(function() {
							activeController = null;
						});
				}

				function scheduleClientSearch(query) {
					if (debounceTimer) {
						clearTimeout(debounceTimer);
					}

					debounceTimer = setTimeout(function() {
						requestClientResults(query);
					}, 200);
				}

				if (clientSearchInput) {
					clientSearchInput.addEventListener('input', function() {
						var query = clientSearchInput.value.trim();

						if (clientIdInput) {
							clientIdInput.value = '';
						}

						if (query.length === 0) {
							clearClientResults();
							hideClientResults();
							if (activeController) {
								activeController.abort();
								activeController = null;
							}
							return;
						}

						scheduleClientSearch(query);
					});

					clientSearchInput.addEventListener('focus', function() {
						if (resultsContainer && resultsContainer.children.length > 0) {
							resultsContainer.hidden = false;
							resultsContainer.style.display = 'block';
						}
					});

					clientSearchInput.addEventListener('keydown', function(event) {
						if (event.key === 'Escape') {
							hideClientResults();
						}
					});
				}

				if (resultsContainer) {
					resultsContainer.addEventListener('mousedown', function(event) {
						var target = event.target.closest('[data-client-id]');

						if (!target) {
							return;
						}

						event.preventDefault();

						var selectedId = target.getAttribute('data-client-id') || '';
						var selectedName = target.textContent || '';

						if (clientSearchInput) {
							clientSearchInput.value = selectedName.trim();
						}

						if (clientIdInput) {
							clientIdInput.value = selectedId;
						}

						hideClientResults();
					});
				}

				document.addEventListener('click', function(event) {
					if (!clientSearchRoot.contains(event.target)) {
						hideClientResults();
					}
				});
			}

			if (typeof ApexCharts === 'undefined') {
				return;
			}

			var chartElements = document.querySelectorAll('[data-dashboard-chart]');

			chartElements.forEach(function(element) {
				var rawConfig = element.getAttribute('data-dashboard-chart') || '{}';
				var config;

				try {
					config = JSON.parse(rawConfig);
				} catch (error) {
					console.warn('Unable to parse dashboard chart configuration', {
						error: error,
						raw: rawConfig
					});
					return;
				}

				var statuses = Array.isArray(config.statuses) ? config.statuses : [];

				if (statuses.length === 0) {
					return;
				}

				var chartId = element.getAttribute('id') || '';
				var wrapper = element.closest('[data-chart-wrapper]');
				var activeText = wrapper ? wrapper.getAttribute('data-active-text') || 'Active' : 'Active';
				var inactiveText = wrapper ? wrapper.getAttribute('data-inactive-text') || 'Inactive' : 'Inactive';

				statuses = statuses.map(function(status) {
					return {
						id: String(status.id ?? ''),
						label: status.label || status.name || '',
						value: typeof status.value === 'number' ? status.value : parseFloat(status.value) || 0,
						color: status.color || '#6c757d',
						percentage: typeof status.percentage === 'number' ?
							status.percentage : parseFloat(status.percentage) || 0,
						textColor: status.textColor || status.text_color || '#ffffff',
						active: Boolean(status.active)
					};
				});

				if (!statuses.some(function(status) {
						return status.active;
					}) && statuses.length > 0) {
					statuses[0].active = true;
				}

				var chartReady = false;
				var pendingUpdate = false;

				var chart = new ApexCharts(element, buildChartOptions(statuses.filter(function(status) {
					return status.active;
				})));

				syncStatusStates();
				updateActiveTotal();

				chart.render().then(function() {
					chartReady = true;
					syncStatusStates();

					if (pendingUpdate) {
						applyChartSelection();
					}
				});

				var toggles = document.querySelectorAll(
					'.dashboard-status-toggle[data-chart-id="' + chartId + '"]'
				);

				toggles.forEach(function(toggle) {
					toggle.addEventListener('change', function() {
						var statusId = toggle.getAttribute('data-status-id');
						var status = findStatus(statusId);

						if (!status) {
							return;
						}

						status.active = toggle.checked;

						if (!statuses.some(function(item) {
								return item.active;
							})) {
							status.active = true;
							toggle.checked = true;
							return;
						}

						applyChartSelection();
					});
				});

				function applyChartSelection() {
					var activeStatuses = statuses.filter(function(status) {
						return status.active;
					});

					if (!activeStatuses.length && statuses.length > 0) {
						statuses[0].active = true;
						activeStatuses = [statuses[0]];

						var fallbackToggle = findToggle(statuses[0].id);
						if (fallbackToggle) {
							fallbackToggle.checked = true;
						}
					}

					syncStatusStates();
					updateActiveTotal(activeStatuses);

					if (!chartReady) {
						pendingUpdate = true;
						return;
					}

					pendingUpdate = false;

					chart.updateOptions({
						labels: activeStatuses.map(function(status) {
							return status.label;
						}),
						colors: activeStatuses.map(function(status) {
							return status.color;
						})
					}, false, false);

					chart.updateSeries(activeStatuses.map(function(status) {
						return status.value;
					}));
				}

				function syncStatusStates() {
					statuses.forEach(function(status) {
						var row = document.querySelector(
							'[data-chart-status-row][data-chart-id="' + chartId + '"][data-status-id="' + status.id + '"]'
						);

						if (row) {
							row.classList.toggle('opacity-50', !status.active);
						}

						var state = document.querySelector(
							'[data-status-state][data-chart-id="' + chartId + '"][data-status-id="' + status.id + '"]'
						);

						if (state) {
							state.textContent = status.active ? activeText : inactiveText;
							state.classList.toggle('text-success', status.active);
							state.classList.toggle('text-muted', !status.active);
						}
					});
				}

				function updateActiveTotal(activeStatuses) {
					if (!Array.isArray(activeStatuses)) {
						activeStatuses = statuses.filter(function(status) {
							return status.active;
						});
					}

					var total = activeStatuses.reduce(function(sum, status) {
						var numericValue = typeof status.value === 'number' ?
							status.value :
							parseFloat(status.value) || 0;

						return sum + numericValue;
					}, 0);

					var totalElement = document.querySelector(
						'[data-status-active-total][data-chart-id="' + chartId + '"]'
					);

					if (totalElement) {
						var template = totalElement.getAttribute('data-total-template') || '';
						var formatted = template.replace(':count', total);
						totalElement.textContent = formatted;
					}
				}

				function findStatus(statusId) {
					return statuses.find(function(status) {
						return String(status.id) === String(statusId);
					});
				}

				function findToggle(statusId) {
					return document.querySelector(
						'.dashboard-status-toggle[data-chart-id="' + chartId + '"][data-status-id="' + statusId + '"]'
					);
				}

				function buildChartOptions(activeStatuses) {
					if (!activeStatuses.length && statuses.length > 0) {
						statuses[0].active = true;
						activeStatuses = [statuses[0]];

						var fallbackToggle = findToggle(statuses[0].id);
						if (fallbackToggle) {
							fallbackToggle.checked = true;
						}
					}

					return {
						chart: {
							type: 'pie',
							height: 320
						},
						labels: activeStatuses.map(function(status) {
							return status.label;
						}),
						series: activeStatuses.map(function(status) {
							return status.value;
						}),
						colors: activeStatuses.map(function(status) {
							return status.color;
						}),
						legend: {
							position: 'bottom'
						},
						dataLabels: {
							enabled: true,
							formatter: function(_, opts) {
								var series = opts.w.config.series || [];
								var count = series[opts.seriesIndex] || 0;
								var total = series.reduce(function(sum, value) {
									var numericValue = parseFloat(value) || 0;
									return sum + numericValue;
								}, 0);
								var percentage = total > 0 ? Math.round((count / total) * 100) : 0;

								return count + ' (' + percentage + '%)';
							}
						},
						tooltip: {
							y: {
								formatter: function(value, opts) {
									var series = opts.w.config.series || [];
									var total = series.reduce(function(sum, current) {
										var numericValue = parseFloat(current) || 0;
										return sum + numericValue;
									}, 0);
									var percentage = total > 0 ? Math.round((value / total) * 100) : 0;

									return value + ' (' + percentage + '%)';
								}
							}
						}
					};
				}

			});
		});
	</script>
@endpush
