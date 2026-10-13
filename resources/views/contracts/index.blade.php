@extends('layouts.app')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.contracts.title') }}</h1>
        <span class="header-count">({{ $contracts->total() }})</span>
    </div>
@endsection

@section('navbar-search')
    <form method="GET" action="{{ route('contracts.index') }}" class="w-100">
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
    <a href="{{ route('contracts.create') }}" class="btn btn-primary" title="{{ __('locale.contracts.new') }}">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.contracts.new') }}
    </a>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="table-toolbar">
            <div class="toolbar-left">
                <span class="text-muted small">
                    Résultats {{ $contracts->firstItem() ?? 0 }}-{{ $contracts->lastItem() ?? 0 }} sur {{ $contracts->total() }}
                </span>
            </div>
            <div class="toolbar-right">
            </div>
        </div>
		<table class="table table-bordered table-hover align-middle">
			<thead class="table-light">
				<tr>
					<th>{{ __('locale.lbl_reference') }}</th>
					<th>{{ __('locale.contracts.client') }}</th>
					<th>{{ __('locale.lbl_company') }}</th>
					<th>{{ __('locale.contracts.name') }}</th>
					<th>{{ __('locale.lbl_amount_ht') }}</th>
					<th>{{ __('locale.contracts.terms.date_start') }}</th>
					<th>{{ __('locale.contracts.terms.date_end') }}</th>
					<th>{{ __('locale.contracts.terms.status') }}</th>
					<th class="text-end">{{ __('locale.contracts.actions') }}</th>
				</tr>
			</thead>
			<tbody>
				@forelse ($contracts as $contract)
					<tr>
						<td>
							<div class="d-flex align-items-center gap-1">
								<a href="{{ route('contracts.show', $contract) }}" class="fw-bold font-monospace text-decoration-none text-primary">
									{{ $contract->contract_number ?: ($contract->contract_name ?: '#' . $contract->id) }}
								</a>
								@if($contract->odoo_id)
									@php
										$odooBaseUrl = rtrim(config('services.odoo.url') ?? '', '/');
										$odooLink = $odooBaseUrl ? "{$odooBaseUrl}/web#id={$contract->odoo_id}&model=sale.order&view_type=form" : null;
									@endphp
									@if($odooLink)
										<a href="{{ $odooLink }}" target="_blank" class="badge bg-light text-secondary border text-decoration-none" title="{{ __('locale.contracts.open_in_odoo') }}">
											<i class="bi bi-box-arrow-up-right me-1"></i>Odoo #{{ $contract->odoo_id }}
										</a>
									@else
										<span class="badge bg-light text-secondary border">Odoo #{{ $contract->odoo_id }}</span>
									@endif
								@endif
							</div>
						</td>
						<td>
							@if($contract->client)
								<a href="{{ route('clients.show', $contract->client) }}" class="text-decoration-none fw-semibold text-dark">
									{{ $contract->client->raison_sociale }}
								</a>
							@else
								<span class="text-muted">N/A</span>
							@endif
						</td>
						<td>
							@if($contract->company)
								<span class="badge bg-light text-dark border">{{ $contract->company->name }}</span>
							@else
								<span class="text-muted small">—</span>
							@endif
						</td>
						<td>
							<div>{{ $contract->contract_name ?? ($contract->template->contract_name ?? '—') }}</div>
							@if($contract->recurring_plan_name)
								<span class="badge bg-info-subtle text-info small">{{ $contract->recurring_plan_name }}</span>
							@endif
						</td>
						<td>
							@if($contract->amount_untaxed !== null)
								<span class="fw-bold font-monospace">{{ number_format($contract->amount_untaxed, 2, ',', ' ') }} {{ $contract->currency ?: '€' }}</span>
							@elseif($contract->amount_total !== null)
								<span class="fw-bold font-monospace">{{ number_format($contract->amount_total, 2, ',', ' ') }} {{ $contract->currency ?: '€' }}</span>
							@else
								<span class="text-muted small">—</span>
							@endif
						</td>
						<td class="small font-monospace">
							{{ $contract->date_debut_validite ? $contract->date_debut_validite->format('d/m/Y') : '—' }}
						</td>
						<td class="small font-monospace">
							{{ $contract->date_fin_validite ? $contract->date_fin_validite->format('d/m/Y') : '—' }}
						</td>
						<td>
							@php
								$statusValue = $contract->calculateStatus();
								$badgeClass = match ($statusValue) {
								    'avec_contrat' => 'badge-matte-success',
								    'contrat_expire' => 'badge-matte-danger',
								    'contrat_resilie' => 'badge-matte-warning',
								    default => 'badge-matte-secondary',
								};
							@endphp
							<span class="badge {{ $badgeClass }}">
								{{ __('locale.contract_status.' . $statusValue) }}
							</span>
							@if($contract->subscription_state)
								<span class="badge bg-light text-muted border small d-block mt-1">{{ $contract->subscription_state_label }}</span>
							@endif
						</td>
						<td class="text-end">
							<div class="dropdown">
								<button class="btn btn-sm btn-outline-secondary" type="button" id="contractActionMenu{{ $contract->id }}" data-bs-toggle="dropdown" aria-expanded="false">
									<i class="bi bi-three-dots-vertical"></i>
								</button>
								<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="contractActionMenu{{ $contract->id }}">
									<li>
										<a href="{{ route('contracts.show', $contract) }}" class="dropdown-item">
											<i class="bi bi-eye me-2"></i>{{ __('locale.view') }}
										</a>
									</li>
									<li>
										<a href="{{ route('contracts.edit', $contract) }}" class="dropdown-item">
											<i class="bi bi-pencil-square me-2"></i>{{ __('locale.edit') }}
										</a>
									</li>
									<li>
										<form action="{{ route('contracts.destroy', $contract) }}" method="POST">
											@csrf
											@method('DELETE')
											<button type="submit" class="dropdown-item text-danger" onclick="return confirm('{{ __('locale.contracts.delete_confirm') }}')">
												<i class="bi bi-trash me-2"></i>{{ __('locale.delete') }}
											</button>
										</form>
									</li>
								</ul>
							</div>
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="9" class="text-center py-4 text-muted">
							<i class="bi bi-file-earmark-x fs-2 d-block mb-2 text-muted opacity-50"></i>
							{{ __('locale.contracts.no_contracts_found') }}
						</td>
					</tr>
				@endforelse
			</tbody>
		</table>
		{{ $contracts->withQueryString()->links() }}
	</div>
</div>

@endsection
