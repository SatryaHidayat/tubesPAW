@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h3 class="fw-bold mb-4">Riwayat Pesanan</h3>

            @forelse($orders as $order)
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-3 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted d-block">Tanggal Pesanan</small>
                        <span class="fw-bold">{{ $order->created_at->format('d M Y, H:i') }}</span>
                    </div>

                    @php
                        $badgeClass = match($order->status) {
                            'pending' => 'bg-warning text-dark',
                            'diproses' => 'bg-info text-white',
                            'siap' => 'bg-primary text-white',
                            'selesai' => 'bg-success text-white',
                            'batal' => 'bg-danger text-white',
                            default => 'bg-secondary text-white',
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>

                <div class="card-body px-4">
                    @foreach($order->details as $detail)
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('storage/' . $detail->menu->foto) }}"
                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 10px;"
                             class="me-3">

                        <div class="flex-grow-1">
                            <h6 class="mb-0 fw-bold">{{ $detail->menu->nama_menu }}</h6>
                            <small class="text-muted">
                                {{ $detail->jumlah }}x Rp{{ number_format($detail->harga_satuan, 0, ',', '.') }}
                            </small>
                            @if($detail->catatan)
                            <br><small class="text-danger fst-italic">Note: {{ $detail->catatan }}</small>
                            @endif
                        </div>

                        <div class="fw-bold">
                            Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                        </div>
                    </div>
                    @endforeach

                    <hr class="my-3">

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Total Bayar</span>
                        <h5 class="fw-bold text-primary mb-0">Rp{{ number_format($order->total_harga, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <img src="https://cdn-icons-png.flaticon.com/512/2038/2038854.png" width="100" class="mb-3 opacity-50">
                <h5 class="text-muted">Belum ada riwayat pesanan.</h5>
                <a href="{{ route('user.menus') }}" class="btn btn-primary rounded-pill mt-3">Pesan Sekarang</a>
            </div>
            @endforelse

        </div>
    </div>
</div>
@endsection
