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

                            <button class="btn btn-sm btn-outline-dark rounded-pill px-3">Add</button>
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
    /* Hover effect sidebar */
    .sidebar-nav .nav-link:hover, .sidebar-nav .nav-link.active {
        color: #000 !important;
        background-color: #f8f9fa;
        border-radius: 8px;
    }

    /* Hover effect card produk */
    .product-card {
        transition: transform 0.2s, box-shadow 0.2s;
        background-color: #ffffff;
        cursor: pointer;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
</style>
@endsection
