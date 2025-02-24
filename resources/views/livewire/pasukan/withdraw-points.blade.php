<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8" 
     x-data="{ 
         selectedTab: 'withdraw', 
         selectedPoints: '0', 
         customPoints: 0, 
         conversionRate: @entangle('conversionRate'),
         withdrawOptions: [250, 300, 400, 500],
         get totalWithdraw() {
             return this.selectedPoints === 'custom' 
                 ? this.customPoints * this.conversionRate 
                 : this.selectedPoints * this.conversionRate;
         }
     }"
     x-cloak>
    <div class="bg-white shadow-2xl rounded-lg overflow-hidden">
        <div class="p-6 sm:p-10">
            <!-- Notifikasi -->
            <div x-show="$wire.notification" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-90" class="mb-6">
                <div x-show="$wire.notificationType === 'success'" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded" role="alert">
                    <p class="font-bold">Sukses!</p>
                    <p x-text="$wire.notification"></p>
                </div>
                <div x-show="$wire.notificationType === 'error'" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded" role="alert">
                    <p class="font-bold">Error!</p>
                    <p x-text="$wire.notification"></p>
                </div>
            </div>

            <!-- Tabs Menu -->
            <nav class="flex space-x-4 mb-8">
                <button @click="selectedTab = 'withdraw'" 
                        :class="{'bg-blue-500 text-white': selectedTab === 'withdraw', 'text-blue-500 bg-blue-100': selectedTab !== 'withdraw'}"
                        class="px-4 py-2 rounded-md font-medium transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    Withdraw Poin
                </button>
                <button @click="selectedTab = 'history'" 
                        :class="{'bg-blue-500 text-white': selectedTab === 'history', 'text-blue-500 bg-blue-100': selectedTab !== 'history'}"
                        class="px-4 py-2 rounded-md font-medium transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    Riwayat Transaksi
                </button>
            </nav>

            <!-- Form Withdraw -->
            <div x-show="selectedTab === 'withdraw'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6 rounded-lg shadow-lg text-white">
                            <p class="text-xl sm:text-2xl">Total Poin Anda:</p>
                            <p class="font-bold text-3xl sm:text-4xl mt-2" x-text="$wire.totalReward.toLocaleString()"></p>
                            <p class="text-sm mt-2">≈ Rp<span x-text="($wire.totalReward * conversionRate).toLocaleString()"></span></p>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-lg shadow">
                            <h3 class="text-xl font-semibold mb-4 text-gray-700">Pilih jumlah penarikan:</h3>
                            <div class="space-y-4">
                                <template x-for="option in withdrawOptions" :key="option">
                                    <label class="flex items-center space-x-3 p-3 bg-white rounded-lg shadow-sm hover:bg-gray-50 transition duration-150 ease-in-out cursor-pointer">
                                        <input type="radio" x-model="selectedPoints" :value="option" class="form-radio h-5 w-5 text-blue-600">
                                        <span class="text-gray-700 font-medium" x-text="`${option.toLocaleString()} Poin - Rp${(option * conversionRate).toLocaleString()}`"></span>
                                    </label>
                                </template>
                                <label class="flex items-center space-x-3 p-3 bg-white rounded-lg shadow-sm hover:bg-gray-50 transition duration-150 ease-in-out cursor-pointer">
                                    <input type="radio" x-model="selectedPoints" value="custom" class="form-radio h-5 w-5 text-blue-600">
                                    <span class="text-gray-700 font-medium">Jumlah lainnya</span>
                                </label>
                            </div>
                        </div>

                        <!-- Custom Points Input -->
                        <div x-show="selectedPoints === 'custom'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="bg-gray-50 p-6 rounded-lg shadow">
                            <div class="flex justify-between items-center mb-2">
                                <label for="customPoints" class="block text-sm font-medium text-gray-700">Masukkan jumlah poin:</label>
                                <small class="text-red-500">Minimum 250 poin</small>
                            </div>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="number" x-model="customPoints" id="customPoints" class="block w-full pr-10 sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan jumlah poin" min="250">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Poin</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-gray-50 p-6 rounded-lg shadow">
                            <h3 class="text-xl font-semibold mb-4 text-gray-700">Ringkasan Penarikan</h3>
                            <div class="space-y-2">
                                <p class="flex justify-between">
                                    <span class="text-gray-600">Jumlah Poin:</span>
                                    <span class="font-medium" x-text="selectedPoints === 'custom' ? customPoints.toLocaleString() + ' Poin' : selectedPoints.toLocaleString() + ' Poin'"></span>
                                </p>
                                <p class="flex justify-between">
                                    <span class="text-gray-600">Nilai Rupiah:</span>
                                    <span class="font-medium" x-text="`Rp${totalWithdraw.toLocaleString()}`"></span>
                                </p>
                                <hr class="my-2">
                                <p class="flex justify-between text-lg font-semibold">
                                    <span>Total Penarikan:</span>
                                    <span x-text="`Rp${totalWithdraw.toLocaleString()}`"></span>
                                </p>
                            </div>
                        </div>
                    <div class="space-y-3">     
                                <p class=" text-md font-bold text-gray-600">
                                    Penarikan akan diproses dalam periode tanggal 1 - 10 setiap bulannya.
                                </p>
                        </div>

                        <button @click="$wire.submitWithdraw(selectedPoints === 'custom' ? customPoints : selectedPoints)" 
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out"
                                :disabled="!selectedPoints || (selectedPoints === 'custom' && customPoints < 250)">
                            Ajukan Penarikan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Riwayat Transaksi -->
            <div x-show="selectedTab === 'history'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                <div class="flex flex-wrap gap-2 overflow-x-auto">
                    <button @click="$wire.setStatusFilter('all')" 
                        :class="{'bg-blue-500 text-white': $wire.statusFilter === 'all', 'bg-gray-200 text-gray-700': $wire.statusFilter !== 'all'}"
                        class="px-4 py-2 rounded-md transition duration-150 ease-in-out">
                        Semua
                    </button>
                    <button @click="$wire.setStatusFilter('pending')" 
                        :class="{'bg-yellow-500 text-white': $wire.statusFilter === 'pending', 'bg-gray-200 text-gray-700': $wire.statusFilter !== 'pending'}"
                        class="px-4 py-2 rounded-md transition duration-150 ease-in-out">
                        Pending
                    </button>
                    <button @click="$wire.setStatusFilter('Approved')" 
                        :class="{'bg-green-500 text-white': $wire.statusFilter === 'Approved', 'bg-gray-200 text-gray-700': $wire.statusFilter !== 'Approved'}"
                        class="px-4 py-2 rounded-md transition duration-150 ease-in-out">
                        Berhasil
                    </button>
                    <button @click="$wire.setStatusFilter('reject')" 
                        :class="{'bg-red-500 text-white': $wire.statusFilter === 'reject', 'bg-gray-200 text-gray-700': $wire.statusFilter !== 'reject'}"
                        class="px-4 py-2 rounded-md transition duration-150 ease-in-out">
                        Gagal
                    </button>
                </div>
                

                <div class="space-y-4">
                    <template x-if="$wire.transactions.length === 0">
                        <p class="text-sm text-gray-500 mt-10 text-center">Tidak ada transaksi</p>
                    </template>
                    <template x-for="transaction in $wire.transactions" :key="transaction.id">
                        <div class="bg-white p-4 rounded-lg shadow">
                            <div class="flex justify-between items-center">
                                <p class="text-gray-700 font-medium" x-text="`Penarikan Rp${transaction.amount.toLocaleString()}`"></p>
                                <span :class="{
                                    'bg-yellow-500 text-white': transaction.status === 'pending',
                                    'bg-green-500 text-white': transaction.status === 'approved',
                                    'bg-red-500 text-white': transaction.status === 'reject'
                                }" class="px-3 py-1 text-sm font-semibold rounded" x-text="transaction.status.charAt(0).toUpperCase() + transaction.status.slice(1)"></span>
                            </div>
                            <p class="text-sm text-gray-500 mt-2" 
                            x-text="transaction.created_at ? new Date(transaction.created_at).toLocaleString('id-ID') : 'Tanggal Tidak Tersedia'">
                            </p>
                            <p class="text-sm text-gray-500 mt-2" 
                            x-text="transaction.amount ? `${(transaction.amount / $wire.conversionRate).toLocaleString()} Poin` : 'Jumlah Poin Tidak Tersedia'">
                            </p>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

