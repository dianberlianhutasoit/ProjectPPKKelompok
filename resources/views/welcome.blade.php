<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Campus Facility System</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#F3F1EC] text-[#263634]">

    {{-- Navigation --}}
    <header class="border-b border-[#dedbd3] bg-[#F3F1EC]">

        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-5 lg:px-8">

            <a href="{{ url('/') }}" class="flex items-center gap-3">

                <div class="flex h-9 w-9 items-center justify-center bg-[#2f625b] text-xs font-bold text-white">
                    CF
                </div>

                <div>
                    <p class="text-sm font-bold tracking-tight text-[#263634]">
                        Campus Facility
                    </p>

                    <p class="text-[10px] font-semibold uppercase tracking-[0.12em] text-[#7a8581]">
                        Reservation System
                    </p>
                </div>

            </a>


            <div class="flex items-center gap-3">

                @auth

                    <a
                        href="{{ route('dashboard') }}"
                        class="rounded-lg bg-[#2f625b] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#244d48]"
                    >
                        Dashboard
                    </a>

                @else

                    <a
                        href="{{ route('login') }}"
                        class="rounded-lg px-4 py-2.5 text-sm font-semibold text-[#43504d] transition hover:bg-white"
                    >
                        Masuk
                    </a>

                    <a
                        href="{{ route('register') }}"
                        class="rounded-lg bg-[#2f625b] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#244d48]"
                    >
                        Daftar
                    </a>

                @endauth

            </div>

        </div>

    </header>


    <main>

        {{-- Hero --}}
        <section class="mx-auto max-w-7xl px-6 pb-20 pt-16 lg:px-8 lg:pt-24">

            <div class="grid items-center gap-12 lg:grid-cols-[1.15fr_0.85fr]">

                {{-- Hero Text --}}
                <div>

                    <div class="inline-flex items-center gap-2 border border-[#d7d4cc] bg-white px-3 py-1.5">

                        <span class="h-1.5 w-1.5 rounded-full bg-[#2f625b]"></span>

                        <span class="text-xs font-semibold text-[#596460]">
                            Sistem Fasilitas Kampus
                        </span>

                    </div>


                    <h1 class="mt-6 max-w-3xl text-4xl font-bold leading-[1.1] tracking-tight text-[#263634] sm:text-5xl lg:text-6xl">
                        Reservasi fasilitas kampus,
                        <span class="text-[#2f625b]">
                            lebih teratur.
                        </span>
                    </h1>


                    <p class="mt-6 max-w-xl text-base leading-7 text-[#68736f] sm:text-lg">
                        Temukan fasilitas yang tersedia, lihat jadwal penggunaan,
                        dan ajukan reservasi dalam satu sistem.
                    </p>


                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">

                        <a
                            href="{{ route('facilities.index') }}"
                            class="inline-flex items-center justify-center rounded-lg bg-[#2f625b] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#244d48] hover:shadow-md"
                        >
                            Lihat Fasilitas
                            <span class="ml-2">→</span>
                        </a>

                        @guest

                            <a
                                href="{{ route('login') }}"
                                class="inline-flex items-center justify-center rounded-lg border border-[#d5d2ca] bg-white px-6 py-3 text-sm font-semibold text-[#43504d] transition hover:bg-[#f8f7f3]"
                            >
                                Masuk untuk Reservasi
                            </a>

                        @endguest

                    </div>

                </div>


                {{-- Visual --}}
                <div class="relative">

                    <div class="border border-[#d9d6ce] bg-white p-5 shadow-[0_18px_45px_rgba(38,54,52,0.08)]">

                        <div class="flex items-center justify-between border-b border-[#e7e4dd] pb-4">

                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.12em] text-[#2f625b]">
                                    Facility Overview
                                </p>

                                <p class="mt-1 text-sm font-semibold text-[#263634]">
                                    Ketersediaan Fasilitas
                                </p>
                            </div>

                            <span class="text-xs font-semibold text-[#7a8581]">
                                Today
                            </span>

                        </div>


                        <div class="mt-5 space-y-3">

                            <div class="flex items-center justify-between border border-[#e4e1da] px-4 py-4">

                                <div>
                                    <p class="text-sm font-bold text-[#263634]">
                                        Ruang Seminar
                                    </p>

                                    <p class="mt-1 text-xs text-[#7a8581]">
                                        Gedung A · Kapasitas 100
                                    </p>
                                </div>

                                <span class="rounded-full bg-[#e6f0ea] px-3 py-1 text-xs font-bold text-[#426b5a]">
                                    Tersedia
                                </span>

                            </div>


                            <div class="flex items-center justify-between border border-[#e4e1da] px-4 py-4">

                                <div>
                                    <p class="text-sm font-bold text-[#263634]">
                                        Laboratorium Komputer
                                    </p>

                                    <p class="mt-1 text-xs text-[#7a8581]">
                                        Gedung B · Kapasitas 40
                                    </p>
                                </div>

                                <span class="rounded-full bg-[#f4e9dd] px-3 py-1 text-xs font-bold text-[#99633d]">
                                    Terjadwal
                                </span>

                            </div>


                            <div class="flex items-center justify-between border border-[#e4e1da] px-4 py-4">

                                <div>
                                    <p class="text-sm font-bold text-[#263634]">
                                        Ruang Diskusi
                                    </p>

                                    <p class="mt-1 text-xs text-[#7a8581]">
                                        Gedung C · Kapasitas 12
                                    </p>
                                </div>

                                <span class="rounded-full bg-[#e6f0ea] px-3 py-1 text-xs font-bold text-[#426b5a]">
                                    Tersedia
                                </span>

                            </div>

                        </div>


                        <div class="mt-5 border-t border-[#e7e4dd] pt-4">

                            <div class="flex items-center justify-between">

                                <span class="text-xs text-[#8a9490]">
                                    Slot reservasi
                                </span>

                                <span class="text-xs font-bold text-[#2f625b]">
                                    07:00 — 20:00
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        {{-- Features --}}
        <section class="border-y border-[#dedbd3] bg-white">

            <div class="mx-auto max-w-7xl px-6 py-14 lg:px-8">

                <div class="grid gap-0 md:grid-cols-3">

                    <div class="border-b border-[#e4e1da] pb-8 md:border-b-0 md:border-r md:pr-8">

                        <p class="text-xs font-bold uppercase tracking-[0.12em] text-[#2f625b]">
                            01
                        </p>

                        <h2 class="mt-3 text-lg font-bold text-[#263634]">
                            Cari fasilitas
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-[#737d79]">
                            Lihat daftar fasilitas kampus beserta lokasi,
                            kapasitas, dan status ketersediaannya.
                        </p>

                    </div>


                    <div class="border-b border-[#e4e1da] py-8 md:border-b-0 md:border-r md:px-8 md:py-0">

                        <p class="text-xs font-bold uppercase tracking-[0.12em] text-[#2f625b]">
                            02
                        </p>

                        <h2 class="mt-3 text-lg font-bold text-[#263634]">
                            Pilih jadwal
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-[#737d79]">
                            Periksa ketersediaan berdasarkan tanggal dan
                            slot waktu sebelum melakukan reservasi.
                        </p>

                    </div>


                    <div class="pt-8 md:pl-8 md:pt-0">

                        <p class="text-xs font-bold uppercase tracking-[0.12em] text-[#2f625b]">
                            03
                        </p>

                        <h2 class="mt-3 text-lg font-bold text-[#263634]">
                            Ajukan reservasi
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-[#737d79]">
                            Kirim pengajuan dan pantau status reservasi
                            sampai diproses oleh staff.
                        </p>

                    </div>

                </div>

            </div>

        </section>


        {{-- Bottom CTA --}}
        <section class="mx-auto max-w-7xl px-6 py-16 lg:px-8">

            <div class="flex flex-col items-start justify-between gap-6 border border-[#d9d6ce] bg-[#2f625b] px-7 py-8 sm:flex-row sm:items-center sm:px-9">

                <div>

                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-white/55">
                        Ready to explore?
                    </p>

                    <h2 class="mt-2 text-2xl font-bold text-white">
                        Temukan fasilitas yang kamu butuhkan.
                    </h2>

                    <p class="mt-2 text-sm text-white/65">
                        Cek fasilitas dan jadwal yang tersedia sekarang.
                    </p>

                </div>


                <a
                    href="{{ route('facilities.index') }}"
                    class="shrink-0 rounded-lg bg-white px-5 py-3 text-sm font-bold text-[#2f625b] transition hover:bg-[#f3f1ec]"
                >
                    Lihat Fasilitas →
                </a>

            </div>

        </section>

    </main>


    {{-- Footer --}}
    <footer class="border-t border-[#dedbd3]">

        <div class="mx-auto flex max-w-7xl flex-col gap-2 px-6 py-6 text-xs text-[#8a9490] sm:flex-row sm:items-center sm:justify-between lg:px-8">

            <p>
                © {{ date('Y') }} Campus Facility System
            </p>

            <p>
                Sistem Reservasi & Pelaporan Fasilitas Kampus
            </p>

        </div>

    </footer>

</body>

</html>