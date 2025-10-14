<!-- @extends('layouts.app')

@section('title', 'Reject Negotiation')

@section('content')
<div class="container mt-4">
    <h3 class="mb-3">Reject Price Negotiation</h3>

    <div class="alert alert-warning">
        Anda akan menolak negosiasi dari <strong>{{ $negotiation->client_name }}</strong>.
    </div>

    <form action="{{ route('negotiation.reject.post', $negotiation->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="alasan" class="form-label">Alasan Penolakan</label>
            <textarea name="alasan" id="alasan" class="form-control" rows="3" placeholder="Tuliskan alasan penolakan..." required></textarea>
        </div>
        <button type="submit" class="btn btn-danger">Konfirmasi Reject</button>
        <a href="{{ route('negotiation.detail', $negotiation->id) }}" class="btn btn-outline-dark">Batal</a>
    </form>
</div>
@endsection -->
