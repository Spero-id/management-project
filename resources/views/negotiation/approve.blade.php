<!-- @extends('layouts.app')

@section('title', 'Approve Negotiation')

@section('content')
<div class="container mt-4">
    <h3 class="mb-3">Approve Price Negotiation</h3>

    <div class="alert alert-info">
        Anda akan menyetujui negosiasi untuk <strong>{{ $negotiation->client_name }}</strong>
        dengan harga dinego <strong>Rp {{ number_format($negotiation->harga_dinego,0,',','.') }}</strong>.
    </div>

    <form action="{{ route('negotiation.approve.post', $negotiation->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="keterangan" class="form-label">Catatan (opsional)</label>
            <textarea name="keterangan" id="keterangan" class="form-control" rows="3" placeholder="Tambahkan catatan persetujuan..."></textarea>
        </div>
        <button type="submit" class="btn btn-success">Konfirmasi Approve</button>
        <a href="{{ route('negotiation.detail', $negotiation->id) }}" class="btn btn-outline-dark">Batal</a>
    </form>
</div>
@endsection -->
