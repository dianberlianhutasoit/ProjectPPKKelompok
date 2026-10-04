@extends('layouts.app')

@section('title', 'Reservasi Saya - Campus Facility System')

@section('content')

<div class="mx-auto max-w-6xl">

    <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#2f625b]">
                Aktivitas Anda
            </p>

            <h1 class="mt-2 text-4xl font-bold tracking-tight text-[#263634]">
                Reservasi Saya
            </h1>

            <p class="mt-2 max-w-2xl text-base leading-7 text-[#68736f]">
                Pantau status dan riwayat reservasi fasilitas kampus Anda.
            </p>
        </div>

        <a
            href="{{ route('facilities.index') }}"
            class="inline-flex items-center justify-center rounded-xl bg-[#2f625b] px-5 py-3 text-base font-semibold text-white transition hover:bg-[#244d48]"
        >
            Cari Fasilitas
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-2xl border border-[#cfe1d6] bg-[#e6f0ea] px-5 py-4 text-sm font-medium text-[#426b5a]">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 rounded-2xl border border-[#e4d1ca] bg-[#f3e8e5] px-5 py-4 text-sm font-medium text-[#765f59]">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 rounded-2xl border border-[#e4d1ca] bg-[#f3e8e5] px-5 py-4">
            <p class="mb-2 text-sm font-bold text-[#765f59]">
                Terjadi kesalahan:
            </p>

            <ul class="list-disc space-y-1 pl-5 text-sm text-[#765f59]">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="overflow-hidden rounded-[28px] border border-[#ddd9d0] bg-white shadow-sm">

        @if($reservations->count())

            <div class="hidden overflow-x-auto md:block">

                <table class="w-full text-left">

                    <thead class="border-b border-[#e4e1da] bg-[#f7f6f2]">
                        <tr>

                            <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-[#737d79]">
                                Fasilitas
                            </th>

                            <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-[#737d79]">
                                Jadwal
                            </th>

                            <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-[#737d79]">
                                Status
                            </th>

                            <th class="w-12 px-4 py-4"></th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#ece9e3]">

                        @foreach($reservations as $reservation)

                            <tr class="transition hover:bg-[#fafaf8]">

                                <td class="px-5 py-5 align-top">

                                    <p class="text-base font-bold text-[#263634]">
                                        {{ $reservation->facility->name ?? '-' }}
                                    </p>

                                    <p class="mt-1 text-sm text-[#7b8581]">
                                        {{ $reservation->facility->location ?? '-' }}
                                    </p>

                                </td>

                                <td class="px-5 py-5 align-top">

                                    <p class="text-base font-semibold text-[#43504d]">
                                        {{ \Carbon\Carbon::parse($reservation->start_time)->translatedFormat('d M Y') }}
                                    </p>

                                    <p class="mt-1 text-sm text-[#7b8581]">
                                        {{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }}
                                        –
                                        {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}
                                    </p>

                                </td>

                                <td class="px-5 py-5 align-top">

                                    @if($reservation->status === 'APPROVED')

                                        <span class="inline-flex items-center rounded-full bg-[#e6f0ea] px-3 py-1 text-xs font-bold text-[#426b5a]">
                                            Disetujui
                                        </span>

                                    @elseif($reservation->status === 'REJECTED')

                                        <span class="inline-flex items-center rounded-full bg-[#f3e8e5] px-3 py-1 text-xs font-bold text-[#765f59]">
                                            Ditolak
                                        </span>

                                    @elseif($reservation->status === 'CANCELLED')

                                        <span class="inline-flex items-center rounded-full bg-[#eeeeeb] px-3 py-1 text-xs font-bold text-[#737a77]">
                                            Dibatalkan
                                        </span>

                                    @else

                                        <span class="inline-flex items-center rounded-full bg-[#f4e9dd] px-3 py-1 text-xs font-bold text-[#99633d]">
                                            Menunggu
                                        </span>

                                    @endif

                                </td>

                                <td class="w-12 px-4 py-5 text-right align-middle">

                                    <button
                                        type="button"
                                        onclick="openReservationDetail({{ $reservation->id }})"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-full text-xl font-medium text-[#2f625b] transition hover:bg-[#e6f0ea] hover:text-[#244d48]"
                                        aria-label="Lihat detail reservasi"
                                    >
                                        >
                                    </button>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            <div class="space-y-4 p-4 md:hidden">

                @foreach($reservations as $reservation)

                    <div class="rounded-2xl border border-[#e4e1da] bg-[#fafaf8] p-5">

                        <div>
                            <p class="text-base font-bold text-[#263634]">
                                {{ $reservation->facility->name ?? '-' }}
                            </p>

                            <p class="mt-1 text-sm text-[#7b8581]">
                                {{ $reservation->facility->location ?? '-' }}
                            </p>
                        </div>

                        <div class="mt-4">

                            <p class="text-xs font-bold uppercase tracking-wide text-[#7b8581]">
                                Jadwal
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#43504d]">
                                {{ \Carbon\Carbon::parse($reservation->start_time)->translatedFormat('d M Y') }}
                            </p>

                            <p class="mt-1 text-sm text-[#7b8581]">
                                {{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }}
                                –
                                {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}
                            </p>

                        </div>

                        <div class="mt-4">

                            <p class="text-xs font-bold uppercase tracking-wide text-[#7b8581]">
                                Status
                            </p>

                            <div class="mt-2">

                                @if($reservation->status === 'APPROVED')

                                    <span class="inline-flex items-center rounded-full bg-[#e6f0ea] px-3 py-1 text-xs font-bold text-[#426b5a]">
                                        Disetujui
                                    </span>

                                @elseif($reservation->status === 'REJECTED')

                                    <span class="inline-flex items-center rounded-full bg-[#f3e8e5] px-3 py-1 text-xs font-bold text-[#765f59]">
                                        Ditolak
                                    </span>

                                @elseif($reservation->status === 'CANCELLED')

                                    <span class="inline-flex items-center rounded-full bg-[#eeeeeb] px-3 py-1 text-xs font-bold text-[#737a77]">
                                        Dibatalkan
                                    </span>

                                @else

                                    <span class="inline-flex items-center rounded-full bg-[#f4e9dd] px-3 py-1 text-xs font-bold text-[#99633d]">
                                        Menunggu
                                    </span>

                                @endif

                            </div>

                        </div>

                        <div class="mt-5 flex justify-end border-t border-[#eeeae4] pt-4">

                            <button
                                type="button"
                                onclick="openReservationDetail({{ $reservation->id }})"
                                class="inline-flex h-9 w-9 items-center justify-center rounded-full text-xl font-medium text-[#2f625b] transition hover:bg-[#e6f0ea] hover:text-[#244d48]"
                                aria-label="Lihat detail reservasi"
                            >
                                >
                            </button>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="px-6 py-16 text-center">

                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-[#e6f0ea] text-3xl">
                    📅
                </div>

                <h2 class="mt-5 text-2xl font-bold text-[#263634]">
                    Belum Ada Reservasi
                </h2>

                <p class="mx-auto mt-2 max-w-md text-base leading-7 text-[#68736f]">
                    Anda belum memiliki reservasi fasilitas. Cari fasilitas yang tersedia dan lakukan reservasi sekarang.
                </p>

                <a
                    href="{{ route('facilities.index') }}"
                    class="mt-6 inline-flex items-center justify-center rounded-xl bg-[#2f625b] px-5 py-3 text-base font-semibold text-white transition hover:bg-[#244d48]"
                >
                    Cari Fasilitas
                </a>

            </div>

        @endif

    </div>

</div>

@foreach($reservations as $reservation)

    <div
        id="reservation-detail-{{ $reservation->id }}"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4 py-6"
        onclick="closeReservationDetail({{ $reservation->id }})"
    >

        <div
            class="max-h-[90vh] w-full max-w-xl overflow-y-auto rounded-[28px] bg-white shadow-2xl"
            onclick="event.stopPropagation()"
        >

            <div class="flex items-start justify-between border-b border-[#e4e1da] px-6 py-5">

                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.15em] text-[#2f625b]">
                        Reservasi
                    </p>

                    <h2 class="mt-1 text-2xl font-bold text-[#263634]">
                        Detail Reservasi
                    </h2>
                </div>

                <button
                    type="button"
                    onclick="closeReservationDetail({{ $reservation->id }})"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-full text-xl text-[#68736f] transition hover:bg-[#f3f1ec] hover:text-[#263634]"
                    aria-label="Tutup"
                >
                </button>

            </div>

            <div class="space-y-5 px-6 py-6">

                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-[#7b8581]">
                        Fasilitas
                    </p>

                    <p class="mt-1 text-base font-bold text-[#263634]">
                        {{ $reservation->facility->name ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-[#7b8581]">
                        Lokasi
                    </p>

                    <p class="mt-1 text-sm text-[#43504d]">
                        {{ $reservation->facility->location ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-[#7b8581]">
                        Tanggal
                    </p>

                    <p class="mt-1 text-sm font-semibold text-[#43504d]">
                        {{ \Carbon\Carbon::parse($reservation->start_time)->translatedFormat('d F Y') }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-[#7b8581]">
                        Waktu
                    </p>

                    <p class="mt-1 text-sm font-semibold text-[#43504d]">
                        {{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }}
                        –
                        {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-[#7b8581]">
                        Jumlah Peserta
                    </p>

                    <p class="mt-1 text-sm font-semibold text-[#43504d]">
                        {{ $reservation->participants ?? '-' }} orang
                    </p>
                </div>

                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-[#7b8581]">
                        Tujuan Reservasi
                    </p>

                    <div class="mt-2 rounded-xl bg-[#f7f6f2] px-4 py-3">
                        <p class="text-sm leading-6 text-[#43504d]">
                            {{ $reservation->purpose ?? '-' }}
                        </p>
                    </div>
                </div>

                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-[#7b8581]">
                        Status
                    </p>

                    <div class="mt-2">

                        @if($reservation->status === 'APPROVED')

                            <span class="inline-flex items-center rounded-full bg-[#e6f0ea] px-3 py-1 text-xs font-bold text-[#426b5a]">
                                Disetujui
                            </span>

                        @elseif($reservation->status === 'REJECTED')

                            <span class="inline-flex items-center rounded-full bg-[#f3e8e5] px-3 py-1 text-xs font-bold text-[#765f59]">
                                Ditolak
                            </span>

                        @elseif($reservation->status === 'CANCELLED')

                            <span class="inline-flex items-center rounded-full bg-[#eeeeeb] px-3 py-1 text-xs font-bold text-[#737a77]">
                                Dibatalkan
                            </span>

                        @else

                            <span class="inline-flex items-center rounded-full bg-[#f4e9dd] px-3 py-1 text-xs font-bold text-[#99633d]">
                                Menunggu Persetujuan
                            </span>

                        @endif

                    </div>

                </div>

                @if(
                    !empty($reservation->cancel_reason) ||
                    !empty($reservation->rejection_reason) ||
                    !empty($reservation->reason)
                )

                    <div>

                        <p class="text-xs font-bold uppercase tracking-wide text-[#7b8581]">
                            Alasan
                        </p>

                        <div class="mt-2 rounded-xl bg-[#f3e8e5] px-4 py-3">

                            <p class="text-sm leading-6 text-[#765f59]">
                                {{
                                    $reservation->cancel_reason
                                    ?? $reservation->rejection_reason
                                    ?? $reservation->reason
                                }}
                            </p>

                        </div>

                    </div>

                @endif

            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-[#e4e1da] px-6 py-5 sm:flex-row sm:items-center sm:justify-end">

                <button
                    type="button"
                    onclick="closeReservationDetail({{ $reservation->id }})"
                    class="inline-flex items-center justify-center rounded-xl border border-[#d9d6cf] bg-white px-5 py-3 text-sm font-semibold text-[#43504d] transition hover:bg-[#f7f6f2]"
                >
                    Tutup
                </button>

                @if($reservation->status === 'PENDING')

                    <form
                        method="POST"
                        action="{{ route('reservations.cancel', $reservation->id) }}"
                        onsubmit="return confirm('Apakah Anda yakin ingin membatalkan reservasi ini?')"
                    >

                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center rounded-xl bg-[#765f59] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#624d48] sm:w-auto"
                        >
                            Batalkan Reservasi
                        </button>

                    </form>

                @endif

            </div>

        </div>

    </div>

@endforeach

<script>

    function openReservationDetail(id) {

        const modal = document.getElementById('reservation-detail-' + id);

        if (!modal) {
            return;
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.body.classList.add('overflow-hidden');
    }

    function closeReservationDetail(id) {

        const modal = document.getElementById('reservation-detail-' + id);

        if (!modal) {
            return;
        }

        modal.classList.add('hidden');
        modal.classList.remove('flex');

        document.body.classList.remove('overflow-hidden');
    }

    document.addEventListener('keydown', function(event) {

        if (event.key !== 'Escape') {
            return;
        }

        document.querySelectorAll('[id^="reservation-detail-"]').forEach(function(modal) {

            if (!modal.classList.contains('hidden')) {

                modal.classList.add('hidden');
                modal.classList.remove('flex');

            }

        });

        document.body.classList.remove('overflow-hidden');

    });

</script>

@endsection
