<div class="modal-overlay" id="assignModal" style="display:none;">

    <div class="modal">

        <div class="modal-header">

            <h3 id="assignModalTitle">Assign</h3>
            <button class="modal-close" onclick="closeAssignModal()">&times;</button>

        </div>

        <form method="POST" id="assignForm">
            @csrf
            @method('PUT') {{-- keep this since your route is PUT --}}

            <div class="modal-body" id="assignModalBody">
                <!-- checkboxes injected here -->
            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn--outline" onclick="closeAssignModal()">Cancel</button>
                <button type="submit" class="btn btn--primary">Assign Selected</button>

            </div>

        </form>

    </div>

</div>
