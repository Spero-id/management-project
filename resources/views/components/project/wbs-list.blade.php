@props(['project', 'wbsItems'])

@php
    $categories = $wbsItems->where('item_type', 'category');
    $tasks = $wbsItems->where('item_type', 'task');
    $totalTasks = $tasks->count();
    $completedTasks = $tasks->where('is_done', 1)->count();
    $overallPercent = $totalTasks ? round(($completedTasks / $totalTasks) * 100) : 0;
@endphp


<div>
    <div class="mb-3">
        <form action="{{ route('project.wbs-items.store', $project) }}" method="POST"
            class="d-flex align-items-center w-100">
            @csrf
            <input type="hidden" name="item_type" value="category">
            <input type="text" name="title" class="form-control me-2 flex-grow-1" placeholder="New category title"
                required aria-label="New category title">
            <button class="btn btn-outline-primary" type="submit">Create Category</button>
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


    <div class="mb-3">
        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#importWbsModal">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-upload">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                <path d="M7 9l5 -5l5 5" />
                <path d="M12 4l0 12" />
            </svg>
            Import WBS Items
        </button>
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
                        <div>
                            <strong>{{ $cat->title }}</strong>
                            @if ($cat->note)
                                <div class="small text-muted">{{ $cat->note }}</div>
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
                        <div class="mt-2">
                            @foreach ($catChildren as $child)
                                <x-project.wbs-task-item :task="$child" />
                            @endforeach
                        </div>
                    @endif

                    {{-- Add Task button for category --}}
                    <button type="button" class="btn btn-outline-primary me-2 w-full open-add-task-modal"
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
                            <input type="hidden" name="is_done" value="0">
                            <div class="d-flex align-items-start">
                                <input type="checkbox" id="wbs-task-{{ $task->id }}" data-id="{{ $task->id }}"
                                    data-title="{{ e($task->title) }}" class="wbs-item-checkbox form-check-input me-2"
                                    name="is_done" value="1" {{ $task->is_done ? 'checked' : '' }}
                                    onchange="toggleWbsItem(this)">

                                <div>
                                    <label for="wbs-task-{{ $task->id }}" class="mb-0"
                                        data-title="{{ e($task->title) }}">
                                        @if ($task->is_done)
                                            <s class="text-success">{{ $task->title }}</s>
                                        @else
                                            {{ $task->title }}
                                        @endif
                                    </label>

                                    @if ($task->note)
                                        <div class="small text-muted mt-1">{{ $task->note }}</div>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                    <div>
                        <form action="{{ route('project.wbs-items.destroy', $task) }}" method="POST" class="d-inline">
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
<div class="modal fade" id="importWbsModal" tabindex="-1" aria-labelledby="importWbsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importWbsModalLabel">Import WBS Items</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('project.wbs-items.import', $project->id) }}" method="POST" enctype="multipart/form-data" id="importWbsForm">
                    @csrf
                    <div class="mb-3">
                        <label for="wbsFile" class="form-label">Select File</label>
                        <input type="file" class="form-control" id="wbsFile" name="file" accept=".xlsx,.xls,.csv" required>
                        <small class="text-muted">Accepted formats: .xlsx, .xls, .csv</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" id="downloadTemplateBtn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-download">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                        <path d="M7 11l5 5l5 -5" />
                        <path d="M12 4l0 12" />
                    </svg>
                    Download Template
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="importWbsForm" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-upload">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
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
