<div class="bg-gray-100 min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-8">Top Up Saldo</h2>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                @if (session()->has('message'))
                    <div class="rounded-md bg-green-50 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    {{ session('message') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="space-y-6">
                    <div>
                        <label class="text-lg font-medium text-gray-900">Pilih Nominal Top Up</label>
                        <div class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-3">
                            @foreach ($topupAmounts as $value => $label)
                                <button wire:click="selectAmount({{ $value }})" 
                                        class="inline-flex justify-center items-center px-4 py-2 border rounded-md shadow-sm text-sm font-medium 
                                               {{ $selectedAmount === $value 
                                                  ? 'border-indigo-500 text-indigo-600 bg-indigo-50 hover:bg-indigo-100' 
                                                  : 'border-gray-300 text-gray-700 bg-white hover:bg-gray-50' 
                                               }}">
                                    Rp {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    @if ($selectedAmount)
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Instruksi Pembayaran</h3>
                            <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Transfer</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="text" readonly value="Rp {{ number_format($selectedAmount, 0, ',', '.') }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-10 sm:text-sm border-gray-300 rounded-md bg-white" />
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            <button onclick="copyToClipboard('{{ $selectedAmount }}', 'amount')" class="text-gray-400 hover:text-gray-500">
                                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"></path>
                                                    <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <p id="amount-copied" class="text-sm text-green-600 mt-1 hidden">Jumlah berhasil disalin!</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Rekening</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="text" readonly value="{{ $bankAccount['number'] }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-10 sm:text-sm border-gray-300 rounded-md bg-white" />
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            <button onclick="copyToClipboard('{{ $bankAccount['number'] }}', 'account')" class="text-gray-400 hover:text-gray-500">
                                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"></path>
                                                    <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <p id="account-copied" class="text-sm text-green-600 mt-1 hidden">Nomor rekening berhasil disalin!</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">Bank:</span> {{ $bankAccount['bank'] }}<br>
                                        <span class="font-medium">Atas Nama:</span> {{ $bankAccount['name'] }}
                                    </p>
                                </div>
                            </div>
                            <p class="mt-4 text-sm text-gray-600">Setelah melakukan transfer, silakan unggah bukti pembayaran di bawah ini.</p>
                        </div>

                        <div>
                            <label for="transferProof" class="block text-sm font-medium text-gray-700 mb-2">
                                Unggah Bukti Transfer
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Unggah file</span>
                                            <input id="file-upload" wire:model="transferProof" type="file" class="sr-only">
                                        </label>
                                        <p class="pl-1">atau seret dan lepas</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PNG, JPG, GIF up to 1MB
                                    </p>
                                </div>
                            </div>
                            @error('transferProof') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <button wire:click="uploadProof" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Kirim Bukti Transfer
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text, type) {
    navigator.clipboard.writeText(text).then(function() {
        const element = document.getElementById(`${type}-copied`);
        element.classList.remove('hidden');
        setTimeout(() => {
            element.classList.add('hidden');
        }, 2000);
    }, function(err) {
        console.error('Gagal menyalin teks: ', err);
    });
}
</script>

