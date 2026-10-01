@extends('layouts.app')

@section('title', 'Kelola Laporan - Campus Facility System')

@section('content')

<div class="mx-auto max-w-6xl">

    {{-- Back --}}
    <div class="mb-6">
        <a
            href="{{ route('staff.reports.index') }}"
            class="inline-flex items-center gap-2 text-base font-semibold text-[#2f625b] hover:underline"
        >
            ← Kembali
        </a>
    </div>


    <div class="grid gap-6 lg:grid-cols-[1fr_390px]">


        {{-- ================================================== --}}
        {{-- DETAIL LAPORAN --}}
        {{-- ================================================== --}}

        <div class="border border-[#dedbd3] bg-white">

            {{-- Header --}}
            <div class="border-b border-[#e4e1da] p-6">

                <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#2f625b]">
                    Detail Laporan
                </p>

                <h1 class="mt-2 text-4xl font-bold tracking-tight text-[#263634]">
                    {{ $report->facility->name ?? 'Fasilitas' }}
                </h1>

                <p class="mt-2 text-sm text-[#7b8581]">
                    Dilaporkan oleh {{ $report->user->name ?? '-' }}
                </p>

            </div>


            {{-- Detail Content --}}
            <div class="space-y-6 p-6">


                {{-- STATUS --}}
                <div class="grid gap-5 sm:grid-cols-2">

                    {{-- Status Laporan --}}
                    <div class="border border-[#eeeae4] bg-[#faf9f6] px-4 py-4">

                        <p class="text-xs font-bold uppercase tracking-wide text-[#68736f]">
                            Status Laporan Saat Ini
                        </p>

                        <div class="mt-2">

                            @if($report->status === 'NEW')

                                <span class="inline-flex rounded-full bg-[#f4e9dd] px-3 py-1.5 text-xs font-bold text-[#99633d]">
                                    Baru
                                </span>

                            @elseif($report->status === 'PROCESSING')

                                <span class="inline-flex rounded-full bg-[#e6f0ea] px-3 py-1.5 text-xs font-bold text-[#426b5a]">
                                    Diproses
                                </span>

                            @elseif($report->status === 'COMPLETED')

                                <span class="inline-flex rounded-full bg-[#e6f0ea] px-3 py-1.5 text-xs font-bold text-[#376453]">
                                    Selesai
                                </span>

                            @else

                                <span class="inline-flex rounded-full bg-[#eee8e5] px-3 py-1.5 text-xs font-bold text-[#765f59]">
                                    Ditolak
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- Status Fasilitas --}}
                    <div class="border border-[#eeeae4] bg-[#faf9f6] px-4 py-4">

                        <p class="text-xs font-bold uppercase tracking-wide text-[#68736f]">
                            Status Fasilitas Saat Ini
                        </p>

                        <div class="mt-2">

                            @if(($report->facility->status ?? null) === 'MAINTENANCE')

                                <span class="inline-flex rounded-full bg-[#f4e9dd] px-3 py-1.5 text-xs font-bold text-[#a65f3e]">
                                    Dalam Perbaikan
                                </span>

                                <p class="mt-1.5 text-base leading-6 text-[#596460]">
                                    Fasilitas sedang dalam proses perbaikan.
                                </p>

                            @elseif(($report->facility->status ?? null) === 'AVAILABLE')

                                <span class="inline-flex rounded-full bg-[#e6f0ea] px-3 py-1.5 text-xs font-bold text-[#426b5a]">
                                    Aktif
                                </span>

                                <p class="mt-1.5 text-base leading-6 text-[#596460]">
                                    Fasilitas dapat digunakan.
                                </p>

                            @else

                                <span class="inline-flex rounded-full bg-[#eee8e5] px-3 py-1.5 text-xs font-bold text-[#765f59]">
                                    Tidak Aktif
                                </span>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- KATEGORI + DESKRIPSI --}}
                <div class="grid gap-5 sm:grid-cols-2">

                    {{-- Kategori --}}
                    <div class="border border-[#eeeae4] bg-[#faf9f6] px-4 py-4">

                        <p class="text-xs font-bold uppercase tracking-wide text-[#68736f]">
                            Kategori Kerusakan
                        </p>

                        <p class="mt-1.5 text-base leading-6 text-[#596460]">
                            {{ $report->category }}
                        </p>

                    </div>


                    {{-- Deskripsi --}}
                    <div class="border border-[#eeeae4] bg-[#faf9f6] px-4 py-4">

                        <p class="text-xs font-bold uppercase tracking-wide text-[#68736f]">
                            Deskripsi Kerusakan
                        </p>

                        <p class="mt-1.5 text-base leading-6 text-[#596460]">
                            {{ $report->description }}
                        </p>

                    </div>

                </div>


                {{-- FOTO --}}
                @if($report->photo)

                    <div>

                        <p class="text-xs font-bold uppercase tracking-wide text-[#68736f]">
                            Foto Kerusakan
                        </p>

                        <div class="mt-2 overflow-hidden border border-[#dedbd3] bg-[#f7f6f2]">

                            <img
                                src="{{ asset('storage/' . $report->photo) }}"
                                alt="Foto kerusakan {{ $report->facility->name ?? '' }}"
                                class="max-h-[500px] w-full object-contain"
                            >

                        </div>

                    </div>

                @endif


                {{-- CATATAN RESOLUSI --}}
                @if($report->resolution_note)

                    <div class="border-t border-[#eeeae4] pt-5">

                        <p class="text-xs font-bold uppercase tracking-wide text-[#68736f]">
                            Catatan Resolusi Tersimpan
                        </p>

                        <div class="mt-2 border border-[#dedbd3] bg-[#f7f6f2] px-4 py-4">

                            <p class="text-base leading-6 text-[#596460]">
                                {{ $report->resolution_note }}
                            </p>

                        </div>

                    </div>

                @endif

            </div>

        </div>



        {{-- ================================================== --}}
        {{-- TINDAKAN PETUGAS --}}
        {{-- ================================================== --}}

        <div class="h-fit border border-[#dedbd3] bg-white">

            <div class="border-b border-[#e4e1da] p-6">

                <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#2f625b]">
                    Staff Action
                </p>

                <h2 class="mt-1.5 text-2xl font-bold text-[#263634]">
                    Tindakan Petugas
                </h2>

                <p class="mt-2 text-sm leading-6 text-[#68736f]">
                    Perbarui status laporan sesuai hasil penanganan
                    kerusakan.
                </p>

            </div>


            <form
                id="report-update-form"
                action="{{ route('staff.reports.update', $report) }}"
                method="POST"
                class="space-y-5 p-6"
            >

                @csrf
                @method('PATCH')


                {{-- STATUS --}}
                <div>

                    <label
                        for="status"
                        class="mb-2 block text-base font-semibold text-[#43504d]"
                    >
                        Status Laporan
                    </label>

                    <select
                        id="status"
                        name="status"
                        required
                        class="w-full rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-4 py-3 text-base text-[#263634] outline-none transition focus:border-[#2f625b] focus:ring-4 focus:ring-[#2f625b]/10"
                    >

                        <option
                            value="NEW"
                            @selected($report->status === 'NEW')
                        >
                            Baru
                        </option>

                        <option
                            value="PROCESSING"
                            @selected($report->status === 'PROCESSING')
                        >
                            Diproses
                        </option>

                        <option
                            value="COMPLETED"
                            @selected($report->status === 'COMPLETED')
                        >
                            Selesai
                        </option>

                        <option
                            value="REJECTED"
                            @selected($report->status === 'REJECTED')
                        >
                            Ditolak
                        </option>

                    </select>

                </div>


                {{-- PENJELASAN STATUS --}}
                <div
                    id="status-guide"
                    class="border border-[#dedbd3] bg-[#f7f6f2] px-4 py-4"
                >

                    <p
                        id="status-guide-title"
                        class="text-sm font-bold text-[#43504d]"
                    ></p>

                    <p
                        id="status-guide-text"
                        class="mt-1 text-sm leading-6 text-[#68736f]"
                    ></p>

                </div>


                {{-- RESOLUTION NOTE --}}
                <div>

                    <label
                        for="resolution_note"
                        class="mb-2 block text-base font-semibold text-[#43504d]"
                    >
                        Catatan Resolusi

                        <span
                            id="required-label"
                            class="text-[#a65f3e]"
                        >
                            *
                        </span>

                    </label>

                    <textarea
                        id="resolution_note"
                        name="resolution_note"
                        rows="7"
                        placeholder="Masukkan catatan penanganan..."
                        class="w-full resize-none rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-4 py-3 text-base leading-6 text-[#263634] outline-none placeholder:text-[#a2aaa7] focus:border-[#2f625b] focus:ring-4 focus:ring-[#2f625b]/10"
                    >{{ old('resolution_note', $report->resolution_note) }}</textarea>

                    <p
                        id="resolution-help"
                        class="mt-2 text-sm leading-5 text-[#8a9490]"
                    >
                        Catatan resolusi wajib diisi saat laporan selesai atau ditolak.
                    </p>

                </div>


                {{-- SAVE --}}
                <button
                    type="submit"
                    class="w-full rounded-lg bg-[#2f625b] px-5 py-3 text-base font-semibold text-white transition hover:bg-[#244d48] focus:outline-none focus:ring-4 focus:ring-[#2f625b]/20"
                >
                    Simpan Perubahan
                </button>

            </form>

        </div>

    </div>

</div>



<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('report-update-form');
    const status = document.getElementById('status');
    const note = document.getElementById('resolution_note');
    const guide = document.getElementById('status-guide');
    const guideTitle = document.getElementById('status-guide-title');
    const guideText = document.getElementById('status-guide-text');
    const requiredLabel = document.getElementById('required-label');
    const resolutionHelp = document.getElementById('resolution-help');


    function updateStatusGuide() {

        const value = status.value;


        if (value === 'NEW') {

            guide.className =
                'border border-[#dedbd3] bg-[#f7f6f2] px-4 py-4';

            guideTitle.className =
                'text-sm font-bold text-[#43504d]';

            guideTitle.textContent =
                'Laporan Baru';

            guideText.className =
                'mt-1 text-sm leading-6 text-[#68736f]';

            guideText.textContent =
                'Laporan masih menunggu penanganan petugas. Status fasilitas tidak diubah.';

            requiredLabel.classList.add('hidden');

            note.required = false;

            resolutionHelp.textContent =
                'Catatan resolusi belum wajib diisi pada status Baru.';
        }


        else if (value === 'PROCESSING') {

            guide.className =
                'border border-[#e4d4c7] bg-[#f4e9dd] px-4 py-4';

            guideTitle.className =
                'text-sm font-bold text-[#a65f3e]';

            guideTitle.textContent =
                'Fasilitas Dalam Perbaikan';

            guideText.className =
                'mt-1 text-sm leading-6 text-[#795e50]';

            guideText.textContent =
                'Saat laporan diproses, fasilitas akan berstatus Dalam Perbaikan dan sementara tidak dapat digunakan.';

            requiredLabel.classList.add('hidden');

            note.required = false;

            resolutionHelp.textContent =
                'Catatan penanganan dapat diisi selama proses perbaikan.';
        }


        else if (value === 'COMPLETED') {

            guide.className =
                'border border-[#cfe0d7] bg-[#e6f0ea] px-4 py-4';

            guideTitle.className =
                'text-sm font-bold text-[#426b5a]';

            guideTitle.textContent =
                'Perbaikan Selesai';

            guideText.className =
                'mt-1 text-sm leading-6 text-[#506a60]';

            guideText.textContent =
                'Setelah perbaikan dinyatakan selesai, status fasilitas akan dikembalikan menjadi Aktif.';

            requiredLabel.classList.remove('hidden');

            note.required = true;

            resolutionHelp.textContent =
                'Catatan resolusi wajib diisi. Jelaskan tindakan dan hasil perbaikan yang dilakukan.';
        }


        else if (value === 'REJECTED') {

            guide.className =
                'border border-[#e2d8d4] bg-[#eee8e5] px-4 py-4';

            guideTitle.className =
                'text-sm font-bold text-[#765f59]';

            guideTitle.textContent =
                'Laporan Ditolak';

            guideText.className =
                'mt-1 text-sm leading-6 text-[#6f625e]';

            guideText.textContent =
                'Laporan akan ditandai sebagai Ditolak. Masukkan alasan penolakan pada catatan resolusi.';

            requiredLabel.classList.remove('hidden');

            note.required = true;

            resolutionHelp.textContent =
                'Catatan alasan penolakan wajib diisi.';
        }

    }


    status.addEventListener(
        'change',
        updateStatusGuide
    );


    form.addEventListener(
        'submit',
        function (event) {

            const requiresNote =
                status.value === 'COMPLETED'
                || status.value === 'REJECTED';


            if (
                requiresNote
                && !note.value.trim()
            ) {

                event.preventDefault();

                note.focus();

                alert(
                    status.value === 'COMPLETED'
                        ? 'Catatan resolusi wajib diisi sebelum laporan ditandai Selesai.'
                        : 'Catatan alasan penolakan wajib diisi sebelum laporan ditolak.'
                );

            }

        }
    );


    updateStatusGuide();

});
</script>

@endsection