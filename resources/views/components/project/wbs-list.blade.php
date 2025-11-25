@props(['project', 'wbsItems'])

@php
    $categories = $wbsItems->where('item_type', 'category');
    $tasks = $wbsItems->where('item_type', 'task');
    $totalTasks = $tasks->count();
    $completedTasks = $tasks->where('is_done', 1)->count();
    $overallPercent = $totalTasks ? round(($completedTasks / $totalTasks) * 100) : 0;
@endphp

<div class="mb-3 d-flex flex-wrap gap-2">
    <button type="button" class="btn btn-success d-flex align-items-center" data-bs-toggle="modal"
        data-bs-target="#importWbsModal">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="icon icon-tabler icons-tabler-outline icon-tabler-upload">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
            <path d="M7 9l5 -5l5 5" />
            <path d="M12 4l0 12" />
        </svg>
        <span>Import list of work</span>
    </button>

    <button type="button" class="btn btn-success d-flex align-items-center" id="exportWbsBtn">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="icon icon-tabler icons-tabler-outline icon-tabler-download">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
            <path d="M7 11l5 5l5 -5" />
            <path d="M12 4l0 12" />
        </svg>
        <span>Export list of work</span>
    </button>
</div>


<div>
    <div class="mb-3">
        <form action="{{ route('project.wbs-items.store', $project) }}" method="POST"
            class="d-flex flex-column gap-2">
            @csrf
            <input type="hidden" name="item_type" value="category">
            
            <div class="row">
                <div class="col-md-9">
                    <input type="text" name="title" class="form-control" placeholder="New category title"
                        required aria-label="New category title">
                </div>
               <div class="col-md-3">
                    <button class="btn btn-outline-primary w-100" type="submit">Create Category</button>
                </div>
            </div>
        </form>
    </div>

    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <small class="text-muted">Overall Progress</small>
            <strong id="wbsOverallPercent">{{ $overallPercent }}%</strong>
        </div>
        <div class="progress" style="height:10px;">
            <div id="wbsOverallBar" class="progress-bar bg-primary" role="progressbar"
                style="width: {{ $overallPercent }}%;" aria-valuenow="{{ $overallPercent }}" aria-valuemin="0"
                aria-valuemax="100"></div>
        </div>
    </div>


    @if ($categories->isEmpty() && $tasks->isEmpty())
        <div class="text-muted">No items yet.</div>
    @else
        <div class="list-group">
            @foreach ($categories as $cat)
                @php
                    $catChildren = $tasks->where('parent_id', $cat->id);
                    $catTotal = $catChildren->count();
                    $catDone = $catChildren->where('is_done', 1)->count();
                    $catPercent = $catTotal ? round(($catDone / $catTotal) * 100) : 0;
                @endphp
                <div class="list-group-item wbs-cat" data-cat-id="{{ $cat->id }}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <strong class="h2">{{ $cat->title }}</strong>
                           
                            @if ($cat->note)
                                <div class="small text-muted mt-1">{{ $cat->note }}</div>
                            @endif
                        </div>
                        <div class="d-flex align-items-center">
                            {{-- Delete category --}}
                            <form action="{{ route('project.wbs-items.destroy', $cat) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit" title="Delete category">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 7l16 0" />
                                        <path d="M10 11l0 6" />
                                        <path d="M14 11l0 6" />
                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Child tasks --}}
                    @if ($catChildren->isNotEmpty())
                        <div class="mt-2 table-responsive">
                            <table class="table table-hover table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 50px;">Done</th>
                                        <th>Task</th>
                                        <th class="text-center">Type</th>
                                        <th class="text-center">From</th>
                                        <th class="text-center">To</th>
                                        <th class="text-center" style="width: 80px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($catChildren as $child)
                                        <x-project.wbs-task-item :task="$child" />
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    {{-- Add Task button for category --}}
                    <button type="button" class="btn btn-outline-primary mt-4 w-full open-add-task-modal"
                        data-cat-id="{{ $cat->id }}" data-cat-title="{{ e($cat->title) }}">
                        Add Task
                    </button>
                </div>
            @endforeach

            {{-- Standalone tasks (without parent) --}}
            @foreach ($tasks->where('parent_id', null) as $task)
                <div class="list-group-item d-flex justify-content-between align-items-start">
                    <div>
                        <form action="{{ route('project.wbs-items.update', $task) }}" method="POST"
                            class="d-inline wbs-toggle-form">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="title" value="{{ $task->title }}">
                            <input type="hidden" name="item_type" value="task">
                            <input type="hidden" name="parent_id" value="">
                            <input type="hidden" name="note" value="{{ $task->note }}">
                            <input type="hidden" name="type" value="{{ $task->type }}">
                            <input type="hidden" name="from" value="{{ $task->from }}">
                            <input type="hidden" name="to" value="{{ $task->to }}">
                            <input type="hidden" name="is_done" value="0">
                            <div class="d-flex align-items-start">
                                <input type="checkbox" id="wbs-task-{{ $task->id }}"
                                    data-id="{{ $task->id }}" data-title="{{ e($task->title) }}"
                                    class="wbs-item-checkbox form-check-input me-2" name="is_done" value="1"
                                    {{ $task->is_done ? 'checked' : '' }} onchange="toggleWbsItem(this)">

                                <div>
                                    <label for="wbs-task-{{ $task->id }}" class="mb-0"
                                        data-title="{{ e($task->title) }}">
                                        @if ($task->is_done)
                                            <s class="text-success">{{ $task->title }}</s>
                                        @else
                                            {{ $task->title }}
                                        @endif
                                    </label>

                                    <div class="small text-muted mt-1">
                                        <span class="badge bg-secondary me-1">{{ $task->type }}</span>
                                        <span>{{ $task->from }} - {{ $task->to }}</span>
                                    </div>

                                    @if ($task->note)
                                        <div class="small text-muted mt-1">{{ $task->note }}</div>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                    <div>
                        <form action="{{ route('project.wbs-items.destroy', $task) }}" method="POST"
                            class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Delete task">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 7l16 0" />
                                    <path d="M10 11l0 6" />
                                    <path d="M14 11l0 6" />
                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Add Task Modal --}}
<x-project.add-task-modal :project="$project" :categories="$categories" />

{{-- Import WBS Items Modal --}}
<div class="modal fade" id="importWbsModal" tabindex="-1" aria-labelledby="importWbsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importWbsModalLabel">Import List Of Work Items</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('project.wbs-items.import', $project->id) }}" method="POST"
                    enctype="multipart/form-data" id="importWbsForm">
                    @csrf
                    <div class="mb-3">
                        <label for="wbsFile" class="form-label text-muted">Select Excel File</label>
                        <input type="file" class="form-control" id="wbsFile" name="file"
                            accept=".xlsx,.xls" required>
                        <div class="form-text">Upload an Excel file (.xlsx or .xls) with list of work items</div>
                    </div>

                    <div class="mb-3">
                        <a href="{{ asset('template/Template List OF Work.xlsx') }}" 
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="importWbsForm" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-upload">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                        <path d="M7 9l5 -5l5 5" />
                        <path d="M12 4l0 12" />
                    </svg>
                    Import
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Utility to escape HTML when inserting into label
    function escapeHtml(str) {
        return String(str).replace(/[&<>"']/g, function(m) {
            return ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            })[m];
        });
    }

    // Utility: recalculate overall and per-category WBS progress and update UI
    function recalcWbsProgress() {
        // overall
        const allCheckboxes = Array.from(document.querySelectorAll('.wbs-item-checkbox'));
        const total = allCheckboxes.length;
        const done = allCheckboxes.filter(cb => cb.checked).length;
        const overallPercent = total ? Math.round((done / total) * 100) : 0;
        const overallBar = document.getElementById('wbsOverallBar');
        const overallPercentEl = document.getElementById('wbsOverallPercent');
        if (overallBar) overallBar.style.width = overallPercent + '%';
        if (overallPercentEl) overallPercentEl.textContent = overallPercent + '%';

        // per-category
        document.querySelectorAll('.wbs-cat').forEach(function(catEl) {
            const catId = catEl.dataset.catId;
            const cbs = Array.from(catEl.querySelectorAll('.wbs-item-checkbox'));
            const t = cbs.length;
            const d = cbs.filter(cb => cb.checked).length;
            const pct = t ? Math.round((d / t) * 100) : 0;
            const bar = document.getElementById('wbs-cat-bar-' + catId);
            const pctEl = document.getElementById('wbs-cat-percent-' + catId);
            if (bar) bar.style.width = pct + '%';
            if (pctEl) pctEl.textContent = pct + '%';
        });
    }

    // Global function to toggle WBS item via AJAX. Called from checkbox onchange.
    window.toggleWbsItem = async function(cb) {
        if (!cb) return;

        const csrf = document.querySelector('meta[name="csrf-token"]') ?
            document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

        const id = cb.dataset.id;
        const checked = cb.checked ? 1 : 0;
        const url = `/project/wbs-items/${id}/toggle`;

        // Disable while processing
        cb.disabled = true;

        try {
            const res = await fetch(url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({
                    is_done: checked
                })
            });

            if (!res.ok) throw new Error('Network response was not ok');
            const data = await res.json();

            // update label content
            const label = document.querySelector(
                `label[for="wbs-child-${id}"], label[for="wbs-task-${id}"]`
            );
            const title = cb.dataset.title || (label ? label.dataset.title : '');
            if (label) {
                if (data.is_done) {
                    // use <span> for inline strike
                    label.innerHTML = `<span class="text-success">${escapeHtml(title)}</span>`;
                } else {
                    label.textContent = title;
                }
            }

            // recalc progress bars
            recalcWbsProgress();

        } catch (err) {
            // revert checkbox on failure
            cb.checked = !cb.checked;
            console.error(err);
            alert('Failed to update task status.');
        } finally {
            cb.disabled = false;
        }
    };

    // initial calculation on page load
    recalcWbsProgress();
});
</script>
@endpush
