<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThemeSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, $value): self
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public static function defaults(): array
    {
        return [
            'primary_color' => '#047857', // Emerald 700
            'secondary_color' => '#0d9488', // Teal 600
            'accent_color' => '#d97706', // Amber 600
            'background_color' => '#f8fafc',
            'surface_color' => '#ffffff',
            'text_color' => '#0f172a',
            'muted_text_color' => '#64748b',
            'border_color' => '#e2e8f0',
            'button_color' => '#047857',
            'heading_font' => 'Plus Jakarta Sans',
            'body_font' => 'Inter',
            'border_radius' => '0.75rem',
        ];
    }

    public static function generateCssVariables(): string
    {
        $settings = static::pluck('value', 'key')->toArray();
        $defaults = static::defaults();
        $merged = array_merge($defaults, array_filter($settings));

        // Sanitize CSS variable outputs (only allow valid hex colors, font names, rem/px)
        $css = ":root {\n";
        foreach ($merged as $key => $val) {
            $cssKey = '--theme-' . str_replace('_', '-', $key);
            // safe validation
            $safeVal = preg_replace('/[^\w\s\-\#\.\,\'\"\%\(\)]/', '', $val);
            $css .= "    {$cssKey}: {$safeVal};\n";
        }
        $css .= "}\n";

        return $css;
    }
}
