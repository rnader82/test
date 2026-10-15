@extends('layouts.app')
@section('portal-module', 'planning')
@section('portal-page', 'index')
@section('portal-kind', 'planning')
@section('portal-family', 'technical')
@section('portal-icon', 'screwdriver-wrench')


@section('navbar-title')
    <div class="d-flex align-items-center gap-2">
        <h1>{{ __('locale.menu_planification') }}</h1>
    </div>
@endsection

@section('page-actions')
    <div class="btn-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
        <a href="{{ route('planning.index') }}" class="btn btn-primary px-4">{{ __('locale.lbl_calendar') ?? 'Calendrier' }}</a>
        <a href="{{ route('planning.week') }}" class="btn btn-outline-primary px-4">{{ __('locale.lbl_weekly') ?? 'Hebdomadaire' }}</a>
    </div>
@endsection

@section('content')
<div class="container-fluid py-4" style="background: #f8fbff; min-height: 100vh;">
    <div class="row">
        <!-- Main Calendar -->
        <div class="col-md-9">
            <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
                <div class="card-body p-4 bg-white">
                    <div id="calendar" style="min-height: 700px;"></div>
                </div>
            </div>
        </div>

        <!-- Sidebar for Unplanned Events -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px; background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px);">
                <div class="card-header bg-white border-0 py-3" style="border-radius: 15px 15px 0 0;">
                    <!-- Search and Filters -->
                    <div class="px-1">
                        <div class="input-group input-group-sm mb-3 shadow-sm border-0" style="border-radius: 10px; overflow: hidden;">
                            <span class="input-group-text bg-white border-0 text-muted"><i class="fas fa-search"></i></span>
                            <input type="text" id="sidebarSearch" class="form-control border-0 bg-white" placeholder="{{ __('locale.search_by_client') ?? 'Rechercher par client...' }}" style="box-shadow: none;">
                        </div>
                        <div class="d-flex gap-1 mb-2">
                            <button class="btn btn-outline-primary btn-sm filter-type active flex-grow-1" data-type="all">{{ __('locale.all') ?? 'Tous' }}</button>
                            <button class="btn btn-outline-primary btn-sm filter-type flex-grow-1" data-type="interv">{{ __('locale.menu_interventions') }}</button>
                            <button class="btn btn-outline-primary btn-sm filter-type flex-grow-1" data-type="vp">{{ __('locale.menu_visite_prev') }}</button>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-list-ul me-2"></i>{{ __('locale.lbl_unplanned') ?? 'Non planifié' }}</h5>
                        <span class="badge bg-primary rounded-pill sidebar-badge">{{ count($unplannedInterventions) + count($unplannedVps) }}</span>
                    </div>
                </div>
                <div class="card-body p-0" style="max-height: 60vh; overflow-y: auto;">
                    <div id="external-events">
                        <div class="p-3">
                            <div id="interventionsList">
                                <h6 class="text-muted small text-uppercase fw-bold mb-3 section-title" data-type="interv">{{ __('locale.menu_interventions') }}</h6>
                                @forelse($unplannedInterventions as $interv)
                                    <div class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event mb-2 p-2 shadow-sm border-0 event-item" 
                                         style="background-color: #1E7A6F; cursor: move; border-radius: 8px;"
                                         data-id="interv_{{ $interv->id }}"
                                         data-type="interv"
                                         data-client="{{ strtolower($interv->client?->raison_sociale ?? '') }}"
                                         data-title="{{ $interv->client?->raison_sociale ? $interv->client->raison_sociale . ' - ' : '' }}{{ $interv->subject }}"
                                         data-duration="02:00">
                                        <div class="fc-event-main text-white small d-flex align-items-center gap-2">
                                            <i class="fas fa-calendar-check opacity-75"></i>
                                            <div class="text-truncate">
                                                <strong>{{ $interv->client?->raison_sociale ?? 'N/A' }}</strong><br>
                                                {{ Str::limit($interv->subject, 30) }}
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted small italic px-2 no-items" data-type="interv">{{ __('locale.no_interventions_found') ?? 'Aucune intervention' }}</p>
                                @endforelse
                            </div>

                            <div id="vpsList">
                                <h6 class="text-muted small text-uppercase fw-bold mb-3 mt-4 section-title" data-type="vp">{{ __('locale.menu_visite_prev') }}</h6>
                                @forelse($unplannedVps as $vp)
                                    <div class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event mb-2 p-2 shadow-sm border-0 event-item" 
                                         style="background-color: #3f51b5; cursor: move; border-radius: 8px;"
                                         data-id="vp_{{ $vp->id }}"
                                         data-type="vp"
                                         data-client="{{ strtolower($vp->client?->raison_sociale ?? '') }}"
                                         data-title="VP: {{ $vp->client?->raison_sociale ?? 'Client' }}"
                                         data-duration="04:00">
                                        <div class="fc-event-main text-white small d-flex align-items-center gap-2">
                                            <i class="fas fa-stethoscope opacity-75"></i>
                                            <div class="text-truncate">
                                                <strong>{{ $vp->client?->raison_sociale ?? 'N/A' }}</strong><br>
                                                {{ __('locale.menu_visite_prev') }}
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted small italic px-2 no-items" data-type="vp">{{ __('locale.no_visite_prev_found') ?? 'Aucune visite' }}</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info border-0 shadow-sm" style="border-radius: 12px; font-size: 0.85rem;">
                <i class="fas fa-info-circle me-2"></i> {{ __('locale.drag_and_drop_hint') ?? 'Faites glisser les éléments sur le calendrier pour les planifier.' }}
            </div>
        </div>
    </div>
</div>

<!-- Modal for Event Details/Edit -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 15px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-3">
                <div id="modalBody"></div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <a href="#" id="modalUrl" class="btn btn-primary w-100 py-2" style="border-radius: 10px;">{{ __('locale.lbl_view_details') ?? 'Voir détails' }}</a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"
    integrity="sha384-5e2ESR8Ycmos6g3gAKr1Jvwye8sW4U1u/cAKulfVJnkakCcMqhOudbtPnvJ+nbv7" crossorigin="anonymous" referrerpolicy="no-referrer">
<style>
    .fc { font-family: 'Outfit', 'Inter', sans-serif; --fc-border-color: #edf2f7; --fc-today-bg-color: rgba(66, 153, 225, 0.05); }
    .fc-toolbar-title { font-weight: 700; font-size: 1.5rem !important; color: #2d3748; }
    .fc-button-primary { background-color: #4299e1 !important; border-color: #4299e1 !important; text-transform: capitalize; border-radius: 8px !important; transition: all 0.3s ease; }
    .fc-button-primary:hover { background-color: #3182ce !important; transform: translateY(-1px); box-shadow: 0 4px 6px rgba(66, 153, 225, 0.2); }
    
    .filter-type.active {
        background-color: #4299e1 !important;
        color: white !important;
        box-shadow: 0 2px 4px rgba(66, 153, 225, 0.3);
    }

    /* Animation for Event Placement */
    .fc-event { 
        padding: 4px 8px; 
        border-radius: 8px !important; 
        border: none !important; 
        box-shadow: 0 2px 4px rgba(0,0,0,0.06); 
        margin: 2px 0;
        transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.2s ease, opacity 0.2s ease;
    }
    
    .fc-event:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        z-index: 10;
    }

    /* Pulse animation on drop success */
    @keyframes drop-pulse {
        0% { transform: scale(1.05); box-shadow: 0 0 0 0 rgba(66, 153, 225, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(66, 153, 225, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(66, 153, 225, 0); }
    }
    
    .fc-event-dropping {
        opacity: 0.6;
        transform: scale(0.95);
    }
    
    .fc-event-just-dropped {
        animation: drop-pulse 0.5s ease-out;
    }

    .fc-daygrid-day-number { color: #4a5568 !important; text-decoration: none !important; margin: 4px; font-weight: 600; }
    .fc-day-today { background: rgba(66, 153, 225, 0.03) !important; }
    
    #external-events .fc-event { 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }
    
    #external-events .fc-event:hover { 
        transform: translateX(5px) scale(1.02); 
        box-shadow: 0 8px 15px rgba(0,0,0,0.1); 
    }
    
    #external-events .fc-event::before {
        content: '⠿';
        position: absolute;
        left: 5px;
        top: 50%;
        transform: translateY(-50%);
        opacity: 0.5;
        font-size: 10px;
    }

    .bg-teal { background-color: #1E7A6F !important; color: white !important; }
    .bg-indigo { background-color: #3f51b5 !important; color: white !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"
    integrity="sha384-n4VL2mHw6ER8HxL4KH/pGa4ZTDkXrXtr5VHKdtXrgvrbP5MrydzsAFLP5kV9h9VY" crossorigin="anonymous"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var Draggable = FullCalendar.Draggable;
        var containerEl = document.getElementById('external-events');
        var calendarEl = document.getElementById('calendar');

        new Draggable(containerEl, {
            itemSelector: '.fc-event',
            eventData: function(eventEl) {
                return {
                    id: eventEl.dataset.id,
                    title: eventEl.dataset.title,
                    duration: eventEl.dataset.duration,
                    backgroundColor: eventEl.style.backgroundColor,
                    borderColor: eventEl.style.backgroundColor,
                    classNames: ['fc-event-just-dropped'] // Add animation on creation
                };
            }
        });

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            locale: '{{ app()->getLocale() }}',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            editable: true,
            droppable: true,
            themeSystem: 'bootstrap5',
            events: @json($events),
            
            // Drag Animation hooks
            eventDragStart: function(info) {
                info.el.classList.add('fc-event-dropping');
            },
            
            eventDragStop: function(info) {
                info.el.classList.remove('fc-event-dropping');
            },

            eventDrop: function(info) {
                info.el.classList.add('fc-event-just-dropped');
                setTimeout(() => info.el.classList.remove('fc-event-just-dropped'), 1000);
                updateEvent(info.event);
            },
            
            eventResize: function(info) {
                info.el.classList.add('fc-event-just-dropped');
                setTimeout(() => info.el.classList.remove('fc-event-just-dropped'), 1000);
                updateEvent(info.event);
            },
            
            eventReceive: function(info) {
                // Animation is already handled by classNames in eventData
                setTimeout(() => info.el.classList.remove('fc-event-just-dropped'), 1000);
                updateEvent(info.event);
                
                // Remove from sidebar
                if (info.draggedEl) {
                    info.draggedEl.remove();
                    filterSidebar(); // Update sections and empty messages
                }
            },
            
            eventClick: function(info) {
                info.jsEvent.preventDefault();
                showEventModal(info.event);
            },
            
            eventContent: function(arg) {
                let type = arg.event.extendedProps.type;
                let badgeClass = type === 'intervention' ? 'bg-teal' : 'bg-indigo';
                let badgeText = type === 'intervention' ? 'INT' : 'VP';
                
                return {
                    html: `
                        <div class="fc-content p-1" style="position: relative; min-height: 40px;">
                            <span class="badge ${badgeClass}" style="position: absolute; top: 0; right: 0; font-size: 0.6rem; padding: 2px 4px; border-radius: 0 8px 0 8px;">${badgeText}</span>
                            <div class="fw-bold text-truncate" style="font-size: 0.85rem; margin-right: 25px;">${arg.event.title}</div>
                            <div class="small opacity-75 text-truncate" style="font-size: 0.7rem;">${arg.event.extendedProps.technician_name || ''}</div>
                        </div>
                    `
                };
            }
        });
        
        calendar.render();

        // Filtering Logic
        const sidebarSearch = document.getElementById('sidebarSearch');
        const filterButtons = document.querySelectorAll('.filter-type');
        const eventItems = document.querySelectorAll('.event-item');
        const sections = document.querySelectorAll('.section-title, .no-items');

        function filterSidebar() {
            const searchTerm = sidebarSearch.value.toLowerCase();
            const activeType = document.querySelector('.filter-type.active').dataset.type;

            eventItems.forEach(item => {
                const clientName = item.dataset.client;
                const itemType = item.dataset.type;
                
                const matchesSearch = clientName.includes(searchTerm);
                const matchesType = activeType === 'all' || itemType === activeType;

                if (matchesSearch && matchesType) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });

            // Hide/Show sections based on visible items
            ['interv', 'vp'].forEach(type => {
                const sectionItems = document.querySelectorAll(`.event-item[data-type="${type}"]`);
                const visibleInSection = Array.from(sectionItems).filter(i => i.style.display !== 'none').length;
                
                const sectionTitle = document.querySelector(`.section-title[data-type="${type}"]`);
                const noItemsMsg = document.querySelector(`.no-items[data-type="${type}"]`);

                if (visibleInSection > 0) {
                    if (sectionTitle) sectionTitle.style.display = 'block';
                    if (noItemsMsg) noItemsMsg.style.display = 'none';
                } else {
                    // Only show title if it matches type filter or if type filter is all
                    const shouldShowTitle = activeType === 'all' || activeType === type;
                    if (sectionTitle) sectionTitle.style.display = shouldShowTitle ? 'block' : 'none';
                    if (noItemsMsg) noItemsMsg.style.display = shouldShowTitle ? 'block' : 'none';
                }
            });

            // Update Total Count Badge
            const totalVisible = Array.from(eventItems).filter(item => item.style.display !== 'none').length;
            const badge = document.querySelector('.sidebar-badge');
            if (badge) badge.innerText = totalVisible;
        }

        sidebarSearch.addEventListener('input', filterSidebar);

        filterButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                filterButtons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                filterSidebar();
            });
        });

        function updateEvent(event) {
            let start = event.startStr;
            let end = event.endStr || start;
            let id = event.id;

            fetch(`/planning/update/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    start: start,
                    end: end
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Event updated successfully');
                    // Optional: show a small toast or notification
                } else {
                    alert('Error updating event');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Connection error');
            });
        }
        
        function showEventModal(event) {
            document.getElementById('modalTitle').innerText = event.title;
            document.getElementById('modalBody').innerHTML = `
                <div class="mb-3">
                    <p class="mb-1 text-muted small"><i class="far fa-clock me-2"></i>{{ __('locale.lbl_period') ?? 'Période' }}</p>
                    <p class="fw-bold mb-0">${event.start.toLocaleString()} - ${event.end ? event.end.toLocaleString() : ''}</p>
                </div>
            `;
            document.getElementById('modalUrl').href = event.url;
            new bootstrap.Modal(document.getElementById('eventModal')).show();
        }
    });
</script>
@endpush
@endsection