@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="fw-bold mb-4 text-primary">Kelola Kode Promo</h2>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">Buat Promo Baru</div>
                <div class="card-body">
                    <form action="{{ route('admin.promos.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>Kode Promo</label>
                            <input type="text" name="kode" class="form-control" placeholder="Cth: DISKON50" required style="text-transform:uppercase">
                        </div>
                        <div class="mb-3">
                            <label>Potongan (Rp)</label>
                            <input type="number" name="diskon" class="form-control" placeholder="Cth: 5000" required>
                        </div>
                        <div class="mb-3">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Simpan</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Diskon</th>
                                <th>Ket</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($promos as $promo)
                            <tr>
                                <td class="fw-bold text-success">{{ $promo->kode }}</td>
                                <td>Rp {{ number_format($promo->diskon) }}</td>
                                <td>{{ $promo->keterangan ?? '-' }}</td>
                                <td>
                                    <form onsubmit="return confirm('Hapus promo ini?');" action="{{ route('admin.promos.destroy', $promo->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted">Belum ada promo.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
