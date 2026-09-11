<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'avatar',
        'interests',
        'skills',
        'learning_goals',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function preferredCategories()
    {
        return $this->belongsToMany(Category::class, 'profile_category');
    }

    /**
     * Flatten all profile text (interests, skills, goals, preferred category names)
     * into one token bag, used by RecommendationService for relevance scoring.
     */
    public function relevanceText(): string
    {
        $parts = [
            $this->interests,
            $this->skills,
            $this->learning_goals,
            $this->preferredCategories->pluck('name')->implode(', '),
        ];

        return trim(implode(', ', array_filter($parts)));
    }
}
