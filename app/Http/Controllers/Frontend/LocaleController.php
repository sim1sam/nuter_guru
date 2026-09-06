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

        return redirect()->back();
    }
}
