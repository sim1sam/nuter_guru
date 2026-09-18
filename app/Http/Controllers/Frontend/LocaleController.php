<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function switch(Request $request, string $locale): RedirectResponse
    {
        if (! in_array($locale, ['bn', 'en'], true)) {
            $locale = 'bn';
        }

        session(['locale' => $locale]);
        session()->save();

        $redirect = $request->query('redirect');
        if (
            is_string($redirect)
            && $redirect !== ''
            && str_starts_with($redirect, '/')
            && ! str_starts_with($redirect, '//')
        ) {
            return redirect()->to($redirect);
        }

        $previous = url()->previous();
        $fallback = url('/');

        // Avoid redirect loops back to the locale switch URL (common in PWA / missing referrer)
        if (
            ! $previous
            || $previous === $request->fullUrl()
            || str_contains($previous, '/locale/')
        ) {
            return redirect()->to($fallback);
        }

        return redirect()->to($previous);
    }
}
