@extends('layouts.app')

@push('styles')
    <style>
        .sales-team-selector {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            background: white;
            height: 450px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        .sales-team-selector > div:first-child {
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        .sales-team-selector .list-group-item {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            margin-bottom: 8px;
            padding-left: 12px !important;
            padding-right: 12px !important;
            color: #374151;
            font-weight: 500;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .sales-team-selector .list-group-item:hover {
            background-color: #f3f4f6;
            border-color: #3b82f6;
        }

        .sales-team-selector .list-group-item .icon {
            opacity: 0.5;
            transition: opacity 0.2s ease;
        }

        .sales-team-selector .list-group-item:hover .icon {
            opacity: 1;
        }

        .sales-team-selector .list-group-item.active {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }

        .sales-team-selector .list-group-item.active .icon {
            opacity: 1;
            color: white;
        }

        .target-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            border: 2px solid #e5e7eb;
        }

        .target-card h6 {
            font-size: 0.85rem;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }

        .target-card .input-group {
            width: 100%;
        }

        .target-card .input-group-text {
            background-color: #f8fafc;
            border: 1px solid #e5e7eb;
            color: #6b7280;
            font-weight: 600;
            font-size: 1.125rem;
        }

        .target-card input {
            border: 1px solid #e5e7eb;
            padding: 0.625rem 1rem;
            font-size: 1.5rem;
            font-weight: bold;
            transition: all 0.2s ease;
            color: #111827;
        }

        .target-card .input-group:focus-within .input-group-text {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }

        .target-card input:focus {
            border-color: #3b82f6;
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .save-button-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            border: 2px solid #e5e7eb;
            text-align: center;
        }

        .save-button-card button {
            padding: 0.75rem 2rem;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .save-button-card button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        #emptyState {
            color: #9ca3af;
            text-align: center;
            padding: 100px 20px;
        }

        #emptyState h3 {
            font-size: 1.125rem;
            font-weight: 500;
        }
    </style>
@endpush

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <div class="page-pretitle">Management</div>
            <h2 class="page-title">Sales target</h2>
        </div>
    </div>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-important alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon alert-icon">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M5 12l5 5l10 -10" />
                    </svg>
                </div>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-important alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon alert-icon">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 9v2m0 4v.01" />
                        <path
                            d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" />
                    </svg>
                </div>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Left Column: Sales Team Selector -->
        <div class="col-lg-4 mb-4">
            <div class="sales-team-selector d-flex flex-column justify-content-between">
                <div>
                    <h5 class="mb-3 fw-bold">MEMBER SALES</h5>
                    <div class="list-group list-group-flush">
                        @foreach ($salesMembers as $member)
                            <a href="?sales_id={{ $member->id }}"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0 py-2 {{ $selectedSales && $selectedSales->id == $member->id ? 'active' : '' }}">
                                <span class="fw-bold">TARGET SALES INDIVIDU {{ strtoupper($member->name) }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="icon">
                                    <path d="M9 6l6 6l-6 6"></path>
                                </svg>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Target Cards -->
        <div class="col-lg-8 mb-4">
            <form method="POST" action="{{ $salesTarget ? route('sales-target.update', $salesTarget->id) : route('sales-target.store') }}" id="salesTargetForm">
                @csrf
                @if($salesTarget)
                    @method('PUT')
                @endif
                <input type="hidden" name="year" value="{{ $currentYear }}">
                <input type="hidden" name="user_id" value="{{ $selectedSales->id ?? '' }}">

                <div id="targetCardsContainer" style="{{ $selectedSales ? 'display: block;' : 'display: none;' }}">
                    <!-- Target Gross Profit Card -->
                    <div class="target-card">
                        <h6>Target sales gross profit</h6>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" 
                                   class="form-control currency-input" 
                                   id="targetGrossProfit" 
                                   name="target_gross_profit"
                                   value="{{ old('target_gross_profit', $salesTarget->target_gross_profit ?? '') }}"
                                   placeholder="0">
                        </div>
                    </div>

                    <!-- Target Monthly Card -->
                    <div class="target-card">
                        <h6>Target sales bulanan</h6>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" 
                                   class="form-control currency-input" 
                                   id="targetMonthly" 
                                   name="target_monthly"
                                   value="{{ old('target_monthly', $salesTarget->target_monthly ?? '') }}"
                                   placeholder="0">
                        </div>
                    </div>

                    <!-- Target Yearly Card -->
                    <div class="target-card">
                        <h6>Target sales tahunan</h6>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" 
                                   class="form-control currency-input" 
                                   id="targetYearly" 
                                   name="target_yearly"
                                   value="{{ old('target_yearly', $salesTarget->target_yearly ?? '') }}"
                                   placeholder="0">
                        </div>
                    </div>

                    <!-- Save Button Card -->
                    <div class="save-button-card">
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M14 4l0 4l-6 0l0 -4" />
                            </svg>
                            Simpan Target
                        </button>
                    </div>
                </div>

                <div id="emptyState" style="{{ $selectedSales ? 'display: none;' : 'display: block;' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="icon text-muted mb-3">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                        <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                    </svg>
                    <h3 class="text-muted">Pilih member sales untuk mengatur target</h3>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const currencyInputs = document.querySelectorAll('.currency-input');

            // Function to format number as Indonesian Rupiah
            function formatCurrency(value) {
                // Remove non-numeric characters
                const numericValue = value.toString().replace(/[^0-9]/g, '');
                
                if (numericValue === '') return '';
                
                // Format with thousand separators
                return new Intl.NumberFormat('id-ID').format(parseInt(numericValue));
            }

            // Function to get numeric value from formatted string
            function getNumericValue(formattedValue) {
                return formattedValue.replace(/[^0-9]/g, '');
            }

            // Initialize existing values with formatting
            currencyInputs.forEach(input => {
                if (input.value && input.value !== '0') {
                    input.value = formatCurrency(input.value);
                }
            });

            currencyInputs.forEach(input => {
                if (!input) return;

                // Format currency on input
                input.addEventListener('input', function(e) {
                    const cursorPosition = e.target.selectionStart;
                    const oldValue = e.target.value;
                    const newValue = formatCurrency(e.target.value);
                    
                    e.target.value = newValue;
                    
                    // Maintain cursor position
                    const lengthDiff = newValue.length - oldValue.length;
                    const newCursorPosition = cursorPosition + lengthDiff;
                    e.target.setSelectionRange(newCursorPosition, newCursorPosition);
                });

                // Validate on blur
                input.addEventListener('blur', function(e) {
                    const numericValue = getNumericValue(e.target.value);
                    const value = parseInt(numericValue) || 0;
                    
                    if (value < 0) {
                        e.target.value = '0';
                    } else if (numericValue === '') {
                        e.target.value = '';
                    }
                });
            });

            // Handle form submission - convert formatted values to numeric
            const form = document.getElementById('salesTargetForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    currencyInputs.forEach(input => {
                        const numericValue = getNumericValue(input.value);
                        input.value = numericValue;
                    });
                });
            }
        });
    </script>
@endpush
