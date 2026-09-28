<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'NANBAN SOCIAL FOUNDATION', 'group' => 'general', 'type' => 'text'],
            ['key' => 'site_short_name', 'value' => 'Nanban NGO', 'group' => 'general', 'type' => 'text'],
            ['key' => 'site_tagline', 'value' => 'Serving Communities Across Tamil Nadu', 'group' => 'general', 'type' => 'text'],
            ['key' => 'state_jurisdiction', 'value' => 'Tamil Nadu, India', 'group' => 'general', 'type' => 'text'],
            ['key' => 'contact_phone', 'value' => '+91 94420 12345', 'group' => 'contact', 'type' => 'text'],
            ['key' => 'contact_email', 'value' => 'contact@nanbanfoundation.org.in', 'group' => 'contact', 'type' => 'text'],
            ['key' => 'contact_address', 'value' => '42, Gandhiji Road, RS Puram, Coimbatore, Tamil Nadu - 641002', 'group' => 'contact', 'type' => 'textarea'],
            ['key' => 'operating_hours', 'value' => 'Mon - Sat: 9:00 AM - 6:00 PM', 'group' => 'contact', 'type' => 'text'],
            ['key' => 'copyright_text', 'value' => '© 2026 Nanban Social Foundation. All Rights Reserved. Built with pride for Tamil Nadu communities.', 'group' => 'general', 'type' => 'text'],
            ['key' => 'website_status', 'value' => 'online', 'group' => 'system', 'type' => 'select'],
            ['key' => 'timezone', 'value' => 'Asia/Kolkata', 'group' => 'system', 'type' => 'text'],
            ['key' => 'logo_url', 'value' => '', 'group' => 'branding', 'type' => 'image'],
            ['key' => 'favicon_url', 'value' => '', 'group' => 'branding', 'type' => 'image'],
            ['key' => 'top_bar_enabled', 'value' => '1', 'group' => 'top_bar', 'type' => 'boolean'],
            ['key' => 'top_bar_badge', 'value' => 'Community Impact', 'group' => 'top_bar', 'type' => 'text'],
            ['key' => 'top_bar_text', 'value' => 'Tamil Nadu Grassroots Community Management & Social Impact', 'group' => 'top_bar', 'type' => 'text'],
            ['key' => 'top_bar_phone_enabled', 'value' => '1', 'group' => 'top_bar', 'type' => 'boolean'],
            ['key' => 'top_bar_phone', 'value' => '+91 94420 12345', 'group' => 'top_bar', 'type' => 'text'],
            ['key' => 'top_bar_email_enabled', 'value' => '1', 'group' => 'top_bar', 'type' => 'boolean'],
            ['key' => 'top_bar_email', 'value' => 'contact@nanbanfoundation.org.in', 'group' => 'top_bar', 'type' => 'text'],
            ['key' => 'top_bar_background_color', 'value' => '#166534', 'group' => 'top_bar', 'type' => 'color'],
            ['key' => 'top_bar_text_color', 'value' => '#FFFFFF', 'group' => 'top_bar', 'type' => 'color'],
            ['key' => 'top_bar_badge_color', 'value' => '#F59E0B', 'group' => 'top_bar', 'type' => 'color'],
            ['key' => 'top_bar_link_color', 'value' => '#FEF08A', 'group' => 'top_bar', 'type' => 'color'],
            ['key' => 'social_share_image', 'value' => '', 'group' => 'branding', 'type' => 'image'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
