@extends('layouts.app')

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.lbl_supplier_orders') ?? 'Commandes Fournisseurs' }}</h1>
        <span class="header-count">({{ $supplierOrders->total() ?? 0 }})</span>
    </div>
@endsection

@section('navbar-search')
    <form method="GET" action="{{ route('supplier-orders.index') }}" class="w-100">
        <div class="search-bar-mockup">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher une commande...">
            @foreach(request()->except(['search', 'page']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </div>
    </form>
@endsection

@section('page-actions')
    <a href="{{ route('supplier-orders.create') }}" class="btn btn-primary" title="{{ __('locale.btn_create_supplier_order') }}">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.btn_create_supplier_order') }}
    </a>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">
        
        <div class="card card-premium mb-4">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('supplier-orders.index') }}" class="mb-0">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="search" class="form-label fw-bold small text-uppercase text-muted">Rechercher</label>
                            <div class="input-group">
                                <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control"
                                    placeholder="Rechercher par numéro, réf interne ou fournisseur...">
                                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label fw-bold small text-uppercase text-muted">Statut</label>
                            <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                                <option value="">Tous les statuts</option>
                                <option value="modifiable" @selected(request('status') === 'modifiable')>Modifiable</option>
                                <option value="non_modifiable" @selected(request('status') === 'non_modifiable')>Figée</option>
                            </select>
                        </div>
                    </div>
                </form>
                
                @if (Auth::user()->isNovalyoAdmin())
                <div class="d-flex justify-content-end gap-3 mb-0 mt-3 border-top pt-3 align-items-center">
                    <form action="{{ route('supplier-orders.sync-odoo') }}" method="POST" class="d-inline"
                          onsubmit="return confirm('{{ __('locale.confirm_sync_supplier_orders') }}')">
                        @csrf
                        <button type="submit" class="btn btn-link text-decoration-none text-primary fw-bold p-0 border-0 bg-transparent">
                            <i class="bi bi-arrow-repeat me-1"></i> {{ __('locale.btn_sync_supplier_orders') }}
                        </button>
                    </form>
                    <a href="#importSection" data-bs-toggle="collapse" role="button" class="btn btn-link text-decoration-none text-success fw-bold p-0"
                        aria-expanded="false" aria-controls="importSection">
                        <i class="bi bi-cloud-arrow-up me-1"></i> Importer des Commandes Fournisseurs
                    </a>
                </div>

                <div class="collapse" id="importSection">
                    <form id="supplierOrderImportForm" class="import-form mt-3" method="POST" action="{{ route('supplier-orders.import') }}" enctype="multipart/form-data">
                        @csrf
                        <h5 class="fw-bold mb-3 text-success"><i class="bi bi-cloud-arrow-up-fill me-2"></i>Import des Commandes Fournisseurs</h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="company_id" class="form-label fw-bold small text-uppercase text-muted">Sélectionner la Société</label>
                                <select name="company_id" class="form-select select2" required data-placeholder="Sélectionner la Société">
                                    <option value=""></option>
                                    @foreach(\App\Models\Company::all() as $comp)
                                        <option value="{{ $comp->id }}" @selected(session('active_company_prefix') === $comp->code)>{{ $comp->name }} ({{ $comp->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="file" class="form-label fw-bold small text-uppercase text-muted">Fichier En-têtes (CSV / XLSX)</label>
                                <input type="file" name="file" id="file" class="form-control" accept=".csv,.xlsx,.xls">
                            </div>
                            <div class="col-md-4">
                                <label for="details_file" class="form-label fw-bold small text-uppercase text-muted">Fichier Lignes (CSV / XLSX)</label>
                                <input type="file" name="details_file" id="details_file" class="form-control" accept=".csv,.xlsx,.xls">
                            </div>
                            <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                                <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">
                                    <i class="bi bi-upload me-1"></i>Lancer l'import
                                </button>
                            </div>
                        </div>
                    </form>
                    <div id="supplierOrderImportMessage" class="alert d-none mt-3" role="alert" aria-live="polite" aria-atomic="true"></div>
                </div>
                @endif
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card card-premium">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('locale.lbl_reference') ?? 'Référence (Numéro)' }}</th>
                            <th>{{ __('locale.lbl_odoo_id') }}</th>
                            <th>Réf. Interne / Origine</th>
                            <th>Commande Client</th>
                            <th>{{ __('locale.lbl_supplier') ?? 'Fournisseur' }}</th>
                            <th>Date de Pièce</th>
                            <th>Date Livraison</th>
                            <th>Total HT</th>
                            <th>Total TTC</th>
                            <th>État</th>
                            <th class="text-end">{{ __('locale.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($supplierOrders as $order)
                            @php
                                $stateBadgeClass = match($order->state) {
                                    'purchase' => 'bg-success bg-opacity-10 text-success',
                                    'done' => 'bg-info bg-opacity-10 text-info',
                                    'cancel' => 'bg-danger bg-opacity-10 text-danger',
                                    'to approve' => 'bg-warning bg-opacity-10 text-warning',
                                    'sent' => 'bg-info bg-opacity-10 text-info',
                                    'draft' => 'bg-secondary bg-opacity-10 text-secondary',
                                    default => $order->modifiable ? 'bg-label-success' : 'bg-label-danger',
                                };
                                $stateLabel = match($order->state) {
                                    'purchase' => 'Bon de commande',
                                    'done' => 'Terminé / Reçu',
                                    'cancel' => 'Annulé',
                                    'to approve' => 'À approuver',
                                    'sent' => 'Envoyé',
                                    'draft' => 'Demande de prix',
                                    default => $order->state ?? ($order->modifiable ? 'Modifiable' : 'Figée'),
                                };
                            @endphp
                            <tr>
                                <td>
                                    <a href="{{ route('supplier-orders.show', $order) }}" class="fw-bold text-dark text-decoration-none">
                                        {{ $order->numero }}
                                    </a>
                                </td>
                                <td>
                                    @if ($order->odoo_id)
                                        @if (config('services.odoo.url'))
                                            <a href="{{ rtrim(config('services.odoo.url'), '/') }}/web#id={{ $order->odoo_id }}&model=purchase.order&view_type=form"
                                               target="_blank"
                                               class="badge bg-info bg-opacity-10 text-info border-0 px-2 py-1 rounded-pill font-monospace fw-bold text-decoration-none">
                                                {{ $order->odoo_id }} <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                            </a>
                                        @else
                                            <span class="badge bg-info bg-opacity-10 text-info border-0 px-2 py-1 rounded-pill font-monospace fw-bold">
                                                {{ $order->odoo_id }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td>
                                    <code class="small">{{ $order->ref_interne ?? ($order->origin ?? '-') }}</code>
                                </td>
                                <td>
                                    @if ($order->order)
                                        <a href="{{ route('orders.show', $order->order) }}" class="fw-semibold text-primary text-decoration-none small">
                                            #{{ $order->order->id }} ({{ $order->order->ref_interne ?? $order->order->numero }})
                                        </a>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($order->supplier)
                                        <a href="{{ route('suppliers.show', $order->supplier) }}" class="fw-bold text-primary text-decoration-none">
                                            {{ $order->supplier->name }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $order->date_piece ? $order->date_piece->format('d/m/Y') : '-' }}</td>
                                <td>{{ $order->date_livraison ? $order->date_livraison->format('d/m/Y') : '-' }}</td>
                                <td class="fw-semibold text-dark">{{ number_format($order->total_ht, 2, ',', ' ') }} {{ $order->devise ?? '€' }}</td>
                                <td class="fw-bold text-primary">{{ number_format($order->total_ttc, 2, ',', ' ') }} {{ $order->devise ?? '€' }}</td>
                                <td>
                                    <span class="badge {{ $stateBadgeClass }} border-0 px-2 py-1 rounded-pill fw-bold">
                                        {{ $stateLabel }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow shadow-none" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical fs-5"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="{{ route('supplier-orders.show', $order) }}">
                                                <i class="bi bi-eye me-2"></i> Consulter
                                            </a>
                                            <a class="dropdown-item" href="{{ route('supplier-orders.edit', $order) }}">
                                                <i class="bi bi-pencil me-2 text-warning"></i> Modifier
                                            </a>
                                            <form action="{{ route('supplier-orders.destroy', $order) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Supprimer cette commande fournisseur?')">
                                                    <i class="bi bi-trash me-2"></i> Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-cart fs-1 mb-2 opacity-50"></i>
                                        <p>Aucune commande fournisseur trouvée</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($supplierOrders->hasPages())
                <div class="card-footer px-4 py-3 border-top">
                    {{ $supplierOrders->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%',
            allowClear: true
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const importForm = document.getElementById('supplierOrderImportForm');
        const messageContainer = document.getElementById('supplierOrderImportMessage');

        if (!importForm) {
            return;
        }

        const submitButton = importForm.querySelector('button[type="submit"]');

        const resetMessage = () => {
            if (!messageContainer) {
                return;
            }
            messageContainer.classList.add('d-none');
            messageContainer.classList.remove('alert', 'alert-success', 'alert-danger');
            messageContainer.textContent = '';
        };

        const showMessage = (type, text) => {
            if (!messageContainer) {
                return;
            }
            messageContainer.innerHTML = text;
            messageContainer.classList.remove('d-none');
            messageContainer.classList.add('alert');
            messageContainer.classList.remove('alert-success', 'alert-danger');
            if (type === 'error') {
                messageContainer.classList.add('alert-danger');
            } else {
                messageContainer.classList.add('alert-success');
            }
        };

        importForm.addEventListener('submit', async function(event) {
            event.preventDefault();
            resetMessage();

            if (submitButton) {
                submitButton.disabled = true;
            }

            try {
                const formData = new FormData(importForm);
                const response = await fetch(importForm.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                let rawText = await response.text();
                rawText = typeof rawText === 'string' ? rawText.trim() : '';
                let payload = null;

                if (rawText !== '') {
                    try {
                        payload = JSON.parse(rawText);
                    } catch (parseError) {
                        payload = rawText;
                    }
                }

                if (!response.ok) {
                    if (payload !== null) {
                        throw payload;
                    }
                    throw {
                        message: "Une erreur est survenue lors de l'importation."
                    };
                }

                const messages = [];
                if (payload && typeof payload === 'object') {
                    if (payload.summary) {
                        const summary = payload.summary;
                        const createdOrders = Number(summary.orders?.created ?? 0);
                        const updatedOrders = Number(summary.orders?.updated ?? 0);
                        const skippedOrders = Number(summary.orders?.skipped ?? 0);
                        const createdLines = Number(summary.lines?.created ?? 0);
                        const skippedLines = Number(summary.lines?.skipped ?? 0);

                        messages.push(`<strong>Importation terminée avec succès</strong><br>`);
                        messages.push(`Commandes: Créées: ${createdOrders}, Mises à jour: ${updatedOrders}, Ignorées: ${skippedOrders}.<br>`);
                        messages.push(`Lignes de détails: Créées: ${createdLines}, Ignorées: ${skippedLines}.`);

                        if (Array.isArray(summary.errors) && summary.errors.length > 0) {
                            const errorsText = summary.errors.slice(0, 5).map(err => {
                                return `[Fichier: ${err.file}, Ligne: ${err.row}] ${err.message}`;
                            }).join('; ');
                            messages.push(`<br><span class="text-danger">Erreurs (${summary.errors.length}): ${errorsText}</span>`);

                            if (summary.error_file_url) {
                                messages.push(`<br><a href="${summary.error_file_url}" class="btn btn-sm btn-outline-danger fw-bold mt-2 d-inline-block" target="_blank"><i class="bi bi-file-earmark-arrow-down me-1"></i>Télécharger le rapport d'erreurs (CSV)</a>`);
                            }
                        }
                    } else if (payload.message) {
                        messages.push(payload.message);
                    }
                } else if (typeof payload === 'string' && payload !== '') {
                    messages.push(payload);
                }

                if (messages.length === 0) {
                    messages.push("Importation terminée.");
                }

                showMessage('success', messages.join(''));
                
                setTimeout(() => {
                    window.location.reload();
                }, 3000);

            } catch (error) {
                console.error(error);
                let errorMsg = "Erreur de traitement de l'importation.";
                if (error && typeof error === 'object') {
                    if (error.message) {
                        errorMsg = error.message;
                    } else if (error.errors) {
                        errorMsg = Object.values(error.errors).flat().join('<br>');
                    }
                }
                showMessage('error', errorMsg);
            } finally {
                if (submitButton) {
                    submitButton.disabled = false;
                }
            }
        });
    });
</script>
@endsection
