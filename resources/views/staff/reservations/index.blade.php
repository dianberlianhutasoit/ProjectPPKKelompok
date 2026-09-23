@extends('layouts.app')

@section('title', 'Kelola Reservasi - Campus Facility System')

@section('content')

<div class="mx-auto max-w-7xl">

    {{-- Header --}}
    <div class="mb-7">
        <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#2f625b]">
            Staff Workspace
        </p>

        <div class="mt-2 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-[#263634]">
                    Kelola Reservasi
                </h1>
                <p class="mt-2 text-sm leading-6 text-[#68736f]">
                    Periksa pengajuan reservasi dan proses sesuai ketersediaan fasilitas.
                </p>
            </div>

            <a
                href="{{ route('facilities.index') }}"
                class="inline-flex w-fit items-center justify-center rounded-lg border border-[#d5d2ca] bg-white px-5 py-2.5 text-sm font-semibold text-[#43504d] transition hover:bg-[#f5f4f0]"
            >
                Lihat Fasilitas
            </a>
        </div>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="mb-5 border border-[#cfe0d7] bg-[#edf5f0] px-4 py-3 text-sm font-medium text-[#426b5a]">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 border border-[#e6d0c5] bg-[#fbf0eb] px-4 py-3 text-sm font-medium text-[#a65f3e]">
            {{ session('error') }}
        </div>
    @endif

    @if($reservations->count())

        {{-- Desktop Table View --}}
        <div class="hidden overflow-hidden border border-[#dedbd3] bg-white md:block">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left">
                    <thead class="border-b border-[#e4e1da] bg-[#f7f6f2]">
                        <tr>
                            <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-[#737d79]">Pemohon</th>
                            <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-[#737d79]">Fasilitas</th>
                            <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-[#737d79]">Jadwal</th>
                            <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-[#737d79]">Keperluan</th>
                            <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-[#737d79]">Status</th>
                            <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-[#737d79]">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#ece9e3]">
                        @foreach($reservations as $reservation)
                            <tr class="transition hover:bg-[#fafaf8]">
                                {{-- User --}}
                                <td class="px-5 py-5 align-top">
                                    <p class="font-bold text-[#263634]">
                                        {{ $reservation->user->name ?? '-' }}
                                    </p>
                                    <p class="mt-1 text-xs text-[#7b8581]">
                                        {{ $reservation->identity_number ?? '-' }}
                                    </p>
                                    <p class="mt-1 text-xs text-[#8a9490]">
                                        {{ $reservation->user->email ?? '-' }}
                                    </p>
                                </td>

                                {{-- Facility --}}
                                <td class="px-5 py-5 align-top">
                                    <p class="font-semibold text-[#43504d]">
                                        {{ $reservation->facility->name ?? '-' }}
                                    </p>
                                    <p class="mt-1 text-xs text-[#7b8581]">
                                        {{ $reservation->facility->location ?? '-' }}
                                    </p>
                                </td>

                                {{-- Schedule --}}
                                <td class="px-5 py-5 align-top whitespace-nowrap">
                                    <p class="font-semibold text-[#43504d]">
                                        {{ \Carbon\Carbon::parse($reservation->start_time)->translatedFormat('d M Y') }}
                                    </p>
                                    <p class="mt-1 text-xs text-[#7b8581]">
                                        {{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}
                                    </p>
                                    <p class="mt-1 text-xs text-[#8a9490]">
                                        {{ $reservation->participants }} peserta
                                    </p>
                                </td>

                                {{-- Purpose --}}
                                <td class="max-w-[220px] px-5 py-5 align-top">
                                    <p class="text-sm leading-5 text-[#596460]">
                                        {{ $reservation->purpose }}
                                    </p>
                                    @if($reservation->reason ?? $reservation->cancel_reason)
                                        <p class="mt-2 text-xs italic text-[#a65f3e]">
                                            Alasan: {{ $reservation->reason ?? $reservation->cancel_reason }}
                                        </p>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-5 align-top">
                                    @if($reservation->status === 'APPROVED')
                                        <span class="inline-flex rounded-full bg-[#e6f0ea] px-3 py-1 text-xs font-bold text-[#426b5a]">Disetujui</span>
                                    @elseif($reservation->status === 'REJECTED')
                                        <span class="inline-flex rounded-full bg-[#f3e8e5] px-3 py-1 text-xs font-bold text-[#765f59]">Ditolak</span>
                                    @elseif($reservation->status === 'CANCELLED')
                                        <span class="inline-flex rounded-full bg-[#eeeeeb] px-3 py-1 text-xs font-bold text-[#737a77]">Dibatalkan</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-[#f4e9dd] px-3 py-1 text-xs font-bold text-[#99633d]">Menunggu</span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-5 py-5 align-top">
                                    @if($reservation->status === 'PENDING')
                                        <div class="flex flex-col gap-2">
                                            <form method="POST" action="{{ route('staff.reservations.approve', $reservation) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="w-full rounded-md bg-[#2f625b] px-3 py-2 text-xs font-bold text-white transition hover:bg-[#244d48]">
                                                    Setujui
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('staff.reservations.reject', $reservation) }}" class="flex gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <input
                                                    type="text"
                                                    name="reason"
                                                    placeholder="Alasan penolakan"
                                                    required
                                                    class="min-w-0 w-full rounded-md border border-[#d5d2ca] bg-[#fafaf8] px-2.5 py-2 text-xs outline-none focus:border-[#2f625b] focus:bg-white"
                                                >
                                                <button type="submit" class="rounded-md border border-[#d5d2ca] bg-white px-3 py-2 text-xs font-bold text-[#a65f3e] transition hover:bg-[#fbf0eb]">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($reservation->status === 'APPROVED')
                                        <form method="POST" action="{{ route('staff.reservations.cancel', $reservation) }}" onsubmit="return confirm('Batalkan reservasi ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-xs font-semibold text-[#a65f3e] hover:underline">
                                                Batalkan
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-[#9aa19e]">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Mobile Card View --}}
        <div class="space-y-4 md:hidden">
            @foreach($reservations as $reservation)
                <div class="border border-[#dedbd3] bg-white p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="font-bold text-[#263634]">
                                {{ $reservation->facility->name ?? '-' }}
                            </p>
                            <p class="mt-1 text-xs text-[#7b8581]">
                                {{ $reservation->facility->location ?? '-' }}
                            </p>
                        </div>

                        @if($reservation->status === 'APPROVED')
                            <span class="shrink-0 rounded-full bg-[#e6f0ea] px-2.5 py-1 text-[11px] font-bold text-[#426b5a]">Disetujui</span>
                        @elseif($reservation->status === 'REJECTED')
                            <span class="shrink-0 rounded-full bg-[#f3e8e5] px-2.5 py-1 text-[11px] font-bold text-[#765f59]">Ditolak</span>
                        @elseif($reservation->status === 'CANCELLED')
                            <span class="shrink-0 rounded-full bg-[#eeeeeb] px-2.5 py-1 text-[11px] font-bold text-[#737a77]">Dibatalkan</span>
                        @else
                            <span class="shrink-0 rounded-full bg-[#f4e9dd] px-2.5 py-1 text-[11px] font-bold text-[#99633d]">Menunggu</span>
                        @endif
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-4 border-t border-[#eeeae4] pt-4">
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-[#8a9490]">Pemohon</p>
                            <p class="mt-1 text-sm font-semibold text-[#43504d]">{{ $reservation->user->name ?? '-' }}</p>
                            <p class="mt-1 text-xs text-[#8a9490]">{{ $reservation->identity_number ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-[#8a9490]">Peserta</p>
                            <p class="mt-1 text-sm font-semibold text-[#43504d]">{{ $reservation->participants }} orang</p>
                        </div>

                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-[#8a9490]">Tanggal</p>
                            <p class="mt-1 text-sm font-semibold text-[#43504d]">
                                {{ \Carbon\Carbon::parse($reservation->start_time)->translatedFormat('d M Y') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-[#8a9490]">Waktu</p>
                            <p class="mt-1 text-sm font-semibold text-[#43504d]">
                                {{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-5 border-t border-[#eeeae4] pt-4">
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-[#8a9490]">Keperluan</p>
                        <p class="mt-1 text-sm leading-5 text-[#596460]">
                            {{ $reservation->purpose }}
                        </p>
                        @if($reservation->reason ?? $reservation->cancel_reason)
                            <p class="mt-2 text-xs italic text-[#a65f3e]">
                                Alasan: {{ $reservation->reason ?? $reservation->cancel_reason }}
                            </p>
                        @endif
                    </div>

                    @if($reservation->status === 'PENDING')
                        <div class="mt-5 flex flex-col gap-2 border-t border-[#eeeae4] pt-4">
                            <form method="POST" action="{{ route('staff.reservations.approve', $reservation) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full rounded-lg bg-[#2f625b] px-4 py-2.5 text-sm font-bold text-white transition hover:bg-[#244d48]">
                                    Setujui Reservasi
                                </button>
                            </form>

                            <form method="POST" action="{{ route('staff.reservations.reject', $reservation) }}">
                                @csrf
                                @method('PATCH')
                                <input
                                    type="text"
                                    name="reason"
                                    placeholder="Alasan penolakan"
                                    required
                                    class="mb-2 w-full rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-3 py-2.5 text-sm outline-none focus:border-[#2f625b] focus:bg-white"
                                >
                                <button type="submit" class="w-full rounded-lg border border-[#d5d2ca] bg-white px-4 py-2.5 text-sm font-bold text-[#a65f3e] transition hover:bg-[#fbf0eb]">
                                    Tolak Reservasi
                                </button>
                            </form>
                        </div>
                    @elseif($reservation->status === 'APPROVED')
                        <div class="mt-5 border-t border-[#eeeae4] pt-4">
                            <form method="POST" action="{{ route('staff.reservations.cancel', $reservation) }}" onsubmit="return confirm('Batalkan reservasi ini?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-sm font-semibold text-[#a65f3e] hover:underline">
                                    Batalkan Reservasi
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

    @else
        <div class="rounded-lg border border-[#dedbd3] bg-white p-8 text-center">
            <p class="text-sm text-[#7b8581]">Belum ada pengajuan reservasi.</p>
        </div>
    @endif

</div>

@endsection