@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold mb-0">Tambah Menu Baru</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Nama Menu</label>
                                <input type="text" name="nama_menu" class="form-control" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Harga (Rp)</label>
                                    <input type="number" name="harga" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Kategori Menu</label>
                                    <select name="kategori" class="form-select">
                                        <option value="" disabled selected>-- Pilih Kategori --</option>
                                        <option value="kopi">Kopi</option>
                                        <option value="non-kopi">Non-Kopi</option>
                                        <option value="makanan">Makanan Berat</option>
                                        <option value="snack">Cemilan / Snack</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Foto Menu</label>
                                <input type="file" name="foto" class="form-control" accept="image/*" required>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('admin.menus.index') }}" class="btn btn-light me-2">Batal</a>
                                <button type="submit" class="btn btn-primary">Simpan Menu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
