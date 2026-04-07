@extends('layouts.app')

@section('title', 'Kalender Booking Ruangan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Kalender</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12 mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Kalender Booking Ruangan</h4>
            <div class="form-inline">
                <label class="mr-2 text-muted">Filter Ruangan</label>
                <select id="roomFilter" class="form-control form-control-sm">
                    <option value="all">Semua Ruangan</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body p-3">
                <div id="calendarFull"></div>
            </div>
        </div>
    </div>
</div>

<!-- Event Detail Modal -->
<div class="modal fade" id="eventDetailModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eventTitle">Detail Acara</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="eventInfo"></p>
      </div>
      <div class="modal-footer">
        <a href="#" id="viewMeetingBtn" class="btn btn-primary">Lihat Rapat</a>
        <button type="button" id="manageBtn" class="btn btn-success" style="display:none">Kelola</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet" />
<style>
#calendarFull { background: white; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendarFull');
    const roomFilter = document.getElementById('roomFilter');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay' },
        height: 'auto',
        locale: 'id',
        events: function(info, successCallback, failureCallback) {
            fetch(`{{ route('dashboard.calendar-events') }}?start=${info.startStr}&end=${info.endStr}`)
                .then(r => r.json())
                .then(data => {
                    // populate room filter options
                    const locations = new Set();
                    data.forEach(e => {
                        if (e.extendedProps && e.extendedProps.type === 'meeting' && e.extendedProps.location && e.extendedProps.location !== 'Online') {
                            locations.add(e.extendedProps.location);
                        }
                    });
                    // add options
                    locations.forEach(loc => {
                        if (!Array.from(roomFilter.options).some(o => o.value === loc)) {
                            const opt = document.createElement('option');
                            opt.value = loc; opt.text = loc;
                            roomFilter.appendChild(opt);
                        }
                    });

                    let filtered = data;
                    if (roomFilter.value !== 'all') {
                        filtered = data.filter(e => e.extendedProps && e.extendedProps.location === roomFilter.value);
                    }
                    successCallback(filtered);
                })
                .catch(err => failureCallback(err));
        },
        eventClick: function(info) {
            info.jsEvent.preventDefault();
            const e = info.event;
            const props = e.extendedProps || {};

            document.getElementById('eventTitle').textContent = e.title.replace('📅 ', '');
            const organizerLabel = props.type === 'room_booking' ? 'Peminjam / PIC' : 'Penyelenggara';
            document.getElementById('eventInfo').innerHTML = `
                <strong>Waktu:</strong> ${props.original_start} - ${props.original_end}<br>
                <strong>Lokasi:</strong> ${props.location || 'Online'}<br>
                <strong>${organizerLabel}:</strong> ${props.organizer || '-'}
            `;
            const viewBtn = document.getElementById('viewMeetingBtn');
            viewBtn.href = e.url || '#';

            const manageBtn = document.getElementById('manageBtn');
            if (props.is_participant) {
                manageBtn.style.display = 'inline-block';
                manageBtn.onclick = function() {
                    // redirect to meeting page for participants
                    window.location.href = e.url;
                };
            } else {
                manageBtn.style.display = 'none';
            }

            $('#eventDetailModal').modal('show');
        }
    });

    calendar.render();

    roomFilter.addEventListener('change', function() {
        calendar.refetchEvents();
    });
});
</script>
@endpush
