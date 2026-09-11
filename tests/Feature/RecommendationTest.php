<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\Profile;
use App\Models\User;
use App\Services\RecommendationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecommendationTest extends TestCase
{
    use RefreshDatabase;

    public function test_scoring_is_deterministic_for_the_same_profile_and_book(): void
    {
        $service = new RecommendationService();
        $category = Category::create(['name' => 'Programming', 'slug' => 'programming']);
        $book = Book::create([
            'title' => 'Clean Code',
            'author' => 'Robert C. Martin',
            'description' => 'A handbook of agile software craftsmanship.',
            'category_id' => $category->id,
            'available_copies' => 3,
            'language' => 'en',
        ]);
        $user = User::factory()->create();
        $profile = Profile::create(['user_id' => $user->id, 'interests' => 'Programming, Clean Code']);
        $profile->preferredCategories()->sync([$category->id]);

        $first = $service->scoreBook($profile, $book);
        $second = $service->scoreBook($profile->fresh(), $book->fresh());

        $this->assertSame($first, $second, 'Same profile + same book must always produce the same score.');
        $this->assertGreaterThan(0, $first);
    }

    public function test_a_book_matching_preferred_category_scores_higher_than_an_unrelated_book(): void
    {
        $service = new RecommendationService();

        $programming = Category::create(['name' => 'Programming', 'slug' => 'programming']);
        $romance = Category::create(['name' => 'Romance', 'slug' => 'romance']);

        $matchingBook = Book::create([
            'title' => 'Laravel for Beginners', 'author' => 'David Katz',
            'description' => 'Learn PHP and Laravel web development from scratch.',
            'category_id' => $programming->id, 'available_copies' => 2, 'language' => 'en',
        ]);
        $unrelatedBook = Book::create([
            'title' => 'A Quiet Romance', 'author' => 'Someone',
            'description' => 'A love story set in Paris.',
            'category_id' => $romance->id, 'available_copies' => 2, 'language' => 'en',
        ]);

        $user = User::factory()->create();
        $profile = Profile::create([
            'user_id' => $user->id,
            'interests' => 'Programming, PHP, Laravel, Web Development',
        ]);
        $profile->preferredCategories()->sync([$programming->id]);

        $matchScore = $service->scoreBook($profile, $matchingBook);
        $unrelatedScore = $service->scoreBook($profile, $unrelatedBook);

        $this->assertGreaterThan($unrelatedScore, $matchScore);
    }

    public function test_recommend_for_returns_results_sorted_highest_first(): void
    {
        $service = new RecommendationService();
        $category = Category::create(['name' => 'Programming', 'slug' => 'programming']);

        Book::create(['title' => 'Laravel Deep Dive', 'description' => 'Laravel, PHP, programming', 'category_id' => $category->id, 'available_copies' => 1, 'language' => 'en']);
        Book::create(['title' => 'Cooking Basics', 'description' => 'Recipes and kitchen tips', 'available_copies' => 1, 'language' => 'en']);

        $user = User::factory()->create();
        $profile = Profile::create(['user_id' => $user->id, 'interests' => 'Laravel, PHP, programming']);
        $profile->preferredCategories()->sync([$category->id]);

        $results = $service->recommendFor($profile);

        $scores = $results->pluck('score')->values()->all();
        $sorted = $scores;
        rsort($sorted);

        $this->assertSame($sorted, $scores, 'Results must be sorted highest score first.');
    }
}
