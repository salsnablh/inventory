<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Inventory' }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<header class="topbar">
    <div>
        <strong>Inventory Salsa Nabillah</strong>
        <span>{{ env('SERVICE_NAME', 'inventory') }}</span>
    </div>
    <nav>
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('inventory.index') }}">Pencatatan</a>
        <a href="{{ route('reports.index') }}">Cetak Laporan</a>
        <a href="{{ route('notifications.index') }}">Notifikasi</a>
    </nav>
</header>

<main class="page">
    @if (session('status'))
        <div class="alert">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @yield('content')
</main>
</body>
</html>
