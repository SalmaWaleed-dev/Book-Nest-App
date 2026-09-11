<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title', 'author', 'description', 'category_id', 'isbn',
        'publication_date', 'available_copies', 'price', 'cover_image',
        'language', 'rating', 'ratings_count',
    ];

    protected function casts(): array
    {
        return [
            'publication_date' => 'date',
            'rating' => 'decimal:2',
            'price' => 'decimal:2',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function coverUrl(): string
    {
        // cover_image is always stored as a path already relative to public/
        // (e.g. "images/covers/arabic/arabic-001.jpg" from BookSeeder, or
        // "storage/covers/xyz.jpg" from an admin upload via BookController)
        // — never prepend anything here, just resolve it through asset().
        return $this->cover_image
            ? asset(ltrim($this->cover_image, '/'))
            : asset('images/covers/placeholder.png');
    }

    /**
     * Flattened searchable text used by the recommendation engine
     * and by the AI service when preparing safe context.
     */
    public function relevanceText(): string
    {
        return trim(implode(' ', array_filter([
            $this->title,
            $this->author,
            $this->description,
            $this->category?->name,
        ])));
    }

    public function scopeSearch($query, ?string $term)
    {
        if (!$term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('author', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%")
                ->orWhere('isbn', 'like', "%{$term}%")
                ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%{$term}%"));
        });
    }
}
