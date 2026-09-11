<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();
        $this->authorize('update', $user);
        $user->load('profile.preferredCategories');
        $categories = Category::orderBy('name')->get();

        return view('profile.edit', ['user' => $user, 'categories' => $categories]);
    }

    public function update(ProfileRequest $request)
    {
        $user = $request->user();
        $this->authorize('update', $user);
        $data = $request->validated();

        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'country' => $data['country'] ?? null,
            'city' => $data['city'] ?? null,
            'date_of_birth' => $data['date_of_birth'] ?? null,
        ])->save();

        $profile = $user->profile ?? $user->profile()->create([]);
        if ($request->hasFile('avatar')) {
            $profile->avatar = 'storage/' . $request->file('avatar')->store('avatars', 'public');
        }

        $profile->update([
            'interests' => $data['interests'] ?? null,
            'skills' => $data['skills'] ?? null,
            'learning_goals' => $data['learning_goals'] ?? null,
        ]);

        if ($request->hasFile('avatar')) {
            $profile->save();
        }

        $profile->preferredCategories()->sync($data['preferred_categories'] ?? []);

        return redirect()->route('profile.edit')->with('status', 'Profile updated.');
    }
}
