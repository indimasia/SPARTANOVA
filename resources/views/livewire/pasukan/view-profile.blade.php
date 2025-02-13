<div class="min-h-screen">
    <div class="container mx-auto px-4">
        <div class="relative mx-auto">
            <!-- Profile Card -->
            {{-- <div class="backdrop-blur-xl bg-white/30 rounded-3xl shadow-2xl overflow-hidden"> --}}
            <!-- Cover & Avatar Section -->
            {{-- <div class="relative h-32">
                    <div class="absolute -bottom-10 left-10">
                        <div class="relative">
                            <img src="https://gravatar.com/avatar/81afa25f705e6146fc697c4e9c7e2ef4?s=400&d=robohash&r=x"
                                 alt="Profile"
                                 class="w-32 h-32 rounded-full border-4 border-white shadow-lg object-cover">
                            <span class="absolute bottom-2 right-2 w-4 h-4 bg-green-400 rounded-full border-2 border-white"></span>
                        </div>
                    </div>
                </div> --}}

            <!-- Profile Info -->
            <div class="pt-16 pb-8 px-8">
                @livewire('update-user-data')
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold">Pasukan User</h1>
                        <p class="">{{ $role }}</p>
                    </div>
                    <a href="{{ route('pasukan.profile.edit') }}"
                        class="px-6 py-2 bg-yellow-600 hover:bg-yellow-700 rounded-full transition-colors duration-200 shadow-lg text-white">
                        Edit Profile
                    </a>
                </div>

                <!-- Stats -->
                {{-- <div class="flex gap-6 mt-8">
                        <div class="text-center">
                            <span class="block text-2xl font-bold">{{ $job_done }}</span>
                            <span class="">Job Approve</span>
                        </div>
                        <div class="text-center">
                            <span class="block text-2xl font-bold">{{ $reward }}</span>
                            <span class="">Reward</span>
                        </div>
                        <div class="text-center">
                            <span class="block text-2xl font-bold">{{ $contact_wa }}</span>
                            <span class="">Contact WA</span>
                        </div>
                    </div> --}}

                <div x-data="{ selectedTab: 'groups' }" class="w-full my-4">
                    <div @keydown.right.prevent="$focus.wrap().next()" @keydown.left.prevent="$focus.wrap().previous()"
                        class="flex gap-2 overflow-x-auto border-b border-neutral-300 dark:border-neutral-700"
                        role="tablist" aria-label="tab options">
                        <button @click="selectedTab = 'groups'" :aria-selected="selectedTab === 'groups'"
                            :tabindex="selectedTab === 'groups' ? '0' : '-1'"
                            :class="selectedTab === 'groups' ?
                                'border-b-2 border-yellow-600 text-yellow-600' :
                                'font-medium hover:border-b-2 hover:border-yellow-600 hover:text-yellow-600'"
                            class="flex h-min items-center gap-2 px-4 py-2 text-sm transition-colors duration-200"
                            type="button" role="tab" aria-controls="tabpanelGroups">
                            <i class="fas fa-user-circle size-4"></i>
                            Informasi Pribadi
                        </button>
                        <button @click="selectedTab = 'likes'" :aria-selected="selectedTab === 'likes'"
                            :tabindex="selectedTab === 'likes' ? '0' : '-1'"
                            :class="selectedTab === 'likes' ?
                                'border-b-2 border-yellow-600 text-yellow-600' :
                                'font-medium hover:border-b-2 hover:border-yellow-600 hover:text-yellow-600'"
                            class="flex h-min items-center gap-2 px-4 py-2 text-sm transition-colors duration-200"
                            type="button" role="tab" aria-controls="tabpanelLikes">
                            <i class="fas fa-map-marker-alt size-4"></i>
                            Informasi Lokasi
                        </button>
                        <button @click="selectedTab = 'saved'" :aria-selected="selectedTab === 'saved'"
                            :tabindex="selectedTab === 'saved' ? '0' : '-1'"
                            :class="selectedTab === 'saved' ?
                                'border-b-2 border-yellow-600 text-yellow-600' :
                                'font-medium hover:border-b-2 hover:border-yellow-600 hover:text-yellow-600'"
                            class="flex h-min items-center gap-2 px-4 py-2 text-sm transition-colors duration-200"
                            type="button" role="tab" aria-controls="tabpanelSaved">
                            <i class="fas fa-info-circle size-4"></i>
                            Informasi Akun
                        </button>
                    </div>
                    <div class="px-2 py-4">
                        <div x-show="selectedTab === 'groups'" id="tabpanelGroups" role="tabpanel" aria-label="groups">
                            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-user w-5"></i>
                                        <span>{{ $name }}</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-envelope w-5"></i>
                                        <span>{{ $email }}</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-phone w-5"></i>
                                        <span>{{ $phone }}</span>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-heart w-5"></i>
                                            <span>Interests:</span>
                                        </div>
                                        @if (!empty($interest))
                                            <ul class="list-disc list-inside ml-8">
                                                @foreach ($interest as $item)
                                                    <li>{{ $item }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-venus-mars w-5"></i>
                                        <span>{{ $gender }}</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-calendar w-5"></i>
                                        <span>{{ $date_of_birth }}</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-user w-5"></i>
                                        <span>{{ $generation_category }}</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-qrcode w-5"></i>
                                        <span x-data="{ copied: false }">
                                            <span>{{ $referral_code }}</span>
                                            <button @click="
                                                let url = '{{ env('APP_URL') }}/pasukan/register/{{ $referral_code }}';
                                                navigator.clipboard.writeText(url);
                                                copied = true;
                                                setTimeout(() => copied = false, 2000);
                                            " class="ml-2 px-2 py-1 bg-gray-200 rounded text-sm">
                                                Copy
                                            </button>
                                            <span x-show="copied" class="text-green-500 text-sm ml-2">Copied!</span>
                                        </span>
                                    </div>                                                                
                                </div>
                            </div>
                        </div>
                        <div x-show="selectedTab === 'likes'" id="tabpanelLikes" role="tabpanel" aria-label="likes">
                            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-map w-5"></i>
                                        <span>PROVINSI {{ $province_nama }}</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-city w-5"></i>
                                        <span>KABUPATEN/KOTA {{ $regency_nama }}</span>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-building w-5"></i>
                                        <span>KECAMATAN {{ $district_nama }}</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-home w-5"></i>
                                        <span>DESA/KELURAHAN {{ $village_nama }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div x-show="selectedTab === 'saved'" id="tabpanelSaved" role="tabpanel" aria-label="saved">
                            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-info-circle w-5"></i>
                                        <span>{{ $status }}</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-calendar-alt w-5"></i>
                                        <span>Account created on {{ $created_at }}</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-clock w-5"></i>
                                        <span>Last account update: {{ $updated_at }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Profile Details -->


                <!-- Social Links -->
                <div class="mt-8 flex gap-4">
                    @if ($facebookAccounts !== 'Tidak punya akun')
                        <a href="{{ $facebookAccounts }}" target="_blank"
                            class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition-colors duration-200">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    @endif
                    @if ($instagramAccounts !== 'Tidak punya akun')
                        <a href="{{ $instagramAccounts }}" target="_blank"
                            class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition-colors duration-200">
                            <i class="fab fa-instagram"></i>
                        </a>
                    @endif
                    @if ($twitterAccounts !== 'Tidak punya akun')
                        <a href="{{ $twitterAccounts }}" target="_blank"
                            class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition-colors duration-200">
                            <i class="fab fa-twitter"></i>
                        </a>
                    @endif
                    @if ($youtubeAccounts !== 'Tidak punya akun')
                        <a href="{{ $youtubeAccounts }}" target="_blank"
                            class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition-colors duration-200">
                            <i class="fab fa-youtube"></i>
                        </a>
                    @endif
                    @if ($tiktokAccounts !== 'Tidak punya akun')
                        <a href="{{ $tiktokAccounts }}" target="_blank"
                            class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition-colors duration-200">
                            <i class="fab fa-tiktok"></i>
                        </a>
                    @endif
                    @if ($googleAccounts !== 'Tidak punya akun')
                        <a href="{{ $googleAccounts }}" target="_blank"
                            class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition-colors duration-200">
                            <i class="fab fa-google"></i>
                        </a>
                    @endif
                </div>
            </div>
            {{-- </div> --}}
        </div>
    </div>
</div>
