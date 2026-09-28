<div>
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 font-heading">Media Asset Library</h1>
            <p class="text-xs text-slate-500 mt-1">Upload and manage high-resolution photos, logos, banners, and documents for the NGO platform.</p>
        </div>
    </div>

    <!-- Drag & Drop / Multi-file Upload Area -->
    <div class="bg-white rounded-2xl border-2 border-dashed border-emerald-300/80 bg-emerald-50/20 p-6 mb-6 text-center hover:bg-emerald-50/40 transition-colors relative"
         x-data="{ isDropping: false }"
         @dragover.prevent="isDropping = true"
         @dragleave.prevent="isDropping = false"
         @drop.prevent="isDropping = false">
        
        <input type="file" 
               wire:model="uploadFiles" 
               multiple 
               accept="image/jpeg,image/png,image/jpg,image/webp,image/svg+xml"
               id="media-upload-input" 
               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
        
        <div class="flex flex-col items-center justify-center pointer-events-none">
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center mb-3 shadow-xs">
                <i data-lucide="upload-cloud" class="w-6 h-6"></i>
            </div>
            <p class="text-sm font-bold text-slate-800">
                <span>Click to browse</span> or drag and drop image files here
            </p>
            <p class="text-xs text-slate-500 mt-1">
                Supports JPG, PNG, WEBP, SVG up to 10MB each.
            </p>
        </div>

        <div wire:loading wire:target="uploadFiles" class="mt-3">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-semibold animate-pulse">
                <svg class="animate-spin h-3.5 w-3.5 text-emerald-700" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Processing & storing images...</span>
            </div>
        </div>

        @error('uploadFiles.*') 
            <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p> 
        @enderror
    </div>

    <!-- Search Bar -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4 mb-6">
        <div class="relative max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <i data-lucide="search" class="w-4 h-4"></i>
            </div>
            <input type="text" 
                   wire:model.live.debounce.300ms="search" 
                   placeholder="Search media by filename, title, alt text..." 
                   class="block w-full rounded-xl pl-10 pr-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 text-slate-900">
        </div>
    </div>

    <!-- Media Grid -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
        @if ($mediaList->isEmpty())
            <div class="py-16 text-center text-slate-400">
                <i data-lucide="image" class="w-10 h-10 mx-auto mb-3 text-slate-300"></i>
                <p class="text-sm font-semibold text-slate-600">No media assets found</p>
                <p class="text-xs text-slate-400 mt-1">Upload images above to populate the NGO media library.</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach ($mediaList as $media)
                    <div class="group relative rounded-xl border border-slate-200 overflow-hidden bg-slate-50 hover:shadow-md transition-all flex flex-col">
                        <!-- Thumbnail Container -->
                        <div class="aspect-square w-full overflow-hidden bg-slate-100 relative">
                            <img src="{{ $media->url }}" 
                                 alt="{{ $media->alt_text }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                 loading="lazy">
                            
                            <!-- Hover Action Overlay -->
                            <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-1.5 p-2">
                                @if ($isPickerMode)
                                    <button type="button" 
                                            wire:click="selectForPicker({{ $media->id }})" 
                                            class="p-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm text-xs font-semibold"
                                            title="Select this image">
                                        <i data-lucide="check" class="w-4 h-4"></i>
                                    </button>
                                @endif
                                <button type="button" 
                                        wire:click="openEdit({{ $media->id }})" 
                                        class="p-1.5 rounded-lg bg-white/90 text-slate-700 hover:bg-white hover:text-emerald-700 shadow-sm"
                                        title="Edit metadata">
                                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                </button>
                                <button type="button" 
                                        @click="openConfirm('Delete Media', 'Are you sure you want to delete \'{{ $media->original_name }}\'?', () => $wire.deleteMedia({{ $media->id }}))" 
                                        class="p-1.5 rounded-lg bg-white/90 text-red-600 hover:bg-white hover:text-red-700 shadow-sm"
                                        title="Delete file">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Info Bar -->
                        <div class="p-2 text-[11px] bg-white border-t border-slate-100 flex-1 flex flex-col justify-between">
                            <p class="font-medium text-slate-800 truncate" title="{{ $media->title ?: $media->original_name }}">
                                {{ $media->title ?: $media->original_name }}
                            </p>
                            <div class="flex items-center justify-between text-[10px] text-slate-400 mt-1">
                                <span>{{ $media->formatted_size }}</span>
                                @if ($media->width)
                                    <span>{{ $media->width }}&times;{{ $media->height }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100">
                {{ $mediaList->links() }}
            </div>
        @endif
    </div>

    <!-- Edit Metadata Modal -->
    @if ($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-900 font-heading">Edit Media Details</h3>
                    <button type="button" wire:click="$set('showEditModal', false)" class="text-slate-400 hover:text-slate-600">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form wire:submit.prevent="saveEdit" class="mt-4 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase">Title / Display Name</label>
                        <input type="text" wire:model="editTitle" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase">Alt Text (Accessibility & SEO)</label>
                        <input type="text" wire:model="editAltText" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        <button type="button" wire:click="$set('showEditModal', false)" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-md shadow-emerald-800/20">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
