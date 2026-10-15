@extends('layouts.app')
@section('portal-module', 'permissions')
@section('portal-page', 'create')
@section('portal-kind', 'form')
@section('portal-family', 'admin')
@section('portal-icon', 'gear')


@section('title', __('locale.lbl_create_permission'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.lbl_create_permission') }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('permissions.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-1 fs-12">{{ __('locale.btn_cancel') ?? 'Annuler' }}</a>
        <button type="submit" form="permissionCreateForm" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-check-lg me-1"></i>{{ __('locale.save') ?? 'Enregistrer' }}
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
                            <i class="bi bi-shield-plus me-2 text-primary"></i>{{ __('locale.lbl_create_permission') }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('permissions.store') }}" method="POST" id="permissionCreateForm">
                            @csrf

                            <!-- Section 1: Main Info -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3 text-dark">{{ __('locale.lbl_main_info') }}</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label fw-semibold">{{ __('locale.name') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required placeholder="Ex: Gestion des utilisateurs" autofocus>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="slug" class="form-label fw-semibold">{{ __('locale.lbl_slug') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="slug" id="slug" class="form-control font-monospace" value="{{ old('slug') }}" required placeholder="Ex: users-manage">
                                    </div>
                                    <div class="col-12">
                                        <label for="description" class="form-label fw-semibold">{{ __('locale.description') }}</label>
                                        <textarea name="description" id="description" class="form-control" rows="2" placeholder="Description facultative...">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2: Translations -->
                            <div class="mb-4 pt-3 border-top">
                                <h6 class="fw-bold mb-3 text-dark">{{ __('locale.lbl_translations') }}</h6>
                                <div class="row g-3">
                                    @foreach(config('locales.available') as $locale)
                                        <div class="col-md-6">
                                            <div class="p-3 rounded bg-light border">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="badge bg-secondary me-2 text-uppercase">{{ $locale }}</span>
                                                    <span class="fw-semibold text-muted small">{{ __('locale.lang_' . $locale) ?? strtoupper($locale) }}</span>
                                                </div>
                                                <div class="mb-2">
                                                    <label for="translations_{{ $locale }}_name" class="form-label small fw-semibold">{{ __('locale.name') }}</label>
                                                    <input type="text" class="form-control form-control-sm" id="translations_{{ $locale }}_name" name="translations[{{ $locale }}][name]" value="{{ old('translations.' . $locale . '.name') }}">
                                                </div>
                                                <div>
                                                    <label for="translations_{{ $locale }}_description" class="form-label small fw-semibold">{{ __('locale.description') }}</label>
                                                    <textarea class="form-control form-control-sm" id="translations_{{ $locale }}_description" name="translations[{{ $locale }}][description]" rows="2">{{ old('translations.' . $locale . '.description') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Section 3: Hierarchy (Parent) -->
                            <div class="mb-4 pt-3 border-top">
                                <h6 class="fw-bold mb-3 text-dark">{{ __('locale.lbl_parent_permission') }}</h6>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="parent_id" class="form-label fw-semibold">{{ __('locale.lbl_select_parent') }}</label>
                                        <select name="parent_id" id="parent_id" class="form-select select2">
                                            <option value="">-- {{ __('locale.lbl_none_root') }} --</option>
                                            @foreach ($parentPermissions as $parent)
                                                <option value="{{ $parent->id }}" @selected(old('parent_id') == $parent->id)>
                                                    {{ $parent->translated_name }} ({{ $parent->slug }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        if ($.fn.select2) {
            $('.select2').select2({
                allowClear: true,
                width: '100%'
            });
        }

        // Simple slugifier helper
        $('#name').on('input', function() {
            let name = $(this).val();
            let slug = name.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
            $('#slug').val(slug);
        });
    });
</script>
@endpush
