@extends('layouts.app')

@section('title', 'Modifier le Projet - ' . $project->name)

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('projects.show', $project->id) }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">Modifier le Projet</h1>
    </div>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header border-0 bg-transparent">
                </div>
                <div class="card-body">
                    <form action="{{ route('projects.update', $project->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @include('projects.form')
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning">Mettre à jour le Projet</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
