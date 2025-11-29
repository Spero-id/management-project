@extends('layouts.app')

@push('styles')
    <style>
        /* Project tabs styles */
        .project-tabs {
            display: flex;
            gap: 10px;
            padding: 12px 0;
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
        }

        .project-tabs::-webkit-scrollbar {
            height: 5px;
        }

        .project-tabs::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .project-tabs::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 10px;
        }

        .project-tabs::-webkit-scrollbar-thumb:hover {
            background-color: #94a3b8;
        }

        .project-tab {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 28px;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 50px;
            color: #475569;
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none !important;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            white-space: nowrap;
            min-width: fit-content;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .project-tab:hover {
            background-color: #f8fafc;
            border-color: #cbd5e1;
            color: #475569;
            text-decoration: none !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }

        .project-tab.active {
            background: var(--tblr-primary);
            border-color: var(--tblr-primary);
            color: white;
            text-decoration: none !important;
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.25);
        }

        .project-tab.active:hover {
            background: var(--tblr-primary-darken);
            border-color: var(--tblr-primary-darken);
            color: white;
            text-decoration: none !important;
            box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
        }

        /* Content area */
        .content-area {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            height: calc(100vh - 200px);
            min-height: 500px;
            overflow-y: auto;
        }

        .content-area h5 {
            font-weight: 700;
            margin-bottom: 20px;
            color: #111827;
            font-size: 1.2rem;
        }

        /* Custom styles for the new table design */
        .card-table {
            margin-bottom: 0;
            font-size: 0.8rem;
            border: 1px solid #dee2e6;
        }

        .card-table th {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            padding: 8px 10px;
            border: 1px solid #dee2e6;
        }

        .card-table td {
            padding: 8px 10px;
            border: 1px solid #dee2e6;
        }

        .card-table td.text-end {
            white-space: nowrap;
        }

        .card-table tbody tr {
            border: 1px solid #dee2e6;
        }

        .table-selectable .table-selectable-check:checked+.table-selectable-check-indicator {
            background-color: var(--tblr-primary);
            border-color: var(--tblr-primary);
        }

        .icon-sm {
            width: 1rem;
            height: 1rem;
        }

        .badge {
            font-size: 0.65em;
        }

        .dropdown-toggle::after {
            margin-left: 0.5em;
        }

        /* Project calculation summary styles */
        .project-summary-table {
            margin-bottom: 1.5rem;
        }

        .project-summary-table .bg-warning {
            background-color: #ffeb3b !important;
            color: #000 !important;
        }

        .project-summary-table .table-success {
            background-color: #d4edda !important;
        }

        .project-summary-table td {
            padding: 12px 16px;
            font-size: 0.9rem;
            border: 1px solid #dee2e6;
        }

        .project-summary-table .fw-bold {
            font-weight: 600 !important;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-state .empty-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            background-color: #f8f9fa;
            border-radius: 50%;
            margin: 0 auto 20px;
        }

        .empty-state h5 {
            color: #6b7280;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .empty-state p {
            color: #9ca3af;
            font-size: 0.9rem;
            line-height: 1.5;
            max-width: 400px;
            margin: 0 auto;
        }

        .empty-state .small {
            font-size: 0.85rem;
        }
    </style>
@endpush

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <!-- Page pre-title -->
            <div class="page-pretitle">Finance</div>
            <h2 class="page-title">Perhitungan Project</h2>
        </div>
        <!-- Page title actions -->
        <div class="col-auto ms-auto d-print-none">
            <!-- PO Upload Modal -->
            <div class="modal modal-blur fade" id="modal-po-upload" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="po-modal-title">Manage purchase order</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="po-upload-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="po-order-item-id">
                            <div class="modal-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Brand</label>
                                        <input type="text" class="form-control" id="po-brand" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Model/Type</label>
                                        <input type="text" class="form-control" id="po-model" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Required quantity</label>
                                        <input type="number" class="form-control" id="po-required" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Confirmed quantity</label>
                                        <input type="number" class="form-control" id="po-confirmed" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Status</label>
                                        <input type="text" class="form-control" id="po-status" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label class="form-label required">PO number</label>
                                        <input type="text" class="form-control" id="po-number" name="po_number"
                                            placeholder="Enter PO number" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label class="form-label" id="po-file-label">Upload PO file</label>
                                        <input type="file" class="form-control" id="po-file" name="po_file"
                                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                        <small class="text-muted">Accepted formats: PDF, DOC, DOCX, JPG, PNG (Max:
                                            5MB)</small>
                                        <div id="current-po-file" class="mt-2" style="display: none;">
                                            <span class="text-muted">Current file: </span>
                                            <a href="#" id="po-file-link" target="_blank" class="text-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                                    <path
                                                        d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                                </svg>
                                                <span id="po-file-name"></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="save-po-upload">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="icon">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M5 12l5 5l10 -10" />
                                    </svg>
                                    Save PO
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="alert alert-important alert-success alert-dismissible fade show" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-check me-2">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M5 12l5 5l10 -10" />
            </svg>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-important alert-danger alert-dismissible fade show" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-alert-circle me-2">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                <path d="M12 8v4" />
                <path d="M12 16h.01" />
            </svg>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Project Tabs at the Top -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="project-tabs">
                @forelse($projects as $project)
                    <a href="javascript:void(0)" 
                       class="project-tab project-item"
                       data-project-id="{{ $project->id }}">
                        {{ $project->project_name }}
                    </a>
                @empty
                
                @endforelse
            </div>
        </div>
    </div>

    <!-- Content Area: Items to Order Table -->
    <div class="row">
        <div class="col-12">
            <div class="content-area">
                @forelse($projects as $index => $project)
                    <div class="project-content" id="project-content-{{ $project->id }}" style="{{ $index === 0 ? '' : 'display: none;' }}">
                        @if($project->quotationItemsGrouped && $project->quotationItemsGrouped->count() > 0)
                            <div class="table-responsive">
                                <!-- Project Summary Table -->
                                <table class="table table-bordered mb-4 project-summary-table">
                                    <tbody>
                                        @php
                                            $totalEquipments = 0;
                                            $totalBaseCost = 0;
                                            
                                            foreach($project->quotationItemsGrouped as $groupedItem) {
                                                $itemTotal = $groupedItem['total_qty'] * $groupedItem['product']->price;
                                                $itemTotalDasar = $groupedItem['total_qty'] * $groupedItem['product']->base_price_rupiah_for_luar_negeri;
                                                
                                                $totalEquipments += $itemTotal;
                                                $totalBaseCost += $itemTotalDasar;
                                            }
                                            
                                            $profit = $totalEquipments - $totalBaseCost;
                                        @endphp
                                        
                                        <tr class="bg-warning">
                                            <td colspan="2" class="text-center fw-bold py-2" style="background-color: #ffeb3b; color: #000;">
                                                PERHITUNGAN PROJECT "{{ strtoupper($project->project_name) }}"
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td class="fw-bold" style="width: 70%;">TOTAL PROJECT {{ strtoupper($project->project_name) }} - EQUIPMENTS</td>
                                            <td class="text-end fw-bold">Rp {{ number_format($totalEquipments, 0, ',', '.') }}</td>
                                        </tr>
                                        
                                        <tr>
                                            <td class="fw-bold">TOTAL PROJECT {{ strtoupper($project->project_name) }} - HARGA DASAR EQUIPMENT</td>
                                            <td class="text-end fw-bold">Rp {{ number_format($totalBaseCost, 0, ',', '.') }}</td>
                                        </tr>
                                        
                                        <tr class="table-success">
                                            <td class="fw-bold">PROFIT</td>
                                            <td class="text-end fw-bold">Rp {{ number_format($profit, 0, ',', '.') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <!-- Empty State for Project without Data -->
                            <div class="text-center py-5">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-muted">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14,2 14,8 20,8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10,9 9,9 8,9"></polyline>
                                        </svg>
                                    </div>
                                    <div class="mt-3">
                                        <h5 class="text-muted mb-2">Belum ada data perhitungan</h5>
                                        <p class="text-muted small mb-0">Project <strong>{{ $project->project_name }}</strong> belum memiliki item untuk dihitung.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <!-- Empty State for No Projects -->
                    <div class="text-center py-5">
                        <div class="empty-state">
                            <div class="empty-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-muted">
                                    <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                                </svg>
                            </div>
                            <div class="mt-4">
                                <h5 class="text-muted mb-3">Belum ada project tersedia</h5>
                                <p class="text-muted mb-0">Saat ini belum ada project yang dapat dihitung.<br>Silakan buat project baru atau hubungi administrator.</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>

    <script>
        $(document).ready(function() {
            // Set first project tab as active
            $('.project-tab').first().addClass('active');

            // Handle project tab click
            $('.project-tab').on('click', function() {
                const projectId = $(this).data('project-id');
                
                // Update active state in tabs
                $('.project-tab').removeClass('active');
                $(this).addClass('active');

                // Hide all project contents
                $('.project-content').hide();

                // Show selected project content
                $('#project-content-' + projectId).show();
            });

            // Handle manage PO button
            $(document).on('click', '.btn-manage-po', function() {
                const itemId = $(this).data('id');
                const brand = $(this).data('brand');
                const model = $(this).data('model');
                const required = $(this).data('required');
                const confirmed = $(this).data('confirmed');
                const status = $(this).data('status');
                const poNumber = $(this).data('po-number') || '';
                const poFile = $(this).data('po-file') || '';
                const poFileName = $(this).data('po-filename') || '';

                // Populate modal
                $('#po-order-item-id').val(itemId);
                $('#po-brand').val(brand);
                $('#po-model').val(model);
                $('#po-required').val(required);
                $('#po-confirmed').val(confirmed);
                $('#po-status').val(status);
                $('#po-number').val(poNumber);

                // Update modal title and file input based on whether PO exists
                if (poNumber && poFile) {
                    $('#po-modal-title').text('Update purchase order');
                    $('#po-file-label').html(
                        'Upload new PO file <span class="text-muted">(optional)</span>');
                    $('#po-file').removeAttr('required');
                    $('#current-po-file').show();
                    $('#po-file-link').attr('href', '/storage/' + poFile);
                    $('#po-file-name').text(poFileName || poNumber);
                } else {
                    $('#po-modal-title').text('Add purchase order');
                    $('#po-file-label').html('Upload PO file <span class="text-danger">*</span>');
                    $('#po-file').attr('required', 'required');
                    $('#current-po-file').hide();
                }

                $('#po-file').val('');

                // Show modal
                $('#modal-po-upload').modal('show');
            });

            // Handle PO upload form submission
            $('#po-upload-form').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const itemId = $('#po-order-item-id').val();
                formData.append('order_item_id', itemId);

                const submitBtn = $('#save-po-upload');
                const originalBtnText = submitBtn.html();

                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Saving...'
                );

                $.ajax({
                    url: '{{ route('finance-project-order.upload-po') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#modal-po-upload').modal('hide');

                            const alertHtml = `
                                <div class="alert alert-important alert-success alert-dismissible fade show" role="alert">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-check me-2">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M5 12l5 5l10 -10" />
                                    </svg>
                                    ${response.message}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            `;
                            $('.row').first().before(alertHtml);

                            $('html, body').animate({
                                scrollTop: 0
                            }, 300);
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred while saving PO';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        alert(errorMessage);
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).html(originalBtnText);
                    }
                });
            });
        });
    </script>
@endpush
