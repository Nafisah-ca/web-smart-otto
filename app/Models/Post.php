<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $table = 'post';

    protected $fillable = [
        'title',
        'slug',
        'category',
        'excerpt',
        'content',
        'thumbnail_url',
        'author_name',
        'published_at',
        'status',
        'meta_title',
        'meta_description',
        'cta_enabled',
        'cta_title',
        'cta_subtitle',
        'cta_button_text',
        'cta_button_url',
        'cta_image',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'cta_enabled'  => 'boolean',
        ];
    }

    // ── Scopes ────────────────────────────────────────────────────

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    // ── Accessors ─────────────────────────────────────────────────

    /**
     * Short human-friendly published date in Indonesian (timezone Asia/Jakarta).
     */
    public function getFormattedDateAttribute(): string
    {
        if (! $this->published_at) {
            return '-';
        }

        $bulan = [
            1  => 'Januari', 2  => 'Februari', 3  => 'Maret',
            4  => 'April',   5  => 'Mei',       6  => 'Juni',
            7  => 'Juli',    8  => 'Agustus',   9  => 'September',
            10 => 'Oktober', 11 => 'November',  12 => 'Desember',
        ];

        $dt = $this->published_at->setTimezone('Asia/Jakarta');

        return $dt->format('d') . ' '
             . $bulan[(int) $dt->format('n')] . ' '
             . $dt->format('Y');
    }

    // ── Helpers ───────────────────────────────────────────────────

    /**
     * Generate a unique slug from a given title.
     * Appends a numeric suffix if the base slug is already taken.
     */
    public static function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i    = 1;

        while (
            static::where('slug', $slug)
                  ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
                  ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
