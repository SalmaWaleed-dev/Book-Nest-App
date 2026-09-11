<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Belt and suspenders: route already has 'admin' middleware, but a
        // Form Request should never assume that.
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $bookId = $this->route('book')?->id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'author' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'isbn' => ['nullable', 'string', 'max:32', 'unique:books,isbn,' . $bookId],
            'publication_date' => ['nullable', 'date'],
            'available_copies' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'language' => ['required', 'in:en,ar'],
            'cover' => ['nullable', 'image', 'max:4096'],
        ];
    }
}
