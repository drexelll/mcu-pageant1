@extends('layouts.appLayout')

@section('title', 'Contestants')

@section('content')
<div class="page-header">
    <div>
        <div class="page-label">SAS Panel</div>
        <h1 class="page-title">Contestants</h1>
        <div class="gold-line"></div>
    </div>
    <a href="{{ route('sas.contestants.create') }}" class="btn btn--gold">+ Add Contestant</a>
</div>

{{-- Success message --}}
@if(session('success'))
    <div class="alert-success">
        <span class="checkmark">✔</span>
        {{ session('success') }}
    </div>
@endif

{{-- Contestants List --}}
<div class="card">
    <div class="card-header">
        <h2 class="card-title">All Contestants</h2>
    </div>

    <table class="tbl">
        <thead>
            <tr>
                <th>Number</th>
                <th>Name</th>
                <th>Course</th>
                <th>Created</th>
                <th>Updated</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($contestants as $contestant)
                <tr>
                    <td class="td-strong">#{{ $contestant->number }}</td>
                    <td>{{ $contestant->name }}</td>
                    <td>{{ $contestant->course }}</td>
                    <td>{{ $contestant->created_at->format('M d, Y') }}</td>
                    <td>{{ $contestant->updated_at->format('M d, Y') }}</td>
                    <td>
                        {{-- <a href="{{ route('sas.contestants.edit', $contestant->id) }}" class="btn btn--outline btn--sm">Edit</a> --}}
                        {{-- Delete button can be added later --}}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center; color:rgba(0,0,0,0.4);">
                        No contestants found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
