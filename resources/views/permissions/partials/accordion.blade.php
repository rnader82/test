@php
$hasChildren = $permission->children->count() > 0;
$collapseId = 'collapse-' . $permission->id;
@endphp

<div class="permission-node" id="node-{{ $permission->id }}">
    <div class="node-content" style="cursor: pointer;" onclick="window.location='{{ route('permissions.show', $permission->id) }}'">
        @if ($hasChildren)
            <button class="node-expand-btn collapsed" type="button" data-bs-toggle="collapse" 
                    data-bs-target="#{{ $collapseId }}" aria-expanded="false" 
                    aria-controls="{{ $collapseId }}" title="Afficher/Masquer les sous-autorisations"
                    onclick="event.stopPropagation();">
                <i class="bi bi-chevron-right"></i>
            </button>
        @else
            <div class="node-expand-spacer"></div>
        @endif

        <div class="node-icon">
            <i class="bi bi-shield-lock"></i>
        </div>

        <div class="d-flex align-items-center flex-grow-1 min-w-0">
            <span class="permission-name text-truncate">{{ $permission->translated_name }}</span>
            <span class="permission-slug">{{ $permission->slug }}</span>
        </div>

        <div class="node-actions ms-auto" onclick="event.stopPropagation();">
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary py-1 px-2 border-0 bg-transparent text-muted" 
                        type="button" id="permActionMenu{{ $permission->id }}" 
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="bi bi-three-dots-vertical fs-6"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border" aria-labelledby="permActionMenu{{ $permission->id }}">
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('permissions.show', $permission->id) }}">
                            <i class="bi bi-eye me-2 text-info"></i> {{ __('locale.btn_show') ?? 'Voir' }}
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('permissions.edit', $permission->id) }}">
                            <i class="bi bi-pencil-square me-2 text-primary"></i> {{ __('locale.edit') }}
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form id="delete-{{ $permission->id }}" action="{{ route('permissions.destroy', $permission->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="dropdown-item text-danger d-flex align-items-center" onclick="myFunctionSubmit('delete-{{ $permission->id }}')">
                                <i class="bi bi-trash me-2"></i> {{ __('locale.delete') }}
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    @if ($hasChildren)
    <div id="{{ $collapseId }}" class="collapse permission-children">
        @foreach ($permission->children as $child)
            @include('permissions.partials.permission_list_item', [
                'permission' => $child,
                'level' => $level + 1,
            ])
        @endforeach
    </div>
    @endif
</div>