@extends('layouts.app')

@section('title', 'Lapor Kerusakan - Campus Facility System')

@section('content')

<div class="mx-auto max-w-4xl">

    {{-- Header --}}
    <div class="mb-7">

        <a href="{{ route('facilities.index') }}"
            class="inline-flex items-center gap-2 text-base font-semibold text-[#2f625b] hover:underline"
        >
            ← Kembali ke fasilitas
        </a>

        <div class="mt-6">

            <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#2f625b]">
                Facility Report
            </p>

            <h1 class="mt-2 text-4xl font-bold tracking-tight text-[#263634]">
                Lapor Kerusakan
            </h1>

            <p class="mt-2 max-w-2xl text-base leading-6 text-[#68736f]">
                Laporkan kerusakan atau masalah pada fasilitas kampus agar dapat
                segera ditindaklanjuti oleh petugas.
            </p>

        </div>

    </div>


    {{-- Form --}}
    <div class="border border-[#dedbd3] bg-white">

        <div class="border-b border-[#e4e1da] px-6 py-5">

            <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#2f625b]">
                Report Form
            </p>

            <h2 class="mt-1.5 text-2xl font-bold text-[#263634]">
                Detail Kerusakan
            </h2>

        </div>


        <form
            id="report-form"
            action="{{ route('reports.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf

            <div class="space-y-6 p-6">

                {{-- Facility --}}
                <div>

                    <label
                        for="facility_id"
                        class="mb-2 block text-base font-semibold text-[#43504d]"
                    >
                        Fasilitas
                    </label>

                    <select
                        id="facility_id"
                        name="facility_id"
                        required
                        class="w-full rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-4 py-3 text-base text-[#263634] outline-none transition focus:border-[#2f625b] focus:bg-white focus:ring-4 focus:ring-[#2f625b]/10"
                    >

                        <option value="">
                            Pilih fasilitas
                        </option>

                        @foreach($facilities as $facility)

                            <option
                                value="{{ $facility->id }}"
                                @selected(
                                    old(
                                        'facility_id',
                                        $selectedFacility?->id
                                    ) == $facility->id
                                )
                            >
                                {{ $facility->name }}
                            </option>

                        @endforeach

                    </select>

                    @error('facility_id')
                        <p class="mt-1.5 text-sm text-[#a65f3e]">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- Category --}}
                <div>

                    <label
                        for="category"
                        class="mb-2 block text-base font-semibold text-[#43504d]"
                    >
                        Kategori Kerusakan
                    </label>

                    <select
                        id="category"
                        name="category"
                        required
                        class="w-full rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-4 py-3 text-base text-[#263634] outline-none transition focus:border-[#2f625b] focus:bg-white focus:ring-4 focus:ring-[#2f625b]/10"
                    >

                        <option value="">
                            Pilih kategori
                        </option>

                        <option value="Kerusakan fasilitas"
                            @selected(old('category') === 'Kerusakan fasilitas')>
                            Kerusakan fasilitas
                        </option>

                        <option value="Fasilitas tidak berfungsi"
                            @selected(old('category') === 'Fasilitas tidak berfungsi')>
                            Fasilitas tidak berfungsi
                        </option>

                        <option value="Kerusakan ringan"
                            @selected(old('category') === 'Kerusakan ringan')>
                            Kerusakan ringan
                        </option>

                        <option value="Kerusakan berat"
                            @selected(old('category') === 'Kerusakan berat')>
                            Kerusakan berat
                        </option>

                        <option value="Kebersihan"
                            @selected(old('category') === 'Kebersihan')>
                            Kebersihan
                        </option>

                        <option value="Lainnya"
                            @selected(old('category') === 'Lainnya')>
                            Lainnya
                        </option>

                    </select>

                    @error('category')
                        <p class="mt-1.5 text-sm text-[#a65f3e]">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- Description --}}
                <div>

                    <div class="flex items-end justify-between gap-3">

                        <label
                            for="description"
                            class="block text-base font-semibold text-[#43504d]"
                        >
                            Deskripsi Kerusakan
                        </label>

                        <span
                            id="description-counter"
                            class="text-xs text-[#8a9490]"
                        >
                            0 karakter
                        </span>

                    </div>

                    <textarea
                        id="description"
                        name="description"
                        rows="6"
                        required
                        placeholder="Jelaskan kerusakan yang ditemukan secara detail..."
                        class="mt-2 w-full resize-none rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-4 py-3 text-base leading-6 text-[#263634] outline-none transition placeholder:text-[#a2aaa7] focus:border-[#2f625b] focus:bg-white focus:ring-4 focus:ring-[#2f625b]/10"
                    >{{ old('description') }}</textarea>

                    <p class="mt-1.5 text-sm text-[#8a9490]">
                        Jelaskan lokasi, kondisi, dan bagian fasilitas yang mengalami masalah.
                    </p>

                    @error('description')
                        <p class="mt-1.5 text-sm text-[#a65f3e]">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- Photo --}}
                <div>

                    <label
                        for="photo"
                        class="mb-2 block text-base font-semibold text-[#43504d]"
                    >
                        Foto Kerusakan
                    </label>

                    <div class="rounded-xl border border-dashed border-[#cfcac1] bg-[#faf9f6] p-5">

                        <input
                            id="photo"
                            type="file"
                            name="photo"
                            accept="image/jpeg,image/png,image/webp"
                            class="block w-full text-base text-[#68736f]
                            file:mr-4 file:rounded-lg file:border-0
                            file:bg-[#e6f0ea] file:px-4 file:py-2.5
                            file:text-sm file:font-bold file:text-[#2f625b]
                            hover:file:bg-[#dceae3]"
                        >

                        <p class="mt-2 text-sm text-[#7b8581]">
                            JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                        </p>

                        <p id="photo-error"
                            class="mt-2 hidden text-sm font-medium text-[#a65f3e]"
                        ></p>

                    </div>

                    @error('photo')
                        <p class="mt-1.5 text-sm text-[#a65f3e]">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- Info --}}
                <div class="border border-[#ddd9d0] bg-[#f7f6f2] p-5">

                    <div class="flex gap-3">

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#e6f0ea] text-base font-bold text-[#2f625b]">
                            i
                        </div>

                        <div>

                            <p class="text-base font-bold text-[#263634]">
                                Sebelum mengirim laporan
                            </p>

                            <p class="mt-1 text-sm leading-6 text-[#68736f]">
                                Pastikan fasilitas, kategori, dan deskripsi kerusakan
                                sudah sesuai agar laporan dapat diproses dengan tepat.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

            {{-- Actions --}}
            <div class="flex flex-col-reverse gap-3 border-t border-[#e4e1da] bg-[#fafaf8] px-6 py-4 sm:flex-row sm:justify-end">

                <a href="{{ route('facilities.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-[#d5d2ca] bg-white px-5 py-2.5 text-base font-semibold text-[#596460] transition hover:bg-[#f1f0eb]"
                >
                    Batal
                </a>

                <button id="submit-report"
                    type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-[#2f625b] px-5 py-2.5 text-base font-semibold text-white transition hover:bg-[#244d48]"
                >
                    Kirim Laporan
                </button>
            </div>
        </form>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('report-form');
    const facility = document.getElementById('facility_id');
    const category = document.getElementById('category');
    const description = document.getElementById('description');
    const photo = document.getElementById('photo');
    const photoError = document.getElementById('photo-error');
    const counter = document.getElementById('description-counter');
    const submitButton = document.getElementById('submit-report');

    function updateCounter() {
        counter.textContent = `${description.value.length} karakter`;
    }

    function validatePhoto() {

        photoError.textContent = '';
        photoError.classList.add('hidden');

        if (!photo.files.length) {
            return true;
        }

        const file = photo.files[0];

        const allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        if (!allowedTypes.includes(file.type)) {
            photoError.textContent =
                'Foto harus berformat JPG, JPEG, PNG, atau WEBP.';

            photoError.classList.remove('hidden');

            return false;
        }

        if (file.size > 2 * 1024 * 1024) {
            photoError.textContent =
                'Ukuran foto maksimal 2 MB.';

            photoError.classList.remove('hidden');

            return false;
        }

        return true;
    }

    description.addEventListener('input', updateCounter);

    photo.addEventListener('change', validatePhoto);

    form.addEventListener('submit', function (event) {

        if (!facility.value) {
            event.preventDefault();
            facility.focus();
            return;
        }

        if (!category.value) {
            event.preventDefault();
            category.focus();
            return;
        }

        if (!description.value.trim()) {
            event.preventDefault();
            description.focus();
            return;
        }

        if (!validatePhoto()) {
            event.preventDefault();
            photo.focus();
            return;
        }

        submitButton.disabled = true;
        submitButton.classList.add(
            'cursor-not-allowed',
            'opacity-60'
        );

        submitButton.textContent = 'Mengirim...';
    });

    updateCounter();

});
</script>

@endsection