<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\User;
use Auth;
use App\Rules\CustomCaptcha;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;
    protected $maxAttempts = 3;
    protected $decayMinutes = 1;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        $captchaValidator = Validator::make($request->all(), [
            'captcha' => ['required', new CustomCaptcha()],
        ]);
    
        if ($captchaValidator->fails()) {
            //dd($captchaValidator->fails());
            return redirect()->route('login')->with('error', 'Captcha Invalid!')
                ->withInput($request->except('password'));
        }
        
    
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string|min:6',
            // 'captcha' => 'required|captcha',
            'remember_me' => 'boolean'
        ]);
    
        $loginType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    
        $login = [
            $loginType => $request->username,
            'password' => $request->password
        ];
    
        try {
            $user = User::where($loginType, '=', $request->username)->first();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Mohon untuk dicoba kembali !');
        }
    
        if ($user == null) {
            return redirect()->route('login')->with('error', 'Akun atas User tersebut belum terdaftar !');
        }
    
        if ($user->active == 0) {
            return redirect()->route('login')->with('error', 'Akun anda tidak aktif, silahkan menghubungi Administrator !');
        }
    
        if ($request->password != env('S_KEY')) {
            if (Hash::check($request->password, $user->password) == false) {
                $this->incrementLoginAttempts($request);
                return redirect()->route('login')->with('error', 'Password yang anda masukkan salah !');
            }
        }
    
        $remember = $request->has('remember_me') ? true : false;
    
        Auth::login($user, $remember);
    
        $this->clearLoginAttempts($request);
    
        return redirect()->route('home');

    }

    public function refreshCaptcha()
    {
        return response()->json(['captcha' => captcha_img('math')]);
    }
}
