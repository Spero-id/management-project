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

        .color-preview {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 1px solid #dee2e6;
            display: inline-block;
        }
    </style>
@endpush

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <!-- Page pre-title -->
            <div class="page-pretitle">Management</div>
            <h2 class="page-title">Settings</h2>
        </div>
    </div>
@endsection

@section('content')
    <!-- Flash Messages -->
    @if (session('success'))
        <div class="alert  alert-important alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon alert-icon">
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
        <div class="alert  alert-important alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon alert-icon">
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

    <!-- Division Table -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Divisions</h3>
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#modal-create-division">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-2">
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Create Division
                    </a>
                </div>
                <div class="card-body border-bottom py-3">
                    <div class="d-flex">
                        <div class="text-secondary">
                            Show
                            <div class="mx-2 d-inline-block">
                                <input type="text" id="divisionPageLength" class="form-control form-control-sm"
                                    value="8" size="3" aria-label="Division count">
                            </div>
                            entries
                        </div>
                        <div class="ms-auto text-secondary">
                            Search:
                            <div class="ms-2 d-inline-block">
                                <input type="text" id="divisionSearch" class="form-control form-control-sm"
                                    aria-label="Search Division">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="division-table-loading" class="position-relative" style="display: none;">
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
                    <div class="table-responsive">
                        <table id="divisionTable"
                            class="table table-selectable card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th class="w-1">ID</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th class="w-1">Actions</th>
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
                            <p id="divisionTableInfo" class="m-0 text-secondary"></p>
                        </div>
                        <div class="col-auto">
                            <ul id="divisionTablePagination" class="pagination m-0 ms-auto"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Prospect Status Table -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Prospect Status</h3>
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#modal-create-prospect-status">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-2">
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Create Status
                    </a>
                </div>
                <div class="card-body border-bottom py-3">
                    <div class="d-flex">
                        <div class="text-secondary">
                            Show
                            <div class="mx-2 d-inline-block">
                                <input type="text" id="statusPageLength" class="form-control form-control-sm"
                                    value="8" size="3" aria-label="Status count">
                            </div>
                            entries
                        </div>
                        <div class="ms-auto text-secondary">
                            Search:
                            <div class="ms-2 d-inline-block">
                                <input type="text" id="statusSearch" class="form-control form-control-sm"
                                    aria-label="Search Status">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="status-table-loading" class="position-relative" style="display: none;">
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
                    <div class="table-responsive">
                        <table id="statusTable"
                            class="table table-selectable card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th class="w-1">ID</th>
                                    <th>Name</th>
                                    <th>Percentage</th>
                                    <th>Color</th>
                                    <th class="w-1">Actions</th>
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
                            <p id="statusTableInfo" class="m-0 text-secondary"></p>
                        </div>
                        <div class="col-auto">
                            <ul id="statusTablePagination" class="pagination m-0 ms-auto"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Installation Table -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Installation</h3>
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#modal-create-installation">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-2">
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Create Installation
                    </a>
                </div>
                <div class="card-body border-bottom py-3">
                    <div class="d-flex">
                        <div class="text-secondary">
                            Show
                            <div class="mx-2 d-inline-block">
                                <input type="text" id="installationPageLength" class="form-control form-control-sm"
                                    value="8" size="3" aria-label="Installation count">
                            </div>
                            entries
                        </div>
                        <div class="ms-auto text-secondary">
                            Search:
                            <div class="ms-2 d-inline-block">
                                <input type="text" id="installationSearch" class="form-control form-control-sm"
                                    aria-label="Search Installation">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="installation-table-loading" class="position-relative" style="display: none;">
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
                    <div class="table-responsive">
                        <table id="installationTable"
                            class="table table-selectable card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th class="w-1">ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Proportional</th>
                                    <th class="w-1">Actions</th>
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
                            <p id="installationTableInfo" class="m-0 text-secondary"></p>
                        </div>
                        <div class="col-auto">
                            <ul id="installationTablePagination" class="pagination m-0 ms-auto"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Accommodation Table -->
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Accommodation</h3>
                </div>
                <div class="card-body border-bottom py-3">
                    <div class="d-flex">
                        <div class="text-secondary">
                            Show
                            <div class="mx-2 d-inline-block">
                                <input type="text" id="accommodationPageLength" class="form-control form-control-sm"
                                    value="8" size="3" aria-label="Accommodation count">
                            </div>
                            entries
                        </div>
                        <div class="ms-auto text-secondary">
                            Search:
                            <div class="ms-2 d-inline-block">
                                <input type="text" id="accommodationSearch" class="form-control form-control-sm"
                                    aria-label="Search Accommodation">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="accommodation-table-loading" class="position-relative" style="display: none;">
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
                    <div class="table-responsive">
                        <table id="accommodationTable"
                            class="table table-selectable card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th class="w-1">ID</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th class="w-1">Actions</th>
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
                            <p id="accommodationTableInfo" class="m-0 text-secondary"></p>
                        </div>
                        <div class="col-auto">
                            <ul id="accommodationTablePagination" class="pagination m-0 ms-auto"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Currency Exchange Rate Table -->
    <div class="row justify-content-center mb-4 mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Currency Exchange Rate</h3>
                   
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Currency</th>
                                    <th scope="col">Exchange Rate</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>USD</td>
                                    <td>{{ $dolarRateSetting }}</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#modal-edit-currency-exchange">Edit</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Division Modals -->
    <!-- Create Division Modal -->
    <div class="modal modal-blur fade" id="modal-create-division" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-header">
                    <h5 class="modal-title">Create Division</h5>
                </div>
                <form method="POST" action="{{ route('division.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="divisionCode" class="form-label">Code</label>
                            <input type="text" class="form-control" id="divisionCode" name="kode" required>
                        </div>
                        <div class="mb-3">
                            <label for="divisionName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="divisionName" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Division Modal -->
    <div class="modal modal-blur fade" id="modal-edit-division" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-header">
                    <h5 class="modal-title">Edit Division</h5>
                </div>
                <form method="POST" action="" id="editDivisionForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editDivisionCode" class="form-label">Code</label>
                            <input type="text" class="form-control" id="editDivisionCode" name="kode" required>
                        </div>
                        <div class="mb-3">
                            <label for="editDivisionName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="editDivisionName" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Division Modal -->
    <div class="modal modal-blur fade" id="modal-delete-division" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-danger"></div>
                <div class="modal-body text-center py-4">
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
                        Anda akan menghapus division <strong id="deleteDivisionName"></strong>.
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
                                <form id="deleteDivisionForm" method="POST" style="display: inline;">
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

    <!-- Prospect Status Modals -->
    <!-- Create Prospect Status Modal -->
    <div class="modal modal-blur fade" id="modal-create-prospect-status" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-header">
                    <h5 class="modal-title">Create Prospect Status</h5>
                </div>
                <form method="POST" action="{{ route('prospect-status.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="statusName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="statusName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="statusPercentage" class="form-label">Percentage</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="statusPercentage" name="persentage"
                                    min="0" max="100" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="statusColor" class="form-label">Color</label>
                            <input type="color" class="form-control form-control-color" id="statusColor"
                                name="color" value="#0073e6" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Prospect Status Modal -->
    <div class="modal modal-blur fade" id="modal-edit-prospect-status" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-header">
                    <h5 class="modal-title">Edit Prospect Status</h5>
                </div>
                <form method="POST" action="" id="editProspectStatusForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editStatusName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="editStatusName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editStatusPercentage" class="form-label">Percentage</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="editStatusPercentage" name="persentage"
                                    min="0" max="100" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editStatusColor" class="form-label">Color</label>
                            <input type="color" class="form-control form-control-color" id="editStatusColor"
                                name="color" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Prospect Status Modal -->
    <div class="modal modal-blur fade" id="modal-delete-prospect-status" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-danger"></div>
                <div class="modal-body text-center py-4">
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
                        Anda akan menghapus status <strong id="deleteProspectStatusName"></strong>.
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
                                <form id="deleteProspectStatusForm" method="POST" style="display: inline;">
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

    <!-- Installation Modals -->
    <!-- Create Installation Modal -->
    <div class="modal modal-blur fade" id="modal-create-installation" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-header">
                    <h5 class="modal-title">Create Installation</h5>
                </div>
                <form method="POST" action="{{ route('installation-category.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="installationName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="installationName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="installationDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="installationDescription" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="installationProportional" class="form-label">Proportional</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="installationProportional"
                                    name="proportional" min="0" max="999.99" step="0.01" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Installation Modal -->
    <div class="modal modal-blur fade" id="modal-edit-installation" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-header">
                    <h5 class="modal-title">Edit Installation</h5>
                </div>
                <form method="POST" action="" id="editInstallationForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editInstallationName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="editInstallationName" name="name"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="editInstallationDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="editInstallationDescription" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editInstallationProportional" class="form-label">Proportional</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="editInstallationProportional"
                                    name="proportional" min="0" max="999.99" step="0.01" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Installation Modal -->
    <div class="modal modal-blur fade" id="modal-delete-installation" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-danger"></div>
                <div class="modal-body text-center py-4">
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
                        Anda akan menghapus installation <strong id="deleteInstallationName"></strong>.
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
                                <form id="deleteInstallationForm" method="POST" style="display: inline;">
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

    <!-- Accommodation Modals -->
    <!-- Edit Accommodation Modal -->
    <div class="modal modal-blur fade" id="modal-edit-accommodation" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-header">
                    <h5 class="modal-title">Edit Accommodation</h5>
                </div>
                <form method="POST" action="" id="editAccommodationForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editAccommodationPrice" class="form-label">Price</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="editAccommodationPrice" name="price"
                                    min="0" step="0.01" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Currency Exchange Rate Modal -->
    <div class="modal modal-blur fade" id="modal-edit-currency-exchange" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-header">
                    <h5 class="modal-title">Edit Currency Exchange Rate</h5>
                </div>
                <form method="POST" action="{{ route('settings.currency-exchange.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="exchangeRate" class="form-label">Dolar Rate</label>
                            <input type="number" class="form-control" id="exchangeRate" name="dolar_rate"
                                step="0.01" value="{{ $dolarRateSetting }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
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
            // Initialize Division Table
            var divisionTable = $('#divisionTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('division.datatable') }}",
                    "type": "GET"
                },
                "columns": [{
                        "data": "id"
                    },
                    {
                        "data": "kode"
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": null,
                        "orderable": false,
                        "searchable": false,
                        "render": function(data, type, row) {
                            return `
                                <a href="#" class="btn btn-icon edit-division-btn" data-bs-toggle="modal" 
                                   data-bs-target="#modal-edit-division" data-division-id="${row.id}" 
                                   data-division-code="${row.kode}" data-division-name="${row.name}" 
                                   aria-label="Edit Division" title="Edit Division">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                </a>
                                <button class="btn btn-icon delete-division-btn" data-bs-toggle="modal" 
                                        data-bs-target="#modal-delete-division" data-division-id="${row.id}" 
                                        data-division-name="${row.name}" aria-label="Delete Division">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                </button>
                            `;
                        }
                    }
                ],
                "searching": true,
                "dom": 't',
                "pageLength": 8,
                "lengthChange": false,
                "info": false,
                "ordering": true,
                "responsive": true,
                "paging": true,
                "drawCallback": function(settings) {
                    updateDivisionTableInfo();
                    updateDivisionPagination();
                    $('#division-table-loading').hide();
                },
                "preDrawCallback": function(settings) {
                    $('#division-table-loading').show();
                }
            });

            // Initialize Prospect Status Table
            var statusTable = $('#statusTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('prospect-status.datatable') }}",
                    "type": "GET"
                },
                "columns": [{
                        "data": "id"
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "persentage",
                        "render": function(data, type, row) {
                            return data + '%';
                        }
                    },
                    {
                        "data": "color",
                        "render": function(data, type, row) {
                            return `<div class="d-flex align-items-center">
                                <span class="color-preview me-2" style="background-color: ${data}"></span>
                                <span>${data}</span>
                            </div>`;
                        }
                    },
                    {
                        "data": null,
                        "orderable": false,
                        "searchable": false,
                        "render": function(data, type, row) {
                            return `
                                <a href="#" class="btn btn-icon edit-prospect-status-btn" data-bs-toggle="modal" 
                                   data-bs-target="#modal-edit-prospect-status" data-status-id="${row.id}" 
                                   data-status-name="${row.name}" data-status-percentage="${row.persentage}" 
                                   data-status-color="${row.color}" aria-label="Edit Status" title="Edit Status">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                </a>
                                <button class="btn btn-icon delete-prospect-status-btn" data-bs-toggle="modal" 
                                        data-bs-target="#modal-delete-prospect-status" data-status-id="${row.id}" 
                                        data-status-name="${row.name}" aria-label="Delete Status">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                </button>
                            `;
                        }
                    }
                ],
                "searching": true,
                "dom": 't',
                "pageLength": 8,
                "lengthChange": false,
                "info": false,
                "ordering": true,
                "responsive": true,
                "paging": true,
                "drawCallback": function(settings) {
                    updateStatusTableInfo();
                    updateStatusPagination();
                    $('#status-table-loading').hide();
                },
                "preDrawCallback": function(settings) {
                    $('#status-table-loading').show();
                }
            });

            // Initialize Accommodation Table
            var accommodationTable = $('#accommodationTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('accommodation-category.datatable') }}",
                    "type": "GET"
                },
                "columns": [{
                        "data": "id"
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "price"
                    },
                    {
                        "data": null,
                        "orderable": false,
                        "searchable": false,
                        "render": function(data, type, row) {
                            return `
                                <a href="#" class="btn btn-icon edit-accommodation-btn" data-bs-toggle="modal" 
                                   data-bs-target="#modal-edit-accommodation" data-accommodation-id="${row.id}" 
                                   data-accommodation-price="${row.price.replace('Rp ', '').replace(/\./g, '').replace(/,/g, '')}" 
                                   aria-label="Edit Accommodation" title="Edit Accommodation">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                </a>
                            `;
                        }
                    }
                ],
                "searching": true,
                "dom": 't',
                "pageLength": 8,
                "lengthChange": false,
                "info": false,
                "ordering": true,
                "responsive": true,
                "paging": true,
                "drawCallback": function(settings) {
                    updateAccommodationTableInfo();
                    updateAccommodationPagination();
                    $('#accommodation-table-loading').hide();
                },
                "preDrawCallback": function(settings) {
                    $('#accommodation-table-loading').show();
                }
            });

            // Initialize Installation Table
            var installationTable = $('#installationTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('installation-category.datatable') }}",
                    "type": "GET"
                },
                "columns": [{
                        "data": "id"
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "description",
                        "render": function(data, type, row) {
                            if (data && data.length > 50) {
                                return data.substring(0, 50) + '...';
                            }
                            return data || '-';
                        }
                    },
                    {
                        "data": "proportional"
                    },
                    {
                        "data": null,
                        "orderable": false,
                        "searchable": false,
                        "render": function(data, type, row) {
                            return `
                                <a href="#" class="btn btn-icon edit-installation-btn" data-bs-toggle="modal" 
                                   data-bs-target="#modal-edit-installation" data-installation-id="${row.id}" 
                                   data-installation-name="${row.name}" data-installation-description="${row.description || ''}" 
                                   data-installation-proportional="${row.proportional.replace('%', '')}" 
                                   aria-label="Edit Installation" title="Edit Installation">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                </a>
                                <button class="btn btn-icon delete-installation-btn" data-bs-toggle="modal" 
                                        data-bs-target="#modal-delete-installation" data-installation-id="${row.id}" 
                                        data-installation-name="${row.name}" aria-label="Delete Installation">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                </button>
                            `;
                        }
                    }
                ],
                "searching": true,
                "dom": 't',
                "pageLength": 8,
                "lengthChange": false,
                "info": false,
                "ordering": true,
                "responsive": true,
                "paging": true,
                "drawCallback": function(settings) {
                    updateInstallationTableInfo();
                    updateInstallationPagination();
                    $('#installation-table-loading').hide();
                },
                "preDrawCallback": function(settings) {
                    $('#installation-table-loading').show();
                }
            });

            // Show loading on initial load
            $('#division-table-loading').show();
            $('#status-table-loading').show();
            $('#installation-table-loading').show();
            $('#accommodation-table-loading').show();

            // Division Table Search
            $('#divisionSearch').on('keyup', function() {
                divisionTable.search(this.value).draw();
            });

            $('#divisionSearch').on('search', function() {
                if (this.value === '') {
                    divisionTable.search('').draw();
                }
            });

            // Division Page length functionality
            $('#divisionPageLength').on('keyup change', function() {
                var length = parseInt(this.value);
                if (!isNaN(length) && length > 0) {
                    divisionTable.page.len(length).draw();
                }
            });

            // Status Table Search
            $('#statusSearch').on('keyup', function() {
                statusTable.search(this.value).draw();
            });

            $('#statusSearch').on('search', function() {
                if (this.value === '') {
                    statusTable.search('').draw();
                }
            });

            // Status Page length functionality
            $('#statusPageLength').on('keyup change', function() {
                var length = parseInt(this.value);
                if (!isNaN(length) && length > 0) {
                    statusTable.page.len(length).draw();
                }
            });

            // Installation Table Search
            $('#installationSearch').on('keyup', function() {
                installationTable.search(this.value).draw();
            });

            $('#installationSearch').on('search', function() {
                if (this.value === '') {
                    installationTable.search('').draw();
                }
            });

            // Installation Page length functionality
            $('#installationPageLength').on('keyup change', function() {
                var length = parseInt(this.value);
                if (!isNaN(length) && length > 0) {
                    installationTable.page.len(length).draw();
                }
            });

            // Accommodation Table Search
            $('#accommodationSearch').on('keyup', function() {
                accommodationTable.search(this.value).draw();
            });

            $('#accommodationSearch').on('search', function() {
                if (this.value === '') {
                    accommodationTable.search('').draw();
                }
            });

            // Accommodation Page length functionality
            $('#accommodationPageLength').on('keyup change', function() {
                var length = parseInt(this.value);
                if (!isNaN(length) && length > 0) {
                    accommodationTable.page.len(length).draw();
                }
            });

            // Division Table Info and Pagination Functions
            function updateDivisionTableInfo() {
                var info = divisionTable.page.info();
                var start = info.start + 1;
                var end = info.end;
                var total = info.recordsTotal;
                var text = `Showing <strong>${start} to ${end}</strong> of <strong>${total} entries</strong>`;
                $('#divisionTableInfo').html(text);
            }

            function updateDivisionPagination() {
                var info = divisionTable.page.info();
                var currentPage = info.page + 1;
                var totalPages = info.pages;
                var pagination = '';

                // Previous button
                if (currentPage === 1) {
                    pagination +=
                        `<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true"><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-1'><path d='M15 6l-6 6l6 6'></path></svg></a></li>`;
                } else {
                    pagination +=
                        `<li class="page-item"><a class="page-link division-page-link" href="#" data-page="${currentPage - 2}"><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-1'><path d='M15 6l-6 6l6 6'></path></svg></a></li>`;
                }

                // Page numbers
                for (var i = 1; i <= totalPages; i++) {
                    if (i === currentPage) {
                        pagination += `<li class="page-item active"><a class="page-link" href="#">${i}</a></li>`;
                    } else {
                        pagination +=
                            `<li class="page-item"><a class="page-link division-page-link" href="#" data-page="${i - 1}">${i}</a></li>`;
                    }
                }

                // Next button
                if (currentPage === totalPages || totalPages === 0) {
                    pagination +=
                        `<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true"><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-1'><path d='M9 6l6 6l-6 6'></path></svg></a></li>`;
                } else {
                    pagination +=
                        `<li class="page-item"><a class="page-link division-page-link" href="#" data-page="${currentPage}"><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-1'><path d='M9 6l6 6l-6 6'></path></svg></a></li>`;
                }

                $('#divisionTablePagination').html(pagination);

                // Handle page click for division table
                $('.division-page-link').off('click').on('click', function(e) {
                    e.preventDefault();
                    var page = parseInt($(this).data('page'));
                    if (!isNaN(page)) {
                        divisionTable.page(page).draw('page');
                    }
                });
            }

            // Status Table Info and Pagination Functions
            function updateStatusTableInfo() {
                var info = statusTable.page.info();
                var start = info.start + 1;
                var end = info.end;
                var total = info.recordsTotal;
                var text = `Showing <strong>${start} to ${end}</strong> of <strong>${total} entries</strong>`;
                $('#statusTableInfo').html(text);
            }

            function updateStatusPagination() {
                var info = statusTable.page.info();
                var currentPage = info.page + 1;
                var totalPages = info.pages;
                var pagination = '';

                // Previous button
                if (currentPage === 1) {
                    pagination +=
                        `<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true"><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-1'><path d='M15 6l-6 6l6 6'></path></svg></a></li>`;
                } else {
                    pagination +=
                        `<li class="page-item"><a class="page-link status-page-link" href="#" data-page="${currentPage - 2}"><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-1'><path d='M15 6l-6 6l6 6'></path></svg></a></li>`;
                }

                // Page numbers
                for (var i = 1; i <= totalPages; i++) {
                    if (i === currentPage) {
                        pagination += `<li class="page-item active"><a class="page-link" href="#">${i}</a></li>`;
                    } else {
                        pagination +=
                            `<li class="page-item"><a class="page-link status-page-link" href="#" data-page="${i - 1}">${i}</a></li>`;
                    }
                }

                // Next button
                if (currentPage === totalPages || totalPages === 0) {
                    pagination +=
                        `<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true"><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-1'><path d='M9 6l6 6l-6 6'></path></svg></a></li>`;
                } else {
                    pagination +=
                        `<li class="page-item"><a class="page-link status-page-link" href="#" data-page="${currentPage}"><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-1'><path d='M9 6l6 6l-6 6'></path></svg></a></li>`;
                }

                $('#statusTablePagination').html(pagination);

                // Handle page click for status table
                $('.status-page-link').off('click').on('click', function(e) {
                    e.preventDefault();
                    var page = parseInt($(this).data('page'));
                    if (!isNaN(page)) {
                        statusTable.page(page).draw('page');
                    }
                });
            }

            // Installation Table Info and Pagination Functions
            function updateInstallationTableInfo() {
                var info = installationTable.page.info();
                var start = info.start + 1;
                var end = info.end;
                var total = info.recordsTotal;
                var text = `Showing <strong>${start} to ${end}</strong> of <strong>${total} entries</strong>`;
                $('#installationTableInfo').html(text);
            }

            function updateInstallationPagination() {
                var info = installationTable.page.info();
                var currentPage = info.page + 1;
                var totalPages = info.pages;
                var pagination = '';

                // Previous button
                if (currentPage === 1) {
                    pagination +=
                        `<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true"><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-1'><path d='M15 6l-6 6l6 6'></path></svg></a></li>`;
                } else {
                    pagination +=
                        `<li class="page-item"><a class="page-link installation-page-link" href="#" data-page="${currentPage - 2}"><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-1'><path d='M15 6l-6 6l6 6'></path></svg></a></li>`;
                }

                // Page numbers
                for (var i = 1; i <= totalPages; i++) {
                    if (i === currentPage) {
                        pagination += `<li class="page-item active"><a class="page-link" href="#">${i}</a></li>`;
                    } else {
                        pagination +=
                            `<li class="page-item"><a class="page-link installation-page-link" href="#" data-page="${i - 1}">${i}</a></li>`;
                    }
                }

                // Next button
                if (currentPage === totalPages || totalPages === 0) {
                    pagination +=
                        `<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true"><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-1'><path d='M9 6l6 6l-6 6'></path></svg></a></li>`;
                } else {
                    pagination +=
                        `<li class="page-item"><a class="page-link installation-page-link" href="#" data-page="${currentPage}"><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-1'><path d='M9 6l6 6l-6 6'></path></svg></a></li>`;
                }

                $('#installationTablePagination').html(pagination);

                // Handle page click for installation table
                $('.installation-page-link').off('click').on('click', function(e) {
                    e.preventDefault();
                    var page = parseInt($(this).data('page'));
                    if (!isNaN(page)) {
                        installationTable.page(page).draw('page');
                    }
                });
            }

            // Accommodation Table Info and Pagination Functions
            function updateAccommodationTableInfo() {
                var info = accommodationTable.page.info();
                var start = info.start + 1;
                var end = info.end;
                var total = info.recordsTotal;
                var text = `Showing <strong>${start} to ${end}</strong> of <strong>${total} entries</strong>`;
                $('#accommodationTableInfo').html(text);
            }

            function updateAccommodationPagination() {
                var info = accommodationTable.page.info();
                var currentPage = info.page + 1;
                var totalPages = info.pages;
                var pagination = '';

                // Previous button
                if (currentPage === 1) {
                    pagination +=
                        `<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true"><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-1'><path d='M15 6l-6 6l6 6'></path></svg></a></li>`;
                } else {
                    pagination +=
                        `<li class="page-item"><a class="page-link accommodation-page-link" href="#" data-page="${currentPage - 2}"><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-1'><path d='M15 6l-6 6l6 6'></path></svg></a></li>`;
                }

                // Page numbers
                for (var i = 1; i <= totalPages; i++) {
                    if (i === currentPage) {
                        pagination += `<li class="page-item active"><a class="page-link" href="#">${i}</a></li>`;
                    } else {
                        pagination +=
                            `<li class="page-item"><a class="page-link accommodation-page-link" href="#" data-page="${i - 1}">${i}</a></li>`;
                    }
                }

                // Next button
                if (currentPage === totalPages || totalPages === 0) {
                    pagination +=
                        `<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true"><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-1'><path d='M9 6l6 6l-6 6'></path></svg></a></li>`;
                } else {
                    pagination +=
                        `<li class="page-item"><a class="page-link accommodation-page-link" href="#" data-page="${currentPage}"><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-1'><path d='M9 6l6 6l-6 6'></path></svg></a></li>`;
                }

                $('#accommodationTablePagination').html(pagination);

                // Handle page click for accommodation table
                $('.accommodation-page-link').off('click').on('click', function(e) {
                    e.preventDefault();
                    var page = parseInt($(this).data('page'));
                    if (!isNaN(page)) {
                        accommodationTable.page(page).draw('page');
                    }
                });
            }

            // Division Modal Handlers
            $(document).on('click', '.edit-division-btn', function() {
                var divisionId = $(this).data('division-id');
                var divisionCode = $(this).data('division-code');
                var divisionName = $(this).data('division-name');

                $('#editDivisionCode').val(divisionCode);
                $('#editDivisionName').val(divisionName);

                var baseUrl = '{{ url('/division') }}';
                $('#editDivisionForm').attr('action', baseUrl + '/' + divisionId);
            });

            $(document).on('click', '.delete-division-btn', function() {
                var divisionId = $(this).data('division-id');
                var divisionName = $(this).data('division-name');

                $('#deleteDivisionName').text(divisionName);

                var baseUrl = '{{ url('/division') }}';
                $('#deleteDivisionForm').attr('action', baseUrl + '/' + divisionId);
            });

            // Prospect Status Modal Handlers
            $(document).on('click', '.edit-prospect-status-btn', function() {
                var statusId = $(this).data('status-id');
                var statusName = $(this).data('status-name');
                var statusPercentage = $(this).data('status-percentage');
                var statusColor = $(this).data('status-color');

                $('#editStatusName').val(statusName);
                $('#editStatusPercentage').val(statusPercentage);
                $('#editStatusColor').val(statusColor);

                var baseUrl = '{{ url('/prospect-status') }}';
                $('#editProspectStatusForm').attr('action', baseUrl + '/' + statusId);
            });

            $(document).on('click', '.delete-prospect-status-btn', function() {
                var statusId = $(this).data('status-id');
                var statusName = $(this).data('status-name');

                $('#deleteProspectStatusName').text(statusName);

                var baseUrl = '{{ url('/prospect-status') }}';
                $('#deleteProspectStatusForm').attr('action', baseUrl + '/' + statusId);
            });

            // Installation Modal Handlers
            $(document).on('click', '.edit-installation-btn', function() {
                var installationId = $(this).data('installation-id');
                var installationName = $(this).data('installation-name');
                var installationDescription = $(this).data('installation-description');
                var installationProportional = $(this).data('installation-proportional');

                $('#editInstallationName').val(installationName);
                $('#editInstallationDescription').val(installationDescription);
                $('#editInstallationProportional').val(installationProportional);

                var baseUrl = '{{ url('/installation') }}';
                $('#editInstallationForm').attr('action', baseUrl + '/' + installationId);
            });

            $(document).on('click', '.delete-installation-btn', function() {
                var installationId = $(this).data('installation-id');
                var installationName = $(this).data('installation-name');

                $('#deleteInstallationName').text(installationName);

                var baseUrl = '{{ url('/installation') }}';
                $('#deleteInstallationForm').attr('action', baseUrl + '/' + installationId);
            });

            // Accommodation Modal Handlers
            $(document).on('click', '.edit-accommodation-btn', function() {
                var accommodationId = $(this).data('accommodation-id');
                var accommodationPrice = $(this).data('accommodation-price');

                $('#editAccommodationPrice').val(accommodationPrice);

                var baseUrl = '{{ url('/accommodation') }}';
                $('#editAccommodationForm').attr('action', baseUrl + '/' + accommodationId);
            });
        });
    </script>
@endpush
