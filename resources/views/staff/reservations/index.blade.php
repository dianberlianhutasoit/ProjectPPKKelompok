@extends('layouts.app')

@section('title', 'Kelola Reservasi - Campus Facility System')

@section('content')

<div class="mx-auto max-w-7xl">

    <div class="mb-6 border border-[#dedbd3] bg-white p-5">

        <form
            method="GET"
            action="{{ route('staff.reservations.index') }}"
            class="grid gap-4 md:grid-cols-4"
        >

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wide text-[#596460]">
                    Status
                </label>

                <select
                    name="status"
                    onchange="this.form.submit()"
                    class="w-full border border-[#d5d2ca] bg-[#fafaf8] px-3 py-2.5 text-base text-[#263634] outline-none focus:border-[#2f625b]"
                >
                    <option value="Semua" @selected(($filters['status'] ?? '') === 'Semua')}>
                        Semua
                    </option>

                    <option value="PENDING" @selected(($filters['status'] ?? 'PENDING') === 'PENDING')}>
                        Menunggu
                    </option>

                    <option value="APPROVED" @selected(($filters['status'] ?? '') === 'APPROVED')}>
                        Disetujui
                    </option>

                    <option value="REJECTED" @selected(($filters['status'] ?? '') === 'REJECTED')}>
                        Ditolak
                    </option>

                    <option value="CANCELLED" @selected(($filters['status'] ?? '') === 'CANCELLED')}>
                        Dibatalkan
                    </option>
                </select>
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wide text-[#596460]">
                    Fasilitas
                </label>

                <select
                    name="facility_id"
                    onchange="this.form.submit()"
                    class="w-full border border-[#d5d2ca] bg-[#fafaf8] px-3 py-2.5 text-base text-[#263634] outline-none focus:border-[#2f625b]"
                >
                    <option value="">
                        Semua fasilitas
                    </option>

                    @foreach(\App\Models\Facility::orderBy('name')->get() as $facility)

                        <option
                            value="{{ $facility->id }}"
                            @selected((string) ($filters['facility_id'] ?? '') === (string) $facility->id)
                        >
                            {{ $facility->name }}
                        </option>

                    @endforeach

                </select>
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wide text-[#596460]">
                    Urutkan berdasarkan
                </label>

                <select
                    name="sort_by"
                    onchange="this.form.submit()"
                    class="w-full border border-[#d5d2ca] bg-[#fafaf8] px-3 py-2.5 text-base text-[#263634] outline-none focus:border-[#2f625b]"
                >
                    <option value="created_at" @selected(($filters['sort_by'] ?? '') === 'created_at')}>
                        Tanggal pengajuan
                    </option>

                    <option value="event_date" @selected(($filters['sort_by'] ?? '') === 'event_date')}>
                        Tanggal reservasi
                    </option>

                    <option value="facility" @selected(($filters['sort_by'] ?? '') === 'facility')}>
                        Nama fasilitas
                    </option>
                </select>
            </div>

            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-wide text-[#596460]">
                    Urutan
                </label>

                <div class="flex gap-2">

                    <select
                        name="sort_order"
                        onchange="this.form.submit()"
                        class="w-full border border-[#d5d2ca] bg-[#fafaf8] px-3 py-2.5 text-base text-[#263634] outline-none focus:border-[#2f625b]"
                    >
                        <option value="asc" @selected(($filters['sort_order'] ?? '') === 'asc')}>
                            Terlama
                        </option>

                        <option value="desc" @selected(($filters['sort_order'] ?? '') === 'desc')}>
                            Terbaru
                        </option>
                    </select>
                </div>
            </div>

        </form>

    </div>

    <div class="mb-7">

        <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#2f625b]">
            Staff Workspace
        </p>

        <div class="mt-2 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

            <div>

                <h1 class="text-4xl font-bold tracking-tight text-[#263634]">
                    Kelola Reservasi
                </h1>

                <p class="mt-2 text-base leading-6 text-[#68736f]">
                    Periksa pengajuan reservasi dan proses sesuai ketersediaan fasilitas.
                </p>

            </div>

            <a
                href="{{ route('facilities.index') }}"
                class="inline-flex w-fit items-center justify-center rounded-lg border border-[#d5d2ca] bg-white px-5 py-2.5 text-base font-semibold text-[#43504d] transition hover:bg-[#f5f4f0]"
            >
                Lihat Fasilitas
            </a>

        </div>

    </div>

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

    @if($errors->any())

        <div class="mb-5 border border-[#e6d0c5] bg-[#fbf0eb] px-4 py-3 text-sm font-medium text-[#a65f3e]">
            {{ $errors->first() }}
        </div>

    @endif

    @if($reservations->count())

        <div class="hidden overflow-hidden border border-[#dedbd3] bg-white md:block">

            <div class="overflow-x-auto">

                <table class="w-full min-w-[900px] text-left">

                    <thead class="border-b border-[#e4e1da] bg-[#f7f6f2]">

                        <tr>

                            <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-[#737d79]">
                                Pemohon
                            </th>

                            <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-[#737d79]">
                                Fasilitas
                            </th>

                            <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-[#737d79]">
                                Jadwal
                            </th>

                            <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-[#737d79]">
                                Keperluan
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

                                <td class="px-5 py-5 align-top">

                                    <p class="text-base font-bold text-[#263634]">
                                        {{ $reservation->user->name ?? '-' }}
                                    </p>

                                    <p class="mt-1 text-xs text-[#8a9490]">
                                        {{ $reservation->user->email ?? '-' }}
                                    </p>

                                </td>

                                <td class="px-5 py-5 align-top">

                                    <p class="text-base font-semibold text-[#43504d]">
                                        {{ $reservation->facility->name ?? '-' }}
                                    </p>

                                    <p class="mt-1 text-xs text-[#7b8581]">
                                        {{ $reservation->facility->location ?? '-' }}
                                    </p>

                                </td>

                                <td class="whitespace-nowrap px-5 py-5 align-top">

                                    <p class="text-base font-semibold text-[#43504d]">
                                        {{ \Carbon\Carbon::parse($reservation->start_time)->translatedFormat('d M Y') }}
                                    </p>

                                    <p class="mt-1 text-sm text-[#7b8581]">
                                        {{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }}
                                        –
                                        {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}
                                    </p>

                                    <p class="mt-1 text-sm text-[#8a9490]">
                                        {{ $reservation->participants }} peserta
                                    </p>

                                </td>

                                <td class="max-w-[220px] px-5 py-5 align-top">

                                    <p class="text-base leading-6 text-[#596460]">
                                        {{ $reservation->purpose }}
                                    </p>

                                    @if($reservation->reason ?? $reservation->cancel_reason)

                                        <p class="mt-2 text-sm italic text-[#a65f3e]">

                                            Alasan:
                                            {{ $reservation->reason ?? $reservation->cancel_reason }}

                                        </p>

                                    @endif

                                </td>

                                <td class="px-5 py-5 align-top">

                                    @if($reservation->status === 'APPROVED')

                                        <span class="inline-flex rounded-full bg-[#e6f0ea] px-3 py-1 text-xs font-bold text-[#426b5a]">
                                            Disetujui
                                        </span>

                                    @elseif($reservation->status === 'REJECTED')

                                        <span class="inline-flex rounded-full bg-[#f3e8e5] px-3 py-1 text-xs font-bold text-[#765f59]">
                                            Ditolak
                                        </span>

                                    @elseif($reservation->status === 'CANCELLED')

                                        <span class="inline-flex rounded-full bg-[#eeeeeb] px-3 py-1 text-xs font-bold text-[#737a77]">
                                            Dibatalkan
                                        </span>

                                    @else

                                        <span class="inline-flex rounded-full bg-[#f4e9dd] px-3 py-1 text-xs font-bold text-[#99633d]">
                                            Menunggu
                                        </span>

                                    @endif

                                </td>

                                <td class="px-5 py-5 align-top">

                                    @if($reservation->status === 'PENDING')

                                        <div class="flex flex-col gap-2">

                                            <form
                                                method="POST"
                                                action="{{ route('staff.reservations.approve', $reservation) }}"
                                            >

                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="w-full rounded-md bg-[#2f625b] px-3 py-2 text-sm font-bold text-white transition hover:bg-[#244d48]"
                                                >
                                                    Setujui
                                                </button>

                                            </form>

                                            <button
                                                type="button"
                                                onclick="openRejectReason({{ $reservation->id }})"
                                                class="rounded-md border border-[#d5d2ca] bg-white px-3 py-2 text-sm font-bold text-[#a65f3e] transition hover:bg-[#fbf0eb]"
                                            >
                                                Tolak
                                            </button>

                                        </div>

                                    @elseif($reservation->status === 'APPROVED')

                                        {{-- Hanya diizinkan batal jika masih ada sisa waktu minimal 30 menit sebelum pelaksanaan --}}
                                        @if(now()->addMinutes(30)->lte(\Carbon\Carbon::parse($reservation->start_time)))

                                            <form
                                                method="POST"
                                                action="{{ route('staff.reservations.cancel', $reservation) }}"
                                                class="flex w-48 flex-col gap-2"
                                            >

                                                @csrf
                                                @method('PATCH')

                                                <input
                                                    type="text"
                                                    name="cancel_reason"
                                                    required
                                                    maxlength="1000"
                                                    placeholder="Alasan pembatalan..."
                                                    class="w-full rounded-md border border-[#d5d2ca] bg-[#fafaf8] px-3 py-2 text-sm text-[#263634] outline-none focus:border-[#2f625b] focus:ring-4 focus:ring-[#2f625b]/10"
                                                >

                                                <button
                                                    type="submit"
                                                    onclick="return confirm('Batalkan reservasi yang disetujui ini?')"
                                                    class="w-full rounded-md border border-[#d5d2ca] bg-white px-3 py-2 text-sm font-bold text-[#a65f3e] transition hover:bg-[#fbf0eb]"
                                                >
                                                    Batalkan
                                                </button>

                                            </form>

                                        @else

                                            <span class="text-xs font-medium text-[#8a9490]">
                                                Batas pembatalan lewat (&lt;30 mnt)
                                            </span>

                                        @endif

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

        </div>

        <div class="space-y-4 md:hidden">

            @foreach($reservations as $reservation)

                <div class="border border-[#dedbd3] bg-white p-5">

                    <div class="flex items-start justify-between gap-4">

                        <div>

                            <p class="text-base font-bold text-[#263634]">
                                {{ $reservation->facility->name ?? '-' }}
                            </p>

                            <p class="mt-1 text-xs text-[#7b8581]">
                                {{ $reservation->facility->location ?? '-' }}
                            </p>

                        </div>

                        @if($reservation->status === 'APPROVED')

                            <span class="shrink-0 rounded-full bg-[#e6f0ea] px-2.5 py-1 text-xs font-bold text-[#426b5a]">
                                Disetujui
                            </span>

                        @elseif($reservation->status === 'REJECTED')

                            <span class="shrink-0 rounded-full bg-[#f3e8e5] px-2.5 py-1 text-xs font-bold text-[#765f59]">
                                Ditolak
                            </span>

                        @elseif($reservation->status === 'CANCELLED')

                            <span class="shrink-0 rounded-full bg-[#eeeeeb] px-2.5 py-1 text-xs font-bold text-[#737a77]">
                                Dibatalkan
                            </span>

                        @else

                            <span class="shrink-0 rounded-full bg-[#f4e9dd] px-2.5 py-1 text-xs font-bold text-[#99633d]">
                                Menunggu
                            </span>

                        @endif

                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-4 border-t border-[#eeeae4] pt-4">

                        <div>

                            <p class="text-xs font-semibold uppercase tracking-wide text-[#8a9490]">
                                Pemohon
                            </p>

                            <p class="mt-1 text-base font-semibold text-[#43504d]">
                                {{ $reservation->user->name ?? '-' }}
                            </p>

                        </div>

                        <div>

                            <p class="text-xs font-semibold uppercase tracking-wide text-[#8a9490]">
                                Peserta
                            </p>

                            <p class="mt-1 text-base font-semibold text-[#43504d]">
                                {{ $reservation->participants }} orang
                            </p>

                        </div>

                        <div>

                            <p class="text-xs font-semibold uppercase tracking-wide text-[#8a9490]">
                                Tanggal
                            </p>

                            <p class="mt-1 text-base font-semibold text-[#43504d]">
                                {{ \Carbon\Carbon::parse($reservation->start_time)->translatedFormat('d M Y') }}
                            </p>

                        </div>

                        <div>

                            <p class="text-xs font-semibold uppercase tracking-wide text-[#8a9490]">
                                Waktu
                            </p>

                            <p class="mt-1 text-base font-semibold text-[#43504d]">
                                {{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }}
                                –
                                {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}
                            </p>

                        </div>

                    </div>

                    <div class="mt-5 border-t border-[#eeeae4] pt-4">

                        <p class="text-xs font-semibold uppercase tracking-wide text-[#8a9490]">
                            Keperluan
                        </p>

                        <p class="mt-1 text-base leading-6 text-[#596460]">
                            {{ $reservation->purpose }}
                        </p>

                        @if($reservation->reason ?? $reservation->cancel_reason)

                            <p class="mt-2 text-sm italic text-[#a65f3e]">

                                Alasan:
                                {{ $reservation->reason ?? $reservation->cancel_reason }}

                            </p>

                        @endif

                    </div>

                    @if($reservation->status === 'PENDING')

                        <div class="mt-5 flex flex-col gap-2 border-t border-[#eeeae4] pt-4">

                            <form
                                method="POST"
                                action="{{ route('staff.reservations.approve', $reservation) }}"
                            >

                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="w-full rounded-lg bg-[#2f625b] px-4 py-2.5 text-sm font-bold text-white transition hover:bg-[#244d48]"
                                >
                                    Setujui
                                </button>

                            </form>

                            <button
                                type="button"
                                onclick="openRejectReason({{ $reservation->id }})"
                                class="w-full rounded-lg border border-[#d5d2ca] bg-white px-4 py-2.5 text-sm font-bold text-[#a65f3e] transition hover:bg-[#fbf0eb]"
                            >
                                Tolak Reservasi
                            </button>

                        </div>

                    @elseif($reservation->status === 'APPROVED')

                        {{-- Hanya diizinkan batal jika masih ada sisa waktu minimal 30 menit sebelum pelaksanaan --}}
                        @if(now()->addMinutes(30)->lte(\Carbon\Carbon::parse($reservation->start_time)))

                            <form
                                method="POST"
                                action="{{ route('staff.reservations.cancel', $reservation) }}"
                                class="mt-5 flex flex-col gap-2 border-t border-[#eeeae4] pt-4"
                            >

                                @csrf
                                @method('PATCH')

                                <input
                                    type="text"
                                    name="cancel_reason"
                                    required
                                    maxlength="1000"
                                    placeholder="Alasan pembatalan..."
                                    class="w-full rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-4 py-2.5 text-base text-[#263634] outline-none focus:border-[#2f625b] focus:bg-white focus:ring-4 focus:ring-[#2f625b]/10"
                                >

                                <button
                                    type="submit"
                                    onclick="return confirm('Batalkan reservasi yang disetujui ini?')"
                                    class="w-full rounded-lg border border-[#d5d2ca] bg-white px-4 py-2.5 text-sm font-bold text-[#a65f3e] transition hover:bg-[#fbf0eb]"
                                >
                                    Batalkan Reservasi
                                </button>

                            </form>

                        @else

                            <div class="mt-5 border-t border-[#eeeae4] pt-4">

                                <p class="text-sm font-medium text-[#8a9490]">
                                    Batas pembatalan lewat (&lt;30 mnt)
                                </p>

                            </div>

                        @endif

                    @endif

                </div>

            @endforeach

        </div>

    @else

        <div class="rounded-lg border border-[#dedbd3] bg-white p-8 text-center">

            <p class="text-base text-[#7b8581]">
                Belum ada pengajuan reservasi.
            </p>

        </div>

    @endif

</div>

<div
    id="reject-reason-modal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4"
>

    <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl">

        <p class="text-xs font-bold uppercase tracking-[0.12em] text-[#a65f3e]">
            Alasan Penolakan
        </p>

        <h2 class="mt-2 text-2xl font-bold text-[#263634]">
            Masukkan alasan penolakan
        </h2>

        <p class="mt-2 text-base leading-6 text-[#68736f]">
            Berikan alasan agar pemohon mengetahui mengapa reservasinya ditolak.
        </p>

        <form
            id="reject-form"
            method="POST"
            class="mt-5"
            onsubmit="return openRejectConfirm(event)"
        >

            @csrf
            @method('PATCH')

            <label
                for="cancel_reason"
                class="mb-2 block text-base font-semibold text-[#43504d]"
            >
                Alasan
            </label>

            <textarea
                id="cancel_reason"
                name="cancel_reason"
                rows="4"
                maxlength="1000"
                required
                placeholder="Jelaskan alasan penolakan..."
                class="w-full resize-none rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-4 py-3 text-base leading-6 text-[#263634] outline-none focus:border-[#2f625b] focus:bg-white focus:ring-4 focus:ring-[#2f625b]/10"
            ></textarea>

            <div class="mt-5 flex justify-end gap-3">

                <button
                    type="button"
                    onclick="closeRejectReason()"
                    class="rounded-lg border border-[#d5d2ca] bg-white px-4 py-2.5 text-base font-semibold text-[#596460] transition hover:bg-[#f1f0eb]"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="rounded-lg bg-[#a65f3e] px-4 py-2.5 text-base font-semibold text-white transition hover:bg-[#8f4f34]"
                >
                    Konfirmasi Penolakan
                </button>

            </div>

        </form>

    </div>

</div>

<div
    id="reject-confirm-modal"
    class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/40 px-4"
>

    <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl">

        <p class="text-xs font-bold uppercase tracking-[0.12em] text-[#a65f3e]">
            Konfirmasi
        </p>

        <h2 class="mt-2 text-2xl font-bold text-[#263634]">
            Yakin ingin menolak?
        </h2>

        <p class="mt-2 text-base leading-6 text-[#68736f]">
            Apakah kamu yakin ingin menolak reservasi ini?
            Alasan yang sudah dimasukkan akan dikirim kepada pemohon.
        </p>

        <div class="mt-6 flex justify-end gap-3">

            <button
                type="button"
                onclick="closeRejectConfirm()"
                class="rounded-lg border border-[#d5d2ca] bg-white px-4 py-2.5 text-base font-semibold text-[#596460] transition hover:bg-[#f1f0eb]"
            >
                Tidak
            </button>

            <button
                type="button"
                onclick="submitRejectForm()"
                class="rounded-lg bg-[#a65f3e] px-4 py-2.5 text-base font-semibold text-white transition hover:bg-[#8f4f34]"
            >
                Iya
            </button>

        </div>

    </div>

</div>

<script>

    let selectedReservationId = null;

    function openRejectReason(id) {

        selectedReservationId = id;

        const modal = document.getElementById('reject-reason-modal');
        const form = document.getElementById('reject-form');
        const reasonInput = document.getElementById('cancel_reason');

        form.action = `/staff/reservations/${id}/reject`;

        reasonInput.value = '';

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        setTimeout(() => {
            reasonInput.focus();
        }, 100);

    }

    function closeRejectReason() {

        const modal = document.getElementById('reject-reason-modal');

        modal.classList.add('hidden');
        modal.classList.remove('flex');

    }

    function openRejectConfirm(event) {

        event.preventDefault();

        const reasonInput = document.getElementById('cancel_reason');

        if (!reasonInput.value.trim()) {

            reasonInput.focus();

            return false;

        }

        closeRejectReason();

        const modal = document.getElementById('reject-confirm-modal');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        return false;

    }

    function closeRejectConfirm() {

        const modal = document.getElementById('reject-confirm-modal');

        modal.classList.add('hidden');
        modal.classList.remove('flex');

    }

    function submitRejectForm() {

        const form = document.getElementById('reject-form');

        form.removeAttribute('onsubmit');

        form.submit();

    }

</script>

@endsection