@extends('layouts.app')
@section('title', 'Edit User — BookNest')
@section('content')
    <div class="form-card">
        <h2 class="font-serif">Edit {{ $user->name }}</h2>
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf @method('PUT')
            <div class="form-group"><label>Name</label><input type="text" name="name" value="{{ old('name', $user->name) }}" required></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" value="{{ old('email', $user->email) }}" required></div>
            <div class="form-group"><label>New password (leave blank to keep current)</label><input type="password" name="password"></div>
            <div class="form-group">
                <label>Role</label>
                <select name="role" @disabled($user->id === auth()->id())>
                    <option value="user" @selected($user->role === 'user')>user</option>
                    <option value="admin" @selected($user->role === 'admin')>admin</option>
                </select>
                @if ($user->id === auth()->id())
                    <div style="font-size:12px;color:var(--text-muted);margin-top:4px">You cannot change your own role (prevents a zero-admin lockout).</div>
                @endif
            </div>
            <button class="btn btn-burgundy">Save</button>
        </form>
    </div>
@endsection
