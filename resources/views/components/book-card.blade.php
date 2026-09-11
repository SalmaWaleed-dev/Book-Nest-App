@props(['book', 'score' => null])
<div class="book-card">
    <a href="{{ route('books.show', $book) }}">
        <img src="{{ $book->coverUrl() }}" alt="{{ $book->title }}" loading="lazy">
    </a>
    <div class="body">
        @if ($book->category)
            <span class="badge">{{ $book->category->name }}</span>
        @endif
        <div class="title">{{ $book->title }}</div>
        <div class="author">{{ $book->author ?: 'Unknown author' }}</div>
        <div class="card-price">{{ number_format((float)$book->price, 2) }} EGP</div>
        @if (!is_null($score))
            <div class="score">{{ $score }}% match</div>
        @endif
        <a href="{{ route('books.show', $book) }}" class="btn btn-burgundy btn-sm" style="margin-top:6px">View Details</a>
    </div>
</div>
