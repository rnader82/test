@php
    $userPermissions = $userPermissions ?? [];
    $indent = $indent ?? 0; // Indentation level for nested permissions

@endphp

<!-- Display Parent Permission -->
<tr>
    <td class="text-nowrap fw-medium" style="padding-left: {{ $indent * 20 }}px;">
        {{ $permission->translated_name }}
    </td>
    <td>
        <div class="form-check mb-0 mt-1 me-4 me-lg-12">
            <input class="form-check-input" type="checkbox" id="permission{{ $permission->id }}" name="permissions[]"
                value="{{ $permission->id }}" {{ in_array($permission->id, $userPermissions) ? 'checked' : '' }} />
            <label class="form-check-label" for="permission{{ $permission->id }}">
                Select
            </label>
        </div>
    </td>
</tr>

<!-- Recursively Display Child Permissions (if any) -->
@if ($permission->children->isNotEmpty())
    @foreach ($permission->children as $childPermission)
        @include('permissions.partials.user_permission_hierarchy', [
            'permission' => $childPermission,
            'userPermissions' => $userPermissions,
            'indent' => $indent + 1,
        ])
    @endforeach
@endif
