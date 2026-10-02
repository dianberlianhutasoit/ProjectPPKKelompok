@extends('layouts.app')

@section('title', 'Fasilitas Kampus')

@section('content')

<div class="space-y-10">

    <section class="relative overflow-hidden rounded-[28px] bg-[#2f625b]">

        <div class="absolute -right-24 -top-24 h-80 w-80 rounded-full bg-[#dce9c9]/10"></div>
        <div class="absolute -bottom-32 -left-20 h-96 w-96 rounded-full bg-[#a65f3e]/10"></div>

        <div class="relative grid min-h-[390px] lg:grid-cols-[1.05fr_0.95fr]">

            <div class="flex flex-col justify-center px-7 py-12 sm:px-10 lg:px-14">

                <div class="inline-flex w-fit items-center gap-2 rounded-full bg-white/10 px-4 py-2 backdrop-blur-sm">

                    <span class="h-2.5 w-2.5 rounded-full bg-[#dce9c9]"></span>

                    <span class="text-xs font-bold uppercase tracking-[0.14em] text-white">
                        Campus Facility
                    </span>

                </div>

                <h1 class="mt-6 max-w-xl text-5xl font-bold leading-tight tracking-tight text-white sm:text-6xl">

                    Temukan Fasilitas Kampus

                    <span class="block text-[#dce9c9]">
                        untuk Kebutuhanmu
                    </span>

                </h1>

                <p class="mt-5 max-w-lg text-base leading-7 text-[#e5eeea] sm:text-lg">
                    Temukan ruang dan fasilitas kampus yang sesuai
                    dengan kebutuhan kegiatan akademik maupun non-akademik.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">

                    <a
                        href="#daftar-fasilitas"
                        class="inline-flex items-center gap-2 rounded-full bg-white px-6 py-3.5 text-base font-bold text-[#2f625b] transition hover:bg-[#f3f1ec]"
                    >
                        Jelajahi Fasilitas
                        <span class="text-lg">↓</span>
                    </a>

                    <div class="inline-flex items-center rounded-full border border-white/25 bg-white/10 px-5 py-3.5 text-base font-medium text-white">
                        07:00 — 20:00
                    </div>

                    @auth
                        @if(auth()->user()->role === 'ADMIN')

                            <a
                                href="{{ route('facilities.create') }}"
                                class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/10 px-5 py-3.5 text-base font-semibold text-white transition hover:bg-white/20"
                            >
                                <span class="text-xl leading-none">+</span>
                                Tambah Fasilitas
                            </a>

                        @endif
                    @endauth

                </div>

            </div>

            <div class="relative hidden min-h-[390px] lg:block">

                <div class="absolute inset-0 flex items-center justify-center px-12">

                    <div class="relative w-full max-w-md">

                        <div class="overflow-hidden rounded-[26px] bg-[#f3f1ec] shadow-2xl">

                            <div class="h-9 bg-[#263634]"></div>

                            <div class="grid grid-cols-3 gap-3 bg-[#e8e5dc] p-7">

                                <div class="h-28 rounded-xl bg-[#d4dfd8]"></div>

                                <div class="h-28 rounded-xl bg-[#c5d5cd]"></div>

                                <div class="h-28 rounded-xl bg-[#d4dfd8]"></div>

                                <div class="h-20 rounded-xl bg-[#b9cbc2]"></div>

                                <div class="h-20 rounded-xl bg-[#2f625b]"></div>

                                <div class="h-20 rounded-xl bg-[#b9cbc2]"></div>

                            </div>

                            <div class="h-9 bg-[#dce9c9]"></div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <section class="-mt-16 relative z-10 px-4 sm:px-8">

        <div class="rounded-[22px] border border-[#dedbd3] bg-[#f3f1ec] p-3 shadow-[0_12px_35px_rgba(38,54,52,0.10)]">

            <form
                method="GET"
                action="{{ route('facilities.index') }}"
                class="grid grid-cols-1 gap-2 md:grid-cols-2 xl:grid-cols-[1.3fr_0.8fr_0.8fr_auto]"
            >

                <div class="relative">

                    <label
                        for="type"
                        class="sr-only"
                    >
                        Tipe fasilitas
                    </label>

                    <select
                        id="type"
                        name="type"
                        class="w-full appearance-none rounded-xl border border-[#d5d2ca] bg-white px-4 py-4 pr-10 text-base text-[#43504d] outline-none transition focus:border-[#2f625b] focus:ring-4 focus:ring-[#2f625b]/10"
                    >

                        <option value="">
                            Semua tipe fasilitas
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

                    <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-base text-[#7b8581]">
                        ↓
                    </span>

                </div>

                <div>

                    <label
                        for="location"
                        class="sr-only"
                    >
                        Lokasi
                    </label>

                    <input
                        id="location"
                        type="text"
                        name="location"
                        value="{{ $filters['location'] ?? '' }}"
                        maxlength="255"
                        placeholder="Lokasi fasilitas"
                        class="w-full rounded-xl border border-[#d5d2ca] bg-white px-4 py-4 text-base text-[#263634] outline-none transition placeholder:text-[#9ba39f] focus:border-[#2f625b] focus:ring-4 focus:ring-[#2f625b]/10"
                    >

                </div>

                <div>

                    <label
                        for="min_capacity"
                        class="sr-only"
                    >
                        Kapasitas minimal
                    </label>

                    <input
                        id="min_capacity"
                        type="number"
                        name="min_capacity"
                        value="{{ $filters['min_capacity'] ?? '' }}"
                        min="1"
                        placeholder="Kapasitas minimal"
                        class="w-full rounded-xl border border-[#d5d2ca] bg-white px-4 py-4 text-base text-[#263634] outline-none transition placeholder:text-[#9ba39f] focus:border-[#2f625b] focus:ring-4 focus:ring-[#2f625b]/10"
                    >

                </div>

                <div class="flex gap-2">

                    <button
                        type="submit"
                        class="flex-1 rounded-xl bg-[#2f625b] px-6 py-4 text-base font-bold text-white transition hover:bg-[#244d48] hover:shadow-md"
                    >
                        Cari
                    </button>

                    @if(
                        ($filters['type'] ?? '') ||
                        ($filters['location'] ?? '') ||
                        ($filters['min_capacity'] ?? '')
                    )

                        <a
                            href="{{ route('facilities.index') }}"
                            class="flex items-center justify-center rounded-xl border border-[#d5d2ca] bg-white px-5 text-base font-semibold text-[#596460] transition hover:bg-[#f4f3ef]"
                        >
                            Reset
                        </a>

                    @endif

                </div>

            </form>

        </div>

    </section>

    <section class="grid gap-4 sm:grid-cols-3">

        <div class="rounded-[18px] border border-[#ddd9d0] bg-white px-6 py-6">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-xs font-bold uppercase tracking-[0.12em] text-[#8a9490]">
                        Katalog
                    </p>

                    <p class="mt-2 text-3xl font-bold text-[#263634]">
                        {{ $facilities->count() }}
                    </p>

                    <p class="mt-1 text-sm text-[#7b8581]">
                        Fasilitas tersedia
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#e6f0ea] text-xl text-[#2f625b]">
                    ▦
                </div>

            </div>

        </div>

        <div class="rounded-[18px] border border-[#ddd9d0] bg-white px-6 py-6">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-xs font-bold uppercase tracking-[0.12em] text-[#8a9490]">
                        Operasional
                    </p>

                    <p class="mt-2 text-3xl font-bold text-[#263634]">
                        07—20
                    </p>

                    <p class="mt-1 text-sm text-[#7b8581]">
                        Jam operasional
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#f3f1ec] text-xl text-[#2f625b]">
                    ◷
                </div>

            </div>

        </div>

        <div class="rounded-[18px] border border-[#ddd9d0] bg-white px-6 py-6">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-xs font-bold uppercase tracking-[0.12em] text-[#8a9490]">
                        Reservasi
                    </p>

                    <p class="mt-2 text-3xl font-bold text-[#263634]">
                        30
                    </p>

                    <p class="mt-1 text-sm text-[#7b8581]">
                        Menit per slot
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#f4e9dd] text-xl text-[#a65f3e]">
                    ◫
                </div>

            </div>

        </div>

    </section>

    <section
        id="daftar-fasilitas"
        class="scroll-mt-28"
    >

        <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#a65f3e]">
            Explore
        </p>

        <div class="mt-2 flex items-center gap-3">

            <h2 class="text-4xl font-bold tracking-tight text-[#263634]">
                Semua Fasilitas
            </h2>

            <span class="rounded-full bg-[#e6f0ea] px-3.5 py-1.5 text-sm font-bold text-[#2f625b]">
                {{ $facilities->count() }}
            </span>

        </div>

        <p class="mt-3 text-base leading-7 text-[#68736f]">
            Pilih fasilitas untuk melihat informasi dan jadwal ketersediaannya.
        </p>

    </section>

    @if($facilities->count())

        <section class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">

            @foreach($facilities as $f)

                <article
                    class="group flex flex-col overflow-hidden rounded-[22px] border border-[#ddd9d0] bg-white transition duration-300 hover:-translate-y-1 hover:border-[#b8c9c3] hover:shadow-[0_14px_35px_rgba(38,54,52,0.10)]"
                >

                    <div class="relative h-56 overflow-hidden bg-[#e6f0ea]">

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

                        <div class="absolute right-4 top-4">

                            @if($f->status === 'AVAILABLE')

                                <span class="inline-flex items-center gap-2 rounded-full bg-[#2f625b] px-3.5 py-2 text-xs font-bold text-white shadow-sm">

                                    <span class="h-2 w-2 rounded-full bg-[#dce9c9]"></span>

                                    Tersedia

                                </span>

                            @elseif($f->status === 'MAINTENANCE')

                                <span class="inline-flex items-center gap-2 rounded-full bg-[#a65f3e] px-3.5 py-2 text-xs font-bold text-white shadow-sm">

                                    <span class="h-2 w-2 rounded-full bg-[#f4e9dd]"></span>

                                    Dalam Perbaikan

                                </span>

                            @else

                                <span class="inline-flex items-center gap-2 rounded-full bg-[#765f59] px-3.5 py-2 text-xs font-bold text-white shadow-sm">

                                    <span class="h-2 w-2 rounded-full bg-[#eee8e5]"></span>

                                    Nonaktif

                                </span>

                            @endif

                        </div>

                        <div class="absolute bottom-4 left-4">

                            <span class="rounded-full bg-white/90 px-3.5 py-2 text-sm font-bold text-[#2f625b] shadow-sm backdrop-blur-sm">
                                {{ $f->type }}
                            </span>

                        </div>

                    </div>

                    <div class="flex flex-1 flex-col p-6">

                        <div>

                            <h3 class="text-2xl font-bold leading-8 text-[#263634]">
                                {{ $f->name }}
                            </h3>

                            @if($f->description)

                                <p class="mt-3 line-clamp-2 text-base leading-7 text-[#68736f]">
                                    {{ $f->description }}
                                </p>

                            @else

                                <p class="mt-3 text-base italic leading-7 text-[#a0a6a3]">
                                    Tidak ada deskripsi fasilitas.
                                </p>

                            @endif

                        </div>

                        <div class="mt-6 grid grid-cols-2 gap-3">

                            <div class="rounded-xl bg-[#f5f3ee] px-4 py-3.5">

                                <p class="text-xs font-bold uppercase tracking-[0.1em] text-[#909995]">
                                    Lokasi
                                </p>

                                <p class="mt-1.5 line-clamp-1 text-sm font-semibold text-[#43504d]">
                                    {{ $f->location }}
                                </p>

                            </div>

                            <div class="rounded-xl bg-[#f5f3ee] px-4 py-3.5">

                                <p class="text-xs font-bold uppercase tracking-[0.1em] text-[#909995]">
                                    Kapasitas
                                </p>

                                <p class="mt-1.5 text-sm font-semibold text-[#43504d]">
                                    {{ $f->capacity }} orang
                                </p>

                            </div>

                        </div>

                        <div class="mt-6 flex items-center justify-between gap-3">

                            <a
                                href="{{ route('facilities.show', $f) }}"
                                class="inline-flex items-center gap-2 rounded-full bg-[#e6f0ea] px-5 py-3 text-sm font-bold text-[#2f625b] transition group-hover:bg-[#2f625b] group-hover:text-white"
                            >
                                Lihat Detail

                                <span class="text-base transition group-hover:translate-x-1">
                                    →
                                </span>

                            </a>

                            @auth

                                @if(auth()->user()->role === 'ADMIN')

                                    <a
                                        href="{{ route('facilities.edit', $f) }}"
                                        class="text-sm font-semibold text-[#7a8581] transition hover:text-[#2f625b]"
                                    >
                                        Edit
                                    </a>

                                @endif

                            @endauth

                        </div>

                    </div>

                </article>

            @endforeach

        </section>

    @else

        <section class="rounded-[24px] border border-dashed border-[#cbc8c0] bg-white px-6 py-16 text-center">

            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#e6f0ea] text-3xl text-[#2f625b]">
                ⌕
            </div>

            <h2 class="mt-5 text-2xl font-bold text-[#263634]">
                Belum ada fasilitas
            </h2>

            <p class="mx-auto mt-3 max-w-md text-base leading-7 text-[#7a8581]">
                Tidak ada fasilitas yang sesuai dengan filter yang dipilih.
                Coba ubah filter atau tampilkan seluruh fasilitas.
            </p>

            <a
                href="{{ route('facilities.index') }}"
                class="mt-7 inline-flex rounded-full bg-[#2f625b] px-6 py-3 text-base font-bold text-white transition hover:bg-[#244d48]"
            >
                Tampilkan Semua
            </a>

        </section>

    @endif

</div>

@endsection
