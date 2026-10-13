@extends('layouts.app')

@section('title', 'Comptes Rendus de Chantier')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1 class="mb-0">Comptes Rendus de Chantier</h1>
    </div>
@endsection

@section('page-actions')
    <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" id="createReportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_new_construction_report') }}
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="createReportDropdown">
            <li>
                <a class="dropdown-item" href="{{ route('construction-reports.create', ['type' => 'client']) }}">
                    <i class="bi bi-building me-2 text-primary"></i> {{ __('locale.lbl_client') }}
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route('construction-reports.create', ['type' => 'prospect']) }}">
                    <i class="bi bi-person-plus me-2 text-success"></i> {{ __('locale.lbl_prospect') }}
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
<div class="container-fluid mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary">Liste des rapports</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reportTypeModal">
                <i class="bi bi-plus-lg"></i> Nouveau Rapport
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>N° Rapport</th>
                            <th>Projet</th>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Lieu</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                            <tr>
                                <td class="fw-bold text-dark">{{ $report->report_number }}</td>
                                <td>{{ $report->project->name ?? 'N/A' }}</td>
                                <td>
                                    @if($report->client)
                                        {{ $report->client->raison_sociale }}
                                    @elseif($report->prospect)
                                        <span class="text-success" title="Prospect"><i class="bi bi-person-plus-fill me-1"></i>{{ $report->prospect->raison_sociale }}</span>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $report->date->format('d/m/Y') }}</td>
                                <td>{{ $report->location }}</td>
                                <td>
                                    @if($report->status === 'draft')
                                        <span class="badge bg-warning text-dark">Brouillon</span>
                                    @else
                                        <span class="badge bg-success">Publié</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <a href="{{ route('construction-reports.export', $report) }}" class="btn btn-outline-secondary btn-sm" title="Exporter en PDF">
                                            <i class="bi bi-file-earmark-pdf"></i>
                                        </a>
                                        <a href="{{ route('construction-reports.edit', $report) }}" class="btn btn-outline-primary btn-sm" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('construction-reports.destroy', $report) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Aucun rapport trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($reports->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $reports->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Choice Modal -->
<div class="modal fade" id="reportTypeModal" tabindex="-1" aria-labelledby="reportTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="reportTypeModalLabel">Nouveau Compte Rendu</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <p class="mb-4 text-muted">Veuillez choisir pour qui est destiné ce compte rendu de chantier :</p>
                <div class="d-grid gap-3">
                    <a href="{{ route('construction-reports.create', ['type' => 'client']) }}" class="btn btn-outline-primary py-3">
                        <i class="bi bi-building fs-3 d-block mb-2"></i>
                        <strong>Rapport pour un Client</strong>
                    </a>
                    <a href="{{ route('construction-reports.create', ['type' => 'prospect']) }}" class="btn btn-outline-success py-3">
                        <i class="bi bi-person-plus fs-3 d-block mb-2"></i>
                        <strong>Rapport pour un Prospect</strong>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
