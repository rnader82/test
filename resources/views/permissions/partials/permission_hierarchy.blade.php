@php
    $indent = $indent ?? 0.5;
    $isChecked = $rolePermissions->contains($permission->id);
@endphp
@if ($permission->children->isNotEmpty())

    <!-- Display the parent permission title -->
    <tr>
        <td class="text-nowrap fw-medium" style="padding-left: {{ $indent * 20 }}px;">
             {{ $permission->translated_name }}
        </td>
        <td>

            <div class="form-check mb-0 mt-1 me-4 me-lg-12">
                <input class="form-check-input permission-checkbox" type="checkbox" id="permission{{ $permission->id }}" name="permissions[]"
                    value="{{ $permission->id }}" data-permission-id="{{ $permission->id }}" data-parent-id="{{ $permission->parent_id }}" {{ $isChecked ? 'checked' : '' }} />
                <label class="form-check-label" for="permission{{ $permission->id }}">
                    {{ __('locale.select') }}
                </label>
            </div>
        </td>
    </tr>

    @foreach ($permission->children as $child)
        @include('permissions.partials.permission_hierarchy', [
            'permission' => $child,
            'indent' => $indent + 1,
            'rolePermissions' => $rolePermissions,
        ])
    @endforeach
@else
    @php
        if (is_null($permission->parent_id)) {
            $indent = 0.5;
        }
    @endphp

    @include('permissions.partials.permission_row', [
        'permission' => $permission,
        'indent' => $indent,
        'rolePermissions' => $rolePermissions,
    ])
@endif
