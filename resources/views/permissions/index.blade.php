@extends('layouts.app')

@section('title', __('locale.lbl_permissions_list'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.lbl_permissions_list') }}</h1>
        <span class="header-count">({{ count($permissions) }})</span>
    </div>
@endsection

@section('page-actions')
    <a href="{{ route('permissions.create') }}" class="btn btn-primary" title="{{ __('locale.lbl_create_permission') }}">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.lbl_create_permission') ?? 'Nouvelle permission' }}
    </a>
@endsection

@push('styles')
<style>
    /* Tree Node Styling */
    .permission-node {
        margin-bottom: 0.5rem;
        position: relative;
    }

    .node-content {
        display: flex;
        align-items: center;
        padding: 0.65rem 1rem;
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid #e7e7e7;
        transition: all 0.15s ease-in-out;
    }

    .node-content:hover {
        background: #f8f9fa;
        border-color: #91378e;
        box-shadow: 0 2px 8px rgba(105, 108, 255, 0.08);
    }

    .node-expand-btn {
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #f1f3f5;
        border: none;
        border-radius: 6px;
        color: #566a7f;
        cursor: pointer;
        padding: 0;
        margin-right: 0.6rem;
        flex-shrink: 0;
        transition: all 0.2s ease;
        font-size: 0.8rem;
    }

    .node-expand-btn:hover {
        background: #e4e7eb;
        color: #91378e;
    }

    .node-expand-btn i {
        display: inline-block;
        transition: transform 0.2s ease;
        transform: rotate(90deg); /* Expanded: down */
    }

    .node-expand-btn.collapsed i {
        transform: rotate(0deg); /* Collapsed: right */
    }

    .node-expand-spacer {
        width: 28px;
        height: 28px;
        margin-right: 0.6rem;
        flex-shrink: 0;
    }

    .node-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(105, 108, 255, 0.1);
        color: #91378e;
        border-radius: 6px;
        margin-right: 0.75rem;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .permission-name {
        font-weight: 600;
        color: #566a7f;
        font-size: 0.925rem;
    }

    .permission-slug {
        font-size: 0.75rem;
        color: #8592a3;
        font-family: var(--bs-font-monospace, monospace);
        background: #f5f5f9;
        border: 1px solid #e7e7e7;
        padding: 0.15rem 0.45rem;
        border-radius: 4px;
        margin-left: 0.6rem;
        font-weight: 500;
    }

    .node-actions {
        opacity: 0.6;
        transition: opacity 0.15s ease;
    }

    .node-content:hover .node-actions {
        opacity: 1;
    }

    .permission-children {
        margin-left: 1.75rem;
        padding-left: 0.85rem;
        border-left: 2px dashed #e2e8f0;
        margin-top: 0.4rem;
    }
</style>
@endpush

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

        <div class="card card-premium shadow-sm border-0">
            <div class="card-body p-4">
                <div id="permissions-tree-root">
                    @forelse ($permissions as $permission)
                        @include('permissions.partials.permission_list_item', [
                            'permission' => $permission,
                            'level' => 0,
                        ])
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-shield-x fs-1 d-block mb-2 text-muted"></i>
                            {{ __('locale.lbl_no_records_found') }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function myFunctionSubmit(form_name) {
    if(confirm("{{ __('locale.confirm_delete') }}")){
        document.getElementById(form_name).submit();
    }
}
</script>
@endsection