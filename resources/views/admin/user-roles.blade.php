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
    @foreach($roleCounts as $role => $count)
        <div class="role-pill">
            <span class="role-pill-icon">
                @switch($role)
                    @case('admin') 🛡️ @break
                    @case('judge') 👨‍⚖️ @break
                    @case('tabulator') 📋 @break
                    @case('sas') ⚙️ @break
                    @default 👁️
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
            <input
                type="text"
                id="userSearch"
                class="search-input"
                placeholder="Search name, email or role…"
                autocomplete="off"
            >
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
            @endforeach
        </tbody>
    </table>

    {{-- No results message --}}
    <div id="noResults" style="display:none; text-align:center; padding: 2rem; color: rgba(0,0,0,0.35); font-size: 0.875rem;">
        No users found matching your search.
    </div>
</div>

{{-- Include modal partials --}}
@include('admin.modals.add-user')
@include('admin.modals.edit-user')

{{-- JS Scripts --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Search ──────────────────────────────────────
const searchInput = document.getElementById('userSearch');
const tableBody   = document.getElementById('userTableBody');
const noResults   = document.getElementById('noResults');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase().trim();
        const rows  = tableBody.querySelectorAll('tr');
        let visibleCount = 0;

        rows.forEach(row => {
            const name  = row.cells[1]?.textContent.toLowerCase() ?? '';
            const email = row.cells[2]?.textContent.toLowerCase() ?? '';
            const role  = row.cells[3]?.textContent.toLowerCase() ?? '';

            const matches = !query || name.includes(query) || email.includes(query) || role.includes(query);
            row.style.display = matches ? '' : 'none';
            if (matches) visibleCount++;
        });

        noResults.style.display = visibleCount === 0 ? 'block' : 'none';
        });
        } else
        {console.error('Search input #userSearch not found!');}

    // ── Modals ──────────────────────────────────────
    function openEditModal(id, name, email, role, status) {
        document.getElementById('edit-name').value = name;
        document.getElementById('edit-email').value = email;
        document.getElementById('edit-role').value = role;
        document.getElementById('edit-status').value = status;
        document.getElementById('editForm').action = `/users/${id}`;
        document.getElementById('editModal').style.display = 'flex';
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    function openAddModal() {
        document.getElementById('addModal').style.display = 'flex';
    }

    function closeAddModal() {
        document.getElementById('addModal').style.display = 'none';
    }

    document.getElementById('editModal').addEventListener('click', function(e){
        if(e.target === this) closeEditModal();
    });
    document.getElementById('addModal').addEventListener('click', function(e){
        if(e.target === this) closeAddModal();
    });

    // Make functions globally accessible
    window.openEditModal = openEditModal;
    window.closeEditModal = closeEditModal;
    window.openAddModal = openAddModal;
    window.closeAddModal = closeAddModal;

});
</script>
@endpush

@endsection
