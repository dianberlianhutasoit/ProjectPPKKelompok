@extends('layouts.app')
@section('title', 'Daftar Fasilitas')
@section('content')
<h2>Daftar Fasilitas</h2>
<p>Status <b>Tersedia</b> = AVAILABLE. <b>Tidak tersedia</b> = MAINTENANCE. Tanpa detail pemohon/tujuan.</p>

{{-- Form cari: filter tipe / lokasi / kapasitas --}}
<form method="GET" action="{{ route('facilities.index') }}">
    <label for="type">Tipe</label>
    <select id="type" name="type">
        <option value="">-- Semua tipe --</option>
        @foreach($types as $t)
            <option value="{{ $t }}" @selected(($filters['type'] ?? '') === $t)>{{ $t }}</option>
        @endforeach
    </select>

    <label for="location">Lokasi (sebagian nama gedung)</label>
    <input id="location" type="text" name="location" value="{{ $filters['location'] ?? '' }}" maxlength="255" placeholder="mis. Gedung A">

    <label for="min_capacity">Kapasitas minimal</label>
    <input id="min_capacity" type="number" name="min_capacity" value="{{ $filters['min_capacity'] ?? '' }}" min="1">

    <button type="submit">Cari</button>
    <a href="{{ route('facilities.index') }}">Reset</a>
</form>

@auth
@if(auth()->user()->role === 'ADMIN')
<p><a href="{{ route('facilities.create') }}"><button type="button">+ Tambah Fasilitas</button></a></p>
@endif
@endauth

<table>
    <thead><tr><th>Nama</th><th>Tipe</th><th>Lokasi</th><th>Kapasitas</th><th>Ketersediaan</th><th>Aksi</th></tr></thead>
    <tbody>
    @forelse($facilities as $f)
        <tr>
            <td>{{ $f->name }}</td>
            <td>{{ $f->type }}</td>
            <td>{{ $f->location }}</td>
            <td>{{ $f->capacity }}</td>
            <td>
                @if($f->status === 'AVAILABLE')
                    <span class="badge ACTIVE">Tersedia</span>
                @elseif($f->status === 'MAINTENANCE')
                    <span class="badge REJECTED">Tidak tersedia (perbaikan)</span>
                @else
                    <span class="badge PENDING">Nonaktif</span>
                @endif
            </td>
            <td>
                <a href="{{ route('facilities.show', $f) }}">Detail</a>
                @auth
                @if(auth()->user()->role === 'ADMIN')
                    | <a href="{{ route('facilities.edit', $f) }}">Edit</a>
                    @if($f->status !== 'INACTIVE')
                    | <form action="{{ route('facilities.destroy', $f) }}" method="POST" style="display:inline" onsubmit="return confirm('Nonaktifkan fasilitas ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" style="margin:0;padding:4px 8px">Nonaktifkan</button>
                      </form>
                    @endif
                @endif
                @endauth
            </td>
        </tr>
    @empty
        <tr><td colspan="6">Tidak ada fasilitas yang cocok.</td></tr>
    @endforelse
    </tbody>
</table>
@endsection
