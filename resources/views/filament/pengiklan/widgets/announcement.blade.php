<x-filament-widgets::widget>
    <x-filament::section>

                <h3 class="text-xl font-semibold">Pengumuman</h3>
       
                    @if (!empty($announcements))

                    @foreach ($announcements as $announcement)
                    <div  class="flex flex-col mb-5 items-center bg-white border border-gray-200 gap-4 rounded-lg shadow-sm md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                        <img class="object-cover w-96 h-96 rounded-t-lg md:h-auto md:w-48 md:rounded-none md:rounded-s-lg" src="{{ $announcement['image'] ? URL::route('storage.fetch', ['filename' => $announcement['image']]) : null }}" alt="">
                        <div class="flex flex-col justify-between p-4 leading-normal">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $announcement['title'] }}</h5>
                            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">{{ $announcement['content'] }}</p>
                        </div>
                    </div>
                    @endforeach
                    <div>
                        <x-filament::pagination :paginator="$announcements" />
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
                    
                

        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </x-filament::section>
</x-filament-widgets::widget>
