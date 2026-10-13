<div class="modal-header justify-content-between align-items-center">
    <h5 class="modal-title fw-bold mb-0">
        <i class="fa-solid fa-screwdriver-wrench me-2 text-primary"></i>
        {{ $method === 'POST' ? (__('locale.lbl_create_technical_intervention') ?? 'Créer une intervention') : (__('locale.lbl_edit_technical_intervention') ?? 'Modifier l\'intervention') }}
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body p-4">
    @include('tech_intervs.form', [
        'technicalIntervention' => $technicalIntervention,
        'users' => $users,
        'priorities' => $priorities,
        'severities' => $severities,
        'types' => $types,
        'subTypes' => $subTypes ?? collect(),
        'metiers' => $metiers,
        'statuses' => $statuses,
        'states' => $states,
        'warehouses' => $warehouses ?? collect(),
        'issues' => $issues,
        'arr_preset' => $arr_preset ?? [],
        'action' => $action,
        'method' => $method,
        'isModal' => true,
    ])
</div>