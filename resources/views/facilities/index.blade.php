@extends('layouts.app')

@section('title', 'Daftar Fasilitas')

@section('content')

{{-- Form cari: filter tipe / lokasi / kapasitas --}}
<form method="GET" action="{{ route('facilities.index') }}">
    <label for="type">Tipe</label>
    <select id="type" name="type">
        <option value="">-- Semua tipe --</option>
        @foreach($types as $t)
            <option value="{{ $t }}" @selected(($filters['type'] ?? '') === $t)>{{ $t }}</option>

{{-- Header --}}
<div class="mb-8">

    <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">

        <div>
            <div class="mb-3 flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-[#2f625b]"></span>

                <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#2f625b]">
                    Campus Facility
                </p>
            </div>

            <h1 class="text-3xl font-bold tracking-tight text-[#263634]">
                Fasilitas Kampus
            </h1>

            <p class="mt-2 max-w-2xl text-sm leading-6 text-[#68736f]">
                Temukan ruang dan fasilitas kampus yang sesuai dengan kebutuhanmu.
                Cek informasi dan ketersediaannya sebelum melakukan reservasi.
            </p>
        </div>


        @auth
            @if(auth()->user()->role === 'ADMIN')

                <a
                    href="{{ route('facilities.create') }}"
                    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-[#2f625b] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#244d48] hover:shadow-md"
                >
                    <span class="text-lg leading-none">+</span>
                    Tambah fasilitas
                </a>

            @endif
        @endauth

    </div>

</div>


{{-- Quick Information --}}
<div class="mb-7 grid gap-px overflow-hidden border border-[#dedbd3] bg-[#dedbd3] sm:grid-cols-3">

    <div class="bg-white px-5 py-4">
        <p class="text-xs font-semibold uppercase tracking-wider text-[#8a9490]">
            Katalog
        </p>

        <p class="mt-1 text-sm font-semibold text-[#263634]">
            {{ $facilities->count() }} fasilitas
        </p>
    </div>

    <div class="bg-white px-5 py-4">
        <p class="text-xs font-semibold uppercase tracking-wider text-[#8a9490]">
            Jam Operasional
        </p>

        <p class="mt-1 text-sm font-semibold text-[#263634]">
            07:00 — 20:00
        </p>
    </div>

    <div class="bg-white px-5 py-4">
        <p class="text-xs font-semibold uppercase tracking-wider text-[#8a9490]">
            Reservasi
        </p>

        <p class="mt-1 text-sm font-semibold text-[#263634]">
            Slot 30 menit
        </p>
    </div>

</div>


{{-- Filter --}}
<div class="mb-7 border border-[#dedbd3] bg-white">

    <div class="border-b border-[#e4e1da] px-5 py-4">
        <div class="flex items-center gap-3">

            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#e7f0eb] text-[#2f625b]">
                <span class="text-sm font-bold">⌕</span>
            </div>

            <div>
                <h2 class="text-sm font-bold text-[#263634]">
                    Cari fasilitas
                </h2>

                <p class="mt-0.5 text-xs text-[#7a8581]">
                    Gunakan filter untuk menemukan fasilitas dengan lebih cepat.
                </p>
            </div>

        </div>
    </div>


    <div class="p-5">

        <form
            method="GET"
            action="{{ route('facilities.index') }}"
            class="grid gap-4 md:grid-cols-4"
        >

            {{-- Type --}}
            <div>

                <label
                    for="type"
                    class="mb-2 block text-xs font-bold uppercase tracking-wide text-[#596460]"
                >
                    Tipe
                </label>

                <select
                    id="type"
                    name="type"
                    class="w-full rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-3.5 py-2.5 text-sm text-[#43504d] outline-none transition focus:border-[#2f625b] focus:bg-white focus:ring-4 focus:ring-[#2f625b]/10"
                >

                    <option value="">
                        Semua tipe
                    </option>

                    @foreach($types as $t)

                        <option
                            value="{{ $t }}"
                            @selected(($filters['type'] ?? '') === $t)
                        >
                            {{ $t }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Location --}}
            <div>

                <label
                    for="location"
                    class="mb-2 block text-xs font-bold uppercase tracking-wide text-[#596460]"
                >
                    Lokasi
                </label>

                <input
                    id="location"
                    type="text"
                    name="location"
                    value="{{ $filters['location'] ?? '' }}"
                    maxlength="255"
                    placeholder="Contoh: Gedung A"
                    class="w-full rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-3.5 py-2.5 text-sm text-[#263634] outline-none transition placeholder:text-[#a2aaa7] focus:border-[#2f625b] focus:bg-white focus:ring-4 focus:ring-[#2f625b]/10"
                >

            </div>


            {{-- Capacity --}}
            <div>

                <label
                    for="min_capacity"
                    class="mb-2 block text-xs font-bold uppercase tracking-wide text-[#596460]"
                >
                    Kapasitas minimal
                </label>

                <input
                    id="min_capacity"
                    type="number"
                    name="min_capacity"
                    value="{{ $filters['min_capacity'] ?? '' }}"
                    min="1"
                    placeholder="Contoh: 30"
                    class="w-full rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-3.5 py-2.5 text-sm text-[#263634] outline-none transition placeholder:text-[#a2aaa7] focus:border-[#2f625b] focus:bg-white focus:ring-4 focus:ring-[#2f625b]/10"
                >

            </div>


            {{-- Buttons --}}
            <div class="flex items-end gap-2">

                <button
                    type="submit"
                    class="flex-1 rounded-lg bg-[#2f625b] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#244d48]"
                >
                    Cari
                </button>

                <a
                    href="{{ route('facilities.index') }}"
                    class="rounded-lg border border-[#d5d2ca] bg-white px-4 py-2.5 text-sm font-semibold text-[#596460] transition hover:bg-[#f4f3ef]"
                >
                    Reset
                </a>

            </div>

        </form>

    </div>

</div>


{{-- Status Legend --}}
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">

    <div>
        <p class="text-sm font-bold text-[#263634]">
            Daftar fasilitas
        </p>

        <p class="mt-1 text-xs text-[#7a8581]">
            Pilih fasilitas untuk melihat detail dan jadwal ketersediaannya.
        </p>
    </div>


    <div class="flex flex-wrap items-center gap-2 text-xs">

        <span class="flex items-center gap-1.5 rounded-full bg-[#e7f0eb] px-3 py-1.5 font-medium text-[#376453]">
            <span class="h-1.5 w-1.5 rounded-full bg-[#426b5a]"></span>
            Tersedia
        </span>

        <span class="flex items-center gap-1.5 rounded-full bg-[#f4e9dd] px-3 py-1.5 font-medium text-[#99633d]">
            <span class="h-1.5 w-1.5 rounded-full bg-[#99633d]"></span>
            Perbaikan
        </span>

        @auth
            @if(auth()->user()->role === 'ADMIN')

                <span class="flex items-center gap-1.5 rounded-full bg-[#eee8e5] px-3 py-1.5 font-medium text-[#765f59]">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#765f59]"></span>
                    Nonaktif
                </span>

            @endif
        @endauth

    </div>

</div>


{{-- Facility Cards --}}
@if($facilities->count())

    <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">

        @foreach($facilities as $f)

            <div class="group flex flex-col border border-[#dedbd3] bg-white transition duration-200 hover:-translate-y-1 hover:border-[#b8c9c3] hover:shadow-[0_10px_25px_rgba(38,54,52,0.08)]">

                {{-- Card Top --}}
                <div class="p-5 pb-4">

                    <div class="mb-5 flex items-start justify-between gap-3">

                        <div class="min-w-0">

                            <p class="mb-1 text-[11px] font-bold uppercase tracking-[0.12em] text-[#2f625b]">
                                {{ $f->type }}
                            </p>

                            <h2 class="text-lg font-bold leading-6 text-[#263634]">
                                {{ $f->name }}
                            </h2>

                        </div>


                        {{-- Status --}}
                        @if($f->status === 'AVAILABLE')

                            <span class="shrink-0 rounded-full bg-[#e7f0eb] px-2.5 py-1 text-[11px] font-bold text-[#376453]">
                                Tersedia
                            </span>

                        @elseif($f->status === 'MAINTENANCE')

                            <span class="shrink-0 rounded-full bg-[#f4e9dd] px-2.5 py-1 text-[11px] font-bold text-[#99633d]">
                                Perbaikan
                            </span>

                        @else

                            <span class="shrink-0 rounded-full bg-[#eee8e5] px-2.5 py-1 text-[11px] font-bold text-[#765f59]">
                                Nonaktif
                            </span>

                        @endif

                    </div>


                    {{-- Facility Description --}}
                    @if($f->description)

                        <p class="line-clamp-2 text-sm leading-6 text-[#68736f]">
                            {{ $f->description }}
                        </p>

                    @else

                        <p class="text-sm italic leading-6 text-[#a0a7a4]">
                            Tidak ada deskripsi fasilitas.
                        </p>

                    @endif

                </div>


                {{-- Facility Information --}}
                <div class="mx-5 border-t border-[#e8e5de] py-4">

                    <div class="grid grid-cols-2 gap-4">

                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wide text-[#909995]">
                                Lokasi
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#43504d]">
                                {{ $f->location }}
                            </p>
                        </div>


                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wide text-[#909995]">
                                Kapasitas
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#43504d]">
                                {{ $f->capacity }} orang
                            </p>
                        </div>

                    </div>

                </div>


                {{-- Action --}}
                <div class="mt-auto flex items-center justify-between border-t border-[#e8e5de] bg-[#fafaf8] px-5 py-3.5">

                    <a
                        href="{{ route('facilities.show', $f) }}"
                        class="text-sm font-bold text-[#2f625b] transition group-hover:text-[#244d48] hover:underline"
                    >
                        Lihat detail →
                    </a>


                    @auth

                        @if(auth()->user()->role === 'ADMIN')

                            <a
                                href="{{ route('facilities.edit', $f) }}"
                                class="text-xs font-semibold text-[#7a8581] transition hover:text-[#2f625b]"
                            >
                                Edit fasilitas
                            </a>

                        @endif

                    @endauth

                </div>

            </div>

        @endforeach
    </select>

    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">

        <div>

    <button type="submit">Cari</button>
    <a href="{{ route('facilities.index') }}">Reset</a>
</form>
    {{-- Empty State --}}
    <div class="border border-dashed border-[#cbc8c0] bg-white px-6 py-16 text-center">

        <div class="mx-auto mb-5 flex h-14 w-14 items-center justify-center rounded-xl bg-[#e7f0eb] text-[#2f625b]">
            <span class="text-xl">⌕</span>
        </div>

        <h2 class="text-lg font-bold text-[#263634]">
            Belum ada fasilitas
        </h2>

        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-[#7a8581]">
            Tidak ada fasilitas yang sesuai dengan filter yang dipilih.
            Coba ubah kata kunci atau gunakan pilihan filter lainnya.
        </p>

        <a
            href="{{ route('facilities.index') }}"
            class="mt-5 inline-flex rounded-lg border border-[#d5d2ca] px-4 py-2.5 text-sm font-semibold text-[#596460] transition hover:bg-[#f4f3ef]"
        >
            Tampilkan semua fasilitas
        </a>

    </div>

            <h1 class="text-2xl font-semibold text-[#3f4f63]">
                Daftar Fasilitas
            </h1>

            <p class="mt-2 text-sm text-[#8b929b]">
                Cari ruang dan fasilitas kampus sesuai kebutuhanmu.
            </p>

        </div>


        @auth
            @if(auth()->user()->role === 'ADMIN')

                <a href="{{ route('facilities.create') }}"
                   class="inline-flex items-center justify-center rounded-lg bg-[#d8c8bc] px-4 py-2.5 text-sm font-medium text-white transition hover:bg-[#c9b5a7]">
                    + Tambah fasilitas
                </a>

            @endif
        @endauth

    </div>

</div>


{{-- Filter --}}
<div class="mb-7 rounded-2xl border border-[#e9e3dd] bg-white p-5 shadow-sm">

    <div class="mb-4">

        <h2 class="text-sm font-semibold text-[#59636f]">
            Cari fasilitas
        </h2>

        <p class="mt-1 text-xs text-[#9ca3ab]">
            Gunakan filter untuk mempersempit hasil pencarian.
        </p>

    </div>


    <form method="GET"
          action="{{ route('facilities.index') }}"
          class="grid gap-4 md:grid-cols-4">


        {{-- Type --}}
        <div>

            <label for="type"
                   class="mb-2 block text-xs font-medium text-[#6b7280]">
                Tipe
            </label>

            <select
                id="type"
                name="type"
                class="w-full rounded-lg border border-[#dedbd6] bg-[#fcfbfa] px-3 py-2.5 text-sm text-[#59636f] outline-none focus:border-[#c9b5a7] focus:ring-2 focus:ring-[#eadfd8]">

                <option value="">
                    Semua tipe
                </option>

                @foreach($types as $t)

                    <option
                        value="{{ $t }}"
                        @selected(($filters['type'] ?? '') === $t)>
                        {{ $t }}
                    </option>

                @endforeach

            </select>

        </div>


        {{-- Location --}}
        <div>

            <label for="location"
                   class="mb-2 block text-xs font-medium text-[#6b7280]">
                Lokasi
            </label>

            <input
                id="location"
                type="text"
                name="location"
                value="{{ $filters['location'] ?? '' }}"
                maxlength="255"
                placeholder="Contoh: Gedung A"
                class="w-full rounded-lg border border-[#dedbd6] bg-[#fcfbfa] px-3 py-2.5 text-sm outline-none placeholder:text-[#b7b7b7] focus:border-[#c9b5a7] focus:ring-2 focus:ring-[#eadfd8]"
            >

        </div>


        {{-- Capacity --}}
        <div>

            <label for="min_capacity"
                   class="mb-2 block text-xs font-medium text-[#6b7280]">
                Kapasitas minimal
            </label>

            <input
                id="min_capacity"
                type="number"
                name="min_capacity"
                value="{{ $filters['min_capacity'] ?? '' }}"
                min="1"
                placeholder="Contoh: 30"
                class="w-full rounded-lg border border-[#dedbd6] bg-[#fcfbfa] px-3 py-2.5 text-sm outline-none placeholder:text-[#b7b7b7] focus:border-[#c9b5a7] focus:ring-2 focus:ring-[#eadfd8]"
            >

        </div>


        {{-- Buttons --}}
        <div class="flex items-end gap-2">

            <button
                type="submit"
                class="flex-1 rounded-lg bg-[#d8c8bc] px-4 py-2.5 text-sm font-medium text-white transition hover:bg-[#c9b5a7]">
                Cari
            </button>

            <a
                href="{{ route('facilities.index') }}"
                class="rounded-lg border border-[#dedbd6] px-4 py-2.5 text-sm text-[#6b7280] transition hover:bg-[#f7f5f2]">
                Reset
            </a>

        </div>

    </form>

</div>


{{-- Keterangan status --}}
<div class="mb-5 flex flex-wrap items-center gap-3 text-xs text-[#8b929b]">

    <span>Status:</span>

    <span class="rounded-full bg-[#e8f2e8] px-3 py-1 text-[#607560]">
        Tersedia
    </span>

    <span class="rounded-full bg-[#f7eee4] px-3 py-1 text-[#92745e]">
        Dalam perbaikan
    </span>

    @auth
        @if(auth()->user()->role === 'ADMIN')
            <span class="rounded-full bg-[#f1eded] px-3 py-1 text-[#806f6f]">
                Nonaktif
            </span>
        @endif
    @endauth

</div>


{{-- Facility Cards --}}
@if($facilities->count())

    <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">

        @foreach($facilities as $f)

            <div class="group rounded-2xl border border-[#e9e3dd] bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">

                {{-- Top --}}
                <div class="mb-5 flex items-start justify-between gap-3">

                    <div>

                        <p class="text-xs text-[#a2a7ad]">
                            {{ $f->type }}
                        </p>

                        <h2 class="mt-1 text-lg font-semibold text-[#3f4f63]">
                            {{ $f->name }}
                        </h2>

                    </div>


                    {{-- Status --}}
                    @if($f->status === 'AVAILABLE')

                        <span class="shrink-0 rounded-full bg-[#e8f2e8] px-3 py-1 text-xs font-medium text-[#607560]">
                            Tersedia
                        </span>

                    @elseif($f->status === 'MAINTENANCE')

                        <span class="shrink-0 rounded-full bg-[#f7eee4] px-3 py-1 text-xs font-medium text-[#92745e]">
                            Perbaikan
                        </span>

                    @else

                        <span class="shrink-0 rounded-full bg-[#f1eded] px-3 py-1 text-xs font-medium text-[#806f6f]">
                            Nonaktif
                        </span>

                    @endif

                </div>


                {{-- Info --}}
                <div class="space-y-3 border-t border-[#f0ece8] pt-4">

                    <div class="flex justify-between gap-4 text-sm">

                        <span class="text-[#9ca3ab]">
                            Lokasi
                        </span>

                        <span class="text-right font-medium text-[#626b75]">
                            {{ $f->location }}
                        </span>

                    </div>


                    <div class="flex justify-between gap-4 text-sm">

                        <span class="text-[#9ca3ab]">
                            Kapasitas
                        </span>

                        <span class="font-medium text-[#626b75]">
                            {{ $f->capacity }} orang
                        </span>

                    </div>

                </div>


                {{-- Action --}}
                <div class="mt-5 flex items-center justify-between border-t border-[#f0ece8] pt-4">

                    <a
                        href="{{ route('facilities.show', $f) }}"
                        class="text-sm font-medium text-[#a48977] transition hover:text-[#806b5d]">
                        Lihat detail →
                    </a>


                    @auth

                        @if(auth()->user()->role === 'ADMIN')

                            <a
                                href="{{ route('facilities.edit', $f) }}"
                                class="text-xs text-[#8b929b] hover:text-[#59636f]">
                                Edit
                            </a>

                        @endif

                    @endauth

                </div>

            </div>

        @endforeach

    </div>

@else

    {{-- Empty --}}
    <div class="rounded-2xl border border-dashed border-[#ddd7d0] bg-white px-6 py-14 text-center">

        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-[#f4eee9] text-[#a48977]">
            ○
        </div>

        <h2 class="text-base font-semibold text-[#59636f]">
            Belum ada fasilitas
        </h2>

        <p class="mt-2 text-sm text-[#9ca3ab]">
            Tidak ada fasilitas yang sesuai dengan filter yang dipilih.
        </p>

    </div>

@endif

@endsection