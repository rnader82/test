@extends('layouts.app')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('deliveries.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <h1>Nouveau Bon de Livraison</h1>
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Veuillez corriger les erreurs ci-dessous.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card card-premium">
            <div class="card-body p-4">
                <form action="{{ route('deliveries.store') }}" method="POST">
                    @csrf

                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-truck me-2"></i>Informations Générales</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="numero" class="form-label fw-bold small text-uppercase text-muted">Numéro BL</label>
                            <input type="text" name="numero" id="numero" class="form-control @error('numero') is-invalid @enderror" value="{{ old('numero') }}" placeholder="Ex: WH/OUT/00001 ou BL-2026-001">
                            @error('numero') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="ref_interne" class="form-label fw-bold small text-uppercase text-muted">Réf. Interne (Document d'Origine)</label>
                            <input type="text" name="ref_interne" id="ref_interne" class="form-control @error('ref_interne') is-invalid @enderror" value="{{ old('ref_interne') }}" placeholder="Ex: CMD-2026-001">
                            @error('ref_interne') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="client_id" class="form-label fw-bold small text-uppercase text-muted">Client / Destinataire</label>
                            <select name="client_id" id="client_id" class="form-select select2 @error('client_id') is-invalid @enderror">
                                <option value="">Sélectionner un client</option>
                                @foreach($clients as $c)
                                    <option value="{{ $c->id }}" @selected(old('client_id') == $c->id)>{{ $c->raison_sociale }}</option>
                                @endforeach
                            </select>
                            @error('client_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="company_id" class="form-label fw-bold small text-uppercase text-muted">Société</label>
                            <select name="company_id" id="company_id" class="form-select @error('company_id') is-invalid @enderror">
                                <option value="">Sélectionner une société</option>
                                @foreach($companies as $comp)
                                    <option value="{{ $comp->id }}" @selected(old('company_id') == $comp->id)>{{ $comp->name }}</option>
                                @endforeach
                            </select>
                            @error('company_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="order_id" class="form-label fw-bold small text-uppercase text-muted">Commande Associée</label>
                            <select name="order_id" id="order_id" class="form-select select2 @error('order_id') is-invalid @enderror">
                                <option value="">Aucune</option>
                                @foreach($orders as $ord)
                                    <option value="{{ $ord->id }}" @selected(old('order_id') == $ord->id)>{{ $ord->ref_interne ?: '#' . $ord->id }}</option>
                                @endforeach
                            </select>
                            @error('order_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="state" class="form-label fw-bold small text-uppercase text-muted">Statut</label>
                            <select name="state" id="state" class="form-select @error('state') is-invalid @enderror">
                                <option value="draft" @selected(old('state') === 'draft')>Brouillon (Draft)</option>
                                <option value="waiting" @selected(old('state') === 'waiting')>En attente (Waiting)</option>
                                <option value="confirmed" @selected(old('state') === 'confirmed')>Confirmé (Confirmed)</option>
                                <option value="assigned" @selected(old('state') === 'assigned')>Prêt (Assigned)</option>
                                <option value="done" @selected(old('state') === 'done')>Fait (Done)</option>
                                <option value="cancel" @selected(old('state') === 'cancel')>Annulé (Cancel)</option>
                            </select>
                            @error('state') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="date_livraison" class="form-label fw-bold small text-uppercase text-muted">Date de Livraison</label>
                            <input type="datetime-local" name="date_livraison" id="date_livraison" class="form-control @error('date_livraison') is-invalid @enderror" value="{{ old('date_livraison') }}">
                            @error('date_livraison') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="scheduled_date" class="form-label fw-bold small text-uppercase text-muted">Date Prévue</label>
                            <input type="datetime-local" name="scheduled_date" id="scheduled_date" class="form-control @error('scheduled_date') is-invalid @enderror" value="{{ old('scheduled_date') }}">
                            @error('scheduled_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label for="note" class="form-label fw-bold small text-uppercase text-muted">Notes internes</label>
                            <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror" rows="3">{{ old('note') }}</textarea>
                            @error('note') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('deliveries.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">Annuler</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                            <i class="bi bi-check-lg me-1"></i>Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
