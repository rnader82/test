@extends('layouts.app')
@section('portal-module', 'supplier_contacts')
@section('portal-page', 'create')
@section('portal-kind', 'form')
@section('portal-family', 'purchasing')
@section('portal-icon', 'cart-flatbed')


@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('supplier-contacts.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">Ajouter un contact fournisseur</h1>
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <form action="{{ route('supplier-contacts.store') }}" method="POST">
            @csrf
            <div class="row g-4">
                <!-- Main Form Card -->
                <div class="col-xl-8 col-lg-7">
                    <div class="module-form-card card shadow-sm mb-4">
                        <div class="card-header py-3">
                            <h5 class="card-title mb-0">Informations générales</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="prenom" class="form-label">Prénom</label>
                                    <input type="text" name="prenom" id="prenom" class="form-control" maxlength="255" value="{{ old('prenom') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="nom" class="form-label">Nom</label>
                                    <input type="text" name="nom" id="nom" class="form-control" maxlength="255" value="{{ old('nom') }}">
                                </div>

                                <div class="col-12">
                                    <label for="supplier_id" class="form-label">Fournisseur</label>
                                    <select name="supplier_id" id="supplier_id" class="form-select select2" required data-placeholder="Sélectionner un fournisseur">
                                        <option value=""></option>
                                        @foreach($suppliers as $sup)
                                            <option value="{{ $sup->id }}" @selected(old('supplier_id') == $sup->id)>
                                                {{ $sup->name }} @if($sup->cegid_id) ({{ $sup->cegid_id }}) @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label for="company_id" class="form-label">Société</label>
                                    <select name="company_id" id="company_id" class="form-select" required>
                                        <option value="">-- Sélectionner une société --</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}" @selected(old('company_id') == $company->id)>
                                                {{ $company->name }} ({{ $company->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label for="langue" class="form-label">{{ __('locale.lbl_langue') ?? 'Langue' }}</label>
                                    <select name="langue" id="langue" class="form-select">
                                        @php
                                            $sContactLang = old('langue', 'Français');
                                            $allLangs = isset($languages) ? $languages : \App\Models\Language::where('is_active', true)->orderBy('name')->get();
                                        @endphp
                                        @foreach($allLangs as $lOpt)
                                            <option value="{{ $lOpt->name }}" @selected($sContactLang === $lOpt->name)>{{ $lOpt->name }}</option>
                                        @endforeach
                                        @if(!empty($sContactLang) && !$allLangs->contains('name', $sContactLang))
                                            <option value="{{ $sContactLang }}" selected>{{ $sContactLang }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Card -->
                    <div class="module-form-card card shadow-sm mb-4">
                        <div class="card-header py-3">
                            <h5 class="card-title mb-0">Adresse</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="adresse_1" class="form-label">Adresse 1</label>
                                    <input type="text" name="adresse_1" id="adresse_1" class="form-control" maxlength="255" value="{{ old('adresse_1') }}">
                                </div>
                                <div class="col-12">
                                    <label for="adresse_2" class="form-label">Adresse 2</label>
                                    <input type="text" name="adresse_2" id="adresse_2" class="form-control" maxlength="255" value="{{ old('adresse_2') }}">
                                </div>
                                <div class="col-12">
                                    <label for="adresse_3" class="form-label">Adresse 3</label>
                                    <input type="text" name="adresse_3" id="adresse_3" class="form-control" maxlength="255" value="{{ old('adresse_3') }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="code_postal" class="form-label">Code postal</label>
                                    <input type="text" name="code_postal" id="code_postal" class="form-control" maxlength="50" value="{{ old('code_postal') }}">
                                </div>
                                <div class="col-md-8">
                                    <label for="ville" class="form-label">Ville</label>
                                    <input type="text" name="ville" id="ville" class="form-control" maxlength="255" value="{{ old('ville') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact details -->
                    <div class="module-form-card card shadow-sm">
                        <div class="card-header py-3">
                            <h5 class="card-title mb-0">Coordonnées</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="email" class="form-label">Adresse messagerie (Email)</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input type="email" name="email" id="email" class="form-control" maxlength="255" value="{{ old('email') }}">
                                    </div>
                                </div>
                                @for ($i = 1; $i <= 3; $i++)
                                <div class="col-md-4">
                                    <label for="phone{{ $i }}" class="form-label">Téléphone {{ $i }}</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                        <input type="text" name="phone{{ $i }}" id="phone{{ $i }}" class="form-control" maxlength="50" value="{{ old('phone' . $i) }}">
                                    </div>
                                </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Card -->
                <div class="col-xl-4 col-lg-5">
                    <div class="module-form-card card shadow-sm">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary w-100 mb-3">Enregistrer</button>
                            <a href="{{ route('supplier-contacts.index') }}" class="btn btn-outline-secondary w-100">Annuler</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
