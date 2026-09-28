<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? ($seo['meta_title'] ?? (($ngo['name'] ?? 'Nanban Social Foundation') . ' | ' . ($ngo['tagline'] ?? 'Building Stronger Communities in Tamil Nadu'))) }}</title>
    <meta name="description" content="{{ $seo['meta_description'] ?? 'Dedicated to grassroots community development, rural education, preventive health, women empowerment, and environmental sustainability across Tamil Nadu.' }}">

    <!-- Open Graph / Meta -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title ?? ($seo['meta_title'] ?? (($ngo['name'] ?? 'Nanban Social Foundation') . ' | Tamil Nadu NGO')) }}">
    <meta property="og:description" content="{{ $seo['meta_description'] ?? 'Building stronger communities through participation, transparency, and sustainable social impact.' }}">
    <meta property="og:image" content="{{ $seo['og_image'] ?? 'https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&w=1200&q=80' }}">

    <!-- Favicon -->
    @php
        $faviconUrl = \App\Models\SiteSetting::get('favicon_url') ?: asset('favicon.ico');
    @endphp
    <link rel="icon" href="{{ $faviconUrl }}">

    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Dynamic Theme CSS Tokens -->
    <style>
        {!! \App\Models\ThemeSetting::generateCssVariables() !!}
    </style>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-[#F8FAF8] text-[#17201B] font-sans antialiased selection:bg-[#DCFCE7] selection:text-[#15803D]" x-data>
    
    <!-- Top Announcement Banner -->
    @if ($ngo['top_bar_enabled'] ?? true)
        @php
            $topBarBg = $ngo['top_bar_background_color'] ?? '#166534';
            $topBarText = $ngo['top_bar_text_color'] ?? '#FFFFFF';
            $topBarBadge = $ngo['top_bar_badge_color'] ?? '#F59E0B';
            $topBarLink = $ngo['top_bar_link_color'] ?? '#FEF08A';
        @endphp
        <div class="text-xs py-2 px-4 border-b border-black/10 transition-colors" style="background-color: {{ $topBarBg }}; color: {{ $topBarText }};">
            <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 text-center sm:text-left">
                <div class="flex items-center gap-2 justify-center">
                    @if (!empty($ngo['top_bar_badge']))
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-semibold text-slate-950 shadow-2xs" style="background-color: {{ $topBarBadge }};">
                            <i data-lucide="sparkles" class="w-3 h-3" aria-hidden="true"></i> <span>{{ $ngo['top_bar_badge'] }}</span>
                        </span>
                    @endif
                    @if (!empty($ngo['top_bar_text']))
                        <span class="opacity-95 hidden md:inline">{{ $ngo['top_bar_text'] }}</span>
                    @endif
                </div>
                <div class="flex items-center gap-4 text-xs justify-center opacity-95">
                    @if (($ngo['phone_enabled'] ?? true) && !empty($ngo['phone']))
                        <a href="tel:{{ preg_replace('/[^\+\d]/', '', $ngo['phone']) }}" aria-label="Call {{ $ngo['phone'] }}" class="hover:opacity-100 hover:underline transition-all inline-flex items-center gap-1" style="color: {{ $topBarText }};">
                            <i data-lucide="phone" class="w-3.5 h-3.5" aria-hidden="true"></i> <span>{{ $ngo['phone'] }}</span>
                        </a>
                    @endif
                    @if (($ngo['phone_enabled'] ?? true) && !empty($ngo['phone']) && ($ngo['email_enabled'] ?? true) && !empty($ngo['email']))
                        <span class="opacity-40 hidden sm:inline" aria-hidden="true">•</span>
                    @endif
                    @if (($ngo['email_enabled'] ?? true) && !empty($ngo['email']))
                        <a href="mailto:{{ $ngo['email'] }}" aria-label="Email {{ $ngo['email'] }}" class="hover:opacity-100 hover:underline transition-all hidden sm:inline-flex items-center gap-1" style="color: {{ $topBarText }};">
                            <i data-lucide="mail" class="w-3.5 h-3.5" aria-hidden="true"></i> <span>{{ $ngo['email'] }}</span>
                        </a>
                    @endif
                    @if ((($ngo['phone_enabled'] ?? true) && !empty($ngo['phone'])) || (($ngo['email_enabled'] ?? true) && !empty($ngo['email'])))
                        <span class="opacity-40 hidden sm:inline" aria-hidden="true">•</span>
                    @endif
                    <a href="{{ route('admin.dashboard') }}" aria-label="Open Admin Dashboard" class="hover:opacity-100 font-semibold inline-flex items-center gap-1 transition-all" style="color: {{ $topBarLink }};">
                        <i data-lucide="layout-dashboard" class="w-3.5 h-3.5" aria-hidden="true"></i> <span>Admin Dashboard</span>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content Flow -->
    {{ $slot }}

    <!-- Reusable Global Interactive UI Modals -->
    @include('components.modals')

    @livewireScripts
</body>
</html>
