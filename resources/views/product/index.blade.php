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
    </style>
@endpush

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <!-- Page pre-title -->
            <div class="page-pretitle">Overview</div>
            <h2 class="page-title">Products</h2>
        </div>
        <!-- Page title actions -->
        <div class="col-auto ms-auto d-print-none">

            <div class="btn-list">

                @can('CREATE_PRODUCT')
                    <a href="#" class="btn btn-success btn-5 d-none d-sm-inline-block" data-bs-toggle="modal"
                        data-bs-target="#modal-import-product">
                        <!-- Download SVG icon from http://tabler.io/icons/icon/plus -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-file-excel">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2" />
                            <path d="M10 12l4 5" />
                            <path d="M10 17l4 -5" />
                        </svg>
                        Import
                    </a>

                    <!-- Modal Trigger for Create Product -->
                    <a href="#" class="btn btn-primary btn-5 d-none d-sm-inline-block" data-bs-toggle="modal"
                        data-bs-target="#modal-create-product">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-2">
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Create
                    </a>

                    <!-- BEGIN MODAL CREATE PRODUCT -->
                    <div class="modal modal-blur fade" id="modal-create-product" tabindex="-1" role="dialog"
                        aria-hidden="true">
                        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                <div class="modal-header">
                                    <h5 class="modal-title">Create Product</h5>
                                </div>
                                <form method="POST" action="{{ route('product.store') }}">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="productName" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="productName" name="name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="productDescription" class="form-label">Description</label>
                                            <textarea class="form-control" id="productDescription" name="description" rows="3" required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="productPrice" class="form-label">Price</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="text" class="form-control rupiah-format" id="productPrice"
                                                    name="price_display" required>
                                                <input type="hidden" id="productPriceHidden" name="price">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="productBrand" class="form-label">Brand</label>
                                            <input type="text" class="form-control" id="productBrand" name="brand"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="productType" class="form-label">Type</label>
                                            <input type="text" class="form-control" id="productType" name="type" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="productDistributorOrigin" class="form-label">Distributor Origin</label>
                                            <input type="text" class="form-control" id="productDistributorOrigin"
                                                name="distributor_origin" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="productWeight" class="form-label">Weight</label>
                                            <input type="text" class="form-control" id="productWeight" name="weight"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="productShippingFee" class="form-label">Shipping Fee (Air)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="text" class="form-control rupiah-format"
                                                    id="productShippingFee" name="shipping_fee_by_air_display" required>
                                                <input type="hidden" id="productShippingFeeHidden"
                                                    name="shipping_fee_by_air">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Create</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- END MODAL CREATE PRODUCT -->

                    <!-- BEGIN MODAL IMPORT PRODUCT -->
                    <div class="modal modal-blur fade" id="modal-import-product" tabindex="-1" role="dialog"
                        aria-hidden="true">
                        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                                <div class="modal-header">
                                    <h5 class="modal-title">Import Products</h5>
                                </div>

                                <div class="alert alert-info mx-3 mt-3" role="alert">
                                    <div class="d-flex">
                                        <div>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" class="icon alert-icon">
                                                <circle cx="12" cy="12" r="10" />
                                                <path d="m9 12 2 2 4-4" />
                                            </svg>
                                        </div>
                                        <div class="ms-2">
                                            <h4 class="alert-title">Download Template Excel</h4>
                                            <div class="text-secondary">
                                                Download template Excel terlebih dahulu untuk memastikan format data yang benar.
                                                <br>
                                                <a href="{{ asset('template/Template Import Product.xlsx') }}" download
                                                    class="btn btn-md btn-outline-primary mt-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="icon">
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                        <polyline points="7,10 12,15 17,10" />
                                                        <line x1="12" y1="15" x2="12" y2="3" />
                                                    </svg>
                                                    Download Template
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('product.import') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="importFile" class="form-label">Choose Excel File</label>
                                            <input type="file" class="form-control" id="importFile" name="import_file"
                                                accept=".xlsx,.xls,.csv" required>
                                            <div class="form-text">
                                                Supported formats: Excel (.xlsx, .xls) and CSV (.csv)
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                <polyline points="7,10 12,15 17,10" />
                                                <line x1="12" y1="15" x2="12" y2="3" />
                                            </svg>
                                            Import Products
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                    <!-- END MODAL IMPORT PRODUCT -->
                @endcan


                @can('EDIT_PRODUCT')
                    <div class="modal modal-blur fade" id="modal-edit-product" tabindex="-1" role="dialog"
                        aria-hidden="true">
                        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Product</h5>
                                </div>
                                <form method="POST" action="" id="editProductForm">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="editProductName" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="editProductName" name="name"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="editProductDescription" class="form-label">Description</label>
                                            <textarea class="form-control" id="editProductDescription" name="description" rows="3" required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="editProductPrice" class="form-label">Price</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="text" class="form-control rupiah-format"
                                                    id="editProductPrice" name="price_display" required>
                                                <input type="hidden" id="editProductPriceHidden" name="price">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="editProductBrand" class="form-label">Brand</label>
                                            <input type="text" class="form-control" id="editProductBrand" name="brand"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="editProductType" class="form-label">Type</label>
                                            <input type="text" class="form-control" id="editProductType" name="type"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="editProductDistributorOrigin" class="form-label">Distributor
                                                Origin</label>
                                            <input type="text" class="form-control" id="editProductDistributorOrigin"
                                                name="distributor_origin" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="editProductWeight" class="form-label">Weight</label>
                                            <input type="text" class="form-control" id="editProductWeight" name="weight"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="editProductShippingFee" class="form-label">Shipping Fee (Air)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="text" class="form-control rupiah-format"
                                                    id="editProductShippingFee" name="shipping_fee_by_air_display" required>
                                                <input type="hidden" id="editProductShippingFeeHidden"
                                                    name="shipping_fee_by_air">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- END MODAL EDIT PRODUCT -->
                @endcan

                <a href="#" class="btn btn-primary btn-6 d-sm-none btn-icon" data-bs-toggle="modal"
                    data-bs-target="#modal-report" aria-label="Create new report">
                    <!-- Download SVG icon from http://tabler.io/icons/icon/plus -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="icon icon-2">
                        <path d="M12 5l0 14" />
                        <path d="M5 12l14 0" />
                    </svg>
                </a>
            </div>
            <!-- BEGIN MODAL -->
            <!-- Delete Product Confirmation Modal -->
            <div class="modal modal-blur fade" id="modal-delete-product" tabindex="-1" role="dialog"
                aria-hidden="true">
                <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="modal-status bg-danger"></div>
                        <div class="modal-body text-center py-4">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/alert-triangle -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24"
                                height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 9v2m0 4v.01" />
                                <path
                                    d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" />
                            </svg>
                            <h3>Apakah Anda yakin?</h3>
                            <div class="text-secondary">
                                Anda akan menghapus produk <strong id="deleteProductName"></strong>.
                                Tindakan ini tidak dapat dibatalkan.
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
                                        <form id="deleteProductForm" method="POST" style="display: inline;">
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
    <!-- Flash Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <div>
                    <!-- Download SVG icon from http://tabler.io/icons/icon/check -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="icon alert-icon">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M5 12l5 5l10 -10" />
                    </svg>
                </div>
                <div>
                    {{ session('success') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <div>
                    <!-- Download SVG icon from http://tabler.io/icons/icon/alert-triangle -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="icon alert-icon">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 9v2m0 4v.01" />
                        <path
                            d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" />
                    </svg>
                </div>
                <div>
                    {{ session('error') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Products</h3>
                </div>
                <div class="card-body border-bottom py-3">
                    <div class="d-flex align-items-center flex-wrap">
                        <div class="text-secondary">
                            Show
                            <div class="mx-2 d-inline-block">
                                <input type="text" id="pageLength" class="form-control form-control-sm"
                                    value="8" size="3" aria-label="Products count">
                            </div>
                            entries
                        </div>

                        <!-- Custom filters: Brand and Distributor -->

                        <div class="ms-auto d-flex flex-wrap gap-2 align-items-center">
                            <div class="d-flex align-items-center" style="min-width:180px;">
                                <label for="filterBrand" class="form-label me-2 mb-0 d-none d-sm-inline">Brand</label>
                                <select id="filterBrand" class="form-select form-select-sm">
                                    <option value="">All Brands</option>
                                </select>
                            </div>

                            <div class="d-flex align-items-center" style="min-width:220px;">
                                <label for="filterDistributor" class="form-label me-2 mb-0 d-none d-sm-inline">Distributor</label>
                                <select id="filterDistributor" class="form-select form-select-sm">
                                    <option value="">All Distributors</option>
                                </select>
                            </div>

                            <div class="d-flex align-items-center" style="min-width:220px;">
                                <label for="customSearch" class="form-label me-2 mb-0 d-none d-sm-inline">Search</label>
                                <input type="text" id="customSearch" class="form-control form-control-sm" placeholder="Search product..." aria-label="Search Product">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-body p-0">
                    <!-- Loading Overlay -->
                    <div id="table-loading" class="position-relative" style="display: none;">
                        <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center"
                            style="background-color: rgba(255, 255, 255, 0.8); z-index: 1000; min-height: 300px;">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <div class="mt-2 text-secondary">Memuat data...</div>
                            </div>
                        </div>
                    </div>
                    <!-- Custom Search and Page Length Controls -->
                    <div class="table-responsive">
                        <table id="example"
                            class="table table-selectable card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th class="w-1">ID</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Brand</th>
                                    <th>Type</th>
                                    <th>Distributor Origin</th>
                                    <th>Weight</th>
                                    @can('VIEW_ALL_INFO_PRODUCT')
                                        <th> HARGA DASAR DOLAR </th>
                                        <th>HARGA DASAR RUPIAH (FOB LUAR NEGERI)</th>
                                        <th>HARGA DASAR RUPIAH (FOB JAKARTA)</th>

                                        <th>Shipping Fee (Air)</th>
                                    @endcan
                                    <th>Description</th>

                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row g-2 justify-content-center justify-content-sm-between">
                        <div class="col-auto d-flex align-items-center">
                            <p id="tableInfo" class="m-0 text-secondary"></p>
                        </div>
                        <div class="col-auto">
                            <ul id="tablePagination" class="pagination m-0 ms-auto"></ul>
                        </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script src="{{ asset('utils.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#example').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "http://management-project.test/product/datatable/api",
                    "type": "GET",
                    "data": function(d) {
                        // send custom filter values to server
                        d.brand = $('#filterBrand').val();
                        d.distributor_origin = $('#filterDistributor').val();
                    }
                },
                "columns": [{
                        "data": "id"
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "price",
                        "render": function(data) {
                            return formatRupiah(data);
                        }
                    },
                    {
                        "data": "brand"
                    },
                    {
                        "data": "type"
                    },
                    {
                        "data": "distributor_origin"
                    },
                    {
                        "data": "weight"
                    },
                    @can('VIEW_ALL_INFO_PRODUCT')
                    {
                        "data": "dollar_base_price"
                    },
                    {
                        "data": null,
                        "render": function(data) {
                            const hargaDasarRupiahLuarNegeri =
                                {{ $currencyExchangeRateSettingValue }} * data
                                .dollar_base_price;
                            return formatRupiah(hargaDasarRupiahLuarNegeri);
                        }
                    },
                    {
                        "data": null,
                        "render": function(data) {
                            const hargaDasarRupiahLuarNegeri =
                                {{ $currencyExchangeRateSettingValue }} * data
                                .dollar_base_price;
                            const hargaDasarRupiahJakarta = hargaDasarRupiahLuarNegeri + (data
                                .shipping_fee_by_air * data.weight);
                            return formatRupiah(hargaDasarRupiahJakarta);
                        }
                    },
                    {
                        "data": "shipping_fee_by_air",
                        "render": function(data) {
                            return formatRupiah(data);
                        }
                    },
                    @endcan
                    {
                        "data": "description"
                    },
                    {
                        "data": null,
                        "orderable": false,
                        "searchable": false,
                        "render": function(data, type, row) {
                            let actions = '';
                            @can('EDIT_PRODUCT')
                                actions += `<a href="" class="btn btn-icon edit-product-btn" data-bs-toggle="modal" data-bs-target="#modal-edit-product" data-product-id="${row.id}" data-product-name="${row.name}" data-product-description="${row.description}" data-product-price="${row.price}" data-product-brand="${row.brand}" data-product-type="${row.type}" data-product-distributor-origin="${row.distributor_origin}" data-product-weight="${row.weight}" data-product-shipping-fee="${row.shipping_fee_by_air}" aria-label="Edit Product" title="Edit Product">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                            </a>`;
                            @endcan
                            @can('DELETE_PRODUCT')
                                actions += `<button class="btn btn-icon delete-product-btn" data-bs-toggle="modal" data-bs-target="#modal-delete-product" data-product-id="${row.id}" data-product-name="${row.name}" aria-label="Delete Product">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                            </button>`;
                            @endcan
                            return actions;
                        }
                    }
                ],
                "searching": true,
                "dom": 't',
                "pageLength": 10,
                "lengthChange": false,
                "info": false,
                "ordering": true,
                "responsive": true,
                "paging": true,
                "drawCallback": function(settings) {
                    updateTableInfo();
                    updatePagination();
                    $('#table-loading').hide();
                },
                "preDrawCallback": function(settings) {
                    $('#table-loading').show();
                }
            });

            // Show loading on initial load
            $('#table-loading').show();

            // Connect custom search input to DataTable search
            $('#customSearch').on('keyup', function() {
                console.log(this.value);
                table.search(this.value).draw();
            });

            // Optional: Clear search when input is empty
            $('#customSearch').on('search', function() {
                if (this.value === '') {
                    table.search('').draw();
                }
            });

            // Page length functionality
            $('#pageLength').on('keyup change', function() {
                var length = parseInt(this.value);
                if (!isNaN(length) && length > 0) {
                    table.page.len(length).draw();
                }
            });

            // Debounce helper to reduce reloads while users change filters
            function debounce(fn, delay) {
                var timer = null;
                return function() {
                    var context = this,
                        args = arguments;
                    clearTimeout(timer);
                    timer = setTimeout(function() {
                        fn.apply(context, args);
                    }, delay);
                };
            }

            // Populate select filters via AJAX. These endpoints should return a JSON array of values.
            // Example expected responses: ["Sony", "Samsung"] or [{"name":"Sony"}, {"name":"Samsung"}]
            var brandsUrl = '{{ url('/product/brands') }}';
            var distributorsUrl = '{{ url('/product/distributors') }}';

            // Helper to safely append options
            function appendOptions($select, items) {
                // leave the first option (All) intact
                $.each(items, function(i, v) {
                    var value = v;
                    var text = v;
                    if (typeof v === 'object') {
                        // try common property names
                        value = v.id || v.value || v.name || v.brand || v.distributor || JSON.stringify(v);
                        text = v.name || v.brand || v.distributor || value;
                    }
                    // avoid adding empty values
                    if (value === null || value === undefined) return;
                    // if option already exists, skip
                    if ($select.find('option[value="' + value + '"]').length === 0) {
                        $select.append($('<option>', {
                            value: value,
                            text: text
                        }));
                    }
                });
            }

            // Try to fetch brands
            $.getJSON(brandsUrl).done(function(data) {
                var $brand = $('#filterBrand');
                appendOptions($brand, data);
            }).fail(function() {
                // silently ignore if endpoint not present
            });

            // Try to fetch distributors
            $.getJSON(distributorsUrl).done(function(data) {
                var $dist = $('#filterDistributor');
                appendOptions($dist, data);
            }).fail(function() {
                // silently ignore if endpoint not present
            });

            // Reload table when select values change, debounced
            $('#filterBrand, #filterDistributor').on('change', debounce(function() {
                table.ajax.reload();
            }, 250));

            // Custom Table Info
            function updateTableInfo() {
                var info = table.page.info();
                var start = info.start + 1;
                var end = info.end;
                var total = info.recordsTotal;
                var text = `Showing <strong>${start} to ${end}</strong> of <strong>${total} entries</strong>`;
                $('#tableInfo').html(text);
            }

            // Custom Pagination
            function updatePagination() {
                var info = table.page.info();
                var currentPage = info.page + 1;
                var totalPages = info.pages;
                var pagination = '';

                // Previous button
                if (currentPage === 1) {
                    pagination +=
                        `<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true"><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-1'><path d='M15 6l-6 6l6 6'></path></svg></a></li>`;
                } else {
                    pagination +=
                        `<li class="page-item"><a class="page-link" href="#" data-page="${currentPage - 2}"><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-1'><path d='M15 6l-6 6l6 6'></path></svg></a></li>`;
                }

                // Page numbers
                for (var i = 1; i <= totalPages; i++) {
                    if (i === currentPage) {
                        pagination += `<li class="page-item active"><a class="page-link" href="#">${i}</a></li>`;
                    } else {
                        pagination +=
                            `<li class="page-item"><a class="page-link" href="#" data-page="${i - 1}">${i}</a></li>`;
                    }
                }

                // Next button
                if (currentPage === totalPages || totalPages === 0) {
                    pagination +=
                        `<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true"><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-1'><path d='M9 6l6 6l-6 6'></path></svg></a></li>`;
                } else {
                    pagination +=
                        `<li class="page-item"><a class="page-link" href="#" data-page="${currentPage}"><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-1'><path d='M9 6l6 6l-6 6'></path></svg></a></li>`;
                }

                $('#tablePagination').html(pagination);

                // Handle page click
                $('#tablePagination a[data-page]').off('click').on('click', function(e) {
                    e.preventDefault();
                    var page = parseInt($(this).data('page'));
                    if (!isNaN(page)) {
                        table.page(page).draw('page');
                    }
                });
            }

            // Handle delete user modal
            $('.delete-user-btn').on('click', function() {
                var userId = $(this).data('user-id');
                var userName = $(this).data('user-name');

                // Update modal content
                $('#deleteUserName').text(userName);

                // Update form action URL - build the URL properly
                var baseUrl = '{{ url('/user') }}';
                $('#deleteUserForm').attr('action', baseUrl + '/' + userId);
            });



            // Handle delete product modal - for both static and dynamic content
            $(document).on('click', '.delete-product-btn', function() {
                var productId = $(this).data('product-id');
                var productName = $(this).data('product-name');

                // Update modal content
                $('#deleteProductName').text(productName);

                // Update form action URL - build the URL properly
                var baseUrl = '{{ url('/product') }}';
                $('#deleteProductForm').attr('action', baseUrl + '/' + productId);
            });

            // Handle Rupiah input formatting
            $(document).on('input', '.rupiah-format', function() {
                let input = $(this);
                let value = input.val();
                let formatted = formatRupiahInput(value);

                input.val(formatted);

                let hiddenInput = input.closest('.input-group').find('input[type="hidden"]');
                hiddenInput.val(parseRupiahInput(value));
            });

            // Update edit modal to show formatted price
            $(document).on('click', '.edit-product-btn', function() {
                var productId = $(this).data('product-id');
                var productName = $(this).data('product-name');
                var productDescription = $(this).data('product-description');
                var productPrice = $(this).data('product-price');
                var productBrand = $(this).data('product-brand');
                var productType = $(this).data('product-type');
                var productDistributorOrigin = $(this).data('product-distributor-origin');
                var productWeight = $(this).data('product-weight');
                var productShippingFee = $(this).data('product-shipping-fee');

                // Update modal form fields
                $('#editProductName').val(productName);
                $('#editProductDescription').val(productDescription);

                // Format price for display
                $('#editProductPrice').val(formatRupiahInput(productPrice));
                $('#editProductPriceHidden').val(productPrice);
                $('#editProductBrand').val(productBrand);
                $('#editProductType').val(productType);
                $('#editProductDistributorOrigin').val(productDistributorOrigin);
                $('#editProductWeight').val(productWeight);
                $('#editProductShippingFee').val(formatRupiahInput(productShippingFee));
                $('#editProductShippingFeeHidden').val(productShippingFee);

                // Update form action URL - build the URL properly
                var baseUrl = '{{ url('/product') }}';
                $('#editProductForm').attr('action', baseUrl + '/' + productId);
            });
        });
    </script>
@endpush
