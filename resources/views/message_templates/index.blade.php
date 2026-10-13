@extends('layouts.app')

@section('title', __('locale.message_templates'))

@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.message_templates') }}</h1>
        <span class="header-count">({{ method_exists($templates, 'total') ? $templates->total() : count($templates) }})</span>
    </div>
@endsection

@section('page-actions')
    <a href="{{ route('message_templates.create') }}" class="btn btn-primary" title="{{ __('locale.add_message_template') }}">
        <i class="bi bi-plus-lg me-1"></i> {{ __('locale.add_message_template') ?? 'Nouveau modèle' }}
    </a>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1">

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

        <div id="template-order-feedback" class="alert alert-success d-none mb-3"></div>

        <div class="card card-premium shadow-sm border-0">
            <div class="card-header bg-light border-bottom py-3 d-flex justify-content-between align-items-center">
                <span class="text-muted small">
                    <i class="bi bi-info-circle me-1"></i> {{ __('locale.template_drag_help') ?? 'Faites glisser les lignes pour réorganiser l\'ordre d\'affichage.' }}
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;" class="text-center"><i class="bi bi-arrows-move"></i></th>
                                <th class="ps-2">{{ __('locale.lbl_subject') }}</th>
                                <th class="text-end pe-4" style="width: 120px;">{{ __('locale.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody id="templates-table" data-reorder-url="{{ route('message_templates.reorder') }}">
                            @forelse ($templates as $template)
                                <tr data-id="{{ $template->id }}" style="cursor: pointer;" onclick="window.location='{{ route('message_templates.show', $template->id) }}'">
                                    <td class="text-center align-middle" onclick="event.stopPropagation();">
                                        <i class="bi bi-grip-vertical fs-5 text-muted" role="button" style="cursor: grab;" aria-label="{{ __('locale.template_drag_handle') }}"></i>
                                    </td>
                                    <td class="ps-2 align-middle">
                                        <a href="{{ route('message_templates.show', $template->id) }}" class="fw-semibold text-dark text-decoration-none">
                                            {{ $template->subject }}
                                        </a>
                                    </td>
                                    <td class="text-end pe-4 align-middle" onclick="event.stopPropagation();">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" id="msgTemplateActionMenu{{ $template->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border" aria-labelledby="msgTemplateActionMenu{{ $template->id }}">
                                                <li>
                                                    <a href="{{ route('message_templates.show', $template->id) }}" class="dropdown-item">
                                                        <i class="bi bi-eye me-2 text-info"></i>{{ __('locale.btn_show') ?? 'Voir' }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('message_templates.edit', $template->id) }}" class="dropdown-item">
                                                        <i class="bi bi-pencil-square me-2 text-primary"></i>{{ __('locale.edit') }}
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('message_templates.destroy', $template->id) }}" method="POST" onsubmit="return confirm('{{ __('locale.delete_confirmation') }}');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="bi bi-trash me-2"></i>{{ __('locale.delete') }}
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2 text-muted"></i>
                                        {{ __('locale.lbl_no_records_found') }}
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
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"
    integrity="sha384-BSxuMLxX+FCbTdYec3TbXlnMGEEM2QXTFdtDaveen71o+jswm2J36+xFqp8k4VHM" crossorigin="anonymous"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tableBody = document.getElementById('templates-table');
        const feedback = document.getElementById('template-order-feedback');

        if (tableBody) {
            new Sortable(tableBody, {
                animation: 150,
                handle: '.bi-grip-vertical',
                onEnd: function () {
                    const order = Array.from(tableBody.children).map(row => row.dataset.id);
                    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                    const csrfToken = tokenMeta ? tokenMeta.getAttribute('content') : '{{ csrf_token() }}';

                    fetch(tableBody.dataset.reorderUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ order }),
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network error');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (feedback) {
                            feedback.textContent = data.message || '{{ __('locale.template_order_updated') }}';
                            feedback.classList.remove('d-none');
                            setTimeout(() => feedback.classList.add('d-none'), 3000);
                        }
                    })
                    .catch(() => {
                        if (feedback) {
                            feedback.textContent = '{{ __('locale.template_order_error') }}';
                            feedback.classList.remove('d-none', 'alert-success');
                            feedback.classList.add('alert-danger');
                            setTimeout(() => {
                                feedback.classList.add('d-none');
                                feedback.classList.remove('alert-danger');
                                feedback.classList.add('alert-success');
                            }, 4000);
                        }
                    });
                },
            });
        }
    });
</script>
@endsection