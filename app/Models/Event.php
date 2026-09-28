<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'event_date',
        'time_info',
        'location',
        'description',
        'media_id',
        'image_url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'event_date' => 'date',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    public function getDayAttribute(): string
    {
        return $this->event_date ? Carbon::parse($this->event_date)->format('d') : '01';
    }

    public function getMonthAttribute(): string
    {
        return $this->event_date ? strtoupper(Carbon::parse($this->event_date)->format('M')) : 'JAN';
    }

    public function getYearAttribute(): string
    {
        return $this->event_date ? Carbon::parse($this->event_date)->format('Y') : '2026';
    }

    public function getResolvedImageUrlAttribute(): ?string
    {
        if ($this->media) {
            return $this->media->url;
        }
        return $this->image_url;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('event_date', 'asc')->orderBy('sort_order', 'asc');
    }
}
