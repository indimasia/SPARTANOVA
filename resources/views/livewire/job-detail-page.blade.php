<div class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden transition-all duration-300 hover:shadow-3xl">
            <!-- Page Header -->
            <div class="bg-gradient-to-r from-yellow-400 via-yellow-500 to-yellow-600 p-6 sm:p-10">
                <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-2">Detail Misi</h1>
                <p class="text-yellow-100">Informasi lengkap tentang misi yang tersedia</p>
            </div>
            
            <!-- Page Content -->
            <div class="p-6 sm:p-10">
                @if ($selectedJob)
                    <!-- Platform & Date -->
                    <div class="flex items-center justify-between mb-8">
                        <span class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                            @switch(strtolower($selectedJob->platform->value))
                                @case('facebook')
                                    <i class="fab fa-facebook text-blue-600 text-xl"></i>
                                @break
                                @case('instagram')
                                    <i class="fab fa-instagram text-pink-600 text-xl"></i>
                                @break
                                @case('twitter')
                                    <i class="fab fa-twitter text-blue-400 text-xl"></i>
                                @break
                                @case('tiktok')
                                    <i class="fab fa-tiktok text-black text-xl"></i>
                                @break
                                @case('youtube')
                                    <i class="fab fa-youtube text-red-600 text-xl"></i>
                                @break
                                @default
                                    <i class="fas fa-globe text-gray-600 text-xl"></i>
                            @endswitch
                            {{ $selectedJob->platform->value }}
                        </span>
                        <span class="text-sm text-gray-500">
                            {{ $selectedJob->created_at->format('d M Y') }} | {{ $selectedJob->createdBy->name ?? 'Admin' }}
                        </span>
                    </div>

                    <!-- Title -->
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-6">
                        {{ $selectedJob->title }}
                    </h2>

                    <!-- Type & Reward -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-10 space-y-4 sm:space-y-0">
                        <div class="flex items-center space-x-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                {{ $selectedJob->type->value }}
                            </span>
                            @if ($jobDetail && $jobDetail->url_link && $selectedJob->type->value !== 'View')
                                <a href="{{ $jobDetail->url_link }}" target="_blank" class="text-yellow-500 hover:text-yellow-600 text-sm font-medium flex items-center transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    Kunjungi Link
                                </a>
                            @endif
                        </div>
                        <span class="text-3xl font-bold text-yellow-500">
                            Rp {{ number_format($selectedJob->reward, 0, ',', '.') }}
                        </span>
                    </div>

                    <!-- Job Details -->
                    @if ($jobDetail)
                        @if (!empty($jobDetail->image) )
                        
                        <div class="mb-10">
                            <div class="relative w-full" style="padding-top: 40%;">
                                <img 
                                    src="{{ asset('storage/' . $jobDetail->image) }}" 
                                    alt="Job Image" 
                                    class="absolute inset-0 w-full h-full object-contain rounded-2xl"
                                >
                            </div>
                        </div>

                        
                        @else
                            
                        <div class="mb-10">
                            <div class="relative w-full" style="padding-top: 40%;">
                                <img 
                                    src="https://placehold.co/400x400?text=Tidak+Ada+Gambar" 
                                    alt="Job Image empty" 
                                    class="absolute inset-0 w-full h-full object-contain rounded-2xl"
                                >
                            </div>
                        </div>

                        @endif
                        @if ($jobDetail->description || $jobDetail->requirements || $selectedJob->instructions)
                            <div class="mb-10 p-6 bg-gray-50 border border-gray-200 rounded-2xl shadow-sm transition-all duration-300 hover:shadow-md">
                                <h4 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    Detail Misi
                                </h4>

                                <div class="space-y-8">
                                    @if ($jobDetail->description)
                                        <div>
                                            <h5 class="text-lg font-semibold text-gray-700 mb-3 flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Deskripsi Misi:
                                            </h5>
                                            <p class="text-gray-600 leading-relaxed whitespace-pre-line">
                                                {!! nl2br(e($jobDetail->description)) !!}
                                            </p>
                                        </div>
                                    @endif

                                    @if ($jobDetail->requirements)
                                        <div>
                                            <h5 class="text-lg font-semibold text-gray-700 mb-3 flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Persyaratan:
                                            </h5>
                                            <ul class="list-disc pl-5 text-gray-600 space-y-2">
                                                @foreach (explode("\n", $jobDetail->requirements) as $requirement)
                                                    <li>{{ $requirement }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @if ($selectedJob->instructions)
                                        <div>
                                            <h5 class="text-lg font-semibold text-gray-700 mb-3 flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                                </svg>
                                                Petunjuk:
                                            </h5>
                                            <p class="text-gray-600 leading-relaxed whitespace-pre-line">
                                                {!! ($selectedJob->instructions) !!}
                                            </p>
                                        </div>
                                    @endif

                                    @if ($jobDetail->caption)
                                        <div>
                                            <h5 class="text-lg font-semibold text-gray-700 mb-3 flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                                </svg>
                                                Caption:
                                            </h5>
                                            <p class="text-gray-600 leading-relaxed whitespace-pre-line">
                                                {!! nl2br(e($jobDetail->caption)) !!}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Specific Information -->
                        @php
                            $specificAttributes = collect([
                                'specific_gender' => 'Jenis Kelamin',
                                'specific_generation' => 'Generasi',
                                'specific_interest' => 'Minat Khusus',
                            ])->filter(fn($label, $key) => $jobDetail->$key);
                        @endphp

                        @if ($specificAttributes->isNotEmpty())
                            <div class="mb-10 p-6 bg-gray-50 border border-gray-200 rounded-2xl shadow-sm transition-all duration-300 hover:shadow-md">
                                <h5 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                    Informasi Tambahan:
                                </h5>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @foreach ($specificAttributes as $key => $label)
                                        <div class="bg-white p-4 rounded-lg shadow-sm">
                                            <h6 class="text-sm font-semibold text-gray-800 mb-2">{{ $label }}:</h6>
                                            <div class="text-gray-600">
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
                    @endif

                    <!-- Quota -->
                    <div class="mb-10">
                        <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                            <span>Kuota Tersisa</span>
                            <span>{{ $selectedJob->participantCount }} / {{ $selectedJob->quota }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                            <div class="bg-yellow-500 h-3 rounded-full transition-all duration-300 ease-in-out"
                                style="width: {{ $selectedJob->quota > 0 ? ($selectedJob->participantCount / $selectedJob->quota) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                        <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm transition-all duration-300 hover:shadow-md">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Tanggal Mulai:
                            </h4>
                            <p class="text-gray-600">{{ $selectedJob->start_date }}</p>
                        </div>
                        <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm transition-all duration-300 hover:shadow-md">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Tanggal Berakhir:
                            </h4>
                            <p class="text-gray-600">{{ $selectedJob->end_date }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Page Footer -->
            <div class="p-6 sm:p-10 border-t border-gray-200 flex justify-end gap-4">
                @if (!Auth::user()->jobParticipants()->where('job_id', $selectedJob->id)->exists())
                <button type="button" onclick="confirmApplyJob({{ $selectedJob->id }})"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    Ambil Misi
                </button>
                @elseif (Auth::user()->jobParticipants()->where('job_id', $selectedJob->id)->exists() && $selectedJob->type->value === 'Posting')
                <button id="openModalBtn" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors duration-200">
                    <i class="fas fa-external-link-alt mr-2"></i>
                    Posting Sekarang
                </button>
                @elseif (Auth::user()->jobParticipants()->where('job_id', $selectedJob->id)->exists() && ($selectedJob->type->value === 'View' || $selectedJob->type->value === 'Rating & Review' || $selectedJob->type->value === 'Download, Rating, Review' || $selectedJob->type->value === 'Subscribe/Follow' || $selectedJob->type->value === 'Follow Marketplace'))
                <button id="openViewModalBtn" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors duration-200">
                    <i class="fas fa-external-link-alt mr-2"></i>
                    Lihat Sekarang
                </button>
                @elseif (Auth::user()->jobParticipants()->where('job_id', $selectedJob->id)->exists() && $selectedJob->type->value === 'Komentar')
                <button id="openCommentModalBtn" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors duration-200">
                    <i class="fas fa-comment mr-2"></i>
                    Komentar Sekarang
                </button>
                @elseif (Auth::user()->jobParticipants()->where('job_id', $selectedJob->id)->exists() && $selectedJob->type->value === 'Selling')
                    <button id="openSellingModalBtn" class="mt-4 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-colors duration-200">
                        <i class="fas fa-link mr-2"></i>
                        Link Selling
                    </button>
                @endif

                <div id="missionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
                    <div class="bg-white rounded-lg p-6 w-full max-w-md">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold">Kerjakan Misi</h2>
                            <button id="closeModalBtn" class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <img id="missionImage" src="{{ asset('storage/' . $jobDetail->image) }}"  alt="Mission Image" class="w-full h-48 object-cover rounded-lg mb-4">
                        <p class="text-sm text-gray-500 mb-2">Instruksi:</p>
                        <div class="bg-gray-100 p-3 rounded-lg relative mb-4">
                            <p id="missionInstruction" class="text-sm text-gray-700 whitespace-pre-wrap"></p>
                        </div>
                        <p class="text-sm text-gray-500 mb-2">Caption:</p>
                        <div class="bg-gray-100 p-3 rounded-lg relative mb-4">
                            <p id="missionCaption" class="text-sm text-gray-700 whitespace-pre-wrap"></p>
                            <button id="copyBtn" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">
                                <i class="far fa-copy"></i>
                            </button>
                        </div>
                        <p id="copiedMessage" class="text-sm text-green-500 mb-4 hidden">Caption copied to clipboard!</p>
                        <div class="flex gap-2">
                            <a href="javascript:void(0);" 
                                id="shareButton-{{ $jobDetail->id }}" 
                                data-image-url="{{ asset('storage/' . $jobDetail->image) }}"
                                class="flex-1 inline-flex items-center justify-center px-3 py-3 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700 transition-colors duration-200">
                                <i class="fas fa-external-link-alt mr-2"></i>
                                Posting Sekarang
                            </a>
                            <a href="{{ route('pasukan.riwayat-pekerjaan') }}"
                                class="flex-1 inline-flex items-center justify-center px-3 py-3 text-sm font-medium text-blue-600 bg-white border border-blue-600 rounded hover:bg-blue-50 transition-colors duration-200">
                                <i class="fas fa-upload mr-2"></i>
                                Upload Bukti Posting
                            </a>
                        </div>
                    </div>
                </div>

                <div id="commentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
                    <div class="bg-white rounded-lg p-6 w-full max-w-md">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold">Instruksi Komentar</h2>
                            <button id="closeCommentModalBtn" class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <p class="text-sm text-gray-500 mb-2">Instruksi:</p>
                        <div class="bg-gray-100 p-3 rounded-lg mb-4">
                            <p id="commentInstruction" class="text-sm text-gray-700 whitespace-pre-wrap"></p>
                        </div>
                        <p class="text-sm text-gray-500 mb-2">Link:</p>
                        <div class="bg-gray-100 p-3 rounded-lg relative mb-4">
                            <p id="commentLink" class="text-sm text-gray-700 whitespace-pre-wrap"></p>
                            <button id="copyCommentBtn" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">
                                <i class="far fa-copy"></i>
                            </button>
                        </div>
                        <p id="commentCopiedMessage" class="text-sm text-green-500 mb-4 hidden">Link copied to clipboard!</p>
                        <div class="flex gap-2">
                            <a id="commentNowBtn" href="#" target="_blank"
                                class="flex-1 inline-flex items-center justify-center px-3 py-3 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700 transition-colors duration-200">
                                <i class="fas fa-external-link-alt mr-2"></i>
                                Komentar Sekarang
                            </a>
                        </div>
                    </div>
                </div>

                <div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
                    <div class="bg-white rounded-lg p-6 w-full max-w-md">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold">Instruksi View</h2>
                            <button id="closeViewModalBtn" class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <p class="text-sm text-gray-500 mb-2">Instruksi:</p>
                        <div class="bg-gray-100 p-3 rounded-lg mb-4">
                            <p id="viewInstruction" class="text-sm text-gray-700 whitespace-pre-wrap"></p>
                        </div>
                        <p class="text-sm text-gray-500 mb-2">Link:</p>
                        <div class="bg-gray-100 p-3 rounded-lg relative mb-4">
                            <p id="viewLink" class="text-sm text-gray-700 whitespace-pre-wrap"></p>
                            <button id="copyViewBtn" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">
                                <i class="far fa-copy"></i>
                            </button>
                        </div>
                        <p id="viewCopiedMessage" class="text-sm text-green-500 mb-4 hidden">Link copied to clipboard!</p>
                        <div class="flex gap-2">
                            <a id="viewNowBtn" href="#" target="_blank"
                                class="flex-1 inline-flex items-center justify-center px-3 py-3 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700 transition-colors duration-200">
                                <i class="fas fa-external-link-alt mr-2"></i>
                                View Sekarang
                            </a>
                        </div>
                    </div>
                </div>

                <div id="sellingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
                    <div class="bg-white rounded-lg p-6 w-full max-w-md">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold">Link Selling</h2>
                            <button id="closeSellingModalBtn" class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <img id="sellingImage" src="{{ asset('storage/' . $jobDetail->image) }}"  alt="Mission Image" class="w-full h-48 object-cover rounded-lg mb-4">
                        <p class="text-sm text-gray-500 mb-2">Caption:</p>
                        <div class="bg-gray-100 p-3 rounded-lg relative mb-4">
                            <p id="sellingCaption" class="text-sm text-gray-700 whitespace-pre-wrap"></p>
                        </div>
                        <p class="text-sm text-gray-500 mb-2">Instruksi:</p>
                        <div class="bg-gray-100 p-3 rounded-lg relative mb-4">
                            <p id="sellingInstruction" class="text-sm text-gray-700 whitespace-pre-wrap"></p>
                        </div>
                        <p class="text-sm text-gray-500 mb-2">Link:</p>
                        <div class="bg-gray-100 p-3 rounded-lg relative mb-4">
                            <p id="sellingLink" class="text-sm text-gray-700 whitespace-pre-wrap"></p>
                            <button id="copySellingBtn" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">
                                <i class="far fa-copy"></i>
                            </button>
                        </div>
                        <p id="sellingCopiedMessage" class="text-sm text-green-500 mb-4 hidden">Link copied to clipboard!</p>
                        <p class="text-sm text-gray-500 mb-2">Deskripsi:</p>
                        <div class="bg-gray-100 p-3 rounded-lg relative mb-4">
                            <p id="sellingDescription" class="text-sm text-gray-700 whitespace-pre-wrap"></p>
                        </div>
                        <div class="flex gap-2">
                            <a href="javascript:void(0);" 
                                id="shareButton-{{ $jobDetail->id }}" 
                                data-image-url="{{ asset('storage/' . $jobDetail->image) }}"
                                class="flex-1 inline-flex items-center justify-center px-3 py-3 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700 transition-colors duration-200">
                                <i class="fas fa-external-link-alt mr-2"></i>
                                Posting Sekarang
                            </a>
                            <a href="{{ route('pasukan.riwayat-pekerjaan') }}"
                                class="flex-1 inline-flex items-center justify-center px-3 py-3 text-sm font-medium text-blue-600 bg-white border border-blue-600 rounded hover:bg-blue-50 transition-colors duration-200">
                                <i class="fas fa-upload mr-2"></i>
                                Upload Bukti Posting
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('missionModal');
        const openModalBtn = document.getElementById('openModalBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const copyBtn = document.getElementById('copyBtn');
        const shareBtn = document.getElementById('shareBtn');
        const missionImage = document.getElementById('missionImage');
        const missionCaption = document.getElementById('missionCaption');
        const copiedMessage = document.getElementById('copiedMessage');
    
        let currentMission = { caption: '', imageUrl: '' };

        function openModal(caption, imageUrl, instructions) {
            currentMission = { caption, imageUrl };
            missionImage.src = imageUrl;
            missionCaption.textContent = caption;
            missionInstruction.textContent = instructions;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        openModalBtn.addEventListener('click', () => openModal('{{ $jobDetail->caption }}', '{{ asset('storage/' . $jobDetail->image) }}', '{{ $selectedJob->instructions }}'));
        closeModalBtn.addEventListener('click', closeModal);

        copyBtn.addEventListener('click', () => {
            navigator.clipboard.writeText(currentMission.caption).then(() => {
                copiedMessage.classList.remove('hidden');
                setTimeout(() => copiedMessage.classList.add('hidden'), 2000);
            });
        });

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
            const imageUrl = button.dataset.imageUrl;
            await shareImageAndContent(imageUrl);
        });
    });

    // Fungsi untuk berbagi konten
    async function shareImageAndContent(imageUrl) {
    try {
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
<script>
    const viewModal = document.getElementById('viewModal');
    const openViewModalBtn = document.getElementById('openViewModalBtn');
    const closeViewModalBtn = document.getElementById('closeViewModalBtn');
    const viewInstruction = document.getElementById('viewInstruction');
    const viewLink = document.getElementById('viewLink');
    const viewNowBtn = document.getElementById('viewNowBtn');
    const copyViewBtn = document.getElementById('copyViewBtn');
    const viewCopiedMessage = document.getElementById('viewCopiedMessage');

    function openViewModal(instructions, url) {
        viewInstruction.textContent = instructions;
        viewLink.textContent = url;
        viewNowBtn.href = url;
        viewModal.classList.remove('hidden');
        viewModal.classList.add('flex');
    }

    function closeViewModal() {
        viewModal.classList.add('hidden');
        viewModal.classList.remove('flex');
    }

    openViewModalBtn.addEventListener('click', () => openViewModal(
        '{{ $selectedJob->instructions }}',
        '{{ $jobDetail->url_link }}'
    ));
    closeViewModalBtn.addEventListener('click', closeViewModal);

    copyViewBtn.addEventListener('click', () => {
        navigator.clipboard.writeText(viewLink.textContent).then(() => {
            viewCopiedMessage.classList.remove('hidden');
            setTimeout(() => viewCopiedMessage.classList.add('hidden'), 2000);
        });
    });

</script>
<script>
    const commentModal = document.getElementById('commentModal');
    const openCommentModalBtn = document.getElementById('openCommentModalBtn');
    const closeCommentModalBtn = document.getElementById('closeCommentModalBtn');
    const commentInstruction = document.getElementById('commentInstruction');
    const commentLink = document.getElementById('commentLink');
    const commentNowBtn = document.getElementById('commentNowBtn');
    const copyCommentBtn = document.getElementById('copyCommentBtn');
    const commentCopiedMessage = document.getElementById('commentCopiedMessage');

    function openCommentModal(instructions, url) {
        commentInstruction.textContent = instructions;
        commentLink.textContent = url;
        commentNowBtn.href = url;
        commentModal.classList.remove('hidden');
        commentModal.classList.add('flex');
    }

    function closeCommentModal() {
        commentModal.classList.add('hidden');
        commentModal.classList.remove('flex');
    }

    openCommentModalBtn.addEventListener('click', () => openCommentModal(
        '{{ $selectedJob->instructions }}',
        '{{ $jobDetail->url_link }}'
    ));
    closeCommentModalBtn.addEventListener('click', closeCommentModal);

    copyCommentBtn.addEventListener('click', () => {
        navigator.clipboard.writeText(commentLink.textContent).then(() => {
            commentCopiedMessage.classList.remove('hidden');
            setTimeout(() => commentCopiedMessage.classList.add('hidden'), 2000);
        });
    });
</script>

<script>
    // Selling Modal
const sellingModal = document.getElementById('sellingModal');
const openSellingModalBtn = document.getElementById('openSellingModalBtn');
const closeSellingModalBtn = document.getElementById('closeSellingModalBtn');
const sellingLink = document.getElementById('sellingLink');
const sellingCaption = document.getElementById('sellingCaption');
const sellingInstruction = document.getElementById('sellingInstruction');
const sellingDescription = document.getElementById('sellingDescription');
const sellingImage = document.getElementById('sellingImage');
const copySellingBtn = document.getElementById('copySellingBtn');
const sellingCopiedMessage = document.getElementById('sellingCopiedMessage');

let currentSellingLink = '';

function openSellingModal(link, caption, instructions, description, imageUrl) {
    currentSellingLink = link;
    sellingLink.textContent = link;
    sellingCaption.textContent = caption;
    sellingInstruction.textContent = instructions;
    sellingDescription.textContent = description;
    sellingImage.src = imageUrl;
    sellingModal.classList.remove('hidden');
    sellingModal.classList.add('flex');
}

function closeSellingModal() {
    sellingModal.classList.add('hidden');
    sellingModal.classList.remove('flex');
}
openSellingModalBtn.addEventListener('click', () => openSellingModal( '{{ $jobDetail->url_link }}', '{{ $jobDetail->caption }}', '{{ $selectedJob->instructions }}', '{{ $jobDetail->description }}', '{{ asset('storage/' . $jobDetail->image) }}'));
closeSellingModalBtn.addEventListener('click', closeSellingModal);

    copySellingBtn.addEventListener('click', () => {
        navigator.clipboard.writeText(currentSellingLink).then(() => {
            sellingCopiedMessage.classList.remove('hidden');
            setTimeout(() => sellingCopiedMessage.classList.add('hidden'), 2000);
        });
    });

    document.querySelectorAll('[id^="shareButton-"]').forEach(button => {
        button.addEventListener('click', async () => {
            const imageUrl = button.dataset.imageUrl;
            await shareImageAndContent(imageUrl);
        });
    });

    // Fungsi untuk berbagi konten
    async function shareImageAndContent(imageUrl) {
        try {
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