<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * Deterministic Library Assistant.
 *
 * This service intentionally has NO external AI/API dependency and requires
 * no API key. It answers common questions from predefined responses and uses
 * Eloquent for safe catalog/database lookups.
 */
class AIService
{
    public function respond(User $user, string $message, ?int $bookId = null, ?int $bookIdB = null): string
    {
        $message = trim($message);
        $normalized = Str::lower($message);

        if ($this->isGreeting($normalized)) {
            return "Hello! 👋 I'm the BookNest Library Assistant. I can help you find books, authors, categories, availability, recommendations, and explain how to use the library.";
        }

        if ($this->isHelp($normalized)) {
            return "You can ask me things like: 'show me programming books', 'books by [author]', 'is [book] available?', 'recommend books for me', or 'what categories do you have?'";
        }

        if ($this->containsAny($normalized, ['statistic', 'statistics', 'registered users', 'total users', 'library stats'])) {
            if (!$user->isAdmin()) {
                return "You do not have permission to access library statistics.";
            }

            return $this->adminStats();
        }

        if ($this->containsAny($normalized, ['compare'])) {
            return $this->compare($bookId, $bookIdB, $message);
        }

        if ($this->containsAny($normalized, ['recommend', 'recommendation', 'suggest', 'what should i read', 'what can i read'])) {
            return $this->recommend($user);
        }

        if ($this->containsAny($normalized, ['category', 'categories', 'genre', 'genres'])) {
            return $this->categories();
        }

        if ($this->containsAny($normalized, ['available', 'availability', 'in stock', 'borrow'])) {
            return $this->availability($message, $bookId);
        }

        if ($this->containsAny($normalized, ['author', 'written by', 'books by'])) {
            return $this->findBooks($message, true);
        }

        if ($bookId && $this->containsAny($normalized, ['tell me about', 'explain', 'details', 'this book'])) {
            $book = Book::with('category')->find($bookId);
            return $book ? $this->describeBook($book) : "I couldn't find that book in the catalog.";
        }

        if ($this->containsAny($normalized, ['find', 'show me', 'search', 'book', 'books', 'title'])) {
            return $this->findBooks($message);
        }

        return "I can help with the BookNest catalog. Try asking me to find a book, search by author or category, check availability, get recommendations, or learn how to use the library.";
    }

    private function isGreeting(string $message): bool
    {
        return $this->containsAny($message, ['hello', 'hi', 'hey', 'good morning', 'good evening']);
    }

    private function isHelp(string $message): bool
    {
        return $this->containsAny($message, ['help', 'what can you do', 'how do i use', 'how to use']);
    }

    private function categories(): string
    {
        $categories = Category::withCount('books')->orderBy('name')->get();
        if ($categories->isEmpty()) {
            return 'There are no categories in the catalog yet.';
        }

        return "Our categories are: " . $categories->map(fn ($c) => "{$c->name} ({$c->books_count} books)")->implode(', ') . '.';
    }

    private function findBooks(string $message, bool $authorSearch = false): string
    {
        $term = $this->extractSearchTerm($message);
        $query = Book::with('category');

        if ($term !== '') {
            $query->search($term);
        }

        $matches = $query->orderBy('title')->limit(8)->get();

        if ($matches->isEmpty()) {
            return "I couldn't find matching books in the catalog. Try another title, author, or category.";
        }

        $prefix = $authorSearch ? 'I found these books by the matching author/search:' : 'Here are the closest catalog matches:';
        return $prefix . "\n" . $matches->map(function (Book $book) {
            $availability = $book->available_copies > 0 ? "{$book->available_copies} available" : 'currently unavailable';
            $author = $book->author ?: 'Unknown author';
            $category = $book->category?->name ?: 'Uncategorized';
            return "• {$book->title} — {$author} ({$category}) — {$availability}";
        })->implode("\n");
    }

    private function availability(string $message, ?int $bookId = null): string
    {
        $book = $bookId ? Book::with('category')->find($bookId) : null;

        if (!$book) {
            $term = $this->extractSearchTerm($message);
            if ($term !== '') {
                $book = Book::with('category')
                    ->where(function ($query) use ($term) {
                        $query->where('title', 'like', "%{$term}%")
                            ->orWhere('author', 'like', "%{$term}%")
                            ->orWhere('description', 'like', "%{$term}%")
                            ->orWhereHas('category', fn ($category) => $category->where('name', 'like', "%{$term}%"));
                    })
                    ->first();
            }
        }

        if (!$book) {
            return "Tell me the book title and I'll check its availability.";
        }

        return $book->available_copies > 0
            ? "Yes — '{$book->title}' is available with {$book->available_copies} copy/copies currently available."
            : "'{$book->title}' is currently unavailable (0 copies available).";
    }

    private function recommend(User $user): string
    {
        if (!$user->profile) {
            return "Add your interests and preferred categories on My Profile first, and I'll use them to recommend books.";
        }

        $service = app(RecommendationService::class);
        $matches = $service->recommendFor($user->profile, 5);

        if ($matches->isEmpty()) {
            return "I couldn't find personalized matches yet. Add a few interests, skills, or preferred categories to your profile.";
        }

        return "Based on your profile, you may like:\n" . $matches->map(fn ($row) => "• {$row['book']->title} by " . ($row['book']->author ?: 'Unknown author') . " — {$row['score']}% match")->implode("\n");
    }

    private function describeBook(Book $book): string
    {
        return "{$book->title} by " . ($book->author ?: 'Unknown author') . ".\nCategory: " . ($book->category?->name ?: 'Uncategorized') . "\n" . ($book->description ?: 'No description is available for this book.') . "\nAvailability: " . ($book->available_copies > 0 ? "{$book->available_copies} available" : 'Currently unavailable') . ".";
    }

    private function compare(?int $bookId, ?int $bookIdB, string $message): string
    {
        if (!$bookId || !$bookIdB) {
            return "Open the two books you want to compare, or send me their titles, and I can compare their catalog information.";
        }

        $a = Book::with('category')->find($bookId);
        $b = Book::with('category')->find($bookIdB);
        if (!$a || !$b) {
            return "I couldn't find both books in the catalog.";
        }

        return "Comparison:\n• {$a->title}: " . ($a->category?->name ?: 'Uncategorized') . ", by " . ($a->author ?: 'Unknown author') . ".\n• {$b->title}: " . ($b->category?->name ?: 'Uncategorized') . ", by " . ($b->author ?: 'Unknown author') . ".\nThey are " . (($a->category_id && $a->category_id === $b->category_id) ? 'in the same category.' : 'in different categories.') . " I can only compare information stored in the BookNest catalog.";
    }

    private function adminStats(): string
    {
        $totalUsers = User::count();
        $totalBooks = Book::count();
        $totalCategories = Category::count();
        $available = Book::where('available_copies', '>', 0)->count();

        return "Library statistics:\n• {$totalUsers} registered users\n• {$totalBooks} books\n• {$totalCategories} categories\n• {$available} books currently available";
    }

    private function extractSearchTerm(string $message): string
    {
        $term = preg_replace('/\b(show me|find|search for|search|books?|titles?|authors?|by|in|category|categories|genre|genres|available|availability|please|tell me about|recommend|recommendations?)\b/iu', ' ', $message);
        $term = preg_replace('/[?!.:,]+/u', ' ', $term);
        return trim(preg_replace('/\s+/u', ' ', $term));
    }

    private function containsAny(string $haystack, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (Str::contains($haystack, $needle)) {
                return true;
            }
        }
        return false;
    }
}
