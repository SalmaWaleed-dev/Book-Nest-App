@extends('layouts.app')
@section('title', ($category->exists ? 'Edit' : 'Add') . ' Category — BookNest')
@section('content')
    <div class="form-card">
        <h2 class="font-serif">{{ $category->exists ? 'Edit Category' : 'Add Category' }}</h2>
        <form method="POST" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
            @csrf
            @if ($category->exists)
                @method('PUT')
            @endif
            <div class="form-group"><label>Name</label><input type="text" name="name" value="{{ old('name', $category->name) }}" required></div>
            <div class="form-group"><label>Description</label><input type="text" name="description" value="{{ old('description', $category->description) }}"></div>
            <button class="btn btn-burgundy">Save</button>
        </form>
    </div>
@endsection
