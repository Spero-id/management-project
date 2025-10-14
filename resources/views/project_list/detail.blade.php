@extends('layouts.app')

@section('header')
<div class="row g-2 align-items-center">
    <div class="col">
        <h2 class="page-title fw-bold fs-3">Project Detail</h2>
        <p class="text-muted fs-5">Detailed overview of the project information</p>
    </div>
    <div class="col-auto ms-auto d-print-none">
        <a href="{{ route('project.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 fs-5">
            Back
        </a>
    </div>
</div>
@endsection

@section('content')

@php
    $projects = [
        1 => [
            'project_name' => 'E-Commerce Platform',
            'type' => 'Web Development',
            'client' => 'TechCorp',
            'progress' => '85%',
            'budget' => 'Rp 1.875.000.000',
            'status' => 'Goal',
        ],
        2 => [
            'project_name' => 'Inventory System',
            'type' => 'ERP',
            'client' => 'LogisticPro',
            'progress' => '15%',
            'budget' => 'Rp 1.100.000.000',
            'status' => 'Pending',
        ],
        3 => [
            'project_name' => 'Cloud Migration',
            'type' => 'Infrastructure',
            'client' => 'CloudTech',
            'progress' => '5%',
            'budget' => 'Rp 2.970.000.000',
            'status' => 'Cancel',
        ],
    ];

    $project = $projects[$id] ?? null;
@endphp

@if(!$project)
    <div class="alert alert-danger text-center mt-4 fs-5">
        Project tidak ditemukan.
    </div>
@else
<div class="row justify-content-center">
    <div class="col-md-11 col-lg-11">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-5">
                <div class="row g-4">
                    <!-- Kolom kiri -->
                    <div class="col-md-6">
                        <div class="bg-light p-4 rounded-3 mb-3">
                            <p class="text-muted mb-1 fs-5">Project Name</p>
                            <h5 class="fw-semibold text-dark fs-5">{{ $project['project_name'] }}</h5>
                        </div>
                        <div class="bg-light p-4 rounded-3 mb-3">
                            <p class="text-muted mb-1 fs-5">Type</p>
                            <h5 class="fw-semibold text-dark fs-5">{{ $project['type'] }}</h5>
                        </div>
                        <div class="bg-light p-4 rounded-3">
                            <p class="text-muted mb-1 fs-5">Client</p>
                            <h5 class="fw-semibold text-dark fs-5">{{ $project['client'] }}</h5>
                        </div>
                    </div>

                    <!-- Kolom kanan -->
                    <div class="col-md-6">
                        <div class="p-4 rounded-3 mb-3" style="background-color: #e8f4ff;">
                            <p class="text-muted mb-1 fs-5">Progress</p>
                            <h5 class="fw-semibold text-dark fs-5">{{ $project['progress'] }}</h5>
                        </div>
                        <div class="p-4 rounded-3 mb-3" style="background-color: #e9fbe8;">
                            <p class="text-muted mb-1 fs-5">Budget</p>
                            <h5 class="fw-semibold text-dark fs-5">{{ $project['budget'] }}</h5>
                        </div>
                        <div class="p-4 rounded-3 d-flex justify-content-between align-items-center" style="background-color: #fff7e5;">
                            <div>
                                <p class="text-muted mb-1 fs-5">Status</p>
                                <h5 class="fw-semibold fs-5
                                    @if($project['status'] === 'Goal') text-success
                                    @elseif($project['status'] === 'Pending') text-warning
                                    @elseif($project['status'] === 'Cancel') text-danger
                                    @endif">
                                    {{ $project['status'] }}
                                </h5>
                    </div>
                </div>

                <hr class="mt-5">

                <!-- Tombol Log -->
                <div class="text-end">
    <a href="{{ route('project.log', ['id' => $id]) }}" 
       class="btn btn-outline-primary rounded-pill px-5 py-2 fs-5">
       View Log
    </a>
</div>
            </div>
        </div>
    </div>
</div>
@endif

@push('styles')
<style>
    body {
        background-color: #f8fafc;
    }
    .card {
        background-color: #fff;
        border-radius: 20px;
    }
    .card-body {
        font-size: 1.2rem; 
    }
    h5 {
        font-size: 1.3rem;
        margin-bottom: 0;
    }
    p {
        font-size: 1.1rem;
    }
    small {
        font-size: 1.1rem;
    }
    hr {
        border-top: 1px solid #eee;
    }
    .bg-light {
        background-color: #f8f9fa !important;
    }
    .btn {
        font-size: 1.1rem;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
    const btnLog = document.getElementById("btnLog");
    btnLog.addEventListener("click", () => {
        alert("📜 Project Log:\n- Created project record\n- Updated client info\n- Progress updated to {{ $project['progress'] }}\n- Status: {{ $project['status'] }}");
    });
});
</script>
@endpush

@endsection
