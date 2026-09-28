<x-layouts.app :title="$title" :ngo="$ngo" :seo="$seo">

    @include('components.header')

    <main id="main-content">
        <!-- Page Hero -->
        <section class="relative bg-gradient-to-b from-[#DCFCE7]/40 via-white to-[#F8FAF8] pt-16 pb-20 border-b border-[#E5EAE6] overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="max-w-3xl space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold text-[#15803D] bg-white border border-green-200 shadow-2xs reveal" data-reveal>
                        <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                        <span>GRASSROOTS INITIATIVES & INTERVENTIONS</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#17201B] tracking-tight font-heading leading-tight reveal delay-100" data-reveal>
                        Our Programs & Social Interventions
                    </h1>
                    <p class="text-base sm:text-lg text-[#647067] leading-relaxed reveal delay-200" data-reveal>
                        Targeted, sustainable programs created in direct consultation with rural communities, designed to foster educational equity, health resilience, and economic independence.
                    </p>
                </div>
            </div>
        </section>

        <!-- Programs Directory -->
        <section class="py-20 bg-white border-b border-[#E5EAE6]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse ($allPrograms as $p)
                        <div class="bg-[#F8FAF8] rounded-2xl overflow-hidden border border-slate-200/80 hover:bg-white hover:shadow-xl hover:border-green-200 transition-all duration-300 flex flex-col group reveal" data-reveal>
                            <div class="relative h-48 overflow-hidden">
                                <img src="{{ $p->resolved_image_url ?: 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=800&q=80' }}" 
                                     alt="{{ $p->title }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                
                                <div class="absolute top-3 right-3 bg-white/95 backdrop-blur-md px-2.5 py-1 rounded-full text-[11px] font-bold text-[#15803D] shadow-xs">
                                    {{ $p->impact_tag ?: 'Community Impact' }}
                                </div>
                            </div>

                            <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                                <div class="space-y-2">
                                    <div class="w-10 h-10 rounded-xl bg-green-100 text-[#15803D] flex items-center justify-center font-bold">
                                        <i data-lucide="{{ $p->icon ?: 'graduation-cap' }}" class="w-5 h-5"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-900 font-heading group-hover:text-[#15803D] transition-colors">
                                        {{ $p->title }}
                                    </h3>
                                    <p class="text-xs text-slate-600 leading-relaxed line-clamp-3">
                                        {{ $p->description }}
                                    </p>
                                </div>

                                <div class="pt-4 border-t border-slate-200/60 flex items-center justify-between">
                                    <a href="{{ route('programs.detail', $p->slug) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#15803D] hover:text-[#166534] transition-colors">
                                        <span>View Program Details</span>
                                        <i data-lucide="arrow-right" class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 py-12 text-center text-slate-500">
                            <p>Programs are being loaded from the database.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Featured Campaign Section -->
        @if (!empty($campaign))
            <section id="campaign" class="py-20 bg-[#F8FAF8] border-b border-[#E5EAE6]">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    @include('components.campaign')
                </div>
            </section>
        @endif

        <!-- Volunteer CTA -->
        <section class="py-20 bg-gradient-to-br from-[#166534] to-[#0F766E] text-white text-center">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 reveal" data-reveal>
                <h2 class="text-3xl sm:text-4xl font-extrabold font-heading">Partner in Our Community Programs</h2>
                <p class="text-sm sm:text-base text-green-100 leading-relaxed">
                    Have a specific skill in teaching, healthcare, rural technology, or community outreach? Join our field teams across Tamil Nadu.
                </p>
                <div class="flex flex-wrap items-center justify-center gap-4 pt-2">
                    <a href="{{ route('volunteer') }}" class="px-6 py-3 rounded-xl text-sm font-bold text-[#166534] bg-white hover:bg-green-50 shadow-md transition-all">
                        Become a Program Volunteer
                    </a>
                </div>
            </div>
        </section>
    </main>

    @include('components.footer')

</x-layouts.app>
