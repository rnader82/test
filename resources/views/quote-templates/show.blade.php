@extends('layouts.app')

@section('title', 'Détails du Modèle - ' . $template->name)

@section('content')
<div class="container-fluid py-4">
    <!-- Breadcrumb & Top Actions -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('quote-templates.index') }}" class="text-decoration-none">Modèles de devis</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $template->name }}</li>
            </ol>
        </nav>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('quote-templates.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
            <a href="{{ route('quote-templates.edit', $template) }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                <i class="bi bi-pencil-square me-1"></i> Modifier
            </a>
            <form action="{{ route('quote-templates.duplicate', $template) }}" method="POST" class="d-inline" onsubmit="return confirm('Dupliquer ce modèle ?');">
                @csrf
                <button type="submit" class="btn btn-outline-secondary rounded-pill px-3 fw-semibold">
                    <i class="fa-solid fa-copy me-1"></i> Dupliquer
                </button>
            </form>
            <form action="{{ route('quote-templates.destroy', $template) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce modèle de devis ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger rounded-pill px-3">
                    <i class="bi bi-trash me-1"></i> Supprimer
                </button>
            </form>
        </div>
    </div>

    <!-- Header Banner Panel (Matching Screenshot) -->
    <div class="card card-premium p-4 mb-4 shadow-sm border-0 bg-white">
        <div class="row align-items-center">
            <!-- Left Info -->
            <div class="col-md-5">
                <h2 class="mb-1 fw-bold text-dark fs-4">
                    {{ $template->name }}
                </h2>
                <div class="small text-muted mb-2">
                    <span class="d-inline-block me-3">
                        <strong>Catégorie :</strong> 
                        <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 rounded-pill">{{ optional($template->category)->name ?? '(non spécifié)' }}</span>
                    </span>
                    <span class="d-inline-block">
                        <strong>Famille :</strong> 
                        <span class="badge bg-info bg-opacity-10 text-info px-2 py-1 rounded-pill">{{ optional($template->family)->name ?? '(non spécifié)' }}</span>
                    </span>
                </div>
                <div class="mt-2 d-flex align-items-center gap-2 flex-wrap">
                    <span class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-bold bg-white border-light text-secondary d-inline-flex align-items-center gap-2">
                        <i class="bi bi-truck text-primary fs-6"></i>
                        <span>Port : {{ number_format((float) $template->port_ht, 2, ',', ' ') }} €</span>
                    </span>
                    @if($template->internal_note)
                        <span class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-bold bg-white border-light text-secondary d-inline-flex align-items-center gap-2">
                            <i class="bi bi-file-earmark-text text-secondary fs-6"></i>
                            <span>Note présente</span>
                        </span>
                    @endif
                </div>
            </div>

            <!-- Center Tabs -->
            <div class="col-md-3 text-center my-3 my-md-0">
                <ul class="nav nav-pills justify-content-center border-0" id="templateShowTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-3 py-2 fw-bold" id="info-show-tab" data-bs-toggle="tab" data-bs-target="#tab-show-info" type="button" role="tab">
                            <i class="bi bi-info-circle me-1"></i> Informations
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active px-3 py-2 fw-bold" id="produits-show-tab" data-bs-toggle="tab" data-bs-target="#tab-show-produits" type="button" role="tab">
                            <i class="bi bi-list-task me-1"></i> Produits ({{ $lines->count() }})
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Right Total & Discount -->
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <div class="d-flex flex-column align-items-md-end gap-1">
                    <div>
                        <span class="text-muted fw-bold text-uppercase d-block mb-0" style="font-size: 0.75rem;">Total HT</span>
                        <h3 class="text-primary fw-bold mb-0 fs-3">
                            {{ number_format((float) ($template->total_ht ?? 0), 2, ',', ' ') }} €
                        </h3>
                    </div>
                    @if($template->remise_pied > 0 || $template->remise_pied_percent > 0)
                        <div class="text-md-end border-top pt-2">
                            <span class="text-muted small">Remise :</span>
                            <span class="badge bg-danger-subtle text-danger fw-bold">
                                @if($template->remise_pied_percent > 0)
                                    {{ number_format((float) $template->remise_pied_percent, 2, ',', ' ') }} %
                                @else
                                    {{ number_format((float) $template->remise_pied, 2, ',', ' ') }} €
                                @endif
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Content -->
    <div class="tab-content bg-transparent p-0 border-0" id="templateShowTabsContent">
        
        <!-- Tab 1: Informations -->
        <div class="tab-pane fade" id="tab-show-info" role="tabpanel">
            <div class="card card-premium p-4 shadow-sm border-0 mb-4">
                <h5 class="fw-bold text-dark mb-4"><i class="bi bi-info-circle text-primary me-2"></i>Informations Générales</h5>
                
                <div class="row g-4">
                    <div class="col-lg-6 border-end pe-lg-4">
                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Nom du modèle</label>
                            <div class="fw-bold text-dark fs-6">{{ $template->name }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Description / Notes publiques</label>
                            <div class="text-secondary bg-light p-3 rounded-3">{{ $template->description ?: 'Aucune description.' }}</div>
                        </div>

                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label small text-muted mb-1">Catégorie de produit</label>
                                <div class="fw-semibold text-dark">{{ optional($template->category)->name ?? '-' }}</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label small text-muted mb-1">Famille de produit</label>
                                <div class="fw-semibold text-dark">{{ optional($template->family)->name ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 ps-lg-4">
                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Client spécifique</label>
                            <div class="fw-semibold text-dark">{{ optional($template->client)->raison_sociale ?? 'Tous les clients (Global)' }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Société / Agence</label>
                            <div class="fw-semibold text-dark">{{ optional($template->company)->name ?? 'Toutes les sociétés' }}</div>
                        </div>

                        @if($template->internal_note)
                            <div class="mb-3">
                                <label class="form-label small text-muted mb-1">Notes Internes</label>
                                <div class="text-secondary bg-light p-3 rounded-3">{{ $template->internal_note }}</div>
                            </div>
                        @endif

                        @if($template->reserves)
                            <div class="mb-3">
                                <label class="form-label small text-muted mb-1">Réserves associées</label>
                                <div class="text-secondary bg-light p-3 rounded-3">{{ $template->reserves }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 2: Produits Table -->
        <div class="tab-pane fade show active" id="tab-show-produits" role="tabpanel">
            <div class="card card-premium shadow-sm border-0">
                <div class="card-header bg-transparent border-0 pt-3 px-3 pb-2 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 fw-bold d-flex align-items-center text-dark">
                        <i class="bi bi-list-task text-primary me-2 fs-5"></i> Lignes d'articles et chapitres
                    </h5>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                        {{ $lines->count() }} élément(s)
                    </span>
                </div>

                <div class="card-body p-0 overflow-auto">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0" style="min-width: 820px;">
                            <thead class="table-light text-uppercase small text-muted">
                                <tr>
                                    <th style="width: 40px;" class="text-center">#</th>
                                    <th>Article</th>
                                    <th class="text-center" style="width: 80px;">Qté</th>
                                    <th class="text-end" style="width: 110px;">PxPub</th>
                                    <th class="text-end" style="width: 85px;">R1</th>
                                    <th class="text-end" style="width: 85px;">R2</th>
                                    <th class="text-end" style="width: 110px;">PuHT</th>
                                    <th class="text-end" style="width: 120px;">PxTot</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lines as $line)
                                    @php
                                        $isOption = (bool) $line->is_option;
                                        $qte = (float) $line->qte;
                                        $puHt = (float) $line->pu_ht;
                                        $r1 = (float) $line->remise_ligne;
                                        $r2 = (float) $line->remise_2;
                                        $puNet = $puHt * (1 - ($r1 / 100)) * (1 - ($r2 / 100));
                                        $pxTot = $qte * $puNet;
                                    @endphp

                                    @if($line->type_ligne === 'Chapitre')
                                        <tr style="background-color: #fbe9d7 !important; font-weight: 700; color: #b76413;">
                                            <td class="text-center text-muted"><i class="bi bi-folder2-open"></i></td>
                                            <td colspan="7" class="py-2">
                                                <div class="d-flex align-items-center gap-2">
                                                    @if($isOption)
                                                        <span class="badge bg-warning text-dark px-2 py-1">Option</span>
                                                    @endif
                                                    <span class="fs-6">{{ $line->libelle }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @elseif($line->type_ligne === 'Section')
                                        <tr style="background-color: #f4f9fd !important; font-weight: 600; color: #0288d1;">
                                            <td class="text-center text-muted"><i class="bi bi-window-sidebar"></i></td>
                                            <td colspan="7" class="ps-4 py-2">
                                                <i class="bi bi-display text-primary me-2"></i>{{ $line->libelle }}
                                            </td>
                                        </tr>
                                    @elseif($line->type_ligne === 'Commentaire')
                                        <tr style="background-color: #fbfbfb !important; font-style: italic;">
                                            <td class="text-center text-muted"><i class="bi bi-chat-left-text"></i></td>
                                            <td colspan="7" class="ps-4 text-muted py-2">
                                                {{ $line->libelle }}
                                            </td>
                                        </tr>
                                    @else
                                        <tr @if($isOption) style="background-color: #e8f5e9 !important;" @endif>
                                            <td class="text-center text-muted small">{{ $line->position }}</td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if($isOption)
                                                            <span class="badge bg-warning text-dark px-1 py-0" style="font-size: 0.7rem;">Option</span>
                                                        @endif
                                                        <span class="fw-bold text-dark small">{{ $line->code_article }}</span>
                                                    </div>
                                                    <span class="text-muted small">{{ $line->libelle }}</span>
                                                </div>
                                            </td>
                                            <td class="text-center fw-semibold">{{ number_format($qte, 0, ',', ' ') }}</td>
                                            <td class="text-end">{{ number_format($puHt, 2, ',', ' ') }} €</td>
                                            <td class="text-end text-muted small">{{ number_format($r1, 2, ',', ' ') }} %</td>
                                            <td class="text-end text-muted small">{{ number_format($r2, 2, ',', ' ') }} %</td>
                                            <td class="text-end fw-semibold text-secondary small">{{ number_format($puNet, 2, ',', ' ') }} €</td>
                                            <td class="text-end fw-bold text-dark">{{ number_format($pxTot, 2, ',', ' ') }} €</td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            Aucune ligne dans ce modèle de devis.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card-premium {
    border-radius: 12px;
    background: #ffffff;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
}
.nav-pills .nav-link {
    color: #495057;
    border-radius: 20px;
    margin: 0 4px;
}
.nav-pills .nav-link.active {
    background-color: #0288d1;
    color: #ffffff;
}
</style>
@endsection
