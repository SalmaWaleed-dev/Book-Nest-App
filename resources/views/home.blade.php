@extends('layouts.app')
@section('title', 'BookNest — Discover Your Next Great Book')
@section('content')
    <section class="hero">
        <div>
            <div class="eyebrow">WELCOME TO BOOKNEST</div>
            <h1>Discover Your Next<br>Great Book</h1>
            <p>Explore thousands of books, get personalized recommendations, and let our AI assistant help you find exactly what you need.</p>
            <div class="actions">
                <a href="{{ route('books.index') }}" class="btn btn-burgundy">Browse Books →</a>
                <a href="{{ route('categories.index') }}" class="btn btn-outline">Explore Categories</a>
            </div>
        </div>
        <div class="image-wrap">
            <img src="{{ asset('images/hero/hero-image.png') }}" alt="BookNest hero">
            <div class="handwritten">
                <span>More</span>
                <span>Books</span>
                <span>More</span>
                <span>Possibilities</span>
            </div>
        </div>
    </section>

    <section class="features">
        <div class="feature"><div class="icon">📚</div><h3>Wide Selection</h3><p>Hundreds of curated titles across genres.</p></div>
        <div class="feature"><div class="icon">✨</div><h3>Personalized</h3><p>Recommendations built from your profile.</p></div>
        <div class="feature"><div class="icon">🔒</div><h3>Secure &amp; Reliable</h3><p>Role-based access on every page.</p></div>
        <div class="feature"><div class="icon">🤖</div><h3>AI Powered</h3><p>Ask, discover, and compare with AI.</p></div>
        <div class="feature"><div class="icon">❤️</div><h3>Book Lovers Community</h3><p>Built by readers, for readers.</p></div>
    </section>

    <section>
        <div class="section-header">
            <h2>Featured Books</h2>
            <a href="{{ route('books.index') }}" class="view-all">View All <span aria-hidden="true">→</span></a>
        </div>
        <div class="grid">
            @forelse ($featured as $book)
                <x-book-card :book="$book" />
            @empty
                <p>No books yet — check back soon.</p>
            @endforelse
        </div>
    </section>

    <section id="recommendations">
        <div class="section-header">
            <h2>Personalized Recommendations</h2>
        </div>
        <p style="color:var(--text-secondary);margin-top:-10px">Based on your interests</p>
        <div class="grid">
            @forelse ($recommendations as $row)
                <x-book-card :book="$row['book']" :score="$row['score']" />
            @empty
                @auth
                    <p>Add some interests to your <a href="{{ route('profile.edit') }}" style="color:var(--green);font-weight:600">profile</a> to get personalized picks.</p>
                @else
                    <p><a href="{{ route('login') }}" style="color:var(--green);font-weight:600">Log in</a> to see recommendations tailored to you.</p>
                @endauth
            @endforelse
        </div>
    </section>
@endsection
