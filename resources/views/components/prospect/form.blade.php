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

    {{-- Customer Information Section --}}

    <div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label required">Customer Name</label>
                <input type="text" name="customer_name"
                    class="form-control @error('customer_name') is-invalid @enderror"
                    value="{{ old('customer_name', $prospect?->customer_name) }}" placeholder="Enter customer name"
                    required>
                @error('customer_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label required">Phone Number</label>
                <input type="tel" name="no_handphone"
                    class="form-control @error('no_handphone') is-invalid @enderror"
                    value="{{ old('no_handphone', $prospect?->no_handphone) }}" placeholder="Enter phone number"
                    required>
                @error('no_handphone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label required">Email Address</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email', $prospect?->email) }}" placeholder="Enter email address" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label required">Company Name</label>
                <input type="text" name="company" class="form-control @error('company') is-invalid @enderror"
                    value="{{ old('company', $prospect?->company) }}" placeholder="Enter company name" required>
                @error('company')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label required">Company Identity</label>
                <input type="text" name="company_identity"
                    class="form-control @error('company_identity') is-invalid @enderror"
                    value="{{ old('company_identity', $prospect?->company_identity) }}"
                    placeholder="Enter company identity number" maxlength="3" required>
                <small class="form-hint">Max 3 characters</small>

                @error('company_identity')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label required">Pre Sales Person</label>
                <select name="pre_sales" class="form-control @error('pre_sales') is-invalid @enderror" required>
                    <option value="">Select Pre Sales Person</option>
                    @foreach ($salesUser as $user)
                        <option value="{{ $user->id }}"
                            {{ old('pre_sales', $prospect?->pre_sales) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('pre_sales')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    {{-- Prospect Information Section --}}

    <div>
        <div class="row">

            {{-- @dd() --}}

            <div class="col-md-12 mb-3">
                <label class="form-label required">Target Deal Period</label>
                <div class="row g-2">
                    <div class="col-3">
                        <select name="target_deal_from_month"
                            class="form-control @error('target_deal') is-invalid @enderror" required>
                            <option value="">From Month</option>
                            @php
                                // $fromMonth = old('target_deal_from_month');
                            @endphp

                            @foreach (['01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'] as $value => $label)
                                <option value="{{ $value }}"
                                    {{ $prospect->target_from_month == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        <select name="target_deal_from_year"
                            class="form-control @error('target_deal') is-invalid @enderror" required>
                            <option value="">From Year</option>
                            @php
                                $currentYear = date('Y');
                                $selectedFromYear = old('target_deal_from_year', $currentYear);
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
                            @php
                                // $toMonth = old('target_deal_to_month');
                            @endphp
                            @foreach (['01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'] as $value => $label)
                                <option value="{{ $value }}" {{ $prospect->target_to_month == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        <select name="target_deal_to_year"
                            class="form-control @error('target_deal') is-invalid @enderror" required>
                            <option value="">To Year</option>
                            @php
                                $currentYear = date('Y');
                                $selectedToYear = old('target_deal_to_year', $currentYear);
                            @endphp
                            @for ($year = $currentYear; $year <= $currentYear + 10; $year++)
                                <option value="{{ $year }}" {{ $selectedToYear == $year ? 'selected' : '' }}>
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




            {{-- Quotation Notes --}}
            @if ($quotation?->id)
                <div class="col-md-12 mb-3">
                    <label class="form-label">Quotation Notes</label>
                    <textarea name="quotation_notes" rows="3" class="form-control @error('quotation_notes') is-invalid @enderror"
                        placeholder="Enter any notes related to the quotation...">{{ old('quotation_notes', $quotation?->notes) }}</textarea>
                    @error('quotation_notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Status Progress </label>
            <select name="status_id" class="form-control @error('status_id') is-invalid @enderror">
                <option value="">Pilih Status Progress </option>
                @foreach ($prospectStatuses as $status)
                    @if ($status->persentage !== 0)
                        <option value="{{ $status->id }}"
                            {{ old('status_id', $prospect?->status_id) == $status->id ? 'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                    @endif
                @endforeach
            </select>
            @error('status_progress')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Product yang ditawarkan</label>
            <input type="text" name="product_offered"
                class="form-control @error('product_offered') is-invalid @enderror"
                value="{{ old('product_offered', $prospect?->product_offered ?? '') }}"
                placeholder="Product yang ditawarkan">
            @error('product_offered')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-12">
        <label class="form-label">Additional Notes</label>
        <textarea name="note" rows="4" class="form-control @error('note') is-invalid @enderror"
            placeholder="Enter any additional information or notes about this prospect...">{{ old('note', $prospect?->note) }}</textarea>
        @error('note')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    {{-- Form Actions --}}
    <div class="d-flex justify-content-end align-items-center mt-4">
        <button type="submit" class="btn btn-primary">
            Simpan Prospect
        </button>
    </div>
</form>

@pushOnce('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const prospectForm = document.getElementById('prospectForm');

            // Form submission validation
            prospectForm.addEventListener('submit', function(e) {
                let formValid = true;
                const requiredFields = prospectForm.querySelectorAll('[required]');

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
                const fromMonth = prospectForm.querySelector('select[name="target_deal_from_month"]').value;
                const fromYear = prospectForm.querySelector('select[name="target_deal_from_year"]').value;
                const toMonth = prospectForm.querySelector('select[name="target_deal_to_month"]').value;
                const toYear = prospectForm.querySelector('select[name="target_deal_to_year"]').value;

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
                    alert('Mohon lengkapi semua field yang wajib diisi.');
                    return false;
                }

                // Show loading state
                const submitBtn = prospectForm.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan...';
            });

            // File upload validation
            const documentInput = prospectForm.querySelector('input[name="document"]');
            if (documentInput) {
                documentInput.addEventListener('change', function(e) {
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
                            alert(
                                'Please select a valid file format (PDF, DOC, DOCX, XLS, XLSX, CSV, JPG, PNG)'
                            );
                            e.target.value = '';
                            return;
                        }
                    }
                });
            }
        });
    </script>
@endPushOnce
