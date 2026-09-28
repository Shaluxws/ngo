<x-layouts.app :title="$title" :ngo="$ngo" :seo="$seo">

    @include('components.header')

    <main id="main-content">
        <!-- Page Hero -->
        <section class="relative bg-gradient-to-b from-[#DCFCE7]/40 via-white to-[#F8FAF8] pt-16 pb-20 border-b border-[#E5EAE6] overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="max-w-3xl space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold text-[#15803D] bg-white border border-green-200 shadow-2xs reveal" data-reveal>
                        <i data-lucide="images" class="w-3.5 h-3.5"></i>
                        <span>GRASSROOTS MOMENTS & FIELD DRIVES</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#17201B] tracking-tight font-heading leading-tight reveal delay-100" data-reveal>
                        Community Action in Pictures
                    </h1>
                    <p class="text-base sm:text-lg text-[#647067] leading-relaxed reveal delay-200" data-reveal>
                        A visual journey through our on-the-ground initiatives, village health camps, classroom digital learning workshops, and tree plantation drives across Tamil Nadu.
                    </p>
                </div>
            </div>
        </section>

        <!-- Gallery Grid -->
        <section class="py-20 bg-white border-b border-[#E5EAE6]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @forelse ($allGallery as $g)
                        <div class="group relative rounded-2xl overflow-hidden shadow-xs hover:shadow-xl border border-slate-200 cursor-pointer aspect-4/3 bg-slate-100 reveal" 
                             data-reveal
                             @click="$store.ngoApp.openLightbox('{{ $g['image'] }}', '{{ $g['title'] }}')">
                            
                            <img src="{{ $g['image'] }}" 
                                 alt="{{ $g['title'] }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4 text-white">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-green-300">{{ $g['category'] }}</span>
                                <h3 class="text-xs sm:text-sm font-bold leading-snug">{{ $g['title'] }}</h3>
                                <span class="text-[11px] text-slate-300 flex items-center gap-1 mt-1">
                                    <i data-lucide="map-pin" class="w-3 h-3"></i>
                                    <span>{{ $g['location'] }}</span>
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-4 py-12 text-center text-slate-500">
                            <p>Gallery items are being loaded from the database.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </main>

    @include('components.footer')

</x-layouts.app>
