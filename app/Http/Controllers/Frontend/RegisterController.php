<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Setting;
use App\Models\BannerImage;
use App\Models\GoogleRecaptcha;
use App\Rules\Captcha;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegistration;
use App\Helpers\MailHelper;
use App\Models\EmailTemplate;
use App\Models\SmsTemplate;
use App\Models\TwilioSms;
use Twilio\Rest\Client;
use Exception;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the registration form
     */
    public function showRegistrationForm()
    {
        $setting = Setting::first();
        $bannerImage = BannerImage::whereId('15')->first();
        $googleRecaptcha = GoogleRecaptcha::first();
        
        return view('frontend.auth.register', compact('setting', 'bannerImage', 'googleRecaptcha'));
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $setting = Setting::first();
        $enable_phone_required = $setting->phone_number_required;

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => $enable_phone_required == 1 ? 'required|unique:users' : 'nullable',
            'password' => 'required|min:4|confirmed',
            'agree' => 'required',
        ];

        $googleRecaptcha = GoogleRecaptcha::first();
        if($googleRecaptcha->status == 1){
            $rules['g-recaptcha-response'] = new Captcha();
        }

        $customMessages = [
            'name.required' => trans('Name is required'),
            'email.required' => trans('Email is required'),
            'email.unique' => trans('Email already exists'),
            'password.required' => trans('Password is required'),
            'password.min' => trans('Password must be at least 4 characters'),
            'password.confirmed' => trans('Confirm password does not match'),
            'agree.required' => trans('You must agree to the terms and conditions'),
            'phone.required' => trans('Phone number is required'),
            'phone.unique' => trans('Phone number already exists'),
        ];

        $this->validate($request, $rules, $customMessages);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone ? $request->phone : '';
        $user->agree_policy = $request->agree ? 1 : 0;
        $user->password = Hash::make($request->password);
        // No email verification required — activate account immediately
        $user->status = 1;
        $user->email_verified = 1;
        $user->email_verified_at = now();
        $user->save();

        // Optional welcome SMS only (never block registration on mail/SMS failure)
        $this->sendRegistrationSMS($user);

        $notification = trans('Registration successful. You can login now.');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('login')->with($notification);
    }

    /**
     * Verify user email
     */
    /**
     * Resend verification code
     */
    public function resendVerificationCode(Request $request)
    {
        $rules = [
            'email' => 'required|email',
        ];

        $customMessages = [
            'email.required' => trans('Email is required'),
            'email.email' => trans('Please provide valid email'),
        ];

        $this->validate($request, $rules, $customMessages);

        $user = User::where('email', $request->email)->first();
        if($user){
            if($user->hasVerifiedEmail()){
                $notification = trans('Email already verified');
                return response()->json(['error' => $notification], 403);
            }

            try {
                $user->sendEmailVerificationNotification();
            } catch (\Throwable $e) {
                report($e);
                return response()->json(['error' => trans('Unable to send email right now')], 503);
            }

            $notification = trans('Verification email sent successfully');
            return response()->json(['success' => $notification]);
        } else {
            $notification = trans('Email does not exist');
            return response()->json(['error' => $notification], 403);
        }
    }

    /**
     * Send registration SMS
     */
    private function sendRegistrationSMS($user)
    {
        try {
            $setting = Setting::first();
            $template = SmsTemplate::where('id', 1)->first();
            $message = $template->description;
            $message = str_replace('{{user_name}}', $user->name, $message);

            if($setting->enable_twilio_sms == 1){
                $twilio = TwilioSms::first();
                $sid = $twilio->account_sid;
                $token = $twilio->auth_token;
                $from = $twilio->twilio_phone_number;
                $client = new Client($sid, $token);
                
                if($user->phone){
                    $client->messages->create($user->phone, [
                        'from' => $from,
                        'body' => $message
                    ]);
                }
            }
        } catch (Exception $e) {
            // Log error but don't stop registration process
        }
    }

    /**
     * Show the email verification notice
     */
    public function showVerifyEmailForm()
    {
        $setting = Setting::first();
        return view('frontend.auth.verify', compact('setting'));
    }

    /**
     * Verify email using Laravel's built-in verification
     */
    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);
        
        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            $notification = trans('Invalid verification link');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->route('login')->with($notification);
        }
        
        if ($user->hasVerifiedEmail()) {
            $notification = trans('Email already verified');
            $notification = array('messege' => $notification, 'alert-type' => 'info');
            return redirect()->route('login')->with($notification);
        }
        
        $user->markEmailAsVerified();
        $user->status = 1;
        $user->save();
        
        $notification = trans('Email verification successful');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('login')->with($notification);
    }

    /**
     * Resend verification email
     */
    public function resendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        try {
            $request->user()->sendEmailVerificationNotification();
        } catch (\Throwable $e) {
            // Mail may be unavailable — do not fail with 500
            report($e);
        }

        return back()->with('resent', true);
    }
}