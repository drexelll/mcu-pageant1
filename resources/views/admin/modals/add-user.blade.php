<!-- Add User Modal -->
<div class="modal-overlay" id="addModal" style="display:none;">
    <div class="modal">
        <div class="modal-header">
            <h3>Add User</h3>
            <button class="modal-close" onclick="closeAddModal()">&times;</button>
        </div>

        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <select name="role" class="form-control">
                        <option value="guest" selected>Guest</option>
                        <option value="admin">Admin</option>
                        <option value="judge">Judge</option>
                        <option value="tabulator">Tabulator</option>
                        <option value="sas">SAS</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn--outline" onclick="closeAddModal()">Cancel</button>
                <button type="submit" class="btn btn--primary">Create User</button>
            </div>
        </form>
    </div>
</div>
