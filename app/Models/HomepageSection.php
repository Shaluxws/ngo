<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomepageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_key',
        'title',
        'subtitle',
        'content',
        'settings_json',
        'draft_settings_json',
        'is_published',
        'published_at',
        'published_by',
        'sort_order',
        'is_enabled',
    ];

    protected $casts = [
        'settings_json' => 'array',
        'draft_settings_json' => 'array',
        'is_published' => 'boolean',
        'is_enabled' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    /**
     * Get the active settings (published vs draft)
     */
    public function getSettingsAttribute(): array
    {
        return $this->settings_json ?? [];
    }

    /**
     * Publish the draft settings to live settings
     */
    public function publish(?int $userId = null): void
    {
        $this->settings_json = $this->draft_settings_json ?? $this->settings_json;
        $this->is_published = true;
        $this->published_at = now();
        $this->published_by = $userId;
        $this->save();
    }
}
