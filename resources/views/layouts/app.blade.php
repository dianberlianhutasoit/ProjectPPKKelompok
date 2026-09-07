<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Campus Facility System')</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f8; margin: 0; }
        .nav { background: #1b3a5c; color: #fff; padding: 12px 20px; display: flex; justify-content: space-between; align-items: center; }
        .nav a { color: #fff; margin-left: 12px; text-decoration: none; }
        .container { max-width: 900px; margin: 24px auto; background: #fff; padding: 24px; border-radius: 8px; }
        .alert-success { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 12px; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 12px; }
        label { display: block; margin-top: 12px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; margin-top: 4px; box-sizing: border-box; }
        button { margin-top: 16px; padding: 10px 16px; background: #1b3a5c; color: #fff; border: 0; border-radius: 4px; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #eef2f6; }
        .badge { padding: 2px 8px; border-radius: 12px; font-size: 12px; }
        .PENDING { background: #fff3cd; } .ACTIVE { background: #d4edda; } .REJECTED { background: #f8d7da; }
    </style>
</head>
<body>
<div class="nav">
    <div><a href="{{ route('facilities.index') }}">Campus Facility</a></div>
    <div>
        @auth
            <span>{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
            @if(auth()->user()->role === 'ADMIN')
                <a href="{{ route('admin.users.index') }}">Kelola Akun</a>
            @endif
            <form action="{{ route('logout') }}" method="POST" style="display:inline">
                @csrf
                <button type="submit" style="margin:0 0 0 12px;">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a>
        @endauth
    </div>
</div>
<div class="container">
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert-error">
            <ul style="margin:0;padding-left:18px">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif
    @yield('content')
</div>
</body>
</html>
