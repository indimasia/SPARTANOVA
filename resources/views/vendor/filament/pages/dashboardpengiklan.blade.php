<x-filament-panels::page class="fi-dashboard-page">
    @if (method_exists($this, 'filtersForm'))
        {{ $this->filtersForm }}
    @endif

    <x-filament-widgets::widgets
        :columns="$this->getColumns()"
        :data="
            [
                ...(property_exists($this, 'filters') ? ['filters' => $this->filters] : []),
                ...$this->getWidgetData(),
            ]
        "
        :widgets="$this->getVisibleWidgets()"
    />
    <script>
        document.addEventListener('DOMContentLoaded', () => {
    
            // Coba untuk mendapatkan lokasi secara otomatis
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const latitude = position.coords.latitude;
                        const longitude = position.coords.longitude;
                        
                        // Kirim data lokasi ke server menggunakan Livewire
                        Livewire.dispatch('updateLocation', { latitude, longitude });
    
                        
                    },
                    (error) => {
                        alert('Tidak dapat mengambil lokasi. Pastikan izin lokasi diaktifkan.');
                    }
                );
            } else {
                alert('Browser Anda tidak mendukung Geolocation.');
            }
    
        });
    </script>
    
    
</x-filament-panels::page>
