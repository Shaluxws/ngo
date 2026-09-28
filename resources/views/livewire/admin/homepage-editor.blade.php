<div>
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-bold text-slate-900 font-heading">Homepage CMS & Content Editor</h1>
                <span class="inline-flex items-center gap-1 text-[10px] text-emerald-700 font-bold bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Database-Driven</span>
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">Manage, draft, publish, and reorder dynamic content for all 12 sections of the public Tamil Nadu NGO website.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.website.settings', ['tab' => 'top_bar']) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-800 text-xs font-semibold shadow-xs transition-colors">
                <i data-lucide="sparkles" class="w-3.5 h-3.5 text-emerald-600"></i>
                <span>Top Bar & Announcement CMS →</span>
            </a>
            <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-semibold shadow-xs transition-colors">
                <i data-lucide="external-link" class="w-3.5 h-3.5 text-slate-400"></i>
                <span>Preview Public Website</span>
            </a>
        </div>
    </div>

    <!-- Feedback messages -->
    @if (session()->has('success'))
        <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between animate-fadeIn">
            <div class="flex items-center gap-2">
                <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        
        <!-- Left 1 Col: Section Organizer (Navigation, Order & Enable/Disable) -->
        <div class="space-y-4">
            <!-- Global Top Bar Notice Card -->
            <div class="p-3.5 bg-emerald-50/80 border border-emerald-200/80 rounded-2xl flex items-start gap-3 text-xs text-emerald-950">
                <i data-lucide="info" class="w-4 h-4 text-emerald-700 shrink-0 mt-0.5"></i>
                <div>
                    <div class="font-bold text-emerald-900">Looking for Top Bar?</div>
                    <p class="text-[11px] text-emerald-800 mt-0.5 leading-snug">
                        The public top announcement bar is a global website setting. Edit it under 
                        <a href="{{ route('admin.website.settings', ['tab' => 'top_bar']) }}" class="underline font-bold hover:text-emerald-950">
                            General & SEO → Top Bar
                        </a>.
                    </p>
                </div>
            </div>

            @php
                $sectionNames = [
                    'hero' => 'Hero',
                    'impact_stats' => 'Impact Stats',
                    'about' => 'About',
                    'programs' => 'Programs',
                    'communities' => 'Communities',
                    'campaign' => 'Campaign',
                    'events' => 'Events',
                    'leadership' => 'Leadership',
                    'stories' => 'Stories',
                    'gallery' => 'Gallery',
                    'join_cta' => 'Join CTA',
                    'contact' => 'Contact',
                ];
            @endphp

            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Homepage Sections</h3>
                    <span class="text-[10px] text-slate-400 font-mono">{{ $sections->count() }} total</span>
                </div>

                <div class="space-y-1.5">
                    @foreach ($sections as $index => $sec)
                        <div class="flex items-center justify-between rounded-xl p-2.5 transition-all {{ $activeSectionKey === $sec->section_key ? 'bg-emerald-50 text-emerald-950 font-bold ring-1 ring-emerald-300 shadow-xs' : 'text-slate-700 hover:bg-slate-50' }}">
                            <button type="button" 
                                    wire:click="setSection('{{ $sec->section_key }}')" 
                                    class="flex-1 text-left flex items-center gap-2 text-xs truncate">
                                <span class="w-5 h-5 rounded-md {{ $activeSectionKey === $sec->section_key ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600' }} font-mono text-[10px] flex items-center justify-center shrink-0">
                                    {{ $index + 1 }}
                                </span>
                                <span class="truncate">{{ $sectionNames[$sec->section_key] ?? ucwords(str_replace('_', ' ', $sec->section_key)) }}</span>
                            </button>

                            <div class="flex items-center gap-1 shrink-0">
                                <!-- Status Badge -->
                                @if (!$sec->is_enabled)
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-slate-100 text-slate-500 border border-slate-200" title="Section is hidden publicly">Disabled</span>
                                @elseif ($sec->is_published)
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800" title="Published live">Live</span>
                                @else
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-amber-100 text-amber-800" title="Draft saved">Draft</span>
                                @endif

                                <!-- Reorder Buttons -->
                                <button type="button" wire:click="moveSection({{ $sec->id }}, 'up')" class="p-1 text-slate-400 hover:text-slate-700" title="Move Section Up">
                                    <i data-lucide="chevron-up" class="w-3.5 h-3.5"></i>
                                </button>
                                <button type="button" wire:click="moveSection({{ $sec->id }}, 'down')" class="p-1 text-slate-400 hover:text-slate-700" title="Move Section Down">
                                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                                </button>
                                <!-- Status Toggle -->
                                <button type="button" 
                                        wire:click="toggleSection({{ $sec->id }})" 
                                        class="w-3.5 h-3.5 rounded-full {{ $sec->is_enabled ? 'bg-emerald-500 ring-2 ring-emerald-200' : 'bg-slate-300' }}" 
                                        title="{{ $sec->is_enabled ? 'Section Enabled (Visible on Homepage)' : 'Section Disabled (Hidden from Homepage)' }}">
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Quick Tips Box -->
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80 text-xs text-slate-600 space-y-2">
                <div class="flex items-center gap-2 text-slate-800 font-bold">
                    <i data-lucide="info" class="w-4 h-4 text-emerald-600"></i>
                    <span>CMS Instructions</span>
                </div>
                <p class="text-[11px] leading-relaxed text-slate-500">
                    • <strong>Save Draft:</strong> Persists edits in database without affecting live public website.<br>
                    • <strong>Publish Section:</strong> Pushes current draft live to visitors instantly.<br>
                    • <strong>Edit/Delete:</strong> Modify or remove individual cards with instant preview.<br>
                    • <strong>Reorder (↑/↓):</strong> Adjust public display sequence.
                </p>
            </div>
        </div>

        <!-- Right 3 Cols: Active Section CMS Editor -->
        <div class="lg:col-span-3 space-y-6">

            @php
                $currentSec = $sections->firstWhere('section_key', $activeSectionKey);
            @endphp
            
            <!-- 1. HERO SECTION EDITOR -->
            @if ($activeSectionKey === 'hero')
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 mb-6 border-b border-slate-100 gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">SECTION 1 • HERO</span>
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full {{ !$currentSec?->is_enabled ? 'bg-slate-100 text-slate-600 border border-slate-200' : ($currentSec?->is_published ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                    ● {{ !$currentSec?->is_enabled ? 'Section Disabled' : ($currentSec?->is_published ? 'Published' : 'Draft Saved') }}
                                </span>
                                <span wire:dirty wire:target="heroData" class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200 animate-pulse">
                                    ● Unsaved Changes
                                </span>
                            </div>
                            <h2 class="text-base font-bold text-slate-900 font-heading mt-1">Hero Banner Section</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Edit the content, headlines, CTA buttons, metrics, and banner imagery displayed in the main hero banner of the public website.</p>
                            <p class="text-[11px] text-slate-400 mt-1">Last saved: {{ $currentSec?->updated_at?->format('d M Y, h:i A') ?? 'N/A' }} | Last published: {{ $currentSec?->published_at?->format('d M Y, h:i A') ?? 'Not published yet' }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" wire:click="saveHero(false)" class="px-3.5 py-2 rounded-xl border border-slate-200 text-slate-700 text-xs font-semibold hover:bg-slate-50 transition-colors">
                                Save Draft
                            </button>
                            <button type="button" wire:click="saveHero(true)" class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-md shadow-emerald-800/20 transition-all">
                                Publish Section
                            </button>
                        </div>
                    </div>

                    <form wire:submit.prevent="saveHero(true)" class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Top Eyebrow / Badge Text</label>
                            <p class="text-[10px] text-slate-400 mb-1">Displayed above the Hero heading on public homepage.</p>
                            <input type="text" wire:model="heroData.badge" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Main Heading (Line 1) <span class="text-red-500">*</span></label>
                                <p class="text-[10px] text-slate-400 mb-1">First line of hero headline displayed on the public homepage.</p>
                                <input type="text" wire:model="heroData.heading_line1" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                                @error('heroData.heading_line1') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Highlighted Heading (Line 2) <span class="text-red-500">*</span></label>
                                <p class="text-[10px] text-slate-400 mb-1">Displayed as the highlighted second line of the Hero heading with gradient effect.</p>
                                <input type="text" wire:model="heroData.heading_line2" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                                @error('heroData.heading_line2') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Subheading / Description <span class="text-red-500">*</span></label>
                            <p class="text-[10px] text-slate-400 mb-1">Main introduction paragraph displayed beneath the headline.</p>
                            <textarea rows="3" wire:model="heroData.subheading" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900"></textarea>
                            @error('heroData.subheading') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Primary Button Text</label>
                                <p class="text-[10px] text-slate-400 mb-1">Displayed on the primary Hero CTA button.</p>
                                <input type="text" wire:model="heroData.primary_cta" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Primary Button Link / Anchor</label>
                                <p class="text-[10px] text-slate-400 mb-1">Target URL or page anchor e.g. #campaign.</p>
                                <input type="text" wire:model="heroData.primary_cta_url" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Secondary Button Text</label>
                                <p class="text-[10px] text-slate-400 mb-1">Displayed on secondary outline CTA button.</p>
                                <input type="text" wire:model="heroData.secondary_cta" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Secondary Button Link / Anchor</label>
                                <p class="text-[10px] text-slate-400 mb-1">Target URL or page anchor e.g. #join-cta.</p>
                                <input type="text" wire:model="heroData.secondary_cta_url" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Hero Image <span class="text-red-500">*</span></label>
                                
                                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-3">
                                    <div class="flex items-center gap-3">
                                        @if (!empty($heroData['image']))
                                            <div class="relative group w-24 h-16 rounded-xl overflow-hidden border border-slate-200 shadow-2xs shrink-0 bg-slate-100">
                                                <img src="{{ $heroData['image'] }}" alt="Hero Preview" class="w-full h-full object-cover">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-xs font-bold text-slate-800 truncate">Current Hero Image</div>
                                                <div class="text-[10px] text-slate-400 truncate">{{ $heroData['image'] }}</div>
                                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                                    <label class="px-2.5 py-1 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-[11px] font-semibold shadow-2xs flex items-center gap-1 cursor-pointer transition-all">
                                                        <i data-lucide="upload" class="w-3 h-3"></i>
                                                        <span>Upload from Device</span>
                                                        <input type="file" wire:model="heroImageFile" accept="image/*" class="sr-only">
                                                    </label>
                                                    <button type="button" wire:click="openMediaPicker('heroData.image')" class="px-2.5 py-1 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-[11px] font-semibold shadow-2xs flex items-center gap-1 transition-all">
                                                        <i data-lucide="image" class="w-3 h-3 text-emerald-600"></i>
                                                        <span>Media Library</span>
                                                    </button>
                                                    <button type="button" wire:click="removeImage('heroData.image')" class="px-2.5 py-1 rounded-lg border border-red-200 bg-red-50 hover:bg-red-100 text-red-700 text-[11px] font-semibold transition-all">
                                                        Remove
                                                    </button>
                                                </div>
                                                <div wire:loading wire:target="heroImageFile" class="mt-1 text-[10px] text-emerald-700 font-semibold flex items-center gap-1">
                                                    <i data-lucide="loader-2" class="w-3 h-3 animate-spin"></i>
                                                    <span>Uploading image...</span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="w-24 h-16 rounded-xl border border-dashed border-slate-300 flex items-center justify-center bg-white text-slate-400 shrink-0">
                                                <i data-lucide="image" class="w-6 h-6"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="text-xs font-semibold text-slate-700">No Image Selected</div>
                                                <div class="text-[10px] text-slate-400">Upload a photo from your computer or choose from Media Library.</div>
                                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                                    <label class="px-3 py-1.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-2xs flex items-center gap-1.5 cursor-pointer transition-all">
                                                        <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                                                        <span>Upload from Device</span>
                                                        <input type="file" wire:model="heroImageFile" accept="image/*" class="sr-only">
                                                    </label>
                                                    <button type="button" wire:click="openMediaPicker('heroData.image')" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-2xs flex items-center gap-1.5 transition-all">
                                                        <i data-lucide="image" class="w-3.5 h-3.5 text-emerald-600"></i>
                                                        <span>Media Library</span>
                                                    </button>
                                                </div>
                                                <div wire:loading wire:target="heroImageFile" class="mt-1 text-[10px] text-emerald-700 font-semibold flex items-center gap-1">
                                                    <i data-lucide="loader-2" class="w-3 h-3 animate-spin"></i>
                                                    <span>Uploading image...</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- URL input (prefilled / fallback) -->
                                    <div class="pt-2 border-t border-slate-200/60">
                                        <div class="flex items-center justify-between text-[10px] text-slate-500 mb-1">
                                            <span>Image URL Reference:</span>
                                            <span class="text-slate-400 font-mono text-[9px]">(Auto-filled from Media Library)</span>
                                        </div>
                                        <input type="text" wire:model="heroData.image" placeholder="https://... or choose from Media Library" class="block w-full rounded-xl px-3 py-1.5 text-[11px] bg-white border border-slate-200 focus:border-emerald-600 text-slate-700">
                                        @error('heroData.image') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Hero Image Alt Text</label>
                                <p class="text-[10px] text-slate-400 mb-1">Accessibility text describing the photo for screen readers and SEO.</p>
                                <input type="text" wire:model="heroData.image_alt" placeholder="e.g. Tamil Nadu community volunteers and children engaged in an interactive learning workshop" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Floating Metric Count</label>
                                <p class="text-[10px] text-slate-400 mb-1">Displayed in floating badge on hero image e.g. 10,000+.</p>
                                <input type="text" wire:model="heroData.stats_count" placeholder="10,000+" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Floating Metric Label</label>
                                <p class="text-[10px] text-slate-400 mb-1">Label for floating metric e.g. Lives Reached in 2026.</p>
                                <input type="text" wire:model="heroData.stats_label" placeholder="Lives Reached in 2026" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Trust Badge Text</label>
                                <p class="text-[10px] text-slate-400 mb-1">Displayed at the bottom of hero banner e.g. 100% Volunteer Driven & Transparent.</p>
                                <input type="text" wire:model="heroData.badge_trust" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
                            <button type="button" wire:click="saveHero(false)" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-xs font-semibold hover:bg-slate-50">
                                Save Draft
                            </button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-md shadow-emerald-800/20">
                                Save & Publish Section
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- 2. IMPACT STATS CMS -->
            @if ($activeSectionKey === 'impact_stats')
                <div class="space-y-6">
                    <!-- Section Header Editor -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 mb-4 border-b border-slate-100 gap-2">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">SECTION 2 • IMPACT STATS</span>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full {{ !$currentSec?->is_enabled ? 'bg-slate-100 text-slate-600 border border-slate-200' : ($currentSec?->is_published ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                        ● {{ !$currentSec?->is_enabled ? 'Section Disabled' : ($currentSec?->is_published ? 'Published' : 'Draft Saved') }}
                                    </span>
                                    <span wire:dirty wire:target="impactStatsHeader" class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200 animate-pulse">
                                        ● Unsaved Changes
                                    </span>
                                </div>
                                <h2 class="text-base font-bold text-slate-900 font-heading mt-1">Impact Statistics Section</h2>
                                <p class="text-xs text-slate-500 mt-0.5">Edit the milestone impact statistics and overarching headline displayed in the public Impact Statistics section.</p>
                                <p class="text-[11px] text-slate-400 mt-1">Last saved: {{ $currentSec?->updated_at?->format('d M Y, h:i A') ?? 'N/A' }} | Last published: {{ $currentSec?->published_at?->format('d M Y, h:i A') ?? 'Not published yet' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" wire:click="saveImpactStatsHeader(false)" class="px-3.5 py-2 rounded-xl border border-slate-200 text-slate-700 text-xs font-semibold hover:bg-slate-50">
                                    Save Draft
                                </button>
                                <button type="button" wire:click="saveImpactStatsHeader(true)" class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs">
                                    Publish Section
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Section Badge</label>
                                <p class="text-[10px] text-slate-400 mb-1">Displayed above the Impact Statistics headline e.g. OUR COMMUNITY IMPACT.</p>
                                <input type="text" wire:model="impactStatsHeader.badge" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Section Heading</label>
                                <p class="text-[10px] text-slate-400 mb-1">Primary headline displayed for the Impact Statistics section.</p>
                                <input type="text" wire:model="impactStatsHeader.heading" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                        </div>
                    </div>

                    <!-- Metrics Cards List -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                        <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 font-heading">Metric Cards ({{ $impactMetrics->count() }})</h3>
                                <p class="text-xs text-slate-500">Edit individual cards, toggle visibility, reorder display sequence, or add statistical milestone cards.</p>
                            </div>
                            <button type="button" wire:click="openItemModal('metric')" class="px-3.5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs flex items-center gap-1.5">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                <span>+ Add Impact Statistic</span>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @forelse ($impactMetrics as $metric)
                                <div class="p-4 rounded-xl border {{ $metric->is_enabled ? 'border-slate-200 bg-slate-50/60' : 'border-slate-200/60 bg-slate-100/60 opacity-70' }} flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full {{ $metric->is_enabled ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-200 text-slate-600' }}">
                                                ● {{ $metric->is_enabled ? 'Active' : 'Hidden' }}
                                            </span>
                                            <div class="flex items-center gap-1">
                                                <button type="button" wire:click="moveItem('metric', {{ $metric->id }}, 'up')" class="p-1 text-slate-400 hover:text-slate-700" title="Move Up"><i data-lucide="arrow-up" class="w-3 h-3"></i></button>
                                                <button type="button" wire:click="moveItem('metric', {{ $metric->id }}, 'down')" class="p-1 text-slate-400 hover:text-slate-700" title="Move Down"><i data-lucide="arrow-down" class="w-3 h-3"></i></button>
                                                <button type="button" wire:click="toggleItemStatus('metric', {{ $metric->id }})" class="p-1 text-slate-500 hover:text-emerald-700" title="Toggle Status">
                                                    <i data-lucide="{{ $metric->is_enabled ? 'eye' : 'eye-off' }}" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <h4 class="text-2xl font-bold text-emerald-700 font-heading">{{ $metric->value }}{{ $metric->suffix }}</h4>
                                        <p class="text-xs font-bold text-slate-900 mt-1">{{ $metric->label }}</p>
                                        <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">{{ $metric->description }}</p>
                                    </div>
                                    <div class="mt-4 pt-3 border-t border-slate-200/60 flex items-center justify-end gap-2">
                                        <button type="button" wire:click="openItemModal('metric', {{ $metric->id }})" class="px-2.5 py-1 rounded-lg border border-slate-200 bg-white text-slate-700 text-xs font-semibold hover:bg-slate-50 flex items-center gap-1">
                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                            <span>Edit</span>
                                        </button>
                                        <button type="button" wire:click="confirmDeleteItem('metric', {{ $metric->id }}, '{{ $metric->label }}')" class="px-2.5 py-1 rounded-lg border border-red-200 bg-red-50 text-red-700 text-xs font-semibold hover:bg-red-100 flex items-center gap-1">
                                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            <span>Delete</span>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full py-8 text-center text-slate-400 text-xs">
                                    <i data-lucide="bar-chart-2" class="w-8 h-8 mx-auto text-slate-300 mb-2"></i>
                                    <p class="font-semibold text-slate-600">No items have been added yet.</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Click below to create your first impact statistic card.</p>
                                    <button type="button" wire:click="openItemModal('metric')" class="mt-3 px-3.5 py-1.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold">
                                        + Add Impact Statistic
                                    </button>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif

            <!-- 3. ABOUT SECTION CMS -->
            @if ($activeSectionKey === 'about')
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 mb-6 border-b border-slate-100 gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">SECTION 3 • ABOUT</span>
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full {{ !$currentSec?->is_enabled ? 'bg-slate-100 text-slate-600 border border-slate-200' : ($currentSec?->is_published ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                    ● {{ !$currentSec?->is_enabled ? 'Section Disabled' : ($currentSec?->is_published ? 'Published' : 'Draft Saved') }}
                                </span>
                                <span wire:dirty wire:target="aboutData" class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200 animate-pulse">
                                    ● Unsaved Changes
                                </span>
                            </div>
                            <h2 class="text-base font-bold text-slate-900 font-heading mt-1">About Section</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Edit the mission statement, narrative paragraphs, photo, and 3 core pillar cards displayed in the public About section.</p>
                            <p class="text-[11px] text-slate-400 mt-1">Last saved: {{ $currentSec?->updated_at?->format('d M Y, h:i A') ?? 'N/A' }} | Last published: {{ $currentSec?->published_at?->format('d M Y, h:i A') ?? 'Not published yet' }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" wire:click="saveAbout(false)" class="px-3.5 py-2 rounded-xl border border-slate-200 text-slate-700 text-xs font-semibold hover:bg-slate-50">
                                Save Draft
                            </button>
                            <button type="button" wire:click="saveAbout(true)" class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs">
                                Publish Section
                            </button>
                        </div>
                    </div>

                    <form wire:submit.prevent="saveAbout(true)" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Section Badge</label>
                                <p class="text-[10px] text-slate-400 mb-1">Displayed above About section heading e.g. WHO WE ARE.</p>
                                <input type="text" wire:model="aboutData.badge" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Main Heading <span class="text-red-500">*</span></label>
                                <p class="text-[10px] text-slate-400 mb-1">Primary headline for the About Us section.</p>
                                <input type="text" wire:model="aboutData.heading" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                                @error('aboutData.heading') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Paragraph 1 (Main Narrative) <span class="text-red-500">*</span></label>
                            <p class="text-[10px] text-slate-400 mb-1">Main narrative paragraph displayed on public site.</p>
                            <textarea rows="2" wire:model="aboutData.p1" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900"></textarea>
                            @error('aboutData.p1') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Paragraph 2 (Approach & Values) <span class="text-red-500">*</span></label>
                            <p class="text-[10px] text-slate-400 mb-1">Supporting paragraph detailing mission and community-first values.</p>
                            <textarea rows="2" wire:model="aboutData.p2" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900"></textarea>
                            @error('aboutData.p2') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">About Photo</label>
                                
                                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-3">
                                    <div class="flex items-center gap-3">
                                        @if (!empty($aboutData['image']))
                                            <div class="relative group w-24 h-16 rounded-xl overflow-hidden border border-slate-200 shadow-2xs shrink-0 bg-slate-100">
                                                <img src="{{ $aboutData['image'] }}" alt="About Preview" class="w-full h-full object-cover">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-xs font-bold text-slate-800 truncate">Current About Photo</div>
                                                <div class="text-[10px] text-slate-400 truncate">{{ $aboutData['image'] }}</div>
                                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                                    <label class="px-2.5 py-1 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-[11px] font-semibold shadow-2xs flex items-center gap-1 cursor-pointer transition-all">
                                                        <i data-lucide="upload" class="w-3 h-3"></i>
                                                        <span>Upload from Device</span>
                                                        <input type="file" wire:model="aboutImageFile" accept="image/*" class="sr-only">
                                                    </label>
                                                    <button type="button" wire:click="openMediaPicker('aboutData.image')" class="px-2.5 py-1 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-[11px] font-semibold shadow-2xs flex items-center gap-1 transition-all">
                                                        <i data-lucide="image" class="w-3 h-3 text-emerald-600"></i>
                                                        <span>Media Library</span>
                                                    </button>
                                                    <button type="button" wire:click="removeImage('aboutData.image')" class="px-2.5 py-1 rounded-lg border border-red-200 bg-red-50 hover:bg-red-100 text-red-700 text-[11px] font-semibold transition-all">
                                                        Remove
                                                    </button>
                                                </div>
                                                <div wire:loading wire:target="aboutImageFile" class="mt-1 text-[10px] text-emerald-700 font-semibold flex items-center gap-1">
                                                    <i data-lucide="loader-2" class="w-3 h-3 animate-spin"></i>
                                                    <span>Uploading image...</span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="w-24 h-16 rounded-xl border border-dashed border-slate-300 flex items-center justify-center bg-white text-slate-400 shrink-0">
                                                <i data-lucide="image" class="w-6 h-6"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="text-xs font-semibold text-slate-700">No Image Selected</div>
                                                <div class="text-[10px] text-slate-400">Upload a photo from your computer or choose from Media Library.</div>
                                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                                    <label class="px-3 py-1.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-2xs flex items-center gap-1.5 cursor-pointer transition-all">
                                                        <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                                                        <span>Upload from Device</span>
                                                        <input type="file" wire:model="aboutImageFile" accept="image/*" class="sr-only">
                                                    </label>
                                                    <button type="button" wire:click="openMediaPicker('aboutData.image')" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-2xs flex items-center gap-1.5 transition-all">
                                                        <i data-lucide="image" class="w-3.5 h-3.5 text-emerald-600"></i>
                                                        <span>Media Library</span>
                                                    </button>
                                                </div>
                                                <div wire:loading wire:target="aboutImageFile" class="mt-1 text-[10px] text-emerald-700 font-semibold flex items-center gap-1">
                                                    <i data-lucide="loader-2" class="w-3 h-3 animate-spin"></i>
                                                    <span>Uploading image...</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- URL input (prefilled / fallback) -->
                                    <div class="pt-2 border-t border-slate-200/60">
                                        <div class="flex items-center justify-between text-[10px] text-slate-500 mb-1">
                                            <span>Image URL Reference:</span>
                                            <span class="text-slate-400 font-mono text-[9px]">(Auto-filled from Media Library)</span>
                                        </div>
                                        <input type="text" wire:model="aboutData.image" placeholder="https://... or choose from Media Library" class="block w-full rounded-xl px-3 py-1.5 text-[11px] bg-white border border-slate-200 focus:border-emerald-600 text-slate-700">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Image Alt Text</label>
                                <p class="text-[10px] text-slate-400 mb-1">Accessibility text describing the photo for screen readers and SEO.</p>
                                <input type="text" wire:model="aboutData.image_alt" placeholder="e.g. Classroom education and village student mentorship program in Tamil Nadu" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                        </div>

                        <!-- 3 Core Pillars -->
                        <div class="pt-2">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-3">Core Pillars (3 Highlighted Values)</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase">Pillar 1 Title</label>
                                    <input type="text" wire:model="aboutData.pillar1_title" class="block w-full rounded-lg px-2.5 py-1.5 text-xs bg-white border border-slate-200">
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase">Pillar 1 Description</label>
                                    <textarea rows="2" wire:model="aboutData.pillar1_desc" class="block w-full rounded-lg px-2.5 py-1.5 text-xs bg-white border border-slate-200"></textarea>
                                </div>
                                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase">Pillar 2 Title</label>
                                    <input type="text" wire:model="aboutData.pillar2_title" class="block w-full rounded-lg px-2.5 py-1.5 text-xs bg-white border border-slate-200">
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase">Pillar 2 Description</label>
                                    <textarea rows="2" wire:model="aboutData.pillar2_desc" class="block w-full rounded-lg px-2.5 py-1.5 text-xs bg-white border border-slate-200"></textarea>
                                </div>
                                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase">Pillar 3 Title</label>
                                    <input type="text" wire:model="aboutData.pillar3_title" class="block w-full rounded-lg px-2.5 py-1.5 text-xs bg-white border border-slate-200">
                                    <label class="block text-[11px] font-bold text-slate-700 uppercase">Pillar 3 Description</label>
                                    <textarea rows="2" wire:model="aboutData.pillar3_desc" class="block w-full rounded-lg px-2.5 py-1.5 text-xs bg-white border border-slate-200"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
                            <button type="button" wire:click="saveAbout(false)" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-xs font-semibold hover:bg-slate-50">
                                Save Draft
                            </button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-md shadow-emerald-800/20">
                                Save & Publish Section
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- 4. PROGRAMS / WHAT WE DO CMS -->
            @if ($activeSectionKey === 'programs')
                <div class="space-y-6">
                    <!-- Section Header Editor -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 mb-4 border-b border-slate-100 gap-2">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">SECTION 4 • PROGRAMS</span>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full {{ !$currentSec?->is_enabled ? 'bg-slate-100 text-slate-600 border border-slate-200' : ($currentSec?->is_published ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                        ● {{ !$currentSec?->is_enabled ? 'Section Disabled' : ($currentSec?->is_published ? 'Published' : 'Draft Saved') }}
                                    </span>
                                    <span wire:dirty wire:target="programsHeader" class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200 animate-pulse">
                                        ● Unsaved Changes
                                    </span>
                                </div>
                                <h2 class="text-base font-bold text-slate-900 font-heading mt-1">Programs Section</h2>
                                <p class="text-xs text-slate-500 mt-0.5">Edit the program cards, categories, impact tags, and headline displayed in the public What We Do section.</p>
                                <p class="text-[11px] text-slate-400 mt-1">Last saved: {{ $currentSec?->updated_at?->format('d M Y, h:i A') ?? 'N/A' }} | Last published: {{ $currentSec?->published_at?->format('d M Y, h:i A') ?? 'Not published yet' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" wire:click="saveProgramsHeader(false)" class="px-3.5 py-2 rounded-xl border border-slate-200 text-slate-700 text-xs font-semibold hover:bg-slate-50">
                                    Save Draft
                                </button>
                                <button type="button" wire:click="saveProgramsHeader(true)" class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs">
                                    Publish Section
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Section Badge</label>
                                <p class="text-[10px] text-slate-400 mb-1">Displayed above Programs heading e.g. WHAT WE DO.</p>
                                <input type="text" wire:model="programsHeader.badge" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Section Heading</label>
                                <p class="text-[10px] text-slate-400 mb-1">Primary headline for program initiatives.</p>
                                <input type="text" wire:model="programsHeader.heading" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                        </div>
                    </div>

                    <!-- Programs List -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                        <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 font-heading">Active Programs & Initiatives ({{ $activities->count() }})</h3>
                                <p class="text-xs text-slate-500">Edit individual program cards, impact metrics, reorder, or add new initiatives.</p>
                            </div>
                            <button type="button" wire:click="openItemModal('activity')" class="px-3.5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs flex items-center gap-1.5">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                <span>+ Add Program</span>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @forelse ($activities as $act)
                                <div class="p-4 rounded-xl border {{ $act->is_active ? 'border-slate-200 bg-slate-50/60' : 'border-slate-200/60 bg-slate-100/60 opacity-70' }} flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center gap-1.5">
                                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">{{ $act->color }}</span>
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full {{ $act->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-200 text-slate-600' }}">
                                                    ● {{ $act->is_active ? 'Active' : 'Hidden' }}
                                                </span>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <button type="button" wire:click="moveItem('activity', {{ $act->id }}, 'up')" class="p-1 text-slate-400 hover:text-slate-700" title="Move Up"><i data-lucide="arrow-up" class="w-3 h-3"></i></button>
                                                <button type="button" wire:click="moveItem('activity', {{ $act->id }}, 'down')" class="p-1 text-slate-400 hover:text-slate-700" title="Move Down"><i data-lucide="arrow-down" class="w-3 h-3"></i></button>
                                                <button type="button" wire:click="toggleItemStatus('activity', {{ $act->id }})" class="p-1 text-slate-500 hover:text-emerald-700" title="Toggle Status">
                                                    <i data-lucide="{{ $act->is_active ? 'eye' : 'eye-off' }}" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <h4 class="text-sm font-bold text-slate-900 mt-1">{{ $act->title }}</h4>
                                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">{{ $act->description }}</p>
                                    </div>
                                    <div class="mt-4 pt-3 border-t border-slate-200/60 flex items-center justify-between">
                                        <span class="text-[11px] font-semibold text-emerald-700">{{ $act->impact_tag }}</span>
                                        <div class="flex items-center gap-2">
                                            <button type="button" wire:click="openItemModal('activity', {{ $act->id }})" class="px-2.5 py-1 rounded-lg border border-slate-200 bg-white text-slate-700 text-xs font-semibold hover:bg-slate-50 flex items-center gap-1">
                                                <i data-lucide="edit-3" class="w-3 h-3"></i>
                                                <span>Edit</span>
                                            </button>
                                            <button type="button" wire:click="confirmDeleteItem('activity', {{ $act->id }}, '{{ $act->title }}')" class="px-2.5 py-1 rounded-lg border border-red-200 bg-red-50 text-red-700 text-xs font-semibold hover:bg-red-100 flex items-center gap-1">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                                <span>Delete</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full py-8 text-center text-slate-400 text-xs">
                                    <i data-lucide="layers" class="w-8 h-8 mx-auto text-slate-300 mb-2"></i>
                                    <p class="font-semibold text-slate-600">No items have been added yet.</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Click below to add your first program card.</p>
                                    <button type="button" wire:click="openItemModal('activity')" class="mt-3 px-3.5 py-1.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold">
                                        + Add Program
                                    </button>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif

            <!-- 5. COMMUNITIES CMS -->
            @if ($activeSectionKey === 'communities')
                <div class="space-y-6">
                    <!-- Section Header Editor -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 mb-4 border-b border-slate-100 gap-2">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">SECTION 5 • COMMUNITIES</span>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full {{ !$currentSec?->is_enabled ? 'bg-slate-100 text-slate-600 border border-slate-200' : ($currentSec?->is_published ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                        ● {{ !$currentSec?->is_enabled ? 'Section Disabled' : ($currentSec?->is_published ? 'Published' : 'Draft Saved') }}
                                    </span>
                                    <span wire:dirty wire:target="communitiesHeader" class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200 animate-pulse">
                                        ● Unsaved Changes
                                    </span>
                                </div>
                                <h2 class="text-base font-bold text-slate-900 font-heading mt-1">Communities Section</h2>
                                <p class="text-xs text-slate-500 mt-0.5">Edit the regional community focal points, taluks, volunteer strength, and headline displayed in the public Communities section.</p>
                                <p class="text-[11px] text-slate-400 mt-1">Last saved: {{ $currentSec?->updated_at?->format('d M Y, h:i A') ?? 'N/A' }} | Last published: {{ $currentSec?->published_at?->format('d M Y, h:i A') ?? 'Not published yet' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" wire:click="saveCommunitiesHeader(false)" class="px-3.5 py-2 rounded-xl border border-slate-200 text-slate-700 text-xs font-semibold hover:bg-slate-50">
                                    Save Draft
                                </button>
                                <button type="button" wire:click="saveCommunitiesHeader(true)" class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs">
                                    Publish Section
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Section Badge</label>
                                <p class="text-[10px] text-slate-400 mb-1">Displayed above Communities heading e.g. WHERE WE WORK.</p>
                                <input type="text" wire:model="communitiesHeader.badge" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Section Heading</label>
                                <p class="text-[10px] text-slate-400 mb-1">Primary headline for regional focal points.</p>
                                <input type="text" wire:model="communitiesHeader.heading" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                        </div>
                    </div>

                    <!-- Communities List -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                        <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 font-heading">Regional Community Focal Points ({{ $communities->count() }})</h3>
                                <p class="text-xs text-slate-500">Manage districts, taluks, volunteer strength, and community descriptions.</p>
                            </div>
                            <button type="button" wire:click="openItemModal('community')" class="px-3.5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs flex items-center gap-1.5">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                <span>+ Add Community</span>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @forelse ($communities as $com)
                                <div class="p-4 rounded-xl border {{ $com->is_enabled ? 'border-slate-200 bg-slate-50/60' : 'border-slate-200/60 bg-slate-100/60 opacity-70' }} flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full {{ $com->is_enabled ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-200 text-slate-600' }}">
                                                ● {{ $com->is_enabled ? 'Active' : 'Hidden' }}
                                            </span>
                                            <div class="flex items-center gap-1">
                                                <button type="button" wire:click="moveItem('community', {{ $com->id }}, 'up')" class="p-1 text-slate-400 hover:text-slate-700" title="Move Up"><i data-lucide="arrow-up" class="w-3 h-3"></i></button>
                                                <button type="button" wire:click="moveItem('community', {{ $com->id }}, 'down')" class="p-1 text-slate-400 hover:text-slate-700" title="Move Down"><i data-lucide="arrow-down" class="w-3 h-3"></i></button>
                                                <button type="button" wire:click="toggleItemStatus('community', {{ $com->id }})" class="p-1 text-slate-500 hover:text-emerald-700" title="Toggle Status">
                                                    <i data-lucide="{{ $com->is_enabled ? 'eye' : 'eye-off' }}" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <h4 class="text-sm font-bold text-slate-900">{{ $com->name }}</h4>
                                        <p class="text-[11px] text-emerald-700 font-semibold mt-0.5">{{ $com->area }}</p>
                                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">{{ $com->description }}</p>
                                    </div>
                                    <div class="mt-4 pt-3 border-t border-slate-200/60 flex items-center justify-between text-xs">
                                        <div class="text-[11px] text-slate-500">
                                            <span>{{ $com->active_volunteers }}</span> • <span>{{ $com->active_programs }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <button type="button" wire:click="openItemModal('community', {{ $com->id }})" class="px-2.5 py-1 rounded-lg border border-slate-200 bg-white text-slate-700 text-xs font-semibold hover:bg-slate-50 flex items-center gap-1">
                                                <i data-lucide="edit-3" class="w-3 h-3"></i>
                                                <span>Edit</span>
                                            </button>
                                            <button type="button" wire:click="confirmDeleteItem('community', {{ $com->id }}, '{{ $com->name }}')" class="px-2.5 py-1 rounded-lg border border-red-200 bg-red-50 text-red-700 text-xs font-semibold hover:bg-red-100 flex items-center gap-1">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                                <span>Delete</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full py-8 text-center text-slate-400 text-xs">
                                    <i data-lucide="map-pin" class="w-8 h-8 mx-auto text-slate-300 mb-2"></i>
                                    <p class="font-semibold text-slate-600">No items have been added yet.</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Click below to add a regional community focal point.</p>
                                    <button type="button" wire:click="openItemModal('community')" class="mt-3 px-3.5 py-1.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold">
                                        + Add Community
                                    </button>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif

            <!-- 6. FEATURED CAMPAIGN CMS -->
            @if ($activeSectionKey === 'campaign')
                <div class="space-y-6">
                    <!-- Section Header Editor -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 mb-4 border-b border-slate-100 gap-2">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">SECTION 6 • CAMPAIGN</span>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full {{ !$currentSec?->is_enabled ? 'bg-slate-100 text-slate-600 border border-slate-200' : ($currentSec?->is_published ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                        ● {{ !$currentSec?->is_enabled ? 'Section Disabled' : ($currentSec?->is_published ? 'Published' : 'Draft Saved') }}
                                    </span>
                                    <span wire:dirty wire:target="campaignHeader" class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200 animate-pulse">
                                        ● Unsaved Changes
                                    </span>
                                </div>
                                <h2 class="text-base font-bold text-slate-900 font-heading mt-1">Featured Campaign Section</h2>
                                <p class="text-xs text-slate-500 mt-0.5">Edit the featured fundraising drive, financial target goals, campaign dates, and headline displayed in the public Campaign section.</p>
                                <p class="text-[11px] text-slate-400 mt-1">Last saved: {{ $currentSec?->updated_at?->format('d M Y, h:i A') ?? 'N/A' }} | Last published: {{ $currentSec?->published_at?->format('d M Y, h:i A') ?? 'Not published yet' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" wire:click="saveCampaignHeader(false)" class="px-3.5 py-2 rounded-xl border border-slate-200 text-slate-700 text-xs font-semibold hover:bg-slate-50">
                                    Save Draft
                                </button>
                                <button type="button" wire:click="saveCampaignHeader(true)" class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs">
                                    Publish Section
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Section Badge</label>
                                <p class="text-[10px] text-slate-400 mb-1">Displayed above Featured Campaign heading e.g. FEATURED CAMPAIGN.</p>
                                <input type="text" wire:model="campaignHeader.badge" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Section Heading</label>
                                <p class="text-[10px] text-slate-400 mb-1">Primary headline for donation drive.</p>
                                <input type="text" wire:model="campaignHeader.heading" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                        </div>
                    </div>

                    <!-- Campaigns List -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                        <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 font-heading">Campaigns & Mission Drives ({{ $campaigns->count() }})</h3>
                                <p class="text-xs text-slate-500">Manage fundraising campaigns, target goals, and featured public status.</p>
                            </div>
                            <button type="button" wire:click="openItemModal('campaign')" class="px-3.5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs flex items-center gap-1.5">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                <span>+ Add Campaign</span>
                            </button>
                        </div>

                        <div class="space-y-4">
                            @forelse ($campaigns as $camp)
                                <div class="p-5 rounded-xl border {{ $camp->is_active ? 'border-slate-200 bg-slate-50/60' : 'border-slate-200/60 bg-slate-100/60 opacity-70' }} flex flex-col md:flex-row gap-5 items-start justify-between">
                                    <div class="flex flex-col sm:flex-row gap-4 items-start flex-1">
                                        <!-- Campaign Image -->
                                        <div class="relative w-full sm:w-40 h-28 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                            @if ($camp->resolved_image_url)
                                                <img src="{{ $camp->resolved_image_url }}" alt="{{ $camp->image_alt ?: $camp->title }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-400">
                                                    <i data-lucide="image" class="w-6 h-6"></i>
                                                    <span class="text-[10px] mt-1">No image</span>
                                                </div>
                                            @endif
                                            @if ($camp->is_featured)
                                                <span class="absolute top-2 left-2 text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-emerald-700 text-white shadow-xs">
                                                    ★ Featured
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Campaign Info -->
                                        <div class="space-y-1.5 flex-1">
                                            <div class="flex items-center gap-2">
                                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">{{ $camp->badge }}</span>
                                                <button type="button" wire:click="toggleFeaturedCampaign({{ $camp->id }})" class="text-[10px] font-semibold px-2 py-0.5 rounded border transition-colors {{ $camp->is_featured ? 'bg-amber-50 text-amber-800 border-amber-300 font-bold' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
                                                    {{ $camp->is_featured ? '★ Main Featured' : '☆ Set as Featured' }}
                                                </button>
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full {{ $camp->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-200 text-slate-600' }}">
                                                    ● {{ $camp->is_active ? 'Active' : 'Hidden' }}
                                                </span>
                                            </div>
                                            <h4 class="text-base font-bold text-slate-900 font-heading">{{ $camp->title }}</h4>
                                            <p class="text-xs text-slate-600 leading-relaxed line-clamp-2">{{ $camp->subtitle }}</p>

                                            <!-- Progress Stats -->
                                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-2 text-xs">
                                                <div class="bg-white p-2 rounded-lg border border-slate-200">
                                                    <span class="text-slate-400 block text-[9px] uppercase font-semibold">Raised</span>
                                                    <span class="font-bold text-emerald-700">₹{{ number_format($camp->raised_amount) }}</span>
                                                </div>
                                                <div class="bg-white p-2 rounded-lg border border-slate-200">
                                                    <span class="text-slate-400 block text-[9px] uppercase font-semibold">Goal</span>
                                                    <span class="font-bold text-slate-800">₹{{ number_format($camp->goal_amount) }}</span>
                                                </div>
                                                <div class="bg-white p-2 rounded-lg border border-slate-200">
                                                    <span class="text-slate-400 block text-[9px] uppercase font-semibold">Supporters</span>
                                                    <span class="font-bold text-slate-800">{{ $camp->supporters_count }}</span>
                                                </div>
                                                <div class="bg-white p-2 rounded-lg border border-slate-200">
                                                    <span class="text-slate-400 block text-[9px] uppercase font-semibold">Days Left</span>
                                                    <span class="font-bold text-amber-700">{{ $camp->days_left }} Days</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex md:flex-col items-center gap-2 shrink-0 self-end md:self-center">
                                        <button type="button" wire:click="toggleItemStatus('campaign', {{ $camp->id }})" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 text-xs font-semibold hover:bg-slate-50 flex items-center gap-1.5 w-full justify-center">
                                            <i data-lucide="{{ $camp->is_active ? 'eye-off' : 'eye' }}" class="w-3.5 h-3.5"></i>
                                            <span>{{ $camp->is_active ? 'Hide' : 'Enable' }}</span>
                                        </button>
                                        <button type="button" wire:click="openItemModal('campaign', {{ $camp->id }})" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 text-xs font-semibold hover:bg-slate-50 flex items-center gap-1.5 w-full justify-center">
                                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                            <span>Edit</span>
                                        </button>
                                        <button type="button" wire:click="confirmDeleteItem('campaign', {{ $camp->id }}, '{{ $camp->title }}')" class="px-3 py-1.5 rounded-lg border border-red-200 bg-red-50 text-red-700 text-xs font-semibold hover:bg-red-100 flex items-center gap-1.5 w-full justify-center">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            <span>Delete</span>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-slate-400 text-xs">
                                    <i data-lucide="heart" class="w-8 h-8 mx-auto text-slate-300 mb-2"></i>
                                    <p class="font-semibold text-slate-600">No items have been added yet.</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Click below to create a fundraising campaign drive.</p>
                                    <button type="button" wire:click="openItemModal('campaign')" class="mt-3 px-3.5 py-1.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold">
                                        + Add Campaign
                                    </button>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif

            <!-- 7. UPCOMING EVENTS CMS -->
            @if ($activeSectionKey === 'events')
                <div class="space-y-6">
                    <!-- Section Header Editor -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 mb-4 border-b border-slate-100 gap-2">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">SECTION 7 • EVENTS</span>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full {{ !$currentSec?->is_enabled ? 'bg-slate-100 text-slate-600 border border-slate-200' : ($currentSec?->is_published ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                        ● {{ !$currentSec?->is_enabled ? 'Section Disabled' : ($currentSec?->is_published ? 'Published' : 'Draft Saved') }}
                                    </span>
                                    <span wire:dirty wire:target="eventsHeader" class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200 animate-pulse">
                                        ● Unsaved Changes
                                    </span>
                                </div>
                                <h2 class="text-base font-bold text-slate-900 font-heading mt-1">Community Events Section</h2>
                                <p class="text-xs text-slate-500 mt-0.5">Edit upcoming community drives, medical camps, dates, venues, event imagery, and headline displayed in the public Events section.</p>
                                <p class="text-[11px] text-slate-400 mt-1">Last saved: {{ $currentSec?->updated_at?->format('d M Y, h:i A') ?? 'N/A' }} | Last published: {{ $currentSec?->published_at?->format('d M Y, h:i A') ?? 'Not published yet' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" wire:click="saveEventsHeader(false)" class="px-3.5 py-2 rounded-xl border border-slate-200 text-slate-700 text-xs font-semibold hover:bg-slate-50">
                                    Save Draft
                                </button>
                                <button type="button" wire:click="saveEventsHeader(true)" class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs">
                                    Publish Section
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Section Badge</label>
                                <p class="text-[10px] text-slate-400 mb-1">Displayed above Events heading e.g. GET INVOLVED LOCALLY.</p>
                                <input type="text" wire:model="eventsHeader.badge" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Section Heading</label>
                                <p class="text-[10px] text-slate-400 mb-1">Primary headline for upcoming community events.</p>
                                <input type="text" wire:model="eventsHeader.heading" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                        </div>
                    </div>

                    <!-- Events List -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                        <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 font-heading">Upcoming Community Action & Events ({{ $events->count() }})</h3>
                                <p class="text-xs text-slate-500">Schedule weekend camps, drives, orientation meetings, and student workshops.</p>
                            </div>
                            <button type="button" wire:click="openItemModal('event')" class="px-3.5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs flex items-center gap-1.5">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                <span>+ Add Event</span>
                            </button>
                        </div>

                        <div class="space-y-3">
                            @forelse ($events as $ev)
                                <div class="p-4 rounded-xl border {{ $ev->is_active ? 'border-slate-200 bg-slate-50/60' : 'border-slate-200/60 bg-slate-100/60 opacity-70' }} flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 text-xs">
                                    <div class="flex items-center gap-4 flex-1">
                                        <!-- Event Image Thumbnail with Date Overlay -->
                                        <div class="relative w-24 h-20 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                            @if ($ev->resolved_image_url)
                                                <img src="{{ $ev->resolved_image_url }}" alt="{{ $ev->title }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-slate-100">
                                                    <i data-lucide="calendar" class="w-5 h-5 text-slate-400"></i>
                                                    <span class="text-[9px] mt-0.5">No image</span>
                                                </div>
                                            @endif
                                            <div class="absolute bottom-1 left-1 bg-emerald-800/90 backdrop-blur-xs text-white rounded px-1.5 py-0.5 text-[9px] font-bold flex items-center gap-0.5">
                                                <span>{{ $ev->day }}</span>
                                                <span class="uppercase text-[8px] text-emerald-200">{{ $ev->month }}</span>
                                            </div>
                                        </div>

                                        <div class="space-y-0.5 flex-1">
                                            <div class="flex items-center gap-2">
                                                <span class="text-[10px] font-semibold text-emerald-700 uppercase">{{ $ev->category }} • {{ $ev->time_info }}</span>
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold px-1.5 py-0.2 rounded-full {{ $ev->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-200 text-slate-600' }}">
                                                    ● {{ $ev->is_active ? 'Active' : 'Hidden' }}
                                                </span>
                                            </div>
                                            <h4 class="text-xs font-bold text-slate-900 mt-0.5">{{ $ev->title }}</h4>
                                            <p class="text-[11px] text-slate-500">{{ $ev->location }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-end gap-1.5 shrink-0">
                                        <button type="button" wire:click="moveItem('event', {{ $ev->id }}, 'up')" class="p-1.5 text-slate-400 hover:text-slate-700" title="Move Up"><i data-lucide="arrow-up" class="w-3.5 h-3.5"></i></button>
                                        <button type="button" wire:click="moveItem('event', {{ $ev->id }}, 'down')" class="p-1.5 text-slate-400 hover:text-slate-700" title="Move Down"><i data-lucide="arrow-down" class="w-3.5 h-3.5"></i></button>
                                        <button type="button" wire:click="toggleItemStatus('event', {{ $ev->id }})" class="p-1.5 text-slate-500 hover:text-emerald-700" title="Toggle Status">
                                            <i data-lucide="{{ $ev->is_active ? 'eye' : 'eye-off' }}" class="w-3.5 h-3.5"></i>
                                        </button>
                                        <button type="button" wire:click="openItemModal('event', {{ $ev->id }})" class="px-2.5 py-1 rounded-lg border border-slate-200 bg-white text-slate-700 text-xs font-semibold hover:bg-slate-50 flex items-center gap-1">
                                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                            <span>Edit</span>
                                        </button>
                                        <button type="button" wire:click="confirmDeleteItem('event', {{ $ev->id }}, '{{ $ev->title }}')" class="px-2.5 py-1 rounded-lg border border-red-200 bg-red-50 text-red-700 text-xs font-semibold hover:bg-red-100 flex items-center gap-1">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            <span>Delete</span>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="py-8 text-center text-slate-400 text-xs">
                                    <i data-lucide="calendar" class="w-8 h-8 mx-auto text-slate-300 mb-2"></i>
                                    <p class="font-semibold text-slate-600">No items have been added yet.</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Click below to schedule your first upcoming event.</p>
                                    <button type="button" wire:click="openItemModal('event')" class="mt-3 px-3.5 py-1.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold">
                                        + Add Event
                                    </button>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif

            <!-- 8. LEADERSHIP CMS -->
            @if ($activeSectionKey === 'leadership')
                <div class="space-y-6">
                    <!-- Section Header Editor -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 mb-4 border-b border-slate-100 gap-2">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">SECTION 8 • LEADERSHIP</span>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full {{ !$currentSec?->is_enabled ? 'bg-slate-100 text-slate-600 border border-slate-200' : ($currentSec?->is_published ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                        ● {{ !$currentSec?->is_enabled ? 'Section Disabled' : ($currentSec?->is_published ? 'Published' : 'Draft Saved') }}
                                    </span>
                                    <span wire:dirty wire:target="leadershipHeader" class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200 animate-pulse">
                                        ● Unsaved Changes
                                    </span>
                                </div>
                                <h2 class="text-base font-bold text-slate-900 font-heading mt-1">Leadership Section</h2>
                                <p class="text-xs text-slate-500 mt-0.5">Edit trustee and coordinator profiles, designations, biographies, portraits, and headline displayed in the public Leadership section.</p>
                                <p class="text-[11px] text-slate-400 mt-1">Last saved: {{ $currentSec?->updated_at?->format('d M Y, h:i A') ?? 'N/A' }} | Last published: {{ $currentSec?->published_at?->format('d M Y, h:i A') ?? 'Not published yet' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" wire:click="saveLeadershipHeader(false)" class="px-3.5 py-2 rounded-xl border border-slate-200 text-slate-700 text-xs font-semibold hover:bg-slate-50">
                                    Save Draft
                                </button>
                                <button type="button" wire:click="saveLeadershipHeader(true)" class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs">
                                    Publish Section
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Section Badge</label>
                                <p class="text-[10px] text-slate-400 mb-1">Displayed above Leadership heading e.g. OUR TRUSTEES & COORDINATORS.</p>
                                <input type="text" wire:model="leadershipHeader.badge" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Section Heading</label>
                                <p class="text-[10px] text-slate-400 mb-1">Primary headline for Trustees & Leadership team.</p>
                                <input type="text" wire:model="leadershipHeader.heading" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                        </div>
                    </div>

                    <!-- Leaders List -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                        <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 font-heading">Trustees & Leadership Team ({{ $leaders->count() }})</h3>
                                <p class="text-xs text-slate-500">Manage names, designations, bios, and profile portraits.</p>
                            </div>
                            <button type="button" wire:click="openItemModal('leader')" class="px-3.5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs flex items-center gap-1.5">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                <span>+ Add Leader</span>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @forelse ($leaders as $leader)
                                <div class="p-4 rounded-xl border {{ $leader->is_active ? 'border-slate-200 bg-slate-50/60' : 'border-slate-200/60 bg-slate-100/60 opacity-70' }} flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full {{ $leader->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-200 text-slate-600' }}">
                                                ● {{ $leader->is_active ? 'Active' : 'Hidden' }}
                                            </span>
                                            <div class="flex items-center gap-1">
                                                <button type="button" wire:click="moveItem('leader', {{ $leader->id }}, 'up')" class="p-1 text-slate-400 hover:text-slate-700" title="Move Up"><i data-lucide="arrow-up" class="w-3 h-3"></i></button>
                                                <button type="button" wire:click="moveItem('leader', {{ $leader->id }}, 'down')" class="p-1 text-slate-400 hover:text-slate-700" title="Move Down"><i data-lucide="arrow-down" class="w-3 h-3"></i></button>
                                                <button type="button" wire:click="toggleItemStatus('leader', {{ $leader->id }})" class="p-1 text-slate-500 hover:text-emerald-700" title="Toggle Status">
                                                    <i data-lucide="{{ $leader->is_active ? 'eye' : 'eye-off' }}" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $leader->resolved_image_url }}" alt="{{ $leader->name }}" class="w-12 h-12 rounded-full object-cover ring-2 ring-emerald-200 shrink-0">
                                            <div>
                                                <h4 class="text-xs font-bold text-slate-900">{{ $leader->name }}</h4>
                                                <p class="text-[11px] text-emerald-700 font-semibold">{{ $leader->role }}</p>
                                                <p class="text-[11px] text-slate-500 mt-1 line-clamp-2 leading-relaxed">{{ $leader->bio }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4 pt-3 border-t border-slate-200/60 flex items-center justify-end gap-2">
                                        <button type="button" wire:click="openItemModal('leader', {{ $leader->id }})" class="px-2.5 py-1 rounded-lg border border-slate-200 bg-white text-slate-700 text-xs font-semibold hover:bg-slate-50 flex items-center gap-1">
                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                            <span>Edit</span>
                                        </button>
                                        <button type="button" wire:click="confirmDeleteItem('leader', {{ $leader->id }}, '{{ $leader->name }}')" class="px-2.5 py-1 rounded-lg border border-red-200 bg-red-50 text-red-700 text-xs font-semibold hover:bg-red-100 flex items-center gap-1">
                                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            <span>Delete</span>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full py-8 text-center text-slate-400 text-xs">
                                    <i data-lucide="users" class="w-8 h-8 mx-auto text-slate-300 mb-2"></i>
                                    <p class="font-semibold text-slate-600">No items have been added yet.</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Click below to add a trustee or coordinator profile.</p>
                                    <button type="button" wire:click="openItemModal('leader')" class="mt-3 px-3.5 py-1.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold">
                                        + Add Leader
                                    </button>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif

            <!-- 9. SUCCESS STORIES CMS -->
            @if ($activeSectionKey === 'stories')
                <div class="space-y-6">
                    <!-- Section Header Editor -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 mb-4 border-b border-slate-100 gap-2">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">SECTION 9 • STORIES</span>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full {{ !$currentSec?->is_enabled ? 'bg-slate-100 text-slate-600 border border-slate-200' : ($currentSec?->is_published ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                        ● {{ !$currentSec?->is_enabled ? 'Section Disabled' : ($currentSec?->is_published ? 'Published' : 'Draft Saved') }}
                                    </span>
                                    <span wire:dirty wire:target="storiesHeader" class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200 animate-pulse">
                                        ● Unsaved Changes
                                    </span>
                                </div>
                                <h2 class="text-base font-bold text-slate-900 font-heading mt-1">Success Stories Section</h2>
                                <p class="text-xs text-slate-500 mt-0.5">Edit beneficiary transformation stories, testimonials, direct quotes, and headline displayed in the public Success Stories section.</p>
                                <p class="text-[11px] text-slate-400 mt-1">Last saved: {{ $currentSec?->updated_at?->format('d M Y, h:i A') ?? 'N/A' }} | Last published: {{ $currentSec?->published_at?->format('d M Y, h:i A') ?? 'Not published yet' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" wire:click="saveStoriesHeader(false)" class="px-3.5 py-2 rounded-xl border border-slate-200 text-slate-700 text-xs font-semibold hover:bg-slate-50">
                                    Save Draft
                                </button>
                                <button type="button" wire:click="saveStoriesHeader(true)" class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs">
                                    Publish Section
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Section Badge</label>
                                <p class="text-[10px] text-slate-400 mb-1">Displayed above Stories heading e.g. COMMUNITY VOICES.</p>
                                <input type="text" wire:model="storiesHeader.badge" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Section Heading</label>
                                <p class="text-[10px] text-slate-400 mb-1">Primary headline for success stories and testimonials.</p>
                                <input type="text" wire:model="storiesHeader.heading" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                        </div>
                    </div>

                    <!-- Stories List -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                        <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 font-heading">Community Success Stories ({{ $stories->count() }})</h3>
                                <p class="text-xs text-slate-500">Real journeys, beneficiary quotes, and transformational narratives.</p>
                            </div>
                            <button type="button" wire:click="openItemModal('story')" class="px-3.5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs flex items-center gap-1.5">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                <span>+ Add Story</span>
                            </button>
                        </div>

                        <div class="space-y-4">
                            @forelse ($stories as $story)
                                <div class="p-4 rounded-xl border {{ $story->is_active ? 'border-slate-200 bg-slate-50/60' : 'border-slate-200/60 bg-slate-100/60 opacity-70' }} flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center gap-1.5">
                                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">{{ $story->category }}</span>
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full {{ $story->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-200 text-slate-600' }}">
                                                    ● {{ $story->is_active ? 'Active' : 'Hidden' }}
                                                </span>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <button type="button" wire:click="moveItem('story', {{ $story->id }}, 'up')" class="p-1 text-slate-400 hover:text-slate-700" title="Move Up"><i data-lucide="arrow-up" class="w-3 h-3"></i></button>
                                                <button type="button" wire:click="moveItem('story', {{ $story->id }}, 'down')" class="p-1 text-slate-400 hover:text-slate-700" title="Move Down"><i data-lucide="arrow-down" class="w-3 h-3"></i></button>
                                                <button type="button" wire:click="toggleItemStatus('story', {{ $story->id }})" class="p-1 text-slate-500 hover:text-emerald-700" title="Toggle Status">
                                                    <i data-lucide="{{ $story->is_active ? 'eye' : 'eye-off' }}" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <h4 class="text-xs font-bold text-slate-900 mt-1">{{ $story->title }}</h4>
                                        <p class="text-[11px] text-emerald-700 font-semibold">{{ $story->author_info }}</p>
                                        <p class="text-xs text-slate-600 mt-1.5 italic bg-white/60 p-2.5 rounded-lg border border-slate-200/60 leading-relaxed">"{{ $story->quote }}"</p>
                                    </div>
                                    <div class="mt-4 pt-3 border-t border-slate-200/60 flex items-center justify-end gap-2">
                                        <button type="button" wire:click="openItemModal('story', {{ $story->id }})" class="px-2.5 py-1 rounded-lg border border-slate-200 bg-white text-slate-700 text-xs font-semibold hover:bg-slate-50 flex items-center gap-1">
                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                            <span>Edit</span>
                                        </button>
                                        <button type="button" wire:click="confirmDeleteItem('story', {{ $story->id }}, '{{ $story->title }}')" class="px-2.5 py-1 rounded-lg border border-red-200 bg-red-50 text-red-700 text-xs font-semibold hover:bg-red-100 flex items-center gap-1">
                                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            <span>Delete</span>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="py-8 text-center text-slate-400 text-xs">
                                    <i data-lucide="quote" class="w-8 h-8 mx-auto text-slate-300 mb-2"></i>
                                    <p class="font-semibold text-slate-600">No items have been added yet.</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Click below to publish your first success story.</p>
                                    <button type="button" wire:click="openItemModal('story')" class="mt-3 px-3.5 py-1.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold">
                                        + Add Story
                                    </button>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif

            <!-- 10. GALLERY CMS -->
            @if ($activeSectionKey === 'gallery')
                <div class="space-y-6">
                    <!-- Section Header Editor -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 mb-4 border-b border-slate-100 gap-2">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">SECTION 10 • GALLERY</span>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full {{ !$currentSec?->is_enabled ? 'bg-slate-100 text-slate-600 border border-slate-200' : ($currentSec?->is_published ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                        ● {{ !$currentSec?->is_enabled ? 'Section Disabled' : ($currentSec?->is_published ? 'Published' : 'Draft Saved') }}
                                    </span>
                                    <span wire:dirty wire:target="galleryHeader" class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200 animate-pulse">
                                        ● Unsaved Changes
                                    </span>
                                </div>
                                <h2 class="text-base font-bold text-slate-900 font-heading mt-1">Gallery Section</h2>
                                <p class="text-xs text-slate-500 mt-0.5">Edit high-resolution field photos, captions, locations, and headline displayed in the public Gallery section.</p>
                                <p class="text-[11px] text-slate-400 mt-1">Last saved: {{ $currentSec?->updated_at?->format('d M Y, h:i A') ?? 'N/A' }} | Last published: {{ $currentSec?->published_at?->format('d M Y, h:i A') ?? 'Not published yet' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" wire:click="saveGalleryHeader(false)" class="px-3.5 py-2 rounded-xl border border-slate-200 text-slate-700 text-xs font-semibold hover:bg-slate-50">
                                    Save Draft
                                </button>
                                <button type="button" wire:click="saveGalleryHeader(true)" class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs">
                                    Publish Section
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Section Badge</label>
                                <p class="text-[10px] text-slate-400 mb-1">Displayed above Gallery heading e.g. FIELD GLIMPSES.</p>
                                <input type="text" wire:model="galleryHeader.badge" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Section Heading</label>
                                <p class="text-[10px] text-slate-400 mb-1">Primary headline for field photo gallery.</p>
                                <input type="text" wire:model="galleryHeader.heading" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                        </div>
                    </div>

                    <!-- Gallery Photos Grid -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                        <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 font-heading">Field Photo Gallery ({{ $gallery->count() }})</h3>
                                <p class="text-xs text-slate-500">Curated high-resolution field photos displayed in homepage lightbox.</p>
                            </div>
                            <button type="button" wire:click="openItemModal('gallery')" class="px-3.5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs flex items-center gap-1.5">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                <span>+ Add Gallery Image</span>
                            </button>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            @forelse ($gallery as $item)
                                <div class="rounded-xl border {{ $item->is_active ? 'border-slate-200 bg-slate-50' : 'border-slate-200/60 bg-slate-100 opacity-70' }} overflow-hidden flex flex-col justify-between">
                                    <div class="relative">
                                        <img src="{{ $item->resolved_image_url }}" alt="{{ $item->title }}" class="w-full h-32 object-cover">
                                        <span class="absolute top-2 left-2 text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full {{ $item->is_active ? 'bg-emerald-600 text-white' : 'bg-slate-700 text-white' }}">
                                            {{ $item->is_active ? 'Active' : 'Hidden' }}
                                        </span>
                                    </div>
                                    <div class="p-3 text-xs flex flex-col justify-between flex-1">
                                        <div>
                                            <p class="font-bold text-slate-900 truncate">{{ $item->title }}</p>
                                            <p class="text-[10px] text-slate-500">{{ $item->location }} • {{ $item->category }}</p>
                                        </div>
                                        <div class="mt-3 pt-2 border-t border-slate-200/60 flex items-center justify-between">
                                            <div class="flex items-center gap-1">
                                                <button type="button" wire:click="moveItem('gallery', {{ $item->id }}, 'up')" class="p-1 text-slate-400 hover:text-slate-700" title="Move Up"><i data-lucide="arrow-up" class="w-3 h-3"></i></button>
                                                <button type="button" wire:click="moveItem('gallery', {{ $item->id }}, 'down')" class="p-1 text-slate-400 hover:text-slate-700" title="Move Down"><i data-lucide="arrow-down" class="w-3 h-3"></i></button>
                                                <button type="button" wire:click="toggleItemStatus('gallery', {{ $item->id }})" class="p-1 text-slate-500 hover:text-emerald-700" title="Toggle Status">
                                                    <i data-lucide="{{ $item->is_active ? 'eye' : 'eye-off' }}" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <button type="button" wire:click="openItemModal('gallery', {{ $item->id }})" class="p-1 text-slate-500 hover:text-emerald-700" title="Edit"><i data-lucide="edit-3" class="w-3.5 h-3.5"></i></button>
                                                <button type="button" wire:click="confirmDeleteItem('gallery', {{ $item->id }}, '{{ $item->title }}')" class="p-1 text-slate-400 hover:text-red-600" title="Delete"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full py-8 text-center text-slate-400 text-xs">
                                    <i data-lucide="image" class="w-8 h-8 mx-auto text-slate-300 mb-2"></i>
                                    <p class="font-semibold text-slate-600">No items have been added yet.</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Click below to upload a photo to the field gallery.</p>
                                    <button type="button" wire:click="openItemModal('gallery')" class="mt-3 px-3.5 py-1.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold">
                                        + Add Gallery Image
                                    </button>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif

            <!-- 11. JOIN CTA CMS -->
            @if ($activeSectionKey === 'join_cta')
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 mb-6 border-b border-slate-100 gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">SECTION 11 • JOIN CTA</span>
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full {{ !$currentSec?->is_enabled ? 'bg-slate-100 text-slate-600 border border-slate-200' : ($currentSec?->is_published ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                    ● {{ !$currentSec?->is_enabled ? 'Section Disabled' : ($currentSec?->is_published ? 'Published' : 'Draft Saved') }}
                                </span>
                                <span wire:dirty wire:target="joinCtaData" class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200 animate-pulse">
                                    ● Unsaved Changes
                                </span>
                            </div>
                            <h2 class="text-base font-bold text-slate-900 font-heading mt-1">Join / Volunteer Section</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Edit the volunteer invitation headline, description, primary CTA, and secondary CTA displayed in the public Join Movement section.</p>
                            <p class="text-[11px] text-slate-400 mt-1">Last saved: {{ $currentSec?->updated_at?->format('d M Y, h:i A') ?? 'N/A' }} | Last published: {{ $currentSec?->published_at?->format('d M Y, h:i A') ?? 'Not published yet' }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" wire:click="saveJoinCta(false)" class="px-3.5 py-2 rounded-xl border border-slate-200 text-slate-700 text-xs font-semibold hover:bg-slate-50">
                                Save Draft
                            </button>
                            <button type="button" wire:click="saveJoinCta(true)" class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs">
                                Publish Section
                            </button>
                        </div>
                    </div>

                    <form wire:submit.prevent="saveJoinCta(true)" class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Banner Badge</label>
                            <p class="text-[10px] text-slate-400 mb-1">Displayed above Join CTA heading e.g. JOIN OUR MOVEMENT.</p>
                            <input type="text" wire:model="joinCtaData.badge" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Main Heading <span class="text-red-500">*</span></label>
                            <p class="text-[10px] text-slate-400 mb-1">Prominent volunteer invitation headline displayed on the public website.</p>
                            <input type="text" wire:model="joinCtaData.heading" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            @error('joinCtaData.heading') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Description <span class="text-red-500">*</span></label>
                            <p class="text-[10px] text-slate-400 mb-1">Supporting description encouraging community members to register or donate.</p>
                            <textarea rows="2" wire:model="joinCtaData.description" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900"></textarea>
                            @error('joinCtaData.description') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Primary Button Label</label>
                                <p class="text-[10px] text-slate-400 mb-1">Text for primary CTA button e.g. Register as Volunteer.</p>
                                <input type="text" wire:model="joinCtaData.primary_button_text" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Primary Button Link / Anchor</label>
                                <p class="text-[10px] text-slate-400 mb-1">Target URL or anchor e.g. #contact.</p>
                                <input type="text" wire:model="joinCtaData.primary_button_url" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Secondary Button Label</label>
                                <p class="text-[10px] text-slate-400 mb-1">Text for secondary CTA button e.g. Support Our Mission.</p>
                                <input type="text" wire:model="joinCtaData.secondary_button_text" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Secondary Button Link / Anchor</label>
                                <p class="text-[10px] text-slate-400 mb-1">Target URL or anchor e.g. #campaign.</p>
                                <input type="text" wire:model="joinCtaData.secondary_button_url" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
                            <button type="button" wire:click="saveJoinCta(false)" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-xs font-semibold hover:bg-slate-50">
                                Save Draft
                            </button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-md shadow-emerald-800/20">
                                Save & Publish Section
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- 12. CONTACT & FAQs CMS -->
            @if ($activeSectionKey === 'contact')
                <div class="space-y-6">
                    <!-- Central Source of Truth Notice for Contact Info -->
                    <div class="p-4 bg-emerald-50/90 border border-emerald-200/80 rounded-2xl flex items-start gap-3.5 text-xs text-emerald-950 shadow-2xs">
                        <i data-lucide="shield-check" class="w-5 h-5 text-emerald-700 shrink-0 mt-0.5"></i>
                        <div class="flex-1">
                            <div class="font-bold text-emerald-900 text-sm">Centralized Contact & Office Information</div>
                            <p class="text-xs text-emerald-800 mt-1 leading-relaxed">
                                The NGO's official phone numbers, email addresses, registered office address, and operating hours are centrally managed under 
                                <strong>Website CMS → General & SEO Settings</strong> to ensure a single, consistent source of truth across the public contact card, top bar, and footer.
                            </p>
                            <div class="mt-2.5">
                                <a href="{{ route('admin.website.settings') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-xs shadow-2xs transition-colors">
                                    <i data-lucide="settings-2" class="w-3.5 h-3.5"></i>
                                    <span>Edit Organization Contact Details in General Settings →</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Section Header Editor -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 mb-4 border-b border-slate-100 gap-2">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">SECTION 12 • CONTACT & FAQs</span>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full {{ !$currentSec?->is_enabled ? 'bg-slate-100 text-slate-600 border border-slate-200' : ($currentSec?->is_published ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                        ● {{ !$currentSec?->is_enabled ? 'Section Disabled' : ($currentSec?->is_published ? 'Published' : 'Draft Saved') }}
                                    </span>
                                    <span wire:dirty wire:target="contactData" class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200 animate-pulse">
                                        ● Unsaved Changes
                                    </span>
                                </div>
                                <h2 class="text-base font-bold text-slate-900 font-heading mt-1">Contact Section</h2>
                                <p class="text-xs text-slate-500 mt-0.5">Edit the contact section header, description, and FAQ accordion items displayed on the public website.</p>
                                <p class="text-[11px] text-slate-400 mt-1">Last saved: {{ $currentSec?->updated_at?->format('d M Y, h:i A') ?? 'N/A' }} | Last published: {{ $currentSec?->published_at?->format('d M Y, h:i A') ?? 'Not published yet' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" wire:click="saveContact(false)" class="px-3.5 py-2 rounded-xl border border-slate-200 text-slate-700 text-xs font-semibold hover:bg-slate-50">
                                    Save Draft
                                </button>
                                <button type="button" wire:click="saveContact(true)" class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs">
                                    Publish Section
                                </button>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 uppercase">Section Badge</label>
                                    <p class="text-[10px] text-slate-400 mb-1">Displayed above Contact heading e.g. GET IN TOUCH.</p>
                                    <input type="text" wire:model="contactData.badge" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 uppercase">Section Heading</label>
                                    <p class="text-[10px] text-slate-400 mb-1">Primary headline for contact section.</p>
                                    <input type="text" wire:model="contactData.heading" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Description</label>
                                <p class="text-[10px] text-slate-400 mb-1">Introductory description displayed above contact card and enquiry form.</p>
                                <textarea rows="2" wire:model="contactData.description" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- FAQs List -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                        <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 font-heading">Frequently Asked Questions ({{ $faqs->count() }})</h3>
                                <p class="text-xs text-slate-500">Accordion questions and verified answers on the public homepage.</p>
                            </div>
                            <button type="button" wire:click="openItemModal('faq')" class="px-3.5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs flex items-center gap-1.5">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                <span>+ Add FAQ</span>
                            </button>
                        </div>

                        <div class="space-y-3">
                            @forelse ($faqs as $faq)
                                <div class="p-4 rounded-xl border {{ $faq->is_active ? 'border-slate-200 bg-slate-50/60' : 'border-slate-200/60 bg-slate-100/60 opacity-70' }} flex items-start justify-between gap-4 text-xs">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full {{ $faq->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-200 text-slate-600' }}">
                                                ● {{ $faq->is_active ? 'Active' : 'Hidden' }}
                                            </span>
                                            <h4 class="font-bold text-slate-900">{{ $faq->question }}</h4>
                                        </div>
                                        <p class="text-slate-600 leading-relaxed pl-1">{{ $faq->answer }}</p>
                                    </div>
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <button type="button" wire:click="moveItem('faq', {{ $faq->id }}, 'up')" class="p-1.5 text-slate-400 hover:text-slate-700" title="Move Up"><i data-lucide="arrow-up" class="w-3.5 h-3.5"></i></button>
                                        <button type="button" wire:click="moveItem('faq', {{ $faq->id }}, 'down')" class="p-1.5 text-slate-400 hover:text-slate-700" title="Move Down"><i data-lucide="arrow-down" class="w-3.5 h-3.5"></i></button>
                                        <button type="button" wire:click="toggleItemStatus('faq', {{ $faq->id }})" class="p-1.5 text-slate-500 hover:text-emerald-700" title="Toggle Status">
                                            <i data-lucide="{{ $faq->is_active ? 'eye' : 'eye-off' }}" class="w-4 h-4"></i>
                                        </button>
                                        <button type="button" wire:click="openItemModal('faq', {{ $faq->id }})" class="px-2.5 py-1 rounded-lg border border-slate-200 bg-white text-slate-700 text-xs font-semibold hover:bg-slate-50 flex items-center gap-1">
                                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                            <span>Edit</span>
                                        </button>
                                        <button type="button" wire:click="confirmDeleteItem('faq', {{ $faq->id }}, '{{ $faq->question }}')" class="px-2.5 py-1 rounded-lg border border-red-200 bg-red-50 text-red-700 text-xs font-semibold hover:bg-red-100 flex items-center gap-1">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            <span>Delete</span>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="py-8 text-center text-slate-400 text-xs">
                                    <i data-lucide="help-circle" class="w-8 h-8 mx-auto text-slate-300 mb-2"></i>
                                    <p class="font-semibold text-slate-600">No items have been added yet.</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Click below to create an FAQ accordion item.</p>
                                    <button type="button" wire:click="openItemModal('faq')" class="mt-3 px-3.5 py-1.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold">
                                        + Add FAQ
                                    </button>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif

        </div>

    </div>

    <!-- Delete Confirmation Modal -->
    @if ($confirmingDelete)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-fadeIn">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100 animate-scaleUp">
                <div class="flex items-center gap-3 text-red-600 mb-3">
                    <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center shrink-0">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 font-heading">Delete {{ ucfirst($deleteTargetType) }}?</h3>
                        <p class="text-xs text-slate-500">This action cannot be undone.</p>
                    </div>
                </div>

                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-700 mb-5">
                    Are you sure you want to permanently delete <strong class="text-slate-900">"{{ $deleteTargetTitle }}"</strong> from MySQL?
                </div>

                <div class="flex items-center justify-end gap-3">
                    <button type="button" wire:click="cancelDelete" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                        Cancel
                    </button>
                    <button type="button" wire:click="performDelete" class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-semibold shadow-md shadow-red-600/20">
                        Confirm Delete
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Universal Modal for Sub-Entities (Metrics, Programs, Communities, Events, Leaders, Stories, Gallery, FAQs) -->
    @if ($showItemModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-fadeIn">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 max-h-[90vh] overflow-y-auto animate-scaleUp">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-900 font-heading">
                        {{ $editingItemId ? 'Edit ' . ucfirst($itemModalType) : 'Add ' . ucfirst($itemModalType) }}
                    </h3>
                    <button type="button" wire:click="closeItemModal" class="text-slate-400 hover:text-slate-600">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form wire:submit.prevent="saveItem" class="mt-4 space-y-4">
                    
                    @if ($itemModalType === 'metric')
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Metric Value (e.g. 10, 25, 500, 10,000) <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="itemFormData.value" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            @error('itemFormData.value') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Suffix Symbol (e.g. +, %, K)</label>
                            <input type="text" wire:model="itemFormData.suffix" placeholder="+" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Metric Title / Label (e.g. Years of Service) <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="itemFormData.label" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            @error('itemFormData.label') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Description</label>
                            <input type="text" wire:model="itemFormData.description" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Lucide Icon Key</label>
                            <input type="text" wire:model="itemFormData.icon" placeholder="heart-handshake, users, school, tree-pine" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                        </div>
                    @endif

                    @if ($itemModalType === 'activity')
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Program Title <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="itemFormData.title" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            @error('itemFormData.title') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Description <span class="text-red-500">*</span></label>
                            <textarea rows="3" wire:model="itemFormData.description" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900"></textarea>
                            @error('itemFormData.description') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Impact Metric Tag</label>
                                <input type="text" wire:model="itemFormData.impact_tag" placeholder="3,200+ Students Supported" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Color Badge</label>
                                <select wire:model="itemFormData.color" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                                    <option value="green">Green</option>
                                    <option value="teal">Teal</option>
                                    <option value="emerald">Emerald</option>
                                    <option value="amber">Amber</option>
                                    <option value="blue">Blue</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Program Image with Standardized Media Picker -->
                        <div class="space-y-2 pt-1">
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Program Cover Photo</label>
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                                <div class="flex items-center gap-3">
                                    @if (!empty($itemFormData['image_url']))
                                        <img src="{{ $itemFormData['image_url'] }}" alt="Cover" class="w-20 h-14 object-cover rounded-lg border border-slate-200 shadow-2xs shrink-0">
                                        <div class="flex-1 min-w-0">
                                            <div class="text-xs font-bold text-slate-800 truncate">Selected Photo</div>
                                            <div class="text-[10px] text-slate-400 truncate">{{ $itemFormData['image_url'] }}</div>
                                            <div class="mt-1.5 flex items-center gap-2">
                                                <button type="button" wire:click="openMediaPicker('itemFormData.image_url')" class="px-2 py-1 rounded bg-emerald-700 hover:bg-emerald-800 text-white text-[11px] font-semibold flex items-center gap-1">
                                                    <i data-lucide="image" class="w-3 h-3"></i>
                                                    <span>Choose from Media Library</span>
                                                </button>
                                                <button type="button" wire:click="removeImage('itemFormData.image_url')" class="px-2 py-1 rounded border border-red-200 bg-red-50 text-red-700 text-[11px] font-semibold hover:bg-red-100">
                                                    Remove Image
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="w-20 h-14 rounded-lg border border-dashed border-slate-300 flex items-center justify-center bg-white text-slate-400 shrink-0">
                                            <i data-lucide="image" class="w-5 h-5"></i>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-xs font-semibold text-slate-700">No Image Selected</div>
                                            <div class="text-[10px] text-slate-400">Select an asset from your Media Library.</div>
                                            <button type="button" wire:click="openMediaPicker('itemFormData.image_url')" class="mt-1.5 px-2.5 py-1 rounded bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold flex items-center gap-1">
                                                <i data-lucide="image" class="w-3 h-3"></i>
                                                <span>Choose from Media Library</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <div class="pt-2 border-t border-slate-200/60">
                                    <input type="text" wire:model="itemFormData.image_url" placeholder="https://... or choose from Media Library" class="block w-full rounded-lg px-2.5 py-1.5 text-[11px] bg-white border border-slate-200 text-slate-700">
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($itemModalType === 'community')
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Community / District Name <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="itemFormData.name" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            @error('itemFormData.name') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Area / Taluk Focus <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="itemFormData.area" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            @error('itemFormData.area') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Description <span class="text-red-500">*</span></label>
                            <textarea rows="3" wire:model="itemFormData.description" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900"></textarea>
                            @error('itemFormData.description') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Active Volunteers Tag</label>
                                <input type="text" wire:model="itemFormData.active_volunteers" placeholder="100+ Volunteers" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Active Programs Tag</label>
                                <input type="text" wire:model="itemFormData.active_programs" placeholder="4 Programs Active" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200">
                            </div>
                        </div>

                        <!-- Community Image with Standardized Media Picker -->
                        <div class="space-y-2 pt-1">
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Community Location Photo</label>
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                                <div class="flex items-center gap-3">
                                    @if (!empty($itemFormData['image_url']))
                                        <img src="{{ $itemFormData['image_url'] }}" alt="Community" class="w-20 h-14 object-cover rounded-lg border border-slate-200 shadow-2xs shrink-0">
                                        <div class="flex-1 min-w-0">
                                            <div class="text-xs font-bold text-slate-800 truncate">Selected Photo</div>
                                            <div class="text-[10px] text-slate-400 truncate">{{ $itemFormData['image_url'] }}</div>
                                            <div class="mt-1.5 flex items-center gap-2">
                                                <button type="button" wire:click="openMediaPicker('itemFormData.image_url')" class="px-2 py-1 rounded bg-emerald-700 hover:bg-emerald-800 text-white text-[11px] font-semibold flex items-center gap-1">
                                                    <i data-lucide="image" class="w-3 h-3"></i>
                                                    <span>Choose from Media Library</span>
                                                </button>
                                                <button type="button" wire:click="removeImage('itemFormData.image_url')" class="px-2 py-1 rounded border border-red-200 bg-red-50 text-red-700 text-[11px] font-semibold hover:bg-red-100">
                                                    Remove Image
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="w-20 h-14 rounded-lg border border-dashed border-slate-300 flex items-center justify-center bg-white text-slate-400 shrink-0">
                                            <i data-lucide="image" class="w-5 h-5"></i>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-xs font-semibold text-slate-700">No Image Selected</div>
                                            <div class="text-[10px] text-slate-400">Select an asset from your Media Library.</div>
                                            <button type="button" wire:click="openMediaPicker('itemFormData.image_url')" class="mt-1.5 px-2.5 py-1 rounded bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold flex items-center gap-1">
                                                <i data-lucide="image" class="w-3 h-3"></i>
                                                <span>Choose from Media Library</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <div class="pt-2 border-t border-slate-200/60">
                                    <input type="text" wire:model="itemFormData.image_url" placeholder="https://... or choose from Media Library" class="block w-full rounded-lg px-2.5 py-1.5 text-[11px] bg-white border border-slate-200 text-slate-700">
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($itemModalType === 'campaign')
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Campaign Title <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="itemFormData.title" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            @error('itemFormData.title') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Badge Label</label>
                            <input type="text" wire:model="itemFormData.badge" placeholder="FEATURED CAMPAIGN" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Subtitle Description</label>
                            <textarea rows="2" wire:model="itemFormData.subtitle" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Goal Amount (₹) <span class="text-red-500">*</span></label>
                                <input type="number" wire:model="itemFormData.goal_amount" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                                @error('itemFormData.goal_amount') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Raised Amount (₹)</label>
                                <input type="number" wire:model="itemFormData.raised_amount" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Start Date</label>
                                <input type="date" wire:model="itemFormData.start_date" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">End Date</label>
                                <input type="date" wire:model="itemFormData.end_date" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Supporters Count</label>
                                <input type="number" wire:model="itemFormData.supporters_count" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Days Left</label>
                                <input type="number" wire:model="itemFormData.days_left" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200">
                            </div>
                        </div>

                        <!-- Campaign Image with Standardized Media Picker -->
                        <div class="space-y-2 pt-1">
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Campaign Featured Image</label>
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                                <div class="flex items-center gap-3">
                                    @if (!empty($itemFormData['image_url']))
                                        <img src="{{ $itemFormData['image_url'] }}" alt="Campaign" class="w-20 h-14 object-cover rounded-lg border border-slate-200 shadow-2xs shrink-0">
                                        <div class="flex-1 min-w-0">
                                            <div class="text-xs font-bold text-slate-800 truncate">Selected Photo</div>
                                            <div class="text-[10px] text-slate-400 truncate">{{ $itemFormData['image_url'] }}</div>
                                            <div class="mt-1.5 flex items-center gap-2">
                                                <button type="button" wire:click="openMediaPicker('itemFormData.image_url')" class="px-2 py-1 rounded bg-emerald-700 hover:bg-emerald-800 text-white text-[11px] font-semibold flex items-center gap-1">
                                                    <i data-lucide="image" class="w-3 h-3"></i>
                                                    <span>Choose from Media Library</span>
                                                </button>
                                                <button type="button" wire:click="removeImage('itemFormData.image_url')" class="px-2 py-1 rounded border border-red-200 bg-red-50 text-red-700 text-[11px] font-semibold hover:bg-red-100">
                                                    Remove Image
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="w-20 h-14 rounded-lg border border-dashed border-slate-300 flex items-center justify-center bg-white text-slate-400 shrink-0">
                                            <i data-lucide="image" class="w-5 h-5"></i>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-xs font-semibold text-slate-700">No Image Selected</div>
                                            <div class="text-[10px] text-slate-400">Select an asset from your Media Library.</div>
                                            <button type="button" wire:click="openMediaPicker('itemFormData.image_url')" class="mt-1.5 px-2.5 py-1 rounded bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold flex items-center gap-1">
                                                <i data-lucide="image" class="w-3 h-3"></i>
                                                <span>Choose from Media Library</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-2 border-t border-slate-200/60">
                                    <input type="text" wire:model="itemFormData.image_url" placeholder="https://... or choose from Media Library" class="block w-full rounded-lg px-2.5 py-1.5 text-[11px] bg-white border border-slate-200 text-slate-700">
                                    <input type="text" wire:model="itemFormData.image_alt" placeholder="Image Alt Text (Accessibility & SEO)" class="block w-full rounded-lg px-2.5 py-1.5 text-[11px] bg-white border border-slate-200 text-slate-700">
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 pt-1">
                            <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                                <input type="checkbox" wire:model="itemFormData.is_featured" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                <span>Set as Featured Campaign on Public Homepage</span>
                            </label>
                        </div>
                    @endif

                    @if ($itemModalType === 'event')
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Event Title <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="itemFormData.title" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            @error('itemFormData.title') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Category <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="itemFormData.category" placeholder="Healthcare, Environment, Youth Workshop" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Time Info</label>
                                <input type="text" wire:model="itemFormData.time_info" placeholder="8:30 AM - 2:00 PM" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Date <span class="text-red-500">*</span></label>
                                <input type="date" wire:model="itemFormData.event_date" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                                @error('itemFormData.event_date') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Location Venue <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="itemFormData.location" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                                @error('itemFormData.location') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Description</label>
                            <textarea rows="2" wire:model="itemFormData.description" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900"></textarea>
                        </div>

                        <!-- Event Image with Standardized Media Picker -->
                        <div class="space-y-2 pt-1">
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Event Image</label>
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                                <div class="flex items-center gap-3">
                                    @if (!empty($itemFormData['image_url']))
                                        <img src="{{ $itemFormData['image_url'] }}" alt="Event" class="w-20 h-14 object-cover rounded-lg border border-slate-200 shadow-2xs shrink-0">
                                        <div class="flex-1 min-w-0">
                                            <div class="text-xs font-bold text-slate-800 truncate">Selected Event Image</div>
                                            <div class="text-[10px] text-slate-400 truncate">{{ $itemFormData['image_url'] }}</div>
                                            <div class="mt-1.5 flex items-center gap-2">
                                                <button type="button" wire:click="openMediaPicker('itemFormData.image_url')" class="px-2 py-1 rounded bg-emerald-700 hover:bg-emerald-800 text-white text-[11px] font-semibold flex items-center gap-1">
                                                    <i data-lucide="image" class="w-3 h-3"></i>
                                                    <span>Choose from Media Library</span>
                                                </button>
                                                <button type="button" wire:click="removeImage('itemFormData.image_url')" class="px-2 py-1 rounded border border-red-200 bg-red-50 text-red-700 text-[11px] font-semibold hover:bg-red-100">
                                                    Remove Image
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="w-20 h-14 rounded-lg border border-dashed border-slate-300 flex items-center justify-center bg-white text-slate-400 shrink-0">
                                            <i data-lucide="image" class="w-5 h-5"></i>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-xs font-semibold text-slate-700">No Image Selected</div>
                                            <div class="text-[10px] text-slate-400">Select an asset from your Media Library.</div>
                                            <button type="button" wire:click="openMediaPicker('itemFormData.image_url')" class="mt-1.5 px-2.5 py-1 rounded bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold flex items-center gap-1">
                                                <i data-lucide="image" class="w-3 h-3"></i>
                                                <span>Choose from Media Library</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-2 border-t border-slate-200/60">
                                    <input type="text" wire:model="itemFormData.image_url" placeholder="https://... or choose from Media Library" class="block w-full rounded-lg px-2.5 py-1.5 text-[11px] bg-white border border-slate-200 text-slate-700">
                                    <input type="text" wire:model="itemFormData.image_alt" placeholder="Image Alt Text (Accessibility & SEO)" class="block w-full rounded-lg px-2.5 py-1.5 text-[11px] bg-white border border-slate-200 text-slate-700">
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($itemModalType === 'leader')
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Full Name & Credentials <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="itemFormData.name" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            @error('itemFormData.name') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Role / Designation <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="itemFormData.role" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            @error('itemFormData.role') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Short Bio <span class="text-red-500">*</span></label>
                            <textarea rows="3" wire:model="itemFormData.bio" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900"></textarea>
                            @error('itemFormData.bio') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>

                        <!-- Leader Portrait with Standardized Media Picker -->
                        <div class="space-y-2 pt-1">
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Leader Portrait Photo</label>
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                                <div class="flex items-center gap-3">
                                    @if (!empty($itemFormData['image_url']))
                                        <img src="{{ $itemFormData['image_url'] }}" alt="Leader" class="w-14 h-14 rounded-full object-cover border border-slate-200 shadow-2xs shrink-0 ring-2 ring-emerald-200">
                                        <div class="flex-1 min-w-0">
                                            <div class="text-xs font-bold text-slate-800 truncate">Selected Portrait</div>
                                            <div class="text-[10px] text-slate-400 truncate">{{ $itemFormData['image_url'] }}</div>
                                            <div class="mt-1.5 flex items-center gap-2">
                                                <button type="button" wire:click="openMediaPicker('itemFormData.image_url')" class="px-2 py-1 rounded bg-emerald-700 hover:bg-emerald-800 text-white text-[11px] font-semibold flex items-center gap-1">
                                                    <i data-lucide="image" class="w-3 h-3"></i>
                                                    <span>Choose from Media Library</span>
                                                </button>
                                                <button type="button" wire:click="removeImage('itemFormData.image_url')" class="px-2 py-1 rounded border border-red-200 bg-red-50 text-red-700 text-[11px] font-semibold hover:bg-red-100">
                                                    Remove Image
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="w-14 h-14 rounded-full border border-dashed border-slate-300 flex items-center justify-center bg-white text-slate-400 shrink-0">
                                            <i data-lucide="user" class="w-5 h-5"></i>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-xs font-semibold text-slate-700">No Photo Selected</div>
                                            <div class="text-[10px] text-slate-400">Select a portrait from your Media Library.</div>
                                            <button type="button" wire:click="openMediaPicker('itemFormData.image_url')" class="mt-1.5 px-2.5 py-1 rounded bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold flex items-center gap-1">
                                                <i data-lucide="image" class="w-3 h-3"></i>
                                                <span>Choose from Media Library</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-2 border-t border-slate-200/60">
                                    <input type="text" wire:model="itemFormData.image_url" placeholder="https://... or choose from Media Library" class="block w-full rounded-lg px-2.5 py-1.5 text-[11px] bg-white border border-slate-200 text-slate-700">
                                    <input type="text" wire:model="itemFormData.image_alt" placeholder="Photo Alt Text (Accessibility & SEO)" class="block w-full rounded-lg px-2.5 py-1.5 text-[11px] bg-white border border-slate-200 text-slate-700">
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($itemModalType === 'story')
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Story Title <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="itemFormData.title" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            @error('itemFormData.title') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Beneficiary / Author Info <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="itemFormData.author_info" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            @error('itemFormData.author_info') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Direct Quote</label>
                            <textarea rows="2" wire:model="itemFormData.quote" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Full Story Excerpt <span class="text-red-500">*</span></label>
                            <textarea rows="3" wire:model="itemFormData.excerpt" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900"></textarea>
                            @error('itemFormData.excerpt') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>

                        <!-- Story Photo with Standardized Media Picker -->
                        <div class="space-y-2 pt-1">
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Story Beneficiary Photo</label>
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                                <div class="flex items-center gap-3">
                                    @if (!empty($itemFormData['image_url']))
                                        <img src="{{ $itemFormData['image_url'] }}" alt="Story" class="w-20 h-14 object-cover rounded-lg border border-slate-200 shadow-2xs shrink-0">
                                        <div class="flex-1 min-w-0">
                                            <div class="text-xs font-bold text-slate-800 truncate">Selected Photo</div>
                                            <div class="text-[10px] text-slate-400 truncate">{{ $itemFormData['image_url'] }}</div>
                                            <div class="mt-1.5 flex items-center gap-2">
                                                <button type="button" wire:click="openMediaPicker('itemFormData.image_url')" class="px-2 py-1 rounded bg-emerald-700 hover:bg-emerald-800 text-white text-[11px] font-semibold flex items-center gap-1">
                                                    <i data-lucide="image" class="w-3 h-3"></i>
                                                    <span>Choose from Media Library</span>
                                                </button>
                                                <button type="button" wire:click="removeImage('itemFormData.image_url')" class="px-2 py-1 rounded border border-red-200 bg-red-50 text-red-700 text-[11px] font-semibold hover:bg-red-100">
                                                    Remove Image
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="w-20 h-14 rounded-lg border border-dashed border-slate-300 flex items-center justify-center bg-white text-slate-400 shrink-0">
                                            <i data-lucide="image" class="w-5 h-5"></i>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-xs font-semibold text-slate-700">No Image Selected</div>
                                            <div class="text-[10px] text-slate-400">Select an asset from your Media Library.</div>
                                            <button type="button" wire:click="openMediaPicker('itemFormData.image_url')" class="mt-1.5 px-2.5 py-1 rounded bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold flex items-center gap-1">
                                                <i data-lucide="image" class="w-3 h-3"></i>
                                                <span>Choose from Media Library</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-2 border-t border-slate-200/60">
                                    <input type="text" wire:model="itemFormData.image_url" placeholder="https://... or choose from Media Library" class="block w-full rounded-lg px-2.5 py-1.5 text-[11px] bg-white border border-slate-200 text-slate-700">
                                    <input type="text" wire:model="itemFormData.image_alt" placeholder="Image Alt Text (Accessibility & SEO)" class="block w-full rounded-lg px-2.5 py-1.5 text-[11px] bg-white border border-slate-200 text-slate-700">
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($itemModalType === 'gallery')
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Photo Caption <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="itemFormData.title" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            @error('itemFormData.title') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Category</label>
                                <input type="text" wire:model="itemFormData.category" placeholder="Education, Environment, Youth" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Location</label>
                                <input type="text" wire:model="itemFormData.location" placeholder="Coimbatore, Tamil Nadu" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            </div>
                        </div>

                        <!-- Gallery Image with Standardized Media Picker -->
                        <div class="space-y-2 pt-1">
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Gallery Photo <span class="text-red-500">*</span></label>
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                                <div class="flex items-center gap-3">
                                    @if (!empty($itemFormData['image_url']))
                                        <img src="{{ $itemFormData['image_url'] }}" alt="Gallery" class="w-20 h-14 object-cover rounded-lg border border-slate-200 shadow-2xs shrink-0">
                                        <div class="flex-1 min-w-0">
                                            <div class="text-xs font-bold text-slate-800 truncate">Selected Photo</div>
                                            <div class="text-[10px] text-slate-400 truncate">{{ $itemFormData['image_url'] }}</div>
                                            <div class="mt-1.5 flex items-center gap-2">
                                                <button type="button" wire:click="openMediaPicker('itemFormData.image_url')" class="px-2 py-1 rounded bg-emerald-700 hover:bg-emerald-800 text-white text-[11px] font-semibold flex items-center gap-1">
                                                    <i data-lucide="image" class="w-3 h-3"></i>
                                                    <span>Choose from Media Library</span>
                                                </button>
                                                <button type="button" wire:click="removeImage('itemFormData.image_url')" class="px-2 py-1 rounded border border-red-200 bg-red-50 text-red-700 text-[11px] font-semibold hover:bg-red-100">
                                                    Remove Image
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="w-20 h-14 rounded-lg border border-dashed border-slate-300 flex items-center justify-center bg-white text-slate-400 shrink-0">
                                            <i data-lucide="image" class="w-5 h-5"></i>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-xs font-semibold text-slate-700">No Image Selected</div>
                                            <div class="text-[10px] text-slate-400">Select an asset from your Media Library.</div>
                                            <button type="button" wire:click="openMediaPicker('itemFormData.image_url')" class="mt-1.5 px-2.5 py-1 rounded bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold flex items-center gap-1">
                                                <i data-lucide="image" class="w-3 h-3"></i>
                                                <span>Choose from Media Library</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-2 border-t border-slate-200/60">
                                    <input type="text" wire:model="itemFormData.image_url" placeholder="https://... or choose from Media Library" class="block w-full rounded-lg px-2.5 py-1.5 text-[11px] bg-white border border-slate-200 text-slate-700">
                                    <input type="text" wire:model="itemFormData.image_alt" placeholder="Image Alt Text (Accessibility & SEO)" class="block w-full rounded-lg px-2.5 py-1.5 text-[11px] bg-white border border-slate-200 text-slate-700">
                                </div>
                                @error('itemFormData.image_url') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endif

                    @if ($itemModalType === 'faq')
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Question <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="itemFormData.question" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            @error('itemFormData.question') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Answer <span class="text-red-500">*</span></label>
                            <textarea rows="3" wire:model="itemFormData.answer" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900"></textarea>
                            @error('itemFormData.answer') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        <button type="button" wire:click="closeItemModal" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-md shadow-emerald-800/20">
                            Save to MySQL Database
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Media Library Picker Modal -->
    @if ($showMediaPickerModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-fadeIn">
            <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl border border-slate-100 max-h-[85vh] flex flex-col animate-scaleUp">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 font-heading">Choose Media Asset</h3>
                        <p class="text-xs text-slate-500">Click any image to select it for the active form field.</p>
                    </div>
                    <button type="button" wire:click="closeMediaPicker" class="text-slate-400 hover:text-slate-600">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <div class="mb-4">
                    <input type="text" wire:model.live.debounce.300ms="mediaSearch" placeholder="Search media by filename, title, alt text..." class="w-full px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600">
                </div>

                <div class="flex-1 overflow-y-auto grid grid-cols-3 sm:grid-cols-4 gap-3 p-1">
                    @forelse ($mediaList as $mediaItem)
                        <div wire:click="selectMediaItem('{{ $mediaItem->url }}', '{{ $mediaItem->alt_text }}')" class="group relative rounded-xl border border-slate-200 overflow-hidden bg-slate-50 cursor-pointer hover:ring-2 hover:ring-emerald-500 transition-all">
                            <img src="{{ $mediaItem->url }}" alt="{{ $mediaItem->alt_text }}" class="w-full h-24 object-cover">
                            <div class="p-1.5 text-[10px] text-slate-700 truncate bg-white border-t border-slate-100">
                                {{ $mediaItem->title ?: $mediaItem->original_name }}
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-8 text-center text-slate-400 text-xs">
                            No media assets found. Upload photos in the <a href="{{ route('admin.website.media') }}" target="_blank" class="text-emerald-700 underline font-semibold">Media Library</a>.
                        </div>
                    @endforelse
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex justify-end">
                    <button type="button" wire:click="closeMediaPicker" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
