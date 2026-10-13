<div class="modal-header justify-content-end">
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body p-0">

    <h1>{{ __('locale.lbl_edit_permission_for_role') }}: {{ $role->translated_name }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <p>{{ __('locale.lbl_error_input') }}:</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="edit-permissions-form" action="{{ route('roles.updatePermissions', $role->id) }}" method="POST">
        @csrf

        <input type="text" id="permissionSearch" class="form-control mb-3" placeholder="{{ __('locale.search') }}">

        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
        <table id="permissionsTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>{{ __('locale.lbl_permission') }}</th>
                    <th>{{ __('locale.assign') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permissions as $permission)
                    @include('permissions.partials.permission_hierarchy', [
                        'permission' => $permission,
                        'rolePermissions' => $rolePermissions,
                    ])
                @endforeach
            </tbody>
        </table>
        </div>
        <button type="submit" class="btn btn-primary">{{ __('locale.btn_update_permission') }}</button>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
    <!--/ Add role form -->
</div>
