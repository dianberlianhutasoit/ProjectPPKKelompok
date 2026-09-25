@extends('layouts.app')

@section('title', 'Reservasi Saya - Campus Facility System')

@section('content')

<div class="mx-auto max-w-6xl">

    {{-- Header --}}
    <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

        <div>
            <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#2f625b]">
                My Reservations
            </p>

            <h1 class="mt-2 text-3xl font-bold tracking-tight text-[#263634]">
                Reservasi Saya
            </h1>

            <p class="mt-2 text-sm leading-6 text-[#68736f]">
                Lihat status dan riwayat pengajuan reservasi fasilitas kamu.
            </p>
        </div>

        <a
            href="{{ route('facilities.index') }}"
            class="inline-flex w-fit items-center justify-center rounded-lg bg-[#2f625b] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#244d48]"
        >
            + Buat Reservasi
        </a>

    </div>


    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-5 border border-[#cfe0d7] bg-[#edf5f0] px-4 py-3 text-sm font-medium text-[#426b5a]">
            {{ session('success') }}
        </div>
    @endif


    {{-- Error Message --}}
    @if(session('error'))
        <div class="mb-5 border border-[#e6d0c5] bg-[#fbf0eb] px-4 py-3 text-sm font-medium text-[#a65f3e]">
            {{ session('error') }}
        </div>
    @endif


    @if($reservations->count())

        <div class="overflow-hidden border border-[#dedbd3] bg-white">

            {{-- Desktop Table --}}
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
                                Peserta
                            </th>

                            <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-[#737d79]">
                                Status
                            </th>

                            <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-[#737d79]">
                                Aksi
                            </th>
                        </tr>
                    </thead>


                    <tbody class="divide-y divide-[#ece9e3]">

                        @foreach($reservations as $reservation)

                            <tr class="transition hover:bg-[#fafaf8]">

                                {{-- Facility --}}
                                <td class="px-5 py-5 align-top">

                                    <p class="font-bold text-[#263634]">
                                        {{ $reservation->facility->name ?? '-' }}
                                    </p>

                                    <p class="mt-1 text-xs text-[#7b8581]">
                                        {{ $reservation->facility->location ?? '-' }}
                                    </p>

                                </td>


                                {{-- Schedule --}}
                                <td class="px-5 py-5 align-top">

                                    <p class="font-semibold text-[#43504d]">
                                        {{ \Carbon\Carbon::parse($reservation->start_time)->translatedFormat('d M Y') }}
                                    </p>

                                    <p class="mt-1 text-xs text-[#7b8581]">
                                        {{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }}
                                        –
                                        {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}
                                    </p>

                                </td>


                                {{-- Participants --}}
                                <td class="px-5 py-5 align-top">

                                    <span class="text-sm font-semibold text-[#43504d]">
                                        {{ $reservation->participants }} orang
                                    </span>

                                </td>


                                {{-- Status --}}
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

                                    @if(in_array($reservation->status, ['REJECTED', 'CANCELLED']) && !empty($reservation->cancel_reason))
                                        <p class="mt-2 text-xs leading-5 text-[#7b8581]">
                                            <span class="font-semibold">Alasan:</span> {{ $reservation->cancel_reason }}
                                        </p>
                                    @endif

                                </td>


                                {{-- Action --}}
                                <td class="px-5 py-5 align-top">

                                    @if($reservation->status === 'PENDING')

                                        <form
                                            method="POST"
                                            action="{{ route('reservations.cancel', $reservation) }}"
                                            onsubmit="return confirm('Batalkan reservasi ini?')"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="text-sm font-semibold text-[#a65f3e] hover:underline"
                                            >
                                                Batalkan
                                            </button>

                                        </form>

                                    @else

                                        <span class="text-xs text-[#9aa19e]">
                                            —
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- Mobile Cards --}}
            <div class="divide-y divide-[#ece9e3] md:hidden">

                @foreach($reservations as $reservation)

                    <div class="p-5">

                        <div class="flex items-start justify-between gap-4">

                            <div>
                                <h2 class="font-bold text-[#263634]">
                                    {{ $reservation->facility->name ?? '-' }}
                                </h2>

                                <p class="mt-1 text-xs text-[#7b8581]">
                                    {{ $reservation->facility->location ?? '-' }}
                                </p>
                            </div>


                            @if($reservation->status === 'APPROVED')

                                <span class="shrink-0 rounded-full bg-[#e6f0ea] px-2.5 py-1 text-[11px] font-bold text-[#426b5a]">
                                    Disetujui
                                </span>

                            @elseif($reservation->status === 'REJECTED')

                                <span class="shrink-0 rounded-full bg-[#f3e8e5] px-2.5 py-1 text-[11px] font-bold text-[#765f59]">
                                    Ditolak
                                </span>

                            @elseif($reservation->status === 'CANCELLED')

                                <span class="shrink-0 rounded-full bg-[#eeeeeb] px-2.5 py-1 text-[11px] font-bold text-[#737a77]">
                                    Dibatalkan
                                </span>

                            @else

                                <span class="shrink-0 rounded-full bg-[#f4e9dd] px-2.5 py-1 text-[11px] font-bold text-[#99633d]">
                                    Menunggu
                                </span>

                            @endif

                        </div>

                        @if(in_array($reservation->status, ['REJECTED', 'CANCELLED']) && !empty($reservation->cancel_reason))
                            <p class="mt-3 text-xs leading-5 text-[#7b8581]">
                                <span class="font-semibold">Alasan:</span> {{ $reservation->cancel_reason }}
                            </p>
                        @endif


                        <div class="mt-5 grid grid-cols-2 gap-4 border-t border-[#eeeae4] pt-4">

                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-wide text-[#8a9490]">
                                    Tanggal
                                </p>

                                <p class="mt-1 text-sm font-semibold text-[#43504d]">
                                    {{ \Carbon\Carbon::parse($reservation->start_time)->translatedFormat('d M Y') }}
                                </p>
                            </div>


                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-wide text-[#8a9490]">
                                    Waktu
                                </p>

                                <p class="mt-1 text-sm font-semibold text-[#43504d]">
                                    {{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }}
                                    –
                                    {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}
                                </p>
                            </div>


                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-wide text-[#8a9490]">
                                    Peserta
                                </p>

                                <p class="mt-1 text-sm font-semibold text-[#43504d]">
                                    {{ $reservation->participants }} orang
                                </p>
                            </div>

                        </div>


                        @if($reservation->status === 'PENDING')

                            <div class="mt-5 border-t border-[#eeeae4] pt-4">

                                <form
                                    method="POST"
                                    action="{{ route('reservations.cancel', $reservation) }}"
                                    onsubmit="return confirm('Batalkan reservasi ini?')"
                                >

                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="submit"
                                        class="text-sm font-semibold text-[#a65f3e] hover:underline"
                                    >
                                        Batalkan Reservasi
                                    </button>

                                </form>

                            </div>

                        @endif

                    </div>

                @endforeach

            </div>

        </div>


        {{-- Pagination --}}
        @if(method_exists($reservations, 'links'))

            <div class="mt-5">
                {{ $reservations->links() }}
            </div>

        @endif


    @else

        {{-- Empty State --}}
        <div class="border border-[#dedbd3] bg-white px-6 py-14 text-center">

            <div class="mx-auto flex h-14 w-14 items-center justify-center bg-[#e6f0ea] text-2xl text-[#2f625b]">
                +
            </div>

            <h2 class="mt-5 text-lg font-bold text-[#263634]">
                Belum ada reservasi
            </h2>

            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-[#7a8581]">
                Kamu belum memiliki pengajuan reservasi fasilitas.
                Pilih fasilitas yang tersedia untuk membuat reservasi.
            </p>

            <a
                href="{{ route('facilities.index') }}"
                class="mt-6 inline-flex items-center justify-center rounded-lg bg-[#2f625b] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#244d48]"
            >
                Lihat Fasilitas
            </a>

        </div>

    @endif

</div>

@endsection