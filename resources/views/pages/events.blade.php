<x-layouts.app :title="$title" :ngo="$ngo" :seo="$seo">

    @include('components.header')

    <main id="main-content">
        <!-- Page Hero -->
        <section class="relative bg-gradient-to-b from-[#DCFCE7]/40 via-white to-[#F8FAF8] pt-16 pb-20 border-b border-[#E5EAE6] overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="max-w-3xl space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold text-[#15803D] bg-white border border-green-200 shadow-2xs reveal" data-reveal>
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        <span>GRASSROOTS GATHERINGS & DRIVES</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#17201B] tracking-tight font-heading leading-tight reveal delay-100" data-reveal>
                        Upcoming & Past Community Events
                    </h1>
                    <p class="text-base sm:text-lg text-[#647067] leading-relaxed reveal delay-200" data-reveal>
                        Participate in our field medical camps, educational workshops, tree plantation drives, and village consultations across Tamil Nadu districts.
                    </p>
                </div>
            </div>
        </section>

        <!-- Events List -->
        <section class="py-20 bg-white border-b border-[#E5EAE6]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    @forelse ($allEvents as $event)
                        <div class="bg-[#F8FAF8] rounded-2xl overflow-hidden border border-slate-200/80 hover:bg-white hover:shadow-xl hover:border-green-200 transition-all duration-300 flex flex-col sm:flex-row group reveal" data-reveal>
                            
                            <!-- Date & Thumbnail Column -->
                            <div class="sm:w-48 relative overflow-hidden shrink-0 min-h-[160px]">
                                <img src="{{ $event->resolved_image_url ?: 'https://images.unsplash.com/photo-1544717305-2782549b5136?auto=format&fit=crop&w=600&q=80' }}" 
                                     alt="{{ $event->title }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                
                                <div class="absolute top-3 left-3 bg-[#15803D] text-white p-2.5 rounded-xl text-center shadow-md min-w-[56px]">
                                    <span class="text-lg font-black block leading-none font-heading">{{ $event->day }}</span>
                                    <span class="text-[10px] font-bold uppercase tracking-wider block mt-0.5">{{ $event->month }}</span>
                                </div>
                            </div>

                            <!-- Content Column -->
                            <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-green-100 text-[#15803D]">
                                            {{ $event->category ?: 'Community Drive' }}
                                        </span>
                                    </div>
                                    <h3 class="text-base sm:text-lg font-bold text-slate-900 font-heading group-hover:text-[#15803D] transition-colors leading-snug">
                                        {{ $event->title }}
                                    </h3>
                                    <div class="space-y-1 text-xs text-slate-500">
                                        <p class="flex items-center gap-1.5">
                                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i>
                                            <span>{{ $event->location ?: 'Tamil Nadu' }}</span>
                                        </p>
                                        <p class="flex items-center gap-1.5">
                                            <i data-lucide="clock" class="w-3.5 h-3.5 text-slate-400"></i>
                                            <span>{{ $event->time_info ?: '9:00 AM - 4:00 PM' }}</span>
                                        </p>
                                    </div>
                                    <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed pt-1">
                                        {{ $event->description }}
                                    </p>
                                </div>

                                <div class="pt-3 border-t border-slate-200/60 flex items-center justify-between">
                                    <a href="{{ route('events.detail', $event->slug ?: $event->id) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#15803D] hover:text-[#166534] transition-colors">
                                        <span>Event Details</span>
                                        <i data-lucide="arrow-right" class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform"></i>
                                    </a>
                                    <button type="button" @click="$store.ngoApp.openVolunteer('volunteer')" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">
                                        Join as Volunteer
                                    </button>
                                </div>
                            </div>

                        </div>
                    @empty
                        <div class="col-span-2 py-12 text-center text-slate-500">
                            <p>Events are being loaded from the database.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </main>

    @include('components.footer')

</x-layouts.app>
