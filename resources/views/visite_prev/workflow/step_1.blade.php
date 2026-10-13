@extends('layouts.app')

@section('content')
<div class="container">
    <h3>{{ __('visite_prev.step_1_title') }}</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('vp-visit-workflow.update', $vp_visit->id) }}?step={{ $step }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="client_id">{{ __('visite_prev.site') }}</label>
            <select name="client_id" id="client_id" class="form-select" required>
                <option value="">{{ __('visite_prev.select_site') }}</option>
                @foreach(\App\Models\Client::orderBy('raison_sociale')->get() as $client)
                    <option value="{{ $client->id }}"
                        {{ old('client_id', $vp_visit->client_id) == $client->id ? 'selected' : '' }}>
                        {{ $client->raison_sociale }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="installer_id">{{ __('visite_prev.installer') }}</label>
            <select name="installer_id" id="installer_id" class="form-select" required>
                @foreach(\App\Models\Installer::all() as $installer)
                    <option value="{{ $installer->id }}"
                        {{ old('installer_id', $vp_visit->installer_id) == $installer->id ? 'selected' : '' }}>
                        {{ $installer->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="assigned_user_ids">{{ __('locale.lbl_assigned_users') }}</label>
            @php
                $selectedAssignedUsers = old('assigned_user_ids', $vp_visit->assignedUsers->pluck('id')->toArray());
            @endphp
            <select
                name="assigned_user_ids[]"
                id="assigned_user_ids"
                class="form-select js-assigned-users-select"
                multiple
                required
                data-placeholder="{{ __('locale.lbl_select') }}"
            >
                @foreach(\App\Models\User::orderBy('name')->get(['id', 'name']) as $user)
                    <option value="{{ $user->id }}" {{ in_array($user->id, $selectedAssignedUsers, true) ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">{{ __('visite_prev.next') }}</button>
    </form>
</div>
@endsection

@section('scripts')
    @parent
    @include('visite_prev.partials.scripts')
@endsection
