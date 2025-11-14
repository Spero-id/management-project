@extends('layouts.app')

@push('styles')
    <style>
        /* Custom styles for datatable */
        .datatable-wrapper .card {
            border: 1px solid #e9ecef;
        }

        .datatable-wrapper .table thead th {
            background-color: #f8f9fa;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }

        .datatable-wrapper .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .datatable-sortable {
            cursor: pointer;
            user-select: none;
        }

        .datatable-sortable:hover {
            background-color: #e9ecef;
        }

        .icon-sort {
            transition: transform 0.2s;
        }

        .datatable-pagination-info {
            font-size: 0.875rem;
            color: #6c757d;
        }

        .badge {
            font-size: 0.65em;
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 0.5rem;
        }
    </style>
@endpush

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <div class="page-pretitle">Overview</div>
            <h2 class="page-title">Prospects</h2>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <a href="" class="btn btn-success d-none d-sm-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" 
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2" />
                        <path d="M10 12l4 5" />
                        <path d="M10 17l4 -5" />
                    </svg>
                    Export
                </a>
                <a href="{{ route('prospect.createEmpty') }}" class="btn btn-primary d-none d-sm-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" 
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 5l0 14" />
                        <path d="M5 12l14 0" />
                    </svg>
                    Create Prospect
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <!-- Flash Messages -->
    @if (session('success'))
        <div class="alert  alert-important alert-success alert-dismissible fade show" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-2">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M5 12l5 5l10 -10" />
            </svg>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert  alert-important alert-danger alert-dismissible fade show" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-2">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                <path d="M12 8v4" />
                <path d="M12 16h.01" />
            </svg>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <x-datatable
                :columns="[
                    [
                        'key' => 'id',
                        'label' => 'No.',
                        'sortable' => true,
                        'render' => function($value, $row) {
                            return '<span class=\"text-secondary fw-medium\">#' . str_pad($value, 4, '0', STR_PAD_LEFT) . '</span>';
                        }
                    ],
                    [
                        'key' => 'company',
                        'label' => 'Company',
                        'sortable' => true,
                        'render' => function($value, $row) {
                            return '<a href=\"/prospect/' . $row['id'] . '\" class=\"text-reset fw-medium\">' . htmlspecialchars($value) . '</a>';
                        }
                    ],
                    [
                        'key' => 'customer_name',
                        'label' => 'Customer Name',
                        'sortable' => true,
                    ],
                    [
                        'key' => 'target_deal',
                        'label' => 'Target Deal',
                        'sortable' => true,
                        'render' => function($value, $row) {
                            return '<span class=\"badge bg-light text-dark\">' . htmlspecialchars($value) . '</span>';
                        }
                    ],
                    [
                        'key' => 'prospect_status.name',
                        'label' => 'Status',
                        'sortable' => false,
                        'render' => function($value, $row) {
                            $color = $row['prospect_status']['color'] ?? '#666';
                            $persentase = $row['prospect_status']['persentase'] ?? 0;
                            return '<span class=\"badge\" style=\"background-color: ' . htmlspecialchars($color) . '; display: inline-block; width: 12px; height: 12px; margin-right: 0.5rem;\"></span>' . htmlspecialchars($value);
                        }
                    ],
                    [
                        'key' => 'prospect_status.persentase',
                        'label' => 'Progress',
                        'sortable' => false,
                        'render' => function($value, $row) {
                            $progress = $value ?? 0;
                            return '
                                <div class=\"d-flex align-items-center gap-2\">
                                    <div class=\"progress\" style=\"min-width: 80px; flex: 1;\">
                                        <div class=\"progress-bar\" style=\"width: ' . $progress . '%\"></div>
                                    </div>
                                    <small class=\"text-secondary fw-medium text-nowrap\">' . $progress . '%</small>
                                </div>
                            ';
                        }
                    ],
                    [
                        'key' => 'created_at',
                        'label' => 'Created Date',
                        'sortable' => true,
                        'render' => function($value, $row) {
                            return '<span class=\"text-secondary\">' . date('d M Y', strtotime($value)) . '</span>';
                        }
                    ],
                    [
                        'key' => 'id',
                        'label' => 'Actions',
                        'sortable' => false,
                        'render' => function($value, $row) {
                            $persentase = $row['prospect_status']['persentase'] ?? 0;
                            $isCompleted = $persentase >= 100;
                            
                            return '
                                <div class=\"btn-group btn-group-sm\">
                                    <a href=\"/prospect/' . $value . '\" class=\"btn btn-outline-primary\" title=\"View\">
                                        <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\">
                                            <path d=\"M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0\" />
                                            <path d=\"M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6\" />
                                        </svg>
                                    </a>
                                    ' . (!$isCompleted ? '
                                        <a href=\"/prospect/' . $value . '/edit\" class=\"btn btn-outline-warning\" title=\"Edit\">
                                            <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\">
                                                <path d=\"M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1\" />
                                                <path d=\"M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z\" />
                                                <path d=\"M16 5l3 3\" />
                                            </svg>
                                        </a>
                                        <button class=\"btn btn-outline-danger delete-prospect-btn\" data-prospect-id=\"' . $value . '\" title=\"Delete\">
                                            <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\">
                                                <path d=\"M4 7l16 0\" />
                                                <path d=\"M10 11l0 6\" />
                                                <path d=\"M14 11l0 6\" />
                                                <path d=\"M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12\" />
                                                <path d=\"M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3\" />
                                            </svg>
                                        </button>
                                    ' : '
                                        <button class=\"btn btn-outline-success\" disabled title=\"Completed\">
                                            <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\">
                                                <circle cx=\"12\" cy=\"12\" r=\"10\"></circle>
                                                <path d=\"m9 12 2 2 4-4\"></path>
                                            </svg>
                                        </button>
                                    ') . '
                                </div>
                            ';
                        }
                    ]
                ]"
                endpoint="{{ route('api.prospects.index') }}"
                cardTitle="Prospects Management"
                pageLength="8"
                :searchableColumns="['company', 'customer_name', 'target_deal']"
            />
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal modal-blur fade" id="modal-delete-prospect" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                <div class="modal-status bg-danger"></div>
                <div class="modal-body text-center py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger" width="56" height="56" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 9v2m0 4v.01" />
                        <path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" />
                    </svg>
                    <h3>Are you sure?</h3>
                    <div class="text-secondary">
                        You're about to delete prospect <strong id="deleteProspectName"></strong>.
                        This action cannot be undone.
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row g-2">
                            <div class="col">
                                <button class="btn btn-light w-100" data-bs-dismiss="modal">Cancel</button>
                            </div>
                            <div class="col">
                                <form id="deleteProspectForm" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle delete buttons
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('delete-prospect-btn')) {
                    const prospectId = e.target.dataset.prospectId;
                    const modal = new bootstrap.Modal(document.getElementById('modal-delete-prospect'));
                    
                    document.getElementById('deleteProspectName').textContent = 'Prospect #' + prospectId;
                    document.getElementById('deleteProspectForm').action = '/prospect/' + prospectId;
                    
                    modal.show();
                }
            });
        });
    </script>
@endpush
