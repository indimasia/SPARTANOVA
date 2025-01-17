<div class="container mx-auto p-6 bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-full bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-8">
            <h1 class="text-4xl font-extrabold text-center text-gray-800 mb-8">Profile</h1>

            @if (session()->has('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <form wire:submit.prevent="updateProfile" class="space-y-6">
                <div class="group">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama
                        Lengkap</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i
                                class="fas fa-user text-gray-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                        </div>
                        <input type="text" id="name" wire:model="name"
                            class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm group-hover:border-blue-300 transition-colors duration-200"
                            placeholder="Masukkan nama lengkap">
                    </div>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="group">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i
                                class="fas fa-envelope text-gray-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                        </div>
                        <input type="email" id="email" wire:model="email"
                            class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm group-hover:border-blue-300 transition-colors duration-200"
                            placeholder="nama@email.com">
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="group">
                    <label for="gender" class="block text-sm font-medium text-gray-700">Jenis
                        Kelamin</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i
                                class="fas fa-venus-mars text-gray-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                        </div>
                        <select id="gender" wire:model="gender"
                            class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm group-hover:border-blue-300 transition-colors duration-200">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    @error('gender')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div x-data="{
                    open: false,
                    interests: @entangle('interest'),
                    search: '',
                    options: [
                        @foreach (\App\Enums\UserInterestEnum::cases() as $interest)
                            { value: '{{ $interest->value }}', label: '{{ $interest->name }}' },
                        @endforeach
                    ],
                    get filteredOptions() {
                        return this.options.filter(option => 
                            option.label.toLowerCase().includes(this.search.toLowerCase())
                        )
                    },
                    isSelected(value) {
                        return this.interests.includes(value)
                    },
                    toggle(value) {
                        if (this.isSelected(value)) {
                            this.interests = this.interests.filter(i => i !== value)
                        } else {
                            this.interests = [...this.interests, value]
                        }
                    }
                }" class="relative" id="interest">
                    <label for="interest" class="block text-sm font-medium text-gray-700">Interest</label>
                        <div @click="open = !open"
                            class="bg-white w-full flex items-center justify-between border rounded-md px-3 py-2 cursor-pointer">
                            <div class="flex flex-wrap gap-1">
                                <template x-for="(value, index) in interests" :key="index">
                                    <span class="bg-blue-100 text-blue-800 text-sm font-medium px-2 py-0.5 rounded-full"
                                        x-text="options.find(opt => opt.value === value)?.label"></span>
                                </template>
                                <span x-show="interests.length === 0" class="text-gray-500">
                                    Select interests...
                                </span>
                            </div>
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414L10 13.414 5.293 8.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    
                        <div x-show="open" @click.away="open = false"
                            class="absolute z-10 w-full mt-1 bg-white rounded-md shadow-lg max-h-60 overflow-y-auto">
                            <div class="sticky top-0 bg-white p-2 border-b">
                                <input type="text" x-model="search" placeholder="Search interests..."
                                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>
                            <div class="py-1">
                                <template x-for="option in filteredOptions" :key="option.value">
                                    <div @click="toggle(option.value)"
                                        class="flex items-center px-3 py-2 cursor-pointer hover:bg-gray-100"
                                        :class="{ 'bg-blue-50': isSelected(option.value) }">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" :checked="isSelected(option.value)" class="h-4 w-4 text-blue-600 rounded">
                                        </div>
                                        <div class="ml-3">
                                            <span x-text="option.label" class="text-gray-700"></span>
                                        </div>
                                    </div>
                                </template>
                                <div x-show="filteredOptions.length === 0" class="px-3 py-2 text-gray-500">
                                    No results found
                                </div>
                            </div>
                        </div>
                    </div>
                @error('interest')
                    <p class="mt-1 text-sm text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ $message }}
                    </p>
                @enderror
                

                <div class="group">
                    <div class="flex justify-between items-center">
                        <!-- Label di kiri -->
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700">
                            Tanggal Lahir
                        </label>
                
                        <!-- Informasi di kanan -->
                        <p class="text-xs text-red-500">
                            Untuk validasi pencairan.
                        </p>
                    </div>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i
                                class="fas fa-calendar text-gray-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                        </div>
                        <input type="date" id="date_of_birth" wire:model="date_of_birth"
                            class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm group-hover:border-blue-300 transition-colors duration-200">
                    </div>
                    @error('date_of_birth')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="group">
                    <label for="phone" class="block text-sm font-medium text-gray-700"><p>Phone</p></label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i
                                class="fas fa-user text-gray-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                        </div>
                        <input type="text" id="phone" wire:model="phone"
                            class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm group-hover:border-blue-300 transition-colors duration-200"
                            placeholder="Masukkan nama lengkap">
                    </div>
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="group">
                    <label for="province_kode"
                        class="block text-sm font-medium text-gray-700">Provinsi</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i
                                class="fas fa-map text-gray-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                        </div>
                        <select id="province_kode" wire:model.live="province_kode"
                            class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm group-hover:border-blue-300 transition-colors duration-200">
                            <option value="">Pilih Provinsi</option>
                            @foreach ($provinces as $province)
                                <option value="{{ $province->kode }}" @selected($province->kode == $province_kode)>
                                    {{ $province->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('province_kode')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="group">
                    <label for="regency_kode"
                        class="block text-sm font-medium text-gray-700">Kabupaten/Kota</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i
                                class="fas fa-city text-gray-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                        </div>
                        <select id="regency_kode" wire:model.live="regency_kode"
                            class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm group-hover:border-blue-300 transition-colors duration-200">
                            <option value="">Pilih Kabupaten/Kota</option>
                            @foreach ($regencies as $regency)
                            <option value="{{ $regency->kode }}" @selected($regency->kode == $regency_kode)>
                                {{ $regency->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @error('regency_kode')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="group">
                    <label for="district_kode"
                        class="block text-sm font-medium text-gray-700">Kecamatan</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i
                                class="fas fa-building text-gray-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                        </div>
                        <select id="district_kode" wire:model.live="district_kode"
                            class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm group-hover:border-blue-300 transition-colors duration-200">
                            <option value="">Pilih Kecamatan</option>
                            @foreach ($districts as $district)
                            <option value="{{ $district->kode }}" @selected($district->kode == $district_kode)>
                                {{ $district->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @error('district_kode')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="group">
                    <label for="village_kode"
                        class="block text-sm font-medium text-gray-700">Desa/Kelurahan</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i
                                class="fas fa-home text-gray-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                        </div>
                        <select id="village_kode" wire:model.live="village_kode"
                            class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm group-hover:border-blue-300 transition-colors duration-200">
                            <option value="">Pilih Desa/Kelurahan</option>
                            @foreach ($villages as $village)
                            <option value="{{ $village->kode }}" @selected($village->kode == $village_kode)>
                                {{ $village->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @error('village_kode')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>                              

                <div class="flex justify-center mt-8">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-8 rounded-lg focus:outline-none focus:ring focus:ring-blue-300 transition duration-300">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
