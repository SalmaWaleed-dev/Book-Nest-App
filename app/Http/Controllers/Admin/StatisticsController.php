<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\User;

class StatisticsController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_books' => Book::count(),
            'total_categories' => Category::count(),
            'available_books' => Book::where('available_copies', '>', 0)->count(),
        ];

        $perCategory = Category::withCount('books')->orderByDesc('books_count')->get();
        $lowAvailability = Book::where('available_copies', '<=', 2)->orderBy('available_copies')->get();

        return view('admin.statistics', compact('stats', 'perCategory', 'lowAvailability'));
    }
}
