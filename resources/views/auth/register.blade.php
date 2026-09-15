@extends('layouts.app')

@section('title', 'Registrasi')

@section('content')

<div class="mx-auto max-w-md">

    <div class="mb-8 text-center">

        <p class="mb-2 text-sm font-medium text-[#b09b8c]">
            Campus Facility
        </p>

        <h1 class="text-2xl font-semibold text-[#3f4f63]">
            Buat akun baru
        </h1>

        <p class="mt-2 text-sm text-[#8b929b]">
            Registrasi sebagai pengguna fasilitas kampus.
        </p>

    </div>


    <div class="rounded-2xl border border-[#e9e3dd] bg-white p-7 shadow-sm">

        <div class="mb-6 rounded-lg bg-[#f7f3ef] px-4 py-3 text-sm leading-6 text-[#7d7168]">
            Akun yang dibuat melalui registrasi akan menunggu verifikasi admin sebelum dapat digunakan.
        </div>


        <form method="POST"
              action="{{ route('register') }}"
              class="space-y-5">

            @csrf


            {{-- Nama --}}
            <div>

                <label for="name"
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
                    class="w-full rounded-lg border border-[#dedbd6] bg-[#fcfbfa] px-4 py-3 text-sm outline-none transition placeholder:text-[#b7b7b7] focus:border-[#c9b5a7] focus:ring-2 focus:ring-[#eadfd8]"
                >

            </div>


            {{-- Email --}}
            <div>

                <label for="email"
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
                    class="w-full rounded-lg border border-[#dedbd6] bg-[#fcfbfa] px-4 py-3 text-sm outline-none transition placeholder:text-[#b7b7b7] focus:border-[#c9b5a7] focus:ring-2 focus:ring-[#eadfd8]"
                >

            </div>


            {{-- Password --}}
            <div>

                <label for="password"
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
                    class="w-full rounded-lg border border-[#dedbd6] bg-[#fcfbfa] px-4 py-3 text-sm outline-none transition placeholder:text-[#b7b7b7] focus:border-[#c9b5a7] focus:ring-2 focus:ring-[#eadfd8]"
                >

            </div>


            {{-- Confirm --}}
            <div>

                <label for="password_confirmation"
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
                    class="w-full rounded-lg border border-[#dedbd6] bg-[#fcfbfa] px-4 py-3 text-sm outline-none transition placeholder:text-[#b7b7b7] focus:border-[#c9b5a7] focus:ring-2 focus:ring-[#eadfd8]"
                >

            </div>


            <button
                type="submit"
                class="w-full rounded-lg bg-[#d8c8bc] px-4 py-3 text-sm font-medium text-white transition hover:bg-[#c9b5a7]">
                Daftar
            </button>

        </form>


        <div class="mt-6 border-t border-[#eeeae5] pt-5 text-center text-sm text-[#8b929b]">

            Sudah punya akun?

            <a href="{{ route('login') }}"
               class="font-medium text-[#a48977] hover:text-[#806b5d]">
                Login

            </a>

        </div>

    </div>

</div>

@endsection