document.addEventListener('DOMContentLoaded', () => {
    // events-create
    const saveBtn = document.getElementById('saveEventBtn');
    if (saveBtn) {
        saveBtn.addEventListener('click', function () {
            this.disabled = true;
            this.form.submit();
        });
    }

    // events-edit
    const deleteBtn = document.getElementById('deleteBtn');
    const deleteForm = document.getElementById('deleteForm');
    const modal = document.getElementById('deleteModal');
    const confirmBtn = document.getElementById('confirmDelete');
    const cancelBtn = document.getElementById('cancelDelete');

    if (deleteBtn && deleteForm && modal) {
        deleteBtn.addEventListener('click', (e) => {
            e.preventDefault();
            modal.style.display = 'flex'; // show modal
        });
    }

    if (confirmBtn && deleteForm) {
        confirmBtn.addEventListener('click', () => {
            deleteForm.submit(); // submit delete form
        });
    }

    if (cancelBtn && modal) {
        cancelBtn.addEventListener('click', () => {
            modal.style.display = 'none'; // hide modal
        });
    }

    function openAssignModal(type) {
    const modal = document.getElementById('assignModal');
    const title = document.getElementById('assignModalTitle');
    const body = document.getElementById('assignModalBody');
    const form = document.getElementById('assignForm');

    modal.style.display = 'flex';

    if (type === 'judge') {
        title.textContent = 'Assign Judges';
        body.innerHTML = judges.map(j => `
            <label class="checkbox-item">
                <input type="checkbox" name="judges[]" value="${j.id}"
                    ${assignedJudges.includes(j.id) ? 'checked' : ''}>
                ${j.name}
            </label>
        `).join('');
        form.action = `/admin/events/${eventId}/assign/judge`;
    }

    else if (type === 'sas') {
        title.textContent = 'Assign SAS';
        body.innerHTML = sas.map(s => `
            <label class="checkbox-item">
                <input type="checkbox" name="sas[]" value="${s.id}"
                    ${assignedSas.includes(s.id) ? 'checked' : ''}>
                ${s.name}
            </label>
        `).join('');
        form.action = `/admin/events/${eventId}/assign/sas`;
    }

    else if (type === 'contestant') {
        title.textContent = 'Assign Contestants';
        body.innerHTML = contestants.map(c => `
            <label class="checkbox-item">
                <input type="checkbox" name="contestants[]" value="${c.id}"
                    ${assignedContestants.includes(c.id) ? 'checked' : ''}>
                #${c.number} — ${c.name} (${c.course})
            </label>
        `).join('');
        form.action = `/admin/events/${eventId}/assign/contestant`;
    }
}


    function closeAssignModal() {
        document.getElementById('assignModal').style.display = 'none';
    }

    document.getElementById('assignModal').addEventListener('click', function (e) {
        if (e.target === this) closeAssignModal();
    });

    function removeAssigned(id, type) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/events/${eventId}/unassign/${type}/${id}`;

        // CSRF token
        const token = document.createElement('input');
        token.type = 'hidden';
        token.name = '_token';
        token.value = document.querySelector('meta[name="csrf-token"]').content;
        form.appendChild(token);

        // Spoof DELETE method
        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';
        form.appendChild(method);

        document.body.appendChild(form);
        form.submit();
    }

    // Make functions globally accessible
    window.openAssignModal = openAssignModal;
    window.closeAssignModal = closeAssignModal;
    window.removeAssigned = removeAssigned;

});
