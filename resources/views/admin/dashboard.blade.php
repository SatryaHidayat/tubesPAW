@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="fw-bold mb-4">Dashboard Admin</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card bg-primary text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-cup-hot"></i> Kelola Menu</h5>
                    <p class="card-text">Tambah atau edit menu cafe.</p>
                    <a href="{{ route('admin.menus.index') }}" class="btn btn-light btn-sm text-primary fw-bold">Buka</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-cart-check"></i> Pesanan Masuk</h5>
                    <p class="card-text">Lihat pesanan pelanggan.</p>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-light btn-sm text-success fw-bold">Buka</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
