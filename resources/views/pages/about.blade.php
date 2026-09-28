<x-layouts.app :title="$title" :ngo="$ngo" :seo="$seo">

    @include('components.header')

    <main id="main-content">
        <!-- Page Hero -->
        <section class="relative bg-gradient-to-b from-[#DCFCE7]/40 via-white to-[#F8FAF8] pt-16 pb-20 border-b border-[#E5EAE6] overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="max-w-3xl space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold text-[#15803D] bg-white border border-green-200 shadow-2xs reveal" data-reveal>
                        <i data-lucide="info" class="w-3.5 h-3.5"></i>
                        <span>WHO WE ARE & OUR PURPOSE</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#17201B] tracking-tight font-heading leading-tight reveal delay-100" data-reveal>
                        Dedicated to Grassroots Dignity & Sustainable Social Progress
                    </h1>
                    <p class="text-base sm:text-lg text-[#647067] leading-relaxed reveal delay-200" data-reveal>
                        Nanban Social Foundation is a community-first non-profit trust partnering with village panchayats, student scholars, women self-help groups, and healthcare volunteers across Tamil Nadu.
                    </p>
                </div>
            </div>
        </section>

        <!-- Detailed About Content -->
        <section class="py-20 bg-white border-b border-[#E5EAE6]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                    
                    <div class="lg:col-span-6 space-y-6 reveal-fade-left" data-reveal>
                        <div class="space-y-3">
                            <span class="text-xs font-bold text-[#15803D] uppercase tracking-wider block">OUR FOUNDING ETHOS</span>
                            <h2 class="text-2xl sm:text-3xl font-bold text-[#17201B] font-heading leading-tight">
                                Empowering Communities from the Grassroots Up
                            </h2>
                        </div>
                        
                        <p class="text-sm sm:text-base text-[#647067] leading-relaxed">
                            {{ $about['p1'] ?? 'We work alongside grassroots communities across Tamil Nadu to identify critical local challenges and co-create practical, sustainable solutions that enrich everyday lives.' }}
                        </p>
                        
                        <p class="text-sm sm:text-base text-[#647067] leading-relaxed">
                            {{ $about['p2'] ?? 'Our community-first approach is anchored in dignity, active citizen participation, transparent governance, and long-term socio-economic empowerment. Rather than imposing external solutions, we nurture local leadership.' }}
                        </p>

                        <!-- Core Principles Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1.5">
                                <div class="w-8 h-8 rounded-lg bg-green-100 text-[#15803D] flex items-center justify-center font-bold">
                                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                                </div>
                                <h3 class="text-xs font-bold text-slate-900">100% Transparency</h3>
                                <p class="text-[11px] text-slate-500 leading-normal">Audited financial records and open community social impact reviews.</p>
                            </div>

                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1.5">
                                <div class="w-8 h-8 rounded-lg bg-green-100 text-[#15803D] flex items-center justify-center font-bold">
                                    <i data-lucide="users" class="w-4 h-4"></i>
                                </div>
                                <h3 class="text-xs font-bold text-slate-900">Community Owned</h3>
                                <p class="text-[11px] text-slate-500 leading-normal">Driven by local village committees and youth coordinators.</p>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-6 reveal-fade-right" data-reveal>
                        <div class="relative rounded-2xl overflow-hidden shadow-xl border border-slate-200">
                            <img src="{{ $about['image'] ?? 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1000&q=80' }}" 
                                 alt="{{ $about['image_alt'] ?? 'Tamil Nadu community initiatives' }}" 
                                 class="w-full h-[400px] object-cover hover:scale-105 transition-transform duration-700">
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent flex items-end p-6">
                                <div class="text-white space-y-1">
                                    <span class="text-xs font-semibold uppercase tracking-wider text-green-300">Grassroots Presence</span>
                                    <p class="text-sm font-bold">Serving rural & semi-urban wards across Tamil Nadu</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Mission, Vision & Values -->
        <section class="py-20 bg-[#F8FAF8] border-b border-[#E5EAE6]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16 space-y-3 reveal" data-reveal>
                    <span class="text-xs font-bold text-[#15803D] uppercase tracking-wider">GUIDING PRINCIPLES</span>
                    <h2 class="text-2xl sm:text-3xl font-bold text-[#17201B] font-heading">Mission, Vision & Core Values</h2>
                    <p class="text-sm text-[#647067]">The values that guide every community project, partnership, and rupee deployed.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white rounded-2xl p-8 border border-slate-200/80 shadow-xs hover:shadow-md transition-all space-y-4 reveal delay-100" data-reveal>
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-[#15803D] flex items-center justify-center">
                            <i data-lucide="target" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 font-heading">Our Mission</h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            To empower marginalized rural and urban communities across Tamil Nadu through accessible education, preventive healthcare, livelihood incubation, and sustainable resource management.
                        </p>
                    </div>

                    <div class="bg-white rounded-2xl p-8 border border-slate-200/80 shadow-xs hover:shadow-md transition-all space-y-4 reveal delay-200" data-reveal>
                        <div class="w-12 h-12 rounded-xl bg-teal-50 text-[#0F766E] flex items-center justify-center">
                            <i data-lucide="eye" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 font-heading">Our Vision</h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            A resilient society where every individual has equal opportunities to learn, thrive in health, access dignified livelihoods, and actively shape their community's collective prosperity.
                        </p>
                    </div>

                    <div class="bg-white rounded-2xl p-8 border border-slate-200/80 shadow-xs hover:shadow-md transition-all space-y-4 reveal delay-300" data-reveal>
                        <div class="w-12 h-12 rounded-xl bg-amber-50 text-[#D97706] flex items-center justify-center">
                            <i data-lucide="heart-handshake" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 font-heading">Our Values</h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Human dignity, unwavering transparency, active grassroots volunteerism, empathy, environmental stewardship, and continuous public accountability.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Communities We Serve -->
        @if (count($communities) > 0)
            <section class="py-20 bg-white border-b border-[#E5EAE6]">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-12 reveal" data-reveal>
                        <div class="space-y-2">
                            <span class="text-xs font-bold text-[#15803D] uppercase tracking-wider">REGIONAL FOOTPRINT</span>
                            <h2 class="text-2xl sm:text-3xl font-bold text-[#17201B] font-heading">Communities We Serve</h2>
                            <p class="text-sm text-[#647067] max-w-xl">Direct on-the-ground support across Tamil Nadu districts and taluks.</p>
                        </div>
                        <a href="{{ route('impact') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#15803D] hover:underline">
                            <span>Explore Full Impact Data</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($communities as $c)
                            <div class="bg-[#F8FAF8] rounded-2xl p-5 border border-slate-200/80 hover:bg-white hover:shadow-md transition-all space-y-3 reveal" data-reveal>
                                <div class="h-36 rounded-xl overflow-hidden">
                                    <img src="{{ $c['image'] ?: 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=600&q=80' }}" alt="{{ $c['name'] }}" class="w-full h-full object-cover">
                                </div>
                                <div class="space-y-1">
                                    <h3 class="text-base font-bold text-slate-900 font-heading">{{ $c['name'] }}</h3>
                                    <span class="text-[11px] font-semibold text-[#15803D] block">{{ $c['area'] }}</span>
                                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">{{ $c['description'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <!-- Leadership Section -->
        @if (count($leadership) > 0)
            <section id="leadership" class="py-20 bg-[#F8FAF8] border-b border-[#E5EAE6]">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-2xl mx-auto mb-16 space-y-3 reveal" data-reveal>
                        <span class="text-xs font-bold text-[#15803D] uppercase tracking-wider">BOARD OF TRUSTEES & LEADERS</span>
                        <h2 class="text-2xl sm:text-3xl font-bold text-[#17201B] font-heading">Guided by Committed Leadership</h2>
                        <p class="text-sm text-[#647067]">Experienced social workers, educators, and community champions dedicated to transparent governance.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($leadership as $leader)
                            <div class="bg-white rounded-2xl overflow-hidden border border-slate-200/80 shadow-2xs hover:shadow-md transition-all text-center p-6 space-y-3 reveal" data-reveal>
                                <div class="w-24 h-24 mx-auto rounded-full overflow-hidden border-2 border-green-200 shadow-xs">
                                    <img src="{{ $leader['image'] ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80' }}" alt="{{ $leader['name'] }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900 font-heading">{{ $leader['name'] }}</h3>
                                    <span class="text-xs font-semibold text-[#15803D] block mt-0.5">{{ $leader['role'] }}</span>
                                </div>
                                <p class="text-xs text-slate-500 leading-relaxed line-clamp-3">{{ $leader['bio'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <!-- CTA Section -->
        <section class="py-20 bg-gradient-to-br from-[#166534] to-[#0F766E] text-white text-center">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 reveal" data-reveal>
                <h2 class="text-3xl sm:text-4xl font-extrabold font-heading">Be Part of Tamil Nadu's Grassroots Change</h2>
                <p class="text-sm sm:text-base text-green-100 max-w-2xl mx-auto leading-relaxed">
                    Whether as a volunteer tutor, medical professional, community organizer, or patron donor, your support creates tangible impact.
                </p>
                <div class="flex flex-wrap items-center justify-center gap-4 pt-4">
                    <a href="{{ route('volunteer') }}" class="px-6 py-3 rounded-xl text-sm font-bold text-[#166534] bg-white hover:bg-green-50 shadow-lg transition-all">
                        Become a Volunteer
                    </a>
                    <button type="button" @click="$store.ngoApp.openDonate(1000)" class="px-6 py-3 rounded-xl text-sm font-bold text-white bg-amber-500 hover:bg-amber-600 shadow-lg transition-all">
                        Donate to Foundation
                    </button>
                </div>
            </div>
        </section>
    </main>

    @include('components.footer')

</x-layouts.app>
