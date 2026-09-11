@extends('layouts.app')
@section('title', 'Manage Categories — BookNest')
@section('content')
    <div class="section-header">
        <h2>Categories</h2>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-burgundy btn-sm">+ Add Category</a>
    </div>
    <table class="data-table">
        <thead><tr><th>Name</th><th>Books</th><th></th></tr></thead>
        <tbody>
        @foreach ($categories as $cat)
            <tr>
                <td>{{ $cat->name }}</td>
                <td>{{ $cat->books_count }}</td>
                <td style="display:flex;gap:8px">
                    <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-outline btn-sm">Edit</a>
                    <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('Delete this category? Its books will become Uncategorized.')">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline btn-sm" style="border-color:var(--burgundy);color:var(--burgundy)">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
