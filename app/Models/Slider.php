<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $appends = ['localized_title_one', 'localized_title_two'];

    protected $fillable = [
        'title_one',
        'title_one_bn',
        'title_two',
        'title_two_bn',
        'image',
        'link',
        'status',
        'serial',
        'slider_location',
        'product_slug',
        'text_position',
    ];

    public function getLocalizedTitleOneAttribute(): string
    {
        if (app()->getLocale() === 'bn' && ! empty($this->title_one_bn)) {
            return (string) $this->title_one_bn;
        }

        return (string) ($this->title_one ?? '');
    }

    public function getLocalizedTitleTwoAttribute(): string
    {
        if (app()->getLocale() === 'bn' && ! empty($this->title_two_bn)) {
            return (string) $this->title_two_bn;
        }

        return (string) ($this->title_two ?? '');
    }

    public function toArray()
    {
        $array = parent::toArray();
        $array['title_one'] = $this->localized_title_one;
        $array['title_two'] = $this->localized_title_two;

        return $array;
    }
}
