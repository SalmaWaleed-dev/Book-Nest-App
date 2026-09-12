@extends('layouts.app')

@section('title', 'Admin Dashboard — BookNest')

@section('content')

<div class="admin-dashboard">

    
        <!-- DASHBOARD HERO -->
    <section class="dashboard-hero">

        <div class="dashboard-hero-content">
            <span class="dashboard-eyebrow">
                Welcome back, Admin 👋
            </span>

            <h1>Admin Dashboard</h1>

            <p>
                Here's a quick overview of your bookstore.
            </p>
        </div>

        <div class="dashboard-hero-art">
            <img
                src="{{ asset('images/branding/dashboard-books.png') }}"
                alt="Books and plant"
            >
        </div>

    </section>


        <!-- STATISTICS CARDS -->

    <section class="dashboard-stats">

         <!-- Total Users  -->
        <div class="dashboard-stat-card stat-users">

            <div class="stat-icon">
                <span>♟</span>
            </div>

            <div class="stat-content">
                <strong>
                    {{ $stats['total_users'] }}
                </strong>

                <span>Total Users</span>

                <small>
                    <b>↑</b> Your community
                </small>
            </div>

        </div>


        <!--Total Books -->
        <div class="dashboard-stat-card stat-books">

            <div class="stat-icon">
                <span>📖</span>
            </div>

            <div class="stat-content">
                <strong>
                    {{ $stats['total_books'] }}
                </strong>

                <span>Total Books</span>

                <small>
                    <b>↑</b> Library collection
                </small>
            </div>

        </div>


        <!-- Categories  -->
        <div class="dashboard-stat-card stat-categories">

            <div class="stat-icon">
                <span>🏷</span>
            </div>

            <div class="stat-content">
                <strong>
                    {{ $stats['total_categories'] }}
                </strong>

                <span>Categories</span>

                <small>
                    <b>↑</b> Book categories
                </small>
            </div>

        </div>


         <!-- Available Books  -->
        <div class="dashboard-stat-card stat-available">

            <div class="stat-icon">
                <span>📚</span>
            </div>

            <div class="stat-content">
                <strong>
                    {{ $stats['available_books'] }}
                </strong>

                <span>Available Books</span>

                <small>
                    <b>↑</b> Ready to borrow
                </small>
            </div>

        </div>


         <!-- Low Stock  -->
        <div class="dashboard-stat-card stat-low">

            <div class="stat-icon">
                <span>⚠</span>
            </div>

            <div class="stat-content">
                <strong>
                    {{ $stats['low_availability'] }}
                </strong>

                <span>Low Stock (≤2)</span>

                <small>
                    <b>↓</b> Needs attention
                </small>
            </div>

        </div>

    </section>


        <!-- MAIN DASHBOARD AREA -->
    
    <section class="dashboard-main-grid">



            <!-- BOOKS PER CATEGORY -->

        <div class="dashboard-category-card">

            <div class="dashboard-card-header">

                <div class="dashboard-card-title">

                    <div class="dashboard-title-icon">
                        📊
                    </div>

                    <div>
                        <h2>Books per category</h2>
                        <p>Distribution of books across your library</p>
                    </div>

                </div>

                <a
                    href="{{ route('admin.books.index') }}"
                    class="dashboard-view-all"
                >
                    View All
                    <span>→</span>
                </a>

            </div>


            <div class="category-list">

                @php
                    $maxBooks = $booksPerCategory->max('books_count') ?: 1;
                @endphp

                @forelse ($booksPerCategory as $index => $cat)

                    @php
                        $percentage = ($cat->books_count / $maxBooks) * 100;

                        $categoryIcons = [
                            '📖',
                            '♥',
                            '☆',
                            '♟',
                            '</>',
                            '📚',
                            '⚙',
                            '◎',
                            '◈',
                            '✦'
                        ];

                        $icon = $categoryIcons[$index % count($categoryIcons)];
                    @endphp

                    <div class="category-row">

                        <div class="category-rank">
                            {{ $index + 1 }}
                        </div>

                        <div class="category-icon">
                            {{ $icon }}
                        </div>

                        <div class="category-name">
                            {{ $cat->name }}
                        </div>

                        <div class="category-progress">

                            <div class="category-progress-track">
                                <div
                                    class="category-progress-bar category-color-{{ $index % 10 }}"
                                    style="width: {{ $percentage }}%"
                                ></div>
                            </div>

                        </div>

                        <div class="category-count">
                            {{ $cat->books_count }}
                        </div>

                    </div>

                @empty

                    <div class="dashboard-empty">
                        No categories available yet.
                    </div>

                @endforelse

            </div>

        </div>



            <!-- QUICK ACTIONS -->

        <div class="dashboard-side-column">

            <div class="quick-actions-card">

                <div class="quick-actions-header">

                    <div class="quick-actions-icon">
                        ♧
                    </div>

                    <div>
                        <h2>Quick Actions</h2>
                        <p>Manage your bookstore with ease.</p>
                    </div>

                </div>


                <div class="quick-actions-list">

                    <a
                        href="{{ route('admin.books.create') }}"
                        class="quick-action primary"
                    >
                        <span class="quick-action-left">
                            <span class="quick-action-symbol">+</span>
                            <span>Add New Book</span>
                        </span>

                        <span class="quick-action-arrow">›</span>
                    </a>


                    <a
                        href="{{ route('admin.categories.create') }}"
                        class="quick-action"
                    >
                        <span class="quick-action-left">
                            <span class="quick-action-symbol">□</span>
                            <span>Add Category</span>
                        </span>

                        <span class="quick-action-arrow">›</span>
                    </a>


                    <a
                        href="{{ route('admin.users.index') }}"
                        class="quick-action"
                    >
                        <span class="quick-action-left">
                            <span class="quick-action-symbol">♟</span>
                            <span>Manage Users</span>
                        </span>

                        <span class="quick-action-arrow">›</span>
                    </a>


                    <a
                        href="{{ route('admin.statistics') }}"
                        class="quick-action"
                    >
                        <span class="quick-action-left">
                            <span class="quick-action-symbol">▥</span>
                            <span>View Reports</span>
                        </span>

                        <span class="quick-action-arrow">›</span>
                    </a>

                </div>

            </div>



                <!-- QUOTE CARD -->

            <div class="dashboard-quote-card">

                <div class="quote-content">

                    <span class="quote-mark">“</span>

                    <p>
                        Books are a uniquely portable magic.
                    </p>

                    <span class="quote-author">
                        — Stephen King
                    </span>

                </div>

                <div class="quote-decoration">
                    📖
                </div>

            </div>

        </div>

    </section>

</div>

@endsection