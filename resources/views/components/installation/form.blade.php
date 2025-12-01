@props([
    'route' => null,
    'quotation' => null,
    'installation' => null,
    'installationCategories' => [],
    'accommodationCategory' => [],
    'accommodationItems' => [],
    'type' => 'create',
    'totalJasaSetting' => 0,
])

<form action="{{ $route }}" method="POST" id="installationForm">
    @csrf
    @if (!$quotation->installationItems?->isEmpty())
        @method('PUT')
    @endif

    @if ($quotation)
        <input type="hidden" name="quotation_id" value="{{ $quotation->id }}">
    @endif

    <input type="hidden" name="need_accommodation" id="need_accommodation_input"
        value="{{ old('need_accommodation') ? 1 : 0 }}">
    <input type="hidden" name="form_type" value="{{ $type }}">



    <div>


        <div class="row mb-4">
            <div class="col-md-6">
                <label class="form-label">Total Quantity Item </label>
                <div class="input-group">

                    <input class="form-control" disabled
                        value="{{ \App\Helpers\CurrencyHelper::formatRupiah($quotation->items?->sum('subtotal') ?? 0) }}">
                </div>

            </div>
            <div class="col-md-6">
                <label class="form-label">Installation Percentage (%)</label>
                <div class="input-group">
                    <input type="number" name="installation_percentage" id="installationPercentage"
                        class="form-control" min="0" max="100" step="0.1"
                        value="{{ old('installation_percentage', $quotation->installation_percentage ?? $totalJasaSetting) }}">
                    <span class="input-group-text">%</span>
                </div>
                @error('installation_percentage')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Notes -->
        <div class="row mb-4">
            <div class="col-12">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3"
                    placeholder="Enter installation notes">{{ old('notes', $quotation->notes) }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>



        <!-- Installations Section -->
        <div class="row mt-5">
            <div class="col-12">
                <label class="form-label">Installations </label>

                <div class="table-responsive">
                    <table class="table table-vcenter" id="installationsTable">
                        <thead>
                            <tr>
                                <th>Installation</th>
                                <th width="120">Quantity</th>
                                <th width="150">Unit Price</th>
                                <th width="150">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="installationTableBody">


                            @php
                                $initialIndex = 1;
                                $installationPercentage = old(
                                    'installation_percentage',
                                    $quotation?->installation_percentage ?? 0,
                                );
                                $productTotal = $quotation?->total_amount ?? 0;
                                $installationTotal = $productTotal * ($installationPercentage / 100);

                                $serverRenderedCount = $installation->count();

                                $sumProportional = 0;
                                foreach ($installation as $it) {
                                    $p =
                                        isset($it->proportional) && $it->proportional !== null
                                            ? floatval($it->proportional)
                                            : 0;
                                    if ($p > 0) {
                                        $sumProportional += $p;
                                    }
                                }

                                $allocations = [];
                                if ($serverRenderedCount > 0) {
                                    if ($sumProportional > 0) {
                                        $allocated = 0;
                                        $unpropIndexes = [];
                                        foreach ($installation as $idx => $it) {
                                            $p =
                                                isset($it->proportional) && $it->proportional !== null
                                                    ? floatval($it->proportional)
                                                    : 0;
                                            if ($p > 0) {
                                                $unit = $installationTotal * ($p / 100);
                                                $allocations[$idx] = $unit;
                                                $allocated += $unit;
                                            } else {
                                                $unpropIndexes[] = $idx;
                                            }
                                        }
                                        $remaining = $installationTotal - $allocated;
                                        $perUnprop = count($unpropIndexes) > 0 ? $remaining / count($unpropIndexes) : 0;
                                        foreach ($unpropIndexes as $u) {
                                            $allocations[$u] = $perUnprop;
                                        }
                                    } else {
                                        $perItem = $installationTotal / $serverRenderedCount;
                                        foreach ($installation as $idx => $it) {
                                            $allocations[$idx] = $perItem;
                                        }
                                    }
                                }
                            @endphp

                            @foreach ($installation as $idx => $item)
                                @php
                                    $proportional =
                                        isset($item->proportional) && $item->proportional !== null
                                            ? floatval($item->proportional)
                                            : 0;
                                    $unitPrice = $item->unit_price;
                                    $quantity = $item->quantity;
                                    $subtotal = $unitPrice * $quantity;
                                @endphp
                                <tr class="installation-row"
                                    @if ($proportional > 0) data-proportional="{{ $proportional }}" @endif>
                                    <td>
                                        <select readonly name="installations[{{ $initialIndex }}][installation_id]"
                                            class="form-select installation-select" required>
                                            <option value="{{ $item->id }}" selected>
                                                {{ $item->text ?? ($item->name ?? ($item->title ?? 'Installation')) }}
                                            </option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="installations[{{ $initialIndex }}][quantity]"
                                            class="form-control installation-quantity-input"
                                            value="{{ $quantity }}" min="1" required>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input readonly type="text"
                                                name="installations[{{ $initialIndex }}][unit_price]"
                                                class="form-control installation-unit-price-input"
                                                value="{{ number_format(round($unitPrice)) }}" placeholder="0"
                                                required>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control installation-subtotal-display"
                                            value="{{ number_format(round($subtotal)) }}" readonly
                                            style="background-color: #f8f9fa;">
                                    </td>
                                </tr>
                                @php $initialIndex++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                    <script>
                        window.__serverInstallationRowCount = {{ isset($serverRenderedCount) ? $serverRenderedCount : 0 }};
                    </script>
                </div>


                <!-- Accommodation Section -->
                <div class="row mt-5 mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">Accommodation </h4>
                                <div class="form-check form-switch">
                                    <input type="checkbox" name="need_accommodation" class="form-check-input"
                                        id="accommodationToggle" @if (old('need_accommodation', $quotation?->need_accommodation)) checked @endif>

                                </div>
                            </div>

                            <div class="card-body" id="accommodationFormContainer"
                                style="@if (!old('need_accommodation', $quotation?->need_accommodation)) display: none; @endif">
                                <!-- Accommodation Details -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Wilayah / AREA</label>
                                        <input type="text" id="accommodation_wilayah" name="accommodation_wilayah"
                                            class="form-control @error('accommodation_wilayah') is-invalid @enderror"
                                            placeholder="Enter area"
                                            value="{{ old('accommodation_wilayah', $quotation?->accommodation_wilayah) }}">
                                        @error('accommodation_wilayah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Jumlah Kamar Hotel</label>
                                        <input type="number" id="accommodation_rooms" readonly
                                            name="accommodation_hotel_rooms"
                                            class="form-control readonly  @error('accommodation_hotel_rooms') is-invalid @enderror"
                                            placeholder="Enter number of rooms"
                                            value="{{ old('accommodation_hotel_rooms', $quotation?->accommodation_hotel_rooms ?? 0) }}"
                                            readonly>
                                        @error('accommodation_hotel_rooms')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Jumlah Orang</label>
                                        <input type="number" name="accommodation_people" id="accommodation_people"
                                            class="form-control @error('accommodation_people') is-invalid @enderror"
                                            placeholder="Enter number of people"
                                            value="{{ old('accommodation_people', $quotation?->accommodation_people ?? 0) }}">
                                        @error('accommodation_people')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Target Pekerjaan (Hari)</label>
                                        <input type="number" id="accommodation_target_days"
                                            name="accommodation_target_days"
                                            class="form-control @error('accommodation_target_days') is-invalid @enderror"
                                            placeholder="Enter target days"
                                            value="{{ old('accommodation_target_days', $quotation?->accommodation_target_days ?? 0) }}">
                                        @error('accommodation_target_days')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Harga Tiket Pesawat (per orang)</label>
                                        <input type="text" id="accommodation_ticket_price"
                                            name="accommodation_plane_ticket_price"
                                            class="form-control @error('accommodation_plane_ticket_price') is-invalid @enderror"
                                            placeholder="0"
                                            value="{{ old('accommodation_plane_ticket_price') ? number_format($quotation?->accommodation_plane_ticket_price ?? 0, 0, ',', '.') : number_format($quotation?->accommodation_plane_ticket_price ?? 0, 0, ',', '.') }}">
                                        @error('accommodation_plane_ticket_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Accommodation Cost Table -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label class="form-label">Accommodation Costs</label>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Description</th>
                                                        <th width="200">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>



                                                    <tr>
                                                        <td>Total Harga Hotel</td>
                                                        <td>
                                                            <input type="text" name="total_hotel_price"
                                                                id="total_hotel_price_input"
                                                                class="form-control @error('total_hotel_price') is-invalid @enderror"
                                                                value="{{ $accommodationItems->firstWhere('name', 'Total Harga Hotel')?->unit_price ?? 0 }}"
                                                                readonly>
                                                            @error('total_hotel_price')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Harga Pesawat</td>
                                                        <td>
                                                            <input type="text" name="total_flight_price"
                                                                id="flight_price_input"
                                                                class="form-control @error('total_flight_price') is-invalid @enderror"
                                                                value="{{ $accommodationItems->firstWhere('name', 'Harga Pesawat')?->unit_price ?? 0 }}"
                                                                readonly>
                                                            @error('total_flight_price')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Harga Transportasi Kendaraan</td>
                                                        <td>
                                                            <input type="text" name="total_transportation_price"
                                                                id="total_transportation_price"
                                                                class="form-control @error('total_transportation_price') is-invalid @enderror"
                                                                value="{{ $accommodationItems->firstWhere('name', 'Harga Transportasi Kendaraan')?->unit_price ?? 0 }}"
                                                                readonly>
                                                            @error('total_transportation_price')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </td>
                                                    </tr>


                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Installation & Accommodation Total Section -->
                <div class="row mt-4">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-md">Installation:</span>
                                    <span class="text-md" id="installationOnlyAmount">Rp 0</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-md">Accommodation:</span>
                                    <span class="text-md" id="accommodationOnlyAmount">Rp 0</span>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong class="text-lg">Total:</strong>
                                    <strong class="text-lg text-success" id="installationTotalAmount">Rp 0</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @error('installations')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>


    </div>

    <div class="mt-4">
        <div class="d-flex justify-content-end">

            <div>
                <button type="submit" class="btn btn-primary">Simpan Installation</button>
            </div>
        </div>
    </div>
</form>



@pushOnce('styles')
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
@endPushOnce

@pushOnce('scripts')
    <script>
        class InstallationFormManager {
            constructor() {
                this.config = {
                    productTotal: {{ $quotation->items?->sum('subtotal') ?? 0 }},
                    hotelPricePerNight: {{ $accommodationCategory[0]->price ?? 0 }},
                    transportationPricePerPerson: {{ $accommodationCategory[1]->price ?? 0 }}
                };

                this.elements = {};
                this.init();
            }

            // ===== UTILITY METHODS =====
            parseNumber(str) {
                if (str === null || str === undefined) return 0;
                const cleanStr = String(str).replace(/[^0-9.-]+/g, '');
                return parseFloat(cleanStr) || 0;
            }

            formatNumber(number) {
                try {
                    return new Intl.NumberFormat('id-ID').format(Math.round(number));
                } catch (e) {
                    return Math.round(number).toString();
                }
            }

            formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(number);
            }

            formatCurrency(amount) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(amount);
            }

            parseRupiah(rupiahString) {
                return parseFloat(String(rupiahString).replace(/[.,]/g, '')) || 0;
            }

            formatRupiahInput(input) {
                let value = input.value.replace(/[^0-9]/g, '');
                if (value) {
                    value = parseInt(value, 10);
                    input.value = this.formatRupiah(value);
                } else {
                    input.value = '';
                }
            }

            // ===== DOM HELPERS =====
            getElementById(id) {
                if (!this.elements[id]) {
                    this.elements[id] = document.getElementById(id);
                }
                return this.elements[id];
            }

            querySelector(selector) {
                return document.querySelector(selector);
            }

            debounce(func, delay = 50) {
                let timeoutId;
                return (...args) => {
                    clearTimeout(timeoutId);
                    timeoutId = setTimeout(() => func.apply(this, args), delay);
                };
            }

            // ===== ACCOMMODATION CALCULATIONS =====
            calculateHotelPrice() {
                const peopleEl = this.getElementById('accommodation_people');
                const daysEl = this.getElementById('accommodation_target_days');
                
                if (!peopleEl || !daysEl) return;

                const people = parseInt(peopleEl.value) || 0;
                const days = parseInt(daysEl.value) || 0;
                const rooms = Math.ceil(people / 2);

                // Update rooms display
                const roomInput = this.getElementById('accommodation_rooms') || 
                                 this.querySelector('input[name="accommodation_hotel_rooms"]');
                if (roomInput) roomInput.value = rooms;

                // Calculate and update hotel price
                if (rooms > 0 && days > 0) {
                    const hotelPrice = this.config.hotelPricePerNight * days * rooms;
                    const hotelInput = this.getElementById('total_hotel_price_input') || 
                                      this.querySelector('input[name="total_hotel_price"]');

                    if (hotelInput) {
                        hotelInput.value = this.formatRupiah(hotelPrice);
                    }
                }
                
                this.debouncedRecalculate();
            }

            calculateTicketPrice() {
                const peopleEl = this.getElementById('accommodation_people');
                const ticketEl = this.getElementById('accommodation_ticket_price');
                
                if (!peopleEl || !ticketEl) return;

                const people = parseInt(peopleEl.value) || 0;
                const ticketPrice = this.parseRupiah(ticketEl.value) || 0;

                if (people > 0 && ticketPrice > 0) {
                    const totalTicketPrice = ticketPrice * people * 2; // Round trip
                    const flightInput = this.getElementById('flight_price_input');
                    
                    if (flightInput) {
                        flightInput.value = this.formatRupiah(totalTicketPrice);
                    }
                }
                
                this.debouncedRecalculate();
            }

            calculateTransportationPrice() {
                const peopleEl = this.getElementById('accommodation_people');
                if (!peopleEl) return;

                const people = parseInt(peopleEl.value) || 0;

                if (people > 0) {
                    const transportationPrice = this.config.transportationPricePerPerson * people;
                    const transportationInput = this.getElementById('total_transportation_price');
                    
                    if (transportationInput) {
                        transportationInput.value = this.formatRupiah(transportationPrice);
                    }
                }
                
                this.debouncedRecalculate();
            }

            calculateAccommodationTotal() {
                const accommodationToggle = this.getElementById('accommodationToggle');
                if (!accommodationToggle || !accommodationToggle.checked) {
                    return 0;
                }

                const hotelInput = this.getElementById('total_hotel_price_input');
                const flightInput = this.getElementById('flight_price_input');
                const transportationInput = this.getElementById('total_transportation_price');

                const hotelPrice = this.parseRupiah(hotelInput?.value || '0');
                const flightPrice = this.parseRupiah(flightInput?.value || '0');
                const transportationPrice = this.parseRupiah(transportationInput?.value || '0');

                return hotelPrice + flightPrice + transportationPrice;
            }

            // ===== INSTALLATION CALCULATIONS =====
            calculateInstallationAllocations(installationTotal, rows) {
                const allocations = new Array(rows.length).fill(0);
                
                // Calculate sum of proportional values
                const sumProportional = rows.reduce((sum, row) => {
                    const proportional = parseFloat(row.dataset.proportional) || 0;
                    return sum + (proportional > 0 ? proportional : 0);
                }, 0);

                if (sumProportional > 0) {
                    // Allocate based on proportional values
                    let allocated = 0;
                    const unproportionalIndexes = [];

                    rows.forEach((row, index) => {
                        const proportional = parseFloat(row.dataset.proportional) || 0;
                        if (proportional > 0) {
                            const allocation = installationTotal * (proportional / 100);
                            allocations[index] = allocation;
                            allocated += allocation;
                        } else {
                            unproportionalIndexes.push(index);
                        }
                    });

                    // Distribute remaining amount to non-proportional items
                    const remaining = installationTotal - allocated;
                    const perUnproportional = unproportionalIndexes.length > 0 
                        ? remaining / unproportionalIndexes.length 
                        : 0;

                    unproportionalIndexes.forEach(index => {
                        allocations[index] = perUnproportional;
                    });
                } else {
                    // Equal distribution if no proportional values
                    const perItem = installationTotal / rows.length;
                    allocations.fill(perItem);
                }

                return allocations;
            }

            recalculateAll() {
                const percentageEl = this.getElementById('installationPercentage');
                const percentage = parseFloat(percentageEl?.value || 0) || 0;
                const installationTotal = this.config.productTotal * (percentage / 100);

                const rows = Array.from(document.querySelectorAll('.installation-row'));
                let displayedInstallationTotal = 0;
                
                if (rows.length > 0) {
                    const allocations = this.calculateInstallationAllocations(installationTotal, rows);

                    rows.forEach((row, index) => {
                        const qtyInput = row.querySelector('.installation-quantity-input');
                        const unitInput = row.querySelector('.installation-unit-price-input');
                        const subtotalDisplay = row.querySelector('.installation-subtotal-display');

                        const quantity = this.parseNumber(qtyInput?.value || 0);
                        const unitPrice = allocations[index] || 0;

                        if (unitInput) {
                            unitInput.value = this.formatNumber(unitPrice);
                        }

                        const subtotal = unitPrice * quantity;
                        if (subtotalDisplay) {
                            subtotalDisplay.value = this.formatNumber(subtotal);
                        }
                        
                        displayedInstallationTotal += subtotal;
                    });
                }

                // Update totals display
                this.updateTotalDisplay(displayedInstallationTotal);
            }

            updateTotalDisplay(installationTotal) {
                const accommodationTotal = this.calculateAccommodationTotal();
                const grandTotal = installationTotal + accommodationTotal;

                const installOnlyEl = this.getElementById('installationOnlyAmount');
                const accommodationOnlyEl = this.getElementById('accommodationOnlyAmount');
                const grandTotalEl = this.getElementById('installationTotalAmount');
                
                if (installOnlyEl) {
                    installOnlyEl.textContent = `Rp ${this.formatNumber(installationTotal)}`;
                }
                if (accommodationOnlyEl) {
                    accommodationOnlyEl.textContent = `Rp ${this.formatNumber(accommodationTotal)}`;
                }
                if (grandTotalEl) {
                    grandTotalEl.textContent = `Rp ${this.formatNumber(grandTotal)}`;
                }
            }

            // ===== EVENT LISTENERS =====
            attachInstallationListeners() {
                const percentageEl = this.getElementById('installationPercentage');
                if (percentageEl) {
                    percentageEl.addEventListener('input', () => this.recalculateAll());
                }

                // Quantity input listeners
                document.addEventListener('input', (e) => {
                    if (e.target?.classList?.contains('installation-quantity-input')) {
                        this.recalculateAll();
                    }
                });
            }

            attachAccommodationListeners() {
                const elements = {
                    targetDays: this.getElementById('accommodation_target_days'),
                    people: this.getElementById('accommodation_people'),
                    ticketPrice: this.getElementById('accommodation_ticket_price'),
                    toggle: this.getElementById('accommodationToggle'),
                    container: this.getElementById('accommodationFormContainer'),
                    hiddenInput: this.getElementById('need_accommodation_input')
                };

                // Target days listener
                if (elements.targetDays) {
                    elements.targetDays.addEventListener('input', () => this.calculateHotelPrice());
                }

                // People count listener
                if (elements.people) {
                    elements.people.addEventListener('input', () => {
                        this.calculateTicketPrice();
                        this.calculateTransportationPrice();
                        this.calculateHotelPrice();
                    });
                }

                // Ticket price listener with formatting
                if (elements.ticketPrice) {
                    elements.ticketPrice.addEventListener('input', () => {
                        this.formatRupiahInput(elements.ticketPrice);
                        this.calculateTicketPrice();
                    });

                    // Format on load
                    if (elements.ticketPrice.value) {
                        this.formatRupiahInput(elements.ticketPrice);
                    }
                }

                // Accommodation toggle listener
                if (elements.toggle && elements.container && elements.hiddenInput) {
                    elements.toggle.addEventListener('change', () => {
                        const isChecked = elements.toggle.checked;
                        elements.container.style.display = isChecked ? 'block' : 'none';
                        elements.hiddenInput.value = isChecked ? '1' : '0';
                        this.recalculateAll();
                    });
                }
            }

            // ===== INITIALIZATION =====
            init() {
                // Create debounced recalculate function
                this.debouncedRecalculate = this.debounce(() => this.recalculateAll(), 50);

                document.addEventListener('DOMContentLoaded', () => {
                    this.attachInstallationListeners();
                    this.attachAccommodationListeners();
                    this.recalculateAll();
                });
            }
        }

        // Initialize the form manager
        new InstallationFormManager();
    </script>
    </script>
@endPushOnce
