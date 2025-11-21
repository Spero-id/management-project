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
            <div class="page-pretitle">Logistics</div>
            <h2 class="page-title">Inventory Management</h2>
        </div>
        <!-- Page title actions -->
        <div class="col-auto ms-auto d-print-none">

            <div class="btn-list">


            </div>
            <!-- BEGIN MODAL -->
            <!-- Edit Modal -->
            <div class="modal modal-blur fade" id="modal-edit-inventory" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Inventory</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="editInventoryForm"  method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label class="form-label required">Item</label>
                                        <input type="text" class="form-control" name="item" id="edit_item" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label required">Stock Awal</label>
                                        <input type="number" class="form-control" name="stock_awal" id="edit_stock_awal"
                                            required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label required">Unit Awal</label>
                                        <input type="text" class="form-control" name="unit_awal" id="edit_unit_awal"
                                            required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label required">Stock Akhir</label>
                                        <input type="number" class="form-control" name="stock_akhir" id="edit_stock_akhir"
                                            required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label required">Unit Akhir</label>
                                        <input type="text" class="form-control" name="unit_akhir" id="edit_unit_akhir"
                                            required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label class="form-label">Note</label>
                                        <textarea class="form-control" name="note" id="edit_note" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label class="form-label required">Posisi</label>
                                        <input type="text" class="form-control" name="posisi" id="edit_posisi" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
                    <h3 class="card-title">Inventory Management</h3>
                </div>
                <div class="card-body">
                    <x-datatable id="inventory-table" title="Inventory Management"
                        url="{{ route('inventory.datatable') }}" :columns="[
                            'item',
                            'stock_awal',
                            'unit_awal',
                            'stock_akhir',
                            'unit_akhir',
                            'note',
                            'posisi',
                            'action',
                        ]" />
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

    <script>
        $(document).ready(function() {
            // Handle edit button click
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');

                // Fetch inventory data
                $.ajax({
                    url: `/inventory/${id}/edit`,
                    type: 'GET',
                    success: function(response) {
                        $('#edit_item').val(response.item);
                        $('#edit_stock_awal').val(response.stock_awal);
                        $('#edit_unit_awal').val(response.unit_awal);
                        $('#edit_stock_akhir').val(response.stock_akhir);
                        $('#edit_unit_akhir').val(response.unit_akhir);
                        $('#edit_note').val(response.note);
                        $('#edit_posisi').val(response.posisi);

                        // Update form action
                        $('#editInventoryForm').attr('action', `/inventory/${id}/update`);

                        // Show modal
                        $('#modal-edit-inventory').modal('show');
                    },
                    error: function(xhr) {
                        alert('Error loading inventory data');
                    }
                });
            });

            // Handle form submission
            $('#editInventoryForm').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                const url = form.attr('action');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        $('#modal-edit-inventory').modal('hide');
                        $('#inventory-table').DataTable().ajax.reload();

                       
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON?.errors;
                        let errorMsg = 'Error updating inventory';

                        if (errors) {
                            errorMsg = Object.values(errors).flat().join('\n');
                        }

                        alert(errorMsg);
                    }
                });
            });
        });
    </script>
@endpush
