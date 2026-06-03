@extends('layouts.app', ['title' => 'Notifikasi dan Komunikasi'])

@section('content')
<section class="hero">
    <div>
        <p class="eyebrow">Service 3</p>
        <h1>Notifikasi dan Komunikasi</h1>
    </div>
</section>

<section class="grid two">
    <form class="panel" method="post" action="{{ route('notifications.store') }}">
        @csrf
        <h2>Buat Notifikasi</h2>
        <label>Kanal
            <select name="channel" required>
                <option value="email">Email</option>
                <option value="whatsapp">WhatsApp</option>
                <option value="internal">Internal</option>
            </select>
        </label>
        <label>Penerima <input name="recipient" required value="{{ old('recipient') }}"></label>
        <label>Subjek <input name="subject" required value="{{ old('subject') }}"></label>
        <label>Pesan <textarea name="body" required>{{ old('body') }}</textarea></label>
        <button>Simpan Draft</button>
    </form>

    <div class="panel">
        <h2>Barang Perlu Perhatian</h2>
        <div class="list">
            @forelse ($lowStockItems as $item)
                <div>
                    <strong>{{ $item->name }}</strong>
                    <span>Stok {{ $item->quantity }} {{ $item->unit }}, minimum {{ $item->minimum_stock }}</span>
                </div>
            @empty
                <p>Tidak ada stok menipis.</p>
            @endforelse
        </div>
    </div>
</section>

<section class="panel">
    <h2>Riwayat Pesan</h2>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Kanal</th><th>Penerima</th><th>Subjek</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse ($messages as $message)
                <tr>
                    <td>{{ ucfirst($message->channel) }}</td>
                    <td>{{ $message->recipient }}</td>
                    <td>{{ $message->subject }}</td>
                    <td><span class="badge">{{ $message->status }}</span></td>
                    <td>
                        @if ($message->status !== 'sent')
                            <form method="post" action="{{ route('notifications.sent', $message) }}">
                                @csrf
                                @method('patch')
                                <button>Tandai Terkirim</button>
                            </form>
                        @else
                            {{ optional($message->sent_at)->format('d M Y H:i') }}
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">Belum ada pesan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
