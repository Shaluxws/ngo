<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\SocialLink;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Header Navigation Menu
        $headerMenu = Menu::firstOrCreate(['location' => 'header'], ['name' => 'Main Navigation']);
        $headerItems = [
            ['label' => 'About Us', 'url' => '#about', 'sort_order' => 1],
            ['label' => 'What We Do', 'url' => '#programs', 'sort_order' => 2],
            ['label' => 'Communities', 'url' => '#communities', 'sort_order' => 3],
            ['label' => 'Campaigns', 'url' => '#campaign', 'sort_order' => 4],
            ['label' => 'Events', 'url' => '#events', 'sort_order' => 5],
            ['label' => 'Stories', 'url' => '#stories', 'sort_order' => 6],
            ['label' => 'Contact', 'url' => '#contact', 'sort_order' => 7],
        ];
        foreach ($headerItems as $item) {
            MenuItem::updateOrCreate(
                ['menu_id' => $headerMenu->id, 'label' => $item['label']],
                array_merge($item, ['menu_id' => $headerMenu->id, 'is_enabled' => true])
            );
        }

        // 2. Footer Quick Links
        $footerQuick = Menu::firstOrCreate(['location' => 'footer_quick_links'], ['name' => 'Footer Quick Links']);
        $quickItems = [
            ['label' => 'Who We Are', 'url' => '#about', 'sort_order' => 1],
            ['label' => 'Core Programs', 'url' => '#programs', 'sort_order' => 2],
            ['label' => 'Impact Communities', 'url' => '#communities', 'sort_order' => 3],
            ['label' => 'Success Stories', 'url' => '#stories', 'sort_order' => 4],
            ['label' => 'Media & Gallery', 'url' => '#gallery', 'sort_order' => 5],
        ];
        foreach ($quickItems as $item) {
            MenuItem::updateOrCreate(
                ['menu_id' => $footerQuick->id, 'label' => $item['label']],
                array_merge($item, ['menu_id' => $footerQuick->id, 'is_enabled' => true])
            );
        }

        // 3. Footer Get Involved
        $footerInvolved = Menu::firstOrCreate(['location' => 'footer_get_involved'], ['name' => 'Footer Get Involved']);
        $involvedItems = [
            ['label' => 'Become a Volunteer', 'url' => '#join-cta', 'sort_order' => 1],
            ['label' => 'Support a Campaign', 'url' => '#campaign', 'sort_order' => 2],
            ['label' => 'Join District Hub', 'url' => '#communities', 'sort_order' => 3],
            ['label' => 'Corporate CSR Partner', 'url' => '#contact', 'sort_order' => 4],
            ['label' => 'Community Leader Login', 'url' => '/login', 'sort_order' => 5],
        ];
        foreach ($involvedItems as $item) {
            MenuItem::updateOrCreate(
                ['menu_id' => $footerInvolved->id, 'label' => $item['label']],
                array_merge($item, ['menu_id' => $footerInvolved->id, 'is_enabled' => true])
            );
        }

        // 4. Social Links
        $socials = [
            ['platform' => 'Facebook', 'url' => 'https://facebook.com', 'icon' => 'facebook', 'sort_order' => 1],
            ['platform' => 'Instagram', 'url' => 'https://instagram.com', 'icon' => 'instagram', 'sort_order' => 2],
            ['platform' => 'YouTube', 'url' => 'https://youtube.com', 'icon' => 'youtube', 'sort_order' => 3],
            ['platform' => 'Twitter', 'url' => 'https://twitter.com', 'icon' => 'twitter', 'sort_order' => 4],
            ['platform' => 'WhatsApp', 'url' => 'https://whatsapp.com', 'icon' => 'whatsapp', 'sort_order' => 5],
        ];
        foreach ($socials as $s) {
            SocialLink::updateOrCreate(
                ['platform' => $s['platform']],
                array_merge($s, ['is_active' => true])
            );
        }
    }
}
