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
    <div class="min-h-screen bg-gray-100 flex flex-col">
        <!-- Header -->
        <header class="fixed top-0 w-full bg-white shadow-lg z-50 transition-all duration-300">
            <div class="container mx-auto px-4 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex-shrink-0 transition-transform duration-300 hover:scale-105">
                        <img src="{{ asset('images/spartav_logo.png') }}" alt="{{ config('app.name', 'SpartaNova') }}"
                            class="h-12">
                    </div>
                    <div class="hidden md:flex flex-grow justify-center space-x-8">
                        <a href="#"
                            class="group relative text-gray-600 hover:text-blue-600 font-medium transition-colors duration-300">
                            <span>Beranda</span>
                            <span
                                class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                        </a>
                        <a href="#"
                            class="group relative text-gray-600 hover:text-blue-600 font-medium transition-colors duration-300">
                            <span>Tentang</span>
                            <span
                                class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                        </a>
                        <a href="#"
                            class="group relative text-gray-600 hover:text-blue-600 font-medium transition-colors duration-300">
                            <span>Layanan</span>
                            <span
                                class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                        </a>
                        <a href="#"
                            class="group relative text-gray-600 hover:text-blue-600 font-medium transition-colors duration-300">
                            <span>Kontak</span>
                            <span
                                class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                        </a>
                    </div>
                    <div class="flex items-center space-x-6">
                        @auth
                            <livewire:auth.logout />
                        @else
                            <a href="{{ route('login') }}"
                                class="font-medium text-gray-600 hover:text-blue-600 transition-colors duration-300">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-full font-medium shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300">
                                Daftar
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-grow pt-16">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white">
            <div class="container mx-auto px-4 py-8">
                <div class="flex flex-col md:flex-row justify-between">
                    <div class="mb-6 md:mb-0">
                        <img src="{{ asset('images/spartav_logo.png') }}" alt="Logo SpartaNova" class="h-12 mb-4">
                        <h4 class="text-lg font-bold mb-2">Tentang Kami</h4>
                        <p class="text-gray-400">SpartaNova adalah mitra terpercaya Anda dalam keunggulan pemasaran
                            digital.</p>
                    </div>
                    <div class="mb-6 md:mb-0">
                        <h4 class="text-lg font-bold mb-2">Kontak</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li><i class="fas fa-envelope mr-2"></i>Email: info@spartanova.com</li>
                            <li><i class="fas fa-phone mr-2"></i>Telepon: (123) 456-7890</li>
                            <li><i class="fas fa-map-marker-alt mr-2"></i>Alamat: Jakarta, Indonesia</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold mb-2">Ikuti Kami</h4>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-white" aria-label="Facebook">
                                <i class="fab fa-facebook-f h-6 w-6"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white" aria-label="Instagram">
                                <i class="fab fa-instagram h-6 w-6"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white" aria-label="Twitter">
                                <i class="fab fa-twitter h-6 w-6"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                    <p>&copy; 2024 SpartaNova. Semua hak dilindungi.</p>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>
