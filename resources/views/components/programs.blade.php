<section id="programs" class="py-16 md:py-24 bg-white border-b border-[#E5EAE6]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-14">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#DCFCE7] text-[#15803D] text-xs font-bold uppercase tracking-wider">
                CORE INITIATIVES
            </span>
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#17201B] font-heading tracking-tight mt-3">
                What We Do
            </h2>
            <p class="text-base text-[#647067] mt-3 leading-relaxed">
                Our programs focus on practical grassroots development, dignity, and sustainable social solutions tailored to rural and semi-urban Tamil Nadu.
            </p>
        </div>

        <!-- 6 Program Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @foreach($programs as $program)
                <div class="bg-[#F8FAF8] rounded-2xl p-6 sm:p-7 border border-[#E5EAE6] hover:border-green-300 hover:bg-white transition-lift flex flex-col justify-between group">
                    <div>
                        <!-- Icon & Impact Tag Header -->
                        <div class="flex items-center justify-between gap-3 mb-5">
                            <div class="w-12 h-12 rounded-xl bg-white shadow-xs border border-[#E5EAE6] flex items-center justify-center text-[#15803D] group-hover:bg-[#15803D] group-hover:text-white transition-colors">
                                <i data-lucide="{{ $program['icon'] }}" class="w-6 h-6"></i>
                            </div>
                            <span class="text-[11px] font-bold text-[#0F766E] bg-[#CCFBF1] px-2.5 py-1 rounded-full">
                                {{ $program['impact_tag'] }}
                            </span>
                        </div>

                        <!-- Title -->
                        <h3 class="text-lg font-bold text-[#17201B] font-heading mb-2.5 group-hover:text-[#15803D] transition-colors">
                            {{ $program['title'] }}
                        </h3>

                        <!-- Description -->
                        <p class="text-sm text-[#647067] leading-relaxed mb-6">
                            {{ $program['description'] }}
                        </p>
                    </div>

                    <!-- Card Footer Action -->
                    <div class="pt-4 border-t border-slate-200/60 flex items-center justify-between">
                        <button 
                            type="button" 
                            @click="$store.ngoApp.openVolunteer('volunteer')"
                            class="text-xs font-bold text-[#15803D] hover:text-[#166534] inline-flex items-center gap-1 group-hover:translate-x-1 transition-transform cursor-pointer"
                        >
                            <span>Get Involved</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </button>
                        <span class="text-[11px] text-slate-600 font-medium">Grassroots Active</span>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>
