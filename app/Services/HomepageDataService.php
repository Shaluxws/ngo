<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Campaign;
use App\Models\Community;
use App\Models\Event;
use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\HomepageSection;
use App\Models\ImpactMetric;
use App\Models\Leader;
use App\Models\Menu;
use App\Models\SeoSetting;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use App\Models\Story;

class HomepageDataService
{
    public static function get(): array
    {
        // 1. Organization Information
        $ngo = [
            'name' => SiteSetting::get('site_name', 'NANBAN SOCIAL FOUNDATION'),
            'short_name' => SiteSetting::get('site_short_name', 'Nanban NGO'),
            'tagline' => SiteSetting::get('site_tagline', 'Serving Communities Across Tamil Nadu'),
            'state' => SiteSetting::get('state_jurisdiction', 'Tamil Nadu, India'),
            'phone' => SiteSetting::get('top_bar_phone', SiteSetting::get('contact_phone', '+91 94420 12345')),
            'phone_enabled' => filter_var(SiteSetting::get('top_bar_phone_enabled', true), FILTER_VALIDATE_BOOLEAN),
            'email' => SiteSetting::get('top_bar_email', SiteSetting::get('contact_email', 'contact@nanbanfoundation.org.in')),
            'email_enabled' => filter_var(SiteSetting::get('top_bar_email_enabled', true), FILTER_VALIDATE_BOOLEAN),
            'address' => SiteSetting::get('contact_address', '42, Gandhiji Road, RS Puram, Coimbatore, Tamil Nadu - 641002'),
            'operating_hours' => SiteSetting::get('operating_hours', 'Mon - Sat: 9:00 AM - 6:00 PM'),
            'copyright' => SiteSetting::get('copyright_text', '© 2026 Nanban Social Foundation. All Rights Reserved.'),
            'logo_url' => SiteSetting::get('logo_url', ''),
            'footer_logo_url' => SiteSetting::get('footer_logo_url', ''),
            'favicon_url' => SiteSetting::get('favicon_url', ''),
            'social_share_image' => SiteSetting::get('social_share_image', ''),
            'top_bar_enabled' => filter_var(SiteSetting::get('top_bar_enabled', SiteSetting::get('announcement_enabled', true)), FILTER_VALIDATE_BOOLEAN),
            'top_bar_badge' => SiteSetting::get('top_bar_badge', SiteSetting::get('announcement_badge', 'Community Impact')),
            'top_bar_text' => SiteSetting::get('top_bar_text', SiteSetting::get('announcement_text', 'Tamil Nadu Grassroots Community Management & Social Impact')),
            'top_bar_background_color' => SiteSetting::get('top_bar_background_color', '#166534'),
            'top_bar_text_color' => SiteSetting::get('top_bar_text_color', '#FFFFFF'),
            'top_bar_badge_color' => SiteSetting::get('top_bar_badge_color', '#F59E0B'),
            'top_bar_link_color' => SiteSetting::get('top_bar_link_color', '#FEF08A'),
            'announcement_badge' => SiteSetting::get('top_bar_badge', SiteSetting::get('announcement_badge', 'Community Impact')),
            'announcement_text' => SiteSetting::get('top_bar_text', SiteSetting::get('announcement_text', 'Tamil Nadu Grassroots Community Management & Social Impact')),
            'announcement_enabled' => filter_var(SiteSetting::get('top_bar_enabled', SiteSetting::get('announcement_enabled', true)), FILTER_VALIDATE_BOOLEAN),
        ];

        // 2. Sections Map
        $sections = HomepageSection::ordered()->get()->keyBy('section_key');

        // 3. Hero Section
        $heroSection = $sections->get('hero');
        $heroSettings = $heroSection ? $heroSection->settings : [];
        $hero = [
            'badge' => $heroSettings['badge'] ?? 'SERVING COMMUNITIES ACROSS TAMIL NADU',
            'heading_line1' => $heroSettings['heading_line1'] ?? 'Together, We Can Build',
            'heading_line2' => $heroSettings['heading_line2'] ?? 'Stronger Communities.',
            'subheading' => $heroSettings['subheading'] ?? 'Working hand-in-hand with grassroots communities, dedicated volunteers, and local leaders to foster sustainable social impact, inclusive education, and lasting dignity across Tamil Nadu.',
            'primary_cta' => $heroSettings['primary_cta'] ?? 'Support Our Mission',
            'primary_cta_url' => $heroSettings['primary_cta_url'] ?? '#campaign',
            'secondary_cta' => $heroSettings['secondary_cta'] ?? 'Join Our Community',
            'secondary_cta_url' => $heroSettings['secondary_cta_url'] ?? '#join-cta',
            'image' => $heroSettings['image'] ?? 'https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&w=1200&q=80',
            'image_alt' => $heroSettings['image_alt'] ?? 'Tamil Nadu community volunteers and children engaged in an interactive learning workshop',
            'stats_pill' => [
                'count' => $heroSettings['stats_count'] ?? '10,000+',
                'label' => $heroSettings['stats_label'] ?? 'Lives Reached in 2026',
            ],
            'badge_trust' => $heroSettings['badge_trust'] ?? '100% Volunteer Driven & Transparent',
            'is_enabled' => $heroSection ? $heroSection->is_enabled : true,
        ];

        // 4. Impact Statistics
        $metrics = ImpactMetric::enabled()->ordered()->get();
        $impactStats = $metrics->map(function ($m) {
            return [
                'value' => $m->value . ($m->suffix ?? ''),
                'label' => $m->label,
                'description' => $m->description,
                'icon' => $m->icon ?: 'heart-handshake',
            ];
        })->toArray();

        // 5. About Section
        $aboutSection = $sections->get('about');
        $aboutSettings = $aboutSection ? $aboutSection->settings : [];
        $about = [
            'badge' => $aboutSettings['badge'] ?? 'WHO WE ARE',
            'heading' => $aboutSettings['heading'] ?? 'Creating Change Together With Communities',
            'p1' => $aboutSettings['p1'] ?? 'We work alongside grassroots communities across Tamil Nadu to identify critical local challenges and co-create practical, sustainable solutions that enrich everyday lives.',
            'p2' => $aboutSettings['p2'] ?? 'Our community-first approach is anchored in dignity, active citizen participation, transparent governance, and long-term socio-economic empowerment. Rather than imposing external solutions, we nurture local leadership.',
            'pillars' => $aboutSettings['pillars'] ?? [
                ['title' => 'Community Participation', 'desc' => 'Every program is planned and driven in collaboration with local community members and ward representatives.', 'icon' => 'users-round'],
                ['title' => 'Complete Transparency', 'desc' => 'Open reporting, clear financial accountability, and regular community social audits.', 'icon' => 'shield-check'],
                ['title' => 'Sustainable Growth', 'desc' => 'Focusing on skill-building and self-reliance rather than one-time short-term relief.', 'icon' => 'sprout'],
            ],
            'image' => $aboutSettings['image'] ?? 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1000&q=80',
            'image_alt' => $aboutSettings['image_alt'] ?? 'Classroom education and village student mentorship program in Tamil Nadu',
            'is_enabled' => $aboutSection ? $aboutSection->is_enabled : true,
        ];

        // 6. Programs / What We Do
        $programs = Activity::with('media')->active()->ordered()->get()->map(function ($act) {
            return [
                'id' => $act->slug,
                'title' => $act->title,
                'description' => $act->description,
                'icon' => $act->icon ?: 'graduation-cap',
                'impact_tag' => $act->impact_tag,
                'color' => $act->color ?: 'green',
                'image' => $act->resolved_image_url,
            ];
        })->toArray();

        // 7. Communities
        $communities = Community::with('media')->enabled()->ordered()->get()->map(function ($c) {
            return [
                'name' => $c->name,
                'area' => $c->area,
                'description' => $c->description,
                'active_volunteers' => $c->active_volunteers,
                'active_programs' => $c->active_programs,
                'image' => $c->resolved_image_url,
            ];
        })->toArray();

        // 8. Featured Campaign
        $campaignModel = Campaign::with('media')->featured()->active()->first() ?? Campaign::with('media')->active()->first();
        $campaign = $campaignModel ? [
            'badge' => $campaignModel->badge ?: 'FEATURED CAMPAIGN',
            'title' => $campaignModel->title,
            'subtitle' => $campaignModel->subtitle,
            'raised' => (float) $campaignModel->raised_amount,
            'goal' => (float) $campaignModel->goal_amount,
            'supporters' => $campaignModel->supporters_count,
            'days_left' => $campaignModel->days_left,
            'progress_percentage' => $campaignModel->progress_percentage,
            'image' => $campaignModel->resolved_image_url,
            'image_alt' => $campaignModel->image_alt ?: $campaignModel->title,
            'impact_bullets' => $campaignModel->impact_bullets ?? [
                'Provides 120 refurbished laptops and solar back-up units',
                'Trains 18 local youth as community digital tutors',
                'Benefits 2,400+ first-generation school students',
                '100% transparent expense audit published quarterly',
            ],
            'suggested_amounts' => $campaignModel->suggested_amounts ?? [500, 1000, 2500, 5000, 10000],
        ] : [];

        // 9. Events
        $events = Event::with('media')->active()->ordered()->get()->map(function ($e) {
            return [
                'day' => $e->day,
                'month' => $e->month,
                'year' => $e->year,
                'category' => $e->category,
                'title' => $e->title,
                'location' => $e->location,
                'time' => $e->time_info,
                'description' => $e->description,
                'image' => $e->resolved_image_url,
            ];
        })->toArray();

        // 10. Leadership
        $leadership = Leader::with('media')->active()->ordered()->get()->map(function ($l) {
            return [
                'name' => $l->name,
                'role' => $l->role,
                'bio' => $l->bio,
                'image' => $l->resolved_image_url,
            ];
        })->toArray();

        // 11. Success Stories
        $stories = Story::with('media')->active()->ordered()->get()->map(function ($s) {
            return [
                'title' => $s->title,
                'author_info' => $s->author_info,
                'excerpt' => $s->excerpt,
                'quote' => $s->quote,
                'category' => $s->category,
                'image' => $s->resolved_image_url,
            ];
        })->toArray();

        // 12. Gallery
        $gallery = GalleryItem::with('media')->active()->ordered()->get()->map(function ($g) {
            return [
                'title' => $g->title,
                'category' => $g->category,
                'location' => $g->location,
                'image' => $g->resolved_image_url,
            ];
        })->toArray();

        // 13. FAQs
        $faqs = Faq::active()->ordered()->get()->map(function ($f) {
            return [
                'q' => $f->question,
                'a' => $f->answer,
            ];
        })->toArray();

        // 14. Join CTA
        $joinSection = $sections->get('join_cta');
        $joinSettings = $joinSection ? $joinSection->settings : [];

        // 15. Contact
        $contactSection = $sections->get('contact');
        $contactSettings = $contactSection ? $contactSection->settings : [];

        // 16. Menus & Social
        $headerMenu = Menu::forLocation('header');
        $footerQuick = Menu::forLocation('footer_quick_links');
        $footerInvolved = Menu::forLocation('footer_get_involved');
        $socialLinks = SocialLink::active()->ordered()->get();

        // 17. SEO
        $seo = SeoSetting::forPage('home', [
            'meta_title' => $ngo['name'] . ' | ' . $ngo['tagline'],
            'meta_description' => 'Grassroots NGO in Tamil Nadu.',
        ]);

        return [
            'ngo' => $ngo,
            'sections' => $sections,
            'hero' => $hero,
            'impact_stats' => $impactStats,
            'about' => $about,
            'programs' => $programs,
            'communities' => $communities,
            'campaign' => $campaign,
            'events' => $events,
            'leadership' => $leadership,
            'stories' => $stories,
            'gallery' => $gallery,
            'faqs' => $faqs,
            'join_cta' => $joinSettings,
            'contact_section' => $contactSettings,
            'header_menu' => $headerMenu,
            'footer_quick' => $footerQuick,
            'footer_involved' => $footerInvolved,
            'social_links' => $socialLinks,
            'seo' => $seo,
        ];
    }
}
