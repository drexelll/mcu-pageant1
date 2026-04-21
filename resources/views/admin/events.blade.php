@extends('layouts.appLayout')

@section('content')

<div class="page-header">
    <div>
        <div class="page-label">Admin Panel</div>
        <h1 class="page-title">Events</h1>
        <div class="gold-line"></div>
    </div>
    <a href="{{ route('admin.events.create') }}" class="btn btn--gold">+ Add Event</a>
</div>

{{-- Success message --}}
@if(session('success'))
    <div class="alert-success">
        <span class="checkmark">✔</span>
        {{ session('success') }}
    </div>
@endif

{{-- Events List --}}
<div class="card">
    <div class="card-header">
        <h2 class="card-title">All Events</h2>
    </div>

    <table class="tbl">
        <thead>
            <tr>
                <th>Name</th>
                <th>Status</th>
                <th>Created</th>
                <th>Updated</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($events as $event)
                <tr>
                    <td class="td-strong">{{ $event->eventName }}</td>
                    <td>
                        <span class="badge badge--{{ $event->status }}">
                            {{ ucfirst($event->status) }}
                        </span>
                    </td>
                    <td>{{ $event->created_at->format('M d, Y') }}</td>
                    <td>{{ $event->updated_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn--outline btn--sm">Edit</a>
                        {{-- You can add delete later --}}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center; color:rgba(0,0,0,0.4);">
                        No events found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/admin/admin_events.js') }}"></script>
@endpush
