@extends('layouts.app')
@section('portal-module', 'employees')
@section('portal-page', 'edit')
@section('portal-kind', 'form')
@section('portal-family', 'technical')
@section('portal-icon', 'screwdriver-wrench')


@section('title', 'Modifier le salarié')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="h4 mb-0 fw-bold">Modifier le salarié : {{ $employee->full_name }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-outline-secondary">
            Annuler
        </a>
        <button type="submit" form="employeeEditForm" class="btn btn-primary">
            <i class="bi bi-check-lg me-1"></i> Enregistrer
        </button>
    </div>
@endsection

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="module-form-card card card-premium shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('employees.update', $employee->id) }}" method="POST" id="employeeEditForm">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" for="last_name">Nom *</label>
                                <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $employee->last_name) }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" for="first_name">Prénom *</label>
                                <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $employee->first_name) }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" for="company_id">Société de rattachement</label>
                                <select name="company_id" id="company_id" class="form-select @error('company_id') is-invalid @enderror">
                                    <option value="">-- Sélectionner la société --</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ old('company_id', $employee->company_id) == $company->id ? 'selected' : '' }}>
                                            {{ $company->name }} ({{ $company->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('company_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" for="establishment">Établissement / Site</label>
                                <input type="text" name="establishment" id="establishment" class="form-control @error('establishment') is-invalid @enderror" placeholder="ex: Siège Paris, Agence Lyon..." value="{{ old('establishment', $employee->establishment) }}">
                                @error('establishment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" for="job_title">Poste / Fonction</label>
                                <input type="text" name="job_title" id="job_title" class="form-control @error('job_title') is-invalid @enderror" placeholder="ex: Technicien SAV, Commercial..." value="{{ old('job_title', $employee->job_title) }}">
                                @error('job_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" for="email">Adresse email <span class="text-muted fw-normal">(Optionnelle)</span></label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="jean.dupont@entreprise.fr" value="{{ old('email', $employee->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" for="phone">Téléphone</label>
                                <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="06 12 34 56 78" value="{{ old('phone', $employee->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" for="user_id">Compte utilisateur système (Optionnel)</label>
                                <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror">
                                    <option value="">-- Aucun compte (Salarié sans accès) --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id', $employee->user_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Laissez vide si le salarié n'a pas de compte applicatif</small>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <div class="form-check form-switch mt-2">
                                    <input type="hidden" name="is_active" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $employee->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="is_active">Salarié actif</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
