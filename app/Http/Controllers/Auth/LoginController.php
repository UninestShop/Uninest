<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string  
     */
    protected $redirectTo = '/profile';  // Redirect to profile page after login

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        try {
            $this->middleware('guest')->except('logout');
            $this->middleware('auth')->only('logout');
        } catch (\Exception $e) {
            report($e);
            abort(500, 'Error setting up login controller.');
        }
    }

    /**
     * The user has been authenticated.
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request  $request, $user)
    {
        try {
            if ($user->is_email_verified != 1) {
                $this->guard()->logout();
                return redirect()->back()
                    ->withInput($request->only('email', 'remember'))
                    ->withErrors(['email' => 'You need to verify your email address before logging in.']);
            }

            if($user->status == 'rejected') {
                $this->guard()->logout();
                return redirect()->back()
                    ->withInput($request->only('email', 'remember'))
                    ->withErrors(['email' => 'Your account has been rejected please contact to support.']);
            }
            
            return redirect()->intended($this->redirectPath());
        } catch (\Exception $e) {
            $this->guard()->logout();
            report($e);
            return redirect()->back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'An error occurred during login. Please try again.']);
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        \Auth::guard('web')->logout();
        $request->session()->regenerateToken();
        
        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }
}
