<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Programming' => 'Software engineering, languages, and tools.',
            'Artificial Intelligence' => 'Machine learning, deep learning, and AI theory.',
            'Web Development' => 'Frontend, backend, and full-stack web technologies.',
            'Database' => 'Database systems, SQL, and data modeling.',
            'Networking & Security' => 'Networks, Linux, and cybersecurity.',
            'Fantasy Fiction' => 'Fantasy and epic fiction.',
            'Romance' => 'Romance and contemporary romance fiction.',
            'Literary Fiction' => 'Literary and mainstream fiction.',
            'Young Adult' => 'YA fiction and coming-of-age stories.',
            'Arabic Literature' => 'Arabic-language fiction and literature.',
        ];

        foreach ($categories as $name => $description) {
            Category::updateOrCreate(['name' => $name], ['description' => $description]);
        }
    }
}
