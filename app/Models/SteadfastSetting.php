<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SteadfastSetting extends Model
{
    protected $fillable = [
        'status',
        'api_key',
        'secret_key',
        'base_url',
    ];

    public static function current(): self
    {
        $row = static::query()->first();
        if (! $row) {
            $row = static::create([
                'status' => 0,
                'base_url' => 'https://portal.packzy.com/api/v1',
            ]);
        }

        return $row;
    }

    public function isConfigured(): bool
    {
        return (int) $this->status === 1
            && filled($this->api_key)
            && filled($this->secret_key);
    }
}
