<div>
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 font-heading">Website & Organization Settings</h1>
            <p class="text-xs text-slate-500 mt-1">Configure NGO identity, top announcement bar, branding & logos, contact details, navigation menus, social media links, and SEO parameters.</p>
        </div>
    </div>

    <!-- Flash Message -->
    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 text-xs text-emerald-800 animate-fadeIn">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Tabs Navigation -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs mb-6 p-1.5 flex flex-wrap gap-1 text-xs">
        <button type="button" 
                wire:click="$set('activeTab', 'general')" 
                class="px-4 py-2 rounded-xl font-semibold transition-all {{ $activeTab === 'general' ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50' }}">
            General Identity
        </button>
        <button type="button" 
                wire:click="$set('activeTab', 'top_bar')" 
                class="px-4 py-2 rounded-xl font-semibold transition-all {{ $activeTab === 'top_bar' ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50' }}">
            Top Bar & Announcement
        </button>
        <button type="button" 
                wire:click="$set('activeTab', 'branding')" 
                class="px-4 py-2 rounded-xl font-semibold transition-all {{ $activeTab === 'branding' ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50' }}">
            Branding & Logos
        </button>
        <button type="button" 
                wire:click="$set('activeTab', 'menus')" 
                class="px-4 py-2 rounded-xl font-semibold transition-all {{ $activeTab === 'menus' ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50' }}">
            Navigation Menus
        </button>
        <button type="button" 
                wire:click="$set('activeTab', 'social')" 
                class="px-4 py-2 rounded-xl font-semibold transition-all {{ $activeTab === 'social' ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50' }}">
            Social Media Links
        </button>
        <button type="button" 
                wire:click="$set('activeTab', 'seo')" 
                class="px-4 py-2 rounded-xl font-semibold transition-all {{ $activeTab === 'seo' ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50' }}">
            SEO & OpenGraph
        </button>
    </div>

    <!-- Tab 1: General Identity -->
    @if ($activeTab === 'general')
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
            <div class="pb-4 mb-6 border-b border-slate-100">
                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">CENTRALIZED NGO IDENTITY</span>
                <h3 class="text-base font-bold text-slate-900 font-heading mt-1">ORGANIZATION & CONTACT INFORMATION</h3>
                <p class="text-xs text-slate-500">Official legal and public contact information. These details serve as the single source of truth across header, footer, top bar, and contact sections.</p>
            </div>

            <form wire:submit.prevent="saveGeneral" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase">Organization Legal Name</label>
                        <p class="text-[10px] text-slate-400 mb-1">Full registered legal name of the NGO.</p>
                        <input type="text" wire:model="settings.site_name" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase">Display Short Name</label>
                        <p class="text-[10px] text-slate-400 mb-1">Abbreviated name used in compact headers and badges.</p>
                        <input type="text" wire:model="settings.site_short_name" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase">Tagline / Mission Motto</label>
                        <p class="text-[10px] text-slate-400 mb-1">Short mission motto displayed beneath logo.</p>
                        <input type="text" wire:model="settings.site_tagline" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase">State & Region Jurisdiction</label>
                        <p class="text-[10px] text-slate-400 mb-1">Official geographical territory of operation.</p>
                        <input type="text" wire:model="settings.state_jurisdiction" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase">Official Contact Phone</label>
                        <p class="text-[10px] text-slate-400 mb-1">Primary phone number for public enquiries.</p>
                        <input type="text" wire:model="settings.contact_phone" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase">Official Email Address</label>
                        <p class="text-[10px] text-slate-400 mb-1">Primary email inbox for incoming messages.</p>
                        <input type="email" wire:model="settings.contact_email" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase">Operating Hours</label>
                        <p class="text-[10px] text-slate-400 mb-1">Working days and public office hours.</p>
                        <input type="text" wire:model="settings.operating_hours" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase">Headquarters / Field Office Address</label>
                    <p class="text-[10px] text-slate-400 mb-1">Official physical postal address of the organization.</p>
                    <textarea rows="2" wire:model="settings.contact_address" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase">Footer Copyright Text</label>
                    <p class="text-[10px] text-slate-400 mb-1">Legal copyright notice displayed in the website footer.</p>
                    <input type="text" wire:model="settings.copyright_text" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-md shadow-emerald-800/20">
                        Save General Settings
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Tab 2: Top Bar & Announcement -->
    @if ($activeTab === 'top_bar')
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                <div class="pb-4 mb-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">PUBLIC GLOBAL COMPONENT</span>
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full {{ $topBarEnabled ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                ● {{ $topBarEnabled ? 'Enabled' : 'Disabled' }}
                            </span>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 font-heading mt-1">TOP BAR & ANNOUNCEMENT</h3>
                        <p class="text-xs text-slate-500">Control the announcement, contact information, visibility, and appearance of the public website's top bar.</p>
                    </div>
                    <div>
                        <button type="button" wire:click="resetTopBarColors" wire:confirm="Reset Top Bar colors to the default website appearance?" class="px-3 py-1.5 rounded-xl border border-slate-200 text-slate-600 hover:text-slate-900 hover:bg-slate-50 text-xs font-semibold flex items-center gap-1.5 transition-colors">
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                            <span>Reset Colors</span>
                        </button>
                    </div>
                </div>

                <!-- Live Preview Block -->
                <div class="mb-6 p-4 rounded-xl bg-slate-900 text-white">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-400">Live Component Preview</span>
                        <span class="text-[10px] text-slate-400">{{ $topBarEnabled ? 'Visible on public site' : 'Hidden from public site' }}</span>
                    </div>

                    @if ($topBarEnabled)
                        <div class="text-xs py-2 px-3 rounded-lg border border-black/10 flex flex-wrap items-center justify-between gap-2 shadow-xs transition-colors" 
                             style="background-color: {{ $topBarBgColor }}; color: {{ $topBarTextColor }};">
                            <div class="flex items-center gap-2">
                                @if (!empty($topBarBadge))
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold text-slate-950 shadow-2xs" 
                                          style="background-color: {{ $topBarBadgeColor }};">
                                        <i data-lucide="sparkles" class="w-3 h-3"></i> <span>{{ $topBarBadge }}</span>
                                    </span>
                                @endif
                                @if (!empty($topBarText))
                                    <span class="text-[11px] opacity-95">{{ $topBarText }}</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-3 text-[11px] opacity-95">
                                @if ($topBarPhoneEnabled && !empty($topBarPhone))
                                    <span class="inline-flex items-center gap-1" style="color: {{ $topBarTextColor }};">
                                        <i data-lucide="phone" class="w-3 h-3"></i> <span>{{ $topBarPhone }}</span>
                                    </span>
                                @endif
                                @if ($topBarPhoneEnabled && !empty($topBarPhone) && $topBarEmailEnabled && !empty($topBarEmail))
                                    <span class="opacity-40">•</span>
                                @endif
                                @if ($topBarEmailEnabled && !empty($topBarEmail))
                                    <span class="inline-flex items-center gap-1" style="color: {{ $topBarTextColor }};">
                                        <i data-lucide="mail" class="w-3 h-3"></i> <span>{{ $topBarEmail }}</span>
                                    </span>
                                @endif
                                @if ((($topBarPhoneEnabled && !empty($topBarPhone)) || ($topBarEmailEnabled && !empty($topBarEmail))))
                                    <span class="opacity-40">•</span>
                                @endif
                                <span class="font-semibold inline-flex items-center gap-1" style="color: {{ $topBarLinkColor }};">
                                    <i data-lucide="layout-dashboard" class="w-3 h-3"></i> <span>Admin Dashboard</span>
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="p-3 text-center text-xs text-slate-400 bg-slate-800/60 rounded-lg border border-slate-700 border-dashed">
                            Top Bar is currently disabled. It will not be rendered on the public website.
                        </div>
                    @endif
                </div>

                <form wire:submit.prevent="saveTopBar" class="space-y-6">
                    
                    <!-- A. TOP BAR VISIBILITY -->
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="text-xs font-bold text-slate-900 uppercase">Enable Top Bar</label>
                                <p class="text-xs text-slate-500 mt-0.5">Show or hide the public website's top information bar.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="topBarEnabled" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            </label>
                        </div>
                    </div>

                    <!-- B. ANNOUNCEMENT -->
                    <div class="space-y-4">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">Announcement Configuration</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Badge / Label</label>
                                <p class="text-[10px] text-slate-400 mb-1">Short label displayed at the beginning of the top bar.</p>
                                <input type="text" wire:model.live="topBarBadge" placeholder="e.g. Community Impact" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                                @error('topBarBadge') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Announcement Text</label>
                                <p class="text-[10px] text-slate-400 mb-1">Short public-facing announcement or organization message.</p>
                                <input type="text" wire:model.live="topBarText" placeholder="e.g. Tamil Nadu Grassroots Community Management & Social Impact" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                                @error('topBarText') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- C. CONTACT INFORMATION -->
                    <div class="space-y-4 pt-2 border-t border-slate-100">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">Contact Information</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Phone -->
                            <div class="p-4 bg-slate-50/60 rounded-xl border border-slate-200 space-y-2">
                                <div class="flex items-center justify-between">
                                    <label class="block text-xs font-semibold text-slate-700 uppercase">Phone Number</label>
                                    <label class="flex items-center gap-1.5 text-xs font-medium text-slate-700 cursor-pointer">
                                        <input type="checkbox" wire:model.live="topBarPhoneEnabled" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                        <span>Show Phone</span>
                                    </label>
                                </div>
                                <input type="text" wire:model.live="topBarPhone" placeholder="+91 94420 12345" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-white border border-slate-200 focus:border-emerald-600 text-slate-900">
                                <p class="text-[10px] text-slate-400">Displayed in the public top bar when enabled.</p>
                                @error('topBarPhone') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                            </div>

                            <!-- Email -->
                            <div class="p-4 bg-slate-50/60 rounded-xl border border-slate-200 space-y-2">
                                <div class="flex items-center justify-between">
                                    <label class="block text-xs font-semibold text-slate-700 uppercase">Email Address</label>
                                    <label class="flex items-center gap-1.5 text-xs font-medium text-slate-700 cursor-pointer">
                                        <input type="checkbox" wire:model.live="topBarEmailEnabled" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                        <span>Show Email</span>
                                    </label>
                                </div>
                                <input type="email" wire:model.live="topBarEmail" placeholder="contact@example.org" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-white border border-slate-200 focus:border-emerald-600 text-slate-900">
                                <p class="text-[10px] text-slate-400">Displayed in the public top bar when enabled.</p>
                                @error('topBarEmail') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- D. TOP BAR APPEARANCE -->
                    <div class="space-y-4 pt-2 border-t border-slate-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">Top Bar Appearance</h4>
                                <p class="text-[11px] text-slate-400">Customize the background, typography, and accent colors for the top information bar.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Background Color -->
                            <div class="p-3.5 bg-slate-50/60 rounded-xl border border-slate-200 space-y-2">
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Background Color</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" wire:model.live="topBarBgColor" class="w-9 h-9 rounded-lg border border-slate-300 cursor-pointer p-0.5 bg-white shrink-0">
                                    <input type="text" wire:model.live="topBarBgColor" placeholder="#166534" class="block w-full rounded-xl px-3 py-1.5 text-xs font-mono bg-white border border-slate-200 focus:border-emerald-600 text-slate-900 uppercase">
                                </div>
                                <p class="text-[10px] text-slate-400">Main background color of public top bar.</p>
                                @error('topBarBgColor') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                            </div>

                            <!-- Text Color -->
                            <div class="p-3.5 bg-slate-50/60 rounded-xl border border-slate-200 space-y-2">
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Text Color</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" wire:model.live="topBarTextColor" class="w-9 h-9 rounded-lg border border-slate-300 cursor-pointer p-0.5 bg-white shrink-0">
                                    <input type="text" wire:model.live="topBarTextColor" placeholder="#FFFFFF" class="block w-full rounded-xl px-3 py-1.5 text-xs font-mono bg-white border border-slate-200 focus:border-emerald-600 text-slate-900 uppercase">
                                </div>
                                <p class="text-[10px] text-slate-400">Primary text color for announcement and contacts.</p>
                                @error('topBarTextColor') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                            </div>

                            <!-- Badge Color -->
                            <div class="p-3.5 bg-slate-50/60 rounded-xl border border-slate-200 space-y-2">
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Badge Color</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" wire:model.live="topBarBadgeColor" class="w-9 h-9 rounded-lg border border-slate-300 cursor-pointer p-0.5 bg-white shrink-0">
                                    <input type="text" wire:model.live="topBarBadgeColor" placeholder="#F59E0B" class="block w-full rounded-xl px-3 py-1.5 text-xs font-mono bg-white border border-slate-200 focus:border-emerald-600 text-slate-900 uppercase">
                                </div>
                                <p class="text-[10px] text-slate-400">Background color for the highlighted badge pill.</p>
                                @error('topBarBadgeColor') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                            </div>

                            <!-- Link Color -->
                            <div class="p-3.5 bg-slate-50/60 rounded-xl border border-slate-200 space-y-2">
                                <label class="block text-xs font-semibold text-slate-700 uppercase">Link Accent Color</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" wire:model.live="topBarLinkColor" class="w-9 h-9 rounded-lg border border-slate-300 cursor-pointer p-0.5 bg-white shrink-0">
                                    <input type="text" wire:model.live="topBarLinkColor" placeholder="#FEF08A" class="block w-full rounded-xl px-3 py-1.5 text-xs font-mono bg-white border border-slate-200 focus:border-emerald-600 text-slate-900 uppercase">
                                </div>
                                <p class="text-[10px] text-slate-400">Color for Admin Dashboard link.</p>
                                @error('topBarLinkColor') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-md shadow-emerald-800/20">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Tab 3: Branding & Logos -->
    @if ($activeTab === 'branding')
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
            <div class="pb-4 mb-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">PUBLIC BRANDING ASSETS</span>
                    <h3 class="text-base font-bold text-slate-900 font-heading mt-1">BRANDING & LOGOS</h3>
                    <p class="text-xs text-slate-500">Upload official logo assets directly from your computer or choose existing files from the Media Library.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- 1. Header Brand Logo -->
                <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/40 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800">Header Brand Logo</h4>
                            <p class="text-[10px] text-slate-400">Displayed on the main public navigation header.</p>
                        </div>
                        @if (!empty($settings['logo_url']))
                            <button type="button" wire:click="removeBrandingImage('logo_url', 'Header Logo')" wire:confirm="Remove header brand logo?" class="text-[11px] text-red-600 hover:text-red-700 font-semibold flex items-center gap-1">
                                <i data-lucide="trash-2" class="w-3 h-3"></i> <span>Remove</span>
                            </button>
                        @endif
                    </div>

                    <!-- Preview Card -->
                    <div class="bg-white p-4 rounded-xl border border-slate-200 flex items-center justify-center min-h-[120px]">
                        @if (!empty($settings['logo_url']))
                            <img src="{{ $settings['logo_url'] }}" alt="Header Logo Preview" class="max-h-20 max-w-full object-contain">
                        @else
                            <div class="text-center py-4">
                                <i data-lucide="image" class="w-8 h-8 text-slate-300 mx-auto mb-1"></i>
                                <span class="text-xs text-slate-400">No logo uploaded (Default icon is active)</span>
                            </div>
                        @endif
                    </div>

                    <!-- Upload & Media Actions -->
                    <div class="flex flex-wrap items-center gap-2">
                        <label class="px-3.5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold cursor-pointer shadow-xs inline-flex items-center gap-1.5 transition-all">
                            <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                            <span>Upload from Device</span>
                            <input type="file" wire:model="headerLogoFile" accept="image/png,image/jpeg,image/webp,image/svg+xml" class="sr-only">
                        </label>
                        <button type="button" wire:click="openMediaPicker('settings.logo_url')" class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold inline-flex items-center gap-1.5 shadow-2xs">
                            <i data-lucide="images" class="w-3.5 h-3.5 text-emerald-600"></i>
                            <span>Choose from Media</span>
                        </button>
                    </div>

                    <div wire:loading wire:target="headerLogoFile" class="text-xs text-emerald-700 font-semibold flex items-center gap-1.5">
                        <i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i>
                        <span>Uploading logo from device...</span>
                    </div>

                    <!-- URL Reference -->
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase">Image URL Reference</label>
                        <input type="text" wire:model.blur="settings.logo_url" placeholder="/storage/media/... or https://..." class="mt-1 block w-full rounded-xl px-3 py-1.5 text-xs font-mono bg-white border border-slate-200 focus:border-emerald-600 text-slate-700">
                    </div>
                </div>

                <!-- 2. Footer Brand Logo -->
                <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/40 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800">Footer Brand Logo</h4>
                            <p class="text-[10px] text-slate-400">Displayed in the dark footer section (light/white logo recommended).</p>
                        </div>
                        @if (!empty($settings['footer_logo_url']))
                            <button type="button" wire:click="removeBrandingImage('footer_logo_url', 'Footer Logo')" wire:confirm="Remove footer brand logo?" class="text-[11px] text-red-600 hover:text-red-700 font-semibold flex items-center gap-1">
                                <i data-lucide="trash-2" class="w-3 h-3"></i> <span>Remove</span>
                            </button>
                        @endif
                    </div>

                    <!-- Preview Card -->
                    <div class="bg-slate-900 p-4 rounded-xl border border-slate-800 flex items-center justify-center min-h-[120px]">
                        @if (!empty($settings['footer_logo_url']))
                            <img src="{{ $settings['footer_logo_url'] }}" alt="Footer Logo Preview" class="max-h-20 max-w-full object-contain">
                        @else
                            <div class="text-center py-4">
                                <i data-lucide="image" class="w-8 h-8 text-slate-600 mx-auto mb-1"></i>
                                <span class="text-xs text-slate-500">No footer logo (Header logo / default icon is active)</span>
                            </div>
                        @endif
                    </div>

                    <!-- Upload & Media Actions -->
                    <div class="flex flex-wrap items-center gap-2">
                        <label class="px-3.5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold cursor-pointer shadow-xs inline-flex items-center gap-1.5 transition-all">
                            <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                            <span>Upload from Device</span>
                            <input type="file" wire:model="footerLogoFile" accept="image/png,image/jpeg,image/webp,image/svg+xml" class="sr-only">
                        </label>
                        <button type="button" wire:click="openMediaPicker('settings.footer_logo_url')" class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold inline-flex items-center gap-1.5 shadow-2xs">
                            <i data-lucide="images" class="w-3.5 h-3.5 text-emerald-600"></i>
                            <span>Choose from Media</span>
                        </button>
                    </div>

                    <div wire:loading wire:target="footerLogoFile" class="text-xs text-emerald-700 font-semibold flex items-center gap-1.5">
                        <i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i>
                        <span>Uploading logo from device...</span>
                    </div>

                    <!-- URL Reference -->
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase">Image URL Reference</label>
                        <input type="text" wire:model.blur="settings.footer_logo_url" placeholder="/storage/media/... or https://..." class="mt-1 block w-full rounded-xl px-3 py-1.5 text-xs font-mono bg-white border border-slate-200 focus:border-emerald-600 text-slate-700">
                    </div>
                </div>

                <!-- 3. Favicon -->
                <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/40 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800">Browser Tab Favicon</h4>
                            <p class="text-[10px] text-slate-400">Displayed in browser tabs and bookmarks (ICO, PNG, or SVG).</p>
                        </div>
                        @if (!empty($settings['favicon_url']))
                            <button type="button" wire:click="removeBrandingImage('favicon_url', 'Favicon')" wire:confirm="Remove custom favicon?" class="text-[11px] text-red-600 hover:text-red-700 font-semibold flex items-center gap-1">
                                <i data-lucide="trash-2" class="w-3 h-3"></i> <span>Remove</span>
                            </button>
                        @endif
                    </div>

                    <!-- Preview Card -->
                    <div class="bg-white p-4 rounded-xl border border-slate-200 flex items-center justify-center min-h-[120px]">
                        @if (!empty($settings['favicon_url']))
                            <div class="flex items-center gap-3 p-2 bg-slate-100 rounded-lg border border-slate-200">
                                <img src="{{ $settings['favicon_url'] }}" alt="Favicon Preview" class="w-8 h-8 object-contain">
                                <div class="text-left">
                                    <span class="text-xs font-bold text-slate-800 block">Browser Tab Icon</span>
                                    <span class="text-[10px] text-slate-400">Active Website Favicon</span>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i data-lucide="globe" class="w-8 h-8 text-slate-300 mx-auto mb-1"></i>
                                <span class="text-xs text-slate-400">Default favicon active</span>
                            </div>
                        @endif
                    </div>

                    <!-- Upload & Media Actions -->
                    <div class="flex flex-wrap items-center gap-2">
                        <label class="px-3.5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold cursor-pointer shadow-xs inline-flex items-center gap-1.5 transition-all">
                            <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                            <span>Upload from Device</span>
                            <input type="file" wire:model="faviconFile" accept="image/x-icon,image/png,image/svg+xml,image/webp,image/jpeg" class="sr-only">
                        </label>
                        <button type="button" wire:click="openMediaPicker('settings.favicon_url')" class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold inline-flex items-center gap-1.5 shadow-2xs">
                            <i data-lucide="images" class="w-3.5 h-3.5 text-emerald-600"></i>
                            <span>Choose from Media</span>
                        </button>
                    </div>

                    <div wire:loading wire:target="faviconFile" class="text-xs text-emerald-700 font-semibold flex items-center gap-1.5">
                        <i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i>
                        <span>Uploading favicon from device...</span>
                    </div>

                    <!-- URL Reference -->
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase">Favicon URL Reference</label>
                        <input type="text" wire:model.blur="settings.favicon_url" placeholder="/favicon.ico or /storage/media/..." class="mt-1 block w-full rounded-xl px-3 py-1.5 text-xs font-mono bg-white border border-slate-200 focus:border-emerald-600 text-slate-700">
                    </div>
                </div>

                <!-- 4. Social Share Preview Image -->
                <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/40 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800">Social Share Image (OG:Image)</h4>
                            <p class="text-[10px] text-slate-400">Displayed when website links are shared on Facebook, WhatsApp, or Twitter.</p>
                        </div>
                        @if (!empty($settings['social_share_image']))
                            <button type="button" wire:click="removeBrandingImage('social_share_image', 'Social Share Image')" wire:confirm="Remove social share preview image?" class="text-[11px] text-red-600 hover:text-red-700 font-semibold flex items-center gap-1">
                                <i data-lucide="trash-2" class="w-3 h-3"></i> <span>Remove</span>
                            </button>
                        @endif
                    </div>

                    <!-- Preview Card -->
                    <div class="bg-white p-4 rounded-xl border border-slate-200 flex items-center justify-center min-h-[120px]">
                        @if (!empty($settings['social_share_image']))
                            <img src="{{ $settings['social_share_image'] }}" alt="Social Share Preview" class="max-h-24 max-w-full rounded object-cover shadow-2xs">
                        @else
                            <div class="text-center py-4">
                                <i data-lucide="share-2" class="w-8 h-8 text-slate-300 mx-auto mb-1"></i>
                                <span class="text-xs text-slate-400">No social preview image selected</span>
                            </div>
                        @endif
                    </div>

                    <!-- Upload & Media Actions -->
                    <div class="flex flex-wrap items-center gap-2">
                        <label class="px-3.5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold cursor-pointer shadow-xs inline-flex items-center gap-1.5 transition-all">
                            <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                            <span>Upload from Device</span>
                            <input type="file" wire:model="socialShareImageFile" accept="image/png,image/jpeg,image/webp" class="sr-only">
                        </label>
                        <button type="button" wire:click="openMediaPicker('settings.social_share_image')" class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold inline-flex items-center gap-1.5 shadow-2xs">
                            <i data-lucide="images" class="w-3.5 h-3.5 text-emerald-600"></i>
                            <span>Choose from Media</span>
                        </button>
                    </div>

                    <div wire:loading wire:target="socialShareImageFile" class="text-xs text-emerald-700 font-semibold flex items-center gap-1.5">
                        <i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i>
                        <span>Uploading share image from device...</span>
                    </div>

                    <!-- URL Reference -->
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase">Image URL Reference</label>
                        <input type="text" wire:model.blur="settings.social_share_image" placeholder="/storage/media/... or https://..." class="mt-1 block w-full rounded-xl px-3 py-1.5 text-xs font-mono bg-white border border-slate-200 focus:border-emerald-600 text-slate-700">
                    </div>
                </div>

            </div>

            <div class="pt-6 mt-6 border-t border-slate-100 flex justify-end">
                <button type="button" wire:click="saveGeneral" class="px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-md shadow-emerald-800/20">
                    Save Branding References
                </button>
            </div>
        </div>
    @endif

    <!-- Tab 4: Navigation Menus -->
    @if ($activeTab === 'menus')
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 mb-5 border-b border-slate-100 gap-3">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">NAVIGATION STRUCTURE</span>
                    <h3 class="text-base font-bold text-slate-900 font-heading mt-1">MENU LINK STRUCTURE</h3>
                    <p class="text-xs text-slate-500">Configure navigation links for the main header and footer columns.</p>
                </div>
                <div class="flex items-center gap-2">
                    <select wire:model.live="selectedMenuLocation" class="rounded-xl px-3 py-1.5 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-800 font-semibold">
                        <option value="header">Main Header Navigation</option>
                        <option value="footer_quick_links">Footer Quick Links</option>
                        <option value="footer_get_involved">Footer Get Involved</option>
                    </select>
                </div>
            </div>

            <!-- Add Menu Item Form -->
            <form wire:submit.prevent="addMenuItem" class="bg-slate-50 rounded-xl p-4 border border-slate-200/80 mb-5 flex flex-col sm:flex-row gap-3 items-end">
                <div class="flex-1 w-full">
                    <label class="block text-[11px] font-semibold text-slate-600 uppercase">Link Label</label>
                    <input type="text" wire:model="newMenuItemLabel" placeholder="e.g. Programs" class="mt-1 block w-full rounded-xl px-3.5 py-1.5 text-xs bg-white border border-slate-200 focus:border-emerald-600 text-slate-900">
                </div>
                <div class="flex-1 w-full">
                    <label class="block text-[11px] font-semibold text-slate-600 uppercase">Target URL / Hash</label>
                    <input type="text" wire:model="newMenuItemUrl" placeholder="e.g. #programs or /about" class="mt-1 block w-full rounded-xl px-3.5 py-1.5 text-xs bg-white border border-slate-200 focus:border-emerald-600 text-slate-900">
                </div>
                <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shrink-0">
                    Add Link
                </button>
            </form>

            <!-- Current Menu Items Table -->
            @if ($currentMenu && $currentMenu->items->isNotEmpty())
                <div class="divide-y divide-slate-100 border border-slate-100 rounded-xl overflow-hidden">
                    @foreach ($currentMenu->items as $item)
                        <div class="p-3 bg-white flex items-center justify-between text-xs hover:bg-slate-50/60 transition-colors">
                            <div class="flex items-center gap-3">
                                <i data-lucide="grip-vertical" class="w-4 h-4 text-slate-300"></i>
                                <div>
                                    <span class="font-semibold text-slate-900">{{ $item->label }}</span>
                                    <span class="text-slate-400 text-[11px] ml-2 font-mono">{{ $item->url }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" 
                                        wire:click="toggleMenuItem({{ $item->id }})" 
                                        class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $item->is_enabled ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                                    {{ $item->is_enabled ? 'Enabled' : 'Disabled' }}
                                </button>
                                <button type="button" 
                                        wire:click="deleteMenuItem({{ $item->id }})" 
                                        class="p-1 rounded text-slate-400 hover:text-red-600">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-slate-400 py-6 text-center">No menu items configured for this location.</p>
            @endif
        </div>
    @endif

    <!-- Tab 5: Social Media Links -->
    @if ($activeTab === 'social')
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 mb-6 border-b border-slate-100 gap-3">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">PUBLIC CHANNELS</span>
                    <h3 class="text-base font-bold text-slate-900 font-heading mt-1">OFFICIAL SOCIAL MEDIA CHANNELS</h3>
                    <p class="text-xs text-slate-500">Manage official social profiles rendered in the public website header and footer. Reorder, edit, activate, or remove channels.</p>
                </div>
                <button type="button" 
                        wire:click="openSocialModal()" 
                        class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold flex items-center gap-1.5 shadow-xs shrink-0">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>+ Add Social Channel</span>
                </button>
            </div>

            <!-- Social Links List -->
            @if ($socialLinks->isNotEmpty())
                <div class="divide-y divide-slate-100 border border-slate-200/80 rounded-2xl overflow-hidden shadow-2xs">
                    @foreach ($socialLinks as $index => $s)
                        <div class="p-4 bg-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 hover:bg-slate-50/60 transition-colors">
                            <div class="flex items-center gap-3">
                                <span class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center font-bold text-xs shrink-0 shadow-2xs">
                                    {{ strtoupper(substr($s->platform, 0, 2)) }}
                                </span>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-xs text-slate-900">{{ $s->platform }}</span>
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full {{ $s->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                                            ● {{ $s->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                    <a href="{{ $s->url }}" target="_blank" rel="noopener noreferrer" class="text-slate-400 hover:text-emerald-700 text-[11px] font-mono flex items-center gap-1 mt-0.5">
                                        <span>{{ $s->url }}</span>
                                        <i data-lucide="external-link" class="w-3 h-3"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 self-end sm:self-auto">
                                <!-- Reorder Buttons -->
                                <div class="flex items-center rounded-lg border border-slate-200 bg-slate-50 p-0.5">
                                    <button type="button" 
                                            wire:click="moveSocialLink({{ $s->id }}, 'up')" 
                                            @if($index === 0) disabled @endif
                                            class="p-1 rounded hover:bg-white text-slate-500 hover:text-slate-900 disabled:opacity-30 disabled:hover:bg-transparent" 
                                            title="Move Up">
                                        <i data-lucide="arrow-up" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <button type="button" 
                                            wire:click="moveSocialLink({{ $s->id }}, 'down')" 
                                            @if($index === $socialLinks->count() - 1) disabled @endif
                                            class="p-1 rounded hover:bg-white text-slate-500 hover:text-slate-900 disabled:opacity-30 disabled:hover:bg-transparent" 
                                            title="Move Down">
                                        <i data-lucide="arrow-down" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>

                                <!-- Toggle Active -->
                                <button type="button" 
                                        wire:click="toggleSocialLink({{ $s->id }})" 
                                        class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ $s->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 border border-slate-200 hover:bg-slate-200' }}">
                                    {{ $s->is_active ? 'Deactivate' : 'Activate' }}
                                </button>

                                <!-- Edit -->
                                <button type="button" 
                                        wire:click="openSocialModal({{ $s->id }})" 
                                        class="px-2.5 py-1 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-semibold flex items-center gap-1 shadow-2xs">
                                    <i data-lucide="pencil" class="w-3 h-3 text-slate-400"></i>
                                    <span>Edit</span>
                                </button>

                                <!-- Delete -->
                                <button type="button" 
                                        wire:click="confirmDeleteSocial({{ $s->id }}, '{{ addslashes($s->platform) }}')" 
                                        class="p-1.5 rounded-lg border border-red-100 text-red-500 hover:text-red-700 hover:bg-red-50" 
                                        title="Delete Channel">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-12 text-center rounded-2xl border border-dashed border-slate-200 bg-slate-50/50">
                    <i data-lucide="share-2" class="w-10 h-10 text-slate-300 mx-auto mb-2"></i>
                    <h4 class="text-xs font-bold text-slate-700 uppercase">No Social Media Channels Added</h4>
                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Add your official Facebook, Instagram, YouTube, or LinkedIn profiles so visitors can connect with your foundation.</p>
                    <button type="button" 
                            wire:click="openSocialModal()" 
                            class="mt-4 px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold inline-flex items-center gap-1.5 shadow-xs">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        <span>+ Add First Social Channel</span>
                    </button>
                </div>
            @endif
        </div>
    @endif

    <!-- Tab 6: SEO -->
    @if ($activeTab === 'seo')
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
            <div class="pb-4 mb-6 border-b border-slate-100">
                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">SEARCH ENGINE & METADATA</span>
                <h3 class="text-base font-bold text-slate-900 font-heading mt-1">SEO & OPENGRAPH PARAMETERS</h3>
                <p class="text-xs text-slate-500">Configure page title, meta description, keywords, canonical URLs, and OpenGraph sharing metadata.</p>
            </div>

            <form wire:submit.prevent="saveSeo" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase">Meta Page Title</label>
                    <p class="text-[10px] text-slate-400 mb-1">Title rendered in search engine results and browser tabs.</p>
                    <input type="text" wire:model="seo.meta_title" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase">Meta Description</label>
                    <p class="text-[10px] text-slate-400 mb-1">Concise summary of the NGO's mission for Google search snippets.</p>
                    <textarea rows="3" wire:model="seo.meta_description" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase">Meta Keywords (Comma separated)</label>
                    <p class="text-[10px] text-slate-400 mb-1">e.g. Tamil Nadu NGO, rural education, Coimbatore NGO</p>
                    <input type="text" wire:model="seo.keywords" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase">OpenGraph Title</label>
                        <p class="text-[10px] text-slate-400 mb-1">Title displayed when shared on social networks.</p>
                        <input type="text" wire:model="seo.og_title" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase">Canonical URL</label>
                        <p class="text-[10px] text-slate-400 mb-1">Preferred absolute URL for search indexing.</p>
                        <input type="url" wire:model="seo.canonical_url" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase">OpenGraph Social Share Image URL</label>
                    <p class="text-[10px] text-slate-400 mb-1">Image URL displayed in social sharing preview cards.</p>
                    <div class="flex items-center gap-2">
                        <input type="text" wire:model="seo.og_image" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                        <button type="button" wire:click="openMediaPicker('seo.og_image')" class="px-3 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shrink-0">
                            Choose Media
                        </button>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-md shadow-emerald-800/20">
                        Save SEO Settings
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MODAL: Social Media Link Add / Edit                                       -->
    <!-- ========================================================================= -->
    @if ($showSocialModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 animate-scaleUp">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                            <i data-lucide="share-2" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 font-heading">
                            {{ $editingSocialLinkId ? 'Edit Social Channel' : 'Add New Social Channel' }}
                        </h3>
                    </div>
                    <button type="button" wire:click="closeSocialModal" class="text-slate-400 hover:text-slate-700 p-1">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <form wire:submit.prevent="saveSocialLink" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase">Platform Name</label>
                        <p class="text-[10px] text-slate-400 mb-1">e.g. Facebook, Instagram, YouTube, X, LinkedIn, WhatsApp</p>
                        <input type="text" wire:model.live="socialForm.platform" placeholder="e.g. Facebook" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                        @error('socialForm.platform') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase">Profile / Page URL</label>
                        <p class="text-[10px] text-slate-400 mb-1">Full URL to the organization's social profile.</p>
                        <input type="url" wire:model="socialForm.url" placeholder="https://facebook.com/nanbanfoundation" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                        @error('socialForm.url') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Display Order</label>
                            <input type="number" wire:model="socialForm.sort_order" min="1" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            @error('socialForm.sort_order') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Status</label>
                            <select wire:model="socialForm.is_active" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                                <option value="1">Active (Public)</option>
                                <option value="0">Inactive (Hidden)</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
                        <button type="button" wire:click="closeSocialModal" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs">
                            {{ $editingSocialLinkId ? 'Save Changes' : 'Add Channel' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MODAL: Delete Social Confirmation                                         -->
    <!-- ========================================================================= -->
    @if ($confirmingDeleteSocial)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 animate-scaleUp">
                <div class="flex items-center gap-3 mb-3 text-red-600">
                    <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                        <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 font-heading">Delete Social Channel</h3>
                        <p class="text-xs text-slate-500">Confirm social link deletion</p>
                    </div>
                </div>

                <p class="text-xs text-slate-600 mb-6 leading-relaxed">
                    Are you sure you want to remove <span class="font-bold text-slate-900">{{ $confirmingDeleteSocialTitle }}</span>? This action will immediately remove the channel link from the public website header and footer.
                </p>

                <div class="flex items-center justify-end gap-2">
                    <button type="button" wire:click="cancelDeleteSocial" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-semibold">
                        Cancel
                    </button>
                    <button type="button" wire:click="performDeleteSocial" class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-semibold shadow-xs">
                        Confirm Delete
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MODAL: Reusable Media Library Chooser                                     -->
    <!-- ========================================================================= -->
    @if ($showMediaPickerModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-3xl w-full p-6 shadow-2xl border border-slate-200 animate-scaleUp max-h-[90vh] flex flex-col">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 font-heading flex items-center gap-2">
                            <i data-lucide="images" class="w-4 h-4 text-emerald-600"></i>
                            <span>Select from Media Library</span>
                        </h3>
                        <p class="text-xs text-slate-400">Choose an image asset already uploaded to the platform.</p>
                    </div>
                    <button type="button" wire:click="closeMediaPicker" class="text-slate-400 hover:text-slate-700 p-1">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <!-- Search Input -->
                <div class="mb-4">
                    <input type="text" wire:model.live.debounce.300ms="mediaSearch" placeholder="Search media assets by filename or title..." class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                </div>

                <!-- Media Grid -->
                <div class="flex-1 overflow-y-auto min-h-[300px]">
                    @if (isset($mediaList) && $mediaList->isNotEmpty())
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                            @foreach ($mediaList as $media)
                                <div wire:click="selectMediaItem('{{ $media->url }}')" class="group relative rounded-xl border border-slate-200 bg-slate-50 p-2 cursor-pointer hover:border-emerald-600 hover:shadow-md transition-all">
                                    <div class="aspect-video w-full rounded-lg overflow-hidden bg-slate-200 flex items-center justify-center">
                                        <img src="{{ $media->url }}" alt="{{ $media->alt_text }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    </div>
                                    <div class="mt-2">
                                        <p class="text-[11px] font-semibold text-slate-800 truncate" title="{{ $media->original_name }}">{{ $media->original_name }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $media->formatted_size }}</p>
                                    </div>
                                    <div class="absolute inset-0 bg-emerald-900/40 rounded-xl opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                        <span class="px-2.5 py-1 bg-white text-emerald-800 rounded-lg text-[10px] font-bold shadow-sm">Select Image</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-16 text-slate-400">
                            <i data-lucide="image" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
                            <p class="text-xs">No media assets found matching your search.</p>
                        </div>
                    @endif
                </div>

                <div class="pt-4 mt-4 border-t border-slate-100 flex justify-end">
                    <button type="button" wire:click="closeMediaPicker" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-semibold">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
