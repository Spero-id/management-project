@extends('layouts.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <!-- Page pre-title -->
            <div class="page-pretitle">Create</div>
            <h2 class="page-title">Quotation</h2>
        </div>
    </div>
@endsection

@section('content')
    <div class="row row-cards">
        <div class="col-12">
            <form action="{{ route('quotation.store') }}" method="POST" class="card" id="quotationForm">
                @csrf
                <input type="hidden" name="prospect_id" value="{{ $prospect->id }}">

                <div class="card-header">
                    <h3 class="card-title">Quotation Information</h3>
                </div>

                <div class="card-body">
                    <!-- Prospect Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Customer Name</label>
                            <div class="fw-bold">{{ $prospect->customer_name ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company</label>
                            <div class="fw-bold">{{ $prospect->company ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <!-- Need Installation -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Need Accommodation</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="need_accommodation"
                                    id="accommodationSwitch" value="1"
                                    {{ old('need_accommodation') ? 'checked' : '' }}>
                                <label class="form-check-label" for="accommodationSwitch">
                                    Include installation in quotation
                                </label>
                            </div>
                            @error('need_accommodation')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>


                    </div>

                    <!-- Accommodation Details -->
                    <div id="accommodationDetails" class="row mb-4 d-none">
                        <div class="col-md-6">
                            <label class="form-label">Wilayah / AREA</label>
                            <input type="text" name="accommodation_wilayah" class="form-control"
                                placeholder="Enter area">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jumlah Kamar Hotel</label>
                            <input type="number" id="accommodation_rooms" name="accommodation_hotel_rooms"
                                class="form-control" placeholder="Enter number of rooms">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jumlah Orang</label>
                            <input type="number" name="accommodation_people" id="accommodation_people" class="form-control"
                                placeholder="Enter number of people">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Target Pekerjaan (Hari)</label>
                            <input type="number" id="accommodation_target_days" name="accommodation_target_days"
                                class="form-control" placeholder="Enter target days">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Harga Tiket Pesawat</label>
                            <input type="number" id="accommodation_ticket_price" name="accommodation_plane_ticket_price"
                                class="form-control" placeholder="Enter ticket price">
                        </div>
                    </div>

                    <!-- Accommodation Cost Table -->
                    <div id="accommodationCostTable" class="row mb-4 d-none">
                        <div class="col-12">
                            <label class="form-label">Accommodation Costs</label>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Total Harga Hotel</td>
                                        <td>
                                            <input type="string" name="total_hotel_price" id="total_hotel_price_input"
                                                class="form-control" value="0">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Harga Pesawat</td>
                                        <td>
                                            <input type="string" name="total_flight_price" id="flight_price_input"
                                                class="form-control" value="0">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Harga Transportasi Kendaraan</td>
                                        <td>
                                            <input type="string" name="total_transportation_price"
                                                id="total_transportation_price" class="form-control" value="0">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3"
                                placeholder="Enter quotation notes">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Products Section -->
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label required">Products</label>

                            <div class="table-responsive">
                                <table class="table table-vcenter" id="productsTable">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th width="120">Quantity</th>
                                            <th width="150">Unit Price</th>
                                            <th width="150">Subtotal</th>
                                            <th width="80">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="productTableBody">

                                    </tbody>
                                </table>
                            </div>

                            <!-- Empty State Message -->
                            <div id="emptyProductsState" class="text-center py-5 border rounded bg-light">

                                <h4 class="text-muted mb-3">No Products Added</h4>
                                <p class="text-muted mb-4">
                                    Start by adding products to your quotation. You can either select from existing products
                                    or add them manually.
                                </p>
                                <div class="d-flex justify-content-center gap-3">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#productSelectionModal">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="me-2">
                                            <path
                                                d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                                            <rect x="8" y="2" width="8" height="4" rx="1"
                                                ry="1" />
                                            <path d="M9 14l2 2 4-4" />
                                        </svg>
                                        Select Products
                                    </button>
                                    <button type="button" class="btn btn-outline-light" onclick="addProductRow()">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="me-2">
                                            <path d="M12 5v14" />
                                            <path d="M5 12h14" />
                                        </svg>
                                        Add Manually
                                    </button>
                                </div>
                            </div>

                            <!-- Product Action Buttons -->
                            <div class="mt-3">
                                <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal"
                                    data-bs-target="#productSelectionModal">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1" />
                                        <path d="M9 14l2 2 4-4" />
                                    </svg>
                                    Select Products
                                </button>
                                <button type="button" class="btn btn-outline-light" onclick="addProductRow()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 5v14" />
                                        <path d="M5 12h14" />
                                    </svg>
                                    Add Product Manually
                                </button>
                            </div>

                            <!-- Total Section -->
                            <div class="row mt-4">
                                <div class="col-md-8"></div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <strong class="text-lg">Total Amount: </strong>
                                                <strong class="text-lg text-primary" id="totalAmount">Rp 0</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @error('products')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="{{ route('quotation.index') }}" class="btn btn-link">Cancel</a>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">Create Quotation</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Product Selection Modal -->
    <div class="modal fade" id="productSelectionModal" tabindex="-1" aria-labelledby="productSelectionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productSelectionModalLabel">Select Products</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Search Input -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold mb-2">Search Products</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="productSearchInput"
                                placeholder="Type product name to search...">

                        </div>
                        <small class="text-muted">Search by product name to find items quickly</small>
                    </div>

                    <!-- Loading Indicator -->
                    <div id="productLoadingIndicator" class="d-none">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item product-list-item text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="spinner-border text-primary mb-3" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="text-muted">Loading products...</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Products List -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold mb-2">Available Products</label>
                        <div class="small text-muted mb-2">
                            <i class="ti ti-info-circle me-1"></i>
                            Products already added to quotation are marked with "Already Added" badge and cannot be selected
                            again.
                        </div>
                        <div id="productsList" class="max-height-400 overflow-auto">
                            <!-- Products will be loaded here -->
                        </div>
                    </div>

                    <!-- No Results -->
                    <div id="noProductsFound" class="d-none">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item product-list-item text-center py-5">
                                <div class="d-flex flex-column align-items-center text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"
                                        stroke-linecap="round" stroke-linejoin="round" class="mb-3">
                                        <circle cx="11" cy="11" r="8" />
                                        <path d="m21 21-4.35-4.35" />
                                    </svg>
                                    <div>No products found</div>
                                    <small class="text-muted mt-2">Try adjusting your search terms</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="me-auto d-flex align-items-center">
                        <div class="me-3">
                            <span id="selectedProductsCount" class="badge bg-white fs-6 text-dark border">0 products
                                selected</span>
                        </div>
                        <small class="text-muted">Select products and click "Add Selected Products" to continue</small>
                    </div>
                    <div>
                        <button type="button" class="btn btn-outline-primary me-2" data-bs-dismiss="modal">
                            <i class="ti ti-x me-1"></i>Cancel
                        </button>
                        <button type="button" class="btn btn-primary" id="addSelectedProductsBtn">
                            <i class="ti ti-plus me-1"></i>Add Selected Products
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .max-height-400 {
            max-height: 400px;
        }




        .product-checkbox {
            cursor: pointer;
            transform: scale(1.1);
        }

        .product-list-item {
            border: none !important;
            border-bottom: 1px solid #e9ecef !important;
            padding: 1rem 1.25rem !important;
            transition: all 0.2s ease-in-out;
        }


        .product-list-item:last-child {
            border-bottom: none !important;
        }

        .product-info {
            padding-left: 0.5rem;
        }

        .product-info .fw-semibold {
            font-size: 0.95rem;
            line-height: 1.3;
        }

        .product-info .small {
            font-size: 0.8rem;
            line-height: 1.2;
        }

        .product-selection-modal .modal-body {
            padding: 1.5rem;
        }

        #productsList {
            border-radius: 0.5rem;
            overflow: hidden;
            border: 1px solid #e9ecef;
        }

        #productsList .list-group {
            margin-bottom: 0;
        }

        .badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
        }

        .product-checkbox:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        /* Loading and empty states */
        #productLoadingIndicator .product-list-item,
        #noProductsFound .product-list-item {
            min-height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e9ecef;
            border-radius: 0.5rem;
            background-color: #f8f9fa;
        }

        #productLoadingIndicator .spinner-border {
            width: 2rem;
            height: 2rem;
        }

        /* Search input styling */
        #productSearchInput {
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            border: 2px solid #e9ecef;
            transition: border-color 0.2s ease-in-out;
        }

        #productSearchInput:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
        }

        #searchProductBtn {
            border-radius: 0 0.5rem 0.5rem 0;
            border: 2px solid #e9ecef;
            border-left: none;
        }

        /* Modal improvements */
        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
        }

        .modal-footer {
            background-color: #f8f9fa;
            border-top: 2px solid #e9ecef;
            padding: 1rem 1.5rem;
        }

        #selectedProductsCount {
            font-weight: 500;
            color: #6c757d;
        }

        /* Already added products styling */
        .product-list-item.bg-light {
            background-color: #f8f9fa !important;
            border-left: 3px solid #198754 !important;
        }

        .product-list-item.bg-light .product-checkbox:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .product-list-item.bg-light .product-info {
            opacity: 0.8;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // ===== GLOBAL VARIABLES =====
        let productRowIndex = 1;
        let allProducts = [];
        let selectedProducts = new Set();



        // Utility to check if need_accommodation mode is active
        function isAccommodationEnabled() {
            const el = document.getElementById('accommodationSwitch');
            return el && el.checked;
        }

        // Returns numeric products total (not formatted)
        function getProductsTotalValue() {
            let total = 0;
            document.querySelectorAll('.product-row').forEach(row => {
                const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
                const unitPrice = parseRupiah(row.querySelector('.unit-price-input').value) || 0;
                total += quantity * unitPrice;
            });
            return total;
        }

        // ===== UTILITY FUNCTIONS =====
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        }

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(number);
        }

        function formatRupiahInput(number) {
            return new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(number);
        }

        function parseRupiah(rupiahString) {
            return parseFloat(rupiahString.replace(/[.,]/g, '')) || 0;
        }

        // ===== EMPTY STATE FUNCTIONS =====
        function toggleEmptyState() {
            const productRows = document.querySelectorAll('.product-row');
            const emptyState = document.getElementById('emptyProductsState');
            const actionButtons = document.querySelector('.mt-3'); // The action buttons container

            // If there are any product rows at all, hide empty state
            // This ensures the empty state disappears immediately when adding manual products
            if (productRows.length > 0) {
                // Hide empty state, show action buttons
                emptyState.style.display = 'none';
                if (actionButtons) actionButtons.style.display = 'block';
            } else {
                // Show empty state, hide action buttons
                emptyState.style.display = 'block';
                if (actionButtons) actionButtons.style.display = 'none';
            }
        }

        // buatkan agar jika accommodation_rooms dan accommodation_target_days terisi, maka hitung harga hotel
        document.getElementById('accommodation_target_days').addEventListener('input', calculateHotelPrice);
        document.getElementById('accommodation_people').addEventListener('input', function() {
            calculateTicketPrice();
            calculateTransportationPrice();
            calculateHotelPrice();
        });
        document.getElementById('accommodation_ticket_price').addEventListener('input', calculateTicketPrice);


        function calculateHotelPrice() {

            // Calculate rooms from people: 2 people per room, round up if odd
            const people = parseInt(document.getElementById('accommodation_people').value) || 0;
            const rooms = Math.ceil(people / 2);
            const days = parseInt(document.getElementById('accommodation_target_days').value) || 0;
            
            const roomInput = document.querySelector('input[name="accommodation_hotel_rooms"]');
            if (roomInput) roomInput.value = rooms;

            if (rooms > 0 && days > 0) {
                const hotelPrice = {{ $accommodationCategory[0]->price ?? 0 }} * days * rooms;
                console.log('Calculated Hotel Price:', hotelPrice);

                const hotelInput = document.querySelector('input[name="total_hotel_price"]');

                if (hotelInput) hotelInput.value = formatRupiah(hotelPrice);
            }
        }





        function calculateTicketPrice() {
            const people = parseInt(document.getElementById('accommodation_people').value) || 0;
            const ticket = parseInt(document.getElementById('accommodation_ticket_price').value) || 0;

            if (people > 0 && ticket > 0) {
                const TicketPrice = ticket * people * 2;

                const flightInput = document.querySelector('input[name="total_flight_price"]');
                if (flightInput) flightInput.value = formatRupiah(TicketPrice);
            }
        }


        function calculateTransportationPrice() {
            const people = parseInt(document.getElementById('accommodation_people').value) || 0;

            if (people > 0) {
                const transportationPrice = {{ $accommodationCategory[1]->price ?? 0 }} * people;

                const transportationInput = document.querySelector('input[name="total_transportation_price"]');
                if (transportationInput) transportationInput.value = formatRupiah(transportationPrice);
            }
        }





        // ===== SELECT2 FUNCTIONS =====
        function initializeSelect2(selectElement) {
            $(selectElement).select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Search and select product...',
                allowClear: true,
                ajax: {
                    url: '{{ route('product.search') }}',
                    dataType: 'json',
                    delay: 300,
                    data: function(params) {
                        return {
                            q: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: true
                },
                templateResult: function(product) {
                    if (product.loading) return product.text;

                    var markup = '<div class="select2-result-product clearfix">' +
                        '<div class="select2-result-product__meta">' +
                        '<div class="select2-result-product__title">' + product.text + '</div>';

                    if (product.price) {
                        markup += '<div class="select2-result-product__price  small">Price: ' + formatCurrency(
                            product.price) + '</div>';
                    }

                    markup += '</div></div>';
                    return $(markup);
                },
                templateSelection: function(product) {
                    return product.text || product.id;
                }
            });

            // Handle selection
            $(selectElement).on('select2:select', function(e) {
                var data = e.params.data;
                var row = $(this).closest('.product-row');
                var unitPriceInput = row.find('.unit-price-input');

                if (data.price) {
                    unitPriceInput.val(formatRupiahInput(parseFloat(data.price)));
                    calculateRowSubtotal(row[0]);
                }
                toggleEmptyState();

                // If modal is open, refresh the products list to update checkbox states
                if ($('#productSelectionModal').hasClass('show')) {
                    const searchTerm = document.getElementById('productSearchInput').value;
                    loadProducts(searchTerm);
                }
            });

            // Handle clearing selection
            $(selectElement).on('select2:clear', function(e) {
                toggleEmptyState();

                // If modal is open, refresh the products list to update checkbox states
                if ($('#productSelectionModal').hasClass('show')) {
                    const searchTerm = document.getElementById('productSearchInput').value;
                    loadProducts(searchTerm);
                }
            });
        }

        // ===== TABLE MANAGEMENT FUNCTIONS =====
        function addProductRow() {
            // Remove empty rows first to avoid duplicates
            removeEmptyProductRows();

            const tableBody = document.getElementById('productTableBody');
            const newRow = document.createElement('tr');
            newRow.className = 'product-row';

            newRow.innerHTML = `
            <td>
                <select name="products[${productRowIndex}][product_id]" class="form-select product-select" required>
                    <option value="">Select Product</option>
                </select>
            </td>
            <td>
                <input type="number" name="products[${productRowIndex}][quantity]" 
                       class="form-control quantity-input" value="0" min="1" required>
            </td>
            <td>
                <div class="input-group">
                    <input type="text" name="products[${productRowIndex}][unit_price]" 
                           class="form-control unit-price-input" placeholder="0" required>
                </div>
            </td>
            <td>
                <input type="text" class="form-control subtotal-display" 
                       value="0.00" readonly style="background-color: #f8f9fa;">
            </td>
            <td>
                <button type="button" class="btn btn-outline-danger remove-row" 
                        onclick="removeProductRow(this)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" 
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>
            </td>
        `;

            tableBody.appendChild(newRow);
            productRowIndex++;

            // Initialize Select2 for the new row
            initializeSelect2(newRow.querySelector('.product-select'));

            // Add event listeners to new row
            addRowEventListeners(newRow);
            updateRemoveButtonState();

            // Hide empty state immediately when adding a manual row
            toggleEmptyState();
        }

        function removeProductRow(button) {
            const row = button.closest('tr');

            // Destroy Select2 before removing the row
            $(row).find('.product-select').select2('destroy');

            row.remove();
            updateRemoveButtonState();
            calculateTotal();
            toggleEmptyState();

            // If modal is open, refresh the products list to update checkbox states
            if ($('#productSelectionModal').hasClass('show')) {
                const searchTerm = document.getElementById('productSearchInput').value;
                loadProducts(searchTerm);
            }
        }

        function updateRemoveButtonState() {
            const rows = document.querySelectorAll('.product-row');
            rows.forEach((row, index) => {
                const removeButton = row.querySelector('.remove-row');
                removeButton.disabled = rows.length === 1;
            });
        }

        function removeEmptyProductRows() {
            const rows = document.querySelectorAll('.product-row');
            const rowsToRemove = [];

            // First pass: identify rows to remove
            rows.forEach(row => {
                const productSelect = row.querySelector('.product-select');
                const selectedValue = productSelect.value;

                // Check if the product select is empty (no product selected)
                if (!selectedValue || selectedValue === '') {
                    rowsToRemove.push(row);
                }
            });

            // Only remove if we have more than one row and we're not removing all rows
            const remainingRows = rows.length - rowsToRemove.length;
            if (remainingRows > 0) {
                // Remove all empty rows
                rowsToRemove.forEach(row => {
                    const productSelect = row.querySelector('.product-select');
                    // Destroy Select2 before removing the row
                    try {
                        $(productSelect).select2('destroy');
                    } catch (e) {
                        console.log('Select2 destroy error (expected for new rows):', e);
                    }
                    row.remove();
                    console.log('Removed empty product row');
                });

                console.log(`Removed ${rowsToRemove.length} empty product rows`);
            } else if (rowsToRemove.length > 1) {
                // If all rows are empty, keep only one
                for (let i = 1; i < rowsToRemove.length; i++) {
                    const row = rowsToRemove[i];
                    const productSelect = row.querySelector('.product-select');
                    try {
                        $(productSelect).select2('destroy');
                    } catch (e) {
                        console.log('Select2 destroy error (expected for new rows):', e);
                    }
                    row.remove();
                    console.log('Removed extra empty product row');
                }
                console.log(`Kept one empty row, removed ${rowsToRemove.length - 1} extra empty rows`);
            }

            // Update remove button states after removing empty rows
            updateRemoveButtonState();
            calculateTotal();
            toggleEmptyState();
        }


        function addRowEventListeners(row) {
            const quantityInput = row.querySelector('.quantity-input');
            const unitPriceInput = row.querySelector('.unit-price-input');

            quantityInput.addEventListener('input', function() {
                calculateRowSubtotal(row);
            });

            unitPriceInput.addEventListener('input', function() {
                // Remove non-numeric characters except decimal points and commas
                let value = this.value.replace(/[^\d]/g, '');
                if (value) {
                    // Format as number with thousand separators
                    this.value = formatRupiahInput(parseInt(value));
                }
                calculateRowSubtotal(row);
            });

            unitPriceInput.addEventListener('blur', function() {
                if (!this.value || this.value === '0') {
                    this.value = '0';
                }
                calculateRowSubtotal(row);
            });
        }

        // ===== CALCULATION FUNCTIONS =====
        function calculateRowSubtotal(row) {
            const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const unitPrice = parseRupiah(row.querySelector('.unit-price-input').value) || 0;
            const subtotal = quantity * unitPrice;

            row.querySelector('.subtotal-display').value = formatRupiah(subtotal);
            calculateTotal();
        }

        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('.product-row').forEach(row => {
                const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
                const unitPrice = parseRupiah(row.querySelector('.unit-price-input').value) || 0;
                total += quantity * unitPrice;
            });

            document.getElementById('totalAmount').textContent = formatCurrency(total);


        }

        // ===== PRODUCT SELECTION MODAL FUNCTIONS =====
        function getExistingProductIds() {
            const existingProductIds = new Set();
            document.querySelectorAll('.product-row').forEach(row => {
                const productSelect = row.querySelector('.product-select');
                if (productSelect && productSelect.value && productSelect.value !== '') {
                    existingProductIds.add(productSelect.value);
                }
            });
            return existingProductIds;
        }

        function loadProducts(searchTerm = '') {
            const loadingIndicator = document.getElementById('productLoadingIndicator');
            const productsList = document.getElementById('productsList');
            const noProductsFound = document.getElementById('noProductsFound');

            // Show loading
            loadingIndicator.classList.remove('d-none');
            productsList.innerHTML = '';
            noProductsFound.classList.add('d-none');

            // Make AJAX request
            $.ajax({
                url: '{{ route('product.search') }}',
                method: 'GET',
                data: {
                    q: searchTerm,
                    per_page: 50 // Load more products for selection
                },
                success: function(response) {
                    loadingIndicator.classList.add('d-none');

                    if (response.results && response.results.length > 0) {
                        allProducts = response.results;
                        renderProductsList(allProducts);
                    } else {
                        noProductsFound.classList.remove('d-none');
                    }
                },
                error: function() {
                    loadingIndicator.classList.add('d-none');
                    noProductsFound.classList.remove('d-none');
                    alert('Error loading products. Please try again.');
                }
            });
        }

        function renderProductsList(products) {
            const productsList = document.getElementById('productsList');
            const existingProductIds = getExistingProductIds();

            let html = '<div class="list-group list-group-flush">';

            products.forEach(product => {
                const isSelected = selectedProducts.has(product.id.toString());
                const isExisting = existingProductIds.has(product.id.toString());
                const isChecked = isSelected || isExisting;

                html += `
                <div class="list-group-item product-list-item ${isExisting ? 'bg-light' : ''}">
                    <div class="row align-items-center g-3">
                        <div class="col-auto">
                            <input type="checkbox" class="form-check-input product-checkbox" 
                                   value="${product.id}" ${isChecked ? 'checked' : ''}
                                   ${isExisting ? 'disabled' : ''}
                                   onchange="toggleProductSelection('${product.id}', this.checked)">
                        </div>
                        <div class="col">
                            <div class="product-info">
                                <div class="fw-semibold text-dark mb-1">
                                    ${product.text}
                                </div>
                                ${product.price ? `<div class="text-muted small">
                                                                                                                    <i class="ti ti-currency-dollar me-1"></i>
                                                                                                                    Price: ${formatCurrency(product.price)}
                                                                                                                </div>` : `<div class="text-muted small">Price not available</div>`}
                            </div>
                        </div>
                        <div class="col-auto">
                           
                        </div>
                    </div>
                </div>
            `;
            });

            html += '</div>';
            productsList.innerHTML = html;
        }

        function toggleProductSelection(productId, isSelected) {
            const existingProductIds = getExistingProductIds();

            // Don't allow toggling if product already exists in table
            if (existingProductIds.has(productId)) {
                return;
            }

            if (isSelected) {
                selectedProducts.add(productId);
            } else {
                selectedProducts.delete(productId);
            }
            updateSelectedProductsCount();
        }

        function updateSelectedProductsCount() {
            const count = selectedProducts.size;
            const countElement = document.getElementById('selectedProductsCount');
            const existingProductIds = getExistingProductIds();
            const existingCount = existingProductIds.size;

            // Update badge text and style
            let text = `${count} product${count !== 1 ? 's' : ''} selected`;
            if (existingCount > 0) {
                text += ` (${existingCount} already in quotation)`;
            }
            countElement.textContent = text;

            // Change badge color based on selection count - keeping white background
            countElement.className = count > 0 ? 'badge bg-white fs-6 text-dark border' :
                'badge bg-white fs-6 text-muted border';

            // Enable/disable add button
            const addButton = document.getElementById('addSelectedProductsBtn');
            addButton.disabled = count === 0;
        }

        function addSelectedProductsToTable() {
            const selectedProductIds = Array.from(selectedProducts);
            const existingProductIds = getExistingProductIds();

            console.log('Adding products to table:', selectedProductIds);

            // Filter out products that are already in the table
            const newProductIds = selectedProductIds.filter(productId =>
                !existingProductIds.has(productId)
            );

            // Check if any new products are selected
            if (newProductIds.length === 0) {
                if (selectedProductIds.length > 0) {
                    alert('Selected products are already in the quotation.');
                } else {
                    alert('Please select at least one product.');
                }
                return;
            }

            // Remove empty product rows before adding new ones to avoid clutter
            removeEmptyProductRows();

            // Add each new selected product to the table
            newProductIds.forEach(productId => {
                const product = allProducts.find(p => p.id.toString() === productId);
                if (product) {
                    addProductRowWithData(product);
                }
            });

            // Clear selections and update count
            selectedProducts.clear();
            updateSelectedProductsCount();

            // Show success message (optional)
            const addedCount = newProductIds.length;
            console.log(`Added ${addedCount} products successfully`);

            // Close modal - using trigger method for better compatibility
            console.log('Closing modal...');

            // Trigger the close button click to ensure proper modal closing
            const closeButton = document.querySelector('#productSelectionModal .btn-close');
            if (closeButton) {
                closeButton.click();
            } else {
                // Fallback methods
                if (typeof $.fn.modal !== 'undefined') {
                    $('#productSelectionModal').modal('hide');
                } else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    const modalElement = document.getElementById('productSelectionModal');
                    const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                    modal.hide();
                }
            }

            // Optional: Show success toast/alert
            if (addedCount > 0) {
                console.log(`${addedCount} product${addedCount > 1 ? 's' : ''} added successfully!`);
            }
        }

        function addProductRowWithData(productData) {
            const tableBody = document.getElementById('productTableBody');
            const newRow = document.createElement('tr');
            newRow.className = 'product-row';

            newRow.innerHTML = `
            <td>
                <select name="products[${productRowIndex}][product_id]" class="form-select product-select" required>
                    <option value="${productData.id}" selected>${productData.text}</option>
                </select>
            </td>
            <td>
                <input type="number" name="products[${productRowIndex}][quantity]" 
                       class="form-control quantity-input" value="0" min="1" required>
            </td>
            <td>
                <div class="input-group">
                    <input type="text" name="products[${productRowIndex}][unit_price]" 
                           class="form-control unit-price-input" 
                           value="${productData.price ? formatRupiahInput(parseFloat(productData.price)) : '0'}" 
                           placeholder="0" required>
                </div>
            </td>
            <td>
                <input type="text" class="form-control subtotal-display" 
                       value="${productData.price ? formatRupiah(parseFloat(productData.price)) : '0.00'}" 
                       readonly style="background-color: #f8f9fa;">
            </td>
            <td>
                <button type="button" class="btn btn-outline-danger remove-row" 
                        onclick="removeProductRow(this)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" 
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>
            </td>
        `;

            tableBody.appendChild(newRow);
            productRowIndex++;

            // Initialize Select2 for the new row
            initializeSelect2(newRow.querySelector('.product-select'));

            // Add event listeners to new row
            addRowEventListeners(newRow);
            updateRemoveButtonState();
            calculateTotal();
            toggleEmptyState();
        }



        // ===== INITIALIZATION =====
        $(document).ready(function() {
            // Initialize Select2 for existing rows
            $('.product-select').each(function() {
                initializeSelect2(this);
            });

            // Add event listeners to existing rows
            document.querySelectorAll('.product-row').forEach(row => {
                addRowEventListeners(row);
            });

            updateRemoveButtonState();
            calculateTotal();
            toggleEmptyState();


            // Modal event listeners
            $('#productSelectionModal').on('shown.bs.modal', function() {
                // Remove empty product rows before showing product selection

                // Load products when modal is shown
                loadProducts();

                // Focus on search input
                document.getElementById('productSearchInput').focus();
            });

            // Reset modal when hidden
            $('#productSelectionModal').on('hidden.bs.modal', function() {
                // Clear search input
                document.getElementById('productSearchInput').value = '';

                // Clear selections
                selectedProducts.clear();
                updateSelectedProductsCount();

                // Clear products list
                document.getElementById('productsList').innerHTML = '';
                document.getElementById('noProductsFound').classList.add('d-none');
                document.getElementById('productLoadingIndicator').classList.add('d-none');
            });

            // Search functionality
            let searchTimeout;
            $('#productSearchInput').on('input', function() {
                const searchTerm = this.value;

                // Clear previous timeout
                clearTimeout(searchTimeout);

                // Set new timeout for search
                searchTimeout = setTimeout(() => {
                    loadProducts(searchTerm);
                }, 300);
            });

            // Handle Enter key on search input
            $('#productSearchInput').on('keypress', function(e) {
                if (e.which === 13) { // Enter key
                    e.preventDefault();
                    const searchTerm = this.value;
                    loadProducts(searchTerm);
                }
            });

            // Search button click
            $('#searchProductBtn').on('click', function() {
                const searchTerm = document.getElementById('productSearchInput').value;
                loadProducts(searchTerm);
            });

            // Add selected products button
            $('#addSelectedProductsBtn').on('click', function(e) {
                console.log('Add Selected Products button clicked');
                e.preventDefault();
                removeEmptyProductRows();
                addSelectedProductsToTable();
            });


            // Initialize selected products count
            updateSelectedProductsCount();
        });

        // Toggle visibility of accommodation details based on checkbox state
        document.getElementById('accommodationSwitch').addEventListener('change', function() {
            const detailsSection = document.getElementById('accommodationDetails');
            const costTable = document.getElementById('accommodationCostTable');
            if (this.checked) {
                detailsSection.classList.remove('d-none');
                costTable.classList.remove('d-none');
            } else {
                detailsSection.classList.add('d-none');
                costTable.classList.add('d-none');
            }
        });

        // Toggle visibility of accommodation cost table based on checkbox state
        document.getElementById('accommodationSwitch').addEventListener('change', function() {
            const costTable = document.getElementById('accommodationCostTable');
            if (this.checked) {
                costTable.classList.remove('d-none');
            } else {
                costTable.classList.add('d-none');
            }
        });
    </script>
@endpush
