<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\RecommendationService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request, RecommendationService $recs)
    {
        $featured = Book::with('category')->orderByDesc('rating')->limit(8)->get();

        $recommendations = collect();
        if ($request->user()?->profile) {
            $recommendations = $recs->recommendFor($request->user()->profile, 6);
        }

        return view('home', compact('featured', 'recommendations'));
    }
}
