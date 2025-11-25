@extends('layouts.app')

@push('styles')
    <style>
        /* Custom styles for the new table design */
        .card-table {
            margin-bottom: 0;
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

        /* Tab styles */
        .nav-tabs .nav-link {
            padding: 0.5rem 1rem;
            font-weight: 500;
        }

        .nav-tabs .nav-link.active {
            color: var(--tblr-primary);
            border-bottom-color: var(--tblr-primary);
        }

        .tab-content {
            padding-top: 1rem;
        }
    </style>
@endpush

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <!-- Page pre-title -->
            <div class="page-pretitle">Finance</div>
            <h2 class="page-title">Items to order</h2>
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
                        <form id="po-upload-form" method="POST" action="{{ route('finance-project-order.upload-po') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="po-order-item-id" name="order_item_id">
                            <div class="modal-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Brand</label>
                                        <input type="text" class="form-control" id="po-brand" disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Model/Type</label>
                                        <input type="text" class="form-control" id="po-model" disabled>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Required quantity</label>
                                        <input type="number" class="form-control" id="po-required" disabled>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Confirmed quantity</label>
                                        <input type="number" class="form-control" id="po-confirmed" disabled>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">To order</label>
                                        <input type="number" class="form-control" id="po-to-order" disabled>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label required">PO number</label>
                                        <input type="text" class="form-control" id="po-number" name="po_number"
                                            placeholder="Enter PO number" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label required">Estimated arrival date</label>
                                        <input type="date" class="form-control" id="po-eta" name="estimated_arrival_date" required>
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
                                    <span id="save-po-text">Save PO</span>
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
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                        @forelse($projects as $index => $project)
                            <li class="nav-item" role="presentation">
                                <a href="#tab-project-{{ $project->id }}"
                                    class="nav-link {{ $index === 0 ? 'active' : '' }}" data-bs-toggle="tab"
                                    aria-selected="{{ $index === 0 ? 'true' : 'false' }}" role="tab">
                                    {{ $project->project_name }}
                                </a>
                            </li>
                        @empty
                            <li class="nav-item" role="presentation">
                                <a href="#tab-no-project" class="nav-link active" data-bs-toggle="tab"
                                    aria-selected="true" role="tab">
                                    No projects available
                                </a>
                            </li>
                        @endforelse
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        @forelse($projects as $index => $project)
                            <div class="tab-pane {{ $index === 0 ? 'active show' : '' }}"
                                id="tab-project-{{ $project->id }}" role="tabpanel">
                                <div class="mb-3">
                                    <x-datatable id="finance-project-order-table-{{ $project->id }}"
                                        title="Items to Order - {{ $project->name }}"
                                        url="{{ route('finance-project-order.datatable', ['project_id' => $project->id]) }}"
                                        :columns="[
                                            ['data' => 'brand', 'name' => 'brand', 'label' => 'Brand'],
                                            ['data' => 'model_type', 'name' => 'model_type', 'label' => 'Model/Type'],
                                            ['data' => 'qty', 'name' => 'qty', 'label' => 'Required'],
                                            [
                                                'data' => 'stock_used_so_far',
                                                'name' => 'stock_used_so_far',
                                                'label' => 'Confirmed',
                                            ],
                                            [
                                                'data' => 'remaining_qty',
                                                'name' => 'remaining_qty',
                                                'label' => 'To Order',
                                            ],
                                            ['data' => 'unit', 'name' => 'unit', 'label' => 'Unit'],
                                            ['data' => 'ead', 'name' => 'ead', 'label' => 'ETA'],
                                            [
                                                'data' => 'action',
                                                'name' => 'action',
                                                'label' => 'Action',
                                                'orderable' => false,
                                                'searchable' => false,
                                            ],
                                        ]" />
                                </div>
                            </div>
                        @empty
                            <div class="tab-pane active show" id="tab-no-project" role="tabpanel">
                                <div class="empty">
                                    <div class="empty-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                            <path
                                                d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                            <path d="M9 9l1 0" />
                                            <path d="M9 13l6 0" />
                                            <path d="M9 17l6 0" />
                                        </svg>
                                    </div>
                                    <p class="empty-title">No items to order</p>
                                    <p class="empty-subtitle text-secondary">
                                        There are currently no items with status "Order sebagian" or "Indent" that require
                                        purchase orders.
                                    </p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTables for all tabs
            let datatables = {};

            $('[id^="finance-project-order-table-"]').each(function() {
                const tableId = $(this).attr('id');
                if ($.fn.DataTable.isDataTable('#' + tableId)) {
                    datatables[tableId] = $('#' + tableId).DataTable();
                }
            });

            // Reload DataTable when tab is shown
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                const targetPane = $(e.target).attr('href');
                const table = $(targetPane).find('[id^="finance-project-order-table-"]');

                if (table.length) {
                    const tableId = table.attr('id');
                    if (datatables[tableId]) {
                        datatables[tableId].ajax.reload(null, false);
                    }
                }
            });

            // Handle manage PO button
            $(document).on('click', '.btn-manage-po', function() {
                const itemId = $(this).data('id');
                const brand = $(this).data('brand');
                const model = $(this).data('model');
                const required = $(this).data('required');
                const confirmed = $(this).data('confirmed');
                const toOrder = $(this).data('to-order');
                const poNumber = $(this).data('po-number') || '';
                const poFile = $(this).data('po-file') || '';
                const poFileName = $(this).data('po-filename') || '';
                const eta = $(this).data('eta') || '';

                // Populate modal
                $('#po-order-item-id').val(itemId);
                $('#po-brand').val(brand);
                $('#po-model').val(model);
                $('#po-required').val(required);
                $('#po-confirmed').val(confirmed);
                $('#po-to-order').val(toOrder);
                $('#po-number').val(poNumber);
                $('#po-eta').val(eta);

                // Update modal title and file input based on whether PO exists
                if (poNumber) {
                    $('#po-modal-title').text('Edit purchase order');
                    $('#save-po-text').text('Update PO');
                    $('#po-file-label').html(
                        'Upload new PO file <span class="text-muted">(optional)</span>');
                    $('#po-file').removeAttr('required');
                    if (poFile) {
                        $('#current-po-file').show();
                        $('#po-file-link').attr('href', '/storage/' + poFile);
                        $('#po-file-name').text(poFileName || poNumber);
                    } else {
                        $('#current-po-file').hide();
                    }
                } else {
                    $('#po-modal-title').text('Add purchase order');
                    $('#save-po-text').text('Save PO');
                    $('#po-file-label').html('Upload PO file <span class="text-danger">*</span>');
                    $('#po-file').attr('required', 'required');
                    $('#current-po-file').hide();
                }

                $('#po-file').val('');

                // Show modal
                $('#modal-po-upload').modal('show');
            });

            // Handle form submission with AJAX
            $('#po-upload-form').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const submitBtn = $('#save-po-upload');
                const originalText = $('#save-po-text').text();

                // Disable submit button
                submitBtn.prop('disabled', true);
                $('#save-po-text').text('Saving...');

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Close modal
                        $('#modal-po-upload').modal('hide');

                        // Show success alert
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message || 'Purchase order has been saved successfully',
                            showConfirmButton: false,
                            timer: 2000
                        });

                        // Reset form
                        $('#po-upload-form')[0].reset();

                        // Reload all datatables
                        Object.keys(datatables).forEach(function(tableId) {
                            if (datatables[tableId]) {
                                datatables[tableId].ajax.reload(null, false);
                            }
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred while saving the purchase order';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = Object.values(xhr.responseJSON.errors).flat();
                            errorMessage = errors.join('<br>');
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: errorMessage,
                            confirmButtonText: 'OK'
                        });
                    },
                    complete: function() {
                        // Re-enable submit button
                        submitBtn.prop('disabled', false);
                        $('#save-po-text').text(originalText);
                    }
                });
            });
        });
    </script>
@endpush
