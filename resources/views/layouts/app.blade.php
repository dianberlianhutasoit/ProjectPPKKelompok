<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ReserVa - Reservasi & Fasilitas')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#f5f3ee] text-[#263634]">
    <nav class="fixed top-0 left-0 right-0 z-50 border-b border-[#dedbd3] bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
            <a href="{{ route('facilities.index') }}"
                class="flex items-center gap-1 text-lg font-bold tracking-tight text-[#2f625b]">
                {{-- Logo ReserVa --}}
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="h-9 w-9 shrink-0" fill="none">
                    <path d="M8 40V18L24 7L40 18V40H8Z" fill="#2f625b" />
                    <path d="M15 40V23H33V40" fill="#f5f3ee" />
                    <path d="M12 18H36" stroke="#f5f3ee" stroke-width="2" stroke-linecap="round" />
                    <path d="M19 28H22" stroke="#2f625b" stroke-width="2" stroke-linecap="round" />
                    <path d="M26 28H29" stroke="#2f625b" stroke-width="2" stroke-linecap="round" />
                    <path d="M19 33H22" stroke="#2f625b" stroke-width="2" stroke-linecap="round" />
                    <path d="M26 33H29" stroke="#2f625b" stroke-width="2" stroke-linecap="round" />
                </svg>
                <span>ReserVa</span>
            </a>

            <div class="flex items-center gap-6 text-base">

                <a href="{{ route('facilities.index') }}"
                    class="relative hidden py-2 transition sm:inline
                {{ request()->routeIs('facilities.*')
                    ? 'font-bold text-[#2f625b] after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-full after:bg-[#2f625b]'
                    : 'text-[#596460] hover:text-[#2f625b]' }}">
                    Fasilitas
                </a>

                @auth

                    @if (auth()->user()->role === 'ADMIN')
                        <a href="{{ route('admin.users.index') }}"
                            class="relative hidden py-2 transition sm:inline
                        {{ request()->routeIs('admin.users.*')
                            ? 'font-bold text-[#2f625b] after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-full after:bg-[#2f625b]'
                            : 'text-[#596460] hover:text-[#2f625b]' }}">
                            Kelola Akun
                        </a>
                    @endif

                    @if (auth()->user()->role === 'USER')
                        <a href="{{ route('reservations.index') }}"
                            class="relative hidden py-2 transition sm:inline
                        {{ request()->routeIs('reservations.*')
                            ? 'font-bold text-[#2f625b] after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-full after:bg-[#2f625b]'
                            : 'text-[#596460] hover:text-[#2f625b]' }}">
                            Reservasi Saya
                        </a>

                        <a href="{{ route('reports.index') }}"
                            class="relative hidden py-2 transition sm:inline
                        {{ request()->routeIs('reports.*')
                            ? 'font-bold text-[#2f625b] after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-full after:bg-[#2f625b]'
                            : 'text-[#596460] hover:text-[#2f625b]' }}">
                            Laporan Saya
                        </a>
                    @endif

                    @if (in_array(auth()->user()->role, ['STAFF']))
                        <a href="{{ route('staff.reservations.index') }}"
                            class="relative hidden py-2 transition sm:inline
                        {{ request()->routeIs('staff.reservations.*')
                            ? 'font-bold text-[#2f625b] after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-full after:bg-[#2f625b]'
                            : 'text-[#596460] hover:text-[#2f625b]' }}">
                            Reservasi
                        </a>

                        <a href="{{ route('staff.reports.index') }}"
                            class="relative hidden py-2 transition sm:inline
                        {{ request()->routeIs('staff.reports.*')
                            ? 'font-bold text-[#2f625b] after:absolute after:bottom-0 after:left-0 after:h-0.5 after:w-full after:bg-[#2f625b]'
                            : 'text-[#596460] hover:text-[#2f625b]' }}">
                            Laporan
                        </a>
                    @endif

                    <span class="hidden border-l border-[#dedbd3] pl-6 text-base text-[#7a827f] md:inline">
                        {{ auth()->user()->name }}
                    </span>

                    <form action="{{ route('logout') }}" method="POST">

                        @csrf

                        <button type="submit"
                            class="!m-0 !p-0 text-base font-medium text-[#a65f3e] transition hover:text-[#87482f]">
                            Logout
                        </button>

                    </form>
                @else
                    <a href="{{ route('login') }}" class="py-2 text-base text-[#596460] transition hover:text-[#2f625b]">
                        Login
                    </a>

                    <a href="{{ route('register') }}"
                        class="rounded-md bg-[#2f625b] px-4 py-2 text-base font-semibold text-white transition hover:bg-[#244d48]">
                        Register
                    </a>

                @endauth
            </div>
        </div>
    </nav>

    <main class="mx-auto max-w-7xl px-6 pb-9 pt-20">

        @if (session('success'))
            <div class="mb-6 border-l-4 border-[#2f625b] bg-white px-5 py-4 text-base text-[#4d5c58]">

                <div class="flex items-start gap-3">

                    <span class="mt-0.5 font-bold text-[#2f625b]">
                        ✓
                    </span>

                    <p>
                        {{ session('success') }}
                    </p>

                </div>

            </div>
        @endif

        @if ($errors->any())

            <div class="mb-6 border-l-4 border-[#a65f3e] bg-white px-5 py-4 text-base text-[#714c3d]">

                <div class="mb-2 font-semibold text-[#87482f]">
                    Terdapat kesalahan:
                </div>

                <ul class="list-disc space-y-1 pl-5">

                    @foreach ($errors->all() as $e)
                        <li>
                            {{ $e }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </main>
</body>

</html>