<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class Product extends Model
{
    use HasFactory;

    protected $appends = ['averageRating','totalSold','localized_name','localized_short_name'];

    public function getAverageRatingAttribute()
    {
        return $this->avgReview()->avg('rating') ? : '0';
    }

    public function getLocalizedNameAttribute(): string
    {
        if (app()->getLocale() === 'bn' && ! empty($this->name_bn)) {
            return $this->name_bn;
        }

        return (string) ($this->name ?? '');
    }

    public function getLocalizedShortNameAttribute(): string
    {
        if (app()->getLocale() === 'bn' && ! empty($this->short_name_bn)) {
            return $this->short_name_bn;
        }

        if (! empty($this->short_name)) {
            return (string) $this->short_name;
        }

        return $this->localized_name;
    }

    public function getTotalSoldAttribute()
    {
        return $this->orderProducts()->sum('qty');
    }

    public function orderProducts(){
        return $this->hasMany(OrderProduct::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function subCategory(){
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function seller(){
        return $this->belongsTo(Vendor::class,'vendor_id');
    }

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function gallery(){
        return $this->hasMany(ProductGallery::class);
    }

    public function specifications(){
        return $this->hasMany(ProductSpecification::class);
    }

    public function reviews(){
        return $this->hasMany(ProductReview::class);
    }


    public function variants(){
        return $this->hasMany(ProductVariant::class);
    }

    public function activeVariants(){
        return $this->hasMany(ProductVariant::class)->select(['id','name','product_id']);
    }



    public function variantItems(){
        return $this->hasMany(ProductVariantItem::class);
    }

    public function avgReview(){
        // return $this->hasMany(ProductReview::class)->where('status', 1)->select('*', DB::raw('AVG(rating) AS avg_rating'));
        return $this->hasMany(ProductReview::class)->where('status', 1);
    }

    public function category_name(){
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function warehouseStocks()
    {
        return $this->hasMany(WarehouseStock::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public static function normalizeUnit(?string $unit): string
    {
        $unit = strtolower(trim((string) $unit));

        if ($unit === '' || $unit === 'pc' || $unit === 'pcs') {
            return 'pc';
        }

        return $unit;
    }

    public static function isPackUnit(?string $unit): bool
    {
        return self::normalizeUnit($unit) !== 'pc';
    }

    public static function convertToPcs($qty, ?string $unit, int $pcsPerBox = 1): float
    {
        $pcsPerBox = max(1, $pcsPerBox);
        $qty = (float) $qty;

        return self::isPackUnit($unit) ? $qty * $pcsPerBox : $qty;
    }

    public static function costPerPc(float $unitCost, ?string $unit, int $pcsPerBox = 1): float
    {
        $pcsPerBox = max(1, $pcsPerBox);

        return self::isPackUnit($unit)
            ? round($unitCost / $pcsPerBox, 4)
            : $unitCost;
    }

    public function pcsPerBox(): int
    {
        return max(1, (int) ($this->pcs_per_box ?: 1));
    }

    public static function resolvePurchaseUnit(?string $code): string
    {
        $code = self::normalizeUnit($code);
        $unit = Unit::where('code', $code)->where('status', 1)->first();

        return $unit ? $unit->code : 'pc';
    }

    public function defaultPurchaseUnit(): string
    {
        return self::normalizeUnit($this->purchase_unit ?? 'pc');
    }

    public function packUnitName(): string
    {
        return Unit::label($this->defaultPurchaseUnit());
    }

    protected $fillable = [
        'name',
        'name_bn',
        'short_name',
        'short_name_bn',
        'slug',
        'thumb_image',
        'vendor_id',
        'category_id',
        'sub_category_id',
        'child_category_id',
        'brand_id',
        'qty',
        'unit_type',
        'selling_price_mode',
        'pcs_per_box',
        'purchase_unit',
        'weight',
        'sold_qty',
        'short_description',
        'long_description',
        'video_link',
        'sku',
        'barcode',
        'low_stock_threshold',
        'seo_title',
        'seo_description',
        'price',
        'offer_price',
        'cost_price',
        'default_supplier_id',
        'tags',
        'show_homepage',
        'is_undefine',
        'is_featured',
        'new_product',
        'is_top',
        'is_best',
        'status',
        'is_specification',
        'approve_by_admin'
    ];

    public function isKg(): bool
    {
        return strtolower((string) $this->unit_type) === 'kg';
    }

    public function isPcs(): bool
    {
        return ! $this->isKg();
    }

    public function unitLabel(): string
    {
        return $this->isKg() ? 'KG' : 'PCS';
    }

    public function weightVariants()
    {
        return $this->belongsToMany(WeightVariant::class, 'product_weight_variants')
            ->withPivot(['id', 'selling_price', 'barcode'])
            ->withTimestamps()
            ->orderBy('weight_variants.sort_order');
    }

    public function productWeightVariants()
    {
        return $this->hasMany(ProductWeightVariant::class);
    }

    public function toArray()
    {
        $array = parent::toArray();
        $array['name'] = $this->localized_name;
        $array['short_name'] = $this->localized_short_name;

        return $array;
    }
}
