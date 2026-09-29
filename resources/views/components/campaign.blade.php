<section id="campaign" class="py-16 md:py-24 bg-white border-b border-[#E5EAE6]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-14">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#FEF3C7] text-[#D97706] text-xs font-bold uppercase tracking-wider">
                <i data-lucide="heart" class="w-3.5 h-3.5 fill-[#D97706]"></i>
                {{ $campaign['badge'] ?? 'FEATURED CAMPAIGN' }}
            </span>
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#17201B] font-heading tracking-tight mt-3">
                Support a Cause That Empowers Children
            </h2>
            <p class="text-base text-[#647067] mt-3">
                Your direct contribution powers real educational infrastructure, books, and mentors for underprivileged village students.
            </p>
        </div>

        <!-- Featured Campaign Card -->
        <div class="bg-[#F8FAF8] rounded-3xl border border-[#E5EAE6] p-6 sm:p-8 lg:p-10 shadow-sm overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                
                <!-- Campaign Photo & Status Badges -->
                <div class="lg:col-span-6 relative">
                    <div class="overflow-hidden rounded-2xl border-4 border-white shadow-lg aspect-16/10 sm:aspect-4/3 bg-slate-100">
                        <img 
                            src="{{ $campaign['image'] ?? 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=1200&q=80' }}" 
                            alt="{{ $campaign['image_alt'] ?? 'Community Education Project' }}" 
                            class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-700"
                            loading="lazy"
                        >
                    </div>
                    
                    <!-- Overlay Badge -->
                    <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-lg text-xs font-bold text-[#15803D] shadow-md border border-green-100 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-[#15803D] animate-ping"></span>
                        <span>Active Fundraising Drive</span>
                    </div>

                    <div class="absolute bottom-4 right-4 bg-slate-900/90 backdrop-blur-md px-3.5 py-1.5 rounded-lg text-xs font-semibold text-white shadow-md flex items-center gap-1.5">
                        <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-400"></i>
                        <span>{{ $campaign['days_left'] ?? 18 }} Days Remaining</span>
                    </div>
                </div>

                <!-- Campaign Details & Contribution Box -->
                <div class="lg:col-span-6 space-y-6">
                    
                    <div>
                        <span class="text-xs font-bold text-[#0F766E] uppercase tracking-wider">
                            Tamil Nadu Rural Education Project
                        </span>
                        <h3 class="text-xl sm:text-2xl font-bold text-[#17201B] font-heading mt-1 leading-snug">
                            {{ $campaign['title'] ?? 'Digital Classrooms & Science Labs for Rural Schools' }}
                        </h3>
                        <p class="text-sm text-[#647067] mt-3 leading-relaxed">
                            {{ $campaign['subtitle'] ?? 'Empowering underprivileged government school students with digital smart classrooms and learning resources.' }}
                        </p>
                    </div>

                    <!-- Progress Bar & Metrics -->
                    <div class="bg-white p-5 rounded-2xl border border-[#E5EAE6] space-y-3 shadow-xs">
                        <div class="flex items-baseline justify-between">
                            <div>
                                <span class="text-2xl sm:text-3xl font-extrabold text-[#15803D] font-heading">
                                    ₹{{ number_format($campaign['raised'] ?? 725000) }}
                                </span>
                                <span class="text-xs text-[#647067] font-medium ml-1">
                                    raised of ₹{{ number_format($campaign['goal'] ?? 1000000) }} goal
                                </span>
                            </div>
                            <span class="text-sm font-extrabold text-[#15803D] bg-[#DCFCE7] px-2.5 py-1 rounded-full">
                                {{ $campaign['progress_percentage'] ?? 72 }}%
                            </span>
                        </div>

                        <!-- Visual Progress Bar -->
                        <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
                            <div 
                                class="h-full bg-gradient-to-r from-[#15803D] to-[#0F766E] rounded-full transition-all duration-1000"
                                style="width: {{ $campaign['progress_percentage'] ?? 72 }}%"
                            ></div>
                        </div>

                        <div class="flex items-center justify-between text-xs text-[#647067] pt-1">
                            <span class="flex items-center gap-1">
                                <i data-lucide="users" class="w-3.5 h-3.5 text-slate-500"></i>
                                <strong class="text-[#17201B]">{{ $campaign['supporters'] ?? 348 }}</strong> generous supporters
                            </span>
                            <span class="text-[11px] text-green-700 font-semibold">
                                80G Tax Deductible
                            </span>
                        </div>
                    </div>

                    <!-- Impact Key Highlights -->
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-[#17201B]">
                        @foreach($campaign['impact_bullets'] ?? ['12 Village Schools Equipped', '2,400+ Students Benefited', 'STEM Learning Toolkits', 'Quarterly Teacher Trainings'] as $bullet)
                            <li class="flex items-start gap-2">
                                <i data-lucide="check-circle" class="w-4 h-4 text-[#15803D] shrink-0 mt-0.5"></i>
                                <span>{{ $bullet }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <!-- Donation Quick Buttons & CTA -->
                    <div class="pt-2 space-y-3">
                        <div class="flex flex-wrap gap-2 items-center">
                            <span class="text-xs font-semibold text-[#647067]">Quick Amount:</span>
                            @foreach($campaign['suggested_amounts'] ?? [500, 1000, 2500, 5000] as $amount)
                                <button 
                                    type="button" 
                                    @click="$store.ngoApp.openDonate({{ $amount }})"
                                    class="px-3 py-1.5 text-xs font-bold text-[#17201B] bg-white border border-[#E5EAE6] hover:border-green-400 hover:bg-green-50 rounded-lg transition-colors cursor-pointer"
                                >
                                    ₹{{ number_format($amount) }}
                                </button>
                            @endforeach
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3 pt-2">
                            <button 
                                type="button" 
                                @click="$store.ngoApp.openDonate(1000)"
                                class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3.5 text-sm font-bold text-white bg-[#15803D] hover:bg-[#166534] rounded-xl shadow-md shadow-green-800/20 transition-all cursor-pointer"
                            >
                                <i data-lucide="heart" class="w-4 h-4 fill-white"></i>
                                <span>Donate to This Campaign</span>
                            </button>
                            <button 
                                type="button" 
                                @click="$store.ngoApp.openVolunteer('volunteer')"
                                class="px-5 py-3.5 text-xs font-bold text-[#17201B] bg-white hover:bg-slate-50 border border-[#E5EAE6] rounded-xl transition-colors cursor-pointer text-center"
                            >
                                Share / Sponsor
                            </button>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>
</section>
