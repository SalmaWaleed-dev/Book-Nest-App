<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Profile;
use Illuminate\Support\Collection;

/**
 * Deterministic, explainable recommendation scoring.
 *
 * No randomness anywhere: the same profile + the same book always produce the
 * same score. If an embedding provider is configured (EMBEDDINGS_PROVIDER in
 * .env) a future version can swap scoreBook() for cosine similarity — but the
 * app must keep working with zero external calls, so the default path here is
 * a weighted keyword/category overlap model.
 */
class RecommendationService
{
    // Tunable weights — category match counts more than a loose keyword hit.
    private const CATEGORY_MATCH_WEIGHT = 40;
    private const KEYWORD_WEIGHT = 60;

    public function recommendFor(Profile $profile, int $limit = 12): Collection
    {
        $books = Book::with('category')->get();

        return $books
            ->map(fn (Book $book) => [
                'book' => $book,
                'score' => $this->scoreBook($profile, $book),
            ])
            ->filter(fn ($row) => $row['score'] > 0)
            ->sortByDesc('score')
            ->take($limit)
            ->values();
    }

    /**
     * Returns an integer percentage 0-100.
     */
    public function scoreBook(Profile $profile, Book $book): int
    {
        $profileTokens = $this->tokenize($profile->relevanceText());

        if (empty($profileTokens)) {
            return 0;
        }

        $categoryScore = $this->categoryScore($profile, $book);
        $keywordScore = $this->keywordScore($profileTokens, $book);

        $raw = ($categoryScore * self::CATEGORY_MATCH_WEIGHT / 100)
             + ($keywordScore * self::KEYWORD_WEIGHT / 100);

        return (int) round(min(100, max(0, $raw)));
    }

    private function categoryScore(Profile $profile, Book $book): float
    {
        if (!$book->category_id) {
            return 0;
        }

        $preferredIds = $profile->preferredCategories->pluck('id');

        return $preferredIds->contains($book->category_id) ? 100 : 0;
    }

    /**
     * Overlap between profile tokens and book tokens, weighted so a hit on
     * the title/category text counts more than a hit buried in the description.
     */
    private function keywordScore(array $profileTokens, Book $book): float
    {
        $titleTokens = $this->tokenize($book->title . ' ' . $book->category?->name);
        $bodyTokens = $this->tokenize($book->description ?? '');

        $profileSet = array_flip($profileTokens);

        $titleHits = count(array_intersect($titleTokens, array_keys($profileSet)));
        $bodyHits = count(array_intersect($bodyTokens, array_keys($profileSet)));

        $weightedHits = ($titleHits * 2) + $bodyHits;
        $possible = max(1, count($profileTokens));

        // Normalize against how many profile tokens *could* have matched,
        // capped so one very long description can't dominate the score.
        return min(100, ($weightedHits / $possible) * 100);
    }

    private function tokenize(string $text): array
    {
        $text = mb_strtolower($text);
        // keep unicode word characters (covers Arabic titles too), split on everything else
        preg_match_all('/[\p{L}\p{N}]+/u', $text, $matches);

        $stopwords = ['the', 'and', 'a', 'an', 'of', 'to', 'in', 'for', 'on', 'is', 'are'];

        return array_values(array_diff(array_unique($matches[0]), $stopwords));
    }
}
