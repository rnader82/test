@php
    $issueClientsCollection = isset($issueClients) ? collect($issueClients) : collect();
    $issueOriginesCollection = isset($issueOrigines) ? collect($issueOrigines) : collect();
    $issueCategoriesCollection = isset($issueCategories) ? collect($issueCategories) : collect();
    $issueTypeOptions = $issueTypeOptions ?? [];
    $issueCriticityOptions = $issueCriticityOptions ?? [];

    $categoryOptionsByType = $issueCategoriesCollection
        ->groupBy(function ($category) {
            return (string) data_get($category, 'issue_type_id', '');
        })
        ->map(function ($group) {
            return $group
                ->map(function ($category) {
                    return [
                        'id' => data_get($category, 'id'),
                        'name' => data_get($category, 'translated_name', data_get($category, 'name')),
                        'equipment_type_id' => data_get($category, 'equipment_type_id'),
                        'equipment_type_name' => data_get($category, 'equipment_type_name'),
                    ];
                })
                ->filter(function ($option) {
                    return $option['id'] !== null && $option['name'] !== null && $option['name'] !== '';
                })
                ->values()
                ->toArray();
        })
        ->toArray();

    $selectedType = old('issue_type_id', '');
    $selectedCategory = old('category_id', '');
    $initialCategoryOptions = $categoryOptionsByType[(string) $selectedType] ?? [];


    $quickIssueClients = $issueClientsCollection->map(function ($client) {
        $city = $client->ville ?? $client->city ?? '';
        $name = $client->raison_sociale ?? '';
        $identifier = $client->id ?? null;
        $postalCode = $client->code_postal ?? $client->postal_code ?? '';

        $searchParts = array_filter([
            $name,
            $city,
            $postalCode,
            $identifier !== null ? (string) $identifier : null,
        ], static fn ($value) => $value !== null && $value !== '');

        return [
            'id' => $identifier,
            'raison_sociale' => $name,
            'ville' => $city,
            'search_terms' => trim(implode(' ', $searchParts)),
        ];
    })->values();
@endphp

<div class="modal fade" id="quickIssueModal" tabindex="-1" aria-hidden="true" data-quick-issue-modal>
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('locale.lbl_create_issue') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('locale.cancel') }}"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger d-none" data-quick-issue-errors></div>
                <div class="alert alert-success d-none" data-quick-issue-success></div>
                <form action="{{ route('issues.store') }}" method="POST" data-quick-issue-form id="quickIssueForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="quick_issue_client_search" class="form-label">{{ __('locale.lbl_client') }}</label>
                            <div
                                class="position-relative has-validation"
                                data-autocomplete-container
                                data-quick-issue-client>
                                <input
                                    type="text"
                                    name="client_search"
                                    id="quick_issue_client_search"
                                    class="form-control"
                                    placeholder="{{ __('locale.lbl_select_client') }}"
                                    autocomplete="off"
                                    data-autocomplete-input
                                    data-autocomplete-url="{{ route('clients.search') }}"
                                    data-autocomplete-hidden-input="#quick_issue_client_id"
                                    data-autocomplete-label-key="raison_sociale"
                                    data-autocomplete-value-key="id"
                                    data-autocomplete-secondary-key="ville"
                                    data-autocomplete-show-id="true"
                                    data-autocomplete-max-results="10"
                                    data-autocomplete-min-length="0"
                                    data-autocomplete-local-data="#quick-issue-client-data"
                                    data-quick-issue-client-search
                                    data-validation-message="{{ __('validation.required', ['attribute' => strtolower(__('locale.lbl_client'))]) }}"
                                    required>
                                <input type="hidden" name="client_id" id="quick_issue_client_id" data-quick-issue-client-id>
                                <div
                                    class="list-group position-absolute w-100 shadow-sm bg-white"
                                    data-autocomplete-results
                                    style="max-height: 240px; overflow-y: auto; z-index: 1050;"
                                    hidden>
                                </div>
                                <div class="invalid-feedback">
                                    {{ __('validation.required', ['attribute' => strtolower(__('locale.lbl_client'))]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="quick_issue_origine_id" class="form-label">{{ __('locale.lbl_origine') }}</label>
                            <select name="origine_id" id="quick_issue_origine_id" class="form-select" required>
                                <option value="">{{ __('locale.lbl_select_origin') }}</option>
                                @foreach($issueOriginesCollection as $origine)
                                    <option value="{{ $origine->id }}">{{ $origine->translated_name ?? $origine->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="quick_issue_type" class="form-label">{{ __('locale.lbl_type') }}</label>
                            <select name="issue_type_id" id="quick_issue_type" class="form-select" required>
                                <option value="" {{ $selectedType ? '' : 'selected' }}>{{ __('locale.lbl_select_type') }}</option>
                                @foreach($issueTypeOptions as $value => $label)
                                    <option value="{{ $value }}" {{ (string) $selectedType === (string) $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="quick_issue_category_id" class="form-label">{{ __('locale.lbl_category') }}</label>
                            <select
                                name="category_id"
                                id="quick_issue_category_id"
                                class="form-select"
                                required
                                data-categories='@json($categoryOptionsByType)'
                                data-placeholder="{{ __('locale.lbl_select_category') }}"
                                data-selected="{{ $selectedCategory }}"
                                {{ $selectedType ? '' : 'disabled' }}
                            >
                                <option value="">{{ __('locale.lbl_select_category') }}</option>
                                @foreach($initialCategoryOptions as $categoryOption)
                                    <option value="{{ $categoryOption['id'] }}" {{ (string) $selectedCategory === (string) $categoryOption['id'] ? 'selected' : '' }}>{{ $categoryOption['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="quick_issue_criticity_internal" class="form-label">{{ __('locale.lbl_criticity_internal') }}</label>
                            <select name="issue_criticity_internal_id" id="quick_issue_criticity_internal" class="form-select" required>
                                @foreach($issueCriticityOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="quick_issue_criticity_client" class="form-label">{{ __('locale.lbl_criticity_client') }}</label>
                            <select name="issue_criticity_client_id" id="quick_issue_criticity_client" class="form-select" required>
                                @foreach($issueCriticityOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="quick_issue_severity" class="form-label">{{ __('locale.lbl_severity') }}</label>
                            <select name="issue_severity_id" id="quick_issue_severity" class="form-select">
                                <option value="">{{ __('locale.lbl_select_severity') }}</option>
                                @foreach($issueSeverityOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="quick_issue_subject" class="form-label">{{ __('locale.lbl_subject') }}</label>
                            <input type="text" name="subject" id="quick_issue_subject" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label for="quick_issue_description" class="form-label">{{ __('locale.description') }}</label>
                            <textarea name="description" id="quick_issue_description" class="form-control" rows="4" required data-quick-issue-description></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="internal_notes" data-quick-issue-internal-notes>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('locale.cancel') }}</button>
                <button type="submit" class="btn btn-primary" form="quickIssueForm" data-quick-issue-submit>{{ __('locale.btn_create_issue') }}</button>
            </div>
            @if($quickIssueClients->isNotEmpty())
                <template id="quick-issue-client-data">@json($quickIssueClients)</template>
            @endif
        </div>
    </div>
</div>