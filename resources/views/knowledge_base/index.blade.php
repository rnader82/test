@extends('layouts.app')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.menu_knowledge_base') }}</h1>
        <span class="header-count">({{ $knowledgeBases->total() }})</span>
    </div>
@endsection

@section('navbar-search')
    <form method="GET" action="{{ route('knowledge-base.index') }}" class="w-100">
        <div class="search-bar-mockup">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('locale.search_placeholder') }}">
            @foreach(request()->except(['search', 'page']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </div>
    </form>
@endsection

@section('page-actions')
    @if (Auth::user()->is_admin || Auth::user()->is_employee)
        <a href="{{ route('knowledge-base.create') }}" class="btn btn-primary" title="{{ __('locale.add') }}">
            <i class="bi bi-plus-lg me-1"></i> {{ __('locale.add') ?? 'Nouvel article' }}
        </a>
    @endif
@endsection


@section('title', __('locale.menu_knowledge_base'))

@section('content')
<div class="container">

    <form method="GET" action="{{ route('knowledge-base.index') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="{{ __('locale.search_placeholder') }}">
            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
        </div>
    </form>

    @if ($knowledgeBases->count())
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>{{ __('locale.id') }}</th>
                        <th>{{ __('locale.lbl_type') }}</th>
                        <th>{{ __('locale.lbl_client') }}</th>
                        <th>{{ __('locale.lbl_subject') }}</th>
                        <th>{{ __('locale.document') }}</th>
                        <th>{{ __('locale.lbl_created_at') }}</th>
                        <th>{{ __('locale.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($knowledgeBases as $kb)
                        @php
                            $typeBadge = match($kb->type) {
                                'internal' => 'bg-danger',
                                'dedicated' => 'bg-info',
                                'general' => 'bg-success',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <tr class="clickable" onclick="window.location='{{ route('knowledge-base.show', $kb->id) }}'" style="cursor: pointer;">
                            <td>{{ $kb->id }}</td>
                            <td>
                                <span class="badge {{ $typeBadge }} mb-2 mt-2">{{ $kb->translated_type }}</span>
                            </td>
                            <td>
                                @if($kb->client)
                                    {{ $kb->client->raison_sociale }}@if($kb->client->ville) - {{ $kb->client->ville }}@endif
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $kb->subject }}</td>
                            <td>
                                <i class="bi bi-file-earmark me-1"></i> {{ $kb->document_name }}
                                <br><small class="text-muted">{{ $kb->size_human }}</small>
                            </td>
                            <td>{{ format_datetime($kb->created_at, 'short', 'short') }}</td>
                            <td>
                                <div class="dropdown" onclick="event.stopPropagation();">
                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('knowledge-base.show', $kb->id) }}">
                                                <i class="bi bi-eye me-1"></i> {{ __('locale.btn_show') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('knowledge-base.download', $kb->id) }}">
                                                <i class="bi bi-download me-1"></i> {{ __('locale.btn_download') }}
                                            </a>
                                        </li>
                                        @if (Auth::user()->is_admin || Auth::user()->is_employee)
                                            <li>
                                                <a class="dropdown-item" href="{{ route('knowledge-base.edit', $kb->id) }}">
                                                    <i class="bi bi-pencil me-1"></i> {{ __('locale.btn_edit') }}
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('knowledge-base.destroy', $kb->id) }}" method="POST" onsubmit="return confirm('{{ __('locale.delete_confirmation') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bi bi-trash me-1"></i> {{ __('locale.btn_delete') }}
                                                    </button>
                                                </form>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $knowledgeBases->withQueryString()->links() }}
    @else
        <p>{{ __('locale.lbl_no_records_found') }}</p>
    @endif
    </div>
@endsection

