<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Post extends Model
{
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
     * Estimated reading time in minutes, based on word count.
     */
    public function getReadingTimeAttribute(): int
    {
        $wordCount = str_word_count(strip_tags($this->content));
        return max(1, (int) ceil($wordCount / 200));
    }

    /**
     * Short human-friendly published date in Indonesian.
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

        return $this->published_at->format('d') . ' '
             . $bulan[(int) $this->published_at->format('n')] . ' '
             . $this->published_at->format('Y');
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
