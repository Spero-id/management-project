@extends('layouts.app')

@section('title', 'Detail Quotation Negotiation')

@section('content')
<div class="page-body">
    <div class="container-xl">

        {{-- Header --}}
        <div class="page-header d-print-none mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title fw-semibold mb-1">Negotiation Detail</h2>
                    <p class="text-muted mb-0">Detailed overview of the quotation negotiation</p>
                </div>
                <div class="col-auto">
                    <a href="{{ url('/negotiation') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>

        @php
            $negotiation = (object)[
                'id' => 1,
                'quotation_no' => 'QTN-001',
                'client_name' => 'PT Maju Jaya',
                'project_name' => 'Website Company',
                'harga_awal' => 15000000,
                'harga_dinego' => 13500000,
                'status' => 'Pending',
                'created_at' => '2024-01-15',
            ];
        @endphp

        {{-- Card Modern --}}
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row mb-3">
                    {{-- Section Info --}}
                    <div class="col-md-6 mb-3">
                        <div class="p-3 bg-light rounded mb-2">
                            <h6 class="text-muted mb-1">Quotation No</h6>
                            <p class="mb-0 fw-medium">{{ $negotiation->quotation_no }}</p>
                        </div>
                        <div class="p-3 bg-light rounded mb-2">
                            <h6 class="text-muted mb-1">Client</h6>
                            <p class="mb-0 fw-medium">{{ $negotiation->client_name }}</p>
                        </div>
                        <div class="p-3 bg-light rounded mb-2">
                            <h6 class="text-muted mb-1">Project Name</h6>
                            <p class="mb-0 fw-medium">{{ $negotiation->project_name }}</p>
                        </div>
                    </div>

                    {{-- Section Price & Status --}}
                    <div class="col-md-6 mb-3">
                        <div class="p-3 rounded mb-2" style="background-color:#f0f8ff;">
                            <h6 class="text-muted mb-1">Harga Awal</h6>
                            <p class="mb-0 fw-medium">Rp {{ number_format($negotiation->harga_awal,0,',','.') }}</p>
                        </div>
                        <div class="p-3 rounded mb-2" style="background-color:#e6ffe6;">
                            <h6 class="text-muted mb-1">Harga Dinego</h6>
                            <p class="mb-0 fw-medium">Rp {{ number_format($negotiation->harga_dinego,0,',','.') }}</p>
                        </div>
                        <div class="p-3 rounded mb-2 d-flex align-items-center justify-content-between" style="background-color:#fff3cd;">
                            <div>
                                <h6 class="text-muted mb-1">Status</h6>
                                <span class="fw-medium">{{ $negotiation->status }}</span>
                            </div>
                            <small class="text-muted">{{ date('d M Y', strtotime($negotiation->created_at)) }}</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="card-footer d-flex justify-content-end gap-2 bg-white border-top">
                <a href="{{ url('/negotiation/log/' . $negotiation->id) }}" class="btn btn-outline-info">
                    <i class="bx bx-list"></i> Log
                </a>
                <button class="btn btn-outline-danger">
                    <i class="bx bx-x"></i> Reject
                </button>
                <button class="btn btn-outline-success">
                    <i class="bx bx-check"></i> Approve
                </button>
            </div>
        </div>

    </div>
</div>
@endsection
