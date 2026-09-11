<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Category::class);
        $categories = Category::withCount('books')->orderBy('name')->get();

        return view('admin.categories.index', compact('categories'));
    }
}
