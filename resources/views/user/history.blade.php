@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h3 class="fw-bold mb-4">Riwayat Pesanan</h3>

            @forelse ($orders as $order)
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <div>
                        <small class="text-muted d-block">Tanggal Pemesanan</small>
                        <span class="fw-bold">{{ $order->created_at->format('d M Y, H:i') }}</span>
                    </div>

                    @php
                        $badgeClass = match ($order->status) {
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
                        <img src="{{ asset('storage/' . ($detail->menu?->foto ?? 'default.png')) }}"
                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 10px;"
                             class="me-3" alt="Foto Menu">

                        <div class="flex-grow-1">
                            <h6 class="mb-0 fw-bold"> {{ $detail->menu?->nama_menu ?? 'Menu tidak tersedia' }} </h6>
                            <small class="text-muted">
                                {{ $detail->jumlah }}x Rp{{ number_format($detail->harga_saat_ini, 0, ',', '.') }}
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

                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Metode: {{ strtoupper($order->metode_pembayaran ?? '-') }}</p>
                            <p class="mb-0">
                                Status Pembayaran:
                                <span class="badge {{ $order->status_pembayaran == 'dibayar' ? 'bg-success' : 'bg-warning' }}">
                                    {{ ucfirst($order->status_pembayaran) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">

                            {{-- --- BAGIAN RINCIAN DISKON --- --}}
                            @if($order->diskon > 0)
                                <div class="mb-1">
                                    <span class="text-muted me-2">Potongan Promo</span>
                                    <span class="text-danger fw-bold">- Rp{{ number_format($order->diskon, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            <span class="text-muted me-2">Total Bayar</span>
                            <h4 class="fw-bold text-primary d-inline-block mb-0">Rp{{ number_format($order->total_harga, 0, ',', '.') }}</h4>

                            <div class="mt-2">
                                @if($order->status_pembayaran == 'belum_bayar' && $order->status != 'batal')
                                    <a href="{{ route('order.pembayaran', $order->id) }}" class="btn btn-warning btn-sm px-4 fw-bold shadow-sm">
                                        Bayar Sekarang
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @empty
            <div class="text-center py-5">
                <img src="https://cdn-icons-png.flaticon.com/512/2038/2038854.png" width="100" class="mb-3 opacity-50" alt="Kosong">
                <h5 class="text-muted">Belum ada riwayat pesanan.</h5>
                <a href="{{ route('user.menus') }}" class="btn btn-primary rounded-pill mt-3 px-4">Pesan Sekarang</a>
            </div>
            @endforelse

        </div>
    </div>
</div>
@endsection
