@extends('layouts.app')
@section('title', 'Tambah Fasilitas')
@section('content')
<h2>Tambah Fasilitas</h2>
{{-- Cek required + min kapasitas di browser, cek lengkapnya di FacilityController@store --}}
<form method="POST" action="{{ route('facilities.store') }}">
    @csrf
    <label for="name">Nama fasilitas</label>
    <input id="name" type="text" name="name" value="{{ old('name') }}" required maxlength="255">

    <label for="type">Tipe (mis. Classroom, Laboratory, Aula, Lapangan, Alat)</label>
    <input id="type" type="text" name="type" value="{{ old('type') }}" required maxlength="100">

    <label for="location">Lokasi</label>
    <input id="location" type="text" name="location" value="{{ old('location') }}" required maxlength="255">

    <label for="capacity">Kapasitas</label>
    <input id="capacity" type="number" name="capacity" value="{{ old('capacity') }}" required min="1">

    <label for="description">Deskripsi</label>
    <input id="description" type="text" name="description" value="{{ old('description') }}">

    <label for="status">Status</label>
    <select id="status" name="status" required>
        <option value="AVAILABLE" @selected(old('status', 'AVAILABLE') === 'AVAILABLE')>AVAILABLE</option>
        <option value="MAINTENANCE" @selected(old('status') === 'MAINTENANCE')>MAINTENANCE</option>
        <option value="INACTIVE" @selected(old('status') === 'INACTIVE')>INACTIVE</option>
    </select>

    <button type="submit">Simpan</button>
</form>
@endsection
