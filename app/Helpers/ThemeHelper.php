<?php

namespace App\Helpers;

use App\Models\Setting;

class ThemeHelper
{
    public static function variables(?Setting $setting = null): array
    {
        $setting = $setting ?: Setting::first();

        $primary = self::normalizeHex($setting->theme_one ?? null, '#B05B36');
        $secondary = self::normalizeHex($setting->theme_two ?? null, '#F3F8F2');
        $background = self::normalizeHex($setting->background_color ?? null, '#FFFFFF');
        $statisticsBg = self::normalizeHex($setting->statistics_color ?? null, '#2D6A4F');
        $statisticsFont = self::normalizeHex($setting->statistics_font_color ?? null, '#ffffff');
        $navbarMenu = self::normalizeHex($setting->navbar_menu_color ?? null, '#333333');
        $navbarMenuActive = self::normalizeHex($setting->navbar_menu_active_color ?? null, '#2D6A4F');
        $navbarBg = self::normalizeHex($setting->navbar_bg_color ?? null, '#FFFFFF');
        $navbarMenuRgb = self::hexToRgbString($navbarMenuActive);

        $accent = self::adjustHex($primary, 18);
        $textDark = self::mixHex($primary, '#2D2A3A', 0.72);
        $textLight = self::mixHex($primary, '#8B8B9A', 0.55);
        $bgElegant = self::adjustHex($background, -4);
        $borderColor = self::mixHex($background, $primary, 0.22);
        $lightPurple = self::adjustHex($background, -6);
        $darkPurple = self::adjustHex($primary, -22);
        $primaryRgb = self::hexToRgbString($primary);

        return [
            'primary' => $primary,
            'secondary' => $secondary,
            'background' => $background,
            'accent' => $accent,
            'text_dark' => $textDark,
            'text_light' => $textLight,
            'bg_light' => $background,
            'bg_elegant' => $bgElegant,
            'border_color' => $borderColor,
            'light_purple' => $lightPurple,
            'dark_purple' => $darkPurple,
            'primary_rgb' => $primaryRgb,
            'statistics_color' => $statisticsBg,
            'statistics_font_color' => $statisticsFont,
            'navbar_menu_color' => $navbarMenu,
            'navbar_menu_active_color' => $navbarMenuActive,
            'navbar_bg_color' => $navbarBg,
            'navbar_menu_rgb' => $navbarMenuRgb,
            'gradient_bg' => sprintf(
                'linear-gradient(135deg, %s 0%%, %s 50%%, %s 100%%)',
                $background,
                $bgElegant,
                $lightPurple
            ),
            'shadow' => "0 2px 15px rgba({$primaryRgb}, 0.15)",
            'soft_shadow' => "rgba({$primaryRgb}, 0.25)",
        ];
    }

    public static function normalizeHex(?string $hex, string $fallback): string
    {
        $hex = trim((string) $hex);

        if ($hex === '') {
            return strtoupper($fallback);
        }

        if ($hex[0] !== '#') {
            $hex = '#' . $hex;
        }

        if (! preg_match('/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/', $hex)) {
            return strtoupper($fallback);
        }

        if (strlen($hex) === 4) {
            $hex = sprintf(
                '#%s%s%s%s%s%s',
                $hex[1],
                $hex[1],
                $hex[2],
                $hex[2],
                $hex[3],
                $hex[3]
            );
        }

        return strtoupper($hex);
    }

    public static function hexToRgb(string $hex): array
    {
        $hex = ltrim(self::normalizeHex($hex, '#000000'), '#');

        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }

    public static function hexToRgbString(string $hex): string
    {
        [$r, $g, $b] = self::hexToRgb($hex);

        return "{$r}, {$g}, {$b}";
    }

    public static function rgbToHex(int $r, int $g, int $b): string
    {
        return sprintf(
            '#%02X%02X%02X',
            max(0, min(255, $r)),
            max(0, min(255, $g)),
            max(0, min(255, $b))
        );
    }

    public static function adjustHex(string $hex, int $percent): string
    {
        [$r, $g, $b] = self::hexToRgb($hex);
        $amount = $percent / 100;

        if ($amount >= 0) {
            $r = (int) round($r + (255 - $r) * $amount);
            $g = (int) round($g + (255 - $g) * $amount);
            $b = (int) round($b + (255 - $b) * $amount);
        } else {
            $amount = abs($amount);
            $r = (int) round($r * (1 - $amount));
            $g = (int) round($g * (1 - $amount));
            $b = (int) round($b * (1 - $amount));
        }

        return self::rgbToHex($r, $g, $b);
    }

    public static function mixHex(string $hex1, string $hex2, float $weight = 0.5): string
    {
        [$r1, $g1, $b1] = self::hexToRgb($hex1);
        [$r2, $g2, $b2] = self::hexToRgb($hex2);
        $weight = max(0, min(1, $weight));

        return self::rgbToHex(
            (int) round($r1 * (1 - $weight) + $r2 * $weight),
            (int) round($g1 * (1 - $weight) + $g2 * $weight),
            (int) round($b1 * (1 - $weight) + $b2 * $weight)
        );
    }
}
