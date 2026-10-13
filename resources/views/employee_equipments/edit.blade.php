@extends('layouts.app')

@section('title', 'Modifier le matériel')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('employee_equipments.show', $employeeEquipment->id) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="h4 mb-0 fw-bold">Modifier le matériel #{{ $employeeEquipment->id }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('employee_equipments.show', $employeeEquipment->id) }}" class="btn btn-outline-secondary">
            Annuler
        </a>
        <button type="submit" form="equipmentEditForm" class="btn btn-primary">
            <i class="bi bi-check-lg me-1"></i> Enregistrer
        </button>
    </div>
@endsection

@section('content')
<div class="container-fluid px-0">
    <form action="{{ route('employee_equipments.update', $employeeEquipment->id) }}" method="POST" id="equipmentEditForm">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <!-- Left Column: Equipment Details -->
            <div class="col-12 col-lg-8">
                
                <!-- Category Selector Card -->
                <div class="card card-premium shadow-sm border-0 mb-4">
                    <div class="card-header bg-transparent border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold"><i class="bi bi-tag me-2 text-primary"></i> 1. Catégorie de matériel</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <label class="card h-100 p-3 text-center cursor-pointer type-option-card border d-flex flex-column align-items-center justify-content-center {{ old('type', $employeeEquipment->type) === 'telephone' ? 'border-primary bg-primary-subtle' : 'border-slate-200' }}" for="type_telephone">
                                    <input class="form-check-input visually-hidden" type="radio" name="type" id="type_telephone" value="telephone" {{ old('type', $employeeEquipment->type) === 'telephone' ? 'checked' : '' }} onchange="updateFormVisibility()">
                                    <div class="category-icon-wrapper d-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px; font-size: 1.75rem;">
                                        <i class="bi bi-phone text-primary"></i>
                                    </div>
                                    <span class="fw-bold">Téléphone</span>
                                    <small class="text-muted d-block mt-1">Avec IMEI</small>
                                </label>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="card h-100 p-3 text-center cursor-pointer type-option-card border d-flex flex-column align-items-center justify-content-center {{ old('type', $employeeEquipment->type) === 'ordinateur' ? 'border-primary bg-primary-subtle' : 'border-slate-200' }}" for="type_ordinateur">
                                    <input class="form-check-input visually-hidden" type="radio" name="type" id="type_ordinateur" value="ordinateur" {{ old('type', $employeeEquipment->type) === 'ordinateur' ? 'checked' : '' }} onchange="updateFormVisibility()">
                                    <div class="category-icon-wrapper d-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px; font-size: 1.75rem;">
                                        <i class="bi bi-laptop text-info"></i>
                                    </div>
                                    <span class="fw-bold">Ordinateur</span>
                                    <small class="text-muted d-block mt-1">PC / Mac / Portable</small>
                                </label>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="card h-100 p-3 text-center cursor-pointer type-option-card border d-flex flex-column align-items-center justify-content-center {{ old('type', $employeeEquipment->type) === 'voiture' ? 'border-primary bg-primary-subtle' : 'border-slate-200' }}" for="type_voiture">
                                    <input class="form-check-input visually-hidden" type="radio" name="type" id="type_voiture" value="voiture" {{ old('type', $employeeEquipment->type) === 'voiture' ? 'checked' : '' }} onchange="updateFormVisibility()">
                                    <div class="category-icon-wrapper d-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px; font-size: 1.75rem;">
                                        <i class="fa-solid fa-car text-success"></i>
                                    </div>
                                    <span class="fw-bold">Véhicule</span>
                                    <small class="text-muted d-block mt-1">Flotte automobile</small>
                                </label>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="card h-100 p-3 text-center cursor-pointer type-option-card border d-flex flex-column align-items-center justify-content-center {{ old('type', $employeeEquipment->type) === 'autre' ? 'border-primary bg-primary-subtle' : 'border-slate-200' }}" for="type_autre">
                                    <input class="form-check-input visually-hidden" type="radio" name="type" id="type_autre" value="autre" {{ old('type', $employeeEquipment->type) === 'autre' ? 'checked' : '' }} onchange="updateFormVisibility()">
                                    <div class="category-icon-wrapper d-flex align-items-center justify-content-center mb-2" style="width: 48px; height: 48px; font-size: 1.75rem;">
                                        <i class="bi bi-box text-secondary"></i>
                                    </div>
                                    <span class="fw-bold">Autre</span>
                                    <small class="text-muted d-block mt-1">Écran, Badge, etc.</small>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Asset Specifications Card -->
                <div class="card card-premium shadow-sm border-0 mb-4">
                    <div class="card-header bg-transparent border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold"><i class="bi bi-sliders me-2 text-primary"></i> 2. Caractéristiques du matériel</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <!-- Marque -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" for="brand">Marque</label>
                                <input type="text" name="brand" id="brand" class="form-control @error('brand') is-invalid @enderror" placeholder="ex: Apple, Dell, Renault..." value="{{ old('brand', $employeeEquipment->brand) }}">
                                @error('brand')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Modèle -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" for="model">Modèle</label>
                                <input type="text" name="model" id="model" class="form-control @error('model') is-invalid @enderror" placeholder="ex: iPhone 15, Latitude 5420, Clio V..." value="{{ old('model', $employeeEquipment->model) }}">
                                @error('model')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- N° de série -->
                            <div class="col-12 col-md-6" id="field_serial_number_wrapper">
                                <label class="form-label fw-semibold" for="serial_number">
                                    <span id="label_serial_number">N° de série</span>
                                </label>
                                <input type="text" name="serial_number" id="serial_number" class="form-control @error('serial_number') is-invalid @enderror" placeholder="ex: C02X1234..." value="{{ old('serial_number', $employeeEquipment->serial_number) }}">
                                @error('serial_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- IMEI (Exclusivement Téléphones) -->
                            <div class="col-12 col-md-6" id="field_imei_wrapper">
                                <label class="form-label fw-semibold text-primary" for="imei">
                                    <i class="bi bi-upc-scan me-1"></i> Numéro IMEI
                                </label>
                                <input type="text" name="imei" id="imei" class="form-control font-monospace @error('imei') is-invalid @enderror" placeholder="ex: 356938035643809" value="{{ old('imei', $employeeEquipment->imei) }}">
                                <small class="text-muted">Numéro IMEI unique du téléphone à 15 chiffres</small>
                                @error('imei')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Immatriculation (Exclusivement Voitures) -->
                            <div class="col-12 col-md-6" id="field_registration_plate_wrapper">
                                <label class="form-label fw-semibold text-success" for="registration_plate">
                                    <i class="bi bi-card-text me-1"></i> Plaque d'immatriculation
                                </label>
                                <input type="text" name="registration_plate" id="registration_plate" class="form-control font-monospace text-uppercase @error('registration_plate') is-invalid @enderror" placeholder="ex: AA-123-AA ou 1234 AB 75" value="{{ old('registration_plate', $employeeEquipment->registration_plate) }}">
                                @error('registration_plate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Type de véhicule (Voitures) -->
                            <div class="col-12 col-md-6" id="field_vehicle_type_wrapper">
                                <label class="form-label fw-semibold" for="vehicle_type">Type de véhicule</label>
                                <select name="vehicle_type" id="vehicle_type" class="form-select @error('vehicle_type') is-invalid @enderror">
                                    <option value="">-- Sélectionner le type --</option>
                                    <option value="Véhicule de fonction" {{ old('vehicle_type', $employeeEquipment->vehicle_type) === 'Véhicule de fonction' ? 'selected' : '' }}>Véhicule de fonction</option>
                                    <option value="Véhicule de service" {{ old('vehicle_type', $employeeEquipment->vehicle_type) === 'Véhicule de service' ? 'selected' : '' }}>Véhicule de service</option>
                                    <option value="Véhicule utilitaire (VU)" {{ old('vehicle_type', $employeeEquipment->vehicle_type) === 'Véhicule utilitaire (VU)' ? 'selected' : '' }}>Véhicule utilitaire (VU)</option>
                                    <option value="Véhicule particulier (VP)" {{ old('vehicle_type', $employeeEquipment->vehicle_type) === 'Véhicule particulier (VP)' ? 'selected' : '' }}>Véhicule particulier (VP)</option>
                                </select>
                                @error('vehicle_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Carburant / Énergie (Voitures) -->
                            <div class="col-12 col-md-6" id="field_fuel_type_wrapper">
                                <label class="form-label fw-semibold" for="fuel_type">Carburant / Motorisation</label>
                                <select name="fuel_type" id="fuel_type" class="form-select @error('fuel_type') is-invalid @enderror">
                                    <option value="">-- Sélectionner l'énergie --</option>
                                    <option value="Diesel" {{ old('fuel_type', $employeeEquipment->fuel_type) === 'Diesel' ? 'selected' : '' }}>Diesel</option>
                                    <option value="Essence" {{ old('fuel_type', $employeeEquipment->fuel_type) === 'Essence' ? 'selected' : '' }}>Essence</option>
                                    <option value="Hybride" {{ old('fuel_type', $employeeEquipment->fuel_type) === 'Hybride' ? 'selected' : '' }}>Hybride</option>
                                    <option value="Électrique" {{ old('fuel_type', $employeeEquipment->fuel_type) === 'Électrique' ? 'selected' : '' }}>Électrique</option>
                                    <option value="GPL" {{ old('fuel_type', $employeeEquipment->fuel_type) === 'GPL' ? 'selected' : '' }}>GPL</option>
                                </select>
                                @error('fuel_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- 1ère mise en circulation (Voitures) -->
                            <div class="col-12 col-md-6" id="field_first_registration_date_wrapper">
                                <label class="form-label fw-semibold" for="first_registration_date">1ère mise en circulation</label>
                                <input type="date" name="first_registration_date" id="first_registration_date" class="form-control @error('first_registration_date') is-invalid @enderror" value="{{ old('first_registration_date', $employeeEquipment->first_registration_date ? $employeeEquipment->first_registration_date->format('Y-m-d') : '') }}">
                                @error('first_registration_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contrôle technique (Voitures) -->
                            <div class="col-12 col-md-6" id="field_technical_inspection_date_wrapper">
                                <label class="form-label fw-semibold text-warning-emphasis" for="technical_inspection_date">
                                    <i class="bi bi-shield-check me-1"></i> Date du Contrôle Technique (CT)
                                </label>
                                <input type="date" name="technical_inspection_date" id="technical_inspection_date" class="form-control @error('technical_inspection_date') is-invalid @enderror" value="{{ old('technical_inspection_date', $employeeEquipment->technical_inspection_date ? $employeeEquipment->technical_inspection_date->format('Y-m-d') : '') }}">
                                @error('technical_inspection_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kilométrage (Voitures) -->
                            <div class="col-12 col-md-6" id="field_mileage_wrapper">
                                <label class="form-label fw-semibold" for="mileage">Kilométrage (km)</label>
                                <input type="number" name="mileage" id="mileage" min="0" class="form-control @error('mileage') is-invalid @enderror" placeholder="ex: 45000" value="{{ old('mileage', $employeeEquipment->mileage) }}">
                                @error('mileage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Carte carburant / Télépéage (Voitures) -->
                            <div class="col-12 col-md-6" id="field_fuel_card_badge_wrapper">
                                <label class="form-label fw-semibold" for="fuel_card_badge">Carte Carburant / Badge Télépéage</label>
                                <input type="text" name="fuel_card_badge" id="fuel_card_badge" class="form-control @error('fuel_card_badge') is-invalid @enderror" placeholder="ex: Total Pass #98234, Badge Liber-t" value="{{ old('fuel_card_badge', $employeeEquipment->fuel_card_badge) }}">
                                @error('fuel_card_badge')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Commentaire général -->
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="notes">Commentaire / Notes</label>
                                <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" placeholder="Observations générales sur l'état ou la configuration du matériel...">{{ old('notes', $employeeEquipment->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Assignment & Status -->
            <div class="col-12 col-lg-4">
                
                <!-- Status Card -->
                <div class="card card-premium shadow-sm border-0 mb-4">
                    <div class="card-header bg-transparent border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold"><i class="bi bi-check2-circle me-2 text-success"></i> Suivi & Statut</h5>
                    </div>
                    <div class="card-body p-4">
                        <label class="form-label fw-semibold" for="status">Statut de l'équipement</label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="en_service" {{ old('status', $employeeEquipment->status) === 'en_service' ? 'selected' : '' }}>En service (Attribué)</option>
                            <option value="disponible" {{ old('status', $employeeEquipment->status) === 'disponible' ? 'selected' : '' }}>Disponible (En stock)</option>
                            <option value="restitue" {{ old('status', $employeeEquipment->status) === 'restitue' ? 'selected' : '' }}>Restitué</option>
                            <option value="en_maintenance" {{ old('status', $employeeEquipment->status) === 'en_maintenance' ? 'selected' : '' }}>En maintenance</option>
                            <option value="hors_service" {{ old('status', $employeeEquipment->status) === 'hors_service' ? 'selected' : '' }}>Hors service</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @php
                    $current = $employeeEquipment->currentAssignment;
                @endphp

                <!-- Current Assignment Card -->
                <div class="card card-premium shadow-sm border-0 mb-4">
                    <div class="card-header bg-transparent border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold"><i class="bi bi-person-badge me-2 text-info"></i> Affectation active</h5>
                    </div>
                    <div class="card-body p-4">
                        
                        <!-- Salarié / Collaborateur -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="assign_employee_id">Salarié / Collaborateur</label>
                            <select name="assign_employee_id" id="assign_employee_id" class="form-select" onchange="onEmployeeSelect(this)">
                                <option value="">-- Sélectionner un collaborateur --</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" 
                                            data-firstname="{{ $emp->first_name }}" 
                                            data-lastname="{{ $emp->last_name }}"
                                            data-companyid="{{ $emp->company_id }}"
                                            data-establishment="{{ $emp->establishment }}"
                                            {{ old('assign_employee_id', $current?->employee_id) == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->select_label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="text-center my-2 text-muted small">— ou saisie libre —</div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-semibold" for="assign_last_name">Nom</label>
                                <input type="text" name="assign_last_name" id="assign_last_name" class="form-control form-control-sm" placeholder="Nom" value="{{ old('assign_last_name', $current?->last_name) }}">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold" for="assign_first_name">Prénom</label>
                                <input type="text" name="assign_first_name" id="assign_first_name" class="form-control form-control-sm" placeholder="Prénom" value="{{ old('assign_first_name', $current?->first_name) }}">
                            </div>
                        </div>

                        <!-- Dates d'affectation -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="assign_handover_date">Date de remise</label>
                            <input type="date" name="assign_handover_date" id="assign_handover_date" class="form-control @error('assign_handover_date') is-invalid @enderror" value="{{ old('assign_handover_date', $current?->handover_date ? $current->handover_date->format('Y-m-d') : '') }}">
                            @error('assign_handover_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="assign_return_date">Date de restitution</label>
                            <input type="date" name="assign_return_date" id="assign_return_date" class="form-control @error('assign_return_date') is-invalid @enderror" value="{{ old('assign_return_date', $current?->return_date ? $current->return_date->format('Y-m-d') : '') }}">
                            <small class="text-muted">Renseigner une date de restitution libère le matériel pour une nouvelle affectation.</small>
                            @error('assign_return_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="assign_notes">Commentaire d'affectation</label>
                            <textarea name="assign_notes" id="assign_notes" rows="2" class="form-control" placeholder="Observations sur la remise ou l'état...">{{ old('assign_notes', $current?->notes) }}</textarea>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function updateFormVisibility() {
        const type = document.querySelector('input[name="type"]:checked')?.value || 'telephone';
        
        // Highlight active radio card
        document.querySelectorAll('.type-option-card').forEach(card => {
            card.classList.remove('border-primary', 'bg-primary-subtle');
            card.classList.add('border-slate-200');
        });
        const selectedRadio = document.querySelector(`input[name="type"][value="${type}"]`);
        if (selectedRadio) {
            const card = selectedRadio.closest('.type-option-card');
            if (card) {
                card.classList.add('border-primary', 'bg-primary-subtle');
                card.classList.remove('border-slate-200');
            }
        }

        // Element Wrappers
        const imeiWrapper = document.getElementById('field_imei_wrapper');
        const plateWrapper = document.getElementById('field_registration_plate_wrapper');
        const vehicleTypeWrapper = document.getElementById('field_vehicle_type_wrapper');
        const fuelTypeWrapper = document.getElementById('field_fuel_type_wrapper');
        const firstRegWrapper = document.getElementById('field_first_registration_date_wrapper');
        const ctWrapper = document.getElementById('field_technical_inspection_date_wrapper');
        const mileageWrapper = document.getElementById('field_mileage_wrapper');
        const fuelCardWrapper = document.getElementById('field_fuel_card_badge_wrapper');
        const serialNumberLabel = document.getElementById('label_serial_number');

        if (type === 'telephone') {
            if (imeiWrapper) imeiWrapper.style.display = 'block';
            if (plateWrapper) plateWrapper.style.display = 'none';
            if (vehicleTypeWrapper) vehicleTypeWrapper.style.display = 'none';
            if (fuelTypeWrapper) fuelTypeWrapper.style.display = 'none';
            if (firstRegWrapper) firstRegWrapper.style.display = 'none';
            if (ctWrapper) ctWrapper.style.display = 'none';
            if (mileageWrapper) mileageWrapper.style.display = 'none';
            if (fuelCardWrapper) fuelCardWrapper.style.display = 'none';
            if (serialNumberLabel) serialNumberLabel.innerText = 'N° de série';
        } else if (type === 'ordinateur') {
            if (imeiWrapper) imeiWrapper.style.display = 'none';
            if (plateWrapper) plateWrapper.style.display = 'none';
            if (vehicleTypeWrapper) vehicleTypeWrapper.style.display = 'none';
            if (fuelTypeWrapper) fuelTypeWrapper.style.display = 'none';
            if (firstRegWrapper) firstRegWrapper.style.display = 'none';
            if (ctWrapper) ctWrapper.style.display = 'none';
            if (mileageWrapper) mileageWrapper.style.display = 'none';
            if (fuelCardWrapper) fuelCardWrapper.style.display = 'none';
            if (serialNumberLabel) serialNumberLabel.innerText = 'N° de série';
        } else if (type === 'voiture') {
            if (imeiWrapper) imeiWrapper.style.display = 'none';
            if (plateWrapper) plateWrapper.style.display = 'block';
            if (vehicleTypeWrapper) vehicleTypeWrapper.style.display = 'block';
            if (fuelTypeWrapper) fuelTypeWrapper.style.display = 'block';
            if (firstRegWrapper) firstRegWrapper.style.display = 'block';
            if (ctWrapper) ctWrapper.style.display = 'block';
            if (mileageWrapper) mileageWrapper.style.display = 'block';
            if (fuelCardWrapper) fuelCardWrapper.style.display = 'block';
            if (serialNumberLabel) serialNumberLabel.innerText = 'N° de série / VIN (Châssis)';
        } else {
            if (imeiWrapper) imeiWrapper.style.display = 'block';
            if (plateWrapper) plateWrapper.style.display = 'block';
            if (vehicleTypeWrapper) vehicleTypeWrapper.style.display = 'none';
            if (fuelTypeWrapper) fuelTypeWrapper.style.display = 'none';
            if (firstRegWrapper) firstRegWrapper.style.display = 'none';
            if (ctWrapper) ctWrapper.style.display = 'none';
            if (mileageWrapper) mileageWrapper.style.display = 'none';
            if (fuelCardWrapper) fuelCardWrapper.style.display = 'none';
            if (serialNumberLabel) serialNumberLabel.innerText = 'N° de série';
        }
    }

    function onEmployeeSelect(select) {
        const option = select.options[select.selectedIndex];
        if (option && option.value) {
            const firstName = option.getAttribute('data-firstname');
            const lastName = option.getAttribute('data-lastname');
            const companyId = option.getAttribute('data-companyid');
            const establishment = option.getAttribute('data-establishment');

            if (firstName) document.getElementById('assign_first_name').value = firstName;
            if (lastName) document.getElementById('assign_last_name').value = lastName;
            
            if (companyId) {
                const companySelect = document.getElementById('company_id');
                if (companySelect) companySelect.value = companyId;
            }
            if (establishment) {
                const estInput = document.getElementById('establishment');
                if (estInput) estInput.value = establishment;
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateFormVisibility();
    });
</script>
@endpush
