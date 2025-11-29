@props(['projectId'])

<!-- Action Buttons -->
<div class="mb-3 d-flex justify-content-between gap-2">
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-success d-flex align-items-center" data-bs-toggle="modal"
            data-bs-target="#importWeeklyMeetingModal">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-upload">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                <path d="M7 9l5 -5l5 5" />
                <path d="M12 4l0 12" />
            </svg>
            <span>Import Weekly Meeting</span>
        </button>

        <a type="button" class="btn btn-success d-flex align-items-center"
            href="{{ route('project-weekly-meetings.export', $projectId) }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-download">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                <path d="M7 11l5 5l5 -5" />
                <path d="M12 4l0 12" />
            </svg>
            <span>Export Weekly Meeting</span>
        </a>
    </div>

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
        url="{{ route('project-weekly-meetings.datatable', $projectId) }}" :columns="['task', 'petugas', 'start_date', 'end_date', 'target_date', 'progress', 'notes', 'action']" />
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
                            <select name="petugas" id="petugas" class="form-control" required>
                                <option value="">Select person</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted">Start Date</label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted">Complete Date</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted">Target Complete Date</label>
                            <input type="date" name="target_date" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted">Progress (%)</label>
                            <input type="number" name="progress" class="form-control" min="0"
                                max="100" placeholder="Enter progress (0-100)" required>
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
                            <select name="petugas" id="edit_petugas" class="form-control" required>
                                <option value="">Select person</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted">Start Date</label>
                            <input type="date" name="start_date" id="edit_start_date" class="form-control"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted">Complete Date</label>
                            <input type="date" name="end_date" id="edit_end_date" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted">Target Complete Date</label>
                            <input type="date" name="target_date" id="edit_target_date" class="form-control"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted">Progress (%)</label>
                            <input type="number" name="progress" id="edit_progress" class="form-control"
                                min="0" max="100" placeholder="Enter progress (0-100)" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label text-muted">Catatan</label>
                            <textarea name="notes" id="edit_notes" class="form-control" placeholder="Catatan tambahan" rows="3"></textarea>
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

<!-- Import Weekly Meeting Modal -->
<div class="modal modal-blur fade" id="importWeeklyMeetingModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-header">
                <h5 class="modal-title">Import Weekly Meeting Tasks</h5>
            </div>

            <form action="{{ route('project-weekly-meetings.import', $projectId) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Select Excel File</label>
                        <input type="file" name="file" id="import_file" class="form-control"
                            accept=".xlsx,.xls" required>
                        <div class="form-text">Upload an Excel file (.xlsx or .xls) with weekly meeting tasks</div>
                    </div>

                    <div class="mb-3">
                        <a href="{{ asset('template/Template Weekly Report.xlsx') }}" 
                           class="btn btn-outline-primary w-100" download>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon me-1">
                                <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                <path d="M7 11l5 5l5 -5" />
                                <path d="M12 4l0 12" />
                            </svg>
                            Download Template
                        </a>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon me-1">
                            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                            <path d="M12 17v-6" />
                            <path d="M9.5 11.5l2.5 -2.5l2.5 2.5" />
                        </svg>
                        Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let select2Initialized = false;

        // Initialize Select2 for Person in Charge dropdowns
        function initializeSelect2() {
            if (select2Initialized) return;

            // Destroy existing select2 if any
            if ($('#petugas').hasClass('select2-hidden-accessible')) {
                $('#petugas').select2('destroy');
            }
            if ($('#edit_petugas').hasClass('select2-hidden-accessible')) {
                $('#edit_petugas').select2('destroy');
            }

            $('#petugas').select2({
                theme: 'bootstrap-5',
                placeholder: 'Select person',
                allowClear: true,
                tags: true,
                dropdownParent: $('#weeklyMeetingModal'),
                width: '100%'
            });

            $('#edit_petugas').select2({
                theme: 'bootstrap-5',
                placeholder: 'Select person',
                allowClear: true,
                tags: true,
                dropdownParent: $('#editWeeklyMeetingModal'),
                width: '100%'
            });

            select2Initialized = true;
        }

        // Load users for dropdown
        function loadUsers() {
            $.ajax({
                url: '/project-weekly-meetings/users',
                method: 'GET',
                success: function(response) {
                    if (response.success && response.data) {
                        const options = response.data.map(user =>
                            `<option value="${user.name}">${user.name}</option>`
                        ).join('');

                        $('#petugas').html('<option value="">Select person</option>' + options);
                        $('#edit_petugas').html('<option value="">Select person</option>' +
                            options);
                    }
                },
                error: function(xhr) {
                    console.error('Error loading users:', xhr);
                }
            });
        }

        // Load users on page load
        loadUsers();

        // Initialize Select2 when modal is about to be shown
        $('#weeklyMeetingModal').on('show.bs.modal', function() {
            setTimeout(function() {
                initializeSelect2();
            }, 100);
        });

        $('#editWeeklyMeetingModal').on('show.bs.modal', function() {
            setTimeout(function() {
                initializeSelect2();
            }, 100);
        });

        // Reset Select2 when modal is closed
        $('#weeklyMeetingModal').on('hidden.bs.modal', function() {
            if ($('#petugas').hasClass('select2-hidden-accessible')) {
                $('#petugas').val('').trigger('change');
            }
            $('#weeklyMeetingForm')[0].reset();
        });

        $('#editWeeklyMeetingModal').on('hidden.bs.modal', function() {
            $('#editWeeklyMeetingForm')[0].reset();
            if ($('#edit_petugas').hasClass('select2-hidden-accessible')) {
                $('#edit_petugas').val('').trigger('change');
            }
        });

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

                    // Handle person in charge - add as option if not exists
                    const petugasValue = response.data.petugas;
                    if (petugasValue && $('#edit_petugas').find(
                            `option[value="${petugasValue}"]`).length === 0) {
                        // Create new option if it doesn't exist (freetext value)
                        const newOption = new Option(petugasValue, petugasValue, true,
                            true);
                        $('#edit_petugas').append(newOption);
                    }
                    $('#edit_petugas').val(petugasValue).trigger('change');

                    $('#edit_start_date').val(response.data.start_date);
                    $('#edit_end_date').val(response.data.end_date);
                    $('#edit_target_date').val(response.data.target_date);
                    $('#edit_progress').val(response.data.progress);
                    $('#edit_notes').val(response.data.notes);

                    // Show modal
                    $('#editWeeklyMeetingModal').modal('show');
                },
                error: function(xhr) {
                    alert('Error fetching task data: ' + (xhr.responseJSON?.message ||
                        'Unknown error'));
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


                    },
                    error: function(xhr) {
                        alert('Error deleting task: ' + (xhr.responseJSON?.message ||
                            'Unknown error'));
                    }
                });
            }
        });
    });
</script>
