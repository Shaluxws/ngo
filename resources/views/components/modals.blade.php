<!-- Global Reusable Prototype Modals (Alpine.js Powered) -->

<!-- 1. DONATE PROTOTYPE MODAL -->
<div 
    x-show="$store.ngoApp.donateModalOpen"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
    style="display: none;"
    @keydown.escape.window="$store.ngoApp.closeDonate()"
>
    <div 
        @click.outside="$store.ngoApp.closeDonate()"
        x-data="{
            frequency: 'one-time',
            customAmt: '',
            paymentMethod: 'upi',
            donorName: '',
            donorEmail: '',
            donorPan: '',
            isSuccess: false,
            processDemo() {
                this.isSuccess = true;
            },
            resetModal() {
                this.isSuccess = false;
                $store.ngoApp.closeDonate();
            }
        }"
        class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-100 relative max-h-[90vh] overflow-y-auto"
    >
        <!-- Close Button -->
        <button 
            type="button" 
            @click="resetModal()"
            class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 p-1.5 rounded-full hover:bg-slate-100"
            aria-label="Close"
        >
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>

        <template x-if="!isSuccess">
            <div class="space-y-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center text-[#15803D]">
                        <i data-lucide="heart" class="w-5 h-5 fill-[#15803D]"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-[#17201B] font-heading">
                            Make a Contribution
                        </h3>
                        <p class="text-xs text-[#647067]">
                            Support Tamil Nadu community initiatives with 80G tax benefit
                        </p>
                    </div>
                </div>

                <!-- Frequency Selection -->
                <div class="grid grid-cols-2 gap-2 bg-slate-100 p-1 rounded-xl">
                    <button 
                        type="button" 
                        @click="frequency = 'one-time'"
                        :class="frequency === 'one-time' ? 'bg-white text-[#15803D] font-bold shadow-xs' : 'text-slate-600 font-medium'"
                        class="py-2 text-xs rounded-lg transition-all"
                    >
                        One-Time Gift
                    </button>
                    <button 
                        type="button" 
                        @click="frequency = 'monthly'"
                        :class="frequency === 'monthly' ? 'bg-white text-[#15803D] font-bold shadow-xs' : 'text-slate-600 font-medium'"
                        class="py-2 text-xs rounded-lg transition-all"
                    >
                        Monthly Sustainer
                    </button>
                </div>

                <!-- Amount Selection -->
                <div>
                    <label class="block text-xs font-bold text-[#17201B] mb-2">
                        Select Amount (INR)
                    </label>
                    <div class="grid grid-cols-4 gap-2">
                        <template x-for="amt in [500, 1000, 2500, 5000]" :key="amt">
                            <button 
                                type="button" 
                                @click="$store.ngoApp.selectedDonationAmount = amt; customAmt = ''"
                                :class="$store.ngoApp.selectedDonationAmount === amt && !customAmt ? 'border-2 border-[#15803D] bg-green-50 text-[#15803D] font-bold' : 'border border-slate-200 bg-white text-slate-700'"
                                class="py-2 text-xs rounded-xl transition-all"
                                x-text="'₹' + amt"
                            ></button>
                        </template>
                    </div>
                    <div class="mt-2.5">
                        <input 
                            type="number" 
                            x-model="customAmt" 
                            @input="$store.ngoApp.selectedDonationAmount = customAmt"
                            placeholder="Or enter custom amount in ₹"
                            class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:border-[#15803D] focus:ring-1 focus:ring-[#15803D]"
                        >
                    </div>
                </div>

                <!-- Donor Details -->
                <div class="space-y-3">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Donor Name *</label>
                            <input type="text" x-model="donorName" placeholder="Full Name" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 focus:border-[#15803D]">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Email *</label>
                            <input type="email" x-model="donorEmail" placeholder="email@domain.com" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 focus:border-[#15803D]">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">PAN Number (For 80G Tax Exemption Certificate)</label>
                        <input type="text" x-model="donorPan" placeholder="ABCDE1234F" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 focus:border-[#15803D] uppercase">
                    </div>
                </div>

                <!-- Payment Method Demonstration -->
                <div>
                    <label class="block text-xs font-bold text-[#17201B] mb-1.5">
                        Simulated Payment Channel (UI Preview)
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        <button 
                            type="button" 
                            @click="paymentMethod = 'upi'"
                            :class="paymentMethod === 'upi' ? 'border-[#15803D] bg-green-50 text-[#15803D] font-bold' : 'border-slate-200 text-slate-600'"
                            class="p-2 border rounded-xl text-center text-xs flex flex-col items-center justify-center gap-1"
                        >
                            <i data-lucide="smartphone" class="w-4 h-4"></i>
                            <span>UPI / GPay</span>
                        </button>
                        <button 
                            type="button" 
                            @click="paymentMethod = 'card'"
                            :class="paymentMethod === 'card' ? 'border-[#15803D] bg-green-50 text-[#15803D] font-bold' : 'border-slate-200 text-slate-600'"
                            class="p-2 border rounded-xl text-center text-xs flex flex-col items-center justify-center gap-1"
                        >
                            <i data-lucide="credit-card" class="w-4 h-4"></i>
                            <span>Cards</span>
                        </button>
                        <button 
                            type="button" 
                            @click="paymentMethod = 'netbanking'"
                            :class="paymentMethod === 'netbanking' ? 'border-[#15803D] bg-green-50 text-[#15803D] font-bold' : 'border-slate-200 text-slate-600'"
                            class="p-2 border rounded-xl text-center text-xs flex flex-col items-center justify-center gap-1"
                        >
                            <i data-lucide="building" class="w-4 h-4"></i>
                            <span>NetBanking</span>
                        </button>
                    </div>
                </div>

                <!-- Prototype Disclaimer -->
                <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-[11px] text-amber-900 flex items-start gap-2">
                    <i data-lucide="info" class="w-4 h-4 text-amber-600 shrink-0 mt-0.5"></i>
                    <span><strong>UI Prototype Only:</strong> In the full Laravel platform, this button initiates the secure Payment Gateway integration and automatically generates official 80G tax receipts.</span>
                </div>

                <!-- Submit Button -->
                <button 
                    type="button" 
                    @click="processDemo()"
                    class="w-full py-3.5 px-4 text-center text-sm font-bold text-white bg-[#15803D] hover:bg-[#166534] rounded-xl shadow-md shadow-green-800/20 cursor-pointer"
                >
                    Proceed to Donate ₹<span x-text="$store.ngoApp.selectedDonationAmount || '1,000'"></span> (Demo UI)
                </button>
            </div>
        </template>

        <!-- Simulated Success State -->
        <template x-if="isSuccess">
            <div class="text-center py-6 space-y-4">
                <div class="w-16 h-16 rounded-full bg-green-100 text-[#15803D] flex items-center justify-center mx-auto">
                    <i data-lucide="check-circle" class="w-10 h-10"></i>
                </div>
                <h3 class="text-2xl font-bold text-[#17201B] font-heading">
                    Thank You for Your Generosity!
                </h3>
                <p class="text-xs text-[#647067] max-w-sm mx-auto leading-relaxed">
                    This completes the donation UI flow demonstration. In production, this screen displays the verified transaction ID, 80G tax exemption PDF download, and WhatsApp receipt dispatch.
                </p>
                <div class="pt-2">
                    <button 
                        type="button" 
                        @click="resetModal()"
                        class="px-6 py-2.5 text-xs font-bold text-white bg-[#15803D] rounded-xl"
                    >
                        Back to Homepage
                    </button>
                </div>
            </div>
        </template>
    </div>
</div>

<!-- 2. VOLUNTEER & MEMBERSHIP PROTOTYPE MODAL -->
<div 
    x-show="$store.ngoApp.volunteerModalOpen"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
    style="display: none;"
    @keydown.escape.window="$store.ngoApp.closeVolunteer()"
>
    <div 
        @click.outside="$store.ngoApp.closeVolunteer()"
        x-data="{
            interestType: 'volunteer',
            district: 'Coimbatore',
            wing: 'Education & Mentorship',
            isSubmitted: false
        }"
        class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-100 relative max-h-[90vh] overflow-y-auto"
    >
        <button 
            type="button" 
            @click="$store.ngoApp.closeVolunteer(); isSubmitted = false;"
            class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 p-1.5 rounded-full hover:bg-slate-100"
            aria-label="Close"
        >
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>

        <template x-if="!isSubmitted">
            <div class="space-y-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-teal-100 flex items-center justify-center text-[#0F766E]">
                        <i data-lucide="users" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-[#17201B] font-heading">
                            Join Our Community
                        </h3>
                        <p class="text-xs text-[#647067]">
                            Register your interest to volunteer, mentor, or become a chapter member
                        </p>
                    </div>
                </div>

                <div class="space-y-3 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Engagement Type</label>
                        <select x-model="interestType" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200">
                            <option value="volunteer">Weekend Volunteer</option>
                            <option value="member">Active Community Member</option>
                            <option value="student-intern">College Student Social Intern</option>
                            <option value="doctor-nurse">Healthcare Professional (Volunteer Camp)</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Your District</label>
                            <select x-model="district" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                                <option value="Coimbatore">Coimbatore</option>
                                <option value="Chennai">Chennai</option>
                                <option value="Erode">Erode</option>
                                <option value="Tiruppur">Tiruppur</option>
                                <option value="Madurai">Madurai</option>
                                <option value="Salem">Salem</option>
                                <option value="Other">Other Tamil Nadu District</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Preferred Wing</label>
                            <select x-model="wing" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                                <option value="Education & Mentorship">Education & Mentorship</option>
                                <option value="Healthcare Camps">Healthcare Camps</option>
                                <option value="Afforestation & Environment">Afforestation & Environment</option>
                                <option value="Women SHG Livelihoods">Women SHG Livelihoods</option>
                                <option value="Youth Leadership">Youth Leadership</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Full Name</label>
                            <input type="text" placeholder="Your Name" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Phone Number</label>
                            <input type="tel" placeholder="+91 98765 43210" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Email Address</label>
                        <input type="email" placeholder="you@domain.com" class="w-full px-3 py-2 rounded-xl border border-slate-200">
                    </div>
                </div>

                <div class="p-3 bg-green-50 rounded-xl border border-green-200 text-[11px] text-green-900">
                    🌿 <strong>No fees or dues required:</strong> Our volunteer community is open to everyone wanting to contribute their skills and time.
                </div>

                <button 
                    type="button" 
                    @click="isSubmitted = true"
                    class="w-full py-3.5 px-4 text-center text-sm font-bold text-white bg-[#15803D] hover:bg-[#166534] rounded-xl shadow-md cursor-pointer"
                >
                    Submit Volunteer Expression (Demo UI)
                </button>
            </div>
        </template>

        <template x-if="isSubmitted">
            <div class="text-center py-6 space-y-4">
                <div class="w-16 h-16 rounded-full bg-teal-100 text-[#0F766E] flex items-center justify-center mx-auto">
                    <i data-lucide="check" class="w-8 h-8"></i>
                </div>
                <h3 class="text-2xl font-bold text-[#17201B] font-heading">
                    Welcome to the Nanban Family!
                </h3>
                <p class="text-xs text-[#647067] max-w-sm mx-auto leading-relaxed">
                    Your volunteer registration has been recorded in this UI prototype. In the complete Laravel platform, your record will be linked to your district volunteer coordinator.
                </p>
                <div class="pt-2">
                    <button 
                        type="button" 
                        @click="$store.ngoApp.closeVolunteer(); isSubmitted = false;"
                        class="px-6 py-2.5 text-xs font-bold text-white bg-[#15803D] rounded-xl"
                    >
                        Close
                    </button>
                </div>
            </div>
        </template>
    </div>
</div>

<!-- 3. PORTAL ACCESS DEMO NOTICE MODAL -->
<div 
    x-show="$store.ngoApp.loginNoticeOpen"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
    style="display: none;"
    @keydown.escape.window="$store.ngoApp.closeLoginNotice()"
>
    <div 
        @click.outside="$store.ngoApp.closeLoginNotice()"
        class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-slate-100 text-center space-y-4"
    >
        <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center mx-auto">
            <i data-lucide="lock" class="w-7 h-7"></i>
        </div>
        <h3 class="text-xl font-bold text-[#17201B] font-heading">
            Future NGO Management Portal
        </h3>
        <p class="text-xs text-[#647067] leading-relaxed">
            In subsequent phases of the project, this portal login will provide role-based authentication for:
        </p>
        <div class="grid grid-cols-2 gap-2 text-left text-xs text-slate-700 bg-slate-50 p-3 rounded-xl border border-slate-200">
            <div class="flex items-center gap-1.5"><i data-lucide="check" class="w-3.5 h-3.5 text-green-600"></i> Super Admin</div>
            <div class="flex items-center gap-1.5"><i data-lucide="check" class="w-3.5 h-3.5 text-green-600"></i> District Admins</div>
            <div class="flex items-center gap-1.5"><i data-lucide="check" class="w-3.5 h-3.5 text-green-600"></i> Overall Leaders</div>
            <div class="flex items-center gap-1.5"><i data-lucide="check" class="w-3.5 h-3.5 text-green-600"></i> Community Leaders</div>
            <div class="flex items-center gap-1.5"><i data-lucide="check" class="w-3.5 h-3.5 text-green-600"></i> Active Volunteers</div>
            <div class="flex items-center gap-1.5"><i data-lucide="check" class="w-3.5 h-3.5 text-green-600"></i> Registered Members</div>
        </div>
        <p class="text-[11px] text-amber-800 bg-amber-50 p-2.5 rounded-lg border border-amber-200">
            Current status: Public Homepage UI Review & Confirmation Phase only.
        </p>
        <button 
            type="button" 
            @click="$store.ngoApp.closeLoginNotice()"
            class="w-full py-2.5 px-4 text-xs font-bold text-white bg-[#15803D] hover:bg-[#166534] rounded-xl"
        >
            Got It
        </button>
    </div>
</div>

<!-- 4. GALLERY LIGHTBOX MODAL -->
<div 
    x-show="$store.ngoApp.lightboxImage !== null"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md flex items-center justify-center p-4"
    style="display: none;"
    @keydown.escape.window="$store.ngoApp.closeLightbox()"
>
    <div class="relative max-w-4xl w-full flex flex-col items-center" @click.outside="$store.ngoApp.closeLightbox()">
        <button 
            type="button" 
            @click="$store.ngoApp.closeLightbox()"
            class="self-end sm:absolute sm:-top-12 sm:right-0 mb-2 sm:mb-0 text-white hover:text-amber-300 p-2 min-w-[44px] min-h-[44px] flex items-center justify-center cursor-pointer"
            aria-label="Close image preview"
        >
            <i data-lucide="x" class="w-6 h-6 sm:w-7 sm:h-7"></i>
        </button>
        <div class="overflow-hidden rounded-2xl bg-black max-h-[75vh] sm:max-h-[80vh] flex items-center justify-center w-full">
            <img :src="$store.ngoApp.lightboxImage" :alt="$store.ngoApp.lightboxTitle" class="max-h-[75vh] sm:max-h-[80vh] w-auto max-w-full object-contain rounded-xl">
        </div>
        <div class="mt-3 text-center text-white font-medium text-xs sm:text-sm px-4" x-text="$store.ngoApp.lightboxTitle"></div>
    </div>
</div>
