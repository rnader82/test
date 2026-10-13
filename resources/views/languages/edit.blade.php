@extends('layouts.app')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('languages.index') }}" class="btn btn-sm btn-icon btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1>Modifier la langue: {{ $language->name }}</h1>
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-4">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light border-0 py-3">
                        <h5 class="card-title mb-0 fs-6 fw-bold text-secondary">
                            <i class="bi bi-pencil-square me-2 text-primary"></i>Informations de la langue
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('languages.update', $language) }}">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="name" class="form-label fw-semibold">Nom de la langue <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $language->name) }}" placeholder="Ex: Français, English, Espagnol" required autofocus>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Nom complet affiché dans les listes déroulantes de sélection.</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="code" class="form-label fw-semibold">Code ISO (2-3 lettres)</label>
                                    <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror"
                                           value="{{ old('code', $language->code) }}" placeholder="Ex: fr, en, es, de" maxlength="10">
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Code ISO 639-1 (ex: fr, en).</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="locale" class="form-label fw-semibold">Locale Odoo / I18N</label>
                                    <input type="text" name="locale" id="locale" class="form-control @error('locale') is-invalid @enderror"
                                           value="{{ old('locale', $language->locale) }}" placeholder="Ex: fr_FR, en_US, es_ES" maxlength="20">
                                    @error('locale')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Code locale utilisé pour la synchronisation Odoo.</div>
                                </div>

                                <div class="col-12 pt-3 border-top">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $language->is_active))>
                                        <label class="form-check-label fw-semibold" for="is_active">Langue active</label>
                                        <div class="form-text">Si cochée, cette langue apparaîtra dans les sélecteurs de contacts, clients et fournisseurs.</div>
                                    </div>

                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_default" id="is_default" value="1" @checked(old('is_default', $language->is_default))>
                                        <label class="form-check-label fw-semibold" for="is_default">Définir comme langue par défaut</label>
                                        <div class="form-text">La langue sélectionnée par défaut lors de la création d'un nouveau contact, client ou fournisseur.</div>
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-end gap-2 pt-3 border-top">
                                    <a href="{{ route('languages.index') }}" class="btn btn-outline-secondary">Annuler</a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-lg me-1"></i> Mettre à jour
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
