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


    {{-- Future reservation --}}
    <div class="mt-5 rounded-xl bg-[#f7f3ef] px-5 py-4">

        <p class="text-sm font-medium text-[#786c63]">
            Informasi reservasi
        </p>

        <p class="mt-1 text-xs leading-5 text-[#9a918b]">
            Jadwal reservasi dan ketersediaan slot akan ditampilkan pada tahap fitur reservasi.
        </p>

    </div>

</div>

@endsection