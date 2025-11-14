@extends('layouts.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <div class="page-pretitle">Sales Order</div>
            <h2 class="page-title">{{$prospect->quotations[0]->quotation_number}}</h2>
        </div>
    </div>
@endsection

@section('content')
    {{-- Success & Error Messages --}}
    @if (session('success'))
        <div class="alert  alert-important alert-success alert-important alert-dismissible fade show" role="alert">
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
        <div class="alert  alert-important alert-danger alert-dismissible fade show" role="alert">
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
        <div class="alert  alert-important  alert-danger alert-dismissible fade show" role="alert">
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

    <form method="POST" action="{{ route('sales-order.store') }}" id="prospectForm" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="prospect_id" value="{{ $prospect->id }}">

        {{-- Header Section with Photo and Sales Information --}}
        <div class="card border-0 shadow-none">
            <div class="card-body">
                <div class="row g-3">
                    {{-- Photo Section --}}
                    <div class="col-lg-3">
                        <div class="border border-2 border-dashed rounded p-3 text-center d-flex align-items-center justify-content-center"
                            style="height: 140px;">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="mx-auto mb-2 text-muted">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                    <polyline points="21 15 16 10 5 21"></polyline>
                                </svg>
                                <p class="text-muted small">No Image</p>
                            </div>
                        </div>
                    </div>

                    {{-- Sales Member Section --}}
                    <div class="col-lg-3">

                        <div class="mb-3">
                            <label class="form-label fw-bold small">NAMA SALES</label>
                            <input type="text" name="sales_name" disabled
                                class="form-control @error('sales_name') is-invalid @enderror"
                                value="{{ $prospect->creator->name }}" placeholder="Nama Sales">
                            @error('sales_name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small"> COMPANY</label>
                            <input type="text" name="company_identity" disabled
                                class="form-control @error('company_identity') is-invalid @enderror"
                                value="{{ $prospect->company_identity }}" placeholder="Company ID" maxlength="3">
                            @error('company_identity')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label class="form-label fw-bold">PROJECT NAME</label>
                            <input type="text" name="project_name"
                                class="form-control @error('project_name') is-invalid @enderror"
                                value="{{ old('project_name') }}" placeholder="Nama Project">
                            @error('project_name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">PIC PROJECT</label>
                            <select name="pic_project"
                                class="form-control @error('pic_project') is-invalid @enderror" required>
                                <option value="">Pilih PIC Project</option>
                                @foreach($projectUsers as $user)
                                    <option value="{{ $user->id }}" {{ old('pic_project') == $user->name ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pic_project')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label class="form-label fw-bold">DEADLINE PROJECT (DAYS)</label>
                            <input type="number" name="deadline_days"
                                class="form-control @error('deadline_days') is-invalid @enderror"
                                value="{{ old('deadline_days') }}" placeholder="Jumlah hari">
                            @error('deadline_days')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                </div>
            </div>
        </div>


        {{-- Automatic User Information (Dynamic Contacts List) --}}

        <div class="card border-0 shadow-none mt-3">

            <div class="card-body">
                <div id="contactsContainer">
                    <div class="contact-item mb-4 pb-4 border-bottom" data-contact-index="1">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="mb-0 fw-bold">Kontak #1</h6>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">NAMA</label>
                                <input type="text" name="contacts[1][name]"
                                    class="form-control @error('contacts.1.name') is-invalid @enderror"
                                    value="{{ $prospect->customer_name }}" placeholder="Nama" required>
                                @error('contacts.1.name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">HANDPHONE</label>
                                <input type="tel" name="contacts[1][phone]"
                                    class="form-control @error('contacts.1.phone') is-invalid @enderror"
                                    value="{{ $prospect->no_handphone }}" placeholder="Phone" required>
                                @error('contacts.1.phone')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">EMAIL</label>
                                <input type="email" name="contacts[1][email]"
                                    class="form-control @error('contacts.1.email') is-invalid @enderror"
                                    value="{{ $prospect->email }}" placeholder="Email" required>
                                @error('contacts.1.email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">JABATAN</label>
                                <input type="text" name="contacts[1][position]"
                                    class="form-control @error('contacts.1.position') is-invalid @enderror"
                                    value="{{ old('contacts.1.position', old('position')) }}" placeholder="Jabatan">
                                @error('contacts.1.position')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                </div>
                <div class="d-flex justify-content-end align-items-center my-3">
                    <button type="button" class="btn btn-success" id="addContactBtn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="me-1" style="display: inline;">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Tambah Kontak
                    </button>
                </div>
            </div>
        </div>

        {{-- Contact Template (Hidden) --}}
        <template id="contactTemplate">
            <div class="contact-item mb-4 pb-4 border-bottom" data-contact-index="__INDEX__">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h6 class="mb-0 fw-bold">Kontak #__INDEX__</h6>
                    <button type="button" class="btn btn-sm btn-danger removeContactBtn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                        Hapus
                    </button>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">NAMA</label>
                        <input type="text" name="contacts[__INDEX__][name]" class="form-control" placeholder="Nama">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">HANDPHONE</label>
                        <input type="tel" name="contacts[__INDEX__][phone]" class="form-control"
                            placeholder="Phone">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">EMAIL</label>
                        <input type="email" name="contacts[__INDEX__][email]" class="form-control"
                            placeholder="Email">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small">JABATAN</label>
                        <input type="text" name="contacts[__INDEX__][position]" class="form-control"
                            placeholder="Jabatan">
                    </div>
                </div>
            </div>
        </template>

        {{-- Upload Files Section --}}
        <div class="card border-0 shadow-none mt-3">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-lg-6">
                        <label class="form-label fw-bold">UPLOAD SKEMATIK RFP</label>
                        <input type="file" name="rfp_file" id="rfpFile"
                            class="form-control @error('rfp_file') is-invalid @enderror"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.csv,.jpg,.jpeg,.png">
                        @error('rfp_file')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted d-block mt-1">Max 5MB - PDF, DOC, XLS, CSV, JPG, PNG</small>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label fw-bold">UPLOAD DATA PROPOSAL</label>
                        <input type="file" name="proposal_file" id="proposalFile"
                            class="form-control @error('proposal_file') is-invalid @enderror"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.csv,.jpg,.jpeg,.png">
                        @error('proposal_file')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted d-block mt-1">Max 5MB - PDF, DOC, XLS, CSV, JPG, PNG</small>
                    </div>
                </div>
            </div>
        </div>

        @php
            $quotation = $prospect->quotations()->latest()->first();
        @endphp

        <!-- Quotation Items Section -->
        <div class="row row-cards mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon me-2">
                                <path d="M3 3h18v4H3z"></path>
                                <path d="M3 11h18v4H3z"></path>
                                <path d="M3 19h18v4H3z"></path>
                            </svg>
                            Product Items
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($quotation && $quotation->items ? $quotation->items : [] as $item)
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ $item->product->name }}</div>
                                                @if ($item->product->description)
                                                    <div class="text-muted small">{{ $item->product->description }}</div>
                                                @endif
                                            </td>
                                            <td>{{ number_format($item->quantity) }}</td>
                                            <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                            <td class="text-end fw-bold">
                                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4">
                                                <div class="empty">
                                                    <p class="empty-title">No items found</p>
                                                    <p class="empty-subtitle text-muted">
                                                        This quotation doesn't have any items yet.
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if ($quotation && $quotation->items->count() > 0)
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end fw-bold">Products Total:</td>
                                            <td class="text-end fw-bold text-success fs-5">
                                                Rp {{ number_format($quotation->items->sum('subtotal'), 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Installation Items Section -->
        @if ($quotation && $quotation->installationItems->count() > 0)
            <div class="row row-cards mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="icon me-2">
                                    <path d="M12 3l8 4.5v9L12 21l-8-4.5v-9L12 3z"></path>
                                    <path d="M12 12l8-4.5"></path>
                                    <path d="M12 12v9"></path>
                                    <path d="M12 12L4 7.5"></path>
                                </svg>
                                Installation Items
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-vcenter card-table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Installation Service</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($quotation->installationItems as $item)
                                            <tr>
                                                <td>
                                                    <div class="fw-bold">{{ $item->installation->name }}</div>
                                                    @if ($item->installation->description)
                                                        <div class="text-muted small">
                                                            {{ $item->installation->description }}</div>
                                                    @endif
                                                </td>
                                                <td>{{ number_format($item->quantity) }}</td>
                                                <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                                <td class="text-end fw-bold">
                                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end fw-bold">Installation Total:</td>
                                            <td class="text-end fw-bold text-info fs-5">
                                                Rp
                                                {{ number_format($quotation->installationItems->sum('subtotal'), 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Accommodation Items Section -->
        @if ($quotation && $quotation->need_accommodation)
            <div class="row row-cards mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="icon me-2">
                                    <path d="M3 3h18v4H3z"></path>
                                    <path d="M3 11h18v4H3z"></path>
                                    <path d="M3 19h18v4H3z"></path>
                                </svg>
                                Accommodation Items
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Wilayah</label>
                                        <div class="fw-bold">{{ $quotation->accommodation_wilayah }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Hotel Rooms</label>
                                        <div class="fw-bold">{{ $quotation->accommodation_hotel_rooms }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">People</label>
                                        <div class="fw-bold">{{ $quotation->accommodation_people }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Target Days</label>
                                        <div class="fw-bold">{{ $quotation->accommodation_target_days }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Plane Ticket Price</label>
                                        <div class="fw-bold">Rp
                                            {{ number_format($quotation->accommodation_plane_ticket_price, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-vcenter card-table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Accommodation</th>
                                            <th class="text-end">Unit Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($quotation->accommodationItems as $item)
                                            <tr>
                                                <td>
                                                    <div class="fw-bold">{{ $item->name }}</div>
                                                </td>
                                                <td class="text-end">Rp
                                                    {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="text-center py-4">
                                                    <div class="empty">
                                                        <p class="empty-title">No accommodation items found</p>
                                                        <p class="empty-subtitle text-muted">
                                                            This quotation doesn't have any accommodation items yet.
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if ($quotation->accommodationItems->count() > 0)
                                        <tfoot>
                                            <tr>
                                                <td class="text-end fw-bold">Accommodation Total:</td>
                                                <td class="text-end fw-bold text-warning fs-5">
                                                    Rp
                                                    {{ number_format($quotation->accommodation_total_amount, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif


        <div class="card border-0 shadow-none mt-3">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-lg-4">
                        <button type="button" class="btn btn-primary  w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="me-2" style="display: inline;">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                            Export/PRINT<br>
                        </button>
                    </div>
                    <div class="col-lg-4">
                        <button type="button" class="btn btn-info w-100" id="minuteOfMeetingBtn">
                            MINUTE OF MEETING<br>
                        </button>
                    </div>
                    <div class="col-lg-4">
                        <button type="submit" class="btn btn-success  w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="me-2" style="display: inline;">
                                <path d="M20 6L9 17l-5-5"></path>
                            </svg>
                            DONE
                        </button>
                    </div>
                </div>
            </div>
        </div>


    </form>

    <!-- Minute of Meeting Modal -->
    <div class="modal fade" id="minuteOfMeetingModal" tabindex="-1" role="dialog" aria-labelledby="minuteOfMeetingLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="minuteOfMeetingLabel">Minute of Meeting</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="minuteOfMeetingForm">
                        @csrf
                        <input type="hidden" name="prospect_id" value="{{ $prospect->id }}">
                        <div class="mb-3">
                            <label for="minuteOfMeetingTextarea" class="form-label fw-bold">Notes</label>
                            <textarea class="form-control" id="minuteOfMeetingTextarea" name="body" rows="10" placeholder="Enter your meeting notes here...">{{ $prospect->minuteOfMeetings->body ?? '' }}</textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveMinuteBtn">Save</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let contactCount = 1;

            const addContactBtn = document.getElementById('addContactBtn');
            const contactsContainer = document.getElementById('contactsContainer');
            const contactTemplate = document.getElementById('contactTemplate');

            // Minute of Meeting modal initialization
            const minuteOfMeetingBtn = document.getElementById('minuteOfMeetingBtn');
            const minuteOfMeetingModal = new bootstrap.Modal(document.getElementById('minuteOfMeetingModal'));
            const minuteOfMeetingTextarea = document.getElementById('minuteOfMeetingTextarea');
            const saveMinuteBtn = document.getElementById('saveMinuteBtn');

            // Minute of Meeting button click handler
            minuteOfMeetingBtn.addEventListener('click', function(e) {
                e.preventDefault();
                minuteOfMeetingModal.show();
            });

            // Save minute button click handler
            saveMinuteBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const body = minuteOfMeetingTextarea.value;
                const prospectId = document.querySelector('input[name="prospect_id"]').value;

                if (!body.trim()) {
                    alert('Please enter meeting notes');
                    return;
                }

                // Disable button and show loading state
                saveMinuteBtn.disabled = true;
                const originalText = saveMinuteBtn.innerHTML;
                saveMinuteBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Saving...';

                fetch('{{ route("sales-order.saveMinuteOfMeeting") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.body || 
                                       document.querySelector('input[name="_token"]')?.value,
                    },
                    body: JSON.stringify({
                        prospect_id: prospectId,
                        body: body,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    // Restore button state
                    saveMinuteBtn.disabled = false;
                    saveMinuteBtn.innerHTML = originalText;

                    if (data.success) {
                     
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    // Restore button state
                    saveMinuteBtn.disabled = false;
                    saveMinuteBtn.innerHTML = originalText;
                    console.error('Error:', error);
                    alert('An error occurred while saving');
                });
            });

            // Function to attach remove listener
            function attachRemoveListener(contactElement) {
                const removeBtn = contactElement.querySelector('.removeContactBtn');
                if (removeBtn) {
                    removeBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        contactElement.remove();
                        updateContactNumbers();
                    });
                }
            }

            // Function to update contact numbers
            function updateContactNumbers() {
                const contactItems = document.querySelectorAll('.contact-item');
                contactItems.forEach((item, index) => {
                    const heading = item.querySelector('h6');
                    if (heading) {
                        heading.textContent = `Kontak #${index + 1}`;
                    }
                    // Update data attribute
                    item.setAttribute('data-contact-index', index + 1);
                });
            }

            // Add contact button click handler
            addContactBtn.addEventListener('click', function(e) {
                e.preventDefault();
                contactCount++;

                // Clone template
                const newContactNode = contactTemplate.content.cloneNode(true);

                // Create temporary container to manipulate HTML
                const tempDiv = document.createElement('div');
                tempDiv.appendChild(newContactNode);

                // Get the HTML string and replace indices
                const contactItemDiv = tempDiv.querySelector('.contact-item');
                const allElements = contactItemDiv.querySelectorAll('[name*="__INDEX__"]');

                allElements.forEach(elem => {
                    const oldName = elem.getAttribute('name');
                    const newName = oldName.replace(/__INDEX__/g, contactCount);
                    elem.setAttribute('name', newName);
                });

                // Update heading
                const heading = contactItemDiv.querySelector('h6');
                if (heading) {
                    heading.textContent = `Kontak #${contactCount}`;
                }

                // Update data attribute
                contactItemDiv.setAttribute('data-contact-index', contactCount);

                // Append to container
                contactsContainer.appendChild(contactItemDiv);

                // Attach remove listener to new contact
                attachRemoveListener(contactItemDiv);

                console.log(`Contact #${contactCount} added successfully`);
            });

            // Initialize remove listeners for existing contacts
            document.querySelectorAll('.contact-item').forEach(item => {
                attachRemoveListener(item);
            });
        });
    </script>
@endsection
