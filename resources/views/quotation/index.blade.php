@extends('layouts.app')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">Management Quotation</h2>
                    <div class="text-secondary">List of all project quotations</div>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <a href="{{ route('quotation.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus"></i> Add Quotation
                    </a>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body border-bottom py-3">
                <div class="d-flex">
                    <div class="ms-auto text-secondary">
                        Search:
                        <div class="ms-2 d-inline-block">
                            <input type="text" class="form-control form-control-sm" placeholder="Search...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Quotation No</th>
                            <th>Client</th>
                            <th>Project</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th class="w-1">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($quotations ?? [] as $quotation)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $quotation->quotation_number ?? '-' }}</td>
                                <td>{{ $quotation->client_name ?? '-' }}</td>
                                <td>{{ $quotation->project_name ?? '-' }}</td>
                                <td>${{ number_format($quotation->total_amount ?? 0, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $quotation->status == 'approved' ? 'success' : ($quotation->status == 'rejected' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($quotation->status ?? 'pending') }}
                                    </span>
                                </td>
                                <td>{{ $quotation->created_at->format('d M Y') ?? '-' }}</td>
                                <td class="text-end">
                                    <div class="btn-list flex-nowrap">
                                        <a href="{{ route('quotation.detail', $quotation->id) }}" class="btn btn-outline-info btn-icon">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="{{ route('quotation.edit', $quotation->id) }}" class="btn btn-outline-primary btn-icon">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-outline-success btn-icon">
                                            <i class="ti ti-upload"></i>
                                        </a>
                                        <a href="#" class="btn btn-outline-secondary btn-icon">
                                            <i class="ti ti-download"></i>
                                        </a>
                                        <a href="#" class="btn btn-outline-success btn-icon">
                                            <i class="ti ti-check"></i>
                                        </a>
                                        <a href="#" class="btn btn-outline-danger btn-icon">
                                            <i class="ti ti-x"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
