<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookCategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_cannot_create_a_book(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->get('/admin/books/create')->assertForbidden();
    }

    public function test_an_admin_can_create_update_and_delete_a_book(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::create(['name' => 'Programming', 'slug' => 'programming']);

        $this->actingAs($admin)->post('/admin/books', [
            'title' => 'Test Driven Laravel',
            'author' => 'Someone',
            'category_id' => $category->id,
            'available_copies' => 5,
            'language' => 'en',
        ])->assertRedirect(route('admin.books.index'));

        $book = Book::where('title', 'Test Driven Laravel')->firstOrFail();

        $this->actingAs($admin)->put("/admin/books/{$book->id}", [
            'title' => 'Test Driven Laravel (2nd Edition)',
            'available_copies' => 7,
            'language' => 'en',
        ])->assertRedirect(route('admin.books.index'));

        $this->assertSame('Test Driven Laravel (2nd Edition)', $book->fresh()->title);

        $this->actingAs($admin)->delete("/admin/books/{$book->id}")
            ->assertRedirect(route('admin.books.index'));

        $this->assertNull(Book::find($book->id));
    }

    public function test_deleting_a_category_does_not_delete_its_books(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::create(['name' => 'Fantasy Fiction', 'slug' => 'fantasy-fiction']);
        $book = Book::create([
            'title' => 'A Court of Something', 'category_id' => $category->id,
            'available_copies' => 1, 'language' => 'en',
        ]);

        $this->actingAs($admin)->delete("/admin/categories/{$category->id}")
            ->assertRedirect(route('admin.categories.index'));

        $this->assertNull(Category::find($category->id));
        $this->assertNotNull(Book::find($book->id), 'Book must survive category deletion (nullOnDelete).');
        $this->assertNull($book->fresh()->category_id);
    }

    public function test_book_search_matches_title_author_and_category(): void
    {
        $category = Category::create(['name' => 'Artificial Intelligence', 'slug' => 'artificial-intelligence']);
        Book::create(['title' => 'Deep Learning', 'author' => 'Ian Goodfellow', 'category_id' => $category->id, 'available_copies' => 1, 'language' => 'en']);
        Book::create(['title' => 'Unrelated Title', 'author' => 'Nobody', 'available_copies' => 1, 'language' => 'en']);

        $response = $this->get('/books?q=Goodfellow');

        $response->assertOk();
        $response->assertSee('Deep Learning');
        $response->assertDontSee('Unrelated Title');
    }
}
