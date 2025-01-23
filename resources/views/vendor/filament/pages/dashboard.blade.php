<x-filament-panels::page class="fi-dashboard-page">
    @if (method_exists($this, 'filtersForm'))
        {{ $this->filtersForm }}
    @endif

    <x-filament-widgets::widgets :columns="$this->getColumns()" :data="[...property_exists($this, 'filters') ? ['filters' => $this->filters] : [], ...$this->getWidgetData()]" :widgets="$this->getVisibleWidgets()" />
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
        </div>

        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var map = L.map('user-map').setView([-5.00000000, 120.00000000], 5);

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
                            var marker = L.marker([user.current_latitude, user.current_longitude])
                                .addTo(map)
                                .bindPopup('<strong>NAMA:</strong> ' + user.name + '<br><br>' + 
                                           '<strong>PHONE:</strong> ' + user.phone + '<br><br>' + 
                                           '<strong>LOKASI SAAT INI:</strong> ' + user.lokasisaatini + '<br><br>' + 
                                           '<strong>LOKASI REGISTER:</strong> ' + user.lokasiregister);
                            markers.push(marker);
                        } else if (type === 'register' && user.latitude && user.longitude) {
                            var marker = L.marker([user.latitude, user.longitude])
                                .addTo(map)
                                .bindPopup('<strong>NAMA:</strong> ' + user.name + '<br><br>' + 
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
</x-filament-panels::page>
