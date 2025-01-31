<x-filament::page>
    <div class="space-y-6">
        <div class="p-4 bg-white rounded-lg shadow-sm">
            
            <!-- Dropdown to choose location type -->
            <div class="mb-4 flex flex-row justify-between items-center">
                <h2 class="text-xl font-semibold">User Locations</h2>
                <div class="flex flex-col">
                    <label for="locationType" class="block text-sm font-medium text-gray-700 mb-1">Pilih Lokasi:</label>
                    <select id="locationType" class="block rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm w-full">
                        <option value="current">Lokasi Saat Ini</option>
                        <option value="register">Lokasi Register</option>
                    </select>
                </div>
            </div>

            <div id="user-map" style="height: 450px; width: 100%; position: relative; z-index: 0;"></div>

            <div class="mt-6 p-4 rounded-lg shadow-sm">
                <div class="flex flex-wrap justify-center gap-4">
                    <div class="flex flex-wrap justify-center gap-4 items-center bg-white px-4 py-2 rounded-full shadow-sm">
                        <div class="w-6 h-6 rounded-full mr-4" style="background-color: red;"></div>
                        <span class="text-sm font-medium text-gray-700">Pengiklan Aktif: {{ $this->getActiveUserCounts()['pengiklan'] }}</span>
                    </div>
                    <div class="flex flex-wrap justify-center gap-4 items-center bg-white px-4 py-2 rounded-full shadow-sm">
                        <div class="w-6 h-6 rounded-full mr-4" style="background-color: green;"></div>
                        <span class="text-sm font-medium text-gray-700">Pasukan Aktif: {{ $this->getActiveUserCounts()['pasukan'] }}</span>
                    </div>
                </div>
            </div>
            
        </div>

        {{-- <div class="mt-6 p-4 bg-white rounded-lg shadow-sm">
            <h3 class="text-lg font-semibold">Pemetaaan Pengguna Berdasarkan Kota dan Provinsi</h3>
            
            <!-- Mapa lokasi register -->
            <h4 class="text-md font-medium text-gray-700 mt-4">Lokasi Register</h4>
            <div class="grid grid-cols-2 gap-4">
                @foreach($this->getCityUserCount()['register'] as $province => $cities)
                    <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                        <h5 class="text-sm font-medium text-gray-700">{{ $province }}</h5>
                        @foreach($cities as $city => $counts)
                            <div class="mt-2">
                                <h6 class="text-sm font-medium text-gray-600">{{ $city }}</h6>
                                <p class="text-sm text-gray-600">Pasukan: {{ $counts['pasukan'] }}</p>
                                <p class="text-sm text-gray-600">Pengiklan: {{ $counts['pengiklan'] }}</p>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        
            <!-- Mapa lokasi saat ini -->
            <h4 class="text-md font-medium text-gray-700 mt-4">Lokasi Saat Ini</h4>
            <div class="grid grid-cols-2 gap-4">
                @foreach($this->getCityUserCount()['current'] as $province => $cities)
                    <div class="p-4 bg-gray-50 rounded-lg shadow-sm">
                        <h5 class="text-sm font-medium text-gray-700">{{ $province }}</h5>
                        @foreach($cities as $city => $counts)
                            <div class="mt-2">
                                <h6 class="text-sm font-medium text-gray-600">{{ $city }}</h6>
                                <p class="text-sm text-gray-600">Pasukan: {{ $counts['pasukan'] }}</p>
                                <p class="text-sm text-gray-600">Pengiklan: {{ $counts['pengiklan'] }}</p>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div> --}}
        
        

        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var map = L.map('user-map').setView([-5.00000000, 120.00000000], 5);
                var greenIcon = new L.Icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                });
                var redIcon = new L.Icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 18,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                var users = @json($this->getUserLocations());
                var locationType = document.getElementById('locationType');
                var markers = [];

                function updateMap(type) {
                    // Clear existing markers
                    markers.forEach(marker => map.removeLayer(marker));
                    markers = [];

                    users.forEach(function (user) {
                        if (type === 'current' && user.current_latitude && user.current_longitude) {
                            var marker = L.marker([user.current_latitude, user.current_longitude], {icon: user.role === 'pengiklan' ? redIcon : greenIcon})
                                .addTo(map)
                                .bindPopup('<strong>NAMA:</strong> ' + user.name + '<br><br>' + 
                                           '<strong>ROLE:</strong> ' + user.role + '<br><br>' + 
                                           '<strong>PHONE:</strong> ' + user.phone + '<br><br>' + 
                                           '<strong>LOKASI SAAT INI:</strong> ' + user.lokasisaatini + '<br><br>' + 
                                           '<strong>LOKASI REGISTER:</strong> ' + user.lokasiregister);
                            markers.push(marker);
                        } else if (type === 'register' && user.latitude && user.longitude) {
                            var marker = L.marker([user.latitude, user.longitude], {icon: user.role === 'pengiklan' ? redIcon : greenIcon})
                                .addTo(map)
                                .bindPopup('<strong>NAMA:</strong> ' + user.name + '<br><br>' + 
                                           '<strong>ROLE:</strong> ' + user.role + '<br><br>' + 
                                           '<strong>PHONE:</strong> ' + user.phone + '<br><br>' + 
                                           '<strong>LOKASI SAAT INI:</strong> ' + user.lokasisaatini + '<br><br>' + 
                                           '<strong>LOKASI REGISTER:</strong> ' + user.lokasiregister);
                            markers.push(marker);
                        }
                    });
                }

                // Initialize map with default type
                updateMap(locationType.value);

                // Update map when dropdown changes
                locationType.addEventListener('change', function () {
                    updateMap(this.value);
                });

                var mapElement = document.getElementById('user-map');
                if (mapElement) {
                    mapElement.style.zIndex = 0;
                }

                var navbar = document.querySelector('.filament-sidebar');
                if (navbar) {
                    navbar.style.zIndex = 1000;
                }
            });
        </script>
    </div>
</x-filament::page>
