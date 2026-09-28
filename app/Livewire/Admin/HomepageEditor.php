<?php

namespace App\Livewire\Admin;

use App\Models\Activity;
use App\Models\Campaign;
use App\Models\Community;
use App\Models\Event;
use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\HomepageSection;
use App\Models\ImpactMetric;
use App\Models\Leader;
use App\Models\Media;
use App\Models\Story;
use App\Services\AuditService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class HomepageEditor extends Component
{
    use WithFileUploads;

    public string $activeSectionKey = 'hero';

    // Section-specific form datasets populated from database
    public array $heroData = [];
    public array $impactStatsHeader = [];
    public array $aboutData = [];
    public array $programsHeader = [];
    public array $communitiesHeader = [];
    public array $campaignHeader = [];
    public array $eventsHeader = [];
    public array $leadershipHeader = [];
    public array $storiesHeader = [];
    public array $galleryHeader = [];
    public array $joinCtaData = [];
    public array $contactData = [];

    // Local file uploads
    public $heroImageFile = null;
    public $aboutImageFile = null;
    public $itemImageFile = null;

    // Sub-entity modal states
    public bool $showItemModal = false;
    public string $itemModalType = ''; // 'metric', 'activity', 'community', 'campaign', 'event', 'leader', 'story', 'gallery', 'faq'
    public ?int $editingItemId = null;
    public array $itemFormData = [];

    // Media Picker state
    public bool $showMediaPickerModal = false;
    public string $mediaPickerTarget = '';
    public string $mediaSearch = '';

    public function mount(): void
    {
        $user = Auth::user();
        if ($user && !$user->hasRole('Super Admin') && !$user->hasAnyPermission(['homepage.view', 'homepage.edit', 'website.view', 'website.edit'])) {
            abort(403, 'Unauthorized access to Homepage CMS.');
        }

        $this->loadAllSectionsData();
    }

    protected function checkAuthorization(): void
    {
        $user = Auth::user();
        if (!$user || (!$user->hasRole('Super Admin') && !$user->hasAnyPermission(['homepage.edit', 'website.edit']))) {
            abort(403, 'Unauthorized action in Homepage CMS.');
        }
    }

    public function loadAllSectionsData(): void
    {
        $sections = HomepageSection::all()->keyBy('section_key');

        // 1. Hero
        $heroSec = $sections->get('hero');
        $heroSettings = $heroSec ? ($heroSec->draft_settings_json ?? $heroSec->settings_json ?? []) : [];
        $this->heroData = [
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
            'stats_count' => $heroSettings['stats_count'] ?? '10,000+',
            'stats_label' => $heroSettings['stats_label'] ?? 'Lives Reached in 2026',
            'badge_trust' => $heroSettings['badge_trust'] ?? '100% Volunteer Driven & Transparent',
        ];

        // 2. Impact Stats Header
        $impactSec = $sections->get('impact_stats');
        $impactSettings = $impactSec ? ($impactSec->draft_settings_json ?? $impactSec->settings_json ?? []) : [];
        $this->impactStatsHeader = [
            'badge' => $impactSettings['badge'] ?? $impactSec?->subtitle ?? 'OUR COMMUNITY IMPACT',
            'heading' => $impactSettings['heading'] ?? $impactSec?->title ?? 'Measurable Change Delivered Directly to Families',
            'description' => $impactSettings['description'] ?? 'Real impact created across Tamil Nadu through grassroots collaboration.',
        ];

        // 3. About
        $aboutSec = $sections->get('about');
        $aboutSettings = $aboutSec ? ($aboutSec->draft_settings_json ?? $aboutSec->settings_json ?? []) : [];
        $this->aboutData = [
            'badge' => $aboutSettings['badge'] ?? 'WHO WE ARE',
            'heading' => $aboutSettings['heading'] ?? 'Creating Change Together With Communities',
            'p1' => $aboutSettings['p1'] ?? 'We work alongside grassroots communities across Tamil Nadu to identify critical local challenges and co-create practical, sustainable solutions that enrich everyday lives.',
            'p2' => $aboutSettings['p2'] ?? 'Our community-first approach is anchored in dignity, active citizen participation, transparent governance, and long-term socio-economic empowerment. Rather than imposing external solutions, we nurture local leadership.',
            'image' => $aboutSettings['image'] ?? 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1000&q=80',
            'image_alt' => $aboutSettings['image_alt'] ?? 'Classroom education and village student mentorship program in Tamil Nadu',
            'pillar1_title' => $aboutSettings['pillars'][0]['title'] ?? 'Community Participation',
            'pillar1_desc' => $aboutSettings['pillars'][0]['desc'] ?? 'Every program is planned and driven in collaboration with local community members and ward representatives.',
            'pillar2_title' => $aboutSettings['pillars'][1]['title'] ?? 'Complete Transparency',
            'pillar2_desc' => $aboutSettings['pillars'][1]['desc'] ?? 'Open reporting, clear financial accountability, and regular community social audits.',
            'pillar3_title' => $aboutSettings['pillars'][2]['title'] ?? 'Sustainable Growth',
            'pillar3_desc' => $aboutSettings['pillars'][2]['desc'] ?? 'Focusing on skill-building and self-reliance rather than one-time short-term relief.',
        ];

        // 4. Programs Header
        $progSec = $sections->get('programs');
        $progSettings = $progSec ? ($progSec->draft_settings_json ?? $progSec->settings_json ?? []) : [];
        $this->programsHeader = [
            'badge' => $progSettings['badge'] ?? $progSec?->subtitle ?? 'WHAT WE DO',
            'heading' => $progSettings['heading'] ?? $progSec?->title ?? 'Programs Built for Grassroots Empowerment',
            'description' => $progSettings['description'] ?? 'Targeted, multi-dimensional interventions structured to break cycles of poverty and nurture self-sustaining rural and peri-urban hubs.',
        ];

        // 5. Communities Header
        $commSec = $sections->get('communities');
        $commSettings = $commSec ? ($commSec->draft_settings_json ?? $commSec->settings_json ?? []) : [];
        $this->communitiesHeader = [
            'badge' => $commSettings['badge'] ?? $commSec?->subtitle ?? 'WHERE WE WORK',
            'heading' => $commSettings['heading'] ?? $commSec?->title ?? 'Our Active Regional Community Focal Points',
            'description' => $commSettings['description'] ?? 'Rooted in villages, taluks, and urban wards across Western & Coastal Tamil Nadu.',
        ];

        // 6. Campaign Header
        $campSec = $sections->get('campaign');
        $campSettings = $campSec ? ($campSec->draft_settings_json ?? $campSec->settings_json ?? []) : [];
        $this->campaignHeader = [
            'badge' => $campSettings['badge'] ?? $campSec?->subtitle ?? 'FEATURED CAMPAIGN',
            'heading' => $campSettings['heading'] ?? $campSec?->title ?? 'Support Our Ongoing Field Mission',
            'description' => $campSettings['description'] ?? 'Every contribution directly funds student laptops, education kits, and community health access.',
        ];

        // 7. Events Header
        $eventSec = $sections->get('events');
        $eventSettings = $eventSec ? ($eventSec->draft_settings_json ?? $eventSec->settings_json ?? []) : [];
        $this->eventsHeader = [
            'badge' => $eventSettings['badge'] ?? $eventSec?->subtitle ?? 'GET INVOLVED LOCALLY',
            'heading' => $eventSettings['heading'] ?? $eventSec?->title ?? 'Upcoming Community Action & Outreach Events',
            'description' => $eventSettings['description'] ?? 'Join our weekend field drives, medical camps, and student mentorship sessions across Tamil Nadu.',
        ];

        // 8. Leadership Header
        $leadSec = $sections->get('leadership');
        $leadSettings = $leadSec ? ($leadSec->draft_settings_json ?? $leadSec->settings_json ?? []) : [];
        $this->leadershipHeader = [
            'badge' => $leadSettings['badge'] ?? $leadSec?->subtitle ?? 'OUR TRUSTEES & COORDINATORS',
            'heading' => $leadSettings['heading'] ?? $leadSec?->title ?? 'Guided by Dedicated Community Leaders & Trustees',
            'description' => $leadSettings['description'] ?? 'Experienced educators, doctors, social workers, and organizers serving with unconditional transparency.',
        ];

        // 9. Stories Header
        $storySec = $sections->get('stories');
        $storySettings = $storySec ? ($storySec->draft_settings_json ?? $storySec->settings_json ?? []) : [];
        $this->storiesHeader = [
            'badge' => $storySettings['badge'] ?? $storySec?->subtitle ?? 'COMMUNITY VOICES',
            'heading' => $storySettings['heading'] ?? $storySec?->title ?? 'Real Journeys of Resilience & Transformation',
            'description' => $storySettings['description'] ?? 'Heartwarming stories of change created when local families and dedicated volunteers work together.',
        ];

        // 10. Gallery Header
        $galSec = $sections->get('gallery');
        $galSettings = $galSec ? ($galSec->draft_settings_json ?? $galSec->settings_json ?? []) : [];
        $this->galleryHeader = [
            'badge' => $galSettings['badge'] ?? $galSec?->subtitle ?? 'FIELD GLIMPSES',
            'heading' => $galSettings['heading'] ?? $galSec?->title ?? 'Moments of Action, Learning & Fellowship',
            'description' => $galSettings['description'] ?? 'Glimpses from classroom workshops, health camps, tree drives, and women collective gatherings.',
        ];

        // 11. Join CTA
        $joinSec = $sections->get('join_cta');
        $joinSettings = $joinSec ? ($joinSec->draft_settings_json ?? $joinSec->settings_json ?? []) : [];
        $this->joinCtaData = [
            'badge' => $joinSettings['badge'] ?? 'JOIN OUR MOVEMENT',
            'heading' => $joinSettings['heading'] ?? 'Ready to Make a Meaningful Difference in Tamil Nadu?',
            'description' => $joinSettings['description'] ?? 'Whether you can contribute your time as a weekend volunteer, share digital skills, or support a child’s education — your participation matters.',
            'primary_button_text' => $joinSettings['primary_button_text'] ?? 'Register as Volunteer',
            'primary_button_url' => $joinSettings['primary_button_url'] ?? '#contact',
            'secondary_button_text' => $joinSettings['secondary_button_text'] ?? 'Support Our Mission',
            'secondary_button_url' => $joinSettings['secondary_button_url'] ?? '#campaign',
        ];

        // 12. Contact
        $contactSec = $sections->get('contact');
        $contactSettings = $contactSec ? ($contactSec->draft_settings_json ?? $contactSec->settings_json ?? []) : [];
        $this->contactData = [
            'badge' => $contactSettings['badge'] ?? 'GET IN TOUCH',
            'heading' => $contactSettings['heading'] ?? 'Connect With Our District Coordinators & Head Office',
            'description' => $contactSettings['description'] ?? 'Have questions about our programs, volunteer orientations, or CSR partnerships? Reach out directly.',
        ];
    }

    public function setSection(string $key): void
    {
        $this->activeSectionKey = $key;
    }

    public function getActiveSectionModelProperty(): ?HomepageSection
    {
        return HomepageSection::where('section_key', $this->activeSectionKey)->first();
    }

    /* -------------------------------------------------------------
     | Section Save Operations (Draft & Live Publish)
     | ------------------------------------------------------------*/

    public function saveHero(bool $publish = false): void
    {
        $this->checkAuthorization();

        $this->validate([
            'heroData.heading_line1' => 'required|string|max:255',
            'heroData.heading_line2' => 'required|string|max:255',
            'heroData.subheading' => 'required|string',
            'heroData.image' => 'required|string',
        ]);

        $section = HomepageSection::firstOrCreate(
            ['section_key' => 'hero'],
            ['title' => 'Hero Section', 'sort_order' => 1]
        );

        $section->draft_settings_json = $this->heroData;
        if ($publish) {
            $section->settings_json = $this->heroData;
            $section->is_published = true;
            $section->published_at = now();
            $section->published_by = Auth::id();
        }
        $section->save();

        AuditService::log(
            $publish ? 'Published updated Homepage Hero section' : 'Saved draft for Homepage Hero',
            'homepage_sections',
            $section->id,
            null,
            $this->heroData
        );

        session()->flash('success', $publish ? 'Hero section published live to website.' : 'Hero draft saved successfully.');
    }

    public function saveAbout(bool $publish = false): void
    {
        $this->checkAuthorization();

        $this->validate([
            'aboutData.heading' => 'required|string|max:255',
            'aboutData.p1' => 'required|string',
            'aboutData.p2' => 'required|string',
        ]);

        $section = HomepageSection::firstOrCreate(
            ['section_key' => 'about'],
            ['title' => 'About Section', 'sort_order' => 3]
        );

        $payload = [
            'badge' => $this->aboutData['badge'] ?? 'WHO WE ARE',
            'heading' => $this->aboutData['heading'],
            'p1' => $this->aboutData['p1'],
            'p2' => $this->aboutData['p2'],
            'image' => $this->aboutData['image'] ?? '',
            'image_alt' => $this->aboutData['image_alt'] ?? '',
            'pillars' => [
                ['title' => $this->aboutData['pillar1_title'] ?? 'Community Participation', 'desc' => $this->aboutData['pillar1_desc'] ?? '', 'icon' => 'users-round'],
                ['title' => $this->aboutData['pillar2_title'] ?? 'Complete Transparency', 'desc' => $this->aboutData['pillar2_desc'] ?? '', 'icon' => 'shield-check'],
                ['title' => $this->aboutData['pillar3_title'] ?? 'Sustainable Growth', 'desc' => $this->aboutData['pillar3_desc'] ?? '', 'icon' => 'sprout'],
            ],
        ];

        $section->draft_settings_json = $payload;
        if ($publish) {
            $section->settings_json = $payload;
            $section->is_published = true;
            $section->published_at = now();
            $section->published_by = Auth::id();
        }
        $section->save();

        AuditService::log(
            $publish ? 'Published updated About section' : 'Saved draft for About section',
            'homepage_sections',
            $section->id,
            null,
            $payload
        );

        session()->flash('success', $publish ? 'About section published live to website.' : 'About draft saved successfully.');
    }

    public function saveSectionHeader(string $sectionKey, array $data, bool $publish = false): void
    {
        $this->checkAuthorization();

        $section = HomepageSection::firstOrCreate(
            ['section_key' => $sectionKey],
            ['title' => ucwords(str_replace('_', ' ', $sectionKey)), 'sort_order' => 1]
        );

        $section->draft_settings_json = $data;
        $section->title = $data['heading'] ?? $section->title;
        $section->subtitle = $data['badge'] ?? $section->subtitle;
        if ($publish) {
            $section->settings_json = $data;
            $section->is_published = true;
            $section->published_at = now();
            $section->published_by = Auth::id();
        }
        $section->save();

        AuditService::log(
            ($publish ? 'Published' : 'Saved draft for') . " {$sectionKey} section header",
            'homepage_sections',
            $section->id,
            null,
            $data
        );

        session()->flash('success', ($publish ? 'Section header published live.' : 'Section header draft saved.'));
    }

    public function saveImpactStatsHeader(bool $publish = false): void
    {
        $this->saveSectionHeader('impact_stats', $this->impactStatsHeader, $publish);
    }

    public function saveProgramsHeader(bool $publish = false): void
    {
        $this->saveSectionHeader('programs', $this->programsHeader, $publish);
    }

    public function saveCommunitiesHeader(bool $publish = false): void
    {
        $this->saveSectionHeader('communities', $this->communitiesHeader, $publish);
    }

    public function saveCampaignHeader(bool $publish = false): void
    {
        $this->saveSectionHeader('campaign', $this->campaignHeader, $publish);
    }

    public function saveEventsHeader(bool $publish = false): void
    {
        $this->saveSectionHeader('events', $this->eventsHeader, $publish);
    }

    public function saveLeadershipHeader(bool $publish = false): void
    {
        $this->saveSectionHeader('leadership', $this->leadershipHeader, $publish);
    }

    public function saveStoriesHeader(bool $publish = false): void
    {
        $this->saveSectionHeader('stories', $this->storiesHeader, $publish);
    }

    public function saveGalleryHeader(bool $publish = false): void
    {
        $this->saveSectionHeader('gallery', $this->galleryHeader, $publish);
    }

    public function saveJoinCta(bool $publish = false): void
    {
        $this->checkAuthorization();

        $section = HomepageSection::firstOrCreate(
            ['section_key' => 'join_cta'],
            ['title' => 'Join CTA Section', 'sort_order' => 11]
        );

        $section->draft_settings_json = $this->joinCtaData;
        if ($publish) {
            $section->settings_json = $this->joinCtaData;
            $section->is_published = true;
            $section->published_at = now();
            $section->published_by = Auth::id();
        }
        $section->save();

        AuditService::log(
            $publish ? 'Published Join CTA section' : 'Saved draft for Join CTA section',
            'homepage_sections',
            $section->id,
            null,
            $this->joinCtaData
        );

        session()->flash('success', $publish ? 'Join CTA section published live.' : 'Join CTA draft saved.');
    }

    public function saveContact(bool $publish = false): void
    {
        $this->checkAuthorization();

        $section = HomepageSection::firstOrCreate(
            ['section_key' => 'contact'],
            ['title' => 'Contact Section', 'sort_order' => 12]
        );

        $section->draft_settings_json = $this->contactData;
        if ($publish) {
            $section->settings_json = $this->contactData;
            $section->is_published = true;
            $section->published_at = now();
            $section->published_by = Auth::id();
        }
        $section->save();

        AuditService::log(
            $publish ? 'Published Contact section' : 'Saved draft for Contact section',
            'homepage_sections',
            $section->id,
            null,
            $this->contactData
        );

        session()->flash('success', $publish ? 'Contact section published live.' : 'Contact draft saved.');
    }

    public function toggleSection(int $id): void
    {
        $this->checkAuthorization();

        $sec = HomepageSection::findOrFail($id);
        $sec->is_enabled = !$sec->is_enabled;
        $sec->save();

        AuditService::log("Toggled homepage section '{$sec->section_key}' status to " . ($sec->is_enabled ? 'enabled' : 'disabled'), 'homepage_sections', $sec->id);
        session()->flash('success', "Section '{$sec->section_key}' visibility toggled.");
    }

    public function moveSection(int $id, string $direction): void
    {
        $this->checkAuthorization();

        $sec = HomepageSection::findOrFail($id);
        $currentSort = $sec->sort_order;

        if ($direction === 'up') {
            $prev = HomepageSection::where('sort_order', '<', $currentSort)->orderByDesc('sort_order')->first();
            if ($prev) {
                $sec->sort_order = $prev->sort_order;
                $prev->sort_order = $currentSort;
                $sec->save();
                $prev->save();
            }
        } elseif ($direction === 'down') {
            $next = HomepageSection::where('sort_order', '>', $currentSort)->orderBy('sort_order')->first();
            if ($next) {
                $sec->sort_order = $next->sort_order;
                $next->sort_order = $currentSort;
                $sec->save();
                $next->save();
            }
        }

        session()->flash('success', 'Section order updated.');
    }

    /* -------------------------------------------------------------
     | Sub-Entity Modal Management (Metrics, Activities, etc.)
     | ------------------------------------------------------------*/

    public function openItemModal(string $type, ?int $id = null): void
    {
        $this->itemModalType = $type;
        $this->editingItemId = $id;
        $this->itemFormData = [];

        if ($id) {
            switch ($type) {
                case 'metric':
                    $m = ImpactMetric::findOrFail($id);
                    $this->itemFormData = $m->toArray();
                    break;
                case 'activity':
                    $a = Activity::findOrFail($id);
                    $this->itemFormData = $a->toArray();
                    break;
                case 'community':
                    $c = Community::findOrFail($id);
                    $this->itemFormData = $c->toArray();
                    break;
                case 'campaign':
                    $cmp = Campaign::findOrFail($id);
                    $this->itemFormData = $cmp->toArray();
                    break;
                case 'event':
                    $e = Event::findOrFail($id);
                    $this->itemFormData = $e->toArray();
                    break;
                case 'leader':
                    $l = Leader::findOrFail($id);
                    $this->itemFormData = $l->toArray();
                    break;
                case 'story':
                    $s = Story::findOrFail($id);
                    $this->itemFormData = $s->toArray();
                    break;
                case 'gallery':
                    $g = GalleryItem::findOrFail($id);
                    $this->itemFormData = $g->toArray();
                    break;
                case 'faq':
                    $f = Faq::findOrFail($id);
                    $this->itemFormData = $f->toArray();
                    break;
            }
        } else {
            // defaults
            if ($type === 'metric') {
                $this->itemFormData = ['label' => '', 'value' => '', 'suffix' => '+', 'description' => '', 'icon' => 'heart-handshake'];
            } elseif ($type === 'activity') {
                $this->itemFormData = ['title' => '', 'description' => '', 'icon' => 'graduation-cap', 'impact_tag' => '', 'color' => 'green', 'image_url' => '', 'image_alt' => ''];
            } elseif ($type === 'community') {
                $this->itemFormData = ['name' => '', 'area' => '', 'description' => '', 'active_volunteers' => '100+ Volunteers', 'active_programs' => '4 Programs Active', 'image_url' => '', 'image_alt' => ''];
            } elseif ($type === 'campaign') {
                $this->itemFormData = ['title' => '', 'badge' => 'FEATURED CAMPAIGN', 'subtitle' => '', 'goal_amount' => 500000, 'raised_amount' => 0, 'supporters_count' => 0, 'days_left' => 30, 'start_date' => now()->format('Y-m-d'), 'end_date' => now()->addDays(30)->format('Y-m-d'), 'is_featured' => true, 'is_active' => true, 'image_url' => '', 'image_alt' => ''];
            } elseif ($type === 'event') {
                $this->itemFormData = ['title' => '', 'category' => 'Healthcare', 'event_date' => now()->addDays(7)->format('Y-m-d'), 'time_info' => '8:30 AM - 2:00 PM', 'start_time' => '08:30', 'end_time' => '14:00', 'location' => 'Coimbatore, Tamil Nadu', 'description' => '', 'image_url' => '', 'image_alt' => '', 'is_active' => true];
            } elseif ($type === 'leader') {
                $this->itemFormData = ['name' => '', 'role' => '', 'bio' => '', 'image_url' => '', 'image_alt' => ''];
            } elseif ($type === 'story') {
                $this->itemFormData = ['title' => '', 'author_info' => '', 'excerpt' => '', 'quote' => '', 'category' => 'Education', 'image_url' => '', 'image_alt' => ''];
            } elseif ($type === 'gallery') {
                $this->itemFormData = ['title' => '', 'category' => 'Community Action', 'location' => 'Tamil Nadu', 'image_url' => '', 'image_alt' => ''];
            } elseif ($type === 'faq') {
                $this->itemFormData = ['question' => '', 'answer' => ''];
            }
        }

        $this->showItemModal = true;
    }

    public function closeItemModal(): void
    {
        $this->showItemModal = false;
        $this->editingItemId = null;
        $this->itemFormData = [];
    }

    public function toggleFeaturedCampaign(int $id): void
    {
        $this->checkAuthorization();

        $camp = Campaign::findOrFail($id);
        if (!$camp->is_featured) {
            Campaign::where('is_featured', true)->update(['is_featured' => false]);
            $camp->is_featured = true;
        } else {
            $camp->is_featured = false;
        }
        $camp->save();

        AuditService::log("Updated featured status for campaign '{$camp->title}' to " . ($camp->is_featured ? 'featured' : 'standard'), 'campaigns', $camp->id);
        session()->flash('success', 'Featured campaign status updated.');
    }

    public function saveItem(): void
    {
        $this->checkAuthorization();

        switch ($this->itemModalType) {
            case 'metric':
                $this->validate(['itemFormData.label' => 'required', 'itemFormData.value' => 'required']);
                if ($this->editingItemId) {
                    ImpactMetric::findOrFail($this->editingItemId)->update($this->itemFormData);
                } else {
                    $max = ImpactMetric::max('sort_order') ?? 0;
                    ImpactMetric::create(array_merge($this->itemFormData, ['sort_order' => $max + 1, 'is_enabled' => true]));
                }
                break;

            case 'activity':
                $this->validate(['itemFormData.title' => 'required', 'itemFormData.description' => 'required']);
                $slug = Str::slug($this->itemFormData['title']);
                if ($this->editingItemId) {
                    Activity::findOrFail($this->editingItemId)->update(array_merge($this->itemFormData, ['slug' => $slug]));
                } else {
                    $max = Activity::max('sort_order') ?? 0;
                    Activity::create(array_merge($this->itemFormData, ['slug' => $slug, 'sort_order' => $max + 1, 'is_active' => true]));
                }
                break;

            case 'community':
                $this->validate(['itemFormData.name' => 'required', 'itemFormData.area' => 'required', 'itemFormData.description' => 'required']);
                if ($this->editingItemId) {
                    Community::findOrFail($this->editingItemId)->update($this->itemFormData);
                } else {
                    $max = Community::max('sort_order') ?? 0;
                    Community::create(array_merge($this->itemFormData, ['sort_order' => $max + 1, 'is_enabled' => true]));
                }
                break;

            case 'campaign':
                $this->validate([
                    'itemFormData.title' => 'required|string|max:255',
                    'itemFormData.goal_amount' => 'required|numeric|min:1',
                ]);
                $slug = Str::slug($this->itemFormData['title']);
                $isFeatured = !empty($this->itemFormData['is_featured']);

                if ($isFeatured) {
                    // Un-feature others to maintain single featured on homepage
                    Campaign::where('id', '!=', $this->editingItemId ?? 0)->where('is_featured', true)->update(['is_featured' => false]);
                }

                $payload = array_merge($this->itemFormData, [
                    'slug' => $slug,
                    'is_featured' => $isFeatured,
                    'is_active' => $this->itemFormData['is_active'] ?? true,
                ]);

                if ($this->editingItemId) {
                    $camp = Campaign::findOrFail($this->editingItemId);
                    $camp->update($payload);
                    AuditService::log("Updated campaign '{$camp->title}'", 'campaigns', $camp->id);
                } else {
                    $camp = Campaign::create($payload);
                    AuditService::log("Created new campaign '{$camp->title}'", 'campaigns', $camp->id);
                }
                break;

            case 'event':
                $this->validate([
                    'itemFormData.title' => 'required|string|max:255',
                    'itemFormData.event_date' => 'required|date',
                    'itemFormData.location' => 'required|string|max:255',
                ]);
                $slug = Str::slug($this->itemFormData['title']);
                $payload = array_merge($this->itemFormData, [
                    'slug' => $slug,
                    'is_active' => $this->itemFormData['is_active'] ?? true,
                ]);

                if ($this->editingItemId) {
                    $ev = Event::findOrFail($this->editingItemId);
                    $ev->update($payload);
                    AuditService::log("Updated event '{$ev->title}'", 'events', $ev->id);
                } else {
                    $max = Event::max('sort_order') ?? 0;
                    $ev = Event::create(array_merge($payload, ['sort_order' => $max + 1]));
                    AuditService::log("Created new event '{$ev->title}'", 'events', $ev->id);
                }
                break;

            case 'leader':
                $this->validate(['itemFormData.name' => 'required', 'itemFormData.role' => 'required', 'itemFormData.bio' => 'required']);
                if ($this->editingItemId) {
                    Leader::findOrFail($this->editingItemId)->update($this->itemFormData);
                } else {
                    $max = Leader::max('sort_order') ?? 0;
                    Leader::create(array_merge($this->itemFormData, ['sort_order' => $max + 1, 'is_active' => true]));
                }
                break;

            case 'story':
                $this->validate(['itemFormData.title' => 'required', 'itemFormData.author_info' => 'required', 'itemFormData.excerpt' => 'required']);
                if ($this->editingItemId) {
                    Story::findOrFail($this->editingItemId)->update($this->itemFormData);
                } else {
                    $max = Story::max('sort_order') ?? 0;
                    Story::create(array_merge($this->itemFormData, ['sort_order' => $max + 1, 'is_active' => true]));
                }
                break;

            case 'gallery':
                $this->validate(['itemFormData.title' => 'required', 'itemFormData.image_url' => 'required']);
                if ($this->editingItemId) {
                    GalleryItem::findOrFail($this->editingItemId)->update($this->itemFormData);
                } else {
                    $max = GalleryItem::max('sort_order') ?? 0;
                    GalleryItem::create(array_merge($this->itemFormData, ['sort_order' => $max + 1, 'is_active' => true]));
                }
                break;

            case 'faq':
                $this->validate(['itemFormData.question' => 'required', 'itemFormData.answer' => 'required']);
                if ($this->editingItemId) {
                    Faq::findOrFail($this->editingItemId)->update($this->itemFormData);
                } else {
                    $max = Faq::max('sort_order') ?? 0;
                    Faq::create(array_merge($this->itemFormData, ['sort_order' => $max + 1, 'is_active' => true]));
                }
                break;
        }

        $this->showItemModal = false;
        session()->flash('success', 'Content saved to database successfully.');
    }

    // Delete confirmation state
    public bool $confirmingDelete = false;
    public string $deleteTargetType = '';
    public ?int $deleteTargetId = null;
    public string $deleteTargetTitle = '';

    public function toggleItemStatus(string $type, int $id): void
    {
        $this->checkAuthorization();

        $model = null;
        $statusField = 'is_active';

        switch ($type) {
            case 'metric':
                $model = ImpactMetric::findOrFail($id);
                $statusField = 'is_enabled';
                break;
            case 'activity':
                $model = Activity::findOrFail($id);
                break;
            case 'community':
                $model = Community::findOrFail($id);
                $statusField = 'is_enabled';
                break;
            case 'campaign':
                $model = Campaign::findOrFail($id);
                break;
            case 'event':
                $model = Event::findOrFail($id);
                break;
            case 'leader':
                $model = Leader::findOrFail($id);
                break;
            case 'story':
                $model = Story::findOrFail($id);
                break;
            case 'gallery':
                $model = GalleryItem::findOrFail($id);
                break;
            case 'faq':
                $model = Faq::findOrFail($id);
                break;
        }

        if ($model) {
            $model->{$statusField} = !$model->{$statusField};
            $model->save();

            AuditService::log(
                "Toggled {$type} #{$id} status to " . ($model->{$statusField} ? 'active/enabled' : 'hidden/disabled'),
                $model->getTable(),
                $model->id
            );

            session()->flash('success', ucfirst($type) . ' status updated.');
        }
    }

    public function moveItem(string $type, int $id, string $direction): void
    {
        $this->checkAuthorization();

        $classMap = [
            'metric' => ImpactMetric::class,
            'activity' => Activity::class,
            'community' => Community::class,
            'event' => Event::class,
            'leader' => Leader::class,
            'story' => Story::class,
            'gallery' => GalleryItem::class,
            'faq' => Faq::class,
        ];

        if (!isset($classMap[$type])) {
            return;
        }

        $modelClass = $classMap[$type];
        $item = $modelClass::findOrFail($id);
        $currentSort = $item->sort_order;

        if ($direction === 'up') {
            $prev = $modelClass::where('sort_order', '<', $currentSort)->orderByDesc('sort_order')->first();
            if ($prev) {
                $item->sort_order = $prev->sort_order;
                $prev->sort_order = $currentSort;
                $item->save();
                $prev->save();
            }
        } elseif ($direction === 'down') {
            $next = $modelClass::where('sort_order', '>', $currentSort)->orderBy('sort_order')->first();
            if ($next) {
                $item->sort_order = $next->sort_order;
                $next->sort_order = $currentSort;
                $item->save();
                $next->save();
            }
        }

        session()->flash('success', ucfirst($type) . ' order updated.');
    }

    public function confirmDeleteItem(string $type, int $id, string $title = ''): void
    {
        $this->deleteTargetType = $type;
        $this->deleteTargetId = $id;
        $this->deleteTargetTitle = $title ?: ucfirst($type) . " #{$id}";
        $this->confirmingDelete = true;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDelete = false;
        $this->deleteTargetType = '';
        $this->deleteTargetId = null;
        $this->deleteTargetTitle = '';
    }

    public function performDelete(): void
    {
        if (!$this->deleteTargetType || !$this->deleteTargetId) {
            $this->cancelDelete();
            return;
        }

        $type = $this->deleteTargetType;
        $id = $this->deleteTargetId;
        $title = $this->deleteTargetTitle;

        $this->deleteItem($type, $id);
        $this->cancelDelete();
    }

    public function deleteItem(string $type, int $id): void
    {
        $this->checkAuthorization();

        switch ($type) {
            case 'metric':
                $item = ImpactMetric::findOrFail($id);
                $title = $item->label;
                $item->delete();
                break;
            case 'activity':
                $item = Activity::findOrFail($id);
                $title = $item->title;
                $item->delete();
                break;
            case 'community':
                $item = Community::findOrFail($id);
                $title = $item->name;
                $item->delete();
                break;
            case 'campaign':
                $item = Campaign::findOrFail($id);
                $title = $item->title;
                $item->delete();
                break;
            case 'event':
                $item = Event::findOrFail($id);
                $title = $item->title;
                $item->delete();
                break;
            case 'leader':
                $item = Leader::findOrFail($id);
                $title = $item->name;
                $item->delete();
                break;
            case 'story':
                $item = Story::findOrFail($id);
                $title = $item->title;
                $item->delete();
                break;
            case 'gallery':
                $item = GalleryItem::findOrFail($id);
                $title = $item->title;
                $item->delete();
                break;
            case 'faq':
                $item = Faq::findOrFail($id);
                $title = $item->question;
                $item->delete();
                break;
        }

        AuditService::log("Deleted {$type}: {$title}", $type, $id);
        session()->flash('success', ucfirst($type) . ' removed successfully.');
    }

    /* -------------------------------------------------------------
     | Media Picker Integration
     | ------------------------------------------------------------*/

    public function openMediaPicker(string $targetProperty): void
    {
        $this->mediaPickerTarget = $targetProperty;
        $this->mediaSearch = '';
        $this->showMediaPickerModal = true;
    }

    public function closeMediaPicker(): void
    {
        $this->showMediaPickerModal = false;
        $this->mediaPickerTarget = '';
    }

    public function selectMediaItem(string $url, ?string $alt = null): void
    {
        if ($this->mediaPickerTarget) {
            data_set($this, $this->mediaPickerTarget, $url);
            if ($this->mediaPickerTarget === 'heroData.image' && $alt) {
                $this->heroData['image_alt'] = $alt;
            } elseif ($this->mediaPickerTarget === 'aboutData.image' && $alt) {
                $this->aboutData['image_alt'] = $alt;
            } elseif ($this->mediaPickerTarget === 'itemFormData.image_url' && $alt && isset($this->itemFormData['image_alt'])) {
                $this->itemFormData['image_alt'] = $alt;
            }
        }
        $this->closeMediaPicker();
        session()->flash('success', 'Media asset selected.');
    }

    public function updatedHeroImageFile(): void
    {
        $this->validate([
            'heroImageFile' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:10240',
        ]);
        $media = $this->storeUploadedFile($this->heroImageFile, 'Hero Image');
        $this->heroData['image'] = $media->url;
        $this->heroData['image_alt'] = $media->alt_text;
        $this->heroImageFile = null;
        session()->flash('success', 'Hero image uploaded and updated successfully.');
    }

    public function updatedAboutImageFile(): void
    {
        $this->validate([
            'aboutImageFile' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:10240',
        ]);
        $media = $this->storeUploadedFile($this->aboutImageFile, 'About Image');
        $this->aboutData['image'] = $media->url;
        $this->aboutData['image_alt'] = $media->alt_text;
        $this->aboutImageFile = null;
        session()->flash('success', 'About section image uploaded and updated successfully.');
    }

    public function updatedItemImageFile(): void
    {
        $this->validate([
            'itemImageFile' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:10240',
        ]);
        $media = $this->storeUploadedFile($this->itemImageFile, ucfirst($this->itemModalType) . ' Image');
        $this->itemFormData['image_url'] = $media->url;
        if (isset($this->itemFormData['image_alt'])) {
            $this->itemFormData['image_alt'] = $media->alt_text;
        }
        $this->itemImageFile = null;
        session()->flash('success', 'Image uploaded and assigned successfully.');
    }

    protected function storeUploadedFile($file, string $label): Media
    {
        $this->checkAuthorization();

        \App\Services\FileSecurityService::validateAndSanitize($file);

        $originalName = $file->getClientOriginalName();
        $mimeType = $file->getMimeType() ?: 'image/jpeg';
        $fileSize = $file->getSize();

        $path = $file->store('media', 'public');
        $fileName = basename($path);

        $width = null;
        $height = null;
        if (str_starts_with($mimeType, 'image/')) {
            $imageSize = @getimagesize($file->getRealPath());
            if ($imageSize) {
                $width = $imageSize[0];
                $height = $imageSize[1];
            }
        }

        $media = Media::create([
            'file_name' => $fileName,
            'original_name' => $originalName,
            'file_path' => $path,
            'disk' => 'public',
            'mime_type' => $mimeType,
            'file_size' => $fileSize,
            'width' => $width,
            'height' => $height,
            'alt_text' => pathinfo($originalName, PATHINFO_FILENAME),
            'title' => pathinfo($originalName, PATHINFO_FILENAME),
            'uploaded_by' => Auth::id(),
        ]);

        AuditService::log("Uploaded {$label} '{$originalName}'", 'media', $media->id, null, [
            'file_name' => $fileName,
            'size' => $fileSize,
        ]);

        return $media;
    }

    public function removeImage(string $targetProperty): void
    {
        data_set($this, $targetProperty, '');
        session()->flash('success', 'Image removed.');
    }

    public function render()
    {
        $sections = HomepageSection::ordered()->get();
        $impactMetrics = ImpactMetric::ordered()->get();
        $activities = Activity::ordered()->get();
        $communities = Community::ordered()->get();
        $campaigns = Campaign::latest()->get();
        $campaign = Campaign::featured()->first() ?? Campaign::first();
        $events = Event::ordered()->get();
        $leaders = Leader::ordered()->get();
        $stories = Story::ordered()->get();
        $gallery = GalleryItem::ordered()->get();
        $faqs = Faq::ordered()->get();

        $mediaList = collect();
        if ($this->showMediaPickerModal) {
            $mediaQuery = Media::latest();
            if (!empty($this->mediaSearch)) {
                $mediaQuery->where(function ($q) {
                    $q->where('original_name', 'like', "%{$this->mediaSearch}%")
                      ->orWhere('title', 'like', "%{$this->mediaSearch}%")
                      ->orWhere('alt_text', 'like', "%{$this->mediaSearch}%");
                });
            }
            $mediaList = $mediaQuery->take(24)->get();
        }

        return view('livewire.admin.homepage-editor', [
            'sections' => $sections,
            'impactMetrics' => $impactMetrics,
            'activities' => $activities,
            'communities' => $communities,
            'campaigns' => $campaigns,
            'campaign' => $campaign,
            'events' => $events,
            'leaders' => $leaders,
            'stories' => $stories,
            'gallery' => $gallery,
            'faqs' => $faqs,
            'mediaList' => $mediaList,
        ]);
    }
}
