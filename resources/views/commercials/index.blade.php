@extends('layouts.app')
@section('portal-module', 'commercials')
@section('portal-page', 'index')
@section('portal-kind', 'list')
@section('portal-family', 'crm')
@section('portal-icon', 'arrow-trend-up')


@section('title', __('locale.lbl_commercials_title'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.lbl_commercials_title') }}</h1>
        <span class="header-count">({{ method_exists($commercials, 'total') ? $commercials->total() : count($commercials) }})</span>
    </div>
@endsection

@section('navbar-search')
    <form method="GET" action="{{ route('commercials.index') }}" class="w-100">
        <div class="search-bar-mockup">
            <i class="bi bi-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('locale.search_placeholder') }}">
            @foreach(request()->except(['search', 'page']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </div>
    </form>
@endsection

@section('page-actions')
    @if (Auth::user()->isNovalyoAdmin())
        <a href="#importSection" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="importSection" class="btn btn-outline-primary">
            <i class="bi bi-cloud-arrow-up me-1"></i> {{ __('locale.import.btn_import') }}
        </a>
    @endif
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">

        <div class="collapse mb-4" id="importSection">
            <div class="card card-premium shadow-sm border-0">
                <div class="card-header bg-light border-bottom py-3">
                    <h5 class="card-title mb-0 fs-6 fw-bold text-dark">
                        <i class="bi bi-cloud-arrow-up me-2 text-primary"></i>{{ __('locale.import.btn_import') }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form id="commercialImportForm" method="POST" action="{{ route('commercials.import') }}" enctype="multipart/form-data" class="d-flex align-items-center gap-2">
                        @csrf
                        <div class="flex-grow-1">
                            <input type="file" name="file" id="commercialImportFile" accept=".csv,text/csv" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('locale.import.btn_import') }}</button>
                    </form>
                </div>
            </div>
        </div>

        <div id="commercialImportMessage" class="d-none mb-3" role="alert" aria-live="polite" aria-atomic="true"></div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card card-premium shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="module-table table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">
                                    <a class="text-dark text-decoration-none fw-semibold" href="{{ route('commercials.index', array_merge(request()->all(), ['sort' => 'external_id', 'direction' => ($sort ?? '') === 'external_id' && ($direction ?? '') === 'asc' ? 'desc' : 'asc'])) }}">
                                        {{ __('locale.external_id') }} <i class="bi bi-arrow-down-up text-muted small ms-1"></i>
                                    </a>
                                </th>
                                <th>
                                    <a class="text-dark text-decoration-none fw-semibold" href="{{ route('commercials.index', array_merge(request()->all(), ['sort' => 'libelle', 'direction' => ($sort ?? '') === 'libelle' && ($direction ?? '') === 'asc' ? 'desc' : 'asc'])) }}">
                                        {{ __('locale.libelle') }} <i class="bi bi-arrow-down-up text-muted small ms-1"></i>
                                    </a>
                                </th>
                                <th>
                                    <a class="text-dark text-decoration-none fw-semibold" href="{{ route('commercials.index', array_merge(request()->all(), ['sort' => 'prenom', 'direction' => ($sort ?? '') === 'prenom' && ($direction ?? '') === 'asc' ? 'desc' : 'asc'])) }}">
                                        {{ __('locale.prenom') }} <i class="bi bi-arrow-down-up text-muted small ms-1"></i>
                                    </a>
                                </th>
                                <th>
                                    <a class="text-dark text-decoration-none fw-semibold" href="{{ route('commercials.index', array_merge(request()->all(), ['sort' => 'etablissement', 'direction' => ($sort ?? '') === 'etablissement' && ($direction ?? '') === 'asc' ? 'desc' : 'asc'])) }}">
                                        {{ __('locale.etablissement') }} <i class="bi bi-arrow-down-up text-muted small ms-1"></i>
                                    </a>
                                </th>
                                <th>{{ __('locale.lbl_email') }}</th>
                                <th class="text-end pe-4" style="width: 120px;">{{ __('locale.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($commercials as $commercial)
                                <tr style="cursor: pointer;" onclick="window.location='{{ route('commercials.show', $commercial) }}'">
                                    <td class="ps-4">
                                        <span class="badge bg-light text-secondary border font-monospace">{{ $commercial->external_id }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('commercials.show', $commercial) }}" class="fw-semibold text-dark text-decoration-none">
                                            {{ $commercial->libelle }}
                                        </a>
                                    </td>
                                    <td>{{ $commercial->prenom }}</td>
                                    <td>
                                        @if($commercial->etablissement)
                                            <span class="badge bg-light text-dark border">{{ $commercial->etablissement }}</span>
                                        @else
                                            <span class="text-muted small">&mdash;</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($commercial->email)
                                            <a href="mailto:{{ $commercial->email }}" class="text-muted small text-decoration-none">
                                                <i class="bi bi-envelope me-1"></i>{{ $commercial->email }}
                                            </a>
                                        @else
                                            <span class="text-muted small">&mdash;</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4" onclick="event.stopPropagation();">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" id="commercialActionMenu{{ $commercial->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border" aria-labelledby="commercialActionMenu{{ $commercial->id }}">
                                                <li>
                                                    <a href="{{ route('commercials.show', $commercial) }}" class="dropdown-item">
                                                        <i class="bi bi-eye me-2 text-info"></i>{{ __('locale.btn_show') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('commercials.edit', $commercial) }}" class="dropdown-item">
                                                        <i class="bi bi-pencil-square me-2 text-primary"></i>{{ __('locale.edit') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2 text-muted"></i>
                                        {{ __('locale.lbl_no_records_found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if(method_exists($commercials, 'hasPages') && $commercials->hasPages())
                <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
                    <span class="text-muted small">
                        {{ __('locale.results_sur', ['first' => $commercials->firstItem() ?? 0, 'last' => $commercials->lastItem() ?? 0, 'total' => $commercials->total()]) }}
                    </span>
                    <div>
                        {{ $commercials->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const importForm = document.getElementById('commercialImportForm');
        const messageContainer = document.getElementById('commercialImportMessage');
        const fileInput = document.getElementById('commercialImportFile');

        const translations = {
            select_file: "{{ __('locale.import.select_file') }}",
            loading: "{{ __('locale.import.loading') }}",
            failed: "{{ __('locale.import.failed') }}",
            completed: "{{ __('locale.import.completed') }}",
            error: "{{ __('locale.import.error') }}",
            summary: "{{ __('locale.import.summary') }}"
        };

        if (!importForm || !messageContainer || !fileInput) {
            return;
        }

        const submitButton = importForm.querySelector('button[type="submit"]');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        const resetMessage = () => {
            messageContainer.classList.add('d-none');
            messageContainer.classList.remove('alert', 'alert-success', 'alert-danger');
            messageContainer.textContent = '';
        };

        const showMessage = (type, text) => {
            messageContainer.classList.remove('d-none');
            messageContainer.classList.add('alert', `alert-${type}`);
            messageContainer.textContent = text;
        };

        const summarizeImport = (summary) => {
            if (!summary || typeof summary !== 'object') {
                return '';
            }

            const parts = [];
            if (typeof summary.created !== 'undefined') {
                parts.push(`{{ __('locale.import.created') }}: ${summary.created}`);
            }
            if (typeof summary.updated !== 'undefined') {
                parts.push(`{{ __('locale.import.updated') }}: ${summary.updated}`);
            }
            if (typeof summary.skipped !== 'undefined') {
                parts.push(`{{ __('locale.import.skipped') }}: ${summary.skipped}`);
            }
            if (typeof summary.failed !== 'undefined') {
                parts.push(`{{ __('locale.import.failed_count') }}: ${summary.failed}`);
            }

            return parts.length ? ` (${parts.join(', ')})` : '';
        };

        importForm.addEventListener('submit', function(event) {
            event.preventDefault();
            resetMessage();

            const file = fileInput.files[0];
            if (!file) {
                showMessage('danger', translations.select_file);
                return;
            }

            const formData = new FormData(importForm);
            if (submitButton) {
                submitButton.disabled = true;
            }

            showMessage('info', translations.loading);

            fetch(importForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken || '',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw data;
                    });
                }
                return response.json();
            })
            .then(data => {
                const summaryText = summarizeImport(data.summary);
                const message = (data.message || translations.completed) + summaryText;
                showMessage('success', message);
                importForm.reset();
                setTimeout(() => window.location.reload(), 1500);
            })
            .catch(error => {
                const message = error.message || translations.failed;
                showMessage('danger', message);
            })
            .finally(() => {
                if (submitButton) {
                    submitButton.disabled = false;
                }
            });
        });
    });
</script>
@endsection
