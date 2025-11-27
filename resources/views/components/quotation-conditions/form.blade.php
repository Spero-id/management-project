@props(['route' => null, 'prospect' => null, 'quotation' => null, 'type' => null])

<form action="{{ $route }}" method="POST" id="quotationForm">
    @csrf
    @if ($quotation)
        @method('PUT')
    @endif
    @if ($prospect)
        <input type="hidden" name="prospect_id" value="{{ $prospect->id }}">
    @endif

    <input type="hidden" name="form_type" value="{{ $type }}">


    <div>
        <!-- Quotation Conditions Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <label class="form-label mb-0">Quotation conditions</label>
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="addConditionRow()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M12 5v14" />
                            <path d="M5 12h14" />
                        </svg>
                        Add condition
                    </button>
                </div>

                <div id="conditionsContainer" class="space-y-2">
                    <!-- Conditions will be dynamically added here -->
                </div>

                @error('conditions')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>

    </div>


    <div class=" mt-4 d-flex justify-content-end">
        <div>
            <button type="submit" class="btn btn-primary">
                Save Quotation Conditions
            </button>
        </div>
    </div>
</form>



@pushOnce('styles')
    <style>
        .space-y-2 > * + * {
            margin-top: 0.5rem;
        }

        .condition-row {
            display: flex;
            gap: 0.5rem;
            align-items: start;
        }

        .condition-row .form-control {
            flex: 1;
        }

        .condition-row .btn-icon {
            width: 38px;
            height: 38px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endPushOnce

@pushOnce('scripts')
    <script>
        // ===== QUOTATION CONDITIONS MANAGEMENT =====
        let conditionRowIndex = 0;
        
        @php
            // Jika quotation memiliki prospect dengan quotation_conditions (update mode)
            $prospectConditions = null;
            if ($quotation && $quotation->prospect && $quotation->prospect->quotation_conditions) {
                $prospectConditions = json_decode($quotation->prospect->quotation_conditions, true);
            }
            
            // Jika tidak ada data di prospect, gunakan master data (create mode)
            $masterConditions = \App\Models\QuotationCondition::all()->pluck('condition')->toArray();
        @endphp
        
        let defaultConditions = @json($prospectConditions ?? $masterConditions);

        function initializeConditions() {
            const container = document.getElementById('conditionsContainer');
            container.innerHTML = '';

            if (defaultConditions && defaultConditions.length > 0) {
                defaultConditions.forEach(condition => {
                    addConditionRow(condition);
                });
            } else {
                addConditionRow();
            }
        }

        function addConditionRow(conditionText = '') {
            const container = document.getElementById('conditionsContainer');
            const rowDiv = document.createElement('div');
            rowDiv.className = 'condition-row';
            rowDiv.setAttribute('data-condition-index', conditionRowIndex);

            rowDiv.innerHTML = `
                <input type="text" 
                       name="conditions[${conditionRowIndex}]" 
                       class="form-control" 
                       placeholder="Enter quotation condition"
                       value="${conditionText}">
                <button type="button" 
                        class="btn btn-outline-danger btn-icon" 
                        onclick="removeConditionRow(this)"
                        title="Remove condition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" 
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>
            `;

            container.appendChild(rowDiv);
            conditionRowIndex++;
            updateConditionRemoveButtons();
        }

        function removeConditionRow(button) {
            const row = button.closest('.condition-row');
            row.remove();
            updateConditionRemoveButtons();
        }

        function updateConditionRemoveButtons() {
            const rows = document.querySelectorAll('.condition-row');
            rows.forEach((row, index) => {
                const removeButton = row.querySelector('.btn-outline-danger');
                removeButton.disabled = rows.length === 1;
            });
        }

        $(document).ready(function() {
            // Initialize quotation conditions
            initializeConditions();
        });
    </script>
@endPushOnce
