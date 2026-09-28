<x-layouts.app :title="$title" :ngo="$ngo" :seo="$seo">

    @include('components.header')

    <main id="main-content">
        <!-- Impact Hero -->
        <section class="relative bg-gradient-to-b from-[#DCFCE7]/40 via-white to-[#F8FAF8] pt-16 pb-20 border-b border-[#E5EAE6] overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="max-w-3xl space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold text-[#15803D] bg-white border border-green-200 shadow-2xs reveal" data-reveal>
                        <i data-lucide="bar-chart-2" class="w-3.5 h-3.5"></i>
                        <span>MEASURABLE SOCIAL IMPACT & TRANSPARENCY</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#17201B] tracking-tight font-heading leading-tight reveal delay-100" data-reveal>
                        Real Lives, Quantified Progress Across Tamil Nadu
                    </h1>
                    <p class="text-base sm:text-lg text-[#647067] leading-relaxed reveal delay-200" data-reveal>
                        We measure our success not by intentions, but by verified outcomes: children empowered in classrooms, patients screened at free medical camps, and saplings nurtured to maturity.
                    </p>
                </div>
            </div>
        </section>

        <!-- Big Impact Animated Counters -->
        <section class="py-16 bg-white border-b border-[#E5EAE6]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="p-6 rounded-2xl bg-[#F8FAF8] border border-slate-200/80 text-center space-y-2 reveal delay-75" data-reveal>
                        <div class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#15803D] font-heading" data-counter="10000+">
                            10,000+
                        </div>
                        <p class="text-xs sm:text-sm font-bold text-slate-900">Lives Directly Reached</p>
                        <p class="text-[11px] text-slate-500">Across education & health programs</p>
                    </div>

                    <div class="p-6 rounded-2xl bg-[#F8FAF8] border border-slate-200/80 text-center space-y-2 reveal delay-150" data-reveal>
                        <div class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#0F766E] font-heading" data-counter="4">
                            4
                        </div>
                        <p class="text-xs sm:text-sm font-bold text-slate-900">District Chapters</p>
                        <p class="text-[11px] text-slate-500">Coimbatore, Salem, Madurai, Chennai</p>
                    </div>

                    <div class="p-6 rounded-2xl bg-[#F8FAF8] border border-slate-200/80 text-center space-y-2 reveal delay-200" data-reveal>
                        <div class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#D97706] font-heading" data-counter="{{ $totalVolunteersCount }}+">
                            {{ $totalVolunteersCount }}+
                        </div>
                        <p class="text-xs sm:text-sm font-bold text-slate-900">Active Volunteers</p>
                        <p class="text-[11px] text-slate-500">Dedicated grassroots champions</p>
                    </div>

                    <div class="p-6 rounded-2xl bg-[#F8FAF8] border border-slate-200/80 text-center space-y-2 reveal delay-300" data-reveal>
                        <div class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-emerald-800 font-heading" data-counter="50+">
                            50+
                        </div>
                        <p class="text-xs sm:text-sm font-bold text-slate-900">Community Initiatives</p>
                        <p class="text-[11px] text-slate-500">Completed with social audits</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- District & Community Reach -->
        <section class="py-20 bg-[#F8FAF8] border-b border-[#E5EAE6]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16 space-y-3 reveal" data-reveal>
                    <span class="text-xs font-bold text-[#15803D] uppercase tracking-wider">REGIONAL BREAKDOWN</span>
                    <h2 class="text-2xl sm:text-3xl font-bold text-[#17201B] font-heading">Our Footprint Across Tamil Nadu</h2>
                    <p class="text-sm text-[#647067]">Directly serving rural panchayats, tribal hamlets, and underserved urban wards.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @forelse ($districts as $d)
                        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs hover:shadow-md transition-all space-y-3 reveal" data-reveal>
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#15803D] flex items-center justify-center font-bold">
                                <i data-lucide="map-pin" class="w-5 h-5"></i>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 font-heading">{{ $d->name }} District</h3>
                            <div class="space-y-1 text-xs text-slate-600">
                                <p><span class="text-slate-400">Areas Covered:</span> {{ $d->areas->count() }} Taluks</p>
                                <p><span class="text-slate-400">Focal Points:</span> {{ $d->areas->flatMap->communities->count() }} Centers</p>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-4 text-center py-8 text-slate-500">
                            Districts loaded from database.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Volunteer Hours & Service Impact -->
        <section class="py-20 bg-white border-b border-[#E5EAE6]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                    <div class="lg:col-span-6 space-y-6 reveal-fade-left" data-reveal>
                        <span class="text-xs font-bold text-[#15803D] uppercase tracking-wider">VOLUNTEER CONTRIBUTION</span>
                        <h2 class="text-2xl sm:text-3xl font-bold text-[#17201B] font-heading leading-tight">
                            The Driving Force: Our Grassroots Volunteer Brigade
                        </h2>
                        <p class="text-sm sm:text-base text-[#647067] leading-relaxed">
                            From university student tutors to retired doctors and agricultural specialists, our volunteers dedicate thousands of service hours annually to uplift their fellow citizens.
                        </p>
                        
                        <div class="p-6 rounded-2xl bg-[#DCFCE7]/30 border border-green-200 flex items-center gap-6">
                            <div class="w-14 h-14 rounded-2xl bg-[#15803D] text-white flex items-center justify-center shrink-0 shadow-md">
                                <i data-lucide="clock" class="w-7 h-7"></i>
                            </div>
                            <div>
                                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-heading block" data-counter="4500+">4,500+</span>
                                <span class="text-xs font-semibold text-[#15803D]">Verified Community Service Hours Logged</span>
                            </div>
                        </div>

                        <div>
                            <a href="{{ route('volunteer') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-[#15803D] hover:bg-[#166534] shadow-sm transition-all">
                                <span>Join the Volunteer Brigade</span>
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>

                    <div class="lg:col-span-6 reveal-fade-right" data-reveal>
                        <div class="rounded-2xl overflow-hidden shadow-xl border border-slate-200">
                            <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&w=1000&q=80" alt="Volunteers in Tamil Nadu" class="w-full h-96 object-cover">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Transparency Guarantee -->
        <section class="py-20 bg-[#F8FAF8] text-center border-b border-[#E5EAE6]">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 reveal" data-reveal>
                <div class="w-12 h-12 rounded-2xl bg-green-100 text-[#15803D] flex items-center justify-center mx-auto">
                    <i data-lucide="shield-check" class="w-6 h-6"></i>
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 font-heading">Our Commitment to 100% Transparency</h2>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Nanban Social Foundation undergoes regular statutory audits by independent Chartered Accountants. All program expenditures and community project logs are documented and verifiable.
                </p>
            </div>
        </section>
    </main>

    @include('components.footer')

</x-layouts.app>
