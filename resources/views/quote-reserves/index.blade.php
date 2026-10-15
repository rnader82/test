@extends('layouts.app')
@section('portal-module', 'quote-reserves')
@section('portal-page', 'index')
@section('portal-kind', 'list')
@section('portal-family', 'quotes')
@section('portal-icon', 'file-lines')


@section('title', 'Réserves de devis')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Page -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">
                <i class="bi bi-shield-check text-primary me-2"></i>Réserves de devis
            </h1>
            <p class="text-muted mb-0">Gérez vos réserves et clauses conditionnelles réutilisables pour les devis.</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm d-inline-flex align-items-center gap-2" id="btn-open-create-modal">
                <i class="bi bi-plus-lg fs-6"></i>
                <span>Nouvelle Réserve</span>
            </button>
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
            <form method="GET" action="{{ route('quote-reserves.index') }}" class="row g-3 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Rechercher par titre ou contenu..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="is_active" class="form-select" onchange="this.form.submit()">
                        <option value="">Tous les statuts</option>
                        <option value="1" @selected(request('is_active') === '1')>Actives</option>
                        <option value="0" @selected(request('is_active') === '0')>Inactives</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-secondary flex-grow-1 rounded-pill fw-semibold">
                        <i class="bi bi-funnel me-1"></i>Filtrer
                    </button>
                    @if(request()->hasAny(['search', 'is_active']))
                        <a href="{{ route('quote-reserves.index') }}" class="btn btn-outline-secondary rounded-pill">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Reserves Table Card -->
    <div class="card card-premium shadow-sm border-0">
        <div class="table-responsive">
            <table class="module-table table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase small text-muted">
                    <tr>
                        <th class="ps-4" style="width: 35%;">Titre de la réserve</th>
                        <th>Détails / Contenu</th>
                        <th class="text-center" style="width: 120px;">Statut</th>
                        <th class="text-center" style="width: 150px;">Créé par</th>
                        <th class="text-end pe-4" style="width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reserves as $reserve)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-dark d-block">{{ $reserve->title }}</span>
                            </td>
                            <td>
                                @if($reserve->content)
                                    <span class="text-muted small d-block text-truncate" style="max-width: 500px;" title="{{ $reserve->content }}">
                                        {{ $reserve->content }}
                                    </span>
                                @else
                                    <span class="text-muted fst-italic small">Aucun texte additionnel</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($reserve->is_active)
                                    <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill">Active</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded-pill">Inactive</span>
                                @endif
                            </td>
                            <td class="text-center text-muted small">
                                {{ $reserve->creator->name ?? '-' }}
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-primary btn-edit-reserve" 
                                            data-id="{{ $reserve->id }}"
                                            data-title="{{ $reserve->title }}"
                                            data-content="{{ $reserve->content }}"
                                            data-is-active="{{ $reserve->is_active ? '1' : '0' }}"
                                            title="Modifier">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <form action="{{ route('quote-reserves.destroy', $reserve) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette réserve ?');">
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
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-card-checklist fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                <span>Aucune réserve de devis trouvée.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reserves->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $reserves->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Réserve (Ajout / Modification) -->
<div class="modal fade" id="modalReserve" tabindex="-1" aria-labelledby="modalReserveTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 8px;">
            <form id="reserveForm" method="POST" action="{{ route('quote-reserves.store') }}">
                @csrf
                <input type="hidden" name="_method" id="reserve_method" value="POST">
                
                <!-- Header -->
                <div class="modal-header border-bottom px-4 pt-4 pb-3 align-items-start">
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="modalReserveTitle" style="color: #1e3a5f !important; font-size: 1.25rem;">
                            Réserve
                        </h5>
                        <small class="text-muted d-block mt-1" id="modalReserveSubtitle" style="font-size: 0.9rem;">
                            Ajout d'une nouvelle réserve
                        </small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Body -->
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="reserve_title" class="form-label text-secondary small fw-semibold mb-2">Réserve</label>
                        <input type="text" class="form-control rounded-2 border py-2" id="reserve_title" name="title" required placeholder="" autofocus>
                    </div>
                    <div>
                        <textarea class="form-control rounded-2 border p-3" id="reserve_content" name="content" rows="6" placeholder=""></textarea>
                    </div>
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="reserve_is_active" checked>
                        <label class="form-check-label small text-muted" for="reserve_is_active">
                            Réserve active (disponible dans la sélection des devis)
                        </label>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer border-0 px-4 pb-4 pt-0 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary px-4 rounded-2" data-bs-dismiss="modal">
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-primary px-4 rounded-2 d-inline-flex align-items-center gap-2" style="background-color: #3b82f6; border-color: #3b82f6;">
                        <span>Enregistrer</span>
                        <i class="bi bi-floppy"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalElement = document.getElementById('modalReserve');
    if (!modalElement) return;
    const modal = new bootstrap.Modal(modalElement);
    const form = document.getElementById('reserveForm');
    const titleInput = document.getElementById('reserve_title');
    const contentInput = document.getElementById('reserve_content');
    const isActiveInput = document.getElementById('reserve_is_active');
    const methodInput = document.getElementById('reserve_method');
    const subtitle = document.getElementById('modalReserveSubtitle');

    const btnCreate = document.getElementById('btn-open-create-modal');
    if (btnCreate) {
        btnCreate.addEventListener('click', function () {
            form.action = "{{ route('quote-reserves.store') }}";
            methodInput.value = "POST";
            subtitle.textContent = "Ajout d'une nouvelle réserve";
            titleInput.value = '';
            contentInput.value = '';
            isActiveInput.checked = true;
            modal.show();
        });
    }

    document.querySelectorAll('.btn-edit-reserve').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const title = this.getAttribute('data-title');
            const content = this.getAttribute('data-content');
            const isActive = this.getAttribute('data-is-active') === '1';

            form.action = "{{ url('quote-reserves') }}/" + id;
            methodInput.value = "PUT";
            subtitle.textContent = "Modification de la réserve";
            titleInput.value = title || '';
            contentInput.value = content || '';
            isActiveInput.checked = isActive;
            modal.show();
        });
    });
});
</script>
@endsection
