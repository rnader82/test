<form id="form_submition" action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif
    @php
        $prefilledClient = isset($arr_preset['client']) ? $arr_preset['client'] : null;

        $issueTypeOptions = $issueTypes ?? [];
        $selectedType = old('issue_type_id', isset($issue) ? $issue->issue_type_id : null);

        $issueCriticityOptions = $issueCriticities ?? [];
        $criticityValues = array_keys($issueCriticityOptions);
        $selectedCriticityInternal = old('issue_criticity_internal_id', isset($issue) ? $issue->issue_criticity_internal_id : ($criticityValues[0] ?? null));
        $selectedCriticityClient = old('issue_criticity_client_id', isset($issue) ? $issue->issue_criticity_client_id : ($criticityValues[0] ?? null));

        $issueSeverityOptions = $issueSeverities ?? [];
        $selectedSeverity = old('issue_severity_id', isset($issue) ? $issue->issue_severity_id : null);

        $issueCategoriesCollection = isset($issueCategories) ? collect($issueCategories) : collect();
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

        $selectedCategoryId = old('category_id', isset($issue) ? $issue->category_id : null);
        $initialCategoryOptions = $categoryOptionsByType[(string) $selectedType] ?? [];
    @endphp

    <div class="row">
    <!-- Client Search -->
    <div class="col-md-6 mb-3 position-relative">
        <label for="client_search" class="form-label">{{ __('locale.lbl_client') }}</label>
       <div class="d-flex align-items-center">
            <input type="text" id="client_search" class="form-control" autocomplete="off"
                value="{{ old('client_search', $prefilledClient?->raison_sociale ?? $issue->client->raison_sociale ?? '') }}">
            <i id="client_warning"
                class="bi bi-exclamation-triangle-fill text-danger ms-2 {{ ($issue->client && ($issue->client->concurrent || $issue->client->default_payment)) ? '' : 'd-none' }}"
                data-bs-toggle="tooltip" data-bs-html="true"
                @if($issue->client)
                    title="{!! implode('<br>', array_filter([
                        $issue->client->concurrent ? __('locale.lbl_concurrent') : null,
                        $issue->client->default_payment ? __('locale.default_payment') : null,
                    ])) !!}"
                @endif></i>
        </div>
        <div id="client_address" class="small text-muted">
            @if($issue->client)
                {{ trim($issue->client->adresse_1.' '.$issue->client->adresse_2) }}@if($issue->client->code_postal || $issue->client->ville) - {{ trim($issue->client->code_postal.' '.$issue->client->ville) }}@endif
            @endif
        </div>
        <input type="hidden" name="client_id" id="client_id" value="{{ old('client_id', $prefilledClient?->id ?? $issue->client_id ?? '') }}">
        <ul id="client_suggestions" class="list-group position-absolute w-100 bg-white autocomplete-suggestions" style="top:100%; left:0; display:none;"></ul>
    </div>

    <!-- Origine Dropdown -->
    <div class="col-md-6 mb-3">
        <label for="origine_id" class="form-label">{{ __('locale.lbl_origine') }}</label>
        <select name="origine_id" id="origine_id" class="form-control select2" style="width:100%" required>
            <option value="">{{ __('locale.lbl_select_origin') }}</option>
            @foreach($origines as $origine)
                <option value="{{ $origine->id }}" {{ old('origine_id', $issue->origine_id ?? '') == $origine->id ? 'selected' : '' }}>{{ $origine->translated_name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Type Dropdown -->
    <div class="col-md-6 mb-3">
        <label for="issue_type_id" class="form-label">{{ __('locale.lbl_type') }}</label>
        <select name="issue_type_id" id="issue_type_id" class="form-control" required>
            <option value="" {{ $selectedType ? '' : 'selected' }}>{{ __('locale.lbl_select_type') }}</option>
            @foreach ($issueTypeOptions as $value => $type)
                <option value="{{ $value }}" {{ (string) $selectedType === (string) $value ? 'selected' : '' }}>
                    {{ $type['label'] ?? $type['name'] ?? '' }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Category Dropdown -->
    <div class="col-md-6 mb-3">
        <label for="category_id" class="form-label">{{ __('locale.lbl_category') }}</label>
        <select
            name="category_id"
            id="category_id"
            class="form-select"
            required
            data-categories='@json($categoryOptionsByType)'
            data-selected="{{ $selectedCategoryId ?? '' }}"
            data-placeholder="{{ __('locale.lbl_select_category') }}"
            {{ $selectedType ? '' : 'disabled' }}
        >
            <option value="">{{ __('locale.lbl_select_category') }}</option>
            @foreach ($initialCategoryOptions as $categoryOption)
                <option value="{{ $categoryOption['id'] }}" {{ (string) $selectedCategoryId === (string) $categoryOption['id'] ? 'selected' : '' }}>
                    {{ $categoryOption['name'] }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Status Dropdown -->
    <div class="col-md-6 mb-3">
        <label for="status_id" class="form-label">{{ __('locale.lbl_status') }}</label>
        <select name="status_id" id="status_id" class="form-select select-status" required>
            @foreach($statuses as $status)
                <option value="{{ $status->id }}" data-color="{{ $status->color }}" data-text-color="{{ $status->text_color }}" {{ old('status_id', $issue->status_id ?? '') == $status->id ? 'selected' : '' }}>
                    {{ $status->translated_name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label for="state_id" class="form-label">{{ __('locale.lbl_state') }}</label>
        <select name="state_id" id="state_id" class="form-select select2" style="width:100%" required>
            <option value="">{{ __('locale.menu_states') }}</option>
            @foreach($states as $state)
                <option value="{{ $state->id }}" {{ old('state_id', $issue->state_id ?? '') == $state->id ? 'selected' : '' }}>
                    {{ $state->translated_name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Criticity Internal -->
    <div class="col-md-6 mb-3">
        <label for="issue_criticity_internal_id" class="form-label">{{ __('locale.lbl_criticity_internal') }}</label>
        <select name="issue_criticity_internal_id" id="issue_criticity_internal_id" class="form-control" required>
            @foreach ($issueCriticityOptions as $value => $criticity)
                <option value="{{ $value }}" {{ (string) $selectedCriticityInternal === (string) $value ? 'selected' : '' }}>
                    {{ $criticity['label'] ?? $criticity['name'] ?? '' }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Criticity Client -->
    <div class="col-md-6 mb-3">
        <label for="issue_criticity_client_id" class="form-label">{{ __('locale.lbl_criticity_client') }}</label>
        <select name="issue_criticity_client_id" id="issue_criticity_client_id" class="form-control" required>
            @foreach ($issueCriticityOptions as $value => $criticity)
                <option value="{{ $value }}" {{ (string) $selectedCriticityClient === (string) $value ? 'selected' : '' }}>
                    {{ $criticity['label'] ?? $criticity['name'] ?? '' }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Severity -->
    <div class="col-md-6 mb-3">
        <label for="issue_severity_id" class="form-label">{{ __('locale.lbl_severity') }}</label>
        <select name="issue_severity_id" id="issue_severity_id" class="form-control">
            <option value="">{{ __('locale.lbl_select_severity') }}</option>
            @foreach ($issueSeverityOptions as $value => $severity)
                <option value="{{ $value }}" {{ (string) $selectedSeverity === (string) $value ? 'selected' : '' }}>
                    {{ $severity['label'] ?? $severity['name'] ?? '' }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Groups Multi -->
    <div class="col-md-6 mb-3">
        <label for="issues_groups" class="form-label">{{ __('locale.lbl_ticket_groups') }}</label>
        <select name="issues_groups[]" id="issues_groups" class="form-control select2" multiple style="width:100%">
            @foreach($issues_groups as $issues_group)
                <option value="{{ $issues_group->id }}" {{ isset($issue) && $issue->issues_groups->contains($issues_group->id) ? 'selected' : '' }}>{{ $issues_group->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Users Multi -->
    <div class="col-md-6 mb-3">
        <label for="users" class="form-label">{{ __('locale.lbl_user_affected') }}</label>
        <select name="users[]" id="users" class="form-control select2" multiple style="width:100%">
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ isset($issue) && $issue->users->contains($user->id) ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label for="subject" class="form-label">{{ __('locale.lbl_subject') }}</label>
        <input type="text" name="subject" id="subject" class="form-control" value="{{ old('subject', $issue->subject ?? '') }}" required>
    </div>
    <div class="col-12 mb-3">
        <label for="description" class="form-label">{{ __('locale.description') }}</label>
        <div id="quill-description" style="height: 100px;">{!! old('description', $issue->description ?? '') !!}</div>
        <input type="hidden" name="description" id="description">
    </div>

    <div class="col-md-6 mb-3">
        <label for="documents" class="form-label">{{ __('locale.lbl_attach_document') }}</label>
        <input type="file" name="documents" id="documents" class="form-control" {{ $method === 'POST' ? 'required' : '' }}>
    </div>
    <div class="col-md-6 mt-auto mb-3">
        <button type="submit" class="btn btn-primary float-end">
            {{ $method === 'POST' ? __('locale.btn_create_issue') : __('locale.btn_update_issue') }}
        </button>
    </div>
    </div>
</form>