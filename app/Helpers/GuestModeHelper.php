<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class GuestModeHelper
{
    public static function isEnabled(?Setting $setting = null): bool
    {
        $setting = $setting ?: Setting::first();

        return (int) ($setting->enable_guest_mode ?? 0) === 1;
    }

    public static function guestCartAllowed(): bool
    {
        return Auth::check() || self::isEnabled();
    }

    public static function loginRequiredResponse(string $message = 'Please login to continue.')
    {
        return response()->json([
            'success' => false,
            'login_required' => true,
            'message' => $message,
            'login_url' => route('login'),
        ], 401);
    }
}
