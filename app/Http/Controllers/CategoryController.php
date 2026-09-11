<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('books')->orderBy('name')->get();

        return view('categories.index', compact('categories'));
    }

    // --- Admin CRUD ---

    public function create()
    {
        $this->authorize('create', Category::class);

        return view('admin.categories.form', ['category' => new Category()]);
    }

    public function store(CategoryRequest $request)
    {
        $this->authorize('create', Category::class);
        Category::create($request->validated());

        return redirect()->route('admin.categories.index')->with('status', 'Category created.');
    }

    public function edit(Category $category)
    {
        $this->authorize('update', $category);

        return view('admin.categories.form', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $this->authorize('update', $category);
        $category->update($request->validated());

        return redirect()->route('admin.categories.index')->with('status', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        // books.category_id is nullOnDelete, so dependent books survive as "Uncategorized".
        $category->delete();

        return redirect()->route('admin.categories.index')->with('status', 'Category deleted.');
    }
}
