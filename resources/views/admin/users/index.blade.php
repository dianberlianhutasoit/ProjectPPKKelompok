@extends('layouts.app')

@section('title', 'Kelola Pengguna - Campus Facility System')

@section('content')

<div class="mx-auto max-w-7xl">

    {{-- Header --}}
    <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

        <div>
            <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#2f625b]">
                Administration
            </p>

            <h1 class="mt-2 text-3xl font-bold tracking-tight text-[#263634]">
                Kelola Pengguna
            </h1>

            <p class="mt-2 text-sm leading-6 text-[#68736f]">
                Tambahkan, periksa, dan kelola akun pengguna sistem.
            </p>
        </div>

        <a
            href="{{ route('admin.users.create') }}"
            class="inline-flex w-fit items-center justify-center rounded-lg bg-[#2f625b] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#244d48]"
        >
            + Tambah Pengguna
        </a>

    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="mb-5 border border-[#cfe0d7] bg-[#edf5f0] px-4 py-3 text-sm font-medium text-[#426b5a]">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 border border-[#e6d0c5] bg-[#fbf0eb] px-4 py-3 text-sm font-medium text-[#a65f3e]">
            {{ session('error') }}
        </div>
    @endif


    @if($users->count())

        {{-- Desktop Table --}}
        <div class="hidden overflow-hidden border border-[#dedbd3] bg-white md:block">

            <div class="overflow-x-auto">

                <table class="w-full text-left">

                    <thead class="border-b border-[#e4e1da] bg-[#f7f6f2]">

                        <tr>

                            <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-[#737d79]">
                                Pengguna
                            </th>

                            <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-[#737d79]">
                                Peran
                            </th>

                            <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-[#737d79]">
                                Status
                            </th>

                            <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-[#737d79]">
                                Terdaftar
                            </th>

                            <th class="px-5 py-4 text-xs font-bold uppercase tracking-wide text-[#737d79]">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-[#ece9e3]">

                        @foreach($users as $user)

                            <tr class="transition hover:bg-[#fafaf8]">

                                {{-- User --}}
                                <td class="px-5 py-5">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center bg-[#e6f0ea] text-sm font-bold text-[#2f625b]">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>

                                        <div class="min-w-0">

                                            <p class="truncate font-bold text-[#263634]">
                                                {{ $user->name }}
                                            </p>

                                            <p class="mt-1 truncate text-xs text-[#7b8581]">
                                                {{ $user->email }}
                                            </p>

                                        </div>

                                    </div>

                                </td>


                                {{-- Role --}}
                                <td class="px-5 py-5">

                                    @if($user->role === 'ADMIN')

                                        <span class="inline-flex rounded-full bg-[#e9e6df] px-3 py-1 text-xs font-bold text-[#5e625f]">
                                            Admin
                                        </span>

                                    @elseif($user->role === 'STAFF')

                                        <span class="inline-flex rounded-full bg-[#f4e9dd] px-3 py-1 text-xs font-bold text-[#99633d]">
                                            Staff
                                        </span>

                                    @else

                                        <span class="inline-flex rounded-full bg-[#e6f0ea] px-3 py-1 text-xs font-bold text-[#426b5a]">
                                            User
                                        </span>

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td class="px-5 py-5">

                                    @if($user->status === 'ACTIVE')
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#426b5a]">
                                            <span class="h-1.5 w-1.5 rounded-full bg-[#426b5a]"></span>
                                            Aktif
                                        </span>
                                    @elseif($user->status === 'PENDING')
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#99633d]">
                                            <span class="h-1.5 w-1.5 rounded-full bg-[#99633d]"></span>
                                            Menunggu Verifikasi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#a65f3e]">
                                            <span class="h-1.5 w-1.5 rounded-full bg-[#a65f3e]"></span>
                                            Ditolak
                                        </span>
                                    @endif

                                </td>


                                {{-- Date --}}
                                <td class="px-5 py-5">

                                    <span class="text-sm text-[#596460]">
                                        {{ $user->created_at?->format('d M Y') ?? '-' }}
                                    </span>

                                </td>


                                {{-- Action --}}
                                <td class="px-5 py-5">

                                    @if(auth()->id() !== $user->id)

                                        <form
                                            method="POST"
                                            action="{{ route('admin.users.destroy', $user) }}"
                                            onsubmit="return confirm('Nonaktifkan pengguna ini?')"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="text-sm font-semibold text-[#a65f3e] hover:underline"
                                            >
                                                Nonaktifkan
                                            </button>

                                        </form>

                                    @else

                                        <span class="text-xs text-[#9aa19e]">
                                            Akun saat ini
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Mobile Cards --}}
        <div class="space-y-4 md:hidden">

            @foreach($users as $user)

                <div class="border border-[#dedbd3] bg-white p-5">

                    <div class="flex items-start gap-3">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center bg-[#e6f0ea] text-sm font-bold text-[#2f625b]">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>

                        <div class="min-w-0 flex-1">

                            <p class="font-bold text-[#263634]">
                                {{ $user->name }}
                            </p>

                            <p class="mt-1 break-all text-xs text-[#7b8581]">
                                {{ $user->email }}
                            </p>

                        </div>

                    </div>


                    <div class="mt-5 grid grid-cols-2 gap-4 border-t border-[#eeeae4] pt-4">

                        <div>

                            <p class="text-[11px] font-semibold uppercase tracking-wide text-[#8a9490]">
                                Peran
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#43504d]">
                                {{ $user->role }}
                            </p>

                        </div>


                        <div>

                            <p class="text-[11px] font-semibold uppercase tracking-wide text-[#8a9490]">
                                Status
                            </p>

                            @if($user->status === 'ACTIVE')
                                <p class="mt-1 text-sm font-semibold text-[#426b5a]">
                                    Aktif
                                </p>
                            @elseif($user->status === 'PENDING')
                                <p class="mt-1 text-sm font-semibold text-[#99633d]">
                                    Menunggu Verifikasi
                                </p>
                            @else
                                <p class="mt-1 text-sm font-semibold text-[#a65f3e]">
                                    Ditolak
                                </p>
                            @endif

                        </div>


                        <div>

                            <p class="text-[11px] font-semibold uppercase tracking-wide text-[#8a9490]">
                                Terdaftar
                            </p>

                            <p class="mt-1 text-sm font-semibold text-[#43504d]">
                                {{ $user->created_at?->format('d M Y') ?? '-' }}
                            </p>

                        </div>

                    </div>


                    @if(auth()->id() !== $user->id)

                        <div class="mt-5 border-t border-[#eeeae4] pt-4">

                            <form
                                method="POST"
                                action="{{ route('admin.users.destroy', $user) }}"
                                onsubmit="return confirm('Nonaktifkan pengguna ini?')"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="text-sm font-semibold text-[#a65f3e] hover:underline"
                                >
                                    Nonaktifkan Pengguna
                                </button>

                            </form>

                        </div>

                    @endif

                </div>

            @endforeach

        </div>


        {{-- Pagination --}}
        @if(method_exists($users, 'links'))

            <div class="mt-5">
                {{ $users->links() }}
            </div>

        @endif


    @else

        <div class="border border-[#dedbd3] bg-white px-6 py-14 text-center">

            <div class="mx-auto flex h-14 w-14 items-center justify-center bg-[#e6f0ea] text-2xl text-[#2f625b]">
                +
            </div>

            <h2 class="mt-5 text-lg font-bold text-[#263634]">
                Belum ada pengguna
            </h2>

            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-[#7a8581]">
                Belum ada akun pengguna yang tersedia di sistem.
            </p>

            <a
                href="{{ route('admin.users.create') }}"
                class="mt-6 inline-flex items-center justify-center rounded-lg bg-[#2f625b] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#244d48]"
            >
                Tambah Pengguna
            </a>

        </div>

    @endif

</div>

@endsection