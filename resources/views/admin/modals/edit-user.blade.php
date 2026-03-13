<!-- Edit User Modal -->
<div class="modal-overlay" id="editModal" style="display:none;">
    <div class="modal">
        <div class="modal-header">
            <h3>Edit User</h3>
            <button class="modal-close" onclick="closeEditModal()">&times;</button>
        </div>

        <form method="POST" action="" id="editForm">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" id="edit-name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="edit-email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <select name="role" id="edit-role" class="form-control">
                        <option value="guest">Guest</option>
                        <option value="admin">Admin</option>
                        <option value="judge">Judge</option>
                        <option value="tabulator">Tabulator</option>
                        <option value="sas">SAS</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="edit-status" class="form-control">
                        <option value="active">Active</option>
                        <option value="pending">Pending</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn--outline" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="btn btn--primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>
