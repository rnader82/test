<div class="modal-header justify-content-end">
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <h5>{{ isset($status->id) ? __('locale.edit').' '.__('locale.status') : __('locale.create').' '.__('locale.status') }}</h5>
    @include('statuses.form', ['status' => $status, 'action' => $action, 'method' => $method])
</div>