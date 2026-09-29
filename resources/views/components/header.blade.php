<header 
    x-data="{ scrolled: false, mobileOpen: false }"
    @scroll.window="scrolled = (window.pageYOffset > 20)"
    @keydown.escape.window="mobileOpen = false"
    @click.outside="mobileOpen = false"
    :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-md py-3 border-b border-[#E5EAE6]' : 'bg-white py-3.5 sm:py-4 border-b border-[#E5EAE6]'"
    class="sticky top-0 z-40 transition-all duration-300 w-full"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            
            <!-- NGO Logo & Brand -->
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 sm:gap-3 group focus:outline-none focus-visible:ring-2 focus-visible:ring-[#15803D] rounded-lg">
                @if (!empty($ngo['logo_url']))
                    <img src="{{ $ngo['logo_url'] }}" alt="{{ $ngo['name'] ?? 'NANBAN FOUNDATION' }}" class="h-9 sm:h-11 w-auto max-w-[150px] sm:max-w-[180px] object-contain rounded-lg">
                @else
                    <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-gradient-to-br from-[#15803D] to-[#0F766E] flex items-center justify-center text-white shadow-md shadow-green-900/10 group-hover:scale-105 transition-transform shrink-0">
                        <i data-lucide="hand-heart" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                    </div>
                @endif
                <div class="flex flex-col min-w-0">
                    <span class="text-sm sm:text-base lg:text-lg font-bold tracking-tight text-[#17201B] font-heading leading-tight truncate">
                        {{ $ngo['name'] ?? 'NANBAN FOUNDATION' }}
                    </span>
                    <span class="text-[9px] sm:text-[11px] font-semibold text-[#15803D] uppercase tracking-wider truncate">
                        {{ $ngo['tagline'] ?? 'Tamil Nadu NGO' }}
                    </span>
                </div>
            </a>

            <!-- Concise Primary Desktop Navigation -->
            <nav class="hidden lg:flex items-center gap-6 xl:gap-8" aria-label="Main Navigation">
                <a href="{{ route('home') }}" class="text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'text-[#15803D] font-bold' : 'text-[#647067] hover:text-[#15803D]' }}">
                    Home
                </a>
                <a href="{{ route('about') }}" class="text-sm font-medium transition-colors {{ request()->routeIs('about') ? 'text-[#15803D] font-bold' : 'text-[#647067] hover:text-[#15803D]' }}">
                    About
                </a>
                <a href="{{ route('programs') }}" class="text-sm font-medium transition-colors {{ request()->routeIs('programs*') ? 'text-[#15803D] font-bold' : 'text-[#647067] hover:text-[#15803D]' }}">
                    Programs
                </a>
                <a href="{{ route('impact') }}" class="text-sm font-medium transition-colors {{ request()->routeIs('impact') ? 'text-[#15803D] font-bold' : 'text-[#647067] hover:text-[#15803D]' }}">
                    Impact
                </a>
                <a href="{{ route('stories') }}" class="text-sm font-medium transition-colors {{ request()->routeIs('stories*') ? 'text-[#15803D] font-bold' : 'text-[#647067] hover:text-[#15803D]' }}">
                    Stories
                </a>
                <a href="{{ route('events') }}" class="text-sm font-medium transition-colors {{ request()->routeIs('events*') ? 'text-[#15803D] font-bold' : 'text-[#647067] hover:text-[#15803D]' }}">
                    Events
                </a>
                <a href="{{ route('contact') }}" class="text-sm font-medium transition-colors {{ request()->routeIs('contact') ? 'text-[#15803D] font-bold' : 'text-[#647067] hover:text-[#15803D]' }}">
                    Contact
                </a>
            </nav>

            <!-- Desktop CTAs -->
            <div class="hidden md:flex items-center gap-3">
                <a 
                    href="{{ route('volunteer') }}"
                    class="px-4 py-2 text-sm font-semibold text-[#15803D] bg-[#DCFCE7]/70 hover:bg-[#DCFCE7] border border-green-200 rounded-xl transition-all hover:shadow-2xs"
                >
                    Join Us
                </a>
                <button 
                    type="button" 
                    @click="$store.ngoApp.openDonate(1000)"
                    class="inline-flex items-center gap-2 px-5 py-2 text-sm font-bold text-white bg-[#15803D] hover:bg-[#166534] shadow-sm shadow-green-800/20 rounded-xl transition-all transform hover:-translate-y-0.5 cursor-pointer"
                >
                    <i data-lucide="heart" class="w-4 h-4 fill-white"></i>
                    <span>Donate</span>
                </button>
            </div>

            <!-- Mobile Action & Hamburger Button -->
            <div class="flex lg:hidden items-center gap-2">
                <button 
                    type="button" 
                    @click="$store.ngoApp.openDonate(500)"
                    class="min-h-[38px] px-3.5 py-1.5 text-xs font-bold text-white bg-[#15803D] active:bg-[#166534] rounded-xl shadow-2xs inline-flex items-center gap-1 cursor-pointer"
                >
                    <i data-lucide="heart" class="w-3.5 h-3.5 fill-white"></i>
                    <span>Donate</span>
                </button>
                <button 
                    type="button"
                    @click="mobileOpen = !mobileOpen; $nextTick(() => window.refreshIcons())"
                    class="min-w-[44px] min-h-[44px] flex items-center justify-center rounded-xl text-[#17201B] hover:bg-slate-100 active:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-[#15803D]"
                    aria-label="Toggle navigation menu"
                    :aria-expanded="mobileOpen"
                >
                    <i x-show="!mobileOpen" data-lucide="menu" class="w-6 h-6"></i>
                    <i x-show="mobileOpen" data-lucide="x" class="w-6 h-6" style="display: none;"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Drawer -->
        <div 
            x-show="mobileOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="lg:hidden mt-3 pt-3 pb-4 border-t border-[#E5EAE6] space-y-2 bg-white rounded-2xl shadow-xl p-4"
            style="display: none;"
        >
            <div class="flex flex-col gap-1 py-1">
                <a @click="mobileOpen = false" href="{{ route('home') }}" class="px-3.5 py-2.5 min-h-[44px] flex items-center rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'bg-green-50 text-[#15803D] font-bold' : 'text-[#17201B] hover:bg-slate-50' }}">
                    Home
                </a>
                <a @click="mobileOpen = false" href="{{ route('about') }}" class="px-3.5 py-2.5 min-h-[44px] flex items-center rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('about') ? 'bg-green-50 text-[#15803D] font-bold' : 'text-[#17201B] hover:bg-slate-50' }}">
                    About
                </a>
                <a @click="mobileOpen = false" href="{{ route('programs') }}" class="px-3.5 py-2.5 min-h-[44px] flex items-center rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('programs*') ? 'bg-green-50 text-[#15803D] font-bold' : 'text-[#17201B] hover:bg-slate-50' }}">
                    Programs
                </a>
                <a @click="mobileOpen = false" href="{{ route('impact') }}" class="px-3.5 py-2.5 min-h-[44px] flex items-center rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('impact') ? 'bg-green-50 text-[#15803D] font-bold' : 'text-[#17201B] hover:bg-slate-50' }}">
                    Impact
                </a>
                <a @click="mobileOpen = false" href="{{ route('stories') }}" class="px-3.5 py-2.5 min-h-[44px] flex items-center rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('stories*') ? 'bg-green-50 text-[#15803D] font-bold' : 'text-[#17201B] hover:bg-slate-50' }}">
                    Stories
                </a>
                <a @click="mobileOpen = false" href="{{ route('events') }}" class="px-3.5 py-2.5 min-h-[44px] flex items-center rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('events*') ? 'bg-green-50 text-[#15803D] font-bold' : 'text-[#17201B] hover:bg-slate-50' }}">
                    Events
                </a>
                <a @click="mobileOpen = false" href="{{ route('contact') }}" class="px-3.5 py-2.5 min-h-[44px] flex items-center rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('contact') ? 'bg-green-50 text-[#15803D] font-bold' : 'text-[#17201B] hover:bg-slate-50' }}">
                    Contact
                </a>
            </div>

            <div class="pt-3 border-t border-slate-100 flex flex-col gap-2">
                <a 
                    href="{{ route('volunteer') }}"
                    @click="mobileOpen = false"
                    class="w-full min-h-[44px] flex items-center justify-center py-2.5 px-4 text-center text-sm font-semibold text-[#15803D] bg-green-50 active:bg-green-100 border border-green-200 rounded-xl"
                >
                    Join Us / Volunteer
                </a>
                <button 
                    type="button" 
                    @click="mobileOpen = false; $store.ngoApp.openDonate(1000)"
                    class="w-full min-h-[44px] flex items-center justify-center py-2.5 px-4 text-center text-sm font-bold text-white bg-[#15803D] active:bg-[#166534] rounded-xl shadow-sm cursor-pointer"
                >
                    Donate to Foundation
                </button>
            </div>
        </div>
    </div>
</header>
