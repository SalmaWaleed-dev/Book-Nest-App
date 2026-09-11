<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeds books from the real supplied cover images (public/images/covers/...).
 * Titles come directly from the original filenames; author/category were
 * hand-curated for recognizable titles in database/seeders/books_seed_data.json
 * (built once from arabic_books_map.json, english_books_map.json,
 * programming_books_map.json) and default to 'Unknown author' where the
 * title couldn't be confidently identified — admins can correct these
 * from /admin/books at any time. No cover here is generated or placeholder;
 * every row points at a real supplied image.
 */
class BookSeeder extends Seeder
{
    public function run(): void
    {
        $path = __DIR__ . '/books_seed_data.json';
        if (!file_exists($path)) {
            $this->command?->warn('books_seed_data.json not found — skipping book seeding.');
            return;
        }

        $rows = json_decode(file_get_contents($path), true);
        $categoryIds = Category::pluck('id', 'name');

        foreach ($rows as $i => $row) {
            $isbn = '978' . substr(str_pad((string) crc32($row['title'] . $row['cover']), 10, '0', STR_PAD_LEFT), 0, 10);

            Book::updateOrCreate(
                ['isbn' => $isbn],
                [
                    'title' => $row['title'],
                    'author' => $row['author'] === 'Unknown' ? null : $row['author'],
                    'description' => $this->generateDescription($row),
                    'category_id' => $categoryIds[$row['category']] ?? null,
                    'publication_date' => null,
                    // deterministic, not random: cycles 1..12 copies by index
                    'available_copies' => ($i % 12) + 1,
                    'cover_image' => 'images/' . $row['cover'],
                    'language' => $row['language'],
                    'rating' => round(3.5 + (($i * 37) % 15) / 10, 1), // deterministic 3.5-5.0 spread
                    'ratings_count' => 50 + (($i * 53) % 950),
                ]
            );
        }

        $this->command?->info(count($rows) . ' books seeded from real supplied covers.');
    }

    private function generateDescription(array $row): string
    {
        if ($row['category'] === 'Arabic Literature') {
            return "كتاب \"{$row['title']}\" من قسم الأدب العربي في مكتبة BookNest.";
        }

        $author = $row['author'] === 'Unknown' ? 'an acclaimed author' : $row['author'];

        return "\"{$row['title']}\" by {$author} is part of BookNest's {$row['category']} collection.";
    }
}
