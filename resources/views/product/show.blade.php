@extends('layouts.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <!-- Page pre-title -->
            <div class="page-pretitle">Overview</div>
            <h2 class="page-title">{{ $user->name }}</h2>
        </div>
        <!-- Page title actions -->
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <a  class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-2">
                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                        <path d="M16 5l3 3" />
                    </svg>
                    Edit User
                </a>
                <a href="{{ route('user.index') }}" class="btn btn-outline-light">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-2">
                        <path d="M9 14l-4 -4l4 -4" />
                        <path d="M5 10h11a4 4 0 1 1 0 8h-1" />
                    </svg>
                    Back to Users
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">User Information</h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- User Name -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Full Name</label>
                                <div class="fw-bold">{{ $user->name }}</div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Email Address</label>
                                <div class="fw-bold">{{ $user->email }}</div>
                            </div>
                        </div>

                        <!-- Unique ID -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Unique ID</label>
                                <div class="fw-bold">{{ $user->unique_id }}</div>
                            </div>
                        </div>

                        <!-- Employee Number -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Employee Number</label>
                                <div class="fw-bold">{{ $user->no_karyawan }}</div>
                            </div>
                        </div>

                        <!-- Division -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Division</label>
                                <div>
                                    <span class="badge bg-primary text-white">{{ $user->division->name }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Division Code -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Division Code</label>
                                <div class="fw-bold">{{ $user->division->kode }}</div>
                            </div>
                        </div>

                        <!-- Join Month -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Join Month</label>
                                <div class="fw-bold">{{ $user->join_month }}</div>
                            </div>
                        </div>

                        <!-- Join Year -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Join Year</label>
                                <div class="fw-bold">{{ $user->join_year }}</div>
                            </div>
                        </div>

                     
                       
                        <!-- Email Verification -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Email Verification</label>
                                <div>
                                    @if($user->email_verified_at)
                                        <span class="badge bg-success text-white">Verified</span>
                                    @else
                                        <span class="badge bg-warning text-white">Not Verified</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- User Roles -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Roles</label>
                                <div>
                                    @forelse($user->roles as $role)
                                        <span class="badge bg-info text-white me-1">{{ $role->name }}</span>
                                    @empty
                                        <span class="text-muted">No roles assigned</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                     
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
