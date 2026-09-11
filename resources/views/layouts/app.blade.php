<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BookNest — Read • Learn • Grow')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Inter:wght@400;500;600;700&family=Caveat:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header class="navbar">
        <div class="brand">
            <button id="sidebar-toggle" aria-label="Toggle menu" style="background:none;border:none;color:#fff;font-size:18px;cursor:pointer;display:none">&#9776;</button>
            <img src="{{ asset('images/branding/navbar-logo.png') }}" alt="BookNest">
            <div>
                <strong>BookNest</strong>
                <span class="tagline">Read • Learn • Grow</span>
            </div>
        </div>
        <nav>
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('books.index') }}" class="{{ request()->routeIs('books.*') ? 'active' : '' }}">Books</a>
            <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">Categories</a>
            @auth
                <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">Recommendations</a>
            @endauth
        </nav>
        <div class="right">
            <form action="{{ route('books.index') }}" method="GET" class="search">
                <input type="text" name="q" placeholder="Search for books, authors, categories..." value="{{ request('q') }}">
            </form>
            @auth
                <a href="{{ route('profile.edit') }}" class="avatar" title="{{ auth()->user()->name }}">@if(auth()->user()->profile?->avatar)<img src="{{ asset(auth()->user()->profile->avatar) }}" alt="{{ auth()->user()->name }}">@else{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}@endif</a>
                <a href="{{ route('cart.index') }}" class="cart-link" aria-label="Shopping cart">🛒<span class="cart-count">{{ collect(session('cart', []))->sum('quantity') }}</span></a>
                <form action="{{ route('logout') }}" method="POST">@csrf<button class="btn btn-outline btn-sm" style="border-color:#fff;color:#fff">Logout</button></form>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline btn-sm" style="border-color:#fff;color:#fff">Login</a>
            @endauth
        </div>
    </header>

    <div class="app-shell">
        @include('components.sidebar')
        <main class="content">
            @if (session('status'))
                <div class="status-banner">{{ session('status') }}</div>
            @endif
            @yield('content')
        </main>
    </div>

    @include('components.footer')
    @include('components.ai-assistant')
    <script src="{{ asset('js/app.js') }}?v=20260911"></script>
</body>
</html>
