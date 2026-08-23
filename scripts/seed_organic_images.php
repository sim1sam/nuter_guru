<?php

/**
 * Seed Nuter Guru with real dry food images (nuts, peanuts, seeds, dry fruits).
 * Run: php scripts/seed_organic_images.php
 */

use App\Models\BannerImage;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\Slider;
use Illuminate\Support\Str;

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$custom = public_path('uploads/custom-images');
$organic = public_path('uploads/organic');

if (! is_dir($custom)) {
    mkdir($custom, 0755, true);
}

// Source files in uploads/organic (must be real dry-food photos)
$fileMap = [
    'slider-dry-fruits.jpg' => 'hero-dryfruits.jpg',
    'slider-nuts.jpg' => 'hero-nuts.jpg',
    'slider-seeds.jpg' => 'hero-seeds.jpg',
    'banner-dry-fruits.jpg' => 'hero-dryfruits.jpg',
    'banner-nuts.jpg' => 'hero-nuts.jpg',
    'banner-peanuts.jpg' => 'prod-peanuts.jpg',
    'banner-seeds.jpg' => 'hero-seeds.jpg',
    'cat-dry-fruits.jpg' => 'cat-dry-fruits.jpg',
    'cat-nuts.jpg' => 'cat-nuts.jpg',
    'cat-spices.jpg' => 'prod-spices.jpg',
    'cat-seeds.jpg' => 'cat-seeds.jpg',
    'cat-honey.jpg' => 'prod-honey.jpg',
    'cat-dates.jpg' => 'cat-dates.jpg',
    'prod-almonds.jpg' => 'prod-almonds.jpg',
    'prod-cashews.jpg' => 'prod-cashews.jpg',
    'prod-peanuts.jpg' => 'prod-peanuts.jpg',
    'prod-walnuts.jpg' => 'prod-walnuts.jpg',
    'prod-pistachios.jpg' => 'prod-pistachios.jpg',
    'prod-raisins.jpg' => 'prod-raisins.jpg',
    'prod-dates.jpg' => 'prod-dates.jpg',
    'prod-mixed-dry.jpg' => 'prod-mixed-dry.jpg',
    'prod-seeds.jpg' => 'prod-seeds.jpg',
    'prod-honey.jpg' => 'prod-honey.jpg',
    'prod-spices.jpg' => 'prod-spices.jpg',
];

foreach ($fileMap as $dest => $src) {
    $from = $organic . DIRECTORY_SEPARATOR . $src;
    $to = $custom . DIRECTORY_SEPARATOR . $dest;
    if (! is_file($from) || filesize($from) < 5000) {
        echo "SKIP missing: {$src}\n";
        continue;
    }
    copy($from, $to);
}

$img = fn (string $file): string => 'uploads/custom-images/' . $file;

// 1) Homepage sliders — nuts, peanuts, seeds, dry fruits
$sliderData = [
    1 => [
        'title_one' => 'Premium Dry Fruits',
        'title_two' => 'Raisins, dates, apricots & mixed dry fruits — 100% organic',
        'image' => $img('slider-dry-fruits.jpg'),
    ],
    2 => [
        'title_one' => 'Fresh Nuts & Peanuts',
        'title_two' => 'Almonds, cashews, pistachios, walnuts & roasted peanuts',
        'image' => $img('slider-nuts.jpg'),
    ],
    3 => [
        'title_one' => 'Organic Seeds & Spices',
        'title_two' => 'Sunflower seeds, chia, flax & natural spices',
        'image' => $img('slider-seeds.jpg'),
    ],
];

foreach (Slider::all() as $slider) {
    if (isset($sliderData[$slider->id])) {
        $slider->fill($sliderData[$slider->id] + ['status' => 1]);
    } else {
        $slider->image = $img('slider-dry-fruits.jpg');
        $slider->status = 0;
    }
    $slider->save();
    echo "Slider {$slider->id}: {$slider->title_one}\n";
}

// 2) Categories
$organicCats = [
    ['name' => 'Dry Fruits', 'slug' => 'dry-fruits', 'image' => $img('cat-dry-fruits.jpg')],
    ['name' => 'Nuts', 'slug' => 'nuts', 'image' => $img('cat-nuts.jpg')],
    ['name' => 'Spices', 'slug' => 'spices', 'image' => $img('cat-spices.jpg')],
    ['name' => 'Seeds', 'slug' => 'seeds', 'image' => $img('cat-seeds.jpg')],
    ['name' => 'Honey', 'slug' => 'honey', 'image' => $img('cat-honey.jpg')],
    ['name' => 'Dates', 'slug' => 'dates', 'image' => $img('cat-dates.jpg')],
];

$categories = Category::orderBy('id')->take(6)->get();
foreach ($categories as $i => $category) {
    if (! isset($organicCats[$i])) {
        break;
    }
    $data = $organicCats[$i];
    $slugTaken = Category::where('slug', $data['slug'])->where('id', '!=', $category->id)->exists();
    $category->name = $data['name'];
    $category->slug = $slugTaken ? ($data['slug'] . '-' . $category->id) : $data['slug'];
    $category->image = $data['image'];
    $category->status = 1;
    $category->save();
    echo "Category {$category->id}: {$category->name}\n";
}

Category::whereNotIn('id', $categories->pluck('id'))->update(['status' => 0]);

// 3) Homepage promo banners
$bannerData = [
    16 => ['title_one' => 'Dry Fruits', 'title_two' => 'Raisins, apricots & mixed organic dry fruits', 'image' => $img('banner-dry-fruits.jpg'), 'product_slug' => 'dry-fruits'],
    17 => ['title_one' => 'Premium Nuts', 'title_two' => 'Almonds, cashews & pistachios', 'image' => $img('banner-nuts.jpg'), 'product_slug' => 'nuts'],
    18 => ['title_one' => 'Roasted Peanuts', 'title_two' => 'Crunchy peanuts & organic seeds', 'image' => $img('banner-peanuts.jpg'), 'product_slug' => 'seeds'],
    19 => ['title_one' => 'Organic Seeds', 'title_two' => 'Sunflower, chia & flax seeds', 'image' => $img('banner-seeds.jpg'), 'product_slug' => 'seeds'],
];

foreach ($bannerData as $id => $data) {
    $banner = BannerImage::find($id);
    if (! $banner) {
        continue;
    }
    $banner->title_one = $data['title_one'];
    $banner->title_two = $data['title_two'];
    $banner->image = $data['image'];
    $banner->product_slug = $data['product_slug'];
    $banner->link = route('products');
    $banner->save();
    echo "Banner {$id}: {$data['title_one']}\n";
}

// 4) Products — dry food names + category-matched images
$imagesByCategory = [
    1 => [$img('prod-raisins.jpg'), $img('prod-mixed-dry.jpg'), $img('prod-dates.jpg')],
    2 => [$img('prod-almonds.jpg'), $img('prod-cashews.jpg'), $img('prod-peanuts.jpg'), $img('prod-walnuts.jpg'), $img('prod-pistachios.jpg')],
    3 => [$img('prod-spices.jpg'), $img('prod-mixed-dry.jpg')],
    4 => [$img('prod-seeds.jpg'), $img('prod-walnuts.jpg'), $img('prod-peanuts.jpg')],
    5 => [$img('prod-honey.jpg'), $img('prod-mixed-dry.jpg')],
    6 => [$img('prod-dates.jpg'), $img('prod-raisins.jpg'), $img('prod-mixed-dry.jpg')],
];

$dryProducts = [
    // Dry Fruits (cat 1)
    ['name' => 'Premium Golden Raisins', 'category_id' => 1, 'price' => 450, 'offer' => 399],
    ['name' => 'Dried Apricots', 'category_id' => 1, 'price' => 520, 'offer' => 475],
    ['name' => 'Dried Figs', 'category_id' => 1, 'price' => 680, 'offer' => 620],
    ['name' => 'Mixed Dry Fruits', 'category_id' => 1, 'price' => 890, 'offer' => 799],
    ['name' => 'Dried Mango Slices', 'category_id' => 1, 'price' => 380, 'offer' => 349],
    ['name' => 'Dried Cranberries', 'category_id' => 1, 'price' => 420, 'offer' => 389],
    ['name' => 'Prunes (Dried Plums)', 'category_id' => 1, 'price' => 360, 'offer' => 329],
    ['name' => 'Dried Blueberries', 'category_id' => 1, 'price' => 550, 'offer' => 499],
    // Nuts (cat 2)
    ['name' => 'Raw Almonds', 'category_id' => 2, 'price' => 750, 'offer' => 699],
    ['name' => 'Cashew Nuts W320', 'category_id' => 2, 'price' => 920, 'offer' => 850],
    ['name' => 'Roasted Pistachios', 'category_id' => 2, 'price' => 1100, 'offer' => 999],
    ['name' => 'Walnut Kernels', 'category_id' => 2, 'price' => 980, 'offer' => 899],
    ['name' => 'Roasted Peanuts', 'category_id' => 2, 'price' => 280, 'offer' => 249],
    ['name' => 'Salted Peanuts', 'category_id' => 2, 'price' => 260, 'offer' => 229],
    ['name' => 'Macadamia Nuts', 'category_id' => 2, 'price' => 1250, 'offer' => 1149],
    ['name' => 'Hazelnuts', 'category_id' => 2, 'price' => 720, 'offer' => 659],
    // Spices (cat 3)
    ['name' => 'Turmeric Powder', 'category_id' => 3, 'price' => 180, 'offer' => 159],
    ['name' => 'Red Chili Powder', 'category_id' => 3, 'price' => 150, 'offer' => 135],
    ['name' => 'Cumin Seeds', 'category_id' => 3, 'price' => 220, 'offer' => 199],
    ['name' => 'Coriander Powder', 'category_id' => 3, 'price' => 160, 'offer' => 145],
    ['name' => 'Black Pepper Whole', 'category_id' => 3, 'price' => 340, 'offer' => 309],
    ['name' => 'Cinnamon Sticks', 'category_id' => 3, 'price' => 280, 'offer' => 259],
    ['name' => 'Green Cardamom', 'category_id' => 3, 'price' => 650, 'offer' => 599],
    ['name' => 'Whole Cloves', 'category_id' => 3, 'price' => 320, 'offer' => 289],
    // Seeds (cat 4)
    ['name' => 'Sunflower Seeds', 'category_id' => 4, 'price' => 240, 'offer' => 219],
    ['name' => 'Pumpkin Seeds', 'category_id' => 4, 'price' => 350, 'offer' => 319],
    ['name' => 'Chia Seeds', 'category_id' => 4, 'price' => 420, 'offer' => 389],
    ['name' => 'Flax Seeds', 'category_id' => 4, 'price' => 180, 'offer' => 165],
    ['name' => 'White Sesame Seeds', 'category_id' => 4, 'price' => 260, 'offer' => 239],
    ['name' => 'Watermelon Seeds', 'category_id' => 4, 'price' => 290, 'offer' => 269],
    ['name' => 'Hemp Seeds', 'category_id' => 4, 'price' => 480, 'offer' => 449],
    ['name' => 'Mixed Seeds Blend', 'category_id' => 4, 'price' => 390, 'offer' => 359],
    // Honey (cat 5)
    ['name' => 'Pure Forest Honey', 'category_id' => 5, 'price' => 580, 'offer' => 529],
    ['name' => 'Sidr Honey', 'category_id' => 5, 'price' => 1200, 'offer' => 1099],
    ['name' => 'Multiflora Honey', 'category_id' => 5, 'price' => 450, 'offer' => 419],
    ['name' => 'Organic Honey 500g', 'category_id' => 5, 'price' => 650, 'offer' => 599],
    // Dates (cat 6)
    ['name' => 'Medjool Dates', 'category_id' => 6, 'price' => 890, 'offer' => 819],
    ['name' => 'Ajwa Dates', 'category_id' => 6, 'price' => 950, 'offer' => 879],
    ['name' => 'Deglet Noor Dates', 'category_id' => 6, 'price' => 420, 'offer' => 389],
    ['name' => 'Date Syrup', 'category_id' => 6, 'price' => 380, 'offer' => 349],
];

$categoryCounters = [];

$products = Product::orderBy('id')->get();
foreach ($products as $index => $product) {
    $data = $dryProducts[$index % count($dryProducts)];
    $catId = $data['category_id'];
    $catImages = $imagesByCategory[$catId] ?? [$img('prod-mixed-dry.jpg')];
    $catIndex = $categoryCounters[$catId] ?? 0;
    $image = $catImages[$catIndex % count($catImages)];
    $categoryCounters[$catId] = $catIndex + 1;

    $baseSlug = Str::slug($data['name']);
    $slug = $baseSlug;
    $suffix = 1;
    while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
        $slug = $baseSlug . '-' . $suffix;
        $suffix++;
    }

    $desc = 'Premium organic ' . strtolower($data['name']) . ' from Nuter Guru — carefully sourced, hygienically packed.';

    $product->name = $data['name'];
    $product->short_name = Str::limit($data['name'], 30, '');
    $product->slug = $slug;
    $product->category_id = $catId;
    $product->sub_category_id = null;
    $product->child_category_id = null;
    $product->thumb_image = $image;
    $product->short_description = $desc;
    $product->long_description = '<p>' . e($desc) . '</p>';
    $product->price = $data['price'];
    $product->offer_price = $data['offer'];
    $product->tags = 'organic,dry food,nuts,seeds,nuter guru';
    $product->status = 1;
    $product->approve_by_admin = 1;
    $product->save();

    ProductGallery::where('product_id', $product->id)->update(['image' => $image]);

    echo "Product {$product->id}: {$product->name} [{$image}]\n";
}

echo "\nDone — all products, sliders & banners use dry food images.\n";
