@extends('layouts.appLayout')

@section('content')
<div class="page-header">
    <div>
        <div class="page-label">Admin Panel</div>
        <h1 class="page-title">Create Event</h1>
        <div class="gold-line"></div>
    </div>
    <a href="{{ route('admin.events') }}" class="btn btn--outline">← Back</a>
</div>

<form action="{{ route('admin.events.store') }}" method="POST" id="createForm">
    @csrf

    <div class="two-col" style="grid-template-columns: repeat(3, 1fr); gap: 1.25rem;">
        {{-- Event Details --}}
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Event Details</h2>
            </div>
            <div class="form-group">
                <label class="form-label">Event Name</label>
                <input type="text" name="eventName" class="form-input" placeholder="e.g. MCU Pageant 2025">
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-input">
                    <option value="upcoming">Upcoming</option>
                    <option value="live">Live</option>
                    <option value="done">Done</option>
                </select>
            </div>
        </div>

        {{-- Judges --}}
        <div class="card">
            <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
                <h2 class="card-title">Judges</h2>
                <button type="button" class="btn btn--outline btn--sm" onclick="openAssignModal('judge')">Assign Judges</button>
            </div>
            <div class="assigned-list">
                <div class="assigned-empty">No judges assigned yet</div>
            </div>
        </div>

        {{-- SAS --}}
        <div class="card">
            <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
                <h2 class="card-title">SAS</h2>
                <button type="button" class="btn btn--outline btn--sm" onclick="openAssignModal('sas')">Assign SAS</button>
            </div>
            <div class="assigned-list">
                <div class="assigned-empty">No SAS assigned yet</div>
            </div>
        </div>
    </div>

    {{-- Save button --}}
    <div class="form-actions" style="margin-top:1.5rem;">
        <button type="submit" class="btn btn--gold" id="saveEventBtn">Save Event</button>
    </div>
</form>

{{-- Assign Modal --}}
@include('admin.modals.assign-people')

<script>
    const judges = @json($users->where('role', 'judge')->values());
    const sas = @json($users->where('role', 'sas')->values());
    const contestants = @json($contestants->values());

    const assignedJudges = [];
    const assignedSas = [];
    const assignedContestants = [];
</script>
@endsection

@push('scripts')
<script src="{{ asset('js/admin/admin_events.js') }}"></script>
@endpush
