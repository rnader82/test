@extends('layouts.app')
@section('portal-module', 'quote-reserves')
@section('portal-page', 'create')
@section('portal-kind', 'form')
@section('portal-family', 'quotes')
@section('portal-icon', 'file-lines')


@section('title', 'Nouvelle réserve de devis')

@section('content')
<div class="container py-4" style="max-width: 800px;">
    <div class="mb-4">
        <a href="{{ route('quote-reserves.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill mb-2">
            <i class="bi bi-arrow-left me-1"></i>Retour aux réserves
        </a>
        <h2 class="fw-bold text-dark mb-1">Ajout d'une nouvelle réserve</h2>
        <p class="text-muted">Définissez une clause ou réserve réutilisable pour vos devis.</p>
    </div>

    <div class="module-form-card card card-premium shadow-sm border-0 p-4">
        <form method="POST" action="{{ route('quote-reserves.store') }}">
            @csrf
            
            <div class="mb-3">
                <label for="title" class="form-label text-secondary small fw-semibold">Réserve</label>
                <input type="text" class="form-control rounded-2 border py-2" id="title" name="title" value="{{ old('title') }}" required autofocus>
                @error('title')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <textarea class="form-control rounded-2 border p-3" id="content" name="content" rows="6" placeholder="Texte ou description détaillée de la réserve...">{{ old('content') }}</textarea>
                @error('content')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                <label class="form-check-label small text-muted" for="is_active">
                    Réserve active (disponible dans la sélection des devis)
                </label>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('quote-reserves.index') }}" class="btn btn-outline-secondary px-4 rounded-2">
                    Annuler
                </a>
                <button type="submit" class="btn btn-primary px-4 rounded-2 d-inline-flex align-items-center gap-2" style="background-color: #3b82f6; border-color: #3b82f6;">
                    <span>Enregistrer</span>
                    <i class="bi bi-floppy"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
