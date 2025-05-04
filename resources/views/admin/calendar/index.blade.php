@extends('layouts.app')

@section('title', 'Campaign Calendar')

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<style>
    .fc-event {
        cursor: pointer;
        border-radius: 4px;
        border: none;
    }
    .calendar-container {
        background: white;
        padding: 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,.08);
    }
    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .calendar-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #333;
        margin: 0;
    }
    #calendar {
        margin-top: 20px;
    }
</style>
@endpush

@section('content')
<div class="calendar-container">
    <div class="calendar-header">
        <h1 class="calendar-title">Campaign Calendar</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('auth.admin.categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-tags me-2"></i>Manage Categories
            </a>
            <a href="{{ route('admin.campaigns.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Campaign
            </a>
        </div>
    </div>
    <div id="calendar"></div>
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
        editable: true,
        eventClick: function(info) {
            currentEvent = info.event;
            document.getElementById('eventTitle').value = currentEvent.title;
            document.getElementById('eventStart').value = moment(currentEvent.start).format('YYYY-MM-DD');
            document.getElementById('eventEnd').value = moment(currentEvent.end).format('YYYY-MM-DD');
            document.getElementById('eventStatus').value = currentEvent.extendedProps.status;
            document.getElementById('viewCampaign').href = '/admin/campaigns/' + currentEvent.id;
            eventModal.show();
        },
        eventDrop: function(info) {
            updateEventDates(info.event);
        },
        eventResize: function(info) {
            updateEventDates(info.event);
        }
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