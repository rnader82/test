@extends('layouts.app')
@section('portal-module', 'prospects')
@section('portal-page', 'edit')
@section('portal-kind', 'form')
@section('portal-family', 'crm')
@section('portal-icon', 'arrow-trend-up')


@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('prospects.show', $prospect->id) }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.lbl_edit_prospect_title') }} : {{ $prospect->raison_sociale }}</h1>
    </div>
@endsection

@section('content')
	<div class="container mt-4">
		<form action="{{ route('prospects.update', $prospect->id) }}" method="POST">
			@csrf
			@method('PUT')
			<div class="module-form-card card shadow-sm border-0">
				<div class="card-header bg-white py-3">
					<h5 class="mb-0 text-primary">Informations Générales</h5>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-6 form-group mt-3">
							<label for="raison_sociale">{{ __('locale.lbl_raison_sociale') }} <span class="text-danger">*</span></label>
							<input type="text" name="raison_sociale" class="form-control @error('raison_sociale') is-invalid @enderror" value="{{ old('raison_sociale', $prospect->raison_sociale) }}" required>
							@error('raison_sociale')
								<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>
						<div class="col-md-6 form-group mt-3">
							<label for="type">{{ __('locale.lbl_type') }}</label>
							<select name="type" class="form-control select2">
								<option value="">-- Choisir un type --</option>
								<option value="Site" {{ old('type', $prospect->type) == 'Site' ? 'selected' : '' }}>Site</option>
								<option value="Groupe" {{ old('type', $prospect->type) == 'Groupe' ? 'selected' : '' }}>Groupe</option>
								<option value="HQ" {{ old('type', $prospect->type) == 'HQ' ? 'selected' : '' }}>HQ</option>
								<option value="Partenaire" {{ old('type', $prospect->type) == 'Partenaire' ? 'selected' : '' }}>Partenaire</option>
							</select>
						</div>
					</div>

					<div class="row mt-3">
						<div class="col-md-4 form-group mt-3">
							<label for="tel_1">Téléphone 1</label>
							<input type="text" name="tel_1" class="form-control" value="{{ old('tel_1', $prospect->tel_1) }}">
						</div>
						<div class="col-md-4 form-group mt-3">
							<label for="tel_2">Téléphone 2</label>
							<input type="text" name="tel_2" class="form-control" value="{{ old('tel_2', $prospect->tel_2) }}">
						</div>
						<div class="col-md-4 form-group mt-3">
							<label for="tel_3">Téléphone 3</label>
							<input type="text" name="tel_3" class="form-control" value="{{ old('tel_3', $prospect->tel_3) }}">
						</div>
					</div>

					<div class="row mt-3">
						<div class="col-md-6 form-group mt-3">
							<label for="email">E-mail</label>
							<input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $prospect->email) }}">
							@error('email')
								<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>
						<div class="col-md-6 form-group mt-3">
							<label for="site_web">Site Web</label>
							<input type="url" name="site_web" class="form-control @error('site_web') is-invalid @enderror" value="{{ old('site_web', $prospect->site_web) }}" placeholder="https://example.com">
							@error('site_web')
								<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>
					</div>
				</div>
			</div>

			<div class="module-form-card card shadow-sm border-0 mt-4">
				<div class="card-header bg-white py-3">
					<h5 class="mb-0 text-primary">Adresse & Localisation</h5>
				</div>
				<div class="card-body">
					<div class="form-group mt-3">
						<label for="adresse_1">{{ __('locale.lbl_adresse_1') }}</label>
						<input type="text" name="adresse_1" class="form-control" value="{{ old('adresse_1', $prospect->adresse_1) }}">
					</div>
					<div class="form-group mt-3">
						<label for="adresse_2">{{ __('locale.lbl_adresse_2') }}</label>
						<input type="text" name="adresse_2" class="form-control" value="{{ old('adresse_2', $prospect->adresse_2) }}">
					</div>
					<div class="form-group mt-3">
						<label for="adresse_3">{{ __('locale.lbl_adresse_3') }}</label>
						<input type="text" name="adresse_3" class="form-control" value="{{ old('adresse_3', $prospect->adresse_3) }}">
					</div>

					<div class="row mt-3">
						<div class="col-md-3 form-group mt-3">
							<label for="code_postal">{{ __('locale.lbl_code_postal') }}</label>
							<input type="text" name="code_postal" class="form-control" value="{{ old('code_postal', $prospect->code_postal) }}">
						</div>
						<div class="col-md-3 form-group mt-3">
							<label for="ville">{{ __('locale.lbl_ville') }}</label>
							<input type="text" name="ville" class="form-control" value="{{ old('ville', $prospect->ville) }}">
						</div>
						<div class="col-md-3 form-group mt-3">
							<label for="region">{{ __('locale.lbl_region') }}</label>
							<input type="text" name="region" class="form-control" value="{{ old('region', $prospect->region) }}">
						</div>
						<div class="col-md-3 form-group mt-3">
							<label for="pays">{{ __('locale.lbl_country') }}</label>
							<input type="text" name="pays" class="form-control" value="{{ old('pays', $prospect->pays) }}">
						</div>
					</div>
				</div>
			</div>

			<div class="module-form-card card shadow-sm border-0 mt-4">
				<div class="card-header bg-white py-3">
					<h5 class="mb-0 text-primary">Notes & Commentaires</h5>
				</div>
				<div class="card-body">
					<div class="form-group mt-3">
						<label for="commentaire">{{ __('locale.lbl_commentaire') }}</label>
						<textarea name="commentaire" class="form-control" rows="3">{{ old('commentaire', $prospect->commentaire) }}</textarea>
					</div>
					<div class="form-group mt-3">
						<label for="bloc_note">Bloc-notes interne</label>
						<textarea name="bloc_note" class="form-control" rows="4">{{ old('bloc_note', $prospect->bloc_note) }}</textarea>
					</div>
				</div>
			</div>

			<div class="d-flex justify-content-end gap-2 my-4">
				<a href="{{ route('prospects.show', $prospect->id) }}" class="btn btn-outline-secondary">{{ __('locale.btn_cancel') ?? 'Annuler' }}</a>
				<button type="submit" class="btn btn-primary">{{ __('locale.btn_save') ?? 'Enregistrer' }}</button>
			</div>
		</form>
	</div>
@endsection

@section('scripts')
	<script>
		$(document).ready(function() {
			$('.select2').select2({
				width: '100%'
			});
		});
	</script>
@endsection
