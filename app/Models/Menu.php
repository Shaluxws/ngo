<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'location',
        'name',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('sort_order', 'asc');
    }

    public function enabledItems(): HasMany
    {
        return $this->hasMany(MenuItem::class)->where('is_enabled', true)->orderBy('sort_order', 'asc');
    }

    public static function forLocation(string $location)
    {
        return static::where('location', $location)->with(['enabledItems' => function ($q) {
            $q->whereNull('parent_id')->with('children');
        }])->first();
    }
}
