@extends('layouts.appLayout')

@section('content')

<div class="page-header">
    <div>
        <div class="page-label">Admin Panel</div>
        <h1 class="page-title">Edit Event</h1>
        <div class="gold-line"></div>
    </div>
    <a href="{{ route('admin.events') }}" class="btn btn--outline">← Back</a>
</div>

{{-- Update form --}}
<form action="{{ route('admin.events.update', $event->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="event-form-layout">
        <div class="event-form-main">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Event Details</h2>
                </div>

                <div class="form-group">
                    <label class="form-label">Event Name</label>
                    <input type="text" name="eventName" class="form-input" value="{{ $event->eventName }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input">
                        <option value="upcoming" {{ $event->status == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="live" {{ $event->status == 'live' ? 'selected' : '' }}>Live</option>
                        <option value="done" {{ $event->status == 'done' ? 'selected' : '' }}>Done</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn--gold">Update Event</button>
                </div>
            </div>
        </div>

        {{-- Right: Assign Judges & SAS --}}
        <div class="card">
    <div class="card-header"><h2 class="card-title">Judges</h2></div>
    <div class="assigned-list">
        <select name="judges[]" multiple class="form-input">
            @foreach($users->where('role', 'judge') as $judge)
                <option value="{{ $judge->id }}"
                    {{ $event->judges->contains($judge->id) ? 'selected' : '' }}>
                    {{ $judge->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="card">
    <div class="card-header"><h2 class="card-title">SAS</h2></div>
    <div class="assigned-list">
        <select name="sas[]" multiple class="form-input">
            @foreach($users->where('role', 'sas') as $sas)
                <option value="{{ $sas->id }}"
                    {{ $event->sas->contains($sas->id) ? 'selected' : '' }}>
                    {{ $sas->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

    </div>
</form>

{{-- Delete form (separate, not nested) --}}
<form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" id="deleteForm" style="display:inline;">
    @csrf
    @method('DELETE')
    <button type="button" id="deleteBtn" class="btn btn--danger">Delete Event</button>
</form>

{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="alert-confirm" style="display:none;">
    <span class="checkmark">⚠</span>
    <p>Are you sure you want to delete this event?</p>
    <div style="margin-top:1rem; display:flex; gap:0.5rem;">
        <button id="cancelDelete" class="btn btn--gold">Cancel</button>
        <button id="confirmDelete" class="btn btn--danger">Yes, Delete</button>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/admin_events.js') }}"></script>
@endpush
