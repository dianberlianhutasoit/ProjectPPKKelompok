@extends('layouts.app')

@section('title', 'Buat Reservasi - Campus Facility System')

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

        <div class="mt-6">

            <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#2f625b]">
                Facility Reservation
            </p>

            <h1 class="mt-2 text-3xl font-bold tracking-tight text-[#263634]">
                Buat Reservasi
            </h1>

            <p class="mt-2 text-sm leading-6 text-[#68736f]">
                Lengkapi informasi berikut untuk mengajukan reservasi fasilitas.
            </p>

        </div>

    </div>


    <div class="grid gap-6 lg:grid-cols-[280px_1fr]">

        {{-- Reservation Summary --}}
        <div class="h-fit border border-[#dedbd3] bg-[#2f625b] p-6 text-white">

            <p class="text-xs font-bold uppercase tracking-[0.14em] text-white/60">
                Facility
            </p>

            <h2 class="mt-3 text-xl font-bold leading-7">
                {{ $facility->name }}
            </h2>

            <p class="mt-1 text-sm text-white/65">
                {{ $facility->type }}
            </p>


            <div class="mt-7 space-y-5 border-t border-white/15 pt-5">

                <div>
                    <p class="text-xs text-white/55">
                        Lokasi
                    </p>

                    <p class="mt-1 text-sm font-semibold">
                        {{ $facility->location }}
                    </p>
                </div>


                <div>
                    <p class="text-xs text-white/55">
                        Kapasitas
                    </p>

                    <p class="mt-1 text-sm font-semibold">
                        {{ $facility->capacity }} orang
                    </p>
                </div>


                <div>
                    <p class="text-xs text-white/55">
                        Tanggal
                    </p>

                    <p class="mt-1 text-sm font-semibold">
                        {{ \Carbon\Carbon::parse(request('date'))->translatedFormat('d F Y') }}
                    </p>
                </div>


                <div>
                    <p class="text-xs text-white/55">
                        Waktu
                    </p>

                    <p class="mt-1 text-sm font-semibold">
                        {{ request('start') }} — {{ request('end') }}
                    </p>
                </div>

            </div>


            <div class="mt-7 border-t border-white/15 pt-5">

                <p class="text-xs leading-5 text-white/60">
                    Reservasi akan berstatus <strong class="text-white">PENDING</strong>
                    sampai diproses oleh staff.
                </p>

            </div>

        </div>


        {{-- Form --}}
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
                method="POST"
                action="{{ route('reservations.store', $facility) }}"
            >

                @csrf


                <div class="space-y-5 p-6">

                    {{-- Participants --}}
                    <div>

                        <label
                            for="participants"
                            class="mb-2 block text-sm font-semibold text-[#43504d]"
                        >
                            Jumlah Peserta
                        </label>

                        <div class="relative">

                            <input
                                id="participants"
                                type="number"
                                name="participants"
                                value="{{ old('participants', 1) }}"
                                required
                                min="1"
                                max="{{ $facility->capacity }}"
                                placeholder="Contoh: 20"
                                class="w-full rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-4 py-3 pr-20 text-sm text-[#263634] outline-none transition placeholder:text-[#a2aaa7] focus:border-[#2f625b] focus:bg-white focus:ring-4 focus:ring-[#2f625b]/10"
                            >

                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-[#8a9490]">
                                orang
                            </span>

                        </div>

                        <p class="mt-1.5 text-xs text-[#8a9490]">
                            Maksimal {{ $facility->capacity }} orang.
                        </p>

                        @error('participants')
                            <p class="mt-1.5 text-xs text-[#a65f3e]">
                                {{ $message }}
                            </p>
                        @enderror

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
                            value="{{ old('date', request('date')) }}"
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
                                Waktu Mulai
                            </label>

                            <input
                                id="start_time"
                                type="time"
                                name="start_time"
                                value="{{ old('start_time', request('start')) }}"
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
                                Waktu Selesai
                            </label>

                            <input
                                id="end_time"
                                type="time"
                                name="end_time"
                                value="{{ old('end_time', request('end')) }}"
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


                    {{-- Purpose --}}
                    <div>

                        <label
                            for="purpose"
                            class="mb-2 block text-sm font-semibold text-[#43504d]"
                        >
                            Keperluan / Tujuan
                        </label>

                        <textarea
                            id="purpose"
                            name="purpose"
                            rows="5"
                            required
                            placeholder="Jelaskan kegiatan yang akan dilakukan di fasilitas ini."
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
                        href="{{ route('facilities.show', [
                            'facility' => $facility,
                            'date' => request('date')
                        ]) }}"
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