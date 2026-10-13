@extends('layouts.app')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('supplier-contacts.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ $supplierContact->prenom }} {{ Str::upper($supplierContact->nom) }}</h1>
        <span class="badge bg-label-success ms-2">Contact Fournisseur</span>
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="d-flex justify-content-end gap-2 mb-4">
            <a href="{{ route('supplier-contacts.edit', $supplierContact->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil-square me-1"></i> Modifier
            </a>
            <form action="{{ route('supplier-contacts.destroy', $supplierContact->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce contact fournisseur ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash me-1"></i> Supprimer
                </button>
            </form>
        </div>

        <div class="row g-4">
            <!-- Left Column: Primary Details -->
            <div class="col-xl-4 col-lg-5 col-md-5">
                <!-- Contact Summary Card -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <div class="user-avatar-section mb-4">
                            <div class="d-flex align-items-center flex-column">
                                <div class="avatar-wrapper mb-3">
                                    <div class="avatar avatar-xl" style="width: 80px; height: 80px;">
                                        <div class="avatar-initial rounded bg-label-success fs-1 fw-bold d-flex align-items-center justify-content-center h-100 w-100" style="background-color: rgba(40, 167, 69, 0.1); color: #28a745;">
                                            {{ strtoupper(substr($supplierContact->prenom ?? '', 0, 1) . substr($supplierContact->nom ?? '', 0, 1)) ?: '?' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="user-info text-center">
                                    <h4 class="mb-1">{{ $supplierContact->prenom }} {{ $supplierContact->nom }}</h4>
                                </div>
                            </div>
                        </div>

                        <div class="info-container">
                            <h5 class="pb-2 border-bottom mb-3">Détails de contact</h5>
                            <ul class="list-unstyled">
                                <li class="mb-3 d-flex align-items-center">
                                    <i class="bi bi-envelope me-2 text-success"></i>
                                    <span class="fw-medium me-2">Email:</span>
                                    <span class="text-muted">{{ $supplierContact->email ?: 'Non renseigné' }}</span>
                                </li>
                                <li class="mb-3 d-flex align-items-center">
                                    <i class="bi bi-telephone me-2 text-success"></i>
                                    <span class="fw-medium me-2">Téléphone 1:</span>
                                    <span class="text-muted">{{ $supplierContact->phone1 ?: 'Non renseigné' }}</span>
                                </li>
                                @if($supplierContact->phone2)
                                <li class="mb-3 d-flex align-items-center">
                                    <i class="bi bi-phone me-2 text-success"></i>
                                    <span class="fw-medium me-2">Téléphone 2:</span>
                                    <span class="text-muted">{{ $supplierContact->phone2 }}</span>
                                </li>
                                @endif
                                @if($supplierContact->phone3)
                                <li class="mb-3 d-flex align-items-center">
                                    <i class="bi bi-phone-fill me-2 text-success"></i>
                                    <span class="fw-medium me-2">Téléphone 3:</span>
                                    <span class="text-muted">{{ $supplierContact->phone3 }}</span>
                                </li>
                                @endif
                                <li class="mb-3 d-flex align-items-center">
                                    <i class="bi bi-translate me-2 text-success"></i>
                                    <span class="fw-medium me-2">{{ __('locale.lbl_langue') ?? 'Langue' }}:</span>
                                    <span class="text-muted">{{ $supplierContact->langue ?: 'Français' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Supplier Info Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="pb-2 border-bottom mb-3">Fournisseur</h5>
                        @if($supplierContact->supplier)
                            <div class="d-flex align-items-center gap-3 clickable mb-3" onclick="window.location='{{ route('suppliers.show', $supplierContact->supplier_id) }}'" style="cursor: pointer;">
                                <div class="avatar bg-label-success p-2 rounded" style="background-color: rgba(40, 167, 69, 0.1); color: #28a745;">
                                    <i class="bi bi-truck fs-4"></i>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <h6 class="mb-0 text-truncate">{{ $supplierContact->supplier->name }}</h6>
                                    <small class="text-muted text-truncate d-block">{{ $supplierContact->supplier->ville }}</small>
                                </div>
                                <i class="bi bi-chevron-right text-muted"></i>
                            </div>
                            <div class="small text-muted">
                                @if($supplierContact->supplier->cegid_id)
                                    <div><strong>Code CEGID :</strong> {{ $supplierContact->supplier->cegid_id }}</div>
                                @endif
                                @if($supplierContact->supplier->odoo_id)
                                    <div>
                                        <strong>Odoo ID :</strong>
                                        @if(config('services.odoo.url'))
                                            <a href="{{ rtrim(config('services.odoo.url'), '/') }}/web#id={{ $supplierContact->supplier->odoo_id }}&model=res.partner&view_type=form"
                                               target="_blank"
                                               rel="noopener"
                                               class="text-decoration-none">
                                                {{ $supplierContact->supplier->odoo_id }} <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                            </a>
                                        @else
                                            {{ $supplierContact->supplier->odoo_id }}
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-muted italic">Aucun fournisseur associé.</div>
                        @endif
                    </div>
                </div>

                <!-- Company Info Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="pb-2 border-bottom mb-3">Société</h5>
                        @if($supplierContact->company)
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar bg-label-primary p-2 rounded" style="background-color: rgba(13, 110, 253, 0.1); color: #0d6efd;">
                                    <i class="bi bi-building fs-4"></i>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <h6 class="mb-0 text-truncate">{{ $supplierContact->company->name }}</h6>
                                    <small class="text-muted">{{ $supplierContact->company->code }}</small>
                                </div>
                            </div>
                        @else
                            <div class="text-muted italic">Aucune société associée.</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Address Details -->
            <div class="col-xl-8 col-lg-7 col-md-7">
                <div class="card shadow-sm">
                    <div class="card-header py-3">
                        <h5 class="card-title mb-0">Adresse du contact</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small mb-0">Adresse 1</label>
                                <div class="fw-medium">{{ $supplierContact->adresse_1 ?: '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small mb-0">Adresse 2</label>
                                <div class="fw-medium">{{ $supplierContact->adresse_2 ?: '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small mb-0">Adresse 3</label>
                                <div class="fw-medium">{{ $supplierContact->adresse_3 ?: '-' }}</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small mb-0">Code postal</label>
                                <div class="fw-medium">{{ $supplierContact->code_postal ?: '-' }}</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small mb-0">Ville</label>
                                <div class="fw-medium">{{ $supplierContact->ville ?: '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
