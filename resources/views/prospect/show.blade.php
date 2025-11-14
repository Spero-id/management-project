@extends('layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <!-- Page pre-title -->
            <div class="page-pretitle">Overview</div>
            <h2 class="page-title">Prospect Details</h2>
        </div>
        <!-- Page title actions -->
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                @if (($prospect->prospectStatus->persentage ?? 0) < 100)
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProspectLogModal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-2">
                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                            <path d="M16 5l3 3" />
                        </svg>
                        Change Status
                    </button>
                    <a href="{{ route('prospect.edit', $prospect->id ?? 1) }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-2">
                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                            <path d="M16 5l3 3" />
                        </svg>
                        Edit Prospect
                    </a>
                @else
                    <div class="alert  alert-important alert-info d-inline-block mb-0 me-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="me-1">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="m9 12 2 2 4-4"></path>
                        </svg>
                        Prospect sudah mencapai 100% - tidak dapat diubah lagi
                    </div>
                @endif



                <a href="{{ route('prospect.index') }}" class="btn btn-outline-light">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-2">
                        <path d="M9 14l-4 -4l4 -4" />
                        <path d="M5 10h11a4 4 0 1 1 0 8h-1" />
                    </svg>
                    Back to Prospects
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon me-2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Prospect Information
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Customer Name -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Customer Name</label>
                                <div class="fw-bold">{{ $prospect->customer_name ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <!-- Phone Number -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Phone Number</label>
                                <div class="fw-bold">{{ $prospect->no_handphone ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <!-- Email Address -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Email Address</label>
                                <div class="fw-bold">{{ $prospect->email ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <!-- Company Name -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Company Name</label>
                                <div class="fw-bold">{{ $prospect->company ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <!-- Company Identity -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Company Identity</label>
                                <div class="fw-bold">{{ $prospect->company_identity ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Status</label>
                                <div>
                                    <x-status-badge :status="$prospect->prospectStatus->name" />
                                </div>
                            </div>
                        </div>

                        <!-- Pre Sales Person -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Pre Sales Person</label>
                                <div class="fw-bold">
                                    {{ $prospect->preSalesPerson->name ?? 'Not Assigned' }}
                                </div>
                            </div>
                        </div>

                        <!-- Target Deal Period -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Periode Target Deal</label>
                                <div class="fw-bold">
                                    @if ($prospect->target_from_month && $prospect->target_to_month)
                                        @php
                                            $months = [
                                                '01' => 'Januari',
                                                '02' => 'Februari',
                                                '03' => 'Maret',
                                                '04' => 'April',
                                                '05' => 'Mei',
                                                '06' => 'Juni',
                                                '07' => 'Juli',
                                                '08' => 'Agustus',
                                                '09' => 'September',
                                                '10' => 'Oktober',
                                                '11' => 'November',
                                                '12' => 'Desember',
                                            ];
                                            $fromMonthKey = $prospect->target_from_month;
                                            $toMonthKey = $prospect->target_to_month;
                                            $fromMonth = $months[$fromMonthKey] ?? $prospect->target_from_month;
                                            $toMonth = $months[$toMonthKey] ?? $prospect->target_to_month;

                                            // Use new separate year fields if available, fallback to old target_year
                                            $fromYear = $prospect->target_from_year ?? $prospect->target_year;
                                            $toYear = $prospect->target_to_year ?? $prospect->target_year;
                                        @endphp
                                        @if ($fromYear == $toYear)
                                            {{ $fromMonth }} - {{ $toMonth }} {{ $fromYear }}
                                        @else
                                            {{ $fromMonth }} {{ $fromYear }} - {{ $toMonth }}
                                            {{ $toYear }}
                                        @endif
                                    @else
                                        Tidak Ada
                                    @endif
                                </div>
                            </div>
                        </div>



                        <!-- PO File -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">File PO (Purchase Order)</label>
                                <div>
                                    @if ($prospect->po_file ?? false)
                                        <a href="{{ Storage::url("{$prospect->po_file}") }}" target="_blank"
                                            class="">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
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
                                    @if ($prospect->spk_file ?? false)
                                        <a href="{{ Storage::url("{$prospect->spk_file}") }}" target="_blank"
                                            class="">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
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
                                    {{ $prospect->created_at ? $prospect->created_at->format('M d, Y') : 'N/A' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Product Offered</label>
                                <div class="fw-bold">
                                    {{ $prospect->product_offered ? $prospect->product_offered : 'N/A' }}
                                </div>
                            </div>
                        </div>



                        <!-- Additional Notes -->
                        @if ($prospect->note ?? false)
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Additional Notes</label>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            {{ $prospect->note }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Quotations Section -->
    <div class="row row-cards mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon me-2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14,2 14,8 20,8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10,9 9,9 8,9"></polyline>
                        </svg>
                        Related Quotations
                    </h3>

                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                    <th>Quotation #</th>
                                    <th>Date Created</th>
                                    <th>Total Amount</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($prospect->quotations ?? collect()) as $quotation)
                                    <tr>
                                        <td class="fw-bold">
                                            {{ $quotation->quotation_number ?? 'QUO-' . str_pad($quotation->id, 4, '0', STR_PAD_LEFT) }}
                                        </td>
                                        <td>
                                            <div class="text-muted">
                                                {{ $quotation->created_at ? $quotation->created_at->format('M d, Y') : 'N/A' }}
                                            </div>
                                            <div class="text-secondary small">
                                                {{ $quotation->created_at ? $quotation->created_at->format('h:i A') : '' }}
                                            </div>
                                        </td>
                                        <td class="fw-bold text-success">
                                            Rp {{ number_format($quotation->total_amount ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('quotation.pdf', $quotation->id) }}" class="btn btn-icon"
                                                aria-label="Edit Quotation" title="Edit Quotation">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-download">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                                    <path d="M7 11l5 5l5 -5" />
                                                    <path d="M12 4l0 12" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('quotation.show', $quotation->id) }}" class="btn btn-icon"
                                                aria-label="View Quotation">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                    <path
                                                        d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('quotation.edit', $quotation->id) }}" class="btn btn-icon"
                                                aria-label="Edit Quotation" title="Edit Quotation">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                    <path
                                                        d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                    <path d="M16 5l3 3" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="empty">
                                                <p class="empty-title">No quotations found</p>
                                                <p class="empty-subtitle text-muted">
                                                    This prospect doesn't have any quotations yet.
                                                </p>

                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Prospect Log Section -->
    <div class="row row-cards mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon me-2">
                            <path d="M3 7v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7" />
                            <path d="M16 3v4" />
                            <path d="M8 3v4" />
                            <path d="M4 11h16" />
                        </svg>
                        Prospect Log
                    </h3>

                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>User</th>
                                    <th>From Status</th>
                                    <th>To Status</th>
                                    <th>Notes</th>

                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($prospect->logs ?? collect()) as $log)
                                    <tr>
                                        <td>
                                            <div class="text-muted">
                                                {{ $log->created_at ? $log->created_at->format('M d, Y') : 'N/A' }}
                                            </div>
                                            <div class="text-secondary small">
                                                {{ $log->created_at ? $log->created_at->format('h:i A') : '' }}
                                            </div>
                                        </td>
                                        <td>{{ $log->user->name ?? '-' }}</td>

                                        <td>
                                            <x-status-badge :status="$log->fromStatus->name" />
                                        </td>
                                        <td>
                                            <x-status-badge :status="$log->toStatus->name" />
                                        </td>
                                        <td>{{ $log->note ?? '-' }}</td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="empty">
                                                <p class="empty-title">No logs found</p>
                                                <p class="empty-subtitle text-muted">
                                                    There are no activities logged for this prospect yet.
                                                </p>
                                                <div class="empty-action mt-3">
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#addProspectLogModal">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="icon me-1">
                                                            <line x1="12" y1="5" x2="12"
                                                                y2="19"></line>
                                                            <line x1="5" y1="12" x2="19"
                                                                y2="12"></line>
                                                        </svg>
                                                        Add your first log
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Modal Add Prospect Log -->
                <div class="modal fade" id="addProspectLogModal" tabindex="-1"
                    aria-labelledby="addProspectLogModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form action="{{ route('prospect-log.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="prospect_id" value="{{ $prospect->id }}">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addProspectLogModalLabel">Change Prospect Status</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <div class="mb-3 ">
                                        <div class="form-label fw-bold mb-1">Current Status</div>
                                        <div>
                                            <x-status-badge :status="$prospect->prospectStatus->name" />
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="activity" class="form-label">Status</label>
                                        <select class="form-select" id="activity" name="status_id" required>
                                            <option value="">Select Status</option>
                                            @foreach ($prospectStatuses as $status)
                                                <option value="{{ $status->id }}"
                                                    data-percentage="{{ $status->persentage }}"
                                                    {{ $prospect->status_id == $status->id ? 'selected' : '' }}>
                                                    {{ $status->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- File Upload Section (Shows when 100% status is selected) -->
                                    <div id="fileUploadSection" class="mb-3" style="display: none;">
                                        <div class="alert   alert-info">
                                            <h4 class="alert-title">Upload Required Documents</h4>
                                            <div class="text-secondary">Status dengan progres 100% memerlukan upload file
                                                PO dan SPK.</div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="po_file" class="form-label">File PO (Purchase Order)</label>
                                                <input type="file" name="po_file" id="po_file" class="form-control"
                                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                                <small class="form-hint">Accepted formats: PDF, DOC, DOCX, JPG, PNG (Max:
                                                    5MB)</small>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="spk_file" class="form-label">File SPK (Surat Perjanjian
                                                    Kerja)</label>
                                                <input type="file" name="spk_file" id="spk_file"
                                                    class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                                <small class="form-hint">Accepted formats: PDF, DOC, DOCX, JPG, PNG (Max:
                                                    5MB)</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Notes</label>
                                        <textarea class="form-control" id="notes" name="note" rows="3" placeholder="Enter notes"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save Log</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('activity');
            const fileUploadSection = document.getElementById('fileUploadSection');
            const poFileInput = document.getElementById('po_file');
            const spkFileInput = document.getElementById('spk_file');

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

            poFileInput.addEventListener('change', function() {
                validateFile(this);
            });

            spkFileInput.addEventListener('change', function() {
                validateFile(this);
            });
        });
    </script>
@endpush
