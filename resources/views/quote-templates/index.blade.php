@extends('layouts.app')
@section('portal-module', 'quote-templates')
@section('portal-page', 'index')
@section('portal-kind', 'list')
@section('portal-family', 'quotes')
@section('portal-icon', 'file-lines')


@section('title', 'Modèles de devis')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Page -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">
                <i class="bi bi-file-earmark-spreadsheet text-primary me-2"></i>Modèles de devis
            </h1>
            <p class="text-muted mb-0">Créez et configurez vos modèles de chiffrage réutilisables (packs matériels, licences, prestations).</p>
        </div>
        <div>
            <a href="{{ route('quote-templates.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                <i class="bi bi-plus-lg fs-6"></i>
                <span>Nouveau Modèle</span>
            </a>
        </div>
    </div>

    <!-- Feedback Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-3 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filters & Search Card -->
    <div class="card card-premium shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('quote-templates.index') }}" class="row g-3 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Rechercher par nom ou description..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="category_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary w-100 rounded-pill fw-semibold">
                        <i class="bi bi-funnel me-1"></i>Filtrer
                    </button>
                </div>
                <div class="col-md-2 text-md-end">
                    @if(request()->hasAny(['search', 'category_id', 'family_id']))
                        <a href="{{ route('quote-templates.index') }}" class="btn btn-outline-secondary w-100 rounded-pill">
                            <i class="bi bi-x-circle me-1"></i>Réinitialiser
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Templates Table Card -->
    <div class="card card-premium shadow-sm border-0">
        <div class="table-responsive">
            <table class="module-table table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase small text-muted">
                    <tr>
                        <th class="ps-4" style="width: 30%;">Nom du modèle</th>
                        <th>Catégorie / Famille</th>
                        <th class="text-center">Lignes</th>
                        <th class="text-end">Total HT</th>
                        <th class="text-center">Dernière modif.</th>
                        <th class="text-end pe-4" style="width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($templates as $tmpl)
                        <tr class="cursor-pointer row-clickable" onclick="window.location='{{ route('quote-templates.show', $tmpl) }}'">
                            <td class="ps-4">
                                <a href="{{ route('quote-templates.show', $tmpl) }}" class="fw-bold text-dark text-decoration-none d-block">
                                    {{ $tmpl->name }}
                                </a>
                                @if($tmpl->description)
                                    <small class="text-muted d-block text-truncate" style="max-width: 350px;">{{ $tmpl->description }}</small>
                                @endif
                            </td>
                            <td>
                                @if($tmpl->category)
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 rounded-pill">
                                        {{ $tmpl->category->name }}
                                    </span>
                                @endif
                                @if($tmpl->family)
                                    <span class="badge bg-info bg-opacity-10 text-info px-2 py-1 rounded-pill ms-1">
                                        {{ $tmpl->family->name }}
                                    </span>
                                @endif
                                @if(!$tmpl->category && !$tmpl->family)
                                    <span class="text-muted small">Non spécifié</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border px-2 py-1 rounded-pill">
                                    {{ $tmpl->lines_count }} article(s)
                                </span>
                            </td>
                            <td class="text-end fw-bold text-primary fs-6">
                                {{ number_format((float) $tmpl->total_ht, 2, ',', ' ') }} €
                            </td>
                            <td class="text-center text-muted small">
                                {{ $tmpl->updated_at ? $tmpl->updated_at->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="text-end pe-4" onclick="event.stopPropagation();">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('quote-templates.show', $tmpl) }}" class="btn btn-outline-info" title="Voir les détails">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('quote-templates.edit', $tmpl) }}" class="btn btn-outline-primary" title="Modifier">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('quote-templates.duplicate', $tmpl) }}" method="POST" class="d-inline" onsubmit="return confirm('Dupliquer ce modèle ?');">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary" title="Dupliquer">
                                            <i class="fa-solid fa-copy"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('quote-templates.destroy', $tmpl) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce modèle de devis ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                <span>Aucun modèle de devis trouvé.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($templates->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $templates->links() }}
            </div>
        @endif
    </div>
</div>

<style>
.cursor-pointer {
    cursor: pointer;
}
.row-clickable:hover td {
    background-color: #f1f5f9 !important;
}
</style>
@endsection
