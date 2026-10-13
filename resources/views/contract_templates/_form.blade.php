@php
	/**
	 * @var \App\Models\ContractTemplate|null $contractTemplate
	 * @var \Illuminate\Support\Collection<int, \App\Models\ContractClauseTemplate> $clauseTemplates
	 * @var \Illuminate\Support\Collection<int, \App\Models\EquipmentType> $equipmentTypes
	 */
	$isEditing = isset($contractTemplate);
	$cancelUrl = $isEditing ? route('contract_templates.index') : route('contract_templates.index');
	$submitLabel = $isEditing ? __('Update') : __('Save');
@endphp

<ul class="nav nav-tabs mb-3" id="contractTemplateTabs" role="tablist">
	<li class="nav-item" role="presentation">
		<button class="nav-link active" id="terms-tab" data-bs-toggle="tab" data-bs-target="#terms" type="button" role="tab">Terms</button>
	</li>
	<li class="nav-item" role="presentation">
		<button class="nav-link" id="gti-tab" data-bs-toggle="tab" data-bs-target="#gti" type="button" role="tab">Intervention time guarantee Delay (Délais GTI)</button>
	</li>
</ul>
<div class="tab-content" id="contractTemplateTabsContent">
	<div class="tab-pane fade show active" id="terms" role="tabpanel" aria-labelledby="terms-tab">
		<div class="row g-4 align-items-start">
			<div class="col-lg-7">
				<div class="card shadow-sm h-100">
					<div class="card-header bg-light fw-semibold">Contract template details</div>
					<div class="card-body">
						<div class="mb-3">
							<label class="form-label" for="contract_name">Contract name</label>
							<input type="text" class="form-control" id="contract_name" name="contract_name" value="{{ old('contract_name', $contractTemplate->contract_name ?? '') }}" maxlength="255" required>
						</div>

						<div class="row g-3 align-items-end mb-3">
							<div class="col-md-7">
								<label class="form-label" for="nb_vps_per_year">NB VPS per year</label>
								<input type="number" class="form-control" id="nb_vps_per_year" name="nb_vps_per_year" value="{{ old('nb_vps_per_year', $contractTemplate->nb_vps_per_year ?? '') }}" required>
							</div>
							<div class="col-md-5">
								<div class="form-check form-switch">
									<input class="form-check-input" type="checkbox" id="astreinte" name="astreinte" value="1" @checked(old('astreinte', $contractTemplate->astreinte ?? false))>
									<label class="form-check-label" for="astreinte">Astreinte</label>
								</div>
							</div>
						</div>

						<div class="row g-3 mb-3">
							<div class="col-md-6">
								<label class="form-label" for="date_debut_validite">Date début validité</label>
								<input type="date" class="form-control" id="date_debut_validite" name="date_debut_validite"
									value="{{ old('date_debut_validite', optional($contractTemplate->date_debut_validite ?? null)->format('Y-m-d')) }}">
							</div>
							<div class="col-md-6">
								<label class="form-label" for="date_fin_validite">Date fin validité</label>
								<input type="date" class="form-control" id="date_fin_validite" name="date_fin_validite"
									value="{{ old('date_fin_validite', optional($contractTemplate->date_fin_validite ?? null)->format('Y-m-d')) }}">
							</div>
						</div>

						<div class="row g-3 align-items-end mb-3">
							<div class="col-md-4">
								<div class="form-check form-switch">
									<input class="form-check-input" type="checkbox" id="tacite_reconduction" name="tacite_reconduction" value="1" @checked(old('tacite_reconduction', $contractTemplate->tacite_reconduction ?? false))>
									<label class="form-check-label" for="tacite_reconduction">Tacite reconduction</label>
								</div>
							</div>
							<div class="col-md-4">
								<label class="form-label" for="duree_renouvellement_en_mois">Durée renouvellement (mois)</label>
								<input type="number" class="form-control" id="duree_renouvellement_en_mois" name="duree_renouvellement_en_mois"
									value="{{ old('duree_renouvellement_en_mois', $contractTemplate->duree_renouvellement_en_mois ?? '') }}">
							</div>
							<div class="col-md-4">
								<label class="form-label" for="nb_de_recurrences">Nombre de récurrences (-1 illimité)</label>
								<input type="number" class="form-control" id="nb_de_recurrences" name="nb_de_recurrences" value="{{ old('nb_de_recurrences', $contractTemplate->nb_de_recurrences ?? -1) }}" required>
							</div>
						</div>

						<div class="mb-3">
							<label class="form-label" for="preavis_en_mois">Préavis (mois)</label>
							<input type="number" class="form-control" id="preavis_en_mois" name="preavis_en_mois" value="{{ old('preavis_en_mois', $contractTemplate->preavis_en_mois ?? '') }}" required>
						</div>

						<div class="mb-3">
							<label class="form-label" for="divers">Divers</label>
							<textarea class="form-control" id="divers" name="divers" rows="3">{{ old('divers', $contractTemplate->divers ?? '') }}</textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-5 d-flex flex-column gap-3">
				<div class="card shadow-sm h-100">
					<div class="card-header bg-light fw-semibold d-flex justify-content-between align-items-center">
						<span>Clauses template</span>
						<button type="button" class="btn btn-sm btn-outline-primary" onclick="addPresetClauseRow()">Add clause</button>
					</div>
					<div class="card-body">
						<div id="presetClausesContainer">
							@php
								$presetClauses = old('preset_clauses', isset($contractTemplate) ? $contractTemplate->presetClauses->toArray() : []);
							@endphp
							@foreach ($presetClauses as $index => $clause)
								<div class="row g-2 mb-2 preset-clause-row">
									<div class="col-md-11">
										<select name="preset_clauses[{{ $index }}][contract_clause_template_id]" class="form-control">
											<option value="">Select a clause template</option>
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
						<span>Equipements pris en charge</span>
						<button type="button" class="btn btn-sm btn-outline-primary" onclick="addEquipmentRow()">Add equipment</button>
					</div>
					<div class="card-body">
						<div id="equipmentContainer">
							@php
								$equipments = old('equipment_types', isset($contractTemplate) ? $contractTemplate->equipmentTypes->toArray() : []);
							@endphp
							@foreach ($equipments as $index => $equipment)
								<div class="row g-2 mb-2 equipment-row">
									<div class="col-md-6">
										<select name="equipment_types[{{ $index }}][equipment_type_id]" class="form-control">
											<option value="">Select equipment type</option>
											@foreach ($equipmentTypes as $type)
												<option value="{{ $type->id }}" @selected(($equipment['equipment_type_id'] ?? null) == $type->id)>{{ $type->name }}</option>
											@endforeach
										</select>
									</div>
									<div class="col-md-5">
										<input type="text" name="equipment_types[{{ $index }}][comment]" class="form-control" placeholder="Comment" value="{{ $equipment['comment'] ?? '' }}">
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
					<a href="{{ $cancelUrl }}" class="btn btn-secondary">Cancel</a>
					<button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
				</div>
			</div>
		</div>
	</div>

	<div class="tab-pane fade" id="gti" role="tabpanel" aria-labelledby="gti-tab">
		<div class="card shadow-sm">
			<div class="card-body">
				<div class="row">
					<div class="col-md-6 mb-3">
						<label class="form-label" for="panne_mineure_gti">Panne mineure GTI</label>
						<input type="number" class="form-control" id="panne_mineure_gti" name="panne_mineure_gti" value="{{ old('panne_mineure_gti', $contractTemplate->panne_mineure_gti ?? '') }}">
					</div>
					<div class="col-md-6 mb-3">
						<label class="form-label" for="panne_mineure_gtr">Panne mineure GTR</label>
						<input type="number" class="form-control" id="panne_mineure_gtr" name="panne_mineure_gtr" value="{{ old('panne_mineure_gtr', $contractTemplate->panne_mineure_gtr ?? '') }}">
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 mb-3">
						<label class="form-label" for="panne_grave_gti">Panne grave GTI</label>
						<input type="number" class="form-control" id="panne_grave_gti" name="panne_grave_gti" value="{{ old('panne_grave_gti', $contractTemplate->panne_grave_gti ?? '') }}">
					</div>
					<div class="col-md-6 mb-3">
						<label class="form-label" for="panne_grave_gtr">Panne grave GTR</label>
						<input type="number" class="form-control" id="panne_grave_gtr" name="panne_grave_gtr" value="{{ old('panne_grave_gtr', $contractTemplate->panne_grave_gtr ?? '') }}">
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 mb-3">
						<label class="form-label" for="panne_majeure_gti">Panne majeure GTI</label>
						<input type="number" class="form-control" id="panne_majeure_gti" name="panne_majeure_gti" value="{{ old('panne_majeure_gti', $contractTemplate->panne_majeure_gti ?? '') }}">
					</div>
					<div class="col-md-6 mb-3">
						<label class="form-label" for="panne_majeure_gtr">Panne majeure GTR</label>
						<input type="number" class="form-control" id="panne_majeure_gtr" name="panne_majeure_gtr" value="{{ old('panne_majeure_gtr', $contractTemplate->panne_majeure_gtr ?? '') }}">
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 mb-3">
						<label class="form-label" for="formation_gti">Formation GTI</label>
						<input type="number" class="form-control" id="formation_gti" name="formation_gti" value="{{ old('formation_gti', $contractTemplate->formation_gti ?? '') }}">
					</div>
					<div class="col-md-6 mb-3">
						<label class="form-label" for="formation_gtr">Formation GTR</label>
						<input type="number" class="form-control" id="formation_gtr" name="formation_gtr" value="{{ old('formation_gtr', $contractTemplate->formation_gtr ?? '') }}">
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 mb-3">
						<label class="form-label" for="sans_objet_gti">Sans objet GTI</label>
						<input type="number" class="form-control" id="sans_objet_gti" name="sans_objet_gti" value="{{ old('sans_objet_gti', $contractTemplate->sans_objet_gti ?? '') }}">
					</div>
					<div class="col-md-6 mb-3">
						<label class="form-label" for="sans_objet_gtr">Sans objet GTR</label>
						<input type="number" class="form-control" id="sans_objet_gtr" name="sans_objet_gtr" value="{{ old('sans_objet_gtr', $contractTemplate->sans_objet_gtr ?? '') }}">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@push('scripts')
	<script>
		let presetClauseIndex = {{ isset($presetClauses) ? count($presetClauses) : 0 }};
		let equipmentIndex = {{ isset($equipments) ? count($equipments) : 0 }};

		function removeRow(button) {
			button.closest('.row').remove();
		}

		function addPresetClauseRow() {
			const container = document.getElementById('presetClausesContainer');
			const wrapper = document.createElement('div');
			wrapper.classList.add('row', 'g-2', 'mb-2', 'preset-clause-row');
			wrapper.innerHTML = `
            <div class="col-md-11">
                <select name="preset_clauses[${presetClauseIndex}][contract_clause_template_id]" class="form-control">
                    <option value="">Select a clause template</option>
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
			wrapper.scrollIntoView({
				behavior: 'smooth',
				block: 'nearest'
			});
			presetClauseIndex++;
		}

		function addEquipmentRow() {
			const container = document.getElementById('equipmentContainer');
			const wrapper = document.createElement('div');
			wrapper.classList.add('row', 'g-2', 'mb-2', 'equipment-row');
			wrapper.innerHTML = `
            <div class="col-md-6">
                <select name="equipment_types[${equipmentIndex}][equipment_type_id]" class="form-control">
                    <option value="">Select equipment type</option>
                    @foreach ($equipmentTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <input type="text" name="equipment_types[${equipmentIndex}][comment]" class="form-control" placeholder="Comment">
            </div>
            <div class="col-md-1 d-flex align-items-center">
                <button type="button" class="btn btn-outline-danger" onclick="removeRow(this)">X</button>
            </div>
        `;
			container.appendChild(wrapper);
			equipmentIndex++;
		}
	</script>
@endpush
