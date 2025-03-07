<div class="p-4 sm:p-6 lg:p-8">
    @if(session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <ul class="mt-1 list-disc list-inside">
                <li>{{ session('error') }}</li>
            </ul>
        </div>
    @endif

    @if(session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Success!</strong>
            <ul class="mt-1 list-disc list-inside">
                <li>{{ session('message') }}</li>
            </ul>
        </div>
    @endif
    
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
            <i class="fas fa-history text-yellow-500"></i>
            Laporan Riwayat Misi
        </h2>

        <!-- Desktop View - Table -->
        <div class="hidden md:block overflow-x-auto pb-20">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kampanye</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Dibuat Oleh</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Platform</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Reward</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            View</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($jobHistory as $history)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $history->job->title }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $history->job->createdBy->name ?? 'Admin' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex items-center gap-1">
                                    @switch(strtolower($history->job->platform->value))
                                        @case('facebook')
                                            <i class="fab fa-facebook text-blue-600"></i>
                                        @break

                                        @case('instagram')
                                            <i class="fab fa-instagram text-pink-600"></i>
                                        @break

                                        @case('twitter')
                                            <i class="fab fa-twitter text-blue-400"></i>
                                        @break

                                        @case('tiktok')
                                            <i class="fab fa-tiktok text-black"></i>
                                        @break

                                        @case('youtube')
                                            <i class="fab fa-youtube text-red-600"></i>
                                        @break

                                        @case('whatsapp')
                                            <i class="fab fa-whatsapp text-green-600"></i>
                                        @break

                                        @case('google')
                                            <i class="fab fa-google text-blue-600"></i>
                                        @break

                                        @default
                                            <i class="fas fa-globe text-gray-600"></i>
                                    @endswitch
                                    {{ $history->job->platform->value }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                Rp {{ number_format($history->reward, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @switch($history->status)
                                    @case('pending')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>
                                            Menunggu
                                        </span>
                                    @break

                                    @case('approved')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>
                                            Disetujui
                                        </span>
                                    @break

                                    @case('rejected')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-1"></i>
                                            Ditolak
                                        </span>
                                    @break

                                    @case('completed')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-check-double mr-1"></i>
                                            Selesai
                                        </span>
                                    @break

                                    @default
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $history->status }}
                                        </span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $history->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $history->views }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium relative">
                                <div x-data="{ open: false }" class="inline-block text-left">
                                    <!-- Tombol tiga titik -->
                                    <button @click="open = !open" class="text-gray-600 hover:text-gray-800">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                            
                                    <!-- Dropdown Aksi -->
                                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-40 bg-white border rounded-md shadow-lg z-50">
                                        <ul class="py-1 text-sm text-gray-700">
                                            {{-- @if ($history->status === 'Applied' && $history->attachment == null)
                                                <button wire:click="showUploadModal({{ $history->id }})" class="text-blue-600 hover:text-blue-900 text-xs font-medium">
                                                    <i class="fas fa-upload mr-1"></i>
                                                    Upload Bukti
                                                </button>
                                            @elseif($history->attachment)
                                                <button wire:click="showAttachmentModal({{ $history->id }})" class="text-green-600 hover:text-green-900 text-xs font-medium">
                                                    <i class="fas fa-eye mr-1"></i>
                                                    Lihat Bukti
                                                </button>
                                            @else
                                                <span class="text-gray-500 text-xs">
                                                    <i class="fas fa-info-circle mr-1"></i>
                                                    Menunggu persetujuan
                                                </span>
                                            @endif
                                            <button wire:click="showUpdateViewModal({{ $history->id }})" class="text-purple-600 hover:text-purple-900 text-xs font-medium">
                                                <i class="fas fa-chart-line mr-1"></i>
                                                Update View
                                            </button> --}}


                                            @if ($history->status === 'Applied' && $history->attachment == null)
                                                <li>
                                                    <button wire:click="showUploadModal({{ $history->id }})" class="block px-4 py-2 hover:bg-gray-100 w-full text-left">
                                                        <i class="fas fa-upload mr-1"></i> Upload Bukti
                                                    </button>
                                                </li>
                                            @elseif($history->attachment)
                                                <li>
                                                    <button wire:click="showAttachmentModal({{ $history->id }})" class="block px-4 py-2 hover:bg-gray-100 w-full text-left">
                                                        <i class="fas fa-eye mr-1"></i> Lihat Bukti
                                                    </button>
                                                </li>
                                            @else
                                                <li>
                                                    <span class="text-gray-500 text-xs">
                                                        <i class="fas fa-info-circle mr-1"></i>
                                                        Menunggu persetujuan
                                                    </span>
                                                </li>
                                            @endif

                                            @if($history->job->platform->value === 'WhatsApp' && $history->job->type->value === 'Posting')
                                                <li>
                                                    <button wire:click="showUpdateViewModal({{ $history->id }})" class="block px-4 py-2 hover:bg-gray-100 w-full text-left">
                                                        <i class="fas fa-chart-line mr-1"></i> Update View
                                                    </button>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </td>                            
                        </tr>

                        <!-- Modal Lihat Bukti -->
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Belum ada riwayat misi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div x-data="{ open: @entangle('viewAttachmentModal'), editing: false, status: @entangle('status') }" x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500 bg-opacity-50">
                <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md mx-4 sm:mx-0">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Lihat Bukti</h3>
                    <div class="mb-4">
                        {{-- @dd($history->attachment) --}}
                        @if($jobParticipant)
                            <img src="{{ asset('storage/' . $jobParticipant->attachment) }}" 
                                alt="Bukti Bayar" 
                                class="mx-auto rounded-lg shadow-md" 
                                style="max-width: 100%; max-height: 400px;">
                        @else
                            <img src="https://placehold.co/400x400?text=Tidak+Ada+Gambar" alt="Tidak Ada Gambar" class="mx-auto rounded-lg shadow-md" style="max-width: 100%; max-height: 400px;">
                        @endif
                    </div>
                    <template x-if="!editing">
                        <div class="flex justify-between">
                            <button type="button" class="px-4 py-2 text-white bg-blue-600 rounded-md" 
                            x-show="status === '{{ \App\Enums\JobStatusEnum::REPORTED->value }}'" 
                            @click="editing = true">Edit Bukti</button>
                            <button type="button" class="px-4 py-2 text-white bg-gray-600 rounded-md" @click="open = false">Tutup</button>
                        </div>
                    </template>
                    <template x-if="editing">
                        <div>
                            <form wire:submit.prevent="updateAttachment" @submit="editing = false; open = false">
                                <input type="file" wire:model="attachment" class="block w-full text-sm text-gray-600">
                                <div class="text-red-500 mt-2" wire:loading wire:target="attachment">Uploading...</div> 
                                @error('attachment') <span class="text-red-500">{{ $message }}</span> @enderror
                                <div class="flex justify-between mt-4">
                                    <button type="submit" class="px-4 py-2 text-white bg-green-600 rounded-md">Simpan</button>
                                    <button type="button" class="px-4 py-2 text-white bg-gray-600 rounded-md" @click="editing = false">Batal</button>
                                </div>
                            </form>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Modal Upload Bukti -->
            <div x-data="{ open: @entangle('showModal') }" x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500 bg-opacity-50">
                <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md mx-4 sm:mx-0">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Upload Bukti</h3>
                    <form wire:submit.prevent="uploadBukti">
                        <div class="mb-4">
                            <label for="attachment" class="block text-sm font-medium text-gray-700">Pilih File</label>
                            <input type="file" id="attachment" wire:model="attachment" class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md">
                            @error('attachment') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                            @if(session()->has('error'))
                            {{-- <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <strong class="font-bold">Error!</strong>
                                <ul class="mt-1 list-disc list-inside">
                                    <li>{{ session('error') }}</li>
                                </ul>
                            </div> --}}
                            <span class="text-red-500 text-xs">{{ session('error') }}</span> 
                            @endif
                            
                        </div>
                
                        <div class="flex justify-end gap-2">
                            <button type="button" class="px-4 py-2 text-white bg-gray-600 rounded-md" @click="open = false">Batal</button>
                            <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-md">Upload</button>
                        </div>
                    </form>
                </div>
            </div>

            <div x-data="{ open: @entangle('showUpdateView') }" x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500 bg-opacity-50">
                <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md mx-4 sm:mx-0">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Update Jumlah View</h3>
                    <form wire:submit.prevent="updateView">
                        <div class="mb-4">
                            <label for="views" class="block text-sm font-medium text-gray-700">Jumlah View Terbaru</label>
                            <input type="number" id="views" wire:model="views" class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md" min="0" value="{{ $views }}">
                            @error('views') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                
                        <div class="flex justify-end gap-2">
                            <button type="button" class="px-4 py-2 text-white bg-gray-600 rounded-md" @click="open = false">Batal</button>
                            <button type="submit" class="px-4 py-2 text-white bg-purple-600 rounded-md">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Mobile View - Cards -->
            <div class="md:hidden space-y-4">
                @forelse($jobHistory as $history)
                    <div
                        class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden">
                        <div class="p-4">
                            <div class="flex items-center justify-between mb-3">
                                <span class="inline-flex items-center gap-1 text-sm font-medium text-gray-600">
                                    @switch(strtolower($history->job->platform->value))
                                        @case('facebook')
                                            <i class="fab fa-facebook text-blue-600 text-lg"></i>
                                        @break

                                        @case('instagram')
                                            <i class="fab fa-instagram text-pink-600 text-lg"></i>
                                        @break

                                        @case('twitter')
                                            <i class="fab fa-twitter text-blue-400 text-lg"></i>
                                        @break

                                        @case('tiktok')
                                            <i class="fab fa-tiktok text-black text-lg"></i>
                                        @break

                                        @case('youtube')
                                            <i class="fab fa-youtube text-red-600 text-lg"></i>
                                        @break

                                        @case('google')
                                            <i class="fab fa-google text-green-600 text-lg"></i>
                                        @break

                                        @case('whatsapp')
                                            <i class="fab fa-whatsapp text-green-600 text-lg"></i>
                                        @break

                                        @default
                                            <i class="fas fa-globe text-gray-600 text-lg"></i>
                                    @endswitch
                                    {{ $history->job->platform->value }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ $history->created_at->format('d M Y') }}
                                </span>
                            </div>

                            <h3 class="text-base font-medium text-gray-900 mb-2">
                                {{ $history->job->title }}
                            </h3>

                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-semibold text-gray-900">
                                    Rp {{ number_format($history->reward, 0, ',', '.') }}
                                </span>
                                @switch($history->status)
                                    @case('pending')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>
                                            Menunggu
                                        </span>
                                    @break

                                    @case('approved')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>
                                            Disetujui
                                        </span>
                                    @break

                                    @case('rejected')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-1"></i>
                                            Ditolak
                                        </span>
                                    @break

                                    @case('completed')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-check-double mr-1"></i>
                                            Selesai
                                        </span>
                                    @break

                                    @default
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $history->status }}
                                        </span>
                                @endswitch
                            </div>

                            @if ($history->status === 'Applied' && $history->attachment == null)
                                <div class="mt-3 text-center">
                                    <button wire:click="showUploadModal({{ $history->id }})"
                                        class="inline-flex items-center text-blue-600 hover:text-blue-900 text-sm font-medium">
                                        <i class="fas fa-upload mr-1"></i>
                                        Upload Bukti
                                    </button>
                                </div>
                            @elseif($history->attachment)
                                <div class="mt-3 text-center">
                                    <button wire:click="showAttachmentModal({{ $history->id }})"
                                        class="inline-flex items-center text-green-600 hover:text-green-900 text-sm font-medium">
                                        <i class="fas fa-eye mr-1"></i>
                                        Lihat Bukti
                                    </button>
                                </div>
                            @else
                                <span class="text-gray-500 text-sm">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Menunggu persetujuan
                                </span>
                            @endif

                            @if($history->job->platform->value === 'WhatsApp' && $history->job->type->value === 'Posting')
                                <div class="mt-3 text-center">
                                    <button wire:click="showUpdateViewModal({{ $history->id }})"
                                        class="inline-flex items-center text-purple-600 hover:text-purple-900 text-sm font-medium">
                                        <i class="fas fa-chart-line mr-1"></i>
                                        Update View
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                                <i class="fas fa-history text-2xl text-gray-400"></i>
                            </div>
                            <h3 class="text-sm font-medium text-gray-900 mb-1">Belum Ada Riwayat</h3>
                            <p class="text-sm text-gray-500">Anda belum memiliki riwayat misi</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
