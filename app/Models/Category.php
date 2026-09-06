<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $appends = ['localized_name'];

    protected $fillable = [
        'name',
        'name_bn',
        'slug',
        'icon',
        'image',
        'status',
    ];

    public function getLocalizedNameAttribute(): string
    {
        if (app()->getLocale() === 'bn' && ! empty($this->name_bn)) {
            return (string) $this->name_bn;
        }

        return (string) ($this->name ?? '');
    }

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function activeSubCategories()
    {
        return $this->hasMany(SubCategory::class)->where('status', 1)->select(['id', 'name', 'name_bn', 'slug', 'category_id']);
    }

    public function toArray()
    {
        $array = parent::toArray();
        $array['name'] = $this->localized_name;

        return $array;
    }
}
