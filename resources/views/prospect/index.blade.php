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
            <h2 class="page-title">Prospect</h2>
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


                <a href="{{ route('prospect.create') }}" class="btn btn-primary btn-5 d-none d-sm-inline-block">
                    <!-- Download SVG icon from http://tabler.io/icons/icon/plus -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-2">
                        <path d="M12 5l0 14" />
                        <path d="M5 12l14 0" />
                    </svg>
                    Create Prospect
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
            <!-- Delete Confirmation Modal -->
            <div class="modal modal-blur fade" id="modal-delete-prospect" tabindex="-1" role="dialog" aria-hidden="true">
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
                                Anda akan menghapus prospect <strong id="deleteProspectName"></strong>.
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
                                        <form id="deleteProspectForm" method="POST" style="display: inline;">
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
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
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
                    <h3 class="card-title">Prospects</h3>
                </div>
                <div class="card-body border-bottom py-3">
                    <div class="d-flex">
                        <div class="text-secondary">
                            Show
                            <div class="mx-2 d-inline-block">
                                <input type="text" id="pageLength" class="form-control form-control-sm"
                                    value="8" size="3" aria-label="Prospects count">
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
                        <table id="example"
                            class="table table-selectable card-table table-vcenter text-nowrap datatable">
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
                                    <th>Company</th>
                                    <th>Customer Name</th>
                                    <th>No Quotation</th>
                                    <th>Target Deal</th>
                                    <th>Status</th>
                                    <th>Progress</th>
                                    <th>Create Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($prospects as $index => $prospect)
                                    <tr>
                                        <td><span class="text-secondary">{{ $index + 1 }}</span></td>
                                        <td><a href="#" class="text-reset"
                                                tabindex="-1">{{ $prospect->company }}</a>
                                        </td>
                                        <td>{{ $prospect->customer_name }}</td>
                                        <td>
                                            @if ($prospect->quotations->isNotEmpty())
                                                <ul class="list-unstyled mb-0">
                                                    @foreach ($prospect->quotations as $quotation)
                                                        <li>
                                                            <a href="{{ route('quotation.show', $quotation->id) }}"
                                                                class="text-primary text-decoration-none"
                                                                title="View quotation details">
                                                                {{ $quotation->quotation_number }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-muted">No Quotation</span>
                                            @endif
                                        </td>
                                        <td>{{ $prospect->target_deal }}</td>
                                        <td>

                                            <span class="badge me-1"
                                                style="background-color: {{ $prospect->prospectStatus->color }};"></span>
                                            {{ $prospect->prospectStatus->name }}
                                        </td>
                                        <td>
                                            @php
                                                $progress = $prospect->prospectStatus->persentage ?? 0;
                                            @endphp
                                            <div class="d-flex align-items-center">
                                                <div class="progress progress-md me-2" style="flex: 1; min-width: 80px;">
                                                    <div class="progress-bar" style="width: {{ $progress }}%"
                                                        role="progressbar" aria-valuenow="{{ $progress }}"
                                                        aria-valuemin="0" aria-valuemax="100">
                                                        <span class="visually-hidden">{{ $progress }}% Complete</span>
                                                    </div>
                                                </div>
                                                <small class="text-secondary fw-medium">{{ $progress }}%</small>
                                            </div>
                                        </td>
                                        <td>{{ $prospect->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="{{ route('prospect.show', $prospect->id) }}" class="btn btn-icon"
                                                aria-label="View" title="View prospect">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                    <path
                                                        d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                </svg>
                                            </a>
                                            @if(($prospect->prospectStatus->persentase ?? 0) < 100)
                                                <a href="{{ route('prospect.edit', $prospect->id) }}" class="btn btn-icon"
                                                    aria-label="Edit" title="Edit prospect">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                        <path
                                                            d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                        <path d="M16 5l3 3" />
                                                    </svg>
                                                </a>
                                                <button class="btn btn-icon delete-prospect-btn" data-bs-toggle="modal"
                                                    data-bs-target="#modal-delete-prospect"
                                                    data-prospect-id="{{ $prospect->id }}"
                                                    data-prospect-name="{{ $prospect->customer_name }}"
                                                    aria-label="Delete Prospect">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M4 7l16 0" />
                                                        <path d="M10 11l0 6" />
                                                        <path d="M14 11l0 6" />
                                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                    </svg>
                                                </button>
                                            @else
                                                <span class="btn btn-icon btn-outline-primary" disabled title="Prospect sudah selesai (100%) - tidak dapat diedit atau dihapus">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                        <path d="m9 12 2 2 4-4"></path>
                                                    </svg>
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

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

            // Handle delete prospect modal
            $('.delete-prospect-btn').on('click', function() {
                var prospectId = $(this).data('prospect-id');
                var prospectName = $(this).data('prospect-name');

                // Update modal content
                $('#deleteProspectName').text(prospectName);

                // Update form action URL - build the URL properly
                var baseUrl = '{{ url('/prospect') }}';
                $('#deleteProspectForm').attr('action', baseUrl + '/' + prospectId);
            });
        });
    </script>
@endpush
