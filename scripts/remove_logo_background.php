<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$source = public_path('uploads/website-images/logo-nuter-guru.jpg');
$output = public_path('uploads/website-images/logo-nuter-guru.png');

if (! file_exists($source)) {
    echo "Source logo not found: {$source}\n";
    exit(1);
}

$src = @imagecreatefromjpeg($source);
if (! $src) {
    $src = @imagecreatefrompng($source);
}

if (! $src) {
    echo "Could not load logo image.\n";
    exit(1);
}

$width = imagesx($src);
$height = imagesy($src);

$dest = imagecreatetruecolor($width, $height);
imagealphablending($dest, false);
imagesavealpha($dest, true);

$transparent = imagecolorallocatealpha($dest, 0, 0, 0, 127);
imagefill($dest, 0, 0, $transparent);

$threshold = 55;

for ($x = 0; $x < $width; $x++) {
    for ($y = 0; $y < $height; $y++) {
        $rgb = imagecolorat($src, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;

        if ($r <= $threshold && $g <= $threshold && $b <= $threshold) {
            imagesetpixel($dest, $x, $y, $transparent);
            continue;
        }

        $color = imagecolorallocatealpha($dest, $r, $g, $b, 0);
        imagesetpixel($dest, $x, $y, $color);
    }
}

imagepng($dest, $output, 9);
imagedestroy($src);
imagedestroy($dest);

$s = App\Models\Setting::first();
if ($s) {
    $s->logo = 'uploads/website-images/logo-nuter-guru.png';
    $s->save();
}

echo "Transparent logo saved: {$output}\n";
