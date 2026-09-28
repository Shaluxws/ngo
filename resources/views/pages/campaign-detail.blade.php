<x-layouts.app :title="$title" :ngo="$ngo" :seo="$seo">

    @include('components.header')

    <main id="main-content">
        <!-- Breadcrumb & Header -->
        <section class="bg-gradient-to-b from-[#DCFCE7]/40 via-white to-[#F8FAF8] pt-12 pb-16 border-b border-[#E5EAE6]">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Breadcrumbs -->
                <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6 font-medium">
                    <a href="{{ route('home') }}" class="hover:text-[#15803D]">Home</a>
                    <span>/</span>
                    <a href="{{ route('programs') }}" class="hover:text-[#15803D]">Campaigns</a>
                    <span>/</span>
                    <span class="text-slate-900 font-bold truncate">{{ $campaignItem->title }}</span>
                </nav>

                <div class="space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold text-amber-800 bg-amber-100 border border-amber-200">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5 text-amber-600"></i>
                        <span>{{ $campaignItem->badge ?: 'PRIORITY SOCIAL CAMPAIGN' }}</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#17201B] tracking-tight font-heading leading-tight">
                        {{ $campaignItem->title }}
                    </h1>
                    <p class="text-base text-[#647067] leading-relaxed">
                        {{ $campaignItem->subtitle }}
                    </p>
                </div>
            </div>
        </section>

        <!-- Campaign Progress & Donation Panel -->
        <section class="py-16 bg-white border-b border-[#E5EAE6]">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
                <div class="rounded-2xl overflow-hidden shadow-xl border border-slate-200">
                    <img src="{{ $campaignItem->resolved_image_url ?: 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1200&q=80' }}" alt="{{ $campaignItem->title }}" class="w-full h-[420px] object-cover">
                </div>

                <!-- Live Progress Card -->
                <div class="p-8 rounded-2xl bg-[#F8FAF8] border border-slate-200/80 space-y-6 shadow-xs">
                    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                        <div>
                            <span class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Funds Raised</span>
                            <div class="flex items-baseline gap-2 mt-1">
                                <span class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-heading">₹{{ number_format($campaignItem->raised_amount) }}</span>
                                <span class="text-sm font-semibold text-slate-500">of ₹{{ number_format($campaignItem->goal_amount) }} goal</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-6 text-xs text-slate-600 font-medium">
                            <div>
                                <span class="text-base font-bold text-slate-900 block">{{ $campaignItem->supporters_count }}</span>
                                <span class="text-slate-400">Generous Patrons</span>
                            </div>
                            <div>
                                <span class="text-base font-bold text-[#15803D] block">{{ $campaignItem->progress_percentage }}%</span>
                                <span class="text-slate-400">Target Achieved</span>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="w-full h-3 bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-[#15803D] to-emerald-400 rounded-full transition-all duration-1000" style="width: {{ min($campaignItem->progress_percentage, 100) }}%;"></div>
                    </div>

                    <!-- Suggested Amounts -->
                    <div class="space-y-3 pt-2">
                        <span class="text-xs font-bold text-slate-700 block">Select a Contribution Amount:</span>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            @foreach ($campaignItem->suggested_amounts ?? [500, 1000, 2500, 5000] as $amt)
                                <button type="button" @click="$store.ngoApp.openDonate({{ $amt }})" class="p-3 rounded-xl border border-slate-200 bg-white hover:border-[#15803D] hover:bg-green-50 text-slate-900 font-bold text-sm transition-all">
                                    ₹{{ number_format($amt) }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="button" @click="$store.ngoApp.openDonate(1000)" class="w-full py-3.5 rounded-xl text-sm font-bold text-white bg-[#15803D] hover:bg-[#166534] shadow-md shadow-green-900/20 transition-all flex items-center justify-center gap-2">
                            <i data-lucide="heart" class="w-4 h-4 fill-white"></i>
                            <span>Donate to this Campaign</span>
                        </button>
                    </div>
                </div>

                <!-- Impact Bullets -->
                @if (!empty($campaignItem->impact_bullets))
                    <div class="space-y-4">
                        <h2 class="text-xl font-bold text-slate-900 font-heading">Where Your Donation Goes</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach ($campaignItem->impact_bullets as $bullet)
                                <div class="p-4 rounded-xl bg-white border border-slate-200 flex items-start gap-3">
                                    <div class="w-5 h-5 rounded-full bg-green-100 text-[#15803D] flex items-center justify-center shrink-0 mt-0.5">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </div>
                                    <span class="text-xs sm:text-sm text-slate-700 leading-relaxed">{{ $bullet }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </main>

    @include('components.footer')

</x-layouts.app>
