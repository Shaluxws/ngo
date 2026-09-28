<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Story extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author_info',
        'excerpt',
        'quote',
        'category',
        'media_id',
        'image_url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    public function getResolvedImageUrlAttribute(): ?string
    {
        if ($this->media) {
            return $this->media->url;
        }
        return $this->image_url;
    }

    public function getSlugAttribute(): string
    {
        return $this->attributes['slug'] ?? \Illuminate\Support\Str::slug($this->title ?: ('story-' . $this->id));
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }
}
