@extends('layouts.app')
@section('title', 'Edit Fasilitas')
@section('content')
<h2>Edit Fasilitas: {{ $facility->name }}</h2>
<form method="POST" action="{{ route('facilities.update', $facility) }}">
    @csrf @method('PUT')
    <label for="name">Nama fasilitas</label>
    <input id="name" type="text" name="name" value="{{ old('name', $facility->name) }}" required maxlength="255">

    <label for="type">Tipe</label>
    <input id="type" type="text" name="type" value="{{ old('type', $facility->type) }}" required maxlength="100">

    <label for="location">Lokasi</label>
    <input id="location" type="text" name="location" value="{{ old('location', $facility->location) }}" required maxlength="255">

    <label for="capacity">Kapasitas</label>
    <input id="capacity" type="number" name="capacity" value="{{ old('capacity', $facility->capacity) }}" required min="1">

    <label for="description">Deskripsi</label>
    <input id="description" type="text" name="description" value="{{ old('description', $facility->description) }}">

    <label for="status">Status</label>
    <select id="status" name="status" required>
        <option value="AVAILABLE" @selected(old('status', $facility->status) === 'AVAILABLE')>AVAILABLE</option>
        <option value="MAINTENANCE" @selected(old('status', $facility->status) === 'MAINTENANCE')>MAINTENANCE</option>
        <option value="INACTIVE" @selected(old('status', $facility->status) === 'INACTIVE')>INACTIVE (nonaktif)</option>
    </select>

    <button type="submit">Perbarui</button>
</form>
@endsection
