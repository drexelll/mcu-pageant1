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
    <form action="{{ route('admin.events.update', $event->id) }}" method="POST" id="updateForm">
        @csrf
        @method('PUT')

        <div class="two-col" style="grid-template-columns: repeat(4, 1fr); gap: 1.25rem;">

            {{-- Event Details --}}
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
            </div>

            {{-- Judges --}}
            <div class="card">
                <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
                    <h2 class="card-title">Judges</h2>
                    <button type="button" class="btn btn--outline btn--sm" onclick="openAssignModal('judge')">Assign
                        Judges</button>
                </div>
                <div class="assigned-list">
                    @forelse($event->judges as $judge)
                        <div class="assigned-item">
                            <span class="assigned-item-name">{{ $judge->name }}</span>
                            <button type="button" class="assigned-remove"
                                onclick="removeAssigned({{ $judge->id }}, 'judge')">✕</button>
                        </div>
                    @empty
                        <div class="assigned-empty">No judges assigned</div>
                    @endforelse
                </div>
            </div>

            {{-- SAS --}}
            <div class="card">
                <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
                    <h2 class="card-title">SAS</h2>
                    <button type="button" class="btn btn--outline btn--sm" onclick="openAssignModal('sas')">Assign
                        SAS</button>
                </div>
                <div class="assigned-list">
                    @forelse($event->sas as $sas)
                        <div class="assigned-item">
                            <span class="assigned-item-name">{{ $sas->name }}</span>
                            <button type="button" class="assigned-remove"
                                onclick="removeAssigned({{ $sas->id }}, 'sas')">✕</button>
                        </div>
                    @empty
                        <div class="assigned-empty">No SAS assigned</div>
                    @endforelse
                </div>
            </div>

            {{-- Contestants --}}
            <div class="card">
                <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
                    <h2 class="card-title">Contestants</h2>
                    <button type="button" class="btn btn--outline btn--sm" onclick="openAssignModal('contestant')">Assign
                        Contestants</button>
                </div>
                <div class="assigned-list">
                    @forelse($event->contestants as $contestant)
                        <div class="assigned-item">
                            <span class="assigned-item-name">#{{ $contestant->number }} — {{ $contestant->name }}
                                ({{ $contestant->course }})</span>
                            <button type="button" class="assigned-remove"
                                onclick="removeAssigned({{ $contestant->id }}, 'contestant')">✕</button>
                        </div>
                    @empty
                        <div class="assigned-empty">No contestants assigned</div>
                    @endforelse
                </div>
            </div>


        </div>

        {{-- Update button --}}
        <div class="form-actions" style="margin-top:1.5rem;">
            <button type="submit" class="btn btn--gold">Update Event</button>
        </div>
    </form>

    {{-- Delete form (separate, sibling) --}}
    <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" id="deleteForm"
        style="margin-top:1.5rem;">
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

    {{-- Assign Modal --}}
    @include('admin.modals.assign-people')

    <script>
        const eventId = {{ $event->id }};
        const judges = @json($users->where('role', 'judge')->values());
        const sas = @json($users->where('role', 'sas')->values());
        const contestants = @json($contestants->values());

        const assignedJudges = @json($event->judges->pluck('id'));
        const assignedSas = @json($event->sas->pluck('id'));
        const assignedContestants = @json($event->contestants->pluck('id'));
    </script>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin_events.js') }}"></script>
@endpush
