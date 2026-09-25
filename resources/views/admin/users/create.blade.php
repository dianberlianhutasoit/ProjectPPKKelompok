@extends('layouts.app')

@section('title', 'Tambah Pengguna - Campus Facility System')

@section('content')

<div class="mx-auto max-w-5xl">

    {{-- Header --}}
    <div class="mb-7">

        <a
            href="{{ route('admin.users.index') }}"
            class="inline-flex items-center gap-2 text-sm font-semibold text-[#2f625b] hover:underline"
        >
            ← Kembali ke pengguna
        </a>

        <div class="mt-6">

            <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#2f625b]">
                Administration
            </p>

            <h1 class="mt-2 text-3xl font-bold tracking-tight text-[#263634]">
                Tambah Pengguna
            </h1>

            <p class="mt-2 text-sm leading-6 text-[#68736f]">
                Buat akun baru untuk pengguna atau staff sistem.
            </p>

        </div>

    </div>

    <div class="grid gap-6 lg:grid-cols-[280px_1fr]">

        {{-- Info Panel --}}
        <div class="h-fit border border-[#dedbd3] bg-[#2f625b] p-6 text-white">

            <p class="text-xs font-bold uppercase tracking-[0.14em] text-white/60">
                Account Setup
            </p>

            <h2 class="mt-3 text-xl font-bold leading-7">
                Akun Pengguna
            </h2>

            <p class="mt-2 text-sm leading-6 text-white/65">
                Pastikan data akun yang dimasukkan sudah benar sebelum dibuat.
            </p>

            <div class="mt-7 border-t border-white/15 pt-5">

                <p class="text-xs font-semibold text-white/55">
                    Informasi
                </p>

                <ul class="mt-3 space-y-3 text-sm leading-5 text-white/75">

                    <li class="flex gap-2">
                        <span class="text-white/45">01</span>
                        Email digunakan untuk login.
                    </li>

                    <li class="flex gap-2">
                        <span class="text-white/45">02</span>
                        Password awal dapat diberikan kepada pengguna.
                    </li>

                    <li class="flex gap-2">
                        <span class="text-white/45">03</span>
                        Pilih role sesuai kebutuhan akun.
                    </li>

                </ul>

            </div>

        </div>

        {{-- Form --}}
        <div class="border border-[#dedbd3] bg-white">

            <div class="border-b border-[#e4e1da] px-6 py-5">

                <p class="text-xs font-bold uppercase tracking-[0.12em] text-[#2f625b]">
                    User Information
                </p>

                <h2 class="mt-1 text-lg font-bold text-[#263634]">
                    Data Akun
                </h2>

            </div>

            <form
                method="POST"
                action="{{ route('admin.users.store') }}"
            >

                @csrf

                <div class="space-y-5 p-6">

                    {{-- Name --}}
                    <div>

                        <label
                            for="name"
                            class="mb-2 block text-sm font-semibold text-[#43504d]"
                        >
                            Nama Lengkap
                        </label>

                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            placeholder="Masukkan nama lengkap"
                            class="w-full rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-4 py-3 text-sm text-[#263634] outline-none transition placeholder:text-[#a2aaa7] focus:border-[#2f625b] focus:bg-white focus:ring-4 focus:ring-[#2f625b]/10"
                        >

                        @error('name')
                            <p class="mt-1.5 text-xs text-[#a65f3e]">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Email --}}
                    <div>

                        <label
                            for="email"
                            class="mb-2 block text-sm font-semibold text-[#43504d]"
                        >
                            Email
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            placeholder="contoh@undip.ac.id"
                            class="w-full rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-4 py-3 text-sm text-[#263634] outline-none transition placeholder:text-[#a2aaa7] focus:border-[#2f625b] focus:bg-white focus:ring-4 focus:ring-[#2f625b]/10"
                        >

                        @error('email')
                            <p class="mt-1.5 text-xs text-[#a65f3e]">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Password --}}
                    <div>

                        <label
                            for="password"
                            class="mb-2 block text-sm font-semibold text-[#43504d]"
                        >
                            Password Awal
                        </label>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            placeholder="Masukkan password awal"
                            class="w-full rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-4 py-3 text-sm text-[#263634] outline-none transition placeholder:text-[#a2aaa7] focus:border-[#2f625b] focus:bg-white focus:ring-4 focus:ring-[#2f625b]/10"
                        >

                        <p class="mt-1.5 text-xs text-[#8a9490]">
                            Password ini dapat diberikan kepada pengguna untuk login pertama kali.
                        </p>

                        @error('password')
                            <p class="mt-1.5 text-xs text-[#a65f3e]">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Role --}}
                    <div>

                        <label
                            for="role"
                            class="mb-2 block text-sm font-semibold text-[#43504d]"
                        >
                            Role
                        </label>

                        <select
                            id="role"
                            name="role"
                            required
                            class="w-full rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-4 py-3 text-sm text-[#263634] outline-none transition focus:border-[#2f625b] focus:bg-white focus:ring-4 focus:ring-[#2f625b]/10"
                        >

                            <option value="">
                                Pilih role
                            </option>

                            <option
                                value="USER"
                                @selected(old('role') === 'USER')
                            >
                                User
                            </option>

                            <option
                                value="STAFF"
                                @selected(old('role') === 'STAFF')
                            >
                                Staff
                            </option>

                            <option
                                value="ADMIN"
                                @selected(old('role') === 'ADMIN')
                            >
                                Admin
                            </option>

                        </select>

                        @error('role')
                            <p class="mt-1.5 text-xs text-[#a65f3e]">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Active --}}
                    <div class="border border-[#e4e1da] bg-[#fafaf8] p-4">

                        <label class="flex cursor-pointer items-start gap-3">

                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                @checked(old('is_active', true))
                                class="mt-1 h-4 w-4 rounded border-[#c9c6bf] text-[#2f625b] focus:ring-[#2f625b]"
                            >

                            <span>

                                <span class="block text-sm font-semibold text-[#43504d]">
                                    Aktifkan akun
                                </span>

                                <span class="mt-1 block text-xs leading-5 text-[#7b8581]">
                                    Pengguna dapat langsung menggunakan akun setelah dibuat.
                                </span>

                            </span>

                        </label>

                        @error('is_active')
                            <p class="mt-1.5 text-xs text-[#a65f3e]">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </div>

                {{-- Actions --}}
                <div class="flex flex-col-reverse gap-3 border-t border-[#e4e1da] bg-[#fafaf8] px-6 py-4 sm:flex-row sm:justify-end">

                    <a
                        href="{{ route('admin.users.index') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-[#d5d2ca] bg-white px-5 py-2.5 text-sm font-semibold text-[#596460] transition hover:bg-[#f1f0eb]"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-[#2f625b] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#244d48] hover:shadow-md"
                    >
                        Buat Akun
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection