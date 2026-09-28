<section id="contact" class="py-16 md:py-24 bg-[#F8FAF8] border-b border-[#E5EAE6]" x-data="{
    formSubmitted: false,
    formData: { name: '', email: '', phone: '', subject: 'General Enquiry', message: '' },
    activeFaq: 0,
    submitForm() {
        if(!this.formData.name || !this.formData.email || !this.formData.message) {
            alert('Please complete the required fields (Name, Email, and Message).');
            return;
        }
        this.formSubmitted = true;
    }
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-14">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#DCFCE7] text-[#15803D] text-xs font-bold uppercase tracking-wider">
                REACH OUT TO US
            </span>
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#17201B] font-heading tracking-tight mt-3">
                Let's Connect & Build Together
            </h2>
            <p class="text-base text-[#647067] mt-3">
                Have questions regarding our community programs, donations, or volunteering? Reach our team in Coimbatore.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-start">
            
            <!-- Left Column: Contact Cards & FAQs -->
            <div class="lg:col-span-5 space-y-6">
                
                <!-- Contact Info Card -->
                <div class="bg-white rounded-2xl p-6 border border-[#E5EAE6] shadow-xs space-y-5">
                    <h3 class="text-lg font-bold text-[#17201B] font-heading border-b border-slate-100 pb-3">
                        Registered Office
                    </h3>

                    <div class="space-y-4 text-xs sm:text-sm text-[#647067]">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center text-[#15803D] shrink-0">
                                <i data-lucide="map-pin" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <strong class="block text-[#17201B] font-semibold">Address</strong>
                                <span>{{ $ngo['address'] ?? '42, Gandhiji Road, RS Puram, Coimbatore, Tamil Nadu - 641002' }}</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center text-[#15803D] shrink-0">
                                <i data-lucide="phone" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <strong class="block text-[#17201B] font-semibold">Phone Helpline</strong>
                                <a href="tel:{{ $ngo['phone'] }}" class="text-[#15803D] hover:underline">{{ $ngo['phone'] }}</a>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center text-[#15803D] shrink-0">
                                <i data-lucide="mail" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <strong class="block text-[#17201B] font-semibold">Email</strong>
                                <a href="mailto:{{ $ngo['email'] }}" class="text-[#15803D] hover:underline">{{ $ngo['email'] }}</a>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center text-[#15803D] shrink-0">
                                <i data-lucide="clock" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <strong class="block text-[#17201B] font-semibold">Office Timings</strong>
                                <span>{{ $ngo['operating_hours'] ?? 'Mon - Sat: 9:00 AM - 6:00 PM' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Frequently Asked Questions Accordion -->
                <div class="bg-white rounded-2xl p-6 border border-[#E5EAE6] shadow-xs space-y-4">
                    <h3 class="text-base font-bold text-[#17201B] font-heading flex items-center gap-2">
                        <i data-lucide="help-circle" class="w-4 h-4 text-[#15803D]"></i>
                        <span>Frequently Asked Questions</span>
                    </h3>

                    <div class="space-y-2">
                        @foreach($faqs as $index => $faq)
                            <div class="border border-slate-100 rounded-xl overflow-hidden">
                                <button 
                                    type="button"
                                    @click="activeFaq = (activeFaq === {{ $index }} ? null : {{ $index }})"
                                    class="w-full text-left p-3.5 text-xs font-bold text-[#17201B] hover:text-[#15803D] flex items-center justify-between gap-2 bg-[#F8FAF8]"
                                >
                                    <span>{{ $faq['q'] }}</span>
                                    <i data-lucide="chevron-down" class="w-4 h-4 shrink-0 transition-transform duration-200" :class="activeFaq === {{ $index }} ? 'rotate-180' : ''"></i>
                                </button>
                                <div 
                                    x-show="activeFaq === {{ $index }}" 
                                    x-transition:enter="transition ease-out duration-150"
                                    x-transition:enter-start="opacity-0 -translate-y-1"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    class="p-3.5 text-xs text-[#647067] leading-relaxed bg-white border-t border-slate-100"
                                    style="display: none;"
                                >
                                    {{ $faq['a'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Right Column: Interactive UI Contact Form -->
            <div class="lg:col-span-7">
                <div class="bg-white rounded-3xl p-6 sm:p-8 md:p-10 border border-[#E5EAE6] shadow-sm">
                    
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-[#17201B] font-heading">
                            Send Us a Message
                        </h3>
                        <p class="text-xs sm:text-sm text-[#647067] mt-1">
                            Fill out the form below. For this UI confirmation, frontend demo validation is demonstrated.
                        </p>
                    </div>

                    <!-- Success State Notice (Demo) -->
                    <div 
                        x-show="formSubmitted" 
                        class="p-6 rounded-2xl bg-[#DCFCE7] border border-green-300 text-center space-y-2 mb-6"
                        style="display: none;"
                    >
                        <div class="w-12 h-12 rounded-full bg-[#15803D] text-white flex items-center justify-center mx-auto">
                            <i data-lucide="check" class="w-6 h-6"></i>
                        </div>
                        <h4 class="text-base font-bold text-[#15803D] font-heading">
                            Thank You for Reaching Out!
                        </h4>
                        <p class="text-xs text-green-900">
                            (UI Demo Mode: Message received. In the production Laravel system, this will dispatch notification emails & store audit records).
                        </p>
                        <button 
                            type="button" 
                            @click="formSubmitted = false" 
                            class="text-xs font-semibold text-[#15803D] underline mt-2"
                        >
                            Send another message
                        </button>
                    </div>

                    <!-- Main Form Fields -->
                    <form x-show="!formSubmitted" @submit.prevent="submitForm" class="space-y-4">
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="contact-name" class="block text-xs font-bold text-[#17201B] mb-1.5">
                                    Full Name <span class="text-rose-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="contact-name" 
                                    x-model="formData.name" 
                                    placeholder="e.g. Ramesh Kumar"
                                    required 
                                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-[#E5EAE6] focus:border-[#15803D] focus:ring-1 focus:ring-[#15803D] bg-[#F8FAF8] focus:bg-white transition-colors"
                                >
                            </div>

                            <div>
                                <label for="contact-email" class="block text-xs font-bold text-[#17201B] mb-1.5">
                                    Email Address <span class="text-rose-500">*</span>
                                </label>
                                <input 
                                    type="email" 
                                    id="contact-email" 
                                    x-model="formData.email" 
                                    placeholder="e.g. ramesh@example.com"
                                    required 
                                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-[#E5EAE6] focus:border-[#15803D] focus:ring-1 focus:ring-[#15803D] bg-[#F8FAF8] focus:bg-white transition-colors"
                                >
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="contact-phone" class="block text-xs font-bold text-[#17201B] mb-1.5">
                                    Phone Number (Optional)
                                </label>
                                <input 
                                    type="tel" 
                                    id="contact-phone" 
                                    x-model="formData.phone" 
                                    placeholder="+91 98765 43210"
                                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-[#E5EAE6] focus:border-[#15803D] focus:ring-1 focus:ring-[#15803D] bg-[#F8FAF8] focus:bg-white transition-colors"
                                >
                            </div>

                            <div>
                                <label for="contact-subject" class="block text-xs font-bold text-[#17201B] mb-1.5">
                                    Topic of Interest
                                </label>
                                <select 
                                    id="contact-subject" 
                                    x-model="formData.subject"
                                    class="w-full px-4 py-2.5 text-sm rounded-xl border border-[#E5EAE6] focus:border-[#15803D] focus:ring-1 focus:ring-[#15803D] bg-[#F8FAF8] focus:bg-white transition-colors"
                                >
                                    <option value="General Enquiry">General Enquiry</option>
                                    <option value="Volunteering">Volunteering Opportunities</option>
                                    <option value="Donations & 80G">Donations & 80G Tax Exemption</option>
                                    <option value="Community Chapter">Start a Local Chapter</option>
                                    <option value="CSR Partnership">Corporate CSR Collaboration</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="contact-message" class="block text-xs font-bold text-[#17201B] mb-1.5">
                                Message <span class="text-rose-500">*</span>
                            </label>
                            <textarea 
                                id="contact-message" 
                                x-model="formData.message" 
                                rows="4" 
                                placeholder="How can we help you or collaborate together?"
                                required 
                                class="w-full px-4 py-2.5 text-sm rounded-xl border border-[#E5EAE6] focus:border-[#15803D] focus:ring-1 focus:ring-[#15803D] bg-[#F8FAF8] focus:bg-white transition-colors"
                            ></textarea>
                        </div>

                        <div class="pt-2">
                            <button 
                                type="submit" 
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 text-sm font-bold text-white bg-[#15803D] hover:bg-[#166534] rounded-xl shadow-md shadow-green-800/20 transition-all cursor-pointer"
                            >
                                <i data-lucide="send" class="w-4 h-4"></i>
                                <span>Send Message (Demo UI)</span>
                            </button>
                        </div>

                        <p class="text-[11px] text-[#647067] text-center sm:text-left pt-1">
                            🔒 Your personal information is kept strictly private in accordance with our NGO Privacy Policy.
                        </p>

                    </form>

                </div>
            </div>

        </div>

    </div>
</section>
