@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Edit Promo</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.promos.update', $promo->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label>Kode Promo</label>
                            <input type="text" name="kode" value="{{ $promo->kode }}" class="form-control" required style="text-transform: uppercase;">
                        </div>
                        <div class="mb-3">
                            <label>Jumlah Potongan (Rupiah)</label>
                            <input type="number" name="diskon" value="{{ $promo->diskon }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Deskripsi</label>
                            <textarea name="Keterangan" class="form-control" rows="2">{{ $promo->deskripsi }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill">Update Promo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
