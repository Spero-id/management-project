@extends('layouts.app')

@push('styles')
<style>
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 1px 6px rgba(0, 0, 0, 0.05);
    }

    .card-header {
        background: #fff;
        border-bottom: 1px solid #e9ecef;
        padding: 1rem 1.25rem;
    }

    .card-title {
        font-weight: 700;
        font-size: 1.05rem;
        color: #2b2b2b;
        margin: 0;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table thead th {
        background-color: #f8fafc;
        font-size: 0.9rem;
        color: #6c757d;
        font-weight: 600;
        padding: 0.9rem 1rem;
        border-bottom: 1px solid #dee2e6;
    }

    .table tbody td {
        font-size: 0.9rem;
        color: #343a40;
        padding: 0.85rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f5;
    }

    .table tbody tr:hover {
        background: #f9fbfd;
    }

    
    /* Warna badge */
.badge.bg-success {
    background-color: #d4edda !important;
    color: #155724 !important;
}

.badge.bg-warning {
    background-color: #fff3cd !important;
    color: #856404 !important;
}

.badge.bg-danger {
    background-color: #f8d7da !important;
    color: #842029 !important;
}


    /* Tombol aksi minimalis */
    .action-btn {
        display: inline-block;
        text-decoration: none;
        font-weight: 600;
        border-radius: 6px;
        font-size: 0.8rem;
        padding: 0.4rem 0.7rem;
        transition: all 0.25s ease;
    }

    .btn-detail {
        background-color: #eef4ff;
        color: #0d6efd;
        border: 1px solid #dbe3ff;
    }

    .btn-detail:hover {
        background-color: #d9e6ff;
        color: #0b5ed7;
    }

    .btn-notes-warning {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeeba;
    }

    .btn-notes-danger {
        background-color: #f8d7da;
        color: #842029;
        border: 1px solid #f5c2c7;
    }

    /* Search & Dropdown */
    #topSearchInput {
        width: 200px;
        height: 38px;
        font-size: 0.9rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        padding: 0.4rem 0.75rem;
    }

    #topSearchInput:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.25);
    }

    .dropdown-toggle {
        border-radius: 8px;
        font-weight: 500;
        height: 38px;
        font-size: 0.9rem;
    }

    /* Pagination & info */
    .dataTables_info {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .pagination {
        margin: 0;
        gap: 0.3rem;
    }

    .page-item .page-link {
        border-radius: 6px;
        border: none;
        color: #6c757d;
        font-size: 0.85rem;
        padding: 0.35rem 0.7rem;
        transition: all 0.2s ease;
    }

    .page-item.active .page-link {
        background-color: #0d6efd;
        color: #fff;
        box-shadow: 0 2px 6px rgba(13,110,253,0.25);
    }

    .page-item .page-link:hover {
        background-color: #e9ecef;
        color: #212529;
    }
</style>
@endpush

@section('header')
<div class="row g-2 align-items-center mb-3">
    <div class="col">
        <div class="page-pretitle text-secondary fw-semibold">Overview</div>
        <h2 class="page-title fw-bold fs-3">Project List</h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list d-flex align-items-center gap-2">
            <input type="text" id="topSearchInput" class="form-control form-control-sm" placeholder="Search project...">
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                <h3 class="card-title mb-2 mb-md-0">Projects</h3>

                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="filterDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span id="currentFilter">All</span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                        <li><a class="dropdown-item filter-option active" data-filter="all" href="#">All</a></li>
                        <li><a class="dropdown-item filter-option" data-filter="goal" href="#">Project Goal</a></li>
                        <li><a class="dropdown-item filter-option" data-filter="pending" href="#">Project Pending</a></li>
                        <li><a class="dropdown-item filter-option" data-filter="cancel" href="#">Project Cancel</a></li>
                    </ul>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive px-3 pb-3">
                    <table id="projectTable" class="table align-middle text-nowrap">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Project Name</th>
                                <th>Type</th>
                                <th>Client</th>
                                <th>Progress</th>
                                <th>Status</th>
                                <th>Budget</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr data-status="goal">
                                <td>1</td>
                                <td>E-Commerce Platform</td>
                                <td>Web Development</td>
                                <td>TechCorp</td>
                                <td>85%</td>
                                <td><span class="badge bg-success">Goal</span></td>
                                <td>Rp 1.875.000.000</td>
                                <td class="text-end">
                                    <a href="{{ route('project.detail', 1) }}" class="action-btn btn-detail">View Detail</a>
                                </td>
                            </tr>
                            <tr data-status="pending">
                                <td>2</td>
                                <td>Inventory System</td>
                                <td>ERP</td>
                                <td>LogisticPro</td>
                                <td>15%</td>
                                <td><span class="badge bg-warning text-dark">Pending</span></td>
                                <td>Rp 1.100.000.000</td>
                                <td class="text-end">
                                    <a href="{{ route('project.detail', 2) }}" class="action-btn btn-detail me-2">Detail</a>
                                    <a href="{{ url('/project/notes/pending') }}" class="action-btn btn-notes-warning">Notes</a>
                                </td>
                            </tr>
                            <tr data-status="cancel">
                                <td>3</td>
                                <td>Cloud Migration</td>
                                <td>Infrastructure</td>
                                <td>CloudTech</td>
                                <td>5%</td>
                                <td><span class="badge bg-danger">Cancel</span></td>
                                <td>Rp 2.970.000.000</td>
                                <td class="text-end">
                                    <a href="{{ route('project.detail', 3) }}" class="action-btn btn-detail me-2">Detail</a>
                                    <a href="{{ url('/project/notes/cancel') }}" class="action-btn btn-notes-danger">Notes</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer p-0">
                <div id="dataTableFooter" class="d-flex justify-content-between align-items-center p-3"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>

<script>
$(document).ready(function () {
    var table = $('#projectTable').DataTable({
        pageLength: 3,
        lengthChange: false,
        searching: true,
        language: {
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            paginate: { previous: "&lt;", next: "&gt;" }
        },
        dom: 't<"bottom d-flex justify-content-between align-items-center p-3"ip>'
    });

    $('#topSearchInput').on('keyup', function () {
        table.search(this.value).draw();
    });

    $('.filter-option').on('click', function (e) {
        e.preventDefault();
        $('.filter-option').removeClass('active');
        $(this).addClass('active');

        var filter = $(this).data('filter');
        $('#currentFilter').text($(this).text());

        if (filter === 'all') {
            $('#projectTable tbody tr').show();
        } else {
            $('#projectTable tbody tr').each(function () {
                var status = $(this).data('status');
                if (status === filter) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
    });
});
</script>
@endpush
