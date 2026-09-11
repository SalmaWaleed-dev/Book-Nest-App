<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;

// Listing only — create/edit/store/update/destroy live on the shared
// App\Http\Controllers\BookController so the same authorization/validation
// path is used whether the form posts to /admin/books or /books.
class BookController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Book::class);
        $books = Book::with('category')->orderBy('title')->paginate(15);

        return view('admin.books.index', compact('books'));
    }
}
