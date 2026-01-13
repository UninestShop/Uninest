<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use App\Models\Chat;
use App\Models\User;
use Exception;
use App\Http\Requests\UpdatePasswordRequest;

class ProfileSettingsController extends Controller
{
    /**
     * Display the settings page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $user = auth()->user();
            $chats = Chat::with(['product', 'product.user', 'sender', 'receiver'])
                ->where(function($query) use ($user) {
                    $query->where('sender_id', $user->id)
                          ->orWhere('receiver_id', $user->id);
                })
                ->latest()
                ->get()
                ->filter(function($chat) {
                    return $chat->product !== null;
                })
                ->groupBy('product_id');

            return view('profile.settings',compact('chats'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to load profile settings: ' . $e->getMessage());
        }
    }

    /**
     * Update the user's password.
     *
     * @param  \App\Http\Requests\UpdatePasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        try {
            $user = Auth::user();
            $user->password = Hash::make($request->password);
            $user->save();

            return back()->with('success', 'Password updated successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'Failed to update password: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Delete the user's account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAccount(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Log the user out before deleting the account
            Auth::logout();
            
            // Delete the user account
            $user->delete();
            
            // Invalidate the session
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect('/')->with('success', 'Your account has been permanently deleted.');
        } catch (Exception $e) {
            // If an error occurs, redirect back with error message
            return back()->with('error', 'Failed to delete account: ' . $e->getMessage());
        }
    }
}
