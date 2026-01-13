<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordEmail;
use App\Mail\PasswordConfirmation;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Requests\ForgotPasswordRequest;

use App\Http\Requests\ResetPasswordRequest;

class ForgotPasswordController extends Controller
{
    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        try {
            return view('auth.passwords.email');
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['error' => 'An error occurred while displaying the password reset request form: ' . $e->getMessage()]);
        }
    }

    /**
     * Send a reset link to the given user.
     */
    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            if($request->email){
                if (!$user) {
                    return redirect()->back()->withErrors(['email' => 'Email address not found']);
                }
            }
            if($request->email != $user->email){
                return redirect()->back()->withErrors(['email' => 'Email address does not match']);
            }

            if($user->status == 'rejected'){
                return redirect()->back()->withErrors(['email' => 'Email address is rejected']);
            }
            
            $status = Password::sendResetLink(
                $request->only('email'),
                function($user, $token) {
                    $resetUrl = url(route('password.reset', [
                        'token' => $token,
                        'email' => $user->email,
                    ], false));
                    
                    Mail::to($user->email)->send(new ForgotPasswordEmail($resetUrl));
                }
            );
            
            return redirect()->back()->with('success', 'A password reset link has been sent to your email address.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to send password reset link: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the password reset view for the given token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        try {
            return view('auth.passwords.reset')->with(
                ['token' => $token, 'email' => $request->email]
            );
        } catch (\Exception $e) {
            return redirect()->route('password.request')
                ->withErrors(['error' => 'An error occurred while displaying the reset form: ' . $e->getMessage()]);
        }
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(ResetPasswordRequest $request)
    {
        try {
            $email = $request->input('email');  
            if (empty($email) && $request->session()->has('_previous')) {
                $previousUrl = $request->session()->get('_previous')['url'] ?? '';
                $parsedUrl = parse_url($previousUrl);
                if (isset($parsedUrl['query'])) {
                    parse_str($parsedUrl['query'], $queryParams);
                    if (isset($queryParams['email'])) {
                        $email = $queryParams['email'];
                    }
                }
            }
            $user = User::where('email', $email)
                ->first();

            $resetUrl = url(route('login'));
            Mail::to($email)->send(new PasswordConfirmation($resetUrl, [
                'email' => $email,
                'name' => $user ? $user->name : 'User'
            ]));
            
            if (empty($email)) {
                return redirect()->back()->withErrors(['email' => 'Email address is required']);
            }
            
            if (!$user) {
                return redirect()->back()->withErrors(['token' => 'Invalid or expired password reset token']);
            }
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect()->route('login')->with('status', 'Password has been reset successfully. A confirmation email has been sent to your email address');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred while resetting your password: ' . $e->getMessage()]);
        }
    }
}
