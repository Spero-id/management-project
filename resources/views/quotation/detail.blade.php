@extends('layouts.app')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="page-header d-print-none mb-3">
            <h2 class="page-title">Quotation Detail</h2>
        </div>

        <div class="card card-lg">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <p class="h3">Company Name</p>
                        <address>
                            123 Street Name<br>
                            City, 12345<br>
                            company@example.com
                        </address>
                    </div>
                    <div class="col-6 text-end">
                        <p class="h3">{{ $quotation->client_name ?? 'Client Name' }}</p>
                        <address>
                            {{ $quotation->project_name ?? 'Project Name' }}<br>
                            Date: {{ $quotation->created_at->format('d M Y') ?? '-' }}<br>
                            Quotation No: {{ $quotation->quotation_number ?? '-' }}
                        </address>
                    </div>
                </div>

                <table class="table table-transparent table-responsive mt-4">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Description</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Unit Price</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($quotation->items ?? [] as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->description }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">${{ number_format($item->unit_price, 2) }}</td>
                                <td class="text-end">${{ number_format($item->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="text-end mt-4">
                    <p class="mb-2"><strong>Subtotal:</strong> ${{ number_format($quotation->subtotal ?? 0, 2) }}</p>
                    <p class="mb-2"><strong>Tax (10%):</strong> ${{ number_format($quotation->tax ?? 0, 2) }}</p>
                    <h3>Total: ${{ number_format($quotation->total_amount ?? 0, 2) }}</h3>
                </div>

                <div class="mt-5 text-center">
                    <a href="{{ route('quotation.index') }}" class="btn btn-outline-secondary">Back</a>
                    <a href="#" class="btn btn-primary">
                        <i class="ti ti-download"></i> Download PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
