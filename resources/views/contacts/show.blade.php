@extends('layouts.app')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ $contact->client_id ? route('clients.show', ['client' => $contact->client_id, 'tab' => 'contacts']) : route('contacts.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ $contact->prenom }} {{ Str::upper($contact->nom) }}</h1>
        <span class="badge bg-label-primary ms-2">{{ $contact->type_label }}</span>
        @if($contact->function_code)
            <span class="badge bg-label-secondary ms-1">{{ $contact->function_code }}</span>
        @endif
        @if($contact->odoo_id)
            @if(config('services.odoo.url'))
                <a href="{{ rtrim(config('services.odoo.url'), '/') }}/web#id={{ $contact->odoo_id }}&model=res.partner&view_type=form" target="_blank" rel="noopener" class="badge bg-secondary-subtle text-secondary border border-secondary-subtle font-monospace text-decoration-none ms-1" title="Ouvrir dans Odoo">
                    <i class="bi bi-hash me-1"></i>Odoo ID: {{ $contact->odoo_id }} <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                </a>
            @else
                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle font-monospace ms-1"><i class="bi bi-hash me-1"></i>Odoo ID: {{ $contact->odoo_id }}</span>
            @endif
        @endif
    </div>
@endsection

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('contacts.edit', $contact->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil-square me-1"></i> {{ __('locale.edit') }}
        </a>
        <form action="{{ route('contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('{{ __('locale.delete_confirmation') }}')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-trash me-1"></i> {{ __('locale.delete') }}
            </button>
        </form>
    </div>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        <div class="row g-4">
            <!-- Left Column: Primary Details -->
            <div class="col-xl-4 col-lg-5 col-md-5">
                <!-- Contact Summary Card -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-body">
                        <div class="user-avatar-section mb-4">
                            <div class="d-flex align-items-center flex-column">
                                <div class="avatar-wrapper mb-3">
                                    <div class="avatar avatar-xl">
                                        <div class="avatar-initial rounded bg-label-primary fs-2 fw-bold">
                                            {{ strtoupper(substr($contact->prenom ?? '', 0, 1) . substr($contact->nom ?? '', 0, 1)) ?: '?' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="user-info text-center">
                                    <h4 class="mb-1">{{ $contact->civility }} {{ $contact->prenom }} {{ $contact->nom }}</h4>
                                    <div class="d-flex gap-1 justify-content-center flex-wrap">
                                        <span class="badge bg-label-info">{{ $contact->type_label }}</span>
                                        @if($contact->primary)
                                            <span class="badge bg-label-warning"><i class="bi bi-star-fill me-1"></i>{{ __('locale.primary') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="info-container">
                            <h5 class="pb-2 border-bottom mb-3">{{ __('locale.details') }}</h5>
                            <ul class="list-unstyled">
                                <li class="mb-3 d-flex align-items-center">
                                    <i class="bi bi-hash me-2 text-primary"></i>
                                    <span class="fw-medium me-2">{{ __('locale.lbl_odoo_id') ?? 'Odoo ID' }}:</span>
                                    @if($contact->odoo_id)
                                        @if(config('services.odoo.url'))
                                            <a href="{{ rtrim(config('services.odoo.url'), '/') }}/web#id={{ $contact->odoo_id }}&model=res.partner&view_type=form" target="_blank" rel="noopener" class="text-decoration-none font-monospace fw-semibold" title="Ouvrir dans Odoo">
                                                {{ $contact->odoo_id }} <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                            </a>
                                        @else
                                            <span class="font-monospace text-muted">{{ $contact->odoo_id }}</span>
                                        @endif
                                    @else
                                        <span class="text-muted">{{ __('locale.not_available') }}</span>
                                    @endif
                                </li>
                                <li class="mb-3 d-flex align-items-center">
                                    <i class="bi bi-envelope me-2 text-primary"></i>
                                    <span class="fw-medium me-2">{{ __('locale.email') }}:</span>
                                    <span class="text-muted">{{ $contact->email ?: __('locale.not_available') }}</span>
                                </li>
                                <li class="mb-3 d-flex align-items-center">
                                    <i class="bi bi-telephone me-2 text-primary"></i>
                                    <span class="fw-medium me-2">{{ __('locale.lbl_phone') }} 1:</span>
                                    <span class="text-muted">{{ $contact->phone1 ?: __('locale.not_available') }}</span>
                                </li>
                                @if($contact->mobile)
                                <li class="mb-3 d-flex align-items-center">
                                    <i class="bi bi-phone me-2 text-primary"></i>
                                    <span class="fw-medium me-2">{{ __('locale.lbl_mobile') }}:</span>
                                    <span class="text-muted">{{ $contact->mobile }}</span>
                                </li>
                                @endif
                                @if($contact->phone2)
                                <li class="mb-3 d-flex align-items-center">
                                    <i class="bi bi-phone me-2 text-primary"></i>
                                    <span class="fw-medium me-2">{{ __('locale.lbl_phone') }} 2:</span>
                                    <span class="text-muted">{{ $contact->phone2 }}</span>
                                </li>
                                @endif
                                @if($contact->phone3)
                                <li class="mb-3 d-flex align-items-center">
                                    <i class="bi bi-phone-fill me-2 text-primary"></i>
                                    <span class="fw-medium me-2">{{ __('locale.lbl_phone') }} 3:</span>
                                    <span class="text-muted">{{ $contact->phone3 }}</span>
                                </li>
                                @endif
                                @if($contact->gln)
                                <li class="mb-3 d-flex align-items-center">
                                    <i class="bi bi-upc-scan me-2 text-primary"></i>
                                    <span class="fw-medium me-2">{{ __('locale.gln') }}:</span>
                                    <span class="text-muted">{{ $contact->gln }}</span>
                                </li>
                                @endif
                                <li class="mb-3 d-flex align-items-center">
                                    <i class="bi bi-translate me-2 text-primary"></i>
                                    <span class="fw-medium me-2">{{ __('locale.lbl_langue') ?? 'Langue' }}:</span>
                                    <span class="text-muted">{{ $contact->langue ?: 'Français' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Client Info Card -->
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="pb-2 border-bottom mb-3">{{ __('locale.lbl_client') }}</h5>
                        @if($contact->client)
                            <div class="d-flex align-items-center gap-3 clickable mb-3" onclick="window.location='{{ route('clients.show', ['client' => $contact->client_id, 'tab' => 'contacts']) }}'" style="cursor: pointer;">
                                <div class="avatar bg-label-info p-2 rounded">
                                    <i class="bi bi-building fs-4"></i>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <h6 class="mb-0 text-truncate">{{ $contact->client->raison_sociale }}</h6>
                                    <small class="text-muted text-truncate d-block">{{ $contact->client->ville }}</small>
                                </div>
                                <i class="bi bi-chevron-right text-muted"></i>
                            </div>
                            <div class="small text-muted">
                                <p class="mb-1"><i class="bi bi-geo-alt me-1"></i> {{ $contact->client->adresse_1 }} {{ $contact->client->adresse_2 }}</p>
                                <p class="mb-0"><i class="bi bi-hash me-1"></i> {{ $contact->client->cegid_id }}</p>
                            </div>
                        @else
                            <p class="text-muted mb-0 italic">{{ __('locale.lbl_no_client') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Additional Details -->
            <div class="col-xl-8 col-lg-7 col-md-7">
                <!-- Address Card -->
                @if($contact->adresse_1 || $contact->ville || $contact->code_postal)
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header py-3 bg-light border-0">
                        <h5 class="card-title mb-0 fs-6 fw-bold text-secondary">
                            <i class="bi bi-geo-alt me-2 text-primary"></i>{{ __('locale.contact_address') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="p-3 bg-light rounded-3">
                            <div class="fw-semibold fs-6">{{ $contact->adresse_1 }}</div>
                            @if($contact->adresse_2)
                                <div class="text-muted">{{ $contact->adresse_2 }}</div>
                            @endif
                            <div class="text-muted mt-1">{{ trim(($contact->code_postal ?? '') . ' ' . ($contact->ville ?? '')) }}</div>
                            @if($contact->state || $contact->pays)
                                <div class="text-muted small">{{ implode(', ', array_filter([$contact->state, $contact->pays])) }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Notes Internes Card -->
                @if($contact->notes)
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header py-3 bg-light border-0">
                        <h5 class="card-title mb-0 fs-6 fw-bold text-secondary">
                            <i class="bi bi-card-text me-2 text-primary"></i>{{ __('locale.notes') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="p-3 bg-light rounded-3 text-muted whitespace-pre-wrap">
                            {{ $contact->notes }}
                        </div>
                    </div>
                </div>
                @endif

                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header d-flex align-items-center justify-content-between py-3 bg-light border-0">
                        <h5 class="card-title mb-0 fs-6 fw-bold text-secondary">{{ __('locale.informations') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 bg-light">
                                    <label class="text-muted small fw-semibold d-block mb-1">{{ __('locale.lbl_odoo_id') ?? 'Odoo ID' }}</label>
                                    <div class="fw-medium font-monospace">
                                        @if($contact->odoo_id)
                                            @if(config('services.odoo.url'))
                                                <a href="{{ rtrim(config('services.odoo.url'), '/') }}/web#id={{ $contact->odoo_id }}&model=res.partner&view_type=form" target="_blank" rel="noopener" class="text-decoration-none" title="Ouvrir dans Odoo">
                                                    {{ $contact->odoo_id }} <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                                </a>
                                            @else
                                                {{ $contact->odoo_id }}
                                            @endif
                                        @else
                                            <span class="text-muted">{{ __('locale.not_available') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 bg-light">
                                    <label class="text-muted small fw-semibold d-block mb-1">{{ __('locale.function_code') }}</label>
                                    <div class="fw-medium">{{ $contact->function_code ?: __('locale.not_available') }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 bg-light">
                                    <label class="text-muted small fw-semibold d-block mb-1">{{ __('locale.service_code') }}</label>
                                    <div class="fw-medium">{{ $contact->service_code ?: __('locale.not_available') }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between">
                                    <div>
                                        <label class="text-muted small fw-semibold d-block mb-1">{{ __('locale.lbl_closed') }}</label>
                                        <div class="fw-medium">{{ $contact->closed ? __('locale.yes') : __('locale.no') }}</div>
                                    </div>
                                    <div class="badge rounded-pill {{ $contact->closed ? 'bg-label-danger' : 'bg-label-success' }} p-2">
                                        <i class="bi {{ $contact->closed ? 'bi-lock-fill' : 'bi-unlock-fill' }}"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between">
                                    <div>
                                        <label class="text-muted small fw-semibold d-block mb-1">{{ __('locale.primary') }}</label>
                                        <div class="fw-medium">{{ $contact->primary ? __('locale.yes') : __('locale.no') }}</div>
                                    </div>
                                    <div class="badge rounded-pill {{ $contact->primary ? 'bg-label-warning' : 'bg-label-secondary' }} p-2">
                                        <i class="bi {{ $contact->primary ? 'bi-star-fill' : 'bi-star' }}"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($contact->client && $contact->client->issues->isNotEmpty())
                <div class="card shadow-sm">
                    <div class="card-header py-3">
                        <h5 class="card-title mb-0">{{ __('locale.lbl_issues') }} ({{ $contact->client->issues->count() }})</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('locale.lbl_id') }}</th>
                                        <th>{{ __('locale.subject') }}</th>
                                        <th>{{ __('locale.lbl_status') }}</th>
                                        <th>{{ __('locale.lbl_created_at') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contact->client->issues->take(5) as $issue)
                                    <tr onclick="window.location='{{ route('issues.show', $issue->id) }}'" style="cursor: pointer;">
                                        <td>#{{ $issue->id }}</td>
                                        <td class="text-truncate" style="max-width: 200px;">{{ $issue->subject }}</td>
                                        <td>
                                            <span class="badge" style="background-color: {{ hexToRgba($issue->status?->color ?? '#6c757d', 0.15) }}; color: {{ $issue->status?->color ?? '#6c757d' }}">
                                                {{ $issue->status?->translated_name ?? $issue->status?->name }}
                                            </span>
                                        </td>
                                        <td class="small">{{ $issue->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .bg-label-primary { background-color: rgba(145, 55, 142, 0.10) !important; color: #91378e !important; }
    .bg-label-secondary { background-color: rgba(133, 146, 163, 0.16) !important; color: #8592a3 !important; }
    .bg-label-success { background-color: rgba(113, 221, 55, 0.16) !important; color: #71dd37 !important; }
    .bg-label-info { background-color: rgba(3, 195, 236, 0.16) !important; color: #03c3ec !important; }
    .bg-label-warning { background-color: rgba(255, 171, 0, 0.16) !important; color: #ffab00 !important; }
    .bg-label-danger { background-color: rgba(255, 62, 29, 0.16) !important; color: #ff3e1d !important; }
    
    .avatar-xl { width: 100px; height: 100px; }
    .avatar-initial { display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; }
</style>
@endsection

