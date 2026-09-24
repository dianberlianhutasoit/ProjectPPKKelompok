@extends('layouts.app')

@section('title', 'Ajukan Reservasi - Campus Facility')

@section('content')

<div class="mx-auto max-w-5xl">

    {{-- Header --}}
    <div class="mb-7">
        <a
            href="{{ route('facilities.show', $facility) }}"
            class="inline-flex items-center gap-2 text-sm font-semibold text-[#2f625b] hover:underline"
        >
            ← Kembali ke fasilitas
        </a>

        <div class="mt-5">
            <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#2f625b]">
                Reservation
            </p>

            <h1 class="mt-2 text-3xl font-bold tracking-tight text-[#263634]">
                Ajukan Reservasi
            </h1>

            <p class="mt-2 text-sm leading-6 text-[#68736f]">
                Lengkapi informasi penggunaan fasilitas yang ingin kamu reservasi.
            </p>
        </div>
    </div>


    <div class="grid gap-6 lg:grid-cols-[320px_1fr]">

        {{-- Facility Information --}}
        <div class="h-fit border border-[#dedbd3] bg-white">

            <div class="border-b border-[#e4e1da] px-5 py-4">
                <p class="text-xs font-bold uppercase tracking-[0.12em] text-[#2f625b]">
                    Fasilitas
                </p>

                <h2 class="mt-1 text-lg font-bold text-[#263634]">
                    {{ $facility->name }}
                </h2>
            </div>

            <div class="space-y-5 p-5">

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-[#8a9490]">
                        Tipe
                    </p>

                    <p class="mt-1 text-sm font-semibold text-[#43504d]">
                        {{ $facility->type }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-[#8a9490]">
                        Lokasi
                    </p>

                    <p class="mt-1 text-sm font-semibold text-[#43504d]">
                        {{ $facility->location }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-[#8a9490]">
                        Kapasitas
                    </p>

                    <p class="mt-1 text-sm font-semibold text-[#43504d]">
                        {{ $facility->capacity }} orang
                    </p>
                </div>

                <div class="border-t border-[#eeeae4] pt-5">

                    <p class="text-xs font-semibold uppercase tracking-wide text-[#8a9490]">
                        Status
                    </p>

                    <span class="mt-2 inline-flex rounded-full bg-[#e7f0eb] px-3 py-1.5 text-xs font-bold text-[#376453]">
                        Tersedia
                    </span>

                </div>

            </div>
        </div>


        {{-- Reservation Form --}}
        <div class="border border-[#dedbd3] bg-white">

            <div class="border-b border-[#e4e1da] px-6 py-5">
                <p class="text-xs font-bold uppercase tracking-[0.12em] text-[#2f625b]">
                    Reservation Form
                </p>

                <h2 class="mt-1 text-lg font-bold text-[#263634]">
                    Detail Reservasi
                </h2>
            </div>


            <form
                action="{{ route('reservations.store', $facility) }}"
                method="POST"
            >

                @csrf

                <div class="space-y-5 p-6">

                    {{-- Nama Pemohon --}}
                    <div>
                        <label
                            for="applicant_name"
                            class="mb-2 block text-sm font-semibold text-[#43504d]"
                        >
                            Nama Pemohon
                        </label>

                        <input
                            id="applicant_name"
                            type="text"
                            value="{{ auth()->user()->name }}"
                            readonly
                            class="w-full cursor-not-allowed rounded-lg border border-[#d5d2ca] bg-[#f4f4f1] px-4 py-3 text-sm text-[#68736f] outline-none"
                        >
                    </div>


                    {{-- Date --}}
                    <div>
                        <label
                            for="date"
                            class="mb-2 block text-sm font-semibold text-[#43504d]"
                        >
                            Tanggal Reservasi
                        </label>

                        <input
                            id="date"
                            type="date"
                            name="date"
                            value="{{ old('date', request('date', now()->toDateString())) }}"
                            min="{{ now()->toDateString() }}"
                            required
                            class="w-full rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-4 py-3 text-sm text-[#263634] outline-none transition focus:border-[#2f625b] focus:bg-white focus:ring-4 focus:ring-[#2f625b]/10"
                        >

                        @error('date')
                            <p class="mt-1.5 text-xs text-[#a65f3e]">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- Time --}}
                    <div class="grid gap-5 sm:grid-cols-2">

                        <div>
                            <label
                                for="start_time"
                                class="mb-2 block text-sm font-semibold text-[#43504d]"
                            >
                                Jam Mulai
                            </label>

                            <input
                                id="start_time"
                                type="time"
                                name="start_time"
                                value="{{ old('start_time', request('start', '07:00')) }}"
                                min="07:00"
                                max="19:30"
                                step="1800"
                                required
                                class="w-full rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-4 py-3 text-sm text-[#263634] outline-none transition focus:border-[#2f625b] focus:bg-white focus:ring-4 focus:ring-[#2f625b]/10"
                            >

                            @error('start_time')
                                <p class="mt-1.5 text-xs text-[#a65f3e]">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>


                        <div>
                            <label
                                for="end_time"
                                class="mb-2 block text-sm font-semibold text-[#43504d]"
                            >
                                Jam Selesai
                            </label>

                            <input
                                id="end_time"
                                type="time"
                                name="end_time"
                                value="{{ old('end_time', request('end', '07:30')) }}"
                                min="07:30"
                                max="20:00"
                                step="1800"
                                required
                                class="w-full rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-4 py-3 text-sm text-[#263634] outline-none transition focus:border-[#2f625b] focus:bg-white focus:ring-4 focus:ring-[#2f625b]/10"
                            >

                            @error('end_time')
                                <p class="mt-1.5 text-xs text-[#a65f3e]">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>


                    {{-- Participants --}}
                    <div>
                        <label
                            for="participants"
                            class="mb-2 block text-sm font-semibold text-[#43504d]"
                        >
                            Jumlah Peserta
                        </label>

                        <input
                            id="participants"
                            type="number"
                            name="participants"
                            value="{{ old('participants', 1) }}"
                            min="1"
                            max="{{ $facility->capacity }}"
                            required
                            class="w-full rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-4 py-3 text-sm text-[#263634] outline-none transition focus:border-[#2f625b] focus:bg-white focus:ring-4 focus:ring-[#2f625b]/10"
                        >

                        <p class="mt-1.5 text-xs text-[#8a9490]">
                            Maksimal {{ $facility->capacity }} orang.
                        </p>

                        @error('participants')
                            <p class="mt-1.5 text-xs text-[#a65f3e]">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- Purpose --}}
                    <div>
                        <label
                            for="purpose"
                            class="mb-2 block text-sm font-semibold text-[#43504d]"
                        >
                            Keperluan / Tujuan Kegiatan
                        </label>

                        <textarea
                            id="purpose"
                            name="purpose"
                            rows="5"
                            required
                            maxlength="255"
                            placeholder="Jelaskan tujuan penggunaan fasilitas..."
                            class="w-full resize-none rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-4 py-3 text-sm leading-6 text-[#263634] outline-none transition placeholder:text-[#a2aaa7] focus:border-[#2f625b] focus:bg-white focus:ring-4 focus:ring-[#2f625b]/10"
                        >{{ old('purpose') }}</textarea>

                        @error('purpose')
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
                        Ajukan Reservasi
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection