@extends('layouts.app')

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
        @auth

            @if(auth()->user()->role === 'ADMIN')

                <div class="mt-5 border-t border-[#eeeae5] pt-5">

                    <a
                        href="{{ route('facilities.edit', $facility) }}"
                        class="inline-flex rounded-lg bg-[#d8c8bc] px-4 py-2.5 text-sm font-medium text-white hover:bg-[#c9b5a7]">
                        Edit fasilitas
                    </a>

                </div>

            @endif

        @endauth

    </div>


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

</div>

@endsection