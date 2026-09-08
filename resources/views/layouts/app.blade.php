<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Campus Facility System')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#faf9f7] text-[#4b5563]">

    {{-- Navbar --}}
    <nav class="border-b border-[#e9e3dd] bg-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">

            <a href="{{ route('facilities.index') }}"
               class="text-lg font-semibold tracking-tight text-[#3f4f63]">
                Campus Facility
            </a>

            <div class="flex items-center gap-5 text-sm">

                <a href="{{ route('facilities.index') }}"
                   class="text-[#6b7280] transition hover:text-[#9b8878]">
                    Fasilitas
                </a>

                @auth

                    <a href="{{ route('dashboard') }}"
                       class="hidden text-[#6b7280] transition hover:text-[#9b8878] sm:inline">
                        Dashboard
                    </a>

                    @if(auth()->user()->role === 'ADMIN')
                        <a href="{{ route('admin.users.index') }}"
                           class="hidden text-[#6b7280] transition hover:text-[#9b8878] sm:inline">
                            Kelola Akun
                        </a>
                    @endif

                    <span class="hidden border-l border-[#e9e3dd] pl-5 text-[#9ca3af] md:inline">
                        {{ auth()->user()->name }}
                    </span>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf

                        <button type="submit"
                                class="!m-0 !p-0 text-[#a88f80] transition hover:text-[#876f60]">
                            Logout
                        </button>
                    </form>

                @else

                    <a href="{{ route('login') }}"
                       class="text-[#6b7280] transition hover:text-[#9b8878]">
                        Login
                    </a>

                    <a href="{{ route('register') }}"
                       class="rounded-lg bg-[#d8c8bc] px-4 py-2 font-medium text-white transition hover:bg-[#c9b5a7]">
                        Register
                    </a>

                @endauth

            </div>
        </div>
    </nav>


    {{-- Main --}}
    <main class="mx-auto max-w-6xl px-6 py-8">

        {{-- Success --}}
        @if(session('success'))
            <div class="mb-6 rounded-xl border border-[#dce9dc] bg-[#f0f7f0] px-4 py-3 text-sm text-[#5f7562]">
                {{ session('success') }}
            </div>
        @endif


        {{-- Error --}}
        @if($errors->any())
            <div class="mb-6 rounded-xl border border-[#ecdada] bg-[#faf0f0] px-4 py-3 text-sm text-[#875f5f]">

                <ul class="list-disc space-y-1 pl-5">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>

            </div>
        @endif


        @yield('content')

    </main>

</body>
</html>