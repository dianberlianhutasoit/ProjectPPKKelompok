@extends('layouts.app')

@section('title', 'Tambah Akun')

@section('content')

<div class="mx-auto max-w-2xl">

    <a
        href="{{ route('admin.users.index') }}"
        class="mb-6 inline-flex text-sm text-[#9b8878] hover:text-[#806b5d]">
        ← Kembali ke kelola akun
    </a>


    <div class="mb-7">

        <p class="text-sm text-[#a2a7ad]">
            Administrator
        </p>

        <h1 class="mt-1 text-2xl font-semibold text-[#3f4f63]">
            Tambah akun
        </h1>

        <p class="mt-2 text-sm text-[#8b929b]">
            Buat akun pengguna atau staff yang langsung aktif.
        </p>

    </div>


    <div class="rounded-2xl border border-[#e9e3dd] bg-white p-7 shadow-sm">

        <form
            method="POST"
            action="{{ route('admin.users.store') }}"
            class="space-y-5">

            @csrf


            {{-- Name --}}
            <div>

                <label
                    for="name"
                    class="mb-2 block text-sm font-medium text-[#59636f]">
                    Nama
                </label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    maxlength="255"
                    placeholder="Nama lengkap"
                    class="w-full rounded-lg border border-[#dedbd6] bg-[#fcfbfa] px-4 py-3 text-sm outline-none placeholder:text-[#b7b7b7] focus:border-[#c9b5a7] focus:ring-2 focus:ring-[#eadfd8]"
                >

            </div>


            {{-- Email --}}
            <div>

                <label
                    for="email"
                    class="mb-2 block text-sm font-medium text-[#59636f]">
                    Email
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    placeholder="nama@email.com"
                    class="w-full rounded-lg border border-[#dedbd6] bg-[#fcfbfa] px-4 py-3 text-sm outline-none placeholder:text-[#b7b7b7] focus:border-[#c9b5a7] focus:ring-2 focus:ring-[#eadfd8]"
                >

            </div>


            {{-- Role --}}
            <div>

                <label
                    for="role"
                    class="mb-2 block text-sm font-medium text-[#59636f]">
                    Role
                </label>

                <select
                    id="role"
                    name="role"
                    required
                    class="w-full rounded-lg border border-[#dedbd6] bg-[#fcfbfa] px-4 py-3 text-sm outline-none focus:border-[#c9b5a7] focus:ring-2 focus:ring-[#eadfd8]">

                    <option
                        value="USER"
                        @selected(old('role', 'USER') === 'USER')}>
                        USER — Pengguna
                    </option>

                    <option
                        value="STAFF"
                        @selected(old('role') === 'STAFF')}>
                        STAFF — Petugas
                    </option>

                </select>

            </div>


            {{-- Password --}}
            <div>

                <label
                    for="password"
                    class="mb-2 block text-sm font-medium text-[#59636f]">
                    Password
                </label>

                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    minlength="6"
                    placeholder="Minimal 6 karakter"
                    class="w-full rounded-lg border border-[#dedbd6] bg-[#fcfbfa] px-4 py-3 text-sm outline-none placeholder:text-[#b7b7b7] focus:border-[#c9b5a7] focus:ring-2 focus:ring-[#eadfd8]"
                >

            </div>


            {{-- Confirm --}}
            <div>

                <label
                    for="password_confirmation"
                    class="mb-2 block text-sm font-medium text-[#59636f]">
                    Konfirmasi password
                </label>

                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    minlength="6"
                    placeholder="Ulangi password"
                    class="w-full rounded-lg border border-[#dedbd6] bg-[#fcfbfa] px-4 py-3 text-sm outline-none placeholder:text-[#b7b7b7] focus:border-[#c9b5a7] focus:ring-2 focus:ring-[#eadfd8]"
                >

            </div>


            {{-- Info --}}
            <div class="rounded-lg bg-[#f7f3ef] px-4 py-3 text-sm text-[#7d7168]">

                Akun yang dibuat oleh admin akan berstatus
                <strong>ACTIVE</strong> dan dapat langsung digunakan.

            </div>


            {{-- Buttons --}}
            <div class="flex justify-end gap-3 border-t border-[#eeeae5] pt-5">

                <a
                    href="{{ route('admin.users.index') }}"
                    class="rounded-lg border border-[#dedbd6] px-4 py-2.5 text-sm text-[#6b7280] hover:bg-[#f7f5f2]">
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-[#d8c8bc] px-5 py-2.5 text-sm font-medium text-white hover:bg-[#c9b5a7]">
                    Buat akun
                </button>

            </div>

        </form>

    </div>

</div>

@endsection