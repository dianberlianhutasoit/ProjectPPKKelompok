@extends('layouts.app')
@section('title', 'Login')
@section('content')
<h2>Login</h2>
{{-- Cek required + email di browser, cek lengkapnya di AuthController@login --}}
<form method="POST" action="{{ route('login') }}">
    @csrf
    <label for="email">Email</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>

    <label for="password">Password</label>
    <input id="password" type="password" name="password" required minlength="6">

    <label style="font-weight:normal"><input type="checkbox" name="remember" value="1" style="width:auto"> Ingat saya</label>

    <button type="submit">Login</button>
</form>
<p>Belum punya akun? <a href="{{ route('register') }}">Registrasi (khusus pengguna)</a></p>
@endsection
