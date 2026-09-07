@extends('layouts.app')
@section('title', 'Buat Akun Staff/Pengguna')
@section('content')
<h2>Daftarkan Akun (langsung aktif)</h2>
<form method="POST" action="{{ route('admin.users.store') }}">
    @csrf
    <label for="name">Nama</label>
    <input id="name" type="text" name="name" value="{{ old('name') }}" required maxlength="255">

    <label for="email">Email</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required>

    <label for="role">Role</label>
    <select id="role" name="role" required>
        <option value="STAFF" @selected(old('role') === 'STAFF')>STAFF (petugas)</option>
        <option value="USER" @selected(old('role', 'USER') === 'USER')>USER (mahasiswa/dosen/staf)</option>
    </select>

    <label for="password">Password (min. 6)</label>
    <input id="password" type="password" name="password" required minlength="6">

    <label for="password_confirmation">Konfirmasi Password</label>
    <input id="password_confirmation" type="password" name="password_confirmation" required minlength="6">

    <button type="submit">Buat Akun</button>
</form>
@endsection
