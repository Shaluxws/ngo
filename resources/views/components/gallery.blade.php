<section id="gallery" class="py-16 md:py-24 bg-white border-b border-[#E5EAE6]" x-data="{ activeFilter: 'All' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#DCFCE7] text-[#15803D] text-xs font-bold uppercase tracking-wider">
                    PHOTO DIARY
                </span>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#17201B] font-heading tracking-tight mt-3">
                    Moments That Matter
                </h2>
                <p class="text-base text-[#647067] mt-2 max-w-2xl">
                    Snapshots from classroom smart sessions, health camps, tree plantations, and self-help group workshops.
                </p>
            </div>

            <!-- Filter Pills -->
            <div class="flex flex-wrap items-center gap-2">
                <template x-for="cat in ['All', 'Education', 'Healthcare', 'Environment', 'Livelihood', 'Community']" :key="cat">
                    <button 
                        type="button"
                        @click="activeFilter = cat; $nextTick(() => window.refreshIcons())"
                        :class="activeFilter === cat ? 'bg-[#15803D] text-white shadow-xs' : 'bg-slate-100 text-[#647067] hover:bg-slate-200'"
                        class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all cursor-pointer"
                        x-text="cat"
                    ></button>
                </template>
            </div>
        </div>

        <!-- Gallery Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($gallery as $item)
                <div 
                    x-show="activeFilter === 'All' || activeFilter === '{{ $item['category'] }}'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="group relative rounded-2xl overflow-hidden aspect-4/3 bg-slate-100 border border-[#E5EAE6] shadow-xs cursor-pointer"
                    @click="$store.ngoApp.openLightbox('{{ $item['image'] }}', '{{ $item['title'] }} ({{ $item['location'] }})')"
                >
                    <img 
                        src="{{ $item['image'] }}" 
                        alt="{{ $item['title'] }}" 
                        class="w-full h-full object-cover group-hover:scale-108 transition-transform duration-700"
                        loading="lazy"
                    >
                    
                    <!-- Hover Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-between p-5 text-white">
                        <div class="self-end bg-white/20 backdrop-blur-md p-2 rounded-full">
                            <i data-lucide="maximize-2" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold text-amber-300 uppercase tracking-wider block">
                                {{ $item['category'] }} • {{ $item['location'] }}
                            </span>
                            <h4 class="text-base font-bold font-heading">
                                {{ $item['title'] }}
                            </h4>
                        </div>
                    </div>

                    <!-- Permanent Static Badge for Mobile -->
                    <div class="absolute bottom-2 left-2 sm:hidden bg-slate-900/80 backdrop-blur-md px-2.5 py-1 rounded-md text-[11px] font-semibold text-white">
                        {{ $item['title'] }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-10 text-center">
            <button 
                type="button" 
                @click="$store.ngoApp.openVolunteer('volunteer')"
                class="inline-flex items-center gap-2 px-6 py-3 text-xs font-bold text-[#17201B] bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors cursor-pointer"
            >
                <i data-lucide="camera" class="w-4 h-4 text-[#15803D]"></i>
                <span>View All Community Albums</span>
            </button>
        </div>

    </div>
</section>
