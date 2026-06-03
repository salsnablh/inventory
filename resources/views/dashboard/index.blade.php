@extends('layouts.app', ['title' => 'Dashboard Inventory'])

@section('content')
<section class="hero dashboard-hero">
    <div>
        <p class="eyebrow">Dashboard</p>
        <h1>Ringkasan Inventory</h1>
        <p class="lead">Pantau stok, laporan, dan komunikasi dari satu halaman awal.</p>
    </div>
    <a class="button" href="{{ route('inventory.index') }}">Mulai Pencatatan</a>
</section>

<section class="stats">
    <div><strong>{{ $totalItems }}</strong><span>Jenis Barang</span></div>
    <div><strong>{{ $totalStock }}</strong><span>Total Stok</span></div>
    <div><strong>{{ $lowStockItems->count() }}</strong><span>Stok Menipis</span></div>
    <div><strong>{{ $draftMessages }}</strong><span>Draft Notifikasi</span></div>
</section>

<section class="service-grid">
    <a class="service-card" href="{{ route('inventory.index') }}">
        <span>Service 1</span>
        <strong>Pencatatan</strong>
        <small>Tambah barang, catat stok masuk, dan stok keluar.</small>
    </a>
    <a class="service-card" href="{{ route('reports.index') }}">
        <span>Service 2</span>
        <strong>Cetak Laporan</strong>
        <small>Lihat ringkasan stok dan unduh laporan CSV.</small>
    </a>
    <a class="service-card" href="{{ route('notifications.index') }}">
        <span>Service 3</span>
        <strong>Notifikasi dan Komunikasi</strong>
        <small>Buat pesan untuk barang yang perlu perhatian.</small>
    </a>
</section>

<section class="grid two">
    <div class="panel">
        <h2>Stok Menipis</h2>
        <div class="list">
            @forelse ($lowStockItems as $item)
                <div>
                    <strong>{{ $item->name }}</strong>
                    <span>{{ $item->quantity }} {{ $item->unit }} tersisa, minimum {{ $item->minimum_stock }}</span>
                </div>
            @empty
                <p>Semua stok masih aman.</p>
            @endforelse
        </div>
    </div>

    <div class="panel">
        <h2>Aktivitas Terakhir</h2>
        <div class="list">
            @forelse ($recentMovements as $movement)
                <div>
                    <strong>{{ $movement->item->name }}</strong>
                    <span>{{ $movement->type === 'in' ? 'Masuk' : 'Keluar' }} {{ $movement->quantity }} {{ $movement->item->unit }}</span>
                    <small>{{ $movement->created_at->format('d M Y H:i') }}</small>
                </div>
            @empty
                <p>Belum ada aktivitas stok.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
