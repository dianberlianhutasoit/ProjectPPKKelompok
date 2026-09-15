@extends('layouts.app')

@section('title', 'Daftar Fasilitas')

@section('content')

{{-- Header --}}
<div class="mb-8">

    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">

        <div>

            <p class="mb-2 text-sm font-medium text-[#b09b8c]">
                Campus Facility
            </p>

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