<footer class="bg-[#17201B] text-slate-300 pt-16 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Main Footer Columns -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 lg:gap-8 pb-12 border-b border-slate-800">
            
            <!-- Col 1: Organization Bio & Socials (2 cols on lg) -->
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center gap-3">
                    @if (!empty($ngo['footer_logo_url'] ?? $ngo['logo_url']))
                        <img src="{{ $ngo['footer_logo_url'] ?? $ngo['logo_url'] }}" alt="{{ $ngo['name'] ?? 'NANBAN FOUNDATION' }}" class="h-10 w-auto max-w-[160px] object-contain rounded-lg">
                    @else
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#15803D] to-[#0F766E] flex items-center justify-center text-white shadow-md">
                            <i data-lucide="hand-heart" class="w-5 h-5"></i>
                        </div>
                    @endif
                    <div>
                        <span class="text-base font-bold text-white font-heading block leading-tight">
                            {{ $ngo['name'] ?? 'NANBAN FOUNDATION' }}
                        </span>
                        <span class="text-[11px] font-semibold text-green-400 uppercase tracking-wider">
                            {{ $ngo['tagline'] ?? 'Tamil Nadu NGO' }}
                        </span>
                    </div>
                </div>

                <p class="text-xs text-slate-400 leading-relaxed max-w-sm">
                    A registered grassroots trust committed to inclusive education, preventive healthcare, women empowerment, and environmental sustainability across Tamil Nadu.
                </p>

                <!-- Tax Exemption Pill -->
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-800 border border-slate-700 text-xs text-green-300 font-medium">
                    <i data-lucide="award" class="w-4 h-4 text-amber-400"></i>
                    <span>Registered 80G Tax Exempt Organization</span>
                </div>

                <!-- Social Links -->
                @php
                    $links = $social_links ?? \App\Models\SocialLink::active()->ordered()->get();
                @endphp
                @if ($links->isNotEmpty())
                    <div class="pt-2 flex flex-wrap items-center gap-2.5">
                        @foreach ($links as $social)
                            @php
                                $platformLower = strtolower($social->platform);
                                $iconName = match(true) {
                                    str_contains($platformLower, 'facebook') => 'facebook',
                                    str_contains($platformLower, 'instagram') => 'instagram',
                                    str_contains($platformLower, 'youtube') => 'youtube',
                                    str_contains($platformLower, 'linkedin') => 'linkedin',
                                    str_contains($platformLower, 'twitter') || str_contains($platformLower, 'x') => 'twitter',
                                    str_contains($platformLower, 'whatsapp') => 'phone',
                                    default => ($social->icon ?: 'globe'),
                                };
                            @endphp
                            <a href="{{ $social->url }}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-[#15803D] text-slate-300 hover:text-white flex items-center justify-center transition-colors" aria-label="{{ $social->platform }}" title="{{ $social->platform }}">
                                <i data-lucide="{{ $iconName }}" class="w-4 h-4"></i>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Col 2: Organization Links -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-white font-heading">
                    Organization
                </h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('about') }}" class="text-slate-400 hover:text-white transition-colors">About Our Trust</a></li>
                    <li><a href="{{ route('programs') }}" class="text-slate-400 hover:text-white transition-colors">Our Programs</a></li>
                    <li><a href="{{ route('impact') }}" class="text-slate-400 hover:text-white transition-colors">Impact & Reach</a></li>
                    <li><a href="{{ route('stories') }}" class="text-slate-400 hover:text-white transition-colors">Stories of Change</a></li>
                    <li><a href="{{ route('about') }}#leadership" class="text-slate-400 hover:text-white transition-colors">Leadership Team</a></li>
                </ul>
            </div>

            <!-- Col 3: Get Involved -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-white font-heading">
                    Get Involved
                </h4>
                <ul class="space-y-2 text-xs">
                    <li>
                        <a href="{{ route('volunteer') }}" class="text-slate-400 hover:text-white transition-colors block">
                            Become a Volunteer
                        </a>
                    </li>
                    <li>
                        <button type="button" @click="$store.ngoApp.openVolunteer('member')" class="text-slate-400 hover:text-white transition-colors text-left cursor-pointer">
                            Join Community
                        </button>
                    </li>
                    <li>
                        <button type="button" @click="$store.ngoApp.openDonate(1000)" class="text-amber-400 hover:text-amber-300 font-semibold transition-colors text-left cursor-pointer flex items-center gap-1">
                            <span>Donate Online</span>
                            <i data-lucide="heart" class="w-3 h-3 fill-amber-400"></i>
                        </button>
                    </li>
                    <li>
                        <a href="{{ route('programs') }}#campaign" class="text-slate-400 hover:text-white transition-colors block">
                            Active Campaigns
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Col 4: Explore & Contact -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-white font-heading">
                    Explore & Contact
                </h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('events') }}" class="text-slate-400 hover:text-white transition-colors">Upcoming Events</a></li>
                    <li><a href="{{ route('gallery') }}" class="text-slate-400 hover:text-white transition-colors">Photo Gallery</a></li>
                    <li><a href="{{ route('contact') }}" class="text-slate-400 hover:text-white transition-colors">Contact Office</a></li>
                    @if (!empty($ngo['phone']))
                        <li class="pt-1">
                            <a href="tel:{{ preg_replace('/[^\+\d]/', '', $ngo['phone']) }}" class="text-green-400 hover:underline flex items-center gap-1.5">
                                <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                                <span>{{ $ngo['phone'] }}</span>
                            </a>
                        </li>
                    @endif
                    @if (!empty($ngo['email']))
                        <li>
                            <a href="mailto:{{ $ngo['email'] }}" class="text-slate-400 hover:text-white hover:underline flex items-center gap-1.5 truncate">
                                <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                                <span>{{ $ngo['email'] }}</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <!-- Bottom Copyright & Jurisdiction Bar -->
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
            <div>
                <span>{{ $ngo['copyright'] ?? '© 2026 Nanban Social Foundation. All Rights Reserved.' }}</span>
            </div>
            <div class="flex items-center gap-4">
                <span>Serving {{ $ngo['state'] ?? 'Tamil Nadu, India' }}</span>
                <span>•</span>
                <a href="{{ route('admin.dashboard') }}" class="text-slate-400 hover:text-white flex items-center gap-1 transition-colors">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                    <span>Staff Portal</span>
                </a>
            </div>
        </div>
    </div>
</footer>
