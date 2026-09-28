<?php

namespace App\Services;

class HomepageData
{
    /**
     * UI Prototype Demo Data for Tamil Nadu NGO Homepage
     * Note: All values are placeholders for UI review & layout confirmation.
     */
    public static function get(): array
    {
        return [
            'ngo' => [
                'name' => 'NANBAN SOCIAL FOUNDATION',
                'short_name' => 'Nanban NGO',
                'tagline' => 'Serving Communities Across Tamil Nadu',
                'state' => 'Tamil Nadu, India',
                'phone' => '+91 94420 12345',
                'email' => 'contact@nanbanfoundation.org.in',
                'address' => '42, Gandhji Road, RS Puram, Coimbatore, Tamil Nadu - 641002',
                'operating_hours' => 'Mon - Sat: 9:00 AM - 6:00 PM',
            ],

            'hero' => [
                'badge' => 'SERVING COMMUNITIES ACROSS TAMIL NADU',
                'heading_line1' => 'Together, We Can Build',
                'heading_line2' => 'Stronger Communities.',
                'subheading' => 'Working hand-in-hand with grassroots communities, dedicated volunteers, and local leaders to foster sustainable social impact, inclusive education, and lasting dignity across Tamil Nadu.',
                'primary_cta' => 'Support Our Mission',
                'secondary_cta' => 'Join Our Community',
                'image' => 'https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&w=1200&q=80',
                'image_alt' => 'Tamil Nadu community volunteers and children engaged in an interactive learning workshop',
                'stats_pill' => [
                    'count' => '10,000+',
                    'label' => 'Lives Reached in 2026',
                ],
                'badge_trust' => '100% Volunteer Driven & Transparent',
            ],

            'impact_stats' => [
                [
                    'value' => '10+',
                    'label' => 'Years of Service',
                    'description' => 'Dedicated grassroots presence across districts',
                    'icon' => 'calendar',
                ],
                [
                    'value' => '25+',
                    'label' => 'Communities Served',
                    'description' => 'Active rural & peri-urban focal centers',
                    'icon' => 'map-pin',
                ],
                [
                    'value' => '500+',
                    'label' => 'Active Volunteers',
                    'description' => 'Students, professionals & elders uniting',
                    'icon' => 'users',
                ],
                [
                    'value' => '10,000+',
                    'label' => 'Lives Reached',
                    'description' => 'Students, families & self-help groups empowered',
                    'icon' => 'heart-handshake',
                ],
            ],

            'about' => [
                'badge' => 'WHO WE ARE',
                'heading' => 'Creating Change Together With Communities',
                'p1' => 'We work alongside grassroots communities across Tamil Nadu to identify critical local challenges and co-create practical, sustainable solutions that enrich everyday lives.',
                'p2' => 'Our community-first approach is anchored in dignity, active citizen participation, transparent governance, and long-term socio-economic empowerment. Rather than imposing external solutions, we nurture local leadership.',
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
                'image' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1000&q=80',
                'image_alt' => 'Classroom education and village student mentorship program in Tamil Nadu',
            ],

            'programs' => [
                [
                    'id' => 'education',
                    'title' => 'Education & Skill Development',
                    'description' => 'After-school learning centers, science labs, scholarship guidance, and vocational digital skills for first-generation learners.',
                    'icon' => 'graduation-cap',
                    'impact_tag' => '3,200+ Students Supported',
                    'color' => 'green',
                ],
                [
                    'id' => 'community',
                    'title' => 'Community Development',
                    'description' => 'Strengthening village water security, local library revival, rural community centers, and neighbourhood solidarity initiatives.',
                    'icon' => 'building-2',
                    'impact_tag' => '25+ Village Clusters',
                    'color' => 'teal',
                ],
                [
                    'id' => 'healthcare',
                    'title' => 'Preventive Healthcare',
                    'description' => 'Monthly village medical checkups, maternal nutrition awareness, eye care camps, and elderly wellness screenings.',
                    'icon' => 'heart-pulse',
                    'impact_tag' => '45+ Health Camps Held',
                    'color' => 'emerald',
                ],
                [
                    'id' => 'environment',
                    'title' => 'Environment & Afforestation',
                    'description' => 'Miyawaki urban forests, lake rejuvenation, indigenous seed banks, and community plastic-reduction drives.',
                    'icon' => 'leaf',
                    'impact_tag' => '20,000+ Native Trees Planted',
                    'color' => 'green',
                ],
                [
                    'id' => 'women-youth',
                    'title' => 'Women & Youth Empowerment',
                    'description' => 'Livelihood micro-enterprises for self-help groups (SHGs), tailorship training, and youth leadership fellowships.',
                    'icon' => 'sparkles',
                    'impact_tag' => '850+ Women Artisans',
                    'color' => 'amber',
                ],
                [
                    'id' => 'social-support',
                    'title' => 'Social Welfare & Senior Care',
                    'description' => 'Connecting marginalized families with government welfare schemes, emergency aid, and companionship for senior citizens.',
                    'icon' => 'hand-heart',
                    'impact_tag' => '1,400+ Families Assisted',
                    'color' => 'teal',
                ],
            ],

            'communities' => [
                [
                    'name' => 'Coimbatore District',
                    'area' => 'Pollachi & Anaikatti Hills',
                    'description' => 'Focusing on tribal education centers, afforestation drives, and eco-friendly farming practices for indigenous communities.',
                    'active_volunteers' => '140+ Volunteers',
                    'active_programs' => '6 Programs Active',
                    'image' => 'https://images.unsplash.com/photo-1596461404969-9ae70f2830c1?auto=format&fit=crop&w=800&q=80',
                ],
                [
                    'name' => 'Chennai District',
                    'area' => 'Ennore & North Chennai Clusters',
                    'description' => 'Youth computer literacy centers, coastal clean-up task forces, and after-school tutoring for urban settlement students.',
                    'active_volunteers' => '180+ Volunteers',
                    'active_programs' => '8 Programs Active',
                    'image' => 'https://images.unsplash.com/photo-1582213782179-e0d53f98f2ca?auto=format&fit=crop&w=800&q=80',
                ],
                [
                    'name' => 'Erode District',
                    'area' => 'Bhavani & Sathyamangalam Belt',
                    'description' => 'Weavers’ self-help group digital marketing training, child health camps, and traditional lake restoration efforts.',
                    'active_volunteers' => '95+ Volunteers',
                    'active_programs' => '4 Programs Active',
                    'image' => 'https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?auto=format&fit=crop&w=800&q=80',
                ],
                [
                    'name' => 'Tiruppur District',
                    'area' => 'Avinashi & Dharapuram Clusters',
                    'description' => 'Apparel worker family support, night study centers for children, and groundwater recharge well conservation.',
                    'active_volunteers' => '110+ Volunteers',
                    'active_programs' => '5 Programs Active',
                    'image' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=800&q=80',
                ],
            ],

            'campaign' => [
                'badge' => 'FEATURED CAMPAIGN',
                'title' => 'Rural Student Digital Learning & STEM Labs',
                'subtitle' => 'Equipping 12 government and community schools across rural Tamil Nadu with modern solar-powered computer labs and regional STEM kits.',
                'raised' => 345000,
                'goal' => 500000,
                'supporters' => 284,
                'days_left' => 18,
                'progress_percentage' => 69,
                'image' => 'https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?auto=format&fit=crop&w=1000&q=80',
                'image_alt' => 'Rural students learning on digital tablets in a Tamil Nadu smart classroom',
                'impact_bullets' => [
                    'Provides 120 refurbished laptops and solar back-up units',
                    'Trains 18 local youth as community digital tutors',
                    'Benefits 2,400+ first-generation school students',
                    '100% transparent expense audit published quarterly',
                ],
                'suggested_amounts' => [500, 1000, 2500, 5000, 10000],
            ],

            'events' => [
                [
                    'day' => '18',
                    'month' => 'OCT',
                    'year' => '2026',
                    'category' => 'Healthcare',
                    'title' => 'Comprehensive Community Health & Eye Screening Camp',
                    'location' => 'Community Hall, Perur, Coimbatore',
                    'time' => '8:30 AM - 2:00 PM',
                    'description' => 'Free general health checkup, pediatric screening, blood pressure diagnostics, and distribution of subsidized reading glasses.',
                    'image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=600&q=80',
                ],
                [
                    'day' => '25',
                    'month' => 'OCT',
                    'year' => '2026',
                    'category' => 'Youth Workshop',
                    'title' => 'Youth Leadership & Digital Skills Bootcamp',
                    'location' => 'Town Hall Auditorium, Erode',
                    'time' => '10:00 AM - 4:30 PM',
                    'description' => 'Hands-on training in resume creation, practical spoken English, basics of AI tools, and personality development for college youth.',
                    'image' => 'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=600&q=80',
                ],
                [
                    'day' => '08',
                    'month' => 'NOV',
                    'year' => '2026',
                    'category' => 'Environment',
                    'title' => 'Noyyal Basin Native Tree Plantation & Seed Ball Drive',
                    'location' => 'River Basin Zone, Tiruppur',
                    'time' => '6:30 AM - 11:00 AM',
                    'description' => 'Planting 1,500 native saplings including Neem, Pungai, and Marutham along the river catchment belt with 100+ volunteers.',
                    'image' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=600&q=80',
                ],
            ],

            'leadership' => [
                [
                    'name' => 'Dr. K. Senthilvelan, Ph.D.',
                    'role' => 'Founder & Managing Trustee',
                    'bio' => 'Former education researcher with 22+ years of grassroots rural development and public policy experience in Western Tamil Nadu.',
                    'image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=500&q=80',
                ],
                [
                    'name' => 'Mrs. Radha Sundaram, M.S.W.',
                    'role' => 'Director - Women & Community Welfare',
                    'bio' => 'Dedicated social worker championing self-help group micro-financing, maternal health awareness, and gender dignity initiatives.',
                    'image' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=500&q=80',
                ],
                [
                    'name' => 'Mr. P. Vijay Anand, B.E.',
                    'role' => 'Lead - Youth & Volunteer Engagement',
                    'bio' => 'Passionate community organizer coordinating over 500+ active student and professional volunteers across 4 district clusters.',
                    'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=500&q=80',
                ],
                [
                    'name' => 'Dr. Meenakshi Ramanathan',
                    'role' => 'Advisory Head - Rural Health Programs',
                    'bio' => 'Senior medical practitioner coordinating mobile clinics, pediatric nutrition screenings, and rural preventive health camps.',
                    'image' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=500&q=80',
                ],
            ],

            'stories' => [
                [
                    'title' => 'From School Dropout Risk to College Engineering Merit',
                    'author_info' => 'Kavitha S., 19 — Coimbatore Community Center',
                    'excerpt' => 'When Kavitha’s family faced severe agricultural hardship, she was on the verge of stopping school. Through Nanban’s evening learning center, mentorship, and tuition support, she excelled in her higher secondary exams and earned a merit engineering seat.',
                    'quote' => 'The teachers and mentors believed in me when I felt completely hopeless. Today, I am the first person in my village pursuing a professional degree.',
                    'category' => 'Education',
                    'image' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?auto=format&fit=crop&w=800&q=80',
                ],
                [
                    'title' => 'How 40 Rural Women Built a Sustainable Coir Enterprise',
                    'author_info' => 'Anuradha & Shanti — Bhavani Women SHG, Erode',
                    'excerpt' => 'With hands-on skills training in value-added eco-friendly coir handicraft production, packaging, and digital marketplaces, our women’s collective now generates dependable monthly household income for 40 families.',
                    'quote' => 'We went from daily wage uncertainty to running our own registered artisan cluster with dignity and financial security.',
                    'category' => 'Livelihood',
                    'image' => 'https://images.unsplash.com/photo-1589156280159-27698a70f29e?auto=format&fit=crop&w=800&q=80',
                ],
                [
                    'title' => 'Reviving a 15-Acre Village Lake in Tiruppur Catchment',
                    'author_info' => 'Muthusamy & Youth Eco-Volunteers, Dharapuram',
                    'excerpt' => 'Mobilizing 150 local farmers, college students, and village elders, the dry, silted lake basin was desilted, strengthened with stone bunds, and encircled with 1,200 native palm and neem trees before the monsoon.',
                    'quote' => 'Within one monsoon season, groundwater levels in our borewells surged by 45 feet across three neighbouring hamlets.',
                    'category' => 'Environment',
                    'image' => 'https://images.unsplash.com/photo-1464226184884-fa280b87c399?auto=format&fit=crop&w=800&q=80',
                ],
            ],

            'gallery' => [
                [
                    'title' => 'Classroom STEM Workshop',
                    'category' => 'Education',
                    'image' => 'https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&w=800&q=80',
                    'location' => 'Pollachi',
                ],
                [
                    'title' => 'Village Free Health Camp',
                    'category' => 'Healthcare',
                    'image' => 'https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?auto=format&fit=crop&w=800&q=80',
                    'location' => 'Coimbatore',
                ],
                [
                    'title' => 'Native Tree Plantation Drive',
                    'category' => 'Environment',
                    'image' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=800&q=80',
                    'location' => 'Tiruppur',
                ],
                [
                    'title' => 'Women SHG Skill Workshop',
                    'category' => 'Livelihood',
                    'image' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=800&q=80',
                    'location' => 'Erode',
                ],
                [
                    'title' => 'Youth Volunteer Gathering',
                    'category' => 'Community',
                    'image' => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=800&q=80',
                    'location' => 'Chennai',
                ],
                [
                    'title' => 'Child Nutrition & Milk Distribution',
                    'category' => 'Welfare',
                    'image' => 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&w=800&q=80',
                    'location' => 'Anaikatti',
                ],
            ],

            'faqs' => [
                [
                    'q' => 'How can I register as a volunteer in my local district?',
                    'a' => 'You can click on the "Become a Volunteer" button and complete the brief interest form. Our district volunteer coordinator will contact you within 2 business days for an introductory orientation.',
                ],
                [
                    'q' => 'Are donations eligible for tax exemption under 80G?',
                    'a' => 'Yes, our registered trust is 80G compliant, and digital donation tax receipts are automatically generated and emailed to donors.',
                ],
                [
                    'q' => 'Can college students join for summer social internships?',
                    'a' => 'Yes! We offer 4-to-8 week structured social impact internships for college students across field education, environment, and community health wings.',
                ],
                [
                    'q' => 'How does the organization ensure transparent utilization of funds?',
                    'a' => 'We adhere to open governance: quarterly audited financial summaries, project-level expenditure reports, and annual impact reports are published openly for community review.',
                ],
            ],
        ];
    }
}
