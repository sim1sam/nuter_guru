<?php

/**
 * Generate square PWA icons from the site logo.
 * Run: php scripts/generate-pwa-icons.php
 */

$dir = __DIR__ . '/../public/frontend/pwa';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

$logoCandidates = [
    __DIR__ . '/../public/uploads/website-images/logo-nuter-guru.png',
];

// Prefer current logo from settings when available via a simple DB-less path scan
$uploadDir = __DIR__ . '/../public/uploads/website-images';
if (is_dir($uploadDir)) {
    foreach (glob($uploadDir . '/logo*.{png,jpg,jpeg,webp}', GLOB_BRACE) ?: [] as $file) {
        array_unshift($logoCandidates, $file);
    }
}

$logoPath = null;
foreach ($logoCandidates as $candidate) {
    if (is_file($candidate) && @getimagesize($candidate)) {
        $logoPath = $candidate;
        break;
    }
}

function loadImage(string $path)
{
    $info = @getimagesize($path);
    if (!$info) {
        return null;
    }

    return match ($info[2]) {
        IMAGETYPE_PNG => @imagecreatefrompng($path),
        IMAGETYPE_JPEG => @imagecreatefromjpeg($path),
        IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : null,
        default => null,
    };
}

foreach ([192, 512] as $size) {
    $canvas = imagecreatetruecolor($size, $size);
    imagealphablending($canvas, false);
    imagesavealpha($canvas, true);

    // Black background to match brand logo plate
    $bg = imagecolorallocate($canvas, 0, 0, 0);
    imagefilledrectangle($canvas, 0, 0, $size, $size, $bg);
    imagealphablending($canvas, true);

    if ($logoPath) {
        $src = loadImage($logoPath);
        if ($src) {
            $srcW = imagesx($src);
            $srcH = imagesy($src);
            $padding = (int) ($size * 0.08);
            $maxW = $size - ($padding * 2);
            $maxH = $size - ($padding * 2);
            $scale = min($maxW / $srcW, $maxH / $srcH);
            $dstW = max(1, (int) round($srcW * $scale));
            $dstH = max(1, (int) round($srcH * $scale));
            $dstX = (int) (($size - $dstW) / 2);
            $dstY = (int) (($size - $dstH) / 2);

            imagecopyresampled($canvas, $src, $dstX, $dstY, 0, 0, $dstW, $dstH, $srcW, $srcH);
            imagedestroy($src);
        }
    }

    $out = $dir . '/icon-' . $size . '.png';
    imagepng($canvas, $out, 6);
    imagedestroy($canvas);
    echo "Wrote {$out}\n";
}

echo $logoPath ? "Source logo: {$logoPath}\n" : "No logo found; solid icons written.\n";
