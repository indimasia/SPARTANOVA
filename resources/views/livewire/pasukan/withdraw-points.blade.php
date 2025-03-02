     @php
         $minimumWithdraw = App\Models\Setting::where('key_name', 'Minimum Withdraw')->first()->value;
         $withdraw1 = $minimumWithdraw * 0.2;
         $withdraw2 = $minimumWithdraw * 0.3;
         $withdraw3 = $minimumWithdraw * 0.4;
         $withdraw4 = $minimumWithdraw * 0.5;
     @endphp
<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8" 
     x-data="{ 
         selectedTab: 'withdraw', 
         selectedPoints: '0', 
         customPoints: 0, 
         conversionRate: @entangle('conversionRate'),
         withdrawOptions: [{{ $minimumWithdraw }}, {{ $minimumWithdraw + $withdraw1 }}, {{ $minimumWithdraw + $withdraw2 }}, {{ $minimumWithdraw + $withdraw3 }}, {{ $minimumWithdraw + $withdraw4 }}],
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
                                <small class="text-red-500">Minimum {{ $minimumWithdraw }} poin</small>
                            </div>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="number" x-model="customPoints" id="customPoints" class="block w-full pr-10 sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan jumlah poin" min="{{ $minimumWithdraw }}">
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

                        <button 
                            @click="$wire.openModal(selectedPoints === 'custom' ? customPoints : selectedPoints)" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out"
                            :disabled="!selectedPoints || (selectedPoints === 'custom' && customPoints < {{ $minimumWithdraw }})">
                            Ajukan Penarikan
                        </button>

                        <div class="w-full relative" x-data="{ open: @entangle('isOpen') }">
                            <div 
                                x-show="open" 
                                class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50"
                                x-transition.opacity.duration.300ms>
                                
                                <div class="bg-white rounded-2xl py-4 px-5 w-full max-w-lg mx-auto shadow-lg transform transition-all"
                                    x-transition:enter="ease-out duration-300" 
                                    x-transition:enter-start="opacity-0 scale-95" 
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="ease-in duration-200"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95">
                                    
                                    <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                        <h4 class="text-sm text-gray-900 font-medium">Modal Simple</h4>
                                        <button class="block cursor-pointer" @click="$wire.closeModal">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M7.75732 7.75739L16.2426 16.2427M16.2426 7.75739L7.75732 16.2427" stroke="black" stroke-width="1.6" stroke-linecap="round"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <form method="POST">

                                        @csrf
                                        @method('POST')
                                        
                                        <div class="mb-4">
                                            <label for="In_the_name_of" class="block text-sm font-medium text-gray-700">Atas Nama</label>
                                            <input type="text" id="In_the_name_of" placeholder="Nama di rekening" wire:model="in_the_name_of" class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md" min="0">
                                            @error('In_the_name_of') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            <label for="metode" class="block text-sm font-medium text-gray-700" >Metode</label>
                                            <input type="text" name="metode" wire:model="bank_account" placeholder="misal: Dana, BRI, BCA, Shopeepay" id="bank_account" class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md">
                                            @error('bank_account') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            
                                            <label for="no_bank_account" class="block text-sm font-medium text-gray-700">Nomer Rekening</label>
                                            <input type="text" id="no_bank_account" placeholder="Nomer rekening akun" wire:model="no_bank_account" class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md" min="0">
                                            @error('no_bank_account') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                

                                        <div class="flex items-center justify-end pt-4 border-t border-gray-200 space-x-4">
                                            <button type="button" class=" bg-indigo-50 text-indigo-500 w-28 flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-lg font-medium hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-50 transition duration-150 ease-in-out" @click="$wire.closeModal">
                                                Cancel
                                            </button>
                                            
                                            <button 
                                                type="button" @click="$wire.submitWithdraw(selectedPoints === 'custom' ? customPoints : selectedPoints)" 
                                                class="w-28 flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out"
                                                :disabled="!selectedPoints || (selectedPoints === 'custom' && customPoints < {{ $minimumWithdraw }})">
                                                Konfirmasi
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
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
                                <div>
                                    <span :class="{
                                        'bg-yellow-500 text-white': transaction.status === 'pending',
                                        'bg-green-500 text-white': transaction.status === 'approved',
                                        'bg-red-500 text-white': transaction.status === 'reject'
                                    }" class=" py-1 text-sm font-semibold rounded" x-text="transaction.status.charAt(0).toUpperCase() + transaction.status.slice(1)"></span>
                                    <p class="text-sm text-gray-500 mt-2" 
                                    x-text="transaction.created_at ? new Date(transaction.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: '2-digit', day: 'numeric'})  : 'Tanggal Tidak Tersedia'">
                                    </p>
                                    <button 
                                        @click="$wire.editModal(transaction.id)" 
                                        class="w-36 flex justify-center py-1 px-2 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out"
                                        >
                                        Edit
                                    </button>
                                    
            
                                    <div class="w-full relative" x-data="{ open: @entangle('IsOpenEdit') }">
                                        <div 
                                            x-show="open" 
                                            class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-10 z-50"
                                            x-transition.opacity.duration.300ms>
                                            
                                            <div class="bg-white rounded-2xl py-4 px-5 w-full max-w-lg mx-auto shadow-lg transform transition-all"
                                                x-transition:enter="ease-out duration-300" 
                                                x-transition:enter-start="opacity-0 scale-95" 
                                                x-transition:enter-end="opacity-100 scale-100"
                                                x-transition:leave="ease-in duration-200"
                                                x-transition:leave-start="opacity-100 scale-100"
                                                x-transition:leave-end="opacity-0 scale-95">
                                                
                                                <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                                    <h4 class="text-sm text-gray-900 font-medium">Edit data withdraw</h4>
                                                    <button class="block cursor-pointer" @click="$wire.closeModal">
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M7.75732 7.75739L16.2426 16.2427M16.2426 7.75739L7.75732 16.2427" stroke="black" stroke-width="1.6" stroke-linecap="round"></path>
                                                        </svg>
                                                    </button>
                                                </div>
            
                                                <form wire:submit.prevent="updateWithdraw">
                                                    @csrf
                                                    
                                                    <div class="mb-4">
                                                        <label for="in_the_name_of" class="block text-sm font-medium text-gray-700">Atas Nama</label>
                                                        <input type="text" id="in_the_name_of" wire:model.defer="editTransaction.in_the_name_of" class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md">
                                                        @error('in_the_name_of') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                
                                                        <label for="bank_account" class="block text-sm font-medium text-gray-700">Metode</label>
                                                        <input type="text" id="bank_account" wire:model.defer="editTransaction.bank_account" class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md">
                                                        @error('bank_account') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                
                                                        <label for="no_bank_account" class="block text-sm font-medium text-gray-700">Nomor Rekening</label>
                                                        <input type="text" id="no_bank_account" wire:model.defer="editTransaction.no_bank_account" class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md">
                                                        @error('no_bank_account') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                    </div>
                                
                                                    <div class="flex items-center justify-end pt-4 border-t border-gray-200 space-x-4">
                                                        <button type="button" class="bg-indigo-50 text-indigo-500 w-28 flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-lg font-medium hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-50 transition duration-150 ease-in-out" @click="$wire.closeModal()">
                                                            Cancel
                                                        </button>
                                                        
                                                        <button type="submit" class="w-28 flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                                            Konfirmasi
                                                        </button>
                                                    </div>
                                                </form>
                                </div>
                            </div>
                        </div>
            
                                </div>
                                </div>
                            <p class="text-sm text-gray-500 mt-2" 
                            x-text="`Atas Nama : ${transaction.in_the_name_of}`">
                            </p>
                            <p class="text-sm text-gray-500 mt-2" 
                            x-text="`Metode : ${transaction.bank_account}`">
                            </p>
                            <p class="text-sm text-gray-500 mt-2" 
                            x-text="`Nomer Rekening : ${transaction.no_bank_account}`">
                            </p>
                            <p class="text-sm text-gray-500 mt-2" 
                            x-text="transaction.amount ? `${(transaction.amount / $wire.conversionRate).toLocaleString()} Poin` : 'Jumlah Poin Tidak Tersedia'">
                            </p>
                            <p x-show="transaction.status === 'rejected'" class="text-sm text-gray-500 mt-2" 
                            x-text="`Alasan : ${transaction.description}`">
                            </p>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

