@extends('layouts.appLayout')

@section('title', 'User Archive')

@section('content')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/userArchive.css') }}">
@endpush

<div class="page-header">
    <div>
        <div class="page-label">Admin Management</div>
        <h1 class="page-title">User Archive</h1>
        <div class="gold-line"></div>
    </div>
    <a href="{{ route('admin.user-roles') }}" class="btn btn--outline">← Back to Users</a>
</div>

@if(session('success'))
    <div id="flashSuccess" class="success-alert">
        ✔ {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div id="flashError" class="alert-error">
        ⚠ {{ session('error') }}
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Archived Users</h2>
        <span class="card-header-sub">Users are permanently deleted after 30 days</span>
    </div>

    <table class="tbl">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Deleted At</th>
                <th>Deletes In</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                @php
                    $daysLeft = max(0, 30 - (int) floor($user->deleted_at->diffInDays(now())));
                @endphp
                <tr>
                    <td class="td-strong">{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge badge--{{ $user->role }}">{{ ucfirst($user->role) }}</span>
                    </td>
                    <td>{{ $user->deleted_at->format('M d, Y') }}</td>
                    <td>
                        @if($daysLeft <= 5)
                            <span class="days-warning">⚠ {{ $daysLeft }} day(s)</span>
                        @else
                            <span>{{ $daysLeft }} day(s)</span>
                        @endif
                    </td>
                    <td>
                        <div class="tbl-actions">
                            <button
                                type="button"
                                class="btn btn--sm btn--outline"
                                onclick="showRestoreConfirm(
                                    '{{ route('admin.users.restore', $user->id) }}',
                                    '{{ addslashes($user->name) }}'
                                )"
                            >
                                ↩ Restore
                            </button>

                            <button
                                type="button"
                                class="btn btn--sm btn--danger"
                                onclick="showDeleteConfirm(
                                    '{{ route('admin.users.force-delete', $user->id) }}',
                                    '{{ addslashes($user->name) }}'
                                )"
                            >
                                🗑 Delete Permanently
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="tbl-empty">
                        No archived users found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ═══════════════════════════════════════
     RESTORE CONFIRM MODAL
════════════════════════════════════════ --}}
<div class="alert-confirm" id="restoreConfirm">
    <div class="alert-confirm__icon-wrap alert-confirm__icon-wrap--restore">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
            <path d="M3 3v5h5"/>
        </svg>
    </div>
    <div class="alert-confirm__title">Restore User?</div>
    <div class="alert-confirm__name" id="restoreConfirmName"></div>
    <div class="alert-confirm__desc">
        This user will be <strong>restored</strong> and moved back to the active users list.
    </div>
    <div class="alert-confirm__actions">
        <button class="alert-confirm__btn alert-confirm__cancel" onclick="closeRestoreConfirm()">
            Cancel
        </button>
        <button class="alert-confirm__btn alert-confirm__restore" onclick="submitRestoreForm()">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                <path d="M3 3v5h5"/>
            </svg>
            Yes, Restore
        </button>
    </div>
</div>

<form method="POST" id="restoreForm" class="form--hidden">@csrf</form>
<div class="modal-backdrop" id="restoreBackdrop" onclick="closeRestoreConfirm()"></div>

{{-- ═══════════════════════════════════════
     PERMANENT DELETE CONFIRM MODAL
════════════════════════════════════════ --}}
<div class="alert-confirm" id="deleteConfirm">
    <div class="alert-confirm__icon-wrap alert-confirm__icon-wrap--delete">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="3 6 5 6 21 6"/>
            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
            <path d="M10 11v6M14 11v6"/>
            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
        </svg>
    </div>
    <div class="alert-confirm__title">Permanently Delete?</div>
    <div class="alert-confirm__name" id="deleteConfirmName"></div>
    <div class="alert-confirm__desc">
        This action <strong>cannot be undone.</strong> The user will be removed forever.
    </div>
    <div class="alert-confirm__actions">
        <button class="alert-confirm__btn alert-confirm__cancel" onclick="closeDeleteConfirm()">
            Cancel
        </button>
        <button class="alert-confirm__btn alert-confirm__delete" onclick="submitDeleteForm()">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                <path d="M10 11v6M14 11v6"/>
                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
            </svg>
            Yes, Delete
        </button>
    </div>
</div>

<form method="POST" id="deleteForm" class="form--hidden">
    @csrf
    @method('DELETE')
</form>
<div class="modal-backdrop" id="deleteBackdrop" onclick="closeDeleteConfirm()"></div>

{{-- ═══════════════════════════════════════
     SCRIPTS
════════════════════════════════════════ --}}
<script>
    /* ── Flash auto-hide ── */
    document.addEventListener('DOMContentLoaded', () => {
        ['flashSuccess', 'flashError'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                setTimeout(() => {
                    el.style.transition = 'opacity 0.5s ease';
                    el.style.opacity = '0';
                    setTimeout(() => el.remove(), 500);
                }, 3500);
            }
        });
    });

    /* ── Restore modal ── */
    function showRestoreConfirm(actionUrl, userName) {
        document.getElementById('restoreForm').action             = actionUrl;
        document.getElementById('restoreConfirmName').textContent = userName;
        document.getElementById('restoreConfirm').style.display   = 'flex';
        document.getElementById('restoreBackdrop').style.display  = 'block';
    }

    function closeRestoreConfirm() {
        document.getElementById('restoreConfirm').style.display  = 'none';
        document.getElementById('restoreBackdrop').style.display = 'none';
    }

    function submitRestoreForm() {
        document.getElementById('restoreForm').submit();
    }

    /* ── Delete modal ── */
    function showDeleteConfirm(actionUrl, userName) {
        document.getElementById('deleteForm').action             = actionUrl;
        document.getElementById('deleteConfirmName').textContent = userName;
        document.getElementById('deleteConfirm').style.display   = 'flex';
        document.getElementById('deleteBackdrop').style.display  = 'block';
    }

    function closeDeleteConfirm() {
        document.getElementById('deleteConfirm').style.display  = 'none';
        document.getElementById('deleteBackdrop').style.display = 'none';
    }

    function submitDeleteForm() {
        document.getElementById('deleteForm').submit();
    }

    /* ── Close all on Escape ── */
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeRestoreConfirm();
            closeDeleteConfirm();
        }
    });
</script>
@endsection
