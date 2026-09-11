@extends('layouts.app')
@section('title', ($book->exists ? 'Edit' : 'Add') . ' Book — BookNest')
@section('content')
    <div class="form-card" style="max-width:600px">
        <h2 class="font-serif">{{ $book->exists ? 'Edit Book' : 'Add Book' }}</h2>
        <form method="POST" action="{{ $book->exists ? route('admin.books.update', $book) : route('admin.books.store') }}" enctype="multipart/form-data">
            @csrf
            @if ($book->exists)
                @method('PUT')
            @endif
            <div class="form-group"><label>Title</label><input type="text" name="title" value="{{ old('title', $book->title) }}" required></div>
            <div class="form-group"><label>Author</label><input type="text" name="author" value="{{ old('author', $book->author) }}"></div>
            <div class="form-group"><label>Description</label><textarea name="description" rows="4">{{ old('description', $book->description) }}</textarea></div>
            <div class="form-group">
                <label>Category</label>
                <select name="category_id">
                    <option value="">— none —</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id', $book->category_id) == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group"><label>ISBN</label><input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}"></div>
            <div class="form-group"><label>Publication date</label><input type="date" name="publication_date" value="{{ old('publication_date', optional($book->publication_date)->format('Y-m-d')) }}"></div>
            <div class="form-group"><label>Available copies</label><input type="number" min="0" name="available_copies" value="{{ old('available_copies', $book->available_copies ?? 0) }}" required></div>
            <div class="form-group"><label>Price (EGP)</label><input type="number" min="0" step="0.01" name="price" value="{{ old('price', $book->price ?? 100) }}" required></div>
            <div class="form-group">
                <label>Language</label>
                <select name="language">
                    <option value="en" @selected(old('language', $book->language ?? 'en') === 'en')>English</option>
                    <option value="ar" @selected(old('language', $book->language) === 'ar')>Arabic</option>
                </select>
            </div>
            <div class="form-group"><label>Cover image {{ $book->exists ? '(leave blank to keep current)' : '' }}</label><input type="file" name="cover" accept="image/*"></div>
            <button class="btn btn-burgundy">Save</button>
        </form>
    </div>
@endsection
