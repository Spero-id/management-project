@props(['projectId'])

<!-- Add Task Button -->
<div class="mb-1 d-flex justify-content-end">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#weeklyMeetingModal">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        Add Task
    </button>
</div>

<!-- Tasks Table -->
<div class="table-responsive">
    <x-datatable id="weekly-meeting-table" title="Projects"
        url="{{ route('project-weekly-meetings.datatable', $projectId) }}" :columns="['task', 'petugas', 'start_date', 'end_date', 'target_date', 'status', 'notes','action']"  />
</div>

<!-- Weekly Meeting Modal (Create) -->
<div class="modal modal-blur fade" id="weeklyMeetingModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-header">
                <h5 class="modal-title">Add Weekly Meeting Task</h5>
            </div>

            <form action="{{ route('project-weekly-meetings.store') }}" method="POST" id="weeklyMeetingForm">
                @csrf
                <input type="hidden" name="project_id" value="{{ $projectId }}">

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Active Task</label>
                            <input type="text" name="task" class="form-control" placeholder="Enter task name"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted">Person in Charge</label>
                            <input type="text" name="petugas" class="form-control" placeholder="Select person"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted">Start Date</label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted">Complete Date</label>
                            <input type="date" name="end_date" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted">Target Complete Date</label>
                            <input type="date" name="target_date" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted">Status</label>
                            <input type="text" name="status" class="form-control"
                                placeholder="e.g., In Progress, Completed, Hold" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label text-muted">Catatan</label>
                            <textarea name="notes" class="form-control" placeholder="Catatan tambahan" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Save Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Weekly Meeting Modal -->
<div class="modal modal-blur fade" id="editWeeklyMeetingModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-header">
                <h5 class="modal-title">Edit Weekly Meeting Task</h5>
            </div>

            <form id="editWeeklyMeetingForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="project_id" value="{{ $projectId }}">
                <input type="hidden" id="edit_meeting_id" name="meeting_id">

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Active Task</label>
                            <input type="text" name="task" id="edit_task" class="form-control" 
                                placeholder="Enter task name" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted">Person in Charge</label>
                            <input type="text" name="petugas" id="edit_petugas" class="form-control" 
                                placeholder="Select person" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted">Start Date</label>
                            <input type="date" name="start_date" id="edit_start_date" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted">Complete Date</label>
                            <input type="date" name="end_date" id="edit_end_date" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted">Target Complete Date</label>
                            <input type="date" name="target_date" id="edit_target_date" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted">Status</label>
                            <input type="text" name="status" id="edit_status" class="form-control"
                                placeholder="e.g., In Progress, Completed, Hold" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label text-muted">Catatan</label>
                            <textarea name="notes" id="edit_notes" class="form-control" 
                                placeholder="Catatan tambahan" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Update Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle edit button click
    $(document).on('click', '.edit-btn', function() {
        const meetingId = $(this).data('id');
        
        // Fetch meeting data
        $.ajax({
            url: `/project-weekly-meetings/${meetingId}`,
            method: 'GET',
            success: function(response) {
                // Populate form fields
                $('#edit_meeting_id').val(response.data.id);
                $('#edit_task').val(response.data.task);
                $('#edit_petugas').val(response.data.petugas);
                $('#edit_start_date').val(response.data.start_date);
                $('#edit_end_date').val(response.data.end_date);
                $('#edit_target_date').val(response.data.target_date);
                $('#edit_status').val(response.data.status);
                $('#edit_notes').val(response.data.notes);
                
                // Show modal
                $('#editWeeklyMeetingModal').modal('show');
            },
            error: function(xhr) {
                alert('Error fetching task data: ' + (xhr.responseJSON?.message || 'Unknown error'));
            }
        });
    });

    // Handle edit form submission
    $('#editWeeklyMeetingForm').on('submit', function(e) {
        e.preventDefault();
        
        const meetingId = $('#edit_meeting_id').val();
        
        $.ajax({
            url: `/project-weekly-meetings/${meetingId}`,
            method: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                $('#editWeeklyMeetingModal').modal('hide');
                $('#editWeeklyMeetingForm')[0].reset();
                $('#weekly-meeting-table').DataTable().ajax.reload();
                
                // Show success message
                if (response.message) {
                    alert(response.message);
                }
            },
            error: function(xhr) {
                let errorMessage = 'Error updating task';
                
                if (xhr.responseJSON?.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON?.errors) {
                    // Display validation errors
                    const errors = Object.values(xhr.responseJSON.errors).flat();
                    errorMessage = errors.join('\n');
                }
                
                alert(errorMessage);
            }
        });
    });

    // Handle delete button click
    $(document).on('click', '.delete-btn', function() {
        const meetingId = $(this).data('id');
        
        if (confirm('Are you sure you want to delete this task?')) {
            $.ajax({
                url: `/project-weekly-meetings/${meetingId}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#weekly-meeting-table').DataTable().ajax.reload();
                    
                    // Show success message
                    if (response.message) {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    alert('Error deleting task: ' + (xhr.responseJSON?.message || 'Unknown error'));
                }
            });
        }
    });
});
</script>
