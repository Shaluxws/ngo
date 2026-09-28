<x-layouts.app :title="$title" :ngo="$ngo" :seo="$seo">

    @include('components.header')

    <main id="main-content">
        <!-- Breadcrumb & Hero -->
        <section class="bg-gradient-to-b from-[#DCFCE7]/40 via-white to-[#F8FAF8] pt-12 pb-16 border-b border-[#E5EAE6]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Breadcrumbs -->
                <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6 font-medium">
                    <a href="{{ route('home') }}" class="hover:text-[#15803D]">Home</a>
                    <span>/</span>
                    <a href="{{ route('programs') }}" class="hover:text-[#15803D]">Programs</a>
                    <span>/</span>
                    <span class="text-slate-900 font-bold truncate">{{ $program->title }}</span>
                </nav>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                    <div class="lg:col-span-7 space-y-4">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold text-[#15803D] bg-white border border-green-200 shadow-2xs">
                            <i data-lucide="{{ $program->icon ?: 'award' }}" class="w-3.5 h-3.5"></i>
                            <span>{{ $program->impact_tag ?: 'Flagship Social Program' }}</span>
                        </div>
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#17201B] tracking-tight font-heading leading-tight">
                            {{ $program->title }}
                        </h1>
                        <p class="text-base text-[#647067] leading-relaxed">
                            {{ $program->description }}
                        </p>
                        <div class="pt-2 flex flex-wrap items-center gap-3">
                            <a href="{{ route('volunteer') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-[#15803D] hover:bg-[#166534] shadow-sm transition-all">
                                Volunteer for this Program
                            </a>
                            <button type="button" @click="$store.ngoApp.openDonate(1000)" class="px-5 py-2.5 rounded-xl text-xs font-bold text-[#15803D] bg-green-50 hover:bg-green-100 border border-green-200 transition-all">
                                Donate to Support
                            </button>
                        </div>
                    </div>

                    <div class="lg:col-span-5">
                        <div class="rounded-2xl overflow-hidden shadow-xl border border-slate-200">
                            <img src="{{ $program->resolved_image_url ?: 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=800&q=80' }}" alt="{{ $program->title }}" class="w-full h-80 object-cover">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Program In-Depth Details -->
        <section class="py-16 bg-white border-b border-[#E5EAE6]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                    
                    <div class="lg:col-span-8 space-y-10">
                        <!-- Section: Objectives -->
                        <div class="space-y-4">
                            <h2 class="text-2xl font-bold text-slate-900 font-heading">Program Purpose & Objectives</h2>
                            <p class="text-sm sm:text-base text-slate-600 leading-relaxed">
                                Our {{ $program->title }} initiative directly addresses critical grassroots challenges across Tamil Nadu through structured community engagement, certified local field coordinators, and measurable milestones.
                            </p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-2">
                                    <div class="w-7 h-7 rounded-lg bg-green-100 text-[#15803D] flex items-center justify-center font-bold">
                                        <i data-lucide="check" class="w-4 h-4"></i>
                                    </div>
                                    <h3 class="text-xs font-bold text-slate-900">Direct Grassroots Delivery</h3>
                                    <p class="text-xs text-slate-500">Implemented on-the-ground without bureaucratic intermediaries.</p>
                                </div>
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-2">
                                    <div class="w-7 h-7 rounded-lg bg-green-100 text-[#15803D] flex items-center justify-center font-bold">
                                        <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                                    </div>
                                    <h3 class="text-xs font-bold text-slate-900">Quarterly Impact Audits</h3>
                                    <p class="text-xs text-slate-500">Every intervention is tracked with verifiable outcomes.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Beneficiaries -->
                        <div class="space-y-4 pt-6 border-t border-slate-100">
                            <h2 class="text-xl font-bold text-slate-900 font-heading">Who We Serve in Tamil Nadu</h2>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                This initiative actively supports first-generation school students, rural women artisans, village farmers, and elderly community members in underserved panchayats.
                            </p>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-4 space-y-6">
                        <div class="p-6 rounded-2xl bg-[#F8FAF8] border border-slate-200/80 space-y-4">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 font-heading">Program Overview</h3>
                            <div class="space-y-3 text-xs">
                                <div class="flex justify-between py-2 border-b border-slate-200">
                                    <span class="text-slate-500">Jurisdiction</span>
                                    <span class="font-bold text-slate-900">Tamil Nadu, India</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-slate-200">
                                    <span class="text-slate-500">Operational Model</span>
                                    <span class="font-bold text-slate-900">Grassroots Volunteer Led</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-slate-200">
                                    <span class="text-slate-500">Audit Status</span>
                                    <span class="font-bold text-emerald-700">100% Transparent</span>
                                </div>
                            </div>

                            <button type="button" @click="$store.ngoApp.openDonate(1000)" class="w-full py-3 rounded-xl text-xs font-bold text-white bg-[#15803D] hover:bg-[#166534] shadow-sm transition-all">
                                Support this Initiative
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Related Programs -->
        @if (count($relatedPrograms) > 0)
            <section class="py-16 bg-[#F8FAF8] border-b border-[#E5EAE6]">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-slate-900 font-heading">Other Social Programs</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach ($relatedPrograms as $rel)
                            <a href="{{ route('programs.detail', $rel->slug) }}" class="bg-white p-5 rounded-2xl border border-slate-200/80 hover:shadow-md transition-all space-y-2 block">
                                <h3 class="text-sm font-bold text-slate-900">{{ $rel->title }}</h3>
                                <p class="text-xs text-slate-500 line-clamp-2">{{ $rel->description }}</p>
                                <span class="text-xs font-bold text-[#15803D] inline-flex items-center gap-1 mt-2">
                                    <span>Learn more</span>
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
