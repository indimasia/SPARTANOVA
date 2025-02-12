<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-gray rounded-xl overflow-hidden">
            <div class="p-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">Daftar Sebagai Pengiklan</h2>
                    <p class="mt-2 text-gray-600">Bergabunglah untuk mempromosikan konten Anda</p>
                </div>

                <form wire:submit.prevent="register" class="space-y-6">
                    <!-- Personal Information Section -->
                    <div class="bg-gray-50 p-6 rounded-lg mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-user-circle mr-2 text-blue-500"></i>
                            Informasi Pribadi
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
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

                            <!-- Email -->
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

                            <!-- company -->
                            <div class="group">
                                <label for="company" class="block text-sm font-medium text-gray-700">Nama Perusahaan</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i
                                            class="fas fa-building text-gray-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                                    </div>
                                    <input type="text" id="company" wire:model="company"
                                        class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm group-hover:border-blue-300 transition-colors duration-200"
                                        placeholder="Nama Perusahaan">
                                </div>
                                @error('company')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="group">
                                <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i
                                            class="fas fa-lock text-gray-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                                    </div>
                                    <input type="password" id="password" wire:model="password"
                                        class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm group-hover:border-blue-300 transition-colors duration-200"
                                        placeholder="Minimal 8 karakter">
                                </div>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="group">
                                <label for="password_confirmation"
                                    class="block text-sm font-medium text-gray-700">Konfirmasi Kata Sandi</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i
                                            class="fas fa-lock text-gray-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                                    </div>
                                    <input type="password" id="password_confirmation" wire:model="password_confirmation"
                                        class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm group-hover:border-blue-300 transition-colors duration-200"
                                        placeholder="Ulangi kata sandi">
                                </div>
                                @error('password_confirmation')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Gender -->
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

                            <!-- Date of Birth -->
                            <div class="group">
                                <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Tanggal
                                    Lahir</label>
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

                            <!-- Phone -->
                            <div class="group">
                                <label for="phone" class="block text-sm font-medium text-gray-700">Nomor
                                    Telepon</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i
                                            class="fas fa-phone text-gray-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                                    </div>
                                    <input type="text" id="phone" wire:model="phone"
                                        class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm group-hover:border-blue-300 transition-colors duration-200"
                                        placeholder="08xxxxxxxxxx">
                                </div>
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Location Section -->
                    <div class="bg-gray-50 p-6 rounded-lg mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                            Informasi Lokasi
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Province -->
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
                                            <option value="{{ $province->kode }}">{{ $province->nama }}</option>
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

                            <!-- Regency -->
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
                                            <option value="{{ $regency->kode }}">{{ $regency->nama }}</option>
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

                            <!-- District -->
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
                                            <option value="{{ $district->kode }}">{{ $district->nama }}</option>
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

                            <!-- Village -->
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
                                            <option value="{{ $village->kode }}">{{ $village->nama }}</option>
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
                        </div>
                    </div>
                    <input type="hidden" id="latitude" wire:model="latitude">
                    <input type="hidden" id="longitude" wire:model="longitude">
                    <p class="text-gray-600 mt-4 text-center">
                        Jika lokasi tidak tersedia, pastikan GPS diaktifkan pada perangkat Anda. 
                        <a href="#" class="text-blue-500 underline" onclick="openHelp()">Lihat panduan</a>.
                    </p>  
                    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-12 px-4 sm:px-6 lg:px-8">
                        <div id="help-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500 bg-opacity-50 hidden">
                            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md mx-4 sm:mx-0">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Panduan Mengaktifkan GPS</h3>
                                <ol class="list-decimal list-inside text-gray-600 mb-4">
                                    <li>Buka pengaturan perangkat Anda.</li>
                                    <li>Aktifkan GPS atau layanan lokasi.</li>
                                    <li>Muat ulang halaman ini untuk mencoba kembali.</li>
                                </ol>
                                <button id="close-help-modal" class="px-4 py-2 bg-blue-600 text-white rounded-md w-full text-center">Tutup</button>
                            </div>
                        </div>    

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button id="find-me" type="submit"
                            class="group relative inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:-translate-y-0.5 transition-all duration-200">
                            <i
                                class="fas fa-bullhorn mr-2 group-hover:scale-110 transition-transform duration-200"></i>
                            Daftar Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openHelp() {
        const helpModal = document.getElementById('help-modal');
        helpModal.style.display = 'flex';
    }

    document.getElementById('close-help-modal').addEventListener('click', () => {
        const helpModal = document.getElementById('help-modal');
        helpModal.style.display = 'none';
    });

</script>
<script>
    document.querySelector("#find-me").addEventListener("click", function (event) {
        event.preventDefault(); // Mencegah form submit langsung
        geoFindMe();
    });

    function geoFindMe() {

        const latitudeInput = document.getElementById('latitude');
        const longitudeInput = document.getElementById('longitude');

        function success(position) {
            latitudeInput.value = position.coords.latitude;
            longitudeInput.value = position.coords.longitude;

            latitudeInput.dispatchEvent(new Event("input"));
            longitudeInput.dispatchEvent(new Event("input"));

            window.dispatchEvent(new CustomEvent('register'));
        }

        function error() {
            alert("Tidak dapat mengambil lokasi. Pastikan GPS aktif.");
        }

        if (!navigator.geolocation) {
            alert("Geolocation tidak didukung oleh browser Anda.");
        } else {
            navigator.geolocation.getCurrentPosition(success, error);
        }
    }


</script>