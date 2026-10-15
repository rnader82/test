@extends('layouts.app')
@section('portal-module', 'quote-templates')
@section('portal-page', 'create')
@section('portal-kind', 'form')
@section('portal-family', 'quotes')
@section('portal-icon', 'file-lines')


@section('title', 'Nouveau Modèle de devis')

@section('content')
<div class="container-fluid py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('quote-templates.index') }}" class="text-decoration-none">Modèles de devis</a></li>
            <li class="breadcrumb-item active" aria-current="page">Nouveau Modèle</li>
        </ol>
    </nav>

    <!-- Feedback Alerts -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-3 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><strong>Veuillez vérifier les erreurs :</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('quote-templates.store') }}" id="quote-template-form">
        @csrf
        @include('quote-templates._form')
    </form>
</div>
@endsection
