@props(['project', 'categories'])

<div class="modal fade" id="addTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="addTaskForm" method="POST" action="{{ route('project.wbs-items.store', $project) }}">
                @csrf
                <input type="hidden" name="item_type" value="task">
                <div class="modal-header">
                    <h5 class="modal-title">Add Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" id="addTaskTitle" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="note" id="addTaskNote" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Parent category (optional)</label>
                        <select name="parent_id" id="addTaskParent" class="form-select" readonly>
                            <option value="">None (standalone task)</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Task</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addTaskModalEl = document.getElementById('addTaskModal');
            if (!addTaskModalEl) return;

            const addTaskModal = new bootstrap.Modal(addTaskModalEl);
            const titleInput = document.getElementById('addTaskTitle');
            const noteInput = document.getElementById('addTaskNote');
            const parentSelect = document.getElementById('addTaskParent');

            document.querySelectorAll('.open-add-task-modal').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const catId = btn.getAttribute('data-cat-id') || '';
                    const catTitle = btn.getAttribute('data-cat-title') || '';

                    if (titleInput) titleInput.value = '';
                    if (noteInput) noteInput.value = '';

                    if (parentSelect) {
                        parentSelect.value = catId;
                        if (catId && titleInput) {
                            titleInput.placeholder = `Add task to ${catTitle}`;
                        } else if (titleInput) {
                            titleInput.placeholder = '';
                        }
                    }

                    addTaskModal.show();
                });
            });
        });
    </script>
@endpush
