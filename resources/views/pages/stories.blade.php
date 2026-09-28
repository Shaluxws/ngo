<x-layouts.app :title="$title" :ngo="$ngo" :seo="$seo">

    @include('components.header')

    <main id="main-content">
        <!-- Page Hero -->
        <section class="relative bg-gradient-to-b from-[#DCFCE7]/40 via-white to-[#F8FAF8] pt-16 pb-20 border-b border-[#E5EAE6] overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="max-w-3xl space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold text-[#15803D] bg-white border border-green-200 shadow-2xs reveal" data-reveal>
                        <i data-lucide="book-open" class="w-3.5 h-3.5"></i>
                        <span>VOICES FROM THE GRASSROOTS</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#17201B] tracking-tight font-heading leading-tight reveal delay-100" data-reveal>
                        Stories of Transformation & Hope
                    </h1>
                    <p class="text-base sm:text-lg text-[#647067] leading-relaxed reveal delay-200" data-reveal>
                        Real accounts of determination, resilience, and transformation from students, women entrepreneurs, village leaders, and grassroots volunteers across Tamil Nadu.
                    </p>
                </div>
            </div>
        </section>

        <!-- Stories Grid -->
        <section class="py-20 bg-white border-b border-[#E5EAE6]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse ($allStories as $s)
                        <div class="bg-[#F8FAF8] rounded-2xl overflow-hidden border border-slate-200/80 hover:bg-white hover:shadow-xl hover:border-green-200 transition-all duration-300 flex flex-col group reveal" data-reveal>
                            <div class="relative h-52 overflow-hidden">
                                <img src="{{ $s->resolved_image_url ?: 'https://images.unsplash.com/photo-1544717305-2782549b5136?auto=format&fit=crop&w=800&q=80' }}" 
                                     alt="{{ $s->title }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                
                                <div class="absolute top-3 right-3 bg-white/95 backdrop-blur-md px-2.5 py-1 rounded-full text-[11px] font-bold text-[#15803D] shadow-xs">
                                    {{ $s->category ?: 'Community Story' }}
                                </div>
                            </div>

                            <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                                <div class="space-y-2">
                                    <h3 class="text-lg font-bold text-slate-900 font-heading group-hover:text-[#15803D] transition-colors leading-snug">
                                        {{ $s->title }}
                                    </h3>
                                    
                                    @if ($s->quote)
                                        <p class="text-xs font-serif italic text-emerald-800 bg-emerald-50/60 p-3 rounded-xl border border-emerald-100">
                                            "{{ $s->quote }}"
                                        </p>
                                    @endif

                                    <p class="text-xs text-slate-600 leading-relaxed line-clamp-3">
                                        {{ $s->excerpt }}
                                    </p>
                                </div>

                                <div class="pt-4 border-t border-slate-200/60 flex items-center justify-between">
                                    <span class="text-[11px] text-slate-400 font-medium">
                                        {{ $s->author_info ?: 'Community Beneficiary' }}
                                    </span>
                                    <a href="{{ route('stories.detail', $s->slug) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#15803D] hover:text-[#166534] transition-colors">
                                        <span>Read Story</span>
                                        <i data-lucide="arrow-right" class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 py-12 text-center text-slate-500">
                            <p>Stories are being loaded from the database.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Secondary Gallery Callout -->
                <div class="mt-16 p-8 rounded-2xl bg-gradient-to-r from-emerald-50 to-teal-50 border border-green-200 flex flex-col sm:flex-row items-center justify-between gap-6 reveal" data-reveal>
                    <div class="space-y-1 text-center sm:text-left">
                        <h3 class="text-lg font-bold text-slate-900 font-heading">Explore Community Photo Gallery</h3>
                        <p class="text-xs sm:text-sm text-slate-600">View grassroots project photos, workshop sessions, and village distribution drives.</p>
                    </div>
                    <a href="{{ route('gallery') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-[#15803D] hover:bg-[#166534] shadow-sm transition-all whitespace-nowrap">
                        View Photo Gallery →
                    </a>
                </div>
            </div>
        </section>
    </main>

    @include('components.footer')

</x-layouts.app>
