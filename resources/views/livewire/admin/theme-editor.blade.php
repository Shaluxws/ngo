<div>
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 font-heading">Design Theme & Color Tokens</h1>
            <p class="text-xs text-slate-500 mt-1">Customize the visual identity, brand palette, and typography rendered across the public NGO website.</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" 
                    wire:click="resetToDefaults" 
                    class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold">
                Reset to Defaults
            </button>
            <button type="button" 
                    wire:click="saveTheme" 
                    class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-md shadow-emerald-800/20">
                Save & Publish Theme
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Cols: Color Pickers & Typography Controls -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Palette Configuration -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                <h3 class="text-sm font-bold text-slate-900 font-heading mb-4 pb-3 border-b border-slate-100 flex items-center gap-2">
                    <i data-lucide="palette" class="w-4 h-4 text-emerald-600"></i>
                    <span>Brand Color Palette</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Primary Color -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase">Primary Color (Emerald)</label>
                        <div class="mt-1 flex items-center gap-2">
                            <input type="color" wire:model.live="theme.primary_color" class="h-9 w-10 rounded-lg cursor-pointer border border-slate-200 p-0.5 bg-white">
                            <input type="text" wire:model="theme.primary_color" class="block w-full rounded-xl px-3 py-1.5 text-xs font-mono bg-slate-50 border border-slate-200">
                        </div>
                        @error('theme.primary_color') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <!-- Secondary Color -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase">Secondary Color (Teal)</label>
                        <div class="mt-1 flex items-center gap-2">
                            <input type="color" wire:model.live="theme.secondary_color" class="h-9 w-10 rounded-lg cursor-pointer border border-slate-200 p-0.5 bg-white">
                            <input type="text" wire:model="theme.secondary_color" class="block w-full rounded-xl px-3 py-1.5 text-xs font-mono bg-slate-50 border border-slate-200">
                        </div>
                        @error('theme.secondary_color') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <!-- Accent Color -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase">Accent Color (Amber)</label>
                        <div class="mt-1 flex items-center gap-2">
                            <input type="color" wire:model.live="theme.accent_color" class="h-9 w-10 rounded-lg cursor-pointer border border-slate-200 p-0.5 bg-white">
                            <input type="text" wire:model="theme.accent_color" class="block w-full rounded-xl px-3 py-1.5 text-xs font-mono bg-slate-50 border border-slate-200">
                        </div>
                        @error('theme.accent_color') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                    <!-- Background Color -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase">Background Tone</label>
                        <div class="mt-1 flex items-center gap-2">
                            <input type="color" wire:model.live="theme.background_color" class="h-9 w-10 rounded-lg cursor-pointer border border-slate-200 p-0.5 bg-white">
                            <input type="text" wire:model="theme.background_color" class="block w-full rounded-xl px-3 py-1.5 text-xs font-mono bg-slate-50 border border-slate-200">
                        </div>
                    </div>

                    <!-- Surface Color -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase">Surface / Card Color</label>
                        <div class="mt-1 flex items-center gap-2">
                            <input type="color" wire:model.live="theme.surface_color" class="h-9 w-10 rounded-lg cursor-pointer border border-slate-200 p-0.5 bg-white">
                            <input type="text" wire:model="theme.surface_color" class="block w-full rounded-xl px-3 py-1.5 text-xs font-mono bg-slate-50 border border-slate-200">
                        </div>
                    </div>

                    <!-- Button Color -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase">Button Primary Color</label>
                        <div class="mt-1 flex items-center gap-2">
                            <input type="color" wire:model.live="theme.button_color" class="h-9 w-10 rounded-lg cursor-pointer border border-slate-200 p-0.5 bg-white">
                            <input type="text" wire:model="theme.button_color" class="block w-full rounded-xl px-3 py-1.5 text-xs font-mono bg-slate-50 border border-slate-200">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                    <!-- Text Color -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase">Heading Text Color</label>
                        <div class="mt-1 flex items-center gap-2">
                            <input type="color" wire:model.live="theme.text_color" class="h-9 w-10 rounded-lg cursor-pointer border border-slate-200 p-0.5 bg-white">
                            <input type="text" wire:model="theme.text_color" class="block w-full rounded-xl px-3 py-1.5 text-xs font-mono bg-slate-50 border border-slate-200">
                        </div>
                    </div>

                    <!-- Muted Text Color -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase">Muted Paragraph Color</label>
                        <div class="mt-1 flex items-center gap-2">
                            <input type="color" wire:model.live="theme.muted_text_color" class="h-9 w-10 rounded-lg cursor-pointer border border-slate-200 p-0.5 bg-white">
                            <input type="text" wire:model="theme.muted_text_color" class="block w-full rounded-xl px-3 py-1.5 text-xs font-mono bg-slate-50 border border-slate-200">
                        </div>
                    </div>

                    <!-- Border Color -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase">Component Border Color</label>
                        <div class="mt-1 flex items-center gap-2">
                            <input type="color" wire:model.live="theme.border_color" class="h-9 w-10 rounded-lg cursor-pointer border border-slate-200 p-0.5 bg-white">
                            <input type="text" wire:model="theme.border_color" class="block w-full rounded-xl px-3 py-1.5 text-xs font-mono bg-slate-50 border border-slate-200">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Typography & Styling -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                <h3 class="text-sm font-bold text-slate-900 font-heading mb-4 pb-3 border-b border-slate-100 flex items-center gap-2">
                    <i data-lucide="type" class="w-4 h-4 text-emerald-600"></i>
                    <span>Typography & Shapes</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase">Heading Font</label>
                        <select wire:model.live="theme.heading_font" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            <option value="Plus Jakarta Sans">Plus Jakarta Sans (Approved)</option>
                            <option value="Inter">Inter</option>
                            <option value="Roboto">Roboto</option>
                            <option value="Outfit">Outfit</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase">Body Font</label>
                        <select wire:model.live="theme.body_font" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            <option value="Inter">Inter (Approved)</option>
                            <option value="Roboto">Roboto</option>
                            <option value="Plus Jakarta Sans">Plus Jakarta Sans</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase">Border Radius</label>
                        <select wire:model.live="theme.border_radius" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            <option value="0.75rem">Rounded (0.75rem / 12px)</option>
                            <option value="1rem">Soft (1rem / 16px)</option>
                            <option value="0.5rem">Subtle (0.5rem / 8px)</option>
                            <option value="0">Sharp (0px)</option>
                        </select>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right 1 Col: Live Component Preview -->
        <div>
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 sticky top-20">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-4 flex items-center justify-between">
                    <span>Live Theme Token Preview</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                </h3>

                <!-- Preview Card -->
                <div class="p-5 rounded-2xl border transition-all"
                     style="background-color: {{ $theme['surface_color'] ?? '#ffffff' }}; border-color: {{ $theme['border_color'] ?? '#e2e8f0' }}; font-family: {{ $theme['body_font'] ?? 'Inter' }};">
                    
                    <span class="inline-flex items-center px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-full"
                          style="background-color: {{ $theme['primary_color'] ?? '#047857' }}15; color: {{ $theme['primary_color'] ?? '#047857' }};">
                        SERVING COMMUNITIES
                    </span>

                    <h4 class="text-lg font-bold mt-3 leading-snug"
                        style="color: {{ $theme['text_color'] ?? '#0f172a' }}; font-family: {{ $theme['heading_font'] ?? 'Plus Jakarta Sans' }};">
                        Building Sustainable Communities Together
                    </h4>

                    <p class="text-xs mt-2 leading-relaxed"
                       style="color: {{ $theme['muted_text_color'] ?? '#64748b' }};">
                        Previewing dynamic typography, harmonious HSL tones, and rounded card geometry.
                    </p>

                    <div class="mt-4 pt-3 border-t flex items-center justify-between"
                         style="border-color: {{ $theme['border_color'] ?? '#e2e8f0' }};">
                        <span class="text-xs font-bold" style="color: {{ $theme['accent_color'] ?? '#d97706' }};">
                            Goal: ₹5,00,000
                        </span>
                        <button type="button" 
                                class="px-3 py-1.5 text-xs font-semibold text-white transition-all shadow-xs"
                                style="background-color: {{ $theme['button_color'] ?? '#047857' }}; border-radius: {{ $theme['border_radius'] ?? '0.75rem' }};">
                            Support Mission
                        </button>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="button" 
                            wire:click="saveTheme" 
                            class="w-full py-2.5 px-4 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-md shadow-emerald-800/20 transition-all">
                        Save & Apply to Public Site
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>
