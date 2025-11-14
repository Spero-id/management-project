@extends('layouts.app')

@push('styles')
    <style>
        /* Dashboard styles with white text */
        .sales-team-selector {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            background: white;
            height: 450px;
            display: flex;
            flex-direction: column;
        }

        .sales-team-selector>div:first-child {
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

        .team-button {
            background: white;
            border: 2px solid #e5e7eb;
            color: #374151;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .team-button.active {
            background: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }

        .chart-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            height: 450px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        .chart-container canvas {
            height: 350px !important;
            width: 100% !important;
        }

        .performance-table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        .performance-table table {
            color: #374151;
            margin-bottom: 0;
        }

        .performance-table th {
            background: #f8fafc;
            border: none;
            padding: 15px;
            color: #374151;
        }

        .performance-table td {
            border-color: #e5e7eb;
            padding: 15px;
        }

        .completion-bar {
            height: 20px;
            background: #f1f5f9;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }

        .completion-fill {
            height: 100%;
            border-radius: 10px;
            transition: width 0.3s ease;
        }

        .completion-yellow {
            background: #f59e0b;
        }

        .completion-blue {
            background: #3b82f6;
        }

        .completion-green {
            background: #10b981;
        }

        .chart-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
            color: #374151;
        }

        .opacity-50 {
            opacity: 0.5;
            transition: opacity 0.3s ease;
        }

        /* Custom styles for the prospects table */
        .card-table {
            margin-bottom: 0;
        }

        .table-selectable .table-selectable-check:checked+.table-selectable-check-indicator {
            background-color: var(--tblr-primary);
            border-color: var(--tblr-primary);
        }

        .icon-sm {
            width: 1rem;
            height: 1rem;
        }

        .badge {
            font-size: 0.65em;
        }

        .dropdown-toggle::after {
            margin-left: 0.5em;
        }

        /* Report panel styles */
        .sales-report {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 30px;
            background: white;
            height: 450px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            /* allow vertical scrolling when content overflows */
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            /* smooth scrolling on mobile */
        }

        /* Slightly smaller heading so the panel feels compact */
        .sales-report h5 {
            text-align: center;
            font-weight: 700;
            margin-bottom: 6px;
            font-size: 0.95rem;
        }

        /* Make each report-row a vertical list: label above value */
        .sales-report .report-row {
            display: flex;
            flex-direction: column;
            /* stack label above value */
            justify-content: flex-start;
            align-items: flex-start;
            /* left align for readability */
            padding: 8px 0;
            border-bottom: 1px dashed #f1f5f9;
            gap: 4px;
        }

        .sales-report .report-row:last-child {
            border-bottom: none;
        }

        /* Label sits above the value, smaller and muted */
        .sales-report .report-label {
            color: #6b7280;
            /* slightly muted */
            font-size: 0.75rem;
            /* smaller label */
            font-weight: 600;
            white-space: normal;
            overflow: visible;
            text-overflow: initial;
            max-width: 100%;
        }

        /* Value below label: stand out but still compact */
        .sales-report .report-value {
            font-weight: 800;
            color: #111827;
            font-size: 0.95rem;
            /* slightly larger than label */
            white-space: normal;
            overflow: visible;
            text-overflow: initial;
            max-width: 100%;
            text-align: left;
        }

        /* Status Header Cards Styles */
        .status-header {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .status-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 2px solid #e5e7eb;
        }

        .status-card h6 {
            font-size: 0.85rem;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }

        .status-value {
            font-size: 2.5rem;
            font-weight: bold;
            color: #111827;
        }

        /* Tabs Section */
        .tabs-section {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 0;
        }

        .tab-btn {
            background: none;
            border: none;
            padding: 12px 20px;
            color: #6b7280;
            font-weight: 600;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .tab-btn:hover {
            color: #3b82f6;
        }

        .tab-btn.active {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
        }

        /* Work Table Section */
        .work-table-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .work-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .work-table thead {
            background: #f8fafc;
            border-bottom: 2px solid #e5e7eb;
        }

        .work-table th {
            padding: 15px;
            text-align: left;
            color: #374151;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
        }

        .work-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e5e7eb;
            color: #374151;
            font-size: 0.9rem;
        }

        .work-table tbody tr:hover {
            background: #f9fafb;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .bobot-column {
            text-align: center;
            font-weight: 600;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            padding-bottom: 20px;
        }

        .action-btn {
            padding: 8px 16px;
            border: 2px solid #e5e7eb;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .action-btn:hover {
            border-color: #3b82f6;
            color: #3b82f6;
        }

        .action-btn.primary {
            background: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }

        .action-btn.primary:hover {
            background: #2563eb;
            border-color: #2563eb;
        }
    </style>
@endpush

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <!-- Page pre-title -->
            <div class="page-pretitle">Project Dashboard</div>
            <h2 class="page-title">Dashboard</h2>
        </div>
        <!-- Page title actions -->

    </div>
@endsection

@section('content')
    <div class="container-xl">
        <!-- Status Header Cards -->

        <div class="row">
            <!-- Left Column: Sales Team Selector -->
            <div class="col-lg-4 mb-4">
                <div class="sales-team-selector d-flex flex-column justify-content-between">
                    <!-- Top: title and project list -->
                    <div>
                        <h5 class="mb-3 fw-bold">ON GOING PROJECT</h5>
                        <div class="list-group list-group-flush">
                            @foreach ($projects as $item)
                                <a href="?project_id={{ $item->id }}"
                                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0 py-2 {{ $item->id == $selectedProject->id ? 'active' : '' }}">
                                    <span class="fw-bold">{{ $item->project_name }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="icon">
                                        <path d="M9 6l6 6l-6 6"></path>
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Status Cards and Performance Chart -->
            <div class="col-lg-8 mb-4">
                <div class="status-header">
                    <div class="status-card">
                        <h6>Persentase Progress Project</h6>
                        <div class="status-value">70%</div>
                    </div>
                    <div class="status-card">
                        <h6>Status Barang</h6>
                        <div class="status-value">70%</div>
                    </div>
                    <div class="status-card">
                        <h6>Deadline</h6>
                        <div class="status-value">
                            @php
                                use Carbon\Carbon;
                                $deadline = $selectedProject->created_at
                                    ?->copy()
                                    ->addDays((int) ($selectedProject->execution_time ?? 0));
                                $diffDays = $deadline ? round(Carbon::now()->diffInDays($deadline)) : 0;
                                echo $diffDays . ' hari';
                            @endphp
                        </div>
                    </div>
                </div>

                <!-- Navigation Tabs -->
                <div class="tabs-section">
                    <button class="tab-btn active" onclick="switchTab('list-works')">List of Works</button>
                    <button class="tab-btn" onclick="switchTab('status-barang')">Status Barang</button>
                    <button class="tab-btn" onclick="switchTab('weekly-meeting')">Weekly Meeting</button>
                </div>

                <!-- Work Table Section -->
                <div class="work-table-section" id="list-works">
                    <x-project.wbs-list :project="$selectedProject" :wbsItems="$selectedProject->wbsItems" />
                </div>

                <!-- Tab Content Placeholders -->
                <div class="work-table-section" id="status-barang" style="display: none;">
                    <x-project.delivery-table :equipment="$equipment ?? []" />
                </div>

                <div class="work-table-section" id="weekly-meeting" style="display: none;">
                    <x-project.weekly-meeting />
                </div>

            </div>
        </div>


    </div>


    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        let monthlyChart;

        function switchTab(tabId) {
            // Hide all tab contents
            document.getElementById('list-works').style.display = 'none';
            document.getElementById('status-barang').style.display = 'none';
            document.getElementById('weekly-meeting').style.display = 'none';

            // Show selected tab
            document.getElementById(tabId).style.display = 'block';

            // Update active button
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
        }

        $(document).ready(function() {
            // Initialize Chart with dummy data if no data from controller
            const monthlyData = @json($monthlyData ?? []);

            // Use dummy data if controller returns empty
            const chartData = {
                omset: monthlyData.omset && monthlyData.omset.length > 0 ? monthlyData.omset : [800000000,
                    1200000000, 950000000, 1500000000, 1100000000, 1300000000,
                    1400000000, 1250000000, 1600000000, 1350000000, 1450000000, 1200000000
                ],
                gross_profit: monthlyData.gross_profit && monthlyData.gross_profit.length > 0 ? monthlyData
                    .gross_profit : [480000000, 720000000, 570000000, 900000000, 660000000, 780000000,
                        840000000, 750000000, 960000000, 810000000, 870000000, 720000000
                    ],
                target_completion: monthlyData.target_completion && monthlyData.target_completion.length > 0 ?
                    monthlyData.target_completion : [65, 72, 58, 85, 70, 78, 82, 75, 88, 80, 85, 72]
            };

            initializeChart(chartData);

            // Handle delete project modal
            $(document).on('click', '.delete-project-btn', function() {
                var projectId = $(this).data('project-id');
                var projectName = $(this).data('project-name');

                // Update modal content
                $('#deleteProjectName').text(projectName);

                // Update form action URL
                var baseUrl = '{{ url('/project') }}';
                $('#deleteProjectForm').attr('action', baseUrl + '/' + projectId);
            });
        });

        // Initialize chart function
        function initializeChart(monthlyData) {
            const ctx = document.getElementById('monthlyChart').getContext('2d');

            // Destroy existing chart if it exists
            if (monthlyChart) {
                monthlyChart.destroy();
            }

            monthlyChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI', 'JULI', 'AGUSTUS', 'SEPTEMBER',
                        'OKTOBER', 'NOVEMBER', 'DESEMBER'
                    ],
                    datasets: [{
                            label: 'OMSET',
                            data: monthlyData.omset,
                            backgroundColor: '#2dd4bf',
                            borderColor: '#2dd4bf',
                            borderWidth: 1
                        },
                        {
                            label: 'GROSS PROFIT',
                            data: monthlyData.gross_profit,
                            backgroundColor: '#3b82f6',
                            borderColor: '#3b82f6',
                            borderWidth: 1
                        },
                        {
                            label: 'TARGET',
                            data: monthlyData.target_completion.map(rate => rate * 150000),
                            type: 'line',
                            borderColor: '#f59e0b',
                            backgroundColor: 'transparent',
                            borderWidth: 3,
                            pointBackgroundColor: '#f59e0b',
                            pointBorderColor: '#f59e0b',
                            pointRadius: 5,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    aspectRatio: 2,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#374151'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#374151',
                                callback: function(value) {
                                    return (value / 1000000) + 'M';
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#374151'
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: false,
                            position: 'right',
                            max: 100 * 150000,
                            grid: {
                                drawOnChartArea: false,
                            }
                        }
                    }
                }
            });
        }
    </script>
@endpush
