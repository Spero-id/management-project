@props(['task'])

<div class="card mb-2">
    <div class="card-body d-flex justify-content-between align-items-center p-2">
        <div class="w-100">
            <form action="{{ route('project.wbs-items.update', $task) }}" method="POST"
                class="d-inline wbs-toggle-form mb-0">
                @csrf
                @method('PUT')
                <input type="hidden" name="title" value="{{ $task->title }}">
                <input type="hidden" name="item_type" value="task">
                <input type="hidden" name="parent_id" value="{{ $task->parent_id }}">
                <input type="hidden" name="note" value="{{ $task->note }}">
                <input type="hidden" name="is_done" value="0">
                <div class="form-check d-flex align-items-start mb-0">
                    <input type="checkbox" id="wbs-child-{{ $task->id }}"
                        data-id="{{ $task->id }}" data-title="{{ e($task->title) }}"
                        class="wbs-item-checkbox form-check-input me-2" name="is_done" value="1"
                        {{ $task->is_done ? 'checked' : '' }} onchange="toggleWbsItem(this)">

                    <div class="ms-2">
                        <label for="wbs-child-{{ $task->id }}" class="mb-0"
                            data-title="{{ e($task->title) }}">
                            @if ($task->is_done)
                                <span class="text-success">{{ $task->title }}</span>
                            @else
                                {{ $task->title }}
                            @endif
                        </label>

                        @if ($task->note)
                            <div class="small text-muted mt-1">Note: {{ $task->note }}</div>
                        @endif
                    </div>
                </div>
            </form>
        </div>
        <div class="ms-2">
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
</div>
