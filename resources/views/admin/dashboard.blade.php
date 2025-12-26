@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="fw-bold mb-4">Dashboard Admin</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card bg-primary text-white mb-3 h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-cup-hot"></i> Kelola Menu</h5>
                        <p class="card-text">Tambah atau edit menu cafe.</p>
                        <a href="{{ route('admin.menus.index') }}"
                            class="btn btn-light btn-sm text-primary fw-bold stretched-link">Buka</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-warning text-dark mb-3 h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-tag-fill"></i> Kelola Promo</h5>
                        <p class="card-text">Buat kode diskon untuk pelanggan.</p>
                        <a href="{{ route('admin.promos.index') }}"
                            class="btn btn-light btn-sm text-warning fw-bold stretched-link">Buka</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-success text-white mb-3 h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-cart-check"></i> Pesanan Masuk</h5>
                        <p class="card-text">Lihat pesanan pelanggan.</p>
                        <a href="{{ route('admin.orders.index') }}"
                            class="btn btn-light btn-sm text-success fw-bold stretched-link">Buka</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
