@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h4 class="fw-bold mb-3">Halaman Pembayaran</h4>

        {{-- Notifikasi --}}
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-3">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm mb-3">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Ringkasan Pesanan</h5>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Status Pesanan:</span>
                            <span class="badge bg-warning text-dark">{{ ucfirst($order->status) }}</span>
                        </div>

                        <hr>

                        {{-- INPUT KODE PROMO --}}
                        {{-- Action diarahkan ke route order.applyPromo yang kita buat di web.php --}}
                        <form action="{{ route('order.applyPromo', $order->id) }}" method="POST" class="mb-4">
                            @csrf
                            <label class="form-label fw-bold">Punya Kode Promo?</label>
                            <div class="input-group">
                                <input type="text" name="kode_promo" class="form-control" placeholder="Masukkan kode..." style="text-transform: uppercase" required>
                                <button class="btn btn-primary" type="submit">Pasang</button>
                            </div>
                            <small class="text-muted">Potongan harga akan langsung memotong total di bawah.</small>
                        </form>

                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="fw-bold mb-0">Total Tagihan:</h4>
                            <h4 class="fw-bold text-primary mb-0">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Konfirmasi Pembayaran</h5>

                        <form method="POST" action="{{ route('order.prosesPembayaran', $order->id) }}">
                            @csrf
                            <label class="mb-2 fw-bold">Pilih Metode Pembayaran</label>
                            <select name="metode_pembayaran" class="form-select mb-4 py-2" required>
                                <option value="">-- Pilih Metode --</option>
                                <option value="cash">Cash (Bayar di Kasir)</option>
                                <option value="qris">QRIS</option>
                                <option value="transfer">Transfer Bank</option>
                            </select>

                            <button class="btn btn-success btn-lg w-100 fw-bold rounded-pill shadow-sm">
                                <i class="bi bi-wallet2 me-2"></i> Bayar Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
