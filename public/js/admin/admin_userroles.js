// ── Lines per page ──────────────────────────────────────────────
function changePerPage(value) {
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', value);
    url.searchParams.set('page', 1);
    window.location.href = url.toString();
}

// ── Filter panel toggle ─────────────────────────────────────────
function toggleFilterPanel() {
    const panel = document.getElementById('filterPanel');
    panel.style.display = panel.style.display === 'none' ? 'flex' : 'none';
}

// ── Filter pills ────────────────────────────────────────────────
const activeFilters = { role: [], status: [] };

function toggleFilterPill(btn) {
    const filterGroup = btn.closest('.filter-pills');
    const allPills = filterGroup.querySelectorAll('.filter-pill');

    if (btn.classList.contains('active')) {
        btn.classList.remove('active');
    } else {
        allPills.forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
    }
    applyFilters();
}

function applyFilters() {
    const search = document.getElementById('userSearch').addEventListener('input', applyFilters);

    // Single selected value per group (or null if none selected)
    const activeRole   = document.querySelector('#roleFilters .filter-pill.active')?.dataset.value ?? null;
    const activeStatus = document.querySelector('#statusFilters .filter-pill.active')?.dataset.value ?? null;

    const rows = document.querySelectorAll('#userTableBody tr');
    let visibleCount = 0;

    rows.forEach(row => {
        const name   = row.querySelector('.td-strong')?.textContent.toLowerCase() ?? '';
        const email  = row.cells[2]?.textContent.toLowerCase() ?? '';
        const role   = row.dataset.role;
        const status = row.dataset.status;

        const matchesSearch = !search || name.includes(search) || email.includes(search) || role.includes(search);
        const matchesRole   = !activeRole   || role   === activeRole;
        const matchesStatus = !activeStatus || status === activeStatus;

        const visible = matchesSearch && matchesRole && matchesStatus;
        row.style.display = visible ? '' : 'none';
        if (visible) visibleCount++;
    });

    document.getElementById('noResults').style.display = visibleCount === 0 ? 'block' : 'none';
}

function clearFilters() {
    document.querySelectorAll('.filter-pill.active').forEach(p => p.classList.remove('active'));
    document.getElementById('userSearch').value = '';
    applyFilters();
}

// ── Modals ──────────────────────────────────────────────────────
function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

function openAddModal() {
    document.getElementById('addModal').style.display = 'flex';
}

function closeAddModal() {
    document.getElementById('addModal').style.display = 'none';
}

// ── DOM Ready ────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {

    // Search
    const searchInput = document.getElementById('userSearch');
    const tableBody   = document.getElementById('userTableBody');
    const noResults   = document.getElementById('noResults');

    if (!searchInput) {
        console.error('Search input #userSearch not found!');
        return;
    }

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

    // Modal backdrops
    document.getElementById('editModal').addEventListener('click', function (e) {
        if (e.target === this) closeEditModal();
    });
    document.getElementById('addModal').addEventListener('click', function (e) {
        if (e.target === this) closeAddModal();
    });

});

// ── Delete Confirmation ──────────────────────────────────────────
function openDeleteConfirm() {
    document.getElementById('deleteConfirmModal').style.display = 'flex';
}

function closeDeleteConfirm() {
    document.getElementById('deleteConfirmModal').style.display = 'none';
}

function submitDelete() {
    document.getElementById('deleteForm').submit();
}

// Update openEditModal to also set the delete form action
function openEditModal(id, name, email, role, status) {
    document.getElementById('edit-name').value   = name;
    document.getElementById('edit-email').value  = email;
    document.getElementById('edit-role').value   = role;
    document.getElementById('edit-status').value = status;
    document.getElementById('editForm').action   = `/admin/users/${id}`;
    document.getElementById('deleteForm').action = `/admin/users/${id}`; // ← add this
    document.getElementById('editModal').style.display = 'flex';
}

function togglePassword(fieldId, clickedIcon) {
    const field = document.getElementById(fieldId);
    const wrapper = clickedIcon.parentElement;
    const eyeClosed = wrapper.querySelector('.eye-closed');
    const eyeOpen = wrapper.querySelector('.eye-open');


    if (field.type === "password") {
        field.type = "text";
        eyeOpen.style.display = "inline";
        eyeClosed.style.display = "none";
    } else {
        field.type = "password";
        eyeOpen.style.display = "none";
        eyeClosed.style.display = "inline";
    }
}

function validatePassword() {
    const password = document.getElementById('password').value;
    const errorSpan = document.getElementById('password-error');

    if (password.length < 8) {
        errorSpan.textContent = "Password must be at least 8 characters.";
    } else if (!/[A-Z]/.test(password)) {
        errorSpan.textContent = "Password must contain at least one uppercase letter.";
    } else if (!/[0-9]/.test(password)) {
        errorSpan.textContent = "Password must contain at least one number.";
    } else {
        errorSpan.textContent = "";
    }
}

function validateConfirmPassword() {
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('password_confirmation').value;
    const errorSpan = document.getElementById('confirm-error');

    if (confirm !== password) {
        errorSpan.textContent = "Passwords do not match.";
    } else {
        errorSpan.textContent = "";
    }
}

// Attach events
document.getElementById('password').addEventListener('blur', validatePassword);
document.getElementById('password_confirmation').addEventListener('blur', validateConfirmPassword);

