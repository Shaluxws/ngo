<section id="stories" class="py-16 md:py-24 bg-[#F8FAF8] border-b border-[#E5EAE6]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-14">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#DCFCE7] text-[#15803D] text-xs font-bold uppercase tracking-wider">
                REAL VOICES
            </span>
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#17201B] font-heading tracking-tight mt-3">
                Stories of Real Change & Hope
            </h2>
            <p class="text-base text-[#647067] mt-3">
                Behind every number is an individual, a family, and a community transformed through collective care and support.
            </p>
        </div>

        <!-- Stories Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @foreach($stories as $story)
                <div class="bg-white rounded-3xl overflow-hidden border border-[#E5EAE6] hover:border-green-300 hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
                    
                    <div>
                        <!-- Story Image -->
                        <div class="relative h-52 overflow-hidden bg-slate-100">
                            <img 
                                src="{{ $story['image'] }}" 
                                alt="{{ $story['title'] }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                loading="lazy"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                            
                            <!-- Category Badge -->
                            <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-md px-3 py-1 rounded-full text-xs font-bold text-[#15803D] shadow-sm">
                                {{ $story['category'] }}
                            </div>

                            <div class="absolute bottom-3 left-4 right-4 text-white text-xs font-semibold truncate">
                                {{ $story['author_info'] }}
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6 space-y-4">
                            <h3 class="text-lg font-bold text-[#17201B] font-heading leading-snug group-hover:text-[#15803D] transition-colors">
                                {{ $story['title'] }}
                            </h3>

                            <p class="text-xs text-[#647067] leading-relaxed">
                                {{ $story['excerpt'] }}
                            </p>

                            <!-- Direct Quote Highlight Box -->
                            <div class="bg-[#F8FAF8] border-l-3 border-[#15803D] p-3.5 rounded-r-xl">
                                <p class="text-xs italic text-slate-700 leading-snug">
                                    "{{ $story['quote'] }}"
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action -->
                    <div class="px-6 pb-6 pt-2">
                        <button 
                            type="button" 
                            @click="$store.ngoApp.openVolunteer('volunteer')"
                            class="w-full py-2.5 px-4 text-center text-xs font-bold text-[#15803D] bg-green-50 hover:bg-[#DCFCE7] rounded-xl transition-colors cursor-pointer inline-flex items-center justify-center gap-1"
                        >
                            <span>Support Similar Lives</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>

                </div>
            @endforeach
        </div>

    </div>
</section>
