<section id="about" class="py-16 md:py-24 bg-[#F8FAF8] border-b border-[#E5EAE6]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left: Community Activity Imagery -->
            <div class="lg:col-span-5 relative order-2 lg:order-1 reveal-fade-left" data-reveal>
                <div class="relative">
                    <div class="overflow-hidden rounded-2xl border-4 border-white shadow-xl bg-slate-100 aspect-4/3 sm:aspect-5/4">
                        <img 
                            src="{{ $about['image'] }}" 
                            alt="{{ $about['image_alt'] }}" 
                            class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-500"
                            loading="lazy"
                        >
                    </div>

                    <!-- Warm Overlay Quote / Badge -->
                    <div class="absolute -bottom-5 right-4 sm:-right-4 bg-white p-4 rounded-xl shadow-lg border border-[#E5EAE6] max-w-xs">
                        <div class="flex items-center gap-2 text-xs font-bold text-[#15803D] uppercase tracking-wider mb-1">
                            <i data-lucide="sparkles" class="w-4 h-4 text-[#F59E0B]"></i>
                            <span>Community-First Vision</span>
                        </div>
                        <p class="text-xs text-[#17201B] font-medium leading-snug">
                            "Real empowerment begins when local voices lead the solution."
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right: Content, Mission, Vision & Pillars -->
            <div class="lg:col-span-7 space-y-6 order-1 lg:order-2 reveal-fade-right" data-reveal>
                
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#DCFCE7] text-[#15803D] text-xs font-bold uppercase tracking-wider">
                        {{ $about['badge'] ?? 'WHO WE ARE' }}
                    </span>
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#17201B] font-heading tracking-tight mt-3">
                        {{ $about['heading'] ?? 'Creating Change Together With Communities' }}
                    </h2>
                </div>

                <div class="space-y-4 text-[#647067] text-base leading-relaxed">
                    <p>{{ $about['p1'] }}</p>
                    <p>{{ $about['p2'] }}</p>
                </div>

                <!-- 3 Pillars of Work -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                    @foreach($about['pillars'] as $pillar)
                        <div class="bg-white p-4 rounded-xl border border-[#E5EAE6] shadow-xs">
                            <div class="w-9 h-9 rounded-lg bg-[#DCFCE7] flex items-center justify-center text-[#15803D] mb-3">
                                <i data-lucide="{{ $pillar['icon'] }}" class="w-5 h-5"></i>
                            </div>
                            <h3 class="text-sm font-bold text-[#17201B] mb-1 font-heading">
                                {{ $pillar['title'] }}
                            </h3>
                            <p class="text-xs text-[#647067] leading-relaxed">
                                {{ $pillar['desc'] }}
                            </p>
                        </div>
                    @endforeach
                </div>

                <!-- CTA Actions -->
                <div class="pt-4 flex flex-wrap items-center gap-4">
                    <a 
                        href="{{ route('about') }}" 
                        class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white bg-[#15803D] hover:bg-[#166534] rounded-xl transition-all shadow-sm"
                    >
                        <span>Learn About Us</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                    <a 
                        href="{{ route('programs') }}" 
                        class="inline-flex items-center gap-2 px-5 py-3 text-sm font-semibold text-[#15803D] bg-white hover:bg-slate-50 border border-green-200 rounded-xl transition-all"
                    >
                        <span>View All Programs</span>
                    </a>
                </div>

            </div>

        </div>
    </div>
</section>
