@php
	$port = $port ?? [];
@endphp
<div class="col-12" data-item>
	<div class="p-4 rounded-3 border bg-light bg-opacity-25 position-relative mb-3">
		<button type="button" class="btn btn-outline-danger btn-sm rounded-pill position-absolute top-0 end-0 m-3" data-remove-item>
			<i class="bi bi-trash-fill"></i>
		</button>
		<div class="row g-3">
			<div class="col-md-3">
				<label class="form-label small fw-bold text-uppercase text-muted">{{ __('locale.orders.fields.code') }}</label>
				<input type="text" name="ports[{{ $index }}][code_port]" class="form-control" value="{{ $port['code_port'] ?? '' }}">
			</div>
			<div class="col-md-3">
				<label class="form-label small fw-bold text-uppercase text-muted">{{ __('locale.orders.fields.label') }}</label>
				<input type="text" name="ports[{{ $index }}][libelle]" class="form-control" value="{{ $port['libelle'] ?? '' }}">
			</div>
			<div class="col-md-3">
				<label class="form-label small fw-bold text-uppercase text-muted">{{ __('locale.orders.fields.minimum_amount') }}</label>
				<input type="number" step="0.01" name="ports[{{ $index }}][montant_mini]" class="form-control fw-bold text-primary" value="{{ $port['montant_mini'] ?? '' }}">
			</div>
			<div class="col-md-3">
				<label class="form-label small fw-bold text-uppercase text-muted">{{ __('locale.orders.fields.type') }}</label>
				<input type="text" name="ports[{{ $index }}][type_port]" class="form-control" value="{{ $port['type_port'] ?? '' }}">
			</div>
			<div class="col-md-12 form-check ms-3">
				<input class="form-check-input" type="checkbox" value="1" id="ports_{{ $index }}_facturable" name="ports[{{ $index }}][facturable]" @checked(($port['facturable'] ?? false) == 1)>
				<label class="form-check-label small fw-bold text-uppercase text-muted" for="ports_{{ $index }}_facturable">
					{{ __('locale.orders.fields.billable') }}
				</label>
			</div>
		</div>
	</div>
</div>
