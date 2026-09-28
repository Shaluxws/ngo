<?php

namespace Database\Seeders;

use App\Models\ThemeSetting;
use Illuminate\Database\Seeder;

class ThemeSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = ThemeSetting::defaults();

        foreach ($defaults as $key => $value) {
            ThemeSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
