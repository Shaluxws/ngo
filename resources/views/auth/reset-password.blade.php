<x-layouts.guest title="Choose New Password | Tamil Nadu NGO Management Platform">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center">
            <a href="{{ route('home') }}" class="group flex items-center gap-3 transition-transform duration-200 hover:scale-105">
                <div class="w-13 h-13 rounded-2xl bg-gradient-to-tr from-emerald-800 to-teal-600 flex items-center justify-center text-white shadow-lg shadow-emerald-900/20 ring-4 ring-emerald-100">
                    <i data-lucide="shield-alert" class="w-7 h-7"></i>
                </div>
            </a>
        </div>

        <h2 class="mt-6 text-center text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 font-heading">
            Choose New Password
        </h2>
        <p class="mt-2 text-center text-sm text-slate-600">
            Create a strong, secure password for your account
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0">
        <div class="bg-white py-8 px-6 sm:px-10 shadow-xl shadow-slate-200/50 rounded-2xl border border-slate-100">
            
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

            <form action="{{ route('password.update') }}" method="POST" class="space-y-5" x-data="{ isSubmitting: false, showPass: false }" @submit="isSubmitting = true">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

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
                               value="{{ old('email', $email) }}"
                               class="block w-full rounded-xl pl-10 pr-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 text-slate-900 transition-all">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-700">
                        New Password <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1.5 relative rounded-xl shadow-xs">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </div>
                        <input id="password" 
                               name="password" 
                               :type="showPass ? 'text' : 'password'" 
                               required 
                               placeholder="Minimum 8 characters"
                               class="block w-full rounded-xl pl-10 pr-10 py-2.5 text-sm bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 text-slate-900 transition-all">
                        <button type="button" 
                                @click="showPass = !showPass" 
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none"
                                tabindex="-1">
                            <i x-show="!showPass" data-lucide="eye" class="w-4 h-4"></i>
                            <i x-show="showPass" data-lucide="eye-off" class="w-4 h-4" style="display: none;"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-slate-700">
                        Confirm New Password <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1.5 relative rounded-xl shadow-xs">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </div>
                        <input id="password_confirmation" 
                               name="password_confirmation" 
                               :type="showPass ? 'text' : 'password'" 
                               required 
                               placeholder="Re-type password"
                               class="block w-full rounded-xl pl-10 pr-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 text-slate-900 transition-all">
                    </div>
                </div>

                <div>
                    <button type="submit" 
                            :disabled="isSubmitting"
                            class="w-full flex justify-center items-center gap-2 py-3 px-4 rounded-xl shadow-lg shadow-emerald-800/20 text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-600 transition-all disabled:opacity-60 disabled:cursor-not-allowed">
                        <span x-show="!isSubmitting" class="flex items-center gap-2">
                            <span>Update Password</span>
                            <i data-lucide="check" class="w-4 h-4"></i>
                        </span>
                        <span x-show="isSubmitting" style="display: none;" class="flex items-center gap-2">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Updating...</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.guest>
