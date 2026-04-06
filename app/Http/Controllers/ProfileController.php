<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        $profileData = $request->only(['phone', 'bio', 'facebook', 'instagram', 'linkedin']);
        if (count(array_filter($profileData, function($value) { return $value !== null || isset($value); })) > 0 || $request->hasAny(['phone', 'bio', 'facebook', 'instagram', 'linkedin'])) {
            $request->user()->profile()->updateOrCreate(
                ['user_id' => $request->user()->id],
                $profileData
            );
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Profile updated successfully']);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Upload the user's profile photo.
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);

        $user = $request->user();

        // Delete old photo if it exists
        $profile = $user->profile;
        if ($profile && $profile->photo) {
            Storage::disk('public')->delete($profile->photo);
        }

        // Store new photo
        $path = $request->file('photo')->store('profile-photos', 'public');

        // Update or create profile record
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            ['photo' => $path]
        );

        return response()->json([
            'message' => 'Profile photo updated successfully',
            'photo_url' => asset('storage/' . $path),
        ]);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
