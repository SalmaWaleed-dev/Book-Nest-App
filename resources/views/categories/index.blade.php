@extends('layouts.app')
@section('title', 'Categories — BookNest')
@section('content')
    <div class="section-header"><h2>Categories</h2></div>
    <div class="grid">
        @foreach ($categories as $cat)
            <a href="{{ route('books.index', ['category' => $cat->id]) }}" class="book-card" style="padding:20px;align-items:center;text-align:center">
                <div class="title font-serif" style="font-size:17px">{{ $cat->name }}</div>
                <div style="color:var(--text-muted);font-size:13px">{{ $cat->books_count }} books</div>
            </a>
        @endforeach
    </div>
@endsection
