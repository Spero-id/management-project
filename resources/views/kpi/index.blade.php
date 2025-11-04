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
            <h2 class="page-title">KPI</h2>
        </div>
        <!-- Page title actions -->
        <div class="col-auto ms-auto d-print-none">

            <div class="btn-list">


                <a href="" class="btn btn-success btn-5 d-none d-sm-inline-block">
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
                    Export
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
            <!-- END MODAL -->
        </div>
    </div>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> KPI</h3>
                </div>
                <div class="card-body border-bottom py-3">
                    <div class="d-flex">
                        <div class="text-secondary">
                            Show
                            <div class="mx-2 d-inline-block">
                                <input type="text" id="pageLength" class="form-control form-control-sm" value="8"
                                    size="3" aria-label="Prospects count">
                            </div>
                            entries
                        </div>
                        <div class="ms-auto text-secondary">
                            Search:
                            <div class="ms-2 d-inline-block">
                                <input type="text" id="customSearch" class="form-control form-control-sm"
                                    aria-label="Search Prospect">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <!-- Custom Search and Page Length Controls -->
                    <div class="table-responsive">
                        <table id="example" class="table table-selectable card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>

                                    <th class="w-1">
                                        No.
                                        <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-up -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-sm icon-thick icon-2">
                                            <path d="M6 15l6 -6l6 6"></path>
                                        </svg>
                                    </th>
                                    <th>Sales Name</th>
                                    <th>Project / Goal</th>
                                    <th>Target (Rp)</th>
                                    <th>Progress</th>

                                    <th></th>
                                </tr>
                            </thead>
                            <tr>

                                <td><span class="text-secondary">1</span></td>
                                <td><a href="#" class="text-reset" tabindex="-1">Andi Susanto</a></td>
                                <td><a href="#" class="text-reset" tabindex="-1">Website Revamp / 5 deals</a></td>
                                <td>Rp 250.000.000</td>
                                <td>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-success" style="width: 85%" role="progressbar"
                                            aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">
                                            <span class="visually-hidden">85% Complete</span>
                                        </div>
                                    </div>
                                    <small class="text-muted">85%</small>
                                </td>

                                <td>
                                    <a href="{{ route('kpi.show', 1) }}" class="btn btn-icon" aria-label="View KPI">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                            <path
                                                d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                        </svg>
                                    </a>

                                </td>
                            </tr>
                            <tr>

                                <td><span class="text-secondary">2</span></td>
                                <td><a href="#" class="text-reset" tabindex="-1">Budi Hartono</a></td>
                                <td><a href="#" class="text-reset" tabindex="-1">Mobile App Sales / 3 deals</a>
                                </td>
                                <td>Rp 180.000.000</td>
                                <td>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-success" style="width: 60%" role="progressbar"
                                            aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">
                                            <span class="visually-hidden">60% Complete</span>
                                        </div>
                                    </div>
                                    <small class="text-muted">60%</small>
                                </td>

                                <td>
                                    <a href="{{ route('kpi.show', 1) }}" class="btn btn-icon" aria-label="View KPI">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                            <path
                                                d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                        </svg>
                                    </a>

                                </td>
                            </tr>
                            <tr>

                                <td><span class="text-secondary">3</span></td>
                                <td><a href="#" class="text-reset" tabindex="-1">Citra Lestari</a></td>
                                <td><a href="#" class="text-reset" tabindex="-1">Analytics Suite / 4 deals</a>
                                </td>
                                <td>Rp 320.000.000</td>
                                <td>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-success" style="width: 45%" role="progressbar"
                                            aria-valuenow="45" aria-valuemin="0" aria-valuemax="100">
                                            <span class="visually-hidden">45% Complete</span>
                                        </div>
                                    </div>
                                    <small class="text-muted">45%</small>
                                </td>

                                <td>
                                    <a href="{{ route('kpi.show', 1) }}" class="btn btn-icon" aria-label="View KPI">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                            <path
                                                d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                        </svg>
                                    </a>

                                </td>
                            </tr>
                            <tr>

                                <td><span class="text-secondary">4</span></td>
                                <td><a href="#" class="text-reset" tabindex="-1">Dewi Kartika</a></td>
                                <td><a href="#" class="text-reset" tabindex="-1">Cloud Migration / 2 deals</a>
                                </td>
                                <td>Rp 400.000.000</td>
                                <td>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-success" style="width: 92%" role="progressbar"
                                            aria-valuenow="92" aria-valuemin="0" aria-valuemax="100">
                                            <span class="visually-hidden">92% Complete</span>
                                        </div>
                                    </div>
                                    <small class="text-muted">92%</small>
                                </td>

                                <td>
                                    <a href="{{ route('kpi.show', 1) }}" class="btn btn-icon" aria-label="View KPI">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                            <path
                                                d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                        </svg>
                                    </a>

                                </td>
                            </tr>
                            <tr>

                                <td><span class="text-secondary">5</span></td>
                                <td><a href="#" class="text-reset" tabindex="-1">Eka Pratama</a></td>
                                <td><a href="#" class="text-reset" tabindex="-1">ERP Deals / 1 deal</a></td>
                                <td>Rp 120.000.000</td>
                                <td>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-success" style="width: 15%" role="progressbar"
                                            aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">
                                            <span class="visually-hidden">15% Complete</span>
                                        </div>
                                    </div>
                                    <small class="text-muted">15%</small>
                                </td>

                                <td>
                                    <a href="{{ route('kpi.show', 1) }}" class="btn btn-icon" aria-label="View KPI">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                            <path
                                                d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                        </svg>
                                    </a>

                                </td>
                            </tr>


                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row g-2 justify-content-center justify-content-sm-between">
                        <div class="col-auto d-flex align-items-center">
                            <p id="tableInfo" class="m-0 text-secondary">Showing <strong>1 to 8</strong> of <strong>8
                                    entries</strong></p>
                        </div>
                        <div class="col-auto">
                            <ul id="tablePagination" class="pagination m-0 ms-auto">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                        <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-left -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                            <path d="M15 6l-6 6l6 6"></path>
                                        </svg>
                                    </a>
                                </li>
                                <li class="page-item active">
                                    <a class="page-link" href="#">1</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">
                                        <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-right -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                            <path d="M9 6l6 6l-6 6"></path>
                                        </svg>
                                    </a>
                                </li>
                            </ul>
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

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#example').DataTable({
                "searching": true,
                "dom": 't', // Only show table, hide default controls
                "pageLength": 8,
                "lengthChange": false,
                "info": false, // We'll handle info manually
                "ordering": true,
                "responsive": true,
                "paging": true, // Enable DataTable's pagination
                "drawCallback": function(settings) {
                    updateTableInfo();
                    updatePagination();
                }
            });

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

            // Functions for updating table info and pagination (you may need to implement these)
            function updateTableInfo() {
                // Update the table information display
                // This function should be implemented based on your needs
            }

            function updatePagination() {
                // Update pagination controls
                // This function should be implemented based on your needs
            }
        });
    </script>
@endpush
