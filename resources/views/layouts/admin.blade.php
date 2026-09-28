<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin Portal' }} | {{ config('app.name', 'Tamil Nadu NGO Platform') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full font-sans text-slate-800 antialiased selection:bg-emerald-600 selection:text-white"
      x-data="{ 
          sidebarOpen: false, 
          profileMenuOpen: false,
          confirmModal: {
              open: false,
              title: '',
              message: '',
              actionCallback: null,
              confirmText: 'Confirm',
              cancelText: 'Cancel',
              isDestructive: true
          },
          openConfirm(title, message, callback, confirmText = 'Delete', isDestructive = true) {
              this.confirmModal.title = title;
              this.confirmModal.message = message;
              this.confirmModal.actionCallback = callback;
              this.confirmModal.confirmText = confirmText;
              this.confirmModal.isDestructive = isDestructive;
              this.confirmModal.open = true;
          },
          executeConfirm() {
              if (this.confirmModal.actionCallback) {
                  this.confirmModal.actionCallback();
              }
              this.confirmModal.open = false;
          }
      }">

    <div class="min-h-full flex flex-col">
        
        <!-- Mobile Sidebar Drawer Overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-40 lg:hidden"
             @click="sidebarOpen = false"
             style="display: none;">
        </div>

        <!-- Mobile Sidebar Drawer -->
        <div x-show="sidebarOpen"
             x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="fixed inset-y-0 left-0 flex flex-col z-50 w-72 bg-slate-900 text-white shadow-2xl lg:hidden"
             style="display: none;">
            
            <div class="flex items-center justify-between h-16 px-6 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white shadow-md">
                        <i data-lucide="heart-handshake" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="font-bold text-sm text-white tracking-tight">NANBAN NGO</span>
                        <span class="block text-[10px] text-emerald-400 font-medium">Admin Management</span>
                    </div>
                </div>
                <button type="button" @click="sidebarOpen = false" class="text-slate-400 hover:text-white p-1">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-4 py-5 space-y-6">
                @include('layouts.admin-nav')
            </div>

            <div class="p-4 border-t border-slate-800 bg-slate-950/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-emerald-700/50 flex items-center justify-center text-emerald-300 font-bold text-xs ring-2 ring-emerald-500/30">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-emerald-400 truncate">{{ auth()->user()->roles->pluck('name')->first() ?? 'User' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop Layout Container -->
        <div class="flex-1 flex overflow-hidden">
            
            <!-- Desktop Fixed Sidebar -->
            <aside class="hidden lg:flex lg:flex-col lg:w-64 bg-slate-900 text-slate-300 border-r border-slate-800 shrink-0">
                <div class="flex items-center gap-3 h-16 px-6 border-b border-slate-800/80 bg-slate-950/40">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white shadow-md shadow-emerald-950/50 ring-2 ring-emerald-500/20">
                        <i data-lucide="heart-handshake" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="font-bold text-sm text-white tracking-tight">NANBAN NGO</span>
                        <span class="block text-[10px] text-emerald-400 font-semibold tracking-wider uppercase">Admin Portal</span>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto px-4 py-5 space-y-6 scrollbar-thin">
                    @include('layouts.admin-nav')
                </div>

                <div class="p-4 border-t border-slate-800/80 bg-slate-950/40">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-2 text-xs font-medium text-slate-400 hover:text-emerald-400 transition-colors">
                            <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                            <span>View Public Site</span>
                        </a>
                        <span class="inline-flex items-center gap-1 text-[10px] text-emerald-400 font-medium bg-emerald-950/60 px-2 py-0.5 rounded-full border border-emerald-800/50">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>v2.0 Live</span>
                        </span>
                    </div>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
                
                <header class="h-16 bg-white border-b border-slate-200/80 px-4 sm:px-6 lg:px-8 flex items-center justify-between sticky top-0 z-30 shadow-xs">
                    <div class="flex items-center gap-3">
                        <button type="button" @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100">
                            <i data-lucide="menu" class="w-5 h-5"></i>
                        </button>
                        
                        <div class="hidden sm:flex items-center gap-2 text-xs text-slate-500">
                            <span class="font-medium text-slate-700">Tamil Nadu NGO Management</span>
                            <span class="text-slate-300">•</span>
                            <span class="text-slate-500">{{ now()->format('l, d M Y') }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 sm:gap-4">
                        <a href="{{ route('home') }}" target="_blank" class="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-slate-600 hover:text-emerald-700 hover:bg-emerald-50 border border-slate-200 transition-colors">
                            <i data-lucide="globe" class="w-3.5 h-3.5 text-emerald-600"></i>
                            <span>Public Website</span>
                        </a>

                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button type="button" 
                                    @click="open = !open" 
                                    class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-slate-100 transition-colors focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-emerald-700 text-white flex items-center justify-center font-bold text-xs ring-2 ring-emerald-200 shadow-xs">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                                </div>
                                <div class="hidden md:block text-left">
                                    <p class="text-xs font-semibold text-slate-800 leading-tight">{{ auth()->user()->name }}</p>
                                    <p class="text-[10px] text-emerald-700 font-medium leading-tight">{{ auth()->user()->roles->pluck('name')->first() ?? 'Admin' }}</p>
                                </div>
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-400"></i>
                            </button>

                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-56 rounded-xl bg-white shadow-xl shadow-slate-200/60 border border-slate-100 py-1.5 z-50 text-xs"
                                 style="display: none;">
                                
                                <div class="px-4 py-2 border-b border-slate-100">
                                    <p class="font-semibold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-[11px] text-slate-500 truncate">{{ auth()->user()->email }}</p>
                                </div>

                                @can('users.edit')
                                    <a href="{{ route('admin.users') }}" class="flex items-center gap-2.5 px-4 py-2 text-slate-700 hover:bg-slate-50 hover:text-emerald-700 transition-colors">
                                        <i data-lucide="user" class="w-4 h-4 text-slate-400"></i>
                                        <span>User Management</span>
                                    </a>
                                @endcan

                                @can('audit_logs.view')
                                    <a href="{{ route('admin.security.audit-logs') }}" class="flex items-center gap-2.5 px-4 py-2 text-slate-700 hover:bg-slate-50 hover:text-emerald-700 transition-colors">
                                        <i data-lucide="shield" class="w-4 h-4 text-slate-400"></i>
                                        <span>Security & Audit Logs</span>
                                    </a>
                                @endcan

                                <div class="border-t border-slate-100 my-1"></div>

                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center gap-2.5 px-4 py-2 text-red-600 hover:bg-red-50 transition-colors font-medium">
                                        <i data-lucide="log-out" class="w-4 h-4 text-red-500"></i>
                                        <span>Sign Out</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <div class="px-4 sm:px-6 lg:px-8 pt-4">
                    @if (session('success'))
                        <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 mb-4 flex items-center justify-between text-sm text-emerald-800 shadow-xs" x-data="{ show: true }" x-show="show">
                            <div class="flex items-center gap-3">
                                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                                <span>{{ session('success') }}</span>
                            </div>
                            <button @click="show = false" class="text-emerald-600 hover:text-emerald-800"><i data-lucide="x" class="w-4 h-4"></i></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="rounded-xl bg-red-50 border border-red-200 p-4 mb-4 flex items-center justify-between text-sm text-red-800 shadow-xs" x-data="{ show: true }" x-show="show">
                            <div class="flex items-center gap-3">
                                <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600 shrink-0"></i>
                                <span>{{ session('error') }}</span>
                            </div>
                            <button @click="show = false" class="text-red-600 hover:text-red-800"><i data-lucide="x" class="w-4 h-4"></i></button>
                        </div>
                    @endif
                </div>

                <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6">
                    {{ $slot }}
                </main>

                <footer class="mt-auto border-t border-slate-200/80 bg-white py-4 px-4 sm:px-6 lg:px-8 text-center text-xs text-slate-500">
                    <span>ShanLux IT Services — Tamil Nadu NGO Management Platform &copy; 2026. All Rights Reserved.</span>
                </footer>
            </div>
        </div>

        <!-- Accessible Alpine Confirmation Modal -->
        <div x-show="confirmModal.open" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;"
             @keydown.escape.window="confirmModal.open = false">
            
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100 transform transition-all"
                 @click.outside="confirmModal.open = false">
                <div class="flex items-center gap-3 text-red-600 mb-3" :class="confirmModal.isDestructive ? 'text-red-600' : 'text-emerald-600'">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" :class="confirmModal.isDestructive ? 'bg-red-50 text-red-600' : 'bg-emerald-50 text-emerald-600'">
                        <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-900" x-text="confirmModal.title"></h3>
                </div>
                <p class="text-sm text-slate-600 leading-relaxed" x-text="confirmModal.message"></p>
                
                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button" 
                            @click="confirmModal.open = false" 
                            class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors" 
                            x-text="confirmModal.cancelText">
                    </button>
                    <button type="button" 
                            @click="executeConfirm()" 
                            class="px-4 py-2 rounded-xl text-xs font-semibold text-white transition-all shadow-md"
                            :class="confirmModal.isDestructive ? 'bg-red-600 hover:bg-red-700 shadow-red-600/20' : 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-600/20'"
                            x-text="confirmModal.confirmText">
                    </button>
                </div>
            </div>
        </div>

    </div>

    @livewireScripts
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                window.lucide.createIcons();
            }
        });
        document.addEventListener('livewire:navigated', () => {
            if (window.lucide) {
                window.lucide.createIcons();
            }
        });
    </script>
</body>
</html>
