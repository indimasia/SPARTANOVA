<div>
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-yellow-400 to-yellow-600 text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">Selamat datang di Spartav</h1>
            <p class="text-xl md:text-2xl mb-8 text-white/90">Gerbang Anda menuju Keunggulan Pemasaran Digital</p>
            <div class="flex justify-center gap-4">
                @if (!auth()->check())
                    <a href="{{ route('register') }}"
                        class="bg-white text-yellow-600 px-8 py-3 rounded-lg font-semibold hover:bg-yellow-50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        Mulai Sekarang
                    </a>
                    <a href="{{ route('login') }}"
                        class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white/10 transition-all duration-300">
                        Masuk
                    </a>
                @else
                    @if(auth()->user()->hasRole('pasukan') && auth()->user()->is_active)
                        <a href="{{ route('dashboard') }}"
                            class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white/10 transition-all duration-300">
                            Kembali ke Dashboard Pasukan
                        </a>
                    @elseif(auth()->user()->hasRole('admin'))
                        <a href="{{ route('filament.admin.pages.dashboard') }}"
                            class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white/10 transition-all duration-300">
                            Kembali ke Dashboard Admin
                        </a>
                    @elseif(auth()->user()->hasRole('pengiklan'))
                        <a href="{{ route('filament.pengiklan.pages.dashboard') }}"
                            class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white/10 transition-all duration-300">
                            Kembali ke Dashboard Pengiklan
                        </a>
                    @endif
                @endif
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl md:text-3xl font-bold text-center mb-12 text-gray-800">Fitur Unggulan Kami</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div
                    class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-bullhorn text-yellow-500 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-gray-800">Pemasaran Digital</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Perluas jangkauan Anda dengan solusi pemasaran digital kami yang komprehensif dan efektif.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div
                    class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-tasks text-yellow-500 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-gray-800">Manajemen Kampanye</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Kelola dan lacak kampanye pemasaran Anda dengan mudah dan efisien melalui dashboard intuitif.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div
                    class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-chart-line text-yellow-500 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-gray-800">Analitik Real-time</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Dapatkan wawasan dan analitik mendetail untuk mengoptimalkan performa kampanye Anda.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl md:text-3xl font-bold text-center mb-12 text-gray-800">Cara Kerja</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Step 1 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-yellow-500">1</span>
                    </div>
                    <h3 class="text-lg font-semibold mb-2 text-gray-800">Daftar</h3>
                    <p class="text-sm text-gray-600">Buat akun Anda dalam hitungan menit</p>
                </div>

                <!-- Step 2 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-yellow-500">2</span>
                    </div>
                    <h3 class="text-lg font-semibold mb-2 text-gray-800">Pilih Kampanye</h3>
                    <p class="text-sm text-gray-600">Temukan kampanye yang sesuai dengan Anda</p>
                </div>

                <!-- Step 3 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-yellow-500">3</span>
                    </div>
                    <h3 class="text-lg font-semibold mb-2 text-gray-800">Mulai Bekerja</h3>
                    <p class="text-sm text-gray-600">Kerjakan tugas dengan panduan yang jelas</p>
                </div>

                <!-- Step 4 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-yellow-500">4</span>
                    </div>
                    <h3 class="text-lg font-semibold mb-2 text-gray-800">Dapatkan Reward</h3>
                    <p class="text-sm text-gray-600">Terima pembayaran untuk setiap tugas selesai</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-2xl md:text-3xl font-bold mb-6 text-gray-800">Siap Untuk Memulai?</h2>
            <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
                Bergabunglah dengan ribuan orang yang telah sukses mengembangkan bisnis mereka bersama Spartav
            </p>
            <a href="{{ route('register') }}"
                class="inline-flex items-center px-8 py-3 bg-yellow-500 text-white font-semibold rounded-lg hover:bg-yellow-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <span>Daftar Sekarang</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </section>
</div>
