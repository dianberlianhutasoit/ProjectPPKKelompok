@extends('layouts.app')

@section('title', 'Kelola Pengguna - ReserVa')

@section('content')

    <div class="mx-auto max-w-7xl">

        <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

            <div>

                <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#2f625b]">
                    Administration
                </p>

                <h1 class="mt-2 text-4xl font-bold tracking-tight text-[#263634]">
                    Kelola Akun
                </h1>

                <p class="mt-2 text-base leading-6 text-[#68736f]">
                    Tambahkan, periksa, dan kelola akun pengguna sistem.
                </p>

            </div>

            <a href="{{ route('admin.users.create') }}"
                class="inline-flex w-fit items-center justify-center rounded-lg bg-[#2f625b] px-5 py-2.5 text-base font-semibold text-white transition hover:bg-[#244d48]">
                + Tambah Pengguna
            </a>

        </div>

        @if (session('success'))
            <div class="mb-5 border border-[#cfe0d7] bg-[#edf5f0] px-4 py-3 text-sm font-medium text-[#426b5a]">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-5 border border-[#e6d0c5] bg-[#fbf0eb] px-4 py-3 text-sm font-medium text-[#a65f3e]">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-5 border border-[#e6d0c5] bg-[#fbf0eb] px-4 py-3 text-sm font-medium text-[#a65f3e]">
                {{ $errors->first() }}
            </div>
        @endif

        @if ($users->count())

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

                            @foreach ($users as $user)
                                <tr class="transition hover:bg-[#fafaf8]">

                                    <td class="px-5 py-5">

                                        <div class="flex items-center gap-3">

                                            <div
                                                class="flex h-10 w-10 shrink-0 items-center justify-center bg-[#e6f0ea] text-sm font-bold text-[#2f625b]">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>

                                            <div class="min-w-0">

                                                <p class="truncate text-base font-bold text-[#263634]">
                                                    {{ $user->name }}
                                                </p>

                                                <p class="mt-1 truncate text-sm text-[#7b8581]">
                                                    {{ $user->email }}
                                                </p>

                                            </div>

                                        </div>

                                    </td>

                                    <td class="px-5 py-5">

                                        @if ($user->role === 'ADMIN')
                                            <span
                                                class="inline-flex rounded-full bg-[#e9e6df] px-3 py-1 text-xs font-bold text-[#5e625f]">
                                                Admin
                                            </span>
                                        @elseif($user->role === 'STAFF')
                                            <span
                                                class="inline-flex rounded-full bg-[#f4e9dd] px-3 py-1 text-xs font-bold text-[#99633d]">
                                                Staff
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex rounded-full bg-[#e6f0ea] px-3 py-1 text-xs font-bold text-[#426b5a]">
                                                User
                                            </span>
                                        @endif

                                    </td>

                                    <td class="px-5 py-5">

                                        @if ($user->status === 'ACTIVE')
                                            <span
                                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#426b5a]">

                                                <span class="h-1.5 w-1.5 rounded-full bg-[#426b5a]"></span>

                                                Aktif

                                            </span>
                                        @elseif($user->status === 'PENDING')
                                            <span
                                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#99633d]">

                                                <span class="h-1.5 w-1.5 rounded-full bg-[#99633d]"></span>

                                                Menunggu Verifikasi

                                            </span>
                                        @elseif($user->status === 'INACTIVE')
                                            <span
                                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#737a77]">

                                                <span class="h-1.5 w-1.5 rounded-full bg-[#737a77]"></span>

                                                Nonaktif

                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#a65f3e]">

                                                <span class="h-1.5 w-1.5 rounded-full bg-[#a65f3e]"></span>

                                                Ditolak

                                            </span>
                                        @endif

                                        @if ($user->status === 'REJECTED' && $user->rejection_reason)
                                            <p class="mt-2 text-xs italic leading-5 text-[#a65f3e]">
                                                Alasan: {{ $user->rejection_reason }}
                                            </p>
                                        @endif

                                    </td>

                                    <td class="px-5 py-5">

                                        <span class="text-sm text-[#596460]">
                                            {{ $user->created_at?->format('d M Y') ?? '-' }}
                                        </span>

                                    </td>

                                    <td class="px-5 py-5">

                                        @if (auth()->id() === $user->id)
                                            <span class="text-sm text-[#9aa19e]">
                                                Akun saat ini
                                            </span>
                                        @elseif($user->status === 'PENDING')
                                            <div class="flex items-center gap-3">

                                                <form method="POST" action="{{ route('admin.users.verify', $user) }}"
                                                    onsubmit="return confirm('Setujui pengguna ini?')">

                                                    @csrf
                                                    @method('PATCH')

                                                    <input type="hidden" name="action" value="approve">

                                                    <button type="submit"
                                                        class="text-sm font-semibold text-[#426b5a] hover:underline">
                                                        Setujui
                                                    </button>

                                                </form>

                                                <button type="button" onclick="openRejectReason({{ $user->id }})"
                                                    class="text-sm font-semibold text-[#a65f3e] hover:underline">
                                                    Tolak
                                                </button>

                                            </div>
                                        @elseif($user->status === 'ACTIVE')
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                                onsubmit="return confirm('Nonaktifkan pengguna ini?')">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="text-sm font-semibold text-[#a65f3e] hover:underline">
                                                    Nonaktifkan
                                                </button>

                                            </form>
                                        @else
                                            <span class="text-sm text-[#9aa19e]">
                                                —
                                            </span>
                                        @endif

                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

            <div class="space-y-4 md:hidden">

                @foreach ($users as $user)
                    <div class="border border-[#dedbd3] bg-white p-5">

                        <div class="flex items-start gap-3">

                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center bg-[#e6f0ea] text-sm font-bold text-[#2f625b]">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>

                            <div class="min-w-0 flex-1">

                                <p class="text-base font-bold text-[#263634]">
                                    {{ $user->name }}
                                </p>

                                <p class="mt-1 break-all text-sm text-[#7b8581]">
                                    {{ $user->email }}
                                </p>

                            </div>

                        </div>

                        <div class="mt-5 grid grid-cols-2 gap-4 border-t border-[#eeeae4] pt-4">

                            <div>

                                <p class="text-xs font-semibold uppercase tracking-wide text-[#8a9490]">
                                    Peran
                                </p>

                                <p class="mt-1 text-base font-semibold text-[#43504d]">
                                    {{ $user->role }}
                                </p>

                            </div>

                            <div>

                                <p class="text-xs font-semibold uppercase tracking-wide text-[#8a9490]">
                                    Status
                                </p>

                                @if ($user->status === 'ACTIVE')
                                    <p class="mt-1 text-base font-semibold text-[#426b5a]">
                                        Aktif
                                    </p>
                                @elseif($user->status === 'PENDING')
                                    <p class="mt-1 text-base font-semibold text-[#99633d]">
                                        Menunggu Verifikasi
                                    </p>
                                @elseif($user->status === 'INACTIVE')
                                    <p class="mt-1 text-base font-semibold text-[#737a77]">
                                        Nonaktif
                                    </p>
                                @else
                                    <p class="mt-1 text-base font-semibold text-[#a65f3e]">
                                        Ditolak
                                    </p>

                                    @if ($user->rejection_reason)
                                        <p class="mt-1 text-sm italic leading-5 text-[#a65f3e]">
                                            Alasan: {{ $user->rejection_reason }}
                                        </p>
                                    @endif
                                @endif

                            </div>

                            <div>

                                <p class="text-xs font-semibold uppercase tracking-wide text-[#8a9490]">
                                    Terdaftar
                                </p>

                                <p class="mt-1 text-base font-semibold text-[#43504d]">
                                    {{ $user->created_at?->format('d M Y') ?? '-' }}
                                </p>

                            </div>

                        </div>

                        @if (auth()->id() !== $user->id && in_array($user->status, ['PENDING', 'ACTIVE']))
                            <div class="mt-5 border-t border-[#eeeae4] pt-4">

                                @if ($user->status === 'PENDING')
                                    <div class="flex items-center gap-4">

                                        <form method="POST" action="{{ route('admin.users.verify', $user) }}"
                                            onsubmit="return confirm('Setujui pengguna ini?')">

                                            @csrf
                                            @method('PATCH')

                                            <input type="hidden" name="action" value="approve">

                                            <button type="submit"
                                                class="text-sm font-semibold text-[#426b5a] hover:underline">
                                                Setujui
                                            </button>

                                        </form>

                                        <button type="button" onclick="openRejectReason({{ $user->id }})"
                                            class="text-sm font-semibold text-[#a65f3e] hover:underline">
                                            Tolak
                                        </button>

                                    </div>
                                @else
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                        onsubmit="return confirm('Nonaktifkan pengguna ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="text-sm font-semibold text-[#a65f3e] hover:underline">
                                            Nonaktifkan Pengguna
                                        </button>

                                    </form>
                                @endif

                            </div>
                        @endif

                    </div>
                @endforeach

            </div>

            @if (method_exists($users, 'links'))
                <div class="mt-5">
                    {{ $users->links() }}
                </div>
            @endif
        @else
            <div class="border border-[#dedbd3] bg-white px-6 py-14 text-center">

                <div class="mx-auto flex h-14 w-14 items-center justify-center bg-[#e6f0ea] text-2xl text-[#2f625b]">
                    +
                </div>

                <h2 class="mt-5 text-2xl font-bold text-[#263634]">
                    Belum ada pengguna
                </h2>

                <p class="mx-auto mt-2 max-w-md text-base leading-6 text-[#7a8581]">
                    Belum ada akun pengguna yang tersedia di sistem.
                </p>

                <a href="{{ route('admin.users.create') }}"
                    class="mt-6 inline-flex items-center justify-center rounded-lg bg-[#2f625b] px-5 py-2.5 text-base font-semibold text-white transition hover:bg-[#244d48]">
                    Tambah Pengguna
                </a>

            </div>

        @endif

    </div>

    <div id="reject-reason-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4">
        <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl">

            <p class="text-xs font-bold uppercase tracking-[0.12em] text-[#a65f3e]">
                Alasan Penolakan
            </p>

            <h2 class="mt-2 text-2xl font-bold text-[#263634]">
                Masukkan alasan penolakan
            </h2>

            <p class="mt-2 text-base leading-6 text-[#68736f]">
                Berikan alasan agar pendaftar mengetahui mengapa akunnya ditolak.
            </p>

            <form id="reject-form" method="POST" class="mt-5">
                @csrf
                @method('PATCH')

                <input type="hidden" name="action" value="reject">

                <label for="rejection_reason" class="mb-2 block text-base font-semibold text-[#43504d]">
                    Alasan
                </label>

                <textarea id="rejection_reason" name="rejection_reason" rows="4" maxlength="1000" required
                    placeholder="Jelaskan alasan penolakan..."
                    class="w-full resize-none rounded-lg border border-[#d5d2ca] bg-[#fafaf8] px-4 py-3 text-base leading-6 text-[#263634] outline-none focus:border-[#2f625b] focus:bg-white focus:ring-4 focus:ring-[#2f625b]/10"></textarea>

                <div class="mt-5 flex justify-end gap-3">

                    <button type="button" onclick="closeRejectReason()"
                        class="rounded-lg border border-[#d5d2ca] bg-white px-4 py-2.5 text-base font-semibold text-[#596460] transition hover:bg-[#f1f0eb]">
                        Batal
                    </button>

                    <button type="submit"
                        class="rounded-lg bg-[#a65f3e] px-4 py-2.5 text-base font-semibold text-white transition hover:bg-[#8f4f34]">
                        Tolak Akun
                    </button>

                </div>
            </form>

        </div>
    </div>

    <script>
        function openRejectReason(id) {
            const modal = document.getElementById('reject-reason-modal');
            const form = document.getElementById('reject-form');
            const reasonInput = document.getElementById('rejection_reason');

            form.action = `/admin/users/${id}/verify`;
            reasonInput.value = '';

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                reasonInput.focus();
            }, 100);
        }

        function closeRejectReason() {
            const modal = document.getElementById('reject-reason-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>

@endsection