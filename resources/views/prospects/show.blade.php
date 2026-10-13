@extends('layouts.app')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('prospects.index') }}" class="btn btn-outline-secondary btn-sm border-0 bg-transparent py-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h1 class="mb-0">{{ $prospect->raison_sociale }}</h1>
        @if($prospect->type)
            <span class="badge badge-matte-info">{{ $prospect->type }}</span>
        @endif
    </div>
@endsection

@section('content')
<div class="container-fluid mt-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <!-- Main Details Column -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="mb-0 text-primary"><i class="bi bi-info-circle-fill me-2"></i>Détails du Prospect</h5>
                    <div>
                        <a href="{{ route('prospects.edit', $prospect->id) }}" class="btn btn-outline-primary btn-sm me-2">
                            <i class="bi bi-pencil-square"></i> Modifier
                        </a>
                        <form action="{{ route('prospects.destroy', $prospect->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('locale.delete_confirmation') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-trash"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small fw-semibold">Raison Sociale</label>
                            <div class="fs-5 text-dark fw-bold">{{ $prospect->raison_sociale }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small fw-semibold">Type</label>
                            <div>{{ $prospect->type ?? 'Non spécifié' }}</div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="text-muted small fw-semibold">Téléphone 1</label>
                            <div>{{ $prospect->tel_1 ?: 'Non disponible' }}</div>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small fw-semibold">Téléphone 2</label>
                            <div>{{ $prospect->tel_2 ?: 'Non disponible' }}</div>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small fw-semibold">Téléphone 3</label>
                            <div>{{ $prospect->tel_3 ?: 'Non disponible' }}</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small fw-semibold">E-mail</label>
                            <div>
                                @if($prospect->email)
                                    <a href="mailto:{{ $prospect->email }}">{{ $prospect->email }}</a>
                                @else
                                    Non disponible
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small fw-semibold">Site Web</label>
                            <div>
                                @if($prospect->site_web)
                                    <a href="{{ $prospect->site_web }}" target="_blank">{{ $prospect->site_web }}</a>
                                @else
                                    Non disponible
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="mb-3">
                        <label class="text-muted small fw-semibold">Adresse postale</label>
                        <div>
                            @php
                                $addressParts = array_filter([
                                    $prospect->adresse_1,
                                    $prospect->adresse_2,
                                    $prospect->adresse_3,
                                    trim(implode(' ', array_filter([$prospect->code_postal, $prospect->ville]))),
                                    $prospect->region,
                                    $prospect->pays
                                ]);
                            @endphp
                            @forelse($addressParts as $part)
                                <div>{{ $part }}</div>
                            @empty
                                <div class="text-muted">Aucune adresse renseignée</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Construction Reports Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary"><i class="bi bi-file-earmark-text-fill me-2"></i>Comptes Rendus de Chantier</h5>
                    <a href="{{ route('construction-reports.create', ['type' => 'prospect', 'prospect_id' => $prospect->id]) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-lg"></i> Nouveau Rapport
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>N° Rapport</th>
                                    <th>Projet</th>
                                    <th>Date</th>
                                    <th>Lieu</th>
                                    <th>Statut</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($prospect->constructionReports as $report)
                                    <tr>
                                        <td><strong>{{ $report->report_number ?: 'N/A' }}</strong></td>
                                        <td>{{ $report->project->name ?? 'Sans projet' }}</td>
                                        <td>{{ $report->date ? $report->date->format('d/m/Y') : 'N/A' }}</td>
                                        <td>{{ $report->location ?: 'N/A' }}</td>
                                        <td>
                                            @if($report->status === 'published')
                                                <span class="badge bg-success">Publié</span>
                                            @else
                                                <span class="badge bg-warning">Brouillon</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('construction-reports.show', $report->id) }}" class="btn btn-sm btn-outline-secondary">Voir</a>
                                            <a href="{{ route('construction-reports.edit', $report->id) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Aucun compte rendu associé à ce prospect.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions/Notes Column -->
        <div class="col-lg-4">
            <!-- Convert Card -->
            <div class="card border-0 shadow-sm mb-4 bg-light-success border-start border-success border-4" style="background-color: #f0fdf4;">
                <div class="card-body py-4">
                    <h5 class="text-success fw-bold mb-2"><i class="bi bi-person-check-fill me-2"></i>Convertir en Client</h5>
                    <p class="small text-muted mb-4">
                        Transformez ce prospect en client actif en un clic. Tous les comptes rendus et détails de contact seront migrés vers la fiche client créée.
                    </p>
                    <div class="d-grid">
                        <form action="{{ route('prospects.convert', $prospect->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment convertir ce prospect en client ? Cette action est irréversible.')">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
                                <i class="bi bi-check-circle-fill me-2"></i>{{ __('locale.btn_convert_to_client') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Notes Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 text-primary"><i class="bi bi-journal-text me-2"></i>Commentaires & Bloc-notes</h5>
                </div>
                <div class="card-body">
                    @if($prospect->commentaire)
                        <div class="mb-4">
                            <label class="text-muted small fw-semibold">Commentaire</label>
                            <div class="p-3 bg-light rounded text-dark small" style="white-space: pre-wrap;">{{ $prospect->commentaire }}</div>
                        </div>
                    @endif

                    <div>
                        <label class="text-muted small fw-semibold">Bloc-notes interne</label>
                        <div class="p-3 bg-light-yellow rounded text-dark small" style="white-space: pre-wrap; background-color: #fefbeb; border-left: 3px solid #d97706;">{{ $prospect->bloc_note ?: 'Aucune note interne.' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
