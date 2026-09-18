<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\ThemeHelper;
use App\Http\Controllers\Controller;
use App\Models\SeoSetting;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class PwaController extends Controller
{
    public function manifest(): JsonResponse
    {
        $setting = Setting::first();
        $seo = SeoSetting::first();
        $theme = ThemeHelper::variables($setting);

        $appName = "Nut'er Guru BD";
        $shortName = "Nut'er Guru";
        $description = $seo?->seo_description
            ?? "Shop pure dry foods, nuts and spices at Nut'er Guru BD.";

        return response()->json([
            'id' => '/',
            'name' => $appName,
            'short_name' => $shortName,
            'description' => $description,
            'lang' => app()->getLocale() ?: 'bn',
            'dir' => 'ltr',
            'start_url' => '/?utm_source=pwa',
            'scope' => '/',
            'display' => 'standalone',
            'orientation' => 'portrait-primary',
            'background_color' => '#000000',
            'theme_color' => $theme['primary'] ?? '#F58220',
            'prefer_related_applications' => false,
            'categories' => ['shopping', 'lifestyle'],
            'icons' => $this->buildIcons(),
        ], 200, [
            'Content-Type' => 'application/manifest+json',
            'Cache-Control' => 'no-cache, must-revalidate',
        ]);
    }

    private function buildIcons(): array
    {
        $icons = [];

        foreach ([192, 512] as $size) {
            $relative = "frontend/pwa/icon-{$size}.png";
            $full = public_path($relative);
            if (! is_file($full)) {
                continue;
            }

            // Root-relative path so the install icon works regardless of APP_URL
            $src = '/' . ltrim($relative, '/');

            $icons[] = [
                'src' => $src,
                'sizes' => "{$size}x{$size}",
                'type' => 'image/png',
                'purpose' => 'any',
            ];
            $icons[] = [
                'src' => $src,
                'sizes' => "{$size}x{$size}",
                'type' => 'image/png',
                'purpose' => 'maskable',
            ];
        }

        return $icons;
    }
}
