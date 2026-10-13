@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">{{ __('locale.logs') }}</h1>
        <a href="{{ route('logs.export', request()->query()) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-download me-1"></i> {{ __('locale.btn_export') }}
        </a>
    </div>

    <!-- Filter Form -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('logs.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="description" class="form-label fw-semibold">{{ __('locale.description') }}</label>
                        <input type="text" name="description" id="description" class="form-control"
                            placeholder="{{ __('locale.search_by_description') }}"
                            value="{{ request('description') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="module" class="form-label fw-semibold">{{ __('locale.module') }}</label>
                        <select name="module" id="module" class="form-select">
                            <option value="">{{ __('locale.select_module') }}</option>
                            @foreach($modules as $module)
                            <option value="{{ $module }}" {{ request('module') == $module ? 'selected' : '' }}>
                                {{ \App\Services\LogService::translateModule($module) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="action" class="form-label fw-semibold">{{ __('locale.action') }}</label>
                        <select name="action" id="action" class="form-select">
                            <option value="">{{ __('locale.select_action') }}</option>
                            @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                {{ \App\Services\LogService::translateAction($action) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="user_id" class="form-label fw-semibold">{{ __('locale.lbl_user') }}</label>
                        <select name="user_id" id="user_id" class="form-select">
                            <option value="">{{ __('locale.select_user') }}</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}{{ in_array($user->id, $deletedUserIds) ? ' (' . __('locale.badge_deleted') . ')' : '' }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-filter me-1"></i>{{ __('locale.btn_apply_filters') }}
                        </button>
                        <a href="{{ route('logs.index') }}" class="btn btn-secondary">{{ __('locale.btn_cancel') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0" style="table-layout: fixed; width: 100%;">
                <thead class="table-light">
                    <tr>
                        <th style="width: 6%;">{{ __('locale.lbl_id') }}</th>
                        <th style="width: 14%;">{{ __('locale.module') }}</th>
                        <th style="width: 12%;">{{ __('locale.action') }}</th>
                        <th style="width: 44%; word-break: break-word;">{{ __('locale.description') }}</th>
                        <th style="width: 12%;">{{ __('locale.lbl_user') }}</th>
                        <th style="width: 12%;">{{ __('locale.timestamp') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    @php
                        $userDisplayName = $log->user
                            ? ($log->user->email ?: $log->user->name) . (in_array($log->user_id, $deletedUserIds) ? ' (' . __('locale.badge_deleted') . ')' : '')
                            : __('locale.logs.guest');
                        $translatedDesc = \App\Services\LogService::translateDescription($log->description);
                        $translatedMod = \App\Services\LogService::translateModule($log->module);
                        $translatedAct = \App\Services\LogService::translateAction($log->action);
                        $badgeClass = \App\Services\LogService::getActionBadgeClass($log->action);
                        $formattedDate = $log->created_at ? $log->created_at->format('d/m/Y H:i:s') : '-';
                    @endphp
                    <tr class="log-row"
                        role="button"
                        style="cursor: pointer;"
                        title="{{ __('locale.click_to_view_details') }}"
                        data-id="{{ $log->id }}"
                        data-module="{{ $translatedMod }}"
                        data-action="{{ $translatedAct }}"
                        data-action-badge="{{ $badgeClass }}"
                        data-user="{{ $userDisplayName }}"
                        data-date="{{ $formattedDate }}"
                        data-description="{{ $translatedDesc }}">
                        <td><span class="text-muted fw-semibold">#{{ $log->id }}</span></td>
                        <td>
                            <span class="fw-semibold">{{ $translatedMod }}</span>
                        </td>
                        <td>
                            <span class="badge {{ $badgeClass }}">
                                {{ $translatedAct }}
                            </span>
                        </td>
                        <td style="word-break: break-word;">{{ $translatedDesc }}</td>
                        <td>
                            @if($log->user)
                                <span>{{ $log->user->email ?: $log->user->name }}</span>
                                @if(in_array($log->user_id, $deletedUserIds))
                                    <span class="badge bg-danger ms-1" title="{{ __('locale.badge_deleted') }}">
                                        <i class="bi bi-trash"></i>
                                    </span>
                                @endif
                            @else
                                <span class="text-muted fst-italic">{{ __('locale.logs.guest') }}</span>
                            @endif
                        </td>
                        <td class="text-muted small">
                            {{ $formattedDate }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            {{ __('locale.no_data') ?? 'Aucun enregistrement' }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
        <div class="card-footer bg-white border-top d-flex justify-content-end py-2">
            {{ $logs->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Détail du Log -->
<div class="modal fade" id="logDetailModal" tabindex="-1" aria-labelledby="logDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-light">
                <h5 class="modal-title d-flex align-items-center gap-2" id="logDetailModalLabel">
                    <i class="bi bi-journal-text text-primary"></i>
                    <span>{{ __('locale.log_details') }}</span>
                    <span id="modal-log-id" class="badge bg-secondary"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3 mb-4">
                    <div class="col-sm-6 col-md-3">
                        <div class="text-muted small mb-1">{{ __('locale.module') }}</div>
                        <div id="modal-log-module" class="fw-semibold"></div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="text-muted small mb-1">{{ __('locale.action') }}</div>
                        <div><span id="modal-log-action" class="badge"></span></div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="text-muted small mb-1">{{ __('locale.lbl_user') }}</div>
                        <div id="modal-log-user" class="fw-semibold"></div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="text-muted small mb-1">{{ __('locale.timestamp') }}</div>
                        <div id="modal-log-timestamp" class="text-muted"></div>
                    </div>
                </div>

                <div class="mb-2 d-flex justify-content-between align-items-center">
                    <label class="form-label fw-semibold mb-0">{{ __('locale.description') }}</label>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-copy-description" title="{{ __('locale.copy_description') }}">
                        <i class="bi bi-clipboard me-1" id="btn-copy-icon"></i> <span id="btn-copy-text">{{ __('locale.lbl_copy') }}</span>
                    </button>
                </div>
                <div class="p-3 bg-light rounded border">
                    <pre id="modal-log-description" class="mb-0" style="white-space: pre-wrap; word-break: break-word; font-family: inherit; font-size: 0.925rem; max-height: 400px; overflow-y: auto;"></pre>
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('locale.btn_close') }}</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const logModalEl = document.getElementById('logDetailModal');
    if (!logModalEl) return;

    const logModal = new bootstrap.Modal(logModalEl);
    const modalLogId = document.getElementById('modal-log-id');
    const modalLogModule = document.getElementById('modal-log-module');
    const modalLogAction = document.getElementById('modal-log-action');
    const modalLogUser = document.getElementById('modal-log-user');
    const modalLogTimestamp = document.getElementById('modal-log-timestamp');
    const modalLogDescription = document.getElementById('modal-log-description');
    const btnCopy = document.getElementById('btn-copy-description');
    const btnCopyText = document.getElementById('btn-copy-text');
    const btnCopyIcon = document.getElementById('btn-copy-icon');

    let currentDescription = '';
    const originalCopyText = btnCopyText ? btnCopyText.textContent : 'Copier';

    function copyToClipboard(text) {
        if (!text) {
            return Promise.reject(new Error('No text to copy'));
        }

        if (navigator.clipboard && window.isSecureContext) {
            return navigator.clipboard.writeText(text);
        }

        return new Promise(function (resolve, reject) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.setAttribute('readonly', '');
            textarea.style.position = 'fixed';
            textarea.style.left = '-9999px';
            textarea.style.top = '0';
            textarea.style.opacity = '0';

            const container = logModalEl.querySelector('.modal-body') || document.body;
            container.appendChild(textarea);
            textarea.focus();
            textarea.select();
            textarea.setSelectionRange(0, 99999);

            try {
                const successful = document.execCommand('copy');
                container.removeChild(textarea);
                if (successful) {
                    resolve();
                } else {
                    reject(new Error('execCommand copy returned false'));
                }
            } catch (err) {
                container.removeChild(textarea);
                reject(err);
            }
        });
    }

    document.querySelectorAll('.log-row').forEach(function (row) {
        row.addEventListener('click', function (e) {
            modalLogId.textContent = '#' + (row.dataset.id || '');
            modalLogModule.textContent = row.dataset.module || '-';

            modalLogAction.textContent = row.dataset.action || '-';
            modalLogAction.className = 'badge ' + (row.dataset.actionBadge || 'bg-secondary');

            modalLogUser.textContent = row.dataset.user || '-';
            modalLogTimestamp.textContent = row.dataset.date || '-';

            currentDescription = row.dataset.description || '';
            modalLogDescription.textContent = currentDescription;

            if (btnCopyText) {
                btnCopyText.textContent = originalCopyText;
            }
            if (btnCopyIcon) {
                btnCopyIcon.className = 'bi bi-clipboard me-1';
            }

            logModal.show();
        });
    });

    if (btnCopy) {
        btnCopy.addEventListener('click', function () {
            const textToCopy = currentDescription || (modalLogDescription ? modalLogDescription.textContent : '');
            if (!textToCopy) return;

            copyToClipboard(textToCopy)
                .then(function () {
                    if (btnCopyText) {
                        btnCopyText.textContent = @json(__('locale.copied'));
                    }
                    if (btnCopyIcon) {
                        btnCopyIcon.className = 'bi bi-check-lg text-success me-1';
                    }
                    setTimeout(function () {
                        if (btnCopyText) {
                            btnCopyText.textContent = originalCopyText;
                        }
                        if (btnCopyIcon) {
                            btnCopyIcon.className = 'bi bi-clipboard me-1';
                        }
                    }, 2000);
                })
                .catch(function (err) {
                    console.error('Failed to copy text: ', err);
                });
        });
    }
});
</script>
@endpush
@endsection