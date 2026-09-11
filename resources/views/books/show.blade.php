@extends('layouts.app')
@section('title', $book->title . ' — BookNest')
@section('content')
    <div style="display:grid;grid-template-columns:280px 1fr;gap:32px">
        <img src="{{ $book->coverUrl() }}" alt="{{ $book->title }}" style="width:100%;border-radius:12px;aspect-ratio:2/3;object-fit:cover">
        <div>
            @if ($book->category)
                <span class="badge" style="color:var(--green)">{{ $book->category->name }}</span>
            @endif
            <h1 class="font-serif" style="margin:8px 0">{{ $book->title }}</h1>
            <p style="color:var(--text-secondary)">by {{ $book->author ?: 'Unknown author' }}</p>
            <div class="book-price">{{ number_format((float)$book->price, 2) }} EGP</div>
            <p>{{ $book->description ?: 'No description on file yet.' }}</p>
            <ul style="color:var(--text-secondary);font-size:14px;line-height:1.9;list-style:none;padding:0">
                <li><strong>ISBN:</strong> {{ $book->isbn ?: '—' }}</li>
                <li><strong>Published:</strong> {{ optional($book->publication_date)->format('Y-m-d') ?: '—' }}</li>
                <li><strong>Available copies:</strong> {{ $book->available_copies }}</li>
                @if ($book->rating)
                    <li><strong>Rating:</strong> {{ $book->rating }} ({{ $book->ratings_count }} ratings)</li>
                @endif
            </ul>
            <div class="book-actions">
                @auth
                    <form method="POST" action="{{ route('cart.add', $book) }}">@csrf<input type="hidden" name="type" value="buy"><button class="btn btn-burgundy">Buy</button></form>
                    <form method="POST" action="{{ route('cart.add', $book) }}">@csrf<input type="hidden" name="type" value="borrow"><button class="btn btn-green" @disabled($book->available_copies < 1)>Borrow Book</button></form>
                @endauth
                <button type="button" class="btn btn-outline" data-ai-book="{{ $book->id }}">Ask the Assistant</button>
            </div>
        </div>
    </div>

    <section class="offer-section">
        <div class="section-header"><div><h2>Special Bundle Offer</h2><p class="section-subtitle">Get 3 books for less than buying them separately.</p></div><span class="offer-badge">15% OFF</span></div>
        <div class="bundle-card">
            <div class="bundle-books">
                @foreach($bundle as $bundleBook)<a href="{{ route('books.show', $bundleBook) }}" class="bundle-book"><img src="{{ $bundleBook->coverUrl() }}" alt="{{ $bundleBook->title }}"><span>{{ $bundleBook->title }}</span></a>@endforeach
            </div>
            <div class="bundle-summary"><span class="old-price">{{ number_format($bundleTotal, 2) }} EGP</span><strong>{{ number_format($bundlePrice, 2) }} EGP</strong><span>Save {{ number_format($bundleTotal - $bundlePrice, 2) }} EGP</span>
                @auth
                    <form method="POST" action="{{ route('cart.bundle') }}">
                        @csrf
                        @foreach($bundle as $bundleBook)
                            <input type="hidden" name="book_ids[]" value="{{ $bundleBook->id }}">
                        @endforeach
                        <button class="btn btn-burgundy">Add Bundle to Cart</button>
                    </form>
                @endauth
            </div>
        </div>
    </section>

    <section>
        <div class="section-header"><div><h2>Similar Books</h2><p class="section-subtitle">More books from the same category.</p></div><a href="{{ route('books.index', ['category' => $book->category_id]) }}" class="view-all">View All <span>→</span></a></div>
        <div class="grid">@forelse($related->take(4) as $relatedBook)<x-book-card :book="$relatedBook" />@empty<p>No similar books found yet.</p>@endforelse</div>
    </section>
@endsection
