<?php

namespace App\Livewire\Admin;

use App\Models\ThemeSetting;
use App\Services\AuditService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class ThemeEditor extends Component
{
    public array $theme = [];

    protected array $rules = [
        'theme.primary_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        'theme.secondary_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        'theme.accent_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        'theme.background_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        'theme.surface_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        'theme.text_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        'theme.muted_text_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        'theme.border_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        'theme.button_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        'theme.heading_font' => 'required|string|max:50',
        'theme.body_font' => 'required|string|max:50',
        'theme.border_radius' => 'required|string|max:20',
    ];

    public function mount(): void
    {
        $defaults = ThemeSetting::defaults();
        $saved = ThemeSetting::pluck('value', 'key')->toArray();
        $this->theme = array_merge($defaults, array_filter($saved));
    }

    public function saveTheme(): void
    {
        $this->validate();

        $oldValues = ThemeSetting::pluck('value', 'key')->toArray();

        foreach ($this->theme as $key => $value) {
            ThemeSetting::set($key, $value);
        }

        AuditService::log(
            'Updated website theme settings and color tokens',
            'theme_settings',
            null,
            $oldValues,
            $this->theme
        );

        session()->flash('success', 'Theme tokens saved & published to public website.');
    }

    public function resetToDefaults(): void
    {
        $this->theme = ThemeSetting::defaults();
        $this->saveTheme();
        session()->flash('success', 'Theme reset to approved default styling.');
    }

    public function render()
    {
        return view('livewire.admin.theme-editor');
    }
}
