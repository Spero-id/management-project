@props(['route' => null, 'prospect' => null, 'quotation' => null, 'type' => null])

<form action="{{ $route }}" method="POST" id="quotationForm">
    @csrf
    @if ($quotation)
        @method('PUT')
    @endif
    @if ($prospect)
        <input type="hidden" name="prospect_id" value="{{ $prospect->id }}">
    @endif

    <input type="hidden" name="form_type" value="{{ $type  }}">


    <div>


        <!-- Notes -->
        <div class="row mb-4">
            <div class="col-12">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3"
                    placeholder="Enter quotation notes">{{ old('notes', $quotation?->notes) }}</textarea>
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="me-2">
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1" />
                                <path d="M9 14l2 2 4-4" />
                            </svg>
                            Select Products
                        </button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#productSelectionModal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="me-2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <path d="M14 2v6h6" />
                                <!-- Excel badge -->
                                <rect x="3" y="13" width="8" height="6" rx="1" ry="1"
                                    fill="#16A34A" stroke="none" />
                                <path d="M5.5 15.5L9.5 18.5" stroke="#fff" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M9.5 15.5L5.5 18.5" stroke="#fff" stroke-width="1.8" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            Upload Excel
                        </button>
                        <button type="button" class="btn btn-outline-primary" onclick="addProductRow()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="me-2">
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
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1" />
                            <path d="M9 14l2 2 4-4" />
                        </svg>
                        Select Products
                    </button>
                    <button type="button" class="btn btn-outline-light" onclick="addProductRow()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
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
                            <div>
                                <div class="d-flex justify-content-between p-2 align-items-center">
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


        <div class=" mt-4 d-flex justify-content-end">
            <div>
                <button type="submit" class="btn btn-primary">
                    Simpan Equipment
                </button>
            </div>
        </div>
</form>

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


    {{-- @json() --}}
    {{-- @json($quotation->items->map(fn($item) => [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'product_name' => $item->product->name ?? 'Unknown Product'
                ])->toArray()); --}}
</div>



@pushOnce('styles')
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
@endPushOnce

@pushOnce('scripts')
    <script>
        // ===== GLOBAL VARIABLES =====
        let productRowIndex = 1;
        let allProducts = [];
        let selectedProducts = new Set();
        let existingQuotationProducts = [];

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
            const actionButtons = document.querySelector('.mt-3');

            if (productRows.length > 0) {
                emptyState.style.display = 'none';
                if (actionButtons) actionButtons.style.display = 'block';
            } else {
                emptyState.style.display = 'block';
                if (actionButtons) actionButtons.style.display = 'none';
            }
        }

        // Utility functions

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
                        markup += '<div class="select2-result-product__price small">Price: ' + formatCurrency(
                            product.price) + '</div>';
                    }

                    markup += '</div></div>';
                    return $(markup);
                },
                templateSelection: function(product) {
                    return product.text || product.id;
                }
            });

            $(selectElement).on('select2:select', function(e) {
                var data = e.params.data;
                var row = $(this).closest('.product-row');
                var unitPriceInput = row.find('.unit-price-input');

                if (data.price) {
                    unitPriceInput.val(formatRupiahInput(parseFloat(data.price)));
                    calculateRowSubtotal(row[0]);
                }
                toggleEmptyState();

                if ($('#productSelectionModal').hasClass('show')) {
                    const searchTerm = document.getElementById('productSearchInput').value;
                    loadProducts(searchTerm);
                }
            });

            $(selectElement).on('select2:clear', function(e) {
                toggleEmptyState();

                if ($('#productSelectionModal').hasClass('show')) {
                    const searchTerm = document.getElementById('productSearchInput').value;
                    loadProducts(searchTerm);
                }
            });
        }

        // ===== TABLE MANAGEMENT FUNCTIONS =====
        function addProductRow() {
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

            initializeSelect2(newRow.querySelector('.product-select'));
            addRowEventListeners(newRow);
            updateRemoveButtonState();
            toggleEmptyState();
        }

        function removeProductRow(button) {
            const row = button.closest('tr');

            $(row).find('.product-select').select2('destroy');

            row.remove();
            updateRemoveButtonState();
            calculateTotal();
            toggleEmptyState();

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

            rows.forEach(row => {
                const productSelect = row.querySelector('.product-select');
                const selectedValue = productSelect.value;

                if (!selectedValue || selectedValue === '') {
                    rowsToRemove.push(row);
                }
            });

            const remainingRows = rows.length - rowsToRemove.length;
            if (remainingRows > 0) {
                rowsToRemove.forEach(row => {
                    const productSelect = row.querySelector('.product-select');
                    try {
                        $(productSelect).select2('destroy');
                    } catch (e) {
                        console.log('Select2 destroy error (expected for new rows):', e);
                    }
                    row.remove();
                });
            } else if (rowsToRemove.length > 1) {
                for (let i = 1; i < rowsToRemove.length; i++) {
                    const row = rowsToRemove[i];
                    const productSelect = row.querySelector('.product-select');
                    try {
                        $(productSelect).select2('destroy');
                    } catch (e) {
                        console.log('Select2 destroy error (expected for new rows):', e);
                    }
                    row.remove();
                }
            }

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
                let value = this.value.replace(/[^\d]/g, '');
                if (value) {
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

            loadingIndicator.classList.remove('d-none');
            productsList.innerHTML = '';
            noProductsFound.classList.add('d-none');

            $.ajax({
                url: '{{ route('product.search') }}',
                method: 'GET',
                data: {
                    q: searchTerm,
                    per_page: 50
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

            let text = `${count} product${count !== 1 ? 's' : ''} selected`;
            if (existingCount > 0) {
                text += ` (${existingCount} already in quotation)`;
            }
            countElement.textContent = text;

            countElement.className = count > 0 ? 'badge bg-white fs-6 text-dark border' :
                'badge bg-white fs-6 text-muted border';

            const addButton = document.getElementById('addSelectedProductsBtn');
            addButton.disabled = count === 0;
        }

        function addSelectedProductsToTable() {
            const selectedProductIds = Array.from(selectedProducts);
            const existingProductIds = getExistingProductIds();

            const newProductIds = selectedProductIds.filter(productId =>
                !existingProductIds.has(productId)
            );

            if (newProductIds.length === 0) {
                if (selectedProductIds.length > 0) {
                    alert('Selected products are already in the quotation.');
                } else {
                    alert('Please select at least one product.');
                }
                return;
            }

            removeEmptyProductRows();

            newProductIds.forEach(productId => {
                const product = allProducts.find(p => p.id.toString() === productId);
                if (product) {
                    addProductRowWithData(product);
                }
            });

            selectedProducts.clear();
            updateSelectedProductsCount();

            const closeButton = document.querySelector('#productSelectionModal .btn-close');
            if (closeButton) {
                closeButton.click();
            } else {
                if (typeof $.fn.modal !== 'undefined') {
                    $('#productSelectionModal').modal('hide');
                } else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    const modalElement = document.getElementById('productSelectionModal');
                    const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                    modal.hide();
                }
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

            initializeSelect2(newRow.querySelector('.product-select'));
            addRowEventListeners(newRow);
            updateRemoveButtonState();
            calculateTotal();
            toggleEmptyState();
        }

        $(document).ready(function() {
            @if ($quotation)
                @php
                    $items = $quotation->items
                        ->map(function ($item) {
                            return [
                                'id' => $item->id,
                                'product_id' => $item->product_id,
                                'quantity' => $item->quantity,
                                'unit_price' => $item->unit_price,
                                'product_name' => optional($item->product)->name ?? 'Unknown Product',
                            ];
                        })
                        ->values();
                @endphp


                const existingProducts = {!! json_encode($items, JSON_UNESCAPED_UNICODE) !!};

                existingProducts.forEach((productItem) => {
                    const tableBody = document.getElementById('productTableBody');
                    const newRow = document.createElement('tr');
                    newRow.className = 'product-row';

                    newRow.innerHTML = `
                    <td>
                        <select name="products[${productRowIndex}][product_id]" class="form-select product-select" required>
                            <option value="${productItem.product_id}" selected>${productItem.product_name}</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" name="products[${productRowIndex}][quantity]" 
                               class="form-control quantity-input" value="${productItem.quantity}" min="1" required>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="text" name="products[${productRowIndex}][unit_price]" 
                                   class="form-control unit-price-input" 
                                   value="${formatRupiahInput(parseFloat(productItem.unit_price))}" 
                                   placeholder="0" required>
                        </div>
                    </td>
                    <td>
                        <input type="text" class="form-control subtotal-display" 
                               value="${formatRupiah(parseFloat(productItem.quantity) * parseFloat(productItem.unit_price))}" 
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

                    initializeSelect2(newRow.querySelector('.product-select'));
                    addRowEventListeners(newRow);
                });
            @endif

            $('.product-select').each(function() {
                initializeSelect2(this);
            });

            document.querySelectorAll('.product-row').forEach(row => {
                addRowEventListeners(row);
            });

            updateRemoveButtonState();
            calculateTotal();
            toggleEmptyState();

            $('#productSelectionModal').on('shown.bs.modal', function() {
                loadProducts();
                document.getElementById('productSearchInput').focus();
            });

            $('#productSelectionModal').on('hidden.bs.modal', function() {
                document.getElementById('productSearchInput').value = '';
                selectedProducts.clear();
                updateSelectedProductsCount();
                document.getElementById('productsList').innerHTML = '';
                document.getElementById('noProductsFound').classList.add('d-none');
                document.getElementById('productLoadingIndicator').classList.add('d-none');
            });

            let searchTimeout;
            $('#productSearchInput').on('input', function() {
                const searchTerm = this.value;
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    loadProducts(searchTerm);
                }, 300);
            });

            $('#productSearchInput').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    const searchTerm = this.value;
                    loadProducts(searchTerm);
                }
            });

            $('#searchProductBtn').on('click', function() {
                const searchTerm = document.getElementById('productSearchInput').value;
                loadProducts(searchTerm);
            });

            $('#addSelectedProductsBtn').on('click', function(e) {
                e.preventDefault();
                removeEmptyProductRows();
                addSelectedProductsToTable();
            });

            updateSelectedProductsCount();

            // Form submission validation
            const quotationForm = document.getElementById('quotationForm');
            quotationForm.addEventListener('submit', function(e) {
                let formValid = true;
                const productRows = quotationForm.querySelectorAll('.product-row');

                if (productRows.length === 0) {
                    e.preventDefault();
                    alert('Mohon tambahkan minimal satu produk ke dalam quotation.');
                    return false;
                }

                // Validate each product row
                productRows.forEach(row => {
                    const productSelect = row.querySelector('.product-select');
                    const quantityInput = row.querySelector('.quantity-input');
                    const unitPriceInput = row.querySelector('.unit-price-input');

                    if (!productSelect.value || productSelect.value === '') {
                        productSelect.classList.add('is-invalid');
                        formValid = false;
                    }

                    if (!quantityInput.value || parseInt(quantityInput.value) <= 0) {
                        quantityInput.classList.add('is-invalid');
                        formValid = false;
                    }

                    if (!unitPriceInput.value || parseRupiah(unitPriceInput.value) <= 0) {
                        unitPriceInput.classList.add('is-invalid');
                        formValid = false;
                    }
                });

                if (!formValid) {
                    e.preventDefault();
                    alert('Mohon lengkapi semua data produk dengan benar.');
                    return false;
                }

                // Show loading state
                const submitBtn = quotationForm.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan...';
            });
        });
    </script>
@endPushOnce
