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

    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            alert('Halaman telah dimuat. Kami ingin mengirimkan notifikasi kepada Anda.');
            
            if (Notification.permission === 'granted') {
                console.log('Notification.permission === granted');
                subscribeUser();
            } else if (Notification.permission !== 'denied') {
                let permission = await Notification.requestPermission();
                
                if (permission === 'granted') {
                    subscribeUser();
                } else {
                    alert('Anda menolak notifikasi.');
                }
            } else {
                alert('Anda sebelumnya telah menolak notifikasi. Jika ingin mengaktifkannya, silakan ubah pengaturan browser.');
            }
        });

        async function subscribeUser() {
            try {
                const registration = await navigator.serviceWorker.ready;
                const subscription = await registration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: 'BN77r8Fxr66uWMEmKjvojYkmW_d0_LonsLVBIbBFzXeDEJYsuGPBs3oIePDqIM_c6GB79LF8XmPDEkLdHW4artg',
                });
                
                await fetch('/subscribe', {
                    method: 'POST',
                    body: JSON.stringify(subscription),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                alert('Berhasil berlangganan notifikasi!');
            } catch (error) {
                console.error('Gagal berlangganan notifikasi:', error);
                alert('Gagal berlangganan notifikasi. Coba lagi nanti.');
            }
        }
    </script>
    
</x-filament-panels::page>
