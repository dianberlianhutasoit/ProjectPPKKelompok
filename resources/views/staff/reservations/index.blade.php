@extends('layouts.app')

@section('title', 'Kelola Reservasi')

@section('content')

<div class="mb-8">

    <p class="mb-2 text-sm font-medium text-[#b09b8c]">
        Staff Reservation
    </p>

    <h1 class="text-2xl font-semibold text-[#3f4f63]">
        Kelola Reservasi
    </h1>

    <p class="mt-2 text-sm text-[#8b929b]">
        Periksa dan proses pengajuan reservasi fasilitas.
    </p>

</div>


<div class="space-y-4">

    @forelse($reservations as $reservation)

        <div class="rounded-2xl border border-[#e9e3dd] bg-white p-6 shadow-sm">

            <div class="flex flex-col justify-between gap-5 lg:flex-row">

                <div class="space-y-3">

                    <div>

                        <h2 class="font-semibold text-[#3f4f63]">
                            {{ $reservation->facility->name }}
                        </h2>

                        <p class="text-sm text-[#8b929b]">
                            {{ $reservation->start_time->format('d M Y, H:i') }}
                            -
                            {{ $reservation->end_time->format('H:i') }}
                        </p>

                    </div>

                    <div class="text-sm text-[#626b75]">

                        <p>
                            <span class="font-medium">Pemohon:</span>
                            {{ $reservation->user->name }}
                        </p>

                        <p>
                            <span class="font-medium">NIM/NIP:</span>
                            {{ $reservation->identity_number }}
                        </p>

                        <p>
                            <span class="font-medium">Peserta:</span>
                            {{ $reservation->participants }} orang
                        </p>

                        <p>
                            <span class="font-medium">Tujuan:</span>
                            {{ $reservation->purpose }}
                        </p>

                    </div>

                </div>


                <div class="flex flex-col items-start gap-3 lg:items-end">

                    @if($reservation->status === 'PENDING')

                        <span class="rounded-full bg-[#f7eee4] px-3 py-1.5 text-xs font-medium text-[#92745e]">
                            PENDING
                        </span>

                        <div class="flex flex-wrap gap-2">

                            <form
                                action="{{ route('staff.reservations.approve', $reservation) }}"
                                method="POST">

                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="rounded-lg bg-[#dce9dc] px-3 py-2 text-xs font-medium text-[#607560] hover:bg-[#cfdfcf]">
                                    Setujui
                                </button>

                            </form>


                            <form
                                action="{{ route('staff.reservations.reject', $reservation) }}"
                                method="POST"
                                class="flex gap-2">

                                @csrf
                                @method('PATCH')

                                <input
                                    type="text"
                                    name="cancel_reason"
                                    placeholder="Alasan penolakan"
                                    required
                                    class="rounded-lg border border-[#dedbd6] px-3 py-2 text-xs">

                                <button
                                    type="submit"
                                    class="rounded-lg bg-[#f1dddd] px-3 py-2 text-xs font-medium text-[#875f5f] hover:bg-[#ead0d0]">
                                    Tolak
                                </button>

                            </form>

                        </div>

                    @elseif($reservation->status === 'APPROVED')

                        <span class="rounded-full bg-[#e8f2e8] px-3 py-1.5 text-xs font-medium text-[#607560]">
                            APPROVED
                        </span>

                        <form
                            action="{{ route('staff.reservations.cancel', $reservation) }}"
                            method="POST"
                            class="flex gap-2">

                            @csrf
                            @method('PATCH')

                            <input
                                type="text"
                                name="cancel_reason"
                                placeholder="Alasan pembatalan"
                                required
                                class="rounded-lg border border-[#dedbd6] px-3 py-2 text-xs">

                            <button
                                type="submit"
                                class="rounded-lg bg-[#f1dddd] px-3 py-2 text-xs font-medium text-[#875f5f]">
                                Batalkan
                            </button>

                        </form>

                    @elseif($reservation->status === 'REJECTED')

                        <span class="rounded-full bg-[#f1eded] px-3 py-1.5 text-xs font-medium text-[#806f6f]">
                            REJECTED
                        </span>

                    @else

                        <span class="rounded-full bg-[#f1eded] px-3 py-1.5 text-xs font-medium text-[#806f6f]">
                            CANCELLED
                        </span>

                    @endif

                </div>

            </div>

            @if($reservation->cancel_reason)

                <div class="mt-5 border-t border-[#eeeae5] pt-4">

                    <p class="text-xs font-medium text-[#8b929b]">
                        Alasan / keterangan
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
                Belum ada pengajuan reservasi.
            </p>

        </div>

    @endforelse

</div>

@endsection