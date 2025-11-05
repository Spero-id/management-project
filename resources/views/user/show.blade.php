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
                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary">
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
        <!-- Basic Information Card -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">User Information</h3>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Full Name</label>
                            <div class="fw-bold mb-3">{{ $user->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Email Address</label>
                            <div class="fw-bold mb-3">{{ $user->email }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Unique ID</label>
                            <div class="fw-bold mb-3">{{ $user->unique_id }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Employee Number</label>
                            <div class="fw-bold mb-3">{{ $user->no_karyawan }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Division</label>
                            <div class="mb-3">{{ $user->division->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Division Code</label>
                            <div class="fw-bold mb-3">{{ $user->division->kode }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Join Date</label>
                            <div class="fw-bold mb-3">{{ $user->join_month }} {{ $user->join_year }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Type</label>
                            <div class="fw-bold mb-3">{{ $user->type }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Quotation Number</label>
                            <div class="fw-bold mb-3">
                                @if($user->no_quotation)
                                    {{ $user->no_quotation }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </div>
                        </div>
                      
                    </div>
                </div>
            </div>
        </div>

        <!-- Roles & Status Card -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Documents</h3>
                </div>
                <div class="card-body">
                 
                    <!-- Documents Section -->
                    @if($user->ktp || $user->ijazah || ($user->sertifikat && count($user->sertifikat) > 0))
                    <div class="mb-2">
                        <div class="d-grid gap-2">
                            @if($user->ktp)
                            <a href="{{ asset('storage/' . $user->ktp) }}" 
                               target="_blank" 
                               class="btn btn-outline-primary d-flex align-items-center py-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-3">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14,2 14,8 20,8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10,9 9,9 8,9"></polyline>
                                </svg>
                                KTP
                            </a>
                            @endif

                            @if($user->ijazah)
                            <a href="{{ asset('storage/' . $user->ijazah) }}" 
                               target="_blank" 
                               class="btn btn-outline-primary d-flex align-items-center py-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-3">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14,2 14,8 20,8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10,9 9,9 8,9"></polyline>
                                </svg>
                                Ijazah
                            </a>
                            @endif

                                                        @if($user->sertifikat && count($user->sertifikat) > 0)
                                @foreach($user->sertifikat as $index => $sertifikat)
                                <a href="{{ asset('storage/' . $sertifikat) }}" 
                                   target="_blank" 
                                   class="btn btn-outline-primary d-flex align-items-center py-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-3">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14,2 14,8 20,8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10,9 9,9 8,9"></polyline>
                                    </svg>
                                    {{ basename($sertifikat) }}
                                </a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
    </div>

    <!-- KTP Modal -->
    @if($user->ktp)
        <div class="modal fade" id="ktpModal" tabindex="-1" aria-labelledby="ktpModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ktpModalLabel">KTP - {{ $user->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="{{ asset('storage/' . $user->ktp) }}" alt="KTP" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Ijazah Modal -->
    @if($user->ijazah)
        <div class="modal fade" id="ijazahModal" tabindex="-1" aria-labelledby="ijazahModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ijazahModalLabel">Ijazah - {{ $user->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="{{ asset('storage/' . $user->ijazah) }}" alt="Ijazah" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Sertifikat Modals -->
    @if($user->sertifikat && count($user->sertifikat) > 0)
        @foreach($user->sertifikat as $index => $sertifikat)
            <div class="modal fade" id="sertifikatModal{{ $index }}" tabindex="-1" aria-labelledby="sertifikatModalLabel{{ $index }}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="sertifikatModalLabel{{ $index }}">{{ basename($sertifikat) }} - {{ $user->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="{{ asset('storage/' . $sertifikat) }}" alt="{{ basename($sertifikat) }}" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
@endsection
