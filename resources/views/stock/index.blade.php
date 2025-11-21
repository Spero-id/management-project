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

        /* Select2 custom styling to match Tabler */
        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 1px solid var(--tblr-border-color);
            border-radius: var(--tblr-border-radius);
            height: calc(1.4285714em + 1rem + 2px);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #000;
            line-height: 1.4285714;
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: calc(1.4285714em + 1rem);
            right: 0.75rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: var(--tblr-secondary) transparent transparent transparent;
        }

        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent var(--tblr-secondary) transparent;
        }

        .select2-dropdown {
            background-color: #fff;
            border: 1px solid var(--tblr-border-color);
            border-radius: var(--tblr-border-radius);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            background-color: #fff;
            border: 1px solid var(--tblr-border-color);
            border-radius: var(--tblr-border-radius);
            padding: 0.4375rem 0.75rem;
            color: #000;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            border-color: var(--tblr-primary);
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(var(--tblr-primary-rgb), 0.25);
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--tblr-primary);
            color: #fff;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: var(--tblr-active-bg);
            color: #000;
        }

        .select2-container--default .select2-results__option {
            padding: 0.5rem 0.75rem;
            color: #000;
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: var(--tblr-primary);
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(var(--tblr-primary-rgb), 0.25);
        }
    </style>
@endpush

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <!-- Page pre-title -->
            <div class="page-pretitle">Logistics</div>
            <h2 class="page-title">Stock Management</h2>
        </div>
        <!-- Page title actions -->
        <div class="col-auto ms-auto d-print-none">

            <div class="btn-list">


                <a href="{{ route('stock.export') }}" class="btn btn-success btn-5 d-none d-sm-inline-block">
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
                    Export Stock
                </a>


                <a href="#" class="btn btn-primary btn-5 d-none d-sm-inline-block" data-bs-toggle="modal"
                    data-bs-target="#modal-input-stock">
                    <!-- Download SVG icon from http://tabler.io/icons/icon/plus -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-2">
                        <path d="M12 5l0 14" />
                        <path d="M5 12l14 0" />
                    </svg>
                    Input Stock
                </a>

                <a href="#" class="btn btn-primary btn-6 d-sm-none btn-icon" data-bs-toggle="modal"
                    data-bs-target="#modal-input-stock" aria-label="Input stock">
                    <!-- Download SVG icon from http://tabler.io/icons/icon/plus -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-2">
                        <path d="M12 5l0 14" />
                        <path d="M5 12l14 0" />
                    </svg>
                </a>
            </div>
            <!-- BEGIN MODAL -->
            <!-- Input Stock Modal -->
            <div class="modal modal-blur fade" id="modal-input-stock" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Input stock</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form id="formInputStock" method="POST" action="{{ route('stock.store') }}">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label required">Brand</label>
                                    <select class="form-select" id="brandSelect" name="brand" required>
                                        <option value="">Pilih brand</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label required">Type</label>
                                    <select class="form-select" id="typeSelect" name="type" required>
                                        <option value="">Pilih type</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Available stock</label>
                                    <input type="text" class="form-control" id="currentStock" readonly value="0">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label required">Stock in</label>
                                    <input type="number" class="form-control" id="stockInput"
                                        name="stock_quantity" min="1" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link link-secondary"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary ms-auto" data-bs-dismiss="modal">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-check">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
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
                    <h3 class="card-title">Stock Management</h3>
                </div>
                <div class="card-body">
                    <x-datatable id="stock-table" title="Stock Management" url="{{ route('stock.datatable') }}"
                        :columns="['brand', 'type', 'stok']" />
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2 for Brand
            $('#brandSelect').select2({
                dropdownParent: $('#modal-input-stock'),
                tags: true,
                placeholder: 'Pilih atau ketik brand baru',
                ajax: {
                    url: '{{ route('stock.brands') }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: data.map(function(brand) {
                                return {
                                    id: brand,
                                    text: brand
                                };
                            })
                        };
                    },
                    cache: true
                }
            });

            // Initialize Select2 for Type
            $('#typeSelect').select2({
                dropdownParent: $('#modal-input-stock'),
                tags: true,
                placeholder: 'Pilih atau ketik type baru',
                ajax: {
                    url: '{{ route('stock.types') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            brand: $('#brandSelect').val(),
                            search: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(function(type) {
                                return {
                                    id: type,
                                    text: type
                                };
                            })
                        };
                    },
                    cache: true
                }
            });

            // Update current stock when brand and type are selected
            $('#brandSelect, #typeSelect').on('change', function() {
                const brand = $('#brandSelect').val();
                const type = $('#typeSelect').val();

                if (brand && type) {
                    $.ajax({
                        url: '{{ route('stock.current') }}',
                        method: 'GET',
                        data: {
                            brand: brand,
                            type: type
                        },
                        success: function(response) {
                            $('#currentStock').val(response.stock || 0);
                        }
                    });
                }
            });

            // Reset form when modal is closed
            $('#modal-input-stock').on('hidden.bs.modal', function() {
                $('#formInputStock')[0].reset();
                $('#brandSelect').val(null).trigger('change');
                $('#typeSelect').val(null).trigger('change');
                $('#currentStock').val('0');
            });

            // Form submission
            $('#formInputStock').on('submit', function(e) {
                e.preventDefault();

                const formData = $(this).serialize();

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        // // Properly close modal and remove backdrop
                        // const modal = bootstrap.Modal.getInstance(document.getElementById('modal-input-stock'));
                        // if (modal) {
                        //     modal.hide();
                        // }
                        // // Remove any remaining backdrops
                        // document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                        // document.body.classList.remove('modal-open');
                        // document.body.style.removeProperty('overflow');
                        // document.body.style.removeProperty('padding-right');
                        
                        // Reload datatable if exists
                        if ($.fn.DataTable.isDataTable('#stock-table')) {
                            $('#stock-table').DataTable().ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan saat menyimpan data';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                    }
                });
            });
        });
    </script>
@endpush
