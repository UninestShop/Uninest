<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the admin profile page.
     */
    public function index()
    {
        try {
            return view('admin.profile.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load profile: ' . $e->getMessage());
        }
    }

    /**
     * Update the admin's profile information.
     */
    public function update(Request $request)
    {
        try {
            $user = Auth::guard('admin')->user();
            
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => [
                    'required', 
                    'string', 
                    'email', 
                    'max:255', 
                    Rule::unique('admins')->ignore($user->id)
                ],
                'current_password' => ['nullable', 'required_with:password', function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('The current password is incorrect.');
                    }
                }],
                'password' => ['nullable', 'required_with:current_password', 'min:8', 'confirmed'],
            ]);
            
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            
            if ($request->filled('password')) {
                $user->password = Hash::make($validated['password']);
            }
            
            $user->save();
            
            return redirect()->route('admin.profile.index')
                ->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update profile: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update the admin's profile photo.
     */
    public function updateAvatar(Request $request)
    {
        try {
            $request->validate([
                'image' => ['required', 'image', 'max:2048'], // max 2MB
            ]);
            
            $user = Auth::guard('admin')->user();
            
            // Delete old avatar if exists and not the default one
            if ($user->image && !str_contains($user->image, 'default-avatar')) {
                Storage::delete('public/' . str_replace('storage/', '', $user->avatar));
            }
            
            // Store the new avatar
            $avatarPath = $request->file('image')->store('avatars', 'public');
            $user->image = 'storage/' . $avatarPath;
            $user->save();
            
            return redirect()->route('admin.profile.index')
                ->with('success', 'Profile photo updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update profile photo: ' . $e->getMessage());
        }
    }
}
