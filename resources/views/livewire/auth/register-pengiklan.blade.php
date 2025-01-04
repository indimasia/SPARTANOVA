<div class="container mx-auto py-8">
    <div class="max-w-lg mx-auto bg-white shadow-md rounded px-8 py-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Register Pengiklan</h2>
        <form wire:submit.prevent="register">
            <!-- nama -->
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Name</label>
                <input type="text" id="name" wire:model="name" class="w-full mt-1 px-4 py-2 border rounded-md"
                    placeholder="Enter your name" />
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" id="email" wire:model="email" class="w-full mt-1 px-4 py-2 border rounded-md"
                    placeholder="Enter your email" />
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-gray-700">Password</label>
                <input type="password" id="password" wire:model="password"
                    class="w-full mt-1 px-4 py-2 border rounded-md" placeholder="Enter your password" />
                @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="password_confirmation" class="block text-gray-700">Confirm Password</label>
                <input type="password" id="password_confirmation" wire:model="password_confirmation"
                    class="w-full mt-1 px-4 py-2 border rounded-md" placeholder="Confirm your password" />
                @error('password_confirmation')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Gender -->
            <div class="mb-4">
                <label for="gender" class="block text-gray-700">Gender</label>
                <select id="gender" wire:model="gender" class="w-full mt-1 px-4 py-2 border rounded-md">
                    <option value="">Select Gender</option>
                    <option value="L">Male</option>
                    <option value="P">Female</option>
                </select>
                @error('gender')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Date of Birth -->
            <div class="mb-4">
                <label for="date_of_birth" class="block text-gray-700">Date of Birth</label>
                <input type="date" id="date_of_birth" wire:model="date_of_birth"
                    class="w-full mt-1 px-4 py-2 border rounded-md" />
                @error('date_of_birth')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Phone -->
            <div class="mb-4">
                <label for="phone" class="block text-gray-700">Phone</label>
                <input type="text" id="phone" wire:model="phone" class="w-full mt-1 px-4 py-2 border rounded-md"
                    placeholder="Enter your phone number" />
                @error('phone')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Province -->
            <div class="mb-4">
                <label for="province_kode" class="block text-gray-700">Province</label>
                <select id="province_kode" wire:model.live="province_kode"
                    class="w-full mt-1 px-4 py-2 border rounded-md">
                    <option value="">Select Province</option>
                    @foreach ($provinces as $province)
                        <option value="{{ $province->kode }}">{{ $province->nama }}</option>
                    @endforeach
                </select>
                @error('province_kode')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Regency -->
            <div class="mb-4">
                <label for="regency_kode" class="block text-gray-700">Regency</label>
                <select id="regency_kode" wire:model.live="regency_kode"
                    class="w-full mt-1 px-4 py-2 border rounded-md">
                    <option value="">Select Regency</option>
                    @foreach ($regencies as $regency)
                        <option value="{{ $regency->kode }}">{{ $regency->nama }}</option>
                    @endforeach
                </select>
                @error('regency_kode')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- District -->
            <div class="mb-4">
                <label for="district_kode" class="block text-gray-700">District</label>
                <select id="district_kode" wire:model.live="district_kode"
                    class="w-full mt-1 px-4 py-2 border rounded-md">
                    <option value="">Select District</option>
                    @foreach ($districts as $district)
                        <option value="{{ $district->kode }}">{{ $district->nama }}</option>
                    @endforeach
                </select>
                @error('district_kode')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Village -->
            <div class="mb-4">
                <label for="village_kode" class="block text-gray-700">Village</label>
                <select id="village_kode" wire:model.live="village_kode"
                    class="w-full mt-1 px-4 py-2 border rounded-md">
                    <option value="">Select Village</option>
                    @foreach ($villages as $village)
                        <option value="{{ $village->kode }}">{{ $village->nama }}</option>
                    @endforeach
                </select>
                @error('village_kode')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit -->
            <div>
                <button type="submit"
                    class="w-full bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Register</button>
            </div>
        </form>
    </div>
</div>
