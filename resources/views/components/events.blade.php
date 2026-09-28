<section id="events" class="py-16 md:py-24 bg-[#F8FAF8] border-b border-[#E5EAE6]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Heading -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#DCFCE7] text-[#15803D] text-xs font-bold uppercase tracking-wider">
                    COMMUNITY INITIATIVES
                </span>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#17201B] font-heading tracking-tight mt-3">
                    Upcoming Events & Drives
                </h2>
                <p class="text-base text-[#647067] mt-2 max-w-2xl">
                    Join our on-ground volunteering camps, workshops, and environmental campaigns across Tamil Nadu.
                </p>
            </div>
            <div>
                <button 
                    type="button" 
                    @click="$store.ngoApp.openVolunteer('volunteer')"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold text-[#15803D] bg-white border border-green-200 rounded-xl hover:bg-green-50 shadow-xs transition-colors cursor-pointer"
                >
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                    <span>Host Event in Your Area</span>
                </button>
            </div>
        </div>

        <!-- Event Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
            @foreach($events as $event)
                <div class="bg-white rounded-2xl overflow-hidden border border-[#E5EAE6] hover:border-green-300 hover:shadow-lg transition-all duration-300 flex flex-col justify-between group">
                    
                    <div>
                        <!-- Event Image with Date Badge -->
                        <div class="relative h-48 overflow-hidden bg-slate-100">
                            <img 
                                src="{{ $event['image'] }}" 
                                alt="{{ $event['title'] }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                loading="lazy"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>

                            <!-- Floating Date Badge -->
                            <div class="absolute top-3 left-3 bg-white/95 backdrop-blur-md rounded-xl p-2 text-center shadow-md min-w-[54px] border border-slate-100">
                                <span class="block text-lg font-extrabold text-[#15803D] font-heading leading-tight">{{ $event['day'] }}</span>
                                <span class="block text-[10px] font-bold text-[#647067] uppercase">{{ $event['month'] }}</span>
                            </div>

                            <!-- Category Badge -->
                            <div class="absolute top-3 right-3 bg-[#17201B]/80 backdrop-blur-md text-white px-2.5 py-1 rounded-md text-[11px] font-semibold">
                                {{ $event['category'] }}
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 space-y-3">
                            <div class="space-y-1 text-xs text-[#647067]">
                                <div class="flex items-center gap-1.5 text-slate-700 font-medium">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-[#15803D] shrink-0"></i>
                                    <span class="truncate">{{ $event['location'] }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 text-slate-500">
                                    <i data-lucide="clock" class="w-3.5 h-3.5 text-slate-400 shrink-0"></i>
                                    <span>{{ $event['time'] }}</span>
                                </div>
                            </div>

                            <h3 class="text-base font-bold text-[#17201B] font-heading leading-snug group-hover:text-[#15803D] transition-colors">
                                {{ $event['title'] }}
                            </h3>

                            <p class="text-xs text-[#647067] leading-relaxed line-clamp-3">
                                {{ $event['description'] }}
                            </p>
                        </div>
                    </div>

                    <!-- Card Action -->
                    <div class="px-6 pb-6 pt-2">
                        <button 
                            type="button" 
                            @click="$store.ngoApp.openVolunteer('event')"
                            class="w-full py-2.5 px-4 text-center text-xs font-bold text-[#15803D] bg-green-50 hover:bg-[#15803D] hover:text-white border border-green-200 rounded-xl transition-colors cursor-pointer inline-flex items-center justify-center gap-1.5"
                        >
                            <i data-lucide="check" class="w-3.5 h-3.5"></i>
                            <span>Volunteer For This Event</span>
                        </button>
                    </div>

                </div>
            @endforeach
        </div>

    </div>
</section>
