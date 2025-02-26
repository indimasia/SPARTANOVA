<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Spartav</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50 flex flex-col">
        <!-- Header -->
        <header class="fixed top-0 w-full bg-white shadow-sm z-50 transition-all duration-300 border-b border-gray-100">
            <div class="px-4 py-3">
                <div class="flex items-center justify-between">
                    <div class="flex-shrink-0 transition-transform duration-300 hover:scale-105">
                        <img src="{{ asset('images/spartav_logo.png') }}" alt="{{ config('app.name', 'Spartav') }}"
                            class="h-10">
                    </div>

                    <!-- Mobile Menu Button -->
                    @auth
                    <div class="flex items-center space-x-4">
                        @php
                                        $notifications = App\Models\Notification::where('notifiable_type', 'App\Models\Article')
                                        ->whereNotIn('id', function ($query) {
                                            $query->select('notification_id')
                                                ->from('notification_reads')
                                                ->where('user_id', Auth::id());
                                        })
                                        ->get();
                                        $count = $notifications->count();
                                    @endphp
                        <!-- Notifications Dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="text-gray-500 hover:text-gray-900 focus:outline-none">
                                <i class="fas fa-bell text-xl"></i>
                                @if (auth()->user()->unreadNotifications->count() + $count > 0)
                                    <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                                        {{ auth()->user()->unreadNotifications->count() + $count }}
                                    </span>
                                @endif

                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg overflow-hidden z-50" style="display: none;">
                                <div class="py-2">
                                    <div class="px-4 py-2 text-sm font-semibold text-gray-700 border-b">
                                        Notifications
                                    </div>
                                    @php
                                        $notifications = App\Models\Notification::where('notifiable_type', 'App\Models\Article')
                                        ->whereNotIn('id', function ($query) {
                                            $query->select('notification_id')
                                                ->from('notification_reads')
                                                ->where('user_id', Auth::id());
                                        })
                                        ->get();
                                    @endphp
                                    @foreach(auth()->user()->unreadNotifications as $notification)
                                        <a href="{{ route('pasukan.riwayat-pekerjaan') }}" class="flex items-center px-4 py-3 hover:bg-gray-100 transition ease-in-out duration-150">
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $notification->data['message'] }}</div>
                                                <div class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</div>
                                            </div>
                                        </a>
                                    @endforeach
                                
                                    @foreach($notifications as $notification)
                                        @php
                                            $notificationData = json_decode($notification->data, true); // Decode the JSON string to an array
                                        @endphp
                                        <a href="{{ route('articles.read', ['articleId' => $notification->id]) }}" class="flex items-center px-4 py-3 hover:bg-gray-100 transition ease-in-out duration-150">
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $notificationData['message'] }}</div>
                                                <div class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</div>
                                            </div>
                                        </a>
                                    @endforeach
                                    <a href="#" class="block bg-gray-50 text-sm text-center font-medium text-indigo-600 py-2 hover:bg-gray-100 transition ease-in-out duration-150">
                                        View all notifications
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <button id="mobile-menu-button"
                            class="md:hidden text-gray-500 hover:text-gray-900 focus:outline-none">
                            <i id="menu-icon" class="fas fa-bars text-xl"></i>
                        </button>
                    </div>
                    @else
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('login') }}"
                                class="text-sm font-medium text-gray-600 hover:text-yellow-500 transition-colors duration-300">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-300">
                                Daftar
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Mobile Sidebar -->
        @auth
            @if (auth()->user() && !request()->routeIs('register.pasukan'))
            <div id="mobile-sidebar" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 md:hidden hidden"
                aria-modal="true">
                <div
                    class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out translate-x-0">
                    <div class="flex flex-col h-full">
                        <!-- Sidebar Header -->
                        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                            <h2 class="text-lg font-semibold text-gray-800">Menu</h2>
                            <button id="close-sidebar" class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <!-- Navigation -->
                        <nav class="flex-1 overflow-y-auto py-4 bg-white">
                            <a href="{{ route('dashboard') }}"
                                class="flex items-center px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-yellow-500 hover:border-l-2 hover:border-yellow-500 transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-gray-50 text-yellow-500 border-l-2 border-yellow-500' : '' }}">
                                <i
                                    class="fas fa-chart-line text-sm mr-3 {{ request()->routeIs('dashboard') ? 'text-yellow-500' : 'text-gray-400' }}"></i>
                                <span>Dashboard</span>
                            </a>

                            <a href="{{ route('pasukan.apply-job') }}"
                                class="flex items-center px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-yellow-500 hover:border-l-2 hover:border-yellow-500 transition-all duration-150 {{ request()->routeIs('pasukan.apply-job') ? 'bg-gray-50 text-yellow-500 border-l-2 border-yellow-500' : '' }}">
                                <i
                                    class="fas fa-briefcase text-sm mr-3 {{ request()->routeIs('pasukan.apply-job') ? 'text-yellow-500' : 'text-gray-400' }}"></i>
                                <span>Misi</span>
                            </a>

                            <a href="{{ route('misi.progres') }}"
                                class="group flex items-center px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-yellow-700 hover:border-l-2 hover:border-yellow-700 transition-all duration-150 {{ request()->routeIs('misi.progres') ? 'bg-gray-50 text-yellow-700 border-l-2 border-yellow-700' : '' }}">
                                <i
                                    class="fas fa-tasks text-sm mr-3 transition-colors duration-150 {{ request()->routeIs('misi.progres') ? 'text-yellow-700' : 'text-gray-600' }} group-hover:text-yellow-700"></i>
                                <span>Misi yang Diambil</span>
                            </a>
                            @php
                                    $jobApprovedCount = App\Models\Notification::where('notifiable_type', 'App\Models\User')
                                        ->where('type', 'Job Approved')
                                        ->where('read_at', null)
                                        ->get();
                                        $countApproved = $jobApprovedCount->count();
                                @endphp

                                <a href="{{ route('pasukan.riwayat-pekerjaan') }}"
                                class="flex items-center px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-yellow-500 hover:border-l-2 hover:border-yellow-500 transition-all duration-150 {{ request()->routeIs('pasukan.riwayat-pekerjaan') ? 'bg-gray-50 text-yellow-500 border-l-2 border-yellow-500' : '' }}">
                                <i
                                class="fas fa-history text-sm mr-3 {{ request()->routeIs('pasukan.riwayat-pekerjaan') ? 'text-yellow-500' : 'text-gray-400' }}"></i>
                                <span>Laporan Riwayat Misi</span>
                                @if($countApproved > 0)
                                        <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                                            {{ $countApproved }}
                                        </span>
                                    @endif
                            </a>

                            <a href="{{ route('pasukan.profile') }}"
                                    class="group flex items-center px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-yellow-700 hover:border-l-2 hover:border-yellow-700 transition-all duration-150 {{ request()->routeIs('pasukan.profile') ? 'bg-gray-50 text-yellow-700 border-l-2 border-yellow-700' : '' }}">
                                    <i
                                        class="fas fa-user text-sm mr-3 transition-colors duration-150 {{ request()->routeIs('pasukan.profile') ? 'text-yellow-700' : 'text-gray-600' }} group-hover:text-yellow-700"></i>
                                    <span>Profile</span>
                            </a>

                            @php
                                        $notifications = App\Models\Notification::where('notifiable_type', 'App\Models\Article')
                                        ->whereNotIn('id', function ($query) {
                                            $query->select('notification_id')
                                                ->from('notification_reads')
                                                ->where('user_id', Auth::id());
                                        })
                                        ->get();
                                        $count = $notifications->count();
                                    @endphp
                            <a href="{{ route('articles.index') }}"
                                class="group flex items-center px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-yellow-700 hover:border-l-2 hover:border-yellow-700 transition-all duration-150 {{ request()->routeIs('articles.index') ? 'bg-gray-50 text-yellow-700 border-l-2 border-yellow-700' : '' }}">
                                <i
                                    class="fas fa-newspaper text-sm mr-3 transition-colors duration-150 {{ request()->routeIs('articles.index') ? 'text-yellow-700' : 'text-gray-600' }} group-hover:text-yellow-700"></i>
                                <span>Artikel</span>
                                @if($count > 0)
                                        <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                                            {{ $count }}
                                        </span>
                                    @endif
                            </a>
                            <a href="{{ route('withdraw.index') }}"
                                class="group flex items-center px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-yellow-700 hover:border-l-2 hover:border-yellow-700 transition-all duration-150 {{ request()->routeIs('withdraw.index') ? 'bg-gray-50 text-yellow-700 border-l-2 border-yellow-700' : '' }}">
                                <i
                                    class="fas fa-credit-card text-sm mr-3 transition-colors duration-150 {{ request()->routeIs('withdraw.index') ? 'text-yellow-700' : 'text-gray-600' }} group-hover:text-yellow-700"></i>
                                <span>Dompet</span>
                            </a>

                            @php
                                $miniGameStatus = App\Models\Setting::where('key_name', 'Mini Game')->first()->value;
                            @endphp

                            <a href="{{ $miniGameStatus == 'on' ? route('mini-game.index') : route('mini-game-cooming-soon.index') }}"
                                class="group flex items-center px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-yellow-700 hover:border-l-2 hover:border-yellow-700 transition-all duration-150 {{ request()->routeIs('mini-game.index') ? 'bg-gray-50 text-yellow-700 border-l-2 border-yellow-700' : '' }}">
                                <i
                                    class="fas fa-gamepad text-sm mr-3 transition-colors duration-150 {{ request()->routeIs('mini-game.index') ? 'text-yellow-700' : 'text-gray-600' }} group-hover:text-yellow-700"></i>
                                <span>Mini Game</span>
                            </a>

                            <div class="px-4 py-2.5 border-t border-gray-100 mt-auto">
                                <livewire:auth.logout />
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
            @endif
        @endauth

        <!-- Page Content -->
        <main class="flex-grow pt-16">
            @auth
                @if (auth()->user() && !request()->routeIs('register.pasukan'))
                    <div class="flex">
                        <!-- Desktop Sidebar -->
                        <div class="hidden md:block w-64 bg-white shadow-sm border-r border-gray-100 min-h-screen">
                            <nav class="py-4 sticky top-16">
                                <a href="{{ route('dashboard') }}"
                                    class="group flex items-center px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-yellow-700 hover:border-l-2 hover:border-yellow-700 transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-gray-50 text-yellow-700 border-l-2 border-yellow-700' : '' }}">
                                    <i class="fas fa-chart-line text-sm mr-3 transition-colors duration-150 {{ request()->routeIs('dashboard') ? 'text-yellow-700' : 'text-gray-600' }} group-hover:text-yellow-700"></i>
                                    <span>Dashboard</span>
                                </a>


                                <a href="{{ route('pasukan.apply-job') }}"
                                    class="group flex items-center px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-yellow-700 hover:border-l-2 hover:border-yellow-700 transition-all duration-150 {{ request()->routeIs('pasukan.apply-job') ? 'bg-gray-50 text-yellow-700 border-l-2 border-yellow-700' : '' }}">
                                    <i
                                        class="fas fa-briefcase text-sm mr-3 transition-colors duration-150 {{ request()->routeIs('pasukan.apply-job') ? 'text-yellow-700' : 'text-gray-600' }} group-hover:text-yellow-700"></i>
                                    <span>Misi</span>
                                </a>
                                <a href="{{ route('misi.progres') }}"
                                    class="group flex items-center px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-yellow-700 hover:border-l-2 hover:border-yellow-700 transition-all duration-150 {{ request()->routeIs('misi.progres') ? 'bg-gray-50 text-yellow-700 border-l-2 border-yellow-700' : '' }}">
                                    <i
                                        class="fas fa-tasks text-sm mr-3 transition-colors duration-150 {{ request()->routeIs('misi.progres') ? 'text-yellow-700' : 'text-gray-600' }} group-hover:text-yellow-700"></i>
                                    <span>Misi yang Diambil</span>
                                </a>
                                
                                @php
                                    $jobApprovedCount = App\Models\Notification::where('notifiable_type', 'App\Models\User')
                                        ->where('type', 'Job Approved')
                                        ->where('read_at', null)
                                        ->get();
                                        $countApproved = $jobApprovedCount->count();
                                @endphp
                                <a href="{{ route('pasukan.riwayat-pekerjaan') }}"
                                    class="group flex items-center px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-yellow-700 hover:border-l-2 hover:border-yellow-700 transition-all duration-150 {{ request()->routeIs('pasukan.riwayat-pekerjaan') ? 'bg-gray-50 text-yellow-700 border-l-2 border-yellow-700' : '' }}">
                                    <i
                                    class="fas fa-history text-sm mr-3 transition-colors duration-150 {{ request()->routeIs('pasukan.riwayat-pekerjaan') ? 'text-yellow-700' : 'text-gray-600' }} group-hover:text-yellow-700"></i>
                                    <span>Laporan Riwayat Misi</span>
                                    @if($countApproved > 0)
                                        <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                                            {{ $countApproved }}
                                        </span>
                                    @endif
                                </a>

                                <a href="{{ route('pasukan.profile') }}"
                                    class="group flex items-center px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-yellow-700 hover:border-l-2 hover:border-yellow-700 transition-all duration-150 {{ request()->routeIs('pasukan.profile') ? 'bg-gray-50 text-yellow-700 border-l-2 border-yellow-700' : '' }}">
                                    <i
                                        class="fas fa-user text-sm mr-3 transition-colors duration-150 {{ request()->routeIs('pasukan.profile') ? 'text-yellow-700' : 'text-gray-600' }} group-hover:text-yellow-700"></i>
                                    <span>Profile</span>
                                </a>

                                @php
                                        $notifications = App\Models\Notification::where('notifiable_type', 'App\Models\Article')
                                        ->whereNotIn('id', function ($query) {
                                            $query->select('notification_id')
                                                ->from('notification_reads')
                                                ->where('user_id', Auth::id());
                                        })
                                        ->get();
                                        $count = $notifications->count();
                                    @endphp
                                <a href="{{ route('articles.index') }}"
                                    class="group flex items-center px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-yellow-700 hover:border-l-2 hover:border-yellow-700 transition-all duration-150 {{ request()->routeIs('articles.index') ? 'bg-gray-50 text-yellow-700 border-l-2 border-yellow-700' : '' }}">
                                    <i class="fas fa-newspaper text-sm mr-3 transition-colors duration-150 {{ request()->routeIs('articles.index') ? 'text-yellow-700' : 'text-gray-600' }} group-hover:text-yellow-700"></i>
                                    <span>Artikel</span>
                                    @if($count > 0)
                                        <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                                            {{ $count }}
                                        </span>
                                    @endif
                                </a>

                                <a href="{{ route('withdraw.index') }}"
                                    class="group flex items-center px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-yellow-700 hover:border-l-2 hover:border-yellow-700 transition-all duration-150 {{ request()->routeIs('withdraw.index') ? 'bg-gray-50 text-yellow-700 border-l-2 border-yellow-700' : '' }}">
                                    <i
                                        class="fas fa-credit-card text-sm mr-3 transition-colors duration-150 {{ request()->routeIs('withdraw.index') ? 'text-yellow-700' : 'text-gray-600' }} group-hover:text-yellow-700"></i>
                                    <span>Dompet</span>
                                </a>

                                @php
                                    $miniGameStatus = App\Models\Setting::where('key_name', 'Mini Game')->first()->value;
                                @endphp

                                <a href="{{ $miniGameStatus == 'on' ? route('mini-game.index') : route('mini-game-cooming-soon.index') }}"
                                    class="group flex items-center px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-yellow-700 hover:border-l-2 hover:border-yellow-700 transition-all duration-150 {{ request()->routeIs('mini-game.index') ? 'bg-gray-50 text-yellow-700 border-l-2 border-yellow-700' : '' }}">
                                    <i
                                        class="fas fa-gamepad text-sm mr-3 transition-colors duration-150 {{ request()->routeIs('mini-game.index') ? 'text-yellow-700' : 'text-gray-600' }} group-hover:text-yellow-700"></i>
                                    <span>Mini Game</span>
                                </a>

                                <div class="px-4 py-2.5 border-t border-gray-100 mt-4">
                                    <livewire:auth.logout />
                                </div>
                            </nav>
                        </div>

                        <!-- Main Content -->
                        <div class="flex-1 overflow-x-hidden bg-gray-50">
                            {{ $slot }}
                        </div>
                    </div>
                @else
                    {{ $slot }}
                @endif
            @else
                {{ $slot }}
            @endauth
        </main>

        <x-toast />

        <!-- Footer -->
        <footer class="bg-black text-white">
            <div class="container mx-auto px-12 md:px-20 py-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    <!-- Left Column -->
                    <div class="lg:col-span-2">
                        <img src="{{ asset('images/spartavlogofooter.png') }}" alt="SPARTAV Logo" class="h-24 object-contain" />
                        <p class="text-white/90 max-w-4xl text-lg leading-relaxed">
                            SPARTAV adalah platform manajemen periklanan dan pemasaran digital yang memfasilitasi advertiser untuk
                            memperluas target market dengan memberdayakan pasukan netizen sebagai 'adsman' untuk melakukan kegiatan
                            branding, marketing, dan selling secara online.
                        </p>
                    </div>
        
                    <!-- Right Column -->
                    <div class="space-y-4 sm:space-y-6">
                        <h2 class="text-lg sm:text-xl md:text-2xl font-bold">PT Sinergi Mitra Mediatama</h2>
                        <div class="space-y-3 sm:space-y-4 font-bold">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <i class="fa-regular fa-envelope text-lg sm:text-xl"></i>
                                <a href="mailto:eov.eventrue@gmail.com" class="hover:text-white/80 text-base sm:text-xl">
                                    eov.eventrue@gmail.com
                                </a>
                            </div>
                            <div class="flex items-center gap-2 sm:gap-3">
                                <i class="fa-brands fa-whatsapp text-lg sm:text-xl"></i>
                                <a href="tel:08999950006" class="hover:text-white/80 text-base sm:text-xl">
                                    08999950006
                                </a>
                            </div>
                            <div class="flex items-center gap-2 sm:gap-3">
                                <i class="fa-solid fa-location-dot text-lg sm:text-xl"></i>
                                <span class="text-base sm:text-xl">Semarang, Indonesia</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Mobile Menu Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const closeSidebarButton = document.getElementById('close-sidebar');
            const menuIcon = document.getElementById('menu-icon');
    
            if (mobileMenuButton && mobileSidebar && menuIcon) {
                mobileMenuButton.addEventListener('click', function() {
                    const isHidden = mobileSidebar.classList.contains('hidden');
    
                    if (isHidden) {
                        // Buka sidebar
                        mobileSidebar.classList.remove('hidden');
                        document.body.style.overflow = 'hidden';
                        menuIcon.classList.remove('fa-bars');
                        menuIcon.classList.add('fa-times');
                    } else {
                        // Tutup sidebar
                        mobileSidebar.classList.add('hidden');
                        document.body.style.overflow = 'auto';
                        menuIcon.classList.remove('fa-times');
                        menuIcon.classList.add('fa-bars');
                    }
                });
            }
    
            if (closeSidebarButton) {
                closeSidebarButton.addEventListener('click', function() {
                    mobileSidebar.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                    menuIcon.classList.remove('fa-times');
                    menuIcon.classList.add('fa-bars');
                });
            }
    
            // Tutup sidebar jika klik di luar sidebar
            mobileSidebar.addEventListener('click', function(e) {
                if (e.target === mobileSidebar) {
                    mobileSidebar.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                    menuIcon.classList.remove('fa-times');
                    menuIcon.classList.add('fa-bars');
                }
            });
        });
    </script>
    
</body>

</html>
