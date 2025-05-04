@extends('layouts.app')

@section('title', 'Campaign Calendar')

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<style>
    /* Calendar Container Styles */
    .calendar-container {
        background: white;
        padding: 2rem;
        border-radius: 0.75rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .calendar-title {
        font-family: 'Poppins', sans-serif;
        font-size: 1.75rem;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
    }

    /* Button Styles */
    .action-btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .action-btn i {
        font-size: 0.875rem;
    }

    .action-btn.primary {
        background: #4361ee;
        border: none;
        color: white;
    }

    .action-btn.secondary {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        color: #495057;
    }

    /* FullCalendar Customization */
    .fc {
        font-family: 'Poppins', sans-serif;
    }

    .fc .fc-toolbar {
        margin-bottom: 1.5rem;
    }

    .fc .fc-toolbar-title {
        font-size: 1.25rem;
        font-weight: 600;
    }

    .fc .fc-button {
        padding: 0.4rem 0.8rem;
        font-size: 0.875rem;
        border-radius: 6px;
        font-weight: 500;
        box-shadow: none;
    }

    .fc .fc-button-primary {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        color: #495057;
    }

    .fc .fc-button-primary:not(:disabled):hover {
        background: #e9ecef;
        border-color: #dee2e6;
        color: #212529;
    }

    .fc .fc-button-primary:not(:disabled).fc-button-active {
        background: #4361ee;
        border-color: #4361ee;
        color: white;
    }

    /* Calendar Grid and Events */
    .fc .fc-daygrid-day {
        min-height: 100px;
    }

    .fc .fc-daygrid-day-frame {
        padding: 4px;
    }

    .fc .fc-daygrid-day-number {
        font-size: 0.875rem;
        color: #495057;
        padding: 4px 8px;
    }

    .fc .fc-event {
        border: none;
        border-radius: 6px;
        padding: 4px 8px;
        font-size: 0.75rem;
        margin-bottom: 2px;
        cursor: pointer;
    }

    /* Event Colors by Category */
    .fc .fc-event.feeding-program {
        background-color: #4361ee !important;
        border-left: 3px solid #3046c0;
    }

    .fc .fc-event.outreach-program {
        background-color: #2ecc71 !important;
        border-left: 3px solid #27ae60;
    }

    .fc .fc-event.rice-distribution {
        background-color: #9b59b6 !important;
        border-left: 3px solid #8e44ad;
    }

    /* Category Legend */
    .category-legend {
        margin-top: 2rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .category-legend h6 {
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #495057;
    }

    .legend-item {
        display: inline-flex;
        align-items: center;
        margin-right: 1.5rem;
        font-size: 0.75rem;
        color: #6c757d;
    }

    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 3px;
        margin-right: 6px;
    }
</style>
@endpush

@section('content')
<div class="calendar-container">
    <div class="calendar-header">
        <h1 class="calendar-title">Campaign Calendar</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.categories.index') }}" class="action-btn secondary">
                <i class="fas fa-tags me-2"></i>Manage Categories
            </a>
            <a href="{{ route('admin.campaigns.create') }}" class="action-btn primary">
                <i class="fas fa-plus me-2"></i>Add New Campaign
            </a>
        </div>
    </div>
    <div id="calendar"></div>
    
    <div class="category-legend">
        <h6>Event Categories</h6>
        <div class="d-flex flex-wrap">
            <div class="legend-item">
                <div class="legend-color" style="background: #4361ee"></div>
                <span>Feeding Programs</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #2ecc71"></div>
                <span>Outreach Programs</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #9b59b6"></div>
                <span>Rice Distributions</span>
            </div>
        </div>
    </div>
</div>

<!-- Event Modal -->
<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Campaign Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="eventForm">
                    <div class="mb-3">
                        <label for="eventTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="eventTitle" name="title" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="eventStart" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="eventStart" name="start_date" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="eventEnd" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="eventEnd" name="end_date" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="eventStatus" class="form-label">Status</label>
                        <input type="text" class="form-control" id="eventStatus" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="eventPledged" class="form-label">Pledged Amount/Quantity</label>
                        <input type="text" class="form-control" id="eventPledged" readonly>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <a href="#" class="btn btn-primary" id="viewCampaign">View Details</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
    var currentEvent = null;

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: @json($campaigns),
        eventDisplay: 'block',
        displayEventEnd: true,
        eventTimeFormat: {
            hour: 'numeric',
            minute: '2-digit',
            meridiem: 'short'
        },
        eventClassNames: function(arg) {
            // Add category-based class
            return [arg.event.extendedProps.category.toLowerCase().replace(' ', '-')];
        },
        eventContent: function(arg) {
            return {
                html: `
                    <div class="fc-event-main-frame">
                        <div class="fc-event-title-container">
                            <div class="fc-event-title">${arg.event.title}</div>
                            ${arg.event.extendedProps.pledged ? 
                                `<div class="fc-event-desc">Pledged: ${arg.event.extendedProps.pledged}</div>` 
                                : ''}
                        </div>
                    </div>
                `
            };
        },
        eventClick: function(info) {
            currentEvent = info.event;
            document.getElementById('eventTitle').value = currentEvent.title;
            document.getElementById('eventStart').value = moment(currentEvent.start).format('YYYY-MM-DD');
            document.getElementById('eventEnd').value = moment(currentEvent.end).format('YYYY-MM-DD');
            document.getElementById('eventStatus').value = currentEvent.extendedProps.status;
            document.getElementById('eventPledged').value = currentEvent.extendedProps.pledged || 'N/A';
            document.getElementById('viewCampaign').href = '/admin/campaigns/' + currentEvent.id;
            eventModal.show();
        },
        eventDrop: function(info) {
            updateEventDates(info.event);
        },
        eventResize: function(info) {
            updateEventDates(info.event);
        },
        dayMaxEvents: true, // Allow "more" link when too many events
        height: 'auto'
    });

    calendar.render();

    function updateEventDates(event) {
        fetch(`/admin/calendar/${event.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                start_date: moment(event.start).format('YYYY-MM-DD'),
                end_date: moment(event.end).format('YYYY-MM-DD')
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                // Show success message
                alert('Campaign dates updated successfully');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            calendar.refetchEvents();
        });
    }
});
</script>
@endpush 