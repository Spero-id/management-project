{{-- resources/views/kpi/index.blade.php --}}
@extends('layouts.app')

@section('title', 'KPI Management')

@section('content')
<div class="container-xl">
    <ul class="nav nav-tabs" data-bs-toggle="tabs">
        <li class="nav-item">
            <a href="#tab-project-list" class="nav-link active" data-bs-toggle="tab">Project Goal List</a>
        </li>
        <li class="nav-item">
            <a href="#tab-detail-project" class="nav-link" data-bs-toggle="tab">Project Goal Detail</a>
        </li>
        <li class="nav-item">
            <a href="#tab-kpi" class="nav-link" data-bs-toggle="tab">KPI Achievement</a>
        </li>
        <li class="nav-item">
            <a href="#tab-log" class="nav-link" data-bs-toggle="tab">Log KPI Achievement</a>
        </li>
    </ul>

    <div class="tab-content mt-3">
        {{-- 1. Project Goal List --}}
        <div class="tab-pane active show" id="tab-project-list">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Project Goal List</h3>
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProjectModal">Add Project Goal</a>
                </div>
                <!-- Search Bar for Project Goal List -->
                <div class="card-body border-bottom py-3">
                    <div class="d-flex">
                        <div class="text-muted">
                            Show
                            <div class="mx-2 d-inline-block">
                                <input type="text" class="form-control form-control-sm" value="8" size="3" aria-label="Invoices count">
                            </div>
                            entries
                        </div>
                        <div class="ms-auto text-muted">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search projects..." aria-label="Search projects" id="searchProject">
                                <button class="btn btn-outline-secondary" type="button" id="clearProjectSearch">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M18 6l-12 12" />
                                        <path d="M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Project Name</th>
                                <th>Description</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="projectTableBody">
                            <tr>
                                <td>1</td>
                                <td>Sistem Akademik</td>
                                <td>Pengembangan portal siswa</td>
                                <td>31-12-2025</td>
                                <td>
                                    <span class="status status-green">
                                        <span class="status-dot"></span>
                                        Active
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#tab-detail-project" data-bs-toggle="tab">Detail</a></li>
                                            <li><a class="dropdown-item" href="#">Edit</a></li>
                                            <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>E-Learning</td>
                                <td>Integrasi modul guru</td>
                                <td>15-11-2025</td>
                                <td>
                                    <span class="status status-orange">
                                        <span class="status-dot"></span>
                                        In Progress
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#tab-detail-project" data-bs-toggle="tab">Detail</a></li>
                                            <li><a class="dropdown-item" href="#">Edit</a></li>
                                            <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Mobile App</td>
                                <td>Pengembangan aplikasi mobile untuk orang tua</td>
                                <td>30-06-2025</td>
                                <td>
                                    <span class="status status-red">
                                        <span class="status-dot"></span>
                                        Pending
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#tab-detail-project" data-bs-toggle="tab">Detail</a></li>
                                            <li><a class="dropdown-item" href="#">Edit</a></li>
                                            <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Database Server</td>
                                <td>Migrasi database ke cloud server</td>
                                <td>20-03-2025</td>
                                <td>
                                    <span class="status status-green">
                                        <span class="status-dot"></span>
                                        Active
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#tab-detail-project" data-bs-toggle="tab">Detail</a></li>
                                            <li><a class="dropdown-item" href="#">Edit</a></li>
                                            <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Payment Gateway</td>
                                <td>Integrasi sistem pembayaran online</td>
                                <td>10-09-2025</td>
                                <td>
                                    <span class="status status-orange">
                                        <span class="status-dot"></span>
                                        In Progress
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#tab-detail-project" data-bs-toggle="tab">Detail</a></li>
                                            <li><a class="dropdown-item" href="#">Edit</a></li>
                                            <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-flex align-items-center">
                    <p class="m-0 text-muted">Showing <span>1</span> to <span>5</span> of <span>5</span> entries</p>
                    <ul class="pagination m-0 ms-auto">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>
                                prev
                            </a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">
                                next
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- 2. Project Goal Detail --}}
        <div class="tab-pane" id="tab-detail-project">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Project Goal Detail</h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-3">Project Name</dt>
                        <dd class="col-9">Sistem Akademik</dd>

                        <dt class="col-3">Description</dt>
                        <dd class="col-9">Pengembangan portal siswa dan integrasi data akademik.</dd>

                        <dt class="col-3">Deadline</dt>
                        <dd class="col-9">31-12-2025</dd>

                        <dt class="col-3">Status</dt>
                        <dd class="col-9">
                            <span class="status status-green">
                                <span class="status-dot"></span>
                                Active
                            </span>
                        </dd>
                    </dl>
                    <a href="#tab-project-list" data-bs-toggle="tab" class="btn btn-secondary mt-3">Back</a>
                </div>
            </div>
        </div>

        {{-- 3. KPI Achievement --}}
        <div class="tab-pane" id="tab-kpi">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">KPI Achievement</h3>
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAchievementModal">Add Achievement</a>
                </div>
                <!-- Search Bar for KPI Achievement -->
                <div class="card-body border-bottom py-3">
                    <div class="d-flex">
                        <div class="text-muted">
                            Show
                            <div class="mx-2 d-inline-block">
                                <input type="text" class="form-control form-control-sm" value="8" size="3" aria-label="Invoices count">
                            </div>
                            entries
                        </div>
                        <div class="ms-auto text-muted">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search KPI achievements..." aria-label="Search KPI" id="searchKPI">
                                <button class="btn btn-outline-secondary" type="button" id="clearKPISearch">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M18 6l-12 12" />
                                        <path d="M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Project</th>
                                <th>KPI Target</th>
                                <th>Progress</th>
                                <th>Update Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="kpiTableBody">
                            <tr>
                                <td>1</td>
                                <td>Sistem Akademik</td>
                                <td>100%</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 45%;">
                                            45%
                                        </div>
                                    </div>
                                </td>
                                <td>02-10-2025</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#tab-log" data-bs-toggle="tab">Log</a></li>
                                            <li><a class="dropdown-item" href="#">Edit</a></li>
                                            <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>E-Learning</td>
                                <td>100%</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 20%;">
                                            20%
                                        </div>
                                    </div>
                                </td>
                                <td>28-09-2025</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#tab-log" data-bs-toggle="tab">Log</a></li>
                                            <li><a class="dropdown-item" href="#">Edit</a></li>
                                            <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Mobile App</td>
                                <td>100%</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 10%;">
                                            10%
                                        </div>
                                    </div>
                                </td>
                                <td>15-09-2025</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#tab-log" data-bs-toggle="tab">Log</a></li>
                                            <li><a class="dropdown-item" href="#">Edit</a></li>
                                            <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Database Server</td>
                                <td>100%</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 85%;">
                                            85%
                                        </div>
                                    </div>
                                </td>
                                <td>05-10-2025</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#tab-log" data-bs-toggle="tab">Log</a></li>
                                            <li><a class="dropdown-item" href="#">Edit</a></li>
                                            <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Payment Gateway</td>
                                <td>100%</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 30%;">
                                            30%
                                        </div>
                                    </div>
                                </td>
                                <td>01-10-2025</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#tab-log" data-bs-toggle="tab">Log</a></li>
                                            <li><a class="dropdown-item" href="#">Edit</a></li>
                                            <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-flex align-items-center">
                    <p class="m-0 text-muted">Showing <span>1</span> to <span>5</span> of <span>5</span> entries</p>
                    <ul class="pagination m-0 ms-auto">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>
                                prev
                            </a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">
                                next
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- 4. Log KPI Achievement --}}
        <div class="tab-pane" id="tab-log">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">KPI Achievement Log</h3>
                </div>
                <!-- Search Bar for Log KPI Achievement -->
                <div class="card-body border-bottom py-3">
                    <div class="d-flex">
                        <div class="text-muted">
                            Show
                            <div class="mx-2 d-inline-block">
                                <input type="text" class="form-control form-control-sm" value="8" size="3" aria-label="Invoices count">
                            </div>
                            entries
                        </div>
                        <div class="ms-auto text-muted">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search logs..." aria-label="Search logs" id="searchLog">
                                <button class="btn btn-outline-secondary" type="button" id="clearLogSearch">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M18 6l-12 12" />
                                        <path d="M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Progress</th>
                                <th>Description</th>
                                <th>Updated By</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id="logTableBody">
                            <tr>
                                <td>1</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 45%;">
                                            45%
                                        </div>
                                    </div>
                                </td>
                                <td>Penambahan fitur login siswa</td>
                                <td>Andryan</td>
                                <td>02-10-2025</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 35%;">
                                            35%
                                        </div>
                                    </div>
                                </td>
                                <td>Pembuatan database awal</td>
                                <td>Andryan</td>
                                <td>25-09-2025</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 85%;">
                                            85%
                                        </div>
                                    </div>
                                </td>
                                <td>Migrasi data berhasil</td>
                                <td>Budi</td>
                                <td>05-10-2025</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 30%;">
                                            30%
                                        </div>
                                    </div>
                                </td>
                                <td>Integrasi API payment</td>
                                <td>Citra</td>
                                <td>01-10-2025</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 10%;">
                                            10%
                                        </div>
                                    </div>
                                </td>
                                <td>Setup environment development</td>
                                <td>Dewi</td>
                                <td>15-09-2025</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-flex align-items-center">
                    <p class="m-0 text-muted">Showing <span>1</span> to <span>5</span> of <span>5</span> entries</p>
                    <ul class="pagination m-0 ms-auto">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>
                                prev
                            </a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">
                                next
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-footer">
                    <a href="#tab-kpi" data-bs-toggle="tab" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL: Add Project Goal --}}
<div class="modal fade" id="addProjectModal" tabindex="-1" aria-labelledby="addProjectModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addProjectModalLabel">Add Project Goal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="projectForm">
          <div class="mb-3">
            <label for="projectName" class="form-label">Project Name</label>
            <input type="text" class="form-control" id="projectName" placeholder="Enter project name" required>
          </div>
          <div class="mb-3">
            <label for="projectDescription" class="form-label">Description</label>
            <textarea class="form-control" id="projectDescription" rows="3" placeholder="Enter description" required></textarea>
          </div>
          <div class="mb-3">
            <label for="projectDeadline" class="form-label">Deadline</label>
            <input type="date" class="form-control" id="projectDeadline" required>
          </div>
          <div class="mb-3">
            <label for="projectStatus" class="form-label">Status</label>
            <select class="form-select" id="projectStatus" required>
              <option selected disabled value="">-- Select Status --</option>
              <option value="active">Active</option>
              <option value="in-progress">In Progress</option>
              <option value="completed">Completed</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="saveProjectBtn">Save</button>
      </div>
    </div>
  </div>
</div>

{{-- MODAL: Add Achievement --}}
<div class="modal fade" id="addAchievementModal" tabindex="-1" aria-labelledby="addAchievementModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addAchievementModalLabel">Add KPI Achievement</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="achievementForm">
          <div class="mb-3">
            <label for="achievementProject" class="form-label">Project</label>
            <select class="form-select" id="achievementProject" required>
              <option selected disabled value="">-- Select Project --</option>
              <option value="sistem-akademik">Sistem Akademik</option>
              <option value="e-learning">E-Learning</option>
              <option value="mobile-app">Mobile App</option>
              <option value="database-server">Database Server</option>
              <option value="payment-gateway">Payment Gateway</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="kpiTarget" class="form-label">KPI Target (%)</label>
            <input type="number" class="form-control" id="kpiTarget" placeholder="Enter target (e.g. 100)" min="0" max="100" required>
          </div>
          <div class="mb-3">
            <label for="achievementProgress" class="form-label">Progress (%)</label>
            <input type="number" class="form-control" id="achievementProgress" placeholder="Enter current progress" min="0" max="100" required>
          </div>
          <div class="mb-3">
            <label for="updateDate" class="form-label">Update Date</label>
            <input type="date" class="form-control" id="updateDate" required>
          </div>
          <div class="mb-3">
            <label for="achievementDescription" class="form-label">Description</label>
            <textarea class="form-control" id="achievementDescription" rows="3" placeholder="Enter description" required></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="saveAchievementBtn">Save</button>
      </div>
    </div>
  </div>
</div>

<!-- JavaScript untuk fungsi pencarian dan modal -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fungsi pencarian untuk Project Goal List
    const searchProject = document.getElementById('searchProject');
    const clearProjectSearch = document.getElementById('clearProjectSearch');
    const projectTableBody = document.getElementById('projectTableBody');
    
    if (searchProject && projectTableBody) {
        searchProject.addEventListener('input', function() {
            filterTable(this.value, projectTableBody);
        });
        
        clearProjectSearch.addEventListener('click', function() {
            searchProject.value = '';
            filterTable('', projectTableBody);
        });
    }
    
    // Fungsi pencarian untuk KPI Achievement
    const searchKPI = document.getElementById('searchKPI');
    const clearKPISearch = document.getElementById('clearKPISearch');
    const kpiTableBody = document.getElementById('kpiTableBody');
    
    if (searchKPI && kpiTableBody) {
        searchKPI.addEventListener('input', function() {
            filterTable(this.value, kpiTableBody);
        });
        
        clearKPISearch.addEventListener('click', function() {
            searchKPI.value = '';
            filterTable('', kpiTableBody);
        });
    }
    
    // Fungsi pencarian untuk Log KPI Achievement
    const searchLog = document.getElementById('searchLog');
    const clearLogSearch = document.getElementById('clearLogSearch');
    const logTableBody = document.getElementById('logTableBody');
    
    if (searchLog && logTableBody) {
        searchLog.addEventListener('input', function() {
            filterTable(this.value, logTableBody);
        });
        
        clearLogSearch.addEventListener('click', function() {
            searchLog.value = '';
            filterTable('', logTableBody);
        });
    }
    
    // Fungsi untuk memfilter tabel berdasarkan input pencarian
    function filterTable(searchTerm, tableBody) {
        const rows = tableBody.getElementsByTagName('tr');
        let visibleCount = 0;
        
        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const cells = row.getElementsByTagName('td');
            let found = false;
            
            for (let j = 0; j < cells.length; j++) {
                const cell = cells[j];
                if (cell.textContent.toLowerCase().includes(searchTerm.toLowerCase())) {
                    found = true;
                    break;
                }
            }
            
            if (found) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        }
        
        // Update showing entries text
        updateShowingEntries(visibleCount, tableBody);
    }
    
    // Fungsi untuk update teks "Showing X to Y of Z entries"
    function updateShowingEntries(visibleCount, tableBody) {
        const cardFooter = tableBody.closest('.card').querySelector('.card-footer');
        if (cardFooter) {
            const showingText = cardFooter.querySelector('p');
            if (showingText) {
                const totalRows = tableBody.getElementsByTagName('tr').length;
                showingText.innerHTML = `Showing <span>1</span> to <span>${visibleCount}</span> of <span>${totalRows}</span> entries`;
            }
        }
    }

    // ===== MODAL FUNCTIONALITY =====

    // Add Project Goal Modal
    const saveProjectBtn = document.getElementById('saveProjectBtn');
    const projectForm = document.getElementById('projectForm');
    const addProjectModal = document.getElementById('addProjectModal');

    if (saveProjectBtn && projectForm) {
        saveProjectBtn.addEventListener('click', function() {
            // Validasi form
            if (!projectForm.checkValidity()) {
                projectForm.reportValidity();
                return;
            }

            // Ambil data dari form
            const projectName = document.getElementById('projectName').value;
            const projectDescription = document.getElementById('projectDescription').value;
            const projectDeadline = document.getElementById('projectDeadline').value;
            const projectStatus = document.getElementById('projectStatus').value;

            // Tampilkan alert sukses
            showAlert('success', `Project "${projectName}" berhasil ditambahkan!`);

            // Reset form
            projectForm.reset();

            // Tutup modal
            const modal = bootstrap.Modal.getInstance(addProjectModal);
            modal.hide();
        });
    }

    // Add Achievement Modal
    const saveAchievementBtn = document.getElementById('saveAchievementBtn');
    const achievementForm = document.getElementById('achievementForm');
    const addAchievementModal = document.getElementById('addAchievementModal');

    if (saveAchievementBtn && achievementForm) {
        saveAchievementBtn.addEventListener('click', function() {
            // Validasi form
            if (!achievementForm.checkValidity()) {
                achievementForm.reportValidity();
                return;
            }

            // Ambil data dari form
            const achievementProject = document.getElementById('achievementProject').value;
            const kpiTarget = document.getElementById('kpiTarget').value;
            const achievementProgress = document.getElementById('achievementProgress').value;
            const updateDate = document.getElementById('updateDate').value;
            const achievementDescription = document.getElementById('achievementDescription').value;

            // Tampilkan alert sukses
            showAlert('success', `KPI Achievement untuk project "${achievementProject}" berhasil ditambahkan!`);

            // Reset form
            achievementForm.reset();

            // Tutup modal
            const modal = bootstrap.Modal.getInstance(addAchievementModal);
            modal.hide();
        });
    }

    // Reset form ketika modal ditutup
    if (addProjectModal) {
        addProjectModal.addEventListener('hidden.bs.modal', function () {
            projectForm.reset();
        });
    }

    if (addAchievementModal) {
        addAchievementModal.addEventListener('hidden.bs.modal', function () {
            achievementForm.reset();
        });
    }

    // Fungsi untuk menampilkan alert
    function showAlert(type, message) {
        // Buat element alert
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        `;
        
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        // Tambahkan alert ke body
        document.body.appendChild(alertDiv);

        // Auto remove alert setelah 5 detik
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.parentNode.removeChild(alertDiv);
            }
        }, 5000);
    }
});
</script>

<style>
/* Custom styling untuk status Tabler */
.status {
    display: inline-flex;
    align-items: center;
    font-size: 0.875rem;
    font-weight: 500;
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
}

.status-dot {
    width: 0.5rem;
    height: 0.5rem;
    border-radius: 50%;
    margin-right: 0.375rem;
}

.status-green {
    background-color: rgba(75, 192, 192, 0.1);
    color: #2fb344;
}

.status-green .status-dot {
    background-color: #2fb344;
}

.status-orange {
    background-color: rgba(255, 159, 64, 0.1);
    color: #f76707;
}

.status-orange .status-dot {
    background-color: #f76707;
}

.status-red {
    background-color: rgba(255, 99, 132, 0.1);
    color: #e03131;
}

.status-red .status-dot {
    background-color: #e03131;
}

.status-blue {
    background-color: rgba(54, 162, 235, 0.1);
    color: #1971c2;
}

.status-blue .status-dot {
    background-color: #1971c2;
}

.status-purple {
    background-color: rgba(153, 102, 255, 0.1);
    color: #9c36b5;
}

.status-purple .status-dot {
    background-color: #9c36b5;
}
</style>
@endsection