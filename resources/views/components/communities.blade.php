<section id="communities" class="py-16 md:py-24 bg-[#F8FAF8] border-b border-[#E5EAE6]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Heading -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#DCFCE7] text-[#15803D] text-xs font-bold uppercase tracking-wider">
                    DISTRICT CHAPTERS
                </span>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#17201B] font-heading tracking-tight mt-3">
                    Our Communities Across Tamil Nadu
                </h2>
                <p class="text-base text-[#647067] mt-2 max-w-2xl">
                    Grassroots social transformation starts locally. Our chapters mobilize neighbourhood volunteers, youth, and local leaders.
                </p>
            </div>
            <div>
                <button 
                    type="button" 
                    @click="$store.ngoApp.openVolunteer('volunteer')"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-[#15803D] bg-white border border-green-200 rounded-xl hover:bg-green-50 shadow-xs transition-colors cursor-pointer"
                >
                    <i data-lucide="plus-circle" class="w-4 h-4"></i>
                    <span>Start a Chapter in Your Town</span>
                </button>
            </div>
        </div>

        <!-- Community Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($communities as $community)
                <div class="bg-white rounded-2xl overflow-hidden border border-[#E5EAE6] hover:border-green-300 hover:shadow-lg transition-all duration-300 flex flex-col group">
                    
                    <!-- Community Image with Location Badge -->
                    <div class="relative h-44 overflow-hidden bg-slate-100">
                        <img 
                            src="{{ $community['image'] }}" 
                            alt="{{ $community['name'] }}" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            loading="lazy"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-3 left-3 right-3 text-white">
                            <div class="flex items-center gap-1 text-xs font-semibold text-amber-300">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                                <span>{{ $community['area'] }}</span>
                            </div>
                            <h3 class="text-base font-bold font-heading leading-snug">
                                {{ $community['name'] }}
                            </h3>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <p class="text-xs text-[#647067] leading-relaxed">
                            {{ $community['description'] }}
                        </p>

                        <div class="pt-3 border-t border-slate-100 space-y-2">
                            <div class="flex items-center justify-between text-xs text-slate-700">
                                <span class="flex items-center gap-1">
                                    <i data-lucide="users" class="w-3.5 h-3.5 text-[#15803D]"></i>
                                    <span>{{ $community['active_volunteers'] }}</span>
                                </span>
                                <span class="text-[11px] font-semibold text-[#0F766E] bg-teal-50 px-2 py-0.5 rounded">
                                    {{ $community['active_programs'] }}
                                </span>
                            </div>
                            <button 
                                type="button" 
                                @click="$store.ngoApp.openVolunteer('volunteer')"
                                class="w-full mt-2 py-2 text-center text-xs font-bold text-[#15803D] bg-[#F8FAF8] hover:bg-[#DCFCE7] rounded-lg transition-colors cursor-pointer"
                            >
                                Connect With Chapter
                            </button>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

    </div>
</section>
