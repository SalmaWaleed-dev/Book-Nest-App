@extends('layouts.app')
@section('title', 'Browse Books — BookNest')
@section('content')
    <div class="section-header"><h2>Books</h2></div>

    <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:20px">
        <input type="text" name="q" placeholder="Search title, author, ISBN..." value="{{ request('q') }}" style="padding:10px;border:1px solid var(--border);border-radius:8px;flex:1;min-width:200px">
        <select name="category" style="padding:10px;border:1px solid var(--border);border-radius:8px">
            <option value="">All categories</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
        <input type="text" name="author" placeholder="Author" value="{{ request('author') }}" style="padding:10px;border:1px solid var(--border);border-radius:8px">
        <label style="display:flex;align-items:center;gap:6px;font-size:13px">
            <input type="checkbox" name="available_only" value="1" @checked(request('available_only'))> Available only
        </label>
        <button class="btn btn-burgundy btn-sm">Filter</button>
    </form>

    <div class="grid">
        @forelse ($books as $book)
            <x-book-card :book="$book" />
        @empty
            <p>No books matched your search.</p>
        @endforelse
    </div>

    <div style="margin-top:24px">{{ $books->links() }}</div>
@endsection
