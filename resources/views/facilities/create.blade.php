@extends('layouts.app')

@section('title', 'Tambah Fasilitas')

@section('content')

<div class="mx-auto max-w-2xl">

    <a
        href="{{ route('facilities.index') }}"
        class="mb-6 inline-flex text-sm text-[#9b8878] hover:text-[#806b5d]">
        ← Kembali ke fasilitas
    </a>


    <div class="mb-7">

        <h1 class="text-2xl font-semibold text-[#3f4f63]">
            Tambah fasilitas
        </h1>

        <p class="mt-2 text-sm text-[#8b929b]">
            Tambahkan informasi fasilitas baru ke dalam sistem.
        </p>

    </div>


    <div class="rounded-2xl border border-[#e9e3dd] bg-white p-7 shadow-sm">

        <form method="POST"
              action="{{ route('facilities.store') }}"
              class="space-y-5">

            @csrf


            {{-- Name --}}
            <div>

                <label for="name"
                       class="mb-2 block text-sm font-medium text-[#59636f]">
                    Nama fasilitas
                </label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    maxlength="255"
                    placeholder="Contoh: Ruang A301"
                    class="w-full rounded-lg border border-[#dedbd6] bg-[#fcfbfa] px-4 py-3 text-sm outline-none focus:border-[#c9b5a7] focus:ring-2 focus:ring-[#eadfd8]"
                >

            </div>


            {{-- Type --}}
            <div>

                <label for="type"
                       class="mb-2 block text-sm font-medium text-[#59636f]">
                    Tipe fasilitas
                </label>

                <input
                    id="type"
                    type="text"
                    name="type"
                    value="{{ old('type') }}"
                    required
                    maxlength="100"
                    placeholder="Contoh: Classroom"
                    class="w-full rounded-lg border border-[#dedbd6] bg-[#fcfbfa] px-4 py-3 text-sm outline-none focus:border-[#c9b5a7] focus:ring-2 focus:ring-[#eadfd8]"
                >

            </div>


            {{-- Location --}}
            <div>

                <label for="location"
                       class="mb-2 block text-sm font-medium text-[#59636f]">
                    Lokasi
                </label>

                <input
                    id="location"
                    type="text"
                    name="location"
                    value="{{ old('location') }}"
                    required
                    maxlength="255"
                    placeholder="Contoh: Gedung A Lantai 3"
                    class="w-full rounded-lg border border-[#dedbd6] bg-[#fcfbfa] px-4 py-3 text-sm outline-none focus:border-[#c9b5a7] focus:ring-2 focus:ring-[#eadfd8]"
                >

            </div>


            {{-- Capacity --}}
            <div>

                <label for="capacity"
                       class="mb-2 block text-sm font-medium text-[#59636f]">
                    Kapasitas
                </label>

                <input
                    id="capacity"
                    type="number"
                    name="capacity"
                    value="{{ old('capacity') }}"
                    required
                    min="1"
                    placeholder="Contoh: 40"
                    class="w-full rounded-lg border border-[#dedbd6] bg-[#fcfbfa] px-4 py-3 text-sm outline-none focus:border-[#c9b5a7] focus:ring-2 focus:ring-[#eadfd8]"
                >

            </div>


            {{-- Description --}}
            <div>

                <label for="description"
                       class="mb-2 block text-sm font-medium text-[#59636f]">
                    Deskripsi
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    placeholder="Jelaskan fungsi atau penggunaan fasilitas."
                    class="w-full resize-none rounded-lg border border-[#dedbd6] bg-[#fcfbfa] px-4 py-3 text-sm outline-none focus:border-[#c9b5a7] focus:ring-2 focus:ring-[#eadfd8]"
                >{{ old('description') }}</textarea>

            </div>


            {{-- Status --}}
            <div>

                <label for="status"
                       class="mb-2 block text-sm font-medium text-[#59636f]">
                    Status
                </label>

                <select
                    id="status"
                    name="status"
                    required
                    class="w-full rounded-lg border border-[#dedbd6] bg-[#fcfbfa] px-4 py-3 text-sm outline-none focus:border-[#c9b5a7] focus:ring-2 focus:ring-[#eadfd8]">

                    <option value="AVAILABLE"
                        @selected(old('status', 'AVAILABLE') === 'AVAILABLE')}>
                        Tersedia
                    </option>

                    <option value="MAINTENANCE"
                        @selected(old('status') === 'MAINTENANCE')}>
                        Dalam perbaikan
                    </option>

                    <option value="INACTIVE"
                        @selected(old('status') === 'INACTIVE')}>
                        Nonaktif
                    </option>

                </select>

            </div>


            {{-- Buttons --}}
            <div class="flex justify-end gap-3 border-t border-[#eeeae5] pt-5">

                <a
                    href="{{ route('facilities.index') }}"
                    class="rounded-lg border border-[#dedbd6] px-4 py-2.5 text-sm text-[#6b7280] hover:bg-[#f7f5f2]">
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-[#d8c8bc] px-5 py-2.5 text-sm font-medium text-white hover:bg-[#c9b5a7]">
                    Simpan fasilitas
                </button>

            </div>

        </form>

    </div>

</div>

@endsection