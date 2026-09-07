@extends('layouts.app')
@section('title', 'Detail Fasilitas')
@section('content')
<h2>{{ $facility->name }}</h2>
<table>
    <tr><th>Tipe</th><td>{{ $facility->type }}</td></tr>
    <tr><th>Lokasi</th><td>{{ $facility->location }}</td></tr>
    <tr><th>Kapasitas</th><td>{{ $facility->capacity }} orang</td></tr>
    <tr><th>Deskripsi</th><td>{{ $facility->description ?? '-' }}</td></tr>
    <tr><th>Ketersediaan</th><td>
        @if($facility->status === 'AVAILABLE')
            <span class="badge ACTIVE">Tersedia</span>
        @elseif($facility->status === 'MAINTENANCE')
            <span class="badge REJECTED">Tidak tersedia (dalam perbaikan)</span>
        @else
            <span class="badge PENDING">Nonaktif</span>
        @endif
    </td></tr>
</table>
<p><a href="{{ route('facilities.index') }}">Kembali ke daftar</a></p>
@auth
@if(auth()->user()->role === 'ADMIN')
<p><a href="{{ route('facilities.edit', $facility) }}"><button type="button">Edit</button></a></p>
@endif
@endauth
{{-- Nanti di tahap reservasi: tambah info jadwal per 30 menit di sini (07.00-20.00) --}}
@endsection
