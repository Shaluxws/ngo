<section class="py-16 md:py-20 bg-gradient-to-br from-[#15803D] via-[#166534] to-[#0F766E] text-white relative overflow-hidden">
    
    <!-- Background Motif Pattern -->
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:16px_16px]"></div>
    
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 space-y-6">
        
        <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-white/20 backdrop-blur-md text-amber-300 text-xs font-bold uppercase tracking-wider">
            <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
            GET INVOLVED TODAY
        </span>

        <h2 class="text-3xl sm:text-4xl md:text-5xl font-extrabold font-heading tracking-tight text-white leading-tight">
            Be Part of the Change in Tamil Nadu
        </h2>

        <p class="text-base sm:text-lg text-green-100 max-w-2xl mx-auto leading-relaxed">
            Whether you want to volunteer on weekends, mentor rural students, support healthcare camps, or join as an active member, every effort creates ripples of hope.
        </p>

        <!-- CTA Buttons -->
        <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
            <button 
                type="button" 
                @click="$store.ngoApp.openVolunteer('volunteer')"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-bold text-slate-900 bg-[#F59E0B] hover:bg-[#D97706] hover:text-white rounded-xl shadow-lg transition-all transform hover:-translate-y-0.5 cursor-pointer"
            >
                <i data-lucide="hand-heart" class="w-5 h-5"></i>
                <span>Become a Volunteer</span>
            </button>
            
            <button 
                type="button" 
                @click="$store.ngoApp.openVolunteer('member')"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-bold text-white bg-white/15 hover:bg-white/25 backdrop-blur-md border border-white/30 rounded-xl transition-all cursor-pointer"
            >
                <i data-lucide="users" class="w-5 h-5"></i>
                <span>Join as a Member</span>
            </button>
        </div>

        <!-- Quick Summary Footnote -->
        <div class="pt-2 text-xs text-green-200">
            <span>No long-term commitment required • Open to students, professionals & senior citizens</span>
        </div>

    </div>
</section>
