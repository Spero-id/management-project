@extends('layouts.app')

@section('title', 'Log Quotation Negotiation')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4 fw-bold text-primary">
        <i class="bx bx-history"></i> Log Quotation Negotiation
    </h3>

    {{-- Container Utama --}}
    <div class="card shadow-sm border-primary">
        <div class="card-body">
            {{-- Informasi Negosiasi --}}
            <div class="mb-4">
                <h5 class="card-title mb-1">Quotation ID: <span class="text-primary">QTN-001</span></h5>
                <p class="mb-0 text-muted">Client: PT Maju Jaya</p>
                <p class="mb-0 text-muted">Project: Website Company</p>
            </div>

            {{-- Timeline Log --}}
            @php
                $logs = [
                    [
                        'status' => 'Harga Awal',
                        'tanggal' => '2025-10-01 08:00',
                        'user' => 'John Doe',
                        'catatan' => 'Harga awal quotation: Rp 15.000.000.',
                        'warna' => 'secondary'
                    ],
                    [
                        'status' => 'Negosiasi Diajukan',
                        'tanggal' => '2025-10-01 09:30',
                        'user' => 'John Doe',
                        'catatan' => 'User mengajukan negosiasi harga sebesar Rp 13.500.000.',
                        'warna' => 'primary'
                    ],
                    [
                        'status' => 'Negosiasi Disetujui',
                        'tanggal' => '2025-10-03 15:45',
                        'user' => 'Admin',
                        'catatan' => 'Negosiasi disetujui dengan harga final Rp 13.500.000.',
                        'warna' => 'success'
                    ]
                ];
            @endphp

            <ul class="list-unstyled position-relative m-0 p-0">
                @foreach ($logs as $log)
                    <li class="mb-4 position-relative ps-4 border-start border-{{ $log['warna'] }} border-3">
                        <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-{{ $log['warna'] }}">
                            <i class="bx bx-check-circle"></i>
                        </span>
                        <div class="ms-3">
                            <h6 class="fw-bold text-{{ $log['warna'] }}">{{ $log['status'] }}</h6>
                            <small class="text-muted">
                                {{ $log['tanggal'] }} • oleh <strong>{{ $log['user'] }}</strong>
                            </small>
                            <p class="mt-1 mb-0">{{ $log['catatan'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Footer Tombol Kembali --}}
        <div class="card-footer text-end bg-light">
            <a href="{{ url('negotiation') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back"></i> Kembali
            </a>
        </div>
    </div>
</div>
@endsection
