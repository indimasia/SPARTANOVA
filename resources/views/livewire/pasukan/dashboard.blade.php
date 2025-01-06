<div class="bg-gray-50 min-h-screen">
    <!-- Content Area -->
    <div class="p-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Profile Card -->
            <a href="#"
                class="block p-4 bg-white rounded-lg border border-gray-100 hover:shadow-sm transition-all duration-150 group">
                <div class="flex items-center text-gray-600 group-hover:text-gray-800">
                    <i class="fas fa-user-circle text-sm mr-2 text-gray-400 group-hover:text-blue-500"></i>
                    <span class="text-xs font-medium">Profil</span>
                </div>
            </a>

            <!-- Change Password Card -->
            <a href="#"
                class="block p-4 bg-white rounded-lg border border-gray-100 hover:shadow-sm transition-all duration-150 group">
                <div class="flex items-center text-gray-600 group-hover:text-gray-800">
                    <i class="fas fa-key text-sm mr-2 text-gray-400 group-hover:text-blue-500"></i>
                    <span class="text-xs font-medium">Ganti Password</span>
                </div>
            </a>

            <!-- Wallet Card -->
            <a href="#"
                class="block p-4 bg-white rounded-lg border border-gray-100 hover:shadow-sm transition-all duration-150 group">
                <div class="flex items-center text-gray-600 group-hover:text-gray-800">
                    <i class="fas fa-wallet text-sm mr-2 text-gray-400 group-hover:text-blue-500"></i>
                    <span class="text-xs font-medium">Dompet Saya</span>
                </div>
            </a>

            <!-- Withdraw Card -->
            <a href="#"
                class="block p-4 bg-white rounded-lg border border-gray-100 hover:shadow-sm transition-all duration-150 group">
                <div class="flex items-center text-gray-600 group-hover:text-gray-800">
                    <i class="fas fa-money-bill-transfer text-sm mr-2 text-gray-400 group-hover:text-blue-500"></i>
                    <span class="text-xs font-medium">Tarik Tunai</span>
                </div>
            </a>
        </div>

        <!-- Telegram Banner -->
        <div class="mt-6">
            <div class="bg-white p-4 rounded-lg border border-gray-100">
                <img src="{{ asset('images/telegram-banner.jpg') }}" alt="Join Telegram"
                    class="w-full rounded shadow-sm">
                <p class="mt-3 text-xs text-gray-500">
                    Silakan klik <a href="#" class="text-blue-500 hover:text-blue-600 hover:underline">Open
                        Order</a> untuk mengambil tiket.
                </p>
            </div>
        </div>
    </div>
</div>
