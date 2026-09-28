<x-layouts.guest title="Reset Password | Tamil Nadu NGO Management Platform">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center">
            <a href="{{ route('home') }}" class="group flex items-center gap-3 transition-transform duration-200 hover:scale-105">
                <div class="w-13 h-13 rounded-2xl bg-gradient-to-tr from-emerald-800 to-teal-600 flex items-center justify-center text-white shadow-lg shadow-emerald-900/20 ring-4 ring-emerald-100">
                    <i data-lucide="key-round" class="w-7 h-7"></i>
                </div>
            </a>
        </div>

        <h2 class="mt-6 text-center text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 font-heading">
            Forgot Password
        </h2>
        <p class="mt-2 text-center text-sm text-slate-600 px-4">
            Enter your registered account email address and we will email you a secure password reset link.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0">
        <div class="bg-white py-8 px-6 sm:px-10 shadow-xl shadow-slate-200/50 rounded-2xl border border-slate-100">
            
            @if (session('status'))
                <div class="mb-5 rounded-xl bg-emerald-50 border border-emerald-200 p-4 flex items-center gap-3 text-sm text-emerald-800">
                    <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 rounded-xl bg-red-50 border border-red-200 p-4 flex items-start gap-3 text-sm text-red-800">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 shrink-0 mt-0.5"></i>
                    <div class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST" class="space-y-5" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-700">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1.5 relative rounded-xl shadow-xs">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </div>
                        <input id="email" 
                               name="email" 
                               type="email" 
                               autocomplete="email" 
                               required 
                               value="{{ old('email') }}"
                               placeholder="your.email@example.com"
                               class="block w-full rounded-xl pl-10 pr-3.5 py-2.5 text-sm bg-slate-50 border @error('email') border-red-300 ring-1 ring-red-300 bg-red-50/30 @else border-slate-200 @enderror focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 text-slate-900 transition-all placeholder:text-slate-400">
                    </div>
                </div>

                <div>
                    <button type="submit" 
                            :disabled="isSubmitting"
                            class="w-full flex justify-center items-center gap-2 py-3 px-4 rounded-xl shadow-lg shadow-emerald-800/20 text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-600 transition-all disabled:opacity-60 disabled:cursor-not-allowed">
                        <span x-show="!isSubmitting" class="flex items-center gap-2">
                            <span>Email Password Reset Link</span>
                            <i data-lucide="send" class="w-4 h-4"></i>
                        </span>
                        <span x-show="isSubmitting" style="display: none;" class="flex items-center gap-2">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Sending link...</span>
                        </span>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-slate-600 hover:text-emerald-700 font-medium transition-colors">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Back to Login</span>
                </a>
                <a href="{{ route('home') }}" class="text-slate-500 hover:text-emerald-700">
                    Public Website
                </a>
            </div>
        </div>
    </div>
</x-layouts.guest>
