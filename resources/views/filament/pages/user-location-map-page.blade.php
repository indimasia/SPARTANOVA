<x-filament::page>
    <div id="user-map" class="pt-10" style="height: 500px; width: 100%; z-index: 0;"></div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var map = L.map('user-map').setView([-5.00000000, 120.00000000], 5);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            var u   ers = @json($this->getUserLocations());
            users.forEach(function(user) {
                if (user.latitude && user.longitude) {
                    L.marker([user.latitude, user.longitude])
                        .addTo(map)
                        .bindPopup('<strong>' + user.name + '</strong><br>' + user.village + ', ' + user.district + ', ' + user.regency + ', ' + user.province);
                }
            });
        });
    </script>
</x-filament::page>
