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
            <div class="page-pretitle">Logistics</div>
            <h2 class="page-title">Project Order</h2>
        </div>
        <!-- Page title actions -->
        <div class="col-auto ms-auto d-print-none">
            <!-- BEGIN MODAL -->
            <!-- Stock Management Modal -->
            <div class="modal modal-blur fade" id="modal-stock-management" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="stock-modal-title">Stock management</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="stock-management-form">
                            @csrf
                            <input type="hidden" id="stock-item-id">
                            <input type="hidden" id="stock-project-id">
                            <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Product</label>
                                    <input type="text" class="form-control" id="stock-product-name" disabled>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Brand</label>
                                    <input type="text" class="form-control" id="stock-brand" disabled>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Model/Type</label>
                                    <input type="text" class="form-control" id="stock-model" disabled>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label class="form-label">QTY needed</label>
                                    <input type="number" class="form-control" id="stock-qty-needed" disabled>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">QTY ready</label>
                                    <input type="number" class="form-control" id="stock-qty-ready" disabled>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">QTY remaining</label>
                                    <input type="number" class="form-control" id="stock-qty-remaining" disabled>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Available stock</label>
                                    <input type="number" class="form-control" id="stock-available" disabled>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label required">Use stock quantity</label>
                                    <input type="number" class="form-control" id="stock-use-qty" min="0" placeholder="Enter quantity">
                                    <small class="text-muted">Max: <span id="stock-max-value">0</span></small>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="save-stock-usage">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M5 12l5 5l10 -10" />
                                </svg>
                                Save
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div class="modal modal-blur fade" id="modal-delete-project" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="modal-status bg-danger"></div>
                        <div class="modal-body text-center py-4">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/alert-triangle -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24"
                                height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 9v2m0 4v.01" />
                                <path
                                    d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" />
                            </svg>
                            <h3>Apakah Anda yakin?</h3>
                            <div class="text-secondary">
                                Anda akan menghapus project <strong id="deleteProjectName"></strong>.
                                Tindakan ini tidak dapat dibatalkan dan akan menghapus semua file terkait.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="w-100">
                                <div class="row">
                                    <div class="col">
                                        <button type="button" class="btn w-100" data-bs-dismiss="modal">
                                            Batal
                                        </button>
                                    </div>
                                    <div class="col">
                                        <form id="deleteProjectForm" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger w-100">
                                                Ya, hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END MODAL -->
        </div>
    </div>
@endsection

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                        @forelse($projectOrder as $index => $project)
                            <li class="nav-item" role="presentation">
                                <a href="#tab-project-{{ $project->project->id }}"
                                    class="nav-link {{ $index === 0 ? 'active' : '' }}" data-bs-toggle="tab"
                                    aria-selected="{{ $index === 0 ? 'true' : 'false' }}" role="tab">
                                    {{ $project->project->project_name }}
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
                        @forelse($projectOrder as $index => $project)
                            <div class="tab-pane {{ $index === 0 ? 'active show' : '' }}"
                                id="tab-project-{{ $project->project->id }}" role="tabpanel">
                                <x-datatable id="project-order-table-{{ $project->id }}"
                                    title="Project Order - {{ $project->name }}"
                                    url="{{ route('project-order.datatable', ['project_id' => $project->id]) }}"
                                    :columns="[
                                        ['data' => 'brand', 'name' => 'brand', 'label' => 'Brand'],
                                        ['data' => 'model_type', 'name' => 'model_type', 'label' => 'Model/Type'],
                                        ['data' => 'qty_needed', 'name' => 'qty_needed', 'label' => 'QTY needed'],
                                        ['data' => 'qty_ready', 'name' => 'qty_ready', 'label' => 'QTY ready'],
                                        ['data' => 'remaining_qty', 'name' => 'remaining_qty', 'label' => 'QTY remaining'],
                                        ['data' => 'unit', 'name' => 'unit', 'label' => 'Unit'],
                                        ['data' => 'status', 'name' => 'status', 'label' => 'Status'],
                                        ['data' => 'bobot', 'name' => 'bobot', 'label' => 'BOBOT', 'orderable' => false, 'searchable' => false],
                                        [
                                            'data' => 'action',
                                            'name' => 'action',
                                            'label' => 'Action',
                                            'orderable' => false,
                                            'searchable' => false,
                                        ],
                                    ]" />
                                <div class="mt-3 d-flex justify-content-between align-items-center">
                                    <div class="delivery-status">
                                        <span class="text-muted">Delivery progress:</span>
                                        <strong class="ms-2 delivery-percentage text-danger"
                                            data-project-id="{{ $project->id }}">0%</strong>
                                        <span class="text-muted">items shipped</span>
                                    </div>
                                    <button type="button" class="btn btn-primary btn-confirm-order" data-project-id="{{ $project->project->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-check me-1">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M5 12l5 5l10 -10" />
                                        </svg>
                                        Confirm Order
                                    </button>
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
                                    <p class="empty-title">No projects available</p>
                                    <p class="empty-subtitle text-secondary">
                                        Please create a project first to view project orders.
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
    
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>

    <script>
        $(document).ready(function() {
            // Show SweetAlert for session messages
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2000
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}',
                    showConfirmButton: false,
                    timer: 2000
                });
            @endif
            // Initialize DataTables for all tabs
            let datatables = {};

            $('[id^="project-order-table-"]').each(function() {
                const tableId = $(this).attr('id');
                if ($.fn.DataTable.isDataTable('#' + tableId)) {
                    datatables[tableId] = $('#' + tableId).DataTable();
                }
            });

            // Function to update delivery percentage
            function updateDeliveryPercentage(projectId) {
                $.ajax({
                    url: '{{ route('project-order.index') }}',
                    type: 'GET',
                    data: {
                        project_id: projectId,
                        calculate_percentage: true
                    },
                    success: function(response) {
                        if (response.percentage !== undefined) {
                            $('.delivery-percentage[data-project-id="' + projectId + '"]').text(response
                                .percentage + '%');
                        }
                    }
                });
            }

            // Reload DataTable when tab is shown
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                const targetPane = $(e.target).attr('href');
                const table = $(targetPane).find('[id^="project-order-table-"]');

                if (table.length) {
                    const tableId = table.attr('id');
                    const projectId = $(targetPane).find('.project-order-form').data('project-id');

                    if (datatables[tableId]) {
                        datatables[tableId].ajax.reload(null, false);
                    }

                    if (projectId) {
                        updateDeliveryPercentage(projectId);
                    }
                }
            });

            // Update percentage on page load for active tab
            const activeProjectId = $('.project-order-form:visible').data('project-id');
            if (activeProjectId) {
                updateDeliveryPercentage(activeProjectId);
            }

            // Validate input on change (legacy support - jika ada)
            $(document).on('input change', '.stok-digunakan-input', function() {
                const input = $(this);
                const value = parseInt(input.val()) || 0;
                const qty = parseInt(input.data('qty')) || 0;
                const stok = parseInt(input.data('stok')) || 0;
                const maxAllowed = Math.min(qty, stok);

                if (value > maxAllowed) {
                    input.val(maxAllowed);

                    // Show warning
                    const warning = $('<small class="text-danger d-block">Max: ' + maxAllowed + '</small>');
                    input.parent().find('small').remove();
                    input.parent().append(warning);

                    setTimeout(function() {
                        warning.fadeOut(function() {
                            $(this).remove();
                        });
                    }, 3000);
                } else if (value < 0) {
                    input.val(0);
                }
            });

            // Handle Confirm Order
            $(document).on('click', '.btn-confirm-order', function(e) {
                e.preventDefault();
                
                const projectId = $(this).data('project-id');
                const submitBtn = $(this);
                const originalBtnText = submitBtn.html();
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action will notify Finance to process the items.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, confirm it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (!result.isConfirmed) {
                        return;
                    }
                    
                    submitBtn.prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Confirming...'
                    );
                    
                    $.ajax({
                        url: '/project-order/confirm/' + projectId,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage,
                            showConfirmButton: true
                        });
                        
                        submitBtn.prop('disabled', false).html(originalBtnText);
                    }
                });
                });
            });

            // Stock Management Modal Logic
            let currentStockItem = null;
            
            // Handle stock management button click
            $(document).on('click', '.btn-manage-stock', function() {
                const itemId = $(this).data('id');
                const brand = $(this).data('brand');
                const model = $(this).data('model');
                const productName = $(this).data('product');
                const stock = parseInt($(this).data('stock')) || 0;
                const qty = parseInt($(this).data('qty')) || 0;
                const qtyReady = parseInt($(this).data('qty-ready')) || 0;
                const qtyRemaining = parseInt($(this).data('qty-remaining')) || 0;
                const stockUsed = parseInt($(this).data('stock-used')) || 0;
                const projectId = $(this).data('project-id');
                const isComplete = $(this).data('is-complete') == '1';
                
                currentStockItem = {
                    id: itemId,
                    projectId: projectId,
                    stock: stock,
                    qty: qty,
                    qtyReady: qtyReady,
                    qtyRemaining: qtyRemaining,
                    isComplete: isComplete
                };
                
                // Populate modal
                $('#stock-item-id').val(itemId);
                $('#stock-project-id').val(projectId);
                $('#stock-product-name').val(productName);
                $('#stock-brand').val(brand);
                $('#stock-model').val(model);
                $('#stock-qty-needed').val(qty);
                $('#stock-qty-ready').val(qtyReady);
                $('#stock-qty-remaining').val(qtyRemaining);
                $('#stock-available').val(stock);
                $('#stock-use-qty').val(0).attr('max', Math.min(stock, qtyRemaining));
                $('#stock-max-value').text(Math.min(stock, qtyRemaining));
                
                // Update modal based on complete status
                if (isComplete) {
                    $('#stock-modal-title').text('View stock details');
                    $('#stock-use-qty').prop('disabled', true);
                    $('#save-stock-usage').hide();
                } else {
                    $('#stock-modal-title').text(stockUsed > 0 ? 'Edit stock management' : 'Stock management');
                    $('#stock-use-qty').prop('disabled', false);
                    $('#save-stock-usage').show();
                }
                
                // Show modal
                $('#modal-stock-management').modal('show');
            });
            
            // Reset form when modal is closed
            $('#modal-stock-management').on('hidden.bs.modal', function() {
                $('#stock-use-qty').val(0);
                currentStockItem = null;
            });
            
            // Validate stock usage input
            $('#stock-use-qty').on('input change', function() {
                if (!currentStockItem) return;
                if (currentStockItem.isComplete) return; // Prevent editing if complete
                
                const value = parseInt($(this).val()) || 0;
                const maxAllowed = Math.min(currentStockItem.stock, currentStockItem.qtyRemaining);
                
                if (value > maxAllowed) {
                    $(this).val(maxAllowed);
                } else if (value < 0) {
                    $(this).val(0);
                }
            });
            
            // Handle form submission for stock management
            $('#stock-management-form').on('submit', function(e) {
                e.preventDefault();
                
                const stockUseQty = parseInt($('#stock-use-qty').val()) || 0;
                
                if (stockUseQty <= 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid quantity',
                        text: 'Please enter a valid stock quantity',
                        showConfirmButton: true
                    });
                    return;
                }
                
                const submitBtn = $('#save-stock-usage');
                const originalBtnText = submitBtn.html();
                
                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Saving...'
                );
                
                $.ajax({
                    url: '{{ route('project-order.store') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        project_id: currentStockItem.projectId,
                        items: [{
                            quotation_item_id: currentStockItem.id,
                            stock_used: stockUseQty,
                            estimated_arrival_date: null
                        }]
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#modal-stock-management').modal('hide');
                            
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 2000
                            });
                            
                            // Reload datatable
                            const tableId = 'project-order-table-' + currentStockItem.projectId;
                            if (datatables[tableId]) {
                                datatables[tableId].ajax.reload(null, false);
                            }
                            
                            // Update delivery percentage
                            updateDeliveryPercentage(currentStockItem.projectId);
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage,
                            showConfirmButton: true
                        });
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).html(originalBtnText);
                    }
                });
            });
        });
    </script>
@endpush
