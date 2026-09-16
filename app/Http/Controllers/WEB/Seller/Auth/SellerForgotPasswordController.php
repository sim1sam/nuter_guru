<?php

namespace App\Http\Controllers\WEB\Seller\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

use Illuminate\Http\Request;
use App\Mail\SellerForgetPassword;
use App\Helpers\MailHelper;
use App\Models\Seller;
use App\Models\EmailTemplate;
use Str;
use Mail;
use Hash;
use Auth;
use App\Models\Setting;
class SellerForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function forgetPassword(){
        $setting = Setting::first();
       return view('seller.auth.forget',compact('setting'));
   }


   public function sendForgetEmail(Request $request){

        $rules = [
            'email'=>'required'
        ];

        $customMessages = [
            'email.required' => trans('admin_validation.Email is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $seller=Seller::where('email',$request->email)->first();
        if($seller){
            $seller->forget_password_token = random_int(100000, 999999);
            $seller->save();

            $template=EmailTemplate::where('id',1)->first();
            $message=$template->description;
            $subject=$template->subject;
            $message=str_replace('{{name}}',$seller->name,$message);

            $mailSent = MailHelper::sendTo($seller->email, new SellerForgetPassword($seller,$message,$subject));

            $notification= trans('admin_validation.Forget password link send your email');
            if (! $mailSent) {
                $notification .= ' | '.MailHelper::notSentMessage();
            }
            return response()->json(['notification' => $notification, 'alert-type' => $mailSent ? 'success' : 'warning'],200);

        }else {
            $notification= trans('admin_validation.email does not exist');
            return response()->json(['notification' => $notification],400);
        }
    }


    public function resetPassword($token){
        $seller=Seller::where('forget_password_token',$token)->first();
        if($seller){
            $setting = Setting::first();
            return view('seller.auth.reset_password',compact('seller','token','setting'));
        }else{
            $notification='Invalid token';
            return redirect()->route('seller.forget.password')->with('error', $notification);
        }
    }

    public function storeResetData(Request $request, $token){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:4|confirmed',
        ]);

        $seller = Seller::where(['email' => $request->email, 'forget_password_token' => $token])->first();
        if($seller){
            $seller->password = Hash::make($request->password);
            $seller->forget_password_token = null;
            $seller->save();
            $notification = 'Password reset successfully';
            return response()->json(['success' => $notification]);
        }else{
            $notification = 'Invalid token or email';
            return response()->json(['error' => $notification]);
        }
    }


}
