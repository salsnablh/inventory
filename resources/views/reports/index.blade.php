@extends('layouts.app', ['title' => 'Cetak Laporan'])

@section('content')
<section class="hero">
    <div>
        <p class="eyebrow">Service 2</p>
        <h1>Cetak Laporan Inventory</h1>
    </div>
    <a class="button" href="{{ route('reports.export') }}">Unduh CSV</a>
</section>

<section class="stats">
    <div><strong>{{ $totalItems }}</strong><span>Jenis Barang</span></div>
    <div><strong>{{ $totalStock }}</strong><span>Total Stok</span></div>
    <div><strong>{{ $lowStockItems->count() }}</strong><span>Stok Menipis</span></div>
</section>

<section class="panel">
    <h2>Ringkasan Stok</h2>
    <div class="table-wrap">
        <table>
            <thead><tr><th>SKU</th><th>Nama</th><th>Jumlah</th><th>Minimum</th><th>Status</th></tr></thead>
            <tbody>
            @forelse ($items as $item)
                <tr>
                    <td>{{ $item->sku }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->quantity }} {{ $item->unit }}</td>
                    <td>{{ $item->minimum_stock }}</td>
                    <td><span class="{{ $item->is_low_stock ? 'badge warn' : 'badge' }}">{{ $item->is_low_stock ? 'Menipis' : 'Aman' }}</span></td>
                </tr>
            @empty
                <tr><td colspan="5">Belum ada data untuk laporan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</section>

<section class="panel">
    <h2>Audit Pergerakan</h2>
    <div class="list">
        @forelse ($movements as $movement)
            <div>
                <strong>{{ $movement->item->name }}</strong>
                <span>{{ $movement->type === 'in' ? 'Masuk' : 'Keluar' }} {{ $movement->quantity }} {{ $movement->item->unit }}</span>
                <small>{{ $movement->created_at->format('d M Y H:i') }}</small>
            </div>
        @empty
            <p>Belum ada audit pergerakan.</p>
        @endforelse
    </div>
</section>
@endsection
