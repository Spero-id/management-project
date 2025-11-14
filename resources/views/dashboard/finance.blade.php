@extends('layouts.app')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">Finance Dashboard</h2>
            </div>
        </div>
    </div>
</div>
<div class="page-wrapper">
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <!-- Key Financial Metrics -->
            <div class="row row-deck row-cards">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-muted small font-weight-medium">Total Revenue</div>
                            <div class="display-6 font-weight-bold">
                                {{ number_format($totalRevenue, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-muted small font-weight-medium">Total Expenses</div>
                            <div class="display-6 font-weight-bold text-danger">
                                {{ number_format($totalExpenses, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-muted small font-weight-medium">Profit Margin</div>
                            <div class="display-6 font-weight-bold text-success">{{ $profitMargin }}%</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-muted small font-weight-medium">Quotation Value</div>
                            <div class="display-6 font-weight-bold text-primary">
                                {{ number_format($quotationMetrics['total_value'], 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quotation Metrics -->
            <div class="row row-cards mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Quotation Summary</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="text-muted">Total Quotations</div>
                                        <div class="display-6 font-weight-bold">{{ $quotationMetrics['total_quotations'] }}</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="text-muted">Accepted</div>
                                        <div class="display-6 font-weight-bold text-success">{{ $quotationMetrics['accepted_quotations'] }}</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="text-muted">Pending</div>
                                        <div class="display-6 font-weight-bold text-warning">{{ $quotationMetrics['pending_quotations'] }}</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="text-muted">Acceptance Rate</div>
                                        <div class="display-6 font-weight-bold text-primary">
                                            @if($quotationMetrics['total_quotations'] > 0)
                                                {{ round(($quotationMetrics['accepted_quotations'] / $quotationMetrics['total_quotations']) * 100, 1) }}%
                                            @else
                                                0%
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoice & Payment Analysis -->
            <div class="row row-cards mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Invoice Status</h3>
                        </div>
                        <div class="card-body">
                            @if(count($invoiceStatus) > 0)
                                <div class="list-group">
                                    @foreach($invoiceStatus as $invoice)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between">
                                            <span>{{ $invoice['invoice_number'] ?? '-' }}</span>
                                            <span class="badge">{{ $invoice['status'] ?? '-' }}</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center text-muted">No invoice data available</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Payment Analysis</h3>
                        </div>
                        <div class="card-body">
                            @if(count($paymentAnalysis) > 0)
                                <div class="list-group">
                                    @foreach($paymentAnalysis as $payment)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between">
                                            <span>{{ $payment['description'] ?? '-' }}</span>
                                            <span>{{ number_format($payment['amount'], 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center text-muted">No payment data available</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cashflow Trend -->
            <div class="row row-cards mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Cashflow Trend</h3>
                        </div>
                        <div class="card-body">
                            @if(count($cashflowTrend) > 0)
                                <!-- Add chart here if data is available -->
                                <p class="text-muted">Cashflow trend data available</p>
                            @else
                                <div class="text-center text-muted">No cashflow data available</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
