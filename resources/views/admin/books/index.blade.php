@extends('layouts.app')
@section('title', 'Manage Books — BookNest')
@section('content')
    <div class="section-header">
        <h2>Books</h2>
        <a href="{{ route('admin.books.create') }}" class="btn btn-burgundy btn-sm">+ Add Book</a>
    </div>
    <table class="data-table">
        <thead><tr><th>Title</th><th>Author</th><th>Category</th><th>Copies</th><th></th></tr></thead>
        <tbody>
        @foreach ($books as $book)
            <tr>
                <td>{{ $book->title }}</td>
                <td>{{ $book->author }}</td>
                <td>{{ $book->category?->name }}</td>
                <td>{{ $book->available_copies }}</td>
                <td style="display:flex;gap:8px">
                    <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-outline btn-sm">Edit</a>
                    <form method="POST" action="{{ route('admin.books.destroy', $book) }}" onsubmit="return confirm('Delete this book?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline btn-sm" style="border-color:var(--burgundy);color:var(--burgundy)">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div style="margin-top:20px">{{ $books->links() }}</div>
@endsection
