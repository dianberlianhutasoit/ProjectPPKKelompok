@extends('layouts.app')

@section('title', 'Login')

@section('content')

<div class="mx-auto max-w-md">

    <div class="mb-8 text-center">
        <p class="mb-2 text-sm font-medium text-[#b09b8c]">
            Campus Facility
        </p>

        <h1 class="text-2xl font-semibold text-[#3f4f63]">
            Selamat datang kembali
        </h1>

        <p class="mt-2 text-sm text-[#8b929b]">
            Masuk untuk melanjutkan ke sistem fasilitas kampus.
        </p>
    </div>


    <div class="rounded-2xl border border-[#e9e3dd] bg-white p-7 shadow-sm">

        <form method="POST" action="{{ route('login') }}" class="space-y-5">

            @csrf

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
                    autofocus
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
                    placeholder="Masukkan password"
                    class="w-full rounded-lg border border-[#dedbd6] bg-[#fcfbfa] px-4 py-3 text-sm outline-none transition placeholder:text-[#b7b7b7] focus:border-[#c9b5a7] focus:ring-2 focus:ring-[#eadfd8]"
                >
            </div>


            {{-- Remember --}}
            <label class="flex cursor-pointer items-center gap-2 text-sm text-[#7b8188]">

                <input
                    type="checkbox"
                    name="remember"
                    value="1"
                    class="h-4 w-4 rounded border-[#d6d2cc] text-[#b59d8c] focus:ring-[#d8c8bc]"
                >

                Ingat saya

            </label>


            {{-- Button --}}
            <button
                type="submit"
                class="w-full rounded-lg bg-[#d8c8bc] px-4 py-3 text-sm font-medium text-white transition hover:bg-[#c9b5a7]">
                Login
            </button>

        </form>


        <div class="mt-6 border-t border-[#eeeae5] pt-5 text-center text-sm text-[#8b929b]">

            Belum punya akun?

            <a href="{{ route('register') }}"
               class="font-medium text-[#a48977] hover:text-[#806b5d]">
                Daftar sekarang
            </a>

        </div>

    </div>

</div>

@endsection