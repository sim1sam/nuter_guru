<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\SteadfastSetting;
use Illuminate\Http\Request;

class SteadfastSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $setting = SteadfastSetting::current();

        return view('admin.steadfast_setting', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'api_key' => 'nullable|string|max:255',
            'secret_key' => 'nullable|string|max:255',
            'base_url' => 'nullable|url|max:255',
        ]);

        $setting = SteadfastSetting::current();
        $setting->api_key = $request->api_key;
        $setting->secret_key = $request->secret_key;
        $setting->base_url = $request->base_url ?: 'https://portal.packzy.com/api/v1';
        $setting->status = $request->status ? 1 : 0;
        $setting->save();

        $notification = [
            'messege' => trans('admin_validation.Update Successfully'),
            'alert-type' => 'success',
        ];

        return redirect()->back()->with($notification);
    }
}
