@extends('admin.layout')

@section('content')
    <div class="container">
        <h4 class="mb-4">Riwayat Pembayaran</h4>

        {{-- FILTER --}}
        <form class="row mb-3">
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="dibayar">Dibayar</option>
                    <option value="belum_bayar">Belum Dibayar</option>
                </select>
            </div>

            <div class="col-md-3">
                <select name="metode" class="form-select">
                    <option value="">Semua Metode</option>
                    <option value="cash">Cash</option>
                    <option value="qris">QRIS</option>
                    <option value="transfer">Transfer</option>
                </select>
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary">Filter</button>
            </div>
        </form>

        {{-- TABLE --}}
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Waktu Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                                        <tr>
                                            <td>#{{ $order->id }}</td>
                                            <td>{{ $order->user->name }}</td>
                                            <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                            <td>{{ strtoupper($order->metode_pembayaran ?? '-') }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $order->status_pembayaran == 'dibayar' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ ucfirst($order->status_pembayaran) }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $order->waktu_bayar
                            ? $order->waktu_bayar->format('d M Y H:i')
                            : '-' }}
                                            </td>
                                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    Belum ada transaksi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection