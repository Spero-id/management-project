@extends('layouts.app')

@push('styles')
    <style>
        .card-table {
            margin-bottom: 0;
        }
    </style>
@endpush

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <div class="page-pretitle">Logistics</div>
            <h2 class="page-title">Delivery Order</h2>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#modal-create-delivery-order">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 5l0 14" />
                        <path d="M5 12l14 0" />
                    </svg>
                    Create delivery order
                </button>
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

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Delivery Order List</h3>
                </div>
                <div class="card-body">
                    <x-datatable id="delivery-order-table" title="Delivery Orders"
                        url="{{ route('delivery-order.datatable') }}" :columns="[
                            ['data' => 'do_number', 'name' => 'do_number', 'label' => 'DO Number'],
                            ['data' => 'project_name', 'name' => 'project.project_name', 'label' => 'Project'],
                            ['data' => 'delivery_date', 'name' => 'delivery_date', 'label' => 'Delivery Date'],
                            [
                                'data' => 'items_count',
                                'name' => 'items_count',
                                'label' => 'Total Items',
                                'orderable' => false,
                                'searchable' => false,
                            ],
                            ['data' => 'created_at', 'name' => 'created_at', 'label' => 'Created At'],
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
        </div>
    </div>

    <!-- View Delivery Order Modal -->
    <div class="modal modal-blur fade" id="modal-view-delivery-order" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delivery order details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="view-do-content">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="mt-2">Loading delivery order details...</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-print-do">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                            <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                            <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
                        </svg>
                        Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Delivery Order Modal -->
    <div class="modal modal-blur fade" id="modal-create-delivery-order" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create delivery order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="delivery-order-form">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required">Project</label>
                                <select class="form-select" id="do-project-id" name="project_id" required>
                                    <option value="">Select project</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label required">Delivery date</label>
                                <input type="date" class="form-control" id="do-delivery-date" name="delivery_date"
                                    required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label required">DO number</label>
                                <input type="text" class="form-control" id="do-number" name="do_number"
                                    placeholder="DO-2025-001" required>
                            </div>
                        </div>

                        <hr class="my-4">
                        <h4 class="mb-3">Items</h4>
                        <div id="items-container">
                            <div class="alert alert-info">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path>
                                    <path d="M12 9h.01"></path>
                                    <path d="M11 12h1v4h1"></path>
                                </svg>
                                <div>Select a project to load items</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submit-delivery-order">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Create delivery order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let currentProjectItems = [];

            // Initialize Select2 for project dropdown
            $('#do-project-id').select2({
                dropdownParent: $('#modal-create-delivery-order'),
                theme: 'bootstrap-5',
                placeholder: 'Select project',
                allowClear: true,
                width: '100%',
                ajax: {
                    url: '{{ route('delivery-order.projects') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });

            // Load project items when project is selected
            $('#do-project-id').on('select2:select select2:clear', function(e) {
                const projectId = $(this).val();
                if (!projectId) {
                    $('#items-container').html(
                        '<div class="alert alert-info">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">' +
                        '<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>' +
                        '<path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path>' +
                        '<path d="M12 9h.01"></path>' +
                        '<path d="M11 12h1v4h1"></path>' +
                        '</svg>' +
                        '<div>Select a project to load items</div>' +
                        '</div>'
                    );
                    return;
                }

                loadProjectItems(projectId);
            });

            function loadProjectItems(projectId) {
                $('#items-container').html(
                    '<div class="text-center py-4">' +
                    '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>' +
                    '<div class="mt-2">Loading items...</div>' +
                    '</div>'
                );

                $.ajax({
                    url: `/delivery-order/project-items/${projectId}`,
                    method: 'GET',
                    success: function(response) {
                        if (response.success && response.data.items.length > 0) {
                            currentProjectItems = response.data.items;
                            renderItems(response.data.items);
                        } else {
                            $('#items-container').html(
                                '<div class="alert alert-warning">' +
                                '<svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">' +
                                '<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>' +
                                '<path d="M12 9v2m0 4v.01" /></path>' +
                                '<path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></path>' +
                                '</svg>' +
                                '<div>No quotation items found for this project</div>' +
                                '</div>'
                            );
                        }
                    },
                    error: function(xhr) {
                        $('#items-container').html(
                            '<div class="alert alert-danger">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">' +
                            '<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>' +
                            '<path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /></path>' +
                            '<path d="M12 8v4" /></path>' +
                            '<path d="M12 16h.01" /></path>' +
                            '</svg>' +
                            '<div>Failed to load items. Please try again.</div>' +
                            '</div>'
                        );
                    }
                });
            }

            function renderItems(items) {
                let html = '<div class="table-responsive"><table class="table table-bordered"><thead><tr>' +
                    '<th width="20%">Product</th><th width="10%">Brand</th><th width="10%">Model/Type</th><th width="8%">Required</th><th width="8%">Delivered</th><th width="8%">Remaining</th><th width="8%">QTY</th><th width="20%">Serial Numbers</th><th width="8%">Notes</th>' +
                    '</tr></thead><tbody>';

                items.forEach((item, index) => {
                    const remainingQty = item.remaining_qty;
                    const deliveredInfo = item.delivered_qty > 0 
                        ? `<span >${item.delivered_qty} </span>` 
                        : '<span class="text-muted">None</span>';
                    
                    html += `
                        <tr>
                            <td>
                                ${item.product_name}
                                <input type="hidden" name="items[${index}][quotation_item_id]" value="${item.id}">
                                <input type="hidden" name="items[${index}][product_id]" value="${item.product_id}">
                            </td>
                            <td>${item.brand}</td>
                            <td>${item.model_type}</td>
                            <td><span class="badge bg-blue-lt">${item.quantity}</span></td>
                            <td>${deliveredInfo}</td>
                            <td><span class="badge bg-warning-lt">${remainingQty}</span></td>
                            <td>
                                <input type="number" class="form-control form-control-sm item-qty" 
                                    name="items[${index}][qty]" 
                                    data-index="${index}" 
                                    data-max="${remainingQty}"
                                    min="1" max="${remainingQty}" value="${remainingQty}" required>
                                <small class="text-muted">Max: ${remainingQty}</small>
                            </td>
                            <td>
                                <div class="sn-inputs" id="sn-inputs-${index}"></div>
                            </td>
                            <td>
                                <textarea class="form-control form-control-sm" name="items[${index}][notes]" rows="2" placeholder="Notes (optional)"></textarea>
                            </td>
                        </tr>
                    `;
                });

                html += '</tbody></table></div>';
                $('#items-container').html(html);

                items.forEach((item, index) => {
                    generateSNInputs(index, item.remaining_qty);
                });

                $('.item-qty').on('change input', function() {
                    const index = $(this).data('index');
                    const qty = parseInt($(this).val()) || 0;
                    const maxQty = parseInt($(this).data('max')) || 0;

                    if (qty > maxQty) {
                        $(this).val(maxQty);
                        generateSNInputs(index, maxQty);
                    } else if (qty < 1) {
                        $(this).val(1);
                        generateSNInputs(index, 1);
                    } else {
                        generateSNInputs(index, qty);
                    }
                });
            }

            function generateSNInputs(itemIndex, quantity) {
                const snContainer = $(`#sn-inputs-${itemIndex}`);
                snContainer.empty();

                for (let i = 0; i < quantity; i++) {
                    const snHtml = `
                        <div class="mb-2">
                            <input type="text" class="form-control form-control-sm" 
                                name="items[${itemIndex}][sn][]" 
                                placeholder="SN ${i + 1}" 
                                required>
                        </div>
                    `;
                    snContainer.append(snHtml);
                }
            }

            $('#delivery-order-form').on('submit', function(e) {
                e.preventDefault();

                const submitBtn = $('#submit-delivery-order');
                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Creating...'
                );

                const formData = $(this).serializeArray();
                const payload = {
                    project_id: $('#do-project-id').val(),
                    delivery_date: $('#do-delivery-date').val(),
                    do_number: $('#do-number').val(),
                    items: []
                };

                const itemsMap = {};
                formData.forEach(field => {
                    const match = field.name.match(/items\[(\d+)\]\[(.+?)\](\[\])?$/);
                    if (match) {
                        const index = match[1];
                        const fieldName = match[2];
                        const isArray = match[3] === '[]';

                        if (!itemsMap[index]) {
                            itemsMap[index] = {
                                sn: []
                            };
                        }

                        if (isArray && fieldName === 'sn') {
                            if (field.value && field.value.trim()) {
                                itemsMap[index].sn.push(field.value.trim());
                            }
                        } else if (!isArray) {
                            itemsMap[index][fieldName] = field.value;
                        }
                    }
                });

                payload.items = Object.values(itemsMap);

                $.ajax({
                    url: '{{ route('delivery-order.store') }}',
                    method: 'POST',
                    data: JSON.stringify(payload),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#modal-create-delivery-order').modal('hide');
                            $('#delivery-order-form')[0].reset();
                            $('#items-container').html(
                                '<div class="alert alert-info">' +
                                '<svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">' +
                                '<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>' +
                                '<path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path>' +
                                '<path d="M12 9h.01"></path>' +
                                '<path d="M11 12h1v4h1"></path>' +
                                '</svg>' +
                                '<div>Select a project to load items</div>' +
                                '</div>'
                            );

                            const alertHtml = `
                                <div class="alert alert-important alert-success alert-dismissible fade show" role="alert">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-check me-2">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M5 12l5 5l10 -10" />
                                    </svg>
                                    ${response.message}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            `;
                            $('.modal-backdrop.fade.show').remove();
                            $('.row').first().before(alertHtml);

                            if ($.fn.DataTable.isDataTable('#delivery-order-table')) {
                                $('#delivery-order-table').DataTable().ajax.reload();
                            }


                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Failed to create delivery order';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        const alertHtml = `
                            <div class="alert alert-important alert-danger alert-dismissible fade show" role="alert">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-alert-circle me-2">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                    <path d="M12 8v4" />
                                    <path d="M12 16h.01" />
                                </svg>
                                ${errorMessage}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `;
                        $('.row').first().before(alertHtml);

                        $('html, body').animate({
                            scrollTop: 0
                        }, 300);
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).html(
                            '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">' +
                            '<path stroke="none" d="M0 0h24v24H0z" fill="none"/>' +
                            '<path d="M12 5l0 14" />' +
                            '<path d="M5 12l14 0" />' +
                            '</svg>' +
                            'Create delivery order'
                        );
                    }
                });
            });

            $('#modal-create-delivery-order').on('hidden.bs.modal', function() {
                $('#delivery-order-form')[0].reset();
                $('#do-project-id').val(null).trigger('change');
                $('#items-container').html(
                    '<div class="alert alert-info">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">' +
                    '<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>' +
                    '<path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path>' +
                    '<path d="M12 9h.01"></path>' +
                    '<path d="M11 12h1v4h1"></path>' +
                    '</svg>' +
                    '<div>Select a project to load items</div>' +
                    '</div>'
                );
            });

            // View Delivery Order
            $(document).on('click', '.btn-view-do', function() {
                const doId = $(this).data('id');
                $('#modal-view-delivery-order').modal('show');
                loadDeliveryOrderDetails(doId);
            });

            function loadDeliveryOrderDetails(doId) {
                $('#view-do-content').html(
                    '<div class="text-center py-4">' +
                    '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>' +
                    '<div class="mt-2">Loading delivery order details...</div>' +
                    '</div>'
                );

                $.ajax({
                    url: `/delivery-order/${doId}`,
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            renderDeliveryOrderDetails(response.data);
                        }
                    },
                    error: function(xhr) {
                        $('#view-do-content').html(
                            '<div class="alert alert-danger">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">' +
                            '<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>' +
                            '<path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /></path>' +
                            '<path d="M12 8v4" /></path>' +
                            '<path d="M12 16h.01" /></path>' +
                            '</svg>' +
                            '<div>Failed to load delivery order details</div>' +
                            '</div>'
                        );
                    }
                });
            }

            function renderDeliveryOrderDetails(data) {
                let itemsHtml = '';
                data.items.forEach((item, index) => {
                    const snList = item.sn && item.sn.length > 0 
                        ? item.sn.map(sn => `<span class="badge bg-blue-lt me-1 mb-1">${sn}</span>`).join('')
                        : '<span class="text-muted">No serial numbers</span>';

                    itemsHtml += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.product_name}</td>
                            <td>${item.brand}</td>
                            <td>${item.model_type}</td>
                            <td>${item.qty}</td>
                            <td>${snList}</td>
                            <td>${item.notes}</td>
                        </tr>
                    `;
                });

                const html = `
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label fw-bold">DO Number</label>
                                <div>${data.do_number}</div>
                            </div>
                            <div class="mb-2">
                                <label class="form-label fw-bold">Project</label>
                                <div>${data.project_name}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label fw-bold">Delivery Date</label>
                                <div>${data.delivery_date}</div>
                            </div>
                            <div class="mb-2">
                                <label class="form-label fw-bold">Created At</label>
                                <div>${data.created_at}</div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h4 class="mb-3">Items</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-vcenter">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="20%">Product</th>
                                    <th width="10%">Brand</th>
                                    <th width="10%">Model/Type</th>
                                    <th width="5%">QTY</th>
                                    <th width="35%">Serial Numbers</th>
                                    <th width="15%">Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${itemsHtml}
                            </tbody>
                        </table>
                    </div>
                `;

                $('#view-do-content').html(html);
            }

            // Print Delivery Order
            $('#btn-print-do').on('click', function() {
                const content = $('#view-do-content').html();
                const printWindow = window.open('', '_blank');
                printWindow.document.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Delivery Order</title>
                        <link href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css" rel="stylesheet">
                        <style>
                            @media print {
                                .btn { display: none; }
                            }
                            body { padding: 20px; }
                        </style>
                    </head>
                    <body>
                        <h2>Delivery Order</h2>
                        ${content}
                      
                    </body>
                    </html>
                `);
                printWindow.document.close();
            });
        });
    </script>
@endpush
