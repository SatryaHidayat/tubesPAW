@extends('layouts.app')

@section('content')
<div class="container pb-5"> <h2 class="fw-bold mb-4 text-center">Daftar Menu</h2>

    @if(session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif

    <form action="{{ route('user.checkout') }}" method="POST">
        @csrf

        <div class="row justify-content-center">
            @forelse($menus as $menu)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div style="height: 200px; overflow: hidden;" class="card-img-top bg-light d-flex align-items-center justify-content-center">
                        @if($menu->foto)
                            <img src="{{ asset('storage/' . $menu->foto) }}" alt="{{ $menu->nama_menu }}" class="w-100 h-100 object-fit-cover">
                        @else
                            <span class="text-muted">No Image</span>
                        @endif
                    </div>

                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <h5 class="fw-bold">{{ $menu->nama_menu }}</h5>
                            <span class="badge {{ $menu->kategori == 'makanan' ? 'bg-warning' : 'bg-info' }} text-dark">
                                {{ ucfirst($menu->kategori) }}
                            </span>
                        </div>
                        <p class="text-muted small">{{ Str::limit($menu->deskripsi, 60) }}</p>
                        <h5 class="text-primary fw-bold mb-3">Rp {{ number_format($menu->harga, 0, ',', '.') }}</h5>

                        <div class="input-group">
                            <span class="input-group-text">Jml</span>
                            <input type="number" name="pesanan[{{ $menu->id }}]" class="form-control text-center" value="0" min="0">
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">Belum ada menu.</div>
            @endforelse
        </div>

        <div class="fixed-bottom bg-white border-top shadow p-3">
            <div class="container d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    <small>Pilih menu & isi jumlahnya,</small><br>
                    <small>lalu klik Pesan.</small>
                </div>
                <button type="submit" class="btn btn-primary fw-bold px-4 rounded-pill">
                    <i class="bi bi-cart-check-fill"></i> Pesan Sekarang
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
