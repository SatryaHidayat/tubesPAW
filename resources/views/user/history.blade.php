@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="fw-bold mb-4">Riwayat Pesanan Saya</h2>

    @if(session('success'))
        <div class="alert alert-success mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row">
        @forelse($orders as $order)
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <span class="fw-bold">Order #{{ $order->id }}</span>
                    <span class="text-muted small">{{ $order->created_at->diffForHumans() }}</span>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush mb-3">
                        @foreach($order->details as $detail)
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <div>{{ $detail->jumlah }}x {{ $detail->menu->nama_menu }}</div>
                            <span>Rp {{ number_format($detail->harga_saat_ini * $detail->jumlah) }}</span>
                        </li>
                        @endforeach
                    </ul>

                    <div class="d-flex justify-content-between align-items-center border-top pt-3">
                        <h5 class="fw-bold mb-0">Total: Rp {{ number_format($order->total_harga) }}</h5>

                        @if($order->status == 'diproses')
                            <span class="badge bg-warning text-dark">Sedang Diproses</span>
                        @elseif($order->status == 'siap')
                            <span class="badge bg-info text-dark">Pesanan Siap!</span>
                        @elseif($order->status == 'selesai')
                            <span class="badge bg-success">Selesai</span>
                        @else
                            <span class="badge bg-danger">Dibatalkan</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="text-muted mb-3">Belum ada riwayat pesanan.</div>
            <a href="{{ route('user.menus') }}" class="btn btn-primary">Pesan Sekarang</a>
        </div>
        @endforelse
    </div>
</div>
@endsection
