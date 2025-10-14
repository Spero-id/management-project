@extends('layouts.app')

@section('title', 'List Quotation Negotiation')

@section('content')
<div class="page-body">
    <div class="container-xl">

        {{-- Header --}}
        <div class="page-header d-print-none mb-3">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title fw-semibold mb-1">Negotiation</h2>
                    <p class="text-muted mb-0">Overview of all quotation negotiations</p>
                </div>
            </div>
        </div>

        {{-- Data Dummy --}}
        @php
        $negotiations = [
        (object)[ 'id'=>1,'quotation_no'=>'QTN-001','client'=>'PT Maju Jaya','project'=>'Website Company','harga_awal'=>15000000,'harga_dinego'=>13500000,'status'=>'Pending','created_at'=>'2024-01-15' ],
        (object)[ 'id'=>2,'quotation_no'=>'QTN-002','client'=>'CV Sinar Baru','project'=>'Mobile App','harga_awal'=>25000000,'harga_dinego'=>23000000,'status'=>'Approved','created_at'=>'2024-02-20' ],
        (object)[ 'id'=>3,'quotation_no'=>'QTN-003','client'=>'PT Cahaya Mandiri','project'=>'E-Commerce','harga_awal'=>18000000,'harga_dinego'=>16000000,'status'=>'Approved','created_at'=>'2024-03-10' ],
        (object)[ 'id'=>4,'quotation_no'=>'QTN-004','client'=>'PT Sentosa','project'=>'CRM System','harga_awal'=>20000000,'harga_dinego'=>19000000,'status'=>'Rejected','created_at'=>'2024-04-22' ],
        ];
        @endphp


        {{-- Card --}}
        <div class="card shadow-sm">
            <div class="card-header pb-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h3 class="card-title mb-0">Negotiation List</h3>

                {{-- Search + Filter --}}
                <div class="d-flex align-items-center gap-2">
                    <select id="statusFilter" class="form-select form-select-sm" style="width:150px;">
                        <option value="All">All Status</option>
                        <option value="Approved">Approved</option>
                        <option value="Pending">Pending</option>
                        <option value="Rejected">Rejected</option>
                    </select>

                    <div class="input-icon">
                        <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search...">
                        <span class="input-icon-addon">
                            <i class="ti ti-search"></i>
                        </span>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-vcenter table-striped text-nowrap mb-0" id="negotiationTable">
                        <thead class="table-light">
                            <tr>
                                <th class="w-1 text-center">#</th>
                                <th>Quotation No</th>
                                <th>Client</th>
                                <th>project</th>
                                <th>Harga Awal</th>
                                <th>Harga Dinego</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($negotiations as $nego)
                            <tr data-status="{{ $nego->status }}">
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $nego->quotation_no }}</td>
                                <td>{{ $nego->client }}</td>
                                <td>{{ $nego->project ?? '—' }}</td>
                                <td>Rp {{ number_format($nego->harga_awal,0,',','.') }}</td>
                                <td>Rp {{ number_format($nego->harga_dinego,0,',','.') }}</td>
                                <td>
                                    <span class="badge 
                                        @if($nego->status == 'Approved') bg-success-lt text-success
                                        @elseif($nego->status == 'Rejected') bg-danger-lt text-danger
                                        @elseif($nego->status == 'Pending') bg-warning-lt text-warning
                                        @endif">
                                        {{ $nego->status }}
                                    </span>
                                </td>
                                <td>{{ date('d M Y', strtotime($nego->created_at)) }}</td>
                                <td class="text-end">
                                    <a href="{{ url('negotiation/detail/' . $nego->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-eye"></i> View Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Footer --}}
            <div class="card-footer d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Showing <b>1</b> to <b>{{ count($negotiations) }}</b> of <b>{{ count($negotiations) }}</b> entries
                </div>
                <ul class="pagination mb-0">
                    <li class="page-item disabled"><a class="page-link" href="#">‹</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">›</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>



{{-- Script Filter + Search --}}
<script>
    const searchInput = document.getElementById("searchInput");
    const statusFilter = document.getElementById("statusFilter");
    const rows = document.querySelectorAll("#negotiationTable tbody tr");

    function filterTable() {
        const searchValue = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const rowStatus = row.getAttribute("data-status");
            const matchSearch = text.includes(searchValue);
            const matchStatus = (statusValue === "All" || rowStatus === statusValue);
            row.style.display = (matchSearch && matchStatus) ? "" : "none";
        });
    }

    searchInput.addEventListener("keyup", filterTable);
    statusFilter.addEventListener("change", filterTable);
</script>
@endsection