@extends('layouts.app')
@section('title', 'Admin Dashboard — BookNest')
@section('content')
    <div class="section-header"><h2>Admin Dashboard</h2></div>
    <div class="grid" style="grid-template-columns:repeat(auto-fill,minmax(160px,1fr))">
        <div class="book-card" style="padding:18px"><div style="font-size:26px;font-weight:700">{{ $stats['total_users'] }}</div><div style="color:var(--text-secondary);font-size:13px">Total Users</div></div>
        <div class="book-card" style="padding:18px"><div style="font-size:26px;font-weight:700">{{ $stats['total_books'] }}</div><div style="color:var(--text-secondary);font-size:13px">Total Books</div></div>
        <div class="book-card" style="padding:18px"><div style="font-size:26px;font-weight:700">{{ $stats['total_categories'] }}</div><div style="color:var(--text-secondary);font-size:13px">Categories</div></div>
        <div class="book-card" style="padding:18px"><div style="font-size:26px;font-weight:700">{{ $stats['available_books'] }}</div><div style="color:var(--text-secondary);font-size:13px">Available Books</div></div>
        <div class="book-card" style="padding:18px"><div style="font-size:26px;font-weight:700;color:var(--burgundy)">{{ $stats['low_availability'] }}</div><div style="color:var(--text-secondary);font-size:13px">Low Stock (≤2)</div></div>
    </div>

    <div class="section-header"><h2 style="font-size:18px">Books per category</h2></div>
    <table class="data-table">
        <thead><tr><th>Category</th><th>Books</th></tr></thead>
        <tbody>
        @foreach ($booksPerCategory as $cat)
            <tr><td>{{ $cat->name }}</td><td>{{ $cat->books_count }}</td></tr>
        @endforeach
        </tbody>
    </table>

@endsection
