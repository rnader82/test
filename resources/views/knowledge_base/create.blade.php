@extends('layouts.app')

@section('title', __('locale.add'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('knowledge-base.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.add') }} - {{ __('locale.menu_knowledge_base') }}</h1>
    </div>
@endsection

@section('content')
<div class="container mt-4">

    <div class="card">
        <div class="card-body">
            <form action="{{ route('knowledge-base.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('knowledge_base.form', ['knowledgeBase' => new \App\Models\KnowledgeBase])

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">{{ __('locale.btn_create') }}</button>
                    <a href="{{ route('knowledge-base.index') }}" class="btn btn-secondary">{{ __('locale.btn_cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
