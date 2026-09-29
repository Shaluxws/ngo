<section id="hero" class="relative bg-gradient-to-b from-[#F8FAF8] via-white to-[#F8FAF8] pt-12 pb-16 md:pt-20 md:pb-24 overflow-hidden border-b border-[#E5EAE6]">
    
    <!-- Subtle Background Glow Elements -->
    <div class="absolute top-10 left-1/2 -translate-x-1/2 w-full max-w-7xl h-96 bg-gradient-to-r from-green-100/40 via-teal-50/30 to-amber-50/20 blur-3xl -z-10 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
            
            <!-- Left Column: Content & CTAs -->
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                
                <!-- Regional Badge -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#DCFCE7] border border-green-300 text-[#15803D] text-xs font-bold tracking-wide uppercase shadow-xs reveal" data-reveal>
                    <span class="w-2 h-2 rounded-full bg-[#15803D] animate-pulse"></span>
                    <span>{{ $hero['badge'] ?? 'SERVING COMMUNITIES ACROSS TAMIL NADU' }}</span>
                </div>

                <!-- Main Heading -->
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight text-[#17201B] font-heading leading-[1.15] reveal delay-75" data-reveal>
                    <span class="block">{{ $hero['heading_line1'] ?? 'Together, We Can Build' }}</span>
                    <span class="bg-gradient-to-r from-[#15803D] to-[#0F766E] bg-clip-text text-transparent">
                        {{ $hero['heading_line2'] ?? 'Stronger Communities.' }}
                    </span>
                </h1>

                <!-- Supporting Subheading -->
                <p class="text-base sm:text-lg text-[#647067] max-w-2xl mx-auto lg:mx-0 leading-relaxed font-normal reveal delay-150" data-reveal>
                    {{ $hero['subheading'] ?? 'Working with communities, volunteers and local leaders to create meaningful and sustainable social impact.' }}
                </p>

                <!-- Action CTAs -->
                <div class="pt-2 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 reveal delay-200" data-reveal>
                    <button 
                        type="button" 
                        @click="$store.ngoApp.openDonate(1000)"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-7 py-3.5 text-base font-bold text-white bg-[#15803D] hover:bg-[#166534] rounded-xl shadow-lg shadow-green-800/20 hover:shadow-green-800/30 transition-all transform hover:-translate-y-0.5 cursor-pointer"
                    >
                        <i data-lucide="heart" class="w-5 h-5 fill-white"></i>
                        <span>{{ $hero['primary_cta'] ?? 'Support Our Mission' }}</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                    
                    <a 
                        href="{{ route('volunteer') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 text-base font-semibold text-[#17201B] bg-white hover:bg-slate-50 border border-[#E5EAE6] hover:border-slate-300 rounded-xl transition-all shadow-xs cursor-pointer"
                    >
                        <i data-lucide="users" class="w-5 h-5 text-[#15803D]"></i>
                        <span>{{ $hero['secondary_cta'] ?? 'Join Our Community' }}</span>
                    </a>
                </div>

                <!-- Trust Strip / Mini Badges -->
                <div class="pt-4 flex flex-wrap items-center justify-center lg:justify-start gap-y-2 gap-x-6 text-xs text-[#647067] reveal delay-300" data-reveal>
                    <div class="flex items-center gap-1.5 font-medium">
                        <i data-lucide="shield-check" class="w-4 h-4 text-[#15803D]"></i>
                        <span>80G Tax Exemption Certified</span>
                    </div>
                    <div class="flex items-center gap-1.5 font-medium">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-[#15803D]"></i>
                        <span>Audited Grassroots Programs</span>
                    </div>
                    <div class="flex items-center gap-1.5 font-medium">
                        <i data-lucide="map-pin" class="w-4 h-4 text-[#15803D]"></i>
                        <span>4 Active District Chapters</span>
                    </div>
                </div>

            </div>

            <!-- Right Column: Emotional Community Imagery -->
            <div class="lg:col-span-5 relative reveal-fade-right mt-6 lg:mt-0" data-reveal>
                <div class="relative mx-auto max-w-md lg:max-w-none">
                    
                    <!-- Decorative Backdrop Border Frame -->
                    <div class="absolute -inset-2 rounded-2xl bg-gradient-to-tr from-[#15803D]/20 via-teal-500/10 to-amber-500/20 transform -rotate-1 -z-10"></div>
                    
                    <!-- Main Hero Image -->
                    <div class="overflow-hidden rounded-2xl border-4 border-white shadow-2xl bg-slate-100 aspect-4/3 sm:aspect-5/4">
                        <img 
                            src="{{ $hero['image'] }}" 
                            alt="{{ $hero['image_alt'] }}" 
                            class="w-full h-full object-cover object-center transform hover:scale-105 transition-transform duration-700"
                            loading="eager"
                        >
                    </div>

                    <!-- Floating Metric Card 1: Lives Reached -->
                    <div class="absolute bottom-2 left-2 sm:-bottom-6 sm:-left-6 bg-white/95 backdrop-blur-md p-3 sm:p-4 rounded-xl shadow-xl border border-[#E5EAE6] flex items-center gap-2.5 sm:gap-3.5">
                        <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-lg bg-[#DCFCE7] flex items-center justify-center text-[#15803D] shrink-0">
                            <i data-lucide="heart-handshake" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                        </div>
                        <div>
                            <div class="text-lg sm:text-xl font-bold text-[#17201B] font-heading leading-tight" data-counter="{{ $hero['stats_pill']['count'] ?? '10,000+' }}">{{ $hero['stats_pill']['count'] ?? '10,000+' }}</div>
                            <div class="text-[11px] sm:text-xs text-[#647067] font-medium">{{ $hero['stats_pill']['label'] ?? 'Lives Reached' }}</div>
                        </div>
                    </div>

                    <!-- Floating Metric Card 2: Transparency -->
                    <div class="absolute top-2 right-2 sm:-top-4 sm:-right-4 bg-white/95 backdrop-blur-md py-1.5 px-3 sm:py-2 sm:px-3.5 rounded-lg shadow-lg border border-[#E5EAE6] flex items-center gap-1.5 sm:gap-2">
                        <i data-lucide="award" class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-[#F59E0B]"></i>
                        <span class="text-[11px] sm:text-xs font-semibold text-[#17201B]">100% Volunteer Driven</span>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>
