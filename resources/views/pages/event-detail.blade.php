<x-layouts.app :title="$title" :ngo="$ngo" :seo="$seo">

    @include('components.header')

    <main id="main-content">
        <!-- Breadcrumb & Header -->
        <section class="bg-gradient-to-b from-[#DCFCE7]/40 via-white to-[#F8FAF8] pt-12 pb-14 border-b border-[#E5EAE6]">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Breadcrumbs -->
                <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6 font-medium">
                    <a href="{{ route('home') }}" class="hover:text-[#15803D]">Home</a>
                    <span>/</span>
                    <a href="{{ route('events') }}" class="hover:text-[#15803D]">Events</a>
                    <span>/</span>
                    <span class="text-slate-900 font-bold truncate">{{ $event->title }}</span>
                </nav>

                <div class="space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold text-[#15803D] bg-white border border-green-200 shadow-2xs">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        <span>{{ $event->category ?: 'Community Gathering' }}</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#17201B] tracking-tight font-heading leading-tight">
                        {{ $event->title }}
                    </h1>
                    
                    <div class="flex flex-wrap items-center gap-4 text-xs sm:text-sm text-slate-600 pt-2 font-medium">
                        <div class="flex items-center gap-1.5 bg-white px-3 py-1.5 rounded-lg border border-slate-200">
                            <i data-lucide="calendar-days" class="w-4 h-4 text-[#15803D]"></i>
                            <span>{{ $event->day }} {{ $event->month }} {{ $event->year }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 bg-white px-3 py-1.5 rounded-lg border border-slate-200">
                            <i data-lucide="clock" class="w-4 h-4 text-[#15803D]"></i>
                            <span>{{ $event->time_info ?: '9:00 AM - 4:00 PM' }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 bg-white px-3 py-1.5 rounded-lg border border-slate-200">
                            <i data-lucide="map-pin" class="w-4 h-4 text-[#15803D]"></i>
                            <span>{{ $event->location ?: 'Tamil Nadu' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Event Detail Body -->
        <section class="py-16 bg-white border-b border-[#E5EAE6]">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
                <div class="rounded-2xl overflow-hidden shadow-xl border border-slate-200">
                    <img src="{{ $event->resolved_image_url ?: 'https://images.unsplash.com/photo-1544717305-2782549b5136?auto=format&fit=crop&w=1200&q=80' }}" alt="{{ $event->title }}" class="w-full h-[400px] object-cover">
                </div>

                <div class="space-y-4">
                    <h2 class="text-2xl font-bold text-slate-900 font-heading">Event Overview & What to Expect</h2>
                    <p class="text-sm sm:text-base text-slate-700 leading-relaxed">
                        {{ $event->description }}
                    </p>
                    <p class="text-sm sm:text-base text-slate-700 leading-relaxed">
                        Volunteers and community members participating in this drive are requested to arrive 15 minutes prior to the scheduled start time. All necessary materials, orientation kits, and community refreshments will be arranged on-site by our field coordinators.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-[#F8FAF8] border border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Want to participate or volunteer in this event?</h3>
                        <p class="text-xs text-slate-500">Free registration for all volunteers and community patrons.</p>
                    </div>
                    <button type="button" @click="$store.ngoApp.openVolunteer('volunteer')" class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-[#15803D] hover:bg-[#166534] shadow-sm transition-all whitespace-nowrap">
                        Register as Volunteer
                    </button>
                </div>
            </div>
        </section>

        <!-- Related Events -->
        @if (count($relatedEvents) > 0)
            <section class="py-16 bg-[#F8FAF8] border-b border-[#E5EAE6]">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-slate-900 font-heading">Other Upcoming Gatherings</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach ($relatedEvents as $rel)
                            <a href="{{ route('events.detail', $rel->slug ?: $rel->id) }}" class="bg-white p-5 rounded-2xl border border-slate-200/80 hover:shadow-md transition-all space-y-2 block">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-[#15803D]">{{ $rel->day }} {{ $rel->month }} {{ $rel->year }}</span>
                                <h3 class="text-sm font-bold text-slate-900">{{ $rel->title }}</h3>
                                <p class="text-xs text-slate-500 line-clamp-2">{{ $rel->description }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </main>

    @include('components.footer')

</x-layouts.app>
