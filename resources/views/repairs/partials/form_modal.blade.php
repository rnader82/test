<div class="modal-header justify-content-end">
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">

    <form action="{{ $action }}" method="POST" data-repair-form>
        @csrf
        @if($method === 'PUT')
            @method('PUT')
        @endif
        @include('repairs.form', [
            'repair' => $repair,
            'issues' => $issues,
            'statuses' => $statuses,
            'equipmentTypes' => $equipmentTypes,
            'brands' => $brands,
            'equipments' => $equipments,
            'suppliers' => $suppliers ?? null,
            'warrantyTypes' => $warrantyTypes ?? null,
            'repairDiagnoses' => $repairDiagnoses ?? null,
        ])
        <div class="text-end mt-3">
            <button type="submit" class="btn btn-primary">{{ $method === 'POST' ? __('locale.save') : __('locale.update') }}</button>
        </div>
    </form>
</div>
@include('repairs.partials.quick_issue_modal', [
    'issueClients' => $issueClients,
    'issueOrigines' => $issueOrigines,
    'issueCategories' => $issueCategories,
    'issueTypeOptions' => $issueTypeOptions,
    'issueCriticityOptions' => $issueCriticityOptions,
])
@include('repairs.partials.equipment_search_modal')
@include('repairs.equipment_js')
@include('repairs.autocomplete_js')
@include('repairs.quick_issue_js')
<script>
    document.dispatchEvent(new Event('repairs:refresh-autocomplete'));
    document.dispatchEvent(new Event('repairs:refresh-quick-issue'));
    document.dispatchEvent(new Event('DOMContentLoaded'));
</script>