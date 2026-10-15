@extends('layouts.app')
@section('portal-module', 'projects')
@section('portal-page', 'create')
@section('portal-kind', 'form')
@section('portal-family', 'adv')
@section('portal-icon', 'boxes-stacked')


@section('title', 'Créer un Projet')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">Créer un Nouveau Projet</h1>
    </div>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="module-form-card card">
                <div class="card-header border-0 bg-transparent">
                </div>
                <div class="card-body">
                    <form action="{{ route('projects.store') }}" method="POST">
                        @csrf
                        @include('projects.form')
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Créer le Projet</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
