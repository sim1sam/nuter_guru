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

        $appName = $seo?->seo_title ?? config('app.name', 'Nuter Guru');
        $shortName = mb_strlen($appName) > 12 ? mb_substr($appName, 0, 12) : $appName;
        $description = $seo?->seo_description ?? 'Shop online with Nuter Guru — fast, easy, and secure.';

        $icons = $this->buildIcons($setting);

        return response()->json([
            'name' => $appName,
            'short_name' => $shortName,
            'description' => $description,
            'start_url' => url('/'),
            'scope' => url('/'),
            'display' => 'standalone',
            'orientation' => 'portrait-primary',
            'background_color' => $theme['background'],
            'theme_color' => $theme['primary'],
            'categories' => ['shopping', 'lifestyle'],
            'icons' => $icons,
        ], 200, [
            'Content-Type' => 'application/manifest+json',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    private function buildIcons(?Setting $setting): array
    {
        $icons = [];
        $logo = $setting?->logo ?? null;

        if ($logo && $this->isPublicImage($logo)) {
            $logoUrl = asset($logo);
            foreach ([192, 512] as $size) {
                $icons[] = [
                    'src' => $logoUrl,
                    'sizes' => "{$size}x{$size}",
                    'type' => $this->mimeFromPath($logo),
                    'purpose' => 'any',
                ];
            }
        }

        foreach ([192, 512] as $size) {
            $icons[] = [
                'src' => asset("frontend/pwa/icon-{$size}.png"),
                'sizes' => "{$size}x{$size}",
                'type' => 'image/png',
                'purpose' => 'any maskable',
            ];
        }

        return $icons;
    }

    private function isPublicImage(string $path): bool
    {
        $fullPath = public_path($path);

        return is_file($fullPath) && (bool) @getimagesize($fullPath);
    }

    private function mimeFromPath(string $path): string
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return match ($ext) {
            'png' => 'image/png',
            'webp' => 'image/webp',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            default => 'image/jpeg',
        };
    }
}
