<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $appends = ['localized_name'];

    protected $fillable = [
        'category_id',
        'name',
        'name_bn',
        'slug',
        'status',
    ];

    public function getLocalizedNameAttribute(): string
    {
        if (app()->getLocale() === 'bn' && ! empty($this->name_bn)) {
            return (string) $this->name_bn;
        }

        return (string) ($this->name ?? '');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function childCategories()
    {
        return $this->hasMany(ChildCategory::class, 'sub_category_id');
    }

    public function activeChildCategories()
    {
        return $this->hasMany(ChildCategory::class, 'sub_category_id')->where('status', 1)->select(['id', 'name', 'slug', 'sub_category_id']);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function toArray()
    {
        $array = parent::toArray();
        $array['name'] = $this->localized_name;

        return $array;
    }
}
