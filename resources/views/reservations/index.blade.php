@extends('layouts.app')

@section('title', 'Reservasi Saya')

@section('content')

<div class="mb-8">

    <p class="mb-2 text-sm font-medium text-[#b09b8c]">
        Reservation
    </p>

    <h1 class="text-2xl font-semibold text-[#3f4f63]">
        Reservasi Saya
    </h1>

    <p class="mt-2 text-sm text-[#8b929b]">
        Lihat status dan riwayat reservasi fasilitas yang kamu ajukan.
    </p>

</div>

<div class="space-y-4">

    @forelse($reservations as $reservation)

        <div class="rounded-2xl border border-[#e9e3dd] bg-white p-6 shadow-sm">

            <div class="flex flex-col justify-between gap-4 sm:flex-row">

                <div>

                    <h2 class="font-semibold text-[#3f4f63]">
                        {{ $reservation->facility->name }}
                    </h2>

                    <p class="mt-1 text-sm text-[#8b929b]">
                        {{ $reservation->start_time->format('d M Y, H:i') }}
                        -
                        {{ $reservation->end_time->format('H:i') }}
                    </p>

                    <p class="mt-3 text-sm text-[#626b75]">
                        {{ $reservation->purpose }}
                    </p>

                </div>

                <div class="flex flex-col items-start gap-3 sm:items-end">

                    @if($reservation->status === 'PENDING')

                        <span class="rounded-full bg-[#f7eee4] px-3 py-1.5 text-xs font-medium text-[#92745e]">
                            Menunggu persetujuan
                        </span>

                        <form
                            action="{{ route('reservations.cancel', $reservation) }}"
                            method="POST">
                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="text-sm text-[#9b6f6f] hover:text-[#7d5555]"
                                onclick="return confirm('Batalkan reservasi ini?')">
                                Batalkan
                            </button>
                        </form>

                    @elseif($reservation->status === 'APPROVED')

                        <span class="rounded-full bg-[#e8f2e8] px-3 py-1.5 text-xs font-medium text-[#607560]">
                            Disetujui
                        </span>

                    @elseif($reservation->status === 'REJECTED')

                        <span class="rounded-full bg-[#f1eded] px-3 py-1.5 text-xs font-medium text-[#806f6f]">
                            Ditolak
                        </span>

                    @else

                        <span class="rounded-full bg-[#f1eded] px-3 py-1.5 text-xs font-medium text-[#806f6f]">
                            Dibatalkan
                        </span>

                    @endif

                </div>

            </div>

            @if($reservation->cancel_reason)

                <div class="mt-4 border-t border-[#eeeae5] pt-4">

                    <p class="text-xs font-medium text-[#8b929b]">
                        Keterangan
                    </p>

                    <p class="mt-1 text-sm text-[#626b75]">
                        {{ $reservation->cancel_reason }}
                    </p>

                </div>

            @endif

        </div>

    @empty

        <div class="rounded-2xl border border-[#e9e3dd] bg-white p-8 text-center">

            <p class="text-sm text-[#8b929b]">
                Belum ada reservasi.
            </p>

        </div>

    @endforelse

</div>

@endsection