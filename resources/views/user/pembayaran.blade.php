@extends('layouts.app')

@section('content')
    <div class="container">
        <h4>Pembayaran</h4>

        <div class="card">
            <div class="card-body">
                <p><strong>Total:</strong>
                    Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                </p>

                <form method="POST" action="{{ route('order.prosesPembayaran', $order->id) }}">
                    @csrf

                    <label class="mb-2">Metode Pembayaran</label>

                    <select name="metode_pembayaran" class="form-select mb-3" required>
                        <option value="">-- Pilih Metode --</option>
                        <option value="cash">Cash</option>
                        <option value="qris">QRIS</option>
                        <option value="transfer">Transfer Bank</option>
                    </select>

                    <button class="btn btn-success w-100">
                        Bayar Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection