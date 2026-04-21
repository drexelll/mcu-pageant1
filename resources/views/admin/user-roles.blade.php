@extends('layouts.appLayout')

@section('title', 'User Roles')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/userRoles.css') }}">
@endpush

@section('content')

{{-- Flash Messages --}}
{{-- @if(session('success'))
    <div class="alert-success" id="flashMessage">
        <span class="checkmark">✔</span>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert-error" id="flashMessage">
        <span>✖</span>
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="alert-error" id="flashMessage">
        <span>✖</span>
        <ul style="margin:0; padding-left:1rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif --}}

<div class="page-header">
    <div>
        <div class="page-label">Admin Management</div>
        <h1 class="page-title">User Roles</h1>
        <div class="gold-line"></div>
    </div>
    {{-- ✅ Fixed: merged both button groups here, removed the duplicate div below --}}
    <div style="display:flex; gap:0.5rem; align-items:center;">
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
                <td><span class="badge badge--{{ $user->role }}">{{ ucfirst($user->role) }}</span></td>
                <td><span class="badge badge--{{ $user->status }}">{{ ucfirst($user->status) }}</span></td>
                <td style="display:flex; gap:0.4rem;">
                    {{-- Edit button --}}
                    <button class="btn btn--sm btn--outline"
                        onclick="openEditModal('{{ $user->id }}','{{ $user->name }}','{{ $user->email }}','{{ $user->role }}','{{ $user->status }}')">
                        Edit
                    </button>

                    {{-- Delete button (soft-delete / archive) --}}
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                          onsubmit="return confirm('Archive this user?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn--sm btn--danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

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
                @forelse($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="td-strong">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge badge--{{ $user->role }}">{{ ucfirst($user->role) }}</span></td>
                        <td><span class="badge badge--{{ $user->status }}">{{ ucfirst($user->status) }}</span></td>
                        <td>
                            <button class="btn btn--sm btn--outline"
                                onclick="openEditModal('{{ $user->id }}','{{ $user->name }}','{{ $user->email }}','{{ $user->role }}','{{ $user->status }}')">
                                Edit
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:1rem; color:rgba(0,0,0,0.35);">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

        {{-- No results message --}}
        <div id="noResults"
            style="display:none; text-align:center; padding: 2rem; color: rgba(0,0,0,0.35); font-size: 0.875rem;">
            No users found matching your search.
        </div>


    </div>
    {{-- Bottom Bar --}}
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
                <a href="{{ $pageUrl }}" class="pg-btn {{ $page === $currentPage ? 'pg-btn--active' : '' }}">
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
<script src="{{ asset('js/admin_userroles.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if($errors->any())
            openAddModal();
        @endif
    });
</script>
@endpush

    {{-- JS Scripts --}}
    @push('scripts')
        <script src="{{ asset('js/admin_userroles.js') }}"></script>
    @endpush
@endsection
