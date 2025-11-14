@props([
    'route' => null,
    'salesUser' => [],
    'prospectStatuses' => [],
    'prospect' => null,
    'quotation' => null,
    'type' => null,
])

<form action="{{ $route }}" method="POST" id="prospectForm" enctype="multipart/form-data">
    @csrf
    @if ($prospect)
        @method('PUT')
    @endif

    <input type="hidden" name="form_type" value="{{ $type }}">

    <div class="card border-0 shadow-none">
        {{-- Header Section with Photo and Sales Information --}}
        <div class="card-body p-0">
            <table class="w-100 border">
                <tr>
                    <td colspan="2" class="border p-3" style="width: 40%">
                        <div class="mb-3">
                            <label class="form-label fw-bold">FOTO</label>
                            <div class="border-dashed rounded p-3 text-center" style="min-height: 150px; display: flex; align-items: center; justify-content: center;">
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-2 text-muted">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                        <polyline points="21 15 16 10 5 21"></polyline>
                                    </svg>
                                    <p class="text-muted small">No Image</p>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="border p-3" style="width: 30%">
                        <div class="mb-3">
                            <label class="form-label fw-bold">SALES MEMBER</label>
                            <label class="form-label fw-bold">NAMA SALES</label>
                            <input type="text" name="sales_name"
                                class="form-control form-control-sm @error('sales_name') is-invalid @enderror"
                                placeholder="Nama Sales">
                            @error('sales_name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">NO.ID COMPANY</label>
                            <input type="text" name="company_identity"
                                class="form-control form-control-sm @error('company_identity') is-invalid @enderror"
                                placeholder="Company ID" maxlength="3">
                            @error('company_identity')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </td>
                    <td class="border p-3" style="width: 30%">
                        <div class="mb-3">
                            <label class="form-label fw-bold">PROJECT NAME</label>
                            <input type="text" name="project_name"
                                class="form-control form-control-sm @error('project_name') is-invalid @enderror"
                                placeholder="Nama Project">
                            @error('project_name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">PIC PROJECT (renaldi)</label>
                            <input type="text" name="pic_project"
                                class="form-control form-control-sm @error('pic_project') is-invalid @enderror"
                                placeholder="PIC Project">
                            @error('pic_project')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Deadline and Additional Info --}}
    <div class="card border-0 shadow-none mt-3">
        <div class="card-body p-0">
            <table class="w-100 border">
                <tr>
                    <td class="border p-3" style="width: 50%">
                        <div class="mb-3">
                            <label class="form-label fw-bold">DEADLINE PROJECT (DAYS) (di isi)</label>
                            <input type="number" name="deadline_days"
                                class="form-control form-control-sm @error('deadline_days') is-invalid @enderror"
                                placeholder="Jumlah hari">
                            @error('deadline_days')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </td>
                    <td class="border p-3" style="width: 50%">
                        <div class="mb-3">
                            <label class="form-label fw-bold">DI ISI</label>
                            <input type="text" name="other_info"
                                class="form-control form-control-sm @error('other_info') is-invalid @enderror"
                                placeholder="Informasi lainnya">
                            @error('other_info')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Automatic User Information (Three Columns) --}}
    <div class="card border-0 shadow-none mt-3">
        <div class="card-body p-0">
            <table class="w-100 border">
                <tr>
                    <td class="border p-3" style="width: 33.33%">
                        <label class="form-label fw-bold">INFORMASI USER OTOMATIS</label>
                        <div class="mb-2">
                            <label class="form-label fw-bold">NAMA</label>
                            <input type="text" name="customer_name"
                                class="form-control form-control-sm @error('customer_name') is-invalid @enderror"
                                placeholder="Nama" required>
                            @error('customer_name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold">HANDPHONE</label>
                            <input type="tel" name="no_handphone"
                                class="form-control form-control-sm @error('no_handphone') is-invalid @enderror"
                                placeholder="Phone" required>
                            @error('no_handphone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold">EMAIL</label>
                            <input type="email" name="email"
                                class="form-control form-control-sm @error('email') is-invalid @enderror"
                                placeholder="Email" required>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold">JABATAN</label>
                            <input type="text" name="position"
                                class="form-control form-control-sm @error('position') is-invalid @enderror"
                                placeholder="Jabatan">
                            @error('position')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </td>
                    <td class="border p-3" style="width: 33.33%">
                        <label class="form-label fw-bold">NAMA</label>
                        <div class="mb-2">
                            <input type="text" name="contact_name_2"
                                class="form-control form-control-sm @error('contact_name_2') is-invalid @enderror"
                                placeholder="Nama">
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold">HANDPHONE</label>
                            <input type="tel" name="contact_phone_2"
                                class="form-control form-control-sm @error('contact_phone_2') is-invalid @enderror"
                                placeholder="Phone">
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold">EMAIL</label>
                            <input type="email" name="contact_email_2"
                                class="form-control form-control-sm @error('contact_email_2') is-invalid @enderror"
                                placeholder="Email">
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold">JABATAN</label>
                            <input type="text" name="contact_position_2"
                                class="form-control form-control-sm @error('contact_position_2') is-invalid @enderror"
                                placeholder="Jabatan">
                        </div>
                    </td>
                    <td class="border p-3" style="width: 33.33%">
                        <label class="form-label fw-bold">NAMA</label>
                        <div class="mb-2">
                            <input type="text" name="contact_name_3"
                                class="form-control form-control-sm @error('contact_name_3') is-invalid @enderror"
                                placeholder="Nama">
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold">HANDPHONE</label>
                            <input type="tel" name="contact_phone_3"
                                class="form-control form-control-sm @error('contact_phone_3') is-invalid @enderror"
                                placeholder="Phone">
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold">EMAIL</label>
                            <input type="email" name="contact_email_3"
                                class="form-control form-control-sm @error('contact_email_3') is-invalid @enderror"
                                placeholder="Email">
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold">JABATAN</label>
                            <input type="text" name="contact_position_3"
                                class="form-control form-control-sm @error('contact_position_3') is-invalid @enderror"
                                placeholder="Jabatan">
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Upload Files Section --}}
    <div class="card border-0 shadow-none mt-3">
        <div class="card-body p-0">
            <table class="w-100 border">
                <tr>
                    <td class="border p-3" style="width: 50%">
                        <div class="mb-3">
                            <label class="form-label fw-bold">UPLOAD SKEMATIK RFP</label>
                            <label class="form-label small">[Request for Proposal]</label>
                            <div class="mt-2">
                                <input type="file" name="rfp_file" id="rfpFile"
                                    class="form-control form-control-sm @error('rfp_file') is-invalid @enderror"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.csv,.jpg,.jpeg,.png">
                                @error('rfp_file')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted d-block mt-1">Max 5MB - PDF, DOC, XLS, CSV, JPG, PNG</small>
                            </div>
                        </div>
                    </td>
                    <td class="border p-3" style="width: 50%">
                        <div class="mb-3">
                            <label class="form-label fw-bold">UPLOAD DATA PROPOSAL</label>
                            <div class="mt-2">
                                <input type="file" name="proposal_file" id="proposalFile"
                                    class="form-control form-control-sm @error('proposal_file') is-invalid @enderror"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.csv,.jpg,.jpeg,.png">
                                @error('proposal_file')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted d-block mt-1">Max 5MB - PDF, DOC, XLS, CSV, JPG, PNG</small>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Bill of Quantity Section --}}
    <div class="card border-0 shadow-none mt-3">
        <div class="card-body p-0">
            <div class="border p-3">
                <h6 class="fw-bold mb-3">BILL OF QUANTITY (OTOMATIS DARI PENAWARAN HARGA YANG DEAL TANPA ADA HARGA)</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">PRODUCT 1</label>
                        <input type="text" name="product_1"
                            class="form-control form-control-sm @error('product_1') is-invalid @enderror"
                            placeholder="Product 1" readonly>
                        @error('product_1')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">PRODUCT 1</label>
                        <input type="text" name="product_1_qty"
                            class="form-control form-control-sm @error('product_1_qty') is-invalid @enderror"
                            placeholder="Quantity" readonly>
                        @error('product_1_qty')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">INSTALASI</label>
                        <input type="text" name="installation"
                            class="form-control form-control-sm @error('installation') is-invalid @enderror"
                            placeholder="Instalasi" readonly>
                        @error('installation')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Action Buttons Section --}}
    <div class="card border-0 shadow-none mt-3">
        <div class="card-body p-0">
            <table class="w-100 border">
                <tr>
                    <td class="border p-3" style="width: 33.33%">
                        <button type="button" class="btn btn-primary btn-sm w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2" style="display: inline;">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                            Export/PRINT<br>
                            <small>Buat standart form MOM</small>
                        </button>
                    </td>
                    <td class="border p-3" style="width: 33.33%">
                        <button type="button" class="btn btn-info btn-sm w-100">
                            MINITE OF MEETING<br>
                            <small>(ini keluar kaya notepad untuk ketik catatan)</small>
                        </button>
                    </td>
                    <td class="border p-3" style="width: 33.33%">
                        <button type="submit" class="btn btn-success btn-sm w-100">
                            DONE
                        </button>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Additional Info Section --}}
    <div class="card border-0 shadow-none mt-3">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Status Progress</label>
                <select name="status_id" class="form-control @error('status_id') is-invalid @enderror">
                    <option value="">Pilih Status Progress</option>
                    @foreach ($prospectStatuses as $status)
                        @if ($status->persentage !== 0)
                            <option value="{{ $status->id }}">
                                {{ $status->name }}
                            </option>
                        @endif
                    @endforeach
                </select>
                @error('status_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Additional Notes</label>
                <textarea name="note" rows="4" class="form-control @error('note') is-invalid @enderror"
                    placeholder="Enter any additional information or notes..."></textarea>
                @error('note')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

</form>

@pushOnce('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const prospectForm = document.getElementById('prospectForm');

            // File upload validation
            const validateFile = (inputElement) => {
                if (!inputElement) return;

                inputElement.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const maxSize = 5 * 1024 * 1024; // 5MB
                        const allowedTypes = [
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'text/csv',
                            'application/csv',
                            'image/jpeg',
                            'image/jpg',
                            'image/png'
                        ];

                        if (file.size > maxSize) {
                            alert('File size must be less than 5MB');
                            e.target.value = '';
                            return;
                        }

                        if (!allowedTypes.includes(file.type)) {
                            alert('Please select a valid file format (PDF, DOC, DOCX, XLS, XLSX, CSV, JPG, PNG)');
                            e.target.value = '';
                            return;
                        }
                    }
                });
            };

            validateFile(document.getElementById('rfpFile'));
            validateFile(document.getElementById('proposalFile'));
        });
    </script>
@endPushOnce
