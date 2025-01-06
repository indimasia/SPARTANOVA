<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="mb-4 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded relative"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-4 bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Header Section -->
        <div class="mb-8 text-center">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Daftar Kampanye Kerja</h1>
            <p class="text-sm text-gray-600">Temukan peluang kampanye yang sesuai dengan keahlian Anda</p>
        </div>

        <!-- Campaign Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach ($jobCampaigns as $job)
                <div
                    class="bg-white rounded-lg overflow-hidden border border-gray-100 hover:shadow-md transition-all duration-300 group">
                    <!-- Platform Icon -->
                    <div
                        class="flex items-center justify-center bg-gray-50 p-4 group-hover:bg-gray-100 transition-colors duration-300">
                        @switch($job->platform->value)
                            @case('Instagram')
                                <i
                                    class="fab fa-instagram text-3xl text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600"></i>
                            @break

                            @case('Facebook')
                                <i class="fab fa-facebook text-3xl text-blue-600"></i>
                            @break

                            @case('Twitter')
                                <i class="fab fa-twitter text-3xl text-sky-500"></i>
                            @break

                            @case('LinkedIn')
                                <i class="fab fa-linkedin text-3xl text-blue-700"></i>
                            @break

                            @case('TikTok')
                                <i class="fab fa-tiktok text-3xl text-black"></i>
                            @break

                            @case('Youtube')
                                <i class="fab fa-youtube text-3xl text-red-600"></i>
                            @break

                            @default
                                <i class="fas fa-briefcase text-3xl text-gray-600"></i>
                        @endswitch
                    </div>

                    <!-- Campaign Info -->
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span
                                class="text-[10px] font-medium px-2 py-1 rounded-full
                                @switch($job->type->value)
                                    @case('Komentar') bg-blue-100 text-blue-700 @break
                                    @case('Like') bg-green-100 text-green-700 @break
                                    @case('Follow') bg-purple-100 text-purple-700 @break
                                    @default bg-gray-100 text-gray-700
                                @endswitch">
                                {{ $job->type->value }}
                            </span>
                            <span class="text-[10px] font-medium text-gray-500">{{ $job->platform->value }}</span>
                        </div>

                        <h2 class="text-sm font-semibold text-gray-800 mb-2 line-clamp-2 min-h-[2.5rem]">
                            {{ $job->title }}</h2>

                        <div class="flex items-center justify-between mb-3">
                            <div class="text-xs font-medium text-gray-900">
                                Rp{{ number_format($job->reward, 0, ',', '.') }}
                            </div>
                            <div class="text-[10px] text-gray-500">
                                Sisa Kuota: {{ $job->participantCount }} / {{ $job->quota }}
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button wire:click="applyJob({{ $job->id }})" wire:loading.attr="disabled"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium py-2 px-3 rounded-md transition-colors duration-200 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                @if (Auth::user()->jobParticipants()->where('job_id', $job->id)->exists()) disabled @endif>
                                @if (Auth::user()->jobParticipants()->where('job_id', $job->id)->exists())
                                    <span class="opacity-50 cursor-not-allowed">
                                        <i class="fas fa-paper-plane mr-1"></i> Sudah Dilamar
                                    </span>
                                @else
                                    <span wire:loading.remove wire:target="applyJob({{ $job->id }})">
                                        <i class="fas fa-paper-plane mr-1"></i> Lamar
                                    </span>
                                @endif

                                <span wire:loading wire:target="applyJob({{ $job->id }})">
                                    <i class="fas fa-spinner fa-spin mr-1"></i> Proses...
                                </span>
                            </button>
                            <button wire:click="showJobDetail({{ $job->id }})"
                                class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium py-2 px-3 rounded-md transition-colors duration-200 focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                                <i class="fas fa-info-circle mr-1"></i> Detail
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Empty State (if needed) -->
        @if ($jobCampaigns->isEmpty())
            <div class="text-center py-12">
                <i class="fas fa-clipboard-list text-gray-300 text-5xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Kampanye</h3>
                <p class="text-sm text-gray-600">Saat ini belum ada kampanye yang tersedia. Silakan cek kembali nanti.
                </p>
            </div>
        @endif

        <!-- Job Detail Modal -->
        @if ($showModal)
            <div class="fixed inset-0 bg-black bg-opacity-50 z-40 flex items-center justify-center p-4">
                <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-hidden relative">
                    <!-- Modal Header -->
                    <div
                        class="p-4 border-b border-gray-100 flex justify-between items-center sticky top-0 bg-white z-10">
                        <h3 class="text-lg font-semibold text-gray-800">Detail Kampanye</h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Modal Content -->
                    <div class="overflow-y-auto p-4">
                        @if ($selectedJob && $jobDetail)
                            <!-- Campaign Header -->
                            <div class="flex items-center gap-4 mb-6">
                                <div
                                    class="flex-shrink-0 w-16 h-16 flex items-center justify-center bg-gray-50 rounded-lg">
                                    @switch($selectedJob->platform->value)
                                        @case('Instagram')
                                            <i
                                                class="fab fa-instagram text-4xl text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600"></i>
                                        @break

                                        @case('Facebook')
                                            <i class="fab fa-facebook text-4xl text-blue-600"></i>
                                        @break

                                        @case('Twitter')
                                            <i class="fab fa-twitter text-4xl text-sky-500"></i>
                                        @break

                                        @case('LinkedIn')
                                            <i class="fab fa-linkedin text-4xl text-blue-700"></i>
                                        @break

                                        @case('TikTok')
                                            <i class="fab fa-tiktok text-4xl text-black"></i>
                                        @break

                                        @case('Youtube')
                                            <i class="fab fa-youtube text-4xl text-red-600"></i>
                                        @break

                                        @default
                                            <i class="fas fa-briefcase text-4xl text-gray-600"></i>
                                    @endswitch
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $selectedJob->title }}</h4>
                                    <div class="flex items-center gap-3 mt-1">
                                        <span
                                            class="text-xs font-medium px-2 py-1 rounded-full
                                            @switch($selectedJob->type->value)
                                                @case('Komentar') bg-blue-100 text-blue-700 @break
                                                @case('Like') bg-green-100 text-green-700 @break
                                                @case('Follow') bg-purple-100 text-purple-700 @break
                                                @default bg-gray-100 text-gray-700
                                            @endswitch">
                                            {{ $selectedJob->type->value }}
                                        </span>
                                        <span class="text-xs text-gray-500">{{ $selectedJob->platform->value }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Campaign Details -->
                            <div class="space-y-6">
                                <!-- Reward & Quota -->
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="text-sm text-gray-600">Reward</p>
                                        <p class="text-lg font-semibold text-gray-900">
                                            Rp{{ number_format($selectedJob->reward, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-600">Sisa Kuota</p>
                                        <p class="text-lg font-semibold text-gray-900">
                                            {{ $selectedJob->participantCount }} /
                                            {{ $selectedJob->quota }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Campaign Image -->
                                @if ($jobDetail->image)
                                    <div>
                                        <p class="text-sm font-medium text-gray-700 mb-2">Gambar Kampanye</p>
                                        <img src="{{ asset('storage/' . $jobDetail->image) }}" alt="Campaign Image"
                                            class="w-full rounded-lg">
                                    </div>
                                @endif

                                <!-- Description -->
                                <div>
                                    <p class="text-sm font-medium text-gray-700 mb-2">Deskripsi</p>
                                    <div class="prose prose-sm max-w-none text-gray-600">
                                        {!! $jobDetail->description !!}
                                    </div>
                                </div>

                                <!-- URL Link -->
                                @if ($jobDetail->url_link)
                                    <div>
                                        <p class="text-sm font-medium text-gray-700 mb-2">URL Kampanye</p>
                                        <a href="{{ $jobDetail->url_link }}" target="_blank"
                                            class="text-blue-600 hover:text-blue-700 text-sm break-all">
                                            {{ $jobDetail->url_link }}
                                        </a>
                                    </div>
                                @endif

                                <!-- Date Info -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <p class="text-xs text-gray-600 mb-1">Tanggal Mulai</p>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ \Carbon\Carbon::parse($selectedJob->start_date)->format('d M Y') }}</p>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <p class="text-xs text-gray-600 mb-1">Tanggal Berakhir</p>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ \Carbon\Carbon::parse($selectedJob->end_date)->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
