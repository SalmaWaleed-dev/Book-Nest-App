@extends('layouts.app')
@section('title', 'My Cart — BookNest')
@section('content')
    <div class="section-header">
        <div><h2>My Cart</h2><p class="section-subtitle">Books you've selected to buy or borrow.</p></div>
        @if($items->isNotEmpty())
            <form method="POST" action="{{ route('cart.clear') }}">@csrf<button class="btn btn-outline btn-sm">Clear Cart</button></form>
        @endif
    </div>

    @if($items->isEmpty())
        <div class="empty-state"><div class="empty-icon">🛒</div><h3>Your cart is empty</h3><p>Browse the catalog and add a book to buy or borrow.</p><a href="{{ route('books.index') }}" class="btn btn-burgundy">Browse Books</a></div>
    @else
        <div class="cart-layout">
            <div class="cart-list">
                @foreach($items as $item)
                    @if($item['type'] === 'bundle')
                        <article class="cart-item bundle-cart-item">
                            <div class="bundle-cart-covers">
                                @foreach($item['books'] as $bundleBook)
                                    <img src="{{ $bundleBook->coverUrl() }}" alt="{{ $bundleBook->title }}">
                                @endforeach
                            </div>
                            <div class="cart-item-main"><span class="badge">Bundle · 15% OFF</span><h3>3-Book Special Bundle</h3><p>{{ $item['books']->pluck('title')->implode(' · ') }}</p><div class="cart-meta">Bundle price is lower than the individual book prices.</div></div>
                            <div class="cart-item-side"><strong>{{ number_format($item['subtotal'], 2) }} EGP</strong></div>
                        </article>
                    @else
                    <article class="cart-item">
                        <img src="{{ $item['book']->coverUrl() }}" alt="{{ $item['book']->title }}">
                        <div class="cart-item-main">
                            <span class="badge">{{ ucfirst($item['type']) }}</span>
                            <h3>{{ $item['book']->title }}</h3>
                            <p>{{ $item['book']->author ?: 'Unknown author' }}</p>
                            <div class="cart-meta">Quantity: {{ $item['quantity'] }} · {{ $item['type'] === 'buy' ? number_format((float)$item['book']->price, 2) . ' EGP each' : 'Borrow — no purchase price' }}</div>
                        </div>
                        <div class="cart-item-side">
                            @if($item['type'] === 'buy')
                                <strong>{{ number_format($item['subtotal'], 2) }} EGP</strong>
                            @else
                                <strong>Borrow</strong>
                            @endif
                            <form method="POST" action="{{ route('cart.remove', [$item['book'], $item['type']]) }}">@csrf @method('DELETE')<button class="text-button">Remove</button></form>
                        </div>
                    </article>
                    @endif
                @endforeach
            </div>
            <aside class="cart-summary">
                <h3>Order Summary</h3>
                <div><span>Items</span><strong>{{ $items->sum('quantity') }}</strong></div>
                <div><span>Purchase total</span><strong>{{ number_format($total, 2) }} EGP</strong></div>
                <div class="summary-note">Borrowed books are handled separately and do not add to the purchase total.</div>
                <form method="POST" action="{{ route('cart.checkout') }}">@csrf<button class="btn btn-burgundy cart-checkout">Continue</button></form>
            </aside>
        </div>
    @endif
@endsection
