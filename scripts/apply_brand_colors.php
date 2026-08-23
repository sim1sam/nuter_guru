<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$s = App\Models\Setting::first();
if (!$s) {
    echo "No setting found\n";
    exit(1);
}

$s->theme_one = '#F58220';
$s->theme_two = '#F8F4F0';
$s->brand_brown = '#5C3317';
$s->accent_color = '#6AB344';
$s->background_color = '#FFFFFF';
$s->statistics_color = '#6AB344';
$s->navbar_menu_active_color = '#6AB344';
$s->navbar_menu_color = '#333333';
$s->logo = 'uploads/website-images/logo-nuter-guru.jpg';
$s->save();

echo "Brand colors applied.\n";
