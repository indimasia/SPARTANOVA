<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Riwayat Pekerjaan</h1>
            <p class="text-sm text-gray-600">Daftar riwayat pekerjaan yang telah Anda lamar</p>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kampanye
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Platform
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tipe
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Reward
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal Lamar
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($jobHistory as $history)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-8 w-8 flex items-center justify-center rounded-lg bg-gray-50">
                                            @switch($history->job->platform->value)
                                                @case('Instagram')
                                                    <i
                                                        class="fab fa-instagram text-xl text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600"></i>
                                                @break

                                                @case('Facebook')
                                                    <i class="fab fa-facebook text-xl text-blue-600"></i>
                                                @break

                                                @case('Twitter')
                                                    <i class="fab fa-twitter text-xl text-sky-500"></i>
                                                @break

                                                @case('LinkedIn')
                                                    <i class="fab fa-linkedin text-xl text-blue-700"></i>
                                                @break

                                                @case('TikTok')
                                                    <i class="fab fa-tiktok text-xl text-black"></i>
                                                @break

                                                @case('Youtube')
                                                    <i class="fab fa-youtube text-xl text-red-600"></i>
                                                @break

                                                @default
                                                    <i class="fas fa-briefcase text-xl text-gray-600"></i>
                                            @endswitch
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 line-clamp-2">
                                                {{ $history->job->title }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-xs font-medium text-gray-600">
                                        {{ $history->job->platform->value }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="text-xs font-medium px-2.5 py-1 rounded-full
                                        @switch($history->job->type->value)
                                            @case('Komentar') bg-blue-100 text-blue-700 @break
                                            @case('Like') bg-green-100 text-green-700 @break
                                            @case('Follow') bg-purple-100 text-purple-700 @break
                                            @default bg-gray-100 text-gray-700
                                        @endswitch">
                                        {{ $history->job->type->value }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        Rp{{ number_format($history->reward, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="text-xs font-medium px-2.5 py-1 rounded-full
                                        @switch($history->status)
                                            @case('pending') bg-yellow-100 text-yellow-700 @break
                                            @case('approved') bg-green-100 text-green-700 @break
                                            @case('rejected') bg-red-100 text-red-700 @break
                                            @case('completed') bg-blue-100 text-blue-700 @break
                                            @default bg-gray-100 text-gray-700
                                        @endswitch">
                                        {{ ucfirst($history->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600">
                                        {{ $history->created_at->format('d M Y H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if ($history->status === 'approved' && !$history->attachment)
                                        <button class="text-blue-600 hover:text-blue-900 text-xs font-medium">
                                            <i class="fas fa-upload mr-1"></i>
                                            Upload Bukti
                                        </button>
                                    @elseif($history->attachment)
                                        <button class="text-green-600 hover:text-green-900 text-xs font-medium">
                                            <i class="fas fa-eye mr-1"></i>
                                            Lihat Bukti
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-history text-gray-300 text-4xl mb-3"></i>
                                            <h3 class="text-sm font-medium text-gray-900 mb-1">Belum Ada Riwayat</h3>
                                            <p class="text-xs text-gray-500">Anda belum pernah melamar pekerjaan apapun.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
