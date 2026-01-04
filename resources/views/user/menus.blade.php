@extends('layouts.app')

@section('content')
<div class="container-fluid px-5">
    <div class="row">
        <div class="col-md-2 border-end pt-3 d-none d-md-block">
            <div class="input-group mb-4">
                <input type="text" class="form-control rounded-pill bg-light border-0" placeholder="Search">
            </div>

            <ul class="nav flex-column gap-3 sidebar-nav">
                <li class="nav-item"><a class="nav-link text-muted" href="#">Trending</a></li>
                <li class="nav-item"><a class="nav-link text-muted" href="#">Promo</a></li>
                <li class="nav-item"><a class="nav-link text-muted" href="#">Contact</a></li>
            </ul>
        </div>

        <div class="col-md-10 pt-3 pb-5">
            <div class="card border-0 rounded-4 overflow-hidden mb-4 shadow-sm">
                <img src="{{ asset('images/banner.jpg') }}" class="card-img-top" style="height: 250px; object-fit: cover;" alt="Banner Promo">
            </div>

            <div class="row row-cols-2 row-cols-md-4 row-cols-lg-5 g-4">
                @forelse($menus as $menu)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-4 product-card text-center p-2">
                        <div style="height: 140px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                            <img src="{{ asset('storage/' . $menu->foto) }}" class="img-fluid" style="max-height: 120px; object-fit: contain;" alt="{{ $menu->nama_menu }}">
                        </div>

                        <div class="card-body px-1 pb-2 pt-0">
                            <h6 class="card-title fw-bold mb-1 text-dark" style="font-size: 0.95rem;">
                                {{ $menu->nama_menu }}
                            </h6>
                            <p class="card-text fw-bold text-secondary small mb-2">
                                Rp{{ number_format($menu->harga, 0, ',', '.') }}
                            </p>

                            <button type="button" class="btn btn-sm btn-outline-dark rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#orderModal{{ $menu->id }}">
                                Add
                            </button>
                        </div>
                    </div>

                    <div class="modal fade" id="orderModal{{ $menu->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-bold">Pesan {{ $menu->nama_menu }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <form action="{{ route('user.checkout') }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <input type="hidden" name="menu_id" value="{{ $menu->id }}">

                                        <div class="d-flex gap-3 mb-4">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('storage/' . $menu->foto) }}" class="rounded-3 border" style="width: 100px; height: 100px; object-fit: cover;">
                                            </div>
                                            <div class="flex-grow-1">
                                                <small class="text-muted d-block">Harga Satuan</small>
                                                <h5 class="fw-bold text-primary mb-3">Rp{{ number_format($menu->harga, 0, ',', '.') }}</h5>

                                                <div class="d-flex align-items-center gap-2">
                                                    <small class="fw-bold">Jumlah:</small>
                                                    <input type="number" name="jumlah" class="form-control form-control-sm text-center" value="1" min="1" style="width: 70px;" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small fw-bold mb-1">Catatan (Opsional)</label>
                                            <input type="text" name="catatan" class="form-control bg-light border-0" placeholder="Cth: Less sugar, Tanpa es...">
                                        </div>

                                        <div class="mb-1">
                                            <label class="form-label small fw-bold mb-1 text-success">
                                                <i class="bi bi-ticket-perforated-fill"></i> Kode Promo
                                            </label>
                                            <input type="text" name="kode_promo" class="form-control border-success text-success" placeholder="Masukkan kode voucher..." style="text-transform: uppercase;">
                                        </div>
                                    </div>

                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary rounded-pill px-4">Pesan Sekarang</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    </div>
                @empty
                <div class="col-12 py-5 text-center">
                    <div class="alert alert-light" role="alert">
                        <h5 class="text-muted">Belum ada menu yang tersedia.</h5>
                        <p class="small text-muted">Silakan minta Admin untuk menambahkan menu.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS Tambahan */
    .sidebar-nav .nav-link { transition: all 0.2s; }
    .sidebar-nav .nav-link:hover, .sidebar-nav .nav-link.active {
        color: #000 !important; background-color: #f8f9fa; border-radius: 8px;
    }
    .product-card {
        transition: transform 0.2s, box-shadow 0.2s; background-color: #ffffff; cursor: pointer;
    }
    .product-card:hover {
        transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
</style>
@endsection
