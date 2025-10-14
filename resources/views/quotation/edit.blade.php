@extends('layouts.app')

@section('content')
<div class="page-body">
    <div class="container-xl">
        <h2 class="page-title mb-4">Edit Quotation</h2>

        <form action="{{ route('quotation.update', $quotation->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Client Name</label>
                            <input type="text" class="form-control" name="client_name" value="{{ $quotation->client_name }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Project Name</label>
                            <input type="text" class="form-control" name="project_name" value="{{ $quotation->project_name }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Total Amount</label>
                            <input type="number" class="form-control" step="0.01" name="total_amount" value="{{ $quotation->total_amount }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="pending" {{ $quotation->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $quotation->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $quotation->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" rows="3" name="notes">{{ $quotation->notes }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('quotation.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Quotation</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
