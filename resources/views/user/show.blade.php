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
        <!-- User Information Card -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- Profile Photo Section -->
                        <div class="col-md-12 mb-4">
                            <div class="d-flex align-items-start gap-4">
                                <div class="flex-shrink-0">
                                    @if ($user->foto)
                                        <img src="{{ asset('storage/' . $user->foto) }}" alt="{{ $user->name }}"
                                            class="avatar avatar-2xl " style="width: 120px; height: 120px; object-fit: cover;">
                                    @else
                                        <div class="avatar avatar-2xl "
                                            style="width: 120px; height: 120px; font-size: 3rem; display: flex; align-items: center; justify-content: center;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <h3 class="mb-1">{{ $user->name }}</h3>
                                    <div class="text-muted mb-2">{{ $user->division->name }}</div>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <span class="badge bg-blue-lt">{{ $user->type }}</span>
                                        <span class="badge bg-purple-lt">{{ $user->division->kode }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Information Grid -->
                        <div class="col-md-12">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted">
                                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                            </svg>
                                            <span class="text-muted small">Full name</span>
                                        </div>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted">
                                                <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z"></path>
                                                <path d="M3 7l9 6l9 -6"></path>
                                            </svg>
                                            <span class="text-muted small">Email address</span>
                                        </div>
                                        <div class="fw-bold text-break">{{ $user->email }}</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted">
                                                <path d="M3 7v4a1 1 0 0 0 1 1h3"></path>
                                                <path d="M7 7v10"></path>
                                                <path d="M10 8v8a1 1 0 0 0 1 1h2a1 1 0 0 0 1 -1v-8a1 1 0 0 0 -1 -1h-2a1 1 0 0 0 -1 1z"></path>
                                                <path d="M17 7v10"></path>
                                                <path d="M21 7v4a1 1 0 0 1 -1 1h-3"></path>
                                            </svg>
                                            <span class="text-muted small">Unique ID</span>
                                        </div>
                                        <div class="fw-bold">{{ $user->unique_id }}</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted">
                                                <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"></path>
                                                <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z"></path>
                                            </svg>
                                            <span class="text-muted small">Employee number</span>
                                        </div>
                                        <div class="fw-bold">{{ $user->no_karyawan }}</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted">
                                                <path d="M19 5v14a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-14"></path>
                                                <path d="M9 3h6"></path>
                                            </svg>
                                            <span class="text-muted small">Division</span>
                                        </div>
                                        <div class="fw-bold">{{ $user->division->name }}</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted">
                                                <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z"></path>
                                                <path d="M16 3v4"></path>
                                                <path d="M8 3v4"></path>
                                                <path d="M4 11h16"></path>
                                            </svg>
                                            <span class="text-muted small">Join date</span>
                                        </div>
                                        <div class="fw-bold">{{ $user->join_month }} {{ $user->join_year }}</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted">
                                                <path d="M12 6m-8 0a8 3 0 1 0 16 0a8 3 0 1 0 -16 0"></path>
                                                <path d="M4 6v6a8 3 0 0 0 16 0v-6"></path>
                                                <path d="M4 12v6a8 3 0 0 0 16 0v-6"></path>
                                            </svg>
                                            <span class="text-muted small">Type</span>
                                        </div>
                                        <div class="fw-bold">{{ $user->type }}</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted">
                                                <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                                                <path d="M9 7h1"></path>
                                                <path d="M9 13h6"></path>
                                                <path d="M9 17h6"></path>
                                            </svg>
                                            <span class="text-muted small">Quotation number</span>
                                        </div>
                                        <div class="fw-bold">
                                            @if ($user->no_quotation)
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
                </div>
            </div>
        </div>

        <!-- Roles & Status Card -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Documents</h3>
                    <div class="card-actions">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#uploadDocumentModal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon">
                                <path d="M12 5v14"></path>
                                <path d="M5 12h14"></path>
                            </svg>
                            Add document
                        </button>
                    </div>
                </div>
                <div class="card-body">

                    <!-- Documents Section -->
                    @if ($user->ktp || $user->ijazah || ($user->sertifikat && count($user->sertifikat) > 0))
                        <div class="mb-2">
                            <div class="d-grid gap-2">
                                @if ($user->ktp)
                                    <a href="{{ asset('storage/' . $user->ktp) }}" target="_blank"
                                        class="btn btn-outline-primary d-flex align-items-center py-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon me-3">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14,2 14,8 20,8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10,9 9,9 8,9"></polyline>
                                        </svg>
                                        KTP
                                    </a>
                                @endif

                                @if ($user->ijazah)
                                    <a href="{{ asset('storage/' . $user->ijazah) }}" target="_blank"
                                        class="btn btn-outline-primary d-flex align-items-center py-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon me-3">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14,2 14,8 20,8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10,9 9,9 8,9"></polyline>
                                        </svg>
                                        Ijazah
                                    </a>
                                @endif

                                @if ($user->sertifikat && count($user->sertifikat) > 0)
                                    @foreach ($user->sertifikat as $index => $sertifikat)
                                        <a href="{{ asset('storage/' . $sertifikat) }}" target="_blank"
                                            class="btn btn-outline-primary d-flex align-items-center py-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon me-3">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                <polyline points="14,2 14,8 20,8"></polyline>
                                                <line x1="16" y1="13" x2="8" y2="13">
                                                </line>
                                                <line x1="16" y1="17" x2="8" y2="17">
                                                </line>
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
        @if ($user->ktp)
            <div class="modal fade" id="ktpModal" tabindex="-1" aria-labelledby="ktpModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ktpModalLabel">KTP - {{ $user->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="{{ asset('storage/' . $user->ktp) }}" alt="KTP" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Ijazah Modal -->
        @if ($user->ijazah)
            <div class="modal fade" id="ijazahModal" tabindex="-1" aria-labelledby="ijazahModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ijazahModalLabel">Ijazah - {{ $user->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="{{ asset('storage/' . $user->ijazah) }}" alt="Ijazah" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Sertifikat Modals -->
        @if ($user->sertifikat && count($user->sertifikat) > 0)
            @foreach ($user->sertifikat as $index => $sertifikat)
                <div class="modal fade" id="sertifikatModal{{ $index }}" tabindex="-1"
                    aria-labelledby="sertifikatModalLabel{{ $index }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="sertifikatModalLabel{{ $index }}">
                                    {{ basename($sertifikat) }} - {{ $user->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                <img src="{{ asset('storage/' . $sertifikat) }}" alt="{{ basename($sertifikat) }}"
                                    class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        <!-- Upload Document Modal -->
        <div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-labelledby="uploadDocumentModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadDocumentModalLabel">Upload document</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('user.upload-document', $user->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="document" class="form-label">Select document</label>
                                <input type="file" class="form-control @error('document') is-invalid @enderror"
                                    id="document" name="document" accept=".jpg,.jpeg,.png,.pdf" required>
                                <div class="form-text">Allowed formats: JPG, PNG, PDF. Maximum size: 2MB</div>
                                @error('document')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection
