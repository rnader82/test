@extends('layouts.app')
@section('portal-module', 'visite_prev')
@section('portal-page', 'index')
@section('portal-kind', 'list')
@section('portal-family', 'technical')
@section('portal-icon', 'screwdriver-wrench')


@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ $pageTitle ?? __('locale.visite_prev.vp_visits') }}</h1>
        <span class="header-count">({{ $vpVisits->total() }})</span>
    </div>
@endsection

@section('navbar-search')
    <form method="GET" action="{{ route('visite-prev.index') }}" class="w-100">
        <div class="search-bar-mockup">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('locale.search_placeholder') }}">
            @foreach(request()->except(['search', 'page']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </div>
    </form>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-3">
        <span class="text-muted small d-none d-md-inline">
            Résultats {{ $vpVisits->firstItem() ?? 0 }}-{{ $vpVisits->lastItem() ?? 0 }} sur {{ $vpVisits->total() }}
        </span>
        <a href="#advancedFilters" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->filled('client_id') || request()->filled('installer_id') || request()->filled('start_date') || request()->filled('end_date') ? 'true' : 'false' }}" aria-controls="advancedFilters" class="btn btn-outline-secondary btn-sm border-0">
            <i class="bi bi-filter me-1"></i> {{ __('locale.advanced_filters') }}
        </a>
        <a href="{{ route('visite-prev.create') }}" class="btn btn-primary" title="{{ __('locale.visite_prev.create_vp_visit') }}">
            <i class="bi bi-plus-lg me-1"></i> {{ __('locale.visite_prev.create_vp_visit') ?? 'Nouvelle visite' }}
        </a>
    </div>
@endsection


@section('content') 
<style>
		table.table th,
		table.table td {
			padding: 4px 6px !important;
			font-size: 1rem;
			line-height: 1.3;
			vertical-align: middle;
			text-transform: inherit;
		}

		.table-hover tbody tr:hover {
			background-color: #f5f5f5;
		}

		.clickable-row {
			cursor: pointer;
		}
	</style>
	@php $activeFilters = request()->filled('client_id') || request()->filled('installer_id') || request()->filled('start_date') || request()->filled('end_date'); @endphp
	<div class="container-xxl flex-grow-1 container-p-y">

		<div class="collapse {{ $activeFilters ? 'show' : '' }}" id="advancedFilters">
			<form method="GET" action="{{ route('visite-prev.index') }}" class="mb-4">
				<input type="hidden" name="search" value="{{ request('search') }}">
				<input type="hidden" name="sort" value="{{ request('sort', 'created_at') }}">
				<input type="hidden" name="direction" value="{{ request('direction', 'desc') }}">
				<div class="row g-3">
					<div class="col-md-6">
						<label for="client_id" class="form-label">{{ __('locale.lbl_client') }}</label>
						<select name="client_id" id="client_id" class="form-control js-vp-client-select" data-placeholder="{{ __('locale.lbl_all_clients') }}"
							data-ajax-url="{{ route('clients.search') }}">
							<option value=""></option>
							@if ($selectedClient)
								@php
	$clientLabel = $selectedClient->raison_sociale;
	if ($selectedClient->ville) {
		$clientLabel .= ' (' . $selectedClient->ville . ')';
	}
								@endphp
								<option value="{{ $selectedClient->id }}" selected>{{ $clientLabel }}</option>
							@endif
						</select>
					</div>
					<div class="col-md-6">
						<label for="installer_id" class="form-label">{{ __('locale.visite_prev.installer') }}</label>
						<select name="installer_id" id="installer_id" class="form-control select2">
							<option value="">{{ __('locale.lbl_all_installers') }}</option>
							@foreach ($installers as $installer)
								<option value="{{ $installer->id }}"
									@selected(request('installer_id') == $installer->id)>{{ $installer->name }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-6">
						<label for="start_date" class="form-label">{{ __('locale.lbl_start_date') }}</label>
						<input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
					</div>
					<div class="col-md-6">
						<label for="end_date" class="form-label">{{ __('locale.lbl_end_date') }}</label>
						<input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
					</div>
					<div class="col-12 d-flex justify-content-end">
						<button type="submit" class="btn btn-primary">{{ __('locale.btn_apply_filters') }}</button>
					</div>
				</div>
			</form>
		</div>
		@if ($vpVisits->count())
			<table class="module-table table table-bordered table-hover">
				<thead>
					<tr>
						<th>
							<a
								href="{{ route('visite-prev.index', array_merge(request()->query(), ['sort' => 'id', 'direction' => request('sort') === 'id' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
								{{ __('locale.visite_prev.id') }} @if (request('sort') === 'id')
									<i class="bi bi-caret-{{ request('direction') === 'asc' ? 'up' : 'down' }}-fill"></i>
								@endif </a>
						</th>
						<th>
							<a
								href="{{ route('visite-prev.index', array_merge(request()->query(), ['sort' => 'client', 'direction' => request('sort') === 'client' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
								{{ __('locale.visite_prev.site') }} @if (request('sort') === 'client')
									<i class="bi bi-caret-{{ request('direction') === 'asc' ? 'up' : 'down' }}-fill"></i>
								@endif </a>
						</th>
						<th>
							<a
								href="{{ route('visite-prev.index', array_merge(request()->query(), ['sort' => 'installer', 'direction' => request('sort') === 'installer' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
								{{ __('locale.visite_prev.installer') }} @if (request('sort') === 'installer')
									<i class="bi bi-caret-{{ request('direction') === 'asc' ? 'up' : 'down' }}-fill"></i>
								@endif </a>
						</th>
						<th>
							<a
								href="{{ route('visite-prev.index', array_merge(request()->query(), ['sort' => 'created_at', 'direction' => request('sort') === 'created_at' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
								{{ __('locale.visite_prev.created_at') }} @if (request('sort') === 'created_at' || request('sort') === null)
									<i
										class="bi bi-caret-{{ request('direction', 'desc') === 'asc' ? 'up' : 'down' }}-fill"></i>
								@endif </a>
						</th>
						<th>{{ __('visite_prev.status') }}</th>
						<th class="text-end">{{ __('locale.visite_prev.actions') }}</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($vpVisits as $visit)
						<tr class="clickable-row" onclick="window.location='{{ route('vp-visit-workflow.complete', $visit->id) }}'">
							<td>{{ $visit->id }}</td>
							<td>
								@if ($visit->client)
									<a href="{{ route('clients.show', $visit->client->id) }}" onclick="event.stopPropagation();"> {{ $visit->client->raison_sociale }} </a>
								@else
									<span
										class="text-muted">{{ __('locale.lbl_no_client') }}</span>
								@endif
							</td>
							<td>{{ $visit->installer->name ?? '-' }}</td>
							<td>{{ format_datetime($visit->created_at, 'short', 'short') }}</td>
							<td>
								@if ($visit->status)
									<span class="badge" style="background-color: {{ $visit->status->color }}; color: {{ $visit->status->text_color }};">
										{{ $visit->status->translated_name }}
									</span>
								@else
									—
								@endif
							</td>
							<td class="text-end">
								<div class="dropdown" onclick="event.stopPropagation();">
									<button class="btn btn-sm btn-outline-secondary" type="button" id="vpVisitActions{{ $visit->id }}" data-bs-toggle="dropdown" aria-expanded="false">
										<i class="bi bi-three-dots-vertical"></i>
									</button>
									<ul class="dropdown-menu" aria-labelledby="vpVisitActions{{ $visit->id }}">
										<li>
											<a href="{{ route('visite-prev.edit', $visit->id) }}" class="dropdown-item">
												<i class="bi bi-pencil-square me-1"></i>{{ __('locale.visite_prev.edit') }} </a>
										</li>
										<li>
											<form action="{{ route('visite-prev.destroy', $visit->id) }}" method="POST"
												onsubmit="event.stopPropagation(); return confirm('{{ __('locale.visite_prev.confirm_delete') }}');"> @csrf @method('DELETE') <button type="submit"
													class="dropdown-item text-danger">
													<i class="bi bi-trash me-1"></i>{{ __('locale.visite_prev.delete') }} </button>
											</form>
										</li>
									</ul>
								</div>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table> {{ $vpVisits->withQueryString()->links() }}
		@else
			<div class="alert alert-info">{{ __('locale.visite_prev.no_visits_found') }}</div>
		@endif
	</div> 
	</div>
</div>

@endsection 

@section('scripts')
	@parent @include('visite_prev.partials.scripts')
@endsection
