<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CmsContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'key', 'label', 'group', 'type', 'value', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function get(string $key, string $default = ''): string
    {
        // Cache hanya string value, bukan object — agar forget() benar-benar efektif
        return Cache::remember("cms_{$key}", 3600, function () use ($key, $default) {
            $row = self::where('key', $key)->where('is_active', true)->first();
            return $row?->value ?? $default;
        });
    }

    public static function clearCache(string $key): void
    {
        Cache::forget("cms_{$key}");
    }
}
