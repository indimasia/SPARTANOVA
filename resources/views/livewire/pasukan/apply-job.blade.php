<div class="p-4 sm:p-6 lg:p-8">
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
            <i class="fas fa-briefcase text-yellow-500"></i>
            Pekerjaan Tersedia
        </h2>

        <!-- Search and Filter -->
        <div class="flex flex-col md:flex-row gap-6 mb-8">
            <div class="flex-1">
                <div class="relative group">
                    <input type="text" wire:model.live="search" placeholder="Cari pekerjaan..."
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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($jobCampaigns as $campaign)
                <div
                    class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
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
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
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
                                <span>{{ $campaign->participantCount }} / {{ $campaign->quota }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                <div class="bg-yellow-500 h-1.5 rounded-full"
                                    style="width: {{ $campaign->quota > 0 ? ($campaign->participantCount / $campaign->quota) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-between mt-4">
                            <button wire:click="showJobDetail({{ $campaign->id }})"
                                class="text-sm font-medium text-yellow-500 hover:text-yellow-600">
                                <i class="fas fa-info-circle mr-1"></i>
                                Detail
                            </button>
                            @if (Auth::user()->jobParticipants()->where('job_id', $campaign->id)->exists())
                                <span
                                    class="inline-flex items-center px-3 py-1.5 border border-gray-200 text-sm font-medium rounded text-gray-400 bg-gray-50 cursor-not-allowed">
                                    <i class="fas fa-check mr-1"></i>
                                    Sudah Dilamar
                                </span>
                            @else
                                <button onclick="confirmApplyJob({{ $campaign->id }})"
                                    class="inline-flex items-center px-3 py-1.5 border border-yellow-500 text-sm font-medium rounded text-yellow-500 bg-white hover:bg-yellow-500 hover:text-white transition-colors duration-200">
                                    <i class="fas fa-paper-plane mr-1"></i>
                                    Lamar
                                </button>
                            @endif
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
                    <div class="p-4 overflow-y-auto">
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
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-tag mr-1"></i>
                                    {{ $selectedJob->type->value }}
                                </span>
                                <span class="text-lg font-semibold text-gray-900">
                                    Rp {{ number_format($selectedJob->reward, 0, ',', '.') }}
                                </span>
                            </div>

                            <!-- Description -->
                            @if ($jobDetail)
                                <div class="prose prose-sm max-w-none mb-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Deskripsi Pekerjaan:</h4>
                                    <div class="text-sm text-gray-600 space-y-2">
                                        {!! nl2br(e($jobDetail->description)) !!}
                                    </div>
                                </div>

                                <!-- Requirements -->
                                @if ($jobDetail->requirements)
                                    <div class="prose prose-sm max-w-none mb-4">
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Persyaratan:</h4>
                                        <div class="text-sm text-gray-600 space-y-2">
                                            {!! nl2br(e($jobDetail->requirements)) !!}
                                        </div>
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
                                Sudah Dilamar
                            </span>
                        @else
                            <button wire:click="applyJob({{ $selectedJob->id }})"
                                class="px-4 py-2 bg-yellow-500 text-white text-sm font-medium rounded hover:bg-yellow-600 transition-colors duration-200">
                                <i class="fas fa-paper-plane mr-1"></i>
                                Lamar Sekarang
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
                'Konfirmasi Lamar Pekerjaan',
                'Apakah Anda yakin ingin melamar pekerjaan ini? Pastikan Anda memenuhi persyaratan yang diminta.',
                () => {
                    @this.applyJob(ok);
                }
            );
    }
</script>