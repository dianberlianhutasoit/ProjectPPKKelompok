<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Sistem Fasilitas Kampus')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#f5f3ee] text-[#263634]">

    {{-- Navbar --}}
    <nav class="fixed top-0 left-0 right-0 z-50 border-b border-[#dedbd3] bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">

            {{-- Logo / Brand --}}
            <a href="{{ route('facilities.index') }}"
               class="text-[17px] font-bold tracking-tight text-[#2f625b]">
                Campus Facility
            </a>

            {{-- Navigation --}}
            <div class="flex items-center gap-6 text-sm">

                {{-- Fasilitas --}}
                <a href="{{ route('facilities.index') }}"
                class="relative py-2 font-medium transition
                {{ request()->routeIs('facilities.*')
                        ? 'font-bold text-[#2f625b] after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-full after:bg-[#2f625b]'
                        : 'text-[#596460] hover:text-[#2f625b]' }}">
                    Fasilitas
                </a>

                @auth

                    {{-- Admin --}}
                    @if(auth()->user()->role === 'ADMIN')
                        <a href="{{ route('admin.users.index') }}"
                        class="relative hidden py-2 transition sm:inline
                        {{ request()->routeIs('admin.users.*')
                                ? 'font-bold text-[#2f625b] after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-full after:bg-[#2f625b]'
                                : 'text-[#596460] hover:text-[#2f625b]' }}">
                            Kelola Akun
                        </a>
                    @endif

                    {{-- User --}}
                    @if(auth()->user()->role === 'USER')
                        <a href="{{ route('reservations.index') }}"
                        class="relative hidden py-2 transition sm:inline
                        {{ request()->routeIs('reservations.*')
                                ? 'font-bold text-[#2f625b] after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-full after:bg-[#2f625b]'
                                : 'text-[#596460] hover:text-[#2f625b]' }}">
                            Reservasi Saya
                        </a>
                    @endif

                    {{-- Staff / Admin --}}
                    @if(in_array(auth()->user()->role, ['STAFF']))
                        <a href="{{ route('staff.reservations.index') }}"
                        class="relative hidden py-2 transition sm:inline
                        {{ request()->routeIs('staff.reservations.*')
                                ? 'font-bold text-[#2f625b] after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-full after:bg-[#2f625b]'
                                : 'text-[#596460] hover:text-[#2f625b]' }}">
                            Reservasi
                        </a>
                    @endif

                    <span class="hidden border-l border-[#dedbd3] pl-6 text-[#7a827f] md:inline">
                        {{ auth()->user()->name }}
                    </span>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="!m-0 !p-0 font-medium text-[#a65f3e] transition hover:text-[#87482f]">
                            Logout
                        </button>
                    </form>

                @else

                    <a href="{{ route('login') }}"
                    class="py-2 text-[#596460] transition hover:text-[#2f625b]">
                        Login
                    </a>

                    <a href="{{ route('register') }}"
                    class="rounded-md bg-[#2f625b] px-4 py-2 font-semibold text-white transition hover:bg-[#244d48]">
                        Register
                    </a>

                @endauth

            </div>
        </div>
    </nav>


    {{-- Main Content --}}
    <main class="mx-auto max-w-7xl px-6 pb-9 pt-28">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-6 border-l-4 border-[#2f625b] bg-white px-5 py-4 text-sm text-[#4d5c58]">
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 font-bold text-[#2f625b]">✓</span>
                    <p>
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        @endif


        {{-- Error Message --}}
        @if($errors->any())
            <div class="mb-6 border-l-4 border-[#a65f3e] bg-white px-5 py-4 text-sm text-[#714c3d]">
                <div class="mb-2 font-semibold text-[#87482f]">
                    Terdapat kesalahan:
                </div>
                <ul class="list-disc space-y-1 pl-5">
                    @foreach($errors->all() as $e)
                        <li>
                            {{ $e }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- Page Content --}}
        @yield('content')

    </main>

</body>
</html>