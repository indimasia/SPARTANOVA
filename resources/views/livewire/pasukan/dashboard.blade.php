<div class="p-4 sm:p-6 lg:p-8">
    @livewire('update-user-data')
    <div class="p-4 sm:p-6 lg:p-8">
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-chart-line text-yellow-500"></i>
                Dashboard
            </h2>
            {{-- <button onclick="subscribeUser()">Aktifkan Notifikasi</button> --}}

            <div class="bg-white border border-gray-100 rounded-lg mt-14">
                <div class="p-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800">Pengumuman Terbaru</h3>
                </div>
                <div class="p-4">

                    @if (!empty($annoucements) )     
                    
                            <div x-data="{            
                                slides: [                
                                  @foreach ($annoucements as $annoucement)
                                {
                                    imgSrc: '{{ $annoucement['image'] ? URL::route('storage.fetch', ['filename' => $annoucement['image']]) : null, }}',
                                    title: '{{ $annoucement['title'] }}',
                                    description: '{{ $annoucement['content'] }}',             
                                    },                
                                    @endforeach
                                    ],             
                                currentSlideIndex: 1,
                                previous() {                
                                    if (this.currentSlideIndex > 1) {                    
                                        this.currentSlideIndex = this.currentSlideIndex - 1                
                                    } else {   
                                        // If it's the first slide, go to the last slide           
                                        this.currentSlideIndex = this.slides.length                
                                    }            
                                },            
                                next() {                
                                    if (this.currentSlideIndex < this.slides.length) {                    
                                        this.currentSlideIndex = this.currentSlideIndex + 1                
                                    } else {                 
                                        // If it's the last slide, go to the first slide    
                                        this.currentSlideIndex = 1                
                                    }            
                                },        
                            }" class="relative w-full overflow-hidden">
                            
                                <!-- previous button -->
                                <button type="button" class="absolute left-5 top-1/2 z-20 flex rounded-full -translate-y-1/2 items-center justify-center bg-surface/40 p-2 text-on-surface transition hover:bg-surface/60 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:outline-offset-0 dark:bg-surface-dark/40 dark:text-on-surface-dark dark:hover:bg-surface-dark/60 dark:focus-visible:outline-primary-dark" aria-label="previous slide" x-on:click="previous()">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="3" class="size-5 md:size-6 pr-0.5" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                                    </svg>
                                </button>
                            
                                <!-- next button -->
                                <button type="button" class="absolute right-5 top-1/2 z-20 flex rounded-full -translate-y-1/2 items-center justify-center bg-surface/40 p-2 text-on-surface transition hover:bg-surface/60 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:outline-offset-0 dark:bg-surface-dark/40 dark:text-on-surface-dark dark:hover:bg-surface-dark/60 dark:focus-visible:outline-primary-dark" aria-label="next slide" x-on:click="next()">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="3" class="size-5 md:size-6 pl-0.5" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                    </svg>
                                </button>
                               
                                <!-- slides -->
                                <div class="relative min-h-[50vh] lg:w-full">
                                    <template x-for="(slide, index) in slides">
                                        <div x-cloak x-show="currentSlideIndex == index + 1" class="absolute inset-0 grid rounded-radius w-full grid-cols-1 md:grid-cols-8 gap-4 overflow-y-auto bg-surface-alt text-on-surface dark:bg-surface-dark-alt dark:text-on-surface-dark" x-transition.opacity.duration.1000ms>

                                            {{-- <article class="group grid rounded-radius  max-full grid-cols-1 md:grid-cols-8 overflow-hidden border border-outline bg-surface-alt text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark"> --}}
                                                <!-- image -->
                                                <div class="col-span-8 lg:col-span-3 rounded">
                                                    <img src='' class="w-full rounded transition duration-700 ease-out group-hover:scale-105" alt="a men wearing VR goggles" x-bind:src="slide.imgSrc" x-bind:alt="slide.imgAlt" />
                                                </div>
                                                <!-- body -->
                                                <div class="flex flex-col  pt-2  col-span-5">
                                                    {{-- <small class="mb-4 font-medium">Artificial Intelligence</small> --}}
                                                    <h3 class="text-balance text-md font-bold text-on-surface-strong lg:text-2xl dark:text-on-surface-dark-strong"   x-text="slide.title" x-bind:aria-describedby="'slide' + (index + 1) + 'Description'" ></h3>
                                                    <p id="articleDescription" class="my-4 lg:max-w-lg md:max-w-md text-pretty text-sm" x-text="slide.description" x-bind:id="'slide' + (index + 1) + 'Description'">
                                                    </p>
                                                </div>
                                            {{-- </article> --}}
                                        </div>
                                    </template>
                                </div>
                                
                                <!-- indicators -->
                                <div class="absolute rounded-radius bottom-3 md:bottom-5 left-1/2 z-20 flex -translate-x-1/2 gap-4 md:gap-3 px-1.5 py-1 md:px-2" role="group" aria-label="slides" >
                                    <template x-for="(slide, index) in slides">
                                        <button class="size-2 rounded-full transition" x-on:click="currentSlideIndex = index + 1" x-bind:class="[currentSlideIndex === index + 1 ? 'bg-on-surface-dark' : 'bg-on-surface-dark/50']" x-bind:aria-label="'slide ' + (index + 1)"></button>
                                    </template>
                                </div>
                            </div>

                            @else

                            <div class="text-center py-6">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                                    <i class="fas fa-clock text-xl text-gray-400"></i>
                                </div>
                                <h3 class="text-sm font-medium text-gray-900">Belum Ada Pengumuman</h3>
                                <p class="text-sm text-gray-500 mt-1">PEngumuman akan muncul di sini</p>
                            </div>
                
                    @endif
                        </div>
                </div>
            </div>


            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white border border-gray-100 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Misi</p>
                            <h3 class="text-xl font-semibold text-gray-800 mt-1">{{ $totalJobs ?? 0 }}</h3>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center">
                            <i class="fas fa-briefcase text-blue-500"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-100 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Menunggu</p>
                            <h3 class="text-xl font-semibold text-gray-800 mt-1">{{ $pendingJobs ?? 0 }}</h3>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-yellow-50 flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-500"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-100 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Disetujui</p>
                            <h3 class="text-xl font-semibold text-gray-800 mt-1">{{ $approvedJobs ?? 0 }}</h3>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center">
                            <i class="fas fa-check text-green-500"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white border border-gray-100 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Poin</p>
                            <h3 class="text-xl font-semibold text-gray-800 mt-1">
                                {{ $totalEarnings }}</h3>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center">
                            <i class="fas fa-wallet text-purple-500"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-lg">
                <div class="p-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800">Aktivitas Terbaru</h3>
                </div>
                <div class="p-4">

                    
                    @if (isset($dataWithdraw))
                    <div class="space-y-4">
                        @foreach ($dataWithdraw as $withdraw)
                            <div class="flex items-center gap-4">
                                <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center">

                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-600">{{ $withdraw->data['message'] }}</p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ \Carbon\Carbon::parse($withdraw['created_at'])->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @endif

                    @if (!empty($recentActivities) && count($recentActivities) > 0)
                        <div class="space-y-4">
                            @foreach ($recentActivities as $activity)
                                <div class="flex items-center gap-4">
                                    <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center">
                                        @switch($activity['type'] ?? '')
                                            @case('apply')
                                                <i class="fas fa-paper-plane text-blue-500"></i>
                                            @break
            
                                            @case('approved')
                                                <i class="fas fa-check text-green-500"></i>
                                            @break
            
                                            @case('completed')
                                                <i class="fas fa-check-double text-purple-500"></i>
                                            @break
            
                                            @default
                                                <i class="fas fa-circle text-gray-400"></i>
                                        @endswitch
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-600">{{ $activity['description'] ?? 'Aktivitas' }}</p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            {{ \Carbon\Carbon::parse($activity['created_at'])->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if (count($recentActivities) === 0 && count($dataWithdraw) === 0 )
                        <div class="text-center py-6">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                                <i class="fas fa-clock text-xl text-gray-400"></i>
                            </div>
                            <h3 class="text-sm font-medium text-gray-900">Belum Ada Aktivitas</h3>
                            <p class="text-sm text-gray-500 mt-1">Aktivitas Anda akan muncul di sini</p>
                        </div>
                    @endif
                </div>
            </div>

            
    </div>
    <div id="location-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500 bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md mx-4 sm:mx-0">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Minta Izin Lokasi</h3>
            <p class="text-gray-600 mb-6">
                Kami memerlukan izin lokasi Anda untuk menyediakan pengalaman yang lebih baik. 
                Apakah Anda bersedia mengaktifkan lokasi?
            </p>
            <div class="flex justify-end">
                <button id="deny-location" class="px-4 py-2 bg-gray-600 text-white rounded-md mr-2">
                    Tolak
                </button>
                <button id="accept-location" class="px-4 py-2 bg-blue-600 text-white rounded-md">
                    Izinkan
                </button>
            </div>
        </div>
    </div>
</div>
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
    async function subscribeUser() {
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
}

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