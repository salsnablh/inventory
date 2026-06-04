@extends('layouts.app', ['title' => 'Login'])

@section('content')
<section class="login-panel">
    <div>
        <p class="eyebrow">Login</p>
        <h1>Masuk ke Inventory</h1>
        <p class="lead">Gunakan akun Google yang terdaftar untuk mengakses dashboard dan pencatatan stok.</p>
    </div>

    <a class="button" href="{{ route('login.google') }}">Login dengan Google</a>
</section>
@endsection
