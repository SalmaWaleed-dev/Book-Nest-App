<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\LlmAIService;
use Illuminate\Http\Request;

class AIChatController extends Controller
{
    public function chat(Request $request, LlmAIService $assistant)
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:500'],
            'book_id' => ['nullable', 'integer', 'exists:books,id'],
            'book_id_b' => ['nullable', 'integer', 'exists:books,id'],
        ]);

        $user = $request->user();
        if (!$user) {
            return response()->json(['reply' => 'Please log in to use the Library Assistant.'], 401);
        }

            return response()->json([
            'reply' => $assistant->respond($user, $data['message'], $data['book_id'] ?? null),
        ]);
    }
}
