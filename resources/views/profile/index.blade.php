@extends('layouts.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <!-- Page pre-title -->
            <div class="page-pretitle">My account</div>
            <h2 class="page-title">Profile</h2>
        </div>
    </div>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex">
               
                <div class="text-white">{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon alert-icon">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                </div>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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
                                    <div class="d-flex gap-2 flex-wrap mb-3">
                                        <span class="badge bg-blue-lt">{{ $user->type }}</span>
                                        <span class="badge bg-purple-lt">{{ $user->division->kode }}</span>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#uploadProfilePhotoModal">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="icon">
                                            <path d="M15 8h.01"></path>
                                            <path d="M3 6a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v12a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-12z"></path>
                                            <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5"></path>
                                            <path d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0l3 3"></path>
                                        </svg>
                                        {{ $user->foto ? 'Update profile photo' : 'Upload profile photo' }}
                                    </button>
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

                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Roles & Status Card -->
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Signature</h3>
                </div>
                <div class="card-body">
                    @if ($user->ttd_img)
                        <div class="mb-3 text-center">
                            <img src="{{ asset('storage/' . $user->ttd_img) }}" alt="Signature" 
                                class="img-fluid" style="max-height: 150px; border: 1px solid #e9ecef; border-radius: 4px; padding: 10px;">
                        </div>
                    @else
                        <div class="text-muted text-center mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" 
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" 
                                stroke-linejoin="round" class="icon text-muted mb-2">
                                <path d="M3 17c3.333 -3.333 5 -6 5 -8c0 -3 -1 -3 -2 -3s-2.032 1.085 -2 3c.034 2.048 1.658 4.877 2.5 6c1.5 2 2.5 2.5 3.5 1l2 -3c.333 2.667 1.333 4 3 4c.53 0 2.639 -2 3 -2c.517 0 1.517 .667 3 2"></path>
                            </svg>
                            <p class="small">No signature uploaded yet</p>
                        </div>
                    @endif
                    <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal"
                        data-bs-target="#uploadSignatureModal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon">
                            <path d="M7 18a4.6 4.4 0 0 1 0 -9a5 4.5 0 0 1 11 2h1a3.5 3.5 0 0 1 0 7h-1"></path>
                            <path d="M9 15l3 -3l3 3"></path>
                            <path d="M12 12l0 9"></path>
                        </svg>
                        {{ $user->ttd_img ? 'Update signature' : 'Upload signature' }}
                    </button>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Documents</h3>
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

        <!-- Upload Profile Photo Modal -->
        <div class="modal fade" id="uploadProfilePhotoModal" tabindex="-1" aria-labelledby="uploadProfilePhotoModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadProfilePhotoModalLabel">Upload profile photo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('profile.upload-photo') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="profile_photo" class="form-label">Select profile photo</label>
                                <input type="file" class="form-control @error('profile_photo') is-invalid @enderror"
                                    id="profile_photo" name="profile_photo" accept=".jpg,.jpeg,.png" required>
                                <div class="form-text">Allowed formats: JPG, PNG. Maximum size: 2MB. Recommended size: 400x400 pixels.</div>
                                @error('profile_photo')
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

        <!-- Upload Signature Modal -->
        <div class="modal fade" id="uploadSignatureModal" tabindex="-1" aria-labelledby="uploadSignatureModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadSignatureModalLabel">Upload signature</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('profile.upload-signature') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="signature" class="form-label">Select signature image</label>
                                <input type="file" class="form-control @error('signature') is-invalid @enderror"
                                    id="signature" name="signature" accept=".jpg,.jpeg,.png" required>
                                <div class="form-text">Allowed formats: JPG, PNG. Maximum size: 2MB. Use transparent background for best results.</div>
                                @error('signature')
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

        <!-- Upload Document Modal -->
        <div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-labelledby="uploadDocumentModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadDocumentModalLabel">Upload document</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('profile.upload-document') }}" method="POST"
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
