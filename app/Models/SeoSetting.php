<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_key',
        'meta_title',
        'meta_description',
        'keywords',
        'og_title',
        'og_description',
        'og_image',
        'canonical_url',
    ];

    public static function forPage(string $key, array $fallback = []): array
    {
        $seo = static::where('page_key', $key)->first();
        if (!$seo) {
            return $fallback;
        }

        return [
            'meta_title' => $seo->meta_title ?: ($fallback['meta_title'] ?? ''),
            'meta_description' => $seo->meta_description ?: ($fallback['meta_description'] ?? ''),
            'keywords' => $seo->keywords ?: ($fallback['keywords'] ?? ''),
            'og_title' => $seo->og_title ?: ($fallback['og_title'] ?? ''),
            'og_description' => $seo->og_description ?: ($fallback['og_description'] ?? ''),
            'og_image' => $seo->og_image ?: ($fallback['og_image'] ?? ''),
            'canonical_url' => $seo->canonical_url ?: ($fallback['canonical_url'] ?? ''),
        ];
    }
}
