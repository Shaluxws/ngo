<section id="leadership" class="py-16 md:py-24 bg-white border-b border-[#E5EAE6]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-14">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#DCFCE7] text-[#15803D] text-xs font-bold uppercase tracking-wider">
                COMMITTED STEWARDSHIP
            </span>
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#17201B] font-heading tracking-tight mt-3">
                Our Leadership & Advisory Board
            </h2>
            <p class="text-base text-[#647067] mt-3">
                Guided by experienced social workers, educators, and community organizers dedicated to transparent governance across Tamil Nadu.
            </p>
        </div>

        <!-- Leadership Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
            @foreach($leadership as $leader)
                <div class="bg-[#F8FAF8] rounded-2xl overflow-hidden border border-[#E5EAE6] hover:border-green-300 hover:shadow-md transition-all duration-300 flex flex-col group text-center p-6">
                    
                    <!-- Portrait -->
                    <div class="relative w-28 h-28 mx-auto rounded-full overflow-hidden border-4 border-white shadow-md bg-slate-100 mb-4">
                        <img 
                            src="{{ $leader['image'] }}" 
                            alt="{{ $leader['name'] }}" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            loading="lazy"
                        >
                    </div>

                    <!-- Name & Role -->
                    <h3 class="text-base font-bold text-[#17201B] font-heading leading-tight group-hover:text-[#15803D] transition-colors">
                        {{ $leader['name'] }}
                    </h3>
                    <div class="text-xs font-semibold text-[#0F766E] mt-1 mb-3">
                        {{ $leader['role'] }}
                    </div>

                    <!-- Bio -->
                    <p class="text-xs text-[#647067] leading-relaxed line-clamp-4">
                        {{ $leader['bio'] }}
                    </p>

                    <!-- Trust Tag -->
                    <div class="mt-4 pt-4 border-t border-slate-200/60 flex items-center justify-center gap-1 text-[11px] text-[#15803D] font-medium">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                        <span>Honorary Trustee</span>
                    </div>

                </div>
            @endforeach
        </div>

    </div>
</section>
