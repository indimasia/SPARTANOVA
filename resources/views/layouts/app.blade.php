<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

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
                            <button id="mobile-menu-button"
                                class="md:hidden text-gray-500 hover:text-gray-900 focus:outline-none">
                                <i class="fas fa-bars text-xl"></i>
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
                                <span>Pekerjaan</span>
                            </a>

                            <a href="{{ route('pasukan.riwayat-pekerjaan') }}"
                                class="flex items-center px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-yellow-500 hover:border-l-2 hover:border-yellow-500 transition-all duration-150 {{ request()->routeIs('pasukan.riwayat-pekerjaan') ? 'bg-gray-50 text-yellow-500 border-l-2 border-yellow-500' : '' }}">
                                <i
                                    class="fas fa-history text-sm mr-3 {{ request()->routeIs('pasukan.riwayat-pekerjaan') ? 'text-yellow-500' : 'text-gray-400' }}"></i>
                                <span>Riwayat Pekerjaan</span>
                            </a>

                            <a href="{{ route('pasukan.profile') }}"
                                    class="group flex items-center px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-yellow-700 hover:border-l-2 hover:border-yellow-700 transition-all duration-150 {{ request()->routeIs('pasukan.profile') ? 'bg-gray-50 text-yellow-700 border-l-2 border-yellow-700' : '' }}">
                                    <i
                                        class="fas fa-user text-sm mr-3 transition-colors duration-150 {{ request()->routeIs('pasukan.profile') ? 'text-yellow-700' : 'text-gray-600' }} group-hover:text-yellow-700"></i>
                                    <span>Profile</span>
                                </a>

                            <div class="px-4 py-2.5 border-t border-gray-100 mt-auto">
                                <livewire:auth.logout />
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        @endauth

        <!-- Page Content -->
        <main class="flex-grow pt-16">
            @auth
                @if (auth()->user())
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
                                    <span>Pekerjaan</span>
                                </a>

                                <a href="{{ route('pasukan.riwayat-pekerjaan') }}"
                                    class="group flex items-center px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-yellow-700 hover:border-l-2 hover:border-yellow-700 transition-all duration-150 {{ request()->routeIs('pasukan.riwayat-pekerjaan') ? 'bg-gray-50 text-yellow-700 border-l-2 border-yellow-700' : '' }}">
                                    <i
                                        class="fas fa-history text-sm mr-3 transition-colors duration-150 {{ request()->routeIs('pasukan.riwayat-pekerjaan') ? 'text-yellow-700' : 'text-gray-600' }} group-hover:text-yellow-700"></i>
                                    <span>Riwayat Pekerjaan</span>
                                </a>

                                <a href="{{ route('pasukan.profile') }}"
                                    class="group flex items-center px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-yellow-700 hover:border-l-2 hover:border-yellow-700 transition-all duration-150 {{ request()->routeIs('pasukan.profile') ? 'bg-gray-50 text-yellow-700 border-l-2 border-yellow-700' : '' }}">
                                    <i
                                        class="fas fa-user text-sm mr-3 transition-colors duration-150 {{ request()->routeIs('pasukan.profile') ? 'text-yellow-700' : 'text-gray-600' }} group-hover:text-yellow-700"></i>
                                    <span>Profile</span>
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

        <!-- Footer -->
        <footer class="bg-gray-800 text-white mt-auto">
            <div class="container mx-auto px-4 py-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <img src="{{ asset('images/spartav_logo.png') }}" alt="Logo Spartav" class="h-10 mb-4">
                        <p class="text-sm text-gray-400">Spartav adalah mitra terpercaya Anda dalam keunggulan pemasaran
                            digital.</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold mb-4">Kontak</h4>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li><i class="fas fa-envelope mr-2"></i>info@spartav.com</li>
                            <li><i class="fas fa-phone mr-2"></i>(123) 456-7890</li>
                            <li><i class="fas fa-map-marker-alt mr-2"></i>Jakarta, Indonesia</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold mb-4">Ikuti Kami</h4>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">
                                <i class="fab fa-twitter"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="border-t border-gray-700 mt-6 pt-6 text-center">
                    <p class="text-xs text-gray-400">&copy; 2024 Spartav. Semua hak dilindungi.</p>
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

            if (mobileMenuButton && mobileSidebar && closeSidebarButton) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileSidebar.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });

                closeSidebarButton.addEventListener('click', function() {
                    mobileSidebar.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                });

                // Close sidebar when clicking outside
                mobileSidebar.addEventListener('click', function(e) {
                    if (e.target === mobileSidebar) {
                        mobileSidebar.classList.add('hidden');
                        document.body.style.overflow = 'auto';
                    }
                });
            }
        });
    </script>
</body>

</html>
