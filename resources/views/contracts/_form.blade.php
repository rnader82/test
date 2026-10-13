@php
	/**
	 * @var \App\Models\Contract|null $contract
	 * @var \Illuminate\Support\Collection<int, \App\Models\Client> $clients
	 * @var \Illuminate\Support\Collection<int, \App\Models\ContractTemplate> $templates
	 * @var \Illuminate\Support\Collection<int, \App\Models\ContractClauseTemplate> $clauseTemplates
	 * @var \Illuminate\Support\Collection<int, \App\Models\EquipmentType> $equipmentTypes
	 */
	$isEditing = isset($contract);
	$cancelUrl = $isEditing ? route('contracts.show', $contract) : route('contracts.index');
	$submitLabel = $isEditing ? __('locale.btn_update') : __('locale.btn_create');
@endphp

<div class="row g-3 mb-3">
	<div class="col-md-6">
		<label class="form-label" for="client_id">{{ __('locale.contracts.client') }} <span class="text-danger">*</span></label>
		<select name="client_id" id="client_id" class="form-control select2" @if (isset($contract)) disabled @endif required>
			<option value="">{{ __('locale.contracts.select_client') }}</option>
			@foreach ($clients as $client)
				<option value="{{ $client->id }}" @selected(old('client_id', request('client_id', $contract->client_id ?? '')) == $client->id)>{{ $client->raison_sociale }}</option>
			@endforeach
		</select>
		@if (isset($contract))
			<input type="hidden" name="client_id" value="{{ $contract->client_id }}">
		@endif
	</div>
	<div class="col-md-6">
		<label class="form-label" for="company_id">{{ __('locale.contracts.company') }}</label>
		<select name="company_id" id="company_id" class="form-control select2">
			<option value="">{{ __('locale.contracts.select_company') }}</option>
			@foreach ($companies ?? \App\Models\Company::orderBy('name', 'asc')->get() as $comp)
				<option value="{{ $comp->id }}" @selected(old('company_id', $contract->company_id ?? '') == $comp->id)>{{ $comp->name }}</option>
			@endforeach
		</select>
	</div>
</div>

<div class="row g-3 mb-3">
	<div class="col-md-4">
		<label class="form-label" for="contract_number">{{ __('locale.contracts.contract_number') }}</label>
		<input type="text" class="form-control font-monospace" id="contract_number" name="contract_number" value="{{ old('contract_number', $contract->contract_number ?? '') }}" placeholder="Ex: S00123">
	</div>
	<div class="col-md-4">
		<label class="form-label" for="contract_type_id">{{ __('locale.contracts.type') }}</label>
		<select name="contract_type_id" id="contract_type_id" class="form-control select2">
			<option value="">{{ __('locale.contracts.select_type') }}</option>
			@foreach ($contractTypes as $type)
				<option value="{{ $type->id }}" @selected(old('contract_type_id', $contract->contract_type_id ?? '') == $type->id)>{{ $type->translated_name }}</option>
			@endforeach
		</select>
	</div>
	<div class="col-md-4">
		<label class="form-label" for="contract_template_id">{{ __('locale.contracts.template') }}</label>
		<select name="contract_template_id" id="contract_template_id" class="form-control select2">
			<option value="">{{ __('locale.contracts.no_template') }}</option>
			@foreach ($templates as $template)
				<option value="{{ $template->id }}" @selected(old('contract_template_id', $contract->contract_template_id ?? '') == $template->id)>{{ $template->contract_name }}</option>
			@endforeach
		</select>
	</div>
</div>

<ul class="nav nav-tabs mb-3" id="contractTabs" role="tablist">
	<li class="nav-item" role="presentation">
		<button class="nav-link active" id="terms-tab" data-bs-toggle="tab" data-bs-target="#terms" type="button" role="tab">{{ __('locale.contracts.tab_terms') }}</button>
	</li>
	<li class="nav-item" role="presentation">
		<button class="nav-link" id="gti-tab" data-bs-toggle="tab" data-bs-target="#gti" type="button" role="tab">{{ __('locale.contracts.tab_gti') }}</button>
	</li>
</ul>
<div class="tab-content" id="contractTabsContent">
	<div class="tab-pane fade show active" id="terms" role="tabpanel" aria-labelledby="terms-tab">
		<div class="row g-4 align-items-start">
			<div class="col-lg-6">
				<div class="card shadow-sm h-100">
					<div class="card-header bg-light fw-semibold">{{ __('locale.contracts.details') }}</div>
					<div class="card-body">
						<div class="form-check form-switch mb-4">
							<input class="form-check-input" type="checkbox" id="is_personalized" name="is_personalized" value="1" @checked(old('is_personalized', $contract->is_personalized ?? false))>
							<label class="form-check-label" for="is_personalized">{{ __('locale.contracts.personalized') }}</label>
						</div>

						<div class="mb-3">
							<label class="form-label" for="contract_name">{{ __('locale.contracts.terms.contract_name') }}</label>
							<input type="text" class="form-control" id="contract_name" name="contract_name" value="{{ old('contract_name', $contract->contract_name ?? '') }}" maxlength="255">
						</div>

						<div class="row g-3 mb-3">
							<div class="col-md-6">
								<label class="form-label" for="amount_untaxed">{{ __('locale.contracts.recurring_amount_ht') }}</label>
								<div class="input-group">
									<input type="number" step="0.01" class="form-control font-monospace" id="amount_untaxed" name="amount_untaxed" value="{{ old('amount_untaxed', $contract->amount_untaxed ?? '') }}">
									<span class="input-group-text">{{ $contract->currency ?? '€' }}</span>
								</div>
							</div>
							<div class="col-md-6">
								<label class="form-label" for="amount_total">{{ __('locale.contracts.amount_total') }}</label>
								<div class="input-group">
									<input type="number" step="0.01" class="form-control font-monospace" id="amount_total" name="amount_total" value="{{ old('amount_total', $contract->amount_total ?? '') }}">
									<span class="input-group-text">{{ $contract->currency ?? '€' }}</span>
								</div>
							</div>
						</div>

						<div class="mb-3">
							<label class="form-label d-block">{{ __('locale.contracts.terms.status') }}</label>
							@if (isset($contract))
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
									<span class="badge bg-light text-muted border ms-2">{{ __('locale.contracts.odoo_state') }}: {{ $contract->subscription_state_label }}</span>
								@endif
							@else
								<span class="badge bg-secondary">N/A</span>
							@endif
						</div>

						<div class="form-check form-switch mb-4">
							<input class="form-check-input" type="checkbox" id="is_resilie" name="is_resilie" value="1" @checked(old('is_resilie', ($contract->status ?? '') == App\Enums\ContractStatus::ContratResilie->value))>
							<label class="form-check-label" for="is_resilie">{{ __('locale.contract_status.contrat_resilie') }}</label>
						</div>

						<div class="row g-3 align-items-end mb-3">
							<div class="col-md-7">
								<label class="form-label" for="nb_vps_per_year">{{ __('locale.contracts.terms.nb_vps_per_year') }}</label>
								<input type="number" class="form-control" id="nb_vps_per_year" name="nb_vps_per_year" value="{{ old('nb_vps_per_year', $contract->nb_vps_per_year ?? '') }}">
							</div>
							<div class="col-md-5">
								<div class="form-check form-switch">
									<input class="form-check-input" type="checkbox" id="astreinte" name="astreinte" value="1" @checked(old('astreinte', $contract->astreinte ?? false))>
									<label class="form-check-label" for="astreinte">{{ __('locale.contracts.terms.astreinte') }}</label>
								</div>
							</div>
						</div>

						<div class="row g-3 mb-3">
							<div class="col-md-4">
								<label class="form-label" for="date_debut_validite">{{ __('locale.contracts.terms.date_start') }}</label>
								<input type="date" class="form-control" id="date_debut_validite" name="date_debut_validite"
									value="{{ old('date_debut_validite', optional($contract->date_debut_validite ?? null)->format('Y-m-d')) }}">
							</div>
							<div class="col-md-4">
								<label class="form-label" for="date_fin_validite">{{ __('locale.contracts.terms.date_end') }}</label>
								<input type="date" class="form-control" id="date_fin_validite" name="date_fin_validite"
									value="{{ old('date_fin_validite', optional($contract->date_fin_validite ?? null)->format('Y-m-d')) }}">
							</div>
							<div class="col-md-4">
								<label class="form-label" for="next_invoice_date">{{ __('locale.contracts.next_invoice_date') }}</label>
								<input type="date" class="form-control" id="next_invoice_date" name="next_invoice_date"
									value="{{ old('next_invoice_date', optional($contract->next_invoice_date ?? null)->format('Y-m-d')) }}">
							</div>
						</div>

						<div class="row g-3 align-items-end mb-3">
							<div class="col-md-4">
								<div class="form-check form-switch">
									<input class="form-check-input" type="checkbox" id="tacite_reconduction" name="tacite_reconduction" value="1" @checked(old('tacite_reconduction', $contract->tacite_reconduction ?? false))>
									<label class="form-check-label" for="tacite_reconduction">{{ __('locale.contracts.terms.tacite_reconduction') }}</label>
								</div>
							</div>
							<div class="col-md-4">
								<label class="form-label" for="duree_renouvellement_en_mois">{{ __('locale.contracts.terms.renewal_duration_months') }}</label>
								<input type="number" class="form-control" id="duree_renouvellement_en_mois" name="duree_renouvellement_en_mois"
									value="{{ old('duree_renouvellement_en_mois', $contract->duree_renouvellement_en_mois ?? '') }}">
							</div>
							<div class="col-md-4">
								<label class="form-label" for="nb_de_recurrences">{{ __('locale.contracts.terms.recurrence_count') }}</label>
								<input type="number" class="form-control" id="nb_de_recurrences" name="nb_de_recurrences" value="{{ old('nb_de_recurrences', $contract->nb_de_recurrences ?? -1) }}">
							</div>
						</div>

						<div class="mb-3">
							<label class="form-label" for="preavis_en_mois">{{ __('locale.contracts.terms.notice_months') }}</label>
							<input type="number" class="form-control" id="preavis_en_mois" name="preavis_en_mois" value="{{ old('preavis_en_mois', $contract->preavis_en_mois ?? '') }}">
						</div>

						<div class="mb-3">
							<label class="form-label" for="divers">{{ __('locale.contracts.terms.divers') }}</label>
							<textarea class="form-control" id="divers" name="divers" rows="3">{{ old('divers', $contract->divers ?? '') }}</textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-6 d-flex flex-column gap-4">
				<div class="card shadow-sm">
					<div class="card-header bg-light fw-semibold">{{ __('locale.contracts.documents') }}</div>
					<div class="card-body">
						@if (isset($contract) && $contract->documents->isNotEmpty())
							<ul class="list-group mb-3">
								@foreach ($contract->documents as $document)
									<li class="list-group-item d-flex justify-content-between align-items-center">
										{{ $document->document_name }}
										<div class="form-check">
											<input class="form-check-input" type="checkbox" name="remove_documents[]" value="{{ $document->id }}" id="remove_document_{{ $document->id }}">
											<label class="form-check-label" for="remove_document_{{ $document->id }}">{{ __('locale.contracts.remove') }}</label>
										</div>
									</li>
								@endforeach
							</ul>
						@endif
						<input type="file" name="documents[]" multiple class="form-control">
					</div>
				</div>
				<div class="card shadow-sm h-100">
					<div class="card-header bg-light fw-semibold d-flex justify-content-between align-items-center">
						<span>{{ __('locale.contracts.preset_clauses') }}</span>
						<button type="button" class="btn btn-sm btn-primary" onclick="addPresetClauseRow()">{{ __('locale.contracts.add_clause') }}</button>
					</div>
					<div class="card-body">
						<div id="presetClausesContainer">
							@php
								$presetClauses = old('preset_clauses', isset($contract) ? $contract->presetClauses->toArray() : []);
							@endphp
							@foreach ($presetClauses as $index => $clause)
								<div class="row g-2 mb-2 preset-clause-row">
									<div class="col-md-11">
										<select name="preset_clauses[{{ $index }}][contract_clause_template_id]" class="form-control">
											<option value="">{{ __('locale.contracts.select_clause_template') }}</option>
											@foreach ($clauseTemplates as $template)
												<option value="{{ $template->id }}" @selected(($clause['contract_clause_template_id'] ?? null) == $template->id)>{{ $template->title }}</option>
											@endforeach
										</select>
									</div>
									<div class="col-md-1 d-flex align-items-center">
										<button type="button" class="btn btn-outline-danger" onclick="removeRow(this)">X</button>
									</div>
								</div>
							@endforeach
						</div>
					</div>
				</div>
				<div class="card shadow-sm h-100">
					<div class="card-header bg-light fw-semibold d-flex justify-content-between align-items-center">
						<span>{{ __('locale.contracts.custom_clauses') }}</span>
						<button type="button" class="btn btn-sm btn-primary" onclick="addCustomClauseRow()">{{ __('locale.contracts.add_custom_clause') }}</button>
					</div>
					<div class="card-body">
						<div id="customClausesContainer">
							@php
								$customClauses = old('custom_clauses', isset($contract) ? $contract->customClauses->toArray() : []);
							@endphp
							@foreach ($customClauses as $index => $clause)
								<div class="row g-2 mb-2 custom-clause-row">
									<div class="col-md-4">
										<input type="text" name="custom_clauses[{{ $index }}][title]" class="form-control" placeholder="{{ __('locale.contracts.custom_clause_title_placeholder') }}"
											value="{{ $clause['title'] ?? '' }}">
									</div>
									<div class="col-md-7">
										<textarea name="custom_clauses[{{ $index }}][body]" class="form-control" rows="2" placeholder="{{ __('locale.contracts.custom_clause_body_placeholder') }}">{{ $clause['body'] ?? '' }}</textarea>
									</div>
									<div class="col-md-1 d-flex align-items-center">
										<button type="button" class="btn btn-outline-danger" onclick="removeRow(this)">X</button>
									</div>
								</div>
							@endforeach
						</div>
					</div>
				</div>
				<div class="card shadow-sm h-100">
					<div class="card-header bg-light fw-semibold d-flex justify-content-between align-items-center">
						<span>{{ __('locale.contracts.equipment') }}</span>
						<button type="button" class="btn btn-sm btn-primary" onclick="addEquipmentRow()">{{ __('locale.contracts.add_equipment') }}</button>
					</div>
					<div class="card-body">
						<div id="equipmentContainer">
							@php
								$equipments = old('equipment_types', isset($contract) ? $contract->equipmentTypes->toArray() : []);
							@endphp
							@foreach ($equipments as $index => $equipment)
								<div class="row g-2 mb-2 equipment-row">
									<div class="col-md-6">
										<select name="equipment_types[{{ $index }}][equipment_type_id]" class="form-control">
											<option value="">{{ __('locale.contracts.select_equipment_type') }}</option>
											@foreach ($equipmentTypes as $type)
												<option value="{{ $type->id }}" @selected(($equipment['equipment_type_id'] ?? null) == $type->id)>{{ $type->name }}</option>
											@endforeach
										</select>
									</div>
									<div class="col-md-5">
										<input type="text" name="equipment_types[{{ $index }}][comment]" class="form-control" placeholder="{{ __('locale.contracts.equipment_comment_placeholder') }}"
											value="{{ $equipment['comment'] ?? '' }}">
									</div>
									<div class="col-md-1 d-flex align-items-center">
										<button type="button" class="btn btn-outline-danger" onclick="removeRow(this)">X</button>
									</div>
								</div>
							@endforeach
						</div>
					</div>
				</div>
				<div class="d-flex justify-content-end gap-2">
					<a href="{{ $cancelUrl }}" class="btn btn-secondary">{{ __('locale.btn_cancel') }}</a>
					<button type="submit" class="btn {{ $isEditing ? 'btn-warning' : 'btn-primary' }}">{{ $submitLabel }}</button>
				</div>
			</div>
		</div>
	</div>
	<div class="tab-pane fade" id="gti" role="tabpanel" aria-labelledby="gti-tab">
		<div class="row">
			<div class="col-md-6 mb-3">
				<label class="form-label" for="panne_mineure_gti">{{ __('locale.contracts.gti.panne_mineure_gti') }}</label>
				<input type="number" class="form-control" id="panne_mineure_gti" name="panne_mineure_gti" value="{{ old('panne_mineure_gti', $contract->panne_mineure_gti ?? '') }}">
			</div>
			<div class="col-md-6 mb-3">
				<label class="form-label" for="panne_mineure_gtr">{{ __('locale.contracts.gti.panne_mineure_gtr') }}</label>
				<input type="number" class="form-control" id="panne_mineure_gtr" name="panne_mineure_gtr" value="{{ old('panne_mineure_gtr', $contract->panne_mineure_gtr ?? '') }}">
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 mb-3">
				<label class="form-label" for="panne_grave_gti">{{ __('locale.contracts.gti.panne_grave_gti') }}</label>
				<input type="number" class="form-control" id="panne_grave_gti" name="panne_grave_gti" value="{{ old('panne_grave_gti', $contract->panne_grave_gti ?? '') }}">
			</div>
			<div class="col-md-6 mb-3">
				<label class="form-label" for="panne_grave_gtr">{{ __('locale.contracts.gti.panne_grave_gtr') }}</label>
				<input type="number" class="form-control" id="panne_grave_gtr" name="panne_grave_gtr" value="{{ old('panne_grave_gtr', $contract->panne_grave_gtr ?? '') }}">
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 mb-3">
				<label class="form-label" for="panne_majeure_gti">{{ __('locale.contracts.gti.panne_majeure_gti') }}</label>
				<input type="number" class="form-control" id="panne_majeure_gti" name="panne_majeure_gti" value="{{ old('panne_majeure_gti', $contract->panne_majeure_gti ?? '') }}">
			</div>
			<div class="col-md-6 mb-3">
				<label class="form-label" for="panne_majeure_gtr">{{ __('locale.contracts.gti.panne_majeure_gtr') }}</label>
				<input type="number" class="form-control" id="panne_majeure_gtr" name="panne_majeure_gtr" value="{{ old('panne_majeure_gtr', $contract->panne_majeure_gtr ?? '') }}">
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 mb-3">
				<label class="form-label" for="formation_gti">{{ __('locale.contracts.gti.formation_gti') }}</label>
				<input type="number" class="form-control" id="formation_gti" name="formation_gti" value="{{ old('formation_gti', $contract->formation_gti ?? '') }}">
			</div>
			<div class="col-md-6 mb-3">
				<label class="form-label" for="formation_gtr">{{ __('locale.contracts.gti.formation_gtr') }}</label>
				<input type="number" class="form-control" id="formation_gtr" name="formation_gtr" value="{{ old('formation_gtr', $contract->formation_gtr ?? '') }}">
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 mb-3">
				<label class="form-label" for="sans_objet_gti">{{ __('locale.contracts.gti.sans_objet_gti') }}</label>
				<input type="number" class="form-control" id="sans_objet_gti" name="sans_objet_gti" value="{{ old('sans_objet_gti', $contract->sans_objet_gti ?? '') }}">
			</div>
			<div class="col-md-6 mb-3">
				<label class="form-label" for="sans_objet_gtr">{{ __('locale.contracts.gti.sans_objet_gtr') }}</label>
				<input type="number" class="form-control" id="sans_objet_gtr" name="sans_objet_gtr" value="{{ old('sans_objet_gtr', $contract->sans_objet_gtr ?? '') }}">
			</div>
		</div>
	</div>
</div>

<script>
	@php
		$contractTemplates = $templates
		    ->mapWithKeys(function ($template) {
		        return [
		            $template->id => [
		                'contract_name' => $template->contract_name,
		                'nb_vps_per_year' => $template->nb_vps_per_year,
		                'astreinte' => (bool) $template->astreinte,
		                'date_debut_validite' => optional($template->date_debut_validite)->format('Y-m-d'),
		                'date_fin_validite' => optional($template->date_fin_validite)->format('Y-m-d'),
		                'tacite_reconduction' => (bool) $template->tacite_reconduction,
		                'duree_renouvellement_en_mois' => $template->duree_renouvellement_en_mois,
		                'nb_de_recurrences' => $template->nb_de_recurrences,
		                'preavis_en_mois' => $template->preavis_en_mois,
		                'divers' => $template->divers,
		                'panne_mineure_gti' => $template->panne_mineure_gti,
		                'panne_mineure_gtr' => $template->panne_mineure_gtr,
		                'panne_grave_gti' => $template->panne_grave_gti,
		                'panne_grave_gtr' => $template->panne_grave_gtr,
		                'panne_majeure_gti' => $template->panne_majeure_gti,
		                'panne_majeure_gtr' => $template->panne_majeure_gtr,
		                'formation_gti' => $template->formation_gti,
		                'formation_gtr' => $template->formation_gtr,
		                'sans_objet_gti' => $template->sans_objet_gti,
		                'sans_objet_gtr' => $template->sans_objet_gtr,
		                'preset_clauses' => $template->presetClauses
		                    ->map(
		                        fn($clause) => [
		                            'contract_clause_template_id' => $clause->contract_clause_template_id,
		                        ],
		                    )
		                    ->values(),
		                'equipment_types' => $template->equipmentTypes
		                    ->map(
		                        fn($equipment) => [
		                            'equipment_type_id' => $equipment->equipment_type_id,
		                            'comment' => $equipment->comment,
		                        ],
		                    )
		                    ->values(),
		            ],
		        ];
		    })
		    ->toArray();
	@endphp

	const contractTemplates = @json($contractTemplates);

	let presetClauseIndex = {{ isset($presetClauses) ? count($presetClauses) : 0 }};
	let customClauseIndex = {{ isset($customClauses) ? count($customClauses) : 0 }};
	let equipmentIndex = {{ isset($equipments) ? count($equipments) : 0 }};

	function removeRow(button) {
		button.closest('.row').remove();
	}

	function addPresetClauseRow() {
		const wrapper = buildPresetClauseRow();
		wrapper.scrollIntoView({
			behavior: 'smooth',
			block: 'nearest'
		});
	}

	function addCustomClauseRow() {
		const container = document.getElementById('customClausesContainer');
		const wrapper = document.createElement('div');
		wrapper.classList.add('row', 'g-2', 'mb-2', 'custom-clause-row');
		wrapper.innerHTML = `
            <div class="col-md-4">
                <input type="text" name="custom_clauses[${customClauseIndex}][title]" class="form-control" placeholder="{{ __('locale.contracts.custom_clause_title_placeholder') }}">
            </div>
            <div class="col-md-7">
                <textarea name="custom_clauses[${customClauseIndex}][body]" class="form-control" rows="2" placeholder="{{ __('locale.contracts.custom_clause_body_placeholder') }}"></textarea>
            </div>
            <div class="col-md-1 d-flex align-items-center">
                <button type="button" class="btn btn-outline-danger" onclick="removeRow(this)">X</button>
            </div>
        `;
		container.appendChild(wrapper);
		wrapper.scrollIntoView({
			behavior: 'smooth',
			block: 'nearest'
		});
		customClauseIndex++;
	}

	function addEquipmentRow() {
		buildEquipmentRow();
	}

	function buildPresetClauseRow(selectedId = '') {
		const container = document.getElementById('presetClausesContainer');
		const wrapper = document.createElement('div');
		wrapper.classList.add('row', 'g-2', 'mb-2', 'preset-clause-row');
		wrapper.innerHTML = `
            <div class="col-md-11">
                <select name="preset_clauses[${presetClauseIndex}][contract_clause_template_id]" class="form-control">
                    <option value="">{{ __('locale.contracts.select_clause_template') }}</option>
                    @foreach ($clauseTemplates as $template)
                        <option value="{{ $template->id }}">{{ $template->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1 d-flex align-items-center">
                <button type="button" class="btn btn-outline-danger" onclick="removeRow(this)">X</button>
            </div>
        `;
		container.appendChild(wrapper);

		const select = wrapper.querySelector('select');
		if (select && selectedId) {
			select.value = selectedId;
		}

		presetClauseIndex++;
		return wrapper;
	}

	function buildEquipmentRow(selectedId = '', comment = '') {
		const container = document.getElementById('equipmentContainer');
		const wrapper = document.createElement('div');
		wrapper.classList.add('row', 'g-2', 'mb-2', 'equipment-row');
		wrapper.innerHTML = `
            <div class="col-md-6">
                <select name="equipment_types[${equipmentIndex}][equipment_type_id]" class="form-control">
                    <option value="">{{ __('locale.contracts.select_equipment_type') }}</option>
                    @foreach ($equipmentTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <input type="text" name="equipment_types[${equipmentIndex}][comment]" class="form-control" placeholder="{{ __('locale.contracts.equipment_comment_placeholder') }}">
            </div>
            <div class="col-md-1 d-flex align-items-center">
                <button type="button" class="btn btn-outline-danger" onclick="removeRow(this)">X</button>
            </div>
        `;
		container.appendChild(wrapper);

		const select = wrapper.querySelector('select');
		if (select && selectedId) {
			select.value = selectedId;
		}

		const commentInput = wrapper.querySelector('input[name^="equipment_types"][name$="[comment]"]');
		if (commentInput) {
			commentInput.value = comment ?? '';
		}

		equipmentIndex++;
		return wrapper;
	}

	document.getElementById('contract_template_id')?.addEventListener('change', (event) => {
		const template = contractTemplates[event.target.value];

		if (!template) {
			return;
		}

		const setValue = (id, value) => {
			const field = document.getElementById(id);
			if (field) {
				field.value = value ?? '';
			}
		};

		const setChecked = (id, value) => {
			const field = document.getElementById(id);
			if (field) {
				field.checked = Boolean(value);
			}
		};

		setValue('contract_name', template.contract_name);
		setValue('nb_vps_per_year', template.nb_vps_per_year);
		setChecked('astreinte', template.astreinte);
		setValue('date_debut_validite', template.date_debut_validite);
		setValue('date_fin_validite', template.date_fin_validite);
		setChecked('tacite_reconduction', template.tacite_reconduction);
		setValue('duree_renouvellement_en_mois', template.duree_renouvellement_en_mois);
		setValue('nb_de_recurrences', template.nb_de_recurrences);
		setValue('preavis_en_mois', template.preavis_en_mois);
		setValue('divers', template.divers);
		setValue('panne_mineure_gti', template.panne_mineure_gti);
		setValue('panne_mineure_gtr', template.panne_mineure_gtr);
		setValue('panne_grave_gti', template.panne_grave_gti);
		setValue('panne_grave_gtr', template.panne_grave_gtr);
		setValue('panne_majeure_gti', template.panne_majeure_gti);
		setValue('panne_majeure_gtr', template.panne_majeure_gtr);
		setValue('formation_gti', template.formation_gti);
		setValue('formation_gtr', template.formation_gtr);
		setValue('sans_objet_gti', template.sans_objet_gti);
		setValue('sans_objet_gtr', template.sans_objet_gtr);

		applyTemplatePresetClauses(template);
		applyTemplateEquipment(template);
	});

	function applyTemplatePresetClauses(template) {
		if (!template?.preset_clauses) {
			return;
		}

		const container = document.getElementById('presetClausesContainer');
		container.innerHTML = '';
		presetClauseIndex = 0;

		template.preset_clauses.forEach((clause) => {
			const row = buildPresetClauseRow(clause.contract_clause_template_id || '');
			row.scrollIntoView({
				behavior: 'smooth',
				block: 'nearest'
			});
		});
	}

	function applyTemplateEquipment(template) {
		if (!template?.equipment_types) {
			return;
		}

		const container = document.getElementById('equipmentContainer');
		container.innerHTML = '';
		equipmentIndex = 0;

		template.equipment_types.forEach((equipment) => {
			buildEquipmentRow(equipment.equipment_type_id || '', equipment.comment || '');
		});
	}
</script>
