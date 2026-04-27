@extends('layouts.appLayout')

@section('title', 'Contestants')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/userRoles.css') }}">
@endpush

@section('content')

@if(session('success'))
    <div id="successAlert" class="success-alert">
        ✔ {{ session('success') }}
    </div>
@endif

<div class="page-header">
    <div>
        <div class="page-label">SAS Panel</div>
        <h1 class="page-title">Contestants</h1>
        <div class="gold-line"></div>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('sas.contestants.archive') }}" class="btn btn--outline">🗄 Archive</a>
        <a href="{{ route('sas.contestants.create') }}" class="btn btn--gold">+ Add Contestant</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">All Contestants</h2>
    </div>

    <table class="tbl">
        <thead>
            <tr>
                <th>ID</th>
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
                    <td>{{ $contestant->id }}</td>
                    <td class="td-strong">#{{ $contestant->number }}</td>
                    <td>{{ $contestant->name }}</td>
                    <td>{{ $contestant->course }}</td>
                    <td>{{ $contestant->created_at->format('M d, Y') }}</td>
                    <td>{{ $contestant->updated_at->format('M d, Y') }}</td>
                    <td>
                        <div class="tbl-actions">
                            <a href="{{ route('sas.contestants.edit', $contestant->id) }}"
                               class="btn btn--outline btn--sm">✏ Edit</a>

                            <button
                                type="button"
                                class="btn btn--sm btn--danger"
                                onclick="showArchiveConfirm(
                                    '{{ route('sas.contestants.destroy', $contestant->id) }}',
                                    '{{ addslashes($contestant->name) }}'
                                )"
                            >
                                🗑 Delete
                            </button>
                        </div>
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

{{-- ═══════════════════════════════════════
     ARCHIVE CONFIRM MODAL
     (same as user-roles — copied exactly)
════════════════════════════════════════ --}}
<div class="archive-confirm" id="archiveConfirm">
    <div class="archive-confirm__icon-wrap">📦</div>
    <div class="archive-confirm__title">Archive Contestant?</div>
    <div class="archive-confirm__name" id="archiveConfirmName"></div>
    <div class="archive-confirm__desc">
        This contestant will be moved to the archive and permanently deleted after <strong>30 days</strong>.
    </div>
    <div class="archive-confirm__actions">
        <button class="archive-confirm__btn archive-confirm__cancel" onclick="closeArchiveConfirm()">
            Cancel
        </button>
        <button class="archive-confirm__btn archive-confirm__submit" onclick="submitArchiveForm()">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 8v13H3V8"/><path d="M1 3h22v5H1z"/><path d="M10 12h4"/>
            </svg>
            Yes, Archive
        </button>
    </div>
</div>

<form method="POST" id="archiveForm" class="form--hidden">
    @csrf
    @method('DELETE')
</form>

<div class="archive-backdrop" id="archiveBackdrop" onclick="closeArchiveConfirm()"></div>

@endsection

@push('scripts')
<script>
    /* ── Flash auto-hide ── */
    document.addEventListener('DOMContentLoaded', () => {
        const el = document.getElementById('successAlert');
        if (el) {
            setTimeout(() => {
                el.style.transition = 'opacity 0.5s ease';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 500);
            }, 3500);
        }
    });

    /* ── Archive modal ── */
    function showArchiveConfirm(actionUrl, contestantName) {
        document.getElementById('archiveForm').action             = actionUrl;
        document.getElementById('archiveConfirmName').textContent = contestantName;
        document.getElementById('archiveConfirm').style.display   = 'flex';
        document.getElementById('archiveBackdrop').style.display  = 'block';
    }

    function closeArchiveConfirm() {
        document.getElementById('archiveConfirm').style.display  = 'none';
        document.getElementById('archiveBackdrop').style.display = 'none';
    }

    function submitArchiveForm() {
        document.getElementById('archiveForm').submit();
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeArchiveConfirm();
    });
</script>
@endpush
