<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Inventory' }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
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
    <div class="auth-actions">
        @auth
            <span>{{ auth()->user()->name }}</span>
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit">Logout</button>
            </form>
        @else
            <a class="button" href="{{ route('login.google') }}">Login Google</a>
        @endauth
    </div>
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
