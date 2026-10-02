@extends('layouts.app')

@section('title', 'Fasilitas Kampus')

@section('content')

<div class="space-y-10">

    {{-- =========================================================
        HERO
    ========================================================== --}}

    <section class="relative overflow-hidden rounded-[28px] bg-[#2f625b]">

        {{-- Decorative background --}}

        <div class="absolute -right-24 -top-24 h-80 w-80 rounded-full bg-white/10"></div>

        <div class="absolute -bottom-32 -left-20 h-96 w-96 rounded-full bg-white/5"></div>

        <div class="relative px-7 py-12 sm:px-10 sm:py-14">

            <div class="max-w-3xl">

                <p class="mb-4 text-xs font-bold uppercase tracking-[0.2em] text-[#dce9c9]">
                    CAMPUS FACILITY SYSTEM
                </p>

                <h1 class="text-5xl font-bold leading-tight tracking-tight text-white sm:text-6xl">
                    Fasilitas Kampus
                </h1>

                <p class="mt-5 max-w-2xl text-base leading-7 text-white/80 sm:text-lg">
                    Temukan fasilitas kampus yang tersedia, lihat detailnya,
                    dan cek ketersediaan jadwal sebelum melakukan reservasi.
                </p>

            </div>

        </div>

    </section>


    {{-- =========================================================
        SEARCH & FILTER
    ========================================================== --}}

    <section>

        <form
            action="{{ route('facilities.index') }}"
            method="GET"
            class="rounded-[22px] border border-[#ddd9d0] bg-white p-5 shadow-sm"
        >

            <div class="grid gap-4 lg:grid-cols-4">

                {{-- Search --}}

                <div>

                    <label
                        for="search"
                        class="mb-2 block text-xs font-bold uppercase tracking-wide text-[#68736f]"
                    >
                        Cari fasilitas
                    </label>

                    <div class="relative">

                        <input
                            type="text"
                            id="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama fasilitas..."
                            class="w-full rounded-xl border border-[#ddd9d0] bg-[#faf9f6] px-4 py-3 text-base text-[#263634] outline-none transition focus:border-[#2f625b] focus:ring-2 focus:ring-[#2f625b]/10"
                        >

                    </div>

                </div>


                {{-- Type --}}

                <div>

                    <label
                        for="type"
                        class="mb-2 block text-xs font-bold uppercase tracking-wide text-[#68736f]"
                    >
                        Tipe
                    </label>

                    <select
                        id="type"
                        name="type"
                        class="w-full rounded-xl border border-[#ddd9d0] bg-[#faf9f6] px-4 py-3 text-base text-[#263634] outline-none transition focus:border-[#2f625b] focus:ring-2 focus:ring-[#2f625b]/10"
                    >

                        <option value="">
                            Semua tipe
                        </option>

                        @foreach($types ?? [] as $type)

                            <option
                                value="{{ $type }}"
                                {{ request('type') === $type ? 'selected' : '' }}
                            >
                                {{ $type }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Location --}}

                <div>

                    <label
                        for="location"
                        class="mb-2 block text-xs font-bold uppercase tracking-wide text-[#68736f]"
                    >
                        Lokasi
                    </label>

                    <select
                        id="location"
                        name="location"
                        class="w-full rounded-xl border border-[#ddd9d0] bg-[#faf9f6] px-4 py-3 text-base text-[#263634] outline-none transition focus:border-[#2f625b] focus:ring-2 focus:ring-[#2f625b]/10"
                    >

                        <option value="">
                            Semua lokasi
                        </option>

                        @foreach($locations ?? [] as $location)

                            <option
                                value="{{ $location }}"
                                {{ request('location') === $location ? 'selected' : '' }}
                            >
                                {{ $location }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Capacity --}}

                <div>

                    <label
                        for="min_capacity"
                        class="mb-2 block text-xs font-bold uppercase tracking-wide text-[#68736f]"
                    >
                        Kapasitas
                    </label>

                    <input
                        type="number"
                        id="min_capacity"
                        name="min_capacity"
                        value="{{ request('min_capacity') }}"
                        min="1"
                        placeholder="Minimal kapasitas"
                        class="w-full rounded-xl border border-[#ddd9d0] bg-[#faf9f6] px-4 py-3 text-base text-[#263634] outline-none transition focus:border-[#2f625b] focus:ring-2 focus:ring-[#2f625b]/10"
                    >

                </div>

            </div>


            <div class="mt-4 flex flex-wrap items-center justify-between gap-3">

                <p class="text-sm text-[#7b8581]">
                    {{ $facilities->count() }} fasilitas ditemukan
                </p>

                <div class="flex gap-2">

                    <a
                        href="{{ route('facilities.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-[#ddd9d0] bg-white px-5 py-2.5 text-sm font-semibold text-[#68736f] transition hover:bg-[#f5f3ee]"
                    >
                        Reset
                    </a>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-[#2f625b] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#244d48]"
                    >
                        Cari
                    </button>

                </div>

            </div>

        </form>

    </section>


    {{-- =========================================================
        QUICK INFO
    ========================================================== --}}

    <section class="grid gap-4 sm:grid-cols-3">

        <div class="rounded-[22px] border border-[#ddd9d0] bg-white p-6 shadow-sm">

            <p class="text-xs font-bold uppercase tracking-wide text-[#7b8581]">
                Total Fasilitas
            </p>

            <p class="mt-2 text-3xl font-bold text-[#263634]">
                {{ $facilities->count() }}
            </p>

            <p class="mt-1 text-sm text-[#68736f]">
                Fasilitas terdaftar
            </p>

        </div>


        <div class="rounded-[22px] border border-[#ddd9d0] bg-white p-6 shadow-sm">

            <p class="text-xs font-bold uppercase tracking-wide text-[#7b8581]">
                Tersedia
            </p>

            <p class="mt-2 text-3xl font-bold text-[#426b5a]">
                {{ $facilities->where('status', 'AVAILABLE')->count() }}
            </p>

            <p class="mt-1 text-sm text-[#68736f]">
                Dapat digunakan
            </p>

        </div>


        <div class="rounded-[22px] border border-[#ddd9d0] bg-white p-6 shadow-sm">

            <p class="text-xs font-bold uppercase tracking-wide text-[#7b8581]">
                Dalam Perbaikan
            </p>

            <p class="mt-2 text-3xl font-bold text-[#a65f3e]">
                {{ $facilities->where('status', 'MAINTENANCE')->count() }}
            </p>

            <p class="mt-1 text-sm text-[#68736f]">
                Sedang tidak tersedia
            </p>

        </div>

    </section>


    {{-- =========================================================
        FACILITY LIST
    ========================================================== --}}

    <section>

        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">

            <div>

                <p class="text-xs font-bold uppercase tracking-[0.15em] text-[#2f625b]">
                    DAFTAR FASILITAS
                </p>

                <h2 class="mt-2 text-4xl font-bold tracking-tight text-[#263634]">
                    Pilih Fasilitas
                </h2>

                <p class="mt-2 text-base text-[#68736f]">
                    Lihat fasilitas dan jadwal yang tersedia untuk kebutuhanmu.
                </p>

            </div>

            <p class="text-sm font-medium text-[#7b8581]">
                {{ $facilities->count() }} fasilitas
            </p>

        </div>


        @if($facilities->count())

            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">

                @foreach($facilities as $f)

                    <article
                        class="group overflow-hidden rounded-[24px] border border-[#ddd9d0] bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg"
                    >

                        {{-- =================================================
                            FACILITY IMAGE
                        ================================================== --}}

                        <div class="relative h-56 overflow-hidden bg-[#e6f0ea]">

                            @if($f->image)

                                <img
                                    src="{{ asset($f->image) }}"
                                    alt="{{ $f->name }}"
                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                >

                            @else

                                {{-- Placeholder jika gambar belum tersedia --}}

                                <div class="absolute inset-0 bg-gradient-to-br from-[#dce9c9] via-[#e6f0ea] to-[#cbdcd4]"></div>

                                <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white/30"></div>

                                <div class="absolute -bottom-16 -left-8 h-44 w-44 rounded-full bg-[#2f625b]/10"></div>

                                <div class="absolute inset-0 flex items-center justify-center">

                                    <div class="flex h-28 w-28 items-center justify-center rounded-[28px] bg-white/80 shadow-sm backdrop-blur-sm">

                                        @if(str_contains(strtolower($f->type), 'lab'))

                                            <span class="text-6xl text-[#2f625b]">
                                                ▣
                                            </span>

                                        @elseif(str_contains(strtolower($f->type), 'kelas'))

                                            <span class="text-6xl text-[#2f625b]">
                                                ▦
                                            </span>

                                        @elseif(
                                            str_contains(strtolower($f->type), 'meeting') ||
                                            str_contains(strtolower($f->type), 'rapat')
                                        )

                                            <span class="text-6xl text-[#a65f3e]">
                                                ◫
                                            </span>

                                        @elseif(
                                            str_contains(strtolower($f->type), 'olahraga') ||
                                            str_contains(strtolower($f->type), 'lapangan')
                                        )

                                            <span class="text-6xl text-[#426b5a]">
                                                ◉
                                            </span>

                                        @else

                                            <span class="text-6xl text-[#2f625b]">
                                                ▤
                                            </span>

                                        @endif

                                    </div>

                                </div>

                            @endif


                            {{-- Status --}}

                            <div class="absolute right-4 top-4">

                                @if($f->status === 'AVAILABLE')

                                    <span class="inline-flex rounded-full bg-[#e6f0ea] px-3 py-1.5 text-xs font-bold text-[#426b5a]">
                                        Tersedia
                                    </span>

                                @elseif($f->status === 'MAINTENANCE')

                                    <span class="inline-flex rounded-full bg-[#f4e9dd] px-3 py-1.5 text-xs font-bold text-[#a65f3e]">
                                        Dalam Perbaikan
                                    </span>

                                @else

                                    <span class="inline-flex rounded-full bg-[#eee8e5] px-3 py-1.5 text-xs font-bold text-[#765f59]">
                                        Tidak Aktif
                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- =================================================
                            CARD CONTENT
                        ================================================== --}}

                        <div class="p-6">

                            {{-- Type --}}

                            <div class="mb-3">

                                <span class="inline-flex rounded-full bg-[#f3f1ec] px-3 py-1 text-sm font-semibold text-[#68736f]">
                                    {{ $f->type }}
                                </span>

                            </div>


                            {{-- Name --}}

                            <h3 class="text-2xl font-bold leading-tight text-[#263634]">
                                {{ $f->name }}
                            </h3>


                            {{-- Description --}}

                            <p class="mt-3 line-clamp-2 text-base leading-6 text-[#68736f]">
                                {{ $f->description }}
                            </p>


                            {{-- Facility Info --}}

                            <div class="mt-5 space-y-3">

                                {{-- Location --}}

                                <div class="flex items-start gap-3">

                                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#e6f0ea] text-[#2f625b]">
                                        ⌖
                                    </div>

                                    <div class="min-w-0">

                                        <p class="text-xs font-bold uppercase tracking-wide text-[#7b8581]">
                                            Lokasi
                                        </p>

                                        <p class="mt-0.5 text-sm font-medium text-[#263634]">
                                            {{ $f->location }}
                                        </p>

                                    </div>

                                </div>


                                {{-- Capacity --}}

                                <div class="flex items-start gap-3">

                                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#f3f1ec] text-[#2f625b]">
                                        ♙
                                    </div>

                                    <div class="min-w-0">

                                        <p class="text-xs font-bold uppercase tracking-wide text-[#7b8581]">
                                            Kapasitas
                                        </p>

                                        <p class="mt-0.5 text-sm font-medium text-[#263634]">
                                            {{ $f->capacity }} orang
                                        </p>

                                    </div>

                                </div>

                            </div>


                            {{-- Action --}}

                            <div class="mt-6 flex items-center justify-between border-t border-[#eeeae4] pt-5">

                                <a
                                    href="{{ route('facilities.show', $f) }}"
                                    class="inline-flex items-center gap-2 text-sm font-semibold text-[#2f625b] transition hover:text-[#244d48]"
                                >

                                    Lihat Detail

                                    <span class="text-base transition-transform duration-200 group-hover:translate-x-1">
                                        →
                                    </span>

                                </a>


                                @auth

                                    @if(auth()->user()->role === 'ADMIN')

                                        <a
                                            href="{{ route('facilities.edit', $f) }}"
                                            class="text-sm font-semibold text-[#68736f] transition hover:text-[#2f625b]"
                                        >
                                            Edit
                                        </a>

                                    @endif

                                @endauth

                            </div>

                        </div>

                    </article>

                @endforeach

            </div>

        @else

            {{-- Empty State --}}

            <div class="rounded-[24px] border border-dashed border-[#cfcac1] bg-white px-6 py-16 text-center">

                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-[#e6f0ea]">

                    <span class="text-4xl text-[#2f625b]">
                        ▤
                    </span>

                </div>


                <h3 class="mt-6 text-2xl font-bold text-[#263634]">
                    Fasilitas tidak ditemukan
                </h3>

                <p class="mx-auto mt-2 max-w-md text-base leading-6 text-[#68736f]">
                    Coba ubah kata pencarian atau filter yang digunakan.
                </p>

                <a
                    href="{{ route('facilities.index') }}"
                    class="mt-6 inline-flex items-center justify-center rounded-xl bg-[#2f625b] px-5 py-3 text-base font-semibold text-white transition hover:bg-[#244d48]"
                >
                    Reset Pencarian
                </a>

            </div>

        @endif

    </section>

</div>

@endsection