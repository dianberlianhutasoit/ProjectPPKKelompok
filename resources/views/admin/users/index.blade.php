@extends('layouts.app')
@section('title', 'Kelola Akun')
@section('content')
<h2>Kelola Akun (Admin)</h2>
<p>
    <a href="{{ route('admin.users.create') }}"><button type="button">+ Daftarkan Staff / Pengguna</button></a>
</p>
<p>
    Filter:
    <a href="{{ route('admin.users.index') }}">Semua</a> |
    <a href="{{ route('admin.users.index', ['status' => 'PENDING']) }}">Pending</a> |
    <a href="{{ route('admin.users.index', ['status' => 'ACTIVE']) }}">Active</a> |
    <a href="{{ route('admin.users.index', ['status' => 'REJECTED']) }}">Rejected</a>
</p>
<table>
    <thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
    @forelse($users as $u)
        <tr>
            <td>{{ $u->name }}</td>
            <td>{{ $u->email }}</td>
            <td>{{ $u->role }}</td>
            <td><span class="badge {{ $u->status }}">{{ $u->status }}</span></td>
            <td>
                @if($u->status === 'PENDING')
                    <form action="{{ route('admin.users.verify', $u) }}" method="POST" style="display:inline">
                        @csrf @method('PATCH')
                        <input type="hidden" name="action" value="approve">
                        <button type="submit">Verifikasi</button>
                    </form>
                    <form action="{{ route('admin.users.verify', $u) }}" method="POST" style="display:inline">
                        @csrf @method('PATCH')
                        <input type="hidden" name="action" value="reject">
                        <button type="submit">Tolak</button>
                    </form>
                @else
                    -
                @endif
            </td>
        </tr>
    @empty
        <tr><td colspan="5">Belum ada akun.</td></tr>
    @endforelse
    </tbody>
</table>
@endsection
