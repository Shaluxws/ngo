<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\Area;
use App\Models\Campaign;
use App\Models\Community;
use App\Models\District;
use App\Models\Event;
use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\HomepageSection;
use App\Models\ImpactMetric;
use App\Models\Leader;
use App\Models\SeoSetting;
use App\Models\Story;
use Illuminate\Database\Seeder;

class HomepageContentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Homepage Sections
        $sections = [
            [
                'section_key' => 'hero',
                'title' => 'Together, We Can Build Stronger Communities.',
                'subtitle' => 'Working hand-in-hand with grassroots communities, dedicated volunteers, and local leaders to foster sustainable social impact, inclusive education, and lasting dignity across Tamil Nadu.',
                'content' => null,
                'sort_order' => 1,
                'is_enabled' => true,
                'settings_json' => [
                    'badge' => 'SERVING COMMUNITIES ACROSS TAMIL NADU',
                    'heading_line1' => 'Together, We Can Build',
                    'heading_line2' => 'Stronger Communities.',
                    'subheading' => 'Working hand-in-hand with grassroots communities, dedicated volunteers, and local leaders to foster sustainable social impact, inclusive education, and lasting dignity across Tamil Nadu.',
                    'primary_cta' => 'Support Our Mission',
                    'primary_cta_url' => '#campaign',
                    'secondary_cta' => 'Join Our Community',
                    'secondary_cta_url' => '#join-cta',
                    'image' => 'https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&w=1200&q=80',
                    'image_alt' => 'Tamil Nadu community volunteers and children engaged in an interactive learning workshop',
                    'stats_count' => '10,000+',
                    'stats_label' => 'Lives Reached in 2026',
                    'badge_trust' => '100% Volunteer Driven & Transparent',
                ],
            ],
            [
                'section_key' => 'impact_stats',
                'title' => 'Our Community Impact',
                'subtitle' => 'Measurable difference delivered directly to Tamil Nadu families.',
                'content' => null,
                'sort_order' => 2,
                'is_enabled' => true,
                'settings_json' => [],
            ],
            [
                'section_key' => 'about',
                'title' => 'Creating Change Together With Communities',
                'subtitle' => 'WHO WE ARE',
                'content' => 'We work alongside grassroots communities across Tamil Nadu to identify critical local challenges and co-create practical, sustainable solutions that enrich everyday lives.',
                'sort_order' => 3,
                'is_enabled' => true,
                'settings_json' => [
                    'badge' => 'WHO WE ARE',
                    'heading' => 'Creating Change Together With Communities',
                    'p1' => 'We work alongside grassroots communities across Tamil Nadu to identify critical local challenges and co-create practical, sustainable solutions that enrich everyday lives.',
                    'p2' => 'Our community-first approach is anchored in dignity, active citizen participation, transparent governance, and long-term socio-economic empowerment. Rather than imposing external solutions, we nurture local leadership.',
                    'image' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1000&q=80',
                    'image_alt' => 'Classroom education and village student mentorship program in Tamil Nadu',
                    'pillars' => [
                        [
                            'title' => 'Community Participation',
                            'desc' => 'Every program is planned and driven in collaboration with local community members and ward representatives.',
                            'icon' => 'users-round',
                        ],
                        [
                            'title' => 'Complete Transparency',
                            'desc' => 'Open reporting, clear financial accountability, and regular community social audits.',
                            'icon' => 'shield-check',
                        ],
                        [
                            'title' => 'Sustainable Growth',
                            'desc' => 'Focusing on skill-building and self-reliance rather than one-time short-term relief.',
                            'icon' => 'sprout',
                        ],
                    ],
                ],
            ],
            [
                'section_key' => 'programs',
                'title' => 'Programs Built for Grassroots Empowerment',
                'subtitle' => 'WHAT WE DO',
                'content' => null,
                'sort_order' => 4,
                'is_enabled' => true,
                'settings_json' => [
                    'badge' => 'WHAT WE DO',
                    'heading' => 'Programs Built for Grassroots Empowerment',
                    'description' => 'Targeted, multi-dimensional interventions structured to break cycles of poverty and nurture self-sustaining rural and peri-urban hubs.',
                ],
            ],
            [
                'section_key' => 'communities',
                'title' => 'Our Active Regional Community Focal Points',
                'subtitle' => 'WHERE WE WORK',
                'content' => null,
                'sort_order' => 5,
                'is_enabled' => true,
                'settings_json' => [
                    'badge' => 'WHERE WE WORK',
                    'heading' => 'Our Active Regional Community Focal Points',
                    'description' => 'Rooted in villages, taluks, and urban wards across Western & Coastal Tamil Nadu.',
                ],
            ],
            [
                'section_key' => 'campaign',
                'title' => 'Rural Student Digital Learning & STEM Labs',
                'subtitle' => 'FEATURED CAMPAIGN',
                'content' => null,
                'sort_order' => 6,
                'is_enabled' => true,
                'settings_json' => [],
            ],
            [
                'section_key' => 'events',
                'title' => 'Upcoming Community Action & Outreach Events',
                'subtitle' => 'GET INVOLVED LOCALLY',
                'content' => null,
                'sort_order' => 7,
                'is_enabled' => true,
                'settings_json' => [
                    'badge' => 'GET INVOLVED LOCALLY',
                    'heading' => 'Upcoming Community Action & Outreach Events',
                    'description' => 'Join our weekend field drives, medical camps, and student mentorship sessions across Tamil Nadu.',
                ],
            ],
            [
                'section_key' => 'leadership',
                'title' => 'Guided by Dedicated Community Leaders & Trustees',
                'subtitle' => 'OUR TRUSTEES & COORDINATORS',
                'content' => null,
                'sort_order' => 8,
                'is_enabled' => true,
                'settings_json' => [
                    'badge' => 'OUR TRUSTEES & COORDINATORS',
                    'heading' => 'Guided by Dedicated Community Leaders & Trustees',
                    'description' => 'Experienced educators, doctors, social workers, and organizers serving with unconditional transparency.',
                ],
            ],
            [
                'section_key' => 'stories',
                'title' => 'Real Journeys of Resilience & Transformation',
                'subtitle' => 'COMMUNITY VOICES',
                'content' => null,
                'sort_order' => 9,
                'is_enabled' => true,
                'settings_json' => [
                    'badge' => 'COMMUNITY VOICES',
                    'heading' => 'Real Journeys of Resilience & Transformation',
                    'description' => 'Heartwarming stories of change created when local families and dedicated volunteers work together.',
                ],
            ],
            [
                'section_key' => 'gallery',
                'title' => 'Moments of Action, Learning & Fellowship',
                'subtitle' => 'FIELD GLIMPSES',
                'content' => null,
                'sort_order' => 10,
                'is_enabled' => true,
                'settings_json' => [
                    'badge' => 'FIELD GLIMPSES',
                    'heading' => 'Moments of Action, Learning & Fellowship',
                    'description' => 'Glimpses from classroom workshops, health camps, tree drives, and women collective gatherings.',
                ],
            ],
            [
                'section_key' => 'join_cta',
                'title' => 'Ready to Make a Meaningful Difference in Tamil Nadu?',
                'subtitle' => 'JOIN OUR MOVEMENT',
                'content' => null,
                'sort_order' => 11,
                'is_enabled' => true,
                'settings_json' => [
                    'badge' => 'JOIN OUR MOVEMENT',
                    'heading' => 'Ready to Make a Meaningful Difference in Tamil Nadu?',
                    'description' => 'Whether you can contribute your time as a weekend volunteer, share digital skills, or support a child’s education — your participation matters.',
                    'primary_button_text' => 'Register as Volunteer',
                    'primary_button_url' => '#contact',
                    'secondary_button_text' => 'Support Our Mission',
                    'secondary_button_url' => '#campaign',
                ],
            ],
            [
                'section_key' => 'contact',
                'title' => 'Connect With Our District Coordinators & Head Office',
                'subtitle' => 'GET IN TOUCH',
                'content' => null,
                'sort_order' => 12,
                'is_enabled' => true,
                'settings_json' => [
                    'badge' => 'GET IN TOUCH',
                    'heading' => 'Connect With Our District Coordinators & Head Office',
                    'description' => 'Have questions about our programs, volunteer orientations, or CSR partnerships? Reach out directly.',
                ],
            ],
        ];

        foreach ($sections as $sec) {
            HomepageSection::updateOrCreate(
                ['section_key' => $sec['section_key']],
                array_merge($sec, [
                    'draft_settings_json' => $sec['settings_json'],
                    'is_published' => true,
                    'published_at' => now(),
                ])
            );
        }

        // 2. Impact Metrics
        $metrics = [
            ['value' => '10+', 'label' => 'Years of Service', 'description' => 'Dedicated grassroots presence across districts', 'icon' => 'calendar', 'sort_order' => 1],
            ['value' => '25+', 'label' => 'Communities Served', 'description' => 'Active rural & peri-urban focal centers', 'icon' => 'map-pin', 'sort_order' => 2],
            ['value' => '500+', 'label' => 'Active Volunteers', 'description' => 'Students, professionals & elders uniting', 'icon' => 'users', 'sort_order' => 3],
            ['value' => '10,000+', 'label' => 'Lives Reached', 'description' => 'Students, families & self-help groups empowered', 'icon' => 'heart-handshake', 'sort_order' => 4],
        ];
        foreach ($metrics as $m) {
            ImpactMetric::updateOrCreate(
                ['label' => $m['label']],
                array_merge($m, ['is_enabled' => true])
            );
        }

        // 3. Activity Categories & Activities
        $cat = ActivityCategory::firstOrCreate(['slug' => 'empowerment'], ['name' => 'Empowerment Programs', 'sort_order' => 1]);
        $activities = [
            [
                'title' => 'Education & Skill Development',
                'slug' => 'education-skill-development',
                'description' => 'After-school learning centers, science labs, scholarship guidance, and vocational digital skills for first-generation learners.',
                'icon' => 'graduation-cap',
                'impact_tag' => '3,200+ Students Supported',
                'color' => 'green',
                'sort_order' => 1,
            ],
            [
                'title' => 'Community Development',
                'slug' => 'community-development',
                'description' => 'Strengthening village water security, local library revival, rural community centers, and neighbourhood solidarity initiatives.',
                'icon' => 'building-2',
                'impact_tag' => '25+ Village Clusters',
                'color' => 'teal',
                'sort_order' => 2,
            ],
            [
                'title' => 'Preventive Healthcare',
                'slug' => 'preventive-healthcare',
                'description' => 'Monthly village medical checkups, maternal nutrition awareness, eye care camps, and elderly wellness screenings.',
                'icon' => 'heart-pulse',
                'impact_tag' => '45+ Health Camps Held',
                'color' => 'emerald',
                'sort_order' => 3,
            ],
            [
                'title' => 'Environment & Afforestation',
                'slug' => 'environment-afforestation',
                'description' => 'Miyawaki urban forests, lake rejuvenation, indigenous seed banks, and community plastic-reduction drives.',
                'icon' => 'leaf',
                'impact_tag' => '20,000+ Native Trees Planted',
                'color' => 'green',
                'sort_order' => 4,
            ],
            [
                'title' => 'Women & Youth Empowerment',
                'slug' => 'women-youth-empowerment',
                'description' => 'Livelihood micro-enterprises for self-help groups (SHGs), tailorship training, and youth leadership fellowships.',
                'icon' => 'sparkles',
                'impact_tag' => '850+ Women Artisans',
                'color' => 'amber',
                'sort_order' => 5,
            ],
            [
                'title' => 'Social Welfare & Senior Care',
                'slug' => 'social-welfare-senior-care',
                'description' => 'Connecting marginalized families with government welfare schemes, emergency aid, and companionship for senior citizens.',
                'icon' => 'hand-heart',
                'impact_tag' => '1,400+ Families Assisted',
                'color' => 'teal',
                'sort_order' => 6,
            ],
        ];
        foreach ($activities as $act) {
            Activity::updateOrCreate(
                ['slug' => $act['slug']],
                array_merge($act, ['category_id' => $cat->id, 'is_active' => true])
            );
        }

        // 4. Districts, Areas, Communities
        $cbe = District::firstOrCreate(['name' => 'Coimbatore', 'code' => 'CBE']);
        $chn = District::firstOrCreate(['name' => 'Chennai', 'code' => 'CHN']);
        $erd = District::firstOrCreate(['name' => 'Erode', 'code' => 'ERD']);
        $tpr = District::firstOrCreate(['name' => 'Tiruppur', 'code' => 'TPR']);

        $cbeArea = Area::firstOrCreate(['district_id' => $cbe->id, 'name' => 'Pollachi & Anaikatti']);
        $chnArea = Area::firstOrCreate(['district_id' => $chn->id, 'name' => 'North Chennai']);
        $erdArea = Area::firstOrCreate(['district_id' => $erd->id, 'name' => 'Bhavani & Sathyamangalam']);
        $tprArea = Area::firstOrCreate(['district_id' => $tpr->id, 'name' => 'Avinashi & Dharapuram']);

        $communities = [
            [
                'district_id' => $cbe->id,
                'area_id' => $cbeArea->id,
                'name' => 'Coimbatore District',
                'area' => 'Pollachi & Anaikatti Hills',
                'description' => 'Focusing on tribal education centers, afforestation drives, and eco-friendly farming practices for indigenous communities.',
                'active_volunteers' => '140+ Volunteers',
                'active_programs' => '6 Programs Active',
                'image_url' => 'https://images.unsplash.com/photo-1596461404969-9ae70f2830c1?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 1,
            ],
            [
                'district_id' => $chn->id,
                'area_id' => $chnArea->id,
                'name' => 'Chennai District',
                'area' => 'Ennore & North Chennai Clusters',
                'description' => 'Youth computer literacy centers, coastal clean-up task forces, and after-school tutoring for urban settlement students.',
                'active_volunteers' => '180+ Volunteers',
                'active_programs' => '8 Programs Active',
                'image_url' => 'https://images.unsplash.com/photo-1582213782179-e0d53f98f2ca?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 2,
            ],
            [
                'district_id' => $erd->id,
                'area_id' => $erdArea->id,
                'name' => 'Erode District',
                'area' => 'Bhavani & Sathyamangalam Belt',
                'description' => 'Weavers’ self-help group digital marketing training, child health camps, and traditional lake restoration efforts.',
                'active_volunteers' => '95+ Volunteers',
                'active_programs' => '4 Programs Active',
                'image_url' => 'https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 3,
            ],
            [
                'district_id' => $tpr->id,
                'area_id' => $tprArea->id,
                'name' => 'Tiruppur District',
                'area' => 'Avinashi & Dharapuram Clusters',
                'description' => 'Apparel worker family support, night study centers for children, and groundwater recharge well conservation.',
                'active_volunteers' => '110+ Volunteers',
                'active_programs' => '5 Programs Active',
                'image_url' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 4,
            ],
        ];
        foreach ($communities as $c) {
            Community::updateOrCreate(
                ['name' => $c['name']],
                array_merge($c, ['is_enabled' => true])
            );
        }

        // 5. Featured Campaign
        Campaign::updateOrCreate(
            ['slug' => 'rural-student-digital-learning'],
            [
                'title' => 'Rural Student Digital Learning & STEM Labs',
                'badge' => 'FEATURED CAMPAIGN',
                'subtitle' => 'Equipping 12 government and community schools across rural Tamil Nadu with modern solar-powered computer labs and regional STEM kits.',
                'raised_amount' => 345000,
                'goal_amount' => 500000,
                'supporters_count' => 284,
                'start_date' => now()->subDays(12),
                'end_date' => now()->addDays(18),
                'image_url' => 'https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?auto=format&fit=crop&w=1000&q=80',
                'image_alt' => 'Rural students learning on digital tablets in a Tamil Nadu smart classroom',
                'impact_bullets' => [
                    'Provides 120 refurbished laptops and solar back-up units',
                    'Trains 18 local youth as community digital tutors',
                    'Benefits 2,400+ first-generation school students',
                    '100% transparent expense audit published quarterly',
                ],
                'suggested_amounts' => [500, 1000, 2500, 5000, 10000],
                'is_featured' => true,
                'is_active' => true,
            ]
        );

        // 6. Upcoming Events
        $events = [
            [
                'title' => 'Comprehensive Community Health & Eye Screening Camp',
                'slug' => 'health-eye-screening-camp',
                'category' => 'Healthcare',
                'event_date' => '2026-10-18',
                'time_info' => '8:30 AM - 2:00 PM',
                'location' => 'Community Hall, Perur, Coimbatore',
                'description' => 'Free general health checkup, pediatric screening, blood pressure diagnostics, and distribution of subsidized reading glasses.',
                'image_url' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=600&q=80',
                'sort_order' => 1,
            ],
            [
                'title' => 'Youth Leadership & Digital Skills Bootcamp',
                'slug' => 'youth-leadership-skills-bootcamp',
                'category' => 'Youth Workshop',
                'event_date' => '2026-10-25',
                'time_info' => '10:00 AM - 4:30 PM',
                'location' => 'Town Hall Auditorium, Erode',
                'description' => 'Hands-on training in resume creation, practical spoken English, basics of AI tools, and personality development for college youth.',
                'image_url' => 'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=600&q=80',
                'sort_order' => 2,
            ],
            [
                'title' => 'Noyyal Basin Native Tree Plantation & Seed Ball Drive',
                'slug' => 'noyyal-tree-plantation-drive',
                'category' => 'Environment',
                'event_date' => '2026-11-08',
                'time_info' => '6:30 AM - 11:00 AM',
                'location' => 'River Basin Zone, Tiruppur',
                'description' => 'Planting 1,500 native saplings including Neem, Pungai, and Marutham along the river catchment belt with 100+ volunteers.',
                'image_url' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=600&q=80',
                'sort_order' => 3,
            ],
        ];
        foreach ($events as $ev) {
            Event::updateOrCreate(
                ['slug' => $ev['slug']],
                array_merge($ev, ['is_active' => true])
            );
        }

        // 7. Leadership
        $leaders = [
            [
                'name' => 'Dr. K. Senthilvelan, Ph.D.',
                'role' => 'Founder & Managing Trustee',
                'bio' => 'Former education researcher with 22+ years of grassroots rural development and public policy experience in Western Tamil Nadu.',
                'image_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=500&q=80',
                'sort_order' => 1,
            ],
            [
                'name' => 'Mrs. Radha Sundaram, M.S.W.',
                'role' => 'Director - Women & Community Welfare',
                'bio' => 'Dedicated social worker championing self-help group micro-financing, maternal health awareness, and gender dignity initiatives.',
                'image_url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=500&q=80',
                'sort_order' => 2,
            ],
            [
                'name' => 'Mr. P. Vijay Anand, B.E.',
                'role' => 'Lead - Youth & Volunteer Engagement',
                'bio' => 'Passionate community organizer coordinating over 500+ active student and professional volunteers across 4 district clusters.',
                'image_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=500&q=80',
                'sort_order' => 3,
            ],
            [
                'name' => 'Dr. Meenakshi Ramanathan',
                'role' => 'Advisory Head - Rural Health Programs',
                'bio' => 'Senior medical practitioner coordinating mobile clinics, pediatric nutrition screenings, and rural preventive health camps.',
                'image_url' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=500&q=80',
                'sort_order' => 4,
            ],
        ];
        foreach ($leaders as $lead) {
            Leader::updateOrCreate(
                ['name' => $lead['name']],
                array_merge($lead, ['is_active' => true])
            );
        }

        // 8. Stories
        $stories = [
            [
                'title' => 'From School Dropout Risk to College Engineering Merit',
                'author_info' => 'Kavitha S., 19 — Coimbatore Community Center',
                'excerpt' => 'When Kavitha’s family faced severe agricultural hardship, she was on the verge of stopping school. Through Nanban’s evening learning center, mentorship, and tuition support, she excelled in her higher secondary exams and earned a merit engineering seat.',
                'quote' => 'The teachers and mentors believed in me when I felt completely hopeless. Today, I am the first person in my village pursuing a professional degree.',
                'category' => 'Education',
                'image_url' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 1,
            ],
            [
                'title' => 'How 40 Rural Women Built a Sustainable Coir Enterprise',
                'author_info' => 'Anuradha & Shanti — Bhavani Women SHG, Erode',
                'excerpt' => 'With hands-on skills training in value-added eco-friendly coir handicraft production, packaging, and digital marketplaces, our women’s collective now generates dependable monthly household income for 40 families.',
                'quote' => 'We went from daily wage uncertainty to running our own registered artisan cluster with dignity and financial security.',
                'category' => 'Livelihood',
                'image_url' => 'https://images.unsplash.com/photo-1589156280159-27698a70f29e?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 2,
            ],
            [
                'title' => 'Reviving a 15-Acre Village Lake in Tiruppur Catchment',
                'author_info' => 'Muthusamy & Youth Eco-Volunteers, Dharapuram',
                'excerpt' => 'Mobilizing 150 local farmers, college students, and village elders, the dry, silted lake basin was desilted, strengthened with stone bunds, and encircled with 1,200 native palm and neem trees before the monsoon.',
                'quote' => 'Within one monsoon season, groundwater levels in our borewells surged by 45 feet across three neighbouring hamlets.',
                'category' => 'Environment',
                'image_url' => 'https://images.unsplash.com/photo-1464226184884-fa280b87c399?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 3,
            ],
        ];
        foreach ($stories as $st) {
            Story::updateOrCreate(
                ['title' => $st['title']],
                array_merge($st, ['is_active' => true])
            );
        }

        // 9. Gallery Items
        $gallery = [
            ['title' => 'Classroom STEM Workshop', 'category' => 'Education', 'location' => 'Pollachi', 'image_url' => 'https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&w=800&q=80', 'sort_order' => 1],
            ['title' => 'Village Free Health Camp', 'category' => 'Healthcare', 'location' => 'Coimbatore', 'image_url' => 'https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?auto=format&fit=crop&w=800&q=80', 'sort_order' => 2],
            ['title' => 'Native Tree Plantation Drive', 'category' => 'Environment', 'location' => 'Tiruppur', 'image_url' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=800&q=80', 'sort_order' => 3],
            ['title' => 'Women SHG Skill Workshop', 'category' => 'Livelihood', 'location' => 'Erode', 'image_url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=800&q=80', 'sort_order' => 4],
            ['title' => 'Youth Volunteer Gathering', 'category' => 'Community', 'location' => 'Chennai', 'image_url' => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=800&q=80', 'sort_order' => 5],
            ['title' => 'Child Nutrition & Milk Distribution', 'category' => 'Welfare', 'location' => 'Anaikatti', 'image_url' => 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&w=800&q=80', 'sort_order' => 6],
        ];
        foreach ($gallery as $g) {
            GalleryItem::updateOrCreate(
                ['title' => $g['title']],
                array_merge($g, ['is_active' => true])
            );
        }

        // 10. FAQs
        $faqs = [
            [
                'question' => 'How can I register as a volunteer in my local district?',
                'answer' => 'You can click on the "Become a Volunteer" button and complete the brief interest form. Our district volunteer coordinator will contact you within 2 business days for an introductory orientation.',
                'sort_order' => 1,
            ],
            [
                'question' => 'Are donations eligible for tax exemption under 80G?',
                'answer' => 'Yes, our registered trust is 80G compliant, and digital donation tax receipts are automatically generated and emailed to donors.',
                'sort_order' => 2,
            ],
            [
                'question' => 'Can college students join for summer social internships?',
                'answer' => 'Yes! We offer 4-to-8 week structured social impact internships for college students across field education, environment, and community health wings.',
                'sort_order' => 3,
            ],
            [
                'question' => 'How does the organization ensure transparent utilization of funds?',
                'answer' => 'We adhere to open governance: quarterly audited financial summaries, project-level expenditure reports, and annual impact reports are published openly for community review.',
                'sort_order' => 4,
            ],
        ];
        foreach ($faqs as $f) {
            Faq::updateOrCreate(
                ['question' => $f['question']],
                array_merge($f, ['is_active' => true])
            );
        }

        // 11. SEO Settings
        SeoSetting::updateOrCreate(
            ['page_key' => 'home'],
            [
                'meta_title' => 'Nanban Social Foundation | Serving Communities Across Tamil Nadu',
                'meta_description' => 'Grassroots NGO working with rural and peri-urban communities across Tamil Nadu for sustainable education, community healthcare, and environment conservation.',
                'keywords' => 'Tamil Nadu NGO, rural education, Coimbatore NGO, community development, volunteer Tamil Nadu',
                'og_title' => 'Nanban Social Foundation | Serving Communities Across Tamil Nadu',
                'og_description' => 'Grassroots NGO working for sustainable education, community healthcare, and environment conservation.',
                'og_image' => 'https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&w=1200&q=80',
                'canonical_url' => 'https://nanbanfoundation.org.in',
            ]
        );
    }
}
