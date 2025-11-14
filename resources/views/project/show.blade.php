@extends('layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <!-- Page pre-title -->
            <div class="page-pretitle">Overview</div>
            <h2 class="page-title">Project Details</h2>
        </div>
        <!-- Page title actions -->
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">


                @if ($project->status == 'project-deal')
                    <button type="button" class="btn btn-primary change-status-btn" data-bs-toggle="modal"
                        data-bs-target="#addProjectLogModal" data-project-id="{{ $project->id }}"
                        data-project-name="{{ $project->project_name ?? ($project->customer_name ?? '') }}"
                        data-pre-sales="{{ $project->preSalesPerson->name ?? '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-2">
                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                            <path d="M16 5l3 3" />
                        </svg>
                        Change Status
                    </button>
                @endif



                <a href="{{ route('project.index') }}" class="btn btn-outline-light">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-2">
                        <path d="M9 14l-4 -4l4 -4" />
                        <path d="M5 10h11a4 4 0 1 1 0 8h-1" />
                    </svg>
                    Back to Projects
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                Project Information
            </h3>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <!-- Customer Name -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label text-muted">Customer Name</label>
                        <div class="fw-bold">{{ $project->client_name ?? 'N/A' }}</div>
                    </div>
                </div>

                <!-- Phone Number -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label text-muted">Phone Number</label>
                        <div class="fw-bold">{{ $project->client_phone ?? 'N/A' }}</div>
                    </div>
                </div>

                <!-- Email Address -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label text-muted">Email Address</label>
                        <div class="fw-bold">{{ $project->client_email ?? 'N/A' }}</div>
                    </div>
                </div>

                <!-- Company Name -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label text-muted">Company Name</label>
                        <div class="fw-bold">{{ $project->company ?? 'N/A' }}</div>
                    </div>
                </div>

                <!-- Company Identity -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label text-muted">Company Identity</label>
                        <div class="fw-bold">{{ $project->company_identity ?? 'N/A' }}</div>
                    </div>
                </div>

                <!-- Status -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label text-muted">Status</label>
                        <div>
                            {{ $project->status }}
                        </div>
                    </div>
                    <!-- Change Status Modal -->
                    <div class="modal modal-blur fade" id="addProjectLogModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <form id="changeStatusForm" method="POST"
                                    action="{{ route('project.changeStatus', $project) }}" enctype="multipart/form-data">
                                    @csrf

                                    <input type="hidden" name="status" value="on-going">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Change Project Status</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="project_id" id="modalProjectId"
                                            value="{{ $project->id }}">

                                        <div class="mb-3">
                                            <label class="form-label">PIC Project</label>
                                            <input type="text" name="pic_project" id="picProject"
                                                class="form-control" value="{{ $project->preSalesPerson->name ?? '' }}">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Waktu Pelaksanaan (hari)</label>
                                            <input type="number" name="waktu_pelaksanaan_days" id="waktuPelaksanaan"
                                                class="form-control" min="1" step="1"
                                                value="{{ $project->waktu_pelaksanaan_days ?? '' }}">
                                        </div>


                                        <!-- Additional files required when changing status to on-going -->

                                        <hr>

                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0">Client Persons</h6>
                                            <button id="addClientPersonBtn" class="btn btn-sm btn-outline-primary">Add
                                                Person</button>
                                        </div>

                                        <div id="clientPersonsWrapper"></div>

                                        <!-- Hidden JSON payload for client persons -->
                                        <input type="hidden" name="client_persons" id="clientPersonsJson">

                                        <!-- Template for client person -->
                                        <template id="clientPersonTemplate">
                                            <div class="card mb-2 client-person-row p-3">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <strong>Client Person</strong>
                                                    <button class="btn btn-sm btn-danger remove-client-person"
                                                        type="button">Remove</button>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Name</label>
                                                        <input type="text" name="client_persons[][name]"
                                                            class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Phone</label>
                                                        <input type="text" name="client_persons[][phone]"
                                                            class="form-control">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Email</label>
                                                        <input type="email" name="client_persons[][email]"
                                                            class="form-control">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Notes</label>
                                                        <input type="text" name="client_persons[][notes]"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </template>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-link"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>



                </div>



                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label text-muted">Sales Person</label>
                        <div class="fw-bold">
                            {{ $project->prospect->creator->name ?? 'Not Assigned' }}
                        </div>
                    </div>
                </div>

                <!-- Target Deal Period -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label text-muted">Waktu pengerjaan</label>
                        <div class="fw-bold">
                            {{ $project->execution_time . ' Hari' ?? '-' }}
                        </div>
                    </div>

                </div>


                <!-- Supporting Document -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label text-muted">Supporting Document</label>
                        <div>
                            @if ($project->document ?? false)
                                <a href="{{ Storage::url("{$project->document}") }}" target="_blank" class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z">
                                        </path>
                                        <polyline points="14,2 14,8 20,8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13">
                                        </line>
                                        <line x1="16" y1="17" x2="8" y2="17">
                                        </line>
                                        <polyline points="10,9 9,9 8,9"></polyline>
                                    </svg>
                                    View Document
                                </a>
                            @else
                                <span class="text-muted">No document uploaded</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- PO File -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label text-muted">File PO (Purchase Order)</label>
                        <div>
                            @if ($project->po_file ?? false)
                                <a href="{{ Storage::url("{$project->po_file}") }}" target="_blank" class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z">
                                        </path>
                                        <polyline points="14,2 14,8 20,8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13">
                                        </line>
                                        <line x1="16" y1="17" x2="8" y2="17">
                                        </line>
                                        <polyline points="10,9 9,9 8,9"></polyline>
                                    </svg>
                                    View PO File
                                </a>
                            @else
                                <span class="text-muted">No PO file uploaded</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- SPK File -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label text-muted">File SPK (Surat Perjanjian Kerja)</label>
                        <div>
                            @if ($project->spk_file ?? false)
                                <a href="{{ Storage::url("{$project->spk_file}") }}" target="_blank" class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z">
                                        </path>
                                        <polyline points="14,2 14,8 20,8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13">
                                        </line>
                                        <line x1="16" y1="17" x2="8" y2="17">
                                        </line>
                                        <polyline points="10,9 9,9 8,9"></polyline>
                                    </svg>
                                    View SPK File
                                </a>
                            @else
                                <span class="text-muted">No SPK file uploaded</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Created Date -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label text-muted">Created Date</label>
                        <div class="fw-bold">
                            {{ $project->created_at ? $project->created_at->format('M d, Y') : 'N/A' }}
                        </div>
                    </div>
                </div>

                <!-- Additional Notes -->
                @if ($project->note ?? false)
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label text-muted">Additional Notes</label>
                            <div class="card bg-light">
                                <div class="card-body">
                                    {{ $project->note }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Client Persons list (from project_client_person) --}}
                @if ($project->clientPersons && $project->clientPersons->isNotEmpty())
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label text-muted">Client Persons</label>
                            <div class="list-group">
                                @foreach ($project->clientPersons as $person)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="fw-bold">{{ $person->name ?? '-' }}</div>
                                                @if ($person->note)
                                                    <div class="small text-muted">{{ $person->note }}</div>
                                                @endif
                                                <div class="small text-muted mt-1">
                                                    Phone: {{ $person->phone ?? '-' }}
                                                    @if ($person->email)
                                                        | Email: {{ $person->email }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>


    @if ($project->status == 'on-going')
        
    <div class="card mb-4">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                <li class="nav-item">
                    <a href="#list-of-work" class="nav-link active" data-bs-toggle="tab">List Of Work</a>
                </li>
                <li class="nav-item">
                    <a href="#status-barang" class="nav-link" data-bs-toggle="tab">Status Barang</a>
                </li>
                <li class="nav-item">
                    <a href="#weekly-meeting" class="nav-link" data-bs-toggle="tab">Weekly Meeting</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane active show" id="list-of-work">
                    <div>
                        <x-project.wbs-list :project="$project" :wbsItems="$wbsItems" />
                    </div>
                </div>
                <div class="tab-pane" id="status-barang">
                    <x-project.delivery-table :equipment="$equipment ?? []" />
                </div>
                <div class="tab-pane" id="weekly-meeting">
                    <x-project.weekly-meeting />
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="icon me-2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14,2 14,8 20,8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                </svg>
                Project Files
            </h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                    <thead>
                        <tr>
                            <th>File Type</th>
                            <th>Uploaded File</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $fileMap = [
                                'drawing_file' => 'Drawing',
                                'wbs_file' => 'WBS',
                                'project_schedule_file' => 'Project Schedule',
                                'purchase_schedule_file' => 'Purchase Schedule',
                                'pengajuan_material_project_file' => 'Pengajuan Material',
                                'pengajuan_tools_project_file' => 'Pengajuan Tools',
                            ];
                        @endphp

                        @foreach ($fileMap as $key => $label)
                            <tr>
                                <td class="fw-bold">{{ $label }}</td>
                                <td>
                                    @if (!empty($project->{$key}))
                                        <a href="{{ Storage::url($project->{$key}) }}"
                                            target="_blank">{{ basename($project->{$key}) }}</a>
                                    @else
                                        <span class="text-muted">No file uploaded</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div>
                                        {{-- 
                                                <button type="button" class="btn btn-sm btn-icon btn-primary open-upload-modal"
                                                    data-file-key="{{ $key }}"
                                                    data-file-label="{{ $label }}"
                                                    title="Upload {{ $label }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                        <polyline points="7 10 12 5 17 10"></polyline>
                                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                                    </svg>
                                                </button> --}}
                                        <button type="button"
                                            class="btn btn-primary btn-5 d-none d-sm-inline-block open-upload-modal"
                                            data-file-key="{{ $key }}" data-file-label="{{ $label }}"
                                            title="Upload {{ $label }}" data-bs-toggle="modal"
                                            data-bs-target="#modal-report">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-upload">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                                <path d="M7 9l5 -5l5 5" />
                                                <path d="M12 4l0 12" />
                                            </svg>
                                            Upload
                                        </button>


                                        @if (!empty($project->{$key}))
                                            <form action="{{ route('project.deleteFile', [$project->id, $key]) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this file?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit    "
                                                    class="btn btn-danger btn-5 d-none d-sm-inline-block ">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M4 7l16 0" />
                                                        <path d="M10 11l0 6" />
                                                        <path d="M14 11l0 6" />
                                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif


    {{-- Add Task Modal Component --}}

    <div class="modal fade" id="uploadProjectFileModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="uploadProjectFileForm" method="POST" enctype="multipart/form-data"
                    action="{{ route('project.uploadFile') }}">
                    @csrf
                    <input type="hidden" name="file_key" id="modalFileKey" value="">
                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadModalTitle">Upload File</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">File</label>
                            <input type="file" name="file" id="modalFileInput" class="form-control" required>
                            <div class="form-text">Allowed types: PDF, DOC, DOCX, JPG, PNG. Max 5MB.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    </div>


@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const uploadModalEl = document.getElementById('uploadProjectFileModal');
            if (!uploadModalEl) return;

            const uploadModal = new bootstrap.Modal(uploadModalEl);
            const modalFileKeyInput = document.getElementById('modalFileKey');
            const uploadModalTitle = document.getElementById('uploadModalTitle');
            const modalFileInput = document.getElementById('modalFileInput');

            // Open modal for a specific file key when clicking the per-row button
            document.querySelectorAll('.open-upload-modal').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const fileKey = btn.getAttribute('data-file-key');
                    const fileLabel = btn.getAttribute('data-file-label') || 'File';
                    if (modalFileKeyInput) modalFileKeyInput.value = fileKey;
                    if (uploadModalTitle) uploadModalTitle.textContent = `Upload ${fileLabel}`;
                    if (modalFileInput) modalFileInput.value = '';
                    uploadModal.show();
                });
            });

            // Generic Upload button opens modal for the first file type (drawing_file) by default
            const openUploadModalBtn = document.getElementById('openUploadModalBtn');
            if (openUploadModalBtn) {
                openUploadModalBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    // default to drawing_file
                    if (modalFileKeyInput) modalFileKeyInput.value = 'drawing_file';
                    if (uploadModalTitle) uploadModalTitle.textContent = 'Upload Drawing';
                    if (modalFileInput) modalFileInput.value = '';
                    uploadModal.show();
                });
            }
        });
    </script>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('activity');
            const fileUploadSection = document.getElementById('fileUploadSection');
            const poFileInput = document.getElementById('po_file');
            const spkFileInput = document.getElementById('spk_file');

            // Only wire up file upload toggle if all related elements exist
            if (statusSelect && fileUploadSection && poFileInput && spkFileInput) {
                function toggleFileUpload() {
                    const selectedOption = statusSelect.options[statusSelect.selectedIndex];
                    const percentage = selectedOption.getAttribute('data-percentage');

                    if (percentage == 100) {
                        fileUploadSection.style.display = 'block';
                        // Make file inputs required when status is 100%
                        poFileInput.setAttribute('required', 'required');
                        spkFileInput.setAttribute('required', 'required');
                    } else {
                        fileUploadSection.style.display = 'none';
                        // Remove required attribute when not 100%
                        poFileInput.removeAttribute('required');
                        spkFileInput.removeAttribute('required');
                    }
                }

                // Check on page load
                toggleFileUpload();

                // Check when status changes
                statusSelect.addEventListener('change', toggleFileUpload);
            }

            // File validation
            function validateFile(input) {
                const file = input.files[0];
                if (file) {
                    const maxSize = 5 * 1024 * 1024; // 5MB
                    const allowedTypes = ['application/pdf', 'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'image/jpeg', 'image/jpg', 'image/png'
                    ];

                    if (file.size > maxSize) {
                        alert('File size must be less than 5MB');
                        input.value = '';
                        return false;
                    }

                    if (!allowedTypes.includes(file.type)) {
                        alert('Invalid file type. Please upload PDF, DOC, DOCX, JPG, or PNG files only.');
                        input.value = '';
                        return false;
                    }
                }
                return true;
            }

            if (poFileInput) {
                poFileInput.addEventListener('change', function() {
                    validateFile(this);
                });
            }

            if (spkFileInput) {
                spkFileInput.addEventListener('change', function() {
                    validateFile(this);
                });
            }

            // Validate M.O.M PDF in modal
            const momFileInput = document.getElementById('momFile');
            if (momFileInput) {
                momFileInput.addEventListener('change', function() {
                    validateFile(this);
                });
            }



            // --- Change Status Modal and dynamic client-persons ---
            const changeModalEl = document.getElementById('addProjectLogModal');
            const addClientPersonBtn = document.getElementById('addClientPersonBtn');
            const clientPersonsWrapper = document.getElementById('clientPersonsWrapper');
            const clientTemplate = document.getElementById('clientPersonTemplate');
            const changeStatusForm = document.getElementById('changeStatusForm');

            function addClientPerson(prefill = {}) {
                const clone = clientTemplate.content.cloneNode(true);
                const row = clone.querySelector('.client-person-row');
                if (!row) return;
                // set prefill values if provided
                const nameInput = row.querySelector('input[name="client_persons[][name]"]');
                const phoneInput = row.querySelector('input[name="client_persons[][phone]"]');
                const emailInput = row.querySelector('input[name="client_persons[][email]"]');
                const notesInput = row.querySelector('input[name="client_persons[][notes]"]');
                if (prefill.name) nameInput.value = prefill.name;
                if (prefill.phone) phoneInput.value = prefill.phone;
                if (prefill.email) emailInput.value = prefill.email;
                if (prefill.notes) notesInput.value = prefill.notes;
                clientPersonsWrapper.appendChild(clone);
            }

            // utility to escape HTML when inserting into label
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
                            // use <s> for inline strike
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

            // Add first empty row by default when modal opens
            if (changeModalEl) {
                changeModalEl.addEventListener('show.bs.modal', function(event) {
                    // origin button that triggered modal
                    const triggerBtn = event.relatedTarget;

                    if (triggerBtn) {
                        const pid = triggerBtn.getAttribute('data-project-id');
                        const preSales = triggerBtn.getAttribute('data-pre-sales');
                        const pname = triggerBtn.getAttribute('data-project-name');
                        const modalProjectId = document.getElementById('modalProjectId');
                        const picProject = document.getElementById('picProject');
                        if (modalProjectId) modalProjectId.value = pid || modalProjectId.value;
                        if (picProject) picProject.value = preSales || picProject.value || '';
                    }
                    // reset client persons wrapper and add one empty row
                    if (clientPersonsWrapper) {
                        clientPersonsWrapper.innerHTML = '';
                        addClientPerson();
                    }
                });
            }

            // Click handler to add new client person
            if (addClientPersonBtn) {
                addClientPersonBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    addClientPerson();
                });
            }

            // Delegate remove buttons
            if (clientPersonsWrapper) {
                clientPersonsWrapper.addEventListener('click', function(e) {
                    if (e.target && e.target.classList.contains('remove-client-person')) {
                        const row = e.target.closest('.client-person-row');
                        if (row) row.remove();
                    }
                });
            }

            // On submit, serialize client persons into single JSON hidden input
            if (changeStatusForm && clientPersonsWrapper) {
                changeStatusForm.addEventListener('submit', function(e) {
                    const persons = [];
                    const rows = clientPersonsWrapper.querySelectorAll('.client-person-row');
                    rows.forEach(function(row) {
                        const nameInput = row.querySelector('input[name="client_persons[][name]"]');
                        const phoneInput = row.querySelector(
                            'input[name="client_persons[][phone]"]');
                        const emailInput = row.querySelector(
                            'input[name="client_persons[][email]"]');
                        const notesInput = row.querySelector(
                            'input[name="client_persons[][notes]"]');

                        const person = {
                            name: nameInput ? nameInput.value.trim() : '',
                            phone: phoneInput ? phoneInput.value.trim() : '',
                            email: emailInput ? emailInput.value.trim() : '',
                            notes: notesInput ? notesInput.value.trim() : ''
                        };
                        persons.push(person);

                        // remove name attributes so original inputs won't be submitted as arrays
                        if (nameInput) nameInput.removeAttribute('name');
                        if (phoneInput) phoneInput.removeAttribute('name');
                        if (emailInput) emailInput.removeAttribute('name');
                        if (notesInput) notesInput.removeAttribute('name');
                    });

                    const hidden = document.getElementById('clientPersonsJson');
                    if (hidden) hidden.value = JSON.stringify(persons);
                    // allow form to submit normally after serialization
                });
            }
        });
    </script>
@endpush
