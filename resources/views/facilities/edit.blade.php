@extends('layouts.app')

@section('title', 'Edit Fasilitas - Campus Facility System')

@section('content')

<div class="mx-auto max-w-4xl">

    {{-- Header --}}
    <div class="mb-8">

        <a
            href="{{ route('facilities.show', $facility) }}"
            class="inline-flex items-center gap-2 text-sm font-semibold text-[#2f625b] hover:underline"
        >
            ← Kembali ke detail
        </a>

        <div class="mt-6">

            <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#2f625b]">
                Facility Management
            </p>

            <h1 class="mt-2 text-3xl font-bold tracking-tight text-[#263634]">
                Edit Fasilitas
            </h1>

            <p class="mt-2 text-sm leading-6 text-[#68736f]">
                Perbarui informasi fasilitas dan status ketersediaannya.
            </p>

        </div>

    </div>


    {{-- Form --}}
    <form
        method="POST"
        action="{{ route('facilities.update', $facility) }}"
    >

        @csrf
        @method('PUT')

        <div class="grid gap-6 lg:grid-cols-[1fr_280px]">

            {{-- Main Form --}}
            <div class="border border-[#dedbd3] bg-white">

                <div class="border-b border-[#e4e1da] px-6 py-5">

                    <p class="text-xs font-bold uppercase tracking-[0.12em] text-[#2f625b]">
                        Facility Information
                    </p>

                    <h2 class="mt-1 text-lg font-bold text-[#263634]">
                        Informasi Fasilitas
                    </h2>

                </div>


                <div class="space-y-5 p-6">

                    {{-- Name --}}
                    <div>

                        <label
                            for="name"
                            class="mb-2 block text-sm font-semibold text-[#43504d]"
                        >
                            Nama Fasilitas
                        </label>

                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name', $facility->name) }}"
                            required
                            maxlength="255"
                            class="w-full rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-4 py-3 text-sm text-[#263634] outline-none transition placeholder:text-[#a2aaa7] focus:border-[#2f625b] focus:bg-white focus:ring-4 focus:ring-[#2f625b]/10"
                        >

                        @error('name')
                            <p class="mt-1.5 text-xs text-[#a65f3e]">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Type + Location --}}
                    <div class="grid gap-5 sm:grid-cols-2">

                        <div>

                            <label
                                for="type"
                                class="mb-2 block text-sm font-semibold text-[#43504d]"
                            >
                                Tipe Fasilitas
                            </label>

                            <input
                                id="type"
                                type="text"
                                name="type"
                                value="{{ old('type', $facility->type) }}"
                                required
                                maxlength="100"
                                class="w-full rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-4 py-3 text-sm text-[#263634] outline-none transition placeholder:text-[#a2aaa7] focus:border-[#2f625b] focus:bg-white focus:ring-4 focus:ring-[#2f625b]/10"
                            >

                            @error('type')
                                <p class="mt-1.5 text-xs text-[#a65f3e]">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        <div>

                            <label
                                for="location"
                                class="mb-2 block text-sm font-semibold text-[#43504d]"
                            >
                                Lokasi
                            </label>

                            <input
                                id="location"
                                type="text"
                                name="location"
                                value="{{ old('location', $facility->location) }}"
                                required
                                maxlength="255"
                                class="w-full rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-4 py-3 text-sm text-[#263634] outline-none transition placeholder:text-[#a2aaa7] focus:border-[#2f625b] focus:bg-white focus:ring-4 focus:ring-[#2f625b]/10"
                            >

                            @error('location')
                                <p class="mt-1.5 text-xs text-[#a65f3e]">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>


                    {{-- Capacity --}}
                    <div>

                        <label
                            for="capacity"
                            class="mb-2 block text-sm font-semibold text-[#43504d]"
                        >
                            Kapasitas
                        </label>

                        <div class="relative">

                            <input
                                id="capacity"
                                type="number"
                                name="capacity"
                                value="{{ old('capacity', $facility->capacity) }}"
                                required
                                min="1"
                                class="w-full rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-4 py-3 pr-20 text-sm text-[#263634] outline-none transition placeholder:text-[#a2aaa7] focus:border-[#2f625b] focus:bg-white focus:ring-4 focus:ring-[#2f625b]/10"
                            >

                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-[#8a9490]">
                                orang
                            </span>

                        </div>

                        @error('capacity')
                            <p class="mt-1.5 text-xs text-[#a65f3e]">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Description --}}
                    <div>

                        <label
                            for="description"
                            class="mb-2 block text-sm font-semibold text-[#43504d]"
                        >
                            Deskripsi
                            <span class="font-normal text-[#9aa19e]">
                                (opsional)
                            </span>
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            rows="5"
                            placeholder="Jelaskan fasilitas, perlengkapan, atau informasi penting lainnya."
                            class="w-full resize-none rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-4 py-3 text-sm leading-6 text-[#263634] outline-none transition placeholder:text-[#a2aaa7] focus:border-[#2f625b] focus:bg-white focus:ring-4 focus:ring-[#2f625b]/10"
                        >{{ old('description', $facility->description) }}</textarea>

                        @error('description')
                            <p class="mt-1.5 text-xs text-[#a65f3e]">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Status --}}
                    <div>

                        <label
                            for="status"
                            class="mb-2 block text-sm font-semibold text-[#43504d]"
                        >
                            Status Fasilitas
                        </label>

                        <select
                            id="status"
                            name="status"
                            required
                            class="w-full rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-4 py-3 text-sm text-[#43504d] outline-none transition focus:border-[#2f625b] focus:bg-white focus:ring-4 focus:ring-[#2f625b]/10"
                        >

                            <option
                                value="AVAILABLE"
                                @selected(old('status', $facility->status) === 'AVAILABLE')
                            >
                                Tersedia
                            </option>

                            <option
                                value="MAINTENANCE"
                                @selected(old('status', $facility->status) === 'MAINTENANCE')
                            >
                                Maintenance
                            </option>

                            <option
                                value="INACTIVE"
                                @selected(old('status', $facility->status) === 'INACTIVE')
                            >
                                Tidak Aktif
                            </option>

                        </select>

                        @error('status')
                            <p class="mt-1.5 text-xs text-[#a65f3e]">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </div>


                {{-- Actions --}}
                <div class="flex flex-col-reverse gap-3 border-t border-[#e4e1da] bg-[#fafaf8] px-6 py-4 sm:flex-row sm:justify-end">

                    <a
                        href="{{ route('facilities.show', $facility) }}"
                        class="inline-flex items-center justify-center rounded-lg border border-[#d5d2ca] bg-white px-5 py-2.5 text-sm font-semibold text-[#596460] transition hover:bg-[#f1f0eb]"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-[#2f625b] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#244d48] hover:shadow-md"
                    >
                        Simpan Perubahan
                    </button>

                </div>

            </div>


            {{-- Side Information --}}
            <div class="h-fit border border-[#dedbd3] bg-[#2f625b] p-6 text-white">

                <p class="text-xs font-bold uppercase tracking-[0.14em] text-white/60">
                    Facility Status
                </p>

                <h2 class="mt-3 text-lg font-bold">
                    {{ $facility->name }}
                </h2>

                <p class="mt-2 text-sm leading-6 text-white/75">
                    Pastikan informasi fasilitas tetap sesuai dengan kondisi
                    terbaru di lapangan.
                </p>


                <div class="mt-6 border-t border-white/15 pt-5">

                    <p class="text-xs text-white/55">
                        Status saat ini
                    </p>

                    @if($facility->status === 'AVAILABLE')

                        <div class="mt-2 inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1.5 text-xs font-semibold">
                            <span class="h-2 w-2 rounded-full bg-[#b8d1c3]"></span>
                            Tersedia
                        </div>

                    @elseif($facility->status === 'MAINTENANCE')

                        <div class="mt-2 inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1.5 text-xs font-semibold">
                            <span class="h-2 w-2 rounded-full bg-[#dfc9b4]"></span>
                            Maintenance
                        </div>

                    @else

                        <div class="mt-2 inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1.5 text-xs font-semibold">
                            <span class="h-2 w-2 rounded-full bg-[#d8ccc7]"></span>
                            Tidak Aktif
                        </div>

                    @endif

                </div>


                <div class="mt-5 border-t border-white/15 pt-5">

                    <p class="text-xs text-white/55">
                        Lokasi
                    </p>

                    <p class="mt-1 text-sm font-semibold">
                        {{ $facility->location }}
                    </p>

                </div>


                <div class="mt-5 border-t border-white/15 pt-5">

                    <p class="text-xs text-white/55">
                        Kapasitas
                    </p>

                    <p class="mt-1 text-sm font-semibold">
                        {{ $facility->capacity }} orang
                    </p>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection