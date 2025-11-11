@extends('layouts.app')

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <!-- Page pre-title -->
            <div class="page-pretitle">Edit</div>
            <h2 class="page-title">Quotation {{ $quotation->quotation_number }}</h2>
        </div>
        <!-- Page title actions -->
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <a href="{{ route('quotation.show', $quotation->id) }}" class="btn btn-outline-light">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-2">
                        <path d="M9 14l-4 -4l4 -4" />
                        <path d="M5 10h11a4 4 0 1 1 0 8h-1" />
                    </svg>
                    Back to Quotation
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
   <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
              
                <li class="nav-item">
                    <a href="#quotation" class="nav-link" data-bs-toggle="tab">Quotation</a>
                </li>
                <li class="nav-item">
                    <a href="#installation" class="nav-link @if (!$quotation) disabled @endif"
                        data-bs-toggle="tab"
                        @if (!$quotation) onclick="return false;" style="cursor: not-allowed; opacity: 0.5;" title="Create a quotation first" @endif>
                        Installation
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
           
                <div class="tab-pane" id="quotation">
                    <div>
                        <x-quotation.form :route="route('quotation.update', $quotation->id)" :prospect="$prospect" :quotation="$quotation" />
                    </div>
                </div>
                <div class="tab-pane" id="installation">
                    <div>
                        @php
                            $installationRoute =
                                $quotation->installationItems?->count() > 0
                                    ? route('installation.update', $quotation->id)
                                    : route('installation.store');

                        @endphp
                        <x-installation.form :route="$installationRoute" :quotation="$quotation" :installation="$quotation->installationItems" :installation-categories="$installationCategories"
                            :accommodationCategory="$accommodationCategory" :accommodationItems="$quotation->accommodationItems" />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .max-height-400 {
            max-height: 400px;
        }

        .product-checkbox {
            cursor: pointer;
            transform: scale(1.1);
        }

        .product-list-item {
            border: none !important;
            border-bottom: 1px solid #e9ecef !important;
            padding: 1rem 1.25rem !important;
            transition: all 0.2s ease-in-out;
        }

        .product-list-item:last-child {
            border-bottom: none !important;
        }

        .product-info {
            padding-left: 0.5rem;
        }

        .product-info .fw-semibold {
            font-size: 0.95rem;
            line-height: 1.3;
        }

        .product-info .small {
            font-size: 0.8rem;
            line-height: 1.2;
        }

        .product-selection-modal .modal-body {
            padding: 1.5rem;
        }

        #productsList {
            border-radius: 0.5rem;
            overflow: hidden;
            border: 1px solid #e9ecef;
        }

        #productsList .list-group {
            margin-bottom: 0;
        }

        .badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
        }

        .product-checkbox:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        /* Loading and empty states */
        #productLoadingIndicator .product-list-item,
        #noProductsFound .product-list-item {
            min-height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e9ecef;
            border-radius: 0.5rem;
            background-color: #f8f9fa;
        }

        #productLoadingIndicator .spinner-border {
            width: 2rem;
            height: 2rem;
        }

        /* Search input styling */
        #productSearchInput {
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            border: 2px solid #e9ecef;
            transition: border-color 0.2s ease-in-out;
        }

        #productSearchInput:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
        }

        #searchProductBtn {
            border-radius: 0 0.5rem 0.5rem 0;
            border: 2px solid #e9ecef;
            border-left: none;
        }

        /* Modal improvements */
        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
        }

        .modal-footer {
            background-color: #f8f9fa;
            border-top: 2px solid #e9ecef;
            padding: 1rem 1.5rem;
        }

        #selectedProductsCount {
            font-weight: 500;
            color: #6c757d;
        }

        /* Already added products styling */
        .product-list-item.bg-light {
            background-color: #f8f9fa !important;
            border-left: 3px solid #198754 !important;
        }

        .product-list-item.bg-light .product-checkbox:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .product-list-item.bg-light .product-info {
            opacity: 0.8;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const accommodationSwitch = document.getElementById('accommodationSwitch');
            const accommodationDetails = document.getElementById('accommodationDetails');
            const accommodationCostTable = document.getElementById('accommodationCostTable');

            function toggleAccommodation(checked) {
                if (!accommodationDetails || !accommodationCostTable) return;

                if (checked) {
                    accommodationDetails.classList.remove('d-none');
                    accommodationCostTable.classList.remove('d-none');
                } else {
                    accommodationDetails.classList.add('d-none');
                    accommodationCostTable.classList.add('d-none');
                }
            }

            if (accommodationSwitch) {
                toggleAccommodation(accommodationSwitch.checked);
                accommodationSwitch.addEventListener('change', function() {
                    toggleAccommodation(this.checked);
                });
            }
        });


        document.getElementById('accommodation_target_days').addEventListener('input', calculateHotelPrice);
        document.getElementById('accommodation_people').addEventListener('input', function() {
            calculateTicketPrice();
            calculateTransportationPrice();
            calculateHotelPrice();
        });
        document.getElementById('accommodation_ticket_price').addEventListener('input', calculateTicketPrice);





        function calculateHotelPrice() {
            console.log('Calculating Hotel Price...');
            const people = parseInt(document.getElementById('accommodation_people').value) || 0;
            const rooms = Math.ceil(people / 2);
            const days = parseInt(document.getElementById('accommodation_target_days').value) || 0;

            const roomInput = document.querySelector('input[name="accommodation_hotel_rooms"]');
            if (roomInput) roomInput.value = rooms;

            if (rooms > 0 && days > 0) {
                const hotelPrice = {{ $accommodationCategory[0]->price ?? 0 }} * days * rooms;
                console.log('Calculated Hotel Price:', hotelPrice);

                const hotelInput = document.querySelector('input[name="total_hotel_price"]');

                if (hotelInput) hotelInput.value = formatRupiah(hotelPrice);
            }
        }


        function calculateTicketPrice() {
            const people = parseInt(document.getElementById('accommodation_people').value) || 0;
            const ticket = parseInt(document.getElementById('accommodation_ticket_price').value) || 0;

            if (people > 0 && ticket > 0) {
                const TicketPrice = ticket * people * 2;

                const flightInput = document.querySelector('input[name="total_flight_price"]');
                if (flightInput) flightInput.value = formatRupiah(TicketPrice);
            }
        }


        function calculateTransportationPrice() {
            const people = parseInt(document.getElementById('accommodation_people').value) || 0;

            if (people > 0) {
                const transportationPrice = {{ $accommodationCategory[1]->price ?? 0 }} * people;

                const transportationInput = document.querySelector('input[name="total_transportation_price"]');
                if (transportationInput) transportationInput.value = formatRupiah(transportationPrice);
            }
        }

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(number);
        }




        // ================= Instalation ====================

        document.getElementById('installationPercentage').addEventListener('input', recalculateInstalationUnitPrice);


        function recalculateInstalationUnitPrice() {

            // Get products total (remove non-digit chars)
            const totalAmountEl = document.getElementById('totalAmount');
            let productsTotal = 0;
            if (totalAmountEl) {
                const raw = totalAmountEl.innerText || '';
                const digits = raw.replace(/[^0-9]/g, '');
                productsTotal = parseInt(digits || '0', 10);
            }

            const percentageInput = document.getElementById('installationPercentage');
            const percentage = parseFloat(percentageInput ? percentageInput.value : 0) || 0;

            // total installation budget to distribute
            const totalInstallationBudget = Math.round(productsTotal * (percentage / 100));

            const rows = Array.from(document.querySelectorAll('.installation-row'));
            if (rows.length === 0) return;

            // collect proportional weights
            let totalProportional = 0;
            const rowInfos = rows.map(row => {
                const propAttr = row.dataset.proportional;
                const proportional = propAttr !== undefined && propAttr !== null && propAttr !== '' ? parseFloat(propAttr) : 0;
                totalProportional += (isNaN(proportional) ? 0 : proportional);
                return { row, proportional: (isNaN(proportional) ? 0 : proportional) };
            });

            // if totalProportional is zero, distribute equally
            if (totalProportional === 0) {
                totalProportional = rows.length;
                rowInfos.forEach(r => r.proportional = 1);
            }

            let allocatedSum = 0;

            rowInfos.forEach((info, idx) => {
                const { row, proportional } = info;

                const qtyInput = row.querySelector('.installation-quantity-input');
                const qty = Math.max(1, parseFloat(qtyInput ? qtyInput.value : 1) || 1);

                // allocate amount based on proportion
                // for last row, use remaining to avoid rounding loss
                let allocated = Math.round(totalInstallationBudget * (proportional / totalProportional));
                if (idx === rowInfos.length - 1) {
                    allocated = totalInstallationBudget - allocatedSum;
                }

                allocatedSum += allocated;

                const unitPrice = Math.round(allocated / qty);

                const unitPriceInput = row.querySelector('.installation-unit-price-input');
                if (unitPriceInput) unitPriceInput.value = formatRupiah(unitPrice);

                const subtotalDisplay = row.querySelector('.installation-subtotal-display');
                if (subtotalDisplay) subtotalDisplay.value = formatRupiah(allocated);
            });

            // update installation total amount display
            const installationTotalEl = document.getElementById('installationTotalAmount');
            if (installationTotalEl) {
                installationTotalEl.innerText = 'Rp ' + formatRupiah(allocatedSum);
            }
        }

        // Run once to initialize values on page load
        try {
            recalculateInstalationUnitPrice();
        } catch (e) {
            // silent
            console.error(e);
        }
    </script>
@endpush
