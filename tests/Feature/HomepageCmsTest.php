<?php

namespace Tests\Feature;

use App\Livewire\Admin\HomepageEditor;
use App\Models\HomepageSection;
use App\Models\ImpactMetric;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class HomepageCmsTest extends TestCase
{
    use DatabaseTransactions;

    protected User $superAdmin;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->superAdmin = User::firstOrCreate(
            ['email' => 'admin_test@nanbanfoundation.org.in'],
            [
                'name' => 'Super Admin Test',
                'phone' => '+919442000000',
                'password' => bcrypt('SuperPassword123!'),
                'status' => 'active',
            ]
        );
        $this->superAdmin->assignRole('Super Admin');

        $this->regularUser = User::firstOrCreate(
            ['email' => 'member_test@nanbanfoundation.org.in'],
            [
                'name' => 'Regular Member',
                'phone' => '+919442011111',
                'password' => bcrypt('MemberPassword123!'),
                'status' => 'active',
            ]
        );
        $this->regularUser->assignRole('Member');
    }

    public function test_unauthenticated_user_cannot_access_homepage_cms(): void
    {
        $response = $this->get('/admin/website/homepage');
        $response->assertRedirect('/login');
    }

    public function test_unauthorized_user_is_forbidden_from_homepage_cms(): void
    {
        $response = $this->actingAs($this->regularUser)->get('/admin/website/homepage');
        $response->assertStatus(403);
    }

    public function test_super_admin_can_access_homepage_cms(): void
    {
        $response = $this->actingAs($this->superAdmin)->get('/admin/website/homepage');
        $response->assertStatus(200);
        $response->assertSee('Homepage CMS');
    }

    public function test_homepage_cms_loads_existing_hero_data(): void
    {
        Livewire::actingAs($this->superAdmin)
            ->test(HomepageEditor::class)
            ->assertSet('activeSectionKey', 'hero')
            ->assertSet('heroData.heading_line1', 'Together, We Can Build')
            ->assertSet('heroData.heading_line2', 'Stronger Communities.')
            ->assertSet('heroData.badge', 'SERVING COMMUNITIES ACROSS TAMIL NADU');
    }

    public function test_selecting_another_section_changes_active_section_and_loads_data(): void
    {
        Livewire::actingAs($this->superAdmin)
            ->test(HomepageEditor::class)
            ->call('setSection', 'about')
            ->assertSet('activeSectionKey', 'about')
            ->assertSet('aboutData.badge', 'WHO WE ARE')
            ->assertSet('aboutData.heading', 'Creating Change Together With Communities')
            ->call('setSection', 'join_cta')
            ->assertSet('activeSectionKey', 'join_cta')
            ->assertSet('joinCtaData.badge', 'JOIN OUR MOVEMENT');
    }

    public function test_section_switching_preserves_hero_data(): void
    {
        Livewire::actingAs($this->superAdmin)
            ->test(HomepageEditor::class)
            ->set('heroData.heading_line1', 'Edited Hero Line 1')
            ->call('setSection', 'about')
            ->assertSet('activeSectionKey', 'about')
            ->call('setSection', 'hero')
            ->assertSet('activeSectionKey', 'hero')
            ->assertSet('heroData.heading_line1', 'Edited Hero Line 1');
    }

    public function test_save_draft_persists_changes_to_draft_settings(): void
    {
        Livewire::actingAs($this->superAdmin)
            ->test(HomepageEditor::class)
            ->set('heroData.heading_line1', 'New Draft Hero Title')
            ->call('saveHero', false);

        $hero = HomepageSection::where('section_key', 'hero')->first();
        $this->assertEquals('New Draft Hero Title', $hero->draft_settings_json['heading_line1']);
    }

    public function test_publish_persists_to_live_settings_and_reflects_on_homepage(): void
    {
        Livewire::actingAs($this->superAdmin)
            ->test(HomepageEditor::class)
            ->set('heroData.heading_line1', 'Empowering Tamil Nadu')
            ->set('heroData.heading_line2', 'Together With People.')
            ->call('saveHero', true);

        $hero = HomepageSection::where('section_key', 'hero')->first();
        $this->assertEquals('Empowering Tamil Nadu', $hero->settings_json['heading_line1']);
        $this->assertTrue($hero->is_published);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Empowering Tamil Nadu');
        $response->assertSee('Together With People.');
    }

    public function test_disabling_section_removes_it_from_public_homepage(): void
    {
        $hero = HomepageSection::where('section_key', 'hero')->first();
        
        Livewire::actingAs($this->superAdmin)
            ->test(HomepageEditor::class)
            ->call('toggleSection', $hero->id);

        $this->assertFalse($hero->fresh()->is_enabled);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertDontSee('SERVING COMMUNITIES ACROSS TAMIL NADU');
    }

    public function test_sub_item_creation_saves_to_database(): void
    {
        Livewire::actingAs($this->superAdmin)
            ->test(HomepageEditor::class)
            ->call('openItemModal', 'metric')
            ->assertSet('showItemModal', true)
            ->set('itemFormData.value', '99')
            ->set('itemFormData.suffix', '+')
            ->set('itemFormData.label', 'Districts Connected')
            ->set('itemFormData.description', 'Active chapters statewide')
            ->call('saveItem')
            ->assertSet('showItemModal', false);

        $this->assertDatabaseHas('impact_metrics', [
            'label' => 'Districts Connected',
            'value' => '99',
        ]);
    }

    public function test_section_reordering_changes_sort_order(): void
    {
        $sec1 = HomepageSection::where('sort_order', 1)->first();
        $sec2 = HomepageSection::where('sort_order', 2)->first();

        Livewire::actingAs($this->superAdmin)
            ->test(HomepageEditor::class)
            ->call('moveSection', $sec1->id, 'down');

        $this->assertEquals(2, $sec1->fresh()->sort_order);
        $this->assertEquals(1, $sec2->fresh()->sort_order);
    }

    public function test_editing_section_headers_persists_to_database(): void
    {
        Livewire::actingAs($this->superAdmin)
            ->test(HomepageEditor::class)
            ->set('impactStatsHeader.badge', 'VERIFIED NUMBERS')
            ->set('impactStatsHeader.heading', 'Impact Across 38 Tamil Nadu Districts')
            ->call('saveImpactStatsHeader', true);

        $sec = HomepageSection::where('section_key', 'impact_stats')->first();
        $this->assertEquals('Impact Across 38 Tamil Nadu Districts', $sec->title);
        $this->assertEquals('VERIFIED NUMBERS', $sec->subtitle);
    }

    public function test_editing_existing_metric_item_updates_record(): void
    {
        $metric = ImpactMetric::first();

        Livewire::actingAs($this->superAdmin)
            ->test(HomepageEditor::class)
            ->call('openItemModal', 'metric', $metric->id)
            ->assertSet('editingItemId', $metric->id)
            ->assertSet('itemFormData.label', $metric->label)
            ->set('itemFormData.value', '75')
            ->set('itemFormData.label', 'Updated Service Milestone')
            ->call('saveItem')
            ->assertSet('showItemModal', false);

        $this->assertEquals('75', $metric->fresh()->value);
        $this->assertEquals('Updated Service Milestone', $metric->fresh()->label);
    }

    public function test_toggling_item_status_persists_in_database(): void
    {
        $metric = ImpactMetric::first();
        $originalStatus = $metric->is_enabled;

        Livewire::actingAs($this->superAdmin)
            ->test(HomepageEditor::class)
            ->call('toggleItemStatus', 'metric', $metric->id);

        $this->assertEquals(!$originalStatus, $metric->fresh()->is_enabled);
    }

    public function test_moving_item_order_swaps_sort_order(): void
    {
        $metrics = ImpactMetric::orderBy('sort_order')->take(2)->get();
        $m1 = $metrics[0];
        $m2 = $metrics[1];
        $sort1 = $m1->sort_order;
        $sort2 = $m2->sort_order;

        Livewire::actingAs($this->superAdmin)
            ->test(HomepageEditor::class)
            ->call('moveItem', 'metric', $m1->id, 'down');

        $this->assertEquals($sort2, $m1->fresh()->sort_order);
        $this->assertEquals($sort1, $m2->fresh()->sort_order);
    }

    public function test_delete_confirmation_flow_removes_item(): void
    {
        $metric = ImpactMetric::create([
            'label' => 'To Be Deleted Metric',
            'value' => '1',
            'suffix' => '',
            'description' => 'Temporary',
            'sort_order' => 999,
            'is_enabled' => true,
        ]);

        Livewire::actingAs($this->superAdmin)
            ->test(HomepageEditor::class)
            ->call('confirmDeleteItem', 'metric', $metric->id, $metric->label)
            ->assertSet('confirmingDelete', true)
            ->assertSet('deleteTargetId', $metric->id)
            ->call('performDelete')
            ->assertSet('confirmingDelete', false);

        $this->assertDatabaseMissing('impact_metrics', [
            'id' => $metric->id,
        ]);
    }

    public function test_media_picker_selection_and_removal(): void
    {
        Livewire::actingAs($this->superAdmin)
            ->test(HomepageEditor::class)
            ->call('openMediaPicker', 'heroData.image')
            ->assertSet('showMediaPickerModal', true)
            ->assertSet('mediaPickerTarget', 'heroData.image')
            ->call('selectMediaItem', 'https://example.com/new-hero.jpg', 'Hero Image Alt Text')
            ->assertSet('showMediaPickerModal', false)
            ->assertSet('heroData.image', 'https://example.com/new-hero.jpg')
            ->assertSet('heroData.image_alt', 'Hero Image Alt Text')
            ->call('removeImage', 'heroData.image')
            ->assertSet('heroData.image', '');
    }

    public function test_campaign_add_edit_and_featured_toggle(): void
    {
        Livewire::actingAs($this->superAdmin)
            ->test(HomepageEditor::class)
            ->call('openItemModal', 'campaign')
            ->assertSet('showItemModal', true)
            ->assertSet('itemModalType', 'campaign')
            ->set('itemFormData.title', 'Rural Girls Education Fund 2026')
            ->set('itemFormData.badge', 'SPECIAL DRIVE')
            ->set('itemFormData.subtitle', 'Empowering 500 first-generation female learners')
            ->set('itemFormData.goal_amount', 1500000)
            ->set('itemFormData.raised_amount', 750000)
            ->set('itemFormData.supporters_count', 320)
            ->set('itemFormData.days_left', 45)
            ->set('itemFormData.image_url', 'https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&w=800&q=80')
            ->set('itemFormData.image_alt', 'Rural students learning')
            ->set('itemFormData.is_featured', false)
            ->set('itemFormData.is_active', true)
            ->call('saveItem')
            ->assertSet('showItemModal', false);

        $this->assertDatabaseHas('campaigns', [
            'title' => 'Rural Girls Education Fund 2026',
            'goal_amount' => 1500000,
            'is_featured' => false,
        ]);

        $campaign = \App\Models\Campaign::where('title', 'Rural Girls Education Fund 2026')->first();
        $this->assertNotNull($campaign);
        $this->assertEquals('https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&w=800&q=80', $campaign->resolved_image_url);

        // Edit campaign
        Livewire::actingAs($this->superAdmin)
            ->test(HomepageEditor::class)
            ->call('openItemModal', 'campaign', $campaign->id)
            ->assertSet('itemFormData.title', 'Rural Girls Education Fund 2026')
            ->assertSet('itemFormData.goal_amount', 1500000)
            ->set('itemFormData.title', 'Updated Rural Girls Education Fund')
            ->call('saveItem')
            ->assertSet('showItemModal', false);

        $this->assertEquals('Updated Rural Girls Education Fund', $campaign->fresh()->title);

        // Toggle featured ON
        Livewire::actingAs($this->superAdmin)
            ->test(HomepageEditor::class)
            ->call('toggleFeaturedCampaign', $campaign->id);

        $this->assertTrue($campaign->fresh()->is_featured);

        // Toggle featured OFF
        Livewire::actingAs($this->superAdmin)
            ->test(HomepageEditor::class)
            ->call('toggleFeaturedCampaign', $campaign->id);

        $this->assertFalse($campaign->fresh()->is_featured);
    }

    public function test_event_creation_editing_and_image_handling(): void
    {
        Livewire::actingAs($this->superAdmin)
            ->test(HomepageEditor::class)
            ->call('openItemModal', 'event')
            ->assertSet('showItemModal', true)
            ->assertSet('itemModalType', 'event')
            ->set('itemFormData.title', 'Statewide Youth Leadership Summit 2026')
            ->set('itemFormData.category', 'Community Development')
            ->set('itemFormData.description', 'Annual youth leadership conference for rural youth.')
            ->set('itemFormData.event_date', '2026-11-20')
            ->set('itemFormData.start_time', '09:00')
            ->set('itemFormData.end_time', '17:00')
            ->set('itemFormData.location', 'Madurai Cultural Centre')
            ->set('itemFormData.image_url', 'https://images.unsplash.com/photo-1511632765486-a01980e01a18?auto=format&fit=crop&w=800&q=80')
            ->set('itemFormData.is_active', true)
            ->call('saveItem')
            ->assertSet('showItemModal', false);

        $this->assertDatabaseHas('events', [
            'title' => 'Statewide Youth Leadership Summit 2026',
            'category' => 'Community Development',
            'location' => 'Madurai Cultural Centre',
        ]);

        $event = \App\Models\Event::where('title', 'Statewide Youth Leadership Summit 2026')->first();
        $this->assertNotNull($event);
        $this->assertEquals('https://images.unsplash.com/photo-1511632765486-a01980e01a18?auto=format&fit=crop&w=800&q=80', $event->resolved_image_url);

        // Edit event
        Livewire::actingAs($this->superAdmin)
            ->test(HomepageEditor::class)
            ->call('openItemModal', 'event', $event->id)
            ->assertSet('itemFormData.title', 'Statewide Youth Leadership Summit 2026')
            ->set('itemFormData.location', 'Trichy Central Auditorium')
            ->call('saveItem')
            ->assertSet('showItemModal', false);

        $this->assertEquals('Trichy Central Auditorium', $event->fresh()->location);
    }

    public function test_public_homepage_contains_zero_developer_branding_and_uses_ngo_identity(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Verify zero developer branding appears in public output
        $content = $response->getContent();
        $this->assertStringNotContainsStringIgnoringCase('ShanLux', $content);
        $this->assertStringNotContainsString('ShanLux NGO Platform', $content);
        $this->assertStringNotContainsString('ShanLux IT Services', $content);

        // Verify public site uses NGO identity
        $response->assertSee('NANBAN SOCIAL FOUNDATION');
        $response->assertSee('Serving Communities Across Tamil Nadu');
        $response->assertSee('Community Impact');
    }

    public function test_admin_portal_retains_internal_developer_identity(): void
    {
        $response = $this->actingAs($this->superAdmin)->get('/admin/website/homepage');
        $response->assertStatus(200);
        $response->assertSee('ShanLux IT Services');
    }

    public function test_hero_media_picker_selection_and_persistence(): void
    {
        Livewire::actingAs($this->superAdmin)
            ->test(HomepageEditor::class)
            ->call('openMediaPicker', 'heroData.image')
            ->assertSet('showMediaPickerModal', true)
            ->assertSet('mediaPickerTarget', 'heroData.image')
            ->call('selectMediaItem', 'https://images.unsplash.com/photo-1577896851231-70ef18881754', 'Tamil Nadu Community Mentorship')
            ->assertSet('showMediaPickerModal', false)
            ->assertSet('heroData.image', 'https://images.unsplash.com/photo-1577896851231-70ef18881754')
            ->assertSet('heroData.image_alt', 'Tamil Nadu Community Mentorship')
            ->call('saveHero', true);

        $section = HomepageSection::where('section_key', 'hero')->first();
        $this->assertNotNull($section);
        $this->assertEquals('https://images.unsplash.com/photo-1577896851231-70ef18881754', $section->settings['image']);
        $this->assertEquals('Tamil Nadu Community Mentorship', $section->settings['image_alt']);
    }

    public function test_about_section_save_draft_and_publish(): void
    {
        Livewire::actingAs($this->superAdmin)
            ->test(HomepageEditor::class)
            ->set('aboutData.heading', 'Empowering Grassroots Tamil Nadu')
            ->set('aboutData.p1', 'Grassroots work across rural districts.')
            ->set('aboutData.p2', 'Community participation at the core.')
            ->call('saveAbout', true);

        $about = HomepageSection::where('section_key', 'about')->first();
        $this->assertEquals('Empowering Grassroots Tamil Nadu', $about->settings_json['heading']);
        $this->assertTrue($about->is_published);
    }

    public function test_join_cta_and_contact_sections_save(): void
    {
        Livewire::actingAs($this->superAdmin)
            ->test(HomepageEditor::class)
            ->set('joinCtaData.heading', 'Become a Volunteer Today')
            ->set('joinCtaData.description', 'Help build sustainable communities in Tamil Nadu.')
            ->call('saveJoinCta', true)
            ->set('contactData.heading', 'Reach Out to Our Team')
            ->set('contactData.description', 'Send us your queries and questions.')
            ->call('saveContact', true);

        $joinCta = HomepageSection::where('section_key', 'join_cta')->first();
        $this->assertEquals('Become a Volunteer Today', $joinCta->settings_json['heading']);

        $contact = HomepageSection::where('section_key', 'contact')->first();
        $this->assertEquals('Reach Out to Our Team', $contact->settings_json['heading']);
    }

    public function test_program_and_community_crud_operations(): void
    {
        // Program CRUD
        Livewire::actingAs($this->superAdmin)
            ->test(HomepageEditor::class)
            ->call('openItemModal', 'activity')
            ->set('itemFormData.title', 'Mobile Digital Literacy')
            ->set('itemFormData.description', 'Bringing computer vans to village schools.')
            ->set('itemFormData.impact_tag', '1,500+ Rural Kids')
            ->set('itemFormData.color', 'teal')
            ->call('saveItem');

        $this->assertDatabaseHas('activities', [
            'title' => 'Mobile Digital Literacy',
            'impact_tag' => '1,500+ Rural Kids',
        ]);

        // Community CRUD
        Livewire::actingAs($this->superAdmin)
            ->test(HomepageEditor::class)
            ->call('openItemModal', 'community')
            ->set('itemFormData.name', 'Tirunelveli Region')
            ->set('itemFormData.area', 'Ambasamudram & Tenkasi border')
            ->set('itemFormData.description', 'Farmer collective and youth learning centers.')
            ->call('saveItem');

        $this->assertDatabaseHas('communities', [
            'name' => 'Tirunelveli Region',
        ]);
    }

    public function test_leader_story_gallery_faq_crud_operations(): void
    {
        // Leader
        Livewire::actingAs($this->superAdmin)
            ->test(HomepageEditor::class)
            ->call('openItemModal', 'leader')
            ->set('itemFormData.name', 'Dr. Sundaram Pillai')
            ->set('itemFormData.role', 'Healthcare Director')
            ->set('itemFormData.bio', 'Public health physician dedicated to village sanitation.')
            ->call('saveItem');

        $this->assertDatabaseHas('leaders', ['name' => 'Dr. Sundaram Pillai']);

        // Story
        Livewire::actingAs($this->superAdmin)
            ->test(HomepageEditor::class)
            ->call('openItemModal', 'story')
            ->set('itemFormData.title', 'Kavitha\'s Journey to College')
            ->set('itemFormData.author_info', 'Kavitha, Salem District')
            ->set('itemFormData.excerpt', 'First engineer from her village.')
            ->set('itemFormData.quote', 'Education transformed my entire family\'s future.')
            ->call('saveItem');

        $this->assertDatabaseHas('stories', ['title' => 'Kavitha\'s Journey to College']);

        // Gallery
        Livewire::actingAs($this->superAdmin)
            ->test(HomepageEditor::class)
            ->call('openItemModal', 'gallery')
            ->set('itemFormData.title', 'Tree Plantation Drive 2026')
            ->set('itemFormData.category', 'Environment')
            ->set('itemFormData.image_url', 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09')
            ->call('saveItem');

        $this->assertDatabaseHas('gallery_items', ['title' => 'Tree Plantation Drive 2026']);

        // FAQ
        Livewire::actingAs($this->superAdmin)
            ->test(HomepageEditor::class)
            ->call('openItemModal', 'faq')
            ->set('itemFormData.question', 'How can college students volunteer?')
            ->set('itemFormData.answer', 'Students can join our weekend drives and tutoring workshops.')
            ->call('saveItem');

        $this->assertDatabaseHas('faqs', ['question' => 'How can college students volunteer?']);
    }
}
