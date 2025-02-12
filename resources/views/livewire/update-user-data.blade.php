<div>
    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg w-96 p-6 relative">
            <h3 class="text-lg font-semibold mb-4">Lengkapi Data Anda</h3>
            <form wire:submit.prevent="save">
                <div class="mb-4">
                    <label for="contact_wa" class="block text-sm font-medium text-gray-700">Berapa Jumlah Kontak WA Anda?</label>
                    <select id="contact_wa" wire:model="contact_wa"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih jumlah kontak WA</option>
                        <option value="50">50 kontak WA</option>
                        <option value="100">100an kontak WA</option>
                        <option value="500">500an kontak WA</option>
                    </select>
                    @error('contact_wa') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Pilih 10 hal di bawah ini yang menjadi minat Anda</label>
                    <div class="space-y-2 max-h-60 overflow-y-auto"> <!-- max-height dan overflow ditambahkan -->
                        @foreach($interestOptions as $option)
                            <div class="flex items-center">
                                <input type="checkbox" id="interest_{{ $option }}" wire:model="interestValue" value="{{ $option }}" 
                                    class="mr-2">
                                <label for="interest_{{ $option }}" class="text-sm">{{ $option }}</label>
                            </div>
                        @endforeach

                    </div>
                    @error('interestValue') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end gap-2">
                    {{-- <button type="button" wire:click="$set('showModal', false)"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md">Batal</button> --}}
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endif


        {{-- <!-- Modal Background -->
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
            <!-- Modal Content -->
            <div class="bg-white rounded-lg shadow-lg w-96 p-6 relative">
                <h3 class="text-lg font-semibold mb-4">Lengkapi Data Anda</h3>
                <form wire:submit.prevent="save">
                    <div class="mb-4">
                        <label for="contact_wa" class="block text-sm font-medium text-gray-700">Nomor WhatsApp</label>
                        <input type="text" id="contact_wa" wire:model="contact_wa"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500">
                        @error('contact_wa') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="interest" class="block text-sm font-medium text-gray-700">Interest</label>
                        <input type="text" id="interest" wire:model="interest"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500">
                        @error('interest') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" wire:click="$set('showModal', false)"
                            class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif --}}
</div>
