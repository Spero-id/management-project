@extends('layouts.app')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">Logistic Dashboard</h2>
            </div>
        </div>
    </div>
</div>
<div class="page-wrapper">
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <!-- Statistics -->
            <div class="row row-deck row-cards">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-muted small font-weight-medium">Total Installations</div>
                            <div class="display-6 font-weight-bold">{{ $totalInstallations }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-muted small font-weight-medium">Pending</div>
                            <div class="display-6 font-weight-bold text-warning">{{ $pendingInstallations }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-muted small font-weight-medium">Completed</div>
                            <div class="display-6 font-weight-bold text-success">{{ $completedInstallations }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-muted small font-weight-medium">On-Time Delivery Rate</div>
                            <div class="display-6 font-weight-bold text-primary">{{ $performanceMetrics['on_time_delivery_rate'] }}%</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Installation Schedule -->
            <div class="row row-cards mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Upcoming Installation Schedule</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter">
                                <thead>
                                    <tr>
                                        <th>Installation</th>
                                        <th>Scheduled Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($installationSchedule as $installation)
                                    <tr>
                                        <td>{{ $installation->name }}</td>
                                        <td>{{ $installation->scheduled_date?->format('d M Y') ?? '-' }}</td>
                                        <td>
                                            <span class="badge badge-{{ $installation->status === 'completed' ? 'success' : 'warning' }}">
                                                {{ ucfirst($installation->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No installations scheduled</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accommodation & Performance -->
            <div class="row row-cards mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Accommodation Status</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-vcenter">
                                <thead>
                                    <tr>
                                        <th>Accommodation</th>
                                        <th>Capacity</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($accommodationStatus as $accommodation)
                                    <tr>
                                        <td>{{ $accommodation->name }}</td>
                                        <td>{{ $accommodation->capacity }}</td>
                                        <td>
                                            <span class="badge badge-primary">{{ ucfirst($accommodation->status) }}</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No accommodation data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Performance Metrics</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>On-Time Delivery</span>
                                    <strong>{{ $performanceMetrics['on_time_delivery_rate'] }}%</strong>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $performanceMetrics['on_time_delivery_rate'] }}%" aria-valuenow="{{ $performanceMetrics['on_time_delivery_rate'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Installation Efficiency</span>
                                    <strong>{{ $performanceMetrics['installation_efficiency'] }}%</strong>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $performanceMetrics['installation_efficiency'] }}%" aria-valuenow="{{ $performanceMetrics['installation_efficiency'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>

                            <div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Accommodation Utilization</span>
                                    <strong>{{ $performanceMetrics['accommodation_utilization'] }}%</strong>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $performanceMetrics['accommodation_utilization'] }}%" aria-valuenow="{{ $performanceMetrics['accommodation_utilization'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
