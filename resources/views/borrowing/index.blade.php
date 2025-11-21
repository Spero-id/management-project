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
        
        /* Select2 styling */
        .select2-container {
            width: 100% !important;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #000000;
        }
        
        .select2-container--default .select2-results__option {
            color: #000000;
        }
    </style>
@endpush

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <!-- Page pre-title -->
            <div class="page-pretitle">Logistics</div>
            <h2 class="page-title">Borrowed items status</h2>
        </div>
        <!-- Page title actions -->
        <div class="col-auto ms-auto d-print-none">

            <div class="btn-list">



                <a href="#" class="btn btn-primary btn-5 d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-borrowing">
                    <!-- Download SVG icon from http://tabler.io/icons/icon/plus -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-2">
                        <path d="M12 5l0 14" />
                        <path d="M5 12l14 0" />
                    </svg>
                    Peminjaman Barang
                </a>
                <a href="#" class="btn btn-primary btn-5 d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-return">
                    <!-- Download SVG icon from http://tabler.io/icons/icon/plus -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-2">
                        <path d="M12 5l0 14" />
                        <path d="M5 12l14 0" />
                    </svg>
                    Pengembalian Barang
                </a>

                <a href="#" class="btn btn-primary btn-6 d-sm-none btn-icon" data-bs-toggle="modal"
                    data-bs-target="#modal-report" aria-label="Create new report">
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
            <!-- Borrowing Modal -->
            <div class="modal modal-blur fade" id="modal-borrowing" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="borrowing-modal-title">Form peminjaman barang</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="borrowingForm" method="POST" action="{{ route('borrowing.store') }}">
                            @csrf
                            <input type="hidden" name="_method" id="formMethod" value="POST">
                            <input type="hidden" name="id" id="borrowingId">
                            
                            <div class="modal-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Tanggal</label>
                                        <input type="text" class="form-control" id="tanggal" name="tanggal" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">No peminjaman</label>
                                        <input type="text" class="form-control" id="no_peminjaman" name="no_peminjaman" readonly>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label required">Keperluan</label>
                                        <input type="text" class="form-control" id="keperluan" name="keperluan" placeholder="POC/DEMO/BACKUP" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label required">Penanggung jawab</label>
                                        <select class="form-select" id="penanggung_jawab" name="penanggung_jawab" required>
                                            <option value="">Pilih karyawan</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label required">Brand</label>
                                        <select class="form-select" id="temp_brand">
                                            <option value="">Pilih brand</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label required">Type</label>
                                        <select class="form-select" id="temp_type">
                                            <option value="">Pilih type</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Stok tersedia</label>
                                        <input type="text" class="form-control" id="temp_stok_tersedia" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label required">Jumlah barang</label>
                                        <input type="number" class="form-control" id="temp_jumlah" placeholder="Masukkan jumlah" min="1">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <button type="button" class="btn btn-primary btn-sm" id="addItemBtn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                            <path d="M12 5l0 14" />
                                            <path d="M5 12l14 0" />
                                        </svg>
                                        Add item
                                    </button>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Brand</th>
                                                <th>Type</th>
                                                <th>Jumlah</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemsTableBody">
                                            <!-- Dynamic rows will be added here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button  data-bs-dismiss="modal" type="submit" class="btn btn-primary ms-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
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
            
            <!-- Return Modal -->
            <div class="modal modal-blur fade" id="modal-return" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Form pengembalian barang</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="returnForm" method="POST" action="{{ route('borrowing.return') }}">
                            @csrf
                            <input type="hidden" name="borrowing_id" id="return_borrowing_id">
                            
                            <div class="modal-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label required">No peminjaman</label>
                                        <select class="form-select" id="return_no_peminjaman" name="no_peminjaman" required>
                                            <option value="">Pilih nomor peminjaman</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Tanggal pengembalian</label>
                                        <input type="date" class="form-control" id="tanggal_pengembalian" name="tanggal_pengembalian" required>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Penanggung jawab</label>
                                        <input type="text" class="form-control" id="return_penanggung_jawab" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Tanggal peminjaman</label>
                                        <input type="text" class="form-control" id="return_tanggal_peminjaman" readonly>
                                    </div>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Brand</th>
                                                <th>Type</th>
                                                <th>Sudah dikembalikan</th>
                                                <th>Sisa belum kembali</th>
                                                <th>Jumlah dikembalikan</th>
                                            </tr>
                                        </thead>
                                        <tbody id="returnItemsTableBody">
                                            <!-- Dynamic rows will be added here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button data-bs-dismiss="modal" type="submit" class="btn btn-primary ms-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M5 12l5 5l10 -10" />
                                    </svg>
                                    Process return
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
    <!-- Flash Messages -->
    @if (session('success'))
        <div class="alert  alert-important alert-success alert-dismissible fade show" role="alert">
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
        <div class="alert  alert-important alert-danger alert-dismissible fade show" role="alert">
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

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Borrowed items status</h3>
                </div>
                <div class="card-body">
                    <x-datatable 
                        id="borrowing-table" 
                        title="Borrowed items status" 
                        url="{{ route('borrowing.datatable') }}"
                        :columns="[
                            ['data' => 'tanggal', 'label' => 'Date'],
                            ['data' => 'no_peminjaman', 'label' => 'Borrowing number'],
                            ['data' => 'penanggung_jawab', 'label' => 'Person in charge'],
                            ['data' => 'keperluan', 'label' => 'Purpose'],
                            ['data' => 'status', 'label' => 'Status'],
                            ['data' => 'action', 'label' => 'Action', 'orderable' => false, 'searchable' => false]
                        ]" 
                    />
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
        let itemsData = [];
        let itemCounter = 0;
        
        $(document).ready(function() {
            // Initialize Select2 for Penanggung Jawab
            $('#penanggung_jawab').select2({
                dropdownParent: $('#modal-borrowing'),
                placeholder: 'Pilih karyawan',
                ajax: {
                    url: '{{ route("borrowing.users") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term
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

            // Initialize Select2 for Brand
            $('#temp_brand').select2({
                dropdownParent: $('#modal-borrowing'),
                tags: true,
                placeholder: 'Pilih atau ketik brand baru',
                ajax: {
                    url: '{{ route("borrowing.brands") }}',
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
            $('#temp_type').select2({
                dropdownParent: $('#modal-borrowing'),
                tags: true,
                placeholder: 'Pilih atau ketik type baru',
                ajax: {
                    url: '{{ route("borrowing.types") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            brand: $('#temp_brand').val(),
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
            $('#temp_brand, #temp_type').on('change', function() {
                const brand = $('#temp_brand').val();
                const type = $('#temp_type').val();

                if (brand && type) {
                    $.ajax({
                        url: '{{ route("borrowing.current-stock") }}',
                        method: 'GET',
                        data: {
                            brand: brand,
                            type: type
                        },
                        success: function(response) {
                            $('#temp_stok_tersedia').val(response.stock || 0);
                        },
                        error: function() {
                            $('#temp_stok_tersedia').val(0);
                        }
                    });
                } else {
                    $('#temp_stok_tersedia').val('');
                }
            });
            
            // Open modal for creating new borrowing
            $(document).on('click', '[data-bs-target="#modal-borrowing"]:not(.edit-btn):not(.view-btn)', function() {
                resetForm();
                loadBorrowingNumber();
            });
            
            // Clear form when modal is closed
            $('#modal-borrowing').on('hidden.bs.modal', function() {
                resetForm();
            });
            
            // Load borrowing number and date
            function loadBorrowingNumber() {
                $.ajax({
                    url: '{{ route("borrowing.create") }}',
                    method: 'GET',
                    success: function(response) {
                        $('#no_peminjaman').val(response.no_peminjaman);
                        $('#tanggal').val(response.tanggal);
                    }
                });
            }
            
            // Add item to table (when user adds brand/type/jumlah)
            $('#addItemBtn').on('click', function() {
                addItemToTable();
            });
            
            $('#temp_jumlah').on('keypress', function(e) {
                if (e.which === 13) { // Enter key
                    e.preventDefault();
                    addItemToTable();
                }
            });
            
            // You might want to add a button to add items
            function addItemToTable() {
                const brand = $('#temp_brand').val();
                const type = $('#temp_type').val();
                const stokTersedia = $('#temp_stok_tersedia').val();
                const jumlah = $('#temp_jumlah').val();
                
                if (!brand || !type || !jumlah) {
                    alert('Mohon isi Brand, Type, dan Jumlah barang');
                    return;
                }
                
                itemCounter++;
                itemsData.push({
                    id: itemCounter,
                    brand: brand,
                    type: type,
                    stok_tersedia: stokTersedia || null,
                    jumlah_barang: jumlah
                });
                
                renderItemsTable();
                clearItemInputs();
            }
            
            function renderItemsTable() {
                let html = '';
                itemsData.forEach((item, index) => {
                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.brand}</td>
                            <td>${item.type}</td>
                            <td>${item.jumlah_barang}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-danger remove-item" data-id="${item.id}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 7l16 0" />
                                        <path d="M10 11l0 6" />
                                        <path d="M14 11l0 6" />
                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    `;
                });
                $('#itemsTableBody').html(html);
            }
            
            // Remove item from table
            $(document).on('click', '.remove-item', function() {
                const itemId = $(this).data('id');
                itemsData = itemsData.filter(item => item.id !== itemId);
                renderItemsTable();
            });
            
            function clearItemInputs() {
                $('#temp_brand').val(null).trigger('change');
                $('#temp_type').val(null).trigger('change');
                $('#temp_stok_tersedia').val('');
                $('#temp_jumlah').val('');
            }
            
            // Submit form
            $('#borrowingForm').on('submit', function(e) {
                e.preventDefault();
                
                if (itemsData.length === 0) {
                    alert('Tambahkan minimal satu barang');
                    return;
                }
                
                const formData = {
                    tanggal: $('#tanggal').val(),
                    no_peminjaman: $('#no_peminjaman').val(),
                    keperluan: $('#keperluan').val(),
                    penanggung_jawab: $('#penanggung_jawab').val(),
                    items: itemsData.map(item => ({
                        brand: item.brand,
                        type: item.type,
                        stok_tersedia: item.stok_tersedia,
                        jumlah_barang: item.jumlah_barang
                    }))
                };
                
                const borrowingId = $('#borrowingId').val();
                const method = borrowingId ? 'PUT' : 'POST';
                const url = borrowingId 
                    ? '/borrowing/' + borrowingId
                    : '{{ route("borrowing.store") }}';
                
                $.ajax({
                    url: url,
                    method: method,
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#modal-borrowing').modal('hide');
                        $('#borrowing-table').DataTable().ajax.reload();
                        alert(response.message || 'Berhasil menyimpan data peminjaman');
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        alert(errorMessage);
                    }
                });
            });
            
            // View borrowing
            $(document).on('click', '.view-btn', function() {
                const id = $(this).data('id');
                loadBorrowing(id, true);
            });
            
            // Edit borrowing
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                loadBorrowing(id, false);
            });
            
            function loadBorrowing(id, viewOnly) {
                $.ajax({
                    url: '/borrowing/' + id + '/edit',
                    method: 'GET',
                    success: function(response) {
                        const data = response.data;
                        
                        // Reset form terlebih dahulu
                        $('#borrowingForm')[0].reset();
                        $('#penanggung_jawab').empty().trigger('change');
                        $('#temp_brand').empty().trigger('change');
                        $('#temp_type').empty().trigger('change');
                        
                        // Set form data
                        $('#borrowingId').val(data.id);
                        $('#tanggal').val(data.tanggal);
                        $('#no_peminjaman').val(data.no_peminjaman);
                        $('#keperluan').val(data.keperluan);
                        
                        // Set penanggung jawab with Select2
                        const penanggungJawabOption = new Option(data.penanggung_jawab, data.penanggung_jawab, true, true);
                        $('#penanggung_jawab').append(penanggungJawabOption).trigger('change');
                        
                        // Load items data
                        itemsData = data.items.map((item, index) => ({
                            id: index + 1,
                            brand: item.brand,
                            type: item.type,
                            stok_tersedia: item.stok_tersedia,
                            jumlah_barang: item.jumlah_barang
                        }));
                        itemCounter = itemsData.length;
                        
                        renderItemsTable();
                        
                        // Set mode (view/edit)
                        if (viewOnly) {
                            $('#borrowingForm input, #borrowingForm select, #borrowingForm button[type="submit"], .remove-item, #addItemBtn').prop('disabled', true);
                            $('#temp_brand, #temp_type, #temp_jumlah').prop('disabled', true);
                            $('#borrowing-modal-title').text('Detail peminjaman barang');
                        } else {
                            $('#borrowingForm input:not([readonly]), #borrowingForm select, #borrowingForm button[type="submit"], .remove-item, #addItemBtn').prop('disabled', false);
                            $('#temp_brand, #temp_type, #temp_jumlah').prop('disabled', false);
                            $('#borrowing-modal-title').text('Edit peminjaman barang');
                        }
                        
                        $('#formMethod').val('PUT');
                        $('#modal-borrowing').modal('show');
                    },
                    error: function(xhr) {
                        alert('Gagal memuat data peminjaman');
                    }
                });
            }
            
            // Delete borrowing
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                
                if (confirm('Apakah Anda yakin ingin menghapus data peminjaman ini?')) {
                    $.ajax({
                        url: '/borrowing/' + id,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $('#borrowing-table').DataTable().ajax.reload();
                            alert(response.message || 'Data peminjaman berhasil dihapus');
                        },
                        error: function(xhr) {
                            let errorMessage = 'Gagal menghapus data peminjaman';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            alert(errorMessage);
                        }
                    });
                }
            });
            
            function resetForm() {
                $('#borrowingForm')[0].reset();
                $('#borrowingId').val('');
                $('#formMethod').val('POST');
                
                // Clear all Select2 dropdowns
                $('#penanggung_jawab').empty().val(null).trigger('change');
                $('#temp_brand').empty().val(null).trigger('change');
                $('#temp_type').empty().val(null).trigger('change');
                
                // Clear temp fields
                $('#temp_stok_tersedia').val('');
                $('#temp_jumlah').val('');
                
                // Reset items
                itemsData = [];
                itemCounter = 0;
                renderItemsTable();
                
                // Enable all inputs
                $('#borrowingForm input:not([readonly]), #borrowingForm select, #borrowingForm button[type="submit"], .remove-item, #addItemBtn').prop('disabled', false);
                $('#temp_brand, #temp_type, #temp_jumlah').prop('disabled', false);
                
                $('#borrowing-modal-title').text('Form peminjaman barang');
            }

            // Return Modal Functionality
            let returnItemsData = [];

            // Initialize Select2 for Return No Peminjaman
            $('#return_no_peminjaman').select2({
                dropdownParent: $('#modal-return'),
                placeholder: 'Pilih nomor peminjaman',
                ajax: {
                    url: '{{ route("borrowing.borrowed-items") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term
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

            // Set default return date to today
            $('#modal-return').on('show.bs.modal', function() {
                $('#tanggal_pengembalian').val(new Date().toISOString().split('T')[0]);
                resetReturnForm();
            });

            // When a borrowing is selected, load its items from database
            $('#return_no_peminjaman').on('change', function() {
                const selectedId = $(this).val();
                
                if (!selectedId) {
                    returnItemsData = [];
                    renderReturnItemsTable();
                    $('#return_penanggung_jawab').val('');
                    $('#return_tanggal_peminjaman').val('');
                    $('#return_borrowing_id').val('');
                    return;
                }

                // Load borrowing detail from database
                $.ajax({
                    url: '/borrowing/data/borrowing-detail/' + selectedId,
                    method: 'GET',
                    success: function(response) {
                        const data = response.data;
                        
                        $('#return_borrowing_id').val(data.id);
                        $('#return_penanggung_jawab').val(data.penanggung_jawab);
                        $('#return_tanggal_peminjaman').val(data.tanggal);

                        returnItemsData = data.items.map(item => ({
                            id: item.id,
                            brand: item.brand,
                            type: item.type,
                            jumlah_sudah_dikembalikan: item.jumlah_sudah_dikembalikan,
                            jumlah_barang: item.jumlah_barang,
                            jumlah_dikembalikan: item.jumlah_dikembalikan
                        }));

                        renderReturnItemsTable();
                    },
                    error: function(xhr) {
                        alert('Error loading borrowing details');
                        returnItemsData = [];
                        renderReturnItemsTable();
                    }
                });
            });

            function renderReturnItemsTable() {
                let html = '';
                returnItemsData.forEach((item, index) => {
                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.brand}</td>
                            <td>${item.type}</td>
                            <td>${item.jumlah_sudah_dikembalikan || 0}</td>
                            <td>${item.jumlah_barang}</td>
                            <td>
                                <input type=\"number\" class=\"form-control form-control-sm return-quantity\" 
                                    data-id=\"${item.id}\" 
                                    value=\"${item.jumlah_dikembalikan}\" 
                                    min=\"1\" 
                                    max=\"${item.jumlah_barang}\" 
                                    required>
                            </td>
                        </tr>
                    `;
                });
                $('#returnItemsTableBody').html(html);
            }

            // Update return quantity when changed
            $(document).on('input', '.return-quantity', function() {
                const itemId = $(this).data('id');
                const quantity = parseInt($(this).val());
                
                const item = returnItemsData.find(i => i.id === itemId);
                if (item) {
                    item.jumlah_dikembalikan = quantity;
                }
            });

            // Submit return form
            $('#returnForm').on('submit', function(e) {
                e.preventDefault();
                
                const borrowingId = $('#return_borrowing_id').val();
                
                if (!borrowingId) {
                    alert('Please select a borrowing number');
                    return;
                }
                
                if (returnItemsData.length === 0) {
                    alert('No items to return');
                    return;
                }
                
                const formData = {
                    borrowing_id: borrowingId,
                    tanggal_pengembalian: $('#tanggal_pengembalian').val(),
                    items: returnItemsData.map(item => ({
                        id: item.id,
                        jumlah_dikembalikan: item.jumlah_dikembalikan
                    }))
                };
                
                $.ajax({
                    url: '{{ route("borrowing.return") }}',
                    method: 'POST',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name=\"csrf-token\"]').attr('content')
                    },
                    success: function(response) {
                        $('#modal-return').modal('hide');
                        $('#borrowing-table').DataTable().ajax.reload();
                        alert(response.message || 'Barang berhasil dikembalikan');
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\\n');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        alert(errorMessage);
                    }
                });
            });

            function resetReturnForm() {
                $('#returnForm')[0].reset();
                $('#return_borrowing_id').val('');
                $('#return_no_peminjaman').val(null).trigger('change');
                $('#return_penanggung_jawab').val('');
                $('#return_tanggal_peminjaman').val('');
                returnItemsData = [];
                renderReturnItemsTable();
            }
        });
    </script>
@endpush
