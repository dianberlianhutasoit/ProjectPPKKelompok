@extends('layouts.app')

@section('title', 'Edit Fasilitas')

@section('content')

<div class="mx-auto max-w-2xl">

    <a
        href="{{ route('facilities.index') }}"
        class="mb-6 inline-flex text-sm text-[#9b8878] hover:text-[#806b5d]">
        ← Kembali ke fasilitas
    </a>


    <div class="mb-7">

        <p class="text-sm text-[#a2a7ad]">
            Edit fasilitas
        </p>

        <h1 class="mt-1 text-2xl font-semibold text-[#3f4f63]">
            {{ $facility->name }}
        </h1>

    </div>


    <div class="rounded-2xl border border-[#e9e3dd] bg-white p-7 shadow-sm">

        <form
            method="POST"
            action="{{ route('facilities.update', $facility) }}"
            class="space-y-5">

            @csrf
            @method('PUT')


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
                    value="{{ old('name', $facility->name) }}"
                    required
                    maxlength="255"
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
                    value="{{ old('type', $facility->type) }}"
                    required
                    maxlength="100"
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
                    value="{{ old('location', $facility->location) }}"
                    required
                    maxlength="255"
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
                    value="{{ old('capacity', $facility->capacity) }}"
                    required
                    min="1"
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
                    class="w-full resize-none rounded-lg border border-[#dedbd6] bg-[#fcfbfa] px-4 py-3 text-sm outline-none focus:border-[#c9b5a7] focus:ring-2 focus:ring-[#eadfd8]"
                >{{ old('description', $facility->description) }}</textarea>

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
                        @selected(old('status', $facility->status) === 'AVAILABLE')}>
                        Tersedia
                    </option>

                    <option value="MAINTENANCE"
                        @selected(old('status', $facility->status) === 'MAINTENANCE')}>
                        Dalam perbaikan
                    </option>

                    <option value="INACTIVE"
                        @selected(old('status', $facility->status) === 'INACTIVE')}>
                        Nonaktif
                    </option>

                </select>

            </div>


            <div class="flex justify-end gap-3 border-t border-[#eeeae5] pt-5">

                <a
                    href="{{ route('facilities.index') }}"
                    class="rounded-lg border border-[#dedbd6] px-4 py-2.5 text-sm text-[#6b7280] hover:bg-[#f7f5f2]">
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-[#d8c8bc] px-5 py-2.5 text-sm font-medium text-white hover:bg-[#c9b5a7]">
                    Simpan perubahan
                </button>

            </div>

        </form>

    </div>

</div>

@endsection