<div class="mb-3">
    <label for="client_id" class="form-label">{{ __('locale.visite_prev.site') }}</label>
    <select
        name="client_id"
        id="client_id"
        class="form-select js-vp-client-select"
        data-placeholder="{{ __('locale.visite_prev.select_site') }}"
        data-ajax-url="{{ route('clients.search') }}"
        required
    >
        @php
            $initialClient = $selectedClient ?? null;
        @endphp
        @if($initialClient)
            <option value="{{ $initialClient->id }}" selected>
                {{ $initialClient->raison_sociale }}
            </option>
        @else
            <option value=""></option>
        @endif
    </select>
</div>

<div class="mb-3">
    <label for="installer_id" class="form-label">{{ __('locale.visite_prev.installer') }}</label>
    <select name="installer_id" id="installer_id" class="form-select" required>
        <option value="">{{ __('locale.visite_prev.select_installer') }}</option>
        @foreach($installers as $installer)
            <option value="{{ $installer->id }}"
                {{ old('installer_id', optional($vpVisit)->installer_id) == $installer->id ? 'selected' : '' }}>
                {{ $installer->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="assigned_user_ids" class="form-label">{{ __('locale.lbl_assigned_users') }}</label>
    @php
        $selectedAssignedUserIds = old('assigned_user_ids');

        if (!is_array($selectedAssignedUserIds)) {
            $selectedAssignedUserIds = $vpVisit?->assignedUsers?->pluck('id')->toArray() ?? [];
        }
    @endphp
    <select
        name="assigned_user_ids[]"
        id="assigned_user_ids"
        class="form-select js-assigned-users-select"
        multiple
        required
        data-placeholder="{{ __('locale.lbl_select') }}"
    >
        @foreach($users as $user)
            <option value="{{ $user->id }}" {{ in_array($user->id, $selectedAssignedUserIds, true) ? 'selected' : '' }}>
                {{ $user->name }}
            </option>
        @endforeach
    </select>
</div>
