@extends('layouts.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <div class="page-pretitle">Edit</div>
            <h2 class="page-title">Edit Prospect</h2>
        </div>
    </div>
@endsection

@section('content')
    {{-- Success & Error Messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon alert-icon">
                        <path d="M20 6L9 17l-5-5"></path>
                    </svg>
                </div>
                <div class="ms-2">
                    {{ session('success') }}
                </div>
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
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
                        </path>
                        <line x1="12" y1="9" x2="12" y2="13"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                </div>
                <div class="ms-2">
                    {{ session('error') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon alert-icon">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
                        </path>
                        <line x1="12" y1="9" x2="12" y2="13"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                </div>
                <div class="ms-2">
                    <strong>Terdapat kesalahan pada form:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row row-cards">
        <div class="col-12">
            <form action="{{ route('prospect.update', $prospect->id) }}" method="POST" class="card" id="prospectForm"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Customer Information Section --}}
                <div class="card-header">
                    <h3 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon me-2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Customer Information
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Customer Name</label>
                            <input type="text" name="customer_name"
                                class="form-control @error('customer_name') is-invalid @enderror"
                                value="{{ old('customer_name', $prospect->customer_name) }}"
                                placeholder="Enter customer name" required>
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Phone Number</label>
                            <input type="tel" name="no_handphone"
                                class="form-control @error('no_handphone') is-invalid @enderror"
                                value="{{ old('no_handphone', $prospect->no_handphone) }}"
                                placeholder="Enter phone number" required>
                            @error('no_handphone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Email Address</label>
                            <input type="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $prospect->email) }}" placeholder="Enter email address" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Company Name</label>
                            <input type="text" name="company"
                                class="form-control @error('company') is-invalid @enderror"
                                value="{{ old('company', $prospect->company) }}" placeholder="Enter company name"
                                required>
                            @error('company')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Company Identity</label>
                            <input type="text" name="company_identity"
                                class="form-control @error('company_identity') is-invalid @enderror"
                                value="{{ old('company_identity', $prospect->company_identity) }}"
                                placeholder="Enter company identity number" maxlength="3" required>
                            <small class="form-hint">Max 3 characters</small>
                            @error('company_identity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Prospect Information Section --}}
                <div class="card-header border-top">
                    <h3 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon me-2">
                            <path d="M3 12l2-2 4 4L19 4"></path>
                        </svg>
                        Prospect Information
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">


                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Pre Sales Person</label>
                            <select name="pre_sales" class="form-control @error('pre_sales') is-invalid @enderror"
                                required>
                                <option value="">Select Pre Sales Person</option>
                                @foreach (\App\Models\User::all() as $user)
                                    <option value="{{ $user->id }}"
                                        {{ (old('pre_sales') ?? $prospect->pre_sales) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pre_sales')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Target Deal Period</label>
                            <div class="row g-2">
                                <div class="col-3">
                                    <select name="target_deal_from_month"
                                        class="form-control @error('target_deal') is-invalid @enderror" required>
                                        <option value="">From Month</option>
                                        <option value="01"
                                            {{ (old('target_deal_from_month') ?? $prospect->target_from_month) == '01' ? 'selected' : '' }}>
                                            January</option>
                                        <option value="02"
                                            {{ (old('target_deal_from_month') ?? $prospect->target_from_month) == '02' ? 'selected' : '' }}>
                                            February</option>
                                        <option value="03"
                                            {{ (old('target_deal_from_month') ?? $prospect->target_from_month) == '03' ? 'selected' : '' }}>
                                            March</option>
                                        <option value="04"
                                            {{ (old('target_deal_from_month') ?? $prospect->target_from_month) == '04' ? 'selected' : '' }}>
                                            April</option>
                                        <option value="05"
                                            {{ (old('target_deal_from_month') ?? $prospect->target_from_month) == '05' ? 'selected' : '' }}>
                                            May</option>
                                        <option value="06"
                                            {{ (old('target_deal_from_month') ?? $prospect->target_from_month) == '06' ? 'selected' : '' }}>
                                            June</option>
                                        <option value="07"
                                            {{ (old('target_deal_from_month') ?? $prospect->target_from_month) == '07' ? 'selected' : '' }}>
                                            July</option>
                                        <option value="08"
                                            {{ (old('target_deal_from_month') ?? $prospect->target_from_month) == '08' ? 'selected' : '' }}>
                                            August</option>
                                        <option value="09"
                                            {{ (old('target_deal_from_month') ?? $prospect->target_from_month) == '09' ? 'selected' : '' }}>
                                            September</option>
                                        <option value="10"
                                            {{ (old('target_deal_from_month') ?? $prospect->target_from_month) == '10' ? 'selected' : '' }}>
                                            October</option>
                                        <option value="11"
                                            {{ (old('target_deal_from_month') ?? $prospect->target_from_month) == '11' ? 'selected' : '' }}>
                                            November</option>
                                        <option value="12"
                                            {{ (old('target_deal_from_month') ?? $prospect->target_from_month) == '12' ? 'selected' : '' }}>
                                            December</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <select name="target_deal_from_year"
                                        class="form-control @error('target_deal') is-invalid @enderror" required>
                                        <option value="">From Year</option>
                                        @php
                                            $currentYear = date('Y');
                                            $selectedFromYear = old('target_deal_from_year', $prospect->target_from_year ?? $prospect->target_year);
                                        @endphp
                                        @for ($year = $currentYear; $year <= $currentYear + 10; $year++)
                                            <option value="{{ $year }}"
                                                {{ $selectedFromYear == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-3">
                                    <select name="target_deal_to_month"
                                        class="form-control @error('target_deal') is-invalid @enderror" required>
                                        <option value="">To Month</option>
                                        <option value="01"
                                            {{ (old('target_deal_to_month') ?? $prospect->target_to_month) == '01' ? 'selected' : '' }}>
                                            January</option>
                                        <option value="02"
                                            {{ (old('target_deal_to_month') ?? $prospect->target_to_month) == '02' ? 'selected' : '' }}>
                                            February</option>
                                        <option value="03"
                                            {{ (old('target_deal_to_month') ?? $prospect->target_to_month) == '03' ? 'selected' : '' }}>
                                            March</option>
                                        <option value="04"
                                            {{ (old('target_deal_to_month') ?? $prospect->target_to_month) == '04' ? 'selected' : '' }}>
                                            April</option>
                                        <option value="05"
                                            {{ (old('target_deal_to_month') ?? $prospect->target_to_month) == '05' ? 'selected' : '' }}>
                                            May</option>
                                        <option value="06"
                                            {{ (old('target_deal_to_month') ?? $prospect->target_to_month) == '06' ? 'selected' : '' }}>
                                            June</option>
                                        <option value="07"
                                            {{ (old('target_deal_to_month') ?? $prospect->target_to_month) == '07' ? 'selected' : '' }}>
                                            July</option>
                                        <option value="08"
                                            {{ (old('target_deal_to_month') ?? $prospect->target_to_month) == '08' ? 'selected' : '' }}>
                                            August</option>
                                        <option value="09"
                                            {{ (old('target_deal_to_month') ?? $prospect->target_to_month) == '09' ? 'selected' : '' }}>
                                            September</option>
                                        <option value="10"
                                            {{ (old('target_deal_to_month') ?? $prospect->target_to_month) == '10' ? 'selected' : '' }}>
                                            October</option>
                                        <option value="11"
                                            {{ (old('target_deal_to_month') ?? $prospect->target_to_month) == '11' ? 'selected' : '' }}>
                                            November</option>
                                        <option value="12"
                                            {{ (old('target_deal_to_month') ?? $prospect->target_to_month) == '12' ? 'selected' : '' }}>
                                            December</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <select name="target_deal_to_year"
                                        class="form-control @error('target_deal') is-invalid @enderror" required>
                                        <option value="">To Year</option>
                                        @php
                                            $currentYear = date('Y');
                                            $selectedToYear = old('target_deal_to_year', $prospect->target_to_year ?? $prospect->target_year);
                                        @endphp
                                        @for ($year = $currentYear; $year <= $currentYear + 10; $year++)
                                            <option value="{{ $year }}"
                                                {{ $selectedToYear == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            @error('target_deal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>



                        <div class="col-md-12 mb-3">
                            <label class="form-label">Quotation Document</label>
                            <input type="file" name="document"
                                class="form-control @error('document') is-invalid @enderror"
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <small class="form-hint">
                                Accepted formats: xlsx
                                @if ($prospect->document)
                                    <br><strong>Current file:</strong> {{ basename($prospect->document) }}
                                @endif
                            </small>
                            @error('document')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Additional Notes</label>
                            <textarea name="note" rows="4" class="form-control @error('note') is-invalid @enderror"
                                placeholder="Enter any additional information or notes about this prospect...">{{ old('note', $prospect->note) }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                {{-- Form Actions --}}
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('prospect.index') }}" class="btn btn-outline-light">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon me-2">
                                <path d="M19 12H5"></path>
                                <path d="M12 19l-7-7 7-7"></path>
                            </svg>
                            Cancel
                        </a>

                        <button type="submit" class="btn btn-primary">

                            Update Prospect
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const prospectForm = document.getElementById('prospectForm');

            // Form submission validation
            prospectForm.addEventListener('submit', function(e) {
                let formValid = true;
                const requiredFields = document.querySelectorAll('[required]');

                // Clear previous validation states
                requiredFields.forEach(field => {
                    field.classList.remove('is-invalid');
                });

                // Validate required fields
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        formValid = false;
                    }
                });

                // Validate target deal period
                const fromMonth = document.querySelector('select[name="target_deal_from_month"]').value;
                const fromYear = document.querySelector('select[name="target_deal_from_year"]').value;
                const toMonth = document.querySelector('select[name="target_deal_to_month"]').value;
                const toYear = document.querySelector('select[name="target_deal_to_year"]').value;

                if (fromMonth && fromYear && toMonth && toYear) {
                    const fromDate = new Date(parseInt(fromYear), parseInt(fromMonth) - 1);
                    const toDate = new Date(parseInt(toYear), parseInt(toMonth) - 1);
                    
                    if (fromDate > toDate) {
                        alert('Periode "dari" tidak boleh lebih besar dari periode "sampai"');
                        e.preventDefault();
                        return false;
                    }
                }

                if (!formValid) {
                    e.preventDefault();
                    // Show alert for better user experience
                    alert('Mohon lengkapi semua field yang wajib diisi.');
                    return false;
                }

                // Show loading state
                const submitBtn = prospectForm.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Updating...';
            });



            // File upload validation
            const documentInput = document.querySelector('input[name="document"]');
            if (documentInput) {
                documentInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const maxSize = 5 * 1024 * 1024; // 5MB
                        const allowedTypes = ['application/pdf', 'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'image/jpeg', 'image/jpg', 'image/png'
                        ];

                        if (file.size > maxSize) {
                            alert('File size must be less than 5MB');
                            e.target.value = '';
                            return;
                        }

                        if (!allowedTypes.includes(file.type)) {
                            alert('Please select a valid file format (PDF, DOC, DOCX, JPG, PNG)');
                            e.target.value = '';
                            return;
                        }
                    }
                });
            }
        });
    </script>
@endpush
