<?php

/**
 * One-off: seed organic slider/category/product images for Nuter Guru.
 * Run: php artisan tinker --execute="require base_path('scripts/seed_organic_images.php');"
 */

use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

$custom = public_path('uploads/custom-images');
$organic = public_path('uploads/organic');

if (! is_dir($custom)) {
    mkdir($custom, 0755, true);
}

// Ensure organic copies exist in custom-images (admin upload folder)
$copies = [
    'slider-organic-1.jpg' => 'hero-1.jpg',
    'slider-organic-2.jpg' => 'hero-2.jpg',
    'slider-organic-3.jpg' => 'hero-3.jpg',
    'cat-organic-dry-fruits.jpg' => 'cat-dry-fruits.jpg',
    'cat-organic-nuts.jpg' => 'cat-nuts.jpg',
    'cat-organic-spices.jpg' => 'cat-spices.jpg',
    'cat-organic-seeds.jpg' => 'cat-seeds.jpg',
    'cat-organic-honey.jpg' => 'cat-honey.jpg',
    'cat-organic-dates.jpg' => 'cat-dates.jpg',
    'prod-organic-1.jpg' => 'prod-1.jpg',
    'prod-organic-2.jpg' => 'prod-2.jpg',
    'prod-organic-3.jpg' => 'prod-3.jpg',
    'prod-organic-4.jpg' => 'prod-4.jpg',
    'prod-organic-5.jpg' => 'prod-5.jpg',
    'prod-organic-6.jpg' => 'prod-6.jpg',
];

foreach ($copies as $dest => $src) {
    $from = $organic . DIRECTORY_SEPARATOR . $src;
    $to = $custom . DIRECTORY_SEPARATOR . $dest;
    if (is_file($from) && filesize($from) > 1000) {
        copy($from, $to);
    }
}

// 1) Replace slider images + titles; delete old jewellery slider files
$sliderData = [
    1 => [
        'title_one' => 'Premium Dry Fruits',
        'title_two' => '100% pure organic dry fruits for your healthy lifestyle',
        'image' => 'uploads/custom-images/slider-organic-1.jpg',
    ],
    2 => [
        'title_one' => 'Fresh Nuts & Seeds',
        'title_two' => 'Crunchy almonds, cashews, pistachios and more',
        'image' => 'uploads/custom-images/slider-organic-2.jpg',
    ],
    3 => [
        'title_one' => 'Natural Spices & Honey',
        'title_two' => 'Farm-fresh spices and pure honey delivered to your door',
        'image' => 'uploads/custom-images/slider-organic-3.jpg',
    ],
];

foreach (Slider::all() as $slider) {
    $old = $slider->image;
    if (isset($sliderData[$slider->id])) {
        $data = $sliderData[$slider->id];
        $slider->title_one = $data['title_one'];
        $slider->title_two = $data['title_two'];
        $slider->image = $data['image'];
        $slider->status = 1;
        $slider->save();
    } else {
        // Extra sliders: point to organic hero or disable
        $slider->image = 'uploads/custom-images/slider-organic-1.jpg';
        $slider->status = 0;
        $slider->save();
    }

    // Delete previous jewellery slider file if different path
    if ($old && $old !== ($slider->image ?? '') && ! str_contains($old, 'organic')) {
        $oldPath = public_path($old);
        if (is_file($oldPath)) {
            @unlink($oldPath);
            echo "Deleted old slider: {$old}\n";
        }
    }
}

// 2) Rename first 6 categories to organic + new images
$organicCats = [
    ['name' => 'Dry Fruits', 'slug' => 'dry-fruits', 'image' => 'uploads/custom-images/cat-organic-dry-fruits.jpg'],
    ['name' => 'Nuts', 'slug' => 'nuts', 'image' => 'uploads/custom-images/cat-organic-nuts.jpg'],
    ['name' => 'Spices', 'slug' => 'spices', 'image' => 'uploads/custom-images/cat-organic-spices.jpg'],
    ['name' => 'Seeds', 'slug' => 'seeds', 'image' => 'uploads/custom-images/cat-organic-seeds.jpg'],
    ['name' => 'Honey', 'slug' => 'honey', 'image' => 'uploads/custom-images/cat-organic-honey.jpg'],
    ['name' => 'Dates', 'slug' => 'dates', 'image' => 'uploads/custom-images/cat-organic-dates.jpg'],
];

$categories = Category::orderBy('id')->take(6)->get();
foreach ($categories as $i => $category) {
    if (! isset($organicCats[$i])) {
        break;
    }
    $data = $organicCats[$i];
    $oldImage = $category->image;

    $category->name = $data['name'];
    // Keep existing slug if unique conflict; otherwise use organic slug
    $slugTaken = Category::where('slug', $data['slug'])->where('id', '!=', $category->id)->exists();
    $category->slug = $slugTaken ? ($data['slug'] . '-' . $category->id) : $data['slug'];
    $category->image = $data['image'];
    $category->status = 1;
    $category->save();

    if ($oldImage && ! str_contains($oldImage, 'organic') && is_file(public_path($oldImage))) {
        // Keep category jewellery images deleted only if not shared — skip mass delete for safety
        // @unlink(public_path($oldImage));
    }
    echo "Category {$category->id} => {$category->name}\n";
}

// Disable remaining jewellery-named categories from homepage (status keep, but homepage only takes 6)
$jewelleryNames = ['Sets', 'Earrings', 'Rings', 'Necklaces', 'Bangles', "Tikli's", 'Tikli’s', 'Other Accessories', 'Activewear', 'Fashion Item'];
Category::whereIn('name', $jewelleryNames)->whereNotIn('id', $categories->pluck('id'))->update(['status' => 0]);

// 3) Update product thumbnails for homepage products (top / featured / newest)
$prodImages = [
    'uploads/custom-images/prod-organic-1.jpg',
    'uploads/custom-images/prod-organic-2.jpg',
    'uploads/custom-images/prod-organic-3.jpg',
    'uploads/custom-images/prod-organic-4.jpg',
    'uploads/custom-images/prod-organic-5.jpg',
    'uploads/custom-images/prod-organic-6.jpg',
];

$homepageProducts = Product::where('status', 1)
    ->where('approve_by_admin', 1)
    ->where(function ($q) {
        $q->where('is_top', 1)
            ->orWhere('is_featured', 1)
            ->orWhere('is_best', 1)
            ->orWhere('show_homepage', 1);
    })
    ->orderByDesc('id')
    ->take(24)
    ->get();

foreach ($homepageProducts as $i => $product) {
    $product->thumb_image = $prodImages[$i % count($prodImages)];
    $product->save();
}
echo 'Updated ' . $homepageProducts->count() . " product thumbs\n";

// Also update newest 12 products
Product::where('status', 1)->where('approve_by_admin', 1)->latest()->take(12)->get()->each(function ($product, $i) use ($prodImages) {
    $product->thumb_image = $prodImages[$i % count($prodImages)];
    $product->save();
});

echo "Organic image seed complete.\n";
