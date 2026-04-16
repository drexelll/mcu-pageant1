@extends('layouts.appLayout')

@section('title', 'User Archive')

@section('content')

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
                            <span style="color:#dc2626; font-weight:600;">⚠ {{ $daysLeft }} day(s)</span>
                        @else
                            <span>{{ $daysLeft }} day(s)</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex; gap:0.5rem; align-items:center;">

                            {{-- Restore trigger --}}
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

                            {{-- Permanent Delete trigger --}}
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
                    <td colspan="6" style="text-align:center; color:rgba(0,0,0,0.4); padding:2rem 0;">
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
    <div style="font-size:2rem; margin-bottom:0.75rem;">↩</div>
    <div style="font-weight:700; font-size:1rem; margin-bottom:0.35rem;">Restore User?</div>
    <div id="restoreConfirmName"
         style="font-size:0.85rem; opacity:0.85; margin-bottom:0.4rem; font-weight:500;">
    </div>
    <div style="font-size:0.78rem; opacity:0.65; margin-bottom:1.4rem;">
        This user will be restored and moved back to the active users list.
    </div>
    <div style="display:flex; gap:0.6rem; justify-content:center; width:100%;margin-bottom:1.30rem;">
        <button
            class="btn btn--sm"
            style="background:rgba(255,255,255,0.15); color:#fff; border:1px solid rgba(255,255,255,0.35);"
            onclick="closeRestoreConfirm()"
        >
            Cancel
        </button>
        <button
            class="btn btn--sm"
            style="background:#1a8a4a; color:#fff; border:none;"
            onclick="submitRestoreForm()"
        >
            Yes, Restore
        </button>
    </div>
</div>

{{-- Shared hidden restore form --}}
<form method="POST" id="restoreForm" style="display:none;">
    @csrf
</form>

{{-- Restore Backdrop --}}
<div
    id="restoreBackdrop"
    onclick="closeRestoreConfirm()"
    style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:9997;"
></div>

{{-- ═══════════════════════════════════════
     PERMANENT DELETE CONFIRM MODAL
════════════════════════════════════════ --}}
<div class="alert-confirm" id="deleteConfirm">
    <div style="font-size:2rem; margin-bottom:0.75rem;">🗑</div>
    <div style="font-weight:700; font-size:1rem; margin-bottom:0.35rem;">Permanently Delete?</div>
    <div id="deleteConfirmName"
         style="font-size:0.85rem; opacity:0.85; margin-bottom:0.4rem; font-weight:500;">
    </div>
    <div style="font-size:0.78rem; opacity:0.65; margin-bottom:1.4rem;">
        This action <strong>cannot</strong> be undone.
    </div>
    <div style="display:flex; gap:0.6rem; justify-content:center; width:100%;margin-bottom:1.30rem;">
        <button
            class="btn btn--sm"
            style="background:rgba(255,255,255,0.15); color:#fff; border:1px solid rgba(255,255,255,0.35);"
            onclick="closeDeleteConfirm()"
        >
            Cancel
        </button>
        <button
            class="btn btn--sm"
            style="background:#e74c3c; color:#fff; border:none;"
            onclick="submitDeleteForm()"
        >
            Yes, Delete
        </button>
    </div>
</div>

{{-- Shared hidden delete form --}}
<form method="POST" id="deleteForm" style="display:none;">
    @csrf
    @method('DELETE')
</form>

{{-- Delete Backdrop --}}
<div
    id="deleteBackdrop"
    onclick="closeDeleteConfirm()"
    style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:9997;"
></div>

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
