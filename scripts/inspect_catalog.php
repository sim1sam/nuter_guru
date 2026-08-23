<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;
use App\Models\Product;

foreach (Category::where('status', 1)->orderBy('id')->get() as $c) {
    echo "C{$c->id} | {$c->name} | {$c->slug}" . PHP_EOL;
}

echo PHP_EOL . 'All products:' . PHP_EOL;
foreach (Product::orderBy('id')->get(['id', 'name', 'category_id', 'thumb_image']) as $p) {
    echo "P{$p->id} | cat:{$p->category_id} | {$p->name} | {$p->thumb_image}" . PHP_EOL;
}
