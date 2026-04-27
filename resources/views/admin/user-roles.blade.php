@extends('layouts.appLayout')

@section('title', 'User Roles')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/userRoles.css') }}">
@endpush

@section('content')

@if(session('success'))
    <div id="successAlert" class="success-alert">
        ✔ {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div id="errorAlert" class="alert-error">
        ⚠ {{ session('error') }}
    </div>
@endif

<div class="page-header">
    <div>
        <div class="page-label">Admin Management</div>
        <h1 class="page-title">User Roles</h1>
        <div class="gold-line"></div>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.user-archive') }}" class="btn btn--outline">🗄 Archive</a>
        <button class="btn btn--gold" onclick="openAddModal()">+ Add User</button>
    </div>
</div>

{{-- Top Bar --}}
<div class="table-top-bar">
    <form method="GET" action="{{ route('admin.user-roles') }}" id="searchForm" style="display:contents;">
        <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
        <div class="search-wrap">
            <span class="search-icon">🔍</span>
            <input
                type="text"
                name="search"
                id="userSearch"
                class="search-input"
                placeholder="Search name, email or role…"
                autocomplete="off"
                value="{{ request('search') }}"
            >
        </div>
    </form>

    <div class="table-top-right">
        <div class="lpp-wrap">
            <span class="lpp-label">Lines per page</span>
            <div class="lpp-select-wrap">
                <select id="lppSelect" class="lpp-select" onchange="changePerPage(this.value)">
                    @foreach([10, 25, 50, 100] as $n)
                        <option value="{{ $n }}" {{ request('per_page', 10) == $n ? 'selected' : '' }}>
                            {{ $n }}
                        </option>
                    @endforeach
                </select>
                <span class="lpp-chevron">▾</span>
            </div>
        </div>

        <button class="btn-filters" onclick="toggleFilterPanel()">
            <svg width="14" height="11" viewBox="0 0 14 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect y="0" width="14" height="1.8" rx="0.9" fill="currentColor"/>
                <rect x="2" y="4.6" width="10" height="1.8" rx="0.9" fill="currentColor"/>
                <rect x="4" y="9.2" width="6" height="1.8" rx="0.9" fill="currentColor"/>
            </svg>
            Filters
        </button>
    </div>
</div>

{{-- Filter Panel --}}
<div id="filterPanel" class="filter-panel" style="display:none;">
    <div class="filter-group">
        <label class="filter-label">Role</label>
        <div class="filter-pills" id="roleFilters">
            @foreach(['sas','admin','tabulator','guest','judge'] as $r)
                <button class="filter-pill" data-filter="role" data-value="{{ $r }}" onclick="toggleFilterPill(this)">
                    {{ ucfirst($r) }}
                </button>
            @endforeach
        </div>
    </div>
    <div class="filter-group">
        <label class="filter-label">Status</label>
        <div class="filter-pills" id="statusFilters">
            @foreach(['active','inactive','pending'] as $s)
                <button class="filter-pill" data-filter="status" data-value="{{ $s }}" onclick="toggleFilterPill(this)">
                    {{ ucfirst($s) }}
                </button>
            @endforeach
        </div>
    </div>
    <button class="filter-clear" onclick="clearFilters()">Clear filters</button>
</div>

{{-- Table --}}
<table class="tbl">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="userTableBody">
        @foreach($users as $user)
        <tr data-role="{{ $user->role }}" data-status="{{ $user->status }}">
            <td>{{ $users->firstItem() + $loop->index }}</td>
            <td class="td-strong">{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
                <span class="badge badge--{{ $user->role }}">{{ ucfirst($user->role) }}</span>
            </td>
            <td>
                <span class="badge badge--{{ $user->status }}">{{ ucfirst($user->status) }}</span>
            </td>
            <td>
                <div class="tbl-actions">
                    <button
                        class="btn btn--sm btn--outline"
                        onclick="openEditModal(
                            '{{ $user->id }}',
                            '{{ addslashes($user->name) }}',
                            '{{ addslashes($user->email) }}',
                            '{{ $user->role }}',
                            '{{ $user->status }}'
                        )"
                    >
                        ✏ Edit
                    </button>

                    <button
                        type="button"
                        class="btn btn--sm btn--danger"
                        onclick="showArchiveConfirm(
                            '{{ route('admin.users.destroy', $user->id) }}',
                            '{{ addslashes($user->name) }}'
                        )"
                    >
                        🗑 Delete
                    </button>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- No results --}}
<div id="noResults" class="no-results">
    No users found matching your search.
</div>

{{-- Bottom Bar / Pagination --}}
<div class="table-bottom-bar">
    <span class="showing-text">
        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
    </span>
    <div class="pagination-wrap">
        @if($users->onFirstPage())
            <span class="pg-btn pg-btn--disabled">&#8249;</span>
        @else
            <a href="{{ $users->previousPageUrl() }}&per_page={{ request('per_page', 10) }}" class="pg-btn">&#8249;</a>
        @endif

        @php
            $currentPage = $users->currentPage();
            $lastPage    = $users->lastPage();
            $range = collect(range(1, $lastPage))->filter(fn($p) =>
                $p === 1 || $p === $lastPage || abs($p - $currentPage) <= 1
            );
            $prev = null;
        @endphp

        @foreach($range as $page)
            @if($prev !== null && $page - $prev > 1)
                <span class="pg-dots">…</span>
            @endif
            @php $pageUrl = $users->url($page) . '&per_page=' . request('per_page', 10); @endphp
            <a href="{{ $pageUrl }}"
               class="pg-btn {{ $page === $currentPage ? 'pg-btn--active' : '' }}">
                {{ $page }}
            </a>
            @php $prev = $page; @endphp
        @endforeach

        @if($users->hasMorePages())
            <a href="{{ $users->nextPageUrl() }}&per_page={{ request('per_page', 10) }}" class="pg-btn">&#8250;</a>
        @else
            <span class="pg-btn pg-btn--disabled">&#8250;</span>
        @endif
    </div>
</div>

{{-- ═══════════════════════════════════════
     ARCHIVE CONFIRM MODAL
════════════════════════════════════════ --}}
<div class="archive-confirm" id="archiveConfirm">
    <div class="archive-confirm__icon-wrap">📦</div>
    <div class="archive-confirm__title">Archive User?</div>
    <div class="archive-confirm__name" id="archiveConfirmName"></div>
    <div class="archive-confirm__desc">
        This user will be moved to the archive and permanently deleted after <strong>30 days</strong>.
    </div>
    <div class="archive-confirm__actions">
        <button class="archive-confirm__btn archive-confirm__cancel" onclick="closeArchiveConfirm()">
            Cancel
        </button>
        <button class="archive-confirm__btn archive-confirm__submit" onclick="submitArchiveForm()">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
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

{{-- Modals --}}
@include('admin.modals.add-user')
@include('admin.modals.edit-user')

@push('scripts')
<script src="{{ asset('js/admin/admin_userroles.js') }}"></script>
<script>
    /* ── Flash auto-hide ── */
    document.addEventListener('DOMContentLoaded', function () {

        ['successAlert', 'errorAlert'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                setTimeout(() => {
                    el.style.transition = 'opacity 0.5s ease';
                    el.style.opacity = '0';
                    setTimeout(() => el.remove(), 500);
                }, 3500);
            }
        });

        @if($errors->any())
            openAddModal();
        @endif
    });

    /* ── Archive modal ── */
    function showArchiveConfirm(actionUrl, userName) {
        document.getElementById('archiveForm').action             = actionUrl;
        document.getElementById('archiveConfirmName').textContent = userName;
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

    /* ── Close on Escape ── */
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeArchiveConfirm();
    });
</script>
@endpush

@endsection
