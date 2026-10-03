@extends('layouts.app')

@section('title', 'Laporan Saya - Campus Facility System')

@section('content')

<div class="mx-auto max-w-6xl">

    <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

        <div>
            <h1 class="mt-2 text-4xl font-bold tracking-tight text-[#263634]">
                Laporan Saya
            </h1>

            <p class="mt-2 text-base leading-6 text-[#68736f]">
                Lihat laporan kerusakan yang pernah kamu kirim dan status penanganannya.
            </p>

        </div>

        <a
            href="{{ route('reports.create') }}"
            class="inline-flex w-fit items-center justify-center rounded-lg bg-[#2f625b] px-5 py-2.5 text-base font-semibold text-white transition hover:bg-[#244d48]"
        >
            + Lapor Kerusakan
        </a>

    </div>

    @if($reports->count())

        <div class="grid gap-5">

            @foreach($reports as $report)

                <div class="border border-[#dedbd3] bg-white p-6">

                    <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">

                        <div>

                            <p class="text-xs font-bold uppercase tracking-wide text-[#2f625b]">
                                {{ $report->category }}
                            </p>

                            <h2 class="mt-1 text-2xl font-bold text-[#263634]">
                                {{ $report->facility->name ?? 'Fasilitas' }}
                            </h2>

                            <p class="mt-1 text-sm text-[#7b8581]">
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

                    <p class="mt-5 text-base leading-7 text-[#596460]">
                        {{ \Illuminate\Support\Str::limit($report->description, 180) }}
                    </p>

                    <div class="mt-5 flex flex-col gap-3 border-t border-[#eeeae4] pt-4 sm:flex-row sm:items-center sm:justify-between">

                        @if($report->resolution_note)

                            <p class="text-sm text-[#68736f]">
                                <span class="font-semibold text-[#43504d]">
                                    Catatan:
                                </span>

                                {{ \Illuminate\Support\Str::limit($report->resolution_note, 120) }}
                            </p>

                        @else

                            <p class="text-sm text-[#8a9490]">
                                Belum ada catatan petugas.
                            </p>

                        @endif

                        <a
                            href="{{ route('reports.show', $report) }}"
                            class="inline-flex w-fit items-center justify-center rounded-lg border border-[#d5d2ca] bg-white px-4 py-2.5 text-sm font-semibold text-[#43504d] transition hover:bg-[#f5f4f0]"
                        >
                            Lihat Detail →
                        </a>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div class="border border-[#dedbd3] bg-white px-6 py-14 text-center">

            <div class="mx-auto flex h-14 w-14 items-center justify-center bg-[#e6f0ea] text-2xl text-[#2f625b]">
                !
            </div>

            <h2 class="mt-5 text-2xl font-bold text-[#263634]">
                Belum ada laporan
            </h2>

            <p class="mx-auto mt-2 max-w-md text-base leading-6 text-[#7a8581]">
                Kamu belum pernah mengirim laporan kerusakan fasilitas.
            </p>

            <a
                href="{{ route('reports.create') }}"
                class="mt-6 inline-flex items-center justify-center rounded-lg bg-[#2f625b] px-5 py-2.5 text-base font-semibold text-white transition hover:bg-[#244d48]"
            >
                Lapor Kerusakan
            </a>

        </div>

    @endif

</div>

@endsection
