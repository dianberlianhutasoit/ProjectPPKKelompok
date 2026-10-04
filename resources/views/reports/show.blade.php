@extends('layouts.app')

@section('title', 'Detail Laporan - Campus Facility System')

@section('content')

<div class="mx-auto max-w-4xl">

    <div class="mb-6">

        <a
            href="{{ route('reports.index') }}"
            class="inline-flex items-center gap-2 text-base font-semibold text-[#2f625b] hover:underline"
        >
            ← Kembali ke laporan saya
        </a>

    </div>

    <div class="border border-[#dedbd3] bg-white">

        <div class="border-b border-[#e4e1da] p-6">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                <div>

                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#2f625b]">
                        Detail Laporan
                    </p>

                    <h1 class="mt-2 text-4xl font-bold tracking-tight text-[#263634]">
                        {{ $report->facility->name ?? 'Fasilitas' }}
                    </h1>

                    <p class="mt-2 text-sm text-[#7b8581]">
                        {{ $report->created_at->translatedFormat('d F Y, H:i') }}
                    </p>

                </div>

                @if($report->status === 'NEW')

                    <span class="w-fit rounded-full bg-[#f4e9dd] px-3 py-1.5 text-xs font-bold text-[#99633d]">
                        Baru
                    </span>

                @elseif($report->status === 'PROCESSING')

                    <span class="w-fit rounded-full bg-[#e6f0ea] px-3 py-1.5 text-xs font-bold text-[#426b5a]">
                        Diproses
                    </span>

                @elseif($report->status === 'COMPLETED')

                    <span class="w-fit rounded-full bg-[#e6f0ea] px-3 py-1.5 text-xs font-bold text-[#376453]">
                        Selesai
                    </span>

                @else

                    <span class="w-fit rounded-full bg-[#eee8e5] px-3 py-1.5 text-xs font-bold text-[#765f59]">
                        Ditolak
                    </span>

                @endif

            </div>

        </div>

        <div class="space-y-6 p-6">

            <div>

                <p class="text-xs font-bold uppercase tracking-wide text-[#68736f]">
                    Kategori
                </p>

                <p class="mt-1.5 text-base leading-6 text-[#596460]">
                    {{ $report->category }}
                </p>

            </div>

            <div>

                <p class="text-xs font-bold uppercase tracking-wide text-[#68736f]">
                    Deskripsi
                </p>

                <p class="mt-2 text-base leading-7 text-[#596460]">
                    {{ $report->description }}
                </p>

            </div>

            @if($report->photo)

                <div>

                    <p class="text-xs font-bold uppercase tracking-wide text-[#68736f]">
                        Foto Kerusakan
                    </p>

                    <div class="mt-3 overflow-hidden border border-[#dedbd3] bg-[#f7f6f2]">

                        <img
                            src="{{ asset('storage/' . $report->photo) }}"
                            alt="Foto kerusakan {{ $report->facility->name ?? '' }}"
                            class="max-h-[480px] w-full object-contain"
                        >

                    </div>

                </div>

            @endif

            <div class="border-t border-[#eeeae4] pt-6">

                <p class="text-xs font-bold uppercase tracking-wide text-[#68736f]">
                    Catatan Petugas
                </p>

                @if($report->resolution_note)

                    <p class="mt-2 text-base leading-7 text-[#596460]">
                        {{ $report->resolution_note }}
                    </p>

                @else

                    <p class="mt-2 text-base text-[#8a9490]">
                        Belum ada catatan dari petugas.
                    </p>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection
