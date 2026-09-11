@extends('layouts.app')
@section('title', 'Register — BookNest')
@section('content')
    <div class="form-card">
        <h2 class="font-serif" style="text-align:center">Create your account</h2>
        @if ($errors->any())
            <div class="error-text">{{ $errors->first() }}</div>
        @endif
        {{-- No role field here on purpose — every public registration becomes role=user server-side. --}}
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group"><label>Name</label><input type="text" name="name" value="{{ old('name') }}" required></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" value="{{ old('email') }}" required></div>
            <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
            <div class="form-group"><label>Confirm password</label><input type="password" name="password_confirmation" required></div>
            <button class="btn btn-burgundy" style="width:100%">Register</button>
        </form>
        <p style="text-align:center;margin-top:16px;font-size:13px">Already have an account? <a href="{{ route('login') }}" style="color:var(--green);font-weight:600">Log in</a></p>
    </div>
@endsection
