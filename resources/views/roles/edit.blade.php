@extends('layouts.app')
@section('portal-module', 'roles')
@section('portal-page', 'edit')
@section('portal-kind', 'form')
@section('portal-family', 'admin')
@section('portal-icon', 'gear')


@section('title', __('locale.lbl_edit_role') . ': ' . $role->translated_name)

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.lbl_edit_role') }}: {{ $role->translated_name }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('roles.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-1 fs-12">{{ __('locale.cancel') ?? 'Annuler' }}</a>
        <button type="submit" form="roleEditForm" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-check-lg me-1"></i>{{ __('locale.btn_update_role') ?? 'Mettre à jour' }}
        </button>
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="module-form-card card card-premium shadow-sm border-0">
                    <div class="card-header bg-light border-bottom py-3">
                        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
                            <i class="bi bi-pencil-square me-2 text-primary"></i>{{ __('locale.lbl_edit_role') }}: {{ $role->translated_name }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>{{ __('locale.lbl_error_input') }}:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('roles.update', $role->id) }}" method="POST" id="roleEditForm">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="slug" class="form-label fw-semibold">{{ __('locale.lbl_slug') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="slug" id="slug" value="{{ old('slug', $role->slug) }}" class="form-control font-monospace" required autofocus>
                                </div>

                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold">{{ __('locale.name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" class="form-control" required>
                                </div>

                                <div class="col-12">
                                    <label for="description" class="form-label fw-semibold">{{ __('locale.description') }} <span class="text-muted small">({{ __('locale.lbl_optional') }})</span></label>
                                    <textarea name="description" id="description" class="form-control" rows="2">{{ old('description', $role->description) }}</textarea>
                                </div>

                                @foreach(config('locales.available') as $locale)
                                    @php $trans = $role->translations[$locale] ?? []; @endphp
                                    <div class="col-12"><hr class="my-2 text-muted"></div>
                                    <div class="col-md-6">
                                        <label for="translations_{{ $locale }}_name" class="form-label fw-semibold">{{ __('locale.name') }} ({{ strtoupper($locale) }})</label>
                                        <input type="text" class="form-control" id="translations_{{ $locale }}_name" name="translations[{{ $locale }}][name]" value="{{ old('translations.' . $locale . '.name', $trans['name'] ?? '') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="translations_{{ $locale }}_description" class="form-label fw-semibold">{{ __('locale.description') }} ({{ strtoupper($locale) }})</label>
                                        <textarea class="form-control" id="translations_{{ $locale }}_description" name="translations[{{ $locale }}][description]" rows="2">{{ old('translations.' . $locale . '.description', $trans['description'] ?? '') }}</textarea>
                                    </div>
                                @endforeach

                                <div class="col-12 mt-4">
                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#permissionsModal">
                                        <i class="bi bi-shield-lock me-1"></i> {{ __('locale.menu_permissions') }}
                                    </button>
                                </div>
                            </div>

                            <!-- Permissions Modal -->
                            <div class="modal fade" id="permissionsModal" tabindex="-1" aria-labelledby="permissionsModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-light border-bottom">
                                            <h5 class="modal-title fs-6 fw-bold" id="permissionsModalLabel">
                                                <i class="bi bi-shield-lock me-2 text-primary"></i>{{ __('locale.menu_permissions') }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <input type="text" id="permissionSearch" class="form-control mb-3" placeholder="{{ __('locale.search') }}">
                                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                                <table id="permissionsTable" class="table table-hover table-bordered align-middle mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>{{ __('locale.lbl_permission') }}</th>
                                                            <th style="width: 100px;" class="text-center">{{ __('locale.assign') }}</th>
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
                                        </div>
                                        <div class="modal-footer border-top bg-light">
                                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">{{ __('locale.btn_close') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Permissions Modal -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
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
</script>
@endsection