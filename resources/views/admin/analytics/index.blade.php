@extends('layouts.app')

@section('title', 'Rekap Okupansi & Kerusakan - Admin')

@section('content')
<div class="mx-auto max-w-7xl">

    {{-- Header & Tombol Export --}}
    <div class="mb-8 flex flex-col gap-4 border-b border-[#dedbd3] pb-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#2f625b]">
                Admin Analytics
            </p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-[#263634] sm:text-4xl">
                Rekap Okupansi & Kerusakan
            </h1>
            <p class="mt-2 text-base text-[#68736f]">
                Pantau tingkat penggunaan fasilitas dan frekuensi laporan kerusakan per lokasi.
            </p>
        </div>

        <div class="flex shrink-0 items-center">
            <a href="{{ route('admin.analytics.exportCsv') }}"
               class="inline-flex w-fit items-center justify-center rounded-lg bg-[#2f625b] px-5 py-2.5 text-base font-semibold text-white transition hover:bg-[#244d48]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Export Rekap CSV</span>
            </a>
        </div>
    </div>

    {{-- Metric Cards Ringkasan --}}
    <div class="mb-8 grid grid-cols-1 gap-5 sm:grid-cols-3">
        <div class="border border-[#dedbd3] bg-white p-6 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-[#737d79]">Total Fasilitas</p>
            <p class="mt-2 text-3xl font-extrabold text-[#263634]">{{ $facilities->count() }}</p>
        </div>
        <div class="border border-[#dedbd3] bg-white p-6 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-[#2f625b]">Total Reservasi Disetujui</p>
            <p class="mt-2 text-3xl font-extrabold text-[#2f625b]">{{ $facilities->sum('total_reservations') }}</p>
        </div>
        <div class="border border-[#dedbd3] bg-white p-6 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-[#a65f3e]">Total Laporan Kerusakan</p>
            <p class="mt-2 text-3xl font-extrabold text-[#a65f3e]">{{ $facilities->sum('total_reports') }}</p>
        </div>
    </div>

    {{-- Tabel Rekapitulasi Desktop --}}
    <div class="hidden overflow-hidden border border-[#dedbd3] bg-white md:block">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="border-b border-[#e4e1da] bg-[#f7f6f2]">
                    <tr>
                        <th class="px-5 py-4 text-xs font-bold uppercase text-[#737d79]">Fasilitas & Lokasi</th>
                        <th class="px-5 py-4 text-xs font-bold uppercase text-[#737d79]">Kapasitas</th>
                        <th class="px-5 py-4 text-xs font-bold uppercase text-[#737d79]">Status</th>
                        <th class="px-5 py-4 text-xs font-bold uppercase text-[#737d79]">Total Okupansi</th>
                        <th class="px-5 py-4 text-xs font-bold uppercase text-[#737d79]">Frekuensi Kerusakan</th>
                        <th class="px-5 py-4 text-xs font-bold uppercase text-[#737d79]">Rasio Kerusakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#ece9e3]">
                    @forelse ($facilities as $facility)
                        <tr class="transition hover:bg-[#fafaf8]">
                            <td class="px-5 py-4">
                                <p class="font-bold text-[#263634]">{{ $facility->name }}</p>
                                <p class="text-sm text-[#7b8581]">{{ $facility->location }}</p>
                            </td>
                            <td class="px-5 py-4 text-sm font-semibold text-[#43504d]">
                                {{ $facility->capacity }} Orang
                            </td>
                            <td class="px-5 py-4">
                                @if ($facility->status === 'AVAILABLE')
                                    <span class="inline-flex rounded-full bg-[#e6f0ea] px-3 py-1 text-xs font-bold text-[#426b5a]">Aktif</span>
                                @elseif ($facility->status === 'MAINTENANCE')
                                    <span class="inline-flex rounded-full bg-[#f4e9dd] px-3 py-1 text-xs font-bold text-[#a65f3e]">Perbaikan</span>
                                @else
                                    <span class="inline-flex rounded-full bg-[#eee8e5] px-3 py-1 text-xs font-bold text-[#765f59]">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 font-bold text-[#2f625b]">
                                {{ $facility->total_reservations }} Kali Dipinjam
                            </td>
                            <td class="px-5 py-4 font-bold text-[#a65f3e]">
                                {{ $facility->total_reports }} Laporan
                            </td>
                            <td class="px-5 py-4 text-sm font-semibold text-[#596460]">
                                @if ($facility->total_reservations > 0)
                                    {{ round(($facility->total_reports / $facility->total_reservations) * 100, 1) }}%
                                @else
                                    0%
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-[#7b8581]">Belum ada data fasilitas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tampilan Mobile --}}
    <div class="divide-y divide-[#ece9e3] border border-[#dedbd3] bg-white md:hidden">
        @foreach ($facilities as $facility)
            <div class="p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-[#263634]">{{ $facility->name }}</h2>
                        <p class="text-xs text-[#7b8581]">{{ $facility->location }}</p>
                    </div>
                    <span class="text-xs font-bold text-[#43504d]">{{ $facility->capacity }} Orang</span>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-2 text-sm border-t border-[#eeeae4] pt-3">
                    <div>
                        <p class="text-xs text-[#7b8581]">Total Okupansi</p>
                        <p class="font-bold text-[#2f625b]">{{ $facility->total_reservations }} Dipinjam</p>
                    </div>
                    <div>
                        <p class="text-xs text-[#7b8581]">Laporan Kerusakan</p>
                        <p class="font-bold text-[#a65f3e]">{{ $facility->total_reports }} Kerusakan</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection