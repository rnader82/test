<form id="tech_interv_form" action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    @php
        $prefilledClient = isset($arr_preset['client']) ? $arr_preset['client'] : null;
        $currentClient = $technicalIntervention->client ?? $prefilledClient;
        $currentContact = $technicalIntervention->clientContact ?? ($currentClient && $currentClient->contacts ? $currentClient->contacts->first() : null);
        $selectedType = old('tech_intervention_type_id', $technicalIntervention->tech_intervention_type_id ?? (isset($types) && $types->count() ? $types->first()->id : ''));
        $selectedSubType = old('tech_intervention_sub_type_id', $technicalIntervention->tech_intervention_sub_type_id ?? '');
        $selectedPriority = old('priority', $technicalIntervention->priority ?? 'Normal');
        $assignedUserIds = old('assigned_users', $technicalIntervention->assignedUsers ? $technicalIntervention->assignedUsers->pluck('id')->toArray() : []);
        $selectedUserId = !empty($assignedUserIds) ? $assignedUserIds[0] : '';
        
        $interventionDateVal = old('intervention_date', isset($technicalIntervention->intervention_date) ? \Carbon\Carbon::parse($technicalIntervention->intervention_date)->format('Y-m-d\TH:i') : now()->format('Y-m-d\T09:00'));
        $planDateVal = '';
        $planTimeVal = '09:00';
        if (!empty($interventionDateVal)) {
            $parsedDate = \Carbon\Carbon::parse($interventionDateVal);
            $planDateVal = $parsedDate->format('Y-m-d');
            $planTimeVal = $parsedDate->format('H:i');
        }

        $formattedDuration = '02:30';
        if (!empty($technicalIntervention->estimated_duration)) {
            $d = intdiv($technicalIntervention->estimated_duration, 1440);
            $rem = $technicalIntervention->estimated_duration % 1440;
            $h = intdiv($rem, 60);
            $m = $rem % 60;
            $formattedDuration = ($d > 0 ? "{$d}d " : '') . sprintf('%02d:%02d', $h, $m);
        } elseif (old('estimated_duration')) {
            $formattedDuration = old('estimated_duration');
        }

        $isSiteDiff = old('is_site_address_different', $technicalIntervention->is_site_address_different ?? false);
        $siteName = old('site_name', $technicalIntervention->site_name ?? ($currentClient ? $currentClient->raison_sociale : 'Site Entrepôt Nord'));
        $siteAddress = old('site_address', $technicalIntervention->site_address ?? ($currentClient ? trim(($currentClient->adresse_1 ?? '') . ' ' . ($currentClient->adresse_2 ?? '') . "\n" . ($currentClient->code_postal ?? '') . ' ' . ($currentClient->ville ?? '')) : "8 rue de l'Industrie\n59100 Roubaix"));
        $gpsCoords = old('gps_coordinates', $technicalIntervention->gps_coordinates ?? '50.6884, 3.1748');
        $siteContactName = old('site_contact_name', $technicalIntervention->site_contact_name ?? ($currentContact ? ($currentContact->prenom . ' ' . $currentContact->nom) : 'M. Martin'));
        $siteContactPhone = old('site_contact_phone', $technicalIntervention->site_contact_phone ?? ($currentContact ? ($currentContact->telephone ?? $currentContact->mobile) : ($currentClient->telephone ?? '06 98 76 54 32')));

        // Preloaded / Existing Tasks
        $tasksList = [];
        if ($technicalIntervention->id && $technicalIntervention->tasks->count() > 0) {
            foreach ($technicalIntervention->tasks as $t) {
                $tasksList[] = [
                    'id' => $t->id,
                    'task' => $t->task,
                    'is_completed' => (bool)$t->is_completed,
                ];
            }
        } elseif (!$technicalIntervention->id) {
            $tasksList = [
                ['task' => 'Vérification du site', 'is_completed' => true],
                ['task' => 'Installation des équipements', 'is_completed' => true],
                ['task' => 'Raccordement électrique', 'is_completed' => true],
                ['task' => 'Configuration du système', 'is_completed' => false],
                ['task' => 'Test de fonctionnement', 'is_completed' => true],
                ['task' => 'Formation client', 'is_completed' => true],
            ];
        }

        // Materials and Tools
        $materialsList = [];
        $toolsList = [];
        if ($technicalIntervention->id) {
            foreach ($technicalIntervention->equipmentTechIntervs as $item) {
                if ($item->item_type === 'tool') {
                    $toolsList[] = $item;
                } else {
                    $materialsList[] = $item;
                }
            }
        }
    @endphp

    <input type="hidden" name="intervention_date" id="intervention_date" value="{{ $interventionDateVal }}">
    <input type="hidden" name="subject" id="hidden_subject" value="{{ old('subject', $technicalIntervention->subject ?? '') }}">

    <!-- TOP SECTION: Grande Card englobant les 4 cartes -->
    <div class="outer-container-card mb-4">
        <div class="row g-3">
            <!-- 1. Client Card -->
            <div class="col-xl-3 col-lg-6 col-md-12">
                <div class="inner-sub-card">
                    <div class="card-title-custom">
                        <i class="bi bi-person" style="color: #91378e; font-size: 1.25rem;"></i>
                        <span>1. Client</span>
                    </div>

                    <div class="field-row">
                        <label for="client_search" class="field-label">Raison sociale</label>
                        <div class="field-input-wrap position-relative">
                            <input type="text" id="client_search" class="form-control custom-input" autocomplete="off"
                                value="{{ old('client_search', $currentClient?->raison_sociale ?? 'Société Exemple') }}" placeholder="Société Exemple" required>
                            <input type="hidden" name="client_id" id="client_id" value="{{ old('client_id', $currentClient?->id ?? '') }}" required>
                            <ul id="client_suggestions" class="list-group position-absolute w-100 bg-white shadow-lg border rounded-3"
                                style="top:100%; left:0; z-index: 1060; display:none; max-height: 200px; overflow-y: auto;"></ul>
                        </div>
                    </div>

                    <div class="field-row">
                        <label for="client_contact_id" class="field-label">Contact</label>
                        <div class="field-input-wrap">
                            <select name="client_contact_id" id="client_contact_id" class="form-select custom-input">
                                <option value="">Sélectionner un contact</option>
                                @if($currentClient && $currentClient->contacts)
                                    @foreach($currentClient->contacts as $contact)
                                        <option value="{{ $contact->id }}"
                                            data-email="{{ $contact->email }}"
                                            data-phone="{{ $contact->telephone ?? $contact->mobile }}"
                                            data-name="{{ $contact->prenom }} {{ $contact->nom }}"
                                            {{ old('client_contact_id', $currentContact?->id ?? '') == $contact->id ? 'selected' : '' }}>
                                            {{ $contact->prenom }} {{ $contact->nom }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" selected>M. Dupont</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="field-row">
                        <label class="field-label">Email</label>
                        <div class="field-input-wrap">
                            <input type="email" id="client_email" class="form-control custom-input"
                                value="{{ $currentContact?->email ?? ($currentClient?->email ?? 'contact@exemple.com') }}" placeholder="contact@exemple.com">
                        </div>
                    </div>

                    <div class="field-row">
                        <label class="field-label">Téléphone</label>
                        <div class="field-input-wrap">
                            <input type="text" id="client_phone" class="form-control custom-input"
                                value="{{ $currentContact?->telephone ?? ($currentContact?->mobile ?? ($currentClient?->telephone ?? '01 23 45 67 89')) }}" placeholder="01 23 45 67 89">
                        </div>
                    </div>

                    <div class="field-row align-items-start">
                        <label class="field-label pt-1">Adresse</label>
                        <div class="field-input-wrap">
                            <textarea id="client_address_display" class="form-control custom-input" rows="2" placeholder="15 rue des Entrepreneurs&#10;75015 Paris">{{ $currentClient ? trim(($currentClient->adresse_1 ?? '') . "\n" . ($currentClient->code_postal ?? '') . ' ' . ($currentClient->ville ?? '')) : "15 rue des Entrepreneurs\n75015 Paris" }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Site d'installation Card -->
            <div class="col-xl-3 col-lg-6 col-md-12">
                <div class="inner-sub-card">
                    <div class="card-title-custom">
                        <i class="bi bi-geo-alt-fill" style="color: #16a34a; font-size: 1.25rem;"></i>
                        <span>2. Site d'installation</span>
                    </div>

                    <div class="form-check mb-2" style="margin-left: 105px;">
                        <input class="form-check-input custom-checkbox" type="checkbox" id="is_site_address_different" name="is_site_address_different" value="1"
                            {{ $isSiteDiff ? 'checked' : '' }}>
                        <label class="form-check-label field-label-text ms-1" for="is_site_address_different">
                            Adresse différente du client
                        </label>
                    </div>

                    <div class="field-row">
                        <label for="site_name" class="field-label">Nom du site</label>
                        <div class="field-input-wrap">
                            <input type="text" name="site_name" id="site_name" class="form-control custom-input"
                                value="{{ $siteName }}" placeholder="Site Entrepôt Nord">
                        </div>
                    </div>

                    <div class="field-row align-items-start">
                        <label for="site_address" class="field-label pt-1">Adresse</label>
                        <div class="field-input-wrap">
                            <textarea name="site_address" id="site_address" class="form-control custom-input" rows="2"
                                placeholder="8 rue de l'Industrie&#10;59100 Roubaix">{{ $siteAddress }}</textarea>
                        </div>
                    </div>

                    <div class="field-row">
                        <label for="gps_coordinates" class="field-label">Coordonnées GPS</label>
                        <div class="field-input-wrap position-relative">
                            <input type="text" name="gps_coordinates" id="gps_coordinates" class="form-control custom-input pe-4"
                                value="{{ $gpsCoords }}" placeholder="50.6884, 3.1748">
                            <button class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-primary p-0 pe-2 border-0" type="button" id="btn_get_gps" title="Obtenir la géolocalisation">
                                <i class="bi bi-geo-alt-fill" style="color: #91378e;"></i>
                            </button>
                        </div>
                    </div>

                    <div class="field-row">
                        <label for="site_contact_name" class="field-label">Contact sur site</label>
                        <div class="field-input-wrap">
                            <input type="text" name="site_contact_name" id="site_contact_name" class="form-control custom-input"
                                value="{{ $siteContactName }}" placeholder="M. Martin">
                        </div>
                    </div>

                    <div class="field-row">
                        <label for="site_contact_phone" class="field-label">Téléphone</label>
                        <div class="field-input-wrap">
                            <input type="text" name="site_contact_phone" id="site_contact_phone" class="form-control custom-input"
                                value="{{ $siteContactPhone }}" placeholder="06 98 76 54 32">
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Intervention Card -->
            <div class="col-xl-3 col-lg-6 col-md-12">
                <div class="inner-sub-card">
                    <div class="card-title-custom">
                        <i class="bi bi-wrench-adjustable" style="color: #91378e; font-size: 1.25rem;"></i>
                        <span>3. Intervention</span>
                    </div>

                    <div class="field-row">
                        <label for="tech_intervention_type_id" class="field-label">Type d'intervention</label>
                        <div class="field-input-wrap">
                            <select class="form-select custom-input" name="tech_intervention_type_id" id="tech_intervention_type_id" required>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" {{ $selectedType == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="field-row">
                        <label for="tech_intervention_sub_type_id" class="field-label">Sous-type</label>
                        <div class="field-input-wrap">
                            <select class="form-select custom-input" name="tech_intervention_sub_type_id" id="tech_intervention_sub_type_id">
                                <option value="">Sélectionner un sous-type</option>
                                @if(isset($subTypes))
                                    @foreach($subTypes as $sub)
                                        <option value="{{ $sub->id }}" data-parent-id="{{ $sub->parent_id }}" {{ $selectedSubType == $sub->id ? 'selected' : '' }}>
                                            {{ $sub->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="field-row">
                        <label for="priority" class="field-label">Priorité</label>
                        <div class="field-input-wrap">
                            <select class="form-select custom-input" name="priority" id="priority">
                                <option value="Normal" {{ $selectedPriority === 'Normal' ? 'selected' : '' }}>Normale</option>
                                <option value="Important" {{ $selectedPriority === 'Important' ? 'selected' : '' }}>Importante</option>
                                <option value="Urgent" {{ $selectedPriority === 'Urgent' ? 'selected' : '' }}>Urgente</option>
                            </select>
                        </div>
                    </div>

                    <div class="field-row">
                        <label class="field-label">Date planifiée</label>
                        <div class="field-input-wrap d-flex gap-2">
                            <div class="position-relative flex-grow-1">
                                <span class="position-absolute start-0 top-50 translate-middle-y ps-2 text-muted" style="pointer-events:none;"><i class="bi bi-calendar3"></i></span>
                                <input type="date" class="form-control custom-input ps-4" id="plan_date" value="{{ $planDateVal }}">
                            </div>
                            <div class="position-relative" style="width: 100px;">
                                <span class="position-absolute start-0 top-50 translate-middle-y ps-2 text-muted" style="pointer-events:none;"><i class="bi bi-clock"></i></span>
                                <input type="time" class="form-control custom-input ps-4" id="plan_time" value="{{ $planTimeVal }}">
                            </div>
                        </div>
                    </div>

                    <div class="field-row">
                        <label for="estimated_duration" class="field-label">Durée estimée</label>
                        <div class="field-input-wrap">
                            <input type="text" class="form-control custom-input" id="estimated_duration" name="estimated_duration"
                                value="{{ $formattedDuration }}" placeholder="02:30">
                        </div>
                    </div>

                    <div class="field-row">
                        <label for="assigned_user_id" class="field-label">Technicien assigné</label>
                        <div class="field-input-wrap">
                            <select name="assigned_users[]" id="assigned_user_id" class="form-select custom-input">
                                <option value="">Non assigné</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ $selectedUserId == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Checklist des tâches Card -->
            <div class="col-xl-3 col-lg-6 col-md-12">
                <div class="inner-sub-card d-flex flex-column">
                    <div class="card-title-custom">
                        <i class="bi bi-list-check" style="color: #91378e; font-size: 1.25rem;"></i>
                        <span>4. Checklist des tâches</span>
                    </div>

                    <div id="checklist_container" class="flex-grow-1 overflow-auto pe-1 mb-3" style="max-height: 280px;">
                        @foreach($tasksList as $idx => $tItem)
                            <div class="task-row d-flex align-items-center justify-content-between py-1 px-1 rounded-2">
                                <div class="d-flex align-items-center gap-2 mb-0 flex-grow-1">
                                    <input class="form-check-input custom-checkbox" type="checkbox"
                                        id="task_check_{{ $idx }}" {{ $tItem['is_completed'] ? 'checked' : '' }}
                                        onchange="updateTaskRow(this)">
                                    <input type="hidden" name="tasks[{{ $idx }}][is_completed]" class="task-completed-val" value="{{ $tItem['is_completed'] ? '1' : '0' }}">
                                    <input type="hidden" name="tasks[{{ $idx }}][task]" class="task-text-val" value="{{ $tItem['task'] }}">
                                    <label class="task-label-text mb-0 cursor-pointer" for="task_check_{{ $idx }}">
                                        {{ $tItem['task'] }}
                                    </label>
                                </div>
                                <button type="button" class="btn btn-link btn-sm text-danger opacity-50 hover-opacity-100 p-0 ms-2 remove-task-btn" onclick="removeTaskRow(this)">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-auto pt-2">
                        <a href="javascript:void(0)" class="action-link-blue" id="btn_add_task_modal">
                            Ajouter une tâche
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BOTTOM SECTION: Grande Card englobant les 2 cartes (Matériels et Outils) -->
    <div class="outer-container-card mb-4">
        <div class="card-title-custom mb-3">
            <i class="bi bi-box-seam" style="color: #91378e; font-size: 1.25rem;"></i>
            <span>5. Matériel & outils nécessaires</span>
        </div>

        <div class="row g-3">
            <!-- Carte 1: Matériel nécessaire pour l'intervention -->
            <div class="col-lg-6 col-md-12">
                <div class="inner-sub-card d-flex flex-column h-100">
                    <div class="sub-table-title mb-3">Matériel nécessaire pour l'intervention</div>
                    
                    <div class="table-responsive flex-grow-1 mb-2">
                        <table class="table custom-table align-middle mb-0" id="materials_table">
                            <thead>
                                <tr>
                                    <th>Matériel</th>
                                    <th>Référence</th>
                                    <th class="text-center" style="width: 60px;">Qté</th>
                                    <th style="width: 230px;">Où se trouve le matériel ?</th>
                                    <th style="width: 25px;"></th>
                                </tr>
                            </thead>
                            <tbody id="materials_tbody">
                                @forelse($materialsList as $mIdx => $mat)
                                    @php
                                        $isAtCl = (bool)$mat->is_at_client;
                                        $whName = $mat->warehouse?->name ?? 'bureau Novalyo';
                                    @endphp
                                    <tr class="mat-row">
                                        <td class="fw-medium text-dark">
                                            {{ $mat->display_name }}
                                            <input type="hidden" name="materials[{{ $mIdx }}][equipment_id]" value="{{ $mat->equipment_id }}">
                                            <input type="hidden" name="materials[{{ $mIdx }}][custom_name]" value="{{ $mat->display_name }}">
                                            <input type="hidden" name="materials[{{ $mIdx }}][custom_reference]" value="{{ $mat->display_reference }}">
                                            <input type="hidden" name="materials[{{ $mIdx }}][quantity]" value="{{ $mat->quantity }}">
                                            <input type="hidden" name="materials[{{ $mIdx }}][is_at_client]" class="mat-is-at-client" value="{{ $isAtCl ? '1' : '0' }}">
                                            <input type="hidden" name="materials[{{ $mIdx }}][warehouse_id]" class="mat-warehouse-id" value="{{ $mat->warehouse_id }}">
                                        </td>
                                        <td class="text-muted">{{ $mat->display_reference }}</td>
                                        <td class="text-center">{{ $mat->quantity }}</td>
                                        <td>
                                            <div class="dropdown">
                                                @if($isAtCl)
                                                    <button class="badge-pill-loc badge-client dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Chez le client
                                                    </button>
                                                @else
                                                    <button class="badge-pill-loc badge-novalyo dropdown-toggle text-truncate" style="max-width: 210px;" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="{{ $whName }}">
                                                        À prendre au bureau Novalyo
                                                    </button>
                                                @endif
                                                <ul class="dropdown-menu shadow-sm border rounded-3 p-1">
                                                    <li><a class="dropdown-item small rounded-2 py-1 location-option" href="#" data-at-client="1" data-warehouse-id="" data-label="Chez le client">Chez le client</a></li>
                                                    <li><hr class="dropdown-divider my-1"></li>
                                                    @foreach($warehouses as $wh)
                                                        <li><a class="dropdown-item small rounded-2 py-1 location-option" href="#" data-at-client="0" data-warehouse-id="{{ $wh->id }}" data-label="À prendre: {{ $wh->name }}">À prendre: {{ $wh->name }}</a></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-link btn-sm text-danger opacity-50 hover-opacity-100 p-0 remove-mat-btn" onclick="removeTableRow(this)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    @if(!$technicalIntervention->id)
                                        <!-- Demo rows matching screenshot -->
                                        <tr class="mat-row">
                                            <td class="fw-medium text-dark">Lecteur de badges
                                                <input type="hidden" name="materials[0][custom_name]" value="Lecteur de badges">
                                                <input type="hidden" name="materials[0][custom_reference]" value="LECT-BADGE-01">
                                                <input type="hidden" name="materials[0][quantity]" value="2">
                                                <input type="hidden" name="materials[0][is_at_client]" class="mat-is-at-client" value="1">
                                                <input type="hidden" name="materials[0][warehouse_id]" class="mat-warehouse-id" value="">
                                            </td>
                                            <td class="text-muted">LECT-BADGE-01</td>
                                            <td class="text-center">2</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="badge-pill-loc badge-client dropdown-toggle" type="button" data-bs-toggle="dropdown">Chez le client</button>
                                                    <ul class="dropdown-menu shadow-sm border rounded-3 p-1">
                                                        <li><a class="dropdown-item small rounded-2 py-1 location-option" href="#" data-at-client="1" data-warehouse-id="" data-label="Chez le client">Chez le client</a></li>
                                                        <li><hr class="dropdown-divider my-1"></li>
                                                        @foreach($warehouses as $wh)
                                                            <li><a class="dropdown-item small rounded-2 py-1 location-option" href="#" data-at-client="0" data-warehouse-id="{{ $wh->id }}" data-label="À prendre: {{ $wh->name }}">À prendre: {{ $wh->name }}</a></li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </td>
                                            <td class="text-end"><button type="button" class="btn btn-link btn-sm text-danger opacity-50 hover-opacity-100 p-0 remove-mat-btn" onclick="removeTableRow(this)"><i class="bi bi-trash"></i></button></td>
                                        </tr>
                                        <tr class="mat-row">
                                            <td class="fw-medium text-dark">Centrale de contrôle
                                                <input type="hidden" name="materials[1][custom_name]" value="Centrale de contrôle">
                                                <input type="hidden" name="materials[1][custom_reference]" value="CTRL-AC-200">
                                                <input type="hidden" name="materials[1][quantity]" value="1">
                                                <input type="hidden" name="materials[1][is_at_client]" class="mat-is-at-client" value="0">
                                                <input type="hidden" name="materials[1][warehouse_id]" class="mat-warehouse-id" value="{{ $warehouses->first()?->id }}">
                                            </td>
                                            <td class="text-muted">CTRL-AC-200</td>
                                            <td class="text-center">1</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="badge-pill-loc badge-novalyo dropdown-toggle text-truncate" style="max-width: 210px;" type="button" data-bs-toggle="dropdown">À prendre au bureau Novalyo</button>
                                                    <ul class="dropdown-menu shadow-sm border rounded-3 p-1">
                                                        <li><a class="dropdown-item small rounded-2 py-1 location-option" href="#" data-at-client="1" data-warehouse-id="" data-label="Chez le client">Chez le client</a></li>
                                                        <li><hr class="dropdown-divider my-1"></li>
                                                        @foreach($warehouses as $wh)
                                                            <li><a class="dropdown-item small rounded-2 py-1 location-option" href="#" data-at-client="0" data-warehouse-id="{{ $wh->id }}" data-label="À prendre: {{ $wh->name }}">À prendre: {{ $wh->name }}</a></li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </td>
                                            <td class="text-end"><button type="button" class="btn btn-link btn-sm text-danger opacity-50 hover-opacity-100 p-0 remove-mat-btn" onclick="removeTableRow(this)"><i class="bi bi-trash"></i></button></td>
                                        </tr>
                                        <tr class="mat-row">
                                            <td class="fw-medium text-dark">Alimentation 12V/5A
                                                <input type="hidden" name="materials[2][custom_name]" value="Alimentation 12V/5A">
                                                <input type="hidden" name="materials[2][custom_reference]" value="ALIM-12V5A">
                                                <input type="hidden" name="materials[2][quantity]" value="1">
                                                <input type="hidden" name="materials[2][is_at_client]" class="mat-is-at-client" value="1">
                                                <input type="hidden" name="materials[2][warehouse_id]" class="mat-warehouse-id" value="">
                                            </td>
                                            <td class="text-muted">ALIM-12V5A</td>
                                            <td class="text-center">1</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="badge-pill-loc badge-client dropdown-toggle" type="button" data-bs-toggle="dropdown">Chez le client</button>
                                                    <ul class="dropdown-menu shadow-sm border rounded-3 p-1">
                                                        <li><a class="dropdown-item small rounded-2 py-1 location-option" href="#" data-at-client="1" data-warehouse-id="" data-label="Chez le client">Chez le client</a></li>
                                                        <li><hr class="dropdown-divider my-1"></li>
                                                        @foreach($warehouses as $wh)
                                                            <li><a class="dropdown-item small rounded-2 py-1 location-option" href="#" data-at-client="0" data-warehouse-id="{{ $wh->id }}" data-label="À prendre: {{ $wh->name }}">À prendre: {{ $wh->name }}</a></li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </td>
                                            <td class="text-end"><button type="button" class="btn btn-link btn-sm text-danger opacity-50 hover-opacity-100 p-0 remove-mat-btn" onclick="removeTableRow(this)"><i class="bi bi-trash"></i></button></td>
                                        </tr>
                                        <tr class="mat-row">
                                            <td class="fw-medium text-dark">Câble 4 fils 0,6mm (20 m)
                                                <input type="hidden" name="materials[3][custom_name]" value="Câble 4 fils 0,6mm (20 m)">
                                                <input type="hidden" name="materials[3][custom_reference]" value="CABLE-4F-06">
                                                <input type="hidden" name="materials[3][quantity]" value="50">
                                                <input type="hidden" name="materials[3][is_at_client]" class="mat-is-at-client" value="0">
                                                <input type="hidden" name="materials[3][warehouse_id]" class="mat-warehouse-id" value="{{ $warehouses->first()?->id }}">
                                            </td>
                                            <td class="text-muted">CABLE-4F-06</td>
                                            <td class="text-center">50 m</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="badge-pill-loc badge-novalyo dropdown-toggle text-truncate" style="max-width: 210px;" type="button" data-bs-toggle="dropdown">À prendre au bureau Novalyo</button>
                                                    <ul class="dropdown-menu shadow-sm border rounded-3 p-1">
                                                        <li><a class="dropdown-item small rounded-2 py-1 location-option" href="#" data-at-client="1" data-warehouse-id="" data-label="Chez le client">Chez le client</a></li>
                                                        <li><hr class="dropdown-divider my-1"></li>
                                                        @foreach($warehouses as $wh)
                                                            <li><a class="dropdown-item small rounded-2 py-1 location-option" href="#" data-at-client="0" data-warehouse-id="{{ $wh->id }}" data-label="À prendre: {{ $wh->name }}">À prendre: {{ $wh->name }}</a></li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </td>
                                            <td class="text-end"><button type="button" class="btn btn-link btn-sm text-danger opacity-50 hover-opacity-100 p-0 remove-mat-btn" onclick="removeTableRow(this)"><i class="bi bi-trash"></i></button></td>
                                        </tr>
                                        <tr class="mat-row">
                                            <td class="fw-medium text-dark">Batterie 12V 7Ah
                                                <input type="hidden" name="materials[4][custom_name]" value="Batterie 12V 7Ah">
                                                <input type="hidden" name="materials[4][custom_reference]" value="BAT-12V7AH">
                                                <input type="hidden" name="materials[4][quantity]" value="2">
                                                <input type="hidden" name="materials[4][is_at_client]" class="mat-is-at-client" value="0">
                                                <input type="hidden" name="materials[4][warehouse_id]" class="mat-warehouse-id" value="{{ $warehouses->first()?->id }}">
                                            </td>
                                            <td class="text-muted">BAT-12V7AH</td>
                                            <td class="text-center">2</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="badge-pill-loc badge-novalyo dropdown-toggle text-truncate" style="max-width: 210px;" type="button" data-bs-toggle="dropdown">À prendre au bureau Novalyo</button>
                                                    <ul class="dropdown-menu shadow-sm border rounded-3 p-1">
                                                        <li><a class="dropdown-item small rounded-2 py-1 location-option" href="#" data-at-client="1" data-warehouse-id="" data-label="Chez le client">Chez le client</a></li>
                                                        <li><hr class="dropdown-divider my-1"></li>
                                                        @foreach($warehouses as $wh)
                                                            <li><a class="dropdown-item small rounded-2 py-1 location-option" href="#" data-at-client="0" data-warehouse-id="{{ $wh->id }}" data-label="À prendre: {{ $wh->name }}">À prendre: {{ $wh->name }}</a></li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </td>
                                            <td class="text-end"><button type="button" class="btn btn-link btn-sm text-danger opacity-50 hover-opacity-100 p-0 remove-mat-btn" onclick="removeTableRow(this)"><i class="bi bi-trash"></i></button></td>
                                        </tr>
                                    @endif
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-auto pt-2">
                        <a href="javascript:void(0)" class="action-link-blue" id="btn_add_material_modal">
                            Ajouter du matériel
                        </a>
                    </div>
                </div>
            </div>

            <!-- Carte 2: Outils d'installation / programmation -->
            <div class="col-lg-6 col-md-12">
                <div class="inner-sub-card d-flex flex-column h-100">
                    <div class="sub-table-title mb-3">Outils d'installation / programmation</div>

                    <div class="table-responsive flex-grow-1 mb-2">
                        <table class="table custom-table align-middle mb-0" id="tools_table">
                            <thead>
                                <tr>
                                    <th>Outil / Logiciel</th>
                                    <th>Référence</th>
                                    <th class="text-center" style="width: 60px;">Qté</th>
                                    <th style="width: 25px;"></th>
                                </tr>
                            </thead>
                            <tbody id="tools_tbody">
                                @forelse($toolsList as $tIdx => $tool)
                                    <tr class="tool-row">
                                        <td class="fw-medium text-dark">
                                            {{ $tool->display_name }}
                                            <input type="hidden" name="tools[{{ $tIdx }}][equipment_id]" value="{{ $tool->equipment_id }}">
                                            <input type="hidden" name="tools[{{ $tIdx }}][custom_name]" value="{{ $tool->display_name }}">
                                            <input type="hidden" name="tools[{{ $tIdx }}][custom_reference]" value="{{ $tool->display_reference }}">
                                            <input type="hidden" name="tools[{{ $tIdx }}][quantity]" value="{{ $tool->quantity }}">
                                        </td>
                                        <td class="text-muted">{{ $tool->display_reference }}</td>
                                        <td class="text-center">{{ $tool->quantity }}</td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-link btn-sm text-danger opacity-50 hover-opacity-100 p-0 remove-tool-btn" onclick="removeTableRow(this)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    @if(!$technicalIntervention->id)
                                        <!-- Demo rows matching screenshot -->
                                        <tr class="tool-row">
                                            <td class="fw-medium text-dark">Tournevis set
                                                <input type="hidden" name="tools[0][custom_name]" value="Tournevis set">
                                                <input type="hidden" name="tools[0][custom_reference]" value="OUT-TS-01">
                                                <input type="hidden" name="tools[0][quantity]" value="1">
                                            </td>
                                            <td class="text-muted">OUT-TS-01</td>
                                            <td class="text-center">1</td>
                                            <td class="text-end"><button type="button" class="btn btn-link btn-sm text-danger opacity-50 hover-opacity-100 p-0 remove-tool-btn" onclick="removeTableRow(this)"><i class="bi bi-trash"></i></button></td>
                                        </tr>
                                        <tr class="tool-row">
                                            <td class="fw-medium text-dark">Perceuse
                                                <input type="hidden" name="tools[1][custom_name]" value="Perceuse">
                                                <input type="hidden" name="tools[1][custom_reference]" value="OUT-PRC-01">
                                                <input type="hidden" name="tools[1][quantity]" value="1">
                                            </td>
                                            <td class="text-muted">OUT-PRC-01</td>
                                            <td class="text-center">1</td>
                                            <td class="text-end"><button type="button" class="btn btn-link btn-sm text-danger opacity-50 hover-opacity-100 p-0 remove-tool-btn" onclick="removeTableRow(this)"><i class="bi bi-trash"></i></button></td>
                                        </tr>
                                        <tr class="tool-row">
                                            <td class="fw-medium text-dark">Ordinateur portable
                                                <input type="hidden" name="tools[2][custom_name]" value="Ordinateur portable">
                                                <input type="hidden" name="tools[2][custom_reference]" value="OUT-PC-01">
                                                <input type="hidden" name="tools[2][quantity]" value="1">
                                            </td>
                                            <td class="text-muted">OUT-PC-01</td>
                                            <td class="text-center">1</td>
                                            <td class="text-end"><button type="button" class="btn btn-link btn-sm text-danger opacity-50 hover-opacity-100 p-0 remove-tool-btn" onclick="removeTableRow(this)"><i class="bi bi-trash"></i></button></td>
                                        </tr>
                                        <tr class="tool-row">
                                            <td class="fw-medium text-dark">Logiciel de configuration
                                                <input type="hidden" name="tools[3][custom_name]" value="Logiciel de configuration">
                                                <input type="hidden" name="tools[3][custom_reference]" value="SOFT-CONF-AC">
                                                <input type="hidden" name="tools[3][quantity]" value="1">
                                            </td>
                                            <td class="text-muted">SOFT-CONF-AC</td>
                                            <td class="text-center">1</td>
                                            <td class="text-end"><button type="button" class="btn btn-link btn-sm text-danger opacity-50 hover-opacity-100 p-0 remove-tool-btn" onclick="removeTableRow(this)"><i class="bi bi-trash"></i></button></td>
                                        </tr>
                                    @endif
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-auto pt-2">
                        <a href="javascript:void(0)" class="action-link-blue" id="btn_add_tool_modal">
                            Ajouter un outil / logiciel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal: Ajouter une tâche -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">Ajouter une tâche</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <div class="mb-3">
                    <label class="form-label small text-muted fw-semibold">Intitulé de la tâche</label>
                    <input type="text" id="modal_task_input" class="form-control custom-input" placeholder="ex: Vérification des branchements">
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-sm btn-light rounded-pill px-3" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" id="btn_confirm_add_task" style="background-color: #91378e; border-color: #91378e;">Ajouter</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Ajouter du matériel -->
<div class="modal fade" id="addMaterialModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">Ajouter du matériel</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <div class="mb-3 position-relative">
                    <label class="form-label small text-muted fw-semibold">Rechercher un équipement / matériel</label>
                    <input type="text" id="modal_mat_search" class="form-control custom-input" placeholder="Nom ou référence de l'équipement..." autocomplete="off">
                    <input type="hidden" id="modal_mat_equipment_id">
                    <input type="hidden" id="modal_mat_name">
                    <input type="hidden" id="modal_mat_ref">
                    <ul id="modal_mat_suggestions" class="list-group position-absolute w-100 bg-white shadow-lg border rounded-3"
                        style="top:100%; left:0; z-index: 1070; display:none; max-height: 180px; overflow-y: auto;"></ul>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-8">
                        <label class="form-label small text-muted fw-semibold">Nom personnalisé / Matériel</label>
                        <input type="text" id="modal_mat_custom_name" class="form-control custom-input" placeholder="Nom du matériel">
                    </div>
                    <div class="col-4">
                        <label class="form-label small text-muted fw-semibold">Référence</label>
                        <input type="text" id="modal_mat_custom_ref" class="form-control custom-input" placeholder="RÉF">
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-4">
                        <label class="form-label small text-muted fw-semibold">Quantité</label>
                        <input type="number" id="modal_mat_qty" class="form-control custom-input" value="1" min="1">
                    </div>
                    <div class="col-8">
                        <label class="form-label small text-muted fw-semibold">Où se trouve le matériel ?</label>
                        <select id="modal_mat_location" class="form-select custom-input">
                            <option value="client">Chez le client</option>
                            @foreach($warehouses as $wh)
                                <option value="wh_{{ $wh->id }}">À prendre: {{ $wh->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-sm btn-light rounded-pill px-3" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" id="btn_confirm_add_material" style="background-color: #91378e; border-color: #91378e;">Ajouter</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Ajouter un outil / logiciel -->
<div class="modal fade" id="addToolModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">Ajouter un outil ou logiciel</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <div class="row g-2 mb-3">
                    <div class="col-8">
                        <label class="form-label small text-muted fw-semibold">Outil / Logiciel</label>
                        <input type="text" id="modal_tool_name" class="form-control custom-input" placeholder="ex: Tournevis set, Logiciel...">
                    </div>
                    <div class="col-4">
                        <label class="form-label small text-muted fw-semibold">Référence</label>
                        <input type="text" id="modal_tool_ref" class="form-control custom-input" placeholder="ex: OUT-TS-01">
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-4">
                        <label class="form-label small text-muted fw-semibold">Quantité</label>
                        <input type="number" id="modal_tool_qty" class="form-control custom-input" value="1" min="1">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-sm btn-light rounded-pill px-3" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" id="btn_confirm_add_tool" style="background-color: #91378e; border-color: #91378e;">Ajouter</button>
            </div>
        </div>
    </div>
</div>

<style>
.outer-container-card {
    background: #ffffff;
    border: 1px solid #e8ecf2;
    border-radius: 14px;
    padding: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
}

.inner-sub-card {
    background: #ffffff;
    border: 1px solid #e8ecf2;
    border-radius: 12px;
    padding: 18px;
    height: 100%;
}

.card-title-custom {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 18px;
}

.field-row {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.field-label {
    width: 105px;
    min-width: 105px;
    font-size: 13px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 0;
}

.field-label-text {
    font-size: 13px;
    font-weight: 500;
    color: #1e293b;
}

.field-input-wrap {
    flex-grow: 1;
}

.custom-input {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 13px;
    color: #1e293b;
    padding: 6px 12px;
    background-color: #ffffff;
}

.custom-input:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.15);
}

.sub-table-title {
    font-size: 13.5px;
    font-weight: 700;
    color: #0f172a;
}

.custom-table {
    font-size: 13px;
    border-collapse: separate;
    border-spacing: 0;
}

.custom-table thead th {
    font-weight: 600;
    color: #0f172a;
    border-bottom: 1px solid #f1f5f9;
    padding: 8px 10px;
    background: transparent;
}

.custom-table tbody td {
    padding: 8px 10px;
    vertical-align: middle;
    border-bottom: 1px solid #f8fafc;
}

.badge-pill-loc {
    border: none;
    font-size: 12px;
    font-weight: 600;
    padding: 4px 14px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    cursor: pointer;
}

.badge-client {
    background-color: #e6f9f0 !important;
    color: #059669 !important;
}

.badge-novalyo {
    background-color: #fff4ea !important;
    color: #ea580c !important;
}

.action-link-blue {
    color: #91378e;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    cursor: pointer;
}

.action-link-blue:hover {
    color: #4338ca;
    text-decoration: underline;
}

.custom-checkbox {
    width: 16px;
    height: 16px;
    border-radius: 4px;
    border: 1px solid #cbd5e1;
    cursor: pointer;
    accent-color: #91378e;
}

.task-label-text {
    font-size: 13px;
    font-weight: 500;
    color: #1e293b;
}
</style>

<script>
window.initTechIntervForm = function(rootDoc) {
    const doc = rootDoc || document;

    // 1. Plan Date and Time Sync
    const planDate = doc.getElementById('plan_date');
    const planTime = doc.getElementById('plan_time');
    const intervDate = doc.getElementById('intervention_date');

    function syncInterventionDateTime() {
        if (planDate && intervDate) {
            const d = planDate.value;
            const t = planTime ? (planTime.value || '09:00') : '09:00';
            if (d) {
                intervDate.value = `${d}T${t}`;
            }
        }
    }
    if (planDate) planDate.addEventListener('change', syncInterventionDateTime);
    if (planTime) planTime.addEventListener('change', syncInterventionDateTime);

    // 2. Client Autocomplete Search
    const clientSearch = doc.getElementById('client_search');
    const clientId = doc.getElementById('client_id');
    const clientSuggestions = doc.getElementById('client_suggestions');
    const clientContactSelect = doc.getElementById('client_contact_id');
    const clientEmail = doc.getElementById('client_email');
    const clientPhone = doc.getElementById('client_phone');
    const clientAddressDisplay = doc.getElementById('client_address_display');
    const isSiteDiff = doc.getElementById('is_site_address_different');
    const siteName = doc.getElementById('site_name');
    const siteAddress = doc.getElementById('site_address');
    const siteContactName = doc.getElementById('site_contact_name');
    const siteContactPhone = doc.getElementById('site_contact_phone');

    if (clientSearch) {
        let debounceTimer;
        clientSearch.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const q = this.value.trim();
            if (q.length < 2) {
                if (clientSuggestions) clientSuggestions.style.display = 'none';
                return;
            }
            debounceTimer = setTimeout(() => {
                fetch(`/clients/search?q=${encodeURIComponent(q)}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(data => {
                    if (!clientSuggestions) return;
                    clientSuggestions.innerHTML = '';
                    if (!data || data.length === 0) {
                        clientSuggestions.style.display = 'none';
                        return;
                    }
                    data.forEach(client => {
                        const li = document.createElement('li');
                        li.className = 'list-group-item list-group-item-action cursor-pointer p-2 small';
                        li.innerHTML = `<strong>${client.raison_sociale}</strong> <span class="text-muted ms-1">${client.ville || ''}</span>`;
                        li.addEventListener('click', function() {
                            clientSearch.value = client.raison_sociale;
                            clientId.value = client.id;
                            clientSuggestions.style.display = 'none';

                            // Populate address and info
                            const addr = `${client.adresse_1 || ''} ${client.adresse_2 || ''}\n${client.code_postal || ''} ${client.ville || ''}`.trim();
                            if (clientAddressDisplay) clientAddressDisplay.value = addr;
                            if (clientEmail) clientEmail.value = client.email || '';
                            if (clientPhone) clientPhone.value = client.telephone || '';

                            // Auto sync site if not different
                            if (isSiteDiff && !isSiteDiff.checked) {
                                if (siteName) siteName.value = client.raison_sociale;
                                if (siteAddress) siteAddress.value = addr;
                            }

                            // Fetch contacts
                            fetch(`/clients/${client.id}/contacts`, {
                                headers: { 'X-Requested-With': 'XMLHttpRequest' }
                            })
                            .then(r => r.json())
                            .then(contacts => {
                                if (!clientContactSelect) return;
                                clientContactSelect.innerHTML = '<option value="">Sélectionner un contact</option>';
                                contacts.forEach(c => {
                                    const opt = document.createElement('option');
                                    opt.value = c.id;
                                    opt.textContent = `${c.prenom} ${c.nom}`;
                                    opt.dataset.email = c.email || '';
                                    opt.dataset.phone = c.telephone || c.mobile || '';
                                    opt.dataset.name = `${c.prenom} ${c.nom}`;
                                    clientContactSelect.appendChild(opt);
                                });
                                if (contacts.length > 0) {
                                    clientContactSelect.selectedIndex = 1;
                                    clientContactSelect.dispatchEvent(new Event('change'));
                                }
                            });
                        });
                        clientSuggestions.appendChild(li);
                    });
                    clientSuggestions.style.display = 'block';
                });
            }, 250);
        });

        document.addEventListener('click', function(e) {
            if (clientSuggestions && !clientSearch.contains(e.target) && !clientSuggestions.contains(e.target)) {
                clientSuggestions.style.display = 'none';
            }
        });
    }

    if (clientContactSelect) {
        clientContactSelect.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            if (opt && opt.value) {
                if (clientEmail && opt.dataset.email) clientEmail.value = opt.dataset.email;
                if (clientPhone && opt.dataset.phone) clientPhone.value = opt.dataset.phone;
                if (isSiteDiff && !isSiteDiff.checked) {
                    if (siteContactName && opt.dataset.name) siteContactName.value = opt.dataset.name;
                    if (siteContactPhone && opt.dataset.phone) siteContactPhone.value = opt.dataset.phone;
                }
            }
        });
    }

    // 3. Site Different Toggle
    if (isSiteDiff) {
        isSiteDiff.addEventListener('change', function() {
            if (!this.checked) {
                if (siteName && clientSearch) siteName.value = clientSearch.value;
                if (siteAddress && clientAddressDisplay) siteAddress.value = clientAddressDisplay.value;
                if (siteContactName && clientContactSelect && clientContactSelect.selectedIndex > 0) {
                    siteContactName.value = clientContactSelect.options[clientContactSelect.selectedIndex].dataset.name || '';
                }
                if (siteContactPhone && clientPhone) siteContactPhone.value = clientPhone.value;
            }
        });
    }

    // 4. GPS Geolocation
    const btnGetGps = doc.getElementById('btn_get_gps');
    const gpsInput = doc.getElementById('gps_coordinates');
    if (btnGetGps && gpsInput) {
        btnGetGps.addEventListener('click', function() {
            if (navigator.geolocation) {
                btnGetGps.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';
                navigator.geolocation.getCurrentPosition(
                    function(pos) {
                        gpsInput.value = `${pos.coords.latitude.toFixed(6)}, ${pos.coords.longitude.toFixed(6)}`;
                        btnGetGps.innerHTML = '<i class="bi bi-geo-alt-fill" style="color: #91378e;"></i>';
                    },
                    function(err) {
                        alert('Impossible de récupérer la position GPS : ' + err.message);
                        btnGetGps.innerHTML = '<i class="bi bi-geo-alt-fill" style="color: #91378e;"></i>';
                    }
                );
            } else {
                alert('La géolocalisation n\'est pas supportée par votre navigateur.');
            }
        });
    }

    // 5. Dynamic Sub-Types filtering
    const typeSelect = doc.getElementById('tech_intervention_type_id');
    const subTypeSelect = doc.getElementById('tech_intervention_sub_type_id');

    function filterSubTypes() {
        if (!typeSelect || !subTypeSelect) return;
        const selectedParentId = typeSelect.value;
        const options = subTypeSelect.querySelectorAll('option[data-parent-id]');
        let firstMatch = null;
        options.forEach(opt => {
            if (opt.dataset.parentId === selectedParentId) {
                opt.style.display = '';
                if (!firstMatch) firstMatch = opt;
            } else {
                opt.style.display = 'none';
                if (opt.selected) opt.selected = false;
            }
        });
        if (firstMatch && subTypeSelect.value === '') {
            firstMatch.selected = true;
        }
    }
    if (typeSelect) {
        typeSelect.addEventListener('change', filterSubTypes);
        filterSubTypes();
    }

    // 6. Checklist Management
    const btnAddTaskModal = doc.getElementById('btn_add_task_modal');
    const addTaskModalEl = doc.getElementById('addTaskModal');
    const btnConfirmAddTask = doc.getElementById('btn_confirm_add_task');
    const modalTaskInput = doc.getElementById('modal_task_input');
    const checklistContainer = doc.getElementById('checklist_container');

    let addTaskModal = null;
    if (addTaskModalEl && typeof bootstrap !== 'undefined') {
        addTaskModal = new bootstrap.Modal(addTaskModalEl);
    }

    if (btnAddTaskModal && addTaskModal) {
        btnAddTaskModal.addEventListener('click', function() {
            if (modalTaskInput) modalTaskInput.value = '';
            addTaskModal.show();
            setTimeout(() => { if (modalTaskInput) modalTaskInput.focus(); }, 400);
        });
    }

    if (btnConfirmAddTask) {
        btnConfirmAddTask.addEventListener('click', function() {
            const val = modalTaskInput ? modalTaskInput.value.trim() : '';
            if (!val) return;
            const newIndex = checklistContainer ? checklistContainer.querySelectorAll('.task-row').length : 0;
            const row = document.createElement('div');
            row.className = 'task-row d-flex align-items-center justify-content-between py-1 px-1 rounded-2';
            row.innerHTML = `
                <div class="d-flex align-items-center gap-2 mb-0 flex-grow-1">
                    <input class="form-check-input custom-checkbox" type="checkbox"
                        id="task_check_${newIndex}" onchange="updateTaskRow(this)">
                    <input type="hidden" name="tasks[${newIndex}][is_completed]" class="task-completed-val" value="0">
                    <input type="hidden" name="tasks[${newIndex}][task]" class="task-text-val" value="${val.replace(/"/g, '&quot;')}">
                    <label class="task-label-text mb-0 cursor-pointer" for="task_check_${newIndex}">
                        ${val}
                    </label>
                </div>
                <button type="button" class="btn btn-link btn-sm text-danger opacity-50 hover-opacity-100 p-0 ms-2 remove-task-btn" onclick="removeTaskRow(this)">
                    <i class="bi bi-x"></i>
                </button>
            `;
            if (checklistContainer) checklistContainer.appendChild(row);
            if (addTaskModal) addTaskModal.hide();
        });
    }

    // 7. Materials Management Modal
    const btnAddMatModal = doc.getElementById('btn_add_material_modal');
    const addMatModalEl = doc.getElementById('addMaterialModal');
    const btnConfirmAddMat = doc.getElementById('btn_confirm_add_material');
    const modalMatSearch = doc.getElementById('modal_mat_search');
    const modalMatSuggestions = doc.getElementById('modal_mat_suggestions');
    const modalMatCustomName = doc.getElementById('modal_mat_custom_name');
    const modalMatCustomRef = doc.getElementById('modal_mat_custom_ref');
    const modalMatQty = doc.getElementById('modal_mat_qty');
    const modalMatLoc = doc.getElementById('modal_mat_location');
    const modalMatEquipmentId = doc.getElementById('modal_mat_equipment_id');
    const materialsTbody = doc.getElementById('materials_tbody');

    let addMatModal = null;
    if (addMatModalEl && typeof bootstrap !== 'undefined') {
        addMatModal = new bootstrap.Modal(addMatModalEl);
    }

    if (btnAddMatModal && addMatModal) {
        btnAddMatModal.addEventListener('click', function() {
            if (modalMatSearch) modalMatSearch.value = '';
            if (modalMatCustomName) modalMatCustomName.value = '';
            if (modalMatCustomRef) modalMatCustomRef.value = '';
            if (modalMatQty) modalMatQty.value = '1';
            if (modalMatEquipmentId) modalMatEquipmentId.value = '';
            if (modalMatSuggestions) modalMatSuggestions.style.display = 'none';
            addMatModal.show();
        });
    }

    if (modalMatSearch) {
        let matDebounce;
        modalMatSearch.addEventListener('input', function() {
            clearTimeout(matDebounce);
            const q = this.value.trim();
            if (q.length < 2) {
                if (modalMatSuggestions) modalMatSuggestions.style.display = 'none';
                return;
            }
            matDebounce = setTimeout(() => {
                fetch(`/equipments/search?q=${encodeURIComponent(q)}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(r => r.json())
                .then(data => {
                    if (!modalMatSuggestions) return;
                    modalMatSuggestions.innerHTML = '';
                    if (!data || data.length === 0) {
                        modalMatSuggestions.style.display = 'none';
                        return;
                    }
                    data.forEach(eq => {
                        const li = document.createElement('li');
                        li.className = 'list-group-item list-group-item-action cursor-pointer p-2 small';
                        li.innerHTML = `<strong>${eq.name}</strong> <span class="text-muted ms-1">(${eq.reference || ''})</span>`;
                        li.addEventListener('click', function() {
                            if (modalMatSearch) modalMatSearch.value = eq.name;
                            if (modalMatCustomName) modalMatCustomName.value = eq.name;
                            if (modalMatCustomRef) modalMatCustomRef.value = eq.reference || '';
                            if (modalMatEquipmentId) modalMatEquipmentId.value = eq.id;
                            modalMatSuggestions.style.display = 'none';
                        });
                        modalMatSuggestions.appendChild(li);
                    });
                    modalMatSuggestions.style.display = 'block';
                });
            }, 250);
        });
    }

    if (btnConfirmAddMat) {
        btnConfirmAddMat.addEventListener('click', function() {
            const name = (modalMatCustomName ? modalMatCustomName.value.trim() : '') || (modalMatSearch ? modalMatSearch.value.trim() : '');
            const ref = modalMatCustomRef ? modalMatCustomRef.value.trim() : '';
            const qty = modalMatQty ? (modalMatQty.value || 1) : 1;
            const eqId = modalMatEquipmentId ? modalMatEquipmentId.value : '';
            const locVal = modalMatLoc ? modalMatLoc.value : 'client';
            const isAtClient = locVal === 'client';
            const warehouseId = isAtClient ? '' : locVal.replace('wh_', '');
            const selectedOpt = modalMatLoc ? modalMatLoc.options[modalMatLoc.selectedIndex] : null;
            const locLabel = selectedOpt ? selectedOpt.textContent.trim() : (isAtClient ? 'Chez le client' : 'À prendre au bureau Novalyo');

            if (!name) {
                alert('Veuillez renseigner le nom du matériel');
                return;
            }

            const newIdx = materialsTbody ? materialsTbody.querySelectorAll('.mat-row').length : 0;
            const badgeClass = isAtClient ? 'badge-client' : 'badge-novalyo';

            const tr = document.createElement('tr');
            tr.className = 'mat-row';
            tr.innerHTML = `
                <td class="fw-medium text-dark">
                    ${name}
                    <input type="hidden" name="materials[${newIdx}][equipment_id]" value="${eqId}">
                    <input type="hidden" name="materials[${newIdx}][custom_name]" value="${name.replace(/"/g, '&quot;')}">
                    <input type="hidden" name="materials[${newIdx}][custom_reference]" value="${ref.replace(/"/g, '&quot;')}">
                    <input type="hidden" name="materials[${newIdx}][quantity]" value="${qty}">
                    <input type="hidden" name="materials[${newIdx}][is_at_client]" class="mat-is-at-client" value="${isAtClient ? '1' : '0'}">
                    <input type="hidden" name="materials[${newIdx}][warehouse_id]" class="mat-warehouse-id" value="${warehouseId}">
                </td>
                <td class="text-muted">${ref}</td>
                <td class="text-center">${qty}</td>
                <td>
                    <div class="dropdown">
                        <button class="badge-pill-loc ${badgeClass} dropdown-toggle text-truncate" style="max-width: 210px;" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            ${locLabel}
                        </button>
                        <ul class="dropdown-menu shadow-sm border rounded-3 p-1">
                            <li><a class="dropdown-item small rounded-2 py-1 location-option" href="#" data-at-client="1" data-warehouse-id="" data-label="Chez le client">Chez le client</a></li>
                            <li><hr class="dropdown-divider my-1"></li>
                            ${Array.from(modalMatLoc.options).filter(o => o.value !== 'client').map(o => `<li><a class="dropdown-item small rounded-2 py-1 location-option" href="#" data-at-client="0" data-warehouse-id="${o.value.replace('wh_', '')}" data-label="${o.textContent.trim()}">${o.textContent.trim()}</a></li>`).join('')}
                        </ul>
                    </div>
                </td>
                <td class="text-end">
                    <button type="button" class="btn btn-link btn-sm text-danger opacity-50 hover-opacity-100 p-0 remove-mat-btn" onclick="removeTableRow(this)">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
            if (materialsTbody) materialsTbody.appendChild(tr);
            if (addMatModal) addMatModal.hide();
        });
    }

    // 8. Tools Management Modal
    const btnAddToolModal = doc.getElementById('btn_add_tool_modal');
    const addToolModalEl = doc.getElementById('addToolModal');
    const btnConfirmAddTool = doc.getElementById('btn_confirm_add_tool');
    const modalToolName = doc.getElementById('modal_tool_name');
    const modalToolRef = doc.getElementById('modal_tool_ref');
    const modalToolQty = doc.getElementById('modal_tool_qty');
    const toolsTbody = doc.getElementById('tools_tbody');

    let addToolModal = null;
    if (addToolModalEl && typeof bootstrap !== 'undefined') {
        addToolModal = new bootstrap.Modal(addToolModalEl);
    }

    if (btnAddToolModal && addToolModal) {
        btnAddToolModal.addEventListener('click', function() {
            if (modalToolName) modalToolName.value = '';
            if (modalToolRef) modalToolRef.value = '';
            if (modalToolQty) modalToolQty.value = '1';
            addToolModal.show();
        });
    }

    if (btnConfirmAddTool) {
        btnConfirmAddTool.addEventListener('click', function() {
            const name = modalToolName ? modalToolName.value.trim() : '';
            const ref = modalToolRef ? modalToolRef.value.trim() : '';
            const qty = modalToolQty ? (modalToolQty.value || 1) : 1;

            if (!name) {
                alert('Veuillez renseigner le nom de l\'outil ou logiciel');
                return;
            }

            const newIdx = toolsTbody ? toolsTbody.querySelectorAll('.tool-row').length : 0;
            const tr = document.createElement('tr');
            tr.className = 'tool-row';
            tr.innerHTML = `
                <td class="fw-medium text-dark">
                    ${name}
                    <input type="hidden" name="tools[${newIdx}][custom_name]" value="${name.replace(/"/g, '&quot;')}">
                    <input type="hidden" name="tools[${newIdx}][custom_reference]" value="${ref.replace(/"/g, '&quot;')}">
                    <input type="hidden" name="tools[${newIdx}][quantity]" value="${qty}">
                </td>
                <td class="text-muted">${ref}</td>
                <td class="text-center">${qty}</td>
                <td class="text-end">
                    <button type="button" class="btn btn-link btn-sm text-danger opacity-50 hover-opacity-100 p-0 remove-tool-btn" onclick="removeTableRow(this)">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
            if (toolsTbody) toolsTbody.appendChild(tr);
            if (addToolModal) addToolModal.hide();
        });
    }

    // 9. Location dropdown option clicked
    doc.addEventListener('click', function(e) {
        const opt = e.target.closest('.location-option');
        if (opt) {
            e.preventDefault();
            const atClient = opt.dataset.atClient === '1';
            const whId = opt.dataset.warehouseId || '';
            const label = opt.dataset.label || (atClient ? 'Chez le client' : 'À prendre au bureau Novalyo');
            const row = opt.closest('.mat-row');
            if (row) {
                const isAtClientInput = row.querySelector('.mat-is-at-client');
                const warehouseIdInput = row.querySelector('.mat-warehouse-id');
                const badgeBtn = row.querySelector('.badge-pill-loc');

                if (isAtClientInput) isAtClientInput.value = atClient ? '1' : '0';
                if (warehouseIdInput) warehouseIdInput.value = whId;

                if (badgeBtn) {
                    badgeBtn.textContent = label;
                    badgeBtn.className = `badge-pill-loc ${atClient ? 'badge-client' : 'badge-novalyo'} dropdown-toggle text-truncate`;
                }
            }
        }
    });
};

function updateTaskRow(chk) {
    const parent = chk.closest('.task-row');
    if (parent) {
        const completedInput = parent.querySelector('.task-completed-val');
        if (completedInput) completedInput.value = chk.checked ? '1' : '0';
    }
}

function removeTaskRow(btn) {
    const row = btn.closest('.task-row');
    if (row) row.remove();
}

function removeTableRow(btn) {
    const row = btn.closest('tr');
    if (row) row.remove();
}
</script>
