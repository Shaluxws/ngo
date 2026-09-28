<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'badge',
        'subtitle',
        'goal_amount',
        'raised_amount',
        'supporters_count',
        'start_date',
        'end_date',
        'media_id',
        'image_url',
        'image_alt',
        'impact_bullets',
        'suggested_amounts',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'goal_amount' => 'decimal:2',
        'raised_amount' => 'decimal:2',
        'supporters_count' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'impact_bullets' => 'array',
        'suggested_amounts' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    public function getDaysLeftAttribute(): int
    {
        if (!$this->end_date) {
            return 30;
        }
        $diff = now()->startOfDay()->diffInDays(Carbon::parse($this->end_date)->startOfDay(), false);
        return max(0, (int)$diff);
    }

    public function getProgressPercentageAttribute(): int
    {
        if ($this->goal_amount <= 0) {
            return 0;
        }
        $pct = ($this->raised_amount / $this->goal_amount) * 100;
        return min(100, (int)round($pct));
    }

    public function getResolvedImageUrlAttribute(): ?string
    {
        if ($this->media) {
            return $this->media->url;
        }
        return $this->image_url;
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
