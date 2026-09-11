@extends('layouts.app')
@section('title', 'My Profile — BookNest')
@section('content')
    <div class="profile-page">
        <div class="profile-heading">
            <div><span class="eyebrow">BOOKNEST MEMBER</span><h1>My Profile</h1><p>Manage your personal information and reading preferences.</p></div>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <section class="profile-hero-card">
                <div class="profile-photo-column">
                    <div class="profile-photo-wrap">
                        @if($user->profile?->avatar)
                            <img src="{{ asset($user->profile->avatar) }}" alt="{{ $user->name }}" class="profile-photo">
                        @else
                            <div class="profile-photo profile-initial">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        @endif
                        <label class="photo-edit" for="avatar" title="Change photo">✎</label>
                    </div>
                    <input id="avatar" type="file" name="avatar" accept="image/*" class="sr-only">
                    <div class="profile-photo-hint">JPG, PNG or WEBP · max 4MB</div>
                </div>
                <div class="profile-main-info">
                    <div class="profile-name-row"><div><h2>{{ $user->name }}</h2><p>{{ $user->email }}</p></div><span class="profile-role">{{ ucfirst($user->role) }}</span></div>
                    <div class="profile-info-grid">
                        <label class="profile-info-field"><span>Full name</span><input name="name" value="{{ old('name', $user->name) }}" required></label>
                        <label class="profile-info-field"><span>Email</span><input type="email" name="email" value="{{ old('email', $user->email) }}" required></label>
                        <label class="profile-info-field"><span>Phone</span><input name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Your phone number"></label>
                        <label class="profile-info-field"><span>Country</span><input name="country" value="{{ old('country', $user->country) }}" placeholder="Country"></label>
                        <label class="profile-info-field"><span>City</span><input name="city" value="{{ old('city', $user->city) }}" placeholder="City"></label>
                        <label class="profile-info-field"><span>Date of birth</span><input type="date" name="date_of_birth" value="{{ old('date_of_birth', optional($user->date_of_birth)->format('Y-m-d')) }}"></label>
                    </div>
                    <div class="profile-meta-row"><span><strong>Member since</strong> {{ optional($user->created_at)->format('d M Y') }}</span><span><strong>Role</strong> {{ ucfirst($user->role) }}</span></div>
                </div>
            </section>

            <section class="profile-preferences">
                <div class="section-header"><div><h2>Reading & Learning Preferences</h2><p class="section-subtitle">These fields are already used by BookNest's recommendation system.</p></div></div>
                <div class="preference-grid">
                    <div class="form-group"><label>Interests</label><textarea name="interests" rows="4" placeholder="e.g. Programming, PHP, Laravel, Web Development">{{ old('interests', $user->profile?->interests) }}</textarea></div>
                    <div class="form-group"><label>Skills</label><textarea name="skills" rows="4" placeholder="e.g. Python, SQL, Web Development">{{ old('skills', $user->profile?->skills) }}</textarea></div>
                    <div class="form-group preference-full"><label>Learning goals</label><textarea name="learning_goals" rows="4" placeholder="What would you like to learn next?">{{ old('learning_goals', $user->profile?->learning_goals) }}</textarea></div>
                </div>
                <div class="form-group">
                    <label>Preferred categories</label>
                    @php($preferred = $user->profile?->preferredCategories->pluck('id')->toArray() ?? [])
                    <div class="category-pills">
                        @foreach ($categories as $cat)
                            <label class="category-pill"><input type="checkbox" name="preferred_categories[]" value="{{ $cat->id }}" @checked(in_array($cat->id, $preferred))><span>{{ $cat->name }}</span></label>
                        @endforeach
                    </div>
                </div>
                <div class="profile-save-row"><span>Your profile helps us personalize recommendations.</span><button class="btn btn-burgundy">Save Profile</button></div>
            </section>
        </form>
    </div>
@endsection
