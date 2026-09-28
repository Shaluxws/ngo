<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Campaign;
use App\Models\Community;
use App\Models\District;
use App\Models\Event;
use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\ImpactMetric;
use App\Models\Leader;
use App\Models\SeoSetting;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use App\Models\Story;
use App\Models\VolunteerProfile;
use App\Services\AuditService;
use App\Services\HomepageDataService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicPageController extends Controller
{
    /**
     * Get shared global organization and layout data.
     */
    protected function getSharedData(): array
    {
        return HomepageDataService::get();
    }

    /**
     * About Us Page: Mission, Vision, Values, Leadership, Communities, Journey.
     */
    public function about(): View
    {
        $data = $this->getSharedData();
        $data['title'] = 'About Us | ' . ($data['ngo']['name'] ?? 'Nanban Social Foundation');
        $data['seo'] = SeoSetting::forPage('about', [
            'meta_title' => 'About Us | ' . ($data['ngo']['name'] ?? 'Nanban Social Foundation'),
            'meta_description' => 'Learn about Nanban Social Foundation, our grassroots mission, vision, core values, leadership team, and communities served across Tamil Nadu.',
        ]);

        return view('pages.about', $data);
    }

    /**
     * Programs Page: All Programs, Categories, Highlights, Volunteer CTA.
     */
    public function programs(Request $request): View
    {
        $data = $this->getSharedData();
        $data['title'] = 'Our Programs & Initiatives | ' . ($data['ngo']['name'] ?? 'Nanban Social Foundation');
        $data['seo'] = SeoSetting::forPage('programs', [
            'meta_title' => 'Our Programs & Initiatives | ' . ($data['ngo']['name'] ?? 'Nanban Social Foundation'),
            'meta_description' => 'Explore our education, healthcare, rural development, women empowerment, and environmental sustainability initiatives across Tamil Nadu.',
        ]);

        $data['allPrograms'] = Activity::with('media', 'category')->active()->ordered()->get();

        return view('pages.programs', $data);
    }

    /**
     * Program Detail Page.
     */
    public function programDetail(string $slug): View
    {
        $program = Activity::with('media', 'category')
            ->where('slug', $slug)
            ->firstOrFail();

        $data = $this->getSharedData();
        $data['program'] = $program;
        $data['relatedPrograms'] = Activity::with('media')
            ->active()
            ->where('id', '!=', $program->id)
            ->ordered()
            ->take(3)
            ->get();

        $data['title'] = $program->title . ' | ' . ($data['ngo']['name'] ?? 'Nanban Social Foundation');
        $data['seo'] = [
            'meta_title' => $program->title . ' | ' . ($data['ngo']['name'] ?? 'Nanban Social Foundation'),
            'meta_description' => $program->description,
            'og_image' => $program->resolved_image_url,
        ];

        return view('pages.program-detail', $data);
    }

    /**
     * Impact Page: Statistics, District Reach, Community Outcomes, Volunteer Contribution.
     */
    public function impact(): View
    {
        $data = $this->getSharedData();
        $data['title'] = 'Our Impact & Reach | ' . ($data['ngo']['name'] ?? 'Nanban Social Foundation');
        $data['seo'] = SeoSetting::forPage('impact', [
            'meta_title' => 'Our Impact & Reach | ' . ($data['ngo']['name'] ?? 'Nanban Social Foundation'),
            'meta_description' => 'Transparent social impact statistics, district footprint, grassroots community outcomes, and volunteer contributions across Tamil Nadu.',
        ]);

        $data['districts'] = District::with('areas.communities')->where('is_active', true)->get();
        $data['totalVolunteersCount'] = VolunteerProfile::where('volunteer_status', 'active')->count() ?: 500;
        $data['totalHoursCount'] = (float)\App\Models\VolunteerParticipation::sum('hours') ?: 4500;

        return view('pages.impact', $data);
    }

    /**
     * Stories Page: Success Stories, Community Testimonials.
     */
    public function stories(Request $request): View
    {
        $data = $this->getSharedData();
        $data['title'] = 'Stories of Change | ' . ($data['ngo']['name'] ?? 'Nanban Social Foundation');
        $data['seo'] = SeoSetting::forPage('stories', [
            'meta_title' => 'Stories of Change | ' . ($data['ngo']['name'] ?? 'Nanban Social Foundation'),
            'meta_description' => 'Inspiring grassroots stories of transformation, student achievements, women empowerment, and community solidarity in Tamil Nadu.',
        ]);

        $data['allStories'] = Story::with('media')->active()->ordered()->get();

        return view('pages.stories', $data);
    }

    /**
     * Story Detail Page.
     */
    public function storyDetail(string $slug): View
    {
        $story = Story::with('media')
            ->where(function ($q) use ($slug) {
                $q->where('id', $slug)->orWhere('title', 'like', str_replace('-', ' ', $slug));
            })
            ->first();

        if (!$story) {
            // Fallback: match by closest slug or first story
            $story = Story::with('media')->active()->firstOrFail();
        }

        $data = $this->getSharedData();
        $data['story'] = $story;
        $data['relatedStories'] = Story::with('media')
            ->active()
            ->where('id', '!=', $story->id)
            ->take(3)
            ->get();

        $data['title'] = $story->title . ' | ' . ($data['ngo']['name'] ?? 'Nanban Social Foundation');
        $data['seo'] = [
            'meta_title' => $story->title . ' | ' . ($data['ngo']['name'] ?? 'Nanban Social Foundation'),
            'meta_description' => $story->excerpt ?: $story->quote,
            'og_image' => $story->resolved_image_url,
        ];

        return view('pages.story-detail', $data);
    }

    /**
     * Events Page: Upcoming & Past Events.
     */
    public function events(): View
    {
        $data = $this->getSharedData();
        $data['title'] = 'Events & Community Drives | ' . ($data['ngo']['name'] ?? 'Nanban Social Foundation');
        $data['seo'] = SeoSetting::forPage('events', [
            'meta_title' => 'Events & Community Drives | ' . ($data['ngo']['name'] ?? 'Nanban Social Foundation'),
            'meta_description' => 'Join our upcoming community drives, health camps, tree plantation initiatives, and interactive workshops across Tamil Nadu.',
        ]);

        $data['allEvents'] = Event::with('media')->active()->ordered()->get();

        return view('pages.events', $data);
    }

    /**
     * Event Detail Page.
     */
    public function eventDetail(string $slug): View
    {
        $event = Event::with('media')
            ->where('slug', $slug)
            ->orWhere('id', $slug)
            ->firstOrFail();

        $data = $this->getSharedData();
        $data['event'] = $event;
        $data['relatedEvents'] = Event::with('media')
            ->active()
            ->where('id', '!=', $event->id)
            ->take(3)
            ->get();

        $data['title'] = $event->title . ' | ' . ($data['ngo']['name'] ?? 'Nanban Social Foundation');
        $data['seo'] = [
            'meta_title' => $event->title . ' | ' . ($data['ngo']['name'] ?? 'Nanban Social Foundation'),
            'meta_description' => $event->description,
            'og_image' => $event->resolved_image_url,
        ];

        return view('pages.event-detail', $data);
    }

    /**
     * Campaign Detail Page.
     */
    public function campaignDetail(string $slug): View
    {
        $campaign = Campaign::with('media')
            ->where('slug', $slug)
            ->orWhere('id', $slug)
            ->first();

        if (!$campaign) {
            $campaign = Campaign::with('media')->active()->firstOrFail();
        }

        $data = $this->getSharedData();
        $data['campaignItem'] = $campaign;
        $data['title'] = $campaign->title . ' | ' . ($data['ngo']['name'] ?? 'Nanban Social Foundation');
        $data['seo'] = [
            'meta_title' => $campaign->title . ' | ' . ($data['ngo']['name'] ?? 'Nanban Social Foundation'),
            'meta_description' => $campaign->subtitle,
            'og_image' => $campaign->resolved_image_url,
        ];

        return view('pages.campaign-detail', $data);
    }

    /**
     * Gallery Showcase Page.
     */
    public function gallery(): View
    {
        $data = $this->getSharedData();
        $data['title'] = 'Photo Gallery | ' . ($data['ngo']['name'] ?? 'Nanban Social Foundation');
        $data['seo'] = SeoSetting::forPage('gallery', [
            'meta_title' => 'Photo Gallery | ' . ($data['ngo']['name'] ?? 'Nanban Social Foundation'),
            'meta_description' => 'Visual moments of grassroots community action, educational workshops, healthcare initiatives, and volunteer service across Tamil Nadu.',
        ]);

        $data['allGallery'] = GalleryItem::with('media')->active()->ordered()->get();

        return view('pages.gallery', $data);
    }

    /**
     * Contact Us Page.
     */
    public function contact(): View
    {
        $data = $this->getSharedData();
        $data['title'] = 'Contact Us | ' . ($data['ngo']['name'] ?? 'Nanban Social Foundation');
        $data['seo'] = SeoSetting::forPage('contact', [
            'meta_title' => 'Contact Us | ' . ($data['ngo']['name'] ?? 'Nanban Social Foundation'),
            'meta_description' => 'Get in touch with Nanban Social Foundation. Reach out for partnerships, volunteering, community support, or general inquiries in Tamil Nadu.',
        ]);

        return view('pages.contact', $data);
    }

    /**
     * Contact Form Submission Handler.
     */
    public function submitContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:150',
            'message' => 'required|string|max:2000',
        ]);

        AuditService::log(
            "Public contact message received from {$validated['name']} ({$validated['email']}): {$validated['subject']}",
            'contact_messages'
        );

        return back()->with('contact_success', 'Thank you for contacting Nanban Social Foundation! Our community coordinator will respond within 24 hours.');
    }

    /**
     * Volunteer Landing Page.
     */
    public function volunteer(): View
    {
        $data = $this->getSharedData();
        $data['title'] = 'Become a Volunteer | ' . ($data['ngo']['name'] ?? 'Nanban Social Foundation');
        $data['seo'] = SeoSetting::forPage('volunteer', [
            'meta_title' => 'Become a Volunteer | ' . ($data['ngo']['name'] ?? 'Nanban Social Foundation'),
            'meta_description' => 'Join our volunteer brigade across Tamil Nadu. Dedicate your skills and time to education, healthcare, and rural empowerment.',
        ]);

        return view('pages.volunteer', $data);
    }
}
