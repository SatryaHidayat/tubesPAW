@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">Daftar Menu</h2>
        <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Menu
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Foto</th>
                        <th>Nama Menu</th>
                        <th>Harga</th>
                        <th>Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menus as $menu)
                    <tr>
                        <td>
                            <img src="{{ asset('storage/' . $menu->foto) }}" alt="foto" width="80" class="rounded shadow-sm">
                        </td>
                        <td class="fw-bold">{{ $menu->nama_menu }}</td>
                        <td>Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                        <td>
                            @php
                                $badgeColor = match($menu->kategori) {
                                    'kopi' => 'bg-dark text-white',
                                    'non-kopi' => 'bg-info text-dark',
                                    'makanan' => 'bg-warning text-dark',
                                    'snack' => 'bg-success text-white',
                                    default => 'bg-secondary text-white',
                                };
                            @endphp

                            <span class="badge {{ $badgeColor }}">
                                {{ strtoupper(str_replace('-', ' ', $menu->kategori)) }}
                            </span>
                        </td>
                        <td>
                            <form onsubmit="return confirm('Yakin hapus menu ini?');" action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada menu tersedia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
