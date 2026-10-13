@extends('layouts.app')

@section('title', __('locale.add_remise_ascom') ?? 'Ajouter une remise Ascom')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('remises_ascom.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0 text-dark" title="{{ __('locale.back') ?? 'Retour' }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ __('locale.add_remise_ascom') ?? 'Ajouter une remise Ascom' }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('remises_ascom.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-1 fs-12">{{ __('locale.cancel') ?? 'Annuler' }}</a>
        <button type="submit" form="remiseCreateForm" class="btn btn-sm btn-primary px-3 py-1 fs-12">
            <i class="bi bi-check-lg me-1"></i>{{ __('locale.save') ?? 'Enregistrer' }}
        </button>
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card card-premium shadow-sm border-0">
                    <div class="card-header bg-light border-bottom py-3">
                        <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
                            <i class="bi bi-percent me-2 text-primary"></i>{{ __('locale.add_remise_ascom') ?? 'Ajouter une remise Ascom' }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('remises_ascom.store') }}" method="POST" id="remiseCreateForm">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="code_remise" class="form-label fw-semibold">{{ __('locale.code_remise') ?? 'Code remise' }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('code_remise') is-invalid @enderror" id="code_remise" name="code_remise" value="{{ old('code_remise') }}" placeholder="Ex: R01, REM-PROMO..." required autofocus>
                                    @error('code_remise')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="pourcentage" class="form-label fw-semibold">{{ __('locale.pourcentage') ?? 'Pourcentage (%)' }} <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" max="100" class="form-control @error('pourcentage') is-invalid @enderror" id="pourcentage" name="pourcentage" value="{{ old('pourcentage') }}" placeholder="Ex: 15.00" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                    @error('pourcentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
