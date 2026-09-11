<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelBehaviorTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_slug_is_generated_from_name_when_not_provided(): void
    {
        $category = Category::create(['name' => 'Networking & Security']);

        $this->assertSame('networking-security', $category->slug);
    }

    public function test_book_relevance_text_combines_title_author_description_and_category(): void
    {
        $category = Category::create(['name' => 'Programming', 'slug' => 'programming']);
        $book = Book::create([
            'title' => 'Clean Code',
            'author' => 'Robert C. Martin',
            'description' => 'Agile software craftsmanship.',
            'category_id' => $category->id,
            'available_copies' => 1,
            'language' => 'en',
        ]);

        $text = $book->relevanceText();

        $this->assertStringContainsString('Clean Code', $text);
        $this->assertStringContainsString('Robert C. Martin', $text);
        $this->assertStringContainsString('Programming', $text);
    }

    public function test_book_cover_url_falls_back_to_placeholder_when_missing(): void
    {
        $book = Book::create(['title' => 'No Cover Book', 'available_copies' => 1, 'language' => 'en']);

        $this->assertStringContainsString('placeholder.png', $book->coverUrl());
    }
}
