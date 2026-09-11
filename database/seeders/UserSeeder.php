<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    // Demo credentials only — change these before any real deployment,
    // and never commit real production credentials to source control.
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@booknest.test'],
            ['name' => 'BookNest Admin', 'password' => Hash::make('password'), 'role' => 'admin']
        );
        Profile::firstOrCreate(['user_id' => $admin->id]);

        $user = User::updateOrCreate(
            ['email' => 'reader@booknest.test'],
            ['name' => 'Demo Reader', 'password' => Hash::make('password'), 'role' => 'user']
        );
        $profile = Profile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'interests' => 'Programming, PHP, Laravel, Web Development, Artificial Intelligence',
                'skills' => 'JavaScript, HTML, CSS',
                'learning_goals' => 'Get better at backend development and machine learning basics',
            ]
        );

        $preferred = Category::whereIn('name', ['Programming', 'Web Development', 'Artificial Intelligence'])->pluck('id');
        $profile->preferredCategories()->sync($preferred);
    }
}
