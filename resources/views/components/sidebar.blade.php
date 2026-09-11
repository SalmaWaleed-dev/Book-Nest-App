<aside class="sidebar" id="sidebar">
    <div class="sidebar-links">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">🏠 Home</a>
        <a href="{{ route('books.index') }}" class="{{ request()->routeIs('books.index') ? 'active' : '' }}">📚 Books</a>
        <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.index') ? 'active' : '' }}">🗂️ Categories</a>
        <a href="{{ route('books.index') }}">🔍 Search</a>
        @auth
            <a href="{{ route('home') }}#recommendations">✨ Recommendations</a>
            <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">👤 My Profile</a>
        @endauth

        @auth
            @if (auth()->user()->isAdmin())
                <hr>
                <div class="group-label">Admin</div>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">📊 Dashboard</a>
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">👥 Users</a>
                <a href="{{ route('admin.books.index') }}" class="{{ request()->routeIs('admin.books.*') ? 'active' : '' }}">📚 Books</a>
                <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">🗂️ Categories</a>
                <a href="{{ route('admin.statistics') }}" class="{{ request()->routeIs('admin.statistics') ? 'active' : '' }}">📈 Statistics</a>
            @endif
        @endauth
    </div>

    <div class="sidebar-bottom">
        <hr>
        <img src="{{ asset('images/branding/sidebar-logo.png') }}" alt="">
        <div class="quote">"A good book is a new adventure"</div>
    </div>
</aside>
