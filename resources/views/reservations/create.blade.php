@extends('layouts.app')

@section('title', 'Ajukan Reservasi')

@section('content')

<div class="mx-auto max-w-2xl">

    <a
        href="{{ route('facilities.show', $facility) }}"
        class="mb-6 inline-flex text-sm text-[#9b8878] hover:text-[#806b5d]">
        ← Kembali ke fasilitas
    </a>

    <div class="rounded-2xl border border-[#e9e3dd] bg-white p-7 shadow-sm">

        <div class="mb-7 border-b border-[#eeeae5] pb-5">

            <p class="text-sm text-[#a2a7ad]">
                Pengajuan Reservasi
            </p>

            <h1 class="mt-1 text-2xl font-semibold text-[#3f4f63]">
                {{ $facility->name }}
            </h1>

            <p class="mt-2 text-sm text-[#8b929b]">
                Isi data reservasi sesuai kebutuhanmu.
            </p>

        </div>

        <form
            method="POST"
            action="{{ route('reservations.store', $facility) }}"
            class="space-y-5">

            @csrf

            <div>
                <label for="identity_number"
                       class="mb-2 block text-sm font-medium text-[#59636f]">
                    NIM / NIP
                </label>

                <input
                    type="text"
                    id="identity_number"
                    name="identity_number"
                    value="{{ old('identity_number') }}"
                    required
                    class="w-full rounded-lg border border-[#dedbd6] bg-[#fcfbfa] px-3 py-2.5 text-sm outline-none focus:border-[#c9b5a7] focus:ring-2 focus:ring-[#eadfd8]">
            </div>

            <div>
                <label for="participants"
                       class="mb-2 block text-sm font-medium text-[#59636f]">
                    Jumlah peserta
                </label>

                <input
                    type="number"
                    id="participants"
                    name="participants"
                    min="1"
                    max="{{ $facility->capacity }}"
                    value="{{ old('participants', 1) }}"
                    required
                    class="w-full rounded-lg border border-[#dedbd6] bg-[#fcfbfa] px-3 py-2.5 text-sm outline-none focus:border-[#c9b5a7] focus:ring-2 focus:ring-[#eadfd8]">

                <p class="mt-1 text-xs text-[#9ca3ab]">
                    Kapasitas fasilitas: {{ $facility->capacity }} orang.
                </p>
            </div>

            <div>
                <label for="date"
                       class="mb-2 block text-sm font-medium text-[#59636f]">
                    Tanggal
                </label>

                <input
                    type="date"
                    id="date"
                    name="date"
                    min="{{ now()->toDateString() }}"
                    value="{{ old('date', now()->toDateString()) }}"
                    required
                    class="w-full rounded-lg border border-[#dedbd6] bg-[#fcfbfa] px-3 py-2.5 text-sm outline-none focus:border-[#c9b5a7] focus:ring-2 focus:ring-[#eadfd8]">
            </div>

            <div class="grid gap-5 sm:grid-cols-2">

                <div>
                    <label for="start_time"
                           class="mb-2 block text-sm font-medium text-[#59636f]">
                        Jam mulai
                    </label>

                    <select
                        id="start_time"
                        name="start_time"
                        required
                        class="w-full rounded-lg border border-[#dedbd6] bg-[#fcfbfa] px-3 py-2.5 text-sm outline-none focus:border-[#c9b5a7] focus:ring-2 focus:ring-[#eadfd8]">

                        @for($hour = 7; $hour < 20; $hour++)

                            @foreach([0, 30] as $minute)

                                @php
                                    $time = sprintf('%02d:%02d', $hour, $minute);
                                @endphp

                                <option
                                    value="{{ $time }}"
                                    @selected(old('start_time') === $time)>
                                    {{ $time }}
                                </option>

                            @endforeach

                        @endfor

                    </select>
                </div>

                <div>
                    <label for="end_time"
                           class="mb-2 block text-sm font-medium text-[#59636f]">
                        Jam selesai
                    </label>

                    <select
                        id="end_time"
                        name="end_time"
                        required
                        class="w-full rounded-lg border border-[#dedbd6] bg-[#fcfbfa] px-3 py-2.5 text-sm outline-none focus:border-[#c9b5a7] focus:ring-2 focus:ring-[#eadfd8]">

                        @for($hour = 7; $hour <= 20; $hour++)

                            @if($hour < 20)
                                @foreach([0, 30] as $minute)

                                    @php
                                        $time = sprintf('%02d:%02d', $hour, $minute);
                                    @endphp

                                    <option
                                        value="{{ $time }}"
                                        @selected(old('end_time') === $time)>
                                        {{ $time }}
                                    </option>

                                @endforeach
                            @else

                                <option
                                    value="20:00"
                                    @selected(old('end_time') === '20:00')>
                                    20:00
                                </option>

                            @endif

                        @endfor

                    </select>
                </div>

            </div>

            <div>
                <label for="purpose"
                       class="mb-2 block text-sm font-medium text-[#59636f]">
                    Tujuan reservasi
                </label>

                <textarea
                    id="purpose"
                    name="purpose"
                    rows="4"
                    required
                    class="w-full rounded-lg border border-[#dedbd6] bg-[#fcfbfa] px-3 py-2.5 text-sm outline-none focus:border-[#c9b5a7] focus:ring-2 focus:ring-[#eadfd8]">{{ old('purpose') }}</textarea>
            </div>

            <button
                type="submit"
                class="w-full rounded-lg bg-[#d8c8bc] px-4 py-2.5 text-sm font-medium text-white transition hover:bg-[#c9b5a7]">
                Ajukan reservasi
            </button>

        </form>

    </div>

</div>

@endsection