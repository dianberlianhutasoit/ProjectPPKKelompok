@extends('layouts.app')

@section('title', 'Kelola Laporan - Campus Facility System')

@section('content')

<div class="mx-auto max-w-7xl">

    {{-- Header --}}
    <div class="mb-7">

        <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#2f625b]">
            Staff Workspace
        </p>

        <h1 class="mt-2 text-4xl font-bold tracking-tight text-[#263634]">
            Kelola Laporan
        </h1>

        <p class="mt-2 max-w-2xl text-base leading-6 text-[#68736f]">
            Periksa laporan kerusakan, perbarui status penanganan,
            dan kelola kondisi fasilitas berdasarkan proses perbaikan.
        </p>

    </div>


    {{-- Success --}}
    @if(session('success'))

        <div class="mb-5 border border-[#cfe0d7] bg-[#edf5f0] px-4 py-3 text-base font-medium text-[#426b5a]">
            {{ session('success') }}
        </div>

    @endif


    {{-- Error --}}
    @if($errors->any())

        <div class="mb-5 border border-[#e6d0c5] bg-[#fbf0eb] px-4 py-3 text-base font-medium text-[#a65f3e]">
            {{ $errors->first() }}
        </div>

    @endif


    @if($reports->count())

        {{-- ========================= --}}
        {{-- DESKTOP --}}
        {{-- ========================= --}}
        <div class="hidden overflow-hidden border border-[#dedbd3] bg-white md:block">

            <div class="overflow-x-auto">

                <table class="w-full min-w-[1200px] text-left">

                    <thead class="border-b border-[#e4e1da] bg-[#f7f6f2]">

                        <tr>

                            <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-[#737d79]">
                                Pelapor
                            </th>

                            <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-[#737d79]">
                                Fasilitas
                            </th>

                            <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-[#737d79]">
                                Kerusakan
                            </th>

                            <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-[#737d79]">
                                Status Laporan
                            </th>

                            <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-[#737d79]">
                                Status Fasilitas
                            </th>

                            <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-[#737d79]">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-[#ece9e3]">

                        @foreach($reports as $report)

                            <tr class="transition hover:bg-[#fafaf8]">

                                {{-- Pelapor --}}
                                <td class="px-5 py-5 align-top">

                                    <p class="text-base font-bold text-[#263634]">
                                        {{ $report->user->name ?? '-' }}
                                    </p>

                                    <p class="mt-1 text-sm text-[#7b8581]">
                                        {{ $report->user->email ?? '-' }}
                                    </p>

                                </td>


                                {{-- Fasilitas --}}
                                <td class="px-5 py-5 align-top">

                                    <p class="text-base font-semibold text-[#43504d]">
                                        {{ $report->facility->name ?? '-' }}
                                    </p>

                                    <p class="mt-1 text-sm text-[#7b8581]">
                                        {{ $report->facility->location ?? '-' }}
                                    </p>

                                </td>


                                {{-- Kerusakan --}}
                                <td class="max-w-[280px] px-5 py-5 align-top">

                                    <p class="text-sm font-bold text-[#2f625b]">
                                        {{ $report->category }}
                                    </p>

                                    <p class="mt-2 text-base leading-6 text-[#596460]">
                                        {{ \Illuminate\Support\Str::limit($report->description, 120) }}
                                    </p>

                                </td>


                                {{-- Status Laporan --}}
                                <td class="px-5 py-5 align-top">

                                    @if($report->status === 'NEW')

                                        <span class="inline-flex rounded-full bg-[#f4e9dd] px-3 py-1.5 text-xs font-bold text-[#99633d]">
                                            Baru
                                        </span>

                                        <p class="mt-2 max-w-[170px] text-sm leading-5 text-[#7b8581]">
                                            Menunggu penanganan petugas.
                                        </p>

                                    @elseif($report->status === 'PROCESSING')

                                        <span class="inline-flex rounded-full bg-[#e6f0ea] px-3 py-1.5 text-xs font-bold text-[#426b5a]">
                                            Diproses
                                        </span>

                                        <p class="mt-2 max-w-[170px] text-sm leading-5 text-[#7b8581]">
                                            Sedang ditangani oleh petugas.
                                        </p>

                                    @elseif($report->status === 'COMPLETED')

                                        <span class="inline-flex rounded-full bg-[#e6f0ea] px-3 py-1.5 text-xs font-bold text-[#376453]">
                                            Selesai
                                        </span>

                                        <p class="mt-2 max-w-[170px] text-sm leading-5 text-[#7b8581]">
                                            Perbaikan telah selesai.
                                        </p>

                                    @else

                                        <span class="inline-flex rounded-full bg-[#eee8e5] px-3 py-1.5 text-xs font-bold text-[#765f59]">
                                            Ditolak
                                        </span>

                                        <p class="mt-2 max-w-[170px] text-sm leading-5 text-[#7b8581]">
                                            Laporan tidak dapat diproses.
                                        </p>

                                    @endif

                                </td>


                                {{-- Status Fasilitas --}}
                                <td class="px-5 py-5 align-top">

                                    @if(($report->facility->status ?? null) === 'MAINTENANCE')

                                        <span class="inline-flex rounded-full bg-[#f4e9dd] px-3 py-1.5 text-xs font-bold text-[#a65f3e]">
                                            Dalam Perbaikan
                                        </span>

                                        <p class="mt-2 max-w-[170px] text-sm leading-5 text-[#7b8581]">
                                            Fasilitas sedang diperbaiki.
                                        </p>

                                    @elseif(($report->facility->status ?? null) === 'AVAILABLE')

                                        <span class="inline-flex rounded-full bg-[#e6f0ea] px-3 py-1.5 text-xs font-bold text-[#426b5a]">
                                            Aktif
                                        </span>

                                        <p class="mt-2 max-w-[170px] text-sm leading-5 text-[#7b8581]">
                                            Fasilitas dapat digunakan.
                                        </p>

                                    @else

                                        <span class="inline-flex rounded-full bg-[#eee8e5] px-3 py-1.5 text-xs font-bold text-[#765f59]">
                                            Tidak Aktif
                                        </span>

                                        <p class="mt-2 max-w-[170px] text-sm leading-5 text-[#7b8581]">
                                            Fasilitas tidak tersedia.
                                        </p>

                                    @endif

                                </td>


                                {{-- Aksi --}}
                                <td class="px-5 py-5 align-top">

                                    <a
                                        href="{{ route('staff.reports.show', $report) }}"
                                        class="inline-flex items-center justify-center rounded-lg bg-[#2f625b] px-4 py-2.5 text-sm font-bold text-white transition hover:bg-[#244d48]"
                                    >
                                        Kelola
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>


        {{-- ========================= --}}
        {{-- MOBILE --}}
        {{-- ========================= --}}
        <div class="divide-y divide-[#ece9e3] border border-[#dedbd3] bg-white md:hidden">

            @foreach($reports as $report)

                <div class="p-5">

                    <div class="flex items-start justify-between gap-4">

                        <div>

                            <p class="text-xs font-bold uppercase tracking-wide text-[#2f625b]">
                                {{ $report->category }}
                            </p>

                            <h2 class="mt-1 text-xl font-bold text-[#263634]">
                                {{ $report->facility->name ?? '-' }}
                            </h2>

                            <p class="mt-1 text-sm text-[#7b8581]">
                                {{ $report->user->name ?? '-' }}
                            </p>

                        </div>


                        {{-- Status Laporan --}}
                        @if($report->status === 'NEW')

                            <span class="shrink-0 rounded-full bg-[#f4e9dd] px-2.5 py-1 text-xs font-bold text-[#99633d]">
                                Baru
                            </span>

                        @elseif($report->status === 'PROCESSING')

                            <span class="shrink-0 rounded-full bg-[#e6f0ea] px-2.5 py-1 text-xs font-bold text-[#426b5a]">
                                Diproses
                            </span>

                        @elseif($report->status === 'COMPLETED')

                            <span class="shrink-0 rounded-full bg-[#e6f0ea] px-2.5 py-1 text-xs font-bold text-[#376453]">
                                Selesai
                            </span>

                        @else

                            <span class="shrink-0 rounded-full bg-[#eee8e5] px-2.5 py-1 text-xs font-bold text-[#765f59]">
                                Ditolak
                            </span>

                        @endif

                    </div>


                    {{-- Description --}}
                    <p class="mt-4 text-base leading-6 text-[#596460]">
                        {{ \Illuminate\Support\Str::limit($report->description, 150) }}
                    </p>


                    {{-- Facility Status --}}
                    <div class="mt-4 border-t border-[#eeeae4] pt-4">

                        <p class="text-xs font-bold uppercase tracking-wide text-[#68736f]">
                            Status Fasilitas
                        </p>

                        <div class="mt-2">

                            @if(($report->facility->status ?? null) === 'MAINTENANCE')

                                <span class="inline-flex rounded-full bg-[#f4e9dd] px-3 py-1.5 text-xs font-bold text-[#a65f3e]">
                                    Dalam Perbaikan
                                </span>

                            @elseif(($report->facility->status ?? null) === 'AVAILABLE')

                                <span class="inline-flex rounded-full bg-[#e6f0ea] px-3 py-1.5 text-xs font-bold text-[#426b5a]">
                                    Aktif
                                </span>

                            @else

                                <span class="inline-flex rounded-full bg-[#eee8e5] px-3 py-1.5 text-xs font-bold text-[#765f59]">
                                    Tidak Aktif
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- Resolution Note --}}
                    @if($report->resolution_note)

                        <div class="mt-4 border-t border-[#eeeae4] pt-4">

                            <p class="text-xs font-bold uppercase tracking-wide text-[#68736f]">
                                Catatan Resolusi
                            </p>

                            <p class="mt-2 text-sm leading-6 text-[#596460]">
                                {{ \Illuminate\Support\Str::limit($report->resolution_note, 150) }}
                            </p>

                        </div>

                    @endif


                    {{-- Action --}}
                    <a
                        href="{{ route('staff.reports.show', $report) }}"
                        class="mt-5 inline-flex w-full items-center justify-center rounded-lg bg-[#2f625b] px-4 py-2.5 text-sm font-bold text-white transition hover:bg-[#244d48]"
                    >
                        Kelola Laporan
                    </a>

                </div>

            @endforeach

        </div>


    @else

        <div class="border border-[#dedbd3] bg-white px-6 py-14 text-center">

            <h2 class="text-2xl font-bold text-[#263634]">
                Belum ada laporan
            </h2>

            <p class="mt-2 text-base text-[#7a8581]">
                Belum ada laporan kerusakan dari pengguna.
            </p>

        </div>

    @endif

</div>

@endsection