@extends('layouts.app')

@section('content')
<div class="container py-4">
    <form action="{{ route('user.checkout') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm p-3 sticky-top" style="top: 20px; border-radius: 15px;">
                    <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-filter-left me-2"></i>Kategori</h5>
                    <div class="list-group list-group-flush mb-4">
                        <a href="{{ route('user.menus') }}" class="list-group-item list-group-item-action border-0 ps-0 {{ !request('kategori') ? 'text-primary fw-bold' : 'text-muted' }}">Semua Menu</a>
                        <a href="{{ route('user.menus', ['kategori' => 'kopi']) }}" class="list-group-item list-group-item-action border-0 ps-0 {{ request('kategori') == 'kopi' ? 'text-primary fw-bold' : 'text-muted' }}">Kopi</a>
                        <a href="{{ route('user.menus', ['kategori' => 'non-kopi']) }}" class="list-group-item list-group-item-action border-0 ps-0 {{ request('kategori') == 'non-kopi' ? 'text-primary fw-bold' : 'text-muted' }}">Non-Kopi</a>
                        <a href="{{ route('user.menus', ['kategori' => 'makanan']) }}" class="list-group-item list-group-item-action border-0 ps-0 {{ request('kategori') == 'makanan' ? 'text-primary fw-bold' : 'text-muted' }}">Makanan</a>
                    </div>

                    <hr class="text-muted">

                    <button type="submit" class="btn btn-primary w-100 fw-bold py-3 rounded-pill shadow-sm mt-2">
                        CHECKOUT SEKARANG <i class="bi bi-arrow-right-circle ms-2"></i>
                    </button>
                </div>
            </div>

            <div class="col-md-9">
                {{-- Banner Diskon --}}
                <div class="card border-0 shadow-sm mb-4 bg-primary text-white" style="border-radius: 1rem;">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="fw-bold mb-1">Pilih Menu Favoritmu!</h3>
                            <p class="mb-0">Nikmati kopi terbaik kami hari ini.</p>
                        </div>
                        <i class="bi bi-stars fs-1 opacity-50"></i>
                    </div>
                </div>

                {{-- Alert Pesan --}}
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger border-0 shadow-sm mb-4">{{ session('error') }}</div>
                @endif

                <div class="row row-cols-2 row-cols-lg-3 g-4">
                    @forelse($menus as $menu)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px;">
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center overflow-hidden" style="height: 160px; border-radius: 12px 12px 0 0;">
                                @if($menu->foto)
                                    <img src="{{ asset('storage/' . $menu->foto) }}" class="w-100 h-100 object-fit-cover">
                                @else
                                    <i class="bi bi-cup text-muted fs-1"></i>
                                @endif
                            </div>
                            <div class="card-body p-3">
                                <h6 class="fw-bold mb-1">{{ $menu->nama_menu }}</h6>
                                <p class="text-primary fw-bold mb-3">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                                <div class="d-flex align-items-center bg-light rounded-pill p-1 border">
                                    <button type="button" class="btn btn-sm btn-white rounded-circle shadow-sm btn-kurang" style="width: 30px; height: 30px;">-</button>
                                    <input type="number" name="pesanan[{{ $menu->id }}]" class="form-control form-control-sm text-center border-0 bg-transparent fw-bold" value="0" min="0" readonly>
                                    <button type="button" class="btn btn-sm btn-white rounded-circle shadow-sm btn-tambah" style="width: 30px; height: 30px;">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                        <div class="col-12 text-center py-5 text-muted">Belum ada menu.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.querySelectorAll('.btn-tambah').forEach(btn => {
        btn.addEventListener('click', function() {
            let input = this.previousElementSibling;
            input.value = parseInt(input.value) + 1;
        });
    });

    document.querySelectorAll('.btn-kurang').forEach(btn => {
        btn.addEventListener('click', function() {
            let input = this.nextElementSibling;
            if (parseInt(input.value) > 0) {
                input.value = parseInt(input.value) - 1;
            }
        });
    });
</script>

<style>
    .list-group-item-action:hover { background-color: #f8f9fa; color: #0d6efd !important; }
    input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    .sticky-top { z-index: 10; }
</style>
@endsection
