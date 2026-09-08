@extends('layouts.app')

@section('title', 'Kelola Akun')

@section('content')

<div class="mb-8">

    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">

        <div>

            <p class="mb-2 text-sm font-medium text-[#b09b8c]">
                Administrator
            </p>

            <h1 class="text-2xl font-semibold text-[#3f4f63]">
                Kelola Akun
            </h1>

            <p class="mt-2 text-sm text-[#8b929b]">
                Kelola pengguna dan verifikasi akun yang baru terdaftar.
            </p>

        </div>


        <a
            href="{{ route('admin.users.create') }}"
            class="inline-flex items-center justify-center rounded-lg bg-[#d8c8bc] px-4 py-2.5 text-sm font-medium text-white hover:bg-[#c9b5a7]">
            + Tambah akun
        </a>

    </div>

</div>


{{-- Filter --}}
<div class="mb-6 flex flex-wrap gap-2">

    <a
        href="{{ route('admin.users.index') }}"
        class="rounded-full px-4 py-2 text-xs font-medium
        {{ empty($status) ? 'bg-[#d8c8bc] text-white' : 'bg-white text-[#6b7280] border border-[#e4dfda]' }}">
        Semua
    </a>


    <a
        href="{{ route('admin.users.index', ['status' => 'PENDING']) }}"
        class="rounded-full px-4 py-2 text-xs font-medium
        {{ ($status ?? '') === 'PENDING' ? 'bg-[#f7eee4] text-[#92745e]' : 'bg-white text-[#6b7280] border border-[#e4dfda]' }}">
        Pending
    </a>


    <a
        href="{{ route('admin.users.index', ['status' => 'ACTIVE']) }}"
        class="rounded-full px-4 py-2 text-xs font-medium
        {{ ($status ?? '') === 'ACTIVE' ? 'bg-[#e8f2e8] text-[#607560]' : 'bg-white text-[#6b7280] border border-[#e4dfda]' }}">
        Aktif
    </a>


    <a
        href="{{ route('admin.users.index', ['status' => 'REJECTED']) }}"
        class="rounded-full px-4 py-2 text-xs font-medium
        {{ ($status ?? '') === 'REJECTED' ? 'bg-[#f1eded] text-[#806f6f]' : 'bg-white text-[#6b7280] border border-[#e4dfda]' }}">
        Ditolak
    </a>

</div>


{{-- User Table --}}
<div class="overflow-hidden rounded-2xl border border-[#e9e3dd] bg-white shadow-sm">

    <div class="overflow-x-auto">

        <table class="w-full min-w-[700px] text-left">

            <thead class="border-b border-[#eeeae5] bg-[#faf9f7]">

                <tr>

                    <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wide text-[#9ca3ab]">
                        Nama
                    </th>

                    <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wide text-[#9ca3ab]">
                        Email
                    </th>

                    <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wide text-[#9ca3ab]">
                        Role
                    </th>

                    <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wide text-[#9ca3ab]">
                        Status
                    </th>

                    <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wide text-[#9ca3ab]">
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-[#f0ece8]">

                @forelse($users as $u)

                    <tr class="transition hover:bg-[#fdfcfb]">

                        <td class="px-5 py-4">

                            <p class="text-sm font-medium text-[#59636f]">
                                {{ $u->name }}
                            </p>

                        </td>


                        <td class="px-5 py-4">

                            <p class="text-sm text-[#7b8188]">
                                {{ $u->email }}
                            </p>

                        </td>


                        <td class="px-5 py-4">

                            <span class="rounded-full bg-[#eef1f4] px-3 py-1 text-xs font-medium text-[#66717c]">
                                {{ $u->role }}
                            </span>

                        </td>


                        <td class="px-5 py-4">

                            @if($u->status === 'PENDING')

                                <span class="rounded-full bg-[#f7eee4] px-3 py-1 text-xs font-medium text-[#92745e]">
                                    Menunggu
                                </span>

                            @elseif($u->status === 'ACTIVE')

                                <span class="rounded-full bg-[#e8f2e8] px-3 py-1 text-xs font-medium text-[#607560]">
                                    Aktif
                                </span>

                            @else

                                <span class="rounded-full bg-[#f1eded] px-3 py-1 text-xs font-medium text-[#806f6f]">
                                    Ditolak
                                </span>

                            @endif

                        </td>


                        <td class="px-5 py-4">

                            @if($u->status === 'PENDING')

                                <div class="flex gap-2">

                                    <form
                                        action="{{ route('admin.users.verify', $u) }}"
                                        method="POST">

                                        @csrf
                                        @method('PATCH')

                                        <input
                                            type="hidden"
                                            name="action"
                                            value="approve">

                                        <button
                                            type="submit"
                                            class="rounded-lg bg-[#e8f2e8] px-3 py-1.5 text-xs font-medium text-[#607560] hover:bg-[#dcebdc]">
                                            Verifikasi
                                        </button>

                                    </form>


                                    <form
                                        action="{{ route('admin.users.verify', $u) }}"
                                        method="POST">

                                        @csrf
                                        @method('PATCH')

                                        <input
                                            type="hidden"
                                            name="action"
                                            value="reject">

                                        <button
                                            type="submit"
                                            class="rounded-lg bg-[#f1eded] px-3 py-1.5 text-xs font-medium text-[#806f6f] hover:bg-[#eadfdf]">
                                            Tolak
                                        </button>

                                    </form>

                                </div>

                            @else

                                <span class="text-xs text-[#b0b4b9]">
                                    —
                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5"
                            class="px-5 py-12 text-center">

                            <p class="text-sm font-medium text-[#69727c]">
                                Belum ada akun
                            </p>

                            <p class="mt-1 text-xs text-[#9ca3ab]">
                                Data akun akan muncul di sini.
                            </p>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection