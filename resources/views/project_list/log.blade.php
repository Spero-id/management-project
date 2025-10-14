@extends('layouts.app')

@section('header')
<div class="row g-2 align-items-center">
    <div class="col">
        <h2 class="page-title fw-bold fs-3">Log View</h2>
    </div>
</div>
@endsection

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border border-primary-subtle rounded-4">
        <div class="card-body px-5 py-4">

            <!-- Informasi  -->
            <div class="mb-4">
                <h5 class="fw-semibold mb-1">
                    E-Commerce Platform
                
                </h5>
                <p class="mb-1 text-muted">
                    Client: <span class="text-dark fw-semibold">TechCorp</span>
                </p>
                <p class="mb-0 text-muted">
                    Type: <span class="text-dark fw-semibold">Web Development</span>
                </p>
            </div>

            <div class="timeline border-start border-2 ps-4">

                <div class="timeline-item mb-4">
                    <div class="timeline-marker bg-secondary"></div>
                    <div class="timeline-content">
                        <h6 class="fw-semibold text-secondary mb-1">Lorem ipsum is a dummy</h6>
                        <small class="text-muted d-block mb-2">
                            Lorem ipsum is a dummy<span class="fw-semibold">Lorem ipsum is a dummy</span>
                        </small>
                        <p class="mb-0">
                            Lorem ipsum is a dummy: <span class="fw-semibold text-dark">Lorem ipsum</span>.
                        </p>
                    </div>
                </div>

                <div class="timeline-item mb-4">
                    <div class="timeline-marker bg-primary"></div>
                    <div class="timeline-content">
                        <h6 class="fw-semibold text-primary mb-1">Lorem ipsum is a dummy</h6>
                        <small class="text-muted d-block mb-2">
                            Lorem ipsum is a dummy <span class="fw-semibold">Lorem ipsum</span>
                        </small>
                        <p class="mb-0">
                            Lorem ipsum is a dummy or placeholder text commonly used
                            <span class="fw-semibold text-dark">Lorem ipsum is a dummy</span>.
                        </p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-marker bg-success"></div>
                    <div class="timeline-content">
                        <h6 class="fw-semibold text-success mb-1">Lorem ipsum is a dummy</h6>
                        <small class="text-muted d-block mb-2">
                            Lorem ipsum is a dummy or<span class="fw-semibold">Lorem ipsum</span>
                        </small>
                        <p class="mb-0">
                            Lorem ipsum is a dummy or placeholder text commonly used
                            <span class="fw-semibold text-dark">Lorem ipsum is a dummy</span>.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tombol Kembali -->
            <div class="text-end mt-4">
                <a href="{{ route('project.detail', ['id' => $id ?? 1]) }}"
                   class="btn btn-outline-secondary rounded-pill px-4 py-2 fs-5">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
body {
    background-color: #f8fafc;
}
.card {
    background-color: #fff;
    border-radius: 20px;
}
.timeline {
    position: relative;
}
.timeline-item {
    position: relative;
    display: flex;
    align-items: flex-start;
    margin-left: 12px;
}
.timeline-marker {
    flex-shrink: 0;
    width: 6px;
    height: 45px;
    border-radius: 3px;
    margin-right: 12px;
}
.timeline-content {
    flex: 1;
}
h5 {
    font-size: 1.2rem;
}
h6 {
    font-size: 1.05rem;
}
.fs-5 {
    font-size: 1.1rem !important;
}
</style>
@endpush
