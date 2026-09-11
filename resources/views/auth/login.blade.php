@extends('layouts.app')
@section('title', 'Login — BookNest')
@section('content')
    <div class="form-card">
        <h2 class="font-serif" style="text-align:center">Welcome back</h2>
        @if ($errors->any())
            <div class="error-text">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group"><label>Email</label><input type="email" name="email" value="{{ old('email') }}" required></div>
            <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
            <label style="display:flex;align-items:center;gap:6px;font-size:13px;margin-bottom:16px">
                <input type="checkbox" name="remember"> Remember me
            </label>
            <button class="btn btn-burgundy" style="width:100%">Log In</button>
        </form>
        <p style="text-align:center;margin-top:16px;font-size:13px">No account? <a href="{{ route('register') }}" style="color:var(--green);font-weight:600">Register</a></p>
    </div>
@endsection
