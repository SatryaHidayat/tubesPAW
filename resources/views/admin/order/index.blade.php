@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="fw-bold mb-4 text-primary">Daftar Pesanan Masuk</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No Order</th>
                            <th>Pelanggan</th>
                            <th>Detail Menu</th>
                            <th>Total & Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>
                                <div class="fw-bold">{{ $order->user->name }}</div>
                                <small class="text-muted">{{ $order->created_at->format('d M H:i') }}</small>
                            </td>
                            <td>
                                <ul class="list-unstyled mb-0 small">
                                    @foreach($order->details as $detail)
                                        <li>
                                            {{ $detail->jumlah }}x {{ $detail->menu->nama_menu }}
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <div class="fw-bold">Rp {{ number_format($order->total_harga) }}</div>
                                @if($order->status == 'diproses')
                                    <span class="badge bg-warning text-dark">Diproses</span>
                                @elseif($order->status == 'siap')
                                    <span class="badge bg-info text-dark">Siap Saji</span>
                                @elseif($order->status == 'selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @else
                                    <span class="badge bg-danger">Batal</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="d-flex gap-1">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-select form-select-sm" style="width: 100px;">
                                        <option value="diproses" {{ $order->status == 'diproses' ? 'selected' : '' }}>Proses</option>
                                        <option value="siap" {{ $order->status == 'siap' ? 'selected' : '' }}>Siap</option>
                                        <option value="selesai" {{ $order->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="batal" {{ $order->status == 'batal' ? 'selected' : '' }}>Batal</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-check"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada pesanan masuk.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
