@php
    $rolePermissions = $rolePermissions ?? [];
    $isChecked = $rolePermissions->contains($permission->id);
    $indent = $indent ?? 0;
@endphp
<tr>
    <td class="text-nowrap fw-medium" style="padding-left: {{ $indent * 20 }}px">{{ $permission->translated_name }}</td>
    <td>
        <div class="form-check mb-0 mt-1 me-4 me-lg-12">
            <input class="form-check-input permission-checkbox" type="checkbox"
                id="permission{{ $permission->id }}" name="permissions[]"
                value="{{ $permission->id }}" data-permission-id="{{ $permission->id }}"
                data-parent-id="{{ $permission->parent_id }}" {{ $isChecked ? 'checked' : '' }} />
            <label class="form-check-label" for="permission{{ $permission->id }}">
                {{ __('locale.select') }}
            </label>
        </div>
    </td>
</tr>
