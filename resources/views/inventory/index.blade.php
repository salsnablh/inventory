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
        <div
            class="upload-field"
            data-cloud-name="{{ $cloudinary['cloudName'] }}"
            data-upload-preset="{{ $cloudinary['uploadPreset'] }}"
        >
            <label>Foto Barang <input id="item-photo" type="file" accept="image/*"></label>
            <input id="photo-url" type="hidden" name="photo_url" value="{{ old('photo_url') }}">
            <input id="photo-public-id" type="hidden" name="photo_public_id" value="{{ old('photo_public_id') }}">
            <div class="upload-preview" id="upload-preview" @if (! old('photo_url')) hidden @endif>
                <img id="photo-preview" src="{{ old('photo_url') }}" alt="Preview foto barang">
                <button id="remove-photo" class="button secondary" type="button">Hapus Foto</button>
            </div>
            <small id="upload-status">Foto akan disimpan di Cloudinary, bukan di server aplikasi.</small>
        </div>
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
                <th>Foto</th>
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
                    <td>
                        @if ($item->photo_url)
                            <img
                                class="item-photo"
                                src="{{ $item->photo_thumbnail_url }}"
                                width="58"
                                height="58"
                                alt="Foto {{ $item->name }}"
                            >
                        @else
                            <span class="muted">-</span>
                        @endif
                    </td>
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
                <tr><td colspan="8">Belum ada barang.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const uploadField = document.querySelector('.upload-field');
    const fileInput = document.getElementById('item-photo');
    const photoUrlInput = document.getElementById('photo-url');
    const photoPublicIdInput = document.getElementById('photo-public-id');
    const previewWrap = document.getElementById('upload-preview');
    const previewImage = document.getElementById('photo-preview');
    const removeButton = document.getElementById('remove-photo');
    const status = document.getElementById('upload-status');

    if (!uploadField || !fileInput) {
        return;
    }

    const cloudName = uploadField.dataset.cloudName;
    const uploadPreset = uploadField.dataset.uploadPreset;

    const setStatus = function (message, isError) {
        status.textContent = message;
        status.classList.toggle('error-text', Boolean(isError));
    };

    const clearPhoto = function () {
        fileInput.value = '';
        photoUrlInput.value = '';
        photoPublicIdInput.value = '';
        previewImage.removeAttribute('src');
        previewWrap.hidden = true;
        setStatus('Foto akan disimpan di Cloudinary, bukan di server aplikasi.', false);
    };

    removeButton.addEventListener('click', clearPhoto);

    fileInput.addEventListener('change', async function () {
        const file = fileInput.files[0];

        if (!file) {
            clearPhoto();
            return;
        }

        if (!cloudName || !uploadPreset) {
            clearPhoto();
            setStatus('Isi CLOUDINARY_CLOUD_NAME dan CLOUDINARY_UPLOAD_PRESET di .env terlebih dahulu.', true);
            return;
        }

        const formData = new FormData();
        formData.append('file', file);
        formData.append('upload_preset', uploadPreset);

        setStatus('Mengunggah foto ke Cloudinary...', false);

        try {
            const response = await fetch(`https://api.cloudinary.com/v1_1/${cloudName}/image/upload`, {
                method: 'POST',
                body: formData,
            });
            const payload = await response.json();

            if (!response.ok) {
                throw new Error(payload.error?.message || 'Upload gagal.');
            }

            photoUrlInput.value = payload.secure_url;
            photoPublicIdInput.value = payload.public_id;
            previewImage.src = payload.secure_url;
            previewWrap.hidden = false;
            setStatus('Foto berhasil tersimpan di Cloudinary.', false);
        } catch (error) {
            clearPhoto();
            setStatus(error.message || 'Upload foto gagal.', true);
        }
    });
});
</script>
@endsection
