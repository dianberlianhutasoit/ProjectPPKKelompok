@extends('layouts.app')
@section('title', 'Registrasi Pengguna')
@section('content')
<h2>Registrasi Akun Pengguna</h2>
<p>Hanya untuk mahasiswa/dosen/staf. Petugas tidak boleh registrasi mandiri. Akun menunggu verifikasi admin.</p>
{{-- Cek required + min 6 karakter di browser, cek lengkapnya di AuthController@register --}}
<form method="POST" action="{{ route('register') }}">
    @csrf
    <label for="name">Nama</label>
    <input id="name" type="text" name="name" value="{{ old('name') }}" required maxlength="255">

    <label for="email">Email</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required>

    <label for="password">Password (min. 6)</label>
    <input id="password" type="password" name="password" required minlength="6">

    <label for="password_confirmation">Konfirmasi Password</label>
    <input id="password_confirmation" type="password" name="password_confirmation" required minlength="6">

    <button type="submit">Daftar</button>
</form>
@endsection
