<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::query()
            ->with('category')
            ->search($request->get('q'))
            ->when($request->filled('category'), fn ($q) => $q->where('category_id', $request->get('category')))
            ->when($request->filled('author'), fn ($q) => $q->where('author', 'like', '%' . $request->get('author') . '%'))
            ->when($request->boolean('available_only'), fn ($q) => $q->where('available_copies', '>', 0))
            ->orderBy('title')
            ->paginate(12)
            ->withQueryString(); // preserves filters across pagination links

        $categories = Category::orderBy('name')->get();

        return view('books.index', compact('books', 'categories'));
    }

    public function show(Book $book)
    {
        $this->authorize('view', $book);
        $book->load('category');

        $related = Book::with('category')
            ->where('id', '!=', $book->id)
            ->when($book->category_id, fn ($q) => $q->where('category_id', $book->category_id))
            ->orderByDesc('rating')->limit(6)->get();

        $bundle = collect([$book])->merge($related->take(2));
        if ($bundle->count() < 3) {
            $extra = Book::with('category')->whereNotIn('id', $bundle->pluck('id'))->orderByDesc('rating')->limit(3 - $bundle->count())->get();
            $bundle = $bundle->merge($extra);
        }
        $bundleTotal = $bundle->sum(fn ($item) => (float) $item->price);
        $bundlePrice = round($bundleTotal * 0.85, 2);

        return view('books.show', compact('book', 'related', 'bundle', 'bundleTotal', 'bundlePrice'));
    }

    // --- Admin CRUD below ---

    public function create()
    {
        $this->authorize('create', Book::class);
        $categories = Category::orderBy('name')->get();

        return view('admin.books.form', ['book' => new Book(), 'categories' => $categories]);
    }

    public function store(BookRequest $request)
    {
        $this->authorize('create', Book::class);

        $data = $request->validated();
        $data['cover_image'] = $this->handleCoverUpload($request);

        Book::create($data);

        return redirect()->route('admin.books.index')->with('status', 'Book created.');
    }

    public function edit(Book $book)
    {
        $this->authorize('update', $book);
        $categories = Category::orderBy('name')->get();

        return view('admin.books.form', compact('book', 'categories'));
    }

    public function update(BookRequest $request, Book $book)
    {
        $this->authorize('update', $book);

        $data = $request->validated();
        if ($cover = $this->handleCoverUpload($request)) {
            $data['cover_image'] = $cover;
        }

        $book->update($data);

        return redirect()->route('admin.books.index')->with('status', 'Book updated.');
    }

    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);
        $book->delete();

        return redirect()->route('admin.books.index')->with('status', 'Book deleted.');
    }

    private function handleCoverUpload(Request $request): ?string
    {
        if (!$request->hasFile('cover')) {
            return null;
        }

        // Stored under storage/app/public/covers — remember to run `php artisan storage:link`.
        $path = $request->file('cover')->store('covers', 'public');

        return 'storage/' . $path;
    }
}
