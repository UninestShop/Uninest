<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateProfileRequest;

class ProfileController extends Controller
{
    public function __construct()
    {
        // Share profile completion status with all views
        $this->middleware(function ($request, $next) {
            if (auth()->check()) {
                $user = auth()->user();
                $incompleteFields = [];
                
                if(empty($user->name)) $incompleteFields[] = 'Full Name';
                if(empty($user->mobile_number)) $incompleteFields[] = 'Contact Number';
                if(empty($user->gender)) $incompleteFields[] = 'Gender';
                if(empty($user->dob)) $incompleteFields[] = 'Date of Birth';
                
                $profileComplete = empty($incompleteFields);
                
                view()->share('profileComplete', $profileComplete);
                view()->share('incompleteFields', $incompleteFields);
            }
            
            return $next($request);
        });
    }

    /**
     * Display the user's profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        try {
            $user = auth()->user();
            $userProducts = $user->products()->where('university_id',$user->university_id)->where('status','approved')->latest()->get();
            
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
            return view('profile.show', [
                'user' => $user,
                'userProducts' => $userProducts ?? collect([]),
                'chats' => $chats,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred while loading your profile.'.$e->getMessage()]);
        }
    }

    public function update(UpdateProfileRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $user = auth()->user();
            
            $user->mobile_number = $validatedData['mobile_number'];
            $user->gender = $validatedData['gender'];
            $user->dob = $validatedData['dob'];
            $user->country_code = $validatedData['country_code'];
            $user->country_iso = $request->country_iso;
            $user->save();

            if ($request->filled('name')) {
                try {
                    $nameParts = explode(' ', $request->name, 2);
                    $firstName = $nameParts[0];
                    $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
                    
                    $user->first_name = $firstName;
                    $user->last_name = $lastName;
                    $user->save();
                } catch (\Exception $e) {
                    dd('Error updating name: ' . $e->getMessage());
                    return redirect()->route('profile.show')
                        ->with('success', 'Profile updated successfully.');
                }
            }

            return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred while updating your profile.'.$e->getMessage()]);
        }
    }

    public function sendEmailVerification()
    {
        try {
            $user = auth()->user();
            $otp = $user->generateOtp();
            
            return back()->with('success', "OTP sent to your university email. For testing, OTP is: {$otp}");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to send verification email.'. $e->getMessage()]);
        }
    }

    public function updateImage(Request $request)
    {
        try {
            $request->validate([
                'profile_picture' => 'required|image|max:2048',
            ]);

            $user = auth()->user();

            if ($request->hasFile('profile_picture')) {
                $path = $request->file('profile_picture')->store('profile-images', 'public');
                $user->profile_picture = $path;
                $user->save();
            }

            return back()->with('success', 'Profile picture updated successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while updating your profile picture.'.$e->getMessage()]);
        }
    }

    public function verifyOtp(Request $request)
    {
        try {
            $request->validate([
                'otp' => 'required|string|size:6'
            ]);

            $user = auth()->user();
            
            if ($user->verifyOtp($request->otp)) {
                $user->is_email_verified = true;
                $user->save();
                return back()->with('success', 'Email verified successfully');
            }

            return back()->withErrors(['otp' => 'Invalid or expired OTP']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while verifying your OTP.'.$e->getMessage()]);
        }
    }
}
