<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleTagManager extends Model
{
    protected $fillable = ['status', 'container_id'];

    public static function current(): ?self
    {
        try {
            if (! \Illuminate\Support\Facades\Schema::hasTable('google_tag_managers')) {
                return null;
            }

            return static::first();
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function isActive(): bool
    {
        return (int) $this->status === 1 && filled($this->container_id);
    }

    public static function normalizeContainerId(?string $id): ?string
    {
        $id = strtoupper(trim((string) $id));
        $id = preg_replace('/[^A-Z0-9\-]/', '', $id) ?: '';

        if ($id === '') {
            return null;
        }

        if (!str_starts_with($id, 'GTM-')) {
            $id = 'GTM-'.$id;
        }

        return $id;
    }
}
