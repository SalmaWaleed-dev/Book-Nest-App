@extends('layouts.app')
@section('title', 'Statistics — BookNest')
@section('content')
    <div class="section-header"><h2>Library Statistics</h2></div>
    <table class="data-table" style="margin-bottom:24px">
        <tbody>
            <tr><td>Total users</td><td>{{ $stats['total_users'] }}</td></tr>
            <tr><td>Total books</td><td>{{ $stats['total_books'] }}</td></tr>
            <tr><td>Total categories</td><td>{{ $stats['total_categories'] }}</td></tr>
            <tr><td>Available books</td><td>{{ $stats['available_books'] }}</td></tr>
        </tbody>
    </table>

    <h3 class="font-serif">Books per category</h3>
    <table class="data-table" style="margin-bottom:24px">
        <thead><tr><th>Category</th><th>Books</th></tr></thead>
        <tbody>
        @foreach ($perCategory as $cat)
            <tr><td>{{ $cat->name }}</td><td>{{ $cat->books_count }}</td></tr>
        @endforeach
        </tbody>
    </table>

    <h3 class="font-serif">Low availability</h3>
    <table class="data-table">
        <thead><tr><th>Title</th><th>Copies left</th></tr></thead>
        <tbody>
        @foreach ($lowAvailability as $book)
            <tr><td>{{ $book->title }}</td><td>{{ $book->available_copies }}</td></tr>
        @endforeach
        </tbody>
    </table>
@endsection
