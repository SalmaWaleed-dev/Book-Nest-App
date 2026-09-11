<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // A user's preferred categories (many-to-many), used directly by the recommendation engine.
    public function up(): void
    {
        Schema::create('profile_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained('profiles')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['profile_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_category');
    }
};
