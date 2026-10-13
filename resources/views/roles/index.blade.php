@extends('layouts.app')

@section('title', __('locale.lbl_role_title'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.lbl_role_title') }}</h1>
        <span class="header-count">({{ count($roles) }})</span>
    </div>
@endsection

@section('page-actions')
    <a href="{{ route('roles.create') }}" class="btn btn-primary" title="{{ __('locale.btn_create_role') }}">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_create_role') ?? 'Nouveau rôle' }}
    </a>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            @forelse ($roles as $role)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card card-premium shadow-sm border-0 h-100" style="cursor: pointer;" onclick="window.location='{{ route('roles.show', $role) }}'">
                        <div class="card-body p-4 d-flex flex-column justify-content-between">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace small mb-2">
                                        {{ $role->slug }}
                                    </span>
                                    <h5 class="fw-bold text-dark mb-1">{{ $role->translated_name }}</h5>
                                    <p class="text-muted small mb-0">{{ $role->translated_description ?: '—' }}</p>
                                </div>
                                <div class="dropdown" onclick="event.stopPropagation();">
                                    <button class="btn btn-sm btn-outline-secondary" type="button" id="roleActionMenu{{ $role->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border" aria-labelledby="roleActionMenu{{ $role->id }}">
                                        <li>
                                            <a href="{{ route('roles.show', $role) }}" class="dropdown-item">
                                                <i class="bi bi-eye me-2 text-info"></i>{{ __('locale.btn_show') ?? 'Voir' }}
                                            </a>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item edit-permissions-btn" data-role-id="{{ $role->id }}" data-role-name="{{ $role->translated_name }}">
                                                <i class="bi bi-shield-lock me-2 text-info"></i>{{ __('locale.edit_permissions') }}
                                            </button>
                                        </li>
                                        <li>
                                            <a href="{{ route('roles.edit', $role) }}" class="dropdown-item">
                                                <i class="bi bi-pencil-square me-2 text-primary"></i>{{ __('locale.edit') }}
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('roles.destroy', $role) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('{{ __('locale.delete_confirmation') }}')">
                                                    <i class="bi bi-trash me-2"></i>{{ __('locale.delete') }}
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="pt-3 border-top d-flex justify-content-between align-items-center" onclick="event.stopPropagation();">
                                <button type="button" class="btn btn-sm btn-outline-primary edit-permissions-btn" data-role-id="{{ $role->id }}" data-role-name="{{ $role->translated_name }}">
                                    <i class="bi bi-shield-lock me-1"></i>{{ __('locale.edit_permissions') }}
                                </button>
                                <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-link text-decoration-none">
                                    <i class="bi bi-pencil me-1"></i>{{ __('locale.edit') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card card-premium shadow-sm border-0 p-5 text-center text-muted">
                        <i class="bi bi-shield-x fs-1 mb-2"></i>
                        {{ __('locale.lbl_no_records_found') }}
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Modal Container -->
        <div class="modal fade" id="editPermissionsModal" tabindex="-1" aria-labelledby="editPermissionsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content" id="edit-permissions-modal-content">
                    <!-- Modal content will be loaded here -->
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.edit-permissions-btn');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const roleId = this.getAttribute('data-role-id');
                const url = `/roles/${roleId}/edit-permissions`;

                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('edit-permissions-modal-content').innerHTML = html;
                        const editPermissionsModal = new bootstrap.Modal(document.getElementById('editPermissionsModal'));
                        editPermissionsModal.show();
                        
                        const permissionSearch = document.getElementById('permissionSearch');
                        if (permissionSearch) {
                            permissionSearch.addEventListener('input', function () {
                                const query = this.value.toLowerCase();
                                document.querySelectorAll('#permissionsTable tbody tr').forEach(row => {
                                    const text = row.querySelector('td').textContent.toLowerCase();
                                    row.style.display = text.includes(query) ? '' : 'none';
                                });
                            });
                        }

                        const form = document.getElementById('edit-permissions-form');
                        if (form) {
                            form.addEventListener('submit', function(e) {
                                e.preventDefault();
                                const formData = new FormData(this);
                                const actionUrl = this.getAttribute('action');

                                fetch(actionUrl, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json',
                                    },
                                    body: formData,
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        editPermissionsModal.hide();
                                        location.reload();
                                    } else {
                                        alert('An error occurred.');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('An error occurred.');
                                });
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching modal content:', error);
                    });
            });
        });

        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('permission-checkbox')) {
                const permissionId = e.target.dataset.permissionId;
                const isChecked = e.target.checked;
                const firstLevel = document.querySelectorAll(`input.permission-checkbox[data-parent-id='${permissionId}']`);
                firstLevel.forEach(cb => {
                    cb.checked = isChecked;
                    const childId = cb.dataset.permissionId;
                    document.querySelectorAll(`input.permission-checkbox[data-parent-id='${childId}']`).forEach(gc => {
                        gc.checked = isChecked;
                    });
                });
            }
        });
    });
</script>
@endsection
