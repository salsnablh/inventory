@extends('layouts.app', ['title' => 'Pencatatan Inventory'])

@section('content')
<section class="hero">
    <div>
        <p class="eyebrow">Service 1</p>
        <h1>Pencatatan Barang dan Stok</h1>
    </div>
</section>

<section class="grid two">
    <form class="panel" method="post" action="{{ route('inventory.store') }}">
        @csrf
        <h2>Tambah Barang</h2>
        <label>SKU <input name="sku" required value="{{ old('sku') }}"></label>
        <label>Nama <input name="name" required value="{{ old('name') }}"></label>
        <label>Jumlah <input name="quantity" type="number" min="0" required value="{{ old('quantity', 0) }}"></label>
        <label>Satuan <input name="unit" required value="{{ old('unit', 'pcs') }}"></label>
        <label>Stok Minimum <input name="minimum_stock" type="number" min="0" required value="{{ old('minimum_stock', 0) }}"></label>
        <label>Lokasi <input name="location" value="{{ old('location') }}"></label>
        <label>Catatan <textarea name="notes">{{ old('notes') }}</textarea></label>
        <button>Tambah</button>
    </form>

    <div class="panel">
        <h2>Pergerakan Terakhir</h2>
        <div class="list">
            @forelse ($movements as $movement)
                <div>
                    <strong>{{ $movement->item->name }}</strong>
                    <span>{{ $movement->type === 'in' ? 'Masuk' : 'Keluar' }} {{ $movement->quantity }} {{ $movement->item->unit }}</span>
                    <small>{{ $movement->created_at->format('d M Y H:i') }} - {{ $movement->actor ?? 'Sistem' }}</small>
                </div>
            @empty
                <p>Belum ada pergerakan stok.</p>
            @endforelse
        </div>
    </div>
</section>

<section class="panel">
    <h2>Daftar Barang</h2>
    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>SKU</th>
                <th>Nama</th>
                <th>Stok</th>
                <th>Minimum</th>
                <th>Lokasi</th>
                <th>Pergerakan</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($items as $item)
                <tr>
                    <td>{{ $item->sku }}</td>
                    <td>{{ $item->name }}</td>
                    <td><span class="{{ $item->is_low_stock ? 'badge warn' : 'badge' }}">{{ $item->quantity }} {{ $item->unit }}</span></td>
                    <td>{{ $item->minimum_stock }}</td>
                    <td>{{ $item->location ?? '-' }}</td>
                    <td>
                        <form class="inline-form" method="post" action="{{ route('inventory.movement', $item) }}">
                            @csrf
                            <select name="type">
                                <option value="in">Masuk</option>
                                <option value="out">Keluar</option>
                            </select>
                            <input name="quantity" type="number" min="1" value="1" aria-label="Jumlah">
                            <input name="actor" placeholder="Petugas" aria-label="Petugas">
                            <button>Catat</button>
                        </form>
                    </td>
                    <td>
                        <form method="post" action="{{ route('inventory.destroy', $item) }}">
                            @csrf
                            @method('delete')
                            <button class="danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">Belum ada barang.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
