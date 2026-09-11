<?php

namespace App\Providers;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use App\Policies\BookPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Explicit registration (belt-and-suspenders alongside Laravel's
        // {Model}Policy auto-discovery convention) so authorization works
        // even if that convention ever changes.
        Gate::policy(Book::class, BookPolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Paginator::defaultView('vendor.pagination.booknest');
    }
}
