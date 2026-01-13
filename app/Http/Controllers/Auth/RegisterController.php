<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\University;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerification;
use App\Mail\WelcomeEmail;
use Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/profile';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        // Get universities from the database
        // $universities = University::orderBy('name')->where('status','active')->get();
        $universities = University::where('country','United States')->get();

        
        return view('auth.register', compact('universities'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'university_email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                'unique:users',
                'ends_with:.edu'
            ],
            'university_name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms_accepted' => ['required', 'accepted'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        try{
            $existingUser = User::where('university_email', $data['university_email'])->first();
            if ($existingUser) {
                if ($existingUser->email_verified_at == null) {
                    $nameParts = explode(' ', $data['name'], 2);
                    $firstName = $nameParts[0];
                    $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
                    
                    $existingUser->first_name = $firstName;
                    $existingUser->last_name = $lastName;
                    $existingUser->email = $data['university_email'];
                    $existingUser->university_id = $data['university_name'];
                    $existingUser->password = Hash::make($data['password']);
                    $existingUser->slug = Str::slug($data['name']);
                    $existingUser->remember_token = Str::random(64);
                    $existingUser->save();

                    try {
                        $verificationData = [
                            'email' => $data['university_email'],
                            'name' => $data['name'],
                            'token' => $existingUser->remember_token
                        ];
                        
                        Mail::to($data['university_email'])->send(new EmailVerification($verificationData));
                    } catch (\Exception $e) {
                        \Log::error('Failed to send verification email: ' . $e->getMessage());
                    }
                    
                    return $existingUser;
                }
                else{  
                 $validator = Validator::make($data, [
                'university_email' => 'unique:users'
                ], [
                    'university_email.unique' => 'This university email is already registered.'
                ]);

               if ($validator->fails()) 
                {
                    return back()->withErrors($validator)->withInput($request->all());
                }
            }
            }

            $nameParts = explode(' ', $data['name'], 2);
            $firstName = $nameParts[0];
            $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
            
            $user = User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $data['university_email'],
                'university_email' => $data['university_email'],
                'university_id' => $data['university_name'],
                'password' => Hash::make($data['password']),
                'user_type' => 'seller',
                'slug' => Str::slug($data['name']),
                'is_email_verified' => false,
                'remember_token' => Str::random(64),
            ]);

            try {
                $verificationData = [
                    'email' => $data['university_email'],
                    'name' => $data['name'],
                    'token' => $user->remember_token
                ];
                
                Mail::to($data['university_email'])->send(new EmailVerification($verificationData));
            } catch (\Exception $e) {
                \Log::error('Failed to send verification email: ' . $e->getMessage());
            }
            
            return $user;
        } catch (\Exception $e) {
            \Log::error('Failed to create user: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Override the registration method from RegistersUsers trait
     *
     * @param  \App\Http\Requests\RegisterRequest  $request
     * @return \Illuminate\Http\Response
     */
    // public function register(RegisterRequest $request)
    // {
    //     try {
    //         // Create user but don't log them in
    //         $user = $this->create($request->validated());

    //         // Instead of logging in, redirect to login with message
    //         return redirect()->route('login')
    //             ->with('success', 'A verification email has been sent to your email address. Please check your inbox and click the link to verify your email.');
    //     } catch (\Exception $e) {
    //         \Log::error('Registration failed: ' . $e->getMessage());
    //         return redirect()->back()
    //             ->withInput($request->except('password', 'password_confirmation'))
    //             ->with('error', 'Registration failed. Please try again later.');
    //     }
    public function register(RegisterRequest $request)
    {
        try {
            $user = $this->create($request->validated());
            session(['registration_email' => $request->university_email]);
            return redirect()->route('login', ['email' => $request->university_email])
                ->with('success', 'A verification email has been sent to your email address. Please check your inbox and click the link to verify your email.')
                ->with('registration_email', $request->university_email);
        } catch (\Exception $e) {
            \Log::error('Registration failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'Registration failed. Please try again later.');
        }
    }

    public function resendVerificationEmail(Request $request)
    {
        $email = $request->email ?? session('registration_email');
        
        if (!$email) {
            return redirect()->route('login')
                ->with('error', 'Email address not found. Please register again.');
        }
        
        $user = User::where('email', $email)->where('is_email_verified', 0)->first();
        
        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'User not found or already verified.');
        }
        
        $resendCount = session('resend_count_' . $user->id, 0);
        $lastResendTime = session('last_resend_' . $user->id, 0);
        $currentTime = time();
        
        // Set a reasonable limit (10 emails) with a cooldown period (30 minutes)
        if ($resendCount >= 10 && ($currentTime - $lastResendTime) < 1800) {
            return redirect()->route('login', ['email' => $email])
                ->with('error', 'Too many verification email requests. Please try again later.')
                ->with('registration_email', $email);
        }
        
        try {
            $verificationData = [
                'email' => $user->email,
                'name' => $user->first_name . ' ' . $user->last_name,
                'token' => $user->remember_token ?: Str::random(64)
            ];
            
            if (!$user->remember_token) {
                $user->remember_token = $verificationData['token'];
                $user->save();
            }
            
            Mail::to($user->email)->send(new EmailVerification($verificationData));
            
            // Update resend tracking
            session([
                'resend_count_' . $user->id => $resendCount + 1,
                'last_resend_' . $user->id => $currentTime
            ]);
            
            return redirect()->route('login', ['email' => $email])
                ->with('success', 'Verification email has been resent. Please check your inbox.')
                ->with('registration_email', $email);
        } catch (\Exception $e) {
            \Log::error('Failed to resend verification email: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Failed to resend verification email. Please try again later.');
        }
    }

    /**
     * Handle email verification
     * 
     * @param string $token
     * @return \Illuminate\Http\Response
     */
    public function verifyEmail($token)
    {
        try {
            $user = User::where('remember_token', $token)->first();
           
            if (!$user) {
                \Log::error('Email verification failed: User not found with token ' . $token);
                return redirect()->route('login')->with('error', 'Invalid verification link or link has expired.');
            }
            
            $user->is_email_verified = 1;
            $user->email_verified_at = now();
            $user->save();

            try {
                $welcomeData = [
                    'email' => $user->email,
                    'name' => $user->first_name
                ];

                Mail::to($user->email)->send(new WelcomeEmail($welcomeData));
            } catch (\Exception $e) {
                \Log::error('Failed to send welcome email: ' . $e->getMessage());
            }
            
            if (auth()->check()) {
                return redirect()->route('profile')->with('success', 'Your email has been verified!');
            }
            
            return redirect()->route('login')->with('success', 'Your email has been verified. You can now login!');
        } catch (\Exception $e) {
            \Log::error('Email verification process failed: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Email verification failed. Please try again or contact support.');
        }
    }

    private function extractUniversityName($email)
    {
        $domain = explode('@', $email)[1];
        $universityName = str_replace('.edu', '', $domain);
        $universityName = str_replace(['.', '-'], ' ', $universityName);
        return ucwords($universityName);
    }
}
