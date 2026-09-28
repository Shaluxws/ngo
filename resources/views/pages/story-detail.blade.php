<x-layouts.app :title="$title" :ngo="$ngo" :seo="$seo">

    @include('components.header')

    <main id="main-content">
        <!-- Breadcrumb & Header -->
        <section class="bg-gradient-to-b from-[#DCFCE7]/40 via-white to-[#F8FAF8] pt-12 pb-14 border-b border-[#E5EAE6]">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Breadcrumbs -->
                <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6 font-medium">
                    <a href="{{ route('home') }}" class="hover:text-[#15803D]">Home</a>
                    <span>/</span>
                    <a href="{{ route('stories') }}" class="hover:text-[#15803D]">Stories</a>
                    <span>/</span>
                    <span class="text-slate-900 font-bold truncate">{{ $story->title }}</span>
                </nav>

                <div class="space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold text-[#15803D] bg-white border border-green-200 shadow-2xs">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                        <span>{{ $story->category ?: 'Grassroots Impact Story' }}</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#17201B] tracking-tight font-heading leading-tight">
                        {{ $story->title }}
                    </h1>
                    <div class="flex items-center gap-3 pt-2 text-xs text-slate-500 font-medium">
                        <span class="text-slate-900 font-bold">{{ $story->author_info ?: 'Nanban Field Coordinator' }}</span>
                        <span>•</span>
                        <span>Tamil Nadu Community Story</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Story Body -->
        <section class="py-16 bg-white border-b border-[#E5EAE6]">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
                <div class="rounded-2xl overflow-hidden shadow-xl border border-slate-200">
                    <img src="{{ $story->resolved_image_url ?: 'https://images.unsplash.com/photo-1544717305-2782549b5136?auto=format&fit=crop&w=1200&q=80' }}" alt="{{ $story->title }}" class="w-full h-[420px] object-cover">
                </div>

                @if ($story->quote)
                    <div class="p-6 sm:p-8 rounded-2xl bg-[#DCFCE7]/40 border-l-4 border-[#15803D] text-slate-900 space-y-2">
                        <p class="text-base sm:text-lg font-serif italic text-emerald-950 leading-relaxed">
                            "{{ $story->quote }}"
                        </p>
                        <p class="text-xs font-bold text-[#15803D]">— {{ $story->author_info ?: 'Community Beneficiary' }}</p>
                    </div>
                @endif

                <div class="prose prose-slate max-w-none text-sm sm:text-base leading-relaxed text-slate-700 space-y-4">
                    <p>{{ $story->excerpt }}</p>
                    <p>
                        Through continuous community presence, structured local mentorship, and direct volunteer involvement, Nanban Social Foundation provides the tools, guidance, and resources required for individuals to lead sustainable transformation in their localities.
                    </p>
                    <p>
                        Our holistic model ensures that every rupee deployed translates directly into improved school attendance, healthier families, and empowered women leaders across Tamil Nadu's grassroots ecosystems.
                    </p>
                </div>

                <div class="pt-8 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
                    <a href="{{ route('stories') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-slate-900">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        <span>Back to All Stories</span>
                    </a>

                    <div class="flex items-center gap-3">
                        <button type="button" @click="$store.ngoApp.openDonate(1000)" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-[#15803D] hover:bg-[#166534] shadow-sm transition-all">
                            Support More Stories Like This
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Related Stories -->
        @if (count($relatedStories) > 0)
            <section class="py-16 bg-[#F8FAF8] border-b border-[#E5EAE6]">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-slate-900 font-heading">More Community Stories</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach ($relatedStories as $rel)
                            <a href="{{ route('stories.detail', $rel->slug) }}" class="bg-white p-5 rounded-2xl border border-slate-200/80 hover:shadow-md transition-all space-y-2 block">
                                <h3 class="text-sm font-bold text-slate-900">{{ $rel->title }}</h3>
                                <p class="text-xs text-slate-500 line-clamp-2">{{ $rel->excerpt }}</p>
                                <span class="text-xs font-bold text-[#15803D] inline-flex items-center gap-1 mt-2">
                                    <span>Read story</span>
                                    <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </main>

    @include('components.footer')

</x-layouts.app>
