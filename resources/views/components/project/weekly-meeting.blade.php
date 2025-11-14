<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label text-muted">Active Task</label>
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Enter task name"
                    value="Penanganban progam default sign untuk fix Ir" readonly>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label text-muted">Person in Charge</label>
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Select person" value="Member" readonly>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label text-muted">Start Date</label>
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Start date" value="Tanggal tanggal" readonly>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label text-muted">Target Complete Date</label>
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Target date"
                    value="Tanggal tanggal kalo sudah selesai" readonly>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label text-muted">Complete Date</label>
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Actual completion date" readonly>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label text-muted">Status Presentase</label>
            <div class="input-group">
                <input type="text" class="form-control" placeholder="0 - 100% / Selesai"
                    readonly>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="mb-3">
            <label class="form-label text-muted">Catatan</label>
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Di isi" readonly>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="row g-2 mb-4">
    <div class="col-auto">
        <button type="button" class="btn btn-outline-secondary">
            Simpan
        </button>
    </div>
    <div class="col-auto">
        <button type="button" class="btn btn-outline-primary">
            Add Task
        </button>
    </div>
</div>

<!-- Tasks Table -->
<div class="table-responsive">
    <table class="table table-vcenter card-table">
        <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th>Active Tasks</th>
                <th>Nama pelapor</th>
                <th>Start Date</th>
                <th>Target Complete Date</th>
                <th>Complete Date</th>
                <th>% Complete</th>
                <th>Status</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @php
                $tasks = [
                    [
                        'no' => 1,
                        'task' => 'Penanganban progam default sign untuk fix Ir',
                        'reporter' => 'Melody',
                        'start_date' => '11/11/2025',
                        'target_date' => '14/11/2025',
                        'complete_date' => '14/11/2025',
                        'percentage' => '100%',
                        'status' => 'Selesai',
                        'notes' => '',
                    ],
                    [
                        'no' => 2,
                        'task' => 'Penandingan kembali Quad Cam dan pengajuan PTZ Front ke dalam core',
                        'reporter' => 'Andy',
                        'start_date' => '10/11/2025',
                        'target_date' => '10/11/2025',
                        'complete_date' => '',
                        'percentage' => '',
                        'status' => 'Hold',
                        'notes' => '',
                    ],
                    [
                        'no' => 3,
                        'task' => 'Request penandingan mic ceiling dan penambahan posisi',
                        'reporter' => 'Pak Rahmat',
                        'start_date' => '11/11/2025',
                        'target_date' => '14/11/2025',
                        'complete_date' => '',
                        'percentage' => '20%',
                        'status' => '',
                        'notes' => '',
                    ],
                ];
            @endphp

            @foreach ($tasks as $task)
                <tr>
                    <td class="text-muted">{{ $task['no'] ?: '-' }}</td>
                    <td class="fw-bold">{{ $task['task'] ?: '-' }}</td>
                    <td>{{ $task['reporter'] ?: '-' }}</td>
                    <td>
                        <span>{{ $task['start_date'] ?: '-' }}</span>
                    </td>
                    <td>
                        <span>{{ $task['target_date'] ?: '-' }}</span>
                    </td>
                    <td>
                        <span>{{ $task['complete_date'] ?: '-' }}</span>
                    </td>
                    <td>
                        <span>{{ $task['percentage'] ?: '-' }}</span>
                    </td>
                    <td>
                        @if ($task['status'] === 'Selesai')
                            <span>{{ $task['status'] }}</span>
                        @elseif ($task['status'] === 'Hold')
                            <span>{{ $task['status'] }}</span>
                        @else
                            <span>{{ $task['status'] ?: '-' }}</span>
                        @endif
                    </td>
                    <td>{{ $task['notes'] ?: '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Import Button -->
<div class="row mt-4">
    <div class="col-12">
        <button type="button" class="btn btn-outline-primary float-end">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon me-1">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="7 10 12 5 17 10"></polyline>
                <line x1="12" y1="5" x2="12" y2="19"></line>
            </svg>
            IMPORT EXCEL
        </button>
    </div>
