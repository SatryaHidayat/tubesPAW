@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Tambah Promo Baru</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.promos.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>Kode Promo (Huruf Kapital)</label>
                            <input type="text" name="kode" class="form-control" placeholder="Contoh: MERDEKA45" required style="text-transform: uppercase;">
                        </div>
                        <div class="mb-3">
                            <label>Jumlah Potongan (Rupiah)</label>
                            <input type="number" name="diskon" class="form-control" placeholder="Contoh: 10000" required>
                        </div>
                        <div class="mb-3">
                            <label>Deskripsi (Opsional)</label>
                            <textarea name="Keterangan" class="form-control" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill">Simpan Promo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
