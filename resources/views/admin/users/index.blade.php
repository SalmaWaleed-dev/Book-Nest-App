@extends('layouts.app')
@section('title', 'Manage Users — BookNest')
@section('content')
    <div class="section-header"><h2>Users</h2></div>
    <table class="data-table">
        <thead><tr><th>Name</th><th>Email</th><th>Role</th><th></th></tr></thead>
        <tbody>
        @foreach ($users as $u)
            <tr>
                <td>{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->role }}</td>
                <td style="display:flex;gap:8px">
                    <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-outline btn-sm">Edit</a>
                    @if ($u->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Delete this user?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline btn-sm" style="border-color:var(--burgundy);color:var(--burgundy)">Delete</button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div style="margin-top:20px">{{ $users->links() }}</div>
@endsection
