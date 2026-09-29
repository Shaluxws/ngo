<!-- Impact Statistics Strip -->
<section class="relative bg-white py-12 border-b border-[#E5EAE6] -mt-px z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-8 reveal" data-reveal>
            <span class="text-xs font-semibold uppercase tracking-wider text-[#15803D] bg-[#DCFCE7] px-3 py-1 rounded-full">
                Grassroots Footprint
            </span>
            <h2 class="text-xl sm:text-2xl font-bold text-[#17201B] font-heading mt-2">
                Demonstrated Impact Across Tamil Nadu
            </h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($impact_stats as $idx => $stat)
                <div class="bg-[#F8FAF8] rounded-2xl p-5 sm:p-6 border border-[#E5EAE6] hover:border-green-300 hover:shadow-md transition-all duration-300 group flex flex-col items-center text-center reveal" data-reveal style="transition-delay: {{ $idx * 100 }}ms;">
                    <div class="w-12 h-12 rounded-xl bg-white shadow-xs border border-[#E5EAE6] flex items-center justify-center text-[#15803D] group-hover:bg-[#15803D] group-hover:text-white transition-colors mb-4">
                        <i data-lucide="{{ $stat['icon'] }}" class="w-6 h-6"></i>
                    </div>
                    <div class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#17201B] font-heading tracking-tight mb-1 group-hover:text-[#15803D] transition-colors" data-counter="{{ $stat['value'] }}">
                        {{ $stat['value'] }}
                    </div>
                    <div class="text-sm font-bold text-[#17201B] mb-1">
                        {{ $stat['label'] }}
                    </div>
                    <div class="text-xs text-[#647067] leading-relaxed line-clamp-2">
                        {{ $stat['description'] }}
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>
