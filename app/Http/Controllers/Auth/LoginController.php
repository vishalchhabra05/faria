<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // \Artisan::call('migrate');
        $this->middleware('guest')->except('logout');
    }

    public function postLogin(Request $request)
    {
        dd("mohan");
        $this->validate($request, [
            'email' => 'required|email', 'password' => 'required',
        ]);
        if ($this->auth->validate(['email' => $request->email, 'password' => $request->password, 'status' => 0])) {
            return redirect($this->loginPath())
                ->withInput($request->only('email', 'remember'))
                ->withErrors('Your account is Inactive or not verified');
        }
        $credentials  = array('email' => $request->email, 'password' => $request->password);
        if ($this->auth->attempt($credentials, $request->has('remember'))){
                return redirect()->intended($this->redirectPath());
        }
        return redirect($this->loginPath())
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => 'Incorrect email address or password',
            ]);
    }
}
