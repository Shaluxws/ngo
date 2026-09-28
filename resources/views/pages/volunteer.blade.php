<x-layouts.app :title="$title" :ngo="$ngo" :seo="$seo">

    @include('components.header')

    <main id="main-content">
        <!-- Page Hero -->
        <section class="relative bg-gradient-to-b from-[#DCFCE7]/40 via-white to-[#F8FAF8] pt-16 pb-20 border-b border-[#E5EAE6] overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="max-w-3xl space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold text-[#15803D] bg-white border border-green-200 shadow-2xs reveal" data-reveal>
                        <i data-lucide="heart-handshake" class="w-3.5 h-3.5"></i>
                        <span>GRASSROOTS VOLUNTEER BRIGADE</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#17201B] tracking-tight font-heading leading-tight reveal delay-100" data-reveal>
                        Your Time & Skills Can Create Lasting Change
                    </h1>
                    <p class="text-base sm:text-lg text-[#647067] leading-relaxed reveal delay-200" data-reveal>
                        Join hundreds of dedicated volunteers across Tamil Nadu. Whether you can offer 2 hours a weekend or lead ongoing community workshops, you are empowering a neighborhood.
                    </p>
                    <div class="pt-2 flex items-center gap-3 reveal delay-300" data-reveal>
                        <button type="button" @click="$store.ngoApp.openVolunteer('volunteer')" class="px-6 py-3 rounded-xl text-xs font-bold text-white bg-[#15803D] hover:bg-[#166534] shadow-md shadow-green-900/20 transition-all">
                            Apply to Become a Volunteer →
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Volunteer Grid -->
        <section class="py-20 bg-white border-b border-[#E5EAE6]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16 space-y-3 reveal" data-reveal>
                    <span class="text-xs font-bold text-[#15803D] uppercase tracking-wider">WHY SERVE WITH US</span>
                    <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 font-heading">Direct, Verified Field Impact</h2>
                    <p class="text-sm text-slate-600">No token gestures. Our volunteers are directly on the frontlines of grassroots transformation.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="p-8 rounded-2xl bg-[#F8FAF8] border border-slate-200/80 shadow-xs space-y-4 reveal delay-75" data-reveal>
                        <div class="w-12 h-12 rounded-xl bg-green-100 text-[#15803D] flex items-center justify-center font-bold">
                            <i data-lucide="award" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 font-heading">Verified Service Hours</h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Every hour you contribute is logged in our NGO platform and eligible for verified social service certificates for academic and professional growth.
                        </p>
                    </div>

                    <div class="p-8 rounded-2xl bg-[#F8FAF8] border border-slate-200/80 shadow-xs space-y-4 reveal delay-150" data-reveal>
                        <div class="w-12 h-12 rounded-xl bg-teal-100 text-[#0F766E] flex items-center justify-center font-bold">
                            <i data-lucide="users-round" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 font-heading">Community Mentorship</h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Work closely with experienced grassroots field organizers, senior doctors, and educators who provide guidance and orientation.
                        </p>
                    </div>

                    <div class="p-8 rounded-2xl bg-[#F8FAF8] border border-slate-200/80 shadow-xs space-y-4 reveal delay-200" data-reveal>
                        <div class="w-12 h-12 rounded-xl bg-amber-100 text-[#D97706] flex items-center justify-center font-bold">
                            <i data-lucide="sparkles" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 font-heading">Tangible Change</h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            See the immediate smiles of children learning to code, villagers receiving clean medical consultations, and newly planted saplings thriving.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Volunteer Opportunities -->
        <section class="py-20 bg-[#F8FAF8] border-b border-[#E5EAE6]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16 space-y-3 reveal" data-reveal>
                    <span class="text-xs font-bold text-[#15803D] uppercase tracking-wider">SKILL ROLES</span>
                    <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 font-heading">Volunteer Opportunities</h2>
                    <p class="text-sm text-slate-600">Choose a role tailored to your background, passions, and availability.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @php
                        $roles = [
                            ['title' => 'Teaching & Tutoring', 'icon' => 'graduation-cap', 'desc' => 'Mentor school children in science, mathematics, English communication, and basic computer literacy.'],
                            ['title' => 'Health & Medical Support', 'icon' => 'heart-pulse', 'desc' => 'Assist doctors and paramedics during free village screening camps, triage, and medicine distribution.'],
                            ['title' => 'Community Outreach', 'icon' => 'megaphone', 'desc' => 'Conduct door-to-door awareness about women empowerment schemes, government subsidies, and hygiene.'],
                            ['title' => 'Event & Camp Coordination', 'icon' => 'calendar-check', 'desc' => 'Manage logistics, crowd registration, sound setup, and distribution of learning kits during drives.'],
                            ['title' => 'Photography & Social Storytelling', 'icon' => 'camera', 'desc' => 'Document field activities, record beneficiary interviews, and help share inspiring grassroots narratives.'],
                            ['title' => 'IT & Digital Support', 'icon' => 'laptop', 'desc' => 'Help maintain computer labs in village schools, assist with digital literacy workshops, and data coordination.'],
                        ];
                    @endphp

                    @foreach ($roles as $r)
                        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-md transition-all space-y-3 reveal" data-reveal>
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#15803D] flex items-center justify-center font-bold">
                                <i data-lucide="{{ $r['icon'] }}" class="w-5 h-5"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 font-heading">{{ $r['title'] }}</h3>
                            <p class="text-xs text-slate-600 leading-relaxed">{{ $r['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- How it Works (3 Steps) -->
        <section class="py-20 bg-white border-b border-[#E5EAE6]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16 space-y-3 reveal" data-reveal>
                    <span class="text-xs font-bold text-[#15803D] uppercase tracking-wider">ONBOARDING PROCESS</span>
                    <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 font-heading">How It Works in 3 Simple Steps</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="p-6 rounded-2xl bg-[#F8FAF8] border border-slate-200 text-center space-y-3 reveal delay-75" data-reveal>
                        <div class="w-10 h-10 rounded-full bg-[#15803D] text-white font-bold flex items-center justify-center mx-auto text-sm">1</div>
                        <h3 class="text-base font-bold text-slate-900">Sign Up Online</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">Fill out the brief volunteer interest form specifying your district, skills, and weekend or weekday availability.</p>
                    </div>

                    <div class="p-6 rounded-2xl bg-[#F8FAF8] border border-slate-200 text-center space-y-3 reveal delay-150" data-reveal>
                        <div class="w-10 h-10 rounded-full bg-[#0F766E] text-white font-bold flex items-center justify-center mx-auto text-sm">2</div>
                        <h3 class="text-base font-bold text-slate-900">Brief Orientation</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">Our district coordinator will connect with you to align your skills with nearby ongoing programs and upcoming drives.</p>
                    </div>

                    <div class="p-6 rounded-2xl bg-[#F8FAF8] border border-slate-200 text-center space-y-3 reveal delay-200" data-reveal>
                        <div class="w-10 h-10 rounded-full bg-[#D97706] text-white font-bold flex items-center justify-center mx-auto text-sm">3</div>
                        <h3 class="text-base font-bold text-slate-900">Serve & Log Hours</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">Participate in field drives, make a tangible difference in grassroots lives, and build a verified service portfolio.</p>
                    </div>
                </div>

                <div class="mt-12 text-center reveal" data-reveal>
                    <button type="button" @click="$store.ngoApp.openVolunteer('volunteer')" class="px-8 py-3.5 rounded-xl text-sm font-bold text-white bg-[#15803D] hover:bg-[#166534] shadow-md transition-all">
                        Register as a Volunteer Now
                    </button>
                </div>
            </div>
        </section>
    </main>

    @include('components.footer')

</x-layouts.app>
