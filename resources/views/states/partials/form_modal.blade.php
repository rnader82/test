<div class="modal-header justify-content-end">
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <h5>{{ isset($state->id) ? __('locale.btn_edit').' '.__('locale.menu_states') : __('locale.btn_create').' '.__('locale.menu_states') }}</h5>
    @include('states.form', ['state' => $state, 'statuses' => $statuses, 'action' => $action, 'method' => $method])
</div>