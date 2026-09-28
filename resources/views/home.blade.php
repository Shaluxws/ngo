<x-layouts.app :title="$seo['meta_title'] ?? ($ngo['name'] . ' | ' . $ngo['tagline'])" :ngo="$ngo" :seo="$seo">
    
    <!-- 1. HEADER -->
    @include('components.header')

    <main id="main-content">
        <!-- 2. HERO -->
        @if ($sections->has('hero') ? $sections->get('hero')->is_enabled : true)
            @include('components.hero')
        @endif

        <!-- 3. IMPACT STATISTICS -->
        @if ($sections->has('impact_stats') ? $sections->get('impact_stats')->is_enabled : true)
            @include('components.impact-stats')
        @endif

        <!-- 4. ABOUT NGO -->
        @if ($sections->has('about') ? $sections->get('about')->is_enabled : true)
            @include('components.about')
        @endif

        <!-- 5. WHAT WE DO / PROGRAMS -->
        @if ($sections->has('programs') ? $sections->get('programs')->is_enabled : true)
            @include('components.programs')
        @endif

        <!-- 6. COMMUNITIES -->
        @if ($sections->has('communities') ? $sections->get('communities')->is_enabled : true)
            @include('components.communities')
        @endif

        <!-- 7. FEATURED CAMPAIGN / DONATE -->
        @if ($sections->has('campaign') ? $sections->get('campaign')->is_enabled : true)
            @include('components.campaign')
        @endif

        <!-- 8. UPCOMING EVENTS -->
        @if ($sections->has('events') ? $sections->get('events')->is_enabled : true)
            @include('components.events')
        @endif

        <!-- 9. LEADERSHIP -->
        @if ($sections->has('leadership') ? $sections->get('leadership')->is_enabled : true)
            @include('components.leadership')
        @endif

        <!-- 10. SUCCESS STORIES -->
        @if ($sections->has('stories') ? $sections->get('stories')->is_enabled : true)
            @include('components.stories')
        @endif

        <!-- 11. GALLERY -->
        @if ($sections->has('gallery') ? $sections->get('gallery')->is_enabled : true)
            @include('components.gallery')
        @endif

        <!-- 12. JOIN / VOLUNTEER CTA -->
        @if ($sections->has('join_cta') ? $sections->get('join_cta')->is_enabled : true)
            @include('components.join-cta')
        @endif

        <!-- 13. CONTACT -->
        @if ($sections->has('contact') ? $sections->get('contact')->is_enabled : true)
            @include('components.contact')
        @endif
    </main>

    <!-- 14. FOOTER -->
    @include('components.footer')

</x-layouts.app>
