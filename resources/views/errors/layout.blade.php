<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | {{ \App\Models\SiteSetting::get('org_name', 'Nanban Social Foundation') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ \App\Models\SiteSetting::get('favicon_url') ?: asset('favicon.ico') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Dynamic Theme CSS -->
    <style>
        {!! \App\Models\ThemeSetting::generateCssVariables() !!}
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8FAF8] text-[#17201B] font-sans antialiased min-h-screen flex flex-col justify-between">
    
    <!-- Minimal Header -->
    <header class="py-6 px-4 sm:px-6 lg:px-8 border-b border-gray-200/80 bg-white/80 backdrop-blur-md">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                @php
                    $logo = \App\Models\SiteSetting::get('logo_url');
                @endphp
                @if ($logo)
                    <img src="{{ $logo }}" alt="{{ \App\Models\SiteSetting::get('org_name', 'Nanban Social Foundation') }}" class="h-10 w-auto object-contain">
                @else
                    <div class="w-10 h-10 rounded-xl bg-emerald-700 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                        NSF
                    </div>
                @endif
                <div>
                    <span class="text-base font-bold text-slate-900 tracking-tight block">{{ \App\Models\SiteSetting::get('org_name', 'Nanban Social Foundation') }}</span>
                    <span class="text-xs text-slate-500 block">Tamil Nadu Community Service</span>
                </div>
            </a>

            <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium text-emerald-800 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition-colors">
                <i data-lucide="home" class="w-4 h-4"></i>
                <span>Return Home</span>
            </a>
        </div>
    </header>

    <!-- Error Content -->
    <main class="flex-1 flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-700 mb-6 shadow-sm">
                @yield('icon')
            </div>
            
            <div class="inline-block px-3 py-1 rounded-full text-xs font-bold tracking-wider uppercase mb-3 @yield('badge_class', 'bg-emerald-100 text-emerald-800')">
                @yield('code')
            </div>

            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 font-display mb-3">
                @yield('message')
            </h1>

            <p class="text-sm text-slate-600 mb-8 leading-relaxed">
                @yield('description')
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ url('/') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-700 text-white font-medium hover:bg-emerald-800 shadow-sm transition-all text-sm">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    <span>Back to Homepage</span>
                </a>
                <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 font-medium hover:bg-slate-50 transition-all text-sm">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                    <span>Portal Login</span>
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-6 border-t border-gray-200 bg-white text-center text-xs text-slate-500">
        <p>&copy; {{ date('Y') }} {{ \App\Models\SiteSetting::get('org_name', 'Nanban Social Foundation') }}. All rights reserved.</p>
    </footer>
</body>
</html>
