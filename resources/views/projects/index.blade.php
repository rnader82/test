@extends('layouts.app')
@section('portal-module', 'projects')
@section('portal-page', 'index')
@section('portal-kind', 'list')
@section('portal-family', 'adv')
@section('portal-icon', 'boxes-stacked')


@section('title', __('locale.projects.title'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.projects.titles.index') }}</h1>
        <span class="header-count">({{ $projects->total() }})</span>
    </div>
@endsection

@section('navbar-search')
    <form method="GET" action="{{ route('projects.index') }}" class="w-100">
        <div class="search-bar-mockup">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('locale.projects.search_placeholder') }}">
            @foreach(request()->except(['search', 'page']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </div>
    </form>
@endsection

@section('page-actions')
    <a href="{{ route('projects.create') }}" class="btn btn-primary" title="{{ __('locale.projects.new_project') }}">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.projects.new_project') ?? 'Nouveau projet' }}
    </a>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="table-toolbar portal-original-toolbar">
            <div class="toolbar-left">
                <span class="text-muted small">
                    Résultats {{ $projects->firstItem() ?? 0 }}-{{ $projects->lastItem() ?? 0 }} sur {{ $projects->total() }}
                </span>
            </div>
            <div class="toolbar-right">
            </div>
        </div>

    @if ($projects->count())
        <div class="table-responsive">
            <table class="module-table table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>{{ __('locale.projects.id') }}</th>
                        <th>{{ __('locale.projects.name') }}</th>
                        <th>{{ __('locale.projects.client') }}</th>
                        <th>{{ __('locale.projects.start_date') }}</th>
                        <th>{{ __('locale.projects.end_date') }}</th>
                        <th>{{ __('locale.projects.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($projects as $project)
                        <tr class="clickable" onclick="window.location='{{ route('projects.show', $project->id) }}'" style="cursor: pointer;">
                            <td>{{ $project->id }}</td>
                            <td>{{ $project->name }}</td>
                            <td>{{ $project->client->raison_sociale ?? 'N/A' }}</td>
                            <td>{{ $project->date_debut ? $project->date_debut->format('d/m/Y') : '-' }}</td>
                            <td>{{ $project->date_fin ? $project->date_fin->format('d/m/Y') : '-' }}</td>
                            <td>
                                <div class="dropdown" onclick="event.stopPropagation();">
                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('projects.show', $project->id) }}">
                                                <i class="bi bi-eye me-1"></i> {{ __('locale.projects.view') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('projects.edit', $project->id) }}">
                                                <i class="bi bi-pencil me-1"></i> {{ __('locale.projects.edit') }}
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('{{ __('locale.projects.delete_confirmation') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bi bi-trash me-1"></i> {{ __('locale.projects.delete') }}
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $projects->withQueryString()->links() }}
    @else
        <div class="alert alert-info">{{ __('locale.projects.no_projects_found') }}</div>
    @endif
</div>
</div>

@endsection
