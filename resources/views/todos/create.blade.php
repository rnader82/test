@extends('layouts.app')
@section('portal-module', 'todos')
@section('portal-page', 'create')
@section('portal-kind', 'form')
@section('portal-family', 'dashboard')
@section('portal-icon', 'house')


@section('title', __('locale.todo.create_title'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('todos.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.todo.create_title') }}</h1>
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="module-form-card card card-premium">
            <div class="card-body p-4">
                <form action="{{ route('todos.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="title" class="form-label fw-semibold">{{ __('locale.todo.lbl_title') }}</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required placeholder="{{ __('locale.todo.lbl_title') }}">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">{{ __('locale.todo.lbl_description') }}</label>
                        <textarea name="description" id="description" class="form-control" rows="4" placeholder="{{ __('locale.todo.lbl_description') }}">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="users" class="form-label fw-semibold">{{ __('locale.todo.lbl_users') }}</label>
                        <select id="users" name="users[]" class="form-control select2" multiple="multiple" style="width: 100%;">
                            @foreach ($users as $user) {{-- Assuming $users is passed to the view --}}
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="comment" class="form-label fw-semibold">{{ __('locale.todo.lbl_initial_comment') }}</label>
                        <textarea name="comment" id="comment" class="form-control" rows="3" placeholder="{{ __('locale.todo.lbl_initial_comment') }}">{{ old('comment') }}</textarea>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('todos.index') }}" class="btn btn-outline-secondary">{{ __('locale.cancel') }}</a>
                        <button type="submit" class="btn btn-primary px-4">{{ __('locale.btn_save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#users').select2({
            placeholder: "{{ __('locale.todo.lbl_users_placeholder') }}",
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush

