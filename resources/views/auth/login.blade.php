@extends('layouts.app')

@section('title', 'Login - Campus Facility System')

@section('content')

<div class="mx-auto flex min-h-[76vh] max-w-5xl items-center py-8">

    <div class="grid w-full overflow-hidden rounded-2xl border border-[#dedbd3] bg-white shadow-[0_12px_35px_rgba(38,54,52,0.08)] md:grid-cols-2">

        {{-- LEFT : VISUAL --}}
        <div class="relative hidden min-h-[560px] overflow-hidden bg-[#dfeae5] md:flex">

            {{-- Decorative shapes --}}
            <div class="absolute -left-20 -top-20 h-64 w-64 rounded-full bg-[#c4d9d1]"></div>

            <div class="absolute -bottom-24 -right-16 h-72 w-72 rounded-full bg-[#ead8c8]"></div>

            <div class="absolute left-8 top-8 h-16 w-16 rounded-full border-[10px] border-[#a65f3e]/20"></div>

            {{-- Content --}}
            <div class="relative z-10 flex w-full flex-col justify-between p-10">

                <div>
                    <div class="inline-flex items-center rounded-full bg-white/80 px-4 py-2 text-xs font-bold tracking-wide text-[#2f625b]">
                        CAMPUS FACILITY
                    </div>

                    <h2 class="mt-7 max-w-sm text-4xl font-bold leading-tight tracking-tight text-[#263634]">
                        Kelola fasilitas kampus dengan lebih mudah.
                    </h2>

                    <p class="mt-4 max-w-sm text-sm leading-6 text-[#596460]">
                        Temukan fasilitas, cek ketersediaan waktu, dan lakukan reservasi dalam satu sistem.
                    </p>
                </div>


                {{-- Simple illustration --}}
                <div class="relative mx-auto mt-8 flex h-60 w-full max-w-sm items-end justify-center">

                    {{-- Building --}}
                    <div class="relative h-44 w-64 rounded-t-lg bg-white shadow-sm">

                        {{-- Roof --}}
                        <div class="absolute -left-5 -top-7 h-0 w-0 border-b-[30px] border-l-[147px] border-r-[147px] border-b-[#2f625b] border-l-transparent border-r-transparent"></div>

                        {{-- Windows --}}
                        <div class="grid grid-cols-4 gap-4 px-7 pt-8">

                            <div class="h-12 rounded bg-[#dfeae5]"></div>
                            <div class="h-12 rounded bg-[#dfeae5]"></div>
                            <div class="h-12 rounded bg-[#dfeae5]"></div>
                            <div class="h-12 rounded bg-[#dfeae5]"></div>

                        </div>

                        {{-- Door --}}
                        <div class="absolute bottom-0 left-1/2 h-20 w-12 -translate-x-1/2 rounded-t bg-[#a65f3e]"></div>

                    </div>

                    {{-- Ground --}}
                    <div class="absolute bottom-0 h-3 w-full rounded-full bg-[#2f625b]/20"></div>

                </div>


                <div class="flex items-center gap-3 text-xs text-[#68736f]">
                    <span class="h-2 w-2 rounded-full bg-[#2f625b]"></span>
                    <span>Campus Facility Reservation System</span>
                </div>

            </div>
        </div>


        {{-- RIGHT : LOGIN --}}
        <div class="flex min-h-[560px] items-center bg-white px-7 py-10 sm:px-12">

            <div class="w-full max-w-sm mx-auto">

                <div class="mb-8">

                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#2f625b]">
                        Welcome back
                    </p>

                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-[#263634]">
                        Log in
                    </h1>

                    <p class="mt-3 text-sm leading-6 text-[#68736f]">
                        Masuk ke akun Anda untuk mengakses layanan fasilitas kampus.
                    </p>

                </div>


                {{-- FORM --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-5">

                    @csrf

                    {{-- Email --}}
                    <div>

                        <label for="email"
                               class="mb-2 block text-sm font-semibold text-[#43504d]">
                            Email
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="nama@email.com"
                            class="w-full rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-4 py-3 text-sm text-[#263634] outline-none transition placeholder:text-[#a2aaa7] focus:border-[#2f625b] focus:bg-white focus:ring-4 focus:ring-[#2f625b]/10"
                        >

                    </div>


                    {{-- Password --}}
                    <div>

                        <label for="password"
                               class="mb-2 block text-sm font-semibold text-[#43504d]">
                            Password
                        </label>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan password"
                            class="w-full rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-4 py-3 text-sm text-[#263634] outline-none transition placeholder:text-[#a2aaa7] focus:border-[#2f625b] focus:bg-white focus:ring-4 focus:ring-[#2f625b]/10"
                        >

                    </div>


                    {{-- Remember --}}
                    <div class="flex items-center gap-2">

                        <input
                            id="remember"
                            type="checkbox"
                            name="remember"
                            class="h-4 w-4 rounded border-[#cbc8c0] text-[#2f625b] focus:ring-[#2f625b]"
                        >

                        <label for="remember"
                               class="text-sm text-[#68736f]">
                            Ingat saya
                        </label>

                    </div>


                    {{-- Button --}}
                    <button
                        type="submit"
                        class="w-full rounded-lg bg-[#2f625b] px-4 py-3.5 text-sm font-bold text-white transition hover:bg-[#244d48] hover:shadow-md">
                        Login
                    </button>

                </form>


                {{-- Register --}}
                <div class="mt-8 border-t border-[#e8e5de] pt-6 text-center">

                    <p class="text-sm text-[#68736f]">
                        Belum memiliki akun?

                        <a href="{{ route('register') }}"
                           class="font-bold text-[#2f625b] hover:underline">
                            Daftar sekarang
                        </a>
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection