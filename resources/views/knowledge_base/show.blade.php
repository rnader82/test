@extends('layouts.app')
@section('portal-module', 'knowledge_base')
@section('portal-page', 'show')
@section('portal-kind', 'record')
@section('portal-family', 'technical')
@section('portal-icon', 'screwdriver-wrench')


@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('knowledge-base.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ $knowledgeBase->subject }}</h1>
    </div>
@endsection

@section('page-actions')
    @if (Auth::user()->is_admin || Auth::user()->is_employee)
        <a href="{{ route('knowledge-base.edit', $knowledgeBase->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-1"></i> {{ __('locale.btn_edit') }}
        </a>
    @endif
@endsection

@section('title', __('locale.btn_show'))

@section('content')
<div class="container">

    <div class="module-record-card card">
        <div class="card-header d-flex justify-content-between align-items-center">
            @php
                $typeBadge = match($knowledgeBase->type) {
                    'internal' => 'bg-danger',
                    'dedicated' => 'bg-info',
                    'general' => 'bg-success',
                    default => 'bg-secondary'
                };
            @endphp
            <div>
                <span class="badge {{ $typeBadge }}">{{ $knowledgeBase->translated_type }}</span>
                @if ($knowledgeBase->client)
                    <span class="ms-2"><strong>{{ __('locale.lbl_client') }}:</strong> {{ $knowledgeBase->client->raison_sociale }}</span>
                @endif
            </div>
            <small class="text-muted">{{ __('locale.lbl_created_at') }}: {{ format_datetime($knowledgeBase->created_at, 'long', 'short') }}</small>
        </div>
        <div class="card-body">
            <div class="kb-document-info text-center py-5">
                <i class="bi bi-file-earmark-text mb-3" style="font-size: 4rem; color: #6c757d;"></i>
                <h3 class="mb-2">{{ $knowledgeBase->document_name }}</h3>
                <p class="text-muted mb-4">
                    {{ $knowledgeBase->mime_type }} | {{ $knowledgeBase->size_human }}
                </p>
                <a href="{{ route('knowledge-base.download', $knowledgeBase->id) }}" class="btn btn-lg btn-success">
                    <i class="bi bi-download me-2"></i> {{ __('locale.btn_download') }}
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .kb-document-info {
        background-color: #f8f9fa;
        border-radius: 0.5rem;
    }
</style>
@endsection
