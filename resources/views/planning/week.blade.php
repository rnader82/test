@extends('layouts.app')
@section('portal-module', 'planning')
@section('portal-page', 'week')
@section('portal-kind', 'planning')
@section('portal-family', 'technical')
@section('portal-icon', 'screwdriver-wrench')


@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>Planning Hebdomadaire</h1>
    </div>
@endsection

@section('navbar-search')
    <div class="search-bar-mockup" style="width: 250px;">
        <i class="bi bi-search"></i>
        <input type="text" id="userSearch" placeholder="Rechercher un technicien ...">
    </div>
@endsection

@section('page-actions')
    <div class="d-flex align-items-center gap-3">
        <div class="d-flex align-items-center gap-2 bg-light rounded-pill px-3 py-1 shadow-sm border">
            <a href="{{ route('planning.week', ['week' => \Carbon\Carbon::parse($week_days[0])->subWeek()->format('Y-m-d')]) }}" class="btn btn-link btn-sm text-primary p-0"><i class="fas fa-chevron-left"></i></a>
            <div class="position-relative d-flex align-items-center">
                <input type="date" id="weekPicker" class="form-control form-control-sm border-0 bg-transparent fw-bold text-dark p-0" 
                       value="{{ \Carbon\Carbon::parse($week_days[0])->format('Y-m-d') }}" 
                       style="width: 130px; cursor: pointer; outline: none; box-shadow: none;">
            </div>
            <a href="{{ route('planning.week', ['week' => \Carbon\Carbon::parse($week_days[0])->addWeek()->format('Y-m-d')]) }}" class="btn btn-link btn-sm text-primary p-0"><i class="fas fa-chevron-right"></i></a>
        </div>

        <div class="btn-group btn-group-sm shadow-sm" style="border-radius: 20px; overflow: hidden;">
            <a href="{{ route('planning.week', ['week' => now()->startOfWeek()->format('Y-m-d')]) }}" class="btn btn-white px-3">{{ __('locale.lbl_this_week') ?? 'Cette semaine' }}</a>
            <a href="{{ route('planning.index') }}" class="btn btn-outline-secondary px-3">Calendrier</a>
        </div>
    </div>
@endsection

@section('content')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.25/dist/sweetalert2.all.min.js"
    integrity="sha384-nLoOnA/BDh8A/jxqtckg4DumuCGOBYUnNJLZdQz/zfYNp3wcjGSoWTAzgko06G/2" crossorigin="anonymous"></script>

<div class="planning-container d-flex flex-column" style="height: calc(100vh - 120px); background: #f4f7f9; font-family: 'Inter', sans-serif;">

    <!-- Main Content Area -->
    <div class="d-flex flex-grow-1 overflow-hidden">
        <!-- Resource Grid Board -->
        <div class="main-board flex-grow-1 overflow-auto position-relative bg-white">
            <table class="table table-bordered mb-0 resource-grid" style="table-layout: fixed; min-width: 1100px; border-collapse: separate; border-spacing: 0;">
                <thead class="sticky-top bg-white" style="z-index: 20;">
                    <tr>
                        <th class="p-3 border-bottom-0" style="width: 200px; background: #f8fafc; border-right: 1px solid #e2e8f0;"></th>
                        @foreach($week_days as $day)
                            @php $isToday = \Carbon\Carbon::parse($day)->isToday(); @endphp
                            <th class="p-2 text-center border-bottom-0 {{ $isToday ? 'bg-primary-subtle' : '' }}" style="border-left: 1px solid #e2e8f0;">
                                <div class="small fw-bold text-uppercase {{ $isToday ? 'text-primary' : 'text-muted' }}">{{ \Carbon\Carbon::parse($day)->translatedFormat('D j') }}</div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr data-user-id="{{ $user->id }}" class="user-row">
                            <td class="p-3 align-middle border-end" style="background: #fcfdfe; border-right: 1px solid #e2e8f0 !important; border-left: 4px solid {{ $user->color ?? '#008cd2' }} !important;">
                                <div class="fw-bold text-dark">{{ $user->name }}</div>
                                <div class="text-muted small" style="font-size: 10px;">ID: {{ $user->identifier ?? '---' }}</div>
                            </td>
                            @foreach($week_days as $day)
                                @php 
                                    $dt = \Carbon\Carbon::parse($day);
                                    $isWeekend = $dt->isWeekend();
                                    $dayEvents = collect($events[$user->id] ?? [])->filter(fn($e) => \Carbon\Carbon::parse($e['start'])->isSameDay($day));
                                @endphp
                                <td class="p-2 align-top position-relative {{ $isWeekend ? 'weekend-cell' : '' }} grid-cell" 
                                    style="height: 100px; border-left: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;">
                                    @foreach($dayEvents as $event)
                                        <div class="event-pill p-2 mb-1 rounded small text-white shadow-sm border-0 position-relative animate__animated animate__fadeInUp event-type-{{ $event['type'] }}" 
                                             style="background: {{ $event['color'] ?? '#008cd2' }}; border-left: 3px solid rgba(0,0,0,0.2) !important; cursor: pointer;"
                                             data-type="{{ $event['type'] }}"
                                             onclick="window.location.href='{{ $event['type'] == 'interv' ? route('tech_intervs.show', str_replace('interv_', '', $event['id'])) : route('vp-visit-workflow.edit', ['vp_visit' => str_replace('vp_', '', $event['id']), 'step' => 0]) }}'">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="fw-bold" style="font-size: 9px;">{{ \Carbon\Carbon::parse($event['start'])->format('H:i') }}</span>
                                                <i class="fas fa-{{ $event['icon'] ?? 'calendar' }}" style="font-size: 9px; opacity: 0.7;"></i>
                                            </div>
                                            <div class="fw-medium text-truncate" style="font-size: 10px;" title="{{ $event['title'] }}">
                                                {{ $event['title'] }}
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    <div class="cell-overlay d-flex align-items-center justify-content-center opacity-0 transition-all">
                                        <i class="fas fa-plus-circle text-primary fa-lg"></i>
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Sidebar: Unplanned Interventions -->
        <div class="sidebar d-flex flex-column border-start bg-white" style="width: 280px; min-width: 280px; transition: all 0.3s ease;">
            <div class="sidebar-header p-3 border-bottom d-flex justify-content-between align-items-center" style="background: #e2e8f0;">
                <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-list-ul me-2"></i>Interventions</h6>
                <span class="badge bg-primary rounded-pill">{{ $groupedUnplanned->flatten()->count() }}</span>
            </div>
            <div class="sidebar-filters p-2 border-bottom">
                <div class="btn-group btn-group-sm shadow-sm border rounded overflow-hidden bg-white w-100" role="group">
                    <input type="checkbox" class="btn-check type-filter" id="filterInterv" data-type="interv" checked autocomplete="off">
                    <label class="btn btn-white text-teal px-3 border-end flex-grow-1" for="filterInterv"><i class="fas fa-calendar-check me-1"></i> Interv.</label>

                    <input type="checkbox" class="btn-check type-filter" id="filterVp" data-type="vp" checked autocomplete="off">
                    <label class="btn btn-white text-indigo px-3 flex-grow-1" for="filterVp"><i class="fas fa-user-nurse me-1"></i> VP</label>
                </div>
            </div>
            <div class="sidebar-search p-2 border-bottom">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" id="sidebarSearchInput" placeholder="Chercher un client ...">
                </div>
            </div>
            
            <div class="sidebar-content flex-grow-1 overflow-auto p-2" id="unplannedSidebar">
                @foreach($groupedUnplanned as $date => $items)
                    <div class="date-group mb-3">
                        <div class="date-header small fw-bold text-muted p-2 bg-light rounded d-flex align-items-center mb-2" style="cursor: pointer;">
                            <i class="fas fa-caret-down me-2 transition-icon"></i>
                            {{ $date == 'no_date' ? 'À planifier' : \Carbon\Carbon::parse($date)->translatedFormat('l j F') }}
                        </div>
                        <div class="date-group-content">
                            @foreach($items as $item)
                                <div class="intervention-card p-2 mb-2 border rounded shadow-sm d-flex flex-column gap-1" 
                                     style="background: white; border-left: 4px solid {{ $item->item_type == 'interv' ? '#1E7A6F' : '#3f51b5' }}; cursor: move; transition: all 0.2s;" 
                                     draggable="true"
                                     data-id="{{ $item->item_type }}_{{ $item->id }}"
                                     data-client="{{ strtolower($item->client?->raison_sociale ?? '') }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="small fw-bold text-dark text-truncate" style="max-width: 180px;">{{ $item->client?->raison_sociale ?? 'Sans client' }}</div>
                                        <div class="d-flex align-items-center gap-1">
                                            <i class="fas {{ $item->item_type == 'interv' ? 'fa-calendar-check text-teal' : 'fa-stethoscope text-indigo' }}" style="font-size: 10px;"></i>
                                            <span class="badge {{ $item->item_type == 'interv' ? 'bg-teal-subtle text-teal' : 'bg-indigo-subtle text-indigo' }}" style="font-size: 9px; padding: 2px 4px;">
                                                {{ $item->item_type == 'interv' ? 'INT' : 'VP' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-muted text-truncate" style="font-size: 10px;">
                                        {{ $item->subject ?? 'Sans sujet' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
                
                @if($groupedUnplanned->isEmpty())
                    <div class="text-center py-5 opacity-50">
                        <i class="fas fa-check-circle fa-3x mb-3"></i>
                        <p class="small">Aucune intervention en attente</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .weekend-cell {
        background-color: #f8fafc;
        background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(0,0,0,0.02) 10px, rgba(0,0,0,0.02) 20px);
    }
    .grid-cell:hover .cell-overlay { opacity: 1; pointer-events: none; }
    .grid-cell.drag-over { background-color: rgba(0, 140, 210, 0.1) !important; outline: 2px dashed #008cd2; }
    
    .intervention-card:hover { transform: scale(1.02); box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important; z-index: 5; }
    .intervention-card.dragging { opacity: 0.5; transform: scale(0.95); }
    
    .bg-teal-subtle { background-color: #e6fffa; }
    .text-teal { color: #319795; }
    .bg-indigo-subtle { background-color: #ebf4ff; }
    .text-indigo { color: #4c51bf; }
    
    .transition-all { transition: all 0.2s ease-in-out; }
    .transition-icon { transition: transform 0.2s ease; }
    
    .resource-grid td, .resource-grid th { height: auto !important; }
    .animate__animated { --animate-duration: 0.4s; }

    /* Filter styles */
    .btn-check:not(:checked) + label.btn-white { opacity: 0.5; background: #f8fafc; color: #718096 !important; }
    .btn-check:checked + label.btn-white { background: #fff; font-weight: bold; }
    .text-teal { color: #1E7A6F !important; }
    .text-indigo { color: #3f51b5 !important; }

    /* Custom Scrollbar */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: #f1f1f1; }
    ::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: #a0aec0; }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.intervention-card');
    const cells = document.querySelectorAll('.grid-cell');
    const sidebarSearch = document.getElementById('sidebarSearchInput');
    const userSearch = document.getElementById('userSearch');
    const typeFilters = document.querySelectorAll('.type-filter');
    
    // Drag and Drop Logic
    cards.forEach(card => {
        card.addEventListener('dragstart', (e) => {
            e.dataTransfer.setData('text/plain', e.target.dataset.id);
            e.target.classList.add('dragging');
        });
        
        card.addEventListener('dragend', (e) => {
            e.target.classList.remove('dragging');
        });
    });

    cells.forEach(cell => {
        cell.addEventListener('dragover', (e) => {
            e.preventDefault();
            if (!cell.classList.contains('weekend-cell')) {
                cell.classList.add('drag-over');
            }
        });

        cell.addEventListener('dragleave', (e) => {
            cell.classList.remove('drag-over');
        });

        cell.addEventListener('drop', (e) => {
            e.preventDefault();
            cell.classList.remove('drag-over');
            const id = e.dataTransfer.getData('text/plain');
            
            // Get user ID and date accurately
            const row = cell.closest('.user-row');
            const userId = row ? row.dataset.userId : null;
            
            // Map cell position to date
            const cellIndex = Array.from(cell.parentNode.children).indexOf(cell) - 1;
            const targetDay = @json($week_days)[cellIndex];
            
            if (id && userId && targetDay) {
                confirmAndSchedule(id, userId, targetDay);
            }
        });
    });

    function confirmAndSchedule(eventId, userId, date) {
        Swal.fire({
            title: 'Planifier l\'intervention ?',
            text: `Assigner l'élément au technicien pour le ${date}`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#008cd2',
            cancelButtonColor: '#718096',
            confirmButtonText: 'Oui, planifier',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                scheduleEvent(eventId, userId, date);
            }
        });
    }

    function scheduleEvent(eventId, userId, date) {
        Swal.fire({
            title: 'Planification en cours...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        fetch('{{ route("planning.update", ["id" => ":id"]) }}'.replace(':id', eventId), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                user_id: userId,
                start: date + ' 09:00:00',
                end: date + ' 11:00:00',
                is_planned: true
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Succès !',
                    text: 'L\'intervention a été planifiée.',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Erreur', data.message || 'Une erreur est survenue', 'error');
            }
        })
        .catch(error => {
            Swal.fire('Erreur', 'Impossible de contacter le serveur', 'error');
        });
    }

    // Unified Filtering Logic
    function applyFilters() {
        const term = sidebarSearch.value.toLowerCase();
        const activeTypes = Array.from(typeFilters).filter(f => f.checked).map(f => f.dataset.type);

        // Filter Grid Events
        document.querySelectorAll('.event-pill').forEach(pill => {
            const type = pill.dataset.type;
            pill.style.display = activeTypes.includes(type) ? 'block' : 'none';
        });

        // Filter Sidebar Cards
        document.querySelectorAll('.intervention-card').forEach(card => {
            const clientName = card.dataset.client || '';
            const type = card.dataset.id.split('_')[0];
            const inTerm = clientName.includes(term);
            const inType = activeTypes.includes(type);
            
            card.style.display = (inTerm && inType) ? 'flex' : 'none';
        });
        
        // Update Sidebar Groups Visibility
        document.querySelectorAll('.date-group').forEach(group => {
            const hasVisibleItems = Array.from(group.querySelectorAll('.intervention-card'))
                .some(card => card.style.display !== 'none');
            group.style.display = hasVisibleItems ? 'block' : 'none';
        });

        // Update Total Count Badge (optional but helpful)
        const totalVisible = Array.from(document.querySelectorAll('.intervention-card'))
            .filter(card => card.style.display !== 'none').length;
        const badge = document.querySelector('.sidebar-header .badge');
        if (badge) badge.innerText = totalVisible;
    }

    // Event Listeners for Filtering
    sidebarSearch.addEventListener('input', applyFilters);
    typeFilters.forEach(filter => filter.addEventListener('change', applyFilters));

    userSearch.addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('.user-row').forEach(row => {
            const name = row.querySelector('.fw-bold').innerText.toLowerCase();
            row.style.display = name.includes(term) ? 'table-row' : 'none';
        });
    });

    // Navigation
    const weekPicker = document.getElementById('weekPicker');
    if (weekPicker) {
        weekPicker.addEventListener('change', (e) => {
            window.location.href = `{{ route('planning.week') }}?week=${e.target.value}`;
        });
    }

    // Initial filter application
    applyFilters();

    // Accordion
    document.querySelectorAll('.date-header').forEach(header => {
        header.addEventListener('click', () => {
            const content = header.nextElementSibling;
            const icon = header.querySelector('.transition-icon');
            content.style.display = content.style.display === 'none' ? 'block' : 'none';
            icon.style.transform = content.style.display === 'none' ? 'rotate(-90deg)' : 'rotate(0deg)';
        });
    });
});
</script>
@endpush
@endsection
