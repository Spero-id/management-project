@extends('layouts.app')

@push('styles')
    <style>
        /* Reuse some styles from index for consistent look */
        .card-table {
            margin-bottom: 0;
        }

        .icon-sm {
            width: 1rem;
            height: 1rem;
        }

        .badge {
            font-size: 0.75em;
        }
    </style>
@endpush

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <div class="page-pretitle">Detail</div>
            <h2 class="page-title">KPI </h2>
        </div>
        <div class="col-auto ms-auto d-print-none">
            

            <div class="btn-list">
                <a href="{{ route('kpi.index') }}" class="btn btn-outline-light">
                    Back to KPI
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">KPI: Andi Susanto</h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label text-muted">Owner</label>
                            <div class="fw-bold">Andi Susanto</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted">Goal</label>
                            <div class="fw-bold">Increase Online Sales</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted">Target (Rp)</label>
                            <div class="fw-bold text-success">Rp 1.000.000.000</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-muted">Progress</label>
                            <div class="d-flex align-items-center">
                                <div class="progress flex-fill me-2" style="height: 8px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 72%"></div>
                                </div>
                                <span class="fw-bold">72%</span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-muted">Period</label>
                            <div class="fw-bold">Jan 2025 - Dec 2025</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-muted">Status</label>
                            <div><span class="badge bg-primary text-white">Active</span></div>
                        </div>

                        <div class="col-12">
                            <label class="form-label text-muted">Description</label>
                            <div class="card bg-light">
                                <div class="card-body">
                                    KPI to track sales performance across channels and improve conversion rate by 20%.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Projects Table Section -->
    <div class="row row-cards mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Projects Contributing to this KPI</h3>
                </div>
                <div class="card-body border-bottom py-3">
                    <div class="d-flex">
                        <div class="text-secondary">Show
                            <div class="mx-2 d-inline-block">
                                <input type="text" id="pageLengthProjects" class="form-control form-control-sm"
                                    value="8" size="3">
                            </div>
                            entries
                        </div>
                        <div class="ms-auto text-secondary">Search:
                            <div class="ms-2 d-inline-block">
                                <input type="text" id="customSearchProjects" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="projectsTable"
                            class="table table-selectable card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th class="w-1">No.</th>
                                    <th>Project Name</th>
                                    <th>Client</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Progress</th>
                                    <th>Value (Rp)</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="text-secondary">1</span></td>
                                    <td>Website Revamp</td>
                                    <td>PT Fashion Nusantara</td>
                                    <td>2025-01-15</td>
                                    <td>2025-07-30</td>
                                    <td>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success" style="width: 85%"></div>
                                        </div>
                                        <small class="text-muted">85%</small>
                                    </td>
                                    <td>Rp 250.000.000</td>
                                    <td>
                                        <a href="#" class="btn btn-icon" aria-label="View Project">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icon-tabler-eye">
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
                                    <td>Mobile App Sales</td>
                                    <td>PT Mobile Indo</td>
                                    <td>2025-03-01</td>
                                    <td>2025-09-15</td>
                                    <td>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-warning" style="width: 60%"></div>
                                        </div>
                                        <small class="text-muted">60%</small>
                                    </td>
                                    <td>Rp 180.000.000</td>
                                    <td>
                                        <a href="#" class="btn btn-icon" aria-label="View Project">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icon-tabler-eye">
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
                            <p id="tableInfoProjects" class="m-0 text-secondary">Showing <strong>1 to 2</strong> of
                                <strong>2 entries</strong>
                            </p>
                        </div>
                        <div class="col-auto">
                            <ul id="tablePaginationProjects" class="pagination m-0 ms-auto">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                            <path d="M15 6l-6 6l6 6"></path>
                                        </svg>
                                    </a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">
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

    <script>
        $(document).ready(function() {
            var projectsTable = $('#projectsTable').DataTable({
                "searching": true,
                "dom": 't',
                "pageLength": 8,
                "lengthChange": false,
                "info": false,
                "ordering": true,
                "responsive": true,
                "paging": true,
                "drawCallback": function(settings) {
                    // placeholder for update functions
                }
            });

            $('#customSearchProjects').on('keyup', function() {
                projectsTable.search(this.value).draw();
            });

            $('#pageLengthProjects').on('keyup change', function() {
                var length = parseInt(this.value);
                if (!isNaN(length) && length > 0) {
                    projectsTable.page.len(length).draw();
                }
            });
        });
    </script>
@endpush
