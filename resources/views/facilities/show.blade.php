@extends('layouts.app')

<<<<<<< HEAD
@section('title', $facility->name)

@section('content')

<div class="mx-auto max-w-3xl">

    {{-- Back --}}
    <a
        href="{{ route('facilities.index') }}"
        class="mb-6 inline-flex text-sm text-[#9b8878] hover:text-[#806b5d]">
        ← Kembali ke fasilitas
    </a>


    <div class="rounded-2xl border border-[#e9e3dd] bg-white p-7 shadow-sm">

        {{-- Header --}}
        <div class="flex flex-col justify-between gap-4 border-b border-[#eeeae5] pb-6 sm:flex-row sm:items-start">

            <div>

                <p class="text-sm text-[#a2a7ad]">
                    {{ $facility->type }}
                </p>

                <h1 class="mt-1 text-2xl font-semibold text-[#3f4f63]">
                    {{ $facility->name }}
                </h1>

            </div>


            @if($facility->status === 'AVAILABLE')

                <span class="w-fit rounded-full bg-[#e8f2e8] px-3 py-1.5 text-xs font-medium text-[#607560]">
                    Tersedia
                </span>

            @elseif($facility->status === 'MAINTENANCE')

                <span class="w-fit rounded-full bg-[#f7eee4] px-3 py-1.5 text-xs font-medium text-[#92745e]">
                    Dalam perbaikan
                </span>

            @else

                <span class="w-fit rounded-full bg-[#f1eded] px-3 py-1.5 text-xs font-medium text-[#806f6f]">
                    Nonaktif
                </span>

            @endif

        </div>


        {{-- Information --}}
        <div class="divide-y divide-[#f0ece8]">

            <div class="flex flex-col gap-1 py-5 sm:flex-row sm:justify-between">

                <span class="text-sm text-[#9ca3ab]">
                    Tipe fasilitas
                </span>

                <span class="text-sm font-medium text-[#59636f]">
                    {{ $facility->type }}
                </span>

            </div>


            <div class="flex flex-col gap-1 py-5 sm:flex-row sm:justify-between">

                <span class="text-sm text-[#9ca3ab]">
                    Lokasi
                </span>

                <span class="text-sm font-medium text-[#59636f]">
                    {{ $facility->location }}
                </span>

            </div>


            <div class="flex flex-col gap-1 py-5 sm:flex-row sm:justify-between">

                <span class="text-sm text-[#9ca3ab]">
                    Kapasitas
                </span>

                <span class="text-sm font-medium text-[#59636f]">
                    {{ $facility->capacity }} orang
                </span>

            </div>


            <div class="py-5">

                <p class="mb-2 text-sm text-[#9ca3ab]">
                    Deskripsi
                </p>

                <p class="text-sm leading-6 text-[#626b75]">
                    {{ $facility->description ?: 'Belum ada deskripsi fasilitas.' }}
                </p>

            </div>

        </div>

        @auth

            @if(auth()->user()->role === 'USER' && $facility->status === 'AVAILABLE')

                <div class="mt-5">

                    <a
                        href="{{ route('reservations.create', $facility) }}"
                        class="inline-flex rounded-lg bg-[#d8c8bc] px-4 py-2.5 text-sm font-medium text-white transition hover:bg-[#c9b5a7]">
                        Ajukan reservasi
                    </a>

                </div>

            @endif

        @endauth


        {{-- Admin --}}
=======
@section('title', $facility->name . ' - Campus Facility System')

@section('content')

{{-- Back --}}
<div class="mb-6">
    <a
        href="{{ route('facilities.index') }}"
        class="inline-flex items-center gap-2 text-sm font-semibold text-[#2f625b] hover:underline"
    >
        ← Kembali ke fasilitas
    </a>
</div>


{{-- Facility Header --}}
<div class="mb-7 grid gap-6 lg:grid-cols-[1fr_280px]">

    {{-- Main Info --}}
    <div class="border border-[#dedbd3] bg-white p-7">

        <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">

            <div>

                <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#2f625b]">
                    {{ $facility->type }}
                </p>

                <h1 class="mt-2 text-3xl font-bold tracking-tight text-[#263634]">
                    {{ $facility->name }}
                </h1>

                <div class="mt-4 flex flex-wrap gap-x-5 gap-y-2 text-sm text-[#68736f]">

                    <span class="flex items-center gap-2">
                        <span class="font-semibold text-[#43504d]">Lokasi</span>
                        {{ $facility->location }}
                    </span>

                    <span class="text-[#c6c3bb]">•</span>

                    <span class="flex items-center gap-2">
                        <span class="font-semibold text-[#43504d]">Kapasitas</span>
                        {{ $facility->capacity }} orang
                    </span>

                </div>

            </div>


            {{-- Status --}}
            <div class="shrink-0">

                @if($facility->status === 'AVAILABLE')

                    <span class="inline-flex items-center gap-2 rounded-full bg-[#e7f0eb] px-3 py-1.5 text-xs font-bold text-[#376453]">
                        <span class="h-2 w-2 rounded-full bg-[#426b5a]"></span>
                        Tersedia
                    </span>

                @elseif($facility->status === 'MAINTENANCE')

                    <span class="inline-flex items-center gap-2 rounded-full bg-[#f4e9dd] px-3 py-1.5 text-xs font-bold text-[#99633d]">
                        <span class="h-2 w-2 rounded-full bg-[#99633d]"></span>
                        Maintenance
                    </span>

                @else

                    <span class="inline-flex items-center gap-2 rounded-full bg-[#eee8e5] px-3 py-1.5 text-xs font-bold text-[#765f59]">
                        <span class="h-2 w-2 rounded-full bg-[#765f59]"></span>
                        Tidak Aktif
                    </span>

                @endif

            </div>

        </div>


        {{-- Description --}}
        <div class="mt-7 border-t border-[#e8e5de] pt-6">

            <p class="mb-2 text-xs font-bold uppercase tracking-[0.12em] text-[#7a8581]">
                Tentang fasilitas
            </p>

            @if($facility->description)

                <p class="max-w-3xl text-sm leading-7 text-[#596460]">
                    {{ $facility->description }}
                </p>

            @else

                <p class="text-sm italic text-[#9aa19e]">
                    Belum ada deskripsi untuk fasilitas ini.
                </p>

            @endif

        </div>


        {{-- Admin Action --}}
>>>>>>> 6f631731ed7493d53d9cad3b55bee88549bdbb15
        @auth

            @if(auth()->user()->role === 'ADMIN')

<<<<<<< HEAD
                <div class="mt-5 border-t border-[#eeeae5] pt-5">

                    <a
                        href="{{ route('facilities.edit', $facility) }}"
                        class="inline-flex rounded-lg bg-[#d8c8bc] px-4 py-2.5 text-sm font-medium text-white hover:bg-[#c9b5a7]">
=======
                <div class="mt-6 border-t border-[#e8e5de] pt-5">

                    <a
                        href="{{ route('facilities.edit', $facility) }}"
                        class="inline-flex rounded-lg border border-[#d5d2ca] bg-white px-4 py-2.5 text-sm font-semibold text-[#596460] transition hover:bg-[#f4f3ef]"
                    >
>>>>>>> 6f631731ed7493d53d9cad3b55bee88549bdbb15
                        Edit fasilitas
                    </a>

                </div>

            @endif

        @endauth

    </div>


<<<<<<< HEAD
    {{-- Availability --}}
<div class="mt-5 rounded-2xl border border-[#e9e3dd] bg-white p-6 shadow-sm">

    <div class="mb-5">
        <h2 class="text-lg font-semibold text-[#3f4f63]">
            Ketersediaan Fasilitas
        </h2>

        <p class="mt-1 text-sm text-[#9ca3ab]">
            Pilih tanggal untuk melihat ketersediaan fasilitas setiap 30 menit.
        </p>
    </div>

    {{-- Pilih tanggal --}}
    <form method="GET" action="{{ route('facilities.show', $facility) }}" class="mb-6">
        <label for="date" class="mb-2 block text-sm font-medium text-[#59636f]">
            Tanggal reservasi
        </label>

        <div class="flex flex-col gap-3 sm:flex-row">
            <input
                type="date"
                id="date"
                name="date"
                value="{{ $selectedDate }}"
                min="{{ now()->format('Y-m-d') }}"
                class="rounded-lg border border-[#e3ddd7] px-3 py-2.5 text-sm text-[#59636f] outline-none focus:border-[#c9b5a7] focus:ring-1 focus:ring-[#c9b5a7]"
            >

            <button
                type="submit"
                class="rounded-lg bg-[#d8c8bc] px-4 py-2.5 text-sm font-medium text-white hover:bg-[#c9b5a7]"
            >
                Lihat jadwal
            </button>
        </div>
    </form>

    @if ($facility->status === 'MAINTENANCE')

        <div class="rounded-xl bg-[#f7eee4] px-4 py-4">
            <p class="text-sm font-medium text-[#92745e]">
                Fasilitas sedang dalam perbaikan
            </p>

            <p class="mt-1 text-xs leading-5 text-[#a18b79]">
                Fasilitas tidak dapat digunakan untuk reservasi selama masa perbaikan.
            </p>
        </div>

    @elseif ($facility->status === 'INACTIVE')

        <div class="rounded-xl bg-[#f1eded] px-4 py-4">
            <p class="text-sm font-medium text-[#806f6f]">
                Fasilitas tidak aktif
            </p>

            <p class="mt-1 text-xs leading-5 text-[#9a8d8d]">
                Fasilitas ini sedang tidak dapat digunakan untuk reservasi.
            </p>
        </div>

    @else

        {{-- Keterangan warna --}}
        <div class="mb-5 flex flex-wrap gap-4 text-xs text-[#6f7378]">

            <div class="flex items-center gap-2">
                <span class="h-3 w-3 rounded-full bg-[#dcebdc]"></span>
                Tersedia
            </div>

            <div class="flex items-center gap-2">
                <span class="h-3 w-3 rounded-full bg-[#ecdede]"></span>
                Sudah dipesan
            </div>

            <div class="flex items-center gap-2">
                <span class="h-3 w-3 rounded-full bg-[#f1dfcc]"></span>
                Dalam perbaikan
            </div>

        </div>

        {{-- Slot waktu --}}
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4">

            @foreach ($slots as $slot)

                @if ($slot['status'] === 'AVAILABLE')

                    @auth
                        @if (auth()->user()->role === 'USER')

                            <a
                                href="{{ route('reservations.create', [
                                    'facility' => $facility,
                                    'date' => $selectedDate,
                                    'start_time' => $slot['start'],
                                    'end_time' => $slot['end'],
                                ]) }}"
                                class="rounded-xl border border-[#dcebdc] bg-[#e8f2e8] px-3 py-3 text-center transition hover:border-[#b9d1b9] hover:bg-[#dfeede]"
                            >
                                <p class="text-sm font-medium text-[#607560]">
                                    {{ $slot['start'] }} - {{ $slot['end'] }}
                                </p>

                                <p class="mt-1 text-xs text-[#7c8c7c]">
                                    Tersedia
                                </p>
                            </a>

                        @else

                            <div class="rounded-xl border border-[#dcebdc] bg-[#e8f2e8] px-3 py-3 text-center">
                                <p class="text-sm font-medium text-[#607560]">
                                    {{ $slot['start'] }} - {{ $slot['end'] }}
                                </p>

                                <p class="mt-1 text-xs text-[#7c8c7c]">
                                    Tersedia
                                </p>
                            </div>

                        @endif
                    @else

                        <div class="rounded-xl border border-[#dcebdc] bg-[#e8f2e8] px-3 py-3 text-center">
                            <p class="text-sm font-medium text-[#607560]">
                                {{ $slot['start'] }} - {{ $slot['end'] }}
                            </p>

                            <p class="mt-1 text-xs text-[#7c8c7c]">
                                Tersedia
                            </p>
                        </div>

                    @endauth

                @elseif ($slot['status'] === 'MAINTENANCE')

                    <div class="cursor-not-allowed rounded-xl border border-[#f1dfcc] bg-[#f7eee4] px-3 py-3 text-center">
                        <p class="text-sm font-medium text-[#92745e]">
                            {{ $slot['start'] }} - {{ $slot['end'] }}
                        </p>

                        <p class="mt-1 text-xs text-[#a18b79]">
                            Perbaikan
                        </p>
                    </div>

                @else

                    <div class="cursor-not-allowed rounded-xl border border-[#ecdede] bg-[#f3e8e8] px-3 py-3 text-center">
                        <p class="text-sm font-medium text-[#806f6f]">
                            {{ $slot['start'] }} - {{ $slot['end'] }}
                        </p>

                        <p class="mt-1 text-xs text-[#9a8d8d]">
                            Sudah dipesan
                        </p>
                    </div>

                @endif

            @endforeach

        </div>

    @endif
=======
    {{-- Quick Info --}}
    <div class="border border-[#dedbd3] bg-[#2f625b] p-6 text-white">

        <p class="text-xs font-bold uppercase tracking-[0.14em] text-white/60">
            Reservation
        </p>

        <h2 class="mt-3 text-xl font-bold">
            Jadwal penggunaan
        </h2>

        <p class="mt-2 text-sm leading-6 text-white/75">
            Periksa slot yang tersedia sebelum mengajukan reservasi.
        </p>


        <div class="mt-7 space-y-4 border-t border-white/15 pt-5">

            <div>
                <p class="text-xs text-white/55">
                    Jam operasional
                </p>

                <p class="mt-1 text-sm font-semibold">
                    07:00 — 20:00
                </p>
            </div>

            <div>
                <p class="text-xs text-white/55">
                    Durasi slot
                </p>

                <p class="mt-1 text-sm font-semibold">
                    30 menit
                </p>
            </div>

        </div>

    </div>

</div>


{{-- Availability --}}
<div class="border border-[#dedbd3] bg-white">

    {{-- Section Header --}}
    <div class="border-b border-[#e4e1da] px-6 py-5">

        <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">

            <div>

                <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#2f625b]">
                    Availability
                </p>

                <h2 class="mt-1.5 text-xl font-bold text-[#263634]">
                    Ketersediaan Fasilitas
                </h2>

                <p class="mt-1 text-sm text-[#7a8581]">
                    Pilih tanggal untuk melihat slot waktu yang tersedia.
                </p>

            </div>


            {{-- Date --}}
            <form
                method="GET"
                action="{{ route('facilities.show', $facility) }}"
                class="flex items-end gap-2"
            >

                <div>

                    <label
                        for="date"
                        class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-[#68736f]"
                    >
                        Tanggal
                    </label>

                    <input
                        id="date"
                        type="date"
                        name="date"
                        value="{{ $selectedDate }}"
                        class="rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-3 py-2.5 text-sm text-[#43504d] outline-none focus:border-[#2f625b] focus:ring-4 focus:ring-[#2f625b]/10"
                    >

                </div>

                <button
                    type="submit"
                    class="rounded-lg bg-[#2f625b] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#244d48]"
                >
                    Lihat
                </button>

            </form>

        </div>

    </div>


    {{-- Availability Content --}}
    <div class="p-6">

        {{-- Legend --}}
        <div class="mb-6 flex flex-wrap gap-2">

            <span class="inline-flex items-center gap-2 rounded-full bg-[#e7f0eb] px-3 py-1.5 text-xs font-semibold text-[#376453]">
                <span class="h-2 w-2 rounded-full bg-[#426b5a]"></span>
                Tersedia
            </span>

            <span class="inline-flex items-center gap-2 rounded-full bg-[#eee8e5] px-3 py-1.5 text-xs font-semibold text-[#765f59]">
                <span class="h-2 w-2 rounded-full bg-[#765f59]"></span>
                Sudah dipesan
            </span>

            <span class="inline-flex items-center gap-2 rounded-full bg-[#f4e9dd] px-3 py-1.5 text-xs font-semibold text-[#99633d]">
                <span class="h-2 w-2 rounded-full bg-[#99633d]"></span>
                Maintenance
            </span>

        </div>


        {{-- Maintenance --}}
        @if($facility->status === 'MAINTENANCE')

            <div class="border border-[#dfc9b4] bg-[#f4e9dd] px-5 py-5">

                <div class="flex gap-4">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white/60 text-[#99633d]">
                        !
                    </div>

                    <div>

                        <h3 class="font-bold text-[#765035]">
                            Fasilitas sedang dalam maintenance
                        </h3>

                        <p class="mt-1 text-sm leading-6 text-[#876b55]">
                            Fasilitas ini sementara tidak dapat digunakan untuk reservasi.
                        </p>

                    </div>

                </div>

            </div>


        @elseif($facility->status === 'INACTIVE')

            <div class="border border-[#d8ccc7] bg-[#eee8e5] px-5 py-5">

                <h3 class="font-bold text-[#765f59]">
                    Fasilitas tidak aktif
                </h3>

                <p class="mt-1 text-sm leading-6 text-[#7c6c67]">
                    Fasilitas ini tidak tersedia untuk digunakan.
                </p>

            </div>


        @else

            {{-- Date Info --}}
            <div class="mb-5 flex items-center justify-between">

                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-[#8a9490]">
                        Jadwal untuk
                    </p>

                    <p class="mt-1 text-base font-bold text-[#263634]">
                        {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d F Y') }}
                    </p>
                </div>

                <p class="hidden text-xs text-[#8a9490] sm:block">
                    07:00 — 20:00
                </p>

            </div>


            {{-- Slots --}}
            <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">

                @foreach($slots as $slot)

                    @if($slot['status'] === 'AVAILABLE')

                        @auth

                            @if(auth()->user()->role === 'USER')

                                <a
                                    href="{{ route('reservations.create', [
                                        'facility' => $facility,
                                        'date' => $selectedDate,
                                        'start' => $slot['start'],
                                        'end' => $slot['end']
                                    ]) }}"
                                    class="group border border-[#b8d1c3] bg-[#e7f0eb] p-3.5 text-left transition hover:border-[#2f625b] hover:bg-[#dceae3]"
                                >

                                    <div class="flex items-center justify-between">

                                        <span class="text-sm font-bold text-[#376453]">
                                            {{ $slot['start'] }}
                                        </span>

                                        <span class="text-[#2f625b] transition group-hover:translate-x-0.5">
                                            →
                                        </span>

                                    </div>

                                    <p class="mt-1 text-xs text-[#668276]">
                                        sampai {{ $slot['end'] }}
                                    </p>

                                    <p class="mt-3 text-[10px] font-bold uppercase tracking-wider text-[#426b5a]">
                                        Reservasi
                                    </p>

                                </a>

                            @else

                                <div class="border border-[#b8d1c3] bg-[#e7f0eb] p-3.5">

                                    <p class="text-sm font-bold text-[#376453]">
                                        {{ $slot['start'] }}
                                    </p>

                                    <p class="mt-1 text-xs text-[#668276]">
                                        sampai {{ $slot['end'] }}
                                    </p>

                                    <p class="mt-3 text-[10px] font-bold uppercase tracking-wider text-[#668276]">
                                        Tersedia
                                    </p>

                                </div>

                            @endif

                        @else

                            <a
                                href="{{ route('login') }}"
                                class="group border border-[#b8d1c3] bg-[#e7f0eb] p-3.5 text-left transition hover:border-[#2f625b] hover:bg-[#dceae3]"
                            >

                                <div class="flex items-center justify-between">

                                    <span class="text-sm font-bold text-[#376453]">
                                        {{ $slot['start'] }}
                                    </span>

                                    <span class="text-[#2f625b] transition group-hover:translate-x-0.5">
                                        →
                                    </span>

                                </div>

                                <p class="mt-1 text-xs text-[#668276]">
                                    sampai {{ $slot['end'] }}
                                </p>

                                <p class="mt-3 text-[10px] font-bold uppercase tracking-wider text-[#426b5a]">
                                    Login untuk reservasi
                                </p>

                            </a>

                        @endauth


                    @elseif($slot['status'] === 'RESERVED')

                        <div class="border border-[#d8ccc7] bg-[#eee8e5] p-3.5">

                            <div class="flex items-center justify-between">

                                <span class="text-sm font-bold text-[#765f59]">
                                    {{ $slot['start'] }}
                                </span>

                                <span class="text-xs font-semibold text-[#927e77]">
                                    —
                                </span>

                            </div>

                            <p class="mt-1 text-xs text-[#8a7771]">
                                sampai {{ $slot['end'] }}
                            </p>

                            <p class="mt-3 text-[10px] font-bold uppercase tracking-wider text-[#765f59]">
                                Sudah dipesan
                            </p>

                        </div>

                    @else

                        <div class="border border-[#dfc9b4] bg-[#f4e9dd] p-3.5">

                            <div class="flex items-center justify-between">

                                <span class="text-sm font-bold text-[#99633d]">
                                    {{ $slot['start'] }}
                                </span>

                                <span class="text-xs font-semibold text-[#b18261]">
                                    —
                                </span>

                            </div>

                            <p class="mt-1 text-xs text-[#a17a5d]">
                                sampai {{ $slot['end'] }}
                            </p>

                            <p class="mt-3 text-[10px] font-bold uppercase tracking-wider text-[#99633d]">
                                Maintenance
                            </p>

                        </div>

                    @endif

                @endforeach

            </div>

        @endif

    </div>

</div>


{{-- Bottom Note --}}
<div class="mt-5 flex items-start gap-3 border border-[#dedbd3] bg-[#fafaf8] px-5 py-4">

    <span class="mt-0.5 text-[#2f625b]">
        ●
    </span>

    <p class="text-xs leading-5 text-[#7a8581]">
        Ketersediaan dapat berubah setelah pengajuan reservasi diproses oleh staff.
        Slot yang sedang dalam proses atau telah disetujui akan ditampilkan sebagai tidak tersedia.
    </p>
>>>>>>> 6f631731ed7493d53d9cad3b55bee88549bdbb15

</div>

@endsection