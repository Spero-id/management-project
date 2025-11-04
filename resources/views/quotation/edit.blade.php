@extends('layouts.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <!-- Page pre-title -->
            <div class="page-pretitle">Edit</div>
            <h2 class="page-title">Quotation {{ $quotation->quotation_number }}</h2>
        </div>
        <!-- Page title actions -->
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <a href="{{ route('quotation.show', $quotation->id) }}" class="btn btn-outline-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-2">
                        <path d="M9 14l-4 -4l4 -4" />
                        <path d="M5 10h11a4 4 0 1 1 0 8h-1" />
                    </svg>
                    Back to Quotation
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="tabs">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#quotation-tab" role="tab"
                    aria-selected="true">Quotation</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#installation-tab" role="tab"
                    aria-selected="false">Installation</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="quotation-tab" role="tabpanel">
                <div class="row ">
                    <div class="col-12">
                        <form action="{{ route('quotation.update', $quotation->id) }}" method="POST" class="card"
                            style="border-top-left-radius: 0; border-top-right-radius: 0;" id="quotationForm">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="is_revision" value="{{ request()->input('is_revision', '1') }}">

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

                                <!-- Need Installation / Accommodation -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Need Accommodation</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="need_accommodation"
                                                id="accommodationSwitch" value="1"
                                                {{ old('need_accommodation', $quotation->need_accommodation ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="accommodationSwitch">
                                                Include installation in quotation
                                            </label>
                                        </div>
                                        @error('need_accommodation')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label required">Status</label>
                                        <select name="status" class="form-select @error('status') is-invalid @enderror"
                                            required>
                                            <option value="draft"
                                                {{ old('status', $quotation->status) == 'draft' ? 'selected' : '' }}>Draft
                                            </option>
                                            <option value="sent"
                                                {{ old('status', $quotation->status) == 'sent' ? 'selected' : '' }}>Sent
                                            </option>
                                            <option value="accepted"
                                                {{ old('status', $quotation->status) == 'accepted' ? 'selected' : '' }}>
                                                Accepted</option>
                                            <option value="rejected"
                                                {{ old('status', $quotation->status) == 'rejected' ? 'selected' : '' }}>
                                                Rejected</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>


                                <!-- Accommodation Details (hidden by default, shown when switch enabled) -->
                                <div id="accommodationDetails"
                                    class="row mb-4 {{ old('need_accommodation', $quotation->need_accommodation ?? false) ? '' : 'd-none' }}">
                                    <div class="col-md-6">
                                        <label class="form-label">Wilayah / AREA</label>
                                        <input type="text" name="accommodation_wilayah" class="form-control"
                                            placeholder="Enter area"
                                            value="{{ old('accommodation_wilayah', $quotation->accommodation_wilayah ?? '') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Jumlah Kamar Hotel</label>
                                        <input type="number" id="accommodation_rooms" name="accommodation_hotel_rooms"
                                            class="form-control" placeholder="Enter number of rooms"
                                            value="{{ old('accommodation_hotel_rooms', $quotation->accommodation_hotel_rooms ?? '') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Jumlah Orang</label>
                                        <input type="number" name="accommodation_people" id="accommodation_people"
                                            class="form-control" placeholder="Enter number of people"
                                            value="{{ old('accommodation_people', $quotation->accommodation_people ?? '') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Target Pekerjaan (Hari)</label>
                                        <input type="number" id="accommodation_target_days"
                                            name="accommodation_target_days" class="form-control"
                                            placeholder="Enter target days"
                                            value="{{ old('accommodation_target_days', $quotation->accommodation_target_days ?? '') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Harga Tiket Pesawat</label>
                                        <input type="number" id="accommodation_ticket_price"
                                            name="accommodation_plane_ticket_price" class="form-control"
                                            placeholder="Enter ticket price"
                                            value="{{ old('accommodation_plane_ticket_price', $quotation->accommodation_plane_ticket_price ?? '') }}">
                                    </div>
                                </div>

                                <!-- Accommodation Cost Table -->
                                <div id="accommodationCostTable"
                                    class="row mb-4 {{ old('need_accommodation', $quotation->need_accommodation ?? false) ? '' : 'd-none' }}">
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
                                                        <input type="string" name="total_hotel_price"
                                                            id="total_hotel_price_input" class="form-control"
                                                            value="{{ old('total_hotel_price', $quotation->accommodationItems->firstWhere('name', 'Total Harga Hotel') ? number_format($quotation->accommodationItems->firstWhere('name', 'Total Harga Hotel')->unit_price, 0, ',', '.') : $quotation->total_hotel_price ?? '0') }}">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Harga Pesawat</td>
                                                    <td>
                                                        <input type="string" name="total_flight_price"
                                                            id="flight_price_input" class="form-control"
                                                            value="{{ old('total_flight_price', $quotation->accommodationItems->firstWhere('name', 'Harga Pesawat') ? number_format($quotation->accommodationItems->firstWhere('name', 'Harga Pesawat')->unit_price, 0, ',', '.') : $quotation->total_flight_price ?? '0') }}">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Harga Transportasi Kendaraan</td>
                                                    <td>
                                                        <input type="string" name="total_transportation_price"
                                                            id="total_transportation_price" class="form-control"
                                                            value="{{ old('total_transportation_price', $quotation->accommodationItems->firstWhere('name', 'Harga Transportasi Kendaraan') ? number_format($quotation->accommodationItems->firstWhere('name', 'Harga Transportasi Kendaraan')->unit_price, 0, ',', '.') : $quotation->total_transportation_price ?? '0') }}">
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
                                            placeholder="Enter quotation notes">{{ old('notes', $quotation->notes) }}</textarea>
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
                                                    @foreach ($quotation->items as $index => $item)
                                                        <tr class="product-row">
                                                            <td>
                                                                <select name="products[{{ $index }}][product_id]"
                                                                    class="form-select product-select @error('products.{{ $index }}.product_id') is-invalid @enderror"
                                                                    required>
                                                                    <option value="{{ $item->product_id }}" selected>
                                                                        {{ $item->product->name }}</option>
                                                                </select>
                                                                @error('products.{{ $index }}.product_id')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </td>
                                                            <td>
                                                                <input type="number"
                                                                    name="products[{{ $index }}][quantity]"
                                                                    class="form-control quantity-input @error('products.{{ $index }}.quantity') is-invalid @enderror"
                                                                    value="{{ old('products.' . $index . '.quantity', $item->quantity) }}"
                                                                    min="1" required>
                                                                @error('products.{{ $index }}.quantity')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </td>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input type="text"
                                                                        name="products[{{ $index }}][unit_price]"
                                                                        class="form-control unit-price-input @error('products.{{ $index }}.unit_price') is-invalid @enderror"
                                                                        value="{{ old('products.' . $index . '.unit_price', number_format($item->unit_price, 0, ',', '.')) }}"
                                                                        placeholder="0" required>
                                                                </div>
                                                                @error('products.{{ $index }}.unit_price')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </td>
                                                            <td>
                                                                <input type="text"
                                                                    class="form-control subtotal-display"
                                                                    value="{{ number_format($item->subtotal, 0, ',', '.') }}"
                                                                    readonly style="background-color: #f8f9fa;">
                                                            </td>
                                                            <td>
                                                                <button type="button"
                                                                    class="btn btn-outline-danger remove-row"
                                                                    onclick="removeProductRow(this)"
                                                                    {{ $quotation->items->count() == 1 ? 'disabled' : '' }}>
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                        height="16" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round">
                                                                        <path d="M18 6 6 18" />
                                                                        <path d="m6 6 12 12" />
                                                                    </svg>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Empty State Message -->
                                        <div id="emptyProductsState" class="text-center py-5 border rounded bg-light"
                                            style="display: none;">

                                            <h4 class="text-muted mb-3">No Products Added</h4>
                                            <p class="text-muted mb-4">
                                                Start by adding products to your quotation. You can either select from
                                                existing products
                                                or add them manually.
                                            </p>
                                            <div class="d-flex justify-content-center gap-3">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#productSelectionModal">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="me-2">
                                                        <path
                                                            d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                                                        <rect x="8" y="2" width="8" height="4" rx="1"
                                                            ry="1" />
                                                        <path d="M9 14l2 2 4-4" />
                                                    </svg>
                                                    Select Products
                                                </button>
                                                <button type="button" class="btn btn-outline-primary"
                                                    onclick="addProductRow()">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="me-2">
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
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path
                                                        d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                                                    <rect x="8" y="2" width="8" height="4" rx="1"
                                                        ry="1" />
                                                    <path d="M9 14l2 2 4-4" />
                                                </svg>
                                                Select Products
                                            </button>
                                            <button type="button" class="btn btn-outline-primary"
                                                onclick="addProductRow()">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                                                            <strong class="text-lg">Total Amount:</strong>
                                                            <strong class="text-lg text-primary" id="totalAmount">Rp
                                                                {{ number_format($quotation->total_amount, 0, ',', '.') }}</strong>
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
                                        <a href="{{ route('quotation.show', $quotation->id) }}"
                                            class="btn btn-link">Cancel</a>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="installation-tab" role="tabpanel">
                <form action="{{ route('installation.store') }}" method="POST" class="card"
                    style="border-top-left-radius: 0; border-top-right-radius: 0;" id="installationForm">
                    @csrf
                    <input type="hidden" name="quotation_id" value="{{ $quotation->id }}">
                    <input type="hidden" name="is_revision" value="{{ request()->input('is_revision', '1') }}">

                    <div class="card-header">
                        <h3 class="card-title">Installation</h3>
                    </div>
                    <!-- Installation Content -->
                    <div class="card-body ">
                        <div class="col-12">
                            <div class="row mb-4">
                                <div class="col-md-12" id="installationPercentageContainer">
                                    <label class="form-label">Installation Percentage (%)</label>
                                    <div class="input-group">
                                        <input type="number" name="installation_percentage" id="installationPercentage"
                                            class="form-control" min="0" max="100" step="0.1"
                                            value="{{ old('installation_percentage', $quotation->installation_percentage ?? 0) }}">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    @error('installation_percentage')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <label class="form-label">Installations</label>

                            <div class="table-responsive">
                                <table class="table table-vcenter" id="installationsTable">
                                    <thead>
                                        <tr>
                                            <th>Installation</th>
                                            <th width="120">Quantity</th>
                                            <th width="150">Unit Price</th>
                                            <th width="150">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody id="installationTableBody">
                                        @if ($quotation->installationItems && $quotation->installationItems->count() > 0)
                                            @foreach ($quotation->installationItems as $index => $item)
                                                <tr class="installation-row" data-proportional="{{ $item->installation->proportional ?? 0 }}">
                                                    <td>
                                                        <select readonly
                                                            name="installations[{{ $index }}][installation_id]"
                                                            class="form-select installation-select" required>
                                                            <option value="{{ $item->installation_id }}" selected>
                                                                {{ $item->installation->name ?? 'Unknown Installation' }}
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input readonly type="number"
                                                            name="installations[{{ $index }}][quantity]"
                                                            class="form-control installation-quantity-input"
                                                            value="{{ old('installations.' . $index . '.quantity', $item->quantity) }}"
                                                            min="1" required>
                                                    </td>
                                                    <td>
                                                        <div readonly class="input-group">
                                                            <input type="text"
                                                                name="installations[{{ $index }}][unit_price]"
                                                                class="form-control installation-unit-price-input"
                                                                value="{{ old('installations.' . $index . '.unit_price', number_format($item->unit_price, 0, ',', '.')) }}"
                                                                placeholder="0" required>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            class="form-control installation-subtotal-display"
                                                            value="{{ number_format($item->subtotal, 0, ',', '.') }}"
                                                            readonly style="background-color: #f8f9fa;">
                                                    </td>

                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>


                            <div class="row mt-4">
                                <div class="col-md-8"></div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <strong class="text-lg">Installation Total:</strong>
                                                <strong class="text-lg text-success" id="installationTotalAmount">Rp
                                                    {{ $quotation->installationItems ? number_format($quotation->installationItems->sum('subtotal'), 0, ',', '.') : '0' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @error('installations')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="{{ route('quotation.show', $quotation->id) }}" class="btn btn-link">Cancel</a>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
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
                        <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">
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
        document.addEventListener('DOMContentLoaded', function() {
            const accommodationSwitch = document.getElementById('accommodationSwitch');
            const accommodationDetails = document.getElementById('accommodationDetails');
            const accommodationCostTable = document.getElementById('accommodationCostTable');

            function toggleAccommodation(checked) {
                if (!accommodationDetails || !accommodationCostTable) return;

                if (checked) {
                    accommodationDetails.classList.remove('d-none');
                    accommodationCostTable.classList.remove('d-none');
                } else {
                    accommodationDetails.classList.add('d-none');
                    accommodationCostTable.classList.add('d-none');
                }
            }

            if (accommodationSwitch) {
                toggleAccommodation(accommodationSwitch.checked);
                accommodationSwitch.addEventListener('change', function() {
                    toggleAccommodation(this.checked);
                });
            }
        });


        document.getElementById('accommodation_target_days').addEventListener('input', calculateHotelPrice);
        document.getElementById('accommodation_people').addEventListener('input', function() {
            calculateTicketPrice();
            calculateTransportationPrice();
            calculateHotelPrice();
        });
        document.getElementById('accommodation_ticket_price').addEventListener('input', calculateTicketPrice);





        function calculateHotelPrice() {
            console.log('Calculating Hotel Price...');
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

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(number);
        }




        // ================= Instalation ====================

        document.getElementById('installationPercentage').addEventListener('input', recalculateInstalationUnitPrice);


        function recalculateInstalationUnitPrice() {

            // Get products total (remove non-digit chars)
            const totalAmountEl = document.getElementById('totalAmount');
            let productsTotal = 0;
            if (totalAmountEl) {
                const raw = totalAmountEl.innerText || '';
                const digits = raw.replace(/[^0-9]/g, '');
                productsTotal = parseInt(digits || '0', 10);
            }

            const percentageInput = document.getElementById('installationPercentage');
            const percentage = parseFloat(percentageInput ? percentageInput.value : 0) || 0;

            // total installation budget to distribute
            const totalInstallationBudget = Math.round(productsTotal * (percentage / 100));

            const rows = Array.from(document.querySelectorAll('.installation-row'));
            if (rows.length === 0) return;

            // collect proportional weights
            let totalProportional = 0;
            const rowInfos = rows.map(row => {
                const propAttr = row.dataset.proportional;
                const proportional = propAttr !== undefined && propAttr !== null && propAttr !== '' ? parseFloat(propAttr) : 0;
                totalProportional += (isNaN(proportional) ? 0 : proportional);
                return { row, proportional: (isNaN(proportional) ? 0 : proportional) };
            });

            // if totalProportional is zero, distribute equally
            if (totalProportional === 0) {
                totalProportional = rows.length;
                rowInfos.forEach(r => r.proportional = 1);
            }

            let allocatedSum = 0;

            rowInfos.forEach((info, idx) => {
                const { row, proportional } = info;

                const qtyInput = row.querySelector('.installation-quantity-input');
                const qty = Math.max(1, parseFloat(qtyInput ? qtyInput.value : 1) || 1);

                // allocate amount based on proportion
                // for last row, use remaining to avoid rounding loss
                let allocated = Math.round(totalInstallationBudget * (proportional / totalProportional));
                if (idx === rowInfos.length - 1) {
                    allocated = totalInstallationBudget - allocatedSum;
                }

                allocatedSum += allocated;

                const unitPrice = Math.round(allocated / qty);

                const unitPriceInput = row.querySelector('.installation-unit-price-input');
                if (unitPriceInput) unitPriceInput.value = formatRupiah(unitPrice);

                const subtotalDisplay = row.querySelector('.installation-subtotal-display');
                if (subtotalDisplay) subtotalDisplay.value = formatRupiah(allocated);
            });

            // update installation total amount display
            const installationTotalEl = document.getElementById('installationTotalAmount');
            if (installationTotalEl) {
                installationTotalEl.innerText = 'Rp ' + formatRupiah(allocatedSum);
            }
        }

        // Run once to initialize values on page load
        try {
            recalculateInstalationUnitPrice();
        } catch (e) {
            // silent
            console.error(e);
        }
    </script>
@endpush
