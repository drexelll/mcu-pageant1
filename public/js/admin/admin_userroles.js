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
    const activeRole   = document.querySelector('#roleFilters .filter-pill.active')?.dataset.value ?? null;
    const activeStatus = document.querySelector('#statusFilters .filter-pill.active')?.dataset.value ?? null;

    const rows = document.querySelectorAll('#userTableBody tr');
    let visibleCount = 0;

    rows.forEach(row => {
        const role   = row.dataset.role;
        const status = row.dataset.status;

        const matchesRole   = !activeRole   || role   === activeRole;
        const matchesStatus = !activeStatus || status === activeStatus;

        const visible = matchesRole && matchesStatus;
        row.style.display = visible ? '' : 'none';
        if (visible) visibleCount++;
    });

    document.getElementById('noResults').style.display = visibleCount === 0 ? 'block' : 'none';
}

function clearFilters() {
    document.querySelectorAll('.filter-pill.active').forEach(p => p.classList.remove('active'));
    const searchInput = document.getElementById('userSearch');
    if (searchInput) searchInput.value = '';
    document.getElementById('searchForm').submit();
}

// ── Modals ──────────────────────────────────────────────────────
function openEditModal(id, name, email, role, status) {
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-email').value = email;
    document.getElementById('edit-role').value = role;
    document.getElementById('edit-status').value = status;
    document.getElementById('editForm').action = `/admin/users/${id}`;
    document.getElementById('deleteForm').action = `/admin/users/${id}`;
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

// ── Delete Confirmation ─────────────────────────────────────────
function openDeleteConfirm() {
    document.getElementById('deleteConfirmModal').style.display = 'flex';
}

function closeDeleteConfirm() {
    document.getElementById('deleteConfirmModal').style.display = 'none';
}

function submitDelete() {
    document.getElementById('deleteForm').submit();
}

// ── Password Toggle ─────────────────────────────────────────────
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

// ── Validation ──────────────────────────────────────────────────
function validatePassword() {
    const password = document.getElementById('password');
    const errorSpan = document.getElementById('password-error');
    if (!password || !errorSpan) return;

    if (password.value.length < 8) {
        errorSpan.textContent = "Password must be at least 8 characters.";
    } else if (!/[A-Z]/.test(password.value)) {
        errorSpan.textContent = "Password must contain at least one uppercase letter.";
    } else if (!/[0-9]/.test(password.value)) {
        errorSpan.textContent = "Password must contain at least one number.";
    } else {
        errorSpan.textContent = "";
    }
}

function validateConfirmPassword() {
    const password = document.getElementById('password');
    const confirm  = document.getElementById('password_confirmation');
    const errorSpan = document.getElementById('confirm-error');
    if (!password || !confirm || !errorSpan) return;

    errorSpan.textContent = confirm.value !== password.value ? "Passwords do not match." : "";
}

// ── DOMContentLoaded ────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {

   // ── Prevent form submit on Enter ────────────────────────────────
const searchForm = document.getElementById('searchForm');
if (searchForm) {
    searchForm.addEventListener('submit', e => e.preventDefault());
}

// ── Live Search (fetch, no reload) ──────────────────────────────
const searchInput = document.getElementById('userSearch');
if (searchInput) {
    let debounceTimer;

    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            const search  = searchInput.value.trim();
            const perPage = document.querySelector('[name="per_page"]')?.value ?? 10;

            const url = new URL(window.location.href);
            url.searchParams.set('search', search);
            url.searchParams.set('per_page', perPage);
            url.searchParams.set('page', 1);
            window.history.replaceState({}, '', url);

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc    = parser.parseFromString(html, 'text/html');

                    const newBody = doc.getElementById('userTableBody');
                    if (newBody) document.getElementById('userTableBody').innerHTML = newBody.innerHTML;

                    const newPagination = doc.querySelector('.table-bottom-bar');
                    if (newPagination) document.querySelector('.table-bottom-bar').innerHTML = newPagination.innerHTML;

                    const visibleRows = document.querySelectorAll('#userTableBody tr');
                    document.getElementById('noResults').style.display =
                        visibleRows.length === 0 ? 'block' : 'none';

                    // ✅ Keep focus so user can keep typing
                    searchInput.focus();
                });
        }, 300);
    });
}

    // ── Success Alert auto-hide ─────────────────────────────────
    const alertBox = document.querySelector('.success-alert');
    if (alertBox) {
        alertBox.style.opacity = '1';
        alertBox.style.transform = 'translateY(0)';

        setTimeout(() => {
            alertBox.style.opacity = '0';
            alertBox.style.transform = 'translateY(-10px)';
        }, 2500);

        setTimeout(() => alertBox.remove(), 3000);
    }

});
