@extends('layouts.app')

@section('title', __('locale.lbl_edit_issue_title'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('issues.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.lbl_edit_issue_title') }}</h1>
    </div>
@endsection

@section('content')
    <div class="container mt-4">
        @include('issues.form', [
            'action' => route('issues.update', $issue->id),
            'method' => 'PUT',
            'issue' => $issue,
            'arr_preset' => $arr_preset ?? [],
        ])
    </div>
@endsection