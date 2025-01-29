<div class="p-4 sm:p-6 lg:p-8">
    @livewire('update-user-data')
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
            <i class="fas fa-briefcase text-yellow-500"></i>
            Misi Tersedia
        </h2>

        <!-- Search and Filter -->
        <div class="flex flex-col md:flex-row gap-6 mb-8">
            <div class="flex-1">
                <div class="relative group">
                    <input type="text" wire:model.live="search" placeholder="Cari misi..."
                        class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-700 placeholder-gray-400 transition-all duration-300 focus:bg-white focus:ring-2 focus:ring-yellow-400 focus:border-transparent hover:border-yellow-300">
                    <i
                        class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-hover:text-yellow-500 transition-colors duration-300"></i>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="relative">
                    <select wire:model.live="selectedPlatform"
                        class="appearance-none w-full bg-gray-50 border border-gray-200 rounded-xl pl-4 pr-10 py-3 text-gray-700 cursor-pointer transition-all duration-300 focus:bg-white focus:ring-2 focus:ring-yellow-400 focus:border-transparent hover:border-yellow-300">
                        <option value="">Semua Platform</option>
                        @foreach ($platforms as $platform)
                            <option value="{{ $platform->value }}">{{ $platform->value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="relative">
                    <select wire:model.live="selectedType"
                        class="appearance-none w-full bg-gray-50 border border-gray-200 rounded-xl pl-4 pr-10 py-3 text-gray-700 cursor-pointer transition-all duration-300 focus:bg-white focus:ring-2 focus:ring-yellow-400 focus:border-transparent hover:border-yellow-300">
                        <option value="">Semua Tipe</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->value }}">{{ $type->value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Job Grid -->
        <div class="space-y-6">
            <!-- Applied Jobs Section -->
            @php
                $availableJobs = $jobCampaigns->filter(function($campaign) {
                    return !Auth::user()->jobParticipants()->where('job_id', $campaign->id)->exists();
                });
                // dd($availableJobs);
            @endphp

            <!-- Available Jobs Section -->
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-semibold text-gray-900">Misi Yang Tersedia</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        {{ $availableJobs->count() }}
                    </span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($availableJobs as $campaign)
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="p-4">
                                <!-- Platform & Date -->
                                <div class="flex items-center justify-between mb-3">
                                    <span class="inline-flex items-center gap-1 text-sm font-medium text-gray-600">
                                        @switch(strtolower($campaign->platform->value))
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
                                            @case('whatsapp')
                                                <i class="fab fa-whatsapp text-green-600 text-lg"></i>
                                            @break
                                            @case('google')
                                                <i class="fab fa-google text-blue-600 text-lg"></i>
                                            @break
                                            @default
                                                <i class="fas fa-globe text-gray-600 text-lg"></i>
                                        @endswitch
                                        {{ $campaign->platform->value }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ $campaign->created_at->format('d M Y') }}
                                    </span>
                                </div>

                                <!-- Title -->
                                <h3 class="text-base font-medium text-gray-900 mb-2">
                                    {{ $campaign->title }}
                                </h3>

                                <!-- Type & Reward -->
                                <div class="flex items-center justify-between mb-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-tag mr-1"></i>
                                        {{ $campaign->type->value }}
                                    </span>
                                    <span class="text-sm font-semibold text-gray-900">
                                        Rp {{ number_format($campaign->reward, 0, ',', '.') }}
                                    </span>
                                </div>

                                <!-- Quota -->
                                <div class="mb-3">
                                    <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                                        <span>Kuota Tersisa</span>
                                        <span>{{ $campaign->activeParticipant }} / {{ $campaign->quota }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                        <div class="bg-yellow-500 h-1.5 rounded-full"
                                            style="width: {{ $campaign->quota > 0 ? ($campaign->activeParticipant / $campaign->quota) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center justify-between">
                                    <a href="{{ route('job.detail', ['jobId' => $campaign->id]) }}"
                                        class="text-sm font-medium text-blue-500 hover:text-blue-600">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Detail
                                     </a>

                                    <button onclick="confirmApplyJob({{ $campaign->id }})"
                                        class="inline-flex items-center px-3 py-1.5 border border-yellow-500 text-sm font-medium rounded text-yellow-500 bg-white hover:bg-yellow-500 hover:text-white transition-colors duration-200">
                                        <i class="fas fa-paper-plane mr-1"></i>
                                        Ambil Misi
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-8">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                                <i class="fas fa-briefcase text-2xl text-gray-400"></i>
                            </div>
                            <h3 class="text-sm font-medium text-gray-900 mb-1">Belum Ada Pekerjaan</h3>
                            <p class="text-sm text-gray-500">Belum ada pekerjaan yang tersedia saat ini</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Job Detail Modal -->
        @if ($showModal)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-lg max-w-2xl w-full max-h-[90vh] overflow-hidden">
                <!-- Modal Header -->
                <div class="p-4 border-b border-gray-100 flex justify-between items-center sticky top-0 bg-white">
                    <h3 class="text-lg font-semibold text-gray-800">Detail Pekerjaan</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <!-- Modal Content -->
                <div class="p-4 overflow-y-auto max-h-[70vh]">
                    @if ($selectedJob)
                        <!-- Platform & Date -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="inline-flex items-center gap-1 text-sm font-medium text-gray-600">
                                @switch(strtolower($selectedJob->platform->value))
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

                                    @default
                                        <i class="fas fa-globe text-gray-600 text-lg"></i>
                                @endswitch
                                {{ $selectedJob->platform->value }}
                            </span>
                            <span class="text-xs text-gray-500">
                                {{ $selectedJob->created_at->format('d M Y') }}
                            </span>
                        </div>

                        <!-- Title -->
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">
                            {{ $selectedJob->title }}
                        </h2>

                        <!-- Type & Reward -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center justify-between mb-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-tag mr-1"></i>
                                    {{ $selectedJob->type->value }}
                                </span>
                                @if ($jobDetail && $jobDetail->url_link)
                                    <a href="{{ $jobDetail->url_link }}" target="_blank" class="text-yellow-500 hover:text-yellow-600 text-sm font-medium flex items-center ml-2">
                                        <i class="fas fa-link mr-1"></i> Kunjungi Link
                                    </a>
                                @endif
                            </div>

                            <span class="text-lg font-semibold text-gray-900">
                                Rp {{ number_format($selectedJob->reward, 0, ',', '.') }}
                            </span>
                        </div>

                        <!-- Description -->
                        @if ($jobDetail)
                            @if ($jobDetail->image)
                                <div class="mb-4">
                                    <a href="{{ asset('storage/' . $jobDetail->image) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $jobDetail->image) }}" alt="Job Image" class="w-full rounded-lg">
                                    </a>
                                </div>
                            @endif
                            @if ($jobDetail->description || $jobDetail->requirements || $selectedJob->instructions || collect(['specific_gender', 'specific_generation', 'specific_interest', 'specific_province', 'specific_regency', 'specific_district', 'specific_village'])->filter(fn($attr) => $jobDetail->$attr)->isNotEmpty())
                                <div class="mb-6 p-6 bg-white border border-gray-200 rounded-lg shadow-md">
                                    <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                                        <i class="fas fa-briefcase text-yellow-500 mr-2"></i> Detail Pekerjaan
                                    </h4>

                                    <div class="space-y-6">
                                        <!-- Deskripsi Pekerjaan -->
                                        @if ($jobDetail->description)
                                            <div>
                                                <h5 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                                    <i class="fas fa-info-circle text-yellow-500 mr-1"></i> Deskripsi Pekerjaan:
                                                </h5>
                                                <p class="text-sm text-gray-600 leading-relaxed">
                                                    {!! nl2br(e($jobDetail->description)) !!}
                                                </p>
                                            </div>
                                        @endif

                                        <!-- Persyaratan -->
                                        @if ($jobDetail->requirements)
                                            <div>
                                                <h5 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                                    <i class="fas fa-check-circle text-yellow-500 mr-1"></i> Persyaratan:
                                                </h5>
                                                <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                                                    @foreach (explode("\n", $jobDetail->requirements) as $requirement)
                                                        <li>{{ $requirement }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <!-- Petunjuk -->
                                        @if ($selectedJob->instructions)
                                            <div>
                                                <h5 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                                    <i class="fas fa-lightbulb text-yellow-500 mr-1"></i> Petunjuk:
                                                </h5>
                                                <p class="text-sm text-gray-600 leading-relaxed">
                                                    {!! $selectedJob->instructions !!}
                                                </p>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Informasi Spesifik -->
                                    @php
                                        $specificAttributes = collect([
                                            'specific_gender' => 'Jenis Kelamin',
                                            'specific_generation' => 'Generasi',
                                            'specific_interest' => 'Minat Khusus',
                                        ])->filter(fn($label, $key) => $jobDetail->$key);
                                    @endphp

                                    @if ($specificAttributes->isNotEmpty())
                                        <div class="mt-6">
                                            <h5 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                                <i class="fas fa-list-alt text-yellow-500 mr-1"></i> Informasi Tambahan:
                                            </h5>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                @foreach ($specificAttributes as $key => $label)
                                                    <div>
                                                        <h6 class="text-sm font-semibold text-gray-800">{{ $label }}:</h6>
                                                        <div class="text-sm text-gray-600">
                                                            @if (is_array($jobDetail->$key))
                                                                <ul class="list-disc pl-5">
                                                                    @foreach ($jobDetail->$key as $item)
                                                                        <li>{{ $item }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            @else
                                                                <p>{{ $jobDetail->$key }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    @if ($province || $regency || $district || $village)
                                        <div class="mt-6">
                                            <h5 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                                <i class="fas fa-map-marker-alt text-yellow-500 mr-1"></i> Lokasi:
                                            </h5>
                                            <div class="space-y-2 text-sm text-gray-600">
                                                @if ($province)
                                                    <p><strong>Provinsi:</strong> {{ $province->nama }}</p>
                                                @endif
                                                @if ($regency)
                                                    <p><strong>Kabupaten/Kota:</strong> {{ $regency->nama }}</p>
                                                @endif
                                                @if ($district)
                                                    <p><strong>Kecamatan:</strong> {{ $district->nama }}</p>
                                                @endif
                                                @if ($village)
                                                    <p><strong>Desa/Kelurahan:</strong> {{ $village->nama }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endif

                        <!-- Quota -->
                        <div class="mb-4">
                            <div class="flex items-center justify-between text-sm text-gray-600 mb-1">
                                <span>Kuota Tersisa</span>
                                <span>{{ $selectedJob->participantCount }} / {{ $selectedJob->quota }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-yellow-500 h-2 rounded-full"
                                    style="width: {{ $selectedJob->quota > 0 ? ($selectedJob->participantCount / $selectedJob->quota) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>

                        <!-- Dates -->
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-calendar-alt text-yellow-500 mr-1"></i> Tanggal Mulai:
                                </h4>
                                <p class="text-sm text-gray-600">{{ $selectedJob->start_date }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-calendar-check text-yellow-500 mr-1"></i> Tanggal Berakhir:
                                </h4>
                                <p class="text-sm text-gray-600">{{ $selectedJob->end_date }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Modal Footer -->
                <div class="p-4 border-t border-gray-100 flex justify-end gap-3 sticky bottom-0 bg-white">
                    <button wire:click="closeModal"
                        class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800">
                        Tutup
                    </button>
                    @if (Auth::user()->jobParticipants()->where('job_id', $selectedJob->id)->exists())
                        <span
                            class="inline-flex items-center px-4 py-2 border border-gray-200 text-sm font-medium rounded text-gray-400 bg-gray-50 cursor-not-allowed">
                            <i class="fas fa-check mr-1"></i>
                            Sudah Diambil
                        </span>
                    @else
                        <button wire:click="applyJob({{ $selectedJob->id }})"
                            class="px-4 py-2 bg-yellow-500 text-white text-sm font-medium rounded hover:bg-yellow-600 transition-colors duration-200">
                            <i class="fas fa-paper-plane mr-1"></i>
                            Ambil Misi
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

<script>
    function confirmApplyJob(ok) {
        showConfirmation(
                'Konfirmasi Pengambilan Misi',
                'Apakah Anda yakin ingin mengambil misi ini? Pastikan Anda memenuhi persyaratan yang diminta.',
                () => {
                    @this.applyJob(ok);
                }
            );
    }

    document.querySelectorAll('[id^="shareButton-"]').forEach(button => {
    button.addEventListener('click', async () => {
        const campaignId = button.id.split('-')[1];
        await shareImageAndContent(campaignId);
    });
});

// Fungsi untuk berbagi konten
async function shareImageAndContent(campaignId) {
    try {
        console.log(campaignId);

        const imageUrl = campaign.jobDetail.image;
        console.log(imageUrl);
        // Ambil gambar dari server
        const response = await fetch(imageUrl);
        const blob = await response.blob();

        // Siapkan file untuk Web Share API
        const filesArray = [
            new File([blob], `misi.jpg`, {
                type: "image/jpeg",
                lastModified: new Date().getTime(),
            }),
        ];

        // Data yang akan dibagikan
        const shareData = {
            title: 'Kerjakan Misi Sekarang',
            text: 'Ayo kerjakan misi sekarang!',
            files: filesArray,
        };

        // Periksa apakah browser mendukung Web Share API dengan file
        if (navigator.canShare && navigator.canShare({ files: filesArray })) {
            await navigator.share(shareData);
            console.log('Konten berhasil dibagikan!');
        } else {
            console.error('Browser tidak mendukung fitur berbagi file.');
            alert('Browser Anda tidak mendukung fitur ini.');
        }
    } catch (error) {
        console.error('Gagal membagikan konten:', error);
        alert('Terjadi kesalahan saat mencoba membagikan konten.');
    }
}

</script>
