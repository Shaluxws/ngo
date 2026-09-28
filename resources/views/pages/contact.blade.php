<x-layouts.app :title="$title" :ngo="$ngo" :seo="$seo">

    @include('components.header')

    <main id="main-content">
        <!-- Page Hero -->
        <section class="relative bg-gradient-to-b from-[#DCFCE7]/40 via-white to-[#F8FAF8] pt-16 pb-20 border-b border-[#E5EAE6] overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="max-w-3xl space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold text-[#15803D] bg-white border border-green-200 shadow-2xs reveal" data-reveal>
                        <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                        <span>WE'D LOVE TO HEAR FROM YOU</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#17201B] tracking-tight font-heading leading-tight reveal delay-100" data-reveal>
                        Get in Touch With Our Team
                    </h1>
                    <p class="text-base sm:text-lg text-[#647067] leading-relaxed reveal delay-200" data-reveal>
                        Have a question about our grassroots initiatives, corporate partnership inquiries, or want to volunteer? Reach out and our community coordinators will assist you.
                    </p>
                </div>
            </div>
        </section>

        <!-- Contact Form & Info Split -->
        <section class="py-20 bg-white border-b border-[#E5EAE6]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                @if (session('contact_success'))
                    <div class="mb-10 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-medium flex items-center gap-3">
                        <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                        <span>{{ session('contact_success') }}</span>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                    
                    <!-- Left Column: Form -->
                    <div class="lg:col-span-7 bg-[#F8FAF8] p-8 sm:p-10 rounded-2xl border border-slate-200/80 shadow-xs space-y-6 reveal-fade-left" data-reveal>
                        <div class="space-y-1">
                            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 font-heading">Send Us a Direct Message</h2>
                            <p class="text-xs text-slate-500">Fill out the form below and we will get back to you promptly.</p>
                        </div>

                        <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Your Full Name <span class="text-rose-500">*</span></label>
                                    <input type="text" name="name" required value="{{ old('name') }}" placeholder="e.g. Ramesh Kumar" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-emerald-600/20 focus:border-[#15803D]">
                                    @error('name') <span class="text-rose-600 text-[11px] block mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Email Address <span class="text-rose-500">*</span></label>
                                    <input type="email" name="email" required value="{{ old('email') }}" placeholder="e.g. ramesh@example.com" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-emerald-600/20 focus:border-[#15803D]">
                                    @error('email') <span class="text-rose-600 text-[11px] block mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Phone Number (Optional)</label>
                                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="e.g. +91 98400 12345" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-emerald-600/20 focus:border-[#15803D]">
                                    @error('phone') <span class="text-rose-600 text-[11px] block mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Subject / Inquiry Type <span class="text-rose-500">*</span></label>
                                    <input type="text" name="subject" required value="{{ old('subject') }}" placeholder="e.g. CSR Partnership / Volunteer Question" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-emerald-600/20 focus:border-[#15803D]">
                                    @error('subject') <span class="text-rose-600 text-[11px] block mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Your Message <span class="text-rose-500">*</span></label>
                                <textarea name="message" rows="5" required placeholder="How can we collaborate or assist your community?" class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-emerald-600/20 focus:border-[#15803D]">{{ old('message') }}</textarea>
                                @error('message') <span class="text-rose-600 text-[11px] block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <button type="submit" class="px-6 py-3 rounded-xl text-xs font-bold text-white bg-[#15803D] hover:bg-[#166534] shadow-sm shadow-green-900/20 transition-all flex items-center gap-2">
                                <i data-lucide="send" class="w-4 h-4"></i>
                                <span>Send Message</span>
                            </button>
                        </form>
                    </div>

                    <!-- Right Column: Contact Details & Office -->
                    <div class="lg:col-span-5 space-y-6 reveal-fade-right" data-reveal>
                        <div class="space-y-4">
                            <span class="text-xs font-bold text-[#15803D] uppercase tracking-wider block">CONTACT DETAILS</span>
                            <h2 class="text-2xl font-bold text-slate-900 font-heading">Headquarters & Reach</h2>
                        </div>

                        <div class="space-y-4 text-xs">
                            <div class="p-5 rounded-2xl bg-[#F8FAF8] border border-slate-200 flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-green-100 text-[#15803D] flex items-center justify-center shrink-0">
                                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-900 text-sm">Trust Secretariat Address</h3>
                                    <p class="text-slate-600 mt-1 leading-relaxed">{{ $ngo['address'] ?? '42, Gandhiji Road, RS Puram, Coimbatore, Tamil Nadu - 641002' }}</p>
                                </div>
                            </div>

                            <div class="p-5 rounded-2xl bg-[#F8FAF8] border border-slate-200 flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-green-100 text-[#15803D] flex items-center justify-center shrink-0">
                                    <i data-lucide="phone" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-900 text-sm">Official Helpline</h3>
                                    <a href="tel:{{ preg_replace('/[^\+\d]/', '', $ngo['phone']) }}" class="text-[#15803D] font-bold block mt-1 hover:underline text-sm">{{ $ngo['phone'] }}</a>
                                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $ngo['operating_hours'] ?? 'Mon - Sat: 9:00 AM - 6:00 PM' }}</p>
                                </div>
                            </div>

                            <div class="p-5 rounded-2xl bg-[#F8FAF8] border border-slate-200 flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-green-100 text-[#15803D] flex items-center justify-center shrink-0">
                                    <i data-lucide="mail" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-900 text-sm">Email Coordination</h3>
                                    <a href="mailto:{{ $ngo['email'] }}" class="text-[#15803D] font-bold block mt-1 hover:underline text-sm">{{ $ngo['email'] }}</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- FAQs Section -->
        @if (count($faqs) > 0)
            <section class="py-20 bg-[#F8FAF8] border-b border-[#E5EAE6]">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
                    <div class="text-center space-y-2 reveal" data-reveal>
                        <span class="text-xs font-bold text-[#15803D] uppercase tracking-wider">COMMON INQUIRIES</span>
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 font-heading">Frequently Asked Questions</h2>
                    </div>

                    <div class="space-y-3" x-data="{ openFaq: null }">
                        @foreach ($faqs as $idx => $faq)
                            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-2xs reveal" data-reveal>
                                <button type="button" 
                                        @click="openFaq = (openFaq === {{ $idx }} ? null : {{ $idx }})" 
                                        class="w-full p-5 text-left flex items-center justify-between gap-4 font-bold text-sm text-slate-900 hover:text-[#15803D] transition-colors">
                                    <span>{{ $faq['q'] }}</span>
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transform transition-transform" :class="openFaq === {{ $idx }} ? 'rotate-180 text-[#15803D]' : ''"></i>
                                </button>
                                <div x-show="openFaq === {{ $idx }}" x-collapse class="px-5 pb-5 pt-1 text-xs sm:text-sm text-slate-600 border-t border-slate-100 leading-relaxed" style="display: none;">
                                    {{ $faq['a'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </main>

    @include('components.footer')

</x-layouts.app>
