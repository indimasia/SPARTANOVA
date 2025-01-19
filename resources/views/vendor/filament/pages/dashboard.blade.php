<x-filament-panels::page class="fi-dashboard-page">
    @if (method_exists($this, 'filtersForm'))
        {{ $this->filtersForm }}
    @endif

    <x-filament-widgets::widgets :columns="$this->getColumns()" :data="[...property_exists($this, 'filters') ? ['filters' => $this->filters] : [], ...$this->getWidgetData()]" :widgets="$this->getVisibleWidgets()" />
    <div class="space-y-6">
        <div class="p-4 bg-white rounded-lg shadow-sm">
            <h2 class="text-xl font-semibold mb-4">User Locations</h2>
            <div id="user-map" style="height: 450px; width: 100%; position: relative; z-index: 0;"></div>
        </div>

        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var map = L.map('user-map').setView([-5.00000000, 120.00000000], 5);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 18,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                var users = @json($this->getUserLocations());
                users.forEach(function(user) {
                    if (user.latitude && user.longitude) {
                        L.marker([user.latitude, user.longitude])
                            .addTo(map)
                            .bindPopup('<strong>' + user.name + '</strong><br>' + user.village + ', ' + user
                                .district + ', ' + user.regency + ', ' + user.province);
                    }
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
