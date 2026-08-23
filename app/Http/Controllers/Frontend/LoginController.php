<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Setting;
use App\Models\BannerImage;
use App\Models\GoogleRecaptcha;
use App\Rules\Captcha;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserForgetPassword;
use App\Helpers\MailHelper;
use App\Models\EmailTemplate;
use App\Models\SocialLoginInformation;
use Laravel\Socialite\Facades\Socialite;
use Carbon\Carbon;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        $setting = Setting::first();
        $bannerImage = BannerImage::whereId('15')->first();
        $googleRecaptcha = GoogleRecaptcha::first();
        
        return view('frontend.auth.login', compact('setting', 'bannerImage', 'googleRecaptcha'));
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:4',
        ];

        $googleRecaptcha = GoogleRecaptcha::first();
        if($googleRecaptcha->status == 1){
            $rules['g-recaptcha-response'] = new Captcha();
        }

        $customMessages = [
            'email.required' => trans('Email is required'),
            'email.email' => trans('Please provide valid email'),
            'password.required' => trans('Password is required'),
            'password.min' => trans('Password must be at least 4 characters'),
        ];

        $this->validate($request, $rules, $customMessages);

        $credential = [
            'email' => $request->email,
            'password' => $request->password,
            'status' => 1,
        ];

        $user = User::where('email', $request->email)->first();
        if($user){
            if($user->email_verified_at == null){
                $notification = trans('Please verify your email');
                return response()->json(['error' => $notification], 403);
            }
            if($user->status == 0){
                $notification = trans('Inactive account');
                return response()->json(['error' => $notification], 403);
            }
        } else {
            $notification = trans('Invalid credentials');
            return response()->json(['error' => $notification], 403);
        }

        if(Auth::attempt($credential, $request->remember)){
            $notification = trans('Login Successfully');
            $redirect = $request->input('redirect');
            if ($redirect && filter_var($redirect, FILTER_VALIDATE_URL) && str_starts_with($redirect, url('/'))) {
                $redirectTo = $redirect;
            } else {
                $redirectTo = route('user.dashboard');
            }

            return response()->json([
                'success' => $notification,
                'redirect' => $redirectTo,
            ]);
        } else {
            $notification = trans('Invalid credentials');
            return response()->json(['error' => $notification], 403);
        }
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        $setting = Setting::first();
        $bannerImage = BannerImage::whereId('15')->first();
        $googleRecaptcha = GoogleRecaptcha::first();
        
        return view('frontend.auth.forgot-password', compact('setting', 'bannerImage', 'googleRecaptcha'));
    }

    /**
     * Send forgot password email
     */
    public function sendForgotPassword(Request $request)
    {
        $rules = [
            'email' => 'required|email',
        ];

        $googleRecaptcha = GoogleRecaptcha::first();
        if($googleRecaptcha->status == 1){
            $rules['g-recaptcha-response'] = new Captcha();
        }

        $customMessages = [
            'email.required' => trans('Email is required'),
            'email.email' => trans('Please provide valid email'),
        ];

        $this->validate($request, $rules, $customMessages);

        $user = User::where('email', $request->email)->first();
        if($user){
            $user->forget_password_token = Str::random(100);
            $user->save();

            MailHelper::setMailConfig();
            $template = EmailTemplate::where('id', 1)->first();
            $subject = $template->subject;
            $message = $template->description;
            $message = str_replace('{{user_name}}', $user->name, $message);
            Mail::to($user->email)->send(new UserForgetPassword($message, $subject, $user));

            $notification = trans('Reset password link send to your email');
            $notification = array('messege' => $notification, 'alert-type' => 'success');
            return redirect()->back()->with($notification);
        } else {
            $notification = trans('Email does not exist');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->back()->with($notification);
        }
    }

    /**
     * Send forgot password email (AJAX)
     */
    public function sendForgotPasswordAjax(Request $request)
    {
        $rules = [
            'email' => 'required|email',
        ];

        $googleRecaptcha = GoogleRecaptcha::first();
        if($googleRecaptcha->status == 1){
            $rules['g-recaptcha-response'] = new Captcha();
        }

        $customMessages = [
            'email.required' => trans('Email is required'),
            'email.email' => trans('Please provide valid email'),
        ];

        $this->validate($request, $rules, $customMessages);

        $user = User::where('email', $request->email)->first();
        if($user){
            $user->forget_password_token = Str::random(100);
            $user->save();

            MailHelper::setMailConfig();
            $template = EmailTemplate::where('id', 1)->first();
            $subject = $template->subject;
            $message = $template->description;
            $message = str_replace('{{user_name}}', $user->name, $message);
            Mail::to($user->email)->send(new UserForgetPassword($message, $subject, $user));

            $notification = trans('Reset password link send to your email');
            return response()->json(['success' => $notification]);
        } else {
            $notification = trans('Email does not exist');
            return response()->json(['error' => $notification], 403);
        }
    }

    /**
     * Show reset password form
     */
    public function showResetPasswordForm($token)
    {
        $user = User::where('forget_password_token', $token)->first();
        if(!$user){
            $notification = trans('Invalid token');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->route('login')->with($notification);
        }

        $setting = Setting::first();
        $bannerImage = BannerImage::whereId('15')->first();
        $googleRecaptcha = GoogleRecaptcha::first();
        $email = $user->email;
        
        return view('frontend.auth.reset-password', compact('setting', 'bannerImage', 'token', 'email', 'googleRecaptcha'));
    }

    /**
     * Store new password
     */
    public function storeResetPassword(Request $request, $token)
    {
        $rules = [
            'password' => 'required|min:4|confirmed',
        ];

        $customMessages = [
            'password.required' => trans('Password is required'),
            'password.min' => trans('Password must be at least 4 characters'),
            'password.confirmed' => trans('Confirm password does not match'),
        ];

        $this->validate($request, $rules, $customMessages);

        $user = User::where('forget_password_token', $token)->first();
        if(!$user){
            $notification = trans('Invalid token');
            return response()->json(['error' => $notification], 403);
        }

        $user->password = Hash::make($request->password);
        $user->forget_password_token = null;
        $user->save();

        $notification = trans('Password reset successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('login')->with($notification);
    }

    /**
     * Logout user
     */
    public function logout()
    {
        Auth::logout();
        $notification = trans('Logout Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('home')->with($notification);
    }

    /**
     * Redirect to Google
     */
    public function redirectToGoogle()
    {
        SocialLoginInformation::setGoogleLoginInfo();
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google callback
     */
    public function googleCallback()
    {
        try {
            SocialLoginInformation::setGoogleLoginInfo();
            $user = Socialite::driver('google')->user();
            $user = $this->createUser($user, 'google');
            Auth::login($user);
            return redirect()->intended(route('user.dashboard'));
        } catch (\Exception $e) {
            $notification = trans('Something went wrong');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->route('login')->with($notification);
        }
    }

    /**
     * Redirect to Facebook
     */
    public function redirectToFacebook()
    {
        SocialLoginInformation::setFacebookLoginInfo();
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Handle Facebook callback
     */
    public function facebookCallback()
    {
        try {
            SocialLoginInformation::setFacebookLoginInfo();
            $user = Socialite::driver('facebook')->user();
            $user = $this->createUser($user, 'facebook');
            Auth::login($user);
            return redirect()->intended(route('user.dashboard'));
        } catch (\Exception $e) {
            $notification = trans('Something went wrong');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->route('login')->with($notification);
        }
    }

    /**
     * Create user from social login
     */
    private function createUser($getInfo, $provider)
    {
        $user = User::where('provider_id', $getInfo->id)->first();
        if (!$user) {
            $user = User::create([
                'name' => $getInfo->name,
                'email' => $getInfo->email,
                'provider' => $provider,
                'provider_id' => $getInfo->id,
                'status' => 1,
                'email_verified_at' => Carbon::now(),
            ]);
        }
        return $user;
    }
}