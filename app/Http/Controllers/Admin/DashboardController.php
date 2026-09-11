<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\User;

// Route already protected by ['auth','admin'] middleware group — see routes/web.php.
class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_books' => Book::count(),
            'total_categories' => Category::count(),
            'available_books' => Book::where('available_copies', '>', 0)->count(),
            'low_availability' => Book::where('available_copies', '<=', 2)->count(),
        ];

        $booksPerCategory = Category::withCount('books')->orderByDesc('books_count')->get();

        return view('admin.dashboard', compact('stats', 'booksPerCategory'));
    }
}
