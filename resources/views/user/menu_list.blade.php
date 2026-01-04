@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">

        <div class="col-md-2 d-none d-md-block pe-4">
            <h5 class="fw-bold mb-3 text-primary">Kategori</h5>

            <div class="list-group list-group-flush">
                <a href="{{ route('user.menus') }}"
                   class="list-group-item list-group-item-action border-0 bg-transparent py-2 ps-0 {{ !request('kategori') ? 'text-primary fw-bold' : 'text-muted' }}">
                    <i class="bi bi-grid-fill me-2"></i> Semua Menu
                </a>

                <a href="{{ route('user.menus', ['kategori' => 'kopi']) }}"
                   class="list-group-item list-group-item-action border-0 bg-transparent py-2 ps-0 {{ request('kategori') == 'kopi' ? 'text-primary fw-bold' : 'text-muted' }}">
                    <i class="bi bi-cup-hot me-2"></i> Kopi
                </a>

                <a href="{{ route('user.menus', ['kategori' => 'non-kopi']) }}"
                   class="list-group-item list-group-item-action border-0 bg-transparent py-2 ps-0 {{ request('kategori') == 'non-kopi' ? 'text-primary fw-bold' : 'text-muted' }}">
                    <i class="bi bi-cup-straw me-2"></i> Non-Kopi
                </a>

                <a href="{{ route('user.menus', ['kategori' => 'makanan']) }}"
                   class="list-group-item list-group-item-action border-0 bg-transparent py-2 ps-0 {{ request('kategori') == 'makanan' ? 'text-primary fw-bold' : 'text-muted' }}">
                    <i class="bi bi-egg-fried me-2"></i> Makanan
                </a>

                <a href="{{ route('user.menus', ['kategori' => 'snack']) }}"
                   class="list-group-item list-group-item-action border-0 bg-transparent py-2 ps-0 {{ request('kategori') == 'snack' ? 'text-primary fw-bold' : 'text-muted' }}">
                    <i class="bi bi-cookie me-2"></i> Snack
                </a>
            </div>
        </div>

        <div class="col-md-10">

            <div class="card border-0 shadow-sm mb-4 bg-primary text-white overflow-hidden" style="border-radius: 1rem;">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="fw-bold">Diskon Spesial Hari Ini!</h2>
                        <p class="mb-0">Gunakan kode promo untuk hemat lebih banyak.</p>
                    </div>
                    <i class="bi bi-bag-heart-fill" style="font-size: 4rem; opacity: 0.8;"></i>
                </div>
            </div>

            <form action="{{ route('user.checkout') }}" method="POST">
                @csrf

                <h4 class="fw-bold mb-4">Pilih Menu Favoritmu</h4>

                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4">
                    @forelse($menus as $menu)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm hover-card" style="border-radius: 12px; transition: 0.3s;">

                            <div class="card-img-top overflow-hidden bg-light d-flex align-items-center justify-content-center" style="height: 160px; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                                @if($menu->foto)
                                    <img src="{{ asset('storage/' . $menu->foto) }}" alt="{{ $menu->nama_menu }}" class="w-100 h-100 object-fit-cover">
                                @else
                                    <i class="bi bi-image text-muted fs-1"></i>
                                @endif
                            </div>

                            <div class="card-body d-flex flex-column p-3">
                                <h6 class="card-title fw-bold mb-1 text-truncate">{{ $menu->nama_menu }}</h6>
                                <small class="text-muted mb-2">{{ ucfirst($menu->kategori) }}</small>

                                <h6 class="text-primary fw-bold mb-3">Rp {{ number_format($menu->harga, 0, ',', '.') }}</h6>

                                <div class="mt-auto d-flex justify-content-between align-items-center bg-light rounded-pill p-1 border">
                                    <button type="button" class="btn btn-sm btn-white rounded-circle text-primary fw-bold btn-kurang shadow-sm" style="width: 28px; height: 28px;">-</button>

                                    <input type="number" name="pesanan[{{ $menu->id }}]" class="form-control form-control-sm text-center border-0 bg-transparent p-0 input-jumlah fw-bold" value="0" min="0" style="width: 40px;">

                                    <button type="button" class="btn btn-sm btn-white rounded-circle text-primary fw-bold btn-tambah shadow-sm" style="width: 28px; height: 28px;">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <div class="text-muted mb-2"><i class="bi bi-search fs-1"></i></div>
                        <h5 class="text-muted">Belum ada menu untuk kategori ini.</h5>
                    </div>
                    @endforelse
                </div>

                <div class="fixed-bottom bg-white border-top shadow-lg p-3" style="z-index: 100;">
                    <div class="container d-flex justify-content-between align-items-center gap-3">

                        <div class="input-group" style="max-width: 250px;">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-ticket-perforated-fill text-warning"></i></span>
                            <input type="text" name="kode_promo" class="form-control bg-light border-0" placeholder="Kode Promo" style="text-transform:uppercase;">
                        </div>

                        <button type="submit" class="btn btn-primary fw-bold px-5 rounded-pill shadow">
                            Checkout <i class="bi bi-arrow-right-circle-fill ms-2"></i>
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
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
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
</style>
@endsection
