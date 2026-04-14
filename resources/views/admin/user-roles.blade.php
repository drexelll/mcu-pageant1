@extends('layouts.appLayout')

@section('title', 'User Roles')

@section('content')

    <div class="page-header">
        <div>
            <div class="page-label">Admin Management</div>
            <h1 class="page-title">User Roles</h1>
            <div class="gold-line"></div>
        </div>
        <button class="btn btn--gold" onclick="openAddModal()">+ Add User</button>
    </div>

    {{-- Role Summary --}}
    <div class="role-summary">
        @foreach ($roleCounts as $role => $count)
            <div class="role-pill">
                <span class="role-pill-icon">
                    @switch($role)
                        @case('admin')
                            🛡️
                        @break

                        @case('judge')
                            👨‍⚖️
                        @break

                        @case('tabulator')
                            📋
                        @break

                        @case('sas')
                            ⚙️
                        @break

                        @default
                            👁️
                    @endswitch
                </span>
                <div>
                    <div class="role-pill-count">{{ $count }}</div>
                    <div class="role-pill-label">{{ ucfirst($role) }}</div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Users Table --}}
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">All Users</h2>
        </div>

        {{-- Search Bar --}}
        <div class="search-wrap">
            <span class="search-icon">🔍</span>
            <input type="text" id="userSearch" class="search-input" placeholder="Search name, email or role…"
                autocomplete="off">
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

    @include('admin.modals.add-user')
    @include('admin.modals.edit-user')

    {{-- JS Scripts --}}
    @push('scripts')
        <script src="{{ asset('js/admin_userroles.js') }}"></script>
    @endpush

@endsection
