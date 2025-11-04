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

        .completion-yellow { background: #f59e0b; }
        .completion-blue { background: #3b82f6; }
        .completion-green { background: #10b981; }

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
    </style>
@endpush

@section('header')
    <div class="row g-2 align-items-center">
        <div class="col">
            <!-- Page pre-title -->
            <div class="page-pretitle">Sales Dashboard</div>
            <h2 class="page-title">Performance Analytics</h2>
        </div>
        <!-- Page title actions -->
       
    </div>
@endsection

@section('content')
    <div class="container-xl">
        <!-- Sales Team Selector and Chart Section -->
        <div class="row mb-4">
            <!-- Sales Team Selector -->
            <div class="col-lg-4">
                <div class="sales-team-selector">
                    <h3 class="mb-3">SALES TEAM</h3>
                    <div class="d-flex flex-wrap" style="max-height: 330px; overflow-y: auto; gap: .5rem;">
                        <button class="team-button active" data-team="all">
                            ALL TEAMS
                        </button>
                        @forelse($salesTeams as $team)
                            <button class="team-button" data-team="{{ $team['id'] }}">
                                {{ $team['name'] }}
                            </button>
                        @empty
                            <p class="text-muted">No sales team members found</p>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Monthly Performance Chart -->
            <div class="col-lg-8">
                <div class="chart-container">
                    <h4 class="chart-title">Performance Data for ALL TEAMS</h4>
                    <canvas id="monthlyChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Prospects Table -->
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Prospects</h3>
                    </div>
                    <div class="card-body border-bottom py-3">
                        <div class="d-flex">
                            <div class="text-secondary">
                                Show
                                <div class="mx-2 d-inline-block">
                                    <input type="text" id="pageLength" class="form-control form-control-sm"
                                        value="8" size="3" aria-label="Prospects count">
                                </div>
                                entries
                            </div>
                            <div class="ms-auto text-secondary">
                                Search:
                                <div class="ms-2 d-inline-block">
                                    <input type="text" id="customSearch" class="form-control form-control-sm"
                                        aria-label="Search Prospect">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="example"
                                class="table table-selectable card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th class="w-1">
                                            No.
                                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-up -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-sm icon-thick icon-2">
                                                <path d="M6 15l6 -6l6 6"></path>
                                            </svg>
                                        </th>
                                        <th>Company</th>
                                        <th>Customer Name</th>
                                        <th>No Quotation</th>
                                        <th>Target Deal</th>
                                        <th>Status</th>
                                        <th>Progress</th>
                                        <th>Create Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($prospects as $index => $prospect)
                                        <tr>
                                            <td><span class="text-secondary">{{ $index + 1 }}</span></td>
                                            <td><a href="#" class="text-reset"
                                                    tabindex="-1">{{ $prospect->company }}</a>
                                            </td>
                                            <td>{{ $prospect->customer_name }}</td>
                                            <td>
                                                @if ($prospect->quotations->isNotEmpty())
                                                    <ul class="list-unstyled mb-0">
                                                        @foreach ($prospect->quotations as $quotation)
                                                            <li>
                                                                <a href="{{ route('quotation.show', $quotation->id) }}"
                                                                    class="text-primary text-decoration-none"
                                                                    title="View quotation details">
                                                                    {{ $quotation->quotation_number }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <span class="text-muted">No Quotation</span>
                                                @endif
                                            </td>
                                            <td>{{ $prospect->target_deal }}</td>
                                            <td>
                                                <span class="badge me-1"
                                                    style="background-color: {{ $prospect->prospectStatus->color }};"></span>
                                                {{ $prospect->prospectStatus->name }}
                                            </td>
                                            <td>
                                                @php
                                                    $progress = $prospect->prospectStatus->persentage ?? 0;
                                                @endphp
                                                <div class="d-flex align-items-center">
                                                    <div class="progress progress-md me-2" style="flex: 1; min-width: 80px;">
                                                        <div class="progress-bar" style="width: {{ $progress }}%"
                                                            role="progressbar" aria-valuenow="{{ $progress }}"
                                                            aria-valuemin="0" aria-valuemax="100">
                                                            <span class="visually-hidden">{{ $progress }}% Complete</span>
                                                        </div>
                                                    </div>
                                                    <small class="text-secondary fw-medium">{{ $progress }}%</small>
                                                </div>
                                            </td>
                                            <td>{{ $prospect->created_at->format('Y-m-d') }}</td>
                                         
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row g-2 justify-content-center justify-content-sm-between">
                            <div class="col-auto d-flex align-items-center">
                                <p id="tableInfo" class="m-0 text-secondary">Showing <strong>1 to 8</strong> of <strong>8
                                        entries</strong></p>
                            </div>
                            <div class="col-auto">
                                <ul id="tablePagination" class="pagination m-0 ms-auto">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-left -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                                <path d="M15 6l-6 6l6 6"></path>
                                            </svg>
                                        </a>
                                    </li>
                                    <li class="page-item active">
                                        <a class="page-link" href="#">1</a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">
                                            <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-right -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                                <path d="M9 6l6 6l-6 6"></path>
                                            </svg>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal modal-blur fade" id="modal-delete-prospect" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="modal-status bg-danger"></div>
                    <div class="modal-body text-center py-4">
                        <!-- Download SVG icon from http://tabler.io/icons/icon/alert-triangle -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 9v2m0 4v.01" />
                            <path
                                d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" />
                        </svg>
                        <h3>Apakah Anda yakin?</h3>
                        <div class="text-secondary">
                            Anda akan menghapus prospect <strong id="deleteProspectName"></strong>.
                            Tindakan ini tidak dapat dibatalkan dan akan menghapus semua file terkait.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="w-100">
                            <div class="row">
                                <div class="col">
                                    <button type="button" class="btn w-100" data-bs-dismiss="modal">
                                        Batal
                                    </button>
                                </div>
                                <div class="col">
                                    <form id="deleteProspectForm" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100">
                                            Ya, hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
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
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>

    <script>
        let monthlyChart;
        let prospectsTable;
        
        $(document).ready(function() {
            // Initialize Chart with data from controller
            const initialMonthlyData = @json($monthlyData);
            initializeChart(initialMonthlyData);

            // Initialize DataTable
            prospectsTable = $('#example').DataTable({
                "searching": true,
                "dom": 't', // Only show table, hide default controls
                "pageLength": 8,
                "lengthChange": false,
                "info": false, // We'll handle info manually
                "ordering": true,
                "responsive": true,
                "paging": true, // Enable DataTable's pagination
                "drawCallback": function(settings) {
                    updateTableInfo();
                    updatePagination();
                }
            });

            // Connect custom search input to DataTable search
            $('#customSearch').on('keyup', function() {
                console.log(this.value);
                prospectsTable.search(this.value).draw();
            });

            // Optional: Clear search when input is empty
            $('#customSearch').on('search', function() {
                if (this.value === '') {
                    prospectsTable.search('').draw();
                }
            });

            // Page length functionality
            $('#pageLength').on('keyup change', function() {
                var length = parseInt(this.value);
                if (!isNaN(length) && length > 0) {
                    prospectsTable.page.len(length).draw();
                }
            });

            // Handle delete prospect modal
            $(document).on('click', '.delete-prospect-btn', function() {
                var prospectId = $(this).data('prospect-id');
                var prospectName = $(this).data('prospect-name');

                // Update modal content
                $('#deleteProspectName').text(prospectName);

                // Update form action URL - build the URL properly
                var baseUrl = '{{ url('/prospect') }}';
                $('#deleteProspectForm').attr('action', baseUrl + '/' + prospectId);
            });

            // Sales team selection
            $('.team-button').click(function() {
                $('.team-button').removeClass('active');
                $(this).addClass('active');
                
                var selectedTeam = $(this).data('team');
                console.log('Selected team:', selectedTeam);
                
                // Show loading state
                $('.chart-container').addClass('opacity-50');
                $('.performance-table').addClass('opacity-50');
                $('.table-responsive').addClass('opacity-50');
                
                // Call AJAX to update data based on selected team
                updateDashboardData(selectedTeam);
            });
        });

        // Functions for updating table info and pagination
        function updateTableInfo() {
            // Update the table information display
            // This function should be implemented based on your needs
        }

        function updatePagination() {
            // Update pagination controls
            // This function should be implemented based on your needs
        }

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
                    labels: ['JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI', 'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOVEMBER', 'DESEMBER'],
                    datasets: [
                        {
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
                            data: monthlyData.target_completion.map(rate => rate * 150000), // Scale for visualization
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

        // Function to update dashboard data via AJAX
        function updateDashboardData(team) {
            $.ajax({
                url: '/dashboard/team/' + team,
                method: 'GET',
                success: function(response) {
                    console.log('Received data:', response);
                    
                    // Update chart with new data
                    initializeChart(response.monthlyData);
                    
                    // Update performance table
                    updatePerformanceTable(response.performanceData);
                    
                    // Update prospects table
                    updateProspectsTable(response.prospects || []);
                    
                    // Update chart title to show selected team
                    $('.chart-title').text('Performance Data for ' + response.teamName);
                    
                    // Remove loading state
                    $('.chart-container').removeClass('opacity-50');
                    $('.performance-table').removeClass('opacity-50');
                    $('.table-responsive').removeClass('opacity-50');
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching team data:', error);
                    alert('Error loading team data. Please try again.');
                    
                    // Remove loading state
                    $('.chart-container').removeClass('opacity-50');
                    $('.performance-table').removeClass('opacity-50');
                    $('.table-responsive').removeClass('opacity-50');
                }
            });
        }

        // Function to update performance table
        function updatePerformanceTable(performanceData) {
            const tbody = $('.performance-table tbody');
            tbody.empty();
            
            if (performanceData.length === 0) {
                tbody.append(`
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="fa fa-chart-bar mb-3" style="font-size: 3rem;"></i>
                                <h5>No performance data available for this sales team</h5>
                                <p>This sales person has no quotations or company data yet.</p>
                            </div>
                        </td>
                    </tr>
                `);
                return;
            }
            
            performanceData.forEach(function(data) {
                const row = `
                    <tr>
                        <td class="fw-bold">${data.company}</td>
                        <td>${new Intl.NumberFormat('id-ID').format(data.monthly_target)}</td>
                        <td>${new Intl.NumberFormat('id-ID').format(data.completion)}</td>
                        <td>
                            <div class="completion-bar">
                                <div class="completion-fill completion-${data.monthly_completion_color}" 
                                     style="width: ${data.monthly_completion_rate}%"></div>
                            </div>
                        </td>
                        <td>${new Intl.NumberFormat('id-ID').format(data.yearly_target)}</td>
                        <td>${new Intl.NumberFormat('id-ID').format(data.accumulative_total)}</td>
                        <td>
                            <div class="completion-bar">
                                <div class="completion-fill completion-${data.yearly_completion_color}" 
                                     style="width: ${data.yearly_completion_rate}%"></div>
                            </div>
                        </td>
                    </tr>
                `;
                tbody.append(row);
            });
        }

        // Function to update prospects table
        function updateProspectsTable(prospects) {
            // Clear the DataTable
            prospectsTable.clear();
            
            if (prospects.length === 0) {
                // Add empty state row
                prospectsTable.row.add([
                    '',
                    '<div class="text-center py-5"><div class="text-muted"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mb-3"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><path d="m22 2-5 10-3-3-10 5"></path></svg><h5>No prospects found</h5><p>This sales team member has no prospects yet.</p></div></div>',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    ''
                ]);
            } else {
                prospects.forEach(function(prospect, index) {
                    let quotationsList = '';
                    if (prospect.quotations && prospect.quotations.length > 0) {
                        const quotations = prospect.quotations.map(quotation => 
                            `<li><a href="/quotation/${quotation.id}" class="text-primary text-decoration-none" title="View quotation details">${quotation.quotation_number}</a></li>`
                        ).join('');
                        quotationsList = `<ul class="list-unstyled mb-0">${quotations}</ul>`;
                    } else {
                        quotationsList = '<span class="text-muted">No Quotation</span>';
                    }
                    
                    const progress = prospect.prospect_status ? prospect.prospect_status.persentage || 0 : 0;
                    const statusColor = prospect.prospect_status ? prospect.prospect_status.color || '#6c757d' : '#6c757d';
                    const statusName = prospect.prospect_status ? prospect.prospect_status.name || 'Unknown' : 'Unknown';
                    const canEdit = progress < 100;
                    
                    let actionButtons = `
                        <a href="/prospect/${prospect.id}" class="btn btn-icon" aria-label="View" title="View prospect">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-eye">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                            </svg>
                        </a>
                    `;
                    
                    if (canEdit) {
                        actionButtons += `
                            <a href="/prospect/${prospect.id}/edit" class="btn btn-icon" aria-label="Edit" title="Edit prospect">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                    <path d="M16 5l3 3" />
                                </svg>
                            </a>
                            <button class="btn btn-icon delete-prospect-btn" data-bs-toggle="modal" data-bs-target="#modal-delete-prospect" data-prospect-id="${prospect.id}" data-prospect-name="${prospect.customer_name}" aria-label="Delete Prospect">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 7l16 0" />
                                    <path d="M10 11l0 6" />
                                    <path d="M14 11l0 6" />
                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                </svg>
                            </button>
                        `;
                    } else {
                        actionButtons += `
                            <span class="btn btn-icon btn-outline-secondary" disabled title="Prospect sudah selesai (100%) - tidak dapat diedit atau dihapus">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="m9 12 2 2 4-4"></path>
                                </svg>
                            </span>
                        `;
                    }
                    
                    const createdDate = new Date(prospect.created_at).toISOString().split('T')[0];
                    
                    // Add row to DataTable
                    prospectsTable.row.add([
                        `<span class="text-secondary">${index + 1}</span>`,
                        `<a href="#" class="text-reset" tabindex="-1">${prospect.company || ''}</a>`,
                        prospect.customer_name || '',
                        quotationsList,
                        prospect.target_deal || '',
                        `<span class="badge me-1" style="background-color: ${statusColor};"></span>${statusName}`,
                        `<div class="d-flex align-items-center"><div class="progress progress-md me-2" style="flex: 1; min-width: 80px;"><div class="progress-bar" style="width: ${progress}%" role="progressbar" aria-valuenow="${progress}" aria-valuemin="0" aria-valuemax="100"><span class="visually-hidden">${progress}% Complete</span></div></div><small class="text-secondary fw-medium">${progress}%</small></div>`,
                        createdDate,
                        actionButtons
                    ]);
                });
            }
            
            // Draw the updated table
            prospectsTable.draw();
        }
    </script>
@endpush
